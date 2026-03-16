<?php 
include 'db.php';

 $minPrice=100;

$sql="SELECT * FROM library_books WHERE price>:price;";

$stmt = $pdo->prepare($sql);

$stmt->execute(['price' => $minPrice]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous"></head>
<body><br>
    <h2>Books with price greater than <?php echo $minPrice?></h1>
    <ul class=list-group>
        <?php foreach ($results as $row){?>
        <li class="list-group-item"><?php echo $row['title']?></li>
        <?php }?>
    </ul>
</body>
</html>