<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is student
require_student();

$student_id = $_SESSION['user_id'];
$error = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_name = sanitize($_POST['module_name']);
    
    if (empty($module_name)) {
        $error = "Module name is required";
    } else {
        // Check if module already exists for this student
        $sql = "SELECT id FROM Modules WHERE name = '$module_name' AND student_id = $student_id";
        $result = query($sql);
        
        if (num_rows($result) > 0) {
            $error = "Module already exists";
        } else {
            // Insert new module
            $sql = "INSERT INTO Modules (name, student_id) VALUES ('$module_name', $student_id)";
            query($sql);
            
            // Redirect back to dashboard
            header("Location: " . SITE_URL . "/students/dashboard.php");
            exit();
        }
    }
}

// If there was an error, redirect back with error message
if (!empty($error)) {
    // In a real application, you would use sessions to pass error messages
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}
?>
