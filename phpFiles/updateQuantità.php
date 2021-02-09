<?php
require("connection.php");

$idArt = (int)$_GET['id'];
$numero = (int)$_GET['number'];


$query = "UPDATE articles SET quantità = ? WHERE id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $numero, $idArt);

$stmt->execute();
/*$stmt->store_result();
$stmt->fetch();*/
$stmt->close();
    





//$conn->close();
?>
