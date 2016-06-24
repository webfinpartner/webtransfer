<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	 function sms_send($text)
	 {


		 if(!SMS_INFO) return;

		 switch ($text)
		 {
			// case "register": $info ="Na sayte ".base_url_shot()." zaregistrirovan noviy polzovatel"; break;
			// case "partner": $info= "Na sayte ".base_url_shot()." zaregistrirovan noviy partner";  break;
			 case "credit" : $info = "Na sayte ".base_url_shot()." est noviy zapros na kredit";  break;
			 case "invest" : $info = "Na sayte ".base_url_shot()." est novaya zayavka na vklad";  break;
			 case "feedback" : $info = "Na sayte ".base_url_shot()." est zapros na obratnuyu svyaz";  break;
		 }
		 $ci = & get_instance();
		 $phones = $ci->base_model->settings('sms');
		 $phones = explode(',', $phones);
		 if(empty($phones)) return;
		 foreach($phones as $item )
		 {
			 sms_sender(trim($item), $info);
		 }

	 }

	 function sms_sender($phone, $text)
	 {
		$user = "webtransfer";
		$password = "";
		$api_id = "";
		$baseurl ="http://api.clickatell.com";

		$text = urlencode($text);
		$to = $phone;

		// auth call
		$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

		// do auth call
    $ret = file($url);

    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") {

        $sess_id = trim($sess[1]); // remove any whitespace
        $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";

        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);

        if ($send[0] == "ID") {
            //echo "successnmessage ID: ". $send[1];
        } else {
           // echo "send message failed";
        }
    } else {
       // echo "Authentication failure: ". $ret[0];
    }
	 }