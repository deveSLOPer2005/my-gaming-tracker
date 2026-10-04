<?php require_once 'db.php';

$first_sql_query = 'SELECT 
                        g.id AS game_id,
                        g.title AS game_title,
                        s.game_status,
                        gn.genre
                    FROM games g
                    INNER JOIN game_statuses s ON g.status_id = s.id
                    INNER JOIN genres gn ON g.genre_id = gn.id;';

$sql_result = $connection->query($first_sql_query);
$my_games = $sql_result->fetchAll(PDO::FETCH_ASSOC); ?>

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