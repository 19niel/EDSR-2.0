<?php
include('php/autoLogin.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-DSR — Electronic Daily Sales Report Version 2.0. Login to access your sales dashboard.">
    <title>E-DSR — Login</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/login.css">

    <!-- Anti-flash: apply saved theme before render -->
    <script>
    (function(){
        var t = localStorage.getItem('edsr-theme') || 'light';
        document.documentElement.setAttribute('data-theme', t);
        document.documentElement.setAttribute('data-bs-theme', t);
    })();
    </script>
</head>

<body>
    <div class="center">
        <img class="logo" src="img/new.png" alt="UBIX Logo">
        <form name="form" action="php/login.php" method="POST">
            <div class="title">
                EDSR <span style="color: var(--login-primary);">RENTAL</span>
            </div>

            <div class="txt_field">
                <input type="text" id="user" name="user" required autocomplete="username">
                <span></span>
                <label for="user">Username</label>
            </div>

            <div class="txt_field">
                <input type="password" id="pass" name="pass" required autocomplete="current-password">
                <span></span>
                <label for="pass">Password</label>
            </div>

            <input type="submit" id="btn" name="login" value="Sign In">
        </form>

        <p class="login-footer-text" style="margin-top:1.25rem;">
            Internal use only &mdash; Unauthorized access is prohibited.
        </p>
    </div>

    <!-- Theme toggle JS -->
    <script src="js/theme-toggle.js"></script>
</body>

</html>