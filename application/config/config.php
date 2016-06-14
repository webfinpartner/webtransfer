<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('EXWT', 46504049);

$config['wt_card_url'] = '';
$config['WTCApi_log'] = 1;
$config['wt_card_user'] = '';
$config['wt_card_pass'] = '';
$config['wt_card_agentId'] = 164288;
$config['wt_perc_card_id'] = 21;
$config['wt_txnAccountId'] = 10034798;


$config['usd1_to_ccreds_perc'] = 50;
$config['usd2_to_ccreds_perc'] = 100;

$config['card_payin_comission'] = 4;
$config['bonus_for_card_invest'] = 20;

//0 - без лимита
$config['loadfunds_limit']= [
   312 => 1000,  
   318 => 1000,
   319 => 1000
];

$config['Wtcard_api_login'] = "import@webtransfercard.com";
$config['Wtcard_api_pass'] = "";


$config['webtransfercard'] = "https://webtransfercard.com/";


$config['exchange_users'] = [EXWT, 25742299, 22498968 ];

$config["db_lang"] = 'ru';

$config["bonus_on_incame_pay"] = true;

$config["bonus_first_credit"] = true;
$config["bonus_first_credit_start"] = '2015-05-11';

$config["VisualDNA_stangart"] = TRUE;
$config["VisualDNA_garant"] = TRUE;

$config["payuot_systems_psnt"] = array(
    "bank_cc" =>            array("min" => 0, "psnt" => 2,   "add" => 5),
    "bank_lava" =>          array("min" => 0, "psnt" => 0,   "add" => 0),
    "bank_qiwi" =>          array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_paypal" =>        array("min" => 0, "psnt" => 4,   "add" => 0),
    //"bank_w1" =>            array("min" => 0, "psnt" => 2,   "add" => 0),
    //"bank_w1_rub" =>        array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_perfectmoney" =>  array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_okpay" =>         array("min" => 0, "psnt" => 2,   "add" => 0),
//    "bank_wpay" =>          array("min" => 0, "psnt" => 2,   "add" => 0),
    //"bank_egopay" =>        array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_tinkoff" =>       array("min" => 3, "psnt" => 2,   "add" => 0),
    "webmoney" =>           array("min" => 0, "psnt" => 3.5, "add" => 0),
    //"bank_liqpay" =>        array("min" => 0, "psnt" => 2,   "add" => 2),
    "bank_yandex" =>        array("min" => 0, "psnt" => 2,   "add" => 0),
    //"bank_name" =>          array("min" => 25,"psnt" => 2,   "add" => 0),
    "bank_rbk" =>           array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_mail" =>          array("min" => 0, "psnt" => 2,   "add" => 0),
//    "bank_name" =>          array("min" => 50,"psnt" => 2,   "add" => 0)
    "wire_bank" =>          array("min" => 50,"psnt" => 2,   "add" => 0)
);
$config["payout_systems"] = array(
        "Payeer" => "bank_lava",
        "VISA/MasterCard" => "bank_cc",
        "QIWI" => "bank_qiwi",
        "W1 (USD)" => "bank_w1",
        "W1 (RUB)" => "bank_w1_rub",
        "RBK Money" => "bank_rbk",
        "Деньги@Mail.Ru" => "bank_mail",
        "PerfectMoney" => "bank_perfectmoney",
        "OKpay" => "bank_okpay",
//        "Wpay" => "bank_wpay",
//        "EGOpay" => "bank_egopay",
        "PayPal" => "bank_paypal",
        "Tinkoff Wallet" => "bank_tinkoff",
        "Webmoney" => "webmoney",
        "Liqpay" => "bank_liqpay",
        "Yandex Деньги" => "bank_yandex",
//        "Bank (EU/US)" => "bank_name"
        "Wire Bank" => "wire_bank"
    );
$config["payout_systems_type"] = array( //type 3+10 = 310
            "bank_name"         => 310,
            "bank_cc"           => 311,
            "bank_lava"         => 312,
            "bank_qiwi"         => 313,
            "bank_w1"           => 314,
            "bank_w1_rub"       => 315,
            "bank_rbk"          => 316,
            "bank_mail"         => 317,
            "bank_perfectmoney" => 318,
            "bank_okpay"        => 319,
            "bank_wpay"         => 320,
            "bank_egopay"       => 321,
            "bank_paypal"       => 322,
            "bank_tinkoff"      => 323,
            "webmoney"          => 324,
            "bank_liqpay"       => 325,
            "bank_yandex"       => 326,
            "wire_bank"         => 327,
        );
$config["pay_sys_psnt"] = array(                     //pay in
    'bank' =>           array("min" => 0, "psnt" => 0,   "add" => 0),
    'bank_norvik' =>    array("min" => 0, "psnt" => 0,   "add" => 0),
    'qiwi' =>           array("min" => 0, "psnt" => 0,   "add" => 0),
    'w1' =>             array("min" => 0, "psnt" => 0,   "add" => 0),
    'mc' =>             array("min" => 0, "psnt" => 0,   "add" => 0),
    'wt' =>             array("min" => 0, "psnt" => 0,   "add" => 0),
    'lava' =>           array("min" => 0, "psnt" => 0,   "add" => 0),
    "paypal" =>         array("min" => 0, "psnt" => 0,   "add" => 0),
    "payeer" =>         array("min" => 0, "psnt" => 0,   "add" => 0),
    "egopay" =>         array("min" => 0, "psnt" => 0,   "add" => 0),
    "perfectmoney" =>   array("min" => 0, "psnt" => 0,   "add" => 0),
    "okpay" =>          array("min" => 0, "psnt" => 0,   "add" => 0),
    "wpay" =>           array("min" => 0, "psnt" => 0,   "add" => 0),
    'nixmoney' =>       array("min" => 0, "psnt" => 0,   "add" => 0),
);
$config["pay_sys"] = array(                     //pay in
        'bank' => array('Банковский счет', 0),
        'bank_norvik' => array('Банковский счет Norvik', 0),
        'qiwi' => array('QIWI Кошелек', 0),
        'w1' => array('Wallet One', 0),
        'mc' => array('Visa / Mastercard', 0),
        'wt' => array('Webtransfer', 0),
        'lava' => array('Lava', 0),
        "out" => array('Вывод средств', 0),
        "paypal" => array('Paypal', 0),
        "arbitr" => array('Арбитраж', 0),
        "payeer" => array('Payeer', 0),
        "egopay" => array('Egopay', 0),
        "perfectmoney" => array('Perfect Money', 0),
        "okpay" => array('OKPay', 0),
        "wpay" => array('WPAY', 0),
        'nixmoney' => array('Nixmoney', 0),
    );
$config["payment_sys"] = array( //type = 1+10 = 110
        310 => 'bank',
        311 => 'mc',
        312 => "payeer",
        313 => 'qiwi',
        314 => 'w1',
//        114 => 'lava',
        318 => 'perfectmoney',
        319 => "okpay",
        320 => "wpay",
        322 => "paypal",
        323 => "bank_norvik",
        324 => 'nixmoney',
    );
$config["payment_bank_fee"] = array(
    'bank' => 20,
    'bank_norvik' => 0
);

$config["transaction_statuses"] = array(
                                1 => 'Получено',
                                2 => 'В ожидании',
                                3 => 'Списано',
                                4 => 'В процессе',
                                5 => 'В ожидании',
                                6 => 'Ошибка',
                                7 => 'Недостаточно средств',
                                8 => 'Удалено',
                                9 => 'Проверено',
                                10=> 'Проверка СБ'
                            );
$config["credit_statuses"] = array(
                                '1'=>"Выставлена",
                                '2'=>"Активный",
                                '3'=>"В ожидании",
                                '4'=>"Просрочен",
                                '5'=>"Выплачен",
                                '6'=>"--Выплачен",
                                '7'=>"Отменен"
                            );

$config["video_catigories"] = array(
    0 => "Без категории",
    1 => "Видео новости",
    2 => "Видео отзывы",
    3 => "Видео уроки",
    4 => "Истории успеха",
    5 => "8 Марта",
    6 => "Стихи",
);
$config["video_catigories_link"] = array(
    0 => "no",
    1 => "news",
    2 => "feedback",
    3 => "lesson",
    4 => "akzii",
    5 => "8-march",
    6 => "poems",
);
$config["checked_users"] = array(500150,500233,EXWT);
$config["untrasted_users_for_send"] = array();

$config["vip_users"] = array(500113, 500112, 500111,500114,76799865);
$config["unlimited_users"] = array(EXWT);

$config["psntStep"] = 0.1;
$config["array4psntStep"] = array();
$i = 0.5;
$config["array4psntStep"][(string)$i] = $i;
do {
    $i += $config["psntStep"];
    $config["array4psntStep"][(string)$i] = $i;
} while ( $i <= 3);



$config["publickeyCapcha"] = ""; // you got this from the signup page
$config["publickeyCapchaPrivate"] = ""; 



$config["ALTERNATE_PHRASE_HASH"] = strtoupper(md5("LP93I0z6a9EgWkGkJvSHayOsA"));
$config["PayeerSecret"] = "";
$config["PayeerPayUser"] = "";
$config["PayeerPaySecret"] = "";
$config["PayeerPayId"] = "31525189";
$config["PayeerPaySystems"] = array("bank_lava");
$config["Payeer_IP"] = "37.59.221.230";

$config["Store_ID"] = "1VIGGAFXM8BD";
$config["Store_Pass"] = "";
$config["Checksum_Key"] = "";

$config['okpayWalletID'] = "";
$config['okpayWalletIDPay'] = "";
$config['okpaySecret'] = "";

$config['perfectmoneySecret'] = ""; //'+/tNDwckaIRslBiQ=';//"mSyVb1+9aCxgIVV9PEsV05DKgvE=";
$config['perfectmoneyID'] = "";
$config['perfectmoneyPayer'] = "";

$config['nixmoneyID'] = 'U33792067134920';//"";
$config['nixMonayHash'] = strtoupper("");//strtoupper(""); //strtoupper("6905ffb2110b0c688e6ec0dc41d263ea");

$config['WpayMerchantID'] = "te54sySIXc";
$config['WpayPassword'] = "";

$config["categories"] = array(
    0 => "Без категории",
    1 => "Базар",
    2 => "Услуги",
    3 => "Аукцион",
);

$config["users4agent"] = [24559609,37718958,96705746,22344518,86462796,85254596,28621703,12839790,64894272,61695455,83933941,90835822,39895850,21550717,99676216,73889972,54268656,90836139,49455378,36010820,33681111,90772465,20903252,79855596,21155135,61732975,13788347,32979768,19831991,77651802,29177146,20759165,59068422,62158116,20430177,93601815,20974398,90835389,19417263,85235320,90837331,78508590,40501403,52393893,38131412,16287918,42590790,79826989,56981340,24564627,15000250,39611020,55313125,53653442,33953375];

/*
 * Documents
 */
//for admins
$config['documents_admin_count']=array('1','2','3','4','5');
$config['documents_admin_label']=array(
    '1'=>'Паспорт (главная  страница)',
    '2'=>'Паспорт (Регистрация)',
    '3'=>'Выписка со счета',
    '4'=>'Копия платежки',
    '5'=>'Иной документ');

//for users
$config['documents_count']=array('1','2','5');//,'3','4');
$config['documents_label']=array(
    '1'=>'Паспорт (главная  страница)',
    '2'=>'Паспорт (Регистрация)',
//    '3'=>'Выписка со счета',
//    '4'=>'Копия платежки'
    '5'=>'Иной документ'
    );

$config['garant-percent']=array('10'=>50.0,'20'=>45.0,'30'=>40.0,'90'=>20.0);
$config['standart-percent']=array('*'=>10.0);

$config['partner-percent']=array('10'=>20.0,'19'=>20.0,'30'=>15.0);
$config['partner-plan']=array('1' => 50, '2' => 50, '3' => 50, '4' => 50); //off this options
$config['partner-plan-add-psnt'] = array('2' => 15, '3' => 20, '4' => 25);
$config['partner-plan-name']=array('1' => "Стандартный", '2' => "VIP", '3' => "Региональный", '4' => "Национальный");
$config['partner-plan-sum']=array('2' => 50000, '3' => 200000, '4' => 1000000);
$config['volunteer-percent'] = 5;
/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['develop'] = true;
$config['base_url_shot'] = 'wsp.cannedstyle.com';
$config['base_url'] = ((empty($_SERVER['HTTPS']))?'http://' : "http://").$config['base_url_shot'];
$config['base_url_preg'] = '/http:\/\/wsp\.cannedstyle\.com\//';
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'russian';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = FALSE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = FALSE;

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']      = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 4;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';


/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Set this to TRUE if you want to use different cache files depending on the
| URL query string.  Please be aware this might result in numerous cache files.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'ses';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependant.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = TRUE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';


function error_handler($errno, $errstr, $errfile, $errline){
    $errno = FriendlyErrorType($errno);
    //if ("E_NOTICE" == $errno) return true;
    log_to_file("MASSAGE: $errstr; FILE: $errfile:$errline", $errno, $errfile, $errline, $errstr);
    return true;
}
#set_error_handler ("error_handler");

function exception_handler($exception) {
    set_status_header(500);
    log_to_file("Massage: ".$exception, 'E_FATAL');
    var_dump($exception);
    echo date("Y-m-d H:i:s")." ERROR - ".$exception->getCode()."<br/> Извините, возникла ошибка на сервере. Сообщите, пожалуйста, в службу технической поддержки Webtransfer.";
    return true;
}

set_exception_handler('exception_handler');


/* End of file config.php */
/* Location: ./application/config/config.php */
