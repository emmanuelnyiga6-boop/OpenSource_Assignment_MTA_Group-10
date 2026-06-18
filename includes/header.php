<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Portfolio Management System</title>
    <link rel="stylesheet" href="/portfolio_system/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">Digital <span>Portfolio</span></div>
    <ul>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="/portfolio_system/portfolio/view_portfolio.php">My Portfolio</a></li>
            <li><a href="/portfolio_system/portfolio/add_portfolio.php">Add New Item</a></li>
            <li><a href="/portfolio_system/portfolio/search_portfolio.php">Search</a></li>
            <li><a href="/portfolio_system/auth/logout.php" class="nav-pill">Logout (<?php echo htmlspecialchars($_SESSION['full_name']); ?>)</a></li>
        <?php else: ?>
            <li><a href="/portfolio_system/auth/login.php">Login</a></li>
            <li><a href="/portfolio_system/auth/register.php" class="nav-pill">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="container">
