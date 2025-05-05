<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is admin
require_admin();

$error = "";
$success = "";

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . SITE_URL . "/admin/dashboard.php");
    exit();
}

$student_id = (int)$_GET['id'];

// Get student data
$sql = "SELECT * FROM Student WHERE id = $student_id";
$result = query($sql);

if (num_rows($result) !== 1) {
    header("Location: " . SITE_URL . "/admin/dashboard.php");
    exit();
}

$student = fetch_assoc($result);
$full_name = $student['full_name'];
$username = $student['username'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($full_name) || empty($username)) {
        $error = "Full name and username are required";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if username already exists (excluding current student)
        $sql = "SELECT id FROM Student WHERE username = '$username' AND id != $student_id";
        $result = query($sql);
        
        if (num_rows($result) > 0) {
            $error = "Username already exists";
        } else {
            // Update student
            if (!empty($password)) {
                // Update with new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE Student SET full_name = '$full_name', username = '$username', password = '$hashed_password' WHERE id = $student_id";
            } else {
                // Update without changing password
                $sql = "UPDATE Student SET full_name = '$full_name', username = '$username' WHERE id = $student_id";
            }
            
            query($sql);
            $success = "Student updated successfully";
        }
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h2>Edit Student</h2>
    
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
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
