<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Countries_model extends CI_model
{
	public $tableName = 'countries';


	static $last_error;

	function __construct() {
		parent::__construct();
		self::$last_error = '';
	}


        
        public function get_list(){
            
            return $this->db->where('iso2 is not null')->order_by('name','ASC')->get($this->tableName)->result();
            
            
        }
}