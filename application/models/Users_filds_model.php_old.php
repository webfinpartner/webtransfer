<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_filds_model extends CI_Model {

    public $tableName = "users_filds";

    private $_res = array();

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

    public function saveUserFild($user){

        if(!isset($user->id_user)){
            return FALSE;
        }

        if ($this->_isInsert(get_object_vars($user)))
            $this->db->insert($this->tableName, $user);
        else{
            $this->db->where('id_user', $user->id_user)
                    ->update($this->tableName, $user);
        }
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

        if(count($this->_res) && isset($this->_res[$id])){
            $res = $this->_res[$id];
        }else{
           $res = $this->db
                ->get_where($this->tableName,'id_user = '.$id)
                ->row();

           $this->_res[$id] = $res;
        }

        if(count($res) && !empty($res->nickname) && $res->is_show){

            return $res->nickname;

        }else{
            return FALSE;
        }
    }

    public function getUserFild($id, $fild = FALSE){

        if(count($this->_res) && isset($this->_res[$id])){
            $res = $this->_res[$id];
        }else{

           $res = $this->db
                ->get_where($this->tableName,'id_user = '.$id)
                ->row();

           $this->_res[$id] = $res;
        }

        if(FALSE === $fild)
        {
            return $res;
        }

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

        $this->db
                ->select("*")
                ->where_in('id_user', $arr_ids);

        $res =  $this->db
                  ->get($this->tableName)
                  ->result();

        foreach($res as $val)
        {
            $this->_res[$val->id_user] = $val;
        }

        return $res;
    }

}
