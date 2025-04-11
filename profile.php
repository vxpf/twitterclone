<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Initialize dark mode session variable if not set
if (!isset($_SESSION['dark_mode'])) {
    $_SESSION['dark_mode'] = 0;
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

// Haal tweets van de gebruiker op
$stmt = $conn->prepare("
    SELECT 
        tweets.id AS tweet_id, 
        tweets.content, 
        tweets.image, 
        tweets.created_at, 
        tweets.likes_count, 
        users.id AS user_id, 
        users.name, 
        users.profile_picture
    FROM tweets
    INNER JOIN users ON tweets.user_id = users.id
    WHERE tweets.user_id = :user_id
    ORDER BY tweets.created_at DESC
");
$stmt->execute([':user_id' => $_SESSION['user_id']]);
$tweets = $stmt->fetchAll();

// Tijd-verstreken functie
function tijdVerstreken($timestamp) {
    // Formatteer de datum en tijd in een leesbaar formaat
    return date("d-m-Y H:i", strtotime($timestamp)); // Bijvoorbeeld: 03-04-2025 14:30
}
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
<body class="<?= $_SESSION['dark_mode'] == 1 ? 'dark-mode' : ''; ?>">
<div class="twitter-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>Chirpify</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="user.php" class="nav-item"> <i class="fa-sharp fa-solid fa-house" style="color: #000000;"></i>  Home</a>
            <a href="profile.php" class="nav-item"><i class="fa-sharp fa-solid fa-user" style="color: #000000;"></i>  Profile</a>
            <a href="Settings.php" class="nav-item"><i class="fa-solid fa-gear" style="color: #000000;"></i> Settings</a>
            <a href="index.php" class="nav-item"><i class="fa-sharp fa-solid fa-right-from-bracket" style="color: #030303;"></i> Logout</a>
        </nav>
            
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
                <img src="<?= htmlspecialchars($banner); ?>" alt="" class="profile-banner">
            </div>

            <!-- Profielfoto -->
            <div class="profile-picture">
                <img src="<?= htmlspecialchars($profilePicture); ?>" alt="">
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

        <div class="tweets">
            <?php if (!empty($tweets)): ?>
                <?php foreach ($tweets as $tweet): ?>
                    <div class="tweet">
                        <div class="tweet-avatar">
                            <img src="<?= htmlspecialchars($tweet['profile_picture']); ?>" 
                                 alt="Profile Picture">
                        </div>

                        <div class="tweet-content">
                            <div class="tweet-header">
                                <span class="username"><?= htmlspecialchars($tweet['name']); ?></span>
                                <span class="time"><?= tijdVerstreken($tweet['created_at']); ?></span>
                            </div>
                            <p><?= htmlspecialchars($tweet['content']); ?></p>
                            <?php if (!empty($tweet['image'])): ?>
                                <img src="<?= htmlspecialchars($tweet['image']); ?>" alt="Tweet Image" class="tweet-image">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Geen tweets gevonden.</p>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>