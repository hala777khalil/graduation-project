<?php
$page_title = 'Dashboard';
$additional_css = ['dashboard.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

$stats_query = $conn->query("SELECT 
    COUNT(*) as total_activities,
    SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified_activities,
    SUM(CASE WHEN status = 'verified' THEN quantity ELSE 0 END) as total_recycled,
    SUM(CASE WHEN status = 'verified' THEN points_earned ELSE 0 END) as total_points
    FROM activities WHERE user_id = $user_id");
$stats = $stats_query->fetch_assoc();

$recent_activities = $conn->query("SELECT * FROM activities WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5");

$leaderboard = $conn->query("SELECT id, username, full_name, points, rank FROM users WHERE rank > 0 ORDER BY rank ASC LIMIT 5");
?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h1>
                    <p>Keep up the great work. Every action counts towards a sustainable future.</p>
                </div>
                <div style="display: flex; gap: 15px;">
                    <a href="add_activity.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Activity
                    </a>
                </div>
            </div>
        </div>
        
        <div class="stats-cards">
            <div class="stat-card-dashboard">
                <div class="stat-icon-dashboard icon-gold">
                    <i class="fas fa-star" style="font-size: 32px; color: #FFD700;"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Points</h4>
                    <p><?php echo number_format($user['points']); ?></p>
                </div>
            </div>
            
            <div class="stat-card-dashboard">
                <div class="stat-icon-dashboard icon-blue">
                    <i class="fas fa-recycle" style="font-size: 32px; color: #0066CC;"></i>
                </div>
                <div class="stat-info">
                    <h4>Activities</h4>
                    <p><?php echo $stats['total_activities']; ?></p>
                </div>
            </div>
            
            <div class="stat-card-dashboard">
                <div class="stat-icon-dashboard icon-green">
                    <i class="fas fa-weight" style="font-size: 32px; color: #28A745;"></i>
                </div>
                <div class="stat-info">
                    <h4>Total Recycled</h4>
                    <p><?php echo number_format($stats['total_recycled'] ?? 0, 1); ?> kg</p>
                </div>
            </div>
            
            <div class="stat-card-dashboard">
                <div class="stat-icon-dashboard icon-purple">
                    <i class="fas fa-trophy" style="font-size: 32px; color: #6F42C1;"></i>
                </div>
                <div class="stat-info">
                    <h4>Rank</h4>
                    <p>#<?php echo $user['rank'] ?: '-'; ?></p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Activities</h3>
                    <a href="my_activities.php">View All</a>
                </div>
                
                <?php if ($recent_activities->num_rows > 0): ?>
                    <ul class="activity-list">
                        <?php while($activity = $recent_activities->fetch_assoc()): ?>
                            <li class="activity-item">
                                <div class="activity-icon <?php echo 'icon-' . ($activity['status'] == 'verified' ? 'green' : ($activity['status'] == 'pending' ? 'gold' : 'blue')); ?>">
                                    <span style="font-size: 24px;"><?php echo getMaterialIcon($activity['material_type']); ?></span>
                                </div>
                                <div class="activity-details">
                                    <h4><?php echo ucfirst($activity['material_type']); ?> - <?php echo $activity['quantity']; ?> <?php echo $activity['unit']; ?></h4>
                                    <p><?php echo getTimeAgo($activity['created_at']); ?> • <?php echo ucfirst($activity['status']); ?></p>
                                </div>
                                <?php if ($activity['status'] == 'verified'): ?>
                                    <span class="activity-points">+<?php echo $activity['points_earned']; ?> pts</span>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-light); padding: 40px 0;">
                        No activities yet. <a href="add_activity.php">Add your first activity!</a>
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Top Recyclers</h3>
                    <a href="../pages/leaderboard.php">View All</a>
                </div>
                
                <ul class="leaderboard-list">
                    <?php while($leader = $leaderboard->fetch_assoc()): ?>
                        <li class="leaderboard-item">
                            <div class="rank-badge"><?php echo $leader['rank']; ?></div>
                            <div class="leaderboard-avatar">
                                <?php echo strtoupper(substr($leader['username'], 0, 1)); ?>
                            </div>
                            <div class="leaderboard-info">
                                <h4><?php echo htmlspecialchars($leader['username']); ?></h4>
                                <p><?php echo number_format($leader['points']); ?> points</p>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        
        <div class="quick-actions-grid">
            <div class="dashboard-card quick-action-card">
                <div class="quick-action-content">
                    <div class="quick-action-icon icon-blue">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3 class="quick-action-title">Add Activity</h3>
                    <p class="quick-action-desc">Log your recycling contribution</p>
                    <a href="add_activity.php" class="btn btn-primary">Start Now</a>
                </div>
            </div>
            
            <div class="dashboard-card quick-action-card">
                <div class="quick-action-content">
                    <div class="quick-action-icon icon-gold">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="quick-action-title">Find Centers</h3>
                    <p class="quick-action-desc">Locate nearby recycling points</p>
                    <a href="../pages/map.php" class="btn btn-gold">View Map</a>
                </div>
            </div>
            
            <div class="dashboard-card quick-action-card">
                <div class="quick-action-content">
                    <div class="quick-action-icon icon-purple">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3 class="quick-action-title">Leaderboard</h3>
                    <p class="quick-action-desc">Compete with eco-warriors</p>
                    <a href="../pages/leaderboard.php" class="btn btn-outline">View Ranking</a>
                </div>
            </div>
        </div>
    </main>
</div>

</div>
</body>
</html>

