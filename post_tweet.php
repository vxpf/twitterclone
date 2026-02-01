<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Database connectie
try {
    $conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

// Controleer of er een inhoud is
if (isset($_POST['content']) && !empty($_POST['content'])) {
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $parent_tweet_id = !empty($_POST['parent_tweet_id']) ? $_POST['parent_tweet_id'] : null; // Voor comments

    // Controleer of er een afbeelding is geüpload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/'; // Zorg ervoor dat deze map bestaat en schrijfbaar is

        // Controleer of de map bestaat, zo niet, probeer deze aan te maken
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) { // 0777 is vaak nodig, pas aan indien mogelijk voor betere beveiliging
                die("Kon de upload directory niet aanmaken: " . $uploadDir);
            }
        }

        // Controleer of de map schrijfbaar is
        if (!is_writable($uploadDir)) {
            die("De upload directory is niet schrijfbaar: " . $uploadDir);
        }

        $imagePath = $uploadDir . time() . '_' . $imageName;

        // Verplaats de geüploade afbeelding naar de uploads-map en controleer het resultaat
        if (!move_uploaded_file($imageTmpName, $imagePath)) {
             // Voeg meer gedetailleerde foutinformatie toe indien nodig
            error_log("Fout bij het verplaatsen van geüpload bestand: " . $_FILES['image']['error']); 
            die("Er is een fout opgetreden bij het uploaden van de afbeelding.");
            $imagePath = null; // Zet pad terug naar null als upload mislukt
        }
    }

    // Tweet of comment opslaan
    $stmt = $conn->prepare("INSERT INTO tweets (content, user_id, image, parent_tweet_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$content, $user_id, $imagePath, $parent_tweet_id]);

    header('Location: user.php'); // Keer terug naar de feed na het posten
    exit();
} else {
    // Geef een meer gebruikersvriendelijke melding of redirect met een foutmelding
    $_SESSION['error_message'] = "De tweet kan niet leeg zijn!";
    header('Location: user.php'); // Of waar de gebruiker vandaan kwam
    exit();
    // echo "De tweet kan niet leeg zijn!";
}
?>