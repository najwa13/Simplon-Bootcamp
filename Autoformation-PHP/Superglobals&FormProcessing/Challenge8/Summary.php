
<?php 
session_start();
if(!isset($_SESSION["name"])){
    header("Location: Username.php");
    exit();
}
$username = $_SESSION["name"];
$language = $_SESSION["language"];
echo "<h1>Hello $username, you love $language!</h1>"; 
session_destroy();
?>