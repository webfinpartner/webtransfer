<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Email_model extends CI_Model{

    protected $secret_key,
            $from,
            $max_sms_count,
            $max_sms_input_attempts,
            $expiration_time; //in sec
    static $last_error;

    function __construct()
    {
        parent::__construct();

        $this->max_sms_count = 20;
        $this->max_sms_input_attempts = 20;
        $this->expiration_time = 3 * 60; // 5 min
        //$this->load->model( 'Messengers_log_model', 'messengers_log' );
    }



    /**
     * Sending message
     *
     * @param type $email
     * @param type $text
     * @return type
     */

    public function sendMessage( $user_id, $email, $text, $title = '',  $ignoreSecuType = FALSE  )
    {
        if( empty( $text ) || empty( $email )  )
            return array( 'error' => _e('Заданы не все параметры') );

//        if ( $ignoreSecuType == FALSE )
//        {
//            require_once APPPATH.'controllers/user/Security.php';
//            $getProtectType = Security::getProtectType( $user_id );
//            if( $getProtectType != 'email' )
//                    return array( 'error' => _e('У вас другой тип верификации. Перезагрузите страницу и попробуйте снова.') );
//        }

       $this->load->library('mail');
       $this->load->model( 'phone_model', 'phone' );
       $phone = $this->phone->getPhone( $user_id );
       $text_after = $this->phone->get_after_text($phone)."\n\n Best wishes,\n".$GLOBALS["WHITELABEL_NAME"]." Team";


       //$email = 'avj83@list.ru';
       $text = $text."\n".$text_after;

       if ( empty($title))
            $title = _e('new_236');
       $result = $this->mail->send_ex($email, $text, $title);
       if ( $result['status'] )
       {
                return array( 'success' => _e('Сообщение успешно отправлено!') );
       } else {
                return array( 'error' => _e('Сообщение не может быть отправлено. Пожалуйста, обратитесь в службу поддержки.') );
       }







    }

    /**
     *
     * @param type $user_id
     * @param type $phone
     * @param type $smsCode
     * @return int
     */
    private function setSmsCode( $user_id, $smsCode )
    {
        if( empty( $user_id ) || $user_id == 0 ||
                empty( $smsCode ) || $smsCode == 0
        )
            return -1;

        //проверка количества отправленных смс
        $user_phone = (array)$this->db->where( array( 'user_id' => $user_id ) )
                        ->get( 'phones' )->row();
        if( !$user_phone )
            return 1;
        $user_phone[ 'sms_attempts' ] = intval( $user_phone[ 'sms_attempts' ] );

        if( strtotime( $user_phone[ 'last_date_sms' ] ) + 24 * 60 * 60 < time() )
            $user_phone[ 'sms_attempts' ] = 0;

        if( $user_phone[ 'sms_attempts' ] >= $this->max_sms_count )
        {
            if( strtotime( $user_phone[ 'last_date_sms' ] ) + 24 * 60 * 60 >= time() )
                return 2; //sms count has been excessed
            else
                $user_phone[ 'sms_attempts' ] = 0;
        }

        $user_phone[ 'sms_attempts' ]++;
        //запись данных
//-----OLD
        $data_old[ 'hash_code' ] = $smsCode;

        //записали новый код в OLD базу
        $this->db->where( 'id_user', $user_id )->update( 'users', $data_old );

//-----NEW

        $user_phone[ 'sms_code' ] = $smsCode;
        $user_phone[ 'last_date_sms' ] = date( 'Y-m-d H:i:s' );

        try
        {
            $this->db->where( 'user_id', $user_id )->update( 'phones', $user_phone );
        } catch( Exception $exp )
        {
            return -1;
        }


        return 0; //OK
    }

    private function getSmsAttemps( $user_id )
    {
        if( empty( $user_id ) || $user_id == 0 )
            return -1;

        $user_phone = (array)$this->db->where( array( 'user_id' => $user_id ) )
                        ->get( 'phones' )->row();

        if( !$user_phone || empty( $user_phone ) || !isset( $user_phone[ 'sms_attempts' ] ) )
            return -1;

        return $user_phone[ 'sms_attempts' ];
    }

    private function getSmsCodeAttemps( $user_id )
    {
        if( empty( $user_id ) || $user_id == 0 )
            return -1;

        $user_phone = (array)$this->db->where( array( 'user_id' => $user_id ) )
                        ->get( 'phones_codes' )->row();

        if( !$user_phone || empty( $user_phone ) || !isset( $user_phone[ 'sms_attempts' ] ) )
            return -1;

        return $user_phone[ 'sms_attempts' ];
    }

    private function getSmsCode( $user_id )
    {
        if( empty( $user_id ) || $user_id == 0 )
            return 0;

        return $this->db
                        ->where( 'user_id', $user_id )
                        ->get( 'phones' )->row( 'sms_code' );
    }

    /**
     * It may be used for any purpose to validate something.
     * NOTICE! Not for none-phone-verification purposes
     *
     * @param type $purpose
     * @param type $user_id
     * @param type $expiration_datetime
     */
    public function sendSmsCode( $purpose, $user_id = null, $expiration_time = null, $verification_purpose = FALSE )
    {
        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty( $expiration_time ) )
            $expiration_time = $this->expiration_time;

        if( empty( $user_id ) )
        {
            if( !isset( $this->accaunt ) )
                $this->load->model( 'accaunt_model', 'accaunt' );
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty( $user_id ) )
            return array( 'error' => 2 );//there is no code

        $email = $this->accaunt->get_user_field($user_id, 'email');
        if( empty( $email ) )
            return array( 'error' => 3 );//there is bad email


        $res = $this->db->get_where( 'phones_codes', array( 'user_id' => $user_id, 'purpose' => $purpose ) )->row();

        $data = array( );//for a new row
        $sms_attempts = 0;

        if( !empty( $res ) )
        {

            if( !empty($res->code) )
            {
                if( strtotime($res->last_sms_date) + 60 >= time() )
                {
                    return array( 'error' => 67 );//code was not send
                }

                if( $res->sms_attempts > $this->max_sms_count )
                    return array( 'error' => 5 ); //a lot of attempts


                if( $res->expiration_datetime <= time() )
                {
                    $res->input_attempts = 0;
                }else{
                    return array( 'error' => 32, 'time' => $res->expiration_datetime - time() ); //a lot of attempts
                }
            }


            $sms_attempts = $res->sms_attempts;
        }


        $sms_attempts++;

        $code = rand( 101010, 9999999 );
        //$code = 555;

        $data[ 'sms_attempts' ] = $sms_attempts;
        $data[ 'input_attempts' ] = 0;
        $data[ 'code' ] = $code;
        $data[ 'user_id' ] = $user_id;
        $data[ 'purpose' ] = $purpose;
        $data[ 'last_sms_date' ] = date( 'Y-m-d H:i:s' );
        $data[ 'expiration_datetime' ] = time() + $expiration_time;

//        $input_attempts = $this->getSmsCodeAttemps($user_id);
        $ignoreSecType = FALSE;

        if( $purpose == 'save_security_settings' || $purpose == 'card_security_action' || $purpose == 'secure_api_check' || $purpose == 'loan_api_check' || $purpose == 'set_security_type')
        {
            $ignoreSecType = TRUE;
        }


        $smsSentResponse = $this->sendMessage( $user_id, $email, $GLOBALS["WHITELABEL_NAME"].': ' . $code, '', $ignoreSecType );

        if( isset( $smsSentResponse[ 'error' ] ) )
            return array( 'error' => array( 'service' => $smsSentResponse[ 'error' ] ) );

        try
        {
            {
                if( empty( $res ) )
                {
                    $this->db->insert( 'phones_codes', $data );
                } else
                {
                    unset( $data['user_id'] );
                    unset( $data['purpose'] );
                    $this->db->where( 'id', $res->id )
                              ->limit( 1 )
                              ->update( 'phones_codes', $data );

                }
            }

        } catch( Exception $e )
        {
            return array( 'error' => 6 );
        }



        return array(
            'success' => 1, //OK
            'action' => 'show-form'
        );
    }

    /**
     * Chacking if sended code is correct
     *
     * It may be used for any purpose to validate something.
     * NOTICE! Not for none-phone-verification purposes
     *
     * @param type $purpose
     * @param type $user_id
     */
    public function checkSmsCode( $purpose, $code, $user_id = null, $no_empty = FALSE )
    {


        if( empty( $purpose ) )
            return array( 'error' => 1 );//there is not purpose

        if( empty( $purpose ) || empty( $code ) || !is_numeric( $code ) )
            return array( 'error' => 2 );//there is no code

        if( empty( $user_id ) )
        {
            if( !isset( $this->accaunt ) )
                $this->load->model( 'accaunt_model', 'accaunt' );
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty( $user_id ) )
            return array( 'error' => 3 );//there is no code


        $email = $this->accaunt->get_user_field($user_id, 'email');
        if( empty( $email ) )
            return array( 'error' => 4 );//there is bad email

        $res = $this->db->where( array( 'user_id' => $user_id, 'purpose' => $purpose, 'code !=' => 0 ) )->get( 'phones_codes' )->row();

        if( empty( $res ) || !isset( $res->id ) )
            return array( 'error' => 6 );//code was not send




        $success = FALSE;
        if( $res->code == $code )
        {
            if( (int)$res->expiration_datetime <= time() )
            {
                return array( 'error' => 66 );//code was not send
            }

            if( $no_empty === FALSE  )
            {
                $res->input_attempts = 0;
                $res->code = 0;
                $res->sms_attempts = 0;
            }
            $success = TRUE;
        }else
        {
            $res->input_attempts++;

            if( $res->sms_attempts > $this->max_sms_count ){
                return array( 'error' => 9 );//a lot of input attempts
            }
            if( $res->input_attempts > $this->max_sms_input_attempts ){
                return array( 'error' => 7 );//a lot of input attempts
            }

        }

        try
        {
            $this->db->trans_start();
            {
                $this->db->where( 'id', $res->id )
                        ->update( 'phones_codes', array( 'input_attempts' => $res->input_attempts,
                            'sms_attempts' => $res->sms_attempts,
                            'code' => $res->code,
                ) );
            }
            $this->db->trans_complete();
        } catch( Exception $exc )
        {
            self::$last_error = $exc->getTraceAsString();
        }

        if( !$success )
            return array( 'error' => 8 );//wrong code

        return array(
            'success' => 1, //OK;
        );

        /*
          try
          {
          $this->db_>trans_start();
          {
          $this->db->where('id', $res->id)
          ->limit(1)
          ->delete('phones_codes');
          }
          $this->db_>trans_complete();
          }catch( Exception $e )
          {
          //??
          }
         */
    }

    /**
     * Получает почтовые сервера
     */
    public function get_email_servers(){

        //TODO: добавить кэш после добавления раздела в админку
        //$this->db->cache_delete();
    //    $this->db->cache_on();
        $res = $this->db->where('master', $GLOBALS["WHITELABEL_ID"])->order_by('id','asc')->get('mail_servers')->result();
        if ( empty($res))
            $res = $this->db->where('master', 1)->order_by('id','asc')->get('mail_servers')->result();    
     //   $this->db->cache_off();

        if ( empty($res) ) return FALSE;

        return $res;

    }

    public function isUserEmailVerified( $user_id = null )
    {
        if( empty( $user_id ) )
        {
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty( $user_id ) )
        {
            return FALSE;
        }
        $this->load->model('users_model','users');
        $user_data =  $this->users->getUserData($user_id);

        if( empty( $user_data ) )
        {
            return FALSE;
        }

        if( empty($user_data->email) || !empty( $user_data->account_verification ) ) //если NULL - значит подтвержден
        {
            return FALSE;
        }

        return TRUE;
    }
}

