<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_filds_model extends CI_Model {

    public $tableName = "users_filds";
    private $_cech_life = '+1 day';

//    private $_res = array();

    public function __construct() {
            parent::__construct();
            if(!isset($_SESSION['_res']))
                $_SESSION['_res'] = [];
    }

    public function saveUserFilds($data){

        if(!isset($data['id_user']) || !isset($data['nickname'])){
            return FALSE;
        }

        if(!isset($data['is_show'])){
            $data['is_show'] = 0;
        }

        if ($this->_isInsert($data)) {
            $this->db
                    ->insert($this->tableName, $data);
        }else{
            $this->db
                    ->where('id_user', $data['id_user'])
                    ->update($this->tableName, $data);
        }
        
    }

    public function saveUserNetwork($data){

        if ($this->_isInsert($data)) {
            $this->db
                    ->insert($this->tableName, $data);
        }else{
            $this->db
                    ->where('id_user', $data['id_user'])
                    ->update($this->tableName, $data);
        }
    }

    public function saveUserFild($user){
        
        if(!isset($user->id_user)){
            return FALSE;
        }

        if ($this->_isInsert(get_object_vars($user))){            
            $this->db->insert($this->tableName, $user);
        }
        else{
            $this->db->where('id_user', $user->id_user)
                    ->update($this->tableName, $user);
            
        }
        return TRUE;
    }


    private function _isInsert($data){
        $insert = FALSE;

        if($data['id_user'] == 0){
           $insert = TRUE;
        }

       $res = $this->db
                ->get_where($this->tableName, 'id_user = '.$data['id_user'])
                ->num_rows();

       if($res == 0){
           return TRUE;
       }

       return $insert;
    }




    public function getUserNickname($id){
        if(count($_SESSION['_res']) && isset($_SESSION['_res'][$id]) && time() < strtotime($this->_cech_life, $_SESSION['_res'][$id]['date'])){
            $res = $_SESSION['_res'][$id]['val'];
        }else{
           $res = $this->db
                ->get_where($this->tableName,'id_user = '.$id)
                ->row();

           $_SESSION['_res'][$id]['val'] = $res;
            $_SESSION['_res'][$id]['date'] = time();
        }

        if(count($res) && !empty($res->nickname) && $res->is_show){

            return $res->nickname;

        }else{
            return FALSE;
        }
    }

    public function getUserFild($id, $fild = FALSE, $use_cache = FALSE){
        if(count($_SESSION['_res']) && isset($_SESSION['_res'][$id]) && time() < strtotime($this->_cech_life, $_SESSION['_res'][$id]['date']) && $use_cache){
            $res = $_SESSION['_res'][$id]['val'];
        }else{

           $res = $this->db
                ->get_where($this->tableName,'id_user = '.$id)
                ->row();

           $_SESSION['_res'][$id]['val'] = $res;
            $_SESSION['_res'][$id]['date'] = time();
        }

        if(FALSE === $fild) return $res;

         if(count($res) && isset($res->{$fild})){

            return $res->{$fild};

        }

        return false;
    }



    public function isNotUniqueFild(array $data){
        if (!count($data) || !isset($data['id_user'] )|| (isset( $data['nickname'] ) && empty( $data['nickname'] )) )
        {
           return FALSE;
        }


        $id = $data['id_user'];
        unset($data['id_user']);

        $data['id_user !='] = $id;

        $res = $this->db
                ->get_where($this->tableName,$data)
                ->num_rows()
            ;

        if($res > 0){
            return TRUE;
        }

        return FALSE;
    }



    public function getUsersByIds($ids, $fild = 'id_user'){

        if(!count($ids)){
            return FALSE;
        }

        $arr_ids = array();

        reset($ids);

        if(is_object(current($ids))){
            foreach($ids as $val){
                $arr_ids[] = $val->{$fild};
            }
        }else{
           $arr_ids = array( $ids );
        }

        $arr_ids = array_unique($arr_ids);

        if(count($_SESSION['_res'])){
            foreach ($arr_ids as $k => $u_id) {
                if(isset($_SESSION['_res'][$u_id]) && time() < strtotime($this->_cech_life, $_SESSION['_res'][$u_id]['date']))
                    unset($arr_ids[$k]);
            }
        }

        if(!count($arr_ids)){
            return FALSE;
        }

        $this->db
                ->select("*")
                ->where_in('id_user', $arr_ids);

        $res =  $this->db
                  ->get($this->tableName)
                  ->result();

        foreach($res as $val)
        {
            $_SESSION['_res'][$val->id_user]['val'] = $val;
            $_SESSION['_res'][$val->id_user]['date'] = time();
        }

        return $res;
    }

    public function issetUser($id, $fild = 'id_user'){

        if(!count($id)){
            return FALSE;
        }

        $res = $this->db
            ->get_where($this->tableName, $fild.' = '.$id)
            ->row();
        return $res ? true : false;
    }

}
