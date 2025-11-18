<?php
/**
 * Resend Email Service Integration
 * Simple and reliable email sending using Resend.com API
 */

class ResendMailer {
    private $apiKey;
    private $fromEmail;
    private $fromName;
    private $error;
    
    public function __construct($apiKey, $fromEmail, $fromName) {
        $this->apiKey = $apiKey;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }
    
    /**
     * Send email using Resend API
     */
    public function send($to, $subject, $htmlBody) {
        $url = 'https://api.resend.com/emails';
        
        $data = [
            'from' => $this->fromName . ' <' . $this->fromEmail . '>',
            'to' => [$to],
            'subject' => $subject,
            'html' => $htmlBody
        ];
        
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        
        curl_close($ch);
        
        if ($curlError) {
            $this->error = 'cURL Error: ' . $curlError;
            return false;
        }
        
        if ($httpCode !== 200) {
            $this->error = 'HTTP Error ' . $httpCode . ': ' . $response;
            return false;
        }
        
        $result = json_decode($response, true);
        
        if (isset($result['id'])) {
            return true;
        } else {
            $this->error = 'API Error: ' . ($result['message'] ?? 'Unknown error');
            return false;
        }
    }
    
    /**
     * Get last error message
     */
    public function getError() {
        return $this->error;
    }
}
