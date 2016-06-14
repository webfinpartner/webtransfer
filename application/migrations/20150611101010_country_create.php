<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Country_create extends CI_Migration {

    public function up()
    {
        $this->dbforge->drop_table('countries');
        $fields = array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'name_len_3' => array(
                'type' =>'CHAR',
                'constraint' => '6'
            ),
            'name_len_2' => array(
                'type' =>'CHAR',
                'constraint' => '4'
            ),
        );
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field($fields)->create_table('countries');

        $code   = array();
        $code[] = array('county_name_ru' => 'Россия', 'code' => '7', 'extra_short' => 'RU', 'short' => 'RUS');
        $code[] = array('county_name_ru' => 'Австралия', 'code' => '61', 'extra_short' => 'AU', 'short' => 'AUS');
        $code[] = array('county_name_ru' => 'Австрия', 'code' => '43', 'extra_short' => 'AT', 'short' => 'AUT');
        $code[] = array('county_name_ru' => 'Азербайджан', 'code' => '994', 'extra_short' => 'AZ', 'short' => 'AZE');
        $code[] = array('county_name_ru' => 'Албания', 'code' => '355', 'extra_short' => 'AL', 'short' => 'ALB');
        $code[] = array('county_name_ru' => 'Алжир', 'code' => '213', 'extra_short' => 'DZ', 'short' => 'DZA');
        $code[] = array('county_name_ru' => 'Американские Виргинские о-ва', 'code' => '1 340', 'extra_short' => 'VI', 'short' => 'VIR');
        $code[] = array('county_name_ru' => 'Американское Самоа', 'code' => '1 684', 'extra_short' => 'AS', 'short' => 'ASM');
        $code[] = array('county_name_ru' => 'Ангилья', 'code' => '1 264', 'extra_short' => 'AI', 'short' => 'AIA');
        $code[] = array('county_name_ru' => 'Ангола', 'code' => '244', 'extra_short' => 'AO', 'short' => 'AGO');
        $code[] = array('county_name_ru' => 'Андорра', 'code' => '376', 'extra_short' => 'AD', 'short' => 'AND');
        $code[] = array('county_name_ru' => 'Антарктида', 'code' => '672', /* 'extra_short'=>'AQ', */
                        'short'          => 'ATA');
        $code[] = array('county_name_ru' => 'Антигуа и Барбуда', 'code' => '1 268', 'extra_short' => 'AG', 'short' => 'ATG');
        $code[] = array('county_name_ru' => 'Аргентина', 'code' => '54', 'extra_short' => 'AR', 'short' => 'ARG');
        $code[] = array('county_name_ru' => 'Армения', 'code' => '374', 'extra_short' => 'AM', 'short' => 'ARM');
        $code[] = array('county_name_ru' => 'Аруба', 'code' => '297', 'extra_short' => 'AW', 'short' => 'ABW');
        $code[] = array('county_name_ru' => 'Афганистан', 'code' => '93', 'extra_short' => 'AF', 'short' => 'AFG');
        $code[] = array('county_name_ru' => 'Багамские о-ва', 'code' => '1 242', 'extra_short' => 'BS', 'short' => 'BHS');
        $code[] = array('county_name_ru' => 'Бангладеш', 'code' => '880', 'extra_short' => 'BD', 'short' => 'BGD');
        $code[] = array('county_name_ru' => 'Барбадос', 'code' => '1 246', 'extra_short' => 'BB', 'short' => 'BRB');
        $code[] = array('county_name_ru' => 'Бахрейн', 'code' => '973', 'extra_short' => 'BH', 'short' => 'BHR');
        $code[] = array('county_name_ru' => 'Беларусь', 'code' => '375', 'extra_short' => 'BY', 'short' => 'BLR');
        $code[] = array('county_name_ru' => 'Белиз', 'code' => '501', 'extra_short' => 'BZ', 'short' => 'BLZ');
        $code[] = array('county_name_ru' => 'Бельгия', 'code' => '32', 'extra_short' => 'BE', 'short' => 'BEL');
        $code[] = array('county_name_ru' => 'Бенин', 'code' => '229', 'extra_short' => 'BJ', 'short' => 'BEN');
        $code[] = array('county_name_ru' => 'Бермудские о-ва', 'code' => '1 441', 'extra_short' => 'BM', 'short' => 'BMU');
        $code[] = array('county_name_ru' => 'Бирма (Мьянма)', 'code' => '95', 'extra_short' => 'MM', 'short' => 'MMR');
        $code[] = array('county_name_ru' => 'Болгария', 'code' => '359', 'extra_short' => 'BG', 'short' => 'BGR');
        $code[] = array('county_name_ru' => 'Боливия', 'code' => '591', 'extra_short' => 'BO', 'short' => 'BOL');
        $code[] = array('county_name_ru' => 'Босния и Герцеговина', 'code' => '387', 'extra_short' => 'BA', 'short' => 'BIH');
        $code[] = array('county_name_ru' => 'Ботсвана', 'code' => '267', 'extra_short' => 'BW', 'short' => 'BWA');
        $code[] = array('county_name_ru' => 'Бразилия', 'code' => '55', 'extra_short' => 'BR', 'short' => 'BRA');
//    $code[] = array( 'county_name_ru' =>'Британская территория Индийского океана', 'code'=> '', 'extra_short'=>'IO', 'short'=>'IOT');
        $code[] = array('county_name_ru' => 'Британские Виргинские о-ва', 'code' => '1 284', 'extra_short' => 'VG', 'short' => 'VGB');
        $code[] = array('county_name_ru' => 'Бруней', 'code' => '673', 'extra_short' => 'BN', 'short' => 'BRN');
        $code[] = array('county_name_ru' => 'Буркина-Фасо', 'code' => '226', 'extra_short' => 'BF', 'short' => 'BFA');
        $code[] = array('county_name_ru' => 'Бурунди', 'code' => '257', 'extra_short' => 'BI', 'short' => 'BDI');
        $code[] = array('county_name_ru' => 'Бутан', 'code' => '975', 'extra_short' => 'BT', 'short' => 'BTN');
        $code[] = array('county_name_ru' => 'Вануату', 'code' => '678', 'extra_short' => 'VU', 'short' => 'VUT');
        $code[] = array('county_name_ru' => 'Венгрия', 'code' => '36', 'extra_short' => 'HU', 'short' => 'HUN');
        $code[] = array('county_name_ru' => 'Венесуэла', 'code' => '58', 'extra_short' => 'VE', 'short' => 'VEN');
        $code[] = array('county_name_ru' => 'Вьетнам', 'code' => '84', 'extra_short' => 'VN', 'short' => 'VNM');
        $code[] = array('county_name_ru' => 'Габон', 'code' => '241', 'extra_short' => 'GA', 'short' => 'GAB');
        $code[] = array('county_name_ru' => 'Гайана', 'code' => '592', 'extra_short' => 'GY', 'short' => 'GUY');
        $code[] = array('county_name_ru' => 'Гаити', 'code' => '509', 'extra_short' => 'HT', 'short' => 'HTI');
        $code[] = array('county_name_ru' => 'Гамбия', 'code' => '220', 'extra_short' => 'GM', 'short' => 'GMB');
        $code[] = array('county_name_ru' => 'Гана', 'code' => '233', 'extra_short' => 'GH', 'short' => 'GHA');
        $code[] = array('county_name_ru' => 'Гватемала', 'code' => '502', 'extra_short' => 'GT', 'short' => 'GTM');
        $code[] = array('county_name_ru' => 'Гвинея', 'code' => '224', 'extra_short' => 'GN', 'short' => 'GIN');
        $code[] = array('county_name_ru' => 'Гвинея-Бисау', 'code' => '245', 'extra_short' => 'GW', 'short' => 'GNB');
        $code[] = array('county_name_ru' => 'Германия', 'code' => '49', 'extra_short' => 'DE', 'short' => 'DEU');
        $code[] = array('county_name_ru' => 'Гибралтар', 'code' => '350', 'extra_short' => 'GI', 'short' => 'GIB');
        $code[] = array('county_name_ru' => 'Гондурас', 'code' => '504', 'extra_short' => 'HN', 'short' => 'HND');
        $code[] = array('county_name_ru' => 'Гонконг', 'code' => '852', 'extra_short' => 'HK', 'short' => 'HKG');
        $code[] = array('county_name_ru' => 'Гренада', 'code' => '1 473', 'extra_short' => 'GD', 'short' => 'GRD');
        $code[] = array('county_name_ru' => 'Гренландия', 'code' => '299', 'extra_short' => 'GL', 'short' => 'GRL');
        $code[] = array('county_name_ru' => 'Греция', 'code' => '30', 'extra_short' => 'GR', 'short' => 'GRC');
        $code[] = array('county_name_ru' => 'Грузия', 'code' => '995', 'extra_short' => 'GE', 'short' => 'GEO');
        $code[] = array('county_name_ru' => 'Гуам', 'code' => '1 671', 'extra_short' => 'GU', 'short' => 'GUM');
        $code[] = array('county_name_ru' => 'Дания', 'code' => '45', 'extra_short' => 'DK', 'short' => 'DNK');
        $code[] = array('county_name_ru' => 'Демократическая Республика Конго', 'code' => '243', 'extra_short' => 'CD', 'short' => 'COD');
//    $code[] = array( 'county_name_ru' =>'Джерси', 'code'=> '', 'extra_short'=>'JE', 'short'=>'JEY');
        $code[] = array('county_name_ru' => 'Джибути', 'code' => '253', 'extra_short' => 'DJ', 'short' => 'DJI');
        $code[] = array('county_name_ru' => 'Доминика', 'code' => '1 767', 'extra_short' => 'DM', 'short' => 'DMA');
        $code[] = array('county_name_ru' => 'Доминиканская Республика', 'code' => '1 809', 'extra_short' => 'DO', 'short' => 'DOM');
        $code[] = array('county_name_ru' => 'Египет', 'code' => '20', 'extra_short' => 'EG', 'short' => 'EGY');
        $code[] = array('county_name_ru' => 'Замбия', 'code' => '260', 'extra_short' => 'ZM', 'short' => 'ZMB');
//    $code[] = array( 'county_name_ru' =>'Западная Сахара', 'code'=> '', 'extra_short'=>'EH', 'short'=>'ESH');
        $code[] = array('county_name_ru' => 'Западный берег', 'code' => '970', 'extra_short' => '', 'short' => '');
        $code[] = array('county_name_ru' => 'Зимбабве', 'code' => '263', 'extra_short' => 'ZW', 'short' => 'ZWE');
        $code[] = array('county_name_ru' => 'Йемен', 'code' => '967', 'extra_short' => 'YE', 'short' => 'YEM');
        $code[] = array('county_name_ru' => 'Израиль', 'code' => '972', 'extra_short' => 'IL', 'short' => 'ISR');
        $code[] = array('county_name_ru' => 'Индия', 'code' => '91', 'extra_short' => 'IN', 'short' => 'IND');
        $code[] = array('county_name_ru' => 'Индонезия', 'code' => '62', 'extra_short' => 'ID', 'short' => 'IDN');
        $code[] = array('county_name_ru' => 'Иордания', 'code' => '962', 'extra_short' => 'JO', 'short' => 'JOR');
        $code[] = array('county_name_ru' => 'Ирак', 'code' => '964', 'extra_short' => 'IQ', 'short' => 'IRQ');
        $code[] = array('county_name_ru' => 'Иран', 'code' => '98', 'extra_short' => 'IR', 'short' => 'IRN');
        $code[] = array('county_name_ru' => 'Ирландия', 'code' => '353', 'extra_short' => 'IE', 'short' => 'IRL');
        $code[] = array('county_name_ru' => 'Исландия', 'code' => '354', 'extra_short' => 'IS', 'short' => 'IS');
        $code[] = array('county_name_ru' => 'Испания', 'code' => '34', 'extra_short' => 'ES', 'short' => 'ESP');
        $code[] = array('county_name_ru' => 'Италия', 'code' => '39', 'extra_short' => 'IT', 'short' => 'ITA');
        $code[] = array('county_name_ru' => 'Кабо-Верде', 'code' => '238', 'extra_short' => 'CV', 'short' => 'CPV');
        $code[] = array('county_name_ru' => 'Казахстан', 'code' => '7', 'extra_short' => 'KZ', 'short' => 'KAZ');
        $code[] = array('county_name_ru' => 'Каймановы о-ва', 'code' => '1 345', 'extra_short' => 'KY', 'short' => 'CYM');
        $code[] = array('county_name_ru' => 'Камбоджа', 'code' => '855', 'extra_short' => 'KH', 'short' => 'KHM');
        $code[] = array('county_name_ru' => 'Камерун', 'code' => '237', 'extra_short' => 'CM', 'short' => 'CMR');
        $code[] = array('county_name_ru' => 'Канада', 'code' => '1', 'extra_short' => 'CA', 'short' => 'CAN');
        $code[] = array('county_name_ru' => 'Катар', 'code' => '974', 'extra_short' => 'QA', 'short' => 'QAT');
        $code[] = array('county_name_ru' => 'Кения', 'code' => '254', 'extra_short' => 'KE', 'short' => 'KEN');
        $code[] = array('county_name_ru' => 'Кипр', 'code' => '357', 'extra_short' => 'CY', 'short' => 'CYP');
        $code[] = array('county_name_ru' => 'Кирибати', 'code' => '686', 'extra_short' => 'KI', 'short' => 'KIR');
        $code[] = array('county_name_ru' => 'Китай', 'code' => '86', 'extra_short' => 'CN', 'short' => 'CHN');
        $code[] = array('county_name_ru' => 'Кокосовые (Килинг) о-ва', 'code' => '61', 'extra_short' => 'CC', 'short' => 'CCK');
        $code[] = array('county_name_ru' => 'Колумбия', 'code' => '57', 'extra_short' => 'CO', 'short' => 'COL');
        $code[] = array('county_name_ru' => 'Коморские о-ва', 'code' => '269', 'extra_short' => 'KM', 'short' => 'COM');
        $code[] = array('county_name_ru' => 'Косово', 'code' => '381', /* 'extra_short'=>'/', */
                        'short'          => '');
        $code[] = array('county_name_ru' => 'Коста-Рика', 'code' => '506', 'extra_short' => 'CR', 'short' => 'CRC');
        $code[] = array('county_name_ru' => 'Кот-д\'Ивуар', 'code' => '225', 'extra_short' => 'CI', 'short' => 'CIV');
        $code[] = array('county_name_ru' => 'Куба', 'code' => '53', 'extra_short' => 'CU', 'short' => 'CUB');
        $code[] = array('county_name_ru' => 'Кувейт', 'code' => '965', 'extra_short' => 'KW', 'short' => 'KWT');
        $code[] = array('county_name_ru' => 'Кыргызстан', 'code' => '996', 'extra_short' => 'KG', 'short' => 'KGZ');
        $code[] = array('county_name_ru' => 'Лаос', 'code' => '856', 'extra_short' => 'LA', 'short' => 'LAO');
        $code[] = array('county_name_ru' => 'Латвия', 'code' => '371', 'extra_short' => 'LV', 'short' => 'LVA');
        $code[] = array('county_name_ru' => 'Лесото', 'code' => '266', 'extra_short' => 'LS', 'short' => 'LSO');
        $code[] = array('county_name_ru' => 'Либерия', 'code' => '231', 'extra_short' => 'LR', 'short' => 'LBR');
        $code[] = array('county_name_ru' => 'Ливан', 'code' => '961', 'extra_short' => 'LB', 'short' => 'LBN');
        $code[] = array('county_name_ru' => 'Ливия', 'code' => '218', 'extra_short' => 'LY', 'short' => 'LBY');
        $code[] = array('county_name_ru' => 'Литва', 'code' => '370', 'extra_short' => 'LT', 'short' => 'LTU');
        $code[] = array('county_name_ru' => 'Лихтенштейн', 'code' => '423', 'extra_short' => 'LI', 'short' => 'LIE');
        $code[] = array('county_name_ru' => 'Люксембург', 'code' => '352', 'extra_short' => 'LU', 'short' => 'LUX');
        $code[] = array('county_name_ru' => 'Маврикий', 'code' => '230', 'extra_short' => 'MU', 'short' => 'MUS');
        $code[] = array('county_name_ru' => 'Мавритания', 'code' => '222', 'extra_short' => 'MR', 'short' => 'MRT');
        $code[] = array('county_name_ru' => 'Мадагаскар', 'code' => '261', 'extra_short' => 'MG', 'short' => 'MDG');
        $code[] = array('county_name_ru' => 'Майотта', 'code' => '262', 'extra_short' => 'YT', 'short' => 'MYT');
        $code[] = array('county_name_ru' => 'Макао', 'code' => '853', 'extra_short' => 'MO', 'short' => 'MAC');
        $code[] = array('county_name_ru' => 'Македония', 'code' => '389', 'extra_short' => 'MK', 'short' => 'MKD');
        $code[] = array('county_name_ru' => 'Малави', 'code' => '265', 'extra_short' => 'MW', 'short' => 'MWI');
        $code[] = array('county_name_ru' => 'Малайзия', 'code' => '60', 'extra_short' => 'MY', 'short' => 'MYS');
        $code[] = array('county_name_ru' => 'Мали', 'code' => '223', 'extra_short' => 'ML', 'short' => 'MLI');
        $code[] = array('county_name_ru' => 'Мальдивские о-ва', 'code' => '960', 'extra_short' => 'MV', 'short' => 'MDV');
        $code[] = array('county_name_ru' => 'Мальта', 'code' => '356', 'extra_short' => 'MT', 'short' => 'MLT');
        $code[] = array('county_name_ru' => 'Марокко', 'code' => '212', 'extra_short' => 'MA', 'short' => 'MAR');
        $code[] = array('county_name_ru' => 'Маршалловы о-ва', 'code' => '692', 'extra_short' => 'MH', 'short' => 'MHL');
        $code[] = array('county_name_ru' => 'Мексика', 'code' => '52', 'extra_short' => 'MX', 'short' => 'MEX');
        $code[] = array('county_name_ru' => 'Микронезия', 'code' => '691', 'extra_short' => 'FM', 'short' => 'FSM');
        $code[] = array('county_name_ru' => 'Мозамбик', 'code' => '258', 'extra_short' => 'MZ', 'short' => 'MOZ');
        $code[] = array('county_name_ru' => 'Молдова', 'code' => '373', 'extra_short' => 'MD', 'short' => 'MDA');
        $code[] = array('county_name_ru' => 'Монако', 'code' => '377', 'extra_short' => 'MC', 'short' => 'MCO');
        $code[] = array('county_name_ru' => 'Монголия', 'code' => '976', 'extra_short' => 'MN', 'short' => 'MNG');
        $code[] = array('county_name_ru' => 'Монтсеррат', 'code' => '1 664', 'extra_short' => 'MS', 'short' => 'MSR');
        $code[] = array('county_name_ru' => 'Намибия', 'code' => '264', 'extra_short' => 'NA', 'short' => 'NAM');
        $code[] = array('county_name_ru' => 'Науру', 'code' => '674', 'extra_short' => 'NR', 'short' => 'NRU');
        $code[] = array('county_name_ru' => 'Непал', 'code' => '977', 'extra_short' => 'NP', 'short' => 'NPL');
        $code[] = array('county_name_ru' => 'Нигер', 'code' => '227', 'extra_short' => 'NE', 'short' => 'NER');
        $code[] = array('county_name_ru' => 'Нигерия', 'code' => '234', 'extra_short' => 'NG', 'short' => 'NGA');
        $code[] = array('county_name_ru' => 'Нидерландские Антильские о-ва', 'code' => '599', 'extra_short' => 'AN', 'short' => 'ANT');
        $code[] = array('county_name_ru' => 'Нидерланды', 'code' => '31', 'extra_short' => 'NL', 'short' => 'NLD');
        $code[] = array('county_name_ru' => 'Никарагуа', 'code' => '505', 'extra_short' => 'NI', 'short' => 'NIC');
        $code[] = array('county_name_ru' => 'Ниуэ', 'code' => '683', 'extra_short' => 'NU', 'short' => 'NIU');
        $code[] = array('county_name_ru' => 'Новая Зеландия', 'code' => '64', 'extra_short' => 'NZ', 'short' => 'NZL');
        $code[] = array('county_name_ru' => 'Новая Каледония', 'code' => '687', 'extra_short' => 'NC', 'short' => 'NCL');
        $code[] = array('county_name_ru' => 'Норвегия', 'code' => '47', 'extra_short' => 'NO', 'short' => 'NOR');
        $code[] = array('county_name_ru' => 'Объединенное Королевство', 'code' => '44', 'extra_short' => 'GB', 'short' => 'GBR');
        $code[] = array('county_name_ru' => 'ОАЭ', 'code' => '971', 'extra_short' => 'AE', 'short' => 'ARE');
        $code[] = array('county_name_ru' => 'Оман', 'code' => '968', 'extra_short' => 'OM', 'short' => 'OMN');
        $code[] = array('county_name_ru' => 'Остров Мэн', 'code' => '44', /* 'extra_short'=>'IM', */
                        'short'          => 'IMN');
        $code[] = array('county_name_ru' => 'Остров Норфолк', 'code' => '672', /* 'extra_short'=>'/', */
                        'short'          => 'NFK');
        $code[] = array('county_name_ru' => 'Остров Рождества', 'code' => '61', 'extra_short' => 'CX', 'short' => 'CXR');
        $code[] = array('county_name_ru' => 'Остров Святой Елены', 'code' => '290', 'extra_short' => 'SH', 'short' => 'SHN');
        $code[] = array('county_name_ru' => 'Острова Кука', 'code' => '682', 'extra_short' => 'CK', 'short' => 'COK');
        $code[] = array('county_name_ru' => 'Острова Питкэрн', 'code' => '870', 'extra_short' => 'PN', 'short' => 'PCN');
        $code[] = array('county_name_ru' => 'Острова Теркс и Кайкос', 'code' => '1 649', 'extra_short' => 'TC', 'short' => 'TCA');
        $code[] = array('county_name_ru' => 'Пакистан', 'code' => '92', 'extra_short' => 'PK', 'short' => 'PAK');
        $code[] = array('county_name_ru' => 'Палау', 'code' => '680', 'extra_short' => 'PW', 'short' => 'PLW');
        $code[] = array('county_name_ru' => 'Панама', 'code' => '507', 'extra_short' => 'PA', 'short' => 'PAN');
        $code[] = array('county_name_ru' => 'Папуа-Новая Гвинея', 'code' => '675', 'extra_short' => 'PG', 'short' => 'PNG');
        $code[] = array('county_name_ru' => 'Парагвай', 'code' => '595', 'extra_short' => 'PY', 'short' => 'PRY');
        $code[] = array('county_name_ru' => 'Перу', 'code' => '51', 'extra_short' => 'PE', 'short' => 'PER');
        $code[] = array('county_name_ru' => 'Польша', 'code' => '48', 'extra_short' => 'PL', 'short' => 'POL');
        $code[] = array('county_name_ru' => 'Португалия', 'code' => '351', 'extra_short' => 'PT', 'short' => 'PRT');
        $code[] = array('county_name_ru' => 'Пуэрто-Рико', 'code' => '1', 'extra_short' => 'PR', 'short' => 'PRI');
        $code[] = array('county_name_ru' => 'Республика Конго', 'code' => '242', 'extra_short' => 'CG', 'short' => 'COG');

        $code[] = array('county_name_ru' => 'Руанда', 'code' => '250', 'extra_short' => 'RW', 'short' => 'RWA');
        $code[] = array('county_name_ru' => 'Румыния', 'code' => '40', 'extra_short' => 'RO', 'short' => 'ROU');
        $code[] = array('county_name_ru' => 'Сальвадор', 'code' => '503', 'extra_short' => 'SV', 'short' => 'SLV');
        $code[] = array('county_name_ru' => 'Самоа', 'code' => '685', 'extra_short' => 'WS', 'short' => 'WSM');
        $code[] = array('county_name_ru' => 'Сан - Марино', 'code' => '378', 'extra_short' => 'SM', 'short' => 'SMR');
        $code[] = array('county_name_ru' => 'Санкт-Бартелеми', 'code' => '590', /* 'extra_short'=>'BL', */
                        'short'          => 'BLM');
        $code[] = array('county_name_ru' => 'Сан-Томе и Принсипи', 'code' => '239', 'extra_short' => 'ST', 'short' => 'STP');
        $code[] = array('county_name_ru' => 'Саудовская Аравия', 'code' => '966', 'extra_short' => 'SA', 'short' => 'SAU');
        $code[] = array('county_name_ru' => 'Свазиленд', 'code' => '268', 'extra_short' => 'SZ', 'short' => 'SWZ');
        $code[] = array('county_name_ru' => 'Святой Престол (Ватикан)', 'code' => '39', 'extra_short' => 'VA', 'short' => 'VAT');
        $code[] = array('county_name_ru' => 'Северная Корея', 'code' => '850', 'extra_short' => 'KP', 'short' => 'PRK');
        $code[] = array('county_name_ru' => 'Северные Марианские о-ва', 'code' => '1 670', 'extra_short' => 'MP', 'short' => 'MNP');
        $code[] = array('county_name_ru' => 'Сейшельские о-ва', 'code' => '248', 'extra_short' => 'SC', 'short' => 'SYC');
        $code[] = array('county_name_ru' => 'Сектор Газа', 'code' => '970', /* 'extra_short'=>'/', */
                        'short'          => '');
        $code[] = array('county_name_ru' => 'Сенегал', 'code' => '221', 'extra_short' => 'SN', 'short' => 'SEN');
        $code[] = array('county_name_ru' => 'Сен-Мартен', 'code' => '1 599', /* 'extra_short'=>'MF', */
                        'short'          => 'MAF');
        $code[] = array('county_name_ru' => 'Сен-Пьер и Микелон', 'code' => '508', 'extra_short' => 'PM', 'short' => 'SPM');
        $code[] = array('county_name_ru' => 'Сент-Винсент и Гренадины', 'code' => '1 784', 'extra_short' => 'VC', 'short' => 'VCT');
        $code[] = array('county_name_ru' => 'Сент-Китс и Невис', 'code' => '1 869', 'extra_short' => 'KN', 'short' => 'KNA');
        $code[] = array('county_name_ru' => 'Сент-Люсия', 'code' => '1 758', 'extra_short' => 'LC', 'short' => 'LCA');
        $code[] = array('county_name_ru' => 'Сербия', 'code' => '381', 'extra_short' => 'RS', 'short' => 'SRB');
        $code[] = array('county_name_ru' => 'Сингапур', 'code' => '65', 'extra_short' => 'SG', 'short' => 'SGP');
        $code[] = array('county_name_ru' => 'Сирия', 'code' => '963', 'extra_short' => 'SY', 'short' => 'SYR');
        $code[] = array('county_name_ru' => 'Словакия', 'code' => '421', 'extra_short' => 'SK', 'short' => 'SVK');
        $code[] = array('county_name_ru' => 'Словения', 'code' => '386', 'extra_short' => 'SI', 'short' => 'SVN');
        $code[] = array('county_name_ru' => 'Соломоновы Острова', 'code' => '677', 'extra_short' => 'SB', 'short' => 'SLB');
        $code[] = array('county_name_ru' => 'Сомали', 'code' => '252', 'extra_short' => 'SO', 'short' => 'SOM');
        $code[] = array('county_name_ru' => 'Судан', 'code' => '249', 'extra_short' => 'SD', 'short' => 'SDN');
        $code[] = array('county_name_ru' => 'Суринам', 'code' => '597', 'extra_short' => 'SR', 'short' => 'SUR');
        $code[] = array('county_name_ru' => 'США', 'code' => '1', 'extra_short' => 'US', 'short' => 'USA');
        $code[] = array('county_name_ru' => 'Сьерра-Леоне', 'code' => '232', 'extra_short' => 'SL', 'short' => 'SLE');
        $code[] = array('county_name_ru' => 'Таджикистан', 'code' => '992', 'extra_short' => 'TJ', 'short' => 'TJK');
        $code[] = array('county_name_ru' => 'Тайвань', 'code' => '886', 'extra_short' => 'TW', 'short' => 'TWN');
        $code[] = array('county_name_ru' => 'Таиланд', 'code' => '66', 'extra_short' => 'TH', 'short' => 'THA');
        $code[] = array('county_name_ru' => 'Танзания', 'code' => '255', 'extra_short' => 'TZ', 'short' => 'TZA');
        $code[] = array('county_name_ru' => 'Тимор-Лешти', 'code' => '670', 'extra_short' => 'TL', 'short' => 'TLS');
        $code[] = array('county_name_ru' => 'Того', 'code' => '228', 'extra_short' => 'TG', 'short' => 'TGO');
        $code[] = array('county_name_ru' => 'Токелау', 'code' => '690', 'extra_short' => 'TK', 'short' => 'TKL');
        $code[] = array('county_name_ru' => 'Тонга', 'code' => '676', 'extra_short' => 'TO', 'short' => 'TON');
        $code[] = array('county_name_ru' => 'Тринидад и Тобаго', 'code' => '1 868', 'extra_short' => 'TT', 'short' => 'TTO');
        $code[] = array('county_name_ru' => 'Тувалу', 'code' => '688', 'extra_short' => 'TV', 'short' => 'TUV');
        $code[] = array('county_name_ru' => 'Тунис', 'code' => '216', 'extra_short' => 'TN', 'short' => 'TUN');
        $code[] = array('county_name_ru' => 'Туркменистан', 'code' => '993', 'extra_short' => 'TM', 'short' => 'TKM');
        $code[] = array('county_name_ru' => 'Турция', 'code' => '90', 'extra_short' => 'TR', 'short' => 'TUR');
        $code[] = array('county_name_ru' => 'Уганда', 'code' => '256', 'extra_short' => 'UG', 'short' => 'UGA');
        $code[] = array('county_name_ru' => 'Узбекистан', 'code' => '998', 'extra_short' => 'UZ', 'short' => 'UZB');
        $code[] = array('county_name_ru' => 'Украина', 'code' => '38', 'extra_short' => 'UA', 'short' => 'UKR');
        $code[] = array('county_name_ru' => 'Уоллис и Футуна', 'code' => '681', 'extra_short' => 'WF', 'short' => 'WLF');
        $code[] = array('county_name_ru' => 'Уругвай', 'code' => '598', 'extra_short' => 'UY', 'short' => 'URY');
        $code[] = array('county_name_ru' => 'Фарерские о-ва', 'code' => '298', 'extra_short' => 'FO', 'short' => 'FRO');
        $code[] = array('county_name_ru' => 'Фиджи', 'code' => '679', 'extra_short' => 'FJ', 'short' => 'FJI');
        $code[] = array('county_name_ru' => 'Филиппины', 'code' => '63', 'extra_short' => 'PH', 'short' => 'PHL');
        $code[] = array('county_name_ru' => 'Финляндия', 'code' => '358', 'extra_short' => 'FI', 'short' => 'FIN');
        $code[] = array('county_name_ru' => 'Фолклендские о-ва', 'code' => '500', 'extra_short' => 'FK', 'short' => 'FLK');
        $code[] = array('county_name_ru' => 'Франция', 'code' => '33', 'extra_short' => 'FR', 'short' => 'FRA');
        $code[] = array('county_name_ru' => 'Французская Полинезия', 'code' => '689', 'extra_short' => 'PF', 'short' => 'PYF');
        $code[] = array('county_name_ru' => 'Хорватия', 'code' => '385', 'extra_short' => 'HR', 'short' => 'HRV');
        $code[] = array('county_name_ru' => 'Центрально-Африканская Республика', 'code' => '236', 'extra_short' => 'CF', 'short' => 'CAF');
        $code[] = array('county_name_ru' => 'Чад', 'code' => '235', 'extra_short' => 'TD', 'short' => 'TCD');
        $code[] = array('county_name_ru' => 'Черногория', 'code' => '382', 'extra_short' => 'ME', 'short' => 'MNE');
        $code[] = array('county_name_ru' => 'Чешская Республика', 'code' => '420', 'extra_short' => 'CZ', 'short' => 'CZE');
        $code[] = array('county_name_ru' => 'Чили', 'code' => '56', 'extra_short' => 'CL', 'short' => 'CHL');
        $code[] = array('county_name_ru' => 'Швейцария', 'code' => '41', 'extra_short' => 'CH', 'short' => 'CHE');
        $code[] = array('county_name_ru' => 'Швеция', 'code' => '46', 'extra_short' => 'SE', 'short' => 'SWE');
//    $code[] = array( 'county_name_ru' =>'Шпицберген', 'code'=> '', 'extra_short'=>'SJ', 'short'=>'SJM');
        $code[] = array('county_name_ru' => 'Шри-Ланка', 'code' => '94', 'extra_short' => 'LK', 'short' => 'LKA');
        $code[] = array('county_name_ru' => 'Эквадор', 'code' => '593', 'extra_short' => 'EC', 'short' => 'ECU');
        $code[] = array('county_name_ru' => 'Экваториальная Гвинея', 'code' => '240', 'extra_short' => 'GQ', 'short' => 'GNQ');
        $code[] = array('county_name_ru' => 'Эритрея', 'code' => '291', 'extra_short' => 'ER', 'short' => 'ERI');
        $code[] = array('county_name_ru' => 'Эстония', 'code' => '372', 'extra_short' => 'EE', 'short' => 'EST');
        $code[] = array('county_name_ru' => 'Эфиопия', 'code' => '251', 'extra_short' => 'ET', 'short' => 'ETH');
        $code[] = array('county_name_ru' => 'Южная Африка', 'code' => '27', 'extra_short' => 'ZA', 'short' => 'ZAF');
        $code[] = array('county_name_ru' => 'Южная Корея', 'code' => '82', 'extra_short' => 'KR', 'short' => 'KOR');
        $code[] = array('county_name_ru' => 'Ямайка', 'code' => '1 876', 'extra_short' => 'JM', 'short' => 'JAM');
        $code[] = array('county_name_ru' => 'Япония', 'code' => '81', 'extra_short' => 'JP', 'short' => 'JPN');


        foreach($code as $country){
            $insert = array("name"=>_e($country['county_name_ru']), "name_len_3"=>$country["extra_short"], "name_len_2"=>$country["short"]);
            $this->db->insert('countries', $insert);

        }



    }

    public function down()
    {
        //$this->dbforge->drop_table('countries');
    }
}