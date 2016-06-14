<?php
 
class WTCARDApi {
 
    protected $token = null;
 
    const WTCAPI_PROTO = "http";
    const WTCAPI_BASIC_USR = "";
    const WTCAPI_BASIC_PWD = "";
    const WTCAPI_DOMAIN = "test.cardapi.net";
    const WTCAPI_PORT = "80";
 
    static $instance = null;
 
    protected $auth_token = "/auth/token";
    protected $auth_token_callback = "http://ya.ru";
 
    protected $user_cards_import = "/user/cards/import";
 
 
    /**
     * @param $username
     * @param $password
     * @return $this
     * @throws Exception
     */
    public function __construct($username, $password){
        if($username === "" || $password === "")
            throw new Exception("WTCARDApi: Username and password fields required");
 
        if(!($token = $this->auth_token($username, $password))){
            // TODO You may add logger here :)
            //throw new Exception("WTCAPI: cannot auth");
            //return null;
        }
 
        $this->set_token($token);
        static::$instance = $this;
        return $this;
    }
 
    /**
     * @param string $username
     * @param string $password
     * @return WTCApi
     */
    public static function instance($username = "", $password = ""){
        if(static::$instance)
            return static::$instance;
 
        $inst = new WTCARDApi($username, $password);
        if($username && $password){
            $token = $inst->auth_token($username, $password);
            $inst->set_token($token);
        }
        static::$instance = $inst;
        return static::$instance;
    }
 
    public function auth_token($username, $password){
        list($result, $info) = $this->__curl($this->auth_token, "GET", ["email"=>$username,"password"=>$password,"callback_url"=>$this->auth_token_callback]);
        $data = $this->__process($result);
        if($data !== null){
            if(isset($data["data"]) && isset($data["data"]["token"]))
                return $data["data"]["token"];
        }
        return null;
    }
 
    public function user_cards_import($card_proxy, $card_uid, $email, $hash){
        list($result, $info) = $this->__curl($this->user_cards_import, "POST", ["token"=>$this->token],["card_proxy"=>$card_proxy, "card_uid"=>$card_uid,"email"=>$email,"hash"=>$hash]);
        $data = $this->__process($result);
        if($data !== null){
            return $data;
        }
        return null;
    }
 
    protected function __process($result){
        if(!is_array($json = json_decode($result, true))){
            // TODO You may add logger here :)
            //throw new Exception("WTCAPI: Some error with response format");
            return null;
        }
        if(!$json["result"]){
            // TODO You may add logger here :)
            //throw new Exception("WTCAPI: Some error with response");
            return null;
        }
 
        return $json;
    }
 
    protected function __curl($url, $method, $getfields = [], $postfields = []){
        $ch = curl_init();
 
        $basic_auth = "";
        if(self::WTCAPI_BASIC_USR && self::WTCAPI_BASIC_PWD){
            $basic_auth = self::WTCAPI_BASIC_USR.":".self::WTCAPI_BASIC_PWD;
        }
        $basic_url = self::WTCAPI_PROTO . "://" . ($basic_auth ? $basic_auth . "@" : "") . self::WTCAPI_DOMAIN . ":" . self::WTCAPI_PORT . $url;
        if($getfields){
            $separator = (parse_url($basic_url, PHP_URL_QUERY) == NULL) ? '?' : '&';
            foreach($getfields as $key=>$value){
                $basic_url .= $separator . $key."=".$value;
                $separator = "&";
            }
        }
        curl_setopt($ch, CURLOPT_URL, $basic_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 
        if($postfields)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
 
        $info = curl_getinfo($ch);
        $data = curl_exec($ch);
        var_dump( [$data, $info] );
        curl_close($ch);
        return array($data,$info);
    }
 
    public function set_token($token){
        $this->token = $token;
    }
}
 
