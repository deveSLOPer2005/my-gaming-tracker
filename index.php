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

require_once 'db.php';
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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="UTF-8">
            <title>my game tracker</title>
            <link rel="stylesheet" href="water.css">

    </head>
    <body>
        <h1>my game tracker</h1> 
        <table border="1">
            <caption>games</caption>
            <tr>
                <th>title</th>
                <th>genre</th>
                <th>status</th>
                <th colspan="2">actions</th>
            </tr>
            <?php foreach ($my_games as $game): ?>
                    <tr>
                    <td><?= htmlspecialchars($game['game_title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($game['genre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($game['game_status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                    <form action="index.php" method="post" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="delete_id" value="<?= (int)$game['game_id'] ?>">
                    <button type="submit">delete</button>
                    </form>
                    </td>
                    <td><a href="index.php?edit_id=<?= (int)$game['game_id'] ?>">edit</a></td>
                    </tr>
            <?php endforeach; ?>
        </table>

        <?php
        if ($count_pages > 1) {
            for ($i = 1; $i <= $count_pages; $i++) {
                echo "<a href='index.php?page=$i&status_choice=$filter'> $i </a> ";
            }
        }
        ?>

        <form action="index.php" method="get" style="margin-top: 20px;">
        <label for='status'>choose status</label>
        <select id='status' name='status_choice'>
            <option value="all" <?= ($filter === 'all') ? 'selected' : '' ?>>all</option>
            <option value='playing' <?= ($filter === 'playing') ? 'selected' : '' ?>>playing</option>
            <option value='in plans' <?= ($filter === 'in plans') ? 'selected' : '' ?>>in plans</option>
            <option value='abandoned' <?= ($filter === 'abandoned') ? 'selected' : '' ?>>abandoned</option>
            <option value='completed' <?= ($filter === 'completed') ? 'selected' : '' ?>>completed</option>
        </select>
        <button type="submit">submit</button>
        </form>

        <h2 style="margin-top: 20px;">add/edit game</h2>
        <form action="index.php" method="post" style="margin-top: 20px;">
                <p>fill the form below:</p>

                <input type="hidden" name="action" value="<?= isset($_GET['edit_id']) ? 'update' : 'create' ?>">
                <?php if (isset($_GET['edit_id'])): ?>
                <input type="hidden" name="game_id" value="<?= (int)$_GET['edit_id'] ?>">
                <?php endif; ?>

                <label for='game_name_id'>game name: </label>
                <input type="text" id="game_name_id" name="game_name" value="<?= isset($games_to_edit) ? htmlspecialchars($games_to_edit['title'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
                
                <label for='genre_name_id'>genre: </label>
                <input type="text" id="genre_name_id" name="genre_name" value="<?= isset($games_to_edit) ? htmlspecialchars($games_to_edit['genre_name'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
                
                <label for='status_select_id'>status: </label>
                <select id='status_select_id' name='status_select'>
                        <option value="" disabled selected hidden>-</option>
                        <option value='1' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 1) ? 'selected' : '' ?>>playing</option>
                        <option value='2' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 2) ? 'selected' : '' ?>>in plans</option>
                        <option value='3' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 3) ? 'selected' : '' ?>>abandoned</option>
                        <option value='4' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 4) ? 'selected' : '' ?>>completed</option>
                </select>
                <button type="submit">submit</button>
        </form>
        <form action="index.php" method="post">
        <input type="hidden" name="action" value="logout">
        <button type="submit">logout</button>
        </form>
        <form action="index.php" method="post">
        <input type="hidden" name="action" value="delete account">
        <button type="submit">delete account</button>
        </form>
    </body>
</html>
