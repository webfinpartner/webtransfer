<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['page/(.+)'] = "user/page/index/$2";

$route['index2']="user/home/index2";
$route['(:num)/(.+)']="user/public_user/$2/$1";
$route['(:num)']="user/public_user/wall/$1";
$route['registration']="user/registr/index";
$route['registration/send/(.+)']="user/registr/send/$1";
$route['registration/(.+)']="user/page/registration/$1";
$route['login']="user/login/index";
$route['loginza']="user/login/loginza";
$route['login/(.+)']="user/login/$1";
$route['login/confirm/(.+)']="user/login/confirm/$1";
//$route['partner']='user/partner/home';
//$route['partner/(.+)']="user/partner/$1";
$route['nabomba']="user/nabomba/index";
$route['merchant/pay']="user/merchant/pay";


$route['partner']='user/partner/home';
//$route['partner/(.+)']="user/partner/$1";
$route['partner/support']='user/partner/support';
$route['partner/banner']='user/partner/banner';
$route['partner/invite']='user/partner/invite';
$route['partner/get_bonus']='user/partner/get_bonus';
$route['partner/transaction']='user/partner/transaction';
$route['partner/about_partner']='user/partner/about_partner';
$route['partner/my_users']='user/partner/my_users';
$route['partner/my_users/(.+)']='user/partner/my_users/$1';
$route['partner/my_balance']='user/partner/my_balance';
$route['partner/expected_income']='user/partner/expected_income';
$route['partner/balances']='user/partner/balances';
$route['partner/top']='user/partner/top';
$route['partner/trigerVolunteer/(.*)']='user/partner/trigerVolunteer/$1';

$route['partner/edit_page/(:num)'] = "user/partners_url/edit_page/$1";

$route['partner/my-link'] = "user/partners_url/index";
$route['partner/(:any)'] = "user/ref/refir/$1";

$route['marketing'] = "user/ref/marketing";

$route['login/out']="user/login/out";
$route['news/(.+)']="user/novost/index/$1";
$route['ask/(.+)']="user/ask/$1"; //(telephone|feedback|check|forget|recovery|get_kurslavapaycallback|perfectmoneyCallback|payeerCallback|egopayCallback||)

$route['(article|category|page)/(:num)'] = "user/$1/index/$2";

$route['page/(.+)'] = "user/page/index/$1";
$route['video/next_video/(.+)'] = "user/video/next_video/$1";
$route['video/vote/(:num)'] = "user/video/vote/$1";
$route['video/(.+)'] = "user/video/show/$1";
$route['video'] = "user/video/index";



$route['account/giftcard']="user/giftcard/create";
$route['account/giftcard/(.*)']="user/giftcard/$1";
$route['account/guarant']="user/guarant/create";
$route['account/guarant/(.*)']="user/guarant/$1";
$route['account/guarant/terms']="user/guarant/terms";

$route['account/merchant'] = "user/merchant/index";
$route['account/merchant/(.+)'] = "user/merchant/$1";
$route['account/automatic'] = "user/automatic/index";
$route['account/automatic/createAutomatic'] = "user/automatic/createAutomatic";
$route['account/exchange/bay/(.+)'] = "user/exchange/bay/$1";
$route['account/back4exchangeCertificate/(:num)'] = "user/exchange/del/$1";
$route['account/exchangeCertificate'] = "user/exchange/add";
$route['account/editExchangeCertificate'] = "user/exchange/edit";
$route['account/exchange'] = "user/exchange/index";
$route['account/exchange_creds2'] = "user/exchange/exchange_creds2";

$route['account/currency_exchange'] = "user/currency_exchange/index";
$route['account/currency_exchange/(:any)'] = "user/currency_exchange/$1";
$route['account/currency_exchange/(:any)/(:any)'] = "user/currency_exchange/$1/$2";

$route['account/security'] = "user/security/index";
$route['account/exchanges2-list/(:any)'] = "user/accaunt/exchanges2_index/$1";
$route['account/exchanges2-list'] = "user/accaunt/exchanges2_list";
$route['account/exchanges-(:any)'] = "user/accaunt/exchanges_page/$1";





$route['account/card-(.+)'] = "user/card/$1";
$route['account/card/(:any)'] = "user/card/card/$1";

$route['account/card-(.+)/(:num)'] = "user/card/$1/$2";

$route['account/card-(.+)/iframe'] = "user/card/cardiframe/$1";
$route['account/card/iframe/(:any)'] = "user/card/cardiframe/$1";

$route['account/arbitrage_credit'] = "user/arbitrage/arbitrage_credit";
$route['account/arbitrage_invest'] = "user/arbitrage/arbitrage_invest";
$route['account/arbitrage_credit_recalculate'] = "user/arbitrage/arbitrage_credit_recalculate";
$route['account/return_arbitration_part/(:num)'] = "user/arbitrage/return_arbitration_part/$1";


$route['account/ajax_user/whatsapp_request'] = "user/security/ajax_user/whatsapp_request";
$route['account/ajax_user/viber_request'] = "user/security/ajax_user/viber_request";
$route['account/ajax_user/email_request'] = "user/security/ajax_user/email_request";

$route['security/get_code_chart/(:any)'] = "user/security/get_code_chart/$1";
$route['security/ajax/(:any)'] = "user/security/ajax_user/$1";
$route['account/ajax_user/sms_request'] = "user/security/ajax_user/sms_request";
$route['account/ajax_user/voice_request'] = "user/security/ajax_user/voice_request";
$route['account/ajax_user/voice_state'] = "user/security/ajax_user/voice_state";
$route['account/ajax_user/sms_request/(.+)'] = "user/security/ajax_user/sms_request/$1";
$route['account/ajax_user/save_security'] = "user/security/ajax_user/save_security";
$route['account/ajax_user/sms_check'] = "user/security/ajax_user/sms_check";

$route['account/(:num)']="user/accaunt/index/$1";
$route['account/(credit|invest)-doc-(:num).pdf']="user/accaunt/$1_doc/$2";
$route['account/ofert-(:num).pdf']="user/accaunt/getofert/$1";
$route['account/paymentdoc-(:num).pdf']="user/accaunt/getPaymentDoc/$1";
$route['account']="user/accaunt/index";
$route['account/(.+)']="user/accaunt/$1";
$route['arbitrage/(.+)']="user/arbitrage/$1";



$route['api/(:num)/(.+)'] = "apis/ver_1/$2";
$route['loan_api/(.+)'] = "apis/loan_api/$1";
$route['secure_api/(.+)'] = "apis/secure_api/$1";
$route['wtchat_api/(.+)'] = "apis/wtchat_api/$1";
$route['wtcard_api/(.+)'] = "apis/wtcard_api/$1";
$route['exchange_api/(.+)'] = "apis/exchange_api/$1";
$route['widget_api/(.+)'] = "apis/widget_api/$1";
$route['dev'] = "api_docs/index";
//$route['cron/(.+)'] = "cron/$1";

$lang = '(ru|en|de|fr|nl)/';

foreach($route as $key => $item){
//	unset($route[$key]);
	$item = preg_replace('~\$2~','\$3', $item);
	$item = preg_replace('~\$1~','\$2', $item);
	$route[$lang. $key] = $item;
}
$route['default_controller'] = "user/home/index";
// URI like '/en/about' -> use controller 'about'
$route['^(ru|en)/(.+)$'] = "$2";

// '/en', '/de', '/fr' and '/nl' URIs -> use default controller
$route['^(ru|en)$'] = $route['default_controller'];

$route['opera'] = "admin/admin_profile/index";

//$route['opera/(:num)'] = "admin/home/index/$1";


$route['opera/(sender|payment|shablon|contribution|invest|page|statistic|statistic_messenger|system_messages|system_events|system_vars|news|video|system_news|feedback|send_notices|faq|banner|exchanges|users|credit|settings|admins|log|business_center|partner_banner|vdna_reports|translate)/(:num)'] = "admin/$1/index/$2";
$route['opera/users/downloadEmail'] = "admin/users/downloadEmail";
$route['opera/users/downloadActiveUsers'] = "admin/users/downloadActiveUsers";
$route['opera/payment/stat_day/(.+)'] = "admin/payment/stat_day/$1";
$route['opera/profile']="admin/admin_profile/index";
$route['opera/login']="admin/auth/login";
$route['opera/cards']="admin/cards/index";
$route['opera/cards/(.+)']="admin/cards/$1";
$route['opera/(.+)'] = "admin/$1";
$route['login/(.+)']="user/login/$1";
$route['ask/(.+)']="user/ask/$1";
$route['merchant/pay']="user/merchant/pay";

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/* End of file routes.php */
/* Location: ./application/config/routes.php */
