<?php 
  require("html/head.php");
  include("html/getArticles.php");
  //include("html/updateSaldo.php");
?>
</head>
<body>
<!-- body -->
<div id="header">
    <div id="home-title">
        <h1>TEST</h1>
        <h2 style="text-align:right;"><?php echo $_SESSION["loggedUser"]?> il tuo saldo è:<span id="uMoney"><?php echo $_SESSION["moneyLeft"]?></span> Euro.</h2>
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
          tot += c.cArt.quantità * c.cArt.prezzo; //
        }
      }
      document.getElementById("tot").innerHTML = tot;
    }
    </script>
    <script>
    
    class dbEntry {
      constructor(id=-1, nome="", prezzo=0, quantità=0){
        this.id = id;
        this.nome = nome;
        this.prezzo = prezzo;
        this.quantità = quantità;
        //Iniziale = quantitàIniziale;
        //this.quantitàResidua = quantitàIniziale;
      }

      copy(tobeCopied){
        this.id = tobeCopied.id;
        this.nome = tobeCopied.nome;
        this.prezzo = tobeCopied.prezzo;
        this.quantità = tobeCopied.quantità;
        //Iniziale = tobeCopied.quantitàInziale;
        //this.quantitàResidua = tobeCopied.quantitàResidua;
      }

      printData(isCart){
        if(isCart){
          let str = "";
          if(this.quantità != 0){
            str = this.quantità + "x " + this.nome + " " + this.prezzo + "<button type='button' onclick='reverseSelection(this)'>X</button>"; //
          }
          else{
            str="";
          } 
          return str;
        }
        else{
          let str = "";
          if(this.quantità > 0){
            str = "<span id='" + this.id +"'>" + this.nome + " " + this.prezzo + "</span><input type='number' min='1' max='" + this.quantità + "' value='1' required><button type='button' onclick='transferArticle(this.parentNode);'>Add to Cart</button>";//
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
  
      var it = new dbEntry(t.id, t.nome, t.prezzo, t.quantità);
  
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
        let num = Number(item.childNodes[1].value);
       
        let flag = false;
        for(c of cart.cArts){
            if(c.cArt.id == selector) {
            
              artList.arts[Number(selector)].art.quantità -= num;         
        
              c.cArt.quantità += num;
              flag = true;
            }
          }
        if(!flag){
          let temp = new dbEntry();
          temp.copy(artList.arts[Number(selector)].art);
  
          artList.arts[Number(selector)].art.quantità -= num;  
        
          temp.quantità = num;
          //artList.arts[Number(selector)].art.quantitàIniziale - artList.arts[Number(selector)].art.quantitàResidua;
          cart.cArts.push({cArt:temp}); 
        }
        updateTot();
      }
      function reverseSelection(article){
        let selector = article.parentNode.getAttribute("id");
        artList.arts[Number(selector)].art.quantità += 1; 
        let cartItem;
        for(c of cart.cArts){
            if(c.cArt.id == selector) cartItem = c;
          }
        cartItem.cArt.quantità -= 1;
        updateTot();
      }
    </script>
    <script>
     
    class order{
      constructor(id =0, user ="", articoli = Object(), stato ="pending"){
        this.id = id;
        this.user = user;
        this.articoli = articoli;
        this.stato = stato;
      }
    }

    function processCart(){
      let userMoney = Number(document.getElementById("uMoney").innerHTML);
      console.log("SALDO =" + userMoney);
      let totale = Number(document.getElementById("tot").innerHTML);
      console.log("TOTALE =" + totale);
      if(totale <= userMoney){
        userMoney-=totale;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let debug = this.responseText;
            console.log("DEBUG = " + debug);
          }
        };
        xhttp.open("GET", "html/updateSaldo.php?saldo=" + userMoney, true);
        xhttp.send();
      }
      else{
        alert("Denaro insufficiente!");
      }
    }
    </script>
</div>
</body>
</html>
