<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestion_autorisations extends CI_Controller {
    
    public function __construct()                                                                                       {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('image_moo');
    }
    
    public function view_output($output){
        $this->load->view('include/header');
        $this->load->view('vuegenerique.php',$output);               
        $this->load->view('include/footer'); 
    }
    
    public function index(){
        is_login();
        $output["titre"] = "ROUTE INVALIDE";
        $this->view_output($output);
    }
    
    public function users(){
        is_login();
        try{
            if(CheckPermission("VEGAS-04")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_users');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("UTILISATEUR");
                $crud->columns('col_nom_prenom','col_role','col_email','col_telephone','col_cni_or_passport','col_login_mobile','col_profil_pic');
                $crud->unique_fields('col_nom_prenom','col_email','col_cni_or_passport');
                $crud->fields('col_nom_prenom','col_role','col_email','col_telephone','col_ville','col_quartier','col_status','col_login_mobile','col_is_delete','col_profil_pic','col_description','col_date_creation','col_password','col_password_conf');
                $crud->required_fields('col_nom_prenom','col_role','col_email','col_telephone','col_ville','col_login_mobile','col_quartier','col_status','col_is_delete');
                $crud->set_rules('col_password_conf', 'Confirmer mot de passe', 'trim|matches[col_password]');
                $crud->set_rules('col_password', 'Password', 'trim');
                $crud->set_rules('col_email','Email', "callback_validate_email_user",array("validate_email_user"=>"Format email invalid"));
                $crud->set_rules('col_telephone','Telephone', "callback_validate_telephone_user",array("validate_telephone_user"=>"Format Telephone invalid"));
                $crud->field_type('col_status', 'dropdwn',array('active'=>"ACTIF",'delete'=>"EN ATTENTE"));
                $crud->field_type('col_password', 'password');
                $crud->field_type('col_password_conf','password');
                $crud->field_type('col_is_delete','true_false',array(0=>"NON",1=>"OUI"));
                $crud->field_type('col_login_mobile','true_false',array(0=>"NON",1=>"OUI"));
                $crud->set_relation("col_role","tb_roles","col_nom");
                $crud->set_field_upload('col_profil_pic',"assets/images/profils/");
                $crud->field_type('col_date_creation','invisible');
                $crud->field_type('col_description','text');
                $crud->display_as("col_status","Statut");
                $crud->display_as("col_login_mobile","Mobile?");
                $crud->display_as("col_is_delete","Est supprimé ?");
                $crud->display_as("col_profil_pic","photo");
                $crud->display_as("col_nom_prenom","Nom et Prenom");
                $crud->display_as("col_cni_or_passport","CNI/PASSEPORT");
                $crud->display_as("col_telephone","Telephone");
                $crud->display_as("col_email","Email");
                $crud->display_as("col_password","Mot de pass");
                $crud->display_as("col_password_conf","Confirmer mot de passe");
                $crud->display_as("col_ville","Ville");
                $crud->display_as("col_quartier","Quartier");
                $crud->display_as("col_date_creation","Date Creation");
                $crud->display_as("col_description","description");
                $crud->display_as("col_role","Role");
                if(!CheckPermission("VEGAS-01")){
                    $crud->unset_add();
                }
                if(!CheckPermission("VEGAS-02")){
                    $crud->unset_edit();
                }
                $crud->callback_before_update(array($this,'encrypt_password_callback'));
                $crud->callback_before_insert(array($this,'encrypt_password_callback'));
                $crud->callback_after_upload(array($this,'example_callback_after_upload'));
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES UTILISATEURS";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function roles(){
        is_login();
        try{
            if(CheckPermission("VEGAS-09")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_roles');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("ROLE");
                $crud->columns('col_nom','col_description','les_privileges');
                $crud->fields("col_nom","col_description",'les_privileges');
                $crud->set_relation_n_n('les_privileges','tb_role_privilege','tb_privileges','col_roles','col_privileges','{col_privilege}({col_code})','col_date');
                $crud->unique_fields('col_nom');
                $crud->required_fields('col_nom');
                $crud->field_type('col_description','text');
                $crud->display_as('col_description','Description');
                $crud->display_as('col_nom','Libele');
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES ROLES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function privileges(){
        is_login();
        try{
            if(CheckPermission("VEGAS-10")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_privileges');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("PRIVILEGE");
                $crud->columns('col_code','col_privilege','col_description');
                $crud->fields('col_code','col_privilege','col_description');
                $crud->required_fields('col_code','col_privilege');
                $crud->unique_fields('col_code','col_privilege');
                $crud->field_type('col_description','text');
                $crud->display_as('col_code','Code');
                $crud->display_as('col_privilege','Libele');
                $crud->display_as('col_description','Description');
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES PRIVILEGES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    function validate_email_user($value){
        $CI = get_instance();
        return $CI->email->valid_email($value);
    }
    
    function validate_telephone_user($value){
        $tel = split(";",$value);
        foreach ($tel as $Un_tel){
            if(!(preg_match("/^237[0-9]{9}$/", $Un_tel) || preg_match("/^237[0-9]{9}$/", $Un_tel))) return FALSE;
        }
        return TRUE;
    }
    
    function encrypt_password_callback($post_array) {
        if(empty($post_array['col_password'])){
            unset($post_array['col_password']);
            unset($post_array['col_password_conf']);
        }else{
            unset($post_array['col_password_conf']);
            $post_array['col_password'] = password_hash($post_array['col_password'], PASSWORD_DEFAULT);
        }
        return $post_array;
    } 
    
    function example_callback_after_upload($uploader_response,$field_info, $files_to_upload){
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name; 
        $this->image_moo->load($file_uploaded)->resize(200,300)->save($file_uploaded,true);
        return true;
    }
}