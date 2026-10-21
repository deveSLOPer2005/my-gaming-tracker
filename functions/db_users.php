<?php

function delete_account($connection, $user_id) {
    $delete_games_query = "DELETE FROM games WHERE user_id = :user_id;";
    $stmt_games = $connection->prepare($delete_games_query);
    $stmt_games->execute(['user_id' => (int)$user_id]);
    $delete_account_query = 'DELETE FROM users WHERE id = :id;';
    $stmt = $connection->prepare($delete_account_query);
    $stmt->execute(['id' => (int)$user_id]);
}

function user_login($connection, $username, $password) {
    $username = trim($username ?? '');
    $password = trim($password ?? '');

    if ($username !== '' && $password !== '') {
        $password_query = "SELECT id, password_hash FROM users WHERE username = :username;";
        $stmt = $connection->prepare($password_query);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
    }
    return false;
}

function user_register($connection, $username, $password) {
    $username = trim($username ?? '');
    $password = trim($password?? '');

    if ($username !== '' && $password !== '') {
    $register_query = "INSERT INTO users(username, password_hash) VALUES (:username, :password_hash);";
    $stmt = $connection->prepare($register_query);
    $stmt->execute([
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ]);
        return true;
    }
    return false;
}