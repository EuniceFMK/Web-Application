<?php
// You shall create your service AJAX request processing here.

// Data received should be cleaned, but only for whitespace and HTML injection.
// No SQL injection cleaning required.

// Each of the three button events should be processed and responses sent as 
// described in the exam document.  Use functions to handle the operations for 
// each action.

// Follow the service pattern introduced in class for handling the actions for
// your web service.
require_once 'labexam01_lib.php';

$clean = array();  // Initialize an array to hold sanitized inputs
// Sanitize all incoming GET parameters
foreach ($_GET as $key => $value)
    $clean[trim(strip_tags(htmlspecialchars($key)))] = strip_tags(htmlspecialchars($value));

$output = array();   // Initialize an array to hold output data

error_log($clean["seed"]);
$action = $clean["action"] ?? "";  // Get the action to be performed
$arr = array();
// This function will generate the array by calling the PartB function in
// the labexam01_lib.php file, and process it as requested.  The output will
// be the processed results or an error message.

switch ($action) {

    // Initialize the game board and reset current player
    case "getsaved":
        GetSaved();
        break;
    case "clearserved":
        ClearSaved();
        break;
}
function SubmitNew()
{
    global $output, $seednum,$arr;

    $arr = PartB($seednum);


    if (!is_numeric($seednum)) {
        $output["message"] = "Seed must be numeric";

    } else {
        $output["response"] = $arr;
        $_SESSION["Returnedarray"] = $arr;
        error_log($arr);
    }
}

echo json_encode($output);  // Output the response as JSON
die();


// This function retrieves the number array from the session (if there is one),
// and then processes it the same as if it was newly submitted and saved.  An error
// message is returned if the array does not exist in the session.
function GetSaved()
{

}


// This function destroys the session variable storing the array if it exists.
// An error message is returned if the array does not exist in the session.
function ClearSaved()
{
    global $arr;
    unset($_SESSION["Returnedarray"]);
}








