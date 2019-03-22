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
                        <div id='templates-div'>
                        <table class="cell-border example1 table table-striped table1 delSelTable col-xs-12">
                            <thead style="background-color: #F7A922">
                                <tr>
                                    <th><strong>CODE</strong></th>
                                    <th><strong>NOM</strong></th>
                                    <th><strong>HTML</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody id="tab_stock">
                                <?php
                                    if($view_data[0]['col_id']==-1){
                                        echo "<tr><td>PAS DE DONNEE ...</td><td></td><td></td><td></td></tr>";
                                    }else{
                                        foreach ($view_data as $unTemplate){
                                            echo "<tr>"
                                            ."<td>".$unTemplate['col_code']."</td>"
                                            ."<td>".$unTemplate['col_name']."</td>"
                                            ."<td>".$unTemplate['col_htlm']."</td>"
                                            ."<td>".$unTemplate['action']."</td>"
                                            ."</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                            <button onclick="modOrAddstock(0,0)" class="btn btn-primary btn-sm"> <i class="fa fa-html5"></i></button>
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
