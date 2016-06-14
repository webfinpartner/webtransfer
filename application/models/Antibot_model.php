<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Antibot_model extends CI_Model {
    

    function __construct() {
        parent::__construct();        
    }

    
    //Считаем количество запросов и среднее время 1 запроса из 10 шт. 
    //Если меньше 5 - ставим в бан. Время бана = сумма частот запросов страницы.
    function check_ajax_by_part_uri( $uri_part )
    {
        
        if( empty( $uri_part ) ) 
            return 1;
        
        
        if( is_string( $uri_part ) )
            $uri_part = array( $uri_part );
        
        $exists = FALSE;
        foreach( $uri_part as $uri )        
        {
            if( strpos( $_SERVER['REQUEST_URI'], $uri ) === FALSE ) continue;
            
            $exists = TRUE;
            break;
        }           
        
        if( !$exists )
            return 2;
                    
        $page_uri = $_SERVER['REQUEST_URI'];
        $page_ref = $_SERVER['HTTP_REFERER'];
        $user_ip = $_SERVER['HTTP_X_REAL_IP'];
        $user_id = $this->accaunt->get_user_id();
        $avrg_time = 0;

        $user_data_src = $this->session->userdata('update_page');

        $timeout = 0;
        $exp_time = 0;
        if( !empty( $user_data_src ) )
        {
            $user_data = unserialize( $user_data_src );

            $count = count( $user_data );
            $exp_time = $user_data[ $count - 1 ]['exp_time'];
            if( $exp_time > 0 && strtotime( $exp_time ) >= time() )
                die('Вы слишком часто делаете запросы к серверу. Доступ ограничен до '.$exp_time );

            if( $count >= 30 )
            {
                $time = 0;
                $last = strtotime(  $user_data[0]['date'] );
                for( $i = 1; $i < count( $user_data ); $i++ )
                {
                    $d = strtotime( $user_data[$i]['date'] );
                    $time += abs($d - $last);

                    $last = $d;
                }

                $avrg_time = round( ( $time / $count ), 2 );
                
                if( $avrg_time < 5 ) //bilo 10
                {                    
//                    $user_data = 
//                    $this->db->where( 'user_id', $user_id )
//                            ->get( 'user_page_requests' )
//                            ->result();
//
//                    $avrg_time_v2 = $avrg_time;
//                    foreach( $user_data as $ud )                        
//                        $time_v2 += (int)$ud->avrg_time;
//
//                    $avrg_time_v2 = $time_v2 / count( $user_data ) + 1;
//
//                    if( $avrg_time_v2 < 3 )                        
//                        $timeout = $ud->timeout + (5 - $avrg_time_v2);

                    $timeout = 3 * 3600;
                    $exp_time = date('Y-m-d H:i:s', ( time() + $timeout ));
                }

                ob_start();
                var_dump($user_data);
                $result = ob_get_clean();
                
                $this->db->insert('user_page_requests', array( 'user_id' => $user_id, 
                                                                'data' => $result, 
                                                                'page_uri' => $page_uri, 
                                                                'count' => $count,
                                                                'avrg_time' => $avrg_time,
                                                                'user_ip' => $user_ip,
                                                                'timeout' => $timeout,
                                                                'exp_time' => $exp_time,
                                                            ) );

                if( $avrg_time < 0 ) $avrg_time = 5;

                if( $this->db->insert_id() ) $user_data = array();
            }
        }else
            $user_data = array();

        $data = array(
            'date' => date( 'Y-m-d h:i:s', time() ),
            'page' => $page_uri,            
            'page_ref' => $page_ref,
            'timeout' => $timeout,
            'exp_time' => $exp_time
        );


        $user_data[] = $data;

        $this->session->set_userdata('update_page', serialize( $user_data ) );
        
            
    }

}