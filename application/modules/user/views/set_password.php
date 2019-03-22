<body class="inner-page-01 login-page">
  <?php 
    if($email=='allredyUsed'){ ?>
      <div class="container text-center">
        <h2>You have already reset your password..</h2>
         <p>Please Login <a href="<?php echo base_url().'user/login'; ?>">Here</a> </p>
      </div>
    <?php } else{  ?>
    <div class="login-box">
<!--      <div class="login-logo">
        <h2 class="text-center"><strong>Set New Password</strong>
          <div class="line1 green-bg"></div>
        </h2>
      </div>-->
      <div class="inner_container loginpage login-box login-box-body" style="border: 3px #F7A922 double">
            <img style="margin-left: 25%; border-radius: 50%; border: 2px #F7A922 solid; height: 150px" src="<?php echo base_url();?>/assets/images/logo.jpg">
            <p class="login-box-msg"><a href="#"><b>Entre le nouveau Mot de passe</b></a></p>
        <section>
          <form class="createaccount" action="<?php echo base_url().'user/reset_password'?>" method="post">
            <input type="hidden" name="col_email" value="<?php echo $email; ?>" class="form-control" placeholder=""  />
            <div class="form-group">
              <input type="password" id="password1" name="password_confirmation" class="form-control" placeholder="Nouveau mot de passe..." data-validation="required" />
            </div>
            <div class="form-group">
              <input type="password" id="password2" name="col_password" class="form-control" placeholder="conrfirmez le nouveau mot de passe" data-validation="confirmation" />
            </div>
            <div>
                <button type="submit" name="sub" value="Set password" class="btn btn-primary btn-block btn-flat btn-color">Enregistrer ce changement</button>
            </div>
          </form>
          <!-- form -->
        </section>
      <!-- content -->
      </div>
    </div>
      <?php } ?>
  <script type="text/javascript">
      window.onload = function () {
      document.getElementById("password1").onchange = validatePassword;
      document.getElementById("password2").onchange = validatePassword;
      }
      function validatePassword(){
        var pass2=document.getElementById("password2").value;
        var pass1=document.getElementById("password1").value;
        if(pass1!=pass2)
        {
          document.getElementById("password2").setCustomValidity("Passwords Don't Match");
        }
        else{
             document.getElementById("password2").setCustomValidity('');
        }
      }
    </script>
</body>
