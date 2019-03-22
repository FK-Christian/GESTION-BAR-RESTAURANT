<div class="content-wrapper">
<style>
    .text-on-image {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #F7A922;
        color: white;
        padding-left: 20px;
        padding-right: 20px;
    }
    
    .image-texte {
        position: relative;
        text-align: center;
        color: white;
    }
</style>
  <section class="content">
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">      
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
  <?php } ?>
      <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border" style="background-color: #F7A922; width: 100%; height: 40px; margin-bottom: 4%">
                <div style="float: left"><img style="margin-left: 5%; margin-right: 10%; border-radius: 50%; border: 2px #F7A922 solid;height: 50px;" src="<?php echo base_url(); ?>/assets/images/logo.jpg"></div>
                <div style="display: inline-block; margin-left: 10%; text-height: max-size;"><strong><?php echo $titre; ?></strong></div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">     
            <?php $compteur = 1; foreach ($activites as $activite){?>
                <?php if($compteur==3){?>
                    <div class="col-xs-12" style="height: 80px;"></div>
                <?php $compteur = 1; }?>
                <div class="image-texte col-xs-6">
                    <a href="<?php echo base_url()."gestion_mobile/espaces?activite=".$activite->col_id;?>"><img style="height: 150px;" src="<?php echo base_url()."/assets/images/activites/".$activite->col_photo; ?>">
                    <div class="text-on-image"><strong><?php echo $activite->col_libele; ?></strong></div></a>
                </div>
            <?php $compteur++;}?>
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
