<!DOCTYPE html>
<html>
<body>
    <?php 
    session_start();
    $language="";
    if(!isset($_SESSION["name"])){
             header("Location: Username.php");
             exit();
        }
     if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $_SESSION["language"] = $_POST["language"];
        header("Location: Summary.php");
        exit(); 
     } 
    ?>
    <form method="post">
         Favorite Programming Language : <input type="text" name="language" ><br>
        <input type="submit" value="Next"><br>
    </form>
  
</body>
</html>