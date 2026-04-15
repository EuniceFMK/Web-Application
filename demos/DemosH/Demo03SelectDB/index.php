<?php
    // Connect your db file
    require_once('DbUtil.php');

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Demo 03</title>
    </head>
    <body>
        <header>
            <h1> Week 04: Day 03 : Demo 03: CMPE2550 A03 Working with Database</h1>
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
                echo $row['sid'] . " | " . $row['sname'] . " | ". $row['semail']. " | " . $row['sphone'];
            }

            ?>
        </main>

    </body>
</html>