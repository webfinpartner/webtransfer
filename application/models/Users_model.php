<?php
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model{

    public $tableName    = "users";

    const DOC_REQUEST_ENABLE   = 1;
    const DOC_REQUEST_DECABLE  = 2;
    const PAYOUT_LIMMIT_OFF    = 0;
    const INVAST_ARBITRAGE_ON  = 1;
    const INVAST_ARBITRAGE_OFF = 2;
    const PARTNER_CHANGE_STATUS_NEW     = 0;
    const PARTNER_CHANGE_STATUS_ACCEPT  = 1;
    const PARTNER_CHANGE_STATUS_DECLINE = 2;

    public function __construct(){
        parent::__construct();
        $this->load->library('code');
        if(!isset($_SESSION['_is_new']))
            $_SESSION['_is_new'] = [];
    }

    public function save2AuthToken($id_user, $token){
        $this->db->update($this->tableName, array('auth_token' => $token), "id_user = $id_user", 1);
    }

    public function gat2AuthToken($id_user){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            return $data->auth_token;
        } else
            return $this->db->get_where($this->tableName, "id_user = $id_user")->row('auth_token');
    }

    public function add_payout_limit($id, $val){
        return $this->db->update($this->tableName, array('payout_limit' => $val), array("id_user" => $id), 1);
    }

    public function get_payout_limit($id){
        $cur_user = $this->getCurrUserId();
        if($id == $cur_user){
            $data = $this->getCurrUserData();
            return $data->payout_limit;
        } else
            return $this->db->get_where($this->tableName, array("id_user" => $id))->row('payout_limit');
    }

    public function swch_payout_doc_request($id){
        $val = $this->db->get_where($this->tableName, array("id_user" => $id))->row('doc_request');
        $val = ($val == self::DOC_REQUEST_ENABLE) ? self::DOC_REQUEST_DECABLE : self::DOC_REQUEST_ENABLE;
        $this->db->update($this->tableName, array('doc_request' => $val), array("id_user" => $id), 1);
        return $val;
    }

    public function is_doc_request($id){
        $cur_user = $this->getCurrUserId();
        if($id == $cur_user){
            $data = $this->getCurrUserData();
            return $data->doc_request;
        } else
            return $this->db->get_where($this->tableName, array("id_user" => $id))->row('doc_request');
    }

    public function debit_user($id){
        $cur_user = $this->getCurrUserId();
        if($id == $cur_user){
            $data   = $this->getCurrUserData();
            $result = $data;
        } else
            $result           = $this->code->db_decode($this->db->select('id_user, name, sername, patronymic, phone')->where('id_user', $id)->from('users')->get()->row());
        if(!empty($result))
            return $result;
        $data             = new stdClass();
        $data->id_user    = "0";
        $data->name       = "";
        $data->sername    = "";
        $data->patronymic = "";
        $data->phone      = "";
        return $data;
    }

    public function get_frends4chat(){
        $id_user = $this->getCurrUserId();

        if(empty($id_user))
            return FALSE;

        $sql = (
            "SELECT "
            ."u.id_user userid, "
            ."u.name, "
            ."u.sername, "
            ."u.id_user link, "
            ."cs.lastactivity lastactivity, "
            ."cs.status, "
            ."cs.message, "
            ."cs.isdevice "
            ."FROM users u "
            ."LEFT JOIN cometchat_status cs "
            ."ON u.id_user = cs.userid "
            ."WHERE u.parent = '$id_user' "
            ."AND (('".time()."' - cs.lastactivity < '1200') OR cs.isdevice = 1) "
            ."AND (cs.status IS NULL OR cs.status <> 'invisible' OR cs.status <> 'offline')"
            ."UNION "
            ."SELECT "
            ."u.id_user userid, "
            ."u.name, "
            ."u.sername, "
            ."u.id_user link, "
            ."cs.lastactivity lastactivity, "
            ."cs.status, "
            ."cs.message, "
            ."cs.isdevice "
            ."FROM users u "
            ."LEFT JOIN users u2 "
            ."ON u2.parent = u.id_user "
            ."LEFT JOIN cometchat_status cs "
            ."ON u.id_user = cs.userid "
            ."WHERE u2.id_user = '$id_user' "
            ."AND (('".time()."' - cs.lastactivity < '1200') OR cs.isdevice = 1) "
            ."AND (cs.status IS NULL OR cs.status <> 'invisible' OR cs.status <> 'offline')"
//            . "ORDER BY u.reg_date ASC"
            );
        return $this->code->db_decode($this->db->query($sql)->result());
    }

    public function getUserByEmail($email){
        if(empty($email) || !is_string($email))
            return 0;

        $email_coded = $this->code->code($email);
        $user        = $this->db
            ->where("email", $email_coded)
            ->get($this->tableName)
            ->row('id_user');

        if(empty($user))
            return null;

        return $user;
    }

    public function getUsersByVerifiedEmail($email){
        if(empty($email) || !is_string($email)){
            return FALSE;
        }
        $email_coded = $this->code->code($email);
        $users       = $this->db
            ->where(array("email" => $email_coded, 'account_verification IS NULL' => NULL))
            ->limit(5)
            ->get($this->tableName)
            ->result();

        if(empty($users)){
            return null;
        }

        return $users;
    }

    public function getAllUsers(){
        return $this->db->select("id_user")->get_where($this->tableName, array("state <>" => 3))->result();
    }

    public function getUserLimit($limit){
        return $this->db->limit($limit)->select("id_user, parent")->get_where($this->tableName, array("state <>" => 3))->result();
    }

    public function getUserLimitWithoutNewLavel($limit){
        return $this->db
                ->limit($limit)
                ->select("id_user")
                ->where("MONTH(partner_plan_update) <> MONTH(NOW())")
                ->get_where("$this->tableName", array("state <>" => 3))->result();
    }

    public function getUserLimitWithoutBalance($limit, $date){
        return $this->db
                ->limit($limit)
                ->select("u.id_user, u.parent")
                ->join("user_balances ub", "ub.id_user = u.id_user and DATE(ub.date) = DATE('$date')", "left", false)
                ->where("ub.id_user IS NULL")
                ->get_where("users_debits_for_bal_calc u", array("u.parent <>" => 0))->result();
    }

    public function getUserByIds($ids){
        return $this->db
                ->select("id_user, partner_plan")
                ->where_in('id_user', $ids)
                ->get_where("$this->tableName", array("state <>" => 3))->result();
    }

    public function getAllCountUsers(){
        return $this->db->select("COUNT(*) as count")->get_where($this->tableName, array("state <>" => 3))->row('count');
    }

    public function getMyUsers(){
        $this->load->model('users_filds_model', 'usersFilds');

        $id_user = $this->getCurrUserId();
        $users   = $this->code->db_decode($this->db
                ->select("u.id_user, /*k.card_user_id, k.card_proxy,*/ CASE WHEN uf.nickname IS NOT NULL AND uf.is_show <> 0 AND uf.nickname <> '' THEN uf.nickname ELSE u.name END name, CASE WHEN uf.nickname IS NOT NULL AND uf.is_show <> 0 AND uf.nickname <> '' THEN '' ELSE u.sername END sername", false)
                ->where("u.parent", $id_user)
                ->join("users_filds uf", "uf.id_user = u.id_user", "left")
                //->join("cards k", "k.user_id = u.id_user", "left")
                ->get("$this->tableName u")
                ->result()
        );
        $parent  = $this->getMyParent();
        if(!empty($parent)){
            $nickname = $this->usersFilds->getUserNickname($parent->id_user);
            if(FALSE !== $nickname){
                $parent->name    = $nickname;
                $parent->sername = '';
            }
        }
        if(empty($users) && empty($parent))
            return array();
        $parent_array = array(0 => $parent);
        $res          = array_merge($users, $parent_array);
        return $res;
    }

    public function getUsers($user = NULL){
        $this->load->model('users_filds_model', 'usersFilds');
        if(null == $user)
            $user = $this->getCurrUserId();
        $res  = $this->code->db_decode(
            $this->db->select("u.id_user, CASE WHEN uf.nickname IS NOT NULL AND uf.is_show <> 0 AND uf.nickname <> '' THEN uf.nickname ELSE u.name END name, CASE WHEN uf.nickname IS NOT NULL AND uf.is_show <> 0 AND uf.nickname <> '' THEN '' ELSE u.sername END sername", false)
                ->where("u.parent", $user)
                ->join("users_filds uf", "uf.id_user = u.id_user", "left")
                ->get("$this->tableName u")
                ->result()
        );
        return $res;
    }

    public function getMyUsersAndChield(){
        $users = $this->getUsers();
        foreach($users as &$user){
            $user->subusers = $this->getUsers($user->id_user);
        }
        return $users;
    }

    public function getUserData($id_user){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            return $data;
        } else
            return $this->code->db_decode($this->db->get_where("users", array('id_user' => $id_user))->row());
    }

    public function getUserDataFields($id_user, $fields){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            return $data;
        } else
            return $this->code->db_decode($this->db->select($fields)->get_where("users", array('id_user' => $id_user))->row());
    }

    public function getRawUserData($id_user){
        return $this->db->get_where("users", array('id_user' => $id_user))->row();
    }

    public function getMyParent(){
        return viewData()->parentUser;
    }

    public function getCurrUserId(){
        if(isset(get_instance()->user) && isset(get_instance()->user->id_user))
            return get_instance()->user->id_user;

        $this->load->model('accaunt_model', 'accaunt');
        if(!empty(get_instance()->accaunt->getUsrId4UsersModel()))
            return get_instance()->accaunt->getUsrId4UsersModel();

        //trigger_error("users_model->getCurrUserId() return NULL. Please check this.", E_USER_ERROR);
//            throw new Exception(_e("Не могу определить текущего пользователя"));
    }

    public function setPlaceIfEmpty(){
        if(empty(viewData()->user->place)){
            $p = $this->code->code($this->base_model->getCountry4Header());
            $this->db->update('users', ['place' => $p], ['id_user' => viewData()->user->id_user], 1);
        }
    }

    public function getCurrUserData(){
        if(isset(get_instance()->user) && 80 < count((array) (get_instance()->user))){
            return get_instance()->user;
        } else {
            $id_user                      = $this->getCurrUserId();
            $data                         = $this->code->db_decode($this->db->where("id_user", $id_user)->get("users")->row());
            foreach($data as $name => $value)
                get_instance()->user->{$name} = $value;

            return get_instance()->user;
        }

        return false;
    }

    public function hasParent(){
        return ((!isset(viewData()->parentUser->id_user) || 0 == viewData()->parentUser->id_user) ? false : true);
    }

    public function getParentUserId(){
        return get_instance()->content->data->parentUser->id_user;
    }

    public function getParent($id_user){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            return $data->parent;
        } else
            return $this->db->select("parent")->where("id_user", $id_user)->get($this->tableName)->row()->parent;
    }

    public function isVolanteer(){
        if(!isset(viewData()->user->volunteer))
            throw new Exception(_e("Не могу определить волонтер ли"));
        return ((Base_model::USER_VOLUNTEER_OFF == viewData()->user->volunteer) ? false : true);
    }

    public function getMyVolunteerId($id_user = null){
        if(null == $id_user)
            $id_user = $this->getCurrUserId();
        return $this->db->select("up.parent")
                ->join("users up", "up.id_user = u.parent", "inner")
                ->where("u.id_user", $id_user)
                ->get("$this->tableName u")
                ->row("parent");
    }

    public function acceptInvestArbitrage(){
        $user_id = $this->getCurrUserId();
        $this->db->update($this->tableName, array("invest_arbitrage" => self::INVAST_ARBITRAGE_ON), array("id_user" => $user_id));
    }

    public function setChildrenVolunteerId(array $children, $id_volunteer){
        $this->db->where_in("id_user", $children)->update($this->tableName, array("id_volunteer" => $id_volunteer));
    }

    public function setVolunteer($id_user, $value){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            $user = $data->id_user;
        } else if(0 != $value)
            $user = $this->db->select('id_user')->where('id_user', $id_user)->get('users')->row();

        if(!empty($user) || 0 == $value){
            $this->updateUserField($id_user, 'id_volunteer', $value);
        }
    }

    public function updateUserField($id_user, $field, $value){
        $this->db->where('id_user', $id_user)->update('users', array($field => $value));
    }

    public function updateUserFields($id_user, array $fields){
        $this->db->update('users', $fields, array('id_user' => $id_user), 1);
    }

    public function setParent($id_user, $value){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            $user = $data;
        } else
            $user = $this->db->select('id_user, name, sername, parent_change_counter')->where('id_user', $value)->get('users')->row();

        if(!empty($user)){
            $this->updateUserFields($id_user, array('parent' => $value, 'parent_change_counter' => $user->parent_change_counter + 1));
        }
    }

    public function setVolunteer2All($id_user, $parent_id){
        $id_volunteer = $this->getMyVolunteerId($id_user);
        $this->setVolunteer($id_user, $id_volunteer);
        $children     = $this->getChildrenId($id_user);
        if(!empty($children))
            $this->setChildrenVolunteerId($children, $parent_id);
    }

    public function delVolunteer4Children($id_user){
        $children = $this->getChildrenId($id_user);
        if(empty($children))
            return;
        $this->setChildrenVolunteerId($children, 0);
    }

    public function delParent($id_user){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->getCurrUserData();
            $user = $data;
        } else
            $user = $this->db->select('id_user, name, sername, parent_change_counter')->where('id_user', $id_user)->get('users')->row();

        if(!empty($user)){
            $this->updateUserFields($id_user, array('parent' => 0, 'parent_change_counter' => $user->parent_change_counter + 1, "id_volunteer" => 0));
            $this->delVolunteer4Children($id_user);
        }
    }

    public function isUserVolanteer($id_user){
        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data      = $this->users->getCurrUserData();
            $volunteer = $data->volunteer;
        } else
            $volunteer = $this->db->select("volunteer")->where("id_user", $id_user)->get($this->tableName)->row("volunteer");
        return ((Base_model::USER_VOLUNTEER_OFF == $volunteer) ? false : true);
    }

    public function getChildrenId($id_user = null){
        if(null == $id_user)
            $id_user = $this->getCurrUserId();

        $i     = $this->db->select("id_user")->where("parent", $id_user)->get($this->tableName)->result();
        $ids   = array();
        foreach($i as $row)
            $ids[] = $row->id_user;

        return $ids;
    }

    public function trigerVolunteer($id){
        $valnt = $this->db->get_where('users', array('id_user' => $id))->row()->volunteer;
        $valnt = (Base_model::USER_VOLUNTEER_ON == $valnt) ? Base_model::USER_VOLUNTEER_OFF : Base_model::USER_VOLUNTEER_ON;
        $this->db->where(array('id_user' => $id))->update('users', array("volunteer" => $valnt));

        //history
        $this->load->model('volunteers_model', 'volunteers');
        $this->volunteers->setVolunteerStatus($id, $valnt);

        return (Base_model::USER_VOLUNTEER_ON == $valnt) ? true : false;
    }

    public function getVolunteerStatus($id){
        return $this->db->get_where('users', array('id_user' => $id))->row()->volunteer;
    }

    public function setUserStatus($id, $state){
        $this->db->where(array('id_user' => $id))->update('users', array("state" => $state));
    }

    public function trigerUserStatus($id){
        $state = $this->db->get_where('users', array('id_user' => $id))->row()->state;
        $state++;
        if(Base_model::USER_STATE_OFF < $state)
            $state = Base_model::USER_STATE_ON;
        $this->db->where(array('id_user' => $id))->update('users', array("state" => $state));
        return $state;
    }

    /**
     *
     * @param type $id
     * @param type $cause
     * @param type $status
     * @param type $admin_id // если крон то id = 9999
     */
    public function writeCause($id, $cause, $status, $admin_id = null){
        $this->db->update('users', array("status_cause" => $cause), array('id_user' => $id), 1);
        $this->load->model("users_status_model", "users_status");
        $this->users_status->set_status($id, $status, $cause, $admin_id);
    }

    public function readCause($id){
        if($id == null)
            return ''; $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($id_user == $cur_user){
            $data = $this->users->getCurrUserData();
            return $data->status_cause;
        } else
            return $this->db->where('id_user', $id)->get('users')->row('status_cause');
    }

    public function socails(){
        $data = $this->all(1);
        $out  = array();
        foreach($data as $item){
            $this->setUserId($item->id_user);
            $list['data']    = $item;
            $list['social']  = $this->getSocial($item->id_user);
            $list['credits'] = $this->getCredits();
            $list['invests'] = $this->getInvests();
            $out[]           = $list;
        }
        return $out;
    }

    public function new_users($type = 1){
        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);
        return $this->code->db_decode(
                $this->db
                    ->select('id_user, name, sername, email, patronymic, phone, reg_date as date, state')
                    ->where(array('state' => 1))
                    ->get('users')
                    ->result()
        );
    }

    public function all($type = 1){
        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);
        return $this->code->db_decode(
                $this->db
                    ->select('id_user, name, sername, email, patronymic, skype, parent, bot, reg_date, ip_address,phone, online_date as date, state')
                    ->order_by('online_date', "DESC")
                    ->get("users")
                    ->result()
        );
    }

    public function is_need_change_doc($id_user){
        $data = $this->document_user();
        $res  = false;

        foreach($data as $item)
            $res = ($id_user == $item->id_user) ? TRUE : $res;
        return $res;
    }

    public function getDoc(){
        $data = $this->document_user();

        foreach($data as $item)
            $in[] = $item->id_user;

        $search   = /* $_POST["search"] */ false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if(isset($_SESSION["getDoc_count"]) && isset($_SESSION["getDoc_count_time"]) && $_SESSION["getDoc_count_time"] > (time() - 24 * 60 * 60) && !$search && !$filter)
            $r["count"] = $_SESSION["getDoc_count"];
        else {
            if($search)
                $this->_createSearch($search);
            if($filter)
                $this->_createFilter($filter);
            $r["count"]                    = $this->db
                ->where_in('id_user', $in)
                ->select("COUNT(*) as count")
                ->get("users")
                ->row("count");
            $_SESSION["getDoc_count"]      = $r["count"];
            if($search || $filter)
                $_SESSION["getDoc_count_time"] = 0;
            else
                $_SESSION["getDoc_count_time"] = time();
        }

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearch($search);

        if(!empty($in))
            $r["rows"] = $this->code->db_decode(
                $this->db
                    ->where_in('id_user', $in)
                    ->select(
                        'id_user, '
                        .'CONCAT_WS(" ", name, sername) as fio, '
                        .'email, '
                        .'parent, '
                        .'reg_date, '
                        .'ip_reg, '
                        .'phone, '
                        .'skype, '
                        .'reg_date as date, '
                        .'state', false)
                    ->limit($per_page, $offset)
                    ->get("users")
                    ->result()
            );
        return json_encode($r);
    }

    public function getAll($type = 0){
        $search   = /* $_POST["search"] */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        if(isset($_SESSION["users_count"]) && isset($_SESSION["users_count_time"]) && $_SESSION["users_count_time"] > (time() - 24 * 60 * 60) && !$search && !$filter)
            $r["count"] = $_SESSION["users_count"];
        else {
            if($search)
                $this->_createSearch($search);
            if($filter)
                $this->_createFilter($filter);
            $r["count"]                   = $this->db
                ->where("(u.bot = ".Base_model::USER_BOT_OFF." OR u.bot = ".Base_model::USER_BOT_PSEVDO.")")
                ->select("COUNT(*) as count")
                ->join('phones p', 'p.user_id=u.id_user', 'left')
                ->get("users u")
                ->row("count");
            $_SESSION["users_count"]      = $r["count"];
            if($search || $filter)
                $_SESSION["users_count_time"] = 0;
            else
                $_SESSION["users_count_time"] = time();
        }

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearch($search);
        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        $r["rows"] = $this->code->db_decode(
            $this->db
                ->where("(bot = ".Base_model::USER_BOT_OFF." OR bot = ".Base_model::USER_BOT_PSEVDO.")")
                ->select(
                    'id_user, '
                    .'CONCAT_WS(" ", u.sername, u.name, u.patronymic) as fio, '
                    .'u.email, '
                    .'u.parent, '
                    .'u.reg_date, '
                    .'u.ip_reg, '
                    .'IFNULL(p.phone_number,u.phone) as phone, '
                    .'u.skype, '
                    .'u.online_date as date, '
                    .'u.state', false)
                ->join('phones p', 'p.user_id=u.id_user', 'left')
                ->limit($per_page, $offset)
                ->get("users u")
                ->result()
        );
        return json_encode($r);
    }

    public function get_currency_exchange_block_users_all($type = 0){
        $search   = /* $_POST["search"] */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        ###############################################
        $res_currency_exchange_block_user = $this->db
            ->where_in('status', array(1, 2, 3, 4))
            ->get('currency_exchange_block_user')
            ->result();

        if(!empty($res_currency_exchange_block_user)){
            $res_currency_exchange_block_user = array_set_value_in_key($res_currency_exchange_block_user, 'user_id');
        }

        if(!empty($res_currency_exchange_block_user)){
            $this->db->where_in('id_user', array_keys($res_currency_exchange_block_user));
        } else {
            $this->db->where('id_user', false);
        }

        #######################################

        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        if(isset($_SESSION["currency_exchange_block_users_count"]) && isset($_SESSION["currency_exchange_block_users_count_time"]) && $_SESSION["currency_exchange_block_users_count_time"] > (time() - 24 * 60 * 60) && !$search && !$filter)
            $r["count"] = $_SESSION["currency_exchange_block_users_count"];
        else {
            if($search)
                $this->_createSearch($search);
            if($filter)
                $this->_createFilter($filter);
            $r["count"]                                           = $this->db
                ->where("(bot = ".Base_model::USER_BOT_OFF." OR bot = ".Base_model::USER_BOT_PSEVDO.")")
                ->select("COUNT(*) as count")
                ->get("users")
                ->row("count");
            $_SESSION["currency_exchange_block_users_count"]      = $r["count"];
            if($search || $filter)
                $_SESSION["currency_exchange_block_users_count_time"] = 0;
            else
                $_SESSION["currency_exchange_block_users_count_time"] = time();
        }

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearch($search);
        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        ##########################
        if(!empty($res_currency_exchange_block_user)){
            $this->db->where_in('id_user', array_keys($res_currency_exchange_block_user));
        } else {
            $this->db->where('id_user', false);
        }
        ########################################################

        $r["rows"] = $this->code->db_decode(
            $this->db
                ->where("(bot = ".Base_model::USER_BOT_OFF." OR bot = ".Base_model::USER_BOT_PSEVDO.")")
                ->select(
                    'id_user, '
                    .'CONCAT_WS(" ", sername, name, patronymic) as fio, '
                    .'email, '
                    .'parent, '
                    .'reg_date, '
                    .'ip_reg, '
                    .'phone, '
                    .'skype, '
                    .'online_date as date, '
                    .'state', false)
                ->limit($per_page, $offset)
                ->get("users")
                ->result()
        );
//                vred($this->db->last_query());
//                pred($r);
        return json_encode($r);
    }

    public function getVipAll($type = 0){
        $search   = /* $_POST["search"] */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        if(isset($_SESSION["users_count"]) && isset($_SESSION["users_count_time"]) && $_SESSION["users_count_time"] > (time() - 24 * 60 * 60) && !$search && !$filter)
            $r["count"] = $_SESSION["users_count"];
        else {
            if($search)
                $this->_createSearch($search);
            if($filter)
                $this->_createFilter($filter);
            $r["count"]                   = $this->db
                ->where("(bot = ".Base_model::USER_BOT_ON.")")
                ->select("COUNT(*) as count")
                ->get("users")
                ->row("count");
            $_SESSION["users_count"]      = $r["count"];
            if($search || $filter)
                $_SESSION["users_count_time"] = 0;
            else
                $_SESSION["users_count_time"] = time();
        }

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearch($search);
        if($type == 1)
            $this->db->where('client', 1);
        if($type == 2)
            $this->db->where('partner', 1);

        $r["rows"] = $this->code->db_decode(
            $this->db
                ->where("(bot = ".Base_model::USER_BOT_ON.")")
                ->select(
                    'id_user, '
                    .'CONCAT_WS(" ", sername, name, patronymic) as fio, '
                    .'email, '
                    .'parent, '
                    .'reg_date, '
                    .'ip_reg, '
                    .'phone, '
                    .'skype, '
                    .'online_date as date, '
                    .'state', false)
                ->limit($per_page, $offset)
                ->get("users")
                ->result()
        ); //echo $this->db->last_query();die;
        return json_encode($r);
    }

    private function _createOrder($order, $t = ''){
        foreach($order as $key => $value)
            $this->db->order_by($t.$key, $value);
    }

    private function _createFilter($order, $t = ''){
        foreach($order as $key => $value)
            if("fio" == $key){
                $value = $this->code->encode($value);
                $this->db->where("({$t}name = '$value' OR {$t}sername = '$value')");
            } else if("email" == $key){
                $value = $this->code->encode($value);
                if(!empty($value))
                    $this->db->where("$t$key", $value);
            } else if("phone" == $key){
                $value = $this->code->encode($value);
                if(!empty($value))
                    $this->db->where("p.phone_number = '$value' OR u.phone = '$value'");
            } else
                $this->db->where("$t$key LIKE", "%$value%");
    }

    private function _createSearch($search){
        $search = explode(" ", $search);
        foreach($search as $word){
            $wordEncript = $this->code->code($word);
            $word        = preg_match("/[а-я]/i", $word) ? "text" : $word;

            $this->db->where("("
                ."id_user LIKE '%$word%' OR "
                ."name = '$wordEncript' OR "
                ."sername = '$wordEncript' OR "
                ."patronymic = '$wordEncript' OR "
                ."email = '$wordEncript' OR "
                ."parent LIKE '%$word%' OR "
                ."reg_date LIKE '%$word%' OR "
                ."ip_address = '$wordEncript' OR "
                ."phone = '$wordEncript' OR "
                ."online_date LIKE '%$word%' OR "
                ."state LIKE '%$word%'"
                .")");
        }
    }

    private function _createSearchCredits($search){
//        $search = explode(" ", $search);
//        foreach ($search as $word) {
//            $wordEncript = $this->code->code($word);
//            $word = preg_match("/[а-я]/i", $word) ? "text" : $word;
//
//            $this->db->where("("
//                    . "id_user LIKE '%$word%' OR "
//                    . "name = '$wordEncript' OR "
//                    . "sername = '$wordEncript' OR "
//                    . "patronymic = '$wordEncript' OR "
//                    . "email = '$wordEncript' OR "
//                    . "parent LIKE '%$word%' OR "
//                    . "reg_date LIKE '%$word%' OR "
//                    . "ip_address = '$wordEncript' OR "
//                    . "phone = '$wordEncript' OR "
//                    . "online_date LIKE '%$word%' OR "
//                    . "state LIKE '%$word%'"
//                    . ")");
//        }
    }

    private function _createSearchTransactions($search){
//        $search = explode(" ", $search);
//        foreach ($search as $word) {
//            $wordEncript = $this->code->code($word);
//            $word = preg_match("/[а-я]/i", $word) ? "text" : $word;
//
//            $this->db->where("("
//                    . "id_user LIKE '%$word%' OR "
//                    . "name = '$wordEncript' OR "
//                    . "sername = '$wordEncript' OR "
//                    . "patronymic = '$wordEncript' OR "
//                    . "email = '$wordEncript' OR "
//                    . "parent LIKE '%$word%' OR "
//                    . "reg_date LIKE '%$word%' OR "
//                    . "ip_address = '$wordEncript' OR "
//                    . "phone = '$wordEncript' OR "
//                    . "online_date LIKE '%$word%' OR "
//                    . "state LIKE '%$word%'"
//                    . ")");
//        }
    }

    public function document_user(){
        return $this->db
                ->select('id_user')
                //->where('(state in (1,3)) or ( img2 !="" and  img2 is not null)  ', null, false)
                ->where('(state = 1 or state = 3)', null, false)
                //	->group_by('id_user')
                ->get('documents')
                ->result();
    }

    public function list_doc_change(){
        $data = $this->document_user();

        foreach($data as $item)
            $in[] = $item->id_user;

        if(!empty($in)){
            return $this->code->db_decode(
                    $this->db->select('id_user, name, sername,  email, patronymic, phone, reg_date as date, state')
                        ->where_in('id_user', $in)
                        ->get('users')
                        ->result());
        } else {
            return array();
        }
    }

    //запрос на проверку одобренного документа
    public function is_ducument_verified($user_id, $doc_num = 1){
        if(!$user_id)
            return false;

        $this->setUserId($user_id);
        $user_documents = $this->getDocuments($user_id);

        if(!empty($user_documents)){
            foreach($user_documents as $item){
                if($item->state == 2 && $item->num == $doc_num){
                    return true;
//                    break;
                }
            }
        }
        return false;
    }

    public function user_active_close($id, $state){
        $this->db->where('id_user', $id)->update("users", array("state" => $state));
    }

    public function isUserActive($id_user){
        $cur_user = $this->getCurrUserId();
        if($id_user == $cur_user){
            $data  = $this->getCurrUserData();
            $state = $data->state;
        } else
            $state = $this->db->get_where($this->tableName, array("id_user" => $id_user))->row("state");
        return (Base_model::USER_STATE_OFF == $state) ? false : true;
    }

    public function isNewUser($id_user){
        if(count($_SESSION['_is_new']) && isset($_SESSION['_is_new'][$id_user])){
            $res = $_SESSION['_is_new'][$id_user];
        } else {
            $res = (($this->db->where(array("id_user" => $id_user, "reg_date >" => '2014-08-14'))->count_all_results($this->tableName)) ? true : false);

            $_SESSION['_is_new'][$id_user] = $res;
        }
        return $res;
    }

    public function getIp4DiffAccaunts($ips){
        $ips                 = array_keys($ips);
        $q                   = "SELECT COUNT(id_user) `count`, ip_reg
              FROM users
              WHERE ip_reg IN('".implode("', '", $ips)."')
              GROUP BY ip_reg
              HAVING `count` > 1";
        $res                 = $this->db->query($q)->result();
        $resp                = array();
        foreach($res as $row)
            $resp[$row->user_ip] = $row->count;
        return $resp;
    }

    public function isUsersActive($id_users){
        $r                  = $this->db->select("id_user, state")->where_in("id_user", $id_users)->get($this->tableName)->result();
        $res                = array();
        foreach($r as $row)
            $res[$row->id_user] = (Base_model::USER_STATE_OFF == $row->state) ? false : true;

        return $res;
    }

    public function checkIP(){
        $ipCount = $this->countIp($this->getIpUser());
        if(5 <= $ipCount){
            if($this->input->is_ajax_request())
                echo json_encode(array("e" => "blocked"));
            else
                echo _e("Извините, но мы не можем создать для вас еще одного пользователя, обратитесь в тех поддержку ").$GLOBALS["WHITELABEL_NAME"].".";
            die;
        }
    }

    public function countIp($ip){
        return $this->db->where(array("ip_reg" => $ip, "reg_date >=" => date("Y-m-d 00:00:00")))->count_all_results($this->tableName);
    }

    public function getIpUser(){
        $ip = getServer("HTTP_X_REAL_IP");
//        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//            $ip = $_SERVER['HTTP_CLIENT_IP'];
//        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        } else {
//            $ip = $_SERVER['REMOTE_ADDR'];
//        }
//        $ip = explode(',', $ip);
//        return $ip[0];
        if($this->input->valid_ip($ip))
            return $ip;
        else
            return $this->input->ip_address();
    }

    public function setUserId($id){
        $this->id_user = $id;
    }

    public function getDocuments($id){
        return $this->db->order_by('num')->get_where('documents', array('id_user' => $id))->result();
    }

    public function getSocial($id){
        return $this->code->db_decode($this->db->select('name, id_social, foto, url')->where('id_user', $id)->get('social_network')->result());
    }

    public function getDebit($type){
        return $this->db->order_by('date')->where('id_user', $this->id_user)->where('type', $type)->get('credits')->result();
    }

    public function getCredits(){
        return $this->getDebit(1);
    }

    public function getInvests(){
        return $this->getDebit(2);
    }

    public function get_credits(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchCredits($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->select("COUNT(*) as count")
            ->get('credits')
            ->row("count");

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearchCredits($search);

        $r["rows"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->select("*,"
                ." CONCAT_WS(' ', time, '"._e('дней')."') time_unit,"
                ." CONCAT_WS('', percent, '%') percent_unit,"
                ." CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
                ." (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr", false)
            ->limit($per_page, $offset)
            ->get("credits")
            ->result();
        return json_encode($r);
    }

    public function get_invests(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchCredits($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_INVEST)
            ->select("COUNT(*) as count")
            ->get('credits')
            ->row("count");

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearchCredits($search);

        $r["rows"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_INVEST)
            ->select("*,"
                ." CONCAT_WS(' ', time, '"._e('дней')."') time_unit,"
                ." CONCAT_WS('', percent, '%') percent_unit",
//                            . " CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
//                            . " (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr",
                false)
            ->limit($per_page, $offset)
            ->get("credits")
            ->result();
        return json_encode($r);
    }

    public function get_transactions(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchTransactions($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->select("COUNT(*) as count")
            ->get('transactions')
            ->row("count");

        if($order)
            $this->_createOrder($order, "t.");
        if($filter)
            $this->_createFilter($filter, "t.");
        if($search)
            $this->_createSearchTransactions($search);

        $r["rows"] = $this->db
            ->where('t.id_user', $this->id_user)
            ->select("t.*,"
                ." , (CASE WHEN c.time IS NULL THEN '-' WHEN c.time IS NOT NULL THEN c.time END) time",
//                            . " CONCAT_WS('', percent, '%') percent_unit",
//                            . " CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
//                            . " (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr",
                false)
            ->limit($per_page, $offset)
            ->join('credits c', 't.value = c.id', 'left')
            ->get("transactions t")
            ->result();
        return json_encode($r);
    }

    public function get_partners(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearch($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('parent', $this->id_user)
            ->select("COUNT(*) as count")
            ->get('users')
            ->row("count");

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearch($search);

        $r["rows"] = $this->code->db_decode($this->db
                ->where('parent', $this->id_user)
                ->select(
                    'id_user, '
                    .'CONCAT_WS(" ", sername, name, patronymic) as fio, '
                    .'email, '
                    .'parent, '
                    .'reg_date, '
                    .'ip_reg, '
                    .'phone, '
                    .'skype, '
                    .'online_date as date, '
                    .'state', false)
                ->limit($per_page, $offset)
                ->get("users")
                ->result()
        );
        return json_encode($r);
    }

    public function get_credits_archive(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchCredits($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->select("COUNT(*) as count")
            ->get('archive_credits')
            ->row("count");

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearchCredits($search);

        $r["rows"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_CREDIT)
            ->select("*,"
                ." CONCAT_WS(' ', time, '"._e('дней')."') time_unit,"
                ." CONCAT_WS('', percent, '%') percent_unit,"
                ." CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
                ." (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr", false)
            ->limit($per_page, $offset)
            ->get("archive_credits")
            ->result();
        return json_encode($r);
    }

    public function get_invests_archive(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchCredits($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_INVEST)
            ->select("COUNT(*) as count")
            ->get('archive_credits')
            ->row("count");

        if($order)
            $this->_createOrder($order);
        if($filter)
            $this->_createFilter($filter);
        if($search)
            $this->_createSearchCredits($search);

        $r["rows"] = $this->db
            ->where('id_user', $this->id_user)
            ->where('type', Base_model::CREDIT_TYPE_INVEST)
            ->select("*,"
                ." CONCAT_WS(' ', time, '"._e('дней')."') time_unit,"
                ." CONCAT_WS('', percent, '%') percent_unit",
//                            . " CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
//                            . " (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr",
                false)
            ->limit($per_page, $offset)
            ->get("archive_credits")
            ->result();
        return json_encode($r);
    }

    public function get_transactions_archive(){
        $search   = /* $_POST["search"]; */
            false;
        $filter   = $_POST["filter"];
        $per_page = (int) $_POST["per_page"];
        $offset   = (int) $_POST["offset"];
        $order    = $_POST["order"];
        $r        = array("count" => "", "rows" => array(), "sql" => "");

        if($search)
            $this->_createSearchTransactions($search);
        if($filter)
            $this->_createFilter($filter);
        $r["count"] = $this->db
            ->where('id_user', $this->id_user)
            ->select("COUNT(*) as count")
            ->get('archive_transactions')
            ->row("count");

        if($order)
            $this->_createOrder($order, "t.");
        if($filter)
            $this->_createFilter($filter, "t.");
        if($search)
            $this->_createSearchTransactions($search);

        $r["rows"] = $this->db
            ->where('t.id_user', $this->id_user)
            ->select("t.*,"
                ." , (CASE WHEN c.time IS NULL THEN '-' WHEN c.time IS NOT NULL THEN c.time END) time",
//                            . " CONCAT_WS('', percent, '%') percent_unit",
//                            . " CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END contr,"
//                            . " (out_summ - CASE WHEN garant = 1 THEN (income * (CASE WHEN time <= 10 THEN 0.5 WHEN time <= 19 THEN 0.45 ELSE 0.4 END)) ELSE (income * 0.1) END) out_summ_contr",
                false)
            ->limit($per_page, $offset)
            ->join('archive_credits c', 't.value = c.id', 'left')
            ->get("archive_transactions t")
            ->result();
        //echo $this->db->last_query();die;
        return json_encode($r);
    }

    public function getUserFromUsers($user_id, $field){
        if(empty($user_id))
            return FALSE;
        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->users->getCurrUserData();
            $user = $data;
        } else
            $user = $this->db
                ->get_where("users", array('id_user' => $user_id))
                ->row();
        if(empty($user) || !isset($user->{$field}))
            return FALSE;

        return $user->{$field};
    }

    public function getUser($id){
        $id_change = (array) $this->db->select('id_user')->get_where('chanche_users', array('id_user' => $id))->row('id_user');

        $data['id_change'] = $id_change;
        $this->load->library('code');

        if(empty($id_change)){
            $this->load->model("users_model", "users");
            $cur_user = $this->users->getCurrUserId();
            if($id == $cur_user){
                $dataCurUser  = $this->users->getCurrUserData();
                $data['user'] = $dataCurUser;
            } else
                $data['user']  = $this->code->db_decode($this->db->get_where("users", array('id_user' => $this->id_user))->row());
            $data['adr_f'] = $this->code->db_decode($this->db->get_where("address", array('id_user' => $this->id_user, 'state' => '2'))->row());
            $data['adr_r'] = $this->code->db_decode($this->db->get_where("address", array('id_user' => $this->id_user, 'state' => '1'))->row());
            $data['adr_h'] = $this->code->db_decode($this->db->get_where("address", array('id_user' => $this->id_user, 'state' => '3'))->row());
        } else {
            $data['user']        = $this->code->db_decode($this->db->get_where("chanche_users", array('id_user' => $id))->row());
            $data['user']->face  = $this->get_user_field($this->id_user, 'face');
            $data['user']->email = $this->get_user_field($this->id_user, 'email');
            $data['user']->place = $this->get_user_field($this->id_user, 'place');

            $data['adr_f'] = (object) array(
                    'index'  => $data->user->findex,
                    'town'   => $data->user->ftown,
                    'street' => $data->user->fstreet,
                    'house'  => $data->user->fhouse,
                    'kc'     => $data->user->fkc,
                    'flat'   => $data->user->fflat
            );

            $data['adr_r'] = (object) array(
                    'index'  => $data->user->rindex,
                    'town'   => $data->user->rtown,
                    'street' => $data->user->rstreet,
                    'house'  => $data->user->rhouse,
                    'kc'     => $data->user->rkc,
//              'flat'   => $data->user->fflat
            );
        }
        return $data;
    }

    public function getUserProfile(){
        return array('user_p' => $this->code->db_decode($this->db->get_where("chanche_users", array('id_user' => $this->id_user))->row()));
    }

    public function getUserFullProfile($user_id = null){
        if($user_id == null)
            $user_id = $this->getCurrUserId();
        if($user_id == null){
            if(!isset($this->accaunt))
                $this->load->model('accaunt_model', 'accaunt');
            $user_id = $this->accaunt->get_user_id();
        }
        if($user_id == null)
            return;

        $cur_user = $this->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->getCurrUserData();
            return $data;
        } else
            return $this->code->db_decode($this->db->get_where("users", array('id_user' => $user_id))->row());
    }

    public function all_profiles(){
        return $this->code->db_decode($this->db->select('id_user, name, sername, patronymic, phone, `date`')->get('chanche_users')->result());
    }

    public function delete_profile($id){
        $this->db->where('id_user', $id)->delete("chanche_users");
    }

    public function isUserExists($user_id){
        $cur_user = $this->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->getCurrUserData();
            $res  = $data;
        } else
            $res = $this->db->where('id_user', $user_id)->get("users")->row();

        return (count($res) == 0) ? FALSE : TRUE;
    }

    //парсим transactions.note на предмет номера заявки
    public function getNumber($note){
        $all_matches = array();
        if(preg_match_all("/.*№\s*(\d*)/", $note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0])
        )
            return $all_matches[1][0];
    }

    //парсим transactions.note на предмет номера партнера
    public function getPartnerIdFromNote($note){
        $all_matches = array();
        if(preg_match_all("/.*партнера №\s*(\d*)/", $note, $all_matches) && isset($all_matches[1]) && isset($all_matches[1][0])
        )
            return $all_matches[1][0];
    }

    /**
     * Get time after registration in days
     *
     * @param type $user_id
     * @return boolean
     */
    public function timeAfterRegistration($user_id){
        if(empty($user_id))
            return -1;
        $this->load->model("users_model", "users");
        $cur_user = $this->users->getCurrUserId();
        if($user_id == $cur_user){
            $data = $this->users->getCurrUserData();
            $date = $data->reg_date;
        } else
            $date = $this->db
                ->select('reg_date')
                ->where(array('id_user' => $user_id))
                ->limit(1)
                ->get('users')
                ->row('reg_date');

        if(empty($date))
            return -1;
        $time = floor((time() - strtotime($date)) / (60 * 60 * 24));
        return $time;
    }

    /*     * Новичок или нет
     *
     * @param type $user_id
     * @return boolean
     */

    public function isNewbie($user_id = null){
        $this->load->model('transactions_model', 'transactions');
        $this->load->model('credits_model', 'credits');

        if(empty($user_id))
            $user_id = $this->accaunt->get_user_id();

        if(empty($user_id))
            return FALSE;

        $days_after_reg = $this->timeAfterRegistration($user_id);
        $is_newbie      = ($days_after_reg > 10) &&
            ($this->transactions->getSumDeposits($user_id) <= 8) &&
            ($this->transactions->getSumInvest($user_id) >= 1) &&
            !$this->transactions->hasTransfers($user_id) &&
            !$this->credits->hasActiveInvests($user_id) &&
            !$this->credits->hasBorrows($user_id) &&
            !$this->credits->hasActiveArbitrageBorrows($user_id) &&
            ($this->base_model->getRealMoney($user_id) < 10);
        return $is_newbie;
    }

    /**
     * Чистая прибыль от вкладов.
     * Складываются все income и вычитаются все отчисления
     * @param type $user_id
     * @param type bonus
     * @return string
     */
    public function getUserPureIncome($user_id, $bonus = null){
        ini_set('memory_limit', '2G');
        if(empty($user_id))
            return '?';

        $extra_where = '';
        if(!empty($bonus))
            $extra_where = ' AND bonus='.$bonus;
        $userCredits = $this->db->query("SELECT *
                                        FROM `credits` t
                                        WHERE id_user = $user_id AND state = ".Base_model::CREDIT_STATUS_PAID." $extra_where
                                        GROUP BY t.id
                                        UNION
                                        SELECT *
                                        FROM `archive_credits` t
                                        WHERE id_user = $user_id AND state = ".Base_model::CREDIT_STATUS_PAID." $extra_where
                                        GROUP BY t.id
                                        ORDER BY id ASC"
            )->result();

        if(empty($userCredits))
            return 'No credits';

        $sorted_credits         = array();
        foreach($userCredits as $c)
            $sorted_credits[$c->id] = $c;

        $this->load->model('transactions_model', 'transactions');
        //types = 24+51+20+23 - 21-25-52-26-14-22; 14,20,21,22,23,24,25,26,51,52
        $typesAdd = array(
            Transactions_model::TYPE_INCOME_REPAY, //24
            Transactions_model::TYPE_EXCHANGE_SELL, //51
            Transactions_model::TYPE_EXCHANGE_CREDS_SELL, //53 new
            Transactions_model::TRANSACTION_TYPE_CERTIFICAT_PAID, //20
            Transactions_model::TYPE_INCOME_LOAN, //23
            Transactions_model::TRANSACTION_TYPE_INVEST_ARBITRAGE_OUTCAME, //16 new
        );
        $typesSub = array(
            Transactions_model::TYPE_EXPENSE_INVEST, //21
            Transactions_model::TYPE_EXPENSE_FEE_REPAY, //25
            Transactions_model::TYPE_EXCHANGE_BUY, //52
            Transactions_model::TYPE_EXCHANGE_CREDS_BUY, //54 new
            Transactions_model::TYPE_BONUS_CREDIT_REMOVED, //26
            Transactions_model::TYPE_EXPENSE_INVEST_REPAY, //22
            Transactions_model::TRANSACTION_TYPE_APPLICATIONS_ARBITRATION_LOAN_BACK_PERCENT, //14
        );



        if($bonus !== NULL)
            $this->db->where('bonus', $bonus);
        else
            $this->db->where('bonus', Base_model::TRANSACTION_BONUS_OFF);
        $userTransactions = $this->db->where_in('status', [1, 3])
            ->where('id_user', $user_id)
            ->where_in('type', array_merge($typesAdd, $typesSub))
            ->get('transactions')
            ->result();

        if(empty($userTransactions))
            return 'No transactions';

        $summa    = 0;
        $type     = array();
        //14,20,21,23,24,25,26,51,52
        $type[14] = $type[20] = $type[21] = $type[22] = $type[23] = $type[24] = $type[25] = $type[26] = $type[51] = $type[52] = 0;
        $type[53] = $type[16] = $type[53] = $type[54] = 0;
        $type_all = $type;
        foreach($userTransactions as $i){
            if($i->value != 0){
                //пропускаем все, кроме выплаченных заявок
                if(isset($sorted_credits[$i->value]) && $sorted_credits[$i->value]->state != 5 || !isset($sorted_credits[$i->value])
                ){
                    continue;
                }
                if(!isset($type_all[(int) $i->type]))
                    $type_all[(int) $i->type] = 0;
                $type_all[(int) $i->type] += $i->summa;
                if(in_array($i->type, $typesAdd))
                    $summa += $i->summa;
                if(in_array($i->type, $typesSub))
                    $summa -= $i->summa;
            } else if(Transactions_model::TYPE_BONUS_CREDIT_REMOVED == $i->type)
                $summa -= $i->summa;
            if(!isset($type[(int) $i->type]))
                $type[(int) $i->type] = 0;
            $type[(int) $i->type] += $i->summa;
        }
        $formula = "$$type[24][24] + $$type[51][51] + $$type[53][53] + $$type[16][16] + $$type[20][20] + $$type[23][23] - $$type[21][21] - $$type[25][25] - $$type[52][52] - $$type[26][26] - $$type[14][14] - $$type[22][22] - $$type[54][54]";
        return array($summa, $type_all, $formula);
    }

    public function isUsedCyrilicLanguage($params, $param_names = null){
        if(empty($params)){
            return FALSE;
        }

        if(!is_array($params)){
            $params = (array) $params;
        }

        $pattern = '/[\!\"\#\$\%\&\'\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\\\\[\\]\^\_\`\sА-Яа-яЁё0-9]+/';
//$contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $str);

        foreach($params as $key => $param){
            if(!empty($param_names) && !isset($param_names[$key])){
                continue;
            }
            if(!empty($param) && preg_match($pattern, $param) !== 1){
                return FALSE;
            }
        }
        return TRUE;
    }

    public function isUsedOnlyLatinLanguage($params, $param_names = null){
        if(empty($params)){
            return FALSE;
        }

        if(!is_array($params)){
            $params = (array) $params;
        }

        $pattern = '/[\!\"\#\$\%\&\'\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\\\\[\\]\^\_\`\sA-Za-z0-9]+/';

        foreach($params as $key => $param){
            if(!empty($param_names) && !isset($param_names[$key])){
                continue;
            }
            if(!empty($param) && preg_match($pattern, $param) !== 1){
                return FALSE;
            }
        }
        return TRUE;
    }

    public function isUsedOnlyLatinLanguageNames($user_id = null){


        if(empty($user_id)){
            $this->load->model('accaunt_model', 'account');
            $user_id = $this->accaunt->get_user_id();
        }

        if(empty($user_id))
            return 0;

        $profile = $this->getUserFullProfile($user_id);

        if(empty($profile))
            return 1;

        $pattern = '/[\!\"\#\$\%\&\'\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\\\\[\\]\^\_\`\sA-Za-z0-9]+/';


        $name       = $profile->name;
        $sername    = $profile->sername;
        $patronymic = $profile->patronymic;

        if(!empty($name) && preg_match($pattern, $name) !== 1)
            return FALSE;
        if(!empty($sername) && preg_match($pattern, $sername) !== 1)
            return FALSE;
        if(!empty($patronymic) && preg_match($pattern, $patronymic) !== 1)
            return FALSE;

        return TRUE;
    }
    
    public function translitFieldsIfPartnetSite(&$params){
        
         
        if ( $GLOBALS["WHITELABEL_ID"] != 1 ){
            $this->load->helper('translit_helper');
            $pattern = '/[\!\"\#\$\%\&\'\(\)\*\+\,\-\.\/\:\;\<\=\>\?\@\\\\[\\]\^\_\`\sA-Za-z0-9]+/';

            foreach($params as $key => &$param){
                if(!empty($param_names) && !isset($param_names[$key])){
                    continue;
                }
                if(!empty($param) && preg_match($pattern, $param) !== 1){
                    $param = translitIt($param);
                }
            }
        }
        
        
    }

    // TODO похожу что $contragent нужен
    public function check_user_for_payout($own, $summa, $contragent = false, $bonus = null, $enable_summa = TRUE){
        $data = new stdClass();

        $data->isCanPay  = TRUE;
        $data->error_num = 0;

        if(empty($bonus))
            $bonus = 5;

//        $own = $this->id_user;
//        $summa = $data->vars->summary;
        $rating = viewData()->accaunt_header;
        if($own != $this->accaunt->get_user_id())
            $rating = $this->accaunt->recalculateUserRating($own);


        $this->load->model('documents_model', 'documents');
        $userDocumentStatus        = $this->documents->getUserDocumentStatus($own);
        $send_transaction          = new stdClass;
        $send_transaction->summa   = $summa;
        $send_transaction->own     = $own;
        $send_transaction->id_user = $contragent;
//            $send_transaction->note    = $data->vars->description;
        if($rating){
            if(!$this->accaunt->isUserAccountVerified($own)){
                $data->isCanPay = false;
                $data->error    = _e("Аккаунт не верифицирован");
            } else if($enable_summa && $summa > $rating['payout_limit_by_bonus'][$bonus]){
//                vred($bonus);
                $data->isCanPay = false;
//                $data->error = _e("Недостаточно средств")." bonus $bonus";
                $data->error    = _e("Недостаточно средств")." "._e('wt_currency_'.$bonus);
            } else if(!$enable_summa && $rating['payout_limit_by_bonus'][$bonus] >= 0){
                $data->isCanPay = false;
//                $data->error = _e("Недостаточно средств")." bonus $bonus";
                $data->error    = _e("Недостаточно средств")." "._e('wt_currency_'.$bonus);
            } else if($rating['overdue_standart_count'] > 0){
                $data->isCanPay = false;
                $data->error    = 20; //_e("Есть просроченные кредиты стандарт");
            } else if($rating['overdue_garant_count'] > 0){
                $data->isCanPay = false;
                $data->error    = 21; //_e("Есть просроченные кредиты гарант");
//                else if ($this->accaunt->isUserUSorCA($id_user) || $this->accaunt->isUserUSorCA())
//                    $data->isCanPay = false;
            } else if(!in_array($own, getUnlimitedUsers()) && (0 <= $summa && Documents_model::STATUS_PROVED != $userDocumentStatus)){
                $data->isCanPay = false;
                $data->error    = 22; //_e("У вас не загружены документы");
            }
//            else if (
//                        !(
//                            !in_array($own, getUntrastedUsers4Send()) && //если пользователь доверенный
////                            (in_array($own, getCheckedUsers()) || true === ($canSend = $this->accaunt->isCanSendMoneyCurExc($send_transaction, true)))
//                            (in_array($own, getCheckedUsers()) ) //|| true === ($canSend = $this->accaunt->isCanSendMoneyCurExc($send_transaction, true)))
//                        )
//                    )
//            {
//                $data->isCanPay = false;
//                $data->error = _e($canSend);
//            }
        } else {
            $data->isCanPay = false;
            $data->error    = _e('Не удалось получить ваши данные профиля. Обратитесь в тех. поддержку.');
        }

//vred($summa, $data->error, $data->isCanPay);
        if($data->isCanPay === TRUE){

            return TRUE;
        }


        return $data->error;
    }

    //todo временный метод для крона - хотя возможно потом и понадобится расчёт для конкретного пользователя
    public function check_user_for_payout_temp_for_cron($own, $summa, $contragent = false){
        $data = new stdClass();

        $data->isCanPay = TRUE;
        //return TRUE;
//        $own = $this->id_user;
//        $summa = $data->vars->summary;
//        $rating = viewData()->accaunt_header;
        $this->load->model('accaunt_model');
//        pred($this->accaunt_model->recalculateUserRating($own));
        $rating                    = $this->accaunt_model->recalculateUserRating($own);
        $this->load->model('documents_model', 'documents');
        $userDocumentStatus        = $this->documents->getUserDocumentStatus($own);
        $send_transaction          = new stdClass;
        $send_transaction->summa   = $summa;
        $send_transaction->own     = $own;
        $send_transaction->id_user = $contragent;
//            $send_transaction->note    = $data->vars->description;
        if($rating){
            if(!$this->accaunt->isUserAccountVerified($own)){
                $data->isCanPay = false;
                $data->error    = _e("Аккаунт не верифицирован");
            } else if($summa > $rating['payout_limit']){
                $data->isCanPay = false;
                $data->error    = _e("Недостаточно средств");
            } else if($rating['overdue_standart_count'] > 0){
                $data->isCanPay = false;
                $data->error    = _e("Есть просроченные кредиты стандарт");
            } else if($rating['overdue_garant_count'] > 0){
                $data->isCanPay = false;
                $data->error    = _e("Есть просроченные кредиты гарант");
//                else if ($this->accaunt->isUserUSorCA($id_user) || $this->accaunt->isUserUSorCA())
//                    $data->isCanPay = false;
            } else if(!in_array($own, getUnlimitedUsers()) && (0 <= $summa && Documents_model::STATUS_PROVED != $userDocumentStatus)){
                $data->isCanPay = false;
                $data->error    = _e("У вас не загружены документы");
            } else if(
                !(
                !in_array($own, getUntrastedUsers4Send()) && //если пользователь доверенный
//                            (in_array($own, getCheckedUsers()) || true === ($canSend = $this->accaunt->isCanSendMoneyCurExc($send_transaction, true)))
                (in_array($own, getCheckedUsers()) || true === ($canSend = $this->accaunt->isCanSendMoneyCurExc($send_transaction, true)))
                )
            ){
                $data->isCanPay = false;
                $data->error    = _e($canSend);
            }
        } else
            $data->isCanPay = false;

//vred($summa, $data->error, $data->isCanPay);
        if($data->isCanPay === TRUE){
            return true;
        } else {
            return $data->error;
        }
//            if ($data->isCanPay){
    }

    /**
     * Получить данные о последней смене старшего партнера
     */
    public function get_last_partner_change($user_id){
        return $this->db
                ->where('user_id', $user_id)
                ->limit(1)
                ->order_by('id', 'desc')
                ->get('partner_change')
                ->row();
    }

    /**
     * Добавляет запрос на изменение старшешл партнера
     * @param type $user_id
     * @param type $parent_user_id
     */
    public function add_change_partner_request($user_id, $parent_user_id){

        // удалим остальные запросы
        $this->db->delete('partner_change_request', ['user_id' => $user_id]);
        $this->db->insert('partner_change_request', [
            'user_id'        => $user_id,
            'parent_user_id' => $parent_user_id,
            'create_dttm'    => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Возвращает все запросы на изменение старшего партнера
     * @param type $user_id
     * @param type $parent_user_id
     * @return type
     */
    public function get_change_partner_requests($user_id = NULL, $parent_user_id = NULL, $statuses = []){

        if(!empty($statuses))
            $this->db->where_in('status', $statuses);
        if(!empty($user_id))
            $this->db->where('user_id', $user_id);
        if(!empty($parent_user_id))
            $this->db->where('parent_user_id', $parent_user_id);

        return $this->db->get('partner_change_request')->result();
    }

    public function change_partner($user_id, $new_partner_id){
        $old_parent_id = $this->getParent($user_id);
        $this->db->insert('partner_change', [
            'user_id'        => $user_id,
            'old_partner_id' => $old_parent_id,
            'new_partner_id' => $new_partner_id,
            'dttm'           => date('Y-m-d H:i:s')]);
        $this->setParent($user_id, $new_partner_id);
        $this->setVolunteer2All($user_id, $new_partner_id);
    }

    /**
     * Получаем заявку по ID
     * @param type $id
     * @return type
     */
    public function get_change_partner_request($id){
        return $this->db
                ->where('id', $id)
                ->get('partner_change_request')
                ->row();
    }

    /**
     * Меняет статус заявки
     * @param type $id
     * @param type $status
     * @return type
     */
    public function set_change_partner_request_status($id, $status){
        $request = $this->get_change_partner_request($id);
        if(empty($request))
            return FALSE;

        if($request->status == $status)
            return FALSE;

        if($request->status != 0)
            return FALSE;

        $this->db->update('partner_change_request', [
            'status'             => $status,
            'change_status_dttm' => date('Y-m-d H:i:s')
            ], ['id' => $id]);
        // если запрос принят то запишем в бд
        if($status == self::PARTNER_CHANGE_STATUS_ACCEPT){
            $this->change_partner($request->user_id, $request->parent_user_id);
        }
        return TRUE;
    }

    public function isUsaLimitedUser($user_id = NULL){
        if(empty($user_id))
            $user_id = get_current_user_id();

        return !empty($this->db->where('user_id', $user_id)->get('limited_usa_users')->row());
    }

    public function get_ewallet_data($user_id = NULL){
        $this->load->model("transactions_model", "transactions");
        $this->load->library('code');
        dev_log("get_ewallet_data uid=$user_id");
        if(empty($user_id))
            $user_id = get_current_user_id();

        $payeer = $this->db->where(['user_id' => $user_id, 'account_type' => 'E_WALLET', 'account_extra_data' => 'Payeer'])->get('users_other_accounts')->row();
        $okpay = $this->db->where(['user_id' => $user_id, 'account_type' => 'E_WALLET', 'account_extra_data' => 'Okpay'])->get('users_other_accounts')->row();
        $perfectmoney = $this->db->where(['user_id' => $user_id, 'account_type' => 'E_WALLET', 'account_extra_data' => 'Perfectmoney'])->get('users_other_accounts')->row();

        $data               = new stdClass();
        $data->payeer       = empty($payeer)?NULL:$payeer->account_personal_num;
        $data->okpay        = empty($okpay)?NULL:$okpay->account_personal_num;
        $data->perfectmoney = empty($perfectmoney)?NULL:$perfectmoney->account_personal_num;

        $data->payeer_id       = empty($payeer)?NULL:$payeer->id;
        $data->okpay_id        = empty($okpay)?NULL:$okpay->id;
        $data->perfectmoney_id = empty($perfectmoney)?NULL:$perfectmoney->id;

        $userdata           = $this->db->where("id_user", $user_id)->get('users')->row();
        if(!empty($userdata->bank_lava) && empty($data->payeer))
            $data->payeer       = $this->code->decrypt($userdata->bank_lava);
        if(!empty($userdata->bank_okpay) && empty($data->okpay))
            $data->okpay        = $this->code->decrypt($userdata->bank_okpay);
        if(!empty($userdata->bank_perfectmoney) && empty($data->perfectmoney))
            $data->perfectmoney = $this->code->decrypt($userdata->bank_perfectmoney);

        dev_log("get_ewallet_data returns for $user_id ".print_r($userdata, true));
        return $data;
    }

    public function blockChilds($user_id){
        if(empty($user_id))
            $user_id = get_current_user_id();

        $this->db->update($this->tableName, ['state' => 3, 'status_cause' => 'Боты от старшего'], ['parent' => $user_id]);
    }

    public function getUserState($user_id){
        if(empty($user_id))
            $user_id = get_current_user_id();

        $state = $this->db->get_where($this->tableName, array("id_user" => $user_id))->row("state");
        return $state;
    }

    public function get_user_info($p){
        $id_user = intval($p->id_user);
        $data    = $this->base_model->getUserInfo($id_user);

        if($id_user === 400400){
            return $this->return_object_error('Для этого пользователя нет инфо');
        }

        if(empty($data)){
            return $this->return_object_error('No data');
        }


        $data->id = (int) $p->id;
        $social   = $this->accaunt->getSocialList($id_user);

        $user_avatar       = null;
        $data->social_list = [];



        $current_user_id = $this->accaunt->get_user_id();
        $debit           = null;
        $data->show_docs = FALSE;
        if(!empty($data->id)){
            $debit = $this->accaunt->getCredit($data->id);
        }

        //только Стандарт
        if(!empty($debit) && $debit->garant == Base_model::CREDIT_GARANT_OFF){

            if(( $debit->type == Base_model::CREDIT_TYPE_INVEST && $debit->id_user == $current_user_id ) || //Вложения, Стандарты в заявках
                $debit->state == Base_model::CREDIT_STATUS_SHOWED && $debit->type == Base_model::CREDIT_TYPE_CREDIT //Займы на бирже
            ){
                $data->show_docs = TRUE;
            }
        }

        $user_avatar = getUserAvatar($id_user);
        foreach($social as $item){

            if(!empty($item->url)){
                $data->social_list[$item->name] = [];
                if($data->show_docs === TRUE){
                    $data->social_list[$item->name]['url'] = $item->url;
                    $data->social_list[$item->name]['name'] = $item->name;
                } else
                    $data->social_list[$item->name]['name'] = $item->name;
            }
        }
        $social_id = get_social_id($id_user);
        if ( !empty($social_id) ){
            $data->social_list['wt_social']= [];
            $data->social_list['wt_social']['name'] = 'wt_social';
            $data->social_list['wt_social']['url'] = "https://webtransfer.com/social/profile/$social_id?lang=en";
        }

        if($data->show_docs === TRUE){
            $this->load->model('documents_model', 'documents');
            $doc_name = $this->documents->get_doc_file_name_by_user_id($id_user, 1, 2);
            $data->docs = [];
            if(FALSE !== $doc_name){
                $data->docs['psprt']['img'] = base64_encode($this->code->fileDecode("upload/doc/".$doc_name));
                $data->docs['psprt']['img_type'] = explode('.', $doc_name)[1];
            }

            $another_doc_name = $this->documents->get_doc_file_name_by_user_id($id_user, 5);

            if(FALSE !== $another_doc_name){
                $data->docs['other']['img'] = base64_encode($this->code->fileDecode("upload/doc/".$another_doc_name));
                $data->docs['other']['img_type'] = explode('.', $another_doc_name)[1];
            }
        }

        $data->user_avatar = (empty($user_avatar) ? '' : $user_avatar);

        $rating = $this->accaunt->recalculateUserRating($id_user);

        $this->load->model('user_balans_model', 'user_balans');
        $fsr       = $this->user_balans->generateNewFsrToBot($id_user);
        if($fsr !== FALSE)
            $data->fsr = $fsr;
        else
            $data->fsr = $rating['fsr'];

        // определяем сколько звезд у данной степени диверсификации

        $data->diversification_degree = $rating['diversification_degree'];

        $month_partner_unic_id_count = $rating['month_partner_unic_id_count'];
        $data->dd_rate_start         = 0;
        if($month_partner_unic_id_count > 300){
            $data->dd_rate_start = 6;
        } else
        if($month_partner_unic_id_count > 100){
            $data->dd_rate_start = 5;
        } else
        if($month_partner_unic_id_count > 50){
            $data->dd_rate_start = 4;
        } else
        if($month_partner_unic_id_count > 30){
            $data->dd_rate_start = 3;
        } else
        if($month_partner_unic_id_count > 20){
            $data->dd_rate_start = 2;
        } else
        if($month_partner_unic_id_count > 10){
            $data->dd_rate_start = 1;
        }


//        $data->max_loan_available = $rating['max_loan_available']; // очень не секретно - можно узнать у кого сколько денег на кашельке и потом делать атаку

        return $this->return_data((array) $data);

//        $data->debit = $debit;

//        $data->showButton = TRUE;

//        $this->load->model("visualdna_model", "visualdna");
//        $data->visualdnaStatus   = $this->visualdna->getStatus($id_user);
//        $data->visualdnaMyStatus = $this->visualdna->getStatus($current_user_id);
//        $data->current_user_id   = $current_user_id;
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

}
