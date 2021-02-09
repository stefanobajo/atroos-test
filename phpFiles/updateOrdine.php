<?php
    require("connection.php");
    //var_dump($_POST["data"]);
    //$articles = "'articles': [";
    $idOrd = $_GET["id"];
    if(isset($_GET['list'])){
        echo "IT WORKS!";
        
        $sql2 = "UPDATE orders SET articoli = ? WHERE codice_ord = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $_GET['list'], $idOrd);
        $stmt2->execute();
        $stmt2->close();
        
    }
    else{
        echo "IT DOESN't";
    }

   /* $idOrd = $_GET["id"];
    //$user = $_POST["user"];
    if(isset($_GET["articles"])) var_dump("is set");
    $articoli = json_encode(urldecode($_GET["articles"]));
    $stato = 1;

    $sql = "UPDATE orders SET articoli = ?, stato = ? WHERE codice_ord = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sbi", $articoli, $stato, $idOrd);
    $stmt->execute();
    $stmt->close();*/
?>