<?php
    error_log("Inside Server.php");
    error_log(json_encode( $_GET));


    // Array styles
    // Not using it in this demo it is to show how to declare and use 2D array
    /*$cars = array(array("Volvo", "BMW", "Toyota"), 
    array("Volvo", "BMW", "Toyota"),
    array("Volvo", "BMW", "Toyota")
    ); 
    
    $numbers = [[0,3,6],[7,78]];
*/

    // Valdiate your request
    if($_SERVER['REQUEST_METHOD']== "GET")
    {
        // Whether request is from B1, B2 or B3 button
        if( strip_tags( $_GET['action']) == "b2")
        {
            // Validate sanitize your data

            session_start();
            $_SESSION['p1Name'] = strip_tags(trim($_GET['p1Name'])); 
            $_SESSION['p2Name'] = strip_tags(trim($_GET['p2Name'])); 
            $_SESSION['gameBoard'] = array();

            error_log(json_encode( $_SESSION));

            // returning response back to client
            echo "Player  ". $_SESSION['p1Name'] ." will go first ";
        }
        
        else if( strip_tags( $_GET['action']) == "b3")
        {
           

            //echo $numbers[0][1] ."<br>";
            //echo $cars[0][0];

            $cleanNameP1  = strip_tags(trim($_GET['p1Name'])); 
            
            $score = 1;

            //$gameBoard= [];
            $data = (object) [
                'name' => $cleanNameP1,  // it should be arrow sign not the = one
                'currentscore' => $score,
                'currentTurn' => 2 
            ];

            echo json_encode($data);

        }
        else // otherwise it is b1 button
        {
        
            $string = "Welcome to server side";
            $string .= "Current Turn: 1";

            echo $string;  // Point to return HTML data back to client
        }
    }
    else
    {
        echo "No my friend you are not allowed";
    }
?>