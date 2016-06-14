<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Permissions
 *
 * @author esb
 * @date 29.07.2014
 */
require_once APPPATH.'libraries/Spyc.php';

class Permissions {

    private $_permissions = NULL;
    private $_permissions_users = NULL;
    private $_URLs = array();
    private $_IDs = array();
    private static $_me = NULL;
    private $_adminPermission = NULL;
    private $_id_user = NULL;
    private static $_ci = null;
    private static $var = null;

    public static $SEC_TYPES = [
        'sms' => 'СМС',
        'app' => 'Webtransfer App'
    ];





    /**
     *
     * @return Permissions
     */
    public static function getInstance(){
        if(!self::$_me) self::$_me = new Permissions();

        return self::$_me;
    }

    /**
     *
     * @return Permissions
     */
    public static function resetInstanse($user_id) {
        self::$_me = new Permissions($user_id);

        return self::$_me;
    }

    private function __construct($user_id = null){
        self::$_ci = CI_Controller::get_instance();
        $this->_permissions = Spyc::YAMLLoad(APPPATH.'config/permissions.yml',-1);
        $this->_permissions_users = Spyc::YAMLLoad(APPPATH.'config/rolies.yml',-1);

        self::$_ci->load->library('code');
	$this->_id_user  =  (!empty($_COOKIE[COOKIE_ADMIN])) ? self::$_ci->code->decrypt($_COOKIE[COOKIE_ADMIN]) : $user_id;
        $this->_adminPermission = self::$_ci->db->get_where("admin",array('id_user'=>$this->_id_user))->row('permission');
        $this->_admin = self::$_ci->db->get_where("admin",array('id_user'=>$this->_id_user))->row();
        if (!$this->_adminPermission) return;
        $this->_colculateURLs();
        $this->_calculateIDs();
    }

    public function getAdminId() {
        return $this->_id_user;
    }

    public function getAdmin() {
        return $this->_admin;
    }

    public function getURLs() {
        return $this->_URLs;
    }

    public function getIDs() {
        return $this->_IDs;
    }

    public function getPermissions() {
        return $this->_permissions;
    }

    public function getRolesOptions($cur) {
        $r = '';
        foreach ($this->_permissions_users as $key => $content) {
            $s = ($cur == $key) ? "selected='selected'": "";
            $r .= "<option $s value='$key'>".$content["name"]."</option>".PHP_EOL;
        }
        return $r;
    }

//    private function _getGroups() {
//        foreach ($this->_permissions as $right => $content) {
//            if("group" == $content['type']) $groups[$right] = $content;
//        }
//    }

    private function _colculateURLs() {
        $rights = $this->_permissions_users[$this->_adminPermission];
        $urls = array();
        foreach ($this->_permissions as $right => $content)
            if(isset($content["url"])) $urls[$right] = $content["url"];


        if ("all allow" == $rights["permission"])
            $this->_URLs = $urls;

        if(empty($rights["pages"])) return;

        foreach ($rights["pages"] as $id => $value) {
            $this->_setURL($value, $id, $urls);

            if(isset($this->_permissions[$id]["items"]))
                foreach ($this->_permissions[$id]["items"] as $sId => $name)
                    $this->_setURL($value, $sId, $urls);
        }

        return;


    }

    private function _setURL($type, $id, array $urls) {
        if ("deny" == $type) unset($this->_URLs[$id]);
        if ("allow" == $type AND isset($urls[$id])) $this->_URLs[$id] = $urls[$id];
    }


    private function _calculateIDs(){
        $rights = $this->_permissions_users[$this->_adminPermission];
        $ids = array();
        foreach ($this->_permissions as $right => $content) {
            if("page" == $content['type'] OR
                "resource" == $content['type'] OR
                ("group" == $content['type'] AND
                    "" == $content['display']))
                $ids[$right] = $right;
        }

        if ("all deny" == $rights["permission"])
            $this->_IDs = $ids;

        if(!empty($rights["pages"])){
            foreach ($rights["pages"] as $id => $value) {
                $this->_setId($value, $id);

                if (isset($this->_permissions[$id]["items"]))
                    foreach ($this->_permissions[$id]["items"] as $sId => $name)
                            $this->_setId($value, $sId);
            }
        }

        $this->_createRealId();

        return;
    }

    private function _setId($type, $id) {
        if ("deny" == $type) $this->_IDs[$id] = $id;
        if ("allow" == $type){
            unset($this->_IDs[$id]);


            $up = explode(".", $id);
            $s = $id;
            foreach ($up as $key => $idPart) {
                $s = substr($s, 0, strrpos($s, ".", -1));
                unset($this->_IDs[$s]);
            }
        }
    }

    private function _createRealId() {
        $hideGroups = array();
        foreach ($this->_permissions as $right => $content)
            if("group" == $content['type'] AND "hide" == $content['display'])
                $hideGroups[$right] = $content;

        $realId = array();
        foreach ($hideGroups as $addId => $content) {
            $id4hGroup = array();
            foreach ($content["items"] as $id => $name)
                $id4hGroup = $this->_getGroup($id, $id4hGroup); //получили все елементы этой группы

            $id4hGroup = array_diff($this->_IDs, array_diff($this->_IDs, $id4hGroup));
            $id4hGroup = array_keys($id4hGroup);
            array_walk($id4hGroup, function(&$item, $key, $addId){$item = "$addId.$item"; $item = str_replace(".", "_", $item);},$addId);
            $realId = array_merge ($realId, $id4hGroup);
        }

        $this->_IDs = $realId;
    }

    private $_r = 0;

    private function _getGroup($id, array $groupId) {
        $this->_r++;
        if(1000 < $this->_r) {print_r($this->_r);die;}

        $groupId[$id] = $id;

        if(isset($this->_permissions[$id]["items"]))
            foreach ($this->_permissions[$id]["items"] as $sId => $name)
                $groupId = array_merge ($groupId, $this->_getGroup($sId, $groupId));

        return $groupId;
    }


    static public function setProtectType($user_id, $new_protect_type) {
        $ci->load->model("admin_model", "admin");
        $cur = $ci->admin->getAdminById($user_id)->sec_auth;
        if ( $cur == $new_protect_type )
            return false;
        else {
            return $ci->admin->setProtectType($user_id, $new_protect_type);
        }
    }

    static public function getProtectType($user_id) {
        if(!empty(self::$var)) return self::$var;
        $ci = self::$_ci;
        $ci->load->model("admin_model", "admin");
        self::$var = $ci->admin->getAdminById($user_id)->sec_auth;
        return self::$var;
    }

    static public function checkSecurity($user_id, $isAjax = false) {
        $protection_type = self::getProtectType($user_id);
        $ci = self::$_ci;
        $ci->load->model("admin_model", "admin");
        switch ($protection_type) {
            case 'sms': return self::checkSms($user_id, $isAjax);
            case 'app':
                $user_id =  $ci->admin->getAdminById($user_id)->users_user_id;
                return self::checkCode($user_id, $isAjax);
            default: return false;
        }

    }

    static private function checkSms($user_id, $isAjax = false) {
        $ci = self::$_ci;
        $code = $ci->input->post('code');
        $ci->load->model("admin_model", "admin");
        return $ci->admin->checkSms($user_id, $code);
    }

    static private function checkCode($user_id, $isAjax = false) {
        $ci = get_instance();
        $ci->load->model('security_model', 'security_model');
        $code = $ci->input->post('code');
        $ci->load->library("hotp");
        $headers=get_headers("https://www.google.com.ua");
        $date = explode(":", $headers[1],2);
        $time = "";
        if("Date" == $date[0]) $time = trim($date[1]);
        $window = 30;
        $key = $ci->security_model->getProtectTypeByAttrName('code_secret', $user_id);
        $htop = HOTP::generateByTime($key, $window, strtotime($time));
        $length = 6;
        $r = $htop->toHotp($length);
        if($code == $r) return true;
        if(!$isAjax) accaunt_message($data, "Код двух этапной авторизации не совпадает", 'error');
        return false;
    }
}
