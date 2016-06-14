<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Table_controller')) {
    require APPPATH . 'libraries/Table_controller.php';
}

class Currency_exchange_block_users extends Table_controller {

    public function __construct() {
        parent::__construct();
        admin_state();
        $this->load->model('users_model', 'user');
        $this->load->library('code');
        $this->load->helper('form');

        $this->header_credits = array(
            "title" => "База кредитов",
            "search" => false,
            "columns" => array(
                "id" => array(
                    "title" => "№№",
                    "filter" => true,
                    "order" => true,
                ),
//                "debit" => array(
//                    "title" => "№№ инв.",
//                    "filter" => true,
//                    "order" => true,
//                ),
                "debit_id_user" => array(
                    "title" => "Id инв.",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP",
                    "filter" => true,
                    "order" => true,
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true
                ),
                "kontract" => array(
                    "title" => "Контракт",
                    "filter" => true,
                    "order" => true
                ),
                "time" => array(
                    "title" => "Срок",
                    "filter" => true,
                    "order" => true
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "percent" => array(
                    "title" => "%",
                    "filter" => true,
                    "order" => true
                ),
                "income" => array(
                    "title" => "Приб.",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "contr" => array(
                    "title" => "Отчис.",
                    "filter" => false,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "out_summ_contr" => array(
                    "title" => "Возврат",
                    "filter" => false,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "out_time" => array(
                    "title" => "Дата воз.",
                    "filter" => true,
                    "order" => true,
                ),
                "garant" => array(
                    "title" => "Грнт",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'Да',
                                2 => 'Нет'
                            )
                        )
                    )
                ),
                "state" => array(
                    "title" => "Статус",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getCreditStatuses()
                        )
                    )
                ),
            ),
        );
        $this->header_invests = array(
            "title" => "База инвестиций",
            "columns" => array(
                "id" => array(
                    "title" => "№№",
                    "filter" => true,
                    "order" => true,
                ),
//                "debit" => array(
//                    "title" => "№№ кред.",
//                    "filter" => true,
//                    "order" => true,
//                ),
                "debit_id_user" => array(
                    "title" => "Id кред.",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP",
                    "filter" => true,
                    "order" => true,
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true
                ),
                "kontract" => array(
                    "title" => "Контракт",
                    "filter" => true,
                    "order" => true
                ),
                "time" => array(
                    "title" => "Срок",
                    "filter" => true,
                    "order" => true
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "percent" => array(
                    "title" => "%",
                    "filter" => true,
                    "order" => true
                ),
                "income" => array(
                    "title" => "Начис.",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "out_summ" => array(
                    "title" => "Возврат",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency'
                    )
                ),
                "out_time" => array(
                    "title" => "Дата воз.",
                    "filter" => true,
                    "order" => true,
                ),
                "garant" => array(
                    "title" => "Грнт",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'Да',
                                2 => 'Нет'
                            )
                        )
                    )
                ),
                "state" => array(
                    "title" => "Статус",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getCreditStatuses()
                        )
                    )
                ),
            ),
        );
        $this->header_transactions = array(
            "title" => "База транзакций",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                    //                  "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
                    )
                ),
                "summa" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'currency',
//                                "widget_options" => array(
//                                    cur_format: ' 0,0[.]00',
//                                    cur_symbol: '$',
//                                    language: 'en',
//                                    force_number: false
//                                )
                    )
                ),
                "metod" => array(
                    "title" => "Метод пополнения",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionsMethodLabel(false, true)
                        )
                    )
                ),
                "bonus" => array(
                    "title" => "Бонус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => 'Да',
                                2 => 'Нет'
                            )
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус",
                    "filter" => true,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getTransactionStatuses()
                        )
                    )
                ),
                "time" => array(
                    "title" => "Срок",
                    "filter" => false,
                    "order" => true
                ),
                "type" => array(
                    "title" => "type",
                    "filter" => true,
                    "order" => true,
                ),
                "value" => array(
                    "title" => "value",
                    "filter" => true,
                    "order" => true,
                ),
                "note" => array(
                    "title" => "Транзакция",
                    "filter" => true,
                    "order" => true,
                ),
                "note_admin" => array(
                    "title" => "Заметки админа",
                    "filter" => true,
                    "order" => true,
                ),
                "user_ip" => array(
                    "title" => "IP адрес",
                    "filter" => true,
                    "order" => false,
                ),
            ),
        );
        $this->header_partners = array(
            "title" => "Партнерская сеть",
            "columns" => array(
                "id_user" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "fio" => array(
                    "title" => "ФИО",
                    "filter" => true,
                    "order" => true,
                ),
                "phone" => array(
                    "title" => "Телефон",
                    "filter" => true,
                    "order" => true,
                ),
                "email" => array(
                    "title" => "Email",
                    "filter" => true,
                    "order" => true,
                ),
                "reg_date" => array(
                    "title" => "Регистрация",
                    "filter" => true,
                    "order" => true,
                ),
                "ip_reg" => array(
                    "title" => "IP",
                    "filter" => true,
                    "order" => true,
                ),
                "date" => array(
                    "title" => "Посещение",
                    "filter" => true,
                    "order" => true,
                ),
                "state" => array(
                    "title" => "Статус", // table title, if false no column title displayed,
                    // its work if all column title is false
                    "filter" => true, // column filter, placeholder: input field placeholder
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'partial',
                        "widget_options" => array(
                            "template" => "cell_status.html"
                        )
                    )
                //                >},
                ),
            ),
        );
    }

    public function doc_request($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $ans = $this->user->swch_payout_doc_request((int) $id);
        echo ((Users_model::DOC_REQUEST_ENABLE == $ans) ? "ok" : "free");
    }

    public function blockManey() {
        $id = (int) $this->input->post("id");
        $lim = (int) $this->input->post("val");
        $this->load->model('users_model', 'user');
        $this->user->add_payout_limit($id, $lim);
        echo "ok";
    }

    public function send($id) {
        $user_mail = $this->code->decrypt($this->db->select('email')->from('users')->where('id_user', $id)->get()->row('email'));
        $this->load->helper('smtpmail');
        $this->db->set('cause', $this->input->post('text'));
        my_mail($this->input->post('subject'), nl2br($this->input->post('text')), $user_mail, $this->base_model->settings('email'));
        $this->load->model('inbox_model');
        $this->inbox_model->writeInbox('admin', $id, $this->input->post('subject'));
    }

    public function social() {
        $data['items'] = $this->user->socails();
        $this->content->view('social', "Список пользователей", $data);
    }

    public function news($type = 1) {
        // не доделано!!!!
        $title = "База " . (($type == 1) ? "пользователей" : "партнеров");

        if (isset($_POST["def"])) {
            echo tebleHeader($title, array("data" => "Дата посещения"));
            die;
        }
        if (isset($_POST["offset"])) {
            echo $this->user->getAll($type);
            die;
        }
        $data['type'] = (1 == $type) ? '' : '/2';
        $this->content->view('users', $title, $data);

        //$data['items'] = $this->user->new_users($type);
    }

    public function all_profiles() {
        $data['items'] = $this->user->all_profiles();
        $this->content->view('profiles', "Заявки  на изменение данных", $data);
    }

    public function failure($id = 0) {
        $this->user->delete_profile($id);
        redirect(base_url() . 'opera/users/all_profiles');
        //выслать  уведомление о  том что  отказано в измененнии
    }

    public function profile($id = 0) {//таи нету отказа,  и сохранения,  и нет  подсветки  изменений, и  некоторых  полей
        $id = intval($id);
        $error = "";
        $this->user->setUserId($id);
        $data = array_merge($this->user->getUser($id), $this->user->getUserProfile());
        if (!empty($_POST['submited'])) {

            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            if ($this->form_validation->run('profile') == FALSE) {
                $error = validation_errors();
            } else {
                $_POST['email'] = $data['user']->email;
                $_POST['password'] = $data['user']->password;
                $this->base_model->update_user_admin($id);
                $this->user->delete_profile($id);
                $this->info->add("edit");
                redirect(base_url() . 'opera/users/all_profiles');
            }
        }

        $data['error'] = $error;
        $this->content->view('profile', '', $data);
    }

    public function all($type = 0) {
        if (0 == $type)
            $title = "База заблокированных пользователей";
        else
            $title = "База " . (($type == 1) ? "заблокированных пользователей" : "партнеров");

        $this->_tableWork("currency_exchange_block_users_all", compact(array("title", "type")));

//        $data['url'] = "/opera/users/all" . ((2 == $type) ? '/2' : '');
        $data['url'] = "/opera/currency_exchange_block_users/all" . ((2 == $type) ? '/2' : '');
        $data["controller"] = "users";
        $this->content->view('users', $title, $data);
    }

    public function switchUserStatus($id = NULL) {
        if (null == id)
            show_404();
        echo json_encode(array("status" => $this->user->trigerUserStatus((int) $id)));
    }

    public function writeCause($id = NULL) {
        if (null == id)
            show_404();
        $this->user->writeCause((int) $id, $this->input->post("cause", true), (int) $this->input->post("status", true));
    }

    public function arbitration() {

        if (!empty($_POST)) {
            $item_id = $this->input->post('item_id');
            $action = $this->input->post('action');

            if (empty($item_id) || empty($action)) {
                echo json_encode(array('error' => 'Ошибка передачи данных'));
                return;
            }

            switch ($action) {
                case 'set_payed':
                    $this->load->model('arbitration_model', 'arbitration');
                    $was_payed = $this->input->post('was_payed');
                    if (empty($was_payed))
                        echo json_encode(array('error' => 'Ошибка передачи данных'));

                    $resp = $this->arbitration->checkAsPayed($item_id, $was_payed);
                    if ($resp == TRUE)
                        echo json_encode(array('success' => 'Данные успешно сохранены'));
                    else
                        echo json_encode(array('error' => 'Ошибка сохранения данных'));

                    break;
                case 'set_amount':
                    $this->load->model('arbitration_model', 'arbitration');
                    $amount = $this->input->post('amount');

                    if (empty($amount))
                        echo json_encode(array('error' => 'Ошибка передачи данных'));

                    $resp = $this->arbitration->setAmount($item_id, $amount);
                    if ($resp == TRUE)
                        echo json_encode(array('success' => 'Данные успешно сохранены'));
                    else
                        echo json_encode(array('error' => 'Ошибка сохранения данных'));

                    break;
                case 'delete':
                    $this->load->model('arbitration_model', 'arbitration');
                    $resp = $this->arbitration->deleteItem($item_id, $was_payed);
                    if ($resp == TRUE)
                        echo json_encode(array('success' => 'Данные успешно удалены'));
                    else
                        echo json_encode(array('error' => 'Ошибка удаления данных'));
                    break;
                default :
                    echo json_encode(array('error' => 'Ошибка передачи данных'));
                    return;
            }

            return;
        }

        $this->load->model('arbitration_model', 'arbitration');

        $title = "База заявок на Арбираж";
//
//        $data['url'] = "/opera/users/all".((1 == $type) ? '' : '/2');
        $data["allInqueries"] = $this->arbitration->getInqueriesDescSortedWithColorsNotDeleted();
        $data["removedInqueries"] = $this->arbitration->getInqueriesDescSortedDeleted();

        $this->content->view('arbitration', $title, $data);
    }

    /*
     *  Выводит данных  о  пользователе
     */

    public function index($id = 0) {
        $this->load->model('monitoring_model', 'monitoring');
        $this->load->model('accaunt_model', 'account');
        $this->load->model('phone_model', 'phone');
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('email_model', 'email');
        $this->load->model('users_model', 'users');
        $this->load->model("transactions_model", "transactions");

        $this->monitoring->log(null, "Просмотр пользователя $id", 'private');

        if ($this->admin_info->permission == 'document-control' && !$this->users->is_need_change_doc($id))
            show_404();

        if (!$this->users->isUserExists($id)) {
            echo "Пользователя не существует";
            exit;
        }

        $id = intval($id);
        $this->load->helper('form');
        $error = "";
        if (!empty($_POST['submited']) && $this->admin_info->permission != 'document-control' && $this->admin_info->permission != 'payments') {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->check_login = $_POST['email'];
            if ($this->form_validation->run('admin_profile') == FALSE)
                if ($this->admin_info->permission == 'admin' && $this->input->post('password') == null) {
                    $error = 'Требуется указать Пароль';
                } else
                    $error = validation_errors();
            elseif ($this->input->post('nickname') != '' && $this->usersFilds->isNotUniqueFild(array(
                    'id_user' => $id,
                    'nickname' => htmlspecialchars($this->input->post('nickname'))
                ))) {
                $error = 'Поле Nickname должно содержать уникальное значение.';
            } else {
                $this->monitoring->log(null, "Изменение данных пользователя $id", 'private');

                $this->info->add("edit");

                #begin:проверка на изменение email
                $user_data = $this->users->getUserData($id);
                $new_email = $this->input->post('email', TRUE);

                if (!empty($user_data) && property_exists($user_data, 'email') && $new_email != $user_data->email) {
//                    var_dump($new_email);
                    $users_by_email = $this->users->getUsersByVerifiedEmail($new_email);

//                    var_dump($users_by_email);
//                die;
                    //проверка, если этот емайл уже подтвержден и используется другим(а) пользователями
                    if ($users_by_email !== FALSE && count($users_by_email) > 0) {
                        $error = $new_email . ' данный емайл уже подтвержден и используется другим пользователем.';
                        $_POST['email'] = $user_data->email;
                    } else {//не используется - изменяем емайл, снимаем верификацию и отправляем пользователю письмо с кодов подтверждения
                        $email_coded = $this->code->code($new_email);
                        $hash = md5($email_coded);
                        //$name, $id_user, $status = array(), $debit = 0, $user_mail_src = null

                        $email_sent = $this->mail->user_sender('welcome_regist_confirm', $id, array("hash" => $hash, "base_url" => site_url('/') . "/"), 0, $new_email);
                        if (FALSE === $email_sent) {
                            $error = 'Не удается отправить письмо на данный емайл.';
                        } else {
                            $error = 'На данный емайл отправлено сообщение с кодом подтверждения емайла.';
                            //запись кода идет после успешной отправки почты

                            $this->users->updateUserField($id, 'email', $email_coded);
                            $this->users->updateUserField($id, 'account_verification', $hash);
                        }
                    }
                }
                #end:проверка на изменение email

                $this->base_model->update_user_admin($id, false);

                $this->load->model('users_model', 'users');
                if (isset($_POST['parent_id'])) {
                    $parent_id = $this->input->post('parent_id');
                    $parent_user = $this->users->getUserData($parent_id);
                    if ($parent_id != $id && ($parent_id == 0 || !empty($parent_user))) {
                        $this->base_model->updateUserField($id, 'parent', $parent_id);
                        $this->base_model->updateUserField($id, 'id_volunteer', $parent_user->parent);
                    }
                }

                $short = htmlspecialchars($this->input->post('phone_short_name'));
                $phone_code = htmlspecialchars($this->input->post('phone_code'));
                $phone = htmlspecialchars($this->input->post('phone'));

                $this->phone->setPhoneWithCode($id, $phone_code, $short, $phone_code . $phone);

                $nickname = htmlspecialchars($this->input->post('nickname'));

                $this->usersFilds->saveUserFilds(array(
                    'id_user' => $id,
                    'nickname' => $nickname
                ));
            }
        }
        $this->load->library('code');
        $this->load->model('users_model', 'user');
        $this->load->model('admin_user_notes_model', 'admin_user_notes');

        if (!empty($_POST['submit_admin_coment'])) {
            $note = $this->input->post("note");
            $this->admin_user_notes->addNotes($id, $note);
        }

        $this->user->setUserId($id);
        $data2 = $this->user->getUser($id);
        $data2['social'] = $this->user->getSocial($id);
        $data2['documents'] = $this->user->getDocuments($id);
        $data2['parent'] = $this->user->debit_user($data2['user']->parent);

        $data2['admin_note'] = $this->admin_user_notes->getNotes($id);
        $data2['error'] = $error;
        $data2['send'] = $this->db->where(array('user_to' => $id, 'admin' => 1))
                ->get('inbox')->result();

        $data2['user']->phone_verified = $this->phone->isStatusVerified($id);
        $phoneWithCode = $this->phone->getPhoneWithCode($id);
        $data2['user']->phone = $phoneWithCode['phone'];
        $data2['user']->code = $phoneWithCode['code'];
        $data2['user']->short_name = $phoneWithCode['short_name'];

        //получение баланса по аккаунту
        $data2['user']->balance = $this->account->recalculateUserRating($id);

        $this->load->model('users_model', 'users');
        $this->load->model('users_model', 'users');
        $this->load->model("users_status_model", "users_status");
        $data2['user']->causes = $this->users_status->get_status($id);
        $data2['user']->status_cause = $this->users->readCause($id);
        $data2['user']->nickname = $this->usersFilds->getUserFild($id, 'nickname');
        $crData = $this->user->getUserPureIncome($id);
        $data2['user']->credit_income = $crData[0];
        $data2['user']->credit_income_2 = $crData[1];
        $data2['user']->credit_income_3 = $crData[2];
        $this->load->model('credits_model', 'credits');
        $data2['user']->credit_standart_income = $this->credits->getSummAllStandart();
//        $data2[ 'user' ]->credit_income = (float) $this->user->getUserPureIncome( $id );
        $data2['user']->archive_sum = (float) $this->transactions->getArhiveSum($id);
        $data2['user']->paout_limit = (float) $this->users->get_payout_limit($id);
        $data2['url_cretits_archive'] = "/opera/users/get_cretits_archive/$id";
        $data2['url_invests_archive'] = "/opera/users/get_invests_archive/$id";
        $data2['url_transactions_archive'] = "/opera/users/get_transactions_archive/$id";
        $data2['url_cretits'] = "/opera/users/get_cretits/$id";
        $data2['url_invests'] = "/opera/users/get_invests/$id";
        $data2['url_transactions'] = "/opera/users/get_transactions/$id";
        $data2['url_partners'] = "/opera/users/get_partners/$id";


        $this->content->view('user', '', $data2);
    }

    public function vip_users($type = 0) {
        if (0 == $type)
            $title = "База пользователей";
        else
            $title = "База " . (($type == 1) ? "пользователей" : "партнеров");

        $this->_tableWork("vip_user", compact(array("title", "type")));

        $data['url'] = "/opera/users/vip_users" . ((2 == $type) ? '/2' : '');
        $data["controller"] = "users/vip_user";
        $this->content->view('vip_users', $title, $data);
    }

    public function vip_user($id = 0) {
        $this->load->model('monitoring_model', 'monitoring');
        $this->load->model('accaunt_model', 'account');
        $this->load->model('phone_model', 'phone');
        $this->load->model('users_filds_model', 'usersFilds');

        $this->monitoring->log(null, "Просмотр vip пользователя $id", 'private');

        $id = intval($id);
        $this->load->helper('form');
        $error = "";
        if (!empty($_POST['submited'])) {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            $this->form_validation->check_login = $_POST['email'];
            if ($this->form_validation->run('admin_profile') == FALSE)
                if ($this->admin_info->permission == 'admin' && $this->input->post('password') == null) {
                    $error = 'Требуется указать Пароль';
                } else
                    $error = validation_errors();
            elseif ($this->input->post('nickname') != '' && $this->usersFilds->isNotUniqueFild(array(
                    'id_user' => $id,
                    'nickname' => htmlspecialchars($this->input->post('nickname'))
                ))) {
                $error = 'Поле Nickname должно содержать уникальное значение.';
            } else {
                $this->monitoring->log(null, "Изменение данных пользователя $id", 'private');

                $this->info->add("edit");
                $this->base_model->update_user_admin($id, false);

                $this->load->model('users_model', 'users');
                if (isset($_POST['parent_id'])) {
                    $parent_id = $this->input->post('parent_id');

                    if ($parent_id != $id && ( $parent_id == 0 || $this->users->isUserExists($parent_id) ))
                        $this->base_model->updateUserField($id, 'parent', $parent_id);
                }

                $short = htmlspecialchars($this->input->post('phone_short_name'));
                $phone_code = htmlspecialchars($this->input->post('phone_code'));
                $phone = htmlspecialchars($this->input->post('phone'));

                $this->phone->setPhoneWithCode($id, $phone_code, $short, $phone);

                $nickname = htmlspecialchars($this->input->post('nickname'));

                $this->usersFilds->saveUserFilds(array(
                    'id_user' => $id,
                    'nickname' => $nickname
                ));
            }
        }
        $this->load->library('code');
        $this->load->model('users_model', 'user');
        $this->load->model('admin_user_notes_model', 'admin_user_notes');

        if (!empty($_POST['submit_admin_coment'])) {
            $note = $this->input->post("note");
            $this->admin_user_notes->addNotes($id, $note);
        }

        $this->user->setUserId($id);
        $data2 = $this->user->getUser($id);
        $data2['social'] = $this->user->getSocial($id);
        $data2['documents'] = $this->user->getDocuments($id);
        $data2['parent'] = $this->user->debit_user($data2['user']->parent);

        $data2['admin_note'] = $this->admin_user_notes->getNotes($id);
        $data2['error'] = $error;
        $data2['send'] = $this->db->where(array('user_to' => $id, 'admin' => 1))
                ->get('inbox')->result();

        $data2['user']->phone_verified = $this->phone->isStatusVerified($id);
        $phoneWithCode = $this->phone->getPhoneWithCode($id);
        $data2['user']->phone = $phoneWithCode['phone'];
        $data2['user']->code = $phoneWithCode['code'];
        $data2['user']->short_name = $phoneWithCode['short_name'];

        //получение баланса по аккаунту
        $data2['user']->balance = $this->account->recalculateUserRating($id);

        $this->load->model('users_model', 'users');
        $this->load->model("users_status_model", "users_status");
        $data2['user']->causes = $this->users_status->get_status($id);
        $data2['user']->status_cause = $this->users->readCause($id);
        $data2['user']->nickname = $this->usersFilds->getUserFild($id, 'nickname');
        $data_incame = $this->user->getUserPureIncome($id);
        $data2['user']->credit_income = $data_incame[0];
        $data2['url_cretits'] = "/opera/users/get_cretits/$id";
        $data2['url_invests'] = "/opera/users/get_invests/$id";
        $data2['url_transactions'] = "/opera/users/get_transactions/$id";
        $data2['url_partners'] = "/opera/users/get_partners/$id";


        $this->content->view('vip_user', '', $data2);
    }

    public function get_cretits($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_credits;
        $this->_tableWork("get_credits", compact(array("header")));
    }

    public function get_invests($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_invests;
        $this->_tableWork("get_invests", compact(array("header")));
    }

    public function get_transactions($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_transactions;
        $this->_tableWork("get_transactions", compact(array("header")));
    }

    public function get_partners($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_partners;
        $this->_tableWork("get_partners", compact(array("header")));
    }

    public function get_cretits_archive($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_credits;
        $this->_tableWork("get_credits_archive", compact(array("header")));
    }

    public function get_invests_archive($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_invests;
        $this->_tableWork("get_invests_archive", compact(array("header")));
    }

    public function get_transactions_archive($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('users_model', 'user');
        $this->user->setUserId($id);

        $header = $this->header_transactions;
        $this->_tableWork("get_transactions_archive", compact(array("header")));
    }

    public function doc_change() {
        $title = "Заявки  на изменение документов";

        $this->_tableWork("user_doc_chenge", compact(array("title")));

        $data['url'] = "/opera/users/doc_change";
        $data["controller"] = "users";
        $this->content->view('users', $title, $data);
        //$data['items'] = $this->user->list_doc_change();
    }

    /* Блокировка юзера */

    public function close($id = 0) {
        $id = intval($id);
        $this->user->user_active_close($id, 3);
    }

    /* активация юзера */

    public function active($id = 0) {
        $id = intval($id);
        $this->user->user_active_close($id, 2);
    }

    /**
     * Удаление страницы(дорабатывать  в дальнейшем)
     */
    public function delete($id = 0) {
        $id = intval($id);
        $user = $this->code->db_decode($this->db->select('client,partner, state')->where('id_user', $id)->get('users')->row());
        if ($this->base_model->check_crediter($id) === true or true) {
            $this->db->where('id_user', $id)->delete(array('users', 'address', 'chanche_users', 'documents', 'credits', 'invest', 'social_network'));
            $this->info->add("delete_yes");
        } else
            $this->info->add("delete_no_user");


        if (empty($user)) {
            redirect(base_url() . 'opera/users/all');
            return;
        }
        if ($user->client == 1 and $user->state == 1)
            redirect(base_url() . 'opera/users/news');
        if ($user->client == 1 and ( $user->state == 2 or $user->state == 3))
            redirect(base_url() . 'opera/users/all');
        if ($user->partner == 1 and $user->state == 1)
            redirect(base_url() . 'opera/users/news/2');
        if ($user->partner == 1 and ( $user->state == 2 or $user->state == 3))
            redirect(base_url() . 'opera/users/all/2');
        redirect(base_url() . 'opera/users/news');
    }

    public function take($id_user, $type, $state, $old = 0) {
        $documents = config_item('documents_admin_count');
        if ($documents !== false && !in_array($type, $documents, true))
            return;

        if ($state === 'yes') {
            //получаем данные о документах до их изменения
            $this->user->setUserId($id_user);
            $user_res = $this->user->getUser($id_user);
            $user_documents = $this->user->getDocuments($id_user);


            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 2));
                //выслать  ссобщение об одобрении
            } else {
                $docs = $this->db->where(array('id_user' => $id_user, 'num' => $type))->select('img, img2')->get('documents')->row();
                @unlink('upload/docs/' . $docs->img);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 2, 'img' => $docs->img2, 'img2' => ''));
                //выслать  ссобщение об одобрении
            }

            $user_data = $user_res['user'];
            //отправка бонуса Parent'у
            $parent = $user_data->parent;

            if ($parent != 0) {
                //были ли подтвержденные документы для начисления бонуса за реферала
                $first_time_verification = 1;
                if (!empty($user_documents)) {
                    foreach ($user_documents as $item) {
                        if ($item->state == 2 && $item->num == 1) {
                            $first_time_verification = 0;
                            break;
                        }
                    }
                }

                //была ли уже выплата за привлечение?
                if ($first_time_verification == 1) {
                    $this->load->model('accaunt_model', 'accaunt');
//                    $userInfo = $this->base_model->getUserInfo( $id_user );
                    //начисление бонуса
                    $this->accaunt->payBonusesToPartner($id_user);
                }
            }
        } else if ($state === 'no') {
            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 3));
                //выслать  ссобщение об отказе
            } else {
                $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
                @unlink($this->image->place . $docs->img2);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                //выслать  ссобщение об отказе
            }
        } else if ($state === 'ithink') {
            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 1));

                $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
                @unlink($this->image->place . $docs->img2);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                //выслать  ссобщение о том что  фотография на рассмотрнии
            }
        } else if ($state === "del") {

            $this->image->place = "upload/doc/";
            $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
            if (empty($old)) {
                $img_name = $docs->img;
                if (!empty($img_name)) {
                    if (!empty($docs->img2))
                        $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 1, 'img' => $docs->img2, 'img2' => ''));
                    else
                        $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 0, 'img' => '', 'img2' => ''));
                    @unlink($this->image->place . $img_name);
                    //выслать  сообщение о  том что  фото  было  удалено
                }
            }
            else {
                $img_name = $docs->img2;
                if (!empty($img_name)) {
                    $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                    @unlink($this->image->place . $img_name);
                    //выслать  сообщение о  том что  фото  было  удалено
                }
            }
        }
    }

    public function take2($id_user, $type, $state, $old = 0) {
        if (!in_array($type, config_item('documents_count'), true))
            return;
        if ($state === 'yes') {
            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 2));
                //выслать  ссобщение об одобрении
            } else {
                $docs = $this->db->where(array('id_user' => $id_user, 'num' => $type))->select('img, img2')->get('documents')->row();
                @unlink('upload/docs/' . $docs->img);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 2, 'img' => $docs->img2, 'img2' => ''));
                //выслать  ссобщение об одобрении
            }
        } else if ($state === 'no') {
            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 3));
                //выслать  ссобщение об отказе
            } else {
                $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
                @unlink($this->image->place . $docs->img2);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                //выслать  ссобщение об отказе
            }
        } else if ($state === 'ithink') {
            if (empty($old)) { //работа с 1 фото
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 1));

                $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
                @unlink($this->image->place . $docs->img2);
                $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                //выслать  ссобщение о том что  фотография на рассмотрнии
            }
        } else if ($state === "del") {

            $this->image->place = "upload/doc/";
            $docs = $this->db->select('state,  img, img2')->from('documents')->where(array('num' => $type, 'id_user' => $id_user))->get()->row();
            if (empty($old)) {
                $img_name = $docs->img;
                if (!empty($img_name)) {
                    if (!empty($docs->img2))
                        $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 1, 'img' => $docs->img2, 'img2' => ''));
                    else
                        $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('state' => 0, 'img' => '', 'img2' => ''));
                    @unlink($this->image->place . $img_name);
                    //выслать  сообщение о  том что  фото  было  удалено
                }
            }
            else {
                $img_name = $docs->img2;
                if (!empty($img_name)) {
                    $this->db->where(array('id_user' => $id_user, 'num' => $type))->update('documents', array('img2' => ''));
                    @unlink($this->image->place . $img_name);
                    //выслать  сообщение о  том что  фото  было  удалено
                }
            }
        }
    }

    public function doc($id, $user, $old = 0) {
        if (intval($id) != 0 and in_array($id, config_item('documents_count'), true)) {
            $select = 'img';
            if (intval($old) == 2)
                $select = 'img2';
            $pic = $this->db->select($select)->where(array('num' => $id, 'id_user' => $user))->from('documents')->get()->row($select);
            ob_end_clean();  //очищает буфер вывода и отключает буферизацию вывода
            header("Content-Type: image/jpg");
            echo $this->code->fileDecode("upload/doc/" . $pic);
        }
    }

    public function downloadEmail() {
        $out = array();
//        $options = array( "options" => array( "regexp" => "/[a-zA-ZА-Яа-яЁё -]*/" ) );

        $part = 0;
        $i = 0;
        foreach ($this->code->db_decode($this->db->select('email, name, sername')->get('users')->result()) as $item) {
            $i++;
            if ($i >= 9000) {
                $part++;
                $i = 0;
            }
            $user = array();
            $user[] = $item->email;

            $sername = ($item->sername ? $item->sername . ' ' : '');
            $name = ($item->name ? $item->name . ' ' : '');

            $user[] = $sername . $name;

            $out[$part][] = implode(", ", $user);
        }



        $zip = new ZipArchive();
        $filename = "/tmp/email.zip";

        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
            exit("Невозможно открыть <$filename>\n");
        }

        foreach ($out as $i => $part) {
            $list = implode("\r\n", $part);
            $zip->addFromString("email-$i.txt", $list);
        }

        $zip->close();

        $file_name = basename($filename);

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Length: " . filesize($filename));

        readfile($filename);

//        header('Content-Description: File Transfer');
//        header('Content-Type: text/plain');
//        header('Content-Disposition: attachment; filename="emails.txt"'); //<<< Note the " " surrounding the file name
//        header('Content-Transfer-Encoding: binary');
//        header('Connection: Keep-Alive');
//        header('Expires: 0');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Pragma: public');
//
//        echo $list;
    }

    public function downloadActiveUsers() {
        $out = array();
        $part = 0;
        $i = 0;
        ignore_user_abort(true);
        set_time_limit(0);
        $data = $this->code->db_decode($this->db
                ->select('email')
                ->where_in('state', array('1', '2'))
                ->where(array('bot' => 2))
                ->where("online_date >", "2014-11-30")
                ->get('users')
                ->result()
        );
        foreach ($data as $item) {
            $i++;
            if ($i >= 90000) {
                $part++;
                $i = 0;
            }
            $user = array();
            $user[] = $item->email;
            $user[] = $item->phone;

            $sername = ($item->sername ? $item->sername . ' ' : '');
            $name = ($item->name ? $item->name . ' ' : '');

            $user[] = $sername . $name;

            $out[$part][] = implode(", ", $user);
        }

        $zip = new ZipArchive();
        $filename = APPPATH . "logs/users.zip";

        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
            exit("Невозможно открыть <$filename>\n");
        }

        foreach ($out as $i => $part) {
            $list = implode("\r\n", $part);
            $zip->addFromString("users-$i.txt", $list);
        }

        $zip->close();
    }

    public function users_block($action = null) {
        ini_set('memory_limit', "2500M");
        $matches = '';
        if (preg_match_all('/\./', $action, $matches) > 0) {
            $entery_users = $action;
            $action = null;
        }

        $title = 'Блокировка пользователей по IP';

        if (!empty($_POST['users'])) {
            $entery_users = $this->input->post('users');
            $entery_block_reason = $this->input->post('block_reason');
        }

        $only_one_transaction = FALSE;
        if (isset($_POST['only_one'])) {
            $only_one_transaction = $this->input->post('only_one');
        }

        if (isset($_POST['block']) && !empty($_POST['block']) && $action == 'block') {
            $note = $this->input->post('block_reason');

            if (empty($note)) {
                $data['error'] = 'Укажите причину блокировки';
            } else
                $this->_blockUsers($note);
        }

        if (!empty($_POST['users']) || !empty($entery_users)) {
            $data['users_table'] = $this->_getBlockTableByIps($entery_users, $only_one_transaction);
//            $this->monitoring->log(null, "Изменение ", 'private');
        }

        $data['entery_users'] = $entery_users;
        $data['entery_block_reason'] = $entery_block_reason;
        $this->content->view('users_block', $title, $data);
    }

    private function _blockUsers($note) {
//        $transactions = array();
//        $this->load->model('users_status_model', 'users_status');
        $this->load->model('users_model', 'users');

        foreach ($_POST as $key => $val) {
//            if ($key[0] == 't' && $val == 'on') {
//                $transactions[] = "id = " . substr($key, 1);
//            } else
            if ($key[0] == 'u' && $val == 'on') {
                $user_id = (int) substr($key, 1);
                if (empty($user_id))
                    continue;
                $this->db->trans_start();
                {
                    $this->users->setUserStatus($user_id, Base_model::USER_STATE_OFF);
                    $this->users->writeCause($user_id, $note, Base_model::USER_STATE_OFF);
                }
                $this->db->trans_complete();
            }
        }



//        if (!empty($transactions)) {
//            $transactions_q = implode(' OR ', $transactions);
//            $transactions_q = "UPDATE transactions SET status = 8 WHERE " . $transactions_q;
//            $this->db->query($transactions_q);
//        }
    }

    private function _getBlockTableByIps($text, $only_one_transaction = FALSE) {
        $this->load->model('users_model', 'users');

        if (empty($text))
            return null;

        $text_repl = preg_replace("/[\\n| |,|\|]/", '|', $text);

        $lines = explode("|", $text_repl);
        $ips = array();
        $ip_regs = array();

        foreach ($lines as $line)
            if (!empty($line)) {
                $ips[] = "user_ip LIKE '%$line%'";
                $ip_regs[] = "ip_reg LIKE '%$line%'";
            }


        if (empty($ips))
            return;

//        echo implode( ' OR ', $ip_regs );

        $users_by_ip = $this->code->db_decode($this->db
                ->select(array('id_user', 'name', 'sername', 'email', 'ip_reg', 'state', 'ip_address'))
                ->where(implode(' OR ', $ip_regs))
                ->get('users')
                ->result()
        );

        $this->db->where('(' . implode(' OR ', $ips) . ')')
//                ->where( array( 'status' => Base_model::TRANSACTION_STATUS_RECEIVED ) )
            ->where("( note LIKE '%Получение средств от пользователя%' OR " .
                " note LIKE '%Отправка средств пользователю%' OR " .
                "(metod = 'out' AND note NOT LIKE '%Комисия за вывод%') OR " .
                "note LIKE '%Бонус за регистрацию%' )");

        if (!$only_one_transaction)
            $this->db->group_by('id');
        else
            $this->db->group_by(array('id_user', 'id'));

        $transactions_by_ip = $this->db->get('transactions')->result();

        $users_by_credit_ip = array();
        $transactions_sorted = array();
        $users_sorted = array();


        if (!empty($transactions_by_ip)) {
            $id_user = array();
            foreach ($transactions_by_ip as $line) {
                if (empty($line))
                    continue;
//                if( preg_match( '/^Зачисление средств от пользователя/', $line->note ) )
//                {
//                   $number = $this->users->getNumber($line->note);
//                   if( !empty( $number ) )
//                   {
//                       $id_user[] = "id_user = {$number}";
//                   }
//                }
                $id_user[] = "id_user = {$line->id_user}";
                $users_sorted[$line->id_user] = new stdClass();

                if (!isset($transactions_sorted[$line->id_user])) {
                    $transactions_sorted[$line->id_user] = array();
                }

                $transactions_sorted[$line->id_user][$line->id] = $line;
            }
            $users_by_credit_ip = $this->code->db_decode(
                $this->db->select(array('id_user', 'name', 'sername', 'email', 'ip_reg', 'state', 'ip_address'))
                    ->where(implode(' OR ', $id_user))
                    ->group_by('id_user')
                    ->get('users')->result()
            );
        }

        $users = array();
        if (!empty($users_by_credit_ip) && !empty($users_by_ip)) {
            $users = array_merge($users_by_credit_ip, $users_by_ip);
        } else
        if (!empty($users_by_credit_ip)) {
            $users = $users_by_credit_ip;
        } else
        if (!empty($users_by_ip)) {
            $users = $users_by_ip;
        }

//        if( empty( $users ) )
//            return "Empty users";


        foreach ($users as $u) {
            $users_sorted[$u->id_user] = $u;
        }

        $table = array();
        $a = 0;
        foreach ($users_sorted as $id_user => $u) {
            $table[$a] = array();
            $u->null = 0;
            if (!isset($u->id_user)) {
                $u->full_name = '-';
                $u->email = '-';
                $u->id_user = $id_user;
                $u->state = 1;
                $u->null = 1;
            } else {
                $u->full_name = "{$u->name} {$u->sername}";
            }
            if (empty($u->ip_reg))
                $u->ip_reg = '-';
            if (empty($u->ip_address))
                $u->ip_address = '-';
            $u->ips = "{$u->ip_reg} / {$u->ip_address}";

            switch ($u->state) {
                case 1: $u->state_img = '160';
                    break;
                case 2: $u->state_img = '152';
                    break;
                default: $u->state_img = '151';
                    break;
            }

            $table[$a][1] = $u;

            $transactions = array();
            if (isset($transactions_sorted[$id_user]))
                $transactions = $transactions_sorted[$id_user];
            $table[$a][2] = $transactions;

            $a++;
        }


        return $table;
    }

    private function _get_recurse_wt_trans($main_user_id, $user_id, $parent, $pos, $date_from, $level, $maxLevel, &$nodes, &$links, &$tree)
    {

        //echo "--------------- $user_id -------------------<br>";

        if (!isset($tree[$level]))
            $tree[$level] = 0;

        if ($level > 0) {
            $out_trans = $this->db->where('metod', 'out')->where('id_user', $user_id)->where('date >=', $date_from)->get('transactions')->row();
            //echo $this->db->last_query();
            if ( !empty($out_trans) ){
                $nodes[] = array( 'id'=>'out_'.$parent, 'loc' => ($pos->x-100).' '.($pos->y+70), 'text' => 'Вывод'/*."\n_$parent"*/, 'color' => '#FF0000' );
                $links[] = array( 'from'=>$parent, 'to' => 'out_'.$parent, 'text' => '$'.$out_trans->summa);
            }
        }

        $rows = $this->db->where('metod="wt" and type=74')
                         ->where('id_user', $user_id)
                         ->where('date >', $date_from)
                         ->get('transactions')->result();


       // echo $this->db->last_query().' - '.count($rows).'<br><hr>';

        /*$hist_rows = $this->db->where('metod="wt" and type=74')
                         ->where('id_user', $user_id)
                         ->where('date >', $date_from)
                         ->get('archive_transactions')->result();
        $rows = array_merge($rows, $hist_rows);*/

        if ( empty($rows) ){
            return 0;
        }


        if ( $level >= $maxLevel )
            return 0;

        /*
        //В1
        foreach( $rows as $idx=>$row ){

            $wt_trans = $this->db->where('id', $row->value)->get('transactions')->row();
            $ret = 0;
            if (!empty( $wt_trans ) ){
                $pos->x = $tree[$level+1]*200;
                $pos->y = (100 * ( $level+1) );
                $nodes[] = array( 'id'=>$wt_trans->id_user.'_'.($tree[$level+1]+1), 'loc' =>  $pos->x.' '.$pos->y, 'text' => $wt_trans->id_user, 'color' => '#00FF00' );
                $links[] = array( 'from'=>$user_id.'_'.$tree[$level], 'to' => $wt_trans->id_user.'_'.($tree[$level+1]+1), 'text' => '$'.$wt_trans->summa);

                $tree[$level+1]++;

                $ret = $this->_get_recurse_wt_trans( $wt_trans->id_user,  $user_id, $pos, $wt_trans->date, $level+1,$maxLevel, $nodes, $links, $tree);
            }
        }*/

          /*
        //В2
        $users = array();
        foreach( $rows as $idx=>$row ){
            $wt_trans = $this->db->where('id', $row->value)->get('transactions')->row();
            if (!empty( $wt_trans ) ){
                @$users[ $wt_trans->id_user ]['summ'] += $wt_trans->summa;
                @$users[ $wt_trans->id_user ]['cnt']++;
                if ( !isset($users[ $wt_trans->id_user ]['date']))
                    @$users[ $wt_trans->id_user ]['date'] = $wt_trans->date;
            }
        }

        foreach( @$users as $trans_user_id=>$p ){
             //echo "[$trans_user_id]";
             //var_dump($p);
                $ret = 0;
                $pos->x = $tree[$level+1]*200;
                $pos->y = (100 * ( $level+1) );
                $nodes[] = array( 'id'=>$trans_user_id.'_'.($tree[$level+1]+1), 'loc' =>  $pos->x.' '.$pos->y, 'text' => "$trans_user_id\n{$p['cnt']} раз", 'color' => '#00FF00' );
                $links[] = array( 'from'=>$user_id.'_'.$tree[$level], 'to' => $trans_user_id.'_'.($tree[$level+1]+1), 'text' => '$'.$p['summ']);

                $tree[$level+1]++;

                $ret = $this->_get_recurse_wt_trans( $trans_user_id,  $user_id, $pos, $p['date'], $level+1,$maxLevel, $nodes, $links, $tree);
        }

           */




        //В3
        $users = array();
        foreach( $rows as $idx=>$row ){
            $wt_trans = $this->db->where('id', $row->value)->get('transactions')->row();
            if (!empty( $wt_trans ) ){
                if ( !isset($users[ $wt_trans->id_user ])){
                    @$users[ $wt_trans->id_user ]['trans'] = $wt_trans;
                    @$users[ $wt_trans->id_user ]['date'] = $wt_trans->date;
                }
             }
        }

       // echo '<pre>';print_r( $users );echo '</pre>';

        foreach( $users as $idx=>$row ){


            //получи фио
            $fio = 'UNK';
            $fresult = $this->code->db_decode($this->db->select('id_user, name, sername, patronymic, phone')->where('id_user', $row['trans']->id_user)->from('users')->get()->row());
	    if (!empty($fresult))
		$fio = $fresult->sername.' '.$fresult->name.' '.$fresult->patronymic;

            $wt_trans = $this->db->where('id', $row['trans']->value)->get('transactions')->row();
            $ret = 0;
            if (!empty( $wt_trans ) ){
                $pos->x = $tree[$level+1]*350;
                $pos->y = (130 * ( $level+1) );
                $color = '#00FF00';
                if ( $row['trans']->id_user == $main_user_id)
                    $color = 'orange';
                $nodes[] = array( 'id'=>$row['trans']->id_user.'_'.($tree[$level+1]+1)."_$level",
                                  'loc' =>  $pos->x.' '.$pos->y,
                                  'text' => $fio."(".$row['trans']->id_user.")\n".$row['trans']->date/*.'_'.($tree[$level+1]+1)."_$level".'_['.$parent."]\n".$row['trans']->date."\n$parent=>".$row['trans']->id_user.'_'.($tree[$level+1]+1)*/,
                                  'color' => $color);
                $links[] = array( 'from'=>$parent, 'to' => $row['trans']->id_user.'_'.($tree[$level+1]+1)."_$level", 'text' => '$'.$row['trans']->summa);

                $tree[$level+1]++;

                $ret = $this->_get_recurse_wt_trans( $main_user_id, $row['trans']->id_user,  $row['trans']->id_user.'_'.($tree[$level+1])."_$level", $pos, $row['trans']->date, $level+1,$maxLevel, $nodes, $links, $tree);
            }
        }

        return 1;
    }

    /*
     * Получает данные для графа
     */

    public function trans_graph( $user_id )
    {
        //$force_user_id = 85383680;
        $this->load->library( 'code' );
        $this->load->model( 'users_model', 'user' );
        $this->load->model( 'accaunt_model', 'account' );



        if ( empty( $force_user_id) ){
            $user_id = (int)$user_id;
            $this->user->setUserId( $user_id );
            $user = (object)$this->user->getUser( $user_id );
            $user->balance = $this->account->recalculateUserRating( $user_id );
        } else {
            $user_id = $force_user_id;
            $user = new stdClass();
            $user->balance['payment_account'] = 0;
            $user->balance['bonuses'] = 0;
            $user->balance['money_sum_add_funds'] = 0;
            $user->balance['money_sum_withdrawal'] = 0;

        }

        //получи фио
        $fio = 'UNK';
        $fresult = $this->code->db_decode($this->db->select('id_user, name, sername, patronymic, phone')->where('id_user', $user_id)->from('users')->get()->row());
        if (!empty($fresult))
            $fio = $fresult->sername.' '.$fresult->name.' '.$fresult->patronymic;

        $nodes = array();
        $links = array();
        $tree = array();

        $pos = new stdClass();
        $pos->x = 200;
        $pos->y = 0;


        $maxLevel = 10;
        //$_REQUEST['from'] = '01.01.2015 00:00';
        //$_REQUEST['to'] = date('d.m.Y H:i');

        if ( isset($_REQUEST['from']) )
           $dateFrom = date_create_from_format('d.m.Y H:i',$_REQUEST['from']);
        if ( isset($_REQUEST['to']) )
           $dateTo = date_create_from_format('d.m.Y H:i',$_REQUEST['to']);
        if ( isset($_REQUEST['maxLevel']) )
           $maxLevel = $_REQUEST['maxLevel'];

        $this->_get_recurse_wt_trans( $user_id, $user_id, $user_id.'_0', $pos, date_format($dateFrom,'Y-m-d H:i:00'),0, $maxLevel, $nodes, $links, $tree  );


        //echo '<pre>';
        //var_dump( $nodes );
        //die();


        $res = array();
        $res['nodeKeyProperty'] = 'id';

        // nodes
        $res['nodeDataArray'] = array();
        $res['nodeDataArray'][] = array( 'id'=>$user_id.'_0', 'loc' => '200 0','text' => $fio."\nID$user_id\nБАЛАНС: $".price_format_double($user->balance['payment_account']+$user->balance['bonuses']),'color' => 'orange' );
        $res['nodeDataArray'][] = array( 'id'=>'in', 'loc' => '0 0', 'text' => "ВВОД", 'color' => '#FFFF00' );
        $res['nodeDataArray'][] = array( 'id'=>'out', 'loc' => '450 0', 'text' => "ВЫВОД", 'color' => '#FF0000' );


        $res['nodeDataArray'] = array_merge($res['nodeDataArray'], $nodes);

        // links
        $res['linkDataArray'] = array();
        $res['linkDataArray'][] = array( 'from'=>'in', 'to' => $user_id.'_0', 'text' => "$".$user->balance['money_sum_add_funds'], 'color' => '#00FF00' );
        $res['linkDataArray'][] = array( 'from'=>$user_id.'_0', 'to' => 'out', 'text' => "$".$user->balance['money_sum_withdrawal'], 'color' => '#00FF00' );

        $res['linkDataArray'] = array_merge($res['linkDataArray'], $links);

        /*
          { "nodeKeyProperty": "id",
          "nodeDataArray": [
          { "id": 0, "loc": "0 0", "text": "IN $100", "color": "#FFFF00" },
          { "id": 1, "loc": "200 0", "text": "ID500500\nБАЛАНС: $200", "color": "orange" },
          { "id": 2, "loc": "400 0", "text": "OUT $200", "color": "#00FF00" },
          { "id": 3, "loc": "0 120", "text": "ID500501", "color": "orange" },
          { "id": 4, "loc": "0 220", "text": "ID500502", "color": "orange" },
          { "id": 5, "loc": "200 120", "text": "ID500503", "color": "orange" }
          ],
          "linkDataArray": [

          { "from": 1, "to": 3, "text": "$50", "curviness": 0},
          { "from": 0, "to": 1, "text": "", "curviness": 0 },
          { "from": 1, "to": 2, "text": "" },
          { "from": 3, "to": 4, "text": "$100" },
          { "from": 4, "to": 1, "text": "$150" },
          { "from": 1, "to": 5, "text": "$150" }
          ]
         */
        echo json_encode($res);
    }

}
