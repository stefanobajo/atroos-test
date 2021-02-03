<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<?php
    

    include("html/getArticles.php");
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
   


    $PageTitle="Atroos - Test";

?>

<?php include("html/head.php"); ?>
</head>
<body>
<!-- body -->
<div id="header">
    <div id="home-title">
        <h1>TEST</h1>
        <br>
      </div>
</div>  

<!-- menu -->
<div id="body-page" class="" role="main">
  

  <div class="row">
    
    <div id="showcase" class="col-lg-6">
              <span>Articoli disponibili:</span> 
              <ul class="articleList col-lg-12">
                <li v-for="a in arts" v-html="a.art.printData()">
                      
                </li>
                  <?php 
                  /*$sql = "SELECT id, nome, prezzo, quantità FROM articles";
                  $result = $conn->query($sql);
        
                  if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                      echo "<li class='container-fluid'>
                      <span style=\"font-weight:bold\">" . $row["nome"] . "</span>
                      -------------"
                      . $row["prezzo"] . "Euro 
                      <input type='number' min='0' max='". $row["quantità"] ."' required>
                      <button type='button' onclick='app.items.push({text:fixContent(this.parentNode)});'>Add to Cart</button>
                      
                      </li>";
                    }
                  } else {
                    echo "0 results";
                  }
                  $conn->close();
                   */
                  ?>
             </ul>
  
    </div>


    
    <div id="cart-div" class="col-lg-6">
      <span>Carrello:</span>
        <ul id="cart" class="articleList container-fluid">
            <li v-for="item in items" v-html="item.text"></li>
        </ul>
        <span id="tot">Totale:</span>
        <button type='button' onclick='processCart()'>Buy</button>
    </div>

  </div>
<!--<h1>TESTDB</h1>-->
    <?php /*
      $sql = "SELECT nome FROM users";
      $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo "id: " . $row["nome"]."<br>";
      }
    } else {
      echo "0 results";
    }
    $conn->close();
    */
    ?>
    <!--<script id="artListScript"></script>-->
    <script>
    var cart = new Vue({
      el: '#cart-div',
      data: {
        items:[
          {text:""}
        ] 
      }
    })

    class dbEntry {
      constructor(id="", nome="", prezzo="", quantità=""){
        this.id = id;
        this.nome = nome;
        this.prezzo = prezzo;
        this.quantità = quantità;
      }
      printData(){
        let str = this.nome + " " + this.prezzo + " <input type='number' min='0' max='" + this.quantità + "' required><button type='button' onclick='cart.items.push({text:fixContent(this.parentNode)});'>Add to Cart</button>";
        return str;
      }
    }    

    var artList = new Vue({
      el: '#showcase',
      data: {
        arts:[
          {art: new dbEntry()}
        ]
      }
    })

    console.log("AAAAAAAAAAAAAAAAAAAAAaa");
    var temp = <?php echo json_encode($output, JSON_HEX_TAG);?>;
    console.log("Result =" + temp);
    for(te of temp){
      console.log("Type of te =" + typeof te);
      let t = JSON.parse(te);
      console.log("Type of t =" + typeof t);
      var it = new dbEntry(t.id, t.nome, t.prezzo, t.quantità);
     
      //var text = it.printData();
      artList.arts.push({art: it});
    }
    artList.arts.shift();
    console.log("arts = " + artList.arts);

    /*var artList = new Vue({
      el: '#showcase',
      data: {
        arts:[
          {art:""}
        ]
      }
    })*/

    //var list = document.getElementByID("articleList");

    //AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
      /*console.log("I'm HERE");
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("artListScript").innerHTML = this.responseText;
        }
        //"art: {" + ar.id +", " + ar.nome + ", " + ar.prezzo + ", " + ar.quantità + "}"
      }
      xhttp.open("GET", "html/getArticles.php", true);
      xhttp.send();*/
      //AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
    </script>
<!--<script>
    function appendItem(prevItem) {
      let list = prev.Item.parentNode;
      var node = document.createTextNode("<li>{{newItem}}</li>");
      list.appendchild(node);
    }
</script>-->
    <script>
    

    function fixContent(item){
      if(cart.items[0].text === "") cart.items.pop();
      //console.log("fixContent");
      let str = item.innerHTML;
      let end = str.indexOf("<input");
      return str.slice(0, end-1);       
    }
    </script>
    <script>
    function processCart(){

    }
    </script>
</div>
</body>
</html>
