<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions_model extends CI_Model {
    public $tableName = "transactions";

//    const TRANSACTION_TYPE_APPLICATIONS = 10; //для заявок: пополнение, списание при заеме или выдаче
//    const TRANSACTION_TYPE_APPLICATIONS_WRITE_OFF = 11; //для заявок: отчисление
    const TRANSACTION_TYPE_REFS_PARTNER        = 11; //

    const TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_ISSUE        = 12; //для : "Получение кредита на Арбитраж №11577073"
    const TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK         = 13; //для : "Погашение кредита на Арбитраж №1445838"
    const TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK_PERCENT = 14; //для заявок: отчисление "Проценты по кредиту на Арбитраж №xxxxxxx"

    const TRANSACTION_TYPE_INVEST_ARBITRAGE            = 15; //для пополнение с банка для вклада в арбитраж
    const TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME    = 16; //для прибыль от вклада в арбитраж
    const TRANSACTION_TYPE_INVEST_ARBITRAGE_SET_ACTIVE = 17; //для перечисление в фонд арбитража полученых дененг

    const TRANSACTION_TYPE_CERTIFICAT_PAID = 20; //продажа сертификата "Продажа сертификата № ССxxxxxx от dd/mm/yyyyг."

    const TYPE_EXPENSE_INVEST       = 21; //снятие средств по заявке - когда сделали заявку на вклад и она стала активной "Снятие средств по заявке № xxxxxx"
    const TYPE_EXPENSE_INVEST_REPAY = 22; //снятие средств по заявке (выплата по заявке на займ) "Погашение кредита №$own->id" "Снятие средств по заявке (выплата) №%" "Погашение займа №%"
    const TYPE_INCOME_LOAN          = 23; //получение займа "Пополнение средств по заявке № $newID" OR "Зачисление средств по заявке №№ххххххх"
    const TYPE_INCOME_REPAY         = 24; //получение средств по заявке (выплата по инвестиции) "Пополнение средств по заявке (оплата по вкладу) №xxxxx"
    const TYPE_EXPENSE_FEE_REPAY    = 25; //otchislenie v garantiyniy fond "Отчисление в '.$GLOBALS["WHITELABEL_NAME"].' по займу №xxxxxx" или "Отчисление по заявке №116089"
    const TYPE_BONUS_CREDIT_REMOVED = 26; //"Возврат кредитного бонуса"

    const TYPE_PARENT_INCOME    = 30; //портнерская прибыль старшему "Партнерское вознаграждение по вкладу  №xxxxxx от партнера №xxxxxxxx"
    const TYPE_VOLUNTEER_INCOME = 31; //партнерская прибыль деду "Партнерское вознаграждение по вкладу  №xxxxxxx от младшего №xxxxxxxx через партнера №xxxxxxxx"

    const TRANSACTION_TYPE_USER_SEND= 40; //пересылка в фонды или магазины
    const TYPE_EXCHANGE_RETURN      = 41; //обменики возврат

    const TYPE_EXCHANGE_SELL = 51; //продажа сертификата на бирже "Биржа: продажа сертификата № СС$i->id от ".date('d/m/Yг.')." пользователю $cur_id_user"
    const TYPE_EXCHANGE_BUY  = 52; //покупка сертификата на бирже "Биржа: покупка сертификата № СС$i->id от ".date('d/m/Yг.')." у пользователя $i->id_user"
    const TYPE_EXCHANGE_CREDS_SELL = 53; //продажа сертификата на бирже _CREDS
    const TYPE_EXCHANGE_CREDS_BUY  = 54; //покупка сертификата на бирже _CREDS

    const TYPE_EXPENSE_SMS       = 61; //плата за смс "Оплата за СМС верификацию"
    const TYPE_EXPENSE_FINE      = 62; //овердрафт штраф "Штраф за не выполнения условий овердрафта"
    const TYPE_EXPENSE_OUTFEE    = 63; //комисия за вывод "Комисия за вывод $ xxx (заявка №xxxxxxxx)" или "Комиссия за вывод средств "
    const TYPE_EXPENSE_OVERDRAFT = 64; //"Отчисление за не выполнения условий Овердрафта по заявке №xxxxxxxx"
    const TYPE_EXPENSE_MERCHANT  = 65; //"Снятие комиссии по транзакции №xxxxxxxx"
    const TYPE_EXPENSE_INFEE    = 66; //комисия за ввод
    const TYPE_EXPENSE_P2P_FEE    = 67; //комисия за вывод Р2Р

//    const TYPE_PAYOUT           = 70;         //заявки на вывод сейчас это 310,311,....
    const TYPE_PAYOUT_APPLIED   = 71;         //заявки на вывод в раб области
    const TYPE_PAYOUT_PAYED     = 72;         //заявки на вывод в истории
    const TYPE_PAYOUT_VERIFYED  = 73;         //заявки на вывод провереная для автовыплат

    const TYPE_SEND_MONEY         = 74;         //перевод пользователю "Зачисление средств от пользователя №xxxxxxxx" "Отправка средств пользователю №xxxxxxxx: note"
    const TYPE_SEND_MONEY_CONFERM = 75;         //перевод пользователю не подтвержденый если есть код "Отправка средств пользователю №xxxxxxxx: note"
    const TYPE_SEND_MONEY_P2P     = 76;         // P2P Webtransfer перревод денег.

    const TYPE_PARTNER_INCOME_BONUS = 80;       //оплата доп вознаграждения если vip user или старше "Партнерское вознаграждение по вкладу бонус за ".date("m.Y")
    const TYPE_PARTNER_EXCHANGE_BONUS = 81;       //оплата доп вознаграждения если vip user или старше "Партнерское вознаграждение по вкладу бонус за ".date("m.Y")

    const TYPE_BONUS_FOR_NEW_USER       = 90; //"Бонус за привлечение нового пользователя №xxxxxxxxx"
    const TYPE_BONUS_FOR_NEW_SUBUSER    = 91; //"Бонусное вознаграждение за младшего реферала №xxxxxxxxx от пользователя №xxxxxxxxx"
    const TYPE_BONUS_FOR_REGESTRATIONS  = 92; //"Бонус за регистрацию"
    const TYPE_BONUS_CREDIT             = 93; //"Кредитный бонус"
    const TYPE_BONUS_SKYPASSER          = 94; //"Пополнение бонусных средств от skypasser"
    const TYPE_BONUS_VOTE               = 95; //"Бонус за голос пользователя №$me
    const TYPE_BONUS_VIDEO              = 96; //"Бонус за добавления видео"
    const TYPE_BONUS_PAYMENT            = 97; //'Бонус за пополнение средств'
    const TYPE_BONUS_FIRST_CREDIT       = 98; //'Бонус за пополнение средств'
    const TYPE_PARTNER_TRANSFER         = 99; //'Снятие партнерских средств'

    const TYPE_ARCHIVE                  = 1000;
    const TYPE_BLOCKED_SUM_FOR_INVEST   = 2001;

    const VALUE_ARCHIVE_SUM             = 1000;
    const VALUE_ARCHIVE_SUM_BONUS       = 9000;
    const VALUE_ARCHIVE_SEND_MONEY      = 40;
    const VALUE_ARCHIVE_PARENT_INCOME   = 30;
    const VALUE_ARCHIVE_FEE_OR_FINE     = 60;

//    const BONUS_PAYMENT_ACCOUNT_CREDS = 2;
//    const BONUS_P_CREDS = 3;
//    const BONUS_C_CREDS = 4;
// <editor-fold defaultstate="collapsed" desc="$_tplWork">

    private $_tplWork =
            '<w:tr w:rsidR="00D878CF" w:rsidTr="003F0FBA">
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1680" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{id}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2001" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF" w:rsidP="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{where}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2499" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{email}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1595" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{summa}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1796" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF"/>
                </w:tc>
            </w:tr>';
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="$_tplPayout">

    private $_tplPayout =
            '<w:tr w:rsidR="00D878CF" w:rsidTr="003F0FBA">
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1680" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{id}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2001" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF" w:rsidP="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{where}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="2499" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{email}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1595" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF">
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{summa}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr>
                        <w:tcW w:w="1796" w:type="dxa"/>
                    </w:tcPr>
                    <w:p w:rsidR="00D878CF" w:rsidRDefault="00D878CF"/>
                        <w:r w:rsidRPr="00D878CF">
                            <w:t>{coments}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>';
// </editor-fold>

    public function __construct() {
        parent::__construct();
        $this->load->model("users_model", 'users');
        //$this->lang->load('transactions_lang', 'transactions_lang');
    }

    function get_currency_exchange_transactions( $user_id, $orig_order_id, $arch_order_id, $statuses = NULL )
    {
        if( empty( $user_id ) || empty( $orig_order_id ) || empty( $arch_order_id ) )
            return FALSE;

        if( !empty( $statuses ) ) $trans = $this->db->where_in('status',$statuses);
        else if( $statuses === NULL )
            $trans = $this->db->where_in('status',array(1,3));

        $trans = $this->db->where('id_user',$user_id)
                          ->where('note LIKE', '%P2P%')
                          ->where_in('value', array( $orig_order_id, $arch_order_id ))
                            ->where_in('type',array( self::TYPE_EXPENSE_P2P_FEE, self::TYPE_SEND_MONEY_P2P, self::TYPE_EXPENSE_OUTFEE ))
                            ->get($this->tableName)
                            ->result();

        if( empty( $trans ) )
            return FALSE;

        return $trans;
    }

    public function getPartnerNewIncome($id_user, $start = null, $end = null, $bonus = NULL) {
        $start = (null == $start) ? date("Y-m-d", strtotime("first day of previous month")) : $start;
        $end = (null == $end) ? date("Y-m-d", strtotime("last day of previous month")) : $end;
        if ( !empty($bonus)) $this->db->where('bonus', $bonus);
        return $this->db
            ->select("SUM(summa) sum")
            ->where('value >', config_item('partner-old50-last-credit'))
            ->where('date >=', $start." 00:00:00")
            ->where('date <=', $end." 23:59:59")
            ->where(array("id_user" => $id_user, "status" => Base_model::TRANSACTION_STATUS_RECEIVED, "type" => self::TYPE_PARENT_INCOME))
            ->get($this->tableName)
            ->row("sum");
    }

    public function getPartnerOldIncome($id_user, $start = null, $end = null, $bonus = NULL) {
        $start = (null == $start) ? date("Y-m-d", strtotime("first day of previous month")) : $start;
        $end = (null == $end) ? date("Y-m-d", strtotime("last day of previous month")) : $end;
        if ( !empty($bonus)) $this->db->where('bonus', $bonus);
        return $this->db
            ->select("SUM(summa) sum")
            ->where('value <=', config_item('partner-old50-last-credit'))
            ->where('date >=', $start." 00:00:00")
            ->where('date <=', $end." 23:59:59")
            ->where(array("id_user" => $id_user, "status" => Base_model::TRANSACTION_STATUS_RECEIVED, "type" => self::TYPE_PARENT_INCOME))
            ->get($this->tableName)
            ->row("sum");
    }

    public function getTransaction($trans_id){
        return $this->db->get_where($this->tableName, array("id" => $trans_id))->row();

    }

    public function getTransactionByValue($value, $where=[]){
        if (!empty($where))$this->db->where($where);
        return $this->db->get_where($this->tableName, array("value" => $value))->row();

    }


    public function saveTransaction($trans) {
        $this->db->update($this->tableName, $trans, "id = $trans->id", 1);
    }

    public function countBankPayment() {
        $cur_id_user = $this->users->getCurrUserId();
        $a =  $this->db
            ->where("(id_user = $cur_id_user OR value = $cur_id_user)")
            ->where("(metod = 'bank' OR metod = 'bank_norvik' OR metod = 'bank_raiffeisen')")
            ->where(array(
                'status' => Base_model::TRANSACTION_STATUS_NOT_RECEIVED
            ))
            ->count_all_results($this->tableName);
         return $a;
    }

    public function confermArbitrageInvest($trans, $active = TRUE) {
        if(!is_object($trans))
            $trans = $this->getTransaction($trans);
        // активируем вклад арбитража
        $this->load->model("credits_model","credits");
        $vklad_id = $trans->value;
        $this->credits->activateInvestArbitrage($vklad_id);
        // завершаем зачисление
        if($active){
            $trans->status = Base_model::TRANSACTION_STATUS_RECEIVED;
            $this->transactions->saveTransaction($trans);
        }
        // переводим в фонд арбитража
        $this->transactions->addPay(
            $trans->id_user,
            $trans->summa,
            Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_SET_ACTIVE,
            $vklad_id,
            'wt',
            Base_model::TRANSACTION_STATUS_REMOVED,
            $trans->bonus,
            "Перечисление средств в фонд арбитража. Заявка №$vklad_id"
            );
    }

    public function createPartnerInvest($summa, $id_user, $id_partner_arbitr) {
        $this->transactions->addPay($id_user, $summa, Transactions_model::TYPE_PARTNER_TRANSFER, $id_partner_arbitr, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_PARTNER, "Снятие средств для Арбитражного вклада № $id_partner_arbitr");
    }

    public function bayCertificat($i, $newID, $bonus = Base_model::TRANSACTION_BONUS_OFF) {
        $cur_id_user = $this->users->getCurrUserId();
        $this->addPay($i->id_user, $i->summ_exchange, Transactions_model::TYPE_EXCHANGE_SELL, $i->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, ($i->bonus==0)?5:$i->bonus, "Биржа: продажа сертификата № СС$i->id от ".date('d/m/Yг.')." пользователю $cur_id_user");
        $this->addPay($cur_id_user, $i->summ_exchange, Transactions_model::TYPE_EXCHANGE_BUY, $newID, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $bonus, "Биржа: покупка сертификата № СС$i->id от ".date('d/m/Yг.')." у пользователя $i->id_user");
    }

    public function bayCREDSCertificat($i, $newID, $bonus = Base_model::TRANSACTION_BONUS_OFF) {
        $cur_id_user = $this->users->getCurrUserId();
        $this->addPay($i->id_user, $i->summ_exchange, Transactions_model::TYPE_EXCHANGE_CREDS_SELL, $i->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $bonus, "Биржа: продажа CREDS № СС$i->id от ".date('d/m/Yг.')." пользователю $cur_id_user");
        $this->addPay($cur_id_user, $i->summ_exchange, Transactions_model::TYPE_EXCHANGE_CREDS_BUY, $newID, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $bonus, "Биржа: покупка CREDS № СС$i->id от ".date('d/m/Yг.')." у пользователя $i->id_user");
    }

    public function paySmsCoust() {
        $this->addPay($this->users->getCurrUserId(), 0.05, Transactions_model::TYPE_EXPENSE_SMS, 0, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, "Оплата за СМС верификацию");
    }

    public function getDouble($id_user) {
        $res = $this->db->select("id,note,summa,value, COUNT(*) c")
            ->where(array(
                "id_user" => $id_user,
            //    'status' => Base_model::TRANSACTION_STATUS_RECEIVED,
                  'status <' => '2',
            //    'metod' => 'wt',
                'summa >' => 0
            ))
            ->where("(type = ".Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID
                . " OR type = ".Transactions_model::TYPE_INCOME_REPAY
                . " OR type = ".Transactions_model::TYPE_EXCHANGE_SELL
                . " OR type = ".Transactions_model::TYPE_PARENT_INCOME
                . " OR type = ".Transactions_model::TYPE_VOLUNTEER_INCOME
                . ")")
            ->group_by("value")
            ->having("c > 1")
            ->get($this->tableName)
            ->result();
        $res_21 =
                $this->db->select("id,note,summa,value, COUNT(*) c")
            ->where(array(
                "id_user" => $id_user,
            //    'status' => Base_model::TRANSACTION_STATUS_RECEIVED,
                  'status' => '3',
            //    'metod' => 'wt',
                'summa >' => 0
            ))
            ->where("type = 21")
            ->group_by("value")
            ->having("c > 1")
            ->get($this->tableName)
            ->row();

        $full_res = [];
        foreach ( $res as $r )
            if ( $r->c !=  $res_21->c)
                $full_res[] = $r;

        return $full_res;
    }

    public function getDoubleArhive($id_user) {
        return $this->db->select("id,note,summa, COUNT(*) c")
            ->where(array(
                "id_user" => $id_user,
             //  'status' => Base_model::TRANSACTION_STATUS_RECEIVED,
                'status <' => '2',
            //    'metod' => 'wt',
                'summa >' => 0
            ))
            ->where("(type = ".Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID
                . " OR type = ".Transactions_model::TYPE_INCOME_REPAY
                . " OR type = ".Transactions_model::TYPE_EXCHANGE_SELL
                . " OR type = ".Transactions_model::TYPE_PARENT_INCOME
                . " OR type = ".Transactions_model::TYPE_VOLUNTEER_INCOME
                . ")")
            ->group_by("value")
            ->having("c > 1")
            ->get("archive_transactions")
            ->result();
    }

    public function getInOut4MerchantUsers($user_id = null) {
        $user_id = (null != $user_id) ? $user_id : $this->users->getCurrUserId();

        $t = $this->db->select('SUM(t.summa) AS s, t.`status`, tt.id_user')
            ->join('transactions AS tt', 'tt.id = t.value', 'inner')
            ->where("(`t`.`type` = ".self::TYPE_SEND_MONEY." OR `t`.`type` = ".self::TRANSACTION_TYPE_USER_SEND.")")
            ->where("(`tt`.`type` = ".self::TYPE_SEND_MONEY." OR `tt`.`type` = ".self::TRANSACTION_TYPE_USER_SEND.")")
            ->where(['t.id_user' => $user_id, 't.bonus' => 6])
            ->where("`tt`.`date` > '2016-02-16 00:00:00'")
            ->where("((`t`.`status` = ".Base_model::TRANSACTION_STATUS_RECEIVED." AND `tt`.`date` < '".date('Y-m-d 23:59:59', strtotime("-10 days"))."') OR `t`.`status` = ".Base_model::TRANSACTION_STATUS_REMOVED.")")
            ->group_by("tt.id_user")
            ->group_by("t.status")
            ->get($this->tableName." as t")
            ->result();
//if(40148209 == $user_id){ print_r($this->db->last_query());die();}
        $res = [];
        foreach ($t as $row)
            $res[$row->id_user][$row->status] = $row->s;

        return $res;
    }

    public function isCanPay2MerchantBayBonusProgram($sum, $merchant_user_id, $user_id = null) {
        $user_id = (null != $user_id) ? $user_id : $this->users->getCurrUserId();
        $merchants_data = $this->getInOut4MerchantUsers($user_id);
        $res = (isset($merchants_data[$merchant_user_id][1]) ? $merchants_data[$merchant_user_id][1]: 0)*config_item('MerchantBayBonusProgram') - (isset($merchants_data[$merchant_user_id][3]) ? $merchants_data[$merchant_user_id][3]: 0);
        return ($res >= $sum) ? true : false;
    }

    public function oldAddPay($summa, $metod, $status = Base_model::TRANSACTION_STATUS_NOT_RECEIVED, $note = '', $id_user = false, $bonus = Base_model::TRANSACTION_BONUS_OFF) {
        $id_user = (false == $id_user) ? $this->users->getCurrUserId() : $id_user;
        $summa = changeComa($summa);
        if (0 > $summa)
            throw new Exception("сумма при платеже отрицательна ($summa)");

        $data = array(
            'id_user' => $id_user,
            'user_ip' => $this->users->getIpUser(),
            'summa' => $summa,
            'metod' => $metod,
            'status' => $status,
            'bonus' => $bonus,
            'note' => $note
        );

        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
    }

    public function setValue($trans_id, $val) {
        $this->db->update($this->tableName, array('value' => $val), array('id' => $trans_id), 1);
    }

    public function setStatus($trans_id, $val) {
        $this->db->update($this->tableName, array('status' => $val), array('id' => $trans_id), 1);
    }

    public function setType($trans_id, $val) {
        $this->db->update($this->tableName, array('type' => $val), array('id' => $trans_id), 1);
    }

    public function addPay($id_user, $summa, $type, $value, $method, $status, $bonus = Base_model::TRANSACTION_BONUS_OFF, $note = '', $date = null, $note_admin = '') {

        //$note = 'Выдача займа №';
        //$note = 'Возврат займа №';
        //$note = 'Отчисление в '.$GLOBALS["WHITELABEL_NAME"].' по займу №';
        //$note = 'Бонус за регистрацию';
        //$note = 'Выдача кредита на Арбитраж';
        //$note = 'Возврат кредита на Арбитраж';
        //$note = 'Отчисление в '.$GLOBALS["WHITELABEL_NAME"].' за кредит на Арбитраж';
//        $note_admin = '';
        if ( $type == self::TYPE_SEND_MONEY  && preg_match('/от пользователя №99676729/i', $note) && $method == 'wt' && $status == Base_model::TRANSACTION_STATUS_RECEIVED)
               $method = 'wtadmin';
        if ($type == self::TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_ISSUE) {
            if ($status == Base_model::TRANSACTION_STATUS_RECEIVED) {
                $note = 'Получение кредита на Арбитраж №';
                $method = 'arbitr';
            } else
            if ($status == Base_model::TRANSACTION_STATUS_REMOVED) {
                $note = 'Выдача кредита на Арбитраж №';
                $method = 'wt';
            }

            $note .= $value;
            //$bonus = Base_model::TRANSACTION_BONUS_OFF;
        }
        if ($type == self::TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK) {
            if ($status == Base_model::TRANSACTION_STATUS_RECEIVED) {
//                if( $bonus == Base_model::TRANSACTION_BONUS_CREDS_CASH ) $note = 'Возврат co счета C-CREDS кредита на Арбитраж №';
//                else
                    $note = 'Возврат кредита на Арбитраж №';
                $method = 'wt';
            } else
            if ($status == Base_model::TRANSACTION_STATUS_REMOVED) {
//                if( $bonus == Base_model::TRANSACTION_BONUS_CREDS_CASH ) $note = 'Погашение co счета C-CREDS  кредита на Арбитраж №';
//                else
                    $note = 'Погашение кредита на Арбитраж №';
                $method = 'arbitr';
            }

            $note .= $value;
            //$bonus = Base_model::TRANSACTION_BONUS_OFF;
        }
        if ($type == self::TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK_PERCENT) {
            $status = Base_model::TRANSACTION_STATUS_REMOVED;
            $note = 'Проценты по кредиту на Арбитраж №';
            $method = 'wt';

            $note .= $value;
            //$bonus = Base_model::TRANSACTION_BONUS_OFF;
        }

        //
        if ( ($type == self::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME && $status == 1) || ($type  == self::TYPE_EXPENSE_FEE_REPAY &&  $status == 3) ){
            $trans21 = $this->db->where('value', $value)->where('type',21)->get('transactions')->row();
            if ( !empty( $trans21) && $trans21->bonus == 1)
                $note_admin = 'bonus';
        }


        if( $type == NULL ) $type = 0;
        $this->load->model("users_model", 'users');

        $correct_summa = changeComa($summa);
        $data = array(
            'id_user' => (int)$id_user,
            'user_ip' => $this->users->getIpUser(),
            'master' => $GLOBALS["WHITELABEL_ID"],
            'summa' => $correct_summa,
            'metod' => $method,
            'status' => (int)$status,
            'type' => (int)$type,
            'value' => (int)$value,
            'bonus' => (int)$bonus,
            'note' => $note,
            'note_admin' => $note_admin
        );
        if ($date != null)
            $data['date'] = $date;

        $this->db->insert('transactions', $data);
        return $this->db->insert_id();
    }

    #принимает займ пользователя, не БОТА 400400
    public function addArbitrationContributions($invest) {

        // отчисление webfin
        $summa = round(($invest->out_summ - $invest->summa) * 100) / 100;

        $res_id = $this->addPay($invest->id_user, $summa, self::TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK_PERCENT, $invest->id, null, Base_model::TRANSACTION_STATUS_REMOVED, $invest->bonus==0?2:$invest->bonus);

        // Запись в базу отчетов
//        $this->base_model->addToWFReport($invest, $summa, $webfin);
        return $res_id;
    }

    public function updateWhereIn( $sets, $colName, $where ) {
        $this->db->where_in( $colName, $where )->update('transactions',$sets);
    }
    public function setError4ContributionPayout($id) {
        $this->db->where(['type' => self::TYPE_EXPENSE_OUTFEE, 'value' => $id])->limit(1)->update($this->tableName, array('status' => Base_model::TRANSACTION_STATUS_DELETED));
    }
    public function getPayedAll($cols, $table, $join = null) {
        $search = $_POST["search"];
        //$filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($search)
            $this->_createSearch($search, $cols, $table);

        $this->db->select("COUNT(*) as count");

        if ($join != null && is_array($join) && isset($join['table']) && isset($join['on']) && isset($join['type'])
        )
            $this->db->join($join['table'], $join['on'], $join['type']);

        $r["count"] = $this->db
                        ->get($table)
                        ->row()
                ->count;

        if ($order)
            $this->_createOrder($order);
        if ($search)
            $this->_createSearch($search, $cols, $table);

        $select = array();
        $select[] = $table . ".*";
        if ($join != null && is_array($join) && isset($join['select']) && isset($join['table'])) {
            if (is_array($join['select'])) {
                foreach ($join['select'] as $key)
                    $select[] = "{$join['table']}.{$key}";
            } else {
                $select[] = "{$join['table']}.{$join['select']}";
            }
        }
        $this->db->select($select)->limit($per_page, $offset);


        if ($join != null && is_array($join) && isset($join['table']) && isset($join['on']) && isset($join['type'])
        )
            $this->db->join($join['table'], $join['on'], $join['type']);

        $r["rows"] = $this->db->get($table)->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }

    public function getSummForSysPayout($sys) {
        return $this->db
                        ->select("SUM(summa) s")
                        ->where("value", $sys)
                        ->where(array('t.status' => Base_model::TRANSACTION_STATUS_VEVERIFYED, 't.type' => self::TYPE_PAYOUT_VERIFYED) )
                        ->get($this->tableName.' t')
                        ->row("s");
    }

    public function autoCheckPayout($param) {
        $res = '';
        $start = time();
        $sys_sum = $sys_limit = array_flip($param['sys_payout']);
        foreach ($sys_limit as $key => &$val){
            $val = $param['p_'.$key];
            $sys_sum[$key] = 0;
        }

        $limit = $param['limit_trans'];
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->model('transactions_model', 'transactions');
       switch ($param['type']) {
           case 'send_money':$this->db->where("metod = 'wt' AND type =  '74'"); break;
           case 'wtcard':    $this->db->where("metod = 'out' AND type = 328 AND value = 328"); break;
           case 'verify_ss': $this->db->where(['status' => Base_model::TRANSACTION_STATUS_VEVERIFY_SS]); break;
           case 'out':       $this->db->where(["t.metod" => "out", "t.value <>" => "327", "t.value <>" => "328"]); break;

           default: die('ERROR - Type incorect'); break;
       }
       if('verify_ss' != $param['type'])
           $this->db->where(["t.status" => Base_model::TRANSACTION_STATUS_IN_PROCESS]);

        $this->db->where(["t.bonus" => $param['bonus']]);
        $payout = $this->db
            ->where([
                "t.date >" => $param['date_start'],
                "t.date <" => $param['date_end'],
                "t.summa <=" => $param['summ_max']
            ])
            ->limit($limit)
            ->get("transactions t")
            ->result();

        //$res .= $this->db->last_query();
        $res .= "Выбрали ".count($payout)." заявок<br/>";
        $users = [];
        $c = 0;
        foreach ($payout as $data) {
            $c++;
            $id_user = $data->id_user;
            $summa = $data->summa;
            $id = $data->id;
            $admin_note_old = $data->note_admin;
            if(isset($users[$id_user]) && (false === $users[$id_user] || (!is_bool($users[$id_user]) && 0 > $users[$id_user]-$summa) )){
                unset($data);
                $data = [];
                if(false === strpos($param['mess'], $admin_note_old)){
                    $data['note_admin'] = $admin_note_old." другая заявка выбела ошибку:".$param['mess'];
                    $this->db->where("id", $id)->update("transactions", $data);
                }
                continue;
            }

            $payoutsys = $param['sys_payout'];
            list($can_out,$bal,$error) = $this->checkuser($param, $data);

            $in_bank = $this->transactions->getAllInMoneyOfUserByType($id_user, 'bank')
                      +$this->transactions->getAllInMoneyOfUserByType($id_user, 'bank_norvik')
                      +$this->transactions->getAllInMoneyOfUserByType($id_user, 'bank_raiffeisen');
            if(0 < $in_bank){
                $can_out = false;
                $error = "Пользователь имеет ввод по Банку::";
            }

            $his_money =
                ($bal['money_sum_add_funds'] + $bal['money_diff_transfer_exch'])*$param['coef_pribil']
                + ($param['send_money'] ? $bal['money_sum_transfer_from_users_clear'] : 0)
                + ($param['partner_money'] ? $bal['sum_partner_reword'] : 0)
                - $bal['money_sum_transfer_to_users_clear']
                - $bal['money_sum_withdrawal'];
            $can_money = $his_money;
            $out = $bal['money_sum_process_withdrawal'];
            $free_m = $bal['payout_limit'];
            $sobstv_sredstva = $bal['net_funds'];
            $withdrawn = $bal['money_sum_withdrawal'];
            $cred_limit = $bal['max_loan_available'];
            if(!isset($users[$id_user])){
                if($his_money >= $out && 2 == $param['payout_limit']) //провека чтоб одну транзакцию можно было выплатить даже если у чела на все не хватает
                    $users[$id_user] = true;
                elseif ($his_money < $out && 2 == $param['payout_limit'])
                    $users[$id_user] = $his_money;
                elseif(1 == $param['payout_limit'])
                    $users[$id_user] = false;
            }
            if($can_out && $can_money >= $summa) {
                $old_value = $data->value;

                $res .= "$c; u=$id_user; s=$summa; m=".round($free_m,2)."; w=".round($withdrawn,2)."; cm=".round($sobstv_sredstva,2)."; o=$out; al=".(($can_money >= $out) ? 1 : 0)."; $old_value -> ";
                unset($data);
                $data = [];
                if(false === strpos($param['mess'], $admin_note_old)){
                    $data['note_admin'] = $admin_note_old." проверено автоматом OK:".$param['mess'];
                    $this->db->where("id", $id)->update("transactions", $data);
                }
                unset($data);
                $data = [];
                $data["status"] = Base_model::TRANSACTION_STATUS_VEVERIFYED;
                $data['type'] = Transactions_model::TYPE_PAYOUT_VERIFYED;

                if(!in_array($old_value, $payoutsys))
                    $old_value = $param['def_payout'];
                $data['value'] = $old_value;

                if($param['payout']){
                    if($sys_sum[$data['value']]+$summa <= $sys_limit[$data['value']]){
                        $this->db->where("id", $id)->update("transactions", $data);
                        $sys_sum[$data['value']] += $summa;
                        $res .= "{$data['value']}; OK"."<br/>";
                        if(!is_bool($users[$id_user])) $users[$id_user] -= $summa;
                    } else if($sys_sum[$param['def_payout']]+$summa <= $sys_limit[$param['def_payout']]){
                        $data['value'] = $param['def_payout'];
                        $this->db->where("id", $id)->update("transactions", $data);
                        $sys_sum[$data['value']] += $summa;
                        $res .= "{$data['value']}; TRANSFER DEF"."<br/>";
                        if(!is_bool($users[$id_user])) $users[$id_user] -= $summa;
                    } else
                        $res .= "$old_value; SKIP"."<br/>";
                } else
                    $res .= "$old_value; SKIP"."<br/>";

            } else if('' != $error){
                $users[$id_user] = false;
                $res .= "$c; u=$id_user; s=$summa; o=$out; al=0; ERROR: $error"."<br/>";
                unset($data);
                $data = [];
                if(false === strpos($error, $admin_note_old)){
                    $data['note_admin'] = $admin_note_old." ".$error.":".$param['mess'];

                    //$this->db->where("id", $id)->update("transactions", $data);
                }
            } else {
                unset($data);
                $data = [];
                if(false === strpos($param['mess'], $admin_note_old)){
                    $data['note_admin'] = $admin_note_old." false:".$param['mess'];

                    //$this->db->where("id", $id)->update("transactions", $data);
                }
            }
        }
        $res .= "Закончили ".(time() - $start)."с"."<br/>";
        return $res;
    }



	public function getCheckPayout($table, $cols) {
		ini_set('max_execution_time', 900);
		$params['date_start']    = date("Y-m-d 00:00:00");
		$params['date_end']      = date("Y-m-d 23:59:59");
		$params['summ_max']      = 200;
		$params['coef_pribil']   = 1;
		$params['send_money']    = 1;
		$params['partner_money'] = 0;
		$params['doc_summ'] = 0;
		if(empty($_POST)) {
			$params['date_start']    = date("Y-m-d 00:00:00");
			$params['date_end']      = date("Y-m-d 23:59:59");
			$params['summ_max']      = 200;
			$params['coef_pribil']   = 1;
			$params['send_money']    = 1;
			$params['partner_money'] = 0;
		}
		$param = array_merge($params, $_POST);


		$res = '';
		$start = time();
		$this->load->model('accaunt_model', 'accaunt');

		$search = /*$_POST["search"]*/ false;
		$filter = $_POST["filter"];
		$per_page = (int) $_POST["per_page"];
		$offset = (int) $_POST["offset"];
		$order = $_POST["order"];
		$r = array("count" => "", "rows" => array(), "sql" => "");
		if ($order)
			$this->_createOrder($order);
		if ($filter)
			$this->_createFilter($filter, "");
		if ($search)
			$this->_createSearch($search, $cols, $table);


		$payout = $this->db
			->where(array(
				"status" => Base_model::TRANSACTION_STATUS_IN_PROCESS,
				"metod" => "out",
				"date >" => $param['date_start'],
				"date <" => $param['date_end'],
				"summa <=" => $param['summ_max'] ))->limit(50)
			->get("transactions")
			->result();
		$users = [];
		$c = 0;
		foreach ($payout as $data) {
			$c++;
			$id_user = $data->id_user;
			$summa = $data->summa;
			if(isset($users[$id_user]) && (false === $users[$id_user] || (!is_bool($users[$id_user]) && 0 > $users[$id_user]-$summa) ))
				continue;

			$id = $data->id;
			$admin_note_old = $data->note_admin;
			list($can_out,$bal,$error) = $this->checkuser($param, $data);

			$his_money =
				$bal['money_sum_add_funds']*$param['coef_pribil']
				+ ($param['send_money'] ? $bal['money_sum_transfer_from_users'] : 0)
				+ ($param['partner_money'] ? $bal['sum_partner_reword'] : 0)
				- $bal['money_sum_transfer_to_users']
				- $bal['money_sum_withdrawal'];
			$can_money = $his_money;
			$out = $bal['money_sum_process_withdrawal'];
			$free_m = $bal['payout_limit'];
			$sobstv_sredstva = $bal['net_funds'];
			$withdrawn = $bal['money_sum_withdrawal'];
			$cred_limit = $bal['max_loan_available'];
			$out = [];
			if($can_out && $can_money >= $summa) {
//				$old_value = $data->value;
				$out[]= $data;
//				$res .= "$c; u=$id_user; s=$summa; m=".round($free_m,2)."; w=".round($withdrawn,2)."; cm=".round($sobstv_sredstva,2)."; o=$out; al=".(($can_money >= $out) ? 1 : 0)."; $old_value -> ";

			}
		}

		$r["rows"] = array_slice($out, $offset, $per_page);//echo $this->db->last_query();die;

		if("users" == $table)
			$r["rows"] = $this->code->db_decode($r["rows"]);

		return json_encode($r);
	}

	public function checkuser($param, $data) {
        $can_out = false;
        $error = '';
        $send_transaction = new stdClass;
        $send_transaction->summa   = $data->summa;
        $send_transaction->own     = $data->id_user;
        $send_transaction->id_user = $data->id_user;
        $send_transaction->note    = $data->note;
        $bal = $this->accaunt->recalculateUserRating( $data->id_user );
        $this->load->model('documents_model', 'documents');
        $userDocumentStatus = $this->documents->getUserDocumentStatus( $data->id_user );
        if ($bal) {
            if (!$this->accaunt->isUserAccountVerified()){
                $can_out = false;
                $error = "Не верифицирован::";
            } else if ( $bal['overdue_standart_count'] > 0){
                $can_out = false;
                $error = "Просроченный КС::";
            } else if ( $bal['overdue_garant_count'] > 0){
                $can_out = false;
                $error = "Просроченный КГ::";
            } else if($param['doc_summ'] <= $data->summa  && Documents_model::STATUS_PROVED != $userDocumentStatus){
                $can_out = false;
                $error = "Нет Док::";
            } else if(true !== ($canSend = $this->accaunt->isCanSendMoney($send_transaction, true))){
                $can_out = false;
                $error = "$canSend::";
            } else
                $can_out = true;
        }
        return [$can_out, $bal, $error];
    }

    public function autoPayoutOne($trId, $fromPayout = FALSE,  $force  = FALSE ) {//return false;
        $this->load->model("transactions_model","transactions");
        $this->load->model('accaunt_model', 'accaunt');

        $this->load->library('code');
        $this->load->library('payout');




        $data = $this->db
                        ->select("count(t.id) count, t.id, t.id_user, t.summa, t.type, t.bonus, t.value, t.note, t.note_admin, u.state, u.email, u.phone, u.phone_verification, u.bank_lava payeer, u.bank_okpay okpay, u.bank_perfectmoney perfectmoney")
                        ->join('users u', 'u.id_user = t.id_user', 'inner')
                        ->where("t.id", $trId)
                        ->get($this->tableName.' t')
                        ->row();


        dev_log("autoPayoutOne for tr=$trId bonus={$data->bonus}");

        if(!in_array($data->type, config_item("auto_payout_systems_type_by_one")))
            return false;

        if (isset($data->email)) $data->email = $this->code->decrypt($data->email);
        else $data->email = '-';
        if (isset($data->phone)) $data->phone = $this->code->decrypt($data->phone);

        // всякие проверки
        if ( $data->bonus == 6){
            //- Maximalniy vivod cheloveku $1,000 v den'
            $full_summ_today = $this->db->select('sum(summa) as sm')->where_in('status',[3,4,9])->where(['id_user'=>$data->id_user,'metod'=>'out', 'date >='=>date('Y-m-d 00:00:00')])->get('transactions')->row('sm');
            $full_summ_today = (empty($full_summ_today)?0:$full_summ_today);
	    if ( $full_summ_today > 1000 ){
                   $this->db->update($this->tableName, array('note_admin' => $data->note_admin.' Вывод больше 1000 в день'."($full_summ_today)"), array('id' => $data->id));
                    return FALSE;
            }

            //- Maximumalnoye kolichestvo vivoda na kartu - 2 shtuki v den
            if ( $t->type == 328){
                $cnt_cards = $this->db->select('count(*) as cnt')->where(['id_user'=>$data->id_user,'metod'=>'out', 'type'=>328, 'status'=>3, 'date >='=>date('Y-m-d 00:00:00')])->get('transactions')->row('cnt');
                if ( $cnt_cards >= 2 ){
                    $this->db->update($this->tableName, array('note_admin' => $data->note_admin.' Вывод 2 выводов на карту'), array('id' => $data->id));
                    return FALSE;
                }
            }


           $rate = $this->accaunt->recalculateUserRating($data->id_user );
            //Sistema doljna menyat status zayavki na proverka SB (status = 10) esli po kakimto perimetram ne prohodit, a imenno:
            //- Odin iz schetov polzovatelya v minuse
            if ( $rate['payment_account_by_bonus'][1]< 0  || $rate['payment_account_by_bonus'][2]< 0 || $rate['payment_account_by_bonus'][3]< 0 || $rate['payment_account_by_bonus'][4]< 0 ||
                 $rate['payment_account_by_bonus'][5]< 0 || $rate['payment_account_by_bonus'][6]< 0){
                $this->db->update('transactions',['status'=>10], ['id'=>$trId]);
                $this->db->update($this->tableName, array('note_admin' => $data->note_admin.' Один из счетов в минусе'), array('id' => $data->id));
                return FALSE;
            }

            //- Est produblirovannie tranzakzii
            if($this->transactions->getDouble($trId) || $this->transactions->getDoubleArhive($trId)){
                $this->db->update('transactions',['status'=>10], ['id'=>$trId]);
                $this->db->update($this->tableName, array('note_admin' => $data->note_admin.' Есть дубликаты транзакции'), array('id' => $data->id));
                return FALSE;
            }

            //- Esli status 1 type 84 bolshe $500
            if ( $rate['transfered_summ_from_bonus_2_to_6'] - $rate['money_own_from_2_to_6'] > 500  ){
                $this->db->update($this->tableName, array('note_admin' => $data->note_admin.' тип 84 стутус 1 больше 500'), array('id' => $data->id));
                $this->db->update('transactions',['status'=>10], ['id'=>$trId]);
                return FALSE;
            }

        }
        $this->load->model("users_model","users");
        $wallets = $this->users->get_ewallet_data($data->id_user);
        $data->payeer = $wallets->payeer;
        $data->okpay = $wallets->okpay;
        $data->perfectmoney = $wallets->perfectmoney;

        list($can_out,$bal,$error) = $this->checkuser(['doc_summ' => 500], $data);

        $summa = $data->summa;
        $id_user = $data->id_user;
        $id = $data->id;
        $admin_note_old = $data->note_admin;
        $his_money = $bal['money_sum_add_funds_by_bonus'][$data->bonus]*1
      //  + $bal['money_sum_transfer_from_users_by_bonus'][$data->bonus]
        - $bal['money_sum_transfer_to_users_by_bonus'][$data->bonus]
        - $bal['money_sum_withdrawal_by_bonus'][$data->bonus];

        if ( $data->bonus == 6 ){
            $lrate = (object)$this->transactions->get_liq_rating($id_user);
            $his_money += $lrate->net_inn;
        }
        dev_log("autoPayoutOne for tr=$trId: prestart");
        if(($can_out && $his_money >= $summa) || $force ) {

            $psnts = 0;

            $data->commission = (float) $psnts; //будет или нет комисия определяется внутри автооплаты в зависимости от метода
            $data->commissionSumm = $data->summa;
            $data->psnts = payuot_systems_psnt();
            $note = $data->note;

            $data->force_commission  = NULL;
            $all_matches = [];
            if(preg_match_all("/\(комисия .+ (.*)\)/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                $data->force_commission = (float)($all_matches[1][0]);

            $data->force_bonus  = 0;
            if(preg_match_all("/\(бонус .+ (.*)\)/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                $data->force_bonus = (float)($all_matches[1][0]);

            $card_id =  null;
            if (preg_match_all("/CARDID: (\d*);/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                $card_id = $all_matches[1][0];
            $data->card_id = $card_id;

            if ( $data->bonus == 9 ){
                $data->comment = "Selling Webtransfer GIT Card #".$data->value;
            }

            $data->note = preg_replace(array('/Заявка на вывод средств: /', '/\(комисия.*$/'), array('',''), $data->note);
            $data->summa = floor($data->summa * 100) / 100;
            $money = $this->base_model->getRealMoney($data->id_user, $data->bonus);
            if($data->summa <= $money || $force){

                dev_log("autoPayoutOne for tr=$trId: pay start");
                $row = $this->payout->pay($data);
                $row['bonus'] = $data->bonus;
                if ( $row['res'] == 'ok' ){
                    // бонус за пост на стену фейсбука и вконтакта
                    $post_vk_bonus = $post_fb_bonus = 0;
                    if (preg_match_all("/ Bonus .(\d*) for vk post/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                        $post_vk_bonus = (int)$all_matches[1][0];
                    if (preg_match_all("/ Bonus .(\d*) for fb post/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                        $post_fb_bonus = (int)$all_matches[1][0];
                    if ( $post_vk_bonus + $post_fb_bonus > 0 )
                        $trId = $this->addPay($data->id_user, ($post_vk_bonus + $post_fb_bonus), 92, 92, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, 'Партнерский Бонус');

                    $this->_save2Db($row);
                    return TRUE;
                }

            } else
                $row = ['res' => "error", "note" => $data->note_admin." не достаточно средств на счету;", 'note_user' => $data->note, 'id' => $data->id, 'item' => $data];

            $this->_save2Db($row);

        } else if('' != $error){
            unset($data);
            $data = [];
            if(false === strpos($error, $admin_note_old)){
                $data['note_admin'] = $admin_note_old." ".$error;
                $this->db->where("id", $id)->update("transactions", $data);
            }
            return FALSE;
        } else if($his_money < $summa){
            $error = 'не хват. собственных средств::';
            unset($data);
            $data = [];
            if(false === strpos($error, $admin_note_old)){
                $data['note_admin'] = $admin_note_old." ".$error;
                $this->db->where("id", $id)->update("transactions", $data);
            }
            return FALSE;
        }
        return FALSE;
    }

    public function autoPayout($sys) {
        $this->load->model('accaunt_model', 'accaunt');
        $this->load->library('code');
        $this->load->library('payout');

        if('all' != $sys)
            $this->db->where('t.value', $sys);

        $dataCollection = $this->db
                        ->select("count(t.id) count, t.id, t.id_user, t.summa, t.type, t.bonus, t.value, t.note, t.note_admin, u.state, u.email, u.phone, u.phone_verification, u.bank_lava payeer, u.bank_okpay okpay, u.bank_perfectmoney perfectmoney")
                        ->join('users u', 'u.id_user = t.id_user', 'inner')
                        //->join('cards k', 'k.user_id = t.id_user', 'left')
                        ->where(array('t.status' => Base_model::TRANSACTION_STATUS_VEVERIFYED, 't.type' => self::TYPE_PAYOUT_VERIFYED) )
//                        ->where('t.value <>', 318)
                        ->limit(50)
                        ->group_by("t.id")
                        ->get($this->tableName.' t')
                        ->result();

        if( empty($dataCollection) ){
            return 1;
        }
        $newContent = '';
        if(config_item("Payout_log"))
            $this->base_model->log2file(PHP_EOL.count($dataCollection), "auto_payout_transactions");
        foreach ($dataCollection as $data) {
            if( preg_match('/^Комисия за вывод/', $data->note) > 0 )  continue;

            if (isset($data->email)) $data->email = $this->code->decrypt($data->email);
            else $data->email = '-';
            if (isset($data->phone)) $data->phone = $this->code->decrypt($data->phone);

            $this->load->model("users_model","users");
            $wallets = $this->users->get_ewallet_data($data->id_user);
            $data->payeer = $wallets->payeer;
            $data->okpay = $wallets->okpay;
            $data->perfectmoney = $wallets->perfectmoney;

            $psnts = 0;
//            if (stripos($data->note, " (комисия $ "))
//                $psnts = substr($data->note, (stripos($data->note, " (комисия $ ") + 19));

            $data->commission = (float) $psnts; //будет или нет комисия определяется внутри автооплаты в зависимости от метода
            $data->commissionSumm = $data->summa;
            $data->psnts = payuot_systems_psnt();

            $card_id =  null;
            if (preg_match_all("/CARDID: (\d*);/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                $card_id = $all_matches[1][0];
            $data->card_id = $card_id;



            $data->force_bonus  = 0;
            if(preg_match_all("/\(бонус .+ (.*)\)/", $item->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                $data->force_bonus = (float)($all_matches[1][0]);

            $data->note = preg_replace(array('/Заявка на вывод средств: /', '/\(комисия.*$/'), array('',''), $data->note);
            $data->summa = floor($data->summa * 100) / 100;
            $money = $this->base_model->getRealMoney($data->id_user);
            if(config_item("Payout_log"))
                $this->base_model->log2file("$data->count, $data->id, $data->id_user, $money, $data->summa, $data->type, $data->value, $data->state, $data->note, $data->note_admin, $data->email, $data->payeer, $data->okpay, $data->perfectmoney", "auto_payout_transactions");
            if($data->summa <= $money)
                $row = $this->payout->pay($data);
                $row['bonus'] = $data->bonus;
                if ( $row['res'] == 'ok' ){
                    // бонус за пост на стену фейсбука и вконтакта
                    $post_vk_bonus = $post_fb_bonus = 0;
                    if (preg_match_all("/ Bonus .(\d*) for vk post/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                        $post_vk_bonus = (int)$all_matches[1][0];
                    if (preg_match_all("/ Bonus .(\d*) for fb post/", $data->note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0]) )
                        $post_fb_bonus = (int)$all_matches[1][0];
                    if ( $post_vk_bonus + $post_fb_bonus > 0 )
                        $trId = $this->addPay($data->id_user, ($post_vk_bonus + $post_fb_bonus), 92, 92, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, 'Партнерский Бонус');
                }

            else
                $row = ['res' => "error", "note" => $data->note_admin." не достаточно средств на счету;", 'note_user' => $data->note, 'id' => $data->id, 'item' => $data];

            $newContent .= $this->_save2Db($row);
        }
        if(config_item("Payout_log"))
            $this->base_model->log2file("end".count($dataCollection).PHP_EOL, "auto_payout_transactions");

        $res = $this->createDocx($newContent, '_payoutAutomat');
        if(0 != $res) return $res;
        return 0;
    }

    private function _save2Db($row){
        if("ok" == $row["res"]){
            $this->db->update($this->tableName, array('type' => self::TYPE_PAYOUT_PAYED, 'status' => Base_model::TRANSACTION_STATUS_REMOVED, 'note_admin' => $row["note"], 'note' => $row["note_user"]), array('id' => $row['id']));

            if(0 < $row['item']->commission) //начисление коммисии
            $this->addPay($row['item']->id_user, $row['item']->commission, Transactions_model::TYPE_EXPENSE_OUTFEE, $row['id'], 'out', Base_model::TRANSACTION_STATUS_REMOVED, $row['item']->bonus , "Комисия за вывод $".$row['item']->commissionSumm." (заявка №{$row['id']})");
        } else if('exwt' == $row["res"]){
            $this->db->update($this->tableName, array('type' => self::TYPE_PAYOUT_PAYED, 'note_admin' => $row["note"], 'note' => $row["note_user"]), array('id' => $row['id']));
        } else
            $this->db->update($this->tableName, array('note_admin' => $row["note"]), array('id' => $row['id']));

        return preg_replace(array('/{id}/', '/{email}/', '/{where}/', '/{summa}/', '/{coments}/'),
                                        array($row['id']." ({$row['item']->id_user})", $row['item']->email, $row["item"]->note, $row["item"]->summa, "res={$row['res']} ({$row['note']})"), $this->_tplPayout);
    }

    public function savePayoutToWord() {

        $this->load->model('accaunt_model', 'accaunt');
        $this->load->library('code');

        $dataCollection = $this->db
                        ->select("t.id, t.id_user, t.summa, t.type, t.value, t.note, u.email")
                        ->join('users u', 't.id_user = u.id_user', 'inner')
                        ->where(array('t.status' => Base_model::TRANSACTION_STATUS_REMOVED, 't.type' => self::TYPE_PAYOUT_APPLIED) )
                        ->get($this->tableName.' t')
                        ->result();

        if( empty($dataCollection) ){
            return 1;
        }
        $lineTpl = $this->_tplWork;

        $newContent = '';
        $updateRows = array();
        foreach ($dataCollection as $data) {
            if( preg_match('/^Комисия за вывод/', $data->note) > 0 )  continue;

            if (isset($data->email))
                $data->email = $this->code->decrypt($data->email);
            else
                $data->email = '-';
            $updateRows[] = $data->id;
            $data->note = preg_replace(array('/Заявка на вывод средств: /', '/\(комисия.*$/'), array('',''), $data->note);
            $data->summa = floor($data->summa * 100) / 100;
            $newContent .= preg_replace(array('/{id}/', '/{email}/', '/{where}/', '/{summa}/'),
                                        array($data->id_user, $data->email, $data->note, $data->summa), $lineTpl);
        }
        if( empty($updateRows) ){
            return 2;
        }

        $res = $this->createDocx($newContent);
        if(0 != $res) return $res;

        $this->updateWhereIn( array('type' => self::TYPE_PAYOUT_PAYED),'id', $updateRows );

        return 0;
    }

    public function createDocx($newContent, $addonName = '') {
        $fileToModify = 'word/document.xml';
        $fileName = date('Y-m-d_H-i-s', time()) . "$addonName.docx";
        $newFile = 'upload/payout_files/' . $fileName;
        $tplFile = 'upload/payout_files/tpl.zip';

        copy($tplFile, $newFile);

        $zip = new ZipArchive;
        if ($zip->open($newFile) === FALSE)
            return 3;

        //Read contents into memory
        $oldContents = $zip->getFromName($fileToModify);

        if (!empty($oldContents)) {
            //Modify contents:
            $newContents = str_replace('<--replace-->', $newContent, $oldContents);

            //Delete the old...
            $zip->deleteName($fileToModify);
            //var_dump($newContents);
            //Write the new...
            $zip->addFromString($fileToModify, $newContents);
        } else {
            return 4;
        }
        //And write back to the filesystem.
        $zip->close();
        return 0;
    }
    public function getPayout($cols, $table, $status = Base_model::TRANSACTION_STATUS_IN_PROCESS, $type = self::TYPE_PAYOUT_APPLIED) {

        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($search)
            $this->_createSearch($search, $cols);
        if ($filter)
            $this->_createFilter($filter, "");

        $r["count"] = $this->db
                        ->select("COUNT(*) as count")
                        ->where(array("status" => $status, "metod" => "out", 'type' => $type))
                        ->get($table)
                        ->row()
                        ->count;

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "");
        if ($search)
            $this->_createSearch($search, $cols, $table);

        $r["rows"] = $this->db
                ->select("*")
                ->where(array("status" => $status, "metod" => "out", 'type' => $type))
                ->limit($per_page, $offset)
                ->get($table)
                ->result(); //echo $this->db->last_query();die;

        if ("users" == $table)
            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }
    public function getSendMoney($cols, $table) {

        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if(isset($_SESSION["sendmoney_count"]) && isset($_SESSION["sendmoney_count_time"]) && $_SESSION["sendmoney_count_time"] > (time() - 24*60*60) && !$search && !$filter)
            $r["count"] = $_SESSION["sendmoney_count"];
        else {
            if ($search)
                $this->_createSearch($search, $cols);
            if ($filter)
                $this->_createFilter($filter, "");

            $r["count"] = $this->db
                        ->select("COUNT(*) as count")
                        ->where("metod like 'wt' and type = '74' and status = '4'")
                        ->get($table)
                        ->row()
                        ->count;
            $_SESSION["sendmoney_count"] = $r["count"];
            if ($search || $filter) $_SESSION["sendmoney_count_time"] = 0;
            else $_SESSION["sendmoney_count_time"] = time();
        }

        if ($order)
            $this->_createOrder($order);
        if ($filter)
            $this->_createFilter($filter, "");
        if ($search)
            $this->_createSearch($search, $cols, $table);

        $r["rows"] = $this->db
                ->select("*")
                ->where("metod like 'wt' and type =  '74' and status = '4'")
                ->limit($per_page, $offset)
                ->get($table)
                ->result(); //echo $this->db->last_query();die;
//
//        if ("users" == $table)
//            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }

    private function _createOrder($order) {
        foreach ($order as $key => $value)
            $this->db->order_by($key, $value);
    }

    private function _createFilter($order, $t = '') {
        foreach ($order as $key => $value){
            if(empty($value)) continue;

            if ("fio" == $key) {
                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            }
            else $this->db->where("$t$key LIKE", "%$value%");
        }
    }

    private function _createSearch($search, $cols) {
        $search = explode(" ", $search);
        foreach ($search as $word) {
            $wordEncript = $this->code->code($word);
            $word = preg_match("/[а-я]/i", $word) ? "text" : $word;

            $where = "(";
            foreach ($cols as $col => $type) {
                if ("encript" == $type)
                    $where .= "t.$col = '$wordEncript' OR ";
                else
                    $where .= "t.$col LIKE '%$word%' OR ";
            }

            $where = substr($where, 0, strlen($where) - 4);
            $where .= ")";

            $this->db->where($where);
        }
    }
    public function getVerifySS($cols, $table) {

        $search = /*$_POST["search"]*/ false;
        $filter = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset = (int) $_POST["offset"];
        $order = $_POST["order"];
        $r = array("count" => "", "rows" => array(), "sql" => "");

        if ($search)
            $this->_createSearch($search, $cols);
        if ($filter)
                $this->_createFilter($filter);

        $r["count"] = $this->db
                        ->select("COUNT(*) as count")
                        ->where(array('status' => Base_model::TRANSACTION_STATUS_VEVERIFY_SS))
                        ->get($table)
                        ->row()
                        ->count;

        if ($order)
             $this->_createOrder($order);
        if ($search)
            $this->_createSearch($search, $cols);
        if ($filter)
                $this->_createFilter($filter);

        $r["rows"] = $this->db
                ->select("*")
                ->where(array('status' => Base_model::TRANSACTION_STATUS_VEVERIFY_SS))
                ->limit($per_page, $offset)
                ->get($table)
                ->result(); //echo $this->db->last_query();die;
//
//        if ("users" == $table)
//            $r["rows"] = $this->code->db_decode($r["rows"]);

        return json_encode($r);
    }

    public function getMonthWithdrawalAmount( $user_id ) {
        return $this->getMonthOutWithdrawalAmount( $user_id ) + $this->getMonthSendMoneyWithdrawalAmount( $user_id );
    }

    public function getMonthOutWithdrawalAmount( $user_id ) {

        if( empty( $user_id ) ) return FALSE;

        $last_month_date = (string)date('Y-m-01 00:00:00');

        $withdrawal = $this->db
                            ->select('sum( summa ) as sum')
                            ->where(
                                    array(
                                        "status" => Base_model::TRANSACTION_STATUS_REMOVED,
                                        "metod" => "out",
                                        'id_user' => $user_id,
                                        "date >= " => $last_month_date
                                    )
                            )
                            ->get('transactions')
                            ->row('sum');

        if( empty( $withdrawal ) ) return 0;

        return $withdrawal;
    }




    /**
     * Получаем все выведенные средства за день
     * $date - строка в формате YYYY-MM-DD
     * @param type $user_id
     * @param string $date
     * @return int|boolean
     */
    public function getDayOutWithdrawalAmount( $user_id, $date = NULL ) {

        if( empty( $user_id ) ) return FALSE;


        if ( $date !== NULL ){
            $start_date = $date.' 00:00:00';
            $end_date = $date.' 23:59:59';
        } else
            $start_date = date('Y-m-d 00:00:00');

        $withdrawal = $this->db
                            ->select('sum( summa ) as sum')
                            ->where(
                                    array(
                                        "status" => Base_model::TRANSACTION_STATUS_REMOVED,
                                        "metod" => "out",
                                        'id_user' => $user_id,
                                        "date >= " => (string)$start_date
                                    )
                            );
        if ( $date !== NULL )
            $withdrawal = $withdrawal->where('date <=', (string)$end_date);
        $withdrawal = $withdrawal->get('transactions')
                            ->row('sum');
        if( empty( $withdrawal ) ) return 0;

        return $withdrawal;
    }

    # месячное списание по магазину
    public function getMonthSendMoneyWithdrawalAmount( $user_id ) {

        if( empty( $user_id ) ) return FALSE;

        $last_month_date = date('Y-m-01 00:00:00');

        $withdrawal_send_money = $this->db
                            ->select('sum( summa ) as sum')
                            ->where( "( type = " . self::TYPE_SEND_MONEY . " OR type = " . self::TYPE_SEND_MONEY_CONFERM . ") AND ".
                                      "status = " . Base_model::TRANSACTION_STATUS_REMOVED .' AND ' .
                                      'id_user = '.$user_id .' AND '.
                                      "date >= '" . $last_month_date."'"
                            )
                            ->get('transactions')
                            ->row('sum');




        if( empty( $withdrawal ) ) return 0;

        return $withdrawal;
    }


	public function getAllOutMoneyOfUserByType($id_user, $type, $payeer=true) {
		if($payeer){
			$this->db->not_like('note_admin', 'payeer');
		}
		return $this->db
			->select("sum(summa) s")
			->where("id_user = '$id_user' AND (metod = 'out') AND status = ".Base_model::TRANSACTION_STATUS_REMOVED)
			->like('note', $type)
			->get($this->tableName)
			->row("s");
	}

	public function getAllInMoneyOfUserByType($id_user, $type) {
		return $this->db
			->select("sum(summa) s")
			->where("id_user = '$id_user' AND (metod = '$type') AND status = ".Base_model::TRANSACTION_STATUS_RECEIVED)
			->get($this->tableName)
			->row("s");
	}

	public function getAllInMoneyOfUserByTypePayeer($id_user) {
		$this->db->like('note', '(2609)');
		return $this->getAllInMoneyOfUserByType($id_user, 'payeer');
	}

    public function getAllInMoneyOfUser($id_user, $bonus = NULL, $pay_sys = NULL, $types = NULL, $date_from = NULL) {
        if ( $pay_sys == NULL ) $pay_sys = getPaymentSys();
        if ($bonus !== NULL) $this->db->where('bonus', $bonus);
        if ($types !== NULL) $this->db->where_in('type', $types);
        if ($date_from !== NULL) $this->db->where('date >', $date_from);
        return $this->db
            ->select("sum(summa) s")
            ->where("id_user = '$id_user' AND (metod = '".implode("' OR metod = '", $pay_sys)."') AND status = 1")
            ->get($this->tableName)
            ->row("s");
    }

    public function getAllInMoneyOfUser_new($id_user) {
        $pay_sys = getPaymentSys();
        return $this->db
            ->select("sum(summa) s")
            ->where("id_user = '$id_user' AND (metod = '".implode("' OR metod = '", $pay_sys)."') AND date > '2015-07-25' AND status =1")
            ->get($this->tableName)
            ->row("s");
    }

    public function getAllOutMoneyOfUser_new($id_user) {
        return $this->db
            ->select("sum(summa) s")
            ->where("id_user = '$id_user' AND date > '2015-07-25' AND metod = 'out' AND status = ".Base_model::TRANSACTION_STATUS_REMOVED)
            ->get($this->tableName)
            ->row("s");
    }

    public function getAllInMoneyOfUsers($id_users) {
        $pay_sys = getPaymentSys();
        $r = $this->db
            ->select("id_user, sum(summa) s")
            ->where_in("id_user", $id_users)
            ->where("(metod = '".implode("' OR metod = '", $pay_sys)."') AND status =1")
            ->group_by("id_user")
            ->get($this->tableName)
            ->result();
        $res = array();
        foreach ($r as $row)
            $res[$row->id_user] = $row->s;

        return $res;
    }

    public function getAllOutMoneyOfUser($id_user) {
        return $this->db
            ->select("sum(summa) s")
            ->where("id_user = '$id_user' AND metod = 'out' AND status = ".Base_model::TRANSACTION_STATUS_REMOVED)
            ->get($this->tableName)
            ->row("s");
    }

    public function getAllSendMoneyOfUser($id_user) {
        return $this->db
            ->select("sum(summa) s")
            ->where("id_user = '$id_user' AND metod = 'wt' AND status = ".Base_model::TRANSACTION_STATUS_RECEIVED." AND (type = ".self::TYPE_SEND_MONEY." OR type = ".self::TYPE_SEND_MONEY_CONFERM.")")
            ->get($this->tableName)
            ->row("s");
    }


    public function getAllExchMoneyOfUser_new($id_user) {
        $excange_users = getExchangeUsers();
        $r = $this->db
            ->select("sum(t.summa) s")
            ->join("transactions tt", "tt.value = t.id")
            ->where('t.id_user', $id_user )
            ->where('t.metod', 'wt' )
            ->where('t.status', Base_model::TRANSACTION_STATUS_RECEIVED )
            ->where('t.date >', '2015-07-25' )
            ->where("(t.type = ".self::TYPE_SEND_MONEY." OR t.type = ".self::TYPE_SEND_MONEY_CONFERM.") AND tt.id_user in (".implode(',', $excange_users).")")
            ->get("$this->tableName t")
            ->row("s");
        return $r;
    }
    public function getAllExchOutMoneyOfUser_new($id_user) {
        $excange_users = getExchangeUsers();
         $r =  $this->db
            ->select("sum(t.summa) s")
            ->join("transactions tt", "tt.value = t.id")
            ->where('t.id_user', $id_user )
            ->where('t.metod', 'wt' )
            ->where('t.status', Base_model::TRANSACTION_STATUS_REMOVED )
            ->where('t.date >', '2015-07-25' )
            ->where("(t.type = ".self::TYPE_SEND_MONEY." OR t.type = ".self::TYPE_SEND_MONEY_CONFERM.") AND tt.id_user in (".implode(',', $excange_users).")")
            ->get("$this->tableName t")
            ->row("s");
         return $r;
    }

    public function getAllOutSendMoneyOfUser_new($id_user) {
        return $this->db
            ->select("sum(t.summa) s")
          //  ->join("transactions tt", "tt.value = t.id")
            ->where("id_user = '$id_user' AND metod = 'wt' AND status = ".Base_model::TRANSACTION_STATUS_REMOVED." AND (type = ".self::TYPE_SEND_MONEY." OR type = ".self::TYPE_SEND_MONEY_CONFERM.") AND date > '2015-07-25'")
            ->get("$this->tableName t")
            ->row("s");
    }
    public function getAllInSendMoneyOfUser_new($id_user) {
        return $this->db
            ->select("sum(t.summa) s")
          //  ->join("transactions tt", "tt.value = t.id")
            ->where("id_user = '$id_user' AND metod = 'wt' AND status = ".Base_model::TRANSACTION_STATUS_RECEIVED." AND (type = ".self::TYPE_SEND_MONEY." OR type = ".self::TYPE_SEND_MONEY_CONFERM.") AND date > '2015-07-25'")
            ->get("$this->tableName t")
            ->row("s");
    }

    public function getSumm_new($id_user) {

        $statuses = [
            Base_model::TRANSACTION_STATUS_IN_PROCESS,
            Base_model::TRANSACTION_STATUS_VEVERIFYED,
            Base_model::TRANSACTION_STATUS_VEVERIFY_SS,
        ];
        $out_request_summ = $this->db
            ->select("sum(t.summa) s")
          //  ->join("transactions tt", "tt.value = t.id")
            ->where("id_user = '$id_user' AND metod = 'out' AND status IN (".implode(',',$statuses).") AND date > '2015-07-25'")
            ->get("$this->tableName t")
            ->row("s");


        return $this->getAllInMoneyOfUser_new($id_user)
                + $this->getAllInSendMoneyOfUser_new($id_user)
                + $this->getAllExchMoneyOfUser_new($id_user)
                - $this->getAllOutMoneyOfUser_new($id_user)
                - $this->getAllOutSendMoneyOfUser_new($id_user)
                - $out_request_summ;
    }


    public function isPayoutTransactionExistsByIpId( $user_ids_ips )
    {
        if( empty( $user_ids_ips ) )
        {
            return array();
        }
        $search = array();
        foreach( $user_ids_ips as $ip => $id )
        {
            $search[] = "( user_ip LIKE '%$ip%' AND id_user = $id )";
        }

        $transactions = $this->db
                            ->select("id_user, user_ip, note")
                            ->where( '('.implode(' OR ', $search).')' )
                            ->where(array(
                                            "status" => Base_model::TRANSACTION_STATUS_RECEIVED,
                                            "metod" => "wt",
                                            "bonus" => Base_model::TRANSACTION_BONUS_OFF,
                                        )
                                    )
                            ->where("note LIKE 'Зачисление средств от пользователя%'")
                            ->get($this->tableName)
                            ->result();

        if( empty($transactions) ) return array();

        $responce = array();
        foreach( $transactions as $val )
        {
            if( $val->user_ip == 0 || $val->user_ip == null )                continue;

            $ips = explode(',', $val->user_ip);
            $ip = $ips[0];

            if( !isset( $ip ) || !isset( $user_ids_ips[ $ip ] ) )                continue;

            $responce[ $ip ] = $val->id_user;
        }

        return $responce;
    }

    /**
     * If user did payouts
     *
     * @param type $user_id
     * @return boolean
     */
    public function hasWithdrawals( $user_id )
    {
        if( empty($user_id) ) return FALSE;

        $result = $this->db
                        ->select('id')
                        ->limit(1)
                        ->get_where('transactions',
                                    array( 'id_user' => $user_id,
                                        'metod' => 'out',
                                        'status' => Base_model::TRANSACTION_STATUS_REMOVED )
                                    )
                        ->row();

        if( empty($result) || !is_object( $result ) ) return FALSE;

        return TRUE;
    }


    public function hasDeposits( $user_id )
    {
        if( empty($user_id) ) return FALSE;

        $result = $this->db
                        ->select('id')
                        ->limit(1)
                ->where( 'metod !=', 'out' )
                ->where( 'metod !=', 'arbitr' )
                ->where( 'metod !=', 'wt' )
                ->where( array( 'id_user' => $user_id,
                                'status' => Base_model::TRANSACTION_STATUS_RECEIVED ) )
                        ->get('transactions')
                        ->row();
//        echo $this->db->last_query();
//                var_dump($result);

        if( empty($result) || !is_object( $result ) ) return FALSE;

        return TRUE;
    }
    public function getSumDeposits( $user_id )
    {
        if( empty($user_id) ) return FALSE;

        $result = $this->db
                        ->select('SUM(summa) AS summa')
                        ->where( 'metod !=', 'out' )
                        ->where( 'metod !=', 'arbitr' )
                        ->where( 'metod !=', 'wt' )
                        ->where( array( 'id_user' => $user_id,
                                        'status' => Base_model::TRANSACTION_STATUS_RECEIVED ) )
                                ->get('transactions')
                                ->row();
//        echo $this->db->last_query();
//                var_dump($result);

//        var_dump( $result );
        if( empty($result) || !is_object( $result ) || $result->summa === NULL ) return FALSE;


        return $result->summa;
    }

    public function getSumInvest( $user_id )
    {
        if( empty($user_id) ) return FALSE;

        $result = $this->db
                        ->select('SUM(summa) AS summa')
                        ->where( 'metod =', 'wt' )
                        ->where( array( 'id_user' => $user_id,
                                        'status' => Base_model::TRANSACTION_STATUS_RECEIVED,
                                        'type' => self::TYPE_INCOME_REPAY ) )
                        ->get('transactions')
                        ->row();
        $result_arh = $this->db
                        ->select('SUM(summa) AS summa')
                        ->where( 'metod =', 'wt' )
                        ->where( array( 'id_user' => $user_id,
                                        'status' => Base_model::TRANSACTION_STATUS_RECEIVED,
                                        'type' => self::TYPE_INCOME_REPAY ) )
                        ->get('archive_transactions')
                        ->row();
        if(empty($result) || !is_object( $result ) || $result->summa === NULL)
            $result = $result_arh;
//        echo $this->db->last_query();
//                var_dump($result);

//        var_dump( $result );
        if( empty($result) || !is_object( $result ) || $result->summa === NULL ) return FALSE;


        return $result->summa;
    }

    /**
     * If user did transfers to other users
     *
     * @param type $user_id
     * @return boolean
     */
    public function hasTransfers( $user_id )
    {
        if( empty($user_id) ) return FALSE;

        $result = $this->db
                ->select('id')
                ->limit(1)
                ->where("((note LIKE 'Зачисление средств от пользователя%' AND status = ".Base_model::TRANSACTION_STATUS_RECEIVED .') OR ('.
                        "note LIKE 'Снятие средств для отправки пользователю%' AND status = ".Base_model::TRANSACTION_STATUS_REMOVED .'))')
                ->where( array( 'id_user' => $user_id,
                                    'metod' => 'wt') )
                ->get('transactions')
                ->row();
        if( empty($result) || !is_object( $result ) ) return FALSE;

        return TRUE;
    }

    public function getAllUserIps($id_user) {
        if(empty($id_user)) return false;
        $q =   "SELECT mu.ip_reg ips, CONCAT_WS('',mu.id_user,' (',mu.parent,')') note, GROUP_CONCAT(DISTINCT CASE when u.id_user IS NULL then '-' else u.id_user end) id_user, count(DISTINCT u.id_user) `count`, 'пользователь' info
                FROM users mu
                left join users u on u.ip_reg = mu.ip_reg AND u.id_user <> '$id_user'
                WHERE mu.id_user = '$id_user' AND mu.ip_reg IS NOT NULL  AND mu.ip_reg <> ''
                GROUP BY ips
                UNION
                SELECT case when 0 < locate(',',t.user_ip) then left(t.user_ip,locate(',',t.user_ip)-1) else t.user_ip end ips, t.note,  GROUP_CONCAT(DISTINCT CASE when u.id_user IS NULL then '-' else u.id_user end) id_user, count(DISTINCT u.id_user) `count`, 'транзакция' info
                FROM transactions t
                left join users u on u.ip_reg = case when 0 < locate(',',t.user_ip) then left(t.user_ip,locate(',',t.user_ip)-1) else t.user_ip end AND u.id_user <> '$id_user'
                WHERE t.id_user = '$id_user' AND t.user_ip IS NOT NULL  AND t.user_ip <> ''
                      AND (t.note LIKE 'Зачисление средств от пользователя%'
                        OR t.note LIKE 'Снятие средств для отправки пользователю%'
                        OR (t.metod = 'out' AND t.note NOT LIKE 'Комисия за вывод%')
                        OR t.note LIKE 'Бонус за регистрацию%'
                        OR t.note LIKE 'Получение кредита на Арбитраж%'
                        OR t.note LIKE 'Продажа сертификата №%')
                GROUP BY ips
                UNION
                SELECT case when 0 < locate(',',c.user_ip) then left(c.user_ip,locate(',',c.user_ip)-1) else c.user_ip end ips, CONCAT_WS('',c.id,'(', c.id_user,')',' <-> ',dc.id,'(',dc.id_user,')')  note, GROUP_CONCAT(DISTINCT CASE when u.id_user IS NULL then '-' else u.id_user end) id_user, count(DISTINCT u.id_user) `count`, 'кредит' info
                FROM credits c
                INNER JOIN credits dc ON dc.debit = c.id
                left join users u on u.ip_reg = case when 0 < locate(',',c.user_ip) then left(c.user_ip,locate(',',c.user_ip)-1) else c.user_ip end AND u.id_user <> '$id_user'
                WHERE c.id_user = '$id_user' AND c.user_ip IS NOT NULL AND c.user_ip <> ''
                GROUP BY ips";

        return $this->db->query($q)->result();
    }

    public function getPaymentTransaction( $id_user, $id, $v  )
    {
        if( empty( $id_user ) || empty( $id ) || empty($v) )
            return FALSE;

        if( $v === 2 )
        {
            $res =  $this->db->where( 'id_user', $id_user )
                            ->where( "( note LIKE 'Пополнение средств по заявке (оплата по вкладу) №$id%' OR note LIKE 'Пополнение средств по заявке №№$id..%' )" )
                            ->limit(1)
                            ->get( $this->tableName )
                            ->row();
        }else{
            $res =  $this->db->where( 'id_user', $id_user )
                      ->where( "( note LIKE 'Снятие средств по заявке №№$id..%' OR note LIKE 'Снятие средств по заявке № $id%' )" )
                      ->limit(1)
                      ->get( $this->tableName )
                      ->row();
        }

        if( empty( $res ) )
            return FALSE;

        return $res->id;
    }
    public function getPaymentTransactionBack( $id_user, $id, $v  )
    {
        if( empty( $id_user ) || empty( $id ) || empty($v) )
            return FALSE;

        if( $v === 2 )
        {
            $res =  $this->db->where( 'id_user', $id_user )
                         ->where( 'type', self::TYPE_INCOME_REPAY )
                         ->where( 'value', $id )
                         ->limit(1)
                         ->get( $this->tableName )
                         ->row();
        }else{
            $res =  $this->db->where( 'id_user', $id_user )
                         ->where( 'type', self::TYPE_EXPENSE_INVEST_REPAY )
                         ->where( 'value', $id )
                         ->limit(1)
                         ->get( $this->tableName )
                         ->row();
        }


        if( empty( $res ) )
            return FALSE;

        return $res->id;
    }

    public function getArhiveSum($id_user, $bonus = NULL) {
        if ( !empty($bonus) )
            $this->db->where('bonus', $bonus);
        return $this->db
            ->select('sum(CASE WHEN status = ' . Base_model::TRANSACTION_STATUS_RECEIVED . ' THEN summa '
                        . 'WHEN status = ' . Base_model::TRANSACTION_STATUS_REMOVED . ' THEN 0-summa END) summa')
            ->where("type", 1000)
            ->where("value", 1000)
            ->where('id_user', $id_user)
            ->get($this->tableName)
            ->row('summa');
    }

    public function payPaymentBonus($data, $send_money = FALSE) {
        $this->base_model->addMoney($data->id_user, $data->summa);
        $amount = null;

        if(config_item('bonus_on_incame_pay')) {//включение выключение этой акции
            if (100 <= $data->summa && 300 > $data->summa)
                $amount = $data->summa * 0.2;
            elseif (300 <= $data->summa && 600 > $data->summa)
                $amount = $data->summa * 0.3;
            elseif (600 <= $data->summa && 1000 > $data->summa)
                $amount = $data->summa * 0.4;
            elseif (1000 <= $data->summa)
                $amount = $data->summa * 0.5;
        } elseif (config_item('bonus_on_incame_pay_from_send_money') && $send_money) {
            if (config_item("min_sum_4_count_bonus_on_incame_pay_from_send_money") <= $data->summa){
                $amount = $data->summa * config_item("psnt_bonus_on_incame_pay_from_send_money");
                $amount = (config_item("min_sum_of_bonuses_on_incame_pay_from_send_money") <= $amount) ? $amount : config_item("min_sum_of_bonuses_on_incame_pay_from_send_money");
                $amount = $amount + config_item("sum_add_of_bonuses_on_incame_pay_from_send_money");
            }
        }

        if (!empty($amount) && $data->bonus != 7) {
        //    $this->addPay($data->id_user, $amount, Transactions_model::TYPE_BONUS_PAYMENT, $data->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, 'Бонус за пополнение средств');
        }
    }

    public function payPaymentBonusForExchange($data, $exchange_user_id) {

        $amount = null;

        if(config_item('bonus_on_incame_pay')) {//включение выключение этой акции
             $this->load->model('Exchange_users_sets_model');
            $perc = $this->Exchange_users_sets_model->find_value( get_current_user_id(), $data->summa );
            $amount = $data->summa * ($perc / 100);
        } elseif (config_item('bonus_on_incame_pay_from_send_money') ) {
            if (config_item("min_sum_4_count_bonus_on_incame_pay_from_send_money") <= $data->summa){
                $amount = $data->summa * config_item("psnt_bonus_on_incame_pay_from_send_money");
                $amount = (config_item("min_sum_of_bonuses_on_incame_pay_from_send_money") <= $amount) ? $amount : config_item("min_sum_of_bonuses_on_incame_pay_from_send_money");
                $amount = $amount + config_item("sum_add_of_bonuses_on_incame_pay_from_send_money");
            }
        }




        if (!empty($amount) && in_array($data->bonus,[6])) {
            $this->addPay($data->id_user, $amount, Transactions_model::TYPE_BONUS_PAYMENT, $data->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, 'Бонус за пополнение средств');
            $this->addPay($exchange_user_id, $amount, Transactions_model::TYPE_BONUS_PAYMENT, $data->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_ON, 'Бонус за пополнение средств пользователю #'.$data->id_user);
        }
    }


    public function payEnvoyPaymentBonus($card_trans_id, $user_id, $summa) {
        $amount = null;

        if(config_item('bonus_on_incame_pay')) {//включение выключение этой акции
            if (100 <= $summa && 300 > $summa)
                $amount = $summa * 0.2;
            elseif (300 <= $summa && 600 > $summa)
                $amount = $summa * 0.3;
            elseif (600 <= $summa && 1000 > $summa)
                $amount = $summa * 0.4;
            elseif (1000 <= $summa)
                $amount = $summa * 0.5;
        }


        // проверим - платили ли уже?
        $r = $this->db->where(['type'=>Transactions_model::TYPE_BONUS_PAYMENT, 'value'=>$card_trans_id])->get($this->tableName)->result();
        if (!empty($amount)&& empty($r)) {
            $this->addPay($user_id, $amount, Transactions_model::TYPE_BONUS_PAYMENT, $card_trans_id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_ON, 'Бонус за пополнение средств');
        }
    }

    public function payPaymentFee($data){
        $psnts = config_item('pay_sys_psnt');
        $psnt = $psnts[$data->metod];
        $amount = $data->summa*$psnt['psnt']/100 + $psnt['add'];
        $amount = ($amount < $psnt['min']) ? $psnt['min'] : $amount;
//        var_dump($amount);
        if (!empty($amount)) {
            $this->addPay($data->id_user, $amount, Transactions_model::TYPE_EXPENSE_INFEE, $data->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_OFF, "Комиссия за пополнение. Транзакция №$data->id");
        }
    }

    /*
     * Получает список ошибок для транзакций
     */
    public function get_transactions_error_list(){
        $this->db->cache_on();
        $res = $this->db->get('trasactions_errors')->result();
        $this->db->cache_off();

        return $res;

    }


    public function find_double_wallets($trans){
        if(!is_object($trans))
            $trans = $this->getTransaction($trans);

        //echo 'MATCHES:';
        if (!preg_match_all("/:(.*)\(комисия/",  $trans->note, $matches))
                return FALSE;

        //var_dump( $matches );
        $payout_systems = payout_systems();
        //print_r($payout_systems );
       if( count($matches) < 2 )
           return FALSE;

       $paysys = NULL;
       $paysys_field = NULL;
       $wallet = NULL;

       $match = $matches[1][0];
       foreach ($payout_systems as $name => $field){
            if ( !in_array($GLOBALS["WHITELABEL_NAME"].' Visa Card',$name) && strpos($match,$name) !== FALSE ){
                $paysys = $name;
                $paysys_field = $field;
                $wallet = trim(str_replace($paysys, '', $match));
                if ( $paysys == 'VISA/MasterCard'){
                    $wallet = substr($wallet,0, strpos($wallet, ' '));
                }
                break;
            }
       }

       //echo "!$paysys $paysys_field [$wallet]".$this->code->code($wallet);

       if ($paysys === NULL || empty($wallet) )
           return FALSE;


       if ( $paysys == 'Wire Bank'){

            $wb_users = $this->db->select('user_id')->where( array(
                                                                                             'field_id'=>10,
                                                                                             'value'=>$this->code->code($wallet)/*,
                                                                                             'user_id !='=>$trans->id_user*/)
                                                                                   )->get('payment_data_values')->result();

            if ( empty($wb_users) )
                return FALSE;
            $users = array();
            foreach ($wb_users as $user)
                $users[] = $user->user_id;
            $res = $this->db->select('id_user,name,sername,patronymic,email,phone')->where_in( 'id_user', $users)
                                                                               ->get('users')->result();

       } else {
           if ( $paysys_field == 'wtcard')
               return FALSE;
            $res = $this->db->select('id_user,name,sername,patronymic,email,phone')->where( array(
                                                                                         $paysys_field=>$this->code->code($wallet)/*,
                                                                                         'id_user !='=>$trans->id_user*/)
                                                                               )->get('users')->result();
       }
       if ( empty($res) )
           return FALSE;
       if ( count($res)< 2 )
           return FALSE;

       $res = $this->code->db_decode($res);
       //var_dump( $res );


       return $res;



    }

    #проверка лимитов пополнения
    public function checkTopUpLimits( $user_id_pay, $amount, $from_user_id = NULL )
    {
        $exchange_users = getExchangeUsers(); //обменники
        if( NULL !== $from_user_id && !empty(  $exchange_users ) && in_array( $from_user_id, $exchange_users ) )
        {
            //регистрация тех, кому перевли много от обменников
            return TRUE;
        }

        $this->load->model('accaunt_model','accaunt');

        $scores = viewData()->accaunt_header;
        $message_for_me = TRUE;
        if( $this->accaunt->get_user_id() != $user_id_pay )
        {
            $scores = $this->accaunt->recalculateUserRating($user_id_pay);
            $message_for_me = FALSE;
        }
        $top_up_sum = $scores['top_up_sum'];
        $money_sum_process_withdrawal_bank = $scores['money_sum_process_withdrawal_bank'];
        $top_up_overload = $scores['top_up_overload'];

        if( $top_up_overload > 0 )
        {
            $message_text = _e('Вы достигли лимита пополнений.');
            if( TRUE === $message_for_me ) $message_text = _e('Аккаунт получателя достиг лимита пополнений.');

            return [ 'error' => $message_text ];
        }
        if( $money_sum_process_withdrawal_bank > 0 && $top_up_sum > 10000 )
        {
            $message_text = _e('У вас есть счет(а) пополенения банком, ожидающие оплаты. Для завершения операции '.
                                                'пополнения оплатите счет(а) или удалите их, если они еще не были оплачены.');
            if( TRUE === $message_for_me ) $message_text = _e('Вы можете пополнить аккаунт получателя на сумму, не превышающую ');

            return [ 'error' => $message_text ];

        }
        if( $top_up_sum <= 10000 && $amount + $top_up_sum > 10000 )
        {
            $message_text = _e('Вы можете пополнить Ваш аккаунт на сумму, не превышающую ' );
            if( FALSE === $message_for_me ) $message_text = _e('Аккаунт получателя достиг лимита пополнений.');

            return [ 'error' => $message_text . (10000 - $top_up_sum) .' USD' ];
        }
        return TRUE;
    }

    #проверяем, проходили ли транзакции по этой сделке для всех участников или нет
    #если найдена хотя бы одна транзакция - возвращает FALSE
    public function check_p2p_completed_order($order)
    {
        if( empty( $order ) ) return FALSE;

        if(is_numeric($order) ){
            $this->load->model('currency_exchange_model','currency_exchange');
            $order = $this->currency_exchange->get_order_by_id( $order );
        }

        if( empty( $order->original_order_id ) ) $order->original_order_id = $order->id;

        $where = "(status = 1 or status = 3 ) AND (type = 76 or type = 63 ) AND (id_user = $order->seller_user_id or id_user = '$order->buyer_user_id' )";
        $TransactionByValue = $this->getTransactionByValue( $order->original_order_id, $where );

        if( !empty( $TransactionByValue ) ) return FALSE;

        return TRUE;
    }

    public function get_processing_loan_transactions($user_id, $loan_id){
        return $this->db->where('id_user', $user_id)->where("note like '%Loan: #{$loan_id}%'")->where_in('status',[3,4,9,10])->get('transactions')->result();

    }



    public function get_social_bonuses_today_count($user_id){

        $r = $this->db->select('summa')->where('id_user', $user_id)->where('type', 92)->where('date >',date('Y-m-d 00:00:00'))->get('transactions')->result();
        return empty($r)?0:count($r);

    }


    public function getTransactionByParams($where, $isOne = FALSE){
        if ( $isOne )
            return $this->db->where($where)->get('transactions')->row();
        else
            return $this->db->where($where)->get('transactions')->result();
    }
    
    public function  get_bonus_pay_to_card_summ($user_id){
        $r = $this->db->select('summa')->where('id_user', $user_id)->where('type', 16)->where('metod','wtcard')->get('transactions')->result();
        return empty($r)?0:count($r);
    }
    
    
}
