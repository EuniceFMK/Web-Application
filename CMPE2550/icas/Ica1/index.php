
<?php
/**
 * Programmer: Eunice De Grace Fmukam Ngadjou
 * Date: January 13, 2025   
 * Description: ICA 01 - PHP Basics for CMPE 2550 - Web Application Development
 * File: index.php
 */
require_once 'requires/util.php';   // Include the utility functions

$clean = array();        // Cleaned input array
foreach ($_GET as $key => $value)
    $clean[trim(strip_tags($key))] = trim(strip_tags($value));

$status = "Status: "; // Status string initialization
$message = "";           // Message initialization

/* Part 4: Form Processing */
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $hm = "";       // Initialize really string
   
    if (
        isset($clean["Name"]) && isset($clean["Hobby"]) && isset($clean["howmuch"])
        && strlen($clean["Name"]) > 0 && strlen($clean["howmuch"]) > 0 && strlen($clean["Hobby"]) > 0
    ) {
        $name = $clean["Name"];    // Get the name
        $hobby = $clean["Hobby"];    // Get the hobby
        $howm = $clean["howmuch"];  // Get how much
        for ($i = 0; $i < $howm; $i++) {
            $hm .= " really";       // Append " really" to the string
        }
        $message = $name . $hm . " likes " . $hobby;        // Create the message
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>ICA O1_php</header>
    
    <section class="server_info">
        <h3>Part 1: Server Info</h3>
        <div class="row">
            <div class="label">Your IP Address is:</div>
            <div class="value"><?php echo $_SERVER["REMOTE_ADDR"]; ?></div>
        </div>
        <div class="row">
            <div class="label">$_GET Evaluation:</div>
            <div class="value">
                Found <?php echo count($_GET); ?> in the $_GET
            </div>
        </div>
        <div class="row">
            <div class="label">$_POST Evaluation:</div>
            <div class="value">
                
                Found <?php echo count($_POST); ?> in the $_POST
            </div>
        </div>
        <?php
        $status .= "+ServerInfo ";
        ?>
    </section>
    <section>
        <h3>Part 2: Form Processing</h3>
        <div class="row">
            <div class="label">$_GET Contents:</div>
            <div class="value">
                <?php echo "<ul>";
                // Loop through the cleaned GET array and display key-value pairs
                foreach ($clean as $Key => $value) {
                    echo "<li>[{$Key}] = {$value}</li>";
                }
               
                echo "</ul>"; ?>
            </div>
        </div>
        <?php
        // Check if there are any GET parameters
        if (count($_GET) > 0) {
            $status .= "+GetData ";
        }
        ?>
        <?php
        $status .= "+ProcessForm ";   // Update status
        ?>
    </section>
    <section>
        <h3>Part 3: Array Generation</h3>
        <div class="row">
            <div class="label">Array Generated:</div>
            <aside class="value tab">
                <?php
                // Generate random numbers and display them as a list
                $randomnum = generateNumbers();     // Generate random numbers
                $status .= "+GenerateNumbers ";    // Update status
                echo MakeList($randomnum);   // Display the list
                $status .= "+MakeList ";         // Update status
                ?>
            </aside>
        </div>
        <?php
        $status .= "+ShowArray ";    // Update status
        ?>
    </section>
    <section>
        <h3>Part 4: Form Processing</h3>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
            
            <label for="">Name: </label>
            <input type="text" name="Name" value="<?php echo $name; ?>"> <br>
            <label for="">Hobby: </label>
            <input type="text" name="Hobby" value="<?php echo $hobby; ?>"> <br>
            <label for="">How Much I like it: </label>
            <input type="Range" name="howmuch" min="0" max="10" value="<?php echo $howm; ?>"> <br>
            <input type="submit" name="Submit" value="Go Now !"> <br>
            
        </form>
    </section>
    <section id="howmuch-sec">
        <?php
        if ($message != "") {
            echo $message;
        }
        ?>
    </section>
    <section id="status-sec">
        <?php
        echo $status;

        ?>
    </section>
    <?php
    include 'includes/footer.php';
    ?>
</body>

</html>
