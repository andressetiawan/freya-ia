<?php
require_once 'utils.php';
require_once 'database.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = query("SELECT * FROM user WHERE username = '$username'")[0];

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: homepage.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Slide Website</title>
</head>

<body class="flex flex-col justify-center items-center p-5 w-full h-screen">
    <h1 class="title text-center text-4xl mb-5 mt-5 font-bold">Login</h1>
    <form class="form-login flex flex-col justify-center items-center gap-2 lg:w-1/4 md:w-1/2 w-full" action="" method="post">
        <?= component(
            'input',
            [
                'type' => 'text',
                'name' => 'username',
                'placeholder' => 'Username',
                'require' => true
            ]
        ); ?>
        <?= component(
            'input',
            [
                'type' => 'password',
                'name' => 'password',
                'placeholder' => 'Password',
                'require' => true
            ]
        ); ?>
        <button name="login" class="mt-2 cursor-pointer p-2 px-5 bg-blue-500 text-white rounded-md w-full active:bg-blue-800 hover:bg-blue-600" type="submit">Login</button>
        <p>Don't have an account? <a href="register.php" class="text-blue-500">Register</a></p>
    </form>
</body>

</html>