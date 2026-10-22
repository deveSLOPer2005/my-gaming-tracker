<?php
session_start();
require_once 'functions/db.php';

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

require_once 'view/login_view.php';