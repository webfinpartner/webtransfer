<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Card_transactions_model extends CI_model
{
	public $tableName = 'card_transactions';


	static $last_error;
        const CARD_TRANS_LOAN_RETURN = 1;
        const CARD_TRANS_LOAN_RETURN_PERC = 2;
        const CARD_TRANS_SEND_DIRECT = 3;
        const CARD_TRANS_TAKE_LOAN = 4;
        const CARD_PAY_WT_ACCOUNT = 5;
        const ADD_BANK_AND_UNLOAD = 6;
        const CARD_TRANS_SEND_DIRECT_WTAPI = 8;
        const CARD_TRANS_TAKE_INVEST = 9;
        const CARD_TRANS_SEND_INTERNAL_DIRECT_WTAPI = 10;
        const CARD_PAY_TO_CARD_ACCOUNT = 11;
        const CARD_TRANS_SEND_TO_PAYSYS = 12;
        const CARD_RETURN_INVEST = 13;
        const CARD_WTRU_LOAD = 14;
        const CARD_PAY_GIFTCARD = 15;
        const CARD_REMOVE_ALL_MONEY = 16;
        const CARD_BUY_GIFTGUARANT = 17;
        const CARD_SELL_GIFTGUARANT = 18;
        const CARD_BOT_LOAD = 19;
        const CARD_START_ARBITRAGE = 20;
        const CARD_STOP_ARBITRAGE = 21;
        const CARD_SELL_CERT = 21;
        const CARD_TRANS_RETURN_GIFTGUARANT = 22;
        
        const CARD_TRANS_PARTNER_RAWARD = 23;
        const CARD_TRANS_VOLUNTEER_RAWARD = 24;        

        const CARD_TRANS_PREFUND_LOAD = 31;        
        const CARD_TRANS_PREFUND_RETURN = 32;        
        const CARD_TRANS_PREFUND_COME = 33;        
        
        const CARD_TRANS_RETURN_ARBITRATION_AND_PERCENTS = 25;
        
        const CARD_TRANS_LOAN_API_SENDDIRECT = 26;
        const CARD_TRANS_LOAN_API_LOAD = 27;
        const CARD_TRANS_LOAN_API_UNLOAD = 28;        
        const CARD_TRANS_BUY_SF = 29;       
        const CARD_PAY_SF = 30;      
        const CARD_RETURN_INCOME = 31;
        
        const CARD_PAY_VISACARD = 33;
        

        const METHOD_SEND_CARD_DIRECT = 'send_card_direct';
        const METHOD_PURCHASE_MONEY = 'purchase_money';
        const METHOD_LOAD = 'load';

        const STATUS_OK = 0;
        const STATUS_ERROR = 1;

	function __construct() {
		parent::__construct();
		self::$last_error = '';
	}



    public function add_transaction( $method, $from_card_id, $to_card_id, $type, $status, $source_id, $api_response = '', $note = '',$summa = NULL ){

        if (is_object($api_response) || is_array($api_response) )
           $api_response =  json_encode($api_response);


        $this->load->model('users_model','users');
        $data = [
            'method' => $method,
            'from_card_id' => $from_card_id,
            'to_card_id' => $to_card_id,
            'type' => $type,
            'status' => $status,
            'api_response' => $api_response,
            'note' => $note,
            'operation_dttm' => date('Y-m-d H:i:s'),
            'source_id' => $source_id,
            'user_ip' => $this->users->getIpUser(),
            'summa' => $summa

        ];

        $this->db->insert($this->tableName, $data );
    }
    
    
    
    public function get_transactions_by_period($card_id, $method, $status, $date_from, $date_to ){
        
        
        $this->db->where('from_card_id', $card_id)
                ->where('status', $status)
                ->where('method', $method)
                ->where('operation_dttm >=', $date_from )
                ->where('operation_dttm <=', $date_to )
                ->get($this->tableName)
                ->result();
        
    }

    public function get_last_trans_from_card($card_id) {
        if(empty($card_id)) return FALSE;
        

        $q = $this->db->where('to_card_id', $card_id)
            ->limit(1)
            ->get($this->tableName)
            ->result();

        if(empty($q[0])) return FALSE;

        return $q[0];
    }

    public function is_success_trans_by_api_response($api_response) {
        if(empty($api_response)) return FALSE;

        $response = json_decode($api_response);
        
        if($response->status == 'Success') 
            return TRUE;

        return FALSE;
    }

}