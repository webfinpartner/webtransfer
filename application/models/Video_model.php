<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {
    public $tableName = "video";

    public function __construct(){
        parent::__construct();
    }

    public function getAll($offset = 0, $limit = 10, $cat = null) {       
        if(null !== $cat) $this->db->where("category", $cat);
        return $this->db
            ->where("status", 1)
           // ->where("lang",$this->lang->lang())
            ->order_by("featured desc")
            ->order_by("date desc")
            ->limit($limit,$offset)
            ->get($this->tableName)
            ->result();
    }

    public function countAll($cat = null) {
        if(null !== $cat) $this->db->where("category", $cat);
        return $this->db
            ->where("status", 1)
          //  ->where("lang",$this->lang->lang())
            ->count_all_results($this->tableName);
    }

    public function getVideo($id) {
        return $this->db
//            ->select("info, category, id_video, title")
            ->where("id", (int) $id)
            ->get($this->tableName)
            ->row();
    }

    public function addVote($id) {
        $val = $this->db->where("id",$id)->get($this->tableName)->row("vote");
        return $this->db->update($this->tableName, array("vote"=>($val+1)), array("id" => $id), 1);
    }

    public function delete_photo($id) {
        return $this->db
                ->where('id', $id)
                ->update($this->tableName, array('foto' => 0));
         
    }
}