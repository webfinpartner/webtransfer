<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Var_model extends CI_Model {

    private function _createIpMask($ip_) {
        $ip = explode('/', $ip_);
        $ip[0] = trim($ip[0]);
        if (!isset($ip[1])) return array($ip_, 4294967295, ip2long($ip[0]));
        $ip[1] = (int) $ip[1];
        if (0 == $ip[1]) return array($ip_, 0, ip2long($ip[0]));
        $bit_mask = '';
        for ($c = 32; 0 < $c; $c--) {
            $bit_mask .= (0 < $ip[1]) ? '1' : '0';
            $ip[1]--;
        }
        return array($ip_, bindec($bit_mask), ip2long($ip[0]));
    }

    public function get($name) {
        return $this->db
                ->select($name)
                ->get("settings")
                ->row()
                ->$name;
    }

    public function get_kontract_count() {
        $count = $this->get("kontract_count");
        $count++;
        $this->db->update("settings", array("kontract_count" => $count));
        return $count;
    }

    public function get_ip($name) {
        $ips = $this->get($name);
            if(empty($ips)) return array();
            if("," == $ips[strlen($ips)-1]) $ips[strlen($ips)-1] = "";

        $ip_list = explode(', ',$ips);
        $res = array();
        foreach ($ip_list as $ip)
            $res[] = $this->_createIpMask($ip);
        return $res;
    }

    public function set($name, $val) {
        return $this->db->update("settings", array($name => $val));
    }

}
