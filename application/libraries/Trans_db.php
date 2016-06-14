<?php

/**
 * Description of transDB
 *
 * @author esb
 */
class trans_db {
    private $_fields = array();
    private $_isTraslate = true;
    private $_ci;
    private $_DB_lang;
    private $_cur_lang;
    private $_trans = array();

    public function __construct($fields) {
        $this->_fields = $fields;
        $this->_ci = get_instance();
        $this->_DB_lang = $this->_ci->config->item('db_lang');
        $this->_cur_lang = $this->_ci->lang->lang();
        $this->_isTraslate = ($this->_DB_lang != $this->_cur_lang);
        if(!$this->_isTraslate) return; // если перевод не нужен то выходим

        //загружаем перевод
        foreach ($fields as $field) {
            $this->_trans[$field] = include 'trans_db/'.$this->_cur_lang.'-'.$field.'.php';
        }
    }

    public function translate($rows) {
        if(!$this->_isTraslate) return $rows; //нужно ли переводить?


        foreach ($rows as &$row) {
            foreach ($this->_fields as $field) {
                $transl_part = explode('"', $row->$field, 2);
                $transl_part[0] = str_replace(array_keys($this->_trans[$field]), array_values($this->_trans[$field]), $transl_part[0]);
                $row->$field = implode('"', $transl_part);
            }
        }
        return $rows;
    }
}
