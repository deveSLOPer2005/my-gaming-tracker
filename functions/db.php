<?php
$serverName = "host.docker.internal"; 
$database = "my_gaming";
$username = "gamer123";
$password = "mygamingtracker123";

try {
    $dsn = "sqlsrv:server=$serverName;Database=$database;Encrypt=0;TrustServerCertificate=1";
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("database connection error. try later");
    }
