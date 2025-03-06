<?php
session_start();
require 'dbh.inc.php'; 

if ($_SESSION['rank'] != 1 && isset($_POST['report_id'])) {
    $reportId = intval($_POST['report_id']);
    $changedBy = $_SESSION['user_id']; 

    try {
        
        $newStatus = 'Beszerzés alatt';

        
        $updateQuery = "UPDATE reports SET report_status = :status WHERE id = :report_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([
            ':status' => $newStatus,
            ':report_id' => $reportId,
        ]);

        
        $changedByQuery = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
        $changedByQuery->execute([':user_id' => $changedBy]);
        $changedByName = $changedByQuery->fetchColumn();

        
        $logStmt = $pdo->prepare("INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)");
        $logEntry = "$changedByName a státuszt Beszerzés alatt-ra változtatta";
        $logStmt->execute([
            ':report_id' => $reportId,
            ':muvelet' => $logEntry,
            ':kezelo' => $changedBy,
        ]);

        
        header("Location: details.php?id=" . $reportId);
        exit;
    } catch (PDOException $e) {
        
        error_log("Hiba a státusz módosításakor: " . $e->getMessage());
        http_response_code(500);
        echo "Hiba történt, próbálja újra később.";
    }
} else {
    
    http_response_code(403);
    echo "Jogosulatlan hozzáférés!";
}
?>
