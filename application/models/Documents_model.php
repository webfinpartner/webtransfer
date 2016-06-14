<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documents_model extends CI_Model {

    const STATUS_WAITING = 1;
    const STATUS_PROVED = 2;
    const STATUS_DECLINED = 3;


    public $table_name = 'documents';


    public function getUserDocumentStatus($user_id = null, $type = 1) {

        if (!$user_id) {
            $this->load->model('accaunt_model', 'accaunt');

            $user_id = $this->accaunt->get_user_id();

        }

        $res = $this->db->where(array('id_user' => $user_id, 'num' => $type))
                        ->get('documents')->row();


        if(empty($res) || !isset($res->state)){
            return NULL;
        }

        return $res->state;
    }

//    public function getDocumentVerified($user_id = nul)
//    {
//        $status =  $this->getUserDocumentStatus($user_id);
//
//        if(is_null($status))
//        {
//            return false;
//        } else {
//
//        }
//
//    }

    /**
     * Get user's documents
     *
     * @param type $user_id
     * @param type $doc_num
     * @param type $old - don't use
     * @return boolean
     */
    function get_doc_file_name_by_user_id($user_id, $doc_num, $old = 0 ) {

        if( empty( $user_id ) || empty( $doc_num ) )
            return FALSE;

//        $select = 'img';
//        if (intval($old) == 2)
//            $select = 'img2';

        $pic = $this->db->select('img','img2')
                        ->where(array('num' => $doc_num, 'id_user' => $user_id, 'state' => self::STATUS_PROVED))
                        ->get( $this->table_name )
                        ->row();

        if( empty( $pic ) )
            return FALSE;

        if( !empty( $pic->img ) ) return $pic->img;
        else
            return FALSE;
    }


}
