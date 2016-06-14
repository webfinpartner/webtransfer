<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volunteers_model extends CI_Model {

	public function __construct(){
            parent::__construct();
	}

        public function setVolunteerStatus($id_user,$status) {
            return $this->db->insert("volunteers", array("id_user" => $id_user, "status" => $status));
        }
}