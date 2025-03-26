<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Stuur niet-ingelogde gebruikers naar de loginpagina
    exit();
}

// Databaseconnectie
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

// Tweets ophalen, inclusief gebruikersgegevens en profielfoto's
$stmt = $conn->prepare("
    SELECT 
        tweets.id AS tweet_id, 
        tweets.content, 
        tweets.image, 
        tweets.created_at, 
        tweets.likes_count, 
        tweets.retweets_count,
        users.id AS user_id, 
        users.name, 
        users.profile_picture
    FROM tweets
    INNER JOIN users ON tweets.user_id = users.id
    ORDER BY tweets.created_at DESC
");
$stmt->execute();
$tweets = $stmt->fetchAll();

// Tijd-verstreken functie
function tijdVerstreken($timestamp) {
    $verstrekenTijd = time() - strtotime($timestamp);

    if ($verstrekenTijd < 60) {
        return $verstrekenTijd . " seconde" . ($verstrekenTijd > 1 ? "n" : "") . " geleden";
    } elseif ($verstrekenTijd < 3600) {
        $minuten = floor($verstrekenTijd / 60);
        return $minuten . " minuut" . ($minuten > 1 ? "en" : "") . " geleden";
    } elseif ($verstrekenTijd < 86400) {
        $uren = floor($verstrekenTijd / 3600);
        return $uren . " uur" . ($uren > 1 ? "en" : "") . " geleden";
    } elseif ($verstrekenTijd < 604800) {
        $dagen = floor($verstrekenTijd / 86400);
        return $dagen . " dag" . ($dagen > 1 ? "en" : "") . " geleden";
    } elseif ($verstrekenTijd < 2592000) {
        $weken = floor($verstrekenTijd / 604800);
        return $weken . " week" . ($weken > 1 ? "en" : "") . " geleden";
    } elseif ($verstrekenTijd < 31557600) {
        $maanden = floor($verstrekenTijd / 2592000);
        return $maanden . " maand" . ($maanden > 1 ? "en" : "") . " geleden";
    } else {
        $jaren = floor($verstrekenTijd / 31557600);
        return $jaren . " jaar" . ($jaren > 1 ? "en" : "") . " geleden";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Home</title>
    <link rel="stylesheet" href="user.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Extra CSS voor bestand upload */
        input[type="file"] {
            display: none;
        }

        .file-label {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            background-color: #e8f5fe;
            color: #14171a;
            border: 2px solid #1da1f2;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .file-label:hover {
            background-color: #1da1f2;
            color: white;
            border-color: #1a91da;
        }

        .file-label:active {
            background-color: #0a85cc;
            border-color: #086fa8;
        }
    </style>
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

    <!-- Hoofdsectie (Feed) -->
    <main class="feed">
        <!-- Pagina Titel -->
        <header class="feed-header">
            <h1>Home</h1>
        </header>

        <!-- Tweet-invoer -->
        <div class="tweet-box">
            <form action="post_tweet.php" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="Wat gebeurt er?" rows="4" required></textarea>
                <input type="file" name="image" id="file-upload" accept="image/*">
                <label for="file-upload" class="file-label">Kies een bestand</label>
                <button type="submit" class="post-btn">Tweet</button>
            </form>
        </div>

        <!-- Tweet-lijst -->
        <div class="tweets">
            <?php if (!empty($tweets)): ?>
                <?php foreach ($tweets as $tweet): ?>
                    <div class="tweet">
                        <!-- Profielfoto van Gebruiker -->
                        <div class="tweet-avatar">
                            <img src="<?= htmlspecialchars(!empty($tweet['profile_picture']) ? $tweet['profile_picture'] : 'path/to/default-profile.png'); ?>"
                                 alt="Profielfoto van <?= htmlspecialchars($tweet['name']); ?>">
                        </div>

                        <!-- Tweet Inhoud -->
                        <div class="tweet-content">
                            <div class="tweet-header">
                                <span class="username"><?= htmlspecialchars($tweet['name']); ?></span>
                                <span class="time"> • <?= tijdVerstreken($tweet['created_at']); ?></span>
                            </div>

                            <p><?= htmlspecialchars($tweet['content']); ?></p>

                            <?php if (!empty($tweet['image'])): ?>
                                <img src="<?= htmlspecialchars($tweet['image']); ?>" alt="Tweet afbeelding" class="tweet-image" style="max-width: 100%; height: auto;">
                            <?php endif; ?>

                            <!-- Tweet Acties -->
                            <div class="tweet-actions">
                                <form action="like_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isLiked = $stmt->rowCount() > 0;
                                    ?>
                                    <button type="submit" class="like-btn">
                                        <?= $isLiked ? "💔 Unlike" : "❤️ Like"; ?> (<?= (int)$tweet['likes_count']; ?>)
                                    </button>
                                </form>

                                <form action="retweet_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM retweets WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isRetweeted = $stmt->rowCount() > 0;
                                    ?>
                                    <button type="submit" class="retweet-btn">
                                        <?= $isRetweeted ? "⛔ Unretweet" : "🔁 Retweet"; ?> (<?= (int)$tweet['retweets_count']; ?>)
                                    </button>
                                </form>

                                <?php if ($_SESSION['user_id'] === $tweet['user_id']): ?>
                                    <form action="delete_tweet.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                        <button type="submit" class="delete-btn">❌ Verwijder</button>
                                    </form>
                                <?php endif; ?>
                            </div>
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