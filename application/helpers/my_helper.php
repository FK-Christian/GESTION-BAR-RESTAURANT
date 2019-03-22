<?php

function CheckPermission($code = "") {
    $CI = get_instance();
    $role = isset($CI->session->get_userdata()['user_details'][0]->col_role)? $CI->session->get_userdata()['user_details'][0]->col_role : "-1";
    $user = isset($CI->session->get_userdata()['user_details'][0]->col_id)? $CI->session->get_userdata()['user_details'][0]->col_id : "-1";
    $tab = array(
        "tb_role_privilege"=>"tb_role_privilege.col_privileges = tb_privileges.col_id",
        "tb_roles"=>"tb_role_privilege.col_roles = tb_roles.col_id",
        "tb_users"=>"tb_roles.col_id = tb_users.col_role");
    $permission = get_data_by("tb_privileges.*","tb_privileges","tb_users.col_id = $user AND tb_users.col_role = $role AND tb_privileges.col_code = '$code'",$tab);
    return !empty($permission);
}

function get_template($code){
    $CI = get_instance();
    $CI->db->where('code', $code);
    return $CI->db->get('templates')->row();
}

function addLog($txt,$module) {	
    $fichierlog = ASSET_PATH."logs/".$module."_Log_".date("d_m_Y").".txt";
    $CI = get_instance();
    $user = isset($CI->session->get_userdata()['user_details'][0]->col_email)? $CI->session->get_userdata()['user_details'][0]->col_email : "SCRIPT";
    if (!file_exists($fichierlog)) file_put_contents ($fichierlog,"");
    file_put_contents($fichierlog,date("[j/m/Y H:i:s]")." - [$user] - $txt \r\n".file_get_contents($fichierlog));
}

function getOperateur($tel) {
    $operateur = array();
    $operateur['nom'] = 'OPERATEUR INCONNU';
    $operateur['code'] = 0;
    if (preg_match("/^23767[0-9]{7}$/", $tel) or preg_match("/^23765[0-4][0-9]{6}$/", $tel) or preg_match("/^23768[0-4][0-9]{6}$/", $tel)) {
        $operateur['nom'] = 'MTN';
        $operateur['code'] = 5;
    } else if (preg_match("/^23769[0-9]{7}$/", $tel) or preg_match("/^23765[5-9][0-9]{6}$/", $tel)) {
        $operateur['nom'] = 'ORANGE';
        $operateur['code'] = 3;
    } else if (preg_match("/^23766[0-9]{7}$/", $tel) or preg_match("/^237685[0-9]{6}$/", $tel)) {
        $operateur['nom'] = 'NEXTEL';
        $operateur['code'] = 17;
    }
    #else if(preg_match("/^2372424[0-9]{5}$/", $tel) or preg_match("/^2372434[0-9]{5}$/", $tel)) {
    else {
        $tableau = range(237242901000, 237242999999);
        if (in_array($tel, $tableau)) {
            $operateur['nom'] = 'YOOME';
            $operateur['code'] = 69;
        } else if (preg_match("/^2372[0-9]{8}$/", $tel)) {
            $operateur['nom'] = 'CAMTEL';
            $operateur['code'] = 67;
        }
    }
    return $operateur;
}

function taille_File($fichier) {
    $taille_fichier = filesize($fichier);
    if ($taille_fichier >= 1073741824) {
        $taille_fichier = round($taille_fichier / 1073741824 * 100) / 100 . " Go";
    } elseif ($taille_fichier >= 1048576) {
        $taille_fichier = round($taille_fichier / 1048576 * 100) / 100 . " Mo";
    } elseif ($taille_fichier >= 1024) {
        $taille_fichier = round($taille_fichier / 1024 * 100) / 100 . " Ko";
    } else {
        $taille_fichier = $taille_fichier . " o";
    }
    return $taille_fichier;
}

function lireRepertoireLogs(){
    $repertoire = ASSET_PATH . 'logs';
    $position = 1; $tab = array();
    if($dossier = opendir($repertoire)){
        while(false !== ($fichier = readdir($dossier))){
            if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && $fichier != 'index.html'){
                $taille = taille_File($repertoire.'/'.$fichier);
                $action = "<span><a class='btn btn-primary telecharger' href='$repertoire/".$fichier."' download>TELECHARGER</a> <a class='btn btn-danger' href='".base_url()."gestion_logs/supprimer/$fichier"."'>SUPPRIMMER</a></span>";
                $ligne = "<tr><td>$position</td><td>$fichier</td><td>$taille</td><td>$action</td></tr>";
                $position++;
                array_push($tab, $ligne);
            }
        }
        closedir($dossier);
    }
    return $tab;
}

function rendomCode(){
    return "VEGAS-".rand(0,9)."".rand(0,9)."".rand(0,9)."".rand(1,9);
}

function setting_all($keys = '') {
    $SettingAll = settings();
    if (!empty($keys)) {
        if (array_key_exists($keys, $SettingAll)){
            return $SettingAll[$keys];
        } else {
            return false;
        }
    } else {
        return $SettingAll;
    }
}

function settings() {
    $SettingAll['website'] =  'VEGAS AFRICA';
    $SettingAll['logo'] =  'logo---1_1520875300.jpg';
    $SettingAll['favicon'] =  'logo_1520875300.jpg';
    $SettingAll['SMTP_EMAIL'] =  'info@vegasafrica.net';
    $SettingAll['HOST'] =  'mail22.lwspanel.com';
    $SettingAll['PORT'] =  '587';
    $SettingAll['SMTP_SECURE'] =  'tls';
    $SettingAll['SMTP_PASSWORD'] =  'FKC@vegas2018';
    $SettingAll['mail_setting'] =  'php_mailer';
    $SettingAll['company_name'] =  'VEGAS-AFRICA SARL';
    $SettingAll['crud_list'] =  'users,User';
    $SettingAll['EMAIL'] =  'info@vegasafrica.net';
    $SettingAll['UserModules'] =  'yes';
    $SettingAll['register_allowed'] =  '1';
    $SettingAll['email_invitation'] =  '1';
    $SettingAll['admin_approval'] =  '0';
    $SettingAll['user_type'] =  '[\"Member\"]';
    $SettingAll['sms_gateway'] =  '100';
    $SettingAll['sms_login'] =  'PUSH_SAISIE_DIFFEREE';
    $SettingAll['sms_passwd'] =  'Sms57DiffeRe';
    $SettingAll['sms_link'] =  'http://213.186.50.176/eu_gateway/SendMsg.php';
    return $SettingAll;
}

function is_login() {
    if (isset($_SESSION['user_details'])) {
        return true;
    } else {
        redirect(base_url() . 'user/login', 'refresh');
    }
}

function get_data_by($select,$tableName = '',$condition = '',$jointure = '') {
    $CI = get_instance();
    $CI->db->where($condition);
    $CI->db->select($select);
    $CI->db->from($tableName);
    if(!empty($jointure)&&  is_array($jointure)){
        foreach ($jointure as $table => $jointure){
            $CI->db->join($table,$jointure);
        }
    }
    $query = $CI->db->get();
    return $query->result();
}

function sortieFacture($facture_id,$imprimer,$sendSms,$sendmail){
    addLog("TENTATIVE DE FACTURATION: [$facture_id,$imprimer,$sendSms,$sendmail]","FACTURATION");
    return true;
}
    
?>
