<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Credits_model extends CI_Model{

    public $tableName = "credits";

    const DIRECT_OFF = 0;
    const DIRECT_ON  = 1;

    function __construct(){
        parent::__construct();
    }

    public function setStatus($id, $status){
        $this->db->update($this->tableName, array("state" => $status), array("id" => $id), 1);
    }

    public function setPayment($id, $payment){
        $this->db->update($this->tableName, array("payment" => $payment), array("id" => $id), 1);
    }

    public function setBlocked_money($id, $status){
        $this->db->update($this->tableName, array("blocked_money" => $status), array("id" => $id), 1);
    }

    public function setArbitration($id, $arbitration){
        $this->db->update($this->tableName, array("arbitration" => $arbitration), array("id" => $id), 1);
    }

    public function setParner_payed_today($id){
        $this->db->update($this->tableName, array("arbitration_invest_pay" => date('Y-m-d')), array("id" => $id), 1);
    }

    public function setExchange($id, $summ, $me){
        $this->db->update($this->tableName, array("exchange" => Base_model::CREDIT_EXCHANGE_ON, "summ_exchange" => $summ, "date_active" => date("Y-m-d H:i:s")), array("id" => $id), 1);
        $this->db->update($this->tableName, array("exchange" => Base_model::CREDIT_EXCHANGE_ON, "summ_exchange" => $summ, "debit_id_user" => $me, "date_active" => date("Y-m-d H:i:s")), array("debit" => $id), 1);
    }

    public function delExchange($id){
        $this->db->update($this->tableName, array("exchange" => Base_model::CREDIT_EXCHANGE_OFF, "summ_exchange" => 0), array("id" => $id), 1);
        $this->db->update($this->tableName, array("exchange" => Base_model::CREDIT_EXCHANGE_OFF, "summ_exchange" => 0), array("debit" => $id), 1);
    }

    public function getAllCreditsForExchange($params){
        $filters  = $params["filter"];
        $search   = (float) $params["search"];
        $per_page = (int) $params["per_page"];
        $offset   = (int) $params["offset"];
        $order    = $params["order"];
        $me       = $params["me"];

        $r = array("count" => "0", "rows" => array());

        if(isset($search) && !empty($search))
            $this->createSearch($search);
        if(isset($filters) && !empty($filters))
            $this->_createFilterForApplications($filters);
        $r["count"] = $this->db
            ->select('COUNT(*) as count')
            ->where(array(
                "state"    => Base_model::CREDIT_STATUS_ACTIVE,
                "type"     => Base_model::CREDIT_TYPE_CREDIT,
                "exchange" => Base_model::CREDIT_EXCHANGE_ON
            ))
            ->get("credits")
            ->row("count");

        if(isset($order) && !empty($order))
            $this->createOrder($order);
        if(isset($search) && !empty($search))
            $this->createSearch($search);
        if(isset($filters) && !empty($filters))
            $this->_createFilterForApplications($filters);

        $r["rows"] = $this->db
            ->select("*,
                (CASE WHEN (DateDiff(out_time, now())) < 0 THEN concat('"._e("Просрочен")." ', DateDiff(out_time, now()) * -1 , 'дн.')  ELSE (DateDiff(out_time, now())) END) AS date_out,

                (
                    CASE WHEN (DateDiff(out_time, now())) > 0
                    THEN summa * (percent/100) * DateDiff(now(), date_kontract) + summa
                    ELSE summa * 0.00065 * time + summa
                    END
                ) AS amount_today,

               (
                    CASE WHEN (DateDiff(out_time, now())) > 0
                    THEN summ_exchange - (summa * (percent/100) * DateDiff(now(), date_kontract) + summa)
                    ELSE summ_exchange - (summa * 0.00065 * time + summa)
                    END
                ) AS diff_today,

                (
                    CASE WHEN debit_id_user = $me
                         THEN 1
                         WHEN id_user = $me
                         THEN 2
                    ELSE 0
                    END
                ) AS action,

                (
                    CASE WHEN (TIMESTAMPDIFF(HOUR, date_active, NOW()) >= 1 OR date_active = '0000-00-00 00:00:00')
                              AND TIMESTAMPDIFF(DAY, date_kontract, NOW()) >= 1
                         THEN 'true'
                    ELSE 'false'
                    END
                ) AS can_sell", false)
            ->where(array(
                "state"    => Base_model::CREDIT_STATUS_ACTIVE,
                "type"     => Base_model::CREDIT_TYPE_CREDIT,
                "exchange" => Base_model::CREDIT_EXCHANGE_ON
            ))
            ->limit($per_page, $offset)
            ->get("credits")
            ->result();
        foreach($r['rows'] as &$item){
            $item->amount_today = price_format_view($item->amount_today, false);
            $item->diff_today   = price_format_view($item->diff_today, false);
        }
        return $r;
    }

    public function getAllCreditsForExchangeCreds($params){
        $search   = (float) $params["search"];
        $per_page = (int) $params["per_page"];
        $offset   = (int) $params["offset"];
        $order    = $params["order"];
        $me       = $params["me"];

        $r = array("count" => "0", "rows" => array());

        if(isset($search) && !empty($search))
            $this->createSearch($search);
        $r["count"] = $this->db
            ->select('COUNT(*) as count')
            ->where(array(
                "state"          => Base_model::CREDIT_STATUS_ACTIVE,
                "type"           => Base_model::CREDIT_TYPE_INVEST,
                "exchange"       => Base_model::CREDIT_EXCHANGE_ON,
                "garant_percent" => Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER
            ))
            ->get("credits")
            ->row("count");

        if(isset($order) && !empty($order))
            $this->createOrder($order);
        if(isset($search) && !empty($search))
            $this->createSearch($search);

        $r["rows"] = $this->db
            ->select("*,
                (CASE WHEN (DateDiff(out_time, now())) < 0 THEN 0  ELSE (DateDiff(out_time, now())) END) AS date_out,
(
                    summa*(percent/100) * (CASE WHEN (DateDiff(out_time, now())) < 0 THEN 0  ELSE (DateDiff(out_time, now())) END)
                ) as days_summ,

                (
                    summa * (percent/100) * (CASE WHEN (DateDiff(out_time, now())) < 0 THEN 0  ELSE (DateDiff(out_time, now())) END) + summa
                ) AS amount_today,

               (
                    summa-summ_exchange
                ) AS diff_today,

                (
                    CASE WHEN id_user = $me
                         THEN 1
                    ELSE 0
                    END
                ) AS action,

                'false' AS can_sell", false)
            ->where(array(
                "state"          => Base_model::CREDIT_STATUS_ACTIVE,
                "type"           => Base_model::CREDIT_TYPE_INVEST,
                "exchange"       => Base_model::CREDIT_EXCHANGE_ON,
                "garant_percent" => Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER
            ))
            ->limit($per_page, $offset)
            ->get("credits")
            ->result();
        foreach($r['rows'] as &$item){
            $item->amount_today = price_format_view($item->amount_today, false);
            $item->diff_today   = price_format_view($item->diff_today, false);
        }
        return $r;
    }

    protected function createOrder($order){
        reset($order);
        $col = key($order);
        $this->db->order_by("$col $order[$col]");
    }

    protected function _createFilterForApplications($filters, $t = ''){
        foreach($filters as $key => $value){
            $value = str_replace('*', '%', $value);
            if($value === '')
                continue;
            if ( $value == 'bonus' )
                continue;//$this->db->where("($t$key='$value' )");  //  or $t$key IS NULL
            elseif ( $key == 'friends' )
                $this->db->where_in($t."id_user", explode(',', $value) );

        //    elseif ( $value == 'card' )
       //         $this->db->where("($t$key='$value' or ($t$key IS NULL and bonus=7))");
            elseif ( $value == 'E_WALLET' )
                $this->db->where("$t$key like", "$value%");
            else
                $this->db->where("$t$key", "$value");
            //if ( get_current_user_id() == 92156962){echo "$t$key $value";}
        }
    }

    protected function createSearch($search){
        $search_str = "(id LIKE '$search'"
            ." OR id_user = '$search'"  //bilo $search% i LIKE vmesto =
            ." OR summa = '$search'"
            ." OR time = '$search'"
            ." OR percent = '$search'"
            ." OR out_summ = '$search'"
            ." OR summ_exchange = '$search'"
            .")";
        $this->db->where($search_str);
    }

    public function bayCertificate($i, $bonus = null){
        //Open TRANSACTION!!!!
        $this->db->trans_start();{
//            if(!is_object($i))
            $i = $this->getDebit4Update($i->id);
            if(empty($i)){
                log_massage('error', 'Имеються некоторые проблемы с продажей сертификата (sellcertificat/$i is empty)');
                die(_e("Извините за некоторые неудобства, что-то пошло не так..."));
            }

            $this->db->update($this->tableName, array('certificate'    => Base_model::CREDIT_CERTIFICAT_PAID,
                'state'          => Base_model::CREDIT_STATUS_PAID,
//                          'kontract' => Base_model::CREDIT_KONTRACT_PAID,
//                          'date_kontract' => date('Y-m-d'),
                'out_time'       => date('Y-m-d'), //Base_model::CREDIT_KONTRACT_PAID_OUT_TIME,
                'debit'          => Base_model::CREDIT_CERTIFICAT_PAID_NEW_DEBIT,
                'previous_debit' => $i->debit,
                ), array('id' => (int) $i->id)
            );

            $d = $this->getDebit4Update($i->debit);

            if(is_null($bonus))
                $bonus = $i->bonus;

            $newID = $this->addOrder($d, $this->users->getCurrUserId(), $bonus, Base_model::CREDIT_OVERDRAFT_OFF, Base_model::CREDIT_EXCHANGE_STATUS_BAY, $d->card_id, $d->account_type, $d->account_id);
            if(0 >= $newID){
                log_message('error', "Имеються некоторые проблемы с продажей сертификата {$d->id}(sellcertificat/NewId is $newID )");
                die(_e("Имеются некоторые проблемы с продажей сертификата")."($newID)");
            }

            //подтвердить кредит - скапировав из прошлой заявку данные
            $this->db->update('credits', array('state'           => Base_model::CREDIT_STATUS_ACTIVE,
                'kontract'        => $i->kontract,
                'date_kontract'   => $i->date_kontract,
                'out_time'        => $i->out_time,
                'confirm_payment' => Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM,
                'exchange'        => Base_model::CREDIT_EXCHANGE_OFF,
                'summ_exchange'   => 0
                ), "id = $newID OR id = $d->id"
            );
        }

        $this->load->model("transactions_model", 'transactions');
        $this->transactions->bayCertificat($i, $newID, $bonus);

        //CLOSE TRANSACTION!!!!
        $this->db->trans_complete();
        return true;
    }

    public function bayCREDSCertificate($i, $bonus = NULL){
        //Open TRANSACTION!!!!

        if(is_null($bonus))
            $bonus = $i->bonus;
        $this->db->trans_start();{
//            if(!is_object($i))
            $i = $this->getDebit4Update($i->id);
            if(empty($i)){
                log_massage('error', 'Имеються некоторые проблемы с продажей сертификата (sellcertificat/$i is empty)');
                die(_e("Извините за некоторые неудобства, что-то пошло не так..."));
            }

            $this->db->update($this->tableName, array('certificate'    => Base_model::CREDIT_CERTIFICAT_PAID,
                'state'          => Base_model::CREDIT_STATUS_PAID,
//                          'kontract' => Base_model::CREDIT_KONTRACT_PAID,
//                          'date_kontract' => date('Y-m-d'),
                'out_time'       => date('Y-m-d'), //Base_model::CREDIT_KONTRACT_PAID_OUT_TIME,
                'debit'          => Base_model::CREDIT_CERTIFICAT_PAID_NEW_DEBIT,
                'previous_debit' => $i->debit,
                ), array('id' => (int) $i->id)
            );

            $newID = $this->createPartnerArbitrage($i->summa, $this->users->getCurrUserId());
            if(empty($newID)){
                log_message('error', "Имеються некоторые проблемы с продажей сертификата {$i->id}(bayCREDSCertificate/NewId is $newID )");
                die(_e("Имеются некоторые проблемы с продажей сертификата"));
            }

            //подтвердить кредит - скапировав из прошлой заявку данные
            $this->db->update('credits', array('state'           => Base_model::CREDIT_STATUS_ACTIVE,
                'kontract'        => $i->kontract,
                'date_kontract'   => $i->date_kontract,
                'out_time'        => $i->out_time,
                'confirm_payment' => Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM,
                'exchange'        => Base_model::CREDIT_EXCHANGE_OFF,
                'summ_exchange'   => 0
                ), "id = $newID"
            );
        }

        $this->load->model("transactions_model", 'transactions');
        $this->transactions->bayCREDSCertificat($i, $newID, $bonus);

        //CLOSE TRANSACTION!!!!
        $this->db->trans_complete();
        return true;
    }

    public function addOrder($debit, $id_user, $bonus = -1, $overdraft = -1, $baySertificat = -1, $card_id = NULL, $credit_account_type = NULL, $credit_account_id = NULL){
        if(empty($debit) || !is_object($debit))
            return null;

        //нельзя передавать так параметры!
        $summa   = intval($debit->summa);
        $time    = intval($debit->time);
        $percent = floatval($debit->percent);
//        $pays = (array) getPaymentOrger($debit->payment);

        if($debit->bonus)
            $_POST['bonus'] = $debit->bonus;

        if($card_id == NULL)
            $card_id = $debit->card_id;


        $garant = $debit->garant;
        $direct = $debit->direct;

        if(Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND != $id_user){
            if($summa < 1 or $summa > 1000){
                return -1;
            }
        }

        if(!$garant || $bonus || $direct)
            $overdraft = false;

        $outPay = credit_summ($percent, $summa, $time);
        $income = ($outPay - $summa);

        $this->load->model("users_model", 'users');

        $data = array(
            'id'            => null,
            'debit'         => $debit->id,
            'debit_id_user' => $debit->id_user,
            'id_user'       => $id_user,
            'user_ip'       => $this->users->getIpUser(),
            'master'        => $GLOBALS["WHITELABEL_ID"],
            'summa'         => changeComa($summa),
            'garant'        => $garant,
            'direct'        => $direct,
            'overdraft'     => ((-1 === $overdraft) ? 0 : $overdraft),
            'time'          => (int) $time,
            'percent'       => changeComa($percent),
            'state'         => Base_model::CREDIT_STATUS_WAITING,
            'type'          => ((Base_model::CREDIT_TYPE_CREDIT == $debit->type) ? Base_model::CREDIT_TYPE_INVEST : Base_model::CREDIT_TYPE_CREDIT),
            'payment'       => setPaymentOrger(),
            'out_summ'      => changeComa($outPay),
            'income'        => changeComa($income),
            'card_id'       => $card_id,
            'account_type'  => empty($credit_account_type) ? $debit->account_type : $credit_account_type,
            'account_id'    => empty($credit_account_id) ? $debit->account_id : $credit_account_id
        );

        if($bonus == 0 || $bonus == -1)
            $data['bonus'] = 2;
        else
            $data['bonus'] = $bonus;

        if(Base_model::CREDIT_EXCHANGE_STATUS_BAY == $baySertificat)
            $data['active'] = Base_model::CREDIT_EXCHANGE_STATUS_BAY;

        try{
            $this->db->insert($this->tableName, $data);
            $newId = $this->db->insert_id();
        } catch(Exception $exc){
            return -2;
        }





//        $this->load->model("credit_state_model","credit_state");
//        $this->credit_state->setShowed($newId);
//        $this->credit_state->setWaiting($newId);

        if(Base_model::CREDIT_TYPE_CREDIT == $debit->type)
            $this->createInvestArbitration($newId, $debit->summa, $id_user, $garant, $bonus, $overdraft, $direct);


        //  $this->mail->user_sender('new_invest', $id_user, array(), $id_debit);
        // $this->mail->admin_sender('new_invest_admin', $id_user, array(), $id_debit);
        $this->load->helper('sms');
        sms_send('invest');
        $this->code->clearCache();

        if(empty($newId)){
            if(Base_model::CREDIT_TYPE_CREDIT == $debit->type)
                throw new Exception(_e("Ошибка. Не получилось создать инвестицию"));
            else
                throw new Exception(_e("Ошибка. Не получилось создать кредит"));
        }

        $state_for_check = Base_model::CREDIT_STATUS_SHOWED;
        if(Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND == $id_user || $debit->exchange == 1 )
            $state_for_check = Base_model::CREDIT_STATUS_ACTIVE;

        $this->db->update($this->tableName, ['state' => Base_model::CREDIT_STATUS_WAITING, 'debit' => $newId, "debit_id_user" => $id_user], ['id' => $debit->id, 'state' => $state_for_check]);
        $res = $this->db->affected_rows();
        if(0 == $res){
            $this->db->delete($this->tableName, ['id' => $newId], 1);
            $this->base_model->log2file("[{$debit->id}]DELETE newid=$newId data=".print_r($data, true), "addOrder");
            return -3;
        }


        return $newId;
    }

    public function createVkladArbitrage($summa, $id_user, $bonus){
        $summa = changeComa($summa);
        $vklad = array(
            'id_user'        => $id_user,
            'debit_id_user'  => 400400,
            'user_ip'        => $this->users->getIpUser(),
            'master'         => $GLOBALS["WHITELABEL_ID"],
            'summa'          => $summa,
            'percent'        => "0.5",
            'income'         => $summa * 0.005,
            'out_summ'       => $summa * 0.005,
            'garant'         => Base_model::CREDIT_GARANT_ON,
            'state'          => Base_model::CREDIT_STATUS_WAITING,
            'type'           => Base_model::CREDIT_TYPE_INVEST, //for invest_arbitrage
//            'payment' => setPaymentOrger(),
            'garant_percent' => Base_model::CREDIT_INVEST_ARBITRAGE_ON,
            'bonus'          => $bonus
        );
        $this->db->insert($this->tableName, $vklad);
        return $this->db->insert_id();
    }

    public function createPartnerArbitrage($summa, $id_user){
        $this->load->model("var_model", 'var');
        $time              = 40;
        $sum_own           = 0;
        $sum_arbitration   = $summa;
        $summa             = changeComa($summa);
        $vklad             = array(
            'id_user'         => $id_user,
            'debit_id_user'   => 400400,
            'user_ip'         => $this->users->getIpUser(),
            'master'          => $GLOBALS["WHITELABEL_ID"],
            'summa'           => $summa,
            'percent'         => "0.5",
            'time'            => $time,
            'income'          => $summa * 0.005,
            'out_summ'        => $summa * 0.005,
            'garant'          => Base_model::CREDIT_GARANT_ON,
            'state'           => Base_model::CREDIT_STATUS_ACTIVE,
            'date_kontract'   => date('Y-m-d'),
            'kontract'        => $this->var->get_kontract_count(),
            'type'            => Base_model::CREDIT_TYPE_INVEST, //for invest_arbitrage
            'out_time'        => credit_time(date('Y-m-d'), $time),
//            'payment' => setPaymentOrger(),
            'garant_percent'  => Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER,
            'sum_own'         => $sum_own,
            'sum_arbitration' => $sum_arbitration,
            'bonus'           => Base_model::TRANSACTION_BONUS_PARTNER
        );
        $this->db->insert($this->tableName, $vklad);
        $id_partner_arbitr = $this->db->insert_id();
        return $id_partner_arbitr;
    }

    public function activateInvestArbitrage($vklad_id){
        $this->load->model("var_model", 'var');
        $this->db->update(
            $this->tableName, array(
            "state"         => Base_model::CREDIT_STATUS_ACTIVE,
            'kontract'      => $this->var->get_kontract_count(),
            'date_kontract' => date('Y-m-d')
            ), "id = $vklad_id", 1);
    }

    public function get_own_sum_and_arbitration($id_user, $summa){
        $arbitration_source = $this->db
            ->select('SUM(summa) AS summa') //теперь для всех вкладов
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->where_in('bonus', [6])
            ->where('state', Base_model::CREDIT_STATUS_ACTIVE)
            //  ->where('garant_percent', '0')
            ->where('id_user', $id_user)
            ->get($this->tableName)
            ->row('summa');

        $loan_source = $this->db
            ->select('SUM(summa) AS summa') //теперь для всех вкладов
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->where('arbitration', 0)
            ->where_in('bonus', [6])
            ->where('state', Base_model::CREDIT_STATUS_ACTIVE)
            //  ->where('garant_percent', '0')
            ->where('id_user', $id_user)
            ->get($this->tableName)
            ->row('summa');



        $arbitration_used = $this->db
            ->select('SUM(sum_arbitration) AS summa')
            ->where('sum_arbitration >', '0')
            //->where('bonus', Base_model::CREDIT_BONUS_OFF)
            ->where_in('bonus', [6])
            ->where('type', Base_model::CREDIT_TYPE_INVEST)
            //->where('garant_percent', '0')
            ->where_in('state', array(Base_model::CREDIT_STATUS_ACTIVE, Base_model::CREDIT_STATUS_SHOWED))
            ->where('id_user', $id_user)
            ->get($this->tableName)
            ->row('summa');

        $arbitration_active = $arbitration_source - $arbitration_used;

        if($arbitration_active >= $summa){
            $sum_own         = 0;
            $sum_arbitration = $summa;
        } else if($arbitration_active > 0){
            $sum_own         = $summa - $arbitration_active;
            $sum_arbitration = $arbitration_active;
        } else {
            $sum_own         = $summa;
            $sum_arbitration = 0;
        }


        $arbitration_active_without_loans = max(0, $arbitration_source - $arbitration_used);
        if($arbitration_active_without_loans >= $summa){
            $sum_own_without_loans         = 0;
            $sum_arbitration_without_loans = $summa;
        } else if($arbitration_active_without_loans > 0){
            $sum_own_without_loans         = $summa - $arbitration_active_without_loans;
            $sum_arbitration_without_loans = $arbitration_active_without_loans;
        } else {
            $sum_own_without_loans         = $summa;
            $sum_arbitration_without_loans = 0;
        }



        return [
            'sum_own'         => $sum_own,
            'sum_arbitration' => $sum_arbitration,
            'sum_own_without_loans'         => $sum_own_without_loans,
            'sum_arbitration_without_loans' => $sum_arbitration_without_loans,
            'arbitration_active'               => $arbitration_active,
            'arbitration_active_without_loans' => $arbitration_active_without_loans
        ];
    }

    public function get_own_sum_and_loans_and_arbitration($id_user, $summa, $date = NULL, $bonus = 6){
       $rating_by_bonus = $this->accaunt->recalculateUserRating($id_user, $date);
       $log = "get_own_sum_and_loans_and_arbitration($id_user, $summa, $date = NULL, $bonus = 6)\n";

       if ( $bonus == 7 ){
           $this->load->model('Card_model','card');
           $cards_balance = $this->card->getCardsBalance($id_user, FALSE);
           $own_summ = min($cards_balance, $cards_balance + $rating_by_bonus['my_investments_garant_by_bonus'][$bonus]+$rating_by_bonus['my_investments_standart_by_bonus'][$bonus] - $rating_by_bonus['all_loans_active_summ_by_bonus'][$bonus] - $rating_by_bonus['invests_showed_and_active_own_sum_by_bonus'][$bonus]);
           $log .= "own_summ= $own_summ = min($cards_balance, $cards_balance + {$rating_by_bonus['my_investments_garant_by_bonus'][$bonus]}+{$rating_by_bonus['my_investments_standart_by_bonus'][$bonus]} - {$rating_by_bonus['all_loans_active_summ_by_bonus'][$bonus]} - {$rating_by_bonus['invests_showed_and_active_own_sum_by_bonus'][$bonus]})".PHP_EOL;

       } else {
            $own_summ = min($rating_by_bonus['payment_account_by_bonus'][$bonus]-$rating_by_bonus['total_processing_by_bonus'][$bonus],
                $rating_by_bonus['payment_account_by_bonus'][$bonus] +
                $rating_by_bonus['my_investments_garant_by_bonus'][$bonus]+
                $rating_by_bonus['my_investments_standart_by_bonus'][$bonus] -
                $rating_by_bonus['all_loans_active_summ_by_bonus'][$bonus] - $rating_by_bonus['old_arbitration_active_summ_by_bonus'][$bonus] -
               $rating_by_bonus['total_processing_by_bonus'][$bonus]-
			   $rating_by_bonus['arbitration_active_summ_by_bonus'][$bonus]+
			   $rating_by_bonus['arbitration_shown_summ_by_bonus'][$bonus]-
               $rating_by_bonus['invests_showed_and_active_own_sum_by_bonus'][$bonus]);
       }

       $own_summ = floor($own_summ);
       $own_summ = $own_summ-$own_summ%50;
       $own_summ = max(0,$own_summ);


       $loans_summ = max(0,$rating_by_bonus['all_loans_active_summ_by_bonus'][$bonus]-$rating_by_bonus['sum_loan_active_summ_by_bonus'][$bonus] + $rating_by_bonus['old_arbitration_active_summ_by_bonus'][$bonus]);
       $arbitration_summ = $rating_by_bonus['max_arbitrage_calc_by_bonus'][$bonus] - $rating_by_bonus['arbitration_active_summ_by_bonus'][$bonus];

       $log .= "loans_summ = $loans_summ = max(0,{$rating_by_bonus['all_loans_active_summ_by_bonus'][$bonus]}-{$rating_by_bonus['sum_loan_active_summ_by_bonus'][$bonus]} + {$rating_by_bonus['old_arbitration_active_summ_by_bonus'][$bonus]});".PHP_EOL;

       $log .= "arbitration_summ = $arbitration_summ = {$rating_by_bonus['max_arbitrage_calc_by_bonus'][$bonus]} - {$rating_by_bonus['arbitration_active_summ_by_bonus'][$bonus]}".PHP_EOL;

       if ( $summa <= $own_summ){
            $sum_own         = $summa;
            $sum_loans       = 0;
            $sum_arbitration = 0;
        } else {
            // сумма больше чем свои средства
            if($summa <= $loans_summ + $own_summ){ // и сумма меньше чем свои средства + займы
                $sum_own         = $own_summ;
                $sum_loans       = $summa - $own_summ;
                $sum_arbitration = 0;
            } else { // и сумма больше чем свои средства + займы
                $sum_own         = $own_summ;
                $sum_loans        = $loans_summ;
                $sum_arbitration = $summa - $own_summ - $loans_summ;
           }



       }

       $result = [
            'sum_own' => max(0,$sum_own),
            'sum_arbitration' => max(0,$sum_arbitration),
            'sum_loan' => max(0,$sum_loans),

            'calc_sum_own' => $own_summ,
            'calc_sum_arbitration' => $arbitration_summ,
            'calc_sum_loan'        => $loans_summ,
        ];

       $this->base_model->log2file($log." result=".print_r($result, true), "get_own_sum_and_loans_and_arbitration");

        return $result;

    }

    public function createInvestArbitration($id_debit, $summa, $id_user, $garant = -1, $bonus = -1, $overdraft = -1, $direct = 0, $old_ratings = null) {
        if($id_user == '90831137') return;
        if($garant == Base_model::CREDIT_GARANT_ON && in_array($bonus,[1,6,7]) ) {

            //$calc = $this->get_own_sum_and_arbitration($id_user,$summa);
            //$this->db->update($this->tableName, array('sum_own' => $calc['sum_own'], 'sum_arbitration' => $calc['sum_arbitration']), array('id' => $id_debit));
            $debit = $this->getDebit($id_debit);
            if(empty($debit))
                return;

            if($debit->arbitration == 2){
                $this->db->update($this->tableName, array('sum_arbitration' => $summa), array('id' => $id_debit));
            } else {
                if ( $bonus==1){
                    $calc = $this->get_bonus_types_calc($id_user, $summa);
                    $this->db->update($this->tableName, array('sum_own' => $calc['sum_new_bonus'], 'sum_loan' => $calc['sum_old_bonus']), array('id' => $id_debit));
                    
                } else {
                    $calc = $this->get_own_sum_and_loans_and_arbitration($id_user,$summa, NULL, $bonus);
                    $this->db->update($this->tableName, array('sum_own' => $calc['sum_own'], 'sum_loan' => $calc['sum_loan'], 'sum_arbitration' => $calc['sum_arbitration']), array('id' => $id_debit));
                }
            }
        }
    }

    public function getDebit4Credit($id){
        return $this->db->get_where($this->tableName, array('debit' => $id))->row();
    }

    public function getDebit4Update($id){
        return $this->db->set_for_update()->get_where($this->tableName, array('id' => $id))->row();
    }

    public function getDebit($id){
        return $this->db->get_where($this->tableName, array('id' => $id))->row();
    }

    #получаем активные гаранты для продажи сертификатов при возврате Арбитража

    public function getShowedAndActiveInvestsNotBonuses($user_id, $order = 'ASC', $bonus = NULL){


        if(empty($bonus) || !is_array($bonus))
            $bonus   = array(0, 2, 5, 6);
        $res_src = $this->db
            ->where_in('state', array('2', '1'))
            ->where_in('bonus', $bonus)
            ->where(array('type'           => Base_model::CREDIT_TYPE_INVEST,
                'id_user'        => $user_id,
                'direct'         => 0,
                'overdraft'      => Base_model::CREDIT_OVERDRAFT_OFF,
                'garant_percent' => 0,
                'arbitration'    => 0
            ))
            ->order_by('summa', $order)
            ->get('credits')
            ->result();

        #if each credit has it's debit
        #new array with ids
        $active_ids    = array();
        $setup_credits = array();
        $credits       = array();
        foreach($res_src as $c){
            if($c->state == 1){
                $setup_credits[$c->id] = $c;
                continue;
            }

            $active_ids[]      = $c->debit;
            $credits[$c->id] = $c;
        }

        $new_credits = array(); //they all has debits
        if(!empty($active_ids)){
            #find all the credits
            $res2 = $this->db->select('debit')
                ->where_in('id', $active_ids)
                ->get('credits')
                ->result();


            foreach($res2 as $c){
                if(!isset($credits[$c->debit]))
                    continue;
                $new_credits[$c->debit] = $credits[$c->debit];
            }
        }

        $res_credits = array_merge($new_credits, $setup_credits);
        return $res_credits;
    }

    public function getShowedCreditsNotBonuses($user_id, $order = 'ASC'){
        $setup_credits = $this->db
            ->where('state', 1)
            ->where_in('bonus', array(0, 2, 5, 6))
            ->where(array('type'           => Base_model::CREDIT_TYPE_CREDIT,
                'id_user'        => $user_id,
                'direct'         => 0,
                'overdraft'      => Base_model::CREDIT_OVERDRAFT_OFF,
                'garant_percent' => 0,
                'arbitration'    => 0,
                'debit ='        => 0,
            ))
            ->order_by('summa', $order)
            ->get('credits')
            ->result();


        return $setup_credits;
    }

    public function getArbitrationCredits($user_id, $next_day = FALSE){
        if(!$user_id)
            return null;

        if($next_day)
            $this->db->where("out_time < '".date('Y-m-d', time())."'");
        else
            $this->db->where("out_time <= '".date('Y-m-d', time())."'");

        return $this->db
                ->where(array('type'        => Base_model::CREDIT_TYPE_CREDIT,
                    'id_user'     => $user_id,
                    'state'       => Base_model::CREDIT_STATUS_ACTIVE,
                    'arbitration' => Base_model::CREDIT_ARBITRATION_ON
                ))
                ->order_by('date  desc')
                ->get('credits')
                ->result();
    }

    public function getOverdueArbitrationCredits($user_id, $bot = FALSE, $order_by_bonus = FALSE){
        if(!$user_id)
            return null;

        if($bot == FALSE){
            $this->db->where('id_user', $user_id)
                ->where('type', Base_model::CREDIT_TYPE_CREDIT);
        } else {
            $this->db->where('debit_id_user', $user_id)
                ->where('type', Base_model::CREDIT_TYPE_INVEST);
        }

        if($order_by_bonus === TRUE)
            $this->db->order_by('bonus  desc');

        return $this->db
                ->where(
                    array('state'           => Base_model::CREDIT_STATUS_ACTIVE,
                        'arbitration'     => Base_model::CREDIT_ARBITRATION_ON,
                        'blocked_money'   => 0,
                        'sum_arbitration' => 0
                ))
                ->where("out_time < '".date('Y-m-d')."'")
                ->order_by('date  desc')
                ->get('credits')
                ->result();
    }

    public function getOverdueArbitrationCredits_cron($user_id, $bot = FALSE, $order_by_bonus = FALSE){
        if(!$user_id)
            return null;


        if($bot === FALSE)
            return FALSE;

        $this->db->where('id_user', $user_id)
            ->where('type', 1)
            ->where('debit_id_user =', 400400);



        if($order_by_bonus === TRUE)
            $this->db->order_by('bonus  desc');

        $res = $this->db
            ->where(
                array('state' => Base_model::CREDIT_STATUS_ACTIVE,
                    'arbitration'     => Base_model::CREDIT_ARBITRATION_ON,
                    'blocked_money'   => 0,
                    'sum_arbitration' => 0
            ))
            //->where("out_time < '2016-02-25'")
            ->where("out_time < '".date('Y-m-d')."'")
            ->order_by('date  desc')
            ->get('credits')
            ->result();


        return $res;
        /**
          SELECT *
          FROM  `credits`
          WHERE state =2
          AND arbitration =1
          AND
          TYPE =1
          AND id_user <>400400
          AND sum_arbitration =0
          AND out_time <  '2016-02-25'
         */
    }

    public function sellCertificate($id = 0){
        if($id == null)
            return array('res' => 1);
        $this->load->model('accaunt_model', 'accaunt');
//        $this->load->model('mail_model','mail');

        $invest = $this->accaunt->getUserCredit($id, false);

        if(empty($invest))
            return array('res' => 2);

        if(Base_model::CREDIT_STATUS_ACTIVE != $invest->state || Base_model::CREDIT_GARANT_ON != $invest->garant){
//            var_dump($invest);
//            echo "<br>";
//            echo "<br>";
            return array('res' => 3);
            //redirect(base_url() . 'account/my_invest');
        }

        if(empty($invest) || 2 == $invest->certificate){
            //accaunt_message($data, 'Ваш сертификат успешно продан');
            return array('res' => 4);
        }

        $this->accaunt->sellCertificate($id);

        $r = $this->checkSellCertificate($id);
        if(false !== $r){
            return array('res' => 5, "error" => $r);
        }

        $replace = array('rate' => countSertificate(4), 'price' => countCertificate($invest->summa, $invest->date_kontract));
        //$this->mail->admin_sender('sell-certificate-admin', $this->accaunt->get_user_id(), $replace, (int) $id);
        $this->mail->user_sender('sell-certificate-user', $invest->id_user, $replace, (int) $id);

        $summ = round(countCertificate($invest->summa, $invest->date_kontract), 2);
        $this->load->model("transactions_model", "transactions");
        $b    = $this->base_model->getBonusTypeByCredit($invest->bonus);
        if($b == 7)
            $b    = 6;
        $this->transactions->addPay($invest->id_user, $summ, Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID, $invest->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $b, "Продажа сертификата № СС$invest->id от ".date('d/m/Yг.'));


        return array('res' => 0, 'summa' => $summ);
    }

    public function checkSellCertificate($i){
        $this->load->model('accaunt_model', 'accaunt');
        if(!is_object($i))
            $i = $this->accaunt->getCredit($i);

        if(empty($i))
            return _e("Заявки не существует");

        if(Base_model::CREDIT_TYPE_INVEST != $i->type)
            return _e("Это не инвестиция");

        if(Base_model::CREDIT_STATUS_PAID != $i->state)
            return _e("Не получилось изменить статус заявки, ошибка БД");

        if(Base_model::CREDIT_CERTIFICAT_PAID != $i->certificate)
            return _e("Не получилось продать сертификат, ошибка БД");

        if(Base_model::CREDIT_GARANT_ON != $i->garant)
            return _e("Это не гарантийный вклад");

        return false;
    }

    public function getExchangeList($date = false){
        $res = [];
        if(false !== $date){
            $res['created'] = $this->db->select("id, bonus, id_user, garant, account_type, account_id, direct, summa, time, percent, out_summ, type, card_id, date, NOW() as max_date", false) // `id`,`id_user`,`date`,`debit`,`previous_debit`,`debit_id_user`,`exchange`,`summ_exchange`,`kontract`,`date_kontract`,`arbitration_invest_pay`,`summa`,`time`,`percent`,`income`,`out_summ`,`out_time`,`payment`,`date_active`,`garant_percent`,`garant`,`overdraft`,`arbitration`,`direct`,`certificate`,`certificate_pay_cause`,`confirm_payment`,`confirm_return`,`active`,`state`,`type`,`blocked_money`,`bonus`,`card_id`,`sum_arbitration`,`sum_own`,`sum_loan`,`account_type`,`account_id`
                ->where('state', Base_model::CREDIT_STATUS_SHOWED)
                ->where('bonus <>', Base_model::CREDIT_BONUS_GARANT_CASH)
                ->where('date_active >=', $date)
                ->get("credits")
                ->result();
            $res['hided'] = $this->db->select('id, NOW() as max_date')->where('created_at >=', $date)
                ->get("credits_exchange_hide")
                ->result();
            $res['timestamp'] = $date;
            $res['timestamp'] = (!empty($res['created'])) ? $res['created'][0]->max_date : $res['timestamp'];
            $res['timestamp'] = (empty($res['created']) && !empty($res['hided'])) ? $res['hided'][0]->max_date : $res['timestamp'];

        } else {
            $res['created'] = $this->db
                ->select("id, bonus, id_user, garant, account_type, account_id, direct, summa, time, percent, out_summ, type, card_id, date, NOW() as max_date", false) // `id`,`id_user`,`date`,`debit`,`previous_debit`,`debit_id_user`,`exchange`,`summ_exchange`,`kontract`,`date_kontract`,`arbitration_invest_pay`,`summa`,`time`,`percent`,`income`,`out_summ`,`out_time`,`payment`,`date_active`,`garant_percent`,`garant`,`overdraft`,`arbitration`,`direct`,`certificate`,`certificate_pay_cause`,`confirm_payment`,`confirm_return`,`active`,`state`,`type`,`blocked_money`,`bonus`,`card_id`,`sum_arbitration`,`sum_own`,`sum_loan`,`account_type`,`account_id`
                ->where('state', Base_model::CREDIT_STATUS_SHOWED)
                ->where('bonus <>', Base_model::CREDIT_BONUS_GARANT_CASH)
                ->get("credits")
                ->result();
            $res['timestamp'] = $res['created'][0]->max_date;
        }
        return $res;

    }

    public function getAllCreditsForApplications($type, $state, &$search_data, $limit, $sort, $filters){
//        $search = trim(htmlspecialchars($params["search"]));

        $r = array("count" => "0", "rows" => [], "sql" => "");

        if ( !is_array($state))
            $state = [$state];

        $use_index = 'birja_new';
        if ( isset($search_data['id_user']))    
            $use_index = 'id_user';
            
        if(!empty($search_data))
            $this->_createFilterForApplications($search_data, 'c.');
        $this->db->where("c.bonus != 9");
        $cnt = $this->db
            ->select( 'COUNT(c.id) cc' )
            ->join('users u','c.id_user=u.id_user AND u.state !=3','inner')
            ->where(  'c.type',$type)
            ->where_in('c.state', $state)
            ->get("credits c use index ($use_index)")
            ->row("cc");


        //if ( get_current_user_id() == 92156962){var_dump($search_data);var_dump($this->db->last_query());}
        //var_dump($search_data);
        //var_dump($this->db->last_query());
//        if( isset( $order ) ) $this->createOrder($order);
        if(!empty($search_data))
            $this->_createFilterForApplications($search_data,"c.");
        if(!empty($sort)){
            $this->db->order_by($sort[0]['column'], $sort[0]['dir']);
        } else {
            $this->db->order_by('c.bonus', 'desc');
        }

        $search_data["total_count"] = $cnt;
        $this->db->where("c.bonus != 9");
        $r = $this->db
            ->select("c.id, c.bonus, c.id_user, c.garant, c.account_type, c.account_id, c.direct, c.summa, c.time, c.percent, c.out_summ, c.type, c.state, c.card_id, c.debit, c.debit_id_user, c.out_time", false)
			//, CASE  WHEN (direct = 1 AND garant = 1) THEN 'GD' WHEN (direct = 1 AND garant = 0) THEN 'SD' ELSE garant END as old_type,  do directa
            ->join('users u','c.id_user=u.id_user AND u.state !=3','inner')
            ->where(  'c.type',$type)
            ->where_in('c.state', $state)
            ->limit($limit[0], $limit[1])
            ->get("credits c use index ($use_index)")
            ->result();

        //if ( get_current_user_id() == 92156962){var_dump($search_data);var_dump($this->db->last_query());}
        return $r;
    }

    public function getIp4DiffAccaunts4Credits($ips){
        $ips                 = array_keys($ips);
        $q                   = "SELECT COUNT(id_user) `count`, user_ip
              FROM credits
              WHERE user_ip IN('".implode("', '", $ips)."')
              GROUP BY user_ip
              HAVING `count` > 1";
        $res                 = $this->db->query($q)->result();
        $resp                = array();
        foreach($res as $row)
            $resp[$row->user_ip] = $row->count;
        return $resp;
    }

    public function isCreditsExistsByIpId($ipsIds){
        if(empty($ipsIds) || !is_array($ipsIds))
            return array();
        $this->load->model('credits_model', 'credits');
        foreach($ipsIds as $ip => $id){
            $whereClause[] = "( id_user = $id AND user_ip LIKE '%$ip%' )";
        }

        $resp = $this->db
            ->select(array('id', 'user_ip', 'id_user'))
            ->where('('.implode(' OR ', $whereClause).')')
            ->where(array('state'       => Base_model::CREDIT_STATUS_ACTIVE,
                'type'        => Base_model::CREDIT_TYPE_CREDIT,
                'garant'      => Base_model::CREDIT_GARANT_OFF,
                'direct'      => Credits_model::DIRECT_OFF,
                'arbitration' => Base_model::CREDIT_ARBITRATION_OFF))
            ->group_by(array('id_user', 'user_ip'))
            ->get("credits")
            ->result();

//        echo $this->db->last_query();
//        echo implode(' OR ', $whereClause);
//        var_dump($resp);
        if(empty($resp))
            return array();

        $newIpsIds = array();
        foreach($resp as $val){
            if($val->user_ip == 0 || $val->user_ip == null)
                continue;

            $ips = explode(',', $val->user_ip);
            $ip  = $ips[0];

            if(!isset($ip))
                continue;

            $newIpsIds[$ip] = $val->id_user;
        }
//        var_dump( $newIpsIds );
        return $newIpsIds;
    }

    public function hasActiveInvests($user_id){
        if(empty($user_id))
            return FALSE;

        $result = $this->db
            ->select('id')
            ->where("id_user = $user_id AND type = ".Base_model::CREDIT_TYPE_INVEST." AND ".
                "( state = ".Base_model::CREDIT_STATUS_ACTIVE." OR ".
                "state = ".Base_model::CREDIT_STATUS_WAITING.")"
            )
            ->limit(1)
            ->get('credits')
            ->row();

//        var_dump($result);
        if(empty($result) || !is_object($result))
            return FALSE;

        return TRUE;
    }

    public function hasBorrows($user_id){
        if(empty($user_id))
            return FALSE;

        $result = $this->db
            ->select('id')
            ->where(array('id_user'     => $user_id,
                'type'        => Base_model::CREDIT_TYPE_CREDIT,
                'arbitration' => Base_model::CREDIT_ARBITRATION_OFF))
            ->where('( state = '.Base_model::CREDIT_STATUS_ACTIVE.' OR state = '.Base_model::CREDIT_STATUS_SHOWED.' )')
            ->limit(1)
            ->get('credits')
            ->row();

//        echo $this->db->last_query();
//        var_dump( $result );

        if(empty($result) || !is_object($result))
            return FALSE;

        return TRUE;
    }

    public function hasActiveArbitrageBorrows($user_id){
        if(empty($user_id))
            return FALSE;

        $result = $this->db
            ->select('id')
            ->where(array('id_user'     => $user_id,
                'type'        => Base_model::CREDIT_TYPE_CREDIT,
                'state'       => Base_model::CREDIT_STATUS_ACTIVE,
                'arbitration' => Base_model::CREDIT_ARBITRATION_ON))
            ->limit(1)
            ->get('credits')
            ->row();

//        echo $this->db->last_query();
//        var_dump( $result );

        if(empty($result) || !is_object($result))
            return FALSE;

        return TRUE;
    }

    public function updateCreditOuttimeOutsumIncome($own, $out_time, $time_days, $out_summ, $income){
        if(
            empty($own) ||
            empty($out_time) ||
            //empty($time_days) ||
            empty($out_summ) //||empty($income)
        )
            return FALSE;

        $data  = array('out_time' => $out_time,
            'time'     => (string) $time_days,
            'out_summ' => (string) $out_summ,
            'income'   => (string) $income
        );
        $where = array('id' => $own->id, 'debit' => $own->id);

        $this->db
            ->or_where($where)
            ->update('credits', $data);

//        echo $this->db->last_query();
        return TRUE;
    }

    public function getNewCreditSumm($id){
        if(
            empty($id) ||
            !is_numeric($id)
        )
            return FALSE;

        $this->load->model('accaunt_model', 'accaunt');

        $own = $this->accaunt->getCredit($id);


        if(empty($own))
            return FALSE;

        $offer = $this->accaunt->getCredit($own->debit);

        if(empty($offer))
            return FALSE;

        if(Base_model::CREDIT_GARANT_ON == $own->garant &&
            Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND == $offer->id_user){
            $this->load->helper('admin');
            $this->load->model('credits_model', 'credits');

            $time_days = calculate_debit_time_now($own->date_kontract);
            $out_summ  = credit_summ($own->percent, $own->summa, $time_days);
            return $out_summ;
        }

        return FALSE;
    }

    public function getUserStandartCredit($id_user){
        if(empty($id_user))
            return FALSE;

        $credits = $this->db->where("id_user = $id_user AND ( state = ".Base_model::CREDIT_STATUS_SHOWED." OR state = ".Base_model::CREDIT_STATUS_ACTIVE." ) ".
                " AND garant = 0 AND direct = 0 AND type = ".Base_model::CREDIT_TYPE_CREDIT)
            ->limit(1)
            ->get($this->tableName)
            ->result();

        if(empty($credits))
            return FALSE;

        return $credits;
    }

    /**
     * Find and payoff all overdue credits garant of the user
     *
     * @param type $user_id
     */
    public function findAndPayoffOverdueCreditGarant($user_id = null){
        $this->load->model('accaunt_model', 'accaunt');
        $where_and = array(
            'state'     => Base_model::CREDIT_STATUS_ACTIVE,
            'type'      => Base_model::CREDIT_TYPE_INVEST,
            'garant'    => Base_model::CREDIT_GARANT_ON,
            'overdraft' => Base_model::CREDIT_OVERDRAFT_OFF,
            'direct'    => self::DIRECT_OFF,
//                        'out_time <' => date('Y-m-d')
        );

        if($user_id != null)
            $where_and['id_user'] = $user_id;

        $credits = $this->db->where($where_and)
            ->order_by('out_time', 'ASC')
            ->get($this->tableName)
            ->result();

        if(empty($credits))
            return 0;

        foreach($credits as $c){

            $res = $this->payoffOverdueCreditGarant($c);
            if(FALSE === $res){
                echo "Can't payoff credit id {$c->id}";
                return;
            }
        }
        return 0;
    }

    public function findOverdueCreditGarant($credit_id){
        if(empty($credit_id))
            return FALSE;

        $where_and = array('id'         => $credit_id,
            'state'      => Base_model::CREDIT_STATUS_ACTIVE,
            'type'       => Base_model::CREDIT_TYPE_CREDIT,
            'garant'     => Base_model::CREDIT_GARANT_ON,
            'overdraft'  => Base_model::CREDIT_OVERDRAFT_OFF,
            'direct'     => self::DIRECT_OFF,
            'out_time <' => date('Y-m-d')
        );

        $credit = $this->db->where($where_and)
            ->limit(1)
            ->get($this->tableName)
            ->result();
        if(empty($credit) || !isset($credit[0]))
            return 0;


        return $credit[0];
    }

    #возврат любого арбитража

    public function payoffArbitration($user_id){
        define('EOL', '<br>');

        if(empty($user_id))
            return FALSE;

        $test = 0;

        $this->load->model('accaunt_model', 'accaunt');
        //$user_id = $arbitration->id_user;
        $rating = $this->accaunt->recalculateUserRating($user_id);

        if(empty($rating))
            return 1; //there is no rating data

        $this->load->model('arbitration_model', 'arbitration');

        $pay_off_arbitration = $rating['pay_off_arbitration'];

        echo "payoffArbitration: user {$user_id}; amount: {$pay_off_arbitration}USD".EOL;
        if($pay_off_arbitration <= 0){
            echo "exit".EOL.EOL;
            return array('status' => 21, 'payed_off' => 0); //zero
        }

        $active_credits = $this->getUserActiveArbitration($user_id);

        var_dump($active_credits);
        die;
        if(empty($active_credits))
            return FALSE;


        $last_summ       = 0;
        $a               = 0;
        $pay_off_credits = array();
        $payed_off       = 0;
        do{
            //остался единственный вклад
            if(count($active_credits) == 1 && $pay_off_arbitration > 0){
                foreach($active_credits as $credit){
                    $debit400400 = $this->accaunt->getCredit($credit->debit);
                    echo "single $credit->summa ~ $pay_off_arbitration<br>";

                    if($test){
                        $res = 0;
                    } else {
                        echo "backArbitrationLoans<br>";
                        //$each_item_fnc = null, $test = FALSE, $recalculate_arbitration = FALSE
                        $res = $this->arbitration->backArbitrationLoans(array($debit400400), null, FALSE, TRUE);
                    }

                    if($res === 0){

                        echo "PAYED: {$credit->id}<br/>";

                        $rating_before = $rating;

                        $rating              = $this->accaunt->recalculateUserRating($user_id);
                        if($rating == FALSE)
                            return FALSE;
                        $payed_off += $credit->summa;
                        $pay_off_credits[]   = array('id'                        => $credit->id, 'summa'                     => $credit->summa,
                            'investments_garant_before' => $rating_before['investments_garant'],
                            'investments_garant_after'  => $rating['investments_garant']);
                        $pay_off_arbitration = $rating['pay_off_arbitration'];
                    }else {
                        echo "Res:";
                        var_dump($res);
                    }
                    break;
                }

                echo "<br>Sell one $credit->id out_summ: $pay_off_arbitration<br>";
                break;
            }
            $last_summ = $pay_off_arbitration;
            //перебираем все вклады
            $min       = -1;
            $min_key   = -1;
            foreach($active_credits as $key => $credit){
                if($min == -1 || $min > $credit->summa){
                    $min     = $credit->summa;
                    $min_key = $key;
                }
                $debit400400 = $this->accaunt->getCredit($credit->debit);

                echo "all $credit->summa < $pay_off_arbitration<br>";
                if($credit->summa > $pay_off_arbitration)
                    continue;

                echo "all $credit->summa > $pay_off_arbitration<br>";

                if($test){
                    $res = 0;
                } else {
                    $res = $this->arbitration->backArbitrationLoans(array($debit400400), null, FALSE, TRUE);
                }

                if($res == 0){

                    echo "PAYED: {$credit->id}<br/>";
                    $rating_before = $rating;

                    $rating            = $this->accaunt->recalculateUserRating($user_id);
                    if($rating == FALSE)
                        return FALSE;
                    $payed_off += $credit->summa;
                    $pay_off_credits[] = array('id'                        => $credit->id, 'summa'                     => $credit->summa,
                        'investments_garant_before' => $rating_before['investments_garant'],
                        'investments_garant_after'  => $rating['investments_garant']);

                    $pay_off_arbitration = $rating['pay_off_arbitration'];
                    unset($active_credits[$key]);

                    echo "<br>Sell2 {$credit->id} out_summ: $pay_off_arbitration<br>";
                }
            }
            echo "<br>";
            //после перебора сумма не изменилась
            echo "$last_summ == $pay_off_arbitration<br>";
            if($last_summ == $pay_off_arbitration && $pay_off_arbitration > 0){
                $debit400400 = $this->accaunt->getCredit($active_credits[$min_key]->id);

                if($test){
                    $res = 0;
                } else {
                    $res = $this->arbitration->backArbitrationLoans(array($debit400400), null, FALSE, TRUE);
                }

                if($res === 0){
                    echo "PAYED: {$credit->id}<br/>";

                    $rating_before = $rating;

                    $rating            = $this->accaunt->recalculateUserRating($user_id);
                    if($rating == FALSE)
                        return FALSE;
                    $payed_off += $credit->summa;
                    $pay_off_credits[] = array('id'                        => $credit->id, 'summa'                     => $credit->summa,
                        'investments_garant_before' => $rating_before['investments_garant'],
                        'investments_garant_after'  => $rating['investments_garant']);

                    $pay_off_arbitration = $rating['pay_off_arbitration'];
                    unset($active_credits[$min_key]);

                    echo "<br>Sell2 {$credit->id} out_summ: $pay_off_arbitration<br>";
                }

                if($pay_off_arbitration <= 0){
                    break;
                }
            }
            $a++;
            echo "<br>i = $a<br>";
            //запоминаем изменение суммы
            $last_summ = $pay_off_arbitration;
            if($a >= 20){
                echo "Exit 10000 arbitration";
                break;
            }
        } while($pay_off_arbitration > 0 || $a < 5);

        var_dump($pay_off_credits);
        $pay_off_credits['payed_off'] = $payed_off;
        return $pay_off_credits;
    }

    public function getUserActiveArbitration($user_id){
        if(empty($user_id)){
            return FALSE;
        }

        $where_and = array(
            'state'       => Base_model::CREDIT_STATUS_ACTIVE,
            'type'        => Base_model::CREDIT_TYPE_CREDIT,
            'garant'      => Base_model::CREDIT_GARANT_OFF,
            'arbitration' => Base_model::CREDIT_ARBITRATION_ON,
            'overdraft'   => Base_model::CREDIT_OVERDRAFT_OFF,
            'direct'      => self::DIRECT_OFF,
            'id_user'     => $user_id
//                        'out_time <' => date('Y-m-d')
        );



        $credits = $this->db->where($where_and)
            ->order_by('out_time', 'ASC')
            ->get($this->tableName)
            ->result();

        return $credits;
    }

    public function payoffOverdueCreditGarant($credit_darant){

        if(empty($credit_darant) || !is_object($credit_darant))
            return FALSE;

        $user_id = $credit_darant->id_user;
        $rating  = $this->accaunt->recalculateUserRating($credit_darant->id_user);
        $this->load->model('arbitration_model', 'arbitration');

        if(empty($rating))
            return 1; //there is no rating data

        $pay_off_arbitration = $rating['pay_off_arbitration'];
        echo $pay_off_arbitration.'!';

        if($pay_off_arbitration > 0){
            $active_credits = $this->getUserActiveArbitration($user_id);

            if(empty($active_credits))
                return FALSE;

//            var_dump( $active_credits );
            $last_summ = 0;
            $a         = 0;
            do{
                //остался единственный вклад
                if(count($active_credits) == 1 && $pay_off_arbitration > 0){
                    foreach($active_credits as $credit){

                        echo "single $credit->summa > $pay_off_arbitration<br>";

                        $res = $this->arbitration->backArbitrationLoans($credit->id);

                        if($res === 0){
                            $rating              = $this->accaunt->recalculateUserRating($user_id);
                            if($rating == FALSE)
                                return FALSE;
                            $pay_off_arbitration = $rating['pay_off_arbitration'];
                        }
                        break;
                    }

                    echo "<br>Sell one $credit->id out_summ: $pay_off_arbitration<br>";
                    break;
                }

                //перебираем все вклады
                $min     = -1;
                $min_key = -1;
                foreach($active_credits as $key => $credit){
                    if($min == -1 || $min > $credit->summa){
                        $min     = $credit->summa;
                        $min_key = $key;
                    }

                    echo "all $credit->summa < $pay_off_arbitration<br>";
                    if($credit->summa > $pay_off_arbitration)
                        continue;

                    echo "all $credit->summa > $pay_off_arbitration<br>";

                    $res = $this->arbitration->backArbitrationLoans($credit->id);

                    if($res == 0){
                        $rating = $this->accaunt->recalculateUserRating($user_id);
                        if($rating == FALSE)
                            return FALSE;

                        $pay_off_arbitration = $rating['pay_off_arbitration'];
                        unset($active_credits[$key]);

                        echo "<br>Sell2 {$credit->id} out_summ: $pay_off_arbitration<br>";
                    }
                }
                echo "<br>";
                //после перебора сумма не изменилась
                echo "$last_summ == $pay_off_arbitration<br>";
                if($last_summ == $pay_off_arbitration && $pay_off_arbitration > 0){

                    $res = $this->arbitration->backArbitrationLoans(array($active_credits[$min_key]));

                    if($res === 0){
                        $rating = $this->accaunt->recalculateUserRating($user_id);
                        if($rating == FALSE)
                            return FALSE;

                        $pay_off_arbitration = $rating['pay_off_arbitration'];
                        unset($active_credits[$min_key]);

                        echo "<br>Sell2 {$credit->id} out_summ: $pay_off_arbitration<br>";
                    }
                }
                $a++;
                echo "<br>i = $a<br>";
                //запоминаем изменение суммы
                $last_summ = $pay_off_arbitration;
                if($a >= 20){
                    echo "Exit 10000 arbitration";
                    break;
                }
            } while($pay_off_arbitration > 0 || $a < 5);
        }
    }

    public function countActiveORPayedOrders($id_user, $type){
        return $this->db
                ->where("id_user = $id_user AND type = $type AND (state = ".Base_model::CREDIT_STATUS_ACTIVE." OR state = ".Base_model::CREDIT_STATUS_PAID.")")
                ->count_all_results($this->tableName);
    }

    public function getSummAllStandart($id_user = null){
        $this->load->model("users_model", 'users');
        if(null == $id_user)
            $id_user = $this->users->getCurrUserId();
        return $this->db
                ->select("SUM(summa) as summa")
                ->where("type = 1 and state = 2 and garant = 0 and arbitration = 0 and id_user = $id_user")
                ->get($this->tableName)
                ->row("summa");
    }

    public function get_active_loan_and_invests($user_id = NULL, $bonuses = []){
        if(empty($user_id))
            $user_id = get_current_user_id();

        if(!empty($bonuses))
            $this->db->where_in('bonus', $bonuses);

        return $this->db
                ->where('id_user', $user_id)
                ->where_in('type', [1])
                ->where('state', Base_model::CREDIT_STATUS_ACTIVE)
                ->count_all_results($this->tableName);
    }

    public function get_active_loans($user_id = NULL, $bonuses = [], $where = ''){
        if(empty($user_id))
            $user_id = get_current_user_id();

        if(!empty($bonuses))
            $this->db->where_in('bonus', $bonuses);
        if(!empty($where))
            $this->db->where($where);

        return $this->db
                ->where('id_user', $user_id)
                ->where_in('type', [1])
                ->where('state', Base_model::CREDIT_STATUS_ACTIVE)
                ->where('arbitration', 0)
                ->get($this->tableName)->result();
    }

    public function get_active_invests($user_id = NULL, $bonuses = [], $where = ''){
        if(empty($user_id))
            $user_id = get_current_user_id();

        if(!empty($bonuses))
            $this->db->where_in('bonus', $bonuses);
        if(!empty($where))
            $this->db->where($where);

        return $this->db
                ->where('id_user', $user_id)
                ->where_in('type', [2])
                ->where('state', Base_model::CREDIT_STATUS_ACTIVE)
                //->where('arbitration', 0)
                ->get($this->tableName)->result();
    }

    public function checkBonus($credit){
        $this->load->model("transactions_model", "transactions");
        $social_bonuses_today = $this->transactions->get_social_bonuses_today_count($user_id);
        if($social_bonuses_today == 0 && substr($credit->payment, 0, 10) == 'soc_bonus='){
            $summ = (int) substr($credit->payment, 10);
            if($summ > 0)
                $trId = $this->transactions->addPay($credit->id_user, $summ, 92, 93, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, 'Партнерский Бонус');
        }
    }

    /*
    public function get_cards_credits_limits($user_id){
       $active_card_invests = $this->get_active_invests($user_id, [7] );
       $showed_card_credits = $this->db
            ->where('id_user', $user_id)
            ->where('bonus', 7)
            ->where_in('type', [1])
            ->where_in('state', [Base_model::CREDIT_STATUS_SHOWED,Base_model::CREDIT_STATUS_ACTIVE])
            //->where('arbitration', 0)
            ->get($this->tableName)->result();


       // удалим уже заявленные
       foreach ($active_card_invests as $iidx=>$invest){
        foreach ($showed_card_credits as $cidx=>$credit){

            $adays = ceil((strtotime($invest->out_time)-strtotime($credit->date) )/60/60/24) - 1;
            if ( $invest->summa == $credit->summa && $credit->time<=$adays){
                unset( $active_card_invests[$iidx]);
                unset( $showed_card_credits[$cidx]);
                //echo "unset ".$invest->id;
                break;

            }
        }
       }


       $card_credit_params = [];
        foreach ($active_card_invests as $invest){
            $adays = ceil((strtotime($invest->out_time)-time())/60/60/24) - 1;
                for ( $d = 3; $d <= $adays; $d++)
                  if( !in_array($d, $card_credit_params[(float)$invest->summa])){
                      //echo "$d ".print_r($card_credit_params[$invest->summa], true);
                    $card_credit_params[(float)$invest->summa][]  = $d;
                  }
            //if ( $adays >= 3 && !in_array($adays, $card_credit_params[$invest->summa]) )
              //  $card_credit_params[(float)$invest->summa][]  = $adays;
        }

        foreach ($card_credit_params as $sm=>&$days){
            sort($days);
        }
        //var_dump($card_credit_params);

        return $card_credit_params;
    }
*/
    public function get_cards_credits_limits($user_id){
       $active_card_invests = $this->get_active_invests($user_id, [7], '', ['summa','asc']);
       $showed_card_credits = $this->db
            ->where('id_user', $user_id)
            ->where('bonus', 7)
            ->where_in('type', [1])
            ->where_in('state', [Base_model::CREDIT_STATUS_SHOWED,Base_model::CREDIT_STATUS_ACTIVE])
            ->order_by('summa', 'desc')
            //->where('arbitration', 0)
            ->get($this->tableName)->result();


       // удалим уже заявленные
       foreach ($active_card_invests as $iidx=>$invest){
        foreach ($showed_card_credits as $cidx=>$credit){

            $adays = ceil((strtotime($invest->out_time)-strtotime($credit->date) )/60/60/24) - 1;
            if ( $invest->summa >= $credit->summa && $credit->time<=$adays){

                if ( $invest->summa == $credit->summa ){
                    unset( $active_card_invests[$iidx]);
                    unset( $showed_card_credits[$cidx]);
                    //echo "unset ".$invest->id;
                    break;
                } else {
                    $invest->summa -= $credit->summa;
                    //echo "less to {$invest->summa} id=".$invest->id;
                }


            }
        }
       }


       $card_credit_params = [];
        foreach ($active_card_invests as $invest){
            $adays = ceil((strtotime($invest->out_time)-time())/60/60/24) - 1;
                for ( $d = 3; $d <= $adays; $d++)
                    for ( $s = 50; $s <= $invest->summa; $s+=50)
                        if( !in_array($d, $card_credit_params[(float)$s])){
                            $card_credit_params[(float)$s][]  = $d;
                  }
            //if ( $adays >= 3 && !in_array($adays, $card_credit_params[$invest->summa]) )
              //  $card_credit_params[(float)$invest->summa][]  = $adays;
        }

        foreach ($card_credit_params as $sm=>&$days){
            sort($days);
        }
        //var_dump($card_credit_params);

        return $card_credit_params;
    }
    
    
    
    public function get_bonus_types_calc($user_id, $summa){
        
        
        
        $new_bonuses_total = $this->db->select('sum(summa) as sm')->where(['id_user'=>$user_id, 'type'=>900,'bonus'=>1])->get('transactions')->row('sm');
        $new_bonuses_used = $this->db->select('sum(sum_own) as sm')->where_in('state',[1,2,5])->where(['id_user'=>$user_id,'type'=>2,'bonus'=>1])->get('credits')->row('sm');
        
        $new_bonuses_total = empty($new_bonuses_total)?0:$new_bonuses_total;
        $new_bonuses_used = empty($new_bonuses_used)?0:$new_bonuses_used;
        
        $new_bonuses_summ = max(0,$new_bonuses_total - $new_bonuses_used);   
        
        
        $r = ['sum_new_bonus' => $new_bonuses_summ,
               'sum_old_bonus' => max(0,$summa - $new_bonuses_summ)
            ];
        
        dev_log("get_bonus_types_calc($user_id, $summa) ".var_export($r, true), 'get_bonus_types_calc');
        return $r;
    }
                        
                    

}
