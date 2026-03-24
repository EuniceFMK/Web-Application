<?php
    session_start();

    // to Delete all your session variables
    session_unset();

    // To destroy the session
    session_destroy();

    header('Location:login.php');
    die();

?>