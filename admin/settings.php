<?php
$page_title = 'Settings';
$additional_css = ['admin.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotAdmin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'points_per_kg_') === 0) {
            $stmt = $conn->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->bind_param("ss", $value, $key);
            $stmt->execute();
        }
    }
    $success = 'Settings updated successfully!';
}

$settings = $conn->query("SELECT * FROM system_settings ORDER BY setting_key");
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
            <li><a href="reports.php">
                <i class="fas fa-chart-bar"></i> Reports
            </a></li>
            <li><a href="settings.php" class="active">
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
            <h1>System Settings</h1>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>Points Configuration</h3>
            </div>
            
            <form method="POST">
                <?php 
                $settings->data_seek(0);
                while($setting = $settings->fetch_assoc()): 
                    if (strpos($setting['setting_key'], 'points_per_kg_') === 0):
                ?>
                    <div class="form-group">
                        <label for="<?php echo $setting['setting_key']; ?>">
                            <?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?>
                        </label>
                        <input type="number" 
                               id="<?php echo $setting['setting_key']; ?>" 
                               name="<?php echo $setting['setting_key']; ?>" 
                               value="<?php echo $setting['setting_value']; ?>"
                               min="1"
                               required>
                        <small style="color: var(--text-light); display: block; margin-top: 5px;">
                            <?php echo $setting['description']; ?>
                        </small>
                    </div>
                <?php 
                    endif;
                endwhile; 
                ?>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </form>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3>System Information</h3>
            </div>
            
            <?php 
            $settings->data_seek(0);
            while($setting = $settings->fetch_assoc()): 
                if (strpos($setting['setting_key'], 'site_') === 0):
            ?>
                <div style="padding: 15px; background: var(--light-bg); border-radius: 8px; margin-bottom: 15px;">
                    <div style="font-weight: 600; color: var(--text-dark); margin-bottom: 5px;">
                        <?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?>
                    </div>
                    <div style="color: var(--text-light);">
                        <?php echo $setting['setting_value']; ?>
                    </div>
                </div>
            <?php 
                endif;
            endwhile; 
            ?>
        </div>
    </main>
</div>

</body>
</html>

