<?php

// Path to the system folder
define('BASEPATH', $_SERVER['DOCUMENT_ROOT'].'/');

$application_folder = 'application';

if (is_dir($application_folder)) define('APPPATH', $application_folder.'/');
else {
        if ( ! is_dir(BASEPATH.$application_folder.'/'))
                exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);

        define('APPPATH', BASEPATH.$application_folder.'/');
}

function FriendlyErrorType($type)
{
    switch($type)
    {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_CORE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_CORE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
    }
    return "E_UNKNOWN";
}

function log_to_file($level = 'E_UNKNOWN', $msg, $php_error = FALSE){
	$log_path = ($config['log_path'] != '') ? $config['log_path'] : APPPATH.'logs/';
	$filepath = $log_path.'log-'.date('Y-m-d')."-".$level.'.php';
	$message  = '';

	if ( ! file_exists($filepath)){
		$message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
	}
	if ( ! $fp = fopen($filepath, "ab")){
		return FALSE;
	}

        ob_start();
            debug_print_backtrace (DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtrace =  ob_get_clean();
        $get_string = (!empty($_GET)) ? print_r($_GET, true) : 'empty'.PHP_EOL;
		$post_string = (!empty($_POST)) ? print_r($_POST, true) : 'empty'.PHP_EOL;

	if ("E_NOTICE" == $level OR "E_STRICT" == $level)
	    $message .= PHP_EOL.date('Y-m-d H:i:s').' '.$msg." URL: ".((empty($_SERVER['HTTPS']))?'http://' : "https://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	elseif ("E_FATAL" == $level)
		$message .= PHP_EOL.date('Y-m-d H:i:s').' --> '.$msg.PHP_EOL
                 . "URL: ".((empty($_SERVER['HTTPS']))?'http://' : "https://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].PHP_EOL
				 . "IP: ".$_SERVER['REMOTE_ADDR'].PHP_EOL
				 . "GET: $get_string"
				 . "POST: $post_string"
				 . "------------------------------------------------------------------------------".PHP_EOL;
	else
	    $message .= PHP_EOL.date('Y-m-d H:i:s').' --> '.$msg.PHP_EOL
                 . "URL: ".((empty($_SERVER['HTTPS']))?'http://' : "https://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].PHP_EOL
				 . "IP: ".$_SERVER['REMOTE_ADDR'].PHP_EOL
				 . "GET: $get_string"
				 . "POST: $post_string"
				 . "TRACE: ".PHP_EOL.$backtrace
				 . "------------------------------------------------------------------------------".PHP_EOL;

	flock($fp, LOCK_EX);
	fwrite($fp, $message);
	flock($fp, LOCK_UN);
	fclose($fp);

	@chmod($filepath, FILE_WRITE_MODE);
	return TRUE;
}

function error_handler($errno, $errstr, $errfile, $errline){
	$errno = FriendlyErrorType($errno);
	//if ("E_NOTICE" == $errno) return true;
    log_to_file($errno, "MASSAGE: $errstr; FILE: $errfile:$errline");
    return true;
}
set_error_handler ("error_handler");

function exception_handler($exception) {
    set_status_header(500);
    log_to_file('E_FATAL', "Massage: ".$exception);
    echo date("Y-m-d H:i:s")." ERROR - ".$exception->getCode()."<br/> Извините возникла ошибка на сервере, сообщите в тех. поддержку Webtransafer пожалуйста.";
    return true;
}

set_exception_handler('exception_handler');