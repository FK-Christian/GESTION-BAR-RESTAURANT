<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestion_web extends CI_Controller {
    
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
    
    public function activites(){
        is_login();
        try{
            if(CheckPermission("VEGAS-15")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_activites');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("ACTIVITES");
                $crud->columns('col_code','col_libele','col_type','col_photo','col_description');
                $crud->unique_fields('col_libele','col_code');
                $crud->fields('col_code','col_libele','col_type','col_photo','col_description','les_stocks');
                $crud->set_relation_n_n('les_stocks','tb_stock_activite','tb_stocks','col_activite','col_stock','{col_libele}({col_qte})','col_date');
                $crud->required_fields('col_code','col_libele','col_type');
                $crud->set_field_upload('col_photo',"assets/images/activites/");
                $crud->field_type('col_description','text');
                $crud->display_as("col_code","code");
                $crud->display_as("col_libele","libele");
                $crud->display_as("col_type","type");
                $crud->display_as("col_photo","photo");
                $crud->display_as("col_description","description");
                $crud->display_as("les_stocks","articles");
                $crud->callback_after_upload(array($this,'example_callback_after_upload'));
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES ACTIVITES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function espaces(){
        is_login();
        try{
            if(CheckPermission("VEGAS-16")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_espace');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("ESPACE");
                $crud->columns('col_libele','col_position','col_statut','col_agis_sur_stock','col_nbre_place');
                $crud->fields('col_libele','col_photo','col_position','col_statut','col_agis_sur_stock','col_nbre_place','les_activite');
                $crud->set_relation_n_n('les_activite','tb_activite_espace','tb_activites','col_espace','col_activite','{col_libele}({col_code})','col_date');
                $crud->unique_fields('col_libele');
                $crud->set_field_upload('col_photo',"assets/images/espaces/");
                $crud->required_fields('col_libele','col_photo','col_position','col_statut','col_agis_sur_stock','col_nbre_place');
                $crud->display_as('col_libele','libele');
                $crud->display_as('col_photo','photo');
                $crud->display_as('col_position','Position');
                $crud->display_as('col_statut','statut');
                $crud->display_as('col_agis_sur_stock','Agit sur stock?');
                $crud->display_as('col_nbre_place','Nbre place');
                $crud->display_as('les_activite','Activite');
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES ESPACES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function stocks(){
        is_login();
        try{
            if(CheckPermission("VEGAS-17")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_stocks');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("STOCK");
                $crud->columns('col_libele', 'col_qte', 'col_puv', 'col_pua');
                $crud->fields('col_libele', 'col_qte', 'col_est_virtuel', 'col_pua', 'col_puv', 'col_description', 'col_fornisseur');
                $crud->required_fields('col_libele', 'col_qte', 'col_est_virtuel', 'col_pua', 'col_puv', 'col_description');
                $crud->set_relation("col_fornisseur","tb_users","col_nom_prenom","1=1");
                $crud->field_type('col_est_virtuel','true_false',array(0=>"NON",1=>"OUI"));
                $crud->unique_fields('col_libele');
                $crud->field_type('col_description','text');
                $crud->display_as('col_qte','Qte en stock');
                $crud->display_as('col_libele','Libele');
                $crud->display_as('col_est_virtuel','Virtuel ?');
                $crud->display_as('col_pua','P.U.A');
                $crud->display_as('col_puv','P.U.V');
                $crud->display_as('col_description','Description');
                $crud->display_as('col_historique','historique');
                $crud->display_as('col_fornisseur','fournisseur');
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES STOCKS";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function factures(){
        is_login();
        try{
            if(CheckPermission("VEGAS-18")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_factures');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("FACTURE");
                $crud->columns('col_date_creation', 'col_date_reglement', 'col_caissier', 'col_generer_sms', 'col_generer_mail', 'col_generer_print','commandes');
                $crud->fields('col_date_reglement', 'col_caissier','col_description' ,'col_generer_sms', 'col_generer_mail', 'col_generer_print','commandes');
                $crud->required_fields('col_date_reglement', 'col_caissier' ,'col_generer_sms', 'col_generer_mail', 'col_generer_print','commandes');
                $crud->set_relation_n_n('commandes','tb_factures_commandes','tb_commandes','col_facture','col_commande','{col_espace}({col_activite})','col_date');
                $crud->field_type('col_description','text');
                $crud->set_relation("col_caissier","tb_users","col_nom_prenom","1=1");
                $crud->field_type('col_generer_sms','true_false',array(0=>"NON",1=>"OUI"));
                $crud->field_type('col_generer_mail','true_false',array(0=>"NON",1=>"OUI"));
                $crud->field_type('col_generer_print','true_false',array(0=>"NON",1=>"OUI"));
                $crud->display_as("col_date_creation","Date creation");
                $crud->display_as("col_date_reglement","date reglement");
                $crud->display_as("col_caissier","caissier");
                $crud->display_as("col_generer_sms","sms?");
                $crud->display_as("col_generer_mail","mail?");
                $crud->display_as("col_description","Description");
                $crud->display_as("col_generer_print","imprimmer?");
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES FACTURES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function commandes(){
        is_login();
        try{
            if(CheckPermission("VEGAS-19")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_commandes');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("COMMANDE");
                $crud->columns('col_date', 'col_statut', 'col_servant', 'col_client', 'col_espace', 'col_activite', 'articles');
                $crud->fields('col_date', 'col_statut', 'col_servant', 'col_client', 'col_espace', 'col_activite','col_description','articles');
                $crud->required_fields('col_date', 'col_statut', 'col_servant', 'col_client', 'col_espace', 'col_activite');
                $crud->set_relation_n_n('articles','tb_commandes_stocks','tb_stocks','col_commande','col_stock','{col_libele}({tb_stocks.col_qte})','col_puv_reel');
                $crud->field_type('col_description','text');
                $crud->set_relation("col_servant","tb_users","col_nom_prenom","1=1");
                $crud->set_relation("col_client","tb_users","col_nom_prenom","1=1");
                $crud->set_relation("col_espace","tb_espace","col_libele");
                $crud->set_relation("col_activite","tb_activites","col_libele");
                $crud->display_as("col_date","Date");
                $crud->display_as("col_statut","statut");
                $crud->display_as("col_servant","servant");
                $crud->display_as("col_client","client");
                $crud->display_as("col_espace","espace");
                $crud->display_as("col_activite","activite");
                $crud->display_as("col_description","Description?");
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES COMMANDES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    public function vente(){
        is_login();
        try{
            if(CheckPermission("VEGAS-20")){
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('tb_commandes_stocks');
                $crud->set_primary_key("id","col_id");
                $crud->set_subject("VENTE");
                $crud->columns('col_stock', 'col_commande', 'col_qte', 'col_puv_reel');
                $crud->fields('col_stock', 'col_commande', 'col_qte', 'col_puv_reel');
                $crud->required_fields('col_stock', 'col_commande', 'col_qte', 'col_puv_reel');
                $crud->set_relation("col_stock","tb_stocks","col_libele({col_qte})");
                $crud->set_relation("col_commande","tb_commandes","col_id");
                $crud->display_as('col_stock','Stock');
                $crud->display_as('col_commande','Commande');
                $crud->display_as('col_qte','Qte vendu');
                $crud->display_as('col_puv_reel','Prix unitaire de vente');
                $crud->unset_add();
                $out = $crud->render();
                $output = (array)$out;
            }else{
               $output["output"] = "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.";
            }
            $output["titre"] = "GESTION DES VENTES";
            $this->view_output($output);
        }  catch (Exception $ex){
           show_error($ex->getMessage().' --- '.$ex->getTraceAsString()); 
        }
    }
    
    function example_callback_after_upload($uploader_response,$field_info, $files_to_upload){
        $file_uploaded = $field_info->upload_path.'/'.$uploader_response[0]->name; 
        addLog($file_uploaded, 'TEST');
        $this->image_moo->load($file_uploaded)->resize(200,300)->save($file_uploaded,true);
        return true;
    }
}