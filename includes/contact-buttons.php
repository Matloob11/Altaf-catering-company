<!-- Social Media Buttons (RIGHT Side) -->
<div class="social-buttons-right">
    <!-- TikTok -->
    <a href="https://www.tiktok.com/@altafcateringcompany?_t=8scdCc9SFQ9&_r=1" class="social-tiktok" target="_blank"
        rel="noopener noreferrer" aria-label="Follow us on TikTok" title="TikTok">
        <i class="fab fa-tiktok"></i>
    </a>

    <!-- Facebook -->
    <a href="https://web.facebook.com/AltafCateringCompany?mibextid=ZbWKwL&_rdc=1&_rdr#" class="social-facebook"
        target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook" title="Facebook">
        <i class="fab fa-facebook-f"></i>
    </a>

    <!-- Instagram -->
    <a href="https://www.instagram.com/altafcateringcompany/" class="social-instagram" target="_blank"
        rel="noopener noreferrer" aria-label="Follow us on Instagram" title="Instagram">
        <i class="fab fa-instagram"></i>
    </a>

    <!-- YouTube -->
    <a href="https://www.youtube.com/@Altafcateringcompanyy" class="social-youtube" target="_blank"
        rel="noopener noreferrer" aria-label="Subscribe to our YouTube channel" title="YouTube">
        <i class="fab fa-youtube"></i>
    </a>
</div>

<!-- Contact Buttons (RIGHT Side - Bottom to Top: Back to Top, WhatsApp, Call) -->
<div class="contact-buttons-right">
    <!-- Back to Top Button (Bottom) -->
    <a href="#" class="back-to-top-btn" title="Back to Top">
        <i class="fa fa-arrow-up"></i>
    </a>

    <!-- AI Chat Button (Middle) -->
    <div class="ai-chat-widget">
        <div class="ai-chat-button" onclick="toggleChat()">
            <i class="fas fa-robot"></i>
            <span class="chat-badge">AI</span>
        </div>
        
        <div class="ai-chat-window" id="aiChatWindow">
            <div class="ai-chat-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="ai-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div>
                        <strong>AI Assistant</strong>
                        <div class="ai-status">
                            <span class="status-dot"></span> Online
                        </div>
                    </div>
                </div>
                <button class="ai-close-btn" onclick="toggleChat()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="ai-chat-messages" id="aiChatMessages">
                <div class="ai-message bot">
                    <div class="ai-message-avatar">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="ai-message-content">
                        <strong>Hello! 👋</strong><br>
                        I'm your Altaf Catering AI assistant. Ask me anything!
                    </div>
                </div>
                
                <div class="ai-suggestions">
                    <button class="ai-suggestion" onclick="sendQuickMessage('What are your packages?')">
                        📦 Packages
                    </button>
                    <button class="ai-suggestion" onclick="sendQuickMessage('Show me menu')">
                        🍽️ Menu
                    </button>
                    <button class="ai-suggestion" onclick="sendQuickMessage('How to book?')">
                        📅 Booking
                    </button>
                    <button class="ai-suggestion" onclick="sendQuickMessage('Contact info?')">
                        📞 Contact
                    </button>
                </div>
            </div>
            
            <div class="ai-chat-input">
                <form id="aiChatForm" onsubmit="sendAIMessage(event)">
                    <input type="text" id="aiUserInput" placeholder="Type your message..." required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Call Button (Top) -->
    <a href="tel:+923039907296" class="call-btn" aria-label="Call us now" title="Call: +92 303 9907296">
        <i class="fas fa-phone-alt"></i>
        <span class="call-pulse"></span>
    </a>

    <!-- Enhanced WhatsApp Button with Quick Menu (Below Call Button) -->
    <div class="whatsapp-container">
        <!-- Main WhatsApp Button -->
        <a href="https://wa.me/923039907296?text=Hello%20Altaf%20Catering!%20I%20would%20like%20to%20inquire%20about%20your%20services."
            class="whatsapp-btn" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp"
            title="Chat on WhatsApp" id="mainWhatsAppBtn" style="bottom: 0; right: 0; ">
            <i class="fab fa-whatsapp"></i>
            <span class="whatsapp-pulse"></span>
        </a>

        <!-- Quick Action Menu -->
        <div class="whatsapp-quick-menu" id="whatsappQuickMenu">
            <div class="quick-menu-header">
                <img src="img/logo.png" alt="Altaf Catering" class="quick-logo">
                <div class="quick-info">
                    <strong>Altaf Catering</strong>
                    <small class="online-status">● Online</small>
                </div>
            </div>

            <div class="quick-actions">
                <a href="https://wa.me/923039907296?text=🎪%20I%20want%20to%20book%20catering%20for%20my%20event.%20Please%20share%20details%20about%20packages%20and%20availability."
                    class="quick-action" target="_blank">
                    <i class="fas fa-calendar-check"></i>
                    <span>Book Event</span>
                </a>

                <a href="https://wa.me/923039907296?text=🍽️%20Can%20I%20see%20your%20complete%20menu%20with%20prices?%20I'm%20planning%20an%20event."
                    class="quick-action" target="_blank">
                    <i class="fas fa-utensils"></i>
                    <span>View Menu</span>
                </a>

                <a href="https://wa.me/923039907296?text=💰%20What%20are%20your%20package%20prices%20for%20different%20events?%20Please%20share%20pricing%20details."
                    class="quick-action" target="_blank">
                    <i class="fas fa-tags"></i>
                    <span>Get Pricing</span>
                </a>

                <a href="https://wa.me/923039907296?text=📞%20I%20need%20immediate%20assistance.%20Please%20call%20me%20back%20as%20soon%20as%20possible."
                    class="quick-action" target="_blank">
                    <i class="fas fa-phone-alt"></i>
                    <span>Request Callback</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// OpenRouter API Configuration
const AI_API_KEY = "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";
const AI_MODEL = "openai/gpt-3.5-turbo";

// Toggle chat window
function toggleChat() {
    const chatWindow = document.getElementById('aiChatWindow');
    chatWindow.classList.toggle('active');
}

// Send quick message
function sendQuickMessage(message) {
    document.getElementById('aiUserInput').value = message;
    document.getElementById('aiChatForm').dispatchEvent(new Event('submit'));
}

// Send AI message
async function sendAIMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('aiUserInput');
    const message = input.value.trim();
    if (!message) return;
    
    addAIMessage(message, 'user');
    input.value = '';
    showTyping();
    
    try {
        const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${AI_API_KEY}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                model: AI_MODEL,
                messages: [
                    { 
                        role: "system", 
                        content: `You are a helpful AI assistant for Altaf Catering Company in Pakistan. 
                        Help users with: menu items, catering packages, booking process, contact information.
                        Phone: +92 303 9907296, Email: altafcatering@gmail.com, Address: MM Farm House Sharif Medical Jati Umrah Road, Karachi
                        Be friendly, concise, and helpful. Use emojis occasionally. Keep responses under 100 words.`
                    },
                    { role: "user", content: message }
                ]
            })
        });
        
        const data = await response.json();
        const botReply = data.choices?.[0]?.message?.content || "Sorry, I couldn't process that. Please call us at +92 303 9907296";
        
        hideTyping();
        addAIMessage(botReply, 'bot');
        
    } catch (error) {
        console.error('AI Error:', error);
        hideTyping();
        addAIMessage("❌ Connection error. Please try again or call +92 303 9907296", 'bot');
    }
}

function addAIMessage(text, sender) {
    const messagesDiv = document.getElementById('aiChatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `ai-message ${sender}`;
    messageDiv.innerHTML = `
        <div class="ai-message-avatar">
            <i class="fas fa-${sender === 'user' ? 'user' : 'robot'}"></i>
        </div>
        <div class="ai-message-content">${text}</div>
    `;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function showTyping() {
    const messagesDiv = document.getElementById('aiChatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'aiTyping';
    typingDiv.className = 'ai-message bot';
    typingDiv.innerHTML = `
        <div class="ai-message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="ai-typing">
            <span></span>
            <span></span>
            <span></span>
        </div>
    `;
    messagesDiv.appendChild(typingDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

function hideTyping() {
    const typing = document.getElementById('aiTyping');
    if (typing) typing.remove();
}
</script>