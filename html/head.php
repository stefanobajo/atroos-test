<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.1.1.js"
  integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
  crossorigin="anonymous"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
    
    
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "atroos_db";
    
    $_SESSION["loggedUser"] = "";
    $_SESSION["moneyLeft"] = 0;
    // Create connection
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT nome, saldo FROM users WHERE nome = 'Utente1'";
                  $result = $conn->query($sql);
                  
                  if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                      $_SESSION["loggedUser"] = $row["nome"];
                      $_SESSION["moneyLeft"] = $row["saldo"];
                    }
            
                    
                  } else {
                    echo "0 results";
                  }
                  //$conn->close();

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
   


    $PageTitle="Atroos - Test";
    

   

    
