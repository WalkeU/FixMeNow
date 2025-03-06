<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $short_issue = trim($_POST["short_issue"]);
    $issue = trim($_POST["issue"]);

    
    if ($_POST["location"] == "Egyéb") {
        $location = trim($_POST["location_custom"]);
        $c_location = 1; 
    } else {
        $location = trim($_POST["location"]);
        $c_location = 0; 
    }

    
    $issueTypes = isset($_POST["issue_type"]) ? explode(',', $_POST["issue_type"]) : [];
    $tagsString = implode(',', $issueTypes);

    
    if (isset($_FILES['images'])) {
    $uploadDirectory = "uploads/"; 
    $uploadedFiles = $_FILES["images"];
        
        
        
        $newFileNames = [];
        
        foreach ($uploadedFiles["name"] as $index => $fileName) {
            $tmpName = $uploadedFiles["tmp_name"][$index];
            $fileSize = $uploadedFiles["size"][$index];
            $fileType = $uploadedFiles["type"][$index];
            $fileError = $uploadedFiles["error"][$index];
            
            if ($fileError === UPLOAD_ERR_OK) {
                $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                if (in_array($fileType, $allowedTypes)) {
                    $targetFilePath = $uploadDirectory . basename($fileName);
        
                    
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        echo "A fájl sikeresen áthelyezve: $fileName<br>"; 
                        
                        
                        $newFileNames[] = $targetFilePath; 
                    } else {
                        echo "Hiba történt a fájl mozgatásakor: $fileName<br>"; 
                    }
                } else {
                    echo "A fájl típusa nem engedélyezett: $fileName<br>"; 
                }
            } else {
                echo "Feltöltési hiba a fájlnál: $fileName, hiba kód: $fileError<br>"; 
            }
        }

        $fileNamesString = implode(",", $newFileNames);
    }

    $priority = isset($_POST["priority"]) && $_POST["priority"] !== "" ? intval($_POST["priority"]) : 3;

    try {
        require_once "dbh.inc.php";

        $query = "INSERT INTO reports (title, description, location, c_location, images, tags, reported_by, report_status, priority)
        VALUES (:title, :description, :location, :c_location, :fileNames, :tags, :reported_by, 'Nyitott', :priority);";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam("title", $short_issue);
        $stmt->bindParam("description", $issue);
        $stmt->bindParam("location", $location);
        $stmt->bindParam("c_location", $c_location);
        $stmt->bindParam("fileNames", $fileNamesString, PDO::PARAM_STR);
        $stmt->bindParam("tags", $tagsString);
        $stmt->bindParam("priority", $priority);
        $stmt->bindParam("reported_by", $_SESSION['user_id']);

        $stmt->execute();

        $reportId = $pdo->lastInsertId();

        $logQuery = "INSERT INTO logs (report_id, muvelet, kezelo) VALUES (:report_id, :muvelet, :kezelo)";
        $logStmt = $pdo->prepare($logQuery);
        $muvelet = "Új hibabejelentés létrehozta: " . $_SESSION['username'];
        $kezelo = $_SESSION['user_id'];

        $logStmt->bindParam("report_id", $reportId);
        $logStmt->bindParam("muvelet", $muvelet);
        $logStmt->bindParam("kezelo", $kezelo);
        $logStmt->execute();

        $pdo = null;
        $stmt = null;
        $logStmt = null;

        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: index.php"); 
    exit();
}
?>
