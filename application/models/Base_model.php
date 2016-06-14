<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model extends CI_Model {

    const TRANSACTION_STATUS_RECEIVED           = 1;
    const TRANSACTION_STATUS_NOT_RECEIVED       = 2;
    const TRANSACTION_STATUS_REMOVED            = 3;
    const TRANSACTION_STATUS_IN_PROCESS         = 4;
    const TRANSACTION_STATUS_PENDING            = 5;
    const TRANSACTION_STATUS_ERROR              = 6;
    const TRANSACTION_STATUS_INSUFFICIENT_FUNDS = 7;
    const TRANSACTION_STATUS_DELETED            = 8;
    const TRANSACTION_STATUS_VEVERIFYED         = 9;
    const TRANSACTION_STATUS_VEVERIFY_SS        = 10;
    const TRANSACTION_STATUS_IN_PROCESS_BANK    = 11;
    const TRANSACTION_BONUS_CREDS_CASH = 4;
    const TRANSACTION_BONUS_PARTNER = 3;
    const TRANSACTION_BONUS_OFF = 2;
    const TRANSACTION_BONUS_ON = 1;
    const CREDIT_STATUS_SHOWED = 1;
    const CREDIT_STATUS_ACTIVE = 2;
    const CREDIT_STATUS_WAITING = 3; //не используется
    const CREDIT_STATUS_OVERDUE = 4; //не используется
    const CREDIT_STATUS_PAID = 5;
    const CREDIT_STATUS_DELETED = 6;
    const CREDIT_STATUS_CANCELED = 7;
    const CREDIT_STATUS_ANNULLED = 8;
    const CREDIT_TYPE_CREDIT = 1;
    const CREDIT_TYPE_INVEST = 2;
    const CREDIT_TYPE_GIFTGUARANT = 3;
    const CREDIT_BONUS_OFF = 0;
    const CREDIT_BONUS_ON = 1;
    const CREDIT_BONUS_PARTNER = 3;
    const CREDIT_BONUS_CREDS_CASH = 4;
    const CREDIT_BONUS_GARANT_CASH = 9;
    const CREDIT_GARANT_OFF = 0;
    const CREDIT_GARANT_ON = 1;
    const CREDIT_ARBITRATION_OFF = 0;
    const CREDIT_ARBITRATION_ON = 1;
    const CREDIT_OVERDRAFT_OFF = 0;
    const CREDIT_OVERDRAFT_ON = 1;
    const CREDIT_EXCHANGE_OFF = 0;
    const CREDIT_EXCHANGE_ON = 1;
    const CREDIT_EXCHANGE_STATUS_BAY = 3;
    const CREDIT_EXCHANGE_STATUS_NORMAL = 1;
    const CREDIT_CERTIFICAT_CREATED = 1;
    const CREDIT_CERTIFICAT_PAID = 2;
    const CREDIT_CONFIRM_PAYMENT_CONFIRM = 1;
    const CREDIT_CONFIRM_PAYMENT_WAITING = 2;
    const CREDIT_KONTRACT_PAID = 0;
    const CREDIT_KONTRACT_PAID_OUT_TIME = "0000-00-00";
    const CREDIT_CERTIFICAT_PAID_USER_ID = 90831137;  //90831137 - ".'.$GLOBALS["WHITELABEL_NAME"].'." Guarantee Fund
    const CREDIT_WEBTRANSFER_GUARANTEE_FUND = 90831137;  //90831137 - ".'.$GLOBALS["WHITELABEL_NAME"].'." Guarantee Fund
    const CREDIT_ARBITRAGE_PAID_USER_ID = 400400;
    const CREDIT_CERTIFICAT_PAID_NEW_DEBIT = 0;
    const CREDIT_CERTIFICAT_CAUSE_ARBITRAGE = 1;
    const CREDIT_CERTIFICAT_CAUSE_SELL      = 2;
    const CREDIT_CERTIFICAT_CAUSE_OVERDUE   = 3;
    const CREDIT_INVEST_ARBITRAGE_PARTNER = 2;
    const CREDIT_INVEST_ARBITRAGE_ON = 1;
    const CREDIT_INVEST_ARBITRAGE_OFF = 0;
    const USER_STATE_ON = 1;
    const USER_STATE_SERIY = 2;
    const USER_STATE_OFF = 3;
    const USER_VOLUNTEER_OFF = 2;
    const USER_VOLUNTEER_ON = 1;
    const USER_BOT_OLD = 4;
    const USER_BOT_PSEVDO = 3;
    const USER_BOT_OFF = 2;
    const USER_BOT_ON = 1;
    const USER_PHONE_VERIFIED = 1;

    public $user_is_login = false; //для отображения и скрытия менюшки
    private $_parner_plans = []; //кеш parner_plan

    private function _need_recalculate_user_rating()
    {

        $current_uri = uri_string();
        $url_to_pass = ['ajax/sell_search','ajax/get_last_deal_items','ajax/sell_search','ajax/get_add_bank_in_payment_system',
                        'ajax/get_dop_ps_data_multi_field','ajax/search_order_details',//P2P
                        'ajax_user/applications_table',//setrificate exchange
                        'ajax/load_more_orders_sell_arhive',
                        '/partner/',
//                        '/account/transactions'
                        ];
        #main page
        if( empty( $current_uri ) || $current_uri == '/' ) return FALSE;

        foreach( $url_to_pass as $url )
            if( strpos($current_uri, $url ) !== FALSE ) return FALSE;

        return TRUE;
    }

    public function generate_page_hash()
    {
        $rand = rand(1,100000);
        viewData()->page_hash = md5( time() + $rand ); //уникальный хеш
    }

    public function get_real_user_ip(){
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    private function is_login_db($login, $pass) {
        $user_ip = $this->get_real_user_ip();

        $data = $this->db->where('email', urldecode($login))
            ->get("users")
            ->row();

        if (empty($data))
            return null;

        if(md5($data->user_pass) != $pass)
            return null;

        $data = $this->code->db_decode($data);
        $_SESSION['auth-idUser'] = $data->id_user;
        $_SESSION['auth-time']   = time();
        $_SESSION['last_login_ip_address'] = $user_ip;

        $this->accaunt->online($data->id_user);

        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, "Успешный вход в лич. каб пользователя", 'private', $data->id_user);
        return $data;
    }

    public function is_login($login, $pass) {
        $user_ip = $this->get_real_user_ip();

        if( 0 && isset( $_SESSION['auth-idUser'] ) && $_SESSION['auth-idUser'] == 500733 )
        {
            echo $user_ip.' '.$_SESSION['last_login_ip_address'];

        }
        if(00&& isset( $_SESSION['last_login_ip_address'] ) && $_SESSION['last_login_ip_address'] != $user_ip )
        {

            unset($_SESSION);

            return FALSE;
        }
        if (!empty($_SESSION['auth-idUser']) && time() <= ($_SESSION['auth-time']+(1*60*60)) ) {
            $data = $this->db->where("id_user", intval($_SESSION['auth-idUser']))
                    ->get("users")
                    ->row();
            if (empty($data))
                return false;
            if ($data->email != urldecode($login))
                return $this->is_login_db($login, $pass);

            $data = $this->code->db_decode($data);
            $_SESSION['auth-idUser'] = $data->id_user;
            $_SESSION['auth-time']   = time();
            $_SESSION['last_login_ip_address'] = $user_ip;

            return $data;
        }
        else
            return $this->is_login_db($login, $pass);

        return false;
    }

    public function login(&$var, $redirect = true) {


        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('users_model', 'users');
        $this->load->model('users_photo_model', 'users_photo');
        $this->load->model('phone_model','phones');
        $this->load->library('globalcheck');
        $this->load->library('code');
        $this->globalcheck->verify();

        $tstart = time(TRUE);

        $login = $this->input->cookie('login');
        $pass = $this->input->cookie('password');

        // http://wtest2.cannedstyle.com/ru/account/cloudwallet
        if ( $this->uri->rsegment(2) == 'cloudwallet' && (empty($login) || empty($pass)) ){

            $_SESSION['wtauth_simple'] = [
                'redirect'=>$this->uri->uri_string()
             ];

            redirect('wtauth/simple');
        }


        // http://wtest2.cannedstyle.com/ru/account/cloudwallet
        if ( $this->uri->rsegment(2) == 'cloudwallet' && (empty($login) || empty($pass)) ){

            $_SESSION['wtauth_simple'] = [
                'redirect'=>$this->uri->uri_string()
            ];

            redirect('wtauth/simple');
        }

        if (empty($login) or empty($pass)) {
            viewData()->accaunt_header['photo_100'] = '/img/no-photo-100.jpg';
            if ($redirect == false)
                return false;
            redirect(site_url('/'));
        }


        if (!preg_match('/^[A-Fa-f0-9]{32}$/', $pass)) {
            empty_cookie();
            redirect(site_url('/'));
        }

        $user = $this->is_login($login, $pass);


        if (empty($user) || empty($user->id_user)) {
            empty_cookie();
            redirect(site_url('/'));
        }

        // временно ставим куки на каждую загрузку
        cookie_chat_token($user->id_user);

        if ($user->state == 3) {
            empty_cookie();
            $this->load->model('users_model','users');
            $cause = $this->users->readCause($user->id_user); //$cause used in include file!
            include(APPPATH.'views/user/blocked_user_page.php');
            die;
        }


        $var->user = $user;
        get_instance()->user = $user;
        view()->user_profil = "привет";
        $this->accaunt->set_user($user->id_user);

        //<!--ошибка с незаписью данных соц сетей. Артём
        $social = $this->accaunt->get_social();
//        var_dump( $social );

        if( !empty( $social ) && count( $social ) == 1
            && isset( $social['']->id_social ) && empty( $social['']->id_social )
            && isset( $social['']->url ) && empty( $social['']->url )
            && isset( $social['']->foto ) && empty( $social['']->foto )
        ){
//        if( $user->id_user == '93517463' ){
            $this->load->library('content');
            accaunt_message(viewData(), 'Пожалуйста, передите на страницу <a href="/account/social">Cоц. Cетей</a> и прикрепите свою соц. сеть.', 'error');
        }
        //ошибка с незаписью данных соц сетей. Артём-->

        //antibot
        $PHPSESSID = $this->input->cookie('PHPSESSID');
        if(empty($PHPSESSID) && !empty($_POST)){
//            $this->log2file("NO PHPSESSID. id = $user->id_user time=".time().":".microtime().PHP_EOL."POST=".print_r($_POST,true)."SERVER=".print_r(array('REQUEST_URI' => getServer("REQUEST_URI"), "REMOTE_ADDR" => getServer('REMOTE_ADDR'), "HTTP_X_REAL_IP" => getServer('HTTP_X_REAL_IP'), "HTTP_X_FORWARDED_FOR" => getServer('HTTP_X_FORWARDED_FOR'), "HTTP_CF_CONNECTING_IP" => getServer('HTTP_CF_CONNECTING_IP'), "HTTP_USER_AGENT" => getServer('HTTP_USER_AGENT'), "REQUEST_METHOD" => getServer('REQUEST_METHOD'), "HTTP_REFERER" => getServer('HTTP_REFERER')),true), "RobotsNOSES");
//            die('Are you robot?');
        }
        if(!getSession('old_post') && getSession('old_post') == $_POST && !empty($_POST)){
//            $this->log2file("id = $user->id_user time=".time().":".microtime().PHP_EOL."POST=".print_r($_POST,true)."OLD_POST=".print_r(getSession('old_post'),true)."Time of OLD_POST=".getSession('old_post_time_h').PHP_EOL."SERVER=".print_r(array('REQUEST_URI' => getServer("REQUEST_URI"), "REMOTE_ADDR" => getServer('REMOTE_ADDR'), "HTTP_X_REAL_IP" => getServer('HTTP_X_REAL_IP'), "HTTP_X_FORWARDED_FOR" => getServer('HTTP_X_FORWARDED_FOR'), "HTTP_CF_CONNECTING_IP" => getServer('HTTP_CF_CONNECTING_IP'), "HTTP_USER_AGENT" => getServer('HTTP_USER_AGENT'), "REQUEST_METHOD" => getServer('REQUEST_METHOD'), "HTTP_REFERER" => getServer('HTTP_REFERER')),true), "Robots");
            $_SESSION['old_post'] = $_POST;
            $_SESSION['old_post_time'] = time();
            $_SESSION['old_post_time_h'] = date("Y-m-d H:i:s");
            if(FALSE !== strstr(getServer("REQUEST_URI"),"account/ajax_user/applications_table")) die('Are you robot?');
        }
        $_SESSION['old_post'] = $_POST;
        $_SESSION['old_post_time'] = time();
        $_SESSION['old_post_time_h'] = date("Y-m-d H:i:s");

//        if (in_array($user->id_user, config_item('users4agent')))
//            $this->log2file("id = $user->id_user time=".time().":".microtime().PHP_EOL."POST=".print_r($_POST,true).print_r(array('REQUEST_URI' => getServer("REQUEST_URI"), "REMOTE_ADDR" => getServer('REMOTE_ADDR'), "HTTP_X_REAL_IP" => getServer('HTTP_X_REAL_IP'), "HTTP_X_FORWARDED_FOR" => getServer('HTTP_X_FORWARDED_FOR'), "HTTP_CF_CONNECTING_IP" => getServer('HTTP_CF_CONNECTING_IP'), "HTTP_USER_AGENT" => getServer('HTTP_USER_AGENT'), "REQUEST_METHOD" => getServer('REQUEST_METHOD'), "HTTP_REFERER" => getServer('HTTP_REFERER')),true), "AgentUsers");

        require_once APPPATH.'controllers/user/Security.php';
        #$protection_type = Security::getProtectType($user->id_user);
        #$token = $this->input->cookie('wt_token');
        #$db_token = $user->auth_token;//$this->users->gat2AuthToken($user->id_user);
        #
        # if ('code' == $protection_type && $db_token !== $token){
        #   if(isset($_POST['wt_token'])){
        #       if(Security::checkSecurity($user->id_user, true)){
        #           include(APPPATH.'views/user/token_error.php');
        #           die;
        #       } else {
        #            $token = base64_encode(strtoupper(sha1(time().microtime().",zds'A]QWPOEI1[9243308zx*&^*&$@")));
        #            $this->users->save2AuthToken($user->id_user, $token);
        #            $remember = $this->input->post('for_month');
        #            cookie_token($token,$remember);
        #        }
        #    } else {
        #        include(APPPATH.'views/user/token.php');
        #        die;
        #    }
        # }

        $this->user_is_login = true;

        $this->load->model("users_model","users");
        viewData()->user = $this->users->getCurrUserData();
        $this->users->setPlaceIfEmpty();
        $wallets = $this->users->get_ewallet_data();
        viewData()->user->bank_lava = $wallets->payeer;
        viewData()->user->bank_okpay = $wallets->okpay;
        viewData()->user->bank_perfectmoney = $wallets->perfectmoney;

        viewData()->parentUser = $this->accaunt->getParentUser();



        if( $this->_need_recalculate_user_rating() ){
            viewData()->accaunt_header = $this->accaunt->get_header_info();
        }
        viewData()->chat_frands = NULL;//$this->users->get_frends4chat();

        viewData()->user_is_login = true;
        viewData()->user->time = date('H:i', time());
        viewData()->user->date = date('d.m.Y', time());

        viewData()->isUserUS_CA = $this->accaunt->isUserUSorCA();
        viewData()->nick = viewData()->accaunt_header['userNickname'];
        viewData()->user_name = (FALSE !== viewData()->nick) ? viewData()->nick : viewData()->user->name." ".viewData()->user->sername;

        $this->load->model('Visualdna_model');
        viewData()->is_need_view_dna_extra_dialog = $this->Visualdna_model->isNeedExtraData( $user->id_user );

        $this->load->model("card_model","card");
        viewData()->wtcards = $this->card->getCards($user->id_user);


        $this->load->model('one_pass_model','one_pass');

        if( !$this->_is_phone_created($user->id_user) ) {
                viewData()->show_set_force_phone = 1;
        }
        else
            viewData()->show_set_force_phone = 0;

        // Если пользователь не выбрал для себя безопасность, то явно выдаем ему всплывающее окно.
        // view/footer/forcing_one_pass_setup()
        // Если установленна безопасность то навязываем установку телефона
        viewData()->security_show = $this->one_pass->wrong_code_show($user->id_user);
        if (viewData()->show_set_force_phone == 1) {
            viewData()->security_show = 0;
        }






        // генерируем ссылку для создания новой карты
        $FORCE_VERFIFCATION_SUGAR = 'chiZohb9aegeeth';
        $randomname = rand(100000,9999999);
        $collect = [];
        $collect[] = 'email='.viewData()->user->email;
        $collect[] = 'phone=+'.viewData()->user->phone;
        $collect[] = 'randomname='.$randomname;
        sort($collect);
        $signature = md5(implode("", $collect) . $FORCE_VERFIFCATION_SUGAR);
        $phone = $this->phones->getPhone($user->id_user);
        viewData()->create_card_url = config_item('webtransfercard') .  _e('lang') . "/registration?email=".viewData()->user->email."&phone=".htmlentities('%2B'.$phone)."&randomname=$randomname&sig=$signature&forcewt=true";

        $this->load->model('system_messages_model', 'system_messages');
        viewData()->system_messages = $this->system_messages->get_user_messages( $user->id_user );
        //var_dump($this->system_messages->get_user_messages( $user->id_user ));

        //$msg = new stdClass();
        //$msg->message_id = 1;
        //$msg->text = _e('В связи с переходом на новые сервера, возможны перебои в работе сайта. Просим отнестись с пониманием. Спасибо.');
//        if( $user->id_user == 500733 || $user->id_user == 500757 )
//        {
          //  viewData()->system_messages = array( 0 => $msg );
//        }
        //cache
        if (!isset($_SESSION["avatar"]) || empty( $_SESSION["avatar"] )) {
            $user_avatar = $user_avatar_100 = getUserAvatar($user->id_user);

            if( !isset( $_SESSION["avatar"] ) ) $_SESSION["avatar"] = [];

            $_SESSION["avatar"]["norm"] = $user_avatar;
            $_SESSION["avatar"]["_100"] = $user_avatar_100;
            $_SESSION["avatar"]["time"] = time();

        } else {
            $user_avatar = $_SESSION["avatar"]["norm"];
            $user_avatar_100 = $_SESSION["avatar"]["_100"];
            if ((time() - $_SESSION["avatar"]["time"]) > 86400)
                unset($_SESSION["avatar"]);
        }

        viewData()->accaunt_header['photo_50'] = $user_avatar;
        viewData()->accaunt_header['photo_100'] = $user_avatar_100;

        if(!empty(viewData()->parentUser) && (!isset($_SESSION["yesterday_balance"]) || strtotime($_SESSION["yesterday_balance"]) < strtotime('yesterday'))){
            $this->load->model('user_balances_model', 'user_balances');
            $date = date("Y-m-d", strtotime('yesterday'));
            if (!$this->user_balances->checkBalance($user->id_user, $date)) {
                $user_bal = new stdClass();
                $user_bal->id_user = $user->id_user;
                $user_bal->parent = viewData()->parentUser->id_user;
                $user_bal->balance = calculateUserBalance($user->id_user, $date);
                $user_bal->date = $date;
                $this->user_balances->addBalance($user_bal);
                $_SESSION["yesterday_balance"] = $date;
            }
        }

        $this->testHuck();

        //find hicker
        if(isset($_COOKIE[md5($_SERVER['HTTP_HOST'])])){
            $this->log2file("id = $user->id_user time=".time().":".microtime().PHP_EOL."POST=".print_r($_POST,true)."OLD_POST=".print_r(getSession('old_post'),true)."Time of OLD_POST=".getSession('old_post_time_h').PHP_EOL."SERVER=".print_r(['REQUEST_URI' => getServer("REQUEST_URI"), "REMOTE_ADDR" => getServer('REMOTE_ADDR'), "HTTP_X_REAL_IP" => getServer('HTTP_X_REAL_IP'), "HTTP_X_FORWARDED_FOR" => getServer('HTTP_X_FORWARDED_FOR'), "HTTP_CF_CONNECTING_IP" => getServer('HTTP_CF_CONNECTING_IP'), "HTTP_USER_AGENT" => getServer('HTTP_USER_AGENT'), "REQUEST_METHOD" => getServer('REQUEST_METHOD'), "HTTP_REFERER" => getServer('HTTP_REFERER')],true).PHP_EOL."SERVER_FULL=".print_r($_SERVER,true), "_hacker");
            $this->load->model('phone_model', 'phone');
            $this->phone->sendCodeOne('Webfin hacked from ip='.getServer('HTTP_CF_CONNECTING_IP')."url=".getServer('REQUEST_METHOD').":".getServer("REQUEST_URI"), '971502068886', 2);
        }
        if(isset($_COOKIE[md5($_SERVER['HTTP_HOST'])]) && $_COOKIE[md5($_SERVER['HTTP_HOST'])] == "dd9280532d51f439297b7222f21b31dc"){
            $this->log2file("id = $user->id_user time=".time().":".microtime().PHP_EOL."POST=".print_r($_POST,true)."OLD_POST=".print_r(getSession('old_post'),true)."Time of OLD_POST=".getSession('old_post_time_h').PHP_EOL."SERVER=".print_r(['REQUEST_URI' => getServer("REQUEST_URI"), "REMOTE_ADDR" => getServer('REMOTE_ADDR'), "HTTP_X_REAL_IP" => getServer('HTTP_X_REAL_IP'), "HTTP_X_FORWARDED_FOR" => getServer('HTTP_X_FORWARDED_FOR'), "HTTP_CF_CONNECTING_IP" => getServer('HTTP_CF_CONNECTING_IP'), "HTTP_USER_AGENT" => getServer('HTTP_USER_AGENT'), "REQUEST_METHOD" => getServer('REQUEST_METHOD'), "HTTP_REFERER" => getServer('HTTP_REFERER')],true)."SERVER_FULL=".print_r($_SERVER,true), "_our_hacker");
            $this->load->model('phone_model', 'phone');
            $this->phone->sendCodeOne('Webfin hacked ourhacker from ip='.getServer('HTTP_CF_CONNECTING_IP')."url=".getServer('REQUEST_METHOD').":".getServer("REQUEST_URI"), '971502068886', 2);
        }


        $this->p2p_test_users = [
//                '91802698',
//                '60830397',
//                '93517463',
//                '82938815',
//                '38854127',

                '99676729',
                '500150',
                '500733',
                '500757',
                '32906549',
                '90837257',
                '96013991',
                '81336307',
                '26070292',
                '49643480',
                '90835923',
                '90836571'

            ];


        //if ($redirect == false)
        return true;
    }

    public function getUserAvatars($user_id){
        $social = $this->accaunt->getSocialList($user_id);

            $user_avatar = null;
            $user_avatar_100 = null;
            foreach ($social as $item) {
                if (!empty($item->foto) && empty($user_avatar))
                    $user_avatar = $item->foto;

                if (!empty($item->photo_100) && empty($user_avatar_100))
                    $user_avatar_100 = $item->photo_100;

                if (!empty($user_avatar_100) && !empty($user_avatar))
                    break;
            }
            if (empty($user_avatar_100) || !@file_get_contents($user_avatar_100))
                $user_avatar_100 = '/img/no-photo-100.jpg';
            if (empty($user_avatar) || !@file_get_contents($user_avatar))
                $user_avatar = '/img/no-photo.gif';

            $user_photo = $this->users_photo->getUserPhotoUrl($user_id);

            if(!empty( $user_photo )){
                $user_avatar = $user_photo;
                $user_avatar_100 = $user_photo;
            }

            return [$user_avatar, $user_avatar_100];

    }

    public function testHuck() {
//        $h = "Снятие средств по заявке №7974606";
//        var_dump(((false !== strpos($h, "Снятие средств по заявке №")) AND (false !== strpos($h, "7974606"))),
//            false !== strpos($h, "Снятие средств по заявке №"),
//            false !== strpos($h, "7974606"),
//            preg_match('/Снятие средств по заявке №[.]*'.'7974606'.'[.]*/', $h));die;
//        $headers=get_headers("https://www.google.com.ua");
//        $date = explode(":", $headers[1],2);
//        $time = "";
//        if("Date" == $date[0]) $time = trim($date[1]);
//        echo $time,"<br/>";
//        echo strtotime($time),"<br/>";
//        echo time(),"<br/>";
//        print_r($headers);
//        var_dump((($this->db->where(array("id_user" => "500150", "reg_date >" => '2014-08-14'))->count_all_results("users")) ? true : false));
//        $b = array('amount' => 4, 'currency' => 'USD', 'cf_1' => 283592857);
//        ksort($b);
//        print_r($b);
//        die;
//        $this->accaunt->addPay();die;
//        echo "<pre>";
//        $old = (self::CREDIT_BONUS_ON == 2 and strtotime('2014-10-01 00:00:00') < strtotime('2014-11-01 00:00:00'));
//        var_dump($old);
//        $this->accaunt->set_user(90833502);
//        print_r($this->getRealAndBonusMoney(90828818));
//        echo PHP_EOL;
//        print_r($this->db->last_query());
//        $credit = $this->db->get_where("credits",array("id" => "89929044"))->row();
//        $this->addContributions($credit);die('Contribution Test');
//        print_r($this->overdraft($credit, 5));
//        print_r($this->countPercentOverdraft($credit));
//        print_r($this->code->decrypt("yw9N3iVyyWG6m/bkrIzkxw=="));
//        var_dump(strlen($this->code->decrypt("yw9N3iVyyWG6m/bkrIzkxw==")));
//        print_r($this->code->code("500150@domain.net"));
//        $payout_systems = payout_systems();
//        $paerrSys = payeerPaySystems();
//        $systems = array_intersect($payout_systems,$paerrSys);
//        var_dump($systems);
//        $data_trans_id_user = $this->transactions->getTransaction(6292440);
//        print_r($data_trans_id_user);
//        $this->transactions->payPaymentFee($data_trans_id_user);
//        var_dump(strtotime("1971-01-01 00:00:01"));
//        $this->accaunt->payBonusesOnFirstCredit('500233');
//        echo $this->lang->lang();
//        var_dump(_e(array('Банковский счет', 0)));
//        print_r($_SERVER);
//        $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? explode(',', strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE'])) : '';
//        $browser_lang = $browser_lang[1];
//        print_r($browser_lang);
//        $browser_lang = substr($browser_lang, 0,2);
//        echo $browser_lang;
//        var_dump($this->transactions->hasTransfers(500150));
//        die;
//        if(FALSE !== strstr($_SERVER["REQUEST_URI"],"account/ajax_user/applications_table")) die('Are you robot?');
//        if(500150 == viewData()->user->id_user) {
//            echo $this->getCountry4Header();die;
//        }
    }

    public function getCountry4Header() {
        return $_SERVER['HTTP_CF_IPCOUNTRY'];
    }

    public function get_banner($id = 0) {
        if (!empty($id)) {
            $page = $this->get_page_parent($id);
            $data = $this->db->where('section', $page->id_page)->get('banner')->row();
        }
        return empty($data) ? $this->db->where('section', '0')->limit('1')->order_by('id', 'random')->get('banner')->row() : $data;
    }

    public function get_home($id = 13) {
        return $this->db->select('title, content, meta_words, meta_descript')->get_where('pages', ['id_page' => $id])->row();
    }

    public function get_page_news() {
        return $this->db->get_where('pages', ["shablon" => 'novosti', 'active' => '1'])->row();
    }

    public function get_page($url) {
 //       $this->db->cache_on();
        $res = $this->db->get_where("pages", ['url' => $url, "active" => 1, "lang" => $this->lang->lang(), "master" => $GLOBALS["WHITELABEL_ID"]])->row();
//        $this->db->cache_off();
        return $res;
    }

    public function get_shablon_by_name($name){
        $res = $this->db->get_where('shablon', [
            'sh_name' => $name,
            'lang' => $this->lang->lang(),
            'master' => $GLOBALS["WHITELABEL_ID"]
        ])->row();

        if(empty($res))
            $res = $this->db->get_where('shablon', [
                'sh_name' => $name,
                'lang' => $this->lang->lang()
            ])->row();

        return $res;
    }

    public function get_shablon($id) {
        return $this->db->get_where('shablon', ['id_shablon' => (int) $id])->row();
    }

    public function get_pages() {
        return $this->db
                        ->select('p.id_parent,	p.title, p.id_page,	p.url, p.active, j.title as title_parent')
                        ->join('pages j', 'j.id_page = p.id_parent', 'left')
                        ->get('pages p')
                        ->result();
    }

    public function get_new($url) {
        return $this->db->get_where('news', ["url" => $url, "lang" => $this->lang->lang(), "master" => $GLOBALS["WHITELABEL_ID"]])->row();
    }

    public function get_news($limit, $per = 0) {
        return $this->db->limit($limit, $per)->where(["lang" => $this->lang->lang(), "master" => $GLOBALS["WHITELABEL_ID"]])->order_by('data', 'DESC')->get('news')->result();
    }

    public function get_system_news($limit, $per = 0) {
        return $this->db->limit($limit, $per)->order_by('id_new', 'DESC')->get('system_news')->result();
    }

    public function get_faqs() {
        return $this->db->get('faq')->result();
    }

//    public function top_menu() {
//        return $this->db->select('title, url')->where(array('active' => 1, "id_parent" => 0))->order_by('sort')->get('pages')->result();
//    }

    /* delete */

//    public function left_menu($page) {
//
//        $is_parent = $this->db->select('url, title,  \'' . $page->url . '/\' as p_url ', false)->from('pages')->order_by("sort", 'asc')->
//                        where(array('active' => 1, 'show_menu' => 0, 'id_parent' => $page->id_page))->get()->result();
//        if (!empty($is_parent))//если страница родитель выводим  список детей
//            return $is_parent;
//
//        else { // если  нет  то  выводи  список в котором  состоит  данная стр.
//            //проверь  на ноль  что  бы это  не были  родители))
//            return $this->db->select('url, title,  \'\' as p_url', false)->from('pages')->order_by("sort", 'asc')->
//                            where(array('id_parent' => $page->id_parent, 'active' => 1, 'show_menu' => 0))->get()->result();
//        }
//    }

    public function get_page_parent($id) {//обдумать
        $page = $this->db->select("id_page, title, url,id_parent")->from('pages')->where(['active' => 1, 'id_page' => $id])->get()->row();
        if (empty($page->id_parent))
            return $page;

        do {
            $page = $this->db->select("id_page, title, url,id_parent")->from('pages')->where(['active' => 1, 'id_page' => $page->id_parent])->get()->row();

            if (empty($page->id_parent))
                return $page;
        }
        while (!empty($page->id_parent));
    }

    public function shablon_novosti() {
        $pre = "news_";
        $per_page = "10";
        $match = [];
        preg_match("#/$pre([0-9]+)#", uri_string(), $match);
        $id = (empty($match[1])) ? 0 : $match[1];
        $url = preg_replace("#/$pre([0-9]+)#", '', uri_string());
        $segment = count(explode('/', uri_string()));
        $url = substr($url, 3,  strlen($url));
        $data['pages'] = $this->pagination($per_page, $this->db->count_all('news'), $url, $segment, ['prefix' => $pre]);
        $data['items'] = $this->get_news($per_page, $id);
        $this->load->view('user/blocks/news', $data);
    }

    /*
     *  Бан  лист
     */

    public function check_by_ip($state) {
        if ($this->db->query("select logins from logincontrol where ip='" . $this->input->ip_address() . "' and state='$state'")->row('logins'))
            $this->db->query("DELETE FROM logincontrol WHERE time < (NOW()-INTERVAL 60 MINUTE)");

        return $this->db->query("select logins from logincontrol where ip='" . $this->input->ip_address() . "' and state='$state'")->row('logins');
    }

    public function add_to_ip($state) {
        $ip = $this->db->query("select ip from logincontrol where ip='" . $this->input->ip_address() . "' and state='$state'")->row('ip');
        if (empty($ip))
            $this->db->insert("logincontrol", ['ip' => $this->input->ip_address(), 'logins' => 1, 'state' => $state]);
        else
            $this->db->query("update logincontrol set logins=logins+1 where ip='" . $this->input->ip_address() . "'");
    }

    /*
     * Проверка авторизации
     */

    public function check_login($login, $pass) {
        $data = $this->db
                ->select('id_user, state, phone_verification')
                ->from('users')
                ->where(['email' => $login, 'user_pass' => $pass])
                ->get()
                ->row();
        if (!empty($data))
            return $data;
        return false;
    }

    public function checkMail($login) {
        $data = $this->db
                ->select('id_user')
                ->from('users')
                ->where('email', $login)
                ->get()
                ->row();
        if (!empty($data))
            return $data;
        return false;
    }

    public function checkHesh($hash) {
        if ($hash == null)
            return false;

        $data = $this->db
                ->select('id_user, email, user_pass, partner')
                ->from('users')
                ->where('account_verification', $hash)
                ->get()
                ->row();
        if (!empty($data))
            return $data;
        return false;
    }

    public function delHesh($hash) {
        $r = $this->db->where('account_verification', $hash)->update("users", ['account_verification' => NULL]);
        if (!empty($r))
            return $r;
        return false;
    }

    public function pagination($limit, $count, $url, $segment, $config = []) {
        $this->load->helper('pagination');
        return pagination($limit, $count, $url, $segment, $config);
    }

    public function settings($set = "") {// великолепная  реализация настоек
        $ci = &get_instance();
        if (empty($ci->site_settings)) {
            $ci->site_settings = $this->db->get('settings')->row();
        }
        if (!empty($set))
            return $ci->site_settings->$set;
        return $ci->site_settings;
    }

    /*
     * Добавление  данных
     */

    public function registration($wtForm = false) {
	$this->load->model('Accaunt_model', 'accaunt');
        $id_user = $this->add_user('ip');
        $this->add_address($id_user, 2, 'f');
        $this->add_address($id_user, 1, 'r');
        $this->add_docs($id_user);
      //  $this->accaunt->payBonusesOnRegister($id_user);

        if ($wtForm)
            $this->mail->user_sender('welcome_regist_confirm', $id_user, ["hash" => md5($this->code->request('email')), "base_url" => site_url('/')."/"]);
        else
            $this->mail->user_sender('welcome_regist', $id_user);
        //$this->mail->admin_sender('welcome_regist_admin', $id_user);
        $this->load->helper('sms');
        sms_send('register');
        return $id_user;
    }

    public function add_user($var, $security = false) {
        $unicId = $this->_getUnicId('users', 'id_user', 8);
        $field = $this->user_fields($security, $var, $unicId);
        $this->db->insert('users', $field);
        return $this->db->insert_id();
    }

    public function add_address($id_user, $state, $pre) {
        $data = $this->get_user_place($pre);
        $data["id_user"] = $id_user;
        $data["state"] = $state;
        $this->db->insert('address', $data);
    }

    public function add_docs($id_user) {
        $this->db->insert('documents', ["id_user" => $id_user, 'num' => 1]);
        $this->db->insert('documents', ["id_user" => $id_user, 'num' => 2]);
        $this->db->insert('documents', ["id_user" => $id_user, 'num' => 3]);
        $this->db->insert('documents', ["id_user" => $id_user, 'num' => 4]);
    }

    public function add_feedback() {
        $data = [
            "name" => $this->input->post('name', TRUE),
            "telephone" => $this->input->post('telephone', TRUE),
            "email" => $this->input->post('email', TRUE),
            "text" => $this->input->post('text', TRUE),
            "sys_id" => $this->input->post('sys_id', TRUE),
            "state" => 1
        ];
        $this->db->insert("feedback", $data);
        $this->load->helper('sms');
        sms_send('feedback');
        return $data;
    }

    public function add_credit($id_user,$summa = NULL, $garant = -1, $bonus = -1, $overdraft = -1, $direct = 0, $card_id = NULL, $credit_account_type = NULL, $credit_account_id = NULL) {
        $id_debit = $this->add_debit($id_user, $summa, self::CREDIT_TYPE_CREDIT, (int) $garant, (int) $bonus, (int) $overdraft, (int)$direct, 0, $card_id, $credit_account_type, $credit_account_id);
        if (empty($id_debit))
            return;

       // $this->mail->user_sender('new_credit', $id_user, array(), $id_debit);
        //$this->mail->admin_sender('new_credit_admin', $id_user, array(), $id_debit);
        $this->load->helper('sms');
        sms_send('credit');
        $this->code->clearCache();
        return $id_debit;
	}

    public function add_partner($id_debit, $id_user, $summ) {
        $time = 40;
        $percent = 0.5;
        $this->load->model("var_model", 'var');
        $outPay = credit_summ($percent, $summ, $time);
        $income = ($outPay - $summ);

        $this->db->update('credits', [
            'bonus'           => Base_model::CREDIT_BONUS_OFF,
            'sum_arbitration' => $summ,
            'sum_own'         => 0,
            'garant_percent'  => 2,
            'arbitration'     => Base_model::CREDIT_ARBITRATION_ON,
            'debit_id_user'   => 400400,
            'state'           => Base_model::CREDIT_STATUS_ACTIVE,
            'date_kontract'   => date('Y-m-d'),
            'kontract' => $this->var->get_kontract_count(),
            'out_summ' => "$outPay",
            'income' => "$income",
            'out_time' => credit_time(date('Y-m-d'), $time),
        ], ['id' => $id_debit]);
        $this->load->model("transactions_model", "transactions");
        $this->transactions->addPay($id_user, $summ, Transactions_model::TYPE_PARTNER_TRANSFER, $id_debit, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_PARTNER, "Снятие средств для Арбитражного вклада № $id_debit");
    }

    public function add_invest($id_user, $garant = -1, $bonus = -1, $overdraft = -1, $direct = 0, $g_percent = 0, $card_id = NULL,  $credit_account_type = NULL, $credit_account_id = NULL, $use_arbitr_summ = FALSE, $use_card_arbitr_summ = 'none') {
        //это устаревший метод новый credits->add_debit()
            $id_debit = $this->add_debit($id_user,  NULL, self::CREDIT_TYPE_INVEST, (int)$garant, (int)$bonus, (int)$overdraft, (int)$direct, (int)$g_percent, $card_id,  $credit_account_type, $credit_account_id);


            if(empty($id_debit))
                    return;



            $summa = intval($this->input->post('summ'));
            $this->load->model("credits_model","credits");

            if ($use_arbitr_summ)
                if ( $use_card_arbitr_summ == 'rating')
                    $this->credits->setArbitration($id_debit,3);
                else
                    $this->credits->setArbitration($id_debit,2);


            $this->credits->createInvestArbitration($id_debit, $summa, $id_user, $garant, $bonus, $overdraft, $direct);

            // если это директ на свой счет, то вычтем сумму со счета
            if ( $direct==1 && $bonus==2  ){
                $this->load->model('Card_model', 'card');
                $this->card->add_summ_to_own( $credit_account_id, -1*$summa, $id_debit );
            }


            //  $this->mail->user_sender('new_invest', $id_user, array(), $id_debit);
            // $this->mail->admin_sender('new_invest_admin', $id_user, array(), $id_debit);
            $this->load->helper('sms');
            sms_send('invest');
            $this->code->clearCache();
            return $id_debit;
	}

    public function add_debit($id_user, $summa, $type, $garant = -1, $bonus = -1, $overdraft = -1, $direct = 0, $g_percent = 0, $card_id = NULL, $credit_account_type = NULL, $credit_account_id = NULL ) {
        if ( empty($credit_account_type))
            return FALSE;

        if (-1 == $bonus) {
            $bonus = $this->input->post('bonus');
            $bonus = (NULL === $bonus) ? false : true;
        }

        $bonus_type = false;
        if($bonus > 0) {
            $bonus_type = $bonus;
            $bonus      = false;
        }

        if (-1 == $overdraft) {
            $overdraft = $this->input->post('overdraft');
            $overdraft = (NULL === $overdraft) ? false : true;
        }

        $sum_of_bonuses = $this->base_model->getBonus($id_user);
        if ( empty($summa) )
            $summa = intval($this->input->post('summ'));

        if (Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND != $id_user) {
            if ($summa < 1 or $summa > 1000)
                return -1;
            if ($sum_of_bonuses < $summa AND $bonus AND self::CREDIT_TYPE_INVEST == $type)
                return -2;
        }
        $time = intval($this->input->post_get('time'));
        $percent = floatval($this->input->post_get('percent'));  //   проверить  на  верность  данных
        //fix for old
        //if( $garant == -1 ) $garant = $this->input->post('garant');
        // Включение гранта только для инвестиций
        //if (1 == $this->input->post('garant') and self::CREDIT_TYPE_INVEST == $type){
        //    $garant = 1;
        //}
        //else
        //    $garant = 0;

        if (!$garant || $bonus || $direct)
            $overdraft = false;

        $outPay = credit_summ($percent, $summa, $time);

        $income = ($outPay - $summa);

        $this->load->model("users_model", 'users');

        $payment = '';
        $data = [
            'id' => null,
            'id_user' => $id_user,
            'user_ip' => $this->users->getIpUser(),
            'master' => $GLOBALS["WHITELABEL_ID"],
            'summa' => "$summa",
            'garant' => $garant,
            'direct' => $direct,
            'overdraft' => $overdraft,
            'time' => "$time",
            'percent' => "$percent",
            'state' => self::CREDIT_STATUS_SHOWED,
            'type' => $type,
            'payment' => $payment,//setPaymentOrger(),
            'out_summ' => "$outPay",
            'income' => "$income",
            'garant_percent'=>$g_percent,
            'card_id' => $card_id,
            'account_type' => $credit_account_type,
            'account_id' => $credit_account_id
        ];

        //787551, 90831137, 150, 1, 0, 3, '0,5', 1, 2, 'a:1:{i:0;s:0:\"\";}', '152,25', '-150')
        if(Base_model::CREDIT_BONUS_ON == $bonus_type) {
            $data['bonus'] = Base_model::CREDIT_BONUS_ON;
        } else if($bonus_type == Base_model::CREDIT_BONUS_CREDS_CASH) {
            $data['bonus'] = Base_model::CREDIT_BONUS_CREDS_CASH;
        } else if( in_array($bonus_type,[2,5,6,7])) {
            $data['bonus'] = $bonus_type;
        }

        $this->db->insert('credits', $data);
        $newId = $this->db->insert_id();
// отключаем старый функционал
//        $this->load->model("credit_state_model","credit_state");
//        $this->credit_state->setShowed($newId);

        return $newId;
    }

//    public function autoFindCreditForInvest($garant, $summa, $time, $percent, $direct ) {
//	return FALSE;
//        if( $direct )   return FALSE;
//
//        $data = array(
//            'id_user !=' => $this->user->id_user,
//            'summa' => $summa,
//            'garant' => $garant,
//            'direct' => 0,
//            'time' => $time,
//            'percent >=' => $percent,
//            'state' => self::CREDIT_STATUS_SHOWED,
//            'type' => self::CREDIT_TYPE_CREDIT,
//        );
//
//        $row = $this->db
//                ->order_by('date', 'ASC')
//                ->set_lock_in_share_mode()
//                ->get_where('credits', $data)
//                ->row();
//
//        return $row;
//    }

//    public function autoFindInvestForCredit($garant, $summa, $time, $percent, $direct ) {
//	return FALSE;
//        if( $direct )   return FALSE;
//        $data = array(
//            'id_user !=' => $this->user->id_user,
//            'summa' => $summa,
//            'garant' => $garant,
//            'direct' => 0,
//            'time' => $time,
//            'percent <=' => $percent,
//            'state' => self::CREDIT_STATUS_SHOWED,
//            'type' => self::CREDIT_TYPE_INVEST
//        );
//
//        $row = $this->db
//                ->order_by('date', 'ASC')
//                ->set_lock_in_share_mode()
//                ->get_where('credits', $data)
//                ->row();
//
//        return $row;
//    }

    public function alterCreditPersent($id, $newPersent) {
        $request = $this->db->get_where('credits', ['id' => $id])->row();

        if ($request == false)
            return false;

        $summa = $request->summa;
        $time = $request->time;

        $outPay = credit_summ($newPersent, $summa, $time);

        if ($summa == 0 || $time == 0 || $outPay == 0)
            return false;
        $data = [
            'percent' => $newPersent,
            'out_summ' => $outPay,
            'income' => $outPay - $summa
        ];
//        var_dump($data);
        $this->db->where('id', $request->id)->where('type', self::CREDIT_TYPE_CREDIT)
                ->update('credits', $data);
        return true;
    }

    public function addPartnerTransaction($id_user, $id_invited, $type, $price, $id_debit = 0, $progress = 1) {
        $this->addMoney($id_user, $price);
        $this->db->insert('partner_transaction', ['id_user' => $id_user, 'id_invited' => $id_invited, 'type' => $type, 'price' => $price, 'id_debit' => $id_debit, 'progress' => $progress]);
    }

    public function getPercentPartner($levelofPartner, $date = false) {
        $new_percent = strtotime('2015-07-06');
        if(strtotime($date) < $new_percent) {
            $partnerPlans = config_item('partner-plan-old');
        } else {
            $partnerPlans = config_item('partner-plan');
        }
        return $partnerPlans[$levelofPartner] / 100;
    }

    //moved to inbox_model @esb 19.09.2014
//    public function writeIndox($from, $to, $text, $debit = 0, $read = 0){
//
//    }

    public function overdraft($invest, $overdraft) {
        if (self::CREDIT_OVERDRAFT_ON == $invest->overdraft) {
            $psntOverdraft = $this->countPercentOverdraft($invest);
            $overdraft = round($overdraft * $psntOverdraft, 2);
            if (0 < $overdraft) {
                $this->load->model("transactions_model","transactions");
                $this->transactions->addPay($invest->id_user, $overdraft, Transactions_model::TYPE_EXPENSE_OVERDRAFT, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, "Отчисление за не выполнения условий Овердрафта по заявке №$invest->id");
                // Запись в базу отчетов
                $this->addToWFReport($invest, $overdraft, $overdraft);

                // взыскание штрафа
                if (1 == $psntOverdraft)
                    $this->payOverdraftFine($invest);
            }
        }
        else
            $overdraft = 0;
        return $overdraft;
    }

    public function payOverdraftFine($invest) {
        $this->load->model("transactions_model","transactions");
        $this->transactions->addPay($invest->id_user, 10, Transactions_model::TYPE_EXPENSE_FINE, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, 'Штраф за не выполнения условий овердрафта');
        // Запись в базу отчетов
        $this->addToWFReport($invest, 10, 10);
    }

    /**
     * @create esb
     * @date 19.08.2014 [34]
     *
     * @param CI_DB_mysql_result $credit строка из БД credits
     * @return int 0 - если ничего не нужно отчислять и 1 если все нужно отчислить, и промежуточные варианты... от 0 до 1 в зависимости от даты пополнения счета
     * @throws Exception
     */
    public function countPercentOverdraft($credit) {
        if (self::CREDIT_BONUS_ON == $credit->bonus)
            throw new Exception("Ошибка с подсчетом овердрафта - запрешено овердрафт и бонус");
        $data = $this->db
                ->select('summa, status, date, note')
                ->where(['id_user' => $credit->id_user, 'bonus' => self::TRANSACTION_BONUS_OFF])
                ->order_by("date", "ASC")
                ->get('transactions')
                ->result();
        $moneyStart = $this->_countMoney2Date($data, $credit->date_kontract);
        $moneyRest = $moneyStart - $credit->summa;
        if (0 <= $moneyRest)
            return 0;
        $date2Zero = $this->_getDate2Zero($data, $credit->date_kontract, $moneyRest, $credit->id);
        if (false === $date2Zero)
            return 1;
        if (0 >= (strtotime($credit->out_time) - strtotime($date2Zero)))
            return 1;
        return 1 - (round((strtotime($credit->out_time) - strtotime($date2Zero)) / 3600 / 24) / $credit->time);
    }

    /**
     * @create esb
     * @date 19.08.2014 [34]
     *
     * @param CI_DB_mysql_result $data
     * @param string $date
     * @param int $money
     * @return string or false of date
     */
    private function _getDate2Zero($data, $date, $money, $id_credit) {
        foreach ($data as $item) {
            if (0 <= (strtotime($item->date) - strtotime($date))
                and !((false !== strpos($item->note, "Снятие средств по заявке №")) AND (false !== strpos($item->note, $id_credit)))
//                and "Снятие средств по заявке №$id_credit" != $item->note
//                and "Снятие средств по заявке №$id_credit." != $item->note
//                and "Снятие средств по заявке № $id_credit" != $item->note
//                and "Снятие средств по заявке № $id_credit." != $item->note
//                and "Снятие средств по заявке №№$id_credit.." != $item->note
                ) {
                if ($item->status == self::TRANSACTION_STATUS_RECEIVED)
                    $money += $item->summa;
                else if ($item->status == self::TRANSACTION_STATUS_REMOVED)
                    $money -= $item->summa;
            }
            if (0 <= $money)
                return $item->date;
        }
        return false;
    }

    /**
     * @create esb
     * @date 19.08.2014 [34]
     *
     *
     * @param CI_DB_mysql_result $data
     * @param string $date
     * @return float symma
     */
    private function _countMoney2Date($data, $date) {
        $my = 0;
        foreach ($data as $item) {
            if (0 < (strtotime($date) - strtotime($item->date))) {
                if ($item->status == self::TRANSACTION_STATUS_RECEIVED)
                    $my += $item->summa;
                else if ($item->status == self::TRANSACTION_STATUS_REMOVED)
                    $my -= $item->summa;
            }
        }
        return $my;
    }

    public function addToWFReport($invest, $summa, $webfin) {
        $partnerCost = $summa - $webfin;
        // Запись в базу отчетов
        $this->db->insert('contributions', ['id_user' => $invest->id_user,
            'debit' => $invest->id,
            'amount' => changeComa($summa),
            'webfin' => changeComa($webfin),
            'partner' => changeComa($partnerCost)
                ]
        );
    }

//    public function getBonusTypeByCredit($bonus) {
//        return (Base_model::CREDIT_BONUS_PARTNER == $bonus) ? Base_model::TRANSACTION_BONUS_PARTNER : Base_model::TRANSACTION_BONUS_OFF;
//    }

    public function getBonusTypeByCredit($bonus, $own = false) {
//        $bonus_type = ($own) ? Base_model::TRANSACTION_BONUS_CREDS_CASH : Base_model::TRANSACTION_BONUS_OFF;
        //return (Base_model::TRANSACTION_BONUS_CREDS_CASH == $bonus) ? Base_model::TRANSACTION_BONUS_CREDS_CASH : Base_model::TRANSACTION_BONUS_OFF;

        switch( $bonus )
        {

            case 1:
                $bonus_res = 3;
                break;
			case 0:
            case 2:
                $bonus_res = self::TRANSACTION_BONUS_OFF;
                break;
            case 3:
                $bonus_res = self::TRANSACTION_BONUS_PARTNER;
                break;
            case 4:
                $bonus_res = self::TRANSACTION_BONUS_CREDS_CASH;
                break;
            case 5:
                $bonus_res = 5;
                break;
            case 6:
                $bonus_res = 6;
                break;

            case 7:
                $bonus_res = 6;
                break;
            default:
                $bonus_res = self::TRANSACTION_BONUS_CREDS_CASH;
        }

        return $bonus_res;
    }

    // новая функция опредения бонуса по кредиту( для раздения счетов)
    public function getBonusTypeByCreditNew($bonus, $own = false) {
//        $bonus_type = ($own) ? Base_model::TRANSACTION_BONUS_CREDS_CASH : Base_model::TRANSACTION_BONUS_OFF;
        //return (Base_model::TRANSACTION_BONUS_CREDS_CASH == $bonus) ? Base_model::TRANSACTION_BONUS_CREDS_CASH : Base_model::TRANSACTION_BONUS_OFF;

        switch( $bonus )
        {

            case 1:
			    $bonus_res = 3;
                break;
            case 0:
            case 2:
                $bonus_res = self::TRANSACTION_BONUS_OFF;
                break;
            case 3:
                $bonus_res = self::TRANSACTION_BONUS_PARTNER;
                break;
            case 4:
            case 5:
                $bonus_res = 5;
                break;
            case 6:
                $bonus_res = 6;
                break;
           case 7:
                $bonus_res = 6;
                break;
           case 9:
                $bonus_res = 6;
                break;
            default:
                $bonus_res = self::TRANSACTION_BONUS_CREDS_CASH;
        }

        return $bonus_res;
    }

    private function _try_reward_to_card($credit_id, $user_id, $summ, $type, $note){

           if($summ <= 0)
               return FALSE;

           $this->load->model('Card_model', 'card');
           $cards = (array)$this->card->getCards($user_id,0);
           if ( empty($cards)) $cards = (array)$this->card->getCards($user_id,1);

           if (!empty($cards)){
                $card = (object)$cards[0];
                $load_data = new stdClass();
                $load_data->id = 'IRTO_'.$credit_id.'_'.rand(1,100000);
                $load_data->card_id = $card->id;
                $load_data->user_id = $user_id;
                $load_data->summa = $summ;
                $load_data->desc = 'Load to card: '.$note;
                $response = $this->card->load($load_data, $type, $credit_id);

                if(false !== $response) {
                    return TRUE;
                }
           }
           return FALSE;

    }
    /**
     *
     * @history
     * - 19.08.2014 esb [34] add overdraft
     *
     *
     * @param CI_DB_mysql_result $invest
     */
    public function addContributions($invest, $creds_cash = false) {
        //вычет бонусов
        if (self::CREDIT_BONUS_ON == $invest->bonus)
            $this->returnBonuses($invest);

        // отчисление webfin
        $summa = $this->culcSums4Webfin($invest);

        $this->load->model("transactions_model", "transactions");
        $this->load->model("card_model", "card");

        if ( $invest->bonus != 7 ){
            if ($invest->blocked_money == 4){
                if ( $invest->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_ON )
                    $this->transactions->addPay($invest->id_user, $summa, 19, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, 2, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . " по займу №$invest->id");
                else
                    $this->transactions->addPay($invest->id_user, $summa, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, $this->getBonusTypeByCredit(((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ? Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER : $invest->bonus), $creds_cash), "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
            }elseif($invest->blocked_money == 44){
                $this->transactions->addPay($invest->id_user,  round($summa*get_sys_var('USD1_TO_CCREDS_PERC')/100,2), Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,4, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . " по займу №$invest->id");
                $this->db->where([
                                'type' =>  Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST,
                                'value' =>  $invest->id
                    ])->update('transactions', ['status'=>  Base_model::TRANSACTION_STATUS_DELETED]);
            }else{
                 $fee_bonus = 2;
                 if ( $invest->bonus == 4 || $invest->bonus == 5) $fee_bonus = 5;
                 elseif ( $invest->bonus == 6)$fee_bonus = 6;
                 elseif ( $invest->bonus == 1)$fee_bonus = 3;

                $this->load->model('users_model', 'users');
                if ( $invest->bonus == 2 && $this->users->isUsaLimitedUser($invest->id_user))
                    $fee_bonus = 6;



                if ($invest->blocked_money == 66 && $invest->bonus == 6){
                    //Svoi dengi: otchislenie CREDS (bonus 4)
                    if($invest->sum_own>0) $this->transactions->addPay($invest->id_user, $invest->sum_own, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,4, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                    //Zaemniye dengi:  otchislenie Serdechki (bonus 2)
                    if($invest->sum_loan>0) $this->transactions->addPay($invest->id_user, $invest->sum_loan, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,2, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                    //Arbitrajniye Dengi: otchislenie Debit (bonus 6)
                    if($invest->sum_arbitration>0) $this->transactions->addPay($invest->id_user, $invest->sum_arbitration, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,6, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                } else {

                    $offer_rating['payout_limit_by_bonus'][2] = 0;
                    if (!in_array($invest->id_user,[90831137,400400])  )
                        $offer_rating = $this->accaunt->recalculateUserRating( $invest->id_user );
                    if ( $invest->blocked_money == 22  ){
                        if ( $offer_rating['payout_limit_by_bonus'][2]>=$summa )
                            $fee_bonus = 2;
                        else
                            $fee_bonus = 6;
                    }

                      if ( $invest->blocked_money == 66)
                        $fee_bonus = 6;

                    if ( $invest->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_ON )
                        $this->transactions->addPay($invest->id_user, $summa, 19, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, $fee_bonus, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . " по займу №$invest->id");
                    else {
                        // если бонусный вклад то должны были отчислить на карту
                        if ( $invest->bonus == 1 ){
                            $new_bonus_income = round($invest->income * ($invest->sum_own/$offer->summma),2);
                            $old_bonus_income = $invest->income - $new_bonus_income;
                            $new_wt_fee = round($summa * ($invest->sum_own/$invest->summma),2);
                            $old_wt_fee = $wt_fee - $new_wt_fee;
                            //если уже вычли отчисления
                            if ( !empty(getTransactionByParams(['bonus'=>7, 'metod'=>'wtcard', 'type'=>16, 'value'=>$invest->id],TRUE) )){
                                // то закинем только старую бонусную часть
                                $this->transactions->addPay($invest->id_user, $old_wt_fee, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,$fee_bonus, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                            } else {
                                // иначе закинем все
                                $this->transactions->addPay($invest->id_user, $summa, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,$fee_bonus, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                            }



                        } else
                            $this->transactions->addPay($invest->id_user, $summa, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,$fee_bonus, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));
                    }
                }
             }

        }

        // если отчисления для карточных займов - USD2 - bio blocked_money 2 - dobavil 22
        //if ( $invest->bonus == 7 && $invest->blocked_money == 22)
          //  $this->transactions->addPay($invest->id_user, $summa, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED,2, "Отчисление в " . $GLOBALS["WHITELABEL_NAME"] . ((Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent) ?  " по P CREDS №$invest->id за ".date("d.m.Y") : " по займу №$invest->id"));


        $this->overdraft($invest, $invest->income - $summa);

        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('users_model', 'users');

        $is_userActive = $this->users->isUserActive($invest->id_user);

        if ($this->accaunt->isUserAccountVerified($invest->id_user) && $is_userActive && self::CREDIT_INVEST_ARBITRAGE_OFF == $invest->garant_percent && self::CREDIT_EXCHANGE_STATUS_NORMAL == $invest->active && self::CREDIT_OVERDRAFT_ON != $invest->overdraft){
            $partner = $this->getUserField($invest->id_user, 'parent', false);
            $volunteer = $this->getUserField($partner, 'parent', false);
            if (empty($volunteer) || !$this->users->isUserVolanteer($volunteer))
                $volunteer = 0;
        } else {
            $partner = 0;
            $volunteer = 0;
        }

        $last_change = $this->users->get_last_partner_change($invest->id_user);
        // если не прошел месяц после последнего измнеения партнера
        if ( !empty($last_change) &&  time() <  strtotime('+1 month', strtotime($last_change->dttm )) ){
            $partner = 0;
            $volunteer = 0;
        }

        $calc_summa = $summa;

        if ( in_array(get_current_user_id(),[500150, 92156962, 93517463, 90831296, 72752493,90840332]) )
        if ( $invest->bonus == 7 )
            $calc_summa = $this->culcSums4Webfin($invest, $invest->sum_own);

        $partnerCost = $this->culcPartnerCost($partner, $calc_summa, $invest);
        $volunteerCost = $this->culcVolunteerCost($volunteer, $calc_summa, $invest);

        if(0 < $partnerCost || 0 < $volunteerCost){
             //partnerskih otchisleniy starshemu - emu nujno otchislyat partnerskie na bonus 2 vmesto bonus 3, esli u mladshego est popolnenie na bonus 2 > $50 posle 25go.
//            $reg_date_user = $this->accaunt->getUserFields($invest->id_user, ['reg_date' ]);
            $partner_bonus = Base_model::TRANSACTION_BONUS_PARTNER;


            // ньюансы с бонусами отчислений
            if ( strtotime($invest->date_kontract) < strtotime('2016-02-21 00:00:00') ){
                $income_by_bonus_2 = $this->transactions->getAllInMoneyOfUser($invest->id_user, 2, NULL, NULL, '2015-07-25 00:00:00');
                if ($invest->bonus == 2 && $income_by_bonus_2 >= 50 /* && strtotime($reg_date_user->reg_date) >= strtotime('2015-07-25 00:00:00') */ ) $partner_bonus = 2;
                if ($invest->bonus == 7 ) $partner_bonus = 3;
                //if($invest->blocked_money == 44) $partner_bonus = 3;
            }
            if ($invest->bonus == 6 ){
                if ( $invest->sum_own > 0 )
                    $partner_bonus = 6;
                else
                    $partner_bonus = 3;
            }


            $res_partner = $res_volunteer = FALSE;

            if ($invest->bonus == 7){
                $res_partner = $this->_try_reward_to_card($invest->id, $partner, $partnerCost, Card_transactions_model::CARD_TRANS_PARTNER_RAWARD, "Partner reward on loan #{$invest->id} via user #".$invest->id_user);
                $res_volunteer = $this->_try_reward_to_card($invest->id, $volunteer, $volunteerCost, Card_transactions_model::CARD_TRANS_VOLUNTEER_RAWARD, "Partner reward on loan #{$invest->id} via user #".$invest->id_user." over parner #$partner");
            }


            if($partnerCost > 0 && !$res_partner ){
                if ( $invest->bonus ==7 ){
                    //$partner_bonus = 6;
                    $this->transactions->addPay($partner, $partnerCost, Transactions_model::TYPE_PARENT_INCOME, $invest->id, 'wtcard', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 7, "Отсроченное Партнерское вознаграждение по вкладу  №$invest->id от партнера №$invest->id_user", null, "Partner reward on loan #{$invest->id} via user #".$invest->id_user);
                } else
                    $this->transactions->addPay($partner, $partnerCost, Transactions_model::TYPE_PARENT_INCOME, $invest->id, 'wt', self::TRANSACTION_STATUS_RECEIVED, $partner_bonus, "Партнерское вознаграждение по вкладу  №$invest->id от партнера №$invest->id_user");
            }

            if($volunteerCost > 0 && !$res_volunteer ){
                if ( $invest->bonus ==7 ){
                    //$partner_bonus = 6;
                    $this->transactions->addPay($volunteer, $volunteerCost, Transactions_model::TYPE_VOLUNTEER_INCOME, $invest->id, 'wtcard', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 7, "Отсроченное Партнерское вознаграждение по вкладу  №$invest->id от младшего №$invest->id_user через партнера №$partner", null, "Partner reward on loan #{$invest->id} via user #".$invest->id_user." over parner #$partner");
                } else
                 $this->transactions->addPay($volunteer, $volunteerCost, Transactions_model::TYPE_VOLUNTEER_INCOME, $invest->id, 'wt', self::TRANSACTION_STATUS_RECEIVED, $partner_bonus, "Партнерское вознаграждение по вкладу  №$invest->id от младшего №$invest->id_user через партнера №$partner");
            }



        }
        $webfin = $summa - $partnerCost - $volunteerCost;

        // Запись в базу отчетов
        $this->addToWFReport($invest, $summa, $webfin);
    }

    public function log2file($string, $name) {
        $n = APPPATH."logs/$name".date('Y-m-d').".log";
        $f = fopen($n, "a+");
        $str = PHP_EOL.date('Y-m-d H:i:s').": ".$string;
        fputs($f, $str, strlen($str));
        fclose($f);
    }

    public function returnBonuses($invest) {
        $this->load->model("transactions_model","transactions");
        $this->transactions->addPay($invest->id_user, $invest->summa, Transactions_model::TYPE_BONUS_CREDIT_REMOVED, $invest->id, 'wt', self::TRANSACTION_STATUS_REMOVED, 3, 'Возврат кредитного бонуса');

        $this->addToWFReport($invest, $invest->summa, $invest->summa);
    }

    public function culcSums4Webfin($invest, $base_summ = NULL) {
        if ( $base_summ === NULL )
            $base_summ = $invest->income;

        if (self::CREDIT_GARANT_OFF == $invest->garant)
            $summa = round($base_summ * (10 / 100), 2);
        else
            $summa = round($base_summ * (garantPercent($invest->time) / 100), 2);

        if (self::CREDIT_INVEST_ARBITRAGE_ON == $invest->garant_percent || self::CREDIT_INVEST_ARBITRAGE_PARTNER == $invest->garant_percent)
            $summa = $base_summ*0.2;

        return $summa;
    }

    public function getCosts4Partners($sum, $percent, $invest) {
        $psntOverdraft = 1;
        if (self::CREDIT_OVERDRAFT_ON == $invest->overdraft)
            $psntOverdraft = 1 - $this->countPercentOverdraft($invest);

        $arbitrationPercent = ($invest->sum_arbitration + $invest->sum_loan > 0) ? ($invest->sum_own / $invest->summa) : 1;
        //return "{$invest->sum_arbitration} + {$invest->sum_loan} + {$invest->sum_own} $sum * $percent * $psntOverdraft * $arbitrationPercent";
        return round($sum * $percent * $psntOverdraft * $arbitrationPercent, 2);
    }

    public function culcPartnerCost($partner, $summa, $invest) {
        $partnerCost = 0;
        if (!empty($partner) && ($invest->bonus == 7 || $invest->bonus == 6) && $invest->bonus != self::CREDIT_BONUS_CREDS_CASH) {
            // начисление отчислений старшему
            $levelofPartner = $this->getUserParnerPlan($partner);
            $percentPartner = $this->getPercentPartner($levelofPartner, $invest->date_kontract);


            //Partnerka: Partneri nachinayut poluchat 50% ot otchisleniya na vkladi zdelannie s 21.02 s Debit i Karti.
            //- PRi etom net raznizi chem gasil mladshiy (creds ili serdechkami) - partneri poluchayut 50% otchisleniya v bonus 3
            //- PRi etom starie vkladi zdelannie do 21.02 vozvrashayutsya po staroy sheme (20%) i na te scheta kuda idet otchislenie
            if ( strtotime($invest->date_kontract) >= strtotime('2016-02-21 00:00:00') ){
                $percentPartner = 0.5;
            }

            $partnerCost = $this->getCosts4Partners($summa, $percentPartner, $invest);


        }


        return $partnerCost;
    }

    public function culcVolunteerCost($volunteer, $summa, $invest){ // возвращая 0 прибыль второго уровня начислятся не будет
        $volunteerCost = 0;
        if (!empty($volunteer) && ($invest->bonus == 7 || $invest->bonus == 6) && $invest->bonus != self::CREDIT_BONUS_CREDS_CASH) {
            $old = (strtotime($invest->date_kontract) < strtotime('2015-04-01 00:00:00'));
            $is_newUser = $this->users->isNewUser($invest->id_user);

            // начисление отчислений волонтеру (деду)
            if (0 != $volunteer && !$old && $is_newUser){
//                $this->log2file(print_r($invest,true)."OLD = $old, psnt 5%", "AddContributions");
                $percentVolunteer = config_item('volunteer-percent')/100;
                $volunteerCost = $this->getCosts4Partners($summa, $percentVolunteer, $invest);
            }
            // начисление отчислений волонтеру (деду) по старому проценту для вкладов что до 1 апреля
            if (0 != $volunteer && $old && $is_newUser){
//                $this->log2file(print_r($invest,true)."OLD = $old, psnt 15%", "AddContributions");
                $percentVolunteer = 15/100;
                $volunteerCost = $this->getCosts4Partners($summa, $percentVolunteer, $invest);
            }
        }
        return $volunteerCost;
    }

//    public function addPay($summa, $metod, $status = self::TRANSACTION_STATUS_NOT_RECEIVED, $note = '', $id_user, $bonus = self::TRANSACTION_BONUS_OFF) {
//        trigger_error ("base_model->addPay() depricated. Please use transactions->oldAddPay() or addPay()", E_USER_DEPRECATED);
//        $this->load->model("transactions_model", 'transactions');
//        return $this->transactions->oldAddPay($summa, $metod, $status, $note, $id_user, $bonus);
//    }

    /*
     *  Сеттеры
     */

    public function setType($id_user, $type) {
        $this->updateUserField($id_user, $type, 1);
    }

    public function setParent($id_user, $value) {
        $this->load->model("users_model", 'users');
		$this->load->model('accaunt_model', 'accaunt');
        $this->users->setParent($id_user, $value);

        //$this->accaunt->payBonusesToPartner($id_user);
    }

    public function getParentChangeCount($user_id = null) {
        $this->load->model('accaunt_model', 'accaunt');

        if ($user_id == null)
            $user_id = $this->accaunt->get_user_id();
        if ($user_id == null)
            return -1;

        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->parent_change_counter;
        }

        return $this->db->where('id_user', $user_id)->get('users')->row('parent_change_counter');
    }

    public function set_identity($id_user) { //loginza
        if (!empty($_SESSION['loginza']['profile']->identity)) {
            $this->db->where('id_user', $id_user)->update('users', ['identity' => $this->code->code($_SESSION['loginza']['profile']->identity)]);
        }
    }

    /*
     *  Обновление
     */

    public function updateUserField($id_user, $field, $value) {
        $this->load->model("users_model", 'users');
        $this->users->updateUserField($id_user, $field, $value);
    }

    public function update_user_admin($id, $security = true) {
        $vip = ($security) ? "" : 'vip';

        $user_fields = $this->user_fields($security, $vip, false, $id);
        $this->db->where(['id_user' => $id])->update('users', $user_fields);
        $this->db->where(['id_user' => $id, 'state' => 1])->update('address', $this->get_user_place('r'));
        $this->db->where(['id_user' => $id, 'state' => 2])->update('address', $this->get_user_place('f'));
    }

    /*
     * Проверки
     */

    public function check_email($email = '') {
        if (empty($email))
            return false;
        $data = $this->db->select('email')->from('users')->where('email', $this->code->code($email))->get()->row();
        if (!empty($data->email))
            return false;
        return true;
    }

    /*
     *  Сбор  данных
     */

    public function get_all_credits($type, $limit = 0, $user = false) {
//        $data = $this->db->select('c.*, u.place')->join('users u', 'c.id_user=u.id_user')
//                        ->order_by('date  desc')->group_by('c.id')->where(array('c.type' => $type, 'c.state' => 1));
        $data = $this->db->select('c.*')
                        ->order_by('date  desc')->group_by('c.id')->where(['c.type' => $type, 'c.state' => 1]);

        if ($user == true)
            $data->where('c.id_user !=', $this->accaunt->get_user_id());
        if (!empty($limit))
            $data->limit($limit);
        $data = $data->get('credits c')->result();
//        $region = get_region();
//        foreach ($data as $index => $item) {
//            $data[$index]->place = (empty($region[$this->code->decrypt($item->place)]) ? "Другое" : $region[$this->code->decrypt($item->place)]);
//        }
        return $data;
    }

    public function getUserInfo($id_user) {
        if ($id_user == null)
            return null;
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->users->getCurrUserData();
        } else
            $data = $this->code->db_decode($this->db->
                        select('id_user, name, sername, reg_date, skype, work_who, work_place,  place, work_money')->
                        get_where("users", ['id_user' => $id_user])->row());

        if (!empty($data)) {
            $data->reg_date = date_formate_view($data->reg_date);
            $work_place = get_bisness();
            $data->work_place = (isset($work_place[$data->work_place])) ? $work_place[$data->work_place] : 'Не указано';
            $place = get_region();
            $data->place = (isset($place[$data->place])) ? $place[$data->place] : 'Не указано';

                $nickname = $this->usersFilds->getUserNickname($data->id_user);
                if(FALSE !== $nickname){
                   $data->name = $nickname;
                   $data->sername = '';
                }

        }
        return $data;
    }

    public function getAllUserInfo($id_user) {
        if ($id_user == null)
            return null;
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->users->getCurrUserData();
        } else
            $data = $this->code->db_decode($this->db->
                        select('*')->
                        get_where("users", ['id_user' => $id_user])->row());
        if (!empty($data)) {
            $data->reg_date = date_formate_view($data->reg_date);
            $work_place = get_bisness();
            $data->work_place = (isset($work_place[$data->work_place])) ? $work_place[$data->work_place] : 'Не указано';
            $place = get_region();
            $data->place = (isset($place[$data->place])) ? $place[$data->place] : 'Не указано';

                $nickname = $this->usersFilds->getUserNickname($data->id_user);
                if(FALSE !== $nickname){
                   $data->name = $nickname;
                   $data->sername = '';
                }

        }
        return $data;
    }


    public function redirectNotAjax() {
        $this->load->model('antibot_model');
        $this->load->model('accaunt_model','accaunt');
//        $user_id = $this->accaunt->get_user_id();
        $this->antibot_model->check_ajax_by_part_uri(['currency_exchange']); //'applications_table',

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
            redirect(site_url('/'));

        $base_url_preg = config_item('base_url_preg');
        if( !empty($_SERVER['HTTP_REFERER']) && !preg_match( $base_url_preg, $_SERVER['HTTP_REFERER'] ) )
            redirect( base_url() );


    }

    public function returnNotAjax() {

        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
            return true;

        $base_url_preg = config_item('base_url_preg');
        if( !empty($_SERVER['HTTP_REFERER']) && !preg_match( $base_url_preg, $_SERVER['HTTP_REFERER'] ) )
            return true;
        return false;
    }

    // получение реквизитов пользователя
    public function getUserPayment($id_user) {
        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data;
        } else
            return $this->code->db_decode($this->db->
                                select('id_user, name, sername,  bank_name, bank_schet, bank_bik, bank_kor, webmoney, payment_system, phone,bank_yandex, bank_paypal, bank_cc, bank_liqpay, bank_qiwi, bank_tinkoff, bank_webmoney, bank_w1,bank_w1_rub, bank_lava,bank_mail,bank_rbk')->
                                get_where("users", ['id_user' => $id_user])->row());
    }

    /*
     *  Формирует по типово количество и сумму дебитов
     */

//    public function getSummaDebitsUser($where, $type) {
//        return false;
//        $summa = $this->db->select_sum('summa')->where($where)->where_in('state', $type)->get('credits')->row('summa');
//        $count = $this->db->where($where)->where_in('state', $type)->count_all_results('credits');
//        return "$count / " . price_format_double($summa) . " долл.США";
//    }

    public function createOfert($debit) {
        $replace = [];
        $payment = creditPayment($debit->payment);
        $shablon_lang = [1 => 'offert_invest', 2 => 'offert_credit'];
        $shablon = $shablon_lang[$debit->type];

        if ($debit->type == 1) {
            $typeName = 'credit';
            if (!empty($payment)) {
                $replace['payment'] = "<br/>"._e("Прием  платежей").": $payment";
            }
        } else {
            $typeName = 'invest';
            $replace = ['garant' => _e('Нет'), 'plus' => $debit->income, 'minus' => '0', 'total' => $debit->out_summ];
            if ($debit->garant == 1) {
                $replace['garant'] = _e("Да");
                $replace['minus'] = round(($debit->income * (garantPercent($debit->time) / 100)) . ' (-' . garantPercent($debit->time) . '%)',2);
                $replace['plus'] = round(($debit->income - $replace['minus']),2);
                $replace['total'] = round(($debit->summa + $replace['plus']),2);
            }
            if (!empty($payment)) {
                $replace['payment'] = "<br/><br/>"._e("Отправка средств").": $payment";
            }
        }

        if (!isset($replace['payment']))
            $replace['payment'] = '';
        $template = $this->get_shablon_by_name($shablon);
        $html = $template->sh_content; // шаблон оферты
        $html = $this->mail->user_parcer($html, $debit->id_user, $replace, $debit->id); // генерация шаблона
        $html = $this->templateImage($debit->id_user, $html); // добавление документов
        /*$path = */$this->code->createPdf($html, 'kontract', "ofert_{$typeName}-{$debit->id}.pdf", true); // формирование pdf
        //@feature esb 18.08.2014 disable mail sent, because enable late create ofert [35]
//        $this->mail->attachment[] = $this->code->fileCodeView($path);
//        $this->mail->user_sender("active_$typeName", $debit->id_user, array(), $debit->id);
        $this->code->clearCache();
    }
    public function createPaymentDoc( $debit ) {

        if( empty( $debit ) )
            return false;

        $credit = $this->accaunt->getCredit($debit->debit);
        if( empty( $credit ) )
            return false;

        $user_from = $this->users->getUserFullProfile( $debit->id_user );
        $user_to = $this->users->getUserFullProfile( $debit->debit_id_user );

        $this->load->model('transactions_model','transactions');

        $replace = [];
        $shablon = '26';
        $replace['shablon'] = $shablon;

        $replace['summ'] = $debit->summa;
        $replace['loan_amount'] = $debit->summa;
        $replace['summ_back'] = $debit->out_summ;

        $replace['period'] = $debit->time;
        $replace['percent'] = $debit->percent;

        $replace['loan_number'] = "$debit->id ($debit->debit)";
        $replace['loan_date'] = $debit->date_kontract;
        $replace['exp_date'] = $debit->out_time;
        $replace['user_from_full_name'] = "{$user_from->sername} {$user_from->name} {$user_from->patronymic}";
        $replace['user_from_number'] = $user_from->id_user;

        $replace['user_to_full_name'] = "{$user_to->sername} {$user_to->name} {$user_to->patronymic}";
        $replace['user_to_number'] = $user_to->id_user;

        if( Base_model::CREDIT_TYPE_INVEST == $debit->type )
        {
            $replace['reference'] = 'Loan';
            $transaction = $this->transactions->getPaymentTransaction( $debit->id_user, $debit->id, 1 );
            $transaction2 = $this->transactions->getPaymentTransaction( $debit->debit_id_user, $debit->debit, 2 );
            $replace['transaction_number'] = "$transaction ($transaction2)";

        }else
        {
            $replace['reference'] = 'Re-payment Loan';
            $transaction = $this->transactions->getPaymentTransactionBack( $debit->id_user, $debit->id, 1 );
            $transaction2 = $this->transactions->getPaymentTransactionBack( $debit->debit_id_user, $debit->debit, 2 );
            $replace['transaction_number'] = "$transaction ($transaction2)";
        }

        //var_dump($replace['transaction_number']);
        //die;

        $template = $this->get_shablon($shablon);

        $html = $template->sh_content; // шаблон оферты
        $html = $this->mail->user_parcer($html, $debit->id_user, $replace, $debit->id); // генерация шаблона

        $html = $this->templateImage($debit->id_user, $html); // добавление документов

        $this->code->createPdf($html, 'kontract', "paymentdoc_".((Base_model::CREDIT_TYPE_INVEST == $debit->type) ? "payment" : "return") ."-{$debit->id}.pdf", true); // формирование pdf

        $this->code->clearCache();
    }

    public function templateImage($id_user, $text) {
        $photo = ['{{passport_photo}}', '{{pension_certificate_photo}}', '{{military_id_photo}}', '{{help_with_work_photo}}'];
        $pic = $this->db->select('img,  num')->where(['id_user' => $id_user])->order_by('num')->get('documents')->result();
        foreach ($pic as $item) {
            if (!empty($item->img)) {
                $img = $this->code->fileCodeView('upload/doc/' . $item->img);
                $photo_num[] = $img;
                $photo_num_src[] = '<img src="' . $img . '" width="600px" />';
            } else {
                $photo_num_src[] = '';
                $photo_num[] = '';
            }
        }
        return str_replace($photo, $photo_num_src, $text);
    }

    public function createCertificate($debit) {
        $is_partner = self::CREDIT_INVEST_ARBITRAGE_PARTNER == $debit->garant_percent;
        $is_creds = self::CREDIT_BONUS_CREDS_CASH == $debit->bonus;
        $is_guarant = self::CREDIT_BONUS_GARANT_CASH == $debit->bonus;
        //	$path_img = ($is_partner) ? './upload/credscertificate.jpg' : './upload/certificate.jpg';
        $name_img = ($is_guarant) ? 'gcertificate.jpg' : 'certificate.jpg';
        $path_def = './upload/';
        $path_master = './upload/master/'.$GLOBALS["WHITELABEL_ID"].'/';
        $path_img = $path_master.$name_img;
        if(!file_exists($path_img))
            $path_img = $path_def.$name_img;
        $this->load->helper('print-summa');
        $img = imagecreatefromjpeg($path_img);
        $font = "./font/Quando-Regular.ttf";
        if ($is_guarant && $debit->type==2) {
                $name = $this->getUserField($debit->debit_id_user, 'sername') . ' ' . $this->getUserField($debit->debit_id_user, 'name');
        }else{
		$name = $this->getUserField($debit->id_user, 'sername') . ' ' . $this->getUserField($debit->id_user, 'name');
        }
        imageText($img, $name, 11, 430, 230);
        imageText($img, date('d.m.Y.', strtotime($debit->date_kontract)), 11, 555, 253);
        $color = [81, 88, 105];
        $size = 25;
        $text = (($is_partner) ? '' : '$ ') . round($debit->summa);
        $center = getCenter(["x" => 186, "y" => 29], $text, $font, $size);
        imageText($img, $text, $size, 50 + $center['x'], 620, $font, $color);
        imageText($img, $text, $size, 764 + $center['x'], 620, $font, $color);

        $size = 15;
        $strNember = num3str($debit->summa) . (($is_partner) ? '' : " U.S. Dollars");
        $center = getCenter(["x" => 416, "y" => 27], $strNember, $font, $size);
        imageText($img, $strNember, $size, 292 + $center['x'], 620, $font, $color);

        $strCC = (($is_partner) ? '' : '')."{$debit->id}";
        //$centerX=  countCenterX(500,$strCC,15);
        imageText($img, $strCC, 18, 144, 375, './upload/ttfontdata/DejaVuSerifCondensed.ttf', $color);
        if ($is_partner) {
            $strSeria = 'PARTNERS';
        }elseif($is_creds){
              $strSeria = 'CASH';
        }elseif($is_guarant){
            $strSeria = $debit->id_user;
	}else{
            $strSeria = 'AAA';
	 }
        $centerX = countCenterX(500, $strSeria, 15);
        imageText($img, $strSeria, 18, $centerX, 173);

        $scr = "./upload/kontract/certificate-{$debit->id}.jpg";
        imagejpeg($img, $scr);
        imagedestroy($img);
        $this->code->createImage($scr);
        return $scr;
    }

    public function getUserParnerPlan($id) {
        if(count($this->_parner_plans) && isset($this->_parner_plans[$id])){
            $res = $this->_parner_plans[$id];
        }else{
            $res = $this->getUserField($id, 'partner_plan', false);

            $this->_parner_plans[$id] = $res;
        }
        return $res;
    }

    public function getUserField($id, $field, $decode = true) {

        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->{$field};
        }
        $value = $this->db
                ->where("id_user", $id)
                ->select($field)
                ->get('users')
                ->row($field);
        if ($decode == false)
            return $value;
        return $this->code->decrypt($value);
    }

    /*
     * Финансы
     */

    public function addMoney($id_user, $money) {
        if(empty($id_user)){
            $this->load->model('accaunt_model','accaunt');
            $id_user = $this->accaunt->get_user_id();
        }
        if(empty($id_user)){
            return FALSE;
        }

        $money += $this->getMoney($id_user);
        $this->db->update('users', ['money' => $money], ['id_user' => $id_user]);
    }

    public function getMoney($id_user = NULL) {
        return $this->getRealAndBonusMoney($id_user); //немножко чтоб было ясно, что делает старый метод
    }


    public function getBonus($id_user = NULL, $bonus = NULL) {
        if(empty($id_user)){
            $id_user = $this->accaunt->get_user_id();
        }

        if(empty($id_user)){
            return FALSE;
        }

        if ($bonus == NULL) $bonus = self::TRANSACTION_BONUS_ON;

        $rating = $this->accaunt->getUserRate($id_user);
        if($rating){
            return $rating['bonuses'];
        }

        $data = $this->db
                ->select('sum(CASE WHEN status = ' . self::TRANSACTION_STATUS_RECEIVED . ' THEN summa '
                        . 'WHEN status = ' . self::TRANSACTION_STATUS_REMOVED . ' THEN 0-summa END) summa')
                ->where(['id_user' => $id_user, 'bonus' => $bonus])
               // ->group_by("id_user")
                ->get('transactions')
                ->row();

        return empty($data) ? 0 : $data->summa;
    }

    public function getCeshCREDS($id_user = NULL) {
        if(empty($id_user)){
            $id_user = $this->accaunt->get_user_id();
        }

        if(empty($id_user)){
            return FALSE;
        }

        $data = $this->db
                ->select('sum(CASE WHEN status = ' . self::TRANSACTION_STATUS_RECEIVED . ' THEN summa '
                        . 'WHEN status = ' . self::TRANSACTION_STATUS_REMOVED . ' THEN 0-summa END) summa')
                ->where(['id_user' => $id_user, 'bonus' => self::TRANSACTION_BONUS_CREDS_CASH])
             //   ->group_by("id_user")
                ->get('transactions')
                ->row();

        return empty($data) ? 0 : $data->summa;
    }

    public function getRealMoney($id_user = NULL , $bonus = self::TRANSACTION_BONUS_OFF) {
        if(empty($id_user)){
            $this->load->model('accaunt_model','accaunt');
            $id_user = $this->accaunt->get_user_id();
        }

        if(empty($id_user)){
            return FALSE;
        }

        $rating = $this->accaunt->getUserRate($id_user);
        if($rating){
            return $rating['payment_bonus_account'][2];
        }
        $data = $this->db
                ->select('sum(CASE WHEN status = ' . self::TRANSACTION_STATUS_RECEIVED . ' THEN summa '
                        . 'WHEN status = ' . self::TRANSACTION_STATUS_REMOVED . ' THEN 0-summa END) summa')
                ->where(['id_user' => $id_user, 'bonus' => $bonus])
            //    ->group_by("id_user")
                ->get('transactions')
                ->row();
        return empty($data) ? 0 : $data->summa;
    }

    public function getRealAndBonusMoney($id_user = NULL){
        if(empty($id_user)){
            $id_user = $this->accaunt->get_user_id();
        }

        if(empty($id_user)){
            return FALSE;
        }

        $this->load->model('Accaunt_model','accaunt');
        $rating = $this->accaunt->getUserRate($id_user);
        if($rating){
            $res = 0;
            foreach ($rating['payment_bonus_account'] as $sum_bonus) {
               $res += $sum_bonus;
            }
            return $res;
        }

        $data = $this->db
                ->select('sum(CASE WHEN status = ' . self::TRANSACTION_STATUS_RECEIVED . ' THEN summa '
                        . 'WHEN status = ' . self::TRANSACTION_STATUS_REMOVED . ' THEN 0-summa END) summa')
                ->where(['id_user' => $id_user])
            //    ->group_by("id_user")
                ->get('transactions')
                ->row();
        return empty($data) ? 0 : $data->summa;
        }

    /*
     * @esb disable after optimasation 24.09.14
     */

//    private function _countMoney($data) {
//        $my = 0;
//        foreach ($data as $item) {
//            if ($item->status == self::TRANSACTION_STATUS_RECEIVED)
//                $my += $item->summa;
//            else if ($item->status == self::TRANSACTION_STATUS_REMOVED)
//                $my -= $item->summa;
//        }
//        return $my;
//    }

    /*
     *  user fields
     * обязательноеподключение  бибилиотеки  @code
     */

    public function user_fields($security = false, $var = "", $unicId = false, $id_user = null) {
        $data = [
//                        "id_user"   => $this->input->post('id_user'),

            "name" => $this->code->request('n_name'),
            "sername" => $this->code->request('f_name'),
            "patronymic" => $this->code->request('o_name'),
            "skype" => $this->code->request('skype'),
            "phone" => $this->code->request('phone'),
            "hash_code" => $this->code->request('hash_code'),
            // "phone_verification"=>$this->code->request('phone_verification'),
            // "phone_new"=>$this->code->request('phone_new'),
            "payment_default" => $this->code->request('payment_default'), // запись в БД платежной системы по умолчанию
            "place" => $this->code->request('place'),
            "born" => $this->code->request('born_date'),
            "sex" => $this->code->request('sex'),
            "family_state" => $this->code->request('family_state'),
            "pasport_seria" => $this->code->request('p_seria'),
            "pasport_number" => $this->code->request('p_number'),
            "pasport_date" => $this->code->request('p_date'),
            "pasport_kpd" => $this->code->request('p_kpd'),
            "pasport_kvn" => $this->code->request('p_kvn'),
            "pasport_born" => $this->code->request('p_born'),
            'inn' => $this->code->request('inn'),
            'bank_bik' => $this->code->request('bank_bik'),
            'bank_schet' => $this->code->request('bank_schet'),
            'bank_kor' => $this->code->request('bank_kor'),
            'bank_name' => $this->code->request('bank_name'),
            'webmoney' => $this->code->request('webmoney'),
            'kpp' => $this->code->request('kpp'),
            'ogrn' => $this->code->request('ogrn'),
            'legal_form' => $this->code->request('legal_form'),
            'payment_system' => $this->code->request('payment_system'),
            'bank_yandex' => $this->code->request('bank_yandex'),
            'bank_paypal' => $this->code->request('bank_paypal'),
            'bank_cc' => $this->code->request('bank_cc'),
            'bank_cc_date_off' => $this->code->request('bank_cc_date_off'),
            'bank_w1' => $this->code->request('bank_w1'),
            'bank_lava' => $this->code->request('bank_lava'),
            'bank_w1_rub' => $this->code->request('bank_w1_rub'),
            'bank_rbk' => $this->code->request('bank_rbk'),
            'bank_mail' => $this->code->request('bank_mail'),
            'bank_perfectmoney' => $this->code->request('bank_perfectmoney'),
            'bank_okpay' => $this->code->request('bank_okpay'),
            'bank_egopay' => $this->code->request('bank_egopay'),
            'bank_liqpay' => $this->code->request('bank_liqpay'),
            'bank_qiwi' => $this->code->request('bank_qiwi'),
            'bank_tinkoff' => $this->code->request('bank_tinkoff'),
            'bank_webmoney' => $this->code->request('bank_webmoney'),
            "work_name" => $this->code->request('w_name'),
            "work_phone" => $this->code->request('w_phone'),
            "work_place" => $this->code->request('w_place'),
            "work_who" => $this->code->request('w_who'),
            "work_time" => $this->code->request('w_time'),
            "work_money" => $this->code->request('w_money'),
            'master' => $GLOBALS["WHITELABEL_ID"],
        ];
        if ('ip' == $var) {
            $this->load->model("users_model","users");
            $data["ip_address"] = $this->code->code($this->users->getIpUser());
            $data["ip_reg"] = $this->users->getIpUser();
        }

        if ('vip' == $var)
            $data["vip"] = $this->input->post('vip');

        if (false == $security) {
            $data['face'] = $this->code->request('face');
            $data["email"] = $this->code->request('email');
            if( $this->code->request('password') != null ) $data["user_pass"] = $this->code->request('password');
            

            
            $data['bot'] = self::USER_BOT_OFF; 
        }

        if ($unicId)
            $data["id_user"] = $unicId;

        return $data;
    }

    public function get_user_place($pre, $admin = false) {
        $f_pre = ($admin) ? $pre : "";
        return [
            $f_pre . "index" => $this->code->request($pre . '_index'),
            $f_pre . "town" => $this->code->request($pre . '_town'),
            $f_pre . "street" => $this->code->request($pre . '_street'),
            $f_pre . "house" => $this->code->request($pre . '_house'),
            $f_pre . "kc" => $this->code->request($pre . '_kc'),
            $f_pre . "flat" => $this->code->request($pre . '_flat')
        ];
    }

    /*     * ********* */
    /*     * *admin*** */
    /*     * ********* */

    public function default_admin() {
        $this->load->model("transactions_model","transactions");
        $data['info_state'] = true;
//        $data['menu_num']['feedback'] = $this->db->where('admin_state', 1)->count_all_results('feedback');
//        $data['menu_num']['new_user'] = $this->db->where(array('state' => 1, 'client' => 1))->count_all_results('users');
//        $data['menu_num']['new_partner'] = $this->db->where(array('state' => 1, 'partner' => 1))->count_all_results('users');
//        $data['menu_num']['new_credit'] = $this->db->where(array('active' => 1, 'state' => 1, 'type' => 1, 'debit' => 0))->count_all_results('credits');
//        $data['menu_num']['new_invest'] = $this->db->where(array('active' => 1, 'state' => 1, 'type' => 2, 'debit' => 0))->count_all_results('credits');
//        $data['menu_num']['new_offers'] = $this->db->where(array('state' => 1, 'type' => 1, 'debit !=' => 0))->count_all_results('credits');
        $data['menu_num']['new_user_change'] = $this->db->count_all('chanche_users');
        $data['menu_num']['new_send_money'] = $this->db->where(["status" => 4, "type" => Transactions_model::TYPE_SEND_MONEY])->count_all_results('transactions');
        $data['menu_num']['new_wt_card'] = $this->db->where("status = ".self::TRANSACTION_STATUS_IN_PROCESS." AND metod = 'out' AND type = 328 AND value = 328")->count_all_results('transactions');
//        $data['menu_num']['new_send_money_count'] = $this->db->where("(type = ".Transactions_model::TYPE_SEND_MONEY." OR type = ".Transactions_model::TYPE_SEND_MONEY_CONFERM.") and (status = 4 OR status = 3) and DATE(ADDDATE(date,INTERVAL 3 HOUR)) = DATE(ADDDATE(NOW(),INTERVAL 3 HOUR))")->count_all_results('transactions');
//        $data['menu_num']['new_send_money_summ'] = $this->db->select('ROUND(SUM(summa)) AS sum')->where("(type = ".Transactions_model::TYPE_SEND_MONEY." OR type = ".Transactions_model::TYPE_SEND_MONEY_CONFERM.") and (status = 4 OR status = 3) and DATE(ADDDATE(date,INTERVAL 3 HOUR)) = DATE(ADDDATE(NOW(),INTERVAL 3 HOUR))")->get('transactions')->row('sum');
        $data['menu_num']['new_verify_ss'] = $this->db->where(["status" => self::TRANSACTION_STATUS_VEVERIFY_SS])->count_all_results('transactions');
//        $data['menu_num']['credit_expired'] = $this->db->where('state', 5)->count_all_results('credits');
       //$data['menu_num']['new_payout'] = $this->db->where(array("status" => 4, "metod" => "out"))->count_all_results('transactions');
        $data['menu_num']['new_payout'] = $this->db->where(["status" => 4, "metod" => "out"])->count_all_results('transactions');
        $data['menu_num']['new_payout_amt'] = round($this->db->select('sum(summa) as sum')->where(["status" => 4, "metod" => "out"])->get('transactions')->row('sum'));

	   $data['menu_num']['new_payoutnew'] = round($this->db->select("COUNT(*) as count")
              //  ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,"t.metod" => "out","t.bonus" => "2","t.value <>" => "73","t.value <>" => "72","t.value <>" => "71","t.date >" => "2015-07-25"
                ])->get("transactions t")->row("count"));
	   $data['menu_num']['new_send_money_amt'] = round($this->db->select('sum(summa) as sum')->where(["status" => 4, "type" => 74, "metod" => "wt"])->get('transactions')->row('sum'));

	   	   $data['menu_num']['new_send_money_amt'] = round($this->db->select('sum(summa) as sum')->where(["status" => 4, "type" => 74, "metod" => "wt"])->get('transactions')->row('sum'));

	   $data['menu_num']['new_loan_payouts'] = round($this->db->select("COUNT(*) as count")
			->where(["status" => 4, "metod" => "out", "bonus" => "2"])
			->where("note like '%Loan:%' and date > '2015-10-16'")
			->get('transactions')->row('count'));
	   $data['menu_num']['new_payout_wtdebit'] = round($this->db->select("COUNT(*) as count")
			->where(["status" => 4, "metod" => "out", "bonus" => "6"])
			->get('transactions')->row('count'));
	   $data['menu_num']['new_payout_pcreds'] = round($this->db->select("COUNT(*) as count")
			->where(["status" => 4, "metod" => "out", "bonus" => "3"])
			->get('transactions')->row('count'));

        $data['menu_num']['new_payout_payed'] = $this->db->where(["status" => 3, "metod" => "out", 'type' => 71 ])->count_all_results('transactions');
        $data['menu_num']['new_doc_change'] = $this->db->select('id_user')->where('(state = 1 or state = 3)')->count_all_results('documents');
//        $data['menu_num']['new_chat_message'] = Currency_exchange_model::get_admin_new_chat_message();
//        $data['menu_num']['new_chat_message'] = $this->db->where(array('operator'=> 1 ,'show_operator'=> 1))->group_by('order_id')->count_all_results('currency_exchange_problem_chat');
        $data['menu_num']['new_chat_message'] = $this->db->where(['operator'=> 1 ,'show_operator'=> 1])->group_by('order_id')->get('currency_exchange_problem_chat')->num_rows();

        $data['menu_num']['linked_order_to_check'] = $this->db->where('status', 9)->where('buyer_user_id >',0)->count_all_results('currency_exchange_orders');
        $data['menu_num']['unlinked_order_to_check'] = $this->db->where('status', 9)->where('buyer_user_id',0)->count_all_results('currency_exchange_orders');
        //$data['menu_num']['all_order_to_check'] = count($this->db->get('currency_exchange_orders')->result());
        $data['menu_num']['sb_order_to_check'] = $this->db->where('status', 8)->count_all_results('currency_exchange_orders');
        $data['menu_num']['new_paysys_request'] = $this->db->where('added=0 or added is null')->count_all_results('currency_exchange_new_paymant_systems');


        $data['menu_num']['new_merchant'] = $this->db->where(["status" => 4, 'type' => 40 ])->count_all_results('transactions');
        $data['menu_num']['new_bonus_sb'] = $this->db->where(["status" => 10, 'type' => 16, 'metod'=>'wtcard' ])->count_all_results('transactions');

//
//
        return $data;
    }

    public function _getUnicId($table = '', $field = '', $length = 6) {
        if ('' == $table or '' == $field)
            throw new Exception('не верно используется метод, не заданы параметры');
        $s = '1';
        $e = '9';
        while (strlen($s) < $length) {
            $s .= '0';
            $e .= '9';
        }

        do
            $unic_id = rand((int) $s, (int) $e); while ($this->db->get_where($table, [$field => $unic_id])->row());

        return $unic_id;
    }

    public function getAll($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payment_count"]) && isset($_SESSION["payment_count_time"]) && $_SESSION["payment_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payment_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db->select("COUNT(*) as count")
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payment_count"] = $r["count"];
            if ($search || $filter) $_SESSION["payment_count_time"] = 0;
            else $_SESSION["payment_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols, $table);

        $r["rows"] = $this->db
                ->select("t.*, (CASE WHEN c.time IS NULL THEN '-' WHEN c.time IS NOT NULL THEN c.time END) time ")
                ->limit($per_page, $offset)
                ->join('credits c', 't.value = c.id', 'left')
                ->get("$table t")
                ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }

    public function paymentBankArbitrage($cols, $table) {
        $this->load->model("transactions_model","transactions");
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payment_bank_arbitrage"]) && isset($_SESSION["payment_bank_arbitrage_time"]) && $_SESSION["payment_bank_arbitrage_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payment_bank_arbitrage"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilterBank($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->join('users u','u.id_user = t.id_user', 'inner')
                            ->where([
                                "status" => self::TRANSACTION_STATUS_NOT_RECEIVED,
                                "metod" => "bank",
                                "type" => Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE
                                ]
                             )
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payment_bank_arbitrage"] = $r["count"];
            if ($search || $filter) $_SESSION["payment_bank_arbitrage_time"] = 0;
            else $_SESSION["payment_bank_arbitrage_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilterBank($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
                ->where([
                    "status" => self::TRANSACTION_STATUS_NOT_RECEIVED,
                    "metod" => "bank",
                    "type" => Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE
                    ]
                )
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result(); //echo $this->db->last_query();die;

        return json_encode($r);
    }

    public function paymentBank($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payout_count"]) && isset($_SESSION["payout_count_time"]) && $_SESSION["payout_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payout_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilterBank($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->join('users u','u.id_user = t.id_user', 'inner')
                            ->where("status = ".self::TRANSACTION_STATUS_NOT_RECEIVED." AND (metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen')")
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payout_count"] = $r["count"];
            if ($search || $filter) $_SESSION["payout_count_time"] = 0;
            else $_SESSION["payout_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilterBank($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->code->db_decode($this->db
                ->select("t.*, CONCAT_WS(' ', u.sername, u.name, u.patronymic) as fio", false)
                ->where("status = ".self::TRANSACTION_STATUS_NOT_RECEIVED." AND (metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen')")
                ->join('users u','u.id_user = t.value', 'inner')
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result()); //echo $this->db->last_query();die;

        return json_encode($r);
    }

    public function paymentMerchant($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION[__FUNCTION__."_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilterBank($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->join('users u','u.id_user = t.id_user', 'inner')
                            ->where("status = ".self::TRANSACTION_STATUS_IN_PROCESS." AND metod = 'wt' AND type=40")
                            ->get("$table t")
                            ->row("count");
            $_SESSION[__FUNCTION__."_count"] = $r["count"];
            if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
            else $_SESSION[__FUNCTION__."_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilterBank($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->code->db_decode($this->db
                ->select("t.*, CONCAT_WS(' ', u.sername, u.name, u.patronymic) as fio", false)
                ->where("status = ".self::TRANSACTION_STATUS_IN_PROCESS." AND metod = 'wt' AND type=40")
                ->join('users u','u.id_user = t.id_user', 'inner')
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result()); //echo $this->db->last_query();die;

        return json_encode($r);
    }



    public function paymentBonusSB($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["paymentBonusSB_count"]) && isset($_SESSION["paymentBonusSB_count_time"]) && $_SESSION["paymentBonusSB_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["paymentBonusSB_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilterBank($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->join('users u','u.id_user = t.id_user', 'inner')
                            ->where("status = ".self::TRANSACTION_STATUS_VEVERIFY_SS." AND metod = 'wtcard' AND type = 16")
                            ->get("$table t")
                            ->row("count");
            $_SESSION["paymentBonusSB_count"] = $r["count"];
            if ($search || $filter) $_SESSION["paymentBonusSB_count_time"] = 0;
            else $_SESSION["paymentBonusSB_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilterBank($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->code->db_decode($this->db
                ->select("t.*")
                ->where("status = ".self::TRANSACTION_STATUS_VEVERIFY_SS." AND metod = 'wtcard' AND type = 16")
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result()); //echo $this->db->last_query();die;

        foreach( $r["rows"] as &$row)
            $row->action = '<a  class="button greenB" href="/opera/payment/bonus_sb_apply/'.$row->id.'">Подтвердить</a><br><a class="button redB" href="/opera/payment/bonus_sb_reject/'.$row->id.'">Отклонить</a>';

        return json_encode($r);
    }


    public function paymentWtcard($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payout_wtcard_count"]) && isset($_SESSION["payout_wtcard_count_time"]) && $_SESSION["payout_wtcard_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payout_wtcard_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilterBank($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->join('users u','u.id_user = t.id_user', 'inner')
                            ->where("status = ".self::TRANSACTION_STATUS_IN_PROCESS." AND metod = 'out' AND type = 328 AND value = 328")
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payout_wtcard_count"] = $r["count"];
            if ($search || $filter) $_SESSION["payout_wtcard_count_time"] = 0;
            else $_SESSION["payout_wtcard_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilterBank($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->code->db_decode($this->db
                ->select("t.*")
                ->where("status = ".self::TRANSACTION_STATUS_IN_PROCESS." AND metod = 'out' AND type = 328 AND value = 328")
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result()); //echo $this->db->last_query();die;

        return json_encode($r);
    }


	public function getPayoutWtcardValue(){
		return $this->db
			->select("sum(summa) as summa")
			->join('users u','u.id_user = t.id_user', 'inner')
			->where("status = ".self::TRANSACTION_STATUS_REMOVED ." AND metod = 'out' AND value = 328")
			->get("transactions t")
			->row("summa");
	}


	public function getPayoutWtcardCount(){
		return $this->db
			->select("COUNT(*) as count")
			->join('users u','u.id_user = t.id_user', 'inner')
			->where("status = ".self::TRANSACTION_STATUS_REMOVED ." AND metod = 'out' AND value = 328")
			->get("transactions t")
			->row("count");
	}

	public function payoutWtcard($cols, $table) {
		$search = /*$_POST["search"]*/ false;
		$filter = $_POST["filter"];
		$per_page = (int) $_POST["per_page"];
		$offset = (int) $_POST["offset"];
		$order = $_POST["order"];
		$r = ["count" => "", "rows" => []];

		if(isset($_SESSION["payout_3_wtcard_count"]) && isset($_SESSION["payout_3_wtcard_count_time"]) && $_SESSION["payout_3_wtcard_count_time"] > (time() - 24*60*60) && !$search && !$filter)
			$r["count"] = $_SESSION["payout_3_wtcard_count"];
		else {
			if ($search)
				$this->_createSearch($search, $cols);
			if ($filter)
				$this->_createFilterBank($filter, "t.");

			$r["count"] = $this->db
				->select("COUNT(*) as count")
				->join('users u','u.id_user = t.id_user', 'inner')
				->where("status = ".self::TRANSACTION_STATUS_REMOVED ." AND metod = 'out' AND value = 328")
				->get("$table t")
				->row("count");
			$_SESSION["payout_3_wtcard_count"] = $r["count"];
			if ($search || $filter) $_SESSION["payout_3_wtcard_count_time"] = 0;
			else $_SESSION["payout_3_wtcard_count_time"] = time();
		}

		if ($order)
			$this->_createOrder($order);
		if ($filter)
			$this->_createFilterBank($filter, "t.");
		if ($search)
			$this->_createSearch($search, $cols);

		$r["rows"] = $this->code->db_decode($this->db
			->select("t.*")
			->where("status = ".self::TRANSACTION_STATUS_REMOVED." AND metod = 'out' AND value = 328")
			->limit($per_page, $offset)
			->get("$table t")
			->result()); //echo $this->db->last_query();die;

		return json_encode($r);
	}
    public function getPayout($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payout_count"]) && isset($_SESSION["payout_count_time"]) && $_SESSION["payout_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payout_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->where(["t.status" => self::TRANSACTION_STATUS_IN_PROCESS, "t.metod" => "out"])
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payout_count"] = $r["count"];
            if ($search || $filter) $_SESSION["payout_count_time"] = 0;
            else $_SESSION["payout_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
                ->select("t.*, ('-') as time ")
                ->where(["t.status" => self::TRANSACTION_STATUS_IN_PROCESS, "t.metod" => "out"])
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }
    public function getPayoutBankProcess($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        if(isset($_SESSION["payout_count"]) && isset($_SESSION["payout_count_time"]) && $_SESSION["payout_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["payout_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                            ->select("COUNT(*) as count")
                            ->where(["t.status" => self::TRANSACTION_STATUS_IN_PROCESS_BANK, "t.metod" => "out"])
                            ->get("$table t")
                            ->row("count");
            $_SESSION["payout_count"] = $r["count"];
            if ($search || $filter) $_SESSION["payout_count_time"] = 0;
            else $_SESSION["payout_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
                ->select("t.*, ('-') as time ")
                ->where(["t.status" => self::TRANSACTION_STATUS_IN_PROCESS_BANK, "t.metod" => "out"])
                ->limit($per_page, $offset)
                ->get("$table t")
                ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }
    public function getPayoutNew($cols, $table) {
        $this->load->model('transactions_model','transactions');
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                    "t.metod" => "out",
                //    "t.value <>" => "328",
                    "t.value <>" => "73",
                    "t.value <>" => "72",
                    "t.value <>" => "71",
					"t.bonus" => "2",
                    "t.date >" => "2015-07-25"
                ])
                ->get("$table t")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("t.*, ('-') as time ")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                "t.metod" => "out",
              //  "t.value <>" => "328",
                "t.value <>" => "73",
                "t.value <>" => "72",
                "t.value <>" => "71",
				"t.bonus" => "2",
                "t.date >" => "2015-07-25"
            ])
            ->limit($per_page, $offset)
            ->get("$table t")
            ->result(); //echo $this->db->last_query();die;


        foreach( $r["rows"] as $rrow ){
            $b2 = $this->transactions->getAllInMoneyOfUser($rrow->id_user, 2);
            if ( $b2 <= 0){
                $rrow->red = 1;
            }
        }


        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }


    public function getPayoutNewNo($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                    "t.metod" => "out",
                //    "t.value <>" => "328",
                    "t.value <>" => "73",
                    "t.value <>" => "72",
                    "t.value <>" => "71",
					"t.bonus" => "2",
                    "t.date >" => "2015-07-25"
                ])
                ->get("$table t")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("t.*, ('-') as time ")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                "t.metod" => "out",
              //  "t.value <>" => "328",
                "t.value <>" => "73",
                "t.value <>" => "72",
                "t.value <>" => "71",
				"t.bonus" => "2",
                "t.date >" => "2015-07-25"
            ])
            ->limit($per_page, $offset)
            ->get("$table t")
            ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }

   public function getPCREDSPayouts($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                    "t.metod" => "out",
                //    "t.value <>" => "328",
                    "t.bonus" => "3",
                    //"t.date >" => "2015-07-25"
                ])
                ->get("$table t")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("t.*, ('-') as time ")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                "t.metod" => "out",
              //  "t.value <>" => "328",
                "t.bonus" => "3",
                //"t.date >" => "2015-07-25"
            ])
            ->limit($per_page, $offset)
            ->get("$table t")
            ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }


    public function getWTdebitPayouts($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                    "t.metod" => "out",
                //    "t.value <>" => "328",
                    "t.value <>" => "73",
                    "t.value <>" => "72",
                    "t.value <>" => "71",
                    "t.bonus" => "6",
                    //"t.date >" => "2015-07-25"
                ])
                ->get("$table t")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("t.*, ('-') as time ")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                "t.metod" => "out",
              //  "t.value <>" => "328",
                "t.value <>" => "73",
                "t.value <>" => "72",
                "t.value <>" => "71",
                "t.bonus" => "6",
                //"t.date >" => "2015-07-25"
            ])
            ->limit($per_page, $offset)
            ->get("$table t")
            ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }


    public function getLoanPayouts($cols, $table) {
        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = ["count" => "", "rows" => []];

        //if(isset($_SESSION[__FUNCTION__."_count"]) && isset($_SESSION[__FUNCTION__."_count_time"]) && $_SESSION[__FUNCTION__."_count_time"] > (time() - 24*60*60) && !$search && !$filter)
          //  $r["count"] = $_SESSION[__FUNCTION__."_count"];
        //else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db
                ->select("COUNT(*) as count")
             //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
                ->where([
                    "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                    "t.metod" => "out",
                //    "t.value <>" => "328",
                    "t.value <>" => "73",
                    "t.value <>" => "72",
                    "t.value <>" => "71",
					//"t.bonus" => "2",
                    //"t.date >" => "2015-07-25"
                ])
                ->where("t.note like '%Loan:%'")
                ->get("$table t")
                ->row("count");
        // $_SESSION[__FUNCTION__."_count"] = $r["count"];
          //  if ($search || $filter) $_SESSION[__FUNCTION__."_count_time"] = 0;
        // else $_SESSION[__FUNCTION__."_count_time"] = time();
        //}

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
        if ($search)
            $this->_createSearch($search, $cols);

        $r["rows"] = $this->db
            ->select("t.*, ('-') as time ")
         //   ->join('transactions_payment2 p', 'p.id_user = t.id_user', 'inner')
            ->where([
                "t.status" => self::TRANSACTION_STATUS_IN_PROCESS,
                "t.metod" => "out",
              //  "t.value <>" => "328",
                "t.value <>" => "73",
                "t.value <>" => "72",
                "t.value <>" => "71",
				//"t.bonus" => "2",
                //"t.date >" => "2015-07-25"
            ])
            ->where("t.note like '%Loan:%'")
            ->limit($per_page, $offset)
            ->get("$table t")
            ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }


    private function _createOrder($order) {
        foreach ($order as $key => $value)
            $this->db->order_by($key, $value);
    }

    private function _createFilterBank($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;

            if ("fio" == $key) {
                $t = 'u.';
                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            } else if("summa" == $key){
                $this->db->where("$t$key = '$value'");
            }
            else $this->db->where("$t$key LIKE", "%$value%");
        }
    }

    private function _createFilter($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;

            if ("fio" == $key) {
                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            }
            else $this->db->where("$t$key LIKE", "%$value%");
        }
    }

    private function _createSearch($search, $cols) {
        $search = explode(" ", $search);
        foreach ($search as $word) {
            $wordEncript = $this->code->code($word);
            $word = preg_match("/[а-я]/i", $word) ? "text" : $word;

            $where = "(";
            foreach ($cols as $col => $type) {
                if ("encript" == $type)
                    $where .= "t.$col = '$wordEncript' OR ";
                else
                    $where .= "t.$col LIKE '%$word%' OR ";
            }

            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";

            $this->db->where($where);
//            "("
//                . "id_user LIKE '%$word%' OR "
//                . "name = '$wordEncript' OR "
//                . "sername = '$wordEncript' OR "
//                . "patronymic = '$wordEncript' OR "
//                . "email = '$wordEncript' OR "
//                . "parent LIKE '%$word%' OR "
//                . "reg_date LIKE '%$word%' OR "
//                . "ip_address = '$wordEncript' OR "
//                . "phone = '$wordEncript' OR "
//                . "online_date LIKE '%$word%' OR "
//                . "state LIKE '%$word%'"
//                . ")"
        }
    }

    public function isPaymentDataChanged( )
    {
        $this->load->model('accaunt_model','accaunt');
        $this->load->library('code');

        $new_user_data_src = $this->user_fields(true);
        $new_user_data = [];
        foreach( $new_user_data_src as $key => $value )
        {
            if( strpos( $key, 'bank_' ) === FALSE && strpos( $key, 'webmoney' ) === FALSE ) continue;
            $new_user_data[ $key ] = $this->code->decode( $value );
        }

        $old_user_data_src = new stdClass();
        $this->accaunt->get_user( $old_user_data_src );

        $old_user_data = (array) $old_user_data_src->user;

        $fields_changed = 0;
        $fields_error = 0;
        if( empty( $new_user_data ) || empty( $old_user_data ) )
            return 1;

        foreach( $new_user_data as $key => $value )
        {
            if( strpos( $key, 'bank_' ) === FALSE && strpos( $key, 'webmoney' ) === FALSE ) continue;

            if( isset( $new_user_data[$key] ) && isset( $old_user_data[$key] ))
            {

                if( $new_user_data[$key] !== $old_user_data[$key] )
                {
                    $fields_changed++;
                }
            }else{
                $fields_error++;
            }
        }

        if( $fields_error > 0 ) return 2;
        if( $fields_changed === 0 ) return FALSE;
        return TRUE;
    }


    public function getTodayStatistic(){
        return $this->db->where('date',date('Y-m-d'))->get('statistics')->row();
    }


    private function _is_phone_created($user_id) {
        $this->load->model('phone_model', 'phone_model');
        if(!empty($this->phone_model->getPhone($user_id)))
            return true;
        return false;
    }

}
