<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login-secure.php');
    exit();
}

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

// Load admin data
$admin_stats = [
    'bookings' => 0,
    'contacts' => 0,
    'pending_bookings' => 0,
    'new_contacts' => 0
];

// Load services
if (file_exists('data/services.json')) {
    $services = json_decode(file_get_contents('data/services.json'), true);
    if (is_array($services)) {
        $business_context['services'] = array_map(function($s) {
            return $s['title'] . ': ' . $s['description'];
        }, $services);
    }
}

// Load menu items
if (file_exists('data/menu.json')) {
    $menu = json_decode(file_get_contents('data/menu.json'), true);
    if (is_array($menu)) {
        $business_context['menu'] = array_map(function($m) {
            return $m['name'] . ' - Rs. ' . $m['price'];
        }, array_slice($menu, 0, 15));
    }
}

// Load packages
if (file_exists('data/packages.json')) {
    $packages = json_decode(file_get_contents('data/packages.json'), true);
    if (is_array($packages)) {
        $business_context['packages'] = array_map(function($p) {
            return $p['name'] . ' - Rs. ' . $p['price'] . ' per person';
        }, $packages);
    }
}

// Load bookings
if (file_exists('data/bookings.json')) {
    $bookings = json_decode(file_get_contents('data/bookings.json'), true);
    if (is_array($bookings)) {
        $admin_stats['bookings'] = count($bookings);
        $admin_stats['pending_bookings'] = count(array_filter($bookings, function($b) {
            return isset($b['status']) && $b['status'] == 'pending';
        }));
    }
}

// Load contacts
if (file_exists('data/contacts.json')) {
    $contacts = json_decode(file_get_contents('data/contacts.json'), true);
    if (is_array($contacts)) {
        $admin_stats['contacts'] = count($contacts);
        $admin_stats['new_contacts'] = count(array_filter($contacts, function($c) {
            return isset($c['status']) && $c['status'] == 'new';
        }));
    }
}

$is_modal = isset($_GET['modal']) && $_GET['modal'] == '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Assistant - Admin Panel</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php if (!$is_modal): ?>
    <!-- Admin CSS -->
    <link href="css/admin-unified.css" rel="stylesheet">
    <?php endif; ?>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            <?php if ($is_modal): ?>
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            <?php else: ?>
            background: #f8f9fa;
            <?php endif; ?>
        }
        
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }
        
        /* Gradient text for title */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .chat-container {
            background: white;
            border-radius: <?php echo $is_modal ? '0' : '15px'; ?>;
            box-shadow: <?php echo $is_modal ? 'none' : '0 10px 30px rgba(0,0,0,0.1)'; ?>;
            overflow: hidden;
            height: <?php echo $is_modal ? '500px' : 'calc(100vh - 250px)'; ?>;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        <?php if (!$is_modal): ?>
        .main-content {
            margin-left: 0;
            padding-top: 80px;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 220px;
            }
        }
        <?php endif; ?>
        
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .chat-header .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            scroll-behavior: smooth;
        }
        
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        }
        
        .message {
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }
        
        .message.user {
            margin-bottom: 22px;
        }
        
        .message.user {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            font-size: 16px;
        }
        
        .message.user .message-avatar {
            width: 44px;
            height: 44px;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(254, 161, 22, 0.3);
        }
        
        .message.bot .message-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .message.user .message-avatar {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
        }
        
        .message-content {
            max-width: 70%;
            padding: 15px 18px;
            border-radius: 18px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .message.user .message-content {
            background: linear-gradient(135deg, #FEA116 0%, #ff6b35 100%);
            color: white;
            max-width: 80%;
            padding: 18px 24px;
            font-size: 16px;
            font-weight: 500;
            min-height: 50px;
            display: flex;
            align-items: center;
            border-radius: 20px;
        }
        
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }
        
        .suggestion-btn {
            padding: 8px 15px;
            background: #e9ecef;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .suggestion-btn:hover,
        .suggestion-btn:focus,
        .suggestion-btn:active {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            outline: none;
        }
        
        .chat-input-form {
            padding: 20px;
            background: white;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .chat-input {
            flex: 1;
            padding: 12px 18px;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }
        
        .chat-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .send-btn {
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        .send-btn:hover,
        .send-btn:focus,
        .send-btn:active {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            outline: none;
        }
        
        .send-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .typing-indicator {
            display: none;
            padding: 12px 18px;
            background: white;
            border-radius: 18px;
            width: fit-content;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            <?php if (!$is_modal): ?>
            .main-content {
                margin-left: 0;
                padding: 10px;
                padding-top: 80px;
            }
            <?php endif; ?>
            
            .chat-container {
                height: <?php echo $is_modal ? '450px' : 'calc(100vh - 180px)'; ?>;
                margin: <?php echo $is_modal ? '0' : '0'; ?>;
                border-radius: <?php echo $is_modal ? '0' : '10px'; ?>;
            }
            
            .message-content {
                max-width: 85%;
                font-size: 14px;
                padding: 12px 15px;
            }
            
            .message.user .message-content {
                max-width: 90%;
                font-size: 16px;
                padding: 16px 20px;
                min-height: 46px;
                border-radius: 18px;
            }
            
            .chat-input-form {
                padding: 15px;
            }
            
            .chat-header {
                padding: 15px;
            }
            
            .chat-header .avatar {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .chat-messages {
                padding: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .chat-container {
                height: <?php echo $is_modal ? '400px' : 'calc(100vh - 160px)'; ?>;
            }
            
            .message-content {
                font-size: 13px;
                padding: 10px 12px;
            }
            
            .message.user .message-content {
                font-size: 15px;
                padding: 14px 18px;
                min-height: 42px;
                max-width: 92%;
                border-radius: 16px;
            }
            
            .chat-input {
                font-size: 14px;
                padding: 10px 15px;
            }
            
            .send-btn {
                width: 40px;
                height: 40px;
            }
            
            .suggestion-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <?php if (!$is_modal): ?>
        <?php include 'includes/header.php'; ?>
        
        <div class="container-fluid">
            <div class="row">
                <?php include 'includes/sidebar.php'; ?>
                
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    <!-- Page Header -->
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                        <h1 class="h2 gradient-text">
                            <i class="fas fa-robot me-2"></i>AI Assistant
                        </h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearChat()">
                                    <i class="fas fa-trash me-1"></i>Clear Chat
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
    <?php endif; ?>
    
                        <div class="chat-container">
                            <!-- Chat Header -->
                            <div class="chat-header">
                                <div class="avatar">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Admin AI Assistant</h5>
                                    <small>Ready to help with your admin tasks</small>
                                </div>
                            </div>
                            
                            <!-- Messages Area -->
                            <div class="chat-messages" id="chatMessages">
                                <!-- Welcome Message -->
                                <div class="message bot">
                                    <div class="message-avatar">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                    <div>
                                        <div class="message-content">
                                            <strong>Hello Admin! 👋</strong><br>
                                            I'm your AI assistant. I can help you with:
                                            <ul class="mt-2 mb-0">
                                                <li>Business analytics and reports</li>
                                                <li>Managing bookings and contacts</li>
                                                <li>Admin panel guidance</li>
                                                <li>Quick data summaries</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Suggestions -->
                                <div class="message bot">
                                    <div class="message-avatar">
                                        <i class="fas fa-lightbulb"></i>
                                    </div>
                                    <div style="max-width: 70%;">
                                        <div class="suggestions">
                                            <button class="suggestion-btn" onclick="askQuestion('Show me today\'s summary')">
                                                📊 Today's Summary
                                            </button>
                                            <button class="suggestion-btn" onclick="askQuestion('How many pending bookings?')">
                                                📅 Pending Bookings
                                            </button>
                                            <button class="suggestion-btn" onclick="askQuestion('Show new contacts')">
                                                📧 New Contacts
                                            </button>
                                            <button class="suggestion-btn" onclick="askQuestion('Business analytics')">
                                                📈 Analytics
                                            </button>
                                            <button class="suggestion-btn" onclick="askQuestion('Help with menu management')">
                                                🍽️ Menu Help
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Input Form -->
                            <form class="chat-input-form" id="chatForm">
                                <input type="text" class="chat-input" id="messageInput" 
                                       placeholder="Ask me anything about your business..." required>
                                <button type="submit" class="send-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
    
    <?php if (!$is_modal): ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // API Configuration
        const apiKey = "<?php echo OPENROUTER_API_KEY; ?>";
        const chatMessages = document.getElementById("chatMessages");
        const chatForm = document.getElementById("chatForm");
        const messageInput = document.getElementById("messageInput");
        
        // Business and admin context
        const businessContext = <?php echo json_encode($business_context); ?>;
        const adminStats = <?php echo json_encode($admin_stats); ?>;
        
        // System prompt for admin assistant
        const systemPrompt = `You are an AI assistant for the admin panel of Altaf Catering Company. Help administrators manage their business efficiently.

Business Information:
- Name: ${businessContext.name}
- Phone: ${businessContext.contact.phone}
- Email: ${businessContext.contact.email}
- Address: ${businessContext.contact.address}
- Services: ${businessContext.services.join(', ')}
- Packages: ${businessContext.packages.join(', ')}
- Menu Items: ${businessContext.menu.join(', ')}

Current Statistics:
- Total Bookings: ${adminStats.bookings}
- Pending Bookings: ${adminStats.pending_bookings}
- Total Contacts: ${adminStats.contacts}
- New Contacts: ${adminStats.new_contacts}

Your role:
1. Provide business insights and analytics
2. Help with admin tasks and guidance
3. Answer questions about bookings, contacts, menu
4. Suggest improvements and best practices
5. Be professional, helpful, and concise
6. Use emojis occasionally for friendliness

Always focus on actionable admin tasks and business management.`;

        // Handle form submission
        chatForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const userMessage = messageInput.value.trim();
            if (!userMessage) return;

            // Add user message
            addMessage(userMessage, "user");
            messageInput.value = "";

            // Show typing indicator and disable send button
            showTypingIndicator();
            const sendBtn = document.querySelector('.send-btn');
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

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
                
                // Hide typing indicator and add AI response
                hideTypingIndicator();
                addMessage(botReply, "bot");
                
                // Re-enable send button
                const sendBtn = document.querySelector('.send-btn');
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
                
            } catch (error) {
                console.error('API Error:', error);
                hideTypingIndicator();
                addMessage("❌ Connection error. Please check your internet connection and try again.", "bot");
                
                // Re-enable send button
                const sendBtn = document.querySelector('.send-btn');
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            }
        });

        // Quick question handler
        function askQuestion(question) {
            messageInput.value = question;
            chatForm.dispatchEvent(new Event('submit'));
        }

        // Add message to chat
        function addMessage(text, sender) {
            const messageDiv = document.createElement("div");
            messageDiv.className = `message ${sender}`;

            const avatar = document.createElement("div");
            avatar.className = "message-avatar";
            avatar.innerHTML = `<i class="fas fa-${sender === 'user' ? 'user-shield' : 'robot'}"></i>`;

            const contentWrapper = document.createElement("div");
            const content = document.createElement("div");
            content.className = "message-content";
            content.innerHTML = formatMessage(text);

            contentWrapper.appendChild(content);
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(contentWrapper);

            chatMessages.appendChild(messageDiv);
            
            // Scroll to bottom
            setTimeout(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 100);
        }

        // Format message text
        function formatMessage(text) {
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n/g, '<br>');
        }

        // Show typing indicator
        function showTypingIndicator() {
            const typingDiv = document.createElement("div");
            typingDiv.className = "message bot";
            typingDiv.id = "typingIndicator";

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
        function hideTypingIndicator() {
            const indicator = document.getElementById("typingIndicator");
            if (indicator) {
                indicator.remove();
            }
        }

        // Auto-focus input
        messageInput.focus();
        
        // Clear chat function
        function clearChat() {
            // Remove all messages except welcome messages
            const messages = chatMessages.querySelectorAll('.message');
            messages.forEach((message, index) => {
                if (index > 1) { // Keep first 2 welcome messages
                    message.remove();
                }
            });
        }
    </script>
</body>
</html>