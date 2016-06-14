<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Testusers_model extends CI_Model{
    
    static $last_error;

    public $table_name_groups = 'testuser_groups';
    public $table_name_user_list = 'testuser_user_list';
            
    function __construct()
    {
        parent::__construct();


    }

    public function get_users_by_purpose( $purpose_name, $active = TRUE )
    {
        if( empty( $purpose_name ) )
        {
            return array();
        }
        
//        $this->db->cache_on();        
        if( $active )
        {
            $this->db->where('active', 1);
        }
        
        $group = $this->db->select('group_id')
                            ->where( 'group_name', $purpose_name )
                            ->get( $this->table_name_groups )
                            ->row();
//        $this->db->cache_off();
        
        if( empty( $group ) )
        {
//            $this->cache->clean();
            return array();
        }
        
//        $this->db->cache_on();
        if( $active )
        {
            $this->db->where('active', 1);
        }
        
        $user_list = $this->db->select('user_id')
                            ->where( 'group_id', $group->group_id )
                            ->get( $this->table_name_user_list )
                            ->result();
//        $this->db->cache_off();
        
        if( empty( $user_list ) )
        {
//            $this->cache->clean();
            return array();
        }
        
        $result = array();
        foreach ($user_list as $row)
        {
            $result[] = $row->user_id;      
        }
        
        return $result;
    }
}

