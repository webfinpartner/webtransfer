<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getValPartnersType($val){
    return ('t' == $val) ? true : false;
}

function renderTablePartnerUser($item, $count = 0,  $sub = false){
    $class = "";
    $right = "";
    if($sub){
        $class = "sub_user sub_user_".$item->parent;
        $right = "sub_right";
    }
    if( empty($item->foto) )
    foreach ($item->social as $social) {
        if (!empty($social->foto)) {
            $item->foto = "/upload/imager.php?src={$social->foto}&w=60";
            break;
        }
    } ?>
            <tr class="user_profile <?=$class?> row_id_<?= $item->id_user ?>" style="font-size:12px;">
                <?if ($sub){?>
               <td></td>
                <?}?>
                <td class="user_photo <?=$right?>">
                    <img src="<?= (empty($item->foto)) ? "/images/no-photo.jpg" : $item->foto ?>" width="35px"/>
                </td>
                <td class="user_name">
                    <? if( Base_model::USER_STATE_OFF == $item->state){ ?>
                    <img src="/images/icons/151.png" alt="<?=_e('заблокирован')?>">
                    <?}else{?>
                    <? if( !$item->status ){ ?>
                    <img src="/images/icons/160.png" alt="<?=_e('неверифицирован')?>">
                    <? }else{?>
                    <img src="/images/icons/152.png" alt="<?=_e('верифицирован')?>">
                    <? } }?> <span rel="<?= $item->id_user ?>" class="load_user"><?= $item->name ?> <?= $item->sername ?></span> <br/>
                    <span style="font-size:10px;display:block;margin-top:-7px;color:#8a8a8a;">№ <?= $item->id_user ?> | <?= date_formate_my($item->reg_date, 'd/m/Y') ?></span>
                </td>
                <?if (!$sub){?>
                <td class="user_email"><?= $item->email ?></td>
                <td class="user_phone"><?= $item->phone ?></td>
                <?}?>
                <?if ($sub){?>
                <td class="user_soc" colspan="3">
                <? } else {?>
                 <td class="user_soc">
                <? } ?>
                    <? if( Base_model::USER_STATE_OFF != $item->state){ ?>
                    <a href="#" onclick="openDialog('<?=$item->id_user?>');return false;">
                        <img width="16" src="/images/icons/chat_icon.png">
                    </a>
                    <?}?>
		<? foreach ($item->social as $social) {
                    if (empty($social->url)) continue; echo '<a  href="' . $social->url . '" onclick="return !window.open(this.href)" style="margin:2px"><img style="width:16px;" src="/images/icons/' . socialList($social->name) . '"></a>';
                } ?>
                <? if (!empty($item->skype)) { ?>
                        <a class="user_social" href="skype:<?= $item->skype ?>?chat">
                            <img width="16" src="/images/icons/sp.png">
                        </a>
                <? } ?>

                <? $social_id = get_social_id($item->id_user);
                    if ( !empty($social_id) ){ ?>
                        <a href="https://webtransfer.com/social/profile/<?=$social_id?>?lang=<?=_e('lang')?>">
                            <img width="16" src="/images/icons/wt_social.png">
                        </a>
                    <? } ?>

                </td>

                <td class="user_status">

                   <?= (($count) ? "<span class='sub_open but agree' data-id='$item->id_user'><b>$count</b>"._e('new_276')."</span>": "");?>
                </td>
<!--                <td class="action">
                    <?= ((!$sub) ? "<span class='del_user' data-id='$item->id_user' data-name='$item->name $item->sername'><img src='/img/del.png'></span>": "");?>
                </td>-->
            </tr>
<?php }

function url_parents($id_parent)//обдумать
{
	$data=array();
	$CI = & get_instance ();

	do
	{
		$page=$CI->db->select("url, title,id_parent")->from('pages')->where(array('active'=>1,'id_page'=>$id_parent))->get()->row();
		$id_parent=(isset($page->id_parent))?$page->id_parent:'';
		if($id_parent!="")$data[]=$page;
	}
	while(!empty($id_parent));


	return $data;
}
function breadcrumbs($parents)
{
	 $out='';

          krsort( $parents);
            foreach( $parents as $parent){
              $out.=  '<li><a  href="'.create_link($parent->url).'">'.$parent->title.'/</a></li>';
         }
		 return $out;
}



function breadcrumbs_url($parents)
{
	 $out = site_url('page') . '/';
          krsort( $parents);
            foreach( $parents as $parent){
              $out.=  $parent->url.'/';
         }
	 return $out;
}

function shablon($page){

    $CI = & get_instance ();

    if($page->shablon=="novosti"){
             $CI->base_model->shablon_novosti();
    } elseif($page->shablon=="faq"){
            $data['faqs']=$CI->base_model->get_faqs();
            $CI->load->view('user/faq',$data);

    } elseif(!empty($page->shablon)){
        $shablon=$CI->db->select("sh_content")->from('shablon')->where('id_shablon',$page->shablon)->get()->row('sh_content');
        $arr = array();
        preg_match_all('/<\?=\$([a-z0-9_]+)->([a-z0-9_]+)\?>/', $shablon, $arr, PREG_SET_ORDER);

        foreach($arr as $var){
                $str[]="/<\?=\\$".$var[1]."-\>".$var[2]."\?>/";
                $values[]=@$GLOBALS["{$var[1]}"]->{$var[2]};
        }
        if(!empty($values))$shablon= preg_replace($str, $values,$shablon );
        $content= preg_replace('#\(\*\*\*\*\*Метка шаблона\*\*\*\*\*\)#',$shablon, $page->content,1);

        if(preg_match('#<\?=get_vklad\(\)\?>#', $content))
                        $content= preg_replace('#<\?=get_vklad\(\)\?>#',get_vklad(), $content,1);
        if(preg_match('#<\?=get_ip\(\)\?>#', $content))
                        $content= preg_replace('#<\?=get_ip\(\)\?>#',get_ip(), $content,1);
        if(preg_match('#<\?=get_region\(\)\?>#', $content))
                        $content= preg_replace('#<\?=get_region\(\)\?>#',get_region(true), $content,1);
        if(preg_match('#<\?=get_bisness\(\)\?>#', $content))
                        $content= preg_replace('#<\?=get_bisness\(\)\?>#',get_bisness('s'), $content,1);
        if(preg_match('#<\?=get_menu_vklad\(\)\?>#', $content))
                        $content= preg_replace('#<\?=get_menu_vklad\(\)\?>#',get_menu_vklad(), $content,1);
        if(preg_match('#<\?=view_social\(\)\?>#', $content))
                        $content= preg_replace('#<\?=view_social\(\)\?>#',get_social(), $content,1);
        if(preg_match('#\{table_count\}#', $content))
                        $content= preg_replace('#\{table_count\}#',get_table_count(), $content,1);

        return $content;
    } else {
        $content=$page->content;
        if(preg_match('#\{get_bisness\(\)\}#', $content))
                        $content= preg_replace('#\{get_bisness\(\)\}#',get_bisness(true), $content,1);
        if(preg_match('#\{view_credits\(\)\}#', $content))
                        $content= preg_replace('#\{view_credits\(\)\}#',view_credits(), $content,1);

        return 	$content;
    }
}

function  get_social()
{

    return get_instance()->load->view('user/blocks/social', array(), true);
}

function  get_ip()
{
	return get_instance()->input->ip_address();
}

function get_registr($id=4)//обдумать
{

	$CI = & get_instance ();
	return create_link($CI->db->select('url')->where('shablon',$id)->from('pages')->get()->row('url'));

}


function redirectInvest()
{
	if(!empty($_COOKIE['social-vklad']))
	{
		get_instance()->input->set_cookie('social-vklad');
		redirect(site_url('account/my_invest'));
	}
}


function cookie_log($login,$pass,$remember=1, $user_id = NULL)
{
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
               'name'   => 'login',
               'value'  => urlencode($login),
               'expire' => $time,
               'domain' => '',
               'path'   => '/',
               'prefix' => '',
               'secure' => config_item('origin_cookie'), // для того чтоб тест работал, задается в config.php
               'httponly' => TRUE
           );

    $CI ->input->set_cookie($cookie);
    $cookie['name']="password";
    $cookie['value']=md5($pass);
    $CI ->input->set_cookie($cookie);

    cookie_chat_token($user_id);

}



function cookie_chat_token($user_id){
    $CI = & get_instance ();
    $time =60*60*24*180;
    $cookie = array(
               'name'   => 'chat_token',
               'expire' => $time,
               'domain' => '',
               'path'   => '/',
               'prefix' => '',
               'secure' => config_item('origin_cookie'), // для того чтоб тест работал, задается в config.php
               'httponly' => FALSE
           );

    if ( !empty($user_id) ){
        $CI->load->model('Chat_auth_tokens_model');
        $token = $CI->Chat_auth_tokens_model->set($user_id);
        $ip=!empty($_SERVER['HTTP_X_REAL_IP'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'];
        dev_log("set chat_token to $user_id: $token IP: $ip uri: {$_SERVER['REQUEST_URI']} cookie=".print_r($_COOKIE, true), 'chat_token');
        if ( !empty($token)){
            //$cookie['value']  = urlencode($token);
            $cookie['value']  = $token;
            $CI->input->set_cookie($cookie);
            
            cookie_log_me_http_secure('user_id', $user_id, 1, FALSE);
            cookie_log_me_http_secure('real_user_id', $user_id, 1, FALSE);
            
            
           
        }

    }
}



function cookie_log_me($key,$value,$remember=1)
{
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
        'name'   => $key,
        'value'  => $value,
        'expire' => $time,
        'domain' => '',
        'path'   => '/',
        'secure' => config_item('origin_cookie'), // для того чтоб тест работал, задается в config.php
        'prefix' => '',
        'httponly' => TRUE
    );
    $CI ->input->set_cookie($cookie);
}

function cookie_log_me_http_secure($key,$value,$remember=1, $http_only = TRUE){
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
        'name'   => $key,
        'value'  => $value,
        'expire' => $time,
        'domain' => '',
        'path'   => '/',
        'prefix' => '',
        'secure' => config_item('origin_cookie'), // для того чтоб тест работал, задается в config.php
        'httponly' => $http_only
    );
    $CI ->input->set_cookie($cookie);
}



function cookie_token($token,$remember=1){
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
               'name'   => 'wt_token',
               'value'  => $token,
               'expire' => $time,
               'domain' => '',
               'path'   => '/',
               'prefix' => '',
           );

    $CI ->input->set_cookie($cookie);
}
function cookie($name,$voted,$remember=1){
    $CI = & get_instance ();
    $time =60*60*24*30;
    $time = ($remember==1)?$time:  0 ;
    $cookie = array(
               'name'   => $name,
               'value'  => $voted,
               'expire' => $time,
               'domain' => '',
               'path'   => '/',
               'prefix' => '',
           );

    $CI ->input->set_cookie($cookie);
}
function empty_cookie()
{
		$CI = & get_instance ();
		$CI->input->set_cookie('login','','');
		$CI->input->set_cookie('password','','');
		$CI->input->set_cookie('wt_token','','');
		$CI->input->set_cookie('user_id','','');
                $CI->input->set_cookie('chat_token','','');
                $CI->input->set_cookie('social_id','','');
}




function get_menu_vklad($option=false)
{
	$CI = & get_instance ();
	$data=$CI->db->order_by('position')->get('contribution')->result();
	$out='';
	if(!$option){
		foreach($data as $var)
		{
			$out.="<option value='".$var->id."'>".$var->title."</option>\n ";
		}
		return $out;
	}
	else
	{
		$out=array();
		foreach($data as $var)
		{
			$out[$var->id]=$var->title;
		}
		return form_dropdown('vklad', $out,$option );
	}
}

function  get_vklad()
{
	$CI = & get_instance ();
	$data=$CI->db->order_by('position')->get('contribution')->result();
	$out='';
	foreach($data as $var)
	{
		$out.="case '".$var->id."':  month=".$var->month."; percent=".$var->percent."; nal=".$var->charge."; bonus=".$var->bonus."; break;\n ";
	}
	return $out;
}


function message_check(&$message)
{
	if(empty($message) and !empty($_SESSION['message']))
	{
		$message=$_SESSION['message'];
	}
	unset($_SESSION['message']);
}

function set_accaunt_message($message,$class='good'){

    $_SESSION['message']=array('class'=>$class, 'send'=>$message);

}

function accaunt_message(&$data,$message,$class='good'){
    $data->message=array('class'=>$class, 'send'=>$message);
//    $ci = &get_instance ();
//    if($ci->base_model->returnNotAjax())
        $_SESSION['message']=array('class'=>$class, 'send'=>$message);
}

//function  get_table_count()
//{
//	$file_handle = fopen('./upload/buyandselltable1.csv', "r");
//		$out=array();
//		$return  =  '
//
//
//<script>
//$(function(){
//
//
//
//
//
//$(".count_table").click(function(){
//
//$(".count_table").css("backgroundColor","inherit")
//$(this).css("backgroundColor","#FF7735")
//
//
//id = $(this).attr("id");
//if($(".row_count_table[name=\'"+id+"\']").eq(0).css("display")=="none")
//{
//$(".row_count_table").hide();
//$(".row_count_table[name=\'"+id+"\']").show()
//
//}
//else{$(".row_count_table").hide();$(".count_table").css("backgroundColor","inherit")}
//
//
//
//}).css("cursor","pointer")
//
//
//
//
//
//$(".row_count_table").hide();
//
//$(".currentday a").click(function(){
//
//$(".active_today").each(function(){
//$(".count_table[id=\'"+$(this).attr("name")+"\'] td:first-child").trigger("click")
//
//}).css("backgroundColor","#20cc0a")
//}).trigger("click")
//window.location.hash= "today"
//})
//</script>
//
//<table cellspacing="0">
//<tbody>
//<tr style="background-color: #229cde; color: #ffffff; font-weight: bold;">
//<td class="first_col" style="width: 220px; text-align: center;">Дата</td>
//<td style="text-align: center; width: 220px;">Рост - 0.065% ежедневно</td>
//<td style="text-align: center; width: 220px;">Стоимость </td>
//</tr>
//</tbody>
//</table>
//<div  class="table_wrap">
//<div  class="table_body">
//<table cellspacing="0">
//<tbody>
//';
//					while (!feof($file_handle)) {
//
//						$line = fgets($file_handle);
//						$line = explode(";", $line);
//						if(empty($line[1])) break;
//						$line[1]= preg_replace('/"/sui', '', $line[1]);
//						$line[2]= preg_replace('/"/sui', '', $line[2]);
//						$line[3]= preg_replace('/"/sui', '', $line[3]);
//
//
//
//                                                $matches = array();
//						preg_match('/[0-9]* ([а-я]* [0-9]* г\.)/sui',$line[1],$matches);
//						$index =  $matches[1];
//						$out[$index][] = $line;
//
//					}
//
//					foreach ($out  as  $index=>$item)
//					{
//						$return.= '<tr id="'.$index.'" class="count_table"><td style="text-align:left;width:205px;">'.$index.'</td><td style="width:205px;">'.$item[0][3].' %</td><td width:205px;>$ '.$item[0][4].'</td></tr>';
//						foreach  ($item  as $elem)
//						{
//
//						$today = date("d m Y г.");
//						$elem[1]=mounth_to_date($elem[1]);
//
//$return.= '<tr name="'.$index.'"   id="'.(($today==$elem[1])?"today":"").'" class="row_count_table '.(($today==$elem[1])?"active_today":"").'">
//<td class="col1">'.$elem[1].'</td>
//<td class="col2">'.$elem[3].' %</td>
//<td class="col3">$ '.$elem[4].'</td>
//</tr>';
//						}
//					}
//					return  $return.'</tbody>
//</table></div></div>
//<div class="currentday"><a href="' . site_url('page/vlojit-dengi/kurs') . '#today">Текущий курс</a></div>
//';
//
//}




function mounth_to_date($m)
{
$m = mb_strtolower($m);

	$data = array(
		 'январь'=>"01",
		 'февраль'=>"02",
		 'март'=>"03",
		 'апрель'=>"04",
		 'май'=>"05",
		 'июнь'=>"06",
		 'июль'=>"07",
		 'август'=>"08",
		 'сентябрь'=>"09",
		 'октябрь'=>"10",
		 'ноябрь'=>"11",
		 'декабрь'=>"12",
		);
		return strtr($m, $data);


}


function getcUrlPhoto($url){
	$ch                     = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$image = curl_exec($ch);

	if(empty($image)) return false;
	return $image;
}
