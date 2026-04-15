<?php

    // Step 1: Validation: Validate the data and request itself which is coming from client side
    /*
        isset()- function checks whether or not a variable is set
        which means it has to be declared and is not null
    */
    if( isset($_GET['username']))
    {
        if(strlen($_GET['username']) > 0)
        {
            
            //Step 2: Sanitize your inputs
            /*
                trim()      : it will remove white spaces from both ends of the string
                strip_tags()  : it will strip a string from HTML, XML, PHP and other tags
            */

            // Cleaning inputs
            error_log("Before cleaning " . $_GET['username']);
            // clean() is user defined function defined at the botton of this file- NO Google search
            $cleanUsername = clean( $_GET['username']) ; 
            error_log("After cleaning " . $cleanUsername);

            echo "Welcome to the world of server";

            echo "<br>Welcome ". $cleanUsername; 
        }
        else{
            echo "<br> Username can not empty";
        }
    }
    else
    {
        echo "Sorry bad request. You can enjoy outside in winter";
    }

    /*************************************************
     * clean    : It will sanitize the input string
     * Inputs   : String
     * Output   : Cleaned string
     *************************************************/
    function clean($input)
    {
        return strip_tags( trim($input) );
    }
?>