<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/user/Accaunt.php';

class Security extends Accaunt {

    protected $me;
    static protected $var;

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model", "users");
        $this->me = $this->users->getCurrUserId();
    }

    //page
    public function index() {
        $data = new stdClass();
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('security_model', 'security_model');
        $this->load->model('email_model', 'email');

//        $id = $this->user->id_user;
        viewData()->page_name = "security";
        viewData()->secondary_menu = "settings";

        $old_pass = $this->input->post('old_password', true);
        $password = $this->input->post('password', true);
        $password_confirm = $this->input->post('password2', true);

        if (!empty($_POST['submited'])) {
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
            if ($this->form_validation->run('password_change') == true) {

                $password_real = $this->accaunt->user_field('user_pass');
                $old_pass = $this->input->post('old_password', true);

                if (empty($old_pass) || $old_pass != $password_real) {
                    accaunt_message($data, _e('security/data1'), 'error');
                } else
                if ($password_confirm != $password) {
                    accaunt_message($data, _e('security/data2'), 'error');
                } else {
                    $email = $this->accaunt->user_field('email', false);
                    $this->accaunt->update_user_field('user_pass', $password);
                    cookie_log($email, $this->code->request('password'));

                    $user_id = get_current_user_id();
                    $this->load->library('soc_network');
                    $this->load->model('users_filds_model', 'usersFilds');
                    $social_id = $this->usersFilds->getUserFild($user_id, 'id_network');
                    $user = $this->users->getUserFullProfile($user_id);
                    $this->soc_network->updateSoc($user, $user->user_login, $password, $social_id);

                    $old_pass = $password = $password_confirm = '';
                    accaunt_message($data, _e('security/data3'));
                }
            } else
                accaunt_message($data, _e('security/data4'), 'error');

            if (!$this->base_model->returnNotAjax()) {
                echo "<script>alert('{$data->message['send']}');</script>";
            }
        }

        $data->pass_old = $old_pass;
        $data->pass_new = $password;
        $data->pass_new_confirm = $password_confirm;

        $data->i = $this->security_model->getProtection();

        // передадим телефонный код страны
        $this->load->model('phone_model', 'phone');
        $user_id = $this->accaunt->get_user_id();
        $phone = $this->phone->getPhone($user_id);
        $data->isProblemOperator = $this->phone->is_problem_operator($phone);
        //$data->isVerifiedEmail = $this->email->isUserEmailVerified( $user_id ); //когда проблемы с СМС, включаем переход на подтвержденные емайлы без сообщений

        $confirm_email = $this->base_model->getUserField($user_id, 'account_verification', FALSE);
        $data->confirm_email = !empty($confirm_email);

        $data->no_email = (!empty($phone) && $phone->short_name == 'USA');

        if ($this->base_model->returnNotAjax())
            $this->content->user_view('security', $data, '');
        else
            $this->load->view('user/accaunt/security', $data);
    }

//заменена
    public function ajax_sms_request() {
        $this->ajax_get_code();
    }
//отключена - небезопасна
    public function ajax_sms_request_v1() {
        return false;
        $this->load->model('phone_model', 'phone');
        $this->load->model('security_model');

        $purpose = $this->input->post('purpose');

        $purpose_matches = array();

        $id_user = $this->accaunt->get_user_id();

        $purpose_preg_match_res = preg_match('/(\w_\d)[5,50]/', $purpose, $purpose_matches);
        if ($purpose_preg_match_res == 1 && strlen($purpose_matches[1]) == strlen($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        if (empty($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        $resp = array();
        $send_resp = array('success' => "ok");
        $page_link_hash = $this->input->server('HTTP_REFERER', TRUE);
        $page_hash = $this->input->post('page_hash', TRUE);

        if (empty($page_hash))
            $page_hash = '';

        /* отключена
        if ($purpose === 'phone_verification') {
            //$purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = ''
            $send_resp = $this->phone->sendSmsCode($purpose, null, null, TRUE, $page_link_hash, $page_hash);
            //$send_resp = $this->phone->sendSmsCode('set_security_type', null, null, TRUE, $page_link_hash, $page_hash);
            $this->security_model->setProtectionTypeValueByAttrName('withdrawal_standart_credit', 'one_pass', $id_user);
            $this->security_model->copy_protection_type($id_user, 'phone_verification', 'change_security_type');
        } else
         *
         */
        if ($purpose === 'save_p2p_payment_data') {
            //$purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = ''
            $send_resp = $this->phone->sendSmsCode($purpose, null, null, TRUE, $page_link_hash, $page_hash);
            $this->security_model->setProtectionTypeValueByAttrName('save_p2p_payment_data', 'sms', $id_user);
        } else {

            $protection_type_1 = $this->security_model->getProtectTypeByAttrName($purpose);
            $protection_type_2 = $this->security_model->getProtectTypeByAttrName('withdrawal_standart_credit');

//            if( ( $protection_type_1 == 'sms' && empty($protection_type_2) ) ||
//                  $protection_type_2 == 'sms' && empty($protection_type_1) ||
//                  $protection_type_1 == 'sms' && $protection_type_2 == 'sms' )
//            {
            //$purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = ''
            $send_resp = $this->phone->sendSmsCode($purpose, null, null, FALSE, $page_link_hash, $page_hash);
//            }else
//            {
//                $resp['error'] = 'Ошибка типа безопасности. Сообщите, пожалуйста, в тех. поддержку.';
//            }
        }

        if (isset($send_resp['error']) && !empty($send_resp['error'])) {
            if (isset($send_resp['error']['service'])) {
                $resp['error'] = _e('Ошибка сервиса: ') . $send_resp['error']['service'] . '. ' . _e('Сообщите, пожалуйста, в тех. поддержку.');
            } else
                switch ($send_resp['error']) {
                    case 1:
                    case 2: $resp['error'] = _e('security/data6');
                        break;
                    case 3: $resp['error'] = _e('security/data7');
                        break;
                    case 4: $resp['error'] = _e('security/data8');
                        break;
                    case 5: $resp['error'] = _e('security/data9');
                        break;
                    case 6: $resp['error'] = _e('security/data10');
                        break;
                    case 44: $resp['error'] = _e('security/data11');
                        break;
                    default : $resp['error'] = _e('security/data12');
                }
        } else if (isset($send_resp['success'])) {
            $resp['data']['voice_verification_enabled'] = $this->phone->is_voice_verification_enabled($id_user);

            if ($purpose === 'phone_verification') {
                $resp['success'] = 'OK';
                $resp['action'] = 'start-counter';
            } else {
                $resp['success'] = _e('security/data13');
                $resp['action'] = 'start-counter';
            }
        }

        $this->send_ajax_responce($resp);
    }

    public function ajax_voice_request() {
        $this->ajax_get_code();
    }
//отключена - небезопасна
    public function ajax_voice_request_v1() {
        return FALSE;
        $this->load->model('phone_model', 'phone');
        $this->load->model('security_model');

        $purpose = $this->input->post('purpose', TRUE);
        $phone_sms_attempts = $this->input->post('phone_sms_attempts', TRUE);
        $lang = $this->input->post('lang', TRUE);

        $purpose_matches = array();

        $id_user = $this->accaunt->get_user_id();

        $purpose_preg_match_res = preg_match('/(\w_\d)[5,50]/', $purpose, $purpose_matches);
        if ($purpose_preg_match_res == 1 && strlen($purpose_matches[1]) == strlen($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        if (empty($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        $resp = array();
        $send_resp = array('success' => "ok");
        $page_link_hash = $this->input->server('HTTP_REFERER', TRUE);
        $page_hash = $this->input->post('page_hash', TRUE);

        if (empty($page_hash))
            $page_hash = '';

        $phone_sms_attempts = $this->phone->getSmsAttemps($id_user);

        if ($this->phone->is_voice_verification_enabled($id_user) != TRUE)
            return $this->send_ajax_responce(['error' => _e('security/data_voice')]);

        /*if ($purpose === 'phone_verification') {
            //$purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE, $page_link_hash = '', $page_hash = ''
            $send_resp = $this->phone->sendVoiceCode($purpose, null, null, TRUE, $page_link_hash, $page_hash, $lang);
            $this->security_model->setProtectionTypeValueByAttrName('withdrawal_standart_credit', 'email', $id_user);
        } else*/
            return $this->send_ajax_responce(['error' => _e('security/data_voice')]);

        $voice_verification_enabled = $this->phone->is_voice_verification_enabled($id_user);
        $resp['data'] = ['voice_verification_enabled' => $voice_verification_enabled];

        if (isset($send_resp['error']) && !empty($send_resp['error'])) {
            if (isset($send_resp['error']['service'])) {
                $resp['error'] = _e('Ошибка сервиса: ') . $send_resp['error']['service'] . '. ' . _e('Сообщите, пожалуйста, в тех. поддержку.');
            } else
                switch ($send_resp['error']) {
                    case 1:
                    case 2: $resp['error'] = _e('security/data6');
                        break;
                    case 3: $resp['error'] = _e('security/data7');
                        break;
                    case 4: $resp['error'] = _e('security/data8');
                        break;
                    case 5: $resp['error'] = _e('security/data9');
                        break;
                    case 6: $resp['error'] = _e('security/data10');
                        break;
                    case 44: $resp['error'] = _e('security/data11');
                        break;
                    default : $resp['error'] = _e('security/data12');
                }
        } else if (isset($send_resp['success'])) {
            if ($purpose === 'phone_verification') {
                $resp['success'] = 'OK';
                $resp['action'] = 'start-counter';
            } else {
                $resp['success'] = _e('security/data13');
                $resp['action'] = 'start-counter';
            }
        }

        $this->send_ajax_responce($resp);
    }
// отключаем whatsapp
    public function ajax_whatsapp_request() {

        return $this->send_ajax_responce(['error' => _e('Этот тип безопасности отключен')]);

        $this->load->model('whatsapp_model', 'whatsapp');
        $this->load->model('security_model');

        $purpose = $this->input->post('purpose');

        $purpose_matches = array();

        $purpose_preg_match_res = preg_match('/(\w_\d)[5,50]/', $purpose, $purpose_matches);
        if ($purpose_preg_match_res == 1 && strlen($purpose_matches[1]) == strlen($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        if (empty($purpose))
            return $this->send_ajax_responce(_e('security/data5'));

        $id_user = $this->accaunt->get_user_id();

        $resp = array();
        $send_resp = array('success' => "ok");
        /*if ($purpose === 'phone_verification') {
            $send_resp = $this->whatsapp->sendSmsCode($purpose, null, null, TRUE);
            $this->security_model->setProtectionTypeValueByAttrName('withdrawal_standart_credit', 'whatsapp', $id_user);
        } else*/
            $send_resp = $this->whatsapp->sendSmsCode($purpose);

        if (isset($send_resp['error']) && !empty($send_resp['error'])) {
            switch ($send_resp['error']) {
                case 1:
                case 2: $resp['error'] = _e('security/data6');
                    break;
                case 3: $resp['error'] = _e('security/data7');
                    break;
                case 4: $resp['error'] = _e('security/data8');
                    break;
                case 5: $resp['error'] = _e('security/data9');
                    break;
                case 6: $resp['error'] = _e('security/data10');
                    break;
                case 44: $resp['error'] = _e('security/data11');
                    break;
                case 'service': $resp['error'] = _e('Ошибка сервиса: ') . $send_resp['error']['service'] . '. ' . _e('Сообщите, пожалуйста, в тех. поддержку.');
                    break;
                default : $resp['error'] = _e('security/data12');
            }
        } else if (isset($send_resp['success'])) {
            if ($purpose === 'phone_verification') {
                $resp['success'] = 'OK';
                $resp['action'] = 'start-counter';
            } else {
                $resp['success'] = _e('security/data13');
                $resp['action'] = 'start-counter';
            }
        }

        $this->send_ajax_responce($resp);
    }

//New security module functions
//
//
    //запрос на отправку кода смс для разных целей
    //сохранение таблицы кодов, прочитал - удалил
    public function get_code_chart($file_name) {

        if (empty($file_name) || !preg_match('/one_pass_(\d{6,10})_([0-9A-Z]{20}).pdf/', $file_name))
            return show_404();

        $path = 'upload/one_pass/' . $file_name;
        if (!file_exists($path)) {
            return show_404();
        }

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Content-disposition: attachment; filename="webtransfer_code.pdf"');
        readfile($path);
        unlink($path);
    }

    //безопасен - возвращает возможность воспользоваться голосовым произнесением кода
    public function ajax_voice_state() {
        $this->load->model('phone_model', 'phone');
        $id_user = $this->accaunt->get_user_id();
        $purpose = $this->input->post('purpose', true);

        echo json_encode(array('state' => $this->phone->is_voice_verification_enabled($id_user, $purpose)));

        return;
    }

    static function ajax_responce_static($res, $isAjaxMes = FALSE) {
        $data = [];
        if (is_string($res))
            $data = ['error' => $res];
        else
            $data = $res;


        if (!$isAjaxMes)
            accaunt_message($data, $error, 'error');
        else
            return json_encode($data);

        return FALSE;
    }

    public function ajax_responce($res) {
        $data = [];
        if (is_string($res))
            $data = ['error' => $res];
        else
            $data = $res;

        echo json_encode($data);
        return FALSE;
    }



    static function get_error_string($error, $security_type, $params = null) {

        switch ($error) {
            case 1:
            case 2:
            case 3: $resp = _e('security/data6'); //$lang['security/data6'] = 'Ошибка передачи данных. Попробуйте еще раз.';      [position 2272:8]
                break;
            case 4: $resp = _e('security/data7'); //$lang['security/data7'] = 'Для продолжения, укажите номер телефона и верифицируйте его в профиле пользователя.';      [position 2273:8]
                break;
            case 5: $resp = _e('security/data8'); //$lang['security/data8'] = 'Для продолжения, верифицируйте номер телефона в профиле.';      [position 2274:8]
                break;
            case 6:
                $resp = _e('security/data15'); //$lang['security/data15'] = 'Код не был отправлен. Сначала нажмите на кнопку "Запрос кода".';      [position 1924:8]
                if ($security_type == 'code')
                    $resp = _e('Невозможно проверить корректность кода. Обратитесь в службу поддержки.'); //$lang['security/data15'] = 'Код не был отправлен. Сначала нажмите на кнопку "Запрос кода".';      [position 1924:8]
                break;

            case 7: $resp = _e('security/data16'); //$lang['security/data16'] = 'Превышено количество попыток ввода. Запросите новый пароль.';      [position 1925:8]
                break;

            case 8: $resp = _e('Вы ввели неверный код. Повторите попытку.');
                break;

            case 9: $resp = _e('security/data9'); //$lang['security/data9'] = 'Превышено количество попыток отправки смс. Попробуйте через 24 часа.';
                break;

            case 10: $resp = _e('Превышено количество попыток ввода кода. Попробуйте через 24 часа.'); //$lang['security/data9'] = 'Превышено количество попыток отправки смс. Попробуйте через 24 часа.';
                break;

            case 32: $resp = sprintf(_e('security/data33'), $params['time']); //$lang['security/data33'] = 'Повторный запрос возможен через <span class=\'counter\'></span> с.';      [position 1942:8]
                break;

            case 44: $resp = _e('security/data11'); //$lang['security/data11'] = 'Номер уже подтвержден.';      [position 1920:8]
                break;
            case 66: $resp = _e('Срок действия кода истек. Запросите код еще раз.');
                break;
            case 67: $resp = _e('Повторный запрос СМС возможен через <span class=\'counter\'></span> с.');
                break;
            case 101: $resp = _e('open_pass_new_code_error');
                break;
            case 'whatsapp_error_010230': $resp = _e('whatsapp_error_010230');
                break;
            case 'whatsapp_error_010240': $resp = _e('whatsapp_error_010240');
                break;
            case 'whatsapp_error_010010': $resp = _e('whatsapp_error_010010');
                break;

            default : $resp = _e('security/data12') . $error;
        }
        //$resp .= ' Code: '.$error." $security_type";

        return $resp;
    }

    public function ajax_email_state() {
        $this->load->model('email_model', 'email');
        if ($this->email->isUserEmailVerified() === TRUE) {
            return $this->ajax_responce(['success' => 'OK']);
        }

        return $this->ajax_responce(_e('accaunt/applications_57_2'));
    }
//отключен whatsapp
    public function ajax_save_phone_verification() {
        return FALSE;
        $data = json_decode($this->input->post('data', TRUE), TRUE);
        $code = '000000';
        if (isset($data['code']))
            $code = $data['code'];
        $purpose_in = 'phone_verification';

        $this->load->model('whatsapp_new_model', 'whatsapp');
        $res = $this->whatsapp->check_code($code, $purpose_in);

        if (isset($res['success'])) {
            $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
            $this->phone->setVerifiedStatus($user_id);
        }

        if (isset($res['error']))
            return $this->ajax_responce(self::get_error_string($res['error']));

        $this->ajax_responce($res);
    }

    public function ajax_check_code() {


        $purpose_in = $this->input->post('purpose', TRUE);
        $security_type_in = $this->input->post('security_type', TRUE);
        $code_in = $this->input->post('code', TRUE);
        $recaptcha_response = $this->input->post('recaptcha', TRUE);

        if (empty($purpose_in) || empty($code_in))
            return $this->ajax_responce(_e('Ошибка передачи данных. Перезагрузите страницу и попробуйте еще раз.'));

        $this->load->model('phone_model', 'phone');
        $this->load->model('security_model');

        $preferable_security_type = $this->security_model->get_preferable_security_type();
        $allowed_security_type = $this->security_model->get_allowed_security_type($purpose_in);

        if ($purpose_in !== 'change_security_type' && $purpose_in !== 'set_security_type' && $purpose_in !== 'phone_verification' &&
                (!in_array($preferable_security_type, $allowed_security_type) || !in_array($security_type_in, $allowed_security_type) )) {
            //if ($purpose_in != 'phone_verification')
            return $this->ajax_responce(_e('change_preferable_security_type'));
        }

        $this->load->model('accaunt_model', 'accaunt');
        $user_id = $this->accaunt->get_user_id();

        if( $this->security_model->check_recaptcha( $recaptcha_response ) === FALSE
           && ($user_id == 500733 || $user_id == 500757 || $user_id == 500150 ) #remove
        )
        {
            #clean any codes. can't pass without itcc
            $this->load->model('phone_model');
            $this->phone->setPhoneCode( $purpose_in, $user_id );
            
            return $this->ajax_responce(['error' => _e('you_are_not_human'), 'action' => 'recaptcha.reload']);
        }


        $security_type = $this->security_model->choose_security_type($purpose_in, $security_type_in);
        $page_hash = '';


        $skip_phone_verification = FALSE;
        if ($purpose_in == 'phone_verification') {
            $skip_phone_verification = TRUE;
        }

        switch ($security_type) {
            /*case 'email':
                $this->load->model('email_model', 'email');
                //                              ( $purpose, $code, $user_id = null, $no_empty = FALSE )
                $res = $this->email->checkSmsCode($purpose_in, $code_in, null, TRUE);
                break;
            */
            /*case 'whatsapp':
                $this->load->model('whatsapp_new_model', 'whatsapp');
                if ($code_in == '000000') {
                    $res = $this->whatsapp->check_ok_message($purpose_in, null);
                } else {
                    $res = $this->whatsapp->check_code($code_in, $purpose_in, null, '', TRUE);
                }

                if (isset($res['error']) && isset($res['status'])) {
                    $res['error'] = 'whatsapp_error_' . $res['status'];
                }

                if (isset($res['action'])) {
                    $ret = [
                        'action' => $res['action'],
                        'data' => $res['data']
                    ];
                    if (isset($res['error']))
                        $ret['error'] = self::get_error_string($res['error']);
                    else
                        $ret['error'] = ' ';
                    return $this->ajax_responce($ret);
                }
                break;*/
            case 'one_pass':
                $this->load->model('one_pass_model');
                $res = $this->one_pass_model->check_code($code_in, $purpose_in, null, $page_hash, TRUE, $check_sms_count = FALSE);
                if (isset($res['action']))
                    return $this->ajax_responce([
                                'action' => $res['action'],
                                'error' => self::get_error_string($res['error']),
                                'data' => $res['data']
                    ]);
                break;
            case 'voice':
            case 'sms':
                $this->load->model('phone_model', 'phone');
                $sms_purpose = 'change_security_type';
                if ($purpose_in == 'phone_verification') {
                    $sms_purpose = $purpose_in;
                }
                $res = $this->phone->checkSmsCode($sms_purpose, $code_in, null, $skip_phone_verification, $page_hash, TRUE);
                break;
            case 'code':
                $this->load->model('Wt_code_model', 'wt_code');

                $temp = FALSE;
                if( $purpose_in === 'set_security_type' ) $temp = TRUE;
                if ($this->wt_code->check_code_first($code_in, $purpose_in, $temp) === FALSE)
                    $res['error'] = 8;

                break;
            default:
                return $this->ajax_responce(_e('Выбран неизвестный тип безопасности. Перезагрузите страницу и попробуйте еще раз.'));
        }


        if (isset($res['error']))
            return $this->ajax_responce(self::get_error_string($res['error']));

        $data = [
            'success' => 1,
            'action' => 'call_back'
        ];

        /*
        if ($purpose_in == 'phone_verification') {
            $this->security_model->copy_protection_type($user_id, 'phone_verification', 'change_security_type');
        }
*/
        if (isset($res['saved_code'])) {
            $data['saved_code'] = $res['saved_code'];
        }

        return $this->ajax_responce($data);
    }

    public function ajax_get_code() {

        $purpose_in = $this->input->post('purpose', TRUE);
        $security_type_in = $this->input->post('security_type', TRUE);
        $lang = $this->input->post('lang', TRUE);

        if (empty($purpose_in) || empty($security_type_in))
            return $this->ajax_responce(_e('Ошибка передачи данных. Перезагрузите страницу и попробуйте еще раз.'));

        $_POST['purpose'] = $purpose_in;

        $resp = array();
        $send_resp = array('success' => "ok");

        $page_link_hash = $this->input->server('HTTP_REFERER', TRUE);
        $page_hash = $this->input->post('page_hash', TRUE);

        $id_user = $this->accaunt->get_user_id();

        //<editor-fold defaultstate="collapsed" desc="Проверяем тип безопасности и можем ли мы отправить код для данной цели">
        $this->load->model('security_model');

        $preferable_security_type = $this->security_model->get_preferable_security_type();
        $allowed_security_type = $this->security_model->get_allowed_security_type($purpose_in);


        //мы меняем настройки
        if (in_array($purpose_in, ['change_security_type', 'set_security_type', 'phone_verification'])) {
            if (empty($security_type_in) || !in_array($security_type_in, $allowed_security_type))
                return $this->ajax_responce(_e('Ошибка передачи данных. Перезагрузите страницу и попробуйте еще раз.'));
        }else {

            if (empty($security_type_in))
                $security_type_in = $preferable_security_type;

            //установленный в настройках тип и запрашиваемый тип должны быть разрешены для проведения операции
            if (!in_array($security_type_in, $allowed_security_type) ||
                    !in_array($preferable_security_type, $allowed_security_type)
            ) {
                return $this->ajax_responce(_e('change_preferable_security_type'));
            }
        }

        //</editor-fold>
        //echo '-1-';

        $skip_phone_verification = FALSE;
        if ($purpose_in == 'phone_verification') {
            $skip_phone_verification = TRUE;
        }
        $is_voice_verification_enabled = false;
        switch ($security_type_in) {
            case 'sms':
                $this->load->model('phone_model', 'phone');
                $send_resp = $this->phone->sendSmsCode($purpose_in, null, null, $skip_phone_verification, $page_link_hash, $page_hash);
                $is_voice_verification_enabled = $this->phone->is_voice_verification_enabled($id_user, $purpose_in);
                //$this->security_model->setProtectionTypeValueByAttrName( $purpose_in, 'email', $id_user );

                break;
            case 'voice':
                $this->load->model('phone_model', 'phone');
                $send_resp = $this->phone->sendVoiceCode($purpose_in, null, null, $skip_phone_verification, '', '', $lang);
                break;
            /*case 'email':
                $this->load->model('email_model', 'email');
                $isUserEmailVerified = $this->email->isUserEmailVerified($id_user);
                if (FALSE === $isUserEmailVerified) {
                    $send_resp = array('error' => 7);
                } else {
                    $send_resp = $this->email->sendSmsCode($purpose_in);
                }
                break;
                */
            /*
            case 'whatsapp':

                $this->load->model('whatsapp_new_model', 'whatsapp');
                $send_resp = $this->whatsapp->sendCode($purpose_in);
                break;
             */
            default:
                return FALSE;
                break;
        }

        //echo '-2-';
        if (isset($send_resp['error']) && !empty($send_resp['error'])) {
            if (isset($send_resp['error']['service'])) {
                $resp['error'] = _e('Ошибка сервиса: ') . $send_resp['error']['service'] . '. ' . _e('Сообщите, пожалуйста, в тех. поддержку.');
            } else
                $resp['error'] = self::get_error_string($send_resp['error'], null, $send_resp);
        }else
        if (isset($send_resp['success'])) {

            $resp['counter-time'] = '600';
            $resp['success'] = sprintf(_e('Сообщение отправлено. Повторно вы сможете запросить код через <span class=\'counter\'>%s</span> сек.'), $resp['counter-time']);
            $resp['action'] = 'start-counter';

            $resp['counter-handle'] = '.counter';
        }
        $resp['voice_verification_enabled'] = $is_voice_verification_enabled;

        return $this->ajax_responce($resp);
    }

    public function ajax_get_sec_data() {

        if (FALSE === ($res = $this->check_security_type() ))
            return FALSE;

        list($form, $security_type) = $res;
        //если нет защиты - возвращаем успех, но без формы
        if ($security_type === NULL)
            $form = NULL;

        $data = array(
            'success' => 1,
            'form' => $form,
            'security_type' => $security_type,
            'security_request_code_url' => site_url('/security/ajax/get_code'),
            'security_check_code_url' => site_url('/security/ajax/check_code')
        );


        return $this->ajax_responce($data);
    }

    private function check_security_type($security_type_required = FALSE) {

        $purpose_in = $this->input->post('purpose', TRUE);
        $security_type_src = $this->input->post('security_type', TRUE);

        $security_type_in = $security_type_src;
        if (empty($purpose_in) || ( $security_type_required && empty($security_type_in) ))
            return $this->ajax_responce(_e('Ошибка передачи данных. Перезагрузите страницу и попробуйте еще раз.'));

        //<editor-fold defaultstate="collapsed" desc="Проверяем тип безопасности и можем ли мы отправить код для данной цели">
        $this->load->model('security_model');

        $preferable_security_type = $this->security_model->get_preferable_security_type();
        $allowed_security_type = $this->security_model->get_allowed_security_type($purpose_in);


        //Когда еще не установлен тип верификации
        if (in_array($purpose_in, ['change_security_type', 'set_security_type', 'phone_verification'])) {
            if (!in_array($security_type_in, $allowed_security_type))
                $security_type_in = $allowed_security_type[0];
        }else {
            if (empty($security_type_in))
                $security_type_in = $preferable_security_type;

            //установленный в настройках тип и запрашиваемый тип должны быть разрешены для проведения операции
            if (!in_array($security_type_in, $allowed_security_type) ||
                    !in_array($preferable_security_type, $allowed_security_type)
            ) {
                //if ($purpose_in != 'phone_verification')
                return $this->ajax_responce(_e('change_preferable_security_type'));
            }
        }

        //</editor-fold>
        #для тех, у кого отключена безопасность - ставим смены через емайл
        //окно выбора способа

        $current_user_id = $this->accaunt->get_user_id();
        $this->load->model('phone_model', 'phone');
        $get_phone_by_user_id = $this->phone->get_phone_by_user_id($current_user_id);

        if ($purpose_in == 'set_phone' && ( empty($get_phone_by_user_id) || empty($get_phone_by_user_id->phone_number) )) {
            return ['', ''];
        } else
        if (empty($preferable_security_type) && $purpose_in == 'change_security_type') {
            if (empty($security_type_src))
                $security_type_in = 'select';
            else
                $security_type_in = $security_type_src;
        }

        $res = $this->security_model->get_security_form($purpose_in, $security_type_in);

        if (is_array($res) && isset($res['error'])) {
            $this->ajax_responce($res['error']);
            return FALSE;
        } else
        if ($res === FALSE) {
            $this->ajax_responce(_e('Выбран неверный тип безопасности. Перезагрузите страницу и попробуйте еще раз.'));
            return FALSE;
        }

        return $res;
    }

    public function ajax_save_security_type() {
        $json = $this->input->post('data', TRUE);

        try {
            $data = json_decode($json);
        } catch (Exception $exc) {

            return
                    $this->ajax_responce(_e('Не удалось сохранить данные. Нажмите Отмена и попробуйте еще раз пройти процесс из изменения типа безопасности.'));
        }

        $old = $data->old;
        $new = $data->new;

        if (empty($old) || empty($new)) {
            return $this->ajax_responce(_e('Не удалось сохранить данные. Нажмите Отмена и попробуйте еще раз пройти процесс из изменения типа безопасности.'));
        }



        $this->load->model('phone_model', 'phone');
        $this->load->model('security_model');
        $this->load->model('wt_code_model');
        $this->load->model('one_pass_model', 'one_pass');

//        $preferable_security_type  = $this->security_model->get_preferable_security_type();
        $allowed_security_type = $this->security_model->get_allowed_security_type('set_security_type');

        if (!in_array($new->type, $allowed_security_type)) {
            return $this->ajax_responce(_e('Данный тип безопасности недоступен. Выберите другой тип безопасности и Сохраните его.'));
        }
        #предыдущий тип безопасности берем из БД
        $old->type = $preferable_security_type = $this->security_model->get_preferable_security_type();
        $verification_purpose = FALSE;
        //if (empty($preferable_security_type)) {
        //    $verification_purpose = TRUE;
        //}

        //for callback force_one_pass_setup


        $user_id = $this->accaunt->get_user_id();
        //Если безопасность == '' - разрешаем изменить
        if ($this->one_pass->wrong_code_show($user_id)) {
            $this->load->model('phone_model', 'phone');
            $id_user = $this->accaunt->get_user_id();
            $this->security_model->copy_protection_type($id_user, 'set_security_type', 'change_security_type');
            $old->code = $new->code;
        }

        $page_hash = '';        //$purpose, $code, $user_id = null, $verification_purpose = FALSE, $page_hash = '', $no_empty = FALSE
        #нет смысла проверять прошлый код, если безопасности не было
        if ($old->type == '') {
            //$this->load->model('email_model');
            $check_old = TRUE;//$this->email_model->checkSmsCode('change_security_type', $old->code);
        } else
            $check_old = $this->phone->checkSmsCode('change_security_type', $old->code, $verification_purpose, FALSE, $page_hash);

        if ($check_old === FALSE || isset($check_old['error'])) {
            if (isset($check_old['error']))
                return $this->ajax_responce(self::get_error_string($check_old['error']));
            else
                return $this->ajax_responce(_e('change_preferable_security_type'));
        }
        if (!empty($new->type)) {
            if ($new->type == 'code') {
//                if( $this->check_wt_code($new->code) === FALSE ) return $this->ajax_responce( self::get_error_string( 8 ) );
                //$page_hash = '', $no_empty = FALSE
                $check_new = $this->phone->checkSmsCode('set_security_type', $new->code, null, FALSE, $page_hash);

                if ($check_new === FALSE || isset($check_new['error']))
                    return $this->ajax_responce(_e('Срок действия кода истек. Повторите запрос снова.'));

                #если код верный - активируем секрет
                $res = $this->wt_code_model->save_active_secret($user_id);
                if ($res === FALSE)
                    return $this->ajax_responce(_e('Не удалось сохранить данные. Перезагрузите страницу и попробуйте еще раз.'));
            }/*elseif ($new->type == 'email') {
                $this->load->model('email_model', 'email');
                if ($this->email->isUserEmailVerified() === FALSE) {
                    return $this->ajax_responce(_e('accaunt/applications_57_2'));
                }


            }*/
            /* else
              {
              $check_new = $this->phone->checkSmsCode('set_security_type', $new->code, null, FALSE, $page_hash);

              if( $check_new === FALSE || isset($check_new['error']) )
              {

              if( isset($check_new['error']) ) return $this->ajax_responce( self::get_error_string( $check_new['error'] ) );
              else
              return $this->ajax_responce( _e('change_preferable_security_type') );
              }
              } */
        } else
            $new->type = '';

        if('' == $new->type) trigger_error("$user_id: Set empty security for user datetime: ".date('Y-m-d H:i:s')); //this is for log off security

        $this->db->trans_start();
        {
            $save_res = $this->security_model->setProtectionTypeValueByAttrName("withdrawal_standart_credit", $new->type);
            $clean_res = $this->phone->cleanUsersCodes();
        }
        $this->db->trans_complete();

        if ($save_res !== TRUE || $clean_res !== TRUE)
            return $this->ajax_responce(_e('Не удалось сохранить данные. Нажмите Отмена и попробуйте еще раз пройти процесс из изменения типа безопасности.'));

        if ($old->type == 'one_pass' && $new->type == 'one_pass') {
            return $this->ajax_responce(['success' => _e('Создана новая карта кодов'), 'action' => 'refesh_page']);
        }

        return $this->ajax_responce(['success' => _e('security/data20'), 'action' => 'refesh_page']);
    }

    #!New security module functions

    public function ajax_viber_request() {
        // отключаем viber
        return $this->send_ajax_responce(['error' => _e('Этот тип безопасности отключен')]);

        $this->load->model('viber_model', 'viber');
        $this->load->model('security_model');

        $purpose = $this->input->post('purpose');

        $purpose_matches = array();

        $purpose_preg_match_res = preg_match('/(\w_\d)[5,50]/', $purpose, $purpose_matches);

        if ($purpose_preg_match_res == 1 && strlen($purpose_matches[1]) == strlen($purpose)) {
            return $this->send_ajax_responce(_e('Невозможно отправить код. Перезагрузите стараницу.'));
        }

        if (empty($purpose)) {
            return $this->send_ajax_responce(_e('Невозможно отправить код. Перезагрузите стараницу.'));
        }

        $id_user = $this->accaunt->get_user_id();

        $resp = array();
        $send_resp = array('success' => "ok");

        /*if ($purpose === 'phone_verification') {
            $send_resp = $this->viber->sendSmsCode($purpose, null, null, TRUE);
//            $this->security_model->setProtectionTypeValueByAttrName( 'withdrawal_standart_credit', 'whatsapp', $id_user );
            $this->security_model->setProtectionTypeValueByAttrName('withdrawal_standart_credit', 'viber', $id_user);
        } else */{
            $send_resp = $this->viber->sendSmsCode($purpose);
        }

        if (isset($send_resp['error']) && !empty($send_resp['error'])) {
            switch (['error']) {
                case 1:
                case 2: $resp['error'] = _e('Ошибка передачи данных. Попробуйте еще раз.');
                    break;
                case 3: $resp['error'] = _e('Для продолжения, укажите номер телефона и верифицируйте его в профиле пользователя.');
                    break;
                case 4: $resp['error'] = _e('Для продолжения, верифицируйте номер телефона в профиле.');
                    break;
                case 5: $resp['error'] = _e('Превышено количество попыток отправки смс. Попробуйте через 24 часа.');
                    break;
                case 6: $resp['error'] = _e('Невозможно сохранить данные. Обратитесь в службу поддержки.');
                    break;
                case 44: $resp['error'] = _e('Номер уже подтвержден.');
                    break;
                case 'service': $resp['error'] = _e('Ошибка сервиса: ') . $send_resp['error']['service'] . '. ' . _e('Сообщите, пожалуйста, в тех. поддержку.');
                    break;
                default : $resp['error'] = _e('Неизвестная ошибка. Обратитесь в службу поддержки.');
            }
        } else if (isset($send_resp['success'])) {
            if ($purpose === 'phone_verification') {
                $resp['success'] = 'OK';
                $resp['action'] = 'start-counter';
            } else {
                $resp['success'] = _e('Сообщение отправлено.');
                $resp['action'] = 'start-counter';
            }
        }

        $this->send_ajax_responce($resp);
    }

    // отправка e-mail с кодом
    public function ajax_email_request() {
        return false;
        $this->load->model('email_model', 'email');
        $this->load->model('security_model');

        $purpose = $this->input->post('purpose');

        if ($purpose == 'save_security_settings' || $purpose == 'save_p2p_payment_data') {
            return $this->send_ajax_responce(['error' => _e('Данную операцию можно подтвердить только через СМС или Webtransfer Auth. Перезагрузите старницу и попробуйте еще раз.')]);
        }

        $purpose_matches = array();

        $purpose_preg_match_res = preg_match('/(\w_\d)[5,50]/', $purpose, $purpose_matches);

        if ($purpose_preg_match_res == 1 && strlen($purpose_matches[1]) == strlen($purpose)) {
            return $this->send_ajax_responce(_e('Невозможно отправить код. Перезагрузите стараницу.'));
        }

        if (empty($purpose)) {
            return $this->send_ajax_responce(_e('Невозможно отправить код. Перезагрузите стараницу.'));
        }

        $id_user = $this->accaunt->get_user_id();

        $resp = array();
        $send_resp = array('success' => "ok");

        /*if ($purpose === 'phone_verification') {
            $send_resp = $this->email->sendSmsCode($purpose, null, null, TRUE);
//            $this->security_model->setProtectionTypeValueByAttrName( 'withdrawal_standart_credit', 'whatsapp', $id_user );
            $this->security_model->setProtectionTypeValueByAttrName('withdrawal_standart_credit', 'email', $id_user);
        } else */{
            $isUserEmailVerified = $this->email->isUserEmailVerified($id_user);
            if (FALSE === $isUserEmailVerified) {
                $send_resp = array('error' => 7);
            } else {
                $send_resp = $this->email->sendSmsCode($purpose);
            }
        }


        if (isset($send_resp['error'])) {
            switch ($send_resp['error']) {
                case 1:
                case 2: $resp['error'] = _e('Ошибка передачи данных. Попробуйте еще раз.');
                    break;
                case 3: $resp['error'] = _e('Для продолжения, укажите EMail и верифицируйте его в профиле пользователя.');
                    break;
                case 5: $resp['error'] = _e('Превышено количество попыток отправки EMail. Попробуйте через 24 часа.');
                    break;
                case 6: $resp['error'] = _e('Невозможно сохранить данные. Обратитесь в службу поддержки.');
                    break;
                case 7: $resp['error'] = _e('Ваш Email не подтвержден. Подтвердите его и попробуйте снова.');
                    break;
                default :
                    if (isset($send_resp['error']['service']))
                        $resp['error'] = '<b>' . _e('Ошибка сервиса: ') . '</b>' . $send_resp['error']['service'];
                    else
                        $resp['error'] = _e('Неизвестная ошибка. Обратитесь в службу поддержки.');
            }
        }
        else if (isset($send_resp['success'])) {
            if ($purpose === 'phone_verification') {
                $resp['success'] = 'OK';
                $resp['action'] = 'start-counter';
            } else {
                $resp['success'] = _e('Сообщение отправлено. Следующее можно отправить через: ');
                $resp['action'] = 'start-counter';
            }
        }

        $this->send_ajax_responce($resp);
    }

    //никогда не использовать
    private function ajax_save_security() {
        return false;
        $this->load->model('phone_model', 'phone');
        $this->load->model('security_model', 'security_model');
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('email_model', 'email');
        $this->load->library("Base32");

        $purpose = $this->input->post('purpose', TRUE);
        $code_in = $this->input->post('code', TRUE);
        $valid = $this->input->post('valid', TRUE);
        $attr_name = $this->input->post('name', TRUE);
        $attr_value = $this->input->post('value', TRUE);
        $user_id = $this->accaunt->get_user_id();

        return false;
        $prev_attr_val = $this->security_model->getProtectTypeByAttrName($attr_name);
        $pass_without_code = FALSE;


        if ($purpose != 'save_security_settings') {
            return $this->send_ajax_responce(['error' => _e('Данную операцию можно подтвердить только через СМС или Webtransfer Auth. Перезагрузите старницу и попробуйте еще раз.')]);
        }

        if ($attr_value == $prev_attr_val) {
            $resp['error'] = _e('Выберите другой метод.');
            return $this->send_ajax_responce($resp);
        }

        //при смене смс <--> вотсап мы не просим ввод какого-либо кода
        if (( $attr_value == 'sms' && $prev_attr_val == 'whatsapp') ||
                ( $attr_value == 'whatsapp' && $prev_attr_val == 'sms') ||
                ( $attr_value == 'whatsapp' && $prev_attr_val == 'viber') ||
                ( $attr_value == 'viber' && $prev_attr_val == 'whatsapp') ||
                ( $attr_value == 'sms' && $prev_attr_val == 'viber') ||
                ( $attr_value == 'viber' && $prev_attr_val == 'sms')
        ) {
            $pass_without_code = TRUE;
        }

        // отключим whatsapp
        if ($attr_value == 'whatsapp') {
            $resp['error'] = _e('Этот тип безопасности отключен.');
            return $this->send_ajax_responce($resp);
        }
        // отключим Viber
        if ($attr_value == 'viber') {
            $resp['error'] = _e('Этот тип безопасности отключен.');
            return $this->send_ajax_responce($resp);
        }
        if ($attr_value == 'sms') {
            $resp['error'] = _e('Этот тип безопасности отключен.');
            return $this->send_ajax_responce($resp);
        }

        return false;
        //при смене c мессенджеров на email коды не запрашиваются только дяя некотрых стран
        $phone = $this->phone->getPhone($user_id);
        $isProblemOperator = $this->phone->is_problem_operator($phone);
        //$isVerifiedEmail = $this->email->isUserEmailVerified( $user_id );
//        if ( $isProblemOperator || $isVerifiedEmail ){//когда плохо отправлялись СМС
        /*
          if ( $isProblemOperator  ){
          if( ($prev_attr_val == 'whatsapp' && $attr_value == 'email' )  ||
          ( $prev_attr_val == 'viber' && $attr_value == 'email' )  ||
          ( $prev_attr_val == 'sms' && $attr_value == 'email' ) )
          {
          $pass_without_code = TRUE;
          }
          }
         */

        if ((FALSE === $pass_without_code && empty($code_in) ) || empty($purpose) || empty($attr_name)) {

            $resp['error'] = _e('security/data14');
            return $this->send_ajax_responce($resp);
        }

        $code = $code_in;
        $match = array();
        if ($code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset($match[0])) {
            $code = $match[0];
        }

        $resp_confirm = array('success' => "ok");


        /*
          if (1 != $valid){
          if ( $attr_value == 'email' || $prev_attr_val == 'email'){
          $this->load->model('email_model','email');
          $resp_confirm = $this->email->checkSmsCode($purpose, $code);
          } else
          $resp_confirm = $this->phone->checkSmsCode($purpose, $code);
          } */
        //проверяем все через СМС
        $resp_confirm = $this->phone->checkSmsCode($purpose, $code);


        if (FALSE === $pass_without_code && isset($resp_confirm['error'])) {
            switch ($resp_confirm['error']) {
                case 1:
                case 2:
                case 3: $resp['error'] = _e('security/data6');
                    break;
                case 4: $resp['error'] = _e('security/data7');
                    break;
                case 5: $resp['error'] = _e('security/data8');
                    break;
                case 6: $resp['error'] = _e('security/data15');
                    break;

                case 66: $resp['error'] = _e('Срок действия кода истек. Запросите код знова.');
                    break;
                case 67: $resp['error'] = _e('Повторный запрос СМС возможен через <span class=\'counter\'></span> с.');
                    break;

                case 7: $resp['error'] = _e('security/data16');
                    break;

                case 8: $resp['error'] = _e('security/data17');
                    break;
                default : $resp['error'] = _e('security/data12');
            }
        } else if (TRUE === $pass_without_code || isset($resp_confirm['success'])) {

            if ($attr_value == "code" || $prev_attr_val == 'code') {
                if ($valid != 1) {
                    $key = sha1(getSecretOtpauth() . time() . microtime());
                    $cd = new Base32();
                    $secret = $cd->base32_encode($key);
                    if ($this->security_model->setProtectionTypeValueByAttrName("code_secret", $key, $user_id)) {
                        $resp['code'] = "<img src='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=200x200&chld=M|0&cht=qr&chl=wbtotpauth://totp/$user_id@webtransfer.com%3Fsecret%3D$secret'>";
                    } else
                        $resp['error'] = _e('security/data18');
                } else {
                    if (self::checkCode($user_id, true)) {
                        $resp['error'] = _e('security/data19');
                    } else if (TRUE != $this->security_model->setProtectionTypeValueByAttrName($attr_name, $attr_value, $user_id)) {
                        $resp['error'] = _e('security/data18');
                    }
                }
            } else if (TRUE != $this->security_model->setProtectionTypeValueByAttrName($attr_name, $attr_value, $user_id)) {
                $resp['error'] = _e('security/data18');
            } else {
                $this->load->model("transactions_model", "transactions");
//                $this->transactions->paySmsCoust();
            }
            $resp['success'] = _e('security/data20');
            $resp['action'] = '';
        } else {
            $resp['error'] = _e('security/data18');
        }

        $this->send_ajax_responce($resp);
    }

    //подтверждение кода смс для разных целей
    public function ajax_sms_check() {
        $this->load->model('phone_model', 'phone');

        $purpose = $this->input->post('purpose', TRUE);
        $code_in = $this->input->post('code', TRUE);
        $page_hash = $this->input->post('page_hash', TRUE);

        if (empty($code_in) || empty($purpose)) {
            $resp['error'] = _e('security/data14');
            return $this->send_ajax_responce($resp);
        }

        $code = $code_in;
        $match = array();
        if ($code_in !== null && preg_match('/\d{3,10}/', $code_in, $match) && isset($match[0])) {
            $code = $match[0];
        }

        $resp_confirm = $this->phone->checkSmsCode($purpose, $code, $page_hash);
        if (isset($resp_confirm['error'])) {
            switch ($resp_confirm['error']) {
                case 1: $resp['error'] = _e('security/data6');
                    break;
                case 2:$resp['error'] = _e('security/data17');
                    break;
                case 3: $resp['error'] = _e('security/data6');
                    break;
                case 4: $resp['error'] = _e('security/data7');
                    break;
                case 5: $resp['error'] = _e('security/data8');
                    break;
                case 6: $resp['error'] = _e('security/data15');
                    break;

                case 7: $resp['error'] = _e('security/data16');
                    break;

                case 8: $resp['error'] = _e('security/data21');
                    break;
                default : $resp['error'] = _e('security/data12');
            }
        } else if (isset($resp_confirm['success'])) {
//            if( $purpose == 'withdrawal_standart_credit' )
//            {
//                $this->load->model("transactions_model","transactions");
//                $this->transactions->paySmsCoust();
//            }

            $resp['success'] = _e('security/data22');
            $resp['action'] = 'send';
        }

        $this->send_ajax_responce($resp);
    }

    //безопасен
    public function ajax_is_created_phone() {
        $form = $this->load->view('user/accaunt/security_module/change_phone', '', true);
        $data = array(
            'success' => 1,
            'form' => $form,
            'security_type' => 'test',
            'security_request_code_url' => site_url('change_phone'),
            'security_check_code_url' => site_url('change_phone')
        );

        return $this->send_ajax_responce($data);
    }


//STATIC
//
//
//безопасен
    static public function getProtectType($user_id) {
        if (!empty(self::$var))
            return self::$var;
        $ci = get_instance();
        $ci->load->model("security_model");
        $purpose = $ci->input->post('purpose', TRUE);

        if (empty($purpose) || $purpose == 'undefined')
            $purpose = 'withdrawal_standart_credit';

        self::$var = $ci->security_model->getProtectTypeByAttrName($purpose, $user_id);

        return self::$var;
    }
//безопасен
    static public function setProtectType($type) {
        self::$var = $type;
    }

//используется
    static public function checkSecurity($user_id, $isAjax = false, $isAjaxMes = false, $isSecurityAPI = FALSE, $is_new_phone = FALSE) {
        $data = new stdClass();
        $ci = get_instance();



        $purpose_in = $ci->input->post_get('purpose', TRUE);
        $security_type_in = $ci->input->post('security_type', TRUE);
        $code_in = $ci->input->post_get('code', TRUE);

        if (empty($purpose_in))
            $purpose_in = "withdrawal_standart_credit";

        $ci->load->model('security_model');

        $preferable_security_type = $ci->security_model->get_preferable_security_type();
        $allowed_security_type = $ci->security_model->get_allowed_security_type($purpose_in);

        if (empty($security_type_in))
            $security_type_in = $preferable_security_type;


        if (!in_array($preferable_security_type, $allowed_security_type) || !in_array($security_type_in, $allowed_security_type)) {
            $res['error'] = _e('change_preferable_security_type');
        }

        $security_type = $ci->security_model->choose_security_type($purpose_in, $security_type_in);

        $page_hash = '';
        switch ($security_type) {
            case 'email':
                $ci->load->model('email_model', 'email');
                //                              ( $purpose, $code, $user_id = null, $no_empty = FALSE )
                $res = $ci->email->checkSmsCode($purpose_in, $code_in, $user_id, TRUE);

                break;

            case 'voice':
            case 'sms':
                $ci->load->model('phone_model', 'phone');
                if ( $isSecurityAPI ){
                                                   // ( $purpose, $code, $user_id = null, $verification_purpose = FALSE, $page_hash = '', $no_empty = FALSE, $new_phone = FALSE  )
                    $res = $ci->phone->checkSmsCode($purpose_in, $code_in, $user_id, FALSE, $page_hash, TRUE, $is_new_phone);
                } else
                $res = $ci->phone->checkSmsCode('change_security_type', $code_in, $user_id, FALSE, $page_hash, TRUE);
                break;
            case 'one_pass':
                $ci->load->model('one_pass_model');
                $res = $ci->one_pass_model->check_code($code_in, $purpose_in, $user_id, $page_hash, FALSE);
                break;
            case 'code':
                $ci->load->model('Wt_code_model', 'wt_code');
                if ( $isSecurityAPI ){
                    $res = $ci->wt_code->check_code($code_in, $purpose_in, $user_id);
                    if ( !$res){
                        $res['error'] = _e('Код не верный');

                    }
                } else
                $res = $ci->wt_code->check_code_next($code_in, $purpose_in, $user_id);

                break;
            default:
                $res['error'] = _e('Выбран неизвестный тип безопасности. Перезагрузите страницу и попробуйте еще раз.');
        }


// return false;

        if (isset($res['error'])) {
            $error = self::get_error_string($res['error'], $security_type);
            if (!$isAjaxMes) {
                accaunt_message($data, $error, 'error');
                return TRUE;
            } else
                return $error;
        }

        return FALSE;

//        return $this->ajax_responce( $data );
//        $protection_type = self::getProtectType($user_id );
//
//        switch ($protection_type) {
//            case 'sms': return self::checkSms($isAjax, $isAjaxMes);
//            case 'code': return self::checkCode($user_id, $isAjax);
//            case 'whatsapp': return self::checkWhatsapp($isAjax, $isAjaxMes);
//            case 'email': return self::checkEmail($isAjax, $isAjaxMes);
//            default: return false;
//        }
    }
//не используется
    static private function checkEmail($user_id, $isAjax = false, $isAjaxMes = false) {
        if (!$isAjaxMes)
            accaunt_message($data, _e('Пожайлуста, выберите другой тип безопасности'), 'error');
        else
            return _e('Пожайлуста, выберите другой тип безопасности');
        return FALSE;

        $data = new stdClass();
        $ci = get_instance();
        $code = $ci->input->post('code');
        $purpose = $ci->input->post('purpose');
        if (empty($purpose))
            $purpose = "withdrawal_standart_credit";
        $sms_confirmed = array();

        $ci->load->model('email_model', 'email');
        $sms_confirmed = $ci->email->checkSmsCode($purpose, $code, $user_id);

        //sms protection
        if (isset($sms_confirmed['error'])) {
            if ($isAjax)
                return $sms_confirmed['error'];
            $error = '';
            switch ($sms_confirmed['error']) {
                case 1:
                case 2: $error = _e('security/data23');
                    break;
                case 3: $error = _e('security/data24');
                    break;
                case 4: $error = _e('security/data25');
                    break;
                case 5: $error = _e('security/data26');
                    break;
                case 6: $error = _e('security/data27');
                    break;
                case 7: $error = _e('security/data28');
                    break;
                case 8: $error = _e('security/data32');
                    break;
            }
            if (!$isAjaxMes)
                accaunt_message($data, $error, 'error');
            else
                return $error;
            return true;
        }
        return false;
    }
//используется в Permissions
    static private function checkSms($user_id, $isAjax = false, $isAjaxMes = false) {
        $data = new stdClass();
        $ci = get_instance();
        $code = $ci->input->post('code');
        $purpose = $ci->input->post('purpose', TRUE);
        $page_hash = $ci->input->post('page_hash', TRUE);
        if (empty($purpose))
            $purpose = "withdrawal_standart_credit";
        $sms_confirmed = array();
        $ci->load->model('phone_model', 'phone');
        //$purpose, $code, $user_id = null, $verification_purpose = FALSE, $page_hash = ''
        $sms_confirmed = $ci->phone->checkSmsCode($purpose, $code, null, FALSE, $page_hash);

        //sms protection
        if (isset($sms_confirmed['error'])) {
            if ($isAjax)
                return $sms_confirmed['error'];
            $error = '';
            switch ($sms_confirmed['error']) {
                case 1:
                case 2: $error = _e('security/data23');
                    break;
                case 3: $error = _e('security/data24');
                    break;
                case 4: $error = _e('security/data25');
                    break;
                case 5: $error = _e('security/data26');
                    break;
                case 6: $error = _e('security/data27');
                    break;
                case 7: $error = _e('security/data28');
                    break;
                case 8: $error = _e('security/data29');
                    break;
            }
            if (!$isAjaxMes)
                accaunt_message($data, $error, 'error');
            else
                return $error;
            return true;
        }
        if ($purpose == 'save_security_settings') {
            return false;
        }
        $ci->load->model("transactions_model", "transactions");
//        $ci->transactions->paySmsCoust();
        return false;
    }
//не используется
    static private function checkWhatsapp($user_id, $isAjax = false, $isAjaxMes = false) {

        // отключим whatsapp
        if (!$isAjaxMes)
            accaunt_message($data, _e('Пожайлуста, выберите другой тип безопасности'), 'error');
        else
            return _e('Пожайлуста, выберите другой тип безопасности');
        return FALSE;

        $data = new stdClass();
        $ci = get_instance();
        $code = $ci->input->post('code');
        $purpose = $ci->input->post('purpose');
        if (empty($purpose))
            $purpose = "withdrawal_standart_credit";
        $sms_confirmed = array();

        $ci->load->model('whatsapp_model', 'whatsapp');
        $sms_confirmed = $ci->whatsapp->checkSmsCode($purpose, $code);

        //sms protection
        if (isset($sms_confirmed['error'])) {
            if ($isAjax)
                return $sms_confirmed['error'];
            $error = '';
            switch ($sms_confirmed['error']) {
                case 1:
                case 2: $error = _e('security/data223');
                    break;
                case 3: $error = _e('security/data24');
                    break;
                case 4: $error = _e('security/data25');
                    break;
                case 5: $error = _e('security/data26');
                    break;
                case 6: $error = _e('security/data27');
                    break;
                case 7: $error = _e('security/data28');
                    break;
                case 8: $error = _e('security/data29');
                    break;
            }
            if (!$isAjaxMes)
                accaunt_message($data, $error, 'error');
            else
                return $error;
            return true;
        }
        return false;
    }
//используется
    static private function checkCode($user_id, $isAjax = false) {
        $data = new stdClass();
        $ci = get_instance();
        $code = $ci->input->post('code');
        $ci->load->library("hotp");
        $headers = get_headers("https://www.google.com.ua");
        $date = explode(":", $headers[1], 2);
        $time = "";
        if ("Date" == $date[0])
            $time = trim($date[1]);
        $window = 30;
        $key = $ci->security_model->getProtectTypeByAttrName('code_secret', $user_id);
        $htop = HOTP::generateByTime($key, $window, strtotime($time));
        $length = 6;
        $r = $htop->toHotp($length);
        if ($code == $r)
            return false;
        if (!$isAjax)
            accaunt_message($data, _e('security/data30'), 'error');
        return true;
    }


}
