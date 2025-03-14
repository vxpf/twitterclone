<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Stuur gebruiker naar de login-pagina als hij niet is ingelogd
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

// Haal tweets en gebruikers op
$stmt = $conn->prepare("
    SELECT tweets.id AS tweet_id, tweets.content, tweets.created_at, tweets.likes_count, tweets.retweets_count,
           users.id AS user_id, users.name, users.profile_picture
    FROM tweets
    INNER JOIN users ON tweets.user_id = users.id
    ORDER BY tweets.created_at DESC
");
$stmt->execute();
$tweets = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Feed</title>
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
            <a href="user.php" class="nav-item active">Home</a>
            <a href="bookmarks.php" class="nav-item">Bookmarks</a>
            <a href="profile.php" class="nav-item">Profile</a>
            <a href="Settings.php" class="nav-item">Settings</a>
            <a href="index.php" class="nav-item">Logout</a>
        </nav>
    </aside>

    <!-- Main Feed Section -->
    <main class="feed">
        <!-- Titel van de Feed -->
        <header class="feed-header">
            <h1>Home</h1>
        </header>

        <!-- Tweet-invoerveld -->
        <div class="tweet-box">
            <form action="post_tweet.php" method="POST">
                <textarea name="content" placeholder="Wat gebeurt er?" rows="4" required></textarea>
                <button type="submit" class="post-btn">Chirpitweet</button>
            </form>
        </div>

        <!-- Tweets Weergave -->
        <div class="tweets">
            <?php if (!empty($tweets)): ?>
                <?php foreach ($tweets as $tweet): ?>
                    <div class="tweet">
                        <!-- Gebruikersavatar -->
                        <div class="tweet-avatar">
                            <img src="<?php echo htmlspecialchars($tweet['profile_picture']); ?>" alt="User Avatar">
                        </div>
                        <div class="tweet-content">
                            <!-- Tweet Header -->
                            <div class="tweet-header">
                                <span class="username"><?php echo htmlspecialchars($tweet['name']); ?></span>
                                <span class="handle">@<?php echo htmlspecialchars($tweet['user_id']); ?></span>
                                <span class="time">• <?php echo date("H:i", strtotime($tweet['created_at'])); ?></span>
                            </div>
                            <!-- Tweet Tekst -->
                            <p><?php echo htmlspecialchars($tweet['content']); ?></p>
                            <!-- Tweet Acties -->
                            <div class="tweet-actions">
                                <!-- Like Formulier -->
                                <form action="like_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    // Controleer of gebruiker de tweet al heeft geliket
                                    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isLiked = $stmt->rowCount() > 0; // True als al geliket
                                    ?>
                                    <button type="submit" class="like-btn">
                                        <?php echo $isLiked ? "💔 Unlike" : "❤️ Like"; ?> (<?php echo (int)$tweet['likes_count']; ?>)
                                    </button>
                                </form>
                                <!-- Retweet Formulier -->
                                <form action="retweet_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    // Controleer of gebruiker de tweet al heeft geretweet
                                    $stmt = $conn->prepare("SELECT * FROM retweets WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isRetweeted = $stmt->rowCount() > 0; // True als al geretweet
                                    ?>
                                    <button type="submit" class="retweet-btn">
                                        <?php echo $isRetweeted ? "⛔ Unretweet" : "🔁 Retweet"; ?> (<?php echo (int)$tweet['retweets_count']; ?>)
                                    </button>
                                </form>
                                <!-- Verwijder Tweet Formulier (alleen als eigenaar) -->
                                <?php if ($_SESSION['user_id'] === $tweet['user_id']): ?>
                                    <form action="delete_tweet.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($tweet['tweet_id']); ?>">
                                        <button type="submit" class="delete-btn">
                                            ❌ Verwijder
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Geen Tweets -->
                <p>Geen tweets gevonden.</p>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>