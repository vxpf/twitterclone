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
    // Check if the user has already liked this tweet
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?");
    $stmt->execute([$user_id, $tweet_id]);

    if ($stmt->rowCount() > 0) {
        // If already liked, remove the like
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND tweet_id = ?");
        $stmt->execute([$user_id, $tweet_id]);

        // Decrease the like count in the original tweet
        $stmt = $conn->prepare("UPDATE tweets SET likes_count = likes_count - 1 WHERE id = ?");
        $stmt->execute([$tweet_id]);
    } else {
        // Add the like to the likes table
        $stmt = $conn->prepare("INSERT INTO likes (user_id, tweet_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $tweet_id]);

        // Increase the like count in the original tweet
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
?>