<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="login-container">
        <?php
        session_start();
        $message = "Belépés";
        $error = false;

        if (isset($_SESSION['error'])) {
            $message = $_SESSION['error'];
            $error = true;
            unset($_SESSION['error']);
        }
        ?>

        <div class="header">
            <img src="images/jlogo.png" alt="Logo" class="logo">
            <h2 class="<?php echo $error ? 'error-header' : ''; ?>"><?php echo $message; ?></h2>
        </div>

        <form action="login_handler.php" method="POST">
            <input type="text" placeholder="Felhasználónév" id="username" name="username" required>
            
            <input type="password" placeholder="Jelszó" id="password" name="password" required>
            
            <button type="submit">Belépés</button>
        </form>
    </div>
</body>
</html>

<!-- Fireworks.js könyvtár -->
<script src="https:

<!-- Fireworks.js könyvtár -->
<script src="https:

<script>
    
    const container = document.createElement('div');
    container.style.position = 'fixed';
    container.style.top = '0';
    container.style.left = '0';
    container.style.width = '100%';
    container.style.height = '100%';
    container.style.pointerEvents = 'none';
    document.body.appendChild(container);

    const fireworks = new Fireworks.default(container, { 
        speed: 2,
        acceleration: 1.05,
        friction: 0.98,
        gravity: 1.5,
        particles: 100,
        traceLength: 3,
        traceSpeed: 5,
        explosion: 6,
        intensity: 30,
        flicker: false,
        hue: { min: 0, max: 360 },
        brightness: { min: 50, max: 80 },
        decay: { min: 0.015, max: 0.03 }
    });

    let isFireworksRunning = false;

    
    function startFireworks() {
        if (!isFireworksRunning) {  
            fireworks.start();
            isFireworksRunning = true;
        }
    }

    
    function stopFireworks() {
        if (isFireworksRunning) {  
            fireworks.stop();
            isFireworksRunning = false;
        }
    }

    
    const inputField = document.getElementById('username');  
    inputField.addEventListener('input', function() {
        if (inputField.value.toLowerCase() === 'bumm') {
            startFireworks();  
        } else {
            stopFireworks();  
        }
    });
</script>
