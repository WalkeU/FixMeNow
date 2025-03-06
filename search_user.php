<?php

require_once 'dbh.inc.php';

if (isset($_GET['user'])) {
    $userInput = trim($_GET['user']);
    
    
    if (!empty($userInput)) {
        
        $query = "SELECT username FROM users WHERE username LIKE :username LIMIT 10";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':username', "%$userInput%", PDO::PARAM_STR);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        echo json_encode($users);
    } else {
        echo json_encode([]);
    }
}
?>
