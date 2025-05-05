<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is admin
require_admin();

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . SITE_URL . "/admin/dashboard.php");
    exit();
}

$student_id = (int)$_GET['id'];

// Delete student
$sql = "DELETE FROM Student WHERE id = $student_id";
query($sql);

// Redirect back to dashboard
header("Location: " . SITE_URL . "/admin/dashboard.php");
exit();
?>
