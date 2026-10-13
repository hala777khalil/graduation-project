<?php
$page_title = 'Contact Us';
$additional_css = ['pages.css'];
require_once '../includes/header.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);
    
    $success = 'Thank you for contacting us! We will get back to you soon.';
}
?>

<div class="page-hero">
    <h1>Contact Us</h1>
    <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
</div>

<div class="page-content">
    <div class="content-section">
        <div class="contact-grid">
            <div class="contact-info-section">
                <div class="contact-item">
                    <div class="contact-icon-box icon-blue">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Our Office</h4>
                        <p>King Fahd Road, Riyadh<br>Saudi Arabia, 12345</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon-box icon-gold">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Phone Number</h4>
                        <p>+966 50 123 4567<br>+966 11 234 5678</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon-box icon-green">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Email Address</h4>
                        <p>info@ecorecycle.com<br>support@ecorecycle.com</p>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon-box icon-purple">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-details">
                        <h4>Working Hours</h4>
                        <p>Sunday - Thursday: 9:00 AM - 6:00 PM<br>Friday - Saturday: Closed</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-section">
                <h3 style="margin-bottom: 20px; color: var(--text-dark);">Send us a Message</h3>
                
                <?php if ($success): ?>
                    <div class="form-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required placeholder="Enter your name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required placeholder="Enter your email">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required placeholder="Enter subject">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Enter your message"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-question-circle"></i> Frequently Asked Questions
        </h2>
        
        <div style="margin-top: 30px;">
            <div style="margin-bottom: 25px; padding: 20px; background: var(--light-bg); border-radius: 12px;">
                <h4 style="color: var(--primary-blue); margin-bottom: 10px; font-size: 18px;">How do I earn points?</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    You earn points by logging your recycling activities. Points are awarded based on the type and quantity of materials you recycle. Verified activities automatically add points to your account.
                </p>
            </div>
            
            <div style="margin-bottom: 25px; padding: 20px; background: var(--light-bg); border-radius: 12px;">
                <h4 style="color: var(--primary-blue); margin-bottom: 10px; font-size: 18px;">How can I redeem my points?</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Visit the 'My Points' section in your dashboard to view available rewards. You can redeem your points for certificates, badges, and exclusive environmental rewards.
                </p>
            </div>
            
            <div style="margin-bottom: 25px; padding: 20px; background: var(--light-bg); border-radius: 12px;">
                <h4 style="color: var(--primary-blue); margin-bottom: 10px; font-size: 18px;">Is the platform free to use?</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Yes! EcoRecycle Hub is completely free to use. Our goal is to make sustainable recycling accessible to everyone in the community.
                </p>
            </div>
            
            <div style="margin-bottom: 25px; padding: 20px; background: var(--light-bg); border-radius: 12px;">
                <h4 style="color: var(--primary-blue); margin-bottom: 10px; font-size: 18px;">Where can I find recycling centers?</h4>
                <p style="color: var(--text-light); line-height: 1.8;">
                    Check out our interactive 'Centers Map' page to find recycling centers near you, complete with addresses, phone numbers, and accepted materials.
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>

