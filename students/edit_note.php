<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/session.php';

// Ensure user is student
require_student();

$student_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}

$note_id = (int)$_GET['id'];

// Get note data and verify ownership
$sql = "SELECT * FROM Notes WHERE id = $note_id AND student_id = $student_id";
$result = query($sql);

if (num_rows($result) !== 1) {
    header("Location: " . SITE_URL . "/students/dashboard.php");
    exit();
}

$note = fetch_assoc($result);
$module_id = $note['module_id'];
$commentaire = $note['commentaire'];

// Get modules for dropdown
$sql = "SELECT * FROM Modules WHERE student_id = $student_id ORDER BY name";
$result = query($sql);
$modules = fetch_all($result);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module_id = (int)$_POST['module_id'];
    $commentaire = sanitize($_POST['commentaire']);
    
    if (empty($commentaire) || $module_id === 0) {
        $error = "All fields are required";
    } else {
        // Verify module belongs to student
        $sql = "SELECT id FROM Modules WHERE id = $module_id AND student_id = $student_id";
        $result = query($sql);
        
        if (num_rows($result) !== 1) {
            $error = "Invalid module selected";
        } else {
            // Update note
            $sql = "UPDATE Notes SET commentaire = '$commentaire', module_id = $module_id WHERE id = $note_id";
            query($sql);
            
            $success = "Note updated successfully";
        }
    }
}

include '../includes/header.php';
?>

<div class="form-container">
    <h2>Edit Note</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="module_id">Select Module</label>
            <select id="module_id" name="module_id" required>
                <option value="">-- Select Module --</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?php echo $module['id']; ?>" <?php echo $module_id == $module['id'] ? 'selected' : ''; ?>>
                        <?php echo $module['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="commentaire">Note Content</label>
            <textarea id="commentaire" name="commentaire" rows="10" required><?php echo $commentaire; ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Note</button>
            <a href="<?php echo SITE_URL; ?>/students/dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
