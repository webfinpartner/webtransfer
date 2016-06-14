<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Mailinglist_model extends CI_model
{
	public $tableName = 'mailing_list';


	static $last_error;

	function __construct() {
		parent::__construct();
		self::$last_error = '';
	}


        
        public function add_email($email){
            if (empty($email) )
                return _e('Внутренняя ошибка');
            
            if ( trim($email) == '')
                return _e('Пустой email');
            
            $rows = $this->db->where('email',$email)->get($this->tableName)->result();
            if ( !empty($rows) )
                return _e('Вы уже подписаны');
            
            $this->db->insert($this->tableName, [
                'email'=>$email, 'ins_date'=>date('Y-m-d H:i:s'),
                'ip'=>$_SERVER["REMOTE_ADDR"]
                    ]);
            return TRUE;
            
            
        }
}