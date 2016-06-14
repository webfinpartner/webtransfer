<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Partner_model extends Accaunt_model{

    private $_usersCount = 0;

    public function __construct(){
        parent::__construct();
        $this->load->model('accaunt_model', 'accaunt');
        $this->id_user = $this->accaunt->get_user_id();
    }

    public function banner(){
        return $this->db->get_where('partner_banner', array('active' => 1, "lang" => $this->lang->lang()))->result();
    }

    public function get_header_info(){
        $out               = $this->accaunt->get_header_info();
        $out['myChildren'] = $this->getMyChildren();
        $out['soon']       = $this->getMySoonMoney();
        $out['money']      = $this->base_model->getMoney();

        return $out;
    }

//    public  function getPartnerTransaction($progress=1)
//    {
//            if($progress==2)
//                    $this->db->select('c.*')->join('credits c','c.id=p.id_debit');
//            return $this->db->select('p.*')->where(array('p.id_user'=>$this->id_user,'progress'=>$progress))->get('partner_transaction p')->result();
//    }

    public function getMyChildren(){
        return $this->db->where(array('parent' => $this->id_user))->count_all_results('users');
    }

    public function getCountUsers(){
        return $this->_usersCount;
    }

    public function getMySoonMoney(){
        $summa = 0;
        $data  = $this->getExpectedIncome();

        foreach($data as $item)
            $summa += $item->income;

        return $summa;
    }

//    public function getAccessDocuments( $user_id  ) {
//        if (!$user_id)
//            return false;
//
//        $count = $this->db->where('id_user', $user_id)
//                        ->where_in('num', 1)
//                        ->where_in('state', 2)
//                        ->count_all_results('documents');
//        return ($count > 0) ? true : false;
//    }

    public function getUsers($user = NULL, $per_page = NULL, $page = NULL, $statuses = ['blocked' => true, 'not_verifyed' => true, 'verifyed' => true, 'start' => NULL, 'step' => NULL]){
        $this->load->model('users_filds_model', 'usersFilds');
        $this->load->model('users_photo_model', 'users_photo');
        if(null == $user)
            $user = $this->id_user;
//        if($this->id_user != $user) $this->db->where(array('reg_date >' => "2014-08-14"));
        if(!is_null($page) && !is_null($per_page))
            $this->db->limit($per_page, $page);

        $selct = 'id_user, name, sername, email, phone, skype, reg_date, state';
        if($this->id_user != $user) $selct = 'id_user, name, sername, skype, reg_date, state';

        $data = $this->code->db_decode(
            $this->db->select($selct)
            ->where(array('parent' => $user))
            ->order_by('reg_date  desc')
            ->get('users')
            ->result()
        );

//        $this->usersFilds->getUsersByIds($data);
        //if ( get_current_user_id() == 99676729)            echo $this->db->last_query()." ".(microtime(TRUE)-$s).' ';


        $count = 0;
        $conf  = $statuses;
        unset($conf['start']);
        unset($conf['step']);

        foreach($data as $id => &$item){
            $item->parent = $user;
            $item->status = $this->accaunt->isUserAccountVerified($item->id_user);
            $t            = (Base_model::USER_STATE_OFF == $item->state) ? 'blocked' : (($item->status) ? 'verifyed' : 'not_verifyed');
            if(!$statuses[$t]){ unset($data[$id]); continue; }
            $count++;
            if(isset($statuses['start']) && isset($statuses['step']) && ($statuses['start'] >= $count || $count > ($statuses['start'] + $statuses['step']))){
                unset($data[$id]);
                continue;
            }
            $item->social   = $this->accaunt->getSocialList($item->id_user, true);
            $item->wt_social= '';
            $social_id = get_social_id($item->id_user);
            if ( !empty($social_id) ) $item->wt_social = "https://webtransfer.com/social/profile/$social_id?lang="._e('lang');

            $item->foto     = '';//getUserAvatar($item->id_user);
            if( empty($item->foto) ){
                foreach ($item->social as $social) {
                    if (!empty($social->foto)) {
                        $item->foto_social = $social->foto;
                        break;
                    }
                }
            }
            if($this->id_user == $user)
                $item->subusers = $this->getUsers($item->id_user, NULL, NULL, $conf);

//                $nickname = $this->usersFilds->getUserNickname($item->id_user);
        }

        //if ( get_current_user_id() == 99676729)            echo $this->db->last_query()." ".(microtime(TRUE)-$s).'<hr>';
        $this->_usersCount = $count;

        return $data;
    }

    public function getExpectedIncome(){
        $this->load->model('users_model', 'users');
        if(!isset(viewData()->user->volunteer)){ // проверка для крона(dayly_balance)
            $this->accaunt->set_user($this->id_user);
            viewData()->user = $this->accaunt->getMainUser();
        }
        $is_meVolunteer = $this->users->isVolanteer();
        if($is_meVolunteer)
            $this->db
                ->join('users u', 'u.id_user = c.id_user')
                ->where('c.type = '.Base_model::CREDIT_TYPE_INVEST.' '
                    ."AND (u.parent = $this->id_user OR u.id_volunteer = $this->id_user)");
        else
            $this->db
                ->join('users u', 'u.id_user = c.id_user')
                ->where(array('u.parent' => $this->id_user, 'c.type' => Base_model::CREDIT_TYPE_INVEST));

        $data = $this->db
            ->select(
                'c.*'
                .', u.name'
                .', u.sername'
                .', u.id_user'
                .', u.id_volunteer'
            )
            ->order_by('id desc')
            ->where('(c.bonus = 6 OR c.bonus = 7)')
            ->where('c.state', Base_model::CREDIT_STATUS_ACTIVE)
            ->where("c.active", Base_model::CREDIT_EXCHANGE_STATUS_NORMAL)
            ->get('credits c')
            ->result(); //print_r($this->db->last_query());die;

        $res = array();
        $this->load->model('users_filds_model', 'usersFilds');
        $this->usersFilds->getUsersByIds($data);
        foreach($data as $item){
            //   if ($item->bonus) continue;
            $id_valunteer = (int) $item->id_volunteer;
            $is_fromChild = ($this->id_user == $id_valunteer);
            $is_bonus     = (Base_model::CREDIT_BONUS_ON == $item->bonus || Base_model::CREDIT_BONUS_CREDS_CASH == $item->bonus);

            if($is_bonus)
                continue;
//            if($is_meVolunteer && $is_fromChild && $is_old) continue;

            $item->name    = $this->code->decode($item->name);
            $item->sername = $this->code->decode($item->sername);

            $summa = $this->base_model->culcSums4Webfin($item);

            if($is_meVolunteer && $is_fromChild){
                $item->income    = $this->base_model->culcVolunteerCost(true, $summa, $item);
                $item->valunteer = true;
            } else {
                $item->income    = $this->base_model->culcPartnerCost($this->id_user, $summa, $item);
                $item->valunteer = false;
            }

            $nickname = $this->usersFilds->getUserNickname($item->id_user);
            if(FALSE !== $nickname){
                $item->name    = $nickname;
                $item->sername = '';
            }

            $res[] = $item;
        }
        return $res;
    }

    public function setCurrentUserId($user_id){
        if(empty($user_id))
            return 1;
        $this->id_user = $user_id;
        return 0;
    }

//    # Получение баланса пользователя
//    public function getPartnerBalance($id_user = FALSE)
//    {
//        if ($id_user)
//        {
//            $query = $this->db->query('SELECT `id`, `status`, `summa`, `bonus` FROM `transactions` WHERE `id_user` = ' . $id_user . ' AND `bonus` = 6');
//            foreach ($query->result_array() as $result)
//            {
//                if ($result['status'] == 1) $balance[$result['bonus']] = $balance[$result['bonus']] + round($result['summa'], 5);
//                if ($result['status'] == 3) $balance[$result['bonus']] = $balance[$result['bonus']] - round($result['summa'], 5);
//                $count++;
//            }
//            $int = (isset($balance[2]) ? $balance[2] : 0) + (isset($balance[1]) ? $balance[1] : 0);
//        }
//        return array('0' => isset($balance[2]) ? round($balance[2], 2) : 0, '1' => isset($balance[1]) ? round($balance[1], 2) : 0, '2' => round($int, 2), '3' => isset($count) ? $count : 0);
//    }
//    # Получение транзакций пользователя
//    public function getPays($limit, $per = 0, $where = array()) {
//        return $this->db->limit($limit, $per)->order_by('date desc')->where($where)->get('transactions')->result();
//    }
//    # Получаем информацию о партнере
//    public function parentAbout($id_user)
//    {
//        $user = $this->db->where(array('id_user =' => (int) $id_user, 'parent' => $this->id_user))->get('users')->result();
//        return empty($user) ? FALSE : $user;
//    }
//    # Список кредитов партнера
//    public function get_partner_credits($type = FALSE, $id_user = FALSE) {
//        return $this->get_debits($type, $id_user);
//    }
//    # Кредиты/Вклады
//    private function get_debits($type, $id_user = FALSE) {
//
//        if ($where_in[0] == 'state' && !$where)
//            $where = array('id !=' => '0');
//        else if (!$where)
//            $where = array('state !=' => '7');
//
//        if ($where_in) $this->db->where_in($where_in[0], $where_in[1]);
//        $data = $this->db
//                ->order_by('date  desc')
//                ->where(array('type' => $type, 'id_user =' => $id_user))
//                ->get('credits')
//                ->result();
//
//
//
//        return $data;
//    }
}
