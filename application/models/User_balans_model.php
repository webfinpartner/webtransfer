<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class User_balans_model extends CI_Model{

    public $tableName = 'user_balans';
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model','users');
    }

    public function setUserBalansField( $user_id, $filed_name, $filed_value, $exp_add_time = 0 )
    {
        if( empty( $user_id ) || empty( $filed_name )) 
            return FALSE;
        
        if( $this->getUserBalans( $user_id ) === FALSE )
        {
            $data = array();
            $data['user_id'] = $user_id;
            $data['last_update'] = date('Y-m-d H:i:s');
            if( $exp_add_time != 0 ) $data['expiration_datetime'] = date('Y-m-d H:i:s', time() + $exp_add_time);
            $data[ $filed_name ] = $filed_value;
            
            $this->db->insert( $this->tableName, $data );
        }
        else
        {
            $data[ $filed_name ] = $filed_value;
            $data['last_update'] = date('Y-m-d H:i:s');
            if( $exp_add_time != 0 ) $data['expiration_datetime'] = date('Y-m-d H:i:s', time() + $exp_add_time);
            
            $this->db->where( 'user_id', $user_id )
                    ->update( $this->tableName, $data );
        }
        
       return 0;        
    }
    public function getUserBalans( $user_id )
    {
        if( empty( $user_id ) ) 
            return FALSE;
        
        $balans = $this->db->where('user_id', $user_id)
                                ->limit(1)
                                ->get($this->tableName)
                                ->result();
        
        if( empty( $balans ) || count( $balans ) == 0 || !isset( $balans[0] ) ) return FALSE;
        
        return $balans[0];
    }
    
    public function generateNewFsrToBot( $user_id )
    {
        if( empty( $user_id ) || !is_numeric( $user_id ) )
            return FALSE;
        
        $bot = $this->users->getUserFromUsers( $user_id, 'bot' );
        $balans = $this->getUserBalans( $user_id );
        
        if( FALSE === $bot || 1 != $bot || ( FALSE !== $balans && !isset( $balans->expiration_datetime ) ) )
         return FALSE;
                
        if( FALSE !== $balans && isset( $balans->expiration_datetime ) &&  strtotime( $balans->expiration_datetime ) >= time() )
         return $balans->FSR;
                
        $new_fsr = rand( 25, 93 );
        $exp_add_time = rand( 15, 120 ) * 60;
        $this->setUserBalansField( $user_id, 'FSR', $new_fsr, $exp_add_time );
        
        return $new_fsr;
    }
}


