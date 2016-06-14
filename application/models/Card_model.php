<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Card_model extends CI_Model {
    public $tableName_requests = "cards_requests";
    public $tableName_cards = "cards";
    public $tableName_cards_users = "users_other_accounts";


    const STATUS_NOT_EVEN_CREATED = 0;
    const STATUS_CREATED = 1;
    const STATUS_VERIFIED = 2;
    const STATUS_APPROVED = 3;
    const STATUS_DECLINED = 4;
    const STATUS_PROCESSED = 5;

    const MAX_OTHER_CARDS = 99;


    const CARD_TYPE_PLASTIC = 0;
    const CARD_TYPE_VIRTUAL = 1;

    public $id = null;
    public $id_user = null;
    public $holder_name = null;
    public $name = null;
    public $birthday = null;
    public $phone_mobile = null;
    public $country = null;
    public $city = null;
    public $zip_code = null;
    public $card_proxy = null;
    public $card_user_id = null;
    public $prop_adress = null;
    public $created = null;
    public $verified = null;
    public $approved = null;
    public $declined = null;
    public $processed = null;
    public $decline_error = null;
    public $status = null;
    public $surname = null;
    public $phone_home = null;
    public $email = null;
    public $delivery_address = null;
    public $delivery_city = null;
    public $delivery_zip_code = null;
    public $delivery_country = null;

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model","users");
        $this->load->model('Card_transactions_model', 'card_transactions');

        return $this;
    }

    /*public function getCard($id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        return $this->db->get_where($this->tableName, array('id_user' => $id_user))->row();
    }*/

    public function getCards($id_user = null, $card_type = null) {
        //return FALSE;
        if ( $id_user === 0 ) return FALSE;
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        if ( $card_type !== null )$this->db->where('card_type', $card_type);
        return $this->db->get_where($this->tableName_cards, array('user_id' => $id_user))->result();
    }

    public function getUrlNewCard(){
        $this->load->model("users_model","users");
        $this->load->model('phone_model','phones');
        $user = $this->users->getCurrUserData();
        $phone_info = $this->phones->get_phone_by_user_id($user->id_user);
        $phone = $this->code->decode($phone_info->phone_number);
        if(!empty($user->email) && !empty($phone) && $phone_info->verified){
            // генерируем ссылку для создания новой карты
            $FORCE_VERFIFCATION_SUGAR = 'chiZohb9aegeeth';
            $randomname = rand(100000,9999999);
            $collect = [];
            $collect[] = 'email='.$user->email;
            $collect[] = 'phone=+'.$phone;
            $collect[] = 'randomname='.$randomname;
            sort($collect);
            $signature = md5(implode("", $collect) . $FORCE_VERFIFCATION_SUGAR);
            return config_item('webtransfercard') .  _e('lang') . "/registration?email=".$user->email."&phone=".htmlentities('%2B'.$phone)."&randomname=$randomname&sig=$signature&forcewt=true";
        } else
            return '';
    }

    public function getOtherCards($id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        return $this->db->get_where($this->tableName_cards_users, array('user_id' => $id_user))->result();
    }


    public function getCardsBalance($id_user, $cache = TRUE){
         if ( $cache == FALSE){
            $summ = 0;
            $cards = $this->getCards($id_user);
            foreach( $cards as $card )
                $summ += $this->getCardBalance($card);
         } else {
             $summ = $this->db
                ->select("SUM(last_balance) as summ")
                ->where('user_id', $id_user)
                ->get($this->tableName_cards)
                ->row()->summ;
         }
        return empty($summ)?0:$summ;
    }

    public function getOtherBalance($id_user){

         $summ = $this->db
            ->select("SUM(summa) as summ")
            ->where('user_id', $id_user)
            ->get($this->tableName_cards_users)
            ->row()->summ;
        return empty($summ)?0:$summ;
    }



    public function getUnpayedCards($user_id) {
        return $this->db->get_where($this->tableName_requests, 'id_user='.$user_id.' AND card_type='.self::CARD_TYPE_PLASTIC.' AND paid = 0 AND card_proxy IS NULL AND processed IS NULL AND status = "created"')->result();
    }


    public function getCard($card_id) {
        return $this->db->get_where($this->tableName_cards, array('id' => $card_id))->row();
    }

    public function getCardByCardUserId($card_user_id) {
        return $this->db->get_where($this->tableName_cards, array('card_user_id' => $card_user_id))->row();
    }


    public function getUserOther($id, $user_id = NULL) {
        $user_id = ($user_id == null) ? $this->users->getCurrUserId() : $user_id;
        return $this->db->get_where($this->tableName_cards_users, array('id' => $id, 'user_id'=>$user_id))->row();
    }



    public function getUserCard($card_id, $user_id = NULL) {
        $user_id = ($user_id == null) ? get_current_user_id() : $user_id;
        return $this->db->get_where($this->tableName_cards, array('id' => $card_id, 'user_id'=>$user_id))->row();
    }
    /* DUMB CODE
    WHY NEED TO USE MODEL IF GETTER && SETTER && UPDATER USE SOME INPUT ARGUMENTS ?


    public function setCard($input, $id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        $input['id_user'] = $id_user;
        $this->db->insert($this->tableName, $input);
    }

    public function updateCard($input, $id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        $input['updated'] = date("Y-m-d H:i:s");
        $input['status'] = 'updated';
        $this->db->update($this->tableName, $input, array('id_user' => $id_user), 1);
    }

    public function setSend($id_user = null) {
        $id_user = (null == $id_user) ? $this->users->getCurrUserId() : $id_user;
        $input['sended'] = date("Y-m-d H:i:s");
        $input['status'] = 'sended';
        $this->db->update($this->tableName, $input, array('id_user' => $id_user), 1);
    }
     */

    // TODO seems need to think about some bitwise comparison
    public function status(){
        $status = self::STATUS_NOT_EVEN_CREATED;
        switch($this->status){
            case "created":
                $status = self::STATUS_CREATED;
                break;
            case "verified":
                $status = self::STATUS_VERIFIED;
                break;
            case "approved":
                $status = self::STATUS_APPROVED;
                break;
            case "declined":
                $status = self::STATUS_DECLINED;
                break;
            case "processed":
                $status = self::STATUS_PROCESSED;
                break;
        }
        return $status;
    }


    public function updateCachedBalance($card_id, $balance){

         $this->db->update($this->tableName_cards, ['last_balance'=>$balance], array('id'=>$card_id));
    }


    /**
     * Создает карту
     * @param type $data
     */
    public function create_card($data, $request_id = NULL){
         $this->db->insert($this->tableName_cards, $data);

    }

    /**
     * Обновим карту
     * @param type $data
     */
    public function update_card($card_id, $data ){
         $this->db->update($this->tableName_cards, $data, ['id'=>$card_id]);

    }


    /**
     * Сохраняет запрос на карту
     * @param type $card_request_id
     * @param type $data
     */
    public function save_request($card_request_id, $data){
        $this->db->update($this->tableName_requests, $data, array('id'=>$card_request_id));

    }

    /**
     * Создает запрос на карту
     * @param type $card_request_id
     * @param type $data
     */
    public function create_request($data, $real_email, $user_id = null){
        $this->load->model('transactions_model', 'transactions');
        $user_id = ($user_id == null) ? $this->users->getCurrUserId() : $user_id;

        $avail_cards = $this->getCards($id_user, $data->card_type);
        if ( count($avail_cards) >= 1 && strpos(base_url(),'wtest2') === false && $data->id_user!=92156962)
            return _e('Можно заказать не более одной карты данного типа');


        $this->db->insert($this->tableName_requests, $data);
        $request_id = $this->db->insert_id();
        $data->request_id = $request_id;


        if ( empty($request_id) )
            return _e('Ошибка при сохранении данных');

         if ( $data->email == 'AUTO'){
            $data->email = $request_id.'@wt-mail.com';
            $this->db->update($this->tableName_requests, ['email'=>$data->email], array('id'=>$request_id));
        }

        // если карта платиковая то запрос к апи не делаем
        if ($data->card_type != self::CARD_TYPE_VIRTUAL )
            return TRUE;


        $this->load->library('WTCApi');
        $wtcapi = new WTCApi();

        $create_card = $wtcapi->card($data);
        if(!empty($create_card['errors'])){
            $this->save_request($request_id, [
                'decline_error' => json_encode($create_card['errors']),
                'declined' => date("Y-m-d H:i:s"),
                'status' => 'declined'
            ]);

            $errorStr = '';
            foreach ($create_card['errors'] as $error)
                $errorStr .= $error['errorDescription'].';<br> ';



            return $errorStr;
        }

        if(isset($create_card['userId']) && isset($create_card['cardDetail']['proxy'])){

            $this->pay_request($request_id);
            $this->save_request($request_id, [
                'card_pan' => substr($create_card['cardDetail']['pan'], -4)
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
            $this->create_card($new_card_data);

            $this->load->library('Wtcard_api');
            $wtcapi = new Wtcard_api(config_item('Wtcard_api_login'), config_item('Wtcard_api_pass'));
            $data->email = $real_email;
            $wtcapi->user_cards_import($create_card['cardDetail']['proxy'], $create_card['usrId'], $data->email, md5("{$create_card['usrId']}:{$create_card['cardDetail']['proxy']}:{$data->email}:silniy_kolobok13") );


            return TRUE;

        }else{
            $this->save_request($request_id, [
                'decline_error' => json_encode(array("errorCode"=>"our api", "errorDescription"=>"I cannot find valid json answer, no userID or card proxy specified")),
                'declined' => date("Y-m-d H:i:s"),
                'status' => 'declined'
            ]);
            return  _e('Ошибка обемена с внешним сервером');


        }

    }


    /*
     * Отменяет запрос на карту
     */
    public function pay_request($request_id, $user_id = null){



        $request_where = ["id"=>$request_id];
        if ( $user_id !== null)
            $request_where['id_user'] = $user_id;


        $request = $this->db->get_where($this->tableName_requests, $request_where)->row();
        if ( empty($request))
                return _e('Запрос не найден');

        $user_id = $request->id_user;

        if ( empty($this->getCards($user_id)) ){
            $this->save_request($request_id, [
                'paid' => 1,
                'status' => 'processed',
                'processed' => date("Y-m-d H:i:s")
            ]);
            return TRUE;
        }


        if ($request->card_type == self::CARD_TYPE_VIRTUAL ){
            $note = 'Заказ виртуальной карты WebTransfer';
            $cost = 1;
            /*$all_moneyin_by_bonus_2=  $this->transactions->getAllInMoneyOfUser($user_id, 2);
            if ( $all_moneyin_by_bonus_2 <= 0 ){
                if ( $this->base_model->getRealMoney($user_id) < $cost )
                    return  _e('У вас не достаточно средств для оформления заявки на карту');

                $trId = $this->transactions->addPay($user_id, $cost, 40, $request_id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, 6, $note);
            }*/
             $trId = $this->transactions->addPay($user_id, $cost, 40, $request_id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, 6, $note);
        }  else {
            $note = 'Оплата за заказ Webtransfer Visa Card';
            if ( strtotime($request->created) > strtotime('2015-06-01'))
                $cost = 22.95;
            else
                $cost = 10;
            if ( $this->base_model->getRealMoney($user_id) < $cost )
               return  _e('У вас не достаточно средств для оформления заявки на карту');
            $trId = $this->transactions->addPay($user_id, $cost, 40, $request_id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, $note);
        }


        $this->save_request($request_id, [
            'paid' => 1,
            'status' => 'processed',
            'processed' => date("Y-m-d H:i:s")
        ]);
        return TRUE;
    }

    /*
     * Отменяет запрос на карту
     */
    public function cancel_request($request_id, $user_id = null){
        if ( $user_id !== null){
            $request = $this->db->get_where($this->tableName_requests, array("id"=>$request_id, 'id_user'=>$user_id))->row();
            if ( empty($request))
                return FALSE;
        }
        $this->save_request($request_id, [
            'status' => 'declined',
            'declined' => date("Y-m-d H:i:s")
        ]);
        return TRUE;
    }

    public function get_request($id, $user_id){

        $row = $this->db->get_where($this->tableName_requests, array("id"=>$id, 'id_user'=>$user_id))->row();
        return $row;
    }

    public function get_for_user($id_user = null){
        if($id_user == null && $this->id_user == null){
            return false;
        }
        if($id_user == null)
            $id_user = $this->id_user;

        $row = $this->db->get_where($this->tableName_requests, array("id_user"=>$id_user))->row();

        if($row){
            foreach($row as $key=>$value)
                $this->$key = $value;

            return true;
        }
        return false;
    }

    public function validate($data){
        $result = array();
        foreach($data as $key=>$value){
            if($value === ""){
                $result[] = $key;
                continue;
            }
            switch($key){
                case "holder_name":
                    if(strlen($value) > 21)
                        $result[] = $key;
                    break;
                case "prop_adress":
                    if(strlen($value) > 35)
                        $result[] = $key;
                    break;
                case "name":
                    if(strlen($value) > 20)
                        $result[] = $key;
                    break;
                case "surname":
                    if(strlen($value) > 20)
                        $result[] = $key;
                    break;
                case "country":
                case "delivery_city":
                case "delivery_country":
                    if(preg_match("/[^a-zA-Z ]+/i",$value))
                        $result[] = $key;
                    break;
                case "birthday":
                    $bd = strtotime($value);
                    $min = strtotime('+18 years', $bd);
                    if(time() < $min)
                        $result[] = $key;
                case "phone_mobile":
                    if(strlen($value) > 15 || preg_match("/[^0-9.- ]+/i",$value) )
                        $result[] = $key;
                case "phone_home":
                    if(strlen($value) > 15 || preg_match("/[^0-9.- ]+/i",$value) )
                        $result[] = $key;
                    break;
                case "zip_code":
                    if(strlen($value) > 8 || strlen($value) < 2)
                        $result[] = $key;
                case "delivery_zip_code":
                    if(preg_match("/[^a-z0-9. ]+/i",$value))
                        $result[] = $key;
                    break;
                case "email":
                    if ( $value == 'AUTO' )
                        break;
                    if(strlen($value) > 64 || strlen($value) < 5)
                        $result[] = $key;
                    elseif(!preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/i",$value))
                        $result[] = $key;

                    break;
            }
        }
        return $result;
    }


    public function generateHolder($user){
        $this->load->helper('translit');
        return strtoupper(substr(translitIt($user->user->name) . " " . translitIt($user->user->sername), 0 , 21));
    }



    public function load($data, $type = NULL, $source = NULL) {
        $wtcard = $this->getUserCard($data->card_id, $data->user_id );
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($wtcard->card_user_id, $wtcard->card_proxy);
        $trans_id = $wtcapi->load($data);
        $this->card_transactions->add_transaction(  Card_transactions_model::METHOD_LOAD, NULL, $data->card_id, $type , ($trans_id!==false?Card_transactions_model::STATUS_OK:Card_transactions_model::STATUS_ERROR), $source, $wtcapi->getResp(), $data->desc, $data->summa   );
        return $trans_id;
    }



    public function sendCardDirect($trans, $type, $source = NULL) {
        $this->load->helper('translit_helper');
        $trans->note = translitIt($trans->note);
        $trans->id_user = $this->getCard($trans->id_user);
        $trans->own = $this->getUserCard($trans->own_card_id, $trans->own);
        $trans->id = $source;
        if(isset($trans->id_user->card_proxy) && $trans->id_user->card_proxy && isset($trans->own->card_proxy) && $trans->own->card_proxy){
            $this->load->library('WTCApi');
            $wtcapi = new WTCApi($trans->own->card_user_id, $trans->own->card_proxy);
            $resp = $wtcapi->unload($trans);
            $error = $wtcapi->getError();
            $this->card_transactions->add_transaction(  Card_transactions_model::METHOD_SEND_CARD_DIRECT,  $trans->own->id,  $trans->id_user->id, $type , ($error=='OK'?Card_transactions_model::STATUS_OK:Card_transactions_model::STATUS_ERROR), $source, $resp, $trans->note, $trans->summa   );

            return $error;

        } else return _e("У этого пользователя нет карты".$trans->own_card_id.", ".$x);
    }

    public function getCardBalance($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        $balance = $wtcapi->balance();
        if ( $balance !== null)
            $this->updateCachedBalance($card->id, $balance);

        return $balance;
    }

    public function getCardTransactions($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        return $wtcapi->transactions();
    }

    public function getCardHolderInfo($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        $result = $wtcapi->cardholderinfo();
        if(!empty($result['errors'])){
            return ['status'=>'error', 'result'=>$result['errors']];
        }
        return ['status'=>'success', 'result'=>$result];
    }


    public function purchaseMoney($data, $type, $source = NULL){
        $wtcard = $this->getUserCard($data['card_id'], $data['user_id'] );
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($wtcard->card_user_id, $wtcard->card_proxy);
        $trans_id = $wtcapi->purchase($data);
        $this->card_transactions->add_transaction(  Card_transactions_model::METHOD_PURCHASE_MONEY, NULL,  $data['card_id'], $type , ($trans_id!==false?Card_transactions_model::STATUS_OK:Card_transactions_model::STATUS_ERROR), $source, $wtcapi->getResp(), $data['desc'], $data['summa']   );
        return $trans_id;
    }

    public function getCountries($payMode, $operationName = NULL ){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0,0);
        $countries = $wtcapi->getCountries($payMode, $operationName);
        return $countries;

    }

    public function getBanks($countryCode){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0,0);
        $banks =  $wtcapi->getBanks($countryCode);
        /* if ($countryCode != 'GB' ) // default bank
            $banks[] = config_item('default_card_bank');*/


        foreach( $banks as $idx=>$bank ){
            if (  $bank['bankName'] == 'Trade Finance Bank ')
              unset($banks[$idx]);

        }


        return $banks;

    }



    public function getBankInfo($countryCode, $account){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0,0);
        $banks = $wtcapi->getBanks($countryCode);
        /*if ($countryCode != 'GB' ) // default bank
            $banks[] = config_item('default_card_bank');        */
        if (!empty($banks)){
            foreach ($banks as $bank) {
                if ( $bank['accountNumber'] == $account)
                    return $bank;
            }
        }

        return NULL;
    }

    public function getServices($countryCode){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0,0);
        $res = $wtcapi->getServices($countryCode);
        /*$res[] = [ "id"=>0, "serviceName"=>'OkPay',"serviceCode"=>'OkPay' ];
        $res[] = [ "id"=>0, "serviceName"=>'Payeer',"serviceCode"=>'Payeer' ];
        $res[] = [ "id"=>0, "serviceName"=>'PerfectMoney',"serviceCode"=>'PerfectMoney' ];
        $res[] = [ "id"=>0, "serviceName"=>'NixMoney',"serviceCode"=>'NixMoney' ];*/
        return $res;
    }

    public function getCustomerUniqueRef($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id,$card->card_proxy);
        return $wtcapi->getCustomerUniqueRef($card->card_proxy);
    }


    public function getServiceUrl($card, $summ, $service_name, $country_code){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id,$card->card_proxy);
        return $wtcapi->getServiceUrl($card->card_proxy, $summ, $service_name, $country_code);
    }

    public function getCardDetails($card) {
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id,$card->card_proxy);
        $details = $wtcapi->cardDetails();
        return $details;


    }

    public function getPin($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id,$card->card_proxy);
        return $wtcapi->getPin();
    }


    public function setStatus($card, $status, $comment = 'I want to change status!'){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id,$card->card_proxy);
        return $wtcapi->status($status, $comment);
    }


    public function activatePlasticCard($card, $pan){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        return $wtcapi->activate_and_set_pin($pan);
    }

    public function activateVirtualCard($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        return $wtcapi->activate();
    }

    public function setPassword($card){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        return $wtcapi->setPasswrd( $card->card_user_id );
    }


    public function addOther($user_id, $name, $summa, $account_type, $account_extra_data, $card_num, $own_wallet,  $account_personal_num ){
        $cards = $this->getOtherCards($user_id);
        if ( count($cards) >= self::MAX_OTHER_CARDS)
            return _e('Максимальное количество счетов: '.self::MAX_OTHER_CARDS);

        $this->db->insert($this->tableName_cards_users, [
            'name' => $name,
            'summa' => $summa,
            'account_type' => $account_type,
            'bank_card_num' => $card_num,
            'account_extra_data' => $account_extra_data,
            'own_wallet' => $own_wallet,
            'account_personal_num' => $account_personal_num,
            'user_id' => $user_id,
            'add_dttm' => date('Y-m-d H:i:s')

        ] );
        return TRUE;

    }

    public function editOther($id, $user_id, $name, $summa,  $account_type, $account_extra_data, $card_num, $own_wallet, $account_personal_num){

        $this->db->update($this->tableName_cards_users, [
            'name' => $name,
            'summa' => $summa,
            'bank_card_num' => $card_num,
            'account_type' => $account_type,
            'own_wallet' => $own_wallet,
            'account_personal_num' => $account_personal_num,
            'account_extra_data' => $account_extra_data,
        ],  ['id'=>$id, 'user_id'=>$user_id]);
        return TRUE;
    }

    public function removeOther($card_id, $user_id ){
        $this->db->where(['id'=>$card_id, 'user_id'=>$user_id])->delete($this->tableName_cards_users);
    }


    public function replaceOther($id, $user_id, $name, $summa,  $account_type, $account_extra_data, $card_num, $own_wallet, $account_personal_num){
            if ( empty($id))
                $this->addOther($user_id, $name, $summa, $account_type, $account_extra_data, $card_num, $own_wallet, $account_personal_num);
            else
                $this->editOther($id, $user_id, $name, $summa, $account_type, $account_extra_data, $card_num, $own_wallet, $account_personal_num);
    }




    public function getBankAccountTemplate($countryCode, $currencyCode = 'USD'){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0, 0);
        return $wtcapi->getBankAccountTemplate($countryCode, $currencyCode);
    }

    public function getPrefundingAccounts(){

        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0, 0);
        return $wtcapi->txnaccounts();

    }

    public function transactionAccountsTransfers($transactionaccountid, $filter){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi(0, 0);
        return $wtcapi->transactionAccountsTransfers($transactionaccountid, $filter);
    }



    public function addBankAndUnload($card_id, $data){

        $wtcard = $this->getUserCard($card_id);
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($wtcard->card_user_id, $wtcard->card_proxy);
        $data['proxy'] = $wtcard->card_proxy;
        //$data['bankAccountCountryCode']= 'ES';
        //$data['bankAccountCurrencyCode']= 'USD';
        $resp = $wtcapi->addBankAndUnload($data);
        $this->card_transactions->add_transaction(  Card_transactions_model::ADD_BANK_AND_UNLOAD, $card_id,  NULL, $type , ($resp!==false && $resp['errorCode']==0?Card_transactions_model::STATUS_OK:Card_transactions_model::STATUS_ERROR), NULL, $wtcapi->getResp(), ''  );
        return $resp;

    }

    public function get_cart_by_uid_and_proxy($card_user_id, $card_proxy){

        return $this->db->where(['card_user_id' => $card_user_id, 'card_proxy' => $card_proxy]
                )->get($this->tableName_cards)->row();
    }


    public function add_summ_to_own($id, $summ, $debit = NULL){

        $own_acc = $this->getUserOther($id);
        if ( !empty($own_acc) ){
            $this->db->set('summa', $own_acc->summa+$summ);
            $this->db->where('id', $id);
            $this->db->update($this->tableName_cards_users);


            $this->db->insert($this->tableName_cards_users.'_transactions',
                    [
                      'user_other_account_id'  => $id,
                      'old_summa' => $own_acc->summa,
                      'new_summa'  => $own_acc->summa + $summ,
                      'debit' => $debit,
                      'dttm' => date('Y-m-d h:i:s')
                    ]);
        }

    }
    /**
     * показывает мимя карты
     * @param type $card
     */
    static function display_card_name($card, $is_short = FALSE){
        if ( !is_object($card) ){
             $CI = &get_instance();
             $CI->load->model('Card_model', 'card');
             $card = $CI->card->getCard($card);
        }
        if ( $is_short)
            return '4665 **** **** '.substr($card->pan,-4);
        else
            return 'Webtransfer Visa 4665 **** **** '.substr($card->pan,-4).($card->card_type == self::CARD_TYPE_PLASTIC?' (Black)':' (Virtual)');

    }

    static function get_card_icon($card){
        if ( $card->card_type == self::CARD_TYPE_PLASTIC)
            return '/images/pcard-sm.png';
        else
            return '/images/vcard-sm.png';
    }


    static function display_own_account_name($account, $user_id = NULL){
        $user_id = ($user_id == NULL) ? get_current_user_id() : $user_id;

        if ( !is_object($account) ){
             $CI = &get_instance();
             $CI->load->model('Card_model', 'card');
            $account = $CI->card->getUserOther($account,$user_id);
        }

        if (empty($account)) return '';


        $result = '';
        switch  ($account->account_type){
                case 'BANK_CARD':
                    return $account->account_extra_data.' '.$account->bank_card_num;
                case 'BANK_ACCOUNT':
                    return $account->name;
                case 'E_WALLET':
                         if(!empty($account->own_wallet))
                            return $account->own_wallet.' '.$account->account_personal_num;
                        else
                            return $account->account_personal_num;

        }

        return $account->bank_card_num.' '.$account->account_extra_data.' '.$account->own_wallet.' '.$account->account_personal_num;
    }


    static function getBalance($card, $fromCache=false){
        $CI = &get_instance();
        $CI->load->model('Card_model', 'card');

        if ( !is_object($card) )
             $card = $CI->card->getCard($card);
        if (empty($card)) return 0;
        if ( $fromCache )
            return $card->last_balance;

        return $CI->card->getCardBalance($card);

    }


    static function get_own_account_icon($account, $user_id = NULL){

        $user_id = ($user_id == NULL) ? get_current_user_id() : $user_id;

        if ( !is_object($account) ){
             $CI = &get_instance();
             $CI->load->model('Card_model', 'card');
            $account = $CI->card->getUserOther($account,$user_id);
        }

        if ( empty($account) ) return '';

        switch  ($account->account_type){
                case 'BANK_CARD':
                case 'BANK_ACCOUNT':
                    return '/images/BANK_ACCOUNT3.png';
                case 'E_WALLET':
                         $res = $val->account_id.','.$val->id_user;

                         if(empty($account->own_wallet))
                            return '/images/'.$account->account_extra_data.'.png';
                        else
                            return '/images/E_WALLET.png';

        }
        return '';

    }





    public function remove_all_money_from_card($card, &$debug){

            if ( !is_object($card) ){
                $card = $this->getCard($card);
            }

            if ( empty($card)){
                echo "[$card]card not found";
                return FALSE;
            }


            $card_id = $card->id;
            $summ = $this->card->getCardBalance($card);
            if ( $summ <= 0){
                $debug = "[{$card_id}]Сумма на карте ($summ). Пропускаем." . PHP_EOL;
                return FALSE;
            }

            $id = time().'_PMCRON'.rand(1,1000);
            $purchaseMoney_data = [];
            $purchaseMoney_data['card_id']  = $card_id;
            $purchaseMoney_data['user_id']  = $card->user_id;
            $purchaseMoney_data['id'] = $id;
            $purchaseMoney_data['summa'] = $summ;
            $purchaseMoney_data['desc'] = 'purchase all money';
            $response = $this->card->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_REMOVE_ALL_MONEY, $id);
            //var_dump( $error );
             if(false !== $response) {
                  $debug = "[{$card_id}]Средства($summ) успешно сняты" . PHP_EOL;
                  return TRUE;
             } else {
                  $debug = "[{$card_id}]purchaseMoney: Ошибка зачисления" . PHP_EOL;
                  return FALSE;
             }

    }



    public function search_transactions($card, $what, $count = 2000, $offset=0){
        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
        $api_filter = isset($what['filter'])?$what['filter']:[];
        $transactions = $wtcapi->transactions($count, $offset, $api_filter );

        $result = [];
        foreach($transactions as $transaction){
            /*echo date('Y-m-d H:i:s', strtotime($transaction['tranDate']));
            if ( strtotime($transaction['tranDate']) <= strtotime('2016-01-15 00:00:00') )
                continue;*/
            $r = true;
            if ( isset($what['fields']) ){
                foreach( $what['fields'] as $field=>$find){
                    switch ( $find['type'] ){
                        case 'regexp':
                            if (!preg_match_all($find['match'], $transaction[$field], $all_matches) )
                                $r = false;
                            break;
                        case 'compare':
                            if ( $find['compare_type'] == 'equal' &&  !($transaction[$field] == $find['value'])  )
                                $r = false;
                            if ( $find['compare_type'] == 'better_equal' &&  !($transaction[$field] >= $find['value'])  ){
                                $r = false;
                            }
                            if ( $find['compare_type'] == 'less_equal' &&  !($transaction[$field] <= $find['value'])  ){
                                $r = false;                            
                            }
                            if ( $find['compare_type'] == 'diap' &&  !($transaction[$field] >= $find['value1'] && $transaction[$field] <= $find['value2'])  )
                                $r = false;
                            
                            break;

                        case 'date_compare':
                            if ( $find['compare_type'] == 'better_equal' &&  !(strtotime($transaction[$field]) >= strtotime($find['value']))  )
                                $r = false;
                            if ( $find['compare_type'] == 'diap' &&  !(strtotime($transaction[$field]) >= strtotime($find['value1'] && strtotime($transaction[$field]) <= strtotime($find['value2'])))  )
                                $r = false;
                            
                            
                            
                            break;
                    }
                }
            }

            if ( $r ){
              $result[] = $transaction;
              //pre($transaction); //transactionId
            }


        }
        return $result;

    }


    public  function start_arbitrage($card_id, $summ, $percent = 0.5, $time = 30){
        $res = new stdClass();
        $res->status = 'error';
        $res->message = '';

        $card = $this->getUserCard($card_id);
        if ( empty($card)){
            $res->message = _e('Нет такой карты');
            return $res;
        }

        $balance = self::getBalance($card);
        if ( $balance < $summ ){
            $res->message = _e('Баланс карты меньше запрашиваемой суммы');
            return $res;
        }

        $arbtr = self::get_fast_arbitrage_by_card($card);
        if ( !empty($arbtr)){
            $res->message = _e('Арбитраж eже запущен');
            return $res;
        }

        $purchaseMoney_data = [];
        $purchaseMoney_data['card_id']  = $card->id;
        $purchaseMoney_data['user_id']  = $card->id_user;
        $purchaseMoney_data['id'] = 'SA_'.$card->id.'_'.rand(1,100000);
        $purchaseMoney_data['summa'] = $summ;
        $purchaseMoney_data['desc'] = 'Mutual fund top up';
        $response = $this->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_START_ARBITRAGE, $purchaseMoney_data['id']);
        //var_dump( $error );
         if($response === false) {
            $res->message = _e('Не удалось снять средства с карты');
            return $res;
         }


        $this->load->model("var_model", 'var');
        $this->load->model('Users_model','users');
        $user_ip = $this->users->getIpUser();
        $invest_data = [
            'id_user' => $card->user_id,
            'user_ip' => $user_ip,
            'master' => $GLOBALS["WHITELABEL_ID"],
            'summa' =>  changeComa($summ),
            'garant' => 0,
            'direct' => 0,
            'overdraft' => 0,
            'time' => (int)$time,
            'confirm_payment' => 1,
            'kontract' => $this->var->get_kontract_count(),
            'date_kontract' => date('Y-m-d'),
            'percent' => changeComa($percent),
            'state' => Base_model::CREDIT_STATUS_ACTIVE,
            'type' => Base_model::CREDIT_TYPE_INVEST,
            'arbitration' => 1,
            'payment' => setPaymentOrger(),
            'out_summ' => credit_summ($percent, $summ, $time),
            'income' => 0,
            'out_time' => credit_time(date('Y-m-d'), $time),
            //'blocked_money' => 9,
            //'out_time' => credit_time(date('Y-m-d'), 30),
            'bonus' => 10,
            'account_type' => 'card',
            'account_id' => $card_id,
            'card_id' => $card_id

       ];

       $this->db->insert('credits', $invest_data);

       $res->status = 'success';
       $res->message = _e('Арбитраж успешно запущен');
        return $res;

    }




     public  function stop_arbitrage($card_id, $user_id = NULL){

        if ( empty($user_id))
            $user_id = get_current_user_id();

        $res = new stdClass();
        $res->status = 'error';
        $res->message = '';

        $card = $this->getUserCard($card_id, $user_id);
        if ( empty($card)){
            $res->message = _e('Нет такой карты');
            return $res;
        }

        $arbtr = self::get_fast_arbitrage_by_card($card);
        if ( empty($arbtr)){
            $res->message = _e('Арбитраж не запущен');
            return $res;
        }

        $income = self::get_fast_arbitrage_income($arbtr->summa, $arbtr->date, $arbtr->percent);
        $total_summ = $arbtr->summa + $income;


        $load_data = new stdClass();
        $load_data->id = 'SFA_'.$arbtr->id.'_'.rand(1,100000);
        $load_data->card_id = $card->id;
        $load_data->user_id = $card->user_id;
        $load_data->summa = $total_summ;
        $load_data->desc = 'Mutual fund repayment #'.$arbtr->id;
        $response = $this->card->load($load_data, Card_transactions_model::CARD_STOP_ARBITRAGE, $load_data->id );

        if(!$response) {
            $res->message = _e('Не удалось загрузить срества на карту');
            return $res;
        }


        $this->db->update('credits', ['income'=>$income, 'out_summ'=> $total_summ, 'state'=>  Base_model::CREDIT_STATUS_PAID,  'date_active' => date('Y-m-d H:i:s') ], ['id'=>$arbtr->id]);

        $this->load->model('User_arbitration_scores_model', 'user_arbitration_scores');
        $this->user_arbitration_scores->add_scores_by_invest($arbtr->id);

        $res->status = 'success';
        $res->message = _e('Арбитраж успешно оставнолен');
        return $res;

     }


     public static function get_fast_arbitrage_income($start_summ, $start_time, $percent){
         $one_minute_summ = $start_summ*($percent/100)/24/60;
         $total_minutes = round((time() - strtotime($start_time))/60,0);

         return round($total_minutes * $one_minute_summ,2);

     }

    public static function get_fast_arbitrage_by_card($card){

        $CI = &get_instance();
        if ( !is_object($card) ){
            $CI->load->model('Card_model', 'card');
            $card = $CI->card->getCard($card);
        }
        if ( empty($card))
            return FALSE;



        return $CI->db->where(['bonus'=>10,'card_id'=>$card->id, 'state'=>  Base_model::CREDIT_STATUS_ACTIVE])->get('credits')->row();


    }


    public function pay($id_user,$method,$card_id, $bank, $country, $summa  ){
        $this->load->helper('return_helper');

        $summa = price_format_save($summa);

       $min = 5;
        $max = 10000;

        if ( $bank == 'Qiwi' ){
                $max = 200;
        }

        if ( $summa > $max || $summa < $min){
             return returnError(sprintf(_e('Сумма должна быть больше $%d и меньше $%d.'),$min, $max));
        }

        if ( !is_numeric($card_id) ){
             return returnError( _e('Сначала закажите карту.'));
        }


        $this->load->model("transactions_model","transactions");
        $this->load->model('card_model', 'card');

        $note = 'Заявка на пополнение кошелька';
        // если это общая карта
        if ( $card_id == 0 ){
            $card = new stdClass();
            $card->card_user_id = 143950;
            $card->card_proxy = 246654439117985;
            $id = $this->transactions->addPay($id_user, $summa, 327, 327, 'bank', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, Base_model::TRANSACTION_BONUS_OFF, $note);
        } else {
            $card = $this->card->getUserCard($card_id);
        }

        // проверим есть карта
        if ( empty($card) ){
                 return returnError( _e('Не удалось получить данные о карте') );
        }


        if ( $method == 'FastBankTransfer')
          {
            $refId = $this->card->getCustomerUniqueRef($card);
            if ( empty($refId)){
                 return returnError( _e('Не удалось сгенерировать счет.') );
            }

            $bankInfo = $this->card->getBankInfo($country, $bank);
            if ( empty($bankInfo) ){
                    return returnError( _e('Не удалось получить данные о банке.') );
            }
            if ( _e('lang') == 'ru' )
                $html = $this->db->get_where('shablon', array('id_shablon' => 58))->row('sh_content');
            else
                $html = $this->db->get_where('shablon', array('id_shablon' => 57))->row('sh_content');

            $html = $this->mail->user_parcer($html, $id_user, array(
                                        'summa' => number_format($summa, 2, ".", " "),
                                        'id_check' => $id,
                                        'transaction_date' => date("d/m/Y"),
                                        'summa_add' => number_format(($summa ), 2, ".", " "),

                                        'bank.refID' => $refId,
                                        'bank.iban' => $bankInfo['iban'],
                                        'bank.swift' => $bankInfo['swift'],
                                        'bank.name' => $bankInfo['bankName'],
                                        'bank.addr' => $bankInfo['branchAddress'],
                                        'bank.account_name' => $bankInfo['accountName'],
                                        'bank.account_number' => $bankInfo['accountNumber'],
                                        'bank.code' => $bankInfo['bankCode'],
                                        'bank.account_currency' => $bankInfo['bankAccountCurrency'],
                                        'bank.account_country' => $bankInfo['bankAccountCountry']
             )
           );
           if ( !empty($id) )
                $id_hash = md5($id.'_'.$id_user);
           else
                $id_hash = md5($refId.'_'.$id_user.'_'.microtime());
           $filename = "cardcheck-{$id_hash}.pdf";
           if (empty($html)) {
                 return returnError( _e('Не удалось сгенерировать бланк счета.') );
           }
           $this->code->createPdf($html, 'checks', $filename);

            return returnJson(['id' => $refId, 'checkLink'=>'/upload/checks/'.$filename]);


        } else {

            //if ( in_array($bank, ['OkPay','Payeer','PerfectMoney','NixMoney'] )){}


             $serviceInfo = $this->card->getServiceUrl($card, $summa, $bank, $country);
             //echo json_encode( ['error' => _e('Не удалось сгенерировать бланк счета.') ] );
             //echo json_encode( ['error' =>  'r:'.$serviceInfo['serviceRedirectUrl']] );
             if ( empty($serviceInfo['customerReference']) || empty($serviceInfo['serviceRedirectUrl']) ){
                 if ( $serviceInfo['error'] == 'EnvoyIn rejected for this KYC')
                    return returnError( _e('Держателям карт 1-го уровня (KYC1) пополнение доступно только на сайте <a href="https://webtransfer.exchange" target="_blank">webtransfer.exchange</a>. Для увеличения доступных методов пополнения необходимо загрузить документы на сайте <a href="https://webtransfercard.com" target="_blank">www.webtransfercard.com</a>  и дождаться пока карте будет присвоен уровень 2.'));
                 else
                  return returnError( _e('Ошибка сервиса: '.$serviceInfo['error']));
             }
             return returnJson(['id' => $serviceInfo['customerReference'], 'redirectLink'=>$serviceInfo['serviceRedirectUrl'] ]);
        }


        //accaunt_message($data, _e('account/data9'), 'good');

        /*
        if('wtcard' == $metod) {
            $this->load->model('card_model', 'card');
            $response = $this->card->purchaseMoney(['id' => $id, 'summa' => $summa, 'desc' => $this->input->post('description')]);
            if(false !== $response) {
                $this->db->update('transactions', array('status' => Base_model::TRANSACTION_STATUS_RECEIVED,'note'=>$note.'  Транзакция № '.$response), array('id' => $id));
                $transaction_item = $this->db->get_where('transactions', array('id' => $id))->row();
                $this->transactions->payPaymentBonus($transaction_item);
            }
            echo json_encode(['id' => $id]);
            return;
        }
         */
        return returnError( _e('Неизвестная ошибка.') );

    }


    public function get_send_money_limit($card_id, $card_balance = NULL){
        $this->load->model('Accaunt_model', 'accaunt');
        $this->load->helper('return_helper');
        $card = $this->getCard($card_id);
        if ( empty($card))
             return 0;




        if ( $card_balance === NULL )
            $card_balance = $this->getCardBalance($card);
        if ( $card_balance === null)
            return 0;

        //$rating_by_bonus = $this->accaunt->recalculateUserRating($card->user_id); // uncoment for work
        return $card_balance;//min($card_balance, $rating_by_bonus['active_cards_invests'] + $card_balance - $rating_by_bonus['active_cards_credits_outsumm']); // uncoment for work




    }
    
    
    public function get_cards_by_level($user_id, $level){
        return $this->db->where(['user_id'=>$user_id, 'kyc'=>$level])->get('cards')->result();
    }
    
    
    public function card_load_docs($card){
            if ( empty($card))
                return _e('Не найдена карта');
        
            $pics = $this->db->where('id_user', $card->user_id)->get('documents')->result();
            if(empty($pics)){
                return _e('У вас не загружены документы');
            }
            foreach($pics as $row){
                if(1 == $row->num && $row->state == 2)
                    $docId   = $row->img;
                if(2 == $row->num && $row->state == 2)
                    $docAddr = $row->img;
            }
            if(empty($docId) || empty($docAddr)){
                return _e('У вас не загружены или не подтверждены документы');
            }

            $docId   = $this->code->fileDecode("upload/doc/".$docId);
            $docAddr = $this->code->fileDecode("upload/doc/".$docAddr);
            $this->load->library('WTCApi');
            $wtcapi  = new WTCApi($card->card_user_id, $card->card_proxy);
            $resp    = $wtcapi->sendDocs($docId, $docAddr);
            if(strpos($wtcapi->getError(), 'We have received you') !== false)
                    return TRUE;
            else
                return $wtcapi->getError();

    }
    
    
    public function create_card_by_request($request_id){
                $this->load->helper('return_helper');
                $request = $this->db->where('id',$request_id)->get('cards_requests')->row();
                if ( !empty($request)){
                    $this->load->library('WTCApi');
                    
                    $wtcapi = new WTCApi();
                    
                    $user_id = $request->id_user;
                    
                    $user = new stdClass();
                    $this->accaunt->set_user($user_id);
                    $this->accaunt->get_user($user);                                
                    
                    $email = $user->user->email;                    

                    $create_card = $wtcapi->card($request);
                    dev_log(var_export($create_card, TRUE),'create_card_by_request');
                    if(!empty($create_card['errors'])){
                        $this->card->save_request($request_id, [
                            'decline_error' => json_encode($create_card['errors']),
                            'declined' => date("Y-m-d H:i:s"),
                            'status' => 'pay_declined'
                        ]);

                        $errorStr = '';
                        foreach ($create_card['errors'] as $error)
                            $errorStr .= $error['errorDescription'].';<br> ';


                        dev_log("return error",'create_card_by_request');
                         return ['message'=>$errorStr,'status'=>'error'];
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
                            'email' => $email,
                            'status' => 0,
                            'last_update' => date("Y-m-d H:i:s"),
                            'last_balance' => 0
                        ];
                        dev_log("save: ".var_export($new_card_data, TRUE),'create_card_by_request');
                        $this->card->create_card($new_card_data);

                        $this->load->library('Wtcard_api');
                        $wtcapi = new Wtcard_api(config_item('Wtcard_api_login'), config_item('Wtcard_api_pass'));
                        
                        $wtcapi->user_cards_import($create_card['cardDetail']['proxy'], $create_card['usrId'], $email, md5("{$create_card['usrId']}:{$create_card['cardDetail']['proxy']}:{$data->email}:silniy_kolobok13") );
                        return ['message'=>_e('Карта успешно создана'),'status'=>'success'];
                        

                        

                    }else{
                        dev_log("no user_id or proxy",'create_card_by_request');
                        $this->card->save_request($request_id, [
                            'decline_error' => json_encode(array("errorCode"=>"our api", "errorDescription"=>"I cannot find valid json answer, no userID or card proxy specified")),
                            'declined' => date("Y-m-d H:i:s"),
                            'status' => 'pay_declined'
                        ]);
                        return ['message'=>_e('no user_id or proxy'),'status'=>'error'];
                        
                        
                    }
                    
                }        
                return ['message'=>_e('Неизвестная ошибка.'),'status'=>'error'];
                
    }
    
    
    public function get_pay_declined_requests($user_id){
        return $this->db->get_where($this->tableName_requests, 'id_user='.$user_id.' AND card_type='.self::CARD_TYPE_PLASTIC.' AND card_proxy IS NULL AND processed IS NULL AND status = "pay_declined"')->result();        
    }




}
