<link href="<?php echo ASSET_PATH . "css/"; ?>bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="<?php echo ASSET_PATH . "js/"; ?>bootstrap.min.js"></script>
<script src="<?php echo ASSET_PATH . "js/"; ?>jquery-1.11.2.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style media='all' rel='stylesheet' type='text/css'>
    /*  bhoechie tab */
    div.bhoechie-tab-container{
        z-index: 10;
        background-color: #ffffff;
        padding: 0 !important;
        border-radius: 4px;
        -moz-border-radius: 4px;
        border:1px solid #ddd;
        margin-top: 20px;
        margin-left: 50px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        -moz-box-shadow: 0 6px 12px rgba(0,0,0,.175);
        background-clip: padding-box;
        opacity: 0.97;
        filter: alpha(opacity=97);
    }
    div.bhoechie-tab-menu{
        padding-right: 0;
        padding-left: 0;
        padding-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group{
        margin-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group>a{
        margin-bottom: 0;
    }
    div.bhoechie-tab-menu div.list-group>a .glyphicon,
    div.bhoechie-tab-menu div.list-group>a .fa {
        color: #F7A922; //#5A55A3; 
    }
    div.bhoechie-tab-menu div.list-group>a:first-child{
        border-top-right-radius: 0;
        -moz-border-top-right-radius: 0;
    }
    div.bhoechie-tab-menu div.list-group>a:last-child{
        border-bottom-right-radius: 0;
        -moz-border-bottom-right-radius: 0;
    }
    div.bhoechie-tab-menu div.list-group>a.active,
    div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
    div.bhoechie-tab-menu div.list-group>a.active .fa{
        background-color: #F7A922;
        background-image: #F7A922;
        color: #ffffff;
    }
    div.bhoechie-tab-menu div.list-group>a.active:after{
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        margin-top: -13px;
        border-left: 0;
        border-bottom: 13px solid transparent;
        border-top: 13px solid transparent;
        border-left: 10px solid #F7A922;
    }

    div.bhoechie-tab-content{
        background-color: #ffffff;
        /* border: 1px solid #eeeeee; */
        width: 100%;
        padding-left: 20px;
        padding-top: 10px;
    }

    div.bhoechie-tab div.bhoechie-tab-content:not(.active){
        display: none;
    }
    
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

<div class="container">
    <div class="row">
        <div class="col-xs-12 bhoechie-tab-container">
            <div class="col-xs-2 bhoechie-tab-menu">
                <div class="list-group">
                    <a href="#" class="list-group-item active text-center">
                        <h4 class="glyphicon glyphicon-briefcase"></h4><br/><strong>ACTIVITES</strong>
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-gift"></h4><br/><strong>ESPACES</strong>
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-list-alt"></h4><br/><strong>COMMANDES</strong>
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-credit-card"></h4><br/><strong>FACTURES</strong>
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-edit"></h4></h4><br/><strong>STOCKS</strong>
                    </a>
                    <a href="#" class="list-group-item text-center">
                        <h4 class="glyphicon glyphicon-user"></h4></h4><br/><strong>PROFIL</strong>
                    </a>
                </div>
            </div>
            
            <div class="col-xs-10 bhoechie-tab">
                <div class="box-header with-border" style="background-color: #F7A922; width: 100%; margin-bottom: 2%;">
                    <img style="margin-left: 5%; margin-right: 10%; border-radius: 50%; border: 2px #F7A922 solid;height: 50px; float: left" src="<?php echo base_url(); ?>/assets/images/logo.jpg">
                    <h3 id="titrePage"></h3><div id="stockage"><span data-cle="0--0--0--0--0"></span></div>
                </div>
                <!-- flight section -->
                <div class="bhoechie-tab-content active">
                    <?php $compteur = 1; foreach ($activites as $activite){?>
                        <?php if($compteur==4){?>
                            <div class="col-xs-12" style="height: 80px;"></div>
                        <?php $compteur = 0; }?>
                        <div class="image-texte col-xs-4">
                            <a href="#"><img style="height: 150px;" src="<?php echo base_url()."/assets/images/activites/".$activite->col_photo; ?>"></a>
                            <div class="text-on-image"><strong><?php echo $activite->col_libele; ?></strong></div>
                        </div>
                    <?php $compteur++;}?>
                </div>
                <!-- train section -->
                <div class="bhoechie-tab-content">
                    <?php $compteur = 1; foreach ($espaces as $espace){?>
                        <?php if($compteur==4){?>
                            <div class="col-xs-12" style="height: 80px;"></div>
                        <?php $compteur = 0; }?>
                        <div class="image-texte col-xs-4">
                            <a href="#"><img style="height: 150px;" src="<?php echo base_url()."/assets/images/espaces/".$espace->col_photo; ?>"></a>
                            <div class="text-on-image"><strong><?php echo $espace->col_libele; ?></strong></div>
                        </div>
                    <?php $compteur++;}?>
                </div>

                <!-- hotel search -->
                <div class="bhoechie-tab-content">
                    <center>
                        <h1 class="glyphicon glyphicon-home" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Hotel Directory</h3>
                    </center>
                </div>
                <div class="bhoechie-tab-content">
                    <center>
                        <h1 class="glyphicon glyphicon-cutlery" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Restaurant Diirectory</h3>
                    </center>
                </div>
                <div class="bhoechie-tab-content">
                    <center>
                        <h1 class="glyphicon glyphicon-credit-card" style="font-size:12em;color:#55518a"></h1>
                        <h2 style="margin-top: 0;color:#55518a">Cooming Soon</h2>
                        <h3 style="margin-top: 0;color:#55518a">Credit Card</h3>
                    </center>
                </div>
                <div class="bhoechie-tab-content">
                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url() . 'user/add_edit' ?>" class="form-label-left">
                        <div class="box-body box-profile">
                            <div class="col-xs-4">
                                <div id="image-holder"> 
                                    <?php
                                    $user_data = $this->session->get_userdata()['user_details'];
                                    if (file_exists('assets/images/profils/' . $user_data[0]->col_profil_pic) && isset($user_data[0]->col_profil_pic)) {
                                        $profile_pic = $user_data[0]->col_profil_pic;
                                    } else {
                                        $profile_pic = 'user.png';
                                    }
                                    ?>
                                    <img style="border: 2px #F7A922 solid; height: 300px; width: 100%" src="<?php echo base_url(); ?>/assets/images/profils/<?php echo isset($profile_pic) ? $profile_pic : 'user.png'; ?>" alt="User profile picture">
                                </div>
                                <br>
                                <div class="fileUpload btn btn-primary wdt-bg">
                                    <span>Changer photo</span>
                                    <input id="fileUpload" class="upload" name="col_profile_pic" type="file" accept="image/*" /><br />
                                    <input type="hidden" name="fileOld" value="<?php echo isset($user_data[0]->col_profil_pic) ? $user_data[0]->col_profil_pic : ''; ?>" />
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group has-feedback clear-both">
                                    <label for="exampleInputname">Nom et Prenom:</label>
                                    <input type="text" id="name" name="col_nom_prenom" value="<?php echo (isset($user_data[0]->col_nom_prenom) ? $user_data[0]->col_nom_prenom : ''); ?>" required="required" class="form-control" placeholder="Nom et Prenom">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback clear-both">
                                    <label for="exampleInputemail">Email:</label>
                                    <input type="text" id="email" name="col_email" value="<?php echo (isset($user_data[0]->col_email) ? $user_data[0]->col_email : ''); ?>" required="required" class="form-control" placeholder="Email">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback clear-both">
                                    <label for="exampleInputemail">Telephone:</label>
                                    <input type="tel" id="telephone" name="col_telephone" value="<?php echo (isset($user_data[0]->col_telephone) ? $user_data[0]->col_telephone : ''); ?>" required="required" class="form-control" placeholder="Telephone">
                                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                </div>
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
                                <div class="form-group has-feedback sub-btn-wdt" >
                                    <input type="hidden" name="col_id" value="<?php echo isset($user_data[0]->col_id) ? $user_data[0]->col_id : ''; ?>">
                                    <button name="submit1" type="button" id="profileSubmit" class="btn btn-success btn-xs wdt-bg"><h4 class="glyphicon glyphicon-edit"></h4>MODIFICATION</button> 
                                    <a href="<?php echo base_url('user/logout'); ?>" class="btn btn-danger btn-xs wdt-bg"><h4 class="glyphicon glyphicon-log-out"></h4>DECONNEXION</a> 
                                </div>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var tab = ["LES ACTIVITES","LES ESPACES","LES COMMANDES","LES FACTURES","LES STOCKS","MON PROFIL"];
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
            $('#titrePage').html("<strong>"+tab[index]+" </strong>");
        });
        $('#titrePage').html("<strong>"+tab[$("div.bhoechie-tab-menu>div.list-group>a.active").index()]+" </strong>");
        $("#fileUpload").on('change', function () {
            if (typeof (FileReader) != "undefined") {
                var image_holder = $("#image-holder");
                image_holder.empty();
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("<img />", {
                        "src": e.target.result,
                        "class": "thumb-image setpropileam"
                    }).appendTo(image_holder);
                }
                image_holder.show();
                reader.readAsDataURL($(this)[0].files[0]);
            } else {
                alert("This browser does not support FileReader.");
            }
        });
    });
</script>