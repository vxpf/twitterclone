<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
        }

        button {
            background-color: #1da1f2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1991db;
        }

        .bericht {
            background-color: #f5f8fa;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .bericht button {
            background-color: #ff4d4d;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .bericht button:hover {
            background-color: #cc0000;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Hamburger menu container */
        .menu-container {
            position: fixed; /* Blijft op zijn plaats, zelfs bij scrollen */
            top: 10px;
            right: 10px;
            z-index: 1000; /* Zorgt ervoor dat het menu boven andere inhoud staat */
        }

        /* Hamburger icoon */
        .hamburger-menu {
            font-size: 100px;
            cursor: pointer;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        /* Dropdown menu */
        .menu {
            display: none;
            position: absolute;
            top: 50px; /* Plaats het menu onder het hamburger icoon */
            right: 0;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            border-radius: 5px;
            min-width: 150px; /* Breedte van het menu */
        }

        .menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        .menu a:hover {
            background-color: #ddd;
        }

        /* Container voor de chatbox en inhoud */
        .container {
            padding: 20px;
            margin-top: 20px; /* Zorgt voor ruimte boven de inhoud */
        }
    </style>
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
