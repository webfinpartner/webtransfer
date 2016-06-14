<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
    public $tableName = "admin";


    public function __construct() {
        parent::__construct();
    }

    public function getAdminById($id_user) {
        return $this->db->get_where($this->tableName, array('id_user' => $id_user))->row();
    }


    public function get_admin_state() {

        $this->load->library('code');
        $id_user = (!empty($_COOKIE[COOKIE_ADMIN])) ? $this->code->decrypt($_COOKIE[COOKIE_ADMIN]) : '';

        if (empty($id_user))
            return null;

        $this->admin_info = $this->db->get_where($this->tableName, array('id_user' => $id_user))->row();

        if (empty($this->admin_info))
            return null;

        return $this->admin_info;
    }
    
    public function sendSms($id_user) {
        $this->load->model('phone_model', 'phone');
        $hash_code = rand(100000, 999999);
        $smsAttemps = 1;
        $phone = $this->db->get_where($this->tableName, array('id_user' => $id_user))->row("telephone");
        $smsSentResponse = $this->phone->sendAdminCode($hash_code, $phone, $smsAttemps);

        if (isset($smsSentResponse['error']))
            return $smsSentResponse;

        $this->db->update($this->tableName,array("sms_code" => $hash_code), array('id_user' => $id_user),1);
        return true;
    }

    public function checkSms($id_user, $code) {
        $code_saved = $this->db->get_where($this->tableName, array('id_user' => $id_user))->row("sms_code");
        return (($code_saved == $code) ? true : false);
    }

    public function getHash($id_admin) {
        return $this->db->get_where($this->tableName, array('id_user' => $id_admin))->row("hash");
    }

    public function setHash($id_admin, $hash) {
        $this->db->update($this->tableName,array("hash" => $hash), array('id_user' => $id_admin),1);
    }

    public function setProtectType($id_admin, $new_protect_type) {
        $this->db->update($this->tableName,array("sec_auth" => $new_protect_type), array('id_user' => $id_admin),1);
    }
    
    public function linkToUsers($id_admin, $id_user) {
        $this->db->update($this->tableName,array("users_user_id" => $id_user), array('id_user' => $id_admin),1);
    }
    
}
