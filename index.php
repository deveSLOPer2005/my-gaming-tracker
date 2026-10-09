<?php require_once 'db.php';

$filter = 'all';
if (isset($_GET['status_choice']) && $_GET['status_choice'] !== '') {
    $filter = $_GET['status_choice'];
}

if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $new_game = $_POST['game_name'];
    $genre_input = trim($_POST['genre_name']);
    $genre_test_query = "SELECT id FROM genres WHERE genre = :genre;";
    $stmt = $connection->prepare($genre_test_query);
    $stmt->execute([
        'genre' => $genre_input,
    ]);
    $genres = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($genres) {
        $genre_id = $genres['id'];
    }
    else {
        $new_genre_query = "INSERT INTO genres (genre) VALUES (:genre)";
        $stmt = $connection->prepare($new_genre_query);
        $stmt->execute([
        'genre' => $genre_input,
    ]);
    $genre_id = $connection->lastInsertId();
    }
    $new_status = (int)$_POST['status_select'];
    $add_sql_query = "INSERT INTO games (title, genre_id, status_id) VALUES (:title, :genre_id, :status_id);";
    $stmt = $connection->prepare($add_sql_query);
    $stmt->execute([
        'title' => $new_game,
        'genre_id' => $genre_id,
        'status_id' => $new_status
    ]);
    header("Location: index.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $edited_game = $_POST['game_name'];
    $genre_input = trim($_POST['genre_name']);
    $genre_test_query = "SELECT id FROM genres WHERE genre = :genre;";
    $stmt = $connection->prepare($genre_test_query);
    $stmt->execute([
        'genre' => $genre_input,
    ]);
    $genres = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($genres) {
        $genre_id = $genres['id'];
    }
    else {
        $new_genre_query = "INSERT INTO genres (genre) VALUES (:genre)";
        $stmt = $connection->prepare($new_genre_query);
        $stmt->execute([
        'genre' => $genre_input,
    ]);
    $genre_id = $connection->lastInsertId();
    }
    $edited_status = (int)$_POST['status_select'];
    $edit_id = (int)$_POST['game_id'];
    $edit_sql_query = "UPDATE games SET title = :title, genre_id = :genre_id, status_id = :status_id WHERE id = :id;";
    $stmt = $connection->prepare($edit_sql_query);
    $stmt->execute([
        'title' => $edited_game,
        'genre_id' => $genre_id,
        'status_id' => $edited_status,
        'id' => $edit_id
    ]);
    header("Location: index.php");
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $delete_id = (int)$_POST['delete_id'];
    $delete_sql_query = "DELETE FROM games WHERE id = :id;";
    $stmt = $connection->prepare($delete_sql_query);
    $stmt->execute(['id' => $delete_id]);
    header("Location: index.php");
    exit;
}

if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $data_edit_query = "SELECT * FROM games WHERE id = :id;";
    $stmt = $connection->prepare($data_edit_query);
    $stmt->execute(['id' => $edit_id]);
    $games_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$connection_sql_query = "SELECT 
                        g.id AS game_id,
                        g.title AS game_title,
                        s.game_status,
                        gn.genre
                    FROM games g
                    INNER JOIN game_statuses s ON g.status_id = s.id
                    INNER JOIN genres gn ON g.genre_id = gn.id";

$params = [];
if ($filter !== 'all') {
    $connection_sql_query .= " WHERE s.game_status = :status;";
    $params['status'] = $filter;
}

$stmt = $connection->prepare($connection_sql_query);
$stmt->execute($params);
$my_games = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

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

<form action="index.php" method="get" style="margin-top: 20px;">
<label for='status'>choose status</label>
<select id='status' name='status_choice'>
    <option value="" disabled selected>-</option>
    <option value="all">all</option>
    <option value='playing'>playing</option>
    <option value='in plans'>in plans</option>
    <option value='abandoned'>abandoned</option>
    <option value='completed'>completed</option>
</select>
<button type="submit">submit</button>
</form>

<form action="index.php" method="post" style="margin-top: 20px;">
        <label for='game_name_id'>fill the form. </label>

        <input type="hidden" name="action" value="<?= isset($_GET['edit_id']) ? 'update' : 'create' ?>">
        <?php if (isset($_GET['edit_id'])): ?>
        <input type="hidden" name="game_id" value="<?= (int)$_GET['edit_id'] ?>">
        <?php endif; ?>

        <label for='game_name_id'>game name: </label>
        <input type="text" id="game_name_id" name="game_name" value="<?= isset($games_to_edit) ? htmlspecialchars($games_to_edit['title'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
        
        <label for='genre_select_id'>genre: </label>
        <input type="text" name="genre_name" required>
        
        <label for='status_select_id'>status: </label>
        <select id='status_select_id' name='status_select'>
                <option value="" disabled selected>-</option>
                <option value='1' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 1) ? 'selected' : '' ?>>playing</option>
                <option value='2' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 2) ? 'selected' : '' ?>>in plans</option>
                <option value='3' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 3) ? 'selected' : '' ?>>abandoned</option>
                <option value='4' <?= (isset($games_to_edit) && $games_to_edit['status_id'] == 4) ? 'selected' : '' ?>>completed</option>
        </select>
        <button type="submit">submit</button>
</form>
