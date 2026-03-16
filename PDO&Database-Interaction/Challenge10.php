<?php 
include 'db.php';

$sql="SELECT * FROM categories";
$stmt= $pdo->prepare($sql);
$stmt->execute();
$results=$stmt->fetchall(PDO::FETCH_ASSOC); 
?>
<!DOCTYPE html>
<html lang="en">
<body><br>
    <select>
        <?php foreach ($results as $result){?>
        <option value="<?php echo $result['id'];?>"><?php echo $result['name'] ;?></option>
        <?php }?>
    </select>
    
</body>
</html>
