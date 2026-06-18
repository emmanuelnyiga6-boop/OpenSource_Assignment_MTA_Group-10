<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: portfolio/view_portfolio.php");
} else {
    header("Location: auth/login.php");
}
exit();
?>
