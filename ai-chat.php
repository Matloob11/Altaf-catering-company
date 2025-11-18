<?php
// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Load business data for AI context
$business_context = [
    'name' => 'Altaf Catering Company',
    'description' => 'Premium catering services in Pakistan',
    'services' => [],
    'menu' => [],
    'packages' => [],
    'contact' => [
        'phone' => '+92 303 9907296',
        'email' => 'altafcatering@gmail.com',
        'address' => 'MM Farm House Sharif Medical Jati Umrah Road, Karachi, Pakistan'
    ]
];

// Load services
if (file_exists('admin/data/services.json')) {
    $services = json_decode(file_get_contents('admin/data/services.json'), true);
    if (is_array($services)) {
        $business_context['services'] = array_map(function($s) {
            return $s['title'] . ': ' . $s['description'];
        }, $services);
    }
}

// Load menu items
if (file_exists('admin/data/menu.json')) {
    $menu = json_decode(file_get_contents('admin/data/menu.json'), true);
    if (is_array($menu)) {
        $business_context['menu'] = array_map(function($m) {
            return $m['name'] . ' - Rs. ' . $m['price'];
        }, array_slice($menu, 0, 20)); // First 20 items
    }
}

// Load packages
if (file_exists('admin/data/packages.json')) {
    $packages = json_decode(file_get_contents('admin/data/packages.json'), true);
    if (is_array($packages)) {
        $business_context['packages'] = array_map(function($p) {
            return $p['name'] . ' - Rs. ' . $p['price'] . ' per person';
        }, $packages);
    }
}
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
    
    <!-- Loader Stylesheet -->
    <link href="css/loader.css" rel="stylesheet">
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
            padding: 10px;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
            z-index: 0;
        }
        
        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }
        
        .chat-container {
            max-width: min(900px, 95vw);
            width: 100%;
            height: min(90vh, 700px);
            min-height: 500px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
            margin: auto;
        }
        
        .chat-header {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 5px 20px rgba(254, 161, 22, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .chat-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }
        
        .chat-header img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }
        
        .header-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }
        
        .header-content h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .header-content small {
            opacity: 0.9;
        }
        
        .close-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            z-index: 1;
        }
        
        .close-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }
        
        #chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
            scroll-behavior: smooth;
        }
        
        #chat-list::-webkit-scrollbar {
            width: 8px;
        }
        
        #chat-list::-webkit-scrollbar-track {
            background: transparent;
        }
        
        #chat-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        .message {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            opacity: 0;
            transform: scale(0.7) translateY(40px);
            transition: all 0.7s cubic-bezier(.68,-0.55,.27,1.55);
        }
        
        .message.user {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            font-size: 20px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transform-style: preserve-3d;
        }
        
        .message.user .message-avatar {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            box-shadow: 0 5px 15px rgba(254, 161, 22, 0.3);
        }
        
        .message-container {
            max-width: 65%;
            padding: 15px 20px;
            border-radius: 20px;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: relative;
            transform-style: preserve-3d;
            word-wrap: break-word;
        }
        
        .message.user .message-container {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(254, 161, 22, 0.3);
        }
        
        .message-container::before {
            content: '';
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
        }
        
        .message.bot .message-container::before {
            left: -10px;
            top: 15px;
            border-width: 10px 10px 10px 0;
            border-color: transparent white transparent transparent;
        }
        
        .message.user .message-container::before {
            right: -10px;
            top: 15px;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent #FEA116;
        }
        
        .message-time {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }
        
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .suggestion-3d {
            padding: 10px 18px;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            border: none;
            border-radius: 25px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(.68,-0.55,.27,1.55);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transform-style: preserve-3d;
        }
        
        .suggestion-3d:hover {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            transform: scale(1.18) rotateY(16deg) translateY(-12px);
            box-shadow: 0 10px 25px rgba(254, 161, 22, 0.4);
        }
        
        #typing-form {
            padding: 20px 25px;
            background: white;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        #user-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        
        #user-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .send-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(.68,-0.55,.27,1.55);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .send-btn:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
        
        .send-btn:active {
            transform: scale(0.95);
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s ease-out;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }
        
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
            }
            
            .message-container {
                max-width: 75%;
            }
        }
    </style>
        body {
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
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
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
            height: 500px;
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
        
        .message-time {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }
        
        .chat-input {
            padding: 20px;
            background: white;
            border-top: 1px solid #dee2e6;
        }
        
        .typing-indicator {
            display: none;
            padding: 10px;
            background: white;
            border-radius: 15px;
            width: fit-content;
        }
        
        .typing-indicator span {
            height: 10px;
            width: 10px;
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
                transform: translateY(-10px);
            }
        }
        
        .quick-questions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .quick-question {
            padding: 8px 15px;
            background: #e9ecef;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .quick-question:hover {
            background: #FEA116;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Input Form Styles */
        #typing-form {
            padding: 20px;
            background: white;
            border-top: 1px solid #e9ecef;
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
            background: #f8f9fa;
        }
        
        #user-input:focus {
            border-color: #FEA116;
            background: white;
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
            box-shadow: 0 3px 15px rgba(254, 161, 22, 0.3);
        }
        
        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(254, 161, 22, 0.4);
        }
        
        .send-btn:active {
            transform: translateY(0);
        }
        
        /* Close Button */
        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 5px;
                align-items: flex-start;
                padding-top: 20px;
            }
            
            .chat-container {
                max-width: 100vw;
                width: 100%;
                height: calc(100vh - 40px);
                min-height: calc(100vh - 40px);
                border-radius: 15px;
                margin: 0;
            }
            
            .chat-header {
                padding: 15px;
                border-radius: 15px 15px 0 0;
            }
            
            .chat-header h5 {
                font-size: 16px;
            }
            
            .chat-header small {
                font-size: 11px;
            }
            
            .chat-header img {
                width: 40px;
                height: 40px;
            }
            
            #chat-list {
                padding: 10px;
            }
            
            .message-container {
                max-width: 85%;
                font-size: 14px;
                padding: 10px 12px;
            }
            
            .suggestions {
                gap: 8px;
            }
            
            .suggestion-3d {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            #typing-form {
                padding: 15px;
            }
            
            #user-input {
                font-size: 14px;
                padding: 10px 14px;
            }
            
            .send-btn {
                width: 40px;
                height: 40px;
            }
        }
        
        @media (max-width: 480px) {
            .chat-container {
                border-radius: 10px;
                height: calc(100vh - 20px);
                min-height: calc(100vh - 20px);
            }
            
            .chat-header {
                padding: 12px;
                border-radius: 10px 10px 0 0;
            }
            
            .chat-header h5 {
                font-size: 14px;
            }
            
            .chat-header img {
                width: 35px;
                height: 35px;
            }
            
            .message-container {
                font-size: 13px;
                padding: 8px 10px;
            }
            
            .suggestion-3d {
                padding: 6px 10px;
                font-size: 11px;
            }
            
            #typing-form {
                padding: 10px;
            }
            
            #user-input {
                font-size: 13px;
                padding: 8px 12px;
            }
            
            .send-btn {
                width: 35px;
                height: 35px;
            }
        }
        
        /* Prevent horizontal overflow */
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        * {
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .chat-container * {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        /* Ensure proper mobile viewport */
        @media screen and (max-width: 768px) {
            html {
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }
            
            body {
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body>
    <?php $loader_text = "Loading AI Assistant..."; include 'includes/loader.php'; ?>
    
    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <img src="img/logo.png" alt="Altaf Catering">
            <div class="header-content">
                <h5>Altaf Catering AI Assistant</h5>
                <small>🤖 Powered by Advanced AI</small>
            </div>
            <button class="close-btn" onclick="window.close()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Messages -->
        <div id="chat-list">
            <!-- Welcome Message -->
            <div class="message bot" style="opacity: 1; transform: scale(1);">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <div class="message-container">
                        <strong>Hello! 👋</strong><br>
                        I'm your Altaf Catering AI assistant. I can help you with menu items, packages, booking, and more!
                    </div>
                </div>
            </div>
            
            <!-- Quick Suggestions -->
            <div class="message bot" style="opacity: 1; transform: scale(1);">
                <div class="message-avatar">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div style="max-width: 70%;">
                    <div class="suggestions">
                        <button class="suggestion-3d" onclick="askQuestion('What are your catering packages?')">
                            📦 Packages
                        </button>
                        <button class="suggestion-3d" onclick="askQuestion('Show me your menu')">
                            🍽️ Menu
                        </button>
                        <button class="suggestion-3d" onclick="askQuestion('How do I book?')">
                            📅 Booking
                        </button>
                        <button class="suggestion-3d" onclick="askQuestion('Contact information?')">
                            📞 Contact
                        </button>
                        <button class="suggestion-3d" onclick="askQuestion('Tell me about your services')">
                            ⭐ Services
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Input Form -->
        <form id="typing-form">
            <input type="text" id="user-input" placeholder="Ask me anything about Altaf Catering..." required>
            <button type="submit" class="send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>

    <script>
        // OpenRouter API Configuration
        // IMPORTANT: Move this to server-side for production!
        const apiKey = "<?php echo defined('OPENROUTER_API_KEY') ? OPENROUTER_API_KEY : 'sk-or-v1-YOUR-KEY-HERE'; ?>";
        const chatList = document.getElementById("chat-list");
        const typingForm = document.getElementById("typing-form");
        const userInput = document.getElementById("user-input");
        
        // Business context for AI
        const businessContext = <?php echo json_encode($business_context); ?>;
        
        // System prompt with business context
        const systemPrompt = `You are a helpful AI assistant for Altaf Catering Company, a premium catering service in Pakistan.

Business Information:
- Name: ${businessContext.name}
- Phone: ${businessContext.contact.phone}
- Email: ${businessContext.contact.email}
- Address: ${businessContext.contact.address}
- Services: ${businessContext.services.join(', ')}
- Available Packages: ${businessContext.packages.join(', ')}
- Popular Menu Items: ${businessContext.menu.slice(0, 10).join(', ')}

Your role:
1. Answer questions about catering services, menu, packages, and booking
2. Be friendly, professional, and helpful
3. Provide accurate information about Altaf Catering
4. Suggest relevant pages (menu.php, book.php, contact.php, etc.)
5. Keep responses concise but informative
6. Use emojis occasionally to be friendly

Always be helpful and guide users to take action (book, call, visit website).`;

        // Handle form submission
        typingForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const userMessage = userInput.value.trim();
            if (!userMessage) return;

            // Add user message
            addMessage(userMessage, "user");
            userInput.value = "";

            // Add typing indicator
            addMessage("Typing...", "bot");
            animateBubble(chatList.lastChild, 'bot');

            try {
                // Call OpenRouter API
                const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${apiKey}`,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        model: "openai/gpt-3.5-turbo",
                        messages: [
                            { role: "system", content: systemPrompt },
                            { role: "user", content: userMessage }
                        ]
                    })
                });

                const data = await response.json();
                const botReply = data.choices?.[0]?.message?.content || "Sorry, I couldn't process that. Please try again!";
                
                // Update last message with AI response
                updateLastMessage(botReply);
                setTimeout(() => animateBubble(chatList.lastChild, 'bot'), 100);
                
            } catch (error) {
                console.error('API Error:', error);
                updateLastMessage("❌ Connection error. Please try again or contact us at " + businessContext.contact.phone);
                setTimeout(() => animateBubble(chatList.lastChild, 'bot'), 100);
            }
        });

        // Quick question handler
        function askQuestion(question) {
            userInput.value = question;
            typingForm.dispatchEvent(new Event('submit'));
        }

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${sender}`;

            const container = document.createElement("div");
            container.className = "message-container";
            container.innerHTML = text;

            const avatar = document.createElement("div");
            avatar.className = "message-avatar";
            avatar.innerHTML = `<i class="fas fa-${sender === 'user' ? 'user' : 'robot'}"></i>`;

            messageDiv.appendChild(avatar);
            
            const wrapper = document.createElement("div");
            wrapper.appendChild(container);
            messageDiv.appendChild(wrapper);

            chatList.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 80);
        }

        // Animate message bubble with 3D effect
        function animateBubble(bubble, type) {
            if (!bubble) return;
            
            bubble.style.opacity = 0;
            bubble.style.transform = 'scale(0.7) translateY(40px)';
            
            setTimeout(() => {
                bubble.style.transition = 'all 0.7s cubic-bezier(.68,-0.55,.27,1.55)';
                bubble.style.opacity = 1;
                bubble.style.transform = type === 'user' 
                    ? 'scale(1.08) rotateY(-8deg)' 
                    : 'scale(1.08) rotateY(8deg)';
                
                setTimeout(() => {
                    bubble.style.transform = type === 'user' 
                        ? 'scale(1) rotateY(-8deg)' 
                        : 'scale(1) rotateY(8deg)';
                }, 500);
            }, 50);
        }

        // Update last message (for AI response)
        function updateLastMessage(newText) {
            const messages = chatList.querySelectorAll(".message");
            if (messages.length > 0) {
                const lastMsg = messages[messages.length - 1];
                const container = lastMsg.querySelector('.message-container');
                if (container) container.innerHTML = newText;
                animateBubble(lastMsg, lastMsg.classList.contains('user') ? 'user' : 'bot');
            }
        }

        // Ripple effect on buttons
        document.querySelectorAll('.send-btn, .suggestion-3d').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                ripple.style.left = e.offsetX + 'px';
                ripple.style.top = e.offsetY + 'px';
                ripple.style.width = ripple.style.height = '100px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Smooth background transition
        document.body.style.transition = 'background 0.7s cubic-bezier(.68,-0.55,.27,1.55)';
    </script>
</body>
</html>
