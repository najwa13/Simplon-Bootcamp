<?php 
function manualReverse($text){
    $reverse="";
    for($i=strlen($text)-1;$i>=0;$i--){
        $reverse.=$text[$i];
    }
    return $reverse;
}
$test="najwa";
$backward = manualReverse($test);

echo "the reverse of $test is : $backward";

?>