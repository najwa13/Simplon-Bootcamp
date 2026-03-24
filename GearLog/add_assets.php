<?php 
require 'db.php';
include 'header.php';

$valide = "";
$error = "";

if($_SERVER["REQUEST_METHOD"]=== "POST"){

    $serial_number=$_POST["serial_number"]; 
    $device_name=$_POST["device_name"];
    $status=$_POST["status"];
    $price=$_POST["price"];
    $category_id =$_POST["category_id"];

    $sql1="INSERT INTO assets (serial_number,device_name,status,price,category_id) 
    VALUES(:serial_number,:device_name,:status,:price,:category_id)";


    try {


         $stmt=$pdo->prepare($sql1);

         $stmt->execute([
             'serial_number'=>$serial_number,
             'device_name'=>$device_name,
             'status'=>$status,
             'price'=>$price,
             'category_id'=>$category_id
    ]);

        $valide = "Asset added successfully";

    } catch(PDOException $e){

        $error = "Error adding asset!";

    }
}

$sql2="SELECT * FROM categories";
$stmt= $pdo->prepare($sql2);
$stmt->execute();
$results=$stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Assets</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">Add Asset</h2>

        <?php if($valide){ ?>
            <div class="alert alert-info text-center">
                <?= htmlspecialchars($valide) ;?>
            </div>
        <?php }?>    
       <?php if($error){?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error) ;?>
            </div>
        <?php }?>
        <form method="POST" class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Serial Number</label>
                <input type="text" name="serial_number" class="form-control" placeholder="ex: SN-A123" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Device Name</label>
                <input type="text" name="device_name" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Deployed">Deployed</option>
                    <option value="Under Repair">Under Repair</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Price</label>
                <input type="number" name="price" step="0.01" min="0" class="form-control" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <?php foreach ($results as $result){ ?>
                        <option value="<?= $result['id']; ?>">
                            <?= htmlspecialchars($result['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-dark px-4 ">Add </button>
                <a href="index.php" class="btn btn-outline-dark px-4">Back</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>