<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'controllers/user/Accaunt.php';

class Automatic extends Accaunt {
    protected $me;

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model","users");

        $this->me = $this->users->getCurrUserId();
    }

    public function index() {
        $data = new stdClass();
        $this->load->model("automatic_model","automatic");
        $this->load->helper('form');
        $this->load->library('form_validation');
        $data->save = false;
        if($this->input->post("submit") && $this->form_validation->run('automatic')){
            $obj = $this->automatic->obj;
            foreach (get_object_vars($obj) as $key => $value) {
                if(in_array($key, array("percent", "credit_max_start_psnt"))) $obj->$key = (isset($_POST[$key])) ? (float) $_POST[$key] : 0;
                else $obj->$key = (isset($_POST[$key])) ? (int) $_POST[$key] : 0;
            }
            $obj->id_user = $this->me;
            $this->automatic->save($obj);
            $data->save = true;
        }
        $data->user = $this->users->getUserData($this->me);
        $template = "automatic_first";
        if(3 == $data->user->bot) {
            $template = "automatic";
            $data->obj = $this->automatic->get($this->me);
            $data->max_psnt = 1.5;
            if($data->obj->credit_max_start_psnt_auto) $data->obj->credit_max_start_psnt = 1;
        }
        $this->content->user_view($template,$data);
    }

    public function createAutomatic() {
        $data = new stdClass();
        $user_ratings = viewData()->accaunt_header;
        if (!$this->accaunt->isUserAccountVerified()) {
            accaunt_message($data, _e('Для подачи заявки необходимо верифицировать Профиль'), 'error');
        } else if ( $user_ratings['overdue_garant_count'] > 0) {
            accaunt_message($data, _e('Для проведение данной операции Вам необходимо погасить просроченные гарантированные займы.'), 'error');
        } else if ( $user_ratings['overdue_standart_count'] > 0) {
            accaunt_message($data, _e('Для проведение данной операции Вам необходимо погасить просроченные стандартные займы.'), 'error');
        } else {
            $agree = $this->input->post("agree");
            if(!empty($agree)) $this->users->updateUserField($this->me, "bot", 3);
            redirect(site_url('account/automatic'));
        }
    }

}
