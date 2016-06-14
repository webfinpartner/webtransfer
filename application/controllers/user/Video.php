<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Video extends SimpleREST_controller {
    protected $me = false;
    protected $my_parent = false;
    public function __construct() {
        parent::__construct();
        $this->load->model("video_model","video");
        if( $this->base_model->login($this, false)){

            $this->load->model('inbox_model', 'inbox');
            $this->_social = $this->session->userdata("social_token");
            viewData()->banner_menu = "profil";
            viewData()->secondary_menu = "profile";
            $this->load->model("users_model","users");
            $this->me = $this->users->getCurrUserId();
            $this->my_parent = $this->users->getMyParent();
            $this->my_parent = $this->my_parent->id_user;
        } else {
            viewData()->banner_menu = "profile_login";
            viewData()->secondary_menu = "help";
        }

    }

    public function index($cat = null) {
        $data = new stdClass();
        $data->count = $this->video->countAll($cat);
        $data->videos = $this->video->getAll(0,15,$cat);
        $data->cat = $cat;
        $data->me = $this->me;
//        $data->verified = $this->accaunt->isUserAccountVerified();
        $this->content->template('videos',$data);
    }

    public function show($cat=null,$id=0) {
        if(null === $cat) show_404();
        if(0 == $id) {
            $this->index(video_catigories_link_num($cat));
            return;
        }
        $data = new stdClass();
        $data->video = $this->video->getVideo((int)$id);
        $data->cat = $cat;
        $data->me = $this->me;
        $data->verified = $this->accaunt->isUserAccountVerified();
        if(empty($data->video)) show_404();
        $this->content->template('video',$data);
    }

    public function vote($id = null) {
        if(null === $id) show_404();
        if(false === $this->me) show_404();
        if (!$this->accaunt->isUserAccountVerified()) show_404();
        $name = "__vk$id";
        $vote = $this->input->cookie($name);
        if(isset($_SESSION["voted"][$id]) || $vote)
            return '';
        $this->load->model('Votes_model','votes');
        if ( empty($this->votes->isVoted(2))){
            return '';
        }

        $voted = substr(md5(microtime()),0,4);
        $_SESSION["voted"][$id] = $voted;
        cookie($name,$voted);

        $this->video->addVote($id);

        $v = $this->video->getVideo($id);

//        $this->mail->user_sender('exchange-cert-buyer', $this->me, array());
        $this->load->model('inbox_model');
        $this->inbox_model->writeInbox($this->me, $v->id_user, sprintf(_e(' За ваше видео(%s) был отдан голос пользователя %s'), $v->title, $this->me));



        $this->votes->vote(['id_vote'=>2, 'variant'=>$id,'vote_name'=>'video']);

        //add bonus
        if($this->my_parent == $v->id_user){
            $me = $this->users->getUserData($this->me);
            if((time()-(5*24*60*60)) < strtotime($me->reg_date)){
                $this->load->model('accaunt_model', "accaunt");
                $this->accaunt->payBonusesOnVoteVideo($v->id_user, $this->me);
            }
        }

        echo $id;
    }

    public function next_video($offset = NULL, $cat = null) {
        $this->base_model->redirectNotAjax();
        if (null == $offset) $offset = 0;

        $data = new stdClass();
        $data->videos = $this->video->getAll($offset, 15, $cat);
        $this->load->view('user/next_videos', $data);
    }

}
