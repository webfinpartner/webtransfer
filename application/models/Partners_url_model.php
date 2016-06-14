<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners_url_model extends CI_Model {

   public $tableName = "partners_url";


	public function get_partner_urls($id_user) {
		$q = $this->db
                        ->where('id_user',$id_user)
                        ->order_by('id')
                        ->get( $this->tableName )
                        ->result();

		return $q;
	}

	public function add_url($id_user, $url, $template,  $showSkype, $skype, $showPhone, $phone, $user_text = '') {
		$data = array(
                    'id_user' => $id_user,
                     'url' => $url,
                     'template' => $template,
                     'showSkype' => $showSkype,
                     'skype' => $skype,
                     'showPhone' => $showPhone,
                     'phone' => $phone,
                     'user_text' => $user_text-link

                        );



      $this->db->insert($this->tableName, $data);
	}

	public function edit_url($id, $url, $template,  $showSkype, $skype, $showPhone, $phone, $user_text = '') {
		$data = array(
                     'url' => $url,
                     'template' => $template,
                     'showSkype' => $showSkype,
                     'skype' => $skype,
                     'showPhone' => $showPhone,
                     'phone' => $phone,
                     'user_text' => $user_text-link

                        );
        $this->db->update($this->tableName, $data, ['id',$id]);
	}

	public function delete_url($id, $id_user) {
		$this->db->delete($this->tableName, ['id' => $id, 'id_user'=>$id_user]);
	}

	public function count_row_user($id_user) {
		$this->db->where('id_user', $id_user );
		$this->db->from( $this->tableName );
		return $this->db->count_all_results();
	}

	public function exist_url($url) {
		$this->db->where('url', $url);
		$q = $this->db->from($this->tableName);
		if ($this->db->count_all_results() == 0)
			return false;
		return true;
	}

	public function get_hit($url) {
		$q = $this->db->where('url', $url)
			->get($this->tableName)
			->result();
		return $q;

	}

	public function set_hit($id, $value) {
		$this->db->where('id', $id)
			->update($this->tableName, array("hits" => $value));
	}

	public function add_reg($id) {
            if ( empty($id))
                return;
            $this->db->set('registration', 'registration+1', FALSE);
	    $this->db->where('id', $id)
			->update($this->tableName);
	}


        public function top($cnt){

            return $this->db->where('registration > 0')->order_by('registration', 'DESC')->limit($cnt)->get($this->tableName)->result();

        }

	public function get_user( $id_user ) {

            $this->load->model("users_model","users");
            $cur_user = $this->users->getCurrUserId();
            if($id_user == $cur_user){
                $data = $this->users->getCurrUserData();
                return [$data];
            }
            $q = $this->code->db_decode(
                    $this->db->where('id_user', $id_user)
                            ->get('users')
                            ->result()
                    );
            return $q;
	}
}