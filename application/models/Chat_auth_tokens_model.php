<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat_auth_tokens_model extends CI_Model {
    public $tableName = "chat_auth_tokens";

    public function __construct(){
        parent::__construct();
        $this->load->model('Accaunt_model', 'accaunt');
    }
 
    public function set($user_id){
        
        if ( empty($user_id))
            return FALSE;
            
        $r = $this->get_by_user($user_id);
        if (!empty($r)){
            $start_date = $r->ins_dttm;
            if ( !empty($r->upd_dttm))$start_date = $r->upd_dttm;
            
            if ( strtotime($start_date) > strtotime("-180 days") )
                return $r->token;
        }
        
        
        do {
            $token = $this->code->code($user_id.'_'.time().'_'.rand(1,100000));
        } while ( !empty($this->get_by_token($token)) );
            
            
        //$token = $this->code->code($user_id.'_'.time().'_'.rand(1,100000));
        
        $r = $this->get_by_user($user_id);
        if (!empty($r)){
            $start_date = $r->ins_dttm;
            if ( !empty($r->upd_dttm))$start_date = $r->upd_dttm;
            
            if ( strtotime($start_date) > strtotime("-180 days") )
                return $r->token;
        }
        
        
        do {
            $token = $this->code->code($user_id.'_'.time().'_'.rand(1,100000));
        } while ( !empty($this->get_by_token($token)) );
        
        
        
        if (!empty($r)){
            $this->db->update($this->tableName, [
                 'token' => $token,
                 'upd_dttm' => date('Y-m-d H:i:s')
            ],['user_id'=>$user_id]);
        } else {
            $this->db->insert($this->tableName, [
                 'user_id' => $user_id,
                 'token' => $token,
                 'ins_dttm' => date('Y-m-d H:i:s')
            ],['user_id'=>$user_id]);
            
        }
        
        return $token;
        
    }
    
    public function get_by_token($token){
        return $this->db->where('token',$token)->get($this->tableName)->row();
    }
    
    public function get_by_user($user_id){
        return $this->db->where('user_id',$user_id)->get($this->tableName)->row();
    }
    
    
    
}
    