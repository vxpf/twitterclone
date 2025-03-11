<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Haal gegevens op uit de sessie
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$role = $_SESSION['role'];

// Connectie met database voorbereiden
try {
    $conn = new PDO("mysql:host=localhost;dbname=login_system", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Haal de extra profielinformatie op uit de database met gebruikers-ID
    $stmt = $conn->prepare("SELECT email, role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = 'Gebruikersgegevens niet gevonden!';
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    die("Connectiefout: " . $e->getMessage());
}

// Verkrijg aanvullende gebruikersinfo
$email = $user['email'];
$role = $user['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Profile</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
<div class="twitter-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>Chirpify</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="user.php" class="nav-item">Home</a>
            <a href="bookmarks.php" class="nav-item">Bookmarks</a>
            <a href="messages.php" class="nav-item">Messages</a>
            <a href="profile.php" class="nav-item active">Profile</a>
            <a href="index.php" class="nav-item">Logout</a>
        </nav>
        <button class="tweet-btn">Tweet</button>
    </aside>

    <!-- Profile Main Content -->
    <main class="feed profile-main">
        <header class="feed-header">
            <h1>Profile</h1>
        </header>

        <!-- Profile Header Section -->
        <div class="profile-header">
            <div class="profile-banner">
                <img src="banner.jpg" alt="Banner">
            </div>
            <div class="profile-info">
                <div class="profile-avatar">
                    <img src="randompersoon.jpg" alt="Profile Picture">
                </div>
                <button class="edit-profile-btn">Edit profile</button>
                <div class="profile-details">
                    <!-- Dynamische gegevens ophalen -->
                    <h2 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h2>
                    <span class="profile-handle">@<?php echo strtolower(str_replace(' ', '', htmlspecialchars($user_name))); ?></span>
                    <p class="profile-bio">Web developer | Tech enthusiast | Coffee lover</p>
                    <div class="profile-meta">
                        <span><strong>250</strong> Following</span>
                        <span><strong>180</strong> Followers</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
