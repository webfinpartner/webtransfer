<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Merchant extends SimpleREST_controller{
    private  $Mrch_log;
    private $id_user;
    public function __construct() {
        parent::__construct();
        $this->load->model("video_model","video");
        $this->load->model('shop_model','shop');
        $this->load->model('accaunt_model','accaunt');
        $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша
        if(!$this->base_model->login($this, false)){
            if('pay' == $this->uri->rsegment(2)){
                $_SESSION['merchant'] = $_POST;
                $_SESSION['merchant']['time'] = time();
                return;
            }
            redirect(site_url(), 'refresh');
        }

        if(empty($_POST)){
            $_POST = getSession('merchant');
            if(null !== getSession('merchant'))
                unset($_SESSION['merchant']);
        }

        $this->id_user = $this->accaunt->get_user_id();
        $this->user_name = viewData()->user_name;

        viewData()->banner_menu = "profil";
        viewData()->secondary_menu = "shop";
        $this->content->clientType = 1;
        $this->Mrch_log = config_item("Merchnt_log");

        # Типы активации
        $this->activation = array ( //'create','enable','ban','del'
            'create' => "Ожидает",
            'enable' => "Активирован",
            'ban' => "Заблокирован",
            'del' => "Удален"
            );
    }

    # Главная страница (Список торговых площадок)
    public function index() {

        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        # Список магазинов
        $data->list = $this->shop->getShops();
        $data->status = $this->activation;
        $this->content->user_view("merchant_index", $data, 'Ваши магазины');
    }

    # API документация
    public function api(){
        viewData()->page_name = __FUNCTION__;
        $data = new stdClass();
        $this->content->user_view("merchant_api", $data, 'API документация');
    }

    # Добавление магазина
    public function add() {

        # Типы протоколов
//        $protocols = array ('0' => "http", '1' => "https");

        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;

        # Проверка на пас. данные
        if (!$this->accaunt->getAccessDocuments())
        {
            $data->err = "Для добавления магазина, требуется загрузить паспортные данные.";
        }

        # Лимит
        if (count($this->shop->getShops()) >= 3)
        {
            $data->err = "Вы привысили лимит добавления площадок.";
        }

        if ($this->input->post('link') && count($this->shop->getShops()) < 3 && $this->accaunt->getAccessDocuments())
        {
           // if (!eregi("/", $this->input->post('link')))
            if (!preg_match("/\//", $this->input->post('link')))
                $data->err = "Не верно указан адрес.";
            else
            {
                $result = $this->_get($this->input->post('link'));

                # Проверяем на наличае мета тега
                if ($result !== FALSE)
                    $result = preg_match("/<meta name=\"webtransfer\" content=\"" . md5($this->id_user) . "\">/i", $result) == TRUE;

                # Если нет данный о индексной странице, получаем созданный файл
                if (!$result)
                {
                    $result = $this->_get($this->input->post('link') . "/webtransfer_" . md5($this->id_user) . ".txt");
                    $result = $result == md5($this->id_user);
                }

                # Если не соответствует активационным данным
                if (!$result)
                    $data->err = "Необноружен активационный файл или метатег.";

                # Если данный магазин уже создан (У этого пользователя)
                if ($shop_id = $this->shop->duplicate($this->id_user, $this->input->post('link')))
                    $data->err = "Данный магазин у вас уже создан.";
                else
                {
                    # Сбрасываем активацию если вдруг у другого пользователя уже есть этот магазин
                    $this->shop->duplicate_deactivation($this->id_user, $this->input->post('link'));

                    # Создаем магазин
                    $shop_id = $this->shop->create($this->id_user, $this->input->post('link'));

                    # Переадресация на страницу редактирования
                    redirect(site_url('account/merchant/edit/' . $this->shop->slash($shop_id)), 'refresh');
                }
            }
        }

        $this->content->user_view("merchant_add", $data, 'Добавление магазина');

    }

    public function transactions($page = 0) {
        $this->load->model('shop_transactions_model','shop_transactions');
        $data = new stdClass();
        $per_page = "10";
        $data->pages = $this->base_model->pagination($per_page, $this->shop_transactions->getCountPays($this->id_user), 'account/merchant/transactions', 5);

        $data->pays = $this->shop_transactions->getPays($per_page, (int) $page, $this->id_user);

        $this->content->user_view("merchant_transactions_all", $data, 'Транзакции');
    }

    public function showTransaction($shop_id, $iframe = false) {
        $data = new stdClass();
        # Наш магазин
        $data->shop = $this->shop->getShops($this->shop->slash($shop_id, TRUE));
        if (isset($_POST["def"])) {
            echo json_encode(array(
//                    "title" => $type,
                    "columns" => array(
                        "date" => array(
                            "title" =>  "Дата",
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "description" => array(
                            "title" =>  "Описание",
                            "filter"=> false,
                            "order" =>  true
                        ),
                        "amount"=> array(
                            "title" =>  "Сумма",
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "order_id" => array(
                            "title" =>  "Ордер",
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "transaction_id" => array(
                            "title" =>  "Транз.",
                            "filter"=> false,
                            "order" =>  true,
                        ),
                        "other"=> array(
                            "title" =>  "Другое",
                            "filter"=> false,
                            "order" =>  true,
                        )

                    )
                ));
            die;
        }
        if(isset($_POST["offset"])){
            $this->load->model('shop_transactions_model','shop_transactions');
            $params = array();
            $params['search'] = $this->input->post('search');
            $params['per_page'] = $this->input->post('per_page');
            $params['offset'] = $this->input->post('offset');
            $params['order'] = $this->input->post('order');
            $params['shop_id'] = $data->shop->shop_id;

            echo json_encode( $this->shop_transactions->getAll($params) );
            die;
        }

        $data->url = site_url("/account/merchant/showTransaction/$shop_id/true");

        if(!$iframe)
            $this->content->user_view("merchant_transactions", $data, 'Магазин «' . base64_decode($data->shop->url) . '». Транзакции.');
        else
            $this->load->view("user/accaunt/merchant_transactions_iframe", $data);
    }

    # Редактирование торговой площадки
    public function edit($shop_id = NULL, $action = FALSE, $string = FALSE) {
        $this->load->helper('form');

        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;

        # Наш магазин
        $data->shop = $this->shop->getShops($this->shop->slash($shop_id, TRUE));

        # Если не наш магазин
        if ($data->shop->user_id != $this->id_user)
        {
            redirect(site_url('account/merchant'), 'refresh');
            return;
        }

        # Дополнительные компаненты
        $data->shop->shop_slash = $shop_id;
        $data->shop->string_slash = $this->shop->slash($data->shop->string);


        # Типы активации
        $data->activation = $this->activation;

        # Активация магазина
        if ($action == "activation" && $string == $data->shop->string_slash)
        {
            if ($data->shop->status != Shop_model::CREATE)
            {
                $data->notification = "<notification>Магазин не может быть повторно активирован</notification>";
            }
            else
            {
                # Запрет на создание
                if (count($this->shop->getShops()) >= 3 || !$this->accaunt->getAccessDocuments())
                {
                    $data->notification = "<notification>Магазин не может быть активирован, так как привышен лимит активных магазинов (3)</notification>";
                }
                else
                {
                    # Получаем содержимое индексной страницы
                    $result = $this->_get(base64_decode($data->shop->url));

                    # Проверяем на наличае мета тега
                    if ($result !== FALSE)
                    {
                        $result = eregi("<meta name=\"webtransfer\" content=\"" . md5($this->id_user) . "\">", $result) == TRUE;
                    }

                    # Если нет данный о индексной странице, получаем созданный файл
                    if (!$result)
                    {
                        $result = $this->_get(base64_decode($data->shop->url) . "/webtransfer_" . md5($this->id_user) . ".txt");
                        $result = $result == md5($this->id_user);
                    }

                    # Если не соответствует активационным данным
                    if (!$result)
                    {
                        $data->notification = "<notification>Магазин не может быть активирован, так как не найден метатег и файл.</notification>";
                    }
                    else
                    {
                        # Сбрасываем активацию если вдруг у другого пользователя уже есть этот магазин
                        $this->shop->duplicate_deactivation($this->id_user, $data->shop->url);

                        # Активация магазина
                        $data->shop->status = Shop_model::ACTIVE;
                        $this->shop->save($data->shop->shop_id, array('status' => Shop_model::ACTIVE));

                        # Уведомление
                        $data->notification = "<notification>Магазин активирован.</notification>";
                    }
                }
            }
        }

        # Изменение ключа
        if ($action == "string" && $string == $data->shop->string_slash)
        {
            if ($data->shop->status == Shop_model::BLOCKED)
            {
                $data->notification = "<notification>Магазин не может быть изменен, так как он является заблокированным</notification>";
            }
            else
            {
                $data->shop->string = strtoupper(random_string('alnum', 24));
                $data->shop->string_slash = $this->shop->slash($data->shop->string);
                $this->shop->save($data->shop->shop_id, array('string' => $data->shop->string));

                $data->notification = "<notification>Секретный ключ изменен</notification>";
            }
        }

        # Деактивация/активация магазина
        if ($action == "delete" && $string == $data->shop->string_slash)
        {
            if ($data->shop->status == Shop_model::BLOCKED)
            {
                $data->notification = "<notification>Магазин не может быть удален, так как является заблокированным</notification>";
            }
            else if ($data->shop->status == Shop_model::DELETED)
            {
                $data->shop->status = Shop_model::CREATE;
                $this->shop->save($data->shop->shop_id, array('status' => Shop_model::CREATE));

                $data->notification = "<notification>Магазин успешно включен, но не активирован</notification>";
            }
            else
            {
                $data->shop->status = Shop_model::DELETED;
                $this->shop->save($data->shop->shop_id, array('status' => Shop_model::DELETED));

                $data->notification = "<notification>Магазин успешно дезактивирован</notification>";
            }
        }

        # Изменение магазина
        if ($this->input->post('type') == "edit")
        {
            if ($data->shop->status == Shop_model::BLOCKED)
            {
                $data->notification = "<notification>Магазин не может быть изменен, так как он является заблокированным</notification>";
            }
            else
            {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('title', 'Торговое имя', 'required|min_length[6]|max_length[85]');
                $this->form_validation->set_rules('url_success', 'URL успешной оплаты', 'required');
                $this->form_validation->set_rules('url_fail', 'URL неуспешной оплаты', 'required');
                $this->form_validation->set_rules('url_result', 'URL обработчика', 'required');
                $this->form_validation->set_rules('section', 'Категория', 'in_myarray_r['.implode('.', array_flip(getCategories())).']');
                $this->form_validation->set_rules('tel', 'Телефон поддержки', 'required|min_length[7]|max_length[15]|regex_match[/^[0-9]{7,15}$/sui]');
                $this->form_validation->set_rules('email', 'Email поддержки', 'required|valid_email');

                $data->shop->title = $data->save->title = base64_encode(htmlspecialchars($this->input->post('title')));
                $data->shop->url_success = $data->save->url_success = base64_encode($this->input->post('url_success'));
                $data->shop->url_fail = $data->save->url_fail = base64_encode($this->input->post('url_fail'));
                $data->shop->url_result = $data->save->url_result = base64_encode($this->input->post('url_result'));
                $data->shop->section = $data->save->section = (int)$this->input->post('section');
                $data->shop->tel = $data->save->tel = base64_encode(htmlspecialchars($this->input->post('tel')));
                $data->shop->email = $data->save->email = base64_encode(htmlspecialchars($this->input->post('email')));
                $data->shop->commission = $data->save->commission = ($this->input->post('commission') == 1) ? 1 : 0;

                if ($this->form_validation->run())
                {
                    $this->shop->save($data->shop->shop_id, $data->save);

                    $data->notification = "<notification>Данные обновлены</notification>";
                }
                else
                {
                    $this->form_validation->set_error_delimiters('<error>', '</error>');
                }
            }
        }

        # Обновление логотипа сайта
        if ($this->input->post('upload'))
        {
            $upload['upload_path'] = './upload/images/merchant/';
            $upload['allowed_types'] = 'jpg|png';
            $upload['max_size'] = '1024';
            $upload['max_width'] = '225';
            $upload['max_height'] = '225';
            $upload['file_name'] = md5($data->shop->shop_id) . "." . end(explode(".", $_FILES['logo']['name']));

            $this->load->library('upload', $upload);

            if (!$this->upload->do_upload('logo'))
                $data->notification = "<error full>" . $this->upload->display_errors() . "</error>";
            else
            {
                if (end(explode(".", $_FILES['logo']['name'])) != $data->shop->image)
                {
                    unlink('./upload/images/merchant/' . md5($data->shop->shop_id) . "." . $data->shop->image);
                }
                $data->shop->image = end(explode(".", $_FILES['logo']['name']));
                $this->shop->save($data->shop->shop_id, array('image' => end(explode(".", $_FILES['logo']['name']))));

                $data->notification = "<notification>Логотип успешно обновлен.</notification>";
            }
        }

        $this->content->user_view("merchant_edit", $data, 'Магазин «' . base64_decode($data->shop->url) . '»');
    }

    # Качаем все что только нам надо
    public function download(){
        $this->load->helper('download');

        $download['name'] = "webtransfer_" . md5($this->id_user) . ".txt";
        $download['content'] = md5($this->id_user);

        force_download($download['name'], $download['content']);
    }

    # Оплата
    public function pay(){
        if($this->Mrch_log)
            $this->base_model->log2file(date("Y-m-d H:i:s")." user #$this->id_user запрос POST=".print_r($_POST, true), "Merchant");
        $this->load->model('shop_transactions_model','shop_transactions');
        $this->load->model('users_model','users');
        $this->load->model('transactions_model','transactions');
        $data = new stdClass();
        $data->error = '';
        $ident = $this->input->post('shop_id');

        # Проверка, есть ли форма
        if (empty($ident))
        {
            $data->error = "Не верная форма запроса";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }
        $shop_id = $this->shop->slash($ident, TRUE);
        $data->shop = $this->shop->getShop($shop_id);
        $data->shop->shop_slash = $this->shop->slash($data->shop->shop_id);
        $this->accaunt->set_user($data->shop->user_id);
        $data->documents = (!$this->accaunt->getAccessDocuments()) ? FALSE : TRUE;
        $this->accaunt->set_user($this->id_user);

//        $otkuda=$_SERVER['HTTP_REFERER'];
//        $part_link = explode("://", $otkuda, 2);
//        $protocol = $part_link[0];
//        $rezz = array();
//        preg_match("/^($protocol:\/\/)?([^\/]+)/i", $otkuda, $rezz);
//        if(!($rezz[0] == base64_decode($data->shop->url) || $rezz[0] == "https://webtransfer-finance.com")){
//            $data->error = "Так оплатить не получится. Вы пришли из незарегестрированого места.";
//            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
//            return;
//        }

        # Проверка, можем ли оплачивать в магазине
        if ($data->shop->status != Shop_model::ACTIVE)
        {
            $data->error = "Магазин не найден, заблокирован или владелец не подтвердил свое владение.";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }

        # Проверка документов
        if (!$data->documents)
        {
            $data->error = "Владелец магазина не подвердил свою личность документами.";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }

        # Проверка есть ли вся нужная информация о магазине
        if (empty($data->shop->title) || empty($data->shop->url_success) || empty($data->shop->url_fail) || empty($data->shop->url_result))
        {
            $data->error = "Владелец магазина еще не заполнил все необходимую информацию для корректной работы магазина.";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }

        # Проверка полученных параметров
        if ($this->input->post('order_id') < 1 || $this->input->post('amount') < 0.01)
        {
            $data->error = "Переданы не все или не верные параметры счета для оплаты.";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }


        # Обрабатываем строки с которыми будет в дальнейшем работать
        $data->vars->amount = changeComa(round(changeComa($this->input->post('amount')), 2));
        $data->vars->order_id = (int) $this->input->post('order_id');
        $data->vars->description = htmlspecialchars($this->input->post('description'));
        $data->vars->commission = changeComa(round($data->vars->amount*floatval($data->shop->commission_psnt) / 100, 2));
        $data->vars->summary = changeComa($data->vars->amount + (($data->shop->commission == 1) ? $data->vars->commission : 0));
        $data->vars->other = htmlspecialchars($this->input->post('other'));
        $data->vars->csrf = htmlspecialchars($this->input->post('csrf'));
        $data->vars->currency = htmlspecialchars($this->input->post('currency'));
        $data->vars->pay_type = 2;//Base_model::TRANSACTION_BONUS_OFF;
        $data->vars->summ_view = '$ %s WTUSD<span style="color:red;">&#10084;</span>';
        $data->vars->hash = $this->input->post('hash');

          //dev_log("1.md5($shop_id:{$data->vars->order_id}:{$data->vars->amount}:{$data->vars->currency}:{$data->shop->string})=".md5($shop_id .':'. $data->vars->order_id .':'. $data->vars->amount .':'. $data->vars->currency .':'. $data->shop->string).'!='.$this->input->post('hash'));

        // проверка кода
         if ( $this->input->post('hash') != md5($shop_id .':'. $data->vars->order_id .':'. $data->vars->amount .':'. $data->vars->currency .':'. $data->shop->string) ){

             dev_log("2.md5($shop_id:{$data->vars->order_id}:{$data->vars->amount}:{$data->vars->currency}:{$data->shop->string})=".md5($shop_id .':'. $data->vars->order_id .':'. $data->vars->amount .':'. $data->vars->currency .':'. $data->shop->string).'!='.$this->input->post('hash'));

            $data->error = "Некоторые переменные были изменены при передаче.";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
         }

        $payout_limit = 0;
        //$bonus = $accaunt_header[ 'bonuses' ] - $accaunt_header[ 'all_advanced_invests_bonuses_summ' ];
        if("P_CREDS" == $data->vars->currency){
             $data->vars->pay_type = Base_model::TRANSACTION_BONUS_PARTNER;
             $data->vars->summ_view = '%s P-CREDS';
             $payout_limit =  viewData()->accaunt_header['payout_limit_by_bonus'][3];
        } elseif("C_CREDS" == $data->vars->currency){
             $data->vars->pay_type = Base_model::TRANSACTION_BONUS_CREDS_CASH;
             $data->vars->summ_view = '%s C-CREDS';
             $payout_limit =  viewData()->accaunt_header['payout_limit_by_bonus'][4];
        } elseif("WTUSD1" == $data->vars->currency){
             $data->vars->pay_type = 5;
             $data->vars->summ_view = '$ %s WTUSD1';
        } elseif("WTUSD2" == $data->vars->currency){
             $data->vars->pay_type = 2;
             $data->vars->summ_view = '$ %s WTUSD<span style="color:red;">&#10084;</span>';
        } elseif("WTDEBIT" == $data->vars->currency){
             $data->vars->pay_type = 6;
             $data->vars->summ_view = '$ %s WTDEBIT';
        } elseif("BONUS" == $data->vars->currency){
             $data->vars->pay_type = Base_model::TRANSACTION_BONUS_ON;
             $data->vars->summ_view = '%s BONUS';
        }



        if(!isset($_SESSION['csrf_m']))
            $_SESSION['csrf_m'] = md5(time()."2948kwljnropasO(%^$");

        $csrf = ($_SESSION['csrf_m'] == $data->vars->csrf) ? TRUE : FALSE;

        if(!$data->vars->csrf){
            $data->vars->csrf = $_SESSION['csrf_m'];
        }

        # Проверка дублирования
        if ($this->shop_transactions->isExistOrder($shop_id, $data->vars->order_id)){
            $data->error = "Попытка повторной оплаты по одному Order id";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
            return;
        }

        $data->id_user = isset($this->id_user) ? $this->id_user : FALSE;
        $data->user_name = isset($this->user_name) ? $this->user_name : FALSE;
        $data->isCanPay = isset($this->id_user) ? TRUE : FALSE;
        if(isset($this->id_user) && !empty($this->id_user)){
            $id_user = $data->shop->user_id; // тот кому платят деньги
            $own = $this->id_user; // тот кто платит деньги
            $summa = $data->vars->summary;
            //$rating = viewData()->accaunt_header;
            $rating = $this->accaunt->recalculateUserRating($this->id_user);
            if ( in_array($data->vars->pay_type, [1,2,3,4,5,6]) )
                $payout_limit = $rating['payout_limit_by_bonus'][$data->vars->pay_type];
            
            
            if ( $data->vars->pay_type == 6 &&!in_array($data->shop->user_id, config_item('exchangeUsersMerchantWTDebitEnabled'))){
                $data->error = "Магазину недоступен прием WTDEBIT";
                $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
                return;                
            }

            $this->load->model('documents_model', 'documents');
            $userDocumentStatus = $this->documents->getUserDocumentStatus();
            $send_transaction = new stdClass;
            $send_transaction->summa   = $summa;
            $send_transaction->own     = $own;
            $send_transaction->id_user = $id_user;
            $send_transaction->note    = $data->vars->description;
            $send_transaction->pay_type    =  $data->vars->pay_type;


           // - nalichie popolneniy > 50
          //- viplati (status = 3, metod out) doljni bit menshe 2x popolneniy. K primeru esli popolnil na 50, to viplati bili tolko do 100
        //- bonus 5, 3, 4 ravno ili bolshe (=>) 0
            $income_money_bonus_2 = $this->transactions->getAllInMoneyOfUser($this->id_user, 2);
            /*$is_can_to_pay_on_bonus2 =  ($income_money_bonus_2 >= 50 && $income_money_bonus_2*2 > $rating['money_sum_withdrawal'] &&
                 $this->base_model->getRealMoney($this->id_user, 3) >= 0 && $this->base_model->getRealMoney($this->id_user, 4) >=0 &&
                 $this->base_model->getRealMoney($this->id_user, 5) >=0);
             */
         $is_can_to_pay_on_bonus2 = true;// ($income_money_bonus_2 >= 1 || $rating['bonus_earn'] > 1);

            if ($rating) {


                if (!$this->accaunt->isUserAccountVerified()){
                    $data->isCanPay = false;
                    $data->error = "Аккаунт не верифицирован";
                } else if ($data->vars->pay_type == 2 && !$is_can_to_pay_on_bonus2  ){
                    $data->isCanPay = false;
                    $data->error = "Недостаточно средств 1";
                } else if ($summa > $payout_limit){
                    $data->isCanPay = false;
                  $data->error = "Недостаточно средств 2 $payout_limit";
            //    } else if ($this->transactions->getAllInMoneyOfUser_new($this->id_user) < 10){
            //        $data->isCanPay = false;
             //       $data->error = "Недостаточно средств2";
//                }else if ( viewData()->accaunt_header['overdue_standart_count'] > 0){
//                    $data->isCanPay = false;
//                    $data->error = "Есть просроченные кредиты стандарт";
//                }else if ( viewData()->accaunt_header['overdue_garant_count'] > 0){
//                    $data->isCanPay = false;
//                    $data->error = "Есть просроченные кредиты гарант";
//                else if ($this->accaunt->isUserUSorCA($id_user) || $this->accaunt->isUserUSorCA())
//                    $data->isCanPay = false;
                }else if( !in_array($own, getUnlimitedUsers()) && (0 <= $summa  && Documents_model::STATUS_PROVED != $userDocumentStatus) ){
                    $data->isCanPay = false;
                    $data->error = "У вас не загружены документы на webtransfer.com";
                    // $id_user - пользователь магазина $own - тот кто зашел и платит
                }else if(!(!in_array($id_user, getUntrastedUsers4Send()) && (in_array($own, getCheckedUsers()) || true === ($canSend = $this->accaunt->isCanSendMoney($send_transaction, true, true))))){
                    $data->isCanPay = false;
                    $data->error = $canSend;
                    // $id_user - пользователь магазина $own - тот кто зашел и платит
                }else if(in_array($id_user, config_item('exchangeUsersMerchantBayBonusProgram')) && (!$this->transactions->isCanPay2MerchantBayBonusProgram($summa, $id_user, $own) || 6 != $data->vars->pay_type)){
                    $data->isCanPay = false;
                    $data->error = "Нет доступных средств для вывода";
                }
            } else $data->isCanPay = false;

            if ($data->isCanPay){
                $data->security = Security::getProtectType($this->id_user);
                $data->security_res = Security::checkSecurity($this->id_user,true);
            }

            if (!$data->isCanPay){
                $hash_success = md5($ident.":".$data->vars->amount.":".$data->vars->currency .":".$data->vars->order_id.":".$data->vars->description.":0:".$this->shop->slash($data->shop->string));
                $send_data = array(
                    'shop_id' => $ident,
                    'amount' => $data->vars->amount,
                    'order_id' => $data->vars->order_id,
                    'description' => $data->vars->description,
                    'status' => 'fail',
                    'other' => $data->vars->other,
                    'currency' => $data->vars->currency,
                    'transaction_id' => 0,
                    'hash' => $hash_success
                );
                $count_send = 5;
                $res = false;
                while ($count_send && !$res) {
                    # Отправляем запрос на удаленный сервер
                    $res = $this->_get(base64_decode($data->shop->url_result),$send_data);
                    $count_send--;
                }
            }
        }

        $this->load->helper('form');

//if($this->Mrch_log) $this->base_model->log2file(date("Y-m-d H:i:s")." user #$this->id_user запрос POST=".print_r($data, true), "Merchant");
        if ($this->input->post('step') != 2 || !isset($_POST['agriment']) || $data->security_res)
            $this->load->view("user/accaunt/merchant_pay", $data);
        else if($data->isCanPay && $csrf){

            # Подключаем модели
            $this->load->model('transactions_model','transactions');
            $this->load->model('monitoring_model','monitoring');

            # Логируем операцию
            $this->monitoring->log( null, "Оплата счета №" . $data->vars->order_id . " в магазине " . $data->id, 'common', $this->id_user );

            $id0_status = (in_array($data->vars->pay_type, [1,2,3,4,6]) )?Base_model::TRANSACTION_STATUS_RECEIVED:Base_model::TRANSACTION_STATUS_NOT_RECEIVED;
            $id1_status = (in_array($data->vars->pay_type, [1,2,3,4,6]) )?Base_model::TRANSACTION_STATUS_REMOVED:Base_model::TRANSACTION_STATUS_IN_PROCESS;
            if ( $data->vars->pay_type == 2 && $data->shop->user_id == 69504490 ){
                $id0_status = Base_model::TRANSACTION_STATUS_RECEIVED;
                $id1_status = Base_model::TRANSACTION_STATUS_REMOVED;
            }


            # Зачисление средств
            $user_fi = $this->users->getUserDataFields( $this->id_user, ['sername','name'] );
            $id[0] = $this->transactions->addPay($data->shop->user_id, $data->vars->amount, Transactions_model::TRANSACTION_TYPE_USER_SEND, 0, 'wt', $id0_status, $data->vars->pay_type, "Оплата счета №{$data->vars->order_id} (".base64_decode($data->shop->url).") пользователем: $this->id_user ({$user_fi->name} {$user_fi->sername})");

            # Снятие средств
            $id[1] = $this->transactions->addPay($this->id_user, $data->vars->amount, Transactions_model::TRANSACTION_TYPE_USER_SEND, $id[0], 'wt', $id1_status, $data->vars->pay_type, "Оплата счета №{$data->vars->order_id} в магазине ".base64_decode($data->shop->title)."(".base64_decode($data->shop->url).")");

            // установка связки для последней транзакции
            $this->transactions->setValue($id[0], $id[1]);

            # Снятие комиссии
            if(0 < $data->vars->commission)
                $data->vars->result = $this->transactions->addPay((($data->shop->commission == 1) ? $this->id_user : $data->shop->user_id), $data->vars->commission, Transactions_model::TYPE_EXPENSE_MERCHANT, $id[1], 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $data->vars->pay_type, "Снятие комиссии по транзакции №" . $id[$data->shop->commission] . " (счет: ". $data->vars->order_id . " " . $data->vars->url . ")");

            $hash_success = md5($ident.":".$data->vars->amount.":".$data->vars->currency .':'.$data->vars->order_id.":".$data->vars->description.":".$id[1].":".$this->shop->slash($data->shop->string));
			dev_log('3'.$ident.":".$data->vars->amount.":".$data->vars->currency .':'.$data->vars->order_id.":".$data->vars->description.":".$id[1].":".$this->shop->slash($data->shop->string));

            $send_data = array(
                'shop_id' => $ident,
                'amount' => $data->vars->amount,
                'order_id' => $data->vars->order_id,
                'description' => $data->vars->description,
                'status' => ($id0_status==Base_model::TRANSACTION_STATUS_RECEIVED)?'success':'waiting',
                'other' => $data->vars->other,
                'currency' => $data->vars->currency,
                'transaction_id' => $id[1],
                'hash' => $hash_success
            );
            $count_send = 5;
            $res = false;
            while ($count_send && !$res) {
                # Отправляем запрос на удаленный сервер
                $res = $this->_get(base64_decode($data->shop->url_result),$send_data);
                $count_send--;
            }
            if($this->Mrch_log)
                $this->base_model->log2file(date("Y-m-d H:i:s")." url ".base64_decode($data->shop->url_result).", res = $res запрос POST=".print_r($send_data, true), "Merchant_send_data");
            unset($send_data['hash']);
            unset($send_data['status']);
            $send_data['shop_id'] = $shop_id;
            $send_data['user_id'] = $this->id_user;
            $this->shop_transactions->addShopTransaction($send_data);
            // отправляем сообщения
            $this->mail->user_sender('send_money', $send_transaction->own, array('to' => "пользователю $send_transaction->id_user. Оплата в магазине проведена.", 'summa' => $send_transaction->summa, 'note' => $send_transaction->note));
            $this->mail->user_sender('send_money_receiver', $send_transaction->id_user, array('from' => $send_transaction->own, 'summa' => $send_transaction->summa, 'note' => $send_transaction->note));
            $this->load->model('inbox_model');
            $mes = "Мы получили вашу заявку на внутренний перевод средств:<br>Кому: $send_transaction->id_user.<br>Сумма перевода $$send_transaction->summa<br>Описание перевода $send_transaction->note<br><br>Перевод совершен как оплата по заявке №{$data->vars->order_id}.<br><br><br><br>С уважением,<br><br>Команда ".$GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($send_transaction->own, $mes);
            $mes = "Для вас был инициирован внутренний перевод средств от $send_transaction->own:<br>Кому: $send_transaction->id_user.<br>Сумма перевода $$send_transaction->summa<br>Описание перевода $send_transaction->note<br><br>Деньги переведены к Вам.<br><br><br><br>С уважением,<br><br>Команда ".$GLOBALS["WHITELABEL_NAME"];
            $this->inbox_model->writeMess2Inbox($send_transaction->id_user, $mes);

            # Выводим сообщение об успешной оплате
            $data->trans_id = $id[1];
            $this->load->view("user/accaunt/merchant_success", $data); //redirect(base64_decode($data->shop->url_success), 'refresh');
            return;

        } else {
            $data->error = "Не получилось оплатить. $data->error";
            $this->load->view("user/accaunt/merchant_err", $data, 'Ошибка покупки');
        }

    }

    # Получение ответа
    private function _get($link, $post = array()){
        $part_link = explode("://", $link, 2);
        $protocol = $part_link[0];
        if (count($post) == 0)
        {
            $headers = stream_context_create(array(
                $protocol => array(
                    'timeout' => 10,
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\n" .
                              "User-Agent: ".$GLOBALS["WHITELABEL_NAME"]." API bot\r\n"
                    )
                )
            );
            return file_get_contents($link, NULL, $headers);
        } else if('http' == $protocol) {
            $headers = stream_context_create(array (
                $protocol => array (
                    'timeout' => 10,
                    'method' => 'POST',
                    'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen(http_build_query($post)) . "\r\n"
                        . "User-Agent: ".$GLOBALS["WHITELABEL_NAME"]." API bot\r\n"
                        . "Accept-language: en\r\n",
                    'content' => http_build_query($post)
                    )
                )
            );
            if($this->Mrch_log)
                $this->base_model->log2file(date("Y-m-d H:i:s")." header = $protocol, content = ".http_build_query($post).", url = $link", "Merchant_get_data");
            if (FALSE == file_get_contents($link, NULL, $headers))
                return FALSE;
            else
                return TRUE;
        } else if('https' == $protocol){
            if (FALSE == $this->_getSslPage($link, $post))
                return FALSE;
            else
                return TRUE;
        }
    }

    private function _getSslPage($url, $post) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, (array)$post);
        curl_setopt($ch, CURLOPT_USERAGENT, '$GLOBALS["WHITELABEL_NAME"] API bot');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        if($this->Mrch_log)
            $this->base_model->log2file(date("Y-m-d H:i:s")." res = $result, url = $url", "Merchant_get_ssl_data");
        return $result;
    }


}