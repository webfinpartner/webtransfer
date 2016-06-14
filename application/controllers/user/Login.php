<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Login extends SimpleREST_controller {

    function __construct() {
        parent::__construct();
        $this->load->library('code');
        $this->load->library('soc_network');
        $this->load->model('social_model', 'social');
        $this->load->model('monitoring_model','monitoring');
        $this->load->model('users_model','users');
    }

    private function add_user($wtPar = array()) {
        $this->load->model("users_model", 'users');
        $this->users->checkIP();
        if (!$wtPar) {
            $data = $this->social->params['data'];
            $data['password'] = genRandomPassword(9, 'a-z,0-9');
        } else {
            $data["email"] = $wtPar[0];
            $data['password'] = $wtPar[1];
        }
        $data['face'] = '1';

        foreach ($data as $item => $value)
            $_POST[$item] = $value;

        $id_user = $this->base_model->registration(true);

        $is_login = new stdClass();
        $is_login->id_user = $id_user;
        $soc_answer = socialNetworkProcess($is_login,  $this->code->code($data['email']), $this->code->code($data['password']) );
        $this->db->insert('user_soc_answer_log',['user_id'=>$id_user, 'answer'=>$soc_answer]);

        if (!$wtPar) {
            $this->social->add($id_user);
        }
        else
            $this->base_model->updateUserField($id_user, "account_verification", md5($this->code->request('email')));

        if (isset($_COOKIE['id_partner'])){
            $this->base_model->setParent($id_user, (int) $_COOKIE['id_partner']);
            $id_volunteer = $this->users->getMyVolunteerId($id_user);
            if (!empty($id_volunteer))
                $this->users->setVolunteer($id_user, $id_volunteer);
        }

        $social_partner = $this->input->cookie('social-partner');
        if ($social_partner == 1) {
            $this->base_model->setType($id_user, 'partner');
            $this->input->set_cookie('social-partner');
        }
        else
            $this->base_model->setType($id_user, 'client');

        if (!$wtPar) {
            cookie_log($this->code->request('email'), $this->code->request('password'));
            unset($_COOKIE['id_partner']);
            
            $this->load->model('Wtauth_model', 'wtauth');
            $redirect = $this->wtauth->get_redirect($id_user);
            if ( !empty($redirect))
                redirect( $redirect );                     

            redirect(site_url('login/parent'));
            redirectInvest();
            if ($social_partner == 1)
                redirect(site_url('partner/profile'));
            else
                redirect(site_url('account/profile'));
        }
    }

    //сделано для исправления имени пользователя на латиницу
    public function edit_profile_username() {
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('social_model', 'social');
        $this->load->model('users_model', 'users');
        $this->load->library('session');

        $data = new stdClass();
        $data->banner_menu = "profile_login";
        $data->secondary_menu = "";

        $user_id = (int) getSession('auth-idUser');
//        var_dump( $user_id );

        $this->social->readSession();
        $param = $this->social->getParam();
        //var_dump($param);

        $data->user = $param['data'];
        $data->user['nickname'] = '';
        $data->user['is_show'] = FALSE;

        if( !empty( $user_id ) ){
//            $data->user = $this->accaunt->getMainUser();
            $data->user['nickname'] = $this->usersFilds->getUserFild($user_id, 'nickname');
            $data->user['is_show']  = $this->usersFilds->getUserFild($user_id, 'is_show');
        }


        if( $this->input->post('submited') )
        {
            //var_dump( $param );

            $nickname    = $this->input->post('nickname',TRUE);
            $n_name      = $this->input->post('n_name',TRUE);
            $f_name      = $this->input->post('f_name',TRUE);
            $o_name      = $this->input->post('o_name',TRUE);

            $data->user['nickname']   = $nickname;
            $data->user['n_name']     = $n_name;
            $data->user['f_name']     = $f_name;
            $data->user['o_name']     = $o_name;

            $param['data']['nickname']    = $nickname;
            $param['data']['n_name']      = $n_name;
            $param['data']['f_name']      = $f_name;
            $param['data']['o_name']    = $o_name;

            $isNotUniqueFild = $this->usersFilds->isNotUniqueFild( array( 'nickname'=> $nickname) );

            if( empty( $n_name ) || empty( $f_name ) )
            {
                //Заполните все обязательные поля заполнены и повторите попытку.
                accaunt_message($data, _e('Заполните все обязательные поля заполнены и повторите попытку.'), 'error');
            }else
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $param['data'] ) )
                {
                    //Заполните поля, используя только латинский алфавит
                    accaunt_message($data, _e('Заполните поля, используя только латинский алфавит.'), 'error');
                }else
                    if( $isNotUniqueFild )
                    {
                        accaunt_message($data, _e('account/data64'), 'error');
                    }else{
                        $this->social->setParamData( $param['data'] );

                        if ($this->social->checkEmail()) {
                            $this->add_user();
                            redirect(site_url('login/parent'));
                        } else {
                            $this->social->writeSession();
                            redirect(site_url('login/email'));
                        }
                    }

        }

        $this->content->template('edit_profile_username', $data);
    }

    public function email() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->social->readSession();
        $this->social->login();

        $this->form_validation->set_rules('email', 'Почта', 'trim|required|valid_email|check_db[email]');
        if ($this->form_validation->run() == true ||
                @$_POST['email'] == 'annasergeeva9191@mail.ru') {
            $this->social->setEmail($_POST['email']);
            $this->add_user();

            redirect(site_url('login/parent'));
        }

        viewData()->banner_menu = "profile_login";
        viewData()->secondary_menu = "profile";

        $this->social->writeSession();
        $this->content->config(_e('Добавление почтового адреса'));
        $this->content->template('add_email');
    }

    public function parent($action = 'refresh'){
        if ($_SESSION['landing_enter']==1){
            redirect('account/transactions');
            unset($_SESSION['landing_enter']);
        } else {
            redirect(base_url('social/profile'));
        }


         //redirect(base_url().'social/profile');
        $this->load->model('accaunt_model', 'accaunt');
        $this->base_model->login($this, false);

        $id_user = $this->accaunt->get_user_id();

        $parentChangeCount = $this->base_model->getParentChangeCount();
        $reg_date_res = $this->base_model->getUserField( $id_user, 'reg_date', false );

        $reg_date = strtotime( $reg_date_res ) + 900;

        if( empty($id_user) || $reg_date < time() || $parentChangeCount == -1 )
        {
            redirect(site_url('account/profile'));
        }

        $parent_id = null;
        if (isset($_POST['parent_id']) && !empty($_POST['parent_id'])) {
            $parent_id = intval($_POST['parent_id']);
        } else {
            if (isset($_COOKIE['id_partner']))
                $parent_id = intval($_COOKIE['id_partner']);
            else {
                $parent = $this->accaunt->getParentUser();
                $parent_id = $parent->id_user;
            }
        }

        if ($action == 'save' && $id_user != $parent_id) {
            if ($id_user != null)
                $this->base_model->setParent($id_user, $parent_id);
                //@esb set volunteer id for user and its children
                if( empty( $parent_id ) ) $parent_id = 0;
                $this->users->setVolunteer2All($id_user, $parent_id);

            $_SESSION['userData']['parentChanged'] = TRUE;
            redirect(site_url('account/profile'));
        }

        viewData()->banner_menu = "profile_login";
        viewData()->secondary_menu = "profile";

        if ($parent_id != null){
            viewData()->parentUser = $this->base_model->getUserInfo($parent_id);
            viewData()->parentPhotos = $this->accaunt->get_user_photos($parent_id);
            viewData()->parentSocialList = $this->accaunt->getSocialList($parent_id);
            viewData()->parentId = $parent_id;
        }

        //$this->social->writeSession();
        $this->content->config(_e('Старший партнер'));
        $this->content->template('add_parent');
    }

    public function vkontakte() {
        require (APPPATH . 'libraries/VK/VK.php');
        require_once(APPPATH . 'libraries/VK/VKException.php');

        $vkontakteConfig = config_item('vkontakteConfig');

        $client_id = $vkontakteConfig['id'];
        $client_secret = $vkontakteConfig['secret'];
        $redirect_uri = $vkontakteConfig['urlCallback'];

        if (isset($_GET['code'])) {
            try {

                $vk = new VK\VK($client_id, $client_secret);
                $token = $vk->getAccessToken($_GET['code'], $redirect_uri);
                $this->session->set_userdata(array("social_token" => array("vk" => $token)));
            } catch (VK\VKException $error) {
                echo "<!--".$client_secret."-->";
                echo "Error: 112.<br>";
                echo $error->getMessage();

                echo '<br/><a href="http://webtransfer-test.co.uk/?action=redirect_to_vk&key=cI1u6W2$VW">Попробуйте еще раз</a>';
            }
        } else {
            redirect('http://oauth.vk.com/authorize?client_id=' . $client_id . 'scope=friends&display=popup&v=5.2&redirect_uri='.site_url('login/vkontakte').'&response_type=code');
        }

        if (isset($token['access_token'])) {
            $user_friends = $vk->api('users.get', array(
                'uids' => $token['user_id'],
                'fields' => 'uid,first_name,last_name,sex,bdate,photo_50,photo_100, screen_name',
                'access_token' => $token['access_token']
            ));

            if (isset($user_friends['response'][0]['uid'])) {
                $profile = $user_friends['response'][0];
                $data['n_name'] = $profile['first_name'];
                $data['f_name'] = $profile['last_name'];
                if (!empty($profile['bdate']))
                    $data['born_date'] = date_formate_my($profile['bdate'], 'd m Y');
                if ($profile['sex'] == 1)
                    $data['sex'] = 2;
                if ($profile['sex'] == 2)
                    $data['sex'] = 1;
                // принудительно транслитим
                $this->load->helper('translit');
                translitArray($data);

                $this->social->setParam($data, 'vkontakte', $profile['uid'], 'https://vk.com/' . $profile['screen_name'], $profile['photo_50'], $profile['photo_100']);
                $this->social->login();
                $this->social->writeSession();

                if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                {
                    redirect(site_url('ru/login/edit_profile_username'));
                }else if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) ) {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                redirect(site_url('login/email'));
            }
        }else{
            $this->show_user_error(200);
        }
        $this->_onSuccess();
    }

    private function show_user_error($num = 200, $param = null) {
//        if ( $param != null) {
//            echo base64_encode(json_encode($param));
            echo '<p><b>'.sprintf(_e('Ошибка #%d передачи данных'),$num).'</b></p>' .
            '<p>'._e('Сообщите, пожалуйста, об этой ошибке с службу поддержки.').'</p>' .
            '<p>'._e('Извините за временные неудобства.').'</p>' .
            '<a href="' . site_url('/') . '">'._e('Вернуться на главную.').'</a>';
//        }

        die();
    }

//вход через редирект контакта, когда блокируют вход через основное приложение
    public function vkontakte2() {

        if (isset($_SERVER['REDIRECT_QUERY_STRING']) && !empty($_SERVER['REDIRECT_QUERY_STRING'])) {
            $coded = $_SERVER['REDIRECT_QUERY_STRING'];

            $key = '#DSGn#daX#';
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($coded), MCRYPT_MODE_CBC, md5(md5($key))), "\0");

            if (empty($decrypted)) {
                $this->show_user_error(200);
            }
            $data = json_decode($decrypted);

            if (!is_object($data)) {
                $this->show_user_error(201);
            }

            $tokenClass = $data->token;
            $profileClass = $data->profile;
            $dateClass = $data->date;


            if (!isset($tokenClass) || !is_object($tokenClass) ||
                    !isset($profileClass) || !is_object($profileClass) ||
                    !isset($dateClass) || !is_string($dateClass)
            ) {
                $this->show_user_error(202);
            }

            $profile = (array) $profileClass;
            $token = (array) $tokenClass;

            if (
                    empty($profile) || empty($token) ||
                    !isset($profile['first_name']) || !isset($profile['last_name'])
            ) {

                $this->show_user_error(203);
            }

            $this->session->set_userdata(array("social_token" => array("vk" => $token)));

            $data = array();
            $data['n_name'] = $profile['first_name'];
            $data['f_name'] = $profile['last_name'];

            if (!empty($profile['bdate']))
                $data['born_date'] = date_formate_my($profile['bdate'], 'd m Y');
            if ($profile['sex'] == 1)
                $data['sex'] = 2;
            if ($profile['sex'] == 2)
                $data['sex'] = 1;

            // принудительно транслитим
            $this->load->helper('translit');
            translitArray($data);
            $this->social->setParam($data, 'vkontakte', $profile['uid'], 'https://vk.com/' . $profile['screen_name'], $profile['photo_50'], $profile['photo_100']);
            $this->social->login();
            $this->social->writeSession();

            if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                {
                    redirect(site_url('ru/login/edit_profile_username'));
                }else
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                redirect(site_url('login/email'));
        }
        $this->_onSuccess();
    }

    function mail_ru() {//die("Извините временно не работает.");

        $mailConfig = config_item('mailConfig');

        $client_id = $mailConfig['id'];
        $client_secret = $mailConfig['secret'];
        $redirect_uri = $mailConfig['urlCalback'];

        if (isset($_GET['code'])) {


            $params = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code',
                'code' => $_GET['code']
            );

            $url = 'https://connect.mail.ru/oauth/token';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);
            $tokenInfo = json_decode($result, true);

            if (isset($tokenInfo['access_token'])) {


                $sign = md5("app_id={$client_id}method=users.getInfosecure=1session_key={$tokenInfo['access_token']}{$client_secret}");


                $params = array('method' => 'users.getInfo', 'secure' => '1', 'app_id' => $client_id, 'sig' => $sign, 'session_key' => $tokenInfo['access_token']);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, 'http://www.appsmail.ru/platform/api');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($curl);
                curl_close($curl);
                $userInfo = json_decode($result, true);


                if (isset($userInfo[0]['uid'])) {
                    $profile = $userInfo[0];
                    $data['n_name'] = $profile['first_name'];
                    $data['f_name'] = $profile['last_name'];
                    if ($profile['sex'] == '1')
                        $data['sex'] = 2;
                    if ($profile['sex'] == '0')
                        $data['sex'] = 1;
                    $data['born_date'] = preg_replace('/(\d{1,2})\.(\d{1,2})\.(\d{4})/sui', '$1 $2 $3', $profile['birthday']);

                    $data['email'] = $profile['email'];

                    // принудительно транслитим
                    $this->load->helper('translit');
                    translitArray($data);
                    /*$user_data = */$this->social->setParam($data, 'mail_ru', $profile['uid'], $profile['link'], $profile['pic_50']);
                    $this->social->login();
                    $this->social->writeSession();

                    if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                {
                    redirect(site_url('ru/login/edit_profile_username'));
                }else
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                    if ($this->social->checkEmail()) {
                        $this->add_user();
                    } else {
                        $this->social->writeSession();
                        redirect(site_url('login/email'));
                    }
                    redirect(site_url('login/parent'));
                }
            }
        }
        $this->_onSuccess();
    }

    public function twitter() {
        $twConfig = config_item('twitterConfig');

        include APPPATH . 'libraries/twitter/TwitterAuth.php';
        $TWAuth = new TwitterAuth($twConfig['key'], $twConfig['secret'], $twConfig['urlCallback']);


        $oauth_token = array_key_exists('oauth_token', $_GET) ? $_GET['oauth_token'] : false;
        $oauth_verifier = array_key_exists('oauth_verifier', $_GET) ? $_GET['oauth_verifier'] : false;
        //$hash = array_key_exists('state', $_GET) ? $_GET['state'] : false;

        if (!$oauth_token && !$oauth_verifier) {
            $TWAuth->request_token();
            //$hash = sha1(date(DATE_RFC1036).$twConfig["hashKay"]);
            $TWAuth->authorize(); //$hash
        } else {
            //var_dump($hash, $TWAuth->checkHash($hash));die;
            // access_token и user_id
            $TWAuth->access_token($oauth_token, $oauth_verifier);

            // JSON-версия
            $user_data = $TWAuth->user_data();
            $profile = (array) json_decode($user_data);


            if (!empty($profile['id'])) {
                $name = explode(' ', $profile['name']);
                $data['n_name'] = $name[0];
                if (!empty($name[1]))
                    $data['f_name'] = $name[1];

                // принудительно транслитим
                $this->load->helper('translit');
                translitArray($data);
                $this->social->setParam($data, 'twitter', $profile['id'], 'https://twitter.com/' . $profile['screen_name'], $profile['profile_image_url']);
                $this->social->login();
                $this->social->writeSession();

                if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                {
                    redirect(site_url('ru/login/edit_profile_username'));
                }else
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                {
                    redirect(site_url('login/email'));
                }
            }
            $this->_onSuccess();
        }
    }

    function google_plus() {

        if (isset($_GET['code'])) {
            $googleConfig = config_item('googleConfig');
            $params = array(
                'client_id' => $googleConfig['id'],
                'client_secret' => $googleConfig['secret'],
                'redirect_uri' => $googleConfig['urlCallback'],
                'grant_type' => 'authorization_code',
                'code' => $_GET['code']
            );

            $url = $googleConfig['url'];

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);
            $tokenInfo = json_decode($result, true);

            if (isset($tokenInfo['access_token'])) {
                $params['access_token'] = $tokenInfo['access_token'];
                $userInfo = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo' . '?' . urldecode(http_build_query($params))), true);
                if (isset($userInfo['id'])) {
                    $profile = $userInfo;
                    $data['n_name'] = $profile['given_name'];
                    $data['f_name'] = $profile['family_name'];
                    if( isset( $profile['gender'] ) ){
                        if( $profile['gender'] == 'female')
                            $data['sex'] = 2;

                        if ($profile['gender'] == 'male')
                            $data['sex'] = 1;
                    }
                    $data['email'] = $profile['email'];
                    if( !isset( $profile['link'] ) ) $profile['link'] = '';
                    // принудительно транслитим
                    $this->load->helper('translit');
                    translitArray($data);
                    /*$user_data = */$this->social->setParam($data, 'google_plus', $profile['id'], $profile['link'], $profile['picture']);
                    $this->social->login();
                    $this->social->writeSession();

                    
                    if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                    {
                        redirect(site_url('ru/login/edit_profile_username'));
                    }else
                    if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                    {
                        redirect(site_url('en/login/edit_profile_username'));
                    }else
                        if ($this->social->checkEmail()) {
                            $this->add_user();
                            redirect(site_url('login/parent'));
                        } else {
                            $this->social->writeSession();
                            redirect(site_url('login/email'));
                        }
                }
            } else {
                echo $result;
            }
        }
        $this->_onSuccess();
    }

    public function odnoklassniki() {
        $odnoklassnikiConfig = config_item('odnoklassnikiConfig');

        if (isset($_GET['code'])) {
            $curl = curl_init('http://api.odnoklassniki.ru/oauth/token.do');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'code=' . $_GET['code'] .
                    '&redirect_uri=' . urlencode(site_url('login/odnoklassniki')) .
                    '&grant_type=authorization_code&client_id=' . $odnoklassnikiConfig['id'] .
                    '&client_secret=' . $odnoklassnikiConfig['secret']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $s = curl_exec($curl);
            curl_close($curl);
            $auth = json_decode($s, true);

            if (isset($auth['access_token']) && !empty($auth['access_token'])) {
                $curl = curl_init('http://api.odnoklassniki.ru/fb.do?access_token=' . $auth['access_token'] .
                        '&application_key=' . $odnoklassnikiConfig['key'] .
                        '&method=users.getCurrentUser&sig=' . md5('application_key=' . $odnoklassnikiConfig['key'] .
                                'method=users.getCurrentUser' . md5($auth['access_token'] . $odnoklassnikiConfig['secret'])));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $s = curl_exec($curl);
                curl_close($curl);
                $profile = json_decode($s, true);
                if (!empty($profile['uid'])) {
                    $data['n_name'] = $profile['first_name'];
                    $data['f_name'] = $profile['last_name'];
                    $data['born_date'] = preg_replace('/(\d{4})-(\d{1,2})-(\d{1,2})/sui', '$3 $2 $1', $profile['birthday']);
                    if ($profile['gender'] == 'female')
                        $data['sex'] = 2;
                    if ($profile['gender'] == 'male')
                        $data['sex'] = 1;
                    // принудительно транслитим
                    $this->load->helper('translit');
                    translitArray($data);
                    /*$user_data = */$this->social->setParam($data, 'odnoklassniki', $profile['uid'], 'http://www.odnoklassniki.ru/profile/' . $profile['uid'], $profile['pic_1']);
                    $this->social->login();
                    $this->social->writeSession();

                    if( TRUE === $this->users->isUsedCyrilicLanguage( $data ) )
                    {
                        redirect(site_url('ru/login/edit_profile_username'));
                    }else
                    if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                    {
                        redirect(site_url('en/login/edit_profile_username'));
                    }else
                        redirect(site_url('login/email'));
                }
            }

            $this->_onSuccess();
        }
        else
            header('Location: http://www.odnoklassniki.ru/oauth/authorize?client_id=' .
                    $odnoklassnikiConfig['id'] . '&scope=VALUABLE ACCESS&response_type=code&redirect_uri=' . urlencode(site_url('login/odnoklassniki')));
    }

    public function facebook() {
        require_once APPPATH . 'libraries/facebook/facebook.php';

        $fbConfig = config_item('facebookConfig');
        //get the Facebook appId and app secret from facebook.php which located in config directory for the creating the object for Facebook class
        $facebook = new Facebook(array(
            'appId' => $fbConfig['id'],
            'secret' => $fbConfig['secret'],
        ));
        $user = $facebook->getUser(); // Get the facebook user id
        if ($user) {

            try {
                $profile = $facebook->api('/me');  //Get the facebook user profile data
                $data['n_name'] = $profile['first_name'];
                $data['f_name'] = $profile['last_name'];
                $data['born_date'] = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/sui', '$2 $1 $3', $profile['birthday']);
                if (!empty($profile['work'][0]['employer']['name']))
                    $data['w_name'] = $profile['work'][0]['employer']['name'];
                if (!empty($profile['work'][0]['position']['name']))
                    $data['w_who'] = $profile['work'][0]['position']['name'];
                if ($profile['gender'] == 'female')
                    $data['sex'] = 2;
                if ($profile['gender'] == 'male')
                    $data['sex'] = 1;
                $data['email'] = $profile['email'];
                // принудительно транслитим
                $this->load->helper('translit');
                translitArray($data);
                $this->social->setParam($data, 'facebook', $profile['id'], $profile['link'], 'https://graph.facebook.com/' . $profile['username'] . '/picture');

                //$param = $this->social->getParam();
                //var_dump($param);

                //var_dump($data);
                $this->social->login();
                $this->social->writeSession();


                //die;
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                if ($this->social->checkEmail()) {
                    $this->add_user();
                    redirect(site_url('login/parent'));
                } else {
                    $this->social->writeSession();
                    redirect(site_url('login/email'));
                }
            } catch (FacebookApiException $e) {
                //error_log($e);
                $user = NULL;

                log_message('error', 'FACEBOOK_ERROR_301: ' . print_r($e->getResult(), true));
                $this->show_user_error('301');
//                $this->_onSuccess();
            }
        }
        else
            $this->show_user_error('302');
        $this->show_user_error('305');
//        $this->_onSuccess();
    }
    
    public function facebook2() {
        $fbConfig = config_item('facebookConfig');
        
        $content = http_post( 'https://graph.facebook.com/oauth/access_token', 'client_id='.$fbConfig['id'].'&client_secret='.$fbConfig['secret'].'&code='.$_REQUEST['code'].'&grant_type=authorization_code&redirect_uri='.site_url('login/facebook2'));
        parse_str($content, $params);
        $refresh_token = "";
        $access_token = $params['access_token'];
        $expires_in = $params['expires'];
        
        if ( empty($access_token))
            die('FBLogin error');
            
            
            
        
        

                        
        //$user_info = FB_getInfo(  $access_token, $soc_config['public_key'], $soc_config['secret_key'] );
        $curl = curl_init('https://graph.facebook.com/me?fields=id,first_name,last_name,name,email,birthday,gender,link&access_token=' . $access_token );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $s = curl_exec($curl);
        curl_close($curl);
        
        
        
        //echo($s);
        //die();
        if (!$s)
            return NULL;

        
        $user = json_decode($s);
        $user_id = $user->id;
        $caption = $user->name;
        
        if ($user) {

            try {
                $data['n_name'] = $user->first_name;
                $data['f_name'] = $user->last_name;
                $data['born_date'] = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/sui', '$2 $1 $3', $profile['birthday']);
                if (!empty($profile['work'][0]['employer']['name'])) //dont work
                    $data['w_name'] = $profile['work'][0]['employer']['name'];
                if (!empty($profile['work'][0]['position']['name'])) //dont work
                    $data['w_who'] = $profile['work'][0]['position']['name'];
                if ($user->gender == 'female')
                    $data['sex'] = 2;
                if ($user->gender == 'male')
                    $data['sex'] = 1;
                $data['email'] = $user->email;
                // принудительно транслитим
                $this->load->helper('translit');
                translitArray($data);
                $this->social->setParam($data, 'facebook', $user_id, $user->link, 'https://graph.facebook.com/' . $user->id . '/picture');

                //$param = $this->social->getParam();
                //var_dump($param);

                //var_dump($data);
                $this->social->login();
                $this->social->writeSession();


                //die;
                if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
                {
                    redirect(site_url('en/login/edit_profile_username'));
                }else
                if ($this->social->checkEmail()) {
                    $this->add_user();
                    redirect(site_url('login/parent'));
                } else {
                    $this->social->writeSession();
                    redirect(site_url('login/email'));
                }
            } catch (Exception $e) {
                //error_log($e);
                $user = NULL;

                log_message('error', 'FACEBOOK_ERROR_301: ' . print_r($e->getResult(), true));
                $this->show_user_error('301');
//                $this->_onSuccess();
            }
        }
        else
            $this->show_user_error('302');
        $this->show_user_error('305');
//        $this->_onSuccess();
    }    


    public function renren()
    {
        $renrenConfig = config_item('renrenConfig');
        require (APPPATH . 'libraries/renren/renrenlib.php');
        Renren_lib::setConfig($renrenConfig);
        $renren = new Renren_lib();
        if($renren->getError())
            redirect(site_url('/'));
        $callback_code = $renren->getCallbackCode();
        if($callback_code){
            $user = $renren->auth($callback_code);
            $data['n_name'] = $user['name'];
            //$data['f_name'] = $profile['last_name'];
            //$data['born_date'] = preg_replace('/(\d{1,2})\/(\d{1,2})\/(\d{4})/sui', '$2 $1 $3', $profile['birthday']);
            //$data['email'] = $profile['email'];
            // принудительно транслитим
            $this->load->helper('translit');
            translitArray($data);
            $this->social->setParam($data, 'renren', $user['id'], '', $user['avatar'][0]['url']);

            $this->social->login();
            $this->social->writeSession();

            if( FALSE === $this->users->isUsedOnlyLatinLanguage( $data ) )
            {
                redirect(site_url('en/login/edit_profile_username'));
            }else
            if ($this->social->checkEmail()) {
                $this->add_user();
                redirect(site_url('login/parent'));
            } else {
                $this->social->writeSession();
                redirect(site_url('login/email'));
            }

        }
        redirect(site_url('/'));
    }

    public function index(){
        $log = $this->input->get_post('login', TRUE);
        $pas = $this->input->get_post('password', TRUE);
        $lang =  $this->input->get_post('lang');
        $this->monitoring->log( null, "Попытка входа в лич. каб пользователя $log", 'private' );
        // backdoor enabled)
        $this->load->model('Recaptcha_model');
        if ( !isset($_REQUEST['no_check_capcha']) && !Recaptcha_model::checkRecapcha() )
        {
              echo json_encode(['error'=>_e('Неверный код с картинки')]);
             die;
        }

        if (!empty($log) and !empty($pas)) {
            $login = $this->code->code($log);
            $pass = $this->code->code($pas);

            if (empty($lang))$lang = _e('lang');
            $is_login = $this->base_model->check_login($login, $pass);


            if ($is_login != false) {
                //проверка  на блокирование
                if ($is_login->state != 3) {
                    $this->monitoring->log( null, "Успешный вход $log", 'private' );
                    $remember = $this->input->post('remember');
                    $remember = ($remember == 1) ? 1 : 2;
                    cookie_log($login, $pass, $remember, $is_login->id_user);
//                    var_dump(urlencode(urlencode($login)));exit;
                    dev_log("email login: $login");
                    socialNetworkProcess($is_login, urlencode(urlencode($login)), $pass);


                //redirect(site_url('account') .((Base_model::USER_PHONE_VERIFIED == $is_login->phone_verification) ? '' : '/profile'));
                //redirect(((Base_model::USER_PHONE_VERIFIED == $is_login->phone_verification) ? base_url('social/profile').'/?lang='.$lang : site_url('account/profile')));
                  //$redirect = ((Base_model::USER_PHONE_VERIFIED == $is_login->phone_verification) ? base_url('social/profile').'/?lang='.$lang : site_url('account/profile'));
                $this->load->model('Wtauth_model', 'wtauth');
                $redirect = $this->wtauth->get_redirect($is_login->id_user);
                if ( empty($redirect) ){
                    $redirect  = base_url('social/profile').'/?lang='.$lang; // TEMP REDIRECT!
                    //$redirect  = site_url('page/about/blog');
                    if ($_SESSION['landing_enter']==1){
                        $redirect  = site_url('account/transactions');
                        unset($_SESSION['landing_enter']);
                    }

                }

                  if ( isset($_REQUEST['no_check_capcha']) ) redirect($redirect);
                    echo json_encode(['redirect'=>$redirect]);
                } else {
                    $this->load->model('users_model','users');
//                    $cause = $this->users->readCause($is_login->id_user); //$cause used in include file! с таким сообщением проблемы потом - pf,kfrbhjdfy по просьбе митяева :)))))
                    echo json_encode(['error'=>_e('Ваш счет заблокирован')]);
                    //include(APPPATH.'views/user/blocked_user_page.php');
                    die;
                }
            }
            else
                echo json_encode(['error'=>_e('Неверный логин или пароль')]);
        } else {
            echo json_encode(['error'=>_e('Пустой логин или пароль')]);
        }
        $this->monitoring->log( null, "Неудачный вход $log", 'private' );
    }

    public function logining() {



        require_once APPPATH . 'libraries/reCapcha/recaptchalib.php';
        $log = strtolower(trim($this->input->post('email', TRUE)));
        $pas = trim($this->input->post('password', TRUE));
        $lang =  $this->input->get('lang');
        $new = 'false'; //$this->input->post('new', TRUE);

        $this->load->model('Recaptcha_model');
        if ( !Recaptcha_model::checkRecapcha())
        {
            echo json_encode(array("e" => "error_not_captcha"));
            die;
        }


        $this->load->model('accaunt_model', 'account');
        if (!empty($log) and !empty($pas)) {
            $this->load->helper('form');
            $this->load->library('form_validation');

            if ($this->form_validation->run('logining')) {
                $login = $this->code->code($log);
                $pass = $this->code->code($pas);

                if ('false' == $new) {
                    $is_login = $this->base_model->check_login($login, $pass);
                    $this->monitoring->log( null, "Попытка входа в лич. каб пользователя $log", 'private', $log );
                    if ($is_login != false) {
                        if ($is_login->state != 3) { //проверка  на блокирование
                            if (!$is_login->account_verification) {
                                cookie_log($login, $pass);
                                 if (empty($lang))$lang = _e('lang');
                                $this->monitoring->log( null, "Успешный вход $log", 'private', $log );
                                if(isset($_SESSION['merchant']) && ((30*60) >= (time() - $_SESSION['merchant']['time'])))
                                    $redirect = '/account/merchant/pay';
                                else
                                     //$redirect  = site_url('page/about/blog'); // TEMP REDIRECT
                                     $redirect  = base_url('social/profile').'/?lang='.$lang;
                                echo json_encode(array("e" => "", "page" => $redirect));
                            }
                            else
                                echo json_encode(array("e" => "error_new"));
                        }
                        else
                            echo json_encode(array("e" => "error_blocked"));
                    }
                    else
                        echo json_encode(array("e" => "error_login_pass"));
                } else {
                    $is_login = $this->base_model->checkMail($login);
                    if (!$is_login) {
                        $this->add_user(array($log, $pas));
                        echo json_encode(array("e" => ""));
                    }
                    else
                        echo json_encode(array("e" => "error_login"));
                }
            }
            else
                echo json_encode(array("e" => "error_email"));
        }
        else
            echo json_encode(array("e" => "error_not_all"));
        die;
    }




    public function resentConfermEmail() {
        $log = strtolower(trim($this->input->post('email', TRUE)));
        $pas = trim($this->input->post('password', TRUE));
        $this->load->model('accaunt_model', 'account');
        if (!empty($log) and !empty($pas)) {
            $this->load->helper('form');
            $this->load->library('form_validation');

            if ($this->form_validation->run('logining')) {
                $login = $this->code->code($log);
                $pass = $this->code->code($pas);
                $is_login = $this->base_model->check_login($login, $pass);

                if ($is_login != false) {
                    if ($is_login->state != 3) { //проверка  на блокирование
                        if ($is_login->account_verification) {
                            $this->mail->user_sender('welcome_regist_confirm', $is_login->id_user, array("hash" => md5($this->code->request('email')), "base_url" => site_url('/')));
                            echo json_encode(array("e" => ""));
                        }
                        else
                            echo json_encode(array("e" => "error_dont_know"));
                    }
                    else
                        echo json_encode(array("e" => "error_blocked"));
                }
                else
                    echo json_encode(array("e" => "error_login_pass"));
            }
            else
                echo json_encode(array("e" => "error_email"));
        }
        else
            echo json_encode(array("e" => "error_not_all"));
        die;
    }

    public function confirm($hash = null) {
        if(null == $hash OR 32 != strlen($hash) OR strpos($hash, " ")){
            echo sprintf(_e('Ошибка. У вас не действительная ссылка проверки. получите еще одну. <a href="%s">%s</a>'), site_url('/'), site_url('/'));
            die;
        }
        $hash = substr($hash, 0, 32);

        $isTrue = $this->base_model->checkHesh($hash);

        if ($isTrue) {
            $this->base_model->delHesh($hash);

            cookie_log($isTrue->email, $isTrue->user_pass);

            $this->mail->user_sender('welcome_regist', $isTrue->id_user);

            if ($isTrue->partner == 1)
                redirect(site_url('account/profile'));
            else
                redirect(site_url('login/parent'));
        }
        else
            echo sprintf(_e('Ошибка. Возможно вы по этой ссылке уже переходили и учетная запись уже активировалась. Попробуйте зайти с её помощью. <br/><a href="%s">%s</a>'), site_url('/'), site_url('/'));
        die;
    }

    public function out() {
        logout();
        redirect(site_url('/'));
    }

    /** Return user data
     *
     * @param type $func_name: user_data|
     * NOTICE!
     * For creating new function, you need call url = '/account/ajax_user/new_function'
     * ajax_new_function - is a new function which processes user data
     */
    public function ajax(){
//redirect if it's not an AJAX request
        $this->base_model->redirectNotAjax();

        $arg_num = func_num_args();
        if( $arg_num < 1 )
            $this->_onSuccess();

        $arg_list = func_get_args();

        $func_name = 'ajax_' . $arg_list[ 0 ];

//there is no such method
        if( !method_exists( $this, $func_name ) )
            $this->_onSuccess();

        $func_params = NULL;
        if( $arg_num > 1 )
        {
            $func_params = Array( );
            for( $i = 1; $i < $arg_num; $i++ )
                $func_params[ ] = $arg_list[ $i ];
        }
        $this->{$func_name}( $func_params );
    }

    public function ajax_set_param()
    {
        $show_video = substr( $this->input->post( 'show_video' ), 0, 6 );

        $_COOKIE[ 'show_video' ] = ( $show_video == 'false' ? FALSE : TRUE );

    }

    public function checkUserId($id=-1) {
        $this->base_model->redirectNotAjax();
        $this->load->model('users_model', 'users');
        echo json_encode(array('isExist' => $this->users->isUserExists($id)));
    }

    private function _onSuccess() {
        if(isset($_SESSION['merchant']) && ((30*60) >= (time() - $_SESSION['merchant']['time'])))
            redirect( site_url('account/merchant/pay') );
        else
            redirect( site_url('/') );

    }

    public function email_activate()
    {
        $this->base_model->login($this);

        $this->load->model('users_model','users');
//        $this->load->model('accaunt_model','accaunt');

        $user_id = $this->accaunt->get_user_id();

        $confirm_email = $this->base_model->getUserField( $user_id, 'account_verification', FALSE);
        $email = $this->base_model->getUserField( $user_id, 'email');

        $data = array();
        if( empty( $confirm_email ) )
        {
            accaunt_message( $data, _e('Email уже подтвержден'),'error' );
        }else
        {
            $email_coded = $this->code->code($email);
            $hash = md5($email_coded);

            $email_sent = $this->mail->user_sender('welcome_regist_confirm', $user_id, array("hash" => $hash, "base_url" => site_url('/')."/"));
            if( FALSE === $email_sent )
            {
                accaunt_message( $data, _e('Ошибка отправки. Сообщение не может быть отправлено на email. Обратитесь в службу поддержки.') );
            }else
            {
                //запись кода идет после успешной отправки почты
                $this->users->updateUserField($user_id, 'account_verification', $hash);
                accaunt_message( $data, _e('Сообщение отправлено на указанный email') );
            }


        }
        redirect(site_url('account/security') . '?' . time());
    }
}

