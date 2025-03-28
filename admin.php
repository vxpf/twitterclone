<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php');
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

// Haal gebruikers op
$stmt = $conn->prepare("SELECT id, name, email, role, banned FROM users WHERE role != 'Admin'");
$stmt->execute();
$users = $stmt->fetchAll();

// Verwerk bannen of verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ban_user'])) {
        $user_id = (int)$_POST['ban_user'];
        $conn->prepare("UPDATE users SET banned = 1 WHERE id = ?")->execute([$user_id]);
    } elseif (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['delete_user'];
        $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    }
    header('Location: admin.php');
    exit();

    // Reacties verwijderen
    if (isset($_POST['delete_comment'])) {
        $comment_id = (int)$_POST['delete_comment'];
        $conn->prepare("DELETE FROM tweets WHERE id = ?")->execute([$comment_id]);
        header('Location: admin.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Beheer</title>
</head>
<body>
<h1>Gebruikersbeheer</h1>
<table border="1">
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
            <td><?= htmlspecialchars($user['role']); ?></td>
            <td>
                <?php if ($user['banned']): ?>
                    <span>Geblokkeerd</span>
                <?php else: ?>
                    <form method="POST" style="display:inline;">
                        <button type="submit" name="ban_user" value="<?= $user['id']; ?>">Blokkeer</button>
                    </form>
                <?php endif; ?>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="delete_user" value="<?= $user['id']; ?>">Verwijder</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>