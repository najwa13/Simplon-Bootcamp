<?php 

function isAdut($age){
    if($age>=18){
        return true;
    }else{
        return false;
    }
}
$Access=isAdut(20);
if($Access==true){
    echo "Access Granted.";
}else{
    echo "Access Denied.";
}
?>