<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $password_query = "SELECT id, password_hash FROM users WHERE username = :username;";
        $stmt = $connection->prepare($password_query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];

            header("Location: index.php");
            exit;
        } else {

            echo "<p>wrong login or password</p>";
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $register_query = "INSERT INTO users(username, password_hash) VALUES (:username, :password_hash);";
        $stmt = $connection->prepare($register_query);
        $stmt->execute([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        $_SESSION['flash_message'] = "registration complete! now login";
        header("Location: login.php");
        exit;
        }
        else {
            echo "<p>error! enter correct username/login to sign up</p>";
            }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>authorization</title>
    <link rel="stylesheet" href="water.css">
</head>
<body>

    <h1>my game tracker</h1>

    <?php if (isset($_SESSION['flash_message'])):
    echo htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8');
    unset($_SESSION['flash_message']);
    endif; ?>
    <h2>sign in</h2>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="login">
        
        <label for="log_username">login:</label>
        <input type="text" id="log_username" name="username" required>
        
        <label for="log_password">password:</label>
        <input type="password" id="log_password" name="password" required>
        
        <button type="submit">submit</button>
    </form>

    <h1>or</h1>

    <h2>sign up</h2>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="register">
        
        <label for="reg_username">login:</label>
        <input type="text" id="reg_username" name="username" required>
        
        <label for="reg_password">password:</label>
        <input type="password" id="reg_password" name="password" required>
        
        <button type="submit">submit</button>
    </form>
</body>
</html>