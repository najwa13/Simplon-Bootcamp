<?php 

$products = [
    'tech' => ['Phone', 'Laptop', 'Tablet'],
    'furniture' => ['Chair','Desk']
];

$filtredCategory = isset($_GET['category']) ? $_GET['category'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

?>

<!DOCTYPE html>
<html>
<head>
<title>Product Catalog</title>
</head>

<body>

<h3>Category</h3>

<a href="challenge6.php">All</a>
<a href="?category=tech">Tech</a>
<a href="?category=furniture">Furniture</a>

<h3>Sort</h3>

<a href="?category=<?php echo $filtredCategory ?>&sort=asc">A → Z</a>
<a href="?category=<?php echo $filtredCategory ?>&sort=desc">Z → A</a>

<h3>Products</h3>

<?php

foreach($products as $category => $items){

    if($sort == 'asc'){
        sort($items);
    } elseif($sort == 'desc'){
        rsort($items);
    }

    if($filtredCategory == '' || $filtredCategory == $category){

        echo "<h4>$category</h4>";

        foreach($items as $item){
            echo $item . "<br>";
        }

    }

}

?>

</body>
</html>