<?php
session_start();

$items = ["Laptop", "Phone", "Tablet"];

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}


if(isset($_POST['clear_cart'])){
    $_SESSION['cart'] = [];
   
}


if(isset($_POST['item'])){
    $item = $_POST['item'];
    if(!in_array($item, $_SESSION['cart'])){
        $_SESSION['cart'][] = $item;
    }
}

$count = count($_SESSION['cart']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Cart</title>
</head>
<body>

<h2>Cart: <?php echo $count; ?> items</h2>

<?php
foreach($items as $item){
?>
<form method="POST">
    <?php echo $item; ?>
    <input type="hidden" name="item" value="<?php echo $item; ?>">
    <button type="submit">Add to Cart</button>
</form>
<br>
<?php
}
?>
<form method="POST">
    <button type="submit" name="clear_cart">Clear Cart</button>
</form>
<br>
<h3>Items in Cart:</h3>
<ul>
<?php
foreach($_SESSION['cart'] as $cartItem){
    echo "<li>$cartItem</li>";
}
?>
</ul>

</body>
</html>