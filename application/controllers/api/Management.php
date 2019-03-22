<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Management extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['paiement_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['paiement_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['paiement_delete']['limit'] = 50; // 50 requests per hour per user/key
        // chargement
        $this->load->model("api/Management_model");
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    public function index() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "204";
        $retour["message"] = "index n'est pas defini sur ce controlleur";
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function registration_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('nom_prenom') != '' && $this->post('num_tel') != '' &&
                $this->post('email') != '' && $this->post('ville') != '' &&
                $this->post('mot_de_pass') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('num_tel'))) {
                if ($this->email->valid_email($this->post('email'))) {
                    $password = sha1($this->post('mot_de_pass'));
                    $g = sha1($this->post('mot_de_pass'));
                    $login_email = $this->post('email');
                    $login_tel = $this->post('num_tel');
                    $test = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login_tel' or col_email = '$login_email'");
                    $val = empty($test) ? TRUE : !($password == $test[0]->col_passwd);
                    if ($val) {
                        $champ = $this->post('num_tel');
                        $test = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$champ'");
                        if (empty($test)) {
                            $champ = $this->post('email');
                            $test = $this->Management_model->get_information_string_where("tb_client", "col_email = '$champ'");
                            if (empty($test)) {
                                $champ = $this->post('col_nom_prenom');
                                $test = $this->Management_model->get_information_string_where("tb_client", "col_nom_prenom = '$champ'");
                                if (empty($test)) {
                                    $to_save['col_nom_prenom'] = $this->post('nom_prenom');
                                    $to_save['col_tel'] = $this->post('num_tel');
                                    #$to_save['col_num_carte'] = $this->post('num_carte');
                                    $to_save['col_email'] = $this->post('email');
                                    $to_save['col_ville'] = $this->post('ville');
                                    $to_save['col_date_creation'] = date("Y-m-d H:i:s");
                                    $to_save['col_etat'] = 1;
                                    $to_save['col_passwd'] = sha1($this->post('mot_de_pass'));
                                    $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_client', 'col_id');
                                    $retour["statut"] = "SUCCES";
                                    $retour["code"] = "200";
                                    $retour["message"] = json_encode($this->post());
                                } else {
                                    $retour["code"] = "102";
                                    $retour["message"] = "nom prenom existe deja: " . $this->post('col_nom_prenom');
                                }
                            } else {
                                $retour["code"] = "103";
                                $retour["message"] = "Email existe deja: " . $this->post('email');
                            }
                        } else {
                            $retour["code"] = "101";
                            $retour["message"] = "Numero telephonique existe deja: " . $this->post('num_tel');
                        }
                    } else {
                        $retour["code"] = "104";
                        $retour["message"] = "Compte deja existant" . $this->post('num_tel');
                    }
                } else {
                    $retour["code"] = "105";
                    $retour["message"] = "Mauvais format de l'email: " . $this->post('email');
                }
            } else {
                $retour["code"] = "106";
                $retour["message"] = "Mauvais numero de telephone: " . $this->post('num_tel');
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function login_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('mot_de_pass') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $password = sha1($this->post('mot_de_pass'));
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                addLog("\nNOUVEAU: " . $password . " \nANCIEN: " . $val[0]->col_passwd, "management");
                $test = empty($val) ? FALSE : ($password == $val[0]->col_passwd);
                if ($test) {
                    if ($val[0]->col_etat) {
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "200"; # {nom_prenom, num_tel, email, num_carte, ville, cle_de_session}
                        $to_message['nom_prenom'] = $val[0]->col_nom_prenom;
                        $to_message['num_tel'] = $val[0]->col_tel;
                        $to_message['email'] = $val[0]->col_email;
                        $to_message['col_est_revendeur'] = ($val[0]->col_est_revendeur) ? "OUI" : "NON";
                        $to_message['ville'] = $val[0]->col_ville;
                        $to_message['cle_de_session'] = $val[0]->col_id . "--" . password_hash("VEGAS-AFRICA-SARL-FKC", PASSWORD_DEFAULT);
                        $retour["message"] = json_encode($to_message);
                    } else {
                        $retour["code"] = "108";
                        $retour["message"] = "Compte inactif";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login et/ou mot de passe incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: get: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function modification_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('nom_prenom') != '' && $this->post('num_tel') != '' && $this->post('cle_de_session') != '' &&
                $this->post('email') != '' && $this->post('ville') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('num_tel'))) {
                if ($this->email->valid_email($this->post('email'))) {
                    $login_email = $this->post('email');
                    $cle = split("--", $this->post('cle_de_session'))[0];
                    $val = $this->Management_model->get_information_string_where("tb_client", "col_id = $cle");
                    if (!empty($val)) {
                        $login_tel = $this->post('num_tel');
                        $nom_prenom = $this->post('nom_prenom');
                        $test = $this->Management_model->get_information_string_where("tb_client", "col_id <> $cle and (col_tel = '$login_tel' or col_email = '$login_email' or col_nom_prenom = '$nom_prenom')");
                        if (empty($test)) {
                            $to_save['col_nom_prenom'] = $this->post('nom_prenom');
                            $to_save['col_tel'] = $this->post('num_tel');
                            $to_save['col_email'] = $this->post('email');
                            $to_save['col_ville'] = $this->post('ville');
                            if ($this->post('mot_de_pass') != '') {
                                $to_save['col_passwd'] = sha1($this->post('mot_de_pass'));
                            }
                            $to_save['col_id'] = $cle;
                            $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_client', 'col_id');
                            $to_message['nom_prenom'] = $this->post('nom_prenom');
                            $to_message['num_tel'] = $this->post('num_tel');
                            $to_message['email'] = $this->post('email');
                            #$to_message['num_carte'] = $this->post('num_carte');
                            $to_message['ville'] = $this->post('ville');
                            $to_message['cle_de_session'] = $this->post('cle_de_session');
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "200";
                            $retour["message"] = json_encode($to_message);
                        } else {
                            $retour["code"] = "104";
                            $retour["message"] = "Compte deja existant" . $this->post('num_tel');
                        }
                    } else {
                        $retour["code"] = "108";
                        $retour["message"] = "Login incorrect";
                    }
                } else {
                    $retour["code"] = "105";
                    $retour["message"] = "Mauvais format de l'email: " . $this->post('email');
                }
            } else {
                $retour["code"] = "106";
                $retour["message"] = "Mauvais numero de telephone: " . $this->post('num_tel');
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function solde_GET() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->get('login') != '' && $this->get('cle_de_session') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->get('login')) || $this->email->valid_email($this->get('login'))) {
                $login = $this->get('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $retour1["commission"] = $val[0]->col_commissions;
                    $retour1["has_commission"] = ($val[0]->col_est_revendeur) ? "OUI" : "NON";
                    $retour1["solde"] = $val[0]->col_solde;
                    $retour["statut"] = "SUCCES";
                    $retour["code"] = "200";
                    $retour["message"] = $retour1;
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function historique_GET() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->get('login') != '' && $this->get('cle_de_session') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->get('login')) || $this->email->valid_email($this->get('login'))) {
                if (($this->get('date_debut') != '' && $this->get('date_fin') != '' && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/", $this->get('date_debut')) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/", $this->get('date_fin'))) ||
                        ($this->get('date_debut') == '' && $this->get('date_fin') == '')) {
                    $login = $this->get('login');
                    $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                    if (!empty($val)) {
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "200";
                        $date_deb = $this->get('date_debut');
                        $date_fin = $this->get('date_fin');
                        $cle = split("--", $this->get('cle_de_session'))[0];
                        $table = 'tb_operation';
                        $primaryKey = 'col_id';
                        $columns = array(
                            array('db' => 'col_client', 'dt' => 0),
                            array('db' => 'col_utilisateur', 'dt' => 1),
                            array('db' => 'col_type_operation', 'dt' => 2),
                            array('db' => 'col_date_operation', 'dt' => 3),
                            array('db' => 'col_montant', 'dt' => 4),
                            array('db' => 'col_description', 'dt' => 5),
                            array('db' => 'col_solde_restant', 'dt' => 6),
                            array('db' => 'col_commission', 'dt' => 7)
                        );
                        $sql_details = array(
                            'user' => $this->db->username,
                            'pass' => $this->db->password,
                            'db' => $this->db->database,
                            'host' => $this->db->hostname # AND col_date_operation BETWEEN ‘$date_deb’ AND ‘$date_fin’
                        );
                        $where = array("col_client = $cle");
                        $output_arr = SSP::complex($this->get(), $sql_details, $table, $primaryKey, $columns, $where);
                        $val_glogal = array();
                        foreach ($output_arr['data'] as $key => $value) {
                            $tmp = [];
                            $temp1 = $output_arr['data'][$key][0];
                            $output_arr['data'][$key][0] = $this->Management_model->get_information_string_where('tb_client', "col_id = $temp1")[0]->col_nom_prenom;
                            $temp2 = $output_arr['data'][$key][1];
                            $output_arr['data'][$key][1] = $this->Management_model->get_information_string_where('users', "users_id = $temp2")[0]->name;
                            $temp3 = $output_arr['data'][$key][2];
                            $output_arr['data'][$key][2] = $this->Management_model->get_information_string_where('tb_type_operation', "col_id = $temp3")[0]->col_type;
                            $tmp["col_client"] = $output_arr['data'][$key][0];
                            $tmp["col_utilisateur"] = $output_arr['data'][$key][1];
                            $tmp["col_type_operation"] = $output_arr['data'][$key][2];
                            $tmp["col_date_operation"] = $output_arr['data'][$key][3];
                            $tmp["col_montant"] = $output_arr['data'][$key][4];
                            $tmp["col_description"] = $output_arr['data'][$key][5];
                            $tmp["col_solde_restant"] = $output_arr['data'][$key][6];
                            $tmp["col_commission"] = $output_arr['data'][$key][7];
                            array_push($val_glogal, $tmp);
                            unset($output_arr['data'][$key]);
                        }
                        $output_arr['data'] = $val_glogal;
                        $retour["message"] = $output_arr;
                    } else {
                        $retour["code"] = "108";
                        $retour["message"] = "Login et/ou mot de passe incorrect";
                    }
                } else {
                    $retour["code"] = "202";
                    $retour["message"] = "format de date incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        #echo json_encode($retour);
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function allmessage_GET() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->get('login') != '' && $this->get('cle_de_session') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->get('login')) || $this->email->valid_email($this->get('login'))) {
                if (($this->get('date_debut') != '' && $this->get('date_fin') != '' && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/", $this->get('date_debut')) && preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}\z/", $this->get('date_fin'))) ||
                        ($this->get('date_debut') == '' && $this->get('date_fin') == '')) {
                    $login = $this->get('login');
                    $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                    $tel = $val[0]->col_tel;
                    if (!empty($val)) {
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "200";
                        $date_deb = $this->get('date_debut');
                        $date_fin = $this->get('date_fin');
                        $cle = split("--", $this->get('cle_de_session'))[0];
                        $table = 'tb_message';
                        $primaryKey = 'col_id';
                        $columns = array(
                            array('db' => 'col_sens', 'dt' => 0),
                            array('db' => 'col_date', 'dt' => 1),
                            array('db' => 'col_message', 'dt' => 2)
                        );
                        $sql_details = array(
                            'user' => $this->db->username,
                            'pass' => $this->db->password,
                            'db' => $this->db->database,
                            'host' => $this->db->hostname # AND col_date_operation BETWEEN ‘$date_deb’ AND ‘$date_fin’
                        );
                        $where = array("col_destinataire like ('%$tel%') and count(col_message)<=160");
                        $output_arr = SSP::complex($this->get(), $sql_details, $table, $primaryKey, $columns, $where);
                        $val_glogal = array();
                        foreach ($output_arr['data'] as $key => $value) {
                            $tmp = [];
                            $tmp["col_sens"] = $output_arr['data'][$key][0] ? "ENVOYE" : "RECU";
                            $tmp["col_date"] = $output_arr['data'][$key][1];
                            $tmp["col_message"] = $output_arr['data'][$key][2];
                            array_push($val_glogal, $tmp);
                            unset($output_arr['data'][$key]);
                        }
                        $output_arr['data'] = $val_glogal;
                        $retour["message"] = $output_arr;
                    } else {
                        $retour["code"] = "108";
                        $retour["message"] = "Login et/ou mot de passe incorrect";
                    }
                } else {
                    $retour["code"] = "202";
                    $retour["message"] = "format de date incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        #echo json_encode($retour);
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function forgotpassword_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('new_pass_word') == '' && $this->post('code') == '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $retour["statut"] = "SUCCES";
                    $retour["code"] = "200";
                    $retour["message"] = "Message recu";
                    # `tb_message`(`col_id`, `col_message`, `col_date`, `col_destinataire`, `col_operation`)
                    $to_save['col_message'] = rendomCode();
                    #############################################################
                    # SEND $to_save['col_message'] BY MAIL AND SMS TO CUSTUMER
                    $global_data = array();
                    $une_data['send_to'] = $val[0]->col_tel;
                    $une_data['message_sms'] = "CODE: " . $to_save['col_message'];
                    $info_to_send['user_name'] = $val[0]->col_nom_prenom;
                    $info_to_send['message_mail'] = "CODE: " . $to_save['col_message'];
                    $info_to_send['logo'] = ASSET_PATH . "images/logo.jpg";
                    $une_data['info_to_send'] = $info_to_send;
                    array_push($global_data, $une_data);
                    $une_data['send_to'] = $val[0]->col_email;
                    array_push($global_data, $une_data);
                    send_sms_mail($global_data);
                    #############################################################
                    $to_save['col_date'] = date('Y-m-d H:m:s');
                    $to_save['col_destinataire'] = $val[0]->col_tel . ";" . $val[0]->col_email;
                    $to_save['col_sens'] = 1;
                    $to_save['col_statut'] = 1;
                    $retour["message"] = "Code de verification envoyé par mail et/ou sms";
                    $to_save_client['col_code'] = $to_save['col_message'];
                    $to_save_client['col_id'] = $val[0]->col_id;
                    $this->Management_model->enregistrer_ou_modifier($to_save_client, 'tb_client', 'col_id');
                    $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_message', 'col_id');
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            if ($this->post('login') != '' && $this->post('new_pass_word') != '' && $this->post('code') == '') {
                if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                    $login = $this->post('login');
                    $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                    if (!empty($val)) {
                        if (strpos($val[0]->col_code, "VEGAS-FKC") !== FALSE) {
                            $to_save['col_passwd'] = sha1($this->post('new_pass_word'));
                            $to_save['col_id'] = $val[0]->col_id;
                            $to_save['col_code'] = 'VEGAS';
                            $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_client', 'col_id');
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "200";
                            $retour["message"] = "Mot de Passe Modifie";
                        } else {
                            $retour["code"] = "108";
                            $retour["message"] = "Attente de code de verification";
                        }
                    } else {
                        $retour["code"] = "108";
                        $retour["message"] = "Login et/ou mot de passe incorrect";
                    }
                } else {
                    $retour["code"] = "201";
                    $retour["message"] = "parametres incorrects";
                }
            } else {
                if ($this->post('login') != '' && $this->post('code') != '' && $this->post('new_pass_word') == '') {
                    if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                        $login = $this->post('login');
                        $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                        if (!empty($val)) {
                            if (strpos($val[0]->col_code, $this->post('code')) !== FALSE) {
                                $to_save['col_code'] = 'VEGAS-FKC';
                                $to_save['col_id'] = $val[0]->col_id;
                                $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_client', 'col_id');
                                $retour["statut"] = "SUCCES";
                                $retour["code"] = "200";
                                $retour["message"] = "Code valide";
                            } else {
                                $retour["code"] = "201"; ###############
                                $retour["message"] = "Code de verification incorrects";
                            }
                        } else {
                            $retour["code"] = "108";
                            $retour["message"] = "Login et/ou mot de passe incorrect";
                        }
                    } else {
                        $retour["code"] = "201";
                        $retour["message"] = "parametres incorrects";
                    }
                } else {
                    $retour["code"] = "201";
                    $retour["message"] = "parametres incorrects";
                }
            }
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function service_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        if ($this->get('login') != '' && $this->get('cle_de_session') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->get('login')) || $this->email->valid_email($this->get('login'))) {
                $login = $this->get('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $service = $this->Management_model->get_information_string_where("tb_services", "col_voir_sur_mobile=1");
                    $les_service = array();
                    foreach ($service as $unService) {
                        #array_push($les_service,$unService->col_libele." (".$unService->col_fa_icon.")");
                        #########################
                        $un_enreg['nom'] = $unService->col_libele;
                        $un_enreg['code'] = $unService->col_code;
                        $un_enreg['description'] = $unService->col_description;
                        $un_enreg['id'] = $unService->col_id;
                        $un_enreg['image'] = base_url() . "/assets/fichiers/images/" . $unService->col_image;
                        array_push($les_service, $un_enreg);
                        ####################
                    }
                    $retour["message"] = $les_service;
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function sous_service_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        if ($this->get("service_id") != '') {
            $serviceId = $this->get("service_id");
            $requete = ($this->get("query") != '') ? '%'.$this->get("query") . "%" : '%';
            $souscription = (($this->get("souscription") != '') && ($this->get("souscription"))) ? "AND col_souscription = 1" : "";
            $service = $this->Management_model->get_information_string_where("tb_type_operation", "col_voir_sur_mobile=1 and col_service = $serviceId and col_type like('$requete') $souscription");
            $les_service = array();
            foreach ($service as $unService) {
                array_push($les_service, array("nom" => $unService->col_type . " (" . $unService->col_montant . ")", "id" => $unService->col_id, "montant" => $unService->col_montant));
            }
            $retour["message"] = $les_service;
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function type_installation_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        $service = $this->Management_model->get_information_string_where("tb_services", "col_voir_sur_mobile=0 and col_libele like('%type_installation%')");
        $all = "(";
        foreach ($service as $unD) {
            $all = "$all " . $unD->col_id . ",";
        }
        $all = "$all-1)";
        $param = ($this->get('pour_souscription') != '') ? $this->get('pour_souscription'):1;
        $service_accessoire = $this->Management_model->get_information_string_where("tb_type_operation", "col_voir_sur_mobile=1 and col_souscription = $param and col_service in $all");
        $les_service = array();
        foreach ($service_accessoire as $unService) {
            array_push($les_service, array("nom" => $unService->col_type, "montant" => $unService->col_montant, "id" => $unService->col_id));
        }
        $retour["message"] = $les_service;
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function les_kits_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        $service = $this->Management_model->get_information_string_where("tb_services", "col_voir_sur_mobile=0 and col_libele like('%kits%')");
        $all = "(";
        foreach ($service as $unD) {
            $all = "$all " . $unD->col_id . ",";
        }
        $all = "$all-1)";
        $service_accessoire = $this->Management_model->get_information_string_where("tb_type_operation", "col_voir_sur_mobile=1 and col_service in $all");
        $les_service = array();
        foreach ($service_accessoire as $unService) {
            array_push($les_service, array("nom" => $unService->col_type, "montant" => $unService->col_montant, "id" => $unService->col_id));
        }
        $retour["message"] = $les_service;
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function accessoires_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        $service = $this->Management_model->get_information_string_where("tb_services", "col_voir_sur_mobile=0 and col_libele like('%accessoire%')");
        $all = "(";
        foreach ($service as $unD) {
            $all = "$all " . $unD->col_id . ",";
        }
        $all = "$all-1)";
        $service_accessoire = $this->Management_model->get_information_string_where("tb_type_operation", "col_voir_sur_mobile=1 and col_service in $all");
        $les_service = array();
        foreach ($service_accessoire as $unService) {
            array_push($les_service, array("nom" => $unService->col_type, "montant" => $unService->col_montant, "id" => $unService->col_id));
        }
        $retour["message"] = $les_service;
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function account_GET() {
        $retour["statut"] = "SUCCES";
        $retour["code"] = "200";
        $retour["message"] = array("mtn" => "237677570594", "orange" => "237697152887", "express_union" => "237650594369");
        #$retour["message"] = json_decode($this->Management_model->get_information_string_where("setting","keys='account'")[0]->value,TRUE);
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function lister_client_info_GET() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->get('login') != '' && $this->get('cle_de_session') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->get('login')) || $this->email->valid_email($this->get('login'))) {
                $login = $this->get('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $retour["statut"] = "SUCCES";
                    $retour["code"] = "200";
                    $val_glogal = array();
                    $output_arr = array();
                    $requete = ($this->get("query") != '') ? '%'.$this->get("query")."%" : '%';
                    $requete_revendeur = ($this->get("tout") != '' && $this->get("tout") && $val[0]->col_est_revendeur) ? "col_num_carte like('$requete')" : "(col_client = " . $val[0]->col_id ." AND col_num_carte like('$requete'))";
                    if ($this->get('pagin') != '' && $this->get('pagin')) {
                        $table = 'tb_info_client';
                        $primaryKey = 'col_id';
                        $columns = array(
                            array('db' => 'col_client', 'dt' => 0),
                            array('db' => 'col_ville', 'dt' => 1),
                            array('db' => 'col_quartier', 'dt' => 2),
                            array('db' => 'col_adresse', 'dt' => 3),
                            array('db' => 'col_num_carte', 'dt' => 4),
                            array('db' => 'col_telephone', 'dt' => 5),
                            array('db' => 'col_id', 'dt' => 6)
                        );
                        $sql_details = array(
                            'user' => $this->db->username,
                            'pass' => $this->db->password,
                            'db' => $this->db->database,
                            'host' => $this->db->hostname # AND col_date_operation BETWEEN ‘$date_deb’ AND ‘$date_fin’
                        );
                        $where = array("$requete_revendeur");
                        $output_arr = SSP::complex($this->get(), $sql_details, $table, $primaryKey, $columns, $where);
                        foreach ($output_arr['data'] as $key => $value) {
                            $tmp = [];
                            $temp1 = $output_arr['data'][$key][0];
                            $output_arr['data'][$key][0] = $this->Management_model->get_information_string_where('tb_client', "col_id = $temp1")[0]->col_nom_prenom;
                            $tmp["col_client"] = $output_arr['data'][$key][0];
                            $tmp["col_ville"] = $output_arr['data'][$key][1];
                            $tmp["col_quartier"] = $output_arr['data'][$key][2];
                            $tmp["col_adresse"] = $output_arr['data'][$key][3];
                            $tmp["col_num_carte"] = $output_arr['data'][$key][4] . " (" . $tmp["col_client"] . ")";
                            $tmp["col_telephone"] = $output_arr['data'][$key][5];
                            $tmp["col_id"] = $output_arr['data'][$key][6];
                            array_push($val_glogal, $tmp);
                            unset($output_arr['data'][$key]);
                        }
                    } else {
                        $info_client = $this->Management_model->get_information_string_where('tb_info_client', "$requete_revendeur");
                        foreach ($info_client as $unInfo) {
                            $tmp = [];
                            $tmp["col_client"] = $this->Management_model->get_information_string_where('tb_client', "col_id = " . $unInfo->col_client)[0]->col_nom_prenom;
                            $tmp["col_ville"] = $unInfo->col_ville;
                            $tmp["col_quartier"] = $unInfo->col_quartier;
                            $tmp["col_adresse"] = $unInfo->col_adresse;
                            $tmp["col_num_carte"] = $unInfo->col_num_carte . " (" . $tmp["col_client"] . ")";
                            $tmp["col_telephone"] = $unInfo->col_telephone;
                            $tmp["col_id"] = $unInfo->col_id;
                            array_push($val_glogal, $tmp);
                        }
                    }
                    $output_arr['data'] = $val_glogal;
                    $retour["message"] = $output_arr;
                    #$retour["message"] = $this->Management_model->get_information_string_where("tb_info_client","col_client = ".$val[0]->col_id);
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function add_client_info_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('info_client') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $data_liste = (array) $this->post('info_client');
                    $global_val = array();
                    foreach ($data_liste as $un_enregistrement) {
                        if (isset($un_enregistrement['num_carte']) && isset($un_enregistrement['ville']) && isset($un_enregistrement['quartier']) && isset($un_enregistrement['telephone'])) {
                            $to_save['col_num_carte'] = $un_enregistrement['num_carte'];
                            $to_save['col_ville'] = $un_enregistrement['ville'];
                            $to_save['col_quartier'] = $un_enregistrement['quartier'];
                            $to_save['col_adresse'] = $un_enregistrement['adresse'];
                            $to_save['col_telephone'] = $un_enregistrement['telephone']; # adresse
                            $to_save['col_client'] = $val[0]->col_id;
                            if (isset($un_enregistrement['id']) && $un_enregistrement['id'] > 0) {
                                $to_save['col_id'] = $un_enregistrement['id'];
                            }
                            array_push($global_val, $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_info_client', 'col_id'));
                        }
                    }
                    $retour["statut"] = "SUCCES";
                    $retour["code"] = "200";
                    #$retour["message"] = $this->post('info_client');
                    $retour["message"] = $global_val;
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function delete_client_info_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('ids_info_client') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $global_val = array();
                    foreach (((array) $this->post('ids_info_client')) as $un_enregistrement) {
                        $temp = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                        $une_val['id'] = $un_enregistrement;
                        if (!empty($temp)) {
                            $une_val['info'] = "INFORMATION DEJA UTILISEE DANS UNE TRANSACTION";
                        } else {
                            $this->Management_model->deleteTable('tb_info_client', 'col_id', $un_enregistrement);
                            $une_val['info'] = "INFORMATION SUPPRIMEE";
                        }
                        array_push($global_val, json_encode($une_val));
                    }
                    $retour["statut"] = "SUCCES";
                    $retour["code"] = "200";
                    $retour["message"] = $global_val;
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "format parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }
    
    public function valider_paiement_POST() { # login(num_tel ou email), cle_de_session, info_client(carte,numéro,...),service(id du service), montant (optionnel)
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('info_client') != '' && $this->post('service') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                if (!empty($val)) {
                    $service = $this->post('service');
                    $carte1 = $this->post('info_client');
                    $val1 = $this->Management_model->get_information_string_where("tb_type_operation", "col_id = $service");
                    $carte = $this->Management_model->get_information_string_where("tb_info_client", "col_id = $carte1");
                    if (!empty($val1)) {
                        if ($val[0]->col_solde < $val1[0]->col_montant) {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "205";
                            $retour["message"] = "SOLDE INSSUFISSANT";
                        } else {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "200";
                            $retour["message"] = "Requete en cours de traitement";
                            
                            $montant = ($this->post('montant') && $this->post('montant') > $val1[0]->col_montant) ? $this->post('montant') : $val1[0]->col_montant;
                            #$to_save["col_id"] = 0;
                            $to_save["col_message"] = " Mr/Mme <strong>" . $val[0]->col_nom_prenom . "</strong> SOLICITE <br/><strong>" . $val1[0]->col_type . "</strong><br/> MONTANT RENSEIGNE: <strong>$montant</strong><br/> INFO CLIENT: <strong>" . $carte[0]->col_num_carte . " - " . $carte[0]->col_telephone . "</strong>";
                            $to_save["col_date"] = date("Y-m-d H:m:s");
                            $to_save["col_destinataire"] = $val[0]->col_email . ";" . $val[0]->col_tel;
                            #$to_save["col_operation"] = "";
                            $to_save["col_source"] = $val[0]->col_id . "--" . $val1[0]->col_id . "--" . $carte[0]->col_id . "--" . $montant . "--REABONNEMENT"; # client--servie--carte--montant--source
                            $to_save["col_requete"] = "REABONNEMENT";
                            $to_save["col_sens"] = 1;
                            $to_save["col_statut"] = 1;
                            $to_save["col_traite"] = 0;
                            $this->Management_model->enregistrer_ou_modifier($to_save, "tb_message", "col_id");
                        }
                    } else {
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "201";
                        $retour["message"] = "service inconnu";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function accessoires_paiement_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('accessoires') != '' && $this->post('type') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                $info = $this->Management_model->get_information_string_where("tb_info_client", "col_client = ".$val[0]->col_id);
                if (!empty($val)) {
                    if(!empty($info)){
                        $les_ids = ((array) $this->post('accessoires'));
                        array_push($les_ids, $this->post('type'));
                        $montant = 0;
                        $type_array = array();
                        $lesID = join("-", $les_ids);
                        foreach ($les_ids as $unID) {
                            $un_accessoire = $this->Management_model->get_information_string_where("tb_type_operation", "col_id = $unID");
                            array_push($type_array, "<br/> --> ".$un_accessoire[0]->col_type);
                            $montant += $un_accessoire[0]->col_montant;
                        }
                        $type = join(" ", $type_array);
                        if ($val[0]->col_solde < $montant) {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "205";
                            $retour["message"] = "SOLDE INSSUFISSANT";
                        } else {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "200";
                            $retour["message"] = "Requete en cours de traitement";

                            $message = ($this->post('message') != '') ? $this->post('message') : "";
                            $to_save["col_message"] = " Mr/Mme <strong>" . $val[0]->col_nom_prenom . "</strong> SOLICITE <strong>" . $type . "</strong><br/> MONTANT RENSEIGNE: <strong>$montant</strong><br/> MESSAGE: <strong>$message</strong>";
                            $to_save["col_date"] = date("Y-m-d H:m:s");
                            $to_save["col_destinataire"] = $val[0]->col_email . ";" . $val[0]->col_tel;
                            #$to_save["col_operation"] = "";
                            $to_save["col_source"] = $val[0]->col_id . "--" . $lesID . "--".$info[0]->col_id."--" . $montant . "--ACCESSOIRES"; # client--servie--carte--montant--source
                            $to_save["col_requete"] = "ACCESSOIRES";
                            $to_save["col_sens"] = 1;
                            $to_save["col_statut"] = 1;
                            $to_save["col_traite"] = 0;
                            $this->Management_model->enregistrer_ou_modifier($to_save, "tb_message", "col_id");
                        }
                    }else{
                        $retour["code"] = "201";
                        $retour["message"] = "Enregistrer une information de carte";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects login not match";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function souscription_paiement_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('tel') != '' && $this->post('kit') != '' && $this->post('formule') != '' && $this->post('type') != '' && $this->post('quartier') != '' && $this->post('ville') != '' && $this->post('adresse') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                $info = $this->Management_model->get_information_string_where("tb_info_client", "col_client = ".$val[0]->col_id);
                if (!empty($val)) {
                    if(!empty($info)){
                        $les_ids = array($this->post('kit'), $this->post('type'), $this->post('formule'));
                        $montant = 0;
                        $type_array = array();
                        $lesID = join("-", $les_ids);
                        foreach ($les_ids as $unID) {
                            $un_accessoire = $this->Management_model->get_information_string_where("tb_type_operation", "col_id = $unID");
                            array_push($type_array, "<br/> --> ".$un_accessoire[0]->col_type);
                            $montant += $un_accessoire[0]->col_montant;
                        }
                        $type = join(" ", $type_array);
                        if ($val[0]->col_solde < $montant) {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "205";
                            $retour["message"] = "SOLDE INSSUFISSANT";
                        } else {
                            $retour["statut"] = "SUCCES";
                            $retour["code"] = "200";
                            $retour["message"] = "Requete en cours de traitement";

                            #$to_save["col_id"] = 0;
                            $informations = "<br/>";
                            $informations = $informations."<strong>QUARTIER: </strong>".$this->post('quartier')."<br/>";
                            $informations = $informations."<strong>VILLE: </strong>".$this->post('ville')."<br/>";
                            $informations = $informations."<strong>TELEPHONE: </strong>".$this->post('tel')."<br/>";
                            $informations = $informations."<strong>ADRESSE: </strong>".$this->post('adresse')."<br/>";
                            $to_save["col_message"] = " Mr/Mme <strong>" . $val[0]->col_nom_prenom . "</strong> SOLICITE <strong>" . $type . "</strong><br/> INFO: <strong>" . $informations . "</strong><br/> MONTANT RENSEIGNE: <strong>$montant</strong>";
                            $to_save["col_date"] = date("Y-m-d H:m:s");
                            $to_save["col_destinataire"] = $val[0]->col_email . ";" . $val[0]->col_tel;
                            #$to_save["col_operation"] = "";
                            $to_save["col_source"] = $val[0]->col_id . "--" . $lesID . "--".$info[0]->col_id."--" . $montant . "--SOUSCRIPTION";# client--servie--carte--montant--source
                            $to_save["col_requete"] = "SOUSCRIPTION";
                            $to_save["col_sens"] = 1;
                            $to_save["col_statut"] = 1;
                            $to_save["col_traite"] = 0;
                            $this->Management_model->enregistrer_ou_modifier($to_save, "tb_message", "col_id");
                        }
                    }else{
                        $retour["code"] = "201";
                        $retour["message"] = "Enregistrer une information de carte";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }

    public function notification_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('message') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                $info = $this->Management_model->get_information_string_where("tb_info_client", "col_client = ".$val[0]->col_id);
                if (!empty($val)) {
                    if(!empty($info)){
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "200";
                        $retour["message"] = "Message recu";
                        $to_save["col_message"] = " Mr/Mme <strong>" . $val[0]->col_nom_prenom . "</strong> SOLICITE <strong><br/>". $this->post('message');
                        $to_save['col_date'] = date('Y-m-d H:m:s');
                        $to_save['col_destinataire'] = "678132186;699876016;fodoup@gmail.com;" . $val[0]->col_email;
                        $service = $this->Management_model->get_information_string_where("tb_type_operation", "col_type like('%NOTIFICATION%')")[0];
                        $to_save["col_source"] = $val[0]->col_id . "--0--".$info[0]->col_id."--".$service->col_id."--NOTIFICATION"; # client--servie--carte--montant--source
                        $to_save["col_requete"] = "NOTIFICATION";
                        $to_save['col_sens'] = 0;
                        $to_save['col_statut'] = 1;
                        $new_message = $this->Management_model->enregistrer_ou_modifier($to_save, 'tb_message', 'col_id');
                        send_after_operation_or_message($new_message->col_id, FALSE);
                    }else{
                        $retour["code"] = "201";
                        $retour["message"] = "Enregistrer une information de carte";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect no user";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects login no match";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects missing parameter";
        }
        addLog("INPUT: POST: " . json_encode($this->post()) . "GET:" . json_encode($this->get()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }
    
    public function recharge_solde_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('login') != '' && $this->post('cle_de_session') != '' && $this->post('message') != '' && $this->post('numero') != '' && $this->post('montant') != '') {
            if (preg_match("/^[0-9]{9}$/", $this->post('login')) || $this->email->valid_email($this->post('login'))) {
                $login = $this->post('login');
                $val = $this->Management_model->get_information_string_where("tb_client", "col_tel = '$login' or col_email = '$login'");
                $info = $this->Management_model->get_information_string_where("tb_info_client", "col_client = ".$val[0]->col_id);
                if (!empty($val)) {
                    if(!empty($info)){
                        $message = $this->post('message');
                        $numero = $this->post('numero');
                        $montant = $this->post('montant');
                        $retour["statut"] = "SUCCES";
                        $retour["code"] = "200";
                        $retour["message"] = "Requete en cours de traitement";
                        #$to_save["col_id"] = 0;
                        $to_save["col_message"] = " Mr/Mme <strong>" . $val[0]->col_nom_prenom . "</strong> SOLICITE <strong>RECHARGE DE SOLDE</strong><br/> NUMERO ENCAISSEUR:<strong>$numero</strong><br/> MONTANT:<strong>$montant</strong><br/> MESSAGE:<strong>$message</strong>";
                        $to_save["col_date"] = date("Y-m-d H:m:s");
                        $to_save["col_destinataire"] = $val[0]->col_email . ";" . $val[0]->col_tel;
                        #$to_save["col_operation"] = "";
                        $service = $this->Management_model->get_information_string_where("tb_type_operation", "col_type like('%RECHARGE SOLDE%')")[0];
                        $to_save["col_source"] = $val[0]->col_id."--".$service->col_id."--".$info[0]->col_id."--".$montant . "--SOLDE"; # client--servie--carte--montant--source
                        $to_save["col_requete"] = "SOLDE";
                        $to_save["col_sens"] = 1;
                        $to_save["col_statut"] = 1;
                        $to_save["col_traite"] = 0;
                        $this->Management_model->enregistrer_ou_modifier($to_save, "tb_message", "col_id");
                    }else{
                        $retour["code"] = "201";
                        $retour["message"] = "Enregistrer une information de carte";
                    }
                } else {
                    $retour["code"] = "108";
                    $retour["message"] = "Login incorrect";
                }
            } else {
                $retour["code"] = "201";
                $retour["message"] = "parametres incorrects";
            }
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }
	
    public function sms_request_POST() {
        $retour["statut"] = "ECHEC";
        $retour["code"] = "203";
        $retour["message"] = "Erreur Inconnu";
        if ($this->post('ID') != '' && $this->post('address') != '' && $this->post('message') != '' && $this->post('readstate') != '' && $this->post('time') != '' && $this->post('foldername') != '') {
            $retour["statut"] = "SUCCES";
            $retour["code"] = "200";
            $retour["message"] = "Requete en cours de traitement";
            $to_load_sms["ID"] = $this->post('ID');
            $to_load_sms["message"] = $this->post('message');
            $to_load_sms["address"] = $this->post('address');
            $to_load_sms["readstate"] = $this->post('readstate');
            $to_load_sms["foldername"] = $this->post('foldername');
            $to_load_sms["time"] = $this->post('time');
            $to_load_sms["col_source_phone"] = $this->post('col_source_phone'); # col_source_phone
            
            $to_save['col_message'] = $this->post('message'); 
            #$to_save['col_date_enregistrement'] = date("Y-m-d H:m:s");
            $to_save['col_address'] = $this->post('address');
            $to_save['col_id_position'] = $this->post('ID');
            $to_save['col_source_phone'] = ($this->post('col_source_phone')!= '') ? $this->post('col_source_phone') : "GENERIC";
            $to_save['col_dateSms'] = date("Y-m-d H:i:s", $this->post('time')/1000);
            $to_save['col_source'] = ($this->post('foldername') === "sent") ? 'OUTBOX' : 'INBOX';
            $to_save['col_statut'] = 'DEJA TRAITE';
            if(controlle_sms($to_save['col_message'])){
				$val_exist = $this->Management_model->get_information_string_where('tb_sms',"col_message = '".$to_save['col_message']."' AND col_dateSms = '".$to_save['col_dateSms']."'");
				if(empty($val_exist)){
					$this->Management_model->save_data($to_save, 'tb_sms');
				}
			}
			addLog(json_encode($to_save), "sms_query");
            #addLog(json_encode($to_load_sms), "sms_query");
        } else {
            $retour["code"] = "201";
            $retour["message"] = "parametres incorrects";
        }
        addLog("INPUT: GET: " . json_encode($this->get()) . "POST:" . json_encode($this->post()) . " || OUT: " . json_encode($retour), "management");
        $this->response($retour, REST_Controller::HTTP_OK);
    }
}
