<?php
session_start();

if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

$admin_name = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container">
    <h2>Welkom, Admin <?php echo $admin_name; ?>!</h2>
    <p>Je bent ingelogd als beheerder.</p>
    <a href="index.php">Uitloggen</a>
</div>

</body>
</html>
