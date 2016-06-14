<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}
class Permissions_controller extends SimpleREST_controller
{

	public function __construct()
	{
            parent::__construct();
            view()->setAdmin();

            $this->load->helper("admin_helper");
            $this->load->model("admin_model", "admin");
            require_once APPPATH.'libraries/Permissions.php';
            $rights = Permissions::getInstance();
            $uri = $_SERVER["REQUEST_URI"];
            $id_admin = $rights->getAdminId();
            $admin = $rights->getAdmin();
            $sec_auth_hesh = (!empty($_COOKIE["auth_hesh"]))? $_COOKIE["auth_hesh"] : false;
            $hash = $this->admin->getHash($id_admin);
            if( $hash != $sec_auth_hesh && !empty($id_admin) && "/opera/auth/sendSms"!= $uri && "/opera/auth/logout"!= $uri){
                if(isset($_POST['wt_token'])){
                    if( !Permissions::checkSecurity($id_admin)){ // ENVIRONMENT == 'production' &&
                        include(APPPATH.'views/admin/sec_auth_error.php');
                        die;
                    } else {
                        $hash = base64_encode(strtoupper(sha1(time().microtime().",zds'A]QWPOEI1[9243308zx*&^*&$@")));
                        $this->admin->setHash($id_admin, $hash);
                        cookie_auth($hash);
                    }
                } else {
                    include(APPPATH.'views/admin/sec_auth.php');
                    die;
                }
            }

            $urls = $rights->getURLs();
            $urls[] = "/opera/auth";
            $urls[] = "/opera/auth/login";
            $urls[] = "/opera/auth/logout";
            $urls[] = "/opera/auth/sendSms";
            $show404 = true;
            foreach ($urls as $url) {
                $suri = $this->_cutNum($uri, $url);
                if($suri == $url) $show404 = false;
            }
            if("/opera" == $uri or "/opera/" == $uri) redirect(config_item('base_url').reset($urls));
            if ($show404) show_404();
        }

        private function _cutNum($uri, $url) {
            $u = explode("/", $url);
            $i = end($u);
            //много аргументов в базовом предпологаемом адресе?
            if ("*" == $i)
                return substr($uri, 0, strlen($url) - 1). "*";

            //последний аргумент число?
            $u = explode("/", $uri);
            $i = end($u);
            $e = (int) $i;
            if ((string) $e == $i AND strlen($uri) != strlen($url))
                return substr($uri, 0, strlen($uri) - strlen($i));

            return $uri;
        }
}