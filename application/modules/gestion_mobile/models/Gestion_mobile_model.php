<?php

class Gestion_mobile_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * This function is used to select data form table  
     */
    function get_data_by($select,$tableName = '',$condition = '',$jointure = '', $group_by = '') {
        $this->db->where($condition);
        $this->db->select($select);
        $this->db->from($tableName);
        if(!empty($jointure)&&  is_array($jointure)){
            foreach ($jointure as $table => $jointure){
                $this->db->join($table,$jointure);
            }
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to Update record in table  
     */
    public function updateRow($table, $col, $colVal, $data) {
        $this->db->where($col, $colVal);
        $this->db->update($table, $data);
        return true;
    }
    
    public function updateRowWhere($table,$data,$tabWhere) {
        foreach ($tabWhere as $col => $colVal){
            $this->db->where($col, $colVal);
        }
        $this->db->update($table, $data);
        return true;
    }
    
        /**
     * This function is used to Insert record in table  
     */
    public function insertRow($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    
    public function UpdateStock($id,$qte){
        $this->db->where('col_id',$id);
        $stock = $this->db->get('tb_stocks')->result()[0];
        if($stock->col_est_virtuel) return TRUE;
        $this->updateRow('tb_stocks','col_id',$id,array('col_qte'=>($qte+$stock->col_qte)));
    }
    
    public function insertIfNotExit($table, $data){
//        foreach ($data as $key => $value){
//            $this->db->where($key,$value);
//        }
        $this->db->where("col_telephone like('%".$data['col_telephone']."%')");
        $query = $this->db->get($table)->result();
        if(empty($query)){
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }else{
            return $query[0]->col_id;
        }
    }
    
    function delete($table,$where) {
        $this->db->where($where);
        $this->db->delete($table);
    }

}
