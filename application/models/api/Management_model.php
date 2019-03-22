<?php
class Management_model extends CI_Model {       
    function __construct(){            
        parent::__construct();
        $this->load->database();
        $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }
    
    public function get_information_string_where($table,$where_clause) {
        $this->db->where($where_clause);
        return $this->db->get("".$table)->result();
    }
    
    public function get_information_tab_where($table,$where_clause) {
        foreach ($where_clause as $col => $colVal){
            $this->db->where($col,$colVal);
        }
        return $this->db->get("".$table)->result();
    }
    
    public function enregistrer_ou_modifier($data,$table,$champ_cle){
        $flag = false;
        addLog("$table ".json_encode($data),"ENREGISTREMENTS");
        if(isset($data[$champ_cle])){
            $this->db->where($champ_cle,$data[$champ_cle]);
            $temp_cle = $data[$champ_cle];
            unset($data[$champ_cle]);
            $flag = $this->db->update($table,$data);
            $data[$champ_cle] = $temp_cle;
        }else{
            $flag = $this->db->insert($table,$data);
            $data[$champ_cle] = $this->db->insert_id();
        }
        $this->db->where($champ_cle,$data[$champ_cle]);
        $retour = $this->db->get("".$table)->result();
        addLog("$table ".json_encode($retour),"ENREGISTREMENTS");
        return $retour[0];
    }
    
    public function save_data($data,$table){
        return $this->db->insert($table,$data);
    }
    
    public function update_data($data,$table,$tab_cle){
        foreach ($tab_cle as $cle){
            $this->db->where($cle,$data[$cle]);
            unset($data[$cle]);
        }
        return $this->db->update($table,$data);
    }

    public function deleteTable($table,$champ_cle,$valeur_champ_cle){
        $this->db->where($champ_cle, $valeur_champ_cle);  
        $this->db->delete($table); 
    }

    public function getclientAdress(){
        $this->db->select("C.col_tel as client_telephone, 
            C.col_email as client_email, 
            C.col_nom_prenom as client, 
            I.col_adresse as adresse,
            I.col_ville as ville,
            I.col_quartier as quartier,
            I.col_num_carte as carte,
            I.col_telephone as info_telephone");
        $this->db->from("tb_client C, tb_info_client I");
        $this->db->where("(C.col_id = I.col_client)");
        $query = $this->db->get();
        $data = array();
        foreach($query->result() as $result_suite){
            $data[$result_suite->client_telephone."; ".$result_suite->client_email] = $result_suite->client." (".$result_suite->client_telephone."; ".$result_suite->client_email.")";
            $data[$result_suite->info_telephone."; ".$result_suite->client_email] = $result_suite->client." [".$result_suite->carte."]"." (".$result_suite->info_telephone."; ".$result_suite->client_email.")";
        }
        return $data;
    }
}