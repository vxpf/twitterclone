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
      
        <aside class="sidebar">
            <div class="logo">
                <h2>Chirpify</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">Home</a>
                <a href="#" class="nav-item">Explore</a>
                <a href="#" class="nav-item">Notifications</a>
                <a href="#" class="nav-item">Messages</a>
                <a href="profile.php" class="nav-item">Profile</a>
                <a href="index.php" class="nav-item">Logout</a>
            </nav>
            <button class="tweet-btn">Tweet</button>
        </aside>

        <!-- Main Feed (Center) -->
        <main class="feed">
            <header class="feed-header">
                <h1>Home</h1>
            </header>
            <div class="tweet-box">
                <textarea placeholder="What's happening?"></textarea>
                <button class="post-btn">Tweet</button>
            </div>
            <div class="tweets">
                <div class="tweet">
                    <div class="tweet-avatar">
                        <img src="https://via.placeholder.com/50" alt="User Avatar">
                    </div>
                    <div class="tweet-content">
                        <div class="tweet-header">
                            <span class="username">John Doe</span>
                            <span class="handle">@johndoe · 1h</span>
                        </div>
                        <p>This is a sample tweet. Loving the new Chirpify platform!</p>
                        <div class="tweet-actions">
                            <span>Reply</span>
                            <span>Retweet</span>
                            <span>Like</span>
                        </div>
                    </div>
                </div>
                <div class="tweet">
                    <div class="tweet-avatar">
                        <img src="https://via.placeholder.com/50" alt="User Avatar">
                    </div>
                    <div class="tweet-content">
                        <div class="tweet-header">
                            <span class="username">Jane Smith</span>
                            <span class="handle">@janesmith · 3h</span>
                        </div>
                        <p>Just saw an amazing sunset. #Chirpify #Nature</p>
                        <div class="tweet-actions">
                            <span>Reply</span>
                            <span>Retweet</span>
                            <span>Like</span>
                        </div>
                    </div>
                </div>
                <!-- Add more tweets as needed -->
            </div>
        </main>
    </div>
</body>
</html>