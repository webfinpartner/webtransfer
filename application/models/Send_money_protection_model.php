<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_money_protection_model extends CI_Model {
    public $tableName = "send_money_protection";

    public function __construct(){
        parent::__construct();
    }

    public function setProtectionCode($code, $transaction_id) {
        $this->db->insert($this->tableName, array('code' => $code, 'transaction_id' => $transaction_id));
    }

    public function isHaveProtection($transaction_id) {
        return $this->db->where(array('transaction_id' => $transaction_id))->count_all_results($this->tableName);
    }

    public function checkProtection($transaction_id, $code) {
        $id_user = $this->accaunt->get_user_id();
        return $this->db
            ->from("$this->tableName smp")
            ->join("transactions t", "t.id = smp.transaction_id")
            ->where(array('smp.transaction_id' => $transaction_id, 'smp.code' => $code, 't.id_user' => $id_user))
            ->count_all_results();
    }
}