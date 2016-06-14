<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Currency_exchange_model extends CI_Model{

    public $table_payment_systems = 'currency_exchange_payment_systems',
            $table_orders = 'currency_exchange_orders',
            $table_orders_arhive = 'currency_exchange_orders_arhive',
            $table_order_details = 'currency_exchange_order_details',
            $table_payment_system_set_id = 'currency_exchange_payment_system_set_id',
            $table_payment_systems_groups = 'currency_exchange_payment_systems_groups',
            $table_user_paymant_data = 'currency_exchange_user_paymant_data',
            $table_new_paymant_systems = 'currency_exchange_new_paymant_systems',
            $table_problem_suject = 'currency_exchange_problem_suject',
            $table_problem_chat = 'currency_exchange_problem_chat',
            $table_operator_notes = 'currency_exchange_operator_notes',
            $table_operator_reject_notes = 'currency_exchange_operator_reject_notes',
            $table_payment_systems_rates = 'currency_exchange_payment_systems_rates',
            $table_block_user = 'currency_exchange_block_user',
            $table_last_deals = 'currency_exchange_last_deals',
            $table_country = 'currency_exchange_country',
            $table_all_currency = 'currency_exchange_all_currency',
            $table_completed_orders_data = 'p2p_completed_orders_data'
            ;

    private $get_all_payment_systems = false;
//    private $cost_nominal_payment_account = 0.5; //минимальная стоимость валюты обмена от стоимости USD - это не размер дисконта!!
    private $cost_nominal_payment_account = [
        2 => 0,
        4 => 0,
        5 => 0,
        6 => 0,
    ]; //минимальная стоимость валюты обмена от стоимости USD - это не размер дисконта!!
    private $up_nominal_payment_account = [
        2 => 2,
        4 => 2,
        5 => 2,
        6 => 100,
    ]; //max стоимость валюты обмена от стоимости USD - это не размер дисконта!!

    private $wt_to_operator = [
        6 => 'wt_debit_usd',
        5 => 'wt',
        4 => 'wt_c_creds',
        2 => 'wt_heart'
    ];
    static $last_error;
    static private $_all_curencys = false;
    static private $_all_curencys_key_num = false;
    static private $_all_country_name = false;

    private $last_deals_path_file = 'application/logs/last_deals/';

    static private $_users_data = false;

    static private $_root_currencys = false;
    static private $_root_currencys_mn = false;

    const MAX_ORDER_GET_USER_WT =  5;//количество заявок ВТ, взятых с биржи, где пользователь получает ВТ
    static private $_bank_ps_arr = false;
    static private $_bank_ps_arr_id = false;
    static private $_other_ps_arr = false;
    static private $_other_ps_arr_id = false;

    static private $_all_user_payment_data = false;

    static private $_self_object = false;

    const SWITCH_OFF = FALSE; // закрыть платежку
    const ORDER_TIME_OUT_MONEY_SEND_WT = 86400; // Время жизни заявки для отправки денег WT Сутки- 24ч
    const ORDER_TIME_OUT_MONEY_RECEIVED_WT = 86400; // Время жизни заявки для подтверждения получения денег WT Сутки- 24ч
    const ORDER_TIME_OUT_MONEY_SEND_WT_BANK = 604800; // Время жизни заявки для подтверждения получения денег Банк 7 суток

    const ORDER_DETAILS_TYPE_SENDER = 0,
            ORDER_DETAILS_TYPE_ACCEPTOR = 1;
    const ORDER_TYPE_SELL_WT = 0,
            ORDER_TYPE_BUY_WT = 1;

    const ORDER_STATUS_PROCESSING_SB = 8; //на проверке у СБ
    const ORDER_STATUS_PROCESSING = 9; //на проверке у опервтора
    const ORDER_STATUS_SET_OUT = 10; //выставлена на продажу
    const ORDER_STATUS_CONFIRMATION = 20;
    const ORDER_STATUS_PENDING_DOCUMENT = 21; //в ожидании загрузки документа о перевдое
//    const ORDER_STATUS_PENDING_CONFIRMATION = 22;
//
//
//    const ORDER_STATUS_DOCUMENT_CONFIRM = 30;
//    const ORDER_STATUS_CONFIRM_ORDER_APLAY = 40;
//
//    const ORDER_STATUS_SUCCESS = 60;
    const ORDER_STATUS_HAVE_PROBLEM = 30;

    const ORDER_STATUS_SUCCESS = 60;

    const ORDER_STATUS_UNSUCCESS = 70; //неуспешна про причине, связанной с банком
//    const ORDER_STATUS_CANCELED = 80; //сам пользоатель отменил
    const ORDER_STATUS_CANCELED = 81;  //сам пользоатель отменил
    const ORDER_STATUS_OPERATOR_CANCELED = 82; //оператор отменил
    const ORDER_STATUS_CANCELED_BY_USER_BLOCK = 83; // отмена заявки после блокировки пользователя

    const ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR = 84; // оператор провёл заявку до конца
    const ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR = 85; // оператор отменил заявку


    const ORDER_STATUS_ANNULLED = 90; //аннулирована админов
    const ORDER_STATUS_REMOVED = 100; //Удалено пользователем
    const ORDER_STATUS_ADMIN_REMOVED = 101; //Удалено пользователем

    const PAYMENT_SYSTEM_TIME_LIVE = 1; // время жизни обменного курса в базе данных в часах

    const NEWPS_ADDED_STATE_NEW = 0;
    const NEWPS_ADDED_STATE_ADDED = 10;
    const NEWPS_ADDED_STATE_REJECTED = 20;

    const WT_SET_NONE = 0;
    const WT_SET_SELL = 1;
    const WT_SET_BUY = 2;
    
    
            
    const ORDER_STEP_LATE_WITHDRAWAL_TAKER = 25; // 25 - у кого этот статус то и берет деньги.
    const ORDER_STEP_LATE_WITHDRAWAL_WAITER = 26; // 26 - а этому высвечивает, ожидайте пока другой заберет деньги.

    
    
    private $_mask_user_step_confirmation = [
        'kontragent_confirm_send_money'     => 0b0001,
        'initiator_confirm_received_money'  => 0b0010,
        'initiator_confirm_send_money'      => 0b0100,
        'kontragent_confirm_received_money' => 0b1000,
        
        'counterparty_pending_received_money' => 0b11001,
        'initiator_pending_received_money' => 0b11010,
    ];

    public $p2p_test_users = array(
                '91802698',
                '60830397',
                '93517463',
                '82938815',
                '38854127',
                '28164570',//wtest6
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
                '90836571',
                '93517463',
                '90827893',
                '92156962',

                '99677967',
                '87667178',
                '54049637',
            );

    public function __construct()
    {
        parent::__construct();
    }

    public function get_test_user_ids() {
        return $this->p2p_test_users;
    }




    static public function getInstance()
    {
        if(self::$_self_object == false)
        {
            self::$_self_object = new Currency_exchange_model();
        }

        return self::$_self_object;
    }



    static public function get_user_data($user_id, $fields_string = false)
    {
        if(!isset(self::$_users_data[$user_id]))
        {
            $ci = get_instance();
            $ci->load->model("users_model","users");
            $user_data = $ci->users->getUserData($user_id);

            self::$_users_data[$user_id] =  $user_data;
        }
        else
        {
            $user_data = self::$_users_data[$user_id];
        }

        if(is_array($fields_string))
        {
            $str = '';

            foreach($fields_string as $field)
            {
                $str .= $user_data->{$field}.' ';
            }

            return $str;
        }

        return $user_data;
    }


    static public function get_ps($ps)
    {
//        var_dump($ps);
        if(is_numeric($ps))
        {
            $res_ps =  self::get_ps_by_id($ps);
        }
        else if(is_object($ps) && $ps instanceof stdClass)
        {
            $res_ps =  self::get_ps_by_id($ps->id);
        }
        else
        {
            $res_ps =  self::get_ps_by_machin_name($ps);
        }

        if(empty($res_ps))
        {
            return false;
        }

        if(self::$_all_user_payment_data !== false)
        {
            $res = self::$_all_user_payment_data;
        }
        else
        {
            $ci = get_instance();
            $ci->load->model("users_model","users");
            $user_id = $ci->users->getCurrUserId();

            $res = $this->db
                ->where('user_id', $user_id)
                ->get($this->table_user_paymant_data)
                ->result();

            self::$_all_user_payment_data = $res;
        }

        $currency_exchange = self::getInstance();
        $currency_exchange->_set_pay_data_to_pay_sys($res, $res_ps);
//vred($res_ps);
        if(empty($res_ps->humen_name))
        {
            $res_ps->humen_name =  _e('currency_name_'.$res_ps->machine_name);
        }

        return $res_ps;
    }


    static public function get_ps_by_id($ps_id)
    {
        if(self::$_other_ps_arr_id === false)
        {
            $ci = get_instance();
            $ci->load->model("users_model","users");
            $user_id = $ci->users->getCurrUserId();

            $currency_exchange = self::getInstance();
//            $all_ps = $currency_exchange->get_all_payment_systems();
            $all_ps = $currency_exchange->get_user_all_paymant_data($user_id);
            self::$_other_ps_arr = self::$_other_ps_arr===false?$all_ps:self::$_other_ps_arr;
            self::$_other_ps_arr_id = array_set_value_in_key($all_ps);
        }

        if(isset(self::$_other_ps_arr_id[$ps_id]))
        {
            return self::$_other_ps_arr_id[$ps_id];
        }

        if(isset(self::$_bank_ps_arr_id[$ps_id]))
        {
            return  self::$_bank_ps_arr_id[$ps_id];
        }

        if(empty($currency_exchange))
        {
            $currency_exchange = self::getInstance();
        }

//        $ps =  $currency_exchange->get_payment_system_by_machin_name($ps_machine_name);
        $ps =  $currency_exchange->get_payment_systems_by_id($ps_id);

        self::$_bank_ps_arr_id[$ps_id] = $ps;
        self::$_bank_ps_arr_id[$ps->machine_name] = $ps;

        return $ps;
    }



    static public function get_ps_by_machin_name($ps_machine_name)
    {
        if(self::$_other_ps_arr === false)
        {
            $ci = get_instance();
            $ci->load->model("users_model","users");
            $user_id = $ci->users->getCurrUserId();

            $currency_exchange = self::getInstance();
//            self::$_other_ps_arr = $currency_exchange->get_all_payment_systems();
            self::$_other_ps_arr = $currency_exchange->get_user_all_paymant_data($user_id);
            self::$_other_ps_arr_id = self::$_other_ps_arr_id === false?array_set_value_in_key(self::$_other_ps_arr):self::$_other_ps_arr_id;
        }

        if(isset(self::$_other_ps_arr[$ps_machine_name]))
        {
            return self::$_other_ps_arr[$ps_machine_name];
        }

        if(isset(self::$_bank_ps_arr[$ps_machine_name]))
        {
            return  self::$_bank_ps_arr[$ps_machine_name];
        }

        if(empty($currency_exchange))
        {
            $currency_exchange = self::getInstance();
        }

        $ps =  $currency_exchange->get_payment_system_by_machin_name($ps_machine_name);

        self::$_bank_ps_arr[$ps_machine_name] = $ps;
        self::$_bank_ps_arr_id[$ps->id] = $ps;

        return $ps;
    }



    static public function root_currencys()
    {
        $currency_exchange = self::getInstance();

        return $currency_exchange->get_root_currencys();
    }



    static public function root_currencys_js($key = 'currency_id')
    {
        $curencys = self::root_currencys();

        $res = [];

        foreach($curencys as $k => $v)
        {
            $res[$k] = $v->{$key};
        }

        return $res;
    }



    static public function root_currencys_id()
    {
        $currency_exchange = self::getInstance();

        return $currency_exchange->get_root_currencys_id();
    }



    static public function is_root_curr($currency)
    {
        if(!$currency)
        {
            return false;
        }

        $currency_exchange = self::getInstance();

        return $currency_exchange->is_root_currency($currency);
    }



    static public function root_currency($currency)
    {
        if(!$currency)
        {
            return false;
        }

        $currency_exchange = self::getInstance();

        if(is_array($currency))
        {
            return $currency_exchange->get_root_currency_from_array($currency);
        }

        return $currency_exchange->get_root_currency($currency);
    }



    public function get_root_currencys_id()
    {
        if(self::$_root_currencys !== false)
        {
            return self::$_root_currencys;
        }

        $res = $this->db
                ->where('root_currency',1)
                ->where('active',1)
                ->get($this->table_payment_systems)
                ->result();

        self::$_root_currencys = array_set_value_in_key($res);

        return  self::$_root_currencys;
    }



    public function get_root_currencys()
    {
        if(self::$_root_currencys_mn !== false)
        {
            return self::$_root_currencys_mn;
        }

        $currencys =  $this->get_root_currencys_id();

        self::$_root_currencys_mn = array_set_value_in_key($currencys, 'machine_name');

        return self::$_root_currencys_mn;
    }


    /**
     * Принимат ка id  так machine_name
     * @param type $currency
     * @return boolean
     * @throws Exception
     */

    public function is_root_currency($currency)
    {
        if(empty($currency))
        {
//            throw new Exception('Не задана валюта.');
            return false;
        }

        $currencys = $this->get_root_currencys();
        $currencys_id = $this->get_root_currencys_id();

        if(isset($currencys[$currency]) || isset($currencys_id[$currency]))
        {
            return true;
        }

        return false;
    }


    public function is_root_currency_in_array($arr)
    {
        foreach($arr as $v)
        {
            if($this->is_root_currency($v))
            {
                return true;
            }
        }

        return false;
    }



    public function is_root_currency_in_array_key($arr)
    {
        $arr_key = array_keys($arr);

        return $this->is_root_currency_in_array($arr_key);
    }



    public function get_root_currency_key_from_array($arr)
    {
        foreach($arr as $k => $v)
        {
            if($this->is_root_currency($k))
            {
                return $k;
            }
        }

        return false;
    }


    public function get_root_currency($currency)
    {
        if(!$this->is_root_curr($currency))
        {
            return false;
        }

        $currencys = $this->get_root_currencys();
        $currencys_id = $this->get_root_currencys_id();

        if(isset($currencys[$currency]))
        {
            return $currencys[$currency];
        }

        if(isset($currencys_id[$currency]))
        {
            return $currencys_id[$currency];
        }
    }



    public function get_root_currency_from_array($arr)
    {
        foreach($arr as $val)
        {
            $res = $this->get_root_currency($val);

            if($res !== false)
            {
                return $res;
            }
        }

        return false;
    }


    public function get_root_payout_limit_arr($user_rating)
    {
        $all_root_currency = $this->get_root_currencys();
//        $bonus = $user_rating['payout_limit_bonus'];
        $bonus = $user_rating['payout_limit_by_bonus'];
//        pre($all_root_currency);
//        pre($bonus);
        foreach($all_root_currency as $k => $v)
        {
            $payout_limit_arr[$v->machine_name] = $this->_calculation_and_create_payout_limit_arr($bonus[$v->transaction_bonus]);

        }

        return $payout_limit_arr;
    }


    private function _calculation_and_create_payout_limit_arr($payout_limit)
    {
        if($payout_limit > 1000) $payout_limit = 1000;
        //список сумм для select WT
        $payout_limit_arr = array(0);

        if($payout_limit <= 0)
        {
            return $payout_limit_arr;
        }

        for($i = 50; $i<$payout_limit+1; $i += 50)
        {
            if($i > $payout_limit)
            {
                break;
            }

            $payout_limit_arr[] = $i;
        }

        return $payout_limit_arr;
    }



    public function get_pay_sys_by_country_id($country_id)
    {
        $res = $this->db
                ->where('country_id', $country_id)
                ->where('active', 1)
                ->order_by('machine_name', 'ASC')
                ->get($this->table_payment_systems)
                ->result();

        return $res;
    }



    public function get_p2p_test_users()
    {
        return $this->p2p_test_users;
    }

    # payment_system

    public function add_order_discount( $wt_ps, $other, $order_id )
    {

        if( empty( $wt_ps ) || empty($other) || empty($order_id) ) return FALSE;

        if(is_numeric($order_id)  )
        {
            $order = $this->get_original_order_by_id($order_id);
            if( empty( $order ) ) return FALSE;
        }else
            $order = $order_id;

        $wt_set = $order->wt_set;

        $smallest_discount = FALSE;
        foreach( $other as &$ps )
        {
            //1 - 20 / 50 =
            $discount = round( 1 - $ps['usd_equivalent'] / $wt_ps['amount'], 4 )* 100;

            if( ($smallest_discount === FALSE || $wt_set == self::WT_SET_SELL ) && ( $discount > 0 || $smallest_discount < $discount ) )
            {
                $smallest_discount = $discount;
                continue;
            }
            if( ($smallest_discount === FALSE || $wt_set == self::WT_SET_BUY ) && ( $discount < 0 || $smallest_discount > $discount ) )

            {
                $smallest_discount = $discount;
                continue;
            }

        }

        $smallest_discount = -$smallest_discount;

        $where = ['order_id' => $order_id ];
        $update = ['order_discount' => $smallest_discount ];
        $this->db->limit(15)->update($this->table_order_details, $update, $where);

        $where = [ 'id' => $order_id ];
        $update = ['discount' => $smallest_discount ];
        $this->db->limit(1)->update($this->table_orders, $update, $where);

        $where = [ 'original_order_id' => $order_id ];
        $update = ['discount' => $smallest_discount ];
        $this->db->limit(2)->update($this->table_orders_arhive, $update, $where);

        return TRUE;
    }


    //$step 0 - выставление, 1 - сведение на бирже, 2 - завершение сделки
    public function add_payment_system_discount( $ps, $update_order_id = null, $step = 0 )
    {
        $wt_ps =  null;
        $other = [];
        $wt_set = FALSE;

        if( empty( $ps ) ) return FALSE;
        $user_id = $this->accaunt->get_user_id();
        foreach( ['buy','sell'] as $order )
        {

            foreach( $ps[ $order ][0] as $ps_name => $ps_id )
            {
                $choice_currecy = false;
                $currency_id = false;
                if( !isset( $ps[$order][1][ $ps_name ] ) ) continue;

                if( isset( $ps[$order][2][ $ps_name ] ) ) $choice_currecy = $ps[$order][2][ $ps_name ];

                $ps_data = $this->get_payment_systems_by_id( $ps_id );
                $usd_rate = $this->get_currency_rate($ps_data, $choice_currecy);

                if( empty( $usd_rate ) ) $usd_rate = 1;

                $currency_id = $choice_currecy;
                if( empty( $currency_id ) ) $currency_id = $ps_data->currency_id;

                $amount = $ps[$order][1][ $ps_name ];
                $structure = [ 'machine_name' => $ps_name, 'id' => $ps_id, 'amount' => $amount, 'currency_id' => $currency_id ];
//vred($ps_name);
                if( $this->is_root_currency( $ps_name ) )
                {
                    $wt_ps = $structure;
                    $wt_set = ( $order == 'sell'? 1 : 2 );

                    continue;
                }

                $structure['usd_equivalent'] = $amount / $usd_rate;

                $other[ $ps_name ] = $structure;
            }

        }

        $minus_discount = FALSE;

        $comment = '';
        foreach( $other as &$ps )
        {
            //1 - 20 / 50 =
            $discount = round( 1 - $ps['usd_equivalent'] / $wt_ps['amount'] , 4 )* 100;
            $rate = $ps['usd_equivalent'] / $wt_ps['amount'];

            $this->load->model('currency_exchange_statistic_model','currency_exchange_statistic');
            $average_rate = $this->currency_exchange_statistic->get_last_average_data( $wt_ps['id'], 840 );


            if( empty( $average_rate ) ) continue;

            $ps_rate = abs( $average_rate - $rate ) / $average_rate * 100;

/*
//            if( $step == 2 )
//            {
//                $comment = "Complete::Rate: $rate; Avr: $average_rate; Sub: $ps_rate";
//                if( $rate > $average_rate && $ps_rate > -50 ) $minus_discount = TRUE;
//            }else
            switch( $wt_ps['machine_name'] )
            {

//101 payeer - 100 WT
                case 'wt_c_creds':
                    if( $this->accaunt->get_user_id() != 500733 ) break;
//                    if( $discount < 0 ) $minus_discount = TRUE;
                break;
//101 payeer - 100 WT
                case 'wt_heart':
                    if( $this->accaunt->get_user_id() != 500733 ) break;

//                    if( $discount < 0 ) $minus_discount = TRUE;
                break;
//101 payeer - 100 WT
                case 'wt_debit_usd':
                    $this->load->model('system_vars_model','system_vars');

                    if( $step == 0 )
                    {

                        $P2P_DEBIT_SETUP_AVR_DISCOUNT = $this->system_vars->get_var('P2P_DEBIT_SETUP_AVR_DISCOUNT');

                        $comment = "Setup::Rate: $rate; Avr: $average_rate; Sub: $ps_rate";
                        if( $rate < $average_rate && $ps_rate > $P2P_DEBIT_SETUP_AVR_DISCOUNT ) $minus_discount = TRUE;
                        break;
                    }
                    if( $step == 1 ){
                        $P2P_DEBIT_PAIR_AVR_DISCOUNT = $this->system_vars->get_var('P2P_DEBIT_PAIR_AVR_DISCOUNT');

                        $comment = "Exchange::Rate: $rate; Avr: $average_rate; Sub: $ps_rate";
                        if( $rate < $average_rate && $ps_rate > $P2P_DEBIT_PAIR_AVR_DISCOUNT ) $minus_discount = TRUE;
                        break;
                    }
                    if( $step == 2 ){
                        $comment = "Complete::Rate: $rate; Avr: $average_rate; Sub: $ps_rate";
                        if( $rate > $average_rate && $ps_rate > -50 ) $minus_discount = TRUE;
                        break;
                    }

            }
*/
            //обычный дисконт идет с -
            $discount = -$discount;
            $ps['discount'] = $discount;

            if( empty( $update_order_id ) ) continue;
            $where = ['payment_system' => $ps['id'], 'order_id' => $update_order_id ];
            $update = ['discount' => $discount ];
            $this->db->update($this->table_order_details, $update, $where);
        }

        if( $minus_discount === FALSE ) $comment = '';
        return [$minus_discount, $wt_ps, $other, $comment];
    }

    public function get_ps_data( $ps_data )
    {
        if( empty( $ps_data ) ) return FALSE;
        
        $sell = [];
        
        $sell['amount']          = $ps_data->summa;
        $sell['ps_machine_name'] = $ps_data->machine_name;       
        $sell['currency_id']      = $ps_data->choice_currecy;
        
        $ps = self::get_ps( $sell['ps_machine_name'] );
        if( empty($sell['currency_id']) )
        {            
            $sell['currency_id'] = $ps->currency_id;
        }        
        $sell['ps_id'] = $ps->id;
        
        //$currency = self::show_payment_system_code(['currency_id' => $sell['currency_id']]);
        
        $rate = $this->get_currency_rate($ps, $sell['currency_id']);
        
        
        
        $sell['root_currency'] = $ps->root_currency;
        $sell['usd_equivalent'] = $sell['amount']/$rate;     
        
        return $sell;
    }
    
    public function empty_all( $ar )
    {
        foreach( $ar as $a )
            if( !empty($a ) ) return FALSE;
        return TRUE;
    }
    
    #проверка условий границ
    public function deside_status( $order_id, $pair, $single_currency, $step, $wt_set )
    {
        if( $this->empty_all( [$pair, $single_currency, $step] ) ) return FALSE;
        
        if( $single_currency['root_currency'] == 0 ) return FALSE;
        
        
        $rate = $pair['rate'];
        
        $this->load->model('currency_exchange_statistic_model','currency_exchange_statistic');            
        $average_rate = $this->currency_exchange_statistic->get_last_average_data( $single_currency['ps_id'], 840 );


        if( empty( $average_rate ) ) return FALSE;
        
        $ps_rate = round( abs( $rate - $average_rate ) / $average_rate * 100, 4 );
        
        $this->load->model('system_vars_model','system_vars');
        
        $comment = '';
        $action = 'statusSB';
        $action_data = [];
        $minus_discount = FALSE;
        switch( $step )
        {
            case 0:    
                #discount < 0
                if( $single_currency['ps_machine_name'] == 'wt_debit_usd' &&
                    $pair['usd_equivalent'] < $single_currency['usd_equivalent'] )
                {
                    $comment = "";

                    $action = 'error';
                    $action_data = [ 'text' => _e('Значительное отклонение от среднерыночного курса (слишком маленький курс Debit). Измените курс и попробуйте еще раз.' ) ];

                    $minus_discount = TRUE;
                    break;
                }
                
                #Debit - nonWT
                if( $single_currency['ps_machine_name'] == 'wt_debit_usd' && $wt_set == self::WT_SET_SELL )
                {
                    $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP = $this->system_vars->get_var('P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP');
                    $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN = $this->system_vars->get_var('P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN');

                    //vre($P2P_DEBIT_SETUP_AVR_DISCOUNT_UP,$P2P_DEBIT_SETUP_AVR_DISCOUNT_DOWN);
                    
                    if( $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP != FALSE )
                        if( $rate > $average_rate && $ps_rate > $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP ){
                            //$comment .= "REASON: P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP = $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_UP;<br>";
                            $comment = "";
                            
                            $action = 'error';
                            $action_data = [ 'text' => _e('Значительное отклонение от среднерыночного курса (слишком большой курс Debit). Измените курс и попробуйте еще раз.' ) ];

                            $minus_discount = TRUE;
                        }
                        
                    if( $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN != FALSE )    
                        if( $rate < $average_rate && $ps_rate > $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN ){
                            $comment .= "REASON: P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN = $P2P_SELL_DEBIT_SETUP_AVR_DISCOUNT_DOWN;<br>";
                            $minus_discount = TRUE;
                        }
                                            
                }
                #nonWT - Debit                
                if( $single_currency['ps_machine_name'] == 'wt_debit_usd' && $wt_set == self::WT_SET_BUY )
                {
                    $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP = $this->system_vars->get_var('P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP');
                    $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN = $this->system_vars->get_var('P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN');

                    if( $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP != FALSE )
                        if( $rate > $average_rate && $ps_rate > $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP ){
                            $action = 'error';
                            $action_data = [ 'text' => _e('Значительное отклонение от среднерыночного курса (слишком большой курс). Измените курс и попробуйте еще раз.' ) ];
                            
                            $comment .= "REASON: P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP = $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_UP;<br>";
                            $minus_discount = TRUE;
                        }
                        
                    if( $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN != FALSE )    
                        if( $rate < $average_rate && $ps_rate > $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN ){
                            $comment .= "REASON: P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN = $P2P_BUY_DEBIT_SETUP_AVR_DISCOUNT_DOWN;<br>";
                            
                            $action = 'error';
                            $action_data = [ 'text' => _e('Значительное отклонение от среднерыночного курса (слишком маленький курс Debit). Измените курс и попробуйте еще раз.' ) ];
                            
                            $minus_discount = TRUE;
                        }                                            
                }
                
                break;            
            case 1:
                if( $single_currency['ps_machine_name'] == 'wt_debit_usd' && $wt_set == self::WT_SET_BUY )
                {                    
                    $P2P_DEBIT_PAIR_AVR_DISCOUNT_UP = $this->system_vars->get_var('P2P_DEBIT_PAIR_AVR_DISCOUNT_UP');
                    $P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN = $this->system_vars->get_var('P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN');

                    if( $P2P_DEBIT_PAIR_AVR_DISCOUNT_UP != FALSE )
                        if( $rate > $average_rate && $ps_rate > $P2P_DEBIT_PAIR_AVR_DISCOUNT_UP ){
                            $comment .= "REASON: P2P_DEBIT_PAIR_AVR_DISCOUNT_UP = $P2P_DEBIT_PAIR_AVR_DISCOUNT_UP;<br>";
                            $minus_discount = TRUE;
                        }

                    if( $P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN != FALSE )    
                        if( $rate < $average_rate && $ps_rate > $P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN ){
                            $comment .= "REASON: P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN = $P2P_DEBIT_PAIR_AVR_DISCOUNT_DOWN;<br>";
                            $minus_discount = TRUE;
                        }                        
                }
                break;
            case 2:
                break;
        }
        
        $comment .= "PS id:{$pair['ps_id']}; ".
                   "Pair rate: $rate; Avr: $average_rate; PS rate: $ps_rate%; Date:".
                    date('Y-m-d H:i:s')."; Result: ".($minus_discount ? 'TRUE': 'FALSE');
        
        
        return ['result' => $minus_discount, 'comment' => $comment, 'action' => $action, 'action_data' => $action_data ];
    }
    
    #получение деталей по заявке
    public function get_order_ps_data( $id )
    {
        //$ps_all = $this->get_all_payment_systems();
        
        $arch_orders = $this->db         
                 ->where("original_order_id", $id)
                 ->order_by('id','DESC')
                 ->limit(1)
                 ->get($this->table_orders_arhive)
                 ->row();

        if( empty( $arch_orders ) )
        {
            $arch_orders = $this->db         
                 ->where("id", $id)
                 ->order_by('id','DESC')
                 ->limit(1)
                 ->get($this->table_orders)
                 ->row();
            $arch_orders->original_order_id = $arch_orders->id;
            
        }
        
        if( empty( $arch_orders ) ){
            return FALSE;
        }
        
        $order = $this->_get_user_orders_arhive_details_one( $arch_orders );
        

        if( empty( $order ) ) return FALSE;
        
        $res = [];
        //$res['sell'] = [];
        //$res['buy'] = [];
        
        
        $single_currency = FALSE;
        if( !isset( $order->sell_system ) || empty( $order->sell_system ) )
        {
            foreach( $order->sell_payment_data_un as $ps_id => $ps_data )
            {
                $res[ $ps_id ] = $this->get_ps_data( $ps_data );
                $res[ $ps_id ]['sell'] = 1;
            }
            
            if( count( $order->sell_payment_data_un ) == 1 ) $sell_id = $ps_id;
        }else{
            $res[ $order->sell_system ] = $this->get_ps_data( $order->sell_payment_data_un[$order->sell_system] );
            $res[ $order->sell_system ]['sell'] = 1;
            
            $sell_id = $order->sell_system;            
        }
        
        $single_currency = ( $res[ $sell_id ]['root_currency'] == 1? $sell_id : FALSE );
        
        if( !isset( $order->sell_system ) || empty( $order->payed_system ) )
        {
            foreach( $order->buy_payment_data_un as $ps_id => $ps_data ){
                $res[ $ps_id ] = $this->get_ps_data( $ps_data );  
                $res[ $ps_id ]['sell'] = 0;
            }
            
            if( count( $order->buy_payment_data_un ) == 1 ) $buy_id = $ps_id;
        }else{
            $res[ $order->payed_system ] = $this->get_ps_data( $order->buy_payment_data_un[$order->payed_system] );            
            $res[ $order->payed_system ]['sell'] = 0;
            
            $buy_id = $order->payed_system;
        }
        
        if( $order->wt_set == 0 )
        {
            $single_currency = $buy_id;
        }else
        if( $single_currency == FALSE )
            $single_currency = ( $res[ $buy_id ]['root_currency'] == 1? $buy_id : FALSE );
        
        return [ 'pairs' => $res, 'single_currency_id' => $single_currency, 'wt_set' => $order->wt_set ];
    }
    
    //$step 0 - выставление, 1 - сведение на бирже, 2 - завершение сделки
    public function is_set_status_sb( $original_order_id, $step )
    {
        if( empty( $original_order_id ) || !is_numeric($step) || 
            !is_numeric($original_order_id) 
        )
            return false;
        
        $order_ps_data = $this->get_order_ps_data( $original_order_id );

        if( empty( $order_ps_data ) ) return FALSE;
        
        list($pairs, $single_currency_id, $wt_set) = array_values($order_ps_data);
        
        #получаем данные о одной валюте ВТ или не ВТ и убираем ее из общего списка валют
        $single_currency = FALSE;
        
        if( !isset( $pairs[ $single_currency_id ] ) ) return FALSE;        
        $single_currency = $pairs[ $single_currency_id ];
        unset( $pairs[ $single_currency_id ] );
        
        
        #расчет курсов для всех валют
        foreach( $pairs as $ps_is => &$ps_data )
        {
            if( $ps_is == $single_currency_id ) continue;
            
            $ps_data['rate'] = round( ($ps_data['usd_equivalent'] / $single_currency['usd_equivalent']), 4 );
        }
        
        //$pairs - $single_currency
        foreach( $pairs as $p ){
            $res_status = $this->deside_status($original_order_id ,$p, $single_currency, $step, $wt_set);
            if( empty( $res_status ) ) continue;
            
            
            if( $res_status['result'] === TRUE ) return $res_status;
        }
        
        return FALSE;
        
        //vred( $original_order_id, $step, $order_ps_data, $pairs );
        #1. Получить данные заявки
        #2. Получить значение курса рынка
        #3. В зависимости от шага, получить значение курса заявки
        #4. В зависимости от шага, проверить условие
        #5. Вернуть результат: выполнено ли условие, пояснение к результату, включаюшее условие, курс рынка, курс заявки
    }
    
    public function set_status_sb( $original_order_id, $note )
    {
        if( empty( $original_order_id ) || empty( $note ) ) return FALSE;
        $note_arr = [
                    'order_id' => $original_order_id,
                    'text' => $note,
                    'date_modified' => date('Y-m-d H:i:s'),
                ];

        $this->currency_exchange->add_operator_note($note_arr);
        $this->currency_exchange->set_status_to_order_arhive_and_order($original_order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);
        
        return TRUE;
    }
    
    
    public function add_payment_system($order_id, $type, $payments, $user_payment_summa, $select_curency)
    {
        $summa = 0;
        $orig_order_data = $this->get_original_order_by_id( $order_id );

        
        #get priority P2P VIP
            $uid = $this->accaunt->get_user_id();
            $this->load->model('users_filds_model','users_filds');
            $priority = $this->users_filds->getUserFild( $uid, 'p2p_priority', FALSE );
//            $priority = $this->users_filds->getUserFild( $uid, 'get_max_count_p2p_wt_orders', FALSE );
            if( !is_numeric($priority) ) $priority = 0;
        #!get priority P2P VIP
        
        try
        {

            $this->db->trans_start();

            $summa_wt = false;
            $wt_key = FALSE;
            foreach($payments as $key => $val)
            {
                        
                $this->_add_payment_system($orig_order_data, $type, $val, $user_payment_summa[$key], $select_curency[$key], $priority);

//                if($key == 'wt')
                if($this->is_root_currency($key))
                {
                    $wt_key = $key;
                    $summa_wt = $user_payment_summa[$key];
                    continue;
                }

                if( $user_payment_summa[$key] > $summa)
                {
                    $summa = $user_payment_summa[$key];
                }
            }
        //vred( $user_payment_summa[$key] );

//            if($type == self::ORDER_TYPE_SELL_WT)
//            {
//                $arr_summ = array('seller_amount' => $summa);
//            }
//            elseif ($type == self::ORDER_TYPE_BUY_WT)
//            {
//                $arr_summ = array('buyer_amount_down' => $summa);
//            }
            if($summa_wt === FALSE)
            {
                $arr_summ = array('buyer_amount_down' => $summa);
            }
            elseif($summa_wt !== FALSE)
            {
                $arr_summ = array('seller_amount' => $summa_wt);
            }

            $this->db
                    ->where('id', $order_id)
                    ->limit(1)
                    ->update($this->table_orders, $arr_summ);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
        return TRUE;
    }



    public function add_seller_amount_is_not_wt($order_id)
    {
        if(empty($order_id))
        {
            return false;
        }

        $orig_order_data = $this->get_original_order_by_id($order_id);

        if($orig_order_data->wt_set != 0)
        {
            return true;
        }

        $res = $this->db
                ->where('order_id', $orig_order_data->id)
                ->where('type', 1)
                ->get($this->table_order_details)
                ->row();

        if(empty($res))
        {
            return false;
        }

        if(!empty($res->choice_currecy))
        {
            $choice_currecy = $res->choice_currecy;
        }
        else
        {
            $choice_currecy = false;
        }

        $summ = $res->summa;

        $ps = self::get_ps($res->payment_system);

        $rate = $this->get_currency_rate($ps, $choice_currecy);

        $summ_usd = $summ/$rate;

        $data = [
            'seller_amount' => round($summ_usd, 4),
        ];

        try
        {
//            $this->db->trans_start();
            $this->db
                ->where('id', $order_id)
                ->limit(1)
                ->update($this->table_orders, $data);

//            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }
//        vre($data);
        return true;
    }



    public function currency_exchange_scores($current_user_id = false)
    {
        if($current_user_id === false)
        {
            $current_user_id = $this->accaunt->get_user_id();
        }

        $this->load->model('currency_exchange_model','currency_exchange');

        #только выставлены
        $status_pending = array( 8, 9, 10 );
        $orders_src = $res = $this->db->where("seller_user_id", $current_user_id)
//                                    ->where("buyer_user_id", 0)
                                    ->where('status <=', 10 )
                                    ->order_by('id', 'DESC')
                                    ->get( 'currency_exchange_orders' )
                                    ->result();

        $orders = array();
        foreach( $orders_src as $o )
        {
           $o->initiator = 1;
           $o->original_order_id = $o->id;
           $o->archive = 0;
            $orders[$o->id] = $o;
        }

        $orders_archive_src = $res = $this->db
                                    ->where("seller_user_id", $current_user_id )// OR buyer_user_id = $current_user_id )")
                                    ->where('status <', 60 )
                                    ->order_by('id', 'DESC')
                                    ->group_by('original_order_id')
                                    ->get( 'currency_exchange_orders_arhive' )
                                    ->result();

        foreach( $orders_archive_src as $o )
        {

            if( isset( $orders[ $o->original_order_id ] ) ) continue;
            $o->archive = 1;
            $orders[ $o->original_order_id ] = $o;
        }

        $limit1 = 0;
        $limit2 = 0;
        $limit3 = 0;
        $net_processing_p2p = 0;
        $total_processing_fee = 0;
        $total_processing_p2p = 0;

        $null_array_by_bonus = array( 0 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 =>0 );
        $limit1_by_bonus = $null_array_by_bonus;
        $limit2_by_bonus = $null_array_by_bonus;
        $limit3_by_bonus = $null_array_by_bonus;
        $net_processing_p2p_by_bonus = $null_array_by_bonus;
        $total_processing_fee_by_bonus = $null_array_by_bonus;
        $total_processing_p2p_by_bonus = $null_array_by_bonus;

        $reverce_processing_fee_by_bonus = $null_array_by_bonus;
        $direct_processing_fee_by_bonus = $null_array_by_bonus;

        if( empty( $orders ) )
        {
            return array(
                'limit1'    => $limit1,
                'limit2'    => $limit2,
                'limit3'    => $limit3,
                'net_processing_p2p'    => $net_processing_p2p,
                'total_processing_fee'  => $total_processing_fee,
                'total_processing_p2p'  => $total_processing_p2p,

                'limit1_by_bonus'    => $limit1_by_bonus,
                'limit2_by_bonus'    => $limit2_by_bonus,
                'limit3_by_bonus'    => $limit3_by_bonus,
                'net_processing_p2p_by_bonus'    => $net_processing_p2p_by_bonus,
                'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
                'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus

            );
        }



//        const ORDER_STATUS_PROCESSING_SB = 8; //на проверке у СБ
//    const ORDER_STATUS_PROCESSING = 9; //на проверке у опервтора
//    const ORDER_STATUS_SET_OUT = 10; //выставлена на продажу
//    const ORDER_STATUS_CONFIRMATION = 20;
//    const ORDER_STATUS_PENDING_DOCUMENT = 21; //в ожидании загрузки документа о перевдое

//    const ORDER_STATUS_HAVE_PROBLEM = 30;
//
//    const ORDER_STATUS_SUCCESS = 60;
//
//    const ORDER_STATUS_UNSUCCESS = 70; //неуспешна про причине, связанной с банком

//    const ORDER_STATUS_CANCELED = 81;  //сам пользоатель отменил
//    const ORDER_STATUS_OPERATOR_CANCELED = 82; //оператор отменил
//    const ORDER_STATUS_CANCELED_BY_USER_BLOCK = 83; // отмена заявки после блокировки пользователя
//    const ORDER_STATUS_ANNULLED = 90; //аннулирована админов
//    const ORDER_STATUS_REMOVED = 100; //Удалено пользователем

        #если не входит в число этис статусов - значит статус считаем активным
        $status_active = array( 60, 70, 81, 82, 84, 85, 90, 100 ); // 81, 82, 83 ????
        $time_blocking = strtotime('2015-09-04 00:00:00');

        $reverce_processing_fee = 0;
        $direct_processing_fee = 0;
        $total_processing_p2p = 0;
        $total_processing_fee = 0;


        $this->load->model('currency_exchange_model','currency_exchange');
        foreach( $orders as $order )
        {
            //пропускаем неактивные статусы
            if( in_array( $order->status, $status_active ) ) continue;

            if( !isset( $order->bonus ) || empty( $order->bonus ) ) $order->bonus = 5;
//            if($order->wt_set == 2 &&
//                    ($order->buyer_send_money_date !='0000-00-00 00:00:00' ||
//                     $order->seller_send_money_date != '0000-00-00 00:00:00')
//            )
//            {
//                vre('таймер истёк 1');
//               //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date)+24*3600 <= time())
////                vre($order->id);
//                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
////                vre(strtotime($order->seller_confirmation_date) , time());
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date) <= time())
////                {
////                    echo 'таймер истёк 2<br/>';
////                }
//            }
            if(false && in_array($current_user_id, $this->p2p_test_users))
            {
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')

                )
                {
    //                vre('таймер истёк 1');
    //                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 1 && strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 2<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
    //                    1
                        $this->currency_exchange->user_block($order, 1);
                        $this->currency_exchange->set_order_canceld_by_user_block($id, 1);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 3<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
                        //2
                        $this->currency_exchange->user_block($order, 2);
                        $this->currency_exchange->set_order_canceld_by_user_block($id);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date != '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 4<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //3
                        $this->currency_exchange->user_block($order, 3);
                    }
                }

                 //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date != '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                    if(     $order->initiator == 1 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT > $time_blocking &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 5<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //4
                        $this->currency_exchange->user_block($order, 4);
                    }
                }
            }

            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 1
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 1 && $order->initiator == 1 )
            {
                
//                if( ($order->id == 1411465 || $order->original_order_id == 1411465 ) ) vred("3--");
//                if( $current_user_id == 95614104 ) echo "0::ID:$order->original_order_id SE:$order->seller_user_id BY:$order->buyer_user_id B:$order->bonus A:$order->seller_amount I:$order->initiator WT: $order->wt_set<br/>";
                $limit1 += $order->seller_amount;
                $limit1_by_bonus[$order->bonus] += $order->seller_amount;


            }
            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 0
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 0 && $order->initiator == 1 )
            {
                
                //don't count new visa orders
                if( strtotime( $order->seller_setup_date ) > strtotime( '2016-04-20 00:00:00' ) )
                {
                    $sell_payment_data = unserialize($order->sell_payment_data);
                    $buy_payment_data = unserialize($order->buy_payment_data);
                    
                    //if( in_array($this->accaunt->get_user_id(), ['500733','500150']) )#remove
                    if( 
                       ( ( !empty( $sell_payment_data ) && $sell_payment_data[0]->payment_system_id == 115 ) ||
                        ( !empty( $buy_payment_data ) && $buy_payment_data[0]->payment_system_id == 115 ) ) 
                    ) continue;
                }   
                
                $limit2 += $order->seller_amount;
                $limit2_by_bonus[$order->bonus] += $order->seller_amount;

                //if( $current_user_id == 99680465 ) echo "2::$order->original_order_id $order->id<br>";
            }

            //ID:89825 SE:49028821 BY:0 B:2 A:1 S:9 I:0 WT: 1
            #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
            if( $order->initiator == 0 && $order->wt_set == 2 && $order->seller_user_id == $current_user_id )
            {
                //echo "ID:$order->id SE:$order->seller_user_id BY:$order->buyer_user_id B:$order->bonus A:$order->seller_amount I:$order->initiator WT: $order->wt_set<br/>";
//if( $current_user_id == 500733 ) echo "1:$order->id:ID:$order->original_order_id SE:$order->seller_user_id BY:$order->buyer_user_id B:$order->bonus A:$order->seller_amount I:$order->initiator WT: $order->wt_set<br/>";
//if( ($order->id == 1411465 || $order->original_order_id == 1411465 ) ) vred("2--");

                $limit3 += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                $reverce_processing_fee += $order->seller_fee;  #Сумма всех комиссий c биржи

                $limit3_by_bonus[$order->bonus] += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                $reverce_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;  #Сумма всех комиссий c биржи
            }



//            if( $order->seller_user_id == $current_user_id && $order->wt_set != 2 )
            if(( $order->seller_user_id == $current_user_id && $order->wt_set == 1 && $order->initiator == 1 ) ||//продаю ВТ
                ( $order->seller_user_id == $current_user_id && $order->wt_set == 0 && $order->initiator == 1 )) //неВТ - неВТ
            {
//                 if( ($order->id == 1411465 || $order->original_order_id == 1411465 ) ) vred("1--");
                 
                $direct_processing_fee += $order->seller_fee;

                $direct_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;
            }



        }

        $net_processing_p2p = $limit1 + $limit2 + $limit3; //зарезервированные средства для уже сведенных обменов
        $total_processing_fee =  $reverce_processing_fee + $direct_processing_fee; //зарезервированные средства на комиссию
        $total_processing_p2p = $net_processing_p2p + $total_processing_fee; //сумма зарезервированных средств, включая комиссию по сведенным заявкам

        $n = 2;

        //echo "$net_processing_p2p = $limit1 + $limit2 + $limit3<br/>";
        $bonus = array_keys( $null_array_by_bonus );
        foreach(  $bonus as $n )
        {

            $net_processing_p2p_by_bonus[$n] = $limit1_by_bonus[$n] + $limit2_by_bonus[$n] + $limit3_by_bonus[$n]; //зарезервированные средства для уже сведенных обменов
            $total_processing_fee_by_bonus[$n] =  $reverce_processing_fee_by_bonus[$n] + $direct_processing_fee_by_bonus[$n];
        //зарезервированные средства на комиссию
            $total_processing_p2p_by_bonus[$n] = $net_processing_p2p_by_bonus[$n] + $total_processing_fee_by_bonus[$n]; //сумма зарезервированных средств, включая комиссию по сведенным заявкам

//            if( $current_user_id == 46009352 )
//            {
//                echo "B:$total_processing_fee_by_bonus[$n] =  $reverce_processing_fee_by_bonus[$n] + $direct_processing_fee_by_bonus[$n]; <br>";
//            }
        }


        $res = array(
            'limit1'    => $limit1,
            'limit2'    => $limit2,
            'limit3'    => $limit3,
            'net_processing_p2p'    => $net_processing_p2p,
            'total_processing_fee'  => $total_processing_fee,
            'total_processing_p2p'  => $total_processing_p2p,

            'limit1_fee_by_bonus'    => $limit1_by_bonus,
            'limit2_fee_by_bonus'    => $limit2_by_bonus,
            'limit3_fee_by_bonus'    => $limit3_by_bonus,
            'net_processing_p2p_fee_by_bonus'    => $net_processing_p2p_by_bonus,
            'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
            'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus
        );



//        echo "$limit1  $limit2 $limit3|";

        return $res;
    }
    public function currency_exchange_scores_old_v2($current_user_id = false)
    {
        if($current_user_id === false)
        {
            $current_user_id = $this->accaunt->get_user_id();
        }

        $this->load->model('currency_exchange_model','currency_exchange');

        #только выставлены
        $status_pending = array( 8, 9, 10 );
        $orders_src = $res = $this->db->where("seller_user_id", $current_user_id)
//                                    ->where("buyer_user_id", 0)
                                    ->where_in('status', $status_pending )
                                    ->order_by('id', 'DESC')
                                    ->get( 'currency_exchange_orders' )
                                    ->result();

//        if( $current_user_id == 48882288 ) vred($orders_src);
        $orders = array();
        foreach( $orders_src as $o )
        {
           $o->archive = 0;
            $orders[$o->id] = $o;
        }

        $orders_archive_src = $res = $this->db
                                    ->where("( seller_user_id = $current_user_id OR buyer_user_id = $current_user_id )")
                                    ->where('status <', 60 )
                                    ->order_by('id', 'DESC')
                                    ->get( 'currency_exchange_orders_arhive' )
                                    ->result();

        foreach( $orders_archive_src as $o )
        {

            if( isset( $orders[ $o->original_order_id ] ) ) continue;
            $o->archive = 1;
            $orders[ $o->original_order_id ] = $o;
        }
//vred($orders);
        if( 00&& $current_user_id == 49028821 )
        {
            foreach( $orders as $o )
            {
                echo "ID:$o->id SE:$o->seller_user_id BY:$o->buyer_user_id B:$o->bonus A:$o->archive S:$o->status I:$o->initiator WT: $o->wt_set<br>";
            }
        }

        $limit1 = 0;
        $limit2 = 0;
        $limit3 = 0;
        $net_processing_p2p = 0;
        $total_processing_fee = 0;
        $total_processing_p2p = 0;

        $null_array_by_bonus = array( 0 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 =>0 );
        $limit1_by_bonus = $null_array_by_bonus;
        $limit2_by_bonus = $null_array_by_bonus;
        $limit3_by_bonus = $null_array_by_bonus;
        $net_processing_p2p_by_bonus = $null_array_by_bonus;
        $total_processing_fee_by_bonus = $null_array_by_bonus;
        $total_processing_p2p_by_bonus = $null_array_by_bonus;

        $reverce_processing_fee_by_bonus = $null_array_by_bonus;
        $direct_processing_fee_by_bonus = $null_array_by_bonus;

        if( empty( $orders ) )
        {
            return array(
                'limit1'    => $limit1,
                'limit2'    => $limit2,
                'limit3'    => $limit3,
                'net_processing_p2p'    => $net_processing_p2p,
                'total_processing_fee'  => $total_processing_fee,
                'total_processing_p2p'  => $total_processing_p2p,

                'limit1_by_bonus'    => $limit1_by_bonus,
                'limit2_by_bonus'    => $limit2_by_bonus,
                'limit3_by_bonus'    => $limit3_by_bonus,
                'net_processing_p2p_by_bonus'    => $net_processing_p2p_by_bonus,
                'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
                'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus

            );
        }



//        const ORDER_STATUS_PROCESSING_SB = 8; //на проверке у СБ
//    const ORDER_STATUS_PROCESSING = 9; //на проверке у опервтора
//    const ORDER_STATUS_SET_OUT = 10; //выставлена на продажу
//    const ORDER_STATUS_CONFIRMATION = 20;
//    const ORDER_STATUS_PENDING_DOCUMENT = 21; //в ожидании загрузки документа о перевдое

//    const ORDER_STATUS_HAVE_PROBLEM = 30;
//
//    const ORDER_STATUS_SUCCESS = 60;
//
//    const ORDER_STATUS_UNSUCCESS = 70; //неуспешна про причине, связанной с банком

//    const ORDER_STATUS_CANCELED = 81;  //сам пользоатель отменил
//    const ORDER_STATUS_OPERATOR_CANCELED = 82; //оператор отменил
//    const ORDER_STATUS_CANCELED_BY_USER_BLOCK = 83; // отмена заявки после блокировки пользователя
//    const ORDER_STATUS_ANNULLED = 90; //аннулирована админов
//    const ORDER_STATUS_REMOVED = 100; //Удалено пользователем

        #если не входит в число этис статусов - значит статус считаем активным
        $status_active = array( 60, 70, 81, 82, 84, 85, 90, 100 ); // 81, 82, 83 ????
        $time_blocking = strtotime('2015-09-04 00:00:00');

        $reverce_processing_fee = 0;
        $direct_processing_fee = 0;
        $total_processing_p2p = 0;
        $total_processing_fee = 0;


        $this->load->model('currency_exchange_model','currency_exchange');
        foreach( $orders as $order )
        {
            if( !isset( $order->bonus ) || empty( $order->bonus ) ) $order->bonus = 5;
//            if($order->wt_set == 2 &&
//                    ($order->buyer_send_money_date !='0000-00-00 00:00:00' ||
//                     $order->seller_send_money_date != '0000-00-00 00:00:00')
//            )
//            {
//                vre('таймер истёк 1');
//               //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date)+24*3600 <= time())
////                vre($order->id);
//                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
////                vre(strtotime($order->seller_confirmation_date) , time());
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date) <= time())
////                {
////                    echo 'таймер истёк 2<br/>';
////                }
//            }
            if(false && in_array($current_user_id, $this->p2p_test_users))
            {
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')

                )
                {
    //                vre('таймер истёк 1');
    //                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 1 && strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 2<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
    //                    1
                        $this->currency_exchange->user_block($order, 1);
                        $this->currency_exchange->set_order_canceld_by_user_block($id, 1);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 3<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
                        //2
                        $this->currency_exchange->user_block($order, 2);
                        $this->currency_exchange->set_order_canceld_by_user_block($id);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date != '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 4<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //3
                        $this->currency_exchange->user_block($order, 3);
                    }
                }

                 //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date != '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                    if(     $order->initiator == 1 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT > $time_blocking &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 5<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //4
                        $this->currency_exchange->user_block($order, 4);
                    }
                }
            }



            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 1
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 1 &&
                ( in_array( $order->status, $status_pending ) || $order->initiator == 1 )
                && !in_array( $order->status, $status_active )
            ){
                $limit1 += $order->seller_amount;
                $limit1_by_bonus[$order->bonus] += $order->seller_amount;

                //if( $current_user_id == 99680465 ) echo "1::$order->original_order_id $order->id<br>";
            }
            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 0
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 0 &&
                (in_array( $order->status, $status_pending ) || $order->initiator == 1 ) &&
                !in_array( $order->status, $status_active ) )
            {
                $limit2 += $order->seller_amount;
                $limit2_by_bonus[$order->bonus] += $order->seller_amount;

                //if( $current_user_id == 99680465 ) echo "2::$order->original_order_id $order->id<br>";
            }

            //ID:89825 SE:49028821 BY:0 B:2 A:1 S:9 I:0 WT: 2
            #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
            if( ( in_array( $order->status, $status_pending ) || $order->initiator == 0 )
                && !in_array( $order->status, $status_active )  )
            {
                if( $order->wt_set == 2 && ( ($order->buyer_user_id == $current_user_id ) ||
                    ( $order->seller_user_id == $current_user_id  )
                )){
                    $limit3 += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                    $reverce_processing_fee += $order->seller_fee;  #Сумма всех комиссий c биржи

                    $limit3_by_bonus[$order->bonus] += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                    $reverce_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;  #Сумма всех комиссий c биржи
                }
            }

            if( $order->seller_user_id == $current_user_id && $order->wt_set != 2 &&
                ( in_array( $order->status, $status_pending ) ) &&
                !in_array( $order->status, $status_active )  )
            {
                $direct_processing_fee += $order->seller_fee;

                $direct_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;
            }

        }

        $net_processing_p2p = $limit1 + $limit2 + $limit3; //зарезервированные средства для уже сведенных обменов
        $total_processing_fee =  $reverce_processing_fee + $direct_processing_fee; //зарезервированные средства на комиссию
        $total_processing_p2p = $net_processing_p2p + $total_processing_fee; //сумма зарезервированных средств, включая комиссию по сведенным заявкам

        $n = 2;


        $bonus = array_keys( $null_array_by_bonus );
        foreach(  $bonus as $n )
        {
            if( $current_user_id == 500733 )
            {
                //echo "B:$n $net_processing_p2p_by_bonus[$n] = $limit1_by_bonus[$n] + $limit2_by_bonus[$n] + $limit3_by_bonus[$n]<br>";
            }
            $net_processing_p2p_by_bonus[$n] = $limit1_by_bonus[$n] + $limit2_by_bonus[$n] + $limit3_by_bonus[$n]; //зарезервированные средства для уже сведенных обменов
            $total_processing_fee_by_bonus[$n] =  $reverce_processing_fee_by_bonus[$n] + $direct_processing_fee_by_bonus[$n];
        //зарезервированные средства на комиссию
            $total_processing_p2p_by_bonus[$n] = $net_processing_p2p_by_bonus[$n] + $total_processing_fee_by_bonus[$n]; //сумма зарезервированных средств, включая комиссию по сведенным заявкам
        }


        $res = array(
            'limit1'    => $limit1,
            'limit2'    => $limit2,
            'limit3'    => $limit3,
            'net_processing_p2p'    => $net_processing_p2p,
            'total_processing_fee'  => $total_processing_fee,
            'total_processing_p2p'  => $total_processing_p2p,

            'limit1_fee_by_bonus'    => $limit1_by_bonus,
            'limit2_fee_by_bonus'    => $limit2_by_bonus,
            'limit3_fee_by_bonus'    => $limit3_by_bonus,
            'net_processing_p2p_fee_by_bonus'    => $net_processing_p2p_by_bonus,
            'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
            'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus
        );


//        echo "$limit1  $limit2 $limit3|";

        return $res;
    }
    public function currency_exchange_scores_old($current_user_id = false)
    {


        if($current_user_id === false)
        {
            $current_user_id = $this->accaunt->get_user_id();
        }

        if( $current_user_id == 13168924  || $current_user_id == 32048468 ) return $this->currency_exchange_scores_v3($current_user_id);

        return $this->currency_exchange_scores_v2($current_user_id);

        $this->load->model('currency_exchange_model','currency_exchange');

        #только выставлены
        $status_pending = array( 8, 9, 10 );
        $orders_src = $res = $this->db->where("seller_user_id", $current_user_id)
                                    ->where("buyer_user_id", 0)
                                    ->where_in('status', $status_pending )
                                    ->order_by('id', 'DESC')
                                    ->get( 'currency_exchange_orders' )
                                    ->result();

//        if( $current_user_id == 48882288 ) vred($orders_src);
        $orders = array();
        foreach( $orders_src as $o )
        {
            $o->archive = 0;
            $orders[] = $o;
        }

        $orders_archive_src = $res = $this->db
                                    ->where("seller_user_id", $current_user_id)
                                    ->order_by('id', 'DESC')
                                    ->get( 'currency_exchange_orders_arhive' )
                                    ->result();

        foreach( $orders_archive_src as $o )
        {
            $o->archive = 1;
            $orders[] = $o;
        }

        if( 00&& $current_user_id == 49028821 )
        {
            foreach( $orders as $o )
            {
                echo "ID:$o->id SE:$o->seller_user_id BY:$o->buyer_user_id B:$o->bonus A:$o->archive S:$o->status I:$o->initiator WT: $o->wt_set<br>";
            }
        }

        $limit1 = 0;
        $limit2 = 0;
        $limit3 = 0;
        $net_processing_p2p = 0;
        $total_processing_fee = 0;
        $total_processing_p2p = 0;

        $null_array_by_bonus = array( 0 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 =>0 );
        $limit1_by_bonus = $null_array_by_bonus;
        $limit2_by_bonus = $null_array_by_bonus;
        $limit3_by_bonus = $null_array_by_bonus;
        $net_processing_p2p_by_bonus = $null_array_by_bonus;
        $total_processing_fee_by_bonus = $null_array_by_bonus;
        $total_processing_p2p_by_bonus = $null_array_by_bonus;

        $reverce_processing_fee_by_bonus = $null_array_by_bonus;
        $direct_processing_fee_by_bonus = $null_array_by_bonus;

        if( empty( $orders ) )
        {
            return array(
                'limit1'    => $limit1,
                'limit2'    => $limit2,
                'limit3'    => $limit3,
                'net_processing_p2p'    => $net_processing_p2p,
                'total_processing_fee'  => $total_processing_fee,
                'total_processing_p2p'  => $total_processing_p2p,

                'limit1_by_bonus'    => $limit1_by_bonus,
                'limit2_by_bonus'    => $limit2_by_bonus,
                'limit3_by_bonus'    => $limit3_by_bonus,
                'net_processing_p2p_by_bonus'    => $net_processing_p2p_by_bonus,
                'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
                'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus

            );
        }



//        const ORDER_STATUS_PROCESSING_SB = 8; //на проверке у СБ
//    const ORDER_STATUS_PROCESSING = 9; //на проверке у опервтора
//    const ORDER_STATUS_SET_OUT = 10; //выставлена на продажу
//    const ORDER_STATUS_CONFIRMATION = 20;
//    const ORDER_STATUS_PENDING_DOCUMENT = 21; //в ожидании загрузки документа о перевдое

//    const ORDER_STATUS_HAVE_PROBLEM = 30;
//
//    const ORDER_STATUS_SUCCESS = 60;
//
//    const ORDER_STATUS_UNSUCCESS = 70; //неуспешна про причине, связанной с банком

//    const ORDER_STATUS_CANCELED = 81;  //сам пользоатель отменил
//    const ORDER_STATUS_OPERATOR_CANCELED = 82; //оператор отменил
//    const ORDER_STATUS_CANCELED_BY_USER_BLOCK = 83; // отмена заявки после блокировки пользователя
//    const ORDER_STATUS_ANNULLED = 90; //аннулирована админов
//    const ORDER_STATUS_REMOVED = 100; //Удалено пользователем

        #если не входит в число этис статусов - значит статус считаем активным
        $status_active = array( 60, 70, 81, 82, 84, 85, 90, 100 ); // 81, 82, 83 ????
        $time_blocking = strtotime('2015-09-04 00:00:00');

        $reverce_processing_fee = 0;
        $direct_processing_fee = 0;
        $total_processing_p2p = 0;
        $total_processing_fee = 0;


        $this->load->model('currency_exchange_model','currency_exchange');
        foreach( $orders as $order )
        {
            if( !isset( $order->bonus ) || empty( $order->bonus ) ) $order->bonus = 5;
//            if($order->wt_set == 2 &&
//                    ($order->buyer_send_money_date !='0000-00-00 00:00:00' ||
//                     $order->seller_send_money_date != '0000-00-00 00:00:00')
//            )
//            {
//                vre('таймер истёк 1');
//               //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date)+24*3600 <= time())
////                vre($order->id);
//                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
////                vre(strtotime($order->seller_confirmation_date) , time());
////                if($order->initiator == 0 && strtotime($order->seller_confirmation_date) <= time())
////                {
////                    echo 'таймер истёк 2<br/>';
////                }
//            }
            if(false && in_array($current_user_id, $this->p2p_test_users))
            {
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')

                )
                {
    //                vre('таймер истёк 1');
    //                vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 1 && strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 2<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
    //                    1
                        $this->currency_exchange->user_block($order, 1);
                        $this->currency_exchange->set_order_canceld_by_user_block($id, 1);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 3<br/>';
    //                    block_user('Залокирован по истечению таймера отправки дежных средст.')
                        //2
                        $this->currency_exchange->user_block($order, 2);
                        $this->currency_exchange->set_order_canceld_by_user_block($id);
                    }
                }

                //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 2 &&
                        ($order->buyer_send_money_date == '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date != '0000-00-00 00:00:00')
                )
                {
                   //Покупка вебтрансферов, заявка сведена увказаны реквизиты.  wt_status = 2
                    if($order->initiator == 0 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 4<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //3
                        $this->currency_exchange->user_block($order, 3);
                    }
                }

                 //Продажа вебтрансферов, заявка сведена увказаны реквизиты.  wt_set = 1
                if($order->wt_set == 1 &&
                        ($order->buyer_send_money_date != '0000-00-00 00:00:00' &&
                         $order->seller_send_money_date == '0000-00-00 00:00:00')
                )
                {
                    if(     $order->initiator == 1 &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT > $time_blocking &&
                            strtotime($order->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_RECEIVED_WT <= time())
                    {
    //                    vre($order->id, strtotime($order->seller_confirmation_date)+24*3600 , time());
    //                    echo 'таймер истёк 5<br/>';
    //                    block_user('Залокирован по истечению таймера подтверждения получения дежных средст.')
                        //4
                        $this->currency_exchange->user_block($order, 4);
                    }
                }
            }



            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 1
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 1 &&
                ( in_array( $order->status, $status_pending ) || $order->initiator == 1 )
                && !in_array( $order->status, $status_active )
            ){
                $limit1 += $order->seller_amount;
                $limit1_by_bonus[$order->bonus] += $order->seller_amount;

                //if( $current_user_id == 99680465 ) echo "1::$order->original_order_id $order->id<br>";
            }
            #Сумма всех сумм всех активных заявок, в которых пользователь = инициатор И ВТ = 0
            if( $order->seller_user_id == $current_user_id && $order->wt_set == 0 &&
                (in_array( $order->status, $status_pending ) || $order->initiator == 1 ) &&
                !in_array( $order->status, $status_active ) )
            {
                $limit2 += $order->seller_amount;
                $limit2_by_bonus[$order->bonus] += $order->seller_amount;

                //if( $current_user_id == 99680465 ) echo "2::$order->original_order_id $order->id<br>";
            }

            //ID:89825 SE:49028821 BY:0 B:2 A:1 S:9 I:0 WT: 2
            #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
            if( ( in_array( $order->status, $status_pending ) || $order->initiator == 0 )
                && !in_array( $order->status, $status_active )  )
            {
                if(
                    ($order->buyer_user_id == $current_user_id && $order->wt_set == 2 ) //||
                    //($order->seller_user_id == $current_user_id && $order->wt_set == 2 )
                ){
                    $limit3 += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                    $reverce_processing_fee += $order->seller_fee;  #Сумма всех комиссий c биржи

                    $limit3_by_bonus[$order->bonus] += $order->seller_amount;           #Сумма всех сумм всех активных заявок, в которых пользователь отдает ВТ c биржи
                    $reverce_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;  #Сумма всех комиссий c биржи

                    //if( $current_user_id == 99680465 ) echo "3::$order->original_order_id $order->id<br>";
                }
            }

            if( $order->seller_user_id == $current_user_id && $order->wt_set != 2 &&
                ( in_array( $order->status, $status_pending ) ) &&
                !in_array( $order->status, $status_active )  )
            {
                $direct_processing_fee += $order->seller_fee;

                $direct_processing_fee_by_bonus[$order->bonus] += $order->seller_fee;

                //if( $current_user_id == 99680465 ) echo "4::$order->original_order_id $order->id<br>";
            }

        }



        $net_processing_p2p = $limit1 + $limit2 + $limit3; //зарезервированные средства для уже сведенных обменов
        $total_processing_fee =  $reverce_processing_fee + $direct_processing_fee; //зарезервированные средства на комиссию
        $total_processing_p2p = $net_processing_p2p + $total_processing_fee; //сумма зарезервированных средств, включая комиссию по сведенным заявкам

        $n = 2;


        $bonus = array_keys( $null_array_by_bonus );
        foreach(  $bonus as $n )
        {
//            if( $current_user_id == 500733 )
//            {
//                //echo "B:$n $net_processing_p2p_by_bonus[$n] = $limit1_by_bonus[$n] + $limit2_by_bonus[$n] + $limit3_by_bonus[$n]<br>";
//            }
            $net_processing_p2p_by_bonus[$n] = $limit1_by_bonus[$n] + $limit2_by_bonus[$n] + $limit3_by_bonus[$n]; //зарезервированные средства для уже сведенных обменов
            $total_processing_fee_by_bonus[$n] =  $reverce_processing_fee_by_bonus[$n] + $direct_processing_fee_by_bonus[$n];
        //зарезервированные средства на комиссию
            $total_processing_p2p_by_bonus[$n] = $net_processing_p2p_by_bonus[$n] + $total_processing_fee_by_bonus[$n]; //сумма зарезервированных средств, включая комиссию по сведенным заявкам
        }


        $res = array(
            'limit1'    => $limit1,
            'limit2'    => $limit2,
            'limit3'    => $limit3,
            'net_processing_p2p'    => $net_processing_p2p,
            'total_processing_fee'  => $total_processing_fee,
            'total_processing_p2p'  => $total_processing_p2p,

            'limit1_fee_by_bonus'    => $limit1_by_bonus,
            'limit2_fee_by_bonus'    => $limit2_by_bonus,
            'limit3_fee_by_bonus'    => $limit3_by_bonus,
            'net_processing_p2p_fee_by_bonus'    => $net_processing_p2p_by_bonus,
            'total_processing_fee_by_bonus'  => $total_processing_fee_by_bonus,
            'total_processing_p2p_by_bonus'  => $total_processing_p2p_by_bonus
        );


//        echo "$limit1  $limit2 $limit3|";

        return $res;
    }

    public function set_single_payment_system( $order_id )
    {

        if( empty( $order_id ) ) return FALSE;

        if( is_numeric($order_id ) ) $orig_order_data = $this->get_original_order_by_id( $order_id );
        else
        {
            $orig_order_data = $order_id;
            $order_id = $orig_order_data->id;
        }

        $sell_payment_data = unserialize( $orig_order_data->sell_payment_data )[0];
        unset( $orig_order_data->buy_payment_data );
        unset( $orig_order_data->sell_payment_data );


        $payment_systems =
        $this->db->select('id,choice_currecy,payment_system,summa,type')
                ->where('order_id', $order_id)
                ->limit(15)
                ->get($this->table_order_details)
                ->result();

        $orig_order_data->payment_systems = [];
        $wt_ps_id = null;
        foreach( $payment_systems as $ps )
        {

            if($wt_ps_id === null && $this->is_root_currency($ps->payment_system))
            {
                $wt_ps_id = $ps->payment_system;
                $summa_wt = $ps->summa;
                $currency_id = ( empty( $ps->choice_currecy )? NULL : $ps->choice_currecy );
            }

            $orig_order_data->payment_systems[ $ps->id ] = $ps;
        }

        if($wt_ps_id === null )
        {

            foreach( $payment_systems as $ps )
            {
                if($wt_ps_id !== null ) break;
                if( $sell_payment_data->payment_system_id != $ps->payment_system ) continue;

                $wt_ps_id = $ps->payment_system;
                $summa_wt = $ps->summa;
                $currency_id = ( empty( $ps->choice_currecy )? NULL : $ps->choice_currecy );

            }

        }


        $orig_order_data = $this->set_fee_to_order($orig_order_data);
        $orig_order_data = $this->set_machin_name_to_payment_systems($orig_order_data);
        $orig_order_data = $this->set_dop_data_to_unserializ_payment_system($orig_order_data);

        $data = array(
            'single_currency'          => $currency_id,
            'single_payment_system'    => $wt_ps_id,
            'single_summa'             => $summa_wt,
            'order_wt_set'       => $orig_order_data->wt_set,
            'orig_order_data'   => serialize( $orig_order_data ),
        );

        try
        {
            $this->db->where('order_id',$order_id)
                    ->update( $this->table_order_details, $data );

        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }

    }

    private function _add_payment_system( $order_id, $type, $payment_id, $summa, $choice_currecy, $priority = 0 )
    {
        if( empty( $order_id ) ) return FALSE;

        if( is_numeric($order_id ) ) $orig_order_data = $this->get_original_order_by_id( $order_id );
        else
        {
            $orig_order_data = $order_id;
            $order_id = $orig_order_data->id;
        }

        if( !is_numeric($priority) ) $priority = 0;
        
        $data = array(
            'priority'          => $priority,
            'order_id'          => $order_id,
            'type'              => $type,
            'payment_system'    => $payment_id,
            'summa'             => $summa,
            'orig_order_data'   => '',//serialize( $orig_order_data ),
            'order_status'      => $orig_order_data->status,
            'order_bonus'       => $orig_order_data->bonus,
            'order_wt_set'       => $orig_order_data->wt_set,
            'choice_currecy'    => is_null($choice_currecy)?'':$choice_currecy,
        );

        try
        {
            $this->db->insert( $this->table_order_details, $data );
        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
        //pre($this->db->last_query());

        return TRUE;
    }


    public function get_payment_systems_by_id($id)
    {

        if(is_numeric($id) )
        {
            $res = $this->db
                    ->where('id', $id)
                    ->limit(1)
                    ->get( $this->table_payment_systems )
                    ->row();
        }elseif(is_array($id)){
            $res = $this->db
                    ->where_in('id', $id)
                    ->limit(count( $id ))
                    ->get( $this->table_payment_systems )
                    ->result();
        }
        return $res;
    }


    public function add_file($fild_name, $id = '', $upload_dir = 'upload/currency_exchange_docs/')
    {
        $this->load->library('image');
        $this->image->place = "upload/tmp/";

        $image = $this->image;
        $foto = $image->file($fild_name, true);

        if (in_array($foto, array(1, 2, 3), true))
        {
            return false;
        }

        if($foto['file']['size'] > 2*1024*1024)
        {
            return 10;
        }
        else
        {
            $bonus = 2;
        }

        $types = [
            'image/jpeg',
            'image/png',
            'image/gif',
        ];

        if(!in_array($foto['file']['type'], $types))
        {
            return 20;
        }

        $add_foto = $image->add_foto($foto);

        if (!empty($add_foto))
        {
             $explode_res = explode('.', $add_foto);

             $file_name = md5($id.rand(1111,9999).rand(1111,9999).rand(111,999).time()).'.'.$explode_res[1];
//             $full_file_name = 'upload/currency_exchange_docs/' . $file_name;
             $full_file_name = $upload_dir. $file_name;

             $tmpPath = $this->image->place . $add_foto;  //имя файла
             $this->code->fileCodeSave($full_file_name, $this->code->fileCode($tmpPath));

             @unlink($tmpPath);
             return $full_file_name;
        }
        else
        {
            @unlink($tmpPath);
            return false;
        }
    }


     /*
     * Получает группу платежной системы
     */
    public function get_payment_systems_group($id){
            $res = $this->db
                            ->where('id', $id)
                            ->get( $this->table_payment_systems_groups )
                            ->row();
            if( empty( $res ) )
            {
                return FALSE;
            }
            return $res;
    }

    /*
     * Получает полный список групп платежных систем
     */
    public function get_full_list_payment_systems_groups($parent_id = 0){
            if ( $parent_id >= 0 )
                $this->db->where('parent_id', $parent_id);
            $res = $this->db
                            ->order_by('order')
                            ->get( $this->table_payment_systems_groups )
                            ->result();
            if( empty( $res ) )
            {
                return FALSE;
            }
            return $res;
    }

    /*
     * Получает полный список групп платежных систем
     */
    public function get_full_list_payment_systems($group_id = 0 ){

            $res = $this->db->where('group_id', $group_id)
                            ->order_by('order')
                            ->get( $this->table_payment_systems )
                            ->result();
            if( empty( $res ) )
            {
                return FALSE;
            }
            return $res;
    }



    public function get_all_payment_systems( $present_in = NULL, $present_out = NULL, $active = null, $order ='ASC' )
    {
        if(empty($present_in) &&
                empty($present_out) &&
                empty($active) && $order == 'ASC' &&
                $this->get_all_payment_systems != FALSE
//                self::$_other_ps_arr !== FALSE
                )
        {
            return $this->get_all_payment_systems;
//            return self::$_other_ps_arr;
        }

        if( $active !== null && is_bool( $active ) )
        {
            $this->db->where( 'active', $active );
        }

        if( $present_in !== NULL && $present_out !== NULL )
        {
            $payment_systems = $this->db->where( array( 'present_in' => $present_in, 'present_out' => $present_out ) );
        }

        $payment_systems = $this->db
                ->where('active', 1)
                ->where('is_bank', 0)
                ->order_by('order')
                ->get( $this->table_payment_systems )
                ->result();

        if( empty( $payment_systems ) )
        {
            return FALSE;
        }

        foreach( $payment_systems as $key => $ps )
        {
            $payment_systems[ $ps->machine_name ] = $ps;
            unset( $payment_systems[ $key ] );
        }

        $this->get_all_payment_systems = $payment_systems;
//        self::$_other_ps_arr = $payment_systems;

//        self::$_other_ps_arr_id = array_set_value_in_key($payment_systems);

        return $payment_systems;
    }



    public function get_payment_system_by_machin_name($machin_name)
    {
        if(empty($machin_name))
        {
            return false;
        }

        $result = $this->db
                    ->where('machine_name', $machin_name)
                    ->get($this->table_payment_systems)
                    ->row();

        return $result;
    }



    public function activate_payment_system( $id )
    {

    }



    public function disactivate_payment_system( $id )
    {

    }



//    public function calculate_fee_ps_id( $amount, $payment_system_id, $buy = false, $bonus = 5)
    public function calculate_fee_ps_id( $amount, $payment_system_id, $buy = false, $choice_currecy = false)
    {
        $all_payment_systems = $this->get_all_payment_systems();
        $all_payment_systems = array_set_value_in_key($all_payment_systems);

//        return $this->calculate_fee($amount, $all_payment_systems[$payment_system_id]->machine_name, $buy, $bonus);
        return $this->calculate_fee($amount, $all_payment_systems[$payment_system_id]->machine_name, $buy, $choice_currecy);
    }



    public function calculate_fee( $amount, $payment_system_machine_name, $buy = false, $choice_currecy = false)
    {
        $all_payment_systems = $this->get_all_payment_systems();
//        var_dump( $all_payment_systems );
//        var_dump( $payment_system_machine_name );
        if( empty( $amount ) || empty( $payment_system_machine_name ) ||
                empty( $all_payment_systems )
//                || !isset( $all_payment_systems[ $payment_system_machine_name ] )
                )
        {

            return FALSE;
        }
//        $ps = $all_payment_systems[ $payment_system_machine_name ];
        $ps = self::get_ps($payment_system_machine_name);

        if(empty($ps))
        {
            return false;
        }

        $rate = $this->get_currency_rate($ps, $choice_currecy);

        $amount = $amount/$rate;

        $total = $amount * $ps->fee_percentage / 100.0 + $ps->fee_percentage_add;

        if( $ps->fee_min > 0 && $total < $ps->fee_min )
        {
            $total = $ps->fee_min;
        }
        if( $ps->fee_max > 0 && $total > $ps->fee_max )
        {
            $total = $ps->fee_max;
        }

        if($buy === TRUE)
        {
            $amount -= $total;
        }

        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
//        $error = false;
//         $user_ratings = viewData()->accaunt_header;
//
//        if($ps->machine_name == 'wt' && $buy === true)
//        {
////            if($user_ratings['payout_limit'] < $amount)
////            {
////                $error = _('У вас недостаточно средств для совершения обмена');
////            }
//        }
//        elseif($ps->machine_name == 'wt' && $buy === FALSE)
//        {
//            if($user_ratings['payout_limit'] < $amount+$total)
//            {
//                $error = _('У вас недостаточно средств для совершения обмена и оплаты комиссии');
//            }
//            elseif($user_ratings['payout_limit'] < $amount)
//            {
//                $error = _('У вас недостаточно средств для совершения обмена');
//            }
//        }
//        else
//        {
//            if($user_ratings['payout_limit'] < $total)
//            {
//                $error = _('У вас недостаточно средств для оплаты комиссии');
//            }
//            elseif($user_ratings['payout_limit'] < $amount+$total)
//            {
//                $error = _('У вас недостаточно средств для совершения обмена и оплаты комиссии');
//            }
//        }
        $error = $this->_get_user_rating_error($ps->machine_name, $buy, $amount, $total);
//        $error = false;
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////
        return array($amount, $total ,$ps->fee_percentage, $error);
    }


    public function get_currency_rate($ps, $choice_currecy = false)
    {
        $rate = 1;

        if($choice_currecy !== false && $choice_currecy)
        {
            $ps->currency_id = $choice_currecy;
        }

        if(!empty($ps->method_calc_fee) && method_exists($this, $ps->method_calc_fee))
        {
//            $rate = $this->{$ps->method_calc_fee}($ps->machine_name, $ps->id);
            $rate = $this->{$ps->method_calc_fee}($ps);
            $rate = 1/$rate;
        }
        // проверяем если рубли
        // это уже не нужно
        elseif(false && 643 == $ps->currency_id)
        {
            //тогда извлекаем курс доллара на сегодяня и переводим рубли в доллары
            $rate = $this->get_exchange_rate_by_currency_char_code('USD');                        
        }
        elseif(980 == $ps->currency_id)
        {
            $rate = $this->get_exchange_rate_uah($ps->id);
        }
        else
        {
            $rate = $this->get_exchange_rate($ps->currency_id);
        }
        
        return $rate;
    }




    private function _get_user_rating_error($ps_machin_name, $buy, $amount, $fee)
    {
        $error = false;
        $user_ratings = viewData()->accaunt_header;

        // Случаи когда мы покупаем WT и случай когда нет wt
//        if(($ps_machin_name == 'wt' && $buy === true) || $ps_machin_name != 'wt')
//        if(($ps_machin_name == 'wt' && $buy === false) || $ps_machin_name != 'wt')
//
//        //TODO Ошибка нас не итересует случай когда wt нет, так как тогда не понятно какой брать бонус
//        if(($this->is_root_currency($ps_machin_name) && $buy === false) || !$this->is_root_currency($ps_machin_name))
        if(($this->is_root_currency($ps_machin_name) && $buy === false))
        {
            $root_currency = $this->get_root_currency($ps_machin_name);
            $bonus = $root_currency->transaction_bonus;
            #Без ВТ - проверка наличия суммы
//            if($user_ratings['payout_limit'] < $fee &&
//            if($user_ratings['payout_limit'] > $fee &&
//                    $user_ratings['max_loan_available'] < $fee + $amount )

            if($user_ratings['payout_limit_by_bonus'][$bonus] > $fee &&
                    $user_ratings['payout_limit_by_bonus'][$bonus] < $fee + $amount )
            {
                $error = _e('У вас недостаточно средств для совершения обмена и оплаты комиссии');
            }
            #Без ВТ - проверка наличия комиссии
            elseif($user_ratings['payout_limit_by_bonus'][$bonus] < $fee )
            {
                $error = _e('У вас недостаточно средств для оплаты комиссии');
            }
        }

        return $error;
    }


    public function check_order_double_items_status_20( $order_id )
    {
        if( empty( $order_id ) || !is_numeric( $order_id ) ) return FALSE;

        $orig_orders = $this->db->select('id')
                                ->where( 'id', $order_id )
                                ->where_in( 'status', array(8,9,10,20) )
                                ->get( $this->table_orders )
                                ->result();

        if( count( $orig_orders ) == 0 ) return TRUE;

        //есть активные заявки у выставленной заявки
        $orders_arch = $this->db->select('id')
                ->where( 'original_order_id', $order_id )
                ->where_in( 'status', array(8,9,10,20,60) )
                ->get( $this->table_orders_arhive )
                ->result();

        if( count( $orders_arch ) > 0 ) return FALSE;


        return TRUE;
    }

    //function check3(){
    public function check_user_rating_to_order_confirm($order )
    {
        $error = false;
        $user_ratings = viewData()->accaunt_header;

        if( !isset( $order->bonus ) ) $order->bonus = 5;

        if( $order->wt_set == 2 && $user_ratings['payout_limit_by_bonus'][$order->bonus] < $order->seller_amount )
        {
             $error = _e('У вас недостаточно средств для совершения обмена');
        }

        if( $order->wt_set == 2 && $user_ratings['payout_limit_by_bonus'][$order->bonus] < $order->seller_amount + $order->seller_fee )
        {
             $error = _e('У вас недостаточно средств для оплаты суммы перевода и комиссии');
        }

        $order_id = $order->id;

        //проверка на двойные записи по оригинальной заявке
        $res_check_order_double_items = $this->check_order_double_items_status_20( $order_id );

        //отправляем на проверку СБ + запись для админа
        if(0&& $res_check_order_double_items === FALSE )
        {
            $note_arr = [
                        'order_id' => $order_id,
                        'text' => 'Неверные статусы в оригинальной заявке',
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

            $this->add_operator_note($note_arr);

            $this->set_status_to_details_orig_order( $order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB );

            $error = _e('Заявку уже забрал другой пользователь. Обновите страницу и повторите поиск.');
        }

        return $error;
    }

    /**
     * проверка пользователя на наличие средств если нет потом отправляем в SB
     */

    public function check_user_initiator_rating_to_order_confirm($order)
    {
        if(empty($order))
        {
            return false;
        }

        $error = false;
        $user_id = $order->seller_user_id;

        if(empty($user_id))
        {
            return false;
        }


        $user_ratings = $this->account->recalculateUserRating($user_id);

        if( empty( $order->bonus ) ) $order->bonus = 5;

//        if( $user_ratings['payout_limit_by_bonus'][$order->bonus] < $order->seller_amount )

        if( $user_ratings['payout_limit_by_bonus'][$order->bonus] < 0 )
        {
            return false;
        }

        return true;
    }





    /**
     * Получение кода выборки платежных систем для фильтра
     *
     * @param type $payment_systems
     * @return boolean
     */
    private function get_payment_system_set( $payment_systems )
    {
        if( empty( $payment_systems ) )
        {
            return FALSE;
        }

        $all_payment_systems = $this->get_all_payment_systems();
//vred($all_payment_systems);
        //создаем номер для выборки систем
        $bin_id = '';
        foreach( $all_payment_systems as $key => $val )
        {
//            if( $val == 1 && isset( $payment_systems[ $key ] ) )
            if( !empty($val) && isset( $payment_systems[ $key ] ) )
            {
                $bin_id = '1' . $bin_id;
                continue;
            }

            $bin_id = '0' . $bin_id;
        }

        $dec_id = bindec( $bin_id );

        return $dec_id;
    }


    public function set_buyer_confirmation($order_id, $value) {
        $this->db->where('id', $order_id)
            ->update($this->table_orders_arhive, array('buyer_confirmed' => $value));
    }

    public function set_success_order($order_id) {
        if(empty($order_id)) return false;

        if(empty($this->get_original_order_by_id($order_id))) return false;

        $this->set_status_to_orig_order($order_id, self::ORDER_STATUS_SUCCESS);

        $archive_orders = $this->get_arhiv_orders_by_parent_id($order_id);

        foreach($archive_orders as $archive_order) {
            $this->set_buyer_confirmation($archive_order->id, 1);
            $this->set_status_archive_orders($archive_order->id, self::ORDER_STATUS_SUCCESS);
        }

        return true;
    }
    

    public function set_confirmation_step($order_id, $step) {
        $this->db->where('original_order_id', $order_id)
            ->update($this->table_orders_arhive, array('confirmation_step' => $step));
    }

    public function set_confirmation_step_by_user_id_and_orig_id($order_id, $step, $user_id) {
        if(empty($order_id) || empty($step) || empty($user_id))
            return false;


        $this->db->where('original_order_id', $order_id)
            ->where('seller_user_id', $user_id)
            ->update($this->table_orders_arhive, array('confirmation_step' => $step));
    }

    public function is_user_in_this_order_archive($user_id, $original_order_id) {
        if(empty($user_id || empty($original_order_id))) 
            return false;

        $q = $this->db->where('original_order_id', $original_order_id)
            ->where('seller_user_id', $user_id)
            ->get($this->table_orders_arhive)
            ->result();

        if (empty($q)) 
            return false;
        return $q;
    }

    public function get_order_archive_only_initiator($original_order_id) {
        if(empty($original_order_id)) return false;

        $q = $this->db->where('original_order_id', $original_order_id)
            ->where('initiator' , 1)
            ->get($this->table_orders_arhive)
            ->result();

        if(empty($q))
            return false;
        return $q[0];
    }

    private function _is_success_visa_transaction($visa_resp) {
        if(!empty($visa_resp['errorDetails'])) {
            if( $visa_resp['errorDetails'][0]['errorDescription'] == 'Success')
                return true;
        }
        return false;
    } 

    private function _is_problem_visa_limit($visa_resp) {
        if( empty( $visa_resp ) ) return TRUE;
        if(!empty($visa_resp['errorDetails'])) {
            if( $visa_resp['errorDetails'][0]['errorDescription'] == 'Transaction amount is greater than the maximum amount allowed per day'
            || $visa_resp['errorDetails'][0]['errorCode'] == 10002 )//Exceeds lifetime limit
                return true;
        }
        return false;
    }

    public function set_delayed_take_money($order_id) {
        if(empty($order_id)) return false;

        // Нужно будет проверять, кому идут деньги, сейчас контрагенту.
        // А потом как то контролировать что это будет инициатор и выдавать по другому!
        $this->db->where('original_order_id', $order_id)
            ->where('initiator' , 0)
            ->update($this->table_orders_arhive, array('confirmation_step' => 10));

        $this->db->where('original_order_id', $order_id)
            ->where('initiator', 1)
            ->update($this->table_orders_arhive, array('status' => 60));

        $archive_orders = $this->get_arhiv_orders_by_parent_id($order_id);

        foreach($archive_orders as $archive_order) {
            $this->currency_exchange->set_buyer_confirmation($archive_order->id, 1);
        }
    }

    public function prefund_second_transaction($options) {
        $pr = validate_options( [ 
                'card_id'   => 'required', 
                'amount'    => 'required', 
                'order_id'  => 'required', 
                'user_id'   => 'required' ], $options );
        extract($pr);

        $this->load->model('card_model', 'card_model');
        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->load->model('Card_transactions_model', 'card_transactions_model');


        $this->db->trans_start();

        $card_to = $this->card_model->getUserCard($card_id, $user_id);

        if(empty($card_to)) {
            return false;   
        }

        $balance = $this->card_model->getBalance($card_to);

        $prefund_order = $this->prefund->get_by_order_id($order_id);
        if(empty($prefund_order[0])) return false;

        if($prefund_order[0]->amount != $amount) return false;


        $id_rand = time().'_PMCRON'.rand(1,1000);      
        $loadMoney_data = new stdClass();
        $loadMoney_data->id = $id_rand;
        $loadMoney_data->return_full_resp = true;
        $loadMoney_data->card_id = $card_id;
        $loadMoney_data->user_id = $user_id;
        $loadMoney_data->summa = $amount;
        $loadMoney_data->desc = 'P2P order id: '.$order_id;//load to user from prefund
        $response = $this->card_model->load($loadMoney_data, card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);
        $this->card_model->getBalance($card_to);

        if($this->_is_success_visa_transaction($response)) {
//        if(false) {
            $this->prefund->set_paired_by_value($prefund_order[0]->value, 1);
            $data = [
                'credit_cid'             => $card_to->id,
                'credit_uid'             => $user_id,
                'credit_current_score'   => $balance,
                'paired'                => 1,
                'amount'                => $amount,
                'type'                  => Card_prefunding_transactions_model::PREFUND_TRANS_COME,
                'value'                 => $order_id,
                'note'                  => 'come money to user',
                'date'                  => date('Y-m-d H:i:s')
            ];
            $send_money_res = $this->prefund->send_money($data);

            if($send_money_res === false) return false;

            $this->db->trans_complete();
            return true;

        }

        if($this->_is_problem_visa_limit($response)) {
//        if(true) {
            $user_id_who_take_money = $user_id;
            $user_id_waiter = $prefund_order[0]->debet_uid;
            if(empty($user_id_waiter) || empty($user_id_who_take_money)) die('do not have id users');

//            var_dump($user_id_who_take_money);
//            var_dump($user_id_waiter);
            // А теперь ставим статусы. инициатору ставим 25 а ожидающуму 26
            $this->set_confirmation_step_by_user_id_and_orig_id($order_id, self::ORDER_STEP_LATE_WITHDRAWAL_TAKER, $user_id_who_take_money);
            $this->set_confirmation_step_by_user_id_and_orig_id($order_id, self::ORDER_STEP_LATE_WITHDRAWAL_WAITER, $user_id_waiter);


//            $this->set_confirmation_step( $order_id, self::ORDER_STEP_LATE_WITHDRAWAL_INITIATOR );
            $this->db->trans_complete();
            return false;
            die();
        }
        
        //go to sb         
        $note_arr = [
            'order_id' => $order_id,
            'text' => 'Произошла ошибка при зачислении денег на карту: '.$response['errorDetails'][0]['errorDescription'],
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        $this->currency_exchange->add_operator_note($note_arr);
        $this->currency_exchange->set_status_to_order_arhive_and_order($order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);
        
        $this->db->trans_complete();
        return false;
    }

    public function get_comission_from_amount($amount) {
        if(empty($amount))
            return false;
        // 1%
        return $amount / 100;
    }

    public function prefund_first_transaction($options) {
        $pr = validate_options( [ 
                'card_id'   => 'required', 
                'amount'    => 'required', 
                'order_id'  => 'required', 
                'user_id'   => 'required' ], $options );
        extract($pr);

        $comission = $this->get_comission_from_amount($amount);

        $this->load->model('card_model', 'card_model');
        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->load->model('Card_transactions_model', 'card_transactions_model');

        $card_from = $this->card_model->getUserCard($card_id, $user_id);
        $balance_from = $this->card_model->getBalance($card_from);

        $res = ['error' => 1]; 

        // $resp = $this->card_model->updateCachedBalance($card_id, 4444);

        if($amount > $balance_from) {
            $res['show'] = 1;
            //$res['text'] =  _e('You have not enough money in the account');
            $res['text'] =  _e('You don\'t have enough funds on the card. Please, input smaller amount or select different card.');
            return $res;
        } 

        $id_rand = time().'_PMCRON'.rand(1,1000);
        $purchaseMoney_data = [];
        $purchaseMoney_data['card_id']  = $card_id;
        $purchaseMoney_data['user_id']  = $user_id;
        $purchaseMoney_data['id']       = $id_rand;
        $purchaseMoney_data['summa']    = $amount + $comission;
        $purchaseMoney_data['desc']     = 'P2P order id:'. $order_id;//purchase from visa to prefund. order_id
        
        $response = $this->card_model->purchaseMoney($purchaseMoney_data, card_transactions_model::CARD_TRANS_PREFUND_LOAD, $id_rand);


        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($card_from->card_user_id, $card_from->card_proxy);
        $transactions = $wtcapi->transactions(1);

        if(empty($transactions)) {
            $res['error'] = _e('Ответ visa api ничего не вернул при снятии денег с карты');
            return false;
        } 

        $last_trans =  reset($transactions);

        if($response != $last_trans['transactionId']) {
            $res['error'] = _e('Ответ visa api не соответствует id транзакции в visa api');
            return false;
        }

        if($response === false) {
            $res['error'] = _e('С данной карты невозможно произвести операцию. Измените карту и попробуйте снова.');
            return $res;    
        }

        if($last_trans['status'] != 'Success') {
            // print_r($last_trans);
            // die;
             $res['error'] = _e('Не получилось выполнить транзакцию');
            return false;
        }


      
        

       
        // $response_from_visa_api = $this->card_transactions_model->get_last_trans_from_card($card_id);
        // $is_success_visa_trans = $this->card_transactions_model->is_success_trans_by_api_response($response_from_visa_api->api_response);

        // if($is_success_visa_trans === false) {
        //     return false;    
        // }
        // var_dump($res);
        // die;
        $data = [
            'debet_cid'             => $card_from->id,
            'debet_uid'             => $user_id,
            'debet_current_score'   => $balance_from,
            'paired'                => 0,
            'amount'                => $amount,
            'type'                  => Card_prefunding_transactions_model::PREFUND_TRANS_LOAD,
            'value'                 => $order_id,
            'note'                  => 'P2P deposit funds, order id'.$order_id,//send money to prefund
            'date'                  => date('Y-m-d H:i:s')
        ];

        $send_money_res = $this->prefund->send_money($data);

        // Отправляем комиссию в размере 1%
        
        $data_comission = [
            'debet_cid'             => $card_from->id,
            'credit_cid'            => 'p2p_comission',
            'debet_uid'             => $user_id,
            'credit_uid'            => 'p2p_comission',
            'debet_current_score'   => $balance_from,
            'paired'                => 0,
            'amount'                => $comission,
            'type'                  => Card_prefunding_transactions_model::PREFUND_TRANS_LOAD_COMMISION,
            'value'                 => $order_id,
            'note'                  => 'P2P fee, order id: '.$order_id, //widthdrawal fee to prefund
            'date'                  => date('Y-m-d H:i:s')
        ];

        $send_money_res2 = $this->prefund->send_money($data_comission);


        if($send_money_res === true && $send_money_res2 === true) {
            $res['success']  = '1';
            unset($res['error']);
        } else {
            $res['text'] = 'Failed to create the transaction';
        }

        return $res;
    }


    public function add_order( $type, $status, $user_id, $sell_data, $buy_data )
    {
//        pre($buy_data);
//        pre($sell_data);
//        vre($user_id, $sell_data, $buy_data);
        if( empty( $user_id ) || empty( $sell_data ) || empty( $buy_data ) )
        {
            return FALSE;
        }
//        vred('>>>>');
        $seller_payment_system_set = $this->get_payment_system_set( $sell_data[ 'payment_systems' ] );
        $buyer_payment_system_set = $this->get_payment_system_set( $buy_data[ 'payment_systems' ] );

//        if($type == self::ORDER_TYPE_SELL_WT)
//        {
//            $user_pay_sys = $this->get_user_paymant_data_for_orders($user_id, $buy_data['payment_systems']);
//        }
//        elseif($type == self::ORDER_TYPE_BUY_WT)
//        {
//            $user_pay_sys = $this->_get_user_paymant_data_for_orders_by_buy_type($user_id, $buy_data['payment_systems']);
//        }

        $user_pay_sys_buy = $this->get_user_paymant_data_for_orders($user_id, $buy_data['payment_systems']);
//        $user_pay_sys_buy = $this->get_user_paymant_data_for_orders($user_id, $sell_data['payment_systems']);

        $user_pay_sys_sell = $this->_get_user_paymant_data_for_orders_by_buy_type($user_id, $sell_data['payment_systems']);

//        pre($user_pay_sys_buy);
//        pre($user_pay_sys_sell);
//        pred('>>>');

        $user_pay_sys_serialize_sell = '';
        $user_pay_sys_serialize_buy = '';

        if(!empty($user_pay_sys_sell))
        {
            $user_pay_sys_serialize_sell = serialize($user_pay_sys_sell);
        }

        if(!empty($user_pay_sys_buy))
        {
            $user_pay_sys_serialize_buy = serialize($user_pay_sys_buy);
        }

        $wt_set = 0;
        $root_currency = false;
        $bonus = 0;
//        if(array_key_exists('wt', $sell_data[ 'payment_systems' ]) || array_key_exists('wt', $buy_data[ 'payment_systems' ]))

//        if(array_key_exists('wt', $sell_data[ 'payment_systems' ]) )
        if($this->is_root_currency_in_array($sell_data[ 'payment_systems' ]))
        {
            $root_currency = $this->get_root_currency_from_array($sell_data[ 'payment_systems' ]);
            $bonus = $root_currency->transaction_bonus;
            $wt_set = 1;
        }
//        elseif(array_key_exists('wt', $buy_data[ 'payment_systems' ]))
        elseif($this->is_root_currency_in_array($buy_data[ 'payment_systems' ]))
        {
            $root_currency = $this->get_root_currency_from_array($buy_data[ 'payment_systems' ]);
            $bonus = $root_currency->transaction_bonus;
            $wt_set = 2;
        }
        else
        {
            $bonus = 6;
        }

        if($bonus == 0)
        {
            return false;
        }

        $data = array(
            'seller_user_id'            => $user_id,
            'server_id'                 => 0,
            'seller_ip'                 => $this->input->ip_address(),
            'seller_amount'             => $sell_data[ 'amount' ],
            'seller_fee'                => $sell_data[ 'fee' ],
            'seller_set_up_date'        => date( 'd-m-Y H:i:s' ),

            'seller_confirmation_date'  => null,
            'seller_payout_limit'       => 0,
            'seller_transaction_id'     => 0,
            'seller_confirmed'          => 0,
            'seller_payment_system_set' => $seller_payment_system_set,
            'buyer_user_id'             => 0,
            'buyer_account_data'        => null,
            'buyer_amount_down'         => $buy_data[ 'amount_down' ],
            'buyer_amount_up'           => $buy_data[ 'amount_up' ],
            'buyer_confirmed'           => 0,
            'buyer_payment_system_set'  => $buyer_payment_system_set,
            'buyer_confirmation_date'   => null,
            'buyer_document_image_path' => '',
            'buyer_payout_limit'        => 0,
            'buyer_transaction_id'      => 0,

            'type'                      => $type,
            'status'                    => $status,
            'sell_payment_data'         => $user_pay_sys_serialize_sell,
            'buy_payment_data'          => $user_pay_sys_serialize_buy,
            'wt_set'                    =>  $wt_set,
            'bonus'                     => $bonus,
//            'sms'                       => $sell_data['sms'],
        );

        try
        {
            $this->db->insert( $this->table_orders, $data );
            $inserted_id = $this->db->insert_id();
        } catch( Exception $exc )
        {
//            vre($this->db->last_query());
//            vred($exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }

        return $inserted_id;
    }

//    Currency_exchange_model::ORDER_TYPE_SELL_WT, Currency_exchange_model::ORDER_STATUS_SET_OUT, $search_data
    //TODO не нужно - удалить.
    public function search_sell($status, $search_data)
    {

        return $this->search(Currency_exchange_model::ORDER_TYPE_BUY_WT, $status, $search_data);
    }

    //TODO не нужно - удалить.
    public function search_buy($status, $search_data)
    {
        return $this->search(Currency_exchange_model::ORDER_TYPE_SELL_WT, $status, $search_data);
    }

    public function search_old3($status, &$search_data, $limit = array(), $sort = array(), $filters = array())
    {

        if( 0&& in_array( $this->accaunt->get_user_id(), array(500733, 500757) ) )
        {
            //return $this->search_v3( $status, $search_data, $limit, $sort, $filters );
        }

        #get block

        $search_data['total_count'] = 0;

        //<editor-fold defaultstate="collapsed" desc="">
        if($search_data['all_orders'] != 1 && !empty($search_data['sel_payment_systems']))
        {
            $this->db->where_in('payment_system', $search_data['sel_payment_systems']);
        }

        if($search_data['all_orders'] != 1 && !empty($search_data['select_curency_sell']))
        {
            $query = '';
            $or = '';
            foreach($search_data['select_curency_sell'] as $k => $v)
            {
                $query .= $or.'(payment_system = '.$k.' AND choice_currecy = \''.$v.'\')';
                $or = ' OR ';
            }

            $this->db->where($query);
        }


        $select_clmns = ['id','order_id','order_status','choice_currecy','payment_system','summa','type','order_bonus'];

        $res_sell_src = $this->db->select($select_clmns)
                ->where('type', self::ORDER_TYPE_SELL_WT)
                ->where('order_status', $status)
                ->where('summa >=', $search_data['sell_amount_down'])
                ->where('summa <=', $search_data['sell_amount_up'])
//                ->where('summa >=', $search_data['amount_down'])
//                ->where('summa <=', $search_data['amount_up'])
//                ->where_in('payment_system', $search_data['sel_payment_systems'])
//                ->where_in('payment_system', $search_data['payment_systems'])
                ->order_by('order_id','DESC')
                ->get($this->table_order_details)
                ->result();

        #give block
        if($search_data['all_orders'] != 1 && !empty($search_data['payment_systems']))
        {
            $this->db->where_in('payment_system', $search_data['payment_systems']);
        }


        if($search_data['all_orders'] != 1 && !empty($search_data['select_curency_buy']))
        {
            $query = '';
            $or = '';
            foreach($search_data['select_curency_buy'] as $k => $v)
            {
                $query .= $or.'(payment_system = '.$k.' AND choice_currecy = \''.$v.'\')';
                $or = ' OR ';
            }

            $this->db->where($query);
        }

        $res_buy_src = $this->db->select($select_clmns)
                 ->where('order_status', $status)
                ->where('type', self::ORDER_TYPE_BUY_WT)
                ->where('summa >=', $search_data['amount_down'])
                ->where('summa <=', $search_data['amount_up'])
//                ->where('summa >=', $search_data['sell_amount_down'])
//                ->where('summa <=', $search_data['sell_amount_up'])
//
//                ->where_in('payment_system', $search_data['sel_payment_systems'])
                ->order_by('order_id','DESC')
                ->get($this->table_order_details)
                ->result();


        if(empty($res_sell_src) && empty($res_buy_src)) return false;
        //</editor-fold>


        #prepareing data for intersect
        //<editor-fold defaultstate="collapsed" desc="">
        $res = array();
        $res_details = array();
        $search_sell_ids = array();
        $search_buy_ids= array();
        $orders_total_ids = [];

        $res_sell = [];
        foreach ($res_sell_src as $val)
        {
            $search_sell_ids[$val->order_id] = $val->order_id;
            $res_sell[$val->order_id] = $val;

            //$orders_total_ids[$val->order_id] = $val->order_id;
            $res_details[ $val->id ] = $val;
        }

        $res_buy = [];
        foreach ($res_buy_src as $val)
        {
            $search_buy_ids[$val->order_id] = $val->order_id;
            $res_buy[$val->order_id] = $val;

            //$orders_total_ids[$val->order_id] = $val->order_id;
            $res_details[ $val->id ] = $val;
        }

        $search_intersect_ids = array_intersect($search_sell_ids, $search_buy_ids);
        $orders_total_ids = array_keys($search_intersect_ids);

        if(empty($search_intersect_ids))
        {
            return false;
        }
        //</editor-fold>

//        $orders_ids_src = $this->db->select('id')
//                 ->where('status', $status)
//
//               // ->group_by('id')
//                ->order_by('id','DESC')
//                 ->get($this->table_orders)
//                ->result();

//        $orders_ids = [];
//        foreach( $orders_ids_src as $o )
//            $orders_ids[ $o->id ] = $o->id;

//        $orders_total_ids = array_intersect($orders_ids, $search_intersect_ids);
        $orders_count = count( $orders_total_ids );

        if(empty($orders_total_ids))
        {
            return false;
        }

        $current_show_records = 99999999;
        // пагинация
        if( count( $limit ) == 0 ){
//            $this->db->limit( 10 );

            $orders_total_ids = array_slice($orders_total_ids, 0, 10);
        }else
        if( count( $limit ) == 1 ){
//            $this->db->limit( $limit[ 0 ] );
            $current_show_records = $limit[ 0 ];

            $orders_total_ids = array_slice($orders_total_ids, 0, $limit[ 0 ]);


        } elseif( count( $limit ) == 2 ){
//            $this->db->limit( $limit[ 0 ], $limit[ 1 ] );
            $current_show_records = $limit[ 0 ]+$limit[ 1 ];

            $orders_total_ids = array_slice($orders_total_ids, $limit[ 1 ], $limit[ 0 ]);
        }

        $search_data['total_count'] = $orders_count;

//        var_dump($orders_total_ids);
        //if sort
        //<editor-fold defaultstate="collapsed" desc="sorting">
        if ( count($sort) > 0 )
        {

            foreach( $sort as $s){
                $dir = ($s['dir']=='asc')?'asc':'desc';
                switch ( $s['column'] ){
                    case 0:
                        $this->db->order_by('id',$dir);
                        break;
                    case 2:
                        $this->db->join($this->table_order_details.' d', 'd.order_id=o.id and o.type=d.type', 'inner');
                        $this->db->order_by('d.summa', $dir);
                        break;
                    case 3:
                        $this->db->order_by('seller_set_up_date',$dir);
                        break;
                    case 4:

                        $this->db->order_by('discount', $dir);
                        break;
                    case 5:
                        $this->db->join($this->table_order_details.' d', 'd.order_id=o.id and o.type!=d.type', 'inner');
                        $this->db->order_by('d.summa', $dir);
                        break;
                }
            }

            $orders = $this->db
//                 ->where("type", $type)
                 ->select('o.*')
                 ->from($this->table_orders.' o')
                 ->where_in("o.id", $orders_total_ids )
                 ->where('o.status', $status)
                 ->get()
                 ->result();

             if( count($orders) <= 0 ) return false;

            $res_orders = array();
            $ids = array();

            foreach($orders as $key => $val)
            {
                $ids[] = $val->id;
                $res_orders[$val->id] = $val;
            }

            $res_details = $this->db
                    ->where_in('order_id', $ids)
                    ->get($this->table_order_details)
                    ->result();

            foreach ($res_details as $val)
            {
                if(isset($res_orders[$val->order_id]))
                {
                   $res_orders[$val->order_id]->payment_systems[$val->id] = $val;
                }
            }

            $res_orders_sort = $res_orders;//$this->_sort_search_res($res_orders);

            foreach($res_orders_sort as $key => $val)
            {
                if(!isset($val->payment_systems))
                {
                    unset($res_orders_sort[$key]);
                }

                $res_orders_sort[$key] = $this->set_fee_to_order($res_orders_sort[$key]);
                $res_orders_sort[$key] = $this->set_machin_name_to_payment_systems($res_orders_sort[$key]);
                $res_orders_sort[$key] = $this->set_dop_data_to_unserializ_payment_system($res_orders_sort[$key]);

            }

            return $res_orders_sort;
        }
        //</editor-fold>


        if( empty( $orders_total_ids ) ) return FALSE;
        //if NO sort
        $orders = $this->db
                 ->select('o.*')
                 ->from($this->table_orders.' o')
                 ->where_in("o.id", $orders_total_ids )
//                 ->where("o.status", $status )
                 //->order_by('o.id','DESC')
                 ->get()
                 ->result();

        if( count($orders) <= 0 ) return false;

        $res_orders = array();
        $ids = array();

        foreach($orders as $key => $val)
        {
            $ids[] = $val->id;
            $res_orders[$val->id] = $val;
        }

//        $res_details = $this->db
//                ->where_in('order_id', $ids)
//                ->order_by('id','DESC')
//                ->get($this->table_order_details)
//                ->result();

        foreach ($res_details as $val)
        {
            if(isset($res_orders[$val->order_id]))
            {
               $res_orders[$val->order_id]->payment_systems[$val->id] = $val;
            }
        }

        $res_orders_sort = $res_orders;//$this->_sort_search_res($res_orders);

        foreach($res_orders_sort as $key => $val)
        {
            if(!isset($val->payment_systems))
            {
                unset($res_orders_sort[$key]);
            }

            $res_orders_sort[$key] = $this->set_fee_to_order($res_orders_sort[$key]);
            $res_orders_sort[$key] = $this->set_machin_name_to_payment_systems($res_orders_sort[$key]);
            $res_orders_sort[$key] = $this->set_dop_data_to_unserializ_payment_system($res_orders_sort[$key]);

        }

        return $res_orders_sort;
    }

    public function search($status, &$search_data, $limit = array(), $sort = array(), $filters = array())
    {        
        #get block    
        $order_wt_set = FALSE;
        if($search_data['all_orders'] != 1 )
        {
//            vred( $search_data );
            #currency
            $single_ps = null;
            $ps = null;

            $wt_side = 0;

            $cl = count( $search_data["sel_payment_systems"] );
            $cr = count( $search_data["payment_systems"] );

            if( $cl == 1 )
            {
                reset($search_data["sel_payment_systems"]);
                $first_key = key($search_data["sel_payment_systems"]);

                if( $this->root_currency( $search_data["sel_payment_systems"][$first_key] ) ){
                    $wt_side = -1;

                    $single_ps = $search_data["sel_payment_systems"][$first_key];
                    $order_wt_set = self::WT_SET_BUY;

                    $ps = $search_data["payment_systems"];
                }
            }

            if( $cr == 1 && $wt_side === 0 )
            {
                reset($search_data["payment_systems"]);
                $first_key = key($search_data["payment_systems"]);

                if( $this->root_currency( $search_data["payment_systems"][$first_key] ) )
                {
                    $wt_side = 1;

                    $single_ps = $search_data["payment_systems"][$first_key];
                    $order_wt_set = self::WT_SET_SELL;

                    $ps = $search_data["sel_payment_systems"];
                }
            }

            if( $wt_side === 0 )
            {
                //if( $this->accaunt->get_user_id() == 500733 ) vred($search_data);
//                    if( $cl == 1 )
//                    {
//                        reset($search_data["sel_payment_systems"]);
//                        $first_key = key($search_data["sel_payment_systems"]);
//                        $single_ps = $search_data["sel_payment_systems"][$first_key];
//                    }else
                if( $cl == 1 ){
                    reset($search_data["payment_systems"]);
                    $first_key = key($search_data["payment_systems"]);
                    $single_ps = $search_data["payment_systems"][$first_key];
                }

                if( $cr > 1 ){
                    $ps = $search_data["payment_systems"];
                    $order_wt_set = 0;
                }
            }

//            if( !empty( $single_ps ) ) $this->db->where('single_payment_system', $single_ps );
//                
//            if( !empty( $ps ) )
//            {
//                $ps_ar = [];
//
//                if( !empty($search_data['select_curency_sell']))
//                {
//                    foreach( $ps as $one )
//                    {
//                        if( isset( $search_data['select_curency_sell'][ $one ] ) )                            
//                        $ps_ar[] = "(payment_system = $one AND choice_currecy = {$search_data['select_curency_sell'][ $one ]})";
//                        else
//                            $ps_ar[] = "payment_system = $one";
//                    }
//                }
//                else
//                {                    
//                    foreach( $ps as $one )
//                        $ps_ar[] = "payment_system = $one";
//                }
//
//                $this->db->where( '('. implode( ' OR ', $ps_ar) .')' );
//            } 
            
//            if(empty($single_ps) && empty( $ps ) && $cl != 0 && $cr == 0 )
            $ps_ar = [];
            $order_wt_set = FALSE;
            if( $cl != 0 && $cr == 0 )
            {
                foreach( $search_data["sel_payment_systems"] as $one )
                {
                    if( isset( $search_data['select_curency_sell'][ $one ] ) )
                    {
                        $ps_ar[] = "(payment_system = $one AND choice_currecy = {$search_data['select_curency_sell'][ $one ]})";
                    }
                    else
                    {
                        $ps_ar[] = "payment_system = $one";
                    }
                }
                
                $this->db->where( '('. implode( ' OR ', $ps_ar) .')' );
                $this->db->where('type', 0);
            }
            elseif( $cr != 0 && $cl == 0 )
            {
                foreach( $search_data["payment_systems"] as $one )
                {
                    if( isset( $search_data['select_curency_buy'][ $one ] ) )
                    {
                        $ps_ar[] = "(payment_system = $one AND choice_currecy = {$search_data['select_curency_buy'][ $one ]})";
                    }
                    else
                    {
                        $ps_ar[] = "payment_system = $one";
                    }
                }
                
                $this->db->where( '('. implode( ' OR ', $ps_ar) .')' );
                $this->db->where('type', 1);
                
            }
            elseif( $cr != 0 && $cl != 0 )
            {
                foreach( $search_data["payment_systems"] as $one )
                {
                    if( isset( $search_data['select_curency_buy'][ $one ] ) )
                    {
                        $ps_ar[] = "(payment_system = $one AND choice_currecy = {$search_data['select_curency_buy'][ $one ]} AND type = 1)";
                    }
                    else
                    {
                        $ps_ar[] = "(payment_system = $one AND type = 1)";
                    }
                }
                
//                $this->db->join($this->ta, 'comments.id = blogs.id');
                $this->db->where( '('. implode( ' OR ', $ps_ar) .')' );
                
                foreach( $search_data["sel_payment_systems"] as $one )
                {
                    if( isset( $search_data['select_curency_sell'][ $one ] ) )
                    {
                        $ps_ar_2[] = "(payment_system = $one AND choice_currecy = {$search_data['select_curency_sell'][ $one ]} AND type = 0)";
                    }
                    else
                    {
                        $ps_ar_2[] = "(payment_system = $one AND type = 0)";
                    }
                }
                
//                $this->db->where_in('order_id', 'SELECT order_id FROM '.$this->table_order_details.' WHERE ('. implode( ' OR ', $ps_ar_2) .')' );
                $this->db->where('order_id IN ', '(SELECT order_id FROM '.$this->table_order_details.' WHERE ('. implode( ' OR ', $ps_ar_2) .'))', false );
//                vre('SELECT order_id FROM '.$this->table_order_details.' WHERE ('. implode( ' OR ', $ps_ar_2) .')' );
//                vre($this->db->get_compiled_select());
            }
        }

        #sums
        if( 0 && $search_data['all_orders'] != 1 && $search_data['sell_amount_down'] !== NULL || $search_data['sell_amount_up'] !== NULL )
        {
                $res_orders = $this->db
                    ->where('summa >=', $search_data['sell_amount_down'])
                    ->where('summa <=', $search_data['sell_amount_up']);
        }

        $this->db->where('order_status', self::ORDER_STATUS_SET_OUT)
                ->where('orig_order_data !=', '' )
                ->where('order_discount IS NOT NULL', NULL )
                ->group_by('order_id')
                ;

        #counter
        //$counter = clone $this->db;
        $search_data['total_count'] = 99999;//$counter->get($this->table_order_details)->count_all_results();

        #limits
        // пагинация

            if( count( $limit ) == 0 ){
                $this->db->limit( 10 );
            }else
            if( count( $limit ) == 1 ){
                $this->db->limit( $limit[ 0 ]+1 );
            } elseif( count( $limit ) == 2 ){
                $this->db->limit( $limit[ 0 ]+1, $limit[ 1 ] );
            }


        #sorting
        if ( count($sort) > 0 )
        {
            foreach( $sort as $s)
            {
                $dir = ($s['dir']=='asc')?'asc':'desc';
                switch ( $s['column'] ){
                    case 0:
                        $this->db->order_by('id',$dir);
                        break;
                    case 1://you get
                        if( $order_wt_set == self::WT_SET_BUY ) 
                            $this->db->order_by('single_summa',$dir)
                                     ->order_by('priority', 'DESC')
                                     ->order_by('order_id', 'DESC');
                        break;
                    case 2://date
                        $this->db->order_by('id', $dir);
                        break;
                    case 3://you get
                        if( $order_wt_set == self::WT_SET_SELL ) 
                            $this->db->order_by('single_summa',$dir)
                                    ->order_by('priority', 'DESC')
                                    ->order_by('RAND()', NULL);
                                    //->order_by('order_id', 'DESC');
                        break;
                    case 4:
                        $this->db->order_by('order_discount', $dir)
                            ->order_by('priority', 'DESC')
                            ->order_by('RAND()', NULL);
                            //->order_by('order_id', 'DESC');
                        break;

                }
            }
        }

        if( $order_wt_set !== FALSE ) $this->db->where('order_wt_set', $order_wt_set);

        $res_details = $this->db                
                ->order_by('order_id','DESC')
                ->get($this->table_order_details)
                ->result();        
        
//        vre($this->db->last_query());
//        pred($res_details);
        
        $c = count( $res_details );

        if( count( $limit ) == 0 ){
            if( $c < $limit ) $search_data['total_count'] = $c;
        }else
        if( count( $limit ) == 1 ){
            if( $c < $limit[0] ) $search_data['total_count'] = $c;
        } elseif( count( $limit ) == 2 ){
            if( $c < $limit[0] ) $search_data['total_count'] = $c;

        }


        $search_res = [];
        foreach( $res_details as $details )
        {
            $order = unserialize( $details->orig_order_data );
            $order->discount = $details->order_discount;

            $search_res[] = $order;
        }
//echo $this->db->last_query();
        /*
         SELECT *
FROM `currency_exchange_order_details`
WHERE `summa` >=0
AND `summa` <= 9999999999
AND `order_status` = 10
AND `orig_order_data` != ''
AND `order_discount` IS NOT NULL
GROUP BY `order_id`
ORDER BY `order_discount` ASC, `priority` DESC, `order_id` DESC
 LIMIT 101
         */
        return $search_res;
    }
    
    
    
    function get_all_order_details($order_id)
    {
        if( empty( $order_id ) ) return false;

        $query = $this->db
                ->where('order_id', $order_id)
                ->get($this->table_order_details)
                ->result();

        if( empty( $query ) ) return false;
        return $query;
    }

    public function get_order_details_by_initiator($order_id) {
        if( empty( $order_id ) ) return false;

        $query = $this->db
                ->where('order_id', $order_id)
                ->where('type', 1)
                ->get($this->table_order_details)
                ->result();

        if( empty( $query ) ) return false;

        return $query;
    }

    public function get_order_details_by_partner($order_id) {
        if( empty( $order_id ) ) return false;

        $query = $this->db
                ->where('order_id', $order_id)
                ->where('type', 0)
                ->get($this->table_order_details)
                ->result();

        if( empty( $query ) ) return false;

        return $query;
    }

    private function _get_order_details($ids, $payment_systems)
    {
        $query = $this->db
            ->where_in('order_id', $ids)
            ->where('payment_system', array_shift($payment_systems));

        foreach($payment_systems as $v)
        {
            $query->or_where('payment_system', $v);
        }

        $res_details = $query->get($this->table_order_details)
                    ->result();

//        vre($this->db->last_query());

        return $res_details;
    }


    private function _sort_search_res($search_res)
    {
        usort($search_res, function($a, $b) {return(Currency_exchange_model::_cmp($a, $b));});

        return $search_res;
    }

    static public function _cmp($a, $b)
    {
        $c_a = count($a->payment_systems);
        $c_b = count($b->payment_systems);

        if ($c_a == $c_b) return 0;
        return ($c_a > $c_b)? -1: 1;
    }


     public function get_all_payment_systems_groups($parent_id = 0, $oder = 'ASC')
    {
        $res = $this->db
                ->where('parent_id', $parent_id)
                ->order_by("order", $oder)
                ->get($this->table_payment_systems_groups)
                ->result();

        $all_payment_groups = $this->db
            ->order_by("order", $oder)
            ->get($this->table_payment_systems_groups)
            ->result();

        $res_arr = array();

        foreach ($res as $val)
        {
            $res_childe = array();
            foreach ($all_payment_groups as $item) {
                if($item->parent_id == $val->id) {
                   $res_childe[] = $item;
                }

            }

            if(!empty($res_childe))
                $val->childes = $res_childe;
            $res_arr[$val->id] = $val;
        }

        // foreach ($res as $val)
        // {
        //     $res_childe = $this->get_all_payment_systems_groups($val->id, $oder);

        //     if(!empty($res_childe))
        //     {
        //         $val->childes = $res_childe;
        //     }

        //     $res_arr[$val->id] = $val;
        // }

        return $res_arr;
    }



    public function get_user_orders($where_status, $user_id)
    {
        $res = $this->db
//                 ->where("type", $type)
//                 ->where("status", $status)
                 ->where($where_status)
                 ->where("seller_user_id", $user_id)
                 ->order_by('id', 'DESC')
                 ->get($this->table_orders)
                 ->result();

        //if( $user_id == 500733 ) vre([$this->db->last_query(),$res]);

        if(count($res))
        {
            $res_orders = array();
            $ids = array();

            foreach($res as $key => $val)
            {
                $ids[] = $val->id;
                $res_orders[$val->id] = $val;
            }

            //if( $user_id == 500733 ) vred($res_orders);

            $query = $this->db->where_in('order_id', $ids);
            $res_details = $query->get($this->table_order_details)
                                ->result();

            //if( $user_id == 500733 ) vred([$this->db->last_query(),$res]);

            foreach ($res_details as $val)
            {
                if(!isset($res_orders[$val->order_id])) continue;

                $res_orders[$val->order_id]->payment_systems[$val->id] = $val;
            }

            foreach($res_orders as $key => $val)
            {
                if(!isset($val->payment_systems))
                {
                    unset($res_orders[$key]);
                    continue;
                }
                $res_orders[$key] = $this->_get_second_order_data_one($res_orders[$key]);
                $res_orders[$key] = $this->set_fee_to_order($res_orders[$key]);
                $res_orders[$key] = $this->set_machin_name_to_payment_systems($res_orders[$key]);
                $res_orders[$key] = $this->set_dop_data_to_unserializ_payment_system($res_orders[$key]);
                $res_orders[$key] = $this->set_reject_note_in_order($res_orders[$key]);

            }

            return $res_orders;
        }
        else
        {
            return false;
        }
    }



    public function get_user_orders_arhive($status, $user_id, $type = null)
    {

        if( !is_numeric( $status ) && is_array( $status ) )
        {
            $this->db->where_in("status", $status);
        }else
        if( !empty( $status ) ){
            $this->db->where("status", $status);
        }

        if($type != null) {
            $this->db->where_in($type);
        }

        $res = $this->db
                 ->where("seller_user_id", $user_id)
                 ->order_by('id', 'DESC')
                 ->get($this->table_orders_arhive)
                 ->result();

        $res = $this->_get_second_order_data($res);
//        pred($res);
        return $this->_get_user_orders_arhive_details($res);
//        if(count($res))
//        {
//            $res_orders = array();
//            $ids = array();
//
//            foreach($res as $key => $val)
//            {
//                $ids[] = $val->original_order_id;
//                $res_orders[$val->original_order_id] = $val;
//            }
//
//            $query = $this->db->where_in('order_id', $ids);
//            $res_details = $query->get($this->table_order_details)->result();
//
//            foreach ($res_details as $val)
//            {
//                if(isset($res_orders[$val->order_id]))
//                {
//                   $res_orders[$val->order_id]->payment_systems[$val->id] = $val;
//                }
//            }
//
//            foreach($res_orders as $key => $val)
//            {
//                if(!isset($val->payment_systems))
//                {
//                    unset($res_orders[$key]);
//                }
//            }
//
//            return $res_orders;
//        }
//        else
//        {
//            return false;
//        }
    }


    public function get_user_orders_arhive_confirmed($type, $status, $user_id)
    {
        if(  self::SWITCH_OFF === false  )
        {
            $this->db
                ->where("status >=", $status)
//                 ->where("status <=", self::ORDER_STATUS_CANCELED);
                ->where("status <=", self::ORDER_STATUS_REMOVED);
        }else
        {
            $this->db
                ->where("status >=", $status)
                ->where("status <", self::ORDER_STATUS_CANCELED)   ;
        }

        if(!empty($type) && !in_array('NULL', $type)) {
            if($type['status'] == 'ORDER_STATUS_OPERATOR_CANCELED_AND_BRAKEN') {
                $this->db->where('status >=', Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED);
                $this->db->where('status <=', Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR);
                $this->db->where('status !=', Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK);
                $this->db->where('status !=', Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR);
            }
            else {
                $this->db->where($type);
            }

        }

        $res = $this->db
                ->where("seller_user_id", $user_id)
                ->order_by('original_order_id', 'DESC')
                ->get($this->table_orders_arhive)
                ->result();
        return $this->_get_user_orders_arhive_details($res);
    }



    public function get_user_orders_arhive_problem($type, $user_id)
    {
        $res = $this->db
//                 ->where("type", $type)
//                 ->where("confirm_type", $type)
                 ->where("status >=", self::ORDER_STATUS_HAVE_PROBLEM)
                 ->where("status <", self::ORDER_STATUS_SUCCESS)
                 ->where("seller_user_id", $user_id)
                 ->get($this->table_orders_arhive)
                 ->result()
                ;

        return $this->_get_user_orders_arhive_details($res);
    }



    public function _get_user_orders_arhive_details_one($res)
    {
        $orders = $this->_get_user_orders_arhive_details(array($res));

        if(empty($orders))
        {
            return FALSE;
        }

        return array_shift($orders);
    }



    public function _get_user_orders_arhive_details($res)
    {
        if(empty($res))
        {
            return false;
        }

        $res_orders = array();
        $ids = array();

        foreach($res as $key => $val)
        {
            $ids[] = $val->original_order_id;

//            if($val->seller_user_id == $val->buyer_user_id)
//            {
//                continue;
//            }

//            if(isset($res_orders[$val->original_order_id]) && $val->seller_user_id == $val->buyer_user_id)
//            {
//                continue;
//            }

            $res_orders[$val->original_order_id] = $val;


            if(!empty($val->order_details_arhiv))
            {
                $userializ_order_details_arhiv = unserialize($val->order_details_arhiv);

                foreach($userializ_order_details_arhiv as $v)
                {
                    $res_orders[$val->original_order_id]->payment_systems[$v->id] = $v;
                }
            }
        }
//pred($ids);

        $query = $this->db->where_in('order_id', $ids); 

        $res_details = $query->get($this->table_order_details)->result();

        foreach ($res_details as $val)
        {
            if(isset($res_orders[$val->order_id]))
            {
               $res_orders[$val->order_id]->payment_systems[$val->id] = $val;
            }
        }

        foreach($res_orders as $key => $val)
        {
            if(!isset($val->payment_systems))
            {
                if($val->status == self::ORDER_STATUS_SUCCESS || $val->status == self::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR)
                {
                    $res_orders[$key] = $this->_repare_spli_userializ_string($val);
                }
                else
                {
                    unset($res_orders[$key]);
                    continue;
                }
            }

            $res_orders[$key] = $this->_get_second_order_data_one($res_orders[$key]);
            $res_orders[$key] = $this->set_fee_to_order($res_orders[$key]);
            $res_orders[$key] = $this->set_machin_name_to_payment_systems($res_orders[$key]);
            $res_orders[$key] = $this->set_dop_data_to_unserializ_payment_system($res_orders[$key]);
            $res_orders[$key] = $this->set_reject_note_in_order($res_orders[$key]);
        }


//        pre($res_orders['23352']);
//        pred($res_orders['49405']);
        return $res_orders;
    }


    private function _repare_spli_userializ_string($order)
    {
        $ps_all = $this->get_all_payment_systems();

        $ps = array_set_value_in_key($ps_all);

        $payment_systems = [];

        $pay_sys = new stdClass();

        $pay_sys->id = 1;
        $pay_sys->order_id = $order->original_order_id;
        $pay_sys->payment_system = $order->payed_system;
//        $pay_sys->summa = $ps[$order->payed_system]->machine_name == 'wt'?$order->seller_amount:'';
        $pay_sys->summa = $this->is_root_currency($ps[$order->payed_system]->machine_name)?$order->seller_amount:'';
        $pay_sys->type = 0;
        $pay_sys->orig_order_data = '';
        $pay_sys->order_status = 10;
        $pay_sys->choice_currecy = '';

        $payment_systems[1] = $pay_sys;

        unset($pay_sys);

        $pay_sys = new stdClass();

        $pay_sys->id = 2;
        $pay_sys->order_id = $order->original_order_id;
        $pay_sys->payment_system = $order->sell_system;
//        $pay_sys->summa = $ps[$order->sell_system]->machine_name == 'wt'?$order->seller_amount:'';
        $pay_sys->summa = $this->is_root_currency($ps[$order->sell_system]->machine_name)?$order->seller_amount:'';
        $pay_sys->type = 1;
        $pay_sys->orig_order_data = '';
        $pay_sys->order_status = 10;
        $pay_sys->choice_currecy = '';

        $payment_systems[2] = $pay_sys;
        unset($pay_sys);

        $order->payment_systems = $payment_systems;

        return $order;
    }


    private function _get_second_order_data_one($res)
    {
        $orders =  $this->_get_second_order_data(array($res));

        if(empty($orders))
        {
            return false;
        }

        return array_shift($orders);
    }



    private function _get_second_order_data($res)
    {
        $order_ids = array();
        $edit_res = array();

        foreach ($res as $val)
        {
            $edit_res[$val->id] = $val;
            if(isset($val->buyer_order_id))
            {
                $order_ids[] = $val->buyer_order_id;
            }
        }

        if(empty($order_ids))
        {
            return $res;
        }

        $second_res = $this->db
                ->where_in('id',$order_ids)
                ->get($this->table_orders_arhive)
                ->result();

        if(empty($second_res))
        {
            return $res;
        }

        foreach ($second_res as $val)
        {
            if(isset($edit_res[$val->buyer_order_id]))
            {
                $edit_res[$val->buyer_order_id]->second_order =  $val;
            }
        }

        return $res;
    }




    public function save_user_paymant_data($user_id, $payment_system_key, $payment_data)
    {
        $payment_systems = $this->get_all_payment_systems();

        if(!isset($payment_systems[$payment_system_key]))
        {
            return FALSE;
        }

        if(method_exists($this, $payment_systems[$payment_system_key]->method_set_ps_data))
        {
//            return false;
            list($check_set_data, $error) = $this->{$payment_systems[$payment_system_key]->method_set_ps_data}($payment_data);

            if($check_set_data === false)
            {
                return [$check_set_data, $error];
            }
        }

        $data = array(
            'user_id' => $user_id,
            'payment_system_id' => $payment_systems[$payment_system_key]->id,
        );

        $res = $this->db->where($data)
                ->get($this->table_user_paymant_data)
                ->row();

        $data['payment_data']  = $payment_data;

        try
        {
            if(empty($res))
            {
                $this->db->insert( $this->table_user_paymant_data, $data );
            }
            else
            {
                $this->db
                    ->where('id', $res->id)
                    ->update($this->table_user_paymant_data, $data);
            }

            return true;

        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }



    public function save_user_paymant_data_without_check($user_id, $payment_system_key, $payment_data)
    {
//        $payment_systems = $this->get_all_payment_systems();
//
//        if(!isset($payment_systems[$payment_system_key]))
//        {
//            return FALSE;
//        }

        $ps = self::get_ps($payment_system_key);

        if(empty($ps))
        {
            return false;
        }

        $data = array(
            'user_id' => $user_id,
            'payment_system_id' => $ps->id,
        );

        $res = $this->db
                ->where($data)
                ->get($this->table_user_paymant_data)
                ->row();

        $data['payment_data']  = $payment_data;

        try
        {
            if(empty($res))
            {
                $this->db->insert( $this->table_user_paymant_data, $data );
            }
            else
            {
                $this->db
                    ->where('id', $res->id)
                    ->limit(1)
                    ->update($this->table_user_paymant_data, $data);
            }

            return true;

        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }



    public function get_user_paymant_data_for_orders($user_id, $pay_sys)
    {
        $payment_systems = $this->get_all_payment_systems();

        $ids = array();

        foreach($pay_sys as $k => $v)
        {
            if(!isset($payment_systems[$k]))
            {
                $payment_systems[$k] = self::get_ps($k);
            }

            $ids[] = $payment_systems[$k]->id;
            $pay_sys[$k] = $payment_systems[$k]->id;
        }

        $res = $this->db
                ->where('user_id', $user_id)
                ->where_in('payment_system_id', $ids)
                ->get($this->table_user_paymant_data)
                ->result();

        $res_all = $this->_get_user_paymant_data_for_orders_by_buy_type($user_id, $pay_sys);
        $temp_res = array();

        $res = array_set_value_in_key($res, 'payment_system_id');


        foreach($res_all as $key => $val)
        {
            if(isset($res[$val->payment_system_id]))
            {
                $res_all[$key] = $res[$val->payment_system_id];
            }
        }

        return $res_all;
    }



    public function get_user_all_paymant_data($user_id)
    {
        if( self::$_other_ps_arr !== false)
        {
            return  self::$_other_ps_arr;
        }

        $payment_systems = $this->get_all_payment_systems();

        if(self::$_all_user_payment_data !== false)
        {
            $res = self::$_all_user_payment_data;
        }
        else
        {
            $res = $this->db
                ->where('user_id', $user_id)
                ->get($this->table_user_paymant_data)
                ->result();

            self::$_all_user_payment_data = $res;
        }

        foreach($payment_systems as &$val)
        {
            $this->_set_pay_data_to_pay_sys($res, $val);
        }

        unset($val);

        self::$_other_ps_arr = $payment_systems;

        return $payment_systems;
    }


    private function _set_pay_data_to_pay_sys($pay_data, &$ps)
    {
        foreach($pay_data as $k => $v)
        {
          if($ps->id ==  $v->payment_system_id)
          {
              $ps->payment_sys_user_data = $v->payment_data;
          }
        }
    }


    public function get_user_paymant_data_by_payment_system_id($payment_system_id, $user_id)
    {
        $res = $this->db
                ->where('user_id', $user_id)
                ->where('payment_system_id', $payment_system_id)
                ->get($this->table_user_paymant_data)
                ->row();

        return $res;
    }


    /**
     * Удаляет пользовательскую заявку на платежную систему
     * @param type $id
     */
    public function delete_new_user_payment_system($id){

        $this->db->where('id', $id)->delete($this->table_new_paymant_systems);
    }


    /*
     * Устанавливает состояние пользовательской платежной системы
     */
    public function set_new_user_payment_system_state($id, $added_state)
    {
         $this->db->where('id', $id)
                 ->limit(1)
                 ->update($this->table_new_paymant_systems, ['added'=>$added_state]);

    }

    public function save_new_user_payment_system($data)
    {
        if(empty($data))
        {
            return false;
        }
//        $data = array(
//            'name' => trim(strtolower($name)),
//        );
        $res = $this->db->where($data)
                ->get($this->table_new_paymant_systems)
                ->row();

        if($res->added != 0 )
        {
            return 'set';
        }

        try
        {
            if(empty($res))
            {
                $data['count'] = 1;
                $this->db->insert( $this->table_new_paymant_systems, $data );
                 return true;
            }
            else
            {

                $data['count'] = ++$res->count;
//                pred($data);
                $this->db
                    ->where('id', $res->id)
                    ->limit(1)
                    ->update($this->table_new_paymant_systems, $data);
                return 'add';
            }



        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }

    /**
     * Вохвращает платежные системы для добавления
     * @return type
     */
    public function get_new_user_payment_systems(&$cnt, $page = 0, $rows = 0, $sort = array())
    {

       $cnt = 0;

        try{
      //      $this->db->cache_on();

            $cnt = $this->db->select("COUNT(*) as count")
                                   ->from($this->table_new_paymant_systems.' m')
                                   ->get()->row()->count;

            $ps = $this->db->select("m.*")
                                   ->from($this->table_new_paymant_systems.' m');


            if (  $rows > 0)
                         $ps = $ps->limit($rows, $page*$rows );

            if (!empty($sort)){
                foreach ( $sort as  $sort_field=>$sort_order){
                    switch ( $sort_field ){
                        case 'status_name':
                             $ps = $ps->order_by('status', $sort_order );
                            break;
                        default:
                             $ps = $ps->order_by($sort_field, $sort_order );
                            break;
                    }
                }
            } else {
                $ps = $ps->order_by('id', 'desc' );
            }

            $ps = $ps->get( )
                                   ->result_array();
        //    $this->db->cache_off();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        if( empty( $ps ) ) return FALSE;


        // переконвертим даты и добавим названмя статусам
        foreach( $ps as &$p )
        {
            // $m['status_name'] = $statuses[$m['status']];
        }


        return $ps;

    }
    /**
     * Вохвращает платежные системы для добавления
     * @return type
     */

    public function get_new_user_payment_systems_by_group($group_id, $country_name = 'ru')
    {
        try
        {
            $ps = $this->db
                    ->where('group_id', $group_id)
                    ->get($this->table_new_paymant_systems)
                    ->result();
        }
        catch (Exception $e)
        {
            self::$last_error = $e;
        }

        if (empty($ps))
        {
            return FALSE;
        }

        // добавляем названия стран
        if($country_name !== false && in_array($country_name, ['ru', 'en']))
        {
            $country_name = 'country_name_'.$country_name;

            $countrys = $this->get_all_country_name();
//            pre($countrys);
            foreach ($ps as &$p)
            {
                $p->country = $countrys[$p->country_id]->{$country_name};
//                $p['country'] = $countrys[$p['country_id']]->{$country_name};
            }
        }

        return $ps;
    }


    public function get_count_children_new_ps($id)
    {
        if(empty($id))
        {
            return FALSE;
        }

        $curent_res = $this->db
                    ->where('id', $id)
                    ->get($this->table_payment_systems_groups)
                    ->row();
//        vre($this->db->last_query());
        if(empty($curent_res))
        {
            return 0;
        }

//        pred($curent_res);
        $new_ps  = $this->db
                    ->where('group_id', $curent_res->id)
                    ->where('added', self::NEWPS_ADDED_STATE_NEW)
                    ->get($this->table_new_paymant_systems)
                    ->result();

//        vre(count($new_ps));
//        vre($this->db->last_query());
//
        $child_res = $this->db
                    ->where('parent_id', $curent_res->id)
                    ->get($this->table_payment_systems_groups)
                    ->result();

        $count_child_res = 0;

        foreach($child_res as $val)
        {
            $count_child_res = $this->get_count_children_new_ps($val->id);
        }

        return $count_child_res+count($new_ps);
//        pred($ps);
    }



    public function buy_exchange_confirmation($order_id, $user_id, $status = self::ORDER_STATUS_CONFIRMATION)
    {
        $order = $this->db
                ->where('id', $order_id)
              //  ->where('seller_user_id !=', $user_id)
                ->get($this->table_orders)
                ->row();

        $this->load->model('users_model', 'users_model');
        $user_data = $this->users_model->getUserData($order->seller_user_id);
//        pred($user_data);
        if(empty($order) || !isset($order->status) || $order->status != 10)
        {
            return false;
        }

//        $sms_send = false;
//
//        if($order->sms == 1)
//        {
//           $sms_send = true;
//        }

        $order_edit = clone $order;


        $order_edit->status = $status;
        $order_edit->buyer_user_id = $user_id;

        try
        {
            $this->db->trans_start();

            $this->db->where('id', $order_id)
                    ->limit(1)
                    ->update($this->table_orders, $order_edit);

            $this->set_status_to_details( $order_id, $status );

            // зануление необходимо так как он при определённых условиях будет заполнен далее.
            $order_edit->buyer_user_id = 0;

            $res_details = $this->db
                    ->where('order_id', $order_edit->id)
                    ->get($this->table_order_details)
                    ->result();

            $order_edit->order_details_arhiv = serialize($res_details);

            $order_arhive_buyer = clone $order_edit;
            $order_arhive_seller = clone $order_edit;

            $order_arhive_buyer->original_order_id = $order_arhive_buyer->id;
            $order_arhive_buyer->seller_user_id = $user_id;


//            if($order_edit->type == self::ORDER_TYPE_SELL_WT)
//            {
//                $order_arhive_buyer->confirm_type = self::ORDER_TYPE_BUY_WT;
//                $order_arhive_buyer->buyer_confirmation_date = date('Y-m-d H:i:s');
//            }
//            else
//            {
//                $order_arhive_buyer->confirm_type = self::ORDER_TYPE_SELL_WT;
//                $order_arhive_buyer->seller_confirmation_date = date('Y-m-d H:i:s');
//            }

            $order_arhive_buyer->confirm_type = self::ORDER_TYPE_BUY_WT;
            $order_arhive_buyer->buyer_confirmation_date = date('Y-m-d H:i:s');
            $order_arhive_buyer->seller_ip = $this->input->ip_address();

            $order_arhive_seller->buyer_confirmation_date = date('Y-m-d H:i:s');

            $order_arhive_seller->original_order_id = $order_arhive_seller->id;
            $order_arhive_seller->confirm_type = $order_arhive_seller->type;
            $order_arhive_seller->initiator = 1;

            unset($order_arhive_buyer->id);
            unset($order_arhive_seller->id);

            $buyer_arhiv_order_id = $this->_inser_arhiv_data($order_arhive_buyer);

            $seller_arhiv_order_id = $this->_inser_arhiv_data($order_arhive_seller);

            if(!$buyer_arhiv_order_id || !$order_arhive_seller)
            {
                return FALSE;
            }

            $res1 = $this->_update_arhiv_data($buyer_arhiv_order_id, $seller_arhiv_order_id);
//            vre($this->db->last_query());
            $res2 = $this->_update_arhiv_data($seller_arhiv_order_id, $buyer_arhiv_order_id);
//            vred($this->db->last_query());
            if($res1 === false || $res2 === false  )
            {
                return false;
            }

            $this->db->trans_complete();

//            $text = 'Ваша заявка на P2P перевод № '.$order->id.' принята.';

//            $this->mail->send($user_data->email, $text, 'Заявка принята');
//            $this->mail->send($user_data->email, $text, 'Заявка на обмен '.$order_edit->seller_amount.' USD Активна.', 'no-reply@webtransfer.com');

//            if($sms_send === true)
//            {
//                $this->load->model('phone_model', 'phone_model');
//                $sms_text = $text;
//                $phone = $user_data->phone;
//
//                $t_own_id = $this->transactions->addPay(
//                    $user_data->id_user,
//                    0.05,
//                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
//                    0,
//                    'wt',
//                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
//                    Base_model::TRANSACTION_BONUS_OFF,
//                    "Коммисия за SMS");
//
//                $this->phone_model->sendCode($sms_text, $phone, 1);
//            }


//            return true;
//            return $order_arhive_buyer->confirm_type;
            return [$buyer_arhiv_order_id, $seller_arhiv_order_id];
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }


    private function _inser_arhiv_data(stdClass $data)
    {
        if(empty($data))
        {
            return false;
        }

        $this->db->insert( $this->table_orders_arhive, $data);

        return $this->db->insert_id();
    }

    private function _update_arhiv_data($id, $data)
    {
        if(empty($id) || empty($data))
        {
            return false;
        }

        $this->db->where('id', $id)->limit(1)
                    ->update($this->table_orders_arhive, array('buyer_order_id' => $data));
    }

    private function _update_arhiv_data_seller($id, $data)
    {
        if(empty($id) || empty($data))
        {
            return false;
        }

        $this->db->where('id', $id)->limit(1)
                    ->update($this->table_orders_arhive, array('buyer_order_id' => $data));
    }

    function get_order_details($order_id, $payment_system)
    {
        if( empty( $order_id ) ) return false;

        if( !empty( $payment_system ) ) $this->db->where('payment_system', $payment_system);

        $query = $this->db
                ->where('order_id', $order_id)
                ->limit(1)
                ->get($this->table_order_details)
                ->row();

        if( empty( $query ) ) return false;

        return $query;
    }

    public function set_confirm_order_arhive($arhiv_order_id, $full_file_name, $select_payment_systems, $sms_send, $user_id = false)
    {
        if(empty($arhiv_order_id) || empty($select_payment_systems))
        {
            return false;
        }

        if($user_id === FALSE)
        {
           $user_id = $this->account->get_user_id();
        }


//        $type = Currency_exchange_model::ORDER_TYPE_BUY_WT;
        $arh_res = $this->db
                        ->where('id', $arhiv_order_id)
                    //    ->where('seller_user_id', $user_id)
                        ->where('status', self::ORDER_STATUS_CONFIRMATION)
                        ->get($this->table_orders_arhive)
                        ->row();



        if(empty($arh_res))
        {
            return false;
        }


        $data = array(
//            'buyer_send_money_date' => date('Y-m-d H:i:s'),
            'buyer_document_image_path' => $full_file_name,
//            'buyer_confirmed' => 1,
            'buyer_user_id' =>  $user_id,
            'payed_system' => $select_payment_systems,

                );

        $order_detail = $this->get_order_details($arh_res->original_order_id, $select_payment_systems);
        if( !empty( $order_detail ) )
            $data['buyer_amount_down'] = $order_detail->summa;


        if( 0&& $user_id == 500733 || $user_id == 500575 )
        {
            vred( [$order_detail, $select_payment_systems ] );
        }
//        vred($arh_res->type != $type);

//        if($arh_res->type != $type)
//        {
//            return false;
//        }

        //TODO потом понадобится
//        if($type == self::ORDER_TYPE_BUY_WT)
//        {
//            $sell_payment_data = unserialize($arh_res->sell_payment_data);
//            foreach($sell_payment_data as $sp_val)
//            {
//                if($sp_val->id == $select_payment_systems)
//                {
//                    $select_user_payment_system = $this->get_user_paymant_data_by_payment_system_id($sp_val->payment_system_id, $user_id);
//                    break;
//                }
//            }
//
//            $data['sell_payment_data'] = serialize($select_user_payment_system);
//        }


        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $arhiv_order_id)
              //  ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->set_status_to_details( $arh_res->original_order_id, $arh_res->status );

            $this->db->trans_complete();

            if($arh_res->seller_user_id == $user_id)
            {
                $arh_res2 = $this->db
                            ->where('id', $arh_res->buyer_order_id)
                            ->get($this->table_orders_arhive)
                            ->row();
            }
            else
            {
                 $arh_res2 =  $arh_res;
            }

//              TODO удалить
//            $this->load->model('users_model', 'users_model');
//            $user_data_kontragent = $this->users_model->getUserData($arh_res2->seller_user_id);

//            $text = 'Ваша заявка на P2P перевод № '.$arh_res2->id.' принята.';
//            $this->mail->send($user_data_kontragent->email, $text, 'Заявка на обмен '.$arh_res2->seller_amount.' USD Активна.', 'no-reply@webtransfer.com');

            if($sms_send === true)
            {
//                $this->load->model('phone_model', 'phone_model');
//                $sms_text = $text;
//                $phone = $user_data_kontragent->phone;
//
//                // За смс платит тот кто заказывает его отправку
//                $t_own_id = $this->transactions->addPay(
//                    $user_id,
//                    0.05,
//                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
//                    0,
//                    'wt',
//                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
//                    Base_model::TRANSACTION_BONUS_OFF,
//                    "Коммисия за SMS");
//
//                $this->phone_model->sendCode($sms_text, $phone, 1);
                $this->send_sms_by_user_id($arh_res2->seller_user_id, $text, $user_id);
            }
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function set_buyer_confirm_order_arhive($arhiv_order_id, $user_id = false)
    {
        if(empty($arhiv_order_id) )
        {
            return false;
        }

        if($user_id === FALSE)
        {
           $user_id = $this->account->get_user_id();
        }

        $data = array(
            'buyer_confirmed' => 1,
                );

        $arh_res = $this->db
                        ->where('id', $arhiv_order_id)
                    //    ->where('seller_user_id', $user_id)
                        ->where('status', self::ORDER_STATUS_CONFIRMATION)
                        ->get($this->table_orders_arhive)
                        ->row();

        if(empty($arh_res))
        {
            return false;
        }

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $arhiv_order_id)
            //    ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);
//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->set_status_to_details( $arh_res->original_order_id, $arh_res->status );
//            vred($this->db->last_query());

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function send_sms_by_user_id($user_id_to, $text, $user_id_from = false)
    {
        if(empty($user_id_to) || empty($text))
        {
            return false;
        }

        if($user_id_from === false)
        {
            $user_id_from = $this->account->get_user_id();
        }

        $this->load->model('users_model', 'users_model');
        $user_data_kontragent = $this->users_model->getUserData($user_id_to);

        $this->load->model('phone_model', 'phone_model');
        $phone = $user_data_kontragent->phone;

        // За смс платит тот кто заказывает его отправку
        $t_own_id = $this->transactions->addPay(
            $user_id_from,
            0.05,
//            (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
            Transactions_model::TYPE_EXPENSE_SMS,
            0,
            'wt',
            (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
            5,
            _e('currency_exchange/model/sms_comiss'));

        $this->phone_model->sendCode($text, $phone, 1);
    }



    public function send_mail_by_user_id($user_id, $subject, $text)
    {
        if(empty($user_id) || empty($text) || empty($subject))
        {
            return false;
        }

        $this->load->model('users_model', 'users_model');
        $user_data = $this->users_model->getUserData($user_id);
//        vre($user_id, $user_data->email, $subject, $text);

        $text2 = preg_replace('|[\n\\r]|', '', $text);

        $this->mail->send($user_data->email, $text2, $subject, 'no-reply@webtransfer.com');
    }



    public function set_confirm_order_arhive_seller($arhiv_order_id, $full_file_name, $type,  $user_id = false)
    {
        if(empty($arhiv_order_id) || empty($full_file_name))
        {
            return false;
        }

        if($user_id === FALSE)
        {
           $user_id = $this->account->get_user_id();
        }

        $data = array(
            'buyer_document_image_path' => $full_file_name,
            'seller_confirmed' => 1,
            'buyer_user_id' =>  $user_id,
                );

        $this->_set_confirm_order_arhive($data, $arhiv_order_id, $type,  $user_id);
    }



    private function _set_confirm_order_arhive($data, $arhiv_order_id, $type,  $user_id)
    {
        try
        {
            $this->db->trans_start();

            $arh_res = $this->db
                        ->where('id', $arhiv_order_id)
                      //  ->where('seller_user_id', $user_id)
                        ->get($this->table_orders_arhive)
                        ->row();

            if($arh_res->type != $type)
            {
                return false;
            }

            $this->db
                ->where('id', $arhiv_order_id)
             //   ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->set_status_to_details( $arh_res->original_order_id, $arh_res->status );

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }





    public function doc_file_uploaded_by_orig_order_id( $order_id, $seller_user_id = null )
    {
        if( empty( $order_id ) )
            return FALSE;

        if( !empty( $seller_user_id ) )
            $arh_res = $this->db
                        ->where('seller_user_id', $seller_user_id);

        $arh_res = $this->db
                        ->where('original_order_id', $order_id)
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get($this->table_orders_arhive)
                        ->row();

        if(empty($arh_res))
            return false;

        $doc_name = '';
        if( !empty( $arh_res->buyer_document_image_path ) && $arh_res->buyer_document_image_path != 'wt' )
            $doc_name = $arh_res->buyer_document_image_path;

        if( !empty( $arh_res->seller_document_image_path ) && $arh_res->seller_document_image_path != 'wt' )
            $doc_name = $arh_res->seller_document_image_path;

        if( empty( $doc_name ) ) return FALSE;

        return $doc_name;
    }

    public function get_doc_file_name_by_orig_order_id( $order_id )
    {
        if( empty( $order_id ) )
            return FALSE;

        $arh_res = $this->db
                        ->where('original_order_id', $order_id)
                        ->order_by('id', 'DESC')
                        ->limit(1)
                        ->get($this->table_orders_arhive)
                        ->row();

        if(empty($arh_res))
        {
            return false;
        }

        return $arh_res;
    }
    public function get_doc_file_name_by_user_id( $user_id, $doc_num )
    {
        $arh_res = $this->db
                        ->where('id', $doc_num)
                   //     ->where('seller_user_id', $user_id)
                        ->get($this->table_orders_arhive)
                        ->row();

        if(empty($arh_res))
        {
            return false;
        }

        return $arh_res;
    }


    public function set_seller_confirm_simple($original_order_id, $value) {
        if(empty($original_order_id) || empty($value)) 
            return FALSE;

        return $this->db
            ->where('original_order_id', $original_order_id)
            ->update($this->table_orders_arhive, ['seller_confirmed' => $value]);
    }

    public function set_seller_confirm($user_id, $confirm_id, $foto_path, $seller_confirmed = 1)
    {
        $arh_res = $this->db
                    ->where('id', $confirm_id)
                //    ->where('seller_user_id', $user_id)
                    ->where('status', self::ORDER_STATUS_CONFIRMATION)
                    ->get($this->table_orders_arhive)
                    ->row();

        if($arh_res->seller_document_image_path && $foto_path === FALSE)
        {
            $foto_path = $arh_res->seller_document_image_path;
        }

        if(empty($arh_res) || empty($foto_path))
        {
            return FALSE;
        }

        $data = array(
            'seller_confirmed' => $seller_confirmed,
            'seller_document_image_path' => $foto_path,
//            'seller_send_money_date' => date('Y-m-d H:i:s'),
        );

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
          //      ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vred($this->db->last_query());
            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }

        public function set_seller_confirm_v2($user_id, $confirm_id, $foto_path, $seller_confirmed = 1)
        {
            $arh_res = $this->db
                        ->where('id', $confirm_id)
                    //    ->where('seller_user_id', $user_id)
                        ->where('status', self::ORDER_STATUS_CONFIRMATION)
                        ->get($this->table_orders_arhive)
                        ->row();

            if($arh_res->seller_document_image_path && $foto_path === FALSE)
            {
                $foto_path = $arh_res->seller_document_image_path;
            }

            if(empty($arh_res) || empty($foto_path))
            {
                return FALSE;
            }

            $data = array(
                'seller_confirmed' => $seller_confirmed,
                'seller_document_image_path' => $foto_path,
    //            'seller_send_money_date' => date('Y-m-d H:i:s'),
            );

            try
            {
                // $this->db->trans_start();

                $this->db
                    ->where('id', $confirm_id)
              //      ->where('seller_user_id', $user_id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

    //            vre($this->db->last_query());

                $this->db
                    ->where('id', $arh_res->buyer_order_id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

    //            vred($this->db->last_query());
                // $this->db->trans_complete();
            }
            catch( Exception $exc )
            {
    //            pre($this->db->last_query());
    //            vred(self::$last_error = $exc->getTraceAsString());
                self::$last_error = $exc->getTraceAsString();
                return false;
            }

            return true;
        }

    public function set_archive_order_data($arch_order_ids, $data, $arch_order_status = self::ORDER_STATUS_CONFIRMATION)
    {
        if(is_array($arch_order_status))
        {
            $this->db->where_in('status', $arch_order_status);
        }
        else
        {
            $this->db->where('status', $arch_order_status);
        }

        $arh_res = $this->db
                    ->where_in('id', $arch_order_ids)
                    ->get($this->table_orders_arhive)
                    ->row();

        if(empty($arh_res))
        {
            return FALSE;
        }

        try
        {
            $this->db->trans_start();

            $this->db
                ->where_in('id', $arch_order_ids)
                ->limit(2)
                ->update($this->table_orders_arhive, $data);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function set_order_data($user_id, $confirm_id, $data, $status = self::ORDER_STATUS_CONFIRMATION)
    {
        if(is_array($status))
        {
            $this->db->where_in('status', $status);
        }
        else
        {
            $this->db->where('status', $status);
        }

        $arh_res = $this->db
                    ->where('id', $confirm_id)
     //               ->where('seller_user_id', $user_id)
//                    ->where('status', self::ORDER_STATUS_CONFIRMATION)
                    ->get($this->table_orders_arhive)
                    ->row();

//        vre($this->db->last_query());
//        pred($arh_res);

        if(empty($arh_res))
        {
            return FALSE;
        }

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
            //    ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);


            //Ещё раз извлекаем статус для того чтоб извдечь статус если он был изменён
            $arh_res = $this->db
                    ->where('id', $confirm_id)
                    ->get($this->table_orders_arhive)
                    ->row();
//            pred($arh_res);
            $this->set_status_to_details( $arh_res->original_order_id, $arh_res->status );

//            vred($this->db->last_query());
            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }

     public function set_order_data_v2($user_id, $confirm_id, $data, $status = self::ORDER_STATUS_CONFIRMATION)
    {
        if(is_array($status))
        {
            $this->db->where_in('status', $status);
        }
        else
        {
            $this->db->where('status', $status);
        }

        $arh_res = $this->db
                    ->where('id', $confirm_id)
     //               ->where('seller_user_id', $user_id)
//                    ->where('status', self::ORDER_STATUS_CONFIRMATION)
                    ->get($this->table_orders_arhive)
                    ->row();

//        vre($this->db->last_query());
//        pred($arh_res);

        if(empty($arh_res))
        {
            return FALSE;
        }

        try
        {
            // $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
            //    ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);


            //Ещё раз извлекаем статус для того чтоб извдечь статус если он был изменён
            $arh_res = $this->db
                    ->where('id', $confirm_id)
                    ->get($this->table_orders_arhive)
                    ->row();
//            pred($arh_res);
            $this->set_status_to_details( $arh_res->original_order_id, $arh_res->status );

//            vred($this->db->last_query());
            // $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }

    function set_status_to_details_orig_order( $order_id, $status )
    {
        $this->set_status_to_details( $order_id, $status );
        $this->set_status_to_orig_order( $order_id, $status );
    }
    function set_status_to_details( $order_id, $status )
    {
        if( empty( $order_id ) || empty( $status ) ) return FALSE;

        $details = array(
            'order_status' => $status
        );
        $this->db
                ->where('order_id', $order_id)
                ->limit(15)
                ->update($this->table_order_details, $details);
    }
    function set_status_to_orig_order( $order_id, $status )
    {

        if( empty( $order_id ) || empty( $status ) )
            return FALSE;

        $details = array(
            'status' => $status
        );
        $this->db
                ->where('id', $order_id)
                ->limit(1)
                ->update($this->table_orders, $details);
        
    }

    function set_status_archives_by_orig_id($original_order_id, $status) {
        if( empty( $original_order_id ) || empty( $status ) )
            return FALSE;

        $q = $this->db
            ->where('original_order_id', $original_order_id)
            ->limit(1)
            ->update($this->table_orders_arhive, ['status' => $status]);

        if(empty($q))
            return false;

        return true;

    }

    function set_status_to_arch_order_by_orig_order_id( $seller_user_id, $orig_order_id, $status )
    {
//        echo "$seller_user_id, $orig_order_id, $status<br>";

        if( empty( $orig_order_id ) || empty( $status ) || empty( $seller_user_id ) )
            return FALSE;

        $details = array(
            'status' => $status
        );

        $res = $this->db->select('id')
                        ->where('original_order_id', $orig_order_id)
                        ->where('seller_user_id', $seller_user_id)
                        ->limit(1)
                        ->order_by('id','DESC')
                        ->get($this->table_orders_arhive)
                        ->row();

        if( empty( $res ) ) return FALSE;

        $this->db
                ->where('id', $res->id)
                ->limit(1)
                ->update($this->table_orders_arhive, $details);
        $this->set_status_to_details( $orig_order_id, $status );

        return TRUE;
    }



    public function set_last_confirm_v2($user_id, $confirm_id)
        {
            $arh_res = $this->db
                        ->where('id', $confirm_id)
                   //     ->where('seller_user_id', $user_id)
                        ->get($this->table_orders_arhive)
                        ->row();

            if(
                    empty($arh_res) ||
                    $arh_res->buyer_confirmed != 1 ||
                    !$arh_res->seller_confirmed ||
                    $arh_res->status != self::ORDER_STATUS_CONFIRMATION
            )
            {
                return FALSE;
            }

            $data = array(
    //            'seller_confirmed' => 1,
                'status' => self::ORDER_STATUS_SUCCESS,
            );

            if($user_id == $arh_res->buyer_user_id)
            {
                $res_dop = $this->db
                    ->where('id', $arh_res->buyer_order_id)
                    ->get($this->table_orders_arhive)
                    ->row()
                    ;
                $arh_res->buyer_user_id = $res_dop->seller_user_id;
            }

            $ps = array_set_value_in_key($this->get_all_payment_systems());

            $sell_payment_data = unserialize($arh_res->sell_payment_data);

            foreach($sell_payment_data as $spd)
            {
                if($spd->id == $arh_res->payed_system)
                {
                    break;
                }
            }

            $buy_payment_data = unserialize($arh_res->buy_payment_data);

            foreach($buy_payment_data as $bpd)
            {
                if($bpd->id == $arh_res->sell_system)
                {
                    break;
                }
            }

            $order_details_arhiv = unserialize($arh_res->order_details_arhiv);
            $order_details_arhiv = array_set_value_in_key($order_details_arhiv, 'payment_system');

    //        pre($order_details_arhiv);
    ////        pre($this->_get_user_orders_arhive_details(array($arh_res)));
    //        pre(unserialize($arh_res->order_details_arhiv));
    //        pre($spd);
    //        pre($bpd);
    //
    //        vre($ps[$spd->payment_system_id]->machine_name);
    //        vre($ps[$bpd->payment_system_id]->machine_name);
    //
    //        pred('>>>>');

            $user_in = false;
            $user_from = false;
            $transaction_bonus = false;
    //        pred($ps[$spd->payment_system_id]->machine_name);
    //        vre($ps[$spd->payment_system_id]->machine_name);
    //        vre($ps[$bpd->payment_system_id]->machine_name);

            if(isset($res_dop))
            {
                $seller_user_id = $res_dop->seller_user_id;
                $buyer_user_id = $res_dop->buyer_user_id;
            }
            else
            {
                $seller_user_id = $arh_res->seller_user_id;
                $buyer_user_id = $arh_res->buyer_user_id;
            }

    //        vre('$seller_user_id = '.$seller_user_id);
    //        vre('$buyer_user_id = '.$buyer_user_id);
    //        vre($user_id);
    //        vre( $arh_res->buyer_user_id);

    //        if($ps[$spd->payment_system_id]->machine_name == 'wt')
    //        {
    //            $user_in = $user_id;
    //            $user_from = $arh_res->buyer_user_id;
    //            $summa = $order_details_arhiv[$spd->payment_system_id]->summa;
    //        }
    //        elseif($ps[$bpd->payment_system_id]->machine_name == 'wt')
    //        {
    //            $user_in = $arh_res->buyer_user_id;
    //            $user_from = $user_id;
    //            $summa = $order_details_arhiv[$bpd->payment_system_id]->summa;
    //        }

    //        if( !isset( $arh_res->bonus ) ) $arh_res->bonus = 5;
            if( !isset( $arh_res->bonus ) || empty($arh_res->bonus) )
            {
                $arh_res->bonus = 5;
            }

    //        if($ps[$spd->payment_system_id]->machine_name == 'wt')
            if($this->is_root_currency($ps[$spd->payment_system_id]->machine_name))
            {
                $user_in = $seller_user_id;
                $user_from = $buyer_user_id;

                $summa = !empty($order_details_arhiv[$spd->payment_system_id]->summa)?
                        $order_details_arhiv[$spd->payment_system_id]->summa:
                        $arh_res->seller_amount;

                $order_fee = $arh_res->seller_fee;
    //            $transaction_bonus = $ps[$spd->payment_system_id]->transaction_bonus;
            }
            elseif($this->is_root_currency($ps[$bpd->payment_system_id]->machine_name))
            {
                $user_in = $buyer_user_id;
                $user_from = $seller_user_id;

                $summa = !empty($order_details_arhiv[$bpd->payment_system_id]->summa)?
                        $order_details_arhiv[$bpd->payment_system_id]->summa:
                        $arh_res->seller_amount;

                $order_fee = $arh_res->seller_fee;
    //            $transaction_bonus = $ps[$bpd->payment_system_id]->transaction_bonus;
            }
            else
            {
    //            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, false, $arh_res->bonus);
                #################
    //            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, FALSE, $order_details_arhiv[$spd->payment_system_id]->choice_currecy);
    //            $order_fee = $summ_fee[1];
                $order_fee = $arh_res->seller_fee;
            }

            $transaction_bonus = $arh_res->bonus;

    //        pre($spd->payment_system_id);
    //        pre($bpd->payment_system_id);
    //        pred($transaction_bonus);

            //<editor-fold defaultstate="collapsed" desc="SB ckeck if discount > 10%">

            #SB, подтверждение получения средств контрагентом
                $set_status_sb = $this->is_set_status_sb( $arh_res->original_order_id, 2);
        
                if( !empty( $set_status_sb ) &&  $set_status_sb['result'] == TRUE )
                //if( $buy_payment_id == 116  && $res !== false && $order->wt_set == 2  &&  $order->discount < 0 )
                {
                    $note_arr = [
                        'order_id' => $arh_res->original_order_id, 
                        'text' => $set_status_sb['comment'], 
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

                    $this->currency_exchange->add_operator_note($note_arr);

                    $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;            
                    $this->currency_exchange->set_status_to_order_arhive_and_order($arh_res->original_order_id, $status, $status);

                    $this->currency_exchange->send_mail($arh_res->original_order_id, 'order_processing', $user_id);

                    return true;
                }

            /*

            $ps = [];
            $buy_ps = self::get_ps($arh_res->payed_system);
            $sell_ps = self::get_ps($arh_res->sell_system);

            $ps['buy'] = [[$buy_ps->machine_name => $arh_res->payed_system], [$buy_ps->machine_name => $arh_res->buyer_amount_down], [$buy_ps->machine_name => $buy_ps->currency_id]];
            $ps['sell'] = [[$sell_ps->machine_name => $arh_res->sell_system],[$sell_ps->machine_name => $arh_res->seller_amount],[$sell_ps->machine_name => $sell_ps->currency_id]];


            list($minus_discount, $wt_ps, $other, $comment) = $this->add_payment_system_discount( $ps, null, 2 );
            //поиск всех, кто ставит больше нужного размера


            if( 0 && $minus_discount == TRUE )
            {
                $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;

                $note_arr = [
                    'order_id' => $arh_res->original_order_id,
                    'text' => $comment,
                    'date_modified' => date('Y-m-d H:i:s'),
                ];

                $this->currency_exchange->add_operator_note($note_arr);
                $this->currency_exchange->set_status_to_order_arhive_and_order($arh_res->original_order_id, $status);

                $this->currency_exchange->send_mail($arh_res->original_order_id, 'order_processing', $user_id);

                return -1;

            }  
            */

            //</editor-fold>

            try
            {
                // $this->db->trans_start();

                $this->db
                    ->where('id', $confirm_id)
               //     ->where('seller_user_id', $user_id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

    //            vre($this->db->last_query());

                $this->db
                    ->where('id', $arh_res->buyer_order_id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

                if( isset( $data['status'] ) ) $this->set_status_to_details( $arh_res->original_order_id, $data['status'] );
                $this->load->model('transactions_model','transactions');

    //            vre($user_id);
    //            vre('>>>>>');
    //            vre($user_in);
    //            vre($user_from);
                if($user_in !== false && $user_from !== false)
                {

                    if($transaction_bonus === false)
                    {
                        throw new Exception('Error order not confirm.');
                    }

                    $t_own_id = $this->transactions->addPay(
                            $user_in,
                            $summa,
    //                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                            Transactions_model::TYPE_SEND_MONEY_P2P,
    //                        0,
                            $arh_res->original_order_id,
                            'wt',
                            (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
    //                        5,
                            $transaction_bonus,
    //                        "Отправка средств пользователю №{$user_in} по заявке обмена  № $arh_res->buyer_order_id");
    //                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->buyer_order_id, $user_in));
                            sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->original_order_id, $user_from));
    //                vre($this->db->last_query());
                    $t_id_user = $this->transactions->addPay(
                            $user_from,
                            $summa,
    //                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                            Transactions_model::TYPE_SEND_MONEY_P2P,
    //                        $user_in,
                            $arh_res->original_order_id,
                            'wt',
                            (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED),
    //                        5,
                            $transaction_bonus,
    //                        "Получение средств от пользователя № {$user_in} за P2P-перевод  № $confirm_id");
    //                        "P2P-перевод по заявке №{$confirm_id} от пользователя № {$user_in}");
    //                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $confirm_id, $user_in));
                            sprintf(_e('currency_exchange/model/p2p_transactio_1_add'), $arh_res->original_order_id, $user_in));
                }
    //            vre($this->db->last_query());
    //            vred($this->db->last_query());

    //            pred();
    //            if($this->is_payment_system_wt($arh_res->payed_system) === false)
    //            {
    //                if($arh_res->confirm_type == $arh_res->type)
    //                {
    //                    $fee_user = $arh_res->seller_user_id;
    //                }
    //                else
    //                {
    //                    $fee_user = $arh_res->buyer_user_id;
    //                }
    //            }
    //            else
    //            {
    //                if($arh_res->confirm_type == $arh_res->type)
    //                {
    //                    $fee_user = $arh_res->buyer_user_id;
    //                }
    //                else
    //                {
    //                    $fee_user = $arh_res->seller_user_id;
    //                }
    //            }

                if($this->is_payment_system_wt($arh_res->payed_system) !== false)
                {
                    if($arh_res->confirm_type == $arh_res->type)
                    {
                        $fee_user = $arh_res->seller_user_id;
                    }
                    else
                    {
                        $fee_user = $arh_res->buyer_user_id;
                    }
                }
                elseif ($this->is_payment_system_wt($arh_res->sell_system) !== false)
                {
                    if($arh_res->confirm_type == $arh_res->type)
                    {
                        $fee_user = $arh_res->buyer_user_id;

                    }
                    else
                    {
                        $fee_user = $arh_res->seller_user_id;
                    }
                }
                else
                {
                    if($arh_res->confirm_type == $arh_res->type)
                    {
                        $fee_user = $arh_res->seller_user_id;
                    }
                    else
                    {
                        $fee_user = $arh_res->buyer_user_id;
                    }
                }


                $create_wt_comission = $this->_allow_comission_by_cross_ps($arh_res->sell_system ,$arh_res->payed_system);

                if($create_wt_comission) {
                    $transaction_bonus === false? 5 :'';
        // Коммисия
                    $t_own_id = $this->transactions->addPay(
        //                    $arh_res->buyer_user_id,
                            $fee_user,
                            $order_fee,
        //                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
        //                    Transactions_model::TYPE_EXPENSE_OUTFEE,
                            Transactions_model::TYPE_EXPENSE_P2P_FEE,
        //                    0,
                            $arh_res->original_order_id,
                            'wt',
                            (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
        //                    5,
                            $transaction_bonus,
        //                    "Коммисия за P2P-перевод по заявке № $arh_res->buyer_order_id");
                            sprintf(_e('currency_exchange/model/p2p_transactio_2'), $arh_res->original_order_id));

        //отчисление Колесу

                    $p = $this->_receive_percentage_of_transaction($arh_res);
        //            vred($p);
                    $this->transactions->addPay(
                            54049637,
                           // round($order_fee/2, 2),
                            $p['percent'],
                            Transactions_model::TYPE_EXPENSE_P2P_FEE,
                            $arh_res->original_order_id,
                            'wt',
                            Base_model::TRANSACTION_STATUS_RECEIVED,
                            6,
                            sprintf(_e('Отчисления за P2P перевод № %s, сумма %s, курс: %s '),
                                    $arh_res->original_order_id,
        //                            $p['orig_summ'].' '._e('currency_id_'.$p['ps']->currency_id),
                                    $p['orig_summ'].' '.$p['currency'],
                                    $p['rate']
                                    ));
                }

                    $this->db->limit(1)
                        ->update($this->table_orders, array('status'=> self::ORDER_STATUS_SUCCESS),array('id' => $arh_res->original_order_id));
                    $this->db->limit(20)
                        ->update($this->table_order_details, array('order_status'=> self::ORDER_STATUS_SUCCESS), array('order_id' => $arh_res->original_order_id));

                    $this->set_status_to_details( $arh_res->original_order_id, self::ORDER_STATUS_SUCCESS );
               
    //            vred($this->db->last_query());
                $this->currency_exchange->send_mail_by_arhiv_order_id($arh_res->id, 'order_confirm_close', $user_id);
                $this->currency_exchange->send_mail_by_arhiv_order_id($arh_res->id, 'order_confirm_close', $user_id, true);
    //            $this->send_mail($arh_res->original_order_id, 'order_confirm_close', $user_id, true);
    //            $this->send_mail($arh_res->original_order_id, 'order_confirm_close', $user_id);
        //        if($arh_res->seller_user_id == '75705622')

                // $this->db->trans_complete();
                $this->update_rating(array('id' => $arh_res->id));
               
            }
            catch( Exception $exc )
            {
                //pre($this->db->last_query());
                //vred(self::$last_error = $exc->getTraceAsString());
                self::$last_error = $exc->getTraceAsString();
                return false;
            }

            $this->add_one_new_deal( $arh_res->original_order_id );

            return true;
    }

    public function set_last_confirm($user_id, $confirm_id)
    {
        $arh_res = $this->db
                    ->where('id', $confirm_id)
               //     ->where('seller_user_id', $user_id)
                    ->get($this->table_orders_arhive)
                    ->row();

        if(
                empty($arh_res) ||
                $arh_res->buyer_confirmed != 1 ||
                !$arh_res->seller_confirmed ||
                $arh_res->status != self::ORDER_STATUS_CONFIRMATION
        )
        {
            return FALSE;
        }

        $data = array(
//            'seller_confirmed' => 1,
            'status' => self::ORDER_STATUS_SUCCESS,
        );

        if($user_id == $arh_res->buyer_user_id)
        {
            $res_dop = $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->get($this->table_orders_arhive)
                ->row()
                ;
            $arh_res->buyer_user_id = $res_dop->seller_user_id;
        }

        $ps = array_set_value_in_key($this->get_all_payment_systems());

        $sell_payment_data = unserialize($arh_res->sell_payment_data);

        foreach($sell_payment_data as $spd)
        {
            if($spd->id == $arh_res->payed_system)
            {
                break;
            }
        }

        $buy_payment_data = unserialize($arh_res->buy_payment_data);

        foreach($buy_payment_data as $bpd)
        {
            if($bpd->id == $arh_res->sell_system)
            {
                break;
            }
        }

        $order_details_arhiv = unserialize($arh_res->order_details_arhiv);
        $order_details_arhiv = array_set_value_in_key($order_details_arhiv, 'payment_system');

//        pre($order_details_arhiv);
////        pre($this->_get_user_orders_arhive_details(array($arh_res)));
//        pre(unserialize($arh_res->order_details_arhiv));
//        pre($spd);
//        pre($bpd);
//
//        vre($ps[$spd->payment_system_id]->machine_name);
//        vre($ps[$bpd->payment_system_id]->machine_name);
//
//        pred('>>>>');

        $user_in = false;
        $user_from = false;
        $transaction_bonus = false;
//        pred($ps[$spd->payment_system_id]->machine_name);
//        vre($ps[$spd->payment_system_id]->machine_name);
//        vre($ps[$bpd->payment_system_id]->machine_name);

        if(isset($res_dop))
        {
            $seller_user_id = $res_dop->seller_user_id;
            $buyer_user_id = $res_dop->buyer_user_id;
        }
        else
        {
            $seller_user_id = $arh_res->seller_user_id;
            $buyer_user_id = $arh_res->buyer_user_id;
        }

//        vre('$seller_user_id = '.$seller_user_id);
//        vre('$buyer_user_id = '.$buyer_user_id);
//        vre($user_id);
//        vre( $arh_res->buyer_user_id);

//        if($ps[$spd->payment_system_id]->machine_name == 'wt')
//        {
//            $user_in = $user_id;
//            $user_from = $arh_res->buyer_user_id;
//            $summa = $order_details_arhiv[$spd->payment_system_id]->summa;
//        }
//        elseif($ps[$bpd->payment_system_id]->machine_name == 'wt')
//        {
//            $user_in = $arh_res->buyer_user_id;
//            $user_from = $user_id;
//            $summa = $order_details_arhiv[$bpd->payment_system_id]->summa;
//        }

//        if( !isset( $arh_res->bonus ) ) $arh_res->bonus = 5;
        if( !isset( $arh_res->bonus ) || empty($arh_res->bonus) )
        {
            $arh_res->bonus = 5;
        }

//        if($ps[$spd->payment_system_id]->machine_name == 'wt')
        if($this->is_root_currency($ps[$spd->payment_system_id]->machine_name))
        {
            $user_in = $seller_user_id;
            $user_from = $buyer_user_id;

            $summa = !empty($order_details_arhiv[$spd->payment_system_id]->summa)?
                    $order_details_arhiv[$spd->payment_system_id]->summa:
                    $arh_res->seller_amount;

            $order_fee = $arh_res->seller_fee;
//            $transaction_bonus = $ps[$spd->payment_system_id]->transaction_bonus;
        }
        elseif($this->is_root_currency($ps[$bpd->payment_system_id]->machine_name))
        {
            $user_in = $buyer_user_id;
            $user_from = $seller_user_id;

            $summa = !empty($order_details_arhiv[$bpd->payment_system_id]->summa)?
                    $order_details_arhiv[$bpd->payment_system_id]->summa:
                    $arh_res->seller_amount;

            $order_fee = $arh_res->seller_fee;
//            $transaction_bonus = $ps[$bpd->payment_system_id]->transaction_bonus;
        }
        else
        {
//            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, false, $arh_res->bonus);
            #################
//            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, FALSE, $order_details_arhiv[$spd->payment_system_id]->choice_currecy);
//            $order_fee = $summ_fee[1];
            $order_fee = $arh_res->seller_fee;
        }

        $transaction_bonus = $arh_res->bonus;

//        pre($spd->payment_system_id);
//        pre($bpd->payment_system_id);
//        pred($transaction_bonus);

        //<editor-fold defaultstate="collapsed" desc="SB ckeck if discount > 10%">

        #SB, подтверждение получения средств контрагентом
            $set_status_sb = $this->is_set_status_sb( $arh_res->original_order_id, 2);
    
            if( !empty( $set_status_sb ) &&  $set_status_sb['result'] == TRUE )
            //if( $buy_payment_id == 116  && $res !== false && $order->wt_set == 2  &&  $order->discount < 0 )
            {
                $note_arr = [
                    'order_id' => $arh_res->original_order_id, 
                    'text' => $set_status_sb['comment'], 
                    'date_modified' => date('Y-m-d H:i:s'),
                ];

                $this->currency_exchange->add_operator_note($note_arr);

                $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;            
                $this->currency_exchange->set_status_to_order_arhive_and_order($arh_res->original_order_id, $status, $status);

                $this->currency_exchange->send_mail($arh_res->original_order_id, 'order_processing', $user_id);

                return true;
            }

        /*

        $ps = [];
        $buy_ps = self::get_ps($arh_res->payed_system);
        $sell_ps = self::get_ps($arh_res->sell_system);

        $ps['buy'] = [[$buy_ps->machine_name => $arh_res->payed_system], [$buy_ps->machine_name => $arh_res->buyer_amount_down], [$buy_ps->machine_name => $buy_ps->currency_id]];
        $ps['sell'] = [[$sell_ps->machine_name => $arh_res->sell_system],[$sell_ps->machine_name => $arh_res->seller_amount],[$sell_ps->machine_name => $sell_ps->currency_id]];


        list($minus_discount, $wt_ps, $other, $comment) = $this->add_payment_system_discount( $ps, null, 2 );
        //поиск всех, кто ставит больше нужного размера


        if( 0 && $minus_discount == TRUE )
        {
            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;

            $note_arr = [
                'order_id' => $arh_res->original_order_id,
                'text' => $comment,
                'date_modified' => date('Y-m-d H:i:s'),
            ];

            $this->currency_exchange->add_operator_note($note_arr);
            $this->currency_exchange->set_status_to_order_arhive_and_order($arh_res->original_order_id, $status);

            $this->currency_exchange->send_mail($arh_res->original_order_id, 'order_processing', $user_id);

            return -1;

        }  
        */

        //</editor-fold>

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
           //     ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            if( isset( $data['status'] ) ) $this->set_status_to_details( $arh_res->original_order_id, $data['status'] );
            $this->load->model('transactions_model','transactions');

//            vre($user_id);
//            vre('>>>>>');
//            vre($user_in);
//            vre($user_from);
            if($user_in !== false && $user_from !== false)
            {

                if($transaction_bonus === false)
                {
                    throw new Exception('Error order not confirm.');
                }

                $t_own_id = $this->transactions->addPay(
                        $user_in,
                        $summa,
//                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                        Transactions_model::TYPE_SEND_MONEY_P2P,
//                        0,
                        $arh_res->original_order_id,
                        'wt',
                        (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
//                        5,
                        $transaction_bonus,
//                        "Отправка средств пользователю №{$user_in} по заявке обмена  № $arh_res->buyer_order_id");
//                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->buyer_order_id, $user_in));
                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->original_order_id, $user_from));
//                vre($this->db->last_query());
                $t_id_user = $this->transactions->addPay(
                        $user_from,
                        $summa,
//                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                        Transactions_model::TYPE_SEND_MONEY_P2P,
//                        $user_in,
                        $arh_res->original_order_id,
                        'wt',
                        (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED),
//                        5,
                        $transaction_bonus,
//                        "Получение средств от пользователя № {$user_in} за P2P-перевод  № $confirm_id");
//                        "P2P-перевод по заявке №{$confirm_id} от пользователя № {$user_in}");
//                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $confirm_id, $user_in));
                        sprintf(_e('currency_exchange/model/p2p_transactio_1_add'), $arh_res->original_order_id, $user_in));
            }
//            vre($this->db->last_query());
//            vred($this->db->last_query());

//            pred();
//            if($this->is_payment_system_wt($arh_res->payed_system) === false)
//            {
//                if($arh_res->confirm_type == $arh_res->type)
//                {
//                    $fee_user = $arh_res->seller_user_id;
//                }
//                else
//                {
//                    $fee_user = $arh_res->buyer_user_id;
//                }
//            }
//            else
//            {
//                if($arh_res->confirm_type == $arh_res->type)
//                {
//                    $fee_user = $arh_res->buyer_user_id;
//                }
//                else
//                {
//                    $fee_user = $arh_res->seller_user_id;
//                }
//            }

            if($this->is_payment_system_wt($arh_res->payed_system) !== false)
            {
                if($arh_res->confirm_type == $arh_res->type)
                {
                    $fee_user = $arh_res->seller_user_id;
                }
                else
                {
                    $fee_user = $arh_res->buyer_user_id;
                }
            }
            elseif ($this->is_payment_system_wt($arh_res->sell_system) !== false)
            {
                if($arh_res->confirm_type == $arh_res->type)
                {
                    $fee_user = $arh_res->buyer_user_id;

                }
                else
                {
                    $fee_user = $arh_res->seller_user_id;
                }
            }
            else
            {
                if($arh_res->confirm_type == $arh_res->type)
                {
                    $fee_user = $arh_res->seller_user_id;
                }
                else
                {
                    $fee_user = $arh_res->buyer_user_id;
                }
            }


            $create_wt_comission = $this->_allow_comission_by_cross_ps($arh_res->sell_system ,$arh_res->payed_system);

            if($create_wt_comission) {
                $transaction_bonus === false? 5 :'';
    // Коммисия
                $t_own_id = $this->transactions->addPay(
    //                    $arh_res->buyer_user_id,
                        $fee_user,
                        $order_fee,
    //                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
    //                    Transactions_model::TYPE_EXPENSE_OUTFEE,
                        Transactions_model::TYPE_EXPENSE_P2P_FEE,
    //                    0,
                        $arh_res->original_order_id,
                        'wt',
                        (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
    //                    5,
                        $transaction_bonus,
    //                    "Коммисия за P2P-перевод по заявке № $arh_res->buyer_order_id");
                        sprintf(_e('currency_exchange/model/p2p_transactio_2'), $arh_res->original_order_id));

    //отчисление Колесу

                $p = $this->_receive_percentage_of_transaction($arh_res);
    //            vred($p);
                $this->transactions->addPay(
                        54049637,
                       // round($order_fee/2, 2),
                        $p['percent'],
                        Transactions_model::TYPE_EXPENSE_P2P_FEE,
                        $arh_res->original_order_id,
                        'wt',
                        Base_model::TRANSACTION_STATUS_RECEIVED,
                        6,
                        sprintf(_e('Отчисления за P2P перевод № %s, сумма %s, курс: %s '),
                                $arh_res->original_order_id,
    //                            $p['orig_summ'].' '._e('currency_id_'.$p['ps']->currency_id),
                                $p['orig_summ'].' '.$p['currency'],
                                $p['rate']
                                ));
            }

                $this->db->limit(1)
                    ->update($this->table_orders, array('status'=> self::ORDER_STATUS_SUCCESS),array('id' => $arh_res->original_order_id));
                $this->db->limit(20)
                    ->update($this->table_order_details, array('order_status'=> self::ORDER_STATUS_SUCCESS), array('order_id' => $arh_res->original_order_id));

                $this->set_status_to_details( $arh_res->original_order_id, self::ORDER_STATUS_SUCCESS );
           
//            vred($this->db->last_query());
            $this->currency_exchange->send_mail_by_arhiv_order_id($arh_res->id, 'order_confirm_close', $user_id);
            $this->currency_exchange->send_mail_by_arhiv_order_id($arh_res->id, 'order_confirm_close', $user_id, true);
//            $this->send_mail($arh_res->original_order_id, 'order_confirm_close', $user_id, true);
//            $this->send_mail($arh_res->original_order_id, 'order_confirm_close', $user_id);
    //        if($arh_res->seller_user_id == '75705622')

            $this->db->trans_complete();
			$this->update_rating(array('id' => $arh_res->id));
           
        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        $this->add_one_new_deal( $arh_res->original_order_id );

        return true;
    }


































































    private function _allow_comission_by_cross_ps($sell_ps_id, $buy_ps_id) {
        // В данном масиве можно добавлять новые пары, и все они будут сравниваться, если есть то вернет false;
        $disallow_ps_ids = [
            [113, 115], // creds - visa
            [114, 115], // heart - visa
            [116, 115]  // debit - visa
        ];
        
        // Если мы желаем получить визу, то коммисия с вт нивкоем случае не идет
        if($buy_ps_id == 115)
            return false;
        else if($sell_ps_id == 115)
            return false;


        foreach ($disallow_ps_ids as $disallow_ps_id) {
            if(in_array($sell_ps_id, $disallow_ps_id) && in_array($buy_ps_id, $disallow_ps_id))
                return false;            
        }        
        return true;
    }

    private function _receive_percentage_of_transaction($order, $p = 1)
    {
        $ps_all = $this->get_all_payment_systems();

        if(!isset($order->sell_payment_data_un) || !isset($order->buy_payment_data_un))
        {
            $order = $this->_get_user_orders_arhive_details_one($order);
        }

        if($order->wt_set == 0)
        {
            $summ = $order->buy_payment_data_un[$order->payed_system]->summa;
            $machine_name = $order->buy_payment_data_un[$order->payed_system]->machine_name;
            $choice_currecy = $order->buy_payment_data_un[$order->payed_system]->choice_currecy;
        }
        elseif($order->wt_set == 1)
        {
            $summ = $order->buy_payment_data_un[$order->payed_system]->summa;
            $machine_name = $order->buy_payment_data_un[$order->payed_system]->machine_name;
            $choice_currecy = $order->buy_payment_data_un[$order->payed_system]->choice_currecy;
        }
        elseif($order->wt_set == 2)
        {
            $summ = $order->sell_payment_data_un[$order->sell_system]->summa;
            $machine_name = $order->sell_payment_data_un[$order->sell_system]->machine_name;
            $choice_currecy = $order->sell_payment_data_un[$order->sell_system]->choice_currecy;
        }

        if(!$choice_currecy)
        {
           $choice_currecy = false;
        }

        $ps = self::get_ps($machine_name);

        $cc = empty($choice_currecy)?$ps->currency_id:$choice_currecy;

        $currency = self::show_payment_system_code(['currency_id' => $cc]);

        $rate = $this->get_currency_rate($ps, $choice_currecy);

        $summ_usd = $summ/$rate;


        $percent = round($summ_usd/100*$p, 4);

        if($percent > $summ_usd*0.5)
        {
            $percent = round($summ_usd*0.5, 4);
        }

        return ['percent'=>$percent, 'ps'=>$ps, 'orig_summ'=>$summ, 'rate'=>$rate, 'currency'=>$currency];
    }

    function allowed_get_wt_orders( $user_id = null ) {
        if( empty($user_id) )
        {
            $user_id = $this->accaunt->get_user_id();
            if( empty($user_id) ) return FALSE;
        }

        $count = $this->db->select('COUNT(id) as count')
                ->where('initiator',0)
                ->where('seller_user_id',$user_id)
//                ->where('wt_set',1)
                ->where('status <',60)
                ->order_by('id','DESC')
                ->limit(6)
                ->get($this->table_orders_arhive)
                ->row('count');
        
        
        $max_order_get_user_wt = self::MAX_ORDER_GET_USER_WT;
        $allowed = TRUE;
        
        if( $count < $max_order_get_user_wt )
        {
            return array( 'res' => $allowed, 'max_order_get_user_wt' => $max_order_get_user_wt );
        }
        
        $this->load->model('Users_filds_model','users_filds');
        $max_order_get_user_wt = $this->users_filds->getUserFild( $user_id, 'get_max_count_p2p_wt_orders', FALSE );
        
        $allowed = FALSE;        
        if( $max_order_get_user_wt > 0 ) $allowed = TRUE;
        else
            $max_order_get_user_wt  = self::MAX_ORDER_GET_USER_WT;
        
        return array( 'res' => $allowed, 'max_order_get_user_wt' => $max_order_get_user_wt );
    }
    
    /**
     * Завершение заявки - только для оператора в админку
     * @param int $confirm_id идентификатор заявки
     * @param int|bool $status задать статус false - оставить текущий статус.
     * @param bool $delete_original удалять или нет оригинальную заявку.
     * @return boolean
     */

    public function set_last_confirm_for_operator($confirm_id, $status = false, $delete_original = TRUE, $buyer_user_id = null, $seller_amount = 0, $seller_fee = 0)
    {

        $arh_res = $this->db
                    ->where("(id = $confirm_id)", NULL )
                    ->get($this->table_orders_arhive)
                    ->row();


        if(empty($arh_res))
        {


            if( !empty( $buyer_user_id ) ){
                $this->db->where('seller_user_id', $buyer_user_id );
            }


            $arh_res = $this->db
                        ->where("original_order_id", $confirm_id)
                        ->get($this->table_orders_arhive)
                        ->row();

            if(empty($arh_res))
            {
                //vred(['-1-',$arh_res, $this->db->last_query()]);
                return FALSE;
            }
        }

        if($arh_res->sell_system == 0 || $arh_res->payed_system == 0)
        {
//            return false;

            return 'Не удалось провести операцию - пользователь не выбрал платёжную систему';
        }

        $user_id =  $arh_res->seller_user_id;

        $orig_order = $this->db->where_in('id', $arh_res->original_order_id)
                                ->get($this->table_orders)
                                ->row();

        $buyer_order = $this->db->where('id', $arh_res->buyer_order_id)
                                ->get($this->table_orders_arhive)
                                ->row();

        if( !isset( $orig_order->bonus ) ) $orig_order->bonus = 5;

        if($status === false)
        {
            $status = $arh_res->status;
        }

        $data = array(
                'status' => $status,
                'buyer_get_money_date' => date('Y-m-d H:i:s'),
            );

//        if($user_id == $arh_res->buyer_user_id)
        if($arh_res->seller_user_id == $arh_res->buyer_user_id)
        {
            $res_dop = $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->get($this->table_orders_arhive)
                ->row()
                ;

            $arh_res->buyer_user_id = $res_dop->seller_user_id;
        }

        $ps = array_set_value_in_key($this->get_all_payment_systems());

        $sell_payment_data = unserialize($arh_res->sell_payment_data);

        foreach($sell_payment_data as $spd)
        {
            if($spd->id == $arh_res->payed_system)
            {
                break;
            }
        }

        $buy_payment_data = unserialize($arh_res->buy_payment_data);

        foreach($buy_payment_data as $bpd_val)
        {
//            if($bpd_val->id == $arh_res->sell_system)
            if($bpd_val->payment_system_id == $arh_res->payed_system)
            {
                $bpd = $bpd_val;
                break;
            }
        }

        $order_details_arhiv = unserialize($arh_res->order_details_arhiv);
        $order_details_arhiv = array_set_value_in_key($order_details_arhiv, 'payment_system');

        $user_in = false;
        $user_from = false;

        if(isset($res_dop) )
        {
            $seller_user_id = $res_dop->seller_user_id;
            $buyer_user_id = $res_dop->buyer_user_id;
        }
        else
        {
            $seller_user_id = $arh_res->seller_user_id;
            $buyer_user_id = $arh_res->buyer_user_id;
        }


        if( empty($seller_user_id ) ) $seller_user_id = $orig_order->seller_user_id;

        if( empty($buyer_user_id ) )
        {
            $buyer_user_id = $orig_order->buyer_user_id;

            if( $seller_user_id == $buyer_user_id )  $buyer_user_id = $orig_order->seller_user_id;
        }

        if( empty( $buyer_user_id ) || empty( $seller_user_id ) || $seller_user_id == $buyer_user_id )
        {
            //vred(['-2-',$arh_res]);
            return FALSE;
        }

        if( !isset( $arh_res->bonus ) || empty($arh_res->bonus) )
        {
            $arh_res->bonus = 5;
        }

//        if($ps[$spd->payment_system_id]->machine_name == 'wt')
        if($this->is_root_currency($ps[$spd->payment_system_id]->machine_name))
        {
            
            $user_in = $seller_user_id;
            $user_from = $buyer_user_id;
//            $summa = $order_details_arhiv[$spd->payment_system_id]->summa;
            $summa = $arh_res->seller_amount;
            $order_fee = $arh_res->seller_fee;
//            $transaction_bonus = $ps[$spd->payment_system_id]->transaction_bonus;
        }
//        elseif($ps[$bpd->payment_system_id]->machine_name == 'wt')
        elseif($this->is_root_currency($ps[$bpd->payment_system_id]->machine_name))
        {
            
            $user_in = $buyer_user_id;
            $user_from = $seller_user_id;
//            $summa = $order_details_arhiv[$bpd->payment_system_id]->summa;
            $summa = $arh_res->seller_amount;
            $order_fee = $arh_res->seller_fee;
//            $transaction_bonus = $ps[$bpd->payment_system_id]->transaction_bonus;
        }
        else
        {         
//            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, false, $orig_order->bonus);
//            $summ_fee = $this->calculate_fee_ps_id( $order_details_arhiv[$spd->payment_system_id]->summa, $spd->payment_system_id, false, $order_details_arhiv[$spd->payment_system_id]->summa);
//            $order_fee = $summ_fee[1];
            $order_fee = $arh_res->seller_fee;
            if( $arh_res->initiator == 0 )
            {
                $user_from = $seller_user_id;
                $user_in = $buyer_user_id;                
            }else{
                $user_from = $buyer_user_id;
                $user_in = $seller_user_id;
            }
        }

        $transaction_bonus = $arh_res->bonus;

//        echo 'seller<br>';
//        var_dump($arh_res);
//        echo '<br><br>buyer<br>';
//        var_dump($buyer_order);
//        echo '<br><br>original<br>';
//        var_dump($orig_order);
//        echo '<br><br>';
        if( empty($summa) )
        {
            if( $seller_amount > 0 ){
                $summa = $seller_amount;
                $order_fee = $seller_fee;
            }else{            
                #new block
                $order = $this->_get_user_orders_arhive_details_one( $arh_res );        
                if( empty( $order ) ) return FALSE;

                if( $order->wt_set != 0 )
                {
                    echo "Сумма равна нулю";
                    return;
                }

                $summa_sell = $order->sell_payment_data_un[$order->sell_system]->summa;
                $summa_buy = $order->buy_payment_data_un[$order->payed_system]->summa;

                $summa = $summa_sell;
                if( $summa_buy > $summa ) $summa = $summa_buy;
            }
            if( empty( $summa ) ) return FALSE;
        }

        $buyer_data = $seller_data = $data;

        // не понятно зачем прописывать, то что и так уже установленно
//        $seller_data['buyer_user_id'] = $orig_order->buyer_user_id;
//        $seller_data['payed_system'] = $bpd->payment_system_id;
//
//        $buyer_data['buyer_user_id'] = $orig_order->seller_user_id;
//        $buyer_data['payed_system'] = $bpd->payment_system_id;


        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
                ->limit(1)
//                ->where('seller_user_id', $user_id)
                ->update($this->table_orders_arhive, $seller_data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $buyer_data);

            $this->set_status_to_details( $arh_res->original_order_id, $data['status'] );

//            vre($this->db->last_query());

            $this->load->model('transactions_model','transactions');

            if(empty($user_in) || empty($user_from))
            {
                $this->db->trans_rollback();
                //vred(['-3-',$arh_res]);
                return FALSE;
            }


//            $create_wt_comission = $this->_allow_comission_by_cross_ps($arh_res->sell_system ,$arh_res->payed_system);
            #verybad











            if(
                $arh_res->wt_set != 0
                &&
                (
                $order->sell_payment_data_un[$order->sell_system]->payment_system_id != 115
                ||
                $order->buy_payment_data_un[$order->payed_system]->payment_system_id != 115
                )
            ) {


                // Если тут есть виза
                // Если wt_set == 0
                // Обязательно проверить что будет в wt_set при заявке visa - wt

                $t_ids = array();
                $t_ids[] = $this->transactions->addPay(
                    $user_in,
                    $summa,
//                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    Transactions_model::TYPE_SEND_MONEY_P2P,
//                        0,
                    $arh_res->original_order_id,
                    'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
//                    5,
                    $transaction_bonus,
//                        "Отправка средств пользователю №{$user_in} по заявке обмена  № $arh_res->buyer_order_id");
//                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->buyer_order_id, $user_in));
                    sprintf(_e('currency_exchange/model/p2p_transactio_1'), $arh_res->original_order_id, $user_from));
//                vre($this->db->last_query());
                $t_ids[] = $this->transactions->addPay(
                    $user_from,
                    $summa,
//                        (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    Transactions_model::TYPE_SEND_MONEY_P2P,
//                        $user_in,
                    $arh_res->original_order_id,
                    'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED),
//                    5,
                    $transaction_bonus,
//                        "Получение средств от пользователя № {$user_in} за P2P-перевод  № $confirm_id");
//                        "P2P-перевод по заявке №{$confirm_id} от пользователя № {$user_in}");
//                        sprintf(_e('currency_exchange/model/p2p_transactio_1'), $confirm_id, $user_in));
                    sprintf(_e('currency_exchange/model/p2p_transactio_1_add'), $arh_res->original_order_id, $user_in));
//                vre($this->db->last_query());

                //<editor-fold defaultstate="collapsed" desc="Расчет fee_user">
                if ($this->is_payment_system_wt($arh_res->payed_system) !== false) {
                    if ($arh_res->confirm_type == $arh_res->type) {
                        $fee_user = $arh_res->seller_user_id;
                    } else {
                        $fee_user = $arh_res->buyer_user_id;
                    }
                } elseif ($this->is_payment_system_wt($arh_res->sell_system) !== false) {
                    if ($arh_res->confirm_type == $arh_res->type) {
                        $fee_user = $arh_res->buyer_user_id;

                    } else {
                        $fee_user = $arh_res->seller_user_id;
                    }
                } else {
                    if ($arh_res->confirm_type == $arh_res->type) {
                        $fee_user = $arh_res->seller_user_id;
                    } else {
                        $fee_user = $arh_res->buyer_user_id;
                    }
                }
                //</editor-fold>


//            $create_wt_comission = $this->_allow_comission_by_cross_ps($arh_res->sell_system ,$arh_res->payed_system);


                $t_ids[] = $this->transactions->addPay(
//                    $arh_res->buyer_user_id,
                    $fee_user,
                    $order_fee,
//                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    Transactions_model::TYPE_EXPENSE_OUTFEE,
                    $arh_res->original_order_id,
                    'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
//                    5,
                    $transaction_bonus,
//                    "Коммисия за P2P-перевод по заявке № $arh_res->buyer_order_id");
                    sprintf(_e('currency_exchange/model/p2p_transactio_2'), $arh_res->original_order_id));


                $create_wt_comission = $this->_allow_comission_by_cross_ps($arh_res->sell_system, $arh_res->payed_system);


                //отчисление Колесу
                $p = $this->_receive_percentage_of_transaction($arh_res);
//            pred($p);
                $this->transactions->addPay(
                    54049637,
                    // round($order_fee/2, 2),
                    $p['percent'],
                    Transactions_model::TYPE_EXPENSE_P2P_FEE,
                    $arh_res->original_order_id,
                    'wt',
                    Base_model::TRANSACTION_STATUS_RECEIVED,
                    6,
                    sprintf(_e('Отчисления за P2P перевод № %s, сумма %s, курс: %s '),
                        $arh_res->original_order_id,
//                            $p['orig_summ'].' '._e('currency_id_'.$p['ps']->currency_id),
                        $p['orig_summ'] . ' ' . $p['currency'],
                        $p['rate']
                    ));

            }


            if( $delete_original === true)
            {
                /*
                $this->db
                    ->delete($this->table_orders, array('id' => $arh_res->original_order_id));

                $this->db
                    ->delete($this->table_order_details, array('order_id' => $arh_res->original_order_id));
                */
                $this->db
                    ->limit(1)
                    ->update($this->table_orders, array('status' => $status), array('id' => $arh_res->original_order_id));

                $this->db
                    ->limit(1)
                    ->update($this->table_order_details, array('order_status' => $status), array('order_id' => $arh_res->original_order_id));

                $this->set_status_to_details( $arh_res->original_order_id, $status );
            }


            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        $this->add_one_new_deal( $arh_res->original_order_id );

        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, "P2P::Проведена сделка {$arh_res->original_order_id}", 'private');

        return true;
    }

    // TODO проверить используется ли гдето это метод
    public function set_buyer_confirm($user_id, $confirm_id)
    {
        $arh_res = $this->db
                    ->where('id', $confirm_id)
                //    ->where('seller_user_id', $user_id)
                    ->get($this->table_orders_arhive)
                    ->row();

        if(empty($arh_res) || $arh_res->seller_confirmed != 1)
        {
            return FALSE;
        }

        $status = self::ORDER_STATUS_SUCCESS;
        $data = array(
            'buyer_confirmed' => 1,
            'status' => $status,
        );

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $confirm_id)
           //     ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

//            vre($this->db->last_query());

            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);

            $this->set_status_to_details( $arh_res->original_order_id, $data['status'] );

//            vre($this->db->last_query());

            $this->load->model('transactions_model','transactions');
            //TODO сделать от продавца деньги к покупателю
//            $user_id ===> $arh_res->user_id
//            $user_id ====+ $a
//            $arh_res->user_id ====+ $b
//            pre($arh_res);
            $user_debitor = $user_id;
            $user_creditor =  $arh_res->buyer_user_id;
            $t_own_id = $this->transactions->addPay(
                    $user_creditor,
                    $arh_res->seller_amount,
                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    $arh_res->original_order_id,
                    'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
                    5,
//                    "Отправка средств пользователю №{$user_creditor} по заявке обмена  № $confirm_id");
                    "P2P-перевод по заявке №{$arh_res->original_order_id} от пользователя №{$user_creditor}");

//            vre('buy');
//            vre($this->db->last_query());

            $t_id_user = $this->transactions->addPay(
                    $user_debitor,
                    $arh_res->seller_amount,
                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    $arh_res->original_order_id,
                    'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED),
                    5,
                    "Получение средств от пользователя №{$user_creditor} по заявке обмена № $arh_res->original_order_id");
//                    "P2P-перевод по заявке №{$user_creditor} от пользователя № {$arh_res->buyer_order_id}");

//            vre($this->db->last_query());
//            ++$arh_res->seller_fee;
//            $t_id_user = $this->transactions->addPay(
//                    $user_debitor,
//                    $arh_res->seller_fee,
//                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
//                    0,
//                    'wt',
//                    (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED),
//                    Base_model::TRANSACTION_BONUS_OFF,
//                    "Коммисия от пользователя №{$user_creditor} по заявки обмена № $arh_res->buyer_order_id");
            $t_own_id = $this->transactions->addPay(
                    $user_creditor,
                    $arh_res->seller_fee,
                    (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY),
                    $arh_res->original_order_id,
                   'wt',
                    (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED),
                    5,
                    "Коммисия за P2P-перевод по заявке № $arh_res->original_order_id");


            /*$this->db
                ->delete($this->table_orders, array('id' => $arh_res->original_order_id));
            $this->db
                ->delete($this->table_order_details, array('order_id' => $arh_res->original_order_id));
            */
            $this->db
                ->limit(1)
                ->update($this->table_orders, array( 'status' => $status ), array('id' => $arh_res->original_order_id));
            $this->db
                ->limit(20)
                ->update($this->table_order_details, array( 'order_status' => $status ), array('order_id' => $arh_res->original_order_id));

            $this->set_status_to_details( $arh_res->original_order_id, $status );

//            pred($arh_res);
//            vred($this->db->last_query());
            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }


    public function get_all_curency_problem_subject()
    {
        $res = $this->db->where('active', 1)
                ->order_by('order', 'ASC')
                ->get($this->table_problem_suject)
                ->result();

        return $res;
    }

    public function get_curency_problem_subject_by_id($id)
    {
        $res = $this->db->where('id', $id)
                ->get($this->table_problem_suject)
                ->row();

        return $res;
    }

    public function get_curency_problem_chat_by_id($id)
    {
        $res = $this->db->where('id', $id)
                ->get($this->table_problem_chat)
                ->row();

        return $res;
    }

    public function get_chat_by_orig_order_id($id)
    {

        $res = $this->db->where('order_id', $id)
                ->get($this->table_problem_chat)
                ->row();

        return $res;
    }



    public function add_to_problem_chat($exchange_id, $user_id, $subject_id, $subject, $problem_text, $image_place, $add_foto, $operator, $to, $suport_team_operator_id = 0, $orig_order_id = 0)
    {
        if(!empty($add_foto))
        {
            $add_foto = $image_place.$add_foto;
        }
        $show = 0;
        // $show = ($user_id == 0? 1: 0);
        $show_operator = ($operator == 0? 0: 1);
        $data = array(
            'id_subject' => $subject_id,
            'user_id' => $user_id,
            'text' => $problem_text,
            'other' => $subject,
            'date' => date('Y-m-d H:i:s'),
//            'suport_team_operator_id' =>'',
            'status' => 0,
//            'user_to_operator',
            'document' => $add_foto,
            'order_id' => $exchange_id,
            'operator' => $operator,
            'suport_team_operator_id' => $suport_team_operator_id,
            'to' => $to,
            'show' =>$show,
            'orig_order_id' => $orig_order_id,
            'show_operator' => $show_operator//сделать видным оператору, если сообщение ему
        );
//        pred($data);
//        $this->db->trans_start();
        $this->db->insert($this->table_problem_chat, $data);

        return $this->db->insert_id();
//        vred($this->db->last_query());
//        $this->db->trans_complete();
    }

    public function set_problem_status_by_id_for_order_arhiv($id, $user_id = false)
    {
        if(empty($user_id))
        {
            $user_id = $this->account->get_user_id();
        }
        $res = $this->db
                ->where('id', $id)
           //     ->where('seller_user_id', $user_id)
                ->get($this->table_orders_arhive)
                ->row();

        if(empty($res))
        {
            throw new Exception('Не верный пользователь.');
        }

        $this->db
                ->where('id', $id)
             //   ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, array('status' => self::ORDER_STATUS_HAVE_PROBLEM));

        $this->set_status_to_details( $res->original_order_id, self::ORDER_STATUS_HAVE_PROBLEM );
    }


    public function add_to_problem_chat_and_set_order_status($exchange_id, $user_id, $subject_id, $subject, $problem_text, $image_place, $add_foto, $operator)
    {
        $res = $this->get_order_and_parent_order_from_arhiv($exchange_id);

        if( empty( $res ) ) return FALSE;

        $orders = array();

        $kontr_agent_order = false;

        foreach($res as $val)
        {
            $orders[$val->id] = $val;
            if($val->id != $exchange_id)
            {
                $kontr_agent_order = $val;
            }
        }
        $temp_kontr_agent_order = $val;

        $original_order_id = $res[0]->original_order_id;

        if(empty($orders) ||
           $orders[$exchange_id]->seller_user_id != $user_id ||
           ($kontr_agent_order === false && $operator != 1)
        )
        {
            return false;
        }

//        $to  = $kontr_agent_order->seller_user_id;

        if(!empty($kontr_agent_order->seller_user_id))
        {
            $to  = $kontr_agent_order->seller_user_id;
        }
        else
        {
            $to  = $temp_kontr_agent_order->seller_user_id;
        }

        try
        {
            $this->db->trans_start();

            $id_message = $this->add_to_problem_chat($exchange_id, $user_id, $subject_id, $subject, $problem_text, $image_place, $add_foto, $operator, $to, 0, $original_order_id);
//            vred($this->db->last_query());
//
            //todo скорее всего не понадобится
//            $res = $this->set_problem_status_by_id_for_order_arhiv($exchange_id);
//            vred($this->db->last_query());
            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            pre($exc->getMessage());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return $id_message;
    }

    public function get_curency_problem_with_join($operator = FALSE)
    {
        $query = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                    ->from($this->table_problem_chat.' as pc')
                    ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left');

        if($operator ===  true)
        {
            $query->where('operator', 1);
            $query->where('suport_team_operator_id', 0);
        }

            $query->order_by('pc.status', 'ASC')
                    ->order_by('pc.date', 'DESC');


        $res = $query->get()->result();
//           pred($res);

        foreach($res as &$val)
        {
            if($val->s_machin_name == 'other')
            {
                $val->s_human_name = $val->s_human_name.' : '.$val->other;
            }
        }

        return $res;
    }   

    private function _is_prefund_amound_eq_order_sum( $user_id, $debit_amount_fee, $order ) {
        if( ( $user_id == $order->seller_user_id && $order->seller_amount + $order->seller_fee == $debit_amount_fee ) ||
            ( $user_id == $order->buyer_user_id && $order->buyer_amount_down * 1.01 == $debit_amount_fee) ) return TRUE;
        
        return FALSE;
    }
    
    
    public function prefund_delete($user_id, $delete_id, $check_status){
        if(empty($user_id) || empty($delete_id)) return false;
        // echo '-1';
        $this->load->model('card_model', 'card_model');
        $this->load->model('Card_prefunding_transactions_model', 'prefund');

        $order =  $this->get_original_order_by_id($delete_id);
        $a_order =  $this->get_arhiv_orders_by_parent_id($delete_id);

        if( empty($order) || $order->status != $check_status || $order->status > 20 ) return false;
        
        $sell_ps = unserialize( $order->sell_payment_data );
        $buy_ps = unserialize( $order->buy_payment_data );
    //echo "{$buy_ps[0]->payment_system_id} != 115 && {$sell_ps[0]->payment_system_id} != 115";
        if( ( empty($sell_ps) || empty($buy_ps) ) && $buy_ps[0]->payment_system_id != 115 && $sell_ps[0]->payment_system_id != 115   ) return FALSE;
        //if($sell_ps_id != 115) return false;

        $prefunds = $this->prefund->get_by_order_id($delete_id);
        $debit_amount_fee = $this->prefund->get_debit_amount_fee_by_value($delete_id);

        if(empty($debit_amount_fee) || count($prefunds) != 2) return false;
        // echo '-3';
//        vred($order);

        if( $order->buyer_user_id != $prefunds[0]->debet_uid && $order->seller_user_id != $prefunds[0]->debet_uid ) return FALSE;

        
        if( !$this->_is_prefund_amound_eq_order_sum( $prefunds[0]->debet_uid, $debit_amount_fee, $order ) ) return false;
           
        $card = $this->card_model->getUserCard($prefunds[0]->debet_cid, $prefunds[0]->debet_uid);
        
        if(empty($card)) return false;
        // echo '-5';

        $id_rand = time().'_PMCRON'.rand(1,1000);      
        $loadMoney_data = new stdClass();
        $loadMoney_data->id = $id_rand;
        $loadMoney_data->card_id = $card->id;
        $loadMoney_data->user_id = $prefunds[0]->debet_uid;
        $loadMoney_data->summa = $debit_amount_fee;
        $loadMoney_data->desc = 'P2P order id: '.$delete_id.'. Refund.';
        $response = $this->card_model->load($loadMoney_data, card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);

        if(empty($response)) return false;
        // echo '-6';

        $this->prefund->return_money($delete_id, $card->id, $user_id, $debit_amount_fee );

        return true;
    }

    public function is_have_archive_orders($original_order_id) {
        if(empty($original_order_id)) return false;

        $orders = $this->get_archive_orders_by_orig_order_id($original_order_id);

        if(empty($orders)) return false;

        return true;
    }

    public function get_all_data_to_prefund($original_order_id) {
        if(empty($original_order_id)) return false;


        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->load->model('card_model', 'card_model');


        $prefunds = $this->prefund->get_by_order_id($original_order_id);

//        $user_id_load_to_prefund = $prefunds[0]->debet_uid;
        $archive_orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($original_order_id);

        $payment_data = unserialize( $archive_orders[0]->sell_payment_data)[0];
        if($payment_data->payment_system_id != 115){
            $payment_data = unserialize( $archive_orders[0]->buy_payment_data)[0];
        }


        $card_id = $payment_data->payment_data;
        $user_id = $payment_data->user_id;
        $amount  = $prefunds[0]->amount;
        $card = $this->card_model->getUserCard($card_id, $user_id);
        if(empty($card) || empty($user_id) || empty($amount)) return false;


        $output = [
            'card_id'  => $card->id,
            'amount'    => $amount,
            'order_id' => $original_order_id,
            'user_id'  => $user_id
        ];

        return $output;
    }


    public function is_last_step_in_order($original_order_id) {
        $archive_orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($original_order_id);
        if(
            (
                !empty($archive_orders[0])
                && $archive_orders[0]->buyer_confirmed == 0
                && $archive_orders[0]->seller_confirmed == 0
                && $archive_orders[0]->confirmation_step == 1
            ) || (
                !empty($archive_orders[0])
                && $archive_orders[0]->buyer_confirmed == 0
                && $archive_orders[0]->seller_confirmed == 1
                && $archive_orders[0]->confirmation_step == 7
            )
        )
            return true;
        return false;
    }

    public function is_have_visa_in_order($original_id) {
        $order = $this->currency_exchange->get_original_order_by_id($original_id);
        $order_buy_data = $order->buy_payment_data_un;
        $order_sell_data = $order->sell_payment_data_un;
        $visa_ps_id = 115;

        // Имеется ли в заявке Виза
        if(!empty($order_buy_data[$visa_ps_id]) || !empty($order_sell_data[$visa_ps_id]) )
            return true;

        return false;
    }


    public function prefund_delete_from_admin($delete_id){
        if( empty($delete_id)) return false;

        $this->load->model('card_model', 'card_model');
        $this->load->model('Card_prefunding_transactions_model', 'prefund');

        $order =  $this->get_original_order_by_id($delete_id);
        $allow_status = [
            self::ORDER_STATUS_PROCESSING_SB, //на проверке у СБ
            self::ORDER_STATUS_PROCESSING, //на проверке у опервтора
            self::ORDER_STATUS_SET_OUT,
            self:: ORDER_STATUS_CONFIRMATION // active
        ];


        if( empty($order) ||  !in_array($order->status, $allow_status) )
        return false;

        $sell_ps = unserialize( $order->sell_payment_data );
        $buy_ps = unserialize( $order->buy_payment_data );

        if( ( empty($sell_ps) || empty($buy_ps) ) && $buy_ps[0]->payment_system_id != 115 && $sell_ps[0]->payment_system_id != 115   ) return FALSE;


        $prefunds = $this->prefund->get_by_order_id($delete_id);
        $debit_amount_fee = $this->prefund->get_debit_amount_fee_by_value($delete_id);

        if(empty($debit_amount_fee) || count($prefunds) != 2) return false;


        if( $order->buyer_user_id != $prefunds[0]->debet_uid && $order->seller_user_id != $prefunds[0]->debet_uid ) return FALSE;


        #remove
//        if( !$this->_is_prefund_amound_eq_order_sum( $prefunds[0]->debet_uid, $debit_amount_fee, $order ) ) return false;

        $card = $this->card_model->getUserCard($prefunds[0]->debet_cid, $prefunds[0]->debet_uid);

        if(empty($card)) return false;
//        return false;

        $id_rand = time().'_PMCRON'.rand(1,1000);
        $loadMoney_data = new stdClass();
        $loadMoney_data->id = $id_rand;
        $loadMoney_data->card_id = $card->id;
        $loadMoney_data->user_id = $prefunds[0]->debet_uid;
        $loadMoney_data->summa = $debit_amount_fee;
        $loadMoney_data->desc = 'P2P order id: '.$delete_id.'. Refund.';
        $response = $this->card_model->load($loadMoney_data, card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);

        if(empty($response)) return false;


        $this->prefund->return_money($delete_id, $card->id, $prefunds[0]->debet_uid, $debit_amount_fee, $from_admin = true );

        return true;
    }


    public function delete_order_without_status($user_id, $delete_id)
    {
        if(empty($user_id) || empty($delete_id))
        {
            return false;
        }

        //можно удалить заявки в статусе выставлена
        $res = $this->db
                ->where('id' , $delete_id)
            //    ->where('seller_user_id' , $user_id)
                // ->where('status' , self::ORDER_STATUS_CONFIRMATION )
                ->get($this->table_orders)
                ->row();

        if(empty($res))
        {
            return false;
        }

        $res_edit = clone $res;
        unset($res_edit->id);

        $res_details = $this->db
                    ->where('order_id', $res->id)
                    ->get($this->table_order_details)
                    ->result();

        $status = self::ORDER_STATUS_REMOVED;
        $res_edit->status = $status;
        $res_edit->original_order_id = $res->id;
        $res_edit->order_details_arhiv = serialize($res_details);

        try
        {
            $this->db->trans_start();

            $this->db->insert($this->table_orders_arhive, $res_edit);

            /*$this->db->delete($this->table_orders, array('id' => $delete_id));
            $this->db->delete($this->table_order_details, array('order_id' => $delete_id));*/

            $this->db->limit(3)->update($this->table_orders, array('status' => $status), array('id' => $delete_id));
            $this->set_status_to_details( $delete_id, $status );
                
            $this->db->trans_complete();


            $this->send_mail($delete_id, 'delete', $user_id);
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }


    public function delete_order($user_id, $delete_id)
    {
        if(empty($user_id) || empty($delete_id))
        {
            return false;
        }

        //можно удалить заявки в статусе выставлена
        $res = $this->db
                ->where('id' , $delete_id)
            //    ->where('seller_user_id' , $user_id)
                ->where('status' , self::ORDER_STATUS_SET_OUT )
                ->get($this->table_orders)
                ->row();

        if(empty($res))
        {
            return false;
        }

        $res_edit = clone $res;
        unset($res_edit->id);

        $res_details = $this->db
                    ->where('order_id', $res->id)
                    ->get($this->table_order_details)
                    ->result();

        $status = self::ORDER_STATUS_REMOVED;
        $res_edit->status = $status;
        $res_edit->original_order_id = $res->id;
        $res_edit->order_details_arhiv = serialize($res_details);

        try
        {
            $this->db->trans_start();

            $this->db->insert($this->table_orders_arhive, $res_edit);

            /*$this->db->delete($this->table_orders, array('id' => $delete_id));
            $this->db->delete($this->table_order_details, array('order_id' => $delete_id));*/

            $this->db->limit(1)->update($this->table_orders, array('status' => $status), array('id' => $delete_id));
            $this->set_status_to_details( $delete_id, $status );
                
            $this->db->trans_complete();


            $this->send_mail($delete_id, 'delete', $user_id);
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function order_processing_cancell($user_id, $order_id)
    {
        if(empty($user_id) || empty($order_id))
        {
            return false;
        }

        #проверка на то, что заявка не имеет связанной заявки
        $res = $this->db
                ->where('id' , $order_id)
             //   ->where('seller_user_id' , $user_id)
                ->where_in('status' , array( self::ORDER_STATUS_PROCESSING, self::ORDER_STATUS_PROCESSING_SB ))
                ->where('wt_set' , 1)
                ->where('buyer_user_id' , 0)
                ->get($this->table_orders)
                ->row()
                    ;

        if(empty($res))
        {
            return false;
        }

        $res_edit = clone $res;
        unset($res_edit->id);

        $res_details = $this->db
                    ->where('order_id', $res->id)
                    ->get($this->table_order_details)
                    ->result();

        $status = self::ORDER_STATUS_CANCELED;
        $res_edit->status = $status;
        $res_edit->original_order_id = $order_id;
        $res_edit->order_details_arhiv = serialize($res_details);

        try
        {
            $this->db->trans_start();

            $this->db->insert($this->table_orders_arhive, $res_edit);

            /*$this->db->delete($this->table_orders, array('id' => $order_id));
            $this->db->delete($this->table_order_details, array('order_id' => $order_id));*/

            $this->db->limit(1)->update($this->table_orders, array('status' => $status), array('id' => $order_id));
            $this->set_status_to_details( $order_id, $status );

            $this->db->trans_complete();
//            $this->send_mail($order_id, 'delete', $user_id);
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function get_problem_chat_without_excess($message, $subjects = false)
    {
        if($subjects === false)
        {
            $subjects = $this->get_all_curency_problem_subject();

            $problems_arr = array();
//            pred($subjects);
            foreach($subjects as $val)
            {
                $problems_arr[$val->id] = $val;
            }
        }
        else
        {
            $problems_arr = $subjects;
        }

        $last_message = new stdClass();

        if(isset($problems_arr[$message->id_subject]) && $problems_arr[$message->id_subject]->machin_name == 'other')
        {
            $last_message->subject = $problems_arr[$message->id_subject]->human_name.' : '.$message->other;
        }
        elseif(isset($problems_arr[$message->id_subject]))
        {
            $last_message->subject = $problems_arr[$message->id_subject]->human_name;
        }

        if($message->user_id)
        {
            $this->load->model('users_model', 'users_model');
            $user_data = $this->users_model->getUserData($message->user_id);
            $last_message->user = $user_data->sername.' '.$user_data->name ;
        }
        else
        {
//            $last_message->user = 'Оператор № '.$message->suport_team_operator_id;
            $last_message->user = sprintf(_e('currency_exchange/model/chat_operator'), $message->suport_team_operator_id);
        }


        $last_message->text = $message->text;
        $last_message->date = date('H:i d.m.y', strtotime($message->date));
        $last_message->user_id = $message->user_id;
//        [name] => Александр
//    [sername] => Копачёв

        $last_message->id = $message->id;
        $last_message->operator = $message->operator;
        $last_message->show = $message->show;
        $last_message->file = $message->document;
        $last_message->is_group_message = !empty($message->group_operator_message);

        return $last_message;
    }



    public function get_all_user_problem_chat($order_id, $user_id = false, $set_show = true)
    {
        if(empty($order_id))
        {
            return false;
        }

        if($user_id === FALSE)
        {
            $user_id = $this->account->get_user_id();
        }

        $orders = $this->get_order_and_parent_order_from_arhiv($order_id, $user_id);

        if($orders == FALSE)
        {
            return false;
        }

        $orders_ids = array();

        foreach($orders as $val)
        {
            $orders_ids[] = $val->id;
        }

//        $res = $this->db
//                ->where('user_id' , $user_id)
//                ->where('order_id' , $order_id)
//                ->get($this->table_problem_chat)
//                ->result();
        $res = $this->db
                ->where_in('order_id' , $orders_ids)
                ->get($this->table_problem_chat)
                ->result();

        $ids = array();
        $res_arr = array();

        foreach($res as $val)
        {
            if($val->operator == 1 &&  $val->user_id != $user_id && $val->user_id != 0)
            {
                continue;
            }

            if($val->user_id == 0 && $val->to != $user_id)
            {
                continue;
            }

            $ids[] = $val->id;
            $res_arr[] = $this->get_problem_chat_without_excess($val);
        }

        if(!empty($ids) && $set_show === true)
        {
            $this->db
                ->where_in('id', $ids)
                ->where('to', $user_id)
                ->limit(20)
                ->update($this->table_problem_chat, array('show' => 1));
        }

        return $res_arr;
    }



    public function get_order_and_parent_order_from_arhiv($order_id, $user_id = FALSE)
    {
        if(empty($order_id))
        {
            return false;
        }

        if($user_id === FALSE)
        {
            $user_id = $this->account->get_user_id();
        }

        $res = $this->db
              //  ->where('seller_user_id', $user_id)
                ->where('id', $order_id)
                ->or_where('buyer_order_id', $order_id)
                ->get($this->table_orders_arhive)
                ->result()
                ;

        return $res;
    }



    public function user_cancel_order($id)
    {
        if(empty($id))
        {
            return false;
        }

        $user_id = $this->account->get_user_id();


        $res = $this->db
                ->where('id', $id)
           //     ->where('seller_user_id', $user_id)
//                ->where('status', self::ORDER_STATUS_CONFIRMATION)
//                ->where('buyer_confirmed', 0)
//                ->where('seller_confirmed', 0)
//                ->where('seller_document_image_path', '')
//                ->where("(buyer_document_image_path = 'wt' OR buyer_document_image_path = '')")
                ->get($this->table_orders_arhive)
                ->row();



        //TODO костыль
//        if(empty($res))
//        {
//            $res = $this->db
//                    ->where('id', $id)
//                    ->where('seller_user_id', $user_id)
//                    ->where('status', self::ORDER_STATUS_CONFIRMATION)
//                    ->where('buyer_confirmed', 1)
//                    ->where('seller_confirmed', 0)
//                    ->where('seller_document_image_path', 'wt')
//                    ->where("buyer_document_image_path = ''")
//                    ->get($this->table_orders_arhive)
//                    ->row();
//        }

//        pre($res2);

        if(empty($res) || $res->status != self::ORDER_STATUS_CONFIRMATION || $res->seller_confirmed != 0)
        {
            return false;
        }



        try
        {
            $this->db->trans_start();

            $this->db
                ->where_in('id', array($id, $res->buyer_order_id))
                ->limit(2)
                ->update($this->table_orders_arhive, array('status' => self::ORDER_STATUS_CANCELED));
//            vre($this->db->last_query());


//            $order_status_abs = self::ORDER_STATUS_SET_OUT;
            $order_status_abs = self::ORDER_STATUS_CANCELED;

            $this->db
                ->where('id', $res->original_order_id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $order_status_abs , 'buyer_user_id' => 0));

            $this->set_status_to_details( $res->original_order_id, $order_status_abs );

//            vred($this->db->last_query());
            $this->send_mail_by_arhiv_order_id($id, 'order_contragent_reject', $user_id, true);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }


    /**
     * orig
     *
     * @param type $orig_order_id
     * @param type $order_status
     * @param type $order_arhiv_status
     * @return boolean
     */
    public function user_cancel_order_operator_all($orig_order_id, $orig_order_status =  self::ORDER_STATUS_SET_OUT,
            $arch_order_ids = array(), $order_arhiv_status = self::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR)
    {
        if(empty($orig_order_id))
        {
            return false;
        }

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $orig_order_id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $orig_order_status, 'buyer_user_id' => 0));

            $this->set_status_to_details( $orig_order_id, $orig_order_status );

            if(!empty($arch_order_ids) && is_array( $arch_order_ids ) )
            {
                $this->db
                    ->where_in('id', $arch_order_ids)
                    ->limit(2)
                    ->update($this->table_orders_arhive, array('status' => $order_arhiv_status));
            }

//            vre($this->db->last_query());

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }
    public function user_cancel_order_operator($id, $order_status =  self::ORDER_STATUS_SET_OUT, $order_arhiv_status = self::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR)
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                ->where('id', $id)
                ->get($this->table_orders)
                ->row();

        if(empty($res))
        {
            return false;
        }

        $res_arhiv = $this->db
                ->where('original_order_id', $id)
//                ->where('status', self::ORDER_STATUS_CONFIRMATION)
                ->where('status', $res->status)
                ->where('seller_user_id', $res->seller_user_id)
                ->get($this->table_orders_arhive)
                ->row();

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $res->id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $order_status, 'buyer_user_id' => 0));

            $this->set_status_to_details( $res->id, $order_status );

            if(!empty($res_arhiv))
            {
                $this->db
                    ->where_in('id', [$res_arhiv->id, $res_arhiv->buyer_order_id])
                    ->limit(2)
                    ->update($this->table_orders_arhive, array('status' => $order_arhiv_status));
            }

//            vre($this->db->last_query());

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function get_user_confirm_data($id)
    {
        if(empty($id))
        {
            return false;
        }

        $user_id = $this->account->get_user_id();


        $res = $this->get_order_arhiv_by_id($id);

        $res = $this->_get_second_order_data_one($res);
        $res = $this->_get_user_orders_arhive_details_one($res);

        if(empty($res) || $res->seller_user_id != $user_id)
        {
            return false;
        }

//        pred($res);

        $select_ps = $res->sell_payment_data_un[$res->sell_system];

        $method = new stdClass();
        $method->contragent->machine_name = $select_ps->machine_name;
        $method->contragent->public_path_to_icon = $select_ps->public_path_to_icon;
//        $method->contragent->name = _e('currency_name_'.$select_ps->machine_name);
        $method->contragent->name = Currency_exchange_model::get_ps($select_ps->machine_name)->humen_name;
        $method->contragent->accaunt = $select_ps->payment_data;
//        $method->contragent->currency = _e('currency_id_'.$select_ps->currency_id);
        $method->contragent->currency =Currency_exchange_model::show_payment_system_code(['currency_id' => $select_ps->currency_id]);

//        $method->machine_name = $ps[$bpd->payment_system_id]->machine_name;
//        $method->public_path_to_icon = $ps[$bpd->payment_system_id]->public_path_to_icon;
//        $method->name = _e('currency_name_'.$ps[$bpd->payment_system_id]->machine_name);
//        pred($res);
        $this->load->model('users_model');
        $user_data = $this->users_model->getUserData($res->second_order->seller_user_id);

        $res_arr = array(
            'id' => $id,
            'fio' => $user_data->sername.' '.$user_data->name.' '.$user_data->patronymic,
            'method' => $method,
            'summ' => $res->buyer_amount_down
        );

        return $res_arr;
    }



    public function get_last_user_confirm_data($id)
    {
        if(empty($id))
        {
            return false;
        }

        $user_id = $this->account->get_user_id();

        $res = $this->db
                ->where('id', $id)
           //     ->where('seller_user_id', $user_id)
                ->get($this->table_orders_arhive)
                ->row()
                ;

        if($user_id == $res->buyer_user_id)
        {
            $res_dop = $this->db
                ->where('id', $res->buyer_order_id)
                ->get($this->table_orders_arhive)
                ->row()
                ;
            $res->buyer_user_id = $res_dop->seller_user_id;
        }

        if(empty($res))
        {
            return false;
        }

        $this->load->model('users_model');
//        vred($this->users_model->getUser($res->buyer_user_id));
        $user_data = $this->users_model->getUserData($res->buyer_user_id);
//        pred($res);
        $order_details_arhiv = unserialize($res->order_details_arhiv);

        $order_details_arhiv = array_set_value_in_key($order_details_arhiv, 'payment_system');

        $sell_payment_data = unserialize($res->sell_payment_data);

        foreach($sell_payment_data as $spd_val)
        {
            if($spd_val->payment_system_id == $res->sell_system)
            {
                $spd = $spd_val;
                break;
            }
        }

        $buy_payment_data = unserialize($res->buy_payment_data);

        foreach($buy_payment_data as $bpd_val)
        {
            if($bpd_val->payment_system_id == $res->payed_system)
            {
                $bpd = $bpd_val;
                break;
            }
        }


//        $contragent_pay_dat = $this->get_paymen_data_kontragent_by_order_id_and_payment_id($id, $bpd->payment_system_id);

        $all_ps = $this->get_all_payment_systems();

        $ps = array();

        foreach ($all_ps as $val)
        {
           $ps[$val->id] = $val;
        }

        if(!isset($ps[$bpd->payment_system_id]))
        {
            $ps[$bpd->payment_system_id] = self::get_ps($bpd->payment_system_id);
        }

        if(!isset($all_ps[$spd->payment_system_id]))
        {
            $ps[$spd->payment_system_id] = self::get_ps($spd->payment_system_id);
        }

        $pay_sys_b = $order_details_arhiv[$bpd->payment_system_id];
//        $pay_sys_b->currency_id = $pay_sys_b->payment_system;
        $pay_sys_b->currency_id = self::get_ps($bpd->payment_system_id)->currency_id;

        $pay_sys_s = $order_details_arhiv[$spd->payment_system_id];
//        $pay_sys_s->currency_id = $pay_sys_s->payment_system;
        $pay_sys_s->currency_id = self::get_ps($spd->payment_system_id)->currency_id;

        $method = new stdClass();
        $method->contragent = new stdClass();

        $method->contragent->machine_name = $ps[$spd->payment_system_id]->machine_name;
        $method->contragent->public_path_to_icon = $ps[$spd->payment_system_id]->public_path_to_icon;
//        $method->contragent->name = _e('currency_name_'.$ps[$spd->payment_system_id]->machine_name);
        $method->contragent->name = Currency_exchange_model::get_ps($spd->payment_system_id)->humen_name;
//        $method->contragent->accaunt = $spd->payment_data;
        $method->contragent->accaunt = self::get_ps_data_for_table_short($ps[$spd->payment_system_id], $spd->payment_data, ['order'=>$res, 'curent_user'=>$user_id]);
//        $method->contragent->currency = _e('currency_id_'.$ps[$spd->payment_system_id]->currency_id);
        $method->contragent->currency = Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys_s]);
        $method->contragent->summ = $pay_sys_s->summa;

        $method->machine_name = $ps[$bpd->payment_system_id]->machine_name;
        $method->public_path_to_icon = $ps[$bpd->payment_system_id]->public_path_to_icon;
//        $method->name = _e('currency_name_'.$ps[$bpd->payment_system_id]->machine_name);
        $method->name = Currency_exchange_model::get_ps($bpd->payment_system_id)->humen_name;
//        $method->currency = _e('currency_id_'.$ps[$bpd->payment_system_id]->currency_id);
        $method->currency = Currency_exchange_model::show_payment_system_code(['pay_sys' => $pay_sys_b]);
        $method->summ = $pay_sys_b->summa;
//        pred($method);
        $res_arr = array(
            'id' => $id,
            'fio' => $user_data->sername.' '.$user_data->name.' '.$user_data->patronymic,
            'method' => $method,
            'summ' => $res->buyer_amount_down
        );

        return $res_arr;
    }

    public function set_payed_system_order_archive($original_order_id, $ps_id) {
        if(empty($original_order_id) || empty($ps_id)) return false;


        $res = $this->db
            ->where('original_order_id', $original_order_id)
            ->update($this->table_orders_arhive, ['payed_system' => $ps_id]);

        return $res;
               
    } 

    public function set_payment_system_order_arhive_buy($arhiv_order_id, $select_payment_systems)
    {
        $user_id = $this->account->get_user_id();

        $arh_res = $this->db
               ->where('id', $arhiv_order_id)
//               ->where('type', self::ORDER_TYPE_BUY_WT)
        //       ->where('seller_user_id', $user_id)
               ->get($this->table_orders_arhive)
               ->row();
//        vred($arh_res);
        if(empty($arh_res))
        {
            return FALSE;
        }

        $data = array(
              'seller_confirmation_date' => date('Y-m-d H:i:s'),
//            'seller_confirmed' => 1,
//            'buyer_user_id' =>  $user_id,
//            'payed_system' => $select_payment_systems,
                );

        $sell_payment_data = unserialize($arh_res->sell_payment_data);
//        pre($sell_payment_data);

        foreach($sell_payment_data as $sp_val)
        {
            if($sp_val->payment_system_id == $select_payment_systems)
            {
                $select_user_payment_system = $this->get_user_paymant_data_by_payment_system_id($sp_val->payment_system_id, $user_id);
                break;
            }
        }

        $is_wt = $this->is_payment_system_wt($select_payment_systems);

        if($is_wt === false && (!isset($select_user_payment_system->payment_data) || empty($select_user_payment_system->payment_data)))
        {
            return false;
        }

        if($is_wt !== false)
        {
            $pay_sys = array($is_wt->machine_name => $is_wt->id);
            list($select_user_payment_system) = $this->_get_user_paymant_data_for_orders_by_buy_type($user_id, $pay_sys);
        }

        $data['sell_payment_data'] = serialize(array($select_user_payment_system));
//        $data['sell_system'] = $select_user_payment_system->id;
        $data['sell_system'] = $select_user_payment_system->payment_system_id;

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $arhiv_order_id)
             //   ->where('seller_user_id', $user_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);
//            vre($this->db->last_query());
            $this->db
                ->where('id', $arh_res->buyer_order_id)
                ->limit(1)
                ->update($this->table_orders_arhive, $data);
//            vred($this->db->last_query());

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }

        return true;
    }



    public function get_order_arhiv_by_id($id)
    {
        $res = $this->db
                ->where('id', $id)
                ->get($this->table_orders_arhive)
                ->row();

        return $res;
    }



    public function get_orders_archive_old_by_id($id)
    {
        if( empty( $id ) )
            return FALSE;

        $res = $this->db
                ->where('id', $id)
                ->get($this->table_orders_arhive)
                ->row();

        return $res;
    }
    public function get_order_by_id($id)
    {
        return $this->get_original_order_by_id( $id );
    }

    public function set_buyer_user_id($original_order_id, $value) {
        if(empty($original_order_id) || empty($value) || !is_numeric($value)) 
            return false;

        $q = $this->db
            ->where('original_order_id', $original_order_id)
            ->limit(2)
            ->update($this->table_orders_arhive, ['buyer_user_id' => $value]);

        if(empty($q))
            return false;
        return true;

    }

    public function get_archive_order_by_id($id, $full_data = TRUE, $where = null, $order = 'ASC')
    {
        if( empty( $id ) )
            return FALSE;

        if( !empty( $where ) )
            $this->db->where( $where );

        $res = $this->db
                ->order_by('id',$order)
            ->where('original_order_id', $id)
            ->get($this->table_orders_arhive)
            ->row();

        if(empty($res))
            return FALSE;

//        $res_orders = array();
        $ids = array();

        $ids[] = $res->id;

        $query = $this->db->where_in('order_id', $ids);
        $res_details = $query->get($this->table_order_details)->result();

        foreach ($res_details as $val)
        {
            $res->payment_systems[$val->id] = $val;
        }
        if( $full_data === TRUE )
        {
            $res = $this->set_fee_to_order($res);
            $res = $this->set_machin_name_to_payment_systems($res);
            $res = $this->set_dop_data_to_unserializ_payment_system($res);
        }
        return $res;
    }

    public function get_archive_orders_by_orig_order_id($orig_order_id, $sort_order = 'ASC' )
    {
        if( empty( $orig_order_id ) )
            return FALSE;

        $res = $this->db
            ->where('original_order_id', $orig_order_id)
            ->order_by('id',$sort_order)
            ->get($this->table_orders_arhive)
            ->result();

        if(empty($res))
            return FALSE;


        return $res;
    }

    public function get_original_order_by_status($user_id, $status) {
        if( empty( $user_id ) || empty($status))
            return FALSE;

        if(is_array($status)) {
            $this->db->where_in('status', $status);
        } elseif( !empty( $status ) ) {
            $this->where('status', $status);
        }

        $res = $this->db
                ->where('seller_user_id', $user_id)
                ->get($this->table_orders)
                ->result();
                
        return $res;
    }

    public function get_original_order_by_orig_id_by_confirmation_step($orig_id, $step)
    {
        if (empty($orig_id) || empty($step))
            return FALSE;

        $res = $this->db
            ->where('original_order_id', $orig_id)
            ->where('confirmation_step', $step)
            ->get($this->table_orders_arhive)
            ->row();

        if (empty($res)) {
            return FALSE;
        }
        return $res;
    }

    public function get_original_order_by_id($id)
    {
        if( empty( $id ) )
            return FALSE;

        $res = $this->db
                ->where('id', $id)
                ->get($this->table_orders)
                ->row();

        if(empty($res))
        {
            return FALSE;
        }

//        $res_orders = array();
        $ids = array();

        $ids[] = $res->id;

        $query = $this->db->where_in('order_id', $ids);
        $res_details = $query->get($this->table_order_details)->result();

        foreach ($res_details as $val)
        {
            $res->payment_systems[$val->id] = $val;
        }

        $res = $this->set_fee_to_order($res);
        $res = $this->set_machin_name_to_payment_systems($res);
        $res = $this->set_dop_data_to_unserializ_payment_system($res);

        return $res;
    }

    public function get_operator_notes($order)
    {
        if( !is_object( $order ) )
            return FALSE;

        if(isset($order->original_order_id))
        {
            $this->db->where('is_arhiv', 1);
        }

        $res = $this->db
                ->where( 'order_id', $order->id )
                ->order_by('id','DESC')
                ->get( $this->table_operator_notes )
                ->result();

        if(empty($res))
            return $order;

        $order->last_note = $res[0];
        $order->history_notes = $res;


        return $order;
    }

    /**
     * Возвращает причины отклонения заявки
     * @param type $order
     * @return boolean
     */
    public function get_operator_reject_notes($order)
    {
        if( !is_object( $order ) )
            return FALSE;

        if(isset($order->original_order_id))
        {
            $this->db->where('is_arhiv', 1);
        }

        $res = $this->db
                ->where( 'order_id', $order->id )
                ->order_by('id','DESC')
                ->get( $this->table_operator_reject_notes )
                ->result();

        if(empty($res))
            return $order;

        $order->last_reject_note = $res[0];
        $order->history_reject_notes = $res;


        return $order;
    }


    public function set_fee_to_order($order)
    {
        $payment_systems = $this->get_all_payment_systems();
        $pay_sys = array_set_value_in_key($payment_systems);

        $order->get_percent = 0;

        foreach($order->payment_systems as $ps)
        {
//            if($pay_sys[$ps->payment_system]->machine_name == 'wt')
            if($ps->payment_system == 115)
            {                
                $order->get_fee = $order->seller_fee;
            }elseif($this->is_root_currency($pay_sys[$ps->payment_system]->machine_name) && !isset( $order->get_fee ))
            {
                $order->get_fee = $order->seller_fee;
//                $order->get_fee_currency_id = $pay_sys[$ps->payment_system]->currency_id;
                $order->get_fee_currency_id = empty($pay_sys[$ps->payment_system]->choice_currecy)?$pay_sys[$ps->payment_system]->currency_id:$pay_sys[$ps->payment_system]->choice_currecy;
                continue;
            }
            elseif($order->get_percent < $pay_sys[$ps->payment_system]->fee_percentage)
            {
                $order->get_percent = $pay_sys[$ps->payment_system]->fee_percentage;
            }
        }

        return $order;
    }



    public function getAll($cols, $table, $where) {
//        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

//        if(isset($_SESSION["payment_count"]) && isset($_SESSION["payment_count_time"]) && $_SESSION["payment_count_time"] > (time() - 24*60*60) && !$search && !$filter)
//            $r["count"] = $_SESSION["payment_count"];
//        else {
//            if ($search)
//                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "t.");

            $r["count"] = $this->db->select("COUNT(*) as count")
//                            ->where("ul.id_user IS NULL")
                            ->where($where)
                            ->get("$table t")
                            ->row("count");
//            $_SESSION["payment_count"] = $r["count"];
//            if ($search || $filter) $_SESSION["payment_count_time"] = 0;
//            else $_SESSION["payment_count_time"] = time();
//        }
//            vred($this->db->last_query());

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "t.");
//        if ($search)
//            $this->_createSearch($search, $cols, $table);
        $columns = array_keys($cols);

        $r["rows"] = $this->db
                ->select(implode(',', $columns))
                ->limit($per_page, $offset)
                ->where($where)
                ->get("$table t")
                ->result(); //echo $this->db->last_query();die;

//        if ("users" == $table)
//            $r["rows"] = $this->code->db_decode($r["rows"]);
//        pred($r);
        return json_encode($r);
    }



    private function _createFilter($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;

            if ("fio" == $key) {
//                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            }
            else $this->db->where("$t$key LIKE", "%$value%");
        }
    }



    private function _createOrder($order) {
        foreach ($order as $key => $value)
            $this->db->order_by($key, $value);
    }



    private function _createSearch($search, $cols) {
        $search = explode(" ", $search);
        foreach ($search as $word) {
//            $wordEncript = $this->code->code($word);
            $wordEncript = $word;
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
        }
    }




    public function set_status_set_out($id)
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                    ->where('id', $id)
                    ->get($this->table_orders)
                    ->row();

        if(empty($res))
        {
            return false;
        }

        $order_status  = self::ORDER_STATUS_SET_OUT;

        $this->db
                ->where('id', $id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $order_status));

        $this->set_status_to_details( $id, $order_status );

        return TRUE;
    }



    public function set_status_archive_orders( $id, $status )
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                    ->where('id', $id)
                    ->get($this->table_orders_arhive)
                    ->row();
        if(empty($res)) return false;
        $this->set_status_to_details( $res->original_order_id, $status );

        $res = $this->db
                ->where('id', $id)
                ->limit(1)
                ->update($this->table_orders_arhive, array('status' => $status));


        //$this->set_status_to_details( $id, $status );

        return $res;
    }


    public function set_status_operator_canceled($id)
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                    ->where('id', $id)
                    ->get($this->table_orders)
                    ->row();

        if(empty($res))
        {
            return false;
        }

        $order_status = self::ORDER_STATUS_OPERATOR_CANCELED;

        ###############################
        ###############1###############
        $res_details = $this->db
                    ->where('order_id', $res->id)
                    ->get($this->table_order_details)
                    ->result();

        $res_edit = clone $res;

        unset($res_edit->id);

        $res_edit->status = $order_status;
        $res_edit->original_order_id = $res->id;
        $res_edit->order_details_arhiv = serialize($res_details);

        try
        {
            $this->db->trans_start();

            $this->db->insert($this->table_orders_arhive, $res_edit);

        ###############1###############
        ###############################


        $this->db
                ->where('id', $id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $order_status));

        $this->set_status_to_details( $id, $order_status );

        ###############################
        ###############2###############

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
//            pre($this->db->last_query());
//            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return false;
        }
        ###############2###############
        ###############################

        return TRUE;
    }


    public function clean_buyer_data($id)
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                    ->where('id', $id)
                    ->get($this->table_orders)
                    ->row();

        if(empty($res))
        {
            return false;
        }

        $this->db
                ->where('id', $id)
                ->limit(1)
                ->update($this->table_orders, array('buyer_user_id' => 0));

        return TRUE;
    }

    /**
     * Добавляет причину отклонения
     * @param array $data
     * @return boolean
     */
    public function add_operator_reject_note(array $data)
    {

        if (empty($data['operator_name'])){
            $data['operator_name'] = $this->admin_info->login;
        }


        $this->db->insert($this->table_operator_reject_notes, $data);

        if($this->db->insert_id())
        {
            return true;
        }

        return FALSE;

    }

    //
    public function add_operator_note(array $data)
    {
        if (empty($data['operator_name'])){
            $data['operator_name'] = $this->admin_info->login;
        }



        if( empty($data['text']) ){
            $res = $this->db->where( 'order_id', $data['order_id'] )->order_by('id','DESC')->limit(1)->get( $this->table_operator_notes )->row();
            if ( empty($res) || (  !empty($res) && empty($res->text) ) )
                return TRUE;
        }

        $this->db->insert($this->table_operator_notes, $data);

        if($this->db->insert_id())
        {
            return true;
        }

        return FALSE;
    }

    /**
     * Создаём псевдо ответ с данными из таблици currency_exchange_user_paymant_data
     * @param int $user_id
     * @param array $pay_sys
     * @return array
     */
    private function _get_user_paymant_data_for_orders_by_buy_type($user_id, $pay_sys)
    {
        $res = array();
        $data = new stdClass();

        $data->id = 0;
        $data->user_id = $user_id;
        $data->payment_data = '';

        foreach($pay_sys as $val)
        {
            $data->payment_system_id = $val;
            $res[] = clone $data;
        }

        return $res ;
    }


    public function get_all_user_chats($user_id = FALSE)
    {
        if($user_id === false)
        {
            $user_id = $this->account->get_user_id();
        }

        $res = $this->db
                ->where('show',0)
                ->where('to', $user_id)
				->where('date>', '2016-01-20 16:29:40')
                // ->where('operator IS NULL')
                // ->or_where('operator', 1)
                // 0 = сообщения пользователей, 1 = оператора к пользователям
                // ->where('operator IS NULL')
//                ->where_in('order_id', $orders_arhiv_ids)
                ->get($this->table_problem_chat)
                ->result();
//        vre($this->db->last_query());
//        pred($res);
        $count_chat = count($res);

        $oreders_conf = $this->db
                ->where('status', self::ORDER_STATUS_CONFIRMATION)
                ->where('seller_user_id', $user_id)
                ->get($this->table_orders_arhive)
                ->result();

        $oreders_conf = array_set_value_in_key($oreders_conf, 'original_order_id');

//        $oreders_other = $this->db
//                ->where('status != ', self::ORDER_STATUS_CONFIRMATION)
//                ->where('seller_user_id', $user_id)
//                ->get($this->table_orders_arhive)
//                ->result();
//        $oreders_conf = array_set_value_in_key($oreders_conf, 'original_order_id');

//        $order_chat = array();
//        $order_chat_conf = [];
//        pred();
        foreach($res as $val)
        {
            if(isset($oreders_conf[$val->orig_order_id]))
            {
                $order_chat_conf[$val->order_id] += 1;
            }
            else
            {
                $order_chat[$val->order_id] += 1;
//                $order_chat_conf[$val->order_id] = 1;
//                if(isset($order_chat[$val->order_id]))
//                {
//                    $order_chat[$val->order_id]++;
//                }
//                else
//                {
//                    $order_chat[$val->order_id] = 1;
//                }
            }



        }
//        pre($order_chat_conf);
//        pre($count_chat);
//        pred($order_chat);
//        pred(array($count_chat, $order_chat));
        return array($count_chat, $order_chat, $order_chat_conf);
    }

    public function chatCountMessages($user_id) {
        $messages = $this->get_all_user_chats($user_id);
        if(empty($messages))
            return false;

        $out = array();

        if(isset($messages[1])) {
            $out['archive_order'] = count($messages[1]);
        }
        if(isset($messages[2])) {
            $out['active_order'] = count($messages[2]);
        }
        return $out;


    }
    #admin only
    public function getAllChat($where)
    {
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($filter)
//            $this->_createFilter($filter, "t.");
            $this->_createFilter($filter, "pc.");

//        $r["count"] = $this->db->select("COUNT(*) as count")
//                        ->where($where)
//                        ->get("$table t")
//                        ->row("count");

//        $r["count"] = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
//                        ->where('operator', 1)
//                        ->where('suport_team_operator_id', 0)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->get()
//                        ->result();
        $r["count"] = $this->db->select("COUNT(*) as count")
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
//                        ->where('operator', 1)
                        ->where($where)
                        ->order_by('pc.status', 'ASC')
                        ->order_by('pc.date', 'DESC')
                        ->get()
                        ->row("count");

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "pc.");

//        $columns = array_keys($cols);

//        $r["rows"] = $this->db
//                ->select(implode(',', $columns))
//                ->limit($per_page, $offset)
//                ->where($where)
//                ->get("$table t")
//                ->result();
        $r["rows"] = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
                        ->where($where)
//                        ->where('suport_team_operator_id', 0)
                        ->order_by('pc.status', 'ASC')
                        ->order_by('pc.date', 'DESC')
                        ->get()
                        ->result();

        return json_encode($r);
    }

    #admin only
    public function getAllChatGroupChatID($where)
    {
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($filter)
//            $this->_createFilter($filter, "t.");
            $this->_createFilter($filter, "pc.");

//        $r["count"] = $this->db->select("COUNT(*) as count")
//                        ->where($where)
//                        ->get("$table t")
//                        ->row("count");

//        $r["count"] = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
//                        ->where('operator', 1)
//                        ->where('suport_team_operator_id', 0)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->get()
//                        ->result();
//        $r["count"] = $this->db->select("COUNT(*) as count")
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
////                        ->where('operator', 1)
//                        ->where($where)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->group_by( 'pc.order_id' )
//                        ->get()
//                        ->row("count");

//        select("COUNT(*) as count")
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
////                        ->where('operator', 1)
//                        ->where($where)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->group_by( 'pc.order_id' )
//                        ->get()
//                        ->row("count");

        $r["count"] = $this->db->query( 'SELECT COUNT(*) as count '.
                                        'FROM '.
                                        '(SELECT s.id '.
                                        'FROM `currency_exchange_problem_chat` as `pc` '.
                                        'LEFT JOIN `currency_exchange_problem_suject` as `s` ON `s`.`id` = `pc`.`id_subject` '.
                                        'WHERE '.$where.' '.
                                        'GROUP BY `pc`.`order_id`) as a' )
                             ->row('count');


//        echo $this->db->last_query();)
        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "pc.");

//        $columns = array_keys($cols);

//        $r["rows"] = $this->db
//                ->select(implode(',', $columns))
//                ->limit($per_page, $offset)
//                ->where($where)
//                ->get("$table t")
//                ->result();
        $r["rows"] = $this->db->select('MAX(pc.id) as m_id,pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
                        ->where($where)
//                        ->where('suport_team_operator_id', 0)
                        ->order_by('pc.id', 'DESC')
                        ->group_by( 'pc.order_id' )

                        ->get()
                        ->result();

        return json_encode($r);
    }


    public function get_all_order_message_by_one_message_id($id)
    {
//        $one_message = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
//                        ->where('pc.operator', 1)
//                        ->where('pc.suport_team_operator_id', 0)
//                        ->where('pc.id', $id)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->get()
//                        ->row();

        $one_message = $this->get_problem_chat_message_by_id($id);
        if(empty($one_message))
        {
            return array('', '');
        }

        $orders_arhiv = $this->get_order_and_parent_order_from_arhiv($one_message->order_id, $one_message->user_id);
        if(empty($orders_arhiv))
        {
            return array('', '');
        }

        $ids = array();

        $this->load->model('users_model');

        $user_data = array();

        foreach($orders_arhiv as $val)
        {
            $ids[] = $val->id;

            $user_data[$val->seller_user_id] = $this->users_model->getUserData($val->seller_user_id);
        }

        $messages = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
                        ->where_in('pc.order_id', $ids)
                        ->order_by('pc.status', 'ASC')
                        ->order_by('pc.date', 'DESC')
                        ->get()
                        ->result();

        if(empty($messages))
        {
            return array('', '');
        }

        foreach($messages as  &$val)
        {
            if($val->user_id && !isset($user_data[$val->user_id]))
            {
                $user_data[$val->user_id] = $this->users_model->getUserData($val->user_id);
            }

            $val->user_name = 'Оператор';

            if(isset($user_data[$val->user_id]))
            {
                $val->user_name = $user_data[$val->user_id]->sername .' '. $user_data[$val->user_id]->name;
            }
        }

        unset($val);

        $one_message->user_name = $user_data[$one_message->user_id]->sername .' '. $user_data[$one_message->user_id]->name;

        return array($one_message, $messages, $user_data);
    }
    public function create_chat($order_id)
    {
        if( empty( $order_id ) ) return FALSE;
//        $one_message = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
//                        ->from($this->table_problem_chat.' as pc')
//                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
//                        ->where('pc.operator', 1)
//                        ->where('pc.suport_team_operator_id', 0)
//                        ->where('pc.id', $id)
//                        ->order_by('pc.status', 'ASC')
//                        ->order_by('pc.date', 'DESC')
//                        ->get()
//                        ->row();


        $orders = $this->db
                ->where('original_order_id', $order_id)
                ->get($this->table_orders_arhive)
                ->result();

        if( empty( $orders ) )
        {
            echo _e("Заявка с таким ИД не найдена. Невозможно создать чат.");
            return FALSE;
        }

        $this->load->model('users_model');
        $user_data = array();

        foreach( $orders as $o )
        {
            $user_data[$o->seller_user_id] = $this->users_model->getUserData($o->seller_user_id);
        }

        return $user_data;
    }



    public function get_problem_chat_message_by_id($id)
    {
        $one_message = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
                        //->where('pc.operator', 1)
                        //->where('pc.suport_team_operator_id', 0)
                        ->where('pc.id', $id)
                        ->order_by('pc.status', 'ASC')
                        ->order_by('pc.date', 'DESC')
                        ->get()
                        ->row();

        return $one_message;
    }
    public function get_problem_chat_by_order_id($original_order_id, $archive_order_id)
    {

        if( empty( $original_order_id ) )
            return FALSE;

        if( !empty( $archive_order_id ) && is_array( $archive_order_id ) ) $this->db->where_in( 'pc.order_id', $archive_order_id );

        $one_message = $this->db->select('pc.*, s.machin_name as s_machin_name, s.human_name as s_human_name')
                        ->from($this->table_problem_chat.' as pc')
                        ->join($this->table_problem_suject.' as s', 's.id = pc.id_subject', 'left')
                        ->where('pc.operator', 1)
//                        ->where('pc.suport_team_operator_id', 0)
                        ->where( 'pc.orig_order_id', $original_order_id )

                        ->order_by('pc.status', 'ASC')
                        ->order_by('pc.date', 'DESC')
                        ->get()
                        ->row();

        return $one_message;
    }


    public function set_show_to_admin_chat($id)
    {
        if(empty($id))
        {
            return false;
        }

        $this->db
                ->where_in('id', $id)
                ->limit(1)
                ->update($this->table_problem_chat, array('show_operator' => 1));
    }
    public function hide_admin_chat_by_order_id($order_id)
    {
        if(empty($order_id))
        {
            return false;
        }

        $this->db
                ->where('order_id', $order_id)
                ->update($this->table_problem_chat, array('show_operator' => 0));
    }
    public function hide_admin_chat($id_subject)
    {
        if(empty($id_subject))
        {
            return false;
        }

        $this->db
                ->where('id_subject', $id_subject)
                ->update($this->table_problem_chat, array('show_operator' => 0));
    }


    public function get_exchange_rate_by_currency_char_code($char_code)
    {
//        $ValCurs = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req=11.07.2015');
        $ValCurs = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req='.date('d.m.Y'));

        $value = false;
        foreach($ValCurs->Valute as $val)
        {
            if($val->CharCode != $char_code) continue;

                $value = (string)$val->Value;
                $nominal = (float)$val->Nominal;

                break;

        }

        $value = preg_replace('|,|', '.', $value);

        return (float)$value/(float)$nominal;
    }


    public function get_exchange_rate_uah($ps_id)
    {
        if(!empty($ps_id))
        {
            $rate = $this->_get_coins_rate_from_db($ps_id, true, 'rate');

            if($rate !== false)
            {
                return $rate;
            }
        }

        $rate = $this->get_exchange_rate_by_currency_char_code('UAH');

        ##########################
        # временное решение для центробанка россии.
        $rate_usd = $this->get_exchange_rate_by_currency_char_code('USD');
        $rate = $rate_usd/$rate;
        ##########################

        if(!empty($ps_id))
        {
            $this->_set_coins_rate($ps_id, $rate);
        }

        return $rate;
    }


    //евро
    public function get_exchange_rate_eur($ps_id)
    {
        if(!empty($ps_id))
        {
            $rate = $this->_get_coins_rate_from_db($ps_id, true, 'rate');

            if($rate !== false)
            {
                return $rate;
            }
        }

        $rate = $this->get_exchange_rate_by_currency_char_code('EUR');

        ##########################
        # временное решение для центробанка россии.
        $rate_usd = $this->get_exchange_rate_by_currency_char_code('USD');
        $rate = $rate_usd/$rate;
        ##########################

        if(!empty($ps_id))
        {
            $this->_set_coins_rate($ps_id, $rate);
        }

        return $rate;
    }


    public function get_exchange_rate($currency_id)
    {
        $all_currency = $this->get_all_currencys_key_num();

        if(!isset($all_currency[$currency_id]))
        {
            return 1;
        }

        $code = $all_currency[$currency_id]->code;

        $rate = $this->_get_rate_from_db($code, true, 'rate');
//        $rate = false;
        if($rate !== false)
        {
            return $rate;
        }

        $this->load->library('simple_html_dom');

        $date = date('d/m/y');
        $format = 'ASCII';
//        $format = 'CSV';
//        $format = 'HTML';
//        $url = 'http://www.oanda.com/currency/table?date='.$date.'&date_fmt=normal&exch=USD&sel_list='.$code.'&value=1&format='.$format.'&redirected=1';
        $url = 'http://www.oanda.com/currency/table?date='.$date.'&date_fmt=normal&exch=USD&sel_list='.$code.'&value=1&format='.$format.'&redirected=0';

        $html = file_get_html($url);

        $rate_str = $html->find('#converter_table #content_section td pre font',0)->innertext;
        $rate_str = html_entity_decode($rate_str);
        $rate_str = preg_replace('/<span>|<\/span>|<nobr>|<\/nobr>|<\!--.*-->/U','', $rate_str);
//        $t = '<!-- 1.82 -->RUB             0.01528<!-- 8.21 -->                    65.57500     Russian Rouble ';
//        $split = preg_split('/\s+/', $rate_str);
        $rate_str = ltrim($rate_str);
        $split = preg_split('/\s\s+/', $rate_str);
        
        $split[2] = preg_replace('/\s+/U', '', $split[2]);
        
        $rate = floatval($split[2]);
        
        $old_rate = $this->_get_rate_from_db($code, false, 'rate');
        
        $percent_rate = $rate/$old_rate;
        
        if($old_rate && ($percent_rate  < 0.8 || $percent_rate > 1.2))
        {
            $rate = $old_rate;
        }
        
        $this->_set_rate($code, $rate);
        
        if($rate <= 0)
        {
            $rate = $this->_get_rate_from_db($code, false, 'rate');
        }
        
        return $rate;
    }


    public function getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $choice_currecy_sell, $choice_currecy_buy)
    {
        $max_fee = false;
        $max_ammount = false;
        $payment_systems = $sell_payment_systems + $buy_payment_systems;

        $buy = false;

//        if(key_exists('wt', $buy_payment_systems))
        if($this->is_root_currency_in_array_key($buy_payment_systems))
        {
            $buy = TRUE;
        }

       
        foreach ($payment_systems as $k => $v)
        {
            
            if(!empty($buy_user_payment_summa[$k]) && $buy_user_payment_summa[$k] > 0)
            {
                $sell_sum_amount = $buy_user_payment_summa[$k];
                $choice_currecy = isset($choice_currecy_buy[$k])?$choice_currecy_buy[$k]:false;
            }

            if(!empty($sell_user_payment_summa[$k]) && $sell_user_payment_summa[$k] > 0)
            {
                $sell_sum_amount = $sell_user_payment_summa[$k];
                $choice_currecy = isset($choice_currecy_sell[$k])?$choice_currecy_sell[$k]:false;
            }
//            vred($sell_sum_amount, $k, $buy, $choice_currecy);
            list($ammount, $fee ,$percentage, $error) = $this->calculate_fee($sell_sum_amount, $k, $buy, $choice_currecy);

//visa fee

            if($this->is_root_currency($k) || $k == 'wt_visa_usd') //or visa
            {
                $max_fee = $fee;
                $max_ammount = $ammount;
                break;
            }

            if($fee > $max_fee)
            {
                $max_fee = $fee;
                $max_ammount = $ammount;
            }
        }
        
        
        $max_ammount = round($max_ammount*100)/100;

        $max_fee = round($max_fee*100)/100;

        if($buy === TRUE)
        {
            $sell_sum_total = $max_ammount;
        }
        else
        {
            $sell_sum_total = $max_ammount + $max_fee;
        }

        if($buy === false && $max_ammount < 50)
        {
            $error = _e('Минимальная сумма перевода - 50 USD.');
        }
        elseif($buy === true && ($sell_sum_total+$max_fee) < 50)
        {
            $error = _e('Минимальная сумма перевода - 50 USD.');
        }

        return array($max_fee, $sell_sum_total, $percentage, $error);
    }



    public function get_equil_usd_summ($ps, $summ, $choice_currency = false)
    {
        $ps = self::get_ps($ps);

        if(empty($ps))
        {
            return false;
        }

        $rate = $this->get_currency_rate($ps, $choice_currency);

        $amount = $summ/$rate;

        return round($amount, 4);
    }



    public function set_order_discount_for_not_wt($order_id)
    {
        if(empty($order_id))
        {
            return false;
        }

        $res_details_sell = $this->db
                    ->where('order_id', $order_id)
                    ->where('type', 1)
                    ->get($this->table_order_details)
                    ->row();

        $res_details_buy = $this->db
                    ->where('order_id', $order_id)
                    ->where('type', 0)
                    ->get($this->table_order_details)
                    ->result();

//        pre($res_details_sell);

        $choice_currency = $res_details_sell->choice_currecy?$res_details_sell->choice_currecy:false;
//        vred($res_details_sell->payment_system);
        $rate = $this->get_currency_rate(self::get_ps($res_details_sell->payment_system), $choice_currency);
//        vre($rate);
        $sell_summ = $res_details_sell->summa/$rate;

//        pre($sell_summ, $rate);

        $smallest_discount = false;

        foreach($res_details_buy as $val)
        {
            $choice_currency = $val->choice_currecy?$val->choice_currecy:false;

            $rate = $this->get_currency_rate(self::get_ps($val->payment_system), $choice_currency);

            $buy_summ = $val->summa/$rate;
            $discount = round(($buy_summ - $sell_summ)/$sell_summ, 4)*100;

            if($smallest_discount === false || $smallest_discount > $discount)
            {
                $smallest_discount = $discount;
            }

            $update = ['discount' => $discount ];
//            vre($buy_summ, $update);
            $this->db
                ->where('id',$val->id)
                ->update($this->table_order_details, $update);

//            vre($this->db->last_query());
        }

//        vre($smallest_discount);

        $update = [
            'order_discount' => $smallest_discount,
        ];

        $this->db
                ->where('order_id', $order_id)
                ->update($this->table_order_details, $update);
//        vre($this->db->last_query());
        $data = [
            'discount' => $smallest_discount,
        ];

        $this->update_original_order($order_id, $data);

        return true;
//        vred($this->db->last_query());
    }



    public function get_paymen_data_kontragent_by_order_id_and_payment_id($order_id, $payment_id)
    {
        $res_order = $this->db
                    ->where('buyer_order_id', $order_id)
                    ->get($this->table_orders_arhive)
                    ->row();

        $res = $this->db
                ->where('user_id', $res_order->seller_user_id)
                ->where('payment_system_id', $payment_id)
                ->get($this->table_user_paymant_data)
                ->row();

        return $res;
    }



    public function get_user_payment_data_by_payment_id($payment_id, $user_id = false)
    {
        if($user_id === false)
        {
            $user_id = $this->account->get_user_id();
        }

        $res = $this->db
                ->where('user_id', $user_id)
                ->where('payment_system_id', $payment_id)
                ->get($this->table_user_paymant_data)
                ->row();

        return $res;
    }


    public function is_payment_system_wt($pay_sys_id, $user_id = false)
    {
        if($user_id === false)
        {
            $user_id = $this->account->get_user_id();
        }

        $paymets = $this->get_user_all_paymant_data($user_id);

        $paymet_data = array_set_value_in_key($paymets);

//        if($paymet_data['wt']->id == $pay_sys_id )
        if($this->is_root_currency($pay_sys_id))
        {
//            return $paymet_data['wt'];
            return $paymet_data[$pay_sys_id];
        }

        return false;
    }



    static public function get_paymen_data_kontragent_by_order_id($order_id, $payment_id)
    {
        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

        return  $ci->currency_exchange->get_paymen_data_kontragent_by_order_id_and_payment_id($order_id, $payment_id);
    }



//    static public function get_ps_data_for_table($ps, $ps_data = false)
    static public function get_ps_data_for_table($ps, $ps_data = false, $dop_data = false)
    {
        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

        if(!method_exists($ci->currency_exchange, $ps->method_get_field_and_ps_data))
        {
            if($ps_data === false)
            {
                $ps_data = $ps->payment_sys_user_data;
            }

            return _e('currency_exchange/timer_confirm/ackount').' '.$ps_data;
        }

        $template = 'template_table';

        if(isset($dop_data['template']))
        {
            $template = $dop_data['template'];
            unset($dop_data['template']);
        }

        return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, $template, $ps_data, $dop_data);
//        return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'template_table', $ps_data, $dop_data);
    }



    static public function get_ps_data_for_table_short($ps, $ps_data = false, $dop_data = false)
    {
        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

        if(!method_exists($ci->currency_exchange, $ps->method_get_field_and_ps_data))
        {
            return $ps_data;
        }

//        $res = $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'array', $ps_data);
        return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'short', $ps_data, $dop_data);
//        pred($res);
//        foreach($res as $v)
//        {
//            return  $v;
//        }
//        return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'template_table', $ps_data);
    }



    static public function get_field_and_ps_data($ps, $ps_data = false)
    {
        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

        if(!method_exists($ci->currency_exchange, $ps->method_get_field_and_ps_data))
        {
            return false;
        }



        return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'template_sell', $ps_data);

//        if($return == 'template_sell')
//        {
//            return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, $return);
//        }
//        elseif($return == 'template_table')
//        {
//            return  $ci->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, $return);
//        }
//        elseif($return == 'array')
//        {
//            return  unserialize($ps->payment_sys_user_data);
//        }
//
//        return false;
    }



    static public function half_bg($bg_position, $percent = 50)
    {
        if(empty($bg_position))
        {
            return $bg_position;
        }

        $percent = $percent/100;

        $bg_position_arr = preg_split('|:|', $bg_position);

        if(!isset($bg_position_arr[1]))
        {
            return $bg_position;
        }


        $bg_position_arr[1] = preg_replace("/px|;/", '', $bg_position_arr[1]);

        $coordinats_arr = preg_split('| |', $bg_position_arr[1]);

        $str = '';

        foreach($coordinats_arr as $val)
        {
            if(!empty($val))
            {
                $str .= $val*$percent.'px ';
            }
        }

       return  $bg_position_arr[0].': '.$str.';';
    }



    static public function ps_show_group_content($data)
    {
        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

//        return $ci->load->view('user/accaunt/currency_exchange/blocks/form/_form_group_and_payments_select_default.php', $data, true);

        if(!empty($data['group']->method_show_group_content) && method_exists($ci->currency_exchange, $data['prefix_method_show_group_content'].$data['group']->method_show_group_content))
        {
            $method = $data['prefix_method_show_group_content'].$data['group']->method_show_group_content;

            return $ci->currency_exchange->{$method}($data);
        }

        if(!empty($data['group']->method_show_group_content) && method_exists($ci->currency_exchange, $data['group']->method_show_group_content))
        {
            $method = $data['group']->method_show_group_content;

            return $ci->currency_exchange->{$method}($data);
        }

        return $ci->load->view('user/accaunt/currency_exchange/blocks/form/_form_group_and_payments_select_default.php', $data, true);
    }


    static public function show_payment_system_code($params)
    {
        extract($params);

        $ci = get_instance();
        $ci->load->model('currency_exchange_model','currency_exchange');

  //      $all_curency = $ci->currency_exchange->get_all_activ_currencys(); // Why is it required?
        $all_curency_key_num = $ci->currency_exchange->get_all_currencys_key_num();
        
        if(isset($order->payment_systems) && isset($curent_ps->id) && isset($order->payment_systems[$curent_ps->id]))
        {
            $key = empty($order->payment_systems[$curent_ps->id]->choice_currecy)?$order->payment_systems[$curent_ps->id]->currency_id:$order->payment_systems[$curent_ps->id]->choice_currecy;

            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $key).'';
        }
        elseif(isset($order->payment_systems) && isset($payment_systems_id))
        {
            $payment_systems = array_set_value_in_key($order->payment_systems, 'payment_system');

            $cur_ps = $payment_systems[$payment_systems_id];

            $key= empty($cur_ps->choice_currecy)?$cur_ps->currency_id:$cur_ps->choice_currecy;

            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $key).'';
        }
        elseif(isset($pay_sys->choice_currecy))
        {
            $key = empty($pay_sys->choice_currecy)?$pay_sys->currency_id:$pay_sys->choice_currecy;

            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $key).'';
        }
        //вариант для старых заявок
        elseif(isset($pay_sys) && !isset($pay_sys->choice_currecy))
        {
            $key = $pay_sys->currency_id;

            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $key).'';
        }
        elseif(isset($currency_id))
        {
            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $currency_id).'';
        }
        elseif(isset($ps) && isset($select_curency_sell) )
        {
            $key = ($select_curency_sell[$ps->machine_name] != 0)?$select_curency_sell[$ps->machine_name]:$ps->currency_id;

            return $ci->currency_exchange->get_currency_code_for_static_method($all_curency_key_num, $key).'';
        }

        return false;
//        if($order->id == 122 && !isset($order->original_order_id))
//        if($order->original_order_id == 122 )
//        {
//            vre(func_get_args());
//            pre($tcurent_ps);
//            pre($curent_ps);
//            pred($order);
//        }
    }


    static public function all_currencys_to_js($res_json = true)
    {
        $currency_exchange = self::getInstance();

        $curencys = $currency_exchange->get_all_currencys();

        $res = [
            9840 => _e('currency_id_9840'),
            9841 => _e('currency_id_9841'),
            9842 => _e('currency_id_9842'),
            9900 => _e('currency_id_9900'),
            9910 => _e('currency_id_9910'),
        ];

        foreach( $curencys as  $one)
        {
            $res[$one->num] = $one->code;
        }

        foreach($res as &$v)
        {
            $v = preg_replace('|"|', '\'', $v);
        }
        unset($v);

        if($res_json === TRUE)
        {
            $res = json_encode($res);
            return preg_replace('|\'|', "\\'", $res);
//            pred(preg_replace('|\'|', "\\'", $res));
        }

        return $res;
    }




    private function get_currency_code_for_static_method($all_curency_key_num, $key)
    {
        if($key >= 9000)
        {
            return _e( 'currency_id_'.$key);
        }

        return $all_curency_key_num[$key]->code;
    }


    public function get_all_currencys()
    {
        if(self::$_all_curencys !== false)
        {
            return self::$_all_curencys;
        }

        return self::$_all_curencys = $this->get_all_activ_currencys();
    }



    public function get_all_currencys_key_num()
    {
        if(self::$_all_curencys_key_num !== false)
        {
            return self::$_all_curencys_key_num;
        }

        $all_curency = $this->get_all_currencys();

        return self::$_all_curencys_key_num = array_set_value_in_key($all_curency, 'num');
    }



    public function show_group_content_bank($data)
    {
//        $res = $this->db
//                ->order_by('country_name_ru', 'ASC')
//                ->get($this->table_country)
//                ->result();

        $data += [
            'countris' => $this->get_all_country_name(),
            'currencys' => $this->get_all_activ_currencys(),
        ];

        return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/show_group_content_bank.php', $data, true);
    }


    public function payment_preferences_show_group_content_bank($data)
    {
        $user_id = $this->account->get_user_id();

        $payment_data = $this->db
                ->where('user_id', $user_id)
                ->get($this->table_user_paymant_data)
                ->result();

        $data += [
            'countris' => $this->get_all_country_name(),
            'currencys' => $this->get_all_activ_currencys(),
            'user_all_payment_data' => $payment_data,
        ];

        return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/show_group_content_bank.php', $data, true);
    }



    public function get_all_activ_currencys()
    {
        $currencys = $this->db
                ->where('active', 1)
              //  ->order_by('location', 'ASC')
                ->get($this->table_all_currency)
                ->result();

        $currencys = array_set_value_in_key($currencys);

        return $currencys;
    }



    public function update_original_order($order_id, $data) {
        if( empty( $order_id ) ) return FALSE;

        $this->db->where('id',$order_id)
                ->limit(1)
                ->update($this->table_orders,$data);
    }



    public function update_archive_order($order_id, $data) {
        if( empty( $order_id ) ) return FALSE;

        $this->db->where('id',$order_id)
                ->limit(1)
                ->update($this->table_orders_arhive,$data);
    }



    public function get_all_country_name()
    {
        if(self::$_all_country_name !== false)
        {
            return self::$_all_country_name;
        }

        $res = $this->db
                ->where('active', 1)
                ->order_by('order', 'ASC')
                ->order_by('country_name_ru', 'ASC')
                ->get($this->table_country)
                ->result();

        $res_ids = array_set_value_in_key($res);

        self::$_all_country_name = $res_ids;

        return $res_ids;
    }






    public function get_arhiv_orders_by_parent_id($parent_id)
    {
        $res = $this->db
                ->where('original_order_id', $parent_id)
                ->get($this->table_orders_arhive)
                ->result();

        return $res;
    }



    public function get_arhiv_orders_by_parent_id_and_status($parent_id, $status = self::ORDER_STATUS_CONFIRMATION)
    {
        if(is_array($status))
        {
            $this->db->where_in('status', $status);
        }
        elseif( !empty( $status ) )
        {
            $this->db->where('status', $status);
        }

        $res = $this->db
                ->where('original_order_id', $parent_id)
                ->order_by('id','ASC')
                ->get($this->table_orders_arhive)
                ->result();

        return $res;
    }




    public function set_status_to_order_arhive_and_order($order_id, $order_status = self::ORDER_STATUS_PROCESSING, $arhiv_order_status = self::ORDER_STATUS_PROCESSING , $curent_arhiv_order_status = self::ORDER_STATUS_CONFIRMATION)
    {
        if(empty($order_id))
        {
            return false;
        }

        $order_data = array(
            "status" => $order_status,
        );

        $arhiv_order_data = array(
            "status" => $arhiv_order_status,
        );

        try
        {
            $this->db->trans_start();

            $this->db
                ->where('id', $order_id)
                ->limit(1)
                ->update($this->table_orders, $order_data);

            $this->db
                ->where('original_order_id', $order_id)
                ->where('status', $curent_arhiv_order_status)
                ->order_by('id', 'DESC')
                ->limit(2)
                ->update($this->table_orders_arhive, $arhiv_order_data);

            $this->set_status_to_details( $order_id, $order_status );

            $this->db->trans_complete();

        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }

        return true;
    }



    public function set_machin_name_to_payment_systems($order)
    {
        $payment_systems = $this->get_all_payment_systems();
        $pay_sys = array_set_value_in_key($payment_systems);

        foreach($order->payment_systems as &$val)
        {
            if(!isset($pay_sys[$val->payment_system]))
            {
                $pay_sys[$val->payment_system] = self::get_ps($val->payment_system);
//                pred( $pay_sys[$val->payment_system]);
            }

            $val->machine_name = $pay_sys[$val->payment_system]->machine_name;
            $val->currency_id = $pay_sys[$val->payment_system]->currency_id;
            $val->public_path_to_icon = $pay_sys[$val->payment_system]->public_path_to_icon;
            $val->is_bank = $pay_sys[$val->payment_system]->is_bank;
        }
        unset($val);

        return $order;
    }

    /**
     * $orders = array( 0 => array( status => 1, buyer_user_id = 23232 ) );
     * $orders = array( id => array( param_name => paran_val ) );
     * @param type $orders
     * @return boolean
     */
    public function update_archive_orders( $orders )
    {
        if( !is_array( $orders ) )
            return FALSE;

        $res = array();
        $res_one = TRUE;
        foreach( $orders as $id => $up_data )
        {
            $res[$id] = $this->db->where( 'id', $id )
                                ->limit(1)
                                ->update( $this->table_orders_arhive, $up_data );

            if( $res[$id] === FALSE ) $res_one = FALSE;
        }


        return array( $res_one, $res);
    }
    public function update_original_orders( $orders )
    {
        if( !is_array( $orders ) )
            return FALSE;

        $res = array();
        $res_one = TRUE;
        foreach( $orders as $id => $up_data )
        {
            $res[$id] = $this->db->where( 'id', $id )
                                ->limit(1)
                                ->update( $this->table_orders, $up_data );

            if( isset( $up_data['status'] ) ) $this->set_status_to_details( $delete_id, $up_data['status'] );

            if( $res[$id] === FALSE ) $res_one = FALSE;
        }


        return array( $res_one, $res);
    }

    public function set_dop_data_to_unserializ_payment_system($order)
    {
       if(!isset($order->payment_systems) && isset($order->order_details_arhiv))
       {
           $order->payment_systems = unserialize($order->order_details_arhiv);
       }
       elseif(!isset($order->payment_systems) && !isset($order->order_details_arhiv) && !isset($order->original_order_id))
       {
           $query = $this->db->where('order_id', $order->original_order_id);
           $order->order_details_arhiv = $query->get($this->table_order_details)->result();
       }

       $order_payment_systems_temp = $order->payment_systems;
       $one_payment_systems_temp = array_shift($order_payment_systems_temp);

       if(!$one_payment_systems_temp->machine_name)
       {
           $order = $this->set_machin_name_to_payment_systems($order);
       }

       $buy_payment_data = unserialize($order->buy_payment_data);
       $sell_payment_data = unserialize($order->sell_payment_data);

//       pre($buy_payment_data);
//       pre($sell_payment_data);
//       pred($order);
       $payment_systems = array_set_value_in_key($order->payment_systems, 'payment_system');

       foreach ($buy_payment_data as &$val)
       {
           $val->machine_name = $payment_systems[$val->payment_system_id]->machine_name;
           $val->summa = $payment_systems[$val->payment_system_id]->summa;
           $val->currency_id = $payment_systems[$val->payment_system_id]->currency_id;
           $val->choice_currecy = $payment_systems[$val->payment_system_id]->choice_currecy;
           $val->public_path_to_icon = $payment_systems[$val->payment_system_id]->public_path_to_icon;
           $val->is_bank = $payment_systems[$val->payment_system_id]->is_bank;
       }
//       $order->buy_payment_data_un = $buy_payment_data;
       $order->buy_payment_data_un = array_set_value_in_key($buy_payment_data, 'payment_system_id');
       unset($val);

       foreach ($sell_payment_data as &$val)
       {
           $val->machine_name = $payment_systems[$val->payment_system_id]->machine_name;
           $val->summa = $payment_systems[$val->payment_system_id]->summa;
           $val->currency_id = $payment_systems[$val->payment_system_id]->currency_id;
           $val->choice_currecy = $payment_systems[$val->payment_system_id]->choice_currecy;
           $val->is_bank = $payment_systems[$val->payment_system_id]->is_bank;
           $val->public_path_to_icon = $payment_systems[$val->payment_system_id]->public_path_to_icon;
       }
       $order->sell_payment_data_un = array_set_value_in_key($sell_payment_data, 'payment_system_id');
       unset($val);
//       pred($order);
       return $order;
    }


    public function set_reject_note_in_order(stdClass $order)
    {
        if(empty($order))
        {
            return FALSE;
        }

        if(isset($order->original_order_id))
        {
            $res = $this->db
                    ->where('order_id', $order->original_order_id)
                    ->where('is_arhiv', 0)
                    ->order_by('id', 'DESC')
                    ->get($this->table_operator_reject_notes)
                    ->row();

            $res_arh = $this->db
                    ->where('order_id', $order->id)
                    ->where('is_arhiv', 1)
                    ->order_by('id', 'DESC')
                    ->get($this->table_operator_reject_notes)
                    ->row();

//            $order->reject_note = NULL;
//            $order->reject_note_arhiv = NULL;
            $order->reject_note = $res;
            $order->reject_note_arhiv = $res_arh;
        }
        else
        {
            $res = $this->db
                    ->where('order_id', $order->id)
                    ->where('is_arhiv', 0)
                    ->order_by('id', 'DESC')
                    ->get($this->table_operator_reject_notes)
                    ->row();

            $order->reject_note = $res;
            $order->reject_note_arhiv = NULL;
        }

        return $order;
    }



    public function send_mail($order_id, $template, $user_id = false, $send_kontragent = false)
    {
        if(empty($order_id))
        {
            return false;
        }
//        return false;
        if($user_id === FALSE)
        {
           $user_id = $this->account->get_user_id();
        }

        if(!is_object($order_id))
        {
            $order = $this->get_order_by_id($order_id);

//            $orders_arhiv = $this->get_arhiv_orders_by_parent_id($order_id);
            $orders_arhiv = $this->db
                            ->where('original_order_id', $order_id)
                            ->where('seller_user_id !=', $user_id)
                            ->get($this->table_orders_arhive)
                            ->result();
//            vre($this->db->last_query());
//            pred($orders_arhiv);
        }
        elseif(is_object($order_id))
        {
            $order = $order_id;
            $order_id = $order->id;

//            $orders_arhiv = $this->get_arhiv_orders_by_parent_id($order->original_order_id);
             $res_oa = $this->db
                        ->where('original_order_id', $order->original_order_id)
                        ->where('seller_user_id !=', $user_id)
                        ->get($this->table_orders_arhive)
                        ->result();

             $orders_arhiv = $res_oa;
        }

        $this->load->model('users_model', 'users_model');
//        $seller_user = $this->users_model->getUserData($order->seller_user_id);
        $seller_user = $this->users_model->getUserData($user_id);

        if(!empty($orders_arhiv))
        {
            $orders_arhiv = $this->_get_user_orders_arhive_details($orders_arhiv);
            $arhiv_order = array_shift($orders_arhiv);

//            $buyer_user = $this->users_model->getUserData($arhiv_order->buyer_user_id);

//            if($send_kontragent === true && $user_id != $arhiv_order->buyer_user_id)
//            {
//                $kontragent = $arhiv_order->buyer_user_id;
//            }
//            elseif($send_kontragent === true && $user_id == $arhiv_order->buyer_user_id)
//            {
//                $kontragent = $arhiv_order->seller_user_id;
//            }
            $kontragent = $arhiv_order->seller_user_id;
//            vred($user_id, $kontragent);
            if($send_kontragent === false)
            {
                $buyer_user = $this->users_model->getUserData($kontragent);
            }
            else
            {
                $seller_user = $this->users_model->getUserData($kontragent);
                $buyer_user = $this->users_model->getUserData($user_id);
            }
        }
        else
        {
            $arhiv_order = FALSE;
            $buyer_user = FALSE;
        }


        $data = array(
            'order' => $order,
            'arhiv_order' => $arhiv_order,
            'seller_user' => $seller_user,
            'buyer_user' => $buyer_user,
            'curent_user_id' => $user_id,
            'send_kontragent' => $send_kontragent
        );
//        pred($data);
        switch ($template)
        {
            case 'order_set':
                $subject = sprintf(_e('Заявка на P2P-обмен № %s выставлена.'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_set.php', $data, true);
            break;
//            PROCESSING
            case 'order_processing':
                $subject = sprintf(_e('currency_exchange/mail/order_processing/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing.php', $data, true);
            break;
            case 'order_processing_2':
                $subject = sprintf(_e(' Контрагент выбрал вашу заявку № %s и она отправлена на рассмотрение Оператору.'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing_2.php', $data, true);
            break;

            case 'order_processing_buyer':
                $subject = sprintf(_e('currency_exchange/mail/order_processing/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing_buyer.php', $data, true);
            break;

            case 'order_processing_confirm':
                $subject = sprintf(_e('currency_exchange/mail/order_processing_confirm/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing_confirm.php', $data, true);
            break;
            case 'order_processing_confirm_without_search':
                $subject = sprintf(_e('Заявка на P2P-обмен № %s одобрена Оператором.'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing_confirm_without_search.php', $data, true);
            break;

            case 'order_processing_reject':
                $subject = sprintf(_e('currency_exchange/mail/order_processing_reject/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_processing_reject.php', $data, true);
            break;

            case 'order_set_active':
                $subject = sprintf(_e('currency_exchange/mail/order_set_active/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_set_active.php', $data, true);
            break;

            case 'user_send_message':
                $subject = sprintf(_e('currency_exchange/mail/user_send_message/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/user_send_message.php', $data, true);
            break;

            case 'operator_send_message':
                $subject = sprintf(_e('currency_exchange/mail/operator_send_message/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/operator_send_message.php', $data, true);
            break;
            case 'order_need_send_money':
                $subject = sprintf(_e('currency_exchange/mail/order_need_send_money/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_need_send_money.php', $data, true);
            break;

            case 'order_need_confirm_send':
                $subject = sprintf(_e('currency_exchange/mail/order_need_confirm_send/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_need_confirm_send.php', $data, true);
            break;
        ////////9
            case 'order_confirm_close':
                $subject = sprintf(_e('currency_exchange/mail/order_confirm_close/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_confirm_close.php', $data, true);
            break;
//        10
            case 'order_error_close':
                $subject = sprintf(_e('currency_exchange/mail/order_confirm_close/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_error_close.php', $data, true);
            break;

            case 'delete':
                $subject = sprintf(_e('currency_exchange/mail/delete/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/delete.php', $data, true);
            break;

            case 'order_contragent_reject':
                $subject = sprintf(_e('currency_exchange/mail/order_contragent_reject/subject'), isset($order->original_order_id)?$order->original_order_id:$order->id);
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/order_contragent_reject.php', $data, true);
            break;

            case 'user_block_1':
//                $subject = sprintf(_e('currency_exchange/mail/order_contragent_reject/subject'), $order_id);
                $subject = _e('Вы заблокированы за несоблюдение Правил переводов');
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/user_block_1.php', $data, true);
            break;
            case 'user_block_2':
//                $subject = sprintf(_e('currency_exchange/mail/order_contragent_reject/subject'), $order_id);
                $subject = _e('Вы заблокированы за несоблюдение Правил переводов');
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/user_block_2.php', $data, true);
            break;
            case 'canceld_by_user_block':
                $subject = sprintf(_e('Заявка № %s переведена в статус Выставлена и видна в поиске'), isset($order->original_order_id)?$order->original_order_id:$order->id);
//                $subject = _e('Вы заблокированы за несоблюдение Правил переводов');
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/canceld_by_user_block.php', $data, true);
            break;

            case 'canceld_by_user_block_2':
                $subject = sprintf(_e('Тема: Заявка № %s удалена.'), isset($order->original_order_id)?$order->original_order_id:$order->id);
//                $subject = _e('Вы заблокированы за несоблюдение Правил переводов');
                $text = $this->load->view('user/accaunt/currency_exchange/blocks/mail/canceld_by_user_block_2.php', $data, true);
            break;
        }

//        pre( $text);
//        vre($subject);
//        vre($this->mail->send('maxim.d1@yandex.ru', $text, $subject, 'no-reply@webtransfer.com'));
//        vre($this->mail->send('maxim.d1@yandex.ru', 'тест', $subject, 'no-reply@webtransfer.com'));
//        vred('заслал');
//        vred($send_kontragent);
        if($send_kontragent === false)
        {
            $this->send_mail_by_user_id($user_id, $subject, $text);
        }
        elseif($send_kontragent === true && !empty($kontragent))
        {
            $this->send_mail_by_user_id($kontragent, $subject, $text);
        }
    }


    public function send_mail_by_arhiv_order_id($arhiv_order_id, $template, $user_id = false, $send_kontragent = false)
    {
        $arhiv_order = $this->get_order_arhiv_by_id($arhiv_order_id);

        if($send_kontragent === true)
        {
            $arhiv_order = $this->get_order_arhiv_by_id($arhiv_order->buyer_order_id);
        }

        $orders_arhiv = $this->_get_user_orders_arhive_details(array($arhiv_order));
        $arhiv_order = array_shift($orders_arhiv);

//        $this->send_mail($arhiv_order->original_order_id, $template, $user_id , $send_kontragent);
        $this->send_mail($arhiv_order, $template, $user_id , $send_kontragent);
    }


   public function add_user_to_block_table($user_id, $order_id, $status, $note)
   {
       $data = [
           'user_id' => $user_id,
           'order_id' => $order_id,
           'status' => $status,
           'note' => $note,
           'date' => date('Y-m-d H:i:s'),
       ];

       try
        {
            $this->db->insert( $this->table_block_user, $data );
        }
        catch( Exception $exc )
        {
            //pre($this->db->last_query());
            //vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
        return  TRUE;
   }



   public function user_block($order, $status)
   {
       if(!class_exists('Users_model'))
       {
            $this->load->model('users_model', 'users');
       }

       $user_id =  $order->seller_user_id;
       $order_id =  $order->id;

       $chat = $this->get_all_user_problem_chat($order_id, $user_id);

//       В механизхм блокировки добавить условие, что блокировать необходимо
//       только тех, у кого нет чата по данной заявке. Иначе - блокировать.
       if(!empty($chat))
       {
           return true;
       }

       switch ($status)
       {
            case 1:
                $note = 'Заблокирован по истечению таймера отправки дежных средств.';
                $mail_template = 'user_block_1';
            break;
            case 2:
                $note = 'Заблокирован по истечению таймера отправки дежных средств.';
                $mail_template = 'user_block_1';
            break;
            case 3:
                $note = 'Заблокирован по истечению таймера подтверждения получения дежных средств.';
                $mail_template = 'user_block_2';
            break;
            case 4:
                $note = 'Заблокирован по истечению таймера подтверждения получения дежных средств.';
                $mail_template = 'user_block_2';
            break;
       }

       $this->users->user_active_close($user_id, 3);

       $this->users->writeCause((int) $user_id, $note, 3, 9999);

       $this->add_user_to_block_table($user_id, $order_id, $status, $note);
//       $this->send_mail_by_arhiv_order_id($id, 'order_contragent_reject', $user_id, true);
       $this->send_mail_by_arhiv_order_id($order_id, $mail_template, $user_id);
   }



    public function set_order_canceld_by_user_block($id, $status = self::ORDER_STATUS_CANCELED_BY_USER_BLOCK, $status_orig = self::ORDER_STATUS_SET_OUT)
    {
        if(empty($id))
        {
            return false;
        }

        $res = $this->db
                ->where('id', $id)
                ->get($this->table_orders_arhive)
                ->row();

        if(empty($res))
        {
            return false;
        }

        try
        {
            $this->db->trans_start();

            $this->db
                ->where_in('id', array($id, $res->buyer_order_id))
                ->limit(2)
                ->update($this->table_orders_arhive, array('status' => $status));
//            vre($this->db->last_query());

            $this->db
                ->where('id', $res->original_order_id)
                ->limit(1)
                ->update($this->table_orders, array('status' => $status_orig, 'buyer_user_id' => 0));
//            vre($this->db->last_query());

            $this->set_status_to_details( $res->original_order_id, $status_orig );
//vred($this->db->last_query());

            if($status_orig == 83)
            {
                /*
                 $this->db
                    ->delete($this->table_orders, array('id' => $res->original_order_id));
                $this->db
                    ->delete($this->table_order_details, array('order_id' => $res->original_order_id));
                */
                 $this->db
                    ->limit(1)
                    ->update($this->table_orders, array('status' => $status), array('id' => $res->original_order_id));
                $this->set_status_to_details( $res->original_order_id, $status );

                //TODO разобраться с письмами сейчас не правильно.
                $this->send_mail_by_arhiv_order_id($id, 'canceld_by_user_block_2', $res->seller_user_id);
            }
            else
            {
                //TODO разобраться с письмами сейчас не правильно.
                $this->send_mail_by_arhiv_order_id($id, 'canceld_by_user_block', $res->seller_user_id);
            }

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
            return false;
        }



        return true;
    }



    public function get_all_order_ids($limit = 5)
    {
        $res = $this->db
                ->limit($limit)
                ->get($this->table_orders)
                ->result();

        $res = array_set_value_in_key($res);

        $res_isd_arr = array_keys($res);

        return $res_isd_arr;
    }

    public function bonus_in_operator( $bonus )
    {

        foreach( $this->wt_to_operator as $i => $n )
        {

            if( $i != $bonus ) continue;
            return TRUE;
        }

        return FALSE;
    }
    public function get_bonus_by_currency( $sell_payment_systems )
    {

        if( !is_string($sell_payment_systems) && is_array($sell_payment_systems) )
        {
            $payment_system = array_keys($sell_payment_systems)[0];
        }else
            $payment_system = $sell_payment_systems;

        foreach( $this->wt_to_operator as $i => $n )
        {

            if( $n != $payment_system ) continue;
            return $i;
        }

        return FALSE;
    }

    public function currency_in_operator( $sell_payment_systems )
    {
        if( !is_string($sell_payment_systems) && is_array($sell_payment_systems) )
        {
            $payment_system = array_keys($sell_payment_systems)[0];
        }else
        $payment_system = $sell_payment_systems;

        if(in_array($payment_system, $this->wt_to_operator) ) return TRUE;

        return FALSE;
    }


    ###############################################################################################
    ###################### COINS RATES METHODS ####################################################
    ###############################################################################################

//    public function get_exchange_rate_btce($key = 'btceUSD', $ps_id)
    public function get_exchange_rate_btce($ps)
    {
        //TODO времменно приравниваем к USD
        return 1;
        $ps_id = $ps->id;

        if(!empty($ps_id))
        {
            $rate = $this->_get_coins_rate_from_db($ps_id, true, 'rate');

            if($rate !== false)
            {
                return $rate;
            }
        }

        $data = file_get_contents('http://api.bitcoincharts.com/v1/markets.json');

        $obj = json_decode($data);
        $obj = array_set_value_in_key($obj, 'symbol');

        $val = $obj['btceUSD'];

        if($val->ask)
        {
            $rate = $val->ask;
        }

        if($val->close)
        {
            $rate = $val->close;
        }

        if(!empty($ps_id))
        {
            $this->_set_coins_rate($ps_id, $rate);
        }

        return $rate;
    }


//    public function get_exchange_rate_coins($coins, $ps_id)
    public function get_exchange_rate_coins($ps)
    {
        $ps_id = $ps->id;
        $coins = $ps->machine_name;

        if(!empty($ps_id))
        {
            $rate = $this->_get_coins_rate_from_db($ps_id, true, 'rate');

            if($rate !== false)
            {
                return $rate;
            }
        }

        switch($coins)
        {
            case 'bitcoin':
                $url = 'http://preev.com/pulse/units:btc+usd/sources:bitfinex+bitstamp+btce+localbitcoins';
                $coins_name = 'btc';
            break;

            case 'litecoin':
                $url = 'http://preev.com/pulse/units:ltc+usd/sources:btce';
                $coins_name = 'ltc';
            break;

            case 'peercoin':
                $url = 'http://preev.com/pulse/units:ppc+usd/sources:btce';
                $coins_name = 'ppc';
            break;

            case 'dogecoin':
                $url = 'http://preev.com/pulse/units:xdg+usd/sources:bter+cryptsy+bitfinex+bitstamp+btce+localbitcoins';

                $data = file_get_contents($url);
                $obj = json_decode($data);
                $bter = floatval($obj->xdg->btc->bter->last);
                $bitstamp = floatval($obj->usd->btc->bitstamp->last);

                $rate = $bter/$bitstamp;

                if(!empty($ps_id))
                {
                    $this->_set_coins_rate($ps_id, $rate);
                }

                return $rate;
            break;
        }

        $data = file_get_contents($url);
        $obj = json_decode($data);

        foreach($obj->{$coins_name} as $val)
        {
            foreach($val as $v)
            {
                if(!empty($ps_id))
                {
                    $this->_set_coins_rate($ps_id, $v->last);
                }
                return $v->last;
            }
        }
    }

//    Namecoin and other
//    public function get_exchange_rate_nmc($coins = 'namecoin', $ps_id)
    public function get_exchange_rate_nmc($ps)
    {
        $ps_id = $ps->id;
        $coins = $ps->machine_name;

        if(!empty($ps_id))
        {
            $rate = $this->_get_coins_rate_from_db($ps_id, true, 'rate');

            if($rate !== false)
            {
                return $rate;
            }
        }

        $this->load->library('simple_html_dom');

        switch($coins)
        {
            case 'namecoin':
                $html = file_get_html('https://www.coingecko.com/en/price_charts/namecoin/usd');
            break;

            case 'novacoin':
                $html = file_get_html('https://www.coingecko.com/en/price_charts/novacoin/usd');
            break;

            case 'stealthcoin':
                $html = file_get_html('https://www.coingecko.com/en/price_charts/stealthcoin/usd');
            break;

            case 'darkcoin':
                $html = file_get_html('https://www.coingecko.com/en/price_charts/dash/usd');
            break;

            case 'bytecoin':
                $html = file_get_html('https://www.coingecko.com/en/price_charts/bytecoin/usd');
            break;
        }

        if(!is_object($html))
        {
            return 1;
        }

        $rate_str = $html->find('.col-md-offset-1 td[style="text-align:right;"]',0)->innertext;

        $rate_str = trim($rate_str);

        $rate = preg_replace('|\$|', '', $rate_str);

        if(!empty($ps_id))
        {
            $this->_set_coins_rate($ps_id, floatval($rate));
        }
        return floatval($rate);
    }



    private function _get_coins_rate_from_db($ps_id, $time_limit = false, $key = false)
    {
        $res = $this->db
                ->where('payment_systems_id', $ps_id)
                ->get($this->table_payment_systems_rates)
                ->row();

        if($time_limit !== FALSE)
        {
            $time_limit = ($time_limit === TRUE) ? self::PAYMENT_SYSTEM_TIME_LIVE : $time_limit;

            $date = strtotime($res->date);

            if(time() - $date > $time_limit*3600)
            {
                return false;
            }
        }

        if($key === FALSE)
        {
            return $res;
        }

        if(isset($res->{$key}))
        {
            return $res->{$key};
        }

        return false;
    }



    private function _set_coins_rate($ps_id, $rate)
    {
        try
        {
            $this->db->trans_start();

            $this->db
                ->where('payment_systems_id', $ps_id)
                ->delete($this->table_payment_systems_rates);

            $data = array(
                'payment_systems_id' => $ps_id,
                'rate' => $rate,
                'date' => date('Y-m-d H:i:s'),
            );

            $this->db
                ->insert($this->table_payment_systems_rates, $data);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }



    private function _get_rate_from_db($code, $time_limit = FALSE, $key = false)
    {
        $res = $this->db
                ->where('payment_systems_code', $code)
                ->get($this->table_payment_systems_rates)
                ->row();

        if($time_limit !== FALSE)
        {
            $time_limit = ($time_limit === TRUE) ? self::PAYMENT_SYSTEM_TIME_LIVE : $time_limit;

            $date = strtotime($res->date);

            if(time() - $date > $time_limit*3600)
            {
                return false;
            }
        }

        if($key === FALSE)
        {
            return $res;
        }

        if(isset($res->{$key}))
        {
            return $res->{$key};
        }

        return false;
    }



    private function _set_rate($code, $rate)
    {
        if($rate <= 0)
        {
            return true;
        }
        
        try
        {
            $this->db->trans_start();

            $this->db
                ->where('payment_systems_code', $code)
                ->delete($this->table_payment_systems_rates);

            $data = array(
                'payment_systems_code' => $code,
                'rate' => $rate,
                'date' => date('Y-m-d H:i:s'),
            );

            $this->db
                ->insert($this->table_payment_systems_rates, $data);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }
    }


    // удаление платежной системы
    public function remove_payment_system($id){

        try {
            //$this->db->where('id', $id)->delete($this->table_payment_systems);
            $this->db->where('id', $id)
                    ->limit(1)
                    ->update($this->table_payment_systems,array('active'=>0));
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;

    }

    // редактирование платежной системы
    public function edit_payment_system($id, $data){

        try {
            $this->db->where('id', $id)
                    ->limit(1)
                    ->update($this->table_payment_systems,$data);
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;

    }

    // добавление платежной системы
    public function create_payment_system($data){

        try {
            $this->db->insert($this->table_payment_systems,$data);
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;

    }


    // удаление группы  платежной системы
    public function remove_payment_system_group($id){

        $g = $this->db->where('parent_id', $id)->get($this->table_payment_systems_groups)->result();
        if ( !empty($g)  )
            return FALSE;

        $ps = $this->db->where('group_id', $id)->get($this->table_payment_systems)->result();
        if ( !empty($ps)  )
            return FALSE;

        try {
            $this->db->where('id', $id)->delete($this->table_payment_systems_groups);
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;

    }

    // редактирование группы  платежной системы
    public function edit_payment_system_group($id, $data){

        try {
            $this->db->where('id', $id)
                    ->limit(1)
                    ->update($this->table_payment_systems_groups,$data);
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;

    }

    // добавление группы платежной системы
    public function create_payment_system_group($data){

        try {
            $this->db->insert($this->table_payment_systems_groups,$data);
        } catch (Exception $e){
            self::$last_error = $e;
            return FALSE;
        }
        return TRUE;
    }

    #admin only
    //Ставит флаги show & show_operator = 0
    public function close_inactive_chats()
    {
        //1. Получить список активных чатов, сгруппировать по номерам заявок
        $problem_chats =    $this->db->select(array( 'order_id', 'user_id','id' ) )
                                     ->where('show_operator',1)
                                     ->group_by('order_id')
//                                     ->limit(5)
                                     ->get( $this->table_problem_chat )
                                     ->result();

        if( empty( $problem_chats ) )
            return FALSE;

//        var_dump($problem_chats);
//
//        echo "<br>";
//        echo "<br>";

        $order_ids_by_users = [];
        foreach( $problem_chats as $pc)
            $order_ids_by_users[ $pc->order_id ] = $pc->user_id;

        $problem_order_ids = array_keys( $order_ids_by_users );

        #from archive
        $order_ids = $this->db->select( array('seller_user_id','buyer_user_id', 'id', 'status') )
                              ->where_in( 'id', $problem_order_ids )
                              ->get( $this->table_orders_arhive )
                              ->result();

        if( empty( $order_ids ) )
            return FALSE;

        //2. Проверить статус заявки. Если она в статусах:
        //60, 70, 81 - 85, 90, 100
        $chats_to_close = [];
        $out_of_statuses = [];
        list($chats_to_close, $out_of_statuses) = $this->check_orders( $order_ids, $order_ids_by_users, $chats_to_close);

        #from originals
        if( !empty( $out_of_statuses ) )
        {
            $order_ids = $this->db->select( array('seller_user_id','buyer_user_id', 'id', 'status') )
                                  ->where_in( 'id', $out_of_statuses )
                                  ->get( $this->table_orders )
                                  ->result();

            if( !empty( $order_ids ) )
                list($chats_to_close, $out_of_statuses) = $this->check_orders( $order_ids, $order_ids_by_users, $chats_to_close);
        }

//        var_dump($chats_to_close);
//        die;


//        return;
        //3. Закрыть все чаты из списка для пользователей и операторов
        $this->db->where_in('order_id', $chats_to_close)
                ->update($this->table_problem_chat, array( 'show' => 0, 'show_operator' => 0 ));
    }

    public function check_orders( $order_ids, $order_ids_by_users, $chats_to_close = [] )
    {
//        echo "----check_orders-----<br>";
        $statuses = [10, 60, 70, 81, 82, 83, 84, 85, 90, 100];
        $out_of_statuses = array();

        foreach( $order_ids as $o )
        {
            if( !isset( $order_ids_by_users[ $o->id ] ) ){
//                echo "!order_ids_by_users $o->id<br>";
//                var_dump($o);
//                echo "<br>";
                continue;
            }

            if( $order_ids_by_users[ $o->id ] != $o->seller_user_id &&
                $order_ids_by_users[ $o->id ] != $o->buyer_user_id )
            {
//                echo "seller_order_id buyer_order_id $o->id<br>";
//                var_dump($o);
//                echo "<br>";
                continue;
            }


            if( !in_array( $o->status, $statuses ) )
            {
//                echo "!in_statuses($o->status) $o->id<br>";
                $out_of_statuses[] = $o->id;
                continue;
            }

            $chats_to_close[] = $o->id;
        }
        return [$chats_to_close, $out_of_statuses];
    }

    //Проверить все чаты и сделать их активными (show_operator = 1), если заявка активна
    public function check_all_chats()
    {
        //1. Получить список активных чатов, сгруппировать по номерам заявок
        $problem_chats =    $this->db->select(array( 'order_id', 'user_id','id' ) )
//                                     ->where('show',1)
                                     ->group_by('order_id')
//                                     ->limit(5)
                                     ->get( $this->table_problem_chat )
                                     ->result();

        if( empty( $problem_chats ) )
            return FALSE;

//        var_dump($problem_chats);
//
//        echo "<br>";
//        echo "<br>";

        $order_ids_by_users = [];
        foreach( $problem_chats as $pc)
            $order_ids_by_users[ $pc->order_id ] = $pc->user_id;

        $problem_order_ids = array_keys( $order_ids_by_users );

        $order_ids = $this->db->select( array('seller_user_id','buyer_user_id', 'id', 'status') )
                              ->where_in( 'id', $problem_order_ids )
                              ->get( $this->table_orders_arhive )
                              ->result();

        if( empty( $order_ids ) )
            return FALSE;

        //2. Проверить статус заявки. Если она в статусах:
        //60, 70, 81 - 85, 90, 100
        $statuses = [10, 60, 70, 81, 82, 83, 84, 85, 90, 100];
        $chats_to_close = [];
        foreach( $order_ids as $o )
        {
            if( !isset( $order_ids_by_users[ $o->id ] ) ){
//                echo "!order_ids_by_users $o->id<br>";
//                var_dump($o);
//                echo "<br>";
                continue;
            }

            if( $order_ids_by_users[ $o->id ] != $o->seller_user_id &&
                $order_ids_by_users[ $o->id ] != $o->buyer_user_id )
            {
//                echo "seller_order_id buyer_order_id $o->id<br>";
//                var_dump($o);
//                echo "<br>";
                continue;
            }

            if( in_array( $o->status, $statuses ) )
            {
//                echo "!in_statuses $o->id<br>";
//                var_dump($o);
//                echo "<br>";
                continue;
            }

            $problem_chats =    $this->db->select('id' )
                                     ->where('user_id',0)
                                     ->where('id_subject',0)
                                     ->where('to', $order_ids_by_users[ $o->id ] )
//                                     ->limit(5)
                                     ->order_by('id','DESC')
                                     ->limit(1)
                                     ->get( $this->table_problem_chat )
                                     ->row();
            if( !empty( $problem_chats ) )
            {
                echo "{$problem_chats->id}<br>";
                continue;
            }

            $chats_to_close[] = $o->id;
            echo "ADDED: {$o->id}<br>";
        }

//        var_dump($chats_to_close);
//        die;



        //3. Закрыть все чаты из списка
        $this->db->where_in('order_id', $chats_to_close)
                ->update($this->table_problem_chat, array( 'show_operator' => 1 ));
    }

    public function check_limit_input_payment_summ($buy_payment_systems, $sell_payment_systems, $buy_user_payment_summa, $sell_user_payment_summa, $choice_currecy_sell, $choice_currecy_buy)
    {
        $payment_systems = $this->get_all_payment_systems();

//        if(array_key_exists('wt', $buy_payment_systems))
        if($this->is_root_currency_in_array_key($buy_payment_systems))
        {
            $k = $this->get_root_currency_key_from_array($buy_payment_systems);
//            $summa_wt = $buy_user_payment_summa['wt'];
            $summa_wt = $buy_user_payment_summa[$k];
            $arr_ps = $sell_payment_systems;
            $arr_ps_summ = $sell_user_payment_summa;
            $choice_currecy = $choice_currecy_sell;
        }
//        elseif (array_key_exists('wt', $sell_payment_systems))
        elseif ($this->is_root_currency_in_array_key($sell_payment_systems))
        {
            $k = $this->get_root_currency_key_from_array($sell_payment_systems);
//            $summa_wt = $sell_user_payment_summa['wt'];
            $summa_wt = $sell_user_payment_summa[$k];
            $arr_ps = $buy_payment_systems;
            $arr_ps_summ = $buy_user_payment_summa;
            $choice_currecy = $choice_currecy_buy;
        }

        $t_bonus = $payment_systems[$k]->transaction_bonus;
//        vre($k);
//        vre($summa_wt);
//        pre($arr_ps);
//        pred($arr_ps_summ);

        foreach($arr_ps as $key => $val)
        {
            $amount = $arr_ps_summ[$key];

            $rate = 1;

            $ps = $payment_systems[$key];

//            if(!empty($ps->method_calc_fee) && method_exists($this, $ps->method_calc_fee))
//            {
//                $rate = $this->{$ps->method_calc_fee}($ps->machine_name, $ps->id);
//                $rate = 1/$rate;
//            }
//
//            if(643 == $ps->currency_id)
//            {
//                //тогда извлекаем курс доллара на сегодяня и переводим рубли в доллары
//                $rate = $this->get_exchange_rate_by_currency_char_code('USD');
//            }
//
//            if(980 == $ps->currency_id)
//            {
//                $rate = $rate = $this->get_exchange_rate_uah($ps->id);
//            }
            $choice_curr = isset($choice_currecy[$key])?$choice_currecy[$key]:false;

            $rate = $this->get_currency_rate($ps, $choice_curr);

            $amount = $amount/$rate;

            if($amount < $summa_wt*$this->cost_nominal_payment_account[$t_bonus])
            {
//                return [_e('currency_name_'.$ps->machine_name), $summa_wt*$this->cost_nominal_payment_account[$t_bonus]*$rate, _e('currency_id_'.$ps->currency_id)];
                return [
//                    _e('currency_name_'.$ps->machine_name),
                    Currency_exchange_model::get_ps($ps->id)->humen_name,
                    $summa_wt*$this->cost_nominal_payment_account[$t_bonus]*$rate,
//                    _e('currency_id_'.$ps->currency_id)
                    Currency_exchange_model::show_payment_system_code(['currency_id' => $ps->currency_id]),
                    TRUE
                    ];
            }

            if($amount > $summa_wt*$this->up_nominal_payment_account[$t_bonus])
            {
//                return [_e('currency_name_'.$ps->machine_name), $summa_wt*$this->cost_nominal_payment_account[$t_bonus]*$rate, _e('currency_id_'.$ps->currency_id)];
                return [
//                    _e('currency_name_'.$ps->machine_name),
                    Currency_exchange_model::get_ps($ps->id)->humen_name,
                    $summa_wt*$this->up_nominal_payment_account[$t_bonus]*$rate,
//                    _e('currency_id_'.$ps->currency_id)
                    Currency_exchange_model::show_payment_system_code(['currency_id' => $ps->currency_id]),
                    FALSE
                    ];
            }
        }

        return true;
    }



    public function check_payment_systems_limit($sell_ps, $sell_summa, $buy_ps, $buy_summa, $select_curency_sell_ps, $select_curency_buy_ps, $full_res = FALSE)
    {
//        pre($sell_ps);
//        pre($buy_ps);
//        vre($this->get_root_currency_key_from_array($sell_ps));
//        vre($this->get_root_currency_key_from_array($buy_ps));
//        pred($sell_ps, $sell_summa, $buy_ps, $buy_summa, $select_curency_sell_ps, $select_curency_buy_ps);

        if($this->get_root_currency_key_from_array($sell_ps) || $this->get_root_currency_key_from_array($buy_ps))
        {
            return false;
        }

        foreach($sell_ps as $k => $v)
        {
            $choice_currecy = isset($select_curency_sell_ps[$k])?$select_curency_sell_ps[$k]:false;

            $rate = $this->get_currency_rate(self::get_ps($k), $choice_currecy);

            $amount = $sell_summa[$k]/$rate;

            if($amount > 1000)
            {
                
//                vre(1);
                if( !$full_res ) return true;
                return [ true, $k ];
            }
        }

        foreach($buy_ps as $k => $v)
        {
            $choice_currecy = isset($select_curency_buy_ps[$k])?$select_curency_buy_ps[$k]:false;

            $rate = $this->get_currency_rate(self::get_ps($k), $choice_currecy);

            $amount = $sell_summa[$k]/$rate;

            if($amount > 1000)
            {
//                vre(2);
                if( !$full_res ) return true;
                return [ true, $k ];
            }
        }

        
        if( !$full_res ) return false;
        return [ FALSE ];
    }


    public function set_wm_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'USD':
                return [(boolean)preg_match('|^[Z][0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат WMZ кошелька.')];
            break;

            case 'UAH':
                return [(boolean)preg_match('|^[U][0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат WMU кошелька.')];
            break;

            case 'EUR':
                return [(boolean)preg_match('|^[E][0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат WME кошелька.')];
            break;

            case 'BYR':
                return [(boolean)preg_match('|^[B][0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат WMB кошелька.')];
            break;

            case 'RUB':
                return [(boolean)preg_match('|^[R][0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат WMR кошелька.')];
            break;
        }
    }

    public function get_left_side_p2p_status()
    {
        $current_user_id = $this->accaunt->get_user_id();
        return in_array( $current_user_id, $this->p2p_test_users );
    }
    public function get_left_side_p2p() {
        if( 0 )
        {
            $data = array();
            $data['last_deal'] = $this->get_last_complete_deal_data();

            if( empty( $data['last_deal'] ) ) return FALSE;


            return $this->load->view('user/accaunt/currency_exchange/modules/left_side__last_deal.php', $data, true);
        }

        $data = array();
        $data['last_deals'] = $this->get_last_deal_items( null, FALSE, 50 );

        if( empty( $data['last_deals'] ) ) return FALSE;

        return $this->load->view('user/accaunt/currency_exchange/modules/left_side__last_deals.php', $data, true);
    }

    public function get_last_deal_items( $dt= null, $json = TRUE, $count = 10 )
     {

         $last_deals_path_file = $this->get_last_deal_path();

         $file_name = $last_deals_path_file."result.json";

         $file_names = file_get_contents( $file_name );

         $parts = explode('|', $file_names);
         $file_name = $parts[0];
         $next_update = $parts[1];

         if( empty( $file_name ) || !file_exists( $last_deals_path_file.$file_name."-$count.json" ) ||
             $next_update <= time() )
         {
             $file_name = $this->generate_last_deal_file($count);
         }

         //вызываем не первый раз или этот файл со сделкой существует
         if( !empty( $dt ) && !empty( $file_name ) && $file_name == $dt && $count == 10 )
             return NULL;//$this->ajax_responce();

        $file_name = $last_deals_path_file.$file_name."-$count.json";
        $content = file_get_contents( $file_name );

        $non_json_content = json_decode($content);
        $non_json_content->interval = 3 * 60* 1000;

        if( $json ){
            return json_encode($non_json_content);
        }
        return $non_json_content;
     }

    //КРОН генерация данных для таблицы Последние сделки
    public function cron_last_deals_generator( $timeframe = 24 )
    {
        if( empty($timeframe) ) $timeframe = 24;

        $total_sec = $timeframe * 60 * 60;

        //<editor-fold defaultstate="collapsed" desc="Генерация интервалов">
            $intervals = [];
            $overal_time = 0;

            $min_rand_sec = 10;
            $max_rand_sec = 5*60;

            $last = $this->db->select('datetime')
                    ->order_by('id','DESC')
                    ->limit(1)
                    ->get($this->table_last_deals)
                    ->row();

            //            $start_time = date( 'H:i:s' );
            $date = time() - 20 * 60;//strtotime( date( 'd/m/Y H:i:s' ) );
            if( !empty( $last ) )
            {
                $date = $last->datetime;
                if( $date > time() + 23 * 60 * 60 ) return false;
            }

            $counter = 0;
            do
            {
                $t = rand( $min_rand_sec, $max_rand_sec );
                $overal_time += $t;


                $dt = $date + $overal_time;

                $intervals[] = [ 'datetime' => $dt ];

                $counter++;
            }while( $overal_time < $total_sec );
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Генерация данных о сделках">

            $last_completed_deals_data = $this->get_last_completed_deals_data( round($counter * 1.2), FALSE);

            if( empty( $last_completed_deals_data ) ) return FALSE;
            $last_completed_deals_data_keys = array_keys($last_completed_deals_data);
            shuffle( $last_completed_deals_data_keys );


            $ci = count( $intervals );
            //сделок меньше, чем интервалов
            if( count( $last_completed_deals_data_keys ) < $ci ) $ci = count( $last_completed_deals_data_keys );

            //'0x20,0x09,0x0A'
            //$trim_mask = chr(0x09).chr(0x20).chr(0x0A).chr(0x0D);
            for( $i = 0; $i < $ci; $i++ )
            {
                $id = $last_completed_deals_data_keys[$i];
                $ld = $last_completed_deals_data[ $id ];

                $ld['date'] = date('d/m/Y', $intervals[$i]['datetime']);
                $ld['time'] = date('H:i:s', $intervals[$i]['datetime']);

                $rend = $this->load->view('user/accaunt/currency_exchange/modules/left_side__last_deals__item.php', ['last_deal'=>$ld], true);

                $intervals[$i]['deal_data'] = json_encode( $ld );
                $intervals[$i]['order_id'] = $id;

                $intervals[$i]['data'] = json_encode( $rend );
                $intervals[$i]['orig'] = 0;
            }
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Сохранение данных о сделках">
            $insert = [];
            $batch_count = 20;
            if( $batch_count > $ci ) $batch_count = $ci;

            for( $i = 0; $i < $ci; $i += $batch_count )
            {
                //записываем каждые 10 шт
                $insert = array_splice($intervals, $i, $batch_count);

                $this->add_last_completed_deal_data( $insert );
            }
        //</editor-fold>
    }

    //КРОН получение последних сделок
    public function add_one_new_deal( $id )
    {
        if( empty($id)  ) return FALSE;
        $ld = $this->db ->where('original_order_id',$id)
                        ->where('status', 60)
                        ->order_by('buyer_confirmation_date','DESC')
                        ->order_by('initiator',1)
                        ->limit(1)
                        ->get($this->table_orders_arhive)
                        ->row();


        $p_data = $this->prepare_last_completed_deals_data( $ld, FALSE, time() );

        if( empty( $p_data ) ) return FALSE;
//
//        $p_data['date'] = date('d/m/Y', time());
//        $p_data['time'] = date('H:i:s', time());

        $rend = $this->load->view('user/accaunt/currency_exchange/modules/left_side__last_deals__item.php', ['last_deal'=>$p_data], true);

        if( empty( $rend ) ) return FALSE;

        $batch_items = [ 'datetime' => time() ];
        $batch_items['data'] = json_encode( $rend );
        $batch_items['deal_data'] = json_encode( $p_data );
        $batch_items['order_id'] = $id;
        $batch_items['orig'] = 1;

        $this->add_last_completed_deal_data( [$batch_items] );

        $this->generate_last_deal_file();

    }
    //25-02-2016 КРОН получение последних сделок
    public function prepare_last_completed_deals_data_v2( $ld, $cron = FALSE, $time = NULL)
    {
        
        $res = array();
        $res['from'] = array();
        $res['to'] = array();

        if( empty($time) ) $time = strtotime($ld->buyer_confirmation_date);

        if( empty( $ld->sell_system ) || empty( $ld->payed_system )) return FALSE;

        #new block
        $order = $this->_get_user_orders_arhive_details_one( $ld );        
        
        $sell_ps_data = $this->get_ps_data( $order->sell_payment_data_un[$order->sell_system] );
        $buy_ps_data = $this->get_ps_data( $order->buy_payment_data_un[$order->payed_system] );        
        #/new block

        
        if( $sell_ps_data['root_currency'] == 1 )
        {
            $a = $sell_ps_data;
            $sell_ps_data = $buy_ps_data;
            $buy_ps_data = $a;
            
            $a = $ld->sell_system;
            $ld->sell_system = $ld->payed_system;
            $ld->payed_system = $a;
        }
        
        $res['from']['amount'] = $sell_ps_data['amount'];
        $res['to']['amount'] = $buy_ps_data['amount'];
        
        $sell_system = Currency_exchange_model::get_ps($ld->sell_system);
        $payed_system = Currency_exchange_model::get_ps($ld->payed_system);
        
        if( empty( $sell_system->machine_name ) || empty( $sell_system->currency_id ) ) return FALSE;
        if( empty( $payed_system->machine_name ) || empty( $payed_system->currency_id ) ) return FALSE;

        $res['from']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$sell_system->public_path_to_icon;
        $res['from']['name'] = $sell_system->humen_name;
        $res['from']['currency_name'] = _e( 'currency_id_'.$sell_ps_data['currency_id'] );


        $res['to']['root'] = $buy_ps_data['root_currency'];
        $res['to']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$payed_system->public_path_to_icon;
        $res['to']['name'] = $payed_system->humen_name;
        $res['to']['currency_name'] = _e( 'currency_id_'.$buy_ps_data['currency_id'] );

        $res['date'] = date('d/m/Y', $time);
        $res['time'] = date('H:i:s', $time);

        return $res;
    }
    public function prepare_last_completed_deals_data( $ld, $cron = FALSE, $time = NULL){
        return $this->prepare_last_completed_deals_data_v2( $ld, $cron, $time);        
    }
    public function prepare_last_completed_deals_data_v1( $ld, $cron = FALSE, $time = NULL)
    {
        $res = array();
        $res['from'] = array();
        $res['to'] = array();

        if( empty($time) ) $time = strtotime($ld->buyer_confirmation_date);

        if( empty( $ld->seller_amount ) || empty( $ld->buyer_amount_down ) ||
            empty( $ld->sell_system ) || empty( $ld->payed_system )) return FALSE;

        #ошибка неверных сумм
        if( $ld->buyer_amount_down == $ld->seller_amount )
        {
            $ld2 = $this->db ->where('id',$ld->original_order_id)
                        ->get($this->table_orders)
                        ->row();

            if( $ld2->seller_user_id == $ld->seller_user_id )
            {
                $ld->buyer_amount_down = $ld2->buyer_amount_down;
                $ld->seller_amount = $ld2->seller_amount;
            }else
            {
                $ld->buyer_amount_down = $ld2->seller_amount;
                $ld->seller_amount = $ld2->buyer_amount_down;
            }
        }
        #/ошибка неверных сумм


        $res['from']['amount'] = $ld->seller_amount;
        $res['to']['amount'] = $ld->buyer_amount_down;

        #на вход подали данные оригинала
        if( $ld->initiator == 1 )
        {
            $res['from']['amount'] = $ld->buyer_amount_down;
            $res['to']['amount'] = $ld->seller_amount;
        }
        #/на вход подали данные оригинала

        $sell_system = Currency_exchange_model::get_ps($ld->sell_system);
        $payed_system = Currency_exchange_model::get_ps($ld->payed_system);

        #в детялях заявки выбрана другая валюта
        $order_details_arhiv = unserialize($ld->order_details_arhiv);

        foreach( $order_details_arhiv as $oa )
        {
            if( empty( $oa->choice_currecy ) ) continue;

            if( $oa->payment_system == $ld->payed_system ){
                $payed_system->currency_id = $oa->choice_currecy;
                continue;
            }

            if( $oa->payment_system == $ld->sell_system ){
                $sell_system->currency_id = $oa->choice_currecy;
                continue;
            }
        }
        #/в детялях заявки выбрана другая валюта

        if( empty( $sell_system->machine_name ) || empty( $sell_system->currency_id ) ) return FALSE;
        if( empty( $payed_system->machine_name ) || empty( $payed_system->currency_id ) ) return FALSE;

        $res['from']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$sell_system->public_path_to_icon;
        $res['from']['name'] = $sell_system->humen_name;
        $res['from']['currency_name'] = _e( 'currency_id_'.$sell_system->currency_id );


        $res['to']['root'] = $payed_system->root;
        $res['to']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$payed_system->public_path_to_icon;
        $res['to']['name'] = $payed_system->humen_name;
        $res['to']['currency_name'] = _e( 'currency_id_'.$payed_system->currency_id );


        $res['date'] = date('d/m/Y', $time);
        $res['time'] = date('H:i:s', $time);


        return $res;
    }

    //КРОН получение последних сделок
    public function get_last_completed_deals_data( $counter, $filter = FALSE )
    {
        if( empty( $counter ) ) $counter = 500;

        $last_complete_deal = $this->db ->where('status', 60)
//                                        ->where('wt_set', 1)
                                        ->where('sell_system >', 0)
                                        ->where('( discount > -100 AND discount < 0)')
                                        ->where('payed_system >', 0)
                                        ->order_by('buyer_confirmation_date','DESC')
                                        ->group_by('original_order_id')
                                        ->limit($counter)
                                        ->get($this->table_orders_arhive)
                                        ->result();
        
        $deals = [];
        foreach( $last_complete_deal as $ld )
        {
            $deals[$ld->original_order_id] = $this->prepare_last_completed_deals_data( $ld, TRUE );
            if( empty( $deals[$ld->original_order_id] ) ) unset( $deals[$ld->original_order_id] );
        }
        return $deals;

    }

    //КРОН добавление новых сделок
    public function add_last_completed_deal_data( $batch_items )
    {
        if( empty( $batch_items ) || !is_array($batch_items) ) return FALSE;

        $this->db->insert_batch($this->table_last_deals,$batch_items);
        return TRUE;
    }

    //получение последних Н сделок для генерации файла
    public function get_last_n_completed_deals($time = null, $limit = 10)
    {
        if( empty($time) ) $time = time();

        $ten = $this->db->select('datetime,data')
                ->where('datetime <=',$time)
                ->order_by('datetime','DESC')
                ->limit($limit)
                ->get($this->table_last_deals)
                ->result();

        $ten_rev = array_reverse( $ten );

        $res = $this->db->select('datetime,data')
                ->where('datetime >', $time)
                ->order_by('datetime','ASC')
                ->limit(1)
                ->get($this->table_last_deals)
                ->row();

        return [$ten_rev, $res ];
    }

    //КРОН, генерирует последние сделки за 24 часа
    public function get_last_complete_deal_data_old()
    {
        $last_complete_deal = $this->db//->select('')
                                        ->where('status', 60)
                                        //->where('id','93093')
//                                        ->limit(5)
                                        ->order_by('buyer_confirmation_date','DESC')
                                        ->get($this->table_orders_arhive)
                                        ->row();

        /*
        $res = array();
        $time = strtotime($last_complete_deal->buyer_confirmation_date);

        $res['from']['amount'] = $last_complete_deal->seller_amount;
        $res['to']['amount'] = $last_complete_deal->buyer_amount_down;


        $all_payment_systems = $this->get_payment_systems_by_id( [$last_complete_deal->sell_system, $last_complete_deal->payed_system ] );
        $res['from']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$all_payment_systems[0]->public_path_to_icon;
//        $res['from']['name'] = _e( 'currency_name_'.$all_payment_systems[0]->machine_name );
        $res['from']['name'] = Currency_exchange_model::get_ps($all_payment_systems[0]->machine_name)->humen_name;
        $res['from']['currency_name'] = _e( 'currency_id_'.$all_payment_systems[0]->currency_id );

        $res['to']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$all_payment_systems[1]->public_path_to_icon;
//        $res['to']['name'] = _e( 'currency_name_'.$all_payment_systems[1]->machine_name );
        $res['to']['name'] = Currency_exchange_model::get_ps($all_payment_systems[1]->machine_name)->humen_name;
        $res['to']['currency_name'] = _e( 'currency_id_'.$all_payment_systems[1]->currency_id );


        $res['date'] = date('d/m/Y', $time);
        $res['time'] = date('H:i:s', $time);
        */

        $res = $this->prepare_last_completed_deals_data($last_complete_deal, FALSE);

        return $res;
    }

    public function get_last_complete_deal_data( $last_complete_deal = null )
    {
        if( empty( $last_complete_deal ) )
        {
            $last_complete_deal = $this->db//->select('')
                                            ->where('status', 60)
                                            //->where('id','93093')
    //                                        ->limit(5)
                                            ->order_by('buyer_confirmation_date','DESC')
                                            ->get($this->table_orders_arhive)
                                            ->row();
        }
        /*

        $res = array();
        $time = strtotime($last_complete_deal->buyer_confirmation_date);

        $res['from']['amount'] = $last_complete_deal->seller_amount;
        $res['to']['amount'] = $last_complete_deal->buyer_amount_down;

        //$all_payment_systems = $this->get_payment_systems_by_id( [$last_complete_deal->sell_system, $last_complete_deal->payed_system ] );

        $sell_system = Currency_exchange_model::get_ps($last_complete_deal->sell_system);
        $payed_system = Currency_exchange_model::get_ps($last_complete_deal->payed_system);

        #в детялях заявки выбрана другая валюта
        $order_details_arhiv = unserialize($last_complete_deal->order_details_arhiv);

        foreach( $order_details_arhiv as $oa )
        {
            if( empty( $oa->choice_currecy ) ) continue;

            if( $oa->payment_system == $last_complete_deal->payed_system )
                $payed_system->currency_id = $oa->choice_currecy;

            if( $oa->payment_system == $last_complete_deal->sell_system )
                $sell_system->currency_id = $oa->choice_currecy;
        }
        #/в детялях заявки выбрана другая валюта


        $res['from']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$sell_system->public_path_to_icon;
//        $res['from']['name'] = _e( 'currency_name_'.$all_payment_systems[0]->machine_name );
        $res['from']['name'] = $sell_system->humen_name;
        $res['from']['currency_name'] = _e( 'currency_id_'.$sell_system->currency_id );

        $res['to']['bg'] = "background-image: url('/images/currency_exchange/spritesheet.png');".$payed_system->public_path_to_icon;
//        $res['to']['name'] = _e( 'currency_name_'.$all_payment_systems[1]->machine_name );
        $res['to']['name'] = $payed_system->humen_name;
        $res['to']['currency_name'] = _e( 'currency_id_'.$payed_system->currency_id );


        $res['date'] = date('d/m/Y', $time);
        $res['time'] = date('H:i:s', $time);
        */
        $res = $this->prepare_last_completed_deals_data($last_complete_deal, FALSE);

        return $res;
    }

    //возвращает путь, по которому хранится файл с json
    public function get_last_deal_path()
    {
        return $this->last_deals_path_file;
    }

    //генерирует файл для модуля с заявками
    public function generate_last_deal_file($count = 10)
     {
         $this->load->model('currency_exchange_model','currency_exchange');
         list($deals,$next_update_time) = $this->get_last_n_completed_deals(null,$count);


         if( empty( $deals ) ) return FALSE;

         $uri = '/account/currency_exchange/ajax/get_last_deal_items';
         $interval = 25000;//ms

         $last_one_dt = $deals[ count( $deals ) - 1 ]->datetime;
         $next_update = $next_update_time->datetime;

         $json_deals = json_encode( [ 'success' => $deals, 'count' => count( $deals ), 'last_dt' => $last_one_dt, 'interval' => $interval, 'uri' => $uri ]);

         $f = fopen($this->last_deals_path_file.$last_one_dt."-$count.json", 'w');
         if( !fwrite($f, $json_deals) ) return FALSE;
         fclose($f);

         $f = fopen($this->last_deals_path_file.'result.json', 'w');
         $str = "$last_one_dt|$next_update";
         if( !fwrite($f, $str) ) return FALSE;
         fclose($f);

         return $last_one_dt;
     }

    public function set_paypal_ps_data($currency, $string = 'USD')
    {
        switch ($currency)
        {
            case 'USD':
                return [(boolean)preg_match('|^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+$|', $string), _e('Неверный формат E-mail.')];
            break;

            case 'RUB':
                return [(boolean)preg_match('|^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+$|', $string), _e('Неверный формат E-mail.')];
            break;
        }
    }

    #импорт таблицы завершенных заявок в CSV
    public function save_completed_orders()
    {
        
        $completed_orders_data = 
        $this->db->get($this->table_completed_orders_data)
                ->result();
        
        if( empty( $completed_orders_data ) ) return false;
        
        $res = [];
        $res[] = implode(';',[
            "ID",
            "Date",
            "WT set",
            "Give Currency",
            "Give PS",
            "Give Amount",
            "Get Currency",
            "Get PS",
            "Get Amount",
            "Status",
            "Discount"
            ] );
        
        foreach( $completed_orders_data as &$row )
        {
            $wt_set = 'non WT';
            
            switch( $row->wt_set )
            {
                case 0: $row->wt_set = 'non WT';
                    break;
                case 1: $row->wt_set = 'Sell WT';
                    break;
                case 2: $row->wt_set = 'Buy WT';
                    break;
                
            }
            
            $row->discount = str_replace('.', ',', $row->discount);
            $row->get_amount = str_replace('.', ',', $row->get_amount);
            $row->give_amount = str_replace('.', ',', $row->give_amount);
            
            $res[] = implode( ';', (array)$row );
        }
        
        
        return implode( "\n", $res );
    }
    
    #создание таблицы из завершенных заявок, Админка
    public function generate_completed_orders( $date_from, $date_to, $ps_id )
    {
        if( empty( $date_from ) || empty( $date_to )  ) return ['result' => FALSE, 'text' => ''];
        
        if( strtotime( $date_from ) > strtotime( $date_to ) ) 
            return ['result' => FALSE, 'text' => 'День даты начала должна быть раньше дня датой окончания периода.'];
     
        ini_set('memory_limit','15G');
        ini_set('max_execution_time', 60*30);
        
#добавть колонку и в нее при помощи скрипта перегнать все даты в нормальном формате    #remove    
        #1. Получение данных
        $completed_orders = 
        $this->db->select(['id','wt_set','status','sell_payment_data','buy_payment_data','buyer_get_money_date','seller_get_money_date', 
            'discount', 'sell_system','payed_system','order_details_arhiv','original_order_id'])
                ->where('buyer_get_money_date <=', $date_to )
                ->where('buyer_get_money_date >=', $date_from )
                ->where('(status = 60 OR status = 84)', NULL )
                ->where('bonus', 6 )
                ->where('initiator', 1 )
                
                ->where("(sell_system = $ps_id OR payed_system = $ps_id)", NULL )                
        
//->limit(2)#remove
                
                ->order_by('id','DESC')
                ->get($this->table_orders_arhive)
                ->result();
        
        
        if( empty( $completed_orders ) )
        {
            return ['result' => FALSE, 'text' => 'За выбранный период нет данных'];
        }
        
        #2. Генерация данных
        $all_currencys_key_num = $this->get_all_currencys_key_num();
        
        $new_data = [];
        foreach( $completed_orders as &$o )
        {
            $o = $this->set_dop_data_to_unserializ_payment_system($o);
            $o = $this->set_machin_name_to_payment_systems($o);
            
            if( isset( $new_data[$o->original_order_id] ) ) continue;
            
            $give = $o->buy_payment_data_un[ $o->payed_system ];
            if( !empty( $give->choice_currecy ) ) $give->currency_id = $give->choice_currecy;
            
            $give_ps = self::get_ps( $give->payment_system_id );
            
            
            $get = $o->sell_payment_data_un[ $o->sell_system ];
            if( !empty( $get->choice_currecy ) ) $get->currency_id = $get->choice_currecy;
            
            $get_ps = self::get_ps( $get->payment_system_id );
            
            if( empty( $o ) || empty( $all_currencys_key_num ) ||
                empty( $give_ps ) || empty( $get_ps ) ||
                !isset( $all_currencys_key_num[$give->currency_id] ) ||
                !isset( $all_currencys_key_num[$get->currency_id] ) 
            ){
                continue;
            }
            
            $date = '1111-11-11 11:11:11';
            
            if( $o->wt_set == self::WT_SET_SELL ) $date = $o->seller_get_money_date;
            else
                if( $o->wt_set == self::WT_SET_BUY ) $date = $o->buyer_get_money_date;
            
            $new_data[$o->original_order_id] = [
                'id' => $o->original_order_id,
                'date' => $date,
                'wt_set' => $o->wt_set,
                
                'give_currency' => $all_currencys_key_num[$give->currency_id]->code,
                'give_ps' => $give_ps->humen_name,
                'give_amount' => $give->summa,
                
                'get_currency' => $all_currencys_key_num[$get->currency_id]->code,
                'get_ps' => $get_ps->humen_name,
                'get_amount' => $get->summa,
                
                'status' => $o->status,
                'discount' => $o->discount,                
            ];
        }
//vred('$new_data',$new_data);#remove
        #3. Запись данных
        
        $this->db->trans_start();
            $this->db->truncate( $this->table_completed_orders_data );

            $insert_batch = [];
            $new_data_count = count( $new_data );
            $i = 0;
            foreach( $new_data as $nd )
            {
                $insert_batch[] = $nd;
                $i++;

                if( $i % 1000 == 0 || $i >= $new_data_count -1 )
                {                    
                    $this->db->insert_batch( $this->table_completed_orders_data, $insert_batch);
                    $insert_batch = [];
                }
                
            };
        $this->db->trans_complete();
        
        return ['result' => TRUE, 'text' => 'Генерация выполнена' ];
    }
    

//    public function set_qiwi_ps_data($currency, $string, $user_id = false)
    public function set_qiwi_ps_data($currency, $string)
    {
//        if($user_id === false)
//        {
//            $user_id = $this->account->get_user_id();
//        }
//
//        $this->load->model('users_model', 'users');
//        $user_data = $this->users->getUserData($user_id);
//
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^[0-9]{6,19}[0-9]{1,1}$|', $string), _e('Неверный формат номера телефона.')];
            break;
        }
    }



    public function set_visa_qiwi_wallet_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^[0-9]{6,19}[0-9]{1,1}$|', $string), _e('Неверный формат номера телефона.')];
            break;

            case 'USD':
                return [(boolean)preg_match('|^[0-9]{6,19}[0-9]{1,1}$|', $string), _e('Неверный формат номера телефона.')];
            break;

            case 'KZT':
                return [(boolean)preg_match('|^[0-9]{6,19}[0-9]{1,1}$|', $string), _e('Неверный формат номера телефона.')];
            break;

            case 'EUR':
                return [(boolean)preg_match('|^[0-9]{6,19}[0-9]{1,1}$|', $string), _e('Неверный формат номера телефона.')];
            break;
        }
    }



    public function set_perfect_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^U[0-9]{6,6}[0-9]{1,1}$|', $string), _e('Неверный формат Perfect')];
            break;

            case 'USD':
                return [(boolean)preg_match('|^U[0-9]{6,6}[0-9]{1,1}$|', $string), _e('Неверный формат Perfect')];
            break;
        }
    }




    public function set_w1_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^[0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат W1')];
            break;

            case 'USD':
                return [(boolean)preg_match('|^[0-9]{11,11}[0-9]{1,1}$|', $string), _e('Неверный формат W1')];
            break;
        }
    }



    public function set_okpay_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;

            case 'USD':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;

            case 'EUR':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;

            case 'GBP':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;
            case 'CAD':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;

            case 'CZK':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;

            case 'CNY':
                return [(boolean)preg_match('|^OK[0-9]{8,8}[0-9]{1,1}$|', $string), _e('Неверный формат Okpay')];
            break;
        }
    }



    public function set_payeer_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'RUB':
                return [(boolean)preg_match('|^P[0-9]{7,7}[0-9]{1,1}$|', $string), _e('Неверный формат Payeer')];
            break;

            case 'USD':
                return [(boolean)preg_match('|^P[0-9]{7,7}[0-9]{1,1}$|', $string), _e('Неверный формат Payeer')];
            break;
            case 'EUR':
                return [(boolean)preg_match('|^P[0-9]{7,7}[0-9]{1,1}$|', $string), _e('Неверный формат Payeer')];
            break;
        }
    }

    public function set_skrill_ps_data($currency, $string)
    {
        switch ($currency)
        {
            case 'USD':
                return [(boolean)preg_match('|^[0-9]+[0-9]{1,1}$|', $string), _e('Неверный формат Skrill')];
            break;
            case 'EUR':
                return [(boolean)preg_match('|^[0-9]+[0-9]{1,1}$|', $string), _e('Неверный формат Skrill')];
            break;
        }
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_btc($string)
    {
        $res = (boolean)preg_match('|^[0-9a-zA-Z]+[0-9a-zA-Z]$|', $string);
        return [$res, _e('Неверный формат Bitcoin')];
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_easypay_uah($string)
    {
        $res = (boolean)preg_match('|^[0-9]+[0-9]$|', $string);
        return [$res, _e('Неверный формат Easypay')];
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_payza_usd($string)
    {
        $res = (boolean)preg_match('|^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+$|', $string);
        return [$res, _e('Неверный формат Payza введите корректный E-mail')];
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_rbkmoney_usd($string)
    {
        $res = (boolean)preg_match('|^RU[0-9]{7,8}[0-9]{1,1}$|', $string) || (boolean)preg_match('|^[0-9]{9,9}[0-9]{1,1}$|', $string);
        return [$res, _e('Неверный формат RBKmoney')];
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_skrill_usd($string)
    {
        return $this->set_skrill_ps_data('USD', $string);
    }

    public function set_skrill_eur($string)
    {
        return $this->set_skrill_ps_data('EUR', $string);
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_payeer_usd($string)
    {
        return $this->set_payeer_ps_data('USD', $string);
    }

    public function set_payeer_rub($string)
    {
        return $this->set_payeer_ps_data('RUB', $string);
    }

    public function set_payeer_eur($string)
    {
        return $this->set_payeer_ps_data('EUR', $string);
    }
    //-----------------------------------------------------------------------------------------------------------
    public function set_wm_usd($string)
    {
        return $this->set_wm_ps_data('USD', $string);
    }

    public function set_wm_uah($string)
    {
        return $this->set_wm_ps_data('UAH', $string);
    }

    public function set_wm_eur($string)
    {
        return $this->set_wm_ps_data('EUR', $string);
    }

    public function set_wm_byr($string)
    {
        return $this->set_wm_ps_data('BYR', $string);
    }

    public function set_wm_rub($string)
    {
        return $this->set_wm_ps_data('RUB', $string);
    }
    //============================================================================================================

    public function set_vqw_rub($string)
    {
        return $this->set_visa_qiwi_wallet_ps_data('RUB', $string);
    }

    public function set_vqw_usd($string)
    {
        return $this->set_visa_qiwi_wallet_ps_data('USD', $string);
    }

    public function set_vqw_kzt($string)
    {
        return $this->set_visa_qiwi_wallet_ps_data('KZT', $string);$user_id = $this->account->get_user_id();
    }

    public function set_vqw_eur($string)
    {
        return $this->set_visa_qiwi_wallet_ps_data('EUR', $string);
    }

    public function set_qiwi_rub($string)
    {
        return $this->set_qiwi_ps_data('RUB', $string);
    }
    //================================================================================================================
    public function set_paypal_rub($string)
    {
        return $this->set_qiwi_ps_data('RUB', $string);
    }

    public function set_paypal_usd($string)
    {
        return $this->set_paypal_ps_data('USD', $string);
    }
    //================================================================================================================
    public function set_perfec_rub($string)
    {
        return $this->set_perfect_ps_data('RUB', $string);
    }

    public function set_perfec_usd($string)
    {
        return $this->set_perfect_ps_data('USD', $string);
    }
    //================================================================================================================
    public function set_w1_rub($string)
    {
        return $this->set_w1_ps_data('RUB', $string);
    }

    public function set_w1_usd($string)
    {
        return $this->set_w1_ps_data('USD', $string);
    }
    //================================================================================================================
    public function set_okpay_rub($string)
    {
        return $this->set_okpay_ps_data('RUB', $string);
    }

    public function set_okpay_usd($string)
    {
        return $this->set_okpay_ps_data('USD', $string);
    }

    public function set_okpay_eur($string)
    {
        return $this->set_okpay_ps_data('EUR', $string);
    }

    public function set_okpay_gbp($string)
    {
        return $this->set_okpay_ps_data('GBP', $string);
    }

    public function set_okpay_cad($string)
    {
        return $this->set_okpay_ps_data('CAD', $string);
    }

    public function set_okpay_czk($string)
    {
        return $this->set_okpay_ps_data('CZK', $string);
    }

    public function set_okpay_cny($string)
    {
        return $this->set_okpay_ps_data('CNY', $string);
    }
    //---------------------------------------------------------------------------
    public function set_sberbank($input_array, $ps)
    {
        $check_input_field = 0;

        $array_field['card'] =[ 'cart_number', 'card_recipient'];
        $array_field['requizites'] =[
            'bik',
            'rs',
            'ks',
            'name',
            'recipient',
//            'destination',
            ];

        foreach(self::get_all_fields_universal_bank() as $field)
        {
            $array_field['requizites_univesal'][] = $field[1];
        }

        $user_id = $this->account->get_user_id();

        $data = array(
            'user_id' => $user_id,
            'payment_system_id' => $ps->id,
        );

        $curent_ps_data = $this->db
                ->where($data)
                ->get($this->table_user_paymant_data)
                ->row();

        $payment_data = unserialize($curent_ps_data->payment_data);

        $payment_data = $this->_get_card_number_decode($payment_data);

        foreach($input_array as $key => $val)
        {
            if($key == 'selector' && in_array($val, ['card', 'requizites', 'requizites_univesal']))
            {
                continue;
            }

            if(!in_array($key, $array_field['card']) &&
                !in_array($key, $array_field['requizites']) &&
                !in_array($key, $array_field['requizites_univesal']) )
            {
                return [false, _e('Заполните все поля формы').'!'];
            }

            if(empty($val))
            {
                continue;
            }

            if(is_array($val) && !implode('', $val))
            {
                continue;
            }

            switch ($key)
            {
                case 'bik':
                    $res =  [(boolean)preg_match('|^[0-9]{8}[0-9]{1}$|', $val),
                        _e('В поле «БИК» должно быть 9 цифр.'),
                        'bik'];
                break;
                case 'rs':
                    $res =  [(boolean)preg_match('|^[0-9]{19,23}[0-9]{1}$|', $val),
                        _e('В поле «Расчетный счет» должно быть от 20 до 24 цифр.'),
                        'rs'];
                break;

                case 'ks':
                    $res =   [(boolean)preg_match('|^[0-9]{19,23}[0-9]{1}$|', $val),
                        _e('В поле «Кор. счет» должно быть от 20 до 24 цифр.'),
                        'ks'];
                break;

                case 'name':
                    $res =   [(boolean)preg_match('/^[A-z]|[А-яЁё]|[0-9,\.,\s]{6,150}[A-z]|[А-яЁё]|[0-9,\.,\s]{1}$/u', $val),
                        _e('В поле «Наименование Банка» должно быть только быквы и цифры.'),
                        'name'];
                break;

                case 'recipient':
                    $res =   [(boolean)preg_match('/^[A-z]|[А-яЁё]|[0-9,\.,\s]{6,150}[A-z]|[А-яЁё]|[0-9,\.,\s]{1}$/u', $val),
                        _e('В поле «Получатель» должно быть только быквы и цифры.'),
                        'recipient'
                        ];
                break;

                case 'cart_number':

                    if($val[4] == $payment_data[$key][4] &&
                       $val[2] == '****' &&
                       $val[3] == '****' &&
                       $val[1] == '****'
                            )
                    {
                        $input_array[$key] = $payment_data[$key];
                        $val = $payment_data[$key];
                    }

                    foreach($val as $k => $v)
                    {
                        if(!in_array($k, [1,2,3,4]))
                        {
                            $v = '';
                        }

                        $res =  [(boolean)preg_match('|^[0-9]{3}[0-9]{1}$|', $v),
                        _e('В поле «Номер карты» должны быть только цифры.'),
                        'cart_number['.$k.']'];

                        if($res[0] === false)
                        {
                            break;
                        }
                    }

                    if($res[0] !== false)
                    {
                        $res =  [(boolean)$this->_check_card_number(implode('-', $val)),
                        _e('Неверный «Номер карты»'),
                        'cart_number'];
                    }
                break;
                case 'card_recipient':
                    $res =  [(boolean)preg_match('/^[A-z]|\s{3,40}[0-9]{1}$/', $val),
                        _e('В поле «Получатель» только латинские буквы.'),
                        'card_recipient'];
                break;

                default :
                    continue;
            }

            if($res[0] === false)
            {
                return $res;
            }
        }

        if(in_array($input_array['selector'], ['card', 'requizites']))
        {
            foreach($array_field[$input_array['selector']] as $val)
            {
                if(empty($input_array[$val]))
                {
                    return [false, _e('Заполните все поля формы.')];
                }
            }
        }
        else
        {
            foreach($array_field[$input_array['selector']] as  $val)
            {
                $input_array_temp[$val] = $input_array[$val];
            }

            $res_universal = $this->_check_universal_bank_data($input_array_temp, $ps);

            if($res_universal[0] === false)
            {
                return $res_universal;
            }

            $country = $this->get_contry_by_id($ps->country_id);
            $input_array['wire_beneficiary_bank_country'] = _e($country->country_name_ru);
        }

        if(!empty($input_array['cart_number']))
        {
            $this->load->library('code');
            foreach ($input_array['cart_number'] as &$val)
            {
                $val = $this->code->code($val);
            }
            unset($val);
        }

        $res = serialize($input_array);

        $this->save_user_paymant_data_without_check($user_id, $ps->machine_name, $res);

        return [true, _e('Заполните все поля формы')];
    }

    static public function get_all_fields_universal_bank()
    {
        $fields = [
            [ _e('accaunt/profile_bank_1'), 'wire_beneficiary_name', 'wire_beneficiary_name', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_2'), 'wire_beneficiary_address', 'wire_beneficiary_address', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_3'), 'wire_beneficiary_bank', 'wire_beneficiary_bank', 'text', 3, true, true, true, true],
//                [ _e('accaunt/profile_bank_4').'<span class="req">*</span>', 'wire_beneficiary_bank_country', 'wire_beneficiary_bank_country', 'select_countries', 3, true, true, false, false, "id='select_countries'", get_country_list()],
            [ _e('accaunt/profile_bank_5'), 'wire_beneficiary_bank_address', 'wire_beneficiary_bank_address', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_6'), 'wire_sort', 'wire_sort', 'text', 3, true, true, true, true],

            [ _e('accaunt/profile_bank_7'), 'wire_beneficiary_account', 'wire_beneficiary_account', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_8'), 'wire_beneficiary_swift', 'wire_beneficiary_swift', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_9'), 'wire_corresponding_bank', 'wire_corresponding_bank', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_10'), 'wire_corresponding_bank_swift', 'wire_corresponding_bank_swift', 'text', 3, true, true, true, true],
            [ _e('accaunt/profile_bank_11'), 'wire_corresponding_account', 'wire_corresponding_account', 'text', 3, true, true, true, true],
        ];

        return $fields;
    }



    public function get_contry_by_id($id)
    {
        $countrys = $this->get_all_country_name();

        return $countrys[$id];
    }



    public function get_wire_form_name($country)
    {
        switch( $country->wire_form )
        {
            case 'EEA':
                $wire_form = 'eea';
                break;
            case 'NA':
            case 'SA':
                $wire_form = 'ea';
                break;
            case 'UK':
                $wire_form = 'uk';
                break;

            default:
                $wire_form = 'others';
                break;
        }

        return $wire_form;
    }



    private function _get_wire_bank_reqired_fileds( $var = '' )
    {
        $fields = array();

        $fields['ea'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
                        'wire_beneficiary_account',
                        'wire_beneficiary_swift',
                       );
        $fields['eea'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
                        'wire_beneficiary_account',
                        'wire_beneficiary_swift',
                       );
        //UK & Ireland
        $fields['uk'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
                        'wire_beneficiary_account',
                        'wire_sort',
                        'wire_beneficiary_swift',
                       );
        $fields['others'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
//                        'wire_beneficiary_bank_address',

                        'wire_beneficiary_account',
                        'wire_beneficiary_swift',
                        'wire_corresponding_bank',
                        'wire_corresponding_bank_swift',
                        'wire_corresponding_account',
                       );

        if( !empty( $var ) && isset( $fields[ $var ] ) )
        {
            return $fields[ $var ];
        }

        return $fields;

    }



    public function get_require_fields_for_universal_bank_ps($ps)
    {
        $country = $this->get_contry_by_id($ps->country_id);

        $wire_form = $this->get_wire_form_name($country);
//        pred($wire_form);
        $reqired_fileds = $this->_get_wire_bank_reqired_fileds($wire_form);

        return $reqired_fileds;
    }



    private function _check_universal_bank_data($input_array, $ps)
    {
         $array_field = [];

        foreach(self::get_all_fields_universal_bank() as $field)
        {
            $array_field[] = $field[1];
        }

//        $user_id = $this->account->get_user_id();
//
//        $data = array(
//            'user_id' => $user_id,
//            'payment_system_id' => $ps->id,
//        );

//        $curent_ps_data = $this->db
//                ->where($data)
//                ->get($this->table_user_paymant_data)
//                ->row();
//
//        $payment_data = unserialize($curent_ps_data->payment_data);

//        $payment_data = $this->_get_card_number_decode($payment_data);

        $reqired_fileds = $this->get_require_fields_for_universal_bank_ps($ps);



        foreach($input_array as $key => $val)
        {
            if(!in_array($key, $array_field))
            {
                return [false, _e('Заполните все поля формы')];
            }

            if(empty($val) && in_array($key, $reqired_fileds))
            {
                return [false, _e('Заполните все обязательные поля формы')];
            }
        }

        return [true,''];
    }



    public function set_universal_bank($input_array, $ps)
    {
//        $check_input_field = 0;

//        $array_field = [];
//
//        foreach(self::get_all_fields_universal_bank() as $field)
//        {
//            $array_field[] = $field[1];
//        }
//
//        $user_id = $this->account->get_user_id();
//
//        $data = array(
//            'user_id' => $user_id,
//            'payment_system_id' => $ps->id,
//        );
//
//        $curent_ps_data = $this->db
//                ->where($data)
//                ->get($this->table_user_paymant_data)
//                ->row();
//
//        $payment_data = unserialize($curent_ps_data->payment_data);
//
//        $payment_data = $this->_get_card_number_decode($payment_data);
//
//        $reqired_fileds = $this->get_require_fields_for_universal_bank_ps($ps);
//
//
//
//        foreach($input_array as $key => $val)
//        {
//            if(!in_array($key, $array_field))
//            {
//                return [false, _e('Заполните все поля формы')];
//            }
//
//            if(empty($val) && in_array($key, $reqired_fileds))
//            {
//                return [false, _e('Заполните все обязательные поля формы')];
//            }
//        }
        $user_id = $this->account->get_user_id();

        $res = $this->_check_universal_bank_data($input_array, $ps);

        if($res[0] === false)
        {
            return $res;
        }

        $country = $this->get_contry_by_id($ps->country_id);

        $input_array['wire_beneficiary_bank_country'] = _e($country->country_name_ru);

        $res = serialize($input_array);

        $res_save = $this->save_user_paymant_data_without_check($user_id, $ps->machine_name, $res);

        if($res_save === false)
        {
            return [false, _e('Ошибка')];
        }

        return [true, _e('Заполните все поля формы')];
    }



    private function _check_card_number($str)
    {
        $str = strrev(preg_replace('/[^0-9]/', '', $str));
        $chk = 0;
        for ($i = 0; $i < strlen($str); $i++)
        {
            $tmp = intval($str[$i]) * (1 + $i % 2);
            $chk+=$tmp - ($tmp > 9 ? 9 : 0);
        }
        return !($chk % 10);
    }
    //##########################################################################


    private function _get_card_number_decode($payment_sys_user_data)
    {
        if(!is_array($payment_sys_user_data['cart_number']))
        {
            return $payment_sys_user_data;
        }

        $this->load->library('code');

        foreach ($payment_sys_user_data['cart_number'] as &$val)
        {
            $val = $this->code->decode($val);
        }
        unset($val);

        return $payment_sys_user_data;
    }


    public function get_fild_and_ps_data_sberbank($ps, $return  = 'template_sell', $payment_sys_user_data = false, $dop_data = false)
    {
        $data = new stdClass();

        if($payment_sys_user_data === false )
        {
            $payment_sys_user_data = $ps->payment_sys_user_data;
        }

        $payment_sys_user_data = unserialize($payment_sys_user_data);

        $payment_sys_user_data = $this->_get_card_number_decode($payment_sys_user_data);

        if($dop_data !== false && isset($dop_data['order']) && isset($dop_data['curent_user']))
        {
            $order = $dop_data['order'];
            $curent_user = $dop_data['curent_user'];

            if(!isset($order->initiator))
            {
                $payment_sys_user_data['cart_number'][1] = '****';
                $payment_sys_user_data['cart_number'][2] = '****';
                $payment_sys_user_data['cart_number'][3] = '****';
            }
            elseif($order->initiator == 1 && isset($order->buy_payment_data_un[$ps->id]))
            {
                $payment_sys_user_data['cart_number'][1] = '****';
                $payment_sys_user_data['cart_number'][2] = '****';
                $payment_sys_user_data['cart_number'][3] = '****';
            }
            elseif($order->initiator == 0 && isset($order->sell_payment_data_un[$ps->id]))
            {
                $payment_sys_user_data['cart_number'][1] = '****';
                $payment_sys_user_data['cart_number'][2] = '****';
                $payment_sys_user_data['cart_number'][3] = '****';
            }
        }

        if($return  == 'template_sell')
        {
            $data->payment_system = $ps;
            $data->ps_user_data = $payment_sys_user_data;

//            $data->fields = self::get_all_fields_universal_bank();
//            $data->require_fields = $this->get_require_fields_for_universal_bank_ps($ps);

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/sberbank.php', $view_data, true);
        }

        if($return  == 'template_sell_ajax')
        {
            $data->payment_system = $ps;
            $data->ps_user_data = $payment_sys_user_data;

            $data->fields = self::get_all_fields_universal_bank();
            $data->require_fields = $this->get_require_fields_for_universal_bank_ps($ps);

            $data->fields_default_value = [
                'wire_beneficiary_bank' => $ps->humen_name,
//                'wire_beneficiary_swift' => $ps->swift_code,
//                'wire_beneficiary_bank_address' => $ps->city,
            ];

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/sberbank_ajax.php', $view_data, true);
        }

        if($return  == 'template_table')
        {
            $data->payment_system = $ps;

            $data->payment_sys_user_data = $payment_sys_user_data;
            $data->fields = self::get_all_fields_universal_bank();

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/sberbank_for_table.php', $view_data, true);
        }

        if($return  == 'template_admin')
        {
            $data->payment_system = $ps;

            $data->payment_sys_user_data = $payment_sys_user_data;
            $data->fields = self::get_all_fields_universal_bank();

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/sberbank_for_admin.php', $view_data, true);
        }

        if($return  == 'short')
        {
            $res_arr = $payment_sys_user_data;

            if($res_arr['selector'] == 'requizites')
            {
                return isset($res_arr['rs'])?$res_arr['rs']:'';
            }
            elseif($res_arr['selector'] == 'card')
            {
                if(isset($dop_data['order']) && isset($dop_data['curent_user']))
                {
                    return isset($res_arr['cart_number'][1])?implode('-', $res_arr['cart_number']):'';
                }
                else
                {
                    return isset($res_arr['cart_number'][1])?'****-****-****-'.$res_arr['cart_number'][4]:'';
                }
            }
            elseif($res_arr['selector'] == 'requizites_univesal')
            {
                return isset($res_arr['wire_beneficiary_account'])?$res_arr['wire_beneficiary_account']:'';
            }

        }
    }



    public function get_fild_and_ps_data_visa($ps, $return  = 'template_sell', $payment_sys_user_data = false, $dop_data = false)
    {
        $this->load->model('card_model','card');
        
        if( empty( $payment_sys_user_data ) ) return '';
        
        $card_data = $this->card->getCard( $payment_sys_user_data );
               
        if( empty( $card_data ) ) return '';
        
        return  'xxxx-xxxx-xxxx-'.substr($card_data->pan, 12);
    }
    public function get_fild_and_ps_data_universal_bank($ps, $return  = 'template_sell', $payment_sys_user_data = false, $dop_data = false)
    {
        $data = new stdClass();

        if($payment_sys_user_data === false )
        {
            $payment_sys_user_data = $ps->payment_sys_user_data;
        }

        $payment_sys_user_data = unserialize($payment_sys_user_data);

        if($dop_data !== false && isset($dop_data['order']) && isset($dop_data['curent_user']))
        {
            $order = $dop_data['order'];
            $curent_user = $dop_data['curent_user'];
        }

        if($return  == 'template_sell')
        {
            $data->payment_system = $ps;
            $data->ps_user_data = $payment_sys_user_data;

//            $data->fields = self::get_all_fields_universal_bank();
//            $data->require_fields = $this->get_require_fields_for_universal_bank_ps($ps);

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/universal_bank.php', $view_data, true);
        }

        if($return  == 'template_sell_ajax')
        {
            $data->payment_system = $ps;
            $data->ps_user_data = $payment_sys_user_data;

            $data->fields = self::get_all_fields_universal_bank();
            $data->require_fields = $this->get_require_fields_for_universal_bank_ps($ps);

            $data->fields_default_value = [
                'wire_beneficiary_bank' => $ps->humen_name,
//                'wire_beneficiary_swift' => $ps->swift_code,
//                'wire_beneficiary_bank_address' => $ps->city,
            ];

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/universal_bank_ajax.php', $view_data, true);
        }

        if($return  == 'template_table')
        {
            $data->payment_system = $ps;
            $data->payment_sys_user_data = $payment_sys_user_data;
            $data->fields = self::get_all_fields_universal_bank();

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/universal_bank_for_table.php', $view_data, true);
        }

        if($return  == 'template_admin')
        {
            $data->payment_system = $ps;
            $data->payment_sys_user_data = $payment_sys_user_data;
            $data->fields = self::get_all_fields_universal_bank();

            $view_data = get_object_vars($data);
            return $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/universal_bank_for_admin.php', $view_data, true);
        }

        if($return  == 'short')
        {
            $res_arr = $payment_sys_user_data;

            return isset($res_arr['wire_beneficiary_account'])?$res_arr['wire_beneficiary_account']:'';
        }
    }



    private function _get_arhiv_order($arhiv_order)
    {
        if(is_numeric($arhiv_order))
        {
            $arhiv_order = $this->get_order_arhiv_by_id($arhiv_order);
        }

        if(!is_object($arhiv_order))
        {
            return false;
        }

        return $arhiv_order;
    }



    public function set_step_users_confirmation($arhiv_order, $step)
    {
        $arhiv_order = $this->_get_arhiv_order($arhiv_order);

        if($arhiv_orders === false)
        {
            return false;
        }

        // Заглушка для старых заявок до введения шагов.
        if($arhiv_order->old_orders == 1)
        {
            return true;
        }

        if(empty($arhiv_order->second_order))
        {
            $arhiv_order = $this->_get_second_order_data_one($arhiv_order);
        }

        $data = [
            'confirmation_step' => $this->_set_step($arhiv_order, $step),
        ];

        try
        {
            $this->db->trans_start();

            $this->db->where('id', $arhiv_order->id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

            $this->db->where('id', $arhiv_order->second_order->id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

            $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }

        return true;
    }


    public function set_step_users_confirmation_v2($arhiv_order, $step)
    {
        $arhiv_order = $this->_get_arhiv_order($arhiv_order);

        if($arhiv_orders === false)
        {
            return false;
        }

        // Заглушка для старых заявок до введения шагов.
        if($arhiv_order->old_orders == 1)
        {
            return true;
        }

        if(empty($arhiv_order->second_order))
        {
            $arhiv_order = $this->_get_second_order_data_one($arhiv_order);
        }

        $data = [
            'confirmation_step' => $this->_set_step($arhiv_order, $step),
        ];

        try
        {
            // $this->db->trans_start();

            $this->db->where('id', $arhiv_order->id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

            $this->db->where('id', $arhiv_order->second_order->id)
                    ->limit(1)
                    ->update($this->table_orders_arhive, $data);

            // $this->db->trans_complete();
        }
        catch( Exception $exc )
        {
            pre($this->db->last_query());
            vred(self::$last_error = $exc->getTraceAsString());
            self::$last_error = $exc->getTraceAsString();
            return FALSE;
        }

        return true;
    }



    private function _set_step($arhiv_order, $step)
    {
        $int_step = $arhiv_order->confirmation_step;

        if(is_array($step))
        {
            $int_step_res = $int_step;

            foreach($step as $val)
            {
//                $int_step_res = $int_step_res | $this->_mask_user_step_confirmation[$val];
                $int_step_res = $int_step_res | $this->_mask_user_step_confirmation[$val];
            }
        }
        else
        {
            $int_step_res = $int_step | $this->_mask_user_step_confirmation[$step];
        }
//        vre($int_step_res, sprintf('%1$04b', $int_step_res));

        return $int_step_res;
    }



    public function get_status_order($arhiv_order)
    {
       $arhiv_order = $this->_get_arhiv_order($arhiv_order);

        if($arhiv_orders === false)
        {
            return false;
        }

//        return $arhiv_order->confirmation_step;

        $user_id = $this->account->get_user_id();

        $status = false;

//        vre($arhiv_order->confirmation_step);
//        vre($arhiv_order->initiator);
//        vre($arhiv_order->status);
//        vre($arhiv_order->wt_set);

        if($arhiv_order->status != self::ORDER_STATUS_CONFIRMATION)
        {
            return 50;
        }
        
        if($arhiv_order->initiator == 0)
        {
            $status = $this->_contragent_order_status($arhiv_order);
        }

        if($arhiv_order->initiator == 1)
        {
            $status = $this->_initiator_order_status($arhiv_order);
        }

        return $status;
    }



    private function _initiator_order_status($arhiv_order)
    {
        if($arhiv_order->wt_set == 1 && $arhiv_order->confirmation_step == 0b1101)
        {
            //Инициатор должен подтвердить получение денег wt_set = 1
            return 2;
        }

        if($arhiv_order->wt_set == 1 && $arhiv_order->confirmation_step == 0b1100)
        {
            //Контрагент должен подтвердить отправку денег wt_set = 1
            //Статус - Контрагент ожидает подтверждения.
            return 3;
        }

        if($arhiv_order->wt_set == 2 && $arhiv_order->confirmation_step == 0b0011)
        {
            // Инициатор должен подтвердить отправку денег wt_set = 2
            return 6;
        }


        if($arhiv_order->wt_set == 2 && $arhiv_order->confirmation_step == 0b0111)
        {
            // Контрагент должен подтвердить получение денег wt_set = 2
            // Статус - Ожидание подтверждения получения средств контрагентом
            return 7;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0000)
        {
            // Контраент должен подтвердить отправку денег wt_set = 0
            // Статус - Ожидание ответа контрагента.
            return 10;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0001)
        {
            // Контраент должен подтвердить отправку денег wt_set = 0
            return 12;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0011)
        {
            // Контрагент должен подтвердить отправку денег wt_set = 0
            return 14;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0111)
        {
            // Контрагент должен подтвердить получение денег wt_set = 0
            // Статус - Ожидание подтверждения получения средств контрагентом
            return 15;
        }
        else if($arhiv_order->confirmation_step ==  0b11001)
        {
            // Отложенное получение денег контрагентом
            return 17;
        }
        else if($arhiv_order->confirmation_step == 0b11010)
        {
            // Отложенное получение денег контрагентом
            return 18;
        }

    }



    private function _contragent_order_status($arhiv_order)
    {
        if($arhiv_order->wt_set == 1 && $arhiv_order->confirmation_step == 0b1100)
        {
            //Контрагент должен подтвердить отправку денег wt_set = 1
            return 1;
        }

        if($arhiv_order->wt_set == 1 && $arhiv_order->confirmation_step == 0b1101)
        {
            //Инициатор должен подтвердить отправку денег wt_set = 1
            //Статус - Ожидание подтверждения получения средств контрагентом
            return 4;
        }

        if($arhiv_order->wt_set == 2 && $arhiv_order->confirmation_step == 0b0011)
        {
            // Инициатор должен подтвердить отправку денег wt_set = 2
            // Статус - Ожидание ответа контрагента.
            return 5;
        }

        if($arhiv_order->wt_set == 2 && $arhiv_order->confirmation_step == 0b0111)
        {
            // Контрагент должен подтвердить получение денег wt_set = 2
            return 8;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0000)
        {
            // Контрагент должен подтвердить отправку денег wt_set = 0
            return 9;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0001)
        {
            // Контраент должен подтвердить отправку денег wt_set = 0
            // Статус - Ожидание ответа контрагента.
            return 11;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0011)
        {
            // Контрагент должен подтвердить отправку денег wt_set = 0
            // Статус - Ожидаем отправку средств контрагентом.
            return 13;
        }

        if($arhiv_order->wt_set == 0 && $arhiv_order->confirmation_step == 0b0111)
        {
            // Контрагент должен подтвердить получения денег wt_set = 0
            return 16;
        }
        else if($arhiv_order->confirmation_step == 0b11001)
        {
            // Отложенное получение денег контрагентом
            return 17;
        }
        else if($arhiv_order->confirmation_step == 0b11010)
        {
            // Отложенное получение денег контрагентом
            return 18;
        }
    }



    static public function get_order_status($arhiv_order)
    {
        $ci = self::getInstance();

        return $ci->get_status_order($arhiv_order);
    }

    static public function get_social_link($user_id) {
        if(empty($user_id))
            return FALSE;

        $currency_exchange = self::getInstance();

        $res = $currency_exchange->db->select('id_network')
            ->where('id_user', $user_id)
            ->get('users_filds')
            ->row();
        if(empty($res->id_network))
            return FALSE;

        return '/social/profile/'. $res->id_network;
    }

    public function order_status_to_text($status) {
        $text = _e('status/unknow');
        if(in_array($status, [8, 9, 10, 20, 21, 30, 60, 70, 80, 81, 82, 83, 84, 85, 90, 100, ] ))
            $text = _e('status_' . $status);

        return $text;
    }

    private function get_data_order_archive_to_export($item) {
        $res = array();
        $res['id']     = $item->id .'/'. $item->original_order_id;
        $res['date']   = $item->seller_set_up_date;
        $res['give']   = $item->seller_amount;
        $res['get']    = $item->buyer_amount_down;
        $res['status'] = $this->order_status_to_text($item->status);

        reset( $item->payment_systems);
        $give_ps =  current( $item->payment_systems);
        $get_ps  =  next( $item->payment_systems);

        $res['give_currency_name'] = self::get_ps($give_ps->payment_system)->humen_name;
        $res['get_currency_name']  = self::get_ps($get_ps->payment_system)->humen_name;



        $res['give_currency'] = self::show_payment_system_code(array( 'order' => $item, 'curent_ps' => $give_ps ));
        $res['get_currency']  = self::show_payment_system_code(array( 'order' => $item, 'curent_ps' => $get_ps ));

        $res['give_currency'] = $this->clear_currency_human_name($res['give_currency']);

        $res['get_currency'] = $this->clear_currency_human_name($res['get_currency']);


        return $res;
    }

    private function get_order_archives_to_export($type) {
        // Завершенные
        if ( $type == 2 ) {
            $this->db->where(array('seller_confirmed' => 1, 'buyer_confirmed' => 1));
        }
        $res = $this->db
                ->where("seller_user_id",  $this->user->id_user)
                ->order_by('original_order_id', 'DESC')
                ->get('currency_exchange_orders_arhive')
                ->result();
        return $this->_get_user_orders_arhive_details($res);
    }

    private function export_excel_settings(&$sheet) {
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);


        $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('2980B9');
        $sheet->getStyle('A2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('B2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('C2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('D2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('E2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('F2')->getFill()->getStartColor()->setRGB('3498DB');
        $sheet->getStyle('G2')->getFill()->getStartColor()->setRGB('3498DB');


        $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('C2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('D2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('E2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('F2')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('G2')->getFont()->getColor()->setRGB('FFFFFF');
    }

    private function clear_currency_human_name($name) {
        // Заменить на код символа
        $conditions = array(
            '<span class="curency_id_heart_red">❤</span>' => '❤'
        );

        foreach ($conditions as $k=>$v) {
            $name = str_replace($k, $v, $name);
        }
        return $name;
    }

    public function export_order_archive() {
        if(!empty($_POST['export_user_id'])) {
            echo $_POST['export_user_id'];
            return false;
        }

        $type = null;
        if (in_array($this->input->get('type', TRUE), [1, 2]))
            $type = $this->input->get('type', TRUE);
        else
            $type = 1;

        $this->load->library('phpexcel');
        $this->load->helper('date');


        # Проверка даты
        $date = [];
        $date['array'][0] = explode('/', $this->input->get('date_1', TRUE));
        $date['array'][1] = explode('/', $this->input->get('date_2', TRUE));

        if (@count($date['array'][0]) != 3 || @!checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2])) $date['array'][0] = explode('/', "05/21/2013");
        if (@count($date['array'][1]) != 3 || @!checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2])) $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));

        $date['first'][0] = $date['array'][0][2] . "-" . $date['array'][0][0] . "-" . $date['array'][0][1];
        $date['last'][0]  = $date['array'][1][2] . "-" . $date['array'][1][0] . "-" . $date['array'][1][1];

        $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1):($date['array'][0][1] - 1));
        $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1):($date['array'][1][1] + 1));

        $date['first'][1] = mktime(23, 59, 59, $date['array'][0][0], $date['array'][0][1], $date['array'][0][2]);
        $date['last'][1] = mktime(00, 00, 00, $date['array'][1][0], $date['array'][1][1], $date['array'][1][2]);



        $sheet = $this->phpexcel->getActiveSheet();
        $this->export_excel_settings($sheet);


        $i = 2;

        $sheet->setCellValue("B$i", "ID");
        $sheet->setCellValue("A$i", _e("Дата"));
        $sheet->setCellValue("C$i", _e("Статус"));
        $sheet->setCellValue("D$i", _e("Отдано"));
        $sheet->setCellValue("E$i", _e("Валюта"));
        $sheet->setCellValue("F$i", _e("Получено"));
        $sheet->setCellValue("G$i", _e("Валюта"));


        $res = $this->get_order_archives_to_export($type);


        foreach ($res as $item) {
            $data = $this->get_data_order_archive_to_export($item);

            $i++;

            $sheet->setCellValue("B$i", $data['id']);
            $sheet->setCellValue("A$i", $data['date']);
            $sheet->setCellValue("C$i", $data['status']);
            $sheet->setCellValue("D$i", $data['give'] .' '.$data['give_currency']);

            $sheet->setCellValue("E$i", $data['give_currency_name']);
            $sheet->setCellValue("F$i", $data['get'] .' '.$data['get_currency']);
            $sheet->setCellValue("G$i", $data['get_currency_name']);
        }


        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue("A1",
            _e("Список")
            ." ".(($type['int'] == 1) ? _e("всех") : _e("завершенных"))
            ." "._e("заявок пользователя")
            ." ". $this->user->id_user
            ." ". _e("c ")
            .$this->input->get('date_1')
            ._e(" по: ")
            . $this->input->get('date_2')
        );

        $i++;

        $sheet->mergeCells("A$i:G$i");

        $sheet->getStyle("A$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle("A$i")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$i")->getFill()->getStartColor()->setRGB('2980B9');

        $file_name =  $this->user->id_user . "_" . mdate("%Y_%m_%d", time()).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $writer->save('php://output');
    }


    public function update_rating($options) {
        // Смотрим /helpers/main_helper.php
        $pr = validate_options( array('id' => 'required'), $options );
        extract($pr);

        // Получаем данные order_archive по указаному id
        $temp_archive_order = $this->get_order_arhiv_by_id($id);
        // Проверяем была ли уже проверенна даная заявка
        if($temp_archive_order->is_checked_rating == 0) {
            $original_order_id = $temp_archive_order->original_order_id;

            // Получаем две записи по текущей заявке из таблици архива.
            $archive_orders_by_one_origin_id = $this->get_archive_orders_by_orig_order_id($original_order_id);

            // Поочередно для каждой выполняем.
            foreach ($archive_orders_by_one_origin_id as $archive_order) {
                // Ставим флаг что дання заявка уже была пройдена для рейтинга.
                $this->set_is_checked_rating(array('id' => $archive_order->id));
                // Обновляем рейтинг пользователя
                $this->update_rating_user(array('user_id' => $archive_order->seller_user_id));
            }
            return true;
        }

        return false;
    }

    private function set_is_checked_rating($options) {
        $pr = validate_options( array('id' => 'required'), $options );
        extract($pr);

        $this->db
            ->where('id', $id)
            ->limit(1)
            ->update($this->table_orders_arhive, array('is_checked_rating' => 1));
    }

    private function update_rating_user($options) {
        $pr = validate_options( array('user_id' => 'required'), $options);
        extract($pr);

        $user_rating = $this->get_user_currency_exchange_rating(array('user_id' => $user_id));

        // Если у пользователя рейтинг 0 то проверяем его историю. Предпологаетс что рейтинг появился несразу.
        if($user_rating == 0) {
            // Берем стак старых записей, которые ранее не были проверены нами.
            $stack_my_old_archives = $this->db
                ->where('seller_user_id', $user_id)
                ->where('is_checked_rating', 0)
                ->get($this->table_orders_arhive)
                ->result();
            // Увеличиваем рейтинг пользователя
            $this->user_up_currency_exchange_rating(array('user_id' => $user_id));

            // И пробегаемся по всему стаку, и получаем рекурсию
         //   foreach ($stack_my_old_archives as $key => $value) {
                // Запускаем на проверку только те заявки которые были подтверждены
                // Иначе указываем что ее уже просматривали
         //       if($value->seller_confirmed == 1 && $value->buyer_confirmed == 1)
         //           $this->update_rating(array('id' => $value->id));
         //       else {
         //           $this->set_is_checked_rating(array('id' => $value->id));
          //      }
        //    }
        } else {
            // А если у пользователя нету истории то просто добавляем ему рейтинг
            $this->user_up_currency_exchange_rating(array('user_id' => $user_id));
        }



    }

  private function user_up_currency_exchange_rating($options) {
        $pr = validate_options(array('user_id' => 'required'), $options);
        extract($pr);

        // Получаем текущий рейтинг
        $user_rating = $this->get_user_currency_exchange_rating(array( 'user_id' => $user_id));

        if(empty($user_rating))
            $user_rating = 0;

        // В будущем изменить, в зависимости от суммы денежной сделки увеличивать на +1, +2, +n.
        $user_rating++;

        $this->db
            ->where('id_user', $user_id)
            ->limit(1)
            ->update('users', array('currency_exchange_rating' => $user_rating));
    }

    private function get_user_currency_exchange_rating($options) {
        $pr = validate_options( array('user_id' => 'required'),  $options );
        extract($pr);

        $this->load->model("users_model","users");
        $cur_user = $this->users->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->currency_exchange_rating;
        }

        $res = $this->db->select('currency_exchange_rating')
            ->where('id_user', $user_id)
            ->limit(1)
            ->get('users')
            ->row();

        return $res->currency_exchange_rating;
    }


}
