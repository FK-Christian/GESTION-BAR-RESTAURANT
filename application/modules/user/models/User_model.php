<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * This function is used authenticate user at login
     */
    function auth_user() {
        $email = $this->input->post('col_email');
        $password = $this->input->post('col_password');
        $this->db->where("col_is_delete='0' AND (col_nom_prenom='$email' OR col_email='$email' OR col_telephone='$email')");
        $result = $this->db->get('tb_users')->result();
        if (!empty($result)) {
            if (password_verify($password, $result[0]->col_password)) {
                if ($result[0]->col_status != 'active') {
                    return 'not_varified';
                }
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * This function is used to load view of reset password and varify user too 
     */
    function mail_varify() {
        $ucode = $this->input->get('code');
        $this->db->select('col_email as e_mail');
        $this->db->from('tb_users');
        $this->db->where('col_key', $ucode);
        $query = $this->db->get();
        $result = $query->row();
        if (!empty($result->e_mail)) {
            return $result->e_mail;
        } else {
            return false;
        }
    }

    /**
     * This function is used Reset password  
     */
    function ResetPpassword() {
        $email = $this->input->post('col_email');
        if ($this->input->post('password_confirmation') == $this->input->post('col_password')) {
            $npass = password_hash($this->input->post('col_password'), PASSWORD_DEFAULT);
            $data['col_password'] = $npass;
            $data['col_key'] = '';
            return $this->db->update('tb_users', $data, "col_email = '$email'");
        }
    }

    /**
     * This function is used to select data form table  
     */
    function get_data_by($tableName = '', $value = '', $colum = '', $condition = '') {
        if ((!empty($value)) && (!empty($colum))) {
            $this->db->where($colum, $value);
        }
        $this->db->select('*');
        $this->db->from($tableName);
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function is used to check user is alredy exist or not  
     */
    function check_exists($table = '', $colom = '', $colomValue = '') {
        $this->db->where($colom, $colomValue);
        $res = $this->db->get($table)->row();
        if (!empty($res)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * This function is used to get users detail  
     */
    function get_users($userID = '') {
        $this->db->where('col_is_delete', '0');
        if (isset($userID) && $userID != '') {
            $this->db->where('col_id', $userID);
        }
        $result = $this->db->get('tb_users')->result();
        return $result;
    }

    /**
     * This function is used to get email template  
     */
    function get_template($code) {
        $this->db->where('col_code', $code);
        return $this->db->get('tb_templates')->row();
    }

    /**
     * This function is used to Update record in table  
     */
    public function updateRow($table, $col, $colVal, $data) {
        $this->db->where($col, $colVal);
        $this->db->update($table, $data);
        return true;
    }

}
