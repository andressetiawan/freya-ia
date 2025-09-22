<?php
require_once 'utils/database.php';
if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "INSERT INTO users (first_name, last_name, username, password) VALUES ('$firstName', '$lastName', '$username', '$password')";
    $result = query($query);
    if ($result) {
        $_POST['success'] = true;
    } else {
        $_POST['failed'] = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'parts/head.php'; ?>
<body>
    <?php if (isset($_POST['success'])): ?>
        <div class="success-container">
            <img src="./images/success.gif" alt="success">
            <p>Registration successful! Redirecting to login page in 2 seconds...</p>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = './pages/login.php';
            }, 2000);
        </script>
    <?php else: ?>
        <form class="register-form mt-5" action="./index.php" method="post">
            <h1>Registration form</h1>    
            <input type="text" name="firstName" placeholder="First name" required autocomplete="off">
            <input type="text" name="lastName" placeholder="Last name" required autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required autocomplete="off">
            <button type="submit" name="submit">Submit</button>
            <a class="login-link" href="./pages/login.php">Already have an account? Login here</a>
        </form>
    <?php endif; ?>
</body>
</html>