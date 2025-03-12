<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Messages</title>
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
                <a href="bookmarks.php" class="nav-item">Bookmarks</a>
                <a href="messages.php" class="nav-item active">Messages</a>
                <a href="profile.php" class="nav-item">Profile</a>
                <a href="Settings.php" class="nav-item">Settings</a>
                <a href="index.php" class="nav-item">Logout</a>
            </nav>
        </aside>

        <main class="feed">
            <header class="feed-header">
                <h1>Messages</h1>
            </header>
            <div class="messages-container">
                <div class="message-thread">
                    <div class="message">
                        <div class="tweet-avatar">
                            <img src="randompersoon.jpg" alt="User Avatar">
                        </div>
                        <div class="message-content">
                            <div class="tweet-header">
                                <span class="username">John Doe</span>
                                <span class="handle">@johndoe · 1h</span>
                            </div>
                            <p>Hey! How's it going?</p>
                        </div>
                    </div>
                    <div class="message own-message">
                        <div class="message-content">
                            <div class="tweet-header">
                                <span class="username">You</span>
                                <span class="handle">@yourhandle · 50m</span>
                            </div>
                            <p>Pretty good, thanks! How about you?</p>
                        </div>
                    </div>
                </div>
                <div class="message-input">
                    <textarea placeholder="Send a message..."></textarea>
                    <button class="post-btn">Send</button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>