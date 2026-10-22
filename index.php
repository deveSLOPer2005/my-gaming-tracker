<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: login.php");
    exit;
}

require_once 'functions/db.php';
require_once 'functions/db_users.php';
require_once 'functions/db_games.php';
require_once 'functions/functions.php';

if (isset($_POST['action']) && $_POST['action'] === 'delete account') {
    delete_account($connection, $_SESSION['user_id']);
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: login.php");
    exit;
}

$filter = 'all';
if (isset($_GET['status_choice']) && $_GET['status_choice'] !== '') {
    $filter = $_GET['status_choice'];
}

if (isset($_POST['action']) && $_POST['action'] === 'create') {
    create_game($connection, $_SESSION['user_id'], $_POST['game_name'], $_POST['genre_name'], $_POST['status_select']);
    header("Location: index.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update') {
    update_game($connection, $_SESSION['user_id'], $_POST['game_id'], $_POST['game_name'], $_POST['genre_name'], $_POST['status_select']);
    header("Location: index.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    delete_game($connection, $_POST['delete_id'], $_SESSION['user_id']);
    header("Location: index.php");
    exit;
}

$games_to_edit = null;
if (isset($_GET['edit_id'])) {
    $games_to_edit = get_game_to_edit($connection, $_GET['edit_id'], $_SESSION['user_id']);
}

$page = 1;
if (isset($_GET['page']) && (int)$_GET['page'] > 0 ) {
    $page = (int)$_GET['page'];
}

$limit = 3;
$offset = ($page - 1) * $limit;
$offset = (int)$offset;
$limit = (int)$limit;

$count_pages = get_games_pages_count($connection, $_SESSION['user_id'], $filter, $limit);
$my_games = get_games_list($connection, $_SESSION['user_id'], $filter, $offset, $limit);

require_once 'view/index_view.php';