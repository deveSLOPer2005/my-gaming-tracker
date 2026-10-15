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