<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class User_arbitration_scores_model extends CI_Model{
    
    
    public $tableName = "user_arbitration_scores";
    const STATUS_RECEIVED = 1;
    const STATUS_REMOVED = 3;

//    private $_res = array();

    public function __construct() {
            parent::__construct();
    }
    

    public function get_scores_summ_in( $user_id, $formatSumm = FALSE ){
        $sm = $this->db->select('sum(scores) as sm')->where(['user_id'=>$user_id,'status'=>self::STATUS_RECEIVED])->get($this->tableName)->row('sm');
        $sm = empty($sm)?0:$sm;
        if ( $formatSumm ){
            $sm = max(0,$sm);
            $sm = $sm - $sm%50;
        }
        return $sm;
    }
    public function get_scores_summ_out( $user_id, $formatSumm = FALSE ){
        $sm = $this->db->select('sum(scores) as sm')->where(['user_id'=>$user_id,'status'=>self::STATUS_REMOVED])->get($this->tableName)->row('sm');
        $sm = empty($sm)?0:$sm;
        if ( $formatSumm ){
            $sm = max(0,$sm);
            $sm = $sm - $sm%50;
        }
        return $sm;
    }

    
    public function get_scores_summ( $user_id, $formatSumm = FALSE ){
        $sm_in = $this->db->select('sum(scores) as sm')->where(['user_id'=>$user_id,'status'=>self::STATUS_RECEIVED])->get($this->tableName)->row('sm');
        $sm_out = $this->db->select('sum(scores) as sm')->where(['user_id'=>$user_id,'status'=>self::STATUS_REMOVED])->get($this->tableName)->row('sm');
        $sm_in = empty($sm_in)?0:$sm_in;
        $sm_out = empty($sm_out)?0:$sm_out;
        
        $sm = $sm_in-$sm_out;
        
        if ( $formatSumm ){
            $sm = max(0,$sm);
            $sm = $sm - $sm%50;
        }
        
        
        return $sm;
    }
    
    public function calc_scores($invest){
        $diff = strtotime($invest->date_active)  - strtotime($invest->date);
        $diff_hours = round($diff/60/60,2);
        
        $scores = round($invest/50 * $diff_hours,1);
        
        $vip_users = config_item('arbitration_scores_vip_users');
        foreach ( $vip_users as $k => $users )
            if (in_array($invest->id_user, $users))
                $scores *= $k;        
        
        return $scores;
    }
    
    public function add_scores_by_invest($invest_id){
        
        $invest = $this->db->where('id', $invest_id)->get('credits')->row();
        if ( empty($invest))
            return FALSE;
        
        
        $user_id = $invest->id_user;
        $calc_scores = $this->calc_scores($invest);
        $this->db->insert($this->tableName, ['user_id'=>$user_id, 'scores'=>$calc_scores, 'invest_id'=>$invest->id, 'status'=>self::STATUS_RECEIVED]);
        
        return TRUE;
        
    }
    
    
     public function remove_scores($user_id, $scores){
       $this->db->insert($this->tableName, ['user_id'=>$user_id, 'scores'=>$scores, 'status'=>self::STATUS_REMOVED]);
     }
    
    
}