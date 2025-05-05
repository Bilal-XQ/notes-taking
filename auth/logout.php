<?php
require_once '../config.php';
require_once '../includes/session.php';

// Clear session
clear_session();

// Clear cookies
setcookie('student_username', '', time() - 3600, '/');
setcookie('student_password', '', time() - 3600, '/');
setcookie('admin_username', '', time() - 3600, '/');
setcookie('admin_password', '', time() - 3600, '/');

// Redirect to home page
header("Location: " . SITE_URL . "/index.php");
exit();
?>
