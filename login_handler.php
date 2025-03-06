<?php
session_start();
require_once "dbh.inc.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    
    if (!empty($username) && !empty($password)) {
        
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id']; 
            $_SESSION['rank'] = $user['group_id']; 

            
            $names = explode('.', $user['username']);  
            $formattedNames = array_map('ucwords', $names);  
            $_SESSION['formatted_username'] = implode(' ', $formattedNames);  

            
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Hibás felhasználónév vagy jelszó.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Kérjük, töltse ki az összes mezőt!";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
