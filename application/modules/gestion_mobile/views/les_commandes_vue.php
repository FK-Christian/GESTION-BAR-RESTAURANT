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
                        <form id = "formData" action="#" method="GET">
                            <div class="container">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">FILTREZ LES DONNEES</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">ACTIVITE:</label>
                                                    <select name="activite" class="form-control">
                                                        <option value="all">TOUTES</option>
                                                        <?php 
                                                            $lesActivites = get_data_by('*','tb_activites','1=1');
                                                            foreach ($lesActivites as $activite){
                                                                echo "<option value='$activite->col_id'>$activite->col_libele</option>";
                                                            }
                                                        ?>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">ESPACE:</label>
                                                    <select name="espace" class="form-control">
                                                        <option value="all">TOUS</option>
                                                        <?php 
                                                            $lesEspaces = get_data_by('*','tb_espace','1=1');
                                                            foreach ($lesEspaces as $espace){
                                                                echo "<option value='$espace->col_id'>$espace->col_libele</option>";
                                                            }
                                                        ?>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">STATUT</label>
                                                    <select name="statut" class="form-control">
                                                        <option value="all">TOUS</option>
                                                        <option value="P">PAYEE</option>
                                                        <option value="F">FACTURE</option>
                                                        <option value="O">OUVERTE</option>
                                                        <option value="R">REJETE</option>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">CLIENT:</label>
                                                    <input type="text" class="form-control" name="client">
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">SERVANT:</label>
                                                    <input type="text" class="form-control" name="servant">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class='col-xs-6'>
                                                <div class="form-group">
                                                    <label class="control-label">DATE DEBUT</label>
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type='text' class="form-control" name="dateDebut"/>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-xs-6'>
                                                <div class="form-group">
                                                    <label class="control-label">DATE FIN</label>
                                                    <div class='input-group date' id='datetimepicker2'>
                                                        <input type='text' class="form-control" name="dateFin"/>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-xs-4'></div><div class='col-xs-4'><input type="submit" class="btn btn-primary submitButton" value="AFFICHER"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="cell-border example1 table-striped table1 delSelTable col-xs-12">
                            <thead style="background-color: #F7A922">
                                <tr>
                                    <th><input type="checkbox" class="selAll"></th>
                                    <th><strong>Num</strong></th>
                                    <th><strong>Date</strong></th>
                                    <th><strong>Activite</strong></th>
                                    <th><strong>Espace</strong></th>
                                    <th><strong>Statut</strong></th>
                                    <th><strong>Serveur</strong></th>
                                    <th><strong>Client_nom</strong></th>
                                    <th><strong>Client_Tel</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody id="tab_commande">
                                <?php
                                    $commandes_tab = json_decode($commandes,true);
                                    if($commandes_tab[0]['col_id'] == -1){
                                        echo "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                                    }else{
                                        foreach ($commandes_tab as $commande){
                                            echo "<tr>"
                                            ."<td>".$commande['selection']."</td>"
                                            ."<td>COM-".$commande['col_id']."</td>"
                                            ."<td>".$commande['date']."</td>"
                                            ."<td>".$commande['activite']."</td>"
                                            ."<td>".$commande['espace']."</td>"
                                            ."<td>".$commande['statut']."</td>"
                                            ."<td>".$commande['servant']."</td>"
                                            ."<td>".$commande['client_name']."</td>"       
                                            ."<td>".$commande['client_tel']."</td>"
                                            ."<td>".$commande['action']."</td>"
                                            ."</tr>";
                                        }
                                    }
                                ?>
                            </tbody> 
                            <button data-base-url="<?php echo base_url().'gestion_moblie/facture/'; ?>" class="btn btn-primary btn-sm setFacture"> <i class="fa fa-file-pdf-o"></i></button>
                        </table>
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
<script>
    $(function() {
        //formModCommande
        $('#formData').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting via the browser
            var form = $(this);
            $.ajax({
                type: 'GET',
                url: "<?php echo base_url() . 'gestion_mobile/commandes'; ?>",
                dataType: 'json',
                data: form.serialize()
            }).done(function(data) {
                var htlmData = "";
                if(data[0].col_id !== -1){
                    data.forEach(function(element) {
                        htlmData += "<tr>";
                        htlmData += "<td>"+element.selection+"</td>";
                        htlmData += "<td>COM-"+element.col_id+"</td>";
                        htlmData += "<td>"+element.date+"</td>";
                        htlmData += "<td>"+element.activite+"</td>";
                        htlmData += "<td>"+element.espace+"</td>";
                        htlmData += "<td>"+element.statut+"</td>";
                        htlmData += "<td>"+element.servant+"</td>";
                        htlmData += "<td>"+element.client_name+"</td>";
                        htlmData += "<td>"+element.client_tel+"</td>";
                        htlmData += "<td>"+element.action+"</td>";
                        htlmData += "</tr>";
                    });
                }else{
                    htlmData = "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $('#tab_commande').html(htlmData);
            }).fail(function(data) {
            });
        }); //setFacture
        $('.selAll').click(function (event) {
            if (this.checked) {
                $('.checkboxTab').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkboxTab').each(function () {
                    this.checked = false;
                });
            }
        });
        $('.setFacture').click(function (event) {
            var tab = [];
            $('.checkboxTab').each(function () {
                if(this.checked){
                    tab.push(this.value);
                }
            });
            var valData = tab.join(",");
            var htmlData = ""; 
            if(tab.length > 0){ 
                htmlData = "<div><p>Voulez-vous soumettre cette liste de "+tab.length+" commande(s) pour facturation ?</p>";
                htmlData += "<input type='button' onclick='facturation(\""+valData+"\")' class='btn btn-xs btn-primary submitButton col-xs-4' value='SOUMETTRE'>";
                htmlData += "<button type='button' class='close col-xs-4' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>";
                htmlData += "</div>";
            }else{
                htmlData = "<p>Selectionnez au moins une commande</p>";
            }
            $('#name_modal_general').find('.modal-body').html(htmlData);
            $('#name_modal_general').modal('show'); 
        });
        $(function () {
            $('#datetimepicker1').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $('#datetimepicker2').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
        });
    });
    function facturation(listeCommande) {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/CreateFacture'; ?>",
            dataType: 'json',
            data: {commandes:listeCommande}
        }).done(function(data) {
            $('#name_modal_general').modal('toggle');
            alert(data.message);
        });
    }
    function modifierCommande(supprimer){
        var idCle = $('#cleBD').val();
        var dataToSend = (supprimer) ? {ToutSupprimer:1,id:idCle} : $('#formModCommande').serialize();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/modifierCommande'; ?>",
            dataType: 'json',
            data: dataToSend
        }).done(function(data) {
            $('#name_modal_general').modal('toggle');
        })
    }
    function modCommande(cle,modifier) {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/commandesDetails'; ?>",
            dataType: 'json',
            data: {id:cle,modification:modifier}
        }).done(function(data) {
            $('#name_modal_general').find('.modal-body').html(data.formulaire);
            $('#name_modal_general').modal('show');
        })
    }
</script>
