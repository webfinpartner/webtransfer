<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Volunteer_topic_model extends CI_Model {
    public $tableName = "volunteer_topic";

    const STATUS_ACTIVE = 1;
    const STATUS_CLOSE  = 2;

    public function __construct(){
        parent::__construct();
        $this->load->model("users_model","users");
    }

    public function setActive($id) {
        return $this->db->update($this->tableName, array("status" => self::STATUS_ACTIVE), array("id" => $id));
    }

    public function countActive($id_user = null) {
        if (null == $id_user)
            $id_user = $this->users->getCurrUserId();

        return $this->db->where(array("id_user" => $id_user, "status" => self::STATUS_ACTIVE))
            ->count_all_results($this->tableName);
    }

    public function setClose($id) {
        return $this->db->update($this->tableName, array("status" => self::STATUS_CLOSE), array("id" => $id));
    }

    public function getTopics($id_user = null) {
        if (null == $id_user)
            $id_user = $this->users->getCurrUserId();

        return $this->db->where("id_user",$id_user)->order_by("status", "ASC")->order_by("id", "DESC")->get($this->tableName)->result();
    }

    public function getTopicId($name, $id_user = null) {
        if (null == $id_user)
            $id_user = $this->users->getCurrUserId();

        $id = $this->db->where(array("id_user" => $id_user, "name" => $name))->get($this->tableName)->row('id');

        if (empty($id)){
            $this->db->insert($this->tableName, array("id_user" => $id_user, "name" => $name));
            $id = $this->db->insert_id();
        }
        return $id;
    }

    public function getTopicRecipient($id_topic) {
        $id_user = $this->db->get_where($this->tableName, array('id' => $id_topic))->row()->id_user;
        $me = $this->users->getCurrUserId();
        $parent = $this->users->getParentUserId();
        return ($id_user == $me) ? $parent : $id_user;
    }

    public function getOwnerTopic($id_topic) {
        return $this->db->get_where($this->tableName, array('id' => $id_topic))->row()->id_user;
    }

    public function getTopicsByIds($ids) {
        if (empty($ids))
            return array();
        if (!is_array($ids))
            $ids = array($ids);
        return $this->db->where_in("id",$ids)->get($this->tableName)->result();
    }

    public function getTopicsByUsers($ids) {
        if (empty($ids))
            return array();
        if (!is_array($ids))
            $ids = array($ids);
        return $this->db->where_in("id_user",$ids)->order_by("id", "DESC")->get($this->tableName)->result();
    }

    public function getUserTopics($id_user){
        return $this->createTopicsWithMessages($this->getTopics($id_user));
    }

    public function getVolanteerTopics($id_children){
        return $this->createTopicsWithMessages($this->getTopicsByUsers($id_children));
    }

    public function createTopicsWithMessages(array $t) {
        $this->load->model("volunteer_message_model");
        $this->load->model('users_filds_model', 'usersFilds');
        $topics = array();
        foreach ($t as $topic) {
            
            $arr_messages = array();
            $messages = $this->volunteer_message_model->getMessagesByTopic($topic->id);
            $this->usersFilds->getUsersByIds($messages);
            
            foreach($messages as $val)
            {
                $nickname = $this->usersFilds->getUserNickname($val->id_user);
                if(FALSE !== $nickname){
                   $val->name = $nickname; 
                   $val->sername = '';
                }
                $arr_messages[] = $val;
            }
            
            $topics[$topic->id] = array_merge((array) $topic, array("messages" => $messages));
            $topics[$topic->id]["count"] = count($topics[$topic->id]["messages"]);
            $last = end($topics[$topic->id]["messages"]);
            $topics[$topic->id]["date"] = (0 < $topics[$topic->id]["count"]) ? $last->date : "";
        }
        return $topics;
    }

    public function getAllTopics($cols, $table) {
        $search = $_POST["search"];
        //$filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($search)
            createSearch($search, $cols);

        $r["count"] = $this->db
                        ->select("COUNT(*) as count")
                        ->get($table)
                        ->row()
                        ->count;

        if ($order)
            createOrder($order);
        if ($search)
            createSearch($search, $cols);

        $r["rows"] = $this->db
                ->select("vt.*, (MIN(vm.for_admin)) admin")
                ->join("volunteer_message vm", "vm.id_topic = vt.id", "inner")
                ->limit($per_page, $offset)
                ->group_by("vt.id")
                ->get("$table vt")
                ->result(); //echo $this->db->last_query();die;

        return json_encode($r);
    }

    public function getAllTopicsWithAsk($cols, $table) {
        $this->load->model("volunteer_message_model");
        $search = $_POST["search"];
        //$filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($search)
            createSearch($search, $cols);
        $q = "SELECT COUNT(c.id) as count FROM ("
            . "SELECT vt.* FROM $table vt "
            . "INNER JOIN volunteer_message vm ON vm.id_topic = vt.id "
            . "WHERE vt.status = ".self::STATUS_ACTIVE." "
            . "GROUP BY vt.id "
            . "HAVING MIN(vm.for_admin) = ". Volunteer_message_model::FOR_ADMIN_TRUE .") c";
        $r["count"] = $this->db->query($q)->row()->count;
//                        ->select("COUNT(*) as count")
//                        ->join("$table vt", "vm.id_topic = vt.id", "inner")
//                        ->where("for_admin", 1)
//                        ->get("volunteer_message vm")
//                        ->row()
//                        ->count;

        if ($order)
            createOrder($order);
        if ($search)
            createSearch($search, $cols);

        $r["rows"] = $this->db
                ->select("vt.*, (MIN(vm.for_admin)) admin")
                ->join("volunteer_message vm", "vm.id_topic = vt.id", "inner")
                ->limit($per_page, $offset)
                ->where("vt.status", self::STATUS_ACTIVE)
                ->group_by("vt.id")
                ->having("MIN(vm.for_admin)", Volunteer_message_model::FOR_ADMIN_TRUE)
                ->get("$table vt")
                ->result(); //echo $this->db->last_query();die;

        return json_encode($r);
    }

    public function getMess4Admin($id) {
        return $this->createTopicsWithMessages($this->getTopicsByIds((int) $id));
    }
}