<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Toegang geweigerd. Alleen beheerders hebben toegang tot deze pagina.");
}

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

// Verwijder een tweet
if (isset($_POST['delete_tweet_id'])) {
    $tweet_id = (int)$_POST['delete_tweet_id'];
    $stmt = $conn->prepare("DELETE FROM tweets WHERE id = ?");
    $stmt->execute([$tweet_id]);
    $_SESSION['success'] = "Tweet succesvol verwijderd.";
    header('Location: admin.php');
    exit();
}

// Verwijder een comment
if (isset($_POST['delete_comment_id'])) {
    $comment_id = (int)$_POST['delete_comment_id'];
    $stmt = $conn->prepare("DELETE FROM tweets WHERE id = ?");
    $stmt->execute([$comment_id]);
    $_SESSION['success'] = "Comment succesvol verwijderd.";
    header('Location: admin.php');
    exit();
}

// Verwijder een gebruiker
if (isset($_POST['delete_user_id'])) {
    $user_id = (int)$_POST['delete_user_id'];

    // Verwijder alle tweets en comments van de gebruiker
    $stmt = $conn->prepare("DELETE FROM tweets WHERE user_id = ?");
    $stmt->execute([$user_id]);

    // Verwijder de gebruiker zelf
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $_SESSION['success'] = "Gebruiker succesvol verwijderd.";
    header('Location: admin.php');
    exit();
}

// Bewerk een gebruiker
if (isset($_POST['edit_user_id'])) {
    $user_id = (int)$_POST['edit_user_id'];
    $new_name = trim($_POST['new_name']);
    $new_email = trim($_POST['new_email']);
    $new_password = trim($_POST['new_password']);

    // Update gebruikersgegevens
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$new_name, $new_email, $hashed_password, $user_id]);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$new_name, $new_email, $user_id]);
    }

    $_SESSION['success'] = "Gebruiker succesvol bijgewerkt.";
    header('Location: admin.php');
    exit();
}

// Haal alle tweets op (hoofdposts)
$tweets = $conn->query("
    SELECT 
        tweets.id AS tweet_id, 
        tweets.content, 
        tweets.created_at, 
        users.name AS username 
    FROM tweets 
    INNER JOIN users ON tweets.user_id = users.id
    WHERE tweets.parent_tweet_id IS NULL
    ORDER BY tweets.created_at DESC
")->fetchAll();

// Haal alle comments op (reacties op tweets)
$comments = $conn->query("
    SELECT 
        tweets.id AS comment_id, 
        tweets.content, 
        tweets.created_at, 
        users.name AS username 
    FROM tweets 
    INNER JOIN users ON tweets.user_id = users.id
    WHERE tweets.parent_tweet_id IS NOT NULL
    ORDER BY tweets.created_at DESC
")->fetchAll();

// Haal alle gebruikers op
$users = $conn->query("
    SELECT 
        id, 
        name, 
        email, 
        is_admin 
    FROM users
    ORDER BY name ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css"> <!-- Zorg ervoor dat dit pad correct is -->
</head>
<body>
<div class="admin-container">
    <h1>Admin Dashboard</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <h2>Tweets Beheren</h2>
    <table>
        <thead>
            <tr>
                <th>Gebruiker</th>
                <th>Inhoud</th>
                <th>Datum</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tweets as $tweet): ?>
                <tr>
                    <td><?= htmlspecialchars($tweet['username']); ?></td>
                    <td><?= htmlspecialchars($tweet['content']); ?></td>
                    <td><?= htmlspecialchars($tweet['created_at']); ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="delete_tweet_id" value="<?= $tweet['tweet_id']; ?>">
                            <button type="submit" class="delete-btn">Verwijder</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Comments Beheren</h2>
    <table>
        <thead>
            <tr>
                <th>Gebruiker</th>
                <th>Inhoud</th>
                <th>Datum</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= htmlspecialchars($comment['username']); ?></td>
                    <td><?= htmlspecialchars($comment['content']); ?></td>
                    <td><?= htmlspecialchars($comment['created_at']); ?></td>
                    <td>
                        <form action="admin.php" method="POST" style="display:inline;">
                            <input type="hidden" name="delete_comment_id" value="<?= $comment['comment_id']; ?>">
                            <button type="submit" class="delete-btn">Verwijder</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gebruikers Beheren</h2>
    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                    <td>
                        <?php if ($_SESSION['user_id'] != $user['id']): // Voorkom dat de admin zichzelf verwijdert ?>
                            <form action="admin.php" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id']; ?>">
                                <button type="submit">Verwijder</button>
                            </form>
                            <button onclick="showEditForm(<?= $user['id']; ?>, '<?= htmlspecialchars($user['name']); ?>', '<?= htmlspecialchars($user['email']); ?>')">Bewerk</button>
                        <?php else: ?>
                            <span>Kan niet verwijderen</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulier om gebruikersgegevens te bewerken -->
    <div id="edit-user-form" style="display:none;">
        <h2>Gebruiker Bewerken</h2>
        <form action="admin.php" method="POST">
            <input type="hidden" name="edit_user_id" id="edit_user_id">
            <label for="new_name">Naam:</label>
            <input type="text" name="new_name" id="new_name" required>
            <label for="new_email">Email:</label>
            <input type="email" name="new_email" id="new_email" required>
            <label for="new_password">Nieuw Wachtwoord (optioneel):</label>
            <input type="password" name="new_password" id="new_password">
            <button type="submit">Opslaan</button>
            <button type="button" onclick="hideEditForm()">Annuleren</button>
        </form>
    </div>
</div>

<script>
    function showEditForm(userId, userName, userEmail) {
        document.getElementById('edit_user_id').value = userId;
        document.getElementById('new_name').value = userName;
        document.getElementById('new_email').value = userEmail;
        document.getElementById('edit-user-form').style.display = 'block';
    }

    function hideEditForm() {
        document.getElementById('edit-user-form').style.display = 'none';
    }
</script>
</body>
</html>