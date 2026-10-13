<?php

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/auth/login.php');
        exit;
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . '/index.php');
        exit;
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function calculatePoints($material, $quantity) {
    global $POINTS_CONFIG;
    $material = strtolower($material);
    if (isset($POINTS_CONFIG[$material])) {
        return floor($quantity * $POINTS_CONFIG[$material]);
    }
    return floor($quantity * 5);
}

function updateUserRank($conn, $user_id) {
    $query = "SELECT id, points FROM users ORDER BY points DESC";
    $result = $conn->query($query);
    $rank = 1;
    while ($row = $result->fetch_assoc()) {
        $conn->query("UPDATE users SET rank = $rank WHERE id = " . $row['id']);
        $rank++;
    }
}

function getTimeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return "Just now";
    if ($diff < 3600) return floor($diff / 60) . " minutes ago";
    if ($diff < 86400) return floor($diff / 3600) . " hours ago";
    if ($diff < 604800) return floor($diff / 86400) . " days ago";
    return date('M d, Y', $timestamp);
}

function formatNumber($number) {
    if ($number >= 1000000) {
        return number_format($number / 1000000, 1) . 'M';
    }
    if ($number >= 1000) {
        return number_format($number / 1000, 1) . 'K';
    }
    return number_format($number);
}

function uploadImage($file, $folder = 'profiles') {
    $target_dir = UPLOAD_PATH . $folder . '/';
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $newFileName = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $newFileName;
    
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        return false;
    }
    
    if ($file["size"] > 5000000) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $folder . '/' . $newFileName;
    }
    
    return false;
}

function sendNotification($conn, $user_id, $title, $message, $type = 'info', $link = null) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type, link) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $message, $type, $link);
    return $stmt->execute();
}

function getUnreadNotifications($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND read_status = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

function getMaterialIcon($material) {
    $icons = [
        'plastic' => '♻️',
        'paper' => '📄',
        'glass' => '🍾',
        'metal' => '🔩',
        'electronics' => '💻',
        'organic' => '🌱',
        'cardboard' => '📦'
    ];
    return $icons[strtolower($material)] ?? '♻️';
}

function getRankBadge($rank) {
    if ($rank == 1) return '🥇';
    if ($rank == 2) return '🥈';
    if ($rank == 3) return '🥉';
    return '🏅';
}

?>

