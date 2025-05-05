<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is student
require_student();

$student_id = $_SESSION['user_id'];

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}

$note_id = (int)$_GET['id'];

// Verify note belongs to student
$sql = "SELECT id FROM Notes WHERE id = $note_id AND student_id = $student_id";
$result = query($sql);

if (num_rows($result) !== 1) {
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}

// Delete note
$sql = "DELETE FROM Notes WHERE id = $note_id";
query($sql);

// Redirect back to dashboard
header("Location: " . SITE_URL . "/students/dashboard.php");
exit();
?>
