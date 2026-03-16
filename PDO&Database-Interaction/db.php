<?php

$host = "localhost";
$dbname = "autoformation_sql";
$user = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo'Database connected successfully';

} catch (PDOException $e) {

    die("Database connection failed: " . $e->getMessage());

}

?>