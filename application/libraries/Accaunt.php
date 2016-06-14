<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Accaunt_base extends SimpleREST_controller
{

    public $_social;

    public function __construct(){
        parent::__construct();

        $login = $this->base_model->login($this);

        $this->load->model('inbox_model','inbox');
        $this->_social = $this->session->userdata("social_token");
        return $login;
    }

    public function password()
    {
            if(!empty($_POST['submited']))
                    {
                            $error=false;
                            $this->load->helper(array('form', 'url'));
                            $this->load->library('form_validation');
                            if ($this->form_validation->run('password_change') == true )
                            {
                                    $data['error']=  'no';
                                    $password = $this->accaunt->user_field('user_pass');
                                    if($this->input->post('old_password')==$password)
                                    {
                                            $email = $this->accaunt->user_field('email',false);
                                            $this->accaunt->update_user_field('user_pass',$this->input->post('password'));
                                            cookie_log($email, $this->code->request('password'));

                                    }
                                    else  $error=true;
                            }
                            else
                                    $data['error']=  validation_errors();

                                    $data['password']=($error)?'no':'yes';
                                    echo json_encode($data);

                    }
    }



    public function social()
    {
            $data->socials = $this->accaunt->get_social();
            $data->socialList=socialList();
            $this->content->user_view('social',$data,_e("Управление соц сетями"));
    }

/**Нельзя открепить соц сеть.
 *
 * @param type $name
 */
    private function social_delete($name)
    {
    return false;
    if(in_array($name,socialList()))
            {
                    $socials = $this->accaunt->get_social();
                    if(count($socials)>1)
                    {
                            $this->accaunt->social_delete($name);
                            accaunt_message($data, 'Страничка "'.$name.'" была отвязана','good' );
                    }
                    else
                    {
                            accaunt_message($data, 'Вы не можете удалить последнюю привязанную страницу','error' );
                    }
            }
                    redirect(site_url('account/social') . '?' . time());
    }

}
