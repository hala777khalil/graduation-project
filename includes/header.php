<?php
if (!defined('DB_HOST')) {
    require_once dirname(__DIR__) . '/config/config.php';
}
if (!isset($conn)) {
    require_once dirname(__DIR__) . '/config/db_connect.php';
}
require_once dirname(__DIR__) . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'EcoRecycle Hub'; ?> - Recycling Activity System</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/global.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/header.css">
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo SITE_URL . '/assets/css/' . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="logo-text">
                    <div class="logo-title">EcoRecycle Hub</div>
                    <div class="logo-subtitle">Save Earth Together</div>
                </div>
            </a>
            
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Home
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/about.php">
                        <i class="fas fa-info-circle"></i> About
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/leaderboard.php">
                        <i class="fas fa-trophy"></i> Leaderboard
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/map.php">
                        <i class="fas fa-map-marked-alt"></i> Centers Map
                    </a></li>
                    <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a></li>
                </ul>
            </nav>
            
            <div class="header-actions">
                <?php if (isLoggedIn()): ?>
                    <div class="user-menu">
                        <div class="user-trigger">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                            <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="dropdown-menu">
                            <a href="<?php echo SITE_URL; ?>/user/dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="<?php echo SITE_URL; ?>/user/profile.php">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a href="<?php echo SITE_URL; ?>/user/my_activities.php">
                                <i class="fas fa-list"></i> My Activities
                            </a>
                            <a href="<?php echo SITE_URL; ?>/user/my_points.php">
                                <i class="fas fa-star"></i> My Points
                            </a>
                            <?php if (isAdmin()): ?>
                                <a href="<?php echo SITE_URL; ?>/admin/index.php">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo SITE_URL; ?>/auth/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn btn-outline">Login</a>
                    <a href="<?php echo SITE_URL; ?>/auth/register.php" class="btn btn-gold">Register</a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    <main>

