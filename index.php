<?php
include('php/autoLogin.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>E-DSR - Login</title>
</head>

<body>
    <div class="center">
        <form name="form" action="php/login.php" onsubmit="return isvalid()" method="POST">
            <img class="logo" src="img/new.png" alt="ubix">
            <div class="title">Electronic Daily Sales Report</div>
            <div class="txt_field">
                <input type="text" id="user" name="user" required>
                <span></span>
                <label>Username</label>
            </div>
            <div name="password" type="VARCHAR" class="txt_field">
                <input type="password" id="pass" name="pass" required>
                <span></span>
                <label>Password</label>
            </div>
            <input type="submit" id="btn" value="Login" id="login" name="login">
        </form>
    </div>
</body>

</html>