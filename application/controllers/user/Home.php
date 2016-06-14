<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Home extends SimpleREST_controller {
    //get all user's data
    //param - name of the fieled
    private function twitterGetData($param) {
        $twConfig = config_item('twitterConfig');

        if (!class_exists('TwitterOAuth'))
            include APPPATH . 'libraries/twitter/twitteroauth.php';

        if (empty($_SESSION['access_token']) ||
                empty($_SESSION['access_token']['oauth_token']) ||
                empty($_SESSION['access_token']['oauth_token_secret'])) {
            $this->session->sess_destroy();
//			header('Location: /');
        }


        /* Get user access tokens out of the session. */
        if (isset($_SESSION['access_token']))
            $access_token = $_SESSION['access_token'];

		// sometimes empty
		if(empty($access_token['oauth_token']))
			$access_token['oauth_token'] = null;

		if(empty($access_token['oauth_token_secret']))
			$access_token['oauth_token_secret'] = null;


        /* Create a TwitterOauth object with consumer/user tokens. */
        $twitterConnection = new TwitterOAuth($twConfig['key'], $twConfig['secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
        // Send the API request
        $twitterData = $twitterConnection->get('users/show', array('screen_name' => $twConfig['userName']));

        if (empty($twitterData) || !isset($param) || !isset($twitterData->$param))
            return null;

        return $twitterData->$param;
    }
	public function index($id = 0) {
        $data = new stdClass();
        require_once APPPATH . 'libraries/reCapcha/recaptchalib.php';
        $data->reCapcha = recaptcha_get_html(getReCapchaKey(), null, true);

		$this->base_model->login($this, false);
		$data->home_content = $this->base_model->get_home(13);
        $data->facebookConfig = config_item('facebookConfig');
        $data->vkontakteConfig = config_item('vkontakteConfig');
        $data->twitter_counter = 100;//$this->twitterGetData('followers_count');

        $data->show_video = FALSE;
        if( isset( $_COOKIE['show_video'] ) && $_COOKIE['show_video'] == FALSE )
        {
            $data->show_video = FALSE;
        }else{
//            $_COOKIE['show_video'] = TRUE;
        }

		if (empty($data->home_content))
			die(_e('Главная  страница  была удалена'));
		//$data->news = $this->base_model->get_news(10);

		//$this->content->config($data->home_content->title, $data->home_content->meta_words, $data->home_content->meta_descript);
                $data->statistic = $this->base_model->getTodayStatistic();
		$this->content->index($data);
	}

}
