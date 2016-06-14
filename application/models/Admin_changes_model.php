<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_changes_model extends CI_model
{
	public $tableName = 'admin_changes';


	static $last_error;
   

	function __construct() {
		parent::__construct();
		self::$last_error = '';
	}


        
        public function add( $object, $record_id, $field, $old, $new   ){
            
            $data = [
                'admin_id' => $this->admin_info->id_user,
                'admin_ip' => !empty($_SERVER['HTTP_X_REAL_IP'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'],
                'change_dttm' => date('Y-m-d H:i:s'),
                'changed_object' => $object,
                'changed_record_id' => $record_id,                
                'field' => $field,
                'old_data' => $old,
                'new_data' => $new
                
            ];
            
            try {
                $this->db->insert($this->tableName, $data );
                return TRUE;
            } catch (Exception $ex) {
               return FALSE;
            }
        }
        
}