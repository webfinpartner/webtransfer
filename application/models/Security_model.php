<?php

class Security_model extends CI_Model {

    const ATTRIBUTE_IS_INACTIVE = '0'; //enum
    const ATTRIBUTE_IS_ACTIVE = '1'; //enum
    const ATTRIBUTE_HIDDEN = '1';
    const ATTRIBUTE_NOT_HIDDEN = '0';

    var $attributes_names_table,
            $attributes_values_table;

    public function __construct() {
        parent::__construct();
        $this->attributes_names_table = 'security_attributes_names';
        $this->attributes_values_table = 'security_attributes_values';
        $this->createAttrs(0);
    }

    public function check_recaptcha( $recaptcha_response = 0 ){
        $this->load->model('accaunt_model','accaunt');
        //if( $this->accaunt->get_user_id() !== 500733 ) return TRUE;
        #only for P2P
        if ( isset( $_SERVER['HTTP_REFERER'] ) &&
             !empty( $_SERVER['HTTP_REFERER'] ) &&
             strpos( $_SERVER['HTTP_REFERER'], 'currency_exchange') === FALSE) return TRUE;

        if( empty( $recaptcha_response ) ) return FALSE;

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(  'secret' => config_item('publickeyCapchaPrivate') ,
                        'response' => $recaptcha_response);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        $resp = json_decode($result);
        if( empty( $result ) || empty( $resp ) ) return FALSE;

        if( isset( $resp->success ) && $resp->success == TRUE ) return TRUE;

        return FALSE;
    }

    public function createAttrs($language_id = 0, $user_id = null) {

        if (empty($user_id)) {
            if (!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }

        if (empty($user_id))
            return; //there is no user_id

        $fields = $this->db
                ->limit(10)
                ->get_where($this->attributes_names_table)
                ->result();

        $values = $this->db
                ->order_by('id', 'DESC')
                ->limit(10)
                ->get_where($this->attributes_values_table, array('user_id' => $user_id))
                ->result();

        $val = array();
        foreach ($values as $row)
            $val[$row->attribute_id] = $row;

        $flds = [];
        foreach ($fields as $row) {
            if (isset($flds[$row->id]))
                continue;
            $flds[$row->id] = $row;
        }

        foreach ($flds as $row) {

            $attribute_value = '';
            if (isset($val[$row->id]))
                $attribute_value = $val[$row->id]->value;

            //if ($row->id == 1 && $attribute_value == 'sms')
            //    $attribute_value = 'email';

            if ($row->id == 4)
                $attribute_value = 'sms';


            if (isset($val[$row->id]) && ( $attribute_value == $val[$row->id]->value ))
                continue;

            $data = array(
                'value' => (string) $attribute_value
            );

            if (isset($val[$row->id]))
                $this->db->update($this->attributes_values_table, $data, ['user_id' => $user_id, 'attribute_id' => $row->id]);
            else {
                $data['user_id'] = $user_id;
                $data['attribute_id'] = $row->id;

                $this->db->insert($this->attributes_values_table, $data);
            }
        }
    }

    public function getProtectionTypesAndValues($language_id = 0, $user_id = null) {

        if (empty($user_id)) {
            $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }

        if (empty($user_id))
            return null; //there is no user_id

        $values = $this->db->select('n.human_name, n.machine_name, v.value')
                ->from($this->attributes_values_table . ' AS v')
                ->join($this->attributes_names_table . ' AS n', 'n.id = v.attribute_id', 'right')
                ->where('v.user_id', $user_id)
                ->where('n.is_active', self::ATTRIBUTE_IS_ACTIVE)
                ->where('n.hidden', self::ATTRIBUTE_NOT_HIDDEN)
                ->get()
                ->result();

        return $values;
    }

    public function getProtection($language_id = 0, $user_id = null) {

        if (empty($user_id)) {
            $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }

        if (empty($user_id))
            return null; //there is no user_id

        $values = $this->db->select('n.human_name, n.machine_name, v.value')
                ->from($this->attributes_values_table . ' AS v')
                ->join($this->attributes_names_table . ' AS n', 'n.id = v.attribute_id', 'right')
                ->where('v.user_id', $user_id)
                ->where('n.is_active', self::ATTRIBUTE_IS_ACTIVE)
                ->where('n.hidden', self::ATTRIBUTE_NOT_HIDDEN)
                ->where('n.machine_name', 'withdrawal_standart_credit')
                ->get()
                ->row();

        return $values;
    }

    public function setProtectionTypeValueByAttrName($attr_name, $attr_value, $user_id = null) {
        if (empty($attr_name))
            return null; //there is no attr_name

        if (empty($user_id)) {
            if (!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if (empty($user_id))
            return null; //there is no user_id

        $protectionTypeId = $this->getProtectionTypeIdByAttrName($attr_name, true, $user_id);

        if (empty($protectionTypeId))
            return false;

        if (in_array($protectionTypeId, array(2, 4)) && $attr_value != 'sms')
            $attr_value != 'sms';

        if ($protectionTypeId == 1 && $attr_value == 'one_pass') {
            $this->load->model('one_pass_model');
            $this->one_pass_model->inc_one_pass_issue($user_id);
            $this->one_pass_model->set_table_saved($user_id);
        }

        $this->db->where('attribute_id', $protectionTypeId)
                ->where('user_id', $user_id)
                ->set('value', $attr_value)
                ->update($this->attributes_values_table);

        return true;
    }

    /**
     * Use ONLY for listing user page
     *
     * @param type $attr_name
     * @param type $user_id
     * @return null
     */
    public function getProtectionTypeIdByAttrName($attr_name, $show_hidden = false, $user_id = null) {
        if (empty($attr_name))
            return null; //there is no attr_name

        if (empty($user_id)) {
            if (!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if (empty($user_id))
            return null; //there is no user_id
















//        echo 'hello';
        if (0) {
            $this->db->select('n.id')
                    ->from($this->attributes_values_table . ' AS v')
                    ->join($this->attributes_names_table . ' AS n', 'n.id = v.attribute_id', 'inner')
                    ->where('n.machine_name', $attr_name)
                    ->where('n.is_active', self::ATTRIBUTE_IS_ACTIVE);
            if (TRUE !== $show_hidden) {
                $this->db->where('n.hidden', self::ATTRIBUTE_NOT_HIDDEN);
            }

            $values = $this->db->select('n.id')
                    ->where('v.user_id', $user_id)
//                        ->where( "( v.user_id = $user_id  OR v.user_id IS NULL )" )
                    ->get()
                    ->row('id');
        }

        $this->db->select('id')
                ->where('machine_name', $attr_name)
                ->where('is_active', self::ATTRIBUTE_IS_ACTIVE);

        if (TRUE !== $show_hidden) {
            $this->db->where('hidden', self::ATTRIBUTE_NOT_HIDDEN);
        }

        $values = $this->db->get($this->attributes_names_table)->row('id');


        return $values;
    }

    public function getProtectTypeByAttrName($attr_name, $user_id = null) {
        if (empty($attr_name))
            return null; //there is no attr_name

        if (empty($user_id)) {
            $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if (empty($user_id))
            return null; //there is no user_id


        if (0) {
            $val = $this->db->select('v.value')
                    ->from($this->attributes_values_table . ' AS v')
                    ->join($this->attributes_names_table . ' AS n', 'n.id = v.attribute_id', 'inner')
                    ->where('n.machine_name', $attr_name)
                    ->where('n.is_active', self::ATTRIBUTE_IS_ACTIVE)
                    ->where('v.user_id', $user_id)
                    //                        ->where( "( v.user_id = $user_id  OR v.user_id IS NULL )" )
                    ->get()
                    ->row('value');
        }
        $id = $this->db->select('id')
                        ->where('machine_name', $attr_name)
                        ->where('is_active', self::ATTRIBUTE_IS_ACTIVE)
                        ->get($this->attributes_names_table)->row('id');



        $val = $this->db->select('value')
                ->from($this->attributes_values_table)
                ->where('attribute_id', $id)
                ->where('user_id', $user_id)
                ->get()
                ->row('value');

//        var_dump( $values );
//        vred( $this->db->last_query() );
        if (empty($val))
            return "";
        return $val;
    }

    function get_allowed_security_type($purpose) {
        switch ($purpose) {

            case 'set_phone': return ['code', 'one_pass'];
            case 'phone_verification': return [/*'whatsapp', */'sms', 'voice', 'select'];
            case 'save_security_settings': return ['code', 'one_pass', /*'whatsapp'*/];
            case 'save_p2p_payment_data': return ['code', 'one_pass', /*'whatsapp'*/];
            case 'withdrawal_standart_credit': return ['code', 'one_pass', /*'whatsapp'*/];
            case 'create_get_p2p_orders': return ['code', 'one_pass', /*'whatsapp'*/];

            case 'merchant_pay': return ['code', 'one_pass', /*'whatsapp'*/];

            case 'card_import_list': return ['code', 'email', 'one_pass', /*'whatsapp'*/];
            case 'card_security_action': return ['code', 'one_pass', /*'whatsapp'*/];

            case 'change_security_type':

                return ['select',/* 'whatsapp', */'sms', 'voice'];  //типы, которые разрешены для смены безопасности
            case 'set_security_type': return ['code', 'one_pass']; //разрешенные типы в Настройках
            case 'default':
            case 'secure_api_check':
            case 'loan_api_check':

                return ['code', 'email', 'one_pass', /*'whatsapp'*/];

            //смена партнера - account/change_partner
            case 'change_partner':
                return ['code', 'one_pass', /*'whatsapp'*/];

            default: return FALSE;
        }
    }

    function get_preferable_security_type($user_id = NULL) {
        return $this->getProtectTypeByAttrName('withdrawal_standart_credit', $user_id);
    }

    public function choose_security_type($purpose_in, $security_type_in, $user_id = NULL) {

//        $security_type = $this->getProtectTypeByAttrName($purpose_in);
//        if( $security_type === FALSE ) return FALSE;
//        if( empty( $security_type ) ) return NULL;
//
//        if( empty( $security_type_in ) ) return $security_type;
//        if( is_string($security_type) && !is_array($security_type) )
//        {
//            $security_type = [$security_type];
//        }

        $preferable_security_type = $this->get_preferable_security_type($user_id);
        $allowed_security_type = $this->get_allowed_security_type($purpose_in);

        if (empty($security_type_in))
            $security_type_in = $preferable_security_type;

        if (!in_array($security_type_in, $allowed_security_type)) {
            return $allowed_security_type[0];
        }


        return $security_type_in;
    }

    function get_veriants_data($type_variants) {
        if (empty($type_variants))
            return FALSE;
        $res = [];
        $all_types = [];
        //$all_types['whatsapp'] = ['js_fnc' => '', 'type' => 'whatsapp', 'image' => 'logo-watsupo.png', 'title' => 'WhatsApp'];
        $all_types['sms'] = ['js_fnc' => '', 'type' => 'sms', 'image' => 'logo-sms.png', 'title' => ''];
        $all_types['email'] = ['js_fnc' => '', 'type' => 'email', 'image' => 'logo-mail.png', 'title' => ''];
        $all_types['code'] = ['js_fnc' => '', 'type' => 'code', 'image' => 'logo-wt.png', 'title' => ''];
        $all_types['voice'] = ['js_fnc' => '', 'type' => 'voice', 'image' => 'logo-voice.png', 'title' => _e('Голосовая верификация')];

        foreach ($type_variants as $v) {
            $res[] = $all_types[$v];
        }

        return $res;
    }

    function get_wt_image() {
        $this->load->model('wt_code_model','wt_code_model');

        $res = list($src, $key) = $this->wt_code_model->get_wt_image_src();

        if( !empty( $res ) && $this->setProtectionTypeValueByAttrName("code_secret_temp", $key)) {
            return "<img src='$src'/>";
        }
        return _e('Wrong data image. Refresh the page and try again.');
    }

    /*
     * копирует данные из одного purpose в другое в таблице phones_codes
     * и добавляем еще 10 минут для симуляции первого этапа проверки
     */

    public function copy_protection_type($user_id, $from, $to) {
        $data = $this->db
                ->where('user_id', $user_id)
                ->where('purpose', $from)
                ->get('phones_codes')
                ->row_array();

        if (empty($data)) {
            return false;
        }

        unset($data['id']);
        $data['purpose'] = $to;
        $data['expiration_datetime'] +=600;

        $old = $this->db
                ->where('user_id', $user_id)
                ->where('purpose', $to)
                ->get('phones_codes')
                ->row();

        if (empty($old)) {
            $this->db->insert('phones_codes', $data);
        } else {
            $this->db->where('id', $old->id)->limit(1)->update('phones_codes', $data);
        }

        return true;
    }

    /*
     * Возвращает html список новых одноразовых паролей
     */

    public function generate_new_one_pass($user_id = NULL, $logo = 'default', $logo_caption = 'www.webtransfer.com') {
        $this->load->model('one_pass_model');

        $this->load->model('accaunt_model', 'accaunt');
        if ( empty($user_id))
            $user_id = $this->accaunt->get_user_id();

        $res = $this->one_pass_model->generate_one_pass($user_id);
        if( $res !== TRUE ){
            return $res;
        }
        $saved = FALSE;
        $table_num = $this->one_pass->get_one_pass_last_id($user_id, $saved);
        $table_num = $this->one_pass->get_number_from_id($table_num);

        return $this->load->view('user/accaunt/security_module/show_pass', [
                    'pass_data' => $this->one_pass_model->get_one_pass($user_id, $saved),
                    'user_id' => $user_id,
                    'number_table' => $table_num,
                    'logo' => $logo,
                    'logo_caption' => $logo_caption
                        ], TRUE);
    }

    public function get_security_form($purpose_in, $security_type_in ) {

        $security_type = $this->choose_security_type($purpose_in, $security_type_in);

        if ($security_type === FALSE)
            return FALSE;

        $data = [];
        $logo_type_image = 0;
        $type_variants = [];
        $title = '';
        $top_text = null;
        $hide_button = FALSE;

        $choose_type = FALSE;
        $choose_type_style = '';
        $send_button_name = _e('ЗАПРОСИТЬ КОД');
        $lang_select = FALSE;
        $code_text = _e('Код подтверждения');

        $template = 'user/accaunt/security_module/universal_window';

        $this->load->model('accaunt_model', 'accaunt');
        $user_id = $this->accaunt->get_user_id();

        #additinal security
        $captcha_enabled = FALSE;
        if ( strpos( $_SERVER['HTTP_REFERER'], 'currency_exchange') !== FALSE) $captcha_enabled = TRUE;

        $content_body = NULL;
        $content_header = NULL;
        $content_footer = NULL;

        switch ($security_type) {
            /*
            case 'whatsapp1':#remove
                $this->load->model('whatsapp_new_model', 'whatsapp');
                $title_type = 'WhatsApp';
                $logo_type_image = 'logo-watsupo.png';

                #hidding the select methods
                $choose_type = TRUE;
                //$choose_type_style = 'display:none';
                $type_variants = ['sms', 'voice'];

                $client_phone = $this->phone->getPhone($user_id);
                if (!$client_phone) {
                    return FALSE; //Выводит ошибку, неправильный метод аутентификации
                }

                //Проверяем было ли ранее отправлено "ок" на номер whatsapp
                $response = $this->whatsapp->check_phone($client_phone);
                $res = json_decode($response, TRUE);

                //Если телефон верифицирован то показываем окно запроса кода
                if ($res['status'] == '010204') {
                    break;
                }

                $response = $this->whatsapp->get_phone($client_phone);
                $res = json_decode($response, TRUE);

                if (isset($res['error'])) {
                    return FALSE; //Выводит ошибку, неправильный метод аутентификации
                }
                $code_text = sprintf(_e('whatsapp_text_ok_message'), $res['phone']);
                $content_body = $this->load->view('user/accaunt/security_module/whatsapp_body', ['code_text' => $code_text], true);

                //$this->whatsapp->insert_check_code($purpose_in);

                break;*/
            case 'sms':
                $logo_type_image = 'logo-sms.png';

                #hidding the select methods
                $choose_type = TRUE;
                $choose_type_style = 'display:none';
                $type_variants = ['voice'//, 'whatsapp'
                    ];

                if ($purpose_in == 'change_security_type' && $this->phone->is_voice_verification_enabled($user_id, $purpose_in))
                    $choose_type_style = '';

                $title_type = 'SMS';
                break;
            case 'one_pass':
                $logo_type_image = 'logo-wt.png';
                $title_type = _e('security_one_pass');
                $hide_button = TRUE;
                $num_code = rand(1, 90);

                $this->load->model('one_pass_model','one_pass');
                $table_num = $this->one_pass->get_one_pass_number_or_date($user_id, FALSE, TRUE);
                
                if( empty( $table_num ) && $purpose_in != 'set_security_type' ) 
                    return ['error' => _e('Ваша таблица кодов устарела. Перейдите в раздел <a href="/account/security" target="_blank">Безопасность</a> и перевыпустите таблицу кодов.') ];
                
                $code_text = sprintf(_e('code_text_security_one_pass_table_num_or_date'), $num_code, $table_num);

                //Если ранее не был установлен этот метод то показать таблицу кодов

                $preferable_security_type = $this->get_preferable_security_type();
                $wrong_code_show = $this->one_pass->wrong_code_show($user_id);
                if ($purpose_in == 'set_security_type' ) {
                    $this->load->helper('random');
                    $filename = 'one_pass_' . $user_id . '_' . generate_password(20, 'ud') . '.pdf';
                    $html_one_pass = $this->generate_new_one_pass();

                    if (is_array($html_one_pass) && isset($html_one_pass['error'])) {
                        return $html_one_pass;
                    }

                    $top_text = _e('top_text_new_one_pass');
                    //
                    if ($wrong_code_show == 2)
                        $top_text = _e('top_text_new_one_pass_wrong');

                    $top_text .= '<strong>1.</strong> <a class="btn btn-primary" target="_blank" href="/security/get_code_chart/' . $filename . '">' . _e('save_one_pass') . '</a>';

                    $this->load->library('code');

                    $this->code->createPdf($html_one_pass, 'one_pass', $filename, false);
                    $table_num = $this->one_pass->get_one_pass_last_id($user_id, FALSE);
                    $table_num = $this->one_pass->get_number_from_id($table_num);
                    $it_table_num = true;
                    $code_text = sprintf(_e('code_text_new_one_pass_table_num'), $num_code, $table_num);
                }

                $this->load->model('one_pass_model');
                $saved = TRUE;
                if( $purpose_in == 'set_security_type'  ) $saved = FALSE;
                $this->one_pass_model->insert_check_code($purpose_in, $num_code, NULL, NULL, $saved);

                break;
            case 'voice':
                $logo_type_image = 'logo-voice.png';
                $type_variants = ['sms'];
                $choose_type = TRUE;
                $top_text = '<p>' . _e('Воспользуйтесь бесплатной голосовой верификацией. После нажатия кнопки, вам позвонит оператор-робот и произнесет код голосом.') . '</p>';
                $send_button_name = _e('ПОЗВОНИТЕ МНЕ');
                $lang_select = TRUE;
                $title_type = 'Voice';
                break;
            case 'code':
                $logo_type_image = 'logo-wt.png';
                if (empty($this->getProtectTypeByAttrName("code_secret")) || $purpose_in == 'set_security_type')
                    $top_text = '<p>' . _e('Запустите Webtransfer Auth на телефоне и нажмите кнопку Отсканировать код.') . '</p>' .
                            '<center>' . $this->get_wt_image() . '</center>';
                else
                    $top_text = _e('Введите код, который сгенерирован программой Webtransfer Auth');

                $hide_button = TRUE;
                $title_type = 'Webtransfer Auth';
                break;
            case 'email':
                $logo_type_image = 'logo-mail.png';
                $title_type = 'Email';
                break;
            case 'select':
                $allowed_types = $this->get_allowed_security_type($purpose_in);
                $show_types = [];
                foreach ($allowed_types as $val) {
                    $type_name = '';
                    switch ($val) {
                        case 'select':
                            continue 2;
                        case 'whatsapp1':#remove
                            $type_name = _e('WhatsApp');
                            break;
                        case 'sms':
                            $type_name = _e('СМС');
                            break;
                        case 'voice':
                            $type_name = _e('Голосовая');
                            break;
                        case 'email':
                            $type_name = _e('Email');
                            break;
                    }
                    $show_types[] = ['type' => $val, 'name' => $type_name];
                }
                $data['show_types'] = $show_types;
                $logo_type_image = 'logo-wt.png';
                $title = _e('Выбор способа доставки кода');
                $template = 'user/accaunt/security_module/universal_window_select_method';
                $top_text = '<p>' . _e('Для включения безопасности вам необходимо подтвердить владение этим аккаунтом.') . '</p>' .
                        '<p>' . _e('Для этого вам будет выслан код выбранным способом.') . '</p>';
                break;
            default:
                $logo_type_image = 'none.png';
        }

        $data['title'] = $title;
        $data['top_text'] = $top_text;
        $data['hide_button'] = $hide_button;
        $data['send_button_name'] = $send_button_name;
        $data['lang_select'] = $lang_select;


        $data['logo_type_image'] = $logo_type_image;
        $data['choose_type'] = $choose_type;
        $data['choose_type_style'] = $choose_type_style;
        $data['type_variants'] = $this->get_veriants_data($type_variants);

        $data['purpose'] = $purpose_in;
        $data['title_type'] = $title_type;

        if($it_table_num === true)
            $data['it_table_num'] = true;

        $data['code_text'] = $code_text;

        $data['content_body'] = $content_body;
        $data['content_header'] = $content_header;
        $data['content_footer'] = $content_footer;
        $data['captcha_enabled'] = $captcha_enabled;

        $form = $this->load->view($template, $data, TRUE);

        if (empty($form))
            return FALSE;

        return [$form, $security_type];
    }

    public function get_security_form_data($user_id, $purpose_in, $security_type_in, $params = [] ) {

        $security_type = $this->choose_security_type($purpose_in, $security_type_in, $user_id);

        if ($security_type === FALSE)
            return FALSE;

        $data = [];
//        $choose_type = FALSE;

        $this->load->model('accaunt_model', 'accaunt');

        #additinal security
//        $captcha_enabled = FALSE;

        switch ($security_type) {

            case 'sms':

                #hidding the select methods
                $type_variants = ['voice'//, 'whatsapp'
                    ];

                if ($purpose_in == 'change_security_type' && $this->phone->is_voice_verification_enabled($user_id, $purpose_in))
                    $choose_type_style = '';


                break;
            case 'one_pass':
//                $logo_type_image = 'logo-wt.png';
//                $title_type = _e('security_one_pass');
//                $hide_button = TRUE;
                $num_code = rand(1, 90);

                $this->load->model('one_pass_model','one_pass');
                $table_num = $this->one_pass->get_one_pass_number_or_date($user_id, FALSE, TRUE);

                $code_text = sprintf(_e('code_text_security_one_pass_table_num_or_date'), $num_code, $table_num);

                //Если ранее не был установлен этот метод то показать таблицу кодов

//                $preferable_security_type = $this->get_preferable_security_type($user_id);
                $wrong_code_show = $this->one_pass->wrong_code_show($user_id);
                if ($purpose_in == 'set_security_type' ) {
                    $this->load->helper('random');
                    $filename = 'one_pass_' . $user_id . '_' . generate_password(20, 'ud') . '.pdf';

                    $logo = 'default';
                    $logo_caption = 'www.webtransfer.com';
                    if ( isset($params['logo']) ) $logo = $params['logo'];
                    if ( isset($params['logo_caption']) ) $logo = $params['logo_caption'];                        
                    
                    $html_one_pass = $this->generate_new_one_pass($user_id, $logo, $logo_caption);

                    if (is_array($html_one_pass) && isset($html_one_pass['error'])) {
                        return $html_one_pass;
                    }

                    if ($wrong_code_show == 2)
                        $top_text = _e('top_text_new_one_pass_wrong');


                    $this->load->library('code');

                    $fullpath = $this->code->createPdf($html_one_pass, 'one_pass', $filename, false);
                    $table_num = $this->one_pass->get_one_pass_last_id($user_id, FALSE);
                    $table_num = $this->one_pass->get_number_from_id($table_num);
                    $security_code_date = $this->one_pass_model->get_one_pass_number_or_date($user_id, TRUE);

                    $data['pdf_filename'] = 'webtransfer_codetable_'.$security_code_date.'.pdf';
                    $data['pdf_data'] = base64_encode( file_get_contents($fullpath) );
                    //$data['fullpath'] = $fullpath;

                    $data['code_num'] = $num_code;
                    $data['code_table_num'] = $table_num;
                    $data['code_table_date'] = $security_code_date;
                    $code_text = sprintf(_e('code_text_new_one_pass_table_num'), $num_code, $table_num);
                }

                $this->load->model('one_pass_model');
                $saved = TRUE;
                if( $purpose_in == 'set_security_type'  ) $saved = FALSE;
                $this->one_pass_model->insert_check_code($purpose_in, $num_code, NULL, $user_id, $saved);

                break;
            case 'voice':
                break;
            case 'code':
                if (empty($this->getProtectTypeByAttrName("code_secret", $user_id)) || $purpose_in == 'set_security_type'){
                        $this->load->model('wt_code_model','wt_code_model');
                        $res = list($src, $key) = $this->wt_code_model->get_wt_image_src($user_id);
                        if( !empty( $res ) && $this->setProtectionTypeValueByAttrName("code_secret_temp", $key, $user_id)) {
                            $data['qr_code_url'] = $src;
                        }
                }
                else
                    $data['message'] = _e('Введите код, который сгенерирован программой Webtransfer Auth');

                break;
            case 'email':
                break;
            case 'select':
                break;
            default:
        }

        $data['type_variants'] = $this->get_veriants_data($type_variants);
        $data['purpose'] = $purpose_in;

        return $data;
    }


    private function _install() {
//        CREATE TABLE IF NOT EXISTS `security_attributes_names` (
//            `id` int(11) NOT NULL,
//            `language_id` int(11) NOT NULL,
//            `human_name` mediumtext NOT NULL,
//            `machine_name` varchar(255) NOT NULL,
//            `is_active` enum('0','1') NOT NULL,
//            PRIMARY KEY(`id`)
//          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//        CREATE TABLE IF NOT EXISTS `security_attributes_values` (
//            `id` int(11) NOT NULL,
//            `user_id` int(11) NOT NULL,
//            `attribute_id` int(11) NOT NULL,
//            `value` mediumtext NOT NULL,
//            PRIMARY KEY(`id`)
//          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    }

}
