<?php
    // Connect your db file
    require_once('DbUtil.php');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Demo 04</title>
    </head>
    <body>
        <header>
            <h1> Week 05: Day 02 : Demo 04: CMPE2550 A03 Working with Database DML part</h1>
        </header>

        <main>
            <?php
            // Step 1: Connect to DB
            mySQLConnection();
            error_log("Connectin part done");
            // Step 2: Prepare your query
        // Part 1: Direct query
            $query = "select * from Students";

            error_log("Query". $query);

            $myResultSet = mySelectQuery($query);

            error_log("Before While");
            // fetch_assoc(): will return one row at a time as an object
            echo "Sid  Sname    Semail   Phone <br>";
            while($row = $myResultSet-> fetch_assoc() )
            {
                error_log("Inside while loop");
                echo "<br>";
                echo "<button id='".$row['sid']."' onclick=books(".$row['sid'].") > RetrieveBooks</Button>". $row['sid'] . " | " . $row['sname'] . " | ". $row['semail']. " | " . $row['sphone'];
            }



            echo " <hr>DML Part";

            $query = "delete from Students "; //Make sure to include space at the end of string when you are splitting your query across multiple lines

            $query.= "where sid= 1";

            error_log($query);

            $response = mysqlNonQuery($query);
            
            if($response == false)
            {
                echo "<br>Error". $mysql_response;
            }
            else
            {
                echo "<br>Delete operation has affected $response rows";
            }


            echo "<br>Tesing Delete with Stored procedure";
            // Test it with Stored procedure
            $sid = 3;
            $query = "call DeleteStudent($sid)";  // Make sure to use ' or " with string value
            
            error_log($query);
            // Execute query directly from PHP code

            $dbResponse = mysqlNonQuery($query);
            
            if($dbResponse == false)
            {   
                echo $dbResponse;
            }
            else
            {
                echo "<br>Delete operation has affected $dbResponse rows";
            }



            echo "<br>Tesing Update with Stored procedure";
            // Test it with Stored procedure
            $sid = 4;
            $sname="Har\simran'jot";

            // real_escape_string();
            $sname = $mysql_connection->real_escape_string(  trim(strip_tags($sname)) );
            error_log($sname);

            $query = "call UpdateStudent('$sname', $sid)";  // Make sure to use ' or " with string value
            
            error_log($query);
            // Execute query directly from PHP code

            $dbResponse = mysqlNonQuery($query);
            
            if($dbResponse == false)
            {   
                echo $dbResponse;
            }
            else
            {
                echo "<br>Update operation has affected $dbResponse rows";
            }
            ?>


        </main>
            
    </body>
</html>

<script>
    function books(id1)
    {
        console.log(id1);
    }

</script>