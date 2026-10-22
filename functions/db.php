<?php
$serverName = "localhost"; 
$database = "my_gaming";

try {
    $connection = new PDO("sqlsrv:server=$serverName;Database=$database", null, null);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("database connection error. try later");
    }
