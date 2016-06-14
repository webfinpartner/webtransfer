<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shop_model extends CI_Model {
    public $tableName = "shops";

    const CREATE = "create";
    const ACTIVE = "enable";
    const BLOCKED = "ban";
    const DELETED = "del";

    function __construct(){
        parent::__construct();
        $this->load->model('accaunt_model','accaunt');
        $this->id_user = $this->accaunt->get_user_id();
    }

    # Работа со строкой идетификатора магазина и ключем
    public function slash ($string, $type = FALSE){
        if (!$type){
            $string = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($string as $symbol) {
                $i++;
                if ($i == 4 || $i == 8 || $i == 12 || $i == 16 || $i == 20)
                    $result .= $symbol . "-";
                else
                    $result .= $symbol;
            }
        }
        else
            $result = str_replace("-", NULL, $string);

        return $result;
    }

    # Получение списка магазинов или магазина пользователя
    public function getShops($shop_id = FALSE) {
        $id_user = $this->accaunt->get_user_id();
        if (!$shop_id)
            return $this->db->get_where($this->tableName, array("user_id" => $id_user))->result();
        else
            return $this->db->get_where($this->tableName, array("user_id" => $id_user, 'shop_id' => $shop_id))->row();
    }

    # Получение магазина для работы
    public function getShop($shop_id = FALSE) {

        $query = $this->db->get_where($this->tableName, array('shop_id' => $shop_id))->row();
        $query->identifier_slash = $this->slash($query->shop_id);
        return $query;
    }

    public function duplicate($user, $link)
    {
        $query = $this->db->query('SELECT `shop_id` FROM `shops` WHERE `user_id` = ' . $this->db->escape($user) . ' AND `url` = ' . $this->db->escape(base64_encode($link)) . ' LIMIT 1');

        if ($query->num_rows() == 0)
            return FALSE;
        else
            return $query->row()->shop_id;
    }

    public function duplicate_deactivation($user_id, $link)
    {
        $this->db->query('UPDATE `shops` SET `status` = 0 WHERE `user_id` != ' . $this->db->escape($user_id) . ' AND `url` = ' . $this->db->escape($link));
        return TRUE;
    }

    public function create($user, $link)
    {
        $this->load->helper('string');

        while (TRUE)
        {
            $shop_id = strtoupper(random_string('alnum', 24));
            $query = $this->db->query('SELECT `shop_id` FROM `shops` WHERE `shop_id` = ' . $this->db->escape($shop_id) . ' LIMIT 1');

            if ($query->num_rows() == 0)
                break;
            else
                continue;
        }

        # Добавление данных в базу
        $data = array(
                        'shop_id' => $shop_id,
                        'user_id' => (int) $user,
                        'url' => base64_encode($link),
                        'string' => strtoupper(random_string('alnum', 24)),
                        'status' => '1'
                    );
        $this->db->insert($this->tableName, $data);

        return $shop_id;
    }

    public function save($shop_id, $data)
    {
        $this->db->update($this->tableName, $data, 'shop_id = \'' . $shop_id . '\'', 1);
    }


}
