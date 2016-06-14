<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Payment extends Admin_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('monitoring_model', 'monitoring');
        $this->load->helper('form');
        $s_fields = array(
            'id_user' => "int",
            'summa' => "float",
            'metod' => "text",
            'type' => "int",
            'note' => "text",
            'note_admin' => "text",
            'date' => "datetime",
            'bonus' => "int",
            'status' => "int"
        );

        $setting = array('ctrl' => 'payment', 'view' => 'payment', 'table' => 'transactions', 'argument' => array_keys($s_fields)+array('value'));
        $s_fields['user_ip'] = 'text';
        $this->cols = $s_fields;

        $this->setting($setting);
        $this->header = array(
// <editor-fold defaultstate="collapsed" desc="header">
            "title" => "Управление транзакциями",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID пользователя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "metod" => array(
                    "title" => "Метод пополнения",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionsMethodLabel(false, true)
                        )
                    )
                ),
                "bonus" => array(
                    "title" => "Бонус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'B-CREDS',
                                2 => 'WTUSD(2)',
                                3 => 'P-CREDS',
                                4 => 'C-CREDS',
                                5 => 'WTUSD(5)',
                                6 => 'WTDEBIT(6)'
                            )
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "time" => array(
                    "title" => "Срок", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true
                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->payout_payed = array(
// <editor-fold defaultstate="collapsed" desc="payout_payed">
            "title" => "Управление транзакциями",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID польз.",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
//                "metod" => array(
//                    "title" => "Метод пополнения",
//                    "filter" => true,
//                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => getTransactionsMethodLabel(false, true)
//                        )
//                    )
//                ),
                "value" => array(
                    "title" => "Pay sys",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getPaymentSys()
                        )
                    )
                ),
                "bonus" => array(
                    "title" => "Бонус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'B-CREDS',
                                2 => 'WTUSD(2)',
                                3 => 'P-CREDS',
                                4 => 'C-CREDS',
                                5 => 'WTUSD(5)',
                                6 => 'WTDEBIT(6)'
                            )
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
//                "time" => array(
//                    "title" => "Срок", // table title, if false no column title displayed,
//                    // its work if all column title is false
//                    "filter" => true, // column filter, placeholder: input field placeholder
//                    "order" => true
//                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->card_exchange_pay = [
// <editor-fold defaultstate="collapsed" desc="card_exchange_pay">
            "title" => "Список на перевод с карт",
            "columns" => [
                "id"             => ["title" => "ID",           "filter" => true, "order" => true,],
                "user_id"        => ["title" => "ID польз.",    "filter" => true, "order" => true,],
                "wtcard_user_id" => ["title" => "Card ID user", "filter" => true, "order" => true,],
                "dttm"           => ["title" => "Дата",         "filter" => true, "order" => true,"formatter" => ["widget" => 'date', "widget_options" => ["formatte" => "D/M/Y"]]],
                "amount"         => ["title" => "Сумма",        "filter" => true, "order" => true,"formatter" => ["widget" => 'currency',]],
                "payment_code"   => ["title" => "Pay sys",      "filter" => true, "order" => true,"formatter" => ["widget" => 'list',"widget_options" => ["list" => getPaymentSys()]]],
                "payment_wallet" => ["title" => "Кашелек",      "filter" => true, "order" => true,],
//                "status"         => ["title" => "Статус",       "filter" => true, "order" => true,"formatter" => ["widget" => 'list',"widget_options" => ["list" => getTransactionStatuses()]]],
                "trnno"          => ["title" => "Транзакция",   "filter" => true, "order" => true,],
            ]
// </editor-fold>
        ];
        $this->header_bank = array(
// <editor-fold defaultstate="collapsed" desc="header_bank">
            "title" => "Ввод денег через банк",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID получателя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "fio" => array(
                    "title" => "Отправитель",
                    "filter" => true,
                    "order" => true
                ),
                "value" => array(
                    "title" => "ID Отправителя",
                    "filter" => true,
                    "order" => true
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->header_wt_card = array(
// <editor-fold defaultstate="collapsed" desc="header_wt_card">
            "title" => "Вывод денег на карту webtransfer",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID получателя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->header_bank_arbitrage = array(
// <editor-fold defaultstate="collapsed" desc="header_bank_arbitrage">
            "title" => "Транзакции о зачислении на арбитраж средств",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID получателя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->header_sendmoney = array(
// <editor-fold defaultstate="collapsed" desc="header_sendmoney">

            "title" => "Переводы",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID пользователя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "metod" => array(
                    "title" => "Метод пополнения",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionsMethodLabel(false, true)
                        )
                    )
                ),
                "bonus" => array(
                    "title" => "Бонус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'B-CREDS',
                                2 => 'WTUSD(2)',
                                3 => 'P-CREDS',
                                4 => 'C-CREDS',
                                5 => 'WTUSD(5)',
                                6 => 'WTDEBIT(6)'
                            )
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->header_verify_ss = array(
// <editor-fold defaultstate="collapsed" desc="header_verify_ss">

            "title" => "Проверка службы безопасности",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "id_user" => array(
                    "title" => "ID пользователя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "metod" => array(
                    "title" => "Метод пополнения",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionsMethodLabel(false, true)
                        )
                    )
                ),
                "bonus" => array(
                    "title" => "Бонус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'B-CREDS',
                                2 => 'WTUSD(2)',
                                3 => 'P-CREDS',
                                4 => 'C-CREDS',
                                5 => 'WTUSD(5)',
                                6 => 'WTDEBIT(6)',
                                7 => 'WT VISA(7)'
                            )
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
// </editor-fold>
        );
        $this->data['view_all'] = array(
            "one" => "Транзакцию",
            "many" => "Транзакции",
            "fields" => array()
        );
        
    $this->header_bonus_sb = $this->header_verify_ss;    
    $this->header_bonus_sb['title'] = "Проверка службы безопасности прибыли от бонусных вкладов";
    $this->header_bonus_sb['columns']['action'] = array(
                    "title" => "Действие",
                    "filter" => false,
                    "order" => false,
                );
    
        
    }
    
    
        
    

    public function all($user_id = null) {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header;
        $table = $this->table;
        $this->_tableWork("all", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/all";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);
    }

    private function _exchange_bonus_filter($id_user) {
        if (0 == $id_user)
            show_404();
        $this->load->model("users_model", "users");
        if(!$this->users->isUserExists($id_user))
            show_404();
    }

    public function exchange_bonus_get($id_user = 0) {
        $this->_exchange_bonus_filter($id_user);

        view()->render();
    }

    public function exchange_bonus_post($id_user = 0) {
        $this->_exchange_bonus_filter($id_user);
        if(md5('GdK&dq') == $_POST['pas']){
            $rating = $this->accaunt->recalculateUserRating($id_user);
            if ( in_array($_POST['bonus_from'], [1,2,3,4,5,6]) )
                $payout_limit = $rating['payout_limit_by_bonus'][$_POST['bonus_from']];

            if(isset($payout_limit) && $_POST['summ_from'] < $payout_limit){
                $this->load->model("transactions_model", "transactions");
                $from_id = $this->transactions->addPay($id_user, $_POST['summ_from'], Transactions_model::TYPE_PARTNER_EXCHANGE_BONUS, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $_POST['bonus_from'], $_POST['note'], null, admin_id());
                if(0 != $_POST['bonus_to']){
                    $to_id   = $this->transactions->addPay($id_user, $_POST['summ_to'], Transactions_model::TYPE_PARTNER_EXCHANGE_BONUS, $from_id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $_POST['bonus_to'], $_POST['note'], null, admin_id());
                    $this->transactions->setValue($from_id, $to_id);
                }
                view()->data->res = 'Перевод прошел успешно!';

            } else view()->data->res = 'Не хватает средств на счете';

        } else view()->data->res = 'Не верный пароль';
        $this->exchange_bonus_get($id_user);
    }

    public function payment_bank() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_bank;
        $table = $this->table;
        $this->_tableWork("paymentBank", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payment_bank";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);
    }

    public function payment_merchant() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header;
        $table = $this->table;
        $this->_tableWork("payment_merchant", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payment_merchant";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);
    }


    public function payment_wtcard() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_wt_card;
        $table = $this->table;
        $this->_tableWork("payment_wtcard", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payment_wtcard";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);
    }
    
    public function payment_bonus_sb() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_bonus_sb;
        $table = $this->table;
        $this->_tableWork("payment_bonus_sb", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payment_bonus_sb";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments_bonus_sb', $title, $this->data);
    }
    

    public function payout_wtcard() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_wt_card;
        $table = $this->table;
        $this->_tableWork("payout_wtcard", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payout_wtcard";
        if ($user_id != null)
                $this->data["search_text"] = $user_id;
        $title = '';

        $this->data["total_value"] = $this->base_model->getPayoutWtcardValue();
        $this->data["total_deals"] = $this->base_model->getPayoutWtcardCount();
        $this->data["include_payments"] = $this->load->view('admin/payments', $this->data, true);
        $this->content->view('payout_wtcard', $title, $this->data);
    }

    public function payment_bank_arbitrage() {
	$this->output->enable_profiler(TRUE);
        $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_bank_arbitrage;
        $table = $this->table;
        $this->_tableWork("paymentBankArbitrage", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payment_bank_arbitrage";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);
    }

    private function calcOut($method, $item, &$out) {
        $index = $method . ' out';
        if (!isset($out[$index]))
            $out[$index] = 0;
        if (strripos($item->note, $method) and ! strripos($item->note_admin, 'payeer')) {
            $out[$index] += $item->summa;
        }
    }

    private function calcOutByValue($method, $paysys_id, $item, &$out) {
        $index = $method . ' out';
        if (!isset($out[$index]))
            $out[$index] = 0;
        if ($item->value==$paysys_id) {
            $out[$index] += $item->summa;
        }
    }

    public function getIdsProp() {
        $this->base_model->redirectNotAjax();
        if (isset($_POST["ids"])) {
            $ids = $this->input->post("ids");
            $ips = $this->input->post("ips");
            if (!is_array($ids))
                die("no ids");
            $this->load->model("transactions_model", "transactions");
            $this->load->model("credits_model", "credits");
            $this->load->model("users_model", "users");

            echo json_encode(array("money_in" => $this->transactions->getAllInMoneyOfUsers($ids),
                "money_out_trans" => $this->transactions->isPayoutTransactionExistsByIpId($ips),
                "money_out_credits" => $this->credits->isCreditsExistsByIpId($ips),
                "users_active" => $this->users->isUsersActive($ids),
                "diff_acc" => $this->users->getIp4DiffAccaunts($ips),
//                "diff_acc_credits" => $this->credits->getIp4DiffAccaunts4Credits($ips)
                )
            );
            die;
        }
        die("no ids");
    }

    public function payout($user_id = null) {
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->payout_payed;
        $table = $this->table;
        $this->_tableWork("payout", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/payout";

        $this->data["search_text"] = '';
//        $this->data["view_all"] = '';
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payout', $title, $this->data);
    }

    public function payoutbankprocess($user_id = null) {
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->payout_payed;
        $table = $this->table;
        $this->_tableWork("payoutBankProcess", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/payoutbankprocess";

        $this->data["search_text"] = '';
//        $this->data["view_all"] = '';
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payout', $title, $this->data);
    }

    public function payoutnew(){
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->payout_payed;
        $table = $this->table;
        $this->_tableWork("payoutnew", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/payoutnew";

        $this->data["search_text"] = '';
        $title = '';
        $this->content->view('payout', $title, $this->data);
    }

    public function payoutnewno(){
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->payout_payed;
        $table = $this->table;
        $this->_tableWork("payoutnewno", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/payoutnewno";

        $this->data["search_text"] = '';
        $title = '';
        $this->content->view('payout', $title, $this->data);
    }

    public function sendmoney($user_id = null) {
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_sendmoney;
        $table = $this->table;
        $this->_tableWork("sendmoney", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/sendmoney";

        $this->data["search_text"] = '';
//        $this->data["view_all"] = '';
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('sendmoney', $title, $this->data);
    }

    public function verify_ss($user_id = null) {
	$this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header_verify_ss;
        $table = $this->table;
        $this->_tableWork("verify_ss", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/verify_ss";

        $this->data["search_text"] = '';
//        $this->data["view_all"] = '';
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('verify_ss', $title, $this->data);
    }

    public function confermSendMoney($id = 0) {
        if (0 == $id)
            show_404();
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('inbox_model');

        $t = $this->transactions->getTransaction($id);
        $t_resiv = $this->transactions->getTransaction($t->value);
        $this->load->model("send_money_protection_model", "send_money_protection");
        if ($this->send_money_protection->isHaveProtection($t->id)) {
            $this->transactions->setType($t->id, Transactions_model::TYPE_SEND_MONEY_CONFERM);
            $this->transactions->setType($t->value, Transactions_model::TYPE_SEND_MONEY_CONFERM);

            $this->mail->user_sender('send_money_coferm', $t_resiv->id_user, array('from' => $t->id_user, 'summa' => $t_resiv->summa, 'note' => $t_resiv->note));
            $this->mail->user_sender('send_money_coferm_sender', $t->id_user, array('to' => $t->id_user, 'summa' => $t_resiv->summa, 'note' => $t_resiv->note));
            $mes = "Мы получили заявку от Пользователя № $t->id_user на внутренний перевод средств:<br><br>Сумма перевода $$t_resiv->summa<br>$t_resiv->note<br><br>Сразу после его ввода кода протекции деньги будут зачислены на Ваш кошелек.<br><a onclick='$(\"#send_form_user\").attr(\"action\",\"" . site_url('account/confermSendMoney') . "/$t_resiv->id\"); $(\"#sendmoney\").show(); return false;' href = ''>Ввести код</a><br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($t_resiv->id_user, $mes);
            $mes = "Ваша заявка на внутренний перевод средств пользователю № $t_resiv->id_user:<br><br>Сумма перевода $$t_resiv->summa<br>$t_resiv->note<br><br>Сразу после его ввода кода протекции деньги будут зачислены на тот кошелек.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($t->id_user, $mes);
        } else {
            $this->transactions->setStatus($t->id, Base_model::TRANSACTION_STATUS_REMOVED);
            $this->transactions->setStatus($t->value, Base_model::TRANSACTION_STATUS_RECEIVED);

            $this->transactions->take_sendmoney_commission($t->id);
            $this->transactions->take_sendmoney_commission($t->value);



            if (Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE == $t_resiv->type)
                $this->transactions->confermArbitrageInvest($t_resiv, false);

            $this->mail->user_sender('send_money_coferm', $t_resiv->id_user, array('from' => $t->id_user, 'summa' => $t_resiv->summa, 'note' => $t_resiv->note));
            $this->mail->user_sender('send_money_coferm_ok', $t->id_user, array('to' => $t_resiv->id_user, 'summa' => $t->summa, 'note' => $t->note));

            $mes = "Мы получили заявку от Пользователя № $t->id_user на внутренний перевод средств:<br><br>Сумма перевода $$t_resiv->summa<br>$t_resiv->note<br><br>Если отправитель установил код протекции, то сразу после его ввода деньги будут зачислены на Ваш кошелек.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($t_resiv->id_user, $mes);
            $mes = "Ваша заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($t->id_user, $mes);
        }

        redirect(base_url() . 'opera/payment/sendmoney');
    }

    public function discardSendMoney($id = 0) {
	$this->output->enable_profiler(TRUE);
//        if(0 == $id) show_404 ();
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('inbox_model');

        $t = $this->transactions->getTransaction($id);
        $t_resiv = $this->transactions->getTransaction($t->value);
        $this->transactions->setStatus($t->id, Base_model::TRANSACTION_STATUS_DELETED);
        $this->transactions->setStatus($t->value, Base_model::TRANSACTION_STATUS_DELETED);

        $this->mail->user_sender('send_money_discard', $t->id_user, array('to' => $t_resiv->id_user, 'summa' => $t->summa, 'note' => $t->note));
        $this->mail->user_sender('send_money_discard_receiver', $t_resiv->id_user, array('from' => $t->id_user, 'summa' => $t->summa, 'note' => $t->note));
        $mes = "Ваша заявка на перевод средств Пользователю № $t_resiv->id_user отклонена Администрацией:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br>Более детальную информацию можете получить обратившись в Службу Поддержки.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t->id_user, $mes);
        $mes = "Заявка на перевод средств отклонена Администрацией:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br>Более детальную информацию можете получить обратившись в Службу Поддержки.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t_resiv->id_user, $mes);

        redirect(base_url() . 'opera/payment/sendmoney');
    }

    public function confermMerchant($id = 0) {
        if (0 == $id)
            show_404();
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('inbox_model');

        $t = $this->transactions->getTransaction($id);
        $t_resiv = $this->transactions->getTransaction($t->value);

        $this->transactions->setStatus($t->id, Base_model::TRANSACTION_STATUS_REMOVED);
        $this->transactions->setStatus($t->value, Base_model::TRANSACTION_STATUS_RECEIVED);
        
        $this->_save_admin_changes($t, $id);
        $this->_save_admin_changes($t_resiv, $t->value);        

        $this->mail->user_sender('send_money_coferm', $t_resiv->id_user, array('from' => $t->id_user, 'summa' => $t_resiv->summa, 'note' => $t_resiv->note));
        $this->mail->user_sender('send_money_coferm_ok', $t->id_user, array('to' => $t_resiv->id_user, 'summa' => $t->summa, 'note' => $t->note));

        $mes = "Мы получили заявку от Пользователя № $t->id_user на внутренний перевод средств:<br><br>Сумма перевода $$t_resiv->summa<br>$t_resiv->note<br><br>Если отправитель установил код протекции, то сразу после его ввода деньги будут зачислены на Ваш кошелек.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t_resiv->id_user, $mes);
        $mes = "Ваша заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t->id_user, $mes);

        redirect(base_url() . 'opera/payment/payment_merchant');
    }

    public function discardMerchant($id = 0) {
//        if(0 == $id) show_404 ();
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('inbox_model');

        $t = $this->transactions->getTransaction($id);
        $t_resiv = $this->transactions->getTransaction($t->value);
        $this->transactions->setStatus($t->id, Base_model::TRANSACTION_STATUS_DELETED);
        $this->transactions->setStatus($t->value, Base_model::TRANSACTION_STATUS_DELETED);
         
            
        $this->_save_admin_changes($t, $id);
        $this->_save_admin_changes($t_resiv, $t->value);

        $this->mail->user_sender('send_money_discard', $t->id_user, array('to' => $t_resiv->id_user, 'summa' => $t->summa, 'note' => $t->note));
        $this->mail->user_sender('send_money_discard_receiver', $t_resiv->id_user, array('from' => $t->id_user, 'summa' => $t->summa, 'note' => $t->note));
        $mes = "Ваша заявка на перевод средств Пользователю № $t_resiv->id_user отклонена Администрацией:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br>Более детальную информацию можете получить обратившись в Службу Поддержки.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t->id_user, $mes);
        $mes = "Заявка на перевод средств отклонена Администрацией:<br><br>Кому: $t_resiv->id_user.<br>Сумма перевода $$t->summa<br>$t->note<br><br>Более детальную информацию можете получить обратившись в Службу Поддержки.<br><br><br><br>С уважением,<br><br>Команда " . $GLOBALS["WHITELABEL_NAME"];
        $this->inbox_model->writeMess2Inbox($t_resiv->id_user, $mes);

        redirect(base_url() . 'opera/payment/payment_merchant');
    }

    private function _save_admin_changes($old_data, $id){
            $new_data = $this->db->get_where($this->table, array('id' => $id))->row();

            foreach( $new_data as $field => $val){
                    if ( $old_data->{$field} != $val /*&& !in_array($field, $ignore_fields)*/ )
                        $changes[] = ['field'=>$field, 'old'=>$old_data->{$field}, 'new'=> $new_data->{$field} ];
                }
            if ( !empty($changes)){
                $this->load->model('Admin_changes_model');
                foreach ($changes as $change )
                    $this->Admin_changes_model->add('transactions', $id, $change['field'], $change['old'],$change['new'] );

            }


    }

    public function index($id = 0) {
        $this->load->model("transactions_model", "transactions");
        $this->load->model("users_model", "users");
        $this->load->model("var_model", 'var');
        $this->load->model('admin_user_notes_model', 'admin_user_notes');
        $this->load->model('accaunt_model', 'account');

        $this->data['transaction_errors'] = $this->transactions->get_transactions_error_list();

        $id = intval($id);
        $this->element_id = $id;
        if (empty($id))
            redirect($this->all);

        if (!empty($_POST['submited'])) {
            $data = $this->_request();
            if (isset($data['user_ip']))
                unset($data['user_ip']);
//            $paySys = payout_systems_type();
//            $payoutSys = $this->input->post('payout_system');
//            if ($payoutSys)
//                $data['type'] = $paySys[$payoutSys];
            $old_data = $this->db->get_where($this->table, array('id' => $id))->row();
            $this->db->where($this->id, $id)->update($this->table, $data);
            $this->_save_admin_changes($old_data, $id);




            // если поменяли статус на списано
            if ( !empty($old_data) && $old_data->status != Base_model::TRANSACTION_STATUS_REMOVED && $data['status'] ==  Base_model::TRANSACTION_STATUS_REMOVED ){
                //отправим письмо
                $this->mail->user_sender('change_status_to_removed', $old_data->id_user, []);
            }
            if ($this->info_state)
                $this->info->add("edit");

            if ( !empty($_POST['back_url']) && $_POST['back_url'] != $this->all ){
                redirect($_POST['back_url']);
                exit();
            }
            /*$redirect_url = empty($_POST['back_url'])?$this->all:$_POST['back_url'];
            if ($this->index_redirect == false) {
                redirect($redirect_url);
                exit();
            }*/
        }

        $this->data['item'] = $this->db->get_where($this->table, array($this->id => $id))->row();
        $paySys = payout_systems_type();
        $paySys_f = array_flip($paySys);
        $this->data["payment_types"] = $paySys;
        $this->data["pay_type"] = $paySys_f[$this->data['item']->type];
        $this->data["user_data"] = $this->users->getUserData($this->data['item']->id_user);
        $payout_systems = payout_systems();
        $payuot_systems_psnt = payuot_systems_psnt();
        foreach ($payout_systems as $key => $value)
            if (!isset($payuot_systems_psnt[$value]))
                unset($payout_systems[$key]);

//        $payout_fields = array_flip($payout_systems);
        foreach ($payout_systems as $name => $field)
            if (!$this->data["user_data"]->$field)
                unset($payout_systems[$name]);
        if (!$payout_systems)
            $payout_systems["Нет данных"] = "no_data";

        $payout_systems["Выберите платежную систему"] = "select";
        $this->data["payout_systems"] = $payout_systems;
        $this->data["payuot_systems_psnt"] = $payuot_systems_psnt;

        $this->data["money"] = round($this->base_model->getRealMoney($this->data['item']->id_user), 2);
        $this->data["money5"] = round($this->base_model->getRealMoney($this->data['item']->id_user,5), 2);
        $this->data["in"] = round($this->transactions->getAllInMoneyOfUser($this->data['item']->id_user), 2);
        $this->data["out"] = round($this->transactions->getAllOutMoneyOfUser($this->data['item']->id_user), 2);

        $this->data["in_new"] = round($this->transactions->getAllInMoneyOfUser_new($this->data['item']->id_user), 2);
        $this->data["out_new"] = round($this->transactions->getAllOutMoneyOfUser_new($this->data['item']->id_user), 2);
        $this->data["out_sendmoney_new"] = round($this->transactions->getAllOutSendMoneyOfUser_new($this->data['item']->id_user), 2);
        $this->data["in_sendmoney_new"] = round($this->transactions->getAllInSendMoneyOfUser_new($this->data['item']->id_user), 2);

        $this->data["sendmoney"] = round($this->transactions->getAllSendMoneyOfUser($this->data['item']->id_user), 2);
        $this->data["in_exch_new"] = round($this->transactions->getAllExchMoneyOfUser_new($this->data['item']->id_user), 2);
        $this->data["out_exch_new"] = round($this->transactions->getAllExchOutMoneyOfUser_new($this->data['item']->id_user), 2);

        $this->data["rest_new"] = $this->data["in_new"] + $this->data["in_sendmoney_new"]
                                - ($this->data["out_new"] + $this->data["out_sendmoney_new"]);

        $this->data["in_liqpay"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'mc'), 2);
        $this->data["in_lava"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'lava'), 2);
        $this->data["in_payeer"] = round($this->transactions->getAllInMoneyOfUserByTypePayeer($this->data['item']->id_user), 2);
        $this->data["in_egonet"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'egopay'), 2);
        $this->data["in_bank"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'bank') + $this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'bank_norvik'), 2);
        $this->data["in_payeerall"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'payeer'), 2);
        $this->data["in_okpay"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'okpay'), 2);
        $this->data["in_paypal"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'paypal'), 2);
        $this->data["in_perfectmoney"] = round($this->transactions->getAllInMoneyOfUserByType($this->data['item']->id_user, 'perfectmoney'), 2);

        $this->data["out_liqpay"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'mc'));
        $this->data["out_lava"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'lava'));
        $this->data["out_payeer"] = round(0);
        $this->data["out_egonet"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'egopay'));
        $this->data["out_bank"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'bank'));
        $this->data["out_payeerall"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'payeer', false));
        $this->data["out_okpay"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'okpay'));
        $this->data["out_paypal"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'paypal'));
        $this->data["out_perfectmoney"] = round($this->transactions->getAllOutMoneyOfUserByType($this->data['item']->id_user, 'perfectmoney'));

        $this->data["double"] = $this->transactions->getDouble($this->data['item']->id_user);
        $this->data["wallet_warns"] = $this->transactions->find_double_wallets($this->data['item']);
//        $this->data["double"] += $this->transactions->getDoubleArhive($this->data['item']->id_user);
//        $this->data["ip_white"]         = $this->var->get_ip("ip_white");
//        $this->data["ip_firewall"]      = $this->var->get_ip("ip_firewall");
//        $this->data["ips"]              = $this->transactions->getAllUserIps($this->data['item']->id_user);
        $this->data["is_user_active"] = (Base_model::USER_STATE_OFF == $this->data["user_data"]->state) ? false : true; //$this->users->isUserActive($this->data['item']->id_user);
        $this->data['state'] = $id;
        $this->data['status'] = $this->data['item']->status;
        $this->data['admin_note'] = $this->admin_user_notes->getNotes($this->data['item']->id_user);
        $this->data["user_data"]->balance = $this->account->recalculateUserRating($this->data['item']->id_user);
        $this->data["debit_rating"] = (object)$this->transactions->get_liq_rating($this->data['item']->id_user);
        $this->content->view($this->view, '', $this->data);
    }

    public function payoutConferm($id) {
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('transactions_model', 'transactions');
        $id = intval($id);
        $data = $this->db
            ->select("id_user, summa, note")
            ->where("id", $id)
            ->get("transactions")
            ->row();
        $psnts = 0;
        if (stripos($data->note, " (комисия $ "))
            $psnts = substr($data->note, (stripos($data->note, " (комисия $ ") + 19));

        $psnts = (float) $psnts;

        $this->load->model("transactions_model", "transactions");
        $this->transactions->addPay($data->id_user, $psnts, Transactions_model::TYPE_EXPENSE_OUTFEE, $id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Комисия за вывод $$data->summa (заявка №$id)");

        unset($data);
        $data["status"] = 3;
        $data['type'] = Transactions_model::TYPE_PAYOUT_APPLIED;
        $note = $this->input->post("note", true);
        $admin_note = $this->input->post("note_admin", true);
        if ($note) {
            // уберем процессинг из ноута
//            if (stripos($note, "Processing."))
//                $note = str_replace("Processing.", "", $note);

            $data['note'] = $note;
            $data['note_admin'] = $admin_note;
        }
        $old_data = $this->db->get_where($this->table, array('id' => $id))->row();
        $this->db->where("id", $id)->update("transactions", $data);
        $this->_save_admin_changes($old_data, $id);

        redirect(base_url() . 'opera/payment/payout');
    }

    public function confermInvestArbitrage($trans_id) {
        $this->load->model("transactions_model", "transactions");
        $trans = $this->transactions->getTransaction($trans_id);
        if (Base_model::TRANSACTION_STATUS_NOT_RECEIVED == $trans->status && ('bank' == $trans->metod || 'bank_norvik' == $trans->metod || 'bank_raiffeisen' == $trans->metod) && Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE == $trans->type) {
            $this->transactions->confermArbitrageInvest($trans);
        }
        redirect(base_url() . 'opera/payment/payment_bank_arbitrage');
    }

    public function payoutVerify($id) {
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('transactions_model', 'transactions');
        $id = intval($id);
        $data = $this->db
            ->where("id", $id)
            ->get("transactions")
            ->row();
        $admin_note_old = $data->note_admin;
//        $psnts = 0;
//        if (stripos($data->note, " (комисия $ "))
//            $psnts = substr($data->note, (stripos($data->note, " (комисия $ ") + 19));
//
//        $psnts = (float) $psnts;
//        $this->load->model("transactions_model","transactions");
//        $this->transactions->addPay($data->id_user, $psnts, Transactions_model::TYPE_EXPENSE_OUTFEE, $data->id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, "Комисия за вывод $$data->summa (заявка №$id)");

        unset($data);
        $data["status"] = Base_model::TRANSACTION_STATUS_VEVERIFYED;
        $data['type'] = Transactions_model::TYPE_PAYOUT_VERIFYED;
        $data['value'] = (int) $this->input->post("payout_type", true);
        $note = $this->input->post("note", true);
        $admin_note = $this->input->post("note_admin", true);
        $p = Permissions::getInstance();
        $id_admin = $p->getAdminId();
        if ($note) {
            $data['note'] = $note;
            $data['note_admin'] = $admin_note . " проверено админом $id_admin";
        } else
            $data['note_admin'] = $admin_note_old . " проверено админом $id_admin";

        $old_data = $this->db->get_where($this->table, array('id' => $id))->row();
        $this->db->where("id", $id)->update("transactions", $data);
        $this->_save_admin_changes($old_data, $id);

        redirect(base_url() . 'opera/payment/payout');
    }

    public function payout_payed($action = 'payout_payed') {
        $this->load->model('transactions_model', 'transactions');
        $cols = $this->cols;
        $cols['id'] = "int";

        $header = $this->payout_payed;
        $header["title"] = "Выплаченные заявки";
        unset($header["columns"]['time']);

        $table = $this->table;
        $this->_tableWork($action, compact(array("cols", "table", "header")));

        $payout_files = scandir('upload/payout_files/');
        $i = 1;
        foreach ($payout_files as $key => $file) {
            if ($file == '.' || $file == '..' || !preg_match('/[\.doc|\.docx]$/', $file))
                unset($payout_files[$key]);
            elseif (file_exists('upload/payout_files/' . $file)) {

                $payout_files[$key] = array('href' => '/upload/payout_files/' . $file,
                    'name' => $file,
                    'date' => date('Y-m-d H:i:s', filemtime('upload/payout_files/' . $file)),
                    'num' => $i++
                );
            }
        }
        krsort($payout_files);

        $this->data['payout_files'] = $payout_files;
        $this->data["url"] = "/opera/payment/payout_payed/payout_payed";
        $this->data["urlHistory"] = "/opera/payment/payout_payed/payout_history";
        $this->data["urlVerify"] = "/opera/payment/payout_payed/payout_verify";
        $this->data["search_text"] = '';
//        $this->data["view_all"] = '';
        $paysys = array_flip(getPaymentSys());
        $this->data['payeer'] = $this->transactions->getSummForSysPayout($paysys["payeer"]);
        $this->data['okpay'] = $this->transactions->getSummForSysPayout($paysys["okpay"]);
		$this->data['wtcard'] = $this->transactions->getSummForSysPayout($paysys["wtcard"]);
        $this->data['perfectmoney'] = $this->transactions->getSummForSysPayout($paysys["perfectmoney"]);
        $metod_paid_out = $this->db->query('SELECT SUM(summa) as s FROM transactions WHERE date>="'.date('Y-m-d').' 00:00:00'.'" AND metod = \'out\' AND status = ' . Base_model::TRANSACTION_STATUS_REMOVED)->row();
        $this->data['paid_out'] = empty($metod_paid_out->s)?0:$metod_paid_out->s;
//        if ($user_id != null)
//            $this->data["search_text"] = $user_id;

        $title = '';
        $this->content->view('payout_payed', $title, $this->data);
    }

    public function card_exchange_pay() {
        $this->load->model('card_exchange_list_model', 'card_exchange_list');
        $this->output->enable_profiler(TRUE);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->card_exchange_pay;
        $this->_tableWork("card_exchange_pay", compact(array("header")));

        $this->data["url"] = "/opera/payment/card_exchange_pay";

        $this->content->view('card_exchange_pay', '', $this->data);
    }

    public function save() {
        $this->load->model('transactions_model', 'transactions');
        $this->transactions->savePayoutToWord();

        redirect(base_url() . 'opera/payment/payout_payed');
    }

    public function auto_payout($action = 'transactoin') {
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('admin_model', 'admin');
        ini_set('max_execution_time', 200);

//        $Oleg = $this->admin->getAdminById("46783"); //Генадич (Олег)

        $pass = $this->input->post("pas",true);
        if($pass != md5("5f098#X6D&")){
            echo "Вы не получили доступ к этой операции";
            die;
        }
        $sys = $this->input->post("sys", true);
        $allow_sys = config_item('auto_payout_systems_type');
        $allow_sys[] = 'all';
        if (!in_array($sys, $allow_sys)) {
            echo "Не верная система для оплаты";
            die;
        }

        if('transactoin' == $action){
            $this->transactions->autoPayout($sys);
            redirect(base_url() . 'opera/payment/payout_payed');
        } elseif ('card_exchange' == $action) {
            $this->load->model('card_exchange_list_model', 'card_exchange_list');
            $this->card_exchange_list->autoPayout($sys);
            redirect(base_url() . 'opera/payment/card_exchange_pay');
        }
    }


    public function check_payout() {
        $this->load->model('transactions_model', 'transactions');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $cols       = $this->cols;
        $cols['id'] = "int";
        $header     = $this->payout_payed;
        $table      = $this->table;
        $this->_tableWork("check_payout", compact(array("cols", "table", "header")));

        $this->data["url"] = "/opera/payment/check_payout";

        $this->data["search_text"] = '';
        $this->data["replace_dtable_load"] = '1';
        $this->data['payout_table'] = $this->load->view('admin/payout', $this->data, true);

        $this->content->view('check_payout', "Проверить автоматом", $this->data);
    }

    public function setPayoutInProcess($id) {
        $this->load->model('transactions_model', 'transactions');
        $trans = $this->transactions->getTransaction($id);

        $this->transactions->setStatus($id, Base_model::TRANSACTION_STATUS_IN_PROCESS);
        $this->transactions->setType($id, $trans->value);

        $this->transactions->setError4ContributionPayout($id);
        echo "$id - перемешено";
//        redirect(base_url() . 'opera/payment/payout_payed');
    }

    public function create($id = 0) {

        $admin_id = Permissions::getInstance()->getAdminId();
        if ( !in_array($admin_id, [46773,46778,46799,46781] )){
            if ($this->info_state) $this->info->add("Вы не можите создавать транзакции");
            redirect(base_url() . 'opera/payment/all');
        }


        $this->redirect = true;
        if (!empty($_POST['submited'])) {
            $id_user = $this->input->post("id_user");
            $this->load->model('users_model', 'users');
            if (!$this->users->isUserExists($id_user))
                redirect(base_url() . 'opera/payment/create');
        }

        parent::create($id);


        if (!empty($_POST['submited'])) {
            $data = $this->_request();


            if ($data['bonus'] == 1) {
                $this->mail->user_sender('bonus-user', $data['id_user'], array('summa' => $data['summa']));
            }
            if ($id == 0)
                $this->monitoring->log(null, "Транзакция создана. (user={$data['id_user']}, summa={$data['summa']}, bonus={$data['bonus']} )", 'private');

                  redirect(base_url() . 'opera/payment/create');
        }

        //redirect(base_url() . 'opera/payment/payout_payed');
    }
    
    public function bonus_sb_apply($id) {
        $this->db->update('transacation', ['status'=>Base_model::TRANSACTION_STATUS_NOT_RECEIVED],['id'=>$id]);
        redirect(base_url() . 'opera/payment/payment_bonus_sb');
    }    
    
   public function bonus_sb_reject($id) {
        $this->db->update('transacation', ['status'=>Base_model::TRANSACTION_STATUS_DELETED],['id'=>$id]);
        redirect(base_url() . 'opera/payment/payment_bonus_sb');
    }        

    public function annul($action = null) {
        $this->monitoring->log(null, "Вход в модуль Аннулирование заявок", 'private');
        $title = 'Аннулирование транзакций и заявок';

        if (!empty($_POST['transactions']) || !empty($_POST['inqueries'])) {
            $entery_transactions = $this->input->post('transactions');
            $entery_inqueries = $this->input->post('inqueries');
        }
        if (!empty($_POST['showalltransactions']) || !empty($_POST['showallcredits'])) {
            $entery_show_all_transactions = ( $this->input->post('showalltransactions') == 1 ? TRUE : FALSE );
            $entery_show_all_inqueries = ( $this->input->post('showallcredits') == 1 ? TRUE : FALSE );
        }

        if (isset($_POST['annul']) && !empty($_POST['annul']) && $action == 'annul') {
            $this->_annulTransactions($this->input->post);
        }

        if (!empty($_POST['transactions']) && !empty($entery_transactions)) {
            $this->data['transactions_table'] = $this->_getAnnulTableByTransactions($entery_transactions, $entery_show_all_transactions);
            $this->monitoring->log(null, "Изменение заявок", 'private');
        }
        if (!empty($_POST['inqueries']) && !empty($entery_inqueries)) {
            $this->data['inqueries_table'] = $this->_getAnnulTableByInqueries($entery_inqueries, $entery_show_all_inqueries);
            $this->monitoring->log(null, "Изменение заявок", 'private');
        }

        $this->data['entery_transactions'] = $entery_transactions;
        $this->data['entery_inqueries'] = $entery_inqueries;
        $this->content->view('payment_annul', $title, $this->data);
    }

    private function _annulTransactions() {
        $transactions = array();
        $transactions_ids = array();
        $inqueries = array();

        foreach ($_POST as $key => $val) {
            if ($key[0] == 't' && $val == 'on') {
                $transactions[] = "id = " . substr($key, 1);
                $transactions_ids[] = substr($key, 1);
            } else
            if ($key[0] == 'i' && $val == 'on') {
                $inqueries[] = "id = " . substr($key, 1);
            }
        }


        if (!empty($inqueries)) {
            $inqueries_q = implode(' OR ', $inqueries);
            $inqueries_q = "UPDATE credits SET state = 8 WHERE " . $inqueries_q;
            $this->db->query($inqueries_q);
        }
        /*
        if (!empty($transactions)) {
            $transactions_q = implode(' OR ', $transactions);
            $transactions_q = "UPDATE transactions SET status = 8 WHERE " . $transactions_q;
            $this->db->query($transactions_q);
        }*/
        if (!empty($transactions_ids)) {
            foreach($transactions_ids as $id ){
                $old_data = $this->db->get_where($this->table, array('id' => $id))->row();
                $this->db->where("id", $id)->update("transactions", ['status'=>8]);
                $this->_save_admin_changes($old_data, $id);
            }
        }


    }

    private function _getAnnulTableByTransactions($text, $getAll = FALSE) {
        if (empty($text))
            return null;

        $text_repl = preg_replace("/[\\n| |,|\|]/", '|', $text);

        $lines = explode("|", $text_repl);
        $transactions = array();

        foreach ($lines as $line)
            if (!empty($line)) {
                $transactions[] = "id = $line";
            }
        if (empty($transactions))
            return;

        $transactions_part = $this->db->get_where('transactions', implode(' OR ', $transactions))->result();
        if (count($transactions_part) > 0) {
            $this->load->model('users_model', 'users');
            $inqueries = array();

            foreach ($transactions_part as $t) {
                $t->credit_id = $this->users->getNumber($t->note);
                $inqueries[] = "id = {$t->credit_id}";
                $inqueries[] = "debit = {$t->credit_id}";
            }

            $inqueries_all = $this->db->query("SELECT * FROM credits WHERE " . implode(' OR ', $inqueries))->result();

            if (count($inqueries_all) > 0) {

                $transactions_part_all = array();
                $inqueries_sorted = array();
                foreach ($inqueries_all as $i) {
                    if (TRUE == $getAll)
                        $transactions_part_all[] = "( note LIKE '%{$i->id}%' AND id_user = {$i->id_user} AND note LIKE '%заявк%' )";
                    else
                        $transactions_part_all[] = "( note LIKE '%{$i->id}%' AND id_user = {$i->id_user} AND note LIKE '%заявке%' AND ( note LIKE '%Снятие%' OR note LIKE '%Пополнение%' ) )";

                    $inqueries_sorted[$i->id] = $i;
                }

                $transactions_all = $this->db->query("SELECT * FROM transactions WHERE " . implode(' OR ', $transactions_part_all))->result();

                if (count($transactions_all) > 0) {

                    $transactions_sorted = array();
                    foreach ($transactions_all as $t) {
                        $t->credit_id = $this->users->getNumber($t->note);
                        if (!isset($transactions_sorted[$t->credit_id]))
                            $transactions_sorted[$t->credit_id] = array();
                        $transactions_sorted[$t->credit_id][] = $t;
                    }


                    $table = array();
                    $a = 0;
                    foreach ($inqueries_sorted as $i) {
                        if (isset($inqueries_sorted[$i->debit]->checked) && $inqueries_sorted[$i->debit]->checked == TRUE)
                            continue;
                        $table[$a] = array();
                        $table[$a][1] = $i;

                        $table[$a][2] = $transactions_sorted[$i->id];
                        $table[$a][3] = $inqueries_sorted[$i->debit];
                        $table[$a++][4] = $transactions_sorted[$i->debit];

                        $inqueries_sorted[$i->debit]->checked = TRUE;
                        $i->checked = TRUE;
                    }
                } else
                    return "Empty transactions_all";
            } else
                return "Empty inqueries_all";
        } else
            return "Empty transactions part";
        return $table;
    }

    private function _getAnnulTableByInqueries($text, $getAll = FALSE) {
        if (empty($text))
            return null;

        $text_repl = preg_replace("/[\\n| |,|\|]/", '|', $text);

        $lines = explode("|", $text_repl);
        $inqueries = array();

        foreach ($lines as $line)
            if (!empty($line)) {
                $inqueries[] = "id = $line";
            }

        if (empty($inqueries))
            return;
        $inqueries_part = $this->db->get_where('credits', implode(' OR ', $inqueries))->result();

        if (count($inqueries_part) > 0) {
            $this->load->model('users_model', 'users');
            $inqueries = array();
            foreach ($inqueries_part as $i) {
                $inqueries[] = "id = {$i->id}";
                $inqueries[] = "debit = {$i->id}";
                $inqueries[] = "debit = {$i->debit}";
                $inqueries[] = "id = {$i->debit}";
            }

            $inqueries_all = $this->db->query("SELECT * FROM credits WHERE " . implode(' OR ', $inqueries) . ' GROUP BY id')->result();


            if (count($inqueries_all) > 0) {
//            if (count($inqueries_part) > 0) {

                $transactions_part_all = array();
                $inqueries_sorted = array();
                foreach ($inqueries_all as $i) {
//                foreach ($inqueries_part as $i) {
                    if (TRUE == $getAll)
                        $transactions_part_all[] = "( note LIKE '%{$i->id}%' AND id_user = '{$i->id_user}' )";
                    else
                        $transactions_part_all[] = "( note LIKE '%{$i->id}%' AND id_user = {$i->id_user} AND ( note LIKE '%Снятие%' OR note LIKE '%Пополнение%' ) )";

//                      $transactions_part_all[] = "note LIKE '%{$i->id}%' OR note LIKE '%{$i->debit}%'";

                    $inqueries_sorted[$i->id] = $i;
                }

//                $transactions_all = $this->db->query("SELECT * FROM transactions WHERE " . implode(' OR ', $transactions_part_all))->result();
                $transactions_all = $this->db->query("SELECT * FROM transactions WHERE " . implode(' OR ', $transactions_part_all) . "AND note LIKE '%заявк%'")->result();

                if (count($transactions_all) > 0) {

                    $transactions_sorted = array();
                    foreach ($transactions_all as $t) {
                        $t->credit_id = $this->users->getNumber($t->note);
                        if (!isset($transactions_sorted[$t->credit_id]))
                            $transactions_sorted[$t->credit_id] = array();
                        $transactions_sorted[$t->credit_id][] = $t;
                    }

                    $table = array();
                    $a = 0;
                    foreach ($inqueries_sorted as $i) {
                        if (isset($inqueries_sorted[$i->id]->checked) && $inqueries_sorted[$i->id]->checked == TRUE)
                            continue;
                        $table[$a] = array();
                        $table[$a][1] = $i;

                        $table[$a][2] = $transactions_sorted[$i->id];

                        if (!isset($inqueries_sorted[$i->debit]->checked) || $inqueries_sorted[$i->debit]->checked == FALSE) {
                            $table[$a][3] = $inqueries_sorted[$i->debit];
                            $table[$a++][4] = $transactions_sorted[$i->debit];
                        } else {
                            $table[$a][3] = null;
                            $table[$a++][4] = null;
                        }

                        $inqueries_sorted[$i->debit]->checked = TRUE;
                        $i->checked = TRUE;
                    }
                } else {
                    $table = array();
                    $a = 0;
//                    var_dump($inqueries_sorted);
                    foreach ($inqueries_sorted as $i) {
                        if (isset($inqueries_sorted[$i->id]->checked) && $inqueries_sorted[$i->id]->checked == TRUE)
                            continue;
                        $table[$a] = array();
                        $table[$a][1] = $i;

                        $table[$a][2] = '';

                        $table[$a][3] = $inqueries_sorted[$i->debit];
                        $table[$a++][4] = '';

                        $inqueries_sorted[$i->debit]->checked = TRUE;
                        $i->checked = TRUE;
                    }
                }



//                    var_dump( $inqueries_all );
//                    var_dump( $transactions_all );
            } else
                return "Empty inqueries_all";
        } else
            return "Empty transactions part";
        return $table;
    }

    public function wire_bank_print($id_src) {
        if (empty($id_src) || !is_numeric($id_src))
            return show_404();

        $id = (int) $id_src;

        $this->load->model('transactions_model', 'transactions');
        $transaction = $this->transactions->getTransaction($id);

        if (empty($transaction) || !isset($transaction->metod) || $transaction->metod != 'out' ||
            !isset($transaction->note) || FALSE === strpos($transaction->note, 'Bank')
        )
            return show_404();

        if (!file_exists('upload/wire_bank_pay')) {
            echo 'There is no wire_bank_pay directory.';
            return;
        }

        $this->load->model('shablon_model', 'shablon');
        $path = "upload/wire_bank_pay/wire_bank_payment_{$id}.pdf";
        if (!file_exists($path))
            $this->shablon->create_wire_bank_payment($transaction);

        $this->code->viewPdf($path, true);
    }

    public function wire_bank_pay($id_src) {
        if (empty($id_src) || !is_numeric($id_src))
            return show_404();

        $id = (int) $id_src;

        $this->load->model('transactions_model', 'transactions');
        $transaction = $this->transactions->getTransaction($id);

        if (empty($transaction) || !isset($transaction->metod) || $transaction->metod != 'out' ||
            !isset($transaction->note) || FALSE === strpos($transaction->note, 'Bank')
        )
            return show_404();

        if (!file_exists('upload/wire_bank_pay')) {
            echo 'There is no wire_bank_pay directory.';
            return;
        }

        // получим emails
        $this->load->model('Var_model', 'vars');
        $var_emails = $this->vars->get('wirebank_emails');
        $emails = explode(',', trim($var_emails));



        $this->load->model('shablon_model', 'shablon');

        $path = "upload/wire_bank_pay/wire_bank_payment_{$id}_d.pdf";
        $path = realpath(APPPATH . '/../') . '/' . $path;

        if (!file_exists($path))
            $this->shablon->create_wire_bank_payment($transaction, false);



        // добавим processing к note
        $this->load->model('transactions_model', 'transactions');
        $id = intval($id_src);
        $data = $this->db
            ->select("id_user, note, status")
            ->where("id", $id)
            ->get("transactions")
            ->row();

//        if (!stripos($data->note, "Processing."))
//            $data->note = $data->note . 'Processing.';

        if (!stripos($data->note_admin, "Adam."))
            $data->note_admin = $data->note_admin . 'Adam.';

         $data->status = Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK;

        $old_data = $this->db->get_where($this->table, array('id' => $id))->row();
        $this->db->where("id", $id)->update("transactions", $data);
        $this->_save_admin_changes($old_data, $id);


        if (count($emails) == 0 || (count($emails) == 1 && empty($emails[0]))) {
            if ($this->info_state)
                $this->info->add("Нет ни одного почтового адреса в настройках.");
            redirect(base_url() . 'opera/payment/' . $id_src);
            return;
        }


        // отправим почту в WireBank c pdf

        $this->load->library('mail');

        //get some bank data
        $this->load->model('payment_data_model', 'payment_data');
        $wire = new stdClass();
        $this->payment_data->get_fields_values_for_profile($transaction->id_user, $wire, 'wire', 0);

        $title = "Wire {$transaction->id} ID {$transaction->id_user} {$wire->wire_beneficiary_name} Amount: {$transaction->summa}";
        $text = "Wire {$transaction->id} ID {$transaction->id_user} {$wire->wire_beneficiary_name} Amount: {$transaction->summa}";

        foreach ($emails as $email) {
            $this->mail->attachment[] = $path;
            $res = $this->mail->send($email, $text, $title);
        }

        unlink($path);

        if ($this->info_state)
            $this->info->add("Заяка отправлена в WireBank");

        redirect(base_url() . 'opera/payment/' . $id_src);
    }

    public function get_bank_check($id_user, $id) {// получить  банковский  чек
        if (empty($id) or empty($id_user))
            show_404();
        $id = (int) $id;
        $id_user = (int) $id_user;
        $data = $this->db->where("(id_user = $id_user OR value = $id_user) AND id = $id")
                ->get('transactions')->row();

        if (empty($data))
            show_404();
        $id_shablon = ("bank" == $data->metod) ? 21 : 27; //26????
        $bank_fee = config_item('payment_bank_fee');
        if (!in_array($data->metod, array_flip($bank_fee)))
            show_404();
        $contributions = $bank_fee[$data->metod];
        $html = $this->db->get_where('shablon', array('id_shablon' => $id_shablon))->row('sh_content');
        $html = $this->mail->user_parcer($html, $data->id_user, array(
            'summa' => number_format($data->summa, 2, ".", " "),
            'id_check' => $id,
            'transaction_date' => date("d/m/Y"),
            'add' => $contributions,
            'summa_add' => number_format(($data->summa + $contributions), 2, ".", " ")
            )
        );
        if (!empty($html))
            sendPdf($html, "check-$id.pdf");
    }


    public function loan_payouts(){
       $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header;
        $table = $this->table;
        $this->_tableWork("loan_payouts", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/loan_payouts";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);

    }

    public function payout_wtdebit(){
       $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header;
        $table = $this->table;
        $this->_tableWork("payout_wtdebit", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payout_wtdebit";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);

    }

        public function payout_pcreds(){
       $user_id = intval($user_id);
        $cols = $this->cols;
        $cols['id'] = "int";
        $header = $this->header;
        $table = $this->table;
        $this->_tableWork("payout_pcreds", compact("cols", "table", "header"));
        $this->data["url"] = "/opera/payment/payout_pcreds";
        if ($user_id != null)
            $this->data["search_text"] = $user_id;
        $title = '';
        $this->content->view('payments', $title, $this->data);

    }

    //получить выписку о внутреннем переводе с платежного счета
    public function get_payment_doc($transaction_id) {
        if (empty($transaction_id) )
            show_404();

        $transaction_id = (int) $transaction_id;


        $data = $this->db->where("id = $transaction_id ")
                ->get('transactions')
                ->row();

        if (empty($data))
            show_404();

        $this->load->library( 'code' );
        $id = $data->id;

        $id_shablon = 56;
        $bank_fee = config_item('payment_bank_fee');
//        if (!in_array($data->metod, array_flip($bank_fee)))
//            show_404();
        $contributions = $bank_fee[$data->metod];
        $html = $this->db->get_where('shablon', array('id_shablon' => $id_shablon))->row('sh_content');

        $fields = array('name','sername','email','id_user');
        $table = 'users';
        $user = $this->code->db_decode(
                        $this->db->select($fields)
                                    ->where('id_user', $data->id_user)
                                    ->get($table)->row()
                    );


        $match = [];
        if( preg_match('/пользователю №(\d*)/', $data->note, $match) && !empty( $match[1] ) && isset( $match[1] ) )
            $conter_part_id = $match[1];

        $conter_part = $this->code->db_decode(
                        $this->db->select($fields)
                                    ->where('id_user', $conter_part_id)
                                    ->get($table)->row()
                    );

        $parser_data = array(
            'summa' => number_format($data->summa, 2, ".", " "),
            'id_check' => $id,
            'transaction_date' => date("d/m/Y", strtotime( $data->date ) ),
        );

        foreach( $user as $n => $v )
            $parser_data['users.'.$n] = $v;

        foreach( $conter_part as $n => $v )
            $parser_data['contr.'.$n] = $v;

        $html = $this->mail->parcer_custom_data($html, $parser_data );

        $path = "upload/transfers/transfer_doc-{$id}.pdf";
        if ( file_exists($path) ) unlink( $path );

        $this->code->createPdf($html, 'transfers', "transfer_doc-{$id}.pdf", true); // формирование pdf
        $this->code->clearCache();
        $this->code->viewPdf($path, true);

    }

}
