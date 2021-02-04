<?php 
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


                  $sql = "SELECT id, nome, prezzo, quantità FROM articles";
                  $result = $conn->query($sql);
                  $output = array();
                  if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        array_push($output, '{"id": '. $row['id'] .', "nome": "'. $row['nome'] .'", "prezzo": ' . $row['prezzo'] . ', "quantitàIniziale": ' . $row['quantità'] . '}');
                    }
            
                    
                  } else {
                    echo "0 results";
                  }
                  $conn->close();
                   
                  ?>


  