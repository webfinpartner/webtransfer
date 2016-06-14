<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social_model extends CI_Model {

	public $params;

    public function getParam() {
        return $this->params;
    }
	function setParamData($data)
    {
        $this->params['data'] = $data;
    }
	function setParam($data, $name, $id, $link, $foto, $photo_100 = null) {
		$this->social = $name;
		$this->social_id = $id;
		$this->link = $link;
		$this->foto = $foto;
		if( !$photo_100 && $foto) $this->photo_100 = $foto;
		$this->photo_100 = $photo_100;

		$this->data = $data;
		$array = array('social' => $name, 'social_id' => $id, 'link' => $link, 'foto' => $foto, 'photo_100' => $photo_100);
		$array['data'] = $data;
		$this->params = $array;
		//print_r($this->params);  die();
		return $array;
	}

	public function writeSession() {
		$_SESSION['userData'] = $this->params;
	}

    public function eraseSession() {
        unset($_SESSION['userData']);
        unset($_SESSION['pre_add_social_time']);
        unset($_SESSION['add_social_pre']);
        unset($_SESSION['pre_add_social_name']);
    }
	public function readSession() {
		if (empty($_SESSION['userData']))
			redirect(site_url('/'));

		$data = $_SESSION['userData'];
		//unset($_SESSION['userData']);

		return $this->setParam($data['data'], $data['social'], $data['social_id'], $data['link'], $data['foto']);
	}

	function setEmail($email) {
		$this->params['data']['email'] = $this->data['email'] = $email;
	}

    /**
     * Get user id by it's social id.
     *
     * @param type $social_name
     * @param type $social_id
     * @return boolean
     */
	public function get_user_id( $social_name, $social_id ) {
        if( empty($social_name) || empty( $social_id ))
            return FALSE;

        if( !isset( $this->code ) ) $this->load->library('code');

		$id_user = $this->db->select('id_user')->where(array('name' => $this->code->code($social_name), 'id_social' => $this->code->code($social_id)))
						->get('social_network')->row('id_user');

		if (!empty($id_user))
			return $id_user;

		return FALSE;
	}
	public function check() {
		$id_user = $this->get_user_id($this->social, $this->social_id);
		if (!empty($id_user))
		$user = $this->db->select('id_user, email,  user_pass')->from('users')->where('id_user', $id_user)->get()->row();
//		$user = $this->db->select('email,  user_pass')->from('users')->where('id_user', $id_user)->get()->row();
        dev_log("Social_login check {$this->social}, {$this->social_id} returns {$id_user}");

		if (!empty($user))
			return $user;
		return false;
	}

	public function login() {
            $data = new stdClass();
            $login = $this->base_model->login($this->base_model, false);
            if ($login === true) {

                $soc = $this->accaunt->get_social();
                if (isset($soc[$this->social]))
                    accaunt_message($data, sprintf(_e('Страничка %s уже привязана'),$this->social));
                else {
                    $id_user = $this->accaunt->get_user_id();
                    if ($this->check() === false) {
                        $this->prepaer4Add($id_user);

                        redirect(site_url('account/social_add/'.$id_user));
                    }
                    else
                        accaunt_message($data, sprintf(_e('Страничка %s принадлежит  другому  пользователю'),$this->social) );
                }

                $user = $this->check();

                if (!empty($user)){
                    cookie_chat_token($user->id_user);
                    socialNetworkProcess($user, urlencode(urlencode($user->email)), $user->user_pass);
                }

                $this->load->model('Wtauth_model', 'wtauth');
                $redirect = $this->wtauth->get_redirect($user->id_user);
                if ( !empty($redirect))
                    redirect( $redirect );            



                redirectInvest();

//                if ($_SESSION['landing_enter']==1){
//                    redirect('account/transactions');
//                    unset($_SESSION['landing_enter']);
//                } else
                 redirect(site_url('account/social'));
            }

            $user = $this->check();
            if (!empty($user)) {
                    // Авторизация
                    cookie_log($user->email, $user->user_pass, 1, $user->id_user);
                    socialNetworkProcess($user, urlencode(urlencode($user->email)), $user->user_pass);
                    
                    $this->load->model('Wtauth_model', 'wtauth');
                    $redirect = $this->wtauth->get_redirect($user->id_user);
                    if ( !empty($redirect))
                        redirect( $redirect );                    
                    
                    if(isset($_SESSION['merchant']) && ((30*60) >= (time() - $_SESSION['merchant']['time'])))
                         redirect(site_url('merchant/pay'));
                     else{
                         redirectInvest();
                      //  redirect(site_url('account')); //
                        if ($_SESSION['landing_enter']==1){
                            redirect('account/transactions');
                            unset($_SESSION['landing_enter']);
                        } else {
                          //redirect(site_url('page/about/blog')); // TEMP REDIRECT
                          redirect(base_url('social/profile'). '/?lang='._e('lang') . '&t=' . time());
                        }
                    }
            }
	}

        public function prepaer4Add($id_user) {
            $_SESSION['add_social_pre'] = ["id_user" => $id_user,
			'name' => $this->code->code($this->social),
			'id_social' => $this->code->code($this->social_id),
			'url' => $this->code->code($this->link),
			'foto' => $this->code->code($this->foto),
			'photo_100' => $this->code->code($this->photo_100)
		];
        
            $_SESSION['wtauth_social_info'] = [
                'id_user' => $id_user,
                'name' => $this->social,
                'id_social' => $this->social_id,
                'url' => $this->link,
                'foto' => $this->foto,
                'photo_100' => $this->photo_100
            ];
                
            $_SESSION['pre_add_social_name'] = $this->social;
            $_SESSION['pre_add_social_time'] = time();
        }

        public function check2Auth4Add($id_user, $code) {
            $data = new stdClass();
            require_once APPPATH.'controllers/user/Security.php';
            $own = $this->users->getCurrUserId();
            if($id_user != $own){
                accaunt_message($data, _e('Вы не можете привязать социальную сеть - не верный пользователь'), 'error');
                return false;
            }
            $_POST['code'] = $code;
            if(Security::checkSecurity($own)) return false;

            if($this->add4Session($id_user))
                accaunt_message($data, sprintf(_e('Страничка %s была привязана'),$_SESSION['pre_add_social_name']), 'good');
            else
                accaunt_message($data, sprintf(_e('Произошла ошибка при привязке страницы социальной сети')), 'error');

            redirect(site_url('account/social'));
        }

	public function checkEmail() {
		$email = $this->data['email'];
		if (empty($email))
			return false;
		$user = $this->db->select('id_user')->from('users')->where('email', $this->code->code($email))->get()->row();
		if (empty($user->id_user))
			return true;

		$this->add($user->id_user);
		$this->login();
	}

	public function add($id_user) {
            $this->db->insert('social_network', array("id_user" => $id_user,
                    'name' => $this->code->code($this->social),
                    'id_social' => $this->code->code($this->social_id),
                    'url' => $this->code->code($this->link),
                    'foto' => $this->code->code($this->foto),
                    'photo_100' => $this->code->code($this->photo_100)
            ));

            $this->eraseSession();
	}
	public function add4Session($id_user) {
            if((isset($_SESSION['pre_add_social_time']) && time() > $_SESSION['pre_add_social_time']+5*60) || !isset($_SESSION['pre_add_social_time'])) return false;
            if(!isset($_SESSION['add_social_pre'])) return false;

            $stored = $_SESSION['add_social_pre'];
            if($stored['id_user'] != $id_user) return false;

            $this->db->insert('social_network', $stored);

            $this->eraseSession();
            return true;
	}
	public function renewUserPhoto( ){
		$this->own()
			->where('id_social', $this->code->code($this->social_id) )
			->update('social_network',
				array('foto' => $this->code->code($this->foto)),
				array('photo_100' => $this->code->code($this->photo_100))
			);
	}



    public function get_all_user_socials($user_id)
    {
       if( empty($user_id) )
            return FALSE;

//        if( !isset( $this->code ) ) $this->load->library('code');

        $socials = $this->db
                ->where('id_user', $user_id)
                ->get('social_network')
                ->result();

        if (!empty($socials))
                return $socials;

        return FALSE;
    }
}
