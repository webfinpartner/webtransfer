<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Users_photo_model extends CI_model
{
	public $tableName = 'users_photo';

	const STATUS_NOT_SHOW = 0;
	const STATUS_SHOW = 1;

	static $last_error;

	function __construct() {
		parent::__construct();
		self::$last_error = '';
	}

	public function setUserPhotoUrl($user_id, $url) {
		$user_id = $this->emptyUserId($user_id);

		if(empty($user_id) || empty($url) || !file_exists($url)) {
			self::$last_error = 'There is no some arguments';
			return 1;
		}

		//$userPhoto = $this->getUserPhotoUrl( $user_id, false );
		$data            = array();
		$data['url']     = $url;
		$data['user_id'] = $user_id;
		$data['user_ip'] = $this->input->ip_address();

		try {
			$this->db->delete('users_photo', array('user_id' => $user_id));
		} catch (Exception $e) {
			self::$last_error = $e->getTraceAsString();
			return 1;
		}

		try {
			$this->db->insert('users_photo', $data);
		} catch (Exception $e) {
			self::$last_error = $e->getTraceAsString();
			return 1;
		}

		unset($_SESSION["avatar"]);
		return 0;
	}

	public function getUserPhotoStatus($user_id) {
		$user_id = $this->emptyUserId($user_id);

		if(empty($user_id)) {
			self::$last_error = 'There is no some arguments';
			return 1;
		}
		return $this->db->get_where('users_photo', array('user_id' => $user_id))->row('status');
	}

	public function setUserPhotoStatus($user_id, $status) {
		$user_id = $this->emptyUserId($user_id);

		if(empty($user_id) || !is_numeric($status) || ($status != 0 && $status != 1)) {
			self::$last_error = 'There is no some arguments';
			return 1;
		}
		$userPhoto = $this->getUserPhotoUrl($user_id, false);
		if($userPhoto) {
			$this->db->where('user_id', $user_id)->update('users_photo', array('status' => (int)$status));
			return 0;
		}
		return 1;
	}

	public function getUserPhotoUrl($user_id = null, $is_active = '1') {

		$user_id = $this->emptyUserId($user_id);

		if(empty($user_id)) {
			self::$last_error = 'There is no some arguments';
			return 0;
		}

		$data['user_id'] = $user_id;
//        if( $is_active ) $data['status'] = self::STATUS_SHOW;

		$photo = $this->db->select('url')
			->where($data)
			->limit(1)
			->get('users_photo')
			->row();

		if(empty($photo) || !isset($photo->url) || !file_exists($photo->url))
			return 0;

		return "/upload/imager.php?src=".$photo->url."&w=50";
	}

	public function getStdAvatarList() {
		$filename = "upload/avatars/list.txt";
		if(!@file_exists($filename)) return null;

		return @file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}

	public function emptyUserId($user_id) {
		if(empty($user_id)) {
			$this->load->model('accaunt_model', 'accaunt');
			return $this->accaunt->get_user_id();
		}
		return $user_id;
	}

	public function checkAvatar($social){
		$user_avatar     = null;
		$user_avatar_100 = null;
		foreach ($social as $item) {
			if(!empty($item->foto))
				$user_avatar = "/upload/imager.php?src=".$item->foto."&w=50";

			if(!empty($item->photo_100))
				$user_avatar_100 = "/upload/imager.php?src=".$item->photo_100."&w=100";

			if($user_avatar_100 && $user_avatar) break;
		}

		if(!$user_avatar_100 /*or !getcUrlPhoto($user_avatar_100)*/) $user_avatar_100 = '/img/no-photo-100.jpg';
		if(!$user_avatar/*or !getcUrlPhoto($user_avatar) */) $user_avatar = '/img/no-photo.gif';
		return ['50' => $user_avatar, '100' => $user_avatar_100];
	}
}