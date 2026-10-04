<?php require_once 'db.php';

$filter = 'all';
if ($selected_status = isset($_GET['status_choice'])) {
    $filter = $_GET['status_choice'];
}

$first_sql_query = "SELECT 
                        g.id AS game_id,
                        g.title AS game_title,
                        s.game_status,
                        gn.genre
                    FROM games g
                    INNER JOIN game_statuses s ON g.status_id = s.id
                    INNER JOIN genres gn ON g.genre_id = gn.id";

if ($filter != 'all') {
    $first_sql_query .= " WHERE s.game_status = '$filter';";
}
    else $first_sql_query .= ";";

$sql_result = $connection->query($first_sql_query);
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