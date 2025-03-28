<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tweet_id'])) {
    try {
        $conn = new PDO(
            "mysql:host=localhost;dbname=login_system",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Controleer of de tweet van de ingelogde gebruiker is
        $stmt = $conn->prepare("SELECT user_id FROM tweets WHERE id = ? AND parent_tweet_id IS NULL");
        $stmt->execute([$_POST['tweet_id']]);
        $tweet = $stmt->fetch();

        if ($tweet && $tweet['user_id'] === $_SESSION['user_id']) {
            // Verwijder de tweet en alle bijbehorende reacties
            $deleteStmt = $conn->prepare("DELETE FROM tweets WHERE parent_tweet_id = ? OR id = ?");
            $deleteStmt->execute([$_POST['tweet_id'], $_POST['tweet_id']]);
            header('Location: user.php'); // Keer terug naar de feed
            exit();
        } else {
            die("Je hebt geen rechten om deze tweet te verwijderen.");
        }
    } catch (PDOException $e) {
        die("Fout bij connectie: " . $e->getMessage());
    }
} else {
    die("Ongeldig verzoek.");
}