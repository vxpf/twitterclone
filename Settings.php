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

$user_id = (int)$_SESSION['user_id']; // Zorg ervoor dat het id een integer is
$message = "";

// Haal huidige sessievariabelen of standaardwaarden op
if (!isset($_SESSION['profile_picture'])) {
    $_SESSION['profile_picture'] = 'path/to/default-profile.png'; // Plaats hier het pad naar een standaardfoto
}

if (!isset($_SESSION['banner'])) {
    $_SESSION['banner'] = 'path/to/default-banner.png'; // Plaats hier het pad naar een standaardbanner
}

// Als het formulier verzonden wordt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gegevens uit het formulier ophalen en saniteren
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $bio = htmlspecialchars(trim($_POST['bio']));
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Uploadfolders (zorg dat deze mappen bestaan met de juiste schrijfrechten)
    $uploadDir = "uploads/";

    // Profielfoto uploaden
    $profilePicture = $_SESSION['profile_picture']; // Begin met de huidige profielfoto
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profilePictureTmp = $_FILES['profile_picture']['tmp_name'];
        $profilePictureName = "profile_" . time() . "_" . basename($_FILES['profile_picture']['name']);
        $profilePicturePath = $uploadDir . $profilePictureName;

        if (move_uploaded_file($profilePictureTmp, $profilePicturePath)) {
            $profilePicture = $profilePicturePath; // Alleen opslaan als de upload succesvol was
        }
    }

    // Banner uploaden
    $banner = $_SESSION['banner']; // Begin met de huidige banner
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $bannerTmp = $_FILES['banner']['tmp_name'];
        $bannerName = "banner_" . time() . "_" . basename($_FILES['banner']['name']);
        $bannerPath = $uploadDir . $bannerName;

        if (move_uploaded_file($bannerTmp, $bannerPath)) {
            $banner = $bannerPath; // Alleen opslaan als de upload succesvol was
        }
    }

    try {
        // Bouw de `UPDATE`-query op
        $query = "UPDATE users SET name = :name, email = :email, bio = :bio";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':bio' => $bio,
        ];

        // Voeg de profielfoto toe aan de query als die werd gewijzigd
        if ($profilePicture !== $_SESSION['profile_picture']) {
            $query .= ", profile_picture = :profile_picture";
            $params[':profile_picture'] = $profilePicture;
        }

        // Voeg de banner toe aan de query als die werd gewijzigd
        if ($banner !== $_SESSION['banner']) {
            $query .= ", banner = :banner";
            $params[':banner'] = $banner;
        }

        // Voeg het wachtwoord toe aan de query als die is ingevuld
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
        $_SESSION['profile_picture'] = $profilePicture;
        $_SESSION['banner'] = $banner;

        // Succesbericht
        $message = "Je instellingen zijn met succes opgeslagen!";
    } catch (PDOException $e) {
        $message = "Er is een fout opgetreden: " . $e->getMessage();
    }
}

// Haal de huidige gegevens van de gebruiker op
$stmt = $conn->prepare("SELECT name, email, bio, profile_picture, banner FROM users WHERE id = :user_id");
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            opacity: 1;
            transition: opacity 1s ease;
        }

        .message.hidden {
            opacity: 0;
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
            <a href="user.php" class="nav-item"> <i class="fa-sharp fa-solid fa-house" style="color: #000000;"></i>  Home</a>
            <a href="profile.php" class="nav-item active"><i class="fa-sharp fa-solid fa-user" style="color: #000000;"></i>  Profile</a>
            <a href="settings.php" class="nav-item"><i class="fa-solid fa-gear" style="color: #000000;"></i> Settings</a>
            <a href="index.php" class="nav-item"><i class="fa-sharp fa-solid fa-right-from-bracket" style="color: #030303;"></i>Logout</a>
        </nav>
    </aside>

    <main class="feed">
        <header class="feed-header">
            <h1>Settings</h1>
        </header>
        <div class="profile-edit-form">
            <h2>Account Settings</h2>

            <?php if ($message): ?>
                <p id="message" class="message"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Naam</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio"><?= htmlspecialchars($user['bio']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Nieuw Wachtwoord</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profielfoto</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="<?= htmlspecialchars($user['profile_picture']); ?>" alt="Profielfoto" style="width: 100px;">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="banner">Banner</label>
                    <input type="file" id="banner" name="banner" accept="image/*">
                    <?php if (!empty($user['banner'])): ?>
                        <img src="<?= htmlspecialchars($user['banner']); ?>" alt="Banner" style="width: 100%; max-width: 300px;">
                    <?php endif; ?>
                </div>
                <button type="submit" class="save-profile-btn">Opslaan</button>
                <button type="button" class="cancel-edit-btn" onclick="window.location.href='user.php';">Annuleren</button>
            </form>
        </div>
    </main>
</div>
<script>
    const messageElement = document.getElementById('message');
    if (messageElement) {
        setTimeout(() => {
            messageElement.classList.add('hidden');
            setTimeout(() => {
                messageElement.remove();
            }, 1000);
        }, 3000);
    }
</script>
</body>
</html>