<?php
session_start();
require_once "dbh.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_id = $_POST['report_id'];
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    try {
        // Új megjegyzés hozzáadása az adatbázishoz
        $query = "INSERT INTO comments (report_id, user_id, comment_text) VALUES (:report_id, :user_id, :comment_text)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':report_id', $report_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':comment_text', $comment_text);
        $stmt->execute();

        // Lekérdezzük a logokat
        $query = "SELECT * FROM logs WHERE report_id = :report_id ORDER BY datum DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':report_id', $report_id);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lekérdezzük a megjegyzéseket
        $query = "SELECT c.comment_text, c.comment_date, u.username 
                  FROM comments c 
                  JOIN users u ON c.user_id = u.user_id 
                  WHERE c.report_id = :report_id 
                  ORDER BY c.comment_date ASC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':report_id', $report_id);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Logok és kommentek összevonása időrend szerint
        $logAndComments = array_merge(
            array_map(function($log) {
                return [
                    'type' => 'log',
                    'text' => $log['muvelet'],
                    'date' => $log['datum']
                ];
            }, $logs),
            array_map(function($comment) {
                return [
                    'type' => 'comment',
                    'text' => $comment['comment_text'],
                    'date' => $comment['comment_date'],
                    'username' => $comment['username']
                ];
            }, $comments)
        );

        // Rendezés időrend szerint (legújabb elem alul)
        usort($logAndComments, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        echo json_encode(['success' => true, 'logAndComments' => $logAndComments]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
