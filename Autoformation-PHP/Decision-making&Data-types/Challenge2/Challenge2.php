<?php
$age = (int) readline("How old are you? : ");

if ($age<12){
    $price = 20;
}elseif($age>=12 && $age<= 18){
    $price = 40;
}elseif($age>60){
    $price=30;
}else{
    $price=60;
}

if($price===20){
   echo "the price is : $price DH Special: Children's Menu included!"; 
}else{
    echo "the price is : $price";

}


?>