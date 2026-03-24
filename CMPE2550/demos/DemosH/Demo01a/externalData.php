<?php 
    $age= 42;

    # MakeArray: function will create an array of stars
    # Input: Int value : number of stars
    #Output: Return an array of stars
    function MakeArray($quantity)
    {
        $stars = array(); // creating an array which empty one

        for($i = 0; $i <= $quantity; ++$i)
        {
            $stars[] =  "*"; 
        }
        return $stars;
    }
?>