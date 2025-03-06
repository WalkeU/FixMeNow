<?php
session_start();
require_once "dbh.inc.php";

if (!isset($_POST['report_id'])) {
    die("Nincs jelentés ID megadva.");
}

$report_id = $_POST['report_id'];

// Jogosultság ellenőrizése
if ($_SESSION['rank'] != 3) {
    die("Nincs jogosultság a státusz frissítésére.");
}

// Státusz frissítése
try {
    $query = "UPDATE reports SET report_status = 'Kész' WHERE id = :report_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $stmt->execute();

    // Logolás hozzáadása
    $current_user_id = $_SESSION['user_id'];
    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
    $log_stmt = $pdo->prepare($log_query);
    $muvelet = "Feladatot elfogadta: " . $_SESSION['username'] ;
    $log_stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
    $log_stmt->bindParam(':muvelet', $muvelet, PDO::PARAM_STR);
    $log_stmt->bindParam(':kezelo', $current_user_id, PDO::PARAM_INT);
    $log_stmt->execute();

    // Sikeres frissítés után visszairányítás
    header("Location: ./details.php?id=" . $report_id);
    exit();
} catch (PDOException $e) {
    die("Hiba történt a státusz frissítése során: " . $e->getMessage());
}
?>
