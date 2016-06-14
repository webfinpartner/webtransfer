<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Send_messages_history_model extends CI_Model{

    public $table_name = 'send_messages_history';
    static $last_error;

    const MESSAGE_TYPE_EMAIL    = 101;
    const MESSAGE_TYPE_SMS      = 102;
    const MESSAGE_TYPE_WT_AUTH  = 103;
    
    const MESSAGE_TYPE_WHATSAPP = 201;
    const MESSAGE_TYPE_VIBER    = 202;
    
    const MESSAGE_STATUS_SUCCESS = 101;
    const MESSAGE_STATUS_SUCCESS_CHECK = 102;
    const MESSAGE_STATUS_PENDING = 201;
    const MESSAGE_STATUS_FAIL    = 301;
    const MESSAGE_STATUS_FAIL_CHECK    = 302;
    const MESSAGE_STATUS_ANOTHER = 401;
    const MESSAGE_STATUS_ANOTHER_CHECK = 402;

    function __construct()
    {
        parent::__construct();
        
    }

    
    public function addMessage( $user_id, $type_id, $recipient_account, $message_text, 
                                $page_link_hash, $page_hash, $delivery_status, 
                                $service_name = '', $service_balance = 0, $cost = 0, $message_id_in_service = null )
    {
        if( empty( $type_id ) || empty( $recipient_account ) 
                //empty( $user_id ) ||
                //|| empty( $message_text ) 
                //|| empty( $page_link_hash ) 
                //|| empty( $page_hash ) 
                || empty( $delivery_status ) )
        {
            return FALSE;
        }
        $this->load->library('code');
        
        $data = array();
        
        $user_ip = $this->input->ip_address();
        
        $data['user_id']                = $user_id;
        $data['user_ip']                = $user_ip;
        $data['added_datetime']         = date( 'Y-m-d H:i:s' );
        
        $data['type_id']                = $type_id;
        $data['service_name']           = $service_name;
        $data['service_balance']        = $service_balance;
        $data['cost']                   = $cost;
        
        $data['recipient']              = $this->code->code( $recipient_account );
        $data['message']                = $message_text;
        $data['page_link_hash']         = $page_link_hash;
        $data['page_hash']              = $page_hash;
        
        if( empty($message_id_in_service ) ) $message_id_in_service = '';
        $data['message_id_in_service']  = $message_id_in_service;
        
        $data['delivery_status']        = $delivery_status;
        
        try
        {
            $this->db->insert( $this->table_name, $data );
        }catch( Exception $exp )
        {
            self::$last_error = $exp->getTraceAsString();
            return FALSE;
        }
//        echo $this->db->last_query();
        return TRUE; //OK
    }
    
    public function setMessageStatusByServiceMessageId( $user_id, $message_id_in_service, $delivery_status )
    {
        if( empty( $user_id ) 
                //|| empty( $message_text ) 
                //|| empty( $page_link_hash ) 
                //|| empty( $page_hash ) 
                || empty( $delivery_status ) )
        {
            return FALSE;
        }
        
        
        $condition = array();
        $condition['user_id']                = $user_id;
        $condition['message_id_in_service']  = $message_id_in_service;                
        
        $data = array();        
        $data['delivery_status']        = $delivery_status;
        $data['confirm_datetime']         = date( 'Y-m-d H:i:s' );
        
        try
        {
            $this->db->update( $this->table_name, $data, $condition );
        }catch( Exception $exp )
        {
            self::$last_error = $exp->getTraceAsString();
            return FALSE;
        }

        return TRUE; //OK
    }
    public function setMessageStatusByHash( $user_id, $page_hash, $delivery_status )
    {
        if( empty( $user_id )                
                || empty( $page_hash ) 
                || empty( $delivery_status ) )
        {
            return FALSE;
        }
        
        
        $condition = array();
        $condition['user_id']                = $user_id;
        $condition['page_hash']              = substr($page_hash, 0, 7);
        
        
        $data = array();        
        $data['delivery_status']        = $delivery_status;
        $data['confirm_datetime']         = date( 'Y-m-d H:i:s' );
        
        try
        {
            $this->db->update( $this->table_name, $data, $condition );
        }catch( Exception $exp )
        {
            self::$last_error = $exp->getTraceAsString();
            return FALSE;
        }
        
        return TRUE; //OK
    }

    

}

