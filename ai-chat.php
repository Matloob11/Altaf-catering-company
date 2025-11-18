<?php
// Load secure configuration
require_once 'config.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'chat') {
    header('Content-Type: application/json');
    
    $message = $_POST['message'] ?? '';
    if (empty($message)) {
        echo json_encode(['error' => 'No message provided']);
        exit;
    }
    
    // Business context for AI
    $business_context = [
        'name' => 'Altaf Catering Company',
        'phone' => '+92 303 9907296',
        'email' => 'altafcatering@gmail.com',
        'address' => 'MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan'
    ];
    
    $system_prompt = "You are a helpful AI assistant for Altaf Catering Company, a premium catering service in Pakistan. Be friendly, professional, and helpful. Provide information about catering services, menu, packages, and booking. Keep responses concise but informative.";
    
    $data = [
        'model' => 'openai/gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $message]
        ]
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://openrouter.ai/api/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . OPENROUTER_API_KEY,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($response === false) {
        echo json_encode(['error' => 'Connection failed']);
    } else {
        $result = json_decode($response, true);
        if ($http_code === 200 && isset($result['choices'][0]['message']['content'])) {
            echo json_encode(['success' => true, 'message' => $result['choices'][0]['message']['content']]);
        } else {
            echo json_encode(['error' => 'API Error: ' . ($result['error']['message'] ?? 'Unknown error')]);
        }
    }
    exit;
}

// Load business data for display
$business_context = [
    'name' => 'Altaf Catering Company',
    'description' => 'Premium catering services in Pakistan',
    'contact' => [
        'phone' => '+92 303 9907296',
        'email' => 'altafcatering@gmail.com',
        'address' => 'MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Assistant - Altaf Catering</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .chat-container {
            max-width: 800px;
            width: 100%;
            height: 600px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .chat-header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid white;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .message {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
        }
        
        .message.user {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }
        
        .message.user .message-avatar {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
        }
        
        .message-content {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .message.user .message-content {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
        }
        
        .chat-input {
            padding: 20px;
            background: white;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        #user-input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
        }
        
        #user-input:focus {
            border-color: #FEA116;
            box-shadow: 0 0 0 3px rgba(254, 161, 22, 0.1);
        }
        
        .send-btn {
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(254, 161, 22, 0.4);
        }
        
        .send-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .suggestion-btn {
            padding: 8px 15px;
            background: #e9ecef;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .suggestion-btn:hover {
            background: #FEA116;
            color: white;
        }
        
        .typing-indicator {
            display: none;
            padding: 10px;
            background: white;
            border-radius: 15px;
            width: fit-content;
        }
        
        .typing-indicator span {
            height: 8px;
            width: 8px;
            background: #999;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
            animation: typing 1.4s infinite;
        }
        
        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }
        
        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }
        
        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-8px);
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .chat-container {
                height: calc(100vh - 20px);
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <img src="img/logo.png" alt="Altaf Catering">
            <div>
                <h5>Altaf Catering AI Assistant</h5>
                <small>🤖 Powered by Advanced AI</small>
            </div>
        </div>
        
        <!-- Messages -->
        <div class="chat-messages" id="chatMessages">
            <!-- Welcome Message -->
            <div class="message bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div class="message-content">
                        <strong>Hello! 👋</strong><br>
                        I'm your Altaf Catering AI assistant. I can help you with menu items, packages, booking, and more!
                    </div>
                    <div class="suggestions">
                        <button class="suggestion-btn" onclick="askQuestion('What are your catering packages?')">
                            📦 Packages
                        </button>
                        <button class="suggestion-btn" onclick="askQuestion('Show me your menu')">
                            🍽️ Menu
                        </button>
                        <button class="suggestion-btn" onclick="askQuestion('How do I book?')">
                            📅 Booking
                        </button>
                        <button class="suggestion-btn" onclick="askQuestion('Contact information?')">
                            📞 Contact
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Input -->
        <div class="chat-input">
            <input type="text" id="user-input" placeholder="Ask me anything about Altaf Catering..." />
            <button class="send-btn" id="send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById("chatMessages");
        const userInput = document.getElementById("user-input");
        const sendBtn = document.getElementById("send-btn");
        
        // Send message function
        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;

            // Add user message
            addMessage(message, "user");
            userInput.value = "";
            
            // Disable send button and show loading
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            // Show typing indicator
            showTyping();

            try {
                const formData = new FormData();
                formData.append('action', 'chat');
                formData.append('message', message);
                
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                hideTyping();
                
                if (data.success) {
                    addMessage(data.message, "bot");
                } else {
                    addMessage("❌ " + (data.error || "Sorry, I couldn't process that. Please try again!"), "bot");
                }
                
            } catch (error) {
                console.error('Error:', error);
                hideTyping();
                addMessage("❌ Connection error. Please try again or contact us at <?php echo $business_context['contact']['phone']; ?>", "bot");
            } finally {
                // Re-enable send button
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            }
        }

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${sender}`;

            const avatar = document.createElement("div");
            avatar.className = "message-avatar";
            avatar.innerHTML = `<i class="fas fa-${sender === 'user' ? 'user' : 'robot'}"></i>`;

            const content = document.createElement("div");
            content.className = "message-content";
            content.innerHTML = text;

            const wrapper = document.createElement("div");
            wrapper.appendChild(content);

            messageDiv.appendChild(avatar);
            messageDiv.appendChild(wrapper);

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Show typing indicator
        function showTyping() {
            const typingDiv = document.createElement("div");
            typingDiv.className = "message bot";
            typingDiv.id = "typing";

            const avatar = document.createElement("div");
            avatar.className = "message-avatar";
            avatar.innerHTML = '<i class="fas fa-robot"></i>';

            const indicator = document.createElement("div");
            indicator.className = "typing-indicator";
            indicator.style.display = "block";
            indicator.innerHTML = '<span></span><span></span><span></span>';

            const wrapper = document.createElement("div");
            wrapper.appendChild(indicator);

            typingDiv.appendChild(avatar);
            typingDiv.appendChild(wrapper);

            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Hide typing indicator
        function hideTyping() {
            const typing = document.getElementById("typing");
            if (typing) {
                typing.remove();
            }
        }

        // Quick question function
        function askQuestion(question) {
            userInput.value = question;
            sendMessage();
        }

        // Enter key support
        userInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });

        // Focus input
        userInput.focus();
    </script>
</body>
</html>