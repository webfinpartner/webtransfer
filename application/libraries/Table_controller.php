<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class Table_controller extends Permissions_controller
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function _tableWork($action,$vars) {
        extract($vars);

        if (isset($_POST["def"])) {
            switch ($action){
                case "all" : echo $this->_tableHeader4Array($header); break;
                case "paymentBank" : echo $this->_tableHeader4Array($header); break;
                case "payment_wtcard" : echo $this->_tableHeader4Array($header); break;
                case "payment_bonus_sb" : echo $this->_tableHeader4Array($header); break;
                case "payout_wtcard" : echo $this->_tableHeader4Array($header); break;
                case "paymentBankArbitrage" : echo $this->_tableHeader4Array($header); break;
                case "payout" : echo $this->_tableHeader4Array($header); break;
                case "payoutBankProcess" : echo $this->_tableHeader4Array($header); break;
                case "payoutnew" : echo $this->_tableHeader4Array($header); break;
                case "payoutnewno" : echo $this->_tableHeader4Array($header); break;
                case "card_exchange_pay" : echo $this->_tableHeader4Array($header); break;
                case "check_payout" : echo $this->_tableHeader4Array($header); break;
                case "sendmoney" : echo $this->_tableHeader4Array($header); break;
                case "verify_ss" : echo $this->_tableHeader4Array($header); break;
                case "payout_payed" : echo $this->_tableHeader4Array($header); break;
                case "payout_history" : echo $this->_tableHeader4Array($header); break;
                case "payout_verify" : echo $this->_tableHeader4Array($header); break;
                case "support" : echo $this->_tableHeader4Array($header); break;
                case "support_ask" : echo $this->_tableHeader4Array($header); break;
                case "bot_chat" : echo $this->_tableHeader4Array($header); break;
                case "user_all" : echo $this->_tebleHeaderUserAll($title, array("date" => "Дата посещения")); break;
                case "vip_user" : echo $this->_tebleHeaderUserAll($title, array("date" => "Дата посещения")); break;
                case "user_doc_chenge" : echo $this->_tebleHeaderUserAll($title, array("date" => "Дата посещения")); break;
                case "get_credits" : echo $this->_tableHeader4Array($header); break;
                case "get_invests" : echo $this->_tableHeader4Array($header); break;
                case "get_transactions" : echo $this->_tableHeader4Array($header); break;
                case "get_partners" : echo $this->_tableHeader4Array($header); break;
                case "get_credits_archive" : echo $this->_tableHeader4Array($header); break;
                case "get_invests_archive" : echo $this->_tableHeader4Array($header); break;
                case "get_transactions_archive" : echo $this->_tableHeader4Array($header); break;
                case "currency_exchange_block_users_all" : echo $this->_tebleHeaderUserAll($title, array("date" => "Дата посещения")); break;
                case "payment_merchant" : echo $this->_tableHeader4Array($header); break;
                case "loan_payouts" : echo $this->_tableHeader4Array($header); break;
                case "payout_wtdebit" : echo $this->_tableHeader4Array($header); break;
                case "payout_pcreds" : echo $this->_tableHeader4Array($header); break;
                
                



                default : break;
            }
            die;
        }
        if(isset($_POST["offset"])){
            switch ($action){
                case "all" : echo $this->base_model->getAll($cols, $table); break;
                case "paymentBank" : echo $this->base_model->paymentBank($cols, $table); break;
                case "payment_wtcard" : echo $this->base_model->paymentWtcard($cols, $table); break;
                case "payment_bonus_sb" : echo $this->base_model->paymentBonusSB($cols, $table); break;
                
                case "payout_wtcard" : echo $this->base_model->payoutWtcard($cols, $table); break;
                case "paymentBankArbitrage" : echo $this->base_model->paymentBankArbitrage($cols, $table); break;
                case "payout" : echo $this->base_model->getPayout($cols, $table); break;
                case "payoutBankProcess" : echo $this->base_model->getPayoutBankProcess($cols, $table); break;
                case "payoutnew" : echo $this->base_model->getPayoutNew($cols, $table); break;
                case "payoutnewno" : echo $this->base_model->getPayoutNewNo($cols, $table); break;

                case "loan_payouts" : echo $this->base_model->getLoanPayouts($cols, $table); break;
                case "payout_wtdebit" : echo $this->base_model->getWTdebitPayouts($cols, $table); break;
                case "payout_pcreds" : echo $this->base_model->getPCREDSPayouts($cols, $table); break;
                
                case "check_payout" :  echo $this->transactions->getCheckPayout($cols, $table); break;
                case "card_exchange_pay" :  echo $this->card_exchange_list->getRequests(); break;
                case "sendmoney" :
                    $this->load->model('transactions_model','transactions');
                    echo $this->transactions->getSendMoney($cols, $table, 3);
                    break;
                case "verify_ss" :
                    $this->load->model('transactions_model','transactions');
                    echo $this->transactions->getVerifySS($cols, $table, 3);
                    break;
                case "payout_payed" :
                    $this->load->model('transactions_model','transactions');
                    echo $this->transactions->getPayout($cols, $table, 3);
                    break;
                case "payout_history" :
                    $this->load->model('transactions_model','transactions');
                    echo $this->transactions->getPayout($cols, $table, Base_model::TRANSACTION_STATUS_REMOVED, Transactions_model::TYPE_PAYOUT_PAYED);
                    break;
                case "payout_verify" :
                    $this->load->model('transactions_model','transactions');
                    echo $this->transactions->getPayout($cols, $table, Base_model::TRANSACTION_STATUS_VEVERIFYED, Transactions_model::TYPE_PAYOUT_VERIFYED);
                    break;
                case "support" :
                    $this->load->model('volunteer_topic_model');
                    echo $this->volunteer_topic_model->getAllTopics($cols, $table);
                    break;
                case "support_ask" :
                    $this->load->model('volunteer_topic_model');
                    echo $this->volunteer_topic_model->getAllTopicsWithAsk($cols, $table);
                    break;
                case "bot_chat" :
                    $this->load->model('cometchat_model','cometchat');
                    echo $this->cometchat->getNewMessage4Bots($cols);
                    break;
                case "user_all" : echo $this->user->getAll($type); break;
                case "vip_user" : echo $this->user->getVipAll($type); break;
                case "user_doc_chenge" : echo $this->user->getDoc(); break;
                case "get_credits" : echo $this->user->get_credits(); break;
                case "get_invests" : echo $this->user->get_invests(); break;
                case "get_transactions" : echo $this->user->get_transactions(); break;
                case "get_partners" : echo $this->user->get_partners(); break;
                case "get_credits_archive" : echo $this->user->get_credits_archive(); break;
                case "get_invests_archive" : echo $this->user->get_invests_archive(); break;
                case "get_transactions_archive" : echo $this->user->get_transactions_archive(); break;

                case "currency_exchange_block_users_all" : echo $this->user->get_currency_exchange_block_users_all($type); break;
                case "payment_merchant" : echo $this->base_model->paymentMerchant($cols, $table); break;


                default : break;
            }
            die;
        }
    }

    protected function _tableHeader4Array(array $params) {
        return json_encode($params);
    }


    protected function _tebleHeaderUserAll($title = false, $titleCol = array()){
        return json_encode(
            array(
                "title" => $title,
                "columns" => array(
                    "id_user" => array(
                        "title" =>  "ID",
                        "filter"=> true,
                        "order" =>  true,
    //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                    ),
                    "fio" => array(
                        "title" =>  "ФИО",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "phone"=> array(
                        "title" =>  "Телефон",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "email"=> array(
                        "title" =>  "Email",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "reg_date"=> array(
                        "title" =>  "Регистрация",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "ip_reg"=> array(
                        "title" =>  "IP",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "parent"=> array(
                        "title" =>  "Parent",
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "date"=> array(
                        "title" =>  $titleCol["date"],
                        "filter"=> true,
                        "order" =>  true,
                    ),
                    "state" => array(
                        "title" =>  "Статус",                                    // table title, if false no column title displayed,
                                                                                 // its work if all column title is false
                        "filter"=> true,                                        // column filter, placeholder: input field placeholder
                        "order" =>  true,
                        "formatter" => array(
                            "widget" => 'partial',
                            "widget_options" => array(
                                "template" => "cell_status.html"
                            )
                        )
        //                >},
                    ),
                ),
            )
        );
    }
}