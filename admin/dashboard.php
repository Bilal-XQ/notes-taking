<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is admin
require_admin();

// Get all students
$sql = "SELECT s.id, s.full_name, s.username, a.username as created_by 
        FROM Student s 
        LEFT JOIN Administrateur a ON s.admin_id = a.id 
        ORDER BY s.id DESC";
$result = query($sql);
$students = fetch_all($result);

include '../includes/header.php';
?>

<div class="dashboard">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
    
    <div class="dashboard-actions">
        <a href="<?php echo SITE_URL; ?>/admin/add_student.php" class="btn btn-primary">Add New Student</a>
    </div>
    
    <div class="dashboard-content">
        <h3>Manage Students</h3>
        
        <?php if (empty($students)): ?>
            <p>No students found.</p>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student['id']; ?></td>
                            <td><?php echo $student['full_name']; ?></td>
                            <td><?php echo $student['username']; ?></td>
                            <td><?php echo $student['created_by'] ?? 'N/A'; ?></td>
                            <td class="actions">
                                <a href="<?php echo SITE_URL; ?>/admin/edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/admin/delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
