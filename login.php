<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'functions/db_users.php';

if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $user = user_login($connection, $_POST['username'], $_POST['password']);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {

            echo "<p>wrong login or password</p>";
        }
}

if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $success_register = user_register($connection, $_POST['username'], $_POST['password']);
        
        if ($success_register) {
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