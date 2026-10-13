<?php
$page_title = 'About Us';
$additional_css = ['pages.css'];
require_once '../includes/header.php';
?>

<div class="page-hero">
    <h1>About EcoRecycle Hub</h1>
    <p>Empowering communities to build a sustainable future through innovative recycling solutions</p>
</div>

<div class="page-content">
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-leaf"></i> Our Mission
        </h2>
        <p class="section-text">
            EcoRecycle Hub is dedicated to transforming the way communities engage with recycling and environmental sustainability. Our mission is to make recycling accessible, rewarding, and impactful for everyone. Through our innovative digital platform, we empower individuals to track their environmental contributions, earn rewards, and compete with others in their journey towards a greener planet.
        </p>
        <p class="section-text">
            We believe that every small action counts, and by providing visibility, recognition, and incentives, we can motivate sustained behavioral change that benefits both people and the planet.
        </p>
    </div>
    
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-eye"></i> Our Vision
        </h2>
        <p class="section-text">
            We envision a world where recycling is not just a responsibility but a celebrated lifestyle choice. A world where technology bridges the gap between environmental awareness and action, creating communities of eco-conscious citizens who actively participate in building a sustainable future.
        </p>
        <p class="section-text">
            By 2030, we aim to have engaged millions of users worldwide, collectively recycling billions of kilograms of materials and significantly reducing global waste and carbon emissions.
        </p>
    </div>
    
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-bullseye"></i> What We Do
        </h2>
        <div class="features-grid" style="margin-top: 30px;">
            <div class="feature-card">
                <div class="icon-wrapper icon-blue feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="feature-title">Activity Tracking</h3>
                <p class="feature-description">Log and monitor your recycling activities with detailed analytics and real-time impact visualization</p>
            </div>
            
            <div class="feature-card">
                <div class="icon-wrapper icon-gold feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3 class="feature-title">Rewards System</h3>
                <p class="feature-description">Earn points for every recycling activity and redeem them for exclusive rewards and certificates</p>
            </div>
            
            <div class="feature-card">
                <div class="icon-wrapper icon-green feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="feature-title">Community Engagement</h3>
                <p class="feature-description">Connect with like-minded individuals, compete on leaderboards, and motivate each other</p>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-users"></i> Our Team
        </h2>
        <p class="section-text">
            Our team consists of passionate environmentalists, software engineers, and community organizers dedicated to making a positive impact on the planet.
        </p>
        
        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar" style="background: var(--gradient-gold);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">John Smith</div>
                <div class="member-role">Founder & CEO</div>
                <div class="member-social">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-avatar" style="background: var(--gradient-blue);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Sarah Johnson</div>
                <div class="member-role">Chief Technology Officer</div>
                <div class="member-social">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-github"></i></a>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-avatar" style="background: var(--gradient-gold);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Michael Chen</div>
                <div class="member-role">Head of Community</div>
                <div class="member-social">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="team-member">
                <div class="member-avatar" style="background: var(--gradient-blue);">
                    <i class="fas fa-user"></i>
                </div>
                <div class="member-name">Emily Davis</div>
                <div class="member-role">Environmental Specialist</div>
                <div class="member-social">
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <h2 class="section-heading">
            <i class="fas fa-award"></i> Our Impact
        </h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">
                    <?php
                    $result = $conn->query("SELECT COUNT(*) as count FROM users");
                    echo number_format($result->fetch_assoc()['count']);
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
                    echo number_format($result->fetch_assoc()['count']);
                    ?>
                </div>
                <div class="stat-label">Activities Completed</div>
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
                <div class="stat-label">Kilograms Recycled</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-purple">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="stat-number">12</div>
                <div class="stat-label">Cities Served</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

