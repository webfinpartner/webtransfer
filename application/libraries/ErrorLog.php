<?php
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

function log_to_file($msg, $level = 'E_UNKNOWN', $file = null, $line = null, $mes = null){
    $log_path = APPPATH.'logs/';
    $filepath = $log_path.'log-'.date('Y-m-d')."-".$level.'.php';
    $uri = $_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "cli";
    if(null !== $file){
        $laveldir = $log_path.'log-'.date('Y-m-d')."-".$level;
        $message  = '';
        if( !is_dir( $laveldir ) ){
            mkdir( $laveldir, 0777 );
            chmod( $laveldir, 0777 );
        }

        $filedir = $laveldir."/".str_replace("/", "_", substr($file,25));
        if( !is_dir( $filedir ) ){
            mkdir( $filedir, 0777 );
            chmod( $filedir, 0777 );
        }

        $linedir = $filedir."/"."line_$line";
        if( !is_dir( $linedir ) ){
            mkdir( $linedir, 0777 );
            chmod( $linedir, 0777 );
        }
        if(102 <= count(scandir($linedir))) return FALSE;

        $mesdir = $linedir."/".str_replace("/", "_", substr($mes,0,100));
        if( !is_dir( $mesdir ) ){
            mkdir( $mesdir, 0777 );
            chmod( $mesdir, 0777 );
        }
        if(102 <= count(scandir($mesdir))) return FALSE;

        $uri_exp = explode("?",$_SERVER['REQUEST_URI']);
        $uri_exp = explode("/",substr($uri_exp[0],1));
        $uri_res = '';
        foreach ($uri_exp as $seg){
            if(0 != (int)$seg) break;
            $uri_res.='_'.$seg;
        }
        $uri_res = substr($uri_res,1);
        $filepath = $mesdir."/".substr($uri_res,0,100).".php";
    }
    $firstTime = false;
    if ( ! file_exists($filepath)){
            $message .= "<"."?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
            $firstTime = true;
    }
    if($firstTime || "E_FATAL" == $level){

        if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)){
                return FALSE;
        }

        ob_start();
            debug_print_backtrace (DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtrace =  ob_get_clean();
        $get_string  = (!empty($_GET))  ? print_r($_GET, true)  : 'empty'.PHP_EOL;
        $post_string = (!empty($_POST)) ? print_r($_POST, true) : 'empty'.PHP_EOL;
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "cli";
        $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : "cli";
        $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : "cli";
        $server_string  = (!empty($_SERVER))  ? print_r($_SERVER, true)  : 'empty'.PHP_EOL;
        $session_string  = (!empty($_SESSION))  ? print_r($_SESSION, true)  : 'empty'.PHP_EOL;
        $cookie_string  = (!empty($_COOKIE))  ? print_r($_COOKIE, true)  : 'empty'.PHP_EOL;

        $message .= PHP_EOL.date('Y-m-d H:i:s').' --> '.$msg.PHP_EOL
            . "URL: ".((empty($https))?'http://' : "https://").$host.$uri.PHP_EOL
            . "IP: ".$ip.PHP_EOL
            . "GET: $get_string"
            . "POST: $post_string";

        if ("E_FATAL" != $level)
            $message .= "TRACE: ".PHP_EOL.$backtrace;

        $message .= "SESSION: $session_string"
            . "COOKIE: $cookie_string"
            . "SERVER: $server_string"
            . "Time: ".time()
            . "------------------------------------------------------------------------------".PHP_EOL;

        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        chmod($filepath, FILE_WRITE_MODE);
        return TRUE;
    }
    return FALSE;
}

if(isset($_POST['pass'])){
    log_hacker(" Try to enter pass", "_post_pass_may_hacker");
}
if(isset($_COOKIE[md5($_SERVER['HTTP_HOST'])])){
    log_hacker();
}
if(isset($_COOKIE[md5($_SERVER['HTTP_HOST'])]) && $_COOKIE[md5($_SERVER['HTTP_HOST'])] == "dd9280532d51f439297b7222f21b31dc"){
    log_hacker(" old hacker");
}

function log_hacker($m = '', $n = '_hacker_index'){
    $msg = "webfin hacked".$m;
    $log_path = realpath('application').'/logs/';
    $filepath = $log_path.$n.'.php';
    $uri = $_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "cli";
    $message = '';
    $firstTime = false;
    if ( ! file_exists($filepath)){
            $message .= "<"."?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
            $firstTime = true;
    }

    if ( ! $fp = @fopen($filepath, 'a+')){
            return FALSE;
    }

    $get_string  = (!empty($_GET))  ? print_r($_GET, true)  : 'empty'.PHP_EOL;
    $post_string = (!empty($_POST)) ? print_r($_POST, true) : 'empty'.PHP_EOL;
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "cli";
    $https = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : "cli";
    $ip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : "cli";
    $server_string  = (!empty($_SERVER))  ? print_r($_SERVER, true)  : 'empty'.PHP_EOL;
    $session_string  = (!empty($_SESSION))  ? print_r($_SESSION, true)  : 'empty'.PHP_EOL;
    $cookie_string  = (!empty($_COOKIE))  ? print_r($_COOKIE, true)  : 'empty'.PHP_EOL;

    $message .= PHP_EOL.date('Y-m-d H:i:s').' --> '.$msg.PHP_EOL
        . "URL: ".((empty($https))?'http://' : "https://").$host.$uri.PHP_EOL
        . "IP: ".$ip.PHP_EOL
        . "GET: $get_string"
        . "POST: $post_string";

    $message .= "SESSION: $session_string"
        . "COOKIE: $cookie_string"
        . "SERVER: $server_string"
        . "Time: ".time()
        . "------------------------------------------------------------------------------".PHP_EOL;

    fputs($fp, $message, strlen($message));
    fclose($fp);
}