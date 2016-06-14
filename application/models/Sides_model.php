<?php

class Sides_model extends CI_Model {

    private $layout;
    private $current_uri;
    private $current_user_id;
    
    public function __construct() {
        parent::__construct();
        $this->layout = array();   
        $this->current_uri = uri_string();
        
    }
    
    public function get_left_side()
    {
        if( strpos( $this->current_uri, 'opera') !== FALSE ) return FALSE;
        
        //$this->add_to_left( 0,['*'],[],1,[$this, get_arbitration_button ]);
                
        $this->load->model('currency_exchange_model', 'currency_exchange');
        
//        if( in_array( $this->user->id_user, $this->currency_exchange->get_test_user_ids() ) ){
//        if( $this->user->id_user == 500733 ){
                        
            $this->add_to_left( 1,'*',[], 1, [ $this, get_left_side_transactions] );            
            //$this->add_to_left( 1,'*',['currency_exchange'], 1, [ $this, get_left_side_transactions] );            
            $this->add_to_left( 2,'*',[], 1, [ $this, get_left_side_p2p_cur_pairs] );            
            
            //$this->add_to_left( 1,['currency_exchange'],[], 1, [ $this, get_left_side_p2p_cur_pairs] );
            //$this->add_to_left( 2,['currency_exchange'],[], 1, [ get_left_side_p2p], ['currency_exchange_model', 'currency_exchange'] );
            
        
        return $this->render_left_side();
    }
    
    private function uri_in_array( $uri_part )
    {
        
        if( empty( $uri_part ) ) return FALSE;
        //array
        if( !is_string( $uri_part ) && is_array( $uri_part ))
        {
            if( in_array( '*', $uri_part ) ) return TRUE;
            
            foreach( $uri_part as $uri )
            {
                
                if( strpos( $this->current_uri, $uri) !== FALSE ) return TRUE;
            }
            
            return FALSE;
        }
        
        //string
        if( '*' == $uri_part ) $pos = TRUE;
        else
            $pos = (bool)strpos( $this->current_uri, $uri_part);
        
        return $pos;
    }
    
    private function add_to_left($order, $uri_inclide, $uri_exclude, $module_status, $fnc_name, $load_model = FALSE) 
    {
        if( !is_numeric( $order ) || 
                (empty( $uri_exclude ) && empty( $uri_inclide ) ) ||
                empty( $fnc_name )
        )
        return FALSE;
        
        //модуль включен, можно ли вызвать функцию получения данных?
         if( $module_status == TRUE && !is_callable( $fnc_name ) && empty($load_model) ) return FALSE;
        
        if( $this->uri_in_array( $uri_inclide ) == FALSE ||
            $this->uri_in_array( $uri_exclude ) == TRUE )
        {
            return FALSE;
        }
        
        
        
         
        if( isset( $this->layout[ $order ] ) ) return FALSE;
        
        $this->layout[ $order ] = array( 'module_status' => $module_status,'fnc_name' => $fnc_name, 'load_model' => $load_model );
    }
    
    private function load_model_get_callable_fnc( $load_model, $fnc_name_src, $load_model_on = FALSE )
    {
        $fnc_name = FALSE;
        
        if( $load_model_on )$this->load->model( $load_model[0], $load_model[1] );
        
        if( !empty($load_model[1] ) ) $fnc_name = array( $this->{$load_model[1]}, $fnc_name_src[0] );
        else
            $fnc_name = array( $this->{$load_model[0]}, $fnc_name_src[0] );

        if( !is_callable( $fnc_name ) ) return FALSE;
        return $fnc_name;
    }
            
    private function render_left_side()
    {
        $out_res = '';
        
        
        foreach( $this->layout as $l )
        {         
            //echo "-1-";
            if( empty( $l ) )                continue;
            
            $load_model     = $l['load_model'];
            $fnc_name       = $l['fnc_name'];            
            
            if( $load_model !== FALSE )
                $fnc_name = $this->load_model_get_callable_fnc( $load_model, $fnc_name, TRUE );
            
            //вызываем функцию проверки статуса. Если FALSE = пропускаем
            if( $fnc_name === FALSE )  continue;
            
                    //'fnc_name' => $fnc_name, 'load_model' => $load_model
            $out = call_user_func( $fnc_name );            

            if( !is_string( $out ) ) continue;
            
            $out_res .= $out;
        }
        
        return $out_res;
    }
    
    public function get_left_side_others()
    {
        return $this->load->view('user/blocks/statistics', [], TRUE);
    }
    public function get_left_side_p2p_cur_pairs()
    {
        $this->load->model('currency_exchange_statistic_model','currency_exchange_statistic');
        return $this->currency_exchange_statistic->get_left_side_p2p_cur_pairs();
    }
    public function get_left_side_transactions()
    {
        return $this->load->view('user/accaunt/modules/left_side__statistics', [], TRUE);
    }
    
    public function get_arbitration_button()
    {
        $str = '<div style="border: 1px solid rgb(204, 204, 204);width: 171px; margin-bottom: 7px; background-color: #ff5200 !important; padding: 10px;text-align:center;height:32px;">'.
                    '<a href="'. site_url('account/arbitrage_credit') ."\" style='color:#ffffff;'>". _e('blocks/header_user_5') . "</a>".
                '</div>';
        return $str;
    }
}

