<?php
$page_title = 'My Activities';
$additional_css = ['dashboard.css', 'activities.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$filter_status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : 'all';
$filter_material = isset($_GET['material']) ? sanitizeInput($_GET['material']) : 'all';

$query = "SELECT a.*, rc.name as center_name FROM activities a 
          LEFT JOIN recycling_centers rc ON a.recycling_center_id = rc.id 
          WHERE a.user_id = $user_id";

if ($filter_status !== 'all') {
    $query .= " AND a.status = '$filter_status'";
}
if ($filter_material !== 'all') {
    $query .= " AND a.material_type = '$filter_material'";
}

$query .= " ORDER BY a.created_at DESC";
$activities = $conn->query($query);
?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="dashboard-main">
        <div class="activities-table-container">
            <div class="table-header">
                <div>
                    <h2 style="font-size: 28px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px;">My Activities</h2>
                    <p style="color: var(--text-light);">View and manage all your recycling activities</p>
                </div>
                <a href="add_activity.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Activity
                </a>
            </div>
            
            <div class="table-filters">
                <select class="filter-select" onchange="window.location.href='?status=' + this.value + '&material=<?php echo $filter_material; ?>'">
                    <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="verified" <?php echo $filter_status === 'verified' ? 'selected' : ''; ?>>Verified</option>
                    <option value="rejected" <?php echo $filter_status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
                
                <select class="filter-select" onchange="window.location.href='?status=<?php echo $filter_status; ?>&material=' + this.value">
                    <option value="all" <?php echo $filter_material === 'all' ? 'selected' : ''; ?>>All Materials</option>
                    <option value="plastic" <?php echo $filter_material === 'plastic' ? 'selected' : ''; ?>>Plastic</option>
                    <option value="paper" <?php echo $filter_material === 'paper' ? 'selected' : ''; ?>>Paper</option>
                    <option value="glass" <?php echo $filter_material === 'glass' ? 'selected' : ''; ?>>Glass</option>
                    <option value="metal" <?php echo $filter_material === 'metal' ? 'selected' : ''; ?>>Metal</option>
                    <option value="electronics" <?php echo $filter_material === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                </select>
            </div>
            
            <?php if ($activities->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Material</th>
                            <th>Quantity</th>
                            <th>Center</th>
                            <th>Status</th>
                            <th>Points</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($activity = $activities->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($activity['activity_date'])); ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 20px;"><?php echo getMaterialIcon($activity['material_type']); ?></span>
                                        <?php echo ucfirst($activity['material_type']); ?>
                                    </div>
                                </td>
                                <td><?php echo $activity['quantity']; ?> <?php echo $activity['unit']; ?></td>
                                <td><?php echo $activity['center_name'] ?: '-'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $activity['status']; ?>">
                                        <?php echo ucfirst($activity['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: var(--primary-gold);">
                                        <?php echo $activity['status'] === 'verified' ? '+' . $activity['points_earned'] : '-'; ?>
                                    </strong>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon btn-edit" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($activity['status'] === 'pending'): ?>
                                            <button class="btn-icon btn-delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h3>No Activities Found</h3>
                    <p>Start logging your recycling activities to track your impact!</p>
                    <a href="add_activity.php" class="btn btn-primary" style="margin-top: 20px;">
                        <i class="fas fa-plus"></i> Add Your First Activity
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

</div>
</body>
</html>

