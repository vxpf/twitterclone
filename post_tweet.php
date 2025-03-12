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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && !empty(trim($_POST['content']))) {
    $tweet_content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    // Voeg tweet toe aan database
    $stmt = $conn->prepare("INSERT INTO tweets (user_id, content, created_at) VALUES (:user_id, :content, NOW())");
    $stmt->execute([
        'user_id' => $user_id,
        'content' => $tweet_content
    ]);
}

header('Location: user.php');
exit();