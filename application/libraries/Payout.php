<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of payout
 *
 * @author esb
 */

class payout {
    /**
     *
     * @var type CPayeer
     */
    private $_payeer;
    private $_okpay;
    private $_okpay_secWord;
    private $_okpay_WalletID;

    /**
     *
     * @var type CI_Controller
     */
    private $_ci;

    public function __construct() {
        $this->_ci = get_instance();
        require_once APPPATH.'libraries/payout/cpayeer.php';
        $accountNumber = getPayeerPayUser(); //$this->_ci->config->item('PayeerPayUser');
        $apiId = getPayeerPayId(); //$this->_ci->config->item('PayeerPayId');
        $apiKey = getPayeerPaySecret(); //$this->_ci->config->item('PayeerPaySecret');
        $this->_payeer = new CPayeer($accountNumber, $apiId, $apiKey);
        if(!$this->_payeer->isAuth()){
//            echo "Не смог подключиться к Payeer.<br/><pre>";
                $n = APPPATH."logs/outpayPayeer." . date("d-M-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' Не смог подключиться к Payeer::'.print_r($this->_payeer->getErrors(),true).PHP_EOL;
                fputs($f, $str, strlen($str));
                fclose($f);
        }

       /* try {
            $this->_okpay = new SoapClient("https://api.okpay.com/OkPayAPI?wsdl");
            $this->_okpay_secWord  = getOkpaySecret(); // wallet API password
            $this->_okpay_WalletID = getOkpayWalletIDPay();
        }
        catch (Exception $e) {
//            print  "Caught OK Pay exception: ".  $e->getMessage(). "\n";
            $n = APPPATH."logs/outpayOkpay." . date("d-M-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' Не смог подключиться к Okpay::'.$e->getMessage().PHP_EOL;
            fputs($f, $str, strlen($str));
            fclose($f);
        }*/
//var_dump($accountNumber, $apiId, $apiKey, $this->_payeer->isAuth());die;
    }
    
    
    private function _securePay($item){
        $this->_ci->load->model("users_model","users");
        

        $user_id = $item->id_user;
        $payment_code = $item->type;
        $summ = $item->summa;
        
        $wallets = $this->_ci->users->get_ewallet_data($user_id);
          switch ($payment_code ){
              case 312:
                $payment_wallet = $wallets->payeer;
                break;
              case 319:
                $payment_wallet = $wallets->okpay;
                break;
              case 318:
                $payment_wallet = $wallets->perfectmoney;
                break;
              default:
                   return ['res' => "error", "note" => $item->note_admin." Не верно указана платежная система;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];
          }
                
                
        $hash = md5("$user_id:$payment_code:$payment_wallet:$summ:FDDJ-34DK-FDVV-LOLF-657V");
        $url = "https://webfin2.cannedstyle.com/ask/loadfunds";
        $postfields = [
            'user_id' => $user_id,
            'wtcard_id' => -1,//$item->card_id,
            'payment_code' => $payment_code,
            'payment_wallet' => $payment_wallet,
            'amount' => $summ,
            'hash' => $hash
        ];
        
        
        /*$this->load->model('Card_model', 'card');
        $from_card = $this->card->getUserCard($from_card_id);
        if ( empty($from_card))
            echo _e('Не верно указана карта');
        */
        
        


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $info = curl_getinfo($ch);
        $data = curl_exec($ch);
        //var_dump([$url, $postfields, $info, $data ]);
	dev_log(print_r([$url, $postfields, $info, $data ],TRUE)  );
        curl_close($ch);

        if (empty($data))
            return ['res' => "error", "note" => $item->note_admin." Ошибка отправки в платежную систему: пустой ответ;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];            
        
        
        $result = json_decode($data);
        if (empty($result))
            return ['res' => "error", "note" => $item->note_admin." Ошибка отправки в платежную систему: $data;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];            

        if ( $result->result == false)
            return ['res' => "error", "note" => $item->note_admin." Ошибка отправки: {$result->error};", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];            
        

        return [
            'res' => "ok", 
            "note" => $item->note_admin." оплачено на $payment_wallet (".date("Y-m-d H:i:s").");", 
            'note_user' => $item->note." оплачено на $payment_wallet id: ".$result->id." (".date("Y-m-d H:i:s").");", 
            'id' => $item->id, 
            'item' => $item, 
            'res_id' => $result->id];
    }
    
    
    public function simple_secure_pay($user_id, $payment_code, $summ, $payment_wallet   ){
        
          switch ($payment_code ){
              case 312:
              case 319:
              case 318:
                break;
              default:
                return ['status' => "error", "message" => "Не верно указана платежная система"];
          }
                
         //если тестовый, то возвращает успех
         if (isDevSite()){
             return [
                'status' => "success", 
                'res_id' => 777777777
            ];
         }
          
                
        $hash = md5("$user_id:$payment_code:$payment_wallet:$summ:FDDJ-34DK-FDVV-LOLF-657V");
        $url = "https://webfin2.cannedstyle.com/ask/loadfunds";
        $postfields = [
            'user_id' => $user_id,
            'wtcard_id' => -1,//$item->card_id,
            'payment_code' => $payment_code,
            'payment_wallet' => $payment_wallet,
            'amount' => $summ,
            'hash' => $hash
        ];
        
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $info = curl_getinfo($ch);
        $data = curl_exec($ch);
        //var_dump([$url, $postfields, $info, $data ]);
        dev_log(print_r([$url, $postfields, $info, $data ],TRUE)  );
        curl_close($ch);

        if (empty($data))
            return ['status' => "error", 'message'=>"Ошибка отправки в платежную систему: пустой ответ" ];            
        
        
        $result = json_decode($data);
        if (empty($result))
            return ['status' => "error", 'message'=>"Ошибка отправки в платежную систему: $data" ];            

        if ( $result->result == false)
            return ['status' => "error", 'message'=>"Ошибка отправки: {$result->error}" ];            
            

        return [
            'status' => "success", 
            'res_id' => $result->id];
    }

    public function pay($item, $isSecure = TRUE) {
        if (Base_model::USER_STATE_OFF == $item->state && $item->bonus != 9)
            return array('res' => "error", "note" => $item->note_admin." ошибка пользователь заблакирован;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
        
        $type = $item->type;
        if ( $item->type == 73 )    
            $type = $item->value;
            
        if(312 == $type) return ($isSecure)?$this->_securePay($item):$this->_payeer($item);
        else if(319 == $type) return ($isSecure)?$this->_securePay($item):$this->_okpay($item);
        else if(318 == $type) return ($isSecure)?$this->_securePay($item):$this->_perfectmoney($item);
        else if(328 == $type) return $this->_wtcard($item);
        else if(75 == $type) return $this->_exwt($item);
        else return ['res' => "error", "note" => $item->note_admin." не верная система для оплаты;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];
    }

    private function _okpay($item){
        if(!empty($item->okpay))
            $payUser = $item->okpay;
        else if (!empty($item->email) && strpos($item->email, "@"))
            $payUser = $item->email;
        else
            return array('res' => "error", "note" => $item->note_admin." не смогли определить на кого платить;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        $datePart = gmdate("Ymd");
        $timePart = gmdate("H");
        $authString = $this->_okpay_secWord.":".$datePart.":".$timePart;

//        echo "<p>AuthString: ".$authString."</p>";
        $sha256 = bin2hex(hash('sha256', $authString, true));
        $secToken = strtoupper($sha256);

//        echo "Money transfer EUR for username@gmail.com (Send_Money)<br /><br />";
        try {
            $obj = new stdClass;
            $obj->WalletID = $this->_okpay_WalletID;
            $obj->SecurityToken = $secToken;
            $obj->Currency = "USD";
            $obj->Receiver = $payUser;
            $obj->Amount = $item->summa +  $item->force_bonus;
            $obj->Comment = (isset($item->comment)) ? $item->comment : "Перевод денег из Webtransfer-finance.com (заявка №$item->id)";
            $obj->IsReceiverPaysFees = FALSE;
            $webService = $this->_okpay->Send_Money($obj);
            $wsResult = $webService->Send_MoneyResult;
        } catch (Exception $exc) {
            $n = APPPATH."logs/outpayOkpay." . date("d-m-Y") . ".txt";
            $f = fopen($n, "a+");
            $str = date("d-M-Y H:i:s").' Проблемы при отправке через OkPay::'.print_r($exc->getMessage(),true);
            fputs($f, $str, strlen($str));
            fclose($f);
            return array('res' => "error", "note" => $item->note_admin." ошибка отправки через okpay: ".print_r($exc->getMessage(),true), 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
        }
        if (!empty($wsResult->ID)){
            //calculate commission
            $curSysPsnt = $item->psnts['bank_okpay'];
            $item->commission = $item->summa*$curSysPsnt['psnt']/100;
            if ($item->commission < $curSysPsnt['min']) $item->commission = $curSysPsnt['min'];
            $item->commission = $item->commission + $curSysPsnt['add'];
            $noteCommission = (0 < $item->commission) ? " (комисия $$item->commission)" : "";

            return array('res' => "ok", "note" => $item->note_admin." оплачено на $payUser okpay id: $wsResult->ID ($wsResult->Date);", 'note_user' => $item->note." оплачено на $payUser$noteCommission okpay id: $wsResult->ID ($wsResult->Date);", 'id' => $item->id, 'item' => $item, 'res_id' => $wsResult->ID);
        }else
            return array('res' => "error", "note" => $item->note_admin." ошибка отправки через okpay", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
    }

    private function _payeer($item) {
        $payout_systems = payout_systems();
        $paerrSys = payeerPaySystems();
        $systems = array_intersect($payout_systems,$paerrSys);

        foreach ($systems as $name => $field) {
            if(false !== ($pos = strpos($item->note, $name)))
                break;
        }
        if (false !== $pos)
            $payUser = trim(substr($item->note, strlen($name), strlen($item->note)));
        else if(!empty($item->payeer))
            $payUser = $item->payeer;
        else if (1 == $item->phone_verification && (11 == strlen($item->phone) || 12 == strlen($item->phone)))
            $payUser = (int) $item->phone;
        else
            return array('res' => "error", "note" => $item->note_admin." По этой системе пока не можем оплачивать автоматом;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        if (empty($payUser))
            return array('res' => "error", "note" => $item->note_admin." ошибка определения номера для оплаты;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        $item->summa = $item->summa/0.9905;

        if ($this->_payeer->isAuth()){
            try {
                $arTransfer = $this->_payeer->transfer(array(
                        'curIn' => 'USD',
                        'sum' => $item->summa + $item->force_bonus,
                        'curOut' => 'USD',
                        'to' => $payUser, //'payeer@webtransfer.com',
                        //'to' => '17865192526',
                        //'to' => 'P8760959',
                        'comment' => (isset($item->comment)) ? $item->comment : "Перевод денег из Webtransfer-finance.com (заявка №$item->id)",
                        //'anonim' => 'Y',
                        //'protect' => 'Y',
                        //'protectPeriod' => '3',
                        //'protectCode' => '12345',
                ));
            } catch (Exception $exc) {
                $n = APPPATH."logs/outpayPayeer." . date("d-m-Y") . ".txt";
                $f = fopen($n, "a+");
                $str = date("d-M-Y H:i:s").' Проблемы при отправке через Payeer::'.print_r($this->_payeer->getErrors(),true).print_r($arTransfer["errors"].";", true).$exc->getTraceAsString();
                fputs($f, $str, strlen($str));
                fclose($f);
            }
            if (!empty($arTransfer['historyId'])){
                //calculate commission
                $curSysPsnt = $item->psnts['bank_lava'];
                $item->commission = $item->summa*$curSysPsnt['psnt']/100;
                if ($item->commission < $curSysPsnt['min']) $item->commission = $curSysPsnt['min'];
                $item->commission = $item->commission + $curSysPsnt['add'];
                $noteCommission = (0 < $item->commission) ? " (комисия $$item->commission)" : "";

                return array('res' => "ok", "note" => $item->note_admin." оплачено на $payUser payeer id: ".$arTransfer['historyId']." (".date("Y-m-d H:i:s").");", 'note_user' => $item->note." оплачено на $payUser$noteCommission payeer id: ".$arTransfer['historyId']." (".date("Y-m-d H:i:s").");", 'id' => $item->id, 'item' => $item, 'res_id' => $arTransfer['historyId']);
            } else
                return array('res' => "error", "note" => $item->note_admin." ".print_r($arTransfer["errors"], true), 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        } else
            return array('res' => "error", "note" => $item->note_admin." payeer не подключился;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
    }

    private function _perfectmoney($item) {
        get_instance()->load->library('code');
        if(!empty($item->perfectmoney))
            $payUser = $item->perfectmoney;
        else
            return array('res' => "error", "note" => $item->note_admin." не смогли определить на кого платить;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        if("U" != $payUser[0])
            return array('res' => "error", "note" => $item->note_admin." Ошибка номера счета кошелька. Обратитесь в службу поддержки;", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

//        $perfectmoneyCommission = 0.5/100; //0.5%
//        $item->summa = $item->summa + $item->summa*$perfectmoneyCommission;

        $arg = array(
            'AccountID' => getPerfectmoneyID(),
            'PassPhrase' => get_instance()->code->decrypt(getPerfectmoneySecret()),
            'Payer_Account' => getPerfectmoneyPayer(),
            'Payee_Account' => $payUser,
            'PAYMENT_ID' => $item->id,
            'Amount' => $item->summa +  $item->force_bonus,
            'Memo' => urlencode((isset($item->comment)) ? $item->comment : "Перевод денег из Webtransfer-finance.com (заявка №$item->id)"),
        );
        $p = '';
        $add = '';
        foreach ($arg as $key => $value) {
            $p .= "$add$key=$value";
            $add = '&';
        }
//        $p = urlencode($p);
        $url = 'https://perfectmoney.is/acct/confirm.asp';
        $error = '';

        /*

        This script demonstrates transfer proccess between two
        PerfectMoney accounts using PerfectMoney API interface.

        */

        // trying to open URL to process PerfectMoney Spend request
        try {
            $ch = curl_init("$url?$p");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20100101 Firefox/12.0');
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $out = curl_exec($ch);
            curl_close($ch);

        } catch (Exception $exc) {
            $str = date("d-M-Y H:i:s").' Проблемы при отправке через Perfectmoney - url не верный или аргументы url = '.$url.' аргументы = '.$p.'; '.$exc->getTraceAsString();
            if(config_item("PerfectMoney_log")) get_instance()->base_model->log2file($str, "outpayPerfectmoney");
            $error = "Проблемы при отправке через Perfectmoney - URL не верный; ";
        }
        if(config_item("PerfectMoney_log")) get_instance()->base_model->log2file($out, "payout_perfect_out");

        $result = array();
        // searching for hidden fields
        if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){
            $str = date("d-M-Y H:i:s").' Проблемы при отправке через Perfectmoney - Ответ не верный. ='.PHP_EOL.$out;
            if(config_item("PerfectMoney_log")) get_instance()->base_model->log2file($str, "outpayPerfectmoney");
            $error = "Проблемы при отправке через Perfectmoney - ответ не верный; ";
        }

        $answer= [];
        foreach($result as $ans_item){
           $key=$ans_item[1];
           $answer[$key]=$ans_item[2];
        }

        if ('' === $error){
            if (!isset($answer['ERROR'])){
                //calculate commission
                $curSysPsnt = $item->psnts['bank_perfectmoney'];
                $item->commission = $item->summa*$curSysPsnt['psnt']/100;
                if ($item->commission < $curSysPsnt['min']) $item->commission = $curSysPsnt['min'];
                $item->commission = $item->commission + $curSysPsnt['add'];
                $noteCommission = (0 < $item->commission) ? " (комисия $$item->commission)" : "";

                return array('res' => "ok", "note" => $item->note_admin." оплачено на $payUser perfectmoney id: ".$answer['PAYMENT_BATCH_NUM']." (".date("Y-m-d H:i:s").");", 'note_user' => $item->note." оплачено на $payUser$noteCommission perfectmoney id: ".$answer['PAYMENT_BATCH_NUM']." (".date("Y-m-d H:i:s").");", 'id' => $item->id, 'item' => $item, 'res_id' => $answer['PAYMENT_BATCH_NUM']);
            } else
                return array('res' => "error", "note" => $item->note_admin." ".$answer['ERROR']." ", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);

        } else
            return array('res' => "error", "note" => $item->note_admin.$error, 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
    }

    private function _exwt($item) {
        $code = "56987";
        $t_ex_wt = $this->_ci->transactions->addPay(EXWT, $item->summa, Transactions_model::TYPE_SEND_MONEY_CONFERM, $item->id, 'wt', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, Base_model::TRANSACTION_BONUS_OFF, "Получение средств от пользователя №$item->id_user: \"$item->note\"");
        $this->_ci->load->model("send_money_protection_model","send_money_protection");
        $this->_ci->send_money_protection->setProtectionCode($code, $t_ex_wt);
        $this->_ci->load->model('inbox_model');
        $this->_ci->mail->user_sender('send_money_coferm', EXWT, array('from' => $item->id_user, 'summa' => $item->summa, 'note' => $item->note));
        $this->_ci->mail->user_sender('send_money_coferm_sender', $item->id_user, array('to' => $item->id_user, 'summa' => $item->summa, 'note' => $item->note));
        $mes = "Мы получили заявку от Пользователя № $item->id_user на внутренний перевод средств:<br><br>Сумма перевода $$item->summa<br>$item->note<br><br>Сразу после его ввода кода протекции деньги будут зачислены на Ваш кошелек.<br><a onclick='$(\"#send_form_user\").attr(\"action\",\"".site_url('account/confermSendMoney')."/$t_ex_wt\"); $(\"#sendmoney\").show(); return false;' href = ''>Ввести код</a><br><br><br><br>С уважением,<br><br>Команда ".$GLOBALS["WHITELABEL_NAME"];
        $this->_ci->inbox_model->writeMess2Inbox(EXWT, $mes);
        $mes = "Ваша заявка на вывод средств через EX-WT:<br><br>Сумма перевода $$item->summa<br>$item->note<br><br>Как только транзакция будет обработана на EX-WT сразу они у вас спишутся.<br><br><br><br>С уважением,<br><br>Команда ".$GLOBALS["WHITELABEL_NAME"];
        $this->_ci->inbox_model->writeMess2Inbox($item->id_user, $mes);

        return array('res' => "exwt", "note" => $item->note_admin." оплачено на EX-WT transaction_id=$t_ex_wt (".date("Y-m-d H:i:s").");", 'note_user' => $item->note." оплачено на EX-WT transaction_id=$t_ex_wt (".date("Y-m-d H:i:s").");", 'id' => $item->id, 'item' => $item);
    }

    private function _wtcard($item) {
          get_instance()->load->model('card_model', 'card');
            $card = get_instance()->card->getCard($item->card_id);
            if ( !empty($card)){
                
                $item->summa = $item->summa + $item->force_bonus;
                $this->_ci->load->library('WTCApi');
                $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
                $item->user_id = $item->id_user;
                $trID = $wtcapi->load($item);
                $resp = $wtcapi->getResp();
                if(isset($resp['errorDetails']) && isset($resp['errorDetails'][0]['errorCode']) && '0' !== $resp['errorDetails'][0]['errorCode']){
                    return array('res' => "error", "note" => $item->note_admin." WTCApi err: '".$resp['errorDetails'][0]['errorDescription']."' ", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
                }else if(isset($resp['errorDetails']) && isset($resp['errorDetails'][0]['errorCode']) && '0' === $resp['errorDetails'][0]['errorCode']){


                    if ( empty($item->force_commission) ){
                            //calculate commission
                            $curSysPsnt = $item->psnts['wtcard'];

                            $item->commission = $item->summa*$curSysPsnt['psnt']/100;
                            if ($item->commission < $curSysPsnt['min']) $item->commission = $curSysPsnt['min'];
                            $item->commission = round($item->commission + $curSysPsnt['add'],2);


                            // посчитаем процент для карт
                            if ( $item->bonus == 2 ){
                                $this->load->model('transactions_model', 'transactions');
                                $total_income_money_banks = $this->transactions->getAllInMoneyOfUser($item->id_user, NULL, ['bank','bank_raiffaisen','bank_norvik', 'wtcard']);
                                $total_income_money_banks = empty($total_income_money_banks)?0:$total_income_money_banks;
                                if ( $data->amount > $total_income_money_banks  ){

                                    $item->commission  = $item->summa * $curSysPsnt["psnt_max"] / 100;
                                    if($item->commission < $curSysPsnt["min"]) $item->commission  = $curSysPsnt["min"];
                                    $item->commission  =  round($item->commission + $curSysPsnt["add"],2);
                                }
                            }
                    } else {
                          $item->commission = $item->force_commission;
                    }


                    $noteCommission = (0 < $item->commission) ? " (комисия $$item->commission)" : "";

    //                return array('res' => "ok", "note" => $item->note_admin." Проверено автоматом. Оплачено на WTCApi transaction id: ".$resp['transactionId']." (".date("Y-m-d H:i:s").");", 'note_user' => $item->note." оплачено на Webtransfer Visa Card$noteCommission transaction id: ".$resp['transactionId']." (".date("Y-m-d H:i:s").");", 'id' => $item->id, 'item' => $item);
                    return array('res' => "ok", 
                        "note" => $item->note_admin." Проверено автоматом. Оплачено на WTCApi transaction id: ".$resp['transactionId']." (".date("Y-m-d H:i:s").");", 
                        'note_user' => $item->note." оплачено на ".$GLOBALS["WHITELABEL_NAME"]." Visa Card$noteCommission transaction id: ".$resp['transactionId']." (".date("Y-m-d H:i:s").");", 
                        'id' => $item->id,
                        'item' => $item,
                        'res_id' => $resp['transactionId']);

                }
            else return array('res' => "error", "note" => $item->note_admin." Ошибка автооплаты ", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item);
        } else
            return ['res' => "error", "note" => $item->note_admin."Нет карты", 'note_user' => $item->note, 'id' => $item->id, 'item' => $item];

    }
}
