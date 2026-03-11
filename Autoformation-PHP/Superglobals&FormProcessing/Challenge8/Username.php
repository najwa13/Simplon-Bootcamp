<!DOCTYPE html>
<html>
<body>
    <?php 
    session_start();
    $username="";
     if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $_SESSION["name"] = $_POST["name"];
        header("Location: Language.php");
        exit(); 
     } 
    ?>
    <form method="post">
         Username : <input type="text" name="name" ><br>
        <input type="submit" value="Next"><br>
    </form>
  
</body>
</html>
