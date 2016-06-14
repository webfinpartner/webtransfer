<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Wt_code_model extends CI_Model{

    protected $secret_key,
            $from,
            $max_sms_count,
            $max_sms_input_attempts,
            $expiration_time; //in sec
    static $last_error;


    public function __construct()
    {
        parent::__construct();
                                        
//        $this->max_sms_count = 20;
        $this->max_sms_input_attempts = 20;
        $this->expiration_time = 3 * 60; // 5 min
        //$this->load->model( 'Messengers_log_model', 'messengers_log' );
    }
    
    //первичная проверка кода, производится еще в окне
    public function check_code_first( $code, $purpose, $temp = FALSE )
    {
        return $this->check_code( $code, $purpose, NULL, $temp );
    }
    
    //$temp - проверять код, используя временный секрет (используется при смене типа безопасности на Auth)
    public function check_code( $code, $purpose, $user_id = null, $temp = FALSE )
    {
        // return true;
        if( empty( $code ) || empty( $purpose ) )
            return FALSE;

        if( empty( $user_id ) )
        {
            $this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }

        if ( ($user_id == 93517463 || $user_id == 500733 || $user_id == 500757 ) && strpos(base_url(),'wtest') !== false ) return TRUE;
        
        
        
        $this->load->library("hotp");
        $headers=get_headers("https://www.google.com.ua");
        $date = explode(":", $headers[1],2);
        $time = "";
        if("Date" == $date[0]) $time = trim($date[1]);
        $window = 30;
        
        $attr_name = 'code_secret';
        if( $temp === TRUE ) $attr_name = 'code_secret_temp';
        
        $key = $this->security_model->getProtectTypeByAttrName($attr_name, $user_id);
        if( empty( $key ) ) return FALSE;

        $htop = HOTP::generateByTime($key, $window, strtotime($time));
        $length = 6;
        $r = $htop->toHotp($length);

       if($code != $r){          
           return FALSE;
       }
        

        $res = $this->db->where(array( 'user_id' => $user_id, 'purpose' => $purpose  ))
                ->get('phones_codes')
                ->row();

        $insert = FALSE;
        if( empty($res) ) $insert = TRUE;
        else
            if( !isset( $res->id )) return array( 'error' => 6 );//code was not send
            
        
        $data = [];
//        $data['sms_attempts'] = $res->sms_attempts;
        $data['input_attempts'] = 0;
        $data['code'] = $code;                
        $data['last_sms_date'] = (string)date('Y-m-d H:i:s');
        $data['expiration_datetime'] = time() + $this->expiration_time;
        
        
        if( $insert === TRUE )
        {
//            $data['sms_attempts'] = 0;
            $data['user_id'] = $user_id;
            $data['purpose'] = $purpose;
            
            $this->db->insert('phones_codes',$data);

            if( $this->db->insert_id() !== FALSE ) return TRUE;
            return FALSE;
        }else
            $this->db->update('phones_codes',$data, array( 'purpose' => $purpose, 'user_id' => $user_id ));
        
        
        return TRUE;
    }
    
    //вторичная проверка кода, при проверке формы
    public function check_code_next( $code, $purpose, $user_id = null, $page_hash = '' )
    {   
        // return true; 
        
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty( $purpose ) || empty( $code ) || !is_numeric($code))
            return array( 'error' => 2 );//there is no code

        if( empty($user_id) )
        {
            if( !isset( $this->accaunt ) ) $this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        
        if( empty($user_id) )
            return array( 'error' => 3 );//there is no code

        //if ( ($user_id == 93517463 || $user_id == 500733 || $user_id == 500757 ) && strpos(base_url(),'wtest') !== false ) return TRUE;
        
        $res = $this->db->where(array( 'user_id' => $user_id, 'purpose' => $purpose, 'code !=' => 0  ))
                ->get('phones_codes')
                ->row();

        
        if( empty($res) || empty( $res->code ))
            return array( 'error' => 6 );//code was not send

        
        $success = FALSE;
        
        if( $res->code == $code )
        {
            
//            if( (int)$res->expiration_datetime <= time() )
//            {
//                return array( 'error' => 66 );//code was not send
//            }
//
//            if( $no_empty === FALSE  )
//            {
                $res->input_attempts = 0;
                $res->code = 0;
                $res->sms_attempts = 0;
//            }
            $success = TRUE;
        }else
        {            
            $res->input_attempts++;
            
            if( $res->sms_attempts > $this->max_sms_count ){
                return array( 'error' => 9 );//a lot of input attempts
            }
//            if( $res->input_attempts > $this->max_sms_input_attempts ){
//                return array( 'error' => 7 );//a lot of input attempts
//            }
            
        }


        $this->load->model('send_messages_history_model','send_messages_history');

        try
        {
            $this->db->trans_start();
            {
                $this->db->where('id', $res->id)
                        ->limit(1)
                        ->update( 'phones_codes', 
                            array( 'input_attempts' => $res->input_attempts,
                                    'sms_attempts' => $res->sms_attempts,
                                    'code' => $res->code ));
            }
            $this->db->trans_complete();
        }catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
        }

        if( !$success ){
            $this->send_messages_history->setMessageStatusByHash( $user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_FAIL_CHECK );
            return array( 'error' => 8 );//wrong code
        }

        $this->send_messages_history->setMessageStatusByHash( $user_id, $page_hash, Send_messages_history_model::MESSAGE_STATUS_SUCCESS_CHECK );

        return TRUE;

    }
    
    //получить картинку QR-кода для синхронизации приложения
    public function get_wt_image_src($user_id = NULL) {  
        $this->load->library("Base32");
        $this->load->model('accaunt_model','accaunt');

        $key = sha1(getSecretOtpauth() . time() . microtime());

        $cd = new Base32();
        $secret = $cd->base32_encode($key);
        
        if ( empty($user_id))
            $user_id = $this->accaunt->get_user_id();
        
        if( empty( $key ) || empty( $secret ) || empty( $user_id ) ) return FALSE;
        
        $img_src = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=200x200&chld=M|0&cht=qr&chl=wbtotpauth://totp/$user_id@webtransfer.com%3Fsecret%3D$secret";
        
        return [$img_src, $key];
    }
        
    //активировать последнее секретное слово для кодирования
    public function save_active_secret( $user_id )
    {
        if( empty( $user_id ) ) return FALSE;
        
        $this->load->model('security_model');
        $key = $this->security_model->getProtectTypeByAttrName('code_secret_temp', $user_id);
        
        if( empty( $key ) ) return FALSE;
        
        $res = $this->security_model->setProtectionTypeValueByAttrName("code_secret", $key, $user_id);
        $this->security_model->setProtectionTypeValueByAttrName("code_secret_temp", '', $user_id);
        if( empty( $res ) ) return FALSE;
        
        return TRUE;
    }
    
}

