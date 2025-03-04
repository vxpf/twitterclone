<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'User') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Chirpify</title>
</head>
<body>
<h1>Welkom, Gebruiker!</h1>
<a href="index.php">Uitloggen</a>
</body>
</html>
