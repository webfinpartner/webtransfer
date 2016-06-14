<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'controllers/user/Accaunt.php';

class Exchange extends Accaunt {
    protected $me;
    protected $header;

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model","users");
        $this->load->model("credits_model","credits");
        $this->me = $this->users->getCurrUserId();
        $this->header = array(
                    "search_name" => _e("Поиск"),
                    "on_page" => _e("Показывать на стр."),
                    "title" => _e('exchange/data1'),
                    "columns" => array(
                        "id" => array(
                           "title" =>  _e('exchange/data2'),
                           "filter"=> true,
                           "order" =>  true,
                       ),
                        "id_user" => array(
                            "title" =>  _e('exchange/data3'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "bonus" => array(
                            "title" =>  _e('Счет'),
                            "filter"=> 'select',
                            "order" =>  true,
                            "formatter" => array(
                                "widget" => 'list',
                                "widget_options" => array(
                                    "list" => [
                                        0 => '',
                                        1 => "<img alt='Webtransfer DEBIT' src='/images/debit.png'>",
                                        2 => "&nbsp;&nbsp;WTUSD<span style='color:red'>&#10084;</span>",
                                        3 => "<img src='/images/wt-pcreds-card.png'>",
                                        4 => "<img src='/images/wt-creds-card.png'>",
                                        5 => '&nbsp;&nbsp;WTUSD1',
                                        6 => "<img alt='Webtransfer DEBIT' src='/images/debit.png'>"
                                    ],
                                    'select' => [
//                                        0 => '-',
//                                        1 => "DEBIT",
                                        '2' => "Webtransfer WTUSD&#10084;",
//                                        3 => "P-CREDS",
//                                        4 => "H-CREDS",
//                                        5 => 'WTUSD1',
                                        '6' => "Webtransfer DEBIT"
                                    ]
                                )
                            )
                        ),
                        "summa"=> array(
                            "title" =>  _e('exchange/data4'),
                            "filter"=> true,
                            "order" =>  true,
                        ),
                        "percent"=> array(
                            "title" =>  "%",
                            "filter"=> true,
                            "order" =>  true,
                        ),
                       "out_summ"=> array(
                            "title" =>  _e('exchange/data5'),
                            "filter"=> true,
                            "order" =>  true,
                        ),
                        "date_out"=> array(
                            "title" =>  _e('exchange/data6'),
                            "filter"=> false,
                            "order" =>  true
                        ),
                        "amount_today"=> array(
                            "title" =>  _e('exchange/data7'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "diff_today"=> array(
                            "title" =>  _e('exchange/data8'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "summ_exchange"=> array(
                            "title" =>  _e('exchange/data9'),
                            "filter"=> true,
                            "order" =>  true,
                        ),
                        "action" => array(
                            "title" =>  "",
                            "filter"=> false,
                            "order" =>  false,
                            "formatter" => array(
                                "widget" => 'partial',
                                "widget_options" => array(
                                    "template" => "bay.html"
                                )
                            )
                        ),
                        "time" => array(
                            "title" =>  "",
                            "filter"=> false,
                            "order" =>  false,
//                            "formatter" => array(
//                                "widget" => 'partial',
//                                "widget_options" => array(
//                                    "template" => "empty.html"
//                                )
//                            )
                        ),
                    ),
                );
        $this->headerCREDS = array(
                    "search_name" => _e("Поиск"),
                    "on_page" => _e("Показывать на стр."),
                    "title" => _e('exchange/data1'),
                    "columns" => array(
                   //     "id" => array(
                   //         "title" =>  _e('exchange/data2'),
                    //        "filter"=> false,
                   //         "order" =>  true,
                   //     ),
                    //    "id_user" => array(
                    //        "title" =>  _e('exchange/data3'),
                    //        "filter"=> false,
                    //        "order" =>  true,
                    //    ),
                        "summa"=> array(
                            "title" =>  _e('exchange/data4'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                       "days_summ"=> array(
                            "title" =>  _e('Прибыль до возврата'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "date_out"=> array(
                            "title" =>  _e('exchange/data6'),
                            "filter"=> false,
                            "order" =>  true
                        ),
                        "amount_today"=> array(
                            "title" =>  _e('exchange/data7'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "diff_today"=> array(
                            "title" =>  _e('exchange/data8'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "summ_exchange"=> array(
                            "title" =>  _e('exchange/data9'),
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "action" => array(
                            "title" =>  "",
                            "filter"=> false,
                            "order" =>  false,
                            "formatter" => array(
                                "widget" => 'partial',
                                "widget_options" => array(
                                    "template" => "bay.html"
                                )
                            )
                        ),
                        "time" => array(
                            "title" =>  "",
                            "filter"=> false,
                            "order" =>  false,
//                            "formatter" => array(
//                                "widget" => 'partial',
//                                "widget_options" => array(
//                                    "template" => "empty.html"
//                                )
//                            )
                        ),
                    ),
                );
    }

    public function index() {
        viewData()->page_name = "exchange";
        viewData()->secondary_menu = "exchange";
        $this->_renderDTable();
        $data["url"] = site_url('account/exchange');
        $data["security"] = Security::getProtectType($this->accaunt->get_user_id());
        $this->content->user_view('exchangies', $data, _e('exchange/data10'));
    }

    private function _renderDTable($type = false) {
        if($this->base_model->returnNotAjax()) return false;
        if (isset($_POST["def"])) {
            if(false === $type)
                echo json_encode($this->header);
            if('creds' == $type)
                echo json_encode($this->headerCREDS);
            die;
        }
        if(isset($_POST["offset"])){
            $this->load->model('credits_model', 'credits');
            $params = array("me" => $this->me);
            $params['search'] = $this->input->post('search');
            $params['per_page'] = $this->input->post('per_page');
            $params['offset'] = $this->input->post('offset');
            $params['order'] = $this->input->post('order');
            $params['filter'] = $this->input->post('filter');
            if(false === $type)
                echo json_encode($this->credits->getAllCreditsForExchange($params));
            if('creds' == $type)
                echo json_encode($this->credits->getAllCreditsForExchangeCreds($params));
            die;
        }
    }

    public function exchange_creds2() {
        viewData()->page_name = "exchange_creds";
        viewData()->secondary_menu = "exchange";
        $this->_renderDTable('creds');
        $data["url"] = site_url('account/exchange_creds');
        $data["security"] = Security::getProtectType($this->accaunt->get_user_id());
        $this->content->user_view('exchangies_creds', $data, _e('exchange/data20'));
    }

    public function edit() {
        $data = new stdClass();
        if($this->update()){
            redirect(site_url('account/exchange'));
        }

        accaunt_message($data, _e('exchange/data11'));
        redirect(site_url('account/exchange'));
    }

    public function add() {
        $data = new stdClass();
        require_once APPPATH.'controllers/user/Security.php';
        if(Security::checkSecurity($this->me))
            redirect(site_url('account/invests_enqueries'));

        if($this->update())
            redirect(site_url('account/invests_enqueries'));


        accaunt_message($data, _e('exchange/data12'));
        redirect(site_url('account/invests_enqueries'));
    }

    protected function update() {
        $data = new stdClass();
        $id = (int) $this->input->post("id");
        $summ = (float) changeComa($this->input->post("summ"));
        $i = $this->credits->getDebit($id);
        if ($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF && ($i->summa > $summ || $i->out_summ < $summ) ||
            $i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER && (10 > $summ || $i->summa < $summ)){
            accaunt_message($data, _e('exchange/data13'), 'error');
            return true;
        }
        if (Base_model::CREDIT_BONUS_ON == (int) $i->bonus){
            accaunt_message($data, _e('exchange/data14'), 'error');
            return true;
        }
        $this->credits->setExchange($id, $summ, $this->me);
        return false;
    }

    public function del($id = null) {
        $data = new stdClass();
        if(null == $id) show_404 ();
        $id = (int) $id;
        $this->credits->delExchange($id);

        accaunt_message($data, _e('exchange/data15'));
        redirect(site_url('account/invests_enqueries'));
    }

    public function bay($id, $type = 'norm', $payment_account = -1, $code = '', $purpose = '') {
        $data = new stdClass();

        if('norm' == $type){
            $i = $this->credits->getDebit4Credit($id);
            $redirect = 'account/exchange';
        }
        if('CREDS' == $type){
            $i = $this->credits->getDebit($id);
            $redirect = 'account/exchange_creds';
        }

        if ( strpos( $_SERVER['HTTP_REFERER'], base_url()) === FALSE){
            accaunt_message($data, _e('Недоступно'), 'error');
            redirect(site_url($redirect));
        }
        
        $_POST['code'] = $code;
        $_POST['purpose'] = $purpose;
        require_once APPPATH.'controllers/user/Security.php';
        if(Security::checkSecurity($this->me))
             redirect(site_url($redirect));

        $user_ratings = viewData()->accaunt_header;


        /*$set_account_bonus = -1;
        if ( $payment_account > 0 ){
                $set_account_bonus = $payment_account;
        } else {
            $set_account_bonus = $i->bonus;
        } */
	$set_account_bonus = $i->bonus;

        // принудительно поставим чтобы бонус был как в заявке
        //if ( $i->bonus != 3)
          //  $set_account_bonus = $i->bonus;


        //$user_ratings = $this->accaunt->recalculateUserRating($this->me, null, $set_account_bonus );

        if ($this->accaunt->isUS2USorCA($i->id_user)) {
            accaunt_message($data, _e('exchange/data16'), 'error');
            redirect(site_url($redirect));
        }

        if($this->preSellCertificat($i, false)){
            redirect(site_url($redirect));
        }

        if($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF){
            if(Base_model::CREDIT_EXCHANGE_OFF == $i->exchange //метка продажи сертификата на бирже
                || $i->summa > $i->summ_exchange // ЕСЛИ номинал сертификата > продажной цены сертификата ТО ошибка, потому что выгоднее поагсить по гаранту
                || $i->out_summ < $i->summ_exchange //ЕСЛИ сумма возврата < продажной цены сертификата ТО
                || Base_model::CREDIT_BONUS_ON == (int) $i->bonus //Это не бонус
                || $this->me == $i->debit_id_user){ //я не продаю сам себе
                    accaunt_message($data, _e('exchange/data17'), 'error');
                    redirect(site_url($redirect));
            }
        } elseif($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER) {
            if(Base_model::CREDIT_EXCHANGE_OFF == $i->exchange //метка продажи сертификата на бирже
                || 10 > $i->summ_exchange // ЕСЛИ номинал сертификата > продажной цены сертификата ТО ошибка, потому что выгоднее поагсить по гаранту
                || $i->summa < $i->summ_exchange //ЕСЛИ сумма возврата < продажной цены сертификата ТО
                || Base_model::CREDIT_BONUS_ON == (int) $i->bonus //Это не бонус
                || $this->me == $i->id_user){ //я не продаю сам себе
                    accaunt_message($data, _e('exchange/data17'), 'error');
                    redirect(site_url($redirect));
            }
        }


        $this->load->model('Fincore_model', 'fincore');
        if(!$this->fincore->canInvest($user_ratings)  //проверка верифицированности и наличие непогашенных займов
                || $this->preCreateInvestExchange($user_ratings, $i->summ_exchange, $set_account_bonus)){
            redirect(site_url($redirect));
        }

        if($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF){
            if (!$this->credits->bayCertificate($i, $set_account_bonus)){
                accaunt_message($data, _e('exchange/data18'), 'error');
                redirect(site_url($redirect));
            }
        }
        if($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER){
            if (!$this->credits->bayCREDSCertificate($i,$set_account_bonus)){
                accaunt_message($data, _e('exchange/data18'), 'error');
                redirect(site_url($redirect));
            }
        }

        $this->postSellCertificat($i, $i->summ_exchange);


        $this->mail->user_sender('exchange-cert-buyer', $this->me, array());
        $this->mail->user_sender('exchange-cert-seller', $i->debit_id_user, array());
        $this->load->model('inbox_model');
        $this->inbox_model->writeInbox('admin', $this->me, _e('Кредитный сертификат купленный вами на бирже, был успешно переоформлен на ваше имя. Средства на покупку были сняты с вашего кошелька и зачислены продавцу.'));
        $this->inbox_model->writeInbox('admin', $i->debit_id_user, _e('Кредитный сертификат выставленный вами на бирже, был успешно продан другому участнику. Средства от продажи зачислены на ваш кошелек.'));


        accaunt_message($data, _e('exchange/data19'));
        redirect(site_url($redirect));
    }

}
