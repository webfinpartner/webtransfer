<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Public_user extends SimpleREST_controller {
    protected $me = false;
    protected $my_parent = false;
    public function __construct() {
        parent::__construct();
        $this->load->model("users_model","users");
        if( $this->base_model->login($this, false)){

            $this->load->model('inbox_model', 'inbox');
            $this->_social = $this->session->userdata("social_token");
            viewData()->banner_menu = "profil";
            $this->me = $this->users->getCurrUserId();
            $this->my_parent = $this->users->getMyParent();
            $this->my_parent = $this->my_parent->id_user;
            $this->content->clientType = 1;
        } else {
            viewData()->banner_menu = "profile_login";
        }
        viewData()->secondary_menu = "public_user";
    }

    private function wall($user_id = null) {
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        viewData()->user = $user_id;
        $view = 'public_user_wall';
        $data->user_data = $this->users->getUserData($user_id);
        if (null == $user_id || empty($data->user_data))
            $view = 'public_no_user';

        $data->me = $this->me;
        $data->my_parent = $this->my_parent;

        $this->show($view, $data);
    }

    private function reviews($user_id = null) {
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        viewData()->user = $user_id;
        $view = 'public_user_reviews';
        $data->user_data = $this->users->getUserData($user_id);
        if (null == $user_id || empty($data->user_data))
            $view = 'public_no_user';

        $data->me = $this->me;
        $data->my_parent = $this->my_parent;

        $this->show($view, $data);
    }

    private function statistics($user_id = null) {
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        viewData()->user = $user_id;
        $view = 'public_user_statistics';
        $data->user_data = $this->users->getUserData($user_id);
        if (null == $user_id || empty($data->user_data))
            $view = 'public_no_user';

        $data->me = $this->me;
        $data->my_parent = $this->my_parent;

        $this->show($view, $data);
    }

    private function applications($user_id = null) {
        $data = new stdClass();
        viewData()->page_name = __FUNCTION__;
        viewData()->user = $user_id;
        $view = 'public_user_applications';
        $data->user_data = $this->users->getUserData($user_id);
        if (null == $user_id || empty($data->user_data))
            $view = 'public_no_user';

        $data->me = $this->me;
        $data->my_parent = $this->my_parent;

        $this->show($view, $data);
    }

    private function show($view, $data) {
        $data->notAjax = $this->base_model->returnNotAjax();
        if($data->notAjax)
            $this->content->public_template($view,$data);
        else
            $this->load->view("user/$view", get_object_vars($data));
    }
}
