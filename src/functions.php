<?php
function validateInput($data) {
    return preg_match('/^[a-zA-Z0-9]+$/', $data);
}

function sendToken($email) {
    $token = bin2hex(random_bytes(16));
    // Saving the token

    // Sending token to email
    mail($email, "Your verification token", "Your token: $token");
}

function login($username, $password) {
    $config = require '../config/config.php';
    $pdo = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['user'], $config['db']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username AND password = :password');
    $stmt->execute([
        ':username' => $username,
        ':password' => $password
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        return true;
    } else {
        incrementLoginAttempts($username);
        return false;
    }
}

function getLoginAttempts($username) {
    $config = require '../config/config.php';
    $pdo = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['user'], $config['db']['password']);
    $stmt = $pdo->prepare('SELECT attempts FROM login_attempts WHERE username = ?');
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['attempts'] : 0;
}

function incrementLoginAttempts($username) {
    $config = require '../config/config.php';
    $pdo = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['user'], $config['db']['password']);
    $stmt = $pdo->prepare('UPDATE login_attempts SET attempts = attempts + 1 WHERE username = ?');
    $stmt->execute([$username]);
}

function resetLoginAttempts($username) {
    $config = require '../config/config.php';
    $pdo = new PDO('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'], $config['db']['user'], $config['db']['password']);
    $stmt = $pdo->prepare('UPDATE login_attempts SET attempts = 0 WHERE username = ?');
    $stmt->execute([$username]);
}
