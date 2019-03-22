<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
<!--            <a href="<?php // echo base_url(); ?>"><b>VEGAS-AFRICA MANAGEMENT</b></a>-->
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body" style="border: 3px #F7A922 double">
            <img style="margin-left: 25%; border-radius: 50%; border: 2px #F7A922 solid; height: 150px" src="<?php echo base_url();?>/assets/images/logo.jpg">
            <p class="login-box-msg"><a href="<?php echo base_url(); ?>"><b>Connectez Vous ICI...</b></a></p>
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">      
                    <?php echo $this->session->flashdata("messagePr") ?>
                </div>
            <?php } ?>
            <form action="<?php echo base_url() . 'user/auth_user'; ?>" method="post">
                <div class="form-group has-feedback">
                    <input type="text" name="col_email" class="form-control" id="" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="col_password" class="form-control" id="pwd" placeholder="Password" >
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <a href="forgetpassword" class="forgot ">J'ai oublié mon mot de passe...</a>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat btn-color">Connection</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
</body>
