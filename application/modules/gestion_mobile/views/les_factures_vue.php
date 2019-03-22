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
                                                        <option value="O">OUVERTE</option>
                                                        <option value="D">DETTE</option>
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">CLIENT:</label>
                                                    <input type="text" class="form-control" name="client">
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">SERVANT:</label>
                                                    <input type="text" class="form-control" name="servant">
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <div class="form-group">
                                                    <label class="control-label">CAISSIER:</label>
                                                    <input type="text" class="form-control" name="caissier">
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
                                    <th><strong>NUM</strong></th>
                                    <th><strong>Date Creation</strong></th>
                                    <th><strong>Date Reglement</strong></th>
                                    <th><strong>Caissier</strong></th>
                                    <th><strong>Statut</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody id="tab_facture">
                                <?php
                                    $factures_tab = json_decode($factures,true);
                                    if($factures_tab[0]['col_id'] == -1){
                                        echo "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td></tr>";
                                    }else{
                                        foreach ($factures_tab as $facture){
                                            echo "<tr>"
                                            ."<td>FAC-".$facture['col_id']."</td>"
                                            ."<td>".$facture['date_c']."</td>"
                                            ."<td>".$facture['date_r']."</td>"
                                            ."<td>".$facture['caissier']."</td>"
                                            ."<td>".$facture['statut']."</td>"
                                            ."<td>".$facture['action']."</td>"
                                            ."</tr>";
                                        }
                                    }
                                ?>
                            </tbody> 
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
                url: "<?php echo base_url() . 'gestion_mobile/factures'; ?>",
                dataType: 'json',
                data: form.serialize()
            }).done(function(data) {
                var htlmData = "";
                if(data[0].col_id !== -1){
                    data.forEach(function(element) {
                        htlmData += "<tr>";
                        htlmData += "<td>FAC-"+element.col_id+"</td>";
                        htlmData += "<td>"+element.date_c+"</td>";
                        htlmData += "<td>"+element.date_r+"</td>";
                        htlmData += "<td>"+element.caissier+"</td>";
                        htlmData += "<td>"+element.statut+"</td>";
                        htlmData += "<td>"+element.action+"</td>";
                        htlmData += "</tr>";
                    });
                }else{
                    htlmData = "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                $('#tab_facture').html(htlmData);
            }).fail(function(data) {
            });
        }); //setFacture
        $(function () {
            $('#datetimepicker1').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
            $('#datetimepicker2').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss'});
        });
    });
    function modifierFacture(supprimer,mode){
        var idCle = $('#cleBD').val();
        var idsms = $('#sms').is(':checked');
        var idmail = $('#mail').is(':checked');
        var idprint = $('#print').is(':checked');
        var dataToSend = (supprimer) ? {ToutSupprimer:1,id:idCle,modeVal:mode,print:idprint,sms:idsms,mail:idmail} : $('#formModFacture').serialize();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/modifierFacture'; ?>",
            dataType: 'json',
            data: dataToSend
        }).done(function(data) {
            $('#name_modal_general').modal('toggle');
        })
    }
    function modFacture(cle,modifier) {
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url() . 'gestion_mobile/facturesDetails'; ?>",
            dataType: 'json',
            data: {id:cle,modification:modifier}
        }).done(function(data) {
            $('#name_modal_general').find('.modal-body').html(data.formulaire);
            $('#name_modal_general').modal('show');
        })
    }
</script>
