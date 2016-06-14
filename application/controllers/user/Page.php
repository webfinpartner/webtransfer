<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Page extends SimpleREST_controller {

    public function index() {
        $data = new stdClass();
        $url = uri_string();
        $url = preg_replace("#/news_(?:[0-9]+)#", "", $url);
        $url = urldecode($url);
        $url = explode("/", $url);
        $url = @array_pop($url);
        require_once APPPATH . 'libraries/reCapcha/recaptchalib.php';
        $data->reCapcha = recaptcha_get_html(getReCapchaKey(), null, true);
        $data->content = $this->base_model->get_page($url);
        viewData()->page_name = $url;
        if (!empty($data->content)) {
                //login user
            $data->isAuthorized = $this->base_model->login($this, false);
            if( $data->isAuthorized ){
                if ($this->user->client == 2)
                    viewData()->banner_menu = "partner";
                else
                    viewData()->banner_menu = "profil";
            }else
                viewData()->banner_menu = "profile_login";


            //$data->banner = $this->base_model->get_banner($data->content->id_page);
            viewData()->secondary_menu = "help";
            if ( !empty($data->content->secondary_menu))
                viewData()->secondary_menu = $data->content->secondary_menu;





            $data->page_parent = url_parents($data->content->id_parent);

            $this->content->config($data->content->title, $data->content->meta_words, $data->content->meta_descript);
            if($this->base_model->returnNotAjax())$this->content->template('page', $data);
            else $this->content->templateAjax('page', $data);
        }
        else show_404();
    }

    public function registration($type) {
        $data = new stdClass();
        $page = new stdClass();
        $id = ($type == 'invest') ? 7 : 4;
        $page->shablon = $id;
        $page->content = "(*****Метка шаблона*****)";
        $data['content'] = shablon($page);
        $this->load->view('/user/reg_form', $data);
    }

}
