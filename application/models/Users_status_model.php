<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_status_model extends CI_Model {
    public $tableName = "users_status";

    public function get_status($id_user) {
        return $this->db->order_by("date","DESC")->get_where($this->tableName, array("id_user" => $id_user))->result();
    }

    public function set_status($id_user, $status, $note = '',$id_admin = null) 
    {
        if(empty($id_admin))
        {
            $p = Permissions::getInstance();
            $id_admin = $p->getAdminId();
        }
        
        return $this->db->insert($this->tableName, array("id_user" => $id_user, "status" => $status, "note" => $note, "id_admin" => $id_admin));
    }

}
