<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Novost extends SimpleREST_controller {

    public function index($url=0){
        $data = new stdClass();
        if( $this->base_model->login($this, false) ){

            $this->load->model('inbox_model', 'inbox');
            $this->_social = $this->session->userdata("social_token");

            viewData()->banner_menu = "profil";

//            viewData()->secondary_menu = "profile";
        }else{
            viewData()->banner_menu = "profile_login";

//            viewData()->secondary_menu = "help";
        }
        viewData()->secondary_menu = "help";

        $data->new=$this->base_model->get_new(urldecode($url));
        $data->page_novosti=$this->base_model->get_page_news();
        if(!empty($data->new) and  !empty($data->page_novosti))
        {
                $data->news=$this->base_model->get_news(5);
                $data->page_parent =url_parents($data->page_novosti->id_parent);
                $data->banner= $this->base_model->get_banner($data->page_novosti->id_page);
                $this->content->config($data->new->title);
                if($this->base_model->returnNotAjax())$this->content->template('novost',$data);
                else $this->content->templateAjax('novost',$data);
        }
        else show_404 ();
    }
}
