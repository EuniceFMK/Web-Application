<?php

    //hashing the password
    //password_hash(actualpassword, Encryption Algo)

    $secret =  password_hash("Simran123", PASSWORD_DEFAULT);
    error_log($secret);

    // password_verify(regularpassword, Encrypted one inside your DB)

   // $return = password_verify("Simran123" , $secret);
    //error_log($return);

    /**************************************************
     * Function Name: LoginCheck
     * Purpose      : Validate user Credentials
     * Inputs       : Client username, password
     * Output       : True or False
     ***************************************************/
    function LoginCheck($user, $pass)
    {
        // bring your username and password from DB
        global $secret;
        if($user == "Simran" && password_verify($pass, $secret))
        {
            return true;
        }
        return false;

    }

?>