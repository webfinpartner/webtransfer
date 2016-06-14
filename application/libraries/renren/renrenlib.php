<?php

class Renren_lib
{

    const CALLBACK_URL = 'https://webtransfer-finance.com';

    static public $config;

    static function setConfig($data)
    {
        self::$config = $data;
    }

    static function getAuthLink()
    {
        $params = [
            'client_id' => self::$config['APP_ID'],
            'redirect_uri' => site_url('login/renren'),
            'response_type' => 'code',
        ];

        $url = 'https://graph.renren.com/oauth/authorize?' . http_build_query($params);
        return $url;
    }

    public function getCallbackCode()
    {
        $code = @$_GET['code'];
        return $code;
    }

    public function auth($callbackCode)
    {
        $params = [
            'grant_type'    => 'authorization_code',
            'client_id'     => self::$config['API_KEY'],
            'redirect_uri'  => site_url('login/renren'),
            'client_secret' => self::$config['SECRET_KEY'],
            'code'          => $callbackCode,
        ];

        //REAL AUTH
        $url = 'https://graph.renren.com/oauth/token?' . http_build_query($params);
        //FAKE AUTH
//        $url = 'http://wtest13.cannedstyle.com/renren_fake_auth.json';
        $response = $this->curl_send($url);
        if(isset($response['user']))
            return $response['user'];
        return;
    }

    public function getError()
    {
        $error = @$_GET['error'];
        return $error;
    }

    public function curl_send($link)
    {
        $curlInit = curl_init($link);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 1000); //Tooo loooongg respooooooonse timeeee...
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($curlInit, CURLOPT_AUTOREFERER, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlInit, CURLOPT_VERBOSE, 1);
        curl_setopt($curlInit,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($curlInit, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($curlInit);

        curl_close($curlInit);
        return json_decode($response, true);
    }
}