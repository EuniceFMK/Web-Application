<?php
    session_start();  // create a new session or rejoin it

    if(isset($_SESSION['logedUserName']))
    {
        echo "Welcome to my website ". $_SESSION['logedUserName'];
        echo "<br><a href='logout.php'> Logout </a>";
    }
    else
    {
        // Redirect the user back to login  page
        header("Location:login.php");
        exit(); // terminate the script
    }
?>