<?php
session_start();

try {
    $conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (isset($_POST['register'])) {
        // Registratie
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';

        if (!$name || !$email || !$password || $role !== 'User') {
            $_SESSION['error'] = "Ongeldige invoer!";
        } else {
            // Controleer of de e-mail of naam al bestaat
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ?");
            $stmt->execute([$email, $name]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "Naam of e-mail in gebruik!";
            } else {
                // Hash het wachtwoord
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Voeg de gebruiker toe aan de database
                $stmt = $conn->prepare("
                    INSERT INTO users (name, email, password, role) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$name, $email, $hashed_password, $role]);
                $_SESSION['success'] = "Registratie succesvol!";
            }
        }
    }

    header("Location: index.php");
    exit();
}
?>