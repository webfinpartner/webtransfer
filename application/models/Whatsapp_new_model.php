<?php

class Whatsapp_new_model extends CI_Model {

    private $api_login = 'wtest6';
    private $api_pass = '8u2PIFpI$y=Iu';
    private $api_url = 'http://dopmull.com/apis/ver_2/';
    private $post_fields;
    private $response;
    public $expiration_time = 3600; //можно ввести пароль в течении часа
    public $max_sms_count = 4;
    public $max_sms_input_attempts = 4;

    public function get_phone($client_phone) {
        $this->post_fields['client_phone'] = $client_phone;
        $this->post_fields['service_name'] = 'whatsapp';
        return $this->call_server('get_phone');
    }

    public function check_phone($client_phone) {
        $this->post_fields['client_phone'] = $client_phone;
        $this->post_fields['service_name'] = 'whatsapp';
        return $this->call_server('check_phone');
    }

    public function send_message($client_phone, $code) {
        $this->post_fields['messages[1][service_name]'] = 'whatsapp';
        $this->post_fields['messages[1][receiver_account]'] = $client_phone;
        $this->post_fields['messages[1][message_body]'] = $code;
        $this->post_fields['messages[1][message_type]'] = 'text';

        return $this->call_server('send_messages');
    }

    /*
     * Return answer of server
     * @return boolean if FALSE, cant get server data
     */

    public function call_server($api_method) {
        
return json_encode(['status' => '010010', 'error' => 'API server not answer ' . curl_errno($ch)]);
        
        $this->response['src'] = '';

        $this->post_fields['api_login'] = $this->api_login;
        $this->post_fields['api_pass'] = $this->api_pass;

        $ch = curl_init($this->api_url . $api_method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (isset($this->post_fields)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post_fields);
        }

        $this->response['src'] = curl_exec($ch);
        //$this->response['info'] = curl_getinfo($ch); //пока не используем

        if (curl_errno($ch)) {
            curl_close($ch);
            return json_encode(['status' => '010010', 'error' => 'API server not answer ' . curl_errno($ch)]);
        }

        curl_close($ch);

        return $this->response['src'];
    }

    public function sendCode($purpose, $increase_sms_attempts = true, $user_id = NULL) {

        $this->load->model('accaunt_model', 'accaunt');
        if (empty($user_id))
            $user_id = $this->accaunt->get_user_id();

        $res = $this->db->where(array('user_id' => $user_id, 'purpose' => $purpose))
                ->get('phones_codes')
                ->row();


        if (!empty($res) && $increase_sms_attempts) {
            $res->sms_attempts++;
        }

        $insert = FALSE;
        if (empty($res))
            $insert = TRUE;

        if (!empty($res)) {
            if ($res->sms_attempts > $this->max_sms_count) {
                if (strtotime($res->last_sms_date) + 24 * 60 * 60 >= time()) {
                    return ['error' => 9];
                } else {
                    $res->sms_attempts = 0;
                    $res->input_attempts = 0;
                }
            }
        }

        $this->load->helper('random');
        $code = generate_password(7, 'd');

        $this->load->model('phone_model', 'phone');
        $client_phone = $this->phone->getPhone($user_id);
        if (!$client_phone) {
            return ['error' => 3]; //the user has no phone;
        }
        $this->send_message($client_phone, $code);

        #log message
        $message_id_in_service = null;
        $service_name = '';
        $service_balance = 0;
        $cost = 0;            
        $this->load->model('send_messages_history_model','send_messages_history');
        $type_id = Send_messages_history_model::MESSAGE_TYPE_WHATSAPP;
        $delivery_status = Send_messages_history_model::MESSAGE_STATUS_SUCCESS;
        $page_link_hash = '';
        $page_hash = '';
                
        $this->send_messages_history->addMessage( $user_id, $type_id, $client_phone, $code,
                                                $page_link_hash, $page_hash, $delivery_status,
                                                $service_name, $service_balance,  $cost, $message_id_in_service );
        #/log message
        
        $data = [];
        $data['sms_attempts'] = 0;
        if (!empty($res)) {
            $data['sms_attempts'] = $res->sms_attempts;
        }
        $data['input_attempts'] = 0;
        $data['code'] = $code;
        $data['last_sms_date'] = date('Y-m-d H:i:s');
        $data['expiration_datetime'] = time() + $this->expiration_time;

        if ($insert === TRUE) {
            $data['user_id'] = $user_id;
            $data['purpose'] = $purpose;

            $this->db->insert('phones_codes', $data);
            if ($this->db->insert_id() !== FALSE)
                return ['success' => 'ok'];
            return ['error' => 6];
        } else {
            $this->db->update('phones_codes', $data, array('purpose' => $purpose, 'user_id' => $user_id));
        }

        return ['success' => 'ok'];
    }

    //Проверяем пришло ли сообщеник "ок" на наш номер
    public function check_ok_message($purpose, $user_id = null) {

        if (empty($purpose))
            return array('error' => 1); //there is not purpose

        if (empty($user_id)) {
            if (!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if (empty($user_id))
            return array('error' => 3); //there is no code

        $this->load->model('phone_model', 'phone');

        $client_phone = $this->phone->getPhone($user_id);
        if (!$client_phone) {
            return array('error' => 3); //there is no code
        }

        $response = json_decode($this->whatsapp->check_phone($client_phone), TRUE);

        if (isset($response['success'])) {
            $response['action'] = 'change_security_type';
            $response['data'] = ['purpose' => $purpose, 'security_type' => 'whatsapp'];
        }

        return $response;
    }

    public function check_code($code, $purpose, $user_id = null, $page_hash = '', $no_empty = FALSE) {

        if (empty($purpose))
            return array('error' => 1); //there is not purpose

        if (empty($code))
            return array('error' => 2); //there is no code

        if (empty($user_id)) {
            if (!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if (empty($user_id))
            return array('error' => 3); //there is no code


        $res = $this->db->where(array('user_id' => $user_id, 'purpose' => $purpose, 'code !=' => '0'))
                ->get('phones_codes')
                ->row();

        if (empty($res) || empty($res->code))
            return array('error' => 6); //code was not send

        $res->input_attempts++;

        if ($res->input_attempts > $this->max_sms_input_attempts) {
            if (strtotime($res->last_sms_date) + 24 * 60 * 60 >= time()) {
                return array('error' => 7);
            } else {
                $res->sms_attempts = 0;
                $res->input_attempts = 0;
            }
        }

        if ($res->sms_attempts > $this->max_sms_count) {

            if (strtotime($res->last_sms_date) + 24 * 60 * 60 >= time()) {
                return array('error' => 9);
            } else {
                $res->sms_attempts = 0;
                $res->input_attempts = 0;
            }
        }

        if ($res->code == $code) {
            $response = ['success' => _e('Телефон подтвержден')];
        } else {
            $response = ['error' => 8]; //wrong code
        }

        //если пришло сообщение!!!
        if (isset($response['success'])) {

            if ($no_empty === FALSE) {
                $res->input_attempts = 0;
                $res->code = 0;
                $res->sms_attempts = 0;
            }
        } else {
            $response = ['error' => 8];
        }

        $this->load->model('send_messages_history_model', 'send_messages_history');

        try {
            $this->db->trans_start();
            {
                $this->db->where('id', $res->id)
                        ->limit(1)
                        ->update('phones_codes', array('input_attempts' => $res->input_attempts,
                            'sms_attempts' => $res->sms_attempts,
                            'code' => $res->code));
            }
            $this->db->trans_complete();
        } catch (Exception $exc) {
            self::$last_error = $exc->getTraceAsString();
        }

        return $response;
    }

}
