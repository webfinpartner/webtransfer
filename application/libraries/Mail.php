<?
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mail{

    const UNDEFINED_VAR = '_________________';

    public function __construct(){
        $this->ci = & get_instance();
        $this->ci->load->helper('smtpmail');
    }

    public function addMessages($text, $topic, $idUserRecipient){
        $this->ci->load->model("users_model", "users");
        $me = $this->ci->users->getCurrUserId();
        $this->user_sender("me_send2support", $me, ['topic' => $topic, 'text' => $text]);
        $this->user_sender("parent_send2support", $idUserRecipient, ['topic' => $topic, 'text' => $text]);
    }

    public function addMessages4Admin($text, $topic, $idUserFrom){
        $this->admin_sender("send4support2admin", $idUserFrom, ['topic' => $topic, 'text' => $text]);
    }

    public function addMessagesFromAdmin($text, $topic, array $users){
        $this->user_sender("send2supportAdmin", $users["owner"], ['topic' => $topic, 'text' => $text]);
        $this->user_sender("send2supportAdmin", $users["parent"], ['topic' => $topic, 'text' => $text]);
    }

    /**
     * @param string $email email
     * @param string $text text
     * @param string $title title
     * @param string $from (optional)
     * @return boolean
     */
    public function send($email, $text, $title, $from = NULL){

        $res = $this->send_ex($email, $text, $title, $from);
        return $res['status'];

        /* if(empty($email)) return false;
          $text = nl2br($text);

          if(null == $from)
          $from = $this->ci->base_model->settings('email');

          $res =  my_mail($title, $text, $email, $from);

          return $res;
         */
    }

    /**
     * Отправляет почту. Использует список серверов из БД.
     * @param type $email
     * @param type $text
     * @param type $title
     * @param type $from
     * @param type $disableAdminNotification
     * @return type
     */
    public function send_ex($email, $text, $title, $from = NULL, $disableAdminNotification = FALSE){
        if(empty($email))
            ['status' => false];
        $text = nl2br($text);

        $this->ci->load->model('Email_model');
        $this->ci->load->model('Messengers_log_model', 'messengers_log');
        $this->ci->load->model('System_events_model', 'system_events');

        // получим email-сервера
        $servers = $this->ci->Email_model->get_email_servers();

        if(empty($servers))
            return ['status' => false];

        $warn_servers = [];
        // проходим список серверов
        foreach($servers as $num => $server){

            if(!$server->enabled)
                continue;

            // Если нужно, то переписываем поле from
            if($from !== NULL)
                $server->from = $from;

            $res = my_mail_ex($server, $title, $text, $email);
            if($res['status']){
                $this->ci->messengers_log->add(Messengers_log_model::MESSAGE_TYPE_EMAIL, $server->service_name, $text, $email, $res['send_time'], Messengers_log_model::MESSAGE_STATUS_SUCCESS);
                // отправим уведомления об неработающих серверах
                if(!$disableAdminNotification && !empty($warn_servers))
                    foreach($warn_servers as $warn_params)
                        $this->ci->system_events->notify(System_events_model::EMAIL_ERROR_EVENT, 'WARN', $warn_params);
                return $res;
            } else {
                $this->ci->messengers_log->add(Messengers_log_model::MESSAGE_TYPE_EMAIL, $server->service_name, $text, $email, 0, Messengers_log_model::MESSAGE_STATUS_FAIL, $res['error']);
                $warn_servers[] = [$server->service_name, $res['error']];
            }
        }

        if(!$disableAdminNotification)
            $this->ci->system_events->notify(System_events_model::EMAIL_ERROR_EVENT, 'CRITICAL');


        return $res;
    }

    public function admin_sender($name, $id_user = 0, $status = [], $debit = 0){
        $template = $this->getTemplate($name);
        if(empty($template) or $template->active != 1)
            return false;

        $preg_mails   = [];
        $preg_mails[] = $this->ci->base_model->settings('email');
        $mails        = explode(',', $template->mail);
        if(!empty($mails)){
            foreach($mails as $value){
                $value        = trim($value);
                if(preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $value))
                    $preg_mails[] = $value;
            }
        }

        if(!empty($preg_mails)){
            $text = $template->text;
            if(!empty($id_user))
                $text = $this->user_parcer($text, $id_user, $status, $debit);
            return $this->send($preg_mails, $text, $template->title);
        }
    }

    public function user_sender($name, $id_user, $status = [], $debit = 0, $user_mail_src = null, $title = false){
        $template = $this->getTemplate($name);
        if(empty($template) or $template->active != 1){
            return false;
        }


        $user_mail = $user_mail_src;
        if(empty($user_mail_src)){
            $user_mail = $this->getUserMail($id_user);
        }

        $text  = $this->user_parcer($template->text, $id_user, $status, $debit);
        $title = (!empty($title)) ? $title : $template->title;
        return $this->send($user_mail, $text, $title);
    }

    public function user_parcer($text, $id_user, $status = [], $debit = 0){
        $vars = ['{{NOW}}' => date('Y-m-d H:i:s')];
        if(is_array($status)){
            foreach($status as $index => $item){
                $vars['{{'.$index.'}}'] = $item;
            }
        } else {
            $vars['{{status}}'] = $status;
        }

        $replace = [];
        $text    = strtr($text, $vars);
        preg_match_all('/{{([a-z_]+).([a-z0-9A-Z_]+)}}/', $text, $matches, PREG_SET_ORDER);

        $all_field = [];
        set_fields('users', $all_field);
        set_fields('credits', $all_field);
        set_fields('address', $all_field);

        foreach($matches as $var){
            $index           = $var[0];
            $replace[$index] = "$index";
            $table           = $var[1];
            $field           = $var[2];

            if($table == 'users'){
                if(in_array($field, $all_field[$table], true)){
                    $result = $this->ci->code->db_decode(
                        $this->ci->db->select($field)->where('id_user', $id_user)->get($table)->row()
                    );
                    if(!empty($result->{$field})){
                        $replace[$index] = $val             = $result->{$field};
                        if($field == 'place'){
                            $reg             = get_region();
                            $replace[$index] = $reg[$val];
                        }

                        if($field == 'work_place'){
                            $reg             = get_bisness();
                            $replace[$index] = $reg[$val];
                        }
                    } else {
                        //TODO log field
                        $replace[$index] = self::UNDEFINED_VAR;
                    }
                }
            } else if(($table == 'credits') and $debit > 0){
                if(in_array($field, $all_field[$table], true)){
                    $where['id_user'] = $id_user;
                    $where['id']      = $debit;

                    $result = $this->ci->db->select($field)->where($where)->get($table)->row();
                    $val    = $result->{$field};
                    switch($field){
                        case "summa":
                            $val = price_format_double($val);
                            break;
                        case "income":
                            $val = price_format_double($val);
                            break;
                        case "out_summ":
                            $val = price_format_double($val);
                            break;
                        case "out_time":
                            $val = date_formate_my($val, '«d» m Yг.');
                            break;
                        case "date_kontract":
                            $val = date_formate_my($val, '«d» m Yг.');
                            break;
                    }

                    $replace[$index] = $val;
                }
            } else if($table == "reg_address"){
                if(in_array($field, $all_field['address'], true)){
                    $replace[$index] = $this->getAddress($field, $id_user, 1);
                }
            } else if($table == "fact_address"){
                if(in_array($field, $all_field['address'], true)){
                    $replace[$index] = $this->getAddress($field, $id_user, 2);
                }
            }
        }
        return strtr($text, $replace);
    }

    public function parcer_custom_data($text, $vars_src = null){

        if(empty($vars_src))
            return $text;

        foreach($vars_src as $name => $val)
            $replace["{{{$name}}}"] = $val;

        return strtr($text, $replace);
    }

    public function getAddress($field, $id_user, $state){
        $result = $this->ci->code->db_decode(
            $this->ci->db->select($field)
                ->where(['id_user' => $id_user, 'state' => $state])
                ->get('address')->row()
        );

        if(empty($result->{$field}))
            return self::UNDEFINED_VAR;
        return $result->{$field};
    }

    public function getUserMail($id_user){
        $this->ci->load->model("users_model", "users");
        $cur_user = $this->ci->users->getCurrUserId();
        if($id_user == $cur_user){
            $res = $this->ci->users->getCurrUserData();
            return $res->email;
        }
        return $this->ci->code->decrypt(
                $this->ci->db->select('email')
                    ->where('id_user', $id_user)
                    ->get('users')->row('email')
        );
    }

    public function getTemplate($name){
        $lang = $this->ci->lang->lang();
        $res = $this->ci->db->get_where('sender', ['name' => $name, 'lang' => $lang, 'master' => $GLOBALS["WHITELABEL_ID"]])->row();
        if(empty($res))
            return $this->ci->db->get_where('sender', ['name' => $name])->row();
        else
            return $res;
    }

}

function set_fields($table, &$all_field){
    $ci                  = & get_instance();
    $fields              = $ci->db->list_fields($table);
    foreach($fields as $field)
        $all_field[$table][] = $field;
}
