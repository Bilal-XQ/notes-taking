<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

start_session();

// Redirect if already logged in
if (is_logged_in()) {
    if (is_admin()) {
        header("Location: " . SITE_URL . "/admin/dashboard.php");
    } else {
        header("Location: " . SITE_URL . "/students/dashboard.php");
    }
    exit();
}

$username = "";
$password = "";
$remember = false;
$error = "";

// Check for cookies
if (isset($_COOKIE['admin_username']) && isset($_COOKIE['admin_password'])) {
    $username = $_COOKIE['admin_username'];
    $password = $_COOKIE['admin_password'];
    $remember = true;
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);
    
    if (empty($username) || empty($password)) {
        $error = "Username and password are required";
    } else {
        // Check if admin exists
        $sql = "SELECT id, username, password FROM Administrateur WHERE username = '$username'";
        $result = query($sql);
        
        if (num_rows($result) === 1) {
            $admin = fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Set session
                set_user_session($admin['id'], $admin['username'], 'admin');
                
                // Set cookies if remember me is checked
                if ($remember) {
                    setcookie('admin_username', $username, time() + COOKIE_EXPIRY, '/');
                    setcookie('admin_password', $password, time() + COOKIE_EXPIRY, '/');
                } else {
                    // Clear cookies if not checked
                    setcookie('admin_username', '', time() - 3600, '/');
                    setcookie('admin_password', '', time() - 3600, '/');
                }
                
                header("Location: " . SITE_URL . "/admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}

include '../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Administrator Login</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" value="<?php echo $password; ?>" required>
            </div>
            
            <div class="form-group checkbox">
                <input type="checkbox" id="remember" name="remember" <?php echo $remember ? 'checked' : ''; ?>>
                <label for="remember">Remember me for 7 days</label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        
        <div class="auth-links">
            <p>Are you a student? <a href="<?php echo SITE_URL; ?>/auth/login.php">Student Login</a></p>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
