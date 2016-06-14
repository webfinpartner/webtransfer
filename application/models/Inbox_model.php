<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH.'models/Accaunt_model.php';

class Inbox_model extends Accaunt_model{

    public $tableName = "inbox";

    const SYSTEM_ID_USER    = 500150;

    function __construct(){
        parent::__construct();
        $this->load->model('Accaunt_model', 'accaunt');
        $this->set_user($this->accaunt->get_user_id());
    }

    private function _try_send_over_social($user_id, $topic, $text){
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('users_model', 'users');
        $social_id = $this->usersFilds->getUserFild($user_id, 'id_network');
        //$social_id=1; $user_id = 92156962;
        if($social_id){
            $this->load->library('soc_network');
            $this->soc_network->createMsg(self::SYSTEM_ID_USER, $user_id, $text, $topic);
            return TRUE;
        }
        return FALSE;
    }

    public function addMessage($topic, $idUserRecipient){
        $this->load->model("users_model", "users");
        $me   = $this->users->getCurrUserId();
        $text = sprintf(_e("У вас новое сообщение от пользователя %d по теме: %s%s%s"), $me, "<a href='".site_url('partner/support')."'>", $topic, '</a>');
        $this->writeInbox($me, $idUserRecipient, $text);
    }

    public function addNews($text){
        $this->load->model("users_model", "users");
        $ids  = $this->users->getAllUsers();
        $mess = $this->getAllUsersWithNewsMessages();
        $new  = array();
        foreach($ids as $row){
            if(!isset($mess[$row->id_user]))
                $new[] = $row->id_user;
        }
        foreach($new as $id)
            $this->writeNews2Inbox($id, $text);

        $this->updateNews($text);
    }

    public function getAllUsersWithNewsMessages(){
//$this->db->cache_on();
        $ids                = $this->db->select("user_to")->get_where($this->tableName, array("cause" => "news"))->result();
        $res                = array();
        foreach($ids as $row)
            $res[$row->user_to] = true;
        return $res;
//$this->db->cache_off();
    }

    public function writeInbox($from, $to, $text, $debit = 0, $read = 0, $debit_account_type = NULL, $debit_account_id = NULL){
        if($from == 'admin'){
            $admin = 1;
            $from  = 0;
            if($this->_try_send_over_social($to, _e('Без темы'), $text))
                return;
        } else {
            $admin = 2;
        }
        $this->db->insert($this->tableName, array(
            'user_to'            => $to,
            'user_from'          => $from,
            'status'             => ($read ? 4 : 1),
            'sender_view'        => 2,
            'admin'              => $admin,
            'message'            => $text,
            'debit'              => $debit,
            'debit_account_type' => $debit_account_type,
            'debit_account_id'   => $debit_account_id,
            'cause'              => ($read ? 'agree' : '')
            )
        );
    }

    public function writeNews2Inbox($to, $text){
        if($this->_try_send_over_social($to, _e('Новости'), $text))
            return;

        $this->db->insert($this->tableName, array(
            'user_to'     => $to,
            'user_from'   => 0,
            'status'      => 1,
            'sender_view' => 2,
            'admin'       => 1,
            'message'     => $text,
            'debit'       => 0,
            'cause'       => "news"
            )
        );
    }

    public function writeMess2Inbox($to, $text){
        if($this->_try_send_over_social($to, _e('Без темы'), $text))
            return;

        $this->db->insert($this->tableName, array(
            'user_to'     => $to,
            'user_from'   => 0,
            'status'      => 1,
            'sender_view' => 2,
            'admin'       => 1,
            'message'     => $text,
            'debit'       => 0,
            'cause'       => "mess"
            )
        );
    }

    public function updateNews($text){
        $this->db->update($this->tableName, array(
            'status'         => 1,
            'recipient_view' => 1,
            'date'           => date("Y-m-d H:i:s"),
            'message'        => $text,
            ), array('cause' => "news")
        );
    }

    public function getOutbox(){
        return $this->inbox(false);
    }

    public function getInbox(){
        return $this->inbox(true);
    }

    public function inboxNew($inbox, $limit = 10){
        if(true == $inbox){
//$this->db->cache_on();
            $q = "SELECT i.*, '' as id_new, '' as master, '' as lang, '' as title, '' as text, '' as url, '' as data, '' as foto, '' as to_all, '' as ids_viewed, '' as ids_deleted "
                ."FROM inbox i "
                ."WHERE (i.user_to = $this->id_user AND i.recipient_view = 1) OR (i.user_from = $this->id_user and i.sender_view = 1)"
                ."UNION "
                ."SELECT CONCAT_WS('_', 'news', n.id_new) id, $this->id_user user_to, 1 recipient_view, 0 user_from, 2 sender_view, 1 admin, 0 debit, NULL debit_account_type, NULL debit_account_id, n.title message, 'news' cause, CONCAT_WS(' ', n.`data`, '00:00:00') DATE, IF(nv.`status` IS NULL,1,2) as status, n.* "
                ."FROM news n "
                ."LEFT JOIN news_view nv ON nv.id_post = n.id_new  and nv.id_user = $this->id_user "
                ."WHERE n.to_all = 1 AND ( nv.status is null or nv.status != 2 ) AND n.lang = '".$this->lang->lang()."' "
                ."ORDER BY date DESC LIMIT $limit";

            $data = $this->db->query($q)->result(); //print_r($this->db->last_query());die;
//$this->db->cache_off();
        } else {
//$this->db->cache_on();
            $data = $this->db->where(
                        array((($inbox) ? 'user_to' : 'user_from')                => $this->id_user,
                            (($inbox) ? 'recipient_view !=' : 'sender_view !=') => "3"))
                    ->order_by('date desc')
                    ->get('inbox')->result();
//$this->db->cache_off();
        }
        $out = array();
        foreach($data as $item){
            if(!empty($item->debit)){
                $item->debit_info = $this->getCredit($item->debit);
            }
            $out[] = $item;
        }
        return $out;
    }

    public function inbox($inbox){
        if(true == $inbox){
//$this->db->cache_on();
            $q    = "SELECT i.*, '' as id_new, '' as master, '' as lang, '' as title, '' as text, '' as url, '' as data, '' as foto, '' as to_all, '' as ids_viewed, '' as ids_deleted "
                ."FROM inbox i "
                ."WHERE i.user_to = $this->id_user AND i.recipient_view <> 3 "
                ."UNION "
                ."SELECT CONCAT_WS('_', 'news', n.id_new) id, $this->id_user user_to, 1 recipient_view, 0 user_from, 2 sender_view, 1 admin, 0 debit, NULL debit_account_type, NULL debit_account_id, n.title message, 'news' cause, CONCAT_WS(' ', n.`data`, '00:00:00') DATE, IF(nv.`status` IS NULL,1,2) as status, n.* "
                ."FROM news n "
                ."LEFT JOIN news_view nv ON nv.id_post = n.id_new  and nv.id_user = $this->id_user "
                ."WHERE n.to_all = 1 AND ( nv.status is null or nv.status != 2 ) AND n.lang = '".$this->lang->lang()."' "
                ."ORDER BY date DESC";
            $data = $this->db->query($q)->result(); //print_r($this->db->last_query());die;
//$this->db->cache_off();
        } else {
//$this->db->cache_on();
            $data = $this->db->where(
                        array((($inbox) ? 'user_to' : 'user_from')                => $this->id_user,
                            (($inbox) ? 'recipient_view !=' : 'sender_view !=') => "3"))
                    ->order_by('date desc')
                    ->get('inbox')->result();
//$this->db->cache_off();
        }
        $out = array();
        foreach($data as $item){
            if(!empty($item->debit)){
                $item->debit_info = $this->getCredit($item->debit);
            }
            $out[] = $item;
        }
        return $out;
    }

    /*
     * Tickets
     */

    function getMessage($id){
        return $this->db->get_where('inbox', array('id' => $id))->row();
    }

    //получение заявки по данным
    function getMessageByUsers($user_from, $user_to, $debit){
//$this->db->cache_on();
        return $this->db->get_where('inbox', array('debit' => $debit, 'user_from' => $user_from, 'user_to' => $user_to))->row();
//$this->db->cache_off();
    }

    function getMessagesByUsers($user_from, $user_to, $debit = 0){
//$this->db->cache_on();
        if(empty($user_from) || empty($user_to))
            return FALSE;

        $this->db->where(array('user_from' => $user_from, 'user_to' => $user_to));

        if($debit !== 0)
            $this->db->where('debit', $debit);

        return $this->db->limit(1)
                ->get('inbox')
                ->row();
//$this->db->cache_off();
    }

    public function countNew(){
        if(!isset($this->countNew)){
//$this->db->cache_on();
            $q              = "SELECT i.*, '' as id_new, '' as master, '' as lang, '' as title, '' as text, '' as url, '' as data, '' as foto, '' as to_all, '' as ids_viewed, '' as ids_deleted "
                ."FROM inbox i "
                ."WHERE (i.user_to = $this->id_user AND i.recipient_view = 1) OR (i.user_from = $this->id_user and i.sender_view = 1)"
                ."UNION "
                ."SELECT CONCAT_WS('_', 'news', n.id_new) id, $this->id_user user_to, 1 recipient_view, 0 user_from, 2 sender_view, 1 admin, 0 debit, NULL debit_account_type, NULL debit_account_id, n.title message, 'news' cause, CONCAT_WS(' ', n.`data`, '00:00:00') DATE, 1 `status`, n.* "
                ."FROM news n "
                ."LEFT JOIN news_view nv ON nv.id_post = n.id_new  and nv.id_user = $this->id_user "
                ."WHERE n.to_all = 1 AND nv.status is null AND n.lang = '".$this->lang->lang()."' ";
            //   . "ORDER BY date DESC";
            $this->countNew = count($this->db->query($q)->result());
        }
        return $this->countNew;
//$this->db->cache_off();
    }

    function checkReapet($debit){
        $item = $this->db->get_where('inbox', array('user_from' => $this->id_user, 'debit' => $debit))->row();
        return (!empty($item)) ? false : true;
    }

    public function setView($id){
        $filter      = array("id_post" => $id, 'id_user' => $this->id_user);
        $readsString = $this->db->get_where("news_view", $filter)->row();
        if(empty($readsString)){
            $this->db->insert('news_view', $filter);
            return $this->db->insert_id();
        } else {
            return $readsString->id;
        }
    }

    public function setReadNews($id){
        $this->setView($id);
    }

    public function deleteNews($id){
        $id = $this->setView($id);
        $this->db->update('news_view', array('status' => 2), array('id' => $id));
    }

    function setRead($id){
        $item = $this->getMessage($id);
        if($item->user_to == $this->id_user and $item->status == 1)
            $this->update($id, array('status' => 2));

        if($item->user_to == $this->id_user and $item->recipient_view != 3)
            $this->update($id, array('recipient_view' => 2));
        else if($item->user_from == $this->id_user and $item->sender_view != 3)
            $this->update($id, array('sender_view' => 2));
    }

    function setNew($item, $id){
        if($item->user_to == $this->id_user)
            $this->update($id, array('sender_view' => 1));
        else if($item->user_from == $this->id_user)
            $this->update($id, array('recipient_view' => 1));
    }

    function update($id, $where){
        $this->db->where('id', $id)->update('inbox', $where);
    }

    function cancel($id){
        $item = $this->getMessage($id);
        if(($item->user_to == $this->id_user or $item->user_from == $this->id_user) and ( $item->status == 1 or $item->status == 2)){
            $this->update($id, array('status' => 3, 'cause' => $this->input->post('cause', true)));
            $this->setNew($item, $id);
        }
    }

    function delete($id){
        $item = $this->getMessage($id);
        if($item->status == 3 or $item->status == 4 or ( $item->status == 2 and $item->admin == 1)){
            if($item->user_to == $this->id_user and $item->recipient_view != 3)
                $this->update($id, array('recipient_view' => 3));
            else if($item->user_from == $this->id_user and $item->sender_view != 3)
                $this->update($id, array('sender_view' => 3));
        }
    }

    function agree($id){
        $this->load->model("credits_model", "credits");
        $item = $this->getMessage($id);
        if($item->user_to == $this->id_user and ( $item->status == 1 or $item->status == 2)){
            $debit = $this->getCredit($item->debit);
//			if($debit->type == 2 and $debit->garant == 1) return;


            $this->update($id, array('status' => 4)); //сообщение прочитано
            $_POST['garant'] = $debit->garant;
            $newID           = $this->credits->addOrder($debit, $item->user_from); //новая заявка

            $accessMoney = $this->base_model->getMoney() - $this->base_model->getBonus();
            //у директа не должно быть транзакций
            if(($debit->overdraft or $accessMoney > $debit->summa) and $debit->type == Base_model::CREDIT_TYPE_INVEST){
                $credit = $this->accaunt->getCredit($newID);
                if(!empty($credit) && $credit->direct == 0 && $debit->direct == 0){
                    $res = $this->accaunt->confirmPayments($debit, $credit);
                    if(0 == $res){
                        $this->load->model("transactions_model", "transactions");
                        $this->transactions->addPay($debit->id_user, $debit->summa, Transactions_model::TYPE_EXPENSE_INVEST, $debit->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, Base_model::TRANSACTION_BONUS_OFF, "Снятие средств по заявке № $debit->id.");
                        $this->transactions->addPay($credit->id_user, $debit->summa, Transactions_model::TYPE_INCOME_LOAN, $credit->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, Base_model::TRANSACTION_BONUS_OFF, "Пополнение средств по заявке № $credit->id.");
                    }
                }
            }

            $this->setNew($item, $id);
            $this->db->where(array('debit' => $item->debit, 'id !=' => $id, 'status !=' => '3'))->update('inbox', array('status' => 3, 'sender_view' => 1, 'cause' => 'agree'));

            if($this->input->post('all') == 1)
                $type = 'ready-take-credit';
            if($this->input->post('all') == 2)
                $type = 'ready-give-credit';
            if(!empty($type)){
                $this->db->where(array('id !=' => $id, 'user_from' => $this->id_user, 'message' => $type, 'status !=' => '3'))
                    ->update('inbox', array('status' => 3, 'recipient_view' => 1, 'cause' => 'agree'));
            }
        }
    }

    //Обработка заявок без подтерждения
    function manual_agree($credit, $user_to, $credit_bonus = -1, $trans_bonus = Base_model::TRANSACTION_BONUS_OFF, $card_id = NULL, $credit_account_type = NULL, $credit_account_id = NULL){
        $this->load->model("transactions_model", "transactions");
        $this->load->model("credits_model", "credits");
        $this->load->model("users_model", "users");

        $this->db->trans_start();
        if(!is_object($credit))
            $credit = $this->getCredit($credit); //строка заявки
//            else
//                $credit = $this->getCredit($credit->id);


        if($credit_bonus == -1)
            $credit_bonus = $credit->bonus;
        //$credit_bonus  = $trans_bonus;

        if(Base_model::CREDIT_STATUS_SHOWED != $credit->state)
            return 2;


        if($credit->type == Base_model::CREDIT_TYPE_CREDIT){
            $credit_bonus = $trans_bonus;
            $this->credits->checkBonus($credit);
        }
        //echo "manual_agree: $user_to, $credit_bonus = -1, $trans_bonus = Base_model::TRANSACTION_BONUS_OFF , $card_id = NULL";


        $newId          = $this->credits->addOrder($credit, $user_to, $credit_bonus, -1, -1, $card_id, $credit_account_type, $credit_account_id); //создает предложившиму пользователю инвестицию
        $new_credit     = $this->getCredit4Update($newId);
        if( !$new_credit || $newId <= 0 ) 
            return 1;
        $me             = $this->users->getCurrUserId();
        $social_bonuses = $this->transactions->get_social_bonuses_today_count($me);

        // бонусы за опубликование на стене
        if($credit->type == Base_model::CREDIT_TYPE_INVEST){

            $bonus_vk_fb = 0;
            if(isset($_POST['post_vk']) && (int) $_POST['post_vk'] > 0)
                $bonus_vk_fb += config_item('social_wallpost_bonus');
            if(isset($_POST['post_fb']) && (int) $_POST['post_fb'] > 0)
                $bonus_vk_fb += config_item('social_wallpost_bonus');
            if($social_bonuses == 0 && $bonus_vk_fb > 0){
                $this->credits->setPayment($newId, 'soc_bonus='.$bonus_vk_fb);
                $new_credit = $this->getCredit4Update($newId);
                $this->credits->checkBonus($new_credit);
            }
            if($social_bonuses > 0 && $bonus_vk_fb > 0){
                $this->credits->setPayment($newId, 'bonus='.$social_bonuses);
                $new_credit = $this->getCredit4Update($newId);
                $this->credits->checkBonus($new_credit);
            }
        }

        //у директа не должно быть транзакций
        if($credit->direct == 1){
            if($credit->bonus == 7)
                $this->accaunt->confirmPayments($credit, $new_credit, Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM);
            elseif($credit->bonus == 6){
                // у бонуса 2  - ждем подтверждения у заеищика, а у инвестора сразу ставим
                $this->db->where('id', $credit->id)->update('credits', array('confirm_payment' => Base_model::CREDIT_CONFIRM_PAYMENT_CONFIRM));
            }
            $this->db->trans_complete();
            return 0;
        }

        $bonus = (Base_model::CREDIT_BONUS_ON == $credit->bonus) ? Base_model::TRANSACTION_BONUS_ON : Base_model::TRANSACTION_BONUS_OFF;
        if(Base_model::CREDIT_BONUS_CREDS_CASH == $credit->bonus){
            $bonus = Base_model::TRANSACTION_BONUS_CREDS_CASH;
        }

        // снимим средства у дающего кредита с того же счета что и он заявил для выдачи кредита
        if($credit->bonus == 2 || $credit->bonus == 5 || $credit->bonus == 6)
            $bonus = $credit->bonus;

        //обмен данных для заявок инвестирования
        if($credit->type == Base_model::CREDIT_TYPE_CREDIT){
            $a          = $new_credit;
            $new_credit = $credit;
            $credit     = $a;

            $b           = $bonus;
            $bonus       = $credit_bonus;
            $trans_bonus = $b;
        }

        $remove_summ = $credit->summa;
        if ($credit->type == Base_model::CREDIT_TYPE_INVEST && $bonus == 6 && ($credit->arbitration == 2||$credit->arbitration == 3))
            $remove_summ -= $credit->sum_arbitration;
        //echo "manual_agree: $bonus, $trans_bonus";
        $res = $this->accaunt->confirmPayments($credit, $new_credit);
        if ($res == 0){
            $this->transactions->addPay($credit->id_user, $remove_summ, Transactions_model::TYPE_EXPENSE_INVEST, $credit->id, 'wt', Base_model::TRANSACTION_STATUS_REMOVED, $bonus, "Снятие средств по заявке №{$credit->id}");
            $this->transactions->addPay($new_credit->id_user, $credit->summa, Transactions_model::TYPE_INCOME_LOAN, $new_credit->id, 'wt', Base_model::TRANSACTION_STATUS_RECEIVED, $trans_bonus, "Пополнение средств по заявке №{$new_credit->id}");
        }
        $this->db->trans_complete();
        return 0;
    }

}
