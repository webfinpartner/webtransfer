<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_model extends CI_Model {

    protected $id_user, $private_path, $common_path, $path;

    function __construct() {
        parent::__construct();
        //new path for logs
        $date = date('Y-m-d');
        $this->path = "application/logs/$date/";
        if( !file_exists($this->path) ) mkdir( $this->path );

        //looking list
        $this->looking_list_path = 'application/logs/looking_list.txt';

        $this->common_path = $this->path."common.log";
        $this->load->model('admin_model','admin');
    }
    public function loadLookingList() {
        if( !isset($this->looking_list_path) || !file_exists($this->looking_list_path) ) return false;
        $list = file_get_contents( $this->looking_list_path );
        $this->looking_list = explode("\n", $list);

        if( empty( $this->looking_list ) ) return false;

        foreach( $this->looking_list as $one )
            if( $one == '*' ){
                $this->is_everyone = TRUE;
                break;
            }

        return true;
    }

    public function log( $login, $note, $type = 'common', $user_email = null ){
        if( empty($login) || $login == null ){
            if( !isset( $this->admin_info ) ){
                $this->admin_info = $this->admin->get_admin_state();
            }
			$login = ($this->admin_info == null)? null : $this->admin_info->login;
        }

        $user_id = '';
        if($user_email != null)
            if(is_numeric($user_email) ){
                $user_id = $user_email;
            }else{
                $this->load->model('users_model','users');
                $user_id = $this->users->getUserByEmail( $user_email );
            }


        if( (empty($login) || $login == null ) && $user_id != null){
            if( $this->loadLookingList() && isset( $this->looking_list )
                && (in_array($user_id, $this->looking_list) || $this->is_everyone )) $login = $user_id;
                else
                    return;
        }
        if( empty($user_id) || $user_id == null ){
            if(!isset( $this->account ))$this->load->model('accaunt_model','accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if( empty($login) || $login == null ) $login = 'anonimus';

        $this->load->model("users_model", 'users');
        $ip = $this->users->getIpUser();

        if( !is_array($type) ) $type = array( $type );

        if( in_array('common', $type) ){
            $common_f = fopen( $this->common_path, 'a+');
            $common_note = date('Y-m-d H:i:s')." : $ip : $login : $user_id : $note\r\n";

            if( $common_f && fwrite($common_f, $common_note) )
                fclose( $common_f );
        }

        if( in_array('private', $type) ){
            $this->private_path = $this->path."$login.log";
            $private_f = fopen( $this->private_path, 'a+');
            $private_note = date('Y-m-d H:i:s')." : $ip : $user_id : $note\r\n";

            if( $private_f && fwrite($private_f, $private_note) )
                fclose( $private_f );
        }
    }


}
