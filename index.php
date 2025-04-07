<?php
session_start();

// Voeg een databaseconnectie toe
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
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Controleer of de gebruiker bestaat
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Controleer het wachtwoord
        if (password_verify($password, $user['password']) || $user['password'] === md5($password)) {
            // Sla gebruikersinformatie op in de sessie
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];

            if ($user['is_admin'] == 1) {
                header('Location: admin.php'); // Redirect naar admin dashboard
            } else {
                header('Location: user.php'); // Redirect naar user dashboard
            }
            exit();
        } else {
            $_SESSION['error'] = "Ongeldig wachtwoord.";
        }
    } else {
        $_SESSION['error'] = "E-mailadres niet gevonden.";
    }

    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify Login / Registreer</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container">


    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        ?>
    
    <?php endif; ?>

    <div class="form-box active" id="login-form">
        <form action="index.php" method="post">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit" name="login">Login</button>
            <p>Heb je nog geen account? <a href="#" onclick="showForm('register-form')">Registreer</a></p>
            <p><a href="about.php" class="info-link">Meer weten over Chirpify?</a></p>
        </form>
    </div>

    <div class="form-box" id="register-form">
        <form action="login_register.php" method="post">
            <h2>Registreren</h2>
            <input type="text" name="name" placeholder="Naam" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <select name="role" required>
                <option value="">--Selecteer rol--</option>
                <option value="User">User</option>
            </select>
            <button type="submit" name="register">Registreer</button>
            <p>Heb je al een account? <a href="#" onclick="showForm('login-form')">Login</a></p>
        </form>
    </div>
</div>

<script src="script.js"></script>
<script>
    setTimeout(function() {
        let messages = document.querySelectorAll('.error, .success');
        messages.forEach(function(message) {
            message.style.transition = "opacity 1s";
            message.style.opacity = "0";
            setTimeout(() => message.style.display = "none", 1000);
        });
    }, 3000);
</script>

</body>
</html>
