<?php defined("BASEPATH") OR exit("No direct script access allowed");

class Gestion_logs extends CI_Controller {

  function __construct() {
    parent::__construct();
  }

  /**
     * This function is used to load page view
     * @return Void
     */
  public function index(){   
    $this->load->view("include/header");
    $this->load->view("index");
    $this->load->view("include/footer");
  }
  public function supprimer($fichier){
      if(CheckPermission("VEGAS-06")){
        if(file_exists(ASSET_PATH.'logs/'.$fichier)){
          unlink(ASSET_PATH.'logs/'.$fichier);
        }
      }else{
          $this->session->set_flashdata('messagePr', "VOUS N'AVEZ PAS LES HABILITATIONS POUR EFFECTUER CETTE ACTION.");
      }
      redirect(base_url()."gestion_logs");
  }
}
?>