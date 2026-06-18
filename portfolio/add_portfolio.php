<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $tools_used  = trim($_POST['tools_used']);
    $date_created = $_POST['date_created'];
    $image_path  = "";

    if ($title === "" || $category === "") {
        $error = "Title and category are required.";
    } else {
        // Handle image or video upload (optional)
        if (isset($_FILES['image']) && $_FILES['image']['name'] !== '') {
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'ogg', 'mov'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $new_name = uniqid('art_') . '.' . $ext;
                    $target = '../uploads/' . $new_name;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $image_path = 'uploads/' . $new_name;
                    } else {
                        $error = "File upload failed. Check folder permissions on uploads/.";
                    }
                } else {
                    $error = "Unsupported file type. Allowed: jpg, jpeg, png, gif, mp4, webm, ogg, mov.";
                }
            } elseif ($_FILES['image']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['image']['error'] === UPLOAD_ERR_FORM_SIZE) {
                $error = "The file is too large for the server's current upload limit. Increase upload_max_filesize and post_max_size in php.ini (see README), then restart Apache and try again.";
            } else {
                $error = "File upload failed (error code: " . $_FILES['image']['error'] . ").";
            }
        }

        if ($error === "") {
            $stmt = $conn->prepare("INSERT INTO portfolio_items (user_id, title, category, description, tools_used, image_path, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$_SESSION['user_id'], $title, $category, $description, $tools_used, $image_path, $date_created])) {
                $success = "Portfolio item added successfully.";
            } else {
                $error = "Failed to add item.";
            }
        }
    }
}
include '../includes/header.php';
?>

<div class="center-page">
<h2 class="page-title">Add New Portfolio Item</h2>

<?php if ($error) echo "<p class='alert error'>$error</p>"; ?>
<?php if ($success) echo "<p class='alert success'>$success</p>"; ?>

<form method="POST" action="add_portfolio.php" enctype="multipart/form-data" class="form-card">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Category</label>
    <select name="category" required>
        <option value="">-- Select --</option>
        <option value="Animation">Animation</option>
        <option value="Graphic Design">Graphic Design</option>
        <option value="Video Editing">Video Editing</option>
        <option value="3D Modeling">3D Modeling</option>
        <option value="Photography">Photography</option>
        <option value="Web/Multimedia">Web/Multimedia</option>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4"></textarea>

    <label>Tools Used</label>
    <input type="text" name="tools_used" placeholder="e.g. Blender, After Effects">

    <label>Date Created</label>
    <input type="date" name="date_created">

    <label>Upload Image or Video (optional)</label>
    <input type="file" name="image" accept="image/*,video/*">
    <small style="color: var(--gray); margin-top: 4px;">Accepted: jpg, png, gif, mp4, webm, ogg, mov</small>

    <button type="submit">Save Item</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>
