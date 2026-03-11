<?php 
function multiplyNumbers($a, $b){
    if(is_numeric($a)&&is_numeric($b)){
       $product= $a*$b;
       echo "the product is: $product";
    }else{
        echo "Error: Invalid Input.";
    }
}
multiplyNumbers(2,10);
?>