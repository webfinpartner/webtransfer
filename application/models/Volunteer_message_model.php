<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volunteer_message_model extends CI_Model {
    public $tableName = "volunteer_message";

    const FOR_ADMIN_FALSE   = 2;
    const FOR_ADMIN_TRUE    = 1;

    const FROM_ADMIN_FALSE   = 2;
    const FROM_ADMIN_TRUE    = 1;

    public function __construct(){
        parent::__construct();
        $this->load->model("users_model","users");
    }

    public function addMessage($id_topic, $text, $for_admin = self::FOR_ADMIN_FALSE, $from_admin = self::FROM_ADMIN_FALSE, $id_user = null) {
        if (null == $id_user)
            $id_user = $this->users->getCurrUserId();

        $this->db->insert($this->tableName, array(
            "id_user" => $id_user,
            "id_topic" => $id_topic,
            "text" => $text,
            "for_admin" => $for_admin,
            "is_admin" => $from_admin
            )
        );

        $this->load->model("volunteer_topic_model");
        $this->volunteer_topic_model->setActive($id_topic);

    }

    public function getMessagesByTopic($id_topic){
        $this->load->library("code");
        
        return $this->code->db_decode($this->db
            ->select("vm.*, "
                . "(CASE WHEN 2 = vm.is_admin THEN u.name WHEN 1 = vm.is_admin THEN a.name END) name, "
                . "(CASE WHEN 2 = vm.is_admin THEN u.sername WHEN 1 = vm.is_admin THEN a.family END) sername")
            ->from($this->tableName." vm")
            ->join("users u", "u.id_user = vm.id_user","left")
            ->join("admin a", "a.id_user = vm.id_user","left")
            ->where(array("vm.id_topic" => $id_topic))
            ->order_by("vm.date", "ASC")
            ->get()
            ->result());
    }
}