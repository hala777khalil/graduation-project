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
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo SITE_URL . '/assets/css/' . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div style="width: 100%; min-height: 100vh;">

