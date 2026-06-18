<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: view_portfolio.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM portfolio_items WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) { header("Location: view_portfolio.php"); exit(); }

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $tools_used  = trim($_POST['tools_used']);
    $date_created = $_POST['date_created'];

    $update = $conn->prepare("UPDATE portfolio_items SET title=?, category=?, description=?, tools_used=?, date_created=? WHERE id=? AND user_id=?");
    $update->execute([$title, $category, $description, $tools_used, $date_created, $id, $_SESSION['user_id']]);
    header("Location: view_portfolio.php");
    exit();
}

include '../includes/header.php';
?>

<h2 class="page-title">Edit Portfolio Item</h2>

<form method="POST" action="edit_portfolio.php?id=<?php echo $item['id']; ?>" class="form-card">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>

    <label>Category</label>
    <select name="category" required>
        <?php foreach (["Animation","Graphic Design","Video Editing","3D Modeling","Photography","Web/Multimedia"] as $cat): ?>
            <option value="<?php echo $cat; ?>" <?php echo $item['category'] === $cat ? 'selected' : ''; ?>><?php echo $cat; ?></option>
        <?php endforeach; ?>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4"><?php echo htmlspecialchars($item['description']); ?></textarea>

    <label>Tools Used</label>
    <input type="text" name="tools_used" value="<?php echo htmlspecialchars($item['tools_used']); ?>">

    <label>Date Created</label>
    <input type="date" name="date_created" value="<?php echo htmlspecialchars($item['date_created']); ?>">

    <button type="submit">Update Item</button>
</form>

<?php include '../includes/footer.php'; ?>
