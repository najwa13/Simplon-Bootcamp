<?php
session_start();
require 'db.php';

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM prompts WHERE id=?");
    $stmt->execute([$id]);
}

header("Location: admin_index.php");
exit();
?>