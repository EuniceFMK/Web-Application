<?php
require_once 'db.php';  // Copy your db.php into your exam folder 

//	FOLLOW THE PATTERN INTRODUCED IN CLASS TO HANDLE THE INCOMING DATA AND FUNCTION PROCESSING
// Sanitize and clean input parameters
$output = array();
$clean = array();
$cleanPOST = array();
// Sanitize and clean input parameters
foreach ($_GET as $key => $value) {
    $clean[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] =
        trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
}
foreach ($_POST as $key => $value) {
    $cleanPOST[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] =
        trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
}


if (isset($clean["action"])) {
    if ($clean["action"] == "Gettags")
        PartA($clean["partA"]);

}

    if ($cleanPOST["action"] == "GettagsbyId")
        PartB($cleanPOST["partB"]);

echo (json_encode($output));
die();

//	ONLY FUNCTION IMPLEMENTATINOS SHOULD OCCUR BELOW THIS LINE OF TEXT 


//  Function    :   PartA
//  Accepts     :   Tag Range Filter value from exam page
//  Description :   The function will query for all tags that have a sum of the 
//                  tagMin and tagMax values that is less than your filter value
//                  ie. tagMax = 120, tagMin = 40 => Sum = 120+40 = 160
//                 
//                  Iterating through the result set, you shall calculate the average of the
//                  differences between the tagMin and tagMax values.
//  
//                  Upon completion, construct and return a result string as an associative data element : 
//                  $respData["partA"].  Format the string as shown in the working copy.
//      
//                  **  No php or sql aggregate functions may be used ( ie. min, max, sum, etc ).
//
//		    Put your PartA code in the blank section following this part of the text




function PartA($val)
{
    global $output;
    $query = "SELECT tagID,tagDesc,tagMin,tagMax
              FROM tags WHERE tagMin+tagMax<'$val'";
    
    $query2 = "SELECT tagMax-tagMin
               FROM tags WHERE tagMin+tagMax<'$val' ";
    $results2 = mySqlQuery($query2);
    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $output["num"] = $results->num_rows;
        $output["tags"] = $results->fetch_all();
        error_log(json_encode($output["tags"]));
        

    } else {
        error_log("Outpur empty");
        $output["tags"] = [];
    }
    if ($results2 && $results2->num_rows > 0) {
        $output["num"] = $results2->num_rows;
        $output["tagsdiff"] = $results2->fetch_all();
        error_log(json_encode($output["tagsdiff"]));
        

    } else {
        error_log("Outpur empty");
        $output["tagsdiff"] = [];
    }
}







//  Function    :   PartB
//  Accepts     :   Tag ID filtering value from exam page
//  Description :   The function will query for all tags where the tagID field value ENDS with your filter value
//                  ie.  If the entered filter value is SHOP, then one of the returned tagIDs would be COSHOP
//
//                  You shall retrieve all fields as indicated by the column headers in the exam page.  Note that
//                  the difference squared column does not exist in the table.  You must calculate the expected value
//                  for each row.  This may be accomplished in either the SQL query or in your PHP processing.
//                  ie. For tagID = COSHOP, tagMin = 20, tagMax = 268, Difference Squared = 61504
// 
//                  Process the result set from the query and return the data to the client.
//		    NOTE: Do NOT build a table on the server.  That task is to be completed in the JS file.
//
//                  Upon completion, construct and return the following as an associative data element : 
//                  $respData["partB"] = "Your_result_set_containing_table_rows"
//
//		    Put your PartB code in the blank section following this part of the text


function PartB($id)
{
    global $output;
    $query = "SELECT tagDesc,tagMin,tagMax
              FROM tags WHERE tagID like '%$id'";
    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $output["tagsId"] = $results->fetch_all();
    } else {
        $output["tagsId"] = [];
        error_log("Something went wrong with the query!");
    }
}


// IF THE CONDITIONS DESCRIBED ABOVE HAVE BEEN SATISFIED, YOU SHOULD NOT BE ABLE TO REACH THIS CODE
// No Match condition, return whatcha got/get

$out["status"] = "LabExamService:Error - no match, see parameters";
$out["get"] = $_GET;
$out["post"] = $_POST;
echo json_encode($out);