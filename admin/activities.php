<?php
$page_title = 'Activities Management';
$additional_css = ['admin.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $activity_id = intval($_POST['activity_id']);
    $action = $_POST['action'];
    
    if ($action === 'approve') {
        $conn->query("UPDATE activities SET status = 'verified', verified_by = {$_SESSION['user_id']}, verified_at = NOW() WHERE id = $activity_id");
    } elseif ($action === 'reject') {
        $conn->query("UPDATE activities SET status = 'rejected', verified_by = {$_SESSION['user_id']}, verified_at = NOW() WHERE id = $activity_id");
    }
    
    header('Location: activities.php');
    exit;
}

$filter_status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : 'all';

$query = "SELECT a.*, u.username, u.full_name FROM activities a JOIN users u ON a.user_id = u.id WHERE 1=1";

if ($filter_status !== 'all') {
    $query .= " AND a.status = '$filter_status'";
}

$query .= " ORDER BY a.created_at DESC";
$activities = $conn->query($query);
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
            <li><a href="activities.php" class="active">
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
            <h1>Activities Management</h1>
        </div>
        
        <div class="admin-card">
            <div class="filters-bar">
                <select class="filter-input" onchange="window.location.href='?status=' + this.value">
                    <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="verified" <?php echo $filter_status === 'verified' ? 'selected' : ''; ?>>Verified</option>
                    <option value="rejected" <?php echo $filter_status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            
            <?php if ($activities->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                        <?php while($activity = $activities->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $activity['id']; ?></td>
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
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="admin-btn-sm admin-btn-approve">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="admin-btn-sm admin-btn-reject">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="admin-btn-sm admin-btn-edit">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        <?php endif; ?>
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
    </main>
</div>

</body>
</html>

