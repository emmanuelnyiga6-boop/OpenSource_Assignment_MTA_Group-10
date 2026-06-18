<?php
session_start();
require_once '../config/db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        header("Location: ../portfolio/view_portfolio.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
include '../includes/header.php';
?>

<div class="auth-page">
<h2>Welcome Back</h2>
<p class="auth-subtitle">Log in to manage your creative portfolio</p>

<?php if ($error) echo "<p class='alert error'>$error</p>"; ?>

<form method="POST" action="login.php" class="form-card">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>
</div>

<?php include '../includes/footer.php'; ?>
