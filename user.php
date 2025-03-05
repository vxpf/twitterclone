<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Hamburger menu rechtsboven -->
    <div class="menu-container">
        <div class="hamburger-menu" onclick="toggleMenu()">
            &#9776; <!-- Hamburger icon -->
        </div>
        <div class="menu" id="menu">
            <a href="index.php">Uitloggen</a>
            <!-- Voeg hier later meer menu items toe -->
        </div>
    </div>

    <!-- Chatbox en inhoud -->
    <div class="container">
        <h1>Mijn Twitter</h1>
        <textarea id="berichtInput" placeholder="Schrijf je bericht..."></textarea>
        <button onclick="postBericht()">Post Bericht</button>
        <div id="berichtenLijst"></div>
    </div>


    <script src="script.js"></script>
</body>
</html>
