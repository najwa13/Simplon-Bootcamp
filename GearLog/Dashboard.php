<?php 
require 'db.php';
include 'header.php';

$stmt = $pdo->prepare("SELECT SUM(price) as total_price FROM assets");
$stmt->execute();
$totalPrice = $stmt->fetch()['total_price'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as total_assets FROM assets");
$stmt->execute();
$totalAssets = $stmt->fetch()['total_assets'];

$stmt = $pdo->prepare("SELECT COUNT(*) as repair FROM assets WHERE status='Under Repair'");
$stmt->execute();
$repair = $stmt->fetch()['repair'];

$stmt = $pdo->prepare("SELECT COUNT(*) as deployed FROM assets WHERE status='Deployed'");
$stmt->execute();
$deployed = $stmt->fetch()['deployed'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container py-5">

    <h2 class="mb-4 text-center"><i class="bi bi-clipboard2-data"></i> Dashboard</h2>

    <div class="row g-4">

        
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 rounded ">
                <h6 class="text-muted">Total Value</h6>
                <h3><?= $totalPrice ?> DH</h3>
            </div>
        </div>

        
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 rounded ">
                <h6 class="text-muted">Total Assets</h6>
                <h3><?= $totalAssets ?></h3>
            </div>
        </div>

       
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 rounded ">
                <h6 class="text-muted">Under Repair</h6>
                <h3 class="text-danger"><?= $repair ?></h3>
            </div>
        </div>

        
        <div class="col-md-3">
            <div class="card text-center shadow-sm p-3 rounded ">
                <h6 class="text-muted">Deployed</h6>
                <h3 class="text-success"><?= $deployed ?></h3>
            </div>
        </div>

    </div>

</div>

</body>
</html>