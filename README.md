# Chirpify

Een Twitter-achtige social media applicatie gebouwd met PHP en MySQL.

## Functies

- **Gebruikersbeheer**
  - Registreren en inloggen
  - Profielpagina met bio, profielfoto en banner
  - Accountinstellingen aanpassen (naam, email, wachtwoord)

- **Tweets**
  - Tweets plaatsen met tekst en afbeeldingen
  - Tweets verwijderen
  - Afbeeldingen uploaden bij tweets

- **Interactie**
  - Tweets liken/unliken
  - Reageren op tweets (comments)
  - Comments verwijderen

- **Admin Dashboard**
  - Alle tweets en comments beheren
  - Gebruikers beheren (bewerken/verwijderen)

## Technische Stack

- **Backend:** PHP 7+
- **Database:** MySQL (PDO)
- **Frontend:** HTML, CSS, JavaScript
- **Icons:** Font Awesome

## Installatie

1. **Clone de repository**
   ```bash
   git clone <repository-url>
   ```

2. **Database opzetten**
   - Maak een MySQL database aan met de naam `login_system`
   - Importeer de benodigde tabellen:
     - `users` (id, name, email, password, role, is_admin, bio, profile_picture, banner)
     - `tweets` (id, user_id, content, image, parent_tweet_id, likes_count, created_at)
     - `likes` (id, user_id, tweet_id)

3. **Configuratie**
   - Pas de database-instellingen aan in `config.php`:
     ```php
     $host = 'localhost';
     $dbname = 'login_system';
     $username = 'root';
     $password = '';
     ```

4. **Uploads map**
   - Zorg dat de `uploads/` map bestaat en schrijfbaar is

5. **Webserver**
   - Plaats het project in je webserver directory (bijv. XAMPP htdocs)
   - Open `http://localhost/twitterclone/` in je browser

## Bestandsstructuur

```
twitterclone/
├── index.php           # Login/registratie pagina
├── user.php            # Homepage met tweet feed
├── profile.php         # Gebruikersprofiel
├── Settings.php        # Account instellingen
├── admin.php           # Admin dashboard
├── post_tweet.php      # Tweet plaatsen
├── post_comment.php    # Comment plaatsen
├── like_tweet.php      # Like/unlike functionaliteit
├── delete_tweet.php    # Tweet verwijderen
├── delete_comment.php  # Comment verwijderen
├── login_register.php  # Registratie verwerking
├── config.php          # Database configuratie
├── about.php           # Over Chirpify pagina
├── uploads/            # Geüploade afbeeldingen
├── index.css           # Styling login pagina
├── user.css            # Styling gebruikerspagina's
├── admin.css           # Styling admin dashboard
└── script.js           # JavaScript functies
```

## Gebruik

1. **Registreren:** Maak een account aan via de registratiepagina
2. **Inloggen:** Log in met je email en wachtwoord
3. **Tweeten:** Plaats tweets met optioneel een afbeelding
4. **Interactie:** Like tweets en plaats reacties
5. **Profiel:** Bekijk en bewerk je profiel via Settings

## Screenshots

Screenshots van de applicatie zijn beschikbaar in de root directory.

## Licentie

Dit project is gemaakt voor educatieve doeleinden.
