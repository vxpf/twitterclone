<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

$user_name = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chirpify</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Welkom, <?php echo $user_name; ?>!</h2>
    <p>Je bent succesvol ingelogd.</p>
    <a href="index.php">Uitloggen</a>
</div>

</body>
</html>
