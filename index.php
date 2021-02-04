<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<?php
    
    include("html/head.php");
    include("html/getArticles.php");
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "atroos_db";
    
    $loggedUser = "";
    $moneyLeft = 0;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    $sql = "SELECT nome, saldo FROM users WHERE nome = 'Utente1'";
                  $result = $conn->query($sql);
                  
                  if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        $loggedUser = $row["nome"];
                        $moneyLeft = $row["saldo"];
                    }
            
                    
                  } else {
                    echo "0 results";
                  }
                  $conn->close();

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
   


    $PageTitle="Atroos - Test";

?>
</head>
<body>
<!-- body -->
<div id="header">
    <div id="home-title">
        <h1>TEST</h1>
        <h2 style="text-align:right;"><?php echo $loggedUser?> il tuo saldo è: <?php echo $moneyLeft?> Euro.</h2>
      </div>
</div>  

<!-- menu -->
<div id="body-page" class="" role="main">
  
  <div class="row">
    
    <div id="showcase" class="col-lg-6">
              <span>Articoli disponibili:</span> 
              <ul class="articleList col-lg-12">
                <li v-for="a in arts" v-html="a.art.printData(false)" v-bind:id="a.art.id"></li>
             </ul>
    </div>

    <div id="cart-div" class="col-lg-6">
      <span>Carrello:</span>
        <ul class="articleList container-fluid">
            <li v-for="c in cArts" v-html="c.cArt.printData(true)" v-bind:id="c.cArt.id"></li>
        </ul>
        Totale:<span id="tot"></span>
        <button type="button" onclick="processCart()">Buy</button>
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
    <script>
    function updateTot(){
      let tot = 0;
      if(cart.cArts.length !== 0){
        for(c of cart.cArts){
          tot += c.cArt.quantitàIniziale * c.cArt.prezzo;
        }
      }
      document.getElementById("tot").innerHTML = tot;
    }
    </script>
    <script>
    
    class dbEntry {
      constructor(id=-1, nome="", prezzo=0, quantitàIniziale=0){
        this.id = id;
        this.nome = nome;
        this.prezzo = prezzo;
        this.quantitàIniziale = quantitàIniziale;
        this.quantitàResidua = quantitàIniziale;
      }

      copy(tobeCopied){
        this.id = tobeCopied.id;
        this.nome = tobeCopied.nome;
        this.prezzo = tobeCopied.prezzo;
        this.quantitàIniziale = tobeCopied.quantitàInziale;
        this.quantitàResidua = tobeCopied.quantitàResidua;
      }

      printData(isCart){
        if(isCart){
          let str = "";
          if(this.quantitàIniziale != 0){
            str = this.quantitàIniziale + "x " + this.nome + " " + this.prezzo + "<button type='button' onclick='reverseSelection(this)'>X</button>";
          }
          else{
            str="";
          } 
          return str;
        }
        else{
          let str = "";
          if(this.quantitàResidua > 0){
            str = "<span id='" + this.id +"'>" + this.nome + " " + this.prezzo + "</span> <input type='number' min='1' max='" + this.quantitàResidua + "' required><button type='button' onclick='transferArticle(this.parentNode);'>Add to Cart</button>";
          }
          else{
            str = "Out of stock :(";
          }
          return str;
        }
        
      }
     
    }  

    var cart = new Vue({
      el: '#cart-div',
      data: {
        cArts:[
          {cArt: new dbEntry()}
        ] 
      }
    })
  

    var artList = new Vue({
      el: '#showcase',
      data: {
        arts:[
          {art: new dbEntry()}
        ]
      }
    })

   
    var temp = <?php echo json_encode($output, JSON_HEX_TAG);?>;
 
    for(te of temp){
   
      let t = JSON.parse(te);
  
      var it = new dbEntry(t.id, t.nome, t.prezzo, t.quantitàIniziale);
  
      artList.arts.push({art: it});
    }
    artList.arts.shift();
    updateTot();
    </script>

    <script>
      function transferArticle(item){
        if(cart.cArts[0].cArt.nome === "") cart.cArts.pop();
        //console.log("fixContent");
        let selector = item.getAttribute("id");
        let flag = false;
        for(c of cart.cArts){
            if(c.cArt.id == selector) {
              console.log("PRIMA =" + artList.arts[Number(selector)].art.quantitàResidua);
              artList.arts[Number(selector)].art.quantitàResidua -= 1; 
              console.log("DOPO =" + artList.arts[Number(selector)].art.quantitàResidua);
              c.cArt.quantitàIniziale += 1;
              flag = true;
            }
          }
        if(!flag){
          let temp = new dbEntry();
          temp.copy(artList.arts[Number(selector)].art);
          console.log("PRIMA =" + artList.arts[Number(selector)].art.quantitàResidua);
          artList.arts[Number(selector)].art.quantitàResidua -= 1;  
          console.log("DOPO =" + artList.arts[Number(selector)].art.quantitàResidua);
          temp.quantitàIniziale = artList.arts[Number(selector)].art.quantitàIniziale - artList.arts[Number(selector)].art.quantitàResidua;
          cart.cArts.push({cArt:temp}); 
        }
        updateTot();
      }
      function reverseSelection(article){
        let selector = article.parentNode.getAttribute("id");
        artList.arts[Number(selector)].art.quantitàResidua += 1; 
        let cartItem;
        for(c of cart.cArts){
            if(c.cArt.id == selector) cartItem = c;
          }
        cartItem.cArt.quantitàIniziale -= 1;
        updateTot();
      }
    </script>
    <script>
    function processCart(){

    }
    </script>
</div>
</body>
</html>
