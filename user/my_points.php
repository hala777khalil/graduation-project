<?php
$page_title = 'My Points';
$additional_css = ['dashboard.css', 'points.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

$breakdown_query = $conn->query("SELECT 
    material_type,
    SUM(quantity) as total_quantity,
    SUM(points_earned) as total_points,
    COUNT(*) as activity_count
    FROM activities 
    WHERE user_id = $user_id AND status = 'verified'
    GROUP BY material_type
    ORDER BY total_points DESC");

$history_query = $conn->query("SELECT * FROM activities WHERE user_id = $user_id AND status = 'verified' ORDER BY activity_date DESC LIMIT 10");

$total_recycled = $conn->query("SELECT SUM(quantity) as total FROM activities WHERE user_id = $user_id AND status = 'verified'")->fetch_assoc()['total'] ?? 0;
?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="dashboard-main">
        <div class="dashboard-header">
            <h1>Points & Rewards</h1>
            <p>Track your environmental impact and redeem rewards</p>
        </div>
        
        <div class="points-overview">
            <div class="points-card">
                <div class="points-icon-large icon-gold">
                    <i class="fas fa-star"></i>
                </div>
                <div class="points-value"><?php echo number_format($user['points']); ?></div>
                <div class="points-label">Total Points</div>
            </div>
            
            <div class="points-card">
                <div class="points-icon-large icon-blue">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="points-value">#<?php echo $user['rank'] ?: '-'; ?></div>
                <div class="points-label">Global Rank</div>
            </div>
            
            <div class="points-card">
                <div class="points-icon-large icon-green">
                    <i class="fas fa-weight"></i>
                </div>
                <div class="points-value"><?php echo number_format($total_recycled, 1); ?></div>
                <div class="points-label">Total Recycled (kg)</div>
            </div>
        </div>
        
        <div class="breakdown-section">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">Points Breakdown by Material</h2>
            <p style="color: var(--text-light); margin-bottom: 20px;">See how your recycling efforts contribute across different materials</p>
            
            <?php if ($breakdown_query->num_rows > 0): ?>
                <div class="breakdown-grid">
                    <?php while($item = $breakdown_query->fetch_assoc()): ?>
                        <div class="breakdown-item">
                            <div class="breakdown-header">
                                <span class="breakdown-icon"><?php echo getMaterialIcon($item['material_type']); ?></span>
                                <span class="breakdown-material"><?php echo ucfirst($item['material_type']); ?></span>
                            </div>
                            <div class="breakdown-stats">
                                <span class="breakdown-quantity"><?php echo $item['total_quantity']; ?> kg</span>
                                <span class="breakdown-points">+<?php echo $item['total_points']; ?> pts</span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">No verified activities yet</p>
            <?php endif; ?>
        </div>
        
        <div class="history-section">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">Recent Points History</h2>
            <p style="color: var(--text-light);">Your latest point-earning activities</p>
            
            <?php if ($history_query->num_rows > 0): ?>
                <div class="timeline">
                    <?php while($activity = $history_query->fetch_assoc()): ?>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-header">
                                <span class="timeline-date"><?php echo date('M d, Y', strtotime($activity['activity_date'])); ?></span>
                                <span class="timeline-points">+<?php echo $activity['points_earned']; ?> points</span>
                            </div>
                            <div class="timeline-activity">
                                <?php echo getMaterialIcon($activity['material_type']); ?> 
                                Recycled <?php echo $activity['quantity']; ?> <?php echo $activity['unit']; ?> of <?php echo ucfirst($activity['material_type']); ?>
                            </div>
                            <?php if ($activity['description']): ?>
                                <div class="timeline-description"><?php echo htmlspecialchars($activity['description']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">No activities yet</p>
            <?php endif; ?>
        </div>
        
        <div class="rewards-section">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px;">Available Rewards</h2>
            <p style="color: var(--text-light);">Redeem your points for exclusive rewards</p>
            
            <div class="rewards-grid">
                <div class="reward-card">
                    <div class="reward-badge icon-gold">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="reward-title">Bronze Certificate</div>
                    <div class="reward-cost">500 points</div>
                    <button class="btn btn-primary" style="width: 100%;">Claim Reward</button>
                </div>
                
                <div class="reward-card">
                    <div class="reward-badge icon-blue">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="reward-title">Silver Badge</div>
                    <div class="reward-cost">1,000 points</div>
                    <button class="btn btn-primary" style="width: 100%;">Claim Reward</button>
                </div>
                
                <div class="reward-card">
                    <div class="reward-badge icon-gold">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="reward-title">Gold Trophy</div>
                    <div class="reward-cost">2,500 points</div>
                    <button class="btn btn-primary" style="width: 100%;">Claim Reward</button>
                </div>
            </div>
        </div>
    </main>
</div>

</div>
</body>
</html>

