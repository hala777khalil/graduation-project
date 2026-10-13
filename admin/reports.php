<?php
$page_title = 'Reports';
$additional_css = ['admin.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotAdmin();

$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_activities = $conn->query("SELECT COUNT(*) as count FROM activities WHERE status = 'verified'")->fetch_assoc()['count'];
$total_recycled = $conn->query("SELECT SUM(quantity) as total FROM activities WHERE status = 'verified'")->fetch_assoc()['total'] ?? 0;
$total_points = $conn->query("SELECT SUM(points) as total FROM users")->fetch_assoc()['total'] ?? 0;

$material_stats = $conn->query("SELECT material_type, SUM(quantity) as total_quantity, COUNT(*) as activity_count FROM activities WHERE status = 'verified' GROUP BY material_type ORDER BY total_quantity DESC");

$monthly_stats = $conn->query("SELECT DATE_FORMAT(activity_date, '%Y-%m') as month, COUNT(*) as count, SUM(quantity) as total FROM activities WHERE status = 'verified' GROUP BY month ORDER BY month DESC LIMIT 12");
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <h2><i class="fas fa-cog"></i> Admin Panel</h2>
            <p>EcoRecycle Hub Management</p>
        </div>
        
        <ul class="admin-menu">
            <li><a href="index.php">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a></li>
            <li><a href="users.php">
                <i class="fas fa-users"></i> Users Management
            </a></li>
            <li><a href="activities.php">
                <i class="fas fa-recycle"></i> Activities
            </a></li>
            <li><a href="reports.php" class="active">
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
            <h1>System Reports</h1>
            <button class="btn btn-gold" onclick="window.print()">
                <i class="fas fa-print"></i> Print Report
            </button>
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
                    <h4>Verified Activities</h4>
                    <p><?php echo number_format($total_activities); ?></p>
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
            
            <div class="admin-stat-card">
                <div class="admin-stat-icon icon-gold">
                    <i class="fas fa-star"></i>
                </div>
                <div class="admin-stat-info">
                    <h4>Total Points</h4>
                    <p><?php echo number_format($total_points); ?></p>
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Recycling by Material Type</h3>
            </div>
            
            <?php if ($material_stats->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Total Quantity (kg)</th>
                            <th>Activity Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($stat = $material_stats->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo getMaterialIcon($stat['material_type']); ?> <?php echo ucfirst($stat['material_type']); ?></td>
                                <td><strong><?php echo number_format($stat['total_quantity'], 2); ?></strong></td>
                                <td><?php echo $stat['activity_count']; ?></td>
                                <td><?php echo number_format(($stat['total_quantity'] / $total_recycled) * 100, 1); ?>%</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Monthly Activity Trends</h3>
            </div>
            
            <?php if ($monthly_stats->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Activities</th>
                            <th>Total Recycled (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($stat = $monthly_stats->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('F Y', strtotime($stat['month'] . '-01')); ?></td>
                                <td><?php echo number_format($stat['count']); ?></td>
                                <td><strong><?php echo number_format($stat['total'], 2); ?></strong></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>

