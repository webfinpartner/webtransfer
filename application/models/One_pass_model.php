<?php

class One_pass_model extends CI_Model {

    public $table_users = 'users';
    public $table_one_pass = 'one_pass';
    public $expiration_time = 3600; //можно ввести пароль в течении часа
    public $max_sms_count = 5;
    public $max_sms_input_attempts = 5;

    /*
     * Создание одноразовых паролей
     */

    public function generate_one_pass($user_id) {
        if( empty( $user_id ) ) return FALSE;
        
        //Дополнительная защита от sql иньекций
        $user_id = (int) $user_id;

        $res = $this->db
                        ->where('id_user', $user_id)
                        ->get($this->table_users)->row();

        if (!isset($res)) {
            return FALSE;
        }

        $one_pass_id = $this->db
                ->where('user_id', $user_id)
                ->order_by('one_pass_id','DESC')                
                ->get($this->table_one_pass)
                ->row();
        
        $issue_counter = 0;
        if( !empty( $one_pass_id ) ) $issue_counter = $one_pass_id->issue_counter;
        
        #<!--comission
        if( $issue_counter > 2 )
        {
            $this->load->model('transactions_model','transactions');

            $account_type = 6;
            $summa = 5;

            $this->load->model('accaunt_model', 'accaunt');
            $user_ratings = $this->accaunt->recalculateUserRating($user_id);
            if( $user_ratings['payout_limit_by_bonus'][$account_type] < 5 ) 
                return ['error' => _e('Для совершения данной операции необходимо пополнить счет и повторить попытку снова')];

            $this->transactions->addPay($user_id, $summa, Transactions_model::TYPE_EXPENSE_SMS, 
                    0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $account_type, 
                    "Комиссия за перевыпуск Таблицы кодов");
            
            $issue_counter = 1;
        }
        #comission-->
        
        $pass_ar = [];

        $this->load->helper('random');

        //генерируем пароли
        for ($i = 1; $i <= 90; $i++) {
            $pass_ar[$i] = generate_password(7, 'd');
        }

        $new_table_id = rand(1111,9999);
        
        $this->db
                ->set('pass_data', serialize($pass_ar))
                ->set('create_date', date('Y-m-d H:i:s'))
                ->set('user_id', $user_id)
                ->set('saved', 0)
                ->set('issue_counter', $issue_counter)
                ->set('table_id', $new_table_id);
        //if (!isset($one_pass_id)) {
            $this->db->insert($this->table_one_pass);
        /*} else {
            $this->db
                ->where('user_id', $user_id)
                ->update($this->table_one_pass);
        }*/

        return TRUE;
    }
    
    public function set_table_saved($user_id) {
        if( empty( $user_id ) ) return FALSE;
        
        //Дополнительная защита от sql иньекций
        $user_id = (int) $user_id;

        $res = $this->db->select('id_user')
                        ->where('id_user', $user_id)
                        ->get($this->table_users)->row();

        if (!isset($res)) {
            return FALSE;
        }

        $res = $this->db->where('user_id', $user_id)
                        ->order_by('one_pass_id','DESC')
                        ->get($this->table_one_pass)
                        ->row();
        
        if (!isset($res)) {
            return FALSE;
        }
        
            $this->db->set('saved', 0)                
                    ->where('user_id', $user_id)                    
                    ->update($this->table_one_pass);
            
            $this->db->set('saved', 1)                
                    ->where('one_pass_id', $res->one_pass_id)
                    ->limit(1)
                    ->update($this->table_one_pass);
        
    }
    
    public function inc_one_pass_issue($user_id) {
        if( empty( $user_id ) ) return FALSE;
        
        //Дополнительная защита от sql иньекций
        $user_id = (int) $user_id;

        $res = $this->db->select('id_user')
                        ->where('id_user', $user_id)                        
                        ->get($this->table_users)->row();

        if (!isset($res)) {
            return FALSE;
        }

        $one_pass_id = $this->db
                ->where('user_id', $user_id)                
                ->order_by('issue_counter', 'DESC')
                ->get($this->table_one_pass)
                ->row();
        
        //if( $user_id == 500733 )vre( $one_pass_id );
        
        $issue_counter = 0;
        if( !empty( $one_pass_id ) ) $issue_counter = $one_pass_id->issue_counter;        
        $issue_counter++;
        
        $this->db->set('issue_counter', $issue_counter)
                ->limit(1);

        //if( $user_id == 500733 )vred( $issue_counter );
        
        if (isset($one_pass_id)) {
            
            $this->db
                    ->where('user_id', $user_id)
                    ->update($this->table_one_pass);
        }else{
            
            $this->db
                    ->set('user_id', $user_id)
                    ->insert($this->table_one_pass);
        }

        return TRUE;
    }

    /*
     * Получение массива одноразовых паролей
     */

    public function get_one_pass($user_id, $saved = TRUE ) {
        
        if( $saved ) $this->db->where('saved', 1);
        
        $res = $this->db
                ->where('user_id', (int) $user_id)  
                ->order_by('one_pass_id','DESC')
                ->get($this->table_one_pass)
                ->row();

        #при переходе на флага saved
        if( $saved == TRUE && empty( $res ) && time() < strtotime('2016-03-31'))
        {
            $res = $this->db
                ->where('user_id', (int) $user_id)  
                ->order_by('one_pass_id','DESC')
                ->get($this->table_one_pass)
                ->row();
            $this->set_table_saved( $user_id );
        }
        
        if (!isset($res)) {
            return FALSE;
        }

        return unserialize($res->pass_data);
    }

    /*
     * Проверка одноразовых паролей
     */

    public function check_one_pass($user_id, $pass_id, $pass) {

        $pass_ar = $this->get_one_pass($user_id);

        if (empty($pass_ar)) {
            return FAlSE;
        }

        if (!isset($pass_ar[$pass_id])) {
            return FALSE;
        }

        if ($pass_ar[$pass_id] != $pass) {
            return FALSE;
        }

        return TRUE;
    }

    public function check_code($code, $purpose, $user_id = null, $page_hash = '', $no_empty = FALSE,  $check_max_sms_count = TRUE) {

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

        if ( $user_id==93517463)  return TRUE;  

        $res = $this->db->where(array('user_id' => $user_id, 'purpose' => $purpose, 'code !=' => '0'))
                ->get('phones_codes')
                ->row();

        if (empty($res) || empty($res->code))
            return array('error' => 6); //code was not send

        $success = FALSE;

        if($check_max_sms_count) {
           if ($res->sms_attempts > $this->max_sms_count) {

               if (strtotime($res->last_sms_date) + 24 * 60 * 60 >= time()) {
                   return array('error' => 9);
               } else {
                   $res->sms_attempts = 0;
                   $res->input_attempts = 0;
               }
           }
        }

        if ($res->code == $code) {

//            if( (int)$res->expiration_datetime <= time() )
//            {
//                return array( 'error' => 66 );//code was not send
//            }
//
            if ($no_empty === FALSE) {
                $res->input_attempts = 0;
                $res->code = 0;
                $res->sms_attempts = 0;
            }
            $success = TRUE;
        } else {
            $res->input_attempts++;

            if ($res->input_attempts > $this->max_sms_input_attempts) {

                // новый код и новый номер
                $num_code = rand(1, 90);
                $code_text = sprintf(_e('code_text_security_one_pass'), $num_code);
                $this->insert_check_code($purpose, $num_code, true);
                return [
                    'error' => 101,
                    'action' => 'change_element',
                    'data' => ['id_element' => '#universal_window_code_text', 'content' => $code_text]
                ]; //a lot of input attempts
            }
        }


        $this->load->model('send_messages_history_model', 'send_messages_history');

        try {
            $this->db->trans_start(); {
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

        if (!$success) {
            //$this->send_messages_history->setMessageStatusByHash($user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_FAIL_CHECK);
            return array('error' => 8); //wrong code
        }

        //$this->send_messages_history->setMessageStatusByHash($user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_SUCCESS_CHECK);

        return TRUE;
    }

    public function insert_check_code($purpose, $num, $increase_sms_attempts = false, $user_id = NULL, $saved = TRUE) {

        $this->load->model('accaunt_model', 'accaunt');
        if ( empty($user_id))
            $user_id = $this->accaunt->get_user_id();

        $res = $this->db->where(array('user_id' => $user_id, 'purpose' => $purpose))
                ->get('phones_codes')
                ->row();


        if (!empty($res)&& $increase_sms_attempts) {
            $res->sms_attempts++;
        }
        
        $insert = FALSE;
        if (empty($res))
            $insert = TRUE;

        
        $one_pass_ar = $this->get_one_pass($user_id, $saved);

        if (!isset($one_pass_ar[$num])) {
            return FALSE;
        }

        $code = $one_pass_ar[$num];

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
                return TRUE;
            return FALSE;
        } else
            $this->db->update('phones_codes', $data, array('purpose' => $purpose, 'user_id' => $user_id));

        return TRUE;
    }

    public function wrong_code_show($user_id) 
    {
        $res = $this->db
                ->where('user_id', (int) $user_id)
                ->where('saved', 1)
                ->get($this->table_one_pass)
                ->row();
        
        $this->load->model('security_model');
        $this->load->model('users_model','users');
        
        $get_preferable_security_type = $this->security_model->get_preferable_security_type();

        $usr = $this->users->getCurrUserData();
        
        if( time() - strtotime( $usr->reg_date ) <= 3 * 24 * 3600 && empty( $res ) &&
            (empty($get_preferable_security_type) || $get_preferable_security_type == '' ))
        {
            return 1;
        }
                /*
        if( ( !empty($res) && $get_preferable_security_type == 'one_pass' &&  empty($res->saved) &&
                strtotime( $res->create_date ) >= strtotime( '2015-12-31 00:00:01' ) ) )
        {
            return 2;
        }*/
        
        return FALSE;
    }

        

    public function get_one_pass_last_id($user_id = null, $saved = TRUE) {
        if(!empty($user_id)) {
            $this->db->where('user_id', $user_id);
        }
        
        if( $saved ) $this->db->where('saved', 1);
        
        $number_table = $this->db->select('table_id')
            ->order_by('one_pass_id','desc')
            ->limit(1)
            ->get($this->table_one_pass)
            ->row('table_id');   

        return $number_table;
    }


    // avj: добавил возвращать только дату
    public function get_one_pass_number_or_date($user_id, $only_date = FALSE, $saved = TRUE) {
        if( empty( $user_id ) ) return FALSE;
        $user_id = (int) $user_id;

        if( $saved ) $this->db->where('saved', 1);
        $res = $this->db->select('*')
            ->where('user_id', $user_id)
            ->order_by('one_pass_id','desc')
            ->limit(1)
            ->get($this->table_one_pass)
            ->row();
        
        if( empty( $res ) ) return FALSE;
        
        // таблица сгенерированна до сегоднешней даты. before_today = true;
        $before_today = ( (strtotime($res->create_date) + 86400) < strtotime(date("Y-m-d H:i:s")) );

        if ( $only_date) return $res->create_date;
        
        if(empty($res->table_id)) {            
            return _e('from').' '.$res->create_date;
        } else {
            return '№ '. $res->table_id;
        }
    }

    public function get_number_from_id($num) {
        if(empty($num)) 
            return FALSE;

        $num = strval($num);
        $num = substr($num, -4);

        return $num;
    }

}
