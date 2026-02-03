<?php
/*
    Programmer:   Ngadjou Eunice Fmukam
    Date:         January 30, 2026
    FileName:     db.php
    Description:  PHP database connection and query functions for ICA 3.
    */
$connection = null;
mySQLConnect();

/**
 * FunctionName:    mySQLConnect
 * Inputs:          None
 * Outputs:        None
 * Decription:     Establishes a connection to the MySQL database.
 */
function mySQLConnect()
{
    global $connection;
    $connection = new mysqli(
        "localhost",
        "efmukamt1251_tester",
        "EuniceFMK2204",
        "efmukamt1251_Test"
    );
    if ($connection->error)
        error_log("Error {$connection->errno}:{$connection->error}");
    else
        error_log("Connection created successfully!");
}

/**
 * FunctionName:    mySqlQuery
 * Inputs:          $query - SQL query string to be executed
 * Outputs:        mysqli_result object on success, false on failure
 * Decription:     Executes a MySQL query and returns the result.
 */
function mySqlQuery($query)
{
    global $connection;
    if ($connection == null) {
        error_log("mySqlQuery : No connection established");
        return false;
    }

    $results = false;
    if (!($results = $connection->query($query))) {
        error_log("mySqlQuery: {$connection->errno} : {$connection->error}");
        error_log($query);
        return false;
    }
    return $results;
}

function mySqlNonQuery($query)
{
    global $connection;
    if ($connection == null) {
        error_log("mySqlQuery : No connection established!");
        return -1;
    }

    $result = 0;

    if (!($result = $connection->query($query))) {
        error_log("mySqlQuery : $connection->errno : $connection->error");
        error_log($query);
        return -1;
    }

    $result = $connection->affected_rows;
    return $result;
}