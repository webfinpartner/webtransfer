<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('EXWT', 46504049);

$config['goto_after_login'] = 'social/profile'; //'page/about/blog'

$config['exchange_users'] = [];
$config['promo_exchange_users'] = [];

$config['payout.use_payout_limit_for_users'] = [];

$config["db_lang"] = 'ru';
$config['social_wallpost_bonus'] = 20;
$config['social_wallpost_bonus'] = 20;


$config['arbitration_b6_enabled'] = TRUE;

$config['mistakes_to_mails'] = ['avj83@list.ru','ravshan@webtransfer.com', 'test@webtransfer.com'];


$config['usd2_fee_users_available'] = [];


$config['arbitration_scores_vip_users'] = [
    2 => [],
    3 => []
];


$config['stab_fond_exchange_perc'] = 1.2;
$config['stab_fond_payment_priority'] = [
    312, 318,319, 'card'
];


$config['create_card_on_registration'] = TRUE;

$config['MerchantBayBonusProgram'] = 1.1;
$config['exchangeUsersMerchantBayBonusProgram'] = [74462637];
$config['exchangeUsersMerchantWTDebitEnabled'] = [74462637,500113,69504490,25881340,90827163,500150,46504049,16782056];


$config["bonus_on_incame_pay"] = true;
$config["bonus_on_incame_pay_from_send_money"] = false;
$config["psnt_bonus_on_incame_pay_from_send_money"] = 0.2;
$config["min_sum_4_count_bonus_on_incame_pay_from_send_money"] = 100;
$config["min_sum_of_bonuses_on_incame_pay_from_send_money"] = 50;
$config["sum_add_of_bonuses_on_incame_pay_from_send_money"] = 50;
$config['usd2_to_ccreds_perc'] = 100;
$config["bonus_first_credit"] = true;
$config["bonus_first_credit_start"] = '2015-05-11';
$config['usd1_to_ccreds_perc'] = 50;
$config['card_payin_comission'] = [312=>4.5,
                                   318=>6,
                                   319=>7];
$config["account_payin_comission"] = 0;
$config["account_sendmoney_comission"] = 1;


$config['exchanger_commissiions'] = [ // $config['exchanger_commissiions'] определена в другом файле
  'card_to_payment_account' => 1,
  'card_to_pay_sys' => 2,
  'payment_account_to_card' => 3,
  'payment_account_to_pay_sys' => 4,
  'pay_sys_to_card' => 5,
  'pay_sys_to_payment_account' => 6

];

$config["VisualDNA_stangart"] = TRUE;
$config["VisualDNA_garant"] = TRUE;
$config['bonus_for_card_invest'] = 20;
//0 - без лимита
$config['loadfunds_limit']= [
   312 => 10,
   318 => 10,
   319 => 10
];
$config["payuot_systems_psnt"] = array(
    "bank_cc" =>            array("min" => 0, "psnt" => 2,   "add" => 5, 'bonus'=> 0),
    "bank_lava" =>          array("min" => 0, "psnt" => 3,   "add" => 0, 'bonus'=> 0),
    "bank_qiwi" =>          array("min" => 0, "psnt" => 3,   "add" => 0, 'bonus'=> 0),
 //   "bank_paypal" =>        array("min" => 0, "psnt" => 4,   "add" => 0),
    //"bank_w1" =>            array("min" => 0, "psnt" => 2,   "add" => 0),
    //"bank_w1_rub" =>        array("min" => 0, "psnt" => 2,   "add" => 0),
    "bank_perfectmoney" =>  array("min" => 0, "psnt" => 1.5,   "add" => 0, 'bonus'=> 0),
    "bank_okpay" =>         array("min" => 0, "psnt" => 0.5,   "add" => 0, 'bonus'=> 0),
//    "bank_wpay" =>          array("min" => 0, "psnt" => 2,   "add" => 0),
    //"bank_egopay" =>        array("min" => 0, "psnt" => 2,   "add" => 0),
  //  "bank_tinkoff" =>       array("min" => 3, "psnt" => 2,   "add" => 0),
    "webmoney" =>           array("min" => 0, "psnt" => 4.5, "add" => 0, 'bonus'=> 0),
    //"bank_liqpay" =>        array("min" => 0, "psnt" => 2,   "add" => 2),
    "bank_yandex" =>        array("min" => 0, "psnt" => 3,   "add" => 0, 'bonus'=> 0),
    //"bank_name" =>          array("min" => 25,"psnt" => 2,   "add" => 0),
 //   "bank_rbk" =>           array("min" => 0, "psnt" => 2,   "add" => 0),
 //   "bank_mail" =>          array("min" => 0, "psnt" => 2,   "add" => 0),
 //  "bank_name" =>          array("min" => 50,"psnt" => 2,   "add" => 0),
 //   "wire_bank" =>          array("min" => 50,"psnt" => 2,   "add" => 0),
    "wtcard"    =>          array("min" => 0,"psnt" => 3, 'psnt_max' => 3, "add" => 0, 'bonus'=> 0),
);
$config["pay_sys_psnt"] = array(                     //pay in
    'bank' =>           array("min" => 0, "psnt" => 0,   "add" => 0),
    'bank_norvik' =>    array("min" => 0, "psnt" => 0,   "add" => 0),
    'bank_raiffeisen' =>array("min" => 0, "psnt" => 0,   "add" => 0),
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
    'wtgift' =>       array("min" => 0, "psnt" => 0,   "add" => 0),
);
$config["payout_systems"] = array(
    "Webtransfer VISA Card" => "wtcard",
    "Payeer" => "bank_lava",
 //   "VISA/MasterCard" => "bank_cc",
    //"W1 (USD)" => "bank_w1",
    //"W1 (RUB)" => "bank_w1_rub",
 //   "RBK Money" => "bank_rbk",
 //   "Деньги@Mail.Ru" => "bank_mail",
    "PerfectMoney" => "bank_perfectmoney",
    "OKpay" => "bank_okpay",
//        "Wpay" => "bank_wpay",
//        "EGOpay" => "bank_egopay",
 //   "PayPal" => "bank_paypal",
 //   "Tinkoff Wallet" => "bank_tinkoff",
    //"Webmoney" => "webmoney",
//	"QIWI" => "bank_qiwi",
    //"Liqpay" => "bank_liqpay",
 //   "Yandex Деньги" => "bank_yandex",
 //       "Bank (EU/US)" => "bank_name",
 //   "Wire Bank" => "wire_bank",

	"Webtransfer Admin" => "wtadmin",
);
$config['webtransfercard'] = "https://webtransfercard.com/";

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
    "wtcard"            => 328,
	"wtadmin"            => 330,
);
$config["auto_payout_systems_type"] = [312, 319, 318, 328];
$config["auto_payout_systems_type_by_one"] = [];
//$config["auto_payout_systems_type_by_one"] = [];
$config["WTCApi_log"] = 2; // 0 - off, 2- on, 3 - on with auth log
$config["Merchnt_log"] = 1; // 0 - off, 1- on
$config["Payment_log"] = 1; // 0 - off, 1- on
$config["Payout_log"] = 0; // 0 - off, 1- on
$config["Payeer_log"] = 0; // 0 - off, 1- on
$config["Okpay_log"] = 0; // 0 - off, 1- on
$config["PerfectMoney_log"] = 0; // 0 - off, 1- on
$config["pay_sys"] = array(                     //pay in
    'bank' => array('Банковский счет', 0),
    'bank_norvik' => array('Банковский счет Norvik', 0),
    'bank_raiffeisen' => array('Банковский счет Raiffeisen', 0),
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
    'wtcard' => array('Webtransfer Visa', 0),
    'wtadmin' => array('Webtransfer Admin', 0),
    'wtgift' => array('Webtransfer Gift', 0),
);
$config["payment_sys"] = array( //type = 1+10 = 110
    310 => 'bank',
    311 => 'mc',
    312 => "payeer",
    313 => 'qiwi',
    314 => 'w1',
    318 => 'perfectmoney',
    319 => "okpay",
    320 => "wpay",
    322 => "paypal",
    323 => "bank_norvik",
    324 => 'nixmoney',
    326 => "yandex",
    327 => "wire",
    328 => 'wtcard',
    329 => "bank_raiffeisen",
    330 => "wtadmin",
    331 => "wtgift",

);
$config["payment_bank_fee"] = array(
    'bank' => 20,
    'bank_norvik' => 0,
    'bank_raiffeisen' => 0,
);
$config["payment_bank_shablon"] = array(
    'bank'              => 21,
    'bank_norvik'       => 27,
    'bank_raiffeisen'   => 55,
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
    10=> 'Проверка СБ',
	11=> 'Стаб. фонд',
	13=> 'Проверка СБ2'
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
    7 => "Запиши видео и получи $1000",
    5 => "8 Марта",
    6 => "Стихи",
	8 => "Юмористический конкурс",
	9 => "QIWI Терминалы",
);
$config["video_catigories_link"] = array(
    0 => "no",
    1 => "news",
    2 => "feedback",
    3 => "lesson",
    4 => "akzii",
	7 => "loan-hour",
    5 => "8-march",
    6 => "poems",
	8 => "fun",
	9 => "qiwi-terminal",
);
$config["checked_users"] = array(500150,500233,EXWT, 87270475,25742299,17956457,16782056,500150,90996925,43094867,36273087, 76799865,94673472,90835755,22498968,92122121,23628555,14310709,12205571,38703936,25881340,74462637,86751272,65927623);
$config["untrasted_users_for_send"] = array();

$config["vip_users"] = array(500113, 500112,500150, 500111,500114,90996925,76799865,65927623,36273087,94673472,43094867,90835755);
$config["unlimited_users"] = array(EXWT, 500150,87270475,17956457,25742299, 16782056,22498968,92122121,90996925,76799865,36273087,23628555,14310709,94673472,12205571,38703936,25881340,74462637,86751272,65927623,43094867,90835755);

$config["psntStep"] = 0.1;
$config["array4psntStep"] = array();
$i = 0.5;
$config["array4psntStep"][(string)$i] = $i;
do {
    $i += $config["psntStep"];
	$i = number_format($i, 1, '.', '');
    $config["array4psntStep"][(string)$i] = $i;
} while ($i < 3);

$config["categories"] = array(
    0 => "Без категории",
    1 => "Базар",
    2 => "Услуги",
    3 => "Аукцион",
);

$config["users4agent"] = [24559609,37718958,96705746,22344518,86462796,85254596,28621703,12839790,64894272,61695455,83933941,90835822,39895850,21550717,99676216,73889972,54268656,90836139,49455378,36010820,33681111,90772465,20903252,79855596,21155135,61732975,13788347,32979768,19831991,77651802,29177146,20759165,59068422,62158116,20430177,93601815,20974398,90835389,19417263,85235320,90837331,78508590,40501403,52393893,38131412,16287918,42590790,79826989,56981340,24564627,15000250,39611020,55313125,53653442,33953375];

$config['default_card_bank'] = [
               'accountNumber'=>11549235,
               'bankName'=>'Lloyds TSB',
               'iban' => 'GB38 LOYD 3096 3411 5492 35',
               'swift' => 'LOYDGB2L',
               'branchAddress' => 'London',
               'accountName' => 'Worldpay AP Ltd' ,
               'bankCode' => '30-96-34',
               'bankAccountCurrency' => 'USD',
               'bankAccountCountry' => 'GB',
               ];

/*
 * Documents
 */
//for admins
$config['documents_admin_count']=array('1','2','3','4','5');
$config['documents_admin_label']=array(
    '1'=>'Паспорт (главная  страница)',
    '2'=>'Регистрация (подтверждение адреса)',
    '3'=>'Выписка со счета',
    '4'=>'Копия платежки',
    '5'=>'Иной документ');

//for users
$config['documents_count']=array('1','2','5');//,'3','4');
$config['documents_label']=array(
    '1'=>'Паспорт (главная  страница)',
    '2'=>'Регистрация (подтверждение адреса)',
//    '3'=>'Выписка со счета',
//    '4'=>'Копия платежки'
    '5'=>'Иной документ'
    );
$config['documents_status'] = [
    1 => 'На рассмотрении',
    2 => 'Одобрен',
    3 => 'Отклонен',
    4 => 'Документ поддельный или чужой',
    5 => 'Возраст по документу меньше 18 лет',
    6 => 'Черно-белая копия',
    7 => 'Недействительный документ',
    8 => 'Сведения в документе нечитаемы',
    9 => 'Несоответствие данных в документе и в профиле',
    10 => 'Копия не отображается - недопустимый формат файла',
    11 => 'Сведения в документе не на латинице и без перевода',
    12 => 'Номер не соответствует номеру основной страницы',
    13 => 'Прописка или документ с истекшим сроком действия',
];
$config['garant-percent']=array('10'=>50.0,'20'=>45.0,'30'=>40.0,'90'=>20.0);
$config['standart-percent']=array('*'=>10.0);

$config['partner-percent']=array('10'=>20.0,'19'=>20.0,'30'=>15.0);
$config['partner-plan-old']=array('1' => 50, '2' => 50, '3' => 50, '4' => 50, '5' => 50, '6' => 50, '7' => 50); //off this options
$config['partner-plan'] = array('1' => 20, '2' => 20, '3' => 20, '4' => 20, '5' => 20, '6' => 20, '7' => 20); //off this options
//$config['partner-plan'] = array('1' => 20, '2' => 30, '3' => 40, '4' => 50, '5' => 60, '6' => 70, '7' => 80); //off this options
$config['partner-plan-add-psnt'] = array('2' => 10, '3' => 20, '4' => 30, '5' => 40, '6' => 50, '7' => 60);
$config['partner-old50-last-credit'] = 36980859;
$config['partner-plan-add-psnt-old'] = array('2' => 0, '3' => 0, '4' => 0, '5' => 10, '6' => 20, '7' => 30);
$config['partner-plan-name']=array('1' => "Стандартный", '2' => "Стандартный 30", '3' => "Стандартный 40", '4' => "Стандартный 50", '5' => "VIP", '6' => "Региональный", '7' => "Национальный");
$config['partner-plan-sum']=array('2' => 20000, '3' => 30000, '4' => 40000, '5' => 50000, '6' => 200000, '7' => 1000000);
$config['volunteer-percent'] = 5;
