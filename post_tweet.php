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
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($content)) {
    $stmt = $conn->prepare("INSERT INTO tweets (user_id, content) VALUES (?, ?)");
    $stmt->execute([$user_id, $content]);
    $_SESSION['success'] = "Tweet geplaatst!";
    header('Location: user.php');
    exit();
} else {
    $_SESSION['error'] = "Je tweet mag niet leeg zijn!";
    header('Location: user.php');
    exit();
}