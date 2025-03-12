<?php
session_start();

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Controleer of tweet_id is meegestuurd
if (!isset($_POST['tweet_id']) || empty($_POST['tweet_id'])) {
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

$tweet_id = (int) $_POST['tweet_id']; // Maak zeker dat tweet_id een integer is
$user_id = (int) $_SESSION['user_id']; // Zorg dat user_id een integer is

// Verifieer of de tweet van de ingelogde gebruiker is
$stmt = $conn->prepare("SELECT id FROM tweets WHERE id = :tweet_id AND user_id = :user_id");
$stmt->execute(['tweet_id' => $tweet_id, 'user_id' => $user_id]);
$tweet = $stmt->fetch();

if ($tweet) {
    // Verwijder likes en retweets gekoppeld aan de tweet
    $conn->prepare("DELETE FROM likes WHERE tweet_id = :tweet_id")->execute(['tweet_id' => $tweet_id]);
    $conn->prepare("DELETE FROM retweets WHERE tweet_id = :tweet_id")->execute(['tweet_id' => $tweet_id]);

    // Verwijder de tweet zelf
    $conn->prepare("DELETE FROM tweets WHERE id = :tweet_id")->execute(['tweet_id' => $tweet_id]);
}

header('Location: user.php');
exit();