<?php if(CheckPermission("VEGAS-07")){ ?>
<li class="header" style="background-color: #761c19"><strong>MANAGEMENT WEB</strong></li>
<li> 
    <a href="<?php echo base_url('gestion_web/activites');?>"> <i class="fa fa-briefcase" style='color: #F7A922'></i> <span><strong>ACTIVITES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_web/espaces');?>"> <i class="fa fa-map" style='color: #F7A922'></i> <span><strong>ESPACES</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_web/stocks');?>"> <i class="fa fa-cubes" style='color: #F7A922'></i> <span><strong>STOCKS</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_web/factures');?>"> <i class="fa fa-file-pdf-o" style='color: #F7A922'></i> <span><strong>FACTURES</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_web/commandes');?>"> <i class="fa fa-list-alt" style='color: #F7A922'></i> <span><strong>COMMANDES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_web/vente');?>"> <i class="fa fa-money" style='color: #F7A922'></i> <span><strong>VENTES</strong></span></a>
</li> 
<?php }if(CheckPermission("VEGAS-08")){ ?>
<li class="header" style="background-color: #761c19"><strong>MANAGEMENT MOBILE</strong></li>
<li> 
    <a href="<?php echo base_url('gestion_mobile/activites');?>"> <i class="fa fa-briefcase" style='color: #F7A922'></i> <span><strong>ACTIVITES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_mobile/espaces');?>"> <i class="fa fa-map" style='color: #F7A922'></i> <span><strong>ESPACES</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_mobile/stocks');?>?page_active=1"> <i class="fa fa-cubes" style='color: #F7A922'></i> <span><strong>STOCKS</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_mobile/factures');?>?page_active=1"> <i class="fa fa-file-pdf-o" style='color: #F7A922'></i> <span><strong>FACTURES</strong></span></a>
</li>
<li> 
    <a href="<?php echo base_url('gestion_mobile/commandes');?>?page_active=1"> <i class="fa fa-list-alt" style='color: #F7A922'></i> <span><strong>COMMANDES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_mobile/ventes');?>"> <i class="fa fa-money" style='color: #F7A922'></i> <span><strong>VENTES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_mobile/clients');?>?page_active=1"> <i class="fa fa-user-plus" style='color: #F7A922'></i> <span><strong>CLIENTS</strong></span></a>
</li> 
<?php }if(CheckPermission("VEGAS-04") || CheckPermission("VEGAS-09") || CheckPermission("VEGAS-10") || CheckPermission("VEGAS-05")){ ?>
<li class="header" style="background-color: #761c19"><strong>ADMINISTRATION</strong></li>
<li class="<?= ($this->router->method === "userTable") ? "active" : "not-active" ?>"> 
    <a href="<?php echo base_url(); ?>gestion_autorisations/users"> <i class="fa fa-users" style="color: #F7A922"></i> <span><strong>UTILISATEURS</strong></span></a>
</li>  
<li> 
    <a href="<?php echo base_url('gestion_autorisations/roles');?>"> <i class="fa fa-list-alt" style='color: #F7A922'></i> <span><strong>ROLES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_autorisations/privileges');?>"> <i class="fa fa-list-alt" style='color: #F7A922'></i> <span><strong>PRIVILEGES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('templates/initialisation');?>"> <i class="fa fa-html5" style='color: #F7A922'></i> <span><strong>TEMPLATES</strong></span></a>
</li> 
<li> 
    <a href="<?php echo base_url('gestion_logs');?>"> <i class="fa fa-file" style='color: #F7A922'></i> <span><strong>LES LOGS</strong></span></a>
</li> 
<?php } ?>
<li class="header" style="background-color: #761c19"><strong>AUTRES</strong></li>
<li class="<?= ($this->router->class === "about") ? "active" : "not-active" ?>">
    <a href="<?php echo base_url("about"); ?>"><i class="fa fa-info-circle" style="color: #F7A922"></i> <span><strong>A PROPOS</strong></span></a>
</li>