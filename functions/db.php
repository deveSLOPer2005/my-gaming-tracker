<?php

function load_env($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Загружаем переменные
load_env(__DIR__ . '/.env');

$serverName = $_ENV['DB_HOST'] ?? 'host.docker.internal'; 
$database   = $_ENV['DB_DATABASE'] ?? 'my_gaming';
$username   = $_ENV['DB_USERNAME'] ?? '';
$password   = $_ENV['DB_PASSWORD'] ?? '';

try {
    $dsn = "sqlsrv:server=$serverName;Database=$database;Encrypt=0;TrustServerCertificate=1";
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("database connection error. try later");
}
