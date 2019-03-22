<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * This function is redirect to users profile page
     * @return Void
     */
    public function index() {
        if (is_login()) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    /**
     * This function is used to load login view page
     * @return Void
     */
    public function login() {
        if (isset($_SESSION['user_details'])) {
            redirect(base_url() . 'user/profile', 'refresh');
        }
        $this->load->view('include/script');
        $this->load->view('login');
    }

    /**
     * This function is used to logout user
     * @return Void
     */
    public function logout() {
        is_login();
        $this->session->unset_userdata('user_details');
        redirect(base_url() . 'user/login', 'refresh');
    }

    /**
     * This function is used for user authentication ( Working in login process )
     * @return Void
     */
    public function auth_user($page = '') {
        $return = $this->User_model->auth_user();
        if (empty($return)) {
            $this->session->set_flashdata('messagePr', 'Invalid details');
            redirect(base_url() . 'user/login', 'refresh');
        } else {
            if ($return == 'not_varified') {
                $this->session->set_flashdata('messagePr', "Ce compte est Inactif veillez vous rapprocher de l'administrateur");
                redirect(base_url() . 'user/login', 'refresh');
            } else {
                $this->session->set_userdata('user_details', $return);
            }
            redirect(base_url() . 'user/profile', 'refresh');
        }
    }

    /**
     * This function is used send mail in forget password
     * @return Void
     */
    public function forgetpassword() {
        $page['title'] = 'Mot de passe oublie';
        if ($this->input->post()) {
            $setting = settings();
            $res = $this->User_model->get_data_by('tb_users', $this->input->post('col_email'), 'col_email', 1);
            if (isset($res[0]->col_id) && $res[0]->col_id != '') {
                $col_key = $this->getVarificationCode();
                $this->User_model->updateRow('tb_users', 'col_id', $res[0]->col_id, array('col_key' => $col_key));
                $sub = "Reinitialiser le mot de passe";
                $email = $this->input->post('col_email');
                $data = array(
                    'user_name' => $res[0]->col_nom_prenom,
                    'action_url' => base_url(),
                    'logo' => ASSET_PATH . "images/logo.jpg",
                    'sender_name' => $setting['company_name'],
                    'website_name' => $setting['website'],
                    'varification_link' => base_url() . 'user/mail_varify?code=' . $col_key,
                    'url_link' => base_url() . 'user/mail_varify?code=' . $col_key,
                );
                $body = $this->User_model->get_template('forgot_password');
                $body = $body->html;
                foreach ($data as $key => $value) {
                    $body = str_replace('{var_' . $key . '}', $value, $body);
                }
                if ($setting['mail_setting'] == 'php_mailer') {
                    $this->load->library("send_mail");
                    $emm = $this->send_mail->email($sub, $body, $email, $setting);
                } else {
                    // content-type is required when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= 'From: ' . $setting['EMAIL'] . "\r\n";
                    $emm = mail($email, $sub, $body, $headers);
                }
                if ($emm) {
                    $this->session->set_flashdata('messagePr', 'Un mail pour reinitialiser votre mot de passe a ete envoye');
                    redirect(base_url() . 'user/login', 'refresh');
                }
            } else {
                $this->session->set_flashdata('forgotpassword', 'Ce compte est inexistant'); //die;
                redirect(base_url() . "user/forgetpassword");
            }
        } else {
            $this->load->view('include/script');
            $this->load->view('forget_password');
        }
    }

    /**
     * This function is used to load view of reset password and varify user too 
     * @return : void
     */
    public function mail_varify() {
        $return = $this->User_model->mail_varify();
        $this->load->view('include/script');
        if ($return) {
            $data['col_email'] = $return;
            $this->load->view('set_password', $data);
        } else {
            $data['col_email'] = 'allredyUsed';
            $this->load->view('set_password', $data);
        }
    }

    /**
     * This function is used to reset password in forget password process
     * @return : void
     */
    public function reset_password() {
        $return = $this->User_model->ResetPpassword();
        if ($return) {
            $this->session->set_flashdata('messagePr', 'Mot de passe modifié avec success..');
            redirect(base_url() . 'user/login', 'refresh');
        } else {
            $this->session->set_flashdata('messagePr', 'Impossible de modifier le mot de passe');
            redirect(base_url() . 'user/login', 'refresh');
        }
    }

    /**
     * This function is generate hash code for random string
     * @return string
     */
    public function getVarificationCode() {
        $pw = $this->randomString();
        return $varificat_key = password_hash($pw, PASSWORD_DEFAULT);
    }

    /**
     * This function is Showing users profile
     * @return Void
     */
    public function profile($id = '') {
        is_login();
        if($this->session->userdata('user_details')[0]->col_login_mobile){
            redirect(base_url() . 'gestion_mobile', 'refresh');
        }else{
            if (!isset($id) || $id == '') {
                $id = $this->session->userdata('user_details')[0]->col_id;
            }
            $data['user_data'] = $this->User_model->get_users($id);
            $this->load->view('include/header');
            $this->load->view('profile', $data);
            $this->load->view('include/footer');
        }
    }

    /**
     * This function is used to upload file
     * @return Void
     */
    function upload() {
        foreach ($_FILES as $name => $fileInfo) {
            $filename = $_FILES[$name]['name'];
            $tmpname = $_FILES[$name]['tmp_name'];
            $exp = explode('.', $filename);
            $ext = end($exp);
            $newname = $exp[0] . '_' . time() . "." . $ext;
            $config['upload_path'] = 'assets/images/';
            $config['upload_url'] = base_url() . 'assets/images/';
            $config['allowed_types'] = "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
            $config['max_size'] = '2000000';
            $config['file_name'] = $newname;
            $this->load->library('upload', $config);
            move_uploaded_file($tmpname, "assets/images/" . $newname);
            return $newname;
        }
    }

    /**
     * This function is used to add and update users
     * @return Void
     */
    public function add_edit($id = '') {
        $data = $this->input->post();
        $profile_pic = 'user.png';
        if ($this->input->post('col_id')) {
            $id = $this->input->post('col_id');
        }
        if (isset($this->session->userdata('user_details')[0]->col_id)) {
            if ($this->input->post('col_id') == $this->session->userdata('user_details')[0]->col_id) {
                $redirect = 'profile';
            } else {
                $redirect = 'userTable';
            }
        } else {
            $redirect = 'login';
        }
        if ($this->input->post('fileOld')) {
            $newname = $this->input->post('fileOld');
            $profile_pic = $newname;
        } else {
            $data[$name] = '';
            $profile_pic = 'user.png';
        }
        foreach ($_FILES as $name => $fileInfo) {
            if (!empty($_FILES[$name]['name'])) {
                $newname = $this->upload();
                $data[$name] = $newname;
                $profile_pic = $newname;
            } else {
                if ($this->input->post('fileOld')) {
                    $newname = $this->input->post('fileOld');
                    $data[$name] = $newname;
                    $profile_pic = $newname;
                } else {
                    $data[$name] = '';
                    $profile_pic = 'user.png';
                }
            }
        }
        if ($id != '') {
            $data = $this->input->post();
            if ($this->input->post('col_status') != '') {
                $data['col_status'] = $this->input->post('col_status');
            }
            if ($this->input->post('col_password') != '') {
                if ($this->input->post('currentpassword') != '') {
                    $old_row = getDataByid('tb_users', $this->input->post('col_id'), 'col_id');
                    if (password_verify($this->input->post('currentpassword'), $old_row->col_password)) {
                        if ($this->input->post('col_password') == $this->input->post('confirmPassword')) {
                            $password = password_hash($this->input->post('col_password'), PASSWORD_DEFAULT);
                            $data['col_password'] = $password;
                        } else {
                            $this->session->set_flashdata('messagePr', 'Le mot de passe et la confirmation doivent etre identique');
                            redirect(base_url() . 'user/' . $redirect, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('messagePr', 'Mot de passe Actuel invalid');
                        redirect(base_url() . 'user/' . $redirect, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('messagePr', 'Mot de passe actuel est requis');
                    redirect(base_url() . 'user/' . $redirect, 'refresh');
                }
            }
            $id = $this->input->post('col_id');
            unset($data['fileOld']);
            unset($data['currentpassword']);
            unset($data['confirmPassword']);
            unset($data['col_id']);
            if (isset($data['edit'])) {
                unset($data['edit']);
            }
            if ($data['col_password'] == '') {
                unset($data['col_password']);
            }
            $data['col_profil_pic'] = $profile_pic;
            $this->User_model->updateRow('tb_users', 'col_id', $id, $data);
            $this->session->set_flashdata('messagePr', 'Donnee modifie avec success');
            redirect(base_url() . 'user/' . $redirect, 'refresh');
        }
    }

    /**
     * This function is used to delete users
     * @return Void
     */
    public function delete($id) {
        is_login();
        $ids = explode('-', $id);
        foreach ($ids as $id) {
            $this->User_model->delete($id);
        }
        redirect(base_url() . 'user/userTable', 'refresh');
    }

    /**
     * This function is used to check email is alredy exist or not
     * @return TRUE/FALSE
     */
    public function checEmailExist() {
        $result = 1;
        $res = $this->User_model->get_data_by('tb_users', $this->input->post('col_email'), 'col_email');
        if (!empty($res)) {
            if ($res[0]->col_id != $this->input->post('uId')) {
                $result = 0;
            }
        }
        echo $result;
        exit;
    }

    /**
     * This function is used to Generate a token for varification
     * @return String
     */
    public function generate_token() {
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $special = ".-+=_,!@$#*%<>[]{}";
        $chars = $alpha . $alpha_upper . $numeric;
        $token = '';
        $up_lp_char = $alpha . $alpha_upper . $special;
        $chars = str_shuffle($chars);
        $token = substr($chars, 10, 10) . strtotime("now") . substr($up_lp_char, 8, 8);
        return $token;
    }

    /**
     * This function is used to Generate a random string
     * @return String
     */
    public function randomString() {
        $alpha = "abcdefghijklmnopqrstuvwxyz";
        $alpha_upper = strtoupper($alpha);
        $numeric = "0123456789";
        $special = ".-+=_,!@$#*%<>[]{}";
        $chars = $alpha . $alpha_upper . $numeric;
        $pw = '';
        $chars = str_shuffle($chars);
        $pw = substr($chars, 8, 8);
        return $pw;
    }

}
