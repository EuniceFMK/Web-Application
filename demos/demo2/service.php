<?php

//Log the incoming GET request for debugging purposes
error_log(json_encode($_GET));

//Clean all input data from the user
$clean = array();
foreach ($_GET as $key => $value)
    $clean[trim(strip_tags(htmlspecialchars($key)))] = strip_tags(htmlspecialchars($value));

$output = array();   //Create an array for output data to be sent back to the client

//Check which action to perform based on the 'action' parameter
if (isset($clean["action"])) 
{
    if ($clean["action"] == "CalcArea") 
    {
        $output["area"] = CalculateArea();
        $output["status"] = "Area calculation successful.";
    }
    else if($clean["action"] == "CalcVol")
    {
        $output["volume"] = CalculateVolume();
        $output["status"] = "Volume calculation successful.";
    }
    else{
        $output["status"] = "The requested action is not recognized!";
    }

}
//Json encode the output data for the client and echo it as the return to the ajax call
echo json_encode($output);

die();   //Terminate the script after sending the response

/**
 * FunctionName:   CalculateArea
 * Inputs:         None
 * Outputs:        Area of Circle
 * Decription:     Calculates the area of circle using the radius applied 
 *                 in the included global clean data array
 * @return float
 */
function CalculateArea()
{
  global $clean, $output;

  return pi()*pow($clean["radius"],2);
}

/**
 * FunctionName:   CalculateVolume
 * Inputs:         None
 * Outputs:        Volume of Circle
 * Decription:     Calculates the volume of circle using the radius applied
 *                 in the included global clean data array
 * @return float
 */
function CalculateVolume()
{
  global $clean,$output;
  return 4*pi()*pow($clean["radius"],3)/3;
}   
?>