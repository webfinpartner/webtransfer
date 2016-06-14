<?php

if( !defined( 'BASEPATH' ) )
    exit( 'No direct script access allowed' );

class Currency_exchange_statistic_model extends CI_Model{

    
    static $last_error;

    public $table_pairs_statistic = 'module_p2p_pairs_statistic';
    public $update_interval = 3600; //s = 1 h
    public $update_window = 172800;//86400; 1d//259200; //s = 3d
    
    public function __construct()
    {
        parent::__construct();                         
    }
    
    //сбор статистики по завершенным сделкам
    public function calc_p2p_statistic()
    {
        //1. Выбрать все Завершенные сделки за Последний час, где приняли участие валюта Дебит
        $grouped_by_ps = [];
        $ps_list = [113,114, 116];

        
        $result = [];
        foreach( $ps_list as $left_ps_id ){
    
            $result[ $left_ps_id ] = [];
            
            $grouped_by_ps[ $left_ps_id ] = [];
            $current_group = &$grouped_by_ps[ $left_ps_id ];

            $up_time = time() - $this->update_window;
//            $down_time = '';
            
//            $up_time = strtotime('2015-12-22 09:00:00');
//            $down_time = strtotime('2015-12-22 15:00:00');
            
            $this->load->model('currency_exchange_model','currency_exchange');
            
            $orders = $this->db->select(['sell_system','payed_system','buyer_amount_down','seller_amount','original_order_id','seller_get_money_date','status', 'order_details_arhiv'])
                                ->where('status',60)
                                ->where('initiator',1)
                                ->where('wt_set',  Currency_exchange_model::WT_SET_SELL)

                                ->where('seller_get_money_date >=', date('Y-m-d H:i:s',$up_time))
//                                ->where('seller_get_money_date <=', date('Y-m-d H:i:s',$down_time))
                                ->where("sell_system = $left_ps_id")

                                ->group_by('original_order_id')

                               // ->limit(100)

                               ->get('currency_exchange_orders_arhive')
                               ->result();

            //2. Сгруппировать по сторонней валюте с сохранением сумм и валют

            foreach( $orders as $k => &$o )
            {            
                if( !isset( $current_group[ $o->payed_system ] ) ) $current_group[ $o->payed_system ] = [];
                
                $payed_system = $o->payed_system;
                
                #<--getting the real currency
                $data = unserialize($o->order_details_arhiv);
                foreach( $data as $r )
                {
                    if( $r->payment_system != $o->payed_system ) continue;
                    $o->payed_currency_id = $r->choice_currecy;                        
                    break;
                }
                unset($o->order_details_arhiv);
                #getting the real currency-->
                
                if( !empty( $o->payed_currency_id ) ) $payed_system = $payed_system.'-'.$o->payed_currency_id;
                
                $current_group[ $payed_system ][ $o->original_order_id ] = &$o;
            }

            
            //3. Сортируем массив по количеству сделок в каждой паре
            $sorted_by_count = [];
            foreach( $current_group as $ps_id => $g )            
                $sorted_by_count[] = ['ps' => $ps_id, 'count' => count( $g )];            
            
            $c = count($sorted_by_count);
            for( $a = 0; $a < $c; $a++ )
            {
                for( $b = $a+1; $b < $c; $b++ )
                {
                    if( $sorted_by_count[ $a ]['count'] < $sorted_by_count[ $b ]['count'] )
                    {
                        $z = $sorted_by_count[ $a ];
                        $sorted_by_count[ $a ] = $sorted_by_count[ $b ];
                        $sorted_by_count[ $b ] = $z;
                    }
                }
            }

            
            
    //        $n = 0;
            //4. Выбираем топ Н штук
            $pairs = [];
            $w = count($sorted_by_count);
            $f = 1;
            for( $n = 0; $n < $w; $n++ )
            {
                $rates = [];
                //Для каждой пары: 
                //6. В новый массив сохраняем: Расчет отношение Валюта/Дебит и количество таких заявок (сохраняем ссылку на оригинал)
                $sorted_rates = [];
                $right_ps_id = $sorted_by_count[ $n ]['ps'];
                
                foreach( $current_group[ $right_ps_id ] as $o )
                {
                    $rt = round($o->buyer_amount_down/$o->seller_amount, 6);
                    $rates[] = ['rate' => $rt, 'order' => $o, 'd' => "$o->buyer_amount_down/$o->seller_amount" ];
                    $sorted_rates[$rt] = $rt;
                }

                //7. Сортируем массив по возрастанию отношения, не учитываем количество
                $c = count($rates);
                for( $a = 0; $a < $c; $a++ )
                {
                    for( $b = $a+1; $b < $c; $b++ )
                    {
                        if( $rates[ $a ]['rate'] < $rates[ $b ]['rate'] )
                        {
                            $z = $rates[ $a ];
                            $rates[ $a ] = $rates[ $b ];
                            $rates[ $b ] = $z;
                        }
                    }
                }
                
                $cc = round( $sorted_by_count[ $n ]['count'] * 0.1, 0 );
                //обрезаем 10 процентов по краям
                $rates = array_slice($rates, $cc, $c - 2 * $cc);

                $wt_sum = 0;
                $oth_sum = 0;
                foreach( $rates as $k => $r ){
                    $o = $r['order'];
                    $wt_sum += $o->seller_amount;
                    $oth_sum += $o->buyer_amount_down;

//                    echo "$k::{$r['rate']}: {$o->seller_amount}/{$o->buyer_amount_down}<br/>";
                }
                $left_c = Currency_exchange_model::get_ps($left_ps_id);
                $right_currency_id = 0;
                if( strpos($right_ps_id, '-') > 0 )
                {
                    $ps_array = explode('-',$right_ps_id);
                    $right_ps_id = $ps_array[0];
                    $right_currency_id = $ps_array[1];                    
                }
                $right_c = Currency_exchange_model::get_ps($right_ps_id);
                
                if( !empty( $right_currency_id ) ) $right_c->currency_id = $right_currency_id;

                $pairs['left']['human_name'] = $left_c->humen_name;                
                $pairs['left']['currency_id'] = $left_c->currency_id;
                $pairs['left']['payment_system_id'] = $left_ps_id;
                
                $pairs['right']['human_name'] = $right_c->humen_name;
                $pairs['right']['currency_id'] = $right_c->currency_id;
                $pairs['right']['payment_system_id'] = $right_ps_id;
                
                $pairs['wt_sum'] = $wt_sum;
                $pairs['oth_sum'] = $oth_sum;
                $pairs['rate'] = round($oth_sum/$wt_sum,2);
                
                //visa on the first place
                if( $right_ps_id == 115 ) $result[ $left_ps_id ][0] = $pairs;
                else
                    $result[ $left_ps_id ][$f++] = $pairs;
            }
            
            
        }
        foreach( $result as &$r )
            ksort( $r );
        
        #<средний курс по рынку>
        
        $avr_sum = 0;
        foreach( $result as &$r )
        {
            $avr_rates = [];
            foreach( $r as $p )
            {
                $currency_id = $p['right']['currency_id'];
                
                if( !isset( $avr_rates[ $currency_id ] ) )
                {
                    $avr_rates[ $currency_id ]['sum'] = 0;
                    $avr_rates[ $currency_id ]['count'] = 0;
                }
                //первые 5 валют считаются для ср курса по рынку
                if( $avr_rates[ $currency_id ]['count'] > 5 ) continue;
                
                $rate = $p['oth_sum']/$p['wt_sum'];
                //странные курсы по рублям и долларам отсекаем
                if( $currency_id == 840 && $rate > 5 ) continue;
                if( $currency_id == 643 && $rate > 350 ) continue;
                
                $avr_rates[ $currency_id ]['sum'] += $rate;
                $avr_rates[ $currency_id ]['count'] ++;
                
                $avr_sum = $avr_rates[ $currency_id ]['sum'] / $avr_rates[ $currency_id ]['count'];
                $avr_rates[ $currency_id ]['avr_sum'] = $avr_sum;
            }
            
            //сохранение в массив
            $average_data = [];
            foreach( $avr_rates as $currency_id => $v )
                $average_data[$currency_id] = round($v['avr_sum'],6);
            
            $r['average_data'] = $average_data;
        }
        #</средний курс по рынку>
        
        return $result;
    }
    
    //данные для левого модуля
    public function get_left_side_p2p_cur_pairs() 
    {
        $data = [];
        $data['cur_pairs'] = $this->get_last_cur_pairs();
        unset( $data['cur_pairs'][114] );
        
        return $this->load->view('user/accaunt/currency_exchange_statistic/modules/left_side__cur_pairs', $data, TRUE);
    }
    
    //добавление новых валютных пар
    public function add_cur_pairs( $data, $reset_last = 0 )
    {
        if( empty( $data ) ) return false;
        
        if( !empty($reset_last) )$this->db->update( $this->table_pairs_statistic, ['last' => 0] );
        
        $this->db->limit(1)
                 ->insert( $this->table_pairs_statistic, $data );
        
        return $this->db->insert_id();
    }
    
    //крон -- собрать статистику по завершенным сделкам
    public function cron_generate_cur_pairs( $show = 0 )
    {
        $result = $this->generate_cur_pairs();
     
    }
    //записать данные по сделкам
    public function generate_cur_pairs()
    {        
//        echo "get_last_cur_pairs-2";
        
        $res = $this->calc_p2p_statistic();
        
        if( empty( $res ) ) return FALSE;
        
        $reset_last = 1;
        $date = date('Y-m-d H:i:s');
        $currency_id = 0;
        foreach( $res as $payment_system_id => &$r )
        {
            if( !empty( $r[1]['left']['currency_id'] ) ) $currency_id = $r[1]['left']['currency_id'];
            
            $average_data = $r['average_data'];
            $r_data = $r;
            unset($r_data['average_data']);
            
            $data = [
                'date' => $date,
                'last' => 1,
                'update_interval' => $this->update_interval,
                'update_window' => $this->update_window,
                'data' => serialize( $r_data ),
                'average_data' => serialize( $average_data ),
                'payment_system_id' => $payment_system_id,
                'currency_id' => $currency_id,
            ];
            
            $this->add_cur_pairs( $data, $reset_last );

            if( !empty($reset_last) ) $reset_last = 0;
        }        
        return $res;
    }
    
    //получить последние пары валют на дату
    public function get_last_cur_pairs()
    {
        
        $res = $this->db->where( 'last', 1 )
                        ->order_by('id','DESC')                       
                        ->get($this->table_pairs_statistic)
                        ->result();
        
        if( !empty( $res ) ){
//            echo "get_last_cur_pairs-1";
            
            $ret = [];
            foreach( $res as $r)
            {
                $ret[ $r->payment_system_id ] = unserialize($r->data);
                $ret[ $r->payment_system_id ]['average_data'] = unserialize($r->average_data);
            }
            
            return $ret;
        }
        return $this->generate_cur_pairs();
    }
    
    //получение средних курсов по рынку для валют -- среднее арифметическое по валюте
    public function get_last_average_data( $ps_id, $cur_id )
    {
        
        if( empty( $ps_id ) || empty( $cur_id ) ) return FALSE;
        
        $res = $this->db->where( 'last', 1 )
                        ->where('payment_system_id',$ps_id)                        
                        ->order_by('id','DESC')   
                        ->limit(1)
                        ->get($this->table_pairs_statistic)
                        ->row();
        if( empty( $res ) ) $res = $this->generate_cur_pairs();
        
        if( !empty( $res ) )
        {        
            $average_data = unserialize($res->average_data);

            if( !empty($average_data) && isset( $average_data[$cur_id] ) ) return $average_data[$cur_id];
        }
        
        $results = $this->db->where( 'last', 0 )
                            ->where('payment_system_id',$ps_id)                        
                            ->order_by('id','DESC')   
                            ->limit(20)
                            ->get($this->table_pairs_statistic)
                            ->result();
        
        foreach( $results as $res  )
        {
            $average_data = unserialize($res->average_data);
            
            if( !empty($average_data) && isset( $average_data[$cur_id] ) ) return $average_data[$cur_id];
        }
        
        return FALSE;
        
    }
    //получить список курсов относительно USD
    public function get_last_all_average_data()
    {
        
        $results = $this->db
                ->where( 'last', 1 )                        
                        ->order_by('id','DESC')   
                        ->limit(20)
                        ->get($this->table_pairs_statistic)
                        ->result();
        
        if( empty( $results ) ) $results = $this->generate_cur_pairs();
        
        $this->load->model('currency_exchange_model','currency_exchange');
        $currencies = $this->currency_exchange->get_all_currencys_key_num();
        
        
        if( !empty( $results ) )
        {        
            foreach( $results as &$res  )
            {
                $res->ps_name = Currency_exchange_model::get_ps( $res->payment_system_id )->machine_name;
                
                $average_data = unserialize($res->average_data);
                $res->average_data = [];
                foreach( $average_data as $c => $r )
                {
                    if( $c != 840 ) continue;
                    $res->average_data[ $c ] = [ 'currency_name' => $currencies[ $c ]->code, 'rate' => $r ];
                }
            }
            return $results;            
        }
        
        $results = $this->db->where( 'last', 0 )                            
                            ->order_by('id','DESC')   
                            ->limit(20)
                            ->get($this->table_pairs_statistic)
                            ->result();
        
        if( empty( $results ) ) return FALSE;
        
        foreach( $results as &$res  )
        {
            $res->ps_name = Currency_exchange_model::get_ps( $res->payment_system_id )->humen_name;
            $average_data = unserialize($res->average_data);
            $res->average_data = [];
            foreach( $average_data as $c => $r )
            {
                if( $c != 840 ) continue;
                $res->average_data[ $c ] = [ 'currency_name' => $currencies[ $c ]->code, 'rate' => $r ];
            }
        }
        
        
        return $results;
        
    }
}

