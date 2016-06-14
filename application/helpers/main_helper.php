<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

function logout(){
    ci()->session->unset_userdata("social_token");
    unset($_SESSION['auth-idUser']);
    unset($_SESSION['auth-time']);
    unset($_SESSION["avatar"]);
    unset($_SESSION["parent_avatar"]);
    empty_cookie();
}

function addPay($pay){
    ci()->load->model("transactions_model", "transactions");

    $pay->method     = (isset($pay->method)) ? $pay->method : 'wt';
    $pay->bonus      = (isset($pay->bonus)) ? $pay->bonus : Base_model::TRANSACTION_BONUS_OFF;
    $pay->note       = (isset($pay->note)) ? $pay->note : '';
    $pay->date       = (isset($pay->date)) ? $pay->date : null;
    $pay->note_admin = (isset($pay->note_admin)) ? $pay->note_admin : '';

    return ci()->transactions->addPay(
        $pay->id_user,
        $pay->summa,
        $pay->type,
        $pay->value,
        $pay->method,
        $pay->status,
        $pay->bonus,
        $pay->note,
        $pay->date,
        $pay->note_admin);
}

function calculateUserBalance($id_user, $date){
    ci()->load->model('user_balances_model', 'user_balances');
    $rating = ci()->accaunt->recalculateUserRating($id_user, $date);
    return ci()->user_balances->calculateBalance($rating);
}

function getUserAvatar($id_user, $use_session = TRUE){
    if(isset($_SESSION['users_avatar']) && isset($_SESSION['users_avatar'][$id_user]) && $_SESSION['users_avatar'][$id_user]['time'] > strtotime('-1 day')){
        if ( $use_session )
        return $_SESSION['users_avatar'][$id_user]['url'];
    }

    ci()->load->library('soc_network');
    $r = ci()->soc_network->get_network_id($id_user);

    $_SESSION['users_avatar'][$id_user]['url'] = $r['avatar_path'];
    $_SESSION['users_avatar'][$id_user]['time'] = time();

    return $r['avatar_path'];
}

function ci(){
    return get_instance();
}

/**
 * @return Content
 */
function view(){
    $cnt = &get_instance()->content;
    return $cnt;
}

function viewData(){
    $t = &view()->data;
    return $t;
}

function hide_data( $var, $first = TRUE, $length = 3 )
{
    $str = '**'. substr( $var, 2, 4 ).'**';
//    $len = strlen( $var );
//    if( $length > $len ) $length = $len;
//
//    if( $first === TRUE )
//    {
//        $str = substr( $var, $length );
//
//        $xs = '';
//        for( $i = 0; $i < $length; $i++ )
//            $xs .= '*';
//
//        return $xs.$str;
//    }
//
//    $str = substr( $var, $len - $length, $length );
//
//    $xs = '';
//    for( $i = 0; $i < $length; $i++ )
//        $xs .= 'x';

    return $str;
}
function socialNetworkProcess($is_login, $login, $pass){
    $social_answer = '';
    get_instance()->load->library('soc_network');
    /*if ( $is_login->id_user == 92156962 ){
                var_dump( get_instance()->soc_network->get_network_id($is_login->id_user) );
                die();
    }*/
      dev_log("socialNetworkProcess $login START");
      if ($_SESSION['landing_enter']==1)
          return;

      get_instance()->load->model('users_filds_model', 'usersFilds');

       get_instance()->load->model('users_model', 'users');
//        $res = get_instance()->users->getUserData(500500);
//        var_dump($res);exit;
        $user = get_instance()->users->getUserData($is_login->id_user);
        cookie_log_me_http_secure('user_id', $is_login->id_user, 1, FALSE);




        $social_id = get_instance()->usersFilds->getUserFild($is_login->id_user, 'id_network');
//        get_instance()->soc_network->checkMsg($is_login->id_user);
  //      get_instance()->soc_network->createMsg(90837257, 500500, 'Test1', 'Test1');
//        var_dump()

        $soc_net_data = get_instance()->soc_network->get_network_id($is_login->id_user);
        dev_log("socialNetworkProcess $login {$is_login->id_user} get_network_id({$soc_net_data['id']})");
         if ( is_array($soc_net_data) && !empty($soc_net_data['id']) && $soc_net_data['id'] != $social_id ){
             $social_id = $soc_net_data['id'];
             get_instance()->usersFilds->saveUserNetwork(['id_user' => $is_login->id_user, 'id_network' => $social_id]);
             dev_log("socialNetworkProcess $login {$is_login->id_user} save in DB($social_id)");
         }

        if($social_id)
        {
           cookie_log_me_http_secure('social_id', $social_id);
           $r = get_instance()->soc_network->updateSoc($user, $login, $pass, $social_id);
           $social_answer = "updateSoc: ({$is_login->id_user}, $login, $pass, $social_id)";
           $social_answer .= var_export($r, true);
           dev_log("socialNetworkProcess $login {$is_login->id_user} UPDATESOC");
        }
        else
       {
           $data = get_instance()->soc_network->saveSoc($user, $login, $pass);
           $social_answer = "saveSoc: ({$is_login->id_user}, $login, $pass)";
           $social_answer .= var_export($data, true);

                if(!$data)
                   return;
           $soc_id = @$data['user']['id'];
           dev_log("socialNetworkProcess $login {$is_login->id_user} saveSoc(newID=$soc_id)");
            if($soc_id)
           get_instance()->usersFilds->saveUserNetwork(['id_user' => $is_login->id_user, 'id_network' => $soc_id]);
        }
        return $social_answer;
}

function getDocumentsStatus(){
    $s = config_item("documents_status");
    foreach ($s as &$item) {
        $item = _e($item);
    }
    return $s;
}

function getCurExchStatuses(){

    $s = array( 8, 9, 10, 20, 21, 30, 60, 70, 81, 82, 83, 84, 85, 90, 100);
    $n = array();
    foreach ($s as $key => $item) {
        unset( $item[$key] );
        $n[ $item ] = _e('status_'.$item);
    }
    return $n;
}

function make($path, $name, $to_string = false){
    $data = view()->data;
    if (is_object($data))
        $data = get_object_vars($data);
//    view()->addJs($path.$name);
    return get_instance()->load->view("$path$name.php", $data, $to_string);
}

function getSmsError($e){
    switch ($e) {
        case 1:
        case 2: $error = _e('security/data23');
            break;
        case 3: $error = _e('security/data24');
            break;
        case 4: $error = _e('security/data25');
            break;
        case 5: $error = _e('security/data26');
            break;
        case 6: $error = _e('security/data27');
            break;
        case 7: $error = _e('security/data28');
            break;
        case 8: $error = _e('security/data32');
            break;
    }
    return $error;
}

function get_currency( $currency_id )
{
    if( empty( $currency_id ) || is_numeric(  $currency_id ))
        return NULL;

    $CI = &get_instance();
	$CI->load->model('currency_model', 'currency');

	return $CI->currency->get_currency_by_code( $currency_id );
}

function is_test_user( $group_name = null ){

    $CI = &get_instance();
	$CI->load->model('accaunt_model', 'accaunt');

	$current_user_id = $CI->accaunt->get_user_id();

    $test_users = array( 500733, 500757 );

    return in_array( $current_user_id, $test_users );
}
function post_item($item){
    $CI = &get_instance();
    return $CI->input->post($item);
}
function get_item($item){
    $CI = &get_instance();
    return $CI->input->get($item);
}

function expiryDate($date) {
    return str_replace("-", "/", $date);
}
function kyc($kyc) {
    return str_replace("_", " ", $kyc);
}

function getSession($item) {
	return (isset($_SESSION[$item]) ? $_SESSION[$item] : null);
}

function getServer($item) {
	return (isset($_SERVER[$item]) ? $_SERVER[$item] : null);
}

function sendPdf($document, $name) {
	if(!class_exists('mPDF'))
		require('./pdf/mpdf.php');

	error_reporting(0);

	$mpdf                          = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
	$mpdf->charset_in              = 'utf-8'; /*не забываем про русский*/
	$mpdf->list_indent_first_level = 0;
	$mpdf->WriteHTML($document, 2); /*формируем pdf*/
	$mpdf->Output($name, 'I');

	error_reporting(E_ALL);

}

function setPaymentOrger() {
//    $pays = (array) $this->input->post('payment');
//    $payment = array();
//    foreach ($pays as $pay)
//        $payment[] = mb_substr($pay, 0, 70);
//
//    return serialize(array_slice($payment, 0, 8));
	return "";
}

function getPaymentOrger($val) {
//    return unserialize($val)
	return "";
}

function oath_hotp($key, $counter) {
	// Counter
	//the counter value can be more than one byte long, so we need to go multiple times
	$cur_counter = array(0, 0, 0, 0, 0, 0, 0, 0);
	for ($i = 7; $i >= 0; $i--) {
		$cur_counter[$i] = pack('C*', $counter);
		$counter         = $counter >> 8;
	}
	$bin_counter = implode($cur_counter);
	// Pad to 8 chars
	if(strlen($bin_counter) < 8) {
		$bin_counter = str_repeat(chr(0), 8 - strlen($bin_counter)) . $bin_counter;
	}

	// HMAC
	$hash = hash_hmac('sha1', $bin_counter, $key);
	return $hash;
}

function oath_truncate($hash, $length = 6) {
	// Convert to dec
	foreach (str_split($hash, 2) as $hex) {
		$hmac_result[] = hexdec($hex);
	}

	// Find offset
	$offset = $hmac_result[19] & 0xf;

	// Algorithm from RFC
	return
		(
			(($hmac_result[$offset + 0] & 0x7f) << 24) |
			(($hmac_result[$offset + 1] & 0xff) << 16) |
			(($hmac_result[$offset + 2] & 0xff) << 8) |
			($hmac_result[$offset + 3] & 0xff)
		) % pow(10, $length);
}

function getNearThis(array $vals, $target) {
	$curDelta  = 0;
	$nearDelta = 0;
	$nearIndex = -1;
	foreach ($vals as $key => $val) {
		$curDelta = abs($target - $val);
		if($nearIndex < 0 || $curDelta < $nearDelta) {
			$nearIndex = $key;
			$nearDelta = $curDelta;
		}
	}
	return $vals[$nearIndex];
}

function genSum4Invest() {
	$res = array();
	for ($c = 50; 1000 >= $c; $c += 50)
		$res[] = $c;
	return $res;
}

function genPsnt4Invest() {
	return getPsntArray();
}

function genTime4Invest() {
	$res = array();
	for ($c = 3; 30 >= $c; $c++)
		$res[] = $c;
	return $res;
}

function getVideoCatigotiesSelect($def) {
	renderSelect(video_catigories(), $def);
}

function getVideoCatSelect($def) {
	renderSelect(array_combine(video_catigories_link(), video_catigories()), $def);
}

function video_catigories($e = null) {
	$CI = &get_instance();
	$c  = $CI->config->config['video_catigories'];
	foreach ($c as &$row)
		$row = _e($row);

	if(null !== $e) return $c[$e];
	return $c;
}

function video_catigories_link($e = null) {
	$CI = &get_instance();
	$c  = $CI->config->config['video_catigories_link'];
	if(null !== $e) return $c[$e];
	return $c;
}

function video_catigories_link_num($e) {
	$CI = &get_instance();
	$c  = array_flip($CI->config->config['video_catigories_link']);
	return (isset($c[$e]) ? $c[$e] : null);
}

function getNameOfPartnerPlan($level) {
	$CI = &get_instance();
	return (isset($CI->config->config['partner-plan-name'][$level]) ? $CI->config->config['partner-plan-name'][$level] : null);
}

function getVisualDNA_standart() {
	$CI = &get_instance();
	return $CI->config->config['VisualDNA_stangart'];
}

function getVisualDNA_garant() {
	$CI = &get_instance();
	return $CI->config->config['VisualDNA_garant'];
}

function getUnlimitedUsers() {
	$CI = &get_instance();
	return $CI->config->config['unlimited_users'];
}

function getVipUsers() {
	$CI = &get_instance();
	return $CI->config->config['vip_users'];
}

function getCheckedUsers() {
	$CI = &get_instance();
	return $CI->config->config['checked_users'];
}

function getUntrastedUsers4Send() {
	$CI = &get_instance();
	return $CI->config->config['untrasted_users_for_send'];
}

function getExchangeUsers() {
	return config_item('exchange_users');
}

function getSecretOtpauth() {
	$CI = &get_instance();
	return $CI->config->config['secret_otpauth'];
}

function getCategories() {
	$CI = &get_instance();
	$r  = $CI->config->config['categories'];
	foreach ($r as &$row)
		$row = _e($row);
	unset($row);
	return $r;
}

function getDocumentsLabel() {
	$CI = &get_instance();
	$r  = $CI->config->item('documents_label');
	foreach ($r as &$row)
		$row = _e($row);
	unset($row);
	return $r;
}

function genSumInvest4Validation() {
	$a = array();
//    for ($c = 10; 50 >= $c; $c+=10)
//        $a[] = $c;
	for ($c = 50; 1000 >= $c; $c += 50)
		$a[] = $c;
	return implode(".", $a);
}

function genTimeInvest4Validation() {
	$a = array();
	for ($c = 3; 30 >= $c; $c++)
		$a[] = $c;
	return implode(".", $a);
}

function genPsnt4Validation() {
	return implode(":", getPsntArray());
}

function genPayoutSystems4Validation() {
	$paySystems = get_instance()->config->config["payuot_systems_psnt"];
	$paySystems = array_keys($paySystems);
	return implode(".", $paySystems);
}

function getTopicsAsJSON($id_user = null) {
	$CI = &get_instance();
	$CI->load->model('accaunt_model', 'accaunt');
	if(null == $id_user)
		$id_user = $CI->accaunt->get_user_id();

	$CI->load->model("volunteer_topic_model");
	$t   = $CI->volunteer_topic_model->getTopics($id_user);
	$tpc = array();
	foreach ($t as $topic)
		$tpc[] = $topic->name;
	return json_encode($tpc);
}

function removeXSS($str) {
	$str = str_replace("--", "HAKKER!", (string)$str);
	$str = str_replace(";", "", $str);
	$str = str_replace("/*", "HAKKER!", $str);
	$str = str_replace("*/", "HAKKER!", $str);
	$str = str_replace("\"", "", $str);
	$str = str_replace("'", "", $str);
	$str = str_replace("/", "", $str);
	$str = str_replace("\\", "", $str);
	return $str;
}

function payeerPaySystems() {
	$CI = &get_instance();
	return $CI->config->config['PayeerPaySystems'];
}

function getWpayMerchantID() {
	$CI = &get_instance();
	return $CI->config->config['WpayMerchantID'];
}

function getWpayPassword() {
	$CI = &get_instance();
	return $CI->config->config['WpayPassword'];
}

function getNixMoneyHash() {
	$CI = &get_instance();
	return $CI->config->config['nixMonayHash'];
}

function payuot_systems_psnt() {
	$CI = &get_instance();
	return $CI->config->config['payuot_systems_psnt'];
}

function payout_systems() {
	$CI = &get_instance();
	return $CI->config->config['payout_systems'];
}

function payout_systems_type() {
	$CI = &get_instance();
	return $CI->config->config['payout_systems_type'];
}

function getPaymentSys() {
	$CI = &get_instance();
	return $CI->config->config['payment_sys'];
}

function getPerfectmoneyHash() {
	$CI = &get_instance();
	return $CI->config->config['ALTERNATE_PHRASE_HASH'];
}

function getEgopayPassword() {
	$CI = &get_instance();
	return $CI->config->config['Store_Pass'];
}

function getEgopayStore() {
	$CI = &get_instance();
	return $CI->config->config['Store_ID'];
}

function getEgopayKey() {
	$CI = &get_instance();
	return $CI->config->config['Checksum_Key'];
}

function getOkpayWalletID() {
	$CI = &get_instance();
	return $CI->config->config['okpayWalletID'];
}

function getNixMonayID() {
	$CI = &get_instance();
	return $CI->config->config['nixmoneyID'];
}

function getPayeerPaySecret() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPayeerPayId() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPayeerPayUser() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getOkpayWalletIDPay() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getOkpaySecret() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPerfectmoneySecret() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPerfectmoneyID() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPerfectmoneyPayer() {
    $name = substr(__FUNCTION__,3);
    return (post_item($name)) ? post_item($name) : config_item($name);
}

function getPayeerSecret() {
	$CI = &get_instance();
	return $CI->config->config['PayeerSecret'];
}

function getReCapchaKey() {
	$CI = &get_instance();
	return $CI->config->config['publickeyCapcha'];
}

function getReCapchaKeyPrivate() {
	$CI = &get_instance();
	return $CI->config->config['publickeyCapchaPrivate'];
}

function getPsntArray() {
	$CI = &get_instance();
	return $CI->config->config['array4psntStep'];
}

function getPsntSelect($def = "") {
	foreach (getPsntArray() as $key => $psnt) {
		$s = ($def == $key) ? "selected='selected'" : "";
		echo "<option $s value='$key'>" . sprintf("%01.1f%%", $psnt) . "</option>" . PHP_EOL;
	}
}

function renderSelectGroup(array $options, $def = "") {
	$sub   = '';
	$me    = '';
	$users = '';
        $cards = '';
	foreach ($options as $value => $title) {
		$s = ($def == $value) ? "selected='selected'" : "";
		if("Мой кошелёк (" == substr($title['item'], 0, 23))
			$me = "<option $s value='$value'>{$title['item']}</option>" . PHP_EOL;
                elseif( substr($value,0,4) == 'card' )
                    $cards .= "<option $s value='$value'>{$title['item']}</option>" . PHP_EOL;
		else
			$users .= "<option $s value='$value'>{$title['item']}</option>" . PHP_EOL;
		if(isset($title['sub']) && !empty($title['sub'])) {
			foreach ($title['sub'] as $key => $val) {
				$s = ($def == $key) ? "selected='selected'" : "";
				$sub .= "<option $s value='$key'>$val</option>" . PHP_EOL;
			}
		}
	}
	echo $me;
        if ( $cards != ''){
            echo "<optgroup label='"._e('Мои карты')."'>" . PHP_EOL;
            echo $cards;
            echo "</optgroup>" . PHP_EOL;
        }
        if (  $users != '' ){
            echo "<optgroup label='1 "._e('уровень')."'>" . PHP_EOL;
            echo $users;
            echo "</optgroup>" . PHP_EOL;
        }
        if (  $sub != '' ){
            echo "<optgroup label='2 "._e('уровень')."'>" . PHP_EOL;
            echo $sub;
            echo "</optgroup>" . PHP_EOL;
        }
}

function renderSelect(array $options, $def = "") {
	foreach ($options as $value => $title) {
		$s = ($def == $value) ? "selected='selected'" : "";
		echo "<option $s value='$value'>$title</option>" . PHP_EOL;
	}
}

function renderSelectKey(array $options, $def = "") {
	foreach ($options as $title => $value) {
		$s = ($def == $value) ? "selected='selected'" : "";
		echo "<option $s value='$value'>$title</option>" . PHP_EOL;
	}
}

function creatUserIdNameArray(array $users) {
	$res = array();
	foreach ($users as $user) {
		if(!isset($user->id_user))
			continue;
		$res[$user->id_user] = "$user->name $user->sername ($user->id_user)";
	}
        if(empty($res)) $res[0] = _e("У вас нет партнеров");
	return $res;
}

function creatUserIdNameArrayCard(array $users) {
        $CI   = &get_instance();
        $CI->load->model('Card_model','card');
	$res = array();
	foreach ($users as $user) {
                $cards = $CI->card->getCards($user->id_user);
		if(!isset($user->id_user) || empty($cards))continue;
                foreach( $cards as $card ){
                    $res[$card->id] = "$user->name $user->sername (".Card_model::display_card_name($card, TRUE).")";
                }
	}
        if(empty($res)) $res[0] = _e("У вас нет партнеров с картой");
	return $res;
}

function creatUserIdNameAndChildArray(array $users) {
	$res = array();
	foreach ($users as $user) {
		if(!isset($user->id_user))
			continue;
		$res[$user->id_user]['item'] = "$user->name $user->sername ($user->id_user)";
		$res[$user->id_user]['sub']  = (!empty($user->subusers)) ? creatUserIdNameArray($user->subusers) : array();
	}
	return $res;
}

function creatUserIdArray(array $users) {
	$res = array();
	foreach ($users as $user) {
		if(!isset($user->id_user))
			continue;
		$res[] = (int)$user->id_user;
	}
	return $res;
}

function creatUserIdAndChildArray(array $users) {
	$res = array();
	foreach ($users as $user) {
		if(!isset($user->id_user))
			continue;
		$res[] = (int)$user->id_user;
		if(!empty($user->subusers))
			$res = array_merge($res, creatUserIdAndChildArray($user->subusers));
	}
	return $res;
}

function changeComa($float) {
	return str_replace(",", ".", (string)$float);
}

function countPesnt($amaunt, $system) {
	$CI   = &get_instance();
	$s    = $CI->config->config["payuot_systems_psnt"];
	$cur  = $s[$system];
	$psnt = $amaunt * $cur["psnt"] / 100;
	if($psnt < $cur["min"])
		$psnt = $cur["min"];
	$psnt = $psnt + $cur["add"];

	return $psnt;
}


function countBonusPesnt($amaunt, $system) {
	$CI   = &get_instance();
	$s    = $CI->config->config["payuot_systems_psnt"];
	$cur  = $s[$system];
	$psnt = $amaunt * $cur["bonus"] / 100;
	return $psnt;
}

function getStatistic() {
	$data        = new stdClass();
	$CI          = &get_instance();
	$data->today = $CI->db->get_where('statistics', array(
		'date' => date('Y-m-d')
	))->row();
	$date        = date('Y-m-d');
	$d           = new DateTime($date);
	$d->modify("-1 day");
	$date                 = $d->format("Y-m-d");
	$data->yesterday_date = $date;
	$data->yesterday      = $CI->db->get_where('statistics', array(
		'date' => $date
	))->row();

	return $data;
}


// кэшируем статистику
function getCachedStatistic($minutes = 5) {
    $CI          = &get_instance();
    $CI->load->driver('cache');
    if ( ! $stat = $CI->cache->file->get('statistic'))
    {
         $stat = getStatistic();
         $CI->cache->file->save('statistic', $stat, 60*$minutes);
    }

    return $stat;

}

function getStatisticPercent() {
	$data        = new stdClass();
	$CI          = &get_instance();
	$data->today = $CI->db->get_where('statistics_percent', array(
		'date' => date('Y-m-d')
	))->result();
	$date        = date('Y-m-d');
	$d           = new DateTime($date);
	$d->modify("-1 day");
	$date                 = $d->format("Y-m-d");
	$data->yesterday_date = $date;
	$result               = $CI->db->get_where('statistics_percent', array(
		'date' => $date
	))->result();
	$data->yesterday      = array();
	foreach ($result as $item) {
		$data->yesterday[$item->percent] = $item;
	}
	return $data;
}

function form_dropdown_countries($name, $array_value, $val, $style) {
	$out   = "<select {$style} name='{$name}'>";
	$c_out = '';

	foreach ($array_value as $i => $country) {
		$data = '';
		foreach ($country as $n => $v) {
			if($n == 'name') continue;
			$data .= "data-{$n}='{$v}' ";
		}
		if($i == $val) $data .= "selected='selected'";

		$c_out .= "<option value='{$i}' {$data}>{$country['name']}</option>";
	}

	$out .= $c_out;
	$out .= "</select>";
	return $out;
}

function getPhoneSelect($short_name = 0, $language = 'ru') {
	$codes = get_phone_codes();

	$s = array();
	foreach ($codes as $c) {
		$img = '';
		if(isset($c['extra_short']) && !empty($c['extra_short']))
			$img = '<img class="dd-image" src="/img/flags/' . strtolower($c['extra_short']) . '.gif" alt=""/>';
		$selected = '';
		if(isset($c['short']) && $c['short'] == $short_name) {
			$selected = 'dd-option-selected';
		}
		if($language == 'en') {
			$s[] = '<li>' .
				'<a class="dd-option ' . $selected . '" data-code="' . $c['code'] . '" data-short="' . $c['short'] . '">' . $img .
				'<label style="line-height: 18px;" class="dd-option-text">' . $c['country_name_en'] . ' (+' . $c['code'] . ')</label>'
				. '</a></li>';
		} else {
			$s[] = '<li>' .
				'<a class="dd-option ' . $selected . '" data-code="' . $c['code'] . '" data-short="' . $c['short'] . '">' . $img .
				'<label style="line-height: 18px;" class="dd-option-text">' . $c['county_name_ru'] . ' (+' . $c['code'] . ')</label>'
				. '</a></li>';
		}


	}
	return implode('', $s);
}

function get_phone_codes() {
	$code   = array();
$code[] = array('county_name_ru' => 'Россия', 'code' => '7', 'extra_short' => 'RU', 'short' => 'RUS', 'country_name_en'		=> '	Russia	');
$code[] = array('county_name_ru' => 'Австралия', 'code' => '61', 'extra_short' => 'AU', 'short' => 'AUS', 'country_name_en'		=> '	Australia	');
$code[] = array('county_name_ru' => 'Австрия', 'code' => '43', 'extra_short' => 'AT', 'short' => 'AUT', 'country_name_en'		=> '	Austria	');
$code[] = array('county_name_ru' => 'Азербайджан', 'code' => '994', 'extra_short' => 'AZ', 'short' => 'AZE', 'country_name_en'		=> '	Azerbaijan	');
$code[] = array('county_name_ru' => 'Албания', 'code' => '355', 'extra_short' => 'AL', 'short' => 'ALB', 'country_name_en'		=> '	Albania	');
$code[] = array('county_name_ru' => 'Алжир', 'code' => '213', 'extra_short' => 'DZ', 'short' => 'DZA', 'country_name_en'		=> '	Algeria	');
$code[] = array('county_name_ru' => 'Американские Виргинские о-ва', 'code' => '1 340', 'extra_short' => 'VI', 'short' => 'VIR', 'country_name_en'		=> '	US Virgin Islands	');
$code[] = array('county_name_ru' => 'Американское Самоа', 'code' => '1 684', 'extra_short' => 'AS', 'short' => 'ASM', 'country_name_en'		=> '	American Samoa	');
$code[] = array('county_name_ru' => 'Ангилья', 'code' => '1 264', 'extra_short' => 'AI', 'short' => 'AIA', 'country_name_en'		=> '	Anguilla	');
$code[] = array('county_name_ru' => 'Ангола', 'code' => '244', 'extra_short' => 'AO', 'short' => 'AGO', 'country_name_en'		=> '	Angola	');
$code[] = array('county_name_ru' => 'Андорра', 'code' => '376', 'extra_short' => 'AD', 'short' => 'AND', 'country_name_en'		=> '	andorra	');
$code[] = array('county_name_ru' => 'Антарктида', 'code' => '672', 'short' => 'ATA', 'country_name_en'		=> '	Antarctica	');
$code[] = array('county_name_ru' => 'Антигуа и Барбуда', 'code' => '1 268', 'extra_short' => 'AG', 'short' => 'ATG', 'country_name_en'		=> '	Antigua and Barbuda	');
$code[] = array('county_name_ru' => 'Аргентина', 'code' => '54', 'extra_short' => 'AR', 'short' => 'ARG', 'country_name_en'		=> '	Argentina	');
$code[] = array('county_name_ru' => 'Армения', 'code' => '374', 'extra_short' => 'AM', 'short' => 'ARM', 'country_name_en'		=> '	Armenia	');
$code[] = array('county_name_ru' => 'Аруба', 'code' => '297', 'extra_short' => 'AW', 'short' => 'ABW', 'country_name_en'		=> '	Aruba	');
$code[] = array('county_name_ru' => 'Афганистан', 'code' => '93', 'extra_short' => 'AF', 'short' => 'AFG', 'country_name_en'		=> '	Afghanistan	');
$code[] = array('county_name_ru' => 'Багамские о-ва', 'code' => '1 242', 'extra_short' => 'BS', 'short' => 'BHS', 'country_name_en'		=> '	Bahamas	');
$code[] = array('county_name_ru' => 'Бангладеш', 'code' => '880', 'extra_short' => 'BD', 'short' => 'BGD', 'country_name_en'		=> '	Bangladesh	');
$code[] = array('county_name_ru' => 'Барбадос', 'code' => '1 246', 'extra_short' => 'BB', 'short' => 'BRB', 'country_name_en'		=> '	Barbados	');
$code[] = array('county_name_ru' => 'Бахрейн', 'code' => '973', 'extra_short' => 'BH', 'short' => 'BHR', 'country_name_en'		=> '	Bahrain	');
$code[] = array('county_name_ru' => 'Беларусь', 'code' => '375', 'extra_short' => 'BY', 'short' => 'BLR', 'country_name_en'		=> '	Belarus	');
$code[] = array('county_name_ru' => 'Белиз', 'code' => '501', 'extra_short' => 'BZ', 'short' => 'BLZ', 'country_name_en'		=> '	Belize	');
$code[] = array('county_name_ru' => 'Бельгия', 'code' => '32', 'extra_short' => 'BE', 'short' => 'BEL', 'country_name_en'		=> '	Belgium	');
$code[] = array('county_name_ru' => 'Бенин', 'code' => '229', 'extra_short' => 'BJ', 'short' => 'BEN', 'country_name_en'		=> '	Benin	');
$code[] = array('county_name_ru' => 'Бермудские о-ва', 'code' => '1 441', 'extra_short' => 'BM', 'short' => 'BMU', 'country_name_en'		=> '	Bermuda	');
$code[] = array('county_name_ru' => 'Бирма (Мьянма)', 'code' => '95', 'extra_short' => 'MM', 'short' => 'MMR', 'country_name_en'		=> '	Burma (Myanmar)	');
$code[] = array('county_name_ru' => 'Болгария', 'code' => '359', 'extra_short' => 'BG', 'short' => 'BGR', 'country_name_en'		=> '	Bulgaria	');
$code[] = array('county_name_ru' => 'Боливия', 'code' => '591', 'extra_short' => 'BO', 'short' => 'BOL', 'country_name_en'		=> '	Bolivia	');
$code[] = array('county_name_ru' => 'Босния и Герцеговина', 'code' => '387', 'extra_short' => 'BA', 'short' => 'BIH', 'country_name_en'		=> '	Bosnia and Herzegovina	');
$code[] = array('county_name_ru' => 'Ботсвана', 'code' => '267', 'extra_short' => 'BW', 'short' => 'BWA', 'country_name_en'		=> '	Botswana	');
$code[] = array('county_name_ru' => 'Бразилия', 'code' => '55', 'extra_short' => 'BR', 'short' => 'BRA', 'country_name_en'		=> '	Brazil	');
$code[] = array('county_name_ru' => 'Британские Виргинские о-ва', 'code' => '1 284', 'extra_short' => 'VG', 'short' => 'VGB', 'country_name_en'		=> '	British Virgin Islands	');
$code[] = array('county_name_ru' => 'Бруней', 'code' => '673', 'extra_short' => 'BN', 'short' => 'BRN', 'country_name_en'		=> '	Brunei	');
$code[] = array('county_name_ru' => 'Буркина-Фасо', 'code' => '226', 'extra_short' => 'BF', 'short' => 'BFA', 'country_name_en'		=> '	Burkina Faso	');
$code[] = array('county_name_ru' => 'Бурунди', 'code' => '257', 'extra_short' => 'BI', 'short' => 'BDI', 'country_name_en'		=> '	Burundi	');
$code[] = array('county_name_ru' => 'Бутан', 'code' => '975', 'extra_short' => 'BT', 'short' => 'BTN', 'country_name_en'		=> '	Butane	');
$code[] = array('county_name_ru' => 'Вануату', 'code' => '678', 'extra_short' => 'VU', 'short' => 'VUT', 'country_name_en'		=> '	Vanuatu	');
$code[] = array('county_name_ru' => 'Венгрия', 'code' => '36', 'extra_short' => 'HU', 'short' => 'HUN', 'country_name_en'		=> '	Hungary	');
$code[] = array('county_name_ru' => 'Венесуэла', 'code' => '58', 'extra_short' => 'VE', 'short' => 'VEN', 'country_name_en'		=> '	Venezuela	');
$code[] = array('county_name_ru' => 'Вьетнам', 'code' => '84', 'extra_short' => 'VN', 'short' => 'VNM', 'country_name_en'		=> '	Vietnam	');
$code[] = array('county_name_ru' => 'Габон', 'code' => '241', 'extra_short' => 'GA', 'short' => 'GAB', 'country_name_en'		=> '	Gabon	');
$code[] = array('county_name_ru' => 'Гайана', 'code' => '592', 'extra_short' => 'GY', 'short' => 'GUY', 'country_name_en'		=> '	Guyana	');
$code[] = array('county_name_ru' => 'Гаити', 'code' => '509', 'extra_short' => 'HT', 'short' => 'HTI', 'country_name_en'		=> '	Haiti	');
$code[] = array('county_name_ru' => 'Гамбия', 'code' => '220', 'extra_short' => 'GM', 'short' => 'GMB', 'country_name_en'		=> '	Gambia	');
$code[] = array('county_name_ru' => 'Гана', 'code' => '233', 'extra_short' => 'GH', 'short' => 'GHA', 'country_name_en'		=> '	Ghana	');
$code[] = array('county_name_ru' => 'Гватемала', 'code' => '502', 'extra_short' => 'GT', 'short' => 'GTM', 'country_name_en'		=> '	Guatemala	');
$code[] = array('county_name_ru' => 'Гвинея', 'code' => '224', 'extra_short' => 'GN', 'short' => 'GIN', 'country_name_en'		=> '	Guinea	');
$code[] = array('county_name_ru' => 'Гвинея-Бисау', 'code' => '245', 'extra_short' => 'GW', 'short' => 'GNB', 'country_name_en'		=> '	Guinea-Bissau	');
$code[] = array('county_name_ru' => 'Германия', 'code' => '49', 'extra_short' => 'DE', 'short' => 'DEU', 'country_name_en'		=> '	Germany	');
$code[] = array('county_name_ru' => 'Гибралтар', 'code' => '350', 'extra_short' => 'GI', 'short' => 'GIB', 'country_name_en'		=> '	Gibraltar	');
$code[] = array('county_name_ru' => 'Гондурас', 'code' => '504', 'extra_short' => 'HN', 'short' => 'HND', 'country_name_en'		=> '	Honduras	');
$code[] = array('county_name_ru' => 'Гонконг', 'code' => '852', 'extra_short' => 'HK', 'short' => 'HKG', 'country_name_en'		=> '	Hong Kong	');
$code[] = array('county_name_ru' => 'Гренада', 'code' => '1 473', 'extra_short' => 'GD', 'short' => 'GRD', 'country_name_en'		=> '	Grenada	');
$code[] = array('county_name_ru' => 'Гренландия', 'code' => '299', 'extra_short' => 'GL', 'short' => 'GRL', 'country_name_en'		=> '	Greenland	');
$code[] = array('county_name_ru' => 'Греция', 'code' => '30', 'extra_short' => 'GR', 'short' => 'GRC', 'country_name_en'		=> '	Greece	');
$code[] = array('county_name_ru' => 'Грузия', 'code' => '995', 'extra_short' => 'GE', 'short' => 'GEO', 'country_name_en'		=> '	Georgia	');
$code[] = array('county_name_ru' => 'Гуам', 'code' => '1 671', 'extra_short' => 'GU', 'short' => 'GUM', 'country_name_en'		=> '	Guam	');
$code[] = array('county_name_ru' => 'Дания', 'code' => '45', 'extra_short' => 'DK', 'short' => 'DNK', 'country_name_en'		=> '	Denmark	');
$code[] = array('county_name_ru' => 'Демократическая Республика Конго', 'code' => '243', 'extra_short' => 'CD', 'short' => 'COD', 'country_name_en'		=> '	Democratic Republic of the Congo	');
$code[] = array('county_name_ru' => 'Джибути', 'code' => '253', 'extra_short' => 'DJ', 'short' => 'DJI', 'country_name_en'		=> '	Djibouti	');
$code[] = array('county_name_ru' => 'Доминика', 'code' => '1 767', 'extra_short' => 'DM', 'short' => 'DMA', 'country_name_en'		=> '	Dominica	');
$code[] = array('county_name_ru' => 'Доминиканская Республика', 'code' => '1 809', 'extra_short' => 'DO', 'short' => 'DOM', 'country_name_en'		=> '	Dominican Republic	');
$code[] = array('county_name_ru' => 'Египет', 'code' => '20', 'extra_short' => 'EG', 'short' => 'EGY', 'country_name_en'		=> '	Egypt	');
$code[] = array('county_name_ru' => 'Замбия', 'code' => '260', 'extra_short' => 'ZM', 'short' => 'ZMB', 'country_name_en'		=> '	Zambia	');
$code[] = array('county_name_ru' => 'Зимбабве', 'code' => '263', 'extra_short' => 'ZW', 'short' => 'ZWE', 'country_name_en'		=> '	Zimbabwe	');
$code[] = array('county_name_ru' => 'Йемен', 'code' => '967', 'extra_short' => 'YE', 'short' => 'YEM', 'country_name_en'		=> '	Yemen	');
$code[] = array('county_name_ru' => 'Израиль', 'code' => '972', 'extra_short' => 'IL', 'short' => 'ISR', 'country_name_en'		=> '	Israel	');
$code[] = array('county_name_ru' => 'Индия', 'code' => '91', 'extra_short' => 'IN', 'short' => 'IND', 'country_name_en'		=> '	India	');
$code[] = array('county_name_ru' => 'Индонезия', 'code' => '62', 'extra_short' => 'ID', 'short' => 'IDN', 'country_name_en'		=> '	Indonesia	');
$code[] = array('county_name_ru' => 'Иордания', 'code' => '962', 'extra_short' => 'JO', 'short' => 'JOR', 'country_name_en'		=> '	Jordan	');
$code[] = array('county_name_ru' => 'Ирак', 'code' => '964', 'extra_short' => 'IQ', 'short' => 'IRQ', 'country_name_en'		=> '	Iraq	');
$code[] = array('county_name_ru' => 'Иран', 'code' => '98', 'extra_short' => 'IR', 'short' => 'IRN', 'country_name_en'		=> '	Iran	');
$code[] = array('county_name_ru' => 'Ирландия', 'code' => '353', 'extra_short' => 'IE', 'short' => 'IRL', 'country_name_en'		=> '	Ireland	');
$code[] = array('county_name_ru' => 'Исландия', 'code' => '354', 'extra_short' => 'IS', 'short' => 'IS', 'country_name_en'		=> '	Iceland	');
$code[] = array('county_name_ru' => 'Испания', 'code' => '34', 'extra_short' => 'ES', 'short' => 'ESP', 'country_name_en'		=> '	Spain	');
$code[] = array('county_name_ru' => 'Италия', 'code' => '39', 'extra_short' => 'IT', 'short' => 'ITA', 'country_name_en'		=> '	Italy	');
$code[] = array('county_name_ru' => 'Кабо-Верде', 'code' => '238', 'extra_short' => 'CV', 'short' => 'CPV', 'country_name_en'		=> '	Cape Verde	');
$code[] = array('county_name_ru' => 'Казахстан', 'code' => '7', 'extra_short' => 'KZ', 'short' => 'KAZ', 'country_name_en'		=> '	Kazakhstan	');
$code[] = array('county_name_ru' => 'Каймановы о-ва', 'code' => '1 345', 'extra_short' => 'KY', 'short' => 'CYM', 'country_name_en'		=> '	Cayman Islands	');
$code[] = array('county_name_ru' => 'Камбоджа', 'code' => '855', 'extra_short' => 'KH', 'short' => 'KHM', 'country_name_en'		=> '	Cambodia	');
$code[] = array('county_name_ru' => 'Камерун', 'code' => '237', 'extra_short' => 'CM', 'short' => 'CMR', 'country_name_en'		=> '	Cameroon	');
$code[] = array('county_name_ru' => 'Канада', 'code' => '1', 'extra_short' => 'CA', 'short' => 'CAN', 'country_name_en'		=> '	Canada	');
$code[] = array('county_name_ru' => 'Катар', 'code' => '974', 'extra_short' => 'QA', 'short' => 'QAT', 'country_name_en'		=> '	Qatar	');
$code[] = array('county_name_ru' => 'Кения', 'code' => '254', 'extra_short' => 'KE', 'short' => 'KEN', 'country_name_en'		=> '	Kenya	');
$code[] = array('county_name_ru' => 'Кипр', 'code' => '357', 'extra_short' => 'CY', 'short' => 'CYP', 'country_name_en'		=> '	Cyprus	');
$code[] = array('county_name_ru' => 'Кирибати', 'code' => '686', 'extra_short' => 'KI', 'short' => 'KIR', 'country_name_en'		=> '	Kiribati	');
$code[] = array('county_name_ru' => 'Китай', 'code' => '86', 'extra_short' => 'CN', 'short' => 'CHN', 'country_name_en'		=> '	China	');
$code[] = array('county_name_ru' => 'Кокосовые (Килинг) о-ва', 'code' => '61', 'extra_short' => 'CC', 'short' => 'CCK', 'country_name_en'		=> '	Cocos (Keeling) Islands	');
$code[] = array('county_name_ru' => 'Колумбия', 'code' => '57', 'extra_short' => 'CO', 'short' => 'COL', 'country_name_en'		=> '	Colombia	');
$code[] = array('county_name_ru' => 'Коморские о-ва', 'code' => '269', 'extra_short' => 'KM', 'short' => 'COM', 'country_name_en'		=> '	Comoros	');
$code[] = array('county_name_ru' => 'Коста-Рика', 'code' => '506', 'extra_short' => 'CR', 'short' => 'CRC', 'country_name_en'		=> '	Costa Rica	');
$code[] = array('county_name_ru' => 'Кот-д\'Ивуар', 'code' => '225', 'extra_short' => 'CI', 'short' => 'CIV', 'country_name_en'		=> '	Côte d \ Ivoire	');
$code[] = array('county_name_ru' => 'Куба', 'code' => '53', 'extra_short' => 'CU', 'short' => 'CUB', 'country_name_en'		=> '	Cuba	');
$code[] = array('county_name_ru' => 'Кувейт', 'code' => '965', 'extra_short' => 'KW', 'short' => 'KWT', 'country_name_en'		=> '	Kuwait	');
$code[] = array('county_name_ru' => 'Кыргызстан', 'code' => '996', 'extra_short' => 'KG', 'short' => 'KGZ', 'country_name_en'		=> '	Kyrgyzstan	');
$code[] = array('county_name_ru' => 'Лаос', 'code' => '856', 'extra_short' => 'LA', 'short' => 'LAO', 'country_name_en'		=> '	Laos	');
$code[] = array('county_name_ru' => 'Латвия', 'code' => '371', 'extra_short' => 'LV', 'short' => 'LVA', 'country_name_en'		=> '	Latvia	');
$code[] = array('county_name_ru' => 'Лесото', 'code' => '266', 'extra_short' => 'LS', 'short' => 'LSO', 'country_name_en'		=> '	Lesotho	');
$code[] = array('county_name_ru' => 'Либерия', 'code' => '231', 'extra_short' => 'LR', 'short' => 'LBR', 'country_name_en'		=> '	Liberia	');
$code[] = array('county_name_ru' => 'Ливан', 'code' => '961', 'extra_short' => 'LB', 'short' => 'LBN', 'country_name_en'		=> '	Lebanon	');
$code[] = array('county_name_ru' => 'Ливия', 'code' => '218', 'extra_short' => 'LY', 'short' => 'LBY', 'country_name_en'		=> '	Libya	');
$code[] = array('county_name_ru' => 'Литва', 'code' => '370', 'extra_short' => 'LT', 'short' => 'LTU', 'country_name_en'		=> '	Lithuania	');
$code[] = array('county_name_ru' => 'Лихтенштейн', 'code' => '423', 'extra_short' => 'LI', 'short' => 'LIE', 'country_name_en'		=> '	Liechtenstein	');
$code[] = array('county_name_ru' => 'Люксембург', 'code' => '352', 'extra_short' => 'LU', 'short' => 'LUX', 'country_name_en'		=> '	Luxembourg	');
$code[] = array('county_name_ru' => 'Маврикий', 'code' => '230', 'extra_short' => 'MU', 'short' => 'MUS', 'country_name_en'		=> '	Mauritius	');
$code[] = array('county_name_ru' => 'Мавритания', 'code' => '222', 'extra_short' => 'MR', 'short' => 'MRT', 'country_name_en'		=> '	Mauritania	');
$code[] = array('county_name_ru' => 'Мадагаскар', 'code' => '261', 'extra_short' => 'MG', 'short' => 'MDG', 'country_name_en'		=> '	Madagascar	');
$code[] = array('county_name_ru' => 'Майотта', 'code' => '262', 'extra_short' => 'YT', 'short' => 'MYT', 'country_name_en'		=> '	Mayotte	');
$code[] = array('county_name_ru' => 'Макао', 'code' => '853', 'extra_short' => 'MO', 'short' => 'MAC', 'country_name_en'		=> '	Macau	');
$code[] = array('county_name_ru' => 'Македония', 'code' => '389', 'extra_short' => 'MK', 'short' => 'MKD', 'country_name_en'		=> '	Macedonia	');
$code[] = array('county_name_ru' => 'Малави', 'code' => '265', 'extra_short' => 'MW', 'short' => 'MWI', 'country_name_en'		=> '	Malawi	');
$code[] = array('county_name_ru' => 'Малайзия', 'code' => '60', 'extra_short' => 'MY', 'short' => 'MYS', 'country_name_en'		=> '	Malaysia	');
$code[] = array('county_name_ru' => 'Мали', 'code' => '223', 'extra_short' => 'ML', 'short' => 'MLI', 'country_name_en'		=> '	Mali	');
$code[] = array('county_name_ru' => 'Мальдивские о-ва', 'code' => '960', 'extra_short' => 'MV', 'short' => 'MDV', 'country_name_en'		=> '	Maldives	');
$code[] = array('county_name_ru' => 'Мальта', 'code' => '356', 'extra_short' => 'MT', 'short' => 'MLT', 'country_name_en'		=> '	Malta	');
$code[] = array('county_name_ru' => 'Марокко', 'code' => '212', 'extra_short' => 'MA', 'short' => 'MAR', 'country_name_en'		=> '	Morocco	');
$code[] = array('county_name_ru' => 'Маршалловы о-ва', 'code' => '692', 'extra_short' => 'MH', 'short' => 'MHL', 'country_name_en'		=> '	Marshall Islands	');
$code[] = array('county_name_ru' => 'Мексика', 'code' => '52', 'extra_short' => 'MX', 'short' => 'MEX', 'country_name_en'		=> '	Mexico	');
$code[] = array('county_name_ru' => 'Микронезия', 'code' => '691', 'extra_short' => 'FM', 'short' => 'FSM', 'country_name_en'		=> '	Micronesia	');
$code[] = array('county_name_ru' => 'Мозамбик', 'code' => '258', 'extra_short' => 'MZ', 'short' => 'MOZ', 'country_name_en'		=> '	Mozambique	');
$code[] = array('county_name_ru' => 'Молдова', 'code' => '373', 'extra_short' => 'MD', 'short' => 'MDA', 'country_name_en'		=> '	Moldova	');
$code[] = array('county_name_ru' => 'Монако', 'code' => '377', 'extra_short' => 'MC', 'short' => 'MCO', 'country_name_en'		=> '	Monaco	');
$code[] = array('county_name_ru' => 'Монголия', 'code' => '976', 'extra_short' => 'MN', 'short' => 'MNG', 'country_name_en'		=> '	Mongolia	');
$code[] = array('county_name_ru' => 'Монтсеррат', 'code' => '1 664', 'extra_short' => 'MS', 'short' => 'MSR', 'country_name_en'		=> '	Montserrat	');
$code[] = array('county_name_ru' => 'Намибия', 'code' => '264', 'extra_short' => 'NA', 'short' => 'NAM', 'country_name_en'		=> '	Namibia	');
$code[] = array('county_name_ru' => 'Науру', 'code' => '674', 'extra_short' => 'NR', 'short' => 'NRU', 'country_name_en'		=> '	Nauru	');
$code[] = array('county_name_ru' => 'Непал', 'code' => '977', 'extra_short' => 'NP', 'short' => 'NPL', 'country_name_en'		=> '	Nepal	');
$code[] = array('county_name_ru' => 'Нигер', 'code' => '227', 'extra_short' => 'NE', 'short' => 'NER', 'country_name_en'		=> '	Niger	');
$code[] = array('county_name_ru' => 'Нигерия', 'code' => '234', 'extra_short' => 'NG', 'short' => 'NGA', 'country_name_en'		=> '	Nigeria	');
$code[] = array('county_name_ru' => 'Нидерландские Антильские о-ва', 'code' => '599', 'extra_short' => 'AN', 'short' => 'ANT', 'country_name_en'		=> '	Netherlands Antilles	');
$code[] = array('county_name_ru' => 'Нидерланды', 'code' => '31', 'extra_short' => 'NL', 'short' => 'NLD', 'country_name_en'		=> '	Netherlands	');
$code[] = array('county_name_ru' => 'Никарагуа', 'code' => '505', 'extra_short' => 'NI', 'short' => 'NIC', 'country_name_en'		=> '	Nicaragua	');
$code[] = array('county_name_ru' => 'Ниуэ', 'code' => '683', 'extra_short' => 'NU', 'short' => 'NIU', 'country_name_en'		=> '	Niue	');
$code[] = array('county_name_ru' => 'Новая Зеландия', 'code' => '64', 'extra_short' => 'NZ', 'short' => 'NZL', 'country_name_en'		=> '	New Zealand	');
$code[] = array('county_name_ru' => 'Новая Каледония', 'code' => '687', 'extra_short' => 'NC', 'short' => 'NCL', 'country_name_en'		=> '	New Caledonia	');
$code[] = array('county_name_ru' => 'Норвегия', 'code' => '47', 'extra_short' => 'NO', 'short' => 'NOR', 'country_name_en'		=> '	Norway	');
$code[] = array('county_name_ru' => 'Объединенное Королевство', 'code' => '44', 'extra_short' => 'GB', 'short' => 'GBR', 'country_name_en'		=> '	United Kingdom	');
$code[] = array('county_name_ru' => 'ОАЭ', 'code' => '971', 'extra_short' => 'AE', 'short' => 'ARE', 'country_name_en'		=> '	UAE	');
$code[] = array('county_name_ru' => 'Оман', 'code' => '968', 'extra_short' => 'OM', 'short' => 'OMN', 'country_name_en'		=> '	Oman	');
$code[] = array('county_name_ru' => 'Остров Мэн', 'code' => '44', 'short' => 'IMN', 'country_name_en'		=> '	Isle Of Man	');
$code[] = array('county_name_ru' => 'Остров Норфолк', 'code' => '672', 'short' => 'NFK', 'country_name_en'		=> '	nf	');
$code[] = array('county_name_ru' => 'Остров Рождества', 'code' => '61', 'extra_short' => 'CX', 'short' => 'CXR', 'country_name_en'		=> '	Christmas Island	');
$code[] = array('county_name_ru' => 'Остров Святой Елены', 'code' => '290', 'extra_short' => 'SH', 'short' => 'SHN', 'country_name_en'		=> '	Saint Helena Island	');
$code[] = array('county_name_ru' => 'Острова Кука', 'code' => '682', 'extra_short' => 'CK', 'short' => 'COK', 'country_name_en'		=> '	Cook Islands	');
$code[] = array('county_name_ru' => 'Острова Питкэрн', 'code' => '870', 'extra_short' => 'PN', 'short' => 'PCN', 'country_name_en'		=> '	Pitcairn Islands	');
$code[] = array('county_name_ru' => 'Острова Теркс и Кайкос', 'code' => '1 649', 'extra_short' => 'TC', 'short' => 'TCA', 'country_name_en'		=> '	Turks and Caicos Islands	');
$code[] = array('county_name_ru' => 'Пакистан', 'code' => '92', 'extra_short' => 'PK', 'short' => 'PAK', 'country_name_en'		=> '	Pakistan	');
$code[] = array('county_name_ru' => 'Палау', 'code' => '680', 'extra_short' => 'PW', 'short' => 'PLW', 'country_name_en'		=> '	Palau	');
$code[] = array('county_name_ru' => 'Панама', 'code' => '507', 'extra_short' => 'PA', 'short' => 'PAN', 'country_name_en'		=> '	Panama	');
$code[] = array('county_name_ru' => 'Папуа-Новая Гвинея', 'code' => '675', 'extra_short' => 'PG', 'short' => 'PNG', 'country_name_en'		=> '	Papua New Guinea	');
$code[] = array('county_name_ru' => 'Парагвай', 'code' => '595', 'extra_short' => 'PY', 'short' => 'PRY', 'country_name_en'		=> '	Paraguay	');
$code[] = array('county_name_ru' => 'Перу', 'code' => '51', 'extra_short' => 'PE', 'short' => 'PER', 'country_name_en'		=> '	Peru	');
$code[] = array('county_name_ru' => 'Польша', 'code' => '48', 'extra_short' => 'PL', 'short' => 'POL', 'country_name_en'		=> '	Poland	');
$code[] = array('county_name_ru' => 'Португалия', 'code' => '351', 'extra_short' => 'PT', 'short' => 'PRT', 'country_name_en'		=> '	Portugal	');
$code[] = array('county_name_ru' => 'Пуэрто-Рико', 'code' => '1', 'extra_short' => 'PR', 'short' => 'PRI', 'country_name_en'		=> '	Puerto-Rico	');
$code[] = array('county_name_ru' => 'Республика Конго', 'code' => '242', 'extra_short' => 'CG', 'short' => 'COG', 'country_name_en'		=> '	Republic of the Congo	');
$code[] = array('county_name_ru' => 'Руанда', 'code' => '250', 'extra_short' => 'RW', 'short' => 'RWA', 'country_name_en'		=> '		');
$code[] = array('county_name_ru' => 'Румыния', 'code' => '40', 'extra_short' => 'RO', 'short' => 'ROU', 'country_name_en'		=> '	Rwanda	');
$code[] = array('county_name_ru' => 'Сальвадор', 'code' => '503', 'extra_short' => 'SV', 'short' => 'SLV', 'country_name_en'		=> '	Romania	');
$code[] = array('county_name_ru' => 'Самоа', 'code' => '685', 'extra_short' => 'WS', 'short' => 'WSM', 'country_name_en'		=> '	Salvador	');
$code[] = array('county_name_ru' => 'Сан - Марино', 'code' => '378', 'extra_short' => 'SM', 'short' => 'SMR', 'country_name_en'		=> '	Samoa	');
$code[] = array('county_name_ru' => 'Санкт-Бартелеми', 'code' => '590', 'short' => 'BLM', 'country_name_en'		=> '	San - Marino	');
$code[] = array('county_name_ru' => 'Сан-Томе и Принсипи', 'code' => '239', 'extra_short' => 'ST', 'short' => 'STP', 'country_name_en'		=> '	St. Barthelemy	');
$code[] = array('county_name_ru' => 'Саудовская Аравия', 'code' => '966', 'extra_short' => 'SA', 'short' => 'SAU', 'country_name_en'		=> '	Sao Tome and Principe	');
$code[] = array('county_name_ru' => 'Свазиленд', 'code' => '268', 'extra_short' => 'SZ', 'short' => 'SWZ', 'country_name_en'		=> '	Saudi Arabia	');
$code[] = array('county_name_ru' => 'Святой Престол (Ватикан)', 'code' => '39', 'extra_short' => 'VA', 'short' => 'VAT', 'country_name_en'		=> '	Swaziland	');
$code[] = array('county_name_ru' => 'Северная Корея', 'code' => '850', 'extra_short' => 'KP', 'short' => 'PRK', 'country_name_en'		=> '	Holy See (Vatican City)	');
$code[] = array('county_name_ru' => 'Северные Марианские о-ва', 'code' => '1 670', 'extra_short' => 'MP', 'short' => 'MNP', 'country_name_en'		=> '	North Korea	');
$code[] = array('county_name_ru' => 'Сейшельские о-ва', 'code' => '248', 'extra_short' => 'SC', 'short' => 'SYC', 'country_name_en'		=> '	Northern Mariana Islands	');
$code[] = array('county_name_ru' => 'Сектор Газа', 'code' => '970', 'short' => 'SCG', 'extra_short' => 'SCG' ,'country_name_en'=> 'Seychelles	');
$code[] = array('county_name_ru' => 'Сенегал', 'code' => '221', 'extra_short' => 'SN', 'short' => 'SEN', 'country_name_en'		=> '	Sector Senegal	');
$code[] = array('county_name_ru' => 'Сен-Мартен', 'code' => '1 599', 'short' => 'MAF', 'country_name_en'		=> '	Saint MartenMAF');
$code[] = array('county_name_ru' => 'Сен-Пьер и Микелон', 'code' => '508', 'extra_short' => 'PM', 'short' => 'SPM', 'country_name_en'		=> '	Saint Pierre and Miquelon	');
$code[] = array('county_name_ru' => 'Сент-Винсент и Гренадины', 'code' => '1 784', 'extra_short' => 'VC', 'short' => 'VCT', 'country_name_en'		=> '	Vc	');
$code[] = array('county_name_ru' => 'Сент-Китс и Невис', 'code' => '1 869', 'extra_short' => 'KN', 'short' => 'KNA', 'country_name_en'		=> '	Saint Kitts and Nevis	');
$code[] = array('county_name_ru' => 'Сент-Люсия', 'code' => '1 758', 'extra_short' => 'LC', 'short' => 'LCA', 'country_name_en'		=> '	Saint Lucia	');
$code[] = array('county_name_ru' => 'Сербия', 'code' => '381', 'extra_short' => 'RS', 'short' => 'SRB', 'country_name_en'		=> '	Serbia	');
$code[] = array('county_name_ru' => 'Сингапур', 'code' => '65', 'extra_short' => 'SG', 'short' => 'SGP', 'country_name_en'		=> '	Singapore	');
$code[] = array('county_name_ru' => 'Сирия', 'code' => '963', 'extra_short' => 'SY', 'short' => 'SYR', 'country_name_en'		=> '	Syria	');
$code[] = array('county_name_ru' => 'Словакия', 'code' => '421', 'extra_short' => 'SK', 'short' => 'SVK', 'country_name_en'		=> '	Slovakia	');
$code[] = array('county_name_ru' => 'Словения', 'code' => '386', 'extra_short' => 'SI', 'short' => 'SVN', 'country_name_en'		=> '	Slovenia	');
$code[] = array('county_name_ru' => 'Соломоновы Острова', 'code' => '677', 'extra_short' => 'SB', 'short' => 'SLB', 'country_name_en'		=> '	Solomon islands	');
$code[] = array('county_name_ru' => 'Сомали', 'code' => '252', 'extra_short' => 'SO', 'short' => 'SOM', 'country_name_en'		=> '	Somalia	');
$code[] = array('county_name_ru' => 'Судан', 'code' => '249', 'extra_short' => 'SD', 'short' => 'SDN', 'country_name_en'		=> '	Sudan	');
$code[] = array('county_name_ru' => 'Суринам', 'code' => '597', 'extra_short' => 'SR', 'short' => 'SUR', 'country_name_en'		=> '	Surinam	');
$code[] = array('county_name_ru' => 'США', 'code' => '1', 'extra_short' => 'US', 'short' => 'USA', 'country_name_en'		=> '	USA	');
$code[] = array('county_name_ru' => 'Сьерра-Леоне', 'code' => '232', 'extra_short' => 'SL', 'short' => 'SLE', 'country_name_en'		=> '	Sierra Leone	');
$code[] = array('county_name_ru' => 'Таджикистан', 'code' => '992', 'extra_short' => 'TJ', 'short' => 'TJK', 'country_name_en'		=> '	Tajikistan	');
$code[] = array('county_name_ru' => 'Тайвань', 'code' => '886', 'extra_short' => 'TW', 'short' => 'TWN', 'country_name_en'		=> '	Taiwan	');
$code[] = array('county_name_ru' => 'Таиланд', 'code' => '66', 'extra_short' => 'TH', 'short' => 'THA', 'country_name_en'		=> '	Thailand	');
$code[] = array('county_name_ru' => 'Танзания', 'code' => '255', 'extra_short' => 'TZ', 'short' => 'TZA', 'country_name_en'		=> '	Tanzania	');
$code[] = array('county_name_ru' => 'Тимор-Лешти', 'code' => '670', 'extra_short' => 'TL', 'short' => 'TLS', 'country_name_en'		=> '	Timor-Leste	');
$code[] = array('county_name_ru' => 'Того', 'code' => '228', 'extra_short' => 'TG', 'short' => 'TGO', 'country_name_en'		=> '	Togo	');
$code[] = array('county_name_ru' => 'Токелау', 'code' => '690', 'extra_short' => 'TK', 'short' => 'TKL', 'country_name_en'		=> '	Tokelau	');
$code[] = array('county_name_ru' => 'Тонга', 'code' => '676', 'extra_short' => 'TO', 'short' => 'TON', 'country_name_en'		=> '	Tonga	');
$code[] = array('county_name_ru' => 'Тринидад и Тобаго', 'code' => '1 868', 'extra_short' => 'TT', 'short' => 'TTO', 'country_name_en'		=> '	Trinidad and Tobago	');
$code[] = array('county_name_ru' => 'Тувалу', 'code' => '688', 'extra_short' => 'TV', 'short' => 'TUV', 'country_name_en'		=> '	Tuvalu	');
$code[] = array('county_name_ru' => 'Тунис', 'code' => '216', 'extra_short' => 'TN', 'short' => 'TUN', 'country_name_en'		=> '	Tunisia	');
$code[] = array('county_name_ru' => 'Туркменистан', 'code' => '993', 'extra_short' => 'TM', 'short' => 'TKM', 'country_name_en'		=> '	Turkmenistan	');
$code[] = array('county_name_ru' => 'Турция', 'code' => '90', 'extra_short' => 'TR', 'short' => 'TUR', 'country_name_en'		=> '	Turkey	');
$code[] = array('county_name_ru' => 'Уганда', 'code' => '256', 'extra_short' => 'UG', 'short' => 'UGA', 'country_name_en'		=> '	Uganda	');
$code[] = array('county_name_ru' => 'Узбекистан', 'code' => '998', 'extra_short' => 'UZ', 'short' => 'UZB', 'country_name_en'		=> '	Uzbekistan	');
$code[] = array('county_name_ru' => 'Украина', 'code' => '38', 'extra_short' => 'UA', 'short' => 'UKR', 'country_name_en'		=> '	Ukraine	');
$code[] = array('county_name_ru' => 'Уоллис и Футуна', 'code' => '681', 'extra_short' => 'WF', 'short' => 'WLF', 'country_name_en'		=> '	Wallis and Futuna	');
$code[] = array('county_name_ru' => 'Уругвай', 'code' => '598', 'extra_short' => 'UY', 'short' => 'URY', 'country_name_en'		=> '	Uruguay	');
$code[] = array('county_name_ru' => 'Фарерские о-ва', 'code' => '298', 'extra_short' => 'FO', 'short' => 'FRO', 'country_name_en'		=> '	Faroe Islands	');
$code[] = array('county_name_ru' => 'Фиджи', 'code' => '679', 'extra_short' => 'FJ', 'short' => 'FJI', 'country_name_en'		=> '	Fiji	');
$code[] = array('county_name_ru' => 'Филиппины', 'code' => '63', 'extra_short' => 'PH', 'short' => 'PHL', 'country_name_en'		=> '	Philippines	');
$code[] = array('county_name_ru' => 'Финляндия', 'code' => '358', 'extra_short' => 'FI', 'short' => 'FIN', 'country_name_en'		=> '	Finland	');
$code[] = array('county_name_ru' => 'Фолклендские о-ва', 'code' => '500', 'extra_short' => 'FK', 'short' => 'FLK', 'country_name_en'		=> '	Falkland Islands	');
$code[] = array('county_name_ru' => 'Франция', 'code' => '33', 'extra_short' => 'FR', 'short' => 'FRA', 'country_name_en'		=> '	France	');
$code[] = array('county_name_ru' => 'Французская Полинезия', 'code' => '689', 'extra_short' => 'PF', 'short' => 'PYF', 'country_name_en'		=> '	French polynesia	');
$code[] = array('county_name_ru' => 'Хорватия', 'code' => '385', 'extra_short' => 'HR', 'short' => 'HRV', 'country_name_en'		=> '	Croatia	');
$code[] = array('county_name_ru' => 'Центрально-Африканская Республика', 'code' => '236', 'extra_short' => 'CF', 'short' => 'CAF', 'country_name_en'		=> '	Central African Republic	');
$code[] = array('county_name_ru' => 'Чад', 'code' => '235', 'extra_short' => 'TD', 'short' => 'TCD', 'country_name_en'		=> '	Chad	');
$code[] = array('county_name_ru' => 'Черногория', 'code' => '382', 'extra_short' => 'ME', 'short' => 'MNE', 'country_name_en'		=> '	Montenegro	');
$code[] = array('county_name_ru' => 'Чешская Республика', 'code' => '420', 'extra_short' => 'CZ', 'short' => 'CZE', 'country_name_en'		=> '	Czech Republic	');
$code[] = array('county_name_ru' => 'Чили', 'code' => '56', 'extra_short' => 'CL', 'short' => 'CHL', 'country_name_en'		=> '	Chile	');
$code[] = array('county_name_ru' => 'Швейцария', 'code' => '41', 'extra_short' => 'CH', 'short' => 'CHE', 'country_name_en'		=> '	Switzerland	');
$code[] = array('county_name_ru' => 'Швеция', 'code' => '46', 'extra_short' => 'SE', 'short' => 'SWE', 'country_name_en'		=> '	Sweden	');
$code[] = array('county_name_ru' => 'Шри-Ланка', 'code' => '94', 'extra_short' => 'LK', 'short' => 'LKA', 'country_name_en'		=> '	Sri Lanka	');
$code[] = array('county_name_ru' => 'Эквадор', 'code' => '593', 'extra_short' => 'EC', 'short' => 'ECU', 'country_name_en'		=> '	Ecuador	');
$code[] = array('county_name_ru' => 'Экваториальная Гвинея', 'code' => '240', 'extra_short' => 'GQ', 'short' => 'GNQ', 'country_name_en'		=> '	Equatorial Guinea	');
$code[] = array('county_name_ru' => 'Эритрея', 'code' => '291', 'extra_short' => 'ER', 'short' => 'ERI', 'country_name_en'		=> '	Eritrea	');
$code[] = array('county_name_ru' => 'Эстония', 'code' => '372', 'extra_short' => 'EE', 'short' => 'EST', 'country_name_en'		=> '	Estonia	');
$code[] = array('county_name_ru' => 'Эфиопия', 'code' => '251', 'extra_short' => 'ET', 'short' => 'ETH', 'country_name_en'		=> '	Ethiopia	');
$code[] = array('county_name_ru' => 'Южная Африка', 'code' => '27', 'extra_short' => 'ZA', 'short' => 'ZAF', 'country_name_en'		=> '	South Africa	');
$code[] = array('county_name_ru' => 'Южная Корея', 'code' => '82', 'extra_short' => 'KR', 'short' => 'KOR', 'country_name_en'		=> '	South Korea	');
$code[] = array('county_name_ru' => 'Ямайка', 'code' => '1 876', 'extra_short' => 'JM', 'short' => 'JAM', 'country_name_en'		=> '	Jamaica	');
$code[] = array('county_name_ru' => 'Япония', 'code' => '81', 'extra_short' => 'JP', 'short' => 'JPN', 'country_name_en'		=> '	Japan	');


	return $code;
}

function get_payment_default($view = false) { // Платежная система по умолчанию
	$array = array(
		"не определено",
		"Номер карты",
		"QIWI",
		"Paypal",
		"Tinkoff Wallet",
		"Webmoney",
		"Liqpay",
		"Yandex",
		"Банк",
	);

// sort($array);
// array_unshift($array, "Paypal");

	if($view) {
		$ci = &get_instance();
		$ci->load->helper('form');

		return form_dropdown('payment_default', $array, '1');
	} else
		return $array;
}

function get_coutry_codes(){
    $arr = get_phone_codes();
    $return = array();
    foreach($arr as $country){
        if($country['extra_short'])
            $return[$country['extra_short']] = _e($country['county_name_ru']);
    }
    asort($return);
    if('ru' == get_instance()->lang->lang())
        $return = array("RU" => _e("Россия")) + $return;
    return $return;
}

function get_country_list($only_names = FALSE, $not_translate = false) {

	$array = array();
	$i     = 0;
    // <editor-fold defaultstate="collapsed" desc="countries">

        $array['']   = array('name' => 'Выберите страну', 'wire_form' => '');
	$array[$i++] = array('name' => 'Россия', 'wire_form' => '');

	$array[$i++] = array('name' => 'Австралия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Австрия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Азербайджан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Аландские острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Албания', 'wire_form' => '');
	$array[$i++] = array('name' => 'Алжир', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ангилья', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ангола', 'wire_form' => '');
	$array[$i++] = array('name' => 'Андорра', 'wire_form' => '');
	$array[$i++] = array('name' => 'Антигуа и Барбуда', 'wire_form' => '');
	$array[$i++] = array('name' => 'Аргентина', 'wire_form' => '');
	$array[$i++] = array('name' => 'Армения', 'wire_form' => '');
	$array[$i++] = array('name' => 'Аруба', 'wire_form' => '');
	$array[$i++] = array('name' => 'Афганистан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Багамские Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бангладеш', 'wire_form' => '');
	$array[$i++] = array('name' => 'Барбадос', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бахрейн', 'wire_form' => '');
	$array[$i++] = array('name' => 'Белиз', 'wire_form' => '');
	$array[$i++] = array('name' => 'Белоруссия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бельгия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Бенин', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бермудские Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Болгария', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Боливия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бонэйр', 'wire_form' => '');
	$array[$i++] = array('name' => 'Босния и Герцеговина', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ботсвана', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бразилия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бруней', 'wire_form' => '');
	$array[$i++] = array('name' => 'Буркина-Фасо', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бурунди', 'wire_form' => '');
	$array[$i++] = array('name' => 'Бутан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Вануату', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ватикан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Великобритания', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Венгрия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Венесуэла', 'wire_form' => '');
	$array[$i++] = array('name' => 'Виргинские Острова (Великобритания)', 'wire_form' => 'NA');
	$array[$i++] = array('name' => 'Виргинские Острова (США)', 'wire_form' => 'NA');
	$array[$i++] = array('name' => 'Восточный Тимор', 'wire_form' => '');
	$array[$i++] = array('name' => 'Вьетнам', 'wire_form' => '');
	$array[$i++] = array('name' => 'Габон', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гайана', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гаити', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гамбия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гана', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гваделупа', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гватемала', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гвиана', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гвинея', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гвинея-Бисау', 'wire_form' => '');
	$array[$i++] = array('name' => 'Германия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Гернси', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гибралтар', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гондурас', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гонконг', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гренада', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гренландия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Греция', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Грузия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Гуам', 'wire_form' => '');
	$array[$i++] = array('name' => 'Дания', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Джерси', 'wire_form' => '');
	$array[$i++] = array('name' => 'Джибути', 'wire_form' => '');
	$array[$i++] = array('name' => 'Доминика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Доминиканская Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Египет', 'wire_form' => '');
	$array[$i++] = array('name' => 'Замбия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Западная Сахара', 'wire_form' => '');
	$array[$i++] = array('name' => 'Зимбабве', 'wire_form' => '');
	$array[$i++] = array('name' => 'Йемен', 'wire_form' => '');
	$array[$i++] = array('name' => 'Израиль', 'wire_form' => '');
	$array[$i++] = array('name' => 'Индия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Индонезия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Иордания', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ирак', 'wire_form' => '');
	$array[$i++] = array('name' => 'Иран', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ирландия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Исландия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Испания', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Италия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Кабо-Верде', 'wire_form' => '');
	$array[$i++] = array('name' => 'Казахстан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Каймановы острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Камбоджа', 'wire_form' => '');
	$array[$i++] = array('name' => 'Камерун', 'wire_form' => '');
	$array[$i++] = array('name' => 'Канада', 'wire_form' => 'NA');
	$array[$i++] = array('name' => 'Катар', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кения', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кипр', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Киргизия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кирибати', 'wire_form' => '');
	$array[$i++] = array('name' => 'Китайская Народная Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Китайская Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кокосовые острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Колумбия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Коморские Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Конго (Браззавиль)', 'wire_form' => '');
	$array[$i++] = array('name' => 'Коста-Рика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кот-д’Ивуар', 'wire_form' => '');
	$array[$i++] = array('name' => 'Куба', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кувейт', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кука острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Кюрасао', 'wire_form' => '');
	$array[$i++] = array('name' => 'Лаос', 'wire_form' => '');
	$array[$i++] = array('name' => 'Латвия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Лесото', 'wire_form' => '');
	$array[$i++] = array('name' => 'Либерия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ливан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ливия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Литва', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Лихтенштейн', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Люксембург', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Маврикий', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мавритания', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мадагаскар', 'wire_form' => '');
	$array[$i++] = array('name' => 'Майотта', 'wire_form' => '');
	$array[$i++] = array('name' => 'Макао', 'wire_form' => '');
	$array[$i++] = array('name' => 'Македония', 'wire_form' => '');
	$array[$i++] = array('name' => 'Малави', 'wire_form' => '');
	$array[$i++] = array('name' => 'Малайзия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мали', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мальдивы', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мальта', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Марокко', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мартиника', 'wire_form' => '');
	$array[$i++] = array('name' => 'Маршалловы Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мексика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Микронезия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мозамбик', 'wire_form' => '');
	$array[$i++] = array('name' => 'Молдавия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Монако', 'wire_form' => '');
	$array[$i++] = array('name' => 'Монголия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Монтсеррат', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мьянма', 'wire_form' => '');
	$array[$i++] = array('name' => 'Мэн', 'wire_form' => '');
	$array[$i++] = array('name' => 'Нагорно-Карабахская Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Намибия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Науру', 'wire_form' => '');
	$array[$i++] = array('name' => 'Непал', 'wire_form' => '');
	$array[$i++] = array('name' => 'Нигер', 'wire_form' => '');
	$array[$i++] = array('name' => 'Нигерия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Нидерланды', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Никарагуа', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ниуэ', 'wire_form' => '');
	$array[$i++] = array('name' => 'Новая Зеландия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Новая Каледония', 'wire_form' => '');
	$array[$i++] = array('name' => 'Норвегия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Норфолк остров', 'wire_form' => '');
	$array[$i++] = array('name' => 'Объединённые Арабские Эмираты', 'wire_form' => '');
	$array[$i++] = array('name' => 'Оман', 'wire_form' => '');
	$array[$i++] = array('name' => 'Пакистан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Палау', 'wire_form' => '');
	$array[$i++] = array('name' => 'Палестина', 'wire_form' => '');
	$array[$i++] = array('name' => 'Панама', 'wire_form' => '');
	$array[$i++] = array('name' => 'Папуа', 'wire_form' => '');
	$array[$i++] = array('name' => 'Парагвай', 'wire_form' => '');
	$array[$i++] = array('name' => 'Перу', 'wire_form' => '');
	$array[$i++] = array('name' => 'Питкэрн острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Полинезия Французская', 'wire_form' => '');
	$array[$i++] = array('name' => 'Польша', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Португалия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Приднестровье', 'wire_form' => '');
	$array[$i++] = array('name' => 'Пуэрто-Рико', 'wire_form' => '');
	$array[$i++] = array('name' => 'Республика Косово', 'wire_form' => '');
	$array[$i++] = array('name' => 'Реюньон', 'wire_form' => '');
	$array[$i++] = array('name' => 'Рождества остров', 'wire_form' => '');
	$array[$i++] = array('name' => 'Руанда', 'wire_form' => '');
	$array[$i++] = array('name' => 'Румыния', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Саба', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сальвадор', 'wire_form' => '');
	$array[$i++] = array('name' => 'Самоа', 'wire_form' => '');
	$array[$i++] = array('name' => 'Самоа Американское', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сан-Марино', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сан-Томе и Принсипи', 'wire_form' => '');
	$array[$i++] = array('name' => 'Саудовская Аравия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Свазиленд', 'wire_form' => '');
	$array[$i++] = array('name' => 'Северная Корея', 'wire_form' => '');
	$array[$i++] = array('name' => 'Северные Марианские острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сейшельские Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сен-Бартелеми', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сенегал', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сен-Мартен', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сен-Пьер и Микелон', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сент-Винсент и Гренадины', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сент-Китс и Невис', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сент-Люсия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сербия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сингапур', 'wire_form' => '');
	$array[$i++] = array('name' => 'Синт-Мартен', 'wire_form' => '');
	$array[$i++] = array('name' => 'Синт-Эстатиус', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сирия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Словакия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Словения', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Соединённые Штаты Америки', 'wire_form' => 'NA');
	$array[$i++] = array('name' => 'Соломоновы Острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сомали', 'wire_form' => '');
	$array[$i++] = array('name' => 'Судан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Суринам', 'wire_form' => '');
	$array[$i++] = array('name' => 'Сьерра-Леоне', 'wire_form' => '');
	$array[$i++] = array('name' => 'Таджикистан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Таиланд', 'wire_form' => '');
	$array[$i++] = array('name' => 'Танзания', 'wire_form' => '');
	$array[$i++] = array('name' => 'Тёркс и Кайкос', 'wire_form' => '');
	$array[$i++] = array('name' => 'Того', 'wire_form' => '');
	$array[$i++] = array('name' => 'Токелау', 'wire_form' => '');
	$array[$i++] = array('name' => 'Тонга', 'wire_form' => '');
	$array[$i++] = array('name' => 'Тринидад и Тобаго', 'wire_form' => '');
	$array[$i++] = array('name' => 'Тувалу', 'wire_form' => '');
	$array[$i++] = array('name' => 'Тунис', 'wire_form' => '');
	$array[$i++] = array('name' => 'Турецкая Республика Северного Кипра', 'wire_form' => '');
	$array[$i++] = array('name' => 'Туркмения', 'wire_form' => '');
	$array[$i++] = array('name' => 'Турция', 'wire_form' => '');
	$array[$i++] = array('name' => 'Уганда', 'wire_form' => '');
	$array[$i++] = array('name' => 'Узбекистан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Украина', 'wire_form' => '');
	$array[$i++] = array('name' => 'Уоллис и Футуна', 'wire_form' => '');
	$array[$i++] = array('name' => 'Уругвай', 'wire_form' => '');
	$array[$i++] = array('name' => 'Фарерские острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Фиджи', 'wire_form' => '');
	$array[$i++] = array('name' => 'Филиппины', 'wire_form' => '');
	$array[$i++] = array('name' => 'Финляндия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Фолклендские острова', 'wire_form' => '');
	$array[$i++] = array('name' => 'Франция', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Хорватия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Центральноафриканская Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Чад', 'wire_form' => '');
	$array[$i++] = array('name' => 'Черногория', 'wire_form' => '');
	$array[$i++] = array('name' => 'Чехия', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Чили', 'wire_form' => '');
	$array[$i++] = array('name' => 'Швейцария', 'wire_form' => '');
	$array[$i++] = array('name' => 'Швеция', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Шри-Ланка', 'wire_form' => '');
	$array[$i++] = array('name' => 'Эквадор', 'wire_form' => '');
	$array[$i++] = array('name' => 'Экваториальная Гвинея', 'wire_form' => '');
	$array[$i++] = array('name' => 'Эритрея', 'wire_form' => '');
	$array[$i++] = array('name' => 'Эстония', 'wire_form' => 'EEA');
	$array[$i++] = array('name' => 'Эфиопия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Южная Корея', 'wire_form' => '');
	$array[$i++] = array('name' => 'Южная Осетия', 'wire_form' => '');
	$array[$i++] = array('name' => 'Южно-Африканская Республика', 'wire_form' => '');
	$array[$i++] = array('name' => 'Южный Судан', 'wire_form' => '');
	$array[$i++] = array('name' => 'Ямайка', 'wire_form' => '');
	$array[$i++] = array('name' => 'Япония', 'wire_form' => '');
// </editor-fold>

//	if(false == $not_translate){
            foreach ($array as &$country)
                    $country['name'] = _e($country['name']);
            unset($country);
//        }

	if($only_names == FALSE) {
		return $array;
	}

	//only countries names
	$country_names = array();

	foreach ($array as $c) {
		$country_names[] = $c['name'];
	}

	sort($country_names);
	if('ru' == get_instance()->lang->lang())
		array_unshift($country_names, _e("Россия"));

	return $country_names;
}

function get_countries($not_translate = false){
    ci()->load->model('countries_list_model');
    $r = ci()->countries_list_model->get_list();

    $res = [];
    foreach ($r as $row){
        if ($not_translate)
            $res[$row->iso2] = $row->eng_name;
        else
        $res[$row->iso2] = _e($row->name);
    }

    return $res;
}

function get_country($view = false, $not_translate = false) {
	$array = get_countries($not_translate);
	asort($array);
	if($current_lang == 'ru'){
            unset($array['RU']);
            $array = ['RU' => _e("Россия")] + $array;
        }

	if($view) {
		$ci = &get_instance();
		$ci->load->helper('form');
		return form_dropdown('place', $array, '1');
	} else
		return $array;
}

function cutString($string, $maxlen) {
	mb_regex_encoding('UTF-8');
	$len = (mb_strlen($string) > $maxlen) ? mb_strripos(mb_substr($string, 0, $maxlen), ' ') : $maxlen;
	$cutStr = mb_substr(strip_tags($string), 0, $len);
	return (mb_strlen($string) > $maxlen) ? '"' . $cutStr . '..."' : '"' . $cutStr . '"';
}

//function countCertificate($summa, $date_active) {
//    if ($date_active == '0000-00-00 00:00:00')
//        return $summa;
//    $day = round((time() - strtotime($date_active)) / 3600 / 24);
//
//    return $summa * (1 + (0.00065 * $day));
//}

function countCertificate($summa, $date_active) {
	if($date_active == '0000-00-00 00:00:00')
		return $summa;

	$cur_date_str = date('Y-m-d', time());
	$cur_timestamp = strtotime($cur_date_str);

	$date = explode(' ', $date_active);
	$item_timestamp = strtotime($date[0] . ' 00:00:00');
	$days = floor(($cur_timestamp - $item_timestamp) / 3600 / 24);
	return $summa * (1 + (0.00065 * $days));
}

//Location: in account_model

function standartPercent($time) {
	return getPercent($time, 's');
}

//Location: in account_model

function garantPercent($time) {
	return getPercent($time, 'g');
}

function getGarantPercent() {
	return get_instance()->config->item('garant-percent');
}

//Location: in account_monel

function getPercent($time, $type = 'g') {
	if($type == 'g')
		$data = getGarantPercent();
	else
		$data = get_instance()->config->item('standart-percent');

	$all = array_key_exists('*', $data);

	if($all !== false)
		return $data['*'];

	foreach ($data as $index => $item)
		if($time <= $index)
			return $item;
}

function paymentArray() {
	return array('Webmoney', 'Yandex', 'Paypal', 'Qiwi', 'Bank', 'Card', 'Liqpay', 'Tinkoff');
}

function checkPaymentAdmin($data, $value) {
	$out = " value='$value' ";
	if(in_array($value, $data, true)) {
		$out .= "checked='checked'";
	}
	$index = array_search($value, $data, true);
	if(isset($data[$index]))
		unset($data[$index]);

	return $out;
}

function creditPayment($str) {

//    if (empty($str))
//        return;
//
//
//    $data = @unserialize($str);
//    if ($data === false)
//        return $str;
//
//
//    foreach ($data as $index => $item) {
//        if (empty($item))
//            unset($data[$index]);
//    }
//
//    return implode(', ', $data);
	return "";
}

//function creditImgPayment($str) {
//
//    if (empty($str))
//        return;
//
//    $data = unserialize($str);
//
//    $out = '';
//
//    foreach ($data as $item) {
//
//        if (empty($item))
//            continue;
//
//        $out.="<img  src='/img/" . $item . ".png' />";
//    }
//
//    return $out;
//}

function socialList($image = '') {
	$list = array('odnoklassniki' => 'ok.png', 'vkontakte' => 'vk.png', 'twitter' => 'tw.png', 'google_plus' => 'gp.png', 'facebook' => 'fb.png', 'mail_ru' => 'mr.png');
	return !empty($list[$image]) ? $list[$image] : array_keys($list);
}

function google_plus2($link = false) {
	$client_id = '556330387880.apps.googleusercontent.com'; // Client ID
	$client_secret = 'i7s5sHWpvlMaa0Rz4Azi0cPQ'; // Client secret
	$redirect_uri = 'http://' . base_url_shot() . '/login/google_plus'; // Redirect URIs
	$url = 'https://accounts.google.com/o/oauth2/auth';

	if($link) {

		$params = array(
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code',
			'client_id'     => $client_id,
			'scope'         => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
		);

		return $url . '?' . urldecode(http_build_query($params));
	}

	return array(
		'redirect_uri'  => $redirect_uri,
		'client_secret' => $client_secret,
		'client_id'     => $client_id,
		'url'           => $url);
}

function google_plus($link = false) {
	$client_id = '556330387880.apps.googleusercontent.com'; // Client ID

//    $client_secret = 'i7s5sHWpvlMaa0Rz4Azi0cPQ'; // Client secret
//    $redirect_uri = 'https://' . base_url_shot() . '/login/google_plus'; // Redirect URIs
//    $client_id = '302134150346-kd60luahq7fks80i4gkvfihup57g0ppp.apps.googleusercontent.com'; // Client ID
//    $client_secret = 'i7s5sHWpvlMaa0Rz4Azi0cPQ'; // Client secret

	$redirect_uri = 'https%3A%2F%2Fwebtransfer.com%2Flogin%2Fgoogle_plus'; //'https://' . base_url_shot() . '/'; // Redirect URIs

	$url = 'https://accounts.google.com/o/oauth2/auth';

	if($link) {

//        $params = array(
//            'response_type' => 'code',
//            'redirect_uri' => $redirect_uri,
//            'client_id' => $client_id,
//            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
//            'access_type' => 'online',
//            'approval_prompt' => 'auto',
//        );

//        return $url . '?' . urldecode(http_build_query($params));

		return $url . '?' .
		'response_type=code&' .
		'redirect_uri=' . $redirect_uri . '&' .
		'client_id=' . $client_id . '&' .
		'scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile&' .
		'access_type=online&' .
		'approval_prompt=auto';
	}
}

function countSertificate($index) {
	$file_handle = fopen('./upload/buyandselltable1.csv', "r");
	setlocale(LC_ALL, 'ru_RU.UTF8', 'ru_RU', 'rus');
	$today = strftime('%d %B %Y г.');

	while (!feof($file_handle)) {
		$line = fgets($file_handle);
		$line = explode(";", $line);
		if(empty($line[1]))
			break;

		$line[1] = preg_replace('/"/sui', '', $line[1]);

		if($today == $line[1])
			return $line[$index];
	}
}

function credit_summ($percent, $summ, $time) {
	$per = $percent / 100 * $time;
	return round(($summ + $summ * $per) * 100) / 100;
}

function invest_summ($percent, $summ, $time, $bonus) {
	$sum_bon = $summ * (($bonus / 100) + 1);
	$per = $sum_bon * ((($percent / 100) / 12) * $time);
	return round($per, 2);
}

/*
 * Статусы кредитов
 */

function accaunt_debit_status($state) {

	switch ($state) {

		case "1":
			return _e('vistavlena');

		case "2":
			return _e('active');

		case "3":
			return _e('waiting');

		case "4":
			return _e('prosrochen');

		case "5":
			return _e('viplachen');

		case "7":
			return _e('otmenen');

		case "8":
			return _e('annulirovan');

		default :
			return _e('udalen');
	}
}

function check_state($state) {
	if($state == 1 or $state == 7)
		return true;
	return false;
}

function get_region($view = false) {

	$array = array(
		"Алтайский край",
		"Амурская область",
		"Архангельская область",
		"Астраханская область",
		"Белгородская область",
		"Брянская область",
		"Владимирская область",
		"Волгоградская область",
		"Вологодская область",
		"Воронежская область",
		"г. Санкт-Петербург",
		"Еврейская автономная область",
		"Забайкальский край",
		"Ивановская область",
		"Иркутская область",
		"Кабардино-Балкарская Республика",
		"Калининградская область",
		"Калужская область",
		"Камчатский край",
		"Кемеровская область",
		"Кировская область",
		"Костромская область",
		"Краснодарский край",
		"Красноярский край",
		"Курганская область",
		"Курская область",
		"Ленинградская область",
		"Липецкая область",
		"Магаданская область",
		"Мурманская область",
		"Ненецкий автономный округ",
		"Нижегородская область",
		"Новгородская область",
		"Новосибирская область",
		"Омская область",
		"Оренбургская область",
		"Орловская область",
		"Пензенская область",
		"Пермский край",
		"Приморский край",
		"Псковская область",
		"Республика Адыгея",
		"Республика Алтай",
		"Республика Башкортостан",
		"Республика Бурятия",
		"Республика Дагестан",
		"Республика Ингушетия",
		"Республика Калмыкия",
		"Республика Карачаево-Черкесия",
		"Республика Карелия",
		"Республика Коми",
		"Республика Марий Эл",
		"Республика Мордовия",
		"Республика Саха (Якутия)",
		"Республика Северная Осетия-Алания",
		"Республика Татарстан",
		"Республика Тыва",
		"Республика Хакасия",
		"Ростовская область",
		"Рязанская область",
		"Самарская область",
		"Саратовская область",
		"Сахалинская область",
		"Свердловская область",
		"Смоленская область",
		"Ставропольский край",
		"Тамбовская область",
		"Тверская область",
		"Томская область",
		"Тульская область",
		"Тюменская область",
		"Удмуртская Республика",
		"Ульяновская область",
		"Хабаровский край",
		"Ханты-Мансийский автономный округ - Югра",
		"Челябинская область",
		"Чеченская Республика",
		"Чувашская Республика",
		"Чукотский автономный округ",
		"Ямало-Ненецкий автономный округ",
		"Ярославская область",
	);

	sort($array);
	array_unshift($array, "Московская область", "г. Москва");

	if($view) {
		$ci = &get_instance();
		$ci->load->helper('form');
		return form_dropdown('place', $array, '1');
	} else
		return $array;
}

function get_bisness($view = false) {
	$CI = &get_instance();
	$result = $CI->db->order_by('title')->get('business_center')->result();
	if($view === true) {
		$out = $A = $B = '';
		foreach ($result as $item) {
			if($item->type == 'A')
				$A .= '<li  style="font-size: 16px;">' . $item->title . '</li>';
			else
				$B .= '<li  style="font-size: 16px;">' . $item->title . '</li>';
		}

		$out = '<h2>'._e('Бизнес центры  класса  "A"').'</h2><br>' . $A . '<br><h2>'._e('Бизнес центры  класса  "B"').'</h2>' . $B;
	} else {
		$out = array();

		foreach ($result as $item) {

			$out[$item->id] = $item->title;
		}

		asort($out);

		$out[99999] = _e('Другой');

		if($view == 's') {

			$CI->load->helper('form');

			return form_dropdown('w_place', $out);
		}
	}
	return $out;
}

function create_link($url, $parent = true) {//обдумать
	$data = site_url('page') . '/';

	if($parent == false)
		return $data . $url;

	$CI = &get_instance();

	$id_parent = $CI->db->select("id_parent")->from('pages')->where(array('active' => 1, 'url' => $url))->get()->row("id_parent");

	while (!empty($id_parent)) {
		$page = $CI->db->select("url, title,id_parent")->from('pages')->where(array('active' => 1, 'id_page' => $id_parent))->get()->row();
		$id_parent = @$page->id_parent;

		if($id_parent != "")
			$data .= $page->url . '/';
	}
	return $data . "$url";
}

function date_formate_view($date) {
	return date_formate_my($date, 'd/m/Y');
}

function date_formate_save($date) {
	return date_formate_my($date, 'Y-m-d');
}

function date_formate_my($date, $format) {
	if(empty($date)) return '';
	$date = str_replace('/', '-', $date);
	return date_format(date_create($date), $format);
}

function price_format_view($price, $round = true) {
	return ($round) ? number_format($price, 0, '', ' ') : number_format($price, 2, '.', ' ');
}

//if zero == true, all digits will be >= 0
function price_format_double($price, $zero = FALSE, $floor = FALSE) {
	$price_tmp = $price;
	if($floor)
		$price_tmp = floor($price * 100) / 100;

	$price_view = price_format_view($price_tmp, false);
	if($zero)
		return ($price_view < 0 ? 0 : $price_view);

	return $price_view;
}

function price_format_save($price) {
	return preg_replace('/\s+/sui', '', $price);
}

function no_error($var, $val) {
	if(is_array($var))
		return (isset($var[$val])) ? $var[$val] : '';

	if(is_object($var))
		return (isset($var->$val)) ? $var->$val : '';
	return (isset($var)) ? $var : '';
}

function view_online($date) {
	$onlineDate = date_create($date);
	date_modify($onlineDate, "+ 20 minute");
	$onlineDate = date_format($onlineDate, 'Y-m-d H:i:s');
	$now = date('Y-m-d H:i:s');
	return ($onlineDate > $now) ? "Онлайн" : $date;
}

/* Новое рассмотренное */

function genRandomPassword($len = 6, $char_list = 'a-z,0-9') {
	$chars = array();
	// предустановленные наборы символов
	$chars['a-z'] = 'qwertyuiopasdfghjklzxcvbnm';
	$chars['A-Z'] = strtoupper($chars['a-z']);
	$chars['0-9'] = '0123456789';
	$chars['~'] = '~!@#$%^&*()_+=-:";\'/\\?><,.|{}[]';

	// набор символов для генерации

	$charset = '';

	// пароль

	$password = '';

	if(!empty($char_list)) {
		$char_types = explode(',', $char_list);
		foreach ($char_types as $type) {
			if(array_key_exists($type, $chars)) {
				$charset .= $chars[$type];
			} else {
				$charset .= $type;
			}
		}
	}

	for ($i = 0; $i < $len; $i++) {
		$password .= $charset[rand(0, strlen($charset) - 1)];
	}
	return $password;
}

function getTransactionLabelStatus($var = false) {
	$data = getTransactionStatuses();
	return ($var) ? $data[$var] : $data;
}

function getBonusLabel($var = false) {
	$data = array(  1 => 'BONUS',
                    2 => 'WTUSD2 (Новый)',
                    3 => 'P-CREDS',
                    4 => 'C-CREDS',
                    5 => 'WTUSD5 (Старый)',
                    6 => 'WTDEBIT'

        );
	return ($var) ? $data[$var] : $data;
}

function getBonusLabelUser($var = false) {
	$data = array(  1 => 'BONUS',
                    2 => 'WTUSD<span style="color:#fdb1b1;">&#10084;</span>',
                    3 => 'P-CREDS',
                    4 => 'C-CREDS',
                    5 => 'WTUSD1',
                    6 => 'WTDEBIT'
        );
	return ($var) ? $data[$var] : $data;
}

function getPaySys() {
	$CI = &get_instance();
	return $CI->config->item('pay_sys');
}

function getTransactionStatuses() {
	$CI = &get_instance();
	return $CI->config->item('transaction_statuses');
}

function getCreditStatuses() {
	$CI = &get_instance();
	return $CI->config->item('credit_statuses');
}

function getNameofPaymentSys() {
	$payName = getPaySys();
	$paySys  = getPaymentSys();
	$name    = array();
	foreach ($paySys as $pay_id)
		$name[] = $payName[$pay_id][0];
	return $name;
}


function getTransactionsMethodLabel($val = false, $admin = false) {
	$methods = getPaySys();
	$userMethods  = array();
	$adminMethods = array();
	foreach ($methods as $key => $value) {
		if($value[1] == 0)
			$userMethods[$key] = $value[0];
		$adminMethods[$key] = $value[0];
	}

	if($admin)
		$data = $adminMethods;
	else
		$data = $userMethods;

	return ($val) ? (isset($data[$val]) ? $data[$val] : $val) : $data;
}

// текст на рисунок

function imageText(&$img, $text, $size, $x, $y, $font = './upload/ttfontdata/DejaVuSerifCondensed.ttf', $color = array(0, 0, 0)) {
	$colorImg = imagecolorallocate($img, $color[0], $color[1], $color[2]); // задаем цвет для вписываемого текста
	imagettftext($img, $size, 0, $x, $y, $colorImg, $font, $text); // наносим текст на изображение (будьте осторожны с применением различных шрифтов. многие из них не поддерживают кириллицу!)
}

/**
 * Retrieve a single key from an array. If the key does not exist in the
 * array, the default value will be returned instead.
 *
 *     // Get the value "username" from $_POST, if it exists
 *     $username = arr_get($_POST, 'username');
 *
 *     // Get the value "sorting" from $_GET, if it exists
 *     $sorting = arr_get($_GET, 'sorting');
 *
 * @param   array $array array to extract from
 * @param   string $key key name
 * @param   mixed $default default value
 * @return  mixed
 */
function arr_get($array, $key, $default = NULL) {
	return isset($array[$key]) ? $array[$key] : $default;
}

// для работы с партнеркой

function partnerPercent($time) {
	$data = get_instance()->config->item('partner-percent');
	foreach ($data as $index => $item) {
		if($time <= $index)
			return $item;
	}
}

function partnerPay($income, $time) {
	return $income * (partnerPercent($time) / 100);
}

function transactionsPartnerType($val) {

	if(empty($val))
		return "не указано";

	switch ($val) {
		case "1":
			return "Регистрация  нового  пользователя";
		case "2":
			return "Получение процентов с вклада";
		case "3":
			return "Снятие наличных  средств  с кошелька";
	}
}

#добавление попапа с текстом
function add_universal_popup( $type, $title, $body, $id, $props = NULL )
{

    $ci = &get_instance();
    $ci->load->library('content');

    if( empty( $type )
        || empty( $title )
        || empty( $body )
        || empty( $id )
    ){
        return FALSE;
    }

    if( !isset( $ci->content->data ) )
            $ci->content->data = new stdClass();

    if( !isset( $ci->content->data->universal_popups ) )
            $ci->content->data->universal_popups = array();

    if( !isset( $ci->content->data->universal_popups[$type] ) )
            $ci->content->data->universal_popups[$type] = array();


    $universal_popups = array();

    $universal_popups = array( 'body' => _e( $body ), 'id_prop' => $id );

    if( !empty( $title ) )
        $universal_popups['title'] = _e( $title ) ;

    if( !isset( $props['add_style_prop'] ) )
        $universal_popups['add_style_prop'] = $props['style'];

    if( !isset( $props['add_class_prop'] ) )
        $universal_popups['add_class_prop'] = $props['class'];

    $ci->content->data->universal_popups[$type][] = $universal_popups;

}

#вывод всех попапов
function show_universal_popups( $type = NULL )
{
    $ci = &get_instance();
    $ci->load->library('content');
    $ci->load->library('language');

    if( !isset( $ci->content->data )
        || !isset( $ci->content->data->universal_popups )
        || ( !empty( $type ) && !isset( $ci->content->data->universal_popups[$type] ) )
    )
        return FALSE;

    if( empty( $type ) )
    {
        foreach( $ci->content->data->universal_popups as $popup_groups )
            foreach( $popup_groups as $popup )
            {
                $data = array( 'popup' => $popup );

                $ci->load->view('user/accaunt/blocks/renderUniversalTextPopup', $data);
            }
    }
    else
        foreach( $ci->content->data->universal_popups[ $type ] as $popup )
        {
            $data = array( 'popup' => $popup );

            $ci->load->view('user/accaunt/blocks/renderUniversalTextPopup', $data);
        }
}

// ------------------------------------------------------------------------


function base_url_shot() {
	$CI = &get_instance();
	return $CI->config->item('base_url_shot');
}

function is_develop() {
	$CI = &get_instance();
	return $CI->config->slash_item('develop');
}

function get_sys_var($name){
   $ci = &get_instance();
   $ci->load->model('System_vars_model');
   return $ci->System_vars_model->get_var($name);
}


/**
 * Функция работает с двумерным массивом
 * (элементы которого могут быть как массивом так и stdClass-ом)
 * и устанавливает в качестве ключей
 * первого уровня выбранный ключ ($key) второго уровня
 *
 * @param array $array
 * @param string $key
 * @return array
 */
function array_set_value_in_key($array, $key = 'id')
{
    $temp_array = array();

    foreach($array as $val)
    {
        if(is_array($val))
        {
            if(!isset($val[$key]))
            {
                return false;
            }

            $temp_array[$val[$key]] = $val;
        }

        if(is_object($val))
        {
            if(!isset($val->{$key}))
            {
                return false;
            }

            $temp_array[$val->{$key}] = $val;
        }
    }

    return $temp_array;
}
/**
 * функции для дебага
 */

function pre() {
	$str = '';

	foreach (func_get_args() as $val) {
		$str .= print_r($val, true) . "\n";
	}

	echo '<pre>' . $str . '</pre>';
}

function pred() {
	$str = '';

	foreach (func_get_args() as $val) {
		$str .= print_r($val, true) . "\n";
	}

	echo '<pre>' . $str . '</pre>';

	die;
}


function vre() {
	echo('<pre>');
	$args = func_get_args();
	if(count($args) > 1) {
		$data = $args;
	} elseif(count($args) == 1) {
		$data = $args[0];
	} else {
		$data = null;
	}
        var_dump($data);
	echo('</pre>');
}

function vred() {
	echo('<pre>');
	$args = func_get_args();
	if(count($args) > 1) {
		$data = $args;
	} elseif(count($args) == 1) {
		$data = $args[0];
	} else {
		$data = null;
	}
        var_dump($data);
	echo('</pre>');

	die;
}



function get_current_user_id(){

        $CI = &get_instance();
        $CI->load->model('accaunt_model', 'accaunt');
        return $CI->accaunt->get_user_id();
}


function get_social_id($user_id){

        $CI = &get_instance();
        $CI->load->model('users_filds_model', 'usersFilds');
	//$user_id = 500150;
        if ( empty($user_id) ){
            $CI->load->model('accaunt_model', 'accaunt');
            $user_id = $CI->accaunt->get_user_id();
        }

        return $CI->usersFilds->getUserFild($user_id, 'id_network');
}



/**
 * Загружаем js файл при необходимости избавляем от кэша.
 * Переменны с версиями находятся по адрессу - applicatin/config/load_js_css.php
 *
 * @param string $path_to_file путь к фалу
 * @param string $module переменная модуля в которой указана версия скрипта
 * @param array $dop_data дополнительные данные
 * @return string
 */
function add_js_script($path_to_file, $module, $dop_data = [] )
{
//    <link async rel="stylesheet" type="text/css" href="/css/user/currency_exchange.css"/>
//    <script async type="text/javascript" src="/js/user/sms_module.js"></script>
    $async = false;

    if (isset($dop_data['async']))
    {
        $async = $dop_data['async'];
    }

    $CI = &get_instance();
    $CI->load->config('load_js_css');
//    var_dump($CI->config->item('vkontakteConfig2'));
//    die;
    $modules = $CI->config->item('modules');

    $path_to_file .= '?t='.(isset($modules[$module])?$modules[$module]:$modules['default']);

    return sprintf('<script %s type="text/javascript" src="%s"></script>',$async ,$path_to_file);
}


/**
 * Загружаем css файл при необходимости избавляем от кэша.
 * Переменны с версиями находятся по адрессу - applicatin/config/load_js_css.php
 *
 * @param string $path_to_file путь к фалу
 * @param string $module переменная модуля в которой указана версия скрипта
 * @param array $dop_data дополнительные данные
 * @return string
 */
function add_css_style($path_to_file, $module, $dop_data = [] )
{
    $async = false;

    if (isset($dop_data['async']))
    {
        $async = $dop_data['async'];
    }

    $CI = &get_instance();
    $CI->load->config('load_js_css');
    $modules = $CI->config->item('modules');

    $path_to_file .= '?t='.(isset($modules[$module])?$modules[$module]:$modules['default']);
    return sprintf('<link %s rel="stylesheet" type="text/css" href="%s"/>',$async ,$path_to_file);
}


function isUserUSorCA($user_id = NULL){
    $CI = &get_instance();
    $CI->load->model('Accaunt_model','accaunt');
    return $CI->accaunt->isUserUSorCA($user_id);
}


 function get_acc_type($acc){

        $type = 'unknown';
        $id = NULL;
        $code = 0;
        $method = '';
        $paysys = '';
        $ps = getPaymentSys();

        if ( substr($acc,0,5) == 'card_'){
            $type = 'card';
            $paysys = 'card';
            $id = (int)substr($acc, 5 );
            $code = 328;
        } elseif ( $acc == 'wtdebit'){
            $type = 'payment_account';
            $paysys = 'wt';
            $id = 6;
        } elseif ( in_array($acc, ['payeer','perfect','okpay','nixmoney']) ){
            $type = 'pay_sys';
            $paysys = $acc;
            $id = $acc;
            switch( $acc ){
                case 'payeer':
                    $code = 312;
                    break;
                case 'okpay':
                    $code = 319;
                    break;
                case 'perfect':
                    $code = 318;
                    break;
            }

        } else
            return NULL;

        return ['type' => $type,
                'id' => $id,
                'code' => $code,
                'method' => $ps[$code],
                'paysys' => $paysys

                ];
    }


function dev_log($text, $name = 'dev')
    {
        $n = APPPATH."logs/$name".date('Y-m-d').".log";
        $f = fopen($n, "a+");
        $str = PHP_EOL.date('Y-m-d H:i:s').": ".$text;
        fputs($f, $str, strlen($str));
        fclose($f);


    }






function get_transaction_by_value($val, $where = [])
{
    $CI = &get_instance();
    $CI->load->model('Transactions_model','transactions');
    return $CI->transactions->getTransactionByValue($val, $where);
}


function debug_stack() {
	print_r( debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) );
}

function validate_options($keys, $options) {
	// Благодоря данной функции вам никогда не придется запоминать в какой очередности распологаются все переменные в функцию, особенно если их много. теперь их можно указывать в массиве ключами.
	// Так же можно задавать дефолтные значения переменным и больше не переживать что такая переменная должна быть только с правой стороны
	// Запускаем нужную нам функцию
	// $this->currency_exchange->test1(array('a' => 1, 'b' => 2));

	// А вот наша функция. в ней прописываем две строки
	// public function test1($options) {
	//     $pr = validate_options( array('a' => 'required', 'b' => 'required', 'c' => 'required', 'd' => 'my custom default value 123'), $options );
	//     extract($pr);
	// }


 	//	Проверяет, передаются ли основные параметры в данную функцию
	if( !empty($keys) && !empty($options)) {
   	$miss_keys = "";

   	// проходим по всем заданым в условие значениям
   	// k - название требуемого ключа
   	// v - параметр
			// required - обязательный
   		// 'my value' - my custom value
    	foreach($keys as $k => $v) {
      	if(!array_key_exists($k, $options) &&  $v == 'required')
        		$miss_keys .= $k . PHP_EOL;
        	else if (empty($options[$k] ) && $v != 'required')
        		$options[$k] =  $keys[$k];
    	}

   	if(!empty($miss_keys)) {
   		echo 'You need add this keys to parameters: ' . $miss_keys . PHP_EOL  . '<br>';
   		print_r($options);
   		debug_stack();
   		die();
   	}
  	}
  	else {
   	print_r($options);
  		echo 'function validate_options not have all parameters!!!' . PHP_EOL . '<br>';
   	debug_stack();
   	die();
  	}

  	return $options;
}


function getTransactionByParams($where, $isOne = FALSE){
    ci()->load->model("transactions_model", "transactions");
    return ci()->transactions->getTransactionByParams($where, $isOne);
}


function get_redirect_form($url, array $data, $target = '') {
        if(!empty($target))
            $target = " target='$target'";
        $str = '';
        $str .= "<form action='$url' method='post' name='frm' $target>";
        foreach ($data as $a => $b) {
            $str .= "<input type='hidden' name='".htmlentities($a)."' value='".htmlentities($b)."'>";
        }
        $str .= "</form>";
        $str .= "<script language=\"JavaScript\">document.frm.submit();</script>";
        return $str;
}

function isDevSite(){
    return (strpos(base_url(),'wtest2.canned') !== false );
}


function get_cackle_user_info(){



    $user_id = get_current_user_id();
    if ( empty($user_id))
        return '';

    $ci = ci();
    $ci->load->model('accaunt_model', 'accaunt');
    $ci->load->model('users_photo_model', 'users_photo');
    $ci->load->model('Base_model');

   $fields = [
        'id_user'      => '',
        'name'         => '',
        'sername'      => '',
        'email'        => '',
        'parent'       => ''
    ];


    $ava = $ci->Base_model->getUserAvatars($user_id);
    $user_avatar  = $ava[0];
    $ci->load->model('users_photo_model', 'users_photo');
    $user_avatar = getUserAvatar($user_id, FALSE);

    $data = (object) [];
    $ci->accaunt->set_user($user_id);
    $ci->accaunt->get_user($data);
    foreach($fields as $field => &$v)
        $v = $data->user->{$field};

    $user = array(
        'id' => $user_id,
        'name' => $fields['sername'].' '.$fields['name'],
        'email' => $fields['email'],
        'avatar' => (empty($user_avatar) ? substr(base_url(),0,-1).'/img/no-photo.gif' : $user_avatar)
    );
    $siteApiKey = config_item('cackle_site_api_key');
    $user_data = base64_encode(json_encode($user));
    $timestamp = time();
    $sign = md5($user_data . $siteApiKey . $timestamp);


    return "$user_data $sign $timestamp";
}

function http_post( $url, $data)
{
          $ch = curl_init();
          curl_setopt ($ch, CURLOPT_URL, $url );
          curl_setopt ($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_VERBOSE, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 60);

          curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
          $res = curl_exec($ch);

          if ( !$res )
             return '{ error: '. curl_error($ch).'('.curl_errno($ch).') }';
          else
            return $res;
          curl_close ($ch);

}


function image2base64($path, $need_decode=FALSE){
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    if ( $need_decode)
        $data = ci()->code->decrypt($data);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $base64;
}

function pdf2base64($path, $need_decode=FALSE){
    $data = file_get_contents($path);

    if ( $need_decode)
        $data = ci()->code->decrypt($data);

    $base64 = base64_encode($data);
    return $base64;

}


function set_whitelabel($master){

        $r = ci()->db->where('id', $master)->get('wtauth_sites')->row();
        if ( !empty($r) && !empty($r->master) && !empty($r->name) ){
            $GLOBALS['WHITELABEL'][$r->url] = ["id" => $r->master, 'name' => $r->name];
            $GLOBALS['WHITELABEL_IDS'][$r->master] = $r->name;
            $GLOBALS['WHITELABEL_HOST_IDS'][$r->master]  = ['host' => $host, 'name' => $r->name];
            $GLOBALS["WHITELABEL_ID"] = $r->master;
            $GLOBALS["WHITELABEL_NAME"] = $r->name;
            $GLOBALS["WHITELABEL_LOGO"] = $r->logo;
        }

    }



