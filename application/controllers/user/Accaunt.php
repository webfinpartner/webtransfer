<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php'; }

class Accaunt extends Accaunt_base{

    public function __construct(){
        $login                  = parent::__construct();
        $user_id                = $this->accaunt->get_user_id();
//        $this->load->model('Card_model');
//        $cm = new Card_model();
//
//        if($cm->get_for_user($user_id)){
//            viewData()->card_pan = str_repeat("XXXX-",3) . $cm->card_pan;
//        }else{
//            viewData()->card_pan = null;
//        }
        if($login)
            viewData()->banner_menu = "profil";
        else
            viewData()->banner_menu = "profile_login";

        viewData()->secondary_menu = "profile";
        //viewData()->page_name = null;
        $this->content->clientType = 1;

        //var_dump(viewData()->accaunt_header);

        require_once APPPATH.'controllers/user/Security.php';
        viewData()->security = Security::getProtectType($user_id);

        $this->base_model->generate_page_hash();
    }

    public function ajaxSendQuestion(){
        $this->load->library('form_validation');
        $this->load->model('volunteer_topic_model');
        $this->load->model('volunteer_message_model');
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('users_model', 'users');
        $txt       = str_replace(PHP_EOL, "<br/>", htmlentities(removeXSS($this->input->post('text', true)), ENT_QUOTES | ENT_HTML5 | ENT_IGNORE, "UTF-8"));
        $tpc       = htmlentities(removeXSS($this->input->post('topic', true)), ENT_QUOTES | ENT_HTML5 | ENT_IGNORE, "UTF-8");
        $id_topic  = (int) $this->input->post('id');
        $for_admin = ("true" == $this->input->post("admin")) ? Volunteer_message_model::FOR_ADMIN_TRUE : Volunteer_message_model::FOR_ADMIN_FALSE;

        if(empty($txt)){
            echo json_encode(["e" => "no_text"]);
            return;
        }

        $id_parent_user = $this->users->getParentUserId();
        $social_id      = $this->usersFilds->getUserFild($id_parent_user, 'id_network');
        //$social_id=1; $id_parent_user = 500150;     $id_user = 92156962;
        if($social_id){
            $this->load->library('soc_network');
            $this->soc_network->createMsg($id_user, $id_parent_user, $txt, $tpc);
        } else {
            if(empty($id_topic))
                $id_topic        = $this->volunteer_topic_model->getTopicId($tpc);
            $this->volunteer_message_model->addMessage($id_topic, $txt, $for_admin);
            $this->load->library("mail");
            $idUserRecipient = $this->volunteer_topic_model->getTopicRecipient($id_topic);
            if(!$for_admin){
                $this->mail->addMessages($txt, $tpc, $idUserRecipient);
            } else
                $this->mail->addMessages4Admin($txt, $tpc, $idUserRecipient);
        }


        echo json_encode(["e" => ""]);
    }

    public function index(/* $page = 0 */){
//        $data = new stdClass();
        //   redirect(site_url('page/about-company/blog'), 'refresh');
        redirect(base_url('social/profile'), 'refresh');
        return;
//        $per_page = '10';
//        $data->pages = $this->base_model->pagination($per_page, $this->db->count_all('system_news'), 'account', 2);
//        $data->news = $this->base_model->get_system_news($per_page, (int) $page);
//
//        $this->content->user_view('home', $data, "Главная");
    }

    public function vote(){
        $this->load->model("votes_model", "votes");
        if(!$this->votes->isVoted()){
            $this->load->helper('form');
            $this->load->library('form_validation');
            if($this->form_validation->run('vote') == FALSE){
                echo _e("Не удалось");
                return;
            }
            $input = $_POST;
            foreach($input as &$val)
                $val   = htmlspecialchars($val);
            $this->votes->vote($input);
            echo _e('Голосование прошло успешно');
            return;
        }
        echo _e('Вы уже голосовали');
    }

    //page+new ajax
    public function my_balance(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;

//        $data->list = $this->accaunt->getBalans();
//        $data->money = $this->accaunt->getDataMoneyTransaction();


        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("balans", $data, _e('account/data1'));
        else {

            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/balans', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data1')]);
            } else {
                $this->load->view('user/accaunt/balans', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data1')]);
            }
        }
    }

    //page+new ajax
    public function my_rating(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("rating", $data, _e('account/data2'));
        else {
            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/rating', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data2')]);
            } else {
                $this->load->view('user/accaunt/rating', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data2')]);
            }
        }
    }

    //page
    public function invite(){
        $this->content->user_view("invite", [], _e('account/data3'));
    }

    public function statistic(){
        $this->content->user_view("statistic", getStatistic(), 'statistic');
    }

    public function stats123(){
        $this->content->user_view("stats", getStatisticPercent(), 'statistic');
    }

    public function visualdna(){
        $this->load->model("visualdna_model", "visualdna");
        $id_user = $this->accaunt->get_user_id();
        $res     = $this->visualdna->getStatus($id_user);
        if(!$res)
            $this->visualdna->setStart($id_user);
        $url     = "https://fs.visualdna.com/quiz/web_transfer?puid=$id_user";
        if($res)
            $url .= "#/feedback";
        redirect($url);
    }

    public function isVisulDNATestReady(){
        $this->load->model("visualdna_model", "visualdna");
        $status  = "off";
        $res     = false;
        $id_user = $this->accaunt->get_user_id();
        $garant  = intval($this->input->post('garant'));

        if($this->_vulnerabilityMoskalenkoTest() && $garant){
            if(getVisualDNA_garant()){
                $status = "on";
                $res    = $this->visualdna->getStatus($id_user);
            }
        } else if(getVisualDNA_standart()){
            $status = "on";
            $res    = $this->visualdna->getStatus($id_user);
        }
        echo json_encode(["status" => $status, "res" => $res]);
    }

    private function _vulnerabilityMoskalenkoTest(){
        $data    = new stdClass();
        $time    = intval($this->input->post('time', TRUE));
        $percent = floatval($this->input->post('percent', TRUE));  //   проверить  на  верность  данных
        $summa   = intval($this->input->post('summ', TRUE));
        $bonus   = intval($this->input->post('payment_account', TRUE));

        if($bonus != 2 && $bonus != 5)
            $bonus = 2;

        if($time == 0 || $percent == 0 || $summa == 0){
            accaunt_message($data, _e('account/data4'), 'error');
            redirect(site_url('account/my_credits'));
            return null;
        }

        $outPay       = credit_summ($percent, $summa, $time);
        $user_ratings = viewData()->accaunt_header;

        $vulnerabilityMoskalenko = ($user_ratings['all_advanced_loans_out_summ_by_bonus'][$bonus] + $outPay < $user_ratings['max_loan_available_by_bonus'][$bonus]) && ($user_ratings['all_advanced_loans_out_summ_by_bonus'][$bonus] + $outPay + $user_ratings['all_active_garant_loans_out_summ_by_bonus'][$bonus] <=
            $user_ratings['max_loan_available_by_bonus'][$bonus] * 3 );


        return $vulnerabilityMoskalenko;
    }

    //
    private function check_user_get_credit_garant($rating_by_bonus, $account_type, $summa, $set_account_bonus){
        $data = $this->data;

        if($account_type == 'card' && ( $rating_by_bonus['max_loan_available_by_bonus'][6] < $summa || $rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6] < $summa )){
            accaunt_message($data, _e('Вашего рейтинга недостаточно для совершения данной операции.4'), 'error');
        } else
        if($account_type != 'card' && $set_account_bonus == 2 && $rating_by_bonus['max_garant_loan_available_by_bonus'][$set_account_bonus] < $summa){
            accaunt_message($data, _e('Вашего рейтинга недостаточно для совершения данной операции.2'), 'error');
        } else if($account_type != 'card' && ($set_account_bonus == 5 || $set_account_bonus == 6) && $rating_by_bonus['max_garant_loan_available_by_bonus'][$set_account_bonus] < $summa){
            accaunt_message($data, _e('Вашего рейтинга недостаточно для совершения данной операции.3'), 'error');
        } else if($account_type != 'card' && $set_account_bonus == 2 && $rating_by_bonus['money_sum_add_funds_by_bonus'][$set_account_bonus] <= 0){
            accaunt_message($data, _e('Вашего рейтинга недостаточно для совершения данной операции.'), 'error'); //Что это за хрень?
        } else
            return TRUE;

        return FALSE;
    }

    //page + new ajax
    public function my_credits(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $this->load->model('documents_model', 'documents');
        $this->load->model('Card_model', 'card');

        $this->load->model('users_model', 'users');
        $this->load->model('credits_model', 'credits');
        $user_data = $this->users->getUserFullProfile();

        $user_id                    = get_current_user_id();
        $data->social_bonuses_today = $this->transactions->get_social_bonuses_today_count($user_id);

        // узнаем какой бонус установить
        $rating_by_bonus             = $this->accaunt->recalculateUserRating($user_id);
        $data->rating_by_bonus       = $rating_by_bonus;
        /* if (  $rating_by_bonus['max_loan_available_by_bonus'][5] > $rating_by_bonus['max_loan_available_by_bonus'][2] )
          $data->default_bonus_account = 5;
          else */
        $data->default_bonus_account = 2;


        $data->card_credit_params = $this->credits->get_cards_credits_limits($user_id);

        //var_dump( $data->active_card_invests);


        if(isset($_POST['submit']) && (empty($user_data) || $user_data->bot == 1)){
            accaunt_message($data, _e('account/data5'), 'error');
            $this->content->user_view("send_money", $data, _e('account/data6'));
            return;
        } elseif(isset($_POST['submit'])){


            $this->load->helper('form');
            $this->load->library('form_validation');


            $summa                = intval($this->input->post('summ'));
            $time                 = intval($this->input->post('time'));
            $percent              = floatval($this->input->post('percent'));  //   проверить  на  верность  данных
            $overdraft            = 0; //(int) $_POST['overdraft'];
            $direct               = 0; //(int) $this->input->post('direct');
            $garant               = isset($_POST['garant']) ? (int) $_POST['garant'] : 0;
            $payment_account_type = $this->input->post('account_type');
            $payment_account      = $this->input->post('payment_account');

            $form_validate = ($payment_account_type == 'pa' && $payment_account == 3) ? 'credit_partner' : 'credit';

            if($this->form_validation->run('credit') == FALSE){
                accaunt_message($data, validation_errors(), 'error');
                redirect('account/my_credits');
            }



            $this->load->model('Fincore_model', 'fincore');
            $result = $this->fincore->credit_request(get_current_user_id(), $summa, $time, $percent, $garant, $overdraft, $direct, $payment_account_type, $payment_account);
            accaunt_message($data, $result->message, $result->status);
            redirect(site_url('account/my_credits'));
        }

        $id_user       = $this->accaunt->get_user_id();
        $data->credits = $this->accaunt->get_credits();
        $data->id_user = $id_user;


        $data->own_accounts = $this->card->getOtherCards($user_id);
        usort($data->own_accounts, function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });




        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("credits", $data, _e('account/data10'));
        else {
            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/credits', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data10')]);
            } else {
                $this->load->view('user/accaunt/credits', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data10')]);
            }
        }
    }

    //page
    //page
    public function credits_enqueries(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;

        # Получаем тип
        $type = (!$this->input->post('type', TRUE)) ? $this->input->cookie('filter_credits', TRUE) : $this->input->post('type', TRUE);
        $type = (!in_array($type, ['t0', 't1', 't3', 't4'])) ? 't0' : $type;

        # Запись в COOKIE
        if($type != $this->input->cookie('type', TRUE))
            $this->input->set_cookie(['name' => 'filter_credits', 'value' => $type, 'expire' => '1200']);

        # Назначаем элемент
        $data->type = $type;

        # Формируем запрос WHERE для вставки в запрос
        $where = $this->enqueries_where($type);

        $data->credits         = $this->accaunt->get_credits([10, 0], $where[0], $where[1]);
        $data->rating_by_bonus = viewData()->accaunt_header;

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("credits_enqueries", $data, _e('account/data11'));
        else
            $this->load->view('user/accaunt/credits_enqueries', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data11')]);
    }

    //page
    public function invests_enqueries(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;

        $this->load->model('documents_model', 'documents');
        $user_id = $this->accaunt->get_user_id();

        # Получаем тип
        $type = (!$this->input->post('type', TRUE)) ? $this->input->cookie('filter_invests', TRUE) : $this->input->post('type', TRUE);
        $type = (!in_array($type, ['t0', 't1', 't2', 't3', 't4'])) ? 't0' : $type;

        # Запись в COOKIE
        if($type != $this->input->cookie('type', TRUE))
            $this->input->set_cookie(['name' => 'filter_invests', 'value' => $type, 'expire' => '1200']);

        # Назначаем элемент
        $data->type = $type;


        $this->load->model('security_model', 'security_model');
        $data->security = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit');

        # Формируем запрос WHERE для вставки в запрос
        $where         = $this->enqueries_where($type);
        $data->credits = $this->accaunt->get_invests([10, 0], $where[0], $where[1]);

        $data->another_doc = $this->documents->get_doc_file_name_by_user_id($user_id, 5);
        $data->another_doc = ( $data->another_doc === FALSE ) ? FALSE : TRUE;

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("invests_enqueries", $data, _e('account/data12'));
        else
            $this->load->view('user/accaunt/invests_enqueries', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data12')]);
    }

    private function export_default(){
        if(!empty($_POST['export_user_id'])){
            echo $_POST['export_user_id'];
            return false;
        }
        if(in_array($this->input->get('type', TRUE), [1, 2, 3, 4, 5, 6]))
            $type['int'] = $this->input->get('type', TRUE);
        else
            $type['int'] = 2;

        $type['name']       = [1 => 'B1', 2 => 'B2', 3 => 'B3', 4 => 'B4', 5 => 'B5', 6 => 'B6'];
        $type['human_name'] = [1 => 'BONUS', 2 => 'WTUSD2', 3 => 'P-CREDS', 4 => 'C-CREDS', 5 => 'WTUSD1', 6 => 'DEBIT'];

        $arhive = $this->input->get('arhive', FALSE);


        $this->load->library('phpexcel');
        $this->load->helper('date');


        # Проверка даты
        $date             = [];
        $date['array'][0] = explode('/', $this->input->get('date_1', TRUE));
        $date['array'][1] = explode('/', $this->input->get('date_2', TRUE));

        if(@count($date['array'][0]) != 3 || @!checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2]))
            $date['array'][0] = explode('/', "05/21/2013");
        if(@count($date['array'][1]) != 3 || @!checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2]))
            $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));

        $date['first'][0] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1];
        $date['last'][0]  = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1];

        $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1) : ($date['array'][0][1] - 1));
        $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1) : ($date['array'][1][1] + 1));

        $date['first'][1] = mktime(23, 59, 59, $date['array'][0][0], $date['array'][0][1], $date['array'][0][2]);
        $date['last'][1]  = mktime(00, 00, 00, $date['array'][1][0], $date['array'][1][1], $date['array'][1][2]);

        $xls = [
            0      => ['money' => 0],
            1      => ['money' => 0],
            'name' => "wt_".$this->user->id_user."_".$type['name'][$type['int']]."_".mdate("%Y_%m_%d", time()).".xls",
            'money' => 0,
            'status' => getTransactionStatuses(),
            'metod' => [ 'bank'         => _e('Банковский счет'), 'qiwi'         => _e('QIWI Кошелек'),
                'w1'           => 'Wallet One', 'mc'           => _e('Visa / Mastercard'),
                'wt'           => $GLOBALS["WHITELABEL_NAME"], 'lava'         => _e('Lava'),
                "out"          => _e('Вывод средств'), "paypal"       => _e('Paypal'),
                "arbitr"       => _e('Арбитраж'), "payeer"       => _e('Payeer'),
                "egopay"       => _e('Egopay'), "perfectmoney" => _e('Perfect Money'),
                "okpay"        => _e('OKPay'),
            ]
        ];


        $sheet = $this->phpexcel->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(11);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(16);
        $sheet->getColumnDimension('G')->setWidth(10);

        $sheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
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

        $i = 2;

        $sheet->setCellValue("A$i", _e("Дата"));
        $sheet->setCellValue("B$i", "ID");
        $sheet->setCellValue("C$i", _e("Тип"));
        $sheet->setCellValue("D$i", _e("Комментарий"));
        $sheet->setCellValue("E$i", _e("Статус"));
        $sheet->setCellValue("F$i", _e("Метод"));
        $sheet->setCellValue("G$i", _e("Сумма ($)"));

        $table = ('arhive' == $arhive) ? "archive_transactions" : "transactions";

        $query = $this->db->query('SELECT `id`, `status`, `summa`, `metod`, `type`, `note`, `bonus`, `date` FROM `'.$table.'` WHERE (`id_user` = "'.$this->user->id_user.'" AND `date` < "'.mdate("%Y.%m.%d %H:%i", $date['last'][1]).'" AND `bonus` IN ('.$type['int'].')) ORDER BY `id` DESC');

        foreach($query->result_array() as $result){
            $result['unix'] = explode('-', $result['date']);

            $result['unix'][2]    = explode(':', $result['unix'][2]);
            $result['unix'][2][0] = explode(' ', $result['unix'][2][0]);

            $result['unix'] = mktime($result['unix'][2][0][1], $result['unix'][2][1], $result['unix'][2][2], $result['unix'][1], $result['unix'][2][0][0], $result['unix'][0]);

            if($date['first'][1] < $result['unix']){

                $i++;

                $sheet->setCellValue("A$i", substr($result['date'], 0, 10));
                $sheet->setCellValue("B$i", $result['id']);
                $sheet->setCellValue("C$i", $type['human_name'][$result['bonus']]);
                $sheet->setCellValue("D$i", $result['note']);
                $sheet->setCellValue("E$i", ((isset($xls['status'][$result['status']])) ? _e($xls['status'][$result['status']]) : "ERR"));
                $sheet->setCellValue("F$i", ((isset($xls['metod'][$result['metod']])) ? $xls['metod'][$result['metod']] : "ERR"));
                $sheet->setCellValue("G$i", (($result['status'] == 3) ? "-" : NULL).round($result['summa'], 2));
            } else {

                if($result['status'] == 1)
                    $xls[0]['money'] = $xls[0]['money'] + round($result['summa'], 2);
                if($result['status'] == 3)
                    $xls[0]['money'] = $xls[0]['money'] - round($result['summa'], 2);
            }

            if($result['status'] == 1)
                $xls[1]['money'] = $xls[1]['money'] + round($result['summa'], 2);
            if($result['status'] == 3)
                $xls[1]['money'] = $xls[1]['money'] - round($result['summa'], 2);
        }

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue("A1", _e("Список ").(($type['int'] == 2) ? _e("платежных") : _e("бонусных"))._e(" транзакций пользователя ").$this->user->id_user." ".(($date['first'][0] == $date['last'][0]) ? _e("на ").$date['last'][0] : _e("c ").$date['first'][0]._e(" по: ").$date['last'][0]));

        $i++;

        $sheet->mergeCells("A$i:G$i");

        $sheet->getStyle("A$i")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle("A$i")->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$i")->getFill()->getStartColor()->setRGB('2980B9');

        $sheet->setCellValue("A$i", sprintf(_e("Состояние %s баланса"), (($type['int'] == 2) ? _e("платежного") : _e("бонусного"))));

        $i++;

        $sheet->mergeCells("A$i:F$i");

        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("G$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue("A$i", _e("Начальный баланс на ").$date['first'][0].": ");
        $sheet->setCellValue("G$i", round($xls[0]['money'], 2));

        $i++;

        $sheet->mergeCells("A$i:F$i");

        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("G$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue("A$i", sprintf(_e("Транзакции с %s по %s"), $date['first'][0], $date['last'][0]).": ");
        $sheet->setCellValue("G$i", round($xls[1]['money'] - $xls[0]['money'], 2));

        $i++;

        $sheet->mergeCells("A$i:F$i");

        $sheet->getStyle("A$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("G$i")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $sheet->setCellValue("A$i", _e("Остаток на: ").$date['last'][0].": ");
        $sheet->setCellValue("G$i", round($xls[1]['money'], 2));

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$xls['name'].'"');
        header('Cache-Control: max-age=0');
        $writer = new PHPExcel_Writer_Excel5($this->phpexcel);
        $writer->save('php://output');
    }

    public function export($type = 'default'){
        if($type == 'default'){
            $this->export_default();
        } elseif($type == 'order_archive'){
            $this->load->model('currency_exchange', 'currency_exchange');
            $this->currency_exchange->export_order_archive();
        }
    }

    private function enqueries_where($type = 't0'){
        $type = (!in_array($type, ['t0', 't1', 't2', 't3', 't4'])) ? 't0' : $type;

        # Формируем WHERE запрос
        if($type == 't0'){
            $where    = ['state !=' => 7];
            $where_in = FALSE;
        }

        if($type == 't1'){
            $where    = ['state =' => 2];
            $where_in = FALSE;
        }

        if($type == 't2'){
            $where    = ['state =' => 1];
            $where_in = FALSE;
        }

        if($type == 't3'){
            //$where = ['state =' => 2, 'out_time <' => date("Y-m-d", strtotime("-1 day"))];
            $where    = ['state =' => 2, 'out_time <=' => date("Y-m-d").' 23:59:59'];
            $where_in = FALSE;
        }

        if($type == 't4'){
            $where    = FALSE;
            $where_in = ['state', [5, 6]];
        }

        return [$where, $where_in];
    }

    public function next_invests_enqueries($offset = NULL){
        $data          = new stdClass();
        if(null == $offset)
            $offset        = 0;
        $where         = $this->enqueries_where($this->input->cookie('filter_invests', TRUE));
        $data->credits = $this->accaunt->get_invests([10, (int) $offset], $where[0], $where[1]);
        $this->load->view('user/accaunt/next_invests_enqueries', $data);
    }

    public function next_credits_enqueries($offset = NULL){
        $data          = new stdClass();
        if(null == $offset)
            $offset        = 0;
        $where         = $this->enqueries_where($this->input->cookie('filter_credits', TRUE));
        $data->credits = $this->accaunt->get_credits([10, (int) $offset], $where[0], $where[1]);
        $this->load->view('user/accaunt/next_credits_enqueries', $data);
    }

    //page
    public function archive_credits_enqueries(){
        $data                      = new stdClass();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "archive";
        viewData()->archive_page   = true;
        $data->notAjax             = $this->base_model->returnNotAjax();

        $data->credits = $this->accaunt->get_archive_credits([10, 0]);
        $this->content->user_view("credits_enqueries", $data, _e('account/data14'));
    }

    public function exchanges_list(){
        $data                      = new stdClass();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "exchanges_list";
        $data->notAjax             = $this->base_model->returnNotAjax();

        $data->exchanges_list = $this->db->get('exchanges_list')->result();
        $this->content->user_view("exchanges/list", $data, _e('Обменники'));
    }

    public function exchanges_index($url){
        $data                      = new stdClass();
        $data->exchange_item       = $this->db->get_where('exchanges_list', array('url' => $url))->row();
        if(empty($data->exchange_item))
            show_404();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "exchanges_list";
        $data->notAjax             = $this->base_model->returnNotAjax();

        $this->content->user_view("exchanges/index", $data);
    }

    public function exchanges_page($url){
        $data                      = new stdClass();
        $data->user                = $this->accaunt->getMainUser('id_user, phone, name, sername, email, skype');
        if(!in_array($url, array('terms', 'features', 'registration','buy-debit')))
            show_404();
        viewData()->page_name      = "exchanges-".$url;
        $data->page_item           = $this->base_model->get_page("exchanges-".$url);
        viewData()->secondary_menu = "exchanges_list";
        $data->notAjax             = $this->base_model->returnNotAjax();

        $this->content->user_view("exchanges/page-".$url, $data);
    }

    public function exchanges_registration(){
        $this->load->library('mail');

        $text = "

Имя: ".$this->input->post('holder_name')."<br>
Обменник: ".$this->input->post('title')."<br>
Сайт: ".$this->input->post('site')."<br>
Кошелек: ".$this->input->post('wallet')."<br>
Телефон: ".$this->input->post('phone_mobile')."<br>
Email: ".$this->input->post('email')."<br>
";

        $title = 'Регистарция нового обменника';
        $this->mail->send('merchant@webtransfer.com', $text, $title);
        $this->mail->send('register@wt-exchange.com', $text, $title);
        $this->mail->send('ravshan@webtransfer.com', $text, $title);
        redirect('http://wt-change.com/obmennikam-wt/');
    }

    //page
    public function archive_invests_enqueries(){
        $data                      = new stdClass();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "archive";
        viewData()->archive_page   = true;
        $data->notAjax             = $this->base_model->returnNotAjax();

//        $this->load->model('security_model', 'security_model');
//        $data->security = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit');
        $data->security = "code";

        $data->credits = $this->accaunt->get_archive_invests([10, 0]);
        $this->content->user_view("invests_enqueries", $data, _e('account/data15').".");
    }

    //page
    public function archive_transactions($page = 0){
        $data                      = new stdClass();
        $this->load->helper('date');
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "archive";
        viewData()->archive_page   = true;

        $id_user  = $this->accaunt->get_user_id();
        $per_page = "10";

        # Получение cookie
        $date           = ['array' => [false, false]];
        $type           = [false, false];
        $date['cookie'] = explode('|', $this->input->cookie('filter_transactions', TRUE));

        if(isset($date['cookie'][1])){
            # Проверка даты
            $date['array'][0] = explode('/', (!$this->input->post('date_1', TRUE)) ? $date['cookie'][1] : $this->input->post('date_1', TRUE));
            $date['array'][1] = explode('/', (!$this->input->post('date_2', TRUE)) ? $date['cookie'][2] : $this->input->post('date_2', TRUE));
        }

        if(@count($date['array'][0]) != 3 || !checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2]))
            $date['array'][0] = explode('/', "06/01/2014");
        if(@count($date['array'][1]) != 3 || !checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2]))
            $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));

        $date['first'][0] = $date['array'][0][0]."/".$date['array'][0][1]."/".$date['array'][0][2];
        $date['last'][0]  = $date['array'][1][0]."/".$date['array'][1][1]."/".$date['array'][1][2];

        $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1) : ($date['array'][0][1] - 1));
        $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1) : ($date['array'][1][1] + 1));

        $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 59:59:59";
        $date['last'][1]  = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";

        # Проверка типа
        if($this->input->post('type_1', TRUE) !== FALSE)
            $type[0] = TRUE;
        else if($this->input->post('type_2', TRUE) == FALSE && $date['cookie'][0] == 1)
            $type[0] = TRUE;
        else
            $type[0] = FALSE;

        if($this->input->post('type_2', TRUE) !== FALSE)
            $type[1] = TRUE;
        else if($this->input->post('type_1', TRUE) == FALSE && $date['cookie'][0] == 2)
            $type[1] = TRUE;
        else
            $type[1] = FALSE;

        if(($type[0] && $type[1]) || (!$type[0] && !$type[1]))
            $type = FALSE;
        else if($type[0])
            $type = 1;
        else
            $type = 2;


        # Запись в COOKIE
        if(!isset($date['cookie'][1]) || $date['cookie'][1] != $date['first'][0] || $date['cookie'][2] != $date['last'][0] || $date['cookie'][0] != $type)
            $this->input->set_cookie(['name' => 'filter_transactions', 'value' => $type.'|'.$date['first'][0].'|'.$date['last'][0], 'expire' => '1200']);


        # Назначаем элементы
        $data->type = $type;
        $data->date = $date;

        # Формируем запрос WHERE для вставки в запрос
        $where = ["bonus ".((!$type) ? "!=" : "=") => $type, "date >" => $date['first'][1], "date <" => $date['last'][1]];

        $data->pages = $this->base_model->pagination($per_page, $this->accaunt->arhive_getCountPays($where), 'account/archive_transactions', 4);

        $data->pays = $this->accaunt->arhive_getPays($per_page, (int) $page, $where);

        $data->arhive = true;

        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view('transaction', $data, _e('account/data16').$id_user);
        else
            $this->load->view('user/accaunt/transaction', get_object_vars($data) +
                get_object_vars(viewData()) +
                ["title_accaunt" => _e('account/data16').$id_user]);
    }

    public function next_archive_invests_enqueries($offset = NULL){
        $data               = new stdClass();
        if(null == $offset)
            $offset             = 0;
        $data->archive_page = true;
        $data->credits      = $this->accaunt->get_archive_invests([10, (int) $offset]);
        $this->load->view('user/accaunt/next_invests_enqueries', $data);
    }

    public function next_archive_credits_enqueries($offset = NULL){
        $data               = new stdClass();
        if(null == $offset)
            $offset             = 0;
        $data->archive_page = true;
        $data->credits      = $this->accaunt->get_archive_credits([10, (int) $offset]);
        $this->load->view('user/accaunt/next_credits_enqueries', $data);
    }

    protected function preCreateInvestExchange($user_ratings, $summa, $bonus){
        $data = new stdClass();

        if(strpos($_SERVER['HTTP_REFERER'], base_url()) === FALSE)
            return true;

        $payment_account_holds = //$user_ratings['all_advanced_invests_summ_by_bonus'][$bonus] +
            //$user_ratings['all_advanced_standart_invests_summ_by_bonus'][$bonus] +
            $user_ratings['total_arbitrage_by_bonus'][$bonus] +
            $summa;


        //    if ($payment_account_holds > $user_ratings['payment_account_by_bonus'][$bonus]) {
        //         accaunt_message($data, _e('account/data20'), 'error');
        //        return true;
        //     }
        //     if ($payment_account_holds > $user_ratings['payment_account_by_bonus'][$bonus] - $user_ratings['total_processing_by_bonus'][$bonus])
        //     {
        //            accaunt_message($data, _e('account/data21'), 'error');
        //             return true;
        //     }

        if($payment_account_holds > $user_ratings['max_garant_vklad_real_available_by_bonus'][$bonus]){
            accaunt_message($data, _e('account/data20'), 'error');
            return true;
        }




        return false;
    }

    //page + ajax
    public function my_invest(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('documents_model', 'documents');
        $this->load->model("transactions_model", "transactions");
        $this->load->model("credits_model", "credits");

        $user_ratings = viewData()->accaunt_header;


        $this->load->model('users_model', 'users');
        $this->load->model('security_model', 'security_model');
        $this->load->model('phone_model', 'phone');

        require_once APPPATH.'controllers/user/Security.php';
        $user_id       = $this->accaunt->get_user_id();
        $data->user_id = $user_id;

        $protection_type = Security::getProtectType($user_id);

        $user_data = $this->users->getUserFullProfile();

        // узнаем какой бонус установить
        /* if (   $user_ratings['availiable_garant_by_bonus'][5] > $user_ratings['availiable_garant_by_bonus'][2] )
          $data->default_bonus_account = 5;
          else */
        $data->default_bonus_account = 2;
        if($user_ratings['availiable_garant_by_bonus'][6] > $user_ratings['availiable_garant_by_bonus'][$data->default_bonus_account])
            $data->default_bonus_account = 6;


        $data->isUS2USorCA = $this->accaunt->isUS2USorCA($user_id);


        $data->calc             = $this->credits->get_own_sum_and_loans_and_arbitration($user_id, 0);
        $data->isUsaLimitedUser = $this->users->isUsaLimitedUser();

        //$user_ratings_15122015 = $this->accaunt->recalculateUserRating($user_id, null, null, '2015-12-14');
        //$user_ratings_25072015 = $this->accaunt->recalculateUserRating($user_id, null, null, '2015-12-14');
        $data->remove_creds_summ2 = 0;
        $data->remove_creds_summ6 = 0;


        //echo "{$user_ratings_15122015['total_garant_loans_by_bonus'][6]} >= {$user_ratings['active_cards_invests']}";
        //if (  $user_ratings_15122015['total_garant_loans_by_bonus'][6] >= $user_ratings['active_cards_invests'] &&  $user_ratings_15122015['total_garant_loans_by_bonus'][6] > 0){
        //  $data->remove_creds_summ6 = round($user_ratings['active_cards_invests'] + $user_ratings_15122015['total_arbitrage_by_bonus'][6] * ($user_ratings['active_cards_invests']/$user_ratings_15122015['total_garant_loans_by_bonus'][6]),2);
        //echo "<br>{$user_ratings['active_cards_invests']} + {$user_ratings_15122015['total_arbitrage_by_bonus'][6]} * ({$user_ratings['active_cards_invests']}/{$user_ratings_15122015['total_garant_loans_by_bonus'][6]}={$data->remove_creds_summ6}";
        //}

        $data->remove_creds_summ6 = round($user_ratings['garant_received'] * 4 - $user_ratings['my_investments_garant_by_bonus'][6], 2);
        $data->remove_creds_summ2 = $this->accaunt->get_remove_creds_summ2($user_id);

        $this->load->model('User_arbitration_scores_model', 'user_arbitration_scores');
        $data->arbitration_scores = $this->user_arbitration_scores->get_scores_summ($user_id, TRUE)*3;


        if(isset($_POST['submit']) && (empty($user_data) /* || $user_data->bot == 1 */)){
            accaunt_message($data, _e('account/data23'), 'error');
        } elseif(isset($_POST['submit'])){
            $this->load->helper('form');
            $this->load->library('form_validation');

            $summa                  = intval($this->input->post('summ'));
            $time                   = intval($this->input->post('time'));
            $percent                = floatval($this->input->post('percent'));  //   проверить  на  верность  данных
            $count                  = (int) $this->input->post("count");
            $overdraft              = 0; //(int) $_POST['overdraft'];
            $direct                 = 0; //(int) $this->input->post('direct');
            $garant                 = isset($_POST['garant']) ? (int) $_POST['garant'] : 0;
            $payment_account_type   = $this->input->post('account_type');
            $payment_account        = $this->input->post('payment_account');
            $is_ccreds_fee          = (isset($_POST['is_ccreds_fee']));
            $is_usd6creds_fee       = (isset($_POST['is_usd6creds_fee']));
            $is_usd2creds_fee       = (isset($_POST['is_usd2_creds_fee']));
            $accept_invest_arbitraj = (isset($_POST['accept_invest_arbitraj']));
            $arbitraj_invest_summ   = $this->input->post('arbitraj_invest_summ');
            $use_arbitr_summ        = (isset($_POST['use_arbitr']));
            $use_card_arbitr_summ        = $_POST['use_arbitr_b7'];


            if($is_usd6creds_fee && !$garant)
                $is_usd6creds_fee = FALSE;

            /* if ($is_usd6creds_fee && $summa*$count > $data->remove_creds_summ2  ){
              accaunt_message ($data, _e('Вы не можете получать отчисления WTDEBIT для этой суммы заявки'), 'error');
              redirect('account/my_invest');
              } */



            $form_validate = ($payment_account_type == 'pa' && $payment_account == 3) ? 'credit_partner' : 'credit';

            if($this->form_validation->run($form_validate) == FALSE){
                accaunt_message($data, validation_errors(), 'error');
                redirect('account/my_invest');
            }



            $this->load->model('Fincore_model', 'fincore');
            $result = $this->fincore->invest_request(get_current_user_id(), $summa, $time, $percent, $count, $garant, $overdraft, $direct, $payment_account_type, $payment_account, $is_ccreds_fee, $is_usd6creds_fee, $is_usd2creds_fee, $use_arbitr_summ, $use_card_arbitr_summ);
            // запустим арбитраж если нада
            if($result->status == 'success'){
                if($accept_invest_arbitraj && $payment_account_type == 'card'){
                    $this->load->model('card_model', 'card');
                    $res = $this->card->start_arbitrage($payment_account, $arbitraj_invest_summ);
                    $result->message.= " ".$res->message;
                }
            }
            accaunt_message($data, $result->message, $result->status);
            //redirect('account/my_invest');
        }


        $data->another_doc = $this->documents->get_doc_file_name_by_user_id($user_id, 5);
        $data->another_doc = ( $data->another_doc === FALSE ) ? FALSE : TRUE;

        $data->credits  = $this->accaunt->get_invests();
        $data->security = $protection_type;

        $data->default_bonus_account = 5;
        $max                         = 0;
        $rating_bonus                = $this->accaunt->recalculateUserRating($user_id);
        for($i = 1; $i <= 6; $i++){
            if($rating_bonus['net_own_funds_by_bonus'][$i] > $max){
                $max                         = $rating_bonus['net_own_funds_by_bonus'][$i];
                $data->default_bonus_account = $i;
            }
        }
        $data->rating_bonus = $rating_bonus;




        $data->wtcards = $this->card->getCards($user_id);

        $data->own_accounts = $this->card->getOtherCards($user_id);
        usort($data->own_accounts, function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });



        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("invests", $data, _e('account/data30'));
        else {
            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/invests', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data30')]);
            } else {
                $this->load->view('user/accaunt/invests', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data30')]);
            }
        }
    }

    public function alfa(){
        $this->content->user_view("card_payment", [], 'Card Payment');
    }

    protected function preSellCertificat($invest, $sell = true){
        $data = new stdClass();
        if(Base_model::CREDIT_TYPE_INVEST != $invest->type ||
            Base_model::CREDIT_STATUS_ACTIVE != $invest->state ||
            Base_model::CREDIT_GARANT_ON != $invest->garant){
            $m = ($sell) ? _e("Вы не можете продать сертификат") : _e("Вы не можете купить сертификат");
            accaunt_message($data, $m.(($invest->id) ? " СС$invest->id" : ""), 'error');
            return true;
        }

        if(empty($invest) || Base_model::CREDIT_CERTIFICAT_PAID == $invest->certificate){
            accaunt_message($data, _e('account/data32', 'error'));
            return true;
        }
        if($invest->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF){
            if(1 * 24 * 60 * 60 >= (time() - strtotime($invest->date_kontract))){
                accaunt_message($data, _e('account/data33'), 'error');
                return true;
            }
            if ( $invest->bonus != 7)
            if((0 < strtotime($invest->date_active) && 1 * 60 * 60 >= (time() - strtotime($invest->date_active))) /*||
               (0 < strtotime($invest->date_active) && Base_model::CREDIT_EXCHANGE_OFF == $invest->exchange)*/){
                accaunt_message($data, _e('account/data33'), 'error');
                return true;
            }
        }


        if($sell){
            $statictic = getStatistic();

            if(empty($statictic->today)){
                accaunt_message($data, _e('account/data34'));
                return true;
            }



            /* if (($statictic->today->avg_rate >= $invest->percent) && (0 > (time() - strtotime($invest->out_time." 00:00:00")))) {
              accaunt_message($data, _e('account/data35'), 'error');
              return true;
              } */

            if(($invest->percent <= $statictic->today->avg_rate ) && ((time() - strtotime($invest->out_time." 00:00:00") <= 0))){
                accaunt_message($data, _e('account/data35'), 'error');
                return true;
            }
        }
        return false;
    }

    public function sellCertificate($id = 0){
        $data    = new stdClass();
        $user_id = $this->accaunt->get_user_id();
        require_once APPPATH.'controllers/user/Security.php';
        if(Security::checkSecurity($user_id)){
            accaunt_message($data, _e("Не верный код авторизации"), 'error');
            redirect(site_url('account/transactions'));
        }
        $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша

        $invest = $this->accaunt->getUserCredit($id);

        if($this->preSellCertificat($invest)){
            redirect(site_url('account/transactions'));
        }

        $sellResult = $this->accaunt->sellCertificate($invest);
        if($sellResult !== TRUE){
            accaunt_message($data, $sellResult, 'error');
            redirect(site_url('account/my_invest'));
        }

        $this->load->model('credits_model', 'credits');
        $r = $this->credits->checkSellCertificate($id);
        if(false !== $r){
            accaunt_message($data, _e($r), 'error');
            redirect(site_url('account/my_invest'));
        }

        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, "Продан сертификат $id", 'common', $user_id);


        $replace = ['rate' => countSertificate(4), 'price' => countCertificate($invest->summa, $invest->date_kontract)];
        //$this->mail->admin_sender('sell-certificate-admin', $this->accaunt->get_user_id(), $replace, (int) $id);
        $this->mail->user_sender('sell-certificate-user', $user_id, $replace, (int) $id);

        $summ = round(countCertificate($invest->summa, $invest->date_kontract), 2);
        $this->load->model("transactions_model", "transactions");
        $b    = $this->base_model->getBonusTypeByCredit($invest->bonus);
        //  esli prodavat certificat ot bonus 7 - nujno chtob na debit padalo
        //if ($b==7) $b=6;
        if($invest->bonus != 7){
            $this->transactions->addPay($invest->id_user, $summ, Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID, $invest->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $b, "Продажа сертификата № СС$invest->id от ".date('d/m/Yг.'));

        }

        $this->postSellCertificat($invest, $summ);

        accaunt_message($data, _e('account/data37'));
        redirect(site_url('account/transactions'));
    }

    protected function postSellCertificat($invest, $summ){
        // Проверка условий овердрафта
        if(Base_model::CREDIT_OVERDRAFT_ON == $invest->overdraft)
            $this->base_model->overdraft($invest, $summ - $invest->summa);

        // Возврат бонусов системе
        if(Base_model::CREDIT_BONUS_ON == $invest->bonus)
            $this->base_model->returnBonuses($invest);
    }

    public function applications_credits(){ // список заявок
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "exchange";

        $data["url_credit"] = site_url('account/ajax_user/applications_table/'.Base_model::CREDIT_TYPE_INVEST)."/";

        $data["search_text"] = '';
        $data["view_all"]    = '';

        $user_id                      = $this->accaunt->get_user_id();
        $data['user_id']              = $user_id;
        $data['social_bonuses_today'] = $this->transactions->get_social_bonuses_today_count($user_id);
        $this->load->model('security_model', 'security_model');
        $this->load->model('accaunt_model', 'accaunt_model');

        $rating_by_bonus         = $this->accaunt->recalculateUserRating($user_id);
        $data['rating_by_bonus'] = $rating_by_bonus;

        if($rating_by_bonus['net_own_funds_by_bonus'][5] > $rating_by_bonus['net_own_funds_by_bonus'][2])
            $data['default_bonus_account'] = 5;
        else
            $data['default_bonus_account'] = 2;
        if($rating_by_bonus['net_own_funds_by_bonus'][6] > $rating_by_bonus['net_own_funds_by_bonus'][$data['default_bonus_account']])
            $data['default_bonus_account'] = 6;

        $data['security'] = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit', $user_id);


        $data['own_accounts'] = $this->card->getOtherCards($user_id);
        usort($data['own_accounts'], function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });


        //$data->curSocial = $this->_social;

        $this->content->user_view('applications_credits', $data, _e('account/data38'));
    }

    public function applications_invest(){ // список заявок
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "exchange";

        $data["url_invest"] = site_url('account/ajax_user/applications_table/'.Base_model::CREDIT_TYPE_CREDIT)."/";

        $data["search_text"] = '';
        $data["view_all"]    = '';

        $user_id = $this->accaunt->get_user_id();
        $this->load->model('security_model', 'security_model');
        $this->load->model('accaunt_model', 'accaunt_model');

        $rating_bonus                  = $this->accaunt->recalculateUserRating($user_id);
        $data['rating_by_bonus']       = $rating_bonus;
        if($rating_bonus['net_own_funds_by_bonus'][5] > $rating_bonus['net_own_funds_by_bonus'][2])
            $data['default_bonus_account'] = 5;
        else
            $data['default_bonus_account'] = 2;
        if($rating_bonus['net_own_funds_by_bonus'][6] > $rating_bonus['net_own_funds_by_bonus'][$data['default_bonus_account']])
            $data['default_bonus_account'] = 6;


        $data['security'] = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit', $user_id);
        $this->load->model('Card_model', 'card');
        $data->wtcards    = $this->card->getCards($user_id);


        $data['own_accounts'] = $this->card->getOtherCards($user_id);
        usort($data['own_accounts'], function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });


        //$data->curSocial = $this->_social;

        $this->content->user_view('applications_invest', $data, _e('account/data38'));
    }

    public function take_credit($id, $code_in = null){// дать займ //пришел кредит будет создана инвестиция
        $this->load->model('Fincore_model', 'fincore');
        $payment_account = $_POST['payment_account'];
        //($user_id, $id, $account_type, $account_id)
        $result          = $this->fincore->take_credit(get_current_user_id(), $id, '', $payment_account);
        echo json_encode(['message' => $result->message, 'status' => $result->status]);
    }

    public function take_invest($id, $credit_bonus = 0){//взять займ //пришла инвестиция будет создан кредит
        $this->load->model('Fincore_model', 'fincore');
        $payment_account = $_POST['payment_account'];
        $result          = $this->fincore->take_invest(get_current_user_id(), $id, '', $payment_account, $credit_bonus);
        echo json_encode(['message' => $result->message, 'status' => $result->status]);
    }

    public function confirm_payment($id){
        $this->base_model->redirectNotAjax();
        $this->accaunt->confirmPayment((int) $id);
    }

    public function confirm_payment_cancel($id){
        $this->base_model->redirectNotAjax();
        $this->accaunt->confirmPaymentCancel((int) $id);
    }

    public function confirm_return($id){
        $this->accaunt->confirmReturn((int) $id);
    }

    public function confirm_send($id){
        $this->accaunt->confirmSend((int) $id);
    }

    public function confirm_send_return($id){
        $card_id = $this->input->post('card_id');
        $this->load->model('Fincore_model', 'fincore');
        $res     = $this->fincore->return_loan((int) $id, get_current_user_id(), $card_id);
        accaunt_message($data, $res->message, $res->status);
    }

    public function report($id){
        $this->accaunt->reportSend((int) $id);
    }

    public function doc($id, $old = 0){
        if(intval($id) != 0 && in_array($id, config_item('documents_count'), true)){
            $select = 'img';
            if(intval($old) == 2)
                $select = 'img2';
            $pic    = $this->db->select($select)
                ->where(['num' => $id, 'id_user' => $this->user->id_user])
                ->from('documents')
                ->get()
                ->row($select);
            if(empty($pic)){
                return FALSE;
            }


            $file_header = 'Content-Type: image/jpg';
            $path        = "upload/doc/".$pic;
            if(file_exists($path) && pathinfo($path, PATHINFO_EXTENSION) == 'pdf'){
                $file_header = 'Content-Type: application/pdf';
            }
//            ob_end_clean();  //очищает буфер вывода и отключает буферизацию вывода
            header($file_header);
            echo $this->code->fileDecode("upload/doc/".$pic);
        }
    }

    public function delete_doc($id, $old = 0){
        if(intval($id) != 0 && in_array($id, config_item('documents_count'), true)){
            $this->image->place = "upload/doc/";
            $docs               = $this->db->select('state,  img, img2')->from('documents')->where(['num' => $id, 'id_user' => $this->user->id_user])->get()->row();
            if($old != 2){
                $img_name = $docs->img;

                if($docs->state != 2){
                    if(!empty($img_name)){
                        @unlink($this->image->place.$img_name);
                        $this->db->where(['num' => $id, 'id_user' => $this->user->id_user])->update('documents', ['state' => 0, 'img' => '']);
                        echo 1;
                    }
                } else {
                    echo 2;
                }
            } else {
                $img_name = $docs->img2;
                if(!empty($img_name)){
                    @unlink($this->image->place.$img_name);
                    $this->db->where(['num' => $id, 'id_user' => $this->user->id_user])->update('documents', ['img2' => '']);
                    echo 1;
                }
            }
        }
    }

    private function ajax_get_card_countries(){
        $pay_mode  = $this->input->post('payMode');
        // получаем страны
        $this->load->model('card_model', 'card');
        $countries = $this->card->getCountries($pay_mode);
        $resp      = [];
        if(!empty($countries)){
            foreach($countries as $country)
                $resp[$country['isoAlpha2Code']] = $country['name'];
        }

        $this->send_ajax_responce($resp);
    }

    private function ajax_get_card_banks(){
        $country                        = $this->input->post('country');
        $this->load->model('card_model', 'card');
        $resp                           = [];
        $banks                          = $this->card->getBanks($country);
        if(!empty($banks))
            foreach($banks as $bank)
                $resp[$bank['accountNumber']] = $bank['bankName'];

        $this->send_ajax_responce($resp);
    }

    private function ajax_get_card_services(){
        $country                         = $this->input->post('country');
        $this->load->model('card_model', 'card');
        $resp                            = [];
        $services                        = $this->card->getServices($country);
        if(!empty($services))
            foreach($services as $service)
                $resp[$service['serviceName']] = $service['serviceName'];

        $this->send_ajax_responce($resp);
    }

    private function ajax_get_card_balance(){
        $resp = ['balance' => '-'];

        $card_id  = $this->input->post('card_id');
        $useCache = $this->input->post('useCache');

        if(empty($card_id)){
            $this->send_ajax_responce($resp);
            return;
        }


        $this->load->model('card_model', 'card');
        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $this->send_ajax_responce($resp);
            return;
        }

        if($useCache){
            $balance = $card->last_balance;
        } else {
            $balance = number_format($this->card->getCardBalance($card), 2, '.', ' ');
            if($balance == null)
                $balance = '-';
        }
        $resp['balance'] = $balance;

        $this->send_ajax_responce($resp);
    }

    private function ajax_get_card_pin(){
        $card_id = $this->input->post('card_id');
        $this->load->model('card_model', 'card');

        $resp = ['pin' => '-'];
        if(empty(Security::getProtectType($this->accaunt->get_user_id())))
            Security::setProtectType('email');
        if(Security::checkSecurity($this->accaunt->get_user_id(), true)){
            $resp['pin'] = 'Неверный код';
            $this->send_ajax_responce($resp);
            return;
        }

        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $this->send_ajax_responce($resp);
            return;
        }

        $pin         = $this->card->getPin($card);
        $resp['pin'] = $pin;

        $this->send_ajax_responce($resp);
    }

    private function ajax_get_card_info(){
        $card_id = $this->input->post('card_id');
        $this->load->model('card_model', 'card');

        $resp = ['info' => '-'];
        if(empty(Security::getProtectType($this->accaunt->get_user_id())))
            Security::setProtectType('email');
        if(Security::checkSecurity($this->accaunt->get_user_id(), true)){
            $resp['info'] = 'Неверный код';
            $this->send_ajax_responce($resp);
            return;
        }

        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $this->send_ajax_responce($resp);
            return;
        }

        $details      = $this->card->getCardDetails($card);
        $resp['info'] = [
            'nameOnCard' => $details['cardDetail']['nameOnCard'],
            'expiryDate' => $details['cardDetail']['expiryDate'],
            'cvv'        => $details['cardDetail']['cvv'],
            'pan'        => preg_replace('/\d{4}/', "$0 ", $details['cardDetail']['pan'])
        ];

        $this->send_ajax_responce($resp);
    }

    private function ajax_set_card_unblocked(){
        $card_id = $this->input->post('card_id');
        $this->load->model('card_model', 'card');

//        $this->send_ajax_responce(['reason' => _e('Функция отключена')]);accaunt_message($data, _e('Функция отключена'), 'error');return;
        //Security::setProtectType('email');
        if(Security::checkSecurity($this->accaunt->get_user_id(), true)){
            $data = (object)[];
            accaunt_message($data, _e('Неверный код'), 'error');
            $this->send_ajax_responce(['reason' => _e('Неверный код')]);
            return;
        }


        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $this->send_ajax_responce(['reason' => _e('Ошибка данных')]);
            return;
        }

        $r = $this->card->setStatus($card, 'ACTIVE');
        if(!empty($r))
            $this->send_ajax_responce(['reason' => _e('Карта успешно заблокирована')]);
        else
            $this->send_ajax_responce(['reason' => _e('Ошибка блокировки карты')]);
    }

    private function ajax_set_card_blocked(){
        $card_id = $this->input->post('card_id');
        $this->load->model('card_model', 'card');

//        $this->send_ajax_responce(['reason' => _e('Функция отключена')]);accaunt_message($data, _e('Функция отключена'), 'error');return;

        if(Security::checkSecurity($this->accaunt->get_user_id(), true)){
            $data = (object)[];
            accaunt_message($data, _e('Неверный код'), 'error');
            $this->send_ajax_responce(['reason' => _e('Неверный код')]);
            return;
        }


        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $this->send_ajax_responce(['reason' => _e('Ошибка данных')]);
            return;
        }

        $r = $this->card->setStatus($card, 'SUSPENDED');
        if(!empty($r))
            $this->send_ajax_responce(['reason' => _e('Карта успешно заблокирована')]);
        else
            $this->send_ajax_responce(['reason' => _e('Ошибка блокировки карты')]);
    }

    //page
    //master card works with paypal
    public function payment(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;
        //	$this->output->enable_profiler(TRUE);
        $data->id_user        = $this->accaunt->get_user_id();
        $user                 = $this->accaunt->userFields('phone, name, sername, email');
        $data->phone          = $user->phone;
        $data->name           = $user->name;
        $data->f_name         = $user->sername;
        $data->email          = $user->email;

        $this->load->model("users_model", "users");
        $this->load->model("transactions_model", "transactions");
        $this->load->model('card_model', 'card');
        $data->wtcards = $this->card->getCards();

        $users                         = creatUserIdNameAndChildArray($this->users->getMyUsersAndChield());
        $users[$data->id_user]['item'] = _e("Мой кошелёк")." ($data->id_user)";


        //<? if (!empty($wtcards)) foreach( $wtcards as $card ){
        // }


        $data->partners    = $users;
        $data->paymenyBank = $this->transactions->countBankPayment();
        $data->notAjax     = $this->base_model->returnNotAjax();
        $data->bank_fee    = config_item('payment_bank_fee');


        $data->countries_fb = $this->card->getCountries('FastBankTransfer');
        //var_dump($data->countries_fb );
        $data->countries_rt = $this->card->getCountries('RealTimeTransfer');



        //usort($data->countries_fb, function($a, $b) { return strcmp($a['name'], $b['name']); });
        usort($data->countries_rt, function($a, $b){ return strcmp($a['name'], $b['name']); });


        $this->load->model('Countries_model', 'countries');
        $data->countries_fb = $this->countries->get_list();
        $data->countries_rt = $data->countries_fb;



        if($data->notAjax)
            $this->content->user_view('payment', $data, _e('account/data39'));
        else
            $this->load->view('user/accaunt/payment', get_object_vars($data) +
                get_object_vars(viewData()) +
                ["title_accaunt" => _e('account/data39')]);
    }

    public function payadmin(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;
        //	$this->output->enable_profiler(TRUE);
        $data->id_user        = $this->accaunt->get_user_id();
        $user                 = $this->accaunt->userFields('phone, name, sername, email');
        $data->phone          = $user->phone;
        $data->name           = $user->name;
        $data->f_name         = $user->sername;
        $data->email          = $user->email;

        $this->load->model("users_model", "users");
        $this->load->model("transactions_model", "transactions");
        $this->load->model('card_model', 'card');
        $data->wtcards = $this->card->getCards();

        $users                         = creatUserIdNameAndChildArray($this->users->getMyUsersAndChield());
        $users[$data->id_user]['item'] = _e("Мой кошелёк")." ($data->id_user)";


        //<? if (!empty($wtcards)) foreach( $wtcards as $card ){
        // }


        $data->partners    = $users;
        $data->paymenyBank = $this->transactions->countBankPayment();
        $data->notAjax     = $this->base_model->returnNotAjax();
        $data->bank_fee    = config_item('payment_bank_fee');


        $data->countries_fb = $this->card->getCountries('FastBankTransfer');
        //var_dump($data->countries_fb );
        $data->countries_rt = $this->card->getCountries('RealTimeTransfer');



        //usort($data->countries_fb, function($a, $b) { return strcmp($a['name'], $b['name']); });
        usort($data->countries_rt, function($a, $b){ return strcmp($a['name'], $b['name']); });


        $this->load->model('Countries_model', 'countries');
        $data->countries_fb = $this->countries->get_list();
        $data->countries_rt = $data->countries_fb;



        if($data->notAjax)
            $this->content->user_view('payadmin', $data, _e('account/data39'));
        else
            $this->load->view('user/accaunt/payadmin', get_object_vars($data) +
                get_object_vars(viewData()) +
                ["title_accaunt" => _e('account/data39')]);
    }

    //page
    //master card works with liqpay
    public function payment_old(){
        $data                 = new stdClass();
        viewData()->page_name = __FUNCTION__;
        //	$this->output->enable_profiler(TRUE);
        $data->phone          = $this->accaunt->user_field('phone');
        $data->id_user        = $this->accaunt->get_user_id();
        $data->name           = $this->accaunt->user_field('name');
        $data->f_name         = $this->accaunt->user_field('sername');
        $data->email          = $this->accaunt->user_field('email');
        $this->content->user_view('payment_old', $data, _e('account/data39'));
    }

    //page
    public function transactions($page = 0){

        $data                 = new stdClass();
        $this->load->helper('date');
        $this->load->helper('translit');
        $this->load->model('monitoring_model', 'monitoring');
        $this->load->model("transactions_model", "transactions");
        $this->load->model("Credits_model", "credits");
        $this->load->model("Card_model", "card");
        viewData()->page_name = __FUNCTION__;

        $user_ratings = viewData()->accaunt_header;


        $id_user  = $this->accaunt->get_user_id();
        $this->monitoring->log(null, 'Страница Кошелек', 'private', $id_user);
        $per_page = "10";
        $debug    = '';



        $this->load->model('Users_model','users');
        $data->ewallets = $this->users->get_ewallet_data($user_id);




        # Получение cookie
        $date           = ['array' => [false, false]];
        $date['cookie'] = explode('|', $this->input->cookie('filter_transactions', TRUE));

        if(isset($date['cookie'][1])){
            # Проверка даты
            $date['array'][0] = explode('/', (!$this->input->post('date_1', TRUE)) ? $date['cookie'][1] : $this->input->post('date_1', TRUE));
            $date['array'][1] = explode('/', (!$this->input->post('date_2', TRUE)) ? $date['cookie'][2] : $this->input->post('date_2', TRUE));
        }

        if(@count($date['array'][0]) != 3 || !checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2]))
            $date['array'][0] = explode('/', "06/01/2014");
        if(@count($date['array'][1]) != 3 || !checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2]))
            $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));

        $date['first'][0] = $date['array'][0][0]."/".$date['array'][0][1]."/".$date['array'][0][2];
        $date['last'][0]  = $date['array'][1][0]."/".$date['array'][1][1]."/".$date['array'][1][2];

        /*
          #Старый вариант
          $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1):($date['array'][0][1] - 1));
          $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1):($date['array'][1][1] + 1));

          $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 59:59:59";
          $date['last'][1] = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";
         */
        #получаем старую дату
        $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 23:59:59";
        $date['last'][1]  = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";

        #преобразуем
        $up_date   = strtotime($date['first'][1]);
        $down_date = strtotime($date['last'][1]);

        #берем только этот день
        $date['first'][1] = date('Y-m-d 23:59:59', $up_date - 24 * 3600);
        $date['last'][1]  = date('Y-m-d H:i:s', $down_date + 24 * 3600);


        # Проверка типа
        /*
          if ($this->input->post('type_1', TRUE) !== FALSE) $type[0] = TRUE;
          else if ($this->input->post('type_2', TRUE) == FALSE && $date['cookie'][0] == 1) $type[0] = TRUE;
          else $type[0] = FALSE;

          if ($this->input->post('type_2', TRUE) !== FALSE) $type[1] = TRUE;
          else if ($this->input->post('type_1', TRUE) == FALSE && $date['cookie'][0] == 2) $type[1] = TRUE;
          else $type[1] = FALSE;

          if (($type[0] && $type[1]) || (!$type[0] && !$type[1])) $type = FALSE;
          else if ($type[0]) $type = 1;
          else $type = 2;

         */
        $types         = [];
        $include_types = [];
        $cookie_types  = explode('-', $date['cookie'][0]);
        //if not POST
        if(empty($_POST)){
            if(is_array($cookie_types) && count($cookie_types) == 6){
                for($type = 1; $type <= 6; $type++){
                    $types[$type]    = $cookie_types[$type - 1];
                    if($cookie_types[$type - 1] == 1)
                        $include_types[] = $type;
                }
            } else {
                $types         = [1 => 1, 1, 1, 1, 1, 1];
                $include_types = [1, 2, 3, 4, 5, 6];
            }
        } else {
            for($type = 1; $type <= 6; $type++){
                if($this->input->post('type_'.$type, TRUE) !== NULL){
                    $types[$type]    = 1;
                    $include_types[] = $type;
                } else {
                    $types[$type] = 0;
                }
            }
        }

        if(count($include_types) == 0){
            $types         = [1 => 1, 1, 1, 1, 1, 1];
            $include_types = [1, 2, 3, 4, 5, 6];
        }


        # Запись в COOKIE
        if(!isset($date['cookie'][1]) || $date['cookie'][1] != $date['first'][0] || $date['cookie'][2] != $date['last'][0] || $date['cookie'][0] != implode('-', $types)){
            $this->input->set_cookie(['name'   => 'filter_transactions',
                'value'  => implode('-', $types).'|'.$date['first'][0].'|'.$date['last'][0],
                'expire' => 1200,
                'domain' => '',
                'path'   => '/',
                'prefix' => ''
            ]);
            //echo 'set cookie: '. implode('-',$types).'|'.$date['first'][0].'|'.$date['last'][0];
        }

        # Назначаем элементы
        $data->types = $types;
        $data->date  = $date;

        $data->pages_payout = $this->base_model->pagination($per_page, $this->accaunt->getCountPaysOut(), 'account/transactions', 4);
        $data->payout       = $this->accaunt->getPaysOut($per_page, (int) $page);

        $data->pages_payment = $this->base_model->pagination($per_page, $this->accaunt->getCountPayment(), 'account/transactions', 4);

        $data->payment = $this->accaunt->getPayment($per_page, (int) $page);

        # Формируем запрос WHERE для вставки в запрос
        $where = [ "bonus <" => 7,
            'bonus in ('.implode(',', $include_types).')',
            "date >"  => $date['first'][1],
            "date <"  => $date['last'][1]];
//        $where = array("bonus ".((!$type) ? "!=" : "=") => $type, "date <" => $date['first'][1], "date >" => $date['last'][1]);

        $data->pages = $this->base_model->pagination($per_page, $this->accaunt->getCountPays($where), 'account/transactions', 4);

        $data->pays = $this->accaunt->getPays($per_page, (int) $page, $where);

        $this->load->model('Card_model', 'card');
        $data->othercards = $this->card->getOtherCards($id_user);
        usort($data->othercards, function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });
        $this->load->model('Countries_model', 'countries');
        $data->countries = $this->countries->get_list();


        $rate15102015 = $this->accaunt->recalculateUserRating($id_user, '2015-08-30');
        $wallet       = $user_ratings['payment_account_by_bonus'][5];
        $balans       = $user_ratings['money_sum_add_funds_by_bonus'][5] + $rate15102015['money_sum_transfer_from_users_by_bonus'][5]
            //- $user_ratings['all_active_garant_loans_out_summ_by_bonus'][5]
            - $user_ratings['money_sum_withdrawal_by_bonus'][5] - $user_ratings['money_sum_transfer_to_users_by_bonus'][5]
            //- $user_ratings['p2p_money_sum_transfer_to_users_by_bonus'][5]
            //- $user_ratings['money_sum_transfer_to_merchant_by_bonus'][5]
            - $user_ratings['transfered_summ_from_bonus_5_to_2'];
        $balans       = max(0, $balans);
        //echo $wallet.' - '.$balans;

        $data->conversion         = ( $wallet > 0 && $balans > 0);
        




        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view('transaction', $data, _e('account/data16').$id_user);
        else
            $this->load->view('user/accaunt/transaction', get_object_vars($data) +
                get_object_vars(viewData()) +
                ["title_accaunt" => _e('account/data16').$id_user]);
    }

    public function pay(){
        $data        = new stdClass();
        $this->load->library('form_validation');
        $this->load->model('card_model', 'card');
        $metod       = $this->input->post('metod');
        $id_user     = $this->accaunt->get_user_id();
        $user_id_pay = $this->input->post('user_id');
        if(empty($user_id_pay)){
            echo json_encode(['error' => _e('Не верный № получателя.')]);
            return;
        }


        // если выбрали карту
        $card_id = NULL;
        if(substr($user_id_pay, 0, 4) == 'card'){
            $card_id = (int) substr($user_id_pay, 5);
            $card    = $this->card->getUserCard($card_id);
            if(empty($card)){
                echo json_encode(['error' => _e('Не такой карты!')]);
                return;
            }

            $this->load->model('Card_transactions_model', 'card_transactions');
            $transes = $this->card_transactions->get_transactions_by_period($card_id, Card_transactions_model::METHOD_LOAD, Card_transactions_model::STATUS_OK, date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'));
            if(!empty($transes) && count($transes) >= 2){
                echo json_encode(['error' => _e('Доступно не более 2х пополнений  в день')]);
                return;
            }

            $user_id_pay = (int) $card->user_id;
        }

        $all = $this->input->post('all');
        if('false' === $all){
            $this->load->model("users_model", "users");
            $partners   = creatUserIdAndChildArray($this->users->getMyUsersAndChield());
            $partners[] = (int) $id_user;
            if(!in_array((int) $user_id_pay, $partners, true)){
                echo json_encode(['error' => _e('Не верно введен пользователь получатель.')]);
                return;
            }
        } else {
            $this->load->model('users_model', 'users');
            if(!$this->users->isUserExists($user_id_pay)){
                echo json_encode(['error' => _e('Такого пользователя не существует!')]);
                return;
            }
        }
        $note = 'Заявка на пополнение кошелька. ';
        if($id_user != $user_id_pay){
            $note = "Пополнение от $id_user ";
            if($this->accaunt->isUserUSorCA($user_id_pay) || $this->accaunt->isUserUSorCA()){
                echo json_encode(['error' => _e('Данная операция для участников US/Canada запрещена.')]);
                return;
            }
        }
        $paymentSys   = getPaymentSys();
        $paymentSys_f = array_flip($paymentSys);
        if(!$this->form_validation->run('summa') || !in_array($metod, $paymentSys, true)){
            echo json_encode(['error' => _e('Ошибка на сервере.')]);
            return;
        }
        $summa = price_format_save($this->input->post('summa'));

        $this->load->model("transactions_model", "transactions");
        if(('bank_norvik' == $metod) && (10000 < $summa || 12 <= $this->transactions->countBankPayment())){
            echo json_encode(['error' => _e('Сумма не может привышать $10,000 или вы не имеете возможность сейчас ввести этим методом')]);
            return;
        }
        if(('bank' == $metod) && (100000 < $summa || 2 <= $this->transactions->countBankPayment())){
            echo json_encode(['error' => _e('Сумма не может привышать $100 000 или вы не имеете возможность сейчас ввести этим методом')]);
            return;
        }
        if(('bank_norvik' == $metod)){
            $note = 'Оплата за услуги скоринга ';
        }

        /*
          #проверка лимитов пополнения
          $error_text = $this->transactions->checkTopUpLimits( $user_id_pay, $summa );
          if( TRUE !== $error_text )
          {
          echo json_encode( $error_text );
          return;
          }
         */

        $change_summ = $summa;
        if($card_id == NULL){
            $change_summ = round($summa + $summa * (config_item('account_payin_comission') / 100), 2);
            $id          = $this->transactions->addPay($user_id_pay, $change_summ, $paymentSys_f[$metod], $id_user, $metod, Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 6, $note);
        } else {
            $note = 'Заявка на пополнение карты. ';

            $comissions  = config_item('card_payin_comission');
            $comission   = 0;
            if(isset($comissions[$paymentSys_f[$metod]]))
                $comission   = $comissions[$paymentSys_f[$metod]];
            $change_summ = round($summa + $summa * ($comission / 100), 2);


            $id = $this->transactions->addPay($user_id_pay, $change_summ, $paymentSys_f[$metod], $card_id, $metod, Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 7, $note);
        }
        accaunt_message($data, _e('account/data9'), 'good');


        if('wtcard' == $metod){
            $this->load->model('card_model', 'card');
            $card_id = $this->input->post('card_id');
            if(empty($card_id)){
                echo json_encode(['error' => _e('Не указана карта')]);
                return;
            }
            $response = $this->card->purchaseMoney(
                ['id'      => $id,
                'card_id' => $card_id,
                'user_id' => $id_user,
                'summa'   => $change_summ,
                'desc'    => $this->input->post('description')
                ], Card_transactions_model::CARD_PAY_WT_ACCOUNT, $id);
            if(false !== $response){
                $this->db->update('transactions', array('summa' => $summa, 'status' => Base_model::TRANSACTION_STATUS_RECEIVED, 'note' => $note.'  Транзакция № '.$response), array('id' => $id));
                $transaction_item = $this->db->get_where('transactions', array('id' => $id))->row();
                $this->transactions->payPaymentBonus($transaction_item);
            }
            echo json_encode(['id' => $id, 'change_summ' => $change_summ]);
            return;
        }

        if('mc' == $metod){
            include(APPPATH.'libraries/Liqpay.php');
            $liqpay = new LiqPay('i2194089800', 'PzqlvnNxUVBxBrUc4Jpzm51n6oMxUWxwiJ7Cc3c6N');
            $html   = $liqpay->cnb_signature([
                'amount'      => $summa,
                'currency'    => 'USD',
                'description' => $this->input->post('description'),
                'order_id'    => $id,
                'result_url'  => $this->input->post('result_url'),
                'server_url'  => $this->input->post('server_url'),
                'type'        => 'buy'
            ]);

            echo json_encode(['id' => $id, 'change_summ' => $change_summ, 'sign' => $html]);
            return;
        }
        if('payeer' == $metod){
            $m_shop    = '24650396';
            $m_orderid = $id;
            $m_amount  = number_format($change_summ, 2, '.', '');
            $m_curr    = 'USD';
            $m_desc    = $this->input->post('description');
            $m_key     = getPayeerSecret();

            $arHash = [
                $m_shop,
                $m_orderid,
                $m_amount,
                $m_curr,
                $m_desc,
                $m_key
            ];
            $sign   = strtoupper(hash('sha256', implode(':', $arHash)));

            echo json_encode(['id' => $id, 'change_summ' => $change_summ, 'sign' => $sign]);
            return;
        }
        if('egopay' == $metod){
            $pass    = getEgopayPassword();
            $amount  = $this->input->post('summa');
            $curency = 'USD';
            $cf_1    = $id;

            $arHash = [
                $pass,
                $amount,
                $cf_1,
                $curency,
            ];
            $sign   = hash('sha256', implode('|', $arHash));

            echo json_encode(['id' => $id, 'change_summ' => $change_summ, 'sign' => $sign]);
            return;
        }
        echo json_encode(['id' => $id, 'change_summ' => $change_summ]);
    }

    public function pay_to_card(){
        $method  = $this->input->post('method');
        $card_id = $this->input->post('card');
        $bank    = $this->input->post('bank');
        $country = $this->input->post('country');
        $summa   = price_format_save($this->input->post('summa'));
        $id_user = $this->accaunt->get_user_id();

        $result = $this->card->pay($id_user, $method, $card_id, $bank, $country, $summa);
        echo json_encode($result);
    }

//    private function createInvoice($id, $id_user, $summa) {
//        $html = $this->db->get_where('shablon', array('id_shablon' => 21))->row('sh_content');
//        $html = $this->mail->user_parcer($html, $id_user, array(
//            'summa' => number_format($summa, 2, ".", " "),
//            'id_check' => $id,
//            'transaction_date' => date("d/m/Y"),
//            'summa_add' => number_format(($summa + 7), 2, ".", " ")
//            )
//        );
//        if (!empty($html)) {
//            $this->code->createPdf($html, 'checks', "check-{$id}.pdf");
//            return $id;
//        } else return false;
//    }
    //page + new ajax
    public function payout(){
        $data                 = new stdClass();
        $data->notAjax        = $this->base_model->returnNotAjax();
        viewData()->page_name = __FUNCTION__;
        $this->load->model('users_model', 'users');
        $this->load->model('payment_data_model', 'payment_data');
        $this->load->model('phone_model', 'phone');
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('accaunt_model', 'accaunt');

        $data->exchanges_list = $this->db->get('exchanges_list')->result();

        $user_id = $this->accaunt->get_user_id();

        // пременые для ограничений
        $data->need_doc      = false;
        $data->limit         = 0;
        $data->incame        = 0;
        $data->little_incame = false;

        $data->isFacebookAvailable = $this->accaunt->isSocialAvailable($user_id, ['facebook']);
        $data->isVKAvailable       = $this->accaunt->isSocialAvailable($user_id, ['vkontakte']);

        if(Users_model::DOC_REQUEST_ENABLE == $this->users->is_doc_request($user_id)){
            $data->need_doc = true;
            if($data->notAjax)
                $this->content->user_view("payout", $data, _e('account/data42'));
            else
                $this->load->view('user/accaunt/payout', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data42')]);
            return;
        }
        $data_incame     = $this->users->getUserPureIncome($user_id);
        $rating_by_bonus = viewData()->accaunt_header;
        $incame          = viewData()->accaunt_header['sum_partner_reword'] + $data_incame[0];
        $payout_limit    = $this->users->get_payout_limit($user_id);


        $data->loan = $this->input->get_post('loan');

        // поработаем с выводом займа

        if(!empty($data->loan)){
            $data->loan = $this->accaunt->getUserCredit((int) $data->loan);
            if(empty($data->loan))
                redirect('account/payout');
            if(!($data->loan->state == 2 && $data->loan->arbitration == Base_model::CREDIT_ARBITRATION_OFF && $data->loan->bonus == 6 && $data->loan->garant == 1 && $data->loan->type == 1 &&
                viewData()->accaunt_header['payout_limit_by_bonus'][6] > $data->loan->out_summ && $data->loan->out_time != date('Y-m-d') )){
                redirect('account/payout');
            }
            if(!empty($this->transactions->get_processing_loan_transactions($user_id, $data->loan->id))){
                accaunt_message($data, _e('Вы уже сделали заявку на вывод займа ').$data->loan->id, 'error');
                redirect('account/payout');
            }
        }


        /* $rating_bonus[2]['net_own_funds'] = 552;
          $rating_bonus[2]['payout_limit'] = 552;
          $rating_bonus[5]['net_own_funds'] = 120;
          $rating_bonus[5]['payout_limit'] = 120; */




        $data->rating_bonus       = $rating_by_bonus;
        $data->withdrawal_limit_2 = round($rating_by_bonus['payout_limit_by_bonus'][2], 2);
        $data->withdrawal_limit_3 = round(max(0, min(($rating_by_bonus['bonus_earned_by_bonus'][3] + $rating_by_bonus['pcreds_inout_after_0112'] - $rating_by_bonus['pcreds_in_payout_process_after_0112']), $rating_by_bonus['payment_account_by_bonus'][3])), 2);


        $data->withdrawal_limit_5 = round($rating_by_bonus['payout_limit_by_bonus'][5], 2);
        $data->withdrawal_limit_6 = round($rating_by_bonus['payout_limit_by_bonus'][6], 2);
        /* $data->withdrawal_limit_6 = min($data->withdrawal_limit_6, round($rating_by_bonus['money_sum_add_funds_by_bonus'][6]+ $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6],2)); */

        if($data->withdrawal_limit_5 > $data->withdrawal_limit_2)
            $data->default_bonus_account = 5;
        else
            if($data->withdrawal_limit_6 > $data->withdrawal_limit_2)
                $data->default_bonus_account = 6;
            else
                $data->default_bonus_account = 2;

        //$data->credits_b6 = $this->credits->get_active_loans($user_id, [6], 'summa < '.$data->withdrawal_limit_6);
        $data->credits_b6 = $this->credits->get_active_loans($user_id, [6], 'out_summ < '.$rating_by_bonus['payout_limit_by_bonus'][6]);
        $data->limit_b6   = max(0, min($data->withdrawal_limit_6, round($rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_sum_add_card_by_bonus'][6] * 0.5 - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6] -
                    $rating_by_bonus['money_sum_transfer_to_users_by_bonus'][6] - $rating_by_bonus['money_sum_transfer_to_merchant_by_bonus'][6]
                    , 2)));
        if(in_array(get_current_user_id(), config_item('payout.use_payout_limit_for_users')))
            $data->limit_b6   = max(0, $data->withdrawal_limit_6);

        $data->bank_inout_by_bonus_5  = $this->transactions->getAllInMoneyOfUser($user_id, 5, ['bank', 'bank_raiffaisen', 'bank_norvik']) - $rating['money_sum_withdrawal_by_bonus'][5];
        $data->all_moneyin_by_bonus_2 = $this->transactions->getAllInMoneyOfUser($user_id, 2);
        $data->all_moneyin_by_bonus_6 = $this->transactions->getAllInMoneyOfUser($user_id, 6);
        $data->real_payout_limit_2    = $data->all_moneyin_by_bonus_2 * 1.5 - $rating_by_bonus['money_sum_withdrawal_by_bonus'][2] - $rating_by_bonus['p2p_money_sum_transfer_to_users_by_bonus'][2] -
            $rating_by_bonus['outcome_merchant_send_by_bonus'][2] + $rating_by_bonus['sum_partner_reword_by_bonus'][2] + $rating_by_bonus['bonus_earned_by_bonus'][2] + $rating_by_bonus['arbitr_inout_by_bonus'][2] * 1.5;

        $withdrawal_limit       = $rating_by_bonus['payout_limit_by_bonus'][$data->default_bonus_account];
        $data->withdrawal_limit = $withdrawal_limit;

        $data->autopayouts_limits = [2 => 0,
            5 => 0,
            6 => round($rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6] - $rating_by_bonus['active_cards_credits_outsumm'], 2)
        ];







        if(Users_model::PAYOUT_LIMMIT_OFF != $payout_limit && $incame < $payout_limit){
            $data->limit         = $payout_limit;
            $data->incame        = $incame;
            $data->little_incame = true;
            if($data->notAjax)
                $this->content->user_view("payout", $data, _e('account/data42'));
            else
                $this->load->view('user/accaunt/payout', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data42')]);
            return;
        }

        $this->load->model('security_model', 'security_model');

        $this->load->model('card_model', 'card');

        require_once APPPATH.'controllers/user/Security.php';


        $payout_systems           = payout_systems();
        $wtcards                  = $this->card->getCards();
        viewData()->user->wtcards = $wtcards;

        $wire_bank_systems_obj = new stdClass();
        $this->payment_data->get_fields_values_for_profile($user_id, $wire_bank_systems_obj, 'wire', 0);

        $wire_bank_systems = (array) $wire_bank_systems_obj;

        viewData()->user->wire_bank = $wire_bank_systems;

        $payuot_systems_psnt = payuot_systems_psnt();
        foreach($payout_systems as $key => $value)
            if(!isset($payuot_systems_psnt[$value]))
                unset($payout_systems[$key]);



        //- Esli u polzovatelya est popolnenie cherez bank,bank_raiffaisen, bank_norvik ili wtcard,
        //i summa vivoda menshe chem ostatok, to stavitsya 1.5%, v kraynem sluchai 8%.
        $data->total_income_money_banks = $this->transactions->getAllInMoneyOfUser($user_id, NULL, ['bank', 'bank_raiffaisen', 'bank_norvik', 'wtcard']);
        $data->total_income_money_banks = empty($data->total_income_money_banks) ? 0 : $data->total_income_money_banks;
        /* foreach( $payuot_systems_psnt as $k=>&$psp ){
          if ( $k == 'wtcard')
          if ( $total_income_money_banks )
          echo $k; var_dump($psp);
          } */

        $payout_fields = array_flip($payout_systems);

        $this->load->model('monitoring_model', 'monitoring');
        $this->load->model('users_model', 'users');


        if(!isset($this->accaunt))
            $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('address_model', 'address');


        $protection_type = Security::getProtectType($user_id);

        $userProfile = $this->users->getUserFullProfile();
        $userAddress = $this->address->getAdressByUserId();

        if(empty($userProfile))
            $userProfile = [];
        if(empty($userAddress))
            $userAddress = [];
        $user_data   = (object) array_merge((array) $userProfile, (array) $userAddress);

        $this->monitoring->log(null, "Страница Вывод", 'common', $this->users->getCurrUserId());

        if(isset($_POST['submit']) && (empty($user_data) || $user_data->bot == 1)){
            $this->monitoring->log(null, "Попытка создания заявки на Вывод БОТом", 'common', $this->accaunt->get_user_id());
            accaunt_message($data, _e('account/data24'), 'error');
        } else
        if(isset($_POST['submit']) && $this->input->post('payout_system') == 'bank_cc' && (empty($user_data->town) || empty($user_data->street) || empty($user_data->house) )
        ){
            accaunt_message($data, _e('account/data43'), 'error');
        } else if(isset($_POST['submit'])){
            $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша
            $data->code    = $this->input->post('code');
            $data->post_vk = $this->input->post('post_vk');
            $data->post_fb = $this->input->post('post_fb');

            //security protection
            if(Security::checkSecurity($user_id)){

            } else {
                $this->load->model('phone_model', 'phone');


                $this->monitoring->log(null, "Создана заявка на Вывод", 'common', $this->accaunt->get_user_id());

                $data->amount = $this->input->post('amount');

                $data->payout_system = $this->input->post('payout_system');

                $rating            = viewData()->accaunt_header;
                // узнаем какой бонус установить
                $set_account_bonus = $this->data->default_bonus_account;
                $payment_account   = $this->input->post('payment_account');

                // сдулеам сумма как в заявке если это вывод займа
                if($payment_account == 'loan_out')
                    $data->amount = $data->loan->summa;

                // поставим бонус как в заявке
                if(!empty($data->loan))
                    $payment_account = $data->loan->bonus;


                if($payment_account > 0){
                    $set_account_bonus = $payment_account;
                } else {
                    accaunt_message($data, _e('Вы не выбрали счет'), 'error');
                    redirect('account/payout');
                }

//
//                    } else if ( ($summa + $psnts) > $rating_by_bonus['payout_limit_by_bonus'][$set_account_bonus]+$rating_by_bonus['income_merchant_send_by_bonus'][$set_account_bonus]) {
                // $withdrawal_limit = $rating_bonus[$set_account_bonus]['net_own_funds'] + $set_account_bonus['income_merchant_send'];
                //$withdrawal_limit = $rating_by_bonus['net_own_funds_by_bonus'][$set_account_bonus] + $rating_by_bonus['income_merchant_send_by_bonus'][$set_account_bonus] +  $rating_by_bonus['bonus_earned_by_bonus'][$set_account_bonus];
                $withdrawal_limit_by_bonus = $rating_by_bonus['withdrawal_limit_by_bonus'][$set_account_bonus];
                // для вывода займов используем payout_limit_by_bonus
                if(!empty($data->loan))
                    $withdrawal_limit_by_bonus = $rating_by_bonus['payout_limit_by_bonus'][$set_account_bonus];


                if($set_account_bonus == 3)
                    $withdrawal_limit_by_bonus = $data->withdrawal_limit_3;

                // смотрим есть ли ид-кошельков
                $sub_id_present_pos = strpos($data->payout_system, '[');
                if($sub_id_present !== FALSE){
                    if(preg_match_all("/(.*)\[(.*)\]/", $data->payout_system, $matches)){
                        if(count($matches) > 2){
                            $data->payout_system        = $matches[1][0];
                            $_POST['payout_system']     = $data->payout_system;
                            $data->payout_system_sub_id = $matches[2][0];
                        } else {
                            die();
                        }
                    }
                }

                $data->other_systems = (null === $this->input->post('other_pay_systems')) ? false : true;
                $data->payout_psnt   = countPesnt($data->amount, $data->payout_system);
                $data->payout_bonus  = countBonusPesnt($data->amount, $data->payout_system);
                $this->load->library('form_validation');

                // посчитаем процент для карт
                if($set_account_bonus == 2 && $data->payout_system == 'wtcard'){
                    if($data->amount > $data->total_income_money_banks){

                        $cur               = $payuot_systems_psnt[$data->payout_system];
                        $data->payout_psnt = $data->amount * $cur["psnt_max"] / 100;
                        if($data->payout_psnt < $cur["min"])
                            $data->payout_psnt = $cur["min"];
                        $data->payout_psnt = $data->payout_psnt + $cur["add"];
                    }
                }




                $summa = price_format_save($data->amount);
                $summa = round($summa, 2);
                $psnts = price_format_save($data->payout_psnt);
                $psnts = round($psnts, 2);

                $user_phone     = $this->phone->getPhone($this->accaunt->get_user_id());
                $payout_account = viewData()->user->{$data->payout_system};

                $id_user = $this->accaunt->get_user_id();

                if($set_account_bonus == 6){
                    //- Maximalniy vivod cheloveku $1,000 v den'
                    $full_summ_today = $this->db->select('sum(summa) as sm')->where_in('status', [3, 4, 9])->where([
                            'id_user' => $this->accaunt->get_user_id(),
                            'metod'   => 'out',
                            'date >=' => date('Y-m-d 00:00:00')])->get('transactions')->row('sm');
                    $full_summ_today = (empty($full_summ_today) ? 0 : $full_summ_today);
                    if($full_summ_today > 1000){
                        accaunt_message($data, _e('Максимальный вывод средств -  $1,000 в день')."($full_summ_today)", 'error');
                        redirect('account/payout');
                    }
                }


                if($set_account_bonus == 5 && $data->payout_system != 'wire_bank'){
                    accaunt_message($data, _e('Вывод средств WTUSD1 доступен только через P2P и обменники'), 'error');
                } else if($set_account_bonus == 5 && $data->payout_system == 'wire_bank' && $data->bank_inout_by_bonus_5 < $summa){
                    accaunt_message($data, _e('Вывод средств WTUSD1 через WireBank доступен только если сумма ваших пополнений через банк минус сумма всех выводов больше выводимой суммы'), 'error');
                } else if($summa > $withdrawal_limit_by_bonus){
                    accaunt_message($data, _e('account/data47').'-1', 'error');
                } else
                if($summa < 50 || $summa != floor($summa / 50) * 50){
                    accaunt_message($data, _e('Некорректная сумма. Сумма должна быть кратна 50.'), 'error');
                } else
                if($set_account_bonus == 2 && in_array($data->payout_system, ['bank_lava', 'bank_okpay', 'bank_perfectmoney'])){
                    accaunt_message($data, _e('Вывод USD2 на платежные системы недоступен.'), 'error');
                } else

                if($set_account_bonus == 2 && $data->payout_system == 'wtcard'){
                    accaunt_message($data, _e('Вывод USD2 на карты недоступен.'), 'error');
                } else
                if($set_account_bonus == 2 && $summa > $data->real_payout_limit_2){
                    accaunt_message($data, _e('Вывод USD2 превышен лимит вывода.'), 'error');
                } else

                if($set_account_bonus == 6 && $summa > $data->limit_b6){
                    accaunt_message($data, _e('Превышен лимит вывода собственных средств'), 'error');
                } else
                if($summa > 2500 && $data->payout_system == 'wire_bank'){
                    accaunt_message($data, _e('Сумма на вывод для Банка не должна превышать $2500.'), 'error');
                } else
                if($summa > 1000 && $data->payout_system != 'wire_bank'){
                    accaunt_message($data, _e('Сумма на вывод не должна превышать $1000.'), 'error');
                } else
                if($rating){
//                        $userDocumentVerified = $this->accaunt->isUserDocumentVerified();
//                        $userAccessDocuments  = $this->accaunt->getAccessDocuments();\


                    $this->load->model('documents_model', 'documents');
                    $userDocumentStatus = $this->documents->getUserDocumentStatus();
//                        var_dump($userDocumentStatus);
//                        die;


                    if(0 < $summa && Documents_model::STATUS_PROVED != $userDocumentStatus){
                        if(Documents_model::STATUS_WAITING == $userDocumentStatus){
                            accaunt_message($data, _e('card/data1'), 'error');
                        } else {
                            accaunt_message($data, _e('card/data2'), 'error');
                        }
                    } else
//                        if ( $data->payout_system == 'bank_qiwi' && $payout_account != $user_phone ) {
                    if($data->payout_system == 'bank_qiwi' && $user_phone[0] != 7 && $payout_account != $user_phone){
                        accaunt_message($data, _e('account/data44'), 'error');
                        //                } elseif ( $data->payout_system == 'bank_liqpay' && $payout_account != $user_phone ) {
                        //                    accaunt_message($data, 'Номер Liqpay-кошелька должен совпадать с верифицированным телефоном.', 'error');
                    } else
                    if($data->payout_system == 'bank_qiwi' && $user_phone[0] == 7 && $payout_account[0] != 7 && $payout_account('bank_qiwi')[0] != 8){
                        accaunt_message($data, _e('account/data44'), 'error');
                    } else
                    if($data->payout_system == 'bank_qiwi' && $user_phone[0] == 7 && substr($payout_account, 1) != substr($user_phone, 1)){
                        accaunt_message($data, _e('account/data44'), 'error');
                    } else
                    if(!$this->accaunt->isUserAccountVerified())
                        accaunt_message($data, _e('account/data17'), 'error');
                    else if($rating['overdue_standart_count'] > 0){
                        accaunt_message($data, _e('account/data18'), 'error');
                    } else if($rating['overdue_garant_count'] > 0){
                        accaunt_message($data, _e('account/data18'), 'error');
                        //} else if ( !empty($this->transaction->getDouble($id_user)) ) {
                        //   accaunt_message($data, _e('У вас есть задвоенные транзакции'), 'error');
                    } else
//                        if (!$this->form_validation->run('amount')) {
//                            accaunt_message($data, _e('account/data45'), 'error');
//                        } else
                    if(50 > $summa)
                        accaunt_message($data, _e('account/data46'), 'error');
                    else if(1000 < $summa && 'wire_bank' != $data->payout_system ||
                        2500 < $summa && 'wire_bank' == $data->payout_system)
                        accaunt_message($data, _e('Максимальная сумма вывода').' $'.(('wire_bank' == $data->payout_system) ? "2500" : "1000"), 'error');
                    else if($this->accaunt->getAccessDocuments() == FALSE &&
                        (($summa > 500 ) || ($summa + $this->transactions->getMonthWithdrawalAmount($user_id) > 2000) )
                    ){
                        accaunt_message($data, sprintf(_e('Для проведения данной операции вам необходимо загрузить документы. Посмотреть %sлимиты на вывод%s'), '<a href="#" onclick="$(\'#unverifieds_limit\').show(); return false;">', '</a>'), 'error');
                        //} else if ( ($summa + $psnts) > $rating_bonus[$set_account_bonus]['payout_limit']) {
                    } else if(($summa + $psnts) > $rating_by_bonus['payout_limit_by_bonus'][$set_account_bonus] + $rating_by_bonus['income_merchant_send_by_bonus'][$set_account_bonus]){
                        accaunt_message($data, _e('account/data47').'-2', 'error');
                    } else if('wire_bank' == $data->payout_system && TRUE !== $this->payment_data->check_wire_bank_fields($wire_bank_systems)){
                        accaunt_message($data, _e('account/data48'), 'error');
                    } else {
                        //if (($summa + $psnts) > $rating['payout_limit'] - $rating['all_advanced_loans_out_summ']) {
                        //    accaunt_message($data, 'Заявка на вывод уже принята', 'error');
                        //} else
                        // $this->mail->admin_sender('pay-out_admin', $id_user, array('summa' => $summa));
                        $this->mail->user_sender('pay-out-user', $id_user, ['summa' => $summa]);

                        $loan_note = '';
                        if(!empty($data->loan))
                            $loan_note = ' Loan: #'.$data->loan->id.' ';

                        $post_vk_fb_note = '';
                        $bonus_vk_fb     = config_item('social_wallpost_bonus');
                        if(in_array($set_account_bonus, [3, 6]) && (int) $data->post_vk > 0)
                            $post_vk_fb_note .= ' Bonus $'.$bonus_vk_fb.' for vk post ';
                        if(in_array($set_account_bonus, [3, 6]) && (int) $data->post_fb > 0)
                            $post_vk_fb_note .= ' Bonus $'.$bonus_vk_fb.' for fb post ';

                        $bonus_note = '';
                        if($data->payout_bonus > 0)
                            $bonus_note = ' (бонус $ '.round($data->payout_bonus, 2).')';

                        //bank_cc_date_off
                        if('bank_cc' == $data->payout_system && isset(viewData()->user->bank_cc_date_off)){
                            $countris     = get_country();
                            $payment_info = $payout_fields[$data->payout_system].' '
                                .viewData()->user->{$data->payout_system}.' EXP '
                                .viewData()->user->bank_cc_date_off
                                .' '.$countris[$userProfile->place].' '
                                ."$loan_note"
                                ."$bonus_note"
                                ."$post_vk_fb_note"
                                ." (комисия $ $psnts)"
                                .(($data->other_systems) ? ". Быстрый платеж." : "");
                        } elseif('wire_bank' == $data->payout_system && isset(viewData()->user->wire_bank) &&
                            isset(viewData()->user->wire_bank['wire_beneficiary_account']) &&
                            !empty(viewData()->user->wire_bank['wire_beneficiary_account'])
                        ){
                            $payment_info = $payout_fields[$data->payout_system]
                                ." "
                                .viewData()->user->wire_bank['wire_beneficiary_account']
                                ."$loan_note"
                                ."$bonus_note"
                                ."$post_vk_fb_note"
                                ." (комисия $ $psnts)"
                                .(($data->other_systems) ? ". Быстрый платеж." : "");
                        } elseif('wtcard' == $data->payout_system){
                            $payment_info = $payout_fields[$data->payout_system]
                                ." "
                                ."$loan_note"
                                ."$bonus_note"
                                ."$post_vk_fb_note"
                                ." (комисия $ $psnts)"
                                .(($data->other_systems) ? ". Быстрый платеж." : "")
                                .'CARDID: '.$data->payout_system_sub_id.';';
                        } else {
                            $payment_info = $payout_fields[$data->payout_system]
                                ." "
                                .viewData()->user->{$data->payout_system}
                                ."$loan_note"
                                ."$bonus_note"
                                ."$post_vk_fb_note"
                                ." (комисия $ $psnts)"
                                .(($data->other_systems) ? ". Быстрый платеж." : "");
                        }


                        $this->load->model("transactions_model", "transactions");
                        $note   = "Заявка на вывод средств: $payment_info";
                        $paySys = payout_systems_type();




                        $trId = $this->transactions->addPay($user_id, $summa, $paySys[$data->payout_system], $paySys[$data->payout_system], 'out', Base_model::TRANSACTION_STATUS_IN_PROCESS, $set_account_bonus, $note);
                        // автовывод на карту с бонуса 6 если сумма позовляет
                        if($set_account_bonus == 6 && in_array($data->payout_system, ['wtcard', 'bank_lava', 'bank_okpay', 'bank_perfectmoney']) && $summa <= $rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6])
                            $this->transactions->autoPayoutOne($trId, TRUE);

                        $data->amount = '';
                        //  $this->transactions->autoPayoutOne($trId);

                        accaunt_message($data, _e('account/data9'));
                        redirect('account/payout');
                    }
                }
            }
        }

        $payout_systems_with_num = [];
        foreach($payout_systems as $name => $field){

            // отключим WireBank для не США
            if($name == 'Wire Bank' && !isUserUSorCA())
                continue;

            // отключим WireBank
            //   if ( $name == 'Wire Bank' )
            //      continue;
            //  if ( !in_array($name,['Webtransfer VISA Card','Wire Bank']) )
            //         continue;
            if($name == 'Webtransfer VISA Card' && !empty(viewData()->user->wtcards)){
                foreach($wtcards as $card){
                    //$payout_systems_with_num["$name [".substr($card->pan,-4)."]"] = $field.'['.$card->id.']';
                    $payout_systems_with_num[Card_model::display_card_name($card)] = $field.'['.$card->id.']';
                }
            }

            if(viewData()->user->$field){
                if(is_string(viewData()->user->$field))
                    $payout_systems_with_num["$name [".viewData()->user->$field."]"] = $field;
                else
                    $payout_systems_with_num[$name]                                  = $field;
            }
        }

        if(!$payout_systems_with_num)
            $payout_systems_with_num[_e('no_data')] = "no_data";

        $payout_systems_with_num[_e('account/data42_1')] = "select";
        $data->payout_systems                            = $payout_systems_with_num;
        $data->pay_fields                                = $payout_fields;
        $data->payuot_systems_psnt                       = $payuot_systems_psnt;

        $data->security = $protection_type;

        if($data->notAjax)
            $this->content->user_view("payout", $data, _e('account/data42'));
        else {
            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/payout', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data42')]);
            } else {
                $this->load->view('user/accaunt/payout', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data42')]);
            }
        }
    }

    public function delPayout($idTransaction){
        $this->accaunt->delTransaction((int) $idTransaction);
        redirect(site_url('account/transactions'));
    }

    public function send_money(){
        $data = new stdClass();
//        require_once APPPATH . 'libraries/reCapcha/recaptchalib.php';
//        $data->reCapcha = recaptcha_get_html(getReCapchaKey(), null, true);

        viewData()->page_name = __FUNCTION__;
        $this->load->model("users_model", "users");
        $this->load->model("monitoring_model", "monitoring");
        $this->load->model('card_model', 'card');
        $data->wtcards        = $this->card->getCards();

        $data->userUsers     = creatUserIdNameArray($this->users->getMyUsers());
        $data->userUsersCard = creatUserIdNameArrayCard($this->users->getMyUsers());


        //erase data
        $data->money_amount = '';
        $data->user_id      = '';
        $data->own          = $own                = $this->accaunt->get_user_id();
        $data->is_exchenge  = in_array($own, config_item('exchange_users'));
		$data->is_exchenge_debit  = in_array($own, config_item('exchangeUsersMerchantWTDebitEnabled'));

        $user_rating = $this->accaunt->recalculateUserRating($own);
        //$data->rating_bonus[2] = $this->accaunt->recalculateUserRating($own,null,2 );
        //$data->rating_bonus[5] = $this->accaunt->recalculateUserRating($own,null,5 );
        // пременые для ограничений
        $data->need_doc      = false;
        $data->limit         = 0;
        $data->incame        = 0;
        $data->little_incame = false;
        if(Users_model::DOC_REQUEST_ENABLE == $this->users->is_doc_request($own)){
            $data->need_doc = true;
            $this->_showsendmoney($data);
            return;
        }
        $data_incame  = $this->users->getUserPureIncome($own);
        $incame       = viewData()->accaunt_header['sum_partner_reword'] + $data_incame[0];
        $payout_limit = $this->users->get_payout_limit($own);

        if(Users_model::PAYOUT_LIMMIT_OFF != $payout_limit && $incame < $payout_limit){
            $data->limit         = $payout_limit;
            $data->incame        = $incame;
            $data->little_incame = true;
            $this->_showsendmoney($data);
            return;
        }

        $this->monitoring->log(null, "Страница перевода", 'common', $own);

        require_once APPPATH.'controllers/user/Security.php';
        $protection_type = Security::getProtectType($own);

        if(isset($_POST['submit']) && !Security::checkSecurity($own)){
            $user_data = $this->users->getUserFullProfile();
            if(empty($user_data) || $user_data->bot == 1){
                accaunt_message($data, _e('account/data5'), 'error');
                $this->_showsendmoney($data);
                return;
            }

            $this->load->library('form_validation');
            //return data
            $data->user_id      = (int) $this->input->post('id_user', true); //$_POST['id_user'];
            $data->money_amount = (float) $this->input->post('amount', true); //$_POST['amount'];
            $data->note         = $this->input->post('note', true);

            $data->comission_payer = $this->input->post('comission_payer', true);




            //field bonus in transaction
            $account_type_src = $this->input->post('account_type', true);


            //field bonus in transaction
            $account_type = Base_model::TRANSACTION_BONUS_OFF;
            switch($account_type_src){
                case 'P-CREDS':
                    $account_type = Base_model::TRANSACTION_BONUS_PARTNER;
                    break;
                case 'C-CREDS':
                    $account_type = Base_model::TRANSACTION_BONUS_CREDS_CASH;
                    break;
                case 'WTUSD1':
                    $account_type = 5;
                    break;
                case 'WTUSD2':
                    $account_type = 2;
                    break;
                case 'WTDEBIT':
                    $account_type = 6;
                    break;

                default: //CREDS
                    $account_type = Base_model::TRANSACTION_BONUS_OFF;
            }
            $rating = viewData()->accaunt_header;


            if(!($this->form_validation->run('send_money'))){
                accaunt_message($data, 'Ошибка ввода.'.$this->form_validation->error_string(), 'error');
                $this->_showsendmoney($data);
                return;
            }

            $this->monitoring->log(null, "Создана заявка на Перевод пользователю №".$data->user_id, 'common', $this->accaunt->id_user);

//            $a = recaptcha_check_answer(getReCapchaKeyPrivate(), $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'], $extra_params = array());
//            if (!$a->is_valid) {
//                accaunt_message($data, 'Не верный код с картинки', 'error');
//                $data->reCapcha = recaptcha_get_html(getReCapchaKey(), $a->error, true);
//                $this->content->user_view("send_money", $data, 'Отправить');
//                return;
//            }
            //@fix esb 18.08.2014 востановление возможности искать по email telephone [31]
            //@fix esb 18.08.2014 востановление замены , на . в сумме и добавление 2 знаков после точки
            $isCardDirect = $this->input->post('card_direct');

            $id_user = (int) $this->accaunt->getIdUserByData(htmlspecialchars(substr($this->input->post('id_user', true), 0, 60)));
            if($isCardDirect !== 'true' && $id_user == 0 && !in_array($id_user, getVipUsers())){ //|| !in_array($id_user, array(500112, 500111)) || !in_array($id_user, array_flip($data->userUsers)) - cencel only for partner esb 27.10.2014
                accaunt_message($data, _e('account/data49'), 'error');
                $this->_showsendmoney($data);
                return;
            }
            $note       = htmlspecialchars(substr($this->input->post('note', true), 0, 100));
            $protection = (int) $this->input->post('protection');
            if($protection){
                $code = (int) $this->input->post('codeprotection');
                if(empty($code)){
                    accaunt_message($data, _e('account/data50'), 'error');
                    $this->_showsendmoney($data);
                    return;
                }
            }

            $summa                = $summa_with_comission = round((float) str_replace(",", ".", (string) $this->input->post('amount', true)), 2);
            if(0 >= $summa){
                accaunt_message($data, _e('account/data51'), 'error');
                $this->_showsendmoney($data);
                return;
            }


            $sender_comission_note   = '';
            $receiver_comission_note = '';
            if($isCardDirect !== 'true'){

                if($data->comission_payer == 'both'){
                    $comission               = round($summa * config_item('account_sendmoney_comission') / 2 / 100, 2);
                    $summa_with_comission    = round($summa + $comission, 2);
                    $sender_comission_note   = ' комиссия=$'.$comission;
                    $receiver_comission_note = ' комиссия=$'.$comission;
                } elseif($data->comission_payer == 'receiver'){
                    $comission               = round($summa * config_item('account_sendmoney_comission') / 100, 2);
                    $receiver_comission_note = ' комиссия=$'.$comission;
                } else {
                    $comission             = round($summa * config_item('account_sendmoney_comission') / 100, 2);
                    $summa_with_comission  = round($summa + $comission, 2);
                    $sender_comission_note = ' комиссия=$'.$comission;
                }
            }

            $send_transaction          = new stdClass;
            $send_transaction->summa   = $summa;
            $send_transaction->own     = $own;  //from user
            $send_transaction->id_user = $id_user;
            $send_transaction->note    = $note;


            if('true' === $isCardDirect){

                $this->load->model('Fincore_model', 'fincore');
                if(!$this->fincore->canInvest($user_rating, $error)){
                    accaunt_message($data, $error, 'error');
                    $this->_showsendmoney($data);
                    return;
                }

                $card_id          = $this->input->post('card', true);
                $receiver_card_id = $this->input->post('id_user', true);
                if(empty($card_id)){
                    accaunt_message($data, _e('Ну указана карта'), 'error');
                    $this->_showsendmoney($data);
                    return;
                }


                $send_limit = $this->card->get_send_money_limit($card_id);
                if($send_transaction->summa > $send_limit){
                    accaunt_message($data, _e('Недостаточно лимита на карте'), 'error');
                    $this->_showsendmoney($data);
                    return;
                }

                if($this->input->post('to_all', true) == 1){
                    $receiver_card_id = $this->input->post('recepient_user_card', true);
                }


                $send_transaction->own_card_id = $card_id; //from user
                $send_transaction->id_user     = $receiver_card_id; //to card
                $error                         = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_SEND_DIRECT);
                if("OK" == $error)
                    accaunt_message($data, _e("Перевод осуществлен успешно"));
                else
                    accaunt_message($data, $error, 'error');

                $this->_showsendmoney($data);
                return;
            } else {
                $send_transaction->summa = $summa_with_comission;
            }

            if(!in_array($id_user, getVipUsers()) && !in_array($own, getUnlimitedUsers()) && 50 > $summa){ //только те кому отсылают из вип юзеров не проходят проверку на более 50
                accaunt_message($data, _e('account/data8'), 'error');
                $this->_showsendmoney($data);
                return;
            }

            $is_return = ('return' == $this->input->post('money_back', true));
            /*
              #проверка лимитов пополнения
              $this->load->model('transactions_model','transactions');
              $error_text = $this->transactions->checkTopUpLimits( $id_user, $summa );

              if( TRUE !== $error_text ) accaunt_message($data, $error_text['error'], 'error');
              else
             */
            if($rating){

                $errors_exists = TRUE;
                if(!$this->accaunt->isUserAccountVerified()) //верифицированность аккаунта
                    accaunt_message($data, _e('account/data17'), 'error');
                else if($rating['overdue_standart_count'] > 0)//наличие просроченных стандартных займов
                    accaunt_message($data, _e('account/data19'));
                else if($rating['overdue_garant_count'] > 0) //наличие просроченных гарантированных займов
                    accaunt_message($data, _e('account/data18'), 'error');
                else
                if($account_type == Base_model::TRANSACTION_BONUS_CREDS_CASH || $account_type == Base_model::TRANSACTION_BONUS_PARTNER || $account_type == 5 || $account_type == 6 || $account_type == 2){

                    //c_creds_amount
                    if($summa_with_comission > $rating['payout_limit_by_bonus'][4] && $account_type == Base_model::TRANSACTION_BONUS_CREDS_CASH)
                        accaunt_message($data, _e('account/data52_4'), 'error');
                    else if($summa_with_comission > $rating['payout_limit_by_bonus'][3] && $account_type == Base_model::TRANSACTION_BONUS_PARTNER)
                        accaunt_message($data, _e('account/data52_3'), 'error');
                    else if($summa_with_comission > $rating['payout_limit_by_bonus'][2] && $account_type == 2)
                        accaunt_message($data, _e('account/data52'), 'error');
                    else if($summa_with_comission > $rating['payout_limit_by_bonus'][5] && $account_type == 5)
                        accaunt_message($data, _e('У вас недостаточно средств на USD1'), 'error');
                    else if($summa_with_comission > $rating['payout_limit_by_bonus'][6] && $account_type == 6)
                        accaunt_message($data, _e('У вас недостаточно средств на WTDEBIT'), 'error');

                    else {
                        $errors_exists = FALSE;

                        $this->load->model("transactions_model", "transactions");
                        if(500111 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Пожертвование в благотворительный фонд ".$GLOBALS["WHITELABEL_NAME"].": \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Пожертвование от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "в благотворительный фонд ".$GLOBALS["WHITELABEL_NAME"], 'summa' => $summa, 'note' => $note]);
                        } else if(500112 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Покупка в магазине WTSHOP: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Покупка в магазине от $own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты в магазине WTSHOP", 'summa' => $summa, 'note' => $note]);
                        } else if(500113 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Оплата услуг Skypasser: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Оплата услуг Skypasser от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты услуг Skypasser", 'summa' => $summa, 'note' => $note]);
                        } else if(500114 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Оплата услуг Webtour.by: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Оплата услуг Webtour.by от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты услуг Webtour.by", 'summa' => $summa, 'note' => $note]);
                        } else {

                            $this->load->model('documents_model', 'documents');
                            $userDocumentStatus = $this->documents->getUserDocumentStatus();

                            if(empty($note)){
                                accaunt_message($data, _e('account/data53'), 'error');
                                $this->_showsendmoney($data);
                                return;
                                //   } else if ($this->accaunt->isUserUSorCA($id_user) || $this->accaunt->isUserUSorCA()) {
                                //        accaunt_message($data, _e('account/data41'), 'error');
                                //       $this->_showsendmoney($data);
                                //       return;
                            } else #месячный оборот не может быть больше 2000
                            if(//!in_array($own, getUnlimitedUsers()) &&
                                ($this->accaunt->getAccessDocuments() == FALSE || Documents_model::STATUS_PROVED != $userDocumentStatus)
//                                 && ( $summa + $this->transactions->getMonthWithdrawalAmount( $own ) > 2000 )
                            ){
                                accaunt_message($data, sprintf(_e('Для проведения данной операции вам необходимо загрузить документы. Посмотреть %sлимиты на вывод%s'), '<a href="#" onclick="$(\'#unverifieds_limit\').show(); return false;">', '</a>'), 'error');
                                $this->_showsendmoney($data);
                                return;
                            } else if(!in_array($own, getUnlimitedUsers()) && (0 <= $summa && Documents_model::STATUS_PROVED != $userDocumentStatus)){
                                if(Documents_model::STATUS_WAITING == $userDocumentStatus)
                                    accaunt_message($data, _e('card/data1'), 'error');
                                else
                                    accaunt_message($data, _e('card/data2'), 'error');

                                $this->_showsendmoney($data);
                                return;
                            }

                            $this->load->model('inbox_model');
                            //для провереных пользователей автоматом и для тех кто может отправить без проверки
                            if(!in_array($id_user, getUntrastedUsers4Send()) && (in_array($own, getCheckedUsers()) || true === $this->accaunt->isCanSendMoney($send_transaction, FALSE, FALSE, TRUE))){
                                $type = ($is_return && $data->is_exchenge) ? Transactions_model::TYPE_EXCHANGE_RETURN : Transactions_model::TYPE_SEND_MONEY;
                                $type = (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : $type);

                                $own_fi     = $this->users->getUserDataFields($own, ['sername', 'name']);
                                $id_user_fi = $this->users->getUserDataFields($id_user, ['sername', 'name']);
                                if($own == 99676729){
                                    $t_own_id  = $this->transactions->addPay($own, $summa, $type, 0, 'wtadmin', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername}){$sender_comission_note}: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, $type, $t_own_id, 'wtadmin', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername}){$receiver_comission_note}: \"$note\"");
                                } else {
                                    $t_own_id  = $this->transactions->addPay($own, $summa, $type, 0, 'wt', (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED), $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername}){$sender_comission_note}: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, $type, $t_own_id, 'wt', (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED), $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername}){$receiver_comission_note}: \"$note\"");
                                }
                                $this->transactions->setValue($t_own_id, $t_id_user);
                                if(!$code){
                                    $this->transactions->take_sendmoney_commission($t_own_id);
                                    $this->transactions->take_sendmoney_commission($t_id_user);
                                }

                                if($code){
                                    $this->load->model("send_money_protection_model", "send_money_protection");
                                    //                                $this->send_money_protection->setProtectionCode($code, $t_own_id);
                                    $this->send_money_protection->setProtectionCode($code, $t_id_user);

                                    $this->mail->user_sender('send_money_coferm', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                    $this->mail->user_sender('send_money_coferm_sender', $own, ['to' => $own, 'summa' => $summa, 'note' => $note]);
                                    $mes = sprintf(_e('Мы получили заявку от Пользователя № %s на внутренний перевод средств:<br><br>Сумма перевода $%d<br>%s<br><br>Сразу после его ввода кода протекции деньги будут зачислены на Ваш кошелек.<br>%sВвести код%s<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $summa, $note, "<a onclick='$(\"#send_form_user\").attr(\"action\",\"".site_url('account/confermSendMoney')."/$t_id_user\"); $(\"#sendmoney\").show(); return false;' href = ''>", '</a>', $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($id_user, $mes);
                                    $mes = sprintf(_e('Ваша заявка на внутренний перевод средств пользователю № %d:<br><br>Сумма перевода $%d<br>%s<br><br>Сразу после его ввода кода протекции деньги будут зачислены на тот кошелек.<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($own, $mes);
                                } else {
                                    $this->mail->user_sender('send_money_coferm', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                    $this->mail->user_sender('send_money_coferm_ok', $own, ['to' => $id_user, 'summa' => $summa, 'note' => $note]);

                                    $mes = sprintf(_e('Мы получили заявку от Пользователя № %s на внутренний перевод средств:<br><br>Сумма перевода $%d<br>%s<br><br>Если отправитель установил код протекции, то сразу после его ввода деньги будут зачислены на Ваш кошелек.<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($id_user, $mes);
                                    $mes = sprintf(_e('Ваша заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: %d.<br>Сумма перевода $%d<br>%s<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($own, $mes);

//                                    // оплата бонусов для пополнения через обменик EX-WT и бругие
//                                    if(in_array($own, getExchangeUsers()) && !$is_return){ //
//                                        $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
//                                        $this->transactions->payPaymentBonus($data_trans_id_user, TRUE);
//                                    }
                                    if(in_array($own, config_item('promo_exchange_users')) && !$is_return){
                                        $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
                                        $this->transactions->payPaymentBonusForExchange($data_trans_id_user, $own);
                                    }
                                }
                            } else {
                                $own_fi     = $this->users->getUserDataFields($own, ['sername', 'name']);
                                $id_user_fi = $this->users->getUserDataFields($id_user, ['sername', 'name']);
                                if($own == 99676729){
                                    $t_own_id  = $this->transactions->addPay($own, $summa, Transactions_model::TYPE_SEND_MONEY, 0, 'wtadmin', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername}){$sender_comission_note}: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, Transactions_model::TYPE_SEND_MONEY, $t_own_id, 'wtadmin', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername}){$receiver_comission_note}: \"$note\"");
                                } else {
                                    $type      = (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : Transactions_model::TYPE_SEND_MONEY);
                                    $t_own_id  = $this->transactions->addPay($own, $summa, $type, 0, 'wt', Base_model::TRANSACTION_STATUS_IN_PROCESS, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername})$sender_comission_note: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, $type, $t_own_id, 'wt', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername})$receiver_comission_note: \"$note\"");
                                }
                                $this->transactions->setValue($t_own_id, $t_id_user);
                                if($code){
                                    $this->load->model("send_money_protection_model", "send_money_protection");
                                    //                                $this->send_money_protection->setProtectionCode($code, $t_own_id);
                                    $this->send_money_protection->setProtectionCode($code, $t_id_user);
                                }
                                $this->mail->user_sender('send_money', $own, ['to' => "пользователю $id_user. Перевод будет зачислен после проверки.", 'summa' => $summa, 'note' => $note]);
                                $this->mail->user_sender('send_money_receiver', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                $mes = sprintf(_e('Мы получили вашу заявку на внутренний перевод средств:<br>Кому: %d.<br>Сумма перевода $%d<br>Описание перевода %s<br><br>На данном этапе ваша заявка находится в статусе - \"В процессе\", деньги Получателю будут переведены сразу после проверки транзакции.<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                $this->inbox_model->writeMess2Inbox($own, $mes);
                                $mes = sprintf(_e('Для вас был инициирован внутренний перевод средств от %s:<br>Кому: %d.<br>Сумма перевода $%d<br>Описание перевода %s<br><br>На данном этапе ваша заявка находится в статусе - \"Не получено\", деньги будут переведены сразу после проверки транзакции.<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                $this->inbox_model->writeMess2Inbox($id_user, $mes);
                                if(in_array($own, config_item('promo_exchange_users')) && !$is_return){
                                    $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
                                    $this->transactions->payPaymentBonusForExchange($data_trans_id_user, $own);
                                }
                            }
                            accaunt_message($data, _e('account/data54'));
                            redirect(site_url('account/transactions'));
                        }

                        //erase data
                        $data->money_amount = '';
                        $data->user_id      = '';
                        $data->note         = '';

                        accaunt_message($data, _e('account/data54'));
                    }
                } else {
                    // не пускать обмениваться всеъ кто не входитв в список обменников
                    if(!in_array($own, getExchangeUsers())){ //
                        accaunt_message($data, _e('Вам недоступно это действие'), 'error');
                        redirect('account/send_money');
                    }

                    $rating_own   = $this->accaunt->recalculateUserRating($own);
                    $payout_limit = $rating_own['payout_limit_by_bonus'][$account_type];


                    if($summa > $payout_limit) //достаточное количество денег на платежном счете
                        accaunt_message($data, _e('account/data52'), 'error');
                    else {
                        $errors_exists = FALSE;

                        $this->load->model("transactions_model", "transactions");
                        if(500111 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Пожертвование в благотворительный фонд ".$GLOBALS["WHITELABEL_NAME"].": \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Пожертвование от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "в благотворительный фонд ".$GLOBALS["WHITELABEL_NAME"], 'summa' => $summa, 'note' => $note]);
                        } else if(500112 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Покупка в магазине WTSHOP: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Покупка в магазине от $own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты в магазине WTSHOP", 'summa' => $summa, 'note' => $note]);
                        } else if(500113 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Оплата услуг Skypasser: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Оплата услуг Skypasser от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты услуг Skypasser", 'summa' => $summa, 'note' => $note]);
                        } else if(500114 == $id_user){
                            $this->transactions->addPay($own, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Оплата услуг Webtour.by: \"$note\"");
                            $this->transactions->addPay($id_user, $summa, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Оплата услуг Webtour.by от №$own: \"$note\"");
                            $this->mail->user_sender('send_money', $own, ['to' => "для оплаты услуг Webtour.by", 'summa' => $summa, 'note' => $note]);
                        } else {
                            $this->load->model('documents_model', 'documents');
                            $userDocumentStatus = $this->documents->getUserDocumentStatus();

                            if(empty($note)){
                                accaunt_message($data, _e('account/data53'), 'error');
                                $this->_showsendmoney($data);
                                return;
                                //  } else if ($this->accaunt->isUserUSorCA($id_user) || $this->accaunt->isUserUSorCA()) {
                                //     accaunt_message($data, _e('account/data41'), 'error');
                                //     $this->_showsendmoney($data);
                                //     return;
                            } else #месячный оборот не может быть больше 2000
                            if(//!in_array($own, getUnlimitedUsers()) &&
                                ($this->accaunt->getAccessDocuments() == FALSE || Documents_model::STATUS_PROVED != $userDocumentStatus ) &&
                                ( $summa + $this->transactions->getMonthWithdrawalAmount($own) > 2000 )
                            ){
                                accaunt_message($data, sprintf(_e('Для проведения данной операции вам необходимо загрузить документы. Посмотреть %sлимиты на вывод%s'), '<a href="#" onclick="$(\'#unverifieds_limit\').show(); return false;">', '</a>'), 'error');
                                $this->_showsendmoney($data);
                                return;
                            } else if(!in_array($own, getUnlimitedUsers()) && (0 <= $summa && Documents_model::STATUS_PROVED != $userDocumentStatus)){
                                if(Documents_model::STATUS_WAITING == $userDocumentStatus)
                                    accaunt_message($data, _e('card/data1'), 'error');
                                else
                                    accaunt_message($data, _e('card/data2'), 'error');

                                $this->_showsendmoney($data);
                                return;
                            }

                            $this->load->model('inbox_model');
                            if(!in_array($id_user, getUntrastedUsers4Send()) && (in_array($own, getCheckedUsers()) || true === $this->accaunt->isCanSendMoney($send_transaction, FALSE, FALSE, TRUE))){ //для провереных пользователей автоматом и для тех кто может отправить без проверки
                                $type       = ($is_return && $data->is_exchenge) ? Transactions_model::TYPE_EXCHANGE_RETURN : Transactions_model::TYPE_SEND_MONEY;
                                $type       = (($code) ? Transactions_model::TYPE_SEND_MONEY_CONFERM : $type);
                                $own_fi     = $this->users->getUserDataFields($own, ['sername', 'name']);
                                $id_user_fi = $this->users->getUserDataFields($id_user, ['sername', 'name']);
                                if($own == 99676729){
                                    $t_own_id  = $this->transactions->addPay($own, $summa, $type, 0, 'wtadmin', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername}){$sender_comission_note}: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, $type, $t_own_id, 'wtadmin', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername}){$receiver_comission_note}: \"$note\"");
                                } else {
                                    $t_own_id  = $this->transactions->addPay($own, $summa, $type, 0, 'wt', (($code) ? Base_model::TRANSACTION_STATUS_IN_PROCESS : Base_model::TRANSACTION_STATUS_REMOVED), $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername})$sender_comission_note: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, $type, $t_own_id, 'wt', (($code) ? Base_model::TRANSACTION_STATUS_NOT_RECEIVED : Base_model::TRANSACTION_STATUS_RECEIVED), $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername})$receiver_comission_note: \"$note\"");
                                }
                                $this->transactions->setValue($t_own_id, $t_id_user);
                                if($code){
                                    $this->load->model("send_money_protection_model", "send_money_protection");
                                    //                                $this->send_money_protection->setProtectionCode($code, $t_own_id);
                                    $this->send_money_protection->setProtectionCode($code, $t_id_user);

                                    $this->mail->user_sender('send_money_coferm', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                    $this->mail->user_sender('send_money_coferm_sender', $own, ['to' => $own, 'summa' => $summa, 'note' => $note]);
                                    $mes = sprintf(_e('Мы получили заявку от Пользователя № %s на внутренний перевод средств:<br><br>Сумма перевода $%d<br>%s<br><br>Сразу после его ввода кода протекции деньги будут зачислены на Ваш кошелек.<br>%sВвести код%s<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $summa, $note, "<a onclick='$(\"#send_form_user\").attr(\"action\",\"".site_url('account/confermSendMoney')."/$t_id_user\"); $(\"#sendmoney\").show(); return false;' href = ''>", '</a>', $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($id_user, $mes);
                                    $mes = sprintf(_e('Ваша заявка на внутренний перевод средств пользователю № %d:<br><br>Сумма перевода $%d<br>%s<br><br>Сразу после его ввода кода протекции деньги будут зачислены на тот кошелек.<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($own, $mes);
                                } else {
                                    $this->mail->user_sender('send_money_coferm', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                    $this->mail->user_sender('send_money_coferm_ok', $own, ['to' => $id_user, 'summa' => $summa, 'note' => $note]);

                                    $mes = sprintf(_e('Мы получили заявку от Пользователя № %s на внутренний перевод средств:<br><br>Сумма перевода $%d<br>%s<br><br>Если отправитель установил код протекции, то сразу после его ввода деньги будут зачислены на Ваш кошелек.<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($id_user, $mes);
                                    $mes = sprintf(_e('Ваша заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: %d.<br>Сумма перевода $%d<br>%s<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                    $this->inbox_model->writeMess2Inbox($own, $mes);

                                    // оплата бонусов для пополнения через обменик EX-WT и бругие
                                    if(in_array($own, getExchangeUsers()) && !$is_return){ //
                                        $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
                                        $this->transactions->payPaymentBonus($data_trans_id_user, TRUE);
                                    }

                                    if(in_array($own, config_item('promo_exchange_users')) && !$is_return){
                                        $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
                                        $this->transactions->payPaymentBonusForExchange($data_trans_id_user, $own);
                                    }
                                }
                            } else {
                                $own_fi     = $this->users->getUserDataFields($own, ['sername', 'name']);
                                $id_user_fi = $this->users->getUserDataFields($id_user, ['sername', 'name']);
                                if($own == 99676729){
                                    $t_own_id  = $this->transactions->addPay($own, $summa, Transactions_model::TYPE_SEND_MONEY, 0, 'wtadmin', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername}){$sender_comission_note}: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, Transactions_model::TYPE_SEND_MONEY, $t_own_id, 'wtadmin', Base_model::TRANSACTION_STATUS_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername}){$receiver_comission_note}: \"$note\"");
                                } else {
                                    $t_own_id  = $this->transactions->addPay($own, $summa, Transactions_model::TYPE_SEND_MONEY, 0, 'wt', Base_model::TRANSACTION_STATUS_IN_PROCESS, $account_type, "Отправка средств ($account_type_src) пользователю №$id_user({$id_user_fi->name} {$id_user_fi->sername})$receiver_comission_note: \"$note\"");
                                    $t_id_user = $this->transactions->addPay($id_user, $summa, Transactions_model::TYPE_SEND_MONEY, $t_own_id, 'wt', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, $account_type, "Получение средств ($account_type_src) от пользователя №$own({$own_fi->name} {$own_fi->sername})$receiver_comission_note: \"$note\"");
                                }
                                $this->transactions->setValue($t_own_id, $t_id_user);
                                if($code){
                                    $this->load->model("send_money_protection_model", "send_money_protection");
                                    //                                $this->send_money_protection->setProtectionCode($code, $t_own_id);
                                    $this->send_money_protection->setProtectionCode($code, $t_id_user);
                                }
                                $this->mail->user_sender('send_money', $own, ['to' => "пользователю $id_user. Перевод будет зачислен после проверки.", 'summa' => $summa, 'note' => $note]);
                                $this->mail->user_sender('send_money_receiver', $id_user, ['from' => $own, 'summa' => $summa, 'note' => $note]);
                                $mes = sprintf(_e('Мы получили вашу заявку на внутренний перевод средств:<br>Кому: %d.<br>Сумма перевода $%d<br>Описание перевода %s<br><br>На данном этапе ваша заявка находится в статусе - \"В процессе\", деньги Получателю будут переведены сразу после проверки транзакции.<br><br><br><br>С уважением,<br><br>Команда %s'), $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                $this->inbox_model->writeMess2Inbox($own, $mes);
                                $mes = sprintf(_e('Для вас был инициирован внутренний перевод средств от %s:<br>Кому: %d.<br>Сумма перевода $%d<br>Описание перевода %s<br><br>На данном этапе ваша заявка находится в статусе - \"Не получено\", деньги будут переведены сразу после проверки транзакции.<br><br><br><br>С уважением,<br><br>Команда %s'), $own, $id_user, $summa, $note, $GLOBALS["WHITELABEL_NAME"]);
                                $this->inbox_model->writeMess2Inbox($id_user, $mes);

                                if(in_array($own, config_item('promo_exchange_users')) && !$is_return){
                                    $data_trans_id_user = $this->transactions->getTransaction($t_id_user);
                                    $this->transactions->payPaymentBonusForExchange($data_trans_id_user, $own);
                                }
                            }
                            accaunt_message($data, _e('account/data54'));
                            redirect(site_url('account/transactions'));
                        }

                        //erase data
                        $data->money_amount = '';
                        $data->user_id      = '';
                        $data->note         = '';

                        accaunt_message($data, _e('account/data54'));
                    }
                }

//                if( !$errors_exists ){
//
//                }
            }
        }
        $data->security = $protection_type;
        $this->_showsendmoney($data);
    }

    public function confermSendMoney($transaction_id = 0){
        if(0 == $transaction_id)
            show_404();
        $this->load->model('transactions_model', 'transactions');

        $t       = $this->transactions->getTransaction($transaction_id);
        //проверки
        $id_user = $this->accaunt->get_user_id();
        if($id_user != $t->id_user)
            show_404();
        if(Transactions_model::TYPE_SEND_MONEY_CONFERM != $t->type)
            show_404();
        if(Base_model::TRANSACTION_STATUS_NOT_RECEIVED != $t->status){
            accaunt_message(new stdClass(), _e('account/data55'), 'error');
            redirect(site_url('account/transactions'));
        }
        $t_sender = $this->transactions->getTransaction($t->value);
        $code     = (int) $this->input->post('code');
        $this->load->model("send_money_protection_model", "send_money_protection");
        if($this->send_money_protection->checkProtection($t->id, $code)){
            $this->transactions->setStatus($t->id, Base_model::TRANSACTION_STATUS_RECEIVED);
            $this->transactions->setStatus($t->value, Base_model::TRANSACTION_STATUS_REMOVED);

            $this->transactions->take_sendmoney_commission($t->id);
            $this->transactions->take_sendmoney_commission($t->value);


            $this->mail->user_sender('send_money_coferm_ok', $t_sender->id_user, ['to' => $t->id_user, 'summa' => $t_sender->summa, 'note' => $t_sender->note]);
            $this->mail->user_sender('send_money_coferm_ok_receiver', $t->id_user, ['from' => $t_sender->id_user, 'to' => $t->id_user, 'summa' => $t_sender->summa, 'note' => $t_sender->note]);
            $this->load->model('inbox_model');
            $mes = sprintf(_e('Ваша заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: %d.<br>Сумма перевода $%d<br>%s<br><br><br><br>С уважением,<br><br>Команда %s'), $t->id_user, $t_sender->summa, $t_sender->note, $GLOBALS["WHITELABEL_NAME"]);
            $this->inbox_model->writeMess2Inbox($t_sender->id_user, $mes);
            $mes = sprintf(_e('Заявка на внутренний перевод средств была обработана и средства зачислены на кошелек Пользователя:<br><br>Кому: %d.<br>Сумма перевода $%d<br>%s<br><br><br><br>С уважением,<br><br>Команда %s'), $t->id_user, $t_sender->summa, $t_sender->note, $GLOBALS["WHITELABEL_NAME"]);
            $this->inbox_model->writeMess2Inbox($t->id_user, $mes);
            accaunt_message(new stdClass(), _e('account/data56'));
        } else {
            accaunt_message(new stdClass(), _e('account/data57'), 'error');
        }
        redirect(site_url('account/transactions'));
    }

    private function _showsendmoney($data){
        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->user_view("send_money", $data, _e('account/data6'));
        else
            $this->load->view('user/accaunt/send_money', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => _e('account/data6')]);
    }

    //page
    public function documents(){
        $data                      = new stdClass();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "settings";
        $this->load->helper('form');
        if(isset($_POST['save']) && in_array(@$_POST['type'], config_item('documents_count'), true)){
            $this->load->library('image');
            $this->image->place = "upload/tmp/";

            $image = $this->image;
            $foto  = $image->file('foto', true);

            if(in_array($foto, [1, 2, 3], true)){
                if($foto == 1)
                    $sms = _e('account/data58');
                if($foto == 3)
                    $sms = _e('account/data59');
                if($foto == 2)
                    $sms = _e('account/data60');
                accaunt_message($data, @$sms, 'error');
            } else {

                $add_foto = $image->add_foto($foto);

                $type = $this->input->post('type', TRUE);

                if(!empty($add_foto)){
                    $tmpPath = $this->image->place.$add_foto;  //имя файла
                    $this->code->fileCodeSave('upload/doc/'.$add_foto, $this->code->fileCode($tmpPath));
                    unlink($tmpPath);

                    $docs = $this->db
                        ->select('state, img, state')
                        ->where(['num' => $type, 'id_user' => $this->user->id_user])
                        ->get('documents')
                        ->row();

                    if(empty($docs)){
                        $this->db->insert('documents', ["id_user" => $this->user->id_user, 'num' => $type, 'img' => $add_foto, 'state' => 1]);
                        @unlink($this->image->place.$add_foto);
                    } else {

                        if($docs->state != 2){
                            $update = ['img' => $add_foto, 'state' => 1];
                            @unlink($this->image->place.$docs->img);
                        } else {
                            $update = ['img2' => $add_foto];
                        }

                        $this->db->where(['num' => $type, 'id_user' => $this->user->id_user])->update('documents', $update);
                    }

                    accaunt_message($data, _e('account/data61'));

                    $this->load->model('accaunt_model', 'accaunt');
                    if($this->accaunt->isUserDocumentVerified($this->user->id_user))
                        $this->accaunt->payBonusesToPartner($this->user->id_user);
                }
            }
        }

        $data->items = $this->accaunt->get_documents();
        if($this->base_model->returnNotAjax())
            $this->content->user_view('documents', $data, '');
        else
            $this->load->view('user/accaunt/documents', $data);
    }

    //page + new ajax
    public function profile(){
        $data = new stdClass();
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('payment_data_model', 'payment_data');
        $this->load->model('users_model', 'users');
        $this->load->model('phone_model', 'phone');

        $id                        = $this->user->id_user;
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "settings";

        if(!empty($_POST['submited'])){
            $this->load->model('monitoring_model', 'monitoring');

            require_once APPPATH.'controllers/user/Security.php';

            $this->monitoring->log(null, _e('account/data62'));

            $this->load->helper(['form', 'url']);


            $this->load->library('form_validation');

            //проверка верифицированного телефона
            //if( $this->input->post('phone_verification') != 1 ) accaunt_message($data, 'Телефон должен быть подтвежден для дальнейшей работы в системе', 'error');
            //else
            $user_phone     = $this->phone->get_phone_by_user_id($this->accaunt->get_user_id());
//
            $post_bank_qiwi = $this->input->post('bank_qiwi', true);

            $this->load->helper('translit');
            $_POST['n_name'] = translitIt($_POST['n_name']);
            $_POST['f_name'] = translitIt($_POST['f_name']);
            $_POST['o_name'] = translitIt($_POST['o_name']);

            //print_r($_POST);
            //die('!');
            if($this->form_validation->run('profile') == FALSE){
// редирект  на страницу регистрации,  запись  в куки  сообщения об  ошибках
                accaunt_message($data, validation_errors(), 'error');
//            } elseif (!$this->accaunt->isUserPhoneVerified()) { #TURN_OFF_PHONE_VEARIFICATION
//                accaunt_message($data, _e('account/data63'), 'error');
            } elseif($this->usersFilds->isNotUniqueFild([
                    'id_user'  => $this->user->id_user,
                    'nickname' => $this->input->post('nickname')])){
                accaunt_message($data, _e('account/data64'), 'error');
            } elseif($this->users->isUsedOnlyLatinLanguage($this->input->post(NULL, TRUE)) === FALSE){
                accaunt_message($data, _e('account/data64_02'), 'error');
            } elseif($this->input->post('bank_qiwi') != null && $user_phone[0] != 7 && $post_bank_qiwi != $user_phone){
                accaunt_message($data, _e('account/data44'), 'error');
            } elseif($this->input->post('bank_qiwi') != null &&
                $user_phone[0] == 7 && $post_bank_qiwi[0] != 7 && $post_bank_qiwi[0] != 8){
                accaunt_message($data, _e('account/data44'), 'error');
            } elseif($this->input->post('bank_qiwi') != null && $user_phone[0] == 7 && substr($post_bank_qiwi, 1) != substr($user_phone, 1)){
                accaunt_message($data, _e('account/data44'), 'error');
//                } elseif ( $this->input->post('bank_liqpay') != null && $this->input->post('bank_liqpay') != $user_phone ) {
//                    accaunt_message($data, 'Номер Liqpay-кошелька должен совпадать с верифицированным телефоном.', 'error');
            } elseif($this->input->post('bank_tinkoff') != null && preg_match('/^\d+$/', $this->input->post('bank_tinkoff')) && $this->input->post('bank_tinkoff') != $user_phone){
                accaunt_message($data, _e('account/data65'), 'error');
            } else {
                $secur = Security::checkSecurity($id, true);

                if(isset($secur['error'])){
                    accaunt_message($data, $secur['error'], 'error');
                    echo json_encode(['redirect' => site_url('account/profile')]);
                    return;
                } else
                if(PROFILE_MODERATOR){
                    $result            = array_merge(
                        $this->base_model->user_fields(true), $this->base_model->get_user_place('r', true), $this->base_model->get_user_place('f', true)
                    );
                    $result['date']    = date("Y-m-d H:i:s");
                    $result['id_user'] = $id;
                    $tmp               = $this->db->where('id_user', $id)->select('id_user')->get('chanche_users')->row('id_user');

                    if(empty($tmp))
                        $this->db->insert('chanche_users', $result);
                    else
                        $this->db->where('id_user', $id)->update('chanche_users', $result);

                    accaunt_message($data, _e("Данные  отправлены на  рассмотрение"));
                    //redirect(site_url('account/profile_saved'));
                    echo json_encode(['redirect' => site_url('account/profile')]);
                    return;
                } else {
                    $check_wire_bank_fields = $this->payment_data->check_wire_bank_fields($_POST);
                    if(FALSE === $check_wire_bank_fields && 1 == 0)
                        accaunt_message($data, _e("Вы начали заполнять поля Wire Bank. Для сохранения \nнеобходимо заполнены все обязательные или оставить все поля пустыми."), 'error');
                    else {
                        $data = $this->accaunt->update_profile($id);

                        $language_id         = 0;
                        $update_fields       = [];
                        $update_fields_names = $this->payment_data->get_fields_names($language_id, 'machine_name');

                        foreach($update_fields_names as $n => $v){
                            $update_fields[$n] = $this->input->post($n, TRUE);
                        }
                        if(FALSE === $this->payment_data->update_fields($id, $update_fields, $language_id, 'wire')){
                            accaunt_message($data, _e("Ошибка при обновлении данных Wire Bank. Обратитесь в службу поддержки"), 'error');
                            echo json_encode(['redirect' => site_url('account/profile')]);
                            return;
                        }



                        // создание карты
                        $this->load->model('Card_model', 'card');
                        $cards = $this->card->getCards($id);
                        if(empty($cards) && $this->input->post('buy_card') == 1 && config_item('create_card_on_registration')){

                            $user          = new stdClass();
                            $this->accaunt->get_user($user, true);
                            $data          = $this->base_model->user_fields(true);
                            $place         = $this->code->decode($data['place']);
                            $phone         = $_POST['phone_code'].$_POST['phone'];
                            $res           = $data;
                            $res           = ['place1' => $place];
                            $res           = ['place_post' => $this->input->post('place', TRUE)];
                            if(empty($place))
                                $place         = $this->code->decode($user->user->place);
                            $res['place2'] = $place;
                            $country       = $place;
                            if(empty($country)){
                                accaunt_message($data, _e('Не указана страна.'));
                                if(!isset($res))
                                    $res          = [];
                                $res['error'] = 'не указана страна';
                                echo json_encode($res);
                                return;
                            }
                            if(empty($user->user->born)){
                                accaunt_message($data, _e('Не указана дата рождения.'));
                                echo json_encode(['error' => 'не указана дата рождения']);
                                return;
                            }
                            if(!(10 <= strlen($phone) && strlen($phone) <= 15)){
                                accaunt_message($data, _e('Номер телефона не указан или не верный формат необходимо от 10 до 15 цифр.'));
                                echo json_encode(['error' => 'Номер телефона не указан или не верный формат необходимо от 10 до 15 цифр. указаный:'.$phone]);
                                return;
                            }

                            $birth_date = substr($user->user->born, 6, 4).'-'.substr($user->user->born, 3, 2).'-'.substr($user->user->born, 0, 2);


                            $card                    = new stdClass();
                            $card->card_type         = Card_model::CARD_TYPE_VIRTUAL;
                            $card->id_user           = $id;
                            $card->holder_name       = $this->card->generateHolder($user);
                            $card->name              = $user->user->name;
                            $card->surname           = $user->user->sername;
                            $card->birthday          = $birth_date;
                            $card->prop_adress       = trim($user->adr_r->index." ".$user->adr_r->town." ".$user->adr_r->street." ".$user->adr_r->house." ".$user->adr_r->flat);
                            $card->city              = $user->adr_r->town;
                            $card->zip_code          = $user->adr_r->index;
                            $card->country           = $country;
                            $card->phone_mobile      = $phone;
                            $card->phone_home        = $phone;
                            $card->email             = $user->user->email;
                            $card->delivery_address  = trim($user->adr_f->index." ".$user->adr_f->town." ".$user->adr_f->street." ".$user->adr_f->house." ".$user->adr_f->flat);
                            $card->delivery_city     = $user->adr_f->town;
                            $card->delivery_zip_code = $user->adr_f->index;
                            $card->delivery_country  = $country;
                            $res                     = $this->card->create_request($card, $user->user->email);
                            if($res === TRUE){
                                $this->mail->user_sender('get_card', $card->id_user, []);
                                $mes = _e('Спасибо за заказ! Ваша платежная карта Webtransfer Visa Debit Card находится в процессе выпуска и после изготовления будет отправлена на указанный вами адрес. Вы сможете использовать вашу новую пластиковую карту для снятия средств в любом банкомате, подключённом к сети Visa, а также для оплаты покупок в обычных и онлайн-магазинах.<br><br>Данное письмо является подтверждением принятия Вашего заказа. Когда Ваша карта будет готова к отправке, Вы получите еще одно письмо. До этого момента просим Вас загрузить в профиль копию Вашего паспорта и документа, подтверждающего Ваш адрес регистрации или места жительства.<br><br>С уважением, Webtransfer');
                                $this->load->model('inbox_model');
                                $this->inbox_model->writeMess2Inbox($card->id_user, $mes);
                                accaunt_message($data, _e('Данные успешно сохранены.'));
                                $_SESSION['profile_card_create_now'] = TRUE;
                                echo json_encode(['redirect' => site_url('account/profile_saved')]);
                                //echo json_encode(['redirect' => site_url('account/card-lst')]);
                                return;
                            } else {
                                accaunt_message($data, _e('Error: ').$res, 'error');
                                //redirect('account/card/virtual');
                                $_SESSION['profile_card_create_now'] = FALSE;
                                echo json_encode(['redirect' => site_url('account/card/virtual')]);
                                return;
                            }
                        }


                        accaunt_message($data, _e("Данные успешно обновлены и вступили в силу"));
                        echo json_encode(['redirect' => site_url('account/profile_saved')]);
                        return;

                        //else
                        //  return $this->profile_saved ();
                    }
                }
            }
            echo json_encode(['redirect' => site_url('account/profile')]);
            return;
        }

        $this->accaunt->get_user($data);
        $this->payment_data->get_fields_values_for_profile($id, $data->user, 'wire', 0);

        $this->load->helper('form');
        $data->user->id_user  = $this->user->id_user;
        $data->user->nickname = $this->usersFilds->getUserFild($id, 'nickname');
        $data->user->is_show  = $this->usersFilds->getUserFild($id, 'is_show');



        //for JS
        $data->wire_bank_reqired_fileds = json_encode($this->payment_data->get_wire_bank_reqired_fileds());
        $data->phone_sms_attempts       = $this->phone->getSmsAttemps($data->user->id_user);
        if($data->phone_sms_attempts < 0)
            $data->phone_sms_attempts       = 0;

        $data->profile_fieldsets = $this->showProfileForm($data);

        $confirm_email = $this->base_model->getUserField($this->user->id_user, 'account_verification');

        $data->confirm_email = !empty($confirm_email);

        $data->isntAjax = $this->base_model->returnNotAjax();

        if($data->isntAjax){
            $this->content->user_view('profile', $data, '');
        } else {
            if(isset($data->message)){
                $data->message['send'] = str_replace(PHP_EOL, '', $data->message['send']);
                $data->message['send'] = strip_tags($data->message['send']);
                if($data->message['send'])
                    echo "<script>alert('{$data->message['send']}');</script>";
            }

            if($this->accaunt->get_user_id() == 500757){
                $this->content->user_ajax_view('user/accaunt/profile', $data);
            } else {
                $this->load->view('user/accaunt/profile', $data);
            }
        }
    }

    //page
    public function profile_saved(){
        $data = new stdClass();

        viewData()->secondary_menu = "settings";

        $this->accaunt->get_user($data);
        $this->load->helper('form');
        //if($data->isntAjax)
        $this->content->user_view('profile_saved', $data, '');
        //else
        //$this->load->view('user/accaunt/profile_saved', $data);
    }

    public function getcheck($id){// получить  банковский  чек
        if(!isset($id) && empty($id))
            show_404();
        $id   = (int) $id;
        $data = $this->accaunt->getMyPay($id);
        if(empty($data))
            show_404();

        $shablons   = config_item("payment_bank_shablon");
        if(!isset($shablons[$data->metod]))
            show_404();
        $id_shablon = $shablons[$data->metod];

        $bank_fee      = config_item('payment_bank_fee');
        if(!isset($bank_fee[$data->metod]))
            show_404();
        $contributions = $bank_fee[$data->metod];

        $html = $this->db->get_where('shablon', ['id_shablon' => $id_shablon])->row('sh_content');
        $html = $this->mail->user_parcer($html, $data->id_user, [
            'summa'            => number_format($data->summa, 2, ".", " "),
            'id_check'         => $id,
            'transaction_date' => date("d/m/Y"),
            'add'              => $contributions,
            'summa_add'        => number_format(($data->summa + $contributions), 2, ".", " ")
            ]
        );
        if(!empty($html))
            sendPdf($html, "check-$id.pdf");
    }

    public function getcheckarbitrage($id){// получить  банковский  чек
        $id            = (int) $id;
        $data          = $this->accaunt->getMyPay($id);
        $id_shablon    = ("bank" == $data->metod) ? 25 : 28; //26????
        $bank_fee      = config_item('payment_bank_fee');
        $contributions = $bank_fee[$data->metod];

        $html = $this->db->get_where('shablon', ['id_shablon' => $id_shablon])->row('sh_content');
        $html = $this->mail->user_parcer($html, $data->id_user, [
            'summa'            => number_format($data->summa, 2, ".", " "),
            'id_check'         => $id,
            'transaction_date' => date("d/m/Y"),
            'add'              => $contributions,
            'summa_add'        => number_format(($data->summa + $contributions), 2, ".", " ")
            ]
        );
        if(!empty($html))
            sendPdf($html, "check-$id.pdf");
    }

    /**
     * @history
     * - 18.08.2014 esb add late creating ofert [31]
     *
     * @param int $id
     */
    public function getofert($id){ // распечать оферту
        $id      = (int) $id;
        $type    = $this->accaunt->getCreditType($id, TRUE);
        $credit  = $this->accaunt->getCredit($id);
        $id_user = $this->accaunt->get_user_id();

        if(empty($type) ||
            //Вклад - убрать заявку заемщика
            (!empty($credit) &&
            $credit->garant == Base_model::CREDIT_GARANT_ON &&
//              $credit->type == Base_model::CREDIT_TYPE_CREDIT &&
            $credit->id_user != $id_user )
        )
            show_404();

        $path = "upload/kontract/ofert_".((Base_model::CREDIT_TYPE_CREDIT == $type) ? "credit" : "invest")."-{$id}.pdf";
        if(!file_exists($path))
            $this->base_model->createOfert($this->accaunt->getCredit($id));
        $this->code->viewPdf($path, true);
    }

    public function getPaymentDoc($id){ // распечать оферту
        $id = (int) $id;

        $debit = $this->accaunt->getCredit($id);

        $type  = $debit->type;
        $state = $debit->state;
        if(empty($type))
            show_404();


        if(empty($debit) || ( $debit->confirm_payment != 1 && $type == Base_model::CREDIT_TYPE_INVEST && $state == Base_model::CREDIT_STATUS_ACTIVE ) || ( $type == Base_model::CREDIT_TYPE_CREDIT && $state == Base_model::CREDIT_STATUS_PAID &&
            $debit->confirm_return != 1 && $debit->garant == Base_model::CREDIT_GARANT_OFF
            )
        )
            return false;

        $path = "upload/kontract/paymentdoc_".((Base_model::CREDIT_TYPE_INVEST == $type) ? "payment" : "return")."-{$id}.pdf";
        if(!file_exists($path))
            $this->base_model->createPaymentDoc($debit);

        $this->code->viewPdf($path, true);
    }

    /**
     * @history
     * - 18.08.2014 esb add late creating certificate [31]
     *
     * @param int $id
     */
    public function getcertificate($id){ // распечать оферту
        $id     = (int) $id;
        $type   = $this->accaunt->getCreditType($id);
        if(empty($type))
            show_404();
        $path   = "upload/kontract/certificate-$id.jpg";
        $credit = $this->accaunt->getCredit($id);
        if(Base_model::CREDIT_GARANT_ON != $credit->garant)
            show_404();
        if(!file_exists($path))
            $this->base_model->createCertificate($credit);
        if(ob_get_contents())
            ob_end_clean();   //очищает буфер вывода и отключает буферизацию вывода
        header("Content-Type: image/jpeg");
        echo $this->code->fileDecode($path);
    }

    public function credit_doc($id){ // распечатать контракт
        $id     = (int) $id;
        $credit = $this->accaunt->getNumberKontract($id);
        if(!empty($credit)){
            $this->code->viewPdf('upload/kontract/credit_kontract-'.$credit.'.pdf', true);
        } else
            show_404();
    }

    //$redirect link after reloading
    public function delete_credit($id, $redirect = null){
        return $this->delete_invest($id, $redirect);
    }

    //page
    public function choose_photo(){
        $data = new stdClass();
        $this->load->helper('form');
        $this->load->model('users_photo_model', 'users_photo');
        $this->load->library('image');

        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "settings";

        $data->img               = new stdClass();
        $data->avatar_list       = $this->users_photo->getStdAvatarList();
        $data->load_photo_active = FALSE;

        $random_key     = '';
        $data->img->ext = '';
        if(!isset($_SESSION['random_key']) || strlen($_SESSION['random_key']) == 0
        ){
            $random_key               = md5(time() + rand(0, 9999999)); //assign the timestamp to the session variable
            $_SESSION['random_key'] = $random_key;
            $data->img->ext           = "";
        } else {
            $random_key     = $_SESSION['random_key'];
            $data->img->ext = (isset($_SESSION['user_file_ext']) ? $_SESSION['user_file_ext'] : '');
        }
        $data->rnd        = $random_key;
        $data->img->error = 0;

        $data->img->upload_dir       = "upload/tmp";     // The directory for the images to be saved in
        $data->img->upload_path      = $data->img->upload_dir."/";    // The path to where the image will be saved
        $large_image_prefix          = "resize_";    // The prefix name to large image
        $thumb_image_prefix          = "thumbnail_";   // The prefix name to the thumb image
        $data->img->large_image_name = $large_image_prefix.$random_key;     // New name of the large image (append the timestamp to the filename)
        $data->img->thumb_image_name = $thumb_image_prefix.$random_key;     // New name of the thumbnail image (append the timestamp to the filename)
        $max_file                    = "3";        // Maximum file size in MB
        $max_width                   = "350";       // Max width allowed for the large image
        $data->img->thumb_width      = "200";      // Width of thumbnail image
        $data->img->thumb_height     = "200";      // Height of thumbnail image
        // Only one of these image types should be allowed for upload
        $allowed_image_types         = [ 'image/pjpeg' => "jpg", 'image/jpeg' => "jpg", 'image/jpg' => "jpg", 'image/png' => "png", 'image/x-png' => "png", 'image/gif' => "gif"];
        $allowed_image_ext           = array_unique($allowed_image_types); // do not change this
        $image_ext                   = ""; // initialise variable, do not change this.

        $data->img->large_image_location = $data->img->upload_path.$data->img->large_image_name.$data->img->ext;
        $data->img->thumb_image_location = $data->img->upload_path.$data->img->thumb_image_name.$data->img->ext;

        foreach($allowed_image_ext as $mime_type => $ext){
            $image_ext.= strtoupper($ext)." ";
        }

        if(!is_dir($data->img->upload_dir)){
            mkdir($data->img->upload_dir, 0666);
            chmod($data->img->upload_dir, 0666);
        }


        if(!empty($_POST) && isset($_POST['upload']) && !empty($_POST['upload'])){

            $data->load_photo_active = TRUE;

            //Get the file information
//            $userfile_name = $_FILES[ 'image' ][ 'name' ];
            $userfile_tmp  = $_FILES['image']['tmp_name'];
            $userfile_size = $_FILES['image']['size'];
            $userfile_type = $_FILES['image']['type'];
            $filename      = basename($_FILES['image']['name']);
            $file_ext      = strtolower(substr($filename, strrpos($filename, '.') + 1));


            //STEP-1
            //Only process if the file is a JPG, PNG or GIF and below the allowed limit
            if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)){

                $file_format = FALSE;
                foreach($allowed_image_types as $mime_type => $ext){
                    //loop through the specified image types and if they match the extension then break out
                    //everything is ok so go and check file size
                    if($file_ext == $ext && $userfile_type == $mime_type){
                        $file_format = TRUE;
                        break;
                    }
                }
                if(!$file_format){
                    $data->img->error = 1;
                    accaunt_message($data, sprintf(_e('Разрешены только %s форматы изображений'), $image_ext), 'error');
                }
                //check if the file size is above the allowed limit
                if($userfile_size > ($max_file * 1048576)){
                    $data->img->error = 1;
                    accaunt_message($data, sprintf(_e('Размер фотографии дожен быть менее %d MB'), $max_file), 'error');
                }
            } else {
                accaunt_message($data, _e('Выберите файл для загрузки'), 'error');
            }

            //STEP-2
            //Everything is ok, so we can upload the image.
            if($data->img->error <= 0){
                $data->img->large_image_location = $data->img->upload_path.$data->img->large_image_name.'.'.$file_ext;
                $data->img->thumb_image_location = $data->img->upload_path.$data->img->thumb_image_name.'.'.$file_ext;

                if(isset($_FILES['image']['name'])){
                    //put the file ext in the session so we know what file to look for once its uploaded
                    $data->img->ext              = ".".$file_ext;
                    $_SESSION['user_file_ext'] = $data->img->ext;


                    $move_uploaded_file = move_uploaded_file($userfile_tmp, $data->img->large_image_location);

                    if(file_exists($data->img->large_image_location)){
                        chmod($data->img->large_image_location, 0666);

                        $width  = $this->image->getWidth($data->img->large_image_location);
                        $height = $this->image->getHeight($data->img->large_image_location);
                        //Scale the image if it is greater than the width set above

                        if($width > $max_width){
                            $scale    = $max_width / $width;
                            $uploaded = $this->image->resizeImage($data->img->large_image_location, $width, $height, $scale);
                        } else {
                            $scale    = 1;
                            $uploaded = $this->image->resizeImage($data->img->large_image_location, $width, $height, $scale);
                        }
                        //Delete the thumbnail file so the user can create a new one
                        if(file_exists($data->img->thumb_image_location)){
                            unlink($data->img->thumb_image_location);
                        }
                    } else {
                        if($userfile_size == 0)
                            accaunt_message($data, sprintf(_e('Размер фотографии дожен быть менее %d MB'), $max_file), 'error');
                        else {
                            accaunt_message($data, _e("Ошибка загрузки. Обратитесь в службу поддержки"), 'error');
                        }
                    }
                }
//                    Refresh the page to show the new uploaded image
                header("location:".site_url('account/choose_photo'));
                exit();
            }
        } else {

            //Create the upload directory with the right permissions if it doesn't exist
            //Check to see if any images with the same name already exist
            if(file_exists($data->img->large_image_location)){
                if(file_exists($data->img->thumb_image_location)){
                    $data->img->thumb_photo_exists = "<img src=\"".$data->img->upload_path.$data->img->thumb_image_name.$data->img->ext."\" alt=\"Thumbnail Image\"/>";
                } else {
                    $data->img->thumb_photo_exists = "";
                }
                $data->img->large_photo_exists = "<img src=\"".$data->img->upload_path.$data->img->large_image_name.$data->img->ext."\" alt=\"Large Image\"/>";
            } else {
                $data->img->large_photo_exists = "";
                $data->img->thumb_photo_exists = "";
            }
        }


        if($this->input->post("upload_thumbnail") != FALSE
//                && file_exists( $data->img->large_image_location )
        ){
            $data->load_photo_active = TRUE;
            //Get the new coordinates to crop the image.
            $x1                      = $this->input->post("x1", true);
            $y1                      = $this->input->post("y1", true);
//            $x2 = $this->input->post( "x2", true );
//            $y2 = $this->input->post( "y2", true );
            $w                       = $this->input->post("w", true);
            $h                       = $this->input->post("h", true);
            //Scale the image to the thumb_width set above
            $scale                   = $data->img->thumb_width / $w;
            $cropped                 = $this->image->resizeThumbnailImage($data->img->thumb_image_location, $data->img->large_image_location, $w, $h, $x1, $y1, $scale);

            if($this->input->post('rules') != 1){
                accaunt_message($data, _e('Подтвердите согласие с правилами размещения личных фотографий'), 'error');
            } else
            if(!$cropped || $this->users_photo->setUserPhotoUrl(null, $data->img->thumb_image_location))
                accaunt_message($data, _e('Неудалось сохранить изменения. Попробуйте еще раз.'), 'error');
            else {
                $this->users_photo->setUserPhotoStatus(null, 1);
                accaunt_message($data, _e('Изменения успешно сохранены'));

                if(file_exists($data->img->large_photo_exists))
                    unlink($data->img->large_photo_exists);

                if(isset($_SESSION['random_key']))
                    unset($_SESSION['random_key']);
                header("location:".site_url('account/choose_photo'));
                exit();
            }
        }

//        if( isset($_POST["show"]) ){
//            $data->show_status = $this->input->post('show', true);
//            $show_status_value = ( $data->show_status == '1'? 1: 0 );
//            $this->users_photo->setUserPhotoStatus(null, $show_status_value );
//            if( isset( $_SESSION["avatar"] ) )unset($_SESSION["avatar"]);
//        }else
//            $data->show_status = $this->users_photo->getUserPhotoStatus(null);

        if(isset($_POST["collection"])){
            $data->stdavatar_value = $this->input->post('stdavatar', true);

            if(!in_array($data->stdavatar_value, $data->avatar_list)){
                accaunt_message($data, _e('Неизвестный файл изображения аватара'), 'error');
            } else {
                if($this->users_photo->setUserPhotoUrl(null, 'upload/avatars/'.$data->stdavatar_value))
                    accaunt_message($data, _e('Неудалось сохранить изменения. Попробуйте еще раз.'), 'error');
                else
                    accaunt_message($data, _e('Изменения успешно сохранены'));
            }
        }else {
            $img_url         = $this->users_photo->getUserPhotoUrl(null);
            $data->stdavatar = '';
            if(!empty($img_url)){
                $stdavatar       = explode('/', $img_url);
                $data->stdavatar = $stdavatar[count($stdavatar) - 1];
            }
        }

        $data->avatar_active = FALSE;
        if(!in_array($data->stdavatar, $data->avatar_list)){
            $data->load_photo_active = TRUE;
        } else {
            $data->avatar_active = TRUE;
        }

        $data->img->current_large_image_width  = '';
        $data->img->current_large_image_height = '';

        if(strlen($data->img->large_photo_exists) > 0){
            $data->img->current_large_image_width  = $this->image->getWidth($data->img->large_image_location);
            $data->img->current_large_image_height = $this->image->getHeight($data->img->large_image_location);
        }

        if($this->base_model->returnNotAjax())
            $this->content->user_view('choose_photo', $data, '');
        else
            $this->load->view('user/accaunt/choose_photo', $data);
    }

    //$redirect link after reloading
    public function delete_invest($id, $redirect = null){
        $data = new stdClass();
        $id = (int) $id;
        $this->load->model('Fincore_model', 'fincore');
        $result = $this->fincore->delete_loan($id);
        if('error' == $result->status)
            if($redirect == null)
                redirect(site_url('account/my_invest').'?'.time());
            else
                redirect(site_url("account/$redirect").'?'.time());
        else if('success' == $result->status){
            accaunt_message($data, $result->message);

            if($redirect == null)
                redirect(site_url('account/my_invest').'?'.time());
            else
                redirect(site_url("account/$redirect").'?'.time());
        }
        echo 'some error';
    }

    //$redirect link after reloading
    private function delete_contract($id, $redirect = null){
        $data   = new stdClass();
        $id     = (int) $id; //$item->state == 3 and $item->confirm_payment == 2 and $item->confirm_return == 2
        $credit = $this->accaunt->getUserCredit($id);
        if(!($credit->state == 3 and $credit->confirm_payment == 2 and $credit->confirm_return == 2))
            return;
        $this->accaunt->delContract($id);
        accaunt_message($data, _e('Заявка расторгнута'));

        if($redirect == null)
            redirect(site_url('account/my_invest').'?'.time());
        else
            redirect(site_url("account/$redirect").'?'.time());
    }

    public function activePartner(){
        $this->base_model->setType($this->user->id_user, 'partner');
        redirect(site_url('partner/banner'));
    }

    public function about_partner(){
        $data       = new stdClass();
        $data->page = $this->base_model->get_page('partnership');
        $this->content->user_view('about_partner', $data, _e("О партнерстве"));
    }

    //page
    public function inbox(){
        $data           = new stdClass();
        $this->load->model('security_model', 'security_model');
        $data->security = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit');

        $this->load->helper('inbox');
        $data->messages_inbox  = $this->inbox->getInbox();
        $data->messages_outbox = $this->inbox->getOutbox();
        $this->content->user_view('inbox', $data, _e("Уведомления"));
    }

    public function inbox_read($id = 0){
        $news = explode("_", $id);
        if("news" == $news[0]){
            $this->inbox->setReadNews((int) $news[1]);
            return;
        }

        $this->inbox->setRead((int) $id);
    }

    public function inbox_cancel($id = 0){
        $this->inbox->cancel((int) $id);
    }

    public function inbox_delete($id = 0){ $news = explode("_", $id);
        if("news" == $news[0]){
            $this->inbox->deleteNews((int) $news[1]);
            return;
        }
        $this->inbox->delete((int) $id);
    }

    public function inbox_agree($id = 0, $code_in = null){
        //$this->inbox->agree((int) $id);

        $this->load->model('inbox_model', 'inbox');
        $item  = $this->inbox->getMessage((int) $id);
        $debit = $this->accaunt->creditTicket($item->debit, Base_model::CREDIT_TYPE_INVEST, FALSE);
        $this->load->model('security_model', 'security_model');
        $this->load->model('phone_model', 'phone');

//        $code = $code_in;
//        $match = array();
//        if( $code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset( $match[0] ))
//        {
//            $code = $match[0];
//        }

        require_once APPPATH.'controllers/user/Security.php';
        $user_id = $this->accaunt->get_user_id();

        $this->load->model('users_model', 'users');

        $res = [];
        if(!empty($debit)){
            $user_ratings = viewData()->accaunt_header;

            $summa = $debit->summa;
            if(!$this->accaunt->isUserAccountVerified()){
                $res['error'] = _e('Для подачи заявки необходимо верифицировать Профиль.');
                //} else if ($this->accaunt->isUS2USorCA($debit->id_user)) {
//                $res['error'] = _e('Данная операция между участниками US/Canada запрещена');
            } else {
                $garant    = $debit->garant;
                $bonus     = $debit->bonus;
                $overdraft = $debit->overdraft;


                // директ на карту
                if($debit->bonus == 7 && $debit->direct == 1){
                    //     echo json_encode(['state' => 'no-data']);
                    //      return;
                    $this->load->model('card_model', 'card');
                    $send_transaction              = new stdClass();
                    $send_transaction->summa       = $debit->summa;
                    $send_transaction->id_user     = $item->debit_account_id; //to card
                    $send_transaction->own         = $debit->id_user; //from user
                    $send_transaction->own_card_id = $debit->card_id; //from card
                    $send_transaction->note        = 'Take invest #'.$debit->id;


                    // установим пароль вкладчику
                    $card = $this->card->getCard($debit->card_id);
                    if(!empty($card))
                        $this->card->setPassword($card);


                    $error = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_TAKE_INVEST, $debit->id);
                    //var_dump( $error );
                    //$error = 'OK';
                    if("OK" == $error){
                        $answer1 = $this->inbox->manual_agree($debit, $item->user_from, 7, 7, $item->debit_account_id);
                        if($answer1){
                            switch($answer1){
                                case 1: $res['error'] = _e('Не удалось одобрить заявку.');
                                    $this->send_ajax_responce($res);
                                    break;
                                case 2: $res['error'] = _e('Займ неактивен.');
                                    $this->send_ajax_responce($res);
                                    break;
                            }
                            return;
                        }

                        // pri sozdanii vklada s bonus 7 time >= 10 dney - dayom bonus 20% na bonus 1.
                        if($debit->time >= 10){
                            $this->load->model("transactions_model", 'transactions');
                            /*
                              от $100 и выше - 20%;
                              от $300 и выше - 30%;
                              от $600 и выше - 40%;
                              от $1 000 - 50%
                             */
                            $bonus_perc = 0;
                            if($debit->summa >= 100 && $debit->summa < 300){
                                $bonus_perc = 20;
                            } elseif($debit->summa >= 300 && $debit->summa < 600){
                                $bonus_perc = 30;
                            } elseif($debit->summa >= 600 && $debit->summa < 1000){
                                $bonus_perc = 40;
                            } elseif($debit->summa >= 1000)
                                $bonus_perc = 50;

                            if($bonus_perc > 0)
                                $this->transactions->addPay($debit->id_user, round($debit->summa * ($bonus_perc / 100), 2), 98, $debit->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, "Бонус за займ через Webtransfer Visa Card");
                        }

                        $res['success'] = _e('Вы выдали займ Директ.');
                        $this->send_ajax_responce($res);
                        return;
                        /* $this->inbox_model->writeInbox($this->accaunt->get_user_id(), $debit->id_user, 'ready-give-credit', $debit->id, 1);
                          //$this->inbox_model->writeInbox($debit->id_user, $this->accaunt->get_user_id(), 'ready-give-credit', $debit->id, 1);
                          $garant_or_standart = ($debit->garant==0)?_e('Стандарт'):_e('Гарант');
                          $this->load->model('users_model', 'users');
                          $credit_user = $this->users->debit_user($debit->id_user);
                          $this->mail->user_sender('take_credit_over_card_debit_user', $this->accaunt->get_user_id(), [
                          'summa' => $debit->summa,
                          'garant_or_standart'=>$garant_or_standart,
                          'credit_user_fio' => $credit_user->sername.' '.$credit_user->name
                          ]); // отправим почту тому кто дал кредите
                          $debit_user = $this->users->debit_user($this->accaunt->get_user_id());
                          $this->mail->user_sender('take_credit_over_card_credit_user', $debit->id_user, [
                          'summa' => $debit->summa,
                          'garant_or_standart'=>$garant_or_standart,
                          'debit_user_fio' => $debit_user->sername.' '.$debit_user->name
                          ]); // отправим почту тому кто взял кредит
                         */
                    } else {
                        $res['error'] = _e('Ошибка: '.$error);
                        $this->send_ajax_responce($res);
                        return;
                    }
                }
                // директ на свой счет
                if($debit->bonus == 2 && $debit->direct == 1){

                    $responce = $this->inbox->manual_agree($debit, $item->user_from, 2, 2, NULL, $item->debit_account_type, $item->debit_account_id);
                    switch($responce){
                        case 0: $res['success'] = _e('Вы выдали займ Директ.');
                            break;
                        case 1: $res['error']   = _e('Ошибка: Заявка отсутствует. Обновите страницу.');
                            break;
                        case 2: $res['error']   = _e('На расчетном счете недостаточно средств.');
                            break;
                        default:
                            $res['error']   = _e('Ошибка: ').$responce;
                    }
                    $this->send_ajax_responce($res);
                    return;
                }


                if($garant == 1){//гарант
                    if($bonus == 0){//деньги
                        if(($overdraft == 0) &&
                            $user_ratings['all_advanced_invests_summ'] +
                            $user_ratings['all_advanced_standart_invests_summ'] + $summa > $user_ratings['payment_account']){
                            $res['error'] = _e('На вашем платежном счете недостаточно средств для совершения данной операции.');
                        } else {
                            $responce = $this->inbox->manual_agree($debit, $item->user_from, Base_model::CREDIT_BONUS_OFF);

                            switch($responce){
                                case 0: $res['success'] = _e('Вы выдали займ Стандарт.');
                                    break;
                                case 1: $res['error']   = _e('Ошибка: Заявка отсутствует. Обновите страницу.');
                                    break;
                                //case 2: $res['error'] = 'На расчетном счете недостаточно средств.';
                                //    break;
                                default:
                                    $res['error']   = _e('Ошибка: ').$responce;
                            }
                        }
                    } else {//бонусы
                        if($summa > $user_ratings['bonuses'] - $user_ratings['all_advanced_invests_bonuses_summ']){
                            $res['error'] = _e('На вашем бонусном счете недостаточно средств для совершения данной операции.');
                        } else {
                            $responce = $this->inbox->manual_agree($debit, $item->user_from, Base_model::CREDIT_BONUS_OFF);

                            switch($responce){
                                case 0: $res['success'] = _e('Вы выдали займ Стандарт.');
                                    break;
                                case 1: $res['error']   = _e('Ошибка: Заявка отсутствует. Обновите страницу.');
                                    break;
                                //case 2: $res['error'] = 'На расчетном счете недостаточно средств.';
                                //    break;
                                default:
                                    $res['error']   = _e('Ошибка: ').$responce;
                            }
                        }
                    }
                } else {//стандарт
                    $secur = Security::checkSecurity($user_id, true);
                    if($secur){
                        $error = '';
                        switch($secur){
                            case 1: $error = _e('Коды не совпадают');
                            case 2: $error = _e('Ошибка передачи данных. Перезагрузите страницу и попробуйте снова.');
                                break;
                            case 3: $error = _e('Вы не указали СМС-код.');
                                break;
                            case 4: $error = _e('Номер телефона не задан.');
                                break;
                            case 5: $error = _e('Номер телефона не верифицирован');
                                break;
                            case 6: $error = _e('Код еще не был отправлен. Сначала запросите СМС-код.');
                                break;
                            case 7: $error = _e('Вы исчерпали количество попыток ввода. Запросите новый СМС-код.');
                                break;
                            case 8: $error = _e('Неверный СМС-код');
                                break;
                        }
                        $res['error'] = $error;
                    } else {
                        $responce = $this->inbox->manual_agree($debit, $item->user_from, Base_model::CREDIT_BONUS_OFF);

                        switch($responce){
                            case 0: $res['success'] = _e('Вы выдали займ Стандарт.');
                                break;
                            case 1: $res['error']   = _e('Ошибка: Заявка отсутствует. Обновите страницу.');
                                break;
                            //case 2: $res['error'] = 'На расчетном счете недостаточно средств.';
                            //    break;
                            default:
                                $res['error']   = _e('Ошибка: ').$responce;
                        }
                    }
                }
            }
        } else
            $res['error'] = _e('Ошибка: Заявка отсутствует. Обновите страницу.');

        $this->send_ajax_responce($res);
    }

    /** Return user data
     *
     * @param type $func_name: user_data|
     * NOTICE!
     * For creating new function, you need call url = '/account/ajax_user/new_function'
     * ajax_new_function - is a new function which processes user data
     */
    public function ajax_user(){
//redirect if it's not an AJAX request
        $this->base_model->redirectNotAjax();

        $arg_num = func_num_args();
        if($arg_num < 1)
            redirect(site_url('/'));

        $arg_list  = func_get_args();
        $func_name = 'ajax_'.$arg_list[0];

//there is no such method
        if(!method_exists($this, $func_name))
            redirect(site_url('/'));

        $func_params = NULL;
        if($arg_num > 1){
            $func_params   = [];
            for($i = 1; $i < $arg_num; $i++)
                $func_params[] = $arg_list[$i];
        }
        $this->{$func_name}($func_params);
    }

    /**
     * Show user documents with watermarks
     *
     * @param type $user_id
     */
    public function application_doc($user_id, $doc_num = 1, $credit_id = 0){

        if(empty($user_id)){
            //echo "Credit-inbox-0";
            return FALSE;
        }
        $this->load->model('credits_model', 'credits');

        $current_user_id = $this->accaunt->get_user_id();

        $credit = $this->credits->getUserStandartCredit($user_id);

        if(!empty($credit_id)){
            $credit_by_id = $this->credits->getDebit($credit_id);
        }

        $inbox = $this->inbox->getMessagesByUsers($user_id, $current_user_id);

        $show_docs = FALSE;

        if(!empty($inbox) || !empty($credit_by_id)){
//            echo "Credit-inbox-1";
            $show_docs = TRUE;
        }

        if(empty($credit) && empty($inbox)){
//            echo "Credit-inbox-2";
            $show_docs = FALSE;
        }

        if($show_docs === FALSE){
//            echo "Credit-inbox-1-1";
            return false;
        }

        $this->load->model('documents_model', 'documents');
        $this->load->library('image');

        $doc_name = $this->documents->get_doc_file_name_by_user_id($user_id, $doc_num);

        if(empty($doc_name)){
//            echo "No docs";
            return FALSE;
        }

        $fileDecoded = $this->code->fileDecode("upload/doc/".$doc_name);
        if(empty($fileDecoded)){
//            echo "File is empty";
            return FALSE;
        }
        ob_end_clean();
        $ext = substr($doc_name, count($doc_name) - 4, 3);
        if($ext == 'pdf'){
            header("Content-Type: application/pdf");
            echo $fileDecoded;

            return;
        }
        echo $this->image->getImageContentWithWarterMark($fileDecoded, "webtransfer-finance.com ID {$user_id}", '#ad2222', 1);
    }

    public function ajax_get_user_data($params){
        if(empty($params))
            redirect(site_url('/'));


        $type    = $params[0] | NULL;
        $id_user = $params[1] | NULL;
        $id      = $params[2] | NULL;

        $id_user = intval($id_user);
        $data    = $this->base_model->getUserInfo($id_user);

        if($id_user === 400400){
            echo "<script>$('#user_popup div.close').click()</script>";
            return FALSE;
        }

        if(empty($data)){
            echo '<p>'._e('Не удалось получить данные').'</p>';
            return;
        }


        $data->id = (int) $id;
        $social   = $this->accaunt->getSocialList($id_user);

        $user_avatar       = null;
        $data->social_list = '';



        $current_user_id = $this->accaunt->get_user_id();
        $debit           = null;
        $show_docs       = FALSE;
        if(!empty($data->id)){
            $debit = $this->accaunt->getCredit($data->id);
        }

        //только Стандарт
        if(!empty($debit) && $debit->garant == Base_model::CREDIT_GARANT_OFF){

            if(( $debit->type == Base_model::CREDIT_TYPE_INVEST && $debit->id_user == $current_user_id ) || //Вложения, Стандарты в заявках
                $debit->state == Base_model::CREDIT_STATUS_SHOWED && $debit->type == Base_model::CREDIT_TYPE_CREDIT //Займы на бирже
            ){
                $show_docs = TRUE;
            }
        }

        $user_avatar = getUserAvatar($id_user);
        foreach($social as $item){

            if(!empty($item->url) /* && !empty( $data->id ) */){
                if($show_docs === TRUE){
                    $data->social_list .= '<a target="_blank" class="user_social" href="'.$item->url.'"><img src="/images/icons/'.socialList($item->name).'"></a>';
                } else
                    $data->social_list .= '<a class="user_social" onclick="return false"><img src="/images/icons/'.socialList($item->name).'"></a>';
            }
        }

        if($show_docs === TRUE){
            $this->load->model('documents_model', 'documents');
            $doc_name = $this->documents->get_doc_file_name_by_user_id($id_user, 1, 2);

            if(FALSE !== $doc_name)
                $data->social_list .= '<a target="_blank" class="user_social" href="/ru/account/application_doc/'.$id_user.'/1/'.$id.'">'.
                    '<img  title="'._e('Посмотреть документ').'"  src="/img/passport.png">'.
                    '</a>';
            else
                $data->social_list .= '<a target="_blank" class="user_social" onclick="return false;"><img  title="'._e('Документ не загружен').'"  src="/img/no_passport.png"></a>';

            $another_doc_name = $this->documents->get_doc_file_name_by_user_id($id_user, 5);

            if(FALSE !== $another_doc_name){
                $data->social_list .= '<a target="_blank" class="user_social" href="/ru/account/application_doc/'.$id_user.'/5/'.$id.'">'.
                    '<img  title="'._e('Посмотреть документ').'"  src="/images/PDF-Ripper.gif" style="width:32px;">'.
                    '</a>';
            }
        }

        $data->user_avatar = (empty($user_avatar) ? '/img/no-photo.gif' : $user_avatar);

//		$where = array('id_user'=>$id_user, 'type'=>2);
//        $data->summaCredit = $this->base_model->getSummaDebitsUser($where, array(3, 5, 6));
//        $data->summaInvest = $this->base_model->getSummaDebitsUser($where, array(3, 5, 6));

        $ratingOwn = viewData()->accaunt_header;
        $rating    = $this->accaunt->recalculateUserRating($id_user);

        $this->load->model('user_balans_model', 'user_balans');
        $fsr       = $this->user_balans->generateNewFsrToBot($id_user);
        if($fsr !== FALSE)
            $data->fsr = $fsr;
        else
            $data->fsr = $rating['fsr'];

        // определяем сколько звезд у данной степени диверсификации

        $data->diversification_degree = $rating['diversification_degree'];

        $month_partner_unic_id_count = $rating['month_partner_unic_id_count'];
        $data->dd_rate_start         = 0;
        if($month_partner_unic_id_count > 300){
            $data->dd_rate_start = 6;
        } else
        if($month_partner_unic_id_count > 100){
            $data->dd_rate_start = 5;
        } else
        if($month_partner_unic_id_count > 50){
            $data->dd_rate_start = 4;
        } else
        if($month_partner_unic_id_count > 30){
            $data->dd_rate_start = 3;
        } else
        if($month_partner_unic_id_count > 20){
            $data->dd_rate_start = 2;
        } else
        if($month_partner_unic_id_count > 10){
            $data->dd_rate_start = 1;
        }


        $data->max_loan_available = $rating['max_loan_available']; // очень не секретно - можно узнать у кого сколько денег на кашельке и потом делать атаку

        $data->debit = $debit;

        $data->showButton = TRUE;

        $this->load->model("visualdna_model", "visualdna");
        $data->visualdnaStatus   = $this->visualdna->getStatus($id_user);
        $data->visualdnaMyStatus = $this->visualdna->getStatus($current_user_id);
        $data->current_user_id   = $current_user_id;


        if($type == 1){
            $this->load->view('user/accaunt/applicationsCredit', ['user' => $data]);
        } else if($type == 2){

            $summa            = intval($debit->summa);
            if($debit->garant == '0' &&
                $summa >= 50 &&
                $summa > $ratingOwn['payment_account'] - $ratingOwn['investments_standart'] - $ratingOwn['investments_garant'])
                $data->showButton = FALSE;

            $this->load->view('user/accaunt/applicationsInvest', ['user' => $data]);
        }
    }

    protected function send_ajax_responce($message){
        if(!is_string($message) && is_array($message))
            $responce = json_encode($message);
        else
            $responce = $message;

        echo $responce;
    }

    public function ajax_receiveWaitingInvest(){
        $this->load->model('Card_model', 'card');
        $this->load->model("transactions_model", "transactions");

        $id      = $this->input->post('id');
        $card_id = $this->input->post('card_id');
        $user_id = get_current_user_id();

        $transaction = getTransactionByParams(['id' => $id], TRUE);
        if(empty($transaction) || $transaction->status == Base_model::TRANSACTION_STATUS_DELETED){
            return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось найти параметры зачисления')]);
        }

        if($user_id != $transaction->id_user){
           return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось транзакцию')]);
        }


        $card = $this->card->getUserCard($card_id, $user_id);
        if(empty($card)){
            return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось найти карту')]);
        }

        $offer = $this->accaunt->getCredit($transaction->value);
        if(empty($offer)){
                return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось найти вклад')]);
        }


        if ( $transaction->type == 24){
            if($offer->garant == Base_model::CREDIT_GARANT_OFF)
                $wt_perc_summ = round($offer->income * (10 / 100), 2);
            else
                $wt_perc_summ = round($offer->income * (garantPercent($offer->time) / 100), 2);

            $load_data          = new stdClass();
            $load_data->id      = 'RI_'.$offer->id;
            $load_data->card_id = $card_id;
            $load_data->user_id = $user_id;
            $load_data->summa   = $offer->summa + $offer->income - $wt_perc_summ;
            $load_data->desc    = 'Return of loan #'.$offer->id.'(total $'.($offer->summa + $offer->income).' less fee $'.$wt_perc_summ.')';
            $response           = $this->card->load($load_data, Card_transactions_model::CARD_RETURN_INVEST, $offer->id);
            if(false !== $response){
                $this->base_model->addContributions($offer);
                $this->transactions->setStatus($id, Base_model::TRANSACTION_STATUS_DELETED);
                
                $this->load->model('Fincore_model', 'fincore');
                $this->fincore->autoReturnInvesterLoans($user_id, $card_id);                            
                return $this->send_ajax_responce([ 'status' => 'success', 'message' => _e('Успешно зачислено')]);
            } else {
                return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось перечислить средства:').$response]);
            }
        }

        if ( $transaction->type == Transactions_model::TYPE_PARENT_INCOME || $transaction->type == Transactions_model::TYPE_VOLUNTEER_INCOME){

            $load_data          = new stdClass();
            $load_data->id      =  'IRTO_'.$offer->id.'_'.rand(1,100000);;
            $load_data->card_id = $card_id;
            $load_data->user_id = $user_id;
            $load_data->summa   = $transaction->summa;
            $load_data->desc    = $transaction->note_admin;
            $response           = $this->card->load($load_data, ( $transaction->type == Transactions_model::TYPE_PARENT_INCOME )?Card_transactions_model::CARD_TRANS_PARTNER_RAWARD:Card_transactions_model::CARD_TRANS_VOLUNTEER_RAWARD, $transaction->id);
            if(false !== $response){
                $this->transactions->setStatus($id, Base_model::TRANSACTION_STATUS_DELETED);
                return $this->send_ajax_responce([ 'status' => 'success', 'message' => _e('Успешно зачислено')]);
            } else {
                return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось перечислить средства:').$response]);
            }

        }

        if ( $transaction->type == 16 && $transaction->bonus == 7 && $transaction->metod=='wtcard' && $transaction->status == Base_model::TRANSACTION_STATUS_NOT_RECEIVED ){

            $load_data          = new stdClass();
            $load_data->id      =  'BRI_'.$transaction->id.'_'.rand(1,100000);;
            $load_data->card_id = $card_id;
            $load_data->user_id = $user_id;
            $load_data->summa   = $transaction->summa;
            $load_data->desc    = $transaction->note_admin;
            $response           = $this->card->load($load_data,  Card_transactions_model::CARD_RETURN_INCOME, $transaction->id);
            if(false !== $response){
                $this->transactions->setStatus($id, Base_model::TRANSACTION_STATUS_DELETED);
                return $this->send_ajax_responce([ 'status' => 'success', 'message' => _e('Успешно зачислено')]);
            } else {
                return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Не удалось перечислить средства:').$response]);
            }

        }

        return $this->send_ajax_responce([ 'status' => 'error', 'message' => _e('Неизветный тип для зачисления:').$transaction->type]);

    }

    public function ajax_phone_request(){
        $this->load->model('phone_model', 'phone');

        $user_id    = $this->input->post('user_id');
        $phone      = $this->input->post('phone');
        $code_in    = $this->input->post('code', true);
        $short_name = $this->input->post('short');


        //'min_length[11]|max_length[14]|regex_match[/^[0-9]{11,14}$/sui]'
        if(empty($user_id) || empty($phone) || empty($code_in) || empty($short_name))
            return $this->send_ajax_responce(_e('Ошибка передачи данных'));

        $code  = $code_in;
        $match = [];
        if($code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset($match[0])){
            $code = $match[0];
        }


        $phone_matches        = [];
        $phone_preg_match_res = preg_match('/(\d)[7,14]/', $phone, $phone_matches);
        if($phone_preg_match_res == 1 && strlen($phone_matches[1]) == strlen($phone))
            return $this->send_ajax_responce(_e('Введен некорректный телефон'));

        $user_id = intval($user_id, 10);

//        $this->form_validation->set_rules('phone', 'query', 'numeric|required');
//        $this->form_validation->set_rules('user', 'query', 'numeric|required');
//        if ($this->form_validation->run() == TRUE)

        $resp = $this->phone->sendRequestCode($code, $short_name, $phone, $user_id);
        $this->send_ajax_responce($resp);
    }

    public function ajax_set_phone(){
        $this->load->model('phone_model', 'phone');

        $user_id = $this->accaunt->get_user_id();
        $phone   = $this->input->post('phone');
        $code_in = $this->input->post('pcode', true);

        $short_name = $this->input->post('short');

        $this->load->model('phone_model', 'phone');
        $get_phone_by_user_id = $this->phone->get_phone_by_user_id($user_id);

        $security_check = Security::checkSecurity($user_id, FALSE, TRUE);

        #устновить если номер телефона - пуст
        if( (!empty( $get_phone_by_user_id ) && !empty( $get_phone_by_user_id->phone_number )) &&
            (!is_bool($security_check) || $security_check == TRUE) )
        {

            return $this->send_ajax_responce(['error' => $security_check]);
        }

        //'min_length[11]|max_length[14]|regex_match[/^[0-9]{11,14}$/sui]'
        if(empty($user_id) || empty($phone) || empty($code_in) || empty($short_name))
            return $this->send_ajax_responce(_e('Ошибка передачи данных'));

        $code  = $code_in;
        $match = [];
        if($code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset($match[0])){
            $code = $match[0];
        }


        $phone_matches        = [];
        $phone_preg_match_res = preg_match('/(\d)[7,14]/', $phone, $phone_matches);
        if($phone_preg_match_res == 1 && strlen($phone_matches[1]) == strlen($phone))
            return $this->send_ajax_responce(_e('Введен некорректный телефон'));

        $isStatusVerified           = $this->phone->isStatusVerified($user_id);
//        $this->form_validation->set_rules('phone', 'query', 'numeric|required');
//        $this->form_validation->set_rules('user', 'query', 'numeric|required');
//        if ($this->form_validation->run() == TRUE)
        //if( ! ){
        $setting_up_phone           = $this->phone->setPhoneWithCode($user_id, $code, $short_name, $phone);
        $voice_verification_enabled = $this->phone->is_voice_verification_enabled($user_id);
        $res                        = [];
        switch($setting_up_phone){
            case 0:
                if($isStatusVerified){
                    $res = ['success' => 'Новый номер сохранен. Верификация снята.'];
                } else {
                    $res = ['success' => 'OK', 'data' => [ 'voice_verification_enabled' => $voice_verification_enabled]];
                    $this->load->model('accaunt_model', 'accaunt');
                    $this->accaunt->payBonusesToPartner($user_id);
                }
                break;
            case 2:
                if($isStatusVerified){
                    $res = ['success' => 'OK'];
                } else {
                    $res = ['error' => _e('Телефон уже используется другим пользователем. Введите другой номер.')];
                }
                break;
            default: $res = ['error' => _e('Не удалось сохранить номер телефона. Попробуйте еще раз.')];
        }
        return $this->send_ajax_responce($res);
//        }else{
//            return $this->send_ajax_responce([ 'error' => _e('Номер уже подтвержден') ]);
//        }
        //$this->send_ajax_responce([ 'success' => 'OK' ]);
    }

    public function ajax_phone_confirm(){
        $this->load->model('phone_model', 'phone');
        // настройки аккаунта clickatell.com
        //echo 'Отправка СМС';

        $user_id = $this->accaunt->get_user_id();
        $code_in = $this->input->post('code');

        if(empty($user_id) || empty($code_in))
            return $this->send_ajax_responce([ 'error' => _e('Ошибка передачи данных')]);

        $code  = $code_in;
        $match = [];
        if($code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset($match[0])){
            $code = $match[0];
        }

        $resp = $this->phone->confirmCode($code, $user_id);
//        var_dump($resp)
        if(!$resp)
            return $this->send_ajax_responce([ 'error' => _e('Ошибка передачи данных')]);
        else
            return $this->send_ajax_responce($resp);
    }

    //заявка на Арбитраж мануал
    private function ajax_get_credit_calc(){
        //redirect if it's not an AJAX request
        $this->base_model->redirectNotAjax();

        if(empty($_POST)){
            echo json_encode([ 'error']);
            return;
        }
        $id = $this->input->post('id', true);

        $this->load->model('credits_model', 'credits');
        $new_summ = $this->credits->getNewCreditSumm($id);

        if(empty($new_summ)){
            echo json_encode([ 'error']);
            return;
        }

        echo json_encode([ 'success' => $new_summ]);
        return;
    }

    private function ajax_applications_table($param){

        //redirect if it's not an AJAX request
        $this->base_model->redirectNotAjax();
        if(empty($param))
            redirect(site_url('/'));

        $this->load->model('Card_model', 'card');
        $this->load->model('Credits_model', 'credits');

        $type = $param[0] | NULL;


        $length  = $this->input->post('length', TRUE);
        $start   = $this->input->post('start', TRUE);
        $sort    = $this->input->post('order', TRUE);
        $filters = $this->input->post('filter', TRUE);
        $columns = $this->input->post('columns', TRUE);


        $search_data = [];
        if(!empty($columns)){
            foreach($columns as $key => $column){

                if(!empty($sort[$key])){
                    $sort[$key]['column'] = $columns[$sort[$key]['column']]['data'];
                    if($sort[$key]['column'] == 'type')
                        $sort[$key]['column'] = 'bonus';
                }

                if($column['search']['value'] != ''){
                    $name = $column['data'];
                    switch($name){
                        case 'id':
                            $search_data['id'] = (int) $column['search']['value'];
                            break;
                        case 'type':

                            switch($column['search']['value']){
                                case 'USDh':
                                    $search_data['bonus']        = 2;
                                    $search_data['account_type'] = 'bonus';
                                    break;
                                case 'USD1':
                                    $search_data['bonus']        = 5;
                                    $search_data['account_type'] = 'bonus';
                                    break;
                                case 'USDB':
                                    $search_data['bonus']        = 1;
                                    $search_data['account_type'] = 'bonus';
                                    break;
                                case 'USDD':
                                    $search_data['bonus']        = 6;
                                    $search_data['account_type'] = 'bonus';
                                    $search_data['direct'] = 0;
                                    break;
                                case 'USDP':
                                    $search_data['bonus']        = 3;
                                    $search_data['account_type'] = 'bonus';
                                    break;
                                case 'USDC':
                                    $search_data['bonus']        = 4;
                                    $search_data['account_type'] = 'bonus';
                                    break;
                                default:
                                    $search_data['account_type'] = $column['search']['value'];
                                    break;
                            }

                            break;
                        case 'garant':
                            $search_data['garant']   = (int) $column['search']['value'];
                            break;
                        case 'summa':
                            $search_data['summa']    = (float) $column['search']['value'];
                            break;
                        case 'time':
                            $search_data['time']     = (int) $column['search']['value'];
                            break;
                        case 'percent':
                            $search_data['percent']  = (float) $column['search']['value'];
                            break;
                        case 'out_summ':
                            $search_data['out_summ'] = (float) $column['search']['value'];
                            break;
                    }
                }
            }
        }
        //var_dump($sort);
        //var_dump($search_data);
        //$limit = array( $length, $start );
        //$res_search = $this->credits->getAllCreditsForApplications($type, $params,$search_data, $limit, $sort, $filters);
        $this->load->model('Fincore_model', 'fincore');
        $result = $this->fincore->requests_list($type, Base_model::CREDIT_STATUS_SHOWED, $start, $length, $sort, $search_data);



        $rows = [];
        foreach($result->data as $val){
            $res = new stdClass();

            //col 1
            $res->DT_RowId = 'row_'.$val->id;
            $res->id_user  = $val->id_user;
            $res->id       = $val->id;
            //$res->id_str  = $val->id;
//                $res->id_str .=  '<input type="hidden" name="id" value="'.$val->id.'">';
            //col 2
            $res->type     = $val->account_type;
            switch($res->type){
                case 'card':
                    $res->type = '<img src="/images/pcard-sm.png" style="vertical-align:middle">';
                    break;
                case 'BANK_CARD':
                    $res->type = '<img src="/images/'.$val->account_type.'.png" style="vertical-align:middle"> ';
                    break;
                case 'BANK_ACCOUNT':
                    $res->type = '<img src="/images/'.$val->account_type.'.png" style="vertical-align:middle"> ';
                    break;
                case 'bonus':
                    switch($val->bonus){
                        case 1:
                            $res->type = '<img src="/images/debit.png">';
                            break;
                        case 2:
                            $res->type = 'WTUSD<span style="color:red">&#10084;</span>';
                            break;
                        case 3:
                            $res->type = '<img src="/images/wt-pcreds-card.png">';
                            break;
                        case 4:
                            $res->type = '<img src="/images/wt-creds-card.png">';
                            break;
                        case 5:
                            $res->type = 'WTUSD1';
                            break;
                        case 6:
                            $res->type = '<img src="/images/debit.png">';
                            break;
                    }

                    break;
                default:
                    if(substr($res->type, 0, 8) == 'E_WALLET'){
                        $own_acc   = $this->card->getUserOther($val->account_id, $val->id_user);
                        //$res->type = '';//$val->account_id.','.$val->id_user;
                        $res->type = '<img src="/images/E_WALLET.png" style="vertical-align:middle"> ';
                        if(empty($own_acc))
                            continue;
                        if(empty($own_acc->own_wallet))
                            $res->type = '<img src="/images/'.$own_acc->account_extra_data.'.png" style="vertical-align:middle"> ';
                        else
                            $res->type = '<img src="/images/E_WALLET.png" style="vertical-align:middle"> ';
                        break;
                    }

                    switch($val->bonus){
                        case 1:
                            $res->type = 'BONUS';
                            break;
                        case 2:
                            $res->type = 'WTUSD<span style="color:red">&#10084;</span>';
                            break;
                        case 3:
                            $res->type = 'P-CREDS';
                            break;
                        case 4:
                            $res->type = 'C-CREDS';
                            break;
                        case 5:
                            $res->type = 'WTUSD1';
                            break;
                        case 6:
                            $res->type = 'WTDEBIT';
                            break;
                        case 7:
                            $res->type = '<img src="/images/pcard-sm.png" style="vertical-align:middle">';
                            break;
                    }
                    break;
            }


            //col 3
            if($val->garant == 0)
                $res->garant = '<b style="color:blue">S</b>';
            else
                $res->garant = '<b style="color:orange">G</b>';

            //col 4
            $res->summa = $val->summa;

            //col 5
            $res->time = $val->time;

            //col 6
            $res->percent = $val->percent;

            // col 7
            $res->out_summ = $val->out_summ;

            $res->status_action .= '<input type="hidden" name="id" value="'.$val->id.'">';

            $rows[] = $res;
        } //end foreach
//            if( count( $res_search ) > $length ) break





        echo json_encode([
            'draw'            => $_POST['draw'],
            'recordsTotal'    => $result->recordsTotal,
            'recordsFiltered' => $result->recordsTotal,
            'data'            => $rows]
        );
    }

    private function _ajax_del_user_parent(){
        $this->load->model('users_model', 'users');
        $id = (int) $this->input->post("id");
        if(empty($id)){ echo "error"; return; }
//        $me = $this->users->getParent($id);
        if($this->users->getCurrUserId() == $this->users->getParent($id)){
            $this->users->delParent($id);
            echo 'ok';
        } else
            echo "error";
    }

    /*     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     *  Получение платежных данных о пользователе по id
     *  @param string  id пользователя
     * 	return шаблон реквизитов
     */

    public function user_payment_info($id_user){
        $id_user = intval($id_user);
        $user    = $this->base_model->getUserPayment($id_user);

        $this->load->model('users_filds_model', 'usersFilds');
        $nickname = $this->usersFilds->getUserNickname($user->id_user);
        if(FALSE !== $nickname){
            $user->name    = $nickname;
            $user->sername = '';
        }

        $this->load->view('user/accaunt/user_rekvizit', ['user' => $user]);
    }

    /* public function share()
      {

      require_once APPPATH.'libraries/facebook/facebook.php';

      //get the Facebook appId and app secret from facebook.php which located in config directory for the creating the object for Facebook class
      $facebook = new Facebook(array(
      'appId'		=>  config_item('appID'),
      'secret'	=> config_item('appSecret'),
      ));

      $social =  $this->accaunt->get_social()	;
      $post_user_id = $social['facebook'];

      $args = array(
      'access_token'  => $facebook->getAccessToken(),
      'message'       => "ПРИВЕТ",
      'link'          => base_url(),
      'from'          => config_item('appID'),
      'to'            => "friends",
      );
      $post_id = $facebook->api("/".$post_user_id."/feed","post",$args);

      } */

    public function social(){
        $data                      = new stdClass();
        viewData()->page_name      = __FUNCTION__;
        viewData()->secondary_menu = "settings";
        $data->socials             = $this->accaunt->get_social();
        $data->socialList          = socialList();
        if($this->base_model->returnNotAjax())
            $this->content->user_view('social', $data, "");
        else
            $this->load->view('user/accaunt/social', get_object_vars($data) + get_object_vars(viewData()) + ["title_accaunt" => '']);
    }

    public function friends($social = false){
        $data = new stdClass();
        $this->load->library('hybridauth/hauth');

        $this->load->model('users_social_friends_model', 'usersSocialFriends');



        if($this->hauth->isAuthGoogle() || $social == "google"){
            $data->friends->Google = $this->hauth->getGoogle()->getUserProfile();
            vred($data->friends->Google);
            $data->friends->Google = $this->hauth->getGoogle()->getUserContacts();

//                       $uc->identifier = 111;
//           $uc->displayName = '111asdffff';
//           $uc->profileURL = 'qqqqqqqqqqqqqq';
//           $uc->photoURL = 'qwe';
//           $uc->l_name = '222';
//
//           $a[] = $uc;
            $data->localFriends->Google = $this->usersSocialFriends->setGoogleFriends($data->friends->Google);
//            $data->localFriends->Google = $this->usersSocialFriends->setGoogleFriends($a);
        } else {
            $data->localFriends->Google = $this->usersSocialFriends->getGoogleLocalFriends();
        }


        if($this->hauth->isAuthOk() || $social == "ok"){
            $data->friends->odnoklassniki      = $this->hauth->getOK()->getUserFriends();
//            unset($a);
//           $uc->identifier = 222;
//           $uc->displayName = '222asdffff';
//           $uc->profileURL = 'qqqqqqqqqqqqqq';
//           $uc->photoURL = 'qwe';
//           $uc->l_name = '222';
//
//           $a[] = $uc;
            $data->localFriends->odnoklassniki = $this->usersSocialFriends->setOkFriends($data->friends->odnoklassniki);
//
//            $data->localFriends->odnoklassniki = $this->usersSocialFriends->setOkFriends($a);
//            echo '<pre>';
//            var_dump($data->localFriends->odnoklassniki);
//            die;
        } else {
            $data->localFriends->odnoklassniki = $this->usersSocialFriends->getOkLocalFriends();
        }


        if($this->hauth->isAuthTwitter() || $social == "twitter"){
            $data->friends->Twitter = $this->hauth->getTwitter()->getUserContacts();

            $data->localFriends->Twitter = $this->usersSocialFriends->setTwitterFriends($data->friends->Twitter);

//           unset($a);
//           $uc->identifier = 333;
//           $uc->displayName = '333  ddddd';
//           $uc->profileURL = 'qqqqqqqqqqqqqq';
//           $uc->photoURL = '333qwe';
////           $uc->l_name = '222';
//
//           $a[] = $uc;
//            $data->localFriends->Twitter = $this->usersSocialFriends->setTwitterFriends($a);
        } else {
            $data->localFriends->Twitter = $this->usersSocialFriends->getTwitterLocalFriends();
        }

        if($this->hauth->isAuthFacebook() || $social == "facebook"){
            $data->friends->Facebook = $this->hauth->getFacebook()->getUserContacts();

            $data->localFriends->Facebook = $this->usersSocialFriends->setFacebookFriends($data->friends->Facebook);
        } else {
            $data->localFriends->Facebook = $this->usersSocialFriends->getFacebookLocalFriends();
        }

        if($this->hauth->isAuthVkontakte() || $social == "vkontakte"){
            $data->friends->Vkontakte = $this->hauth->getVkontakte()->getUserContacts();

            $data->localFriends->Vkontakte = $this->usersSocialFriends->setVkontakteFriends($data->friends->Vkontakte);
        } else {
            $data->localFriends->Vkontakte = $this->usersSocialFriends->getVkontakteLocalFriends();
        }


        if($this->hauth->isAuthMailru() || $social == "mailru"){
//            $data->friends->Mailru = $this->hauth->getMailru()->getUserProfile();

            $data->friends->Mailru = $this->hauth->getMailru()->getUserContacts();


            $data->localFriends->Mailru = $this->usersSocialFriends->setMailruFriends($data->friends->Mailru);
        } else {
            $data->localFriends->Mailru = $this->usersSocialFriends->getMailruLocalFriends();
        }


        $data->social = $social;

        $data->socialList = socialList();
        $this->content->user_view('friends', $data, _e('Друзья'));
    }

    public function hybridEndpoint(){

        $this->load->library('hybridauth/hauth_endpoint');

        Hybrid_Endpoint::process();
    }

    private function ajax_hybridSendMessage($args){
        $this->load->library('hybridauth/hauth');

        $social = $args[0];
        $uid    = $args[1];

        $mess = _e('Присоеденяйтесь к нам скорее.');


        if($social == "google" && $uid && $this->hauth->isAuthGoogle()){
            $res = $this->hauth->getGoogle()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }

        if($social == "ok" && $uid && $this->hauth->isAuthOk()){
            $res = $this->hauth->getOK()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }

        if($social == "twitter" && $uid && $this->hauth->isAuthTwitter()){
            $res = $this->hauth->getTwitter()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }

        if($social == "facebook" && $uid && $this->hauth->isAuthFacebook()){
            $res = $this->hauth->getFacebook()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }

        if($social == "vkontakte" && $uid && $this->hauth->isAuthVkontakte()){
            $res = $this->hauth->getVkontakte()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }





        if($social == "mailru" && $uid && $this->hauth->isAuthMailru()){
            $res = $this->hauth->getMailru()->sendMessageFriend($uid, $mess);
            if(isset($res->error) && $res->error){
                echo json_encode(['message' => $res->error_mesage]);
            } else {
                echo json_encode(['message' => _e('Сообщение отправленно.')]);
            }

            return FALSE;
        }
    }

    private function ajax_hybridAjaxIsAuth($args){
        $this->load->library('hybridauth/hauth');

        $social = $args[0];

        if($social == "google" && $this->hauth->isAuthGoogle()){
            echo json_encode(['error' => '0']);
        } elseif($social == "google"){
            echo json_encode(['error' => '1']);
        }

        if($social == "ok" && $this->hauth->isAuthOk()){
            echo json_encode(['error' => '0']);
        } elseif($social == "ok"){
            echo json_encode(['error' => '1']);
        }

        if($social == "twitter" && $this->hauth->isAuthTwitter()){
            echo json_encode(['error' => '0']);
        } elseif($social == "twitter"){
            echo json_encode(['error' => '1']);
        }

        if($social == "facebook" && $this->hauth->isAuthFacebook()){
            echo json_encode(['error' => '0']);
        } elseif($social == "facebook"){
            echo json_encode(['error' => '1']);
        }

        if($social == "vkontakte" && $this->hauth->isAuthVkontakte()){
            echo json_encode(['error' => '0']);
        } elseif($social == "vkontakte"){
            echo json_encode(['error' => '1']);
        }

        if($social == "mailru" && $this->hauth->isAuthMailru()){
            echo json_encode(['error' => '0']);
        } elseif($social == "mailru"){
            echo json_encode(['error' => '1']);
        }

//        $uid = $args[0];
    }

    public function social_delete($name){
        $data = new stdClass();
        require_once APPPATH.'controllers/user/Security.php';
        $own  = $this->users->getCurrUserId();
        if(Security::checkSecurity($own))
            return;

        if(in_array($name, socialList())){
            $socials = $this->accaunt->get_social();
            if(count($socials) > 1){
                $this->accaunt->social_delete($name);
                accaunt_message($data, sprintf(_e('Страничка "%s" была отвязана'), $name), 'good');
            } else {
                accaunt_message($data, _e('Вы не можете удалить последнюю привязанную страницу'), 'error');
            }
        }
        echo json_encode(['res' => 'success']);
    }

    public function social_add($id_user, $code = null){
        if(empty($id_user))
            redirect(site_url('account/social'));
        if(null != $code){
            $this->load->model('social_model', 'social');
            $this->social->check2Auth4Add($id_user, $code);
            redirect(site_url('account/social')); // if false eval this;
        }
        include(APPPATH.'views/user/social_add.php');
    }

    private function user_field($data, $label, $name, $db_name, $var, $type, $style, $value, $id_user){


        $val = null;
        if($db_name && isset($data->{$var}) && isset($data->{$var}->$db_name)){
            $val = $data->{$var}->$db_name;
        }

        if($db_name == 'place')
            $val = $data->user->place;


        if(empty($val) && isset($_POST[$name]))
            $val = $_POST[$name];

        $matches = null;

        if(preg_match('/^(.*)\|(.*)$/sui', $label, $matches)){
            $face  = $data->user->face;
            $label = $matches[$face];
        }

        $out = '<div class="formRow padding10-0" '.($name == 'phone' ? 'style="height: 55px; overflow: visible;"' : '').'>'.
            '<label>'.$label.'</label>
                        <div class="formRight">';

        //var_dump($data);
        ///$out .= "$val $label, $name, $db_name, $var, $type, $style, $value, $id_user";

        switch($type){
            case 'my': $out .= $value[$val];
                break;
            case 'select':
                $out .= form_dropdown($name, $value, $val, $style);

                break;
            case 'select_countries':
                $out .= form_dropdown_countries($name, $value, $val, $style);

                break;
            case 'text':

                // проверка на подветреждение главной страницы паспорта
                if($name == 'n_name' || $name == 'o_name' || $name == 'f_name'){
                    $this->load->model('Documents_model', 'documents');
                    //если главная страница подтверждена, то блокируем поля ввода
                    $style .= ' data-limiter="50" data-filter="latin" ';
                    if($this->documents->getUserDocumentStatus($id_user) == Documents_model::STATUS_PROVED && $this->users->isUsedOnlyLatinLanguageNames() === TRUE)
                        $style .= ' readonly="readonly"';
                }

                if($name == 'r_town')
                    $style .= ' data-limiter="50" data-filter="latin" ';

                if($name == 'r_street')
                    $style .= ' data-limiter="35" data-filter="latin" ';

                // Вывод поля телефон и подтверждение вывода
                if($name == 'phone'){
                    $this->load->model('phone_model', 'phone');
                    $phoneWithCode = $this->phone->getPhoneWithCode($id_user);

                    $phone_verification = $this->phone->isStatusVerified($id_user);
                    $emptyPhone         = empty($phoneWithCode['phone']);

                    $blocked = '';
                    if($phone_verification == 1 || !empty($phoneWithCode['phone']))
                        $blocked = 'blocked';
                    $urls    = explode('/', $_SERVER['REQUEST_URI']);
                    $lang    = 'ru';
                    if(in_array('en', $urls))
                        $lang    = 'en';

                    $out .= '<span class="formPhone">'
                        .'<div class="phone-code">'.
                        '<div class="dd-select '.$blocked.'">
                                        <input value="'.$phoneWithCode['code'].'" class="dd-selected-code-value" type="hidden" name="phone_code"/>
                                        <input value="'.$phoneWithCode['short_name'].'" class="dd-selected-short-name-value" type="hidden" name="phone_short_name"/>
                                        <a class="dd-selected">
                                            <label class="dd-selected-text">'._e('КОД').'</label>
                                        </a>
                                        <span class="dd-pointer dd-pointer-down"></span>
                                        <ul style="width:370px;display:none;height:180px;" class="dd-options">'.
                        getPhoneSelect($phoneWithCode['short_name'], $lang).
                        '</ul>'.
                        '</div>'.
                        '</div>'.
                        '<input type="text" name="phone" id="phone" value="'.$phoneWithCode['phone'].'" class="maskPhone_new" onblur="chek_phone_tt()" '.
                        ($phone_verification == 1 || !$emptyPhone ? 'readonly="readonly"' : '').' />'.
                        '</span>';
                    // если телефон пользователя подтвержден, то блокируем возможность его менять $out .= 'readonly="readonly"' - в сафари на МАК не выводит телефон


                    /* if ($phone_verification == 1) { // Номер подтвержден
                      $out .= '<span class="wNavButtons" style="width: 40px;display: inline-block;margin-left: 10px;">' .
                      '<span class="wNavButtons" id="chek"> &nbsp &nbsp &nbsp </span>' .
                      '<input type="hidden" name="phone_verification" id="phone_verification" value="1">' .
                      '</span>';   // Сюда выводим галочку если номер подтвержден
                      } else { // Номер не подтвержден */
                    $out .= '<span class="wNavButtons" style="width:250px;display:inline-block;margin-left:10px;">'.
                        '<span  id="divResult">'.
                        '<span class="wNavButtons">';

                    if($phone_verification == 1){
                        $out .= '<span class="wNavButtons" id="chek" style="float:left;"> &nbsp &nbsp &nbsp </span>'.
                            '<input type="hidden" name="phone_verification" id="phone_verification" value="1">';
                    } else {
                        $out .= '<input type="hidden" name="phone_verification" id="phone_verification" value="0"/>';
                    }

                    $out .= '<a id="various1" style="font-size: 11px; float: right; '.(!$emptyPhone ? 'display:none;' : '').'width: 80px !important;height: auto;color: #FFF; line-height: 14px; margin-top: 0px; padding-top: 7px !important;" onclick="obr_button(); return false;" class="but blueB" href="#" >'._e('Сохранить').'</a>';
                    $out .= '<a id="variousB" style="font-size: 11px; '.($emptyPhone ? 'display:none;' : '').' float: right;width: 80px !important;height: auto;color: #FFF; line-height: 14px; margin-top: 0px; padding-top: 7px !important;" onclick="show_save_button(this); return false;" class="but blueB" href="#" >'._e('Изменить').'</a>';
                    //'<a id="various1" style="display:none; float: right;width: 80px !important;height: auto;color: #FFF; line-height: 14px; margin-top: 0px; padding-top: 7px !important;" onclick="obr_button(); return false;" class="but blueB" href="#" >'._e('Подтвердить').'</a>'.



                    $out .= '<label></label>'.
                        '</span>'.
                        '</span>'.
                        '<div id="formPhone">'.
                        '<input type="text" id="kod" placeholder="'._e('Введите СМС-код').'"/>'.
                        '<a id="next3">OK</a>'.
                        '</div>'.
                        '</span>'.
                        '<div id="divResult2"></div>';
                    //}
                    break;
                } else {
                    $out .= form_input($name, $val, $style);
                    break;
                }


                if($name == 'phone_verification'){
                    $out .= '<span class="formRight formPhone"><input type="text" name="phone_verification" id="phone_verification" value="'.$val.'" class="maskPhone_new" /></span>';
                    break;
                }

            case 'radio': foreach($value as $index => $item){
                    $out .='<input type="radio" name="'.$name.'" '.(($val == $index) ? "checked" : "").' value="'.$index.'" />'.$item.' <!--br /-->';
                } break;

            case 'checkbox':
                $out .= form_checkbox($name, 1, (bool) $val);
                break;

            default : break;
        }
        $out .='</div>
                    </div>';

        return $out;
    }

    private function showProfileForm($data){

        $countries = get_country(false, true);

        $sub = [
            [1, _e("profile/personal_info"), 'user', true],
            //     [2, _e("profile/personal_info1"), 'user', true, _e("profile/personal_info4")],
            [6, _e("profile/personal_info2"), 'adr_r', true, _e("profile/personal_info5")],
            [7, _e("Контактная информация"), 'user', true, _e("Контактная информация")],
            //     [3, _e("profile/personal_info3").' <span class="payment_format_tt" onclick="$(\'#popup_payment\').show(\'slow\');  return  false;">(?)</span>', 'user', true],
            //		array(3,'Предпочтение','adr_r',true, 'Предпочтение'),
            //array(5,'Фактический адрес','adr_f',false),
            //array(4,'Место  работы - <font color="red">Данные ниже необходимы при заявке на займ</font>','user', false ),
        ];

//array('label','name','db_name', 'input type',  parent,  c_f,  c_y,  i_f, i_y,  style)
// array('','','','text', 1,  true,  true, true, true),
        $fields = [
            //   [_e('Тип регистрации'), 'face', 'face', 'my', 1, false, false, false, true, '', [2 => _e("Юридическое Лицо"), 1 => _e('Физическое Лицо')]],
            //    [_e('Тип клиента'), 'vip', 'vip', 'my', 1, false, false, true, false, '', [2 => _e("Vip"), 1 => _e('Стандарт')]],
            //    [_e('Название организации'), 'w_name', 'work_name', 'text', 1, false, false, false, true],
            [_e("profile/personal_info6").' <span  class="req">*</span>', 'id_user', 'id_user', 'text', 1, true, true, true, true, 'disabled="disabled"  class="wizard-ignore"'],
            [_e("profile/personal_info7"), 'nickname', 'nickname', 'text', 1, true, true, true, true],
            [_e("profile/personal_info8"), 'is_show', 'is_show', 'checkbox', 1, true, true, true, true],
            // array('<label>Показывать Nickname</label><div class="formRight"><input type="checkbox" name="is_show" value="1" id="sv_fact_dres" /></div>', '', '', '', 1, true, true, false, true),
//            array('<label>Показывать Nickname</label><div class="formRight"><input type="checkbox" name="is_show" value="1" id="sv_fact_dres" /></div>', 'is_show', 'name', 'my', 1, true, true, true, true),
            [_e("profile/personal_info9").' <span class="req">*</span>', 'n_name', 'name', 'text', 1, true, true, true, true],
            [_e("profile/personal_info10").' <span class="req">*</span>', 'f_name', 'sername', 'text', 1, true, true, true, true],
            //   [_e("profile/personal_info11"), 'o_name', 'patronymic', 'text', 1, true, true, true, true],
            //  [_e("profile/personal_info12"), 'w_who', 'work_who', 'text', 1, false, false, false, true],
            [_e("profile/personal_info19").' <span class="req">*</span>', 'sex', 'sex', 'radio', 1, true, true, true, true, '', [1 => _e("profile/personal_info20"), 2 => _e("profile/personal_info21")]],
            [_e("profile/personal_info18").' <span class="req">*</span>', 'born_date', 'born', 'text', 1, true, true, true, true, 'class="maskDate" placeholder="'._e("profile/personal_info29").'" '],
            [_e("profile/personal_info13"), 'skype', 'skype', 'text', 7, true, true, true, true],
            //	array('Телефон <span  class="req">*</span>','phone','phone','text', 1,  true,  true, true, true, 'class="maskPhone"'),
            [_e("profile/personal_info14").' <span class="req">*</span> <span class="phone_format" onclick="$(\'#popup_debit\').show(\'slow\');  return  false;">(?)</span>', 'phone', 'phone', 'text', 7, true, true, true, true, 'class="maskPhone_new"'],
            // Телефон в новом формате в новом поле базы данных
            //	array('Телефон<span  class="req">*</span>  <span class="phone_format" onclick="$(\'#popup_debit\').show(\'slow\');  return  false;">(?)</span>','phone_new','phone_new','text', 1,  true,  true, true, true, 'class="maskPhone_new"'),
            //	array('Проверочный код','hash_code','hash_code','text', 1,  true,  true, true, true, 'class="maskPhone1"'),
            //array('Верификация телефона ','phone_verification','phone_verification','text', 1,  true,  true, true, true, 'class="maskPhone1"'),
            // array('id','id_user','id_user','text', 1,  true,  true, true, true, 'class="maskPhone1"'), // ади юзера
            [_e("profile/personal_info15"), 'email123', 'email', 'text', 7, true, true, true, true, 'disabled="disabled"  class="wizard-ignore"'],
            // 	array('Регион проживания <span  class="req">*</span>|Регион <span  class="req">*</span>','place','place','select', 1, true, false, false, false, '', get_region()),
            //	array('Страна <span  class="req">*</span>|Страна <span  class="req">*</span>','place','place','select', 1, true, false, false, false, '', get_country()),
            //   [_e("profile/personal_info17"), 'w_place', 'work_place', 'select', 1, false, false, true, false, '', get_bisness()],
            //   [_e("profile/personal_info22"), 'family_state', 'family_state', 'radio', 1, true, false, false, false, '', [1 => _e("profile/personal_info23"), 2 => _e("profile/personal_info24"), 3 => _e("profile/personal_info25")]],
            //    [_e("profile/personal_info26"), 'p_seria', 'pasport_seria', 'text', 2, true, true, true, true],
            //    [_e("profile/personal_info27"), 'p_number', 'pasport_number', 'text', 2, true, true, true, true],
            //    [_e("profile/personal_info28"), 'p_date', 'pasport_date', 'text', 2, true, true, true, true, 'class="maskDate"  placeholder="'._e("profile/personal_info29").'"  '],
            //    [_e("profile/personal_info30"), 'p_kpd', 'pasport_kpd', 'text', 2, true, true, true, true, 'class="maskKPD"'],
            //    [_e("profile/personal_info31"), 'p_kvn', 'pasport_kvn', 'text', 2, true, true, true, true],
            //     [_e("profile/personal_info32"), 'bank_cc', 'bank_cc', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            //      [_e("profile/personal_info33"), 'bank_cc_date_off', 'bank_cc_date_off', 'text', 3, true, true, true, true, " class=\"w10\" placeholder='MM/YY'"], // 'class="maskcard2"'),
            //  ['W1 (USD)', 'bank_w1', 'bank_w1', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            // ['W1 (RUB)', 'bank_w1_rub', 'bank_w1_rub', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            //['PerfectMoney', 'bank_perfectmoney', 'bank_perfectmoney', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            //['OKpay', 'bank_okpay', 'bank_okpay', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            // ['EGOpay', 'bank_egopay', 'bank_egopay', 'text', 3, true, true, true, true], // 'class="maskcard2"'),
            //     [_e("profile/personal_info34"), 'bank_qiwi', 'bank_qiwi', 'text', 3, true, true, true, true], //, 'class="maskPhone"'),
            //   ['Paypal', 'bank_paypal', 'bank_paypal', 'text', 3, true, true, true, true], // 'maxlength=50'),
            //  ['Tinkoff Wallet', 'bank_tinkoff', 'bank_tinkoff', 'text', 3, true, true, true, true], // 'class="maskPhone"'),
            //   ['Webmoney', 'webmoney', 'webmoney', 'text', 3, true, true, true, true],
//            array('Liqpay', 'bank_liqpay', 'bank_liqpay', 'text', 3, true, true, true, true), // 'class="maskPhone"'),
            //     ['RBK Money', 'bank_rbk', 'bank_rbk', 'text', 3, true, true, true, true],
            //    ['Деньги@Mail.Ru', 'bank_mail', 'bank_mail', 'text', 3, true, true, true, true],
            //['Payeer', 'bank_lava', 'bank_lava', 'text', 3, true, true, true, true],
            //    ['Yandex', 'bank_yandex', 'bank_yandex', 'text', 3, true, true, true, true],
            //     [ _e('accaunt/profile_bank_title'), 'bank_name', 'bank_name', 'title', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_1').'<span class="req">*</span>', 'wire_beneficiary_name', 'wire_beneficiary_name', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_2').'<span class="req">*</span>', 'wire_beneficiary_address', 'wire_beneficiary_address', 'text', 3, true, true, true, true],
            //   [ _e('accaunt/profile_bank_3').'<span class="req">*</span>', 'wire_beneficiary_bank', 'wire_beneficiary_bank', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_4').'<span class="req">*</span>', 'wire_beneficiary_bank_country', 'wire_beneficiary_bank_country', 'select_countries', 3, true, true, false, false, "id='select_countries'", get_country_list()],
            //   [ _e('accaunt/profile_bank_5'), 'wire_beneficiary_bank_address', 'wire_beneficiary_bank_address', 'text', 3, true, true, true, true],
            //     [ _e('accaunt/profile_bank_6'), 'wire_sort', 'wire_sort', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_7').'<span class="req">*</span>', 'wire_beneficiary_account', 'wire_beneficiary_account', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_8').'<span class="req">*</span>', 'wire_beneficiary_swift', 'wire_beneficiary_swift', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_9'), 'wire_corresponding_bank', 'wire_corresponding_bank', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_10'), 'wire_corresponding_bank_swift', 'wire_corresponding_bank_swift', 'text', 3, true, true, true, true],
            //    [ _e('accaunt/profile_bank_11'), 'wire_corresponding_account', 'wire_corresponding_account', 'text', 3, true, true, true, true],

            /*
              array('Wire transfer (Bank) <span class="phone_format" onclick="$(\'#popup_bank\').show(\'slow\');  return  false;">(?)</span>', 'bank_name', 'bank_name', 'text', 3, true, true, true, true),
              array('Номер счета', 'bank_schet', 'bank_schet', 'text', 3, true, true, true, true),
              array('ABA/SWIFT', 'bank_bik', 'bank_bik', 'text', 3, true, true, true, true), //, 'maxlength=50'),
             * */

            // array('Корр счет','bank_kor','bank_kor','text', 3,   true,  true, true, true),
            //  [_e("profile/personal_info35").' <span class="phone_format" onclick="$(\'#popup_preference\').show(\'slow\');  return  false;">(?)</span>', 'payment_default', 'payment_default', 'select', 3, true, false, false, false, '', get_payment_default()],
            //  [_e("profile/personal_info36"), 'w_name', 'work_name', 'text', 4, false, false, false, false],
            //array('Правовая Форма Организации','legal_form','legal_form','text', 4,  false,  true, false, true), //---
            //   [_e("profile/personal_info37"), 'ogrn', 'ogrn', 'text', 2, false, false, false, false], //---
            //    [_e("profile/personal_info38"), 'kpp', 'kpp', 'text', 2, false, false, false, false], //---
            //    [_e("profile/personal_info39"), 'inn', 'inn', 'text', 2, false, false, false, false, 'placeholder="'._e("profile/personal_info40").'"   '],
            //    [_e("profile/personal_info41"), 'w_phone', 'work_phone', 'text', 4, false, false, false, false, ' class="maskPhone" '],
            //    [_e("profile/personal_info42"), 'w_place', 'work_place', 'select', 4, false, false, false, false, '', get_bisness()],
            //    [_e("profile/personal_info43"), 'w_who', 'work_who', 'text', 4, false, false, false, false],
            //    [_e("profile/personal_info44"), 'w_time', 'work_time', 'text', 4, false, false, false, false],
            //    [_e("profile/personal_info45"), 'w_money', 'work_money', 'text', 4, false, false, false, false],
            //    [_e("profile/personal_info39"), 'inn', 'inn', 'text', 4, false, false, false, false, 'placeholder="'._e("profile/personal_info40").'"   '],
            //    ['<input type="checkbox" name="f_r" value="1" id="sv_fact_dres" />(*)'._e("profile/personal_info54"), '', '', '', 5, true, false, false, true],
            //    [_e("profile/personal_info16"),'f_kc','kc','select', 6, true, false, false, false, '', get_country()], 	// Страна тут и в предыдущем окне - две разные в БД
            //    [_e("profile/personal_info46"), 'f_index', 'index', 'text', 5, true, true, true, true],
            //    [_e("profile/personal_info47"), 'f_town', 'town', 'text', 5, true, true, true, true],
            //    [_e("profile/personal_info48"), 'f_street', 'street', 'text', 5, true, true, true, true],
            //    [_e("profile/personal_info49"), 'f_house', 'house', 'text', 5, true, true, true, true],
            //   [_e("profile/personal_info50"), 'f_kc', 'kc', 'text', 5, true, true, true, true],
            //   [_e("profile/personal_info51"), 'f_flat', 'flat', 'text', 5, true, true, true, true],
            // 	array('Корпус/строение','r_kc','kc','text', 6,  true,  true, true, true), 				// Оригинал
            // 	array('Страна','r_kc','kc','text', 6,  true,  true, true, true),
            //	array('Страна','r_kc','place','select', 6, true, false, false, false, '', get_country()),
            //     [_e("profile/personal_info16"),'r_kc','kc','select', 6, true, false, false, false, '', get_country()], 	// Страна тут и в предыдущем окне - две разные в БД
            [_e("profile/personal_info16").' <span class="req">*</span>', 'place', 'place', 'select', 6, true, true, false, false, '', $countries],
            [_e("profile/personal_info46").' <span class="req">*</span>', 'r_index', 'index', 'text', 6, true, true, true, true],
            [_e("profile/personal_info47").' <span class="req">*</span>', 'r_town', 'town', 'text', 6, true, true, true, true],
            [_e("Адрес").' <span class="req">*</span>', 'r_street', 'street', 'text', 6, true, true, true, true],
            // 	array('Дом','r_house','house','text', 6,  true,  true, true, true),
            //[_e("profile/personal_info52"), 'r_house', 'house', 'text', 6, true, true, true, true],
            //[_e("profile/personal_info53"), 'r_flat', 'flat', 'text', 6, true, true, true, true],
        ];


        $out = '';

        $face = 6; //($data->user->face == 2) ? 6 : 5;

        $out .= '<fieldset class="step" id="w2first">';
        foreach($sub as $parent){ //array('label','name','db_name', 'input type',  parent,  c_f,  c_y,  i_f, i_y,  style)
            if($parent[0] == 2){
//                $tmp = true;
                $out .= '</fieldset><fieldset id="w2confirmation" class="step">';
            }
            if($parent[0] == 3){
//                $tmp = true;
                $out .= '</fieldset><fieldset id="w3confirmation" class="step">';
            }
            if(($data->user->face == 2 and $parent[3]) or $data->user->face == 1){
                $out .= '<div class="title">'.
                    '<img src="images/icons/dark/pencil.png" alt="" class="titleIcon" />'.
                    '<h6>'.(($data->user->face == 2 and $parent[0] == 2) ? $parent[4] : $parent[1]).'</h6>'.
                    '</div>';

                foreach($fields as $item){

                    if($item[4] == $parent[0]){
                        if(!isset($item[9]))
                            $item[9]  = "";
                        if(!isset($item[10]))
                            $item[10] = "";

                        //$phone_verification  = $data->user->phone_verification; 	// проверяем признак подтверждения телефона, если 1 - то подтвержден
                        //	$phone_new = $data->user->phone_new;						// телефон пользователя в новом формате
                        $id_user = $data->user->id_user;

                        if($item[$face] == true)
                            $out .= $this->user_field($data, $item[0], $item[1], $item[2], $parent[2], $item[3], no_error($item, 9), no_error($item, 10), $id_user);
                    }
                }
            }
        }
        $out .= '</fieldset>';


        return $out;
    }

    /**
     * СОхраняем дополнительные данные для  vdna теста
     *
     */
    private function ajax_save_vdna_extra(){

        $birthday = $this->input->post('birthday');
        $sex      = $this->input->post('sex');


        if(empty($birthday) || empty($sex))
            return $this->send_ajax_responce(
                    array(
                        'result'        => 'error',
                        'error_message' => _e('Неверные параметры.')
                    )
            );

        $this->load->model('Visualdna_model');
        $user_id = $this->accaunt->get_user_id();

        if(!$this->Visualdna_model->saveExtraData($user_id, $sex, $birthday)){
            return $this->send_ajax_responce(
                    array(
                        'result'        => 'error',
                        'error_message' => _e('Данные не удалось сохранить. Попробуйте еще раз.')
                    )
            );
        }

        return $this->send_ajax_responce(
                array(
                    'result'  => 'OK',
                    'message' => _e('Спасибо! Информация успешно сохранена.')
                )
        );
    }

    // закрытие окна системного сообщения
    public function ajax_close_system_message(){

        $message_id = $this->input->post('message_id');
        $user_id    = $this->accaunt->get_user_id();

        if(empty($message_id) || empty($user_id))
            return $this->send_ajax_responce(array('error' => 'Bad params'));

        $this->load->model('system_messages_model', 'system_messages');
        if($this->system_messages->set_user_message_status($user_id, $message_id) === FALSE)
            return $this->send_ajax_responce(array('error' => _e('Ошибка сохранения')));


        $this->send_ajax_responce(array('success' => 'OK'));
    }

    public function calcs($user_id = NULL, $date = NULL){
        if($user_id == NULL)
            $user_id = $this->accaunt->get_user_id();

        if(!in_array($this->accaunt->get_user_id(), [93517463, 500150, 92156962]))
            redirect('/account/profile');

        $data            = new stdClass();
        $data->calcs[0]  = $this->accaunt->recalculateUserRating($user_id, $date);
        for($i = 1; $i <= 6; $i++)
            $data->calcs[$i] = $this->accaunt->recalculateUserRating($user_id, NULL, $i);
        $this->content->user_view('calcs', $data, _e('Просмотр расчетов для пользователя '.$user_id));
    }

    private function _check_partner($current_user_id, $new_partner_user_id){
        $this->load->model('Users_model', 'users');
        $result        = new stdClass();
        $result->found = NULL;

        // если пользователь = текущий пользователь
        if($current_user_id == $new_partner_user_id){
            $result->error  = _e('Вы ищите сами себя');
            $result->status = FALSE;
            return $result;
        }

        $current_user = $this->accaunt->getUserFields($current_user_id, ['reg_date', 'parent']);

        if($current_user->parent == $new_partner_user_id){
            $result->error  = _e('Этот пользователь уже ваш старший партнер');
            $result->status = FALSE;
            return $result;
        }

        // проверим чтобы искомый пользоветль не был младшим партнером
        $children = $this->users->getChildrenId($current_user_id);
        if(in_array($new_partner_user_id, $children)){
            $result->error  = _e('Этот пользователь ваш младший партнер');
            $result->status = FALSE;
            return $result;
        }


        $found = $this->accaunt->getUserFields($new_partner_user_id, ['id_user', 'reg_date', 'parent', 'sername', 'name', 'state']);

        if(empty($found)){
            $result->error  = _e('Такой пользователь не найден');
            $result->status = FALSE;
            return $result;
        }

        // если пользователь заблокирован то не ищем его
        if($found->state == 3){
            $result->error  = _e('Этот пользователь заблокирован');
            $result->status = FALSE;
            return $result;
        }



        $result->status = TRUE;
        $result->found  = $found;
        return $result;
    }

    public function change_partner(){

        $this->load->model('Users_model', 'users');
        $this->load->model('Users_filds_model', 'users_filds');

        $data    = new stdClass();
        $user_id = $this->accaunt->get_user_id();
        $user    = $this->accaunt->getUserFields($user_id, ['reg_date', 'parent']);


        $data->available = TRUE;
        //если у пользователя нет старшего партнера вообще
        //if (empty($user->parent)){
        //}


        $last_change = $this->users->get_last_partner_change($user_id);
        // партнера можно менять через 3 месяца после регистрации
        if(empty($last_change) && time() < strtotime('+1 month', strtotime($user->reg_date))){
            $data->error     = _e('Вы можете поменять партнера после 1 месяца с даты регистрации');
            $data->available = FALSE;
        }
        // либо через 6 месяцев после последнего изменения
        if(!empty($last_change) && time() < strtotime('+3 month', strtotime($last_change->dttm))){
            $data->error     = _e('Вы можете поменять партнера после 3 месяцев после последней смены').'('.$last_change->dttm.')';
            $data->available = FALSE;
        }



        if(strpos($_SERVER['HTTP_REFERER'], base_url()) === FALSE){
            $data->available = FALSE;
        }



        if($data->available && !empty($_POST['new_partner'])){
            $id                = (int) $_POST['new_partner'];
            $data->new_partner = $id;
            // если тот кого ищем уже не старший партнер
            $check_result      = $this->_check_partner($user_id, $id);
            if($check_result->status){
                $data->found = $check_result->found;
                if(!empty($data->found)){
                    $data->found->grandchilds_count = 0;
                    $data->found->childs_count      = 0;
                    $childs                         = $this->users->getUsers($data->found->id_user);
                    if(!empty($childs)){
                        $data->found->childs_count = count($childs);
                        foreach($childs as $child){
                            $data->found->grandchilds_count += count($this->users->getUsers($child->id_user));
                        }
                    }
                    $data->found->id_network = $this->users_filds->getUserFild($data->found->id_user, 'id_network');
                    $data->found->soc_url    = 'https://webtransfer.com/social/profile/'.$data->found->id_network;
                    $data->found->ava        = $this->base_model->getUserAvatars($data->found->id_user);
                }
            } else {
                $data->error = $check_result->error;
            }
        }



        if($data->available && !empty($_POST['send_request'])){
            $parent_user_id = (int) $_POST['send_request'];

            $check_result = $this->_check_partner($user_id, $parent_user_id);
            if(!$check_result->status){
                accaunt_message($data, $check_result->error, 'error');
            } else {

                if(Security::checkSecurity($user_id)){
                    accaunt_message($data, _e("Неверный код авторизации"), 'error');
                    redirect(site_url('account/change_partner'));
                }

                $requests = $this->users->get_change_partner_requests($user_id, $parent_user_id, [Users_model::PARTNER_CHANGE_STATUS_DECLINE, Users_model::PARTNER_CHANGE_STATUS_NEW]);
                //var_dump($requests);
                if(count($requests) > 0)
                    accaunt_message($data, _e('Вы уже отправляли запрос этому пользователю'), 'error');
                else {
                    accaunt_message($data, _e('Ваш запрос отправлен'));
                    $this->mail->user_sender('partner_change_request_user', $user_id, ['partner_id' => $parent_user_id]);
                    $this->mail->user_sender('partner_change_request_partner', $parent_user_id, ['sender_id' => $user_id]);
                    $this->users->add_change_partner_request($user_id, $parent_user_id);
                }
            }
        }

        $this->content->user_view('change_partner', $data, _e('Сменить старшего партнера'));
    }

    public function ajax_social_friend_action(){
        $action         = $this->input->post('action');
        $friend_id      = $this->input->post('friend_id');
        $webtransfer_id = $this->accaunt->get_user_id();
        $this->load->library('soc_network');
        if($action == 'accept'){
            echo $this->soc_network->accept_friendship($friend_id, $webtransfer_id);
        } else if($action == 'deny'){
            echo $this->soc_network->deny_friendship($friend_id, $webtransfer_id);
        }
    }

    public function bankout(){
        $this->load->model('Card_model', 'cards');
        $user_id = $this->accaunt->get_user_id();
        if(!empty($_POST['bankAccountTemplateId'])){

            $card_id = $this->input->post('card_id');

            $data = [
                'bankAccountTemplateId'   => $this->input->post('bankAccountTemplateId'),
                'bankAccountCountryCode'  => $this->input->post('bankAccountCountryCode'),
                'bankAccountCurrencyCode' => $this->input->post('bankAccountCurrencyCode'),
                'paymentDetails'          => [
                    'transactionAmount' => $this->input->post('summa'),
                    'sourceTxnId'       => $user_id.time()
                ]
            ];
            foreach($_POST as $field => $val){
                if(substr($field, 0, 5) == 'form_')
                    $data['bankDetails'][substr($field, 5)] = $val;
            }

            $res = $this->cards->addBankAndUnload($card_id, $data);
            if(empty($res)){
                accaunt_message($data, _e('Ошибка отправки на сервис'), 'error');
            } else {
                if($res['errorDetails'][0]['errorDescription'] == 'Success'){
                    accaunt_message($data, 'Ваша заявка принята');
                } else {
                    accaunt_message($data, $res['errorDetails'][0]['errorDescription'], 'error');
                }
            }
            var_dump($res);
            //die();
        }


        $data            = new stdClass();
        $data->countries = $this->cards->getCountries('FastBankTransfer', 'FundsOut');
        $this->content->user_view("bankout", $data, _e('Вывод через банк'));
    }

    public function ajax_get_bank_account_template(){
        $this->load->model('Card_model', 'cards');
        echo json_encode($this->cards->getBankAccountTemplate($this->input->post('country')));
    }

    public function ajax_import_card(){
        $card_num = $this->input->post('card_num');
        $this->load->model('card_model', 'card');

        $this->send_ajax_responce(['card_info' => _e('Временно отключено')]);
        return;


        if(strlen($card_num) < 16){
            $resp['card_info'] = _e('Неверный формат карты');
            $this->send_ajax_responce($resp);
            return;
        }
        //$card_usr_id = substr($card_num,0, -15);
        //$card_proxy = substr($card_num, -15, 15);
        $card_proxy  = substr($card_num, 0, 15);
        $card_usr_id = substr($card_num, 16);

        $resp = ['card_info' => '-'];
        if(empty(Security::getProtectType($this->accaunt->get_user_id())))
            Security::setProtectType('email');
        if(Security::checkSecurity($this->accaunt->get_user_id(), true)){
            $resp['card_info'] = _e('Неверный код');
            $this->send_ajax_responce($resp);
            return;
        }

        // проверка на наличие карты
        $card = $this->card->get_cart_by_uid_and_proxy($card_usr_id, $card_proxy);
        if(!empty($card)){
            $resp = ['card_info' => _e('Эта карта уже привязана')];
            $this->send_ajax_responce($resp);
            return;
        }
        $card               = new stdClass();
        $card->card_user_id = $card_usr_id;
        $card->card_proxy   = $card_proxy;
        $details            = $this->card->getCardDetails($card);
        if(!empty($details) && $details['errorDetails']['errorCode'] == 0){
            $this->card->create_card([
                'user_id'       => $this->accaunt->get_user_id(),
                'card_user_id'  => $card_usr_id,
                'card_proxy'    => $card_proxy,
                'card_type'     => $details['cardDetail']['cardType'] == 'VIRTUAL' ? 1 : 0,
                'name_on_card'  => $details['cardDetail']['nameOnCard'],
                'creation_date' => $details['cardDetail']['creationDate'],
                'txnId'         => $details['cardDetail']['txnId'],
                'pan'           => $details['cardDetail']['pan'],
                'email'         => $this->accaunt->user_field('email'),
                'status'        => 0,
                'last_update'   => date('Y-m-d H:i:s'),
                'last_balance'  => 0
            ]);
            $res = $this->card->setPassword($card);
            if(isset($res['errorDetails']) && '0' != $res['errorDetails'][0]['errorCode']){
                $resp['card_info'] = _e('Ошибка установки пароля: ').$res['errorDetails'][0]['errorDescription'];
                $this->send_ajax_responce($resp);
                return;
            }
            $resp['card_info'] = 'ok';
            //print_r($details);
        } else {
            $resp['card_info'] = _e('Не удалось найти карту');
        }



        $this->send_ajax_responce($resp);
    }

    //page
    public function cloudwallet($page = 0){
        $data                 = new stdClass();
        $this->load->helper('date');
        $this->load->model('monitoring_model', 'monitoring');
        viewData()->page_name = __FUNCTION__;

        $id_user  = $this->accaunt->get_user_id();
        $this->monitoring->log(null, 'Страница Кошелек', 'private', $id_user);
        $per_page = "10";

        # Получение cookie
        $date           = ['array' => [false, false]];
        $date['cookie'] = explode('|', $this->input->cookie('filter_transactions', TRUE));

        if(isset($date['cookie'][1])){
            # Проверка даты
            $date['array'][0] = explode('/', (!$this->input->post('date_1', TRUE)) ? $date['cookie'][1] : $this->input->post('date_1', TRUE));
            $date['array'][1] = explode('/', (!$this->input->post('date_2', TRUE)) ? $date['cookie'][2] : $this->input->post('date_2', TRUE));
        }

        if(@count($date['array'][0]) != 3 || !checkdate($date['array'][0][0], $date['array'][0][1], $date['array'][0][2]))
            $date['array'][0] = explode('/', "06/01/2014");
        if(@count($date['array'][1]) != 3 || !checkdate($date['array'][1][0], $date['array'][1][1], $date['array'][1][2]))
            $date['array'][1] = explode('/', mdate("%m/%d/%Y", time()));

        $date['first'][0] = $date['array'][0][0]."/".$date['array'][0][1]."/".$date['array'][0][2];
        $date['last'][0]  = $date['array'][1][0]."/".$date['array'][1][1]."/".$date['array'][1][2];

        /*
          #Старый вариант
          $date['array'][0][1] = (($date['array'][0][1] <= 10) ? "0".($date['array'][0][1] - 1):($date['array'][0][1] - 1));
          $date['array'][1][1] = (($date['array'][1][1] <= 10) ? "0".($date['array'][1][1] + 1):($date['array'][1][1] + 1));

          $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 59:59:59";
          $date['last'][1] = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";
         */
        #получаем старую дату
        $date['first'][1] = $date['array'][0][2]."-".$date['array'][0][0]."-".$date['array'][0][1]." 23:59:59";
        $date['last'][1]  = $date['array'][1][2]."-".$date['array'][1][0]."-".$date['array'][1][1]." 00:00:00";

        #преобразуем
        $up_date   = strtotime($date['first'][1]);
        $down_date = strtotime($date['last'][1]);

        #берем только этот день
        $date['first'][1] = date('Y-m-d 23:59:59', $up_date - 24 * 3600);
        $date['last'][1]  = date('Y-m-d H:i:s', $down_date + 24 * 3600);


        # Проверка типа
        /*
          if ($this->input->post('type_1', TRUE) !== FALSE) $type[0] = TRUE;
          else if ($this->input->post('type_2', TRUE) == FALSE && $date['cookie'][0] == 1) $type[0] = TRUE;
          else $type[0] = FALSE;

          if ($this->input->post('type_2', TRUE) !== FALSE) $type[1] = TRUE;
          else if ($this->input->post('type_1', TRUE) == FALSE && $date['cookie'][0] == 2) $type[1] = TRUE;
          else $type[1] = FALSE;

          if (($type[0] && $type[1]) || (!$type[0] && !$type[1])) $type = FALSE;
          else if ($type[0]) $type = 1;
          else $type = 2;

         */
        $types         = [];
        $include_types = [];
        $cookie_types  = explode('-', $date['cookie'][0]);
        //if not POST
        if(empty($_POST)){
            if(is_array($cookie_types) && count($cookie_types) == 5){
                for($type = 1; $type <= 5; $type++){
                    $types[$type]    = $cookie_types[$type - 1];
                    if($cookie_types[$type - 1] == 1)
                        $include_types[] = $type;
                }
            } else {
                $types         = [1 => 1, 1, 1, 1, 1];
                $include_types = [1, 2, 3, 4, 5];
            }
        } else {
            for($type = 1; $type <= 5; $type++){
                if($this->input->post('type_'.$type, TRUE) !== NULL){
                    $types[$type]    = 1;
                    $include_types[] = $type;
                } else {
                    $types[$type] = 0;
                }
            }
        }

        if(count($include_types) == 0){
            $types         = [1 => 1, 1, 1, 1, 1];
            $include_types = [1, 2, 3, 4, 5];
        }


        # Запись в COOKIE
        if(!isset($date['cookie'][1]) || $date['cookie'][1] != $date['first'][0] || $date['cookie'][2] != $date['last'][0] || $date['cookie'][0] != implode('-', $types)){
            $this->input->set_cookie(['name'   => 'filter_transactions',
                'value'  => implode('-', $types).'|'.$date['first'][0].'|'.$date['last'][0],
                'expire' => 1200,
                'domain' => '',
                'path'   => '/',
                'prefix' => ''
            ]);
            //echo 'set cookie: '. implode('-',$types).'|'.$date['first'][0].'|'.$date['last'][0];
        }

        # Назначаем элементы
        $data->types = $types;
        $data->date  = $date;

        $data->pages_payout = $this->base_model->pagination($per_page, $this->accaunt->getCountPaysOut(), 'account/cloudwallet', 4);
        $data->payout       = $this->accaunt->getPaysOut($per_page, (int) $page);

        $data->pages_payment = $this->base_model->pagination($per_page, $this->accaunt->getCountPayment(), 'account/cloudwallet', 4);

        $data->payment = $this->accaunt->getPayment($per_page, (int) $page);

        # Формируем запрос WHERE для вставки в запрос
        $where = [ 'bonus in ('.implode(',', $include_types).')',
            "date >" => $date['first'][1],
            "date <" => $date['last'][1]];
//        $where = array("bonus ".((!$type) ? "!=" : "=") => $type, "date <" => $date['first'][1], "date >" => $date['last'][1]);

        $data->pages = $this->base_model->pagination($per_page, $this->accaunt->getCountPays($where), 'account/cloudwallet', 4);

        $data->pays = $this->accaunt->getPays($per_page, (int) $page, $where);

        $this->load->model('Card_model', 'card');
        $data->othercards = $this->card->getOtherCards($id_user);
        usort($data->othercards, function($a, $b){
            $sort = ['BANK_ACCOUNT' => 2, 'E_WALLET' => 3, 'BANK_CARD' => 1];
            if($sort[$a->account_type] == $sort[$b->account_type])
                return 0;
            return ($sort[$a->account_type] < $sort[$b->account_type]) ? -1 : 1;
        });
        $this->load->model('Countries_model', 'countries');
        $data->countries = $this->countries->get_list();


        $this->load->view('user/accaunt/cloud_wallet', get_object_vars($data) +
            get_object_vars(viewData()) +
            ["title_accaunt" => _e('account/data16').$id_user]);
    }

    public function ajax_export_card(){

        $card_id = $this->input->post('card_id');
        $this->load->model('Card_model', 'card');

        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $resp['message'] = _e('Не удалось найти карту');
            $this->send_ajax_responce($resp);
            return;
        }
        $resp['message'] = $card->card_proxy.'-'.$card->card_user_id;
        $this->send_ajax_responce($resp);
    }

    public function ajax_updateData_card(){
        $card_id = $this->input->post('card_id');
        $this->load->model('Card_model', 'card');



        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            $resp['message'] = _e('Не удалось найти карту');
            $this->send_ajax_responce($resp);
            return;
        }



        $details = $this->card->getCardDetails($card);
        if(!empty($details) && $details['errorDetails']['errorCode'] == 0){
            $this->card->update_card($card_id, [
                //'user_id' => $this->accaunt->get_user_id(),
                //'card_user_id' => $details['cardDetail']['userId'],
                //'card_proxy' => $details['cardDetail']['proxy'],
                'card_type'     => $details['cardDetail']['cardType'] == 'VIRTUAL' ? 1 : 0,
                'name_on_card'  => $details['cardDetail']['nameOnCard'],
                'creation_date' => $details['cardDetail']['creationDate'],
                'txnId'         => $details['cardDetail']['txnId'],
                'pan'           => $details['cardDetail']['pan'],
                'email'         => $this->accaunt->user_field('email'),
                //'status' => 0,
                //'last_update' => date('Y-m-d H:i:s'),
                //'last_balance' => 0
            ]);
            $res = $this->card->setPassword($card);
            if(isset($res['errorDetails']) && '0' != $res['errorDetails'][0]['errorCode']){
                $resp['message'] = _e('Ошибка установки пароля: ').$res['errorDetails'][0]['errorDescription'];
                $this->send_ajax_responce($resp);
                return;
            }

            $resp['message'] = _e('Данные по карте успешно обновлены');
            //print_r($details);
        } else {
            $resp['message'] = _e('Не удалось найти карту');
        }



        $this->send_ajax_responce($resp);
    }

    public function ajax_upgrade_card(){
        $card_id = $this->input->post('card_id');
        $this->load->model('Card_model', 'card');



        $card = $this->card->getUserCard($card_id);
        if(empty($card)){
            echo _e('Не удалось найти карту');
            return;
        }


        $r = $this->card->card_load_docs($card);
        if ( $r === TRUE){
            echo 'ok';
            accaunt_message($data, _e('Документы для повышения уровня успешно загружены'));
        } else
            echo $r;

    }

    public function ajax_send_to_own_card(){

        $from_card_id = $this->input->post('from_card_id');
        $to_card_id   = $this->input->post('to_card_id');
        $summ         = $this->input->post('summ');
        $user_id      = $this->accaunt->get_user_id();


        if(empty($summ) || empty($from_card_id) || empty($to_card_id)){
            echo _e('Не верно переданы параметры');
            return;
        }

        $this->load->model('Card_model', 'card');
        $to_card = $this->card->getUserCard($to_card_id);
        if(empty($to_card)){
            echo _e('Не верно указана карта пополнения');
            return;
        }

        $send_transaction              = new stdClass();
        $send_transaction->summa       = $summ;
        $send_transaction->id_user     = $to_card_id; //to card
        $send_transaction->own         = $user_id; //from user
        $send_transaction->own_card_id = $from_card_id; //from card
        $send_transaction->note        = 'Internal transfer';

        $error = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_SEND_INTERNAL_DIRECT_WTAPI);
        //$error = 'OK';
        if("OK" == $error){
            echo 'ok';
            accaunt_message($data, _e('Средства успешно переведены'));
        } else
            echo $error;
    }

    private function ajax_send_to_paysys(){
        $this->load->model("transactions_model", "transactions");
        $this->load->library('code');
        $this->load->library('payout');

        $from_card_id   = $this->input->post('from_card_id');
        $payment_code   = $this->input->post('payment_code');
        $payment_wallet = $this->input->post('payment_wallet');
        $summ           = $this->input->post('summ');
        $user_id        = $this->accaunt->get_user_id();


        if(empty($summ) || empty($from_card_id) || empty($payment_code) || empty($payment_wallet)){
            echo _e('Не верно переданы параметры');
            return;
        }
        require_once APPPATH.'controllers/user/Security.php';
        if(empty(Security::getProtectType($user_id)))
            Security::setProtectType('email');
        if(Security::checkSecurity($user_id, true)){
            echo 'Неверный код';
            return;
        }


        $hash       = md5("$user_id:$payment_code:$payment_wallet:$summ:FDDJ-34DK-FDVV-LOLF-657V");
        $url        = "https://webfin2.cannedstyle.com/ask/loadfunds";
        $postfields = [
            'user_id'        => $user_id,
            'wtcard_id'      => $from_card_id,
            'payment_code'   => $payment_code,
            'payment_wallet' => $payment_wallet,
            'amount'         => $summ,
            'hash'           => $hash
        ];


        $this->load->model('Card_model', 'card');
        $from_card = $this->card->getUserCard($from_card_id);
        if(empty($from_card)){
            echo _e('Не верно указана карта');
            return;
        }

        if(!in_array($payment_code, [312, 319, 318])){
            echo _e('Не верно указана платежная система');
            return;
        }

        $id                            = time().rand(1000, 9999);
        $purchaseMoney_data            = [];
        $purchaseMoney_data['card_id'] = $from_card_id;
        $purchaseMoney_data['user_id'] = $user_id;
        $purchaseMoney_data['id']      = 'ME_'.$id;
        $purchaseMoney_data['summa']   = ($summ * 1.06);
        $purchaseMoney_data['desc']    = 'Money Exchange';
        $response                      = $this->card->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_TRANS_SEND_TO_PAYSYS, $id);
        //var_dump( $error );
        if(false !== $response){

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
            curl_close($ch);

            if(empty($data)){
                echo _e('Ошибка отправки в платежную систему: пустой ответ');
                return;
            }
            $result = json_decode($data);
            if(empty($result)){
                echo _e('Ошибка отправки в платежную систему: неверный ответ'.$data);
                return;
            }

            if($result->result == false){
                echo _e('Ошибка отправки: ').$result->error;
                return;
            }

            echo 'ok';
        } else {
            echo "purchaseMoney: Ошибка транзакции";
        }
    }

    // защита от скачивания карт пользователей
    private function _check_get_user_cards_limit(){

        if(!isset($_SESSION['last_get_user_cards_count'])){
            $_SESSION['last_get_user_cards_count'] = 1;
            $_SESSION['last_get_user_cards_time']  = time();
        } else
            $_SESSION['last_get_user_cards_count'] += 1;

        if($_SESSION['last_get_user_cards_count'] >= 100)
            return _e('Вы привысили количество запросов на получение информации о картах других пользователей');


        if(!isset($_SESSION['last_get_user_cards_time']))
            return _e('Внутренняя ошибка');


        if(time() - $_SESSION['last_get_user_cards_time'] > 60 * 60)
            unset($_SESSION['last_get_user_cards_count']);

        return TRUE;
    }

    public function ajax_get_user_cards(){

        $check_limit_result = TRUE; //$this->_check_get_user_cards_limit();
        if($check_limit_result !== TRUE){
            echo json_encode(['status' => 'error', 'error' => $check_limit_result]);
            return;
        }


        $user_id = (int) $this->input->post('user_id');
        $this->load->model('Card_model', 'card');
        $cards   = $this->card->getCards($user_id);
        if(empty($cards)){
            echo json_encode(['status' => 'error', 'error' => _e('У этого пользователя нет карт')]);
            return;
        }

        $result = [];
        foreach($cards as $card){
            $result[] = [
                'id'   => $card->id,
                'name' => Card_model::display_card_name($card)
            ];
        }

        echo json_encode([
            'status' => 'success',
            'cards'  => $result
        ]);
    }



    public function send_message(){

        $from_user_id = get_current_user_id();
        $to_user_id   = (int) $this->input->post('to_user_id');

        //$from_user_id = get_current_user_id();
        //$to_user_id = 500150;
        $msg = trim($this->input->post('msg'));

        if(empty($to_user_id) || empty($msg)){
            echo json_encode(['status' => false, 'message' => _e('Пустые поля недопустимы')]);
            return;
        }

        $this->load->library('soc_network');
        $res = $this->soc_network->createMsg($from_user_id, $to_user_id, $msg, '');
        //var_dump($res);
        if($res['id'] > 0)
            echo json_encode(['status' => true, 'message' => _e('Сообщение успешно отправлено')]);
        else
            echo json_encode(['status' => false, 'message' => _e('Ошибка отправки')]);
    }

    public function ajax_is_created_phone(){
        $user_id        = $this->accaunt->get_user_id();
        $input_data     = array(
            '0' => '',
            '1' => ' <span class="req">*</span> <span class="phone_format" onclick="$(\'#popup_debit\').show(\'slow\');  return  false;">(?)</span>',
            '2' => 'phone',
            '3' => 'phone',
            '4' => 'user',
            '5' => 'text',
            '6' => 'class="maskPhone_new"',
            '7' => '',
            '8' => '',
            '9' => $user_id
        );
        $phone_template = $this->user_field($input_data[0], $input_data[1], $input_data[2], $input_data[3], $input_data[4], $input_data[5], $input_data[7], $input_data[8], $input_data[9]);

        $template_data = array(
            'phone_input' => $phone_template
        );
        $form          = $this->load->view('user/accaunt/security_module/change_phone', $template_data, true);

        $data = array(
            'success'                   => 1,
            'form'                      => $form,
            'security_type'             => 'test',
            'security_request_code_url' => site_url('/security/ajax/get_code'),
            'security_check_code_url'   => site_url('/security/ajax/check_code')
        );

        return $this->send_ajax_responce($data);
    }

    public function ajax_save_phone(){
        $user_id = $this->accaunt->get_user_id();

        $short_name = $this->input->post('short_name', TRUE);
        $phone      = str_replace(' ', '', rawurldecode($this->input->post('phone', TRUE)));
        $code       = str_replace(' ', '', $this->input->post('code', TRUE));

        if(empty($user_id) || empty($phone) || $phone == 'КОД'){
            return $this->send_ajax_responce(['success' => '']);
        }

        $this->load->model('phone_model', 'phone');
        $this->load->model('users_model', 'users');
        // $this->Phone_model->setPhone($user_id, $phone);
        #can't add phone number after 3 days after registration
        $usr = $this->users->getCurrUserData();

        $get_phone_by_user_id = $this->phone->get_phone_by_user_id( $user_id );

        #устновить можно, если телефон пуст
        if( !empty( $get_phone_by_user_id ) && !empty( $get_phone_by_user_id->phone_number ) )
        {
            return $this->send_ajax_responce( ['error' => _e('Невозможно изменить телефон на данной странице. Перейдите на страницу <a href="/account/profile/">Профиль<a/> и измените телефон.')] );
        }

        $this->phone->setPhoneWithCode($user_id, $code, $short_name, $phone);

        return $this->send_ajax_responce(['success' => '']);
    }

    public function get_my_phone(){
        $this->load->model('Phone_model', 'Phone_model');
        $phone = $this->Phone_model->get_phone_by_user_id(75705622);
        print_r($this->code->decode($phone->phone_number));
    }

    public function exchangeUsersSets(){
        //if (!in_array(get_current_user_id(), getExchangeUsers() ))
        //      redirect('account/transactions');
        $this->load->model('Exchange_users_sets_model');
        if(!empty($_POST)){
            $this->Exchange_users_sets_model->set(get_current_user_id(), $_POST);
            accaunt_message($data, _e('Успешно сохранено'));
        }

        $data->sets = $this->Exchange_users_sets_model->get_sets(get_current_user_id());
        $this->content->user_view("exchangeUsersSets", $data, _e('Настройки процентов'));
    }

    public function mistakes_get(){
        $this->load->view('user/blocks/mistakes');
    }

    public function mistakes_post(){
        # Заголовок сообщения - замените "yousite.ru" на имя своего сайта:
        $title   = 'Сообщение об ошибке на сайте '.base_url();
        $ip      = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
        $url     = (trim($_POST['url']));
        $mis     = substr(htmlspecialchars(trim($_POST['mis'])), 0, 100000);
        $comment = substr(htmlspecialchars(trim($_POST['comment'])), 0, 100000);

        $user = $this->accaunt->userFields('phone, name, sername, email');
        $user_info =   $user->sername.' '.$user->name.'('.get_current_user_id().') E-Mail: '.$user->email;

        $mess = 'Адрес страницы: '.$url.'
            Ошибка: '.$mis.'
            Комментарий: '.$comment.'
            IP: '.$ip.'
            Пользователь: '.$user_info.'
            '.$_POST['mess'];


        $this->load->library('mail');
        $mails = config_item('mistakes_to_mails');
        foreach($mails as $mail)
            $res   = $this->mail->send_ex($mail, $mess, $title, NULL, TRUE);

        echo json_encode($res);
    }



    

    public function under_construction(){
        $this->load->view('user/under_contruction');
    }


}
