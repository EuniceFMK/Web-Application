<?php

require_once "db.php";

$output = array();
$clean = array();

// Sanitize and clean input parameters
function CleanCollection($input)
{
    global $connection;
    $cleanA = array();
    foreach ($input as $key => $value)
        if (is_array($value)) {
            $cleanA[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))] = CleanCollection($value);
        } else {
            $cleana[trim($connection->real_escape_string(strip_tags(htmlspecialchars($key))))]
                = trim($connection->real_escape_string(strip_tags(htmlspecialchars($value))));
        }

    return $cleanA;
}

$cleanGet = CleanCollection($_GET);
error_log(json_encode($cleanGet));


