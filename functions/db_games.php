<?php
function get_or_create_genre($connection, $genre_name) {
    $genre_input = trim($genre_name);
    if ($genre_input === '') {
        return null; 
    }
    $genre_test_query = "SELECT id FROM genres WHERE genre = :genre;";
    $stmt = $connection->prepare($genre_test_query);
    $stmt->execute([
        'genre' => $genre_input,
    ]);
    $genres = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($genres) {
        return $genres['id'];
    }
    $new_genre_query = "INSERT INTO genres (genre) VALUES (:genre)";
    $stmt = $connection->prepare($new_genre_query);
    $stmt->execute([
        'genre' => $genre_input,
    ]);
    return $connection->lastInsertId();
}

function create_game($connection, $user_id, $game_name, $genre_name, $status_select) {
    $new_game = trim($game_name ?? '');
    $genre_name = trim($genre_name ?? '');
    $new_status = (int)($status_select ?? 0);

    if ($new_game !== '' && $genre_name !== '' && $new_status !== 0) {
        $genre_id = get_or_create_genre($connection, $genre_name);
        $add_sql_query = "INSERT INTO games (title, genre_id, status_id, user_id) VALUES (:title, :genre_id, :status_id, :user_id);";
        $stmt = $connection->prepare($add_sql_query);
        $stmt->execute([
            'title' => $new_game,
            'genre_id' => $genre_id,
            'status_id' => $new_status,
            'user_id' => (int)$user_id
        ]);
    }
}

function update_game($connection, $user_id, $edit_id, $game_name, $genre_name, $status_select) {
    $edited_game = trim($game_name ?? '');
    $genre_name = trim($genre_name ?? '');
    $edited_status = (int)($status_select ?? 0);

    if ($edited_game !== '' && $genre_name !== '' && $edited_status !== 0) {
        $genre_id = get_or_create_genre($connection, $genre_name);
        $edit_id = (int)$edit_id;
        $edit_sql_query = "UPDATE games SET title = :title, genre_id = :genre_id, status_id = :status_id WHERE id = :id AND user_id = :user_id;";
        $stmt = $connection->prepare($edit_sql_query);
        $stmt->execute([
            'title' => $edited_game,
            'genre_id' => $genre_id,
            'status_id' => $edited_status,
            'id' => $edit_id,
            'user_id' => (int)$user_id
        ]);
    }
}

function delete_game($connection, $delete_id, $user_id) {
    $delete_id = (int)$delete_id;
    $delete_sql_query = "DELETE FROM games WHERE id = :id AND user_id = :user_id;";
    $stmt = $connection->prepare($delete_sql_query);
    $stmt->execute([
        'id' => $delete_id,
        'user_id' => (int)$user_id
    ]);
}

function get_game_to_edit($connection, $edit_id, $user_id) {
    $edit_id = (int)$edit_id;
    $data_edit_query = "SELECT g.*, gn.genre AS genre_name
                        from games g
                        INNER JOIN genres gn ON g.genre_id = gn.id
                        WHERE g.id = :id AND user_id = :user_id;";
    $stmt = $connection->prepare($data_edit_query);
    $stmt->execute([
        'id' => $edit_id,
        'user_id' => (int)$user_id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
