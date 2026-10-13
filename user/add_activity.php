<?php
$page_title = 'Add Activity';
$additional_css = ['dashboard.css', 'activities.css'];
require_once '../includes/dashboard_header.php';

redirectIfNotLoggedIn();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_type = sanitizeInput($_POST['material_type']);
    $quantity = floatval($_POST['quantity']);
    $unit = sanitizeInput($_POST['unit']);
    $activity_date = sanitizeInput($_POST['activity_date']);
    $description = sanitizeInput($_POST['description']);
    $recycling_center_id = !empty($_POST['recycling_center']) ? intval($_POST['recycling_center']) : null;
    
    if (empty($material_type) || $quantity <= 0 || empty($unit) || empty($activity_date)) {
        $error = 'Please fill in all required fields';
    } else {
        $points_earned = calculatePoints($material_type, $quantity);
        $user_id = $_SESSION['user_id'];
        $status = 'verified';
        
        $stmt = $conn->prepare("INSERT INTO activities (user_id, material_type, quantity, unit, activity_date, description, recycling_center_id, points_earned, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdssssis", $user_id, $material_type, $quantity, $unit, $activity_date, $description, $recycling_center_id, $points_earned, $status);
        
        if ($stmt->execute()) {
            $conn->query("UPDATE users SET points = points + $points_earned WHERE id = $user_id");
            updateUserRank($conn, $user_id);
            sendNotification($conn, $user_id, 'Activity Added', "You earned $points_earned points for recycling $quantity $unit of $material_type", 'success');
            $success = "Activity added successfully! You earned $points_earned points.";
        } else {
            $error = 'Failed to add activity. Please try again.';
        }
    }
}

$centers = $conn->query("SELECT * FROM recycling_centers WHERE status = 'active' ORDER BY name");
?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <?php include 'sidebar.php'; ?>
    </aside>
    
    <main class="dashboard-main">
        <div class="activity-form-container">
            <div class="form-card">
                <div class="form-header">
                    <div class="form-icon icon-green">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <h1 class="form-title">Log Recycling Activity</h1>
                    <p class="form-subtitle">Track your contribution to a sustainable future</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Material Type *</label>
                        <div class="material-select">
                            <div class="material-option">
                                <input type="radio" name="material_type" value="plastic" id="plastic" required>
                                <label for="plastic" class="material-label material-plastic">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-recycle"></i>
                                    </div>
                                    <span class="material-name">Plastic</span>
                                    <span class="material-points">+10 pts/kg</span>
                                </label>
                            </div>
                            <div class="material-option">
                                <input type="radio" name="material_type" value="paper" id="paper">
                                <label for="paper" class="material-label material-paper">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <span class="material-name">Paper</span>
                                    <span class="material-points">+5 pts/kg</span>
                                </label>
                            </div>
                            <div class="material-option">
                                <input type="radio" name="material_type" value="glass" id="glass">
                                <label for="glass" class="material-label material-glass">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-wine-bottle"></i>
                                    </div>
                                    <span class="material-name">Glass</span>
                                    <span class="material-points">+12 pts/kg</span>
                                </label>
                            </div>
                            <div class="material-option">
                                <input type="radio" name="material_type" value="metal" id="metal">
                                <label for="metal" class="material-label material-metal">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-wrench"></i>
                                    </div>
                                    <span class="material-name">Metal</span>
                                    <span class="material-points">+15 pts/kg</span>
                                </label>
                            </div>
                            <div class="material-option">
                                <input type="radio" name="material_type" value="electronics" id="electronics">
                                <label for="electronics" class="material-label material-electronics">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <span class="material-name">Electronics</span>
                                    <span class="material-points">+20 pts/item</span>
                                </label>
                            </div>
                            <div class="material-option">
                                <input type="radio" name="material_type" value="cardboard" id="cardboard">
                                <label for="cardboard" class="material-label material-cardboard">
                                    <div class="material-icon-wrapper">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <span class="material-name">Cardboard</span>
                                    <span class="material-points">+7 pts/kg</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantity">
                                <i class="fas fa-weight"></i> Quantity *
                            </label>
                            <input type="number" id="quantity" name="quantity" step="0.1" min="0.1" required placeholder="Enter quantity">
                        </div>
                        
                        <div class="form-group">
                            <label for="unit">
                                <i class="fas fa-balance-scale"></i> Unit *
                            </label>
                            <select id="unit" name="unit" required>
                                <option value="">Select unit</option>
                                <option value="kg">Kilograms (kg)</option>
                                <option value="lbs">Pounds (lbs)</option>
                                <option value="items">Items</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="activity_date">
                            <i class="fas fa-calendar-alt"></i> Activity Date *
                        </label>
                        <input type="date" id="activity_date" name="activity_date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="recycling_center">
                            <i class="fas fa-map-marker-alt"></i> Recycling Center (Optional)
                        </label>
                        <select id="recycling_center" name="recycling_center">
                            <option value="">Select center</option>
                            <?php while($center = $centers->fetch_assoc()): ?>
                                <option value="<?php echo $center['id']; ?>">
                                    <?php echo htmlspecialchars($center['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-comment-alt"></i> Description (Optional)
                        </label>
                        <textarea id="description" name="description" rows="4" placeholder="Add any additional details about this activity..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-submit">
                            <i class="fas fa-check-circle"></i> Submit Activity
                        </button>
                        <a href="dashboard.php" class="btn btn-outline btn-cancel">
                            <i class="fas fa-times-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

</div>
</body>
</html>

