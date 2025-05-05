<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is admin
require_admin();

$full_name = "";
$username = "";
$error = "";
$success = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($full_name) || empty($username) || empty($password)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM Student WHERE username = '$username'";
        $result = query($sql);
        
        if (num_rows($result) > 0) {
            $error = "Username already exists";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $admin_id = $_SESSION['user_id'];
            
            // Insert new student
            $sql = "INSERT INTO Student (full_name, username, password, admin_id) 
                    VALUES ('$full_name', '$username', '$hashed_password', $admin_id)";
            query($sql);
            
            $success = "Student added successfully";
            $full_name = "";
            $username = "";
        }
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h2>Add New Student</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
