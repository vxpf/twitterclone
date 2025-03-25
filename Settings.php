<?php
// Start de sessie
session_start();

// Controleer of de gebruiker is ingelogd
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

$user_id = (int) $_SESSION['user_id']; // Zorg ervoor dat het id een integer is
$message = "";

// Als het formulier verzonden wordt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gegevens uit het formulier ophalen en saniteren
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $bio = htmlspecialchars(trim($_POST['bio']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        // Bouw de `UPDATE`-query progressief op
        $query = "UPDATE users SET name = :name, email = :email, bio = :bio";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':bio' => $bio,
        ];

        // Voeg het wachtwoord toe aan de query als het is ingevuld
        if ($password) {
            $query .= ", password = :password";
            $params[':password'] = $password;
        }

        $query .= " WHERE id = :user_id";
        $params[':user_id'] = $user_id;

        // Voer de query uit
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        // Update de sessievariabelen na een succesvolle update
        $_SESSION['user_name'] = $name;
        $_SESSION['bio'] = $bio;

        // Succesbericht instellen
        $message = "Je instellingen zijn met succes opgeslagen!";
    } catch (PDOException $e) {
        $message = "Er is een fout opgetreden: " . $e->getMessage();
    }
}

// Haal de huidige gegevens van de gebruiker op
$stmt = $conn->prepare("SELECT name, email, bio FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Gebruiker niet gevonden.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Settings</title>
    <link rel="stylesheet" href="user.css">
    <style>
        .message {
            background-color: #d4edda; /* Lichtgroene achtergrond */
            color: #155724; /* Donkergroene tekstkleur */
            border: 1px solid #c3e6cb; /* Rand */
            padding: 10px 15px; /* Ruimte binnenin */
            border-radius: 5px; /* Hoekjes afronden */
            margin-bottom: 20px; /* Ruimte onder het bericht */
            font-family: Arial, sans-serif; /* Net lettertype */
            font-size: 14px; /* Goede leesbaarheid */
            opacity: 1; /* Volledig zichtbaar bij het begin */
            transition: opacity 1s ease; /* Zorgt voor een vloeiende overgang */
        }

        .message.hidden {
            opacity: 0; /* Verberg het bericht (onzichtbaar) */
        }

    </style>
</head>
<body>
<div class="twitter-container">

    <aside class="sidebar">
        <div class="logo">
            <h2>Chirpify</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="user.php" class="nav-item">Home</a>
            <a href="profile.php" class="nav-item">Profile</a>
            <a href="settings.php" class="nav-item active">Settings</a>
            <a href="index.php" class="nav-item">Logout</a>
        </nav>
    </aside>

    <main class="feed">
        <header class="feed-header">
            <h1>Settings</h1>
        </header>
        <div class="profile-edit-form">
            <h2>Account Settings</h2>

            <!-- Het bericht indien aanwezig -->
            <?php if ($message): ?>
                <p id="message" class="message"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>" placeholder="Naam" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" placeholder="email@example.com" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Vertel iets over jezelf"><?= htmlspecialchars($user['bio']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Nieuw Wachtwoord</label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                </div>
                <button type="submit" class="save-profile-btn">Opslaan</button>
                <button type="button" class="cancel-edit-btn" onclick="window.location.href='user.php';">Annuleren</button>
            </form>
        </div>
    </main>
</div>

<script>
    // Verwijder het bericht na 3 seconden
    const messageElement = document.getElementById('message');
    if (messageElement) {
        setTimeout(() => {
            messageElement.classList.add('hidden'); // Voeg de klasse 'hidden' toe
            setTimeout(() => {
                messageElement.remove(); // Verwijder het element na de animatie
            }, 1000); // Wacht op het einde van de fade-out transitie (1 seconde)
        }, 3000); // Wacht 3 seconden voordat de fade-out start
    }
</script>
</body>
</html>