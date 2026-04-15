<?php
    // if the form is submitted using post

    if( $_SERVER['REQUEST_METHOD']== "POST")
    {   
        // sanitize the data
        $cleanName= strip_tags(trim($_POST['username']));
        $cleanPass= strip_tags(trim($_POST['password']));

        // validate the data if required
        
        //start a new session
        session_start();  // will create a new session for you on server or rejoin the session if there is already a session for you

        // To store something into session variable 
        $_SESSION['logedUserName'] = $cleanName;
        

        // Redirect the user to second page
        header("Location:dashboard.php");

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Demo 02 a</title>
    </head>
    <body>
        <header>
            <h1> Week 02 Day 02 : Demo 02 a : Sessions</h1>
        </header>
        <main>
            <form action="login.php" method="POST">
                <label for="">Username</label>
                <input type="text" name="username" required>
                
                <label for="">Password</label>
                <input type="password" name="password" required>

                <input type="submit" name="submit" value="Login">
            </form>
        </main>
    </body>
</html>