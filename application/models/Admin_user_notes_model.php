<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_user_notes_model extends CI_Model {
    public $tableName = "admin_user_notes";


    public function __construct() {
        parent::__construct();
    }

    public function getNotes($id_user) {
        return $this->db->where("id_user", $id_user)->get($this->tableName)->result();
    }

    public function addNotes($id_user, $note) {
        $p = Permissions::getInstance();
        $id_admin = $p->getAdminId();
        return $this->db->insert($this->tableName, array("id_user" => $id_user, "note" => $note, "id_admin" => $id_admin));
    }

}
