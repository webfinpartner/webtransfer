<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php'; }

class Partner extends Accaunt_base
{
    function  __construct(){
        parent::__construct();
   //     if($this->user->partner==2)  redirect(site_url('account/'));

        viewData()->banner_menu = "partner";
        viewData()->secondary_menu = "partner";
        $this->load->model('partner_model');
        $this->load->model('users_model', "users");
        $this->load->model('user_balances_model', 'user_balances');

        viewData()->page_name = null;
        $this->content->clientType=2;

        $this->bal = getSession('bal');
        if(null === $this->bal || (time()-getSession('bal_tume')) > 12*60*60){
            $id_user = $this->user->id_user;
    //        $start = date("Y-m-d", strtotime("first day of previous month"));
            $end = date("Y-m-d", strtotime("yesterday"));
    //        $bal = $this->user_balances->getBalances($id_user,$start,$end);
            $this->bal = $this->user_balances->getBalances($id_user, null, $end);
            $_SESSION['bal'] = $this->bal;
            $_SESSION['bal_tume'] = time();
        }
        viewData()->bal = $this->bal;
    }

    public function support() {
        $data = new stdClass();
        $this->load->model("users_model","users");
        $data->id_user = $this->users->getCurrUserId();
        if ($data->hasParent = $this->users->hasParent())
            $data->id_parent_user = $this->users->getParentUserId();
        if ($data->is_volanteer = $this->users->isVolanteer())
            $data->id_children = $this->users->getChildrenId();

        $this->load->model("volunteer_topic_model");
        if ($data->is_volanteer)
            $data->volanteer_topics = $this->volunteer_topic_model->getVolanteerTopics($data->id_children);
        if ($data->hasParent)
            $data->user_topics = $this->volunteer_topic_model->getUserTopics($data->id_user);
        $this->content->user_view('support',$data,_e('partner/data1'));
    }

    public function closeTopic($id = null) {
        if (null == $id) show_404();
        $this->load->model('volunteer_topic_model');
        $this->volunteer_topic_model->setClose($id);
        redirect(site_url('partner/support'));
    }

    public function home(){
        $data = new stdClass();
         $data->page_name = __FUNCTION__;
        $per_page="10";
        $data->pages =$this->base_model->pagination($per_page,$this->db->count_all('system_news'),'account',2);
        $data->news= $this->base_model->get_system_news($per_page);
        $this->content->user_view('home',$data,_e('partner/data2'));
    }

    public function top(){

        $data = new stdClass();
        $data->page_name = __FUNCTION__;
        $this->load->model('partners_url_model');
        $data->top = $this->partners_url_model->top(10);
        $this->content->user_view('partner_top',$data,_e('ТОП10 страниц'));

    }

    public function get_bonus(){

        $data = new stdClass();
        $data->page_name = __FUNCTION__;

        $this->load->model('accaunt_model', 'accaunt');
        $id_user = $this->accaunt->get_user_id();
        $this->accaunt->payBonusesToPartner($id_user);
        if ( !$this->accaunt->isUS2USorCA($id_user))
            $this->accaunt->payBonusesOnRegister($id_user);
        redirect('account/transactions');
        //$data->bonus_was_payed = $bonus_was_payed;
        //$this->content->user_view('partner_bonus',$data,_e('Бонус'));

    }


    public  function banner(){
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $data->banners= $this->partner_model->banner();
        $this->content->user_view('banner',$data,_e('partner/data3'));
    }

    public  function invite(){
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $this->content->user_view("invite", [], _e('account/data3'));
    }



//    public  function transaction(){
//        $data = new stdClass();
//        viewData()->page_name = __FUNCTION__;
//
//        $data->transactions = $this->partner_model->getPartnerTransaction(1);
//        $data->transactions_wait = $this->partner_model->getPartnerTransaction(2);
//        $this->content->user_view('partner_transaction',$data,_e('partner/data4'));
//    }

    public function about_partner(){
        viewData()->page_name = __FUNCTION__;

        $data = new stdClass();
        $data->page = $this->base_model->get_page('partnership');
        $this->content->user_view('about_partner',$data,_e('partner/data5'));
    }

    public  function activeClient(){
        $this->base_model->setType($this->user->id_user,'client');
        redirect(site_url('account'));
    }


    public function my_users($per_page = 20, $blocked = 't', $not_varif = 't', $varif = 't', $page = 0){
        $this->load->model('users_model', 'users');
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('users_photo_model', 'users_photo');
        $user_id = $this->users->getCurrUserId();

        if ( !empty($_POST['request_id']) && !empty($_POST['status']) ){
            $id = $_POST['request_id'];
            $status = $_POST['status'];
            $request = $this->users->get_change_partner_request($id);
            if ( $request->parent_user_id == $user_id ){
                $res = $this->users->set_change_partner_request_status($id, $status);
                if ( $res )
                    accaunt_message ($data, $status==1?_e('Пользователь успешно добавлен в Вашу партнерскую сеть'):_e('Заявка успешно отклонена'));
                else
                    accaunt_message ($data, _e('Не удалось выполнить операцию', 'error'));

            } else {
                accaunt_message ($data, _e('Вы не имеет прав подтверждать чужие заявки', 'error'));
            }
        }
        
        $statuses = [
            'blocked' => getValPartnersType($blocked),
            'not_verifyed' => getValPartnersType($not_varif),
            'verifyed' => getValPartnersType($varif),
            'start' => (int) $page,
            'step' => (int) $per_page
        ];
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $data->list = $this->partner_model->getUsers(NULL, NULL, NULL, $statuses);
        $data->listCount = $this->partner_model->getCountUsers();
        $data->pages = $this->base_model->pagination($per_page, $data->listCount, "partner/my_users/$per_page/$blocked/$not_varif/$varif", 8);
        $data->request_list = $this->users->get_change_partner_requests(NULL, $user_id, [0]);
        $data->statuses = (object) $statuses;
        foreach( $data->request_list as &$r){
            $r->user = $this->accaunt->getUserFields($r->user_id, ['id_user', 'reg_date', 'parent', 'sername', 'name', 'email', 'phone' ]);
            $r->user->social = $this->accaunt->getSocialList($r->user_id);
            $r->user->status = $this->accaunt->isUserAccountVerified($r->user_id);
            $r->user->foto = $this->users_photo->getUserPhotoUrl($r->user_id);
        }

        $data->volunteer = (Base_model::USER_VOLUNTEER_ON == viewData()->user->volunteer) ? true : false;
        $this->content->user_view('partner_myusers',$data,_e('partner/data6'));
    }


    public function my_balance() {
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $id_user = $this->user->id_user;

        /*$data->month = date('m');
        $data->year = date('Y');

        $input_month = $this->input->get_post('month');
        $input_year = $this->input->get_post('year');

        if ( !empty($input_month) && !empty($input_year)){
            $data->month = $input_month;
            $data->year = $input_year;
            $end = date($input_year.'-'.$input_month.'-t');
            $this->bal = $this->user_balances->getBalances($id_user, null, $end);
        } */

        $input_month_year = $this->input->get_post('month_year');
        $data->month_year = date('Y-m');
        if ( !empty($input_month_year) ){
            $data->month_year = $input_month_year;
            $start = date($input_month_year.'-01');
            $end = date($input_month_year.'-t');
            $this->bal = $this->user_balances->getBalances($id_user, $start, $end);
        }






        $bal = $this->bal;

        $this->load->model('user_balances_model', 'user_balances');
        $d = array();
        $max = 0;
        $partnerPlansSum = config_item('partner-plan-sum');
        foreach ($bal as $key => $value) {
            $d['label'][] = $key;
            $d['line_bal'][] = $value;
            $max = ($max < $value) ? $value : $max;
        }
        $lineLevel = 0;
        foreach ($partnerPlansSum as $plan => $sum) {
            if($max >= $sum*0.5) $lineLevel = $plan;
            if($max >= $sum) $lineLevel = $plan;
            if($lineLevel == $plan){
                foreach ($bal as $key => $value) {
                    $d['line_'.$plan][] = $sum;
                }
            }
        }

        $partnerPlans = config_item('partner-plan');
        $partnerPlansName = config_item('partner-plan-name');
        $levelofPartner = $this->base_model->getUserParnerPlan($id_user);
        $data->chart = $d;
        $data->bal = $bal;
        $data->lavel = $partnerPlans[$levelofPartner];
        $data->lavel_name = $partnerPlansName[$levelofPartner];
        $data->lavel_update = $this->base_model->getUserField($id_user, 'partner_plan_update', false);
        $this->content->user_view('partner_mybalance',$data,_e('partner/data17'));
    }

    public function trigerVolunteer($backPage = 'my_users') {
        $this->users->trigerVolunteer($this->user->id_user);
        redirect(site_url('partner')."/$backPage");
    }


    public function expected_income(){
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $data->list = $this->partner_model->getExpectedIncome();
        $this->content->user_view('partner_expected_income',$data,_e('partner/data8'));
    }

    function int_sclonenie($number, $forms, $base = '') {
        $rest = $number % 10;
        $number = (int) substr($number, -2, 2);
        if ($rest == 1 && $number != 11) return $base . $forms[0];
        elseif (in_array($rest, array(2, 3, 4)) && !in_array($number, array(12, 13, 14))) return $base . $forms[1];
        else return $base . $forms[2];
    }

//    public function balances(){
//        $data = new stdClass();
//        viewData()->page_name = __FUNCTION__;
//        $data->list = $this->partner_model->getUsers();
//        foreach ($data->list as $item) {
//            $data->balance[$item->id_user] = $this->partner_model->getPartnerBalance($item->id_user);
//            $data->balance[$item->id_user][4] = $this->int_sclonenie($data->balance[$item->id_user][3], array(_e('partner/data10'), _e('partner/data11'), _e('partner/data12')));
//        }
//        $data->volunteer = (Base_model::USER_VOLUNTEER_ON == viewData()->user->volunteer) ? true : false;
//        $this->content->user_view('partner_balances', $data, _e('partner/data9'));
//    }

//    public function transactions($id_user = 0, $page = 0) {
//        if ($page == 0 && $id_user == 0) show_404();
//            $id_user = (int)$id_user;
//            $page = (int)$page;
//        if (!$this->partner_model->parentAbout($id_user)) show_404();
//
//        $data = new stdClass();
//        $this->load->helper('date');
//        $this->load->model('monitoring_model','monitoring');
//        viewData()->page_name = __FUNCTION__;
//
//        $this->monitoring->log( null, _e('partner/data13'), 'private', $id_user);
//        $per_page = "10";
//
//        # Получение cookie
//        $date = array();
//        $date['cookie'] = explode('|', $this->input->cookie('filter_transactions', TRUE));
//
//        # Проверка даты
//        $date['array'][0] = explode('/', (!$this->input->post('date_1', TRUE)) ? $date['cookie'][1] : $this->input->post('date_1', TRUE));
//        $date['array'][1] = explode('/', (!$this->input->post('date_2', TRUE)) ? $date['cookie'][2] : $this->input->post('date_2', TRUE));
//
//        if (@count($date['array'][0]) != 3 || !checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2])) $date['array'][0] = explode('/', "06/01/2014");
//        if (@count($date['array'][1]) != 3 || !checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2])) $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));
//
//        $date['first'][0] = $date['array'][0][0]."/".$date['array'][0][1]."/".$date['array'][0][2];
//        $date['last'][0]  = $date['array'][1][0]."/".$date['array'][1][1]."/".$date['array'][1][2];
//
//        $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1):($date['array'][0][1] - 1));
//        $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1):($date['array'][1][1] + 1));
//
//        $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 59:59:59";
//        $date['last'][1] = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";
//
//
//        # Проверка типа
//        if ($this->input->post('type_1', TRUE) !== FALSE) $type[0] = TRUE;
//        else if ($this->input->post('type_2', TRUE) == FALSE && $date['cookie'][0] == 1) $type[0] = TRUE;
//        else $type[0] = FALSE;
//
//        if ($this->input->post('type_2', TRUE) !== FALSE) $type[1] = TRUE;
//        else if ($this->input->post('type_1', TRUE) == FALSE && $date['cookie'][0] == 2) $type[1] = TRUE;
//        else $type[1] = FALSE;
//
//        if (($type[0] && $type[1]) || (!$type[0] && !$type[1])) $type = FALSE;
//        else if ($type[0]) $type = 1;
//        else $type = 2;
//
//
//        # Запись в COOKIE
//        if ($date['cookie'][1] != $date['first'][0] || $date['cookie'][2] != $date['last'][0] || $date['cookie'][0] != $type) $this->input->set_cookie(array('name' => 'filter_transactions', 'value'  => $type.'|'.$date['first'][0].'|'.$date['last'][0], 'expire' => '1200'));
//
//
//        # Назначаем элементы
//        $data->type = $type;
//        $data->date = $date;
//        $data->parent = TRUE;
//        $data->statistic = $this->partner_model->getPartnerBalance($id_user);
//
//        # Формируем запрос WHERE для вставки в запрос
//        $where = array("bonus ".((!$type) ? "!=" : "=") => $type, "date >" => $date['first'][1], "date <" => $date['last'][1], "id_user =" => $id_user);
//
//        $data->pages = $this->base_model->pagination($per_page, $this->partner_model->getCountPays($where), 'partner/transactions/' . $id_user, 5);
//
//        $data->pays = $this->partner_model->getPays($per_page, $page, $where);
//        $this->content->user_view('transaction', $data, _e('partner/data14') . $id_user);
//
//    }

//    # Вклады
//    public function invests($id_user = 0) {
//            $id_user = (int)$id_user;
//        if (!$this->partner_model->parentAbout($id_user)) show_404();
//
//        $data = new stdClass();
//
//        $data->type = 2;
//        $data->credits = $this->partner_model->get_partner_credits($data->type, $id_user);
//
//        $this->content->user_view("partner_credits", $data, _e('partner/data15') . $id_user);
//    }

//    # Кредиты
//    public function credits($id_user = 0) {
//            $id_user = (int)$id_user;
//        if (!$this->partner_model->parentAbout($id_user)) show_404();
//
//        $data = new stdClass();
//
//        $data->type = 1;
//        $data->credits = $this->partner_model->get_partner_credits($data->type, $id_user);
//
//        $this->content->user_view("partner_credits", $data, _e('partner/data16') . $id_user);
//    }


}