<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Table_controller')){ require APPPATH.'libraries/Table_controller.php';}


class Prefund extends Table_controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->load->library( 'code' );
        $this->load->helper( 'form' );

        $this->table = $this->currency_exchange->table_orders;
        $this->table_arhiv = $this->currency_exchange->table_orders_arhive;

        $this->cols = array(
            'id' => "int",
            'seller_user_id' => "int",
            'seller_amount' => "float",
            'buyer_amount_down' => "float",
            'seller_set_up_date' => "datetime",
            'status' => "text",
            'bonus' => "int"
        );

        $setting = array(
            'ctrl' => 'currency_exchange',
            'view' => 'currency_exchange',
            'table' => $this->currency_exchange->table_orders,
            'argument' => array_keys($this->cols));


        $this->header = array(
            "title" => "Список записей",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "debet_cid" => array(
                    "title" => "debet_cid",
                    "filter" => true,
                    "order" => true,
                ),
//                "seller_user_id" => array(
//                    "title" => "ID пользователя",
//                    "filter" => true,
//                    "order" => true,
//                ),
//                "seller_amount" => array(
//                    "title" => "Сумма",
//                    "filter" => true,
//                    "order" => true,
//                ),
//
//                "buyer_amount_down" => array(
//                    "title" => "К получению",
//                    "filter" => true,
//                    "order" => true,
//                ),
//
//                "seller_set_up_date" => array(
//                    "title" => "Дата",
//                    "filter" => true,
//                    "order" => true,
//                ),
//                "bonus" => array(
//                    "title" => "Bonus",
//                    "filter" => true,
//                    "order" => true,
//                ),
//                "status" => array(
//                    "title" => "Статус",
//                    "filter" => true,
//                    "order" => false,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => getCurExchStatuses()
//                        )
//                    )
//                ),


//
            ),
        );
    }


    public function all() {
//        if (isset($_POST["def"])) {
//            $all_items = $this->prefund->get_all();
//            echo json_encode($all_items);
//            die;
//        }


        $this->content->view('prefund_all', 'Все записи',null);

        $title = 'Все заявки';
        $this->header['title'] = $title;

        $this->_tableWork_('1=1');


        $this->data["url"] = "/opera/prefund/all";
        $this->data["controller"] = '/opera/prefund/all';
        $this->content->view('prefund_all', $title, $this->data);
    }
    protected function _tableWork_($where)
    {

//        extract($vars);
//        pre($action);
//        pre($vars);
//        vred($header);
        if (isset($_POST["def"]))
        {
            echo $this->_tableHeader4Array($this->header);
            die;
        }

        if(isset($_POST["offset"]))
        {
//            pred();
//            $r = array("count" => "", "rows" => array(), "sql" => "");

            echo $this->prefund->getAll($this->cols, $this->table, $where);

//            $r["rows"] = $res;
//            $r["count"] = count($res);
////            pred($r);
//            echo json_encode($r);

            die;
        }
    }
}