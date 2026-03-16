<?php 
include 'db.php';
$title= "Clean Code";
$author="Ahmed Sakhi";
$price=350.60;

$sql="INSERT INTO library_books (title,author,price) VALUES(:title,:author,:price)";

$stmt = $pdo->prepare($sql);

$stmt->execute (['title'=>$title,'author'=>$author,'price'=>$price]);

$lastID= $pdo->lastInsertId();
echo"Success! Book added with ID: $lastID";

?>
