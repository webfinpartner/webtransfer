<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Fincore_model extends CI_Model{

    public $tableName = "send_money_protection";

    public function __construct(){
        parent::__construct();
        $this->load->model('Accaunt_model', 'accaunt');
        $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша
        require_once APPPATH.'controllers/user/Security.php';
    }

    private function return_object_error($message){
        return $this->return_object($message, 'error');
    }

    private function return_object($message, $status = 'success'){
        $r          = new stdClass();
        $r->status  = $status;
        $r->message = $message;
        return $r;
    }

    private function return_data($data, $status = 'success'){
        $data['status'] = $status;
        return (object) $data;
    }

    public function canInvest($user_ratings, &$error, $type = null){
        $data = new stdClass();
        if(!$this->accaunt->isUserAccountVerified() && $type != 'invest'){
            $error = sprintf(_e('Для проведение данной операции Вам необходимо верифицировать <a href="%s">Профиль</a>.'),site_url('account/profile'));
            return FALSE;
        } else
        if($user_ratings['overdue_garant_count'] > 0){
            $error = _e('Для проведение данной операции Вам необходимо погасить просроченные гарантированные займы.');
            return FALSE;
        } else if($user_ratings['overdue_standart_count'] > 0){
            $error = _e('Для проведение данной операции Вам необходимо погасить просроченные стандартные займы.');
            return FALSE;
        }
        return TRUE;
    }

    private function preCreateInvest($user_ratings, $summa, $bonus, &$error, $use_arbitr_summ = FALSE){
        if($bonus == 7){
            $error = _e('Бонус 7 не должен здесь быть');
            return FALSE;
        }

        $payment_account = $user_ratings['all_advanced_invests_summ_by_bonus'][$bonus] + $user_ratings['all_advanced_standart_invests_summ_by_bonus'][$bonus] + $summa;

        $add_summ = 0;
        if($use_arbitr_summ && $bonus == 6){
            $add_summ = $user_ratings['max_arbitrage_calc_by_bonus'][$bonus] - $user_ratings['arbitration_active_summ_by_bonus'][$bonus];
            if($summa > $add_summ){
                $error = _e('На вашем платежном счете недостаточно средств для совершения данной операции.');
                return FALSE;
            }
            return TRUE;
        }




        if($payment_account > $user_ratings['payment_account_by_bonus'][$bonus]){
            $error = _e('На вашем платежном счете недостаточно средств для совершения данной операции.'); //.($use_arbitr_summ?'t':'f')." $payment_account {$user_ratings['payment_account_by_bonus'][$bonus]} + $add_summ";
            return FALSE;
        } else if($payment_account > $user_ratings['payment_account_by_bonus'][$bonus] - $user_ratings['total_processing_payout_by_bonus'][$bonus]){
            $error = _e('На вашем платежном счете недостаточно средств для совершения данной операции.<br/>У вас есть заявки на вывод со статусом "В процессе".');
            return FALSE;
        }
        return TRUE;
    }

    public function invest_request($user_id, $summa, $time, $percent, $count, $garant, $overdraft, $direct, $payment_account_type, $payment_account, $is_ccreds_fee, $is_usd6creds_fee, $is_usd2creds_fee = FALSE, $use_arbitr_summ = FALSE, $use_card_arbitr_summ = 'none'){
        $this->load->model('credits_model', 'credits');
        $this->load->model('Card_model', 'card');
        $this->load->helper('form');
        $this->load->library('form_validation');

        #нельзя вкладывать деньги с просроченного арбитража
        $this->load->model('credits_model', 'credits');
        $credits = $this->credits->getOverdueArbitrationCredits($user_id);

        if(!empty($credits))
            return $this->return_object_error(_e('Для проведение данной операции Вам необходимо погасить просроченный(е) займ(ы) на Арбитраж.'));
        #/нельзя вкладывать деньги с просроченного арбитража

        $partner_fund = $payment_account_type == 'pa' && $payment_account == 3;
        $c_creds_fund = $payment_account_type == 'pa' && $payment_account == 4;
        $bonus        = ($payment_account_type == 'pa' && $payment_account == 1) ? 1 : 0;

        $form_validate = ($partner_fund == 1) ? 'credit_partner' : 'credit';

        $user_ratings = $this->accaunt->recalculateUserRating($user_id);


        $remove_creds_summ6 = round($user_ratings['garant_received'] * 4 - $user_ratings['my_investments_garant_by_bonus'][6], 2);
        $calc               = $this->credits->get_own_sum_and_loans_and_arbitration($user_id, 0);


        if(!is_numeric($payment_account))
            return $this->return_object_error(_e('Не выбран счет'));


        if ( !empty($use_card_arbitr_summ) && $use_card_arbitr_summ != 'none')
            $use_arbitr_summ = TRUE;



        $card_id             = NULL;
        $credit_account_type = 'bonus';
        $credit_account_id   = NULL;
        $account_type        = $payment_account_type;
        // поставим типы и ИД для счета
        // КАРТА
        if($account_type == 'card'){
            $card_id             = (int) $payment_account;
            $set_account_bonus   = 7;
            $credit_account_type = 'card';
            // СВОЙ СЧЕТ
        } elseif($account_type == 'own_account'){
            $credit_account_id = (int) $payment_account;
            $set_account_bonus = 6;
            $own_acc           = $this->card->getUserOther($credit_account_id, $user_id);

            if(empty($own_acc))
                return $this->return_object_error(_e('Неверный счет'));


            if($own_acc->summa < $summa)
                return $this->return_object_error(_e('Недостаточно средств на счете'));


            if($own_acc->account_type == 'E_WALLET')
                $credit_account_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
            else
                $credit_account_type = $own_acc->account_type;

            // ВСЕ ОСТАЛЬНОЕ
        } else {
            $set_account_bonus = $payment_account;
        }



        if($set_account_bonus == 5)
            return $this->return_object_error(_e('Неверный счет'));

        /* if ( $credit_account_type=='bonus' && $set_account_bonus == 2)
          return $this->return_object_error( _e('Неверный счет') );TODO: hide 2 */


        if($this->canInvest($user_ratings, $error, 'invest1') === FALSE)
            return $this->return_object_error($error);

        if($garant == Base_model::CREDIT_GARANT_OFF && Security::checkSecurity($user_id, TRUE, TRUE) !== false)
            return $this->return_object_error(_e('Неверный код.'));




        if($summa < 50)
            return $this->return_object_error(_e('Сумма должна быть $50 и более.'));




        $percent    = floatval($percent);  //   проверить  на  верность  данных
        $stat       = getStatistic();
        $bonus_psnt = (0.5 > $stat->today->avg_rate) ? 0.5 : round($stat->today->avg_rate, 1);

        // check for Canada and USA
        /*
          if ($data->isUS2USorCA && $bonus_psnt > 0.5 ){
          accaunt_message($data, _e('Нельзя  устанавливать процентную ставку более 0.5 для жителей США и Канады'), 'error');
          redirect(site_url('account/my_invest'));
          return;
          } */

        //if($bonus_psnt > $percent &&  $bonus == Base_model::CREDIT_BONUS_ON ){
        //  return  $this->return_object_error(_e('Нельзя поставить процент ниже среднего для бонусных вкладов'));



        $overdraft = (int) $overdraft;
        $direct    = (int) $direct;

        if($overdraft > 0)
            return $this->return_object_error(_e('Опция Овердрафт временно отключена'));

        if($time > 10 && $bonus == Base_model::CREDIT_BONUS_ON){
            $_POST['time'] = 10;
            $time          = 10;
        }

        $countInvests = (int) $count;
        $countInvests = (empty($countInvests)) ? 1 : $countInvests;
        $countInvests = (1 > $countInvests) ? 1 : $countInvests;

        if($countInvests > 10)
            return $this->return_object_error(_e('Превышен лимит создания заявок'));

        if($percent > 3.0)
            return $this->return_object_error(_e('Процент может быть не более 3'));

        if($use_arbitr_summ && $use_card_arbitr_summ != 'rating' && $time > $user_ratings['max_arbitrage_days_by_bonus'][$set_account_bonus])
            return $this->return_object_error(_e('Неверное количество дней.'));

        // проверим баланс карты и статус владельца
        $qiwi_income_summ = 0;
        if(!empty($card_id)){
            $this->load->model('Card_model', 'card');
            $card    = $this->card->getUserCard($card_id);
            $balance = $this->card->getCardBalance($card);
            if ( $use_card_arbitr_summ != 'rating' ){
                if( !$use_arbitr_summ && $balance < ($summa * $countInvests + $user_ratings['showed_cards_invests']))
                    return $this->return_object_error(_e('На карте недостаточно средств')."($balance $card_id )");

                if ( $use_arbitr_summ  && $summa * $countInvests > ($user_ratings['max_arbitrage_calc_by_bonus'][7]- $user_ratings['arbitration_active_summ_by_bonus'][7]) )
                    return $this->return_object_error(_e('У вас недосточно арбитражных средств'));
            } else {
                   $this->load->model('User_arbitration_scores_model', 'user_arbitration_scores');
                   $arbitration_scores = $this->user_arbitration_scores->get_scores_summ($user_id, TRUE)*3;
                   if ( $countInvests > 1)
                        return $this->return_object_error(_e('Нельзя подать больше 1 заявки для этого типа вклада'));

                   if ( $summa > $arbitration_scores || $time > floor($arbitration_scores / $summa) ){
                        return $this->return_object_error(_e('Неверная сумма или количество дней'));
                   }
            }



            $holderInfo = $this->card->getCardHolderInfo($card);
            if($holderInfo['status'] == 'error'){
                return $this->return_object_error(_e('Не удалось получить информацию по владельцу карты'));
            } else {
                if($holderInfo['result']['statusCode'] != 'ACTIVE')
                    return $this->return_object_error(_e('Карта не активна'."({$holderInfo['result']['statusCode']})"));
            }
            if($is_usd2creds_fee && !empty($card)){
                $result = $this->card->search_transactions($card, [
                    'fields' => [
                        'comment'  => ['type' => 'regexp', 'match' => '/.*(MYCUS000|top-up).*/'],
                        'txnType'  => ['type' => 'compare', 'compare_type' => 'equal', 'value' => 'Load'],
                        'status'   => ['type' => 'compare', 'compare_type' => 'equal', 'value' => 'Success'],
                        'tranDate' => ['type' => 'date_compare', 'compare_type' => 'better_equal', 'value' => '2016-02-10 00:00:00']
                    ]
                ]);

                if(!empty($result)){
                    foreach($result as $r)
                        $qiwi_income_summ += round($r['transactionAmount'] / 100, 2);
                }
                $qiwi_income_summ -= $user_ratings['total_active_usd2_fee_invests'];
            }
        }



        $outPay = credit_summ($percent, $summa, $time);
        $income = ($outPay - $summa);

        $this->load->model('accaunt_model', 'accaunt');
        $remove_creds_summ2 = $this->accaunt->get_remove_creds_summ2($user_id);



        $direct     = ($account_type == 'card' || $account_type == 'own_account' );
        $total_summ = 0;
        for($ii = 1; $ii <= $countInvests; $ii++){
            $total_summ += $summa;
            //гарант && деньги
            if($garant == Base_model::CREDIT_GARANT_ON && $bonus == Base_model::CREDIT_BONUS_OFF){
                //direct
                if(($overdraft == Base_model::CREDIT_OVERDRAFT_OFF) && ( $direct == Credits_model::DIRECT_ON)){
                    $avail_summ = $user_ratings['availiable_garant_by_bonus'][$set_account_bonus] - $user_ratings['all_advanced_invests_summ_by_bonus'][$set_account_bonus];
                    if($set_account_bonus != 7 && $summa > $avail_summ){
                        return $this->return_object_error(_e('Вашего кредитного лимита недостаточно для совершения данной операции.'));
                    } else {
                        //$id_user, $garant = -1, $bonus = -1, $overdraft = -1, $direct = -1
                        $id_new_inv = $this->base_model->add_invest($user_id, (int) $garant, $set_account_bonus, -1, (int) $direct, 0, $card_id, $credit_account_type, $credit_account_id, $use_arbitr_summ, $use_card_arbitr_summ);
                        if(in_array(get_current_user_id(), explode(',',get_sys_var('usd2_fee_users_available'))) && $id_new_inv > 0 && $is_usd2creds_fee && ($credit_account_type == 'card' && $total_summ <= $qiwi_income_summ )){
                            $this->credits->setBlocked_money($id_new_inv, 22);
                        }

                        if ( $use_card_arbitr_summ == 'rating' ){
                            $this->load->model('User_arbitration_scores_model', 'user_arbitration_scores');
                            $this->user_arbitration_scores->remove_scores($user_id, $summa);

                        }

                        if($ii == $countInvests)
                            return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                        $_SESSION['invest_summ'] = $summa;
                    }
                }
                // p-creds garant
                else if($partner_fund == '1'){
                    //fix: add all_advanced_standart_invests_summ_by_bonus to var
                    if($summa > $user_ratings['payment_account_by_bonus'][3] - $user_ratings['all_advanced_invests_summ_by_bonus'][3] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][3]){
                        return $this->return_object_error(_e('На вашем платежном счете недостаточно средств для совершения данной операции.'));
                    } else {
                        $this->load->model('credits_model', 'credits');
                        $this->load->model("transactions_model", "transactions");
                        $id_partner_arbitr       = $this->credits->createPartnerArbitrage($summa, $user_id);
                        $this->transactions->createPartnerInvest($summa, $user_id, $id_partner_arbitr); //$id_user, $summa, $id_partner_arbitr
//                                $debit_id = $this->base_model->add_invest($this->user->id_user, (int)$garant, -1, -1, 0, 2);
//                                $this->base_model->add_partner($debit_id, $this->user->id_user, $summa);
                        $_SESSION['invest_summ'] = $summa;
                        if($ii == $countInvests)
                            return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                    }
                    // c-creds garant
                } else if($c_creds_fund == '1'){
                    //fix: add all_advanced_standart_invests_summ_by_bonus to var
                    if($summa > $user_ratings['payment_account_by_bonus'][4] - $user_ratings['all_advanced_invests_summ_by_bonus'][4] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][4]){
                        return $this->return_object_error(_e('На вашем платежном счете недостаточно средств для совершения данной операции.'));
                    } else {
                        $id_new_inv = $this->base_model->add_invest($user_id, (int) $garant, Base_model::CREDIT_BONUS_CREDS_CASH, -1, 0, 0, $card_id, $credit_account_type, $credit_account_id);
                        if($id_new_inv > 0){
                            $this->load->model('monitoring_model', 'monitoring');
                            $this->monitoring->log(null, "Создана заявка $id_new_inv гарант и cash creds", 'common', $this->user->id_user);
                            $_SESSION['invest_summ'] = $summa;
                            if($ii == $countInvests)
                                return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                        } else
                            return $this->return_object_error(_e("Ошибка параметров"));
                    }
                    // meoney garant
                } else {

                    if((Base_model::CREDIT_OVERDRAFT_OFF == $overdraft) && !$this->preCreateInvest($user_ratings, $total_summ, $set_account_bonus, $error, $use_arbitr_summ))
                        return $this->return_object_error($error);


                    if(( Base_model::CREDIT_OVERDRAFT_ON == $overdraft ) && $this->accaunt->isNotInCreditOverdraftLimit() === true)
                        return $this->return_object_error(_e('Лимит по выдаче кредитов с опцией овердрафт превышен.'));

                    // если отчисления C-CREDS

                    $wt_perc_summ = round($income * (garantPercent($time) / 100), 2);
                    if($is_ccreds_fee){
                        if(!in_array($set_account_bonus, [6]))
                            return $this->return_object_error(_e('Неверно выбран счет'));

                        $c_creds_funds = $user_ratings['payment_account_by_bonus'][4] - $user_ratings['all_advanced_invests_summ_by_bonus'][4] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][4];
                        if($wt_perc_summ * get_sys_var('USD1_TO_CCREDS_PERC') / 100 * 0.5 > $c_creds_funds)
                            return $this->return_object_error(_e('У Вас недостаточно  C-CREDS для погашения отчисления'));


                        /* $data->calc = $this->credits->get_own_sum_and_loans_and_arbitration($user_id, $summa);
                          if (  $data->calc['sum_own_without_loans'] < $data->calc['sum_arbitration_without_loans'] ){
                          accaunt_message($data, _e('У Вас недостаточно своих средств'), 'error');
                          redirect('account/my_invest');
                          } */

                        if($set_account_bonus == 6 && $summa < $remove_creds_summ6)
                            return $this->return_object_error(_e('У Вас недостаточно  C-CREDS для погашения отчисления'));
                    }

                    // проверим чтобы sum_arbitration не была больше 0 при заявке не на арбитражный вклад
                    if ( $set_account_bonus == 6 && !$use_arbitr_summ ){
                        $this->load->model("credits_model", "credits");
                        $calc = $this->credits->get_own_sum_and_loans_and_arbitration($user_id, $summa);
                        if ( $calc['sum_arbitration'] > 0 )
                            return $this->return_object_error(_e('У Вас недостаточно средств'));
                    }

                    //Open TRANSACTION!!!!
                    $this->db->trans_start();{
                        $id_new_inv = $this->base_model->add_invest($user_id, (int) $garant, $set_account_bonus, -1, 0, 0, $card_id, $credit_account_type, $credit_account_id, $use_arbitr_summ);
                        if($id_new_inv > 0){
                            $this->load->model('monitoring_model', 'monitoring');
                            $this->monitoring->log(null, "Создана заявка $id_new_inv гарант и деньги", 'common', $user_id);
                            // если отчисления C-CREDS, то заблокируем сумму

                            if(!$use_arbitr_summ && $is_ccreds_fee && $total_summ <= $calc['calc_sum_own']){
                                $this->transactions->addPay($user_id, round($wt_perc_summ * get_sys_var('USD1_TO_CCREDS_PERC') / 100, 2), Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST, $id_new_inv, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, 4, "Блокировка суммы для заявки №$id_new_inv от ".date('d/m/Yг.'));
                                $this->credits->setBlocked_money($id_new_inv, 44);
                            }
                            if(!$use_arbitr_summ && $is_usd6creds_fee && $total_summ <= $remove_creds_summ2){
                                $this->credits->setBlocked_money($id_new_inv, 66);
                            }

                            if(!$use_arbitr_summ && $is_usd2creds_fee && ($set_account_bonus == 6 && $total_summ > $calc['calc_sum_own'] && $total_summ <= $calc['calc_sum_own'] + $calc['calc_sum_loan'] )){
                                $this->credits->setBlocked_money($id_new_inv, 22);
                            }


                            $_SESSION['invest_summ'] = $summa;
                            $this->db->trans_complete();

                            if($ii == $countInvests)
                                return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                        } else
                            return $this->return_object_error(_e('Ошибка параметров'));
                    }
                    //CLOSE TRANSACTION!!!!
                }
            }

            //гарант && бонусы
            if(Base_model::CREDIT_GARANT_ON == $garant && Base_model::CREDIT_BONUS_ON == $bonus){
                //fix: add all_advanced_standart_invests_summ_by_bonus to var
                if($summa > $user_ratings['payment_account_by_bonus'][1] - $user_ratings['all_advanced_invests_summ_by_bonus'][1] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][1]/* $user_ratings['bonuses'] - $user_ratings['all_advanced_invests_bonuses_summ'] */){
                    return $this->return_object_error(_e('На вашем платежном счете недостаточно средств для совершения данной операции.'));
                } else {
                    $this->db->trans_start();{
                        if($this->base_model->add_invest($user_id, (int) $garant, $set_account_bonus, -1, 0, 0, $card_id, $credit_account_type, $credit_account_id) > 0){
                            $_SESSION['invest_summ'] = $summa;
                            $this->db->trans_complete();
                            return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                        } else
                            return $this->return_object_error(_e("Ошибка параметров"), 'error');
                    }
                    $this->db->trans_complete();
                }
            }

            //стандарт && деньги по бонусу 2
            if(Base_model::CREDIT_GARANT_OFF == $garant && in_array($set_account_bonus, [2, 6])){
                $avail_summ = $user_ratings['payout_limit_by_bonus'][$set_account_bonus] - $user_ratings['all_advanced_invests_summ_by_bonus'][$set_account_bonus];
                if(Credits_model::DIRECT_ON == $direct){

                } else
                if($summa > $avail_summ){
                    return $this->return_object_error(_e('На вашем платежном счете недостаточно средств для совершения данной операции1.'));
                    //}else if(Security::checkSecurity($user_id)){
                } else {

                    $wt_perc_summ = round($income * (10 / 100), 2);
                    if($is_ccreds_fee){

                        $this->load->model('users_model', 'users');
                        if($set_account_bonus == 2 && $this->users->isUsaLimitedUser())
                            return $this->return_object_error(_e('Вы не можете погашать отчесления с помощью C-CREDS'));



                        if(!in_array($set_account_bonus, [6]))
                            return $this->return_object_error(_e('Неверно выбран счет'));


                        $c_creds_funds = $user_ratings['payment_account_by_bonus'][4] - $user_ratings['all_advanced_invests_summ_by_bonus'][4] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][4];
                        if($wt_perc_summ * get_sys_var('USD1_TO_CCREDS_PERC') / 100 > $c_creds_funds)
                            return $this->return_object_error(_e('У Вас недостаточно  C-CREDS для погашения отчисления'));


                        /*
                          if ( $set_account_bonus == 6 && $summa < $data->remove_creds_summ6 ){
                          accaunt_message($data, _e('У Вас недостаточно  C-CREDS для погашения отчисления'), 'error');
                          redirect('account/my_invest');
                          } */


                        /* $data->calc = $this->credits->get_own_sum_and_arbitration($user_id, $summa*$countInvests);
                          if (  $data->calc['sum_own'] < $data->calc['sum_arbitration'] ){
                          accaunt_message($data, _e('У Вас недостаточно  C-CREDS для погашения отчисления'), 'error');
                          redirect('account/my_invest');
                          } */
                    }

                    $id_new_inv = $this->base_model->add_invest($user_id, (int) $garant, $set_account_bonus, -1, 0, 0, $card_id, $credit_account_type, $credit_account_id);
                    if(empty($id_new_inv))
                        return $this->return_object_error(_e('Ошибка параметров'));
                    else {
                        // если отчисления C-CREDS, то заблокируем сумму
                        if($is_ccreds_fee){
                            $this->transactions->addPay($user_id, round($wt_perc_summ * get_sys_var('USD1_TO_CCREDS_PERC') / 100, 2), Transactions_model::TYPE_BLOCKED_SUM_FOR_INVEST, $id_new_inv, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, 4, "Блокировка суммы для заявки №$id_new_inv от ".date('d/m/Yг.'));
                            $this->credits->setBlocked_money($id_new_inv, 44);
                        }
                        $_SESSION['invest_summ'] = $summa;

                        if($ii == $countInvests)
                            return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                    }
                }
            }
        }
    }

    private function check_user_get_credit_garant($rating_by_bonus, $account_type, $summa, $set_account_bonus, &$error){
        $data = $this->data;

        if($account_type == 'card' && ( /* $rating_by_bonus['max_loan_available_by_bonus'][6] + $rating_by_bonus['money_sum_transfer_from_users_by_bonus'][6] < $summa || $rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6] + $rating_by_bonus['money_sum_transfer_from_users_by_bonus'][6] < $summa ||  */ $rating_by_bonus['active_cards_invests'] - $rating_by_bonus['active_cards_credits_outsumm'] < $summa )){
            $error = _e('Вашего рейтинга недостаточно для совершения данной операции.');
        } else
        if($account_type != 'card' && $set_account_bonus == 2 && $rating_by_bonus['max_garant_loan_available_by_bonus'][$set_account_bonus] < $summa){
            $error = _e('Вашего рейтинга недостаточно для совершения данной операции.');
        } else if($account_type != 'card' && ($set_account_bonus == 5 || $set_account_bonus == 6) && $rating_by_bonus['max_garant_loan_available_by_bonus'][$set_account_bonus] < $summa){
            $error = _e('Вашего рейтинга недостаточно для совершения данной операции.');
        } else if($account_type != 'card' && $set_account_bonus == 2 && $rating_by_bonus['money_sum_add_funds_by_bonus'][$set_account_bonus] <= 0){
            $error = _e('Вашего рейтинга недостаточно для совершения данной операции.');
        } else
            return TRUE;

        return FALSE;
    }

    private function _vulnerabilityMoskalenkoTest($user_ratings, $summa, $time, $percent, $bonus){
        $data  = new stdClass();
        if($bonus != 2 && $bonus != 5 && $bonus != 6)
            $bonus = 2;

        $outPay = credit_summ($percent, $summa, $time);

        $vulnerabilityMoskalenko = ($user_ratings['all_advanced_loans_out_summ_by_bonus'][$bonus] + $outPay < $user_ratings['max_loan_available_by_bonus'][$bonus]) && ($user_ratings['all_advanced_loans_out_summ_by_bonus'][$bonus] + $summa + $user_ratings['all_active_garant_loans_out_summ_by_bonus'][$bonus] <=
            $user_ratings['max_loan_available_by_bonus'][$bonus] * 2 );


        return $vulnerabilityMoskalenko;
    }

    public function credit_request($user_id, $summa, $time, $percent, $garant, $overdraft, $direct, $payment_account_type, $payment_account){
        
        $this->load->model('documents_model', 'documents');
        $this->load->model('Card_model', 'card');

        $this->load->model('users_model', 'users');
        $this->load->model('credits_model', 'credits');


        if(!is_numeric($payment_account))
            return $this->return_object_error(_e('Не выбран счет'));

        $user_ratings = $this->accaunt->recalculateUserRating($user_id);

        $card_id             = NULL;
        $credit_account_type = 'bonus';
        $credit_account_id   = NULL;
        $account_type        = $payment_account_type;
        // поставим типы и ИД для счета
        // КАРТА
        if($account_type == 'card'){
            $card_id             = (int) $payment_account;
            $set_account_bonus   = 7;
            $credit_account_type = 'card';
            // СВОЙ СЧЕТ
        } elseif($account_type == 'own_account'){
            $credit_account_id = (int) $payment_account;
            $set_account_bonus = 6;
            $own_acc           = $this->card->getUserOther($credit_account_id, $user_id);

            if(empty($own_acc))
                return $this->return_object_error(_e('Неверный счет'));


            if($own_acc->summa < $summa)
                return $this->return_object_error(_e('Недостаточно средств на счете'));


            if($own_acc->account_type == 'E_WALLET')
                $credit_account_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
            else
                $credit_account_type = $own_acc->account_type;

            // ВСЕ ОСТАЛЬНОЕ
        } else {
            $set_account_bonus = $payment_account;
        }



        if($set_account_bonus == 5)
            return $this->return_object_error(_e('Неверный счет'));

        /* if ( $credit_account_type=='bonus' && $set_account_bonus == 2)
          return $this->return_object_error( _e('Неверный счет') );
          TODO: hide 2 */


        if($set_account_bonus == 2 && $this->users->isUsaLimitedUser($user_id))
            return $this->return_object_error(_e('Вы не можете брать займы на этот счет'));



        if($garant == 0 && Security::checkSecurity($user_id, TRUE, TRUE) !== false){
            return $this->return_object_error(_e('Неверный код.'));
        } else if(!$this->accaunt->getAccessDocuments()){
            return $this->return_object_error(_e('Для проведение данной операции Вам необходимо верифицировать Профиль.'));
        } else {
            $direct = ($account_type == 'card' || $account_type == 'own_account');
            if($summa < 50)
                return $this->return_object_error(_e('Сумма должна быть $50 и более.'));

            if($time == 0 || $percent == 0 || $summa == 0){
                return $this->return_object_error(_e('Данные обновлены'));
            } else {
                //закрываем уязвимость Москаленко - запрещаем пирамидиться
                if($this->_vulnerabilityMoskalenkoTest($user_ratings, $summa, $time, $percent, $set_account_bonus) && $garant == 1)
                    $garant = 1;
                else
                    $garant = 0;


                if($garant == 1){
                    if($account_type == 'card'){

                        $card_credit_params = $this->credits->get_cards_credits_limits($user_id);
                        //var_dump($card_credit_params);
                        if(empty($card_credit_params[$summa])){
                            return $this->return_object_error(_e('Вы не можете занять на эту сумму. Сумма займа должна быть меньше суммы ваших активных вкладов с карты.'));
                        }
                        if(!in_array($time, $card_credit_params[$summa])){
                            return $this->return_object_error(_e('Вы не можете занять на этот срок. Срок займа должен быть меньше срока ваших активный вкладов с карты.'));
                        }
                    }



                    //Open TRANSACTION!!!!
                    $this->db->trans_start();{
                        if($this->check_user_get_credit_garant($user_ratings, $account_type, $summa, $set_account_bonus, $error) === TRUE){
                            $cr_id = $this->base_model->add_credit($user_id, $summa, $garant, $set_account_bonus, -1, $direct, $card_id, $credit_account_type, $credit_account_id);
                            $this->db->trans_complete();
                            return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                        } else {
                            return $this->return_object_error($error);
                        }
                    }
                    //CLOSE TRANSACTION!!!!
                } else {

                    if(($account_type != 'card' && $user_ratings['availiable_garant_by_bonus'][$set_account_bonus] >= $summa) || ( $account_type == 'card' )){
                        $cr_id = $this->base_model->add_credit($user_id, $summa, $garant, $set_account_bonus, -1, $direct, $card_id, $credit_account_type, $credit_account_id);
                        return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение'));
                    } else {
                        return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));
                    }
                }

                // бонусы за опубликование на стене
                $bonus_vk_fb = 0;
                if(isset($_POST['post_vk']) && (int) $_POST['post_vk'] > 0)
                    $bonus_vk_fb += config_item('social_wallpost_bonus');
                if(isset($_POST['post_fb']) && (int) $_POST['post_fb'] > 0)
                    $bonus_vk_fb += config_item('social_wallpost_bonus');
                if($cr_id > 0 && $bonus_vk_fb > 0)
                    $this->credits->setPayment($cr_id, 'soc_bonus='.$bonus_vk_fb);
            }
        }
    }

    public function take_invest($user_id, $id, $account_type, $account_id, $credit_bonus = 0){//взять займ //пришла инвестиция будет создан кредит
//        $this->base_model->redirectNotAjax(); //не включать!!!! esb
        //echo json_encode(['state' => 'no-data']);return;
        $debit = $this->accaunt->creditTicket($id, Base_model::CREDIT_TYPE_INVEST);
        if(empty($debit))
            return $this->return_object_error(_e('Заявка не активна'));

        $this->load->model('inbox_model', 'inbox');

        // узнаем какой бонус установить
        if(is_numeric($account_id))
            $set_account_bonus = $account_id;
        else {
            return $this->return_object_error(_e('Неверный параметр'));
        }
        /*
          if ( (($debit->bonus == 1 || $debit->bonus == 5) && $set_account_bonus != 5) || ($debit->bonus == 2 && !in_array($set_account_bonus,[1,2,5] )) ){
          echo json_encode(['state' => 'error']);
          return;
          }
         */
        //var_dump( $set_account_bonus );
        //var_dump( $debit );
        /* if ( $debit->bonus == 2 && $debit->direct == 0)
          return $this->return_object_error(_e('Счет USD2 недоступен')); TODO: hide 2 */

        if(!($debit->bonus == 7 && $debit->direct == 1) && !($debit->bonus == 2 && $debit->direct == 1) && !in_array($set_account_bonus, [2, 5, 6, 7]))
            return $this->return_object_error(_e('Недопустимая операция'));



        if($debit->account_type != 'card' && (($set_account_bonus == 2) && !in_array($debit->bonus, [2]) || (($set_account_bonus == 6) && !in_array($debit->bonus, [6])) || ($set_account_bonus == 5 && !in_array($debit->bonus, [1, 4, 5])))){
            return $this->return_object_error(_e('Недопустимая операция'));
        }
        $user_ratings = $this->accaunt->recalculateUserRating($user_id/* , null, $set_account_bonus */); //viewData()->accaunt_header;
        // заглушка если нет обеспечения то кредит не давать
        /*
          if ( $user_ratings['max_loan_available_by_bonus'][$set_account_bonus] < $summa  ){
          echo json_encode(['state' => 'no-money']);
          return;
          } else if (  $set_account_bonus == 2 &&  $user_ratings['money_sum_add_funds_by_bonus'][$set_account_bonus] <= 0  ){
          echo json_encode(['state' => 'no-own-funds']);
          return;
          }
         */

        if(empty($debit)){
            return $this->return_object_error(_e('Заявка не активна'));
        }

        if(!$this->accaunt->isUserAccountVerified()){
            return $this->return_object_error(_e('Для подачи заявки, просьба загрузить документы, и дополнить данные в профайле.'));
        }
        if(!$this->inbox->checkReapet($debit->id)){
            return $this->return_object_error(_e('Вы уже подавали заявку'));
        }
        //    if ($this->accaunt->isUS2USorCA($debit->id_user)) {
        //        echo json_encode(['state' => 'us2ca']);
        //        return;
        //    }
        //заявки гарант мы можем брать без подтверждающих писем
        // американцы не могут давать американцам займы
        if($this->accaunt->isUS2USorCA($debit->id_user) && $this->accaunt->isUS2USorCA($user_id)){
            return $this->return_object_error(_e('Вы не можете совершить эту операциию с данным пользователем'));
        }


        /* повторная проверка вкладчика */
        $invester_ratings = $this->accaunt->recalculateUserRating($debit->id_user/* , null, $debit->bonus */);
        if(empty($invester_ratings))
            return $this->return_object_error(_e('Вкладчик не найден'));


        $summa     = $debit->summa;
        $bonus     = $debit->bonus;
        $garant    = $debit->garant;
        $overdraft = $debit->overdraft;
        $direct    = $debit->direct;

        if($bonus == 0)
            return $this->return_object_error(_e('Счет вклада равен 0'));

        // директ на карту
        if($debit->bonus == 7 && $debit->direct == 1){
            if($user_ratings['net_loan_available_by_bonus'][6] < $summa)
                return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));


            if(!$this->canInvest($user_ratings, $error))
                return $this->return_object_error($error);

            //   if ( $summa > $user_ratings['net_own_funds_by_bonus_new'][2] - $user_ratings['p2p_money_sum_transfer_to_users_by_bonus'][2] ){
            //          echo json_encode(['state' => 'no-own-funds']);
            //            return;
            //    }

            $total_out = $user_ratings['money_sum_withdrawal_by_bonus'][6] + // method=out
                $user_ratings['money_sum_transfer_to_users_by_bonus'][6] - //74,75,40,76
                $user_ratings['outcome_merchant_return_by_bonus'][6]; //41
            //    if ( $summa > $user_ratings['money_own_from_2_to_6'] + $user_ratings['money_sum_add_funds_by_bonus'][6] + $user_ratings['money_sum_transfer_from_users_by_bonus'][6] - $total_out ){
            //              return $this->return_object_error(_e('Недостаточно средств.'));
            //     }

            if(/* $user_ratings['max_loan_available_by_bonus'][6] < $summa || */ $user_ratings['active_cards_invests'] - $user_ratings['active_cards_credits_outsumm'] < $summa){
                return $this->return_object_error(_e('Недостаточно средств..'));
            }



            if($debit->garant == 1){
                $this->load->model('card_model', 'card');
                $card = $this->card->getCard($debit->card_id);

                $arbtr = Card_model::get_fast_arbitrage_by_card($card);
                if(!empty($arbtr)){
                    $balance = Card_model::getBalance($card);
                    if($balance < $debit->summa)
                        $res     = $this->card->stop_arbitrage($debit->card_id, $debit->id_user);
                }


                $card_credit_params = $this->credits->get_cards_credits_limits($user_id);
                if(!isset($card_credit_params[(int)$debit->summa])){
                    return $this->return_object_error(_e('Вы не можете занять на эту сумму. Сумма займа должна быть меньше суммы ваших активных вкладов с карты.'));
                }
                if(!in_array($debit->time, $card_credit_params[(int)$debit->summa])){
                    return $this->return_object_error(_e('Вы не можете занять на этот срок. Срок займа должен быть меньше срока ваших активный вкладов с карты.'));
                }


                $send_transaction              = new stdClass();
                $send_transaction->summa       = $debit->summa;
                $send_transaction->id_user     = $set_account_bonus; //to card
                $send_transaction->own         = $debit->id_user; //from user
                $send_transaction->own_card_id = $debit->card_id; //from card
                $send_transaction->note        = 'Take invest #'.$debit->id;

                // установим пароль вкладчику
                if(!empty($card))
                    $this->card->setPassword($card);
                // и заемшмку
                $card_own = $this->card->getUserCard($set_account_bonus);
                if(!empty($card_own))
                    $this->card->setPassword($card_own);
                //return $this->return_object_error(print_r($this->card->setPassword($card),true));
                //return $this->return_object_error(print_r($this->card->setPassword($card_own),true));


                if ( ($debit->arbitration == 2 || $debit->arbitration == 3 )&& $debit->sum_arbitration > 0  ){
                    $load_data          = new stdClass();
                    $load_data->id      = 'TI_'.$debit->id;
                    $load_data->card_id = $set_account_bonus;
                    $load_data->user_id = $user_id;
                    $load_data->summa   = $debit->summa;
                    $load_data->desc    = 'Take invest #'.$debit->id;
                    $response           = $this->card->load($load_data, Card_transactions_model::CARD_TRANS_TAKE_INVEST, $debit->id);
                    if(false !== $response){
                        $answer1 = $this->inbox->manual_agree($debit, $user_id, 7, 7, $set_account_bonus);
                        if($answer1)
                            return $this->return_object_error(_e('Ошибка сведения заявки'));
                        $this->transactions->addPay($debit->id_user, 0, 21, $debit->id, 'wt', 8, 7, "Арбитражный вклад с карты");
                        return $this->return_object(_e('Вы получили займ'));
                    } else {
                         return $this->return_object_error(_e('Не удалось перечислить средства'));
                    }

                }


                $error = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_TAKE_INVEST, $debit->id);
                //var_dump( $error );
                //$error = 'OK';
                if("OK" == $error){
                    $answer1 = $this->inbox->manual_agree($debit, $user_id, 7, 7, $set_account_bonus);
                    if($answer1)
                        return $this->return_object_error(_e('Ошибка сведения заявки'));

                    // pri sozdanii vklada s bonus 7 time >= 10 dney - dayom bonus 20% na bonus 1.
                    if($debit->time >= 10){
                        $this->load->model("transactions_model", 'transactions');
                        /*
                          от $100 и выше - 20%;
                          от $300 и выше - 30%;
                          от $600 и выше - 40%;
                          от $1 000 - 50%
                         */
                        $bonus_perc = 0;
                        if($debit->summa >= 100 && $debit->summa < 300){
                            $bonus_perc = 20;
                        } elseif($debit->summa >= 300 && $debit->summa < 600){
                            $bonus_perc = 30;
                        } elseif($debit->summa >= 600 && $debit->summa < 1000){
                            $bonus_perc = 40;
                        } elseif($debit->summa >= 1000)
                            $bonus_perc = 50;

                        if($bonus_perc > 0)
                            $this->transactions->addPay($debit->id_user, round($debit->summa * ($bonus_perc / 100), 2), 98, $debit->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 1, "Бонус за займ через Webtransfer Visa Card");
                    }


                    return $this->return_object(_e('Вы получили займ'));
                } else {

                    if($error == 'InSufficient Funds'){
                        $this->accaunt->set_status_credit(7, $id);
                        return $this->return_object_error(_e('Недостаточно средств на карте пользователя'));
                    } elseif($error == 'User is not active'){
                        $this->accaunt->set_status_credit(7, $id);
                        return $this->return_object_error(_e('Карта пользователя неактивна(1)'));
                    } elseif($error == 'Invalid User Secondary PASSWORD'){
                        return $this->return_object_error(_e('Карта пользователя неактивна(2)'));
                    } elseif($error == 'Exceeds daily funds transfer count limit'){
                        return $this->return_object_error(_e('Превышен лимит операций по карте'));
                    } else
                        return $this->return_object_error(_e('Не удалось перечислить средства').$error);

                    // $this->accaunt->set_status_credit( 7, $id );
                    return $this->return_object_error($error);
                }
            } else {
                $this->load->model('inbox_model', 'inbox');
                $this->inbox->writeInbox($user_id, $debit->id_user, 'ready-take-credit', $debit->id, 0, 'card', $debit->card_id);
                return $this->return_object(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение.'));
            }
            //accaunt_message($data, 'Благодарим  за заявку. Данные  приняты на  рассмотрение.>');

            return $this->return_object_error(_e('Неизвестная ошибка.')); ;
        }

        // директ на свой счет
        if($debit->bonus == 6 && $debit->direct == 1){
            $this->load->model('inbox_model', 'inbox');
            $own_acc = $this->card->getUserOther($set_account_bonus, $user_id);

            if(empty($own_acc)){
                //accaunt_message($data, _e('Неверный счет'), 'error');
                return $this->return_object_error(_e('Неверный кошелек'));
            }


            if($own_acc->account_type == 'E_WALLET')
                $credit_account_type = $own_acc->account_type.'_'.$own_acc->account_extra_data;
            else
                $credit_account_type = $own_acc->account_type;

            $this->inbox->writeInbox($user_id, $debit->id_user, 'ready-take-credit', $debit->id, 0, $credit_account_type, $set_account_bonus);
            //accaunt_message($data, 'Благодарим  за заявку. Данные  приняты на  рассмотрение.>');
            return $this->return_object_error(_e('Благодарим  за заявку. Данные  приняты на  рассмотрение.'));
        }


        if($debit->bonus == 2 && $this->users->isUsaLimitedUser($user_id))
            return $this->return_object_error(_e('Вы не можете брать займы на этот счет.'));





        $allow = FALSE;

        if(Base_model::CREDIT_GARANT_ON == $garant){//гарант
            if($bonus != 1){//деньги
                //direct
                if((Base_model::CREDIT_OVERDRAFT_OFF == $overdraft) && ( Credits_model::DIRECT_ON == $direct )){
                    if(0 > $invester_ratings['max_loan_available_by_bonus'][$debit->bonus] + $summa - $invester_ratings['direct_collateral']){ // директов нет
//                            accaunt_message($data, 'Вашего кредитного лимита недостаточно для совершения данной операции.', 'error');
                        return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.'));
                    } else {
                        $allow = TRUE;
                        //$id_user, $garant = -1, $bonus = -1, $overdraft = -1, $direct = -1
//                            accaunt_message($data, 'Благодарим  за заявку. Данные  приняты на  рассмотрение');
//                            $_SESSION['invest_summ'] = $this->input->post('summ');
                    }
                } else {

                    $add_summ = 0;
                    if($debit->sum_arbitration > 0 && $debit->sum_own == 0 && $debit->sum_loan == 0 && $debit->bonus == 6 and $invester_ratings['arbitration_active_summ_by_bonus'][6] - $invester_ratings['arbitration_shown_summ_by_bonus'][6] <= $invester_ratings['max_arbitrage_calc_by_bonus'][6])
                        $add_summ += (
                            // $invester_ratings['max_arbitrage_calc_by_bonus'][$debit->bonus] -
                            $invester_ratings['arbitration_shown_summ_by_bonus'][6]);

                    if(max(0, $invester_ratings['availiable_garant_by_bonus'][$debit->bonus]) + $add_summ <= 0){
                        //$invester_ratings['availiable_garant_by_bonus'] - $invester_ratings['total_processing_payout'] < 0) {
                        //                        accaunt_message($data, 'На вашем платежном счете недост аточно средств для совершения данной операции.<br/>У вас есть заявки на вывод со статусом "В процессе".', 'error');
                        //$this->accaunt->set_status_credit( 7, $id );
                        return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.3'));
                    } else {

                        if(( Base_model::CREDIT_OVERDRAFT_ON == $overdraft ) && $this->accaunt->isNotInCreditOverdraftLimit()){
                            //                            accaunt_message($data, 'Лимит по выдаче кредитов с опцией овердрафт превышен.', 'error');
                            return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.4'));
                        } else {
                            $allow = TRUE;
                        }
                    }
                }
            } else {//бонусы
                //fix: add all_advanced_standart_invests_summ_by_bonus to var
                if($invester_ratings['payment_account_by_bonus'][1] - $invester_ratings['all_advanced_standart_invests_summ_by_bonus'][1] < $summa/* $invester_ratings['bonuses'] - $invester_ratings['all_advanced_invests_bonuses_summ'] */){
//                        accaunt_message($data, 'На вашем бонусном счете недостаточно средств для совершения данной операции.', 'error');
                    $this->accaunt->set_status_credit(7, $id);
                    return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.1'));
                } else {
                    $allow = TRUE;
                }
            }
        } else {//стандарт
            if(!$this->accaunt->getAccessDocuments())
                return $this->return_object_error(_e('Для подачи заявки, просьба загрузить документы, и дополнить данные в профайле.'));


            if(Credits_model::DIRECT_ON == $direct){
                if($invester_ratings['max_loan_available_by_bonus'][$debit->bonus] - $invester_ratings['direct_collateral'] <= 0){    // директ не работает
//                           accaunt_message($data, 'Вашего кредитного лимита недостаточно для совершения данной операции.', 'error');
                    return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.2'));
                } else {
                    //$id_user, $garant = -1, $bonus = -1, $overdraft = -1, $direct = -1
//                            $this->base_model->add_invest($this->user->id_user, (int) $garant, -1, -1, (int)$direct );
//                           accaunt_message($data, 'Благодарим  за заявку. Данные  приняты на  рассмотрение');
//                           $_SESSION['invest_summ'] = $this->input->post('summ');
                    $allow = TRUE;
                }
            } else
            if($invester_ratings['payout_limit_by_bonus'][$debit->bonus] < 0){
//                    accaunt_message($data, 'На вашем платежном счете недостаточно средств для совершения данной операции.', 'error');
                return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.'));
            } else if($invester_ratings['payout_limit_by_bonus'][$debit->bonus] - $invester_ratings['total_processing_payout_sum_by_bonus'][$debit->bonus] < $summa){
//                    accaunt_message($data, 'На вашем платежном счете недостаточно средств для совершения данной операции.<br/>У вас есть заявки на вывод со статусом "В процессе".', 'error');
                return $this->return_object_error(_e('Займ неактивен. На платежном счете кредитора недостаточно средств для выдачи данного займа.'));
            } else {
//                    $this->base_model->add_invest($this->user->id_user, (int) $garant);
//                    accaunt_message($data, 'Благодарим  за заявку. Данные  приняты на  рассмотрение.</a>');
//                    $_SESSION['invest_summ'] = $this->input->post('summ');
                $allow = TRUE;
            }
        }

        if(FALSE == $allow)
            return $this->return_object_error(_e('Вы не получить займ.'));

        /* /повторная проверка заемщика */

        if($debit->garant == Base_model::CREDIT_GARANT_ON){
            if($credit_bonus == Base_model::CREDIT_BONUS_OFF){//деньги
                if(( $user_ratings['max_garant_loan_available_by_bonus'][$set_account_bonus] ) < $summa){
                    return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));
                } else if(( $user_ratings['all_advanced_loans_out_summ_by_bonus'][$set_account_bonus] ) > $user_ratings['max_loan_available_by_bonus'][$set_account_bonus]){
                    return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));
                } else
                //альтернативный вариант строки 1374
//                    if (( $user_ratings['all_advanced_loans_out_summ'] +
//                            $user_ratings['all_active_garant_loans_out_summ'] ) > $user_ratings['max_loan_available'] * 3
//                    ) {
//                  //закрываем уязвимость Москаленко - запрещаем пирамидиться
                if(($user_ratings['all_advanced_loans_out_summ_by_bonus'][$set_account_bonus] + $debit->out_summ < $user_ratings['max_loan_available_by_bonus'][$set_account_bonus]) && ($user_ratings['all_advanced_loans_out_summ_by_bonus'][$set_account_bonus] + $debit->summa + $user_ratings['all_active_garant_loans_out_summ_by_bonus'][$set_account_bonus] <=
                    $user_ratings['max_loan_available_by_bonus'][$set_account_bonus] * 2 )
                ){
//                  //идентично строке 1374
//                    if(($user_ratings['all_advanced_loans_out_summ'] + $debit->out_summ < $user_ratings['max_loan_available']) ){
                    //отправили 1 заявку
                    $this->load->model('inbox_model', 'inbox');
                    $this->inbox->writeInbox($user_id, $debit->id_user, 'ready-take-credit', $debit->id, 1);

//                        ob_start();
                    $answer = $this->inbox->manual_agree($debit, $user_id, $set_account_bonus/* $debit->bonus */, $set_account_bonus);
//                        ob_clean();
                    if(!$answer){
                        $this->accaunt->payBonusesOnFirstCredit($user_id);
                        return $this->return_object(_e('Вы получили займ'));
                    } else {
                        return $this->return_object_error(_e('Не удалось получить займ.'));
                    }
                } else {
                    return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));
                }
            } else {//бонус
                //fix: add all_advanced_standart_invests_summ_by_bonus to var
                if($user_ratings['payment_account_by_bonus'][1] - $user_ratings['all_advanced_invests_summ_by_bonus'][1] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][1] < $summa/* $user_ratings['bonuses'] - $user_ratings['all_advanced_invests_bonuses_summ'] < 0 */){
                    return $this->return_object_error(_e('Вашего рейтинга недостаточно для совершения данной операции.'));
                } else {
                    //отправили 1 заявку
                    $this->load->model('inbox_model', 'inbox');
                    $this->inbox->writeInbox($user_id, $debit->id_user, 'ready-take-credit', $debit->id, Base_model::CREDIT_BONUS_ON);

//                        ob_start();
                    $answer = $this->inbox->manual_agree($debit, $user_id, $set_account_bonus, $set_account_bonus);
//                        ob_clean();
                    if(!$answer){
                        $this->accaunt->payBonusesOnFirstCredit($user_id);
                        return $this->return_object(_e('Вы получили займ'));
                    } else {
                        return $this->return_object_error(_e('Не удалось получить займ.'));
                    }
                }
            }
        } else {//стандарт
            $this->load->model('inbox_model');
            $this->inbox_model->writeInbox($user_id, $debit->id_user, 'ready-take-credit', $debit->id);
            return $this->return_object(_e('Вы получили займ'));
        }


        return $this->return_object_error(_e('Неизвестная ошибка.'));
    }

    public function take_credit($user_id, $id, $account_type, $account_id){// дать займ //пришел кредит будет создана инвестиция
//        $this->base_model->redirectNotAjax(); //не включать!!!! esb
        // узнаем какой бонус установить
        if(is_numeric($account_id))
            $set_account_bonus = $account_id;
        else {
            return $this->return_object_error(_e('Неверный параметр'));
        }

        $debit = $this->accaunt->creditTicket($id, Base_model::CREDIT_TYPE_CREDIT);
        $this->load->model('security_model', 'security_model');
        $this->load->model('phone_model', 'phone');

        #нельзя вкладывать деньги с просроченного арбитража
        $this->load->model('credits_model', 'credits');
        $credits = $this->credits->getOverdueArbitrationCredits($user_id);

        if(!empty($credits))
            return $this->return_object_error(_e('Для проведение данной операции Вам необходимо погасить просроченный(е) займ(ы) на Арбитраж.'));
        #/нельзя вкладывать деньги с просроченного арбитража

        if(empty($debit))
            return $this->return_object_error(_e('Заявка не активна'));


        if(!($debit->bonus == 7 && $debit->direct == 1) && !($debit->bonus == 2 && $debit->direct == 1) && !in_array($set_account_bonus, [1, 2, 4, 5, 6]))
            return $this->return_object_error(_e('Недопустимая операция'));


        if((($set_account_bonus == 1 || $set_account_bonus == 4 || $set_account_bonus == 5) && !in_array($debit->bonus, [5])) || ($set_account_bonus == 2 && !in_array($debit->bonus, [2])) || ($set_account_bonus == 6 && !in_array($debit->bonus, [6]))){
            return $this->return_object_error(_e('Займ в валюте USD❤ можно получить только на счет USD❤.<br>Займ в валюте WTDEBIT можно получить только на счет WTDEBIT.<br>Займ USD1 можно получить на счета USD-B, C-CREDS, USD1'));
        }

        /* if ( $debit->bonus == 2 && $debit->direct == 0)
          return $this->return_object_error(_e('Счет USD2 недоступен'));
          TODO: hide 2 */

        // американцы не могут давать американцам займы
        if($this->accaunt->isUS2USorCA($debit->id_user) && $this->accaunt->isUS2USorCA($user_id)){
            return $this->return_object_error(_e('Вы не можете совершить эту операциию с данным пользователем'));
        }


        $user_ratings = $this->accaunt->recalculateUserRating($user_id/* , NULL, $set_account_bonus */); // viewData()->accaunt_header;
        require_once APPPATH.'controllers/user/Security.php';


        $borrower = $this->accaunt->recalculateUserRating($debit->id_user/* , NULL, $debit->bonus */);
        if(empty($borrower)){
            return $this->return_object_error(_e('Пользователь не найден'));
        }

        /* повторная проверка кредитора */
        //закрываем уязвимость Москаленко - запрещаем пирамидиться
        if(($borrower['all_advanced_loans_out_summ_by_bonus'][$debit->bonus] + $debit->out_summ < $borrower['max_loan_available_by_bonus'][$debit->bonus]) && ($borrower['all_advanced_loans_out_summ_by_bonus'][$debit->bonus] + $debit->summa + $borrower['all_active_garant_loans_out_summ_by_bonus'][$debit->bonus] <=
            $borrower['max_loan_available_by_bonus'][$debit->bonus] * 2 )
        )
            $garant = 1;
        else
            $garant = 0;

        if(Base_model::CREDIT_GARANT_OFF == $garant && Base_model::CREDIT_GARANT_ON == $debit->garant){
            $this->accaunt->set_status_credit(7, $id);
            return $this->return_object_error(_e('Займ неактивен. Рейтинга заемщика недостаточно для получения займа по опции Гарант'));
        }
        /* повторная проверка кредитора */

        $garant = (int) $debit->garant;
        $summa  = (int) $debit->summa;

        $bonus = Base_model::CREDIT_BONUS_OFF;
        if(isset($_POST['bonus']) && $_POST['bonus'] == Base_model::CREDIT_BONUS_ON)
            $bonus = Base_model::CREDIT_BONUS_ON;


        if(!$this->canInvest($user_ratings, $error, 'invest')){
            return $this->return_object_error($error);
        }

        $this->load->model('inbox_model', 'inbox');
        if(!$this->inbox->checkReapet($debit->id))
            return $this->return_object_error(_e('Вы уже сделали  предложение на эту заявку'));

        // if ($this->accaunt->isUS2USorCA($debit->id_user)) {
        //     echo json_encode(['state' => 'us2ca']);
        //      return;
        //  }
        // директ на карту
        if($debit->bonus == 7 && $debit->direct == 1){

            $total_out = $borrower['money_sum_withdrawal_by_bonus'][6] + // method=out
                $borrower['money_sum_transfer_to_users_by_bonus'][6] - //74,75,40,76
                $borrower['outcome_merchant_return_by_bonus'][6]; //41

            /* if ( $summa > $borrower['money_own_from_2_to_6'] + $borrower['money_sum_add_funds_by_bonus'][6] - $total_out ){
              return $this->return_object_error(_e('Недостаточно средств.'));
              }

              if ($borrower['max_loan_available_by_bonus'][6] < $summa){
              return $this->return_object_error(_e('Недостаточно средств1.'));
              } */

            if(($rating_by_bonus['max_loan_available_by_bonus'][6] + $rating_by_bonus['money_sum_transfer_from_users_by_bonus'][6] < $summa || $rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6] + $rating_by_bonus['money_sum_transfer_from_users_by_bonus'][6] < $summa || $borrower['active_cards_invests'] - $borrower['active_cards_credits_outsumm'] < $summa)){
                return $this->return_object_error(_e('Недостаточно средств2.'));
            }

            $send_transaction              = new stdClass();
            $send_transaction->summa       = $debit->summa;
            $send_transaction->id_user     = $debit->card_id; //to card
            $send_transaction->own         = $user_id; //from user
            $send_transaction->own_card_id = $set_account_bonus; //from card
            $send_transaction->note        = 'Take loan #'.$debit->id;

            $error = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_TAKE_LOAN, $debit->id);
            //$error = 'OK';
            if("OK" == $error){
                $answer1 = $this->inbox->manual_agree($debit, $user_id, 7, 7, $set_account_bonus);
                if($answer1)
                    return $this->return_object_error(_e('Ошибка подтверждения заяви.'));


                $this->inbox->writeInbox($user_id, $debit->id_user, 'ready-give-credit', $debit->id, 1);
                //$this->inbox_model->writeInbox($debit->id_user, $this->accaunt->get_user_id(), 'ready-give-credit', $debit->id, 1);
                $garant_or_standart = ($debit->garant == 0) ? _e('Стандарт') : _e('Гарант');
                $this->load->model('users_model', 'users');
                $credit_user        = $this->users->debit_user($debit->id_user);
                $this->mail->user_sender('take_credit_over_card_debit_user', $user_id, [
                    'summa'              => $debit->summa,
                    'garant_or_standart' => $garant_or_standart,
                    'credit_user_fio'    => $credit_user->sername.' '.$credit_user->name
                ]); // отправим почту тому кто дал кредите
                $debit_user         = $this->users->debit_user($user_id);
                $this->mail->user_sender('take_credit_over_card_credit_user', $debit->id_user, [
                    'summa'              => $debit->summa,
                    'garant_or_standart' => $garant_or_standart,
                    'debit_user_fio'     => $debit_user->sername.' '.$debit_user->name
                ]); // отправим почту тому кто взял кредит

                return $this->return_object(_e('Вы дали займ'));
            } else {
                return $this->return_object_error(_e('Ошибка перевода средств на карту: '.$error));
            }

            return $this->return_object_error(_e('Неизвестная ошибка'));
        }



        if($garant == Base_model::CREDIT_GARANT_ON){//гарант
            if($bonus == Base_model::CREDIT_BONUS_ON){//бонусы
                //fix: add all_advanced_standart_invests_summ_by_bonus to var
                if($summa > $user_ratings['payment_account_by_bonus'][1] - $user_ratings['all_advanced_invests_summ_by_bonus'][1] - $user_ratings['all_advanced_standart_invests_summ_by_bonus'][1]/* $user_ratings['bonuses'] - $user_ratings['all_advanced_invests_bonuses_summ'] */){
                    return $this->return_object_error(_e('Недостаточно средств.'));
                } else {


                    //  $answer1 = $this->inbox->manual_agree($debit, $this->accaunt->get_user_id(), Base_model::CREDIT_BONUS_ON);
                    $answer1 = $this->inbox->manual_agree($debit, $user_id, Base_model::CREDIT_BONUS_ON, $set_account_bonus);
                    if($answer1)
                        return $this->return_object_error(_e('Ошибка подтверждения заяви.'));
                    $this->accaunt->payBonusesOnFirstCredit($debit->id_user);
                    $this->load->model('inbox_model');
                    $this->inbox_model->writeInbox($user_id, $debit->id_user, 'ready-give-credit', $debit->id, 1);
                    return $this->return_object(_e('Вы дали займ'));
                }
            } else {//деньги
                $add_summ = 0;
                if($set_account_bonus == 6)
                    $add_summ = $user_ratings['max_arbitrage_calc_by_bonus'][6] - $user_ratings['arbitration_active_summ_by_bonus'][6];
                if($summa > $user_ratings['availiable_garant_by_bonus'][$set_account_bonus] + $add_summ
                /* $user_ratings['all_advanced_invests_summ'] +
                  $user_ratings['all_advanced_standart_invests_summ'] +
                  $summa > $user_ratings['payment_account'] */
                ){
                    return $this->return_object_error(_e('Недостаточно средств.'));
                } else if($user_ratings['all_advanced_invests_summ_by_bonus'][$set_account_bonus] +
                    $user_ratings['all_advanced_standart_invests_summ_by_bonus'][$set_account_bonus] +
                    $summa > $user_ratings['payment_account_by_bonus'][$set_account_bonus] - $user_ratings['total_processing_payout_by_bonus'][$set_account_bonus] + $add_summ
                ){
                    return $this->return_object_error(_e('Недостаточно средств.'));
                } else {

                    // заглушка если нет обеспечения то кредит не давать
                    if($borrower['max_loan_available_by_bonus'][$set_account_bonus] < $summa){
                        $this->accaunt->set_status_credit(7, $id);
                        return $this->return_object_error(_e('У вас недостаточно средств для выдачи займ.'));
                    }/* else if (  $set_account_bonus == 2 &&  $borrower['money_sum_add_funds_by_bonus'][$set_account_bonus] <= 0  ){
                      echo json_encode(['state' => 'no-money']);

                      return;
                      } */

                    //$answer1 = $this->inbox->manual_agree($debit, $this->accaunt->get_user_id(), $set_account_bonus);
                    $answer1 = $this->inbox->manual_agree($debit, $user_id, $debit->bonus, $set_account_bonus);
                    if($answer1)
                        return $this->return_object_error(_e('Ошибка подтверждения заяви.'));

                    $this->accaunt->payBonusesOnFirstCredit($debit->id_user);
                    $this->load->model('inbox_model');
                    $this->inbox_model->writeInbox($user_id, $debit->id_user, 'ready-give-credit', $debit->id, 1);
                    return $this->return_object(_e('Вы дали займ'));
                }
            }
        } else {//стандарт
            if(!$this->accaunt->getAccessDocuments()){
                return $this->return_object_error(_e('Для подачи заявки, просьба загрузить документы, и дополнить данные в профайле.'));
            }

            if($summa > $user_ratings['payout_limit_by_bonus'][$set_account_bonus]){
                return $this->return_object_error(_e('Недостаточно средств.'));
            } else
            if($summa > $user_ratings['payout_limit_by_bonus'][$set_account_bonus] -
                $user_ratings['total_processing_payout_sum_by_bonus'][$set_account_bonus] -
                $user_ratings['total_processing_payout_fee_by_bonus'][$set_account_bonus]
            ){
                return $this->return_object_error(_e('Недостаточно средств для выдачи займа. У вас есть заявки на вывод средств.'));
            } else {
                $secur = Security::checkSecurity($user_id, true, true);
                if($secur){
                    return $this->return_object_error($secur);
                } else {
                    $answer1 = $this->inbox->manual_agree($debit, $user_id, $debit->bonus, $set_account_bonus);
                    if($answer1)
                        return $this->return_object_error(_e('Ошибка подтверждения заяви.'));
                    $this->load->model('inbox_model');
                    $this->inbox_model->writeInbox($user_id, $debit->id_user, 'ready-give-credit', $debit->id, 1);
                    return $this->return_object(_e('Вы дали займ'));
                }
            }
        }
    }

    public function getExchangeList($date){
        $this->load->model('Credits_model', 'credits');
        if(!empty($date))
            $date = date("Y-m-d H:i:s", strtotime($date));
        else
            $date = false;
        $result = $this->credits->getExchangeList($date);
        return $this->return_data(['orders'=>$result]);
    }

    public function requests_list($type, $state, $start, $length, $sort, $search_data){
        $this->load->model('Card_model', 'card');
        $this->load->model('Credits_model', 'credits');


        if(!in_array((int) $type, [1, 2]))
            return $this->return_object_error(_e('Неверный тип.'));

        foreach($search_data as $k => $s){
            if(!preg_match('/^[а-яА-ЯёЁa-zA-Z0-9:,]+$/', $s))
                $this->return_object_error(_e('Неверные символы в параметрах.').$k);
        }


        $limit      = array($length, $start);
        $res_search = $this->credits->getAllCreditsForApplications($type, $state, $search_data, $limit, $sort, NULL);

        return $this->return_data([
                'recordsTotal' => $search_data['total_count'],
                'data'         => $res_search]
        );
    }

    private function _check_return($own, $rating, $out_summ, $bonus){
        $repay_limit = 0; //сумма для вравнения при возврате Гаранта


        if($bonus == 9){
            $out_summ = $own->income;
            $bonus    = 6;

            if(($rating['payout_limit_by_bonus'][$bonus] + $rating['garant_received'] < $out_summ) || ($rating['max_loan_available_by_bonus'][$bonus] < $own->summa ) || ($rating['net_funds_by_bonus'][$bonus] + $rating['garant_received'] - $rating['garant_issued'] < $own->summa))
                return FALSE;
            //     if ( $rating['max_loan_available_by_bonus'][$bonus] < $own->summa )
            //         return FALSE;

            return TRUE;
        }

        if(Base_model::CREDIT_GARANT_OFF == $own->garant){//Стандарты
            $repay_limit = $rating['payout_limit_by_bonus'][$bonus];
        } else { //Гаранты
            $check = $rating['payout_limit_by_bonus'][$bonus] + $rating['arbitrage_collateral_by_bonus'][$bonus] + $rating['loans_percentage_garant_by_bonus'][$bonus] < $rating['payment_account_by_bonus'][$bonus];

            if($check)
                $repay_limit = $rating['payout_limit_by_bonus'][$bonus] + $rating['arbitrage_collateral_by_bonus'][$bonus] + $rating['loans_percentage_garant_by_bonus'][$bonus];
            else
                $repay_limit = $rating['payment_account_by_bonus'][$bonus];

            //если нет арбитаража - можно гасить всей суммой
            if($rating['total_arbitrage_by_bonus'][$bonus] == 0)
                $repay_limit = $rating['payment_account_by_bonus'][$bonus];

            if($bonus == 6){
                $repay_limit = $rating['payment_account_by_bonus'][$bonus] - $rating['total_processing_by_bonus'][$bonus];
            }
        }

        if($out_summ > $repay_limit)
            return false;

        return true;
    }

    public function autoReturnInvesterLoans($user_id, $card_id = NULL){
        $loans = $this->db->where(['id_user' => $user_id, 'type' => 1, 'bonus' => 7, 'state' => 2, 'out_time <=' => date('Y-m-d')])->get('credits')->result();
        foreach($loans as $loan){
            if(empty($card_id))
                $card_id = $loan->card_id;
            $this->return_loan($loan->id, $user_id, $card_id);
        }
    }

    public function return_gift_invests($offer){
    }

    public function return_loan($id, $user_id, $card_id){

        $d    = 0.02;
//        $data = new stdClass();
        $own  = $this->accaunt->getCredit($id); // погашает кредит

        if($own->id_user != $user_id)
            return $this->return_object_error(_e('Невозможно подтвердить перевод средств для этой заявки.'));
        $this->accaunt->set_user($user_id);

        $offer = $this->accaunt->getCredit($own->debit); // дает займ


        if($own->confirm_return != 2
            or ( $own->state != Base_model::CREDIT_STATUS_ACTIVE
            and $own->state != Base_model::CREDIT_STATUS_OVERDUE )
            or empty($offer)
            or $own->type != Base_model::CREDIT_TYPE_CREDIT
            or ( strtotime($own->out_time) > strtotime(date('Y-m-d')) && $own->bonus != 9 )
            or empty($own->out_time)){
            return $this->return_object_error(_e('Невозможно подтвердить перевод средств для этой заявки.'));
        }



        //пересчет суммы погашения Гаранта, если кредитор - ".'.$GLOBALS["WHITELABEL_NAME"].'." Garantee Fund
        $out_summ = $own->out_summ;
        $income   = $own->income;
        if((Base_model::CREDIT_GARANT_ON == $own->garant && Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND == $offer->id_user ) || $own->direct == 1 || $own->bonus == 9){
            $this->load->helper('admin');
            $this->load->model('credits_model', 'credits');

            $time_days = calculate_debit_time_now($own->date_kontract);
            $out_summ  = credit_summ($own->percent, $own->summa, $time_days);
            $income    = $out_summ - $own->summa;
        }

//        if ($out_summ > $this->accaunt->getMoney()) {
//        if( !isset( viewData()->accaunt_header['payout_limit'] ) ||
//            $out_summ > viewData()->accaunt_header['payout_limit']) {
//            accaunt_message($data, 'У вас не достаточно средств для вывода', 'error');
//            return;
//        }
        // TODO: разобраться с этими бонсами
        $own_trans_bonus = $this->base_model->getBonusTypeByCreditNew($own->bonus);
        $rating_by_bonus = $this->accaunt->recalculateUserRating($own->id_user);

        $rating = $rating_by_bonus;


        //
        if($own->direct == 1 && $own->bonus == 7){
            $this->load->model('Card_model', 'card');


            //$card_id = $this->input->post('card_id');
            // если платим с бонуса 6
            if(!empty($card_id)){
                if($card_id == 'bonus_6'){
                    $card_id = NULL;
                    $summ_b6 = min($rating_by_bonus['payment_account_by_bonus'][6], $rating_by_bonus['money_sum_add_funds_by_bonus'][6] + $rating_by_bonus['money_own_from_2_to_6'] - $rating_by_bonus['money_sum_withdrawal_by_bonus'][6]);
                    //$summ_b6 = max($rating_by_bonus['availiable_garant_by_bonus'][6],0); // berem payment account - total processing - pending invest garant i standart
                    if($summ_b6 < $out_summ - $d){
                        return $this->return_object_error(_e('Недостаточно средств на счете WTDEBIT'));
                    }
                }
            } else {
                $card_id = $own->card_id;
            }
            if($own->garant == Base_model::CREDIT_GARANT_OFF)
                $wt_perc_summ = round($income * (10 / 100), 2);
            else
                $wt_perc_summ = round($income * (garantPercent($own->time) / 100), 2);




            // если платим картой
            if(!empty($card_id)){
                if($offer->id_user == 90831137){
                    $purchaseMoney_data            = [];
                    $purchaseMoney_data['card_id'] = $card_id;
                    $purchaseMoney_data['user_id'] = $own->id_user;
                    $purchaseMoney_data['id']      = 'SFDPoL_'.$own->id;
                    $purchaseMoney_data['summa']   = $out_summ;
                    $purchaseMoney_data['desc']    = 'Re-payment of the loan #'.$own->id;
                    $response                      = $this->card->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_TRANS_LOAN_RETURN, $own->id);
                    $error                         = (false !== $response) ? 'OK' : 'ERROR';
                } else {
                    $send_transaction              = new stdClass();
                    $send_transaction->summa       = $out_summ;
                    $send_transaction->id_user     = $offer->card_id; //to card
                    $send_transaction->own         = $own->id_user; //from user
                    $send_transaction->own_card_id = $card_id; //from card
                    $send_transaction->note        = 'Re-payment of the loan #'.$own->id;
                    $error                         = $this->card->sendCardDirect($send_transaction, Card_transactions_model::CARD_TRANS_LOAN_RETURN, $offer->id);
                }
                $offer_rating['payout_limit_by_bonus'][2] = 0;

                if(!in_array($offer->id_user, [90831137, 400400]))
                    $offer_rating = $this->accaunt->recalculateUserRating($offer->id_user);

                if("OK" == $error){
                    //$this->confirmReturns($own, $offer);
                    //accaunt_message($data, _e('Перевод успешно совершен'));
                    $this->base_model->addContributions($offer);
                    if($offer->blocked_money == 22 && $offer_rating['payout_limit_by_bonus'][2] >= $wt_perc_summ){
                        $this->transactions->addPay($offer->id_user, $wt_perc_summ, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $offer->id, 'wt', 3, 2, "Отчисление в ".$GLOBALS["WHITELABEL_NAME"]." по займу №$offer->id");
                        $this->accaunt->confirmReturns($own, $offer);
                        return $this->return_object(_e('Перевод успешно совершен'));
                    } else {
                        $purchaseMoney_data            = [];
                        $purchaseMoney_data['card_id'] = $offer->card_id;
                        $purchaseMoney_data['user_id'] = $offer->id_user;
                        $purchaseMoney_data['id']      = 'SFDoL_'.$own->id;
                        $purchaseMoney_data['summa']   = $wt_perc_summ;
                        $purchaseMoney_data['desc']    = 'Security Fund deduction of the loan #'.$own->id;
                        $response                      = $this->card->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_TRANS_LOAN_RETURN_PERC, $own->id);
                        //var_dump( $error );
                        if(false !== $response){
                            $this->accaunt->confirmReturns($own, $offer);
                            $this->autoReturnInvesterLoans($offer->id_user, $offer->card_id);
                            return $this->return_object(_e('Перевод успешно совершен'));
                        } else {
                            return $this->return_object_error(_e('Ошибка перечисления процентов.'));
                        }
                    }
                } else {

                    $purchaseMoney_data            = [];
                    $purchaseMoney_data['card_id'] = $own->card_id;
                    $purchaseMoney_data['user_id'] = $own->id_user;
                    $purchaseMoney_data['id']      = 'SFDPoL_'.$own->id;
                    $purchaseMoney_data['summa']   = $out_summ;
                    $purchaseMoney_data['desc']    = 'Re-payment of the loan #'.$own->id;
                    $response                      = $this->card->purchaseMoney($purchaseMoney_data, Card_transactions_model::CARD_TRANS_LOAN_RETURN_PERC, $own->id);
                    //var_dump( $error );
                    if(false !== $response){
                        $this->transactions->addPay($offer->id_user, ($offer->summa + $offer->income), 24, $offer->id, 'wtcard', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 7, "Отсроченное получение прибыли по вкладу №$offer->id");
                        $this->accaunt->confirmReturns($own, $offer);
                    } else {
                        return $this->return_object_error('Ошибка погашения займа');
                    }

                    /* $this->transactions->addPay($own->id_user, $out_summ, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Погашение кредита №$own->id");
                      //$this->transactions->addPay($offer->id_user, $out_summ, 24, $offer->id, 'wtcard', Base_model::TRANSACTION_STATUS_NOT_RECEIVED, 7, "Отсроченное получение прибыли по вкладу №$offer->id");
                      //$this->accaunt->confirmReturns($own, $offer); */
                    // return $this->return_object_error($error);
                    //return $this->return_object_error($error);
                    /* $this->transactions->addPay($own->id_user, $out_summ, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Погашение кредита №$own->id");
                      $this->confirmReturns($own, $offer);
                      $this->base_model->addContributions($offer);
                      accaunt_message($data, _e('Перевод успешно совершен с помощью WTDEBIT')); */
                }
            } else {
                // если платим бонусом то добавляем пользователю денег на карту сами
                $load_data          = new stdClass();
                $load_data->id      = 'RI_'.$offer->id;
                $load_data->card_id = $offer->card_id;
                $load_data->user_id = $offer->id_user;
                $load_data->summa   = $offer->summa + $income - $wt_perc_summ;
                $load_data->desc    = 'Return of loan #'.$offer->id.'(total $'.($offer->summa + $income).' less fee $'.$wt_perc_summ.')';
                $response           = $this->card->load($load_data, Card_transactions_model::CARD_RETURN_INVEST, $offer->id);
                if(false !== $response){
                    $this->transactions->addPay($own->id_user, $out_summ, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Погашение кредита №$own->id");
                    $this->accaunt->confirmReturns($own, $offer);
                    $this->base_model->addContributions($offer);
                    return $this->return_object(_e('Перевод успешно совершен'));
                } else {
                    $bonus = $this->base_model->getBonusTypeByCreditNew($offer->bonus);
                    $this->transactions->addPay($own->id_user, $out_summ, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'out', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Погашение кредита №$own->id");
                    $this->transactions->addPay($offer->id_user, $offer->income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $bonus, "Прибыль по вкладу №$offer->id");
                    $this->transactions->addPay($offer->id_user, $offer->summa, Transactions_model::TYPE_INCOME_REPAY, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $this->base_model->getBonusTypeByCredit($offer->bonus), "Оплата вклада  №$offer->id");
                    $this->accaunt->confirmReturns($own, $offer);
                    $this->base_model->addContributions($offer);
                    return $this->return_object(_e('Перевод успешно совершен с помощью WTDEBIT.'));
                    //accaunt_message($data, _e('Ошибка отправки средств инвестору'), 'error');
                }
            }
            //var_dump( $error );
            //$error = 'OK';

            return $this->return_object_error(_e('Неизвестная ошибка.'));
        }


        if($own->direct == 1 && $own->bonus == 6){
            $this->load->model('Card_model', 'card');
            $this->card->add_summ_to_own($own->account_id, -1 * $out_summ, $own->id);
            $this->db->update('credits', [ 'confirm_return' => '1'], "id = $own->id");
            if($own->garant == Base_model::CREDIT_GARANT_OFF)
                $wt_perc_summ = round($income * (10 / 100), 2);
            else
                $wt_perc_summ = round($income * (garantPercent($own->time) / 100), 2);
            $this->load->model('transactions_model', 'transactions');
            $this->transactions->addPay($own->id_user, $wt_perc_summ, Transactions_model::TYPE_EXPENSE_FEE_REPAY, $own->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, 6, "Отчисление в ".$GLOBALS["WHITELABEL_NAME"]." по займу №$own->id");

            return $this->return_object_error(_e('Перевод успешно совершен. Заявка закроется после подтверждения получения средств контрагентом.'));
        }

        $is_ok = $this->_check_return($own, $rating, $out_summ - $d, $own->bonus);
        /* if ( !$is_ok){
          // посмотрим на другом счете
          $rating = $this->accaunt->recalculateUserRating($own->id_user,null, ($own_trans_bonus==2?5:2) );
          $is_ok = $this->_check_return($own, $rating, $out_summ);
          if ( !$is_ok){
          accaunt_message($data, _e('У вас недостаточно средств для погашения.'), 'error');
          return;
          } else {
          $own_trans_bonus = ($own_trans_bonus==2?5:2);
          }
          }
         */
        if(!$is_ok)
            return $this->return_object_error(_e('У вас недостаточно средств для погашения.'));

        //обновление данных для пересчитанной суммы
        if(Base_model::CREDIT_GARANT_ON == $own->garant && Base_model::CREDIT_WEBTRANSFER_GUARANTEE_FUND == $offer->id_user || $own->bonus == 9){
            if(FALSE === $this->credits->updateCreditOuttimeOutsumIncome($own, date('Y-m-d'), $time_days, $out_summ, $income)){
                return $this->return_object_error(_e('Не удалось погасить займ..'));
            }
        }

        // для гарантий используем ДЕБИТ счет
        if($own_trans_bonus == 9)
            $own_trans_bonus = 6;

        $this->db->trans_start();
        $this->accaunt->confirmReturns($own, $offer);
        $this->load->model("transactions_model", "transactions");
        if($own->bonus == 9)
            $this->transactions->addPay($own->id_user, $income, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $own_trans_bonus, "Погашение гарантии №$own->id");
        else
            $this->transactions->addPay($own->id_user, $out_summ, Transactions_model::TYPE_EXPENSE_INVEST_REPAY, $own->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $own_trans_bonus, "Погашение кредита №$own->id");

        if($offer->blocked_money == 4)
            $this->transactions->addPay($offer->id_user, $income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 4, "Прибыль по вкладу №$offer->id");
        else {

            $bonus = $this->base_model->getBonusTypeByCreditNew($offer->bonus);
            if($offer->bonus == 1)
                $bonus = 3;
            $this->load->model('users_model', 'users');
            if($offer->bonus == 2 && $this->users->isUsaLimitedUser())
                $bonus = 6;

            if($offer->blocked_money == 66)
                $bonus = 6;

            // если бонус=1
            if ( $offer->bonus == 1 && ($offer->sum_own>0 || $offer->sum_loan>0) ){
                $cards_l2 = $this->card->get_cards_by_level($offer->id_user,'LEVEL_2');
                $wt_fee = $this->base_model->culcSums4Webfin($offer);
                $new_bonus_income = round($income * ($offer->sum_own/$offer->summma),2);
                $old_bonus_income = $income - $new_bonus_income;
                $new_wt_fee = round($wt_fee * ($offer->sum_own/$offer->summma),2);
                $old_wt_fee = $wt_fee - $new_wt_fee;

                // бонусную прибыль оправляем на карту если есть с уровнем = 2
                if ($new_bonus_income > 0){
                    if(  !empty($cards_l2) ){
                        $first_card_id = $cards_l2[0];
                        // если получил меньше 50 за все время то отправляем на карту
                        $new_bonus_summ  = $new_bonus_income - $new_wt_fee;
                        if ( $this->transactions->get_bonus_pay_to_card_summ($offer->id_user) + $new_bonus_summ < 50  ){
                            $load_data          = new stdClass();
                            $load_data->id      = 'BRI_'.$offer->id;
                            $load_data->card_id = $first_card_id;
                            $load_data->user_id = $offer->id_user;
                            $load_data->summa   = $new_bonus_summ;
                            $load_data->desc    = 'Receive of new bonus income #'.$offer->id.'(income $'.($new_bonus_income).' less fee $'.$new_wt_fee.')';
                            $response           = $this->card->load($load_data, Card_transactions_model::CARD_RETURN_INCOME, $offer->id);
                            if(false !== $response){
                                $this->transactions->addPay($offer->id_user, $new_bonus_summ, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wtcard', Base_model::TRANSACTION_STATUS_DELETED, 7, "Прибыль по вкладу №$offer->id");
                            } else {
                                // если не удалось отправить на карту - то на бонус 6
                                $this->transactions->addPay($offer->id_user, $new_bonus_income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 6, "Прибыль по вкладу №$offer->id");
                            }
                        // иначе отправляем на проверку Генадичу
                        } else {
                            $this->transactions->addPay($offer->id_user, $new_bonus_summ, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wtcard', Base_model::TRANSACTION_STATUS_VEVERIFY_SS, 7, "Прибыль по вкладу №$offer->id", NULL,  $load_data->desc   );
                        }

                    } else {
                        $this->transactions->addPay($offer->id_user, $income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 6, "Прибыль по вкладу №$offer->id");
                    }
                }

                if ( $old_bonus_income > 0 )
                    $this->transactions->addPay($offer->id_user, $old_bonus_income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, 3, "Прибыль по вкладу №$offer->id");

            } else {
                // все остальные бонусы
                $this->transactions->addPay($offer->id_user, $income, Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $bonus, "Прибыль по вкладу №$offer->id");
            }
        }
        // $this->transactions->addPay($offer->id_user, $offer->summa, Transactions_model::TYPE_INCOME_REPAY, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $this->base_model->getBonusTypeByCreditNew($offer->bonus), "Оплата вклада  №$offer->id");
        if($own->bonus != 9){
            $this->transactions->addPay($offer->id_user, $offer->summa, Transactions_model::TYPE_INCOME_REPAY, $offer->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $this->base_model->getBonusTypeByCredit($offer->bonus), "Оплата вклада  №$offer->id");
            $this->base_model->addContributions($offer);
        }


        $this->db->trans_complete();


        return $this->return_object(_e('Перевод успешно совершен'));
    }

    public function delete_loan($id){ //  $user_id необходимо чтоб был установлен в account_model!
        $id = (int) $id;
        if ($this->accaunt->getCreditState($id) != 1)
            return $this->return_object_error(_e('Эта заявка уже не может быть удалена.'));
        $this->accaunt->hideDebit($id);
        return $this->return_object(_e('Заявка успешно удалена'));
    }


    public function preSellCertificat($invest, $sell = true, $only_finish = false){
        $data = new stdClass();
        if(Base_model::CREDIT_TYPE_INVEST != $invest->type ||
            Base_model::CREDIT_STATUS_ACTIVE != $invest->state ||
            Base_model::CREDIT_GARANT_ON != $invest->garant){
            $m = ($sell) ? _e("Вы не можете продать сертификат") : _e("Вы не можете купить сертификат");
            return $this->return_object_error($m.(($invest->id) ? " СС$invest->id" : ""));
        }

        if(empty($invest) || Base_model::CREDIT_CERTIFICAT_PAID == $invest->certificate){
            return $this->return_object_error(_e('account/data32'));
        }
        if($invest->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF){
            if(1 * 24 * 60 * 60 >= (time() - strtotime($invest->date_kontract))){
                return $this->return_object_error(_e('account/data33'));
            }
            if ( $invest->bonus != 7)
            if((0 < strtotime($invest->date_active) && 1 * 60 * 60 >= (time() - strtotime($invest->date_active)))
            /*|| (0 < strtotime($invest->date_active) && Base_model::CREDIT_EXCHANGE_OFF == $invest->exchange)*/){
                return $this->return_object_error(_e('account/data33'));
            }
        }


        if($sell){
            $statictic = getStatistic();

            if(empty($statictic->today)){
                return $this->return_object_error(_e('account/data34'));
            }

            if($only_finish && ((time() - strtotime($invest->out_time." 23:59:59") <= 0)))
                return $this->return_object_error(_e('Вы не можете продать сертификат'));

            if(($invest->percent <= $statictic->today->avg_rate ) && ((time() - strtotime($invest->out_time." 00:00:00") <= 0))){
                return $this->return_object_error(_e('account/data35'));
            }
        }
        return $this->return_object('OK');
    }

    public function exchangeCertificate($id, $summ, $user_id = NULL){
        $data    = new stdClass();
        if ( empty($user_id))
            $user_id = $this->accaunt->get_user_id();
        require_once APPPATH.'controllers/user/Security.php';
        $security_check_result = Security::checkSecurity($user_id, true, TRUE, TRUE);
        if($security_check_result !== FALSE ){
            return $this->return_object_error(_e('Неверный код. Код ошибки: ').$security_check_result);
        }

        $i = $this->credits->getDebit($id);
        if ( $i->id_user != $user_id ){
            return $this->return_object_error(_e('Вклад не найден'));
        }


        if ($i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_OFF && ($i->summa > $summ || $i->out_summ < $summ) ||
            $i->garant_percent == Base_model::CREDIT_INVEST_ARBITRAGE_PARTNER && (10 > $summ || $i->summa < $summ)){
            return $this->return_object_error(_e('exchange/data13'));
        }
        if (Base_model::CREDIT_BONUS_ON == (int) $i->bonus){
            return $this->return_object_error(_e('exchange/data14'));
        }
        $this->credits->setExchange($id, $summ, $user_id);
        return $this->return_object(_e('Операция успешно выполнена'));


    }

    public function sellCertificate($id, $user_id = NULL, $only_finish = false){
//        $data    = new stdClass();
        if ( empty($user_id))
            $user_id = $this->accaunt->get_user_id();
        require_once APPPATH.'controllers/user/Security.php';
        $security_check_result = Security::checkSecurity($user_id, true, TRUE, TRUE);
        if($security_check_result !== FALSE){
            return $this->return_object_error(_e('Неверный код. Код ошибки: ').$security_check_result);
        }

        $this->accaunt->offUserRateCach(); // для фин операций нужно без кеша

        $invest = $this->accaunt->getUserCredit($id);
        if ( $invest->id_user != $user_id ){
            return $this->return_object_error(_e('Вклад не найден'));
        }

        $r = $this->preSellCertificat($invest, true, $only_finish);
        if($r->status=='error')
            return $this->return_object_error($r->message);


        $sellResult = $this->accaunt->sellCertificate($invest);
        if($sellResult !== TRUE){
            return $this->return_object_error($sellResult);
        }

        $this->load->model('credits_model', 'credits');
        $r = $this->credits->checkSellCertificate($id);
        if(false !== $r){
            return $this->return_object_error(_e($r));
        }

        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, "Продан сертификат $id", 'common', $user_id);


        $replace = ['rate' => countSertificate(4), 'price' => countCertificate($invest->summa, $invest->date_kontract)];
        //$this->mail->admin_sender('sell-certificate-admin', $this->accaunt->get_user_id(), $replace, (int) $id);
        $this->mail->user_sender('sell-certificate-user', $user_id, $replace, (int) $id);

        $summ = round(countCertificate($invest->summa, $invest->date_kontract), 2);
        $this->load->model("transactions_model", "transactions");
        $b    = $this->base_model->getBonusTypeByCredit($invest->bonus);
        //  esli prodavat certificat ot bonus 7 - nujno chtob na debit padalo
        //if ($b==7) $b=6;
        if($invest->bonus != 7){
            $this->transactions->addPay($invest->id_user, $summ, Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID, $invest->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $b, "Продажа сертификата № СС$invest->id от ".date('d/m/Yг.'));


        }

        $this->postSellCertificat($invest, $summ);

        return $this->return_object(_e('account/data37'));
    }

    public function postSellCertificat($invest, $summ){
        // Проверка условий овердрафта
        if(Base_model::CREDIT_OVERDRAFT_ON == $invest->overdraft)
            $this->base_model->overdraft($invest, $summ - $invest->summa);

        // Возврат бонусов системе
        if(Base_model::CREDIT_BONUS_ON == $invest->bonus)
            $this->base_model->returnBonuses($invest);
    }
}
