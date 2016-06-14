<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Ref extends SimpleREST_controller {
    
	function phone_number($sPhone){
	    $sPhone = ereg_replace("[^0-9]",'',$sPhone);
	    if(strlen($sPhone) != 12) return(False);
	    $sArea = substr($sPhone, 0,3);
	    $sPrefix = substr($sPhone,3,3);
	    $sNumber1 = substr($sPhone,6,2);
	    $sNumber2 = substr($sPhone,8,2);
	    $sPhone = "(".$sArea.")".$sPrefix."-".$sNumber1."-".$sNumber2;
	    return($sPhone);
	}

	public function refir($url) {
            $data = new stdClass();

            $this->load->model('partners_url_model');

            $mode = 'BY_NAME';

            // если у пользщователя еше нет своих страниц то он может указывать ее по ID
            if (strpos($url, 'id-')!==FALSE){
                $hitObject = [];
                $hitObject[0] = new stdClass();
                $hitObject[0]->id = 0;;
                $hitObject[0]->id_user = (int)(substr($url,3));
                $hitObject[0]->hit      = 0;
                $hitObject[0]->template = 2;
                $mode = 'BY_ID';

            } else {
                $hitObject = $this->partners_url_model->get_hit( $url );
            }

            if(!empty($hitObject[0])) {
                              // logout
                              /*if(!empty($_SESSION['auth-idUser'])) {
                                      $this->session->unset_userdata("social_token");
                                      unset($_SESSION['auth-idUser']);
                                      unset($_SESSION['auth-time']);
                                      unset($_SESSION["avatar"]);
                                      empty_cookie();
                                      redirect(site_url($_SERVER['REQUEST_URI']));
                              }*/
            }
            else {
                show_404();
                die;
            }

            $id       = $hitObject[0]->id;
            $id_user  = $hitObject[0]->id_user;
            $hit      = $hitObject[0]->hits;
            $template = $hitObject[0]->template;




            if ( $mode == 'BY_NAME' &&  !isset( $_COOKIE['hit_'.$id] )  )
                $this->partners_url_model->set_hit( $id, ++$hit);


            $this->input->set_cookie([
                                'name'   => 'hit_'.$id,
				'value'  => $id_user,
				'expire' => 60 * 60 * 24 * 30 * 3,
				'domain' => '',
				'path'   => '/',
				'prefix' => '']);

            $this->input->set_cookie([
                                'name'   => 'partner_page_id',
				'value'  => $id,
				'expire' => 60 * 60 * 24 * 30 * 3,
				'domain' => '',
				'path'   => '/',
				'prefix' => '']);

            $this->input->set_cookie([
                                'name'   => 'id_partner',
				'value'  => $id_user,
				'expire' => 60 * 60 * 24 * 30 * 3,
				'domain' => '',
				'path'   => '/',
				'prefix' => '']);

            $userdata = $this->partners_url_model->get_user( $id_user );

            //$email    = $userdata[0]->email;
            //$phone    = $userdata[0]->phone;

            // format phone
            //$phone = $this->phone_number( $phone );
            //$data->front_email = $email;
            $data->phone = $hitObject[0]->phone;
            $data->skype = $hitObject[0]->skype;
            $data->showPhone = $hitObject[0]->showPhone;
            $data->showSkype = $hitObject[0]->showSkype;


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

            $data->statistic = $this->base_model->getTodayStatistic();


            if ($template == 2) {
                    $this->content->index_template_2($data);
            }
			elseif ($template == 3) {
                    $this->content->index_template_3($data);
            }
			elseif ($template == 4) {
                    $this->content->index_template_4($data);
            }
			elseif ($template == 5) {
                    $this->content->index_template_5($data);
            }
			elseif ($template == 6) {
                    $this->content->index_template_6($data);
            }
			elseif ($template == 7) {
                    $this->content->index_template_7($data);
            }
			elseif ($template == 8) {
                    $this->content->index_template_8($data);
            }
			elseif ($template == 9) {
                    $this->content->index_template_9($data);
            }
            else
                    $this->content->index($data);

            $_SESSION['landing_enter'] = 1;

	}

    public function marketing(){
        $this->load->view('user/index_marketing',$data);

    }

    public function index(){
        show_404();
    }

}

