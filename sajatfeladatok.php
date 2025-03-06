<?php
header('Cache-Control: no cache'); 
session_cache_limiter('private_no_expire'); 
session_start();
$rank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;
$formattedUsername = $_SESSION['formatted_username'];
$_SESSION['from'] = 'sajat';

function relativeTime($datetime) {
    $now = new DateTime();  
    $then = new DateTime($datetime);  
    $diff = $now->diff($then);

    if ($diff->y >= 1) {
        return $diff->y . " éve";
    } elseif ($diff->m >= 1) {
        return $diff->m . " hónapja";
    } elseif ($diff->d >= 14) {
        $weeks = floor($diff->d / 7);
        return $weeks . " hete";
    } elseif ($diff->d >= 7) {
        return "múlt hét";
    } elseif ($diff->d == 1) {
        return "tegnap";  
    } elseif ($diff->d > 1) {
        return $diff->d . " napja";
    } elseif ($diff->h >= 1) {
        return $diff->h . " órája";
    } elseif ($diff->i >= 1) {
        return $diff->i . " perce";
    } else {
        return "Most";
    }
}

function getPriorityText($priority) {
    switch ($priority) {
        case '1':
            return 'Nagyon alacsony';
        case '2':
            return 'Alacsony';
        case '3':
            return 'Közepes';
        case '4':
            return 'Magas';
        case '5':
            return 'Nagyon magas';
        default:
            return 'Nincs megadva';
    }
}

function getPriorityColor($priority) {
    switch ($priority) {
        case '1':
            return '#66cc66';  
        case '2':
            return '#069106';  
        case '3':
            return '#ffd83b';  
        case '4':
            return '#ff8c00';  
        case '5':
            return '#cc271b';  
        default:
            return '#d9d9d9';  
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        require_once "dbh.inc.php";
        
        
        $selected_status = isset($_POST['status']) ? $_POST['status'] : ''; 
        $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'created_at'; 
        $sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'DESC'; 

        
        $user_id = $_SESSION['user_id']; 
        $query = "SELECT id, title, created_at, report_status, priority, show_to_user 
            FROM reports 
            WHERE assigned_to = :user_id 
            AND show_to_user = 1";  

        if ($selected_status === 'Összes') {
            
        } elseif ($selected_status) {
            $query .= " AND report_status = :status"; 
        }

        
        $query .= " ORDER BY $sort_by $sort_order"; 

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($selected_status && $selected_status !== 'Összes') {
            $stmt->bindParam(':status', $selected_status, PDO::PARAM_STR);
        }

        $stmt->execute();
        $results_assigned = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null; 
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ./logout.php");
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feladatok</title>
    <link rel="stylesheet" href="feladatok.css">
    <link rel="stylesheet" href="https:
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <div class="navbar">
        <form action="dashboard.php" method="POST">
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
        <div class="tables-container">
            <!-- Saját feladatok táblázata -->
            <div class="table-wrapper">
                <h1>Saját Feladataim</h1>

                <table id="taskTable">
                    <tr>
                        <th onclick="sortTable(0)">
                            ID
                            <span class="sort-icon" id="sort-icon-0">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(1)">
                            Cím
                            <span class="sort-icon" id="sort-icon-1">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(2)">
                            Létrehozva
                            <span class="sort-icon" id="sort-icon-2">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th onclick="sortTable(3)">
                            Prioritás
                            <span class="sort-icon" id="sort-icon-3">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="status-dropdown-th">
                        <form method="POST" action="">
                            <div class="filters">
                                <!-- Statusz szűrés -->
                                <select name="status" id="status" class="status-dropdown" onchange="this.form.submit()">
                                    <option value="Összes" <?= $selected_status == 'Összes' ? 'selected' : '' ?>>Összes</option>
                                    <option value="Elvállalt" <?= $selected_status == 'Elvállalt' ? 'selected' : '' ?>>Elvállalt</option>
                                    <option value="Elfogadásra vár" <?= $selected_status == 'Elfogadásra vár' ? 'selected' : '' ?>>Elfogadásra vár</option>
                                    <option value="Beszerzés szükséges" <?= $selected_status == 'Beszerzés szükséges' ? 'selected' : '' ?>>Beszerzés szükséges</option>
                                    <option value="Kész" <?= $selected_status == 'Kész' ? 'selected' : '' ?>>Kész</option>
                                </select>
                            </div>
                        </form>
                        </th>
                    </tr>
                    <?php if (empty($results_assigned)) : ?>
                        <tr>
                            <td colspan="5">Nincs feladatod jelenleg!</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($results_assigned as $row) : ?>
                            <?php
                            
                            if ($_SESSION['rank'] == 2 && $row['report_status'] === 'Archivált') {
                                $row['report_status'] = 'Kész';
                            }

                            $rowClass = ($row['report_status'] === 'Elutasítva') ? 'rejected-row' : (($row['report_status'] === 'Kész' || $row['report_status'] === 'Archivált') ? 'accepted-row' : '');
                            ?>
                            <tr class="<?= $rowClass ?>" onclick="location.href='details.php?id=<?= $row['id'] ?>'">
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td title="<?= (new DateTime($row['created_at']))->format('Y-m-d H:i') ?>"><?= relativeTime($row['created_at']) ?></td>
                                <td class="priority-td">
                                    <div class="priority-box" style="background-color: <?= getPriorityColor($row['priority']); ?>;">
                                        <?= getPriorityText($row['priority']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($row['report_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="button-group">
            <button class="back-button" type="button" onclick="window.location.href='dashboard.php'">
                <i class="fa-solid fa-chevron-left"></i> Vissza
            </button>
        </div>
    </div>

    <script>
        let lastSortedColumn = -1;
        let lastSortDirection = 'asc';

        function getPriorityValue(priorityText) {
            switch (priorityText) {
                case "Nagyon alacsony":
                    return 1;
                case "Alacsony":
                    return 2;
                case "Közepes":
                    return 3;
                case "Magas":
                    return 4;
                case "Nagyon magas":
                    return 5;
                default:
                    return 0;
            }
        }

        function sortTable(n) {
            var table = document.getElementById("taskTable");
            var rows = table.rows;
            var switching = true;
            var dir = (lastSortedColumn === n && lastSortDirection === 'asc') ? 'desc' : 'asc';
            var switchcount = 0;

            if (lastSortedColumn !== -1) {
                document.getElementById("sort-icon-" + lastSortedColumn).classList.remove('sorted-asc', 'sorted-desc');
                document.getElementById("sort-icon-" + lastSortedColumn).querySelector('i').classList.remove('fa-sort-up', 'fa-sort-down');
            }

            while (switching) {
                switching = false;
                var rowsArray = Array.from(rows);
                for (var i = 1; i < rowsArray.length - 1; i++) {
                    var x = rowsArray[i].getElementsByTagName("TD")[n];
                    var y = rowsArray[i + 1].getElementsByTagName("TD")[n];
                    var shouldSwitch = false;

                    if (dir == "asc") {
                        if (n === 3) { 
                            var xValue = getPriorityValue(x.innerText);
                            var yValue = getPriorityValue(y.innerText);
                            if (xValue > yValue) {
                                shouldSwitch = true;
                                break;
                            }
                        } else if (n === 2) { 
                            var xDate = new Date(x.getAttribute("title"));
                            var yDate = new Date(y.getAttribute("title"));
                            if (xDate > yDate) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    } else if (dir == "desc") {
                        if (n === 3) { 
                            var xValue = getPriorityValue(x.innerText);
                            var yValue = getPriorityValue(y.innerText);
                            if (xValue < yValue) {
                                shouldSwitch = true;
                                break;
                            }
                        } else if (n === 2) { 
                            var xDate = new Date(x.getAttribute("title"));
                            var yDate = new Date(y.getAttribute("title"));
                            if (xDate < yDate) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }
                }
                if (shouldSwitch) {
                    rowsArray[i].parentNode.insertBefore(rowsArray[i + 1], rowsArray[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount === 0 && dir === "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }

            const icon = document.getElementById("sort-icon-" + n).querySelector('i');
            if (dir === 'asc') {
                icon.classList.add('fa-sort-up');
                icon.classList.remove('fa-sort-down');
                document.getElementById("sort-icon-" + n).classList.add('sorted-asc');
            } else {
                icon.classList.add('fa-sort-down');
                icon.classList.remove('fa-sort-up');
                document.getElementById("sort-icon-" + n).classList.add('sorted-desc');
            }

            lastSortedColumn = n;
            lastSortDirection = dir;
        }
    </script>

</body>
</html>
