<?php
$page_title = 'AI Chatbot';
$additional_css = ['chatbot.css'];
require_once '../includes/header.php';
?>

<div class="chatbot-container">
    <div class="chatbot-header">
        <div class="chatbot-icon-large">
            <i class="fas fa-robot"></i>
        </div>
        <h1>EcoBot Assistant</h1>
        <p>Your intelligent recycling companion</p>
    </div>
    
    <div class="chat-window">
        <div class="chat-messages" id="chatMessages">
            <div class="chat-message bot">
                <div class="message-avatar bot">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    <div>Hello! I'm EcoBot, your AI recycling assistant. How can I help you today?</div>
                    <div class="message-time">Just now</div>
                </div>
            </div>
        </div>
        
        <div class="quick-suggestions">
            <button class="suggestion-btn" onclick="sendQuickMessage('What materials can I recycle?')">
                What materials can I recycle?
            </button>
            <button class="suggestion-btn" onclick="sendQuickMessage('How do I earn more points?')">
                How do I earn more points?
            </button>
            <button class="suggestion-btn" onclick="sendQuickMessage('Find nearest recycling center')">
                Find nearest center
            </button>
            <button class="suggestion-btn" onclick="sendQuickMessage('Environmental tips')">
                Environmental tips
            </button>
        </div>
        
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Type your message...">
            <button class="chat-send-btn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    
    <div class="recommendations-section">
        <h2 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">
            <i class="fas fa-lightbulb"></i> Personalized Recommendations
        </h2>
        <p style="color: var(--text-light); margin-bottom: 20px;">
            Based on your recycling activity, here are some suggestions to maximize your impact
        </p>
        
        <div class="recommendations-grid">
            <div class="recommendation-card">
                <div class="recommendation-icon icon-green">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="recommendation-title">Increase Plastic Recycling</div>
                <p class="recommendation-text">
                    You've been doing great with paper! Consider adding more plastic recycling to diversify your impact and earn bonus points.
                </p>
                <a href="../user/add_activity.php" class="btn btn-primary">Add Activity</a>
            </div>
            
            <div class="recommendation-card">
                <div class="recommendation-icon icon-gold">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="recommendation-title">Join Weekly Challenge</div>
                <p class="recommendation-text">
                    Participate in this week's recycling challenge and earn 2x points! Only 3 days left to join.
                </p>
                <a href="../pages/leaderboard.php" class="btn btn-gold">View Challenge</a>
            </div>
            
            <div class="recommendation-card">
                <div class="recommendation-icon icon-blue">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="recommendation-title">New Center Nearby</div>
                <p class="recommendation-text">
                    A new recycling center just opened 2km from your location. Check it out for convenient drop-offs!
                </p>
                <a href="../pages/map.php" class="btn btn-outline">View Map</a>
            </div>
        </div>
    </div>
</div>

<script>
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');

const responses = {
    'materials': 'You can recycle plastic, paper, glass, metal, electronics, cardboard, and organic waste. Each material earns different points based on environmental impact!',
    'points': 'Earn points by logging your recycling activities! Plastic earns 10 pts/kg, Paper 8 pts/kg, Glass 12 pts/kg, Metal 15 pts/kg, and Electronics 20 pts/kg.',
    'center': 'Visit our interactive map to find recycling centers near you. Go to the "Centers Map" page from the menu.',
    'tips': 'Eco Tip: Always clean and sort your recyclables! Rinse containers, remove labels when possible, and separate different materials for maximum recycling efficiency.',
    'ai': 'Use AI',
    'default': 'That\'s a great question! For detailed information, you can check our FAQ page or contact our support team.'
};

function addMessage(text, isUser = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `chat-message ${isUser ? 'user' : 'bot'}`;
    
    const avatar = document.createElement('div');
    avatar.className = `message-avatar ${isUser ? 'user' : 'bot'}`;
    avatar.innerHTML = isUser ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';
    
    const content = document.createElement('div');
    content.className = 'message-content';
    content.innerHTML = `
        <div>${text}</div>
        <div class="message-time">Just now</div>
    `;
    
    messageDiv.appendChild(avatar);
    messageDiv.appendChild(content);
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showTyping() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'chat-message bot';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `
        <div class="message-avatar bot"><i class="fas fa-robot"></i></div>
        <div class="message-content">
            <div class="typing-indicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
    `;
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function removeTyping() {
    const typing = document.getElementById('typingIndicator');
    if (typing) typing.remove();
}

function getResponse(message) {
    const lowerMessage = message.toLowerCase();
    
    if (lowerMessage.includes('material') || lowerMessage.includes('recycle') || lowerMessage.includes('what')) {
        return responses.materials;
    } else if (lowerMessage.includes('point') || lowerMessage.includes('earn')) {
        return responses.points;
    } else if (lowerMessage.includes('center') || lowerMessage.includes('location') || lowerMessage.includes('where')) {
        return responses.center;
    } else if (lowerMessage.includes('tip') || lowerMessage.includes('help') || lowerMessage.includes('advice')) {
        return responses.tips;
    }
    return responses.default;
}

function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;
    
    addMessage(message, true);
    chatInput.value = '';
    
    showTyping();
    
    fetch('../ai/api_handler.php?action=chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'question=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        removeTyping();
        if (data.success) {
            addMessage(data.answer);
        } else {
            addMessage(getResponse(message));
        }
    })
    .catch(error => {
        removeTyping();
        addMessage(getResponse(message));
    });
}

function sendQuickMessage(message) {
    addMessage(message, true);
    
    showTyping();
    
    setTimeout(() => {
        removeTyping();
        const response = getResponse(message);
        addMessage(response);
    }, 1500);
}

chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>

