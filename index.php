<?php session_start(); ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registreer</title>
    <link rel="stylesheet" href="index.css">
</head>
<style>
    .error, .success {
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        margin: 10px auto;
        width: 90%;
        max-width: 400px;
        font-weight: bold;
        transition: opacity 1s ease-in-out;
    }

    .error {
        color: red;
        background-color: #ffcccc;
        border: 1px solid red;
    }

    .success {
        color: green;
        background-color: #ccffcc;
        border: 1px solid green;
    }
</style>
<body>

<div class="container">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="form-box active" id="login-form">
        <form action="login_register.php" method="post">
            <h2>Login</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <button type="submit" name="login">Login</button>
            <p>Heb je nog geen account? <a href="#" onclick="showForm('register-form')">Registreer</a></p>
        </form>
    </div>

    <div class="form-box" id="register-form">
        <form action="login_register.php" method="post">
            <h2>Registreren</h2>
            <input type="text" name="name" placeholder="Naam" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <select name="role" required>
                <option value="">--Selecteer rol--</option>
                <option value="User">User</option>
                <option value="Admin">Admin</option>
                <option value="Guest">Guest</option>
            </select>
            <button type="submit" name="register">Registreer</button>
            <p>Heb je al een account? <a href="#" onclick="showForm('login-form')">Login</a></p>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
<script>

    setTimeout(function() {
        let messages = document.querySelectorAll('.error, .success');
        messages.forEach(function(message) {
            message.style.transition = "opacity 1s";
            message.style.opacity = "0";
            setTimeout(() => message.style.display = "none", 1000);
        });
    }, 3000);
</script>

</html>
