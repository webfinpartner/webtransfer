<?php defined('BASEPATH') OR exit('No direct script access allowed');
$whitelabel = require_once dirname(__FILE__).'/../config/whitelabel.php';

if(!isset($_SERVER['HTTP_HOST']) && (PHP_SAPI === 'cli' OR defined('STDIN'))) //
    $_SERVER['HTTP_HOST'] = 'cli';
else if(!isset($_SERVER['HTTP_HOST']))
    die('HTTP_HOST is not defined'.PHP_EOL);

if(!isset($whitelabel[$_SERVER['HTTP_HOST']])){
    header('HTTP/1.0 404 Not Found');
    include dirname(__FILE__).'/../views/errors/html/error_domen.php';
    die;
}
$whitelabel_host_ids= [];
$whitelabel_ids= [];
foreach ($whitelabel as $k => $v) {
    $whitelabel_host_ids[$v['id']] = ['host' => $k, 'name' => $v['name']];
    $whitelabel_ids[$v['id']] = $v['name'];
}

$GLOBALS['WHITELABEL'] = $whitelabel;
$GLOBALS['WHITELABEL_IDS'] = $whitelabel_ids;
$GLOBALS['WHITELABEL_HOST_IDS'] = $whitelabel_host_ids;
$GLOBALS["WHITELABEL_ID"] = $whitelabel[$_SERVER['HTTP_HOST']]['id'];
$GLOBALS["WHITELABEL_NAME"] = $whitelabel[$_SERVER['HTTP_HOST']]['name'];
$GLOBALS["WHITELABEL_LOGO"] = ''; 