<?php

class Recovery_pass extends CI_Model {
    private $table = 'recovery_pass';

    static $last_error;

    public function __construct() {
        parent::__construct();
    }

    public function send_request($email){
        $this->load->model('users_model', 'users');
        $this->load->helper('form');
        $this->load->helper('random_helper');
        $this->load->library('form_validation');

        $id_user = $this->users->getUserByEmail(trim($email));

        if(!empty($id_user)){
            $hesh      = base64_encode(substr(dechex(time()).md5(uniqid(rand(), 1)), 2, 8));
            $pass     = generate_password(12, 'lud');
            $recovery = $this->db->get_where($this->table, ['id_user' => $id_user])->row();

            if(empty($recovery))
                $this->db->insert($this->table, ['id_user' => $id_user, "access_hash" => $hesh, 'pass' => $pass]);
            else
                $this->db->update($this->table, ["access_hash" => $hesh, 'pass' => $pass], ['id_user' => $id_user], 1);

            if($this->mail->user_sender('forget_pass', $id_user, ['hesh' => urlencode($hesh), 'pass' => $pass])){
                return  ['message' => _e('Информация для востановления отправлена Вам на E-mail')];
            } else
                return ['error' => _e('Не удалось отправить информацию для востановления на E-Mail.')];
        } else
            return ['error' => _e('Такой email не найден.')];
    }

    public function active_pass($param){
        if(!is_object($param)) throw new Exception(_e('Не верное использование Recovery_pass->active_pass'));

        $this->db->where('expires <', "now() - INTERVAL '1' day", false)->delete($this->table);

        $user_id = (int) isset($param->user_id) ? $param->user_id : 0;
        $hesh    = isset($param->hesh) ? $param->hesh : '';

        if(base64_decode($hesh, true)){
            $rec = $this->db->get_where($this->table, ['id_user' => $user_id, "access_hash" => $hesh])->row();

            if(empty($rec))
                return ['error' => _e('Неверный код доступа.')];
            else {
                $this->db->where('id_user', $user_id)->delete($this->table);
                return ['message' => $this->set_new_pass((object)['password' => $rec->pass, 'user_id' => $rec->id_user])];
            }
        } else
            return ['error' => _e('Неверный код доступа. Что-то случилось с ссылкой.')];
    }

    public function set_new_pass($param){
        if(!is_object($param)) throw new Exception(_e('Не верное использование Recovery_pass->set_new_pass'));

        $password = isset($param->password) ? $param->password : '';
        $user_id  = isset($param->user_id) ? $param->user_id : '';

        $this->accaunt->update_user_field('user_pass', $password);

        $this->load->library('soc_network');
        $this->load->model('users_model', 'users');
        $this->load->model('users_filds_model', 'usersFilds');

        $social_id = $this->usersFilds->getUserFild($user_id, 'id_network');
        $user      = $this->users->getUserFullProfile($user_id);
        $this->soc_network->updateSoc($user, $user->user_login, $password, $social_id);
        return _e('Пароль успешно изменен');
    }
}