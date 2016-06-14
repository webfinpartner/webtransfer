<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('SimpleREST_controller')){ require APPPATH.'libraries/SimpleREST_controller.php';}

class Registr extends SimpleREST_controller {

    public function index()
	{
		//$this->content->user_view('registr');
		show_404();
		$this->load->library('code');

		$this->load->helper('sms');
		$this->load->library('form_validation');
		$type = $this->input->post('type');

		$run = ($type==2)?"form_investor":(($type==3)?'form_parent':"form");
		if($type==4)$run ="form_loginza";
		if ($this->form_validation->run($run) == FALSE  )
			echo validation_errors();
				//redirect ('/');
		else
		{
			$id_user=$this->base_model->registration();
			$this->load->model('accaunt_model','accaunt');
			$this->accaunt->set_user($id_user);

			if($type==1)
				$this->base_model->add_credit($id_user);

			else if($type==2)
				$this->base_model->add_invest($id_user);

			/*else if($type==4)
				$this->base_model->set_identity($id_user);*/

			if($type==3)
			{
				$this->accaunt->update_user_field('partner',1,false);
				$this->accaunt->update_user_field('face',1);
				//$this->mail->admin_sender('partner_regist_admin');
				sms_send('partner');

			}
				cookie_log($this->code->request('email'),$this->code->request('password'),1,$id_user);
				redirect(site_url('account/profile'));

		}
	}
}
