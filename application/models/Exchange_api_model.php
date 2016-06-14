<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exchange_api_model extends CI_Model {
    public $logTableName = "exchange_api_log";
    
    const STATUS_NEW = 0; 
    const STATUS_ERROR = 1; 
    const STATUS_SUCCESS = 2; 
    const STATUS_IN_PROGRESS = 3; 

    
    public function __construct() {
        parent::__construct();
        $this->load->model("users_model", 'users');
        //$this->lang->load('transactions_lang', 'transactions_lang');
    }    
    
    
    public function get_status_text($status){
        switch( $status){
            case self::STATUS_NEW:
                return _e('Не обработан');
            break;
            case self::STATUS_ERROR:
                return _e('Ошибка');
            break;
            case self::STATUS_IN_PROGRESS:
                return _e('В процессе');
            break;            
            case self::STATUS_SUCCESS:
                return _e('Успешно');
            break;
        }
        return _e('неизвестно');
    }
    
    public function add($method, $to_user_id, $from_method, $to_method, $remove_summ, $add_summ, $data){
        $this->db->insert($this->logTableName,[
            'method'=>$method,
            'to_user_id' => $to_user_id,
            'from_method' => $from_method,
            'to_method' => $to_method,
            'remove_summ' => $remove_summ,
            'add_summ' => $add_summ,
            'data'=>json_encode($data),
            'date'=>date('Y-m-d H:i:s')
        ]);
        
        return $this->db->insert_id();
          
    }
    
    
    public function setStatus($id, $status, $note = ''){
        $this->db->update($this->logTableName, ['status'=>$status, 'note'=>$note], ['id'=>$id]);
    }
    
    public function getHistory($user_id, $offset = 0, $limit=99999999999999999){
        $total = $this->db->where('to_user_id', $user_id )->count_all_results($this->logTableName);
        $h =  $this->db->where('to_user_id', $user_id )->order_by('id','desc')->limit($limit, $offset)->get($this->logTableName)->result();
        if ( !empty($h))
            foreach( $h as &$item){
                $item->statusText = $this->get_status_text($item->status);
            }
       return ['h'=>$h, 'total'=>$total];
        
    }
    
    public function getStat($user_id ){
        $to_m =  $this->db->select('to_method, sum(add_summ) as summa')->where(['to_user_id'=> $user_id, 'status'=>2] )->group_by('to_method')->get($this->logTableName)->result();
        $from_m =  $this->db->select('from_method, sum(add_summ) as summa')->where(['to_user_id'=> $user_id, 'status'=>2 ] )->group_by('from_method')->get($this->logTableName)->result();
        
        $result = [
            'to_method'=>[],
            'from_method'=>[]
        ];
        
        foreach( $to_m as $r)
            if ( !empty( $r->to_method))
                $result['to_method'][$r->to_method] = $r->summa;
        foreach( $from_m as $r)
            if ( !empty( $r->from_method))
                $result['from_method'][$r->from_method] = $r->summa;        
       return $result;
        
    }    
 
    public function getLimitOut($user_id){
        $in =  $this->db->select('sum(add_summ) as sm')
                ->where('to_user_id', $user_id )
                ->where('status', 2)
                ->where('date <', date('Y-m-d H:i:s', strtotime('- 3 days')))
                ->where('to_method','payment_account')
                ->get($this->logTableName)->row('sm');

        $out =  $this->db->select('sum(add_summ) as sm')
                ->where('to_user_id', $user_id )
                ->where('status', 2)
                //->where('date <', date('Y-m-d H:i:s', strtotime('- 3 days')))
                ->where('from_method','payment_account')
                ->get($this->logTableName)->row('sm');        
        if (empty($in)) $in = 0;
        if (empty($out)) $out = 0;
       return ($in - $out)*1.0;
        
    }    
    
}
    
