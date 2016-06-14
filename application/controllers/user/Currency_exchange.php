<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * List of modification:
 *
 * 18.08.2014 esb [31] sent_money() modified.
 *
 * sell = out,
 * buy = in
 */
if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php';}

class Currency_exchange extends Accaunt_base{

    private $count_chat;
    private $order_count_chat;
// <editor-fold defaultstate="collapsed" desc="что-то лишнее">
//    private $p2p_test_users = array(
////                '91802698',
////                '60830397',
////                '93517463',
////                '82938815',
////                '38854127',
//
//                '99676729',
//                '500150',
//                '500733',
//                '500757',
//                '32906549',
//                '90837257',
//                '96013991',
//                '81336307',
//                '26070292',
//                '49643480',
//                '90835923',
//                '90836571',
//                '93517463',
//                '90827893',
//                '92156962',
//
//                '99677967',
//                '87667178',
//                '54049637',
//            );
// </editor-fold>

    // !! ВНИМАНИЕ в качестве ключа платёжной системы нужно использовать её бонус( проверка по бонусу используется при сведении заявки)

    private $test_p2p_wt = [
        4 => 'wt_c_creds',
        2 => 'wt_heart',
        5 => 'wt'
        ];


    private $user_id;

    function __construct() {
        parent::__construct();
        $this->load->model('accaunt_model','account');
        $this->load->model('currency_exchange_model','currency_exchange');
//        $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша
        viewData()->banner_menu = "profil";
//        viewData()->secondary_menu = "profile";
        viewData()->secondary_menu = "curency_exchange";
//        viewData()->page_name = $this->router->fetch_method();
        viewData()->page_name =$this->router->fetch_method();
        $this->content->clientType = 1;

        $this->user_id = $this->account->get_user_id();
// <editor-fold defaultstate="collapsed" desc="что-то лишнее">
//        $user_array = array(
//                '91802698',
//                '60830397',
//                '93517463',
//                '82938815',
//                '38854127',
//
//            '99676729',
//            '500150',
//            '500733',
//            '500757',
//            '32906549',
//            '90837257',
//            '96013991',
//            '81336307',
//            '26070292',
//            '49643480',
//            '90835923',
//            '90836571'
//
//        );
//
//        if(array_search($user_id, $user_array) === false)
//        {
//            show_404();
//            die;
//        }

        //require_once APPPATH.'controllers/user/Security.php';
        //viewData()->security = Security::getProtectType($this->user_id);
// </editor-fold>
        list($this->count_chat, $this->order_count_chat, $this->order_chat_conf) = $this->currency_exchange->get_all_user_chats();
//        vred($this->count_chat, $this->order_count_chat);
//        pre($this->count_chat);
//        pre($this->order_count_chat);

//        viewData()->count_chat = $this->count_chat;
        viewData()->count_chat = count($this->order_count_chat);
        viewData()->order_count_chat = $this->order_count_chat;
        viewData()->order_chat_conf = $this->order_chat_conf;
        viewData()->count_order_chat_conf = count($this->order_chat_conf);
//        viewData()->count_chat = $this->count_chat;
//        viewData()->order_count_chat = $this->order_count_chat;
//        viewData()->page_bottom = '<div style="display: block;z-index: 1101; padding: 20px;" class="popup_window" id="p2p_close">
//                                                <div class="close" onclick="$(this).parent().hide(\'slow\');"></div>
//                                                <div class="close" onclick="$(this).parent().hide(\'slow\');"></div>'._e('close-p2p-value__body').'<br>
//                                            </div>';

       viewData()->page_bottom  = '<center>
	   <div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
	   <iframe id="a0440697" name="a0440697" src="https://biggerhost.com/ads/www/delivery/afr.php?zoneid=49&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}" frameborder="0" scrolling="no" width="728" height="90"><a href="https://biggerhost.com/ads/www/delivery/ck.php?n=a701830f&amp;cb={random}" target="_blank"><img src="https://biggerhost.com/ads/www/delivery/avw.php?zoneid=49&amp;cb={random}&amp;n=a701830f&amp;ct0={clickurl_enc}" border="0" alt="" /></a></iframe>
	   </div><br/>'.

        //solved problem with unbinded top menu
       "<script>
        $(function(){
           console.log('show_data' );
           console.log( $('.user-main-menu_item_amount') );
            var click_new = function(){
                window.location = $(this).attr('href');
                return;
            }
            $( '.user-main-menu_item.borrow .menu_item_link' ).on('click',click_new);
            $( '.user-main-menu_item.wallet .menu_item_link' ).on('click',click_new);
            $( '.user-main-menu_item.balans .menu_item_link' ).on('click',click_new);
            $( '.user-main-menu_item.rating .menu_item_link' ).on('click',click_new);
        });
        </script>

		";


//        $current_time = strtotime(  date('2015-08-08 H:i:s') );
//
//        if( !in_array( $this->user->id_user, $this->currency_exchange->p2p_test_users ) )
//        {
//            if( $current_time >= strtotime( '2015-08-08 8:00:00' ) && $current_time <= strtotime( '2015-08-08 20:00:00' )
//                && Currency_exchange_model::SWITCH_OFF == FALSE
//            )
//            {
//                viewData()->page_bottom = '';
//            }
//            else
//                $_POST = null;
//        }else{
//            viewData()->page_bottom = '';
//        }
//
//        viewData()->page_bottom .= $page_bottom_comments;
        //if( !in_array($this->user_id, ['500733', '500757', '500150', '75705622', '500002', '99676729', '87667178']  ) && strpos(current_url(), 'closed') === FALSE )
        //      $this->redirect_iframe( '/account/currency_exchange/closed' );


        $this->load->model('users_model', 'users_model');
        $user_data = $this->users_model->getUserData($this->user_id);
        viewData()->curent_user_data = $user_data;

        $name = $user_data->name;
        if( !empty( $name ) ) $name = ' '.mb_substr( $name, 0, 1 ).'.';
        viewData()->curent_user_data->name_sername = $user_data->sername . $name;
        //$this->redirect_iframe('account/under_construction');  
    }



    public function index() {
        $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');
        return;
    }

    //the page is showed when close the P2P
    public function closed() {
        $this->content->user_view('currency_exchange/closed' );
    }
    
    //проверка наличия VISA в перемежку с другими валютами: VISA + Qiwi - Perfect.
    private function _is_have_visa_pair($payment_systems) {
        if(empty($payment_systems))
            return false;

        if(count($payment_systems)>1) {
            foreach ($payment_systems as $k => $v) {
                if($k == 'wt_visa_usd')
                    return true;
            }
        }
        return false;
    }
    // ОШИБКА
    // При создании заяви неВТ - вт в полях seller_amount и buye_amount_down значения меняются местами

    // Отлючено использование дебита,
    // #DETIT_OFF_2016_04_18 искать по идентификатору
    // в js искать это идентификатор или в функциях sell_payment_systems_checkbox_change, buy_payment_systems_checkbox_change

    // Отключено создание заявки неВт - неВт
    // Открыто создание visa - неВт
    // #neWt_neWt_off___visa_neWt_on__2016_04_18
    // в js смотреть функцию hide_other_checkbox_if_select_wt
    private function _curency_excahnge($viever_g, $title_g, $type, $data = false, $bonus = 5, $iframe = false)
    {

        
        if(!$data)
        {
            $data = new stdClass();
        }

        $user_id = $this->account->get_user_id();
        $data->user_ratings = viewData()->accaunt_header;
//        if( $this->accaunt->get_user_id() == 500733 ) var_dump( $data->user_ratings );
//        pred($data->user_ratings);
//        pred($data->user_ratings['payout_limit']);
//        $payout_limit = $data->user_ratings['payout_limit_bonus'][5];
//        $payout_limit = $data->user_ratings['payout_limit_by_bonus'][$bonus];

//        if($payout_limit > 1000) $payout_limit = 1000;

        $payout_limit_arr = $this->currency_exchange->get_root_payout_limit_arr($data->user_ratings);

        $payout_limit_arr_buy = array();

        for($i = 50; $i<1000+1; $i += 50)
        {
            $payout_limit_arr_buy[] = $i;
        }

        $data->payout_limit_arr = $payout_limit_arr;
        $data->payout_limit_arr_buy = $payout_limit_arr_buy;


        $payment_systems_groups = $this->currency_exchange->get_all_payment_systems_groups();
        $data->payment_systems_groups = $payment_systems_groups;

//        $data->payment_systems = $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);
        $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);
        //отключили USD1 c 10/12/2016
        unset( $payment_systems['wt'] );

//

        $data->payment_systems = $payment_systems;


        $data->buy_amount_down = 0;
        $data->buy_amount_up = 0;

        $data->sell_amount_down = 0;
        $data->sell_amount_up = 0;

        $data->sell_sum_total = 0;
        $data->sell_sum_amount = 0;
        $data->sell_sum_fee = 0;

        $data->buy_payment_systems = array();
        $data->sell_payment_systems = array();

        $protection_type = Security::getProtectType($user_id);
        $data->security = $protection_type;

        require_once APPPATH.'controllers/user/Security.php';

        if( $this->input->post('submited', TRUE) == 1 && Currency_exchange_model::SWITCH_OFF === FALSE )
        {
//            $sms = $this->input->post('sms', TRUE);
            $buy_payment_systems = $this->input->post('buy_payment_systems', TRUE);
            $sell_payment_systems = $this->input->post('sell_payment_systems', TRUE);

            $data->sell_user_payment_summa = $sell_user_payment_summa = $this->input->post('input_summa_sell_payment_systems', TRUE);
            $data->buy_user_payment_summa = $buy_user_payment_summa = $this->input->post('input_summa_buy_payment_systems', TRUE);

            // Данный блок запрещает делать заявки с использованием Дебита
            // #DETIT_OFF_2016_04_18
            if(!empty($sell_user_payment_summa['wt_debit_usd']) || !empty($buy_user_payment_summa['wt_debit_usd']) ) {
                accaunt_message($data, _e('В данный момент нет возможности выбора DEBIT'), 'error');
                $this->redirect_iframe( '/account/currency_exchange/sell' );
                return false;
            }



            // для тестовых пользователей
            #проверяем, что Отдаю - только ВТ, а Получаю - только не ВТ
            //отключили USD1 c 10/12/2016
            if( $buy_payment_systems[ 'wt' ] )
            {
                accaunt_message($data, _e('Валюта USD1 недоступна для выставления в завке'), 'error');
                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');

                return false;
            }
            if( $buy_payment_systems[ 'wt_debit_usd' ] )
            {
                accaunt_message($data, _e('Валюта DEBIT недоступна для выставления в завке'), 'error');
                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');

                return false;
            }
            if( $sell_payment_systems[ 'wt' ] )
            {
                accaunt_message($data, _e('Валюта USD1 недоступна для выставления в завке'), 'error');
                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');

                return false;
            }
            if( $sell_payment_systems[ 'wt_debit_usd' ] )
            {
                accaunt_message($data, _e('Валюта DEBIT недоступна для выставления в завке'),
                    'error');
                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');

                return false;
            }

            //для непроведения валют
            if(0){
                foreach($sell_payment_systems as $k => $v)
                {
                    if(!in_array($k, $this->test_p2p_wt))
//                    if( $v->root_currency == 1 )
                    {
                        accaunt_message($data, _e('Операция не доступна'), 'error');

                        $this->redirect_iframe( '/account/currency_exchange/sell' );

                        return false;
                    }
                }

                foreach($buy_payment_systems as $k => $v)
                {
                    if(in_array($k, $this->test_p2p_wt))
//                    if( $v->root_currency != 1 )
                    {
                        accaunt_message($data, _e('Операция не доступна'), 'error');
                        $this->redirect_iframe( '/account/currency_exchange/sell' );
                        return false;
                    }
                }
            }

            $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
            $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');




            $all_currency = $this->currency_exchange->get_all_activ_currencys();

            $all_currency_key_code = array_set_value_in_key($all_currency, 'num');

            $select_currency_error = FALSE;

            foreach ($select_curency_sell_payment_systems as $key => &$val)
            {
                if(isset($sell_payment_systems[$key]) && !$val)
                {
                    $val = false;
                    $select_currency_error =  true;
                }

                if($val != 0 && !isset($all_currency_key_code[$val]))
                {
                    $val = false;
                    $select_currency_error =  true;
                }
            }
            unset($val);

            foreach ($select_curency_buy_payment_systems as $key => &$val)
            {
                if(isset($buy_payment_systems[$key]) && !$val)
                {
                    $val = false;
                    $select_currency_error =  true;
                }

                if($val != 0 && !isset($all_currency_key_code[$val]))
                {
                    $val = false;
                    $select_currency_error =  true;
                }
            }
            unset($val);
//            $data->payment_system_machine_name = $payment_system_machine_name = 'wt';

            $buy_error = FALSE;
            $buy_payment_systems_view = array();

            $currency_id_temp = FALSE;

//            $b_p_s = isset($_POST['real_buy_payment_systems'])?$_POST['real_buy_payment_systems']:$buy_payment_systems;
//            $user_pay_data = $this->currency_exchange->get_user_paymant_data_for_orders($user_id, $b_p_s);
            $user_pay_data = $this->currency_exchange->get_user_paymant_data_for_orders($user_id, $buy_payment_systems);

            $user_pay_data_err = FALSE;
            $user_payment_summa_error = false;
            $duplicate_payment_system_error = false;
            $no_singl_wt_error = false;
            $no_min_summ_wt_50 = true;

            $is_not_buy_wt = $is_not_sell_wt = true;

            $count_sell_no_wt = 0;
            $count_buy_no_wt = 0;
//            vred($sell_payment_systems, $buy_payment_systems);
            if(count($buy_payment_systems) > 1)
            {
                foreach ($buy_payment_systems as $k => $v)
                {
//                    if($k == 'wt')
                    if($this->currency_exchange->is_root_currency($k))
                    {
                        $no_singl_wt_error = true;
                        break;
                    }
                    else
                    {
                        $count_buy_no_wt++;
                    }
                }
            }

            if(count($sell_payment_systems) > 1)
            {
                foreach ($sell_payment_systems as $k => $v)
                {
//                    if($k == 'wt')
                    if($this->currency_exchange->is_root_currency($k))
                    {
                        $no_singl_wt_error = true;
                        break;
                    }
                    else
                    {
                        $count_sell_no_wt++;
                    }
                }
            }


            $error_duplicate_payment_systems = false;

            foreach($sell_payment_systems as $key => $val)
            {
                foreach($buy_payment_systems as $k => $v)
                {
                    if($key == $k)
                    {
                        $error_duplicate_payment_systems = true;

                        break;
                    }
                }
            }

            #закрытие возможности брать смешанные с Визой заявки типа: Виза + Киви - Перфект

            $error_visa_pair = false;
            if( $this->_is_have_visa_pair($sell_payment_systems) || $this->_is_have_visa_pair($buy_payment_systems)) {
                $error_visa_pair = true;
            }

            #######################################
            #закрываем не Вт - не ВТ
            foreach($buy_payment_systems as $k => $v)
            {

                // #neWt_neWt_off___visa_neWt_on__2016_04_18
                // Что бы вернуть ставим строку ниже заместь той что с визой
                // if($this->currency_exchange->is_root_currency($k) )
                // Строка ниже посволяет закрыть neWt - neWt но открывает visa - neWt
                if($this->currency_exchange->is_root_currency($k) || $k == 'wt_visa_usd')
                {
                    $is_not_buy_wt = false;
                    break;
                }
            }

            foreach($sell_payment_systems as $k => $v)
            {
//                if($k == 'wt')

                // #neWt_neWt_off___visa_neWt_on__2016_04_18
                // Что бы вернуть ставим строку ниже заместь той что с визой
                // if($this->currency_exchange->is_root_currency($k) )
                // Строка ниже посволяет закрыть neWt - neWt но открывает neWt - visa
                if($this->currency_exchange->is_root_currency($k) || $k == 'wt_visa_usd')
                {
                    $is_not_sell_wt = false;
                    break;
                }
            }

            // убираем проверку на обязательный выбор wt 
            // $is_not_buy_wt = false;
            // $is_not_sell_wt  = false;
            ############################################


            foreach($user_pay_data as $val)
            {
//                if(!$val->payment_data && $val->payment_system_id != $payment_systems['wt']->id)
                if(!$val->payment_data && !$this->currency_exchange->is_root_currency($val->payment_system_id))
                {
                    $user_pay_data_err = TRUE;
                }
            }
//            pred($buy_payment_systems);
            foreach( $buy_payment_systems as $key => &$val )
            {
                if( !isset( $payment_systems[ $key ] ) )
                {
                    $payment_systems[ $key ] = Currency_exchange_model::get_ps($key);
                    if(empty($payment_systems[ $key ]))
                    {
                        unset($payment_systems[ $key ]);
                    }
                }

                if( !isset( $payment_systems[ $key ] ) )
                {
                    unset( $buy_payment_systems[ $key ] );
                }
                else
                {
                    $buy_payment_systems_view[$key] = $val;
                    $val = $payment_systems[ $key ]->id;

                    if($currency_id_temp === FALSE)
                    {
                        $currency_id_temp = $payment_systems[ $key ]->currency_id;
                    }
                }

                if(empty($buy_user_payment_summa[$key]) || !floatval($buy_user_payment_summa[$key]) || $buy_user_payment_summa[$key] <= 0)
                {
                    $user_payment_summa_error = true;
                }

                if(array_key_exists($key, $sell_payment_systems) === true)
                {
                    $duplicate_payment_system_error = true;
                }
            }

            unset($val); // необходимо удалить так как ранее был ииспользован указатель &$val;

            $data->buy_payment_systems = $buy_payment_systems_view;

            $sell_error = FALSE;

            foreach( $sell_payment_systems as $key => $val )
            {
                if( !isset( $payment_systems[ $key ] ) )
                {
                    $payment_systems[ $key ] = Currency_exchange_model::get_ps($key);
                    if(empty($payment_systems[ $key ]))
                    {
                        unset($payment_systems[ $key ]);
                    }
                }

                if( !isset( $payment_systems[ $key ] ) )
                {
                    unset( $sell_payment_systems[ $key ] );
                }
                else
                {
                    $sell_payment_systems[$key] = $payment_systems[ $key ]->id;
                }

                if(empty($sell_user_payment_summa[$key]) || !floatval($sell_user_payment_summa[$key]) || $sell_user_payment_summa[$key] <= 0)
                {
                    $user_payment_summa_error = true;
                }
            }

            $data->sell_payment_systems = $sell_payment_systems;

            $data->payment_systems = $payment_systems;

            // TODO нужно удалить везде где встречается
            $sell_sum_amount = '';

            // TODO нужно удалить везде где встречается
            $buy_amount_down = '';

            $sell_root =  $this->currency_exchange->get_root_currency_key_from_array($sell_payment_systems);
            $buy_root = $this->currency_exchange->get_root_currency_key_from_array($buy_payment_systems);

            $res_check_limit_input_payment_summ = $this->currency_exchange->check_limit_input_payment_summ($buy_payment_systems, $sell_payment_systems,$buy_user_payment_summa, $sell_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);
//            pre($buy_payment_systems, $sell_payment_systems,$buy_user_payment_summa, $sell_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);
//            pred($res_check_limit_input_payment_summ);
            $sell_root_bonus = $this->currency_exchange->get_bonus_by_currency( $sell_root );

            
            list( $error_check_payment_systems_limit, $res_ps )  = $this->currency_exchange->check_payment_systems_limit($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems, TRUE);

//            $this->load->model('phone_model', 'phone');
//
//            if( $this->phone->isStatusVerified( $user_id ) === FALSE )
//            {
//                accaunt_message($data, _e('security/data8'), 'error');
//            }
//            elseif( $error_check_payment_systems_limit === true )

            if( $this->accaunt->isUserDocumentVerified( $user_id ) === FALSE )
            {
                accaunt_message($data, _e('upload_docs_or_verify_phone'), 'error');////Загрузите документы на странице <a href="/account/profile">Профиль</a><br/> и повторите попытку.
            }
            elseif( $error_check_payment_systems_limit === true )
            {
                
                $ps = Currency_exchange_model::get_ps($res_ps);
                $error_str = sprintf( _e('Сумма %s не должна превышать 1000 USD или её эквивалента'), $ps->humen_name );
                accaunt_message($data, $error_str, 'error');                    
                
            }
            elseif( $count_sell_no_wt > 1 && $count_buy_no_wt > 0 )
            {
                accaunt_message($data, _e('Выбрана неверная валюта').'.', 'error');
            }
            elseif( $error_duplicate_payment_systems === true )
            {
                accaunt_message($data, _e('Выбрана неверная валюта').'.', 'error');
            }
            elseif( empty( $data->buy_payment_systems ) )
            {
                accaunt_message($data, _e('currency_exchange/controller/error_select_carency_for_buy'), 'error');
            }
            elseif($select_currency_error === true)
            {
                accaunt_message($data, _e('Выбрана неверная валюта'), 'error');
            }
            elseif(Security::checkSecurity($user_id))
            {
            }
            elseif(true !== $res_check_limit_input_payment_summ)
            {
                if( $res_check_limit_input_payment_summ[3] === TRUE )
//                 accaunt_message($data, sprintf(_e('Сумма в валюте обмена не должна быть меньше 70%% (%s USD) от суммы номинала.'), $res_check_limit_input_payment_summ), 'error');
                 accaunt_message($data, sprintf(_e('%s: Превышен максимальный размер дисконта (%s %s)'), $res_check_limit_input_payment_summ[0], $res_check_limit_input_payment_summ[1], $res_check_limit_input_payment_summ[2]), 'error');
                else
                    accaunt_message($data, sprintf(_e('max_discount'), $res_check_limit_input_payment_summ[0], $res_check_limit_input_payment_summ[1], $res_check_limit_input_payment_summ[2]), 'error');
            }
//            elseif(isset($sell_payment_systems['wt']) && in_array($sell_user_payment_summa['wt'], $payout_limit_arr) === false )
            elseif($sell_root && in_array($sell_user_payment_summa[$sell_root], $payout_limit_arr[$sell_root]) === false )
            {
//                if ( $sell_user_payment_summa[$sell_root] > $payout_limit )
//                    accaunt_message($data, _e('currency_exchange/controller/error_limit'), 'error');
//                else
                    accaunt_message($data, _e('currency_exchange/controller/error_div_50'), 'error');
            }
//            elseif(isset($buy_payment_systems['wt']) && in_array($buy_user_payment_summa['wt'], $payout_limit_arr_buy) === false )
            elseif($buy_root && in_array($buy_user_payment_summa[$buy_root], $payout_limit_arr_buy) === false )
            {
                accaunt_message($data, _e('currency_exchange/controller/error_input_data'), 'error');
            }
            elseif($is_not_buy_wt === true && $is_not_sell_wt === true)
            {
                accaunt_message($data, _e('currency_exchange/controller/error_select_wt'), 'error');
            }
            elseif(empty( $data->sell_payment_systems ))
            {
                accaunt_message($data, _e('currency_exchange/controller/error_select_carency_for_sell'), 'error');
            }
            elseif($user_pay_data_err && (isset($data->show_payment_data) && $data->show_payment_data === TRUE))
            {
                accaunt_message($data, _e('currency_exchange/controller/error_fill_all_ps_fields'), 'error');
            }
            elseif($duplicate_payment_system_error === TRUE)
            {
                accaunt_message($data, _e('currency_exchange/controller/error_select_different_ps'), 'error');
            }
            elseif($no_singl_wt_error === TRUE)
            {
                accaunt_message($data,  _e('currency_exchange/controller/error_wt_not_select_together_anothet_ps'), 'error');
            } elseif($error_visa_pair === TRUE)
            {
                accaunt_message($data,  _e('Вы не можете создать заявку в паре с визой.'), 'error');
            }
            else
            {
                $user_ratings = viewData()->accaunt_header;

                if( $sell_error === TRUE || $buy_error === TRUE )
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_input_data'), 'error');
                }
                else if($user_payment_summa_error === TRUE)
                {
                     accaunt_message($data, _e('currency_exchange/controller/error_fill_all_fields_desired_amount'), 'error');
                }

                else
                {
                    list($sell_sum_fee, $sell_sum_total, $percent, $error) = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);

                    $status = Currency_exchange_model::ORDER_STATUS_SET_OUT;

//                    $pd_s = $payment_data_by_id[$sell_payment_id];
//                    $pd_b = $payment_data_by_id[$buy_payment_id];
//
                    $ps = [];
//
//                    $buy_payment_systems[ $pd_b->machine_name ] = $pd_b->id;
//                    $buy_user_payment_summa[ $pd_b->machine_name ] = $order->buyer_amount_down;
//                    $select_curency_buy_payment_systems[ $pd_b->machine_name ] = $pd_b->currency_id;
//
//                    $sell_payment_systems[ $pd_s->machine_name ] = $pd_s->id;
//                    $sell_user_payment_summa[ $pd_s->machine_name ] = $order->seller_amount + $order->seller_fee;
//                    $select_curency_sell_payment_systems[ $pd_s->machine_name ] = $pd_s->currency_id;


                    $ps['buy'] = [$buy_payment_systems, $buy_user_payment_summa, $select_curency_buy_payment_systems];
                    $ps['sell'] = [$sell_payment_systems, $sell_user_payment_summa, $select_curency_sell_payment_systems];


                    list($minus_discount) = $this->currency_exchange->add_payment_system_discount( $ps );
                    //поиск всех, кто ставит больше нужного размера
                    if(empty($ps['sell'][0]['wt_visa_usd']) && empty($ps['buy'][0]['wt_visa_usd']))  {

    //                    $res_check_user_for_payout = true;
                        // проверяем столбик слева на наличие wt если есть отправляем заявку администратору на рассмотрение.
    //                    if(array_key_exists('wt', $sell_payment_systems) === true)
                        if($this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems)  && empty($ps['sell'][0]['wt_visa_usd']))
                        {
    //                        $status = Currency_exchange_model::ORDER_STATUS_PROCESSING;
    //                        vred($sell_root_bonus);

                            $this->load->model('users_model', 'users');
                            $res_check_user_for_payout = $this->users->check_user_for_payout($user_id, $sell_sum_total, FALSE, $sell_root_bonus);
    //                        vred($res_check_user_for_payout);
                            if($res_check_user_for_payout !== TRUE)
                            {
                                $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );

                            }
    //                        else{
    //                            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING;
    //                        }
    //                          vred($sell_payment_systems);
                            //отправляем валюты через оператора
    //                        foreach( $sell_payment_systems as $id )
    //                        {
    //                            if( $id != 116 ) continue;
    //                            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
    //                            break;
    //                        }
                        }
                        elseif(!$this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems) && !$this->currency_exchange->is_root_currency_in_array_key($buy_payment_systems))
                        {
                            // Получаем первую и единственную платёжнную системму которую отдают.
                            foreach ($sell_payment_systems as $k => $v)
                            {
                                $sell_ps_mn = $k;
                                $sell_summa = $sell_user_payment_summa[$k];
                                $select_curency_sell_ps = isset($select_curency_sell_payment_systems[$k])?$select_curency_sell_payment_systems[$k]:false;

                                break;
                            }

                            $summ_total_for_not_wt = $this->currency_exchange->get_equil_usd_summ($sell_ps_mn, $sell_summa, $select_curency_sell_ps);

                            if($summ_total_for_not_wt === false)
                            {
                                $error = _e('currency_exchange/controller/error_order_not_save');
                            }
                            else
                            {
                                $this->load->model('users_model', 'users');

                                $bonus_for_not_wt = 6;

                                $res_check_user_for_payout = $this->users->check_user_for_payout($user_id, ($summ_total_for_not_wt+$sell_sum_fee), FALSE, $bonus_for_not_wt);

                                if($res_check_user_for_payout !== TRUE)
                                {
                                    $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );
                                }
                            }
                        }
                    }                














                    if($error !== FALSE)
                    {
                        accaunt_message($data, $error, 'error');
                    }
//                    elseif ($res_check_user_for_payout !== true)
//                    {
//                        accaunt_message($data, $res_check_user_for_payout, 'error');
//                    }
                    else
                    {
                        $sell_data = array( 'payment_systems' => $sell_payment_systems,
                                               'amount' => 1,
                                               'fee' =>$sell_sum_fee,
                                               'total' => $sell_sum_total,
                                );

                        $buy_data = array( 'payment_systems' => $buy_payment_systems,
                                              'amount_down' => 1,
                                              'amount_up' => 0 );

                        $this->db->trans_start();
//vre($type, $status, $user_id, $sell_data, $buy_data);
                        $res_adding = $this->currency_exchange->add_order( $type, $status, $user_id, $sell_data, $buy_data );
//vred($res_adding);
                        if( FALSE !== $res_adding )
                        {
                            $this->currency_exchange->add_payment_system($res_adding, Currency_exchange_model::ORDER_TYPE_BUY_WT, $sell_payment_systems, $sell_user_payment_summa, $select_curency_sell_payment_systems);
                            $this->currency_exchange->add_payment_system($res_adding, Currency_exchange_model::ORDER_TYPE_SELL_WT, $buy_payment_systems, $buy_user_payment_summa, $select_curency_buy_payment_systems);


                            // Если sell == visa 
                            // То делаем prefund проплату, снимает деньги, ложим их в буффер.
                            if(!empty($data->sell_user_payment_summa['wt_visa_usd'])) {
                                $pref_res = $this->currency_exchange->prefund_first_transaction(
                                    [
                                        'card_id'   => $_SESSION['visa_select_card_id'], 
                                        'amount'    => $data->sell_user_payment_summa['wt_visa_usd'], 
                                        'order_id'  => $res_adding, 
                                        'user_id'   => $this->users->getCurrUserId()
                                    ]
                                );
                                if(!empty($pref_res['error'])) {
                                     accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                                    return false;
                                }
                                unset($_SESSION['visa_select_card_id']);
                                // В данном блоке должна быть взаимосвязь, а именно после создания заявки мы получаем order_id и его вставляем в запись в Prefund


                            } else if(!empty($data->buy_user_payment_summa['wt_visa_usd'])) {
                                $buy_amount_visa = $data->buy_user_payment_summa['wt_visa_usd'];
                                if($buy_amount_visa < 50) {
                                        accaunt_message($data, _e('По одной из валют сумма задана меньше 50.'), 'error');
                                        $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');
                                        return false;
                                }
                            }
                            #adding data for a single PS - new search

                            $this->currency_exchange->set_single_payment_system( $res_adding );
                            #//adding data for a single PS - new search

                            #adding discount for orders in details
                            $ps = [];
                            $ps['buy'] = [$buy_payment_systems, $buy_user_payment_summa, $select_curency_buy_payment_systems];
                            $ps['sell'] = [$sell_payment_systems, $sell_user_payment_summa, $select_curency_sell_payment_systems];

                            if(!$this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems) && !$this->currency_exchange->is_root_currency_in_array_key($buy_payment_systems))
                            {
                                $this->currency_exchange->set_order_discount_for_not_wt( $res_adding );
                            }
                            else
                            {
                                #SB - выставление заявки
                                $set_status_sb = $this->currency_exchange->is_set_status_sb( $res_adding, 0);

                                list($minus_discount, $wt_ps, $other, $comment) = $this->currency_exchange->add_payment_system_discount( $ps, $res_adding );
                                $this->currency_exchange->add_order_discount( $wt_ps, $other, $res_adding );
                                #//adding discount for orders in details
//
                                if( !empty( $set_status_sb && $set_status_sb['result'] === TRUE ) )
                                {
                                    if( $set_status_sb['action'] == 'statusSB' )
                                    {
                                        $note_arr = [
                                            'order_id' => $res_adding,
                                            'text' => $set_status_sb['comment'],
                                            'date_modified' => date('Y-m-d H:i:s'),
                                        ];

                                        $this->currency_exchange->add_operator_note($note_arr);

                                        $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
                                        $this->currency_exchange->set_status_to_order_arhive_and_order($res_adding, $status,$status);

                                        $this->currency_exchange->send_mail($res_adding, 'order_processing', $user_id);
                                        accaunt_message($data, _e('currency_exchange/controller/order_accepded_for_review'));
                                    }else
                                        if( $set_status_sb['action'] == 'error' )
                                        {
                                            accaunt_message($data, $set_status_sb['action_data']['text'], 'error' );

                                            $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');
                                            return;
                                        }

                                }

                            }

//                            vred('>>>>>>>>>>>');
                            //Добавляем seller_amount в случае если не вт на не вт.
                            $res = $this->currency_exchange->add_seller_amount_is_not_wt($res_adding);

                            if($res !== true)
                            {
                                accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');

                                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');
                            }
//                            vred($this->db->last_query());



//                            vred('>>>>>>>>>>>');
                            //Добавляем seller_amount в случае если не вт на не вт.
                            $res = $this->currency_exchange->add_seller_amount_is_not_wt($res_adding);

                            if($res !== true)
                            {
                                accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');

                                $this->redirect_iframe(site_url('account/currency_exchange/sell'), 'refresh');
                            }
//                            vred($this->db->last_query());

                            if($status == Currency_exchange_model::ORDER_STATUS_PROCESSING)
                            {
                                if($res_check_user_for_payout !== true)
                                {
                                    $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );
                                    $note_arr = [
                                        'order_id' => $res_adding,
                                        'text' => $error ,
                                        'date_modified' => date('Y-m-d H:i:s'),
                                    ];

                                    $this->currency_exchange->add_operator_note($note_arr);
                                }

                                $this->currency_exchange->send_mail($res_adding, 'order_processing', $user_id);
                                accaunt_message($data, _e('currency_exchange/controller/order_accepded_for_review'));
                            }
                            else
                            {
                               accaunt_message($data, _e('currency_exchange/controller/order_save'));
                            }

                            $data->buy_payment_systems = array();
                            $data->sell_payment_systems = array();
                            $data->buy_amount_down = FALSE;
                            $data->sell_sum_amount = FALSE;
                            $data->sell_sum_total = false;
                            $data->sell_sum_fee = false;
                            $data->sell_user_payment_summa = array();
                            $data->buy_user_payment_summa = array();

                            $this->db->trans_complete();
                            
                            $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                        }
                        else
                        {
                           accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                        }
                    }
                }
            }

            // Для заполнения ошибки меняем местами данные
            // $data->buy_payment_systems и $data->sell_payment_systems для
            // корректного заполнения полей в случае ошибки так как это было
            // сделано выше в метод  _get_type_in_addiction_post_data()
            if($type == Currency_exchange_model::ORDER_TYPE_BUY_WT &&
                !empty($data->sell_payment_systems) &&
                !empty($data->buy_payment_systems) &&
                !empty($this->input->post('real_buy_payment_systems', TRUE))
            )
            {
                $data->sell_payment_systems = $data->buy_payment_systems;
                $data->buy_payment_systems = $this->input->post('real_buy_payment_systems', TRUE);
            }
        }

        if($iframe === true){
            $this->content->iframe_user_view($viever_g, $data, $title_g);
        }

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
        {
            $this->content->user_view($viever_g, $data, $title_g);
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
                        array("title_accaunt" => $title_g );

            $this->load->view('user/currency_exchange/'.$viever_g, $view_data );
        }
    }


    // TODO удалить потом
//    public function buy()
    private function buy()
    {
        $viever_g = "currency_exchange/buy_wt";
        $title_g = 'Куплю';
        $type = Currency_exchange_model::ORDER_TYPE_BUY_WT;
//        pred($_POST);
        $this->_curency_excahnge($viever_g, $title_g, $type);
    }


    public function ajax_get_cards_user() {
        $user_id = $this->users->getCurrUserId();
        $this->load->model('card_model', 'card_model');
        $cards = $this->card_model->getCards($user_id);
        $v = $this->input->post('v', TRUE);
        
        $output = array();

        if(count($cards) > 0) {
            $cards_result = array();

            foreach ($cards as $card) {
                $cards_result[] = 
                array(
                    'id'   => $card->id,
                    'name' => $card->name_on_card . ' **** ' . substr( $card->pan, -4 )
                );
            }  
            
            $text = _e('Выберите карту');       
            if( empty($v) || $v === 'select-1' )$text = _e('Выберите карту, на которую хотите получить средства');
            
            
            $output = array(
                'success' => 1, 
                'result'  => $cards_result,
                'text' => $text
            );
        } else {
            $output = array(
                'error'  => 1,
                'text' => sprintf(_e('У вас нет доступных карт. <a href="%s" >Создайте</a> или <a href="%s" >импортируйте</a> карту и выставите заявку снова.'), '/link1', '/link2')
            );  
        }

        echo json_encode($output);
        return true;
    }

    public function ajax_get_cards_user_full() {
        $user_id = $this->users->getCurrUserId();
        $this->load->model('card_model', 'card_model');
        $cards = $this->card_model->getCards($user_id);

        $cards_result = array();
        foreach ($cards as $card) {
            $info = $this->card_model->getCardHolderInfo($card);
            $cards_result[] = $info;
        }
        
        echo json_encode(
            array(
                'success' => '1', 
                'result' => $cards_result
            )
        );
        return true;
    }

    public function ajax_check_visa_money_from_order() {
        $card_id  = $this->input->post('card_id', true);
        $order_id = $this->input->post('order_id', true);
        $user_id  = $this->account->get_user_id();

        $res['success'] = 0;

        $valid_user = $this->currency_exchange->is_user_in_this_order_archive($user_id ,$order_id);
        
        if($valid_user === false ) {
            $res['text'] = _e('Извините, не удалось получить данные карты. Перезагрузите страницу и попробуйте еще раз.');
            echo json_encode($res);
        }


        $this->load->model('Card_prefunding_transactions_model', 'prefund');



       
        // Узнаем сумму из заявки
        $order = $this->currency_exchange->get_original_order_by_id($order_id);

        // if($order->status != Currency_exchange_model::ORDER_STATUS_CONFIRMATION) {
        //     $res = ['error' => _e('Заявка не может быть обработана из за несоответствия статуса'), 
        //     'success' => 0];
        //     echo json_encode($res);
        //     return false;
        // }
        $amount = $order->buy_payment_data_un[115]->summa;

        if(empty($amount)) 
            $res['text'] = _e('Не удалось получить данные заявки. Обратитесь к оператору через « Есть проблема »');



        $this->load->model('card_model', 'card_model');
        $card = $this->card_model->getUserCard( $card_id, $user_id );
        $balance = $this->card_model->getBalance($card);

        if($balance < $amount) {
            $res['text'] = _e('На карте недостаточно средств');
            $res['show'] = 1;
        }

        if(empty($res['text']))
            $res['success'] = 1;

        // print_r($res);
        echo json_encode($res);
    }

    private function _get_amount_with_comission($amount) {
        if(empty($amount)) return false;

        // 1%
        return $amount * 1.01;
    }

    public function ajax_check_visa_card_money() {
        $card_id = $this->input->post('card_id', true);
        $money   = $this->input->post('money', true);
        $user_id = $this->account->get_user_id();
        $res     = [ 'error' => 1 ];
        
        if( empty( $card_id ) || empty( $user_id ) ) {
            $res['text'] = _e('Извините, не удалось получить данные карты. Перезагрузите страницу и попробуйте еще раз.');
            echo json_encode($res);
            return;
        }
        
        $this->load->model('card_model', 'card_model');
        $card = $this->card_model->getUserCard( $card_id, $user_id );

        if (empty($money)) {
            $res['text']  = _e('Пожалуйста, выберите карту и повторите попытку.');
        } else if (empty($card)) {
            $res['show'] = 1;
            $res['text'] = sprintf(_e('У вас нет доступных карт. <a href="%s" >Создайте</a> или <a href="%s" >импортируйте</a> карту'), '/link1', '/link2');
        } else {
            
            
            $balance = $this->card_model->getBalance($card);

            $money = $this->_get_amount_with_comission($money);

            if($money < $this->_get_amount_with_comission(50)){
                $res['show'] = 1;
                $res['text']  = _e('The minimum amount should be more than 50 usd');
            }
            else if( $balance >= $money ) {
                $res['success'] = 1;
                unset($res['error']);
            } else {
                $res['show']  = 1;
                //$res['text']  = _e('You have not enough money in the account');
                $res['text'] =  _e('You don\'t have enough funds on the card. Please, input smaller amount or select different card.');
            }
        }

        echo json_encode($res);
    }

    private function delete_from_initiator($order_id) {
        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->load->model('card_model', 'card_model');
        // $order_id = $this->input->post('order_id', true);

        if(empty($order_id)) {
            $res['text'] = _e('Не удалось получить данные заявки. Перезагрузите страницу и повторите попытку.');
            $res['success'] = false;            
            return false;
        }
        
        $user_id = $this->account->get_user_id();
        $res = array();
        $archive_order = $this->currency_exchange->get_order_arhiv_by_id($order_id);
        if(empty($archive_order)) {
            $res['text'] = _e('Не удалось найти заявку');
            $res['success'] = false;            
            return false;
        }

        $original_order_id = $archive_order->original_order_id;

        $order_from_initiator = $this->currency_exchange->get_order_archive_only_initiator($original_order_id);
        

        if($order_from_initiator->seller_user_id != $user_id) {
            $res['text'] = _e('Это не ваша заявка');
            $res['success'] = false;
            // echo json_encode($res);
//die("2--");            
            return false;
        }

        $prefunds = $this->prefund->get_by_order_id($original_order_id);

        if(count($prefunds) != 2) {
            $res['text'] = _e('Ошибка в префанде');
            $res['success'] = false;   
//die("3--");            
            // echo json_encode($res);
            return false;
        }

        if(!$this->prefund->is_paired_free($prefunds)) {
            $res['text'] = _e('Ошибка в префанде');
            $res['success'] = false;   
            
//die("4--");            
            // echo json_encode($res);
            return false;   
        }

        $user_id_return_money = $prefunds[0]->debet_uid;

        if(empty($user_id_return_money)) {
            $res['text'] = _e('Неизвестно кому отправлять обратно префанд');
            $res['success'] = false;   
//die("5--");            
          // echo json_encode($res);
          return false;   
      }

      $card_id = $prefunds[0]->debet_cid;
      if(empty($card_id)) {
          $res['text'] = _e('Неизвестно id карты для отправки');
          $res['success'] = false;   
//die("6--");            
          // echo json_encode($res);
          return false;  
      }

      $debit_amount_fee = $this->prefund->get_debit_amount_fee_by_value($original_order_id);
      //$amount = $prefunds[0]->amount;
      $amount = $debit_amount_fee;//$amount + $this->currency_exchange->get_comission_from_amount($amount);
      if(empty($amount)) {
          $res['text'] = _e('Неизвестно сумма для отправки');
          $res['success'] = false;   
//die("7--");            
          // echo json_encode($res);
          return false;  
      }


      // $id_rand = time().'_PMCRON'.rand(1,1000);      
      // $loadMoney_data = new stdClass();
      // $loadMoney_data->id = $id_rand;
      // $loadMoney_data->card_id = $card_id;
      // $loadMoney_data->user_id = $user_id_return_money;
      // $loadMoney_data->summa = $amount;
      // $loadMoney_data->desc = 'return to user from delete order. order_id: '.$original_order_id;

      // $response = $this->card_model->load($loadMoney_data, card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);
      
      // if(empty($response)) {
      //     $res['text'] = _e('Не удалось перевести деньги на карту');
      //     $res['success'] = false;   
      //     echo json_encode($res);
      //     return false;  
      // }
/*
      $this->prefund->return_money($original_order_id, $card_id, $user_id, $amount);

      
      //delete order
      $this->currency_exchange->set_status_to_order_arhive_and_order($original_order_id, Currency_exchange_model::ORDER_STATUS_REMOVED, Currency_exchange_model::ORDER_STATUS_REMOVED);
      
      $this->currency_exchange->send_mail($archive_order->original_order_id, 'order_contragent_reject', $user_id);
      $this->currency_exchange->send_mail($archive_order->original_order_id, 'order_contragent_reject', $user_id, true);


      accaunt_message($data, _e('currency_exchange/controller/you_order_reject'));
*/
      $res['text'] = _e('Заявка была удалена');
      $res['success'] = true;
      // echo json_encode($res);
      return true;
      
    }

    public function ajax_make_prefund_first_transaction() {
        $card_id  = $this->input->post('card_id', true);
        $order_id = $this->input->post('order_id', true);
        $money    = $this->input->post('money', true);
        $user_id  = $this->account->get_user_id();

        $order = $this->currency_exchange->get_original_order_by_id($order_id);
        $amount = $order->buyer_amount_down;
        $archive_orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id);

        if($amount < 50) {
            $res = ['error' => _e('Сумма визы должна быть больше чем 50 USD'), 
            'success' => 0];
            echo json_encode($res);
            return false;
        }

        if($order->status != Currency_exchange_model::ORDER_STATUS_CONFIRMATION) {
            $res = ['error' => _e('Данная заявка находится не в статусе « Сведена, активна » и не может быть оплачена.'), 
            'success' => 0];
            echo json_encode($res);
            return false;
        }

        $skip_next_step = false; 
        

        $this->db->trans_start();
        
        $this->currency_exchange->set_buyer_user_id($order_id, $user_id);
        
        if($archive_orders[0]->wt_set == 1 && $archive_orders[0]->confirmation_step == 12) {
            $this->currency_exchange->set_confirmation_step($order_id, 13);
            $visa_ps_id = 115;
            $skip_next_step = true;
            $this->currency_exchange->set_payed_system_order_archive($order_id, $visa_ps_id);
        } else {
            $this->currency_exchange->set_confirmation_step($order_id, 3);
        }
   

        $res['success'] = 0;;
        // Узнаем сумму из заявки
        

        if(empty($amount)) 
            $res['text'] = 'dont find any order by this order_id';

        $this->load->model('card_model', 'card_model');
        $card = $this->card_model->getUserCard( $card_id, $user_id );
        $balance = $this->card_model->getBalance($card);

        if($balance < $amount * 1.01) {
            $res['text'] = _e('На карте недостаточно средств');
            $res['show'] = 1;
        }

        if(empty($res['text']))
            $res['success'] = 1;

        $res = $this->currency_exchange->prefund_first_transaction(
            [
                'card_id'   => $card_id, 
                'amount'    => $amount, 
                'order_id'  => $order_id, 
                'user_id'   => $user_id
            ]
        );

        if(!empty($res['error'])) {
            echo json_encode($res);
            return false;
        }
        
        $confirm_id = $archive_orders[1]->id;
        $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);
        $user_id = $archive_orders[1]->seller_user_id;
        $visa_ps_id = 115;

        // if($confirm_order->wt_set == 1 && $confirm_order->payed_system == $visa_ps_id) {
        if($confirm_order->wt_set == 1 && $skip_next_step === true) {
            $first_transaction = $this->first_trasaction_to_visa($confirm_order, $user_id, $confirm_id);
            // $res['success'] = 0;

            // if($first_transaction === true)
                $resp1 = $this->step_seller_confirm_v2($confirm_order, $user_id, $confirm_id);
        }

        if($res['success'] == 1 ) {
            $this->db->trans_complete();
            $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');

        }

        echo json_encode($res);
    }

    public function ajax_prefund_save_card_from() {
        $card_id = $this->input->post('card_id', true);
        
        $user_id = $this->account->get_user_id();
        
        $this->load->model('card_model', 'card_model');
        $card = $this->card_model->getUserCard( $card_id, $user_id );
        
        if( empty( $card ) ) return FALSE;
        
        $_SESSION['visa_select_card_id'] = $card_id;
    }

    /*private function _is_have_visa_pair($payment_systems) {
        if(empty($payment_systems))
            return false;

        if(count($payment_systems)>1) {
            foreach ($payment_systems as $k => $v) {
                if($k == 'wt_visa_usd')
                    return true;
            }
        }
        return false;
    }*/


    public function ajax_prefund_take_money() {
        // $order_id = $this->input->post('order_id', true);
        // $user_id = $this->account->get_user_id();
        // $this->load->model('Card_prefunding_transactions_model', 'prefund');


        // $valid_user = $this->currency_exchange->is_user_in_this_order_archive($user, $order_id);
        // if($valid_user === false) {
        //     echo json_encode(['success' => 0, 'error' => 1, 'text' => _e('Извините, не удалось получить данные карты. Перезагрузите страницу и попробуйте еще раз.')]);
        //     die;
        // }

        // $prefund_first_record = $this->prefund->get_by_order_id($order_id);
        // $res = $this->prefund->check_before_second_transaction($order_id, $user_id, $prefund_first_record[0]->amount);

        // if( $res['success'] == 1) {
        //     $order = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id);
        //     $order = $order[0];
        //     $order_data = unserialize($order->sell_payment_data);

        //     if(empty($order_data[0]->payment_data)) {
        //         echo json_encode(['success' => 0, 'error' => 1, 'text' => _e('Извините, не удалось получить данные карты. Перезагрузите страницу и попробуйте еще раз.')]);
        //         die;
        //     }
        //     $card_id = $order_data[0]->payment_data;


        //     $this->currency_exchange->prefund_second_transaction([
        //         'card_id'  => $card_id,
        //         'amount'   => $prefund_first_record[0]->amount,
        //         'order_id' => $order_id,
        //         'user_id'  => $user_id
        //     ]);

        //     $this->currency_exchange->set_success_order($order_id);
        // }
        
        // echo json_encode( $res );
    }
  

    // public function ajax_pay_from_visa_card() {  
    //     $user2_card_id = htmlspecialchars($_GET['pay_from_id']);
    //     $original_order_id = htmlspecialchars($_GET['order_id']);
        
    //     $user2_id = $this->users->getCurrUserId();
        
    //     $this->load->model('card_model', 'card_model');

    //     $user2_card = $this->card_model->getUserCard($user2_card_id, $user2_id);

    //     if(empty($user2_card)) { // проверяем является ли эта карта картой пользователя
    //         echo json_encode(
    //             array(
    //                 'error' => '1', 
    //                 'result' => 'not valid card'
    //             )
    //         );
    //         return false;
    //     }

    //     $this->load->model('currency_exchange_model', 'currency_exchange_model');
    //     $original_order = $this->currency_exchange_model->get_order_by_id($original_order_id);
    //     $ps_id_visa = 115;
    //     // $pay_to_card_number = $original_order->buy_payment_data_un[$ps_id_visa]->payment_data;
    //     $user1_id_card = $original_order->buy_payment_data_un[$ps_id_visa]->payment_data;
    //     $user1_id      = $original_order->buy_payment_data_un[$ps_id_visa]->user_id;
    //     $user1_card = $this->card_model->getUserCard($user1_id_card, $user1_id);

    //     $price = $original_order->buyer_amount_down;
    //     $user2_balance = $this->card_model->getBalance($user2_card);
    //     if($user2_balance < $price) {
    //         echo json_encode(
    //             array(
    //                 'error' => '1', 
    //                 'result' => 'not enought balance'
    //             )
    //         );
    //         return false;
    //     }

    //     // меняем статус заявок
        
    //     $archive_orders = $this->currency_exchange_model->get_archive_orders_by_orig_order_id($original_order_id);
        
    //     $is_valid_orders = array(false, false);
    //     $i=0;
    //     foreach ($archive_orders as $archive_order) {
    //         if($archive_order->confirmation_step == 0) {
    //             $data_order = array(
    //                 'seller_confirmed' => 1,
    //                 'confirmation_step  ' => 3,
    //                 'seller_get_money_date' => date('Y-m-d H:i:s'),
    //                 'buyer_send_money_date' => date('Y-m-d H:i:s'),

    //             );
    //             $this->db
    //                 ->where('id', $archive_order->id)
    //                 ->update('currency_exchange_orders_arhive', $data_order);
    //             $is_valid_orders[$i] = true;
    //             $i++;
    //         }
    //     }
    
    //     if($is_valid_orders[0] == true && $is_valid_orders[1] == true) {


            
    //         // Забираем деньги у пользователя 2
    //         $id_rand = time().'_PMCRON'.rand(1,1000);
    //         $purchaseMoney_data = [];
    //         $purchaseMoney_data['card_id']  = $user2_card->id;
    //         $purchaseMoney_data['user_id']  = $user2_id;
    //         $purchaseMoney_data['id'] = $id_rand;
    //         $purchaseMoney_data['summa'] = $price;
    //         $purchaseMoney_data['desc'] = 'purchase from p2p ';
    //         $response2 = $this->card_model->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_PAY_WT_ACCOUNT, $id_rand);

    //         // Даем деньги пользователю 1
    //         $id_rand = time().'_PMCRON'.rand(1,1000);      
    //         $loadMoney_data = new stdClass();
    //         $loadMoney_data->id = $id_rand;
    //         $loadMoney_data->card_id = $user1_card->id;
    //         $loadMoney_data->user_id = $user1_id;
    //         $loadMoney_data->summa = $price;
    //         $loadMoney_data->desc = 'load from p2p ';
    //         $response1 = $this->card_model->load($loadMoney_data, Card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);

    //         // обновляет баланс
    //         $user2_balance = $this->card_model->getBalance($user2_card);
    //         $user1_balance = $this->card_model->getBalance($user1_card);
            
    //         echo json_encode(
    //             array(
    //                 'success' => '1',
    //             )
    //         );
    //         return true;            
    //     } else {
    //         echo json_encode(
    //             array(
    //                 'error' => '1',
    //             )
    //         );
    //         return true;            
    //     }


    // }

    public function ajax_set_user_payment_data() {
        $card_number = $this->input->post('card_id', true);

        $this->load->model('currency_exchange_model', 'currency_exchange_model');

        $user_id = $this->users->getCurrUserId();
        
        $this->load->model('card_model', 'card_model');
        $card = $this->card_model->getUserCard($card_number, $user_id);
        
        if( empty( $card ) ) 
        {            
            echo json_encode(
                array(
                    'error' => '1', 
                )
            );
            return false;
        }
        
        $this->currency_exchange_model->save_user_paymant_data_without_check($user_id, 115, $card_number);

        
        // $card = $this->card_model->getCard($card_number);
        //$card_info = $this->card_model->getCardHolderInfo($card);

        //$this->currency_exchange_model->save_user_paymant_data_without_check($user_id, 115, $card_info['result']['pan']);
        // $this->currency_exchange_model->save_user_paymant_data_without_check($card->id);
        // print_r($card_info);
        
        
        echo json_encode(
            array(
                'success' => '1', 
            )
        );
        return false;
    }

    public function sell()
    {
        $viever_g = "currency_exchange/sell_wt_new";

        $title_g = 'P2P Webtransfer';
//        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;
        $data = new stdClass();
        $data->show_payment_data = true;
//        $data->show_payment_summa = true;
        $data->input_settings_class_for_ps = '';
//        $data->is_test_user = in_array($this->account->get_user_id(), $this->currency_exchange->p2p_test_users);
//        $type = $this->_get_type_in_addiction_post_data();
        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;

        $this->_curency_excahnge($viever_g, $title_g, $type, $data);
    }



    public function close()
    {
        $this->content->user_view('p2p_close');
    }


    // TODO устарела не нужна
//    private function _getSummAndFee($sell_sum_amount, $payment_system_machine_name)
//    {
//        $payment_system_machine_name = 'wt';
//
//        $sell_sum_fee = $this->currency_exchange->calculate_fee( $sell_sum_amount, $payment_system_machine_name  );
//
//        $sell_sum_total = $sell_sum_amount + $sell_sum_fee;
//
//        return array($sell_sum_fee, $sell_sum_total);
//    }




    //page
    public function orders() {
        $data = new stdClass();

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
        {
            $this->content->user_view("currency_exchange/orders", $data, 'Заявки на обмен');
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
                        array("title_accaunt" => 'Заявки на обмен' );

            $this->load->view('user/currency_exchange/orders', $view_data );
        }
    }

    //ajax functions

    public function ajax() {
//redirect if it's not an AJAX request
        $this->base_model->redirectNotAjax();

        $arg_num = func_num_args();
        if ($arg_num < 1)
            $this->redirect_iframe(site_url('/'));

        $arg_list = func_get_args();

        $func_name = 'ajax_' . $arg_list[0];

//there is no such method
        if (!method_exists($this, $func_name))
            $this->redirect_iframe(site_url('/'));

        $func_params = NULL;
        if ($arg_num > 1) {
            $func_params = Array();
            for ($i = 1; $i < $arg_num; $i++)
                $func_params[] = $arg_list[$i];
        }
        $this->{$func_name}($func_params);
    }



    private function ajax_get_country_bank_data()
    {
        $id = $this->input->post('id', true);
        $checkbox_name = $this->input->post('checkbox_name', true);

        $this->db->where('branch_parent_id',0);
        $pay_sys = $this->currency_exchange->get_pay_sys_by_country_id($id);

        $data = new stdClass();

        $data->pay_sys = $pay_sys;
        $data->checkbox_name = $checkbox_name;

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/show_country_bank_content.php', $view_data );
    }



    private function ajax_get_add_bank_in_payment_system()
    {
        $id = $this->input->post('id', true);
        $checkbox_name = $this->input->post('checkbox_name', true);

        if(empty($id) || empty($checkbox_name))
        {
            return false;
        }

        $user_id = $this->account->get_user_id();

//        $pay_sys = $this->currency_exchange->get_payment_systems_by_id($id);

        $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $payment_systems_key_id = array_set_value_in_key($payment_systems);

        $pay_sys = $payment_systems_key_id[$id];

        if(empty($pay_sys))
        {
            $pay_sys = Currency_exchange_model::get_ps($id);
        }

        if(empty($pay_sys))
        {
            return false;
        }

        $data = new stdClass();

        $data->payment_system = $pay_sys;
        $data->checkbox_name = $checkbox_name;

//        $data->name = 'currency_name_'.$pay_sys->machine_name;
        $data->name = Currency_exchange_model::get_ps($pay_sys->id)->humen_name;

        $data->currencys = $this->currency_exchange->get_all_activ_currencys();

        $data->currencys_country_id = array_set_value_in_key($data->currencys, 'country_id');
        $data->currencys_id = array_set_value_in_key($data->currencys);
//        pred($data->currencys_country_id);
        if(empty($this->input->post('save_user_data', true)))
        {
            $data->save_user_data = (bool)$this->input->post('save_user_data', true);
        }
        else
        {
            $data->save_user_data = ($checkbox_name == 'buy_payment_systems');
        }


//        no_show_select_currency
        $data->no_show_select_currency = (bool) $this->input->post('no_show_select_currency', true);

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/show_bank.php', $view_data );
    }



    private function ajax_curency_problem()
    {
//        pred($_POST);
        $this->_curency_problem();
    }



    private function ajax_get_all_chat_messages()
    {
        if(!isset($_POST['id']))
        {
            echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_not_set_chat_id')));
            return false;
        }

        $id = $this->input->post('id', true);

        $res = $this->currency_exchange->get_all_user_problem_chat($id);

        if($res === FALSE)
        {
            echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_chat_data_not_load')));
            return false;
        }

        echo json_encode($res);
        die;
    }


    private function ajax_last_user_confirm()
    {
        if(!isset($_POST['last_user_confirm']))
        {
             echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_not_set_chat_id')));
             return false;
        }

        $id = $this->input->post('id', true);

        $res = $this->currency_exchange->get_last_user_confirm_data($id);

        if(!empty($res))
        {
            echo json_encode($res);
            return false;
        }

        echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_chat_data_not_load')));
        return false;
    }

    private function ajax_last_user_confirm1()
    {
        if(!isset($_POST['last_user_confirm']))
        {
             echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_not_set_chat_id')));
             return false;
        }

        $id = $this->input->post('id', true);

        $res = $this->currency_exchange->get_last_user_confirm_data($id);

        if(!empty($res))
        {
            echo json_encode($res);
            return false;
        }

        echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_chat_data_not_load')));
        return false;
    }


    private function ajax_buyer_confirm_receipt()
    {
       if($this->input->post('last_user_confirm') != 1 || !$this->input->post('id', true))
       {
            echo json_encode(array('error'=>1, 'text' =>  _e('currency_exchange/controller/error_not_set_order_id')));
            return false;
       }

       $id = $this->input->post('id', true);

       $res = $this->currency_exchange->get_user_confirm_data($id);

        if(!empty($res))
        {
            echo json_encode($res);
            return false;
        }

        echo json_encode(array('error'=>1, 'text' => _e('currency_exchange/controller/error_chat_data_not_load')));
        return false;
    }


//    $type = Currency_exchange_model::ORDER_TYPE_BUY_WT;

    private function ajax_load_more_orders_sell_arhive()
    {
        $orders_on_list = $this->input->post('orders_on_list');
        $curent_list = $this->input->post('curent_list');

        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;

        $data = $this->_load_more_orders_arhive($type, $orders_on_list, $curent_list);

        $data->res_search = $data->orders;
        $data->table_search_set_title_contragent = true;
        $data->curency_problem = $this->currency_exchange->get_all_curency_problem_subject();

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', $view_data );
    }


    //TODO проверить если используется
    private function ajax_load_more_orders_buy_arhive()
    {
        $orders_on_list = $this->input->post('orders_on_list');
        $curent_list = $this->input->post('curent_list');

        $type = Currency_exchange_model::ORDER_TYPE_BUY_WT;

        $data = $this->_load_more_orders_arhive($type, $orders_on_list, $curent_list);

        $data->res_search = $data->orders;

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', $view_data );
    }



    private function ajax_load_more_orders_sell()
    {
        $orders_on_list = $this->input->post('orders_on_list');
        $curent_list = $this->input->post('curent_list');

        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;

        $data = $this->_load_more_orders($type, $orders_on_list, $curent_list);

        $data->res_search = $data->orders;

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', $view_data );
    }



    private function ajax_load_more_orders_buy()
    {
        $orders_on_list = $this->input->post('orders_on_list');
        $curent_list = $this->input->post('curent_list');

        $type = Currency_exchange_model::ORDER_TYPE_BUY_WT;

        $data = $this->_load_more_orders($type, $orders_on_list, $curent_list);

        $data->res_search = $data->orders;

        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', $view_data );
    }


    private function ajax_step_3_new_sell_check_payment_limit()
    {
        $data = new stdClass();
        $data->sell_payment_systems = $sell_payment_systems = $this->input->post('sell_payment_systems', true);
        $data->input_summa_sell_payment_systems = $sell_user_payment_summa = $this->input->post('input_summa_sell_payment_systems', true);

        $data->buy_payment_systems = $buy_payment_systems = $this->input->post('buy_payment_systems', true);
        $data->input_summa_buy_payment_systems = $buy_user_payment_summa = $this->input->post('input_summa_buy_payment_systems', true);

        $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
        $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');

        $res = $this->currency_exchange->check_limit_input_payment_summ($buy_payment_systems, $sell_payment_systems,$buy_user_payment_summa, $sell_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);
//        pred($res);

        if(TRUE === $res)
        {
            echo json_encode(array('res' => 'ok', 'text' => '1'));
            return false;
        }
        else
        {
//            echo json_encode(array('res' => 'error', 'text' => sprintf(_e('Сумма в валюте обмена не должна быть меньше 70%% (%s USD) от суммы номинала.'), $res) ));
//             Okpay: Превышен максимальный размер дискнта (30USD)
            if( $res[3] === TRUE )
//                 accaunt_message($data, sprintf(_e('Сумма в валюте обмена не должна быть меньше 70%% (%s USD) от суммы номинала.'), $res_check_limit_input_payment_summ), 'error');
            echo json_encode(array('res' => 'error', 'text' => sprintf(_e('%s: Превышен максимальный размер дисконта (%s %s)'), $res[0], $res[1], $res[2]) ));
            else
                echo json_encode(array('res' => 'error', 'text' => sprintf(_e('max_discount'), $res[0], $res[1], $res[2]), 't2' => _e('max_discount') ) );


            return false;
        }
    }



    private function ajax_step_3_new_sell()
    {
        $data = new stdClass();
        $data->sell_payment_systems = $sell_payment_systems = $this->input->post('sell_payment_systems', true);
        $data->input_summa_sell_payment_systems = $sell_user_payment_summa = $this->input->post('input_summa_sell_payment_systems', true);

        $data->buy_payment_systems = $buy_payment_systems = $this->input->post('buy_payment_systems', true);
        $data->input_summa_buy_payment_systems = $buy_user_payment_summa = $this->input->post('input_summa_buy_payment_systems', true);

        $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
        $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');

        $data->select_curency_sell = $select_curency_sell_payment_systems?$select_curency_sell_payment_systems:[];
        $data->select_curency_buy = $select_curency_buy_payment_systems?$select_curency_buy_payment_systems:[];

        $res = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);

        $data->no_wt = 0;
//        if(!key_exists('wt', $sell_payment_systems) && !key_exists('wt', $buy_payment_systems))
        if(!$this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems) &&
           !$this->currency_exchange->is_root_currency_in_array_key($buy_payment_systems)
          )
        {
            $data->no_wt = 1;
        }

        $data->fee = $res[0];
        $data->summ = $res[1];
        $data->percentage = $res[2];
        $data->error = $res[3];

        $arr_sys = $sell_payment_systems+$buy_payment_systems;

        $arr_sys_keys = array_keys($arr_sys);

        $data->fee_ps = $this->currency_exchange->get_root_currency_from_array($arr_sys_keys);

        $user_id = $this->account->get_user_id();
        $data->payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $data->visa_fee = FALSE;
        foreach($arr_sys_keys as $ps_machine_name)
        {
            if( $ps_machine_name == 'wt_visa_usd' ) $data->visa_fee = TRUE;
            if(!isset($data->payment_systems[$ps_machine_name]))
            {
                $data->payment_systems[$ps_machine_name] = Currency_exchange_model::get_ps($ps_machine_name);
            }
        }

        $data->curent_user_data = viewData()->curent_user_data;
        
        $view_data = get_object_vars($data);
        $this->load->view('user/accaunt/currency_exchange/blocks/form/form_table_step_3_new_sell.php', $view_data );
    }



    private function _load_more_orders($type, $orders_on_list, $curent_list)
    {
        if(empty($orders_on_list) || empty($curent_list))
        {
            return array();
        }

        $data = new stdClass();
        $user_id = $this->account->get_user_id();
        /*
        $status = '(status = '.Currency_exchange_model::ORDER_STATUS_SET_OUT.' OR status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.
                    ' OR status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING_SB.')';
        $status .= 'AND !(status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.' AND buyer_user_id != 0)';
        */
        $status = '(status = '.Currency_exchange_model::ORDER_STATUS_SET_OUT.' OR status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.
                    ' OR status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING_SB.')';
        $status .= 'AND !(status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.' AND buyer_user_id != 0)';

//        $status = 'status = 10 OR ( buyer_user_id = 0 AND status >= 8 AND status <= 9 )';
        $status = 'status <= 10';


        $data->orders = $orders = $this->currency_exchange->get_user_orders($status, $user_id);


        if(empty($data->orders))
        {
            $data->orders = array();
        }else{
            foreach( $data->orders as $k => $o )
            {
                if( $o->buyer_user_id != 0 && ( $o->status == 8 || $o->status == 9 ) )  unset( $data->orders[$k] );
            }
        }

        $count = 0;

        $data->count_all_orders = count($data->orders);

        $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        foreach($data->orders as $key => $val)
        {
            ++$count;
            if( $count <= ($curent_list-1)*$orders_on_list || $count > $curent_list*$orders_on_list  )
            {
                unset($data->orders[$key]);
            }
            else
            {
                foreach($val->payment_systems as $ps)
                {
                    if(!isset($payment_systems[$ps->machine_name]))
                    {
                        $payment_systems[$ps->machine_name] = Currency_exchange_model::get_ps($ps->payment_system);
                    }
                }

            }
        }

        $data->user_id = $user_id;
        $data->button = FALSE;

        $data->payment_systems = $payment_systems;

        foreach($payment_systems as $val)
        {
            $payment_systems_id_arr[$val->id] = $val;
        }

        $data->payment_systems_id_arr = $payment_systems_id_arr;

        return $data;
    }



    private function _load_more_orders_arhive($type, $orders_on_list, $curent_list)
    {
        if(empty($orders_on_list) || empty($curent_list))
        {
            return array();
        }

        $data = new stdClass();
        $user_id = $this->account->get_user_id();

//        $status = '(status = '.Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED.')';
//
//        $data->orders = $orders = $this->currency_exchange->get_user_orders( $status, $user_id);

        $status = Currency_exchange_model::ORDER_STATUS_SUCCESS;
        $data->orders_arhive_confirmed = $this->currency_exchange->get_user_orders_arhive_confirmed($type, $status, $user_id);
//        pred($data->orders_arhive_confirmed);
        if(empty($data->orders))
        {
            $data->orders = array();
        }

        if(empty($data->orders_arhive_confirmed))
        {
            $data->orders_arhive_confirmed = array();
        }

        $data->orders = array_merge($data->orders, $data->orders_arhive_confirmed);

        $count = 0;

        $data->count_all_orders = count($data->orders);

        foreach($data->orders as $key => $val)
        {
            ++$count;
            if( $count <= ($curent_list-1)*$orders_on_list || $count > $curent_list*$orders_on_list  )
            {
                unset($data->orders[$key]);
            }
        }

        $data->user_id = $user_id;
        $data->button = FALSE;

        $payment_systems = $this->currency_exchange->get_all_payment_systems();
        $data->payment_systems = $payment_systems;

        foreach($payment_systems as $val)
        {
            $payment_systems_id_arr[$val->id] = $val;
        }

        $data->payment_systems_id_arr = $payment_systems_id_arr;

        return $data;
    }



    private function _set_data_search()
    {
        $data = new stdClass();
        $data->user_id = $user_id = $this->account->get_user_id();

        $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $data->payment_systems = $payment_systems;

        $payment_systems_groups = $this->currency_exchange->get_all_payment_systems_groups();
        $data->payment_systems_groups = $payment_systems_groups;

        $data->buy_amount_down = 0;
        $data->buy_amount_up = 0;

        $data->sell_amount_down = 0;
        $data->sell_amount_up = 0;

        $data->sell_sum_total = 0;
        $data->sell_sum_amount = 0;
        $data->sell_sum_fee = 0;

        $data->buy_payment_systems = array();
        $data->sell_payment_systems = array();

        $protection_type = Security::getProtectType($user_id);
        $data->security = $protection_type;

        return $data;
    }


    /*private function ajax_sell_search()
    {
        $data = $this->_set_data_search();

        $post_res = $this->_seach_post_data($data);

        $per_page = 5;
        $page = $this->input->post('page');
        if ( empty($page))
            $page = 1;
        $page = (int)$page;

        $element_num = ($page - 1) * $per_page;
        $limit = array( $per_page, $element_num );


        $data->res_search = array();
        if($post_res !== FALSE)
        {
            $data = $post_res[0];
            $search_data = $post_res[1];

            $data->res_search = $res_search = $this->currency_exchange->search(Currency_exchange_model::ORDER_STATUS_SET_OUT, $search_data, $limit );
            $payment_systems_id_arr = array();
            $payment_systems = $data->payment_systems;
            foreach($payment_systems as $val)
            {
                $payment_systems_id_arr[$val->id] = $val;
            }

            $data->payment_systems_id_arr = $payment_systems_id_arr;
        }

        if($this->input->post('submited', TRUE) == 1 && empty($res_search))
        {
            $data->error_message =  _e('currency_exchange/controller/error_no_search_result');
        }
//        pred($data->error_message);
        $data->table_search_set_title_contragent = true;
        $view_data = get_object_vars($data);
//        $this->load->view('user/accaunt/currency_exchange/sell_wt_search.php', $view_data );
        $this->load->view('user/accaunt/currency_exchange/blocks/show_search_result.php', $view_data );

        $all_orders = $this->input->post('all_orders');
        $view_data['res_page'] = $page+1;
        $view_data['all_orders'] = empty($all_orders)?0:$all_orders;
        if ( !empty($res_search) ){
            $view_data['current_show_records'] = $search_data['current_show_records'];
            $view_data['total_count'] = $search_data['total_count'];
        }
        $this->load->view('user/accaunt/currency_exchange/sell_wt_search.php', $view_data );
    } */

    /**
     * Выводит детали заявки в таблице поиска
     */
    private function ajax_search_order_details()
    {
        $id = (int)$this->input->post('id', TRUE);

        if ( empty($id) ) return FALSE;

        $data = new stdClass();
        $resp = array();



        $allowed_get_wt_orders = $this->currency_exchange->allowed_get_wt_orders();

        if( $allowed_get_wt_orders === FALSE )
        {
            $resp['error'] = _e('Не удалось получить данные заявки. Перезагрузите страницу и попробуйте еще раз.');
        }else
        if( $allowed_get_wt_orders['res'] === FALSE )
        {
            $resp['error'] = sprintf( _e('currency_exchange/max_order_get_user_wt'), $allowed_get_wt_orders['max_order_get_user_wt'] );
        }

        $order = $this->currency_exchange->get_order_by_id($id);
        $this->currency_exchange->set_fee_to_order($order);
        $this->currency_exchange->set_machin_name_to_payment_systems($order);
        $this->currency_exchange->set_dop_data_to_unserializ_payment_system($order);

        $payment_systems_id_arr = array();
        foreach($this->currency_exchange->get_all_payment_systems() as $val)
            $payment_systems_id_arr[$val->id] = $val;

        $data->user_id = $this->user_id;
        $data->val = $order;
        $data->curency_id = $order->get_fee_currency_id;
        $data->payment_systems_id_arr = $payment_systems_id_arr;
        $user_id = $this->account->get_user_id();
        $data->payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        foreach($order->payment_systems as $ps)
        {
            if(!isset($data->payment_systems[$ps->machine_name]))
            {
                $data->payment_systems[$ps->machine_name] = Currency_exchange_model::get_ps($ps->payment_system);
            }
        }

        $data->curent_user_data = viewData()->curent_user_data;

        $resp['form'] = $this->load->view('user/accaunt/currency_exchange/blocks/table_search_details.php', $data, TRUE);

        echo json_encode( $resp );
        return;
    }

    private function ajax_get_ps()
    {
        if(empty($this->input->post('ps_mn', TRUE)) || empty($this->input->post('currency_id', TRUE)))
        {
            return false;
        }

        $ps_mn = $this->input->post('ps_mn', TRUE);
        $currency_id = $this->input->post('currency_id', TRUE);
        $currency = $this->input->post('currency', TRUE);

        $user_id = $this->account->get_user_id();

        if(empty($user_id))
        {
            return false;
        }

//        $ps_all = $this->currency_exchange->get_user_all_paymant_data($user_id);
//
//        if(empty($ps_all))
//        {
//            return false;
//        }
//
//        $ps = $ps_all[$ps_mn];

        $ps = Currency_exchange_model::get_ps($ps_mn);

        if(empty($ps))
        {
            return false;
        }

        $show_currency = $currency?$currency:_e('currency_id_'.$currency_id);

//        echo sprintf(_e('currency_exchange/translate_js/error_fill_all_data'),_e('currency_name_'.$ps->machine_name).' '.$show_currency);
        echo sprintf(_e('currency_exchange/translate_js/error_fill_all_data'),Currency_exchange_model::get_ps($ps->id)->humen_name.' '.$show_currency);

        return false;

    }



    private function ajax_sell_search()
    {

        $data = $this->_set_data_search();
        $post_res = $this->_seach_post_data($data);

        $length = $this->input->post('length', TRUE);
        $start = $this->input->post('start', TRUE);
        $sort = $this->input->post('order', TRUE);
        $filters = $this->input->post('filter', TRUE);

        $limit = array( $length, $start );
        $table_search_set_value_contragent = false;
        $rows = [];
        if($post_res !== FALSE)
        {
      
            
            $search_data = $post_res[1];
            $data = $post_res[0];
            $res_search = $this->currency_exchange->search(Currency_exchange_model::ORDER_STATUS_SET_OUT, $search_data, $limit, $sort, $filters );

            $payment_systems_id_arr = array();
            foreach($data->payment_systems as $val)
                $payment_systems_id_arr[$val->id] = $val;


            foreach ( $res_search as $val)
            {
                $res = new stdClass();

                //col 1
                $res->DT_RowId = 'row_'.$val->id;
                $res->id = $val->id;
                $res->discount = $val->discount;
                $res->id_str  = $val->id . $this->load->view('user/accaunt/currency_exchange/blocks/not_read_message_chat.php', array('order' => $val), TRUE);
//                $res->id_str .=  '<input type="hidden" name="id" value="'.$val->id.'">';
                //col 2
                $res->seller = ' - ';
                if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS ){
                        if(isset($table_search_set_value_contragent) && $table_search_set_value_contragent === true)
                           $res->seller =  $val->second_order->seller_user_id;
                        else
                           $res->seller =  $val->seller_user_id;

//                        $res->seller = hide_data( $res->seller );
                        $res->seller = '**'. substr( $res->seller,2, 4 ).'**';
                }


                //col 3
                if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS ){
                    if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $this->user_id ){
                        $res->sell = $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system, 'payment_systems_id_arr' => $payment_systems_id_arr), TRUE);
                    } else {
                        $show_payment_system = isset($val->payed_system)?$val->payed_system:FALSE;
                         $res->sell = $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system, 'payment_systems_id_arr' => $payment_systems_id_arr), TRUE);
                    }
                 }else {
                        $show_payment_system = FALSE;
                        $res->sell = $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system, 'payment_systems_id_arr' => $payment_systems_id_arr), TRUE);
                 }

                //col 4
                 $res->seller_set_up_date = date('d.m.y',strtotime($val->seller_set_up_date));

               //col 5
               if($val->status < Currency_exchange_model::ORDER_STATUS_UNSUCCESS ){
                        if(isset($val->confirm_type) && $val->type == $val->confirm_type || !isset($val->confirm_type) && $val->seller_user_id == $this->user_id){
                            $show_payment_system = isset($val->payed_system)?$val->payed_system:FALSE;
                            $res->buy = $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system), TRUE);
                        } else {
                            $show_payment_system = isset($val->sell_system)?$val->sell_system:FALSE;
                            $res->buy = $this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_SELL_WT, 'show_payment_system' => $show_payment_system), TRUE);
                        }
                    } else {
                           $show_payment_system = FALSE;
                           $res->buy = '1';//$this->load->view('user/accaunt/currency_exchange/blocks/show_payment_systems.php', array('val' => $val, 'order_type' => Currency_exchange_model::ORDER_TYPE_BUY_WT, 'show_payment_system' => $show_payment_system), TRUE);
                    }

              // col 6
                  if(isset($button) && $button == 'confirme'){
                        $res->status_action =  '<a href="#" class="confirme_action">'._e('currency_exchange/table_search/confirm').'</a>';
                  } elseif(isset($button) && $button === FALSE){
                         if ($this->user_id ==  $val->seller_user_id  && $val->buyer_confirmed != 1 && !($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS)){
                             if($val->status == Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED)
                                 $res->status_action = _e('currency_exchange/table_search/reject');
                             elseif($val->status == Currency_exchange_model::ORDER_STATUS_CANCELED)
                                $res->status_action = _e('currency_exchange/table_search/canceled');
                             elseif($val->status == Currency_exchange_model::ORDER_STATUS_PROCESSING){
                                $res->status_action = _e('currency_exchange/table_search/processing');
                                $res->status_action .= '<form action="" method="post">
                                    <input type="hidden" name="processing_cancell" value="1"/>
                                    <input type="hidden" name="delete_id" value="'.$val->id.'"/>
                                    <a href="#" class="redB but" onclick="$(this).parent().submit(); return false;">'._e('currency_exchange/table_search/delete').'</a></form>';
                             } else{
                                $res->status_action = '<form action="" method="post">
                                    <input type="hidden" name="delete" value="1"/>
                                    <input type="hidden" name="delete_id" value="'.$val->id.'"/>
                                    <a href="#" class="redB but" onclick="$(this).parent().submit(); return false;">'._e('currency_exchange/table_search/delete').'</a></form>';
                             }
                         }
                  }
                     else{
                         if ($this->user_id ==  $val->seller_user_id)
                            $res->status_action = _e('currency_exchange/table_search/you_order');
                         else{
                             $temp_sell_payment_data = $val->sell_payment_data_un;
                             $first_sell_payment_data = array_shift($temp_sell_payment_data);

//                             if($first_sell_payment_data->machine_name == 'wt')
                             if($this->currency_exchange->is_root_currency($first_sell_payment_data->machine_name))
                                $res->status_action = '<a href="#" class="table_green_button" onclick="exchange_action_wt($(this)); return false;">'._e('currency_exchange/table_search/change').'</a>';
                             else
                                $res->status_action = '<a href="#" class="exchange_action" onclick="return false;">'._e('currency_exchange/table_search/change').'</a>';
                         }
                    }

                     if($val->buyer_confirmed == 1 && $val->seller_confirmed == 1)
                        $res->status_action = _e('currency_exchange/table_search/end');


                     if($val->status >= Currency_exchange_model::ORDER_STATUS_HAVE_PROBLEM && $val->status < Currency_exchange_model::ORDER_STATUS_SUCCESS)
                        $res->status_action = _e('currency_exchange/table_search/problem');

                  $res->status_action .=  '<input type="hidden" name="id" value="'.$val->id.'">';
                  $rows[] = $res;

            } //end foreach
//            if( count( $res_search ) > $length ) break
        }

        //if($this->input->post('submited', TRUE) == 1 && empty($res_search))
          //  $data->error_message =  _e('currency_exchange/controller/error_no_search_result');

        echo json_encode([
            'draw' => $_POST['draw'],
            'recordsTotal' => $search_data['total_count'],
            'recordsFiltered' => $search_data['total_count'],
            'data' => $rows]
       );
    }



    private function ajax_out_table() {

    }



    private function ajax_orders_table() {

    }



    private function ajax_get_dop_ps_data_multi_field()
    {
        $id = $this->input->post('id',TRUE);

        if(empty($id))
        {
            return false;
        }

//        $user_id = $this->account->get_user_id();
//        $ps_all = $this->currency_exchange->get_user_all_paymant_data($user_id);

//        $ps_all = array_set_value_in_key($ps_all);



//        if(!isset($ps_all[$id]))
//        {
//            return false;
//        }

//        $ps = $ps_all[$id];
//        pred($ps);
//        vre($ps->method_get_field_and_ps_data);

        $ps = Currency_exchange_model::get_ps(intval($id));

        if(empty($ps))
        {
            return false;
        }

        if(!method_exists($this->currency_exchange, $ps->method_get_field_and_ps_data))
        {
            return false;
        }

        echo  $this->currency_exchange->{$ps->method_get_field_and_ps_data}($ps, 'template_sell_ajax', false);

//        echo Currency_exchange_model::get_field_and_ps_data($ps[$id]);

        return false;
    }



    private function ajax_set_dop_ps_data_multi_field()
    {

        $id = $this->input->post('id',TRUE);
        $post = $this->input->post();

        $user_id = $this->account->get_user_id();

        if(Security::checkSecurity($user_id))
        {
            $message = false;
            message_check($message);
            $text = $message['send'];
            echo json_encode(array('res' => 'error', 'text' => $text));
            return false;
        }

        if(empty($post) || empty(intval($id)))
        {
            echo json_encode(array('res' => 'error', 'text' => _e('Ошибка')));
            return false;
        }

//        $ps = $this->currency_exchange->get_all_payment_systems();
//
//        $ps_key = array_set_value_in_key($ps);
//
//        if(!isset($ps_key[$id]))
//        {
//            echo json_encode(array('res' => 'error', 'text' => _e('Ошибка')));
//            return false;
//        }
//
//        $curent_ps = $ps_key[$id];

        $curent_ps = Currency_exchange_model::get_ps($id);

        if(empty($curent_ps))
        {
            echo json_encode(array('res' => 'error', 'text' => _e('Ошибка')));
            return false;
        }

        if(!method_exists($this->currency_exchange, $curent_ps->method_set_ps_data))
        {
            echo json_encode(array('res' => 'error', 'text' => _e('Ошибка')));
            return false;
        }
        unset(
            $post['id'],
            $post['code'],
            $post['purpose']
                );

        $post_check = [];

        foreach ($post as $k => $v)
        {
            $post_check[$k] = $this->input->post($k,true);
        }

        $res = $this->currency_exchange->{$curent_ps->method_set_ps_data}($post_check,$curent_ps);
//        vre($_POST);
//        vre($curent_ps->method_set_ps_data);
//        vred($res);
        if($res[0] !== false)
//        {
////            $input_data = serialize($post);
//            $input_data = $res[0];
//
//            $this->currency_exchange->save_user_paymant_data_without_check($this->account->get_user_id(), $curent_ps->machine_name, $input_data);
//
//            echo json_encode(array('res' => 'ok', 'text' => ''));
//
//            return false;
//        }
//        if($res !== false)
        {
            echo json_encode(array('res' => 'ok', 'text' => ''));

            return false;
        }
        else
        {
            echo json_encode(array('res' => 'error', 'text' => $res[1], 'field' => $res[2]));
            return false;
        }
    }



    private function _get_error__check_user_for_payout( $res_check_user_for_payout )
    {
        if( !is_numeric( $res_check_user_for_payout ) ) return $res_check_user_for_payout;

        $error = $res_check_user_for_payout;
        switch( $res_check_user_for_payout )
        {
            case 20: $error =  _e('accaunt/applications_42');////Для проведение данной операции Вам необходимо погасить просроченные стандартные займы
                break;
            case 21: $error =  _e('accaunt/applications_43');//Для проведение данной операции Вам необходимо погасить просроченные гарантированные займы
                break;
            case 22: $error =  _e('accaunt/applications_41_1');//Для подачи заявки, просьба загрузить документы, и дополнить данные в профайле
                break;
        }

        return $error;
    }

    private function ajax_check_user_for_payout()
    {
        $sell_payment_systems = is_array($this->input->post('sell_payment_systems'))?$this->input->post('sell_payment_systems'):array();
        $sell_user_payment_summa = $this->input->post('input_summa_sell_payment_systems');

        $buy_payment_systems = is_array($this->input->post('buy_payment_systems'))?$this->input->post('buy_payment_systems'):array();
        $buy_user_payment_summa = $this->input->post('input_summa_buy_payment_systems');

        $bonus = 5;//заменить на номер счета валюты
        $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
        $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');

        $bonus = $this->currency_exchange->get_bonus_by_currency( $sell_payment_systems );

        if(empty($sell_payment_systems) ||
                empty($sell_user_payment_summa) ||
                empty($buy_payment_systems) ||
                empty($buy_user_payment_summa)
           )
        {
            echo json_encode(array('res' => 'ok', 'text' => '1'));
            return false;
        }

//        if(array_key_exists('wt', $sell_payment_systems) !== true)
        if(!$this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems))
        {
            echo json_encode(array('res' => 'ok', 'text' => ''));
            return false;
        }
                                                                                        //getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $bonus)
//        list($sell_sum_fee, $sell_sum_total, $percent, $error) = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $bonus);

        list($sell_sum_fee, $sell_sum_total, $percent, $error) = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);

        $user_id = $this->account->get_user_id();


        #checking phone or decuments - cut bots
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('phone_model', 'phone');
        $userDocumentStatus = $this->accaunt->isUserDocumentVerified();
        $isStatusVerified = $this->phone->isStatusVerified( $user_id );
        if( $isStatusVerified === FALSE && $userDocumentStatus === FALSE ){
            echo json_encode(array('res' => 'error', 'text' => _e('upload_docs_or_verify_phone')));
            return false;
        }


        $this->load->model('users_model', 'users');
        $res_check_user_for_payout = $this->users->check_user_for_payout($user_id, $sell_sum_total, false, $bonus);

        if($res_check_user_for_payout !== true)
        {
            $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );
            echo json_encode(array('res' => 'error', 'text' => $error));
            return false;
        }

        echo json_encode(array('res' => 'ok', 'text' => ''));
        return false;


    }

    private function ajax_check_buyer_rating()
    {
        $exchange_id = $exchange_id = $this->input->post('exchange_id');

        if(empty($exchange_id))
        {
            $text = _e('Ошибка получения данных заявки');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $order = $this->currency_exchange->get_order_by_id($exchange_id);

        if(empty($order))
        {
            $text = _e('Ошибка получения данных заявки');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $error = $this->currency_exchange->check_user_rating_to_order_confirm($order);

        if($error)
        {
            $text = $error;
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        // Убрано, теперь замечание от этого блока идут к оператору
//        if($order->wt_set == 2)
//        {
//            $user_id = $this->account->get_user_id();
//
//            $this->load->model('users_model', 'users');
//            $res_check_user_for_payout = $this->users->check_user_for_payout($user_id, $order->seller_amount);
//
//            if($res_check_user_for_payout !== true)
//            {
//                echo json_encode(array('res' => 'error', 'text' => $res_check_user_for_payout, 'type' => 10));
//                return false;
//            }
//        }

        echo json_encode(array('res' => 'ok', 'text' => $text, 'type' => 10));
    }



    private function ajax_get_summ_and_fee()
    {
        $sell_payment_systems = is_array($this->input->post('sell_payment_systems'))?$this->input->post('sell_payment_systems'):array();
        $sell_user_payment_summa = $this->input->post('input_summa_sell_payment_systems');

        $buy_payment_systems = is_array($this->input->post('buy_payment_systems'))?$this->input->post('buy_payment_systems'):array();
        $buy_user_payment_summa = $this->input->post('input_summa_buy_payment_systems');

        $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
        $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');

        $res = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems);

        $no_wt = 0;
//vred($sell_payment_systems,$buy_payment_systems);
//        if(!key_exists('wt', $sell_payment_systems) && !key_exists('wt', $buy_payment_systems))
        if( !$this->currency_exchange->is_root_currency_in_array_key($sell_payment_systems) &&
            !$this->currency_exchange->is_root_currency_in_array_key($buy_payment_systems) )            
        {
            $no_wt = 1;
        }

        list( $error_check_payment_systems_limit, $res_ps ) = $this->currency_exchange->check_payment_systems_limit($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa, $select_curency_sell_payment_systems, $select_curency_buy_payment_systems, TRUE);

        if( $error_check_payment_systems_limit === true )
        {
            $ps = Currency_exchange_model::get_ps($res_ps);
            $res[3] = sprintf( _e('Сумма %s не должна превышать 1000 USD или её эквивалента'), $ps->humen_name );
        }

        echo json_encode(array('fee' => $res[0], 'summ' => $res[1], 'no_wt' => $no_wt, 'percentage' => $res[2], 'error' => $res[3] ));

        die;
    }



    private function ajax_save_user_data_payment()
    {
        if(!isset($_POST['value']) || !$_POST['value'] || !isset($_POST['payment_system']) )
        {
            return false;
        }

        $user_id = $this->account->get_user_id();
        $sec_res = Security::checkSecurity($user_id);

        if( $sec_res )
        {
            $message = false;
            message_check($message);
            $text = $message['send'];

            echo json_encode(array('res' => 'error', 'text' => $text));
            return false;
        }
        $payment_system = $this->input->post('payment_system', TRUE);
        $value = $this->input->post('value', TRUE);

        $res = $this->currency_exchange->save_user_paymant_data($this->account->get_user_id(), $payment_system, $value);

        if($res === TRUE)
        {
            $res = 'ok';
        }
        elseif (is_array($res) && $res[0] === false)
        {
            echo json_encode(array('res' => 'error' , 'text' => $res[1]));

            return false;
        }
        else
        {
            $res = 'error';
        }

        echo json_encode(array('res' => $res));

        die;
    }



    private function ajax_save_user_data_new_payment()
    {
        if(!isset($_POST['value']) )
        {
            return false;
        }

//        pred($_POST);

        $value = trim(strtolower($this->input->post('value',true)));
        $group = intval($this->input->post('group',true));

        if(empty($value) || empty($group))
        {
            $res = 'error';
            $text = _e('currency_exchange/controller/error_ps_not_addedet');

            echo json_encode(array('res' => $res, 'text'=>$text));
            return false;
        }

        $data = array(
            'name' => $value,
            'url' => trim(strtolower($this->input->post('url',true))),
            'group_id' => $group,
            'country_id' => intval($this->input->post('country',true)),
        );

//        $res = $this->currency_exchange->save_new_user_payment_system($this->input->post('value'));
        $res = $this->currency_exchange->save_new_user_payment_system($data);

        if($res === TRUE)
        {
            $res = 'ok';
            $text = _e('currency_exchange/controller/this_ps_soon_addedet');
        }
        elseif ($res == 'add')
        {
            $res = 'ok';
            $text = _e('currency_exchange/controller/this_ps_soon_addedet');
        }
        elseif ($res == 'set')
        {
            $res = 'ok';
            $text = _e('currency_exchange/controller/this_ps_alredy_addedet');
        }
        else
        {
            $res = 'error';
            $text = _e('currency_exchange/controller/error_ps_not_addedet');
       }

       echo json_encode(array('res' => $res, 'text'=>$text));

       die;
    }


    private function ajax_exchange_confirmation()
    {
        $user_id = $this->account->get_user_id();

        $user_array = array();

        if(//array_search($user_id, $user_array) === false ||
                Currency_exchange_model::SWITCH_OFF === TRUE )
        {
            $res = 'error';
            $text =  _e('currency_exchange_closed_error');
            $type = 10;

            echo json_encode(array('res' => $res, 'text' => $text, 'type' => $type));

            return FALSE;
        }

        $exchange_id = $this->input->post('exchange_id');
        $select_payment_systems_sell = $this->input->post('select_payment_systems_sell');
        $sms = $this->input->post('sms');

        // для тестовых пользователей
        if(0&!in_array($user_id, $this->currency_exchange->p2p_test_users))
        {
            $order = $this->currency_exchange->get_order_by_id($exchange_id);
            if(empty($order) || array_key_exists($order->bonus, $this->test_p2p_wt))
            {
                $text = _e('currency_exchange_closed_error');
                echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
                return false;
            }
        }


        if($this->input->post('correct') != 1)
        {
            $text = _e('currency_exchange/controller/error_not_confirm_correct_requisites');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        if(empty($exchange_id))
        {
            $text = _e('currency_exchange/controller/error_operation_no_carried_out');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        if(empty($select_payment_systems_sell))
        {
            $text = _e('currency_exchange/controller/error_no_select_ps');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $user_id = $this->account->get_user_id();

        if(Security::checkSecurity($user_id))
        {
            $message = false;
            message_check($message);
            $text = $message['send'];
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $order = $this->currency_exchange->get_order_by_id($exchange_id);

        if($order->status != Currency_exchange_model::ORDER_STATUS_SET_OUT)
        {
            $text = _e('currency_exchange/controller/inquery_inactive');
            $this->currency_exchange->set_status_to_details( $exchange_id, $order->status );

            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }
        
        if( time() - strtotime( $order->seller_set_up_date ) < 20 + rand(0,5) )
        {
            $res = 'error';
            $text =  _e('Заявка проходит проверку. Повторите попытку позже.');
            $type = 10;

            echo json_encode(array('res' => $res, 'text' => $text, 'type' => $type));

            return FALSE;
        }

        $res_check_user_for_payout = TRUE;

        if($order->wt_set == 2 || $order->wt_set == 0 )
        {
            #берем ид инциатора
            if( $order->wt_set == 0 ) $uid = $order->seller_user_id;
            
            $this->load->model('users_model', 'users');
            $res_check_user_for_payout = $this->users->check_user_for_payout($uid, $order->seller_amount + $order->seller_fee, null, $order->bonus);
            
            $visa_ps_id = 115;
            $sell_ps_id = unserialize($order->sell_payment_data)[0]->payment_system_id;

            if($sell_ps_id == $visa_ps_id) {
                // Если инициатор отдает визу, то никаких проверок на наличие комисси по вт.

                // Явно указываем что ошибки быть недолжно, потому что у нас уже виза!

                // $amount = $order->seller_amount;
                $order_details = $this->currency_exchange->get_order_details_by_initiator($order->id);
                $amount = $order_details[0]->summa;
                // Теперь проверка наличи данных в префанде, и там у нас есть комиссия!
                $this->load->model('Card_prefunding_transactions_model', 'prefund');
                $is_succes_prefs = $this->prefund->is_succes_prefund_items_by_origin_id($order->id, $amount);

                $res_check_user_for_payout = true;

                if($is_succes_prefs['error'] === true) {
                    $note_arr = [
                        'order_id' => $order->id,
                        'text' => 'При закрытии заявки произошла ошибка, несоответствия записей в префанде',
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

                   $this->currency_exchange->add_operator_note($note_arr);
                    //go to sb
                    $this->currency_exchange->set_status_to_details_orig_order($order->id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);

                    $error = _e('Заявка находится на проверке СБ. Пожалуйста, выберите другую заявку.');
           
                    
                    echo json_encode(array('res' => 'error', 'text' => $error, 'type' =>51));
                    $this->redirect_iframe(site_url('account/currency_exchange/sell_search'), 'refresh');
                    return false;
                }
            }

            if($res_check_user_for_payout !== true)
            {
                $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );
                
                if( $order->wt_set == 0 ) 
                    if( strpos($error, 'Недостаточно средств') !== FALSE ) {
                        #меняем сообщение для инциатора
                        $error = _e('Заявка удалена из поиска. У контрагента недостаточно средств для резерва и оплаты комисии по сделке. Попробуйте взять другую заявку.');
                        
                        #отменяем заявку инициатора с записью причины отклонения
                        $reject_text = _e('Недостаточно средств для резерва и оплаты комиссии по сделке');
                        $res = $this->currency_exchange->set_status_operator_canceled($order->id);
                        $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $order->id, 'text' => $reject_text, 'date_modified' => date('Y-m-d'), 'text_to_user' => $reject_text ) );
                        $this->currency_exchange->send_mail($order->id, 'order_processing_reject', $uid);
                        
                        echo json_encode(array('res' => 'error', 'text' => $error, 'type' => 10));
                        return false;
                    }
                   
                    if( strpos($error, 'Аккаунт не верифицирован') !== FALSE ) {
                        #меняем сообщение для инциатора
                        $error = _e('Заявка удалена из поиска. Контрагент не верифицирован. Попробуйте взять другую заявку.');
                        
                        #отменяем заявку инициатора с записью причины отклонения
                        $reject_text = _e('Ваш аккаунт не прошел верификацию.');
                        $res = $this->currency_exchange->set_status_operator_canceled($order->id);
                        $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $order->id, 'text' => $reject_text, 'date_modified' => date('Y-m-d'), 'text_to_user' => $reject_text ) );
                        $this->currency_exchange->send_mail($order->id, 'order_processing_reject', $uid);
                        
                        echo json_encode(array('res' => 'error', 'text' => $error, 'type' => 10));
                        return false;
                    }
                    
                
            }
            
        }

        $error = $this->currency_exchange->check_user_rating_to_order_confirm($order);

        if($error !== false)
        {
            $text = $error;
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        //проверяем - можно ли еще брать этому пользователю заявки на получение ВТ
        $verified_docs = $this->accaunt->isUserDocumentVerified();
        if( $verified_docs === FALSE )
        {
            $error = _e('upload_docs_or_verify_phone');
            echo json_encode(array('res' => 'error', 'text' => $error, 'type' => 10));
            return false;
        }

        if($order->wt_set == 1)
        {
            $allowed_get_wt_orders = $this->currency_exchange->allowed_get_wt_orders();
            $error = FALSE;

            if( $allowed_get_wt_orders === FALSE )
            {
                $error = _e('Не удалось получить данные заявки. Перезагрузите страницу и попробуйте еще раз.');
            }else
            if( $allowed_get_wt_orders['res'] === FALSE )
            {
                $error = sprintf( _e('currency_exchange/max_order_get_user_wt'), $allowed_get_wt_orders['max_order_get_user_wt'] );
            }

            if( $error !== false)
            {
                $text = $error;
                echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
                return false;
            }
        }

        if($order->wt_set == 1 && !$this->currency_exchange->check_user_initiator_rating_to_order_confirm($order))
        {
//            $note_arr = [
//                'order_id' => $order->id,
//                'text' => 'Отрицательная сумма доступно' ,
//                'date_modified' => date('Y-m-d H:i:s'),
//            ];

            //$this->currency_exchange->add_operator_note($note_arr);

            //$res = $this->currency_exchange->set_status_to_details_orig_order($order->id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);

            $text = _e('Заявка удалена из поиска. У контрагента недостаточно средств для резерва и оплаты комисии по сделке. Попробуйте взять другую заявку.');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            
            #отменяем заявку инициатора с записью причины отклонения
            $reject_text = _e('Недостаточно средств для резерва и оплаты комиссии по сделке');
            $res = $this->currency_exchange->set_status_operator_canceled($order->id);
            $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $order->id, 'text' => $reject_text, 'date_modified' => date('Y-m-d'), 'text_to_user' => $reject_text ) );
            $this->currency_exchange->send_mail($order->id, 'order_processing_reject', $order->seller_user_id);
            
            return false;
        }

        $order_payment_systems = array_set_value_in_key($order->payment_systems, 'payment_system');

        if(
            !isset($order_payment_systems[$select_payment_systems_sell]) ||
             $order_payment_systems[$select_payment_systems_sell]->type != Currency_exchange_model::ORDER_TYPE_BUY_WT
          )
        {
            $text = _e('currency_exchange/controller/error_no_select_ps');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }


        $payment_data = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $payment_data_by_id = array_set_value_in_key($payment_data);

        foreach($order->payment_systems as $ps)
        {
            if(!isset($payment_data_by_id[$ps->payment_system]))
            {
                $payment_data_by_id[$ps->payment_system] = Currency_exchange_model::get_ps($ps->payment_system);
                $payment_data[$ps->machine_name] = $payment_data_by_id[$ps->payment_system];
            }
        }

//        pred($payment_data_by_id);
        if(
//            $payment_data_by_id[$select_payment_systems_sell]->machine_name != 'wt' &&
            !$this->currency_exchange->is_root_currency($payment_data_by_id[$select_payment_systems_sell]->machine_name) &&
            (!isset($payment_data_by_id[$select_payment_systems_sell]->payment_sys_user_data)
            || !$payment_data_by_id[$select_payment_systems_sell]->payment_sys_user_data)
            )
        {
            $text = _e('currency_exchange/controller/error_no_fill_requisites_ps');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }



        $this->db->trans_start();

        $status = Currency_exchange_model::ORDER_STATUS_CONFIRMATION;
//        vred( $order );

        if(empty($order) )
        {
            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING;
            $note_arr = [
                        'order_id' => $exchange_id,
                        'text' => 'У заявки нет оригинальной. Нужно восстановление.',
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

                    $this->currency_exchange->add_operator_note($note_arr);
        }
        //временное решение
        /*отключили 2016-02-25
         elseif($order->wt_set == 0)
        {
            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
                    $note_arr = [
                                'order_id' => $exchange_id,
                                'text' => 'Заявка не WT на не WT',
                                'date_modified' => date('Y-m-d H:i:s'),
                            ];

                    $this->currency_exchange->add_operator_note($note_arr);
        }*/
//        elseif( !empty( $order ) && $order->wt_set == 2 )
//        {
//            if( $order->bonus == 6 ){
//                $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
//                $note_arr = [
//                            'order_id' => $exchange_id,
//                            'text' => 'Заявка по бонусу 6 с дисконтом > 10%',
//                            'date_modified' => date('Y-m-d H:i:s'),
//                        ];
//
//                $this->currency_exchange->add_operator_note($note_arr);
//            }
//        }


        list($arhiv_order_id, $arhiv_order_id_second) = $this->currency_exchange->buy_exchange_confirmation($exchange_id, $user_id, $status);

        if($arhiv_order_id === FALSE)
        {
            $text = _e('currency_exchange/controller/error_operation_no_carried_out');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $res = $this->currency_exchange->set_payment_system_order_arhive_buy($arhiv_order_id, $select_payment_systems_sell);

        if($res === FALSE)
        {
            $text = _e('currency_exchange/controller/error_operation_no_carried_out');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

        $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($arhiv_order_id);
        $confirm_order_sell_payment_data = unserialize($confirm_order->sell_payment_data);
        $confirm_order_buy_payment_data = unserialize($confirm_order->buy_payment_data);

        $buy_payment_id = false;
        if(count($confirm_order_buy_payment_data) == 1)
        {
           $buy_payment_data = array_shift($confirm_order_buy_payment_data);
           $buy_payment_id = $buy_payment_data->payment_system_id;
        }

//        $is_buy_wt = $this->currency_exchange->is_payment_system_wt($select_payment_systems_buy);

        $sell_payment_id = false;
        foreach ($confirm_order_sell_payment_data as $val)
        {
            if($val->payment_system_id == $select_payment_systems_sell)
            {
                $sell_payment_id = $val->payment_system_id;
            }
        }

        if($sell_payment_id === FALSE)
        {
            $text = _e('currency_exchange/controller/error_operation_no_carried_out');
            echo json_encode(array('res' => 'error', 'text' => $text, 'type' => 10));
            return false;
        }

//        vred($payment_data_by_id[$sell_payment_id]->is_bank);
        if($payment_data_by_id[$sell_payment_id]->is_bank == 1 && $res !== FALSE)
        {
            $data_order = array('bank_set' => 1);
            $res = $this->currency_exchange->set_order_data($user_id, $arhiv_order_id, $data_order, $status);
        }
//        if($payment_data_by_id[$sell_payment_id]->machine_name == 'wt' && $res !== FALSE)
        if($this->currency_exchange->is_root_currency($payment_data_by_id[$sell_payment_id]->machine_name) && $res !== FALSE)
        {
            $res = $this->currency_exchange->set_seller_confirm($user_id, $arhiv_order_id, 'wt', 0);
        }

//        if($payment_data_by_id[$sell_payment_id]->machine_name == 'wt' && $res !== FALSE)
        if($this->currency_exchange->is_root_currency($payment_data_by_id[$sell_payment_id]->machine_name) && $res !== FALSE)
        {
            $res = $this->currency_exchange->set_buyer_confirm_order_arhive($arhiv_order_id);
        }

        if($this->currency_exchange->is_root_currency($payment_data_by_id[$sell_payment_id]->machine_name) && $res !== FALSE)
        {
            $res = $this->currency_exchange->set_step_users_confirmation($arhiv_order_id,
                        ['initiator_confirm_send_money', 'kontragent_confirm_received_money']);
        }

//        if($payment_data_by_id[$buy_payment_id]->machine_name == 'wt' && $res !== FALSE)
        if($this->currency_exchange->is_root_currency($payment_data_by_id[$buy_payment_id]->machine_name) && $res !== FALSE)
        {
            //нельзя поставить для того, что не подтверджено
//            if( $status == Currency_exchange_model::ORDER_STATUS_PROCESSING ) $res = TRUE;
//            else
//                $res = $this->currency_exchange->set_confirm_order_arhive($arhiv_order_id, 'wt', $buy_payment_id, false);
            if($res === true && ($status == Currency_exchange_model::ORDER_STATUS_PROCESSING || $status == Currency_exchange_model::ORDER_STATUS_PROCESSING_SB) )
            {
                $data_order = array(
                    'status' => $status,
                    'buyer_document_image_path' => 'wt',
                    'buyer_user_id' =>  $user_id,
                    'payed_system' => $buy_payment_id,
                );

                $res = $this->currency_exchange->set_order_data($user_id, $arhiv_order_id, $data_order, $status);
//                vred($res,'@@@@@');
            }
            elseif($res === true)
            {
                $res = $this->currency_exchange->set_confirm_order_arhive($arhiv_order_id, 'wt', $buy_payment_id, false);
//                vred($res, '%%%%%');
            }

            if($res === true)
            {
                $res = $this->currency_exchange->set_step_users_confirmation($arhiv_order_id,
                        ['kontragent_confirm_send_money', 'initiator_confirm_received_money']);
            }
        }

//        if($payment_data_by_id[$buy_payment_id]->machine_name == 'wt' && $res !== FALSE)
        if($this->currency_exchange->is_root_currency($payment_data_by_id[$buy_payment_id]->machine_name) && $res !== FALSE)
        {
            if($res_check_user_for_payout !== true)
            {
                $error = $this->_get_error__check_user_for_payout( $res_check_user_for_payout );
                $note_arr = [
                    'order_id' => $exchange_id,
                    'text' => $error ,
                    'date_modified' => date('Y-m-d H:i:s'),
                ];

                $this->currency_exchange->add_operator_note($note_arr);

                $res = $this->currency_exchange->set_status_to_order_arhive_and_order($exchange_id);

                $this->currency_exchange->send_mail($exchange_id, 'order_processing', $user_id);
            }
        }




//        if( $this->accaunt->get_user_id() == 500733 ) $this->currency_exchange->get_payment_system_discount_data( $exchange_id, $select_payment_systems_sell, null, 1 );

        #SB - сведение сделки, когда берешь на бирже
        $set_status_sb = $this->currency_exchange->is_set_status_sb( $exchange_id, 1);
        if( !empty( $set_status_sb ) &&  $set_status_sb['result'] == TRUE )
        //if( $buy_payment_id == 116  && $res !== false && $order->wt_set == 2  &&  $order->discount < 0 )
        {
            $note_arr = [
                'order_id' => $exchange_id,
                'text' => $set_status_sb['comment'],
                'date_modified' => date('Y-m-d H:i:s'),
            ];

            $this->currency_exchange->add_operator_note($note_arr);

            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
            $res = $this->currency_exchange->set_status_to_order_arhive_and_order($exchange_id, $status,$status);
        }


        if(0&&$sms == 1 && $res !== FALSE)
        {
            $text =  sprintf(_e('currency_exchange/controller/you_order_accepted'), $order->id);
            $this->currency_exchange->send_sms_by_user_id($order->seller_user_id, $text, $user_id);
        }


       if($res !== FALSE )
       {


           $visa_ps_id = 115;

           if($order->wt_set == 2 && !empty($order->sell_payment_data_un[$visa_ps_id]))  {
              $archive_orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($order->id);
              
              $this->currency_exchange->set_confirmation_step($order->id, 7);
              $this->currency_exchange->set_seller_confirm_simple($order->id, 1);

              $confirm_id = $archive_orders[0]->id;
              // $confirm_id = $arhiv_order_id;
              $archive_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);
              
              $res_from_prefund = $this->last_confirm_visa($archive_order, $confirm_id);
              if($res_from_prefund === false) {
                $res = 'error';
                $text =  _e('По данной карте невозможно провести операцию из-за достигнутых ограничений. Выберите другую карту и повторите попытку');
                $type = 10;
                echo json_encode(array('res' => $res, 'text' => $text, 'type' => $type));
                return false;
              }
              $this->last_confirm_order_v2($archive_order, $confirm_id, $user_id);
           } 

           // if (!empty($_POST['visa_card_id'])) {
           //      $card_id = $this->input->post('visa_card_id', true);
           //      $this->load->model('card_model', 'card_model');
           //      $card = $this->card_model->getUserCard( $card_id, $user_id );

           //      $id_rand = time().'_PMCRON'.rand(1,1000);      
           //      $loadMoney_data = new stdClass();
           //      $loadMoney_data->id = $id_rand;
           //      $loadMoney_data->card_id = $card_id;
           //      $loadMoney_data->user_id = $user_id;
           //      $loadMoney_data->summa = 0.01;
           //      $loadMoney_data->desc = 'test';
           //      $response = $this->card_model->load($loadMoney_data, card_transactions_model::CARD_PAY_TO_CARD_ACCOUNT, $id_rand);

           //      if()
           //      $id_rand = time().'_PMCRON'.rand(1,1000);
           //      $purchaseMoney_data = [];
           //      $purchaseMoney_data['card_id']  = $card_id;
           //      $purchaseMoney_data['user_id']  = $user_id;
           //      $purchaseMoney_data['id']       = $id_rand;
           //      $purchaseMoney_data['summa']    = 0.01;
           //      $purchaseMoney_data['desc']     = 'purchase from visa prefund';
                
           //      $response = $this->card_model->purchaseMoney($purchaseMoney_data, card_transactions_model::CARD_TRANS_PREFUND_LOAD, $id_rand);

           //      var_dump($response);
           //      die;
           // }

           $this->db->trans_complete();
           $this->currency_exchange->send_mail($exchange_id, 'order_set_active', $user_id, true);

           $type = 1;
           $res = 'ok';
           $text = _e('currency_exchange/controller/you_order_success');
       }
       else
       {
           $res = 'error';
           $text =  _e('currency_exchange/controller/error_operation_no_carried_out');
           $type = 10;
       }


       echo json_encode(array('res' => $res, 'text' => $text, 'type' => $type));

       return FALSE;
    }



    public function sell_search($iframe = false)
    {
        $data = $this->_set_data_search();

        $user_id = $this->account->get_user_id();
        // для тестовых пользователей
        //if(0&!in_array($user_id, $this->currency_exchange->p2p_test_users))
        //{
            //foreach($this->test_p2p_wt as $v)
            //{
        unset($data->payment_systems['wt']);
            //}
        //}

        $data->input_settings_class_for_ps = '';

        $data->show_payment_data = true;
        $data->notAjax = $this->base_model->returnNotAjax();
        $data->show_start = FALSE;
        $data->save_user_data = FALSE;
        $data->add_bank_button_text = _e('Выбрать банк');

        if($iframe === true) {
            $data->show_start = true;
            $this->content->iframe_user_view("currency_exchange/sell_wt_search", $data, _e('Биржа P2P'));
        }

        if($data->notAjax)
        {
            $data->show_start = $this->input->get('show_start');
            if( !empty( $data->show_start ) || $data->show_start > 100 || $data->show_start < 0 ) $data->show_start = 100;

//            $this->content->user_view("currency_exchange/sell_wt_search", $data, _e('currency_exchange/sell_wt_search/search'));
            $this->content->user_view("currency_exchange/sell_wt_search", $data, _e('Биржа P2P'));
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
//                        array("title_accaunt" => _e('currency_exchange/sell_wt_search/search') );
                        array("title_accaunt" => _e('Биржа P2P') );

            $this->load->view('user/currency_exchange/sell_wt_search', $view_data );
        }
    }



    private function _seach_post_data($data)
    {
        $payment_systems = $this->currency_exchange->get_all_payment_systems();

        require_once APPPATH.'controllers/user/Security.php';

        if( $this->input->post('submited', TRUE) == 1 )
        {
            $data->sell_sum = $this->input->post('sell_sum', TRUE);
            $data->buy_amount = $this->input->post('buy_amount', TRUE);

            $buy_payment_systems = $this->input->post('buy_payment_systems', TRUE);

            $sell_payment_systems = $this->input->post('sell_payment_systems', TRUE);
            $all_orders = $this->input->post('all_orders', TRUE);

            $select_curency_sell_payment_systems = $this->input->post('select_curency_sell_payment_systems');
            $select_curency_buy_payment_systems = $this->input->post('select_curency_buy_payment_systems');

            if(empty($buy_payment_systems))
            {
                $buy_payment_systems = array();
            }

            if(empty($sell_payment_systems))
            {
                $sell_payment_systems = array();
            }

            //отключили USD1 c 10/12/2016
            if( $buy_payment_systems[ 'wt' ] )
            {
                accaunt_message($data, _e('Валюта USD1 недоступна для выставления в завке'), 'error');
                return false;
            }
            if( $sell_payment_systems[ 'wt' ] )
            {
                accaunt_message($data, _e('Валюта USD1 недоступна для выставления в завке'), 'error');
                return false;
            }

            foreach($buy_payment_systems as $k=>$v)
            {
                if(!isset($payment_systems[$k]))
                {
                    $payment_systems[$k] = Currency_exchange_model::get_ps($k);
                }
            }

            foreach($sell_payment_systems as $k=>$v)
            {
                if(!isset($payment_systems[$k]))
                {
                    $payment_systems[$k] = Currency_exchange_model::get_ps($k);
                }
            }

//            $data->payment_system_machine_name = $payment_system_machine_name = 'wt';

            $buy_error = FALSE;
            $buy_payment_systems_view = array();

            $currency_id_temp = FALSE;

            $select_curency_sell = [];
            $select_curency_buy = [];

            foreach( $buy_payment_systems as $key => &$val )
            {
                if( !isset( $payment_systems[ $key ] ) )
                {
                    unset( $buy_payment_systems[ $key ] );
                }
                else
                {
                    $buy_payment_systems_view[$key] = $val;
                    $val = $payment_systems[ $key ]->id;

                    if(isset($select_curency_buy_payment_systems[$key]) && $select_curency_buy_payment_systems[$key])
                    {
                        $select_curency_buy[$val] = $select_curency_buy_payment_systems[$key];
                    }

                    if($currency_id_temp === FALSE)
                    {
                        $currency_id_temp = $payment_systems[ $key ]->currency_id;
                    }
                }
            }
            unset($val);

            $data->buy_payment_systems = $buy_payment_systems_view;

            $sell_error = FALSE;
            foreach( $sell_payment_systems as $key => &$val )
            {
                if( !isset( $payment_systems[ $key ] ) )
                {
                    unset( $sell_payment_systems[ $key ] );
                }
                else
                {
                    $val = $payment_systems[ $key ]->id;

                    if(isset($select_curency_sell_payment_systems[$key]) && $select_curency_sell_payment_systems[$key])
                    {
                        $select_curency_sell[$val] = $select_curency_sell_payment_systems[$key];
                    }
                }
            }
            unset($val);
            $data->sell_payment_system = $sell_payment_systems;

            $data->select_curency_sell =  $select_curency_sell;
            $data->select_curency_buy =  $select_curency_buy;

            $user_id = $this->accaunt->get_user_id();

            $sell_sum_amount_src = $this->input->post('sell_sum_amount',FALSE);
            $data->sell_sum_amount = $sell_sum_amount_src;
            $sell_sum_amount = FALSE;
            if( !empty($sell_sum_amount_src) )
            {
                $sell_sum_amount = floatval( $sell_sum_amount_src );
            }

            $buy_amount_down_src = floatval( $this->input->post('buy_amount_down',FALSE) );
            $data->buy_amount_down = $buy_amount_down_src;
            $buy_amount_down = 0;
            if( !empty( $buy_amount_down_src ) )
            {
                $buy_amount_down = floatval( $buy_amount_down_src );
            }

            $buy_amount_up_src = floatval( $this->input->post('buy_amount_up',FALSE) );
            $data->buy_amount_up = $buy_amount_up_src;
            $buy_amount_up = 9999999999;
            if( !empty( $buy_amount_up_src ) )
            {
                $buy_amount_up = floatval( $buy_amount_up_src );
            }

            $sell_amount_up_src = floatval( $this->input->post('sell_amount_up',FALSE) );
            $data->buy_amount_up = $sell_amount_up_src;
            $sell_amount_up = 9999999999;
            if( !empty( $sell_amount_up_src ) )
            {
                $sell_amount_up = floatval( $sell_amount_up_src );
            }

            $sell_amount_down_src = floatval( $this->input->post('sell_amount_down',FALSE) );
            $data->buy_amount_up = $sell_amount_down_src;
            $sell_amount_down = 0;
            if( !empty( $sell_amount_down_src ) )
            {
                $sell_amount_down = floatval( $sell_amount_down_src );
            }

            if( $all_orders != 1 && (empty( $data->buy_payment_systems ) && empty($data->sell_payment_system)) )
            {
                accaunt_message($data, _e('currency_exchange/controller/error_select_ps'), 'error');
            }else
            {
                $user_ratings = viewData()->accaunt_header;

                if( $sell_error === TRUE || $buy_error === TRUE ||
                    $buy_amount_down === FALSE || $buy_amount_up === FALSE
                        || $sell_amount_up === FALSE || $sell_amount_down === false)
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_fill_data'), 'error');
                }
                else
                {
                   //TODO нужно подправить
//                   list($sell_sum_fee, $sell_sum_total) = $this->_getSummAndFee($sell_sum_amount, $payment_system_machine_name);
//                   $data->sell_sum_fee = $sell_sum_fee;
//                   $data->sell_sum_total = $sell_sum_total;

                    $search_data = array(
                        'amount' => $sell_sum_amount,
                        'amount_down' => $buy_amount_down,
                        'amount_up' => $buy_amount_up ,
                        'payment_systems' => $buy_payment_systems,
                        'sel_payment_systems' => $sell_payment_systems,
                        'sell_amount_up' => $sell_amount_up,
                        'sell_amount_down' => $sell_amount_down,
                        'all_orders' => $all_orders,
                        'select_curency_sell' =>  $select_curency_sell,
                        'select_curency_buy' =>  $select_curency_buy
                    );

                    return array($data, $search_data);
                }

            }
        }
        return false;
    }


    public function my_sell_list($iframe = false)
    {
        $data =  new stdClass();
        $user_id = $this->account->get_user_id();
        $data->user_id = $this->account->get_user_id();

        $protection_type = Security::getProtectType($user_id);
        $data->security = $protection_type;

//        vred($this->input->post());

        $this->_canceled_order();

        //удаление выставленной заявки
        if($this->input->post('delete', true) == 1 && Currency_exchange_model::SWITCH_OFF === false)
        {

            $delete_id = $this->input->post('delete_id', true);

            $order =  $this->currency_exchange->get_original_order_by_id($delete_id);
            $sell_ps_id = unserialize( $order->sell_payment_data )[0]->payment_system_id;


            if($sell_ps_id == 115) {
                $return_from_prefund = $this->currency_exchange->prefund_delete($user_id, $delete_id, Currency_exchange_model::ORDER_STATUS_SET_OUT);
                if($return_from_prefund === false) {
                    accaunt_message($data, _e('currency_exchange/controller/error_record_no_delete'), 'error');
                    $reject_text = _e('Удаление заявки пользователем, не найдена запись в префанде');
                    // $res = $this->currency_exchange->set_status_operator_canceled($delete_id);
                     $this->currency_exchange->set_status_to_details_orig_order( $delete_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB );

                     $note_arr = [
                        'order_id' => $delete_id,
                        'text' => 'При закрытии заявки произошла ошибка связанная с записями в префанде',
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

                    $this->currency_exchange->add_operator_note($note_arr); 
                     
                    // $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $delete_id, 'text' => $reject_text, 'date_modified' => date('Y-m-d'), 'text_to_user' => $reject_text ) );

                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh'); 
                    die;
                } 
            }

        







            $res = $this->currency_exchange->delete_order($user_id, $delete_id);

            if($res === FALSE)
            {
                accaunt_message($data, _e('currency_exchange/controller/error_record_no_delete'), 'error');

            }   
            else
            {   
             

                accaunt_message($data, _e('currency_exchange/controller/order_delete'));
            }


        }

        //удаление Несведенной заявки На проверке
        if($this->input->post('processing_cancell', true) == 1 && Currency_exchange_model::SWITCH_OFF === false)
        {
            ####################
            $user_array = array();

            if(//array_search($user_id, $user_array) !== false ||
                    Currency_exchange_model::SWITCH_OFF === false )
            {
            ####################
                $delete_id = $this->input->post('delete_id', true);

                $res = $this->currency_exchange->order_processing_cancell($user_id, $delete_id);

                if($res === FALSE)
                {
                    accaunt_message($data, _e('Ошибка'), 'error');
                }
                else
                {
                    accaunt_message($data, _e('currency_exchange/controller/order_delete'));
                }
            ######################
            }
            else
            {
                accaunt_message($data, _e('Ошибка'), 'error');
            }
            ####################
        }

        $data->curency_problem = $this->currency_exchange->get_all_curency_problem_subject();

        $this->_curency_problem();

        if($this->input->post('real_last_confirm') == 1 && Currency_exchange_model::SWITCH_OFF === false)
        {
            $confirm_id = $this->input->post('confirm_id', true);

            if($this->input->post('last_user_confirm_checkbox',true) != 1)
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay');
                accaunt_message($data, $text, 'error');
            }elseif( Security::checkSecurity($user_id) ){}
            else
            {   
                $archive_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);
                // Если это у нас виза, то там произойдет проверка и свои действия
                $step_1 = $this->last_confirm_visa($archive_order, $confirm_id);

                if($step_1 === true)
                    $this->last_confirm_order_v1($archive_order, $confirm_id, $user_id);
                // В данном месте был код обработки формы. Теперь он помещен в функцию

            }
        }



        if($this->input->post('confirm') == 1 && Currency_exchange_model::SWITCH_OFF === false )
        {
//            $this->load->library('image');
//            $this->image->place = "upload/tmp/";
//
//            $image = $this->image;
//            $foto = $image->file('foto', true);
            $confirm_id = $this->input->post('confirm_id');
//
//            $add_foto = $image->add_foto($foto);
////                vred($add_foto);
//            if (!empty($add_foto))
//            {
//                 $explode_res = explode('.', $add_foto);
//
//                 $file_name = md5($confirm_id.rand(1111,9999).rand(111,999).time()).'.'.$explode_res[1];
//                 $full_file_name = 'upload/currency_exchange_docs/' . $file_name;
//
//                 $tmpPath = $this->image->place . $add_foto;  //имя файла
//                 $this->code->fileCodeSave($full_file_name, $this->code->fileCode($tmpPath));
//            }
//
//            @unlink($tmpPath);

            $full_file_name = $this->currency_exchange->add_file('foto', $confirm_id);
            if(is_numeric( $full_file_name ) )
            {
                switch( $full_file_name )
                {
                    case 10: $text = sprint(_e('Загруженный размер файла превышает %s. Уменьшите размер и загрузите снова'),'2MB');
                        break;
                    case 20: $text = sprint(_e('Загруженный файл должен быть формата %s. Измените формат файла и повторите попытку'), 'jpg, .png или .gif');
                        break;
                }

                accaunt_message($data, $text, 'error');
            }else
            if($this->input->post('last_user_confirm_checkbox',true) != 1)
            {

                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay');
                accaunt_message($data, $text, 'error');
            }
            elseif(Security::checkSecurity($user_id))
            {
            }
            else
            {
                $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);
                $confirm_order_sell_payment_data = unserialize($confirm_order->sell_payment_data);

                $pay_data = $this->currency_exchange
                                ->get_payment_systems_by_id($confirm_order->sell_system);

//                if(!empty($pay_data) && $pay_data->machine_name == 'wt')
                if(!empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
                {
                    $full_file_name = 'wt';
                }

                if(!empty($full_file_name))
                {
                    $this->db->trans_start();

                    $res = $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, $full_file_name);

                    if($res != false )
                    {
                        $data_order = array('seller_send_money_date' => date('Y-m-d H:i:s'));
                        $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);
                    }

                    if($res != false )
                    {
                        $res = $this->currency_exchange->set_step_users_confirmation($confirm_order, 'initiator_confirm_send_money');
                    }

//                    if($res != false && !empty($pay_data) && $pay_data->machine_name == 'wt')
                    if($res != false && !empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
                    {
                        $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);

                    }

//                    vred('>>>>>>>>>>');
                    if($res == false)
                    {
                        accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');

                    }
                    else
                    {
                        $this->db->trans_complete();
                        accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

//                        if(!empty($pay_data) && $pay_data->machine_name == 'wt')
                        if(!empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
                        {
                            $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
                        }
                    }
                }
                else
                {
                    $text = _e('currency_exchange/controller/error_image_no_load');
                    accaunt_message($data, $text, 'error');
                }
            }
        }

        if($this->input->post('seller_confirm') == 1 && Currency_exchange_model::SWITCH_OFF === false )
        {
            $image = $this->image;
            $confirm_id = $this->input->post('confirm_id');

            if($this->input->post('last_user_confirm_checkbox',true) != 1)
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay');
                accaunt_message($data, $text, 'error');
            }
            elseif(Security::checkSecurity($user_id))
            {
            }
            else
            {   
                
                $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);

                $step_1 = $this->first_trasaction_to_visa($confirm_order, $user_id, $confirm_id);
                if($step_1 === true){
                    
                    $this->step_seller_confirm($confirm_order, $user_id, $confirm_id);
                }
                
            }
        }

        if($this->input->post('buyer_confirm_receipt') == 1 && Currency_exchange_model::SWITCH_OFF === false )
        {

            $image = $this->image;
            $confirm_id = $this->input->post('confirm_id');

            if($this->input->post('last_user_confirm_checkbox',true) != 1)
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay');
                accaunt_message($data, $text, 'error');
            }elseif( Security::checkSecurity($user_id) ){}
            else
            {
                $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);

                $pay_data = $this->currency_exchange
                                ->get_payment_systems_by_id($confirm_order->sell_system);

                $this->db->trans_start();

                $res = $this->currency_exchange->set_buyer_confirm_order_arhive($confirm_id);

//                if($res != false && !empty($pay_data) && $pay_data->machine_name == 'wt')
                if($res != false && !empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
                {
                    $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);
                }

                if($res == false)
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                }
                else
                {
                    $this->db->trans_complete();

                    accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

//                    if(!empty($pay_data) && $pay_data->machine_name == 'wt')
                    if(!empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
                    {
                        $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
                    }
                }
            }
        }

        if($this->input->post('confirm_money_send') == 1 && Currency_exchange_model::SWITCH_OFF === false)
        {
            $confirm_id = $this->input->post('confirm_id');
//            $this->load->library('image');
//            $this->image->place = "upload/tmp/";
//
//            $image = $this->image;
//            $foto = $image->file('foto', true);
//
//            $add_foto = $image->add_foto($foto);
//
//            if (!empty($add_foto))
//            {
//                 $explode_res = explode('.', $add_foto);
//
//                 $file_name = md5($arhiv_order_id.rand(1111,9999).rand(111,999).time()).'.'.$explode_res[1];
//                 $full_file_name = 'upload/currency_exchange_docs/' . $file_name;
//
//                 $tmpPath = $this->image->place . $add_foto;  //имя файла
//                 $this->code->fileCodeSave($full_file_name, $this->code->fileCode($tmpPath));
//            }
//
//            @unlink($tmpPath);

            $full_file_name = $this->currency_exchange->add_file('foto', $confirm_id);

            $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($confirm_id);
            $confirm_order_sell_payment_data = unserialize($confirm_order->sell_payment_data);

            $pay_data = $this->currency_exchange
                            ->get_payment_systems_by_id($confirm_order->sell_system);

//            if(!empty($pay_data) && $pay_data->machine_name == 'wt')
            if(!empty($pay_data) && $this->currency_exchange->is_root_currency($pay_data->machine_name))
            {
                $full_file_name = 'wt';
            }
            
            if( Security::checkSecurity($user_id) ){
                
            }else
            if(!empty($full_file_name))
            {
                $this->db->trans_start();

                if($confirm_order->wt_set == 0)
                {
                    $res = $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, $full_file_name, 1);
                }
                else
                {
                    $res = $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, $full_file_name, 0);
                }
//                vre($this->db->last_query());
                // выставить время.
                if($res != false)
                {
                    $data_order = array('seller_send_money_date' => date('Y-m-d H:i:s'));

                    $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);
                }
//                vre($this->db->last_query());
                // выставить степ.
                if($res != false)
                {
                    $res = $this->currency_exchange->set_step_users_confirmation($confirm_order, 'initiator_confirm_send_money');
                }

//                vre($this->db->last_query());
//                vred('>>>>');
                if($res == false)
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                }
                else
                {
                    $this->currency_exchange->send_mail_by_arhiv_order_id($confirm_id, 'order_need_send_money', $user_id, true);
                    $this->db->trans_complete();
                    accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));
                }
            }
            else
            {
                $text = _e('currency_exchange/controller/error_image_no_load');
                accaunt_message($data, $text, 'error');
            }
        }

        if ($this->input->post('select_payment_system_submit_confirm') == 1 && Currency_exchange_model::SWITCH_OFF === false)
        {
            $arhiv_order_id = $this->input->post('exchange_id');
            $sms = $this->input->post('sms', true);

            $select_payment_systems_buy = $this->input->post('select_payment_systems_buy', true);
            $select_payment_systems_sell = $this->input->post('select_payment_systems_sell', true);

            if(0&&$sms == 1)
            {
                $sms = TRUE;
            }

//            $this->load->library('image');
//            $this->image->place = "upload/tmp/";
//
//            $image = $this->image;
//            $foto = $image->file('foto', true);
//
//            if (in_array($foto, array(1, 2, 3), true))
//            {
//                accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
//            }
//            else
//            {
//                $add_foto = $image->add_foto($foto);
//            }
//
//            if (!empty($add_foto))
//            {
//                $explode_res = explode('.', $add_foto);
//
//                $file_name = md5($arhiv_order_id.rand(1111,9999).rand(111,999).time()).'.'.$explode_res[1];
//                $full_file_name = 'upload/currency_exchange_docs/' . $file_name;
//
//                $tmpPath = $this->image->place . $add_foto;  //имя файла
//                $this->code->fileCodeSave($full_file_name, $this->code->fileCode($tmpPath));
//            }
//
//            @unlink($tmpPath);

            $full_file_name = $this->currency_exchange->add_file('foto', $arhiv_order_id);

            $is_buy_wt = $this->currency_exchange->is_payment_system_wt($select_payment_systems_buy);
            $is_sell_wt = $this->currency_exchange->is_payment_system_wt($select_payment_systems_sell);

            if($is_buy_wt !== false)
            {
                !empty($full_file_name)? @unlink($full_file_name):'';

                $full_file_name = 'wt';
            }

            if(Security::checkSecurity($user_id) === false)
            {
                if (!empty($full_file_name) )
                {
                    $this->db->trans_start();
//                     pred($select_payment_systems_buy);
                    $res = $this->currency_exchange->set_confirm_order_arhive($arhiv_order_id, $full_file_name, $select_payment_systems_buy, $sms);
//                    vred($res);
                    $all_ps = $this->currency_exchange->get_all_payment_systems();
                    $all_ps_key_id = array_set_value_in_key($all_ps);

//                     pred($all_ps_key_id[$select_payment_systems_buy]);
                    if($all_ps_key_id[$select_payment_systems_buy]->is_bank == 1 && $res !== FALSE)
                    {
                       $data_order = array('bank_set' => 2);
                       $res = $this->currency_exchange->set_order_data($user_id, $arhiv_order_id, $data_order);
                    }

                    if($res !== false )
                    {
                       $data_order = array('buyer_send_money_date' => date('Y-m-d H:i:s'),);
                       $res = $this->currency_exchange->set_order_data($user_id, $arhiv_order_id, $data_order);
                    }

                    if($res !== false )
                    {
                        $res = $this->currency_exchange->set_step_users_confirmation($arhiv_order_id, 'kontragent_confirm_send_money');
                    }

                    //<editor-fold desc="Отправить в СБ, если денег нет на счету" defaultstate="collapsed">
                    /*
                        $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($arhiv_order_id);

                        $scores = FALSE;
                        $comment_sb = '';
                        if( $confirm_order->wt_set == Currency_exchange_model::WT_SET_BUY ){
                            $scores = $this->accaunt->recalculateUserRating();
                            $comment_sb = 'user: '.$this->accaunt->get_user_id();
                        }else
                            if( $confirm_order->wt_set == Currency_exchange_model::WT_SET_SELL && $confirm_order->buyer_user_id > 0 ){
                                $scores = $this->accaunt->recalculateUserRating( $confirm_order->buyer_user_id );
                                $comment_sb = 'user: '.$confirm_order->buyer_user_id;
                            }

                        if( !empty( $scores ) && isset( $scores['payout_limit_by_bonus'] ) &&
                            isset( $scores['payout_limit_by_bonus'][ $confirm_order->bonus ] ) &&
                            $scores['payout_limit_by_bonus'][ $confirm_order->bonus ] < 0
                        ){
                            $note_arr = [
                                'order_id' => $confirm_order->original_order_id,
                                'text' => 'Подтверждение оплаты и payout_limit_by_bonus < 0.'.$comment_sb.'; payout_limit_by_bonus = '.$scores['payout_limit_by_bonus'][ $confirm_order->bonus ],
                                'date_modified' => date('Y-m-d H:i:s'),
                            ];

                            $this->currency_exchange->add_operator_note($note_arr);

                            $status = Currency_exchange_model::ORDER_STATUS_PROCESSING_SB;
                            $this->currency_exchange->set_status_to_order_arhive_and_order($confirm_order->original_order_id, $status,$status);
                        }
                        */
                        //</editor-fold>

                    if($res === false )
                    {
                       accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                    }
                    else
                    {
                        $this->currency_exchange->send_mail_by_arhiv_order_id($arhiv_order_id, 'order_need_confirm_send', $user_id, true);
                        $this->db->trans_complete();
                        accaunt_message($data, _e('currency_exchange/controller/confirmation_send_wait_contr'));
                    }
                }
                else
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                }
            }
        }

        if( $this->input->post('initiator_confirm_money_block', true) == 1)
        {
            $arhiv_order_id = $this->input->post('confirm_id');
            $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($arhiv_order_id);
//            vred($arhiv_order_id);
            if($this->input->post('last_user_confirm_checkbox',true) != 1)
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay');
                accaunt_message($data, $text, 'error');
            }
            elseif(
                    $confirm_order->status != Currency_exchange_model::ORDER_STATUS_CONFIRMATION ||
                    $confirm_order->confirmation_step != 0b0001 ||
                    $confirm_order->seller_user_id != $user_id
                    )
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay').'.';
                accaunt_message($data, $text, 'error');
            }elseif(Security::checkSecurity($user_id))
            {                
            }
            else
            {
//                $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($arhiv_order_id);
//                pred($confirm_order);
                $this->db->trans_start();

                $data_order = array(
                    'seller_get_money_date' => date('Y-m-d H:i:s'),
                    'seller_confirmed' => 1,
                    );

                $res = $this->currency_exchange->set_order_data($user_id, $confirm_order->id, $data_order);

                if($res === TRUE)
                {
                    $res = $this->currency_exchange->set_step_users_confirmation($confirm_order->id, 'initiator_confirm_received_money');
                }

//                vred($this->db->last_query());
                if($res == false)
                {
                    accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
                }
                else
                {
                    $this->load->model('card_model', 'card_model');
                    $this->load->model('Card_prefunding_transactions_model', 'prefund');

                    $visa_ps_id = 115;

                    $counterparty_payment_data = unserialize($confirm_order->sell_payment_data);
                    $counterparty_card_id      = $counterparty_payment_data[0]->payment_data;
                    $counterparty_id           = $counterparty_payment_data[0]->user_id;
                    $counterparty_ps_id        = $counterparty_payment_data[0]->payment_system_id;
                    $order_id                  = $confirm_order->original_order_id;


                    if($counterparty_ps_id == $visa_ps_id) {
                        $card = $this->card_model->getUserCard($counterparty_card_id, $counterparty_id);

                        $check_before_trans = $this->prefund->check_before_second_transaction($order_id, $counterparty_id, $confirm_order->seller_amount);
                        
                        
                        if($check_before_trans['error'] == 1) {
                            if($check_before_trans['create_error'] == 1) {
                                $note_arr = [
                                    'order_id' => $order_id,
                                    'text' => 'При закрытии заявки произошла ошибка связанная с записями в префанде',
                                    'date_modified' => date('Y-m-d H:i:s'),
                                ];

                                $this->currency_exchange->add_operator_note($note_arr); 

                                $this->currency_exchange->set_status_to_order_arhive_and_order($order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);
                                $this->db->trans_complete();
                                header("Refresh:0");
                                die;
                            }
                            $this->currency_exchange->set_delayed_take_money($order_id); 
                            // Создать отложенный запрос
                        } else {
                            
                            // Закрываем заявку
                            $pref_res = $this->currency_exchange->prefund_second_transaction(
                                [
                                    'card_id'   => $counterparty_card_id,
                                    'amount'    => $confirm_order->seller_amount,
                                    'order_id'  => $order_id,
                                    'user_id'   => $counterparty_id
                                ]
                            );
                            
                            $set_success_order = TRUE;
                            if($pref_res === TRUE ) $set_success_order = $this->currency_exchange->set_success_order($order_id);
                            
                            #если ошибка зачисления и ошибка установки статуса заявки
//                            if($pref_res === FALSE || $set_success_order === FALSE ) {
                            //заглушка если заявки visa-qiwi и prefund second === false
                            if(false) {
                                $note_arr = [
                                    'order_id' => $order_id,
                                    'text' => 'При закрытии  заявки произошла ошибка при отправке денег визы',
                                    'date_modified' => date('Y-m-d H:i:s'),
                                ];

                                $this->currency_exchange->add_operator_note($note_arr);
                                $this->currency_exchange->set_status_to_order_arhive_and_order($order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);

//                                //$this->db->trans_complete();
//                                // header("Refresh:0");
//                                // return false;
                            }
                            // Не получилось сделать оплату по заявке

                        }

                    }  
                    
                    $this->db->trans_complete();

                    accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

//                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
                }
            }
        }
        
        //отложенное получение денег контрагентом: step = 25 = 11001
        if( $this->input->post('counterparty_confirm_money_block', true) == 1)
        {
            
            $arhiv_order_id = $this->input->post('confirm_id');
            $confirm_order = $this->currency_exchange->get_order_arhiv_by_id($arhiv_order_id);

            if(
                    $confirm_order->status != Currency_exchange_model::ORDER_STATUS_CONFIRMATION ||
                    $confirm_order->confirmation_step != 0b11001 ||
                    $confirm_order->seller_user_id != $user_id
                    )
            {
                $text = _e('currency_exchange/controller/error_you_no_confirm_cpntr_pay').'.';
                accaunt_message($data, $text, 'error');
            }
            else
            {
                // берем Order_id и ищем запись в архиве по 25
                // имея эту запись мы узнаем ожидателя
                // берем его ид и ищем в префанде запись по ордер ид и юзер ид
                // проверяем этот префанд чист ли он
                // Делаем всевозможные проверки перед отправкой на визу. что бы не случилось чуда!!
                // и если чист то наверно выполняем prefund second

                // Если инициатор то данные о карте берем из buy_payment_data
                // если контрагент то берем из seller



                $order_id = $confirm_order->original_order_id;
                $archive_order_taker = $this->currency_exchange->get_original_order_by_orig_id_by_confirmation_step($order_id, Currency_exchange_model::ORDER_STEP_LATE_WITHDRAWAL_TAKER);

                $archive_order_waiter = $this->currency_exchange->get_original_order_by_orig_id_by_confirmation_step($order_id, Currency_exchange_model::ORDER_STEP_LATE_WITHDRAWAL_WAITER);

                if(empty($archive_order_waiter) || empty($archive_order_taker))
                {
                    //die('not find order');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }
                    

                $user_id_taker = $archive_order_taker->seller_user_id;
                $user_id_waiter = $archive_order_waiter->seller_user_id;

                if($user_id_taker != $user_id || empty($user_id_waiter)) {
                    //die('not correct user!');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }

                $this->load->model('Card_prefunding_transactions_model', 'prefund');
                $prefunds = $this->prefund->get_by_order_id($order_id);

                if(count($prefunds) != 2){
                    //                    die('bad count prefunds');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }

                if(!$this->prefund->is_paired_free($prefunds)){
                    //die('prefunds not free paired');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }
                    
                if($prefunds[0]->debet_uid != $user_id_waiter){
                    //die('not valid user who send money');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }
                    

                $amount = $prefunds[0]->amount;
                if($amount < 50){
                    //die('error prefund amount');                    
                   accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                   $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }

                $archive_order_taker_ps_data = unserialize($archive_order_taker->buy_payment_data);
                if(empty($archive_order_taker_ps_data)){
//                    die('error getting ps data');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }
                
                if($archive_order_taker->initiator == 0) {
                    // Если мы контрагент то данные о карте берем из sell payment data
                    // в принципе это место опасное!
                    $archive_order_taker_ps_data = unserialize($archive_order_taker->sell_payment_data);

                }


                $taker_card_id = $archive_order_taker_ps_data[0]->payment_data;
                if(empty($taker_card_id)){
//                    die('taker card_id empty');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }
                
                $this->load->model('card_model', 'card_model');
                $valid_card_id = $this->card_model->getUserCard($taker_card_id, $user_id_taker)->id;


                if(empty($valid_card_id)){
//                    die('not valid taker card');
                    accaunt_message($data, _e('Нам не удалось получить данные заявки. Обратитесь, пожалуйста, к Оператору.'), 'error');
                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
                }

                $pref_res = $this->currency_exchange->prefund_second_transaction(
                    [
                        'card_id'   => $valid_card_id,
                        'amount'    => $amount,
                        'order_id'  => $order_id,
                        'user_id'   => $user_id_taker
                    ]
                );
                if($pref_res === false) {                    
                    //die('error prefund second');
                       accaunt_message($data, _e('Ваша карта достигла максимальных суточных лимитов. Попробуйте повторить операцию через 24 часа с момента последней операции по карте.'), 'error');
                       $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');                
//                    выводим пользователю что ему стоит посмотреть лимиты. или там 24 часа подождать
                }
                // закрываем заявку
                $this->currency_exchange->set_success_order($order_id);
                
                $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
            }
        }

        $data->orders_on_list = 10;
        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;

        $res = $this->_load_more_orders($type, $data->orders_on_list, 1);
//        pred($res);
        $data->orders = $res->orders;
        $data->payment_systems = $res->payment_systems;
        $data->payment_systems_id_arr = $res->payment_systems_id_arr;
        $data->count_all_orders = $res->count_all_orders;

        $status = Currency_exchange_model::ORDER_STATUS_CONFIRMATION;

        $orders_arhive = $this->currency_exchange->get_user_orders_arhive($status, $user_id, $type);



//        pred($orders_arhive);
        $orders_arhive_procesing = $this->currency_exchange->get_user_orders_arhive( [8,9], $user_id);

        if(empty($orders_arhive))
        {
            $orders_arhive = array();
        }

        if(empty($orders_arhive_procesing))
        {
            $orders_arhive_procesing = array();
        }
        $this->load->model('users_filds_model','users_filds');

        $data->orders_arhive = $orders_arhive + $orders_arhive_procesing;

        #альтернативный вариант поиска ид-пользователя
        $seller_social_id_v2 = 0;
        if( count( $data->orders_arhive ) == 2 )
        foreach($data->orders_arhive as $order)
        {
            if( $order->seller_user_id == $user_id ) continue;

            $seller_social_id_v2 = $order->seller_user_id;
            break;
        }
        #/альтернативный вариант поиска ид-пользователя

        $show_visa = FALSE;

        $seller_social_id = 0;
        foreach($data->orders_arhive as &$order)
        {
//            $order->visa_tranfer_money_url = '123';
//            if( $user_id == 87667178 ) vred( $order );

            //vre( $order );
            if( $order->seller_user_id == $user_id && $order->wt_set == 1 && $order->seller_confirmed == 0 && $order->initiator == 0 )
            {
                foreach($order->buy_payment_data_un as $ps)
                {
                    if( $ps->user_id != $user_id && !empty( $ps->user_id ) ) $seller_social_id = $this->users_filds->getUserFild($ps->user_id, 'id_network');
                    if( $ps->machine_name != 'wt_visa_usd' ) continue;

                    $show_visa = TRUE;
                    break;
                }
            }
            elseif( $order->seller_user_id == $user_id && $order->wt_set == 2 && $order->seller_confirmed == 0 && $order->initiator == 1 )
            {
                foreach($order->sell_payment_data_un as $ps)
                {
                    if( $ps->user_id != $user_id && !empty( $ps->user_id ) ) $seller_social_id = $this->users_filds->getUserFild($ps->user_id, 'id_network');
                    if( $ps->machine_name != 'wt_visa_usd' ) continue;

                    $show_visa = TRUE;
                    break;
                }
            }
//            elseif( $order->seller_user_id == $user_id && $order->wt_set == 0 && $order->seller_confirmed == 0 && $order->initiator == 0 )
            elseif( $order->seller_user_id == $user_id && $order->wt_set == 0 && Currency_exchange_model::get_order_status($order) == 9 )
            {
                foreach($order->buy_payment_data_un as $ps)
                {
                    if( $ps->machine_name != 'wt_visa_usd' ) continue;

                    if( $ps->user_id != $user_id && !empty( $ps->user_id ) ) $seller_social_id = $this->users_filds->getUserFild($ps->user_id, 'id_network');

                    $show_visa = TRUE;
                    break;
                }
            }
//            elseif( $order->seller_user_id == $user_id && $order->wt_set == 0 && $order->seller_confirmed == 0 && $order->initiator == 1 )
//            elseif( $order->seller_user_id == $user_id && $order->wt_set == 0 && Currency_exchange_model::get_order_status($order) == 14 )
            elseif( $order->seller_user_id == $user_id && $order->wt_set == 0 && Currency_exchange_model::get_order_status($order) == 14 )
            {
                foreach($order->sell_payment_data_un as $ps)
                {
                    if( $ps->machine_name != 'wt_visa_usd' ) continue;

                    if( $ps->user_id != $user_id && !empty( $ps->user_id ) ) $seller_social_id = $this->users_filds->getUserFild($ps->user_id, 'id_network');

                    $show_visa = TRUE;
                    break;
                }
            }


            foreach($order->payment_systems as $ps)
            {
                if( $show_visa && $ps->machine_name == 'wt_visa_usd' )
                {
                    $order->visa_tranfer_money_url = [];

                    if( !empty( $seller_social_id ) ) $order->visa_tranfer_money_url['url'] = "https://webtransfer.com/social/profile/$seller_social_id";
                    else
                    {
                        if( !empty( $seller_social_id_v2 ) ) $order->visa_tranfer_money_url['url'] = "https://webtransfer.com/social/profile/$seller_social_id_v2";
                        else
                            $order->visa_tranfer_money_url['error'] = _e('Контрагент не создал страницу в социальной сети Webtransfer. Сообщите о проблеме Оператору.');
                    }

                }

                if(isset($data->payment_systems_id_arr[$ps->payment_system])) continue;

                $data->payment_systems_id_arr[$ps->payment_system] = Currency_exchange_model::get_ps($ps->payment_system);
                $data->payment_systems_id_arr[$ps->machine_name] = Currency_exchange_model::get_ps($ps->payment_system);
            }
        }

        $data->button = 'confirme';
        $data->is_show_payment_data = true;
        
        //TODO Удалить уже не нужно
        //записи с проблемами
//        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;
//        $data->orders_arhive_problem = $this->currency_exchange->get_user_orders_arhive_problem($type, $user_id);

        if(empty($data->orders_arhive_problem))
        {
            $data->orders_arhive_problem = array();
        }

        $data->url_load_more_orders = site_url('account/currency_exchange/ajax/load_more_orders_sell');

        if($iframe === true) {
            $this->content->iframe_user_view("currency_exchange/my_sell_list", $data, _e('currency_exchange/model_currency_exchange/my_sells'));
        }


        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
        {
            $this->content->user_view("currency_exchange/my_sell_list", $data, _e('currency_exchange/model_currency_exchange/my_sells'));
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
                        array("title_accaunt" => _e('currency_exchange/model_currency_exchange/my_sells') );

            $this->load->view('user/currency_exchange/my_sell_list', $view_data );
        }
    }

    private function step_seller_confirm($confirm_order, $user_id, $confirm_id) {
        // user_id передавать только инициатора!!!!
        $pay_data = $this->currency_exchange
            ->get_payment_systems_by_id($confirm_order->sell_system);

        $this->db->trans_start();

        $res = $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, FALSE);

        if($res != false )
        {
            $data_order = array('seller_get_money_date' => date('Y-m-d H:i:s'));
            $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);

        }

        if($res != false )
        {
            $res = $this->currency_exchange->set_step_users_confirmation($confirm_order, 'initiator_confirm_received_money');
        }

//                if($res != false && !empty($pay_data) && ($pay_data->machine_name == 'wt' || $confirm_order->buyer_confirmed && $confirm_order->buyer_user_id) )
        if($res != false && !empty($pay_data) && ($this->currency_exchange->is_root_currency($pay_data->machine_name) || $confirm_order->buyer_confirmed && $confirm_order->buyer_user_id) )
        {
            $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);
        }

        if($res === false)
        {
            accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
        }
        elseif( $res === -1 ){
            $this->db->trans_complete();
            accaunt_message($data, _e('currency_exchange/controller/order_accepded_for_review'));
        }
        else
        {
            $this->db->trans_complete();


            accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

//                    if(!empty($pay_data) && ($pay_data->machine_name == 'wt' || ($confirm_order->buyer_confirmed && $confirm_order->buyer_user_id)))
            if(!empty($pay_data) && ($this->currency_exchange->is_root_currency($pay_data->machine_name) || ($confirm_order->buyer_confirmed && $confirm_order->buyer_user_id)))
            {
                $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
            }
        }
    }


    private  function redirect_iframe($uri = '', $method = 'auto', $code = NULL)
    {
		$iframe_server_url = 'https://webtransfer.exchange/';
//		$iframe_server_url = 'http://wtest15.cannedstyle.com/index.php/';

		$iframe_user_url = 'https://webtransfer.exchange/';
//		$iframe_user_url = 'http://wtest12.cannedstyle.com/';


		if(strpos($_SERVER['HTTP_REFERER'], 'iframe') !== false)
                {

                    $redirect_url = '';
                    $lang = get_instance()->lang->lang();

                    $uri_parts = explode('/', $uri);
                    $uri_parts_count = count( $uri_parts );

                    switch ($uri_parts[ $uri_parts_count - 1 ]) {
                        case 'my_sell_list':
                                $redirect_url = $iframe_user_url.$lang. '/exchanger/pear2pear/my_sell_list';
                                break;
                        case 'sell':
                                $redirect_url = $iframe_user_url.$lang. '/exchanger/pear2pear/sell';
                                break;
                        case 'sell_search':
                                $redirect_url = $iframe_user_url.$lang. '/exchanger/pear2pear/sell_search';
                                break;
                        case 'giftguarant_market':
                                $redirect_url = $iframe_user_url.$lang. '/exchanger/pear2pear/giftguarant_market';
                                break;
                        case 'settings':
                                $redirect_url = $iframe_user_url.$lang. '/exchanger/pear2pear/settings';
                                break;
                    }

                    header('Location: '.$redirect_url, TRUE, 303);

                    $_SESSION['p2p_redirect_url'] = $redirect_url;
                    //			echo '<script> window.parent.location.href = \''.$redirect_url.'\'; </script>';
                    return false;
                }

        if ( ! preg_match('#^(\w+:)?//#i', $uri))
        {
            $uri = site_url($uri);
        }



        // IIS environment likely? Use 'refresh' for better compatibility
        if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
        {
            $method = 'refresh';
        }
        elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
        {
            if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
            {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
                    ? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
                    : 307;
            }
            else
            {
                $code = 302;
            }
        }

        switch ($method)
        {
            case 'refresh':
                header('Refresh:0;url='.$uri);
                break;
            default:
                header('Location: '.$uri, TRUE, $code);
                break;
        }
        exit;
    }



    private function step_seller_confirm_v2($confirm_order, $user_id, $confirm_id) {
        $response = array();
        // user_id передавать только инициатора!!!!
        $pay_data = $this->currency_exchange
            ->get_payment_systems_by_id($confirm_order->sell_system);


        $res = $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, FALSE);

        if($res != false )
        {
            // echo ' h1'; 
            $data_order = array('seller_get_money_date' => date('Y-m-d H:i:s'));
            $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);

        }

        if($res != false )
        {   
            // echo ' h2'; 
            $res = $this->currency_exchange->set_step_users_confirmation($confirm_order, 'initiator_confirm_received_money');
        }

//                if($res != false && !empty($pay_data) && ($pay_data->machine_name == 'wt' || $confirm_order->buyer_confirmed && $confirm_order->buyer_user_id) )
        if($res != false && !empty($pay_data) && ($this->currency_exchange->is_root_currency($pay_data->machine_name) || $confirm_order->buyer_confirmed && $confirm_order->buyer_user_id) )
        {
            // echo ' h3';
            $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);
        }

        if($res === false)
        {
            // echo ' h4';
            accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
        }
        elseif( $res === -1 ){
            // echo ' h5';
            accaunt_message($data, _e('currency_exchange/controller/order_accepded_for_review'));
        }
        else
        {

            // echo ' h6';
            accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

//                    if(!empty($pay_data) && ($pay_data->machine_name == 'wt' || ($confirm_order->buyer_confirmed && $confirm_order->buyer_user_id)))
            if(!empty($pay_data) && ($this->currency_exchange->is_root_currency($pay_data->machine_name) || ($confirm_order->buyer_confirmed && $confirm_order->buyer_user_id)))
            {
                
            // echo ' h7';
            $response['success'] = 1;
                // $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
            }
        }
        return $response;
    }

 
    private function _get_order_ps_id( $confirm_order, $user_id ) {
        
        if( $confirm_order->seller_user_id == $user_id ) return $confirm_order->sell_system;
        
        if( $confirm_order->buyer_user_id == $user_id )  return $confirm_order->payed_system;        
        
        return FALSE;
    }
    private function first_trasaction_to_visa($confirm_order, $user_id, $confirm_id) {
        $visa_ps_id = 115;

        $current_uid = $this->accaunt->get_user_id();
        
        $user_payment_data = unserialize($confirm_order->buy_payment_data);
        $user_card_id      = $user_payment_data[0]->payment_data;
        $user_id           = $user_payment_data[0]->user_id;
        $user_ps_id        = $this->_get_order_ps_id($confirm_order, $user_id);//$user_payment_data[0]->payment_system_id;
        $order_id          = $confirm_order->original_order_id;

        //process VISA
        if($user_ps_id == $visa_ps_id) {
            $this->load->model('card_model', 'card_model');
            $this->load->model('Card_prefunding_transactions_model', 'prefund');
            $card = $this->card_model->getUserCard($user_card_id, $current_uid);
            $order_details = $this->currency_exchange->get_order_details_by_partner($order_id);
            $amount = $order_details[0]->summa;


            $check_before_trans = $this->prefund->check_before_second_transaction($order_id, $user_id, $amount);
            if($check_before_trans['error'] == 1) {
                if($check_before_trans['create_error'] == 1) {

                    $this->currency_exchange->set_status_sb( $order_id, 'При проведении заявки произошла ошибка в проверке префанда');

                    $this->db->trans_complete();
                    header("Refresh:0");
                    die;
                }

                $this->currency_exchange->set_delayed_take_money($order_id); 
                return FALSE;
            } 

            $orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id); 
            if($orders[0]->buyer_user_id == 0) {
                $this->currency_exchange->set_buyer_user_id($order_id, $orders[0]->seller_user_id);
            }

            $pref_res = $this->currency_exchange->prefund_second_transaction(
                [
                    'card_id'   => $user_card_id,
                    'amount'    => $amount,
                    'order_id'  => $order_id,
                    'user_id'   => $user_id
                ]
            );

            // var_dump($confirm_id); //10
            // var_dump($order_id); //5
            if($pref_res === false) return FALSE;
            $this->currency_exchange->set_seller_confirm($user_id, $confirm_id, FALSE);
            return FALSE;
        }  
        
        return TRUE;
    }

    /**
     * @param $archive_order
     * @param $confirm_id
     * @return bool
     */
    private function last_confirm_visa($archive_order, $confirm_id) {
        // Данная функция запускается со стороны контрагента.
        // Когда он закрывает заявку


        $original_order_id = $archive_order->original_order_id;

        $confirmation_step = $archive_order->confirmation_step;

        $buy_payment_data = unserialize($archive_order->buy_payment_data);
        $sell_payment_data = unserialize($archive_order->sell_payment_data);
        $buy_ps = $buy_payment_data[0]->payment_system_id;
        $sell_ps = $sell_payment_data[0]->payment_system_id;
        $visa_ps_id = 115;

        if(($buy_ps == $visa_ps_id || $sell_ps == $visa_ps_id) && $confirmation_step == 7 ) {
            
            #add payed_system to orders
            if( $sell_ps == $archive_order->sell_system ) {
                $this->currency_exchange->set_payed_system_order_archive($original_order_id , $buy_ps);                
            }elseif( $buy_ps == $archive_order->sell_system ) {
                $this->currency_exchange->set_payed_system_order_archive($original_order_id , $sell_ps);                
            }
            
        
            $archive_orders = $this->currency_exchange->get_archive_orders_by_orig_order_id($original_order_id);

            $user_id = $archive_orders[1]->seller_user_id;
            if($sell_ps == $visa_ps_id)
                $user_id = $archive_orders[0]->seller_user_id;
            $this->load->model('Card_prefunding_transactions_model', 'prefund');
            $card_id = $buy_payment_data[0]->payment_data;
            if($sell_ps == $visa_ps_id) 
                $card_id = $sell_payment_data[0]->payment_data;

            $prefund = $this->prefund->get_by_order_id($original_order_id);

            if(!empty($prefund[0]->amount)) {
                $amount = $prefund[0]->amount;

                $res = $this->prefund->check_before_second_transaction($original_order_id, $user_id, $amount);

                if($res['success'] == 1) {
//                    if($buy_ps == $visa_ps_id || $sell_ps == $visa_ps_id) {
//                        $this->currency_exchange->set_payed_system_order_archive($original_order_id, $visa_ps_id);

                        $pref_res = $this->currency_exchange->prefund_second_transaction(
                            [
                                'card_id'   => $card_id,
                                'amount'    => $amount,
                                'order_id'  => $original_order_id,
                                'user_id'   => $user_id
                            ]
                        );

                        if($pref_res === false) {
                            return false;
                        }

//                    }
                    return true;
                } else {
                    if($res['create_error'] == 1) {
                        $note_arr = [
                        'order_id' => $order_id,
                        'text' => 'При зачислении денег VISА на карту, произошла ошибка Прифанда',
                        'date_modified' => date('Y-m-d H:i:s'),
                    ];

                   $this->currency_exchange->add_operator_note($note_arr);
                        $this->currency_exchange->set_status_archives_by_orig_id($original_order_id, Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);
                        $this->db->trans_complete();
                        header("Refresh:0");
                        die;
                    }
                    // $this->currency_exchange->set_delayed_take_money($original_order_id); 
                    // $this->db->trans_complete();
                    // header("Refresh:0");
                    // die;
                    // TODO В случае ошибки отправить в сб

                }
                
            }
        }
        return true;
    }

    private function last_confirm_order_v1($archive_order, $confirm_id, $user_id) {
        // user_id идет контрагента.
        // confirm_id id_archive_order от контрагента
        $this->db->trans_start();

        $res = $this->currency_exchange->set_buyer_confirm_order_arhive($confirm_id);

        if($res === TRUE)
        {
            $data_order = array('buyer_get_money_date' => date('Y-m-d H:i:s'));
            $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);
        }

        if($res === TRUE)
        {
            $res = $this->currency_exchange->set_step_users_confirmation($confirm_id, 'kontragent_confirm_received_money');
        }

        if($res === TRUE)
        {
            $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);
        }
//                vred($res);
        if($res == false)
        {
            accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
        }
        else
        {
            $this->db->trans_complete();

            accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));
            $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
        }
    }

    private function last_confirm_order_v2($archive_order, $confirm_id, $user_id) {
        // user_id идет контрагента.
        // confirm_id id_archive_order от контрагента

        $res = $this->currency_exchange->set_buyer_confirm_order_arhive($confirm_id);

        if($res === TRUE)
        {
            $data_order = array('buyer_get_money_date' => date('Y-m-d H:i:s'));
            $res = $this->currency_exchange->set_order_data($user_id, $confirm_id, $data_order);
        }

        if($res === TRUE)
        {
            $res = $this->currency_exchange->set_step_users_confirmation($confirm_id, 'kontragent_confirm_received_money');
        }

        if($res === TRUE)
        {
            $res = $this->currency_exchange->set_last_confirm($user_id, $confirm_id);
        }
//                vred($res);
        if($res == false)
        {
            accaunt_message($data, _e('currency_exchange/controller/error_order_not_save'), 'error');
        }
        else
        {

            accaunt_message($data, _e('currency_exchange/controller/order_curred_out'));

            // $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh');
        }
    }

    private function filter_operation(&$filter_state) {
        $filter_state = Currency_exchange_model::ORDER_TYPE_SELL_WT;

        # Получаем тип
        $filter_state = (!$this->input->post('filter_state', TRUE)) ? $this->input->cookie('filter_p2p_orders_hist', TRUE) : $this->input->post('filter_state', TRUE);
        $filter_state = (!in_array($filter_state, ['t0', 't1', 't2', 't3', 't4'])) ? 't0' : $filter_state;

        # Запись в COOKIE
        if ($filter_state != $this->input->cookie('filter_state', TRUE)) $this->input->set_cookie(['name' => 'filter_p2p_orders_hist', 'value'  => $filter_state, 'expire' => '1200']);


        $send_type = '';
        if($filter_state == 't0') { // все
            $send_type = '';
        }
        else if($filter_state == 't1') { // Завершена
           $send_type = array('seller_confirmed' => 1, 'buyer_confirmed' => 1);
        }
        else if($filter_state == 't2') { // Отменена оператором
            $send_type = array('status' => 'ORDER_STATUS_OPERATOR_CANCELED_AND_BRAKEN');
        }
        else if($filter_state == 't3') { // Отменена контрагентом
            $send_type = array('status' => Currency_exchange_model::ORDER_STATUS_CANCELED);
        }
        else if($filter_state == 't4') { // Проведена оператором
            $send_type = array('status' => Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR);
        }

        return $send_type;
    }

    public function my_sell_list_arhive()
    {
        $data = new stdClass();
        $user_id = $this->account->get_user_id();
        $data->user_id = $user_id;

        $data->table_dop_data_sell = 'Вы отдали';
        $data->table_dop_data_buy = 'Вы получили';
        $data->table_header_sell = 'Вы отдали';
        $data->table_header_buy = 'Вы получили';

        $data->orders_on_list = 10;

        $data->filter_state = 't0';
        $send_type = $this->filter_operation($data->filter_state);

        $res = $this->_load_more_orders_arhive($send_type, $data->orders_on_list, 1);

        $data->orders = $res->orders;

        $data->payment_systems = $res->payment_systems;
        $data->payment_systems_id_arr = $res->payment_systems_id_arr;
        $data->count_all_orders = $res->count_all_orders;


        $data->button = 'confirme';

        if(empty($data->orders_arhive_problem))
        {
            $data->orders_arhive_problem = array();
        }

        $data->url_load_more_orders = site_url('account/currency_exchange/ajax/load_more_orders_sell_arhive');

        $data->table_search_set_title_contragent = true;
        $data->table_search_set_value_contragent = true;

        $data->curency_problem = $this->currency_exchange->get_all_curency_problem_subject();

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
        {
            $this->content->user_view("currency_exchange/my_sell_list", $data, _e('currency_exchange/my_sell_list_arhive/arhiv'));
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
                        array("title_accaunt" => _e('currency_exchange/my_sell_list_arhive/arhiv') );

            $this->load->view('user/currency_exchange/my_sell_list', $view_data );
        }
    }


    private function _curency_problem()
    {
        if($this->input->post('problem_submit') == 1)
        {
            $problems = $this->currency_exchange->get_all_curency_problem_subject();

            $user_id = $this->account->get_user_id();
            $subject_id = $this->input->post('problem_id');
            $subject = $this->input->post('problem_subject');
            $problem_text = $this->input->post('msg_body');
            $exchange_id = $this->input->post('exchange_id');
            $responce = $this->input->post('responce', true);
            $problem_submit_operator = $this->input->post('problem_submit_operator');
//            vred($problem_submit_operator);
            if($responce == 1)
            {
                $subject_id = 0;
            }

            if($responce != 1 && empty($subject_id))
            {
                echo json_encode(array('error' => '1', 'text' => _e('currency_exchange/controller/error_select_theme')));
                return false;
            }

            $problems_arr = array();
            foreach($problems as $val)
            {
                $problems_arr[$val->id] = $val;
            }

            if($responce != 1 && $problems_arr[$subject_id]->machin_name == 'other' && empty($subject))
            {
                echo json_encode(array('error' => '1', 'text' => _e('currency_exchange/controller/erroe_not_fill_theme_field')));
                return false;
            }

            if(empty($problem_text))
            {
                echo json_encode(array('error' => '1', 'text' => _e('currency_exchange/controller/erroe_not_fill_text_field')));
                return false;
            }

            if(empty($exchange_id))
            {
                echo json_encode(array('error' => '1', 'text' => _e('currency_exchange/controller/erroe_no_corrct_id')));
                return false;
            }



            $this->load->library('image');
            $this->image->place = "upload/currency_exchange_problem/";

            $image = $this->image;
            $foto = $image->file('foto', true);

            $add_foto = '';

            if (in_array($foto, array(1, 2, 3), true)) {
                if ($foto == 1)
                    $sms = _e('account/data58');
                if ($foto == 3)
                    $sms = _e('account/data59');
                if ($foto == 2)
                    $sms = _e('account/data60');
//                accaunt_message($data, @$sms, 'error');
                echo json_encode(array('error' => '1', 'text' => $sms));
                return false;
            } else {

                $add_foto = $image->add_foto($foto); //имя файла
            }

            $id_message = $this->currency_exchange->add_to_problem_chat_and_set_order_status($exchange_id, $user_id, $subject_id, $subject, $problem_text, $this->image->place, $add_foto, $problem_submit_operator);

            if($id_message === false)
            {
                echo json_encode(array('error' => '1', 'text' => _e('currency_exchange/controller/erroe_no_corrct_id')));
                return false;
            }

            $message = $this->currency_exchange->get_curency_problem_chat_by_id($id_message);

            $last_message = $this->currency_exchange->get_problem_chat_without_excess($message, $problems_arr);

            if($problem_submit_operator != 1)
            {
                $this->currency_exchange->send_mail_by_arhiv_order_id($exchange_id, 'user_send_message', $user_id, true);
            }


            echo json_encode(array('success' => '1', 'last_message' => $last_message));
         }
    }

    private function _is_visa_in_payment_data( $sell_user_payment_data, $buy_user_payment_data )
    {
        $visa_ps_id = 115;
        if( $sell_user_payment_data[0]->payment_system_id == $visa_ps_id || 
            $buy_user_payment_data[0]->payment_system_id == $visa_ps_id ) 
            return TRUE;
        
        return FALSE;
    }

    private function _is_visa_in_payment_data_paied( $prefunds, $initiator, $sell_user_payment_data, $buy_user_payment_data ) {
        $visa_ps_id = 115;
//        echo "if( ".(int)empty($prefunds)." && 
//                ( $initiator == 0 && $buy_user_payment_data[0]->payment_system_id == $visa_ps_id )    ||
//                ( $initiator == 1 && $sell_user_payment_data[0]->payment_system_id == $visa_ps_id )     )";
//        die;
        //пропускаем возврат денег с Визы,
            //когда отдаем Виза, но еще не оплатили
        if( empty($prefunds) && 
                (( $initiator == 0 && $buy_user_payment_data[0]->payment_system_id == $visa_ps_id )    ||
                ( $initiator == 1 && $sell_user_payment_data[0]->payment_system_id == $visa_ps_id ) )    )
        return FALSE;
        
        return TRUE;
    }
    
    private function _canceled_order()
    {
        if($this->input->post('cancel_action', TRUE) == 1)
        {
            $id = $this->input->post('exchange_id', true);

            
            $archive_order = $this->currency_exchange->get_order_arhiv_by_id($id);            
            $original_order_id = $archive_order->original_order_id;

            $current_uid = $this->accaunt->get_user_id();
            $valid_user = $this->currency_exchange->is_user_in_this_order_archive($current_uid ,$original_order_id);
            
            if($valid_user === false ) {
                accaunt_message($data, _e('Нельзя удалить заявку, в которой вы не являетесь участником.'), 'error');
                return FALSE;
            }

            if( empty($archive_order) ){
                $this->currency_exchange->set_status_sb( $original_order_id, 'Заявка не найдена в таблице архива '. $original_order_id);
                accaunt_message($data, _e('Мы не можем получить данные заявки. Оператор решит вопрос в течение 24 часов.'), 'error');
                return FALSE;
            }
            
            
            $delete_from_initiator = 0;
            if( 
                ( $archive_order->initiator == 1 && $archive_order->seller_user_id == $current_uid ) ||
                ( $archive_order->initiator == 0 && $archive_order->buyer_user_id == $current_uid )
            ){
                $delete_from_initiator = 1;
            }
            
            #return if not VISA
            $visa_ps_id = 115;

            $buy_user_payment_data = unserialize($archive_order->buy_payment_data);
            $sell_user_payment_data = unserialize($archive_order->sell_payment_data);


            if( empty($sell_user_payment_data) || empty( $buy_user_payment_data ) ) return FALSE;

            $this->load->model('Card_prefunding_transactions_model', 'prefund');
            $prefunds = $this->prefund->get_by_order_id($original_order_id);

            
            if( $this->_is_visa_in_payment_data_paied( $prefunds, $archive_order->initiator, $sell_user_payment_data, $buy_user_payment_data )                     
                && $this->_is_visa_in_payment_data($sell_user_payment_data, $buy_user_payment_data) )                   
            {
                
                if( empty($prefunds) ){
                    $this->currency_exchange->set_status_sb( $original_order_id, 'В префанде не обнаружено транзакций по заявке '. $original_order_id);
                    accaunt_message($data, _e('Мы не можем получить данные заявки. Оператор решит вопрос в течение 24 часов.'), 'error');
                    return FALSE;
                }

                //if($this->input->post('delete_from_initiator', TRUE) == 1) {
                if( $delete_from_initiator == 1 )
                {

                    if( !$this->delete_from_initiator($id) ) {
                        accaunt_message($data, _e('currency_exchange/controller/error_you_order_reject_filed'), 'error');
                        return FALSE;
                    }
                    
                }

                if ( empty($prefunds)) return NULL;

                $order = $this->currency_exchange->get_order_archive_only_initiator($original_order_id);
                if(empty($order)) {
                    accaunt_message($data, _e('currency_exchange/controller/error_you_order_reject_filed'), 'error');

                    return false;

                } 

                $initiator_id = $order->seller_user_id;

                $return_from_prefund = $this->currency_exchange->prefund_delete($initiator_id, $original_order_id, Currency_exchange_model::ORDER_STATUS_CONFIRMATION);

                if($return_from_prefund === false) {
                    accaunt_message($data, _e('currency_exchange/controller/error_record_no_delete'), 'error');

                    $this->currency_exchange->set_status_sb( $original_order_id, 'При удалении заявки заявки произошла ошибка связанная с записями в префанде '. $original_order_id);

                    $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh'); 
                    return FALSE;
                }
                
            }
            #!return if not VISA
                
            $res = $this->currency_exchange->user_cancel_order($id);

            if($res === TRUE)
            {
                accaunt_message($data, _e('currency_exchange/controller/you_order_reject'));
                $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list_arhive'), 'refresh'); 
                return false;
            }

            accaunt_message($data, _e('currency_exchange/controller/error_you_order_reject_filed'), 'error');
            return false;
        }
    }


    //TODO delete
    private function user_payment_data()
    {
        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
        {
            $this->content->user_view("currency_exchange/user_payment_data", $data, 'Мои покупки');
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
                        array("title_accaunt" => 'Мои покупки' );
//                        array("title_accaunt" => _e('account/data1') );

            $this->load->view('user/currency_exchange/user_payment_data', $view_data );
        }
    }



    public function buy_application_doc($doc_num)
    {
        $this->_application_doc($doc_num, 'buyer_document_image_path');
    }



    public function sell_application_doc($doc_num)
    {
        $this->_application_doc($doc_num, 'seller_document_image_path');
    }



    private function _application_doc($doc_num, $doc_field)
    {
        $this->load->model('documents_model','documents');
        $this->load->library('image');

        $user_id = $this->account->get_user_id();

        $doc_name = $this->currency_exchange->get_doc_file_name_by_user_id( $user_id, $doc_num );

        $doc_name = $doc_name->{$doc_field};

        if( empty( $doc_name ) ||  $doc_name == 'wt')
        {
            echo "No docs";
            return FALSE;
        }

        $fileDecoded = $this->code->fileDecode($doc_name);

        if( empty($fileDecoded) )
        {
            echo "File is empty";
            return FALSE;
        }

        $explode_res = explode('.', $doc_name);

        $ext = $explode_res[1];

        @ob_end_clean();

        if( $ext == 'pdf' ){
            header("Content-Type: application/pdf");
            echo $fileDecoded;

            return;
        }

        echo $this->image->getImageContentWithWarterMark( $fileDecoded,"webtransfer-finance.com ID {$user_id}", '#ad2222', 1 );
    }


    public function payment_preferences()
    {
        $viever_g = "currency_exchange/payment_preferences";
        $title_g = _e('currency_exchange/payment_preferences/controller');

        $data = new stdClass();
        $data->show_payment_data = true;

        $protection_type = Security::getProtectType($user_id);
        $data->security = $protection_type;

        if(!$data)
        {
            $data = new stdClass();
        }

        $user_id = $this->account->get_user_id();

        $payment_systems_groups = $this->currency_exchange->get_all_payment_systems_groups();
        $data->payment_systems_groups = $payment_systems_groups;

        $data->payment_systems = $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $data->buy_amount_down = 0;
        $data->buy_amount_up = 0;

        $data->sell_amount_down = 0;
        $data->sell_amount_up = 0;

        $data->sell_sum_total = 0;
        $data->sell_sum_amount = 0;
        $data->sell_sum_fee = 0;

        $data->buy_payment_systems = array();
        $data->sell_payment_systems = array();
        $data->no_show_select_currency = true;

        $data->input_settings_class_for_ps = 'settings';

        foreach($payment_systems as $key => $val)
        {
            if($key == 'wt_visa_usd')
                continue;

            if(isset($val->payment_sys_user_data))
            {
                $data->buy_payment_systems[$key] = 1;
            }
        }

//        pred($payment_systems);
        require_once APPPATH.'controllers/user/Security.php';

        $data->notAjax = $this->base_model->returnNotAjax();

        $data->prefix_method_show_group_content = 'payment_preferences_';

        if($data->notAjax)
        {
            $this->content->user_view($viever_g, $data, $title_g);
        }
        else
        {
            $view_data = get_object_vars($data) +
                        get_object_vars(viewData()) +
//                        array("title_accaunt" => _e('account/data1') );
                        array("title_accaunt" => $title_g );

            $this->load->view('user/currency_exchange/'.$viever_g, $view_data );
        }
    }




    public function user_reject_order_before_end_time($id)
    {
        $int_id = intval($id);

        $this->db
            ->where('status', Currency_exchange_model::ORDER_STATUS_CONFIRMATION)
            ->where('seller_user_id', $this->user->id_user);

        $res = $this->currency_exchange->get_order_arhiv_by_id($int_id);

        if(empty($res))
        {
            return false;
        }

        $contr_res = $this->currency_exchange->get_order_arhiv_by_id($res->buyer_order_id);

        $data = new stdClass();

        if(!in_array($res->seller_user_id, $this->currency_exchange->p2p_test_users) || !in_array($contr_res->seller_user_id, $this->currency_exchange->p2p_test_users))
        {
            accaunt_message($data, _e('Временно недоступно'), 'error');

            $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');

            return false;
        }

        if($contr_res->wt_set == 1 &&
            ($contr_res->buyer_send_money_date == '0000-00-00 00:00:00' && $contr_res->seller_send_money_date == '0000-00-00 00:00:00') &&
            $contr_res->initiator == 0 &&
            strtotime($contr_res->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
          )
        {
            $this->currency_exchange->user_block($contr_res, 2);
            $this->currency_exchange->set_order_canceld_by_user_block($contr_res->id);
            accaunt_message($data, _e('Сделка разорвана, заявка доступна в поиске.'));
        }

        if($contr_res->wt_set == 2 &&
               ($contr_res->buyer_send_money_date == '0000-00-00 00:00:00' &&
                   $contr_res->seller_send_money_date == '0000-00-00 00:00:00') &&
                $contr_res->initiator == 1 &&
               $contr_res->bank_set != 1 &&
               strtotime($contr_res->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT <= time()
           )
        {
            $this->currency_exchange->user_block($contr_res, 1);
            $this->currency_exchange->set_order_canceld_by_user_block($contr_res->id, 83, 83);
            accaunt_message($data, _e('Сделка разорвана'));
        }

        if($contr_res->wt_set == 2 &&
               ($contr_res->buyer_send_money_date == '0000-00-00 00:00:00' &&
                   $contr_res->seller_send_money_date == '0000-00-00 00:00:00') &&
                $contr_res->initiator == 1 &&
               $contr_res->bank_set == 1 &&
               strtotime($contr_res->buyer_confirmation_date)+Currency_exchange_model::ORDER_TIME_OUT_MONEY_SEND_WT_BANK <= time()
           )
        {
            $this->currency_exchange->user_block($contr_res, 1);
            $this->currency_exchange->set_order_canceld_by_user_block($contr_res->id, 83, 83);

            accaunt_message($data, _e('Сделка разорвана'));
        }

        $this->redirect_iframe(site_url('account/currency_exchange/my_sell_list'), 'refresh');
    }



    private function ajax_responce( $message, $type = 'error' )
    {
         $res = [];
         if( is_string( $message ) ) $res[$type] = $message;
         else
             if(is_array( $message ) ) $res = $message;

        echo json_encode ($res);
     }


     //отдает 10 последних сдело для модуля
     private function ajax_get_last_deal_items()
//      function get_last_deal_items()
     {
         return $this->ajax_responce('empty','success');
        $dt = $this->input->post('dt',TRUE);
        $count = $this->input->post('count',TRUE);
        if( empty($count) || $count < 10 ) $count = 10;
        if( $count > 50 ) $count  = 50;

        $this->load->model('currency_exchange_model','currency_exchange');
        $last_deal_items = $this->currency_exchange->get_last_deal_items( $dt, true, $count );

        if( empty($last_deal_items) ) return $this->ajax_responce('empty','success');


        echo $last_deal_items;
        return;
     }












    ########################################################################################################################
    ########################################################################################################################
    ########################################################################################################################
    ########################################################################################################################
    ########################################################################################################################

    private function _temp_save_ps2($country_id, $order, $val)
    {
        pred('>>>>>!!!');
//        $m_name = $val->FINm.' '.$val->GIIN;
//        $name = (string)$val->FINm;
//
//        $m_name = mb_strtolower($m_name);
//        $m_name = preg_replace('/[^\w-]/u', '_', $m_name);

        $data = [
            'root_currency' => 0,
            'group_id' => 1,
            'country_id' => $country_id,
            'payment_id' => 0,

            'machine_name' => $val->machine_name,
            'humen_name' => $val->humen_name,

            'city' => $val->city,
            'branch' => $val->branch,
            'swift_code' => $val->swift_code,


            'is_bank' => 1,
            'present_out' => 2,
            'present_in' => 1,
            'fee_percentage' => 1,
            'fee_percentage_add' => 0,
            'fee_min' => 0,
            'fee_max' => 0,
            'method_calc_fee' => '',
            'method_set_ps_data' => 'set_universal_bank',
            'method_get_field_and_ps_data' => 'get_fild_and_ps_data_universal_bank',
            'currency_id' => '840',
            'public_path_to_icon' => 'background-position: 45px 45px;',
            'transaction_bonus' => 0,
            'active' => 1,
            'order' => $order,
        ];

        if($country_id == 219)
        {
            $data['method_set_ps_data'] = 'set_sberbank';
            $data['method_get_field_and_ps_data'] = 'get_fild_and_ps_data_sberbank';
        }

//        pred($data);

        $this->db
//            ->insert('currency_exchange_payment_systems_temp', $data);
            ->insert('currency_exchange_payment_systems', $data);
    }


    private function _insert_in_parce_temp($data)
    {
//        currency_exchange_parce_temp_payment_systems
        $m_name = $data['humen_name'].'_'.$data['swift_code'];
        $m_name = mb_strtolower($m_name);
        $m_name = preg_replace('/[^\w-]/u', '_', $m_name);
        $data['machine_name'] = $m_name;

         $this->db
            ->insert('currency_exchange_parce_temp_payment_systems', $data);
    }


    private function _is_page_exist($url)
    {
        $headers = get_headers($url, TRUE);
        if($headers[0] == "HTTP/1.1 404 Not Found")
        {
            return false;
        }

        return true;
    }



    private function temppp2()
    {
        pred('@@@@');
        ignore_user_abort(false);

        $this->load->library('simple_html_dom');

        $url_array = [];
        $url_array[] = 'http://www.theswiftcodes.com/country/a/';
        $url_array[] = 'http://www.theswiftcodes.com/country/b/';
        $url_array[] = 'http://www.theswiftcodes.com/country/c';
        $url_array[] = 'http://www.theswiftcodes.com/country/d-e/';
        $url_array[] = 'http://www.theswiftcodes.com/country/f-g/';
        $url_array[] = 'http://www.theswiftcodes.com/country/h-i/';
        $url_array[] = 'http://www.theswiftcodes.com/country/j-l/';
        $url_array[] = 'http://www.theswiftcodes.com/country/m/';
        $url_array[] = 'http://www.theswiftcodes.com/country/n/';
        $url_array[] = 'http://www.theswiftcodes.com/country/o-r/';
        $url_array[] = 'http://www.theswiftcodes.com/country/s/';
        $url_array[] = 'http://www.theswiftcodes.com/country/t/';
        $url_array[] = 'http://www.theswiftcodes.com/country/u-z/';

        vre('Start parce url');

        $country_url = [];

        foreach($url_array as $one_url)
        {
            $html = file_get_html($one_url);

            foreach ($html->find('ol.country', 0)->find('li') as $li)
            {

                $a = $li->find('a', 0);
                $url = $a->href;
                $country = $a->innertext;

//                http://www.theswiftcodes.com/afghanistan/

                $country_url[$country] = 'http://www.theswiftcodes.com'.$url;
            }
            vre($one_url);
        }
        vre(' END PARCE URL#########################################################');
        vre('#######################################################################');

//        http://www.theswiftcodes.com/afghanistan/
//        $country_url['afghanistan'] = 'http://www.theswiftcodes.com/afghanistan/';
//        $country_url['united-states'] = 'http://www.theswiftcodes.com/united-states/';
//        $country_url['russia'] = 'http://www.theswiftcodes.com/russia/';

        foreach($country_url as $country_name => $country_url)
        {
            $page = 1;
            $url = $country_url;

            while($this->_is_page_exist($url))
            {

//                pred($country_url);
                $this->_get_bank_country($country_name, $url);
//                pred($url);
                vre($url);

                $page ++;


                $url = $country_url.'page/'.$page.'/';
//                if($page > 1)
//                {
//                    $country_url .= 'page/'.$page.'/';
//                }
            }
//            vre($country_url);
//            vred($this->_is_page_exist($country_url));
        }

        pred('>>>>');
    }


    private function _get_bank_country($country, $url)
    {
//         $html = file_get_html('http://www.theswiftcodes.com/united-states/');
        $html = file_get_html($url);

//        $rate_str = $html->find('.col-md-offset-1 td[style="text-align:right;"]',0)->innertext;
        foreach ($html->find('table.swift tr') as $tr )
        {
            if(!is_numeric($tr->find('td',0)->innertext))
            {
                continue;
            }

            $data = [
              'country' => $country,
              'humen_name' => $tr->find('td',1)->innertext,
              'city' => $tr->find('td',2)->innertext,
              'branch' => $tr->find('td',3)->innertext,
              'swift_code' => $tr->find('td',4)->find('a',0)->innertext,
            ];
//            vre($data);
            $this->_insert_in_parce_temp($data);

            unset($data);
//            vre($tr->find('td',0)->innertext);
//            vre(is_numeric($tr->find('td',0)->innertext));
//            vre($tr->find('td',1)->innertext);
//            vre($tr->find('td',2)->innertext);
//            vre($tr->find('td',3)->innertext);
//            vre($tr->find('td',4)->find('a',0)->innertext);
//            vre($tr->find('td',4)->innertext);
//            vre('####################################################################3');
//            pred($data);
        }
//        pred('>>>>');
//        vre($rate_str = $html->find('table.swift tr.alternate',0)->innertext);
//        pre('>>>>');
//        vred($rate_str = $html->find('table.swift',0)->innertext);
    }



    //----------------------------------------------------------------------------------------------------------------------

    private function _getNotSetCountry_2($c_name)
    {
        $all_country = $this->currency_exchange->get_all_country_name();

        $country_ids = [
            'American Samoa' => 372,
            'Bonaire' => 352,
            'Cayman Islands' => 259,
            'Congo, Democratic Republic' => 312,
            'Congo, Republic' => 441,
            'Côte d’Ivoire' => 315,
            'Curaçao' => 352,
//            'Dominica' => 'Dominica',
            'Falkland Islands' => 259,
            'Guam' => 372,
            'Guinea Bissau' => 272,
//            'Kosovo' => 'Kosovo',
            'Macao' => 309,
            'Marshall Islands' => 372,
            'Mayotte' => 417,
            'Montserrat' => 259,
            'Myanmar' => 345,
//            'Palestinian Territory' => 'Palestinian Territory',
            'Réunion' => 417,
            'São Tomé and Príncipe' => 376,
            'Sint Maarten' => 352,
            'St Vincent and Grenadines' => 386,
            'Timor Leste' => 262,
            'Vatican City' => 299,
            'Virgin Islands (UK)' => 252,
            'Virgin Islands (US)' => 443,
        ];

        if (isset($country_ids[$c_name]) && $all_country[$country_ids[$c_name]])
        {
            $res = $all_country[$country_ids[$c_name]];

            return $res;
        }

        return false;
    }




    private function temppp3()
    {
        $temp_countrys = $this->db
                ->group_by('country')
                ->get('currency_exchange_parce_temp_payment_systems')
                ->result();

        $all_country = $this->currency_exchange->get_all_country_name();

        $order          = 10;
        $save           = 0;
        $not_save       = 0;
        $not_save_arr   = [];
        $not_save_arr_c = [];
        $branch         = 0;

        foreach ($temp_countrys as $val)
        {
            $found_country = false;

            foreach ($all_country as $country)
            {
                if (mb_strtolower($val->country) == mb_strtolower($country->country_name_en))
                {
                    $found_country = $country;
                    break;
                }
            }

            if ($found_country === false)
            {
                $found_country = $this->_getNotSetCountry_2($val->country);
            }

            if ($found_country === false)
            {
                $not_save_arr[]                = $val;
                $not_save_arr_c[$val->country] = $val->country;
                $not_save ++;
                continue;
            }


            $one_country_data = $this->db
                ->where('country', $val->country)
                ->get('currency_exchange_parce_temp_payment_systems')
                ->result();

            foreach($one_country_data as $v)
            {
                $this->_temp_save_ps2($found_country->id, $order, $v);
            }

            vre($found_country->country_name_en);

            $order += 10;
            $save ++;
//           pred($found_country);
        }

        vre('save = ' . $save);
        vre('not save = ' . $not_save);
//        vre('Branch  = '. $branch);
        pred($not_save_arr_c);
    }

    //----------------------------------------------------------------------------------------------------------------------





    private function _temp_save_ps($country_id, $order, $val, &$lang)
    {
        $m_name = $val->FINm.' '.$val->GIIN;
        $name = (string)$val->FINm;

        $m_name = mb_strtolower($m_name);
        $m_name = preg_replace('/[^\w-]/u', '_', $m_name);

        $data = [
            'root_currency' => 0,
            'group_id' => 1,
            'country_id' => $country_id,
            'payment_id' => 0,
            'machine_name' => $m_name,
            'humen_name' => $name,
            'is_bank' => 1,
            'present_out' => 2,
            'present_in' => 1,
            'fee_percentage' => 1,
            'fee_percentage_add' => 0,
            'fee_min' => 0,
            'fee_max' => 0,
            'method_calc_fee' => '',
            'method_set_ps_data' => 'set_universal_bank',
            'method_get_field_and_ps_data' => 'get_fild_and_ps_data_universal_bank',
            'currency_id' => '840',
            'public_path_to_icon' => 'background-position: 45px 45px;',
            'transaction_bonus' => 0,
            'active' => 1,
            'order' => $order,
        ];

        $lang[$m_name] = $name;
    //        pre($lang);
    //        pre($data);
    //        file_put_contents('', $data);
    //        pre($data);
    //        (`id`, `root_currency`, `group_id`, `country_id`, `payment_id`, `machine_name`, `is_bank`, `present_out`, `present_in`,
    //        `fee_percentage`, `fee_percentage_add`, `fee_min`, `fee_max`, `method_calc_fee`,
    //        `method_set_ps_data`, `method_get_field_and_ps_data`, `currency_id`, `public_path_to_icon`, `transaction_bonus`, `active`, `order`) VALUES
    //        (7, 0, 5, 372, 0, 'jp_morgan_chase', 1, 2, 1,
    //         1, 0, 0, 0, '',
    //          'set_universal_bank', 'get_fild_and_ps_data_universal_bank', 840, 'background-position: -2px -134px;', 0, 1, 1);

    //        $this->db
    //            ->insert('currency_exchange_payment_systems_temp_2', $data);
    }


    private function _getNotSetCountry($c_name)
    {
        $all_country = $this->currency_exchange->get_all_country_name();

        $country_ids = [
            'CAYMAN ISLANDS' => 259, //UK
            'RUSSIAN FEDERATION' => 219,
            'VIRGIN ISLANDS (BRITISH)' => 252,
            'KOREA, REPUBLIC OF' => 434,
            'OTHER' => 439,   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            'MACAO' => 309, //china
            'TANZANIA, UNITED REPUBLIC OF' => 400,
            'CURACAO' => 352, // Нидерланды (Голландия) 352
            'MYANMAR' => 345,
            'MARSHALL ISLANDS' => 372, // USA 372
//            'WEST BANK AND GAZA' => 'WEST BANK AND GAZA', ?????? palistina
            'LAO PEOPLE\'S DEMOCRATIC REPUBLIC' => 320,
            'VIET NAM' => 263,
            'MOLDOVA, REPUBLIC OF' => 341,
            'COTE D\'IVOIRE' => 315,
            'BOLIVIA, PLURINATIONAL STATE OF' => 248,
            'BONAIRE, SINT EUSTATIUS AND SABA' => 352, //Карибские нидерланды
            'CONGO, DEMOCRATIC REPUBLIC OF THE' => 312,
            'VENEZUELA, BOLIVARIAN REPUBLIC OF' => 261,
            'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF' => 332,
            'SINT MAARTEN (DUTCH PART)' => 352, // в составе Королевства Нидерландов
            'MONTSERRAT' => 259, // заморская территория Великобритании
//            'DOMINICA' => 'DOMINICA', // страна остров.
            'CENTRAL AFRICAN REPUBLIC' => 440,// !!! нужно добавить
            'BRUNEI DARUSSALAM' => 253,
            'CONGO' => 441,
            'FRENCH SOUTHERN TERRITORIES' => 417, //заморское особое административно-территориальное образование Франции
            'ALAND ISLANDS' => 416, //автономия в составе Финляндии
            'BOUVET ISLAND' => 356, //Зависимая территория Норвегии --- необитаемый вулканический остров
            'SAINT MARTIN (FRENCH PART)' => 417, // заморское сообщество Франции
            'TIMOR-LESTE' => 262,
            'BRITISH INDIAN OCEAN TERRITORY' => 259, //заморская территория Великобритании
            'HOLY SEE (VATICAN CITY STATE)' => 299, // ватикан - Итальянщина
            'SYRIAN ARAB REPUBLIC' => 389,
            'SOUTH SUDAN' => 442, //Южный судан  -- нужно добавить
            'GUAM' => 372, //имеющий статус неинкорпорированной организованной территории США
            'VIRGIN ISLANDS (U.S.)' => 443, // Нужно добавить -- Виргинские острова США
            'FALKLAND ISLANDS (MALVINAS)' => 259, //Фолклендские острова являются британской заморской территорией
            'NORTHERN MARIANA ISLANDS' => 372, // имеющее статус неинкорпорированной организованной территории, свободно ассоциированной с США
            'MICRONESIA, FEDERATED STATES OF' => 372, //Формально независимая с 3 ноября 1986 года, страна остаётся тесно связанной с США (статус «свободной ассоциации с США») и сильно зависит от американской экономической помощи
            'PALAU' => 372, // островное государство, ассоциированное с США
        ];

        if(isset($country_ids[$c_name]) && $all_country[$country_ids[$c_name]])
        {
            $res = $all_country[$country_ids[$c_name]];
//            $lang
            return $res;
        }

        return false;
    }


    private function temppp()
    {
        //INSERT INTO `currency_exchange_payment_systems_temp_2`
//        $asd = "
//        <IRSFFIList>
//            <FinancialInstitution>
//                <GIIN>AAAFIM.99999.SL.826</GIIN>
//                <FINm>The Trustees of Col R J Hoare No 2 Settlement</FINm>
//                <CountryNm>UNITED KINGDOM</CountryNm>
//            </FinancialInstitution>
//               ";

        $all_country = $this->currency_exchange->get_all_country_name();

//        vred(mb_strtolower($all_country[219]->country_name_en));

        $ValCurs = simplexml_load_file(APPPATH.'/../FFIListFull.xml');
//        $ValCurs = simplexml_load_string($asd);

//        vre(empty($ValCurs));
//        vre($ValCurs->FinancialInstitution);
//        vred($ValCurs);
        vre('all = '.count($ValCurs->FinancialInstitution));

        $order = 10;
        $save = 0;
        $not_save = 0;
        $not_save_arr = [];
        $not_save_arr_c = [];
        $branch = 0;

        $lang = [];

        foreach($ValCurs->FinancialInstitution as $val)
        {
            $found_country = false;

            if((string)$val->FINm == 'Branch')
            {
                $branch++;
                continue;
            }

            foreach($all_country as $country)
            {
                if(mb_strtolower($val->CountryNm) == mb_strtolower($country->country_name_en))
                {
                    $found_country = $country;
                    break;
                }
            }

            if($found_country === false)
            {
                $found_country = $this->_getNotSetCountry((string)$val->CountryNm);
            }


            if($found_country === false)
            {
                $not_save_arr[] = $val;
                $not_save_arr_c[(string)$val->CountryNm] = (string)$val->CountryNm;
//                vre($not_save_arr_c);
//                vre($val);
//                vre((string)$val->CountryNm);
                $not_save ++;
                continue;
            }

            $country_id = $found_country->id;
            $order += 10;
            $save ++;


//            continue;
            $this->_temp_save_ps($country_id, $order, $val, $lang);
////
//            if($order >= 30)
//            {
////                vred('>>>>>');
//                break;
//            }


//            vre($val->GIIN);
//            vre($val->FINm);
//            vre($val->CountryNm);
//            vred('>>>>');
        }

//        $txt = '$$$';
//        $txt = '';
//        foreach($lang as $k => $l)
//        {
//            $txt .= sprintf("\$lang['%s'] = '%s';\n", $k, $l);
//        }
////        pre($lang);
////        pred($txt);
//        file_put_contents(APPPATH.'/../temp_lang.php', $txt);

        vre('save = '. $save);
        vre('not save = '. $not_save);
        vre('Branch  = '. $branch);
        pred($not_save_arr_c);
//        pred($not_save_arr);


//        $ValCurs = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req='.date('d.m.Y'));
//
//        $value = false;
//        foreach($ValCurs->Valute as $val)
//        {
//            if($val->CharCode == $char_code)
//            {
//                $value = (string)$val->Value;
//                $nominal = (float)$val->Nominal;
//                break;
//            }
//        }
//
//        $value = preg_replace('|,|', '.', $value);
//
//        return (float)$value/$nominal;

        return false;




        $data = new stdClass();

        $user_id = $this->account->get_user_id();
        $data->user_ratings = viewData()->accaunt_header;

        $payout_limit_arr = $this->currency_exchange->get_root_payout_limit_arr($data->user_ratings);

        $payout_limit_arr_buy = array();

        for($i = 50; $i<1000+1; $i += 50)
        {
            $payout_limit_arr_buy[] = $i;
        }

        $data->payout_limit_arr = $payout_limit_arr;
        $data->payout_limit_arr_buy = $payout_limit_arr_buy;

        $viever_g = "currency_exchange/sell_wt_new_1";

        $this->content->user_view($viever_g, $data, 'lalallal!!!!');
        return false;
//         pred(get_country_list());
        die();
        $countrys = get_country_list();
        foreach ($countrys as $val)
        {
            if(empty($val['wire_form']))
            {
                continue;
            }

            $res = $this->db
                    ->where('country_name_ru', $val['name'])
                    ->get('currency_exchange_country')
                    ->row()
                    ;

            if(empty($res))
            {
                pre($val);
            }
//            vred($res);
        }
        die('#####');
        foreach ($countrys as $val)
        {
            if(empty($val['wire_form']))
            {
                continue;
            }

            $res = $this->db
                    ->where('country_name_ru', $val['name'])
                    ->update('currency_exchange_country', ['wire_form' => $val['wire_form']]);

            if($res !== true)
            {
                pre($val);
            }
//            vred($res);
        }

        die('>>>>>>');

        $res = $this->currency_exchange->get_root_currencys();
        $res_id = $this->currency_exchange->get_root_currencys_id();
        $res2 = $this->currency_exchange->is_root_currency(111);
        pre($res);
        pre($res_id);
        vred($res2);
 //        pred('>>>>>>>>');
//        $data = new stdClass();
//        $data->asd = '123';
//        echo  $this->load->view('user/accaunt/currency_exchange/blocks/dop_input_form/test_1.php', $data, true);
////        echo '<<<<<<<<<<<';
////        echo '<<<<<<<<<<<';
//        die;
    }

    public function giftguarant_market() {
        viewData()->page_bottom = '';

        viewData()->page_name = "Giftguarant-market";
       $this->content->user_view('giftguarant/giftguarant_market', (object)[], _e('Gift'));
    }























    ### for webtransfer.exchanger ###
    public function iframe_giftguarant_market() {
        viewData()->page_bottom = '';

        viewData()->page_name = "Giftguarant-market";
        $this->content->iframe_user_view('giftguarant/giftguarant_market', (object)[], _e('Gift'));
    }

    public function iframe_payment_preferences() {
        viewData()->page_bottom = '';

        $viever_g = "currency_exchange/iframe_payment_preferences";
        $title_g = _e('currency_exchange/payment_preferences/controller');

        $data = new stdClass();
        $data->show_payment_data = true;

        $protection_type = Security::getProtectType($user_id);
        $data->security = $protection_type;

        if(!$data)
        {
            $data = new stdClass();
        }

        $user_id = $this->account->get_user_id();

        $payment_systems_groups = $this->currency_exchange->get_all_payment_systems_groups();
        $data->payment_systems_groups = $payment_systems_groups;

        $data->payment_systems = $payment_systems = $this->currency_exchange->get_user_all_paymant_data($user_id);

        $data->buy_amount_down = 0;
        $data->buy_amount_up = 0;

        $data->sell_amount_down = 0;
        $data->sell_amount_up = 0;

        $data->sell_sum_total = 0;
        $data->sell_sum_amount = 0;
        $data->sell_sum_fee = 0;

        $data->buy_payment_systems = array();
        $data->sell_payment_systems = array();
        $data->no_show_select_currency = true;

        $data->input_settings_class_for_ps = 'settings';

        foreach($payment_systems as $key => $val)
        {
            if($key == 'wt_visa_usd')
                continue;

            if(isset($val->payment_sys_user_data))
            {
                $data->buy_payment_systems[$key] = 1;
            }
        }

        require_once APPPATH.'controllers/user/Security.php';

        $data->notAjax = $this->base_model->returnNotAjax();

        $data->prefix_method_show_group_content = 'payment_preferences_';


        $this->content->iframe_user_view($viever_g, $data, $title_g);
    }


    public function iframe_my_sell_list_arhive()
    {
        viewData()->page_bottom = '';

        $data = new stdClass();
        $user_id = $this->account->get_user_id();
        $data->user_id = $user_id;

        $data->table_dop_data_sell = 'Вы отдали';
        $data->table_dop_data_buy = 'Вы получили';
        $data->table_header_sell = 'Вы отдали';
        $data->table_header_buy = 'Вы получили';

        $data->orders_on_list = 10;

        $data->filter_state = 't0';
        $send_type = $this->filter_operation($data->filter_state);

        $res = $this->_load_more_orders_arhive($send_type, $data->orders_on_list, 1);

        $data->orders = $res->orders;

        $data->payment_systems = $res->payment_systems;
        $data->payment_systems_id_arr = $res->payment_systems_id_arr;
        $data->count_all_orders = $res->count_all_orders;


        $data->button = 'confirme';

        if(empty($data->orders_arhive_problem))
        {
            $data->orders_arhive_problem = array();
        }

        $data->url_load_more_orders = site_url('account/currency_exchange/ajax/load_more_orders_sell_arhive');

        $data->table_search_set_title_contragent = true;
        $data->table_search_set_value_contragent = true;

        $data->curency_problem = $this->currency_exchange->get_all_curency_problem_subject();

        $data->notAjax = $this->base_model->returnNotAjax();

        $this->content->iframe_user_view("currency_exchange/my_sell_list", $data, _e('currency_exchange/my_sell_list_arhive/arhiv'));
    }


    public function iframe_sell()
    {
        viewData()->page_bottom = '';

        $viever_g = "currency_exchange/sell_wt_new";

        $title_g = 'P2P Webtransfer';
        $data = new stdClass();
        $data->show_payment_data = true;
        $data->input_settings_class_for_ps = '';

        $type = Currency_exchange_model::ORDER_TYPE_SELL_WT;


        $this->_curency_excahnge($viever_g, $title_g, $type, $data, $bonus = 5, $iframe = true);
    }


    public function iframe_my_sell_list() {
//        $_SESSION['my_ref'] = 'excha';
        viewData()->page_bottom = '';

        $this->my_sell_list($iframe = true);
    }

    public function iframe_sell_search() {
//        $_SESSION['my_ref'] = 'excha';
        viewData()->page_bottom = '';
        $this->sell_search($iframe = true);
    }






}
