<?php
/*session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atroos_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
*/

require("head.php");

if(isset($_GET['saldo'])){
    var_dump("CIAO 2");
    $soldi = (int)$_GET['saldo'];
    $user = $_SESSION["loggedUser"];
    var_dump($soldi);
    $_SESSION["moneyLeft"] = $soldi;
    var_dump("CONN 2 =");
    var_dump($conn);
    $query = "UPDATE users SET saldo = ? WHERE nome = ?";
    if ($conn->connect_error) {
      echo "Not connected, error: " . $conn->connect_error;
   }
   else {
      echo "Connected.";
      $stmt = $conn->prepare($query);

      if ($stmt === FALSE) {
        //trigger_error($conn->error, E_USER_ERROR);
        var_dump("THEN");
        die($conn->error);
      } 
      else{
      
        $stmt->bind_param("is", $soldi, $user);

        $stmt->execute();
        /*$stmt->store_result();
        $stmt->fetch();*/
        $stmt->close();
    
        echo $soldi;
      }
    
    }
   
}
else echo "nada";

$conn->close();
?>
</head>
</html>