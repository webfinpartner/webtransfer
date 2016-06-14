<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Countries_list_model extends CI_model {
    public $tableName = 'countries_list';

    static $last_error;

    public function __construct() {
        parent::__construct();
        self::$last_error = '';
    }

    public function get_list(){
        return $this->db->where('iso2 is not null')->order_by('name','ASC')->get($this->tableName)->result();
    }
}