<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Database koppeling
try {
    $conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

// Haal gebruikersgegevens op
$stmt = $conn->prepare("SELECT name, bio, profile_picture, banner FROM users WHERE id = :user_id");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

// Controleer of de gebruiker is gevonden
if (!$user) {
    die("Gebruiker niet gevonden.");
}

// Gebruikersgegevens uit $user array halen
$name = !empty($user['name']) ? $user['name'] : "Onbekende Gebruiker"; // Fallback naam
$bio = !empty($user['bio']) ? $user['bio'] : ""; // Fallback bio

// Standaardwaarden voor profielfoto en banner instellen
$profilePicture = !empty($user['profile_picture']) ? $user['profile_picture'] : "path/to/default-profile.png";
$banner = !empty($user['banner']) ? $user['banner'] : "path/to/default-banner.jpg";
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Profiel</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="twitter-container">

    <!-- Sidebar -->
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

    <!-- Content -->
    <main class="feed profile-main">
        <header class="feed-header">
            <h1>Profiel</h1>
        </header>

        <section class="profile-header">
            <!-- Banner -->
            <div class="profile-banner-container">
                <img src="<?= htmlspecialchars($banner); ?>" alt="Banner afbeelding" class="profile-banner">
            </div>

            <!-- Profielfoto -->
            <div class="profile-picture">
                <img src="<?= htmlspecialchars($profilePicture); ?>" alt="Profielfoto">
            </div>

            <!-- Profiel informatie -->
            <div class="profile-info">
                <h2 class="profile-name">
                    <?= htmlspecialchars($name); ?>
                    <a href="settings.php" class="edit-profile-link">
                        <i class="fa-solid fa-pen" style="color: #000000;"></i>
                    </a>
                </h2>
                <p class="profile-handle">@<?= htmlspecialchars(strtolower(str_replace(' ', '', $name))); ?></p>
                <p class="profile-bio"><?= htmlspecialchars(!empty($bio) ? $bio : "Deze gebruiker heeft nog geen bio."); ?></p>
            </div>
        </section>
    </main>
</div>
</body>
</html>