<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$stmt = $conn->prepare("SELECT * FROM portfolio_items WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="hero">
    <h1>My Creative Portfolio</h1>
    <p>Showcase your artwork and projects, track your creative journey, and share your best work.</p>
    <a href="add_portfolio.php" class="btn-yellow">+ Add New Item</a>
</div>

<?php if (count($items) === 0): ?>
    <p style="color: var(--gray); font-size: 1.05rem;">No portfolio items yet. Click "+ Add New Item" above to get started.</p>
<?php else: ?>
<div class="portfolio-grid">
    <?php foreach ($items as $item): ?>
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
                <p><strong>Tools:</strong> <?php echo htmlspecialchars($item['tools_used']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($item['date_created']); ?></p>
                <div class="card-actions">
                    <a href="edit_portfolio.php?id=<?php echo $item['id']; ?>">Edit</a>
                    <a href="delete_portfolio.php?id=<?php echo $item['id']; ?>" onclick="return confirm('Delete this item?');">Delete</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
