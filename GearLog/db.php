<?php 
$host="localhost";
$dbname="gearlog";
$user="root";
$password="";

try{

    $pdo=new PDO("mysql:host=$host;dbname=$dbname",$user,$password);
    $pdo-> setattribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
}
catch(PDOException $e){
    die("Database connection failed: ".$e->getMessage());
}

?>