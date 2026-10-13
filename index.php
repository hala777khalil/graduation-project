<?php
$page_title = 'Home';
$additional_css = ['footer.css', 'index.css'];
require_once 'includes/header.php';
?>

<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Transform Waste Into Rewards</h1>
        <p class="hero-subtitle">Join thousands of eco-warriors tracking their recycling activities, earning points, and making a real environmental impact</p>
        <div class="hero-buttons">
            <?php if (isLoggedIn()): ?>
                <a href="<?php echo SITE_URL; ?>/user/dashboard.php" class="btn btn-gold">Go to Dashboard</a>
                <a href="<?php echo SITE_URL; ?>/user/add_activity.php" class="btn btn-primary">Add Activity</a>
            <?php else: ?>
                <a href="<?php echo SITE_URL; ?>/auth/register.php" class="btn btn-gold">Get Started</a>
                <a href="<?php echo SITE_URL; ?>/pages/about.php" class="btn btn-outline">Learn More</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">
                <?php
                $result = $conn->query("SELECT COUNT(*) as count FROM users");
                echo $result->fetch_assoc()['count'];
                ?>
            </div>
            <div class="stat-label">Active Users</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <i class="fas fa-recycle"></i>
            </div>
            <div class="stat-number">
                <?php
                $result = $conn->query("SELECT COUNT(*) as count FROM activities WHERE status = 'verified'");
                echo $result->fetch_assoc()['count'];
                ?>
            </div>
            <div class="stat-label">Activities Logged</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-gold">
                <i class="fas fa-weight"></i>
            </div>
            <div class="stat-number">
                <?php
                $result = $conn->query("SELECT SUM(quantity) as total FROM activities WHERE status = 'verified'");
                echo number_format($result->fetch_assoc()['total'] ?? 0);
                ?>
            </div>
            <div class="stat-label">Kg Recycled</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-number">
                <?php
                $result = $conn->query("SELECT COUNT(*) as count FROM recycling_centers WHERE status = 'active'");
                echo $result->fetch_assoc()['count'];
                ?>
            </div>
            <div class="stat-label">Recycling Centers</div>
        </div>
    </div>
</section>

<section class="features-section">
    <h2 class="section-title">Why Choose EcoRecycle Hub?</h2>
    <div class="features-grid">
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-blue feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="feature-title">Track Your Impact</h3>
            <p class="feature-description">Monitor your recycling activities with detailed analytics and visualize your environmental contribution in real-time</p>
        </div>
        
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-gold feature-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <h3 class="feature-title">Earn Rewards</h3>
            <p class="feature-description">Get points for every recycling activity and redeem them for exclusive rewards and recognition</p>
        </div>
        
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-green feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="feature-title">Community Competition</h3>
            <p class="feature-description">Compete with friends and neighbors on the leaderboard and motivate each other to recycle more</p>
        </div>
        
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-purple feature-icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3 class="feature-title">Find Centers Easily</h3>
            <p class="feature-description">Locate nearby recycling centers with our interactive map and get directions instantly</p>
        </div>
        
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-blue feature-icon">
                <i class="fas fa-robot"></i>
            </div>
            <h3 class="feature-title">AI Assistant</h3>
            <p class="feature-description">Get instant answers about recycling guidelines and personalized recommendations from our AI chatbot</p>
        </div>
        
        <div class="feature-card fade-in">
            <div class="icon-wrapper icon-gold feature-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="feature-title">Detailed Analytics</h3>
            <p class="feature-description">View comprehensive reports of your recycling patterns and environmental savings over time</p>
        </div>
    </div>
</section>

<section class="how-it-works">
    <h2 class="section-title">How It Works</h2>
    <div class="steps-container">
        <div class="step-item">
            <div class="step-number" style="background: var(--gradient-gold);">1</div>
            <div class="step-content">
                <h3 class="step-title">Sign Up</h3>
                <p class="step-description">Create your free account in less than a minute and join our eco-friendly community</p>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number" style="background: var(--gradient-blue);">2</div>
            <div class="step-content">
                <h3 class="step-title">Log Activities</h3>
                <p class="step-description">Record your recycling activities with details like material type and quantity</p>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number" style="background: var(--gradient-gold);">3</div>
            <div class="step-content">
                <h3 class="step-title">Earn Points</h3>
                <p class="step-description">Receive points automatically based on your recycling contributions</p>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number" style="background: var(--gradient-blue);">4</div>
            <div class="step-content">
                <h3 class="step-title">Compete & Win</h3>
                <p class="step-description">Climb the leaderboard and earn badges as you continue your eco-journey</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="cta-content">
        <h2 class="cta-title">Ready to Make a Difference?</h2>
        <p class="cta-text">Join our community today and start tracking your recycling journey</p>
        <?php if (!isLoggedIn()): ?>
            <a href="<?php echo SITE_URL; ?>/auth/register.php" class="btn btn-gold" style="font-size: 18px; padding: 15px 40px;">Start Recycling Now</a>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

