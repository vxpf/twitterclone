<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

try {
    $conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'];
$tweet_id = isset($_POST['tweet_id']) ? (int)$_POST['tweet_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tweet_id) {
    // Controleer of de gebruiker de tweet al heeft geliket
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?");
    $stmt->execute([$user_id, $tweet_id]);

    if ($stmt->rowCount() > 0) {
        // Als de gebruiker al eerder heeft geliket, geef een foutmelding

    } else {
        // Voeg een nieuwe like toe in de 'likes'-tabel
        $stmt = $conn->prepare("INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $tweet_id]);

        // Verhoog het aantal likes in de 'tweets'-tabel
        $stmt = $conn->prepare("UPDATE tweets SET likes_count = likes_count + 1 WHERE id = ?");
        $stmt->execute([$tweet_id]);


    }

    header('Location: user.php');
    exit();
} else {
    $_SESSION['error'] = "Ongeldige aanvraag.";
    header('Location: user.php');
    exit();
}