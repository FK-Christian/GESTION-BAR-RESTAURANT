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
            <div class="box-header with-border" style="background-color: #F7A922; width: 100%; height: 40px; margin-bottom: 2%">
                <div style="float: left"><img style="margin-left: 5%; margin-right: 10%; border-radius: 50%; border: 2px #F7A922 solid;height: 50px;" src="<?php echo base_url(); ?>/assets/images/logo.jpg"></div>
                <div style="display: inline-block; margin-left: 10%; text-height: max-size;"><strong><?php echo $titre; ?></strong></div>
            </div>
          <!-- /.box-header -->
          <div class="box-body">     
            <?php 
            foreach($css_files as $file): ?>
                    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
            <?php endforeach; ?>
            <?php foreach($js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
            <?php endforeach; ?>
            <div>
                <?php 
                    echo $output; 
                ?>
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