<?php

// Requirement : function PartB 
// Accepts     : A single value representing the "array length", the "random seed              
//               value", and the "minimum random value"
// Returns     : Sorted array of randomly generated values
// Description : Initializes the random number generator with the "random seed value"
//               Populates an array with "array length" number of random values
//               Each value will be between the "maximum random value" and 100
//               The values will then be sorted and returned to the caller
session_start();
// Put function PartB below here :
function PartB($seed)
{
    srand($seed);
    $arr = array();
    for ($i = 0; $i < $seed; $i++) {
        $arr[$i] = rand($seed, 100);
    }
    sort($arr);   // Sort the array to randomize the order
    return $arr;    // Return the array of random numbers   
}

