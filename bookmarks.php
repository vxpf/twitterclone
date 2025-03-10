<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Bookmarks</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <div class="twitter-container">
        <aside class="sidebar">
            <div class="logo">
                <h2>Chirpify</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="user.php" class="nav-item">Home</a>
                <a href="bookmarks.php" class="nav-item active">Bookmarks</a>
                <a href="messages.php" class="nav-item">Messages</a>
                <a href="profile.php" class="nav-item">Profile</a>
                <a href="index.php" class="nav-item">Logout</a>
            </nav>
            <button class="tweet-btn">Tweet</button>
        </aside>

        <main class="feed">
            <header class="feed-header">
                <h1>Bookmarks</h1>
            </header>
            <div class="tweets">
                <div class="tweet">
                    <div class="tweet-avatar">
                        <img src="randompersoon.jpg" alt="User Avatar">
                    </div>
                    <div class="tweet-content">
                        <div class="tweet-header">
                            <span class="username">John Doe</span>
                            <span class="handle">@johndoe · 1h</span>
                        </div>
                        <p>This is a bookmarked tweet. Loving the new Chirpify platform!</p>
                        <div class="tweet-actions">
                            <span>Reply</span>
                            <span>Retweet</span>
                            <span>Like</span>
                        </div>
                    </div>
                </div>
                <div class="tweet">
                    <div class="tweet-avatar">
                        <img src="jane_smith.webp" alt="User Avatar">
                    </div>
                    <div class="tweet-content">
                        <div class="tweet-header">
                            <span class="username">Jane Smith</span>
                            <span class="handle">@janesmith · 3h</span>
                        </div>
                        <p>Bookmarked this amazing sunset tweet. #Chirpify #Nature</p>
                        <div class="tweet-actions">
                            <span>Reply</span>
                            <span>Retweet</span>
                            <span>Like</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>