<?php
try {
    $conn = new PDO(
        "mysql:host=localhost;dbname=login_system",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $newPassword = md5('adminpassword'); // Gebruik het gewenste wachtwoord
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE is_admin = 1");
    $stmt->execute([$newPassword]);

    if ($stmt->rowCount() > 0) {
        echo "Admin password reset successfully.";
    } else {
        echo "Geen rijen bijgewerkt. Controleer of er een adminaccount bestaat.";
    }
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}
?>