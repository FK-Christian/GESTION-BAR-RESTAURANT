<div class="content-wrapper">
<!-- Content Header (Page header) -->
<!-- Main content -->
  <section class="content">
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">      
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
  <?php } ?>
      <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
        <div class="box-header with-border" style="background-color: #F7A922; width: 100%">
            <img style="margin-left: 5%; border-radius: 50%; border: 2px #F7A922 solid; height: 50px" src="<?php echo base_url(); ?>/assets/images/logo.jpg">
            <h1 class="box-title">
                <strong>GESTION DES FICHIERS LOGS</strong>
            </h1>
        </div>
          <div class="box-body">     
            <div>
                <table class="table table-striped table-responsive w-auto">
                    <thead class="thead-dark">
                        <tr style="background-color: #139ff7; text-align: center">
                            <th><strong>NUMERO</strong></th>
                            <th><strong>NOM DU FICHIER</strong></th>
                            <th><strong>TAILLE DU FICHIER</strong></th>
                            <th><strong>ACTION</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(CheckPermission("VEGAS-05")){
                                $lignes = lireRepertoireLogs();
                                foreach ($lignes as $ligne){
                                    echo $ligne;
                                }
                            }else{
                                $this->session->set_flashdata('messagePr', "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.");
                            }
                        ?>
                    </tbody>
                </table>
            </div>
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