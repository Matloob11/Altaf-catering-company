<?php
/**
 * Simple SMTP Email Sender
 * Lightweight alternative to PHPMailer for basic SMTP functionality
 */

class SimpleMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $encryption;
    private $from_email;
    private $from_name;
    private $socket;
    private $error;
    
    public function __construct($config) {
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->encryption = $config['encryption'] ?? 'tls';
        $this->from_email = $config['from_email'];
        $this->from_name = $config['from_name'];
    }
    
    public function send($to, $subject, $body, $isHTML = true) {
        try {
            // Connect to SMTP server
            if (!$this->connect()) {
                return false;
            }
            
            // Send SMTP commands
            $this->command("EHLO " . $this->host);
            
            // Start TLS if needed
            if ($this->encryption === 'tls' && $this->port == 587) {
                $this->command("STARTTLS");
                stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                $this->command("EHLO " . $this->host);
            }
            
            // Authenticate
            $this->command("AUTH LOGIN");
            $this->command(base64_encode($this->username));
            $this->command(base64_encode($this->password));
            
            // Send email
            $this->command("MAIL FROM: <{$this->from_email}>");
            $this->command("RCPT TO: <{$to}>");
            $this->command("DATA");
            
            // Email headers and body
            $message = $this->buildMessage($to, $subject, $body, $isHTML);
            $this->send_data($message);
            $this->send_data(".");
            
            // Close connection
            $this->command("QUIT");
            fclose($this->socket);
            
            return true;
            
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            if ($this->socket) {
                fclose($this->socket);
            }
            return false;
        }
    }
    
    private function connect() {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        $protocol = ($this->encryption === 'ssl') ? 'ssl://' : '';
        $this->socket = @stream_socket_client(
            $protocol . $this->host . ':' . $this->port,
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if (!$this->socket) {
            $this->error = "Connection failed: $errstr ($errno)";
            return false;
        }
        
        // Read server greeting
        $this->get_response();
        return true;
    }
    
    private function command($cmd) {
        fwrite($this->socket, $cmd . "\r\n");
        return $this->get_response();
    }
    
    private function send_data($data) {
        fwrite($this->socket, $data . "\r\n");
        return $this->get_response();
    }
    
    private function get_response() {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        return $response;
    }
    
    private function buildMessage($to, $subject, $body, $isHTML) {
        $boundary = md5(time());
        
        $headers = [];
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        $headers[] = "To: <{$to}>";
        $headers[] = "Subject: {$subject}";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
        $headers[] = "X-Mailer: Altaf Catering Mailer";
        
        $message = implode("\r\n", $headers) . "\r\n\r\n";
        
        // Plain text version
        $message .= "--{$boundary}\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= strip_tags($body) . "\r\n\r\n";
        
        // HTML version
        if ($isHTML) {
            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message .= $body . "\r\n\r\n";
        }
        
        $message .= "--{$boundary}--";
        
        return $message;
    }
    
    public function getError() {
        return $this->error;
    }
}
