<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Giftcard_model extends CI_Model {

        private $tablename = 'gift_cards';
        private $tablename_history = 'gift_cards_history';
        
      
	function __construct()
	{
		parent::__construct();
	}
        
        public function add($user_id, $nominal){
            $card_data = [
              'user_id'  => $user_id,
              'nominal' => $nominal,
               'date_buy' => date('Y-m-d H:i:s')
                
            ];
            $this->db->insert($this->tablename, $card_data);
        }
        
        public function get_statuses(){
            return [
            0=>_e('Активная'),
            1=>_e('Использована'),
            2=>_e('Подарена')];            
            
        }
        
        public function get_list($status = [], $user_id = NULL ){
            if (empty($user_id))
                $user_id = get_current_user_id();
            
            $full_result = [];
            
            if ( !empty($status) )
                $this->db->where_in('status', $status);
            $result = $this->db->where('user_id', $user_id)->get($this->tablename)->result();
            foreach( $result as &$r){
                $r->status_text = $this->get_statuses()[(int)$r->status];
                $full_result[] = $r;
            }
            
            // добавим историю
            $history = $this->db->where('from_user_id', $user_id)->get($this->tablename_history)->result();
            $result_history = [];
            foreach( $history as $r){
                $giftcard = $this->get($r->gift_card_id ,$r->to_user_id);
                $full_result[] = (object)[
                    'id' => $r->gift_card_id,
                    'nominal' => $giftcard->nominal,
                    'status' => 2, 
                    'status_text' => $this->get_statuses()[2],
                    'date_buy' => $giftcard->date_buy,
                    'from_user_id' => $r->from_user_id,
                    'to_user_id' => $r->to_user_id
                ];
            }
            return $full_result;
            
            
        }
        
        
        public function set_status($id, $status){
            //if ( $status > 0)
              //  return;
            $this->db->update($this->tablename, ['status'=>$status], ['id'=>$id]);
            if ( $status == 1){
                $this->db->update($this->tablename, ['date_activate'=>date('Y-m-d H:i:s')], ['id'=>$id]);    
            }
            
        }
        
        public function present($id, $from_user_id, $to_user_id)
        {
            
            $history_data = [
                'gift_card_id' => $id,
                'ins_date' => date('Y-m-d H:i:s'),
                'from_user_id' => $from_user_id,
                'to_user_id'=>$to_user_id
            ];
            $this->db->insert($this->tablename_history, $history_data);
            $this->db->update($this->tablename, ['user_id'=>$to_user_id], ['id'=>$id]);    
        }       
        
        public function get($id, $user_id = NULL)
        {
            if (empty($user_id))
                $user_id = get_current_user_id();
            
            return $this->db->where(['id'=>$id, 'user_id'=>$user_id])->get($this->tablename)->row();
        }           
        
        
        public function get_total_gift_summ($user_id = NULL){
            if (empty($user_id))
                $user_id = get_current_user_id();
            
            $summ = $this->db->select('sum(nominal) as s')->where(['user_id'=>$user_id, 'status'=>0])->get($this->tablename)->row('s');
            
            
            return empty($summ)?0:$summ;
        }
        
        
  }


