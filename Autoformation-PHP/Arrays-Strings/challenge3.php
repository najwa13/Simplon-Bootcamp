<!DOCTYPE html>
<html >
<body>
    <?php 
    $debts = ["Amine"=>"150","Ali"=>"100","Sara"=>"200","ahmed"=>"60","nora"=>"90"];
    $total=0;
    
    foreach ($debts as $friend => $amount){

        $total+=$amount;

        if($amount>100){
            echo "<p style='color:red;font-weight:bold '>$friend owes $amount DH<br></p>";
        }else{
            echo "<p>$friend owes $amount DH <br></p>";
        }    
    } 

    echo "<h3>total of debts : $total</h3>";
    ?>
</body>
</html>