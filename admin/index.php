<?php
$page_title = 'Admin Dashboard';
$additional_css = ['admin.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotAdmin();

$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_activities = $conn->query("SELECT COUNT(*) as count FROM activities")->fetch_assoc()['count'];
$pending_activities = $conn->query("SELECT COUNT(*) as count FROM activities WHERE status = 'pending'")->fetch_assoc()['count'];
$total_recycled = $conn->query("SELECT SUM(quantity) as total FROM activities WHERE status = 'verified'")->fetch_assoc()['total'] ?? 0;

$recent_activities = $conn->query("SELECT a.*, u.username, u.full_name FROM activities a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 10");

$top_users = $conn->query("SELECT id, username, full_name, points, rank FROM users WHERE rank > 0 ORDER BY rank ASC LIMIT 5");
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <h2><i class="fas fa-cog"></i> Admin Panel</h2>
            <p>EcoRecycle Hub Management</p>
        </div>
        
        <ul class="admin-menu">
            <li><a href="index.php" class="active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="users.php">
                <i class="fas fa-users"></i> Users Management
            </a></li>
            <li><a href="activities.php">
                <i class="fas fa-recycle"></i> Activities
            </a></li>
            <li><a href="reports.php">
                <i class="fas fa-chart-bar"></i> Reports
            </a></li>
            <li><a href="settings.php">
                <i class="fas fa-sliders-h"></i> Settings
            </a></li>
        </ul>
        
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <a href="../auth/logout.php" style="display: flex; align-items: center; gap: 15px; padding: 15px 25px; color: #FF6B6B; text-decoration: none; transition: all 0.3s ease; border-radius: 8px;">
                <i class="fas fa-sign-out-alt" style="width: 20px; text-align: center; font-size: 18px;"></i>
                <span style="font-weight: 500;">Logout</span>
            </a>
        </div>
    </aside>
    
    <main class="admin-content">
        <div class="admin-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p style="color: var(--text-light); margin-top: 5px;">Welcome back, <?php echo $_SESSION['username']; ?>!</p>
            </div>
            <div>
                <span style="color: var(--text-light); font-size: 14px;">
                    <i class="fas fa-clock"></i> <?php echo date('l, F d, Y'); ?>
                </span>
            </div>
        </div>
        
        <div class="admin-stats">
            <div class="admin-stat-card">
                <div class="admin-stat-icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
                <div class="admin-stat-info">
                    <h4>Total Users</h4>
                    <p><?php echo number_format($total_users); ?></p>
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-icon icon-green">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="admin-stat-info">
                    <h4>Total Activities</h4>
                    <p><?php echo number_format($total_activities); ?></p>
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-icon icon-gold">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="admin-stat-info">
                    <h4>Pending Review</h4>
                    <p><?php echo number_format($pending_activities); ?></p>
                </div>
            </div>
            
            <div class="admin-stat-card">
                <div class="admin-stat-icon icon-purple">
                    <i class="fas fa-weight"></i>
                </div>
                <div class="admin-stat-info">
                    <h4>Total Recycled</h4>
                    <p><?php echo number_format($total_recycled, 1); ?> kg</p>
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Recent Activities</h3>
                <a href="activities.php" class="btn btn-primary">View All</a>
            </div>
            
            <?php if ($recent_activities->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Material</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($activity = $recent_activities->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($activity['username']); ?></td>
                                <td><?php echo getMaterialIcon($activity['material_type']); ?> <?php echo ucfirst($activity['material_type']); ?></td>
                                <td><?php echo $activity['quantity']; ?> <?php echo $activity['unit']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($activity['activity_date'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $activity['status']; ?>">
                                        <?php echo ucfirst($activity['status']); ?>
                                    </span>
                                </td>
                                <td><strong><?php echo $activity['points_earned']; ?></strong></td>
                                <td>
                                    <div class="admin-actions">
                                        <?php if ($activity['status'] === 'pending'): ?>
                                            <button class="admin-btn-sm admin-btn-approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="admin-btn-sm admin-btn-reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="admin-btn-sm admin-btn-edit">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">No activities found</p>
            <?php endif; ?>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Top Users</h3>
                <a href="users.php" class="btn btn-outline">View All</a>
            </div>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = $top_users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo getRankBadge($user['rank']); ?> #<?php echo $user['rank']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                            <td><strong style="color: var(--primary-gold);"><?php echo number_format($user['points']); ?></strong></td>
                            <td>
                                <div class="admin-actions">
                                    <button class="admin-btn-sm admin-btn-edit">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>

