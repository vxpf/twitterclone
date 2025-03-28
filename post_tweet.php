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
        $imagePath = $uploadDir . time() . '_' . $imageName;

        // Verplaats de geüploade afbeelding naar de uploads-map
        move_uploaded_file($imageTmpName, $imagePath);
    }

    // Tweet of comment opslaan
    $stmt = $conn->prepare("INSERT INTO tweets (content, user_id, image, parent_tweet_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$content, $user_id, $imagePath, $parent_tweet_id]);

    header('Location: user.php'); // Keer terug naar de feed na het posten
    exit();
} else {
    echo "De tweet kan niet leeg zijn!";
}
?>