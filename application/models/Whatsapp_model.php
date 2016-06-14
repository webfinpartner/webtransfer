<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Whatsapp_model extends CI_Model{

    protected $secret_key,
            $from,
            $max_sms_count,
            $max_sms_input_attempts,
            $expiration_time; //in sec
    static $last_error;

    function __construct()
    {
        parent::__construct();

        $this->load->model( 'phone_model', 'phone' );
        //$this->load->model( 'Messengers_log_model', 'messengers_log' );

        $this->secret_key = "a'Ф\"N^в)$#kO;п*t"; // Ключ шифрования
        $this->from = $GLOBALS["WHITELABEL_NAME"];
        $this->max_sms_count = 3;
        $this->max_sms_input_attempts = 3;
        $this->expiration_time = 5 * 60; // 5 min

    }

    /**
     * Router for the success message sending
     *
     * @param string $hash_code - secret code
     * @param string $phone - phone number
     * @return boolean -
     */
    public function sendMessageRouter( $phone, $text )
    {
        $servers = array();
        $i = 0;
        $servers[ $i++ ] = array( 'url' => "http://46.101.130.66/",
                             'username' => 'H@zf$^CXO2Nbs',
                             'password' => '40HQRQloXP6EX',
                             'name'     => 'WhatApp015',
                             'created'  => '13.05.2015',
                             'modified' => '13.05.2015',
                             'active' => TRUE
                            );

        $servers[ $i++ ] = array( 'url' => "http://188.166.77.139/",
                             'username' => 'H@zf$^CXO2Nbs',
                             'password' => '40HQRQloXP6EX',
                             'name'     => 'WhatApp016',
                             'created'  => '13.05.2015',
                             'modified' => '13.05.2015',
                             'active' => TRUE
                            );
        $servers[ $i++ ] = array( 'url' => "http://188.166.77.149/",
                             'username' => 'H@zf$^CXO2Nbs',
                             'password' => '40HQRQloXP6EX',
                             'name'     => 'WhatApp016',
                             'created'  => '13.05.2015',
                             'modified' => '13.05.2015',
                             'active' => TRUE
                            );


        $serv_count = count( $servers );
        $full_fail = TRUE;

        //generate server numbers
        $serv_array = range(0, $serv_count - 1);
        //shuffle them
        shuffle( $serv_array );

        for( $a = 0; $a < $serv_count; $a ++ )
        {
            //get rundom server number
            $i = $serv_array[ $a ];

            if( FALSE === $servers[$i]['active'] ) continue;

            $sendMessageRes = $this->sendMessage( $phone, $text, $servers[$i] );
            if( !empty( $sendMessageRes['success'] ) && isset( $sendMessageRes['success'] ) )
            {
                $full_fail = FALSE;
                break;
            }
        }

        if( $full_fail === TRUE )
        {
            //send sms to Artem
            $this->load->model('phone_model','phone');
            //$this->phone->sendCode('WhatsApp full fail', '79118242913', 1);
        }

        return $sendMessageRes;
    }

    /**
     * Sending message using API to different servers
     *
     * @param type $phone
     * @param type $text
     * @param type $server_data
     * @return type
     */

    public function sendMessage( $phone, $text, $server_data )
    {
        if( empty( $text ) || empty( $phone ) || $phone == 0  ||
            empty( $server_data ) ||
            !isset( $server_data['url'] ) || !isset( $server_data['username'] ) || !isset( $server_data['password'] )
        )
        {
            return array( 'error' => 'Заданы не все параметры' );
        }

        $url      = $server_data['url'];
        $username = $server_data['username'];
        $password = $server_data['password'];
        // create a new cURL resource
        $myRequest = curl_init( $url );

        // do a POST request, using application/x-www-form-urlencoded type
        curl_setopt( $myRequest, CURLOPT_POST, TRUE );
        // credentials
        curl_setopt( $myRequest, CURLOPT_USERPWD, "$username:$password" );
        // returns the response instead of displaying it
        curl_setopt( $myRequest, CURLOPT_RETURNTRANSFER, 1 );

        curl_setopt( $myRequest, CURLOPT_POSTFIELDS, array(
            "phone" => $phone,
            "text" => urlencode( $text )
        ) );

        // do request, the response text is available in $response
        $response_src = curl_exec( $myRequest );
//        vred($response_src);
        // status code, for example, 200
         $info = curl_getinfo($myRequest);
        //$statusCode = curl_getinfo($myRequest, CURLINFO_HTTP_CODE);
        // close cURL resource, and free up system resources
        curl_close( $myRequest );

        if( empty( $response_src ) )
        {
            //$this->messengers_log->add(Messengers_log_model::MESSAGE_TYPE_WHATSAPP, NULL, $text, $phone, 0, Messengers_log_model::MESSAGE_STATUS_FAIL, curl_error($myRequest) );
            return array( 'error' => 'Сообщение не может быть отправлено. Пожалуйста, обратитесь в службу поддержки.' );
        }

        $response_data = json_decode( $response_src );

        if( isset( $response_data->error ) )
        {
            switch( $response_data->error )
            {
                case 210:
                case 211: $success = 'Ошибка передачи данных. Пожалуйста, обратитесь в службу поддержки.';
                    break;

                default: $error = "Ошибка WA/{$response_data->error} сервиса. Попробуйте позже.";
                    break;
            }
        } else

        if( isset( $response_data->success ) )
        {
            $success = 'Сообщение успешно отправлено!';
        }

        // пишем в лог
        /*if ( isset($success) )
                $this->messengers_log->add(Messengers_log_model::MESSAGE_TYPE_WHATSAPP, NULL, $text, $phone,@$info['total_time'],  Messengers_log_model::MESSAGE_STATUS_SUCCESS);
        else
               $this->messengers_log->add(Messengers_log_model::MESSAGE_TYPE_WHATSAPP, NULL, $text, $phone, @$info['total_time'], Messengers_log_model::MESSAGE_STATUS_FAIL, $error );

         */

        return array( 'error' => $error, 'success' => $success );
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

        if( !($phone = $this->phone->getPhone( $user_id )) )
            return array( 'error' => 3 );//the user has no phone

//        if( !$this->phone->isStatusVerified( $user_id ) && FALSE === $verification_purpose )
//            return array( 'error' => 4 );//the phone is not verified #TURN_OFF_PHONE_VEARIFICATION

        $res = $this->db->get_where( 'phones_codes', array( 'user_id' => $user_id, 'purpose' => $purpose ) )->row();

        $data = array( );//for a new row
        $sms_attempts = 0;
        if( !empty( $res ) )
        {
            if( (int)$res->expiration_datetime < time() )
            {
                $res->sms_attempts = 0;
            }

            if( $res->sms_attempts > $this->max_sms_count )
                return array( 'error' => 5 ); //a lot of attempts

            $sms_attempts = $res->sms_attempts;
        }

        $sms_attempts++;

        $code = rand( 101010, 9999999 );

        $data[ 'sms_attempts' ] = $sms_attempts;
        $data[ 'input_attempts' ] = 0;
        $data[ 'code' ] = $code;
        $data[ 'user_id' ] = $user_id;
        $data[ 'purpose' ] = $purpose;
        $data[ 'last_sms_date' ] = date( 'Y-m-d H:i:s' );
        $data[ 'expiration_datetime' ] = time() + $expiration_time;


//        $input_attempts = $this->getSmsCodeAttemps($user_id);
        $smsSentResponse = $this->sendMessageRouter( $phone, $GLOBALS["WHITELABEL_NAME"].': ' . $code, $sms_attempts );

        if( isset( $smsSentResponse[ 'error' ] ) )
            return array( 'error' => array( 'service' => $smsSentResponse[ 'error' ] ) );

        try
        {
            $this->db->trans_start();
            {
                if( empty( $res ) )
                {
                    $this->db->insert( 'phones_codes', $data );
                } else
                {
                    $this->db->where( 'id', $res->id )->limit( 1 )->update( 'phones_codes', $data );
                }
            }
            $this->db->trans_complete();
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
    public function checkSmsCode( $purpose, $code, $user_id = null )
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

        if( !($phone = $this->phone->getPhone( $user_id )) )
            return array( 'error' => 4 );//the user has no phone

        if( !$this->phone->isStatusVerified( $user_id ) )
            return array( 'error' => 5 );//the phone is not verified

        $res = $this->db->where( array( 'user_id' => $user_id, 'purpose' => $purpose, 'code !=' => 0 ) )->get( 'phones_codes' )->row();

        if( empty( $res ) || !isset( $res->id ) )
            return array( 'error' => 6 );//code was not send

        $res->input_attempts++;

        if( $res->input_attempts > $this->max_sms_input_attempts )
        {
            return array( 'error' => 7 );//a lot of input attempts
        }
        $success = FALSE;
        if( $res->code == $code )
        {
            $res->input_attempts = 0;
            $res->sms_attempts = 0;
            $res->code = 0;
            $success = TRUE;
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

}

