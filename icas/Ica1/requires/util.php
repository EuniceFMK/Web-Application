<?php
/**
 * Programmer: Eunice De Grace Fmukam Ngadjou
 * Date: January 13, 2025   
 * Description: ICA 01 - PHP Basics for CMPE 2550 - Web Application Development
 * File: util.php
 */

/**
 * Summary of generateNumbers
 * Generates an array of 10 random numbers between 0 and 9
 * @return array
 */
function generateNumbers()
{
    $numbers = array();             // Initialize an empty array
    for ($i = 1; $i < 11; $i++) {
        $numbers[] = $i;  // Append numbers 1 to 10
    }
    shuffle($numbers);   // Shuffle the array to randomize the order
    return $numbers;    // Return the array of random numbers
}

/**
 * Summary of MakeList
 * Creates an ordered HTML list from an array of numbers
 * @param array $num
 * @return string
 */
function MakeList($num)
{
    $output = "<ol>";   // Start the ordered list
    for ($i = 0; $i < count($num); $i++) {
        $output .= "<li>" . ($num[$i]) . "</li>";   // Add each number as a list item
    }
    $output .= "</ol>";   // Close the ordered list
    return $output;     //  Return the HTML string
}
?>