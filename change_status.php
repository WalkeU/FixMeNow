<?php
session_start();
require 'dbh.inc.php'; 

if ($_SESSION['rank'] == 3 && isset($_POST['report_id'], $_POST['status'])) {
    $reportId = intval($_POST['report_id']);
    $newStatus = $_POST['status'];
    $changedBy = $_SESSION['user_id']; 

    try {
        
        $updateQuery = "UPDATE reports SET report_status = :status";
        $updateParams = [':status' => $newStatus, ':report_id' => $reportId];

        
        if ($newStatus === 'Nyitott') {
            $updateQuery .= ", assigned_to = NULL";
        }

        $updateQuery .= " WHERE id = :report_id";

        
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute($updateParams);

        
        $changedByQuery = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
        $changedByQuery->execute([':user_id' => $changedBy]);
        $changedByName = $changedByQuery->fetchColumn();

        
        $logStmt = $pdo->prepare("INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)");
        $logEntry = "$changedByName a státuszt módosítása '$newStatus' értékre";
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
