<?php
require_once "db.php";

$output = array();
$clean = array();

foreach ($_GET as $key => $value) {
    $clean[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] =
        trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
}

if (isset($clean["action"])) {
    if ($clean["action"] == "GetAllAuthor")
        GetAllAuthor();
    if($clean["action"]=="GetBooksByAuthors"&& isset($clean["au_id"]))
        GetBooksByAuthors($clean["au_id"]);
}

echo (json_encode($output));
die();

function GetAllAuthor()
{
    global $output;
    $query = "SELECT au_id,au_lname,au_fname,phone FROM authors";
    $queryOutput = null;
    if ($queryOutput = mySqlQuery($query)) {
        $output["authors"] = $queryOutput->fetch_all();
        error_log(json_encode($output["authors"]));
    }
    else{
        error_log("Something went wrong with the query!");
    }
}

function GetBooksByAuthors($au_id){
    global $output;
    $query="SELECT t.title, t.type,t.price 
            FROM titles t 
            JOIN titleauthor ta
            ON t.title_id=ta.title_id 
            WHERE ta.au_id='$au_id'";

    $results = mySqlQuery($query);
    if($results&&$results->num_rows>0){
        $output["books"]=$results->fetch_all();

    }
    else
        $output["books"]=[];

}