<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'form' => array(
        array(
            'field' => 'parent_id',
            'label' => 'Старший Партнер',
            'rules' => 'trim|regex_match[/^[0-9]{1,14}$/sui]'
        ),
        array(
            'field' => 'n_name',
            'label' => 'Имя',
            'rules' => 'required|trim|min_length[1]|max_length[200]|regex_match[/^[0-9a-zA-ZА-Яа-яЕеЁё\- \"\(\)]*$/sui]'/* узнать */
        ),
        array(
            'field' => 'nickname',
            'label' => 'Nickname',
//            'rules' => 'trim|min_length[3]|max_length[255]|regex_match[/^[0-9a-zA-zА-Яа-яЕеЁё\- \"\(\)]$/sui]'
            'rules' => 'trim|min_length[1]|max_length[255]|regex_match[/^[0-9a-zA-Z\- \"\(\)]*$/sui]'
//            'rules' => 'trim|min_length[3]|max_length[255]|regex_match[/^[0-9a-z]$/sui]'
//            'rules' => 'trim|min_length[3]|max_length[200]|is_unique[users_filds.nickname]'
        ),
        array(
            'field' => 'f_name',
            'label' => 'Фамилия',
            'rules' => 'required|trim|min_length[1]|regex_match[/^[0-9a-zA-ZА-Яа-яЕеЁё\- \"\(\)]*$/sui]|max_length[200]'/* узнать |regex_match[/^[a-zа-яёЁ\s]+$/i]*/
        ),
        array(
            'field' => 'o_name',
            'label' => 'Отчество',
            'rules' => 'trim|max_length[200]|regex_match[/^[0-9a-zA-ZА-Яа-яЕеЁё\- \"\(\)]*$/sui]'/* узнать */
        ),
//        array(
//            'field' => 'phone',
//            'label' => 'Телефон',
//            //	'rules' => 'required|regex_match[/^\([0-9]{3}\)\ [0-9]{3}\-[0-9]{4}$/sui]'
//            //	'rules' => 'regex_match[/^\([0-9]{3}\)\ [0-9]{3}\-[0-9]{4}$/sui]'//
//            'rules' => 'required|min_length[7]|max_length[15]|regex_match[/^[0-9]{7,15}$/sui]'//
//        ),
        //	array(
        //            'field' => 'phone_new',
        //            'label' => 'Телефон (новый формат)',
        //            'rules' => 'required|min_length[11]|max_length[12]|regex_match[/^[0-9]{11,12}$/sui]'//
        //         ),
        array(
            'field' => 'w_phone',
            'label' => 'Рабочий телефон',
            'rules' => 'regex_match[/^[0-9]{7,15}$/sui]'
        ),
        array(
            'field' => 'phone_code',
            'label' => 'Тел. код страны',
            'rules' => 'regex_match[/^[0-9]{1,4}$/sui]'
        ),
        array(
            'field' => 'place',
            'label' => 'Страна',
            'rules' => 'required|max_length[2]'
        ),
        array(
            'field' => 'born_date',
            'label' => 'Дата рождения',
            'rules' => 'required|age_date'
        ),
        array(
            'field' => 'p_seria',
            'label' => 'Серия паспорта',
            'rules' => 'trim'
        ),
        array(
            'field' => 'inn',
            'label' => 'ИНН',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'bank_paypal',
            'label' => 'Paypal',
            'rules' => 'trim|max_length[50]|valid_email'
        ),
        array(
            'field' => 'bank_w1',
            'label' => 'W1',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_perfectmoney',
            'label' => 'PerfectMoney',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_okpay',
            'label' => 'OKpay',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_egopay',
            'label' => 'EGOpay',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_qiwi',
            'label' => 'QIWI',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_tinkoff',
            'label' => 'Tinkoff Wallet',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'bank_cc',
            'label' => 'Номер карты',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'webmoney',
            'label' => 'Webmoney',
            'rules' => 'trim|max_length[50]'
        ),
//        array(
//            'field' => 'bank_yandex',
//            'label' => 'Liqpay',
//            'rules' => 'trim|max_length[20]'
//        ),
        array(
            'field' => 'bank_bik',
            'label' => 'ABA/Swift',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'bank_schet',
            'label' => 'Номер счета',
        //  'rules' => 'regex_match[/^[0-9]{50}$/sui]'
        ),
        array(
            'field' => 'bank_kor',
            'label' => 'Корр.Счет',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'bank_name',
            'label' => 'Название  банка',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'legal_form',
            'label' => 'Правовая Форма Организации',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'ogrn',
            'label' => 'ОГРН',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'kpp',
            'label' => 'КПП',
            'rules' => 'trim|max_length[30]'
        ),
        array(
            'field' => 'p_number',
            'label' => 'Номер  паспорта',
            'rules' => 'trim|max_length[20]'
        ), array(
            'field' => 'p_date',
            'label' => 'Дата выдачи паспорта',
            'rules' => 'valid_date'
        ), array(
            'field' => 'p_kpd',
            'label' => 'Код подразделения',
            'rules' => 'trim|max_length[50]'
        ), array(
            'field' => 'p_kvn',
            'label' => 'Кем выдан',
            'rules' => 'trim|max_length[150]'
        ), array(
            'field' => 'p_born',
            'label' => 'Место рождения',
            'rules' => 'trim|max_length[50]'
        ), array(
            'field' => 'w_name',
            'label' => 'Название огранизации',
            'rules' => 'trim|max_length[50]'
        ), array(
            'field' => 'w_place',
            'label' => 'Регион нахождения организаци',
            'rules' => 'trim|is_natural|max_length[5]'
        ), array(
            'field' => 'w_who',
            'label' => 'Должность',
            'rules' => 'trim|max_length[50]'
        ), array(
            'field' => 'w_time',
            'label' => 'Стаж',
            'rules' => 'trim|max_length[50]'
        ), array(
            'field' => 'w_money',
            'label' => 'Зарплата',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'sex',
            'label' => 'Пол',
            'rules' => 'in_myarray_r[1.2]'
        ),
        array(
            'field' => 'family_state',
            'label' => 'Семейное положение',
            'rules' => 'in_myarray_r[1.2.3]'
        ),
        array(
            'field' => 'f_index',
            'label' => 'Фактический адрес - индекс',
            'rules' => 'trim|is_natural|min_length[4]|max_length[10]'
        ),
        array(
            'field' => 'f_town',
            'label' => 'Фактический адрес - город',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'f_street',
            'label' => 'Фактический адрес - улица',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'f_house',
            'label' => 'Фактический адрес - дом',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'f_kc',
            'label' => 'Фактический адрес - корпус',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'f_flat',
            'label' => 'Фактический адрес - квартира',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'r_index',
            'label' => 'Адрес прописки - индекс',
            'rules' => 'required|trim|is_natural|min_length[4]|max_length[50]'
        ),
        array(
            'field' => 'r_town',
            'label' => 'Адрес прописки - город',
            'rules' => 'required|trim|max_length[20]'
        ),
        array(
            'field' => 'r_street',
            'label' => 'Адрес прописки - улица',
            'rules' => 'required|trim|max_length[30]'
        ),
        array(
            'field' => 'r_house',
            'label' => 'Адрес прописки - дом',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'r_kc',
            'label' => 'Адрес прописки - корпус',
            'rules' => 'trim|max_length[50]'
        ),
        array(
            'field' => 'r_flat',
            'label' => 'Адрес прописки - квартира',
            'rules' => 'trim|max_length[50]'
        )
    ),
    'telephone' => array(
        array(
            'field' => 'name',
            'label' => 'Имя пользователя',
            'rules' => 'trim|required|alpha_space|min_length[3]|max_length[20]'
        ),
        array(
            'field' => 'telephone',
            'label' => 'Телефон',
            'rules' => 'required|regex_match[/^\([0-9]{3}\)\ [0-9]{3}\-[0-9]{4}$/sui]'
        ),
        array(
            'field' => 'when',
            'label' => 'Время',
            'rules' => 'required|is_natural|in_myarray_r[1.2.3]'
        ),
        array(
            'field' => 'email',
            'label' => 'Почта',
            'rules' => 'trim|required|valid_email'
        )
    ),
    'feedback' => array(
        array(
            'field' => 'name',
            'label' => 'Имя пользователя',
            'rules' => 'trim|required|alpha_space|min_length[3]|max_length[15]'
        ),
        array(
            'field' => 'telephone',
            'label' => 'Телефон',
            'rules' => 'required|max_length[50]'
        ),
        array(
            'field' => 'sys_id',
            'label' => '№ Кошелька в системе',
            'rules' => 'regex_match[/^[0-9]/]'
        ),
        array(
            'field' => 'email',
            'label' => 'Почта',
            'rules' => 'trim|required|valid_email'
        ),
        array(
            'field' => 'text',
            'label' => 'Сообщение',
            'rules' => 'trim|required|min_length[5]|max_length[300]'
        )
    ),
    'card' => array(
        array(
            'field' => 'name',
            'label' => 'Имя',
            'rules' => 'trim|required|regex_match[/^[A-Za-z \']+$/]|min_length[3]|max_length[40]'
        ),
        array(
            'field' => 'tel',
            'label' => 'Телефон',
            'rules' => 'trim|required|regex_match[/^[0-9]+/]'
        ),
        array(
            'field' => 'birthday',
            'label' => 'Дата рождения',
            'rules' => 'required|regex_match[/^[1-2][0,9][0-9]{2}\-[0-1][0-9]\-[0-3][0-9]$/]'
        ),
        array(
            'field' => 'country',
            'label' => 'Страна',
            'rules' => 'required|regex_match[/^[0-9]/]'
        ),
        array(
            'field' => 'city',
            'label' => 'Город',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'index',
            'label' => 'Индекс',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'address',
            'label' => 'Адрес',
            'rules' => 'trim|required'
        ),
    ),
    'vote' => array(
        array(
            'field' => 'id_vote',
            'label' => 'Голосование',
            'rules' => 'trim|required|regex_match[/^[0-9]+$/]'
        ),
        array(
            'field' => 'variant',
            'label' => 'вариант',
            'rules' => 'trim|required|regex_match[/^[0-9]+/]'
        )
    )
);

$admin = array(
    array(
        'field' => 'email',
        'label' => 'Почта',
        'rules' => 'trim|required|valid_email|check_db[email]'
    ),
    array(
        'field' => 'password',
        'label' => 'Пароль',
        'rules' => 'trim|alpha_dash|min_length[6]|max_length[15]'
        ));
$reg = array(array(
        'field' => 'password2',
        'label' => 'Повторите пароль',
        'rules' => 'trim|required|matches[password]'
        ));
$recovery = array(array(
        'field' => 'password',
        'label' => 'Новый  пароль',
        'rules' => 'trim|required|alpha_dash|min_length[6]|max_length[15]'
        ));
$old_password = array(array(
        'field' => 'old_password',
        'label' => 'Старый  пароль',
        'rules' => 'trim|required|alpha_dash|min_length[6]|max_length[15]'
        ));

$config['credit'] = array(
    array(
        'field' => 'summ',
        'label' => 'Сумма кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[7]|in_myarray_r['.genSumInvest4Validation().']'/* узнать */
    ),
    array(
        'field' => 'time',
        'label' => 'Срок кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[2]|in_myarray_r['.genTimeInvest4Validation().']'/* узнать */
    ),
    array(
        'field' => 'percent',
        'label' => 'Проценты',
        'rules' => 'required|valid_float|in_myarray_s['.genPsnt4Validation().']'
    ),
    array(
        'field' => 'payment',
        'label' => 'Метод оплаты'
    ),
    array(
        'field' => 'bonus',
        'label' => 'Бонусный счет',
        'rules' => 'is_natural'
    ),
    array(
        'field' => 'garant',
        'label' => 'Гарант',
        'rules' => 'is_natural'
    ),
    array(
        'field' => 'overdraft',
        'label' => 'Овердрафт',
        'rules' => 'is_natural'
    )
);

$config['credit_partner'] = array(
    array(
        'field' => 'summ',
        'label' => 'Сумма кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[7]|in_myarray_r['.genSumInvest4Validation().']'/* узнать */
    ),
    array(
        'field' => 'time',
        'label' => 'Срок кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[2]|in_myarray_r[40]'/* узнать */
    ),
    array(
        'field' => 'percent',
        'label' => 'Проценты',
        'rules' => 'required|valid_float|in_myarray_s[0.5]'
    ),
    array(
        'field' => 'payment',
        'label' => 'Метод оплаты'
    ),
    array(
        'field' => 'bonus',
        'label' => 'Бонусный счет',
        'rules' => 'is_natural'
    ),
    array(
        'field' => 'garant',
        'label' => 'Гарант',
        'rules' => 'is_natural'
    ),
    array(
        'field' => 'overdraft',
        'label' => 'Овердрафт',
        'rules' => 'is_natural'
    )
);

$config['investor'] = array(
    array(
        'field' => 'n_vklad',
        'label' => 'Сумма инвестиции',
        'rules' => 'required|regex_match[/[0-9\s]*/sui]|max_length[13]|min_length[2]'/* узнать */
    ),
    array(
        'field' => 'vklad',
        'label' => 'План',
        'rules' => 'type_valid'/* узнать */
    )
);
$config['amount'] = array(
    array(
        'field' => 'amount',
        'label' => 'Сумма',
        'rules' => 'required|regex_match[/[0-9\.]*/sui]|max_length[13]|min_length[1]'/* узнать */
    ),
    array(
        'field' => 'payout_system',
        'label' => 'Через',
        'rules' => 'required|in_myarray_r['.genPayoutSystems4Validation().']'/* bank_cc.bank_qiwi.bank_paypal.bank_tinkoff.webmoney.bank_liqpay.bank_yandex.bank_name.bank_w1.bank_perfectmoney.bank_okpay.bank_egopay.*/
    ),
);
$config['logining'] = array(
    array(
        'field' => 'email',
        'label' => 'емаил',
        'rules' => 'trim|required|valid_email|max_length[60]|min_length[6]'/* узнать */
    ),
    array(
        'field' => 'password',
        'label' => 'пароль',
        'rules' => 'trim|required'/* узнать */
    ),
    array(
        'field' => 'new',
        'label' => 'новый',
        'rules' => ''/* узнать */
    ),
);
$config['summa'] = array(
    array(
        'field' => 'summa',
        'label' => 'Сумма',
        'rules' => 'required|regex_match[/[0-9\.]*/sui]|max_length[13]|min_length[1]'/* узнать */
    ),
);

$config['send_money'] = array(
    array(
        'field' => 'id_user',
        'label' => 'пользователя',
        'rules' => 'trim|required|is_email_or_natural|max_length[60]|min_length[1]'/* узнать */
    ),
    array(
        'field' => 'amount',
        'label' => 'сумму',
        'rules' => 'trim|required|max_length[13]|min_length[1]'/* узнать */
    ),
    array(
        'field' => 'account_type',
        'label' => 'счет',
        'rules' => 'trim|max_length[10]|min_length[3]'
    ),
    array(
        'field' => 'note',
        'label' => 'Описание',
        'rules' => 'trim|max_length[100]'/* узнать */
    ),
//    array(
//        'field' => 'recaptcha_challenge_field',
//        'label' => 'строку с картинки',
//        'rules' => 'required'/* узнать */
//    ),
//    array(
//        'field' => 'recaptcha_response_field',
//        'label' => 'строку с картинки',
//        'rules' => 'required'/* узнать */
//    ),
);
$config['automatic'] = array(
    array(
        'field' => 'summ',
        'label' => 'Сумма кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[7]|in_myarray_r['.genSumInvest4Validation().']'/* узнать */
    ),
    array(
        'field' => 'time',
        'label' => 'Срок кредита',
        'rules' => 'required|is_natural|min_length[1]|max_length[2]|in_myarray_r['.genTimeInvest4Validation().']'/* узнать */
    ),
    array(
        'field' => 'percent',
        'label' => 'Проценты',
        'rules' => 'required|valid_float|in_myarray_s['.genPsnt4Validation().']'
    ),
);
$face = array(array(
        'field' => 'face',
        'label' => 'Тип',
        'rules' => 'in_myarray_r[1.2]'
        ));
$face_r = array(array(
        'field' => 'face',
        'label' => 'Тип',
        'rules' => 'in_myarray_r[1.2]'
        ));
$config['profile'] = $config['form'];
$config['recovery'] = array_merge($reg, $recovery);
$config['password_change'] = array_merge($reg, $recovery, $old_password);
$config['admin_profile'] = array_merge($config['form'], $admin, $face_r);

$config['form_parent'] = array_merge($reg, $config['form'], $admin);
$config['form_loginza'] = array_merge($reg, $config['form'], $admin);
$config['form_investor'] = array_merge($reg, $config['form'], $admin, $config['credit'], $face_r);
$config['form'] = array_merge($reg, $config['form'], $admin, $config['credit'], $face_r);