<?php
$username = $_GET['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-DSR — Set your new password before first login.">
    <title>E-DSR — Set New Password</title>

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
        var t = localStorage.getItem('edsr-theme');
        if (!t) t = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
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
                Electronic Daily Sales Report
                <span style="display:block;font-size:0.78rem;font-weight:500;color:var(--login-muted);margin-top:4px;">Set Your New Password</span>
            </div>

            <div class="txt_field">
                <input type="text" id="user" name="user" value="<?php echo htmlspecialchars($username); ?>" readonly autocomplete="username">
                <span></span>
                <label for="user">Username</label>
            </div>

            <div class="txt_field">
                <input type="password" id="pass" name="pass" required autocomplete="new-password" placeholder=" ">
                <span></span>
                <label for="pass">New Password</label>
            </div>

            <input type="submit" id="btn" name="newLogin" value="Set Password & Login">
        </form>

        <p class="login-footer-text" style="margin-top:1.25rem;">
            Choose a strong password you will remember.
        </p>
    </div>

    <!-- Theme toggle JS -->
    <script src="js/theme-toggle.js"></script>
</body>

</html>