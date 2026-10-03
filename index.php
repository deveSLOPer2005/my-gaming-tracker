<?php
$serverName = "localhost"; 
$database = "my_gaming";

try {
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", null, null);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h2>база данных подключена</h2>";
    
} catch (PDOException $e) {
    echo "<h2>база данных НЕ подключена:</h2>";
    echo "<p style='color:red;'>" . $e->getMessage() . "</p>";
}
