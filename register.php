<?php
require_once 'utils.php';
require_once 'database.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];

    $isExist = !empty(query("SELECT * FROM user WHERE username = '$username' OR email = '$email'"));
    if (!$isExist) {
        query("INSERT INTO user (username, password, firstName, lastName, email) VALUES ('$username', '$password', '$firstName', '$lastName', '$email')");
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Username or Email already exists');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Register</title>
</head>

<body class="flex flex-col justify-center items-center p-5 w-full h-screen">
    <h1 class="title text-center text-4xl mb-5 mt-5 font-bold">Register</h1>

    <form class="flex flex-col justify-center items-center gap-2 lg:w-1/4 md:w-1/2 w-full" action="" method="post">
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
                'type' => 'text',
                'name' => 'firstName',
                'placeholder' => 'First Name',
                'require' => true
            ]
        ); ?>
        <?= component(
            'input',
            [
                'type' => 'text',
                'name' => 'lastName',
                'placeholder' => 'Last Name',
                'require' => true
            ]
        ); ?>
        <?= component(
            'input',
            [
                'type' => 'email',
                'name' => 'email',
                'placeholder' => 'Email',
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
        <button name="register" class="mt-2 cursor-pointer p-2 px-5 bg-blue-500 text-white rounded-md w-full active:bg-blue-800 hover:bg-blue-600" type="submit">Register</button>
        <p>Already have an account? <a href="index.php" class="text-blue-500">Login</a></p>
    </form>

</body>

</html>