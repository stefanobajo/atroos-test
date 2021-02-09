<?php
require("connection.php");

if(isset($_GET['saldo'])){
    
    $soldi = (int)$_GET['saldo'];
    $user = $_GET['user'];
   
    $_SESSION["moneyLeft"] = $soldi;

    $query = "UPDATE users SET saldo = ? WHERE nome = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $soldi, $user);

    $stmt->execute();
    /*$stmt->store_result();
    $stmt->fetch();*/
    $stmt->close();
    
}
else echo "nada";




//$conn->close();
?>
