<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Card_exchange_list_model extends CI_model
{
    public $tableName = 'card_exchange_list';

    const STATUS_NOT_PAEYD = 0;
    const STATUS_PAEYD = 1;

    public function autoPayout($sys) {
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->library('code');
        $this->load->library('payout');

        if('all' != $sys)
            $this->db->where('ce.payment_code', $sys);

        $dataCollection = $this->db
                        ->select("ce.*")
                        ->where(["ce.status" => self::STATUS_NOT_PAEYD, 'ce.trnno' => ''])
                        ->limit(50)
                        ->get($this->tableName." ce")
                        ->result();

        if( empty($dataCollection) ){
            return 1;
        }

        $newContent = '';
        if(config_item("Payout_log"))
            $this->base_model->log2file(PHP_EOL.count($dataCollection), "auto_payout_transactions");
        foreach ($dataCollection as $data) {
            $data->email = '-';
            $data->okpay = $data->perfectmoney = $data->payeer = $data->payment_wallet;
            $data->value = $data->payment_code;
            $data->summa = $data->amount;
            $psnts = 0;
//            if (stripos($data->note, " (комисия $ "))
//                $psnts = substr($data->note, (stripos($data->note, " (комисия $ ") + 19));

            $data->commission = (float) $psnts; //будет или нет комисия определяется внутри автооплаты в зависимости от метода
            $data->commissionSumm = $data->summa;
            $data->psnts = payuot_systems_psnt();

            $data->comment = "Обмен средств на webtransfercard.com #$data->id";
            $data->summa = floor($data->summa * 100) / 100;

            $row = $this->payout->pay($data);

            $newContent .= $this->_save2Db($row);
        }
        if(config_item("Payout_log"))
            $this->base_model->log2file("end".count($dataCollection).PHP_EOL, "auto_payout_transactions");

    }

    private function _save2Db($row){
        if("ok" == $row["res"]){
            $this->db->update($this->tableName, ['status' => self::STATUS_PAEYD, 'trnno' => $row["res_id"]], ['id' => $row['id']]);
        }
    }

    public function getRequests() {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array());

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "ce.status" => self::STATUS_NOT_PAEYD,
                ])
                ->get($this->tableName." ce")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("ce.*")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "ce.status" => self::STATUS_NOT_PAEYD,
            ])
            ->limit($per_page, $offset)
            ->get($this->tableName." ce")
            ->result(); //echo $this->db->last_query();die;

        return json_encode($r);
    }

    private function _createOrder($order) {
        foreach ($order as $key => $value)
            $this->db->order_by($key, $value);
    }

    private function _createFilter($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;

            if ("fio" == $key) {
                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            }
            else $this->db->where("$t$key LIKE", "%$value%");
        }
    }

    private function _createSearch($search, $cols) {
        $search = explode(" ", $search);
        foreach ($search as $word) {
            $wordEncript = $this->code->code($word);
            $word = preg_match("/[а-я]/i", $word) ? "text" : $word;

            $where = "(";
            foreach ($cols as $col => $type) {
                if ("encript" == $type)
                    $where .= "t.$col = '$wordEncript' OR ";
                else
                    $where .= "t.$col LIKE '%$word%' OR ";
            }

            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";

            $this->db->where($where);
        }
    }

}