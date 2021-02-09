
<?php
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "atroos_db";
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    
    // Create connection
    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    //$conn = new mysqli($servername, $username, $password, $dbname);
    
    
    //$conn->close();

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    else{
     
    }
   
    ?>

   

    
