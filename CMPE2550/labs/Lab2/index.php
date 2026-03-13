<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pretty Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js"></script>
</head>
<body>

<div class="card">
    <h1>Lab2 - Register/Login 💕</h1>

    <form>
        <div class="input-box">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" required>
        </div>

        <div class="input-box">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <button type="button" class="register" id="regButton">Register 💕</button>
        <button type="button" class="login" id="loginButton">Login ✨</button>
    </form>
</div>

  <div id="message"></div>
</body>
</html>