<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class WTCApi {

    private $url = '';

    private $card_user          = "/users/{userid}";
    private $card_proxy         = "/cards/{proxy}";
    private $card_actions       = "";
    private $a_details          = "/carddetails";   //GET
    private $a_balance          = "/balance";       //GET
    private $a_status           = "/status";        //GET
    private $a_cardholderinfo   = "/cardholderinfo";//GET
    private $a_transactions     = "/transactions";  //POST
    private $a_activate         = "/activate";      //POST
    private $a_activate_pin     = "/activateAndSetPIN";//POST
    private $a_replace          = "/replace";       //POST
    private $a_load             = "/load";          //POST
    private $pr_unload          = "/transfers";     //POST
    private $cu_passwrd         = "/createPassword";//POST
    private $a_pin              = "/pin";           //POST
    private $cu_kyc              = "/kyc";           //POST



    private $token = '';
    private $resp = null;

    private $default_headers = array();

    public function __construct($card_user_id, $card_proxy) {
        $this->card_user = str_replace("{userid}", $card_user_id, $this->card_user);
        $this->card_proxy = str_replace("{proxy}", $card_proxy, $this->card_proxy);
        $this->card_actions = $this->card_user.$this->card_proxy;
        $this->url = config_item("wt_card_url");
        $this->log = 3;//config_item("WTCApi_log");
    }

    public function auth(){

                return false;


    }

    /* TODO one point to answer
    private function answer($errors = array(), $data = array()){
        return json_encode();
    }
    */


    public function card($data){
       

        return FALSE;
    }



    public function txnaccounts(){
        return FALSE;

    }


    public function transactionAccountsTransfers($transactionaccountid, $filter){
        return FALSE;
    }

    public function updateInfo($data) {
        return FALSE;
	}

    public function purchase($data) {
        return FALSE;
    }

    public function balance(){
        return null;
    }

    public function transactions($count = 1750, $offset = 0, $filter = []){
        return null;
    }
    
    public function transactions_count($filter){
        return 0;
    }
    

    public function cardDetails() {
        return null;
    }

    public function load($payment){
        return false;
    }

    public function unload($payment){
        return null;
    }


    public function addBankAndUnload($data){
          return null;
    }

    public function activate_and_set_pin($pan){
        return null;
    }

    public function activate(){
        return null;
    }


    public function status($status, $comment){
        return null;
    }

    public function setPasswrd($pass){
        return null;
    }

    public function sendDocs($docId, $docAddr){

        return null;
    }

    public function getPin(){
        return null;
    }

    public function getResp() {
        return $this->resp;

    }


    public function getError() {
        if(isset($this->resp['errorDetails']) && '0' != $this->resp['errorDetails'][0]['errorCode'])
            return $this->resp['errorDetails'][0]['errorDescription'];
        else if(isset($this->resp['errorDetails']) && '0' == $this->resp['errorDetails'][0]['errorCode'])
            return "OK";
        else return "Error encountered";
    }


    public function getCountries($payMode, $operationName = NULL) {
        return null;
    }

    public function getBanks($countryCode) {
        return null;
    }

    public function getServices($countryCode) {
        return null;
    }


    public function getBankAccountTemplate($countryCode, $currencyCode = 'USD'){
        return null;

    }

    public function getCustomerUniqueRef($proxy){
        return null;
    }
    
    public function  cardholderinfo(){
        return null;
        
        
    }



    public function getServiceUrl($proxy, $summ, $service_name, $country_code){
        return ['error'=>$this->resp['errorDetails'][0]['errorDescription']];
    }


    private function process_error($data_raw, $info){
            return false;
    }

    private function curl($service, $headers = array(), $method = "GET", $postfields = ''){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $service);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if($postfields)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        if(!empty($headers))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $info = curl_getinfo($ch);
        $data = curl_exec($ch);
        //var_dump([$service,$method, $postfields, $info, $data ]);
        curl_close($ch);
        if(1 < $this->log) ci()->base_model->log2file("curl resp: ".print_r($data,true).' info='.print_r($info,true).PHP_EOL, "WTCApi");
        return array($data,$info);
    }


}