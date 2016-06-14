<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

  public function new_feeds()
  {
  		return $this->db->get_where("feedback", array('admin_state'=>1))->result();
  }

  public function  all_feeds()
  {
  		return $this->db->order_by('admin_state',"ACS")->get("feedback")->result();
  }
  public function  get_feedback($id)
  {
  		$data['item']=$this->db->get_where("feedback", array('id'=>$id))->row();
  		$data['answer']=$this->db->get_where("feedback_answer", array('id_back'=>$data['item']->id))->result();
  		return $data;
  }

  public function add_answer($data,$post)
  {
  		$id=$data['item']->id;
  		$email=$data['item']->email;

  		$this->db->insert("feedback_answer",array('id_back'=>$id,"text"=>$post));
  		$this->mail->send($email, $post,_e('Ответ').' Webtransfer',"support@webtransfer-finance.com");

  }

  public function feed_readen($id)
  {
  		$this->db->where('id',$id)->update("feedback", array("admin_state"=>2));
  }

    public function feed_close($id)
  {
  		$this->db->where('id',$id)->update("feedback", array("admin_state"=>3));
  }

     public function feed_open($id)
  {
  		$this->db->where('id',$id)->update("feedback", array("admin_state"=>2));
  }
  }


