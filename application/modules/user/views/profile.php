<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Main content -->
    <div class="col-md-12 form f-label">
        <?php if ($this->session->flashdata("messagePr")) { ?>
            <div class="alert alert-info">      
                <?php echo $this->session->flashdata("messagePr") ?>
            </div>
        <?php } ?>
        <!-- Profile Image -->
        <div class="box box-success pad-profile">
            <div class="box-header with-border" style="background-color: #F7A922; width: 100%; height: 40px; margin-bottom: 2%">
                <div style="float: left"><img style="margin-left: 5%; margin-right: 10%; border-radius: 50%; border: 2px #F7A922 solid;height: 50px;" src="<?php echo base_url(); ?>/assets/images/logo.jpg"></div>
                <div style="display: inline-block; margin-left: 10%; text-height: max-size;"><strong>Mon Compte</strong></div>
            </div>
            <form method="post" enctype="multipart/form-data" action="<?php echo base_url() . 'user/add_edit' ?>" class="form-label-left">
                <div class="box-body box-profile">
                    <div class="col-md-3">
                        <div class="pic_size" id="image-holder"> 
                            <?php
                            if (file_exists('assets/images/profils/' . $user_data[0]->col_profil_pic) && isset($user_data[0]->col_profil_pic)) {
                                $profile_pic = $user_data[0]->col_profil_pic;
                            } else {
                                $profile_pic = 'user.png';
                            }
                            ?>
                            <center> <img class="thumb-image setpropileam" src="<?php echo base_url(); ?>/assets/images/profils/<?php echo isset($profile_pic) ? $profile_pic : 'user.png'; ?>" alt="User profile picture"></center>
                        </div>
                        <br>
                        <div class="fileUpload btn btn-success wdt-bg">
                            <span>Changer photo</span>
                            <input id="fileUpload" class="upload" name="col_profile_pic" type="file" accept="image/*" /><br />
                            <input type="hidden" name="fileOld" value="<?php echo isset($user_data[0]->col_profil_pic) ? $user_data[0]->col_profil_pic : ''; ?>" />
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h3>Informations personnelle:</h3>


                        <div class="form-group has-feedback">
                            <label for="exampleInputstatus">Status:</label>
                            <select name="col_status" id="status" class="form-control">
                                <option value="active" <?php echo (isset($user_data[0]->col_status) && $user_data[0]->col_status == 'active' ? 'selected="selected"' : ''); ?> >Active</option>

                                <option value="deleted" <?php echo (isset($user_data[0]->col_status) && $user_data[0]->col_status == 'deleted' ? 'selected="selected"' : ''); ?> >Deleted</option>

                            </select>
                        </div>



                        <div class="form-group has-feedback clear-both">
                            <label for="exampleInputname">Nom et Prenom:</label>
                            <input type="text" id="name" name="col_nom_prenom" value="<?php echo (isset($user_data[0]->col_nom_prenom) ? $user_data[0]->col_nom_prenom : ''); ?>" required="required" class="form-control" placeholder="Nom et Prenom">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>



                        <div class="form-group has-feedback clear-both">
                            <label for="exampleInputemail">Email:</label>
                            <input type="text" id="email" name="col_email" value="<?php echo (isset($user_data[0]->col_email) ? $user_data[0]->col_email : ''); ?>" required="required" class="form-control" placeholder="Email">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        
                        <div class="form-group has-feedback clear-both">
                            <label for="exampleInputemail">Telephone:</label>
                            <input type="tel" id="telephone" name="col_telephone" value="<?php echo (isset($user_data[0]->col_telephone) ? $user_data[0]->col_telephone : ''); ?>" required="required" class="form-control" placeholder="Telephone">
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        </div>


                        <br>
                        <h3>Changer le mot de passe:</h3>
                        <div class="form-group has-feedback">
                            <label for="exampleInputEmail1">Mot de passe actuel:</label>
                            <input id="pass11" class="form-control" pattern=".{6,}" type="password" placeholder="********" name="currentpassword" title="6-14 characters">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>                       
                        <div class="form-group has-feedback">
                            <label for="exampleInputEmail1">Nouveau mot de passe:</label>
                            <input type="password" class="form-control" placeholder="nouveau mot de passe" name="col_password">
                            <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                        </div>                       
                        <div class="form-group has-feedback">
                            <label for="exampleInputEmail1">Confirmation nouveau mot de passe:</label>
                            <input type="password" class="form-control" placeholder="Confirmer nouveau mot de passe" name="confirmPassword">
                            <span class="glyphicon glyphicon-pencil form-control-feedback"></span>
                        </div>  
                        <br>
                        <div class="form-group has-feedback sub-btn-wdt" >
                            <input type="hidden" name="col_id" value="<?php echo isset($user_data[0]->col_id) ? $user_data[0]->col_id : ''; ?>">
                            <button name="submit1" type="button" id="profileSubmit" class="btn btn-success btn-md wdt-bg">Save</button>  
                            <!-- <div class=" pull-right">
                            </div> -->
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </form>                     
            <!-- /.box -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->