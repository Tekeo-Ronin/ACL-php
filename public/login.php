<?php
require '../src/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (validateInput($username) && validateInput($password)) {
        if (login($username, $password)) {
            echo "Login successful!";
        } else {
            echo "Invalid login details.";
        }
    } else {
        echo "Incorrect data format.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <form method="post" action="login.php">
        <label for="username">Login or Email:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">LogIn</button>
    </form>
</body>
</html>
