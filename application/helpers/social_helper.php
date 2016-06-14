<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function vkPost($vkontakteUserId, $vkontakteAccessToken, $text, $link=null) {
    $sRequest = "https://api.vkontakte.ru/method/wall.post?owner_id=".$vkontakteUserId."&access_token=".$vkontakteAccessToken."&message=".$text;

    $oResponce = file_get_contents($sRequest);
//    var_dump($sRequest);
//    var_dump("otvet", $oResponce, "sss ");
//
//    require (APPPATH.'libraries/VK/VK.php');
//    require_once(APPPATH.'libraries/VK/VKException.php');
//
//    $vk = new VK\VK($client_id, $client_secret);


}