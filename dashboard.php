<?php
header('Cache-Control: no cache'); 
session_cache_limiter('private_no_expire'); 
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$rank = isset($_SESSION['rank']) ? $_SESSION['rank'] : null;
$formattedUsername = $_SESSION['formatted_username'];
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üdvözöljük!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/png" href="images/logo.png">
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

    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Üdvözöljük, <?php echo htmlspecialchars($formattedUsername); ?>! </h1>
        </header>
        <nav class="button-container">
            <form action="hibjel.php" method="POST">
                <button type="submit" class="button-card">
                    <i class="fas fa-bug"></i>
                    <span>Hiba bejelentése</span>
                </button>
            </form>

            <?php if ($rank !== 1): ?>
                <form action="sajatfeladatok.php" method="POST">
                    <button type="submit" class="button-card">
                        <i class="fas fa-user-check"></i>
                        <span>Saját Feladatok</span>
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($rank == 3): ?>
                <form action="osszesfeladat.php" method="POST">
                    <button type="submit" class="button-card">
                        <i class="fas fa-tasks"></i>
                        <span>Összes Feladat</span>
                    </button>
                </form>
            <?php endif; ?>

            <?php if ($rank == 2): ?>
                <form action="ujfeladatok.php" method="POST">
                    <button type="submit" class="button-card">
                        <i class="fas fa-envelope"></i>
                        <span>Beérkezett Feladatok</span>
                    </button>
                </form>
            <?php endif; ?>

            <form action="logout.php" method="POST">
                <button type="submit" class="button-card">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Kijelentkezés</span>
                </button>
            </form>
        </nav>
    </div>
</body>

</html>