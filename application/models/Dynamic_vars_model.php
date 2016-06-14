<?php

class Dynamic_vars_model extends CI_Model {
    
        private $tablename = 'dynamic_vars';
      
	function __construct()
	{
		parent::__construct(); 
	}

        public function get($name){
            
            return $this->db->get($this->tablename)->row();
        }
        
        
        public function get_value($name){
            
            return $this->db->get($this->tablename)->row('value');
        }

        public function get_last_update($name){
            
            return $this->db->get($this->tablename)->row('last_update');
        }
        
        
        public function update($name, $value){
            
            $old = $this->get($name);
            if (empty($old)){
                $this->db->insert($this->tablename, ['value'=>$value, 'last_update'=>date('Y-m-d H:i:s'), 'name'=>$name]);    
            } else {
                $this->db->update($this->tablename, ['value'=>$value, 'last_update'=>date('Y-m-d H:i:s')], ['name'=>$name]);    
            }
            
            
            
        }
        
        
}