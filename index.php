<?php
  require("phpFiles/head.php");
  include("phpFiles/getArticles.php");
  //include("html/updateSaldo.php");
?>
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
      if(cart.cArts.length > 0){
        for(c of cart.cArts){
          tot += c.cArt.quantità * c.cArt.prezzo;
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
          if(this.quantità > 0){
            str = this.quantità + "x " + this.nome + " " + this.prezzo + "<button type='button' onclick=\"reverseSelection('" + this.id + "')\">X</button>"; 
            //onclick='reverseSelection(" + this.id + ")'
          }
          else{
            str="";
          } 
          return str;
        }
        else{
          let str = "";
          if(this.quantità > 0){
            str = "<span id='" + this.id +"'>" + this.nome + " " + this.prezzo + "</span><input type='number' min='1' max='" + this.quantità + "' value='1' required><button type='button' onclick='transferArticle(this.parentNode);'>Add to Cart</button>";
          }
          else{
            str = "Out of stock :(";
          }
          return str;
        }
        
      }
     
    }  

    //var res = Vue.compile('c.cArt.quantità + "x " + c.cArt.nome + " " + c.cArt.prezzo + "<button type=\"button\" v-on:click=\"deleteItem('" + c.cArt.id + "')\">X</button>"');


    var cart = new Vue({
      el: '#cart-div',
      data: {
        cArts:[
          {cArt: new dbEntry()}
        ] 
      }
      
    })
    //var filtered = array.filter(function(value, index, arr){ 
    //    return value > 5;

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
      var codiceOrdine = -1;

      function transferArticle(item){
        if(cart.cArts[0] != null && cart.cArts[0].cArt.nome === ""){
          cart.cArts.pop();
          var xhttp = new XMLHttpRequest();
          //xhttp.dataType = 'int';
          xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            codiceOrdine = Number(this.responseText);           
          }
        };
        let currentUser = <?php echo "'" . $_SESSION["loggedUser"] . "'";?>;
        xhttp.open("GET", "phpFiles/createOrdine.php?user=" + currentUser, true);
        xhttp.send();
        } 
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

      function reverseSelection(selector){

        artList.arts[Number(selector)].art.quantità += 1; 
        
        for(c of cart.cArts){
            if(c.cArt.id == selector) c.cArt.quantità -= 1;            
          }
        
        cart.cArts = cart.cArts.filter(function(value){return value.cArt.quantità > 0 ;});
        updateTot();
      }


    </script>
    <script>

    /*function cartStringify(){
      let str = "'articles':[";
      for(c of cart.cArts){
        str += "{'id': " + c.cArt.id + ", 'nome': \"" + c.cArt.nome + "\", 'prezzo': " + c.cArt.prezzo + ", 'quantità': " + c.cArt.quantità + '}, ';
      }
      str = str.slice(0, str.length - 2);
      str += "]";
      return str;
    }*/

    function processCart(){
      let userMoney = Number(document.getElementById("uMoney").innerHTML);
      let totale = Number(document.getElementById("tot").innerHTML);
      if(totale <= userMoney){

        //_________________________________UPDATE_______SALDO_________________________________________
        userMoney-=totale;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let debug = this.responseText;
            document.getElementById("uMoney").innerHTML = userMoney;
          }
        };
        let currentUser = <?php echo "'" . $_SESSION["loggedUser"] . "'";?>;
        xhttp.open("GET", "phpFiles/updateSaldo.php?saldo=" + userMoney + "&user=" + currentUser, true);
        xhttp.send();
        
        //_________________________________UPDATE_______ORDINE_________________________________________
        var sendOrder = new XMLHttpRequest();
        var jsonString = JSON.stringify(cart.$data);
        //var jsonString = jsonString.slice(0, jsonString.length -1) + ", 'id':" + codiceOrdine + "}";
        sendOrder.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            let debug = this.responseText;
          }
        };
        //console.log("JSONString = " + jsonString);
        sendOrder.open('GET', 'phpFiles/updateOrdine.php?id=' + codiceOrdine +'&list=' + jsonString, true);
        sendOrder.send();

        //_________________________________UPDATE_______QUANTITà_________________________________________
        var newQuantità = new XMLHttpRequest();
        for(a of artList.arts){
          console.log("iterazione " + a.art.quantità);
          newQuantità.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              let debug = this.responseText;
              console.log("DEBUG = " + debug);
            }
          };
          newQuantità.open('GET', 'phpFiles/updateQuantità.php?id=' + a.art.id + '&number=' + a.art.quantità, true);
          newQuantità.send();
        }
        
        //var jsonString = JSON.stringify(cart.$data);
        //var jsonString = jsonString.slice(0, jsonString.length -1) + ", 'id':" + codiceOrdine + "}";
        

        cart.cArts = cart.cArts.filter(function(value){return value.cArt.quantità < 0 ;});
        updateTot();
      }
      else{
        alert("Denaro insufficiente!");
        for(c of cart.cArts){
          while(c.cArt.quantità > 0) reverseSelection(c.cArt.id);
        }
      }
      

    }
    </script>
</div>
</body>
</html>
