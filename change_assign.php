<?php
session_start();
require 'dbh.inc.php';

if ($_SESSION['rank'] == 3 && isset($_POST['report_id'], $_POST['assigned_to'])) {
    $reportId = intval($_POST['report_id']);
    $assignedTo = intval($_POST['assigned_to']);
    $changedBy = $_SESSION['user_id'];

    try {
        // Frissítjük az adatbázisban a report felelősét
        $stmt = $pdo->prepare("UPDATE reports SET assigned_to = :assigned_to WHERE id = :report_id");
        $stmt->execute([
            ':assigned_to' => $assignedTo,
            ':report_id' => $reportId,
        ]);

        // Felhasználónevek lekérdezése
        $changedByQuery = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
        $changedByQuery->execute([':user_id' => $changedBy]);
        $changedByName = $changedByQuery->fetchColumn();

        $assignedToQuery = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
        $assignedToQuery->execute([':user_id' => $assignedTo]);
        $assignedToName = $assignedToQuery->fetchColumn();

        // Napló bejegyzések hozzáadása
        $logQuery = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";

        // Első üzenet: Feladat kiosztásának változtatása
        $logStmt = $pdo->prepare($logQuery);
        $muveletChanged = "Feladat kiosztását változtatta: $changedByName";
        $logStmt->execute([
            ':report_id' => $reportId,
            ':muvelet' => $muveletChanged,
            ':kezelo' => $changedBy,
        ]);

        // Második üzenet: Feladatot megkapta
        $muveletAssigned = "Feladatot megkapta: $assignedToName";
        $logStmt->execute([
            ':report_id' => $reportId,
            ':muvelet' => $muveletAssigned,
            ':kezelo' => $assignedTo,
        ]);

        // Visszairányítás a details.php oldalra
        header("Location: details.php?id=" . $reportId);
        exit;
    } catch (PDOException $e) {
        // Hibaüzenet kiírása és naplózása
        error_log("Hiba a feladat módosításakor: " . $e->getMessage());
        http_response_code(500);
        echo "Hiba történt, próbálja újra később.";
    }
} else {
    // Jogosulatlan hozzáférés kezelése
    http_response_code(403);
    echo "Jogosulatlan hozzáférés!";
}
?>
