<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Redirect non-logged-in users to the login page
    exit();
}

// Database connection
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
    die("Connection error: " . $e->getMessage());
}

// Fetch tweets
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
    WHERE tweets.parent_tweet_id IS NULL 
    ORDER BY tweets.created_at DESC
");
$stmt->execute();
$tweets = $stmt->fetchAll();

// Time elapsed function
function tijdVerstreken($timestamp) {
    $time = strtotime($timestamp);
    $timeDifference = time() - $time;

    if ($timeDifference < 60) {
        return $timeDifference . ' seconds ago';
    } elseif ($timeDifference < 3600) {
        return floor($timeDifference / 60) . ' minutes ago';
    } elseif ($timeDifference < 86400) {
        return floor($timeDifference / 3600) . ' hours ago';
    } elseif ($timeDifference < 604800) {
        return floor($timeDifference / 86400) . ' days ago';
    } else {
        return date('d M Y', $time);
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
            display: none; /* Hide comments by default */
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
            color: #1da1f2; /* Twitter blue */
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

    <!-- Main Section (Feed) -->
    <main class="feed">
        <header class="feed-header">
            <h1>Home</h1>
        </header>

        <div class="tweet-box">
            <form action="post_tweet.php" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="What's happening?" rows="4" required></textarea>
                
                
                <input type="file" id="file-upload" name="file" style="display: none;">
                <label for="file-upload" class="file-label">Choose File</label>

                <button type="submit" class="post-btn">Tweet</button>
            </form>
        </div>

        <div class="tweets">
            <?php if (!empty($tweets)): ?>
                <?php foreach ($tweets as $tweet): ?>
                    <?php
                    // Fetch users who liked the tweet
                    $likeUsersStmt = $conn->prepare("
                        SELECT users.name 
                        FROM likes 
                        INNER JOIN users ON likes.user_id = users.id 
                        WHERE likes.tweet_id = ?
                    ");
                    $likeUsersStmt->execute([$tweet['tweet_id']]);
                    $likeUsers = $likeUsersStmt->fetchAll(PDO::FETCH_COLUMN);

                    // Check if there are likes and create a list of names
                    $likeUsersList = !empty($likeUsers) ? implode(', ', $likeUsers) : 'No likes yet';
                    ?>
                    <div class="tweet">
                        <div class="tweet-avatar">
                            <img src="<?= htmlspecialchars(!empty($tweet['profile_picture']) ? $tweet['profile_picture'] : 'path/to/default-profile.png'); ?>"
                                 alt="Profile picture of <?= htmlspecialchars($tweet['name']); ?>">
                        </div>

                        <div class="tweet-content">
                            <div class="tweet-header">
                                <span class="username"><?= htmlspecialchars($tweet['name']); ?></span>
                                <span class="time"> <?= tijdVerstreken($tweet['created_at']); ?></span>
                            </div>

                            <p><?= htmlspecialchars($tweet['content']); ?></p>

                            <?php if (!empty($tweet['image'])): ?>
                                <img src="<?= htmlspecialchars($tweet['image']); ?>" alt="Tweet image" class="tweet-image" style="max-width: 100%; height: auto;">
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
                                    <button type="submit" class="like-btn <?= $isLiked ? 'active' : ''; ?>" title="<?= htmlspecialchars($likeUsersList); ?>">
                                        <?php if ($isLiked): ?>
                                            <i class="fa-solid fa-heart"></i> <!-- Full heart for liked tweets -->
                                        <?php else: ?>
                                            <i class="fa-regular fa-heart"></i> <!-- Empty heart for unliked tweets -->
                                        <?php endif; ?>
                                        <span class="action-count"><?= (int)$tweet['likes_count']; ?></span>
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
                                                 alt="Profile picture of <?= htmlspecialchars($comment['username']); ?>">
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

                                <form action="post_comment.php" method="POST" class="comment-form">
                                    <textarea name="comment" placeholder="Post your reply" rows="2" required></textarea>
                                    <input type="hidden" name="tweet_id" value="<?= htmlspecialchars($tweet['tweet_id']); ?>">
                                    <button type="submit">Post Comment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tweets found.</p>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>