<?php
session_start(); 
require_once "dbh.inc.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['report_id']) && isset($_POST['assigned_to'])) {
        $report_id = $_POST['report_id'];
        $assigned_to = $_POST['assigned_to']; 
        $current_user_id = $_SESSION['user_id']; 

        try {
            
            $query = "UPDATE reports SET assigned_to = :assigned_to, report_status = 'Elvállalt' WHERE id = :report_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
            $stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                
                $current_user_name = $_SESSION['username'];
                $assigned_to_query = "SELECT username FROM users WHERE user_id = :assigned_to";
                $assigned_to_stmt = $pdo->prepare($assigned_to_query);
                $assigned_to_stmt->bindParam(':assigned_to', $assigned_to, PDO::PARAM_INT);
                $assigned_to_stmt->execute();
                $assigned_to_user = $assigned_to_stmt->fetchColumn(); 

                if ($assigned_to == $current_user_id) {
                    
                    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
                    $log_stmt = $pdo->prepare($log_query);
                    $muvelet = "Feladatot elvállalta: " . $assigned_to_user;
                    $log_stmt->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt->bindParam(':muvelet', $muvelet, PDO::PARAM_STR);
                    $log_stmt->bindParam(':kezelo', $assigned_to, PDO::PARAM_INT);
                    $log_stmt->execute();
                } else {
                    
                    $log_query = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
                    
                    
                    $log_stmt_assigned_by = $pdo->prepare($log_query);
                    $muvelet_assigned_by = "Feladatott kiosztotta: " . $current_user_name;
                    $log_stmt_assigned_by->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt_assigned_by->bindParam(':muvelet', $muvelet_assigned_by, PDO::PARAM_STR);
                    $log_stmt_assigned_by->bindParam(':kezelo', $current_user_id, PDO::PARAM_INT);
                    $log_stmt_assigned_by->execute();

                    
                    $log_stmt_assigned_to = $pdo->prepare($log_query);
                    $muvelet_assigned_to = "Feladatot megkapta: " . $assigned_to_user;
                    $log_stmt_assigned_to->bindParam(':report_id', $report_id, PDO::PARAM_INT);
                    $log_stmt_assigned_to->bindParam(':muvelet', $muvelet_assigned_to, PDO::PARAM_STR);
                    $log_stmt_assigned_to->bindParam(':kezelo', $assigned_to, PDO::PARAM_INT);
                    $log_stmt_assigned_to->execute();
                }

                $_SESSION['success'] = "A feladat sikeresen elvállalva.";
                
                header("Location: ./details.php?id=" . $report_id);
                exit();
            } else {
                
                $_SESSION['error'] = "Nem történt változás a feladat állapotában.";
                
                header("Location: ./index.php");
                exit();
            }
        } catch (PDOException $e) {
            
            $_SESSION['error'] = "Hiba történt: " . $e->getMessage();
            
            header("Location: ./index.php");
            exit();
        }
    } else {
        
        $_SESSION['error'] = "Érvénytelen kérés.";
        
        header("Location: ./index.php");
        exit();
    }
} else {
    
    header("Location: ./index.php");
    exit();
}
?>
