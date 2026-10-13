<?php
$page_title = 'Email Verification';
$additional_css = ['auth.css'];
require_once '../includes/header.php';

$message = '';
$type = 'info';

if (isset($_GET['token'])) {
    $token = sanitizeInput($_GET['token']);
    
    $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = 'Your email has been verified successfully! You can now login.';
        $type = 'success';
    } else {
        $message = 'Invalid or expired verification token.';
        $type = 'error';
    }
}
?>

<div class="auth-container">
    <div class="auth-box">
        <div class="auth-header">
            <div class="auth-icon <?php echo $type === 'success' ? 'icon-green' : 'icon-blue'; ?>">
                <i class="fas fa-<?php echo $type === 'success' ? 'check-circle' : 'envelope'; ?>"></i>
            </div>
            <h1 class="auth-title">Email Verification</h1>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($type === 'success'): ?>
            <a href="login.php" class="btn btn-primary" style="width: 100%; text-align: center;">Go to Login</a>
        <?php else: ?>
            <a href="register.php" class="btn btn-gold" style="width: 100%; text-align: center;">Register Again</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

