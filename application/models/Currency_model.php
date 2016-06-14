<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_model extends CI_Model {
    public $tableName = "currency";


    function __construct() {
        parent::__construct();
    }


    /**
     * Возвращает список валют
     */
    public function get_currency_list(){
        
        $this->db->cache_on();
        $res = $this->db->get($this->tableName)->result(); 
        $this->db->cache_off();
        return $res;
        
    }
    
    /**
     * Возврашщает валюту по коду
     * @param type $code
     * @return type
     */
    public function get_currency_by_code($code){
        return $this->db->where('code', $code)->get($this->tableName)->row(); 
    }    
    
    
    
}