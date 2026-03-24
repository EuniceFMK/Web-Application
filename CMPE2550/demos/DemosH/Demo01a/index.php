<?php
   require_once('externalData.php');
   // Single Line Comment : PHP: Hypertext preprocessor: Server side scriting language
    // PHP8.3 Dec 2023
    // PHP8.4 Dec 2024
    # Single Line comment: Another version to add single line comment
    /*
        Multi line comments
    */
   
    // PHP files need to be saved with .php extension
    # PHP code will parse on server side, but the final output will be sent back to client side
    // error_log() will put an entry inside error log file: It will be your friend for debugging
    error_log("At the top of the page");
    echo "Stuff coming from PHP block"; 
    // Keep in mind, we need dollar sign in front of all idenfiers
    $name= "Harsimranjot";

    $numbers = array(1,2,3,4); // An array to store some numbers

    function UserData()
    {
        global $name; 
        // To access your global variables inside the function, you need to mention the scope
        echo "<br> Inside Function Your name is $name";

        # Or Super global array $GLOBALS 
        echo "<br> Inside Function Your name is ".  $GLOBALS['name'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "Demo 01 a"; ?></title>
    </head>
    <body>
        <h1>CMPE 2550 Demo 01a</h1>

        <?php
            // You can directly use your storage elements like variables directly inside any string.
            echo "<p>Printing the value of name variable: $name </p>";
            // Alternative 1: You can concat operator . [dot] operator is used
            echo "<p> Printing the value of name variable: " . $name . "</p>";
            
            echo "Your age = $age";
            // Call your function here
            UserData();

            echo "<p>Value at index position 0 : $numbers[0] </p>";

            // Calling function from external library
            $collection = MakeArray(8);

            $list= "<ul>";
            foreach($collection as $key=>$value)
            {
                $list = $list . "<li> $key : $value </li>";
            }

            $list .= "</ul>";
            
            //printing the list on page
            echo $list;

        ?>


    <footer>
        <?php
            # Try to include stuff from another file
            // require_once() or include()

            require_once('footer.php');
            //include('footer1.php');

            /*
                The only difference is that include statement 
                generates a PHP alert warning only, but PHP script will keep
                running if that file is missing

                on the other hand, require_once() will generate a fatal error
                and terminates the script

                Most of the programmers use require_once- Recommended for all

            */
        ?>
    </footer>
    </body>
</html>