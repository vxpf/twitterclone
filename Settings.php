<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify - Settings</title>
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
                <a href="profile.php" class="nav-item">Profile</a>
                <a href="Settings.php" class="nav-item active">Settings</a>
                <a href="index.php" class="nav-item">Logout</a>
            </nav>
        </aside>

      
        <main class="feed">
            <header class="feed-header">
                <h1>Settings</h1>
            </header>
            <div class="profile-edit-form">
                <h2>Account Settings</h2>
                <form>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="@username">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" placeholder="Tell us about yourself"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label for="theme">Theme</label>
                        <select id="theme" name="theme">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                    </div>
                    <button type="submit" class="save-profile-btn">Save Changes</button>
                    <button type="button" class="cancel-edit-btn">Cancel</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>