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
        <!-- Sidebar (same as front page) -->
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
                        <h2 class="profile-name">John Doe</h2>
                        <span class="profile-handle">@johndoe</span>
                        <p class="profile-bio">Web developer | Tech enthusiast | Coffee lover</p>
                        <div class="profile-meta">
                            <span><strong>250</strong> Following</span>
                            <span><strong>180</strong> Followers</span>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="profile-edit-form" style="display: none;">
                <form>
                    <div class="form-group">
                        <label for="banner">Banner Image</label>
                        <input type="file" id="banner" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="avatar">Profile Picture</label>
                        <input type="file" id="avatar" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" value="John Doe">
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio">Web developer | Tech enthusiast | Coffee lover</textarea>
                    </div>
                    <button type="submit" class="save-profile-btn">Save</button>
                    <button type="button" class="cancel-edit-btn">Cancel</button>
                </form>
            </div>

            
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
                        <p>This is a sample tweet. Loving the new Chirpify platform!</p>
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

   
    <script>
        const editBtn = document.querySelector('.edit-profile-btn');
        const editForm = document.querySelector('.profile-edit-form');
        const cancelBtn = document.querySelector('.cancel-edit-btn');

        editBtn.addEventListener('click', () => {
            editForm.style.display = 'block';
            editBtn.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            editForm.style.display = 'none';
            editBtn.style.display = 'block';
        });
    </script>
</body>
</html>