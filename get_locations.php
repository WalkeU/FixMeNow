<?php

require_once 'dbh.inc.php';

try {
    
    $stmt = $pdo->prepare("SELECT name FROM helyszinek");
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    
    echo json_encode($locations);
} catch (PDOException $e) {
    echo json_encode(["error" => "Nem sikerült lekérni a helyszíneket: " . $e->getMessage()]);
}
