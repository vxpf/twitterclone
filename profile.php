<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Haal gegevens op uit de sessie
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$bio = $_SESSION['bio'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Profiel</title>
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
            <a href="profile.php" class="nav-item active">Profile</a>
            <a href="Settings.php" class="nav-item">Settings</a>
            <a href="index.php" class="nav-item">Logout</a>
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
                <img src="path/to/default-banner.jpg" alt="Banner afbeelding" class="profile-banner">

                <!-- Profiel foto -->
                <div class="profile-picture">
                    <img src="path/to/default-profile.png" alt="Profiel foto">
                </div>
            </div>

            <!-- Profiel-informatie -->
            <div class="profile-info">
                <h2 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h2>
                <p class="profile-handle">@<?php echo strtolower(str_replace(' ', '', htmlspecialchars($user_name))); ?></p>
                <p class="profile-bio">
                    <?php echo htmlspecialchars($bio ?: "Deze gebruiker heeft nog geen bio."); ?>
                </p>
            </div>
        </section>
            </div>
        </section>
    </main>
</div>
</body>
</html>