<?php
    
    session_start();

    // Remove all the session variables
    session_unset();

    // It will destroy the session information
    session_destroy();

    header("Location: login.php");
    
?>