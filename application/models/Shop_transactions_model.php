<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shop_transactions_model extends CI_Model {
    public $tableName = "shop_transactions";

    public function addShopTransaction($data) {
        $this->db->insert($this->tableName, $data);
    }

    public function getAll($params) {
        $filter = (float) $params["filter"];
        $per_page = (int) $params["per_page"];
        $offset = (int) $params["offset"];
        $order = $params["order"];
        $shop_id = $params["shop_id"];

        $r = array("count" => "0", "rows" => array(), "sql" => "");

        if(!empty($filter)) $this->_createFilter($filter);
        $r["count"] = $this->db
                ->select( 'COUNT(*) as count' )
                ->where("shop_id",$shop_id)
                ->get($this->tableName)
                ->row("count");

        if(!empty($order)) $this->_createOrder($order);
        if(!empty($filter)) $this->_createFilter($filter);

        $r["rows"] = $this->db
                ->where("shop_id",$shop_id)
                ->limit($per_page, $offset)
                ->get($this->tableName)
                ->result();
        return $r;
    }

    private function _createOrder($order) {
        foreach ($order as $key => $value)
            $this->db->order_by($key, $value);
    }

    private function _createFilter($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;
            $this->db->where("$t$key LIKE", "%$value%");
        }
    }

    public function getPays($limit, $per, $id_user) {
        return $this->db->where(array("s.user_id" => $id_user))
            ->select("s.*, st.*")
            ->join("shops s", "s.shop_id = st.shop_id")
            ->limit($limit, $per)
            ->order_by('date desc')
            ->get("$this->tableName st")
            ->result();
    }

    public function getCountPays($id_user) {
        return $this->db->where(array("user_id" => $id_user))
            ->count_all_results($this->tableName);
    }

    public function isExistOrder($ident_shop, $order_id) {
        return $this->db->where(array("shop_id" => $ident_shop, 'order_id' => $order_id))
            ->count_all_results($this->tableName);
    }

}
