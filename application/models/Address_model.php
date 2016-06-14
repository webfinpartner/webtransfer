<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_model extends CI_Model {


    function __construct() {
        parent::__construct();
    }


    public function getAdressByUserId( $userId = null ) {
        if( $userId == null ){
            if( isset($this->account) ) $this->accaunt = $this->account;
            else
                if( !isset($this->accaunt) ) $this->load->model('accaunt_model','accaunt');
            $userId = $this->accaunt->get_user_id();
        }
        if( $userId == null ) return null;

        $this->load->library('code');
        $address = $this->db->where( array( 'id_user' => $userId, 'state' => 1 ) )->get('address')->row();
        if( empty( $address ) ) $address = $this->db->where( array( 'id_user' => $userId, 'state' => 2 ) )->get('address')->row();

        return $this->code->db_decode( $address );
    }

}
