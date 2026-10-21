<?php

function get_games_pages_count($connection, $user_id, $filter, $limit) {
    $page_count_query = "SELECT COUNT(*) FROM games g 
                        INNER JOIN game_statuses s ON g.status_id = s.id
                        WHERE g.user_id = :user_id";

    $params = ['user_id' => (int)$user_id];
    
    if ($filter !== 'all') {
        $page_count_query .= " AND s.game_status = :status";
        $params['status'] = $filter;
    }

    $stmt_count = $connection->prepare($page_count_query);
    $stmt_count->execute($params);
    $count_games = $stmt_count->fetchColumn();
    return ceil($count_games / $limit); 
}

function get_games_list($connection, $user_id, $filter, $offset, $limit) {
    $connection_sql_query = "SELECT 
                            g.id AS game_id,
                            g.title AS game_title,
                            s.game_status,
                            gn.genre
                            FROM games g
                            INNER JOIN game_statuses s ON g.status_id = s.id
                            INNER JOIN genres gn ON g.genre_id = gn.id
                            WHERE g.user_id = :user_id";

    $params = ['user_id' => (int)$user_id];
    
    if ($filter !== 'all') {
        $connection_sql_query .= " AND s.game_status = :status";
        $params['status'] = $filter;
    }

    $connection_sql_query .= " ORDER BY g.id OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY;";
    $stmt = $connection->prepare($connection_sql_query);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
