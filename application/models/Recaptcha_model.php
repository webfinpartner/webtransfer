<?php

class Recaptcha_model extends CI_Model {
    
    
    static $last_error;
    
    function __construct() {
        parent::__construct();        
    }
    
    
    
        /**
     * Проверка новой ГуглКАПЧИ
     * @return boolean
     */
    static function checkRecapcha(){
        
        $recaptcha=$_POST['g-recaptcha-response'];
        if ( empty($recaptcha))
            $recaptcha=$_GET['g-recaptcha-response'];
        
        if ( empty($recaptcha))
            return FALSE;
        $google_url="https://www.google.com/recaptcha/api/siteverify";
        $secret = config_item('publickeyCapchaPrivate');

        $ip=!empty($_SERVER['HTTP_X_REAL_IP'])?$_SERVER['HTTP_X_REAL_IP']:$_SERVER['REMOTE_ADDR'];
        $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = curl_exec($curl);
        curl_close($curl);
        
        $res= json_decode($curlData, true);
        if($res['success'])
        {
            return TRUE;
        }
        else
        {
             return FALSE;
        }         
        
    }
    

    
    
}