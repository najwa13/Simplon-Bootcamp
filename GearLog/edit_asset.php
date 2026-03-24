<?php 
require 'db.php';
include 'header.php';

$valide = "";
$error = "";

$serial = $_GET['serial'] ?? '';

if(!$serial){
    header("Location: index.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM assets WHERE serial_number=?");
$stmt->execute([$serial]);
$asset = $stmt->fetch();

if(!$asset){
    header("Location: index.php");
    exit;
}


$sql2="SELECT * FROM categories";
$stmt2= $pdo->prepare($sql2);
$stmt2->execute();
$categories=$stmt2->fetchAll(PDO::FETCH_ASSOC);


if($_SERVER['REQUEST_METHOD']==='POST'){

    $device_name = $_POST['device_name'];
    $status = $_POST['status'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    try {

        $stmt = $pdo->prepare("
            UPDATE assets 
            SET device_name=:device_name, status=:status, price=:price, category_id=:category_id 
            WHERE serial_number=:serial_number
        ");

        $stmt->execute([
            'device_name'=>$device_name,
            'status'=>$status,
            'price'=>$price,
            'category_id'=>$category_id,
            'serial_number'=>$serial
        ]);

        $valide = "Asset updated successfully";

        
        $asset['device_name']=$device_name;
        $asset['status']=$status;
        $asset['price']=$price;
        $asset['category_id']=$category_id;

    } catch(PDOException $e){
        $error = "Error updating asset!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Asset</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">
            Edit Asset:
        </h2>

        <?php if($valide){ ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($valide); ?>
            </div>
        <?php }?>    

        <?php if($error){ ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php }?>

        <form method="POST" class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Device Name</label>
                <input type="text" name="device_name" class="form-control"
                    value="<?= htmlspecialchars($asset['device_name']); ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Deployed" <?= ($asset['status']=="Deployed") ? "selected" : "" ?>>Deployed</option>
                    <option value="Under Repair" <?= ($asset['status']=="Under Repair") ? "selected" : "" ?>>Under Repair</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price</label>
                <input type="number" name="price" step="0.01" min="0" class="form-control"
                    value="<?= htmlspecialchars($asset['price']); ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <?php foreach($categories as $c){ ?>
                        <option value="<?= $c['id']; ?>"
                            <?= ($asset['category_id']==$c['id']) ? "selected" : "" ?>>
                            <?= htmlspecialchars($c['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-dark px-4">Update</button>
                <a href="index.php" class="btn btn-outline-dark px-4">Back</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>