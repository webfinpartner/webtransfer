<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accaunt_model extends CI_Model{

    protected $id_user            = null;
    static public $userId4Mempeak = null;
    protected $users_rate         = [];
    protected $users_rate_on      = true;

    public function offUserRateCach(){
        $this->users_rate_on = false;
    }

    public function onUserRateCach(){
        $this->users_rate_on = true;
    }

    public function __construct(){
        parent::__construct();
    }

    public function getUserRate($id_user){
        if(isset($this->users_rate[$id_user]))
            return $this->users_rate[$id_user];
        else
            return false;
    }

    public function delTransaction($id){
        $id_user    = $this->get_user_id();
        $in_process = Base_model::TRANSACTION_STATUS_IN_PROCESS;
        $not_resive = Base_model::TRANSACTION_STATUS_NOT_RECEIVED;
        $security   = Base_model::TRANSACTION_STATUS_VEVERIFY_SS;
        $this->db->update("transactions", array("status" => Base_model::TRANSACTION_STATUS_DELETED), "id = $id AND (((status = $in_process OR status = $security) AND type <> 310 AND type <> 323) OR (status = $not_resive AND (metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen'))) AND id_user = $id_user", 1
        );
    }

    public function set_user($id_user){
        $this->id_user        = $id_user;
        self::$userId4Mempeak = $this->id_user;
    }

    public function get_user_id(){
        if(empty($this->id_user)){
            $this->load->model("users_model", "users");
            $id_user = $this->users->getCurrUserId();
        } else
            $id_user              = $this->id_user;
        self::$userId4Mempeak = $id_user;
        return $id_user;
    }

    public function getUsrId4UsersModel(){
        return $this->id_user;
    }

    public function get_documents(){

        $docs = $this->own()
            ->order_by('num')
            ->get('documents')
            ->result();

        if(empty($docs))
            return FALSE;

        foreach($docs as $doc){
            $path = "upload/doc/".$doc->img;
            if(file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) == 'pdf'){
                $doc->ext = 'pdf';
            }
        }

        return $docs;
    }

    public function getAccessDocuments(){
        if(!$this->id_user)
            return $this->id_user;
        $count = $this->own()->where_in('num', array('1', '2'))->where('state', 2)->count_all_results('documents');
        return ($count != 2) ? false : true;
    }

    public function isNotInCreditOverdraftLimit($user_id = null){
        if(null == $user_id){
            if($this->id_user)
                $user_id = $this->id_user;
            else
                return false;
        }
        $count = $this->db->where(
                "id_user = $user_id "
                ."AND overdraft = ".Base_model::CREDIT_OVERDRAFT_ON." "
                ."AND (state = ".Base_model::CREDIT_STATUS_ACTIVE." "
                ."OR state = ".Base_model::CREDIT_STATUS_SHOWED.")"
            )
            ->count_all_results('credits');
        return ($count < 5) ? false : true;
    }

    public function isUserDocumentVerified($user_id = null){

        if(!$user_id){
            if($this->id_user)
                $user_id = $this->id_user;
            else
                return false;
        }

        $count = $this->db->where(array('id_user' => $user_id, 'num' => 1, 'state' => 2))
            ->count_all_results('documents');
        return ($count == 1) ? true : false;
    }

    public function isUserPhoneVerified($user_id = null){
        if(!$user_id){
            if($this->id_user)
                $user_id = $this->id_user;
            else
                return false;
        }

        $this->load->model('phone_model', 'phone');

        return $this->phone->isStatusVerified($user_id);
    }

    public function isUS2USorCA($id_user){
        $this->load->model('phone_model', 'phone');

        $user_phone = $this->phone->getPhoneWithCode($id_user);
        $my_phone   = $this->phone->getPhoneWithCode($this->get_user_id());
        if(in_array($my_phone['short_name'], array('CAN', 'USA')) and in_array($user_phone['short_name'], array('CAN', 'USA'))){
            return true;
        }
        return false;
    }

    public function isUserUSorCA($id_user = null){

        $this->load->model('phone_model', 'phone');
        if(empty($id_user))
            $id_user = $this->get_user_id();


        $user_phone = $this->phone->getPhoneWithCode($id_user);
        if(in_array($user_phone['short_name'], array('CAN', 'USA'))){
            return true;
        }
        return false;
    }

    public function isUserAccountVerified($user_id = null){
        $this->load->model("users_model", "users");
        if(!$user_id){
            if($this->id_user)
                $user_id = $this->id_user;
            else
                return FALSE;
        }

        if($this->users->isUserActive($user_id) &&
            //$this->isUserDocumentVerified($user_id) )
            ($this->isUserDocumentVerified($user_id) || $this->isUserPhoneVerified($user_id))) #TURN_OFF_PHONE_VEARIFICATION
            return TRUE;

        return FALSE;
    }

    public function own(){
        $this->db->where('id_user', $this->id_user);
        return $this->db;
    }

    /*
     *  Start Social  network
     */

    public function get_social(){
        return $this->getSocialList($this->id_user);
    }

    public function getSocialList($id_user, $secure = false){
        $data              = array();
        $select = 'name, id_social, foto, url';
        if($secure) $select = 'name, foto, url';
        $result            = $this->code->db_decode($this->db->where('id_user', $id_user)->select($select)->get('social_network')->result());
        foreach($result as $item)
            $data[$item->name] = $item;
        return $data;
    }

    public function isSocialAvailable($user_id, $names){
        foreach($names as &$n)
            $n   = $this->code->code($n);
        $res = !empty($this->db->where('id_user', $user_id)->where_in('name', $names)->get('social_network')->result());
        return $res;
    }

    /*     * Нельзя откреплять соц сети. теперь нужно это включить.
     *
     * @param type $name
     * @return boolean
     */

    public function social_delete($name){
        $this->own()->where('name', $this->code->code($name))->delete('social_network');
    }

    /*
     * End Social  work
     */

    /*
     * Work whith user
     */

    public function get_user(&$data, $update = false){

        $id              = $this->id_user;
        $id_change       = $this->own()->select('id_user')->get_where('chanche_users')->row('id_user');
        $data->id_change = $id_change;
        if(empty($id_change)){
            $this->load->model("users_model", "users");
            $cur_user = $this->users->getCurrUserId();
            if($id == $cur_user && false == $update){
                $dataCurUser = $this->users->getCurrUserData();
                $data->user  = $dataCurUser;
            } else
                $data->user  = $this->code->db_decode($this->db->get_where("users", array('id_user' => $id))->row());
            $data->adr_f = $this->code->db_decode($this->db->get_where("address", array('id_user' => $id, 'state' => '2'))->row());
            $data->adr_r = $this->code->db_decode($this->db->get_where("address", array('id_user' => $id, 'state' => '1'))->row());
            $data->adr_h = $this->code->db_decode($this->db->get_where("address", array('id_user' => $id, 'state' => '3'))->row());
        } else {
            $data->user        = $this->code->db_decode($this->db->get_where("chanche_users", array('id_user' => $id))->row());
            $data->user->face  = $this->get_user_field($this->id_user, 'face');
            $data->user->email = $this->get_user_field($this->id_user, 'email');
            $data->user->place = $this->get_user_field($this->id_user, 'place');

            $data->adr_f         = new stdClass();
            $data->adr_f->index  = $data->user->findex;
            $data->adr_f->town   = $data->user->ftown;
            $data->adr_f->street = $data->user->fstreet;
            $data->adr_f->house  = $data->user->fhouse;
            $data->adr_f->kc     = $data->user->fkc;
            $data->adr_f->flat   = $data->user->fflat;

            $data->adr_r         = new stdClass();
            $data->adr_r->index  = $data->user->rindex;
            $data->adr_r->town   = $data->user->rtown;
            $data->adr_r->street = $data->user->rstreet;
            $data->adr_r->house  = $data->user->rhouse;
            $data->adr_r->kc     = $data->user->rkc;
            $data->adr_r->flat   = $data->user->rflat;
        }
    }

    public function getMainUser(){
        $this->load->model("users_model", "users");
        return $data = $this->users->getCurrUserData();
    }

    public function getParentUser($user_id = null){
        if($user_id == null)
            $user_id = $this->id_user;

        if($user_id == null)
            return false;

        return $this->code->db_decode($this->db
                    ->select('p.id_user, p.name, p.sername, p.email,p.skype')
                    ->from("users u")
                    ->join("users p", "p.id_user = u.parent")
                    ->where('u.id_user', $this->id_user)
                    ->get()
                    ->row());
    }

    public function get_user_photos($user_id = null){
        $data = array();

        $data['photo_50_default']  = '/img/no-photo-100.jpg';
        $data['photo_100_default'] = '/img/no-photo.gif';
        $data['photo_50']          = null;
        $data['photo_100']         = null;

        if($user_id == null)
            $user_id = $this->id_user;

        if($user_id == null){
            $data['photo_50']  = $data['photo_50_default'];
            $data['photo_100'] = $data['photo_100_default'];

            return $data;
        }

        $social = $this->accaunt->getSocialList($user_id);

        foreach($social as $item){
            if(empty($data['photo_50']) &&
                !empty($item->foto) &&
                @file_get_contents($item->foto)
            )
                $data['photo_50'] = $item->foto;

            if(empty($data['photo_100']) &&
                !empty($item->photo_100) &&
                @file_get_contents($item->photo_100)
            )
                $data['photo_100'] = $item->photo_100;

            if(!empty($data['photo_100']) &&
                !empty($data['photo_50'])
            )
                break;
        }
        if(empty($data['photo_50']))
            $data['photo_50']  = $data['photo_50_default'];
        if(empty($data['photo_100']))
            $data['photo_100'] = $data['photo_100_default'];

        return $data;
    }

    public function get_user_field($id, $field){
        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->{$field};
        }
        $res = $this->db->where('id_user', $id)->select($field)->get('users')->row($field);
        if(!$res)
            return null;
        return $this->code->decode($res);
    }

    public function getUserFields($id, $fields){
        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($id == $cur_user){
            $data     = $this->users->getCurrUserData();
            $nickName = viewData()->nick;
            if(false === $nickName){
                return $data;
            }
            $data->name    = $nickName;
            $data->sername = '';
            return $data;
        }
        $this->load->model('users_filds_model', 'usersFilds');
        $res = $this->db->select($fields)->where('id_user', $id)->get('users')->row();

        if(!$res)
            return null;

        $nickName = $this->usersFilds->getUserNickname($id);

        if(FALSE === $nickName){
            return $this->code->db_decode($res);
        }

        $res = $this->code->db_decode($res);

        $res->name    = $nickName;
        $res->sername = '';

        return $res;
    }

    public function getIdUserByData($id){
        return $this->db
                ->where('id_user', $id)
                ->or_where('email', $this->code->code($id))
                ->or_where('phone', $this->code->code($id))
                ->select('id_user')
                ->get('users')
                ->row('id_user');
    }

    public function user_field($field, $true = true){
        $this->load->model("users_model", "users");
        $data = $this->users->getCurrUserData();
        return $data->{$field};
//        $res = $this->own()->select($field)->get('users')->row($field);
//        if ($true)
//            return $this->code->decrypt($res);
//        else
//            return $res;
    }

    public function userFields($fields){
        $this->load->model("users_model", "users");
        $data = $this->users->getCurrUserData();
        return $data;
//        $res = $this->own()->select($fields)->get('users')->row();
//        if (!$res)
//                return null;
//
//        return $this->code->db_decode($res);
    }

    public function update_user_field($field, $value, $code = true){
        $data = [];
        $data[$field] = ($code) ? $this->code->code($value) : $value;
        return $this->own()->update('users', $data);
    }

    public function update_profile($id = null){
        if(null == $id)
            $id = $this->user->id_user;

        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('users_model', 'users');


        $data = $this->base_model->user_fields(true);
        // проверка на подветреждение главной страницы паспорта
        $this->load->model('Documents_model', 'documents');
        //если главная страница подтверждена, то блокируем сохранение ФИО
        if($this->documents->getUserDocumentStatus($id) == Documents_model::STATUS_PROVED &&
            $this->users->isUsedOnlyLatinLanguageNames() === TRUE){
            unset($data['name']);
            unset($data['sername']);
            unset($data['patronymic']);
        }

        $this->own()->update('users', $data);



        $userFilds = array(
            "id_user"  => $id,
            "nickname" => $this->input->post('nickname') ? $this->input->post('nickname') : '',
            "is_show"  => $this->input->post('is_show'),
        );

        $this->usersFilds->saveUserFilds($userFilds);
        $this->own()->where('state', 1)->update('address', $this->base_model->get_user_place('r'));
        $this->own()->where('state', 2)->update('address', $this->base_model->get_user_place('f'));
        return $data;
    }

    public function online($id_user){
        $this->db->update('users', array('online_date' => date("Y-m-d H:i:s")), array("id_user" => $id_user), 1);
    }

    public function get_header_info(){

        $this->load->model('inbox_model', 'inbox');
        $this->load->model('volunteer_topic_model');
        $this->load->model('user_balances_model', 'user_balances');
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('Card_model', 'cards');
        $this->load->model('Currency_exchange_model', 'currency_exchange');

        $out['invests'] = $this->getDebitSumm(2);
        $out['credits'] = $this->getDebitSumm(1);
        $user_balance   = $this->recalculateUserRating();


// <editor-fold defaultstate="collapsed" desc="save balance to DB">
//        $user = new stdClass; //
//        $user->id_user = $this->user->id_user;
//        $user->parent = $this->user->parent;
//        $user->balance = $this->user_balances->calculateBalance($user_balance);
//        if(!$this->user_balances->checkBalance($this->user->id_user))
//            $this->user_balances->addBalance($user);
//        else $this->user_balances->updateBalance($user);//
// </editor-fold>
        //copy all variables
        foreach($user_balance as $key => $val)
            $out[$key] = $val;

        $out['wallet_bonus']     = $user_balance['bonuses'];
        //$out['wallet_ceshCREDS'] = $this->base_model->getCeshCREDS();
        $out['wallet_ceshCREDS'] = $user_balance['c_creds_total'];
//        $out['wallet_money'] = $user_balance['payment_bonus_account'][2];//$this->base_model->getRealMoney(); // не используется, а лишний запрос идет
//        /*$balanc = */$out['money'] = $this->base_model->getMoney(); // не используется, а лишний запрос идет

        $this->load->model('users_filds_model', 'usersFilds');
        $out['social_id'] = $this->usersFilds->getUserFild($this->id_user, 'id_network');

        $this->load->library('soc_network');
        $out['socialMessagesNewCnt']       = $this->soc_network->checkMsg($this->id_user);
        $out['socialFriendsPending']       = $this->soc_network->pending_friends($this->id_user);
        $out['socialMessagesNewList']      = $this->soc_network->messages($this->id_user);
        $out['socialNotificationsNewList'] = $this->soc_network->notifications($this->id_user);
        $out['chat_messages']              = $this->currency_exchange->chatCountMessages($this->id_user);



        if($out['socialMessages'] > 99)
            $out['socialMessages'] = '&infin;';


        $out['allMessages']     = $out['newMessage']      = $this->inbox->countNew();
        $out['allMessagesList'] = $out['newMessageList']  = $this->inbox->inboxNew(TRUE, 5);
        $out["openTopics"]      = $this->volunteer_topic_model->countActive();
        if($out['newMessage'] > 99)
            $out['allMessages']     = $out['newMessage']      = '&infin;';
        else
            $out['allMessages'] += $out["openTopics"];


        $list  = $this->getBalans();
        $money = $this->getDataMoneyTransaction();

        $out['total_cards_balance']       = $this->cards->getCardsBalance($this->id_user);
        $out['total_other_cards_balance'] = $this->cards->getOtherBalance($this->id_user);


        $moneyOut       = $moneyIn        = $incomeSumma    = $incomePribil   = $otchisleniya   = $outcomeSumma   = $outcomePercent = 0;
        foreach($list as $item){
            if($item->type == 2){
                $incomeSumma += $item->summa;
                $incomePribil += $item->income;

                if($item->garant == 0)
                    $otchisleniya += $item->income * 0.10;
                else if($item->garant == 1)
                    $otchisleniya += $item->income * ((garantPercent($item->time) / 100));
            }
            else if($item->type == 1){
                $outcomeSumma += $item->summa;
                $outcomePercent += $item->income;
            }
        }

        $moneyOut = $moneyIn  = 0;
        foreach($money as $item){
            if($item->status == 3)
                $moneyOut += $item->summa;
            else if($item->status == 1)
                $moneyIn += $item->summa;
        }

//        $out['balance'] = $this->getUserBalance(); //($incomeSumma + $incomePribil - $otchisleniya) - ($outcomeSumma + $outcomePercent) + ($moneyIn - $moneyOut);

        $out['userNickname'] = $this->usersFilds->getUserNickname($this->id_user);

        return $out;
    }

    public function getBalans(){
        return $this
                ->own()
                ->where('state', 2)
                ->get('credits')
                ->result();
    }

    public function getDataMoneyTransaction(){
        return $this->own()->select('summa, status')->get('transactions')->result();
    }

    public function garantPercent($time){
        return $this->getPercent($time, 'g');
    }

    public function standartPercent($time){
        return $this->getPercent($time, 's');
    }

    public function getPercent($time, $type = 'g'){
        if($type == 'g')
            $data = config_item('garant-percent');
        else
        if($type == 's')
            $data = config_item('standart-percent');

        $all = array_key_exists('*', $data);

        if($all !== false)
            return $data['*'];

        foreach($data as $index => $item)
            if($time <= $index){
                return $item;
            }
    }

    //расчет по
    public function currency_exchange_scores($current_user_id = false){
        $this->load->model('currency_exchange_model', 'currency_exchange');

        if($current_user_id === false)
            $current_user_id = $this->get_user_id();

        return $this->currency_exchange->currency_exchange_scores($current_user_id);
    }

    //возвращает массив для расчета комиссии для методов
    function get_payout_fee_by_method($method_name){

        if(empty($method_name) || is_numeric($method_name))
            return FALSE;

        //<editor-fold defaultstatus="Список методов">
        $method_fees = array(
            //banks
            'bank'         => array('id' => 101, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0),
            //payment systems
            'lava'         => array('id' => 201, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0),
            'okpay'        => array('id' => 202, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0),
            'payeer'       => array('id' => 203, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0), //203,
            'perfectmoney' => array('id' => 204, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0), //204,
            'qiwi'         => array('id' => 205, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0), //205,
            //cards
            'mc'           => array('id' => 301, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0),
            //others
            'out'          => array('id' => 951, 'fee_add' => 0, 'fee_procent' => 0, 'fee_min' => 0, 'fee_max' => 0), //950
        );
        //</editor-fold>

        if(!isset($method_fees[$method_name]))
            return FALSE;

        return $method_fees[$method_name];
    }

    //расчет комиссии за вывод
    public function calc_payout_fee($payout_system, $amount){
        if(empty($payout_system))
            return FALSE;

        if($amount === 0)
            return 0;

        if(empty($amount))
            return FALSE;

        $payout_psnt = countPesnt($amount, $payout_system);
        //$this->load->library('form_validation');
//        // посчитаем процент для карт
//        if ( $set_account_bonus == 2 && $payout_system == 'wtcard'){
//            if ( $amount > $total_income_money_banks  ){
//
//                $cur  = $payuot_systems_psnt[$payout_system];
//                $payout_psnt  = $amount * $cur["psnt_max"] / 100;
//                if($payout_psnt  < $cur["min"])
//                    $payout_psnt  = $cur["min"];
//                $payout_psnt  = $payout_psnt  + $cur["add"];
//            }
//        }

        return $payout_psnt;
    }

    public function get_payout_fee_by_item($item){
        if(empty($item))
            return FALSE;

        return $this->calc_payout_fee($item->metod, $item->summa);
    }

    public function recalculateUserRating($_id = null, $date_end = null, $bonus = null, $date_start = null){
        if(!$_id)
            $_id = $this->id_user;
        if($_id === 0)
            return 0;
        if($this->users_rate_on && isset($this->users_rate[$_id]) && null == $date_end && null == $bonus && null == $date_start){
            return $this->users_rate[$_id];
        }


        //$this->load->model('testusers_model','testusers');
        //$test_users = $this->testusers->get_users_by_purpose('save_scores');
//        $need_calculate_id = $this->getNeedRecalculateId( $_id );
//        if( $_id == 500733 ) var_dump( $test_users);
        //если пусто - не нужно пересчитывать
//        if( in_array($_id, $test_users) &&
//            empty( $need_calculate_id ) )
//        {
        //var_dump($need_calculate_id);
//            $last_rating = $this->getLastUserRating( $_id );
//            if( !empty( $last_rating ) )
//            {
//                echo "return last--";
//                return $last_rating;
//            }
//        }
//        if( $_id == 500733 )
//        echo "calculating--";
        // <editor-fold defaultstate="collapsed" desc="load models">
        $this->load->model('partner_model', 'partner');
        $this->load->model('credits_model', 'credits');
        $this->load->model('users_model', 'users');
        // </editor-fold>

        $this->partner->setCurrentUserId($_id);
        $expected_partnership_income = $this->partner->getMySoonMoney(); //партнерская прибыль дату не учитывает


        $currency_exchange_scores = $this->currency_exchange_scores($_id);


        //SELECT * FROM `credits` WHERE `id_user`= AND `state`=2
        if(null !== $date_end)
            $this->db->where("DATE(date) <= DATE('$date_end')");
        if(null !== $date_start)
            $this->db->where("DATE(date) >= DATE('$date_start')");
        if(null !== $bonus){
            if($bonus == 2)
                $this->db->where_in("bonus", [0, 2]);
            else
                $this->db->where("bonus", $bonus);
        }
        $all_inquiries = $this->db->where('id_user', $_id)->get('credits')->result();

        //<editor-fold defaultstate="collapsed" desc="Объявление переменных">
        //TODO: бонус = 1 пересчитать в общие переменные
        $null_array_by_bonus = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7=>0);
        $null_array_keys     = array_keys($null_array_by_bonus);

        $cur_date_str           = date('Y-m-d', time());
        $cur_timestamp          = strtotime($cur_date_str);
        $current_garant_percent = 0.065;
        $max_loan               = 1900; //new1
        $max_rating             = 84;

        $total_active_blocked_own2     = 0;
        $total_active_usd2_fee_invests = 0;

        $my_investments_garant                  = 0; //сумма вложений по гаранту
        $my_investments_garant_by_bonus         = $null_array_by_bonus; //сумма вложений по гаранту
        $my_investments_garant_bonuses          = 0; //сумма вложений по гаранту бонусный
        $my_investments_garant_percent          = 0; //проценты по гаранту
        $my_investments_garant_percent_by_bonus = $null_array_by_bonus; //проценты по гаранту
        $my_investments_standart                = 0; //сумма вложений по гаранту
        $my_investments_standart_by_bonus        = $null_array_by_bonus; //сумма вложений по гаранту
        $my_loans                               = 0;   //займы
        $my_loans_by_bonus                      = $null_array_by_bonus;   //займы
        $loans_garant_by_bonus                  = $null_array_by_bonus;   //займы ганарт


        $c_creds_amount_invest        = 0; //размер средств на счете C-CREDS
        $c_creds_amount_invest_active = 0; //размер средств на счете C-CREDS
        /// type=2 state=2 bonus=4
//        $loans_standart_outsumm = 0;   //сумма всех полученных стандартных займов
//        $my_balance = 0;    //баланс
        $my_loans_percentage          = 0; //Проценты расход на весь срок
        $my_loans_percentage_by_bonus = $null_array_by_bonus; //Проценты расход на весь срок

        $my_loans_percentage_garant          = 0; //проценты на выплату по гаранту
        $my_loans_percentage_garant_by_bonus = $null_array_by_bonus; //проценты на выплату по гаранту

        $my_total_future_income_standart = 0;  //всего проценты по стандрант
        $my_total_future_income_garant   = 0;     //всего проценты по стандрант

        $my_future_income_standart = 0;  //перспективные проценты по стандрант
        $my_future_income_garant   = 0;  //перспективные проценты по гаранту

        $all_advanced_loans_out_summ               = 0; //Выставленные заявки на займ Гарант Деньги
        $all_posted_garant_loans_out_summ_by_bonus = $null_array_by_bonus; //Выставленные заявки на займ Гарант Деньги
        $all_advanced_invests_summ                 = 0; //Выставленные заявки на вклад Гарант Деньги
        $all_advanced_invests_summ_by_bonus        = $null_array_by_bonus; //Выставленные заявки на вклад Гарант Деньги

        $all_advanced_invests_bonuses_summ = 0; //Выставленные заявки на вклад Гарант Бонусы

        $all_advanced_standart_loans_out_summ        = 0;  //выставленные займы Стандарт
        $all_advanced_standart_invests_summ          = 0;    //выставленные заявки на вклад Стандарт
        $all_advanced_standart_invests_summ_by_bonus = $null_array_by_bonus;    //выставленные заявки на вклад Стандарт

        $invests_own_sum_by_bonus                   = $null_array_by_bonus;
        $invests_showed_and_active_own_sum_by_bonus = $null_array_by_bonus;

//        $all_active_garant_loans_sum = 0; //сумма акивных займов гарант (без процентов)

        $total_garant_loans          = 0; //автивные заявки Гарант на Займ
        $total_garant_loans_by_bonus = $null_array_by_bonus; //автивные заявки Гарант на Займ

        $total_arbitrage           = 0; //сумма всех активных займов Арбитраж
        $total_arbitrage_by_bonus  = $null_array_by_bonus; //сумма всех активных займов Арбитраж
        $total_arbitrage2          = 0;
        $total_arbitrage3          = 0;
        $total_arbitrage4          = 0;
        $total_arbitrage5          = 0;
        $total_arbitrage2_by_bonus = $null_array_by_bonus;
        $total_arbitrage3_by_bonus = $null_array_by_bonus;
        $total_arbitrage4_by_bonus = $null_array_by_bonus;
        $total_arbitrage5_by_bonus = $null_array_by_bonus;

        $total_arbitrage_percentage          = 0; //проценты по займам Арбитраж
        $total_arbitrage_percentage_by_bonus = $null_array_by_bonus; //проценты по займам Арбитраж

        $arbitrage_collateral                 = 0;    //обеспечение по Арбтражу
        $overdraft                            = 0;
        $loans_standart_out_summ              = 0;
        $loans_standart_out_summ_by_bonus     = $null_array_by_bonus;
        $direct_collateral                    = 0;         //ожидающие вклады Директ
        $all_posted_garant_direct             = 0;          //выставленные директы
        $all_posted_garant_direct_by_bonus    = $null_array_by_bonus;          //выставленные директы
        $all_pending_garant_direct            = 0;
        $all_pending_garant_direct_by_bonus   = $null_array_by_bonus;
        $all_posted_standart_direct           = 0;        //выставленные директы
        $all_posted_standart_direct_by_bonus  = $null_array_by_bonus;        //выставленные директы
        $all_pending_standart_direct          = 0;       //в ожидани
        $all_pending_standart_direct_by_bonus = $null_array_by_bonus;       //в ожидани

        $all_active_garant_loans_out_summ          = 0;  //сумма out_summ всех активных займов Гарант
        $all_active_garant_loans_out_summ_by_bonus = $null_array_by_bonus;  //сумма out_summ всех активных займов Гарант
        $all_active_garant_loans_summa             = 0;     //сумма summa всех активных займов Гарант
        $all_active_garant_loans_summa_by_bonus    = $null_array_by_bonus;     //сумма summa всех активных займов Гарант

        $overdue_standart       = array();    //количество просроченных займов стандарт
        $overdue_standart_count = 0;    //количество просроченных займов стандарт
        $overdue_garant         = array();    //количество просроченных займов гарант
        $overdue_garant_count   = 0;    //количество просроченных займов гарант

        $overdue_garant_interest          = 0;    //проценты по просроченным займам гарант
        $overdue_garant_interest_by_bonus = $null_array_by_bonus;    //проценты по просроченным займам гарант
        $my_investments_garant_partner    = 0;

        $active_cards_invests         = 0;
        $active_cards_invests_outsumm = 0;
        $active_cards_garant_invests  = 0;

        $active_cards_credits         = 0;
        $active_cards_credits_outsumm = 0;
        $active_cards_garant_credits  = 0;

        $showed_cards_credits        = 0;
        $showed_cards_garant_credits = 0;
        $showed_cards_invests        = 0;
        $showed_cards_garant_invests = 0;

        $creds_amount_invest_by_bonus[2] = $creds_amount_invest_by_bonus[5] = 0;

        $arbitration_garant_active_summ          = 0;
        $arbitration_garant_active_summ_by_bonus = $null_array_by_bonus;
        $arbitration_active_summ                 = 0;
        $arbitration_active_summ_by_bonus        = $null_array_by_bonus;
        $arbitration_shown_summ                  = 0;
        $arbitration_shown_summ_by_bonus         = $null_array_by_bonus;
        $old_arbitration_active_summ             = 0;
        $old_arbitration_active_summ_by_bonus    = $null_array_by_bonus;
        $sum_loan_active_summ                    = 0;
        $sum_loan_active_summ_by_bonus           = $null_array_by_bonus;

        $loan_garant_active_summ = 0;
        $new_arbitr_date         = strtotime('2015-08-04 22:00:00'); //дата перехода на коэф 5

        $all_active_garant_loans_summa_new_by_bonus = $null_array_by_bonus;

        $loan_active_cnt                   = 0;
        $loan_active_cnt_by_bonus          = $null_array_by_bonus;
        $loan_active_summ                  = 0;
        $loan_active_summ_by_bonus         = $null_array_by_bonus;
        $loan_active_days_remains          = 0;
        $loan_active_days_remains_by_bonus = $null_array_by_bonus;

        $all_loans_active_summ_by_bonus = $null_array_by_bonus;

        $old_arbitration_active_summ_by_bonus = $null_array_by_bonus;

        $garant_issued   = 0;
        $garant_received = 0;

        $garant_issued_in_wait = 0;



        //</editor-fold>
        //тип = 1 - займ, 2 -  вклад
        $this->load->helper('admin');
        $total_grant_loan_new = 0;
        foreach($all_inquiries as $item){



            // передача рейтинга
            if($item->arbitration == 0 && $item->garant == 1 && $item->bonus == 9){
                if($item->state == 1)
                    $garant_issued_in_wait += $item->summa;
                if($item->state == 2){
                    if($item->type == 2 || $item->type == 3){
                        $garant_issued += $item->summa;
                    } elseif($item->type == 1){
                        $garant_received += $item->summa;
                    }
                }
            }

            // активные займы
            if($item->type == 1 && $item->state == 2 && $item->arbitration == 0)
                $all_loans_active_summ_by_bonus[$item->bonus] += $item->summa;
            if($item->type == 1 && $item->state == 2 && $item->arbitration == 0 && $item->garant == 1){
                $loan_active_summ += $item->summa;
                $loan_active_cnt++;
                $loan_active_summ_by_bonus[$item->bonus] += $item->summa;
                $loan_active_cnt_by_bonus[$item->bonus] ++;

                if(strtotime($item->out_time) > time()){
                    $loan_active_days_remains += floor(( strtotime($item->out_time) - time()) / 60 / 60 / 24) + 1;
                    $loan_active_days_remains_by_bonus[$item->bonus] += floor(( strtotime($item->out_time) - time()) / 60 / 60 / 24) + 1;
                }
            }

            if($item->garant == Base_model::CREDIT_GARANT_ON && $item->type == 1 && $item->state == 2 && $item->arbitration == 0)
                $loan_garant_active_summ += $item->summa;
            // активные арбитражи Гарант
            if($item->garant == Base_model::CREDIT_GARANT_ON && $item->type == 1 && $item->state == 2 && $item->arbitration == 1 && $item->blocked_money != '9'){
                $arbitration_garant_active_summ += $item->summa;
                $arbitration_garant_active_summ_by_bonus[$item->bonus] += $item->summa;
            }


            if($item->type == 1 && $item->state == 2 && $item->arbitration == 1 && $item->blocked_money != '9'){
                $old_arbitration_active_summ += $item->summa;
                $old_arbitration_active_summ_by_bonus[$item->bonus] += $item->summa;
            }

            if($item->type == 2 && ($item->state == 2 || $item->state == 1) && $item->sum_arbitration > 0 && $item->arbitration != 3 &&  $item->date > '2016-02-21' && $item->blocked_money != '9'){
                $arbitration_active_summ += $item->sum_arbitration;
                $arbitration_active_summ_by_bonus[$item->bonus] += $item->sum_arbitration;
            }
            if($item->type == 2 && ($item->state == 1) && $item->sum_arbitration > 0 && $item->date > '2016-02-21' && $item->blocked_money != '9'){
                $arbitration_shown_summ += $item->sum_arbitration;
                $arbitration_shown_summ_by_bonus[$item->bonus] += $item->sum_arbitration;
            }
            if($item->type == 2 && ($item->state == 2 || $item->state == 1) && $item->sum_loan > 0 && $item->blocked_money != '9'){
                $sum_loan_active_summ += $item->sum_loan;
                $sum_loan_active_summ_by_bonus[$item->bonus] += $item->sum_loan;
            }


            if($item->state == 1 && $item->overdraft == Base_model::CREDIT_OVERDRAFT_OFF){
                if($item->garant == 1){
                    if($item->type == 1){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_loans_out_summ += $item->out_summ;

                        $all_posted_garant_loans_out_summ_by_bonus[$item->bonus] += $item->out_summ;
                    }
                    else
                    if($item->type == 2){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_invests_summ += $item->summa;

                        $all_advanced_invests_summ_by_bonus[$item->bonus] += $item->summa;
                    }
                }else {
                    if($item->type == Base_model::CREDIT_TYPE_CREDIT){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_standart_loans_out_summ += $item->out_summ;
                    }else
                    if($item->type == Base_model::CREDIT_TYPE_INVEST){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_standart_invests_summ += $item->summa;

                        $all_advanced_standart_invests_summ_by_bonus[$item->bonus] += $item->summa;
                    }
                }
            }


            // карточные активные вклады
            if($item->type == 2 && $item->bonus == 7){
                if($item->state == 2){
                    $active_cards_invests+=$item->summa;
                    $active_cards_invests_outsumm+=$item->out_summ;
                    if($item->garant == 1)
                        $active_cards_garant_invests += $item->summa;
                } elseif($item->state == 1){
                    $showed_cards_invests+=$item->summa;
                    if($item->garant == 1)
                        $showed_cards_garant_invests += $item->summa;
                }
            }
            // карточные активные займы и выставленные
            if($item->type == 1 && $item->bonus == 7){

                if($item->state == 2){
                    $active_cards_credits+=$item->summa;
                    $active_cards_credits_outsumm+=$item->out_summ;
                    if($item->garant == 1)
                        $active_cards_garant_credits += $item->summa;
                } elseif($item->state == 1){
                    $showed_cards_credits+=$item->summa;
                    if($item->garant == 1)
                        $showed_cards_garant_credits += $item->summa;
                }
            }



            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->bonus == 2 && $item->blocked_money == 66)
                $total_active_blocked_own2 += $item->summa;

            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->bonus == 7 && $item->blocked_money == 22)
                $total_active_usd2_fee_invests += $item->summa;




            if($item->state == 1 && $item->type == 2 && $item->bonus == 4){
                $c_creds_amount_invest+=$item->summa;
            }

            if($item->state == 1 && $item->type == 2 && ($item->bonus == 2 || $item->bonus == 5 || $item->bonus == 6)){
                $creds_amount_invest_by_bonus[$item->bonus] +=$item->summa;
            }

            if($item->state == 2 && $item->type == 2 && $item->sum_own > 0){
                $invests_own_sum_by_bonus[$item->bonus] +=$item->summa;
            }

            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->sum_own > 0){
                $invests_showed_and_active_own_sum_by_bonus[$item->bonus] +=$item->sum_own;
            }

            //директы
            if($item->direct == Credits_model::DIRECT_ON && $item->overdraft == Base_model::CREDIT_OVERDRAFT_OFF && $item->bonus == Base_model::CREDIT_BONUS_OFF
            ){
                //выставленные и в ожидании
                if($item->garant == Base_model::CREDIT_GARANT_ON){
//                    if ($item->type == 1)
//                        $all_posted_garant_direct += $item->out_summ;
//                    else

                    if($item->type == Base_model::CREDIT_TYPE_INVEST && $item->state == Base_model::CREDIT_STATUS_SHOWED){
                        $all_posted_garant_direct += $item->summa;
                        $all_posted_garant_direct_by_bonus[$item->bonus] += $item->summa;
                    }

                    if($item->type == Base_model::CREDIT_TYPE_INVEST && $item->state == Base_model::CREDIT_STATUS_WAITING){
                        $all_pending_garant_direct += $item->summa;
                        $all_pending_garant_direct_by_bonus[$item->bonus] += $item->summa;
                    }
                } else
                //выставленные
                if($item->garant == Base_model::CREDIT_GARANT_OFF){
                    if($item->state == Base_model::CREDIT_STATUS_SHOWED){
                        if($item->type == Base_model::CREDIT_TYPE_INVEST){
                            $all_posted_standart_direct += $item->summa;
                            $all_posted_standart_direct_by_bonus[$item->bonus] += $item->summa;
                        }
                    } else
                    if($item->state == Base_model::CREDIT_STATUS_WAITING){
                        if($item->type == Base_model::CREDIT_TYPE_INVEST){
                            $all_pending_standart_direct += $item->summa;
                            $all_pending_standart_direct_by_bonus[$item->bonus] += $item->summa;
                        }
                    }
                }
            }


            if($item->state == Base_model::CREDIT_STATUS_ACTIVE && $item->type == Base_model::CREDIT_TYPE_CREDIT && $item->garant == Base_model::CREDIT_GARANT_ON){
                //просрочен
                if(strtotime($item->out_time.' 23:59:59') < time()){
                    $actual_term = calculate_debit_time_now($item->date);
                    $out_summ    = credit_summ($item->percent, $item->summa, $actual_term);

                    $overdue_garant_interest += $out_summ - $item->out_summ;
                    $overdue_garant_interest_by_bonus[$item->bonus] += $out_summ - $item->out_summ;
                } else {//не просрочен
                    $all_active_garant_loans_out_summ += $item->out_summ;
                    $all_active_garant_loans_out_summ_by_bonus[$item->bonus] += $item->out_summ;
                    $all_active_garant_loans_summa += $item->summa;
                    $all_active_garant_loans_summa_by_bonus[$item->bonus] += $item->summa;
                }

                if(strtotime($item->date) > $new_arbitr_date){
                    $all_active_garant_loans_summa_new += $item->summa;
                    $all_active_garant_loans_summa_new_by_bonus[$item->bonus] += $item->summa;
                    //когда использовался коэф = 10
                    if(strtotime($item->date) < strtotime('2015-12-04 00:00:00') && strtotime($item->date) > strtotime('2015-11-28 00:00:00')){
                        //    $all_active_garant_loans_summa_new += $item->summa;
                        //   $all_active_garant_loans_summa_new_by_bonus[$item->bonus] += $item->summa;
                    }
                }

                $total_grant_loan_new += $item->summa;
            }

            if($item->state == 1 && $item->type == 2 && $item->garant == 1 && $item->bonus == 1)
                $all_advanced_invests_bonuses_summ += $item->summa;

            if($item->state == 2 && $item->type == 1 && $item->garant == 1){
                $total_garant_loans += $item->out_summ;
                $total_garant_loans_by_bonus[$item->bonus] += $item->out_summ;

                if(strtotime($item->out_time).' 23:59:59' < time() && $item->previous_debit == 0){
                    $overdue_garant_count++;
                    $overdue_garant[] = $item->id;
                }
            }

            //овердрафт
            if($item->state == 2 && $item->type == 2 && $item->garant == 1 && $item->overdraft == 1 && $item->direct = Credits_model::DIRECT_OFF){
                $overdraft += $item->summa;
            }
            if($item->state == 2 && $item->type == 2 && $item->garant == 1 && $item->garant_percent == 2){
                $my_investments_garant_partner += $item->summa;
            }
            if($item->state != 2) //только активный
                continue;

            if($item->type == 2){//только вклады
                if($item->garant == 1){
                    if($item->bonus == 1)
                        $my_investments_garant_bonuses += $item->summa;
                    $my_total_future_income_garant += $item->income;
                    $my_future_income_garant += $item->income * (1.0 - $this->garantPercent($item->time) / 100.0);
                }
                else {
                    $my_total_future_income_standart += $item->income;
                    $my_future_income_standart += $item->income * (1.0 - $this->standartPercent($item->time) / 100.0);
                }

                if($item->garant == 0){
                    $my_investments_standart += $item->summa;
                    $my_investments_standart_by_bonus[$item->bonus] += $item->summa;
                }

                if($item->garant == 0)
                    continue;

                $date           = explode(' ', $item->date_kontract);
                $item_timestamp = strtotime($date[0].' 00:00:00');
                $days           = floor(($cur_timestamp - $item_timestamp) / 3600 / 24);

                $my_investments_garant_percent += $item->summa * $days;
                $my_investments_garant_percent_by_bonus[$item->bonus] += $item->summa * $days;

                if(!($item->bonus == 0 || $item->bonus == 2 || $item->bonus == 5 || $item->bonus == 6 || $item->bonus==7))
                    continue; //не учитываем бонусы и партнерские вклады
                $my_investments_garant += $item->summa;
                $my_investments_garant_by_bonus[$item->bonus] += $item->summa;


//            } else if ($item->type == 1 ){//все займы
            } else if($item->type == 1 && $item->arbitration == 0 && $item->bonus != 9){//все займы za isk bonus 9
                $my_loans += $item->summa;
                $my_loans_by_bonus[$item->bonus] += $item->summa;

                $my_loans_percentage += $item->summa * $item->time * $item->percent; //++
                $my_loans_percentage_by_bonus[$item->bonus] += $item->summa * $item->time * $item->percent;

                if($item->garant == 1){


                    $my_loans_percentage_garant += $item->summa * $item->time * $item->percent;
                    $my_loans_percentage_garant_by_bonus[$item->bonus] = $item->summa * $item->time * $item->percent;
                }

                if($item->garant == 0){
                    $loans_standart_out_summ += floatval($item->out_summ);
                    $loans_standart_out_summ_by_bonus[$item->bonus] += floatval($item->out_summ);

                    if(strtotime($item->out_time.' 23:59:59') < time() && Base_model::CREDIT_STATUS_ACTIVE == $item->state){
                        $overdue_standart_count++;
                        $overdue_standart[] = $item->id;
                    }
                }
            } else if($item->type == 1 && $item->arbitration == Base_model::CREDIT_ARBITRATION_ON && $item->blocked_money != '9'){//все займы
//                $total_arbitrage += $item->summa;
                if(strtotime($item->date) <= $new_arbitr_date){
                    $total_arbitrage2 += $item->summa;
                    $total_arbitrage2_by_bonus[$item->bonus] += $item->summa;
                } else {
                    //   $total_arbitrage4_by_bonus[$item->bonus] += $item->summa;
                    if(strtotime($item->date) < strtotime('2015-12-04 00:00:00') && strtotime($item->date) > strtotime('2015-11-28 00:00:00')){
                        //     $total_arbitrage3 -= $item->summa /2; // ubral /2
                        //    $total_arbitrage3_by_bonus[$item->bonus] -= $item->summa /2;                 // ubral /2
                        //$total_arbitrage4_by_bonus[$item->bonus] -= $item->summa /2;                 // ubral /2
                        $total_arbitrage4 += $item->summa;
                        $total_arbitrage4_by_bonus[$item->bonus] += $item->summa;
                    } elseif(strtotime($item->date) > strtotime('2015-12-14 00:00:00')){
                        $total_arbitrage5 += $item->summa;
                        $total_arbitrage5_by_bonus[$item->bonus] += $item->summa;
                    } else {
                        $total_arbitrage3 += $item->summa;
                        $total_arbitrage3_by_bonus[$item->bonus] += $item->summa;
                    }
                }
                $total_arbitrage_percentage += $item->summa * $item->time * $item->percent;
                $total_arbitrage_percentage_by_bonus[$item->bonus] += $item->summa * $item->time * $item->percent;
            }
        }


        $total_arbitrage = $total_arbitrage2 + $total_arbitrage3 + $total_arbitrage4 + $total_arbitrage5; //++

        $my_investments_garant_percent *= $current_garant_percent / 100; //++

        $my_loans_percentage /= 100; //++
        $my_loans_percentage_garant /= 100;
        $total_arbitrage_percentage /= 100; //++
        $my_loans_percentage += $total_arbitrage_percentage; //++
        //$my_investments_garant -= $garant_issued;
        //$my_investments_garant += $garant_received;
        //разделение по счетам
        foreach($null_array_keys as $b){
            //if ( $b == 6){
            //$my_investments_garant_by_bonus[$b] -= $garant_issued;
            //$my_investments_garant_by_bonus[$b] += $garant_received;
            //}


            $total_arbitrage_by_bonus[$b] = //++
                $total_arbitrage2_by_bonus[$b] + //++
                $total_arbitrage3_by_bonus[$b] +
                $total_arbitrage4_by_bonus[$b] +
                $total_arbitrage5_by_bonus[$b]; //++
            /* echo "[$b]{$date_start}{$total_arbitrage_by_bonus[$b]} = //++
              {$total_arbitrage2_by_bonus[$b]} 2+ //++
              {$total_arbitrage3_by_bonus[$b]} 3+
              {$total_arbitrage4_by_bonus[$b]} 4+
              {$total_arbitrage5_by_bonus[$b]} 5;//++<br>"; */

            $my_investments_garant_percent_by_bonus[$b] *= $current_garant_percent / 100; //++

            $my_loans_percentage_by_bonus[$b] /= 100;
            $my_loans_percentage_garant_by_bonus[$b] /= 100;
            $total_arbitrage_percentage_by_bonus[$b] /= 100;

            $my_loans_percentage_by_bonus[$b] += $total_arbitrage_percentage_by_bonus[$b];
        }

        $this->load->model('transactions_model', 'transactions');
        if(null !== $date_end)
            $this->db->where("DATE(date) <= DATE('$date_end')");
        if(null !== $bonus)
            $this->db->where("bonus", $bonus);
        $transactions              = $this->db->where(array('id_user' => $_id))
            ->get('transactions')
            ->result();
        //<editor-fold defaultstate="collapsed" desc="Объявление переменных">
        $my_income_money           = 0;
        $my_income_money_by_bonus  = $null_array_by_bonus;
        $my_outcome_money          = 0;
        $my_outcome_money_by_bonus = $null_array_by_bonus;

        $my_income_money_acrhive  = 0;
        $my_outcome_money_acrhive = 0;

        $my_income_bonus_money              = $null_array_by_bonus;
        $my_outcome_bonus_money             = $null_array_by_bonus;
        $money_bonus_sum_process_withdrawal = $null_array_by_bonus;

        $item_summa       = 0;
        $item_status      = 0;
        $item_note        = '';
        $item_method      = '';
        $my_bonuses       = 0;
        $my_partner_funds = 0;

        //my add and sub money

        $money_sum_add_funds          = 0;     //зачисление на счет
        $money_sum_add_funds_by_bonus = $null_array_by_bonus;     //зачисление на счет

        $money_sum_add_card          = 0;     //зачисление с карты
        $money_sum_add_card_by_bonus = $null_array_by_bonus;     //зачисление с карты

        $money_sum_withdrawal                   = 0;     //сумма вывода
        $money_sum_withdrawal_by_bonus          = $null_array_by_bonus;     //сумма вывода
        $money_sum_transfer_to_users            = 0;     //сумма перевода другим
        $money_sum_transfer_to_users_by_bonus   = $null_array_by_bonus;     //сумма перевода другим
        $money_sum_transfer_from_users          = 0;   //от других
        $money_sum_transfer_from_users_by_bonus = $null_array_by_bonus;   //от других
        $money_sum_transfer_from_exch           = 0; //сумма перевода от обменников
        $money_sum_transfer_from_exch_exwt      = 0;
        $money_sum_transfer_to_exch             = 0; //сумма перевода на обменников
        $sum_partner_reword                     = 0;         //партнерские
        $sum_partner_reword_by_bonus            = $null_array_by_bonus;
        $money_sum_process_withdrawal           = 0;
        $money_sum_process_withdrawal_by_bonus  = $null_array_by_bonus;

        $money_sum_transfer_to_merchant          = 0;
        $money_sum_transfer_to_merchant_by_bonus = $null_array_by_bonus;
        $income_merchant_send                    = 0;
        $income_merchant_send_by_bonus           = $null_array_by_bonus;
        $income_merchant_return                  = 0;
        $outcome_merchant_send                   = 0;
        $outcome_merchant_send_by_bonus          = $null_array_by_bonus;
        $outcome_merchant_return                 = 0;
        $outcome_merchant_return_by_bonus        = $null_array_by_bonus;

        $income_internal_sends  = 0;
        $outcome_internal_sends = 0;

        $bonus_earned_in  = 0;
        $bonus_earned_out = 0;
        $bonus_earned     = 0;

        $bonus_earned_in_by_bonus                  = $null_array_by_bonus;
        $bonus_earned_out_by_bonus                 = $null_array_by_bonus;
        $bonus_earned_by_bonus                     = $null_array_by_bonus;
        $income_merchant_send_by_bonus             = $null_array_by_bonus;
        $processing_internal_transfer_sum_by_bonus = $null_array_by_bonus;
        $max_garant_vklad_real_available_by_bonus  = $null_array_by_bonus;

        $partner_unic_id_count = 0;         //Количество партнерских с разных ID
        $diversification_coeff = 15;        //Коэфицент диверсификации

        $partner_week_contribution  = 0;     //Сумма партнерских за неделю
        $partner_contribution_count = 0;    //Количество партнерских отчислений

        $average_partner_contribution = 0;  //Средняя сумма партнерского отчисления

        $diversification_degree          = 0; //Степень Диверсификации
        $partner_network_valuation_coeff = 0; //Коэфицент оценки партнерской сети
//        $new_max_loan_available = 0;    //Новый кредитный лимит
        $fee_or_fine                     = 0; //всякие отчисления за смс или что-то еще штрафы и т.п.

        $unic_partners_ids                    = array();
        $total_processing_payout              = 0; //сумма заявкок на вывод
        $total_processing_payout_sum_by_bonus = $null_array_by_bonus; //сумма заявкок на вывод
        $total_processing_payout_fee_by_bonus = $null_array_by_bonus; //сумма комиссий заявкок на вывод

        $p2p_money_sum_transfer_to_users            = 0;
        $p2p_money_sum_transfer_to_users_by_bonus   = $null_array_by_bonus;
        $p2p_money_sum_transfer_from_users          = 0;
        $p2p_money_sum_transfer_from_users_by_bonus = $null_array_by_bonus;


        $arbitr_in             = 0;
        $arbitr_out            = 0;
        $arbitr_inout          = 0;
        $arbitr_in_by_bonus    = $null_array_by_bonus;
        $arbitr_out_by_bonus   = $null_array_by_bonus;
        $arbitr_inout_by_bonus = $null_array_by_bonus;

        $transfered_summ_from_bonus_2_to_6 = 0;
        $transfered_summ_from_bonus_3_to_6 = 0;
        $money_own_from_2_to_6             = 0;

        $pcreds_inout_after_0112             = 0;
        $pcreds_income_after_0112            = 0;
        $pcreds_outcome_after_0112           = 0;
        $pcreds_in_payout_process_after_0112 = 0;







        $last_week_date = strtotime(date('Y-m-d 00:00:00')) - 7 * 24 * 3600;

        $last_month_date                   = strtotime(date('Y-m-d 00:00:00')) - 30 * 24 * 3600;
        $month_partner_unic_id_count       = 0;   //Количество партнерских с разных ID
        $month_unic_partners_ids           = array();
        $money_sum_process_withdrawal_bank = 0;

        $exchange_users = getExchangeUsers(); //обменники

        $c_creds_amount         = 0; //размер средств на счете C-CREDS
        $c_creds_amount_process = 0; //размер средств на счете C-CREDS на проверке у операторов

        $my_partner_funds_process          = 0; //партнерские на проверке у операторов
        $max_garant_vklad_real_available   = 0; // сколько можно выдать вклад гарант на реальные деньги
        $money_sum_withdrawal_minus_type65 = 0;

        $transfered_summ_from_bonus_5_to_2 = 0;


        $money_in_after_26                    = 0;
        $money_in_after_26_by_bonus           = $null_array_by_bonus;
        $money_out_after_26_only_out          = 0;
        $money_out_after_26_only_out_by_bonus = $null_array_by_bonus;
        $money_inout_after_26                 = 0;
        $money_inout_after_26_by_bonus        = $null_array_by_bonus;


        $money_own_stab_fond_blocked_by_bonus     = $null_array_by_bonus;
		$money_income_stab_fond_blocked_by_bonus     = $null_array_by_bonus;

        $active_status = array(Base_model::TRANSACTION_STATUS_IN_PROCESS,
            Base_model::TRANSACTION_STATUS_VEVERIFYED,
            Base_model::TRANSACTION_STATUS_VEVERIFY_SS,
            Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK);

        $transactions_pending_statuses = array(Base_model::TRANSACTION_STATUS_IN_PROCESS,
            Base_model::TRANSACTION_STATUS_PENDING,
            Base_model::TRANSACTION_STATUS_VEVERIFYED,
            Base_model::TRANSACTION_STATUS_VEVERIFY_SS,
            Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK);

        $partner_type     = array(
            Transactions_model::TYPE_PARENT_INCOME,
            Transactions_model::TYPE_VOLUNTEER_INCOME
        );
        $send_money_type  = array(
            Transactions_model::TYPE_SEND_MONEY,
            Transactions_model::TYPE_SEND_MONEY_CONFERM,
            Transactions_model::TRANSACTION_TYPE_USER_SEND,
            Transactions_model::TYPE_SEND_MONEY_P2P,
        );
        $fee_or_fine_type = array(
            Transactions_model::TYPE_EXPENSE_SMS,
            Transactions_model::TYPE_EXPENSE_FINE,
            Transactions_model::TYPE_EXPENSE_OUTFEE,
            Transactions_model::TYPE_EXPENSE_OVERDRAFT,
            Transactions_model::TYPE_EXPENSE_MERCHANT,
            Transactions_model::TYPE_EXPENSE_INFEE,
            Transactions_model::TYPE_EXPENSE_P2P_FEE
        );

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Расчет по транзакциям">
        foreach($transactions as $item){

            $item_summa  = $item->summa;
            $item_status = $item->status;
            $item_note   = $item->note;
            $item_method = $item->metod;

            if($item->type == Transactions_model::TYPE_ARCHIVE && $item->value == Transactions_model::TYPE_ARCHIVE)
                if($item->status == Base_model::TRANSACTION_STATUS_RECEIVED)
                    $my_income_money_acrhive += $item_summa;
                elseif($item->status == Base_model::TRANSACTION_STATUS_REMOVED)
                    $my_outcome_money_acrhive += $item_summa;

            if(Base_model::TRANSACTION_STATUS_IN_PROCESS == $item->status ||
                Base_model::TRANSACTION_STATUS_VEVERIFYED == $item->status || Base_model::TRANSACTION_STATUS_VEVERIFY_SS == $item->status ||
                Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK == $item->status){
                $total_processing_payout += $item->summa;
            }

            if($item->metod == 'out' && in_array($item->status, [4, 9, 10, 11])){
                $total_processing_payout_sum_by_bonus[$item->bonus] += $item->summa;
                $total_processing_payout_fee_by_bonus[$item->bonus] += $this->get_payout_fee_by_item($item); //++

                if($item->bonus == 3 && strtotime($item->date) > strtotime('2015-12-01 00:00:00'))
                    $pcreds_in_payout_process_after_0112 += $item->summa;
            }

            if($item->metod == 'wt' && in_array($item->type, [74, 75]) && in_array($item->status, [4, 9, 10])){
                $processing_internal_transfer_sum_by_bonus[$item->bonus] += $item->summa; //++
            }
            if($item->metod == 'out' && in_array($item->type, [85]) &&  $item->status==11){
                $money_own_stab_fond_blocked_by_bonus[$item->bonus] += $item->summa; //++
            }
			if($item->metod == 'out' && in_array($item->type, [86]) &&  $item->status==11){
                $money_income_stab_fond_blocked_by_bonus[$item->bonus] += $item->summa; //++
            }



            if($item_status == 1){//пополнение
                $my_income_money_by_bonus[$item->bonus] += $item_summa;
                if($item->type == 40){
                    $income_merchant_send += $item_summa;
                    $income_merchant_send_by_bonus[$item->bonus] += $item_summa;
                }
                if($item->type == 18){
                    $arbitr_in += $item_summa;
                    $arbitr_in_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->bonus == 3 && in_array($item->type, [30, 31]) && strtotime($item->date) > strtotime('2015-12-01 00:00:00')){
                    $pcreds_income_after_0112 +=$item_summa;
                }

                if($item->bonus == 6 && ($item->type == 83 || $item->type == 84)){
                    $transfered_summ_from_bonus_2_to_6 += $item_summa;
                    if($item->type == 83)
                        $money_own_from_2_to_6 += $item_summa;
                }

                if($item->type == 41)
                    $income_merchant_return += $item_summa;

                if($item->type == Transactions_model::TYPE_SEND_MONEY_P2P){
                    $p2p_money_sum_transfer_from_users += $item_summa;
                    $p2p_money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                }

                if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)){
                    $money_sum_transfer_from_users += $item_summa;
                    $money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 16 && $item->note_admin == 'bonus'){
                    $bonus_earned_in += $item->summa;
                    $bonus_earned_in_by_bonus[$item->bonus] += $item->summa;
                }
            } else if($item_status == 3){//снятие
                $my_outcome_money_by_bonus[$item->bonus] += $item_summa;
                if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)){
                    $money_sum_transfer_to_users += $item_summa;
                    $money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == Transactions_model::TYPE_SEND_MONEY_P2P){
                    $p2p_money_sum_transfer_to_users += $item_summa;
                    $p2p_money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->bonus == 3 && !in_array($item->type, [25, 26]) && strtotime($item->date) > strtotime('2015-12-01 00:00:00')){
                    $pcreds_outcome_after_0112 +=$item_summa;
                }

                if($item->bonus == 3 && $item->type == 81)
                    $transfered_summ_from_bonus_3_to_6 += $item_summa;


                if($item_method == 'out'){
                    $money_sum_withdrawal += $item_summa;
                    $money_sum_withdrawal_by_bonus[$item->bonus] += $item_summa;
                    if($item->date > strtotime('2015-11-26 00:00:00')){
                        $money_out_after_26_only_out += $item_summa;
                        $money_out_after_26_only_out_by_bonus[$item->bonus] += $item_summa;
                    }
                }

                if($item->type == 19){
                    $arbitr_out += $item_summa;
                    $arbitr_out_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 25 && $item->note_admin == 'bonus'){
                    $bonus_earned_out += $item->summa;
                    $bonus_earned_out_by_bonus[$item->bonus] += $item->summa;
                }


                if($item->type == 40){
                    $money_sum_transfer_to_merchant += $item_summa;
                    $money_sum_transfer_to_merchant_by_bonus[$item->bonus] += $item_summa;
                    $outcome_merchant_send += $item_summa;
                    $outcome_merchant_send_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 41){
                    $outcome_merchant_return += $item_summa;
                    $outcome_merchant_return_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 98 || $item->type == 99)
                    $outcome_internal_sends += $item_summa;
            }

            if($item->bonus == 2 && $item->type == 81)
                $transfered_summ_from_bonus_5_to_2 += $item_summa;


            if($item->bonus == Base_model::TRANSACTION_BONUS_PARTNER){
                if($item_status == 1)//пополнение
                    $my_partner_funds += $item_summa;
                else if($item_status == 3)//снятие
                    $my_partner_funds -= $item_summa;
                else if(in_array($item_status, $active_status))
                    $my_partner_funds_process += $item_summa;
            } else if($item->bonus == 1){
                if($item_status == 1)//пополнение
                    $my_bonuses += $item_summa;
                else if($item_status == 3)//снятие
                    $my_bonuses -= $item_summa;
            } else if($item->bonus == Base_model::TRANSACTION_BONUS_CREDS_CASH){
                if($item_status == 1)//пополнение
                    $c_creds_amount += $item_summa;
                else if($item_status == 3)//снятие
                    $c_creds_amount -= $item_summa;
                else if(in_array($item_status, $active_status))
                    $c_creds_amount_process += $item_summa;
            }else {
                if($item_status == 1){//пополнение
                    $my_income_money += $item_summa;
                    $my_income_bonus_money[$item->bonus] += $item_summa;


                    /*
                      if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)) {
                      $money_sum_transfer_from_users += $item_summa;
                      $money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                      } */




                    $matches = array();
                    if(( in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type && Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value ) ) &&
                        ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], $exchange_users) )
                    ){
                        $money_sum_transfer_from_exch += $item_summa;
                    }


                    if($item->type == 74 && $item->bonus == 5 && ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], [46504049]))){
                        $money_sum_transfer_from_exch_exwt += $item_summa;
                    }

                    if(in_array($item->type, $partner_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_PARENT_INCOME == $item->value)){
                        $sum_partner_reword += $item_summa;
                        $sum_partner_reword_by_bonus[$item->bonus] += $item_summa;
                    }
                    if($item_method != 'wt' && $item_method != 'arbitr'){
                        $money_sum_add_funds += $item_summa;
                        $money_sum_add_funds_by_bonus[$item->bonus] += $item_summa;
                        if($item_method == 'wtcard'){
                            $money_sum_add_card += $item_summa;
                            $money_sum_add_card_by_bonus[$item->bonus] += $item_summa;
                        }
                        if(strtotime($item->date) > strtotime('2015-11-26 00:00:00')){
                            $money_in_after_26 += $item_summa;
                            $money_in_after_26_by_bonus[$item->bonus] += $item_summa;
                        }
                    }

                    if($last_week_date <= strtotime($item->date) && preg_match('/Партнерское вознаграждение по вкладу/', $item->note)){
                        $id_user                     = $this->users->getPartnerIdFromNote($item->note);
                        //Количество партнерских с разных ID
                        if(!empty($id_user))
                            if(!isset($unic_partners_ids[$id_user]))
                                $unic_partners_ids[$id_user] = 1;
                            else
                                $unic_partners_ids[$id_user] ++;

                        //Сумма партнерских за неделю
                        $partner_week_contribution += $item_summa;

                        //Количество партнерских отчислений
                        $partner_contribution_count++;
                    }

                    /* if ( $item->type == 40){
                      $income_merchant_send += $item_summa;
                      $income_merchant_send_by_bonus[$item->bonus] += $item_summa;
                      }
                      if ( $item->type == 41)
                      $income_merchant_return += $item_summa; */

                    if($item->type == 98 || $item->type == 99)
                        $income_internal_sends += $item_summa;;


                    if($last_month_date <= strtotime($item->date) && preg_match('/Партнерское вознаграждение по вкладу/', $item->note)){
                        $id_user                           = $this->users->getPartnerIdFromNote($item->note);
                        //Количество партнерских с разных ID
                        if(!empty($id_user))
                            if(!isset($month_unic_partners_ids[$id_user]))
                                $month_unic_partners_ids[$id_user] = 1;
                            else
                                $month_unic_partners_ids[$id_user] ++;
                    }
                }
                else if($item_status == 3){//снятие
                    $my_outcome_money += $item_summa;
                    $my_outcome_bonus_money[$item->bonus] += $item_summa;


                    /*
                      if ( $item->type == Transactions_model::TYPE_SEND_MONEY_P2P ){
                      $p2p_money_sum_transfer_to_users += $item_summa;
                      $p2p_money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                      }
                     */

                    /* if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value))
                      {
                      $money_sum_transfer_to_users += $item_summa;
                      $money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                      } */

                    if(in_array($item->type, $fee_or_fine_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_FEE_OR_FINE == $item->value))
                        $fee_or_fine += $item_summa;

                    if($item_method == 'out' && $item->type != 65)
                        $money_sum_withdrawal_minus_type65 += $item_summa;


                    $matches = array();
                    if(( in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type &&
                        Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value ) ) &&
                        ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], $exchange_users) )
                    ){
                        $money_sum_transfer_to_exch += $item_summa;
                    }

                    /*
                      if($item->type == 40){
                      $money_sum_transfer_to_merchant += $item_summa;
                      $money_sum_transfer_to_merchant_by_bonus[ $item->bonus ] += $item_summa;
                      $outcome_merchant_send += $item_summa;
                      $outcome_merchant_send_by_bonus[$item->bonus] += $item_summa;
                      }

                      if ( $item->type == 41)
                      $outcome_merchant_return += $item_summa;

                      if ($item->type == 98 || $item->type == 99)
                      $outcome_internal_sends += $item_summa;
                     */
                } else if(in_array($item_status, $active_status)){
                    $money_sum_process_withdrawal += $item_summa;
                    $money_sum_process_withdrawal_by_bonus[$item->bonus] += $item_summa;
                    $money_bonus_sum_process_withdrawal[$item->bonus] += $item_summa;
                    //сумма на вывод по банку
                    if(FALSE !== strpos($item_method, 'bank'))
                        $money_sum_process_withdrawal_bank += $item_summa;
                    //if ( $item_status == 11) $money_sum_process_withdrawal_bank += $item_summa;
                }
            }
        }

        //</editor-fold>


        $c_creds_total      = $c_creds_amount - $c_creds_amount_process - $c_creds_amount_invest;
        $c_creds_total_info = "c_creds_amount - c_creds_amount_process - c_creds_amount_invest = $c_creds_amount - $c_creds_amount_process - $c_creds_amount_invest = $c_creds_total";


        $pcreds_inout_after_0112 = $pcreds_income_after_0112 - $pcreds_outcome_after_0112;


        $my_partner_funds      = $my_partner_total      = $my_partner_funds - $my_partner_funds_process;
        $my_partner_funds_info = $my_partner_total_info = "my_partner_funds - my_partner_funds_process = $my_partner_funds - $my_partner_funds_process = $my_partner_funds";

        $partner_unic_id_count       = count($unic_partners_ids);
        $month_partner_unic_id_count = count($month_unic_partners_ids);

        if($partner_contribution_count > $diversification_coeff){
            $partner_temp = $partner_contribution_count;
        } else {
            $partner_temp = $diversification_coeff;
        }
        //Средняя сумма партнерского отчисления
        if($partner_contribution_count != 0)
            $average_partner_contribution = $partner_week_contribution / $partner_temp;

        //Степень Диверсификации
        if($diversification_coeff != 0)
            $diversification_degree = $partner_unic_id_count / $diversification_coeff;


        #Коэфицент оценки партнерской сети
        $partner_network_valuation_coeff = $average_partner_contribution * $diversification_degree;

        #Коэф соц интеграции
        $social_integration_value = 0;

        $ln      = strlen($_id);
        $_id_str = (string) $_id;
        $sp_l    = intval(substr($_id_str, $ln - 2, 1));
        $sp_f    = intval(substr($_id_str, 0, 1));

        //дробная часть
        $fraction_diversification_coeff = $diversification_degree - floor($diversification_degree);
        $integer_diversification_coeff  = 0.72 + 0.01 * $sp_l;

        //Если дробная_часть( Степень Диверсификации ) > 0.50
        if($fraction_diversification_coeff >= 0.5){
            $integer_diversification_coeff = 0.15 + 0.01 * $sp_f;
        }
        //        (0 + дробная_часть( Степень Диверсификации ) ) * Коэфицент оценки  партнерской сети * 100
        $social_integration_value = ( $integer_diversification_coeff + $fraction_diversification_coeff ) * $partner_network_valuation_coeff * 100;

        //качество партнерской сети
        $partner_network_value = $partner_network_valuation_coeff * 100;

        //old
        $all_liabilities = $my_loans + $my_loans_percentage + $total_arbitrage + $overdue_garant_interest; //++

        $all_liabilities_info = "my_loans + my_loans_percentage  + total_arbitrage + overdue_garant_interest =  $my_loans + $my_loans_percentage  + $total_arbitrage + $overdue_garant_interest = $all_liabilities_info";

//        $arbitrage_collateral = $total_arbitrage / 3;
        $arbitrage_collateral2 = $total_arbitrage2 / 3; //++
        $arbitrage_collateral3 = $total_arbitrage3 / 5; //++
        $arbitrage_collateral  = $total_arbitrage2 / 3 + $total_arbitrage3 / 5; //++


        $payment_account = $my_income_money - $my_outcome_money; //++

        $payment_account_info = "my_income_money - my_outcome_money = $my_income_money - $my_outcome_money = $payment_account";


        // разбиваем счет по бунусам 2 и 5
        $payment_bonus_account[2] = $my_income_bonus_money[2] - $my_outcome_bonus_money[2];
        $payment_bonus_account[5] = $my_income_bonus_money[5] - $my_outcome_bonus_money[5];
        $payment_bonus_account[6] = $my_income_bonus_money[6] - $my_outcome_bonus_money[6];


        $money_inout_after_26 = $money_in_after_26 - $money_out_after_26_only_out;
        if($money_inout_after_26 > $loan_active_summ)
            $money_inout_after_26 = $loan_active_summ;




        $payment_account_by_bonus       = $null_array_by_bonus;
        $arbitrage_collateral2_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral3_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral4_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral5_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral_by_bonus  = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $arbitrage_collateral2_by_bonus[$b] = $total_arbitrage2_by_bonus[$b] / 3; //++
            $arbitrage_collateral3_by_bonus[$b] = $total_arbitrage3_by_bonus[$b] / 5; //++
            $arbitrage_collateral4_by_bonus[$b] = $total_arbitrage4_by_bonus[$b] / 10; //++
            $arbitrage_collateral5_by_bonus[$b] = $total_arbitrage5_by_bonus[$b] / 3; //++



            $money_inout_after_26_by_bonus[$b] = $money_in_after_26_by_bonus[$b] - $money_out_after_26_only_out_by_bonus[$b];

            if($b != 6 && $money_inout_after_26_by_bonus[$b] > $loan_active_summ_by_bonus[$b])
                $money_inout_after_26_by_bonus[$b] = $loan_active_summ_by_bonus[$b];

            $bonus_earned_by_bonus[$b] = $bonus_earned_in_by_bonus[$b] - $bonus_earned_out_by_bonus[$b];

            $arbitrage_collateral_by_bonus[$b] = $total_arbitrage2_by_bonus[$b] / 3 + $total_arbitrage3_by_bonus[$b] / 5 + $total_arbitrage4_by_bonus[$b] / 10 + $total_arbitrage5_by_bonus[$b] / 3; //++

            $all_liabilities_by_bonus[$b] = $my_loans_by_bonus[$b] + //++
                $my_loans_percentage_by_bonus[$b] + //++
                $total_arbitrage_by_bonus[$b] + //++
                $arbitration_active_summ_by_bonus[$b] -
                $arbitration_shown_summ_by_bonus[$b] +
                $overdue_garant_interest_by_bonus[$b]; //++


            $payment_account_by_bonus[$b] = //++
                $my_income_money_by_bonus[$b] //++
                - $my_outcome_money_by_bonus[$b]; //++


            /* echo "payment_account_by_bonus[$b] = //++
              my_income_money_by_bonus[$b] //++
              - my_outcome_money_by_bonus[$b];//++
              {$payment_account_by_bonus[$b]} = //++
              {$my_income_money_by_bonus[$b]} //++
              - {$my_outcome_money_by_bonus[$b]};
              ";
             */


            $all_investments_by_bonus[$b] = $my_investments_garant_percent_by_bonus[$b] + //++
                $my_investments_garant_by_bonus[$b] + //++
                $payment_account_by_bonus[$b]; //++
//                              +  $partner_network_value_by_bonus[$b]
//                                + $social_integration_value_by_bonus[$b]; //активы
        }


        if($money_inout_after_26_by_bonus[6] > $loan_active_summ_by_bonus[6] + $loan_active_summ_by_bonus[7])
            $money_inout_after_26_by_bonus[6] = $loan_active_summ_by_bonus[6] + $loan_active_summ_by_bonus[7];


        //<editor-fold defaultstate="collapsed" desc="calc old $all_investments & $all_investments_info">
        $all_investments = $my_investments_garant_percent + $my_investments_garant + $payment_account + $partner_network_value + $social_integration_value; //активы

        $all_investments_info = "my_investments_garant_percent + my_investments_garant + payment_account + partner_network_value
                            + social_integration_value(активы) = $my_investments_garant_percent + $my_investments_garant + $payment_account + $partner_network_value
                            + $social_integration_value = $all_investments";
        //</editor-fold>


        $future_interest_payout      = $my_total_future_income_standart - $my_future_income_garant + $my_total_future_income_garant - $my_future_income_standart;
        $future_interest_payout_info = "my_total_future_income_standart - my_future_income_garant + my_total_future_income_garant - my_future_income_standart = $my_total_future_income_standart - $my_future_income_garant + $my_total_future_income_garant - $my_future_income_standart = $future_interest_payout";

        $total_future_income = $my_total_future_income_garant + $my_total_future_income_standart;

        //Емкость финансового рейтинга
        $FSRC = 0;
        if($max_loan != 0)
            $FSRC = ($all_investments - $all_liabilities - $total_garant_loans - $arbitrage_collateral) / $max_loan;

        $FSRC_temp = ($FSRC > 1 ? 1 : $FSRC);


        $FSRC_by_bonus = $null_array_by_bonus;
        if($max_loan > 0)
            foreach($null_array_keys as $b){


                $FSRC_by_bonus[$b] = ($all_investments_by_bonus[$b] - //++
                    $all_liabilities_by_bonus[$b] - //++
                    $total_garant_loans_by_bonus[$b] //++
                    /* - $arbitrage_collateral[2] */
                    ) / $max_loan; //++
                //        Т.к. арбитраж не дается под активы, вычитать залог не следует
            }


        $arbitr_inout = $arbitr_in - $arbitr_out;

        //рейтинг
        $ratio = 0;
        if($all_investments != 0)
            $ratio = ($all_investments - $all_liabilities) / $all_investments;

        $FSR_temp = round($max_rating * $FSRC_temp, 0);
        $FSR      = 5 + ($FSR_temp < 0 ? 0 : $FSR_temp); //округлять в большую сторону
        if($this->isUserAccountVerified($_id))
            $FSR += 4;

        //Максимально доступная сумма займа с процентами по опции Гарант
        //10/10/2015
        $max_loan_available_by_bonus      = $null_array_by_bonus;
        $availiable_garant_by_bonus       = $null_array_by_bonus;
        $total_processing_by_bonus        = $null_array_by_bonus;
        $max_loan_available_by_bonus_info = '';
        foreach($null_array_keys as $b){
            $arbitr_inout_by_bonus[$b] = $arbitr_in_by_bonus[$b] - $arbitr_out_by_bonus[$b];

            $total_processing_by_bonus[$b] = $total_processing_payout_sum_by_bonus[$b]//++=  $total_processing_payout
                + $total_processing_payout_fee_by_bonus[$b]  //++комиссии - см блокнот
                + $currency_exchange_scores['total_processing_p2p_by_bonus'][$b] + $processing_internal_transfer_sum_by_bonus[$b];

            //взять гарант
            $max_loan_available_by_bonus[$b] = round(($all_investments_by_bonus[$b] - $all_liabilities_by_bonus[$b]//++
                - $all_posted_garant_direct_by_bonus[$b] //++
                - $all_pending_garant_direct_by_bonus[$b] //++
                - $all_posted_standart_direct_by_bonus[$b] //++
                - $all_pending_standart_direct_by_bonus[$b] //++
                + $total_arbitrage_percentage_by_bonus[$b]//++
                - $total_processing_by_bonus[$b]//??? как только инициировал р2р-операцию его скор падает
                - $active_cards_credits_outsumm // добавим карточные вклады как обеспечение
                + $active_cards_invests



//                                        - $total_processing_payout_sum_by_bonus[$b]//++=  $total_processing_payout
//                                        - $total_processing_payout_fee_by_bonus[$b]  //++комиссии - см блокнот
//                                        - $currency_exchange_scores['total_processing_fee_by_bonus'][$b]//++
//                                        - $currency_exchange_scores['total_processing_p2p_by_bonus'][$b]//++
//                                    - Транзакции по переводе денежных средств бонус 2 в незавершенных
//                                    статусах (в ожидании, проверка СБ, в процессе, выставленные, но не прошедшие)
                ), 2);
            if($b == 6){
                $max_loan_available_by_bonus[$b] -= $garant_issued * 1;
                $max_loan_available_by_bonus[$b] += $garant_received * 1;
            }
            if($b == 2)
                $max_loan_available_by_bonus[$b] -= $total_active_blocked_own2;
//            $availiable_garant_by_bonus[$b] = $payment_account_by_bonus[$b]
//                    - $all_posted_garant_loans_out_summ_by_bonus[$b]
//                    - $total_processing_by_bonus[$b];
//          //дать гарант
            $availiable_garant_by_bonus[$b] = $payment_account_by_bonus[$b] - $total_processing_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] - $all_advanced_standart_invests_summ_by_bonus[$b];

            //if(0 &&  $this->get_user_id() == 90832388 && $b == 6 )
            {
                $max_loan_available_by_bonus_info .= "<b>Bonus=$b</b><br>";
                $max_loan_available_by_bonus_info .= "max_loan_available_by_bonus = <b>$max_loan_available_by_bonus[$b]</b> = <br> all_investments_by_bonus($all_investments_by_bonus[$b]) <br>
                                        - all_liabilities_by_bonus($all_liabilities_by_bonus[$b])//++<br>
                                        - all_posted_garant_direct_by_bonus($all_posted_garant_direct_by_bonus[$b]) //++<br>
                                        - all_pending_garant_direct_by_bonus($all_pending_garant_direct_by_bonus[$b]) //++<br>
                                        - all_posted_standart_direct_by_bonus($all_posted_standart_direct_by_bonus[$b]) //++<br>
                                        - all_pending_standart_direct_by_bonus($all_pending_standart_direct_by_bonus[$b]) //++<br>
                                        + total_arbitrage_percentage_by_bonus($total_arbitrage_percentage_by_bonus[$b])//++<br>
                                        - total_processing_by_bonus($total_processing_by_bonus[$b])//??? как только инициировал р2р-операцию его скор падает<br>
                                        - active_cards_credits($active_cards_credits)<br>
                                        + active_cards_invests($active_cards_invests)";

                if($b == 2)
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= total_active_blocked_own2($total_active_blocked_own2) = {$max_loan_available_by_bonus[$b]}";

                if($b == 6){
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= garant_issue = {$garant_issued}";
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= garant_receive = {$garant_received}";
                }


                $max_loan_available_by_bonus_info .= "<br>[$b]availiable_garant_by_bonus = <b>$availiable_garant_by_bonus[$b]</b> = <br> payment_account_by_bonus($payment_account_by_bonus[$b]) <br>
                                    - total_processing_by_bonus($total_processing_by_bonus[$b]) <br>
                                    - all_advanced_invests_summ_by_bonus($all_advanced_invests_summ_by_bonus[$b]) <br>
                                    - all_advanced_standart_invests_summ_by_bonus($all_advanced_standart_invests_summ_by_bonus[$b])<br><hr>";
                //vred( $max_loan_available_by_bonus, $availiable_garant_by_bonus );
            }
        }

        //availiable_garant_by_bonus = payment_account
        //- all_advanced_loans_outsumm
        //- all_processing_payout_summ
        //- all_processing_payout_fee
        //- $currency_exchange_scores['total_processing_fee']
        //- $currency_exchange_scores['total_processing_p2p']
        //-
        //
//        old
        //<editor-fold defaultstate="collapsed" desc="calc old $max_loan_available">
        $max_loan_available = round(($all_investments - $all_liabilities - $overdraft - $all_posted_garant_direct - $all_pending_garant_direct - $all_posted_standart_direct - $all_pending_standart_direct - $total_processing_payout + $total_arbitrage_percentage - $my_investments_garant_partner - $currency_exchange_scores['limit2'] - $currency_exchange_scores['limit1'] - $currency_exchange_scores['limit3'] - $currency_exchange_scores['total_processing_fee']
            ), 2);

        $max_loan_available_info = "all_investments
                                    - all_liabilities
                                    - overdraft
                                    - all_posted_garant_direct
                                    - all_pending_garant_direct
                                    - all_posted_standart_direct
                                    - all_pending_standart_direct
                                    - total_processing_payout
                                    + total_arbitrage_percentage
                                    - my_investments_garant_partner
                                    - currency_exchange_scores['limit2']
                                    - currency_exchange_scores['limit1']
                                    - currency_exchange_scores['limit3']
                                    - currency_exchange_scores['total_processing_fee'] = $all_investments
                                    - $all_liabilities
                                    - $overdraft
                                    - $all_posted_garant_direct
                                    - $all_pending_garant_direct
                                    - $all_posted_standart_direct
                                    - $all_pending_standart_direct
                                    - $total_processing_payout
                                    + $total_arbitrage_percentage
                                    - $my_investments_garant_partner
                                    - {$currency_exchange_scores['limit2']}
                                    - {$currency_exchange_scores['limit1']}
                                    - {$currency_exchange_scores['limit3']}
                                    - {$currency_exchange_scores['total_processing_fee']} = $max_loan_available";
        //</editor-fold>

        $direct_collateral      = $all_posted_garant_direct + $all_pending_garant_direct + $all_posted_standart_direct + $all_pending_standart_direct;
        $direct_collateral_info = "all_posted_garant_direct
                                    + all_pending_garant_direct
                                    + all_posted_standart_direct
                                    + all_pending_standart_direct = $all_posted_garant_direct
                                    + $all_pending_garant_direct
                                    + $all_posted_standart_direct
                                    + $all_pending_standart_direct = $direct_collateral";

        $int_part      = explode('.', (string) $max_loan_available);
        $numder_digits = strlen($int_part[0]) + 3;
        $FSRC          = round($FSRC, $numder_digits);

        $total_assets = $payment_account +
            $my_bonuses +
            $my_partner_funds +
            $my_investments_standart +
            $my_investments_garant +
            $my_investments_garant_bonuses +
            $my_investments_garant_percent;


        $future_income = $my_future_income_garant + $my_future_income_standart + $expected_partnership_income;
        $balance       = $total_assets - $all_liabilities +
            $future_income -
            $my_bonuses -
//			$my_partner_funds  -
            $my_investments_garant_bonuses -
            $my_investments_garant_percent;

        $max_garant_loan_available = $max_loan_available - $all_advanced_loans_out_summ;

        //--v3-------tech
        $tech_payment_account = 0; //технический


        if($payment_account <= $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value){ ////06.05.2015
            $tech_payment_account      = $payment_account;
            $tech_payment_account_info = "payment_account = $tech_payment_account_info (because $payment_account (payment_account) <= $max_loan_available(max_loan_available) - $arbitrage_collateral(arbitrage_collateral) - $partner_network_value(partner_network_value) - $social_integration_value(social_integration_value))";
        } else {
            $tech_payment_account      = $max_garant_loan_available - $my_investments_garant_percent //проценты по гаранту
                + $loans_standart_out_summ - $arbitrage_collateral + $total_processing_payout - $partner_network_value //06.05.2015
                - $social_integration_value;  //13.06.2015
            $tech_payment_account_info = "max_garant_loan_available
                                    - my_investments_garant_percent
                                    + loans_standart_out_summ
                                    - arbitrage_collateral
                                    + total_processing_payout
                                    - partner_network_value
                                    - social_integration_value =   $max_garant_loan_available
                                    - $my_investments_garant_percent + $loans_standart_out_summ - $arbitrage_collateral + $total_processing_payout - $partner_network_value
                                    - $social_integration_value = $tech_payment_account";
        }

        if($tech_payment_account > $payment_account){
            $tech_payment_account = $payment_account;
            $tech_payment_account_info .= " присвоиили $payment_account(payment_account) потому что $tech_payment_account(tech_payment_account) > $payment_account(payment_account))";
        }


        //Собственные средства
        //               (1,1+1,3+1,4)
        $net_funds      = ($payment_account + ($my_investments_garant + $my_investments_garant_bonuses) + $my_investments_garant_percent ) - ( $my_loans + $total_arbitrage ); //-  (2,1+2,4)
        $net_funds_info = "(payment_account + (my_investments_garant + my_investments_garant_bonuses) + my_investments_garant_percent )
                     - ( my_loans + total_arbitrage ) = ($payment_account + ($my_investments_garant + $my_investments_garant_bonuses) + $my_investments_garant_percent )
                     - ( $my_loans + $total_arbitrage ) = $net_funds";


        $net_funds_by_bonus          = $null_array_by_bonus;
        $net_loan_available_by_bonus = $null_array_by_bonus;
        $net_loan_available          = $max_loan_available - $my_loans;
        foreach($null_array_keys as $b){
            $net_funds_by_bonus[$b] = ($payment_account_by_bonus[$b] + ($b == 6 ? $active_cards_garant_invests : 0) - ($b == 6 ? $active_cards_credits_outsumm : 0) + ($my_investments_garant_by_bonus[$b]) + $my_investments_garant_percent_by_bonus[$b] ) - ( $my_loans_by_bonus[$b] + $total_arbitrage_by_bonus[$b]); //-  (2,1+2,4)
            /* if ( $b == 6){
              $net_funds_by_bonus[$b] += $garant_issued *2;
              $net_funds_by_bonus[$b] -= $garant_received *2;
              } */

            if($max_loan_available_by_bonus[$b] > round($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b], 2)){
                $max_loan_available_by_bonus_info .= "<br>[$b]max_loan_available_by_bonus($max_loan_available_by_bonus[$b]) > net_funds_by_bonus($net_funds_by_bonus[$b]) - total_processing_by_bonus($total_processing_by_bonus[$b])  => max_loan_available_by_bonus = net_funds_by_bonus-total_processing_by_bonus =  ".($net_funds_by_bonus[$b] - $total_processing_by_bonus[$b]);
                $max_loan_available_by_bonus[$b] = $net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b];
                /* if ( $b == 6){
                  $max_loan_available_by_bonus[$b] -= $garant_issued;
                  $max_loan_available_by_bonus[$b] += $garant_received;
                  } */
            }


            $net_loan_available_by_bonus[$b] = max(0, $max_loan_available_by_bonus[$b] - $my_loans_by_bonus[$b]);
        }



        $max_garant_loan_available_by_bonus = $null_array_by_bonus;
        $tech_payment_account_by_bonus      = $null_array_by_bonus; //технический
        $payout_limit_by_bonus              = $null_array_by_bonus;

        foreach($null_array_keys as $b){


            $max_garant_loan_available_by_bonus[$b] = $max_loan_available_by_bonus[$b] -
                $all_posted_garant_loans_out_summ_by_bonus[$b]; //++



            if($payment_account_by_bonus[$b] <= $max_loan_available_by_bonus[$b] - ($b == 6 ? $garant_received : 0)
            // - $arbitrage_collateral_by_bonus[$b] // закоментировали залог по Арбитражу, т.к. он уже вычетается из all_liabilities
            //TODO:-$partner_network_value_by_bonus[$b]
            //TODO: - $social_integration_value_by_bonus[$b]
            ){ ////06.05.2015
                $tech_payment_account_by_bonus[$b] = $payment_account_by_bonus[$b];
            } else {

                $tech_payment_account_by_bonus[$b] = $max_loan_available_by_bonus[$b] - $all_posted_garant_loans_out_summ_by_bonus[$b]//++
                    - $my_investments_garant_percent_by_bonus[$b] //++проценты по гаранту
                    + $total_processing_by_bonus[$b] //вычетается 2 раза: $max_loan_available_by_bonus &  $total_processing_by_bonus
                    - $arbitrage_collateral_by_bonus[$b] + $loans_standart_out_summ_by_bonus[$b]
                //  + $all_active_garant_loans_summa_by_bonus[$b]
                ;
                if($b == 6)
                    $tech_payment_account_by_bonus[$b] -= $garant_received;

                //отрицательно доступно. Равшан, вычел из max_loan_avaliable - garant_issued и поломал всесь расчет
                //1)  Полученный займ «Гарант» уменьшил «Доступно» на тело+проценты, а должен был только на проценты
            }


            //if( $_id == 49966452 ) echo "$b::$tech_payment_account_by_bonus[$b] > $payment_account_by_bonus[$b]<br/>";
            if($tech_payment_account_by_bonus[$b] > $payment_account_by_bonus[$b]){
                $tech_payment_account_by_bonus[$b] = $payment_account_by_bonus[$b];
            }

            //if ( $b==6 ) $tech_payment_account_by_bonus[$b] -= $garant_received;



            $payout_limit_by_bonus[$b] = $tech_payment_account_by_bonus[$b] - $total_processing_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] //++выставленные вклады гарант
                - $all_advanced_standart_invests_summ_by_bonus[$b] //++выставленные вклады стандарт
                + $all_posted_garant_direct_by_bonus[$b] //++
                + $all_posted_standart_direct_by_bonus[$b];


            //if ( $b==6 ) $payout_limit_by_bonus[$b] -= $garant_received;
if( 0  )
if( $_id == 500733 || $_id == 500150 )
{
    echo $payout_limit_by_bonus[4].'<br>';
}
            if($_id == 1140006264)
                echo "$b:
			       T: $tech_payment_account_by_bonus[$b] = $max_loan_available_by_bonus[$b]
                                        - $all_posted_garant_loans_out_summ_by_bonus[$b]
                                        - $my_investments_garant_percent_by_bonus[$b]
                                        + $total_processing_by_bonus[$b]
                                        - $arbitrage_collateral_by_bonus[$b]
                                        + $loans_standart_out_summ_by_bonus[$b];

				P: $payout_limit_by_bonus[$b] =
                    $tech_payment_account_by_bonus[$b]
                    - $total_processing_by_bonus[$b]
                    - $all_advanced_invests_summ_by_bonus[$b]
                    - $all_advanced_standart_invests_summ_by_bonus[$b]
                    + $all_posted_garant_direct_by_bonus[$b]
                    + $all_posted_standart_direct_by_bonus[$b];
			";
        }

        $payout_limit_bonus = array(0 => 0, 2 => 0, 5 => 0, 6 => 0); //доступно по счетам
        $payout_limit       = 0;
//        $payout_limit = $tech_payment_account -
//                $all_advanced_invests_summ - //гарантированные
//                $all_advanced_standart_invests_summ //стандарт
//                + $all_posted_garant_direct
//                + $all_posted_standart_direct;

        $payout_limit = $tech_payment_account -
            $all_advanced_invests_summ - //гарантированные
            $all_advanced_standart_invests_summ //стандарт
            + $all_posted_garant_direct + $all_posted_standart_direct - $total_processing_payout;

        $payout_limit_info = "tech_payment_account -
                all_advanced_invests_summ - //гарантированные
                all_advanced_standart_invests_summ //стандарт
                + all_posted_garant_direct
                + all_posted_standart_direct
                - total_processing_payout = $tech_payment_account -
                $all_advanced_invests_summ -
                $all_advanced_standart_invests_summ
                + $all_posted_garant_direct
                + $all_posted_standart_direct
                - $total_processing_payout = $payout_limit";




        // если бонус по P-CREDS или C-CREDS то $payout_limitсчитаем немного по другому
        /* if ( $bonus == 3)
          $payout_limit = $my_partner_funds;

          if ( $bonus == 4)
          $payout_limit = $c_creds_total;
         */







        //[21.08.15 23:43] raschet ostatka sobstvennih deneg
        $net_own_funds = + $money_sum_add_funds // dobavlennie dengi
            - $money_sum_withdrawal - $money_sum_process_withdrawal - $money_sum_transfer_to_users
            // - $money_sum_transfer_to_exch
            - $money_sum_transfer_to_merchant - $total_garant_loans;

        $net_own_funds_by_bonus_new = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $net_own_funds_by_bonus_new[$b] = + $money_sum_add_funds_by_bonus[$b] // dobavlennie dengi
                - $money_sum_withdrawal_by_bonus[$b]//++
                - $money_sum_process_withdrawal_by_bonus[$b]//++
                - $money_sum_transfer_to_users_by_bonus[$b]//++
                // - $money_sum_transfer_to_exch
                - $money_sum_transfer_to_merchant_by_bonus[$b]//++
                - $total_garant_loans_by_bonus[$b]; //++

            if($net_own_funds_by_bonus_new[$b] < 0)
                $net_own_funds_by_bonus_new[$b] = 0;
        }

        $net_own_funds_info = "
        + money_sum_add_funds // dobavlennie dengi
        - money_sum_withdrawal
        - money_sum_process_withdrawal
        - money_sum_transfer_to_users
        - money_sum_transfer_to_exch
        - money_sum_transfer_to_merchant
        - total_garant_loans = + $money_sum_add_funds
        - $money_sum_withdrawal
        - $money_sum_process_withdrawal
        - $money_sum_transfer_to_users
        - $money_sum_transfer_to_exch
        - $money_sum_transfer_to_merchant
        - $total_garant_loans = $net_own_funds";


        if($net_own_funds < 0){
            $net_own_funds = 0;
            $net_own_funds_info .= ' = 0 потому что был меньше 0';
        }


        // собственные средства с разбивкой на бонус 2 и 5
        $net_own_funds_by_bonus[2] = $payment_bonus_account[2] - $money_bonus_sum_process_withdrawal[2] - $creds_amount_invest_by_bonus[2];
        $net_own_funds_by_bonus[5] = $payment_bonus_account[5] - $money_bonus_sum_process_withdrawal[5] - $creds_amount_invest_by_bonus[5];
        $net_own_funds_by_bonus[6] = $payment_bonus_account[6] - $money_bonus_sum_process_withdrawal[6] - $creds_amount_invest_by_bonus[6];
        $net_own_funds_by_bonus[7] = $payment_bonus_account[7] - $money_bonus_sum_process_withdrawal[7] - $creds_amount_invest_by_bonus[7];


//        if( $payout_limit > $payment_account )
//        {
//          $payout_limit = $payment_account;
//        }
//максимальная сумма, из которой расчитывается Арбитраж
//        if( $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value < $payout_limit )
//        {
//            $arbitrage_temp = $payout_limit;
//        }
//        else
//            $arbitrage_temp = $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value;
//
//        $max_arbitrage_amount = $arbitrage_temp + $all_active_garant_loans_out_summ ;//+ $arbitrage_collateral;
//
//        if( $max_arbitrage_amount > ($arbitrage_temp + $arbitrage_collateral/2)*2.2 )
//            $max_arbitrage_amount = ($arbitrage_temp + $arbitrage_collateral/2) *2.2; //т.к. дырка Москаленко закрыта может убрать это?
//
//        $max_arbitrage_calc = round( $max_arbitrage_amount * 3 / 50 ) * 50;
        //новый max_arbitrage_calc - максимальный расчетный Арбитраж.
        //        if( $all_active_garant_loans_summa > $net_funds )
        //            $max_arbitrage_amount = $net_funds;
        //        else
        //            $max_arbitrage_amount = $all_active_garant_loans_summa;
        //
        //прибаляем потому что $net_funds вычитали
        //получается, что если взял арбитраж, то он вычетается из собственных средств, а для расчеткого количества
        //так не пойдет
//         echo "$all_active_garant_loans_summa > ($net_funds + $total_arbitrage)--";
//         echo $all_active_garant_loans_summa."!";
//         echo $total_grant_loan_new.'!';
//         $total_arbitrage2 ;     // все старые полученные арбитражи


        $arbitrage_collateral_real = $max_loan_available + $all_active_garant_loans_out_summ;
        if($arbitrage_collateral_real < 0)
            $arbitrage_collateral_real = 0;
//         $arbitrage_collateral2; // необходимый размер залога по старым арбитражам
//         if( $_id == 22921387 ) echo "$write_off_arbitration_old = ($arbitrage_collateral2 - $arbitrage_collateral_real)*3;";
        $write_off_arbitration_old = ($arbitrage_collateral2 - $arbitrage_collateral_real) * 3;
        if($write_off_arbitration_old < 17)
            $write_off_arbitration_old = 0;

//         $total_arbitrage3;      //все новые полученные арбитражи
//         $arbitrage_collateral3; // необходимый размер залога по новым арбитражам


        if($arbitrage_collateral2 <= $max_loan_available){
//            if( $_id == 22921387 ) echo  "$write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa_new) * 5;";
            $write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa_new) * 5; //
        } else {
//            if( $_id == 22921387 ) echo  "$write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa) * 5;";
            $write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa) * 5; //
        }

        if($write_off_arbitration_new < 0)
            $write_off_arbitration_new = 0;


//         if( $_id == 22921387 ) echo  "$total_write_off_arbitration = $write_off_arbitration_old + $write_off_arbitration_new;";
        $total_write_off_arbitration = //$write_off_arbitration_old +
            $write_off_arbitration_new;


        $arbitrage_collateral_new = $total_arbitrage / 5;

        $pay_off_arbitration_new = ($arbitrage_collateral_new - $total_grant_loan_new) * 5;

        if($pay_off_arbitration_new < 17)
            $pay_off_arbitration_new = 0; //новички


            //
//        if( $arbitrage_collateral2 <= $max_loan_available )
//            $all_active_garant_loans_summa_temp = $all_active_garant_loans_summa;
//        else
        $all_active_garant_loans_summa_temp = $all_active_garant_loans_summa_new;

        // посчитаем арбитраж по бонусу 2 и 5
        $card_arbitr_active          = min($active_cards_credits, $active_cards_invests) * 5;
        $max_arbitrage_calc_by_bonus = $null_array_by_bonus;
        $max_arbitrage_days_by_bonus = $null_array_by_bonus;

        $akzia_by_bonus = $null_array_by_bonus;
        if(time() < strtotime('2015-12-04 00:00:00')){
            $akzia_by_bonus[2] = 0;
            $akzia_by_bonus[6] = 0
            //	+ $card_arbitr_active
            ;
        }



        foreach([2, 5, 6, 7] as $b){
            if($loan_active_cnt_by_bonus[$b] > 0)
                $max_arbitrage_days_by_bonus[$b] = floor($loan_active_days_remains_by_bonus[$b] / $loan_active_cnt_by_bonus[$b]);

            $all_active_garant_loans_summa_temp_by_bonus[$b] = $all_active_garant_loans_summa_new_by_bonus[$b];
            if($all_active_garant_loans_summa_temp_by_bonus[$b] > ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] ) && $b!=7){
                /* if ( $b == 6){
                  $net_funds_by_bonus[$b] -= $garant_issued*2;
                  $net_funds_by_bonus[$b] += $garant_received*2;
                  } */
                $max_arbitrage_calc_by_bonus[$b] = max(0, ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] ) * 3) + $total_arbitrage2_by_bonus[$b];

            } else if($all_active_garant_loans_summa_temp_by_bonus[$b] < ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] ) && $b!=7 ){

                $max_arbitrage_calc_by_bonus[$b] = $all_active_garant_loans_summa_temp_by_bonus[$b] * 3 + $total_arbitrage2_by_bonus[$b];

            } else {
                if($b == 6)
                    $all_active_garant_loans_summa_temp_by_bonus[$b] -= $garant_issued;
                $max_arbitrage_calc_by_bonus[$b] = $all_active_garant_loans_summa_temp_by_bonus[$b] * 3 + $total_arbitrage2_by_bonus[$b];

            }




            // if ( $b == 2 || $b ==6)
            //    $max_arbitrage_calc_by_bonus[$b] += $akzia_by_bonus[$b];



            if($total_arbitrage_by_bonus[$b] >= 3000)
                $max_arbitrage_calc_by_bonus[$b] = 0;

            $max_arbitrage_calc_by_bonus[$b] = $max_arbitrage_calc_by_bonus[$b] - $total_arbitrage_by_bonus[$b] + $total_arbitrage4_by_bonus[$b];
            if($b == 6)
                $max_arbitrage_calc_by_bonus[$b] += $total_active_usd2_fee_invests * 2; //uje 3 uchitivaetsya na verhu

            if($max_arbitrage_calc_by_bonus[$b] < 0)
                $max_arbitrage_calc_by_bonus[$b] = 0;
            if($max_arbitrage_calc_by_bonus[$b] > 3000)
                $max_arbitrage_calc_by_bonus[$b] = 3000;
            $max_arbitrage_calc_by_bonus[$b] = floor($max_arbitrage_calc_by_bonus[$b] / 50) * 50;
        }

        // посчитаем полный арбитраж
        if($all_active_garant_loans_summa_temp > $net_funds && $net_funds > 0)
            $max_arbitrage_calc = $net_funds * 3;
        else
            $max_arbitrage_calc = $all_active_garant_loans_summa_temp * 3 + $total_arbitrage2;

        if($max_arbitrage_calc > 3000)
            $max_arbitrage_calc = 3000;

        $max_arbitrage_calc = $max_arbitrage_calc - $total_arbitrage;
//        if( $_id == 99423637 ) echo "$max_arbitrage_calc = $max_arbitrage_calc - $total_arbitrage;";
        if($max_arbitrage_calc < 0)
            $max_arbitrage_calc = 0;

        $max_arbitrage_calc = floor($max_arbitrage_calc / 50) * 50;

        //$pay_off_arbitration = $total_write_off_arbitration;
//        if( $total_arbitrage > $max_arbitrage_calc_availiable )
//            $pay_off_arbitration = $total_arbitrage - $max_arbitrage_calc;
//
//        echo "$total_arbitrage $pay_off_arbitration = $total_arbitrage_real - $max_arbitrage_calc_availiable|";
//        echo "$total_arbitrage2 + $total_arbitrage3|";
//
//        //арбитраж к списанию
//
//        if( 0 > $max_arbitrage_calc && $arbitrage_collateral > 0 )
//        {
//            $pay_off_arbitration = - $max_arbitrage_calc;
//        }
        //-------------------
        #Сумма превышение максимального размера пополнений
        //1 + 2 - 6 - 7
        $top_up_overload_orig = $money_sum_add_funds + $money_sum_transfer_from_users
//        + $money_sum_process_withdrawal_bank
            - $money_sum_transfer_to_users - $money_sum_withdrawal;

        if($top_up_overload_orig <= 10000)
            $top_up_overload = 0;
        else
            $top_up_overload = $top_up_overload_orig - 10000;

        //сумма пополенения
        $top_up_sum = $money_sum_add_funds + $money_sum_transfer_from_users + $money_sum_process_withdrawal_bank - $money_sum_transfer_to_users - $money_sum_withdrawal;

        $top_up_sum_overflow = 0;
        if($top_up_sum > 10000){
            $top_up_sum_overflow = $top_up_sum - 10000;
        }

        //доступно на вывод/обмен
        $payout_limit_bonus[5] = $payment_bonus_account[5] - $currency_exchange_scores['net_processing_p2p'] - $currency_exchange_scores['total_processing_p2p'];
        //когда заработают вклады с бонуса 5 и переводы
        //минус внутренние переводы в незавершенных статусах (проверка, проверка СБ, в Ожидании)
        //минус выставленные заявки на вклад с бонусом 5

        $withdrawal_limit_by_bonus = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $bonus_earned_by_bonus[$b] = $bonus_earned_in_by_bonus[$b] - $bonus_earned_out_by_bonus[$b];

            $withdrawal_limit_by_bonus[$b]                = $net_own_funds_by_bonus[$b] +
                $income_merchant_send_by_bonus[$b] +
                $bonus_earned_by_bonus[$b];
            $max_garant_vklad_real_available_by_bonus[$b] = $payment_account_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] - $all_advanced_standart_invests_summ_by_bonus[$b] - $total_processing_by_bonus[$b];
        }

        $max_garant_vklad_real_available = $payment_account - $all_advanced_invests_summ - $all_advanced_standart_invests_summ - $total_processing_payout;


        if(0 && in_array($_id, array(500733, 500757)) &&
            strpos($_SERVER['REQUEST_URI'], 'ajax') == FALSE){

            echo "max_loan_available $max_loan_available<br>payout_limit $payout_limit<br/>";
            echo "limit1 {$currency_exchange_scores['limit1']}<br/>
                limit2 {$currency_exchange_scores['limit2']}<br/>
                limit3 {$currency_exchange_scores['limit3']}<br/>
                net_processing_p2p {$currency_exchange_scores['net_processing_p2p']}<br/>
                total_processing_fee {$currency_exchange_scores['total_processing_fee']}<br/>
                total_processing_p2p {$currency_exchange_scores['total_processing_p2p']}<br/><br/>";
        }

//        var_dump( $payment_account_by_bonus );
//        var_dump( $currency_exchange_scores['total_processing_p2p_by_bonus'] );
//        echo '<br>';
        //-------------------
//if( in_array($_id, ['500733','500150'] ) )#remove
//      echo $payout_limit_by_bonus[4].'<br/>';

        $ratings = array(
            'fsr'                                    => $FSR,
            'fsrc'                                   => $FSRC,
            'fsrc_by_bonus'                          => $FSRC_by_bonus,
            'max_loan_available'                     => $max_loan_available,
            'max_loan_available_by_bonus'            => $max_loan_available_by_bonus,
            'balance'                                => $balance,
            'payment_account'                        => $payment_account,
            'payment_account_by_bonus'               => $payment_account_by_bonus,
            'payment_account_info'                   => $payment_account_info,
            'bonuses'                                => $my_bonuses,
            'partner_funds'                          => $my_partner_funds,
            'all_advanced_invests_bonuses_summ'      => $all_advanced_invests_bonuses_summ,
            'transfered_summ_from_bonus_5_to_2'      => $transfered_summ_from_bonus_5_to_2,
            'investments_garant'                     => $my_investments_garant,
            'investments_garant_by_bonus'            => $my_investments_garant_by_bonus,
            'my_investments_garant_by_bonus'         => $my_investments_garant_by_bonus,
            'my_investments_garant_percent'          => $my_investments_garant_percent,
            'investments_garant_bonuses'             => $my_investments_garant_bonuses,
            'investments_standart'                   => $my_investments_standart,
            'my_investments_standart_by_bonus'       => $my_investments_standart_by_bonus,
            'all_liabilities'                        => $all_liabilities,
            'all_liabilities_by_bonus'               => $all_liabilities_by_bonus,
            'loans'                                  => $my_loans,
            'my_loans_by_bonus'                      => $my_loans_by_bonus,
            'total_arbitrage_by_bonus'               => $total_arbitrage_by_bonus,
            'my_investments_garant_percent_by_bonus' => $my_investments_garant_percent_by_bonus,
            'total_processing_by_bonus'              => $total_processing_by_bonus,
            'max_loan_available_by_bonus_info'       => $max_loan_available_by_bonus_info,
            'pcreds_inout_after_0112'             => $pcreds_inout_after_0112,
            'pcreds_income_after_0112'            => $pcreds_income_after_0112,
            'pcreds_in_payout_process_after_0112' => $pcreds_in_payout_process_after_0112,
            'invests_own_sum_by_bonus'                   => $invests_own_sum_by_bonus,
            'invests_showed_and_active_own_sum_by_bonus' => $invests_showed_and_active_own_sum_by_bonus,
            'loans_percentage' => $my_loans_percentage,
            'loans_percentage_by_bonus'              => $my_loans_percentage_by_bonus,
            'money_sum_transfer_from_users_by_bonus' => $money_sum_transfer_from_users_by_bonus,
            'active_cards_invests'                   => $active_cards_invests,
            'active_cards_credits'                   => $active_cards_credits,
            'all_loans_active_summ_by_bonus' => $all_loans_active_summ_by_bonus,
            'active_cards_credits_outsumm' => $active_cards_credits_outsumm,
            'active_cards_invests_outsumm' => $active_cards_invests_outsumm,
            'active_cards_garant_invests' => $active_cards_garant_invests,
            'active_cards_garant_credits' => $active_cards_garant_credits,
            'card_arbitr_active'          => $card_arbitr_active,
            'garant_issued'         => $garant_issued,
            'garant_received'       => $garant_received,
            'all_active_garant_loans_summa_temp_by_bonus' => $all_active_garant_loans_summa_temp_by_bonus,
            'garant_issued_in_wait' => $garant_issued_in_wait,
            'total_active_blocked_own2'        => $total_active_blocked_own2,
            'total_active_usd2_fee_invests'    => $total_active_usd2_fee_invests,
            'loan_active_summ'                 => $loan_active_summ,
            'loan_active_cnt'                  => $loan_active_cnt,
            'loan_active_summ_by_bonus'        => $loan_active_summ_by_bonus,
            'loan_active_cnt_by_bonus'         => $loan_active_cnt_by_bonus,
            'arbitrage_collateral_by_bonus'    => $arbitrage_collateral_by_bonus,
            'loans_percentage_garant_by_bonus' => $my_loans_percentage_by_bonus,
            'arbitrage_collateral_by_bonus'    => $arbitrage_collateral_by_bonus,
            'loans_percentage_garant_by_bonus' => $my_loans_percentage_by_bonus,
            'showed_cards_credits'        => $showed_cards_credits,
            'showed_cards_garant_credits' => $showed_cards_garant_credits,
            'showed_cards_invests'        => $showed_cards_invests,
            'showed_cards_garant_invests' => $showed_cards_garant_invests,
            'loan_active_days_remains'          => $loan_active_days_remains,
            'loan_active_days_remains_by_bonus' => $loan_active_days_remains_by_bonus,
            'net_loan_available_by_bonus'       => $net_loan_available_by_bonus,
            'net_loan_available'                => $net_loan_available,
            'loans_percentage_garant'                     => $my_loans_percentage_garant,
            'future_income'                               => $future_income,
            'total_future_income'                         => $total_future_income,
            'future_interest_payout'                      => $future_interest_payout, //будущая выплата по процентам
            'all_advanced_loans_out_summ'                 => $all_advanced_loans_out_summ, //сумма тел + %% выставленных заявок на займ Гарант
            'all_advanced_loans_out_summ_by_bonus'        => $all_posted_garant_loans_out_summ_by_bonus, //сумма тел + %% выставленных заявок на займ Гарант
            'all_advanced_invests_summ'                   => $all_advanced_invests_summ, //сумма тел + %% выставленных заявок на вклад Гарант
            'all_advanced_standart_invests_summ'          => $all_advanced_standart_invests_summ, //выставленные заявки на вклад Стандарт
            'all_advanced_standart_invests_summ_by_bonus' => $all_advanced_standart_invests_summ_by_bonus, //выставленные заявки на вклад Стандарт
            'total_processing_payout'                     => $total_processing_payout,
            'total_processing_payout_sum_by_bonus'        => $total_processing_payout_sum_by_bonus,
            'total_processing_payout_fee_by_bonus'        => $total_processing_payout_fee_by_bonus,
            'max_arbitrage_calc_by_bonus'                 => $max_arbitrage_calc_by_bonus,
            'availiable_garant_by_bonus'                  => $availiable_garant_by_bonus,
            'all_advanced_invests_summ_by_bonus'          => $all_advanced_invests_summ_by_bonus,
            'transfered_summ_from_bonus_2_to_6'           => $transfered_summ_from_bonus_2_to_6,
            'transfered_summ_from_bonus_3_to_6'           => $transfered_summ_from_bonus_3_to_6,
            'max_garant_vklad_real_available_by_bonus'    => $max_garant_vklad_real_available_by_bonus,
            'total_garant_loans'                          => $total_garant_loans,
            'total_garant_loans_by_bonus'                 => $total_garant_loans_by_bonus,
            'payout_limit'                                => $payout_limit,
            'payout_limit_by_bonus'                       => $payout_limit_by_bonus,
            'payout_limit_info'                           => $payout_limit_info,
            'money_sum_transfer_to_merchant_by_bonus'     => $money_sum_transfer_to_merchant_by_bonus,
            'arbitr_inout'                                => $arbitr_inout,
            'arbitr_inout_by_bonus'                       => $arbitr_inout_by_bonus,
            'bonus_earned_by_bonus'                       => $bonus_earned_by_bonus,
            'money_sum_withdrawal_by_bonus'               => $money_sum_withdrawal_by_bonus,
            'money_own_from_2_to_6'                       => $money_own_from_2_to_6,
            'max_arbitrage_days_by_bonus'                 => $max_arbitrage_days_by_bonus,
            'akzia_by_bonus'                              => $akzia_by_bonus,
            'money_in_after_26'                           => $money_in_after_26,
            'money_in_after_26_by_bonus'                  => $money_in_after_26_by_bonus,
            'money_in_after_26'                           => $money_in_after_26,
            'money_in_after_26_by_bonus'                  => $money_in_after_26_by_bonus,
            'money_inout_after_26'                        => $money_inout_after_26,
            'money_inout_after_26_by_bonus'               => $money_inout_after_26_by_bonus,
            'total_assets'                                => $total_assets,
            'max_garant_loan_available'                   => $max_garant_loan_available, //Сумма, на которую вы можете получить займ Гарант (включая проценты)
            'total_arbitrage'                             => $total_arbitrage, //остаток по арбитражу
            'arbitrage_collateral2'                       => $arbitrage_collateral2,
            'arbitrage_collateral3'                       => $arbitrage_collateral3,
            'arbitrage_collateral'                        => $arbitrage_collateral, //обеспечение по Арбитражу,
            'arbitrage_collateral2'                       => $arbitrage_collateral2,
            'arbitrage_collateral3'                       => $arbitrage_collateral3,
            'overdraft'                                   => $overdraft,
            'loans_standart_out_summ'                     => $loans_standart_out_summ,
            'sum_partner_reword_by_bonus'                 => $sum_partner_reword_by_bonus,
            'all_active_garant_loans_out_summ'            => $all_active_garant_loans_out_summ,
            'all_active_garant_loans_out_summ_by_bonus'   => $all_active_garant_loans_out_summ_by_bonus,
            'soon'                                        => $expected_partnership_income,
            'direct_collateral'                           => $direct_collateral,
            'overdue_standart_count'                      => $overdue_standart_count,
            'overdue_standart'                            => $overdue_standart,
            'overdue_garant_count'                        => $overdue_garant_count,
            'overdue_garant'                              => $overdue_garant,
            'money_sum_add_funds'                         => $money_sum_add_funds,
            'money_sum_add_funds_by_bonus'                => $money_sum_add_funds_by_bonus,
            'money_sum_add_card'                          => $money_sum_add_card,
            'money_sum_add_card_by_bonus'                 => $money_sum_add_card_by_bonus,
            'money_sum_withdrawal'                        => $money_sum_withdrawal,
            'money_sum_withdrawal_minus_type65'           => $money_sum_withdrawal_minus_type65,
            'money_sum_add_card'                          => $money_sum_add_card,
            'money_sum_add_card_by_bonus'                 => $money_sum_add_card_by_bonus,
            'money_sum_process_withdrawal'                => $money_sum_process_withdrawal,
            'money_sum_transfer_to_users'                 => $money_sum_transfer_to_users, //сумма перевода другим
            'money_sum_transfer_from_users'               => $money_sum_transfer_from_users, //от других
            'sum_partner_reword'                          => $sum_partner_reword, //партнерские
            'sum_partner_reword_by_bonus'                 => $sum_partner_reword_by_bonus,
            'tech_payment_account_info'                   => $tech_payment_account_info,
            'month_partner_unic_id_count'                 => $month_partner_unic_id_count, //Количество партнерских с разных ID for month
            'partner_unic_id_count'                       => $partner_unic_id_count, //Количество партнерских с разных ID for week
            'diversification_coeff'                       => $diversification_coeff, //Коэфицент диверсификации
            'partner_week_contribution'                   => $partner_week_contribution, //Сумма партнерских за неделю
            'partner_contribution_count'                  => $partner_contribution_count, //Количество партнерских отчислений
            'average_partner_contribution'                => $average_partner_contribution, //Средняя сумма партнерского отчисления
            'diversification_degree'                      => $diversification_degree, //Степень Диверсификации
            'partner_network_valuation_coeff'             => $partner_network_valuation_coeff, //Коэфицент оценки партнерской сети
            'partner_network_value'                       => $partner_network_value, //Новый кредитный лимит
            'max_arbitrage_amount'                        => $max_arbitrage_amount, //НЕ ИСПОЛЬЗУЕТСЯ; вместо - max_arbitrage_calc - максимальная сумма, из которой расчитывается Арбитраж
            'overdue_garant_interest'                     => $overdue_garant_interest, //разница процентов по просроченным займам гарант,
            'net_funds'                                   => $net_funds, //Собственные средства
            'net_funds_info'                              => $net_funds_info,
            'fee_or_fine'                                 => $fee_or_fine, //всяктие отчисления, штрафы
            'pay_off_arbitration'                         => $total_write_off_arbitration, //арбитраж к списанию
            'max_arbitrage_calc'                          => $max_arbitrage_calc, //максимально-доступный арбитраж
            'social_integration_value'                    => $social_integration_value, //коэф соц интеграции
            'money_sum_process_withdrawal_bank'           => $money_sum_process_withdrawal_bank,
            'arbitration_garant_active_summ'              => $arbitration_garant_active_summ,
            'arbitration_garant_active_summ_by_bonus'     => $arbitration_garant_active_summ_by_bonus,
            'arbitration_active_summ'                     => $arbitration_active_summ,
            'arbitration_active_summ_by_bonus'            => $arbitration_active_summ_by_bonus,
            'arbitration_shown_summ_by_bonus'             => $arbitration_shown_summ_by_bonus,
            'old_arbitration_active_summ'                 => $old_arbitration_active_summ,
            'old_arbitration_active_summ_by_bonus'        => $old_arbitration_active_summ_by_bonus,
            'sum_loan_active_summ'                        => $sum_loan_active_summ,
            'sum_loan_active_summ_by_bonus'               => $sum_loan_active_summ_by_bonus,
            'loan_garant_active_summ'                     => $loan_garant_active_summ,
            'max_garant_loan_available_by_bonus'          => $max_garant_loan_available_by_bonus,
            'p2p_money_sum_transfer_to_users'             => $p2p_money_sum_transfer_to_users,
            'p2p_money_sum_transfer_to_users_by_bonus'    => $p2p_money_sum_transfer_to_users_by_bonus,
            'p2p_money_sum_transfer_from_users'           => $p2p_money_sum_transfer_from_users,
            'p2p_money_sum_transfer_from_users_by_bonus'  => $p2p_money_sum_transfer_from_users_by_bonus,
            'money_sum_transfer_to_users_by_bonus'        => $money_sum_transfer_to_users_by_bonus,
            'top_up_sum_overflow'                         => $top_up_sum_overflow, //Сумма пополнений
            'top_up_sum'                                  => $top_up_sum, //Сумма пополнений
            'money_sum_transfer_from_exch'                => $money_sum_transfer_from_exch, //Сумма пополнений
            'money_sum_transfer_from_exch_exwt'           => $money_sum_transfer_from_exch_exwt,
            'money_sum_transfer_to_exch'                  => $money_sum_transfer_to_exch, //Сумма пополнений
            'money_diff_transfer_exch'                    => $money_sum_transfer_from_exch - $money_sum_transfer_to_exch, //Сумма пополнений
            'money_sum_transfer_to_users_clear'           => $money_sum_transfer_to_users - $money_sum_transfer_to_exch, //Сумма пополнений
            'money_sum_transfer_from_users_clear'         => $money_sum_transfer_from_users - $money_sum_transfer_from_exch, //Сумма пополнений
            'money_sum_transfer_to_merchant'              => $money_sum_transfer_to_merchant,
            'c_creds_amount'                              => $c_creds_amount,
            'c_creds_total'                               => $c_creds_total,
            'c_creds_amount'                              => $c_creds_amount,
            'net_processing_p2p'                          => $currency_exchange_scores['net_processing_p2p'],
            'net_processing_p2p_by_bonus'                 => $currency_exchange_scores['net_processing_p2p_by_bonus'],
            'total_processing_fee'                        => $currency_exchange_scores['total_processing_fee'],
            'total_processing_fee_by_bonus'               => $currency_exchange_scores['total_processing_fee_by_bonus'],
            'total_processing_p2p'                        => $currency_exchange_scores['total_processing_p2p'],
            'total_processing_p2p_by_bonus'               => $currency_exchange_scores['total_processing_p2p_by_bonus'],
            'bonus_earned_in'                             => $bonus_earned_in,
            'bonus_earned_out'                            => $bonus_earned_out,
            'bonus_earned'                                => $bonus_earned_in - $bonus_earned_out,
            'bonus_earned_in_by_bonus'                    => $bonus_earned_in_by_bonus,
            'bonus_earned_out_by_bonus'                   => $bonus_earned_out_by_bonus,
            'bonus_earned_by_bonus'                       => $bonus_earned_by_bonus,
            'c_creds_amount_process'                      => $c_creds_amount_process,
            'net_own_funds'                               => $net_own_funds,
            'money_own_stab_fond_blocked_by_bonus'        => $money_own_stab_fond_blocked_by_bonus,
            'money_income_stab_fond_blocked_by_bonus'        => $money_income_stab_fond_blocked_by_bonus,
            'net_own_funds_info'                          => $net_own_funds_info,
            'payment_bonus_account'                       => $payment_bonus_account, // разбивка счета по бонусам 2 и 5
            'net_own_funds_by_bonus'                      => $net_own_funds_by_bonus, // собвстенные средтва с разбивкой по бонусам 2 и 5
            'net_own_funds_by_bonus_new'                  => $net_own_funds_by_bonus_new, // собвстенные средтва с разбивкой по бонусам 2 и 5
            'withdrawal_limit_by_bonus'                   => $withdrawal_limit_by_bonus,
            'net_funds_by_bonus'                          => $net_funds_by_bonus,
            'my_income_money'                             => $my_income_money, //Сумма пополнений
            'my_outcome_money'                            => $my_outcome_money, //Сумма out
            'my_income_money_acrhive'                     => $my_income_money_acrhive, //Сумма пополнений в архиве
            'my_outcome_money_acrhive'                    => $my_outcome_money_acrhive, //Сумма списаний в архиве
            'my_outcome_money'                            => $my_outcome_money, //Сумма out
            'my_inoutcome_money_acrhive'                  => $my_income_money_acrhive - $my_outcome_money_acrhive, //Сумма inout в архиве
            'tech_payment_account'                        => $tech_payment_account,
            'all_posted_standart_direct'                  => $all_posted_standart_direct,
            'all_posted_garant_direct'                    => $all_posted_garant_direct,
            'all_active_garant_loans_summa_new'           => $all_active_garant_loans_summa_new,
            'max_garant_vklad_real_available'             => $max_garant_vklad_real_available,
            'payout_limit_bonus'                          => $payout_limit_bonus,
            'income_merchant_send'                        => $income_merchant_send, // входящие переводы мерчанта
            'income_merchant_send_by_bonus'               => $income_merchant_send_by_bonus, // входящие переводы мерчанта
            'income_merchant_return'                      => $income_merchant_return, // входящие возвраты мерчанта
            'outcome_merchant_send'                       => $outcome_merchant_send, // исходящие переводы мерчанта
            'outcome_merchant_send_by_bonus'              => $outcome_merchant_send_by_bonus, // исходящие переводы мерчанта
            'outcome_merchant_return'                     => $outcome_merchant_return, // исходящие возвраты мерчанта
            'outcome_merchant_return_by_bonus'            => $outcome_merchant_return_by_bonus,
            'income_internal_sends'                       => $income_internal_sends, // пополениния внутренних переводов
            'outcome_internal_sends'                      => $outcome_internal_sends // списания внутренних переводов
        );

        if($ratings['pay_off_arbitration'] > 55 && $ratings['pay_off_arbitration'] > $ratings['c_creds_total'])
            $this->checkArbitrationToPayOff($_id, $ratings);

        //если не пусто - удаляем
        if(0){//&& in_array($_id, $test_users) || $this->checkNeedSave( $_id ) )
//            if( $_id == 500733 ) echo "--save-1<br>";
            $need_update = FALSE;
            if(empty($need_calculate_id)){

                $checkRatings_src = $this->checkRatings($ratings);

                $checkRatings = ( $checkRatings_src === TRUE ? 'OK' : 'FAIL: '.$checkRatings_src );
                $this->add_to_log('save_scores', $checkRatings);

                $need_update = ($checkRatings !== TRUE);
            }

            if(!empty($need_calculate_id) || $need_update){
//                echo "saved--";
//                if( $_id == 500733 ) echo "--save-2<br>";
                $new_ratings = $this->saveGetUserRating($_id, $ratings);

                if(empty($new_ratings)){
                    $this->add_to_log('save_scores', 'Empty new scores data');
                    //add to log
                }

                if(!empty($need_calculate_id)){
                    $this->removeArbitrationToPayOff($_id, $last_rating, $ratings);
                    $removeNeedRecalculate    = $this->removeNeedRecalculate($_id, $need_calculate_id);
                    $checkArbitrationToPayOff = $this->checkArbitrationToPayOff($_id, $new_ratings);
                    if($checkArbitrationToPayOff === TRUE){
                        $this->add_to_log('save_scores', 'checkArbitrationToPayOff === TRUE');

                        //add to log
                    }
                    if($removeNeedRecalculate === FALSE){
                        $this->add_to_log('save_scores', 'removeNeedRecalculate === FALSE');
                        //add to log
                    }
                }
            }
        }
        if(null == $date_end && null == $bonus && null == $date_start){
            $this->users_rate[$_id] = $ratings;
        }
        return $ratings;
    }

    public function removeArbitrationToPayOff($user_id, $old_ratings = null, $new_ratings = null, $purpose = NULL){
        if(empty($user_id) ||
            ( (!empty($old_ratings) && !is_array($old_ratings) || !isset($old_ratings['pay_off_arbitration'])) ||
            (!empty($new_ratings) && !is_array($new_ratings) || !isset($new_ratings['pay_off_arbitration']) )
            )
        ){
            return FALSE;
        }

        if($old_ratings['pay_off_arbitration'] > 50 && $old_ratings['pay_off_arbitration'] > 50){
            return FALSE;
        }

        if(!empty($purpose))
            $this->db->where('purpose', $purpose);

        $this->db->where('user_id', $user_id)
            ->delete('users_pay_off_arbitration');

        return TRUE;
    }

    public function checkArbitrationToPayOff($user_id, $new_ratings){
        if(empty($new_ratings) || !is_array($new_ratings) || !isset($new_ratings['pay_off_arbitration'])){
            return FALSE;
        }

        if($new_ratings['pay_off_arbitration'] <= 50){
            return FALSE;
        }

        $pay_off_arbitration = $this->db->select(array('pay_off_arbitration', 'pay_off_date'))
            ->where('user_id', $user_id)
            ->limit(1)
            ->order_by('id', 'ASC')
            ->get('users_pay_off_arbitration')
            ->row();

        $pay_off_date  = date('Y-m-d', time() + 20 * 3600);
        $modifing_date = date('Y-m-d H:i:s', time() + 20 * 3600);

        if(!empty($pay_off_arbitration) &&
            $pay_off_arbitration->pay_off_arbitration == $new_ratings['pay_off_arbitration'] &&
            $pay_off_arbitration->pay_off_date == $pay_off_date
        )
            return FALSE;

        $server_id = 0;

        $data = array(
            'user_id'             => $user_id,
            'server_id'           => $server_id,
            'pay_off_arbitration' => $new_ratings['pay_off_arbitration'],
            'payed_off'           => 0,
            'arbitration_id'      => 0,
            'pay_off_date'        => $pay_off_date,
            'modifing_date'       => $modifing_date,
            'status'              => 0
        );
        //$new_ratings->pay_off_arbitration

        $this->db->insert('users_pay_off_arbitration', $data);


        $this->add_to_log('save_scores', $this->db->last_query());

        return TRUE;
    }

    public function removeNeedRecalculate($user_id, $need_calculate_id){
        if(empty($user_id)){
            $user_id = $this->id_user;
        }
        if($user_id === 0){
            return 0;
        }

        $where = array(
            'user_id' => $user_id,
//            'checked' => 1,
            'id <='   => $need_calculate_id
        );

        $this->db->limit(100)
            ->delete('user_need_recalculate_scors', $where);
//        echo $this->db->last_query();
//
//        var_dump($this->db->affected_rows());

        return !empty($this->db->affected_rows());
    }

    public function getNeedRecalculateId($user_id = null){
        if(empty($user_id)){
            $user_id = $this->id_user;
        }
        if($user_id === 0){
            return 0;
        }


        $user_need_recalculate_scors = $this->db->select('id')
            ->where('user_id', $user_id)
//                                                ->where( 'checked', 0 )
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('user_need_recalculate_scors')
            ->row();

        if(empty($user_need_recalculate_scors)){
            return FALSE;
        }

        return $user_need_recalculate_scors->id;
    }

    /**
     * Conditions when save user ratings. #1st version
     * @param type $user_id
     * @return boolean
     */
    public function checkNeedSave($user_id = null){
        if(empty($user_id)){
            $user_id = $this->id_user;
        }
        if($user_id === 0){
            return 0;
        }

        $user_need_recalculate_scors = $this->db->select('id')
            ->where('user_id', $user_id)
            ->where('checked', 0)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('user_need_recalculate_scors')
            ->row();
        if(empty($user_need_recalculate_scors)){
            return FALSE;
        }

        return $user_need_recalculate_scors->id;
    }

    public function checkRatings($rating_old_version){
        if(empty($rating_old_version)){
            return FALSE;
        }
        $rating_save_version = $this->getLastUserRating();

        if(is_string($rating_old_version['overdue_garant']))
            $rating_old_version['overdue_garant'] = unserialize($rating_old_version['overdue_garant']);

        if(is_string($rating_old_version['overdue_standart']))
            $rating_old_version['overdue_standart'] = unserialize($rating_old_version['overdue_standart']);


        foreach($rating_old_version as $key => $val){
            if(!isset($rating_save_version[$key]) ||
                (!is_array($val) && (string) $val !== (string) $rating_save_version[$key] ) ||
                ( is_array($val) && $val !== $rating_save_version[$key] )
            ){
//                var_dump($val);
//                echo "<br/>";
//                echo "<br/>";
//                var_dump($rating_save_version[ $key ]);
//                echo "<br/>";
//                echo "[$key] $val != {$rating_save_version[ $key ]}<br/>";
//                var_dump($rating_old_version);
//                echo "<br/>";
//                echo "<br/>";
//                var_dump($rating_save_version);
//                echo "<br/>";
//                echo "<br/>";
//                die;
                return "[$key] $val != {$rating_save_version[$key]}";
            }
        }
        return TRUE;
    }

    public function getLastUserRating($user_id = null){
        if(empty($user_id)){
            $user_id = $this->id_user;
        }

        if(empty($user_id)){
            return 0;
        }

        $table_user_current_score_data = 'user_current_score_data';

//        echo "2--";
        $rating = (array) $this->db->limit(1)
                ->where('user_id', $user_id)
                ->get($table_user_current_score_data)
                ->row();

        if(empty($rating)){
            return FALSE;
        }

        $rating['overdue_garant'] = unserialize($rating->overdue_garant);
        if(empty($rating['overdue_garant']))
            $rating['overdue_garant'] = array();

        $rating['overdue_standart'] = unserialize($rating->overdue_standart);
        if(empty($rating['overdue_standart']))
            $rating['overdue_standart'] = array();

        return $rating;
    }

    public function recalculateSaveGetUserRating($_id = null, $date_end = null){
        $rating = $this->recalculateUserRating($_id, $date_end);
    }

    public function saveGetUserRating($_id, $rating){
//        echo "1--";
        if(empty($rating)){
            return FALSE;
        }
//        echo "2--";
        if(is_object($rating)){
            $rating = (array) $rating;
        }
//        echo "3--";
        if(empty($_id)){
            $_id = $this->id_user;
        }

        if(empty($_id)){
            return 0;
        }
//        echo "4--";
        $table_user_current_score_data = 'user_current_score_data';


        $row = $this->db->limit(1)
            ->get_where($table_user_current_score_data, array('user_id' => $_id))
            ->row();

//        echo "5--";

        $rating['date_modified'] = date('Y-m-d H:i:s');
        if(empty($row)){
            $rating['user_id'] = $_id;
        } else {
            unset($rating['user_id']);
        }

        $rating['overdue_garant']   = serialize($rating['overdue_garant']);
        $rating['overdue_standart'] = serialize($rating['overdue_standart']);

        try{
            if(empty($row)){
                $res = $this->db->insert($table_user_current_score_data, $rating);
            } else {
                $res = $this->db->update($table_user_current_score_data, $rating, array('user_id' => $_id));
            }
        } catch(Exception $exc){
            //echo $exc->getTraceAsString();
            return NULL;
        }


        if($res){
//            echo $this->db->last_query();
            return $rating;
        } else {
            return FALSE;
        }
//        echo "6--".$this->db->last_query();
    }

    public function add_to_log($purpose, $str){
        $string = date('Y-m-d h:i:s').' '.$str."\n";

        $log_path = "application/logs/$purpose.log";
        $f        = fopen($log_path, 'a+');
        fwrite($f, $string);
        fclose($f);
    }

    //получение тела+% из формы заявки POST
    function getCreditOutsumFromPOST(){
        $summa = intval($this->input->post('summ'));

        $time    = intval($this->input->post('time'));
        $percent = floatval($this->input->post('percent'));  //   проверить  на  верность  данных

        return credit_summ($percent, $summa, $time);
    }

    public function getUserRating($_id = null){
        return $this->getUserFSR($_id);
    }

    public function getUserFSR($_id = null){
        if(!$_id)
            $_id = $this->id_user;
        if($_id === 0)
            return 0;

        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($_id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->rating;
        }
        $rating = $this->db
            ->where('id_user', $_id)
            ->get('users')
            ->row('rating');

        return $rating;
    }

    public function getUserBalance($_id = null){
        if(!$_id)
            $_id = $this->id_user;
        if($_id === 0)
            return 0;

        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($_id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->balance;
        }

        $rating = $this->db
            ->where('id_user', $_id)
            ->get('users')
            ->row('balance');
        return $rating;
    }

    public function payBonusesOnFirstCredit($id_user, $bonus = Base_model::TRANSACTION_BONUS_ON){
        if(!config_item('bonus_first_credit'))
            return;
        //дата регистрации после указаной в конфиге?
        $this->load->model("users_model", "users");
        $user         = $this->users->getUserData($id_user);
        if(strtotime(config_item('bonus_first_credit_start')) > strtotime($user->reg_date))
            return;
        // это будет первый кредит?
        $this->load->model('credits_model', 'credits');
        $ownCredits   = $this->credits->countActiveORPayedOrders($id_user, Base_model::CREDIT_TYPE_CREDIT);
        if(1 < $ownCredits)
            return;
        // есть ли уже бонус за этого пользователя
        $transactions = $this->db
            ->where('id_user', $user->parent)
            ->where('type', Transactions_model::TYPE_BONUS_FIRST_CREDIT)
            ->where('value', $id_user)
            ->count_all_results('transactions');

        $transactions += $this->db
            ->where('id_user', $user->parent)
            ->where('type', Transactions_model::TYPE_BONUS_FIRST_CREDIT)
            ->where('value', $id_user)
            ->count_all_results('archive_transactions');

        //была ли уже выплата за привлечение?
        if(0 < $transactions)
            return false;
        $this->load->model("transactions_model", "transactions");
        if(!empty($user->parent))
            $this->transactions->addPay($user->parent, 50, Transactions_model::TYPE_BONUS_FIRST_CREDIT, $id_user, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, "Бонус за приглашение заемщика №$id_user");
    }

    public function payBonusesOnRegister($id_user){
        $this->load->model("transactions_model", "transactions");
        $transactions = $this->db
            ->select('note')
            ->where(array('id_user' => $id_user))
            ->where(array('type' => Transactions_model::TYPE_BONUS_FOR_REGESTRATIONS))
            ->count_all_results('transactions');

        $transactions += $this->db
            ->select('note')
            ->where(array('id_user' => $id_user))
            ->where(array('type' => Transactions_model::TYPE_BONUS_FOR_REGESTRATIONS))
            ->count_all_results('archive_transactions');

        //была ли уже выплата за привлечение?
        if(0 < $transactions)
            return false;

        $this->transactions->addPay($id_user, 50, Transactions_model::TYPE_BONUS_FOR_REGESTRATIONS, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, 'Бонус за регистрацию');
    }

    public function payBonusesOnVoteVideo($id_user, $me){
        $transactions = $this->db
            ->select('note')
            ->where(array('id_user' => $id_user))
            ->where("(note = 'Бонус за голос пользователя №$me' OR note = 'Бонус за голосавщего пользователя №$me')")
            ->count_all_results('transactions');
        $transactions += $this->db
            ->select('note')
            ->where(array('id_user' => $id_user))
            ->where("(note = 'Бонус за голос пользователя №$me' OR note = 'Бонус за голосавщего пользователя №$me')")
            ->count_all_results('archive_transactions');

        //была ли уже выплата за голос?
        if(0 < $transactions)
            return false;
        $this->load->model("transactions_model", "transactions");
//       $this->transactions->addPay($id_user, 50, Transactions_model::TYPE_BONUS_VOTE, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, "Бонус за голос пользователя №$me");
    }

    public function payBonusesOnAddVideo($id_user){
        $transactions = $this->db
            ->select('note')
            ->where(array('id_user' => $id_user))
            ->where(array('note' => "Бонус за добавления видео"))
            ->where(array('date >' => '2016-03-01'))
            ->count_all_results('transactions');
     //   $transactions += $this->db
      //      ->select('note')
      //      ->where(array('id_user' => $id_user))
      //      ->where(array('note' => "Бонус за добавления видео"))
       //     ->where(array('date >' => '2016-03-01'))
       //     ->count_all_results('archive_transactions');

        //была ли уже выплата за голос?
        if(0 < $transactions)
            return false;
        $this->load->model("transactions_model", "transactions");
        $this->transactions->addPay($id_user, 50, Transactions_model::TYPE_BONUS_VIDEO, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, "Бонус за добавления видео");
    }

    /**
     *
     * @history
     *
     * начисление бонуса родителю за реферала
     *
     * @param type $child
     * @return boolean
     */
    public function payBonusesToPartner($child){
        $this->load->model("transactions_model", "transactions");
        //$this->load->model("var_model", 'var');
        //--включены условия оплаты бонусов только при регистрации до 14.08.2014
        //if($this->var->get("bonus_off")) return;

        $this->load->model('users_model', 'users');
        //получаем данные о документах до их изменения
        $this->users->setUserId($child);
        $user_res  = $this->users->getUser();
        $user_data = $user_res['user'];

        //отправка бонуса Parent'у
        $parent = $user_data->parent;

        if(0 == $parent)
            return false;


        if(strtotime($user_data->reg_date) < strtotime('2015-09-28 18:25:00')){
            //$this->payBonusesToPartnerValater($parent, $child);
            return;
        }

        $transactions = $this->db
            ->select('note')
            ->where(array('id_user' => $parent))
            ->where(array('note' => "Бонус за привлечение нового пользователя №$child"))
            ->count_all_results('transactions');
        $transactions += $this->db
            ->select('note')
            ->where(array('id_user' => $parent))
            ->where(array('note' => "Бонус за привлечение нового пользователя №$child"))
            ->count_all_results('archive_transactions');

        //была ли уже выплата за привлечение?
        if(0 < $transactions)
            return false;

        //    $this->transactions->addPay(50, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, "Бонус за привлечение нового пользователя №$child", $parent, Base_model::TRANSACTION_BONUS_ON);
        if(strtotime($user_data->reg_date) < strtotime('2016-04-27 00:00:00'))
            $this->transactions->addPay($parent, 50, Transactions_model::TYPE_BONUS_FOR_NEW_USER, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, "Бонус за привлечение нового пользователя №$child");
        else
            $this->transactions->addPay($parent, 50, 900, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, "Бонус за привлечение нового пользователя №$child");
    }

    public function payBonusesToPartnerValater($parent, $child){
        return false;
//        $this->users->setUserId($parent);
//        $user_res = $this->users->getUser();
//        $user_data = $user_res['user'];
//        //отправка бонуса Parent'у
//        $volunteer = $user_data->parent;
//
//        if (0 == $volunteer or Base_model::USER_VOLUNTEER_OFF == $user_data->volunteer)
//            return false;
//
//        $transactions = $this->db
//                ->select('note')
//                ->where(array('id_user' => $volunteer))
//                ->where(array('note LIKE' => "Бонусное вознаграждение за младшего реферала №$child%"))
//                ->count_all_results('transactions');
//        $transactions += $this->db
//                ->select('note')
//                ->where(array('id_user' => $volunteer))
//                ->where(array('note LIKE' => "Бонусное вознаграждение за младшего реферала №$child%"))
//                ->count_all_results('archive_transactions');
//
//        //была ли уже выплата за привлечение?
//        if (0 < $transactions)
//            return false;
//
//        $this->transactions->_addPay(10, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, "Бонусное вознаграждение за младшего реферала №$child от пользователя №$parent", $volunteer, Base_model::TRANSACTION_BONUS_ON);
    }

    /*
     * Подтверждение оплаты
     */

    public function confirmPayment($own, $only_own = TRUE, $date = null){
        if(!is_object($own))
            $own = $this->getCredit($own);

        if(empty($own))
            return 1;

        if($own->id_user != $this->id_user && $only_own == TRUE)
            return 1;

        $this->db->where('id', $own->id)
            ->update('credits', array('confirm_payment' => Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM));

        $offer = $this->getCredit($own->debit);
        if(empty($offer))
            return 2;

        if($offer->confirm_payment != Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM || $own->confirm_payment == Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM)
            return 3;

        // если подтверждается получение денег, то добавляем их на свой счет
        if($own->type == Base_model::CREDIT_TYPE_CREDIT && $own->direct == 1 && $own->bonus == 2){
            $this->load->model('Card_model', 'card');
            $this->card->add_summ_to_own($own->account_id, $own->out_summ, $own->id);
        }

        $this->load->helper('admin');
        $this->load->model("var_model", 'var');

        $data = array(
            'kontract'      => $this->var->get_kontract_count(),
            'date_kontract' => ((NULL == $date) ? date('Y-m-d') : date('Y-m-d', strtotime($date))),
            'state'         => Base_model::CREDIT_STATUS_ACTIVE,
            'out_time'      => credit_time(((NULL == $date) ? date('Y-m-d') : date('Y-m-d', strtotime($date))), $own->time)
        );

        $this->db->update('credits', $data, "id = $offer->id OR id = $own->id");

        return 0;
    }

    public function confirmPaymentCancel($own){
        if(!is_object($own))
            $own = $this->getCredit($own);

        $offer = $this->getCredit($own->debit);

        if(!empty($own))
            $this->hideDebit($own->id);
        if(!empty($offer))
            $this->hideDebit($offer->id);
    }

    /*
     * Подтверждение оплаты
     */

    public function confirmPayments($own, $offer, $confirm_payment = Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM){
        if(!is_object($own))
            $own   = $this->getCredit4Update($own);
        if(!is_object($offer))
            $offer = $this->getCredit4Update($own->debit);

        if(empty($offer) or empty($own))
            return 2;

        $this->load->helper('admin');
        $this->load->model("var_model", 'var');


        $data = array(
            'kontract'        => $this->var->get_kontract_count(),
            'date_kontract'   => date('Y-m-d'),
            'state'           => Base_model::CREDIT_STATUS_ACTIVE,
            'out_time'        => credit_time(date('Y-m-d'), $own->time),
            'confirm_payment' => $confirm_payment
        );

        $this->db->update('credits', $data, "(id = $offer->id OR id = $own->id) AND state = ".Base_model::CREDIT_STATUS_WAITING);
        $res = $this->db->affected_rows();
        if(0 == $res)
            return 3;

        return 0;
    }

    //метод не используеться пользователями..
    public function confirmSend($id){
        $data  = new stdClass();
        $own   = $this->getCredit($id);
        if($own->id_user != $this->id_user)
            return;
        $offer = $this->getCredit($own->debit);

        if($own->confirm_payment != 2 or $own->state != 3 or empty($offer) or $own->type != 2)
            return;

        $accessMoney = $this->base_model->getMoney() - $this->base_model->getBonus();
        if($own->summa > $accessMoney){
            accaunt_message($data, 'В вашем кошельке недостаточно средств', 'error');
            return 1;
        }

        $this->db->trans_start();
        $res = $this->confirmPayments($own, $offer);
        if(0 == $res){
            $this->load->model("transactions_model", "transactions");
            $this->transactions->addPay($own->id_user, $own->summa, Transactions_model::TYPE_EXPENSE_INVEST, $own->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, "Снятие средств по заявке №№$own->id");
            if($offer->blocked_money == 4)
                $this->transactions->addPay($offer->id_user, $offer->income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 4, "Прибыль по вкладу №$offer->id");
            else {

                if($offer->bonus == 1)
                    $bonus = 3;
                $bonus = $this->base_model->getBonusTypeByCreditNew($offer->bonus);
                $this->load->model('users_model', 'users');
                if($offer->bonus == 2 && $this->users->isUsaLimitedUser($offer->id_user))
                    $bonus = 6;
                $this->transactions->addPay($offer->id_user, $offer->income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $bonus, "Прибыль по вкладу №$offer->id");
            }

            $this->transactions->addPay($offer->id_user, $own->summa, Transactions_model::TYPE_INCOME_LOAN, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_OFF, "Зачисление средств по заявке №№$offer->id");
        }
        $this->db->trans_complete();
        accaunt_message($data, _e('Перевод успешно совершен'));
        return 0;
    }

    public function confirmReturn($id){

        $own = $this->getCredit($id);
        if($own->id_user != $this->id_user or strtotime($own->out_time) > strtotime(date('Y-m-d')) or empty($own->out_time) or ( $own->state != 2 and $own->state != 4 ))
            return;
        $this->own()->where('id', $id)->update('credits', array('confirm_return' => '1'));

        $offer = $this->getCredit($own->debit);
        if(empty($offer))
            return;
        if($offer->confirm_return != 1 or $own->confirm_return == 1)
            return;
        $this->db->update('credits', array('state' => Base_model::CREDIT_STATUS_PAID), "id = $offer->id OR id = $own->id");
        // перечислим сумму вкладчику на свой счет
        if($own->direct == 1 && $own->bonus == 2){
            $this->load->model('Card_model', 'card');

            if(strtotime($own->out_time) <= strtotime(date('Y-m-d')) && strtotime($own->out_time) > 0){
                $time_days = calculate_debit_time_now($own->date_kontract);
                $out_summ  = credit_summ($own->percent, $own->summa, $time_days);
                $income    = $out_summ - $own->summa;
            }

            $this->card->add_summ_to_own($own->account_id, $own->summa, $own->id);
            $this->card->add_summ_to_own($own->account_id, $income, $own->id);
        }
    }

    public function confirmReturns($own, $offer = NULL){
        if(!is_object($own))
            $own   = $this->getCredit($own);
        if(!is_object($offer))
            $offer = $this->getCredit($own->debit);
        if(empty($offer))
            return;

        if($own->id_user != $this->id_user
            or ( strtotime($own->out_time) > strtotime(date('Y-m-d')) && $own->bonus != 9 )
            or empty($own->out_time)
            or ( $own->state != 2 and $own->state != 4 ))
            return;

        $this->db->update('credits', array('state' => Base_model::CREDIT_STATUS_PAID, 'confirm_return' => '1'), "id = $offer->id OR id = $own->id");

        $this->load->model('Fincore_model', 'fincore');
        $this->fincore->return_gift_invests($offer);

        if($offer->bonus == 7 && ($offer->arbitration == 2 || $offer->arbitration == 3 )&& $offer->sum_arbitration > 0){
            $this->load->model('arbitration_model', 'arbitration');
            $this->arbitration->return_arbitration_from_loan($offer);
        }

    }

    #проверка наличия транзакций после работы функции confirmSendReturn

    public function checkConfirmSendReturn($credit_id){
        if(empty($credit_id))
            return 1;

        $credit = $this->getCredit($credit_id);
        if(empty($credit))
            return 2;

        $credits = $this->db->where('value', $credit->id)
            ->where('id_user', $credit->id_user)
            ->get('credits')
            ->result();

        if(empty($credits))
            return FALSE;


        $func_name = 'checkConfirmSendReturn';

        $original                          = array();
        #Погашение кредита №credit->id
        $original['Погашение кредита']     = array('status'   => Base_model::TRANSACTION_STATUS_REMOVED,
            'type'     => Transactions_model::TYPE_EXPENSE_INVEST_REPAY,
//                             'value' => Transactions_model::TYPE_EXPENSE_INVEST_REPAY,
            'metod'    => 'wt',
            'bonus'    => Base_model::TRANSACTION_BONUS_OFF,
            'out_summ' => 'out_summ'
//                           'note' => "/№{$credit->id}/"
        );
        #Оплата вклада  №credit->debit
        $original['Оплата вклада']         = array('status'   => Base_model::TRANSACTION_STATUS_RECEIVED,
            'type'     => Transactions_model::TYPE_INCOME_REPAY,
            'metod'    => 'wt',
            'bonus'    => Base_model::TRANSACTION_BONUS_OFF,
            'note'     => "/№{$credit->id}/",
            'out_summ' => 'out_summ'
        );
        #Отчисление в по займу №
        $original['Отчисление в по займу'] = array('status' => Base_model::TRANSACTION_STATUS_REMOVED,
            'type'   => Transactions_model::TYPE_EXPENSE_FEE_REPAY,
            'metod'  => 'wt',
            'bonus'  => Base_model::TRANSACTION_BONUS_OFF,
            'note'   => "/№{$credit->id}/"
        );

        $checks = array(); //собираем количество совпадений
        $errors = array(); //собираем ошибки по проверке
        foreach($credits as $c){
            #проверка типа транзакций
            $checked_set_name = FALSE;
            foreach($original as $set_name => $fields){
                #проверка 1 типа транзакции - перебор всех полей
                $check = TRUE;
                foreach($fields as $field_name => $filed_value){
                    if($credits->{$field_name} != $filed_value){
                        $check = FALSE;
                        break;
                    }
                }
                if(FALSE === $check)
                    continue;

                $checked_set_name = $set_name;
                break;
            }

            #ни один тип не подошел
            if(FALSE === $checked_set_name){
                $errors[] = array('status' => '', 'human_status' => "Undetected transaction for {$func_name}", 'data' => $c);
                continue;
            }
            #собираем транзакции по типам
            if(!isset($checks[$checked_set_name]))
                $checks[$checked_set_name] = array();

            $checks[$checked_set_name] = $c;
        }

        #проверяем количество  транзакций
        foreach($checks as $t_name => $set_t){
            $count = count($set_t);
            if($count === 1)
                continue;

            if($count === 0){
                $errors[] = array('status' => '', 'human_status' => "There is no '{$t_name}' for {$func_name}");
                continue;
            }
            //$count >1
            $errors[] = array('status' => '', 'human_status' => "Too many transactions '{$t_name}' for {$func_name}", 'data' => $set_t);
        }

        #TODO: записать ошибки с таблицу ошибок

        if(count($errors) !== 0)
            return FALSE;

        return TRUE;
    }

    public function reportSend($id){
        $own     = $this->getCredit($id);
        if($own->id_user != $this->id_user)
            return 1;
        $offer   = $this->getCredit($own->debit);
        $replace = array('debit' => $id, 'contr_id_user' => $offer->id_user, 'message' => $this->input->post('message', true), 'contr_name' => $this->base_model->getUserField($offer->id_user, 'name'), 'contr_sername' => $this->base_model->getUserField($offer->id_user, 'sername'));
        $this->mail->admin_sender('admin_report', $own->id_user, $replace, 0);

        $replace = array(
            'debit'         => $id,
            'contr_id_user' => $own->id_user,
            'message'       => $this->input->post('message', true),
            'contr_name'    => $this->base_model->getUserField($own->id_user, 'name'),
            'contr_sername' => $this->base_model->getUserField($own->id_user, 'sername'));
        $this->mail->user_sender('user_report', $offer->id_user, $replace, 0);

        return 0;
    }

    /*
     * Получнение номера контранкта по id
     */

    public function getNumberKontract($id){
        return $this->own()->where('id', $id)->get('credits')->row('kontract');
    }

    /*
     * Вывбрка заявки для подачи предложения
     */

    public function creditTicket($id, $type, $own = TRUE){
        if($own == TRUE)
            return $this->db
                    ->where(array('id'         => (int) $id,
                        'state'      => Base_model::CREDIT_STATUS_SHOWED,
                        'type'       => $type,
                        'id_user !=' => $this->id_user,
                        'debit'      => '0'))
//                        ->set_lock_in_share_mode()
                    ->get('credits')
                    ->row();
        else
            return $this->db
                    ->where(array('id'    => (int) $id,
                        'state' => Base_model::CREDIT_STATUS_SHOWED,
                        'type'  => $type,
                        'debit' => '0'))
//                            ->set_lock_in_share_mode()
                    ->get('credits')
                    ->row();
    }

    /*
     * Получение данных дебита
     */

    public function getCredit($id){
        $c = $this->db->where('id', $id)
            ->get('credits')
            ->row();
        //    if (empty($c))
        //        $this->db->where('id', $id)
        //                    ->get('archive_credits')
        //                    ->row();
        return $c;
    }

    public function getCredit4DBTransaction($id){
        return $this->db->where('id', $id)
                ->set_lock_in_share_mode()
                ->get('credits')
                ->row();
    }

    public function getDebit($id){
        $c = $this->db->where('id', $id)
            ->get('credits')
            ->row();
        //    if (empty($c))
        //        $this->db->where('id', $id)
        //                    ->get('archive_credits')
        //                    ->row();
        return $c;
    }

    /*
     * Получение данных дебита
     */

    public function getCredit4Update($id){
        $c = $this->db->where('id', $id)
            ->get('credits')
            ->row();
        //    if (empty($c))
        //        $this->db->where('id', $id)
        //                    ->get('archive_credits')
        //                     ->row();
        return $c;
    }

    /*
     *  Получение дебита текущего пользователя
     */

    public function getUserCredit($id, $own = true){
        if(TRUE == $own)
            return $this->own()->where('id', $id)->get('credits')->row();
        else
            return $this->db->where('id', $id)->get('credits')->row();
    }

    /**
     * Возвращает тип заявки.
     * Если $two_sides ложно, то он возвращает только в том случае результат,
     * когда владелец - настоящий пользователь.
     * Если $two_sides ложно, то он возвращает только в том случае результат,
     * когда Другая сторона - настоящий пользователь.
     *
     * @param type $id
     * @param type $two_sides
     * @return boolean
     */
    public function getCreditType($id, $two_sides = FALSE){
        $res = $this->own()
            ->where('id', $id)
            ->limit(1)
            ->get('credits')
            ->row();

        if($two_sides === FALSE || (!empty($res) && isset($res->type) )){
            if(isset($res->type))
                return $res->type;

            return FALSE;
        }


        $res = $this->db->where('id', $id)
            ->limit(1)
            ->get('credits')
            ->row();

        if(empty($res) || $res->debit_id_user != $this->id_user){
            return FALSE;
        }
        return $res->type;
    }

    public function getCreditState($id){
        return $this->own()->select('state')->where('id', $id)->get('credits')->row('state');
    }

    public function getCreditRelation($id){
        return $this->db
                ->select('debit')
                ->set_lock_in_share_mode() //set_for_update()
                ->where('id', $id)
                ->get('credits')
                ->row('debit');
    }

    /*
     *  Полученине общей суммы дебитов по типу, активных и просроченных
     */
    public function getCredits(){
        return $this->getDebitSumm(1);
    }
    public function getInvests(){
        return $this->getDebitSumm(2);
    }


    private function getDebitSumm($type){
        return $this->own()->select_sum('summa')->where('type', $type)->where('state', 2)->get('credits')->row('summa');
    }

    public function sellCertificate($i, $cause = 0){
        $this->load->model("credits_model", "credits");
        //Open TRANSACTION!!!!
        $this->db->trans_start();{
            if(!is_object($i))
                $i = $this->getCredit4Update($i);
            if(empty($i)){
                log_massage('error', 'Имеются некоторые проблемы с продажей сертификата (sellcertificat/$i is empty)');
                //die("Извините за некоторые неудобства, что-то пошло не так...");
                return _e('Извините за некоторые неудобства, что-то пошло не так...');
            }
            if(0 === $cause)
                $cause = (time() > strtotime($i->out_time)) ? Base_model::CREDIT_CERTIFICAT_CAUSE_OVERDUE : Base_model::CREDIT_CERTIFICAT_CAUSE_SELL;

            $this->db->update('credits', array('certificate'           => Base_model::CREDIT_CERTIFICAT_PAID,
                'certificate_pay_cause' => $cause,
                'state'                 => Base_model::CREDIT_STATUS_PAID,
//                          'kontract' => Base_model::CREDIT_KONTRACT_PAID,
//                          'date_kontract' => date('Y-m-d'),
                'out_time'              => date('Y-m-d'), //Base_model::CREDIT_KONTRACT_PAID_OUT_TIME,
                'debit'                 => Base_model::CREDIT_CERTIFICAT_PAID_NEW_DEBIT,
                'previous_debit'        => $i->debit,
                ), array('id' => (int) $i->id)
            );

            $d = $this->getCredit4Update($i->debit);

            $newID = $this->credits->addOrder($d, Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND, $i->bonus, Base_model::CREDIT_OVERDRAFT_OFF, -1, 37900, $d->account_type, $d->account_id);
            if(0 >= $newID){
                log_message('error', 'Имеются некоторые проблемы с продажей сертификата (sellcertificat/NewId is empty)');
                //die("Имеются некоторые проблемы с продажей сертификата");
                return _e('Невозможно создать заявку'."($newID)");
            }

            if($i->bonus == 7){
                $load_data          = new stdClass();
                $load_data->id      = 'sc_'.$i->id.'_'.rand(1, 100000);
                $load_data->card_id = $i->card_id;
                $load_data->user_id = $i->id_user;
                $load_data->summa   = round(countCertificate($i->summa, $i->date_kontract), 2);
                $load_data->desc    = "Sell Certificate # $i->id from ".date('d/m/Y');
                $this->load->model('Card_model', 'card');
                $response = $this->card->load($load_data, Card_transactions_model::CARD_SELL_CERT, $i->id);

                if(false === $response){
                    $this->db->trans_rollback();
                    return _e('Ошибка зачисления средств на карту');
                } else {
                    $this->load->model('Fincore_model', 'fincore');
                    $this->fincore->autoReturnInvesterLoans($i->id_user, $i->card_id);
                }
            }

            //подтвердить кредит - скапировав из прошлой заявку данные
            $this->db->update('credits', array('state'           => Base_model::CREDIT_STATUS_ACTIVE,
                'kontract'        => $i->kontract,
                'date_kontract'   => $i->date_kontract,
                'out_time'        => $i->out_time,
                'confirm_payment' => Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM,
                'exchange'        => Base_model::CREDIT_EXCHANGE_OFF,
                'summ_exchange'   => 0
                ), "id = $newID OR id = $d->id"
            );
        }
        //CLOSE TRANSACTION!!!!
        $this->db->trans_complete();
        return TRUE;
    }

    /*
     * Получение списков дебитов для вывода в личном кабинете
     */

    public function get_credits($limit = FALSE, $where = FALSE, $where_in = FALSE){
        return $this->get_debits(Base_model::CREDIT_TYPE_CREDIT, $limit, $where, $where_in);
    }

    public function set_status_credit($status, $id){
        if(empty($status) || empty($id) || !is_numeric($id))
            return FALSE;

        $this->db->where('id', $id)->limit(1)->update('credits', array('state' => $status));
        return TRUE;
    }

    public function get_invests($limit = FALSE, $where = FALSE, $where_in = FALSE){
        return $this->get_debits(Base_model::CREDIT_TYPE_INVEST, $limit, $where, $where_in);
    }

    public function get_orders($type = FALSE, $limit = FALSE, $where = FALSE, $where_in = FALSE){
        return $this->get_debits($type, $limit, $where, $where_in);
    }

    private function get_debits($type = FALSE, $limit = FALSE, $where = FALSE, $where_in = FALSE){

        if($where_in[0] == 'state' && !$where)
            $where = array('id !=' => '0');
        else if(!$where)
            $where = array('state !=' => '7');

        if($where_in)
            $this->db->where_in($where_in[0], $where_in[1]);
        if(false !== $type)
            $this->db->where(array('type' => $type));
        $count = $this->own()
            ->where($where)
            ->count_all_results('credits');

        if($where_in)
            $this->db->where_in($where_in[0], $where_in[1]);
        if($limit)
            $this->db->limit($limit[0], $limit[1]);
        if(false !== $type)
            $this->db->where(array('type' => $type));
        $data = $this->own()
            //->order_by('state asc, id desc') // bilo garant_percent desc i date_Desc
            ->order_by('field(state, 3,1,2,5,4,6,7,8),id desc', '', FALSE) // bilo garant_percent desc i date_Desc
            ->where($where)
            ->get('credits')
            ->result();

        // сбор всех заявок
        $request = $this->db
            ->select('i.*, u.id_user, u.name, u.bot,  u.sername')
            ->where(array('user_to' => $this->id_user, 'admin' => 2))
            ->where_in('i.status', array(1, 2))
            ->join('users u', 'u.id_user = i.user_from')
            ->order_by('i.id')
            ->get('inbox i')
            ->result();


        $inbox = array();
        foreach($request as $r){
            $r->name            = $this->code->decrypt($r->name);
            $r->sername         = $this->code->decrypt($r->sername);
            $r->bot             = $r->bot;
            $inbox[$r->debit][] = $r;
        }
        $out = array();
        foreach($data as $item){
            // получение данных о контрагенте
            if(!empty($item->debit)){
                $item->offer = $this->db->where('id', $item->debit)->get('credits')->row();
                if(!empty($item->offer))
                    $item->user  = $this->getUserFields($item->offer->id_user, 'name, sername');
                else {
                    $item->offer = $this->db->where('id', $item->debit)->get('archive_credits')->row();
                    if(!empty($item->offer))
                        $item->user  = $this->getUserFields($item->offer->id_user, 'name, sername');
                }
            }
            //список заявок
            $item->debits = (isset($inbox[$item->id]) and $item->state == 1) ? $inbox[$item->id] : array();
            $item->count  = $count;

            $out[] = $item;
        }


        return $out;
    }

    /*
     * Получение архивный списков дебитов для вывода в личном кабинете
     */

    public function get_archive_credits($limit = FALSE){
        return $this->get_archive_debits(Base_model::CREDIT_TYPE_CREDIT, $limit);
    }

    public function get_archive_invests($limit = FALSE){
        return $this->get_archive_debits(Base_model::CREDIT_TYPE_INVEST, $limit);
    }

    private function get_archive_debits($type, $limit = FALSE){
        $count = $this->own()
            ->where(array('state !=' => '7', 'type' => $type))
            ->count_all_results('archive_credits');
        if($limit)
            $this->db->limit($limit[0], $limit[1]);
        $data  = $this->own()
            ->order_by('id  desc')
            ->where(array('state !=' => '7', 'type' => $type))
            ->get('archive_credits')
            ->result();

        // сбор всех заявок
        $request = $this->db->select('i.*, u.id_user, u.name, u.bot,  u.sername')->
                where(array('user_to' => $this->id_user, 'admin' => 2))->
                where_in('i.status', array(1, 2))->
                join('users u', 'u.id_user = i.user_from')->
                get('inbox i')->result();

        $inbox = array();
        foreach($request as $r){
            $r->name            = $this->code->decrypt($r->name);
            $r->sername         = $this->code->decrypt($r->sername);
            $r->bot             = $r->bot;
            $inbox[$r->debit][] = $r;
        }
        $out = array();
        foreach($data as $item){
            // получение данных о контрагенте
            if(!empty($item->debit)){
                $item->offer = $this->db->where('id', $item->debit)->get('credits')->row();
                if(!empty($item->offer)){
                    $item->user = $this->getUserFields($item->offer->id_user, 'name, sername');
                }
            }

            //список заявок
            $item->debits = (isset($inbox[$item->id]) and $item->state == 1) ? $inbox[$item->id] : array();
            $item->count  = $count;
            $out[]        = $item;
        }
        return $out;
    }

    /*
     *  Управление дебитами
     */

    // установка связанного дебита
    private function setCreditDebit($id, $val){
        $u_val = $this->getDebit($val);
        $this->db
            ->where('id', $id)
            ->update('credits', array('debit' => $val, "debit_id_user" => $u_val->id_user));
    }

    public function setCreditRelations($id1, $id2, $delete = false){
        $this->getCredit($id1);
        $this->getCredit($id2);
        if($delete == true){
            $this->setCreditDebit($id1, 0);
            $this->setCreditDebit($id2, 0);
        } else {
            $this->setCreditDebit($id1, $id2);
            $this->setCreditDebit($id2, $id1);
        }
    }

    // отклонение дебита
    public function hideDebit($id){
        $this->load->model("transactions_model", 'transactions');
        $this->db->where('id', $id)->update('credits', array('state' => 7));
        $this->db->where('debit', $id)->update('inbox', array('sender_view' => 1, 'recipient_view' => 1, 'status' => 3, 'cause' => 'cancel'));
        $this->db->where([
            'type'  => Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST,
            'value' => $id
        ])->update('transactions', ['status' => Base_model::TRANSACTION_STATUS_DELETED]);
    }

    // отклонение дебита
    public function delContract($id){
        $this->db->where('id', $id)->update('credits', array('state' => 1));
        $this->db->where('debit', $id)->update('credits', array('state' => 1));
        $crId = $this->db->where("debit", $id)->get('credits')->row()->id;
        $this->db->where('debit', $crId)->update('inbox', array('sender_view' => 1, 'recipient_view' => 1, 'status' => 3, 'cause' => 'Заявка расторгнута Кредитором'));
    }

    // добавление связанного дебита
    public function addOrder($debit, $user, $bonus = -1, $overdraft = -1){
        $data = new stdClass();
        //новый способ реализации - credits->addOrder();
        trigger_error("accaunt_model->addOrder() depricated. Please use credits->addOrder()", E_USER_DEPRECATED);

        if(!is_object($debit))
            $debit = $this->getCredit($debit);

        if(empty($debit))
            return null;

        //нельзя передавать так параметры!
        $_POST['summ']    = $debit->summa;
        $_POST['time']    = $debit->time;
        $_POST['percent'] = $debit->percent;
        $_POST['payment'] = getPaymentOrger($debit->payment);

        if($debit->bonus)
            $_POST['bonus'] = $debit->bonus;


        $garant = $debit->garant;
        $direct = $debit->direct;
        //если кредит - то создает предложившиму пользователю инвестицию
        if(Base_model::CREDIT_TYPE_CREDIT == $debit->type)
            $newID  = $this->base_model->add_invest($user, $garant, $bonus, $overdraft, $direct);
        else
            $newID  = $this->base_model->add_credit($user, $debit->summa, $garant, $bonus, $overdraft, $direct);
        if(empty($newID))
            throw new Exception("Ошибка. Не получилось создать ".((Base_model::CREDIT_TYPE_CREDIT == $debit->type) ? "инвестицию" : "кредит"));

        $this->db->where('id', $newID)
            ->update('credits', array('state' => Base_model::CREDIT_STATUS_WAITING));

        $this->db->where('id', $debit->id)
            ->update('credits', array('state' => Base_model::CREDIT_STATUS_WAITING));

        $this->setCreditRelations($debit->id, $newID);
        //$this->mail->admin_sender('offer_admin', $user, $newID, $newID);
        //модель обрабатывает данные, и выдает не выдает сообщение в системном виде
        //такого не должно быть здесь, необходимо перенести в котроллер
        //@esb - не согласен, эта функция направленость её это работа с БД и выполняет
        // все необходимое до и после вставки в БД на меня так тут все ОК

        accaunt_message($data, 'Спасибо, ваша заявка принята.');

        return $newID;
    }

    public function isOrderUsed($id){
        return (0 < $this->db->where("debit", $id)->count_all_results("credits")) ? true : false;
    }

    /*
     *  Транзакции
     */

    public function getPays($limit, $per = 0, $where = array()){
        $this->load->library('trans_db', array('note'));

        //$where = array_merge(array("status !=" => Base_model::TRANSACTION_STATUS_IN_PROCESS), $where);

        $where["status !="] = Base_model::TRANSACTION_STATUS_IN_PROCESS;
        if(!empty($where))
            foreach($where as $key => $value){
                if(!empty($key))
                    $this->db->where($key, $value);
                else
                    $this->db->where($value);
            }
        $pays = $this->own()
            //->where($where)
            ->where('summa > 0')
            ->where('type <> 1000')
            ->where_not_in('type', [82, 83, 84])
            ->limit($limit, $per)
            //  ->order_by('date desc')
            ->order_by('id desc')
            ->get('transactions')
            ->result();

        return $this->trans_db->translate($pays);
    }

    public function getCountPays($where = array()){

        if(!empty($where))
            foreach($where as $key => $value){
                if(!empty($key))
                    $this->db->where($key, $value);
                else
                    $this->db->where($value);
            }
        return $this->own()
                //->where($where)
                ->where('summa > 0')
                ->where('type <> 1000')
                ->where_not_in('type', [82, 83, 84])
                ->count_all_results('transactions');
    }

    public function arhive_getPays($limit, $per = 0, $where = array()){
        $this->load->library('trans_db', array('note'));
        $where = array_merge(array("status !=" => Base_model::TRANSACTION_STATUS_IN_PROCESS), $where);
        return $this->trans_db->translate($this->own()->limit($limit, $per)->order_by('id desc')->where($where)->where('summa > 0')->get('archive_transactions')->result());
    }

    public function arhive_getCountPays($where = array()){
        return $this->own()->where($where)->where('summa > 0')->count_all_results('archive_transactions');
    }

    public function getPaysOut($limit, $per = 0){
        $this->load->model("transactions_model", 'transactions');
        $this->load->library('trans_db', array('note'));
        return $this->trans_db->translate($this->own()->limit($limit, $per)
//->where("(metod = 'out' OR (metod = 'wt' AND (type = '74' OR type = '75'))) AND (status = '4' OR status = '11' OR status = '9' OR status = '10')")
                    ->where("(status = '4' OR status = '11' OR status = '9' OR status = '10') AND bonus != 9")
//->order_by('date desc')
                    ->get('transactions')->result());
    }

    public function getCountPaysOut(){
        return $this->own()->where(array("metod" => "out", "bonus !=" => 0, "status" => Base_model::TRANSACTION_STATUS_IN_PROCESS))->count_all_results('transactions');
    }

    public function getPayment($limit, $per = 0){
        $this->load->library('trans_db', array('note'));

        $payments = $this->db->limit($limit, $per)
            // ->where("(metod = 'wt' OR metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen')")
            ->where(array("id_user" => $this->id_user)) //, "id_user <>" => $this->id_user
            ->where("status", Base_model::TRANSACTION_STATUS_NOT_RECEIVED)
            // ->order_by('id desc')
            ->get('transactions')
            ->result();
        //print_r($this->db->last_query());die();
        return $this->trans_db->translate($payments);
    }

    public function getCountPayment(){
        return $this->db
                //->where("(metod = 'wt' OR metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen')")
                ->where(array("id_user" => $this->id_user)) //, "id_user <>" => $this->id_user
                ->where("status", Base_model::TRANSACTION_STATUS_NOT_RECEIVED)
                ->count_all_results('transactions');
    }

//    public function addPay($summa, $metod, $status = Base_model::TRANSACTION_STATUS_NOT_RECEIVED, $note = '', $id_user = false, $bonus = Base_model::TRANSACTION_BONUS_OFF) {
//        trigger_error ("base_model->addPay() depricated. Please use transactions->oldAddPay() or addPay()", E_USER_DEPRECATED);
//        $this->load->model("transactions_model", 'transactions');
//        return $this->transactions->oldAddPay($summa, $metod, $status, $note, $id_user, $bonus);
//    }


    /*
     * Get user's prefer payment method and account number
     */

    public function getDefaultPaymentData($id_user = null, $is_array = false){
        $id_user = (null == $id_user) ? $this->id_user : $id_user;
        if($id_user == 0)
            return '';

        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data      = $this->users->getCurrUserData();
            $user_data = $data;
        } else
            $user_data = $this->code->db_decode($this->db->get_where("users", array('id_user' => $id_user))->row());

        if(empty($user_data))
            return '';
        $payment_num = $user_data->payment_default;

        $array = array(
            0  => array("не определено", "-"),
            1  => array("Номер карты", "bank_cc"),
            2  => array("QIWI", "bank_qiwi"),
            3  => array("Paypal", "bank_paypal"),
            4  => array("Tinkoff Wallet", "bank_tinkoff"),
            5  => array("Webmoney", "bank_webmoney"),
            6  => array("Liqpay", "bank_liqpay"),
            7  => array("Yandex", "bank_yandex"),
            8  => array("Банк", "bank_name"),
            9  => array("RBK Money", "bank_rbk"),
            10 => array("W1 USD", "bank_w1"),
            11 => array("W1 RUB", "bank_w1_rub"),
            12 => array("Dengi Mail.ru", "bank_mail"),
            13 => array("Lavapay", "bank_lava")
        );

        $payment_default = $array[$payment_num][0];
        $payment_field   = $array[$payment_num][1];

        if(!empty($payment_default) && !empty($payment_field) && isset($user_data->{$payment_field}))
            if($is_array == false)
                return $payment_default.' '.$user_data->{$payment_field};
            else
                return array('payment_default' => $payment_default,
                    'account_numder'  => $user_data->{$payment_field});

        return '';
    }

    public function getPay($id){
        return $this->own()->get_where('transactions', array('id' => $id))->row();
    }

    public function getMyPay($id){
        return $this->db
                ->where("(id_user = $this->id_user OR value = $this->id_user) AND id = $id")
                ->get('transactions')
                ->row();
    }

    /*
     *  Финансы
     */

    public function getMoneyA(){
        return $this->base_model->getMoney($this->id_user);
    }

    public function getBonusA(){
        return $this->base_model->getBonus($this->id_user);
    }

    public function getRealMoneyA(){
        return $this->base_model->getRealMoney($this->id_user);
    }

    public function isCanSendMoney($send_transaction, $payout = FALSE, $merchant = FALSE, $sendMoney = FALSE){
        $rating = $this->recalculateUserRating($send_transaction->own); // viewData()->accaunt_header;


        $this->load->model('users_model', 'users');
        $data_incame  = $this->users->getUserPureIncome($send_transaction->own, $send_transaction->pay_type);
        $credit_incam = (float) $data_incame[0];
        $this->load->model("transactions_model", "transactions");
        $archive_sum  = (float) $this->transactions->getArhiveSum($send_transaction->own, $send_transaction->pay_type);

        $obtaining = $rating['sum_partner_reword_by_bonus'][$send_transaction->pay_type]                            //заработаных денег
            + $credit_incam;
//                     - $rating['fee_or_fine'];
        //  if(50 > $obtaining) return "Не хватает заработанных денег";

        if(!$payout && $send_transaction->summa >= $rating['payout_limit_by_bonus'][$send_transaction->pay_type])
            return "Нет свободных средств"; //есть ли свободные средства

        $balans = ($rating['money_sum_add_funds_by_bonus'][$send_transaction->pay_type]                             //баланс положительный
            + $rating['money_sum_transfer_from_users_by_bonus'][$send_transaction->pay_type] + $rating['sum_partner_reword_by_bonus'][$send_transaction->pay_type] + $credit_incam + $archive_sum) - ($rating['money_sum_transfer_to_users_by_bonus'][$send_transaction->pay_type] + $rating['money_sum_withdrawal_by_bonus'][$send_transaction->pay_type] + $rating['money_sum_process_withdrawal_by_bonus'][$send_transaction->pay_type]); //+ $rating['fee_or_fine']
        switch($send_transaction->pay_type){
            case 2:
                $balans+=$rating['transfered_summ_from_bonus_5_to_2'];
                $balans-=$rating['transfered_summ_from_bonus_2_to_6'];
                break;
            case 5:
                $balans-=$rating['transfered_summ_from_bonus_5_to_2'];
                break;
            case 6:
                $balans+=$rating['transfered_summ_from_bonus_2_to_6'];
                break;
        }

        if($sendMoney)
            $balans = $rating['payout_limit_by_bonus'][$send_transaction->pay_type];
        if(!$payout && $send_transaction->summa > $balans)
            return "Сумма на вывод больше доступных средств";
        if(!$merchant && $payout && 0 > $balans)
            return "Баланс отрицателен (".$balans.')';

        $this->load->model('transactions_model', 'transactions');             //нет дуюляжей
        if($this->transactions->getDouble($send_transaction->own)) // || $this->transactions->getDoubleArhive($send_transaction->own)
            return "В истории ваших транзакций есть продублированные списания или пополнения";

        return true;
    }

    /**
     * Проверка возможности перевода сердств для модуля curreny_exchange, аналог функции isCanSendMoney
     * @param type $send_transaction
     * @param type $payout
     * @return string|boolean
     */
    public function recalculateUserRatingtest($_id = null, $date_end = null, $bonus = null, $date_start = null){
        if(!$_id)
            $_id = $this->id_user;
        if($_id === 0)
            return 0;
        if($this->users_rate_on && isset($this->users_rate[$_id]) && null == $date_end && null == $bonus && null == $date_start){
            return $this->users_rate[$_id];
        }


        //$this->load->model('testusers_model','testusers');
        //$test_users = $this->testusers->get_users_by_purpose('save_scores');
//        $need_calculate_id = $this->getNeedRecalculateId( $_id );
//        if( $_id == 500733 ) var_dump( $test_users);
        //если пусто - не нужно пересчитывать
//        if( in_array($_id, $test_users) &&
//            empty( $need_calculate_id ) )
//        {
        //var_dump($need_calculate_id);
//            $last_rating = $this->getLastUserRating( $_id );
//            if( !empty( $last_rating ) )
//            {
//                echo "return last--";
//                return $last_rating;
//            }
//        }
//        if( $_id == 500733 )
//        echo "calculating--";
        // <editor-fold defaultstate="collapsed" desc="load models">
        $this->load->model('partner_model', 'partner');
        $this->load->model('credits_model', 'credits');
        $this->load->model('users_model', 'users');
        // </editor-fold>

        $this->partner->setCurrentUserId($_id);
        $expected_partnership_income = $this->partner->getMySoonMoney(); //партнерская прибыль дату не учитывает


        $currency_exchange_scores = $this->currency_exchange_scores($_id);


        //SELECT * FROM `credits` WHERE `id_user`= AND `state`=2
        if(null !== $date_end)
            $this->db->where("date <= '$date_end'");
        if(null !== $date_start)
            $this->db->where("DATE(date) >= DATE('$date_start')");
        if(null !== $bonus){
            if($bonus == 2)
                $this->db->where_in("bonus", [0, 2]);
            else
                $this->db->where("bonus", $bonus);
        }
        $all_inquiries = $this->db->where('id_user', $_id)->get('credits')->result();

        //<editor-fold defaultstate="collapsed" desc="Объявление переменных">
        //TODO: бонус = 1 пересчитать в общие переменные
        $null_array_by_bonus = array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0);
        $null_array_keys     = array_keys($null_array_by_bonus);

        $cur_date_str           = date('Y-m-d', time());
        $cur_timestamp          = strtotime($cur_date_str);
        $current_garant_percent = 0.065;
        $max_loan               = 1900; //new1
        $max_rating             = 84;

        $total_active_blocked_own2     = 0;
        $total_active_usd2_fee_invests = 0;

        $my_investments_garant                  = 0; //сумма вложений по гаранту
        $my_investments_garant_by_bonus         = $null_array_by_bonus; //сумма вложений по гаранту
        $my_investments_garant_bonuses          = 0; //сумма вложений по гаранту бонусный
        $my_investments_garant_percent          = 0; //проценты по гаранту
        $my_investments_garant_percent_by_bonus = $null_array_by_bonus; //проценты по гаранту
        $my_investments_standart                = 0; //сумма вложений по гаранту
        $my_loans                               = 0;   //займы
        $my_loans_by_bonus                      = $null_array_by_bonus;   //займы
        $loans_garant_by_bonus                  = $null_array_by_bonus;   //займы ганарт

        $invests_showed_and_active_own_sum_by_bonus = $null_array_by_bonus;


        $c_creds_amount_invest        = 0; //размер средств на счете C-CREDS
        $c_creds_amount_invest_active = 0; //размер средств на счете C-CREDS
        /// type=2 state=2 bonus=4
//        $loans_standart_outsumm = 0;   //сумма всех полученных стандартных займов
//        $my_balance = 0;    //баланс
        $my_loans_percentage          = 0; //Проценты расход на весь срок
        $my_loans_percentage_by_bonus = $null_array_by_bonus; //Проценты расход на весь срок

        $my_loans_percentage_garant          = 0; //проценты на выплату по гаранту
        $my_loans_percentage_garant_by_bonus = $null_array_by_bonus; //проценты на выплату по гаранту

        $my_total_future_income_standart = 0;  //всего проценты по стандрант
        $my_total_future_income_garant   = 0;     //всего проценты по стандрант

        $my_future_income_standart = 0;  //перспективные проценты по стандрант
        $my_future_income_garant   = 0;  //перспективные проценты по гаранту

        $all_advanced_loans_out_summ               = 0; //Выставленные заявки на займ Гарант Деньги
        $all_posted_garant_loans_out_summ_by_bonus = $null_array_by_bonus; //Выставленные заявки на займ Гарант Деньги
        $all_advanced_invests_summ                 = 0; //Выставленные заявки на вклад Гарант Деньги
        $all_advanced_invests_summ_by_bonus        = $null_array_by_bonus; //Выставленные заявки на вклад Гарант Деньги

        $all_advanced_invests_bonuses_summ = 0; //Выставленные заявки на вклад Гарант Бонусы

        $all_advanced_standart_loans_out_summ        = 0;  //выставленные займы Стандарт
        $all_advanced_standart_invests_summ          = 0;    //выставленные заявки на вклад Стандарт
        $all_advanced_standart_invests_summ_by_bonus = $null_array_by_bonus;    //выставленные заявки на вклад Стандарт

        $invests_own_sum_by_bonus = $null_array_by_bonus;

//        $all_active_garant_loans_sum = 0; //сумма акивных займов гарант (без процентов)

        $total_garant_loans          = 0; //автивные заявки Гарант на Займ
        $total_garant_loans_by_bonus = $null_array_by_bonus; //автивные заявки Гарант на Займ

        $total_arbitrage           = 0; //сумма всех активных займов Арбитраж
        $total_arbitrage_by_bonus  = $null_array_by_bonus; //сумма всех активных займов Арбитраж
        $total_arbitrage2          = 0;
        $total_arbitrage3          = 0;
        $total_arbitrage4          = 0;
        $total_arbitrage5          = 0;
        $total_arbitrage2_by_bonus = $null_array_by_bonus;
        $total_arbitrage3_by_bonus = $null_array_by_bonus;
        $total_arbitrage4_by_bonus = $null_array_by_bonus;
        $total_arbitrage5_by_bonus = $null_array_by_bonus;

        $total_arbitrage_percentage          = 0; //проценты по займам Арбитраж
        $total_arbitrage_percentage_by_bonus = $null_array_by_bonus; //проценты по займам Арбитраж

        $arbitrage_collateral                 = 0;    //обеспечение по Арбтражу
        $overdraft                            = 0;
        $loans_standart_out_summ              = 0;
        $loans_standart_out_summ_by_bonus     = $null_array_by_bonus;
        $direct_collateral                    = 0;         //ожидающие вклады Директ
        $all_posted_garant_direct             = 0;          //выставленные директы
        $all_posted_garant_direct_by_bonus    = $null_array_by_bonus;          //выставленные директы
        $all_pending_garant_direct            = 0;
        $all_pending_garant_direct_by_bonus   = $null_array_by_bonus;
        $all_posted_standart_direct           = 0;        //выставленные директы
        $all_posted_standart_direct_by_bonus  = $null_array_by_bonus;        //выставленные директы
        $all_pending_standart_direct          = 0;       //в ожидани
        $all_pending_standart_direct_by_bonus = $null_array_by_bonus;       //в ожидани

        $all_active_garant_loans_out_summ          = 0;  //сумма out_summ всех активных займов Гарант
        $all_active_garant_loans_out_summ_by_bonus = $null_array_by_bonus;  //сумма out_summ всех активных займов Гарант
        $all_active_garant_loans_summa             = 0;     //сумма summa всех активных займов Гарант
        $all_active_garant_loans_summa_by_bonus    = $null_array_by_bonus;     //сумма summa всех активных займов Гарант

        $overdue_standart       = array();    //количество просроченных займов стандарт
        $overdue_standart_count = 0;    //количество просроченных займов стандарт
        $overdue_garant         = array();    //количество просроченных займов гарант
        $overdue_garant_count   = 0;    //количество просроченных займов гарант

        $overdue_garant_interest          = 0;    //проценты по просроченным займам гарант
        $overdue_garant_interest_by_bonus = $null_array_by_bonus;    //проценты по просроченным займам гарант
        $my_investments_garant_partner    = 0;

        $active_cards_invests         = 0;
        $active_cards_invests_outsumm = 0;
        $active_cards_garant_invests  = 0;

        $active_cards_credits         = 0;
        $active_cards_credits_outsumm = 0;
        $active_cards_garant_credits  = 0;

        $showed_cards_credits        = 0;
        $showed_cards_garant_credits = 0;
        $showed_cards_invests        = 0;
        $showed_cards_garant_invests = 0;

        $creds_amount_invest_by_bonus[2] = $creds_amount_invest_by_bonus[5] = 0;

        $arbitration_garant_active_summ          = 0;
        $arbitration_garant_active_summ_by_bonus = $null_array_by_bonus;
        $arbitration_active_summ                 = 0;
        $arbitration_active_summ_by_bonus        = $null_array_by_bonus;
        $arbitration_shown_summ                  = 0;
        $arbitration_shown_summ_by_bonus         = $null_array_by_bonus;
        $old_arbitration_active_summ             = 0;
        $old_arbitration_active_summ_by_bonus    = $null_array_by_bonus;
        $sum_loan_active_summ                    = 0;
        $sum_loan_active_summ_by_bonus           = $null_array_by_bonus;

        $loan_garant_active_summ = 0;
        $new_arbitr_date         = strtotime('2015-08-04 22:00:00'); //дата перехода на коэф 5

        $all_active_garant_loans_summa_new_by_bonus = $null_array_by_bonus;

        $loan_active_cnt                   = 0;
        $loan_active_cnt_by_bonus          = $null_array_by_bonus;
        $loan_active_summ                  = 0;
        $loan_active_summ_by_bonus         = $null_array_by_bonus;
        $loan_active_days_remains          = 0;
        $loan_active_days_remains_by_bonus = $null_array_by_bonus;

        $all_loans_active_summ_by_bonus = $null_array_by_bonus;

        $old_arbitration_active_summ_by_bonus = $null_array_by_bonus;

        $garant_issued   = 0;
        $garant_received = 0;

        $garant_issued_in_wait = 0;



        //</editor-fold>
        //тип = 1 - займ, 2 -  вклад
        $this->load->helper('admin');
        $total_grant_loan_new = 0;
        foreach($all_inquiries as $item){



            // передача рейтинга
            if($item->arbitration == 0 && $item->garant == 1 && $item->bonus == 9){
                if($item->state == 1)
                    $garant_issued_in_wait += $item->summa;
                if($item->state == 2){
                    if($item->type == 2 || $item->type == 3){
                        $garant_issued += $item->summa;
                    } elseif($item->type == 1){
                        $garant_received += $item->summa;
                    }
                }
            }

            // активные займы
            if($item->type == 1 && $item->state == 2 && $item->arbitration == 0)
                $all_loans_active_summ_by_bonus[$item->bonus] += $item->summa;
            if($item->type == 1 && $item->state == 2 && $item->arbitration == 0 && $item->garant == 1){
                $loan_active_summ += $item->summa;
                $loan_active_cnt++;
                $loan_active_summ_by_bonus[$item->bonus] += $item->summa;
                $loan_active_cnt_by_bonus[$item->bonus] ++;

                if(strtotime($item->out_time) > time()){
                    $loan_active_days_remains += floor(( strtotime($item->out_time) - time()) / 60 / 60 / 24) + 1;
                    $loan_active_days_remains_by_bonus[$item->bonus] += floor(( strtotime($item->out_time) - time()) / 60 / 60 / 24) + 1;
                }
            }

            if($item->garant == Base_model::CREDIT_GARANT_ON && $item->type == 1 && $item->state == 2 && $item->arbitration == 0)
                $loan_garant_active_summ += $item->summa;
            // активные арбитражи Гарант
            if($item->garant == Base_model::CREDIT_GARANT_ON && $item->type == 1 && $item->state == 2 && $item->arbitration == 1 && $item->blocked_money != '9'){
                $arbitration_garant_active_summ += $item->summa;
                $arbitration_garant_active_summ_by_bonus[$item->bonus] += $item->summa;
            }


            if($item->type == 1 && $item->state == 2 && $item->arbitration == 1 && $item->blocked_money != '9'){
                $old_arbitration_active_summ += $item->summa;
                $old_arbitration_active_summ_by_bonus[$item->bonus] += $item->summa;
            }

            if($item->type == 2 && ($item->state == 2 || $item->state == 1) && $item->sum_arbitration > 0 && $item->arbitration != 3 && $item->date > '2016-02-21' && $item->blocked_money != '9'){
                $arbitration_active_summ += $item->sum_arbitration;
                $arbitration_active_summ_by_bonus[$item->bonus] += $item->sum_arbitration;
            }
            if($item->type == 2 && ($item->state == 1) && $item->sum_arbitration > 0 && $item->date > '2016-02-21' && $item->blocked_money != '9'){
                $arbitration_shown_summ += $item->sum_arbitration;
                $arbitration_shown_summ_by_bonus[$item->bonus] += $item->sum_arbitration;
            }
            if($item->type == 2 && ($item->state == 2 || $item->state == 1) && $item->sum_loan > 0 && $item->blocked_money != '9'){
                $sum_loan_active_summ += $item->sum_loan;
                $sum_loan_active_summ_by_bonus[$item->bonus] += $item->sum_loan;
            }


            if($item->state == 1 && $item->overdraft == Base_model::CREDIT_OVERDRAFT_OFF){
                if($item->garant == 1){
                    if($item->type == 1){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_loans_out_summ += $item->out_summ;

                        $all_posted_garant_loans_out_summ_by_bonus[$item->bonus] += $item->out_summ;
                    }
                    else
                    if($item->type == 2){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_invests_summ += $item->summa;

                        $all_advanced_invests_summ_by_bonus[$item->bonus] += $item->summa;
                    }
                }else {
                    if($item->type == Base_model::CREDIT_TYPE_CREDIT){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_standart_loans_out_summ += $item->out_summ;
                    }else
                    if($item->type == Base_model::CREDIT_TYPE_INVEST){
                        if($item->bonus == Base_model::CREDIT_BONUS_OFF || $item->bonus == $bonus)
                            $all_advanced_standart_invests_summ += $item->summa;

                        $all_advanced_standart_invests_summ_by_bonus[$item->bonus] += $item->summa;
                    }
                }
            }


            // карточные активные вклады
            if($item->type == 2 && $item->bonus == 7){
                if($item->state == 2){
                    $active_cards_invests+=$item->summa;
                    $active_cards_invests_outsumm+=$item->out_summ;
                    if($item->garant == 1)
                        $active_cards_garant_invests += $item->summa;
                } elseif($item->state == 1){
                    $showed_cards_invests+=$item->summa;
                    if($item->garant == 1)
                        $showed_cards_garant_invests += $item->summa;
                }
            }
            // карточные активные займы и выставленные
            if($item->type == 1 && $item->bonus == 7){

                if($item->state == 2){
                    $active_cards_credits+=$item->summa;
                    $active_cards_credits_outsumm+=$item->out_summ;
                    if($item->garant == 1)
                        $active_cards_garant_credits += $item->summa;
                } elseif($item->state == 1){
                    $showed_cards_credits+=$item->summa;
                    if($item->garant == 1)
                        $showed_cards_garant_credits += $item->summa;
                }
            }



            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->bonus == 2 && $item->blocked_money == 66)
                $total_active_blocked_own2 += $item->summa;

            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->bonus == 7 && $item->blocked_money == 22)
                $total_active_usd2_fee_invests += $item->summa;




            if($item->state == 1 && $item->type == 2 && $item->bonus == 4){
                $c_creds_amount_invest+=$item->summa;
            }

            if($item->state == 1 && $item->type == 2 && ($item->bonus == 2 || $item->bonus == 5 || $item->bonus == 6)){
                $creds_amount_invest_by_bonus[$item->bonus] +=$item->summa;
            }

            if($item->state == 2 && $item->type == 2 && $item->sum_own > 0){
                $invests_own_sum_by_bonus[$item->bonus] +=$item->summa;
            }

            if(($item->state == 1 || $item->state == 2) && $item->type == 2 && $item->sum_own > 0){
                $invests_showed_and_active_own_sum_by_bonus[$item->bonus] +=$item->summa;
            }


            //директы
            if($item->direct == Credits_model::DIRECT_ON && $item->overdraft == Base_model::CREDIT_OVERDRAFT_OFF && $item->bonus == Base_model::CREDIT_BONUS_OFF
            ){
                //выставленные и в ожидании
                if($item->garant == Base_model::CREDIT_GARANT_ON){
//                    if ($item->type == 1)
//                        $all_posted_garant_direct += $item->out_summ;
//                    else

                    if($item->type == Base_model::CREDIT_TYPE_INVEST && $item->state == Base_model::CREDIT_STATUS_SHOWED){
                        $all_posted_garant_direct += $item->summa;
                        $all_posted_garant_direct_by_bonus[$item->bonus] += $item->summa;
                    }

                    if($item->type == Base_model::CREDIT_TYPE_INVEST && $item->state == Base_model::CREDIT_STATUS_WAITING){
                        $all_pending_garant_direct += $item->summa;
                        $all_pending_garant_direct_by_bonus[$item->bonus] += $item->summa;
                    }
                } else
                //выставленные
                if($item->garant == Base_model::CREDIT_GARANT_OFF){
                    if($item->state == Base_model::CREDIT_STATUS_SHOWED){
                        if($item->type == Base_model::CREDIT_TYPE_INVEST){
                            $all_posted_standart_direct += $item->summa;
                            $all_posted_standart_direct_by_bonus[$item->bonus] += $item->summa;
                        }
                    } else
                    if($item->state == Base_model::CREDIT_STATUS_WAITING){
                        if($item->type == Base_model::CREDIT_TYPE_INVEST){
                            $all_pending_standart_direct += $item->summa;
                            $all_pending_standart_direct_by_bonus[$item->bonus] += $item->summa;
                        }
                    }
                }
            }


            if($item->state == Base_model::CREDIT_STATUS_ACTIVE && $item->type == Base_model::CREDIT_TYPE_CREDIT && $item->garant == Base_model::CREDIT_GARANT_ON){
                //просрочен
                if(strtotime($item->out_time.' 23:59:59') < time()){
                    $actual_term = calculate_debit_time_now($item->date);
                    $out_summ    = credit_summ($item->percent, $item->summa, $actual_term);

                    $overdue_garant_interest += $out_summ - $item->out_summ;
                    $overdue_garant_interest_by_bonus[$item->bonus] += $out_summ - $item->out_summ;
                } else {//не просрочен
                    $all_active_garant_loans_out_summ += $item->out_summ;
                    $all_active_garant_loans_out_summ_by_bonus[$item->bonus] += $item->out_summ;
                    $all_active_garant_loans_summa += $item->summa;
                    $all_active_garant_loans_summa_by_bonus[$item->bonus] += $item->summa;
                }

                if(strtotime($item->date) > $new_arbitr_date){
                    $all_active_garant_loans_summa_new += $item->summa;
                    $all_active_garant_loans_summa_new_by_bonus[$item->bonus] += $item->summa;
                    //когда использовался коэф = 10
                    if(strtotime($item->date) < strtotime('2015-12-04 00:00:00') && strtotime($item->date) > strtotime('2015-11-28 00:00:00')){
                        //    $all_active_garant_loans_summa_new += $item->summa;
                        //   $all_active_garant_loans_summa_new_by_bonus[$item->bonus] += $item->summa;
                    }
                }

                $total_grant_loan_new += $item->summa;
            }

            if($item->state == 1 && $item->type == 2 && $item->garant == 1 && $item->bonus == 1)
                $all_advanced_invests_bonuses_summ += $item->summa;

            if($item->state == 2 && $item->type == 1 && $item->garant == 1){
                $total_garant_loans += $item->out_summ;
                $total_garant_loans_by_bonus[$item->bonus] += $item->out_summ;

                if(strtotime($item->out_time).' 23:59:59' < time() && $item->previous_debit == 0){
                    $overdue_garant_count++;
                    $overdue_garant[] = $item->id;
                }
            }

            //овердрафт
            if($item->state == 2 && $item->type == 2 && $item->garant == 1 && $item->overdraft == 1 && $item->direct = Credits_model::DIRECT_OFF){
                $overdraft += $item->summa;
            }
            if($item->state == 2 && $item->type == 2 && $item->garant == 1 && $item->garant_percent == 2){
                $my_investments_garant_partner += $item->summa;
            }
            if($item->state != 2) //только активный
                continue;

            if($item->type == 2){//только вклады
                if($item->garant == 1){
                    if($item->bonus == 1)
                        $my_investments_garant_bonuses += $item->summa;
                    $my_total_future_income_garant += $item->income;
                    $my_future_income_garant += $item->income * (1.0 - $this->garantPercent($item->time) / 100.0);
                }
                else {
                    $my_total_future_income_standart += $item->income;
                    $my_future_income_standart += $item->income * (1.0 - $this->standartPercent($item->time) / 100.0);
                }

                if($item->garant == 0 && $item->bonus == 0)
                    $my_investments_standart += $item->summa;

                if($item->garant == 0)
                    continue;

                $date           = explode(' ', $item->date_kontract);
                $item_timestamp = strtotime($date[0].' 00:00:00');
                $days           = floor(($cur_timestamp - $item_timestamp) / 3600 / 24);

                $my_investments_garant_percent += $item->summa * $days;
                $my_investments_garant_percent_by_bonus[$item->bonus] += $item->summa * $days;

                if(!($item->bonus == 0 || $item->bonus == 2 || $item->bonus == 5 || $item->bonus == 6))
                    continue; //не учитываем бонусы и партнерские вклады
                $my_investments_garant += $item->summa;
                $my_investments_garant_by_bonus[$item->bonus] += $item->summa;


//            } else if ($item->type == 1 ){//все займы
            } else if($item->type == 1 && $item->arbitration == 0 && $item->bonus != 9){//все займы za isk bonus 9
                $my_loans += $item->summa;
                $my_loans_by_bonus[$item->bonus] += $item->summa;

                $my_loans_percentage += $item->summa * $item->time * $item->percent; //++
                $my_loans_percentage_by_bonus[$item->bonus] += $item->summa * $item->time * $item->percent;

                if($item->garant == 1){


                    $my_loans_percentage_garant += $item->summa * $item->time * $item->percent;
                    $my_loans_percentage_garant_by_bonus[$item->bonus] = $item->summa * $item->time * $item->percent;
                }

                if($item->garant == 0){
                    $loans_standart_out_summ += floatval($item->out_summ);
                    $loans_standart_out_summ_by_bonus[$item->bonus] += floatval($item->out_summ);

                    if(strtotime($item->out_time.' 23:59:59') < time() && Base_model::CREDIT_STATUS_ACTIVE == $item->state){
                        $overdue_standart_count++;
                        $overdue_standart[] = $item->id;
                    }
                }
            } else if($item->type == 1 && $item->arbitration == Base_model::CREDIT_ARBITRATION_ON && $item->blocked_money != '9'){//все займы
//                $total_arbitrage += $item->summa;
                if(strtotime($item->date) <= $new_arbitr_date){
                    $total_arbitrage2 += $item->summa;
                    $total_arbitrage2_by_bonus[$item->bonus] += $item->summa;
                } else {
                    //   $total_arbitrage4_by_bonus[$item->bonus] += $item->summa;
                    if(strtotime($item->date) < strtotime('2015-12-04 00:00:00') && strtotime($item->date) > strtotime('2015-11-28 00:00:00')){
                        //     $total_arbitrage3 -= $item->summa /2; // ubral /2
                        //    $total_arbitrage3_by_bonus[$item->bonus] -= $item->summa /2;                 // ubral /2
                        //$total_arbitrage4_by_bonus[$item->bonus] -= $item->summa /2;                 // ubral /2
                        $total_arbitrage4 += $item->summa;
                        $total_arbitrage4_by_bonus[$item->bonus] += $item->summa;
                    } elseif(strtotime($item->date) > strtotime('2015-12-14 00:00:00')){
                        $total_arbitrage5 += $item->summa;
                        $total_arbitrage5_by_bonus[$item->bonus] += $item->summa;
                    } else {
                        $total_arbitrage3 += $item->summa;
                        $total_arbitrage3_by_bonus[$item->bonus] += $item->summa;
                    }
                }
                $total_arbitrage_percentage += $item->summa * $item->time * $item->percent;
                $total_arbitrage_percentage_by_bonus[$item->bonus] += $item->summa * $item->time * $item->percent;
            }
        }


        $total_arbitrage = $total_arbitrage2 + $total_arbitrage3 + $total_arbitrage4 + $total_arbitrage5; //++

        $my_investments_garant_percent *= $current_garant_percent / 100; //++

        $my_loans_percentage /= 100; //++
        $my_loans_percentage_garant /= 100;
        $total_arbitrage_percentage /= 100; //++
        $my_loans_percentage += $total_arbitrage_percentage; //++
        //$my_investments_garant -= $garant_issued;
        //$my_investments_garant += $garant_received;
        //разделение по счетам
        foreach($null_array_keys as $b){
            //if ( $b == 6){
            //$my_investments_garant_by_bonus[$b] -= $garant_issued;
            //$my_investments_garant_by_bonus[$b] += $garant_received;
            //}


            $total_arbitrage_by_bonus[$b] = //++
                $total_arbitrage2_by_bonus[$b] + //++
                $total_arbitrage3_by_bonus[$b] +
                $total_arbitrage4_by_bonus[$b] +
                $total_arbitrage5_by_bonus[$b]; //++
            /* echo "[$b]{$date_start}{$total_arbitrage_by_bonus[$b]} = //++
              {$total_arbitrage2_by_bonus[$b]} 2+ //++
              {$total_arbitrage3_by_bonus[$b]} 3+
              {$total_arbitrage4_by_bonus[$b]} 4+
              {$total_arbitrage5_by_bonus[$b]} 5;//++<br>"; */

            $my_investments_garant_percent_by_bonus[$b] *= $current_garant_percent / 100; //++

            $my_loans_percentage_by_bonus[$b] /= 100;
            $my_loans_percentage_garant_by_bonus[$b] /= 100;
            $total_arbitrage_percentage_by_bonus[$b] /= 100;

            $my_loans_percentage_by_bonus[$b] += $total_arbitrage_percentage_by_bonus[$b];
        }

        $this->load->model('transactions_model', 'transactions');
        if(null !== $date_end)
            $this->db->where("date <= '$date_end'");
        if(null !== $bonus)
            $this->db->where("bonus", $bonus);
        $transactions              = $this->db->where(array('id_user' => $_id))
            ->get('transactions')
            ->result();
        //<editor-fold defaultstate="collapsed" desc="Объявление переменных">
        $my_income_money           = 0;
        $my_income_money_by_bonus  = $null_array_by_bonus;
        $my_outcome_money          = 0;
        $my_outcome_money_by_bonus = $null_array_by_bonus;

        $my_income_money_acrhive  = 0;
        $my_outcome_money_acrhive = 0;

        $my_income_bonus_money              = $null_array_by_bonus;
        $my_outcome_bonus_money             = $null_array_by_bonus;
        $money_bonus_sum_process_withdrawal = $null_array_by_bonus;

        $item_summa       = 0;
        $item_status      = 0;
        $item_note        = '';
        $item_method      = '';
        $my_bonuses       = 0;
        $my_partner_funds = 0;

        //my add and sub money

        $money_sum_add_funds          = 0;     //зачисление на счет
        $money_sum_add_funds_by_bonus = $null_array_by_bonus;     //зачисление на счет

        $money_sum_add_card          = 0;     //зачисление с карты
        $money_sum_add_card_by_bonus = $null_array_by_bonus;     //зачисление с карты

        $money_sum_withdrawal                   = 0;     //сумма вывода
        $money_sum_withdrawal_by_bonus          = $null_array_by_bonus;     //сумма вывода
        $money_sum_transfer_to_users            = 0;     //сумма перевода другим
        $money_sum_transfer_to_users_by_bonus   = $null_array_by_bonus;     //сумма перевода другим
        $money_sum_transfer_from_users          = 0;   //от других
        $money_sum_transfer_from_users_by_bonus = $null_array_by_bonus;   //от других
        $money_sum_transfer_from_exch           = 0; //сумма перевода от обменников
        $money_sum_transfer_from_exch_exwt      = 0;
        $money_sum_transfer_to_exch             = 0; //сумма перевода на обменников
        $sum_partner_reword                     = 0;         //партнерские
        $sum_partner_reword_by_bonus            = $null_array_by_bonus;
        $money_sum_process_withdrawal           = 0;
        $money_sum_process_withdrawal_by_bonus  = $null_array_by_bonus;

        $money_sum_transfer_to_merchant          = 0;
        $money_sum_transfer_to_merchant_by_bonus = $null_array_by_bonus;
        $income_merchant_send                    = 0;
        $income_merchant_send_by_bonus           = $null_array_by_bonus;
        $income_merchant_return                  = 0;
        $outcome_merchant_send                   = 0;
        $outcome_merchant_send_by_bonus          = $null_array_by_bonus;
        $outcome_merchant_return                 = 0;
        $outcome_merchant_return_by_bonus        = $null_array_by_bonus;

        $income_internal_sends  = 0;
        $outcome_internal_sends = 0;

        $bonus_earned_in  = 0;
        $bonus_earned_out = 0;
        $bonus_earned     = 0;

        $bonus_earned_in_by_bonus                  = $null_array_by_bonus;
        $bonus_earned_out_by_bonus                 = $null_array_by_bonus;
        $bonus_earned_by_bonus                     = $null_array_by_bonus;
        $income_merchant_send_by_bonus             = $null_array_by_bonus;
        $processing_internal_transfer_sum_by_bonus = $null_array_by_bonus;
        $max_garant_vklad_real_available_by_bonus  = $null_array_by_bonus;

        $partner_unic_id_count = 0;         //Количество партнерских с разных ID
        $diversification_coeff = 15;        //Коэфицент диверсификации

        $partner_week_contribution  = 0;     //Сумма партнерских за неделю
        $partner_contribution_count = 0;    //Количество партнерских отчислений

        $average_partner_contribution = 0;  //Средняя сумма партнерского отчисления

        $diversification_degree          = 0; //Степень Диверсификации
        $partner_network_valuation_coeff = 0; //Коэфицент оценки партнерской сети
//        $new_max_loan_available = 0;    //Новый кредитный лимит
        $fee_or_fine                     = 0; //всякие отчисления за смс или что-то еще штрафы и т.п.

        $unic_partners_ids                    = array();
        $total_processing_payout              = 0; //сумма заявкок на вывод
        $total_processing_payout_sum_by_bonus = $null_array_by_bonus; //сумма заявкок на вывод
        $total_processing_payout_fee_by_bonus = $null_array_by_bonus; //сумма комиссий заявкок на вывод

        $p2p_money_sum_transfer_to_users            = 0;
        $p2p_money_sum_transfer_to_users_by_bonus   = $null_array_by_bonus;
        $p2p_money_sum_transfer_from_users          = 0;
        $p2p_money_sum_transfer_from_users_by_bonus = $null_array_by_bonus;


        $arbitr_in             = 0;
        $arbitr_out            = 0;
        $arbitr_inout          = 0;
        $arbitr_in_by_bonus    = $null_array_by_bonus;
        $arbitr_out_by_bonus   = $null_array_by_bonus;
        $arbitr_inout_by_bonus = $null_array_by_bonus;

        $transfered_summ_from_bonus_2_to_6 = 0;
        $money_own_from_2_to_6             = 0;

        $pcreds_inout_after_0112             = 0;
        $pcreds_income_after_0112            = 0;
        $pcreds_outcome_after_0112           = 0;
        $pcreds_in_payout_process_after_0112 = 0;







        $last_week_date = strtotime(date('Y-m-d 00:00:00')) - 7 * 24 * 3600;

        $last_month_date                   = strtotime(date('Y-m-d 00:00:00')) - 30 * 24 * 3600;
        $month_partner_unic_id_count       = 0;   //Количество партнерских с разных ID
        $month_unic_partners_ids           = array();
        $money_sum_process_withdrawal_bank = 0;

        $exchange_users = getExchangeUsers(); //обменники

        $c_creds_amount         = 0; //размер средств на счете C-CREDS
        $c_creds_amount_process = 0; //размер средств на счете C-CREDS на проверке у операторов

        $my_partner_funds_process          = 0; //партнерские на проверке у операторов
        $max_garant_vklad_real_available   = 0; // сколько можно выдать вклад гарант на реальные деньги
        $money_sum_withdrawal_minus_type65 = 0;

        $transfered_summ_from_bonus_5_to_2 = 0;


        $money_in_after_26                    = 0;
        $money_in_after_26_by_bonus           = $null_array_by_bonus;
        $money_out_after_26_only_out          = 0;
        $money_out_after_26_only_out_by_bonus = $null_array_by_bonus;
        $money_inout_after_26                 = 0;
        $money_inout_after_26_by_bonus        = $null_array_by_bonus;


        $active_status = array(Base_model::TRANSACTION_STATUS_IN_PROCESS,
            Base_model::TRANSACTION_STATUS_VEVERIFYED,
            Base_model::TRANSACTION_STATUS_VEVERIFY_SS,
            Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK);

        $transactions_pending_statuses = array(Base_model::TRANSACTION_STATUS_IN_PROCESS,
            Base_model::TRANSACTION_STATUS_PENDING,
            Base_model::TRANSACTION_STATUS_VEVERIFYED,
            Base_model::TRANSACTION_STATUS_VEVERIFY_SS,
            Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK);

        $partner_type     = array(
            Transactions_model::TYPE_PARENT_INCOME,
            Transactions_model::TYPE_VOLUNTEER_INCOME
        );
        $send_money_type  = array(
            Transactions_model::TYPE_SEND_MONEY,
            Transactions_model::TYPE_SEND_MONEY_CONFERM,
            Transactions_model::TRANSACTION_TYPE_USER_SEND,
            Transactions_model::TYPE_SEND_MONEY_P2P,
        );
        $fee_or_fine_type = array(
            Transactions_model::TYPE_EXPENSE_SMS,
            Transactions_model::TYPE_EXPENSE_FINE,
            Transactions_model::TYPE_EXPENSE_OUTFEE,
            Transactions_model::TYPE_EXPENSE_OVERDRAFT,
            Transactions_model::TYPE_EXPENSE_MERCHANT,
            Transactions_model::TYPE_EXPENSE_INFEE,
            Transactions_model::TYPE_EXPENSE_P2P_FEE
        );

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Расчет по транзакциям">
        foreach($transactions as $item){

            $item_summa  = $item->summa;
            $item_status = $item->status;
            $item_note   = $item->note;
            $item_method = $item->metod;

            if($item->type == Transactions_model::TYPE_ARCHIVE && $item->value == Transactions_model::TYPE_ARCHIVE)
                if($item->status == Base_model::TRANSACTION_STATUS_RECEIVED)
                    $my_income_money_acrhive += $item_summa;
                elseif($item->status == Base_model::TRANSACTION_STATUS_REMOVED)
                    $my_outcome_money_acrhive += $item_summa;

            if(Base_model::TRANSACTION_STATUS_IN_PROCESS == $item->status ||
                Base_model::TRANSACTION_STATUS_VEVERIFYED == $item->status || Base_model::TRANSACTION_STATUS_VEVERIFY_SS == $item->status ||
                Base_model::TRANSACTION_STATUS_IN_PROCESS_BANK == $item->status){
                $total_processing_payout += $item->summa;
            }

            if($item->metod == 'out' && in_array($item->status, [4, 9, 10, 11])){
                $total_processing_payout_sum_by_bonus[$item->bonus] += $item->summa;
                $total_processing_payout_fee_by_bonus[$item->bonus] += $this->get_payout_fee_by_item($item); //++

                if($item->bonus == 3 && strtotime($item->date) > strtotime('2015-12-01 00:00:00'))
                    $pcreds_in_payout_process_after_0112 += $item->summa;
            }

            if($item->metod == 'wt' && in_array($item->type, [74, 75]) && in_array($item->status, [4, 9, 10])){
                $processing_internal_transfer_sum_by_bonus[$item->bonus] += $item->summa; //++
            }

            if($item_status == 1){//пополнение
                $my_income_money_by_bonus[$item->bonus] += $item_summa;
                if($item->type == 40){
                    $income_merchant_send += $item_summa;
                    $income_merchant_send_by_bonus[$item->bonus] += $item_summa;
                }
                if($item->type == 18){
                    $arbitr_in += $item_summa;
                    $arbitr_in_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->bonus == 3 && in_array($item->type, [30, 31]) && strtotime($item->date) > strtotime('2015-12-01 00:00:00')){
                    $pcreds_income_after_0112 +=$item_summa;
                }

                if($item->bonus == 6 && ($item->type == 83 || $item->type == 84)){
                    $transfered_summ_from_bonus_2_to_6 += $item_summa;
                    if($item->type == 83)
                        $money_own_from_2_to_6 += $item_summa;
                }

                if($item->type == 41)
                    $income_merchant_return += $item_summa;

                if($item->type == Transactions_model::TYPE_SEND_MONEY_P2P){
                    $p2p_money_sum_transfer_from_users += $item_summa;
                    $p2p_money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                }

                if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)){
                    $money_sum_transfer_from_users += $item_summa;
                    $money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 16 && $item->note_admin == 'bonus'){
                    $bonus_earned_in += $item->summa;
                    $bonus_earned_in_by_bonus[$item->bonus] += $item->summa;
                }
            } else if($item_status == 3){//снятие
                $my_outcome_money_by_bonus[$item->bonus] += $item_summa;
                if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)){
                    $money_sum_transfer_to_users += $item_summa;
                    $money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == Transactions_model::TYPE_SEND_MONEY_P2P){
                    $p2p_money_sum_transfer_to_users += $item_summa;
                    $p2p_money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->bonus == 3 && !in_array($item->type, [25, 26]) && strtotime($item->date) > strtotime('2015-12-01 00:00:00')){
                    $pcreds_outcome_after_0112 +=$item_summa;
                }


                if($item_method == 'out'){
                    $money_sum_withdrawal += $item_summa;
                    $money_sum_withdrawal_by_bonus[$item->bonus] += $item_summa;
                    if($item->date > strtotime('2015-11-26 00:00:00')){
                        $money_out_after_26_only_out += $item_summa;
                        $money_out_after_26_only_out_by_bonus[$item->bonus] += $item_summa;
                    }
                }

                if($item->type == 19){
                    $arbitr_out += $item_summa;
                    $arbitr_out_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 25 && $item->note_admin == 'bonus'){
                    $bonus_earned_out += $item->summa;
                    $bonus_earned_out_by_bonus[$item->bonus] += $item->summa;
                }


                if($item->type == 40){
                    $money_sum_transfer_to_merchant += $item_summa;
                    $money_sum_transfer_to_merchant_by_bonus[$item->bonus] += $item_summa;
                    $outcome_merchant_send += $item_summa;
                    $outcome_merchant_send_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 41){
                    $outcome_merchant_return += $item_summa;
                    $outcome_merchant_return_by_bonus[$item->bonus] += $item_summa;
                }

                if($item->type == 98 || $item->type == 99)
                    $outcome_internal_sends += $item_summa;
            }

            if($item->bonus == 2 && $item->type == 81)
                $transfered_summ_from_bonus_5_to_2 += $item_summa;


            if($item->bonus == Base_model::TRANSACTION_BONUS_PARTNER){
                if($item_status == 1)//пополнение
                    $my_partner_funds += $item_summa;
                else if($item_status == 3)//снятие
                    $my_partner_funds -= $item_summa;
                else if(in_array($item_status, $active_status))
                    $my_partner_funds_process += $item_summa;
            } else if($item->bonus == 1){
                if($item_status == 1)//пополнение
                    $my_bonuses += $item_summa;
                else if($item_status == 3)//снятие
                    $my_bonuses -= $item_summa;
            } else if($item->bonus == Base_model::TRANSACTION_BONUS_CREDS_CASH){
                if($item_status == 1)//пополнение
                    $c_creds_amount += $item_summa;
                else if($item_status == 3)//снятие
                    $c_creds_amount -= $item_summa;
                else if(in_array($item_status, $active_status))
                    $c_creds_amount_process += $item_summa;
            }else {
                if($item_status == 1){//пополнение
                    $my_income_money += $item_summa;
                    $my_income_bonus_money[$item->bonus] += $item_summa;


                    /*
                      if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value)) {
                      $money_sum_transfer_from_users += $item_summa;
                      $money_sum_transfer_from_users_by_bonus[$item->bonus] += $item_summa;
                      } */




                    $matches = array();
                    if(( in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type && Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value ) ) &&
                        ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], $exchange_users) )
                    ){
                        $money_sum_transfer_from_exch += $item_summa;
                    }


                    if($item->type == 74 && $item->bonus == 5 && ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], [46504049]))){
                        $money_sum_transfer_from_exch_exwt += $item_summa;
                    }

                    if(in_array($item->type, $partner_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_PARENT_INCOME == $item->value)){
                        $sum_partner_reword += $item_summa;
                        $sum_partner_reword_by_bonus[$item->bonus] += $item_summa;
                    }
                    if($item_method != 'wt' && $item_method != 'arbitr'){
                        $money_sum_add_funds += $item_summa;
                        $money_sum_add_funds_by_bonus[$item->bonus] += $item_summa;
                        if($item_method == 'wtcard'){
                            $money_sum_add_card += $item_summa;
                            $money_sum_add_card_by_bonus[$item->bonus] += $item_summa;
                        }
                        if(strtotime($item->date) > strtotime('2015-11-26 00:00:00')){
                            $money_in_after_26 += $item_summa;
                            $money_in_after_26_by_bonus[$item->bonus] += $item_summa;
                        }
                    }

                    if($last_week_date <= strtotime($item->date) && preg_match('/Партнерское вознаграждение по вкладу/', $item->note)){
                        $id_user                     = $this->users->getPartnerIdFromNote($item->note);
                        //Количество партнерских с разных ID
                        if(!empty($id_user))
                            if(!isset($unic_partners_ids[$id_user]))
                                $unic_partners_ids[$id_user] = 1;
                            else
                                $unic_partners_ids[$id_user] ++;

                        //Сумма партнерских за неделю
                        $partner_week_contribution += $item_summa;

                        //Количество партнерских отчислений
                        $partner_contribution_count++;
                    }

                    /* if ( $item->type == 40){
                      $income_merchant_send += $item_summa;
                      $income_merchant_send_by_bonus[$item->bonus] += $item_summa;
                      }
                      if ( $item->type == 41)
                      $income_merchant_return += $item_summa; */

                    if($item->type == 98 || $item->type == 99)
                        $income_internal_sends += $item_summa;;


                    if($last_month_date <= strtotime($item->date) && preg_match('/Партнерское вознаграждение по вкладу/', $item->note)){
                        $id_user                           = $this->users->getPartnerIdFromNote($item->note);
                        //Количество партнерских с разных ID
                        if(!empty($id_user))
                            if(!isset($month_unic_partners_ids[$id_user]))
                                $month_unic_partners_ids[$id_user] = 1;
                            else
                                $month_unic_partners_ids[$id_user] ++;
                    }
                }
                else if($item_status == 3){//снятие
                    $my_outcome_money += $item_summa;
                    $my_outcome_bonus_money[$item->bonus] += $item_summa;


                    /*
                      if ( $item->type == Transactions_model::TYPE_SEND_MONEY_P2P ){
                      $p2p_money_sum_transfer_to_users += $item_summa;
                      $p2p_money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                      }
                     */

                    /* if(in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value))
                      {
                      $money_sum_transfer_to_users += $item_summa;
                      $money_sum_transfer_to_users_by_bonus[$item->bonus] += $item_summa;
                      } */

                    if(in_array($item->type, $fee_or_fine_type) || (Transactions_model::TYPE_ARCHIVE == $item->type AND Transactions_model::VALUE_ARCHIVE_FEE_OR_FINE == $item->value))
                        $fee_or_fine += $item_summa;

                    if($item_method == 'out' && $item->type != 65)
                        $money_sum_withdrawal_minus_type65 += $item_summa;


                    $matches = array();
                    if(( in_array($item->type, $send_money_type) || (Transactions_model::TYPE_ARCHIVE == $item->type &&
                        Transactions_model::VALUE_ARCHIVE_SEND_MONEY == $item->value ) ) &&
                        ( preg_match_all('/№(\d*)/', $item->note, $matches) > 0 &&
                        isset($matches[1]) && isset($matches[1][0]) && in_array($matches[1][0], $exchange_users) )
                    ){
                        $money_sum_transfer_to_exch += $item_summa;
                    }

                    /*
                      if($item->type == 40){
                      $money_sum_transfer_to_merchant += $item_summa;
                      $money_sum_transfer_to_merchant_by_bonus[ $item->bonus ] += $item_summa;
                      $outcome_merchant_send += $item_summa;
                      $outcome_merchant_send_by_bonus[$item->bonus] += $item_summa;
                      }

                      if ( $item->type == 41)
                      $outcome_merchant_return += $item_summa;

                      if ($item->type == 98 || $item->type == 99)
                      $outcome_internal_sends += $item_summa;
                     */
                } else if(in_array($item_status, $active_status)){
                    $money_sum_process_withdrawal += $item_summa;
                    $money_sum_process_withdrawal_by_bonus[$item->bonus] += $item_summa;
                    $money_bonus_sum_process_withdrawal[$item->bonus] += $item_summa;
                    //сумма на вывод по банку
                    if(FALSE !== strpos($item_method, 'bank'))
                        $money_sum_process_withdrawal_bank += $item_summa;
                    //if ( $item_status == 11) $money_sum_process_withdrawal_bank += $item_summa;
                }
            }
        }

        //</editor-fold>


        $c_creds_total      = $c_creds_amount - $c_creds_amount_process - $c_creds_amount_invest;
        $c_creds_total_info = "c_creds_amount - c_creds_amount_process - c_creds_amount_invest = $c_creds_amount - $c_creds_amount_process - $c_creds_amount_invest = $c_creds_total";


        $pcreds_inout_after_0112 = $pcreds_income_after_0112 - $pcreds_outcome_after_0112;


        $my_partner_funds      = $my_partner_total      = $my_partner_funds - $my_partner_funds_process;
        $my_partner_funds_info = $my_partner_total_info = "my_partner_funds - my_partner_funds_process = $my_partner_funds - $my_partner_funds_process = $my_partner_funds";

        $partner_unic_id_count       = count($unic_partners_ids);
        $month_partner_unic_id_count = count($month_unic_partners_ids);

        if($partner_contribution_count > $diversification_coeff){
            $partner_temp = $partner_contribution_count;
        } else {
            $partner_temp = $diversification_coeff;
        }
        //Средняя сумма партнерского отчисления
        if($partner_contribution_count != 0)
            $average_partner_contribution = $partner_week_contribution / $partner_temp;

        //Степень Диверсификации
        if($diversification_coeff != 0)
            $diversification_degree = $partner_unic_id_count / $diversification_coeff;


        #Коэфицент оценки партнерской сети
        $partner_network_valuation_coeff = $average_partner_contribution * $diversification_degree;

        #Коэф соц интеграции
        $social_integration_value = 0;

        $ln      = strlen($_id);
        $_id_str = (string) $_id;
        $sp_l    = intval(substr($_id_str, $ln - 2, 1));
        $sp_f    = intval(substr($_id_str, 0, 1));

        //дробная часть
        $fraction_diversification_coeff = $diversification_degree - floor($diversification_degree);
        $integer_diversification_coeff  = 0.72 + 0.01 * $sp_l;

        //Если дробная_часть( Степень Диверсификации ) > 0.50
        if($fraction_diversification_coeff >= 0.5){
            $integer_diversification_coeff = 0.15 + 0.01 * $sp_f;
        }
        //        (0 + дробная_часть( Степень Диверсификации ) ) * Коэфицент оценки  партнерской сети * 100
        $social_integration_value = ( $integer_diversification_coeff + $fraction_diversification_coeff ) * $partner_network_valuation_coeff * 100;

        //качество партнерской сети
        $partner_network_value = $partner_network_valuation_coeff * 100;

        //old
        $all_liabilities = $my_loans + $my_loans_percentage + $total_arbitrage + $overdue_garant_interest; //++

        $all_liabilities_info = "my_loans + my_loans_percentage  + total_arbitrage + overdue_garant_interest =  $my_loans + $my_loans_percentage  + $total_arbitrage + $overdue_garant_interest = $all_liabilities_info";

//        $arbitrage_collateral = $total_arbitrage / 3;
        $arbitrage_collateral2 = $total_arbitrage2 / 3; //++
        $arbitrage_collateral3 = $total_arbitrage3 / 5; //++
        $arbitrage_collateral  = $total_arbitrage2 / 3 + $total_arbitrage3 / 5; //++


        $payment_account = $my_income_money - $my_outcome_money; //++

        $payment_account_info = "my_income_money - my_outcome_money = $my_income_money - $my_outcome_money = $payment_account";


        // разбиваем счет по бунусам 2 и 5
        $payment_bonus_account[2] = $my_income_bonus_money[2] - $my_outcome_bonus_money[2];
        $payment_bonus_account[5] = $my_income_bonus_money[5] - $my_outcome_bonus_money[5];
        $payment_bonus_account[6] = $my_income_bonus_money[6] - $my_outcome_bonus_money[6];


        $money_inout_after_26 = $money_in_after_26 - $money_out_after_26_only_out;
        if($money_inout_after_26 > $loan_active_summ)
            $money_inout_after_26 = $loan_active_summ;




        $payment_account_by_bonus       = $null_array_by_bonus;
        $arbitrage_collateral2_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral3_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral4_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral5_by_bonus = $null_array_by_bonus;
        $arbitrage_collateral_by_bonus  = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $arbitrage_collateral2_by_bonus[$b] = $total_arbitrage2_by_bonus[$b] / 3; //++
            $arbitrage_collateral3_by_bonus[$b] = $total_arbitrage3_by_bonus[$b] / 5; //++
            $arbitrage_collateral4_by_bonus[$b] = $total_arbitrage4_by_bonus[$b] / 10; //++
            $arbitrage_collateral5_by_bonus[$b] = $total_arbitrage5_by_bonus[$b] / 3; //++



            $money_inout_after_26_by_bonus[$b] = $money_in_after_26_by_bonus[$b] - $money_out_after_26_only_out_by_bonus[$b];

            if($b != 6 && $money_inout_after_26_by_bonus[$b] > $loan_active_summ_by_bonus[$b])
                $money_inout_after_26_by_bonus[$b] = $loan_active_summ_by_bonus[$b];

            $bonus_earned_by_bonus[$b] = $bonus_earned_in_by_bonus[$b] - $bonus_earned_out_by_bonus[$b];

            $arbitrage_collateral_by_bonus[$b] = $total_arbitrage2_by_bonus[$b] / 3 + $total_arbitrage3_by_bonus[$b] / 5 + $total_arbitrage4_by_bonus[$b] / 10 + $total_arbitrage5_by_bonus[$b] / 3; //++

            $all_liabilities_by_bonus[$b] = $my_loans_by_bonus[$b] + //++
                $my_loans_percentage_by_bonus[$b] + //++
                $total_arbitrage_by_bonus[$b] + //++
                $arbitration_active_summ_by_bonus[$b] -
                $arbitration_shown_summ_by_bonus[$b] +
                $overdue_garant_interest_by_bonus[$b]; //++



            $payment_account_by_bonus[$b] = //++
                $my_income_money_by_bonus[$b] //++
                - $my_outcome_money_by_bonus[$b]; //++


            /* echo "payment_account_by_bonus[$b] = //++
              my_income_money_by_bonus[$b] //++
              - my_outcome_money_by_bonus[$b];//++
              {$payment_account_by_bonus[$b]} = //++
              {$my_income_money_by_bonus[$b]} //++
              - {$my_outcome_money_by_bonus[$b]};
              ";
             */


            $all_investments_by_bonus[$b] = $my_investments_garant_percent_by_bonus[$b] + //++
                $my_investments_garant_by_bonus[$b] + //++
                $payment_account_by_bonus[$b]; //++
//                              +  $partner_network_value_by_bonus[$b]
//                                + $social_integration_value_by_bonus[$b]; //активы
        }


        if($money_inout_after_26_by_bonus[6] > $loan_active_summ_by_bonus[6] + $loan_active_summ_by_bonus[7])
            $money_inout_after_26_by_bonus[6] = $loan_active_summ_by_bonus[6] + $loan_active_summ_by_bonus[7];


        //<editor-fold defaultstate="collapsed" desc="calc old $all_investments & $all_investments_info">
        $all_investments = $my_investments_garant_percent + $my_investments_garant + $payment_account + $partner_network_value + $social_integration_value; //активы

        $all_investments_info = "my_investments_garant_percent + my_investments_garant + payment_account + partner_network_value
                            + social_integration_value(активы) = $my_investments_garant_percent + $my_investments_garant + $payment_account + $partner_network_value
                            + $social_integration_value = $all_investments";
        //</editor-fold>


        $future_interest_payout      = $my_total_future_income_standart - $my_future_income_garant + $my_total_future_income_garant - $my_future_income_standart;
        $future_interest_payout_info = "my_total_future_income_standart - my_future_income_garant + my_total_future_income_garant - my_future_income_standart = $my_total_future_income_standart - $my_future_income_garant + $my_total_future_income_garant - $my_future_income_standart = $future_interest_payout";

        $total_future_income = $my_total_future_income_garant + $my_total_future_income_standart;

        //Емкость финансового рейтинга
        $FSRC = 0;
        if($max_loan != 0)
            $FSRC = ($all_investments - $all_liabilities - $total_garant_loans - $arbitrage_collateral) / $max_loan;

        $FSRC_temp = ($FSRC > 1 ? 1 : $FSRC);


        $FSRC_by_bonus = $null_array_by_bonus;
        if($max_loan > 0)
            foreach($null_array_keys as $b){


                $FSRC_by_bonus[$b] = ($all_investments_by_bonus[$b] - //++
                    $all_liabilities_by_bonus[$b] - //++
                    $total_garant_loans_by_bonus[$b] //++
                    /* - $arbitrage_collateral[2] */
                    ) / $max_loan; //++
                //        Т.к. арбитраж не дается под активы, вычитать залог не следует
            }


        $arbitr_inout = $arbitr_in - $arbitr_out;

        //рейтинг
        $ratio = 0;
        if($all_investments != 0)
            $ratio = ($all_investments - $all_liabilities) / $all_investments;

        $FSR_temp = round($max_rating * $FSRC_temp, 0);
        $FSR      = 5 + ($FSR_temp < 0 ? 0 : $FSR_temp); //округлять в большую сторону
        if($this->isUserAccountVerified($_id))
            $FSR += 4;

        //Максимально доступная сумма займа с процентами по опции Гарант
        //10/10/2015
        $max_loan_available_by_bonus      = $null_array_by_bonus;
        $availiable_garant_by_bonus       = $null_array_by_bonus;
        $total_processing_by_bonus        = $null_array_by_bonus;
        $max_loan_available_by_bonus_info = '';
        foreach($null_array_keys as $b){
            $arbitr_inout_by_bonus[$b] = $arbitr_in_by_bonus[$b] - $arbitr_out_by_bonus[$b];

            $total_processing_by_bonus[$b] = $total_processing_payout_sum_by_bonus[$b]//++=  $total_processing_payout
                + $total_processing_payout_fee_by_bonus[$b]  //++комиссии - см блокнот
                + $currency_exchange_scores['total_processing_p2p_by_bonus'][$b] + $processing_internal_transfer_sum_by_bonus[$b];

            //взять гарант
            $max_loan_available_by_bonus[$b] = round(($all_investments_by_bonus[$b] - $all_liabilities_by_bonus[$b]//++
                - $all_posted_garant_direct_by_bonus[$b] //++
                - $all_pending_garant_direct_by_bonus[$b] //++
                - $all_posted_standart_direct_by_bonus[$b] //++
                - $all_pending_standart_direct_by_bonus[$b] //++
                + $total_arbitrage_percentage_by_bonus[$b]//++
                - $total_processing_by_bonus[$b]//??? как только инициировал р2р-операцию его скор падает
                - $active_cards_credits  // добавим карточные вклады как обеспечение
                + $active_cards_invests



//                                        - $total_processing_payout_sum_by_bonus[$b]//++=  $total_processing_payout
//                                        - $total_processing_payout_fee_by_bonus[$b]  //++комиссии - см блокнот
//                                        - $currency_exchange_scores['total_processing_fee_by_bonus'][$b]//++
//                                        - $currency_exchange_scores['total_processing_p2p_by_bonus'][$b]//++
//                                    - Транзакции по переводе денежных средств бонус 2 в незавершенных
//                                    статусах (в ожидании, проверка СБ, в процессе, выставленные, но не прошедшие)
                ), 2);
            if($b == 6){
                $max_loan_available_by_bonus[$b] -= $garant_issued * 1;
                $max_loan_available_by_bonus[$b] += $garant_received * 1;
            }
            if($b == 2)
                $max_loan_available_by_bonus[$b] -= $total_active_blocked_own2;
//            $availiable_garant_by_bonus[$b] = $payment_account_by_bonus[$b]
//                    - $all_posted_garant_loans_out_summ_by_bonus[$b]
//                    - $total_processing_by_bonus[$b];
//          //дать гарант
            $availiable_garant_by_bonus[$b] = $payment_account_by_bonus[$b] - $total_processing_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] - $all_advanced_standart_invests_summ_by_bonus[$b];

            //if(0 &&  $this->get_user_id() == 90832388 && $b == 6 )
            {
                $max_loan_available_by_bonus_info .= "<b>Bonus=$b</b><br>";
                $max_loan_available_by_bonus_info .= "max_loan_available_by_bonus = <b>$max_loan_available_by_bonus[$b]</b> = <br> all_investments_by_bonus($all_investments_by_bonus[$b]) <br>
                                        - all_liabilities_by_bonus($all_liabilities_by_bonus[$b])//++<br>
                                        - all_posted_garant_direct_by_bonus($all_posted_garant_direct_by_bonus[$b]) //++<br>
                                        - all_pending_garant_direct_by_bonus($all_pending_garant_direct_by_bonus[$b]) //++<br>
                                        - all_posted_standart_direct_by_bonus($all_posted_standart_direct_by_bonus[$b]) //++<br>
                                        - all_pending_standart_direct_by_bonus($all_pending_standart_direct_by_bonus[$b]) //++<br>
                                        + total_arbitrage_percentage_by_bonus($total_arbitrage_percentage_by_bonus[$b])//++<br>
                                        - total_processing_by_bonus($total_processing_by_bonus[$b])//??? как только инициировал р2р-операцию его скор падает<br>
                                        - active_cards_credits($active_cards_credits)<br>
                                        + active_cards_invests($active_cards_invests)";

                if($b == 2)
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= total_active_blocked_own2($total_active_blocked_own2) = {$max_loan_available_by_bonus[$b]}";

                if($b == 6){
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= garant_issue = {$garant_issued}";
                    $max_loan_available_by_bonus_info .= "<br>max_loan_available_by_bonus[$b] -= garant_receive = {$garant_received}";
                }


                $max_loan_available_by_bonus_info .= "<br>[$b]availiable_garant_by_bonus = <b>$availiable_garant_by_bonus[$b]</b> = <br> payment_account_by_bonus($payment_account_by_bonus[$b]) <br>
                                    - total_processing_by_bonus($total_processing_by_bonus[$b]) <br>
                                    - all_advanced_invests_summ_by_bonus($all_advanced_invests_summ_by_bonus[$b]) <br>
                                    - all_advanced_standart_invests_summ_by_bonus($all_advanced_standart_invests_summ_by_bonus[$b])<br><hr>";
                //vred( $max_loan_available_by_bonus, $availiable_garant_by_bonus );
            }
        }

        //availiable_garant_by_bonus = payment_account
        //- all_advanced_loans_outsumm
        //- all_processing_payout_summ
        //- all_processing_payout_fee
        //- $currency_exchange_scores['total_processing_fee']
        //- $currency_exchange_scores['total_processing_p2p']
        //-
        //
//        old
        //<editor-fold defaultstate="collapsed" desc="calc old $max_loan_available">
        $max_loan_available = round(($all_investments - $all_liabilities - $overdraft - $all_posted_garant_direct - $all_pending_garant_direct - $all_posted_standart_direct - $all_pending_standart_direct - $total_processing_payout + $total_arbitrage_percentage - $my_investments_garant_partner - $currency_exchange_scores['limit2'] - $currency_exchange_scores['limit1'] - $currency_exchange_scores['limit3'] - $currency_exchange_scores['total_processing_fee']
            ), 2);

        $max_loan_available_info = "all_investments
                                    - all_liabilities
                                    - overdraft
                                    - all_posted_garant_direct
                                    - all_pending_garant_direct
                                    - all_posted_standart_direct
                                    - all_pending_standart_direct
                                    - total_processing_payout
                                    + total_arbitrage_percentage
                                    - my_investments_garant_partner
                                    - currency_exchange_scores['limit2']
                                    - currency_exchange_scores['limit1']
                                    - currency_exchange_scores['limit3']
                                    - currency_exchange_scores['total_processing_fee'] = $all_investments
                                    - $all_liabilities
                                    - $overdraft
                                    - $all_posted_garant_direct
                                    - $all_pending_garant_direct
                                    - $all_posted_standart_direct
                                    - $all_pending_standart_direct
                                    - $total_processing_payout
                                    + $total_arbitrage_percentage
                                    - $my_investments_garant_partner
                                    - {$currency_exchange_scores['limit2']}
                                    - {$currency_exchange_scores['limit1']}
                                    - {$currency_exchange_scores['limit3']}
                                    - {$currency_exchange_scores['total_processing_fee']} = $max_loan_available";
        //</editor-fold>

        $direct_collateral      = $all_posted_garant_direct + $all_pending_garant_direct + $all_posted_standart_direct + $all_pending_standart_direct;
        $direct_collateral_info = "all_posted_garant_direct
                                    + all_pending_garant_direct
                                    + all_posted_standart_direct
                                    + all_pending_standart_direct = $all_posted_garant_direct
                                    + $all_pending_garant_direct
                                    + $all_posted_standart_direct
                                    + $all_pending_standart_direct = $direct_collateral";

        $int_part      = explode('.', (string) $max_loan_available);
        $numder_digits = strlen($int_part[0]) + 3;
        $FSRC          = round($FSRC, $numder_digits);

        $total_assets = $payment_account +
            $my_bonuses +
            $my_partner_funds +
            $my_investments_standart +
            $my_investments_garant +
            $my_investments_garant_bonuses +
            $my_investments_garant_percent;


        $future_income = $my_future_income_garant + $my_future_income_standart + $expected_partnership_income;
        $balance       = $total_assets - $all_liabilities +
            $future_income -
            $my_bonuses -
//            $my_partner_funds  -
            $my_investments_garant_bonuses -
            $my_investments_garant_percent;

        $max_garant_loan_available = $max_loan_available - $all_advanced_loans_out_summ;

        //--v3-------tech
        $tech_payment_account = 0; //технический


        if($payment_account <= $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value){ ////06.05.2015
            $tech_payment_account      = $payment_account;
            $tech_payment_account_info = "payment_account = $tech_payment_account_info (because $payment_account (payment_account) <= $max_loan_available(max_loan_available) - $arbitrage_collateral(arbitrage_collateral) - $partner_network_value(partner_network_value) - $social_integration_value(social_integration_value))";
        } else {
            $tech_payment_account      = $max_garant_loan_available - $my_investments_garant_percent //проценты по гаранту
                + $loans_standart_out_summ - $arbitrage_collateral + $total_processing_payout - $partner_network_value //06.05.2015
                - $social_integration_value;  //13.06.2015
            $tech_payment_account_info = "max_garant_loan_available
                                    - my_investments_garant_percent
                                    + loans_standart_out_summ
                                    - arbitrage_collateral
                                    + total_processing_payout
                                    - partner_network_value
                                    - social_integration_value =   $max_garant_loan_available
                                    - $my_investments_garant_percent + $loans_standart_out_summ - $arbitrage_collateral + $total_processing_payout - $partner_network_value
                                    - $social_integration_value = $tech_payment_account";
        }

        if($tech_payment_account > $payment_account){
            $tech_payment_account = $payment_account;
            $tech_payment_account_info .= " присвоиили $payment_account(payment_account) потому что $tech_payment_account(tech_payment_account) > $payment_account(payment_account))";
        }


        //Собственные средства
        //               (1,1+1,3+1,4)
        $net_funds      = ($payment_account + ($my_investments_garant + $my_investments_garant_bonuses) + $my_investments_garant_percent ) - ( $my_loans + $total_arbitrage ); //-  (2,1+2,4)
        $net_funds_info = "(payment_account + (my_investments_garant + my_investments_garant_bonuses) + my_investments_garant_percent )
                     - ( my_loans + total_arbitrage ) = ($payment_account + ($my_investments_garant + $my_investments_garant_bonuses) + $my_investments_garant_percent )
                     - ( $my_loans + $total_arbitrage ) = $net_funds";


        $net_funds_by_bonus          = $null_array_by_bonus;
        $net_loan_available_by_bonus = $null_array_by_bonus;
        $net_loan_available          = $max_loan_available - $my_loans;
        foreach($null_array_keys as $b){
            $net_funds_by_bonus[$b] = ($payment_account_by_bonus[$b] + ($b == 6 ? $active_cards_garant_invests : 0) - ($b == 6 ? $active_cards_credits_outsumm : 0) + ($my_investments_garant_by_bonus[$b]) + $my_investments_garant_percent_by_bonus[$b] ) - ( $my_loans_by_bonus[$b] + $total_arbitrage_by_bonus[$b]); //-  (2,1+2,4)
            /* if ( $b == 6){
              $net_funds_by_bonus[$b] += $garant_issued *2;
              $net_funds_by_bonus[$b] -= $garant_received *2;
              } */

            if($max_loan_available_by_bonus[$b] > round($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b], 2)){
                $max_loan_available_by_bonus_info .= "<br>[$b]max_loan_available_by_bonus($max_loan_available_by_bonus[$b]) > net_funds_by_bonus($net_funds_by_bonus[$b]) - total_processing_by_bonus($total_processing_by_bonus[$b])  => max_loan_available_by_bonus = net_funds_by_bonus-total_processing_by_bonus =  ".($net_funds_by_bonus[$b] - $total_processing_by_bonus[$b]);
                $max_loan_available_by_bonus[$b] = $net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b];
                /* if ( $b == 6){
                  $max_loan_available_by_bonus[$b] -= $garant_issued;
                  $max_loan_available_by_bonus[$b] += $garant_received;
                  } */
            }


            $net_loan_available_by_bonus[$b] = max(0, $max_loan_available_by_bonus[$b] - $my_loans_by_bonus[$b]);
        }



        $max_garant_loan_available_by_bonus = $null_array_by_bonus;
        $tech_payment_account_by_bonus      = $null_array_by_bonus; //технический
        $payout_limit_by_bonus              = $null_array_by_bonus;

        foreach($null_array_keys as $b){


            $max_garant_loan_available_by_bonus[$b] = $max_loan_available_by_bonus[$b] -
                $all_posted_garant_loans_out_summ_by_bonus[$b]; //++



            if($payment_account_by_bonus[$b] <= $max_loan_available_by_bonus[$b] - ($b == 6 ? $garant_received : 0)
            // - $arbitrage_collateral_by_bonus[$b] // закоментировали залог по Арбитражу, т.к. он уже вычетается из all_liabilities
            //TODO:-$partner_network_value_by_bonus[$b]
            //TODO: - $social_integration_value_by_bonus[$b]
            ){ ////06.05.2015
                $tech_payment_account_by_bonus[$b] = $payment_account_by_bonus[$b];
            } else {

                $tech_payment_account_by_bonus[$b] = $max_loan_available_by_bonus[$b] - $all_posted_garant_loans_out_summ_by_bonus[$b]//++
                    - $my_investments_garant_percent_by_bonus[$b] //++проценты по гаранту
                    + $total_processing_by_bonus[$b] //вычетается 2 раза: $max_loan_available_by_bonus &  $total_processing_by_bonus
                    - $arbitrage_collateral_by_bonus[$b] + $loans_standart_out_summ_by_bonus[$b]
                //  + $all_active_garant_loans_summa_by_bonus[$b]
                ;
                if($b == 6)
                    $tech_payment_account_by_bonus[$b] -= $garant_received;

                //отрицательно доступно. Равшан, вычел из max_loan_avaliable - garant_issued и поломал всесь расчет
                //1)  Полученный займ «Гарант» уменьшил «Доступно» на тело+проценты, а должен был только на проценты
            }


            //if( $_id == 49966452 ) echo "$b::$tech_payment_account_by_bonus[$b] > $payment_account_by_bonus[$b]<br/>";
            if($tech_payment_account_by_bonus[$b] > $payment_account_by_bonus[$b]){
                $tech_payment_account_by_bonus[$b] = $payment_account_by_bonus[$b];
            }

            //if ( $b==6 ) $tech_payment_account_by_bonus[$b] -= $garant_received;



            $payout_limit_by_bonus[$b] = $tech_payment_account_by_bonus[$b] - $total_processing_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] //++выставленные вклады гарант
                - $all_advanced_standart_invests_summ_by_bonus[$b] //++выставленные вклады стандарт
                + $all_posted_garant_direct_by_bonus[$b] //++
                + $all_posted_standart_direct_by_bonus[$b];


            //if ( $b==6 ) $payout_limit_by_bonus[$b] -= $garant_received;

            if($_id == 1140006264)
                echo "$b:
                   T: $tech_payment_account_by_bonus[$b] = $max_loan_available_by_bonus[$b]
                                        - $all_posted_garant_loans_out_summ_by_bonus[$b]
                                        - $my_investments_garant_percent_by_bonus[$b]
                                        + $total_processing_by_bonus[$b]
                                        - $arbitrage_collateral_by_bonus[$b]
                                        + $loans_standart_out_summ_by_bonus[$b];

                P: $payout_limit_by_bonus[$b] =
                    $tech_payment_account_by_bonus[$b]
                    - $total_processing_by_bonus[$b]
                    - $all_advanced_invests_summ_by_bonus[$b]
                    - $all_advanced_standart_invests_summ_by_bonus[$b]
                    + $all_posted_garant_direct_by_bonus[$b]
                    + $all_posted_standart_direct_by_bonus[$b];
            ";
        }

        $payout_limit_bonus = array(0 => 0, 2 => 0, 5 => 0, 6 => 0); //доступно по счетам
        $payout_limit       = 0;
//        $payout_limit = $tech_payment_account -
//                $all_advanced_invests_summ - //гарантированные
//                $all_advanced_standart_invests_summ //стандарт
//                + $all_posted_garant_direct
//                + $all_posted_standart_direct;

        $payout_limit = $tech_payment_account -
            $all_advanced_invests_summ - //гарантированные
            $all_advanced_standart_invests_summ //стандарт
            + $all_posted_garant_direct + $all_posted_standart_direct - $total_processing_payout;

        $payout_limit_info = "tech_payment_account -
                all_advanced_invests_summ - //гарантированные
                all_advanced_standart_invests_summ //стандарт
                + all_posted_garant_direct
                + all_posted_standart_direct
                - total_processing_payout = $tech_payment_account -
                $all_advanced_invests_summ -
                $all_advanced_standart_invests_summ
                + $all_posted_garant_direct
                + $all_posted_standart_direct
                - $total_processing_payout = $payout_limit";




        // если бонус по P-CREDS или C-CREDS то $payout_limitсчитаем немного по другому
        /* if ( $bonus == 3)
          $payout_limit = $my_partner_funds;

          if ( $bonus == 4)
          $payout_limit = $c_creds_total;
         */







        //[21.08.15 23:43] raschet ostatka sobstvennih deneg
        $net_own_funds = + $money_sum_add_funds // dobavlennie dengi
            - $money_sum_withdrawal - $money_sum_process_withdrawal - $money_sum_transfer_to_users
            // - $money_sum_transfer_to_exch
            - $money_sum_transfer_to_merchant - $total_garant_loans;

        $net_own_funds_by_bonus_new = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $net_own_funds_by_bonus_new[$b] = + $money_sum_add_funds_by_bonus[$b] // dobavlennie dengi
                - $money_sum_withdrawal_by_bonus[$b]//++
                - $money_sum_process_withdrawal_by_bonus[$b]//++
                - $money_sum_transfer_to_users_by_bonus[$b]//++
                // - $money_sum_transfer_to_exch
                - $money_sum_transfer_to_merchant_by_bonus[$b]//++
                - $total_garant_loans_by_bonus[$b]; //++

            if($net_own_funds_by_bonus_new[$b] < 0)
                $net_own_funds_by_bonus_new[$b] = 0;
        }

        $net_own_funds_info = "
        + money_sum_add_funds // dobavlennie dengi
        - money_sum_withdrawal
        - money_sum_process_withdrawal
        - money_sum_transfer_to_users
        - money_sum_transfer_to_exch
        - money_sum_transfer_to_merchant
        - total_garant_loans = + $money_sum_add_funds
        - $money_sum_withdrawal
        - $money_sum_process_withdrawal
        - $money_sum_transfer_to_users
        - $money_sum_transfer_to_exch
        - $money_sum_transfer_to_merchant
        - $total_garant_loans = $net_own_funds";


        if($net_own_funds < 0){
            $net_own_funds = 0;
            $net_own_funds_info .= ' = 0 потому что был меньше 0';
        }


        // собственные средства с разбивкой на бонус 2 и 5
        $net_own_funds_by_bonus[2] = $payment_bonus_account[2] - $money_bonus_sum_process_withdrawal[2] - $creds_amount_invest_by_bonus[2];
        $net_own_funds_by_bonus[5] = $payment_bonus_account[5] - $money_bonus_sum_process_withdrawal[5] - $creds_amount_invest_by_bonus[5];
        $net_own_funds_by_bonus[6] = $payment_bonus_account[6] - $money_bonus_sum_process_withdrawal[6] - $creds_amount_invest_by_bonus[6];


//        if( $payout_limit > $payment_account )
//        {
//          $payout_limit = $payment_account;
//        }
//максимальная сумма, из которой расчитывается Арбитраж
//        if( $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value < $payout_limit )
//        {
//            $arbitrage_temp = $payout_limit;
//        }
//        else
//            $arbitrage_temp = $max_loan_available - $arbitrage_collateral - $partner_network_value - $social_integration_value;
//
//        $max_arbitrage_amount = $arbitrage_temp + $all_active_garant_loans_out_summ ;//+ $arbitrage_collateral;
//
//        if( $max_arbitrage_amount > ($arbitrage_temp + $arbitrage_collateral/2)*2.2 )
//            $max_arbitrage_amount = ($arbitrage_temp + $arbitrage_collateral/2) *2.2; //т.к. дырка Москаленко закрыта может убрать это?
//
//        $max_arbitrage_calc = round( $max_arbitrage_amount * 3 / 50 ) * 50;
        //новый max_arbitrage_calc - максимальный расчетный Арбитраж.
        //        if( $all_active_garant_loans_summa > $net_funds )
        //            $max_arbitrage_amount = $net_funds;
        //        else
        //            $max_arbitrage_amount = $all_active_garant_loans_summa;
        //
        //прибаляем потому что $net_funds вычитали
        //получается, что если взял арбитраж, то он вычетается из собственных средств, а для расчеткого количества
        //так не пойдет
//         echo "$all_active_garant_loans_summa > ($net_funds + $total_arbitrage)--";
//         echo $all_active_garant_loans_summa."!";
//         echo $total_grant_loan_new.'!';
//         $total_arbitrage2 ;     // все старые полученные арбитражи


        $arbitrage_collateral_real = $max_loan_available + $all_active_garant_loans_out_summ;
        if($arbitrage_collateral_real < 0)
            $arbitrage_collateral_real = 0;
//         $arbitrage_collateral2; // необходимый размер залога по старым арбитражам
//         if( $_id == 22921387 ) echo "$write_off_arbitration_old = ($arbitrage_collateral2 - $arbitrage_collateral_real)*3;";
        $write_off_arbitration_old = ($arbitrage_collateral2 - $arbitrage_collateral_real) * 3;
        if($write_off_arbitration_old < 17)
            $write_off_arbitration_old = 0;

//         $total_arbitrage3;      //все новые полученные арбитражи
//         $arbitrage_collateral3; // необходимый размер залога по новым арбитражам


        if($arbitrage_collateral2 <= $max_loan_available){
//            if( $_id == 22921387 ) echo  "$write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa_new) * 5;";
            $write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa_new) * 5; //
        } else {
//            if( $_id == 22921387 ) echo  "$write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa) * 5;";
            $write_off_arbitration_new = ($arbitrage_collateral3 - $all_active_garant_loans_summa) * 5; //
        }

        if($write_off_arbitration_new < 0)
            $write_off_arbitration_new = 0;


//         if( $_id == 22921387 ) echo  "$total_write_off_arbitration = $write_off_arbitration_old + $write_off_arbitration_new;";
        $total_write_off_arbitration = //$write_off_arbitration_old +
            $write_off_arbitration_new;


        $arbitrage_collateral_new = $total_arbitrage / 5;

        $pay_off_arbitration_new = ($arbitrage_collateral_new - $total_grant_loan_new) * 5;

        if($pay_off_arbitration_new < 17)
            $pay_off_arbitration_new = 0; //новички


            //
//        if( $arbitrage_collateral2 <= $max_loan_available )
//            $all_active_garant_loans_summa_temp = $all_active_garant_loans_summa;
//        else
        $all_active_garant_loans_summa_temp = $all_active_garant_loans_summa_new;

        // посчитаем арбитраж по бонусу 2 и 5
        $card_arbitr_active          = min($active_cards_credits, $active_cards_invests) * 5;
        $max_arbitrage_calc_by_bonus = $null_array_by_bonus;
        $max_arbitrage_days_by_bonus = $null_array_by_bonus;

        $akzia_by_bonus = $null_array_by_bonus;
        if(time() < strtotime('2015-12-04 00:00:00')){
            $akzia_by_bonus[2] = 0;
            $akzia_by_bonus[6] = 0
            //    + $card_arbitr_active
            ;
        }



        foreach([2, 5, 6] as $b){
            if($loan_active_cnt_by_bonus[$b] > 0)
                $max_arbitrage_days_by_bonus[$b] = floor($loan_active_days_remains_by_bonus[$b] / $loan_active_cnt_by_bonus[$b]);

            $all_active_garant_loans_summa_temp_by_bonus[$b] = $all_active_garant_loans_summa_new_by_bonus[$b];
            if($all_active_garant_loans_summa_temp_by_bonus[$b] > ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] )){
                /* if ( $b == 6){
                  $net_funds_by_bonus[$b] -= $garant_issued*2;
                  $net_funds_by_bonus[$b] += $garant_received*2;
                  } */
                $max_arbitrage_calc_by_bonus[$b] = max(0, ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] ) * 3) + $total_arbitrage2_by_bonus[$b];
            } else if($all_active_garant_loans_summa_temp_by_bonus[$b] < ($net_funds_by_bonus[$b] + ($b == 6 ? $garant_received : 0) - ($b == 6 ? $garant_issued : 0) - $total_processing_by_bonus[$b] )){

                $max_arbitrage_calc_by_bonus[$b] = $all_active_garant_loans_summa_temp_by_bonus[$b] * 3 + $total_arbitrage2_by_bonus[$b];
            } else {
                if($b == 6)
                    $all_active_garant_loans_summa_temp_by_bonus[$b] -= $garant_issued;
                $max_arbitrage_calc_by_bonus[$b] = $all_active_garant_loans_summa_temp_by_bonus[$b] * 3 + $total_arbitrage2_by_bonus[$b] + $active_cards_garant_invests * 2;
            }




            // if ( $b == 2 || $b ==6)
            //    $max_arbitrage_calc_by_bonus[$b] += $akzia_by_bonus[$b];



            if($total_arbitrage_by_bonus[$b] >= 3000)
                $max_arbitrage_calc_by_bonus[$b] = 0;

            $max_arbitrage_calc_by_bonus[$b] = $max_arbitrage_calc_by_bonus[$b] - $total_arbitrage_by_bonus[$b] + $total_arbitrage4_by_bonus[$b];
            if($b == 6)
                $max_arbitrage_calc_by_bonus[$b] += $total_active_usd2_fee_invests * 2; //uje 3 uchitivaetsya na verhu

            if($max_arbitrage_calc_by_bonus[$b] < 0)
                $max_arbitrage_calc_by_bonus[$b] = 0;
            if($max_arbitrage_calc_by_bonus[$b] > 3000)
                $max_arbitrage_calc_by_bonus[$b] = 3000;
            $max_arbitrage_calc_by_bonus[$b] = floor($max_arbitrage_calc_by_bonus[$b] / 50) * 50;
        }

        // посчитаем полный арбитраж
        if($all_active_garant_loans_summa_temp > $net_funds && $net_funds > 0)
            $max_arbitrage_calc = $net_funds * 3;
        else
            $max_arbitrage_calc = $all_active_garant_loans_summa_temp * 3 + $total_arbitrage2;

        if($max_arbitrage_calc > 3000)
            $max_arbitrage_calc = 3000;

        $max_arbitrage_calc = $max_arbitrage_calc - $total_arbitrage;
//        if( $_id == 99423637 ) echo "$max_arbitrage_calc = $max_arbitrage_calc - $total_arbitrage;";
        if($max_arbitrage_calc < 0)
            $max_arbitrage_calc = 0;

        $max_arbitrage_calc = floor($max_arbitrage_calc / 50) * 50;

        //$pay_off_arbitration = $total_write_off_arbitration;
//        if( $total_arbitrage > $max_arbitrage_calc_availiable )
//            $pay_off_arbitration = $total_arbitrage - $max_arbitrage_calc;
//
//        echo "$total_arbitrage $pay_off_arbitration = $total_arbitrage_real - $max_arbitrage_calc_availiable|";
//        echo "$total_arbitrage2 + $total_arbitrage3|";
//
//        //арбитраж к списанию
//
//        if( 0 > $max_arbitrage_calc && $arbitrage_collateral > 0 )
//        {
//            $pay_off_arbitration = - $max_arbitrage_calc;
//        }
        //-------------------
        #Сумма превышение максимального размера пополнений
        //1 + 2 - 6 - 7
        $top_up_overload_orig = $money_sum_add_funds + $money_sum_transfer_from_users
//        + $money_sum_process_withdrawal_bank
            - $money_sum_transfer_to_users - $money_sum_withdrawal;

        if($top_up_overload_orig <= 10000)
            $top_up_overload = 0;
        else
            $top_up_overload = $top_up_overload_orig - 10000;

        //сумма пополенения
        $top_up_sum = $money_sum_add_funds + $money_sum_transfer_from_users + $money_sum_process_withdrawal_bank - $money_sum_transfer_to_users - $money_sum_withdrawal;

        $top_up_sum_overflow = 0;
        if($top_up_sum > 10000){
            $top_up_sum_overflow = $top_up_sum - 10000;
        }

        //доступно на вывод/обмен
        $payout_limit_bonus[5] = $payment_bonus_account[5] - $currency_exchange_scores['net_processing_p2p'] - $currency_exchange_scores['total_processing_p2p'];
        //когда заработают вклады с бонуса 5 и переводы
        //минус внутренние переводы в незавершенных статусах (проверка, проверка СБ, в Ожидании)
        //минус выставленные заявки на вклад с бонусом 5

        $withdrawal_limit_by_bonus = $null_array_by_bonus;
        foreach($null_array_keys as $b){
            $bonus_earned_by_bonus[$b] = $bonus_earned_in_by_bonus[$b] - $bonus_earned_out_by_bonus[$b];

            $withdrawal_limit_by_bonus[$b]                = $net_own_funds_by_bonus[$b] +
                $income_merchant_send_by_bonus[$b] +
                $bonus_earned_by_bonus[$b];
            $max_garant_vklad_real_available_by_bonus[$b] = $payment_account_by_bonus[$b] - $all_advanced_invests_summ_by_bonus[$b] - $all_advanced_standart_invests_summ_by_bonus[$b] - $total_processing_by_bonus[$b];
        }

        $max_garant_vklad_real_available = $payment_account - $all_advanced_invests_summ - $all_advanced_standart_invests_summ - $total_processing_payout;


        if(0 && in_array($_id, array(500733, 500757)) &&
            strpos($_SERVER['REQUEST_URI'], 'ajax') == FALSE){

            echo "max_loan_available $max_loan_available<br>payout_limit $payout_limit<br/>";
            echo "limit1 {$currency_exchange_scores['limit1']}<br/>
                limit2 {$currency_exchange_scores['limit2']}<br/>
                limit3 {$currency_exchange_scores['limit3']}<br/>
                net_processing_p2p {$currency_exchange_scores['net_processing_p2p']}<br/>
                total_processing_fee {$currency_exchange_scores['total_processing_fee']}<br/>
                total_processing_p2p {$currency_exchange_scores['total_processing_p2p']}<br/><br/>";
        }

//        var_dump( $payment_account_by_bonus );
//        var_dump( $currency_exchange_scores['total_processing_p2p_by_bonus'] );
//        echo '<br>';
        //-------------------


        $ratings = array(
            'fsr'                                         => $FSR,
            'fsrc'                                        => $FSRC,
            'fsrc_by_bonus'                               => $FSRC_by_bonus,
            'max_loan_available'                          => $max_loan_available,
            'max_loan_available_by_bonus'                 => $max_loan_available_by_bonus,
            'balance'                                     => $balance,
            'payment_account'                             => $payment_account,
            'payment_account_by_bonus'                    => $payment_account_by_bonus,
            'payment_account_info'                        => $payment_account_info,
            'bonuses'                                     => $my_bonuses,
            'partner_funds'                               => $my_partner_funds,
            'all_advanced_invests_bonuses_summ'           => $all_advanced_invests_bonuses_summ,
            'transfered_summ_from_bonus_5_to_2'           => $transfered_summ_from_bonus_5_to_2,
            'investments_garant'                          => $my_investments_garant,
            'investments_garant_by_bonus'                 => $my_investments_garant_by_bonus,
            'my_investments_garant_by_bonus'              => $my_investments_garant_by_bonus,
            'my_investments_garant_percent'               => $my_investments_garant_percent,
            'investments_garant_bonuses'                  => $my_investments_garant_bonuses,
            'investments_standart'                        => $my_investments_standart,
            'all_liabilities'                             => $all_liabilities,
            'all_liabilities_by_bonus'                    => $all_liabilities_by_bonus,
            'loans'                                       => $my_loans,
            'my_loans_by_bonus'                           => $my_loans_by_bonus,
            'total_arbitrage_by_bonus'                    => $total_arbitrage_by_bonus,
            'my_investments_garant_percent_by_bonus'      => $my_investments_garant_percent_by_bonus,
            'max_loan_available_by_bonus_info'            => $max_loan_available_by_bonus_info,
            'total_processing_by_bonus'                   => $total_processing_by_bonus,
            'pcreds_inout_after_0112'                     => $pcreds_inout_after_0112,
            'pcreds_income_after_0112'                    => $pcreds_income_after_0112,
            'pcreds_in_payout_process_after_0112'         => $pcreds_in_payout_process_after_0112,
            'invests_own_sum_by_bonus'                    => $invests_own_sum_by_bonus,
            'invests_showed_and_active_own_sum_by_bonus'  => $invests_showed_and_active_own_sum_by_bonus,
            'loans_percentage'                            => $my_loans_percentage,
            'loans_percentage_by_bonus'                   => $my_loans_percentage_by_bonus,
            'money_sum_transfer_from_users_by_bonus'      => $money_sum_transfer_from_users_by_bonus,
            'active_cards_invests'                        => $active_cards_invests,
            'active_cards_credits'                        => $active_cards_credits,
            'all_loans_active_summ_by_bonus'              => $all_loans_active_summ_by_bonus,
            'active_cards_credits_outsumm'                => $active_cards_credits_outsumm,
            'active_cards_invests_outsumm'                => $active_cards_invests_outsumm,
            'active_cards_garant_invests'                 => $active_cards_garant_invests,
            'active_cards_garant_credits'                 => $active_cards_garant_credits,
            'card_arbitr_active'                          => $card_arbitr_active,
            'garant_issued'                               => $garant_issued,
            'garant_received'                             => $garant_received,
            'garant_issued_in_wait'                       => $garant_issued_in_wait,
            'total_active_blocked_own2'                   => $total_active_blocked_own2,
            'total_active_usd2_fee_invests'               => $total_active_usd2_fee_invests,
            'loan_active_summ'                            => $loan_active_summ,
            'loan_active_cnt'                             => $loan_active_cnt,
            'loan_active_summ_by_bonus'                   => $loan_active_summ_by_bonus,
            'loan_active_cnt_by_bonus'                    => $loan_active_cnt_by_bonus,
            'arbitrage_collateral_by_bonus'               => $arbitrage_collateral_by_bonus,
            'loans_percentage_garant_by_bonus'            => $my_loans_percentage_by_bonus,
            'arbitrage_collateral_by_bonus'               => $arbitrage_collateral_by_bonus,
            'loans_percentage_garant_by_bonus'            => $my_loans_percentage_by_bonus,
            'showed_cards_credits'                        => $showed_cards_credits,
            'showed_cards_garant_credits'                 => $showed_cards_garant_credits,
            'showed_cards_invests'                        => $showed_cards_invests,
            'showed_cards_garant_invests'                 => $showed_cards_garant_invests,
            'loan_active_days_remains'                    => $loan_active_days_remains,
            'loan_active_days_remains_by_bonus'           => $loan_active_days_remains_by_bonus,
            'net_loan_available_by_bonus'                 => $net_loan_available_by_bonus,
            'net_loan_available'                          => $net_loan_available,
            'loans_percentage_garant'                     => $my_loans_percentage_garant,
            'future_income'                               => $future_income,
            'total_future_income'                         => $total_future_income,
            'future_interest_payout'                      => $future_interest_payout, //будущая выплата по процентам
            'all_advanced_loans_out_summ'                 => $all_advanced_loans_out_summ, //сумма тел + %% выставленных заявок на займ Гарант
            'all_advanced_loans_out_summ_by_bonus'        => $all_posted_garant_loans_out_summ_by_bonus, //сумма тел + %% выставленных заявок на займ Гарант
            'all_advanced_invests_summ'                   => $all_advanced_invests_summ, //сумма тел + %% выставленных заявок на вклад Гарант
            'all_advanced_standart_invests_summ'          => $all_advanced_standart_invests_summ, //выставленные заявки на вклад Стандарт
            'all_advanced_standart_invests_summ_by_bonus' => $all_advanced_standart_invests_summ_by_bonus, //выставленные заявки на вклад Стандарт
            'total_processing_payout'                     => $total_processing_payout,
            'total_processing_payout_sum_by_bonus'        => $total_processing_payout_sum_by_bonus,
            'total_processing_payout_fee_by_bonus'        => $total_processing_payout_fee_by_bonus,
            'max_arbitrage_calc_by_bonus'                 => $max_arbitrage_calc_by_bonus,
            'availiable_garant_by_bonus'                  => $availiable_garant_by_bonus,
            'all_advanced_invests_summ_by_bonus'          => $all_advanced_invests_summ_by_bonus,
            'transfered_summ_from_bonus_2_to_6'           => $transfered_summ_from_bonus_2_to_6,
            'max_garant_vklad_real_available_by_bonus'    => $max_garant_vklad_real_available_by_bonus,
            'total_garant_loans'                          => $total_garant_loans,
            'total_garant_loans_by_bonus'                 => $total_garant_loans_by_bonus,
            'payout_limit'                                => $payout_limit,
            'payout_limit_by_bonus'                       => $payout_limit_by_bonus,
            'payout_limit_info'                           => $payout_limit_info,
            'money_sum_transfer_to_merchant_by_bonus'     => $money_sum_transfer_to_merchant_by_bonus,
            'arbitr_inout'                                => $arbitr_inout,
            'arbitr_inout_by_bonus'                       => $arbitr_inout_by_bonus,
            'bonus_earned_by_bonus'                       => $bonus_earned_by_bonus,
            'money_sum_withdrawal_by_bonus'               => $money_sum_withdrawal_by_bonus,
            'money_own_from_2_to_6'                       => $money_own_from_2_to_6,
            'max_arbitrage_days_by_bonus'                 => $max_arbitrage_days_by_bonus,
            'akzia_by_bonus'                              => $akzia_by_bonus,
            'money_in_after_26'                           => $money_in_after_26,
            'money_in_after_26_by_bonus'                  => $money_in_after_26_by_bonus,
            'money_in_after_26'                           => $money_in_after_26,
            'money_in_after_26_by_bonus'                  => $money_in_after_26_by_bonus,
            'money_inout_after_26'                        => $money_inout_after_26,
            'money_inout_after_26_by_bonus'               => $money_inout_after_26_by_bonus,
            'total_assets'                                => $total_assets,
            'max_garant_loan_available'                   => $max_garant_loan_available, //Сумма, на которую вы можете получить займ Гарант (включая проценты)
            'total_arbitrage'                             => $total_arbitrage, //остаток по арбитражу
            'arbitrage_collateral2'                       => $arbitrage_collateral2,
            'arbitrage_collateral3'                       => $arbitrage_collateral3,
            'arbitrage_collateral'                        => $arbitrage_collateral, //обеспечение по Арбитражу,
            'arbitrage_collateral2'                       => $arbitrage_collateral2,
            'arbitrage_collateral3'                       => $arbitrage_collateral3,
            'overdraft'                                   => $overdraft,
            'loans_standart_out_summ'                     => $loans_standart_out_summ,
            'sum_partner_reword_by_bonus'                 => $sum_partner_reword_by_bonus,
            'all_active_garant_loans_out_summ'            => $all_active_garant_loans_out_summ,
            'all_active_garant_loans_out_summ_by_bonus'   => $all_active_garant_loans_out_summ_by_bonus,
            'soon'                                        => $expected_partnership_income,
            'direct_collateral'                           => $direct_collateral,
            'overdue_standart_count'                      => $overdue_standart_count,
            'overdue_standart'                            => $overdue_standart,
            'overdue_garant_count'                        => $overdue_garant_count,
            'overdue_garant'                              => $overdue_garant,
            'money_sum_add_funds'                         => $money_sum_add_funds,
            'money_sum_add_funds_by_bonus'                => $money_sum_add_funds_by_bonus,
            'money_sum_add_card'                          => $money_sum_add_card,
            'money_sum_add_card_by_bonus'                 => $money_sum_add_card_by_bonus,
            'money_sum_withdrawal'                        => $money_sum_withdrawal,
            'money_sum_withdrawal_minus_type65'           => $money_sum_withdrawal_minus_type65,
            'money_sum_add_card'                          => $money_sum_add_card,
            'money_sum_add_card_by_bonus'                 => $money_sum_add_card_by_bonus,
            'money_sum_process_withdrawal'                => $money_sum_process_withdrawal,
            'money_sum_transfer_to_users'                 => $money_sum_transfer_to_users, //сумма перевода другим
            'money_sum_transfer_from_users'               => $money_sum_transfer_from_users, //от других
            'sum_partner_reword'                          => $sum_partner_reword, //партнерские
            'sum_partner_reword_by_bonus'                 => $sum_partner_reword_by_bonus,
            'tech_payment_account_info'                   => $tech_payment_account_info,
            'month_partner_unic_id_count'                 => $month_partner_unic_id_count, //Количество партнерских с разных ID for month
            'partner_unic_id_count'                       => $partner_unic_id_count, //Количество партнерских с разных ID for week
            'diversification_coeff'                       => $diversification_coeff, //Коэфицент диверсификации
            'partner_week_contribution'                   => $partner_week_contribution, //Сумма партнерских за неделю
            'partner_contribution_count'                  => $partner_contribution_count, //Количество партнерских отчислений
            'average_partner_contribution'                => $average_partner_contribution, //Средняя сумма партнерского отчисления
            'diversification_degree'                      => $diversification_degree, //Степень Диверсификации
            'partner_network_valuation_coeff'             => $partner_network_valuation_coeff, //Коэфицент оценки партнерской сети
            'partner_network_value'                       => $partner_network_value, //Новый кредитный лимит
            'max_arbitrage_amount'                        => $max_arbitrage_amount, //НЕ ИСПОЛЬЗУЕТСЯ; вместо - max_arbitrage_calc - максимальная сумма, из которой расчитывается Арбитраж
            'overdue_garant_interest'                     => $overdue_garant_interest, //разница процентов по просроченным займам гарант,
            'net_funds'                                   => $net_funds, //Собственные средства
            'net_funds_info'                              => $net_funds_info,
            'fee_or_fine'                                 => $fee_or_fine, //всяктие отчисления, штрафы
            'pay_off_arbitration'                         => $total_write_off_arbitration, //арбитраж к списанию
            'max_arbitrage_calc'                          => $max_arbitrage_calc, //максимально-доступный арбитраж
            'social_integration_value'                    => $social_integration_value, //коэф соц интеграции
            'money_sum_process_withdrawal_bank'           => $money_sum_process_withdrawal_bank,
            'arbitration_garant_active_summ'              => $arbitration_garant_active_summ,
            'arbitration_garant_active_summ_by_bonus'     => $arbitration_garant_active_summ_by_bonus,
            'arbitration_active_summ'                     => $arbitration_active_summ,
            'arbitration_active_summ_by_bonus'            => $arbitration_active_summ_by_bonus,
            'arbitration_shown_summ'                      => $arbitration_active_summ,
            'arbitration_shown_summ_by_bonus'             => $arbitration_active_summ_by_bonus,
            'old_arbitration_active_summ'                 => $old_arbitration_active_summ,
            'old_arbitration_active_summ_by_bonus'        => $old_arbitration_active_summ_by_bonus,
            'sum_loan_active_summ'                        => $sum_loan_active_summ,
            'sum_loan_active_summ_by_bonus'               => $sum_loan_active_summ_by_bonus,
            'loan_garant_active_summ'                     => $loan_garant_active_summ,
            'max_garant_loan_available_by_bonus'          => $max_garant_loan_available_by_bonus,
            'p2p_money_sum_transfer_to_users'             => $p2p_money_sum_transfer_to_users,
            'p2p_money_sum_transfer_to_users_by_bonus'    => $p2p_money_sum_transfer_to_users_by_bonus,
            'p2p_money_sum_transfer_from_users'           => $p2p_money_sum_transfer_from_users,
            'p2p_money_sum_transfer_from_users_by_bonus'  => $p2p_money_sum_transfer_from_users_by_bonus,
            'money_sum_transfer_to_users_by_bonus'        => $money_sum_transfer_to_users_by_bonus,
            'top_up_sum_overflow'                         => $top_up_sum_overflow, //Сумма пополнений
            'top_up_sum'                                  => $top_up_sum, //Сумма пополнений
            'money_sum_transfer_from_exch'                => $money_sum_transfer_from_exch, //Сумма пополнений
            'money_sum_transfer_from_exch_exwt'           => $money_sum_transfer_from_exch_exwt,
            'money_sum_transfer_to_exch'                  => $money_sum_transfer_to_exch, //Сумма пополнений
            'money_diff_transfer_exch'                    => $money_sum_transfer_from_exch - $money_sum_transfer_to_exch, //Сумма пополнений
            'money_sum_transfer_to_users_clear'           => $money_sum_transfer_to_users - $money_sum_transfer_to_exch, //Сумма пополнений
            'money_sum_transfer_from_users_clear'         => $money_sum_transfer_from_users - $money_sum_transfer_from_exch, //Сумма пополнений
            'money_sum_transfer_to_merchant'              => $money_sum_transfer_to_merchant,
            'c_creds_amount'                              => $c_creds_amount,
            'c_creds_total'                               => $c_creds_total,
            'c_creds_amount'                              => $c_creds_amount,
            'net_processing_p2p'                          => $currency_exchange_scores['net_processing_p2p'],
            'net_processing_p2p_by_bonus'                 => $currency_exchange_scores['net_processing_p2p_by_bonus'],
            'total_processing_fee'                        => $currency_exchange_scores['total_processing_fee'],
            'total_processing_fee_by_bonus'               => $currency_exchange_scores['total_processing_fee_by_bonus'],
            'total_processing_p2p'                        => $currency_exchange_scores['total_processing_p2p'],
            'total_processing_p2p_by_bonus'               => $currency_exchange_scores['total_processing_p2p_by_bonus'],
            'bonus_earned_in'                             => $bonus_earned_in,
            'bonus_earned_out'                            => $bonus_earned_out,
            'bonus_earned'                                => $bonus_earned_in - $bonus_earned_out,
            'bonus_earned_in_by_bonus'                    => $bonus_earned_in_by_bonus,
            'bonus_earned_out_by_bonus'                   => $bonus_earned_out_by_bonus,
            'bonus_earned_by_bonus'                       => $bonus_earned_by_bonus,
            'c_creds_amount_process'                      => $c_creds_amount_process,
            'net_own_funds'                               => $net_own_funds,
            'net_own_funds_info'                          => $net_own_funds_info,
            'payment_bonus_account'                       => $payment_bonus_account, // разбивка счета по бонусам 2 и 5
            'net_own_funds_by_bonus'                      => $net_own_funds_by_bonus, // собвстенные средтва с разбивкой по бонусам 2 и 5
            'net_own_funds_by_bonus_new'                  => $net_own_funds_by_bonus_new, // собвстенные средтва с разбивкой по бонусам 2 и 5
            'withdrawal_limit_by_bonus'                   => $withdrawal_limit_by_bonus,
            'net_funds_by_bonus'                          => $net_funds_by_bonus,
            'my_income_money'                             => $my_income_money, //Сумма пополнений
            'my_outcome_money'                            => $my_outcome_money, //Сумма out
            'my_income_money_acrhive'                     => $my_income_money_acrhive, //Сумма пополнений в архиве
            'my_outcome_money_acrhive'                    => $my_outcome_money_acrhive, //Сумма списаний в архиве
            'my_outcome_money'                            => $my_outcome_money, //Сумма out
            'my_inoutcome_money_acrhive'                  => $my_income_money_acrhive - $my_outcome_money_acrhive, //Сумма inout в архиве
            'tech_payment_account'                        => $tech_payment_account,
            'all_posted_standart_direct'                  => $all_posted_standart_direct,
            'all_posted_garant_direct'                    => $all_posted_garant_direct,
            'all_active_garant_loans_summa_new'           => $all_active_garant_loans_summa_new,
            'max_garant_vklad_real_available'             => $max_garant_vklad_real_available,
            'payout_limit_bonus'                          => $payout_limit_bonus,
            'income_merchant_send'                        => $income_merchant_send, // входящие переводы мерчанта
            'income_merchant_send_by_bonus'               => $income_merchant_send_by_bonus, // входящие переводы мерчанта
            'income_merchant_return'                      => $income_merchant_return, // входящие возвраты мерчанта
            'outcome_merchant_send'                       => $outcome_merchant_send, // исходящие переводы мерчанта
            'outcome_merchant_send_by_bonus'              => $outcome_merchant_send_by_bonus, // исходящие переводы мерчанта
            'outcome_merchant_return'                     => $outcome_merchant_return, // исходящие возвраты мерчанта
            'outcome_merchant_return_by_bonus'            => $outcome_merchant_return_by_bonus,
            'income_internal_sends'                       => $income_internal_sends, // пополениния внутренних переводов
            'outcome_internal_sends'                      => $outcome_internal_sends // списания внутренних переводов
        );

        if($ratings['pay_off_arbitration'] > 55 && $ratings['pay_off_arbitration'] > $ratings['c_creds_total'])
            $this->checkArbitrationToPayOff($_id, $ratings);

        //если не пусто - удаляем
        if(0){//&& in_array($_id, $test_users) || $this->checkNeedSave( $_id ) )
//            if( $_id == 500733 ) echo "--save-1<br>";
            $need_update = FALSE;
            if(empty($need_calculate_id)){

                $checkRatings_src = $this->checkRatings($ratings);

                $checkRatings = ( $checkRatings_src === TRUE ? 'OK' : 'FAIL: '.$checkRatings_src );
                $this->add_to_log('save_scores', $checkRatings);

                $need_update = ($checkRatings !== TRUE);
            }

            if(!empty($need_calculate_id) || $need_update){
//                echo "saved--";
//                if( $_id == 500733 ) echo "--save-2<br>";
                $new_ratings = $this->saveGetUserRating($_id, $ratings);

                if(empty($new_ratings)){
                    $this->add_to_log('save_scores', 'Empty new scores data');
                    //add to log
                }

                if(!empty($need_calculate_id)){
                    $this->removeArbitrationToPayOff($_id, $last_rating, $ratings);
                    $removeNeedRecalculate    = $this->removeNeedRecalculate($_id, $need_calculate_id);
                    $checkArbitrationToPayOff = $this->checkArbitrationToPayOff($_id, $new_ratings);
                    if($checkArbitrationToPayOff === TRUE){
                        $this->add_to_log('save_scores', 'checkArbitrationToPayOff === TRUE');

                        //add to log
                    }
                    if($removeNeedRecalculate === FALSE){
                        $this->add_to_log('save_scores', 'removeNeedRecalculate === FALSE');
                        //add to log
                    }
                }
            }
        }
        if(null == $date_end && null == $bonus && null == $date_start){
            $this->users_rate[$_id] = $ratings;
        }
        return $ratings;
    }

    public function isCanSendMoneyCurExc($send_transaction, $payout = FALSE){
        $rating = viewData()->accaunt_header;

        $this->load->model('users_model', 'users');
        $data_incame  = $this->users->getUserPureIncome($send_transaction->own);
        $credit_incam = (float) $data_incame[0];
        $this->load->model("transactions_model", "transactions");
        $archive_sum  = (float) $this->transactions->getArhiveSum($send_transaction->own);

//        $obtaining = $rating['sum_partner_reword']                            //заработаных денег
//                     + $credit_incam;
////                     - $rating['fee_or_fine'];
        //if(50 > $obtaining) return "Не хватает заработанных денег";

        if(!$payout && $send_transaction->summa >= $rating['payment_bonus_account'][5])
            return "Нет свободных средств"; //есть ли свободные средства



//        $balans = ($rating['money_sum_add_funds']                             //баланс положительный
//                   + $rating['money_sum_transfer_from_users']
//                   + $rating['sum_partner_reword']
//                   + $credit_incam
//                   + $archive_sum)
//                  - ($rating['money_sum_transfer_to_users']
//                    + $rating['money_sum_withdrawal']
//                    + $rating['money_sum_process_withdrawal']); //+ $rating['fee_or_fine']
//        if(!$payout && $send_transaction->summa > $balans) return "Сумма на вывод больше доступных средств";
//        if($payout && 0 > $balans) return "Баланс отрицателен";

        $this->load->model('transactions_model', 'transactions');             //нет дуюляжей
        if($this->transactions->getDouble($send_transaction->own) || $this->transactions->getDoubleArhive($send_transaction->own))
            return "В истории ваших транзакций есть продублированные списания или пополнения";

        return true;
    }

    public function get_remove_creds_summ2($user_id, $without_total_active_blocked_own2 = FALSE){



        $perc                  = 0.20;
        $isUS2USorCA           = $this->isUS2USorCA($user_id);
        $isUsaLimitedUser      = $this->users->isUsaLimitedUser($user_id);
        if($isUS2USorCA)
            if(!$isUsaLimitedUser)
                $perc                  = 0.20;
            else
                $perc                  = 1;
        $user_ratings          = $this->recalculateUserRating($user_id);
        $user_ratings_25072015 = $this->recalculateUserRating($user_id, null, null, '2015-07-25');
        $own                   = round(
            $user_ratings['money_sum_add_funds_by_bonus'][5] +
            $user_ratings_25072015['money_sum_transfer_from_exch_exwt'] -
            $user_ratings['money_sum_withdrawal_by_bonus'][5] -
            $user_ratings_25072015['money_sum_transfer_to_users_by_bonus'][5], 2);

        $r = round($user_ratings['invests_own_sum_by_bonus'][6] + $user_ratings['active_cards_garant_invests'] + $own * $perc, 2);
        if($without_total_active_blocked_own2 == FALSE)
            $r -= $user_ratings['total_active_blocked_own2'];

        if($r > $own)
            $r = $own;




        if($r < 50 && $r > 0)
            $r = 50;

        /* if ( $user_id == 500150)
          echo "  ({$user_ratings['money_sum_add_funds_by_bonus'][5]} +
          {$user_ratings_25072015['money_sum_transfer_from_exch_exwt']} -
          {$user_ratings['money_sum_withdrawal_by_bonus'][5]} -
          {$user_ratings_25072015['money_sum_transfer_to_users_by_bonus'][5]}) * $perc - {$user_ratings['total_active_blocked_own2']} = $r"; */

        $r = max(0, $r);
        return $r;
    }

}
