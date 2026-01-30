<?php
$connection = null;

mySQLConnect();

function mySQLConnect()
{
    global $connection;

    $connection = new mysqli("localhost",
                                "efmukamt1251_tester",
                                "My_1st_Test",
                                "efmukamt1251_Test");
    
    if ($connection->error)
            error_log("Error {$connection->errno} : {$connection->error}");
    else
        error_log("Connection created successfully!");
    
        
}

function mySqlQuery( $query )
{
    global $connection;

    if ($connection == null)
    {
        error_log("mySqlQuery : No connection established!");
        return false;
    }
        
    $results = false;

    if (!($results = $connection->query( $query )))
    {
        error_log("mySqlQuery : $connection->errno : $connection->error");
        error_log($query);
        return false;
    }

    return $results;
}

