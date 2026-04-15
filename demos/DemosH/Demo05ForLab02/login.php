<?php
    error_log("Inside Login.php");

    if($_SERVER['REQUEST_METHOD']=='POST')
    {
        error_log("Inside Main logic");

        // start a new session
        session_start();
        $cleanUsername = trim( strip_tags( $_POST['username']));
        $cleanPassword = trim( strip_tags( $_POST['password']));

        $_SESSION['username'] = $cleanUsername;

        // Write your logic  to validate password
        // Compare for valid credentials

        // Redirect the user to Welcome page
        header('Location:welcome.php');

    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>

        <!--Link to CSS-->
        <link rel="stylesheet" href="styles.css">

    </head>
    <body>
        <h1>Login Page</h1>
        <form action="login.php" method = "POST">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Enter Your username here">

            <label for="password">Password</label>
            <input type="password" name="password" >

            <input type="submit" value="Login">
            <input type="submit" value="Register">
            <input type="reset" value ="reset">

        </form>
    </body>
</html>