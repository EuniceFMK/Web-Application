<?php
/*
    Programmer:   Ngadjou Eunice Fmukam
    Date:         January 30, 2026
    FileName:     service.php
    Description:  PHP service file to handle AJAX requests for ICA 3.
    */
require_once "db.php";

$output = array();
$clean = array();

// Sanitize and clean input parameters
foreach ($_GET as $key => $value) {
    $clean[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] =
        trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
}

// Handle actions based on the 'action' parameter
if (isset($clean["action"])) {
    if ($clean["action"] == "GetAllAuthor")
        GetAllAuthor();
    if ($clean["action"] == "GetBooksByAuthors" && isset($clean["au_id"]))
        GetBooksByAuthors($clean["au_id"]);
    if ($clean["action"] == "DeleteBooksByAuthors" && isset($clean["titleID"]))
        DeleteBooksByAuthors($clean["titleID"]);
    if ($clean["action"] == "EditBook" && isset($clean["titleID"]))
        EditBookbyTitleID($clean["titleID"]);
    if ($clean["action"] == "GetAllTypes")
        GetAllTypes();
}

echo (json_encode($output));
die();

/**
 * FunctionName:    GetAllAuthor
 * Inputs:          None
 * Outputs:        None
 * Decription:     Retrieves all authors from the database and stores them in the global output array.
 */
function GetAllAuthor()
{
    global $output;
    $query = "SELECT au_id,au_lname,au_fname,phone FROM authors ORDER BY au_lname";
    $queryOutput = null;
    if ($queryOutput = mySqlQuery($query)) {
        $output["authors"] = $queryOutput->fetch_all();
        error_log(json_encode($output["authors"]));
    } else {
        error_log("Something went wrong with the query!");
    }
}
/**
 * FunctionName:    GetBooksByAuthors
 * Inputs:          $au_id - Author ID to retrieve books for
 * Outputs:        None
 * Decription:     Retrieves books by a specific author and stores them in the global output array.
 */

function GetBooksByAuthors($au_id)
{
    global $output;
    $query = "SELECT t.title_id,t.title, t.type,t.price 
            FROM titles t 
            JOIN titleauthor ta
            ON t.title_id=ta.title_id 
            WHERE ta.au_id='$au_id'";

    $results = mySqlQuery($query);
    if ($results && $results->num_rows > 0) {
        $output["books"] = $results->fetch_all();

    } else
        $output["books"] = [];

}

/**
 * FunctionName:    DeleteBooksByAuthors
 * Inputs:          $titleID - ID of the book to be deleted
 * Outputs:        None
 * Decription:     Deletes a book and its associated author records from the database.
 */
function DeleteBooksByAuthors($titleID)
{
    global $clean, $output;
    $query2 = "DELETE from titleauthor where title_id liKe '$titleID'";
    $query1 = "DELETE FROM titles WHERE title_id ='$titleID'";
    error_log("$query1" . " $query2");

    $result = -1;

    if (($result = mySqlNonQuery($query2)) >= 0 && ($result = mySqlNonQuery($query1)) >= 0) {
        error_log("$result records were successfully deleted");
        $output["status"] = "$result records were sucessfully deleted ";
    } else {
        error_log("There was a problem with the query!");
        $output["status"] = "There was a problem with the query!";
    }
}

/**
 * FunctionName:   EditBookbyTitleID
 * Inputs:         $titleID - ID of the book to be edited
 * Outputs:        None
 * Decription:    Updates a book's details in the database based on the provided title ID and input parameters, and stores the status of the operation in the global output array.
 */
function EditBookbyTitleID($titleID)
{
    global $clean, $output;
    $title = $clean["title"];
    $price = $clean["price"];
    $type = $clean["type"];
    $query = "UPDATE titles
              SET title = '$title',
                  type ='$type',
                  price ='$price'
              WHERE title_id = '$titleID'";
    error_log($query);

    $result = -1;

    if (($result = mySqlNonQuery($query)) >= 0) {
        error_log("$result records were successfully updated");
        $output["status"] = "$result records were successfully updated";
    } else {
        error_log("There was a problem with the query!");
        $output["status"] = "There was a problem with the query!";
    }
    error_log("EDIT CALLED");
    error_log(json_encode($clean));
}

/**
 * FunctionName:   GetAllTypes
 * Inputs:         None
 * Outputs:        None
 * Decription:     Retrieves all book types from the database and stores them in the global output array.
 */
function GetAllTypes()
{
    global $output;
    $query = "SELECT distinct type FROM titles ORDER BY type ";
    $queryOutput = null;
    if ($queryOutput = mySqlQuery($query)) {
        $output["types"] = $queryOutput->fetch_all();
        error_log(json_encode($output["types"]));
    } else {
        error_log("Something went wrong with the query!");
    }
}