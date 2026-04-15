<?php
require_once 'db.php';  // Copy your db.php into your exam folder 

//  Function    :   GetTag
//  Accepts     :   Tag value to retrieve
//  Description :   The function will query for the tag that has been specified by the input 
//                      from the user.  If found, details are returned as a string encoded table body
//                  Note:  Tag value should be exactly 6 characters long.

if (isset($_GET["getTag"]) && strlen($_GET["getTag"]) == 6) {
    echo json_encode(GetTag($_GET["getTag"]));
    die();
}

if (isset($_POST["tagManipID"]) ) {
    echo json_encode(ManipulateTagData($_POST["tagManipOP"], $_POST["tagManipID"]));
    die();
}
// NOTE: YOU MAY NEED TO CHAGE THE NAME(S) OF VARIABLES IN THE FOLLOWING FUNCTION TO MATCH 
// 		YOUR db.php FILE!
function GetTag($inputData)
{
    global $connection, $mysql_response;
    $cleanedData = $connection->real_escape_string(strip_tags(trim($inputData)));

    $query = "SELECT tagID, tagMin, tagMax from tags where tagID = '$cleanedData'";

    $respData["getTag"] = "";
    if ($results = mySqlQuery($query)) {
        while ($row = $results->fetch_assoc()) {
            $respData["getTag"] .= "<tr><td>" . $row['tagID']
                . "</td><td>" . $row['tagMin']
                . "</td><td>" . $row['tagMax']
                . "</td></tr>";
        }
    } else
        return $mysql_response[0];

    return $respData;
}





// Follow the service pattern that has been demo'd and enforced in class!  Clean data, perform operation, echo output...
//
// REST operations are not permitted!                 
//
// die(); the page after you echo back to the user!




//  Function    :   ManipulateTagData
//  Accepts     :   The tag modification operation (an insert, update or delete will be requested)
//                  The tagID to be manipulated
//  Description :   This function will cause a data modification of a specified tag to occur
//
//                  Note:   An insert may only take place if the tagID does not already exist.  You will check for this,
//                          and return an error message if the insert is unsuccessful.  For update and delete, the 
//                          operation will return an error message if the tag does not exist.  For any operation to 
//                          occur, the entered tag must be exactly 6 characters long.
//
//                  When inserting or updating a tag, you shall generate a random tagMin value between 30000 and 35000,
//                  and a random tagMax value between 40000 and 45000.
//
//                  All operations shall return a proper status message back to the user.  See the "working solution" for sample 
//                  messages, remembering to test out more than one path of operation.
//                  ie. Insert then update then update then update then delete
//                      Delete then delete then update then insert then insert
//                      etc.
//                  Basically make sure you are able to hit all of your status messages.
//
//                  Upon completion of the data modification, construct and return the following result string as an 
//                  associative data element : 
//                  $respData["ManipulateTagResponse"] = "Your_data_manipulation_status_message : # row(s) affected"
//                  See the functioning page for examples of expected messages.  Remember to test all permutations.
//
function ManipulateTagData($op, $inputData)
{
    global $connection, $mysql_response;
    $cleanedData = $connection->real_escape_string(strip_tags(trim($inputData)));
    

    if ($op == "insert") {
        $min = rand(40000, 45000);
        $max = rand(40000, 45000);
        $query = "INSERT INTO tags (tagID,tagMin,tagMax) 
              VALUES('$cleanedData','$min','$max')";

        $respData["ManipulateTagResponse"] = "";
        if(strlen($cleanedData) !=6 ){
             $respData["ManipulateTagResponse"] .= "TagID must be exactly 6 characters long: 0 rows affected ";
             return $respData;
             return;
        }
        if (($results = mySqlNonQuery($query)) > 0) {
            error_log("$results records were successfully updated");
            $respData["ManipulateTagResponse"] .= "Inserted " . $cleanedData . " with Min= " . $min . " and Max= " . $max . " " . $results . " row affected";
        } 
        else{
            $respData["ManipulateTagResponse"] .= "Something went wrong ";
        }
        return $respData;
    }


    if ($op == "delete") {
        $query = "DELETE FROM tags 
                 WHERE tagId='$cleanedData'";
        $result = -1;

        $respData["ManipulateTagResponse"] = "";
         if(strlen($cleanedData) !=6 ){
             $respData["ManipulateTagResponse"] .= "TagID must be exactly 6 characters long: 0 rows affected ";
             return $respData;
             return;
        }

        if (($result = mySqlNonQuery($query)) > 0) {
            error_log("$result records were successfully deleted");
            $respData["ManipulateTagResponse"] = "Deleted " . $cleanedData . " tag:" . $result . " row affected";

        } else if(($result = mySqlNonQuery($query)) ==0) {
            $respData["ManipulateTagResponse"] = $cleanedData . " tag not foud for delete: " . $result . " row affected";
        }
        else{
             error_log("There was a problem with the query!");
            $respData["ManipulateTagResponse"] = "There was a problem with the query!";
        }
        return $respData;
    }

    if ($op = "update") {
        

        $min = rand(40000, 45000);
        $max = rand(40000, 45000);
        $query2 = "UPDATE tags
                       SET tagMin='$min', 
                           tagMax='$max'
                       WHERE tagID= '$cleanedData'";


        $result = -1;
        $respData["ManipulateTagResponse"] = "";
         if(strlen($cleanedData) !=6 ){
             $respData["ManipulateTagResponse"] .= "TagID must be exactly 6 characters long: 0 rows affected ";
             return $respData;
             return;
        }
        if (($result = mySqlNonQuery($query2)) > 0) {

            error_log("$result records were successfully updated");
            $respData["ManipulateTagResponse"] .= "Updated " . $cleanedData . " with Min= " . $min . " and Max= " . $max . " " . $result . " row affected";
        } else if(($result = mySqlNonQuery($query2)) ==0) {
            $respData["ManipulateTagResponse"] = $cleanedData . " tag not foud for Update: " . $result . " row affected";
        }
        else{
           error_log("There was a problem with the query!");
            $respData["ManipulateTagResponse"] = "There was a problem with the query!";
        }
        return $respData;
    }
}




// THE FOLLOWING SHOULD NOT OCCUR IF YOU HAVE COMPLETED THE ABOVE CORRECTLY.
// No Match condition, return whatcha got/get
$out["status"] = "LabExamService:Error - no match, see parameters";
$out["get"] = $_GET;
$out["post"] = $_POST;
echo json_encode($out);
die();