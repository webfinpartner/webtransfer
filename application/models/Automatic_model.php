<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Automatic_model extends CI_Model {
    const PSEVDOBOT_ON = 1;
    const PSEVDOBOT_OFF = 0;

    const AUTOMAT_ZAJM_ON = 1;
    const AUTOMAT_ZAJM_OFF = 0;

    const PSEVDOBOT_AUTOPSNT_ON = 1;
    const PSEVDOBOT_AUTOPSNT_OFF = 0;

    public $tableName = "automatic";
    public $obj = array (
        "zajm" => 0,
        "zajm_sum" => 0,
        "summ" => 0,
        "zajm_time" => 0,
        "time" => 0,
        "zajm_psnt" => 0,
        "percent" => 0,
        "credit" => 0,
        "credit_max_start_psnt_auto" => 0,
        "credit_max_start_psnt" => 0
    );

    public function __construct(){
        parent::__construct();
        $this->obj = (object)$this->obj;
    }

    public function get($id) {
        return $this->db->get_where($this->tableName,array("id_user" => $id))->row();
    }

    public function save($obj) {
        $exist = $this->db->get_where($this->tableName,array("id_user" => $obj->id_user))->row();
        if($exist)
            $this->db->update($this->tableName,$obj,array("id_user" => $obj->id_user), 1);
        else $this->db->insert($this->tableName,$obj);
    }

    public function getZajmBots($limit_sum) {
        $res = $this->db->get_where($this->tableName." a", array('zajm' => self::AUTOMAT_ZAJM_ON))->result();
        return $this->workOverBots($res,$limit_sum);

    }
    public function getPsevdoBots($limit_sum, $item_limit) {
        $res = $this->db->get_where($this->tableName." a", array('credit' => self::PSEVDOBOT_ON))->result();
        return $this->workOverBots($res,$limit_sum, $item_limit);

    }

    private function workOverBots(array $res, $limit_sum, $item_limit = false) {
        $this->load->model('accaunt_model', 'accaunt');
        foreach ($res as $key => &$row) {
            $this->accaunt->set_user($row->id_user);
            $user_ratings = $this->accaunt->get_header_info();
            $valid = true;
             if (!$this->accaunt->isUserAccountVerified($row->id_user)) {
                $valid = false;
            } else if ( $user_ratings['overdue_garant_count'] > 0) {
                $valid = false;
            } else if ( $user_ratings['overdue_standart_count'] > 0) {
                $valid = false;
            }
            if(!$valid) {
                unset($res[$key]);
                continue;
            }

            $row->payment_account = $user_ratings['all_advanced_invests_summ'] + $user_ratings['all_advanced_standart_invests_summ'];
            $row->user_ratings = $user_ratings;
            $row->limit = $limit_sum;
            $row->item_limit = $item_limit;
        }
        return $res;
    }
}