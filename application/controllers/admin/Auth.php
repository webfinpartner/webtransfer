<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Permissions_controller')) {
    require APPPATH . 'libraries/Permissions_controller.php';
}

class Auth extends Permissions_controller {

    /**
     * Проверяем наличие юзера
     */
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('code');
        $this->user = (!empty($_COOKIE[COOKIE_ADMIN])) ? $this->code->decrypt($_COOKIE[COOKIE_ADMIN]) : '';

        if (empty($this->user))
            $this->user = false;
    }

    public function index() {
        $this->user ? redirect(base_url() . 'opera/feedback/all_feeds') : $this->login();
    }

    /**
     * Проверка введенных данных
     */
    public function login() {
        $this->load->model('monitoring_model', 'monitoring');
        if ($this->user) {
            $this->monitoring->log(null, 'Вход уже залогиненного пользователя', array('private', 'common'));
            redirect(base_url() . 'admin/feedback/all_feeds');
        }

        if (isset($_POST['password']) && isset($_POST['login'])) {
            $login = trim($_POST['login']);
            $this->monitoring->log($login, 'Попытка входа');

            // Обработка введенных данных
            $array = array(
                'login' => strip_tags(trim($_POST['login'])),
                'password' => strip_tags(trim($_POST['password'])),
            );

            $auth = $this->db->get_where('admin', $array)->row('id_user');

            if (!empty($auth)) {
                $time = (!empty($_POST['remMe'])) ? 60 * 60 * 24 * 30 * 3 : 0;

                $cookie = array(
                    'name' => COOKIE_ADMIN,
                    'value' => $this->code->code($auth),
                    'expire' => $time,
                    'domain' => '',
                    'path' => '/',
                    'prefix' => '',
                );

                $this->input->set_cookie($cookie);
                $urls = Permissions::resetInstanse($auth)->getURLs();

                $this->monitoring->log($login, "Успешный вход", array('private', 'common'));

                redirect(base_url() . substr(reset($urls), 1));
            } else {
                $this->monitoring->log($login, 'Неудача');
                $this->load->view('admin/login', array("error" => 1));
            }
        } else {
            $this->load->view('admin/login');
        }
    }

    /**
     * Выход из системы
     */
    function logout() {
        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, 'Выход', array('private', 'common'));

        out_admin();
        redirect(base_url() . 'opera/auth/login');
    }

    function sendSms() {
        $this->load->model("admin_model", "admin");
        if (!empty($this->user)) {
            $ans = $this->admin->sendSms($this->user);
            if ($ans === true)
                echo "ok";
            else
                print_r($ans);
        }
    }

}
