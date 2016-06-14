<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Exchange_users_sets_model extends CI_Model {
    
    public $tableName = 'exchange_users_sets';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get($user_id){
     
        return $this->db->get_where($this->tableName, ['user_id'=>$user_id])->row();
        
    }
    
    public function get_sets($user_id){
        $result = FALSE;
        $r = $this->db->get_where($this->tableName, ['user_id'=>$user_id])->row();
        if ( !empty($r))
            $result = json_decode($r->sets,true);
        
            
        return $result;
        
    }   
    
    
    public function find_value($user_id, $need){
        $sets = $this->get_sets($user_id);

        if (empty($sets))
            return 0;
        foreach ( $sets as $diap=>$val ){
            $d = explode('-', $diap);
            $min = $d[0];
            $max = $d[1];
            if ( $need >= $min && $need <= $max ){
                return $val;
            }
        }
        
        return 0;
        
    }
    
    public function set($user_id, $sets){
     
        $data = [
            'user_id' => $user_id,
            'sets' => json_encode($sets)
        ];
        
        $r = $this->get($user_id);
        if ( empty($r))
            $this->db->insert($this->tableName, $data);
        else
            $this->db->update($this->tableName, $data, ['user_id'=>$user_id]);
    }
    
    
}
