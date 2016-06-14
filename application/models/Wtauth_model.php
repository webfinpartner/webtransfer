<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wtauth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_redirect($user_id){
        if ( isset($_SESSION['wtauth_simple']) ){
            $redirect = '/'.$_SESSION['wtauth_simple']['redirect'];
            unset($_SESSION['wtauth_simple']);
            return $redirect;
        }

        if ( isset($_SESSION['wtauth']) ){
            $user = ['id_user' => $user_id, 'social' => $_SESSION['wtauth_social_info'] ];

            $redirect = $_SESSION['wtauth']['redirect'];
            $app_id = $_SESSION['wtauth']['app_id'];

            $token = md5( $user->id_user.time().rand(1,100000));
            $code = md5(rand(10000,99999).'_'.time().'_'.$app_id);


            $this->db->insert('wtauth', ['code'=> $code, 'app_id' => $app_id, 'token'=>$token, 'user_data'=>  json_encode($user), 'dttm'=>date('Y-m-d H:i:s')]);
            unset($_SESSION['wtauth']);

            return $redirect.'code='.$code;
        }
        return FALSE;
    }
    
    
    
    public function set_whitelabel($id, $name, $host, $logo){
        
        
        $GLOBALS['WHITELABEL'][$host] = ["id" => $id, 'name' => $name];
        $GLOBALS['WHITELABEL_IDS'][$id] = $name;
        $GLOBALS['WHITELABEL_HOST_IDS'][$id]  = ['host' => $host, 'name' => $name];
        $GLOBALS["WHITELABEL_ID"] = $id;
        $GLOBALS["WHITELABEL_NAME"] = $name;        
        $GLOBALS["WHITELABEL_LOGO"] = $logo;        
        
    }

    public function get_site_by_appid($app_id){
        $r = $this->db->where('id', $app_id)->get('wtauth_sites')->row();
        if ( !empty($r) && !empty($r->master) && !empty($r->name) )
            $this->set_whitelabel($r->master, $r->name, $r->url, $r->logo);
        return $r;
    }

    public function  get_auth_by_code( $code ){
        return $this->db->where('code', $code)->get('wtauth')->row();
    }

    public function  get_auth_by_token( $token ){
        return $this->db->where('token', $token)->get('wtauth')->row();
    }

    public function checkAndProcessCode($param, $secure = false) {
        if ( empty( $param->site) ){
            return [
                     'error'=>'unknown_app_id',
                     'error_description' => 'Unknown app_id'
            ];
        }

        /*$referrer = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $url_params = parse_url( $referrer );
        if ( $url_params['host'] != $site->url ){
            return ['status'=>'error',
                     'error' => 'Bad referer'.print_r([$app_id, $redirect, $secret, $code,$referrer ], true)
                     ];

        }*/

        $url_params = parse_url( $param->redirect );
        if ( $url_params['host'] != $param->site->url ){
            return [
                'error'=>'bad_redirect_url',
                'error_description' => 'Bad redirect URL'
            ];
        }

        if ((false == $secure && $param->site->secret_key != $param->secret) ||
            (false != $secure && md5($param->time.$param->code.$param->site->secret_key) != $param->secret)){
            return ['error'=>'invalid_secret',
                'error_description' => 'Invalid secret'
            ];
        }

        $r = $this->wtauth->get_auth_by_code( $param->code );
        if ( empty($r) ){
            return [
                'error'=>'bad_code',
                'error_description' => 'Bad code'
            ];
        }

        $user_id = NULL;

        $user_data = json_decode($r->user_data);
        if (!empty( $user_data )){
            $user_id = $user_data->id_user;
            $social_info = $user_data->social;
            
            $this->load->model('users_model', 'users');
            $user_data = $this->users->getUserData($user_id);
            $surname = $user_data->sername;
            $name = $user_data->name;
            $email = $user_data->email;
            $phone = $user_data->phone;
        }

        $this->load->model('Chat_auth_tokens_model');
        $chat_token = $this->Chat_auth_tokens_model->get_by_user($user_id);

        return [
            'expires_in' => time()+60*60,
            'token' => $r->token,
            'user_id' => $user_id,
            'surname' => $surname,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'social' => $social_info,
            'set_cookies' => [
               'chat_token' => $chat_token
            ]
        ];
    }

    public function prepareRedirect($redirect) {
        if ( strpos($redirect, '?') !== false )
            $redirect.= '&';
        else
            $redirect.= '?';

        return $redirect;
    }

    public function logout($token) {
        $r = $this->wtauth->get_auth_by_token( $token );
        if ( !empty($r) ){
            logout();
            return [
                'logout'=>'success'
            ];
        } else {
            return [
                'error'=>'bad_token',
                'error_description' => 'Bad token'
            ];
        }
    }

}
