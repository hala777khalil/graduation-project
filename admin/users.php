<?php
$page_title = 'Users Management';
$additional_css = ['admin.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotAdmin();

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$filter = isset($_GET['filter']) ? sanitizeInput($_GET['filter']) : 'all';

$query = "SELECT * FROM users WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (username LIKE '%$search%' OR email LIKE '%$search%' OR full_name LIKE '%$search%')";
}

if ($filter === 'admin') {
    $query .= " AND role = 'admin'";
} elseif ($filter === 'user') {
    $query .= " AND role = 'user'";
}

$query .= " ORDER BY created_at DESC";
$users = $conn->query($query);
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
            <li><a href="users.php" class="active">
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
            <h1>Users Management</h1>
        </div>
        
        <div class="admin-card">
            <div class="filters-bar">
                <input type="text" class="filter-input" placeholder="Search by name, username, or email..." value="<?php echo htmlspecialchars($search); ?>" onchange="window.location.href='?search=' + this.value + '&filter=<?php echo $filter; ?>'">
                
                <select class="filter-input" onchange="window.location.href='?search=<?php echo $search; ?>&filter=' + this.value">
                    <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All Users</option>
                    <option value="user" <?php echo $filter === 'user' ? 'selected' : ''; ?>>Regular Users</option>
                    <option value="admin" <?php echo $filter === 'admin' ? 'selected' : ''; ?>>Administrators</option>
                </select>
            </div>
            
            <?php if ($users->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Points</th>
                            <th>Rank</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'gold' : 'blue'; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><strong><?php echo number_format($user['points']); ?></strong></td>
                                <td>#<?php echo $user['rank'] ?: '-'; ?></td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="admin-actions">
                                        <button class="admin-btn-sm admin-btn-edit">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="admin-btn-sm admin-btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-light); padding: 40px 0;">No users found</p>
            <?php endif; ?>
        </div>
    </main>
</div>

</body>
</html>

