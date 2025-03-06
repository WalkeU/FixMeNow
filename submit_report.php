<?php
session_start();
require_once "dbh.inc.php";

if (!isset($_POST['report_id'])) {
    die("Nincs jelentés ID megadva.");
}

$report_id = $_POST['report_id'];

try {
    
    if ($_SESSION['rank'] == 3) {
        $new_status = 'Kész';
        $muvelet = "Feladatot elvégezte: " . $_SESSION['username'];
    } elseif ($_SESSION['rank'] == 2) {
        $new_status = 'Elfogadásra vár';
        $muvelet = "Feladatot elküldte elfogadtatásra: " . $_SESSION['username'];
    } else {
        die("Nincs jogosultság a státusz frissítésére.");
    }

    
    $query = "UPDATE reports SET report_status = :status WHERE id = :report_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':status', $new_status);
    $stmt->bindParam(':report_id', $report_id);
    $stmt->execute();

    
    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
    $log_stmt = $pdo->prepare($log_query);
    $log_stmt->bindParam(':report_id', $report_id);
    $log_stmt->bindParam(':muvelet', $muvelet);
    $log_stmt->bindParam(':kezelo', $_SESSION['user_id']);
    $log_stmt->execute();

    
    header("Location: ./details.php?id=" . $report_id);
    exit();
} catch (PDOException $e) {
    die("Hiba történt a státusz frissítése során: " . $e->getMessage());
}