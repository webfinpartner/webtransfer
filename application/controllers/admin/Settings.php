<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Settings extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $data = array(
            'email',
            'telephon',
            'p_new_credior',
            'p_old_credior',
            'vip_credior',
            'shtraf',
            'foto',
            'sms',
            'flc',
            'flp',
            'slp',
            'regpartner',
            'ip_firewall',
            'ip_white',
            'wirebank_emails',
            'vdna_emails'
        );

        $setting = array('ctrl' => 'settings',
            'view' => 'settings',
            'table' => 'settings',
            'argument' => $data,
            'image' => 'logo'
        );
        $this->index_redirect = true;
        $this->setting($setting);
    }

    function all($param) {
        show_404();
    }

    function delete($param) {
        show_404();
    }

    function create($param) {
        show_404();
    }

}
