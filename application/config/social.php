<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cackle_site_id'] = 43883;
$config['cackle_account_api_key'] = '';
$config['cackle_site_api_key'] = '';


//vk
$config['vkontakteConfig2']	= array(
	'id' => '3926553',
	'secret' => '',
	'urlCallback' => 'https://webtransfer.com/login/vkontakte'
);
$config['vkontakteConfig']	= array(
	'id' => '4352044',
	'secret' => '',
	'urlCallback' => 'https://webtransfer.com/login/vkontakte'
);
//$config['vkontakteConfig']	= array(
//	'id' => '4970692',
//	'secret' => 'RVKB3UIBjJtS61Gq0GD2',
//	'urlCallback' => 'http://wtest2.cannedstyle.com/login/vkontakte'
//);

//facebook
$config['facebookConfig'] = array(
	'id' => '526472357444368',
	'secret' => ''
);

//mail ru
$config['mailConfig'] = array(
	'id' => '737165',
	'secret' => '',
	'urlCalback' => "https://webtransfer.com/login/mail_ru"
);

//google
$config['googleConfig'] = array(
		'id' => '556330387880.apps.googleusercontent.com',
		'secret' => '',
		'urlCallback' => 'https://webtransfer.com/login/google_plus',
		'url' => 'https://accounts.google.com/o/oauth2/token'
);

//twitter
$config['twitterConfig'] = array(
		'key' => 'QaVu6lWHPf9cTtXw7mwvWg',
		'secret' => '',
		'urlCallback' => 'https://webtransfer.com/login/twitter',//' . base_url_shot() . '
		'userName' => 'webtransfercom',
                'hashKay' => '196nyer{/\']\rl;JKLS7*%^$#&'
);

//odnoklassniki
$config['odnoklassnikiConfig'] = array(
		'id' => '1152937216',
		'secret' => '',
		'key' => ''
);



//renren
$config['renrenConfig'] = [
	'APP_ID' 		=> '481473',
	'API_KEY' 		=> '',
	'SECRET_KEY' 	=> ''
];

//weibo
$config['weiboConfig'] = [
	'APP_KEY' 		=> '3820830398',
	'APP_SECRET' 	=> ''
];

/* End of file facebook.php */
/* Location: ./application/config/facebook.php */
