<?php 
$evenNum=[];
for($i=1;$i<=50;$i++){

    if($i%2==0){
        $evenNum[]=$i;
    }
}
echo "Total even numbers:" . count($evenNum);
?>