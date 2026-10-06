<?php require_once 'db.php';

$filter = 'all';
if (isset($_GET['status_choice']) && $_GET['status_choice'] !== '') {
    $filter = $_GET['status_choice'];
}

if (isset($_POST['game_name']) && isset($_POST['genre_select']) && isset($_POST['status_select'])) {
    $new_game = $_POST['game_name'];
    $new_genre = $_POST['genre_select'];
    $new_status = $_POST['status_select'];
    $add_sql_query = "INSERT INTO games(title, genre_id, status_id) values ('$new_game', $new_genre, $new_status);";
    $add = $connection->query($add_sql_query);
}
else {
    echo "ошибка";
}

if (isset($_GET['delete_id']) && $_GET['delete_id'] !== '') {
    $delete_id = $_GET['delete_id'];
    $delete_sql_query = "DELETE FROM games WHERE id = $delete_id;";
    $delete = $connection->query($delete_sql_query);
}

$connection_sql_query = "SELECT 
                        g.id AS game_id,
                        g.title AS game_title,
                        s.game_status,
                        gn.genre
                    FROM games g
                    INNER JOIN game_statuses s ON g.status_id = s.id
                    INNER JOIN genres gn ON g.genre_id = gn.id";

if ($filter != 'all') {
    $connection_sql_query .= " WHERE s.game_status = '$filter';";
}
    else $connection_sql_query .= ";";

$sql_result = $connection->query($connection_sql_query);
$my_games = $sql_result->fetchAll(PDO::FETCH_ASSOC);

?>

<table border="1">
    <caption>games</caption>
    <tr>
        <th>title</th>
        <th>genre</th>
        <th>status</th>
    </tr>
    <?php foreach ($my_games as $game): ?>
            <tr>
            <td><?= $game['game_title'] ?></td>
            <td><?= $game['genre'] ?></td>
            <td><?= $game['game_status'] ?></td>
            <td><a href="index.php?delete_id=<?= $game['game_id'] ?>">delete</a></td>
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
        <label for='game_form'>fill the form. </label>
        <label for='game_input'>game name: </label>
        <input type="text" id="game_name_id" name="game_name">
        <label for='genre_input'>genre: </label>
        <select id='genre_select_id' name='genre_select'>
                <option value="" disabled selected>-</option>
                <option value='1'>MMORPG</option>
                <option value='2'>co-op survival</option>
                <option value='3'>open-world RPG</option>
                <option value='4'>sandbox</option>
                <option value='5'>gacha</option>
        </select>
        <label for='status_input'>status: </label>
        <select id='status_select_id' name='status_select'>
                <option value="" disabled selected>-</option>
                <option value='1'>playing</option>
                <option value='2'>in plans</option>
                <option value='3'>abandoned</option>
                <option value='4'>completed</option>
        </select>
        <button type="submit">add game</button>
</form>