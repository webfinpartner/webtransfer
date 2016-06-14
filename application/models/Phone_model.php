<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Phone_model extends CI_Model{

    protected $secret_key,
            $from,
            $max_sms_count,
            $max_sms_input_attempts,
            $expiration_time; //in sec
    static $last_error;
    private $without_phone_verification = TRUE;

    public $table_phone_providers_list = 'phone_providers_list';

    function __construct() {
        parent::__construct();

        $this->secret_key = "a'Ф\"N^в)$#kO;п*t"; // Ключ шифрования
        $this->from = $GLOBALS["WHITELABEL_NAME"];
        $this->max_sms_count = 3;
        $this->max_sms_input_attempts = 3;
        $this->expiration_time = 10 * 60; // 5 min
    }

    public function get_after_text( $phone_with_code )
    {
        $this->load->model('message_ads_text_model','message_ads_text');

        return $this->message_ads_text->get_ad_text_by_phone_with_code( $phone_with_code );
    }

    //********************SERVICES******************************/
    public function onBalance_prostor( $log = FALSE, $return_value = FALSE )
    {
        $login = "Webtransfer";
        $pass = '';

        $url = "http://api.prostor-sms.ru/messages/v2/balance/?login=".urlencode($login).'&password='.urlencode($pass);

        //set the url, number of POST vars, POST data
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //  curl_setopt($ch,CURLOPT_HEADER, false);

        if(false === ($Result = curl_exec($ch))) {
//            throw new Exception('Http request failed');
            return FALSE;
        }

        $balance = explode(';', $Result);
        if( $log ) var_dump($balance);
        if( !is_array( $balance ) )
        {
            return FALSE;
        }

        $balance_debit = floatval( $balance[1] );
        $balance_credit = floatval( $balance[2] );

        if( TRUE === $return_value )
        {
            return $balance_debit;
        }

        if( $balance_debit < 10.0 )
        {
            return FALSE;
        }

        return TRUE;
    }

    public function sendCode_prostor($text, $phone, $from = '')
    {

        $login = "Webtransfer";
        $pass = '';
        $before_text = $GLOBALS["WHITELABEL_NAME"].' code: ';
        $sender = 'Webtransfer';//$GLOBALS["WHITELABEL_NAME"];//поменяешь на другого - не будут приходить смс с тестовых

        $url = "http://$login:$pass@gate.prostor-sms.ru/send/?".
                "phone=%2B".urlencode($phone).
                '&text='.urlencode( $before_text . $text ).
                '&sender='.urlencode($sender);

        //set the url, number of POST vars, POST data
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //  curl_setopt($ch,CURLOPT_HEADER, false);

        if(false === ($Result = curl_exec($ch))) {
//            throw new Exception('Http request failed');
            return array('error' => 999, 'code' => _e("Ошибка EP/EXC сервиса. Попробуйте позже.") );
        }

        switch( $Result )
        {
            case 'NULL':
                $success = _e('Сообщение принято к отправке');
                break;

            case 'invalid mobile phone':
                $error = _e('Неверный номер.');
                $code = 999;
                break;
            case 'text is empty':
            case 'sender address invalid': //— указано неверно заданное обратное слово
            case 'wapurl invalid': //— указана неверно заданная ссылка
            case 'invalid schedule time format': //— неверный формат даты отложенной отправки сообщения
            case 'invalid status queue name': //— неверное название очереди статусов сообщений

                $error = _e('Ошибка отправки сообщения.');
                $code = 999;

                break;
            case 'not enough credits':
                $error = _e('Ошибка сервиса P/cred');
                $code = 999;

                break;

            default:
                //1553539685=accepted
                $parts = explode( '=', $Result );

                if( is_numeric( $parts[0] ) && $parts[1] == 'accepted' )
                {
                    $success = _e('Сообщение принято к отправке');
                    $message_id = $parts[0];
                }else
                    $error = _e('Ошибка сервиса cred.');
                break;
        }

        curl_close($ch);

        $result =  array( 'error' => $error, 'success' => $success, 'code' => $code, 'message_id' => $message_id );

        return $result;
    }

    public function sendCode_epochta($text, $phone) {

        if (empty($text) || empty($phone) || $phone == 0)
            return array('error' => _e('Заданы не все параметры'));

        $user_name = 'office@com';
        $user_pass = '';

        $src = '<?xml version="1.0" encoding="UTF-8"?>
        <SMS>
        <operations>
        <operation>SEND</operation>
        </operations>
        <authentification>
        <username>'.$user_name.'</username>
        <password>'.$user_pass.'</password>
        </authentification>
        <message>
        <sender>'.$GLOBALS["WHITELABEL_NAME"].'</sender>
        <text>'.$GLOBALS["WHITELABEL_NAME"].' Code: '.$text.'</text>
        </message>
        <numbers>
        <number>'.$phone.'</number>
        </numbers>
        </SMS>';


        $Curl = curl_init();
        $CurlOptions = array(
        CURLOPT_URL=>'http://atompark.com/members/sms/xml.php',
        CURLOPT_FOLLOWLOCATION=>false,
        CURLOPT_POST=>true,
        CURLOPT_HEADER=>false,
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_CONNECTTIMEOUT=>15,
        CURLOPT_TIMEOUT=>100,
        CURLOPT_POSTFIELDS=>array('XML'=>$src),
        );
        curl_setopt_array($Curl, $CurlOptions);


        if(false === ($Result = curl_exec($Curl))) {
//            throw new Exception('Http request failed');
            return array('error' => 999, 'code' => _e("Ошибка EP/EXC сервиса. Попробуйте позже.") );
        }

        curl_close($Curl);

        $code = 0;
        $error = $success = '';

        if( strpos( $Result, '<status>1</status>' ) !== FALSE )
        {
            $success = _e('Сообщение принято к отправке');
        }else{
            $error = _e('Ошибка отправки сообщения.');
            $code = 999;
        }

        $result =  array( 'error' => $error, 'success' => $success, 'code' => $code );

        return $result;
    }

    private function sendCode_Smsru($text, $phone) {
        if (empty($text) || empty($phone) || $phone == 0)
            return array('error' => _e('Заданы не все параметры'));

        $ch = curl_init("http://sms.ru/sms/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "api_id" => "",
            "to" => $phone,
            "from" => $this->from,
//            "test" => 1,
            "text" => $text
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        if (strrpos(' ', $response) !== false) {
            $sess = explode(" ", $response);
            $code = intval($sess[0]);
        }
        else
            $code = $response;

        $error = null;
        $success = null;
        switch ($code) {
            case 100: $success = _e('Сообщение принято к отправке');
                break;
            case 202: $error = _e('Неправильно указан получатель');
                break;
            default:
                $code = 999;
                $error = sprintf(_e("Ошибка S/%s сервиса. Попробуйте позже."),$code);
                break;
        }

        return array('error' => $error, 'success' => $success, 'code' => $code );
    }

    private function sendCode_Clickatel($text, $phone) {
        if (empty($text) || empty($phone) || $phone == 0)
            return false;

        $user = "webtransfer";
        $password = "";
        $api_id = ""; // новое  АПИ - деньги списывает а доставки СМС нетуHTTP API, created 30/05/2014 for wtf-test.org
        // $api_id = "3241106"; // API ID - старое проверенное
        $from = $this->from; // От этого имени пользователи будут получать СМС
        $baseurl = "http://api.clickatell.com";

        $hash_code = urlencode($text);   // Сюда вписать текст СМС для отправки
        // $phone = "380967253626";			// Номер получателя СМС
        // auth call
        $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

        // do auth call
        $ret = file($url);

        $error = null;
        $success = null;
        // explode our response. return string is on first line of the data returned
        $sess = explode(":", $ret[0]);
        $code = 0;
        if ($sess[0] == "OK") {

            $sess_id = trim($sess[1]); // remove any whitespace

            $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$phone&text=$hash_code&from=$from";

            // do sendmsg call
            $ret = file($url);
            $send = explode(":", $ret[0]);

            if ($send[0] == "ID") {
                $success = _e('Сообщение принято к отправке');
            } else {
                $error = _e("Ошибка C/20. Попробуйте позже.");


                ob_start();
                var_dump($send);
                var_dump($ret);
                $result = ob_get_clean();

                $this->accaunt->add_to_log( 'clickatell',  $result );
            }
        } else {
            $code = 999;
            $error = _e("Ошибка C/10. Попробуйте позже.");
        }

        return array('error' => $error, 'success' => _e('Сообщение принято к отправке'), 'code' => $code);
    }

    private function onBalance_Nexmo( $log = FALSE, $return_value = FALSE ) {

        $api_key = "";
        $api_secret = "";


        $baseurl = "https://rest.nexmo.com";
        $url = "{$baseurl}/account/get-balance?api_key={$api_key}&api_secret={$api_secret}";

        // do auth call
        $ret_src = file($url);

        if (empty($ret_src) || !isset($ret_src[0]) )
        {
            return false;
        }

        $balance = json_decode( $ret_src[0] );
        if( empty($balance) || !isset($balance->value) )
        {
            return false;
        }

        return $balance->value;
    }
    private function sendCode_Nexmo($text, $phone, $from = null) {
        if (empty($text) || empty($phone) || $phone == 0)
            return false;

        $api_key = "";
        $api_secret = "";

        $from = '447520615146';
        if( substr($phone) == 1 ) $from = 12013514506;
        //if( $from == null ) $from = $this->from; // От этого имени пользователи будут получать СМС

        $baseurl = "https://rest.nexmo.com";

        $text = urlencode($text);

        $url = "{$baseurl}/sms/json?api_key={$api_key}&api_secret={$api_secret}&type=unicode&from={$from}&to={$phone}&text={$text}";
        // do auth call
        $ret = file($url);

        if (empty($ret) || !isset($ret[0]) || !is_array($ret))
            return false;

        $send = json_decode($ret[0]);
//        var_dump( $send );
        $code = -1;
        $message_id = 0;
        $balance = 0;
        $cost = 0;
        if (isset($send->messages) && isset($send->messages[0]) )
        {
            if( isset($send->messages[0]->status) )
            {
                $code = $send->messages[0]->status;
                $message_id = $send->messages[0]->{'message-id'};
                $balance = $send->messages[0]->{'remaining-balance'};
                $cost = $send->messages[0]->{'message-price'};
            }

//            if( $phone == 79118242913 )
//                var_dump( $send->messages);
        }

        $error = null;
        $success = null;
        switch ($code) {
            case -1: $success = _e('Ошибка отправки сообщения.');
                break;
            case 0: $success = _e('Сообщение принято к отправке');
                break;

            default:
                $code = 999;
                $error = sprintf(_e("Ошибка N/%s сервиса. Попробуйте позже."),$code);
                break;
        }

        return array('error' => $error, 'success' => $success,
                    'code' => $code,
                    'message_id' => $message_id,
                    'balance' => $balance,
                    'cost' => $cost);
    }

    public function nexmo_text_to_voice($text, $number, $lang = 'en-en')
    {
        if( empty( $text ) || empty( $number ) )
            return FALSE;

        $baseurl = "https://api.nexmo.com";

        $data = array();
        //artem.prof@gmail.com
        $data['api_key'] = '';
        $data['api_secret'] = '';


        $data['to'] = $number;  //Required. Phone number in international format and one recipient per request.
                            //Ex: to=447525856424 when sending to UK
        $data['from'] = '';           //Optional. A voice-enabled virtual number associated with your Nexmo account.
        $data['text']       = $text;    //Required. Body of the text message (with a maximum length of 1500 characters), UTF-8 and URL encoded value. Check our tips.
        $data['lg']         = $lang;  //Optional. The language used to read the message, expected values are below and en-us "US english" is the default.
        $data['voice']      = 'male'; //Optional. The voice to be used female (default) or male
        $data['repeat']     = 1;    //Optional. Define how many times you want to repeat the text message (default is 1 , maximum is 10).
        //$data['callback']   = '';   //Optional. A URL to which Nexmo will send a request when the call ends to notify your application.

        //machine_detection	Optional. How to behave when an answering machine is detected. If set to true we try to beat the machine and play the message after the beep. If no beep we will close the call. If set to hangup, the call hangs up immediately. The status value in the callback url is set to machine when we close the call.
        //machine_timeout	Optional. Time allocated to analyse if the call has been answered by a machine. Accepts values in ms between 400ms, to maximum 10000ms,(default). Wrong values, will be set to default.
        //callback	Optional. A URL to which Nexmo will send a request when the call ends to notify your application.
        //callback_method	Optional. The HTTP method for your callback. Must be GET (default) or POST.

        $params = http_build_query($data);

        $url = "{$baseurl}/tts/json?$params";

        // do auth call
        $ret = file($url);

        //var_dump( $ret );

        if (empty($ret) || !isset($ret[0]) || !is_array($ret))
            return false;

        //$ret = $ret[0];
        //array(1) { [0]=> string(101) "{"call_id":"6be05ba5972a122652efab0660c2e470","to":"79118434231","status":"0","error_text":"Success"}" }
        $send = json_decode($ret[0]);
//        var_dump( $send );
        $code = -1;
        $message_id = 0;
        $balance = 0;
        $cost = 0;

        if( isset($send->status ) )
        {
            $code = $send->status;
            $message_id = $send->{'call_id'};
            //$balance = $send->messages[0]->{'remaining-balance'};
            //$cost = $send->messages[0]->{'message-price'};
        }

        $error = null;
        $success = null;
        switch ($code) {
            case -1: $success = _e('Ошибка отправки сообщения.');
                break;
            case 0: $success = _e('Сообщение принято к отправке');
                break;
            case 17: $success = _e('На данный номер невозможно сделать звонок.');
                break;

            default:
                $code = 999;
                $error = sprintf(_e("Ошибка N/%s сервиса. Попробуйте позже."),$code);
                break;
        }

        return array('error' => $error, 'success' => $success,
                    'code' => $code,
                    'message_id' => $message_id,
                    'balance' => $balance,
                    'cost' => $cost);

    }

    private function sendCode_Twilio($sms_body, $phone, $from = null) {
        if (empty($sms_body) || empty($phone) || $phone == 0)
            return false;


        $this->load->library('Twilio/Services_Twilio');


        $AccountSid = "";
        $AuthToken = "";

        $client = new Services_Twilio($AccountSid, $AuthToken);
        $phone_number = '+'.$phone;
        $ret = $client->account->messages->sendMessage( $from,$phone_number, $sms_body );

        if (empty($ret))
            return false;

        $send = json_decode($ret);


        $error = null;
        $success = null;
        $code = 0;
        if (isset($send->error_code) || isset($send->error_message) )
        {
            $code = 999;
            $error = sprintf(_e("Ошибка T/%s сервиса. Попробуйте позже."),$code);
        }else
            if( isset( $send->status ) && $send->status == 'queued' )
            {
                $success = _e('Ошибка отправки сообщения.');
            }

        return array('error' => $error, 'success' => $success, 'code' => $code);
    }


    //**********************SENDING & ROUTING****************************/

    public function sendAdminCode($hash_code, $phone, $times, $page_link_hash = '', $page_hash = '') {
        return $this->sendCode($hash_code, $phone, $times, $page_link_hash, $page_hash );
    }

    public function getServiceList( $active = 0  )
    {
    //    $this->db->cache_on();

        $res = $this->db->where( 'active', $active )
                 ->get( $this->table_phone_providers_list )
                 ->result();

   //     $this->db->cache_off();

        if( empty( $res ) )
        {
            return FALSE;
        }
        return (array)$res;
    }
    public function getService( $phone, $times, $repeat = 0, $disableAdminNotification = FALSE  )
    {
//        echo "$times, $repeat";


        $this->services = array();
        $i = 0;
        $i = 1;
        $this->services[$i] = array();    //4
        $this->services[$i]['name'] = 'Twillo';
        $this->services[$i]['from'] = '+12025248719';
        $this->services[$i]['avaliable'] = TRUE;

        $i = 2;
        $this->services[$i] = array();    //3
        $this->services[$i]['name'] = 'Smsru';
        $this->services[$i]['from'] = '';
        $this->services[$i]['avaliable'] = TRUE;

        $i = 3;
        $this->services[$i] = array();    //1
        $this->services[$i]['name'] = 'Clickatel';
        $this->services[$i]['from'] = '';
        $this->services[$i]['avaliable'] = TRUE;

        $i = 4;
        $this->services[$i] = array();    //2
        $this->services[$i]['name'] = 'Nexmo';
        $this->services[$i]['from'] = '';
        $this->services[$i]['avaliable'] = TRUE;

        $i = 5;
        $this->services[$i] = array();    //2
        $this->services[$i]['name'] = 'epochta';
        $this->services[$i]['from'] = '';
        $this->services[$i]['avaliable'] = TRUE;

        $i = 6;
        $this->services[$i] = array();    //2
        $this->services[$i]['name'] = 'prostor';
        $this->services[$i]['twin'] = $this->services[5];
        $this->services[$i]['from'] = '';
        $this->services[$i]['avaliable'] = TRUE;

        //$this->services = $this->getServiceList( 0  );

        $oneNum = substr($phone, 0, 1);
        $twoNum = substr($phone, 0, 2);
        $threeNum = substr($phone, 0, 3);


        #set service on common conditions
//        $service = $this->services[4];
//        $service['from'] = '447520615146';
//        if( $oneNum == '1' )
//        {//if America
//            $service['from'] = '12402240018';
//        }

        #America and Europe
        $service = $this->services[3];
        $service['from'] = '';

        if( $oneNum == '1' || $twoNum == '51' )
        {
//            $service = $this->services[1];
//            $service['from'] = '+12025248719';
            $service = $this->services[4];
//            $service['from'] = '447520615146';
//            if( $oneNum == '1' )
                $service['from'] = '12402240018';
        }


        #set service for Russia and some some countries of the USSR
        if ( ($oneNum == '7' || $twoNum == '38' || $twoNum == '37' || $twoNum == '91' ||//$twoNum == '49' ||
            $threeNum == '372' || $threeNum == '995') && $threeNum != '380' && $threeNum != '374'
        ){
            $i = 6;
            $service = $this->services[$i];
            unset( $this->services[$i] );

            $balance = $this->onBalance_prostor(FALSE,TRUE);

            $service['balance'] = $balance;

            if( $balance < 20 )
            {
                //if( $balance > 95 && $balance < 100 )
                //    $this->sendCodeOne('Prostor balanse is '.$balance, '79118242913');

                $service = $this->services[3];
                unset( $this->services[3] );
            }

            #next trying
            if( $times > 2 || $repeat > 0 )
            {
                $service = $this->services[3];
            }

        }



        #set service for russian and some others countries of the USSR
        if ( 00 & $threeNum == '996' ){
            $i = 3;
            $service = $this->services[$i];
            unset( $this->services[$i] );

            if( $times > 1 || $repeat > 0 )
            {
                $service = $this->services[1];
            }
            if( $times > 2 || $repeat > 0 )
            {
                $service = $this->services[6];
            }
        }


        if ( !$disableAdminNotification){
            $this->load->model( 'System_events_model', 'system_events' );
            switch( $service['name'] )
            {
                case 'prostor':
                    $balance = $this->onBalance_prostor(FALSE,TRUE);
                    if( $balance > 5990 && $balance < 6000 )
                    {
                        //$this->sendCodeOne('Prostor balanse is '.$balance, '79118242913');
                        $this->system_events->notify(System_events_model::LOW_BALANCE_EVENT, 'WARN_PROSTOR', array(  $service['name'], $balance ) );
                    }
                    break;
                case 'Nexmo':
                    $balance = $this->onBalance_Nexmo(FALSE,TRUE);

                    if( $balance >= 49.7 && $balance <= 50 )
                    {
                        //$this->sendCodeOne('Nexmo balanse is '.$balance, '79118242913');
                        $this->system_events->notify(System_events_model::LOW_BALANCE_EVENT, 'WARN_NEXMO', array(  $service['name'], $balance ) );
                    }

                    break;
            }
        }



        /*
        if( $threeNum == '370' || $threeNum == '371' || $threeNum == '375')
        {
            $i = 4;
            $service = $this->services[$i];
            unset( $this->services[$i] );

            if( $times > 0 || $repeat > 0 )
            {
                $service = $this->services[3];
            }else
                if( $times > 1 )
                {
                    $service = $this->services[4];
                }
        }else
        if ($oneNum == '7' || $twoNum == '38' || $twoNum == '49' || $twoNum == '37' || $twoNum == '91' ||
            $twoNum == '44' || $threeNum == '372' || $threeNum == '374' || $threeNum == '995'
        ){
            $i = 1;
            $service = $this->services[$i];
            unset( $this->services[$i] );

//            $balance = $this->onBalance_prostor(FALSE,TRUE);
//            $service['balance'] = $balance;
//
//            if( $balance > 2995 && $balance < 3000 )
//            {
//                $this->sendCodeOne('Prostor balanse is '.$balance, '79118242913');
//            }
//
//            if( $balance > 1995 && $balance < 2000 )
//            {
//                $this->sendCodeOne('Prostor balanse is '.$balance, '79118242913');
//                unset( $service['balance'] );
//                $service = $this->services[4];
//                unset( $this->services[4] );
//            }

            if( $times > 0 || $repeat > 0 )
            {
                $service = $this->services[3];
            }else
                if( $times > 1 )
                {
                    $service = $this->services[4];
                }

        }else //99
        {
            $i = 4;
            $service = $this->services[$i];
            unset( $this->services[$i] );

            if( $oneNum == '1' )
            {//if America
                $service['from'] = '12402240018';
            }


            if( $times > 0 || $repeat > 0 )
            {
                $service = $this->services[1];
            }
        }

        */

        if( $phone == 79118242913 && 0)
        {
            $this->onBalance_prostor(true);
            die;
            $service = $this->services[6];
//        echo "1";

            $fnc_name = "onBalance_{$service['name']}";
            if( is_callable( array($this, $fnc_name) ) )
            {
//                echo "2";
                $services_twin = null;
                if( isset( $service['twin'] ) )
                {
                    $services_twin = $service['twin'];
                }
//                echo "3";
                if( $this->$fnc_name() === FALSE && $services_twin !== null )
                {
                    $service = $services_twin;
                }
            }

//            var_dump($service);
        }

//        if( $repeat > 0 )
//        {
//            shuffle( $this->services );
//            $service = $this->services[$repeat];
//        }elseif( $times > 0 && $times < count( $this->services ) )
//        {
//            shuffle( $this->services );
//            $service = $this->services[$times];
//        }
//
//        if( $service['avaliable'] === FALSE )
//        {
//            shuffle( $this->services );
//            $count_services = count( $this->services );
//            $rand = rand( 1, $count_services );
//            $service = $this->services[$rand];
//        }

        return $service;
    }

    /*
     * Отправляет простое сообщение
     */
    private function _sendSimpleMessage($text, $phone, $disableAdminNotification = FALSE)
    {
        $times = 0;
        $all_repeats = 4;
        $repeat = $all_repeats;
        $res = array();

        $service_res = $this->getService( $phone, $times, $all_repeats - $repeat, $disableAdminNotification );

        if( empty( $service_res ) || !isset( $service_res['name'] ) )
                return $res;

        $from = $service_res['from'];
        $service = $service_res['name'];
        $res['service_name'] = $service;

        if( empty( $service ) )
            return $res;

        $funcName = "sendCode_$service";
        if( !is_callable(array($this, $funcName)) )
            return $res;

        $sms_text = $text;
        $send_res = $this->$funcName($sms_text, $phone, $from );
        $res = array_merge($send_res, $res );

        if( isset( $service_res['balance'] ) )
            $res['service_balance'] = $service_res['balance'];

        if( isset( $res['code'] ) && $res['code'] == 999 )
            return $res;

        return $res;

    }

    /*
     * Отправляет сообщение администраторам. Не учитывается количество попыток.
     */
    public function sendAdminMessage($text, $phone )
    {
            $disableAdminNotification = TRUE;

            $res = $this->_sendSimpleMessage($text, $phone, $disableAdminNotification);
            $this->load->model('send_messages_history_model','send_messages_history');

            $page_link_hash = '';
            $page_hash = '';
            $message_id_in_service = null;
            $service_name = '';
            $service_balance = 0;
            $cost = 0;
            $delivery_status = Send_messages_history_model::MESSAGE_STATUS_FAIL;

            if( isset($res['success']) && !empty( $res['success'] ) )
            {
                if( isset($res['message_id']) && !empty( $res['message_id'] ) )
                {
                    $message_id_in_service = $res['message_id'];
                }
                if( isset($res['service_name']) && !empty( $res['service_name'] ) )
                {
                    $service_name = $res['service_name'];
                }

                if( isset($res['service_balance']) && !empty( $res['service_balance'] ) )
                {
                    $service_balance = $res['service_balance'];
                }

                if( isset($res['cost']) && !empty( $res['cost'] ) )
                {
                    $cost = $res['cost'];
                }

                $delivery_status = Send_messages_history_model::MESSAGE_STATUS_SUCCESS;

            }

            $type_id = Send_messages_history_model::MESSAGE_TYPE_SMS;
            $user_id = 0;

            $send_messages_history_insert = $this->send_messages_history->addMessage( $user_id, $type_id, $phone, $text,
                                                        $page_link_hash, $page_hash, $delivery_status,
                                                        $service_name, $service_balance,  $cost, $message_id_in_service );


        return $res;

    }

    public function sendCode($hash_code, $phone, $times, $page_link_hash = '', $page_hash = '' )
    {

        $res = $this->sendCodeOne($hash_code, $phone, $times);


            $this->load->model('send_messages_history_model','send_messages_history');

//            $res = array(
//                        "error" => NULL,
//                        "success" => "Сообщение принято к отправке",
//                        "code" => NULL,
//                        "message_id"=> "1557484525"
//                );

            $message_id_in_service = null;
            $service_name = '';
            $service_balance = 0;
            $cost = 0;
            $delivery_status = Send_messages_history_model::MESSAGE_STATUS_FAIL;
            $user_id = get_current_user_id();

            if( isset($res['success']) && !empty( $res['success'] ) )
            {
                if( isset($res['message_id']) && !empty( $res['message_id'] ) )
                {
                    $message_id_in_service = $res['message_id'];
                }
                if( isset($res['service_name']) && !empty( $res['service_name'] ) )
                {
                    $service_name = $res['service_name'];
                }

                if( isset($res['service_balance']) && !empty( $res['service_balance'] ) )
                {
                    $service_balance = $res['service_balance'];
                }

                if( isset($res['cost']) && !empty( $res['cost'] ) )
                {
                    $cost = $res['cost'];
                }

                $delivery_status = Send_messages_history_model::MESSAGE_STATUS_SUCCESS;

            }

            $type_id = Send_messages_history_model::MESSAGE_TYPE_SMS;


            $this->load->model('accaunt_model', 'accaunt');


            //$user_id, $type_id, $recipient_account, $message_text,
//                                $page_link_hash, $page_hash, $delivery_status,
//                                $service_name = '', $service_balance = 0, $cost = 0, $message_id_in_service = null )
            $send_messages_history_insert = $this->send_messages_history->addMessage( $user_id, $type_id, $phone, $hash_code,
                                                        $page_link_hash, $page_hash, $delivery_status,
                                                        $service_name, $service_balance,  $cost, $message_id_in_service );


//            die;


        return $res;
    }
    public function sendCodeOne($hash_code, $phone, $times) {

//        if( ENVIRONMENT !== 'production' )
//            return TRUE;

        $text_after = $this->get_after_text( $phone );
        $text_before = $GLOBALS["WHITELABEL_NAME"].' code:';
        $all_repeats = 4;
        $repeat = $all_repeats;
       // do
       // {
            if( $repeat <= 0 )
            {
                $res = array();
                return $res;
            }

            $service_res = $this->getService( $phone, $times, $all_repeats - $repeat );

            if( empty( $service_res ) || !isset( $service_res['name'] ) )
            {
                $repeat--;
                return $res;
            }


            $from = $service_res['from'];
            $service = $service_res['name'];
            $res['service_name'] = $service;


            if( empty( $service ) )
            {
                $repeat--;
                return $res;
            }

            $funcName = "sendCode_$service";

            if( !is_callable(array($this, $funcName)) )
            {
                $repeat--;
                return $res;
            }

            $sms_text = $text_before . $hash_code . $text_after;
            $res = $this->$funcName($sms_text, $phone, $from );



            if( isset( $service_res['balance'] ) )
            {
                $res['service_balance'] = $service_res['balance'];
            }
            if( isset( $res['code'] ) && $res['code'] == 999 )
            {
                $repeat--;
                return $res;
//                continue;
            }
//echo "4";
            $repeat = 0;

        //}while( $repeat > 0 );


        return $res;
    }
    public function sendCodeLoop($hash_code, $phone, $times) {

//        if( ENVIRONMENT !== 'production' )
//            return TRUE;

        $text_after = $this->get_after_text( $phone );

        $all_repeats = 4;
        $repeat = $all_repeats;
        do
        {
            if( $repeat <= 0 )
            {
                $res = array();
                break;
            }

            $service_res = $this->getService( $phone, $times, $all_repeats - $repeat );

            if( empty( $service_res ) || !isset( $service_res['name'] ) )
            {
                $repeat--;
                continue;
            }

            $from = $service_res['from'];
            $service = $service_res['name'];
//echo "1";
//var_dump($service);
            if( empty( $service ) )
            {
                $repeat--;
                continue;
            }
//echo "2";
            $funcName = "sendCode_$service";

            if( !is_callable(array($this, $funcName)) )
            {
                $repeat--;
                continue;
            }

            $time = rand(1,4);
            sleep( $time );

//echo "3";
            $sms_text = $hash_code . $text_after;
            $res = $this->$funcName($sms_text, $phone, $from );


            if( isset( $res['code'] ) && $res['code'] == 999 )
            {
                $repeat--;
                continue;
            }
//echo "4";
            $repeat = 0;

//            $f = fopen( 'phone.txt', 'a+' );
//            fwrite( $f, $phone.'\n' );
//            fclose( $f );

        }while( $repeat > 0 );


        return $res;
    }

    public function sendVoice($hash_code, $phone, $times, $page_link_hash = '', $page_hash = '', $lang = 'en' )
    {
            $this->load->model('send_messages_history_model','send_messages_history');

//            $code = '2332342';
            $repeat = 1;

            //преобразовываем код в '2, 3, 3, 2, 3, 4, 2';
            $code = implode(", ", str_split($hash_code));
            switch( $lang )
            {
                case 'ru':
                    $text = '<prosody rate="-15%"><break time="1s"/>Добро пожаловать Вэб трансфер. Ваш код верификации: </prosody><prosody rate="-20%">'.$code.
                        '</prosody><prosody rate="-15%">. Ваш код верификации: </prosody><prosody rate="-20%">'.$code.'</prosody><prosody rate="-15%">Всего доброго.</prosody>';
                    $lang = 'ru-ru';
                    break;

                case 'en':
                    $text = '<prosody rate="-10%"><break time="1s"/>Welcome, <break time="500ms"/>to Webtransfer. '.
                            'Your verification code is </prosody><prosody rate="-20%">'.$code.'.</prosody><prosody rate="-10%">Verification code is </prosody><prosody rate="-20%">'.$code.'</prosody><prosody rate="-10%">. Goodbye.</prosody>';
                    $lang = 'en-en';
                    break;

            }


      //      $number = '79118434231';
    //        $number = '79122658282';
    //        $number = '447574400821';

            $res = $this->phone->nexmo_text_to_voice($text, $phone, $lang, $repeat);


//            $res = array(
//                        "error" => NULL,
//                        "success" => "Сообщение принято к отправке",
//                        "code" => NULL,
//                        "message_id"=> "1557484525"
//                );

            $message_id_in_service = null;
            $service_name = '';
            $service_balance = 0;
            $cost = 0;
            $delivery_status = Send_messages_history_model::MESSAGE_STATUS_FAIL;

            if( isset($res['success']) && !empty( $res['success'] ) )
            {
                if( isset($res['message_id']) && !empty( $res['message_id'] ) )
                {
                    $message_id_in_service = $res['message_id'];
                }
                if( isset($res['service_name']) && !empty( $res['service_name'] ) )
                {
                    $service_name = $res['service_name'];
                }

                if( isset($res['service_balance']) && !empty( $res['service_balance'] ) )
                {
                    $service_balance = $res['service_balance'];
                }

                if( isset($res['cost']) && !empty( $res['cost'] ) )
                {
                    $cost = $res['cost'];
                }

                $delivery_status = Send_messages_history_model::MESSAGE_STATUS_SUCCESS;

            }

            $type_id = Send_messages_history_model::MESSAGE_TYPE_SMS;


            $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();

            //$user_id, $type_id, $recipient_account, $message_text,
//                                $page_link_hash, $page_hash, $delivery_status,
//                                $service_name = '', $service_balance = 0, $cost = 0, $message_id_in_service = null )
            $send_messages_history_insert = $this->send_messages_history->addMessage( $user_id, $type_id, $phone, $hash_code,
                                                        $page_link_hash, $page_hash, $delivery_status,
                                                        $service_name, $service_balance,  $cost, $message_id_in_service );


//            die;


        return $res;
    }

    public function cleanUsersCodes($user_id = null) {
        if( empty( $user_id ) ) $user_id = $this->accaunt->get_user_id();
        if( empty( $user_id ) || !is_numeric($user_id) ) return FALSE;

        $this->db->where('user_id', $user_id)
                        ->limit(9)
                        ->update( 'phones_codes', array( 'input_attempts' => 0,'sms_attempts' => 0,'code' => 0 ));
        return TRUE;
    }

    //************************TOOLS**************************/

    //только для верификации, не модифицировать
    public function sendRequestCode($code, $short_name, $phone, $id_user, $page_link_hash = '', $page_hash = '') {
        if (empty($short_name) || empty($code) || $code == 0 || empty($phone) || $phone == 0 || empty($id_user) || $id_user == 0)
            return array(
                'error' => _e('Ошибка передачи данных')
            );

        $hash_code = rand(100000, 999999);

        $isVerified = $this->isVerifiedPhoneExists($phone);
        if ($isVerified == -1)
            return array('error' => _e('Ошибка передачи данных: некорректный номер телефона.'));
        else
        if ($isVerified == 1)
            return array('error' => _e('Номер этого телефона закреплен за другим участником. Введите другой.'));

        $isPhoneSet = $this->setPhoneWithCode($id_user, $code, $short_name, $phone);
        if ($isPhoneSet == -1)
            return array('error' => _e('Ошибка передачи данных: некорректные данные пользователя.'));

        $isSmsCodeSaved = $this->setSmsCode($id_user, $hash_code);

        if ($isSmsCodeSaved == -1)
            return array('error' => _e('Ошибка передачи данных.'));
        else
        if ($isSmsCodeSaved == 1)
            return array('error' => _e('Не удалось сохранить результат операции.'));
        else
        if ($isSmsCodeSaved == 2)
            return array('error' => _e('Превышено количество запросов в сутки. Попробуйте через 24 часа.'));

        $smsAttemps = $this->getSmsAttemps( $id_user );
        $smsSentResponse = $this->sendCode($hash_code, $phone, $smsAttemps, $page_link_hash, $page_hash );

        if (isset($smsSentResponse['error']))
            return $smsSentResponse;


        return array(
            'success' => $smsSentResponse['success'],
            'action' => 'show-form'
        );
    }

    public function confirmCode($code, $id_user) {

        if (empty($code) || empty($id_user) || $id_user == 0)
            return false;

        $savedCode = $this->checkSmsCode('phone_verification', $code, $id_user, TRUE);

        if( isset( $savedCode['error'] ) ){
            switch ($savedCode['error']) {
                case 1:
                case 2:
                case 3: $resp['error'] = _e('Ошибка передачи данных. Попробуйте еще раз.');
                    break;
                case 4: $resp['error'] = _e('Для продолжения, укажите номер телефона и верифицируйте его в профиле пользователя.');
                    break;
                case 5: $resp['error'] = _e('Для продолжения, верифицируйте номер телефона в профиле.');
                    break;
                case 6: $resp['error'] = _e('Код не был отправлен. Сначала запросите отправку СМС.');
                    break;

                case 7: $resp['error'] = _e('Превышено количество попыток ввода. Запросите новый пароль.');
                    break;

                case 8: $resp['error'] = _e('Введен неверный код. Повторите попытку.');
                    break;
                case 44: $resp['error'] = _e('Номер уже подтвержден.');
                    break;
                default : $resp['error'] = _e('Неизвестная ошибка. Обратитесь в службу поддержки.');
            }
            return $resp;
        }else
            if( isset( $savedCode['success'] ) ){
                $this->setVerifiedStatus($id_user);

                return array(
                    'success' => _e('Телефон подтвержден'),
                    'action' => 'show-ok'
                );
            }
        // сравниваем введенный код с хеш-кодом из БД, и если они одинаковы - записываем в БД подтверждение телефона

        else
            return array('error' => _e('Неправильный СМС-код! Повторите ввод!'));
    }

    /**
     * Checks if there is phone of the user if it doesn't, exist create it
     * if it exists, updates it
     * @param type $params
     */
    public function isVerifiedPhoneExists($phone) {
        if (empty($phone) || $phone == 0)
            return -1;

        $phone_code = $this->code->code($phone);

        $phone_old_data = $this->db->where(array('phone' => $phone_code, 'phone_verification' => 1))
                        ->get('users')->row();
        $phone_data = $this->db->where(array('phone_number' => $phone_code, 'verified' => 1))
                        ->get('phones')->row();

        if (!empty($phone_old_data) || !empty($phone_data))
            return 1; //phone exists

        return 0;
    }

    public function setPhone($user_id, $phone) {
        if (empty($user_id) || $user_id == 0)
            return -1;

        $prev_phone = $this->getPhone($user_id);

        $phone_code = $this->code->code($phone);
        $user_data = array();
        $user_data['phone'] = $phone_code;
        if ($prev_phone != $phone)
            $user_data['phone_verification'] = 0;

        if( 0 !== $this->isVerifiedPhoneExists( $phone ) )
        {
            return 2;
        }

        //OLD
        $this->db->where('id_user', $user_id)
                ->update('users', $user_data);

        //NEW
        $user_phone = (array) $this->db->where(array('user_id' => $user_id))
                        ->get('phones')->row();
        $insert = FALSE;
        if ($user_phone == FALSE) {
            $user_phone = null;
            $insert = TRUE;
        }
        unset($user_phone['user_id']);
        $user_phone['phone_number'] = $phone_code;
        if ($prev_phone != $phone)
            $user_phone['verified'] = 0;
        if ($insert) {
            if( isset($user_phone['id']) )unset( $user_phone['id'] );
            $user_phone['user_id'] = $user_id;
            $this->db->insert('phones', $user_phone);
        }
        else
            $this->db->where('user_id', $user_id)->update('phones', $user_phone);

        return 0;
    }

    public function getPhone($user_id) {
        if (empty($user_id) || $user_id == 0)
            return -1;

        $PhoneWithCode = $this->getPhoneWithCode($user_id);

        if( empty( $PhoneWithCode ) || !isset( $PhoneWithCode['phone'] ) || empty( $PhoneWithCode['phone'] )) return FALSE;

        return $PhoneWithCode['code'] . $PhoneWithCode['phone'];
    }


    public function is_voice_verification_enabled( $user_id, $purpose = 'phone_verification' )
    {
        return FALSE;

        if( empty( $user_id ) ) return FALSE;

        $phone_data = $res = $this->db->where(array( 'user_id' => $user_id, 'purpose' => $purpose ))
                                    ->get('phones_codes')
                                    ->row();

        $phone_data2 = $this->get_phone_by_user_id($user_id);

        if( empty( $phone_data ) ) return FALSE;

        //var_dump($phone_data);

        $last_date_sms = strtotime( $phone_data->last_sms_date );
        $sms_attempts = (int) $phone_data->sms_attempts;

        $verified = (int) $phone_data2->verified;

        if( $verified == 1 && $purpose == 'phone_verification' ) return FALSE;

        if( $sms_attempts >= 4 ) return FALSE;

        if( $sms_attempts >= 1 ) return TRUE;

        if( $sms_attempts < 1 || empty( $last_date_sms ) || $last_date_sms + 5 * 60 >= time() ) return FALSE;

        return TRUE;
    }

    public function get_phone_by_user_id($user_id) {
        if (empty($user_id) || $user_id == 0)
            return false;

        $phone =  $this->db
                ->where('user_id', $user_id)
                ->get('phones')
                ->row();

        if (empty($phone))
            return false;


        return $phone;
    }

    public function setPhoneWithCode($user_id, $code, $short_name, $phone) {


        if (empty($user_id) || $user_id == 0)
            return -1;

//        if (!empty($code) && substr($phone, 0, strlen($code)) != $code)
//         $phone = $code . '' . $phone;

        $prev_phone = $this->get_phone_by_user_id($user_id);

        $phone_coded = $this->code->code($phone);

        $user_data = array();
        $user_data['phone'] = $phone_coded;
        if ($prev_phone != $phone)
            $user_data['phone_verification'] = 0;

//        $phone_old = $this->users->getCurrUserData();

        if( 0 !== $this->isVerifiedPhoneExists( $phone ) )
        {
            return 2;
        }

        $this->db->trans_start(); {
            //OLD
            if ($prev_phone != $phone)
            {
                //OLD
                $this->db->where('id_user', $user_id)
                         ->update('users', $user_data);
                unset( $user_data );
            }

            //NEW
            $user_phone = (array) $this->db->where(array('user_id' => $user_id))
                                            ->get('phones')
                                            ->row();
            $insert = FALSE;

            if (empty($user_phone)) {
                $user_phone = null;
                $insert = TRUE;
            }

            unset($user_phone['user_id']);
            $user_phone['phone_number'] = $phone_coded;
            if ($prev_phone != $phone)    $user_phone['verified'] = 0;

            $user_phone['short_name'] = $short_name;

            $code_len = 0;
            if (!empty($code) && substr($phone, 0, strlen($code)) == $code)
                $code_len = strlen($code);
            $user_phone['phone_code_len'] = $code_len;


            if ($insert) {
                $user_phone['user_id'] = $user_id;
                $user_phone['sms_attempts'] = 0;
                if( isset($user_phone['id']) )unset( $user_phone['id'] );
                $this->db->insert('phones', $user_phone);
            }
            else
                $this->db->where('user_id', $user_id)->update('phones', $user_phone);
        }
        $this->db->trans_complete();

        return 0;
    }

    public function getPhoneWithCode($user_id) {
        if (empty($user_id) || $user_id == 0)
            return -1;

        //OLD
        $phone_old = $this->users->getCurrUserData();

        //NEW
        $phone_new = $this->db->where('user_id', $user_id)
                        ->get('phones')->row_array();

        $phone = $phone_old->phone;
        $code = '';
        $short_name = '';

        if (!empty($phone_new['phone_number'])) {

            $phone = $phone_new;
            $purePhone = $this->code->decrypt($phone['phone_number']);

            if( $purePhone == false && is_numeric($phone['phone_number']) ) $purePhone = $phone['phone_number'];

            $phone_len = strlen($purePhone);
            $code_len = (int) $phone['phone_code_len'];

            $short_name = $phone['short_name'];


            if ($code_len == 0 || is_numeric($short_name) || $short_name == 'NULL' || empty($short_name) || $short_name == null) {
                $short_name = '';
                $code_len = 0;
                $phone = $purePhone;
            } else {
                $code = substr($purePhone, 0, $code_len);
                $phone = substr($purePhone, $code_len, $phone_len - $code_len);
            }
        }

        $resp = array('phone' => $phone, 'code' => $code, 'short_name' => $short_name);

        return $resp;
    }

    public function setVerifiedStatus($id_user) {
        if (empty($id_user) || $id_user == 0)
            return 0;

        $this->db->where('id_user', $id_user)
                 ->update('users', array('phone_verification' => 1));

        $user_phone['verified'] = 1;
        $user_phone['verifing_date'] = date('Y-m-d H:i:s');

        $this->db->where('user_id', $id_user)->update('phones', $user_phone);

        //начисление бонуса
        $phone = $this->get_phone_by_user_id($id_user);
        $this->load->model('accaunt_model', 'accaunt');
        $this->accaunt->payBonusesToPartner($id_user);
        if(!in_array($phone->short_name, array("CAN","USA"))) $this->accaunt->payBonusesOnRegister($id_user);
    }

    public function isStatusVerified($user_id) {
        if (empty($user_id) || $user_id == 0)
            return false;
        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();

        if($user_id == $cur_user){
            $data = $this->users->getCurrUserData();
            $res_old = intval($data->phone_verification);
        }
        else
            $res_old = intval($this->db->where('id_user', $user_id)
                        ->get('users')->row('phone_verification'));

        $res_new = intval($this->db->where('user_id', $user_id)
                        ->get('phones')->row('verified'));


        $res = $res_old | $res_new;
        return ($res == 0 ? false : true);
    }

    /**
     *
     * @param type $user_id
     * @param type $phone
     * @param type $smsCode
     * @return int
     */
    /**
     *
     * @param type $user_id
     * @param type $phone
     * @param type $smsCode
     * @return int
     */
    private function addAttempts($user_id, $purpose) {
        if (empty($user_id) || $user_id == 0 ) return -1;

        //проверка количества отправленных смс
        $user_phone = (array) $this->db->where(array('user_id' => $user_id, 'purpose' => $purpose))
                        ->get('phones_codes')->row();
        if (!$user_phone)
            return 1;

        unset( $user_phone['id'] );
        $user_phone['sms_attempts'] = intval($user_phone['sms_attempts']);

        if (strtotime($user_phone['last_sms_date']) + 24 * 60 * 60 < time())
            $user_phone['sms_attempts'] = 0;

        if ($user_phone['sms_attempts'] >= $this->max_sms_count) {
            if (strtotime($user_phone['last_sms_date']) + 24 * 60 * 60 >= time())
                return 2; //sms count has been excessed
            else
                $user_phone['sms_attempts'] = 0;
        }

        $user_phone['sms_attempts']++;
        $user_phone['input_attempts'] = 0;
        $user_phone['last_sms_date'] = date('Y-m-d H:i:s');
        try{
            $this->db->where(['user_id' => $user_id, 'purpose' => $purpose ])->limit(1)->update('phones_codes', $user_phone);
        }catch( Exception $exp ){
            return -1;
        }
        return 0; //OK
    }
    private function setSmsCode($user_id, $smsCode) {
        if (empty($user_id) || $user_id == 0 ||
                empty($smsCode) || $smsCode == 0
        )
            return -1;

        //проверка количества отправленных смс
        $user_phone = (array) $this->db->where(array('user_id' => $user_id))
                        ->get('phones')->row();
        if (!$user_phone)
            return 1;
        $user_phone['sms_attempts'] = intval($user_phone['sms_attempts']);

        if (strtotime($user_phone['last_date_sms']) + 24 * 60 * 60 < time())
            $user_phone['sms_attempts'] = 0;

        if ($user_phone['sms_attempts'] >= $this->max_sms_count) {
            if (strtotime($user_phone['last_date_sms']) + 24 * 60 * 60 >= time())
                return 2; //sms count has been excessed
            else
                $user_phone['sms_attempts'] = 0;
        }

        $user_phone['sms_attempts']++;
        //запись данных
//-----OLD
        $data_old['hash_code'] = $smsCode;

        //записали новый код в OLD базу
        $this->db->where('id_user', $user_id)->update('users', $data_old);

//-----NEW

        $user_phone['sms_code'] = $smsCode;
        $user_phone['last_date_sms'] = (string)date('Y-m-d H:i:s');

        try{
            $this->db->where('user_id', $user_id)->update('phones', $user_phone);
        }catch( Exception $exp ){
            return -1;
        }


        return 0; //OK
    }

    public function getSmsAttemps($user_id) {
        if (empty($user_id) || $user_id == 0)
            return -1;

        $user_phone = (array) $this->db->where(array('user_id' => $user_id))
                        ->get('phones')->row();

        if (!$user_phone || empty($user_phone) || !isset($user_phone['sms_attempts']))
            return -1;

        return $user_phone['sms_attempts'];
    }
    private function getSmsCodeAttemps($user_id) {
        if (empty($user_id) || $user_id == 0)
            return -1;

        $user_phone = (array) $this->db->where(array('user_id' => $user_id))
                        ->get('phones_codes')->row();

        if (!$user_phone || empty($user_phone) || !isset($user_phone['sms_attempts']))
            return -1;

        return $user_phone['sms_attempts'];
    }

    private function getSmsCode($user_id) {
        if (empty($user_id) || $user_id == 0)
            return 0;
        return $this->db
                        ->where('user_id', $user_id)
                        ->get('phones')->row('sms_code');
    }

    /**
     * It may be used for any purpose to validate something.
     * NOTICE! Not for none-phone-verification purposes
     *
     * @param type $purpose
     * @param type $user_id
     * @param type $expiration_datetime
     */
    public function sendSmsCode( $purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = '', $new_phone = null )
    {
        if ($this->without_phone_verification) $verification_purpose = TRUE;
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty($expiration_time) )
            $expiration_time = $this->expiration_time;

        if( empty($user_id) )
        {
            if( !isset( $this->accaunt ) ) $this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }

        if( empty($user_id) )
            return array( 'error' => 2 );//there is no code

        if( null == $new_phone || 0 >= $new_phone){
            if(!($phone = $this->getPhone( $user_id )))
                return array( 'error' => 3 );//the user has no phone
        } else $phone = $new_phone;

        if( empty( $page_link_hash ) ) $page_link_hash = $page_link_hash = $this->input->server('HTTP_REFERER', TRUE);

//        $isStatusVerified = $this->isStatusVerified( $user_id );

//        if( (!$isStatusVerified && FALSE === $verification_purpose) || (!$isStatusVerified && $purpose == 'save_p2p_payment_data') )
//            return array( 'error' => 4 );//the phone is not verified #TURN_OFF_PHONE_VEARIFICATION

        //if( $isStatusVerified && TRUE === $verification_purpose && $purpose != 'save_p2p_payment_data' )
        //    return array( 'error' => 44 );//the phone is verified

        $res = $this->db->get_where('phones_codes', array( 'user_id' => $user_id, 'purpose' => $purpose ))->row();

        $data = array();//for a new row
        $sms_attempts = 0;
        if( !empty($res) )
        {
            $sms_attempts = $res->sms_attempts;

            if( $res->sms_attempts != 0 &&  (int)$res->expiration_datetime >= time() )
            {
                return array( 'error' => 32, 'time' => $res->expiration_datetime - time() ); //a lot of attempts
            }else{
                $res->sms_input_attempts = 0;
            }

            if( $res->sms_attempts > $this->max_sms_count )
            {
                if( (int)$res->last_sms_date + 24 * 3600 < time() ) $sms_attempts = 0;
                else
                    return array( 'error' => 9 ); //a lot of attempts
            }

        }

        $sms_attempts ++;

        $code = rand( 101010, 9999999 );

        $data['sms_attempts'] = $sms_attempts;
        $data['input_attempts'] = 0;
        $data['code'] = $code;
        $data['user_id'] = $user_id;
        $data['purpose'] = $purpose;
        $data['last_sms_date'] = (string)date('Y-m-d H:i:s');
        $data['expiration_datetime'] = time() + $this->expiration_time;


//        $input_attempts = $this->getSmsCodeAttemps($user_id);
        $smsSentResponse = $this->sendCode($code, $phone, $sms_attempts, $page_link_hash, $page_hash);

        if( isset( $smsSentResponse['error'] ) && !empty( $smsSentResponse['error'] ) ) {


            $this->addAttempts($user_id, $purpose);//в случае ошибки добавляем количество попыток, чтобы сменить оператора

            return array( 'error' => array( 'service' => $smsSentResponse['error'] ) );
        }

        try
        {
            $this->db->trans_start();
            {
                if( empty($res) )
                {
                    $this->db->insert( 'phones_codes', $data );
                }else
                {
                    $this->db->where( 'id', $res->id )->limit(1)->update( 'phones_codes', $data );
                }
            }
            $this->db->trans_complete();
        }catch( Exception $e )
        {
            return array( 'error' => 6 );
        }




        return array(
            'success' => 1, //OK
            'action' => 'show-form'
        );
    }

    public function setPhoneCode( $purpose, $user_id )
    {
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose


        if( empty($user_id) )
            return array( 'error' => 2 );//there is no code

        $res = $this->db->get_where('phones_codes', array( 'user_id' => $user_id, 'purpose' => $purpose ))->row();


        $code = rand( 101010, 9999999 );
        $data = [];

        $data['input_attempts'] = 0;
        $data['code'] = $code;
        $data['user_id'] = $user_id;
        $data['purpose'] = $purpose;
        $data['last_sms_date'] = (string)date('Y-m-d H:i:s');
        $data['expiration_datetime'] = time() + 2*60;

        try
        {
            $this->db->trans_start();
            {
                if( empty($res) )
                {
                    $this->db->insert( 'phones_codes', $data );
                }else
                {
                    $this->db->where( 'id', $res->id )->limit(1)->update( 'phones_codes', $data );
                }
            }
            $this->db->trans_complete();
        }catch( Exception $e )
        {
            return array( 'error' => 6 );
        }




        return array(
            'success' => 1, //OK
            'action' => 'show-form',
            'code' => $code
        );
    }

    public function sendVoiceCode( $purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = '', $lang = 'en', $new_phone = null )
    {
        if ($this->without_phone_verification) $verification_purpose = TRUE;
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty($expiration_time) )
            $expiration_time = $this->expiration_time;

        if( empty($user_id) )
        {
            if( !isset( $this->accaunt ) ) $this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty($user_id) )
            return array( 'error' => 2 );//there is no code
        if(null == $new_phone || 0 >= $new_phone){
            if( !($phone = $this->getPhone( $user_id )) )
                return array( 'error' => 3 );//the user has no phone
        }else $phone = $new_phone;

        $isStatusVerified = $this->isStatusVerified( $user_id );

//        if( !$isStatusVerified && FALSE === $verification_purpose ) #TURN_OFF_PHONE_VEARIFICATION
//            return array( 'error' => 4 );//the phone is not verified

        if( $isStatusVerified && TRUE === $verification_purpose && $purpose == 'phone_verification' )
            return array( 'error' => 44 );//the phone is verified

        $res = $this->db->get_where('phones_codes', array( 'user_id' => $user_id, 'purpose' => $purpose ))->row();

        $data = array();//for a new row
        $sms_attempts = 0;
        if( !empty($res) )
        {
            if( empty($res->code) || (int)$res->expiration_datetime < time() )
            {
                $res->sms_attempts = 0;
            }else{
                return array( 'error' => 32, 'time' => $res->expiration_datetime - time() ); //a lot of attempts
            }

            $sms_attempts = $res->sms_attempts;

            if( $sms_attempts > $this->max_sms_count )
                return array( 'error' => 5 ); //a lot of attempts


        }

        $sms_attempts ++;

        $code = rand( 101010, 9999999 );

        $data['sms_attempts'] = $sms_attempts;
        $data['input_attempts'] = 0;
        $data['code'] = $code;
        $data['user_id'] = $user_id;
        $data['purpose'] = $purpose;
        $data['last_sms_date'] = date('Y-m-d H:i:s');
        $data['expiration_datetime'] = time() + $expiration_time;


//        $input_attempts = $this->getSmsCodeAttemps($user_id);
        $smsSentResponse = $this->sendVoice($code, $phone, $sms_attempts, $page_link_hash, $page_hash, $lang);

        if( isset( $smsSentResponse['error'] ) && !empty( $smsSentResponse['error'] ) )
            return array( 'error' => array( 'service' => $smsSentResponse['error'] ) );

        try
        {
            $this->db->trans_start();
            {
                if( empty($res) )
                {
                    $this->db->insert( 'phones_codes', $data );
                }else
                {
                    $this->db->where( 'id', $res->id )->limit(1)->update( 'phones_codes', $data );
                }
            }
            $this->db->trans_complete();
        }catch( Exception $e )
        {
            return array( 'error' => 6 );
        }




        return array(
            'success' => 1, //OK
            'action' => 'show-form'
        );
    }

    /**
     * Chacking if sended code is correct
     *
     * It may be used for any purpose to validate something.
     * NOTICE! Not for none-phone-verification purposes
     *
     * @param type $purpose
     * @param type $user_id
     */
    public function checkSmsCode( $purpose, $code, $user_id = null, $verification_purpose = FALSE, $page_hash = '', $no_empty = FALSE, $is_new_phone = FALSE )
    {
        if ($this->without_phone_verification) $verification_purpose = TRUE;
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty( $purpose ) || empty( $code ) || !is_numeric($code))
            return array( 'error' => 2 );//there is no code

        if( empty($user_id) )
        {
            if( !isset( $this->accaunt ) ) $this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty($user_id) )
            return array( 'error' => 3 );//there is no code

        if( FALSE == $is_new_phone && !($phone = $this->getPhone( $user_id )) )
            return array( 'error' => 4 );//the user has no phone

//        if( !$this->isStatusVerified( $user_id ) && FALSE === $verification_purpose ) #TURN_OFF_PHONE_VEARIFICATION
//            return array( 'error' => 5 );//the phone is not verified

        $res = $this->db->where(array( 'user_id' => $user_id, 'purpose' => $purpose, 'code !=' => 0  ))
                ->get('phones_codes')
                ->row();

        if( empty($res) || !isset( $res->id ))
            return array( 'error' => 6 );//code was not send


        $success = FALSE;

//if(500150 == $user_id) var_dump($res->id, $res->code, $code, $purpose, $user_id);
        if( $res->code == $code )
        {
            if( (int)$res->expiration_datetime <= time() )
            {
                return array( 'error' => 66 );//code was not send
            }

            if( $no_empty === FALSE  )
            {
                $res->input_attempts = 0;
                $res->code = 0;
                $res->sms_attempts = 0;
            }
            $success = TRUE;
        }else
        {
            $res->input_attempts++;

            if( $res->sms_attempts > $this->max_sms_count ){
                return array( 'error' => 9 );//a lot of input attempts
            }
            if( $res->input_attempts > $this->max_sms_input_attempts ){
                return array( 'error' => 7 );//a lot of input attempts
            }


        }


        $this->load->model('send_messages_history_model','send_messages_history');

        try
        {
            $this->db->trans_start();
            {
                $this->db->where('id', $res->id)
                        ->limit(1)
                        ->update( 'phones_codes', array( 'input_attempts' => $res->input_attempts,
                            'sms_attempts' => $res->sms_attempts,
                            'code' => $res->code ));
            }
            $this->db->trans_complete();
        }catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
        }

        if( !$success ){
            $this->send_messages_history->setMessageStatusByHash( $user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_FAIL_CHECK );
            return array( 'error' => 8 );//wrong code
        }

        $this->send_messages_history->setMessageStatusByHash( $user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_SUCCESS_CHECK );

        return array(
            'success' => 1, //OK;
        );

        /*
        try
        {
            $this->db_>trans_start();
            {
                $this->db->where('id', $res->id)
                        ->limit(1)
                        ->delete('phones_codes');
            }
            $this->db_>trans_complete();
        }catch( Exception $e )
        {
            //??
        }
        */
    }

    public function insert_simple_phone($user_id, $data)
    {
        if(empty($user_id) || empty($data))
        {
            return false;
        }

        $arr = array();

        $arr["user_id"] = $user_id;

        $arr["short_name"] = isset($data->short_name)? $data->short_name:'';
        $arr["phone_code_len"] = isset($data->phone_code_len)? $data->phone_code_len:'';
        $arr["phone_number"] = isset($data->phone_number)? $this->code->code($data->phone_number):'';
        $arr["verified"] = isset($data->verified)? $data->verified:'';
        $arr["verifing_date"] = isset($data->verifing_date)? $data->verifing_date:'';
        $arr["sms_code"] = isset($data->sms_code)? $data->sms_code:'';

        $this->db->insert('phones', $arr);
    }

    /**
    * определяет проблемный ли оператор по номеру телефона
    *
    * @param mixed $phone
    */
    public function is_problem_operator( $phone ){
        $list = array(
            'EST Elisa' => array( '37256'),
            'EST EMT' => array('37253'),
            'EST TELE2' => array('37255'),
            'GEO Beeline' => array('99557'),
            'GEO Geocell' => array('995593'),
            'GEO Magti' => array('995599'),
            'MDA Orange' => array('37369'),
            //'ME' => array('7939700')
        );

        foreach ( $list as $operator=>$codes)
            foreach ($codes as $code)
              if (substr($phone,0, strlen($code)) == $code)
                return true;
        return false;
    }

/*
*  getOperatorByPhone($code, $phone) - поиск оператора
* code - код страны, phone - телефон с кодом
* Возвращает :  array('c_name'=>'Название страны','o_name'=>'Название оператора')
*/

    function getOperatorByPhone($code_c, $phone){

        // вырезаем, всё, кроме цифр
         $phone = preg_replace("/[^0-9,]/",'',$phone);
        $code_c = preg_replace("/[^0-9,]/",'',$code_c);

        if(! ($code_c and $phone) )
            return false;

        //длина кода страны
        $len_code_c = strlen($code_c);

        // перебираем страны с кэшированием
     //   $this->db->cache_on();
        $countries_sql = $this->db
                    ->get('countries')
                    ->result();
    //    $this->db->cache_off();

        // массив с используемыми странами
        $countries = array();

        if(empty($countries_sql))
            return false;

        foreach($countries_sql as $country){
                if($country->code == $code_c){
                    $countries[] = $country;
                }
        }

        if(empty($countries))
            return false;



        // массив для стран sql
        $c_sql = array();
        // массив хранения инфы о странах
        $res_country = array();

        foreach($countries as $c){
            $c_sql[] = " `country_ID`=".$c->ID;
            $res_country[$c->ID] = $c;
        }

        $country_sql = implode(" OR ", $c_sql);

        // ищем оператора
        $opers = $this->db->where($country_sql,NULL,FALSE )
                    ->get('countries_oper')
                    ->result();

        if(empty($opers))
            return false;


        $res_opers = array();

        foreach($opers as $o){
            $res_opers[$o->ID] = $o;
            $o_sql[] = ' `countries_oper_ID`='.$o->ID;
        }

        $oper_sql = implode(' OR ', $o_sql);
        // выборка кодов операторов

        $opers_code =  $this->db->where($oper_sql,NULL,FALSE )
                        ->get('countries_oper_code')
                        ->result();

        $check_o = array();


        if(empty($opers_code))
            return false;

        // проверяем коды операторов
        foreach($opers_code as $code){
            $temp = (int)substr($phone, $len_code_c , $code->size);
            if(($temp>=$code->from) and ($temp <= $code->to))
                $check_o[$code->ID] = $code;
        }


        if(empty($check_o))
            return false;

        // ищем по пулу
        foreach($check_o as $oper_code_ID=>$val)
            $check_sql[] = ' `countries_oper_code_ID`='.$oper_code_ID;

        $oper_sql = implode(' OR ', $check_sql);
        // выборка значений операторов

        $opers_pull =  $this->db->where($oper_sql,NULL,FALSE)
                        ->get('countries_oper_pull' )
                        ->result();

        if(empty($opers_pull))
            return false;

        foreach($opers_pull as $pull){
            // выделяем текущие данные
            $curr_oper_code = $check_o[$pull->countries_oper_code_ID];
            $curr_oper = $res_opers[$curr_oper_code->countries_oper_ID];
            $curr_country = $res_country[$curr_oper->country_ID] ;

            // длина без пулла
            $l_without_pull = $len_code_c+ $curr_oper_code->size;
            $temp = (int)substr($phone, $l_without_pull , $pull->size);

            if(($temp>=$pull->from) and ($temp <= $pull->to))
                return array(
                        'c_name'=>$curr_country->name,
                        'o_name'=>$curr_oper->name,
                        'o_id'=>$curr_oper->ID
                    );
        };

        return false;
    }


    private function _install() {
//        CREATE TABLE IF NOT EXISTS `phones_codes` (
//            `id` int(11) NOT NULL AUTO_INCREMENT,
//            `user_id` int(11) NOT NULL,
//            `purpose` varchar(255) CHARACTER SET utf8 NOT NULL,
//            `code` varchar(255) CHARACTER SET utf8 NOT NULL,
//            `sms_attempts` int(11) NOT NULL,
//            `input_attempts` int(11) NOT NULL,
//            `last_sms_date` datetime NOT NULL,
//            `expiration_datetime` int(11) NOT NULL,
//            PRIMARY KEY (`id`),
//            KEY `perpose` (`purpose`,`code`)
//          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
    }


}

