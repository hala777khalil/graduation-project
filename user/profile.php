<?php
$page_title = 'Profile';
$additional_css = ['dashboard.css', 'profile.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $full_name, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $success = 'Profile updated successfully!';
    } else {
        $error = 'Failed to update profile';
    }
}

$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

$stats_query = $conn->query("SELECT 
    COUNT(*) as total_activities,
    SUM(CASE WHEN status = 'verified' THEN quantity ELSE 0 END) as total_recycled
    FROM activities WHERE user_id = $user_id");
$stats = $stats_query->fetch_assoc();
?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="dashboard-main">
        <div class="profile-container">
            <div class="profile-header-card">
                <div class="profile-avatar-section">
                    <div class="profile-avatar-huge">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <h1 class="profile-name"><?php echo htmlspecialchars($user['full_name']); ?></h1>
                    <p class="profile-username">@<?php echo htmlspecialchars($user['username']); ?></p>
                    
                    <div class="profile-stats-row">
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?php echo number_format($user['points']); ?></span>
                            <span class="profile-stat-label">Points</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value">#<?php echo $user['rank'] ?: '-'; ?></span>
                            <span class="profile-stat-label">Rank</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?php echo $stats['total_activities']; ?></span>
                            <span class="profile-stat-label">Activities</span>
                        </div>
                        <div class="profile-stat-item">
                            <span class="profile-stat-value"><?php echo number_format($stats['total_recycled'], 1); ?></span>
                            <span class="profile-stat-label">Kg Recycled</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="profile-section">
                <h2 class="section-title-profile">
                    <i class="fas fa-user-circle"></i> Personal Information
                </h2>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Member Since</label>
                        <input type="text" value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" disabled>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
            
            <div class="profile-section">
                <h2 class="section-title-profile">
                    <i class="fas fa-trophy"></i> Achievements
                </h2>
                
                <div class="achievements-grid">
                    <div class="achievement-item">
                        <div class="achievement-icon-large">🥇</div>
                        <div class="achievement-name">First Activity</div>
                        <div class="achievement-date">Earned</div>
                    </div>
                    
                    <div class="achievement-item">
                        <div class="achievement-icon-large">♻️</div>
                        <div class="achievement-name">Eco Warrior</div>
                        <div class="achievement-date">Earned</div>
                    </div>
                    
                    <div class="achievement-item">
                        <div class="achievement-icon-large">🌟</div>
                        <div class="achievement-name">100 Points</div>
                        <div class="achievement-date">
                            <?php echo $user['points'] >= 100 ? 'Earned' : 'Locked'; ?>
                        </div>
                    </div>
                    
                    <div class="achievement-item">
                        <div class="achievement-icon-large">🏆</div>
                        <div class="achievement-name">Top 10</div>
                        <div class="achievement-date">
                            <?php echo ($user['rank'] && $user['rank'] <= 10) ? 'Earned' : 'Locked'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</div>
</body>
</html>