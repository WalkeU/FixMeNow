<?php
    header('Cache-Control: no cache');
    session_cache_limiter('private_no_expire');
    session_start();
    require_once "dbh.inc.php"; 

    $rank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;
    $formattedUsername = $_SESSION['formatted_username'];

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        
        header("Location: index.php");
        exit();
    }

    
    function getRelativeTime($dateTime) {
        $now = new DateTime();
        $diff = $now->diff($dateTime);

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

    function getPriorityTextAndClass($priority) {
        switch ($priority) {
            case 1:
                return ["Nagyon alacsony", "priority-lowest"];
            case 2:
                return ["Alacsony", "priority-low"];
            case 3:
                return ["Közepes", "priority-medium"];
            case 4:
                return ["Magas", "priority-high"];
            case 5:
                return ["Nagyon magas", "priority-highest"];
            default:
                return ["Érvénytelen prioritás", "priority-invalid"];
        }
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keresési oldal</title>
    <link rel="stylesheet" href="search.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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


    <div class="main-container">
        <div class="search-form-container">
            <form method="POST" action="">
                <h1>Keresési oldal</h1>


                <div class="first-row">
                    <div class="id">
                        <label for="id">ID:</label>
                        <input type="number" name="id" id="id" autocomplete="off" min="1">
                    </div>
                    <div class="short_issue">
                        <label for="title">Cím:</label>
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>



                <label for="description">Leírás:</label>
                <input type="text" name="description" id="description" autocomplete="off">

                <div class="form-row">
                    <div class="form-group">
                        <label for="time">Idő:</label>
                        <input type="date" name="time" id="time">
                    </div>
                    <div class="form-group">
                        <label for="location">Helyszín:</label>
                        <select id="location" class="location_dropdown" name="location" onchange="toggleCustomLocation()">
                            <option value="" disabled selected>Válasszon helyszínt</option>
                            <option value="Egyéb">Egyéb</option>
                            <option value="1. terem">1. terem</option>
                            <option value="2. terem">2. terem</option>
                            <option value="3. terem">3. terem</option>
                        </select>
                    </div>
                </div>

                <label>Hiba fajtája:</label>
                <div class="tag-container">
                    <input type="checkbox" name="tags[]" value="Hardver" id="tag_hardver">
                    <label for="tag_hardver" class="tag-label">Hardver</label>

                    <input type="checkbox" name="tags[]" value="Szoftver" id="tag_szoftver">
                    <label for="tag_szoftver" class="tag-label">Szoftver</label>

                    <input type="checkbox" name="tags[]" value="Hálózati" id="tag_halozati">
                    <label for="tag_halozati" class="tag-label">Hálózati</label>

                    <input type="checkbox" name="tags[]" value="Egyéb" id="tag_egyeb">
                    <label for="tag_egyeb" class="tag-label">Egyéb</label>
                </div>

                <label for="user">Felhasználó:</label>
                <input type="text" name="user" id="user" autocomplete="off">
                <div id="user_suggestions" class="suggestions-box"></div>

                <label for="archive_search">
                    <input type="checkbox" name="archive_search" id="archive_search"> Keresés az archivumban
                </label>

                <button class="search" type="submit">Keresés</button>
            </form>
        </div>

        <div class="results-container">
            <div class="table-wrapper">
            <?php
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
                    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
                    $time = isset($_POST['time']) ? trim($_POST['time']) : '';
                    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
                    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
                    $user = isset($_POST['user']) ? trim($_POST['user']) : '';
                    $archive_search = isset($_POST['archive_search']) ? true : false;

                    $time_type = isset($_POST['time_type']) ? $_POST['time_type'] : 'created_at';

                } else {
                    
                    $title = '';
                    $description = '';
                    $time = '';
                    $location = '';
                    $tags = [];
                    $user = '';
                    $archive_search = false;
                }

                $query = "SELECT * FROM reports WHERE 1=1";

                if (!empty($_POST['id'])) {
                    $query .= " AND id = :id";
                }

                
                if ($location === 'Egyéb') {
                    $query .= " AND c_location = 1";  
                } else {
                    if (!empty($location)) {
                        $query .= " AND location LIKE :location";
                    }
                }

                if (!empty($title)) {
                    $query .= " AND title LIKE :title";
                }
                if (!empty($description)) {
                    $query .= " AND description LIKE :description";
                }
                if (!empty($time)) {
                    $query .= " AND $time_type >= :time";
                }
                if (!empty($tags)) {
                    $query .= " AND (";
                    foreach ($tags as $key => $tag) {
                        if ($key > 0) {
                            $query .= " OR ";
                        }
                        $query .= "tags LIKE :tag" . $key;
                    }
                    $query .= ")";
                }
                if (!empty($user)) {
                    $query .= " AND (reported_by LIKE :user OR assigned_to LIKE :user)";
                }
                if (!empty($time)) {
                    $query .= " AND DATE($time_type) = :time"; 
                }

                
                if ($archive_search) {
                    $query .= " AND report_status = 'Archivált'";
                } else {
                    $query .= " AND report_status != 'Archivált'";
                }

                $query .= " ORDER BY created_at DESC";

                try {
                    $stmt = $pdo->prepare($query);
                    if (!empty($_POST['id'])) {
                        $stmt->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                    }
                    if (!empty($title)) {
                        $stmt->bindValue(':title', "%$title%", PDO::PARAM_STR);
                    }
                    if (!empty($description)) {
                        $stmt->bindValue(':description', "%$description%", PDO::PARAM_STR);
                    }
                    if (!empty($time)) {
                        $stmt->bindValue(':time', $time, PDO::PARAM_STR);
                    }
                    if (!empty($location) && $location !== 'Egyéb') {
                        $stmt->bindValue(':location', "%$location%", PDO::PARAM_STR);
                    }
                    if (!empty($user)) {
                        $stmt->bindValue(':user', "%$user%", PDO::PARAM_STR);
                    }

                    foreach ($tags as $key => $tag) {
                        $stmt->bindValue(':tag' . $key, "%$tag%", PDO::PARAM_STR);
                    }

                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    
                    if (empty($results)) {
                        echo "<h1>Sajnos nincsenek találatok.😞</h1>";
                    } else {
                        echo "<table>";
                        echo "  <tr>
                                    <th>ID</th>
                                    <th>Cím</th>
                                    <th>Dátum</th>
                                    <th>Prioritás</th>
                                    <th>Státusz</th>
                                </tr>"; 
                        foreach ($results as $row) {
                            $createdAt = new DateTime($row['created_at']);
                            $relativeTime = getRelativeTime($createdAt); 
                            $status = $row['report_status']; 
                            list($priorityText, $priorityClass) = getPriorityTextAndClass($row['priority']);

                            echo "<tr onclick=\"location.href='details.php?id=" . $row['id'] . "'\">";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . $createdAt->format('Y-m-d H:i') . "</td>";
                            echo "<td class='" . $priorityClass . "'>" . htmlspecialchars($priorityText) . "</td>";
                            echo "<td>" . htmlspecialchars($status) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }
                } catch (PDOException $e) {
                    echo "Hiba történt: " . $e->getMessage();
                }
            ?>
            </div>  
        </div>
    </div>

</body>

<script>
document.getElementById('user').addEventListener('input', function() {
    var userInput = this.value;
    var suggestionBox = document.getElementById('user_suggestions');

    
    if (userInput.length < 2) {
        suggestionBox.innerHTML = '';  
        return;
    }

    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_user.php?user=' + encodeURIComponent(userInput), true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var suggestions = JSON.parse(xhr.responseText);
            displaySuggestions(suggestions);
        }
    };
    xhr.send();
});


function displaySuggestions(suggestions) {
    var suggestionBox = document.getElementById('user_suggestions');
    suggestionBox.innerHTML = '';  

    if (suggestions.length > 0) {
        suggestionBox.style.display = 'block';  
        suggestions.forEach(function(user) {
            var div = document.createElement('div');
            div.className = 'suggestion';
            div.textContent = user.username;
            div.onclick = function() {
                document.getElementById('user').value = user.username;
                suggestionBox.innerHTML = '';  
                suggestionBox.style.display = 'none'; 
            };
            suggestionBox.appendChild(div);
        });
    } else {
        
        suggestionBox.innerHTML = '<div class="no-results">Nincsenek találatok</div>';
        suggestionBox.style.display = 'block';  
    }
}


document.getElementById('user').addEventListener('blur', function() {
    setTimeout(function() {
        document.getElementById('user_suggestions').style.display = 'none';
    }, 200); 
});

</script>
</html>
