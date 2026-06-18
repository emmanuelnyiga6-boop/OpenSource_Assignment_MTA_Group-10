<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM portfolio_items WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}
header("Location: view_portfolio.php");
exit();
?>
