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

// Alleen hoofdtweets ophalen
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
    WHERE tweets.parent_tweet_id IS NULL 
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
        .time {
            margin-left: 5px;
        }

        .comments {
            display: none; /* Reacties standaard verbergen */
            margin-top: 10px;
        }

        .comment {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .tweet-actions button {
            background: none;
            border: none;
            cursor: pointer;
            color: #555;
            font-size: 16px;
            margin-right: 15px;
        }

        .tweet-actions button:hover {
            color: #1da1f2; /* Twitter blauw */
        }



        .action-count {
            font-size: 14px;
            margin-left: 5px;
        }

        .tweet-avatar img,
        .comment img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
    <script>
        function toggleComments(tweetId) {
            const commentsDiv = document.getElementById('comments-' + tweetId);
            commentsDiv.style.display = (commentsDiv.style.display === 'block') ? 'none' : 'block';
        }
    </script>
</head>
<body>
<div class="twitter-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>Chirpify</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="user.php" class="nav-item"> <i class="fa-solid fa-house"></i> Home</a>
            <a href="profile.php" class="nav-item"><i class="fa-solid fa-user"></i> Profile</a>
            <a href="Settings.php" class="nav-item"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="index.php" class="nav-item"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </aside>

    <!-- Hoofdsectie (Feed) -->
    <main class="feed">
        <header class="feed-header">
            <h1>Home</h1>
        </header>

        <div class="tweet-box">
            <form action="post_tweet.php" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="Wat gebeurt er?" rows="4" required></textarea>
                <input type="file" name="image" id="file-upload" accept="image/*">
                <button type="submit" class="post-btn">Tweet</button>
            </form>
        </div>

        <div class="tweets">
            <?php if (!empty($tweets)): ?>
                <?php foreach ($tweets as $tweet): ?>
                    <div class="tweet">
                        <div class="tweet-avatar">
                            <img src="<?= htmlspecialchars(!empty($tweet['profile_picture']) ? $tweet['profile_picture'] : 'path/to/default-profile.png'); ?>"
                                 alt="Profielfoto van <?= htmlspecialchars($tweet['name']); ?>">
                        </div>

                        <div class="tweet-content">
                            <div class="tweet-header">
                                <span class="username"><?= htmlspecialchars($tweet['name']); ?></span>
                                <span class="time"> <?= tijdVerstreken($tweet['created_at']); ?></span>
                            </div>

                            <p><?= htmlspecialchars($tweet['content']); ?></p>

                            <?php if (!empty($tweet['image'])): ?>
                                <img src="<?= htmlspecialchars($tweet['image']); ?>" alt="Tweet afbeelding" class="tweet-image" style="max-width: 100%; height: auto;">
                            <?php endif; ?>

                            <div class="tweet-actions">
                                <!-- Like Button -->
                                <form action="like_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isLiked = $stmt->rowCount() > 0;
                                    ?>
                                    <button type="submit" class="like-btn">
                                        <i class="<?= $isLiked ? 'fa-solid fa-heart' : 'fa-regular fa-heart'; ?>"></i>
                                        <span class="action-count"><?= (int)$tweet['likes_count']; ?></span>
                                    </button>
                                </form>

                                <form action="retweet_tweet.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM retweets WHERE user_id = ? AND tweet_id = ?");
                                    $stmt->execute([$_SESSION['user_id'], $tweet['tweet_id']]);
                                    $isRetweeted = $stmt->rowCount() > 0;
                                    ?>
                                    <button type="submit" class="retweet-btn <?= $isRetweeted ? 'active' : ''; ?>">
                                        <i class="fa-solid fa-retweet"></i>
                                        <span class="action-count"><?= (int)$tweet['retweets_count']; ?></span>
                                    </button>
                                </form>

                                <!-- Comments Button -->
                                <?php
                                $commentCountStmt = $conn->prepare("SELECT COUNT(*) AS comment_count FROM tweets WHERE parent_tweet_id = ?");
                                $commentCountStmt->execute([$tweet['tweet_id']]);
                                $commentCount = $commentCountStmt->fetch()['comment_count'];
                                ?>
                                <button type="button" onclick="toggleComments(<?= htmlspecialchars($tweet['tweet_id']); ?>)" class="comment-btn">
                                    <i class="fa-regular fa-comment"></i>
                                    <span class="action-count"><?= (int)$commentCount; ?></span>
                                </button>

                                <!-- Delete Button -->
                                <?php if ($tweet['user_id'] === $_SESSION['user_id']): ?>
                                    <form action="delete_tweet.php" method="POST" style="display: inline-block;">
                                        <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <div id="comments-<?= htmlspecialchars($tweet['tweet_id']); ?>" class="comments">
                                <?php
                                $replyStmt = $conn->prepare("
                                    SELECT 
                                        tweets.id AS comment_id, 
                                        tweets.content, 
                                        tweets.created_at,
                                        users.name AS username, 
                                        users.profile_picture,
                                        users.id AS user_id
                                    FROM tweets
                                    INNER JOIN users ON tweets.user_id = users.id
                                    WHERE tweets.parent_tweet_id = ?
                                    ORDER BY tweets.created_at
                                ");
                                $replyStmt->execute([$tweet['tweet_id']]);
                                $comments = $replyStmt->fetchAll();

                                foreach ($comments as $comment): ?>
                                    <div class="comment">
                                        <div class="comment-avatar">
                                            <img src="<?= htmlspecialchars(!empty($comment['profile_picture']) ? $comment['profile_picture'] : 'path/to/default-profile.png'); ?>"
                                                 alt="Profielfoto van <?= htmlspecialchars($comment['username']); ?>">
                                        </div>
                                        <strong><?= htmlspecialchars($comment['username']); ?></strong>
                                        <p><?= htmlspecialchars($comment['content']); ?></p>
                                        <small><?= tijdVerstreken($comment['created_at']); ?></small>

                                        <?php if ($comment['user_id'] === $_SESSION['user_id']): ?>
                                            <form style="display:inline;" action="delete_comment.php" method="POST">
                                                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['comment_id']); ?>">
                                                <button type="submit" class="delete-btn">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <form action="post_comment.php" method="POST">
                                    <textarea name="comment" placeholder="Schrijf een reactie..." rows="2" required></textarea>
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <button type="submit">Plaats Reactie</button>
                                </form>
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