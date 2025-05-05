<?php
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/session.php';

start_session();

// Redirect to appropriate dashboard if logged in
if (is_admin()) {
    header("Location: " . SITE_URL . "/admin/dashboard.php");
    exit();
} elseif (is_student()) {
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}

include 'includes/header.php';
?>

<div class="welcome-section">
    <h2>Welcome to Student Notes Manager</h2>
    <p>A simple and efficient way to organize your academic notes by module.</p>
    
    <div class="login-options">
        <div class="login-card">
            <h3>Student Access</h3>
            <p>Login to manage your notes organized by module.</p>
            <a href="<?php echo SITE_URL; ?>/auth/login.php" class="btn">Student Login</a>
        </div>
        
        <div class="login-card">
            <h3>Administrator Access</h3>
            <p>Login to manage students and system settings.</p>
            <a href="<?php echo SITE_URL; ?>/auth/admin_login.php" class="btn">Admin Login</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
