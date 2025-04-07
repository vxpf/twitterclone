<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Over Chirpify</title>
    <link rel="stylesheet" href="index.css">
    <style>
        /* Algemene stijlen voor de About-pagina */
        body {
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
            font-family: 'Poppins', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .about-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .about-container h1 {
            font-size: 42px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .about-container p {
            font-size: 18px;
            color: #555;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .about-container ul {
            text-align: left;
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }

        .about-container ul li {
            font-size: 18px;
            color: #555;
            margin-bottom: 15px;
            padding-left: 40px;
            position: relative;
        }

        .about-container ul li::before {
            content: "✔";
            color: #7494ec;
            position: absolute;
            left: 0;
            top: 0;
            font-size: 20px;
        }

        .screenshots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .screenshots img {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .screenshots img:hover {
            transform: scale(1.1);
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.3);
        }

        .cta-section {
            margin-top: 40px;
            padding: 20px;
            background: #7494ec;
            color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-section h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .cta-section p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .cta-section a {
            display: inline-block;
            padding: 12px 25px;
            background-color: #fff;
            color: #7494ec;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cta-section a:hover {
            background-color: #6884d3;
            color: #fff;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 25px;
            background-color: #7494ec;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #6884d3;
        }
    </style>
</head>
<body>
<div class="about-container">
    <h1>Welkom bij Chirpify</h1>
    <p>Chirpify is een sociaal mediaplatform waar je berichten kunt plaatsen, liken, retweeten en reageren op berichten van anderen. 
       Ons doel is om mensen te verbinden en hen een platform te bieden om hun gedachten en ideeën te delen.</p>
    
    <h2>Waarom kiezen voor Chirpify?</h2>
    <ul>
        <li><strong>Berichten plaatsen:</strong> Deel je gedachten met de wereld.</li>
        <li><strong>Likes:</strong> Laat anderen weten dat je hun berichten waardeert.</li>
        <li><strong>Retweets:</strong> Deel berichten van anderen met je eigen volgers.</li>
        <li><strong>Reacties:</strong> Ga in gesprek met anderen door te reageren op hun berichten.</li>
    </ul>

    <h2>Screenshots</h2>
    <div class="screenshots">
        <img src="Schermafbeelding 2025-04-03 134522.png" alt="Screenshot 1 - Homepagina">
        <img src="Schermafbeelding 2025-04-03 134535.png" alt="Screenshot 2 - Profielpagina">
        <img src="Schermafbeelding 2025-04-03 134559.png" alt="Screenshot 3 - Instellingenpagina">
        <img src="Schermafbeelding 2025-04-03 134624.png" alt="Screenshot 4 - Reacties op een tweet">
    </div>

    <div class="cta-section">
        <h2>Word vandaag nog lid van Chirpify!</h2>
        <p>Sluit je aan bij onze community en begin met het delen van je gedachten en ideeën.</p>
        <a href="index.php">Registreer nu</a>
    </div>

    <a href="index.php" class="back-link">Terug naar de hoofdpagina</a>
</div>
</body>
</html>