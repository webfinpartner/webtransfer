<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function admin_id() {
    return Permissions::getInstance()->getAdminId();
}

function _u($resource){
    $p = Permissions::getInstance()->getPermissions();
    if(isset($p['admin.'.$resource]))
        return $p['admin.'.$resource]['url'];
    else die('не верный ресурс для отображения url');
}

function cookie_auth($token,$remember=1){
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
               'name'   => 'auth_hesh',
               'value'  => $token,
               'expire' => $time,
               'domain' => '',
               'path'   => '/',
               'prefix' => '',
           );

    $CI ->input->set_cookie($cookie);
}

function render_leng_select($def = false){
    $ci = get_instance();
    $lang = $ci->lang->get_langs();
//    $res = "";
    foreach ($lang as $key => $cod) {
        echo "<option value='$key' ".(($def == $key) ? "selected='selected'": "").">$cod</option>".PHP_EOL;
    }
}

function render_whitelabel_select($def = false){
    $lang = $GLOBALS['WHITELABEL_IDS'];
//    $res = "";
    foreach ($lang as $key => $cod) {
        echo "<option value='$key' ".(($def == $key) ? "selected='selected'": "").">$cod</option>".PHP_EOL;
    }
}

function checkIpList($ip,$ip_white,$ip_firewall){
    $ip = trim($ip);
    $ip_bin = ip2long($ip);
    foreach ($ip_white as $item_ip)
        if($item_ip[2] == ($ip_bin & $item_ip[1])) return "Белый список ($item_ip[0])";

    foreach ($ip_firewall as $item_ip){
//        echo $ip."<br/>".$item_ip[0]."<br/>".decbin($item_ip[2])." fw ip<br/>".decbin($ip_bin)." - user ip<br/>".decbin($item_ip[1])." - mask<br/>".decbin(($ip_bin & $item_ip[1]))." - res<br/><br/>";
        if($item_ip[2] == ($ip_bin & $item_ip[1])) return "Фаервол ($item_ip[0])";
    }

    return false;
}

function ipWork($row,$ip_white,$ip_firewall) {
    $users = '';
    $user_col = '';
    $shot_users = '';
    $users_id = explode(',', $row->id_user);
    $all_users = count($users_id);
        if(1 == $all_users && 0 == $users_id[0]) return array("-", "");
    $ip_status = checkIpList($row->ips,$ip_white,$ip_firewall);
    $ip = str_replace('.', "_", $row->ips);
    $c = 0;
    foreach ($users_id as $user){
        $c++;
        $users .= " <a href='/opera/users/$user' target='_blank'>$user</a>,";
        $user_col .= "$c) <a href='/opera/users/$user' target='_blank'>$user</a><br/>";
        if(6 < $all_users && 6 > $c) $shot_users = $users;
    }
    $users = substr($users, 0, strlen($users)-1);
    if (!empty($ip_status)) $shot_users = $ip_status." ";

    if (!empty($shot_users)){
        $shot_users = substr($shot_users, 0, strlen($shot_users)-1);
        $shot_users .= " <a href='' onclick='$(\"#$ip\").dialog(\"open\");return false;'>...</a>";
        $cc = 2;
        $users = $user_col;
    }
    return array($users, $shot_users);
}


function createIpslink(array $ips) {
    $all = array();
    $html = '';
    foreach ($ips as $row) {
        $ip = explode(', ', $row->ips);
        $row->ips = $ip[0];
        $html .= "<a href='/opera/users/users_block/$row->ips' target='_blank'>$row->ips</a><br/>";
        $all[] = $row->ips;
    }
//    $html = "<form action='/opera/users/users_block' me><a href='/opera/users/users_block/".implode(',', $all)."' target='_blank'>Открыть все вместе...</a><br/>".$html;
    echo $html;
}

function createOrder($order, $table = '') {
    $add_table = "";
    if ($table != '')
        $add_table = "$table.";
    foreach ($order as $key => $value)
        get_instance()->db->order_by("$add_table$key", $value);
}

function createSearch($search, $cols, $table = '') {
    $search = explode(" ", $search);
    foreach ($search as $word) {
        $wordEncript = get_instance()->code->code($word);
        $word = preg_match("/[а-я]/i", $word) ? "text" : $word;

        $add_table = "";
        if ($table != '')
            $add_table = "$table.";
        $where = "(";
        foreach ($cols as $col => $type) {
            if ($type == 'no-search')
                continue;
            if ("encript" == $type)
                $where .= $add_table . "$col = '$wordEncript' OR ";
            else
                $where .= $add_table . "$col LIKE '%$word%' OR ";
        }

        $where = substr($where, 0, strlen($where) - 4);
        $where .= ")";

        get_instance()->db->where($where);
//            "("
//                . "id_user LIKE '%$word%' OR "
//                . "name = '$wordEncript' OR "
//                . "sername = '$wordEncript' OR "
//                . "patronymic = '$wordEncript' OR "
//                . "email = '$wordEncript' OR "
//                . "parent LIKE '%$word%' OR "
//                . "reg_date LIKE '%$word%' OR "
//                . "ip_address = '$wordEncript' OR "
//                . "phone = '$wordEncript' OR "
//                . "online_date LIKE '%$word%' OR "
//                . "state LIKE '%$word%'"
//                . ")"
    }
}


function invest_time($date,  $time)
{
	return debit_time($date, $time, 'month');
}

function credit_time($date,  $time)
{
	return debit_time($date, $time, 'day');
}

function debit_time($date, $time, $period){
    $date = str_replace('/','-',$date);
    $date = date_create($date);

    date_modify($date, "+$time $period");
    return date_format($date, 'Y-m-d');
}

function calculate_debit_time_now($date){
    $date_full_ticks = strtotime($date);
    $date_ticks = strtotime(date('d-m-Y 00:00:00', $date_full_ticks));
    $date_now_ticks = strtotime(date('d-m-Y 00:00:00'));

    $time_days = round(($date_now_ticks - $date_ticks)/(60*60*24));

    return $time_days;
}

function  debit_user($item)
{
	$ci = get_instance();
	$user->name = (!empty($item->name))? $ci->code->decrypt($item->name):"";
	$user->sername = (!empty($item->sername))? $ci->code->decrypt($item->sername):"";
	$user->patronymic = (!empty($item->patronymic))? $ci->code->decrypt($item->patronymic):"";
	$user->phone = (!empty($item->phone))? $ci->code->decrypt($item->phone):"";
	return $user;

}

function check_status($status=false)
{
	$data = array(
		'1'=>"Выставлена",
		'2'=>"Активный",
		'3'=>"В ожидании",
		'4'=>"Просрочен",
		'5'=>"Выплачен",
		'6'=>"--Выплачен",
		'7'=>"Отменен"


		//'1'=>"Новый",
		//'2'=>"На рассмотрении",
		//'3'=>"Активный",
		//'4'=>"Отклонен",
		//'5'=>"Просрочен",
		//'6'=>"Выплачен",
		//'7'=>"Отклонен  пользователем"

	);
	return ($status)?$data[$status]:$data;
}

function checkActive($status)
{
	switch($status)
	{
		case '1': return "Новый";
		case '2': return "Активный";
		case '3': return "Отклонен";
	}
}

function get_menu_banner()
{
	$CI = & get_instance ();
	$data=$CI->db->get_where('pages', array('id_parent'=>'0'))->result();
	$out[0]="Без раздела";
	foreach($data as $var)
	{
		$out["{$var->id_page}"]= $var->title;
	}
	return $out;
}

function news_menu($value,$class='redBack')
{
	if(!empty($value)) echo '<span class="'.$class.'">'.$value.'</span>';
}

function get_patents()
{
	$CI = & get_instance ();
	return $CI->db->get('pages')->result();
}

function menu_moderator()
{
	return true; //(get_instance()->admin_info->manager != 1) ? false :  true;
}

function access_moderator()
{
//	if(get_instance()->admin_info->manager != 1)
//	{
//		show_404();  exit;
//	}
}

function  admin_state()
{

	$ci =  & get_instance();
	$ci->load->library('code');
	$id_user  =  (!empty($_COOKIE[COOKIE_ADMIN]))?$ci->code->decrypt($_COOKIE[COOKIE_ADMIN]):'';
	if(empty($id_user))
	{
		out_admin();
		redirect(base_url() . 'opera/auth/index');
		die();
	}
	else
	{

		$ci->admin_info = $ci->db->get_where("admin",array('id_user'=>$id_user))->row();

		if(empty($ci->admin_info))
		{
			out_admin();
			redirect(base_url() . 'opera/auth/index');
			die();
		}
		/*if(empty($_SESSION['name'])  or empty($_SESSION['login']))
		{
			$res = $ci->db->get_where("admin",array('id_user'=>$_SESSION['id_user']))->row();
			$_SESSION['name']= $res->name;
			$_SESSION['login'] =$res->login;
		}*/
	}

}

function out_admin()
{

		$CI = & get_instance ();
		$CI->load->helper('cookie');
		delete_cookie(COOKIE_ADMIN);
		delete_cookie("auth_hesh");
}
//{
//    "title": <string||false>,                                       # table title
//    "columns": {
//        <column_id>: {
//            "title":  <false||string>,                              # table title, if false no column title displayed,
//                                                                    # its work if all column title is false
//            "filter": <false||true||{"placeholder": <string>}>,     # column filter, placeholder: input field placeholder
//            "order":  <false||true>,                                # column order enable/disable
//            "html_tag_attr":   <false||{                            # html attr for column header
//              <attr_name>: <attr_value>
//            }>,
//            // not required, used by formatter module
//            "formatter": <formatter module specified options>       # here you can set column option for formatter
//        }
//    }
//}

?>
