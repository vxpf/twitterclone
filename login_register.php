<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login_system");

if ($conn->connect_error) {
    die("Connectiefout: " . $conn->connect_error);
}

// **Registreren**
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "Vul alle velden in!";
        header("Location: index.php");
        exit();
    }

    if ($role != 'User' && $role != 'Admin') {
        $_SESSION['error'] = "Ongeldige rol geselecteerd!";
        header("Location: index.php");
        exit();
    }

    // Controleer of naam of e-mail al bestaat
    $check_user = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ?");
    $check_user->bind_param("ss", $email, $name);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        $_SESSION['error'] = "Deze naam of e-mail is al in gebruik!";
        header("Location: index.php");
        exit();
    }

    // Wachtwoord hash maken en gebruiker opslaan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registratie succesvol! Je kunt nu inloggen.";
    } else {
        $_SESSION['error'] = "Er ging iets mis tijdens het registreren.";
    }

    $stmt->close();
    header("Location: index.php");
    exit();
}

// **Inloggen**
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'Admin') {
            header("Location: admin.php");
        } else {
            header("Location: user.php");
        }
    } else {
        $_SESSION['error'] = "Ongeldig e-mailadres of wachtwoord!";
        header("Location: index.php");
    }

    $stmt->close();
    exit();
}

$conn->close();
?>
