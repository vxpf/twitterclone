<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Controleer of een comment_id is meegestuurd
if (isset($_POST['comment_id'])) {
    $comment_id = (int) $_POST['comment_id']; // Zorg ervoor dat het een integer is
    $user_id = (int) $_SESSION['user_id'];   // Huidige ingelogde gebruiker

    // Verbind met de database
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
    } catch (PDOException $e) {
        die("Connectiefout: " . $e->getMessage());
    }

    // Controleer of de reactie van de huidige gebruiker is
    $stmt = $conn->prepare("SELECT * FROM tweets WHERE id = :comment_id AND user_id = :user_id");
    $stmt->execute([
        ':comment_id' => $comment_id,
        ':user_id' => $user_id,
    ]);

    if ($stmt->rowCount() > 0) {
        // Verwijder de reactie als deze van de gebruiker is
        $deleteStmt = $conn->prepare("DELETE FROM tweets WHERE id = :comment_id");
        $deleteStmt->execute([':comment_id' => $comment_id]);

        // Keer terug naar de feed
        header('Location: user.php');
        exit();
    } else {
        echo "Je hebt geen toestemming om deze reactie te verwijderen.";
    }
} else {
    echo "Ongeldige aanvraag.";
}
?>