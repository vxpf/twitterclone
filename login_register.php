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
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';

        if (!$name || !$email || !$password || !in_array($role, ['User', 'Admin'])) {
            $_SESSION['error'] = "Ongeldige invoer!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ?");
            $stmt->execute([$email, $name]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['error'] = "Naam of e-mail in gebruik!";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password, $role]);
                $_SESSION['success'] = "Registratie succesvol!";
            }
        }
    }

    if (isset($_POST['login'])) {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION = [
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'role' => $user['role']
            ];
            header("Location: " . ($user['role'] === 'Admin' ? "admin.php" : "user.php"));
            exit();
        } else {
            $_SESSION['error'] = "Ongeldig e-mailadres of wachtwoord!";
        }
    }

    header("Location: index.php");
    exit();
}