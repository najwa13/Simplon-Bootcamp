<?php
require 'db.php';

$serial = $_GET['serial'] ?? '';

if($serial){
    $stmt = $pdo->prepare("DELETE FROM assets WHERE serial_number=?");
    $stmt->execute([$serial]);
}

header("Location: index.php");
exit;