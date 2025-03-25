<?php
header('Cache-Control: no cache'); 
session_cache_limiter('private_no_expire'); 
session_start();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    
    header('Location: index.php'); 
    exit();
}

if (!isset($_SESSION['username']) && (!isset($_SESSION['user_id']))) {
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
    <title>Hibabejelentés</title>
    <link rel="stylesheet" href="hibjel.css">
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

    <div class="content">
    <div class="report-container">
        <h1>Hibabejelentés</h1>

        <form id="uploadForm" action="formhandler.inc.php" method="POST" enctype="multipart/form-data">

            <label for="short_issue" class="short_issue">
            Hiba rövid megnevezése<span style="color: red;">*</span>
            </label>
            <input type="text" id="short_issue" name="short_issue" maxlength="40" required autocomplete="off" class="short_issue">
            
            <label for="issue" class="issue">Hiba leírása<span style="color: red;">*</span></label>
            <textarea id="issue" name="issue" required oninput="autoResize(this)" rows="1"></textarea>
            

            <label for="location" class="location">Helyszín<span style="color: red;">*</span></label>
            <select id="location" class="location_dropdown" name="location" required onchange="toggleCustomLocation()">
                <option value="" disabled selected>Válasszon helyszínt</option>
            </select>
            <input type="text" name="location_custom" placeholder="Ha egyéb, adja meg" id="custom_location"
            style="display: none;" required autocomplete="off">
            
            <label for="Tags">
                Hiba fajtája<span style="color: red;">*</span>
            </label>
            <div class="issue-type">    
                <button type="button" class="issue-button" data-value="Szoftver" onclick="toggleSelection(this)">Szoftver</button>
                <button type="button" class="issue-button" data-value="Hardver" onclick="toggleSelection(this)">Hardver</button>
                <button type="button" class="issue-button" data-value="Hálózati" onclick="toggleSelection(this)">Hálózati</button>
                <button type="button" class="issue-button" data-value="Egyéb" onclick="toggleSelection(this)">Egyéb</button>
            </div>

            <!-- Rejtett input mező, ahol a kiválasztott kategóriákat tároljuk -->
            <input type="hidden" id="selected_issues" name="issue_type">

            <label for="priority" class="priority-label">Feladat prioritása<span style="color: red;">*</span></label>
            <div class="slider-container">
                <input type="range" class="priority-slider" name="priority" min="1" max="5" value="3" oninput="updatePriorityLabel(this.value)" required>
                <span class="priority-value">3</span> <!-- Az alapértelmezett érték, ami a csúszka aktuális értékét mutatja -->
            </div>
            <input type="hidden" id="hidden_priority" name="priority">

            <div class="upload-container">
                <label for="images" class="upload-label">
                    Kép feltöltése
                    <span class="optional">(opcionális)</span>
                </label>
                <button type="button" class="upload-button" onclick="document.getElementById('images').click();">
                    Képek kiválasztása
                    <i class="fa fa-cloud-arrow-up"></i>
                </button>
            </div>
            <input type="file" name="images[]" id="images" multiple style="display: none;" onchange="showFileNames()">

            <!-- Rejtett input a kiválasztott fájlok átadásához -->
            <input type="hidden" name="fileNames" id="fileNames" value="">

            <!-- Kiválasztott fájlok listázása -->
            <ul id="fileList"></ul>

            <!-- Előnézeti képek -->
            <div class="preview" id="imagePreview"></div>

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

        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_locations.php')
                .then(response => response.json())
                .then(data => {
                    let locationDropdown = document.getElementById('location');

                    
                    let otherOption = document.createElement('option');
                    otherOption.value = "Egyéb";
                    otherOption.textContent = "Egyéb";
                    locationDropdown.appendChild(otherOption);

                    if (data.error) {
                        console.error(data.error);
                    } else {
                        data.forEach(location => {
                            let option = document.createElement('option');
                            option.value = location;
                            option.textContent = location;
                            locationDropdown.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Hiba a helyszínek betöltésekor:', error));
        });

        function toggleTags() {
            var dropdown = document.getElementById('tags_dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        function toggleCustomLocation() {
            var locationSelect = document.getElementById('location');
            var customLocationInput = document.getElementById('custom_location');

            if (locationSelect.value === "Egyéb") {
                customLocationInput.style.display = "block";
                customLocationInput.required = true; 
            } else {
                customLocationInput.style.display = "none";
                customLocationInput.required = false; 
            }
        }

        
        
        function toggleSelection(button) {
            button.classList.toggle('selected'); 

            
            let selectedIssues = [];
            document.querySelectorAll('.issue-button.selected').forEach(function(selectedButton) {
                selectedIssues.push(selectedButton.getAttribute('data-value'));
            });

            
            document.getElementById('selected_issues').value = selectedIssues.join(',');
        }

        function updatePriorityLabel(value) {
            const priorityValueDisplay = document.querySelector('.priority-value');
            const prioritySlider = document.querySelector('.priority-slider');
            const hiddenPriority = document.getElementById('hidden_priority');

            
            priorityValueDisplay.textContent = value;

            
            let color;
            switch (value) {
                case '1':
                    color = '#4CAF50'; 
                    break;
                case '2':
                    color = '#8BC34A'; 
                    break;
                case '3':
                    color = '#FFEB3B'; 
                    break;
                case '4':
                    color = '#FFC107'; 
                    break;
                case '5':
                    color = '#F44336'; 
                    break;
            }

            
            prioritySlider.style.background = color;

            
            hiddenPriority.value = value;
        }



        const imageInput = document.getElementById('images');
        const fileList = document.getElementById('fileList');
        const fileNamesInput = document.getElementById('fileNames');
        const imagePreview = document.getElementById('imagePreview');

        let selectedFiles = [];

        
        imageInput.addEventListener('change', function() {
            
            for (let i = 0; i < imageInput.files.length; i++) {
                const file = imageInput.files[i];
                const fileName = file.name;

                
                if (!selectedFiles.some(f => f.name === fileName)) {
                    selectedFiles.push(file);
                }
            }

            
            updateFileList();
            updateImagePreview();
        });

        
        function updateFileList() {
            fileList.innerHTML = '';  
            selectedFiles.forEach(file => {
                const li = document.createElement('li');
                li.textContent = file.name;
                fileList.appendChild(li);
            });

            
            fileNamesInput.value = selectedFiles.map(file => file.name).join(',');
        }

        
        function updateImagePreview() {
            imagePreview.innerHTML = '';  
            selectedFiles.forEach(file => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;

                    
                    const removeButton = document.createElement('button');
                    removeButton.textContent = '×';
                    removeButton.classList.add('remove-btn');
                    removeButton.addEventListener('click', function() {
                        removeFile(file);  
                    });

                    const imgWrapper = document.createElement('div');
                    imgWrapper.style.position = 'relative';
                    imgWrapper.appendChild(imgElement);
                    imgWrapper.appendChild(removeButton);

                    imagePreview.appendChild(imgWrapper);
                };

                reader.readAsDataURL(file);
            });
        }

        
        function removeFile(file) {
            selectedFiles = selectedFiles.filter(f => f.name !== file.name);
            updateFileList();
            updateImagePreview();
        }

        
        const form = document.getElementById('uploadForm');
        form.addEventListener('submit', function(event) {
            
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageInput.files = dataTransfer.files;
        });



        </script>
    </div>
    </div>
</body>
</html>
