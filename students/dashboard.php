<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is student
require_student();

$student_id = $_SESSION['user_id'];

// Get student data
$sql = "SELECT * FROM Student WHERE id = $student_id";
$result = query($sql);
$student = fetch_assoc($result);

// Get modules
$sql = "SELECT * FROM Modules WHERE student_id = $student_id ORDER BY name";
$result = query($sql);
$modules = fetch_all($result);

// Get notes
$sql = "SELECT n.*, m.name as module_name 
        FROM Notes n 
        JOIN Modules m ON n.module_id = m.id 
        WHERE n.student_id = $student_id 
        ORDER BY n.created_at DESC";
$result = query($sql);
$notes = fetch_all($result);

include '../includes/header.php';
?>

<div class="dashboard">
    <h2>Student Dashboard</h2>
    <p>Welcome, <?php echo $student['full_name']; ?>!</p>
    
    <div class="dashboard-actions">
        <a href="<?php echo SITE_URL; ?>/students/add_note.php" class="btn btn-primary">Add New Note</a>
    </div>
    
    <div class="dashboard-content">
        <div class="modules-section">
            <h3>Your Modules</h3>
            
            <?php if (empty($modules)): ?>
                <p>No modules found. <a href="#" class="add-module-link">Add a module</a> to get started.</p>
            <?php else: ?>
                <div class="module-list">
                    <?php foreach ($modules as $module): ?>
                        <div class="module-item">
                            <span class="module-name"><?php echo $module['name']; ?></span>
                            <div class="module-actions">
                                <a href="<?php echo SITE_URL; ?>/students/add_note.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-primary">Add Note</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="notes-section">
            <h3>Your Notes</h3>
            
            <?php if (empty($notes)): ?>
                <p>No notes found. Add a note to get started.</p>
            <?php else: ?>
                <div class="notes-list">
                    <?php foreach ($notes as $note): ?>
                        <div class="note-card">
                            <div class="note-header">
                                <h4 class="module-badge"><?php echo $note['module_name']; ?></h4>
                                <span class="note-date"><?php echo date('M d, Y', strtotime($note['created_at'])); ?></span>
                            </div>
                            <div class="note-content">
                                <?php echo nl2br($note['commentaire']); ?>
                            </div>
                            <div class="note-actions">
                                <a href="<?php echo SITE_URL; ?>/students/edit_note.php?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/students/delete_note.php?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Module Modal -->
<div id="moduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Add New Module</h3>
        <form id="moduleForm" method="POST" action="<?php echo SITE_URL; ?>/students/add_module.php">
            <div class="form-group">
                <label for="module_name">Module Name</label>
                <input type="text" id="module_name" name="module_name" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Module</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Show module modal when add module link is clicked
    document.addEventListener('DOMContentLoaded', function() {
        const addModuleLink = document.querySelector('.add-module-link');
        const moduleModal = document.getElementById('moduleModal');
        const closeBtn = moduleModal.querySelector('.close');
        
        if (addModuleLink) {
            addModuleLink.addEventListener('click', function(e) {
                e.preventDefault();
                moduleModal.style.display = 'block';
            });
        }
        
        closeBtn.addEventListener('click', function() {
            moduleModal.style.display = 'none';
        });
        
        window.addEventListener('click', function(e) {
            if (e.target === moduleModal) {
                moduleModal.style.display = 'none';
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
