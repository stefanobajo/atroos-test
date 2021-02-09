<?php
require("connection.php");

//codice ordine --------- nome utente ------- articoli -------- stato
$idOrd = -1;
$result = $conn->query("SELECT MAX(codice_ord) FROM orders");
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $idOrd = (int)$row["MAX(codice_ord)"] + 1; 
    }

    
  } else {
    echo "0 results";
  }
$user = $_GET["user"];
$articoli = json_encode("");
$stato = 0;

$sql = "INSERT INTO orders (codice_ord, nome_utente, articoli, stato) VALUES (?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issb", $idOrd, $user, $articoli, $stato);
$stmt->execute();
    /*$stmt->store_result();
    $stmt->fetch();*/
$stmt->close();
echo $idOrd;
//$conn->close();
?>
