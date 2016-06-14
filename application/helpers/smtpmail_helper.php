<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');



function   my_mail($title, $text, $mails, $from="no-reply@webtransfer.com")
{

	mb_internal_encoding('UTF-8');
	$title =  mb_encode_mimeheader($title);

	$mails=  (array)$mails;
	$CI =  & get_instance();

	$CI->load->library('PHPMailer');
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	if(!empty($CI->mail->attachment))
	{
		foreach($CI->mail->attachment as $attach)
		{
			$mail->AddAttachment($attach);
		}

		unset($CI->mail->attachment);
	}
	$mail->SMTPDebug =0; // debugging: 1 = errors and messages, 2 = messages only
	$mail->Debugoutput = 'html';
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "webfin.webtransfer-finance.com";
	$mail->Port = 25; // or 587
	$mail->IsHTML(true);
	$mail->Username = "support@webtransfer.com";
	$mail->Password = "kolobok";

	$mail->SetFrom($from,$GLOBALS["WHITELABEL_NAME"]);
	$mail->Subject = $title;
	$mail->MsgHTML($text);
	$mail->AddReplyTo($from);

	foreach($mails as $item)
	{
		$mail->AddAddress($item);
	}
    return $mail->Send();
}


function   my_mail_ex($server, $title, $text, $mails)
{

    if ( empty($server) || !is_object($server) ) 
         return array( 'status' => false, 'error' => 'Bad server object' );
         
    if ( !property_exists($server, 'host') || !property_exists($server, 'port') || !property_exists($server, 'username') || !property_exists($server, 'password') ) 
         return array( 'status' => false, 'error' => 'Bad server params' );
         

    mb_internal_encoding('UTF-8');
    $title =  mb_encode_mimeheader($title);

    $mails=  (array)$mails;
    $CI =  & get_instance();

    $CI->load->library('PHPMailer');
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    if(!empty($CI->mail->attachment))
    {
        foreach($CI->mail->attachment as $attach)
        {
            $mail->AddAttachment($attach);
        }

        unset($CI->mail->attachment);
    }
    $mail->SMTPDebug =0; // debugging: 1 = errors and messages, 2 = messages only
    $mail->Debugoutput = 'html';
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for GMail
    $mail->Host = $server->host;
    $mail->Port = $server->port; // or 587
    $mail->IsHTML(true);
    $mail->Username = $server->username;
    $mail->Password = $server->password;
    
    $from = $server->username;
    if ( !empty($server->from) )
        $from  =  $server->from;
    

    $mail->SetFrom($from,$GLOBALS["WHITELABEL_NAME"]);
    $mail->Subject = $title;
    $mail->MsgHTML($text);
    $mail->AddReplyTo($from);


    foreach($mails as $item)
    {
        $mail->AddAddress($item);
    }
    if (  $mail->Send() )
        return  array( 'status' => true, 'send_time' => $mail->send_time );
    
    return array( 'status' => false, 'error' => $mail->ErrorInfo );
}
?>