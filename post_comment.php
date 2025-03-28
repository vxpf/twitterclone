<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['comment'], $_POST['tweet_id'])) {
    $content = trim($_POST['comment']);
    $parent_tweet_id = (int) $_POST['tweet_id'];
    $user_id = $_SESSION['user_id'];

    if (!empty($content)) {
        try {
            $conn = new PDO(
                "mysql:host=localhost;dbname=login_system",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            $stmt = $conn->prepare("
                INSERT INTO tweets (user_id, content, parent_tweet_id, created_at) 
                VALUES (:user_id, :content, :parent_tweet_id, NOW())
            ");

            $stmt->execute([
                ':user_id' => $user_id,
                ':content' => $content,
                ':parent_tweet_id' => $parent_tweet_id,
            ]);

            header('Location: user.php');
            exit();
        } catch (PDOException $e) {
            die("Connectiefout: " . $e->getMessage());
        }
    }
}

header('Location: user.php');
exit();