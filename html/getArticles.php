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
                  $output = "var artList = new Vue({
                    el: '#showcase',
                    data: {
                      arts:[
                        {";
                  if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        $output .= "art: new dbEntry(". $row["id"] .", ". $row["nome"] .", " . $row["prezzo"] . ", " . $row["quantità"] . "), ";
                    }
                    $output = chop($output, ", ");
                    $output .= "}] }})";
                    echo $output;
                    
                  } else {
                    echo "0 results";
                  }
                  $conn->close();
                   
                  ?>

var artList = new Vue({
      el: '#showcase',
      data: {
        arts:[
          {art:""}
        ]
      }
    })