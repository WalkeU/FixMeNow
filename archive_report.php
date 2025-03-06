<?php
session_start();
require_once "dbh.inc.php"; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id']) && isset($_SESSION['user_id'])) {
    try {
        $report_id = $_POST['report_id'];

        
        $query = "UPDATE reports SET report_status = 'Archivált' WHERE id = :report_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ./details.php?id=" . $report_id);
        exit();
    } catch (PDOException $e) {
        die("Hiba történt az archiválás során: " . $e->getMessage());
    }
}
?>
