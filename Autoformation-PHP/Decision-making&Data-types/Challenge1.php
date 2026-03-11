<?php

$TeaPrice = 10;
do {
    $Qantite = (int) readline("How many cups of tea do you want? : ");
    if ($Qantite <= 0) {
        echo "Invalid value \n";
    }
} while ($Qantite <= 0);

do {
    $isStudent = readline("Are you a student? (yes/no) : ");
    if ($isStudent !== "yes" && $isStudent !== "no") {
        echo "Invalid value \n";
    }
} while ($isStudent !== "yes" && $isStudent !== "no");

$total = $TeaPrice*$Qantite;

if($Qantite > 5){
    $total-= $Qantite;
}

if ($isStudent==="yes"){
    $total *= 0.8;
}

echo "the tea bill is : $total DH";

?>

