<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gestion_mobile extends CI_Controller {
    
    public function __construct()                                                                                       {
        parent::__construct();
        $this->load->model('Gestion_mobile_model');
    }
    
    public function view_output($page,$output){
        $this->load->view('include/header');
        $this->load->view($page,$output);               
        $this->load->view('include/footer');  
    }
    
    public function index(){
        is_login();
        //$this->view_output("index",$data);
        $data["titre"] = "PRENDRE UNE COMMANDE";
        $this->view_output("les_ventes_vue",$data);
    }
    
    public function ventes(){
        is_login();
        $data["titre"] = "PRENDRE UNE COMMANDE";
        $this->view_output("les_ventes_vue",$data);
    }
    
    public function activites(){
        is_login();
        $data["titre"] = "LISTE DES ACTIVITES";
        $data["activites"] = $this->Gestion_mobile_model->get_data_by("*","tb_activites","1=1");
        $this->view_output("les_activites_vue",$data);
    }
    
    public function espaces(){
        is_login();
        $where = ($this->input->get('activite') != '') ? "tb_activites.col_id = ".$this->input->get('activite') : "tb_activites.col_id > 0";
        $data["titre"] = "LISTE DES ESPACES";
        $data["espaces"] = $this->Gestion_mobile_model->get_data_by("tb_espace.*",'tb_espace',$where,array('tb_activite_espace'=>'tb_activite_espace.col_espace = tb_espace.col_id','tb_activites' => 'tb_activites.col_id = tb_activite_espace.col_activite'));
        $this->view_output("les_espaces_vue",$data);
    }
    
    public function commandes(){
        is_login();
        $where = "";
        if(($this->input->get('espace') != '') && ($this->input->get('espace') != 'all')){
            $where .= "tb_espace.col_id = ".$this->input->get('espace');
        }else{
            $where .= "tb_espace.col_id > 0"; 
        }
        if(($this->input->get('activite') != '') && ($this->input->get('activite') != 'all')){
            $where .= " AND tb_activites.col_id = ".$this->input->get('activite');  
        }else{
            $where .= " AND tb_activites.col_id > 0";  
        }
        if(($this->input->get('statut') != '') && ($this->input->get('statut') != 'all')){
            $where .= " AND tb_commandes.col_statut = '".$this->input->get('statut')."'";  
        }else{
            $where .= " AND tb_commandes.col_statut <> ''";  
        }
        $where .= ($this->input->get('servant') != '') ? " AND (tb_users.col_nom_prenom like('%".$this->input->get('servant')."%') OR (tb_users.col_telephone like('%".$this->input->get('servant')."%'))" : " AND tb_users.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('client') != '') ? " AND (client.col_nom_prenom like('%".$this->input->get('client')."%') OR (client.col_telephone like('%".$this->input->get('client')."%'))" : " AND client.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('dateDebut') != '' && $this->input->get('dateFin') != '') ? " AND tb_commandes.col_date between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')" : " AND tb_commandes.col_date > '2018-04-20 14:57:20'";
        $data["titre"] = "LISTE DES COMMANDES";
        $tab = array(
            'tb_users' => 'tb_users.col_id = tb_commandes.col_servant',
            'tb_users client' => 'client.col_id = tb_commandes.col_client',
            'tb_espace' => 'tb_espace.col_id = tb_commandes.col_espace',
            'tb_activites' => 'tb_activites.col_id = tb_commandes.col_activite');
        $liste = "tb_commandes.col_id,tb_commandes.col_date as date,tb_commandes.col_statut as statut,";
        $liste .= "client.col_nom_prenom as client_name,client.col_telephone as client_tel,";
        $liste .= "tb_users.col_nom_prenom as servant,tb_espace.col_libele as espace,tb_activites.col_libele as activite";
        $data["commandes"] = $this->Gestion_mobile_model->get_data_by($liste,'tb_commandes',$where,$tab);
        
        $retour = array();
        if(count($data["commandes"])>0){
            foreach ($data["commandes"] as $uneCommande){
                $temp["col_id"] = $uneCommande->col_id;
                $temp["date"] = $uneCommande->date;
                $temp["client_name"] = $uneCommande->client_name;
                $temp["client_tel"] = $uneCommande->client_tel;
                $temp["servant"] = $uneCommande->servant;
                $temp["espace"] = $uneCommande->espace;
                $temp["activite"] = $uneCommande->activite;
                $temp["selection"] = "";
                switch ($uneCommande->statut){ # $temp["statut"] = $uneCommande->statut;
                    case'O':
                        $temp["statut"] = "<button class='btn bg-warning btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["selection"] = "<input type='checkbox' name='commandes' class='checkboxTab' value='$uneCommande->col_id'/>";
                        $temp["action"] = "<button onclick='modCommande($uneCommande->col_id,1)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";
                        break;
                    case'F':
                        $temp["statut"] = "<button class='btn bg-danger btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["action"] = "<button onclick='modCommande($uneCommande->col_id,0)' class='btn btn-primary btn-sm'> <i class='fa fa-eye'></i></button>";
                        break;
                    case'P':
                        $temp["statut"] = "<button class='btn bg-success btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["action"] = "<button onclick='modCommande($uneCommande->col_id,0)' class='btn btn-primary btn-sm'> <i class='fa fa-eye'></i></button>";
                        break;
                    case'R':
                        $temp["statut"] = "<button class='btn bg-primary btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["selection"] = "<input type='checkbox' name='commandes' class='checkboxTab' value='$uneCommande->col_id'/>";
                        $temp["action"] = "<button onclick='modCommande($uneCommande->col_id,1)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";
                        break;
                }
                array_push($retour,$temp);
            }
        }else{
            $temp["col_id"] = -1;
            array_push($retour,$temp);
        }
        $data["commandes"] = json_encode($retour);
        if(!CheckPermission("VEGAS-19")){
            $data["commandes"] = json_encode(array("col_id"=>-1));
        }
        if(($this->input->get('page_active') != '') && ($this->input->get('page_active') == 1)){
            $this->view_output("les_commandes_vue",$data);
        }else{
            echo $data["commandes"];
        }
    }
    
    public function commandesDetails(){
        $id = $this->input->post('id');
        $modifier = $this->input->post('modification');
        $tab = array(
            'tb_users' => 'tb_users.col_id = tb_commandes.col_servant',
            'tb_users client' => 'client.col_id = tb_commandes.col_client',
            'tb_espace' => 'tb_espace.col_id = tb_commandes.col_espace',
            'tb_activites' => 'tb_activites.col_id = tb_commandes.col_activite');
        $liste = "tb_commandes.col_id,tb_commandes.col_date as date,tb_commandes.col_statut as statut,";
        $liste .= "client.col_id as client_cle,client.col_nom_prenom as client_name,client.col_telephone as client_tel,";
        $liste .= "tb_users.col_id as idServant,tb_users.col_nom_prenom as servant,tb_users.col_telephone as servant_tel,tb_espace.col_libele as espace,tb_activites.col_libele as activite";
        $where = 'tb_commandes.col_id = '.$id;
        $laCommande = $this->Gestion_mobile_model->get_data_by($liste,'tb_commandes',$where,$tab)[0];
        $tab = array(
            'tb_commandes_stocks' => 'tb_commandes_stocks.col_commande = tb_commandes.col_id',
            'tb_stocks' => 'tb_stocks.col_id = tb_commandes_stocks.col_stock');
        $liste = "tb_stocks.col_id,tb_commandes_stocks.col_qte as qte_vendu,tb_commandes_stocks.col_puv_reel,";
        $liste .= "tb_stocks.col_qte,tb_stocks.col_libele,tb_stocks.col_puv";
        $lesArticles = $this->Gestion_mobile_model->get_data_by($liste,'tb_commandes',$where,$tab);
        $testMod = False;
        switch ($laCommande->statut){ # $temp["statut"] = $uneCommande->statut;
            case'O':
                $laCommande->statut = "<input type='text' style='text-align:center; background-color: #F7A922'  name='statut' value='OUVERTE' class='form-control' placeholder='Name' readonly>";
                $testMod = $modifier && ($laCommande->idServant == $this->session->get_userdata()['user_details'][0]->col_id);
                break;
            case'F':
                $laCommande->statut = "<input type='text' style='text-align:center; background-color: #c7254e'  name='statut' value='FACTURATION' class='form-control' placeholder='Name' readonly>";
                break;
            case'P':
                $laCommande->statut = "<input type='text' style='text-align:center; background-color: #00ca6d'  name='statut' value='PAYEE' class='form-control' placeholder='Name' readonly>";
                break;
            case'R':
                $laCommande->statut = "<input type='text' style='text-align:center; background-color: #F7A922'  name='statut' value='REJETE' class='form-control' placeholder='Name' readonly>";
                $testMod = $modifier && ($laCommande->idServant == $this->session->get_userdata()['user_details'][0]->col_id);
                break;
        }
        $formulaire = ""
                . "<form role='form bor-rad' id ='formModCommande' enctype='multipart/form-data' action='#' method='post'>"
                    . "<div class='box-body'>"
                        . "<div class='row'>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>code</label>"
                                    . "<input type='text' name='code' value='COM-$laCommande->col_id' class='form-control' placeholder='Name' readonly>"
                                    . "<input type='hidden' name='id' id='cleBD' value='$laCommande->col_id' class='form-control'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>date</label>"
                                    . "<input type='text' name='dateC' value='$laCommande->date' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>statut</label>"
                                    . "$laCommande->statut"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-8'>"
                                . "<div class='form-group'>"
                                    . "<label class='control-label'>CLIENT:</label>"
                                        . "<select name='client' id='client' class='form-control'>";
                                            $lesClients = get_data_by('*','tb_users','1=1');
                                            foreach ($lesClients as $client){
                                               $formulaire .= "<option value='$client->col_id'".(($laCommande->client_cle == $client->col_id)? 'selected':'').">$client->col_nom_prenom ($client->col_telephone)</option>";
                                            }
                        $formulaire .= "</select>" 
                                    . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>activite</label>"
                                    . "<input type='text' name='activite' value='$laCommande->activite' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-8'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>serveur</label>"
                                    . "<input type='text' name='serveur' value='$laCommande->servant ($laCommande->servant_tel)' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>espace</label>"
                                    . "<input type='text' name='espace' value='$laCommande->espace' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>";
        foreach ($lesArticles as $unArticle){
            $formulaire .= ""             
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>article</label>"
                                    . "<input type='text' name='libele' value='$unArticle->col_libele' class='form-control' placeholder='Name' readonly>"
                                    . "<input type='hidden' name='idStock_$unArticle->col_id' value='$unArticle->col_id' class='form-control'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-2'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>QTE-S</label>"
                                    . "<input type='text' name='qtes' value='$unArticle->col_qte' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-2'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>PUV</label>"
                                    . "<input type='text' name='puv' value='$unArticle->col_puv' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-2'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>QTE-V</label>"
                                    . "<input type='text' name='qtev_$unArticle->col_id' value='$unArticle->qte_vendu' class='form-control' placeholder='Name'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-2'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>PUV-R</label>"
                                    . "<input type='text' name='puvr_$unArticle->col_id' value='$unArticle->col_puv_reel' class='form-control' placeholder='Name'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Supp</label>"
                                    . "<input type='checkbox' name='supp_$unArticle->col_id'/>"
                                . "</div>"
                            . "</div>";
        }
        if($testMod){
            $formulaire .= ""
                        . "<div class='col-xs-2'></div>"
                        . "<button type='button' onclick='modifierCommande(0)' class='col-xs-2 bg-primary' aria-label='Close'><span aria-hidden='true'>MODIFIER</span></button>"
                        . "<div class='col-xs-1'></div>"
                        . "<button type='button' onclick='modifierCommande(1)' class='col-xs-2 bg-danger' aria-label='Close'><span aria-hidden='true'>SUPPRIMER</span></button>"
                        . "<div class='col-xs-1'></div>"
                        . "<button type='button' class='col-xs-2 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
        }
        $formulaire .= ""
                    . "</div>"
                    . "</div>"
                . "</form>";
        echo json_encode(array('formulaire'=>$formulaire));
    }
    
    public function factures(){
        is_login();
        $where = "";
        if(($this->input->get('espace') != '') && ($this->input->get('espace') != 'all')){
            $where .= "tb_espace.col_id = ".$this->input->get('espace');
        }else{
            $where .= "tb_espace.col_id > 0"; 
        }
        if(($this->input->get('activite') != '') && ($this->input->get('activite') != 'all')){
            $where .= " AND tb_activites.col_id = ".$this->input->get('activite');  
        }else{
            $where .= " AND tb_activites.col_id > 0";  
        }
        if(($this->input->get('statut') != '') && ($this->input->get('statut') != 'all')){
            $where .= " AND tb_factures.col_statut = '".$this->input->get('statut')."'";  
        }else{
            $where .= " AND tb_factures.col_statut <> ''";  
        }
        $where .= ($this->input->get('servant') != '') ? " AND (tb_users.col_nom_prenom like('%".$this->input->get('servant')."%') OR (tb_users.col_telephone like('%".$this->input->get('servant')."%'))" : " AND tb_users.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('caissier') != '') ? " AND (caissier.col_nom_prenom like('%".$this->input->get('caissier')."%') OR (caissier.col_telephone like('%".$this->input->get('caissier')."%'))" : " AND caissier.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('client') != '') ? " AND (client.col_nom_prenom like('%".$this->input->get('client')."%') OR (client.col_telephone like('%".$this->input->get('client')."%'))" : " AND client.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('dateDebut') != '' && $this->input->get('dateFin') != '') ? " "
                . "AND ((tb_commandes.col_date between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')) OR "
                . "(tb_factures.col_date_creation between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')) OR "
                . "(tb_factures.col_date_reglement between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')) OR "
                . ")" : " AND tb_commandes.col_date > '2018-04-20 14:57:20'";
        $data["titre"] = "LISTE DES FACTURES";
        $tab = array(
            'tb_users caissier' => 'caissier.col_id = tb_factures.col_caissier',
            'tb_factures_commandes' => 'tb_factures_commandes.col_facture = tb_factures.col_id',
            'tb_commandes' => 'tb_factures_commandes.col_commande = tb_commandes.col_id',
            'tb_users' => 'tb_users.col_id = tb_commandes.col_servant',
            'tb_users client' => 'client.col_id = tb_commandes.col_client',
            'tb_espace' => 'tb_espace.col_id = tb_commandes.col_espace',
            'tb_activites' => 'tb_activites.col_id = tb_commandes.col_activite');
        $liste = "tb_factures.col_id,tb_factures.col_date_creation,tb_factures.col_date_reglement,";
        $liste .= "caissier.col_nom_prenom,caissier.col_telephone,tb_factures.col_statut";
        $data["factures"] = $this->Gestion_mobile_model->get_data_by($liste,'tb_factures',$where,$tab,'tb_factures.col_id');
        
        $retour = array();
        if(count($data["factures"])>0){
            foreach ($data["factures"] as $uneFacture){
                $temp["col_id"] = $uneFacture->col_id;
                $temp["date_c"] = $uneFacture->col_date_creation;
                $temp["date_r"] = $uneFacture->col_date_reglement;
                $temp["caissier"] = $uneFacture->col_nom_prenom." (".$uneFacture->col_telephone.")";
                switch ($uneFacture->col_statut){ # $temp["statut"] = $uneFacture->statut;
                    case'O':
                        $temp["statut"] = "<button class='btn bg-warning btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["action"] = "<button onclick='modFacture($uneFacture->col_id,1)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";
                        break;
                    case'D':
                        $temp["statut"] = "<button class='btn bg-danger btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["action"] = "<button onclick='modFacture($uneFacture->col_id,1)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";
                        break;
                    case'P':
                        $temp["statut"] = "<button class='btn bg-success btn-sm'> <i class='fa fa-list-alt'></i></button>";
                        $temp["action"] = "<button onclick='modFacture($uneFacture->col_id,0)' class='btn btn-primary btn-sm'> <i class='fa fa-eye'></i></button>";
                        break;
                }
                array_push($retour,$temp);
            }
        }else{
            $temp["col_id"] = -1;
            array_push($retour,$temp);
        }
        $data["factures"] = json_encode($retour);
        if(!CheckPermission("VEGAS-18")){
            $data["factures"] = json_encode(array("col_id"=>-1));
        }
        if(($this->input->get('page_active') != '') && ($this->input->get('page_active') == 1)){
            $this->view_output("les_factures_vue",$data);
        }else{
            addLog($data["factures"],'FKC');
            echo $data["factures"];
        }      
    }
    
    public function facturesDetails(){
        $id = $this->input->post('id');
        $modifier = $this->input->post('modification');
        $dette = TRUE;
        $tab = array('tb_users caissier' => 'caissier.col_id = tb_factures.col_caissier');
        $liste = "tb_factures.col_id,tb_factures.col_date_creation,tb_factures.col_date_reglement,";
        $liste .= "caissier.col_nom_prenom,caissier.col_telephone,tb_factures.col_statut,tb_factures.col_generer_sms,tb_factures.col_generer_mail,tb_factures.col_generer_print";
        $where = 'tb_factures.col_id = '.$id;
        $laFacture = $this->Gestion_mobile_model->get_data_by($liste,'tb_factures',$where,$tab)[0];
        $tab = array(
            'tb_factures_commandes' => 'tb_factures_commandes.col_facture = tb_factures.col_id',
            'tb_commandes' => 'tb_factures_commandes.col_commande = tb_commandes.col_id',
            'tb_users' => 'tb_users.col_id = tb_commandes.col_servant');
        $liste = "tb_commandes.col_id as commande,tb_users.col_nom_prenom,tb_users.col_telephone";
        $lesArticles = $this->Gestion_mobile_model->get_data_by($liste,'tb_factures',$where,$tab);
        switch ($laFacture->col_statut){ # $temp["statut"] = $uneCommande->statut;
            case'O':
                $laFacture->col_statut = "<input type='text' style='text-align:center; background-color: #F7A922'  name='statut' value='OUVERTE' class='form-control' placeholder='Name' readonly>";
                break;
            case'D':
                $laFacture->col_statut = "<input type='text' style='text-align:center; background-color: #c7254e'  name='statut' value='DETTE' class='form-control' placeholder='Name' readonly>";
                $dette = FALSE;
                break;
            case'P':
                $laFacture->col_statut = "<input type='text' style='text-align:center; background-color: #00ca6d'  name='statut' value='PAYEE' class='form-control' placeholder='Name' readonly>";
                break;
        }
        $sms = ($laFacture->col_generer_sms) ? "checked" : "";
        $mail = ($laFacture->col_generer_mail) ? "checked" : "";
        $print = ($laFacture->col_generer_print) ? "checked" : "";
        $formulaire = ""
                . "<form role='form bor-rad' id ='formModFacture' enctype='multipart/form-data' action='#' method='post'>"
                    . "<div class='box-body'>"
                        . "<div class='row'>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>code</label>"
                                    . "<input type='text' name='code' value='FAC-$laFacture->col_id' class='form-control' placeholder='Name' readonly>"
                                    . "<input type='hidden' name='id' id='cleBD' value='$laFacture->col_id' class='form-control'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>date Creation</label>"
                                    . "<input type='text' name='dateC' value='$laFacture->col_date_creation' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>date Reglement</label>"
                                    . "<input type='text' name='dateR' value='$laFacture->col_date_reglement' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-8'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>caissier</label>"
                                    . "<input type='text' name='client' value='$laFacture->col_nom_prenom ($laFacture->col_telephone)' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>statut</label>"
                                    . "$laFacture->col_statut"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>SMS</label>"
                                    . "<input type='checkbox' name='sms' id='sms' $sms/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>MAIL</label>"
                                    . "<input type='checkbox' name='mail' id='mail' $mail/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>PRINT</label>"
                                    . "<input type='checkbox' name='print' id='print' $print/>"
                                . "</div>"
                            . "</div>";
        foreach ($lesArticles as $unArticle){
            $formulaire .= ""             
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Commande</label>"
                                    . "<input type='text' name='commande' value='COM-$unArticle->commande' class='form-control' placeholder='Name' readonly>"
                                    . "<input type='hidden' name='idCommande_$unArticle->commande' value='$unArticle->commande' class='form-control'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-8'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>serveur</label>"
                                    . "<input type='text' name='serveur' value='$unArticle->col_nom_prenom ($unArticle->col_telephone)' class='form-control' placeholder='Name' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>rejeter</label>"
                                    . "<input type='checkbox' name='supp_$unArticle->commande'/>"
                                . "</div>"
                            . "</div>";
        }
        if($modifier){
            if($dette){
                $formulaire .= ""
                            . "<button type='button' onclick='modifierFacture(0,1)' class='col-xs-2 bg-primary' aria-label='Close'><span aria-hidden='true'>MOD</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,2)' class='col-xs-1 bg-danger' aria-label='Close'><span aria-hidden='true'>DEL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,3)' class='col-xs-1 bg-primary' aria-label='Close'><span aria-hidden='true'>PRI</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,4)' class='col-xs-1 bg-success' aria-label='Close'><span aria-hidden='true'>VAL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,5)' class='col-xs-1 bg-success' aria-label='Close'><span aria-hidden='true'>DET</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' class='col-xs-1 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>CAN</span></button>"; 
            }else{
                $formulaire .= ""
                            . "<button type='button' onclick='modifierFacture(0,1)' class='col-xs-2 bg-primary' aria-label='Close'><span aria-hidden='true'>MOD</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,2)' class='col-xs-1 bg-danger' aria-label='Close'><span aria-hidden='true'>DEL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,3)' class='col-xs-1 bg-primary' aria-label='Close'><span aria-hidden='true'>PRI</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierFacture(1,4)' class='col-xs-1 bg-success' aria-label='Close'><span aria-hidden='true'>VAL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' class='col-xs-2 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>CAN</span></button>"; 
            }
        }else{
            $formulaire .= ""
                        . "<div class='col-xs-1'></div>"
                        . "<button type='button' onclick='modifierFacture(1,3)' class='col-xs-4 bg-primary' aria-label='Close'><span aria-hidden='true'>IMPRIMMER</span></button>"
                        . "<div class='col-xs-2'></div>"
                        . "<button type='button' class='col-xs-4 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
        }
        $formulaire .= ""
                    . "</div>"
                    . "</div>"
                . "</form>";
        echo json_encode(array('formulaire'=>$formulaire));
    }
    
    public function stocks(){
        is_login();
        $where = "";
        if(($this->input->get('activite') != '') && ($this->input->get('activite') != 'all')){
            $where .= "tb_activites.col_id = ".$this->input->get('activite');  
        }else{
            $where .= "tb_activites.col_id > 0";  
        }
        $where .= ($this->input->get('fournisseur') != '') ? " AND (tb_users.col_nom_prenom like('%".$this->input->get('fournisseur')."%') OR (tb_users.col_telephone like('%".$this->input->get('fournisseur')."%'))" : " AND tb_users.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('libele') != '') ? " AND (tb_stocks.col_libele like('%".$this->input->get('libele')."%')" : " AND tb_stocks.col_libele <> 'FKC0'";
        $where .= ($this->input->get('dateDebut') != '' && $this->input->get('dateFin') != '') ? " AND tb_stocks.col_date_deniere_mod between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')" : " AND tb_stocks.col_date_deniere_mod > '2018-04-20 14:57:20'";
        $data["titre"] = "LISTE DES STOCKS";
        $tab = array(
            'tb_users' => 'tb_users.col_id = tb_stocks.col_fornisseur',
            'tb_stock_activite' => 'tb_stock_activite.col_stock = tb_stocks.col_id',
            'tb_activites' => 'tb_activites.col_id = tb_stock_activite.col_activite');
        $liste = "tb_stocks.col_id,tb_stocks.col_qte,tb_stocks.col_puv,tb_stocks.col_libele,tb_stocks.col_est_virtuel,tb_stocks.col_date_deniere_mod";
        $data["stocks"] = $this->Gestion_mobile_model->get_data_by($liste,'tb_stocks',$where,$tab);
        
        $retour = array();
        if(count($data["stocks"])>0){
            foreach ($data["stocks"] as $unStock){
                $temp["col_id"] = $unStock->col_id;
                $temp["col_qte"] = $unStock->col_qte;
                $temp["col_puv"] = $unStock->col_puv;
                $temp["col_libele"] = $unStock->col_libele;
                $temp["col_est_virtuel"] = ($unStock->col_est_virtuel) ? "OUI" : "NON";
                $temp["col_date_deniere_mod"] = $unStock->col_date_deniere_mod;
                $temp["action"] = "<button onclick='modOrAddstock($unStock->col_id,1)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";
                array_push($retour,$temp);
            }
        }else{
            $temp["col_id"] = -1;
            array_push($retour,$temp);
        }
        $data["stocks"] = json_encode($retour);
        if(!CheckPermission("VEGAS-17")){
            $data["stocks"] = json_encode(array("col_id"=>-1));
        }
        if(($this->input->get('page_active') != '') && ($this->input->get('page_active') == 1)){
            $this->view_output("les_stocks_vue",$data);
        }else{
            echo $data["stocks"];
        }      
    }
    
    public function stocksDetails(){
        $id = $this->input->post('id');
        $modifier = $this->input->post('modification');
        if($modifier){
        $tab = array(
            'tb_users' => 'tb_users.col_id = tb_stocks.col_fornisseur',
            'tb_stock_activite' => 'tb_stock_activite.col_stock = tb_stocks.col_id',
            'tb_activites' => 'tb_activites.col_id = tb_stock_activite.col_activite');
            $liste = "tb_stocks.col_id,tb_stocks.col_qte,tb_stocks.col_pua,tb_stocks.col_puv,tb_stocks.col_libele,tb_stocks.col_description,"
                    . "tb_activites.col_id as activite,tb_users.col_id as fournisseur,tb_stocks.col_est_virtuel,"
                    . "tb_stocks.col_date_deniere_mod,tb_users.col_nom_prenom,tb_users.col_telephone";
            $where = 'tb_stocks.col_id = '.$id;
            $leStock = $this->Gestion_mobile_model->get_data_by($liste,'tb_stocks',$where,$tab)[0];
        }
        $virtuel = ($modifier && $leStock->col_est_virtuel) ? "checked" : "";
        $formulaire = ""
                . "<form role='form bor-rad' id='formModStock' enctype='multipart/form-data' action='#' method='post'>"
                    . "<div class='box-body'>"
                        . "<div class='row'>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>libele</label>"
                                    . "<input type='text' name='libele' value='".($modifier ? $leStock->col_libele : "")."' class='form-control' placeholder='libele'>"
                                    . ($modifier ? "<input type='hidden' name='id' id='cleBD' value='$leStock->col_id' class='form-control'>" : "")
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label class=4control-label'>ACTIVITE:</label>"
                                        . "<select name='activite' class='form-control'>";
                                            $lesActivites = get_data_by('*','tb_activites','1=1'); #selected
                                            foreach ($lesActivites as $activite){
                                                if($modifier && $activite->col_id == $leStock->activite){
                                                    $formulaire .= "<option value='$activite->col_id' selected>$activite->col_libele</option>";
                                                }else{
                                                    $formulaire .= "<option value='$activite->col_id'>$activite->col_libele</option>";
                                                }
                                            }
                        $formulaire .= "</select>"
                                . "</div>"
                                . "</div>"
                            . "<div class='col-xs-2'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Qte</label>"
                                    . "<input type='number' name='qte' value='".($modifier ? $leStock->col_qte : "")."' class='form-control' placeholder='qte'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Virtuel</label>"
                                    . "<input type='checkbox' name='virtuel' $virtuel/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>P.U.A</label>"
                                    . "<input type='number' name='pua' value='".($modifier ? $leStock->col_pua : "")."' class='form-control' placeholder='pua'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>P.U.V</label>"
                                    . "<input type='number' name='puv' value='".($modifier ? $leStock->col_puv : "")."' class='form-control' placeholder='puv'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Date modif</label>"
                                    . "<input type='text' name='dateM' value='".($modifier ? $leStock->col_date_deniere_mod : date('Y-m-d H:m:s'))."' class='form-control' placeholder='date' readonly>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label class=4control-label'>FOURNISSEUR:</label>"
                                        . "<select name='fournisseur' class='form-control'>"
                                            . "<option value='-1'>NOUVEAU / INCONNU</option>";
                                            $fournisseurs = get_data_by('*','tb_users','1=1'); #selected
                                            foreach ($fournisseurs as $unFournisseur){
                                                if($modifier && $unFournisseur->col_id == $leStock->fournisseur){
                                                    $formulaire .= "<option value='$unFournisseur->col_id' selected>$unFournisseur->col_nom_prenom</option>";
                                                }else{
                                                    $formulaire .= "<option value='$unFournisseur->col_id'>$unFournisseur->col_nom_prenom</option>";
                                                }
                                            }
                        $formulaire .= "</select>"
                                . "</div>"
                                . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Nom Fournisseur</label>"
                                    . "<input type='text' name='nomF' value='".($modifier ? $leStock->col_nom_prenom : "")."' class='form-control' placeholder='Name'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-4'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Tel Fournisseur</label>"
                                    . "<input type='text' name='telF' value='".($modifier ? $leStock->col_telephone : "")."' class='form-control' placeholder='tel'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-12'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>Description</label>"
                                    . "<textarea class='form-control' name='description' value='".($modifier ? $leStock->col_description : "")."'>".($modifier ? $leStock->col_description : "")."</textarea>"
                                . "</div>"
                            . "</div>";
            if($modifier){
                $formulaire .= ""
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterStock(0)' class='col-xs-3 bg-primary' aria-label='Close'><span aria-hidden='true'>MODIFIER</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterStock(1)' class='col-xs-2 bg-danger' aria-label='Close'><span aria-hidden='true'>DEL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' class='col-xs-3 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
            }else{
                $formulaire .= ""
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterStock(0)' class='col-xs-4 bg-primary' aria-label='Close'><span aria-hidden='true'>AJOUTER</span></button>"
                            . "<div class='col-xs-2'></div>"
                            . "<button type='button' class='col-xs-4 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
            }
            $formulaire .= ""
                        . "</div>"
                        . "</div>"
                    . "</form>";
        echo json_encode(array('formulaire'=>$formulaire));
    }
    
    public function stocksAjax(){
        $cle = ($this->input->post('keyword') != '') ? $this->input->post('keyword') : "";
        $activite = ($this->input->post('activite') != '') ? "AND tb_stock_activite.col_activite = ".$this->input->post('activite') : "";
        $tab = array('tb_stock_activite'=>'tb_stock_activite.col_stock = tb_stocks.col_id');
        $lesArticles = $this->Gestion_mobile_model->get_data_by('tb_stocks.*','tb_stocks',"tb_stocks.col_libele like('%".$cle."%') $activite",$tab);
        $retour = array();
        if(!empty($lesArticles)) {
            foreach($lesArticles as $unArticle) {
                array_push($retour,
                    array(
                        "col_libele"=>$unArticle->col_libele,
                        "col_id"=>$unArticle->col_id,
                        "col_qte"=>$unArticle->col_qte,
                        "col_puv"=>$unArticle->col_puv
                    )
                );
            }
        } 
        echo json_encode($retour);
    }
    
    public function SaveCommande(){
        if(!CheckPermission("VEGAS-20")){
            echo json_encode(array("message"=>"ERREUR: PAS DE DROIT","statut"=>0));
        }else{
            $data = ($this->input->post('liste') != '') ? $this->input->post('liste') : json_encode(array("0--0--0"));
            $data_activite = ($this->input->post('activite') != '') ? $this->input->post('activite') : "0";
            $data_espace = ($this->input->post('espace') != '') ? $this->input->post('espace') : "0";
            $data_client = ($this->input->post('client') != '') ? $this->input->post('client') : "-1";
            $data_newClientName = ($this->input->post('newClientName') != '') ? $this->input->post('newClientName') : "INCONNU";
            $data_newClientTel = ($this->input->post('newClientTel') != '') ? $this->input->post('newClientTel') : "237000000000";
            $data_description = ($this->input->post('description') != '') ? $this->input->post('description') : "";
            $dataClientToSave = array(
                'col_nom_prenom'=>$data_newClientName,
                'col_telephone'=>$data_newClientTel,
                'col_cni_or_passport'=>'123456789',
                'col_password'=>'client',
                'col_email'=>'info@vegasafrica.net',
                'col_role'=>2);
            $data_client = ($data_client == -1) ? $this->Gestion_mobile_model->insertIfNotExit('tb_users',$dataClientToSave) : $data_client;
            try{
                $dataVal = json_decode($data);
                if(strpos($dataVal[0],"0--0--0") !== -1){
                    $data_commande['col_date'] = date('Y-m-d H:m:s');
                    $data_commande['col_statut'] = 'O';
                    $data_commande['col_servant'] = $this->session->get_userdata()['user_details'][0]->col_id;
                    $data_commande['col_client'] = $data_client;
                    $data_commande['col_espace'] = $data_espace;
                    $data_commande['col_activite'] = $data_activite;
                    $data_commande['col_description'] = $data_description;
                    $commande = $this->Gestion_mobile_model->insertRow('tb_commandes',$data_commande);
                    if($commande){
                        foreach ($dataVal as $UneData){
                            $tab = split("--", $UneData);
                            $data_stock_commande['col_commande'] = $commande;
                            $data_stock_commande['col_stock'] = $tab[0];
                            $data_stock_commande['col_qte'] = $tab[1];
                            $data_stock_commande['col_puv_reel'] = $tab[2];
                            $this->Gestion_mobile_model->insertRow('tb_commandes_stocks',$data_stock_commande);
                            $this->Gestion_mobile_model->UpdateStock($tab[0],(0-$tab[1]));
                        }
                        echo json_encode(array("message"=>"COMMANDE: COM-$commande ENREGISTREE","statut"=>1));
                    }else{
                        echo json_encode(array("message"=>"ERREUR: COM-$commande NON ENREGISTREE","statut"=>0));
                    }
                }else{
                    echo json_encode(array("message"=>"ERREUR SUR LES DONNEES","statut"=>0));
                }
            } catch (Exception $ex) {
                echo json_encode(array("message"=>"ERREUR LORS DE L'ENREGISTREMENT","statut"=>0));
            }
        }
    }
    
    public function CreateFacture(){
        if(!CheckPermission("VEGAS-18")){
            echo json_encode(array("message"=>"PAS DE DROIT","statut"=>0));
        }else{
            $commandes = ($this->input->post('commandes') != '') ? $this->input->post('commandes') : 0;
            $this->db->where("col_commande in ($commandes)");
            $testExistance = $this->db->get('tb_factures_commandes')->result();
            if(empty($testExistance)){
                if($commandes){
                    $data_facture['col_date_creation'] = date('Y-m-d H:m:s');
                    $data_facture['col_date_reglement'] = date('Y-m-d H:m:s');
                    $data_facture['col_caissier'] = $this->session->get_userdata()['user_details'][0]->col_id;
                    $data_facture['col_statut'] = 'O';
                    $data_facture['col_description'] = 'nouvelle facture';
                    $data_facture['col_generer_sms'] = 0;
                    $data_facture['col_generer_mail'] = 0;
                    $data_facture['col_generer_print'] = 1;
                    $facture = $this->Gestion_mobile_model->insertRow('tb_factures',$data_facture);
                    if($facture){
                        $dataVal = split(",",$commandes);
                        foreach ($dataVal as $UneData){
                            $data_facture_commande['col_commande'] = $UneData;
                            $data_facture_commande['col_facture'] = $facture;
                            $data_facture_commande['col_date'] = date('Y-m-d H:m:s');
                            $this->Gestion_mobile_model->insertRow('tb_factures_commandes',$data_facture_commande);
                            $this->Gestion_mobile_model->updateRow('tb_commandes','col_id',$UneData,array('col_statut'=>'F'));
                        }
                        echo json_encode(array("message"=>"FACTURE: FAC-$facture ENREGISTREE","statut"=>1));
                    }else{
                        echo json_encode(array("message"=>"ERREUR SUR LES DONNEES FACTURE","statut"=>0));
                    }
                }else{
                   echo json_encode(array("message"=>"ERREUR SUR LES DONNEES","statut"=>0));
                }
            }else{
                echo json_encode(array("message"=>"COMMANDE DEJA EN FACTURATION","statut"=>0));
            }
        }
    }
    
    public function modifierCommande(){
        $commande = $this->input->post('id'); # ToutSupprimer
        $tab = array('tb_commandes_stocks'=>'tb_commandes_stocks.col_stock = tb_stocks.col_id');
        $liste_articles =  $this->Gestion_mobile_model->get_data_by('tb_stocks.*,tb_commandes_stocks.col_qte as qte','tb_stocks','tb_commandes_stocks.col_commande = '.$commande,$tab);
        $compteur = 0;
        $this->Gestion_mobile_model->updateRowWhere('tb_commandes',array('col_client'=>$this->input->post('client')),array('tb_commandes.col_id'=>$commande));
        foreach ($liste_articles as $Unarticle){
            if($this->input->post('ToutSupprimer') != ''){
                $this->Gestion_mobile_model->UpdateStock($Unarticle->col_id,$Unarticle->qte);
                $this->Gestion_mobile_model->delete('tb_commandes_stocks','tb_commandes_stocks.col_stock = '.$Unarticle->col_id);
                $compteur++;
            }else{
                if($this->input->post('supp_'.$Unarticle->col_id) != ''){
                    $this->Gestion_mobile_model->UpdateStock($Unarticle->col_id,$Unarticle->qte);
                    $this->Gestion_mobile_model->delete('tb_commandes_stocks','tb_commandes_stocks.col_stock = '.$Unarticle->col_id);
                    $compteur++;
                }else{
                    if($this->input->post('puvr_'.$Unarticle->col_id) >= $Unarticle->col_puv){
                        $data_stock_commande_where['col_commande'] = $commande;
                        $data_stock_commande_where['col_stock'] = $Unarticle->col_id;
                        $data_stock_commande['col_qte'] = $this->input->post('qtev_'.$Unarticle->col_id);
                        $data_stock_commande['col_puv_reel'] = $this->input->post('puvr_'.$Unarticle->col_id);
                        $this->Gestion_mobile_model->UpdateStock($Unarticle->col_id,$Unarticle->qte - $this->input->post('qtev_'.$Unarticle->col_id));
                        $this->Gestion_mobile_model->updateRowWhere('tb_commandes_stocks',$data_stock_commande,$data_stock_commande_where);
                    }
                }
            }
        }
        if(($compteur == count($liste_articles))||($this->input->post('ToutSupprimer') != '')){
            $this->Gestion_mobile_model->delete('tb_commandes','col_id = '.$commande);
        }
        echo json_encode(array('message'=>'ok'));
    }
    
    public function modifierFacture(){
        $facture = $this->input->post('id');
        $tab = array('tb_factures_commandes'=>'tb_factures_commandes.col_commande = tb_commandes.col_id');
        $liste_articles =  $this->Gestion_mobile_model->get_data_by('tb_commandes.*','tb_commandes','tb_factures_commandes.col_facture = '.$facture,$tab);
        if($this->input->post('ToutSupprimer') != ''){
            switch ($this->input->post('modeVal')){
                case 2: //supprimer
                    foreach ($liste_articles as $Unarticle){
                        $this->Gestion_mobile_model->delete('tb_factures_commandes',"tb_factures_commandes.col_commande = $Unarticle->col_id AND tb_factures_commandes.col_facture = $facture");
                        $this->Gestion_mobile_model->updateRowWhere('tb_commandes',array('col_statut'=>'R'),array('tb_commandes.col_id' => $Unarticle->col_id));
                    }
                    $this->Gestion_mobile_model->delete('tb_factures','col_id = '.$facture);
                break;
                case 3: //imprimmer
                    $imprimmer = ($this->input->post('print') != '') ? (($this->input->post('print') != 'false') ? 1:0):0;
                    $sendSms = ($this->input->post('sms') != '') ? (($this->input->post('sms') != 'false') ? 1:0):0;
                    $sendMail = ($this->input->post('mail') != '') ? (($this->input->post('mail') != 'false') ? 1:0):0;
                    sortieFacture($facture,$imprimmer,$sendSms,$sendMail);
                break;
                case 4: // valider
                    foreach ($liste_articles as $Unarticle){
                        $this->Gestion_mobile_model->updateRowWhere('tb_commandes',array('col_statut'=>'P'),array('tb_commandes.col_id' => $Unarticle->col_id));
                    }
                    $data_to_update = array(
                        'col_statut'=> 'P',
                        'col_caissier'=> $this->session->get_userdata()['user_details'][0]->col_id,
                        'col_date_reglement'=> date('Y-m-d H:m:s'));
                    $this->Gestion_mobile_model->updateRowWhere('tb_factures',$data_to_update,array('tb_factures.col_id' => $facture));
                    $imprimmer = ($this->input->post('print') != '') ? (($this->input->post('print') != 'false') ? 1:0):0;
                    $sendSms = ($this->input->post('sms') != '') ? (($this->input->post('sms') != 'false') ? 1:0):0;
                    $sendMail = ($this->input->post('mail') != '') ? (($this->input->post('mail') != 'false') ? 1:0):0;
                    sortieFacture($facture,$imprimmer,$sendSms,$sendMail);
                break;
                case 5: // dette
                    $data_to_update = array(
                        'col_statut'=> 'D',
                        'col_caissier'=> $this->session->get_userdata()['user_details'][0]->col_id,
                        'col_date_reglement'=> date('Y-m-d H:m:s'));
                    $this->Gestion_mobile_model->updateRowWhere('tb_factures',$data_to_update,array('tb_factures.col_id' => $facture));
                    $imprimmer = ($this->input->post('print') != '') ? (($this->input->post('print') != 'false') ? 1:0):0;
                    $sendSms = ($this->input->post('sms') != '') ? (($this->input->post('sms') != 'false') ? 1:0):0;
                    $sendMail = ($this->input->post('mail') != '') ? (($this->input->post('mail') != 'false') ? 1:0):0;
                    sortieFacture($facture,$imprimmer,$sendSms,$sendMail);
                break;
                default :
                break;
            }
        }else{
            $compteur = 0;
            foreach ($liste_articles as $Unarticle){
                if($this->input->post('supp_'.$Unarticle->col_id) != ''){
                    $this->Gestion_mobile_model->delete('tb_factures_commandes',"tb_factures_commandes.col_commande = $Unarticle->col_id AND tb_factures_commandes.col_facture = $facture");
                    $this->Gestion_mobile_model->updateRowWhere('tb_commandes',array('col_statut'=>'R'),array('tb_commandes.col_id' => $Unarticle->col_id));
                    $compteur++;
                }
            }
            if($compteur == count($liste_articles)){
                $this->Gestion_mobile_model->delete('tb_factures','col_id = '.$facture);
            }else{
                $imprimmer = ($this->input->post('print') != '') ? (($this->input->post('print') != 'false') ? 1:0):0;
                $sendSms = ($this->input->post('sms') != '') ? (($this->input->post('sms') != 'false') ? 1:0):0;
                $sendMail = ($this->input->post('mail') != '') ? (($this->input->post('mail') != 'false') ? 1:0):0;
                sortieFacture($facture,$imprimmer,$sendSms,$sendMail);
            }
        }
        echo json_encode(array('message'=>'ok'));
    }
    
    public function modifierOrajouterStock(){
        if(!CheckPermission("VEGAS-17")){
            echo json_encode(array("message"=>"PAS DE DROIT","statut"=>0));
        }else{
            if($this->input->post('ToutSupprimer') != ''){
                $stock = $this->input->post('id');
                $testExist =  $this->Gestion_mobile_model->get_data_by('*','tb_commandes_stocks','tb_commandes_stocks.col_stock = '.$stock,array());
                if(empty($testExist)){
                    $this->Gestion_mobile_model->delete('tb_stock_activite','col_stock = '.$stock);
                    $this->Gestion_mobile_model->delete('tb_stocks','col_id = '.$stock);
                }
            }else{
                $activite = $this->input->post('activite');
                $to_save_or_update['col_libele'] = $this->input->post('libele');
                $to_save_or_update['col_qte'] = $this->input->post('qte');
                $to_save_or_update['col_est_virtuel'] = ($this->input->post('virtuel') != '');
                $to_save_or_update['col_pua'] = $this->input->post('pua');
                $to_save_or_update['col_puv'] = $this->input->post('puv');
                $to_save_or_update['col_date_deniere_mod'] = date('Y-m-d H:m:s');
                $to_save_or_update['col_description'] = $this->input->post('description');
                $to_save_or_update['col_historique'] = "[".date('Y-m-d H:m:s')."]: Mise ajour du stock par Mr/Mme ".$this->session->get_userdata()['user_details'][0]->col_nom_prenom." : nouvelle qte = ".$to_save_or_update['col_qte'];
                $data_fournisseur = $this->input->post('fournisseur');
                $dataFournisseurToSave = array(
                    'col_nom_prenom'=>$this->input->post('nomF'),
                    'col_telephone'=>$this->input->post('telF'),
                    'col_cni_or_passport'=>'123456789',
                    'col_password'=>'fournisseur',
                    'col_email'=>'info@vegasafrica.net',
                    'col_role'=>3);
                $to_save_or_update['col_fornisseur'] = ($data_fournisseur == -1) ? $this->Gestion_mobile_model->insertIfNotExit('tb_users',$dataFournisseurToSave) : $data_fournisseur;
                if($this->input->post('id') != ''){
                    $stock = $this->input->post('id');
                    $ancienStock =  $this->Gestion_mobile_model->get_data_by('*','tb_stocks','col_id = '.$stock,array())[0];
                    $to_save_or_update['col_historique'] = $ancienStock->col_historique."<br/>".$to_save_or_update['col_historique'];
                    $this->Gestion_mobile_model->updateRowWhere('tb_stock_activite',array('tb_stock_activite.col_activite'=>$activite,'tb_stock_activite.col_date'=>date('Y-m-d H:m:s')),array('tb_stock_activite.col_stock' => $stock));
                    $this->Gestion_mobile_model->updateRowWhere('tb_stocks',$to_save_or_update,array('col_id' => $stock));
                }else{
                    $stock = $this->Gestion_mobile_model->insertRow('tb_stocks',$to_save_or_update);
                    $this->Gestion_mobile_model->insertRow('tb_stock_activite',array('tb_stock_activite.col_activite'=>$activite,'tb_stock_activite.col_date'=>date('Y-m-d H:m:s'),'tb_stock_activite.col_stock'=>$stock));
                }
            }
        }
            echo json_encode(array('message'=>'ok'));
    }
    
    public function clients(){
        is_login();
        $where = "";
        $where .= ($this->input->get('nom') != '') ? "(tb_users.col_nom_prenom like('%".$this->input->get('nom')."%'))" : "tb_users.col_nom_prenom <> 'FKC0'";
        $where .= ($this->input->get('carte') != '') ? " AND (tb_users.col_carte_canal like('%".$this->input->get('carte')."%'))" : " AND tb_users.col_carte_canal <> 'FKC0'";
        $where .= ($this->input->get('telephone') != '') ? " AND (tb_users.col_telephone like('%".$this->input->get('telephone')."%'))" : " AND tb_users.col_telephone <> '237'";
        if(($this->input->get('role') != '') && ($this->input->get('role') != 'all')){
            $where .= " AND (tb_roles.col_nom like('%".$this->input->get('role')."%'))";
        }else{
            $where .= " AND tb_roles.col_nom in ('FOURNISSEUR','CLIENT')";
        }
        $where .= ($this->input->get('dateDebut') != '' && $this->input->get('dateFin') != '') ? " AND tb_users.col_date_creation between('".$this->input->get('dateDebut')."' AND '".$this->input->get('dateFin')."')" : " AND tb_users.col_date_creation > '2018-04-20 14:57:20'";
        $data["titre"] = "LISTE DES CLIENTS/FOURNISSEURS";
        $tab = array('tb_roles' => 'tb_roles.col_id = tb_users.col_role');
        $liste = "tb_users.*,tb_roles.col_id as role_id,tb_roles.col_nom as role_nom";
        $data["clients"] = $this->Gestion_mobile_model->get_data_by($liste,'tb_users',$where,$tab);
        
        $retour = array();
        if(count($data["clients"])>0){
            foreach ($data["clients"] as $unUsers){
                $temp["col_id"] = $unUsers->col_id;
                $temp["col_nom_prenom"] = $unUsers->col_nom_prenom;
                $temp["col_cni_or_passport"] = $unUsers->col_cni_or_passport;
                $temp["col_carte_canal"] = $unUsers->col_carte_canal;
                $temp["col_telephone"] = $unUsers->col_telephone;
                $temp["col_ville"] = $unUsers->col_ville;
                $temp["col_quartier"] = $unUsers->col_quartier;
                $temp["col_date_creation"] = $unUsers->col_date_creation;
                $temp["role_nom"] = $unUsers->role_nom;
                $temp["action"] = "<button onclick='modClients($unUsers->col_id)' class='btn btn-primary btn-sm'> <i class='fa fa-edit'></i></button>";;
                array_push($retour,$temp);
            }
        }else{
            $temp["col_id"] = -1;
            array_push($retour,$temp);
        }
        $data["clients"] = json_encode($retour);
        if(!CheckPermission("VEGAS-20") &&  !CheckPermission("VEGAS-17")){
           $data["clients"] = json_encode(array("col_id"=>-1)); 
        }
        if(($this->input->get('page_active') != '') && ($this->input->get('page_active') == 1)){
            $this->view_output("les_clients_vue",$data);
        }else{
            echo $data["clients"];
        }      
    }
    
    public function clientsDetails(){
        $id = $this->input->post('id');
        if($id){
            $tab = array('tb_roles' => 'tb_roles.col_id = tb_users.col_role');
            $liste = "tb_users.*,tb_roles.col_id as role_id,tb_roles.col_nom as role_nom";
            $user = $this->Gestion_mobile_model->get_data_by($liste,'tb_users','tb_users.col_id = '.$id,$tab)[0];
            $data = json_decode($user->col_creation,TRUE);
            $is_om = ($data['ORANGE']['statut']) ? "checked" : "";
            $is_mtn = ($data['MTN']['statut']) ? "checked" : "";
            $is_eu = ($data['EU']['statut']) ? "checked" : "";
            $is_canal = ($data['CANAL']['statut']) ? "checked" : "";
        }
        $formulaire = ""
                . "<form role='form bor-rad' id='formModClient' enctype='multipart/form-data' action='#' method='post'>"
                    . "<div class='box-body'>"
                        . "<div class='row'>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>NOM:</label>"
                                    . "<input type='text' name='nom' value='".($id ? $user->col_nom_prenom : "")."' class='form-control' placeholder='Nom et prenom'>"
                                    . ($id ? "<input type='hidden' name='id' id='cleBD' value='$user->col_id' class='form-control'>" : "")
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>TELEPHONE:</label>"
                                    . "<input type='text' name='telephone' value='".($id ? $user->col_telephone : "")."' class='form-control' placeholder='Telephone'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>EMAIL:</label>"
                                    . "<input type='email' name='email' value='".($id ? $user->col_email : "")."' class='form-control' placeholder='email'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label class=4control-label'>ROLE:</label>"
                                        . "<select name='role' class='form-control'>"
                                            . "<option value='2' ".(($user->role_id == 2) ? 'selected' : "").">CLIENT</option>"
                                            . "<option value='3' ".(($user->role_id == 3) ? 'selected' : "").">FOURNISSEUR</option>"
                                        . "</select>"
                                . "</div>"
                                . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>VILLE:</label>"
                                    . "<input type='text' name='ville' value='".($id ? $user->col_ville : "")."' class='form-control' placeholder='VILLE'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>QUARTIER:</label>"
                                    . "<input type='text' name='quartier' value='".($id ? $user->col_quartier : "")."' class='form-control' placeholder='Quartier'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>CNI:</label>"
                                    . "<input type='text' name='cni' value='".($id ? $user->col_cni_or_passport : "")."' class='form-control' placeholder='CNI / PASSEPORT'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-3'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>CARTE CANAL:</label>"
                                    . "<input type='text' name='carte' value='".($id ? $user->col_carte_canal : "")."' class='form-control' placeholder='CARTE CANAL PLUS'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-5'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>ORANGE MONEY:</label>"
                                    . "<input type='text' name='om' value='".($id ? $data['ORANGE']['data'] : "")."' class='form-control' placeholder='COMPTE OM'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>OM</label>"
                                    . "<input type='checkbox' name='om_check' $is_om disabled/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-5'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>MTN MOBILE MONEY:</label>"
                                    . "<input type='text' name='mtn' value='".($id ? $data['MTN']['data'] : "")."' class='form-control' placeholder='COMPTE MOMO'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>MOMO</label>"
                                    . "<input type='checkbox' name='mtn_check' $is_mtn disabled/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-5'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>EU MOBILE MONEY:</label>"
                                    . "<input type='text' name='eumm' value='".($id ? $data['EU']['data'] : "")."' class='form-control' placeholder='COMPTE EUMM'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>EUMM</label>"
                                    . "<input type='checkbox' name='eumm_check' $is_eu disabled/>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-5'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>CANAL PLUS:</label>"
                                    . "<input type='text' name='canal' value='".($id ? $data['CANAL']['data'] : "")."' class='form-control' placeholder='FORMULE INITIALE CANAL PLUS'>"
                                . "</div>"
                            . "</div>"
                            . "<div class='col-xs-1'>"
                                . "<div class='form-group'>"
                                    . "<label for=''>CANAL</label>"
                                    . "<input type='checkbox' name='canal_check' $is_canal disabled/>"
                                . "</div>"
                            . "</div>";
            if($id){
                $formulaire .= ""
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterClient(0)' class='col-xs-3 bg-primary' aria-label='Close'><span aria-hidden='true'>MODIFIER</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterClient(1)' class='col-xs-2 bg-danger' aria-label='Close'><span aria-hidden='true'>DEL</span></button>"
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' class='col-xs-3 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
            }else{
                $formulaire .= ""
                            . "<div class='col-xs-1'></div>"
                            . "<button type='button' onclick='modifierOrajouterClient(0)' class='col-xs-4 bg-primary' aria-label='Close'><span aria-hidden='true'>AJOUTER</span></button>"
                            . "<div class='col-xs-2'></div>"
                            . "<button type='button' class='col-xs-4 bg-warning' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>ANNULER</span></button>"; 
            }
            $formulaire .= ""
                        . "</div>"
                        . "</div>"
                    . "</form>";
        echo json_encode(array('formulaire'=>$formulaire));
    }
    
    public function modifierOrajouterClient(){
        if(!CheckPermission("VEGAS-20") &&  !CheckPermission("VEGAS-17")){
            echo json_encode(array("message"=>"PAS DE DROIT","statut"=>0));
        }else{
            if($this->input->post('ToutSupprimer') != ''){
                $user = $this->input->post('id');
                $testExist1 =  $this->Gestion_mobile_model->get_data_by('*','tb_stocks','col_fornisseur = '.$user,array());
                $testExist2 =  $this->Gestion_mobile_model->get_data_by('*','tb_commandes','col_client = '.$user,array());
                if(empty($testExist1) && empty($testExist2)){
                    $this->Gestion_mobile_model->delete('tb_users','col_id = '.$user);
                }
            }else{
                $to_save_or_update['col_nom_prenom'] = $this->input->post('nom');
                $to_save_or_update['col_telephone'] = $this->input->post('telephone');
                $to_save_or_update['col_email'] = ($this->input->post('email') != '');
                $to_save_or_update['col_role'] = $this->input->post('role');
                $to_save_or_update['col_ville'] = $this->input->post('ville');
                $to_save_or_update['col_quartier'] = $this->input->post('quartier');
                $to_save_or_update['col_cni_or_passport'] = $this->input->post('cni');
                $to_save_or_update['col_carte_canal'] = $this->input->post('carte');
                $creation['ORANGE'] = array(
                    'data' => $this->input->post('om'),
                    'statut' => (($this->input->post('om_check') != '') ? 1:0)
                );
                $creation['MTN'] = array(
                    'data' => $this->input->post('mtn'),
                    'statut' => (($this->input->post('mtn_check') != '') ? 1:0)
                );
                $creation['EU'] = array(
                    'data' => $this->input->post('eumm'),
                    'statut' => (($this->input->post('eumm_check') != '') ? 1:0)
                );
                $creation['CANAL'] = array(
                    'data' => $this->input->post('canal'),
                    'statut' => (($this->input->post('canal_check') != '') ? 1:0)
                );
                $to_save_or_update['col_creation'] = json_encode($creation);
                if($this->input->post('id') != ''){
                    $user = $this->input->post('id');
                    $this->Gestion_mobile_model->updateRowWhere('tb_users',$to_save_or_update,array('col_id' => $user));
                }else{
                    $this->Gestion_mobile_model->insertRow('tb_users',$to_save_or_update);
                }
            }
        }
        echo json_encode(array('message'=>'ok'));
    }
    
    public function getStatutEspace(){
        $id = $this->input->post('id');
        $test = get_data_by("*",'tb_commandes',"col_espace = $id AND col_statut in ('O','R','F')",array());
        $retour['statut'] = (empty($test)) ? "<button class='btn-success btn-sm'> <i class='fa fa-table'></i></button>" : "<button class='btn-danger btn-sm'> <i class='fa fa-table'></i></button>";
        echo json_encode($retour);
    }
    
    public function getEspaceFromActivite(){
        $id = $this->input->post('id');
        $tab = array('tb_activite_espace'=>'tb_activite_espace.col_espace = tb_espace.col_id');
        $espaces = get_data_by("*",'tb_espace',"tb_activite_espace.col_activite = $id",$tab);
        $retour = array();
        foreach ($espaces as $espace){
            array_push($retour,array('valeur'=>"<option value='$espace->col_id'>$espace->col_libele</option>"));
        }
        echo json_encode($retour);
    }
}