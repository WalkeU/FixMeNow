<?php
session_start();
require_once "dbh.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ellenőrizzük, hogy van-e 'report_id' az űrlapban
    if (isset($_POST['report_id']) && isset($_POST['assigned_to'])) {
        $report_id = $_POST['report_id'];
        $assigned_to = $_POST['assigned_to']; // Az új felhasználó ID
        $current_user_id = $_SESSION['user_id']; // Aki a reportot kezeli (sessionből lekérdezve)

        try {
            // SQL lekérdezés a report frissítésére, beleértve a státusz frissítését
            $query = "UPDATE reports SET assigned_to = :assigned_to, report_status = 'Elvállalt' WHERE id = :report_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
            $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Felhasználónevek lekérdezése a logoláshoz
                $current_user_name = $_SESSION['username'];
                $assigned_to_query = "SELECT username FROM users WHERE user_id = :assigned_to";
                $assigned_to_stmt = $pdo->prepare($assigned_to_query);
                $assigned_to_stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
                $assigned_to_stmt->execute();
                $assigned_to_user = $assigned_to_stmt->fetchColumn(); // Lekérdezzük az új felhasználó nevét

                if ($assigned_to == $current_user_id) {
                    // Csak 'assigned to' logolás, ha saját magának adja a feladatot
                    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
                    $log_stmt = $pdo->prepare($log_query);
                    $muvelet = "Feladatot elvállalta: " . $assigned_to_user;
                    $log_stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt->bindParam(':muvelet', $muvelet, PDO::PARAM_STR);
                    $log_stmt->bindParam(':kezelo', $assigned_to, PDO::PARAM_INT);
                    $log_stmt->execute();
                } else {
                    // Logolás 'assigned by' és 'assigned to' esetén
                    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
                    
                    // 'assigned by' logolás
                    $log_stmt_assigned_by = $pdo->prepare($log_query);
                    $muvelet_assigned_by = "Feladatott kiosztotta: " . $current_user_name;
                    $log_stmt_assigned_by->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt_assigned_by->bindParam(':muvelet', $muvelet_assigned_by, PDO::PARAM_STR);
                    $log_stmt_assigned_by->bindParam(':kezelo', $current_user_id, PDO::PARAM_INT);
                    $log_stmt_assigned_by->execute();

                    // 'assigned to' logolás
                    $log_stmt_assigned_to = $pdo->prepare($log_query);
                    $muvelet_assigned_to = "Feladatot megkapta: " . $assigned_to_user;
                    $log_stmt_assigned_to->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt_assigned_to->bindParam(':muvelet', $muvelet_assigned_to, PDO::PARAM_STR);
                    $log_stmt_assigned_to->bindParam(':kezelo', $assigned_to, PDO::PARAM_INT);
                    $log_stmt_assigned_to->execute();
                }

                $_SESSION['success'] = "A feladat sikeresen elvállalva.";
                // Navigálás a részletező oldalra, ha sikeres volt
                header("Location: ./details.php?id=" . $report_id);
                exit();
            } else {
                // Nem történt változás
                $_SESSION['error'] = "Nem történt változás a feladat állapotában.";
                // Irányítsd az index oldalra
                header("Location: ./index.php");
                exit();
            }
        } catch (PDOException $e) {
            // Hiba kezelése
            $_SESSION['error'] = "Hiba történt: " . $e->getMessage();
            // Irányítsd az index oldalra
            header("Location: ./index.php");
            exit();
        }
    } else {
        // Ha a report_id vagy assigned_to nem létezik
        $_SESSION['error'] = "Érvénytelen kérés.";
        // Irányítsd az index oldalra
        header("Location: ./index.php");
        exit();
    }
} else {
    // Ha a kérés nem POST
    header("Location: ./index.php");
    exit();
}
?>
