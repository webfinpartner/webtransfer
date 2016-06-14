<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!class_exists('Accaunt_base')){ require APPPATH.'libraries/Accaunt.php'; }

class Partners_url extends Accaunt_base
{
    public function  __construct() {

        parent::__construct();

      //  if($this->user->partner==2)  redirect(site_url('account/'));



        viewData()->banner_menu = "partner";
        viewData()->secondary_menu = "partner";

        $this->load->model('partner_model');

        $this->load->model('users_model', "users");

        viewData()->page_name = null;

        $this->content->clientType=2;



        $this->bal = getSession('bal');

        if(null === $this->bal || (time()-getSession('bal_tume')) > 12*60*60){

            $this->load->model('user_balances_model', 'user_balances');

            $id_user = $this->user->id_user;



            $end = date("Y-m-d", strtotime("yesterday"));



            $this->bal = $this->user_balances->getBalances($id_user, null, $end);

            $_SESSION['bal'] = $this->bal;

            $_SESSION['bal_tume'] = time();

      }

      viewData()->bal = $this->bal;
   }

   public function index() {
   	$this->load->model('partners_url_model');

    	$data = new stdClass();
  	$user_id = $this->accaunt->get_user_id();


        if ( !empty($_POST['url_del'] )){
            $id = (int)$this->input->post('id', TRUE);
            $this->partners_url_model->delete_url($id, $user_id);
            accaunt_message($data, _e('Страница успешно удалена'));
        } else if(!empty($_POST['f_url'])) {
            // add new link
            // parameters
            $url             =  htmlspecialchars($_POST['f_url'], ENT_QUOTES);
            $url             = str_replace(" ","",$url);
            $template        = $_POST['f_template'];
            $showSkype = isset($_POST['showSkype'])?1:0;
            $showPhone = isset($_POST['showPhone'])?1:0;
            $skype = $_POST['skype'];
            $phone = $_POST['phone'];
            // check limit
            $count_row       = $this->partners_url_model->count_row_user( $user_id );
            // check exists currnect adding link
            $exists_link     = $this->partners_url_model->exist_url( $url );
            $condition_links = array('support', 'banner', 'transaction', 'about_partner', 'invite', 'ads', 'my_users', 'my_balance', 'edit_page', 'expected_income', 'balances', 'my-link', 'top', 'index', 'bonus', 'trigerVolunteer');

            if ( strlen( $url ) > 30 ){
                accaunt_message($data, _e('Можно создать ссылку максимум  30 символов', 'error'));
            } else
            if( $count_row == 99 ) {
                accaunt_message($data, _e('Можно создать максимум  99 страниц', 'error'));
            }
            else if ($exists_link){
                accaunt_message($data, _e('Такая ссылка уже существует','error'));
            }
	    else if ( ! preg_match( '/^[A-Za-z0-9-]+$/i', $url ) ){
                accaunt_message($data, _e('Доступны только латинские буквы, цифры и знак -','error'));
            }
	    else if ( !empty($skype) && ! preg_match( '/^[a-z][a-z0-9\.,\-_]{5,31}$/i', $skype ) ){
                accaunt_message($data, _e('Неверно введен Skype','error'));
            }
	    else if ( ! preg_match( '/^[0-9\-\+]{9,15}$/i', $phone ) ){
                accaunt_message($data, _e('Неверный формат телефона','error'));
            }

            else if (in_array( $url, $condition_links)) {
                accaunt_message($data, _e('Эта ссылка зарезервирована','error'));
            }
            else {
                    $this->partners_url_model->add_url( $user_id, $url, $template, $showSkype, $skype, $showPhone, $phone);
                      accaunt_message($data, _e('Промо-сайт успешно создан'));
            }
      }


        $userdata = $this->partners_url_model->get_user( $user_id );
        $data->phone = $userdata[0]->phone;
        $data->skype = $userdata[0]->skype;

        $data->list = $this->partners_url_model->get_partner_urls($user_id);
        viewData()->banner_menu = "partner";
        viewData()->secondary_menu = "partner";
        viewData()->page_name = __FUNCTION__;

        $view_data = get_object_vars($data) +
                          get_object_vars(viewData()) +
                          ['title_accaunt' => _e('Ваш сайт')];
        $this->content->user_view('partners_url',$view_data,_e('Ваш сайт'));
   }


   public function edit_page($id){


      $this->content->user_view('partners_url_edit',$view_data,_e('Ваш сайт'));

   }


}