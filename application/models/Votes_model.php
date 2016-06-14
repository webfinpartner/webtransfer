<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Votes_model extends CI_Model {
    public $tableName = "votes";

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model","users");
    }

    public function isVoted($id_vote, $id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        return !empty($this->db->get_where($this->tableName, array('id_user' => $id_user, 'id_vote'=>$id_vote))->row());
    }

    public function vote($input, $id_user = NULL) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        $input['id_user'] = $id_user;
        $input['ip_addr'] = get_ip();
        $this->db->insert($this->tableName, $input);
    }

    public function getVotes($id_vote) {
        return $this->db
            ->select("COUNT(id) count, variant")
            ->where(array('id_vote' => $id_vote))
            ->group_by("variant")
            ->get($this->tableName)
            ->result();
    }
}
