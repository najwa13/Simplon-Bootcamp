<!DOCTYPE html>
<html>
<body>
    <?php 
    $name='';
    $email='';
    $message='';
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $name=$_POST["name"];
        $email=$_POST["email"];
        $message=$_POST["message"];
        if(str_contains($email, "@")==false){
            echo "Email invalide!";
        }
    }
    ?>
    <h2>Contact Form</h2>
    <form method="POST">
        Name :<br>
         <input type="text" name="name" value="<?php echo "$name" ?>"><br>
        E-mail :<input type="text" name="email"><br>
        Message :<br>
        <textarea name="message" ></textarea><br>
        <input type="submit" value="Submit"><br>
    </form>
</body>
</html>