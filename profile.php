<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Haal gegevens op uit de sessie
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$role = $_SESSION['role'];
$followers_count = $_SESSION['followers_count'];
$following_count = $_SESSION['following_count'];
$bio = $_SESSION['bio'];
$profile_picture = $_SESSION['profile_picture'];
$banner = $_SESSION['banner'];
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
            <a href="bookmarks.php" class="nav-item">Bookmarks</a>
            <a href="messages.php" class="nav-item">Messages</a>
            <a href="profile.php" class="nav-item active">Profile</a>
            <a href="index.php" class="nav-item">Logout</a>
        </nav>
    </aside>

    <!-- Profielpagina hoofdcontent -->
    <main class="feed profile-main">
        <header class="feed-header">
            <h1>Profiel</h1>
        </header>

        <!-- Profieldetails -->
        <div class="profile-header">
            <div class="profile-banner">
                <img src="<?php echo htmlspecialchars($banner); ?>" alt="Banner">
            </div>
            <div class="profile-info">
                <div class="profile-avatar">
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profielfoto">
                </div>
                <div class="profile-details">
                    <h2 class="profile-name"><?php echo htmlspecialchars($user_name); ?></h2>
                    <span class="profile-handle">@<?php echo strtolower(str_replace(' ', '', htmlspecialchars($user_name))); ?></span>
                    <p class="profile-bio"><?php echo htmlspecialchars($bio ?: "Deze gebruiker heeft nog geen bio."); ?></p>
                    <div class="profile-meta">
                        <span><strong><?php echo $following_count; ?></strong> Following</span>
                        <span><strong><?php echo $followers_count; ?></strong> Followers</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>