<?php

    session_start();

    if(isset($_SESSION['username']))
    {
        echo "Welcome to Dashboard page " . $_SESSION['username'];
        echo "<br><a href='logout.php'> Logout </a>";
    }
    else
    {
        //echo "<br> Sorry my friend you can enjoy outside";
        // give a message to user or redirect the user to login page or any other page
        header('Location:login.php');
    }

?>