<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message_ads_text_model extends CI_Model {

    protected 
            
            $text,
            $allowed_langs; //in sec
    
    static $last_error;

    function __construct() {
        parent::__construct();
        
        $this->allowed_langs = array('ru', 'en');
        
        $this->set_ad_text( '', 'ru') ;
        $this->set_ad_text( '', 'en') ;
//        $this->set_ad_text( ' Priglashaem na forum "$GLOBALS["WHITELABEL_NAME"]-Belyie Nochi", Sankt-Peterburg. Podrobnee - http://qps.ru/1NXlO, tel. +79052026292', 'ru') ;
//        $this->set_ad_text( ' We invite you to "$GLOBALS["WHITELABEL_NAME"]-White Nights" Forum, St. Petersburg, Russia. More info: http://qps.ru/1NXlO, t: +79052026292', 'en') ;        
    }
    
    public function set_ad_text( $text, $lang = 'ru' )
    {
        if( empty( $this->allowed_langs ) || strlen( $lang ) != 2 || !in_array( $lang, $this->allowed_langs ))
        {
            return FALSE;
        }
        if( empty( $this->text ) ) $this->text = array();
        
        $this->text[ $lang ] = $text;
        
        return TRUE;
    }
    public function get_ad_text_by_phone_with_code( $phone_with_code )
    {
        
        if( empty( $phone_with_code ) || !is_numeric( $phone_with_code ))
        {
            return FALSE;
        }
        
        
        $oneNum = substr($phone_with_code, 0, 1);
        $twoNum = substr($phone_with_code, 0, 2);
        $threeNum = substr($phone_with_code, 0, 3);
        
        $text = $this->get_ad_text_by_lang('ru');
        
        if( $twoNum == '44' || $twoNum == '99') $text = $this->get_ad_text_by_lang('en');
       
        
        return $text;
        
    }
    public function get_ad_text_by_lang( $lang = 'ru' )
    {     
        
        if( empty( $this->allowed_langs ) || strlen( $lang ) != 2 || 
            !in_array( $lang, $this->allowed_langs ) || 
            !isset( $this->text[ $lang ] )
        )
        {
            return FALSE;
        }
        
        return $this->text[ $lang ];        
    }

}

