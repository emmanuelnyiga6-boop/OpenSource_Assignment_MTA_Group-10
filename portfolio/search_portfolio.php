<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$results = [];
$searched = false;

if (isset($_GET['query']) && trim($_GET['query']) !== "") {
    $searched = true;
    $query = trim($_GET['query']);
    $stmt = $conn->prepare("SELECT * FROM portfolio_items WHERE user_id = ? AND (title LIKE ? OR category LIKE ?) ORDER BY created_at DESC");
    $like = "%$query%";
    $stmt->execute([$_SESSION['user_id'], $like, $like]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

include '../includes/header.php';
?>

<h2 class="page-title">Search Portfolio Items</h2>

<form method="GET" action="search_portfolio.php" class="form-card search-form">
    <input type="text" name="query" placeholder="Search by title or category..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($searched): ?>
    <h3 style="color: var(--navy); margin: 24px 0 14px;"><?php echo count($results); ?> result(s) found</h3>
    <div class="portfolio-grid">
        <?php foreach ($results as $item): ?>
            <div class="portfolio-card">
                <?php if ($item['image_path']):
                    $ext = strtolower(pathinfo($item['image_path'], PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                ?>
                    <?php if ($isVideo): ?>
                        <video controls preload="metadata">
                            <source src="/portfolio_system/<?php echo htmlspecialchars($item['image_path']); ?>" type="video/<?php echo $ext === 'mov' ? 'mp4' : $ext; ?>">
                        </video>
                    <?php else: ?>
                        <img src="/portfolio_system/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <?php endif; ?>
                <?php endif; ?>
                <div class="card-body">
                    <span class="tag"><?php echo htmlspecialchars($item['category']); ?></span>
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($item['description']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
