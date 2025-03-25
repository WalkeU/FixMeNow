<?php
session_start();


if (!isset($_GET['id'])) {
    header("Location: ./index.php");
    exit();
}

$backLink = 'index.php'; 
$rank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;
$formattedUsername = $_SESSION['formatted_username'];


if (isset($_SESSION['from'])) {
    if ($_SESSION['from'] == 'uj') {
        $backLink = 'ujfeladatok.php';
    } elseif ($_SESSION['from'] == 'ossz') {
        $backLink = 'osszesfeladat.php';
    } elseif ($_SESSION['from'] == 'sajat') {
        $backLink = 'sajatfeladatok.php';
    }
}


if (!isset($_SESSION['user_id'])) {
    
    header("Location: ./index.php");
    exit();
}

require_once "dbh.inc.php";


$report = null;
$reported_by_username = "Ismeretlen felhasználó";
$users = [];
$logs = [];
$assignedToUsername = "";


try {
    $id = $_GET['id'];

    
    $query = "SELECT *, reported_by, assigned_to FROM reports WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($report) {
        $reported_by = $report['reported_by'];
        $query = "SELECT username FROM users WHERE user_id = :reported_by";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':reported_by', $reported_by);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $reported_by_username = $user ? htmlspecialchars($user['username']) : "Ismeretlen felhasználó";
    }

    
    if ($_SESSION['rank'] == 3) {
        $query = "SELECT user_id, username FROM users WHERE group_id != 1";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    $query = "SELECT * FROM logs WHERE report_id = :report_id ORDER BY datum DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':report_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    $query = "SELECT c.comment_text, c.comment_date, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.user_id 
    WHERE c.report_id = :report_id 
    ORDER BY c.comment_date ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':report_id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
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

    
    usort($logAndComments, function($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']); 
    });

    
    $assignedUserId = $_SESSION['user_id'];
    if (!empty($report['assigned_to']) && $assignedUserId != $report['assigned_to']) {
        $query = "SELECT username FROM users WHERE user_id = :assigned_to";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':assigned_to', $report['assigned_to'], PDO::PARAM_INT);
        $stmt->execute();
        $assignedUser = $stmt->fetch(PDO::FETCH_ASSOC);
        $assignedToUsername = $assignedUser ? htmlspecialchars($assignedUser['username']) : "";
    }

    $pdo = null;
    $stmt = null;
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>  

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Részletes leírás</title>
    <link rel="stylesheet" href="details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="navbar.css">

</head>
<body>
<div class="navbar">
        <form action="dashboard.php">
            <button class="navbar-button-home">
                <i class="fas fa-house"></i>
            </button>
        </form>
    
        <form action="hibjel.php" method="POST">
            <button type="submit" class="navbar-button">
                <span>Hiba bejelentése</span>
                <i class="fas fa-bug"></i>
            </button>
        </form>

        <?php if ($rank !== 1): ?>
            <form action="sajatfeladatok.php" method="POST">
                <button type="submit" class="navbar-button">
                    <span>Saját Feladatok</span>
                    <i class="fas fa-user-check"></i>
                </button>
            </form>
        <?php endif; ?>

        <?php if ($rank == 3): ?>
            <form action="osszesfeladat.php" method="POST">
                <button type="submit" class="navbar-button">
                    <span>Összes Feladat</span>
                    <i class="fas fa-tasks"></i>
                </button>
            </form>
        <?php endif; ?>

        <?php if (isset($_SESSION['rank']) && $_SESSION['rank'] == 3): ?>
            <form method="POST" action="search.php">
                <button class="navbar-button" type="submit">
                    <span>Keresés</span>
                    <i class="fas fa-search"></i>
                </button>
            </form>
        <?php endif; ?>

        <?php if ($rank == 2): ?>
            <form action="ujfeladatok.php" method="POST">
                <button type="submit" class="navbar-button">
                    <span>Beérkezett Feladatok</span>
                    <i class="fas fa-envelope"></i>
                </button>
            </form>
        <?php endif; ?>

        <div class="navbar-right">
            <img src="images/jlogo.png" alt="Jedlik Logo" class="navbar-logo">
            <span class="username"><?php echo htmlspecialchars($formattedUsername); ?></span>
            <form action="logout.php" method="POST">
                <button type="submit" class="navbar-logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="container">
        <h1>Részletes leírás (ID:<?php echo htmlspecialchars($_GET['id']); ?>)</h1>
        <table class="report-table">
            <tr>
                <td><strong>ID:</strong></td>
                <td><?php echo htmlspecialchars($_GET['id']); ?></td>
            </tr>
            <tr>
                <td><strong>Cím:</strong></td>
                <td><?php echo htmlspecialchars($report['title']); ?></td>
            </tr>

            <?php if ($assignedToUsername || ($_SESSION['rank'] == 3 && !empty($report['assigned_to']))): ?>
                <tr>
                    <td><strong>Feladat kiosztva:</strong></td>
                    <td>
                        <?php if ($_SESSION['rank'] == 3): ?>
                            <form action="change_assign.php" method="POST" class="assign-form">
                                <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                                <div class="change-assign-container">
                                    <select name="assigned_to" class="styled-select">
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo htmlspecialchars($user['user_id']); ?>" <?php echo $user['user_id'] == $report['assigned_to'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($user['username']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="change-assign-button">Változtatás</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($assignedToUsername); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
            
            <?php if ($_SESSION['rank'] == 3 && empty($report['assigned_to'])): ?>
                <form action="assign_task.php" method="post" class="assignment-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <tr>
                        <td><strong>Feladat kiosztása:</strong></td>
                        <td>
                            <div class="change-assign-container">
                                <select class="styled-select" name="assigned_to" id="assignUserDropdown">
                                    <option value="<?php echo $assignedUserId; ?>">Én</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="change-assign-button" type="submit">Feladat elvállalása</button>
                            </div>
                        </td>
                    </tr>
                </form>
            <?php endif; ?>

            <?php if ($_SESSION['rank'] == 3): ?>
                <tr>
                    <td><strong>Státusz:</strong></td>
                    <td>
                        <form action="change_status.php" method="POST" class="status-form">
                            <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                            <div class="change-assign-container">
                                <select name="status" class="styled-select">
                                    <?php if ($report['report_status'] == 'Elvállalt'): ?>
                                        <option value="Elvállalt" selected>Elvállalt</option>
                                    <?php endif; ?>
                                    <?php if ($report['report_status'] == 'Elfogadásra vár'): ?>
                                        <option value="Elfogadásra vár" selected>Elvállalt</option>
                                    <?php endif; ?>
                                    <?php if ($report['report_status'] == 'Elfogadásra vár'): ?>
                                        <option value="Elfogadásra vár" selected>Elvállalt</option>
                                    <?php endif; ?>
                                    <option value="Nyitott" <?php echo $report['report_status'] == 'Nyitott' ? 'selected' : ''; ?>>Nyitott</option>
                                    <option value="Kész" <?php echo $report['report_status'] == 'Kész' ? 'selected' : ''; ?>>Kész</option>
                                    <option value="Archivált" <?php echo $report['report_status'] == 'Archivált' ? 'selected' : ''; ?>>Archivált</option>
                                    <option value="Meghíusult" <?php echo $report['report_status'] == 'Meghíusult' ? 'selected' : ''; ?>>Meghíusult</option>

                                </select>
                                <button type="submit" class="change-assign-button">Változtatás</button>
                            </div>
                        </form>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><strong>Státusz:</strong></td>
                    <td><?php echo htmlspecialchars($report['report_status']); ?></td>
                </tr>
            <?php endif; ?>


            <tr>
                <td><strong>Részletek:</strong></td>
                <td class="details"><?php echo htmlspecialchars($report['description']); ?></td>
            </tr>
            <tr>
                <td><strong>Hely:</strong></td>
                <td><?php echo htmlspecialchars($report['location']); ?></td>
            </tr>
            <tr>
                <td><strong>Létrehozva:</strong></td>
                <td><?php echo (new DateTime($report['created_at']))->format('Y-m-d H:i'); ?></td>
            </tr>
            <tr>
                <td><strong>Hiba jelentő neve:</strong></td>
                <td><?php echo $reported_by_username; ?></td>
            </tr>
            <tr>
                <td><strong>Hiba típusa:</strong></td>
                <td>
                    <?php
                    $tags = explode(',', $report['tags']);
                    if (!empty($tags[0])) {
                        foreach ($tags as $tag) {
                            echo '<span class="tag">' . htmlspecialchars(trim($tag)) . '</span> ';
                        }
                    } else {
                        echo 'Nincsen megadva.';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Prioritás:</strong></td>
                <td>
                    <?php 
                        
                        $priorityText = "Nincs meghatározva"; 
                        $priorityClass = "priority-default"; 

                        if (!empty($report['priority'])) {
                            switch ($report['priority']) {
                                case 1:
                                    $priorityText = "Nagyon alacsony";
                                    $priorityClass = "priority-lowest"; 
                                    break;
                                case 2:
                                    $priorityText = "Alacsony";
                                    $priorityClass = "priority-low"; 
                                    break;
                                case 3:
                                    $priorityText = "Közepes";
                                    $priorityClass = "priority-medium"; 
                                    break;
                                case 4:
                                    $priorityText = "Magas";
                                    $priorityClass = "priority-high"; 
                                    break;
                                case 5:
                                    $priorityText = "Nagyon magas";
                                    $priorityClass = "priority-highest"; 
                                    break;
                                default:
                                    $priorityText = "Érvénytelen prioritás";
                                    $priorityClass = "priority-invalid"; 
                            }
                        }
                    ?>
                    <span class="<?php echo $priorityClass; ?>"><?php echo $priorityText; ?></span>
                </td>
            </tr>
            <?php if (!empty($report['images'])): ?>
                <tr>
                    <td><strong>Feltöltött képek:</strong></td>
                    <td> 
                    </td>
                </tr>
            <?php endif; ?>
        </table>

        <p>
            <?php
            $images = explode(',', $report['images']);
            if (empty(trim($report['images']))) {
                echo ' Nincsenek feltöltve képek.';
            } else {
                echo '<div class="image-gallery">';
                foreach ($images as $image) {
                    $image = trim($image);
                    if (!empty($image)) {
                        echo '<img src="' . htmlspecialchars($image) . '" alt="Kép" class="report-image" onclick="openPopup(\'' . htmlspecialchars($image) . '\')" />';
                    }
                }
                echo '</div>';
            }
            ?>
        </p>


        <div id="image-popup" class="popup" onclick="closePopup()">
            <span class="close">&times;</span>
            <img class="popup-content-img" id="popup-img">
            <div id="caption"></div>
        </div>

        <div id="log-popup" class="popup-logs">
            <div class="popup-content">
                <span class="close" onclick="closeLogPopup()">&times;</span>
                <h2 class="log-h2-popup">Logok és megjegyzések</h2>
                <div class="log-list-container"> <!-- Új konténer -->
                    <ul>
                        <?php foreach ($logAndComments as $entry): ?>
                            <li>
                                <?php if ($entry['type'] == 'log'): ?>
                                    <?php echo htmlspecialchars((new DateTime($entry['date']))->format('Y-m-d H:i') . ' ' . $entry['text']); ?>
                                <?php else: ?>
                                    <span class="comment-date"><?php echo (new DateTime($entry['date']))->format('Y-m-d H:i'); ?></span>
                                    <strong><?php echo htmlspecialchars($entry['username']); ?>:</strong>
                                    <?php echo htmlspecialchars($entry['text']); ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <form id="commentForm" class="comment-form" onsubmit="return addComment(event);">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <textarea name="comment_text" required placeholder="Ide írhatja a megjegyzést..." 
                            onkeydown="handleTextareaKeydown(event)"></textarea>
                    <button class="comment-button" type="submit">Hozzáadás</button>
                </form>
            </div>
        </div>


        <div class="button-group">
            <form action="<?php echo $backLink; ?>" method="post" class="button-form">
                <button class="back-button" type="submit">
                <i class="fa-solid fa-chevron-left"></i>
                    Vissza
                </button>
            </form>

            <button class="logs-button" onclick="openLogPopup()">Logok és megjegyzések</button>

            <?php if (empty($report['assigned_to']) && $_SESSION['rank'] != 3): ?>
                <form action="assign_task.php" method="post" class="assignment-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <input type="hidden" name="assigned_to" id="assignedUserInput" value="<?php echo htmlspecialchars($assignedUserId); ?>">
                    <button class="accept-button" type="submit">
                        Feladat elvállalása
                        <i class="fa-solid fa-thumbtack"></i>
                    </button>
                </form>
            <?php elseif ($report['report_status'] === 'Elfogadásra vár' && $_SESSION['rank'] == 2): ?>
                <div class="processing-message">
                    Feldolgozás alatt
                    <i class="fas fa-rotate"></i>
                </div>
            <?php endif; ?>

            <?php if (!empty($report['assigned_to']) && $_SESSION['rank'] == 2 &&
                    ($report['report_status'] === 'Elvállalt' || $report['report_status'] === 'Elutasítva')): ?>
                <form action="waiting_status_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="accept-button" type="submit">
                        Beszerzés szükséges
                        <i class="fas fa-screwdriver-wrench"></i>
                    </button>
                </form>
            <?php endif; ?>


            <?php if (!empty($report['assigned_to']) && 
                    ($report['report_status'] === 'Elvállalt' ||
                    $report['report_status'] === 'Beszerzés alatt')):        ?>
                <form action="submit_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="accept-button" type="submit">
                        Elvégezve
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            <?php endif; ?>


            <?php if (!empty($report['assigned_to']) && ($report['report_status'] === 'Kész' || $report['report_status'] === 'Archivált')
            && $_SESSION['rank'] == 2): ?>
                <form action="remove_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="delete-button" type="submit">
                        Törlés
                        <i class="fas fa-archive"></i>
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($report['report_status'] === 'Elfogadásra vár' && $_SESSION['rank'] == 3): ?>
                <form action="approve_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="accept-button" type="submit">
                        Elfogad
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            <?php endif; ?>


            <?php if ($report['report_status'] === 'Elfogadásra vár' && $_SESSION['rank'] == 3): ?>
                <form action="disapprove_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="delete-button" type="submit">
                        Elutasít
                        <i class="fas fa-xmark"></i>
                    </button>
                </form>
            <?php endif; ?>

            <?php if (!empty($report['assigned_to']) && $report['report_status'] === 'Kész' && $_SESSION['rank'] == 3): ?>
                <form action="archive_report.php" method="post" class="status-form">
                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($report['id']); ?>">
                    <button class="delete-button" type="submit">
                        Archiválás
                        <i class="fas fa-archive"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function openPopup(imageSrc) {
        document.getElementById("image-popup").style.display = "block";
        document.getElementById("popup-img").src = imageSrc;
        document.getElementById("caption").innerHTML = imageSrc;
    }
    function closePopup() {
        document.getElementById("image-popup").style.display = "none";
    }
    function setAssignedUser(userId) {
        document.getElementById("assignedUserInput").value = userId;
    }
    function openLogPopup() {
        document.getElementById("log-popup").style.display = "block";
    }
    function closeLogPopup() {
        document.getElementById("log-popup").style.display = "none";
    }

    function addComment(event) {
        event.preventDefault(); 

        var formData = new FormData(document.getElementById("commentForm"));

        fetch('add_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                updateLogAndCommentList(data.logAndComments);
                
                document.querySelector('textarea[name="comment_text"]').value = '';
            } else {
                alert("Hiba történt a megjegyzés hozzáadása során.");
            }
        })
        .catch(error => console.error('Error:', error));

        return false; 
    }

    function updateLogAndCommentList(logAndComments) {
        const logList = document.querySelector('#log-popup ul');
        logList.innerHTML = ''; 

        logAndComments.forEach(entry => {
            const li = document.createElement('li');
            const date = new Date(entry.date);
            const formattedDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')} ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
            
            if (entry.type === 'log') {
                li.textContent = `${formattedDate} ${entry.text}`;
            } else {
                li.innerHTML = `<span class="comment-date">${formattedDate}</span>
                                <strong>${entry.username}:</strong> ${entry.text}`;
            }
            logList.appendChild(li);
        });
    }

    function handleTextareaKeydown(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            
            event.preventDefault();

            
            addComment(event);
        }
    }
    </script>
</body>
</html>
