<?php
$user_query = $conn->query("SELECT * FROM users WHERE id = " . $_SESSION['user_id']);
$user = $user_query->fetch_assoc();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="user-profile">
    <div class="user-avatar-large">
        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
    </div>
    <div class="user-info">
        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
        <p class="user-rank">Rank #<?php echo $user['rank'] ?: 'Unranked'; ?></p>
    </div>
</div>

<ul class="dashboard-nav">
    <li><a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a></li>
    <li><a href="add_activity.php" class="<?php echo $current_page == 'add_activity.php' ? 'active' : ''; ?>">
        <i class="fas fa-plus-circle"></i>
        <span>Add Activity</span>
    </a></li>
    <li><a href="my_activities.php" class="<?php echo $current_page == 'my_activities.php' ? 'active' : ''; ?>">
        <i class="fas fa-list"></i>
        <span>My Activities</span>
    </a></li>
    <li><a href="my_points.php" class="<?php echo $current_page == 'my_points.php' ? 'active' : ''; ?>">
        <i class="fas fa-star"></i>
        <span>My Points</span>
    </a></li>
    <li><a href="profile.php" class="<?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a></li>
</ul>

<div class="sidebar-logout">
    <a href="<?php echo SITE_URL; ?>/auth/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>

