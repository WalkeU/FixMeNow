<?php
header('Cache-Control: no cache'); // no cache
session_cache_limiter('private_no_expire'); // works
session_start();

// Ellenőrzés, hogy a kérés POST metódussal történt-e
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Ha nem POST kérés, akkor átirányítás vagy hibaüzenet megjelenítése
    header('Location: index.php'); // Ezt átirányíthatod egy hibaoldalra
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
    <title>Hibabejelentés</title>
    <link rel="stylesheet" href="hibjel.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <div class="navbar">
        <form action="hibjel.php" method="POST">
            <button type="submit" class="navbar-button">
                <i class="fas fa-bug"></i>
                <span>Hiba bejelentése</span>
            </button>
        </form>

        <?php if ($rank !== 1): ?>
            <form action="sajatfeladatok.php" method="POST">
                <button type="submit" class="navbar-button">
                    <i class="fas fa-user-check"></i>
                    <span>Saját Feladatok</span>
                </button>
            </form>
        <?php endif; ?>

        <?php if ($rank == 3): ?>
            <form action="osszesfeladat.php" method="POST">
                <button type="submit" class="navbar-button">
                    <i class="fas fa-tasks"></i>
                    <span>Összes Feladat</span>
                </button>
            </form>
        <?php endif; ?>

        <?php if ($rank == 2): ?>
            <form action="ujfeladatok.php" method="POST">
                <button type="submit" class="navbar-button">
                    <i class="fas fa-envelope"></i>
                    <span>Beérkezett Feladatok</span>
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
    <div class="content">
    <div class="report-container">
        <h1>Hibabejelentés</h1>

        <form action="formhandler.inc.php" method="POST" enctype="multipart/form-data">

            <label for="short_issue">Hiba rövid megnevezése<span style="color: red;">*</span></label>
            <input type="text" id="short_issue" name="short_issue" maxlength="40" required>
            
            <label for="issue">Hiba leírása<span style="color: red;">*</span></label>
            <textarea id="issue" name="issue" required oninput="autoResize(this)" rows="1"></textarea>
            

            <label for="location">Helyszín<span style="color: red;">*</span></label>
            <select id="location" class="location_dropdown" name="location" required onchange="toggleCustomLocation()">
                <option value="" disabled selected>Válasszon helyszínt</option>
                <option value="Egyéb">Egyéb</option>
                <option value="1. terem">1. terem</option>
                <option value="2. terem">2. terem</option>
                <option value="3. terem">3. terem</option>
            </select>
            <input type="text" name="location_custom" placeholder="Ha egyéb, adja meg" id="custom_location" style="display: none;" required>
            
            <label for="Tags">
                Hiba fajtája
                <span class="optional">(opcionális)</span>
            </label>
            <div class="issue-type">
                <button type="button" class="issue-button" data-value="Szoftver" onclick="toggleSelection(this)">Szoftver</button>
                <button type="button" class="issue-button" data-value="Hardver" onclick="toggleSelection(this)">Hardver</button>
                <button type="button" class="issue-button" data-value="Hálózati" onclick="toggleSelection(this)">Hálózati</button>
                <button type="button" class="issue-button" data-value="Egyéb" onclick="toggleSelection(this)">Egyéb</button>
            </div>

            <!-- Rejtett input mező, ahol a kiválasztott kategóriákat tároljuk -->
            <input type="hidden" id="selected_issues" name="issue_type">

            <label for="priority">Feladat prioritása<span style="color: red;">*</span></label>
            <div class="slider-container">
                <input type="range" class="priority-slider" name="priority" min="1" max="5" value="3" oninput="updatePriorityLabel(this.value)" required>
                <span class="priority-value">3</span> <!-- Az alapértelmezett érték, ami a csúszka aktuális értékét mutatja -->
            </div>
            <input type="hidden" id="hidden_priority" name="priority">

            <div class="image-upload-wrapper">
                <label for="images">
                    Kép feltöltése
                    <span class="optional">(opcionális)</span>
                </label>
                <div class="button-container-img">
                    <input type="file" id="images" name="images[]" multiple accept="image/*" onchange="previewImages()" style="display: none;">
                    <button type="button" class="upload-button" onclick="document.getElementById('images').click();">Kép kiválasztása <i class="fa-solid fa-arrow-up-from-bracket"></i></button>
                    <button type="button" class="clear-button" onclick="removeAllImages()" style="display: none;">Összes törlése <i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
            <div class="image-preview" id="image_preview"></div>

            <div class="button-container-send">
                <button class="backbutton" type="button" onclick="window.location.href='dashboard.php'">
                    <i class="fa-solid fa-chevron-left"></i>
                    Mégse
                </button>
                <button type="submit" class="send-button">Küldés <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>

        <script>
        
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        function toggleTags() {
            var dropdown = document.getElementById('tags_dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        function toggleCustomLocation() {
            var locationSelect = document.getElementById('location');
            var customLocationInput = document.getElementById('custom_location');

            if (locationSelect.value === "Egyéb") {
                customLocationInput.style.display = "block";
                customLocationInput.required = true; // Kötelezővé tesszük
            } else {
                customLocationInput.style.display = "none";
                customLocationInput.required = false; // Nem kötelező
            }
        }

        function previewImages() {
            const fileInput = document.getElementById('images');
            const previewContainer = document.getElementById('image_preview');
            const clearButton = document.querySelector('.clear-button'); // A törlés gomb
            previewContainer.innerHTML = ''; // Előnézet tisztítása

            // Ha nincsenek kiválasztott képek, rejtsük el a törlés gombot
            if (fileInput.files.length === 0) {
                clearButton.style.display = 'none';
                return;
            }

            clearButton.style.display = 'block'; // Megjelenítjük a törlés gombot

            for (const file of fileInput.files) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';
                    img.classList.add('image-preview-image');

                    const wrapper = document.createElement('div');
                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                }

                reader.readAsDataURL(file);
            }
        }

        function removeAllImages() {
            const fileInput = document.getElementById('images');
            const clearButton = document.querySelector('.clear-button'); // A törlés gomb
            fileInput.value = ''; // Törli a fájlokat
            document.getElementById('image_preview').innerHTML = ''; // Előnézet törlése
            clearButton.style.display = 'none'; // Rejtsük el a törlés gombot
        }
        
        //tags
        function toggleSelection(button) {
            button.classList.toggle('selected'); // Állapot megváltoztatása (kiválasztott/nem kiválasztott)

            // Kiválasztott elemek összegyűjtése
            let selectedIssues = [];
            document.querySelectorAll('.issue-button.selected').forEach(function(selectedButton) {
                selectedIssues.push(selectedButton.getAttribute('data-value'));
            });

            // Rejtett input mező frissítése a kiválasztott értékekkel
            document.getElementById('selected_issues').value = selectedIssues.join(',');
        }

        function updatePriorityLabel(value) {
            const priorityValueDisplay = document.querySelector('.priority-value');
            const prioritySlider = document.querySelector('.priority-slider');
            const hiddenPriority = document.getElementById('hidden_priority');

            // Az érték kijelzése
            priorityValueDisplay.textContent = value;

            // Színek meghatározása az érték alapján
            let color;
            switch (value) {
                case '1':
                    color = '#4CAF50'; // Zöld - alacsony prioritás
                    break;
                case '2':
                    color = '#8BC34A'; // Világoszöld
                    break;
                case '3':
                    color = '#FFEB3B'; // Sárga - közepes prioritás
                    break;
                case '4':
                    color = '#FFC107'; // Narancssárga
                    break;
                case '5':
                    color = '#F44336'; // Piros - magas prioritás
                    break;
            }

            // Csak a csúszka háttérszínének beállítása
            prioritySlider.style.background = color;

            // A prioritás értékének beállítása a rejtett input mezőben
            hiddenPriority.value = value;
        }

        </script>
    </div>
    </div>
</body>
</html>
