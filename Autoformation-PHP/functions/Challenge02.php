<?php

function calculateArea($width,$hight){
    $Area=$width*$hight;
    return $Area;
}
$totalArea=calculateArea(10,10);
echo "The total area is $totalArea square units";
?>
