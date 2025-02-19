<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />

    <link rel="stylesheet" href="style.css" />
    <title>Login in</title>
</head>
<body>
<div class="container">
    <div class="forms-container">
        <div class="signin-signup">
            <form action="#" class="sign-in-form">
                <h2 class="title">Log in</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Username" />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" />
                </div>
                <input type="submit" value="Login" class="btn solid" />
            </form>
            <form action="#" class="sign-up-form">
                <h2 class="title">Registreer</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Username" />
                </div>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" placeholder="Email" />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" />
                </div>
                <input type="submit" class="btn" value="Sign up" />
            </form>
        </div>
    </div>

    <div class="panels-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>Nieuw hier?</h3>
                <p>
                    Maak dan een nieuw geweldige account naar deze mooie site!
                </p>
                <button class="btn transparent" id="sign-up-btn">
                    Sign up
                </button>
            </div>
            <img src="login.svg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
            <div class="content">
                <h3>Ben je een van ons?</h3>
                <p>
                    Log dan in!
                </p>
                <button class="btn transparent" id="sign-in-btn">
                   Log in
                </button>
            </div>
            <img src="reg.svg" class="image" alt="" />
        </div>
    </div>
</div>

<script src="app.js"></script>
</body>
</html>
