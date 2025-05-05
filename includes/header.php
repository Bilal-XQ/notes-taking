<?php
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/session.php';
start_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1><?php echo SITE_NAME; ?></h1>
            </div>
            <nav>
                <ul>
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php">Dashboard</a></li>
                        <?php elseif (is_student()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/students/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo SITE_URL; ?>/auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo SITE_URL; ?>/auth/login.php">Student Login</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/auth/admin_login.php">Admin Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
