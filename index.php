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
$my_games = $sql_result->fetchAll(PDO::FETCH_ASSOC);
print_r($my_games);