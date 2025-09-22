<?php
session_start();
require_once '../utils/database.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = query($query);
    if (count($result) > 0) {
        $_SESSION['user'] = $result[0];
        $_POST['success'] = true;
    } else {
        $_POST['failed'] = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/global.css">
    <link rel="stylesheet" href="../styles/login.css">
    <title>Login</title>
</head>
<body>
    <form class="login-form mt-5" action="" method="post">
        <h1>Login form</h1>
        <input type="text" name="username" placeholder="Username" required autocomplete="off">
        <input type="password" name="password" placeholder="Password" required autocomplete="off">
        <button type="submit" name="submit">Submit</button>
        <a class="register-link" href="../index.php">Don't have an account? Register here</a>

        <?php if (isset($_POST['success'])): ?>
            <?php header('Location: ../pages/home.php'); ?>
        <?php endif; ?>

        <?php if (isset($_POST['failed'])): ?>
            <p>Login failed!</p>
        <?php endif; ?>
    </form>
</body>
</html>