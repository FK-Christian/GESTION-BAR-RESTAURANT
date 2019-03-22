<div class="content-wrapper">
    <section class="content">
        <?php if ($this->session->flashdata("messagePr")) { ?>
            <div class="alert alert-info">      
                <?php echo $this->session->flashdata("messagePr") ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border" style="background-color: #F7A922; width: 100%; height: 40px; margin-bottom: 1%">
                        <div style="float: left"><img style="margin-left: 5%; margin-right: 10%; border-radius: 50%; border: 2px #F7A922 solid;height: 50px;" src="<?php echo base_url(); ?>/assets/images/logo.jpg"></div>
                        <div style="display: inline-block; margin-left: 10%; text-height: max-size;"><strong><?php echo $titre; ?></strong></div>
                    </div>
                    <div class="box-body"> 
                        <section class="container">
                            <article class="well form-inline pull-left col-xs-12">
                                <legend class="col-xs-12">References</legend>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">ACTIVITE:</label>
                                        <select name="activite" id="activite" onchange="placerEspace()" class="form-control">
                                            <?php 
                                                $lesActivites = get_data_by('*','tb_activites','1=1');
                                                foreach ($lesActivites as $activite){
                                                    echo "<option value='$activite->col_id'>$activite->col_libele</option>";
                                                }
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <label class="control-label">ESPACE:</label>
                                        <select name="espace" id="espace" onchange="placerStatutEspace()" class="form-control selectpicker">
                                            <?php 
                                                $lesEspaces = get_data_by('*','tb_espace','1=1');
                                                foreach ($lesEspaces as $espace){
                                                    echo "<option value='$espace->col_id'>$espace->col_libele</option>";
                                                }
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-xs-1">
                                    <div class="form-group" id="statut-espace">
                                        <label class="control-label">...</label>
                                        <button class='btn-success btn-sm' disabled=""><i class='fa fa-table'></i></button>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label class="control-label">CLIENT:</label>
                                        <select name="client" id="client" class="form-control">
                                            <option value='-1'>NOUVEAU / INCONNU</option>
                                            <?php 
                                                $lesClients = get_data_by('*','tb_users','1=1');
                                                foreach ($lesClients as $client){
                                                    echo "<option value='$client->col_id'>$client->col_nom_prenom ($client->col_telephone)</option>";
                                                }
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="control-label">Nom client:</label>
                                        <input type="text" name="client_nom" id="client_nom"  class="input-sm form-control">
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label class="control-label">Tel client:</label>
                                        <input type="text" name="client_tel" id="client_tel"  class="input-sm form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">Description:</label>
                                        <textarea name='description' id='description' class="input-sm form-control"></textarea>
                                    </div>
                                </div>
                            </article>
                        </section>
                        <section  class="container">
                            <article class="well form-inline pull-left col-xs-12">
                                <legend class="col-xs-12">Gestion du panier</legend>
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label class="control-label">Libele:</label>                                        
                                        <input type="text" name="libele" id="cle_ajax" data-libele='' class="input-sm form-control"/>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label class="control-label">QTE(stock):</label>                                        
                                        <input type="text" name="qteS" id="cle_qte" data-libele='' class="bg-gray center input-sm form-control" readonly=""/>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label class="control-label">PUV:</label>                                        
                                        <input type="text" name="puv" id="cle_puv" data-libele='' class="bg-gray center input-sm form-control" readonly=""/>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label class="control-label">Quantité:</label>
                                        <input type="number" id="qte"  class="input-sm form-control">
                                        <input type="hidden" id="id"  class="input-sm form-control">
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label class="control-label">Prix:</label>
                                        <input type="number" id="prix"  class="input-sm form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12"></div><div class="col-xs-8"></div>
                                <div class="col-xs-4">
                                    <label class="control-label">Action:</label>
                                    <button class="btn btn-primary" type="submit" onclick="ajouter()"><span class="glyphicon glyphicon-shopping-cart"></span> Ajouter au panier</button>
                                </div>
                            </article>
                        </section>
                        <section class="container">
                            <article class="well form-inline pull-left col-xs-12">
                                <legend class="col-xs-12">Contenu du panier</legend>
                                <table id="tableau" class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>code</strong></th>
                                            <th><strong>Libele</strong></th>
                                            <th><strong>Qte</strong></th>
                                            <th><strong>Prix unitaire</strong></th>
                                            <th><strong>Prix de la ligne</strong></th>
                                            <th><strong>Supprimer</strong></th>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                                <div class="col-xs-6"><label>Prix du panier total</label> : <label id = "prixTotal"></label></div>
                                <div class="col-xs-1"><label id = "nbreLignes" hidden>0</label></div>
                                <div class="col-xs-4">
                                    <button class="btn btn-sm btn-success" onclick="saveCommande();"><span class="glyphicon glyphicon-ok-circle"></span> Ajouter la commande</button>
                                </div>
                            </article>
                        </section>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div> 
<script type="text/javascript">  
    function placerStatutEspace(){
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/getStatutEspace'; ?>",
            dataType: 'json',
            data: {id:$('#espace').val()}
        }).done(function(data) {
            var dataHtlm = "<label class='control-label'>...</label>";
            dataHtlm += data.statut;
            $('#statut-espace').html(dataHtlm);
        });    
    }
    
    function placerEspace(){
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/getEspaceFromActivite'; ?>",
            dataType: 'json',
            data: {id:$('#activite').val()}
        }).done(function(data) {
            var dataHtlm = "";
            data.forEach(function(element) {
                dataHtlm += element.valeur;
            });
            $('#espace').html(dataHtlm);
        });    
    }
    
    placerEspace();
    placerStatutEspace();
    
    function autocomplete(inp) {
      /*the autocomplete function takes two arguments,
      the text field element and an array of possible autocompleted values:*/
      var currentFocus;
      /*execute a function when someone writes in the text field:*/
      inp.addEventListener("input", function(e) {
          var a, b, i, val = this.value;
          /*close any already open lists of autocompleted values*/
          closeAllLists();
          if (!val) { return false;}
          currentFocus = -1;
          /*create a DIV element that will contain the items (values):*/
          a = document.createElement("DIV");
          a.setAttribute("id", this.id + "autocomplete-list");
          a.setAttribute("class", "autocomplete-items");
          /*append the DIV element as a child of the autocomplete container:*/
          this.parentNode.appendChild(a);
          /*for each item in the array...*/
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() . 'gestion_mobile/stocksAjax'; ?>",
                dataType: 'json',
                data: {keyword:val,activite:$('#activite').val()}
            }).done(function(data) {
                data.forEach(function(element) {
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = element.col_libele.split(val).join(val.bold());
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' data-cle='"+element.col_id+"' value='" + element.col_libele +"'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        var cle = this.getElementsByTagName("input")[0].getAttribute('data-cle');
                        document.getElementById("id").value = cle;
                        document.getElementById("cle_qte").value = element.col_qte;
                        document.getElementById("cle_puv").value = element.col_puv;
                        inp.setAttribute('data-libele',element.col_libele);
                        inp.setAttribute('data-puv',element.col_puv);
                        /*close the list of autocompleted values,(or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                });
            }).fail(function(data) {
            });
      });
      /*execute a function presses a key on the keyboard:*/
      inp.addEventListener("keydown", function(e) {
          var x = document.getElementById(this.id + "autocomplete-list");
          if (x) x = x.getElementsByTagName("div");
          if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
          } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
              /*and simulate a click on the "active" item:*/
              if (x) x[currentFocus].click();
            }
          }
      });
      function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
      }
      function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
          x[i].classList.remove("autocomplete-active");
        }
      }
      function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
          if (elmnt != x[i] && elmnt != inp) {
            x[i].parentNode.removeChild(x[i]);
          }
        }
      }
      /*execute a function when someone clicks in the document:*/
      document.addEventListener("click", function (e) {
          closeAllLists(e.target);
          });
    }
    
    function saveCommande(){
        var tableau = document.getElementById("tableau");
        var longueurTab = parseInt(document.getElementById("nbreLignes").innerHTML);
        var dataSET = [];
        if (longueurTab > 0){
            for(var i = longueurTab ; i > 0  ; i--){
                dataSET.push(tableau.rows[i].cells[0].innerHTML+"--"+tableau.rows[i].cells[2].innerHTML+"--"+tableau.rows[i].cells[3].innerHTML);
            }
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/SaveCommande'; ?>",
            dataType: 'json',
            data: {liste:JSON.stringify(dataSET),activite:$('#activite').val(),espace:$('#espace').val(),client:$('#client').val(),newClientName:$('#client_nom').val(),newClientTel:$('#client_tel').val(),description:$('#description').val()}
        }).done(function(data) {
            if(data.statut){
                if (longueurTab > 0){
                    for(var i = longueurTab ; i > 0  ; i--){
                        tableau.deleteRow(i);
                    }
                }
            }
            alert(data.message);
        });
    }

    /*An array containing all the country names in the world:*/
    autocomplete(document.getElementById("cle_ajax"));

    function LignePanier (code, libele, qte, prix){
        this.codeArticle = code;
        this.qteArticle = qte;
        this.prixArticle = prix;
        this.libeleArticle = libele;
        this.ajouterQte = function(qte){
            this.qteArticle += qte;
        }
        this.getPrixLigne = function(){
            var resultat = this.prixArticle * this.qteArticle;
            return resultat;
        }
        this.getCode = function(){
            return this.codeArticle;
        }
        this.getLibele = function(){
            return this.libeleArticle;
        }
    }
    
    function Panier(){
        this.liste = [];
        this.ajouterArticle = function(code, libele, qte, prix){ 
            var index = this.getArticle(code);
            if (index == -1) this.liste.push(new LignePanier(code, libele, qte, prix));
            else this.liste[index].ajouterQte(qte);
        }
        this.getPrixPanier = function(){
            var total = 0;
            for(var i = 0 ; i < this.liste.length ; i++)
                total += this.liste[i].getPrixLigne();
            return total;
        }
        this.getArticle = function(code){
            for(var i = 0 ; i <this.liste.length ; i++)
                if (code == this.liste[i].getCode()) return i;
            return -1;
        }
        this.supprimerArticle = function(code){
            var index = this.getArticle(code);
            if (index > -1) this.liste.splice(index, 1);
        }
    }
    
    function ajouter(){
        //document.getElementById("id").value = document.getElementById("cle_ajax").value
        var code = parseInt(document.getElementById("id").value);
        var qte = parseInt(document.getElementById("qte").value);
        var prix = parseInt(document.getElementById("prix").value);
        var libele = document.getElementById("cle_ajax").getAttribute('data-libele');
        var monPanier = new Panier();
        if(!isNaN(code) && !isNaN(qte) && !isNaN(prix)){
            if(parseInt(document.getElementById("cle_ajax").getAttribute('data-puv'))<=prix){
                if(parseInt(document.getElementById("cle_qte").value)>=qte){
                    monPanier.ajouterArticle(code, libele, qte, prix);
                    var tableau = document.getElementById("tableau");
                    var longueurTab = parseInt(document.getElementById("nbreLignes").innerHTML);
                    if (longueurTab > 0){
                        for(var i = longueurTab ; i > 0  ; i--){
                            monPanier.ajouterArticle(parseInt(tableau.rows[i].cells[0].innerHTML), tableau.rows[i].cells[1].innerHTML, parseInt(tableau.rows[i].cells[2].innerHTML), parseInt(tableau.rows[i].cells[3].innerHTML));
                            tableau.deleteRow(i);
                        }
                    }
                    var longueur = monPanier.liste.length;
                    for(var i = 0 ; i < longueur ; i++){
                        var ligne = monPanier.liste[i];
                        var ligneTableau = tableau.insertRow(-1);
                        var colonne1 = ligneTableau.insertCell(0);
                        colonne1.innerHTML += ligne.getCode();
                        var colonne2 = ligneTableau.insertCell(1);
                        colonne2.innerHTML += ligne.getLibele();
                        var colonne3 = ligneTableau.insertCell(2);
                        colonne3.innerHTML += ligne.qteArticle;
                        var colonne4 = ligneTableau.insertCell(3);
                        colonne4.innerHTML += ligne.prixArticle;
                        var colonne5 = ligneTableau.insertCell(4);
                        colonne5.innerHTML += ligne.getPrixLigne();
                        var colonne6 = ligneTableau.insertCell(5);
                        colonne6.innerHTML += "<button class=\"btn btn-primary\" type=\"submit\" onclick=\"supprimer(this.parentNode.parentNode.cells[0].innerHTML)\"><span class=\"glyphicon glyphicon-remove\"></span> Retirer</button>";
                    }
                    document.getElementById("prixTotal").innerHTML = monPanier.getPrixPanier();
                    document.getElementById("nbreLignes").innerHTML = longueur;
                    document.getElementById("id").value = '';
                    document.getElementById("qte").value = '';
                    document.getElementById("prix").value = '';
                    document.getElementById("cle_ajax").value = '';
                }else{
                    alert("La Quantite en stock est insuffisante");
                }
            }else{
                alert("Le Prix unitaire doit etre superieure ou egale a "+document.getElementById("cle_ajax").getAttribute('data-puv')+" FCFA");
            }
        }else{
            alert("Verifiez les entrees ...");
        }
    }
            
    function supprimer(code){
        var monPanier = new Panier();
        var tableau = document.getElementById("tableau");
        var longueurTab = parseInt(document.getElementById("nbreLignes").innerHTML);
        if (longueurTab > 0){
            for(var i = longueurTab ; i > 0  ; i--){
                monPanier.ajouterArticle(parseInt(tableau.rows[i].cells[0].innerHTML), tableau.rows[i].cells[1].innerHTML, parseInt(tableau.rows[i].cells[2].innerHTML), parseInt(tableau.rows[i].cells[3].innerHTML));
                tableau.deleteRow(i);
            }
        }
        monPanier.supprimerArticle(code);
        var longueur = monPanier.liste.length;
        for(var i = 0 ; i < longueur ; i++){
            var ligne = monPanier.liste[i];
            var ligneTableau = tableau.insertRow(-1);
            var colonne1 = ligneTableau.insertCell(0);
            colonne1.innerHTML += ligne.getCode();
            var colonne2 = ligneTableau.insertCell(1);
            colonne2.innerHTML += ligne.getLibele();
            var colonne3 = ligneTableau.insertCell(2);
            colonne3.innerHTML += ligne.qteArticle;
            var colonne4 = ligneTableau.insertCell(3);
            colonne4.innerHTML += ligne.prixArticle;
            var colonne5 = ligneTableau.insertCell(4);
            colonne5.innerHTML += ligne.getPrixLigne();
            var colonne6 = ligneTableau.insertCell(5);
            colonne6.innerHTML += "<button class=\"btn btn-primary\" type=\"submit\" onclick=\"supprimer(this.parentNode.parentNode.cells[0].innerHTML)\"><span class=\"glyphicon glyphicon-remove\"></span> Retirer</button>";
        }
        document.getElementById("prixTotal").innerHTML = monPanier.getPrixPanier();
        document.getElementById("nbreLignes").innerHTML = longueur;
    }
</script>
