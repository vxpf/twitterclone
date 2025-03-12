<?php
session_start();

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Controleer of tweet_id is meegestuurd
if (!isset($_POST['tweet_id'])) {
    $_SESSION['error'] = "Ongeldig verzoek.";
    header('Location: user.php');
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

$tweet_id = $_POST['tweet_id'];
$user_id = $_SESSION['user_id'];

// Verifieer of de tweet van de ingelogde gebruiker is
$stmt = $conn->prepare("SELECT user_id FROM tweets WHERE id = :tweet_id");
$stmt->execute(['tweet_id' => $tweet_id]);
$tweet = $stmt->fetch();

if ($tweet && $tweet['user_id'] === $user_id) {
    // Voer delete uit
    $deleteStmt = $conn->prepare("DELETE FROM tweets WHERE id = :tweet_id");
    $deleteStmt->execute(['tweet_id' => $tweet_id]);
    $_SESSION['success'] = "Tweet succesvol verwijderd.";
} else {
    $_SESSION['error'] = "Je kunt alleen je eigen tweets verwijderen.";
}

header('Location: user.php');
exit();