<?php

function delete_account($connection, $user_id) {
    $delete_games_query = "DELETE FROM games WHERE user_id = :user_id;";
    $stmt_games = $connection->prepare($delete_games_query);
    $stmt_games->execute(['user_id' => (int)$user_id]);
    $delete_account_query = 'DELETE FROM users WHERE id = :id;';
    $stmt = $connection->prepare($delete_account_query);
    $stmt->execute(['id' => (int)$user_id]);
}
