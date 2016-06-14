<?php

class System_vars_model extends CI_Model {
    
    
    static $last_error;
    
    function __construct() {
        parent::__construct();        
    }
    
    /**
     * 
     * @param Object $data
     * @return int
     */
     public function create($data) {
            // проверим наличие такой же переменной
            if ($this->isMachineNameExists($data['machine_name'])){
                self::$last_error = 'Такая системная переменная уже есть';
                return FALSE;
            }
                
            $this->db->insert('system_vars', $data);
            $res = $this->db->insert_id();
            if  (!$res){
                self::$last_error = 'Системная ошибка при добавлении системной переменной';
                return FALSE;                
            }
            // почистим кэш
            $this->db->cache_delete('sysvars','all');
            return $res;
    }    
    /**
     * 
     * @param Object $data
     * @param int $id
     * @return int
     */
    public function edit($data, $id) {
            // проверим наличие такой же переменной
            if ($this->isMachineNameExists($data['machine_name'], $id)){
                self::$last_error = 'Такая системная переменная уже есть';
                return FALSE;
            }
        
            $this->db->where('id', $id)->update('system_vars', $data);
            $res = $this->db->affected_rows();
            // если записи обновились то почистим кэш
            $this->db->cache_delete('sysvars','all');
            return $res;
    }    
    /**
     * 
     * @param int $id
     */
    public function remove($id) {
            $this->db->where('id', $id)->delete('system_vars');
            //почистим кэш
            $this->db->cache_delete('sysvars','all');
    }    

    /**
     * 
     * @param int $id
     * @return Object
     */
     public function get($id) {
        $query = $this->db->get_where('system_vars',array('id'=>$id));
        if ($query->num_rows()>0)
            return $query->row();
        return FALSE;
    }
    
    /**
     * 
     * @return Object
     */
     public function get_all() {
        //$this->db->cache_on();
        $this->db->order_by('machine_name','ASC');
        $result = $this->db->get('system_vars')->result();
        //$this->db->cache_off();
        return $result;
        
    }
    
    /**
     * 
     * @return Array
     */
     public function vars() {
        $vars = array(); 
    //    $this->db->cache_on();
        $res = $this->db->get('system_vars')->result();
        if ( !empty($res) )
             foreach( $res as $row)
                 if ( $row->value==NULL )
                    $vars[ $row->machine_name ] = $row->default_value;
                 else 
                    $vars[ $row->machine_name ] = $row->value;
        
     //   $this->db->cache_off();
        return $vars;
        
    }
    
    /**
     * 
     * @return Array
     */
     public function get_var($name) {
        $vars = $this->vars();
        if (!isset($vars[$name])){
            //throw  new Exception("System var $name not found");
            //show_error("System var $name not found");
            return FALSE;
        }
        return $vars[$name];
        
    }
    
    
    /**
     * Проверка наличия такой же переменной
     * @param String $name
     * @param int $exclude_id
     * @return boolean
     */
     public function isMachineNameExists($name, $exclude_id = 0) {
         $query = $this->db->get_where('system_vars',array( 'LOWER(machine_name)'=>  strtolower($name), 'id !='=>$exclude_id));
         return $query->num_rows() > 0;
    }    
    
    
    
    /**
     * Return last error was catched
     * @return type
     */
    public function get_last_error() {
        return self::$last_error;
    }
}
