<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php';}

class Card extends Accaunt_base {
    public function __construct() {
        $login = parent::__construct();
        $user_id = $this->accaunt->get_user_id();

        if ($login)
            viewData()->banner_menu = "profil";
        else
            viewData()->banner_menu = "profile_login";

        viewData()->secondary_menu = "card";
        //viewData()->page_name = null;
        $this->content->clientType = 1;

        require_once APPPATH.'controllers/user/Security.php';
        viewData()->security = Security::getProtectType($user_id);
        $this->base_model->generate_page_hash();
    }


    public function create(){
        $data = new stdClass();
        $this->content->user_view("card/create_card", $data, _e('Заказать карту Webtransfer Visa'));
    }


    public function lst(){

        viewData()->page_name = __FUNCTION__;
        $this->load->model('card_model','card');

        $data = new stdClass();
        $data->id_user = $this->accaunt->get_user_id();
        $data->wtcards_list = [];
        $wtcards = $this->card->getCards();


        // активация карты
        if ( !empty($_POST) && isset($_POST['action']) && $_POST['action']=='activate' )
        {
            $card_id = $this->input->post('card_id');
            $card = $this->card->getUserCard($card_id);
            if ( empty($card) ){
                $data->error = _e('Не выбрана карта');
            } else {
                $resp =  $this->card->setPassword($card);

                if(isset($resp['errorDetails']) && '0' != $resp['errorDetails'][0]['errorCode']){
                    accaunt_message(new stdClass(), $resp['errorDetails'][0]['errorDescription'], 'error');
                     $this->content->user_view("card/card_list", $data, _e('Список карт'));
                    return;
                }

                if ( $card->card_type == Card_model::CARD_TYPE_PLASTIC ){
                    $pan = $this->input->post('card_pan');
                    if( empty($pan)){
                        accaunt_message(new stdClass(), _e('Не указан PAN'), 'error');
                         $this->content->user_view("card/card_list", $data, _e('Список карт'));
                        return;
                    }
                    $resp = $this->card->activatePlasticCard($card, $pan);
                } else {
                    $resp = $this->card->activateVirtualCard($card);
                }

                if(isset($resp['errorDetails']) && '0' != $resp['errorDetails'][0]['errorCode'])
                    accaunt_message($data, $resp['errorDetails'][0]['errorDescription'], 'error');
                else
//                    accaunt_message(new stdClass(), _e('Карта успешно активирована. Текущий статус карты: ').$resp['newStatus']);
                    accaunt_message($data, _e('Карта активирована. Текущий статус карты: ').$resp['newStatus']);

            }
        }

        // оплата карты
        if ( !empty($_POST) && isset($_POST['action']) && $_POST['action']=='pay' )
        {
            $request_id = $this->input->post('request_id');
            $res = $this->card->pay_request($request_id, $data->id_user);
            if ( $res === TRUE )
                accaunt_message($dt, _e('Заявка успешно оплачена'));
            else {
                accaunt_message($dt, $res, 'error');
            }
        }
        // отмена заказа карты
        if ( !empty($_POST) && isset($_POST['action']) && $_POST['action']=='cancel' )
        {
            $request_id = $this->input->post('request_id');
            $this->card->cancel_request($request_id, $data->id_user);
            accaunt_message($dt, 'Заяка успешно отменена');
        }

        if ( !empty($wtcards))
        {
            foreach ($wtcards as $card){
                $card->last_balance = $this->card->getCardBalance($card);
                $card->details = $this->card->getCardDetails($card);
                $data->wtcards_list[ $card->details['cardDetail']['cardStatus'] ][] = $card;
            }
        }
        $data->wtcards_list['NOT_PAID'] = $this->card->getUnpayedCards($data->id_user);



        $this->load->model("users_model","users");
        $wallets = $this->users->get_ewallet_data();
        $data->payeer = $wallets->payeer;
        $data->okpay = $wallets->okpay;
        $data->perfectmoney = $wallets->perfectmoney;


        if ( !empty($data->wtcards_list['NOT_PAID']))
            foreach ($data->wtcards_list['NOT_PAID'] as &$card){

                if ( strtotime($card->created) > strtotime('2015-06-01'))
                    $card->cost = 22.95;
                else
                    $card->cost = 10;

            }


        $this->content->user_view("card/card_list", $data, _e('Список карт'));
    }
	
// IFRAME GOVNOHACK grim6681

    public function create_wtcard_merchant_form($price, $request_id){
        
        function getSignature($arHash){
            $collect = [];
            foreach($arHash as $key => $value)
            {
                if(is_array($value)) {
                    $value = json_encode($value);
                }
                $collect[] = $key . "=" . $value;
            }
            sort($collect);
            $current = md5(implode("", $collect) . 'U2HYe8fh4cbyueg343');
            return $current;        
        }

        $currency_id = '840';
        $params = [
            'shop_id'   => 'S021018678211',
            'order_id'  => $request_id,
            'order_sum' => number_format($price, 2, '.', ''),
            'currency_id' => $currency_id,
            'order_description' => 'Pay for plastic card request #'.$request_id
            ];

        $signature = getSignature($params);
        


        $paysystems = '';
    
        echo '<form method="POST" action="https://webtransfercard.com/en/payment/select" target="_blank" name="frm" id="frm">
                <input type="hidden" name="shop_id" value="'.$params['shop_id'].'">
                <input type="hidden" name="order_id" value="'.$params['order_id'].'">
                <input type="hidden" name="order_sum" value="'.$params['order_sum'].'">
                <input type="hidden" name="currency_id" value="'.$params['currency_id'].'">
                <input type="hidden" name="order_description" value="'.$params['order_description'].'">
                '.$paysystems.'
                <input type="hidden" name="signature" value="'.$signature.'">
                <!--input type="submit" name="process" value="'._e('Перейти к оплате').'" /-->
            </form>
            <script language="JavaScript">document.frm.submit();</script>'._e('Переадресация...');
      
        
    }

    public function card($id = NULL) {
        viewData()->page_name = __FUNCTION__;
        $data = new stdClass();
        $this->load->model("card_model","card");
        $this->load->model('accaunt_model','accaunt');

        $data->card = null;

        $user = new stdClass();
        $this->accaunt->get_user($user);

        $user_cards = $this->card->getCards();
        $data->is_already_card_received = !empty($user_cards);

        $action = 'new';
        if ( empty($id) ||  $id == 'plastic' || $id == 'virtual' ){
            $card = new stdClass();
            $card->card_type = ($id == 'plastic')?Card_model::CARD_TYPE_PLASTIC:Card_model::CARD_TYPE_VIRTUAL;
            $card->id_user = $this->accaunt->get_user_id();
            $card->holder_name = $this->card->generateHolder($user);
            $card->name = $user->user->name;
            $card->surname = $user->user->sername;
            $card->birthday = $user->user->pasport_born;
            $card->prop_adress = trim($user->adr_r->index . " " . $user->adr_r->town . " " . $user->adr_r->street . " " . $user->adr_r->house . " " . $user->adr_r->flat);
            $card->city = $user->adr_r->town;
            $card->zip_code = $user->adr_r->index;
            $card->country = $user->user->place;
            $card->phone_mobile = $user->user->phone;
            $card->phone_home = $user->user->phone;
            $card->email = $user->user->email;
            $card->delivery_address = trim($user->adr_f->index . " " . $user->adr_f->town . " " . $user->adr_f->street . " " . $user->adr_f->house . " " . $user->adr_f->flat);
            $card->delivery_city = $user->adr_f->town;
            $card->delivery_zip_code = $user->adr_f->index;
            $card->delivery_country = $user->user->place;

        } else {
            $action = 'edit';
            $card = $this->card->get_request( (int)$id, $this->accaunt->get_user_id() );
            if (empty($card))
                redirect('/');
        }


        $data->card = $card;
        $data->user = $user;


        $data->verified = $this->accaunt->isUserAccountVerified();
        $data->docs_exists = $this->accaunt->getAccessDocuments();
        


        if (!empty($_POST)){

            $card->holder_name = $this->input->post('holder_name');
            $card->name = $this->input->post('name');
            $card->surname = $this->input->post('surname');
            $card->birthday = $this->input->post('birthday');
            $card->prop_adress = $this->input->post('prop_adress');
            $card->city = $this->input->post('city');
            $card->zip_code = $this->input->post('zip_code');
            $card->country = $this->input->post('country');
            $card->phone_mobile = $this->input->post('phone_mobile');
            $card->phone_home = $this->input->post('phone_mobile');
            //$card->phone_home = $this->input->post('phone_home');
            // если у пользователя есть уже карта и мы создаем новую то поле делаем генерируемое, если это редактирование - то емэйл не меняем
            if ( $data->is_already_card_received  ) {
                if ( $action == 'new' )
                    $card->email = 'AUTO';
            } else
                $card->email = $this->input->post('email');
            if (  $card->card_type == Card_model::CARD_TYPE_VIRTUAL ){
                $card->delivery_address = $card->prop_adress;
                $card->delivery_city = $card->city;
                $card->delivery_zip_code = $card->zip_code;
                $card->delivery_country =  $card->country;
            } else {
                $card->delivery_address = $this->input->post('delivery_address');
                $card->delivery_city = $this->input->post('delivery_city');
                $card->delivery_zip_code = $this->input->post('delivery_zip_code');
                $card->delivery_country = $this->input->post('delivery_country');
            }
            $data->card = $card;



            $have_funds = TRUE;
            if ( $card->card_type == Card_model::CARD_TYPE_VIRTUAL  && $this->base_model->getRealMoney($card->id_user) < 1 )
                    $have_funds = FALSE;

            if ($card->id_user==92156962)$have_funds = TRUE;
            if (!$data->verified) {
                accaunt_message($data, _e('card/data3'), 'error');
            } elseif (!$have_funds){
                accaunt_message($data, _e('У вас не достаточно средств для оформления заявки на карту'), 'error');
            } elseif (!$data->docs_exists && $card->card_type == Card_model::CARD_TYPE_PLASTIC){
                accaunt_message($data, _e('У вас не загружены документы'), 'error');
            } else{

                    $errors = $this->card->validate($card);
                    if(empty($errors) && $this->input->post("dogovor") == 'true'){
                        if ( $action == 'edit' ){
                            $this->card->save_request($id, $card);
                            accaunt_message($data, _e('Данные успешно изменены.'));
                            redirect('account/card-lst');

                        }
                        $card->status = "created";
                        $res = $this->card->create_request($card, $user->user->email );
                        if ( $res === TRUE){
                            $this->mail->user_sender('get_card', $card->id_user, []);
                            $mes = _e('Спасибо за заказ! Ваша платежная карта Webtransfer Visa Debit Card находится в процессе выпуска и после изготовления будет отправлена на указанный вами адрес. Вы сможете использовать вашу новую пластиковую карту для снятия средств в любом банкомате, подключённом к сети Visa, а также для оплаты покупок в обычных и онлайн-магазинах.<br><br>Данное письмо является подтверждением принятия Вашего заказа. Когда Ваша карта будет готова к отправке, Вы получите еще одно письмо. До этого момента просим Вас загрузить в профиль копию Вашего паспорта и документа, подтверждающего Ваш адрес регистрации или места жительства.<br><br>С уважением, Webtransfer');
                            $this->load->model('inbox_model');
                            $this->inbox_model->writeMess2Inbox($card->id_user, $mes);
                            accaunt_message($data, _e('Заявка успешно создана.'));
                            redirect('account/card-lst');
                        } else {
                            accaunt_message($data, _e('Не удалось создать карту.<br>'.$res), 'error');
                        }
                    }else if('true' != $this->input->post("dogovor")){
                        accaunt_message($data, _e('Вы не согласны с условиями оказания услуг.'), 'error');
                    }else {
                        accaunt_message($data, _e('Ошибка в полях: ').implode(", ", $errors), 'error');
                    }
                }
            }

        $title =  _e('card/title');
        if (  $card->card_type == Card_model::CARD_TYPE_VIRTUAL )
            $title =  _e('Webtransfer VISA Card (Virtual)');

        $this->content->user_view("card/card", $data, $title);
    }

    public function transactions($page){
        viewData()->page_name = __FUNCTION__;
        $data = array("errors"=>null,"transactions"=>null);
        $this->load->model("card_model","card");

        $user_id = $this->accaunt->get_user_id();
        $data['wtcards'] = $this->card->getCards($user_id);
        $data['days'] = [30,60,90];
        $data['days_selected'] = 30;
        
        $data['page'] = (!isset($_REQUEST['page']))?0:(int)$_REQUEST['page'];
        $per_page = $data['per_page'] = 10;
        $offset = $data['page'];
        
        
        
        if ( empty($data['wtcards']) ){
            $data['errors'] = _e("У вас нет ни одной карты");
        } else {
            $data['wtcard_selected'] = $data['wtcards'][0]->id;
            if (isset($_REQUEST['selected']))
               $data['wtcard_selected'] = (int)$_REQUEST['selected'];
            if (isset($_REQUEST['card']))
               $data['wtcard_selected'] = (int)$_REQUEST['card'];
            if (isset($_REQUEST['days']) && in_array((int)$_REQUEST['days'], $data['days']))
               $data['days_selected'] = (int)$_REQUEST['days'];
            

            $card = $this->card->getUserCard( $data['wtcard_selected'], $user_id );
            if(!empty($card)){
                $this->load->library('WTCApi');
                $wtcapi = new WTCApi($card->card_user_id, $card->card_proxy);
                
                // добавим данные для карты
                $card->last_balance = $this->card->getCardBalance($card);
                $card->details = $this->card->getCardDetails($card);
                $data['card']= $card;                
                
                // прогоноим переданные данные через фильтры для безопасности
                $data['input_filter']['date_from'] = empty($_REQUEST['date_from'])?'':date('Y-m-d', strtotime($_REQUEST['date_from']));
                $data['input_filter']['date_to'] = empty($_REQUEST['date_to'])?'':date('Y-m-d', strtotime($_REQUEST['date_to']));
                $data['input_filter']['amount_min'] = empty($_REQUEST['amount_min'])?'':(float)($_REQUEST['amount_min']);
                $data['input_filter']['amount_max'] = empty($_REQUEST['amount_max'])?'':(float)($_REQUEST['amount_max']);
                
                // фильтр по датам
                $filter = [];
                $filter['startDate'] =  date('c',  strtotime('-'.$data['days_selected'].' days'));
                $filter['endDate'] =  date('c');
                
                if ( !empty($data['input_filter']['date_from'])) $filter['startDate'] =  date('c', strtotime( $data['input_filter']['date_from'] ));
                if ( !empty($data['input_filter']['date_to'])) $filter['endDate'] = date('c', strtotime($data['input_filter']['date_to']));
                

                // фильтр по суммам
                $pagination = TRUE;
                $filter_fields = [];
                if (  $data['input_filter']['amount_min'] != ''){
                        $pagination = FALSE;
                        $filter_fields['billAmount'] =  ['type'=>'compare', 'compare_type'=>'better_equal', 'value'=>$data['input_filter']['amount_min']*100 ];
                }

                if (  $data['input_filter']['amount_max'] != ''){
                        $pagination = FALSE;
                        $filter_fields['billAmount'] =  ['type'=>'compare', 'compare_type'=>'less_equal', 'value'=>$data['input_filter']['amount_max']*100];
                }
                
                $transactions  = $this->card->search_transactions($card,[ 'fields' => $filter_fields, 'filter'=>$filter], $per_page, $offset);
                if(!empty($transactions))
                    $data['transactions'] = $transactions;
                else
                    $data['errors'] = "У вас нет ни одной транзакации, либо произошлка ошибка получения данных";                
                
                
                if ( isset($_POST['export'])){
                    if ( isset( $data['errors']) ){
                        echo $data['errors'];
                        return;
                    }
                    $this->load->helper('random');
                    $filename = 'cardhistory_' . $card->id . '_' . generate_password(20, 'ud') . '.pdf';
                    $pdf_content_html = $this->load->view('user/accaunt/card/pdf_templete', $data, TRUE);                    
                    $fullpath = $this->code->createPdf($pdf_content_html, 'cardhistory', $filename, false);
                    
                    //echo "<a target=\"_blank\" href='$fullpath'>"._e('Скачать файл')."</a>";
                    $download_link = site_url('account/card-get_pdf/').'/'.$filename;
                    echo "<a target=\"_blank\" href='$download_link'>"._e('Скачать файл')."</a>";
                    
                    return;
                    
                    
                }
                
                // пагинация
                $data['pages'] = '';
                if ( $pagination ){
                    $pagination_url_params = [
                        'card' => $card->id,
                        'days' =>  $data['days_selected'],
                        'date_from' => $data['input_filter']['date_from'],
                        'date_to' => $data['input_filter']['date_to'],
                        'amount_min' => $data['input_filter']['amount_min'],
                        'amount_max' => $data['input_filter']['amount_max']
                        ];

                    $total_trans = $wtcapi->transactions_count($filter);
                    $data['pages'] = $this->base_model->pagination($per_page,
                                $total_trans, 
                                'account/card-transactions',
                                4,
                                ['page_query_string'=>TRUE,
                                 'query_string_segment'=>'page',
                                 'suffix'=>'&' . http_build_query($pagination_url_params, '', "&"),
                                 'first_url' => site_url('/') .'/account/card-transactions?'.http_build_query($pagination_url_params, '', "&"),

                                ]);
                }
                
    

            }else{
                $data['errors'] = _e("Нет такой карты");
            }
        }
        $this->content->user_view("card/transactions", $data, _e('Список транзакций'));
    }
    
    public function get_pdf($file_name) {

        if (empty($file_name) || !preg_match('/cardhistory_(\d{1,20})_([0-9A-Z]{20}).pdf/', $file_name))
            return show_404();

        $path = 'upload/cardhistory/' . $file_name;
        if (!file_exists($path)) {
            return show_404();
        }

        $user_filename = 'card_transactions_'.date('Y-m-d-H-i-s').'.pdf';
        
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-disposition: attachment; filename="'.$user_filename.'"');
        readfile($path);
        unlink($path);
    }

    public function tariff() {
        viewData()->page_name = __FUNCTION__;

        $this->content->user_view("card/tariff", [], _e('Тарифы'));
    }

    public function terms() {
        viewData()->page_name = __FUNCTION__;

        $this->content->user_view("card/terms", [], _e('Условия'));
    }

    public function deals() {
        viewData()->page_name = __FUNCTION__;

        $this->content->user_view("card/deals", [], _e('Акции'));
    }


    public function removeOther($card_id){

        $back_url  = 'account/transactions';
        //if ( get_current_user_id() == 92156962)die( $_SERVER['HTTP_REFERER'] );
        if ( strpos($_SERVER['HTTP_REFERER'],'account/cloudwallet') !== FALSE )
            $back_url  = 'account/cloudwallet';


        if (Security::checkSecurity(get_current_user_id(), true)) {
             accaunt_message($data, _e('Неверный код'),'error');
             redirect($back_url);
         }

        $user_id = $this->accaunt->get_user_id();
        $this->card->removeOther($card_id,$user_id);
        accaunt_message($data, _e('Счет успешно удален'));
        redirect($back_url);
    }


    public function saveOther(){

        //$name = $this->input->post('name');
        $summa = $this->input->post('summa');
        $type = $this->input->post('type');

        $back_url  = 'account/transactions';
        //if ( get_current_user_id() == 92156962)die( $_SERVER['HTTP_REFERER'] );
        if ( strpos($_SERVER['HTTP_REFERER'],'account/cloudwallet') !== FALSE )
            $back_url  = 'account/cloudwallet';

        $nums = $this->input->post('BANK_CARD_NUM');

        if (Security::checkSecurity(get_current_user_id(), true)) {
             accaunt_message($data, _e('Неверный код'),'error');
             redirect($back_url);
         }

        $account_extra_data = $this->input->post($type.'_account_extra_data');
        $name = $this->input->post($type.'_name');
        $id = $this->input->post('id');

        if ( $type == 'BANK_ACCOUNT'){
            $account_extra_data = [
                'wire_beneficiary_name' => $this->input->post('wire_beneficiary_name'),
                'wire_beneficiary_bank_country' => $this->input->post('wire_beneficiary_bank_country'),
                'wire_sort' => $this->input->post('wire_sort'),
                'wire_beneficiary_account' => $this->input->post('wire_beneficiary_account'),
                'wire_beneficiary_swift' => $this->input->post('wire_beneficiary_swift'),
                'wire_corresponding_bank' => $this->input->post('wire_corresponding_bank'),
                'wire_corresponding_bank_swift' => $this->input->post('wire_corresponding_bank_swift'),
                'wire_corresponding_account' => $this->input->post('wire_corresponding_account')
                ];
                $account_extra_data = json_encode($account_extra_data);
        }

        if ( (empty($name) && $type != 'E_WALLET') || empty($summa) || empty($type) || empty($account_extra_data)  ){
              accaunt_message($data, _e('Пустые поля недопустимы'),'error');
             redirect($back_url);
        }

        $card_num = '';
        if ( $type == 'BANK_CARD'){
            $card_num = implode('-', $nums);
            if ( empty($nums[0]) || empty($nums[1]) || empty($nums[2]) || empty($nums[3])){
                 accaunt_message($data, _e('Пустые поля недопустимы'),'error');
                 redirect($back_url);
            }
        }

        $own_wallet = '';
        $account_personal_num = NULL;
        if ( $type == 'E_WALLET' ){
            $account_personal_num  = $this->input->post('account_personal_num');

            if ($account_extra_data == 'Other')
            $own_wallet = $this->input->post('ewallet_own_name');

            $name = '';
        }

        if (!is_numeric($summa)){
              accaunt_message($data, _e('Сумма должна содержать только цифры'),'error');
             redirect($back_url);
        }

        $user_id = $this->accaunt->get_user_id();

        if ( empty($id) )
            $res = $this->card->addOther($user_id, $name, $summa, $type, $account_extra_data, $card_num, $own_wallet, $account_personal_num);
        else
            $res = $this->card->editOther($id, $user_id, $name, $summa, $type, $account_extra_data, $card_num, $own_wallet, $account_personal_num);

        if ( $res === TRUE)
            accaunt_message($data, _e('Счет успешно сохранен'));
        else
            accaunt_message($data, $res,'error');
        redirect($back_url);
    }



    function getOther($id){
        $res = $this->card->getUserOther( $id);
        if ( $res->account_type == 'BANK_ACCOUNT'){
            $res->account_extra_data = json_decode($res->account_extra_data);
        }
        echo json_encode($res);


    }
    /*
    public function balance(){ // type ajax
        $id_user = $this->accaunt->get_user_id();
        $this->load->model('Card_model');
        $cm = new Card_model();

        if($cm->get_for_user($id_user)){
            if($cm->status() < Card_model::STATUS_VERIFIED){
                echo json_encode(array("errors"=>_e('Ваша карта не верифицирована')));
                return;
            }

            $this->load->library('WTCApi');
            $wtcapi = new WTCApi($cm->card_user_id, $cm->card_proxy);
            $data['answer'] = 0.0;
            $balance = $wtcapi->balance();

            if($balance !== null){
                $data['answer'] = number_format($balance, 2, '.', ' ');
            }
            echo json_encode($data);
            return;
        }
        echo json_encode(array("errors"=>_e("Невозможно получить баланс")));
        return;
    }

    public function pin() {
        require_once APPPATH.'controllers/user/Security.php';
        $id_user = $this->accaunt->get_user_id();
        Security::setProtectType('email');
        $e = Security::checkSecurity($id_user, false, true);
        if($e){
            echo "{error:'$e'}";
            return;
        }

        $this->load->model('Card_model');
        $cm = new Card_model();
        $cm->get_for_user($id_user);

        $this->load->library('WTCApi');
        $wtcapi = new WTCApi($cm->card_user_id, $cm->card_proxy);
        $r = $wtcapi->getPin();
        echo json_encode(['error' => $wtcapi->getError(), "pin" => (isset($r['pin']) ? $r['pin'] : '')]);
    }

    public function activete(){
        $id_user = $this->accaunt->get_user_id();
        $this->load->model('Card_model');
        $cm = new Card_model();

        if($cm->get_for_user($id_user) && "true" == $this->input->post("active")){
            if($cm->status() < Card_model::STATUS_VERIFIED and false){
                accaunt_message(new stdClass(), _e('Ваша карта не верифицирована'), 'error');
                redirect(site_url('account/card'));
                return;
            }

            $this->load->library('WTCApi');
            $wtcapi = new WTCApi($cm->card_user_id, $cm->card_proxy);
            $PAN = (int)$this->input->post("PAN", TRUE);
            $resp = $wtcapi->activate($PAN);

            if(isset($resp['errorDetails']) && '0' != $resp['errorDetails'][0]['errorCode'])
                accaunt_message(new stdClass(), $resp['errorDetails'][0]['errorDescription'], 'error');
            else if(isset($resp['errorDetails']) && '0' == $resp['errorDetails'][0]['code'])
                accaunt_message(new stdClass(), _e('Текущий статус карты: ').$resp['newStatus']);
            else accaunt_message(new stdClass(), _e('Возникли проблемы'), "error");
            redirect(site_url('account/card'));
            return;
        }
        accaunt_message(new stdClass(), _e('Невозможно поменять статус'), 'error');
        redirect(site_url('account/card'));
        return;
    }
     */



    public function transactions_summ_by_service(){
            $this->load->model("transactions_model", "transactions");
            $card_id = $this->input->get_post('card_id');
            $card = $this->card->getUserCard( $card_id, get_current_user_id());
            $summ = 0;
            if(!empty($card)){
                $result = $this->card->search_transactions($card,[
                    'fields' => [
                        'comment' => ['type'=>'regexp', 'match'=>'/.*(MYCUS000|top-up).*/'],
                        'txnType' => ['type'=>'compare', 'compare_type'=>'equal', 'value'=>'Load'],
                        'status' => ['type'=>'compare', 'compare_type'=>'equal', 'value'=>'Success'],
                        'tranDate' => ['type'=>'date_compare', 'compare_type'=>'better_equal', 'value'=>'2016-02-10 00:00:00']
                    ]
                ]);

                if (!empty($result)){
                    foreach ($result as $r){
                        $summ += round($r['transactionAmount']/100,2);
                        $this->transactions->payEnvoyPaymentBonus($r['transactionId'],$card->user_id, $r['transactionAmount']/100 );
                    }
                }



            }
            $user_ratings = viewData()->accaunt_header;
            $summ -= $user_ratings['total_active_usd2_fee_invests'];
            echo json_encode(['summ'=>$summ]);
    }


    public function fast_arbitrage(){
        viewData()->secondary_menu = "arbitrage";
        viewData()->page_name = __FUNCTION__;        
        $this->content->user_view("card/arbitr_card_list", $data, _e('Арбитраж'));
    }

    public function start_arbitrage(){
        $summ = $this->input->post('summ');
        $card_id = $this->input->post('card_id');
        $this->load->model('card_model','card');



        $res = $this->card->start_arbitrage($card_id, $summ);
        accaunt_message($data, $res->message, $res->status);
        redirect(site_url('account/card-fast_arbitrage'));

    }

    public function stop_arbitrage(){
        $card_id = $this->input->post_get('card_id');
        $this->load->model('card_model','card');



        $res = $this->card->stop_arbitrage($card_id);
        accaunt_message($data, $res->message, $res->status);
        redirect(site_url('account/card-fast_arbitrage'));

    }

}
