<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_social_friends_model extends CI_Model {
    
//    private $_google = 'Google';
//    private $_odnoklassniki = 'Odnoklassniki';
    
    const GOOGLE = 'Google';
    const ODNOKLASSNIKI = 'Odnoklassniki';
    const TWITTER = 'Twitter';
    const FACEBOOK = 'Facebook';
    const VKONTACTE = 'Vkontakte';
    const MAILRU = 'Mailru';


    public $tableName = "users_social_friends";
    
    
    private function _addFriends($friends){
        if(!count($friends)){
            return FALSE;
        }
        
        if(isset($friends->user_id)){
            $this->db
                 ->insert($this->tableName, $friends);
        }else{
            $this->db
                 ->insert_batch($this->tableName, $friends);
        }
    }
    
    
    
    private function _eraseUserFriends($user_id, $social_name){
        $this->db->where(
                array(
                    'user_id'=> $user_id, 
                    'social_name' => $social_name
                )
             );
        $this->db->delete($this->tableName);
    }

    private function _getLocalFriends($social_name, $user_id = FALSE){
        if(FALSE === $user_id ){
            $user_id = $this->accaunt->get_user_id();
        }
        $res = $this->db
                    ->where(array(
                                    'user_id' => $user_id, 
                                    'social_name' => $social_name
                                ))
                    ->get($this->tableName)
                    ->result();
        
        return $res;
    }
    
    
    private function _setFriends($social_name, $assoc_array, $ucs, $user_id = false){
    
        if(!count($ucs)){
            return FALSE;
        }
        
        if(FALSE === $user_id ){
            $user_id = $this->accaunt->get_user_id();
        }
        
        $userFrinds = array();
        
        foreach ($ucs as $key => $uc){            
            if(!isset($uc->identifier)){
                continue;
            }
            
            $userFrinds[$key]['user_id'] = $user_id;
            $userFrinds[$key]['social_name'] = $social_name;
            
            $userFrinds[$key]['friend_id']   = isset($uc->{$assoc_array['friend_id']})?$uc->{$assoc_array['friend_id']}:'';
            $userFrinds[$key]['f_name']      = isset($uc->{$assoc_array['f_name']})?$uc->{$assoc_array['f_name']}:'';
            $userFrinds[$key]['l_name']      = isset($uc->{$assoc_array['l_name']})?$uc->{$assoc_array['l_name']}:'';
            $userFrinds[$key]['s_name']      = isset($uc->{$assoc_array['s_name']})?$uc->{$assoc_array['s_name']}:'';
            $userFrinds[$key]['social_uri']  = isset($uc->{$assoc_array['social_uri']})?$uc->{$assoc_array['social_uri']}:'';
            $userFrinds[$key]['foto']        = isset($uc->{$assoc_array['foto']})?$uc->{$assoc_array['foto']}:'';
        }
        
        if(count($userFrinds)){
            $this->_eraseUserFriends($user_id, $social_name);
            $this->_addFriends($userFrinds);
            
            return json_decode(json_encode($userFrinds));
        }
        
        return FALSE;
    }

    




//    public function setGoogleFriends($ucs, $user_id = false){
//        if(!count($ucs)){
//            return FALSE;
//        }
//        
//        if(FALSE === $user_id ){
//            $user_id = $this->accaunt->get_user_id();
//        }
//        
//        $userFrinds = array();
//        
//        foreach ($ucs as $key => $uc){            
//            if(!isset($uc->identifier)){
//                continue;
//            }
//            
//            $userFrinds[$key]['user_id'] = $user_id;
//            $userFrinds[$key]['social_name'] = self::GOOGLE;
//            
//            $userFrinds[$key]['friend_id']   = $uc->identifier;
//            $userFrinds[$key]['f_name']      = $uc->displayName;
//            $userFrinds[$key]['l_name']      = '';
//            $userFrinds[$key]['s_name']      = '';
//            $userFrinds[$key]['social_uri']  = $uc->profileURL;
//            $userFrinds[$key]['foto']        = $uc->photoURL;
//        }
//        
//        if(count($userFrinds)){
//            $this->_eraseUserFriends($user_id);
//            $this->_addFriends($userFrinds);
//        }
//    }
    public function setGoogleFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'displayName';
//        $assoc_array['l_name']      = '';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::GOOGLE, $assoc_array, $ucs, $user_id);
    }
    
    
    public function setOkFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'displayName';
        $assoc_array['l_name']      = 'l_name';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::ODNOKLASSNIKI, $assoc_array, $ucs, $user_id);
    }
    
    
    public function setTwitterFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'displayName';
//        $assoc_array['l_name']      = 'l_name';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::TWITTER, $assoc_array, $ucs, $user_id);
    }
    
    
    
    
    public function setFacebookFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'displayName';
//        $assoc_array['l_name']      = 'l_name';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::FACEBOOK, $assoc_array, $ucs, $user_id);
    }
    
    public function setVkontakteFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'displayName';
//        $assoc_array['l_name']      = 'l_name';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::VKONTACTE, $assoc_array, $ucs, $user_id);
    }
    
    
    public function setMailruFriends($ucs, $user_id = false){

        $assoc_array['friend_id']   = 'identifier';
        $assoc_array['f_name']      = 'first_name';
        $assoc_array['l_name']      = 'last_name';
//        $assoc_array['s_name']      = '';
        $assoc_array['social_uri']  = 'profileURL';
        $assoc_array['foto']        = 'photoURL';
        
        return $this->_setFriends(self::MAILRU, $assoc_array, $ucs, $user_id);
    }
    
    

    
    
    
    
    
    
    
    
    public function getGoogleLocalFriends($user_id = FALSE){
                
        return $this->_getLocalFriends(self::GOOGLE, $user_id);
    }
    
    
    public function getOkLocalFriends($user_id = FALSE){
               
        return $this->_getLocalFriends(self::ODNOKLASSNIKI, $user_id);
    }
    
    
    public function getTwitterLocalFriends($user_id = FALSE){
               
        return $this->_getLocalFriends(self::TWITTER, $user_id);
    }
    
    public function getFacebookLocalFriends($user_id = FALSE){
               
        return $this->_getLocalFriends(self::FACEBOOK, $user_id);
    }
    
    public function getVkontakteLocalFriends($user_id = FALSE){
               
        return $this->_getLocalFriends(self::VKONTACTE, $user_id);
    }
    
    public function getMailruLocalFriends($user_id = FALSE){
               
        return $this->_getLocalFriends(self::MAILRU, $user_id);
    }
    
    
    
    
    
}