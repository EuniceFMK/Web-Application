<?php
// Database connection
$mysql_connection = null;

$mysql_response = "";
/****************************************
 * Function Name    : mySQLConnection: To connect to DB
 * Inputs           : No
 * Output           : No
 ****************************************/
function mySQLConnection()
{
    // Grab hold on to connection variable 
    //because you can not access global variable directly inside a function
    global $mysql_connection;

    // Try to connect to Db here
    /*
    1. Database you would like to connect 
    2. Username
    3. Password
    4. DB server URL
    */
                            //      DB Server,   Username              , Password,         DBName
    $mysql_connection = new mysqli("localhost", "aulakhha_2550A03Admin", "WY!4{eGk#^HK", "aulakhha_2550A03DB");

    if($mysql_connection -> connect_errno)
    {
        error_log("Connection error");
        die();
    }
    // if you are here
    error_log("Connection is successful");

}

/****************************************
 * Function Name    : mySelectQuery - To execute select query
 * Inputs           : Query 
 * Output           : result set or false
 ****************************************/

function mySelectQuery($myquery)
{
    // Grab hold to global varaible inside function
    global $mysql_connection;

    // query function will return result set
    // or false 

    if( !($results = $mysql_connection ->query($myquery)) )
    {
        // Error 
        error_log("Error while executing query");
        die();
    }
    else
    {
        // All good
        error_log("Inside DBUTIL before returning result set");
        return $results;
    }
}
// Testing my function 

//mySQLConnection();

/****************************************
 * Function Name    : mysqlNonQuery - To execute DML queries : Insert, update or delete
 * Inputs           : Query 
 * Output           : NumberofrowsAffected or false
 ****************************************/
function mysqlNonQuery($myDMLQuery)
{
    // Grab hold to global varaible inside function
    global $mysql_connection, $mysql_response;

    // validate your connection that you have an active connection in place
    if($mysql_connection==null)
    {
        error_log("No active connection. Make sure to establish a connectin first");
        $mysql_response= "No active connection. Make sure to establish a connectin first";
        return false;
    }
    if(!($result = $mysql_connection->query($myDMLQuery)))
    {
        error_log("Error while executing query");
        $mysql_response ="Error: $mysql_connection->errno : $mysql_connection->error "; 
        return false;
    }
    // if you are here, all good

    error_log("Success");
    return $mysql_connection-> affected_rows;  // affected_rows will give you the number of rows affected.
}

?>