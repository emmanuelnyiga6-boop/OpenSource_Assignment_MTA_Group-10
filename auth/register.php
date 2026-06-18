<?php
require_once '../config/db.php';
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    if ($full_name === "" || $username === "" || $email === "" || $password === "") {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            $error = "Username or email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, username, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$full_name, $username, $email, $hashed_password])) {
                $success = "Registration successful. You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
include '../includes/header.php';
?>

<div class="auth-page">
<h2>Create Your Account</h2>
<p class="auth-subtitle">Join and start building your digital portfolio</p>

<?php if ($error) echo "<p class='alert error'>$error</p>"; ?>
<?php if ($success) echo "<p class='alert success'>$success</p>"; ?>

<form method="POST" action="register.php" class="form-card">
    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Username</label>
    <input type="text" name="username" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include '../includes/footer.php'; ?>
