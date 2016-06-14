<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require 

APPPATH.'libraries/SimpleREST_controller.php';}

class Ask extends SimpleREST_controller {

    public function check() {
        $this->load->library('code');
        $str = $this->input->post('item');
        if (!empty($str)) {
            $field = "email";
            $res = $this->db->select($field)->from('users')->where("$field = '" . $this->code->code($str) . "'")->get()->row($field);
            echo (!empty($res)) ? "2" : "1";
        }
    }

    public function telephone() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if ($this->form_validation->run('telephone') == FALSE) {
            echo validation_errors();
        } else {
            echo _e('Спасибо!').'<br />';
            $data = array(
                "name" => $this->input->post('name', TRUE),
                "telephone" => $this->input->post('telephone', TRUE),
                "email" => $this->input->post('email', TRUE),
                "where" => $this->input->post('when', TRUE),
                "state" => 2
            );
            $this->mail->send($this->input->post('email', TRUE), "Уважаемый " . $this->input->post('name', TRUE) . "!Вас запрос на обратный звонок принят. Наши  операторы  перезвонят  вам в  ближайшие время.С уважением,Команда ".$GLOBALS["WHITELABEL_NAME"]." Finance
Телефон: 8 800 100 9691support@webtransfer.com", 'Запрос на  звонок  принят');
            $this->db->insert("feedback", $data);
        }
    }

    public function feedback() {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        if ($this->form_validation->run('feedback') == FALSE) {
            echo "<div style='color:red'>" . validation_errors() . "</div>";
            echo "<script>"
            . "    $('#request').css('margin','');"
            . "    $('.loading-div').hide();"
            . "</script>";
        } else {
            echo"<h2 style='mrgin-bottom:  -20px'>Спасибо за обращение. Ваша заявка принята.</h2>
                    <script>$('#request').css('display','none')</script>";
            extract($this->base_model->add_feedback());
            $this->mail->send($this->input->post('email'), 'Ваш запрос принят', 'Обратная 

связь');
            $text = "Пользователь $name (кашелек - $sys_id) отослал вопрос \"$text\". Телефон - 

$telephone, емаил - $email.";
            $this->mail->send('support@webtransfer.com', $text, 'Новый  запрос с сайта');
        }
    }

    public function recovery() {
        $data = new stdClass();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->db->where('expires <', "now() - INTERVAL '1' day", false)->delete('recovery_pass');
        $id = (int) $this->input->get('id');
        $access = $this->input->get('access');
        $form = null;
        $error = null;
        if (base64_decode($access, true)) {
            $rec = $this->db->get_where('recovery_pass', array('id_user' => $id, "access_hash" => $access))->row();
            if (empty($rec))
                $error = 1;
            else {
                $form = true;

                if ($this->form_validation->run('recovery') == true) {
                    $form = false;
                    $error = 3;
                    $this->load->library('code');
                    $this->db->where('id_user', $id)->delete('recovery_pass');
                    $this->db->where('id_user', $id)->update('users', array('user_pass' => 

$this->code->request('password')));
                    $email = $this->db->select('email')->from('users')->where('id_user', $id)->get()->row('email');
                    cookie_log($email, $this->code->request('password'));
                }
            }
        }
        else
            $error = 2;

        $data->form = $form;
        $data->error = $error;

        viewData()->banner_menu = "profile_login";
        viewData()->secondary_menu = "profile";

        $this->content->config(_e('Восстановление пароля'));
        $this->content->template('recovery', $data);
    }

    public function forget() {
        $data = new stdClass();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('code');
        $send = $this->base_model->check_by_ip("send_forget");

        $this->load->model('Recaptcha_model');



        $error = null;
        if (!empty($send) and $send >= 2)
            $error = 2;
        else {
            $this->form_validation->set_rules('mail', 'lang:Почта', 'trim|required|valid_email');
            if ($this->form_validation->run() == true) {

                if (!Recaptcha_model::checkRecapcha()) {
                    accaunt_message($data, _e('Не верный код с картинки'), 'error');
                } else {
                    $res = $this->db->query("select id_user from users where email='" . $this->code->code($this->input->post('mail')) . "'")->row();
                    if (!empty($res->id_user)) {
                        $hex = base64_encode(substr(dechex(time()) . md5(uniqid(rand(), 1)), 2, 

8));
                        $recovery = $this->db->get_where('recovery_pass', array('id_user' => 

$res->id_user))->row();
                        if (empty($recovery))
                            $this->db->insert('recovery_pass', array('id_user' => $res->id_user, 

"access_hash" => $hex));
                        else
                            $this->db->where('id_user', $res->id_user)->update('recovery_pass', 

array('expires' => date("Y-m-d H:i:s"), "access_hash" => $hex));

                        if ($this->mail->user_sender('forget', $res->id_user, urlencode($hex))) {
                            $this->base_model->add_to_ip('send_forget');
                            $error = 1;
                        }
                        else
                            $error = 3;
                    }
                    else
                        $error = "4";
                }
            }
        }
        $data->error = $error;

        viewData()->banner_menu = "profile_login";
        viewData()->secondary_menu = "profile";

        $this->content->config(_e('Забыли пароль'));
        $this->content->template('forget', $data);
    }

//    public function get_kurs() {
//        echo ' <script type="text/javascript" src="/js/user/jquery.js"></script>
//            <link rel="stylesheet" href="/css/user/main.css" />';
//        echo get_table_count();
//        echo '<script>$(".currentday").hide()</script>';
//    }

    private function _failurePayment($data, $error_data){
    }

    private function _addMoney2System($data, $state, $note) {
        $this->base_model->log2file("data=".print_r($data, true).", state=".print_r($state,true).", note=".print_r($note,true), "ask_test");
        if (Base_model::TRANSACTION_STATUS_RECEIVED != $state)
            return;
            
           
            
        $exchanage_comission =  NULL;
        if(preg_match_all("/\(comission:(.*)\)/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
            $exchanage_comission = (float)($all_matches[1][0]);
            
        $exTrNo =  NULL;
        if(preg_match_all("/\(ExTrNo:(.*)\)/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
            $exTrNo = (int)($all_matches[1][0]);            


        $this->load->model("transactions_model","transactions");
        if(Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE == $data->type && Base_model::TRANSACTION_STATUS_RECEIVED == $state){
            $this->transactions->confermArbitrageInvest($data, FALSE);
        } else {
            $this->base_model->log2file("payBonus=try", "ask_test");
            $this->transactions->payPaymentBonus((object)$data);
            $this->base_model->log2file("payPaymentBonus after", "ask_test");
            $this->transactions->payPaymentFee((object)$data);
            $this->base_model->log2file("payBonus=ok", "ask_test");
        }
        // если пополняем карту
        if (  $data->bonus == 7){
            
            if ( $data->type==334){
                if ( $data->status == 2 ){
                    $request_id = $data->value;
                    $this->load->model('Card_model','card'); 
                    $r = $this->card->create_card_by_request( $request_id );
                    $this->db->update('transactions', array('status' => Base_model::TRANSACTION_STATUS_DELETED, "note" => $data->note.$note), array('id' => $data->id), 1);
                }
            } else {
            
                $this->load->helper('translit_helper');  
                if ( $data->status == 2 ){
                    if ( $exchanage_comission !== NULL )
                        $orig_summ = $data->summa - $exchanage_comission;
                    else {
                        $comissions = config_item('card_payin_comission');
                        $comission = 0;
                        if ( isset($comissions[$data->type]))
                            $comission = $comissions[$data->type];                    
                        $orig_summ = $data->summa / (1+($comission/100));
                    }
                    $this->load->model('card_model', 'card');
                    $load_data = new stdClass();
                    $load_data->id = $data->id;
                    $load_data->card_id = $data->value;
                    $load_data->user_id = $data->id_user;
                    $load_data->summa = $orig_summ;
                    $load_data->desc = 'Load to card: '.translitIt($note);
                    $response = $this->card->load($load_data, Card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $data->id);
                    if(false !== $response) {
                        $this->db->update('transactions', array('status' => Base_model::TRANSACTION_STATUS_DELETED, "note" => $data->note.$note), array('id' => $data->id), 1);
                       if ($exTrNo !== NULL){
                            $this->load->model('Exchange_api_model', 'exchange_api');
                            $this->exchange_api->setStatus(  $exTrNo, Exchange_api_model::STATUS_SUCCESS, 'Успешно выполнено' );    
                       }                    
                    } else {
                        $this->db->update('transactions', array('summa'=>$orig_summ, 'status' => Base_model::TRANSACTION_STATUS_RECEIVED, 'bonus'=>0, "note_admin" => 'card_problem'), array('id' => $data->id), 1);
                       if ($exTrNo !== NULL){
                            $this->load->model('Exchange_api_model', 'exchange_api');
                            $this->exchange_api->setStatus(  $exTrNo, Exchange_api_model::STATUS_ERROR, 'Ошибка зачисления средств' );    
                       }                    
                    }
                }
        }
    }  else {
                if ( $exchanage_comission !== NULL ){
                   $orig_summ = $data->summa - $exchanage_comission;
                   if ($exTrNo !== NULL){
                        $this->load->model('Exchange_api_model', 'exchange_api');
                        $this->load->model('Transactions_model', 'transactions');
                        $this->transactions->payPaymentBonus($data);
                        $this->exchange_api->setStatus(  $exTrNo, Exchange_api_model::STATUS_SUCCESS, 'Успешно выполнено' );    
                   }
                }
                else
                   $orig_summ = $data->summa / (1+(config_item('account_payin_comission')/100));
                $this->db->update('transactions', array('summa'=>$orig_summ,'status' => $state, "note" => $data->note.$note), array('id' => $data->id), 1);
        }
        $this->base_model->log2file("going to end data=".print_r($data,true), "ask_test");
    }

    public function nixmoneyCallback() {
        error_reporting(E_ALL ^ E_NOTICE);

        if(!isset($_POST['V2_HASH'])){
            $n = APPPATH."logs/nixmoney." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' HACKER(no_hash)::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        $string=
              $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
              $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
              $_POST['PAYMENT_BATCH_NUM'].':'.
              $_POST['PAYER_ACCOUNT'].':'.getNixMoneyHash().':'.
              $_POST['TIMESTAMPGMT'];

        $hash=strtoupper(md5($string));


        /*
           Please use this tool to see how valid hash is genereted:
           https://nixmoney.is/acct/md5check.html
        */
        if($hash==$_POST['V2_HASH']){ // proccessing payment if only hash is valid

            $order_id = (int) $this->input->post('PAYMENT_ID', true);
            $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
            if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                if(!config_item("Payment_log")) die;
                 $n = APPPATH."logs/nixmoney." . date("d-M-Y") . ".txt";
                 $f = fopen($n, "a+");
                 $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                 fputs($f, $str, strlen($str));
                 fclose($f);
                 die;
            }

            if($_POST['PAYMENT_AMOUNT']==$data->summa && $_POST['PAYEE_ACCOUNT']==getNixMonayID() 

&& $_POST['PAYMENT_UNITS']=='USD'){

                $txn_id = $this->input->post('PAYER_ACCOUNT', true);

                $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                $note = "Top up from Nixmoney #$txn_id (NIX)";

            }else{ // you can also save invalid payments for debug purposes
                if(!config_item("Payment_log")) die;
                 $n = APPPATH."logs/nixmoney." . date("d-M-Y") . ".txt";
                 $f = fopen($n, "a+");
                 $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                 fputs($f, $str, strlen($str));
                 fclose($f);

                 $this->_failurePayment($data, $_POST);
                 die;
               // uncomment code below if you want to log requests with fake data
               /* $f=fopen(PATH_TO_LOG."bad.log", "ab+");
               fwrite($f, date("d.m.Y H:i")."; REASON: fake data; POST: ".serialize($_POST)."; 

STRING: $string; HASH: $hash\n");
               fclose($f); */

            }


        }else{ // you can also save invalid payments for debug purposes
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/nixmoney." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' HACKER(hash_vrong)::'.print_r

($_POST,true).PHP_EOL."our_hash:: $hash /n ($string) /n key:".getNixMoneyHash();
            fputs($f, $str, strlen($str));
            fclose($f);
            $this->_failurePayment($data, 'hash_vrong');
            die;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function okpayCallback() {
        /* Check IPN and process payment */
        error_reporting(E_ALL ^ E_NOTICE);

        // Read the post from OKPAY and add 'ok_verify'
        $request = 'ok_verify=true';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $request .= "&$key=$value";
        }

        $fsocket = false;
        $result = false;

        // Try to connect via SSL due sucurity reason
        if ( $fp = @fsockopen('ssl://www.okpay.com', 443, $errno, $errstr, 30) ) {
            // Connected via HTTPS
            $fsocket = true;
        } elseif ($fp = @fsockopen('www.okpay.com', 80, $errno, $errstr, 30)) {
            // Connected via HTTP
            $fsocket = true;
        }

        // If connected to OKPAY
        if ($fsocket == true) {
            $header = 'POST /ipn-verify.html HTTP/1.0' . "\r\n" .
                      'Host: www.okpay.com'."\r\n" .
                      'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                      'Content-Length: ' . strlen($request) . "\r\n" .
                      'Connection: close' . "\r\n\r\n";

            @fputs($fp, $header . $request);
            $string = '';
            while (!@feof($fp)) {
                $res = @fgets($fp, 1024);
                $string .= $res;
                // Find verification result in response
                if ( $res == 'VERIFIED' || $res == 'INVALID' || $res == 'TEST') {
                    $result = $res;
                    break;
                }
            }
            @fclose($fp);
        }

        if ($result == 'VERIFIED') {
            // check the "ok_txn_status" is "completed"
            // check that "ok_txn_id" has not been previously processed
            // check that "ok_receiver_email" is your OKPAY email
            // check that "ok_txn_gross"/"ok_txn_currency" are correct
            // process payment

            $order_id = (int) $_POST['ok_invoice'];
            $txn_id = $_POST['ok_txn_id'];
            $pay_email = $_POST['ok_receiver_email'];
            $status = $_POST['ok_txn_status'];
            $curr = strtoupper( $_POST['ok_txn_currency'] );

            if ($status == 'completed') {
                $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
                if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                    if(!config_item("Payment_log")) die;
                    $n = APPPATH."logs/okpay." . date("d-M-Y") . ".txt";
                    $f = fopen($n, "a+");
                    $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                    fputs($f, $str, strlen($str));
                    fclose($f);
                    die;
                }
                if( 'USD' == $curr  && "payments@webtransfer.com" == $pay_email){
                    $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                    $note = "Top up from Okpay #$txn_id (OKP)";
                }else{
                    if(!config_item("Payment_log")) die;
                    $n = APPPATH."logs/okpay." . date("d-M-Y") . ".txt";
                    $f = fopen($n, "a+");
                    $str = date("d-M-Y H:i:s").' HACHER(wrong cur or email)::'.print_r

($_POST,true);
                    fputs($f, $str, strlen($str));
                    fclose($f);
                    $this->_failurePayment($data, $_POST);
                    die;
                }
            } else return;


        } elseif($result == 'INVALID') {
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/okpay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' INVALID::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            $this->_failurePayment($data, $result);
            die;

        } elseif($result == 'TEST') {
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/okpay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' TEST::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            $this->_failurePayment($data, $result);
            die;

        } else {
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/okpay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' HACHER::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            // IPN not verified or connection errors
            // If status != 200 IPN will be repeated later
            header("HTTP/1.0 404 Not Found");
            $this->_failurePayment($data, $_POST);
            exit;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function wpaycallback() {
        try{

            $password = getWpayPassword(); //Password located in your merchant profile -  https://wpay.net/user/merchantapi
            $data = $_POST;

            unset($data['wp_sigh']); //remove from the data signature line
            ksort($data, SORT_STRING); // sort by key in the alphabetical order of the array elements
            array_push($data, $password); // adding password to the end of the array
            $signString = implode(':', $data); // concatenating the value of the symbol ":"
            $sign = base64_encode(md5($signString, true)); // taking the MD5 hash of a binary form on line and encode in BASE64

            if($sign != $_POST['wp_sigh']){
                if(!config_item("Payment_log")) die;
                $n = APPPATH."logs/wpay." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                fputs($f, $str, strlen($str));
                fclose($f);
                die;
            }

            if ($_POST['wp_status'] == 'success'){
                $order_id = (int) $this->input->post('wp_order_id', true);
                $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
                if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/" . date("d-M-Y") . ".wpay.txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     die;
                }

                if($_POST['wp_amount']==$data->summa && $_POST['wp_currency']=='USD' && $_POST

["wp_merchant_id"] == getWpayMerchantID()){

                    $txn_id = $this->input->post('wp_invoice_id', true);
                    $processed = $this->input->post('wp_processed', true);
                    $created = $this->input->post('wp_created', true);
    //                $date = $this->input->post('m_operation_date', true);

                    $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                    $note = "Информация о пополнении: №$txn_id ($created, $processed)";

                }else{ // you can also save invalid payments for debug purposes
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/wpay." . date("d-M-Y") . ".txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     die;
                }
            } else {
                if(!config_item("Payment_log")) die;
                $n = APPPATH."logs/wpay." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' HACKER(wrong_status)::'.print_r($_POST,true);
                fputs($f, $str, strlen($str));
                fclose($f);
                die;
            }
        } catch(Exception $e){
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/wpay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' (error)::'.print_r($_POST,true).$e->getMessage();
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function egopayCallback() {
        $this->load->library("EgoPaySci");
        try{
            $oEgopay = new EgoPaySciCallback(array(
                'store_id'          => getEgopayStore(),
                'store_password'    => getEgopayPassword(),
                'checksum_key'      => getEgopayKey(),
            ));
            $aResponse = $oEgopay->getResponse($_POST);

            /**
             * $aResponse = array(
             *     'sId'        => (string), //'TEST MODE' if test mode is enabled
             *    'sDate'      => (string),
             *  'fAmount'    => (float) ,
             *  'sCurrency'  => (string),
             *  'fFee'       => (float) , //Zero in test mode
             *  'sType'      => (string), //'TEST Store Received' in test mode
             *  'iTypeId'    => (string), //Zero in test mode
             *  'sEmail'     => (string),
             *  'sDetails'   => (string),
             *  'sStatus'    => (string), //'Completed' or 'TEST SUCCESS' in test mode
             *     // OPTIONAL
             *  'cf_1'         => (string),
             *  'cf_2'         => (string),
             *  'cf_3'         => (string),
             *  'cf_4'         => (string),
             *  'cf_5'         => (string),
             *  'cf_6'         => (string),
             *  'cf_7'         => (string),
             *  'cf_8'         => (string),
             *
             * );
             *
             * Note: To check if payment was not made in test mode and is completed,
             * you have to make sure that sStatus equals to 'Completed'
             */

            if ($_POST['sStatus'] == 'Completed'){
                $order_id = (int) $this->input->post('cf_1', true);
                $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
                if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/" . date("d-M-Y") . ".egopay.txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     die;
                }

                if($_POST['fAmount']==$data->summa && $_POST['sCurrency']=='USD'){

                    $txn_id = $this->input->post('sId', true);
                    $method_id = $this->input->post('sType', true);
    //                $date = $this->input->post('m_operation_date', true);

                    $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                    $note = "Информация о пополнении: №$txn_id ($method_id)";

                }else{ // you can also save invalid payments for debug purposes
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/egopay." . date("d-M-Y") . ".txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     die;
                }
            } else {
                if(!config_item("Payment_log")) die;
                $n = APPPATH."logs/egopay." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' HACKER(wrong_status)::'.print_r($_POST,true);
                fputs($f, $str, strlen($str));
                fclose($f);
                die;
            }
        } catch(EgoPayException $e){
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/egopay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' (error)::'.print_r($_POST,true).$e->getMessage();
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function payeerCallback() {
        if (isset($_POST['m_operation_id']) && isset($_POST['m_sign'])){
            $this->load->model("users_model", 'users');
//            if(config_item("Payeer_IP") != $this->users->getIpUser()){
//                if(!config_item("Payment_log")) die;
//                $n = APPPATH."logs/payeer." . date("d-M-Y") . ".txt";
//                if($f = fopen($n, "a+")){
//                    $str = date("d-M-Y H:i:s").' (IP_WRONG)::'.print_r($_POST,true)." ip = ".

$this->users->getIpUser();
//                    fputs($f, $str, strlen($str));
//                    fclose($f);
//                }
//                die;
//            }


            $m_key = getPayeerSecret();
            $arHash = array($_POST['m_operation_id'],
                            $_POST['m_operation_ps'],
                            $_POST['m_operation_date'],
                            $_POST['m_operation_pay_date'],
                            $_POST['m_shop'],
                            $_POST['m_orderid'],
                            $_POST['m_amount'],
                            $_POST['m_curr'],
                            $_POST['m_desc'],
                            $_POST['m_status'],
                            $m_key);
            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
            if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success'){
                $order_id = (int) $this->input->post('m_orderid', true);
                $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
                if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/payeer." . date("d-M-Y") . ".txt";
                     if($f = fopen($n, "a+")){
                        $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                        fputs($f, $str, strlen($str));
                        fclose($f);
                     }
                     die;
                }

                if($_POST['m_amount']==$data->summa && $_POST['m_shop']=='24650396' && $_POST

['m_curr']=='USD'){

                    $txn_id = $this->input->post('m_operation_id', true);
                    $method_id = $this->input->post('m_operation_ps', true);
    //                $date = $this->input->post('m_operation_date', true);

                    $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                    $note = "Top up from Payeer # $txn_id ($method_id)";

                }else{ // you can also save invalid payments for debug purposes
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/payeer." . date("d-M-Y") . ".txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     $this->_failurePayment($data, $_POST);
                     die;
                }
            } else {
                $n = APPPATH."logs/payeer." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' HACKER(hash_vrong)::'.print_r

($_POST,true).PHP_EOL."our_hash::$sign_hash";
                fputs($f, $str, strlen($str));
                fclose($f);
                $this->_failurePayment($data, 'bad_hash');
                die;
            }
        } else {
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/payeer." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' HACKER(no_sign)::'.print_r($_POST,true);
                fputs($f, $str, strlen($str));
                fclose($f);
                $this->_failurePayment($data, $_POST);
                die;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function perfectmoneyCallback() {
$this->base_model->log2file("data=".print_r($_POST, true), "perfect_test");
         /* Constant below contains md5-hashed alternate passhrase in upper case.
            You can generate it like this:
            strtoupper(md5('your_passphrase'));
            Where `your_passphrase' is Alternate Passphrase you entered
            in your Perfect Money account.

            !!! WARNING !!!
            We strongly recommend NOT to include plain Alternate Passphrase in
            this script and use its pre-generated hashed version instead (just
            like we did in this scipt below).
            This is the best way to keep it secure. */
        //define('ALTERNATE_PHRASE_HASH',  '80F632EBFE5295A9F8933E360EB382DF');


        // Path to directory to save logs. Make sure it has write permissions.
        //define('PATH_TO_LOG',  '/somewhere/out/of/document_root/');
        if(!isset($_POST['V2_HASH'])){
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/perfectmoney." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' HACKER(no_hash)::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        $string=
              $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
              $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
              $_POST['PAYMENT_BATCH_NUM'].':'.
              $_POST['PAYER_ACCOUNT'].':'.getPerfectmoneyHash().':'.
              $_POST['TIMESTAMPGMT'];

        $hash=strtoupper(md5($string));


        /*
           Please use this tool to see how valid hash is genereted:
           https://perfectmoney.is/acct/md5check.html
        */
        if($hash==$_POST['V2_HASH']){ // proccessing payment if only hash is valid

           /* In section below you must implement comparing of data you recieved
              with data you sent. This means to check if $_POST['PAYMENT_AMOUNT'] is
              particular amount you billed to client and so on. */
            $order_id = (int) $this->input->post('PAYMENT_ID', true);
            $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
            if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                if(!config_item("Payment_log")) die;
                 $n = APPPATH."logs/perfectmoney." . date("d-M-Y") . ".txt";
                 if($f = fopen($n, "a+")){
                    $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                    fputs($f, $str, strlen($str));
                    fclose($f);
                 }
                 die;
            }

            if($_POST['PAYMENT_AMOUNT']==$data->summa && $_POST['PAYEE_ACCOUNT']=='U8258953' && 

$_POST['PAYMENT_UNITS']=='USD'){

                $txn_id = $this->input->post('PAYER_ACCOUNT', true);

                $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                $note = "Top up from Perfectmoney # $txn_id (PERF)";

               // uncomment code below if you want to log successfull payments
               /* $f=fopen(PATH_TO_LOG."good.log", "ab+");
               fwrite($f, date("d.m.Y H:i")."; POST: ".serialize($_POST)."; STRING: $string; 

HASH: $hash\n");
               fclose($f); */

            }else{ // you can also save invalid payments for debug purposes
                if(!config_item("Payment_log")) die;
                 $n = APPPATH."logs/perfectmoney." . date("d-M-Y") . ".txt";
                 $f = fopen($n, "a+");
                 $str = date("d-M-Y H:i:s").' HACKER(daesnt_match)::'.print_r($_POST,true);
                 fputs($f, $str, strlen($str));
                 fclose($f);
                 $this->_failurePayment($data, $_POST);
                 die;
               // uncomment code below if you want to log requests with fake data
               /* $f=fopen(PATH_TO_LOG."bad.log", "ab+");
               fwrite($f, date("d.m.Y H:i")."; REASON: fake data; POST: ".serialize($_POST)."; 

STRING: $string; HASH: $hash\n");
               fclose($f); */

            }


        }else{ // you can also save invalid payments for debug purposes
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/perfectmoney." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' HACKER(hash_vrong)::'.print_r

($_POST,true).PHP_EOL."our_hash:: $hash /n ($string) /n key:".getPerfectmoneyHash();
            fputs($f, $str, strlen($str));
            fclose($f);
            $this->_failurePayment($data, 'bad_hash');
            die;
           // uncomment code below if you want to log requests with bad hash
           /* $f=fopen(PATH_TO_LOG."bad.log", "ab+");
           fwrite($f, date("d.m.Y H:i")."; REASON: bad hash; POST: ".serialize($_POST)."; STRING: 

$string; HASH: $hash\n");
           fclose($f); */

        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function lavapayCallback() {
        /* Check callback and process payment */
        error_reporting(E_ALL ^ E_NOTICE);

        if(empty($_POST)){
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/lavapay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' (empty_POST)::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        // Read the post from LavaPay and add 'LavaVerify'
        $request = 'LavaVerify=true';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $request .= "&$key=$value";
        }

        $fsocket = false;
        $result = false;

        // Try to connect via SSL due sucurity reason
        if ($fp = @fsockopen('ssl://www.LavaPay.com', 443, $errno, $errstr, 30)) {
            // Connected via HTTPS
            $fsocket = true;
        } elseif ($fp = @fsockopen('www.LavaPay.com', 80, $errno, $errstr, 30)) {
            // Connected via HTTP
            $fsocket = true;
        }

        // If connected to LavaPay
        if ($fsocket == true) {
            $header = 'POST /callback-verify.html HTTP/1.0' . "\r\n" .
                    'Host: www.LavaPay.com' . "\r\n" .
                    'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                    'Content-Length: ' . strlen($request) . "\r\n" .
                    'Connection: close' . "\r\n\r\n";

            @fputs($fp, $header . $request);
            $string = '';
            while (!@feof($fp)) {
                $res = @fgets($fp, 1024);
                $string .= $res;
                // Find verification result in response
                if ($res == 'VERIFIED' || $res == 'INVALID' || $res == 'TEST') {
                    $result = $res;
                    break;
                }
            }
            @fclose($fp);
        }

        if ($result == 'VERIFIED') {
            $order_id = (int) $_POST['LavaItem1Custom1Value'];
            $payment_id = $_POST['LavaItem1Custom2Value'];
            $txn_id = $_POST['LavaTxnId'];
            $pay_method = $_POST['LavaTxnPayMethod'];
            $status = $_POST['LavaTxnStatus'];
            $curr = strtoupper( $_POST['LavaTxnCurrency'] );

            if ($status == 'completed') {
                $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
                if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                    if(!config_item("Payment_log")) die;
                     $n = APPPATH."logs/lavapay." . date("d-M-Y") . ".txt";
                     $f = fopen($n, "a+");
                     $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                     fputs($f, $str, strlen($str));
                     fclose($f);
                     die;
                }
                if( $curr == 'USD'){
                    $state = Base_model::TRANSACTION_STATUS_RECEIVED;
                    $note = "Информация о пополнении: №$txn_id ($pay_method)";
                }else{
                    if(!config_item("Payment_log")) die;
                    $n = APPPATH."logs/lavapay." . date("d-M-Y") . ".txt";
                    $f = fopen($n, "a+");
                    $str = date("d-M-Y H:i:s").' HACHER(wrong cur)::'.print_r($_POST,true);
                    fputs($f, $str, strlen($str));
                    fclose($f);
                    die;
                }
            }
            else
                return;

        } elseif ($result == 'INVALID') {
            return;
        } elseif ($result == 'TEST') {
            $out = $result . PHP_EOL;
            foreach ($_POST as $key => $item)
                $out .="$key = $item " . PHP_EOL;

            $n = "/home/webfintest/public_html/upload/lavapay." . date("d-M-Y H:i:s") . ".txt";
            $f = fopen($n, "a+");

            fputs($f, $out, strlen($out));
            fclose($f);
            die;
        } else {
            // Callback not verified or connection errors
            // If status != 200 callback will be repeated later
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $this->_addMoney2System($data, $state, $note);
    }

    public function liq_pay() {
        if(!isset($_POST['order_id'])) {
            if(!config_item("Payment_log")) die;
            $n = APPPATH."logs/liq_pay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' (no_order_id)::'.print_r($_POST,true);
            fputs($f, $str, strlen($str));
            fclose($f);
            die;
        }

        $out = '';
        foreach ($_POST as $key => $item) {
            $out .="$key = $item -- ";
        }

        $order_id = $_POST['order_id'];
        $payment_id = $_POST['transaction_id'];
        $status = $_POST['status'];

        if ($status == 'failure') return;

        if ($status == 'success') {
            $data = $this->db->get_where('transactions', array('id' => $order_id))->row();
            if(Base_model::TRANSACTION_STATUS_RECEIVED == $data->status){
                if(!config_item("Payment_log")) die;
                $n = APPPATH."logs/liq_pay." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' (STATUS_RECEIVED)::'.print_r($_POST,true);
                fputs($f, $str, strlen($str));
                fclose($f);
                die;
            }
            $state = Base_model::TRANSACTION_STATUS_RECEIVED;
            $note = "Информация о пополнении: №$payment_id";
//        } elseif ($status == 'sandbox') {
//            $state = Base_model::TRANSACTION_STATUS_NOT_RECEIVED;
//        } elseif ($status == 'wait_secure') {
//            $state = Base_model::TRANSACTION_STATUS_PENDING;
//            $note = "Ожидание ответа";
        } else return;




        /*     $public_key = 'i2194089800';
          $json_data = json_encode( array(
          'public_key' => $public_key,
          'payment_id' => $payment_id,
          'order_id' => $order_id
          ));

          $url = 'https://www.liqpay.com/api/payment/status';

          $data = json_encode(array_merge(compact('public_key'), array('order_id'=>$id)));
          $signature = base64_encode(sha1($private_key.$data.$private_key, 1));
          $postfields = "data={$data}&signature={$signature}";

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS,$postfields);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          $server_output = curl_exec($ch);
          curl_close($ch);

          print_r(json_decode($server_output));
         */

        $this->_addMoney2System($data, $state, $note);
    }


    public function skypasserbonus (){
        if($this->input->post('secret')!='"lHxt?[DYy'){ echo "fail"; return; }
        echo "success";
        $this->load->model("transactions_model","transactions");
        $this->transactions->addPay($this->input->post('id_user'), $this->input->post('amount'), 

Transactions_model::TYPE_BONUS_SKYPASSER, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 

Base_model::TRANSACTION_BONUS_ON, "Пополнение бонусных средств от skypasser");
    }

    public function visualdna() {
        $puid = $this->input->post('puid');
        if(empty($puid)) $puid = $this->input->post('qapuid');
        $end_date = $this->input->post('date');

        $this->load->model("visualdna_model","visualdna");
        $this->visualdna->seveStatus($puid, $end_date);
//        if(!config_item("Payment_log")) die;
//        $n = APPPATH."logs/visualdna." . date("d-M-Y") . ".txt";
//        if($f = fopen($n, "a+")){
//            $str = date("d-M-Y H:i:s").' POST::'.print_r($_POST,true);
//            fputs($f, $str, strlen($str));
//            fclose($f);
//        }
    }


    public function loadfunds(){

        $this->load->model("transactions_model","transactions");
        $this->load->library('code');
        $this->load->library('payout');

        $state = 0;
        $user_id = $this->input->post_get('user_id');
        $wtcard_id = $this->input->post_get('wtcard_id');
        $payment_code = $this->input->post_get('payment_code');
        $payment_wallet = $this->input->post_get('payment_wallet');
        $amount = $this->input->post_get('amount');
        $hash = $this->input->post_get('hash');
        if ( /*empty($user_id) ||*/ empty($wtcard_id) || empty($payment_code) || empty

($payment_wallet) || empty($amount) || empty($hash) ){
            echo json_encode(["result"=>false, 'error'=> _e('Неверные данные')]);
            return;
        }


        //echo md5("$user_id:$payment_code:$payment_wallet:$amount:FDDJ-34DK-FDVV-LOLF-657V");
        if ( md5("$user_id:$payment_code:$payment_wallet:$amount:FDDJ-34DK-FDVV-LOLF-657V") == 

$hash ) {

            $check = $this->db->select('sum(amount) as total')->where

(['status'=>1,'payment_code'=>$payment_code])->where('dttm >', date('Y-m-d 00:00:00') )-> get

('card_exchange_list')->row();

            $this->db->insert('card_exchange_list', [
                'user_id' => $user_id,
                'wtcard_user_id' => $wtcard_id,
                'payment_code' => $payment_code,
                'payment_wallet' => $payment_wallet,
                'amount' => $amount,
                'hash' => $hash,
                'dttm' => date('Y-m-d H:i:s') ]);
            $id = $this->db->insert_id();
          //  echo json_encode(["result"=>true]); eti polya otklyuchayut avtoviplatu
           //   return;

            if ( $check->total + $amount > config_item('loadfunds_limit')[$payment_code] ){
                $this->db->update('card_exchange_list',['status'=>0, 'api_details'=>'Превышен 

лимит'],['id'=>$id]);
                echo json_encode(["result"=>false, 'error'=> _e('Превышен лимит')]);
                return;
            }


            // отправим деньги
            $data = new stdClass();
            $userdata = $this->db
                            ->where("id_user", $user_id)
                            ->get('users')
                            ->row();

            if (isset($userdata->email)) $data->email = $this->code->decrypt($userdata->email);
            else $data->email = '-';
            if (isset($userdata->phone)) $data->phone = $this->code->decrypt($userdata->phone);

            $data->id = $id;
            $data->value = $payment_code;
            $data->state = 0;
            $data->note_admin = '';
            $data->note = '';
            $data->summa = $amount;
            $data->commission = 0;
            $data->comment = "Funds transfer from Webtransfercard.com (# $id)";
            if(312 == $data->value) $data->payeer = $payment_wallet;
            else if(319 == $data->value) $data->okpay = $payment_wallet;
            else if(318 == $data->value) $data->perfectmoney = $payment_wallet;

            if ( in_array($data->value,[312,319,318] )){
                $res = $this->payout->pay($data, FALSE);
             //   var_dump($res);
                if ( $res['res'] == 'ok' ){
                    $state = 1;
                    echo json_encode(["result"=>true, 'id'=>$res['res_id']]);
                } else {
                    echo json_encode(["result"=>false, 'error'=> _e('Платежная система отклонила 

запрос')]);
                }
                $this->db->update('card_exchange_list',['trnno'=>$res['res_id'],'status'=>$state, 'api_details'=>$res['note']],['id'=>$id]);
            }



        } else {
            echo json_encode(["result"=>false, 'error'=> _e('Неверная CRC')]);
        }





    }
    
     public function wtcardSuccess() {
         echo _e("Вы успешно оплатили"); 
         
     }
     public function wtcardError() {
         echo _e("Не удалось оплатить");
     }
    
     public function wtcardCallback() {
                      $this->load->model('Card_model','card');
                      function getSignature($arHash)
                        {

                            $collect = [];
                            foreach($arHash as $key => $value)
                            {
                                if ($key != 'sig') {
                                    if(is_array($value)) {
                                        $value = json_encode($value);
                                    }
                                    $collect[] = $key . "=" . $value;
                                }
                            }

                            sort($collect);
                            $secret_key = 'U2HYe8fh4cbyueg343';//SHOP SECRET KEY
                            $current    = md5(implode("", $collect) . $secret_key);
                            return $current;
                        }
                        dev_log(var_export($_POST, TRUE),'wtcardCallback');
                        dev_log("IP".$_SERVER['REMOTE_ADDR'],'wtcardCallback');
                        if ($_SERVER['REMOTE_ADDR'] != '195.154.133.27') die('ERROR');

                        $callback_params = $_POST;

                        /*

                        EXAMPLE PARAMS RECEIVED FROM WEBTRANSFER

                        $callback_params = [
                            'order_id'          => '1234',
                            'service_id'        => 'L000058678211',
                            'order_sum'         => '1',
                            'currency_id'       => '840',
                            'status'            => 'done',
                            'order_description' => 'Test description',
                            'payment_system'    => 'MCH',
                            'sig'               => 'a630de2f20524fa58d0e0bbc01a021ba',
                        ];

                        */


                        if($callback_params['sig'] !== getSignature($callback_params)){
                            dev_log("SIG FAIL: ".$callback_params['sig'].' '.getSignature($callback_params),'wtcardCallback');
                            die('ERROR');
                        }
                        dev_log("SIG OK",'wtcardCallback');
                        
                        $request_id = $callback_params['order_id'];
                        $request = $this->db->where('id',$request_id)->get('cards_requests')->row();
                        if ( !empty($request)){
                            $this->load->library('WTCApi');
                            
                            $wtcapi = new WTCApi();
                            
                            $user_id = $request->id_user;

                            $create_card = $wtcapi->card($request);
                            dev_log(var_export($create_card, TRUE),'wtcardCallback');
                            if(!empty($create_card['errors'])){
                                $this->card->save_request($request_id, [
                                    'decline_error' => json_encode($create_card['errors']),
                                    'declined' => date("Y-m-d H:i:s"),
                                    'status' => 'pay_declined'
                                ]);

                                $errorStr = '';
                                foreach ($create_card['errors'] as $error)
                                    $errorStr .= $error['errorDescription'].';<br> ';


                                dev_log("return error",'wtcardCallback');
                                die('ERROR');
                            }

                            if(isset($create_card['userId']) && isset($create_card['cardDetail']['proxy'])){
                                $this->card->save_request($request_id, [
                                    'card_pan' => substr($create_card['cardDetail']['pan'], -4),
                                    'paid' => 1,
                                    'status' => 'processed',
                                    'processed' => date("Y-m-d H:i:s")
                                ]);


                                $new_card_data = [
                                    'user_id' => $user_id,
                                    'card_user_id' => $create_card['usrId'],
                                    'card_proxy' => $create_card['cardDetail']['proxy'],
                                    'card_type' => $create_card['cardDetail']['cardType']=='PLASTIC'?0:1, //'1 - plastic / 0 - virtual',
                                    'name_on_card' => $create_card['cardDetail']['nameOnCard'],
                                    'creation_date' => $create_card['cardDetail']['creationDate'],
                                    'txnId'  => $create_card['cardDetail']['txnId'],
                                    'pan' => $create_card['cardDetail']['pan'],
                                    'email' => $data->email,
                                    'status' => 0,
                                    'last_update' => date("Y-m-d H:i:s"),
                                    'last_balance' => 0
                                ];
                                dev_log("save: ".var_export($new_card_data, TRUE),'wtcardCallback');
                                $this->card->create_card($new_card_data);

                                $this->load->model('accaunt_model','accaunt');
                                $user = new stdClass();
                                $this->accaunt->set_user($user_id);
                                $this->accaunt->get_user($user);                                
                                
                                $this->load->library('Wtcard_api');
                                $wtcapi = new Wtcard_api(config_item('Wtcard_api_login'), config_item('Wtcard_api_pass'));
                                $data->email = $user->user->email;
                                $wtcapi->user_cards_import($create_card['cardDetail']['proxy'], $create_card['usrId'], $data->email, md5("{$create_card['usrId']}:{$create_card['cardDetail']['proxy']}:{$data->email}:silniy_kolobok13") );
                                die('OK');

                                

                            }else{
                                dev_log("no user_id or proxy",'wtcardCallback');
                                $this->card->save_request($request_id, [
                                    'decline_error' => json_encode(array("errorCode"=>"our api", "errorDescription"=>"I cannot find valid json answer, no userID or card proxy specified")),
                                    'declined' => date("Y-m-d H:i:s"),
                                    'status' => 'pay_declined'
                                ]);
                                die('ERROR');
                                
                            }
                            
                        }
                            
                        die('ERROR');
        
    }
   
    

}