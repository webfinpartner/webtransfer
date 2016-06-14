<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Send_notices_model extends CI_Model{

    private $history_tablename = 'send_notice_history';
    private $recipient_tablename = 'send_notice_history_recipient';
    
    const SEND_STATUS_NEW = 0;
    const SEND_STATUS_SUCCESS = 1;
    const SEND_STATUS_FAIL = 2;
    
    
    const RECIPIENT_SEND_STATUS_NEW = 0;
    const RECIPIENT_SEND_STATUS_SUCCESS = 1;
    const RECIPIENT_SEND_STATUS_FAIL = 2;
    
    
    function __construct()
    {
        parent::__construct();
        
    }    
    
    
    /**
     * Получить всю историю
     * @return boolean
     */
            
    public function get_all(){
        
        try {
       //     $this->db->cache_on();
            $res = $this->db->get( $this->history_tablename )->result();
       //     $this->db->cache_off();
        } catch ( Exception $e){
            return FALSE;
        }
        
        if ( empty($res) ) return FALSE;
        
        $statuses = $this->get_statuses();
        foreach ($res as &$r)
            $r->status_text = $statuses[$r->status];
        
        return $res;
        
            
    }
    
    /**
     * Получить запись
     * @return boolean
     */
            
    public function get($id){
        $this->load->model('Accaunt_model', 'account');

        
        
        try {
      //      $this->db->cache_on();
            $res = $this->db->where('id',$id)->get( $this->history_tablename )->row();
       //     $this->db->cache_off();
        } catch ( Exception $e){
            return FALSE;
        }
        
        if ( empty($res) ) return FALSE;
        $recipient_statuses = $this->get_recipient_statuses();
        
        // добавим получаетей
            try {
                $recipients = $this->db->where('send_notice_history_id', $res->id)->get( $this->recipient_tablename )->result();
                if ( !empty($recipients) ){
                    foreach ( $recipients as  &$recipient){                    
                       $recipient->status_text = @$recipient_statuses[$recipient->status];
                       $decoded_user = $this->account->getUserFields($recipient->user_id, array('id_user','sername','name','patronymic','phone','email') ); 
                       if ( !empty($decoded_user))
                          $recipient =  (object) array_merge((array) $decoded_user, (array) $recipient);  
                    }
                    $res->recipients = $recipients;
                }
            } catch(Exception $e){
                return FALSE;
            }
        
        return $res;
        
            
    }    
    
    
    /**
     * Сохраняет запись
     * @param type $status
     * @param type $text
     * @param object $recipients
     * @return boolean
     */        
    public function save($text, $recipients){
        
        if ( empty($text) || empty($recipients) )
            return FALSE;
        
        $sent_recipient_list = $this->send($text,$recipients, $send_status );
        if ( $sent_recipient_list === FALSE )
            return FALSE;
        
        try {
            $data['send_datetime'] = date('Y-m-d H:i:s');
            $data['status'] = $send_status;
            $data['recipient_count'] = count($recipients);
            $data['send_text'] = $text;
                    
            $this->db->insert( $this->history_tablename, $data );
            $insert_id = $this->db->insert_id();
            foreach( $sent_recipient_list as $recipient){
                $rdata['send_notice_history_id'] = $insert_id;
                $rdata['user_id'] = $recipient->user_id;
                $rdata['security'] = $recipient->security;
                $rdata['status'] = $recipient->status;
                $this->db->insert( $this->recipient_tablename, $rdata );
            }
     //       $this->db->cache_delete(); 
            
        } catch ( Exception $e){
            return FALSE;
        }
        return $insert_id;
        
            
    }       
    
    
    /**
     * Отправка сообщений
     * @param type $text
     * @param array $recipients
     */
    private function send($text, $recipients, &$ret_status)
    {
        $ret_status = self::SEND_STATUS_FAIL;
        $errors = 0;
        
        $result = array();
        $this->load->model('Security_model');
        $this->load->model('Accaunt_model', 'account');
        
        if ( empty($recipients))
            return FALSE;
        
        foreach ( $recipients as $recipient_id )
            {
                $status = self::RECIPIENT_SEND_STATUS_FAIL;
                $security = $this->Security_model->getProtection(0,$recipient_id)->value;            
                if ( empty($security) )
                    $security = 'sms';
                
                 $decoded_user = $this->account->getUserFields($recipient_id, array('phone','email') ); 
                 if ( !empty($decoded_user))
                    switch( $security ){
                        case 'sms':
                             $this->load->model('phone_model', 'phone');
                             $send_resp = $this->phone->sendCode($text, $decoded_user->phone, 0 );
                            break;
                        case 'email':
                             $this->load->model('email_model', 'email');
                             $send_resp = $this->email->sendMessage($recipient_id, $decoded_user->email, $text, 'Служба поддержки', TRUE);
                            break;
                        case 'viber':
                             $this->load->model('viber_model', 'viber');
                             $send_resp = $this->viber->sendMessageRouter($decoded_user->phone, $text);
                        case 'whatsapp':
                             $this->load->model('whatsapp_model', 'whatsapp');
                             $send_resp = $this->whatsapp->sendMessageRouter($decoded_user->phone, $text);
                            break;                   
                        default:
                            break;
                    }
                
                if (isset($send_resp['success'])){
                    $status = self::RECIPIENT_SEND_STATUS_SUCCESS;
                    if ($errors == 0)
                         $ret_status = self::SEND_STATUS_SUCCESS;
                } else {
                    $errors++;
                }
                
                
                $result[] = (object)array(
                    'user_id' => $recipient_id,
                    'security' => $security,
                    'status' => $status,
                    'error_text' => $send_resp['error']
                );
        }
        return $result;
    }
    

    
    
    /**
     * Получить статусы для получателей
     */
    public function get_recipient_statuses(){
        return array(
            self::RECIPIENT_SEND_STATUS_NEW => 'Новый',
            self::RECIPIENT_SEND_STATUS_SUCCESS => 'Отправлен',
            self::RECIPIENT_SEND_STATUS_FAIL => 'Не отправлен'
        );
    }

    
   /**
     * Получить статусы для получателей
     */
    public function get_statuses(){
        return array(
            self::SEND_STATUS_NEW => 'Новый',
            self::SEND_STATUS_SUCCESS => 'Успешно отправлено',
            self::SEND_STATUS_FAIL => 'Есть ошибки отправки'
        );
    }
    
}