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
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">TYPE:</label>
                                                    <select name="role" class="form-control">
                                                        <option value="all">TOUTES</option>
                                                        <option value="CLIENT">CLIENTS</option>
                                                        <option value="FOURNISSEUR">FOURNISSEURS</option>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">NOM et PRENOM:</label>
                                                    <input type="text" class="form-control" name="nom">
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">CARTE(CNI/CANAL):</label>
                                                    <input type="text" class="form-control" name="carte">
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="form-group">
                                                    <label class="control-label">TELEPHONE:</label>
                                                    <input type="text" class="form-control" name="telephone">
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
                        <table class="cell-border example1 table table-striped table1 delSelTable col-xs-12">
                            <thead style="background-color: #F7A922">
                                <tr>
                                    <th><strong>Nom</strong></th>
                                    <th><strong>Telephone</strong></th>
                                    <th><strong>Quartier</strong></th>
                                    <th><strong>Date creation</strong></th>
                                    <th><strong>Role</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody id="tab_client">
                                <?php
                                    $clients_tab = json_decode($clients,true);
                                    if($clients_tab[0]['col_id']==-1){
                                        echo "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td></tr>";
                                    }else{
                                        foreach ($clients_tab as $client){
                                            echo "<tr>"
                                            ."<td>".$client['col_nom_prenom']."</td>"
                                            ."<td>".$client['col_telephone']."</td>"
                                            ."<td>".$client['col_quartier']."</td>"
                                            ."<td>".$client['col_date_creation']."</td>"
                                            ."<td>".$client['role_nom']."</td>"
                                            ."<td>".$client['action']."</td>"
                                            ."</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                            <button onclick="modClients(0)" class="btn btn-primary btn-sm"> <i class="fa fa-user"></i></button>
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
        $('#formData').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting via the browser
            var form = $(this);
            $.ajax({
                type: 'GET',
                url: "<?php echo base_url() . 'gestion_mobile/clients'; ?>",
                dataType: 'json',
                data: form.serialize()
            }).done(function(data) {
                var htlmData = "";
                if(data[0].col_id !== -1){
                    data.forEach(function(element) {
                        htlmData += "<tr>";
                        htlmData += "<td>"+element.col_nom_prenom+"</td>";
                        htlmData += "<td>"+element.col_telephone+"</td>";
                        htlmData += "<td>"+element.col_quartier+"</td>";
                        htlmData += "<td>"+element.col_date_creation+"</td>";
                        htlmData += "<td>"+element.role_nom+"</td>";
                        htlmData += "<td>"+element.action+"</td>";
                        htlmData += "</tr>";
                    });
                }else{
                    htlmData = "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $('#tab_client').html(htlmData);
            }).fail(function(data) {
            });
        });
    });
	$(function () {
		$('#datetimepicker1').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
		$('#datetimepicker2').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
	});
    function modifierOrajouterClient(supprimer){
        var idCle = $('#cleBD').val();
        var dataToSend = (supprimer) ? {ToutSupprimer:1,id:idCle} : $('#formModClient').serialize();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/modifierOrajouterClient'; ?>",
            dataType: 'json',
            data: dataToSend
        }).done(function(data) {
            $('#name_modal_general').modal('toggle');
        })
    }
    function modClients(cle) {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/clientsDetails'; ?>",
            dataType: 'json',
            data: {id:cle}
        }).done(function(data) {
            $('#name_modal_general').find('.modal-body').html(data.formulaire);
            $('#name_modal_general').modal('show');
        })
    }
</script>
