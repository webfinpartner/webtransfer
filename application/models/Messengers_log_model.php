<?php

class Messengers_log_model extends CI_Model {
    
    static $last_error;

    const MAX_SENDTIME_IN_SEC = 30;             //  максимальное время отправки, по истечению которого сообщения ставится в статус  STATUS_OK_SENDTIME_WARNING
    const MIN_TIME_BETWEEN_SENDS_IN_SEC = 10;    // минимальное допустимое время между двумя сообщения одного пользоватя (если оно меньше, то нужно писать в лог)    
    
    /*const STATUS_OK = 10;
    const STATUS_OK_QUEUE = 20;
    const STATUS_OK_LONGTIME_WARNING = 30;
    const STATUS_FAIL = 40;
    
    
    const MESSENGER_SMS = 'SMS';
    const MESSENGER_VIBER = 'VIBER';
    const MESSENGER_WHATSAPP = 'WHATSAPP';
    const MESSENGER_EMAIL = 'EMAIL';
    const MESSENGER_WTAPP = 'WTAPP';
    const MESSENGER_TELEGRAM = 'TELEGRAM';
    */
    
    const SERVICE_SMS_PROSTOR = 'prostor';
    const SERVICE_SMS_EPOCHTA = 'epochta';
    const SERVICE_SMS_SMSRU = 'Smsru';
    const SERVICE_SMS_CLICATEL = 'Clickatel';
    const SERVICE_SMS_NEXMO = 'Nexmo';
    const SERVICE_SMS_TWILIO = 'Twilio';
    
    

    
    
    const MESSAGE_TYPE_EMAIL    = 101;
    const MESSAGE_TYPE_SMS      = 102;
    const MESSAGE_TYPE_WT_AUTH  = 103;
    
    const MESSAGE_TYPE_WHATSAPP = 201;
    const MESSAGE_TYPE_VIBER    = 202;
    
    const MESSAGE_STATUS_SUCCESS = 101;
    const MESSAGE_STATUS_SUCCESS_CHECK = 102;
    const MESSAGE_STATUS_PENDING = 201;
    const MESSAGE_STATUS_FAIL    = 301;
    const MESSAGE_STATUS_FAIL_CHECK    = 302;
    const MESSAGE_STATUS_ANOTHER = 401;
    const MESSAGE_STATUS_ANOTHER_CHECK = 402;    
    
    
    
    
    
    private $repump_statistic = FALSE;
    
    

    
    
    
    
    function __construct()
    {
        parent::__construct();
        

    }
    
    /**
    * Добавляем сообщение в лог
    * 
    * @param mixed $messenger_name
    * @param mixed $send_service_name
    * @param mixed $text
    * @param mixed $target_addr
    * @param mixed $status
    * @param mixed $status_text
    */
     public function add($message_type, $send_service_name, $text, $target_addr, $send_time, $status, $status_text = NULL  ){
        
        $this->load->model( 'phone_model', 'phone' );                                                                               
         if (  empty($message_type) ||empty($text) ||empty($target_addr) ||empty($status) )
            return FALSE;
            
         
         $this->load->model( 'accaunt_model', 'accaunt' );
         $user_id = $this->accaunt->get_user_id();
        
         if ( empty($user_id) )           
            return FALSE;
         
         // хэш страницы должно быть в POSTe    
         $page_hash = $this->input->post('page_hash');            
         $page_link_hash = '';
         if(empty($page_hash))
            $page_hash = 'NOT_FOUND';
         else {
                     ;
         }   
            
            
                  
        /*          
         if ($status==self::STATUS_OK && $send_time>self::MAX_SENDTIME_IN_SEC)
             $status = self::STATUS_OK_LONGTIME_WARNING;
        */                                             
         
         
         $data = array();
         $data['user_id'] = $user_id;
         $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
         $data['added_datetime'] = date("Y-m-d H:i:s");
         $data['confirm_datetime'] = '0000-00-00 00:00:00';
         $data['type_id'] = $message_type;
         $data['service_name'] = $send_service_name;
         $data['recipient'] = $this->code->code($target_addr);
         $data['message'] = $text;
         $data['page_link_hash'] = $page_link_hash;
         $data['page_hash'] = $page_hash;
         $data['message_id_in_service'] = 0;
         $data['service_balance'] = 0;
         $data['cost'] = 0;
         $data['delivery_status'] = $status;
         $data['status_error_service_result'] = $status_text;
         
         
         
         
         
        // var_dump($data);
         
         // получим оператора и страну
         $phoneDetails = $this->phone->getPhoneWithCode($user_id);
         if ( is_array($phoneDetails) ){
             
             $data['short_country_name'] = $phoneDetails['short_name'];
             
             if ( $message_type == self::MESSAGE_TYPE_EMAIL){
                  $email_split = explode('@',$target_addr);
                  if ( count($email_split) > 1)
                    $data['target_operator'] = $email_split[1];
                  else 
                    $data['target_operator'] = $target_addr;                  
             } else {
                $operatorDetails =  $this->phone->getOperatorByPhone($phoneDetails['code'],$phoneDetails['phone'] );
                if ( !empty($operatorDetails)  ){
                    $data['target_operator'] = $operatorDetails['o_name'];                 
                    }
                    
                    
                    
             
             }
         }

         
        try
        {
            $this->db->trans_start();
            {
                    $this->db->insert( 'send_messages_history', $data );
            }
            $this->db->trans_complete();
        } catch( Exception $e )
        {
            self::$last_error = $e;
            return FALSE;
        }         
        
        return TRUE;
         
         
     }      
    
    
    /**
    * Добавляем сообщение в лог
    * 
    * @param mixed $messenger_name
    * @param mixed $send_service_name
    * @param mixed $text
    * @param mixed $target_addr
    * @param mixed $status
    * @param mixed $status_text
    */
   /*  public function add($messenger_name, $send_service_name, $text, $target_addr, $send_time, $status, $status_text = NULL  ){
        
        $this->load->model( 'phone_model', 'phone' );                                                                               
         if (  empty($messenger_name) ||empty($text) ||empty($target_addr) ||empty($status) )
            return FALSE;
            
         
         $this->load->model( 'accaunt_model', 'accaunt' );
         $user_id = $this->accaunt->get_user_id();
        
         if ( empty($user_id) )           
            return FALSE;
         
         // хэш страницы должно быть в POSTe    
         $page_hash = $this->input->post('page_hash');            
         if(empty($page_hash))
            $page_hash = 'NOT_FOUND';
         
         $time_left = $this->db->select("NOW()-dttm as time_left")
                  ->where(array('page_hash'=>$page_hash, 'user_id'=>$user_id)) 
                  ->order_by('id','desc')
                  ->limit(1)
                  ->get('messengers_log')
                  ->row()->time_left;
                  
                  
         if ($status==self::STATUS_OK && $send_time>self::MAX_SENDTIME_IN_SEC)
             $status = self::STATUS_OK_LONGTIME_WARNING;
                                                     
         
         
         $data = array();
         $data['messenger_name'] = $messenger_name;
         $data['send_service_name'] = $send_service_name;
         $data['message_text'] = $text;
         
         //зашифруем данные
         $data['target_addr'] = $this->code->code($target_addr);
         
         $data['user_id'] = $user_id;
         $data['page_hash'] = $page_hash;
         $data['status'] = $status;
         $data['send_time'] = $send_time;
         $data['last_time'] = $time_left;
         
        // var_dump($data);
         
         // получим оператора и страну
         $phoneDetails = $this->phone->getPhoneWithCode($user_id);
         if ( is_array($phoneDetails) ){
             
             $data['country_short_name'] = $phoneDetails['short_name'];
             
             if ( $messenger_name == self::MESSENGER_EMAIL){
                  $email_split = explode('@',$target_addr);
                  if ( count($email_split) > 1)
                    $data['target_operator'] = $email_split[1];
                  else 
                    $data['target_operator'] = $target_addr;                  
             } else {
                $operatorDetails =  $this->phone->getOperatorByPhone($phoneDetails['code'],$phoneDetails['phone'] );
                if ( !empty($operatorDetails)  ){
                    $data['target_operator'] = $operatorDetails['o_name'];                 
                    }
                    
                    
                    
             
             }
         }

         
        try
        {
            $this->db->trans_start();
            {
                    $this->db->insert( 'messengers_log', $data );
            }
            $this->db->trans_complete();
        } catch( Exception $e )
        {
            self::$last_error = $e;
            return FALSE;
        }         
        
        return TRUE;
         
         
     }  */
     
     /**
      * Заливка в исходные данные оператора и страну
      * @return type
      */
     private function fill_country_and_operator(){
         $this->load->model('Phone_model', 'phone');
         $rows = $this->db->where('short_country_name',NULL)->or_where('target_operator', NULL)->get('send_messages_history')->result();
         
         
         if (!empty($rows)){
             foreach( $rows as $row ){
                    // получим оператора и страну
                    $phoneDetails = $this->phone->getPhoneWithCode($row->user_id);
                    $upd_data = array();
                    if ( is_array($phoneDetails) ){

                        if ( empty($row->short_country_name) && !empty($phoneDetails['short_name']))
                            $upd_data['short_country_name'] = $phoneDetails['short_name'];
                        
                        if ( empty($row->target_operator)){
                            if ( $this->row->type_id == self::MESSAGE_TYPE_EMAIL){
                                 $email_split = explode('@',$row->recipient);
                                 if ( count($email_split) > 1)
                                    $upd_data['target_operator'] = $email_split[1];
                                 else 
                                   $upd_data['target_operator'] = $row->recipient;                  
                            } else {
                               $operatorDetails =  $this->phone->getOperatorByPhone($phoneDetails['code'],$phoneDetails['code'].$phoneDetails['phone'] );
                               if ( !empty($operatorDetails)  ){
                                   $upd_data['target_operator'] = $operatorDetails['o_name'];                 
                                   }
                            }
                        }
                        // обновим запись
                        if (!empty($upd_data)){
                            $this->db->where('id', $row->id)->update('send_messages_history', $upd_data);
                            echo $this->db->last_query();
                            flush();
                        }
                        
                        
                    }
             }
         }
     }
     
      /**
      * Заливка данные для ежедневной статистики из исходного файла
      * @return type
      */
     private function fill_data_1day(){
         
                
                //$this->fill_country_and_operator();
                $this->db->query('DELETE FROM message_send_1day_statistic');
             
           
                 
                 $first_date = $this->db->order_by('id','asc')->limit(1)->get('send_messages_history')->row('added_datetime');
                 echo $first_date;
                 $now_date = date_create(); 
                 $min_date = date_create_from_format('Y-m-d H:i:s',$first_date);
                 date_time_set($min_date,0,0,1);
                 
                 $max_date =  $now_date;
                 
                 $cur_date = $min_date;
                 $i = 0;
                 $cur_timestamp = date_timestamp_get($min_date);
                 echo '['.$cur_timestamp.']';
                 //$cur_timestamp = $cur_timestamp-$cur_timestamp%86400;
                 
                 for (  ;$cur_timestamp <= date_timestamp_get($max_date)-86400; $cur_timestamp+=86400  ){
                     //echo $cur_timestamp;
                     $i++;
                     $query  = $this->db->query('SELECT type_id, service_name, delivery_status, COUNT(*) as cnt,  MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                       FROM send_messages_history WHERE added_datetime BETWEEN ? AND ? GROUP BY type_id, service_name,delivery_status', array(
                                  date("Y-m-d H:i:s", $cur_timestamp), date("Y-m-d H:i:s", $cur_timestamp+86400-1)
                       ) );                 
                       var_dump($this->db->last_query());
                       echo ' - '.$query->num_rows().' - ';
                       //echo $cur_timestamp;
                       $this->add_1day_stat($query,date("Y-m-d H:i:s", $cur_timestamp+86400));                       
                 }
     }
     
      /**
      * Заливка данные для 5минутной статистики из исходного файла
      * @return type
      */
     private function fill_data_5min(){
         
                
                //$this->fill_country_and_operator();
                $this->db->query('DELETE FROM message_send_5min_statistic');
             
           
                 
                 $first_date = $this->db->order_by('id','asc')->limit(1)->get('send_messages_history')->row('added_datetime');
                 echo $first_date;
                 $now_date = date_create(); 
                 $min_date =  date_create_from_format('Y-m-d H:i:s',$first_date);
                 
                 $max_date =  $now_date;
                 
                 $cur_date = $min_date;
                 $i = 0;
                 $cur_timestamp = date_timestamp_get($min_date);
                 $cur_timestamp = $cur_timestamp-$cur_timestamp%300;
                 
                 for (  ;$cur_timestamp <= date_timestamp_get($max_date)-300; $cur_timestamp+=300  ){
                     //echo $cur_timestamp;
                     $i++;
                     $query  = $this->db->query('SELECT type_id, service_name, delivery_status, COUNT(*) as cnt,  MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                       FROM send_messages_history WHERE added_datetime BETWEEN ? AND ? GROUP BY type_id, service_name,delivery_status', array(
                                  date("Y-m-d H:i:s", $cur_timestamp), date("Y-m-d H:i:s", $cur_timestamp+300-1)
                       ) );                 
                       var_dump($this->db->last_query());
                       echo ' - '.$query->num_rows().' - ';
                       //echo $cur_timestamp;
                       $this->add_5min_stat($query,date("Y-m-d H:i:s", $cur_timestamp+300));                       
                 }
     }
     

     /**
      * Заливка данные для 1часовой статистики из исходного файла
      * @return type
      */
     private function fill_data_1hour(){
                //$this->fill_country_and_operator();
                $this->db->query('DELETE FROM message_send_1hour_statistic');
             
           
                 
                 $first_date = $this->db->order_by('id','asc')->limit(1)->get('send_messages_history')->row('added_datetime');
                 echo $first_date;
                 $now_date = date_create(); 
                 $min_date =  date_create_from_format('Y-m-d H:i:s',$first_date);
                 
                 $max_date =  $now_date;
                 
                 $cur_date = $min_date;
                 $i = 0;
                 $cur_timestamp = date_timestamp_get($min_date);
                 $cur_timestamp = $cur_timestamp-$cur_timestamp%3600;
                 
                 for (  ;$cur_timestamp <= date_timestamp_get($max_date)-3600; $cur_timestamp+=3600  ){
                     $i++;
                     $query  = $this->db->query('SELECT type_id, service_name, IFNULL(short_country_name,"") as short_country_name, IFNULL(target_operator,"") as target_operator, delivery_status, COUNT(*) as cnt,  MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                        FROM send_messages_history WHERE added_datetime BETWEEN ? AND ?  GROUP BY type_id, service_name, IFNULL(short_country_name,""), IFNULL(target_operator,""), delivery_status', array(
                                  date("Y-m-d H:i:s", $cur_timestamp), date("Y-m-d H:i:s", $cur_timestamp+3600-1)
                       ) );                 
                       var_dump($this->db->last_query());
                       echo ' - '.$query->num_rows().' - ';
                       //echo $cur_timestamp;
                       $this->add_1hour_stat($query,date("Y-m-d H:i:s", $cur_timestamp+3600));                       
                 }
     }     
     
     /**
     * Заливает результаты запроса в таблицу 5минутной статистики
     * 
     * @param mixed $query
     * @param mixed $create_dttm
     */
     private function add_5min_stat( $query, $create_dttm  ){
         
         
            if (empty($query))
                return;         

            if ($query->num_rows() == 0)
                return;
                
                
            
         
             $store = array();
             
             foreach ($query->result() as $row)
            {
                 $store[ $row->type_id ][ $row->service_name ] = array();
                 
                 switch( $row->delivery_status ){
    
                    case self::MESSAGE_STATUS_SUCCESS:
                        $store[ $row->type_id ][ $row->service_name ]['status_success_cnt'] = $row->cnt;
                    break;
                    case self::MESSAGE_STATUS_SUCCESS_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_success_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_PENDING:
                        $store[ $row->type_id ][ $row->service_name ]['status_pending_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL:
                        $store[ $row->type_id ][ $row->service_name ]['status_fail_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_fail_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER:
                        $store[ $row->type_id ][ $row->service_name ]['status_another_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_another_check_cnt'] = $row->cnt;
                    break;                    
                 }
                 
                 //$store[ $row->type_id ][ $row->service_name ]['avg_send_time_in_sec'] = $row->avg_send_time_in_sec;
                 $store[ $row->type_id ][ $row->service_name ]['balance'] = ($row->balance==NULL)?0:$row->balance;
                 $store[ $row->type_id ][ $row->service_name ]['cost'] = $row->cost;
                

            }
            
            //vred( $store);
            //echo '<pre>'.print_r( $store ).'</pre>';
            
            
            foreach( $store as $messenger_type_id=>$services )
                foreach( $services as $service_name=>$row )
            {
                $data = array();
                $data['messenger_type_id'] = $messenger_type_id;
                $data['send_service_name'] = $service_name;
                $data['create_dttm'] = $create_dttm;
                $data  = array_merge($data, $row);
                $this->db->insert( 'message_send_5min_statistic', $data );  
                echo '<pre>'.print_r( $data ).'</pre>';

            }         
         
     }
     
     /**
     * Заливает результаты запроса в таблицу 60минутной статистики
     * 
     * @param mixed $query
     * @param mixed $create_dttm
     */
     private function add_1hour_stat( $query, $create_dttm  ){
         
            if (empty($query))
                return;         

            if ($query->num_rows() == 0)
                return;

             $store = array();
             foreach ($query->result() as $row)
            {
                 $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator] = array();
                 
                 switch( $row->delivery_status ){
    
                    case self::MESSAGE_STATUS_SUCCESS:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_success_cnt'] = $row->cnt;
                    break;
                    case self::MESSAGE_STATUS_SUCCESS_CHECK:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_success_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_PENDING:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_pending_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_fail_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL_CHECK:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_fail_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_another_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER_CHECK:
                        $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['status_another_check_cnt'] = $row->cnt;
                    break;                    
                 }
                 
                 //$store[ $row->type_id ][ $row->service_name ]['avg_send_time_in_sec'] = $row->avg_send_time_in_sec;
                 $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['balance'] = ($row->balance==NULL)?0:$row->balance;
                 $store[ $row->type_id ][ $row->service_name ][$row->short_country_name][$row->target_operator]['cost'] = $row->cost;
                

            }
            
            //vred( $store);
            //echo '<pre>'.print_r( $store ).'</pre>';
            
            foreach( $store as $messenger_type_id=>$services )
                foreach( $services as $service_name=>$countries )
                    foreach( $countries as $counry_name=>$operators )
                        foreach( $operators as $operator_name=>$row )
            {
                $data = array();
                $data['messenger_type_id'] = $messenger_type_id;
                $data['send_service_name'] = $service_name;
                $data['country_short_name'] = $counry_name;
                $data['operator_name'] = $operator_name;


                $data['create_dttm'] = $create_dttm;
                $data  = array_merge($data, $row);
                $this->db->insert( 'message_send_1hour_statistic', $data );  
                echo '<pre>'.print_r( $data ).'</pre>';

            }         
         
     }   
     
     
     /**
     * Заливает результаты запроса в таблицу ежедневной статистики
     * 
     * @param mixed $query
     * @param mixed $create_dttm
     */
     private function add_1day_stat( $query, $create_dttm  ){
         
         
            if (empty($query))
                return;         

            if ($query->num_rows() == 0)
                return;
                
                
            
         
             $store = array();
             
             foreach ($query->result() as $row)
            {
                 $store[ $row->type_id ][ $row->service_name ] = array();
                 
                 switch( $row->delivery_status ){
    
                    case self::MESSAGE_STATUS_SUCCESS:
                        $store[ $row->type_id ][ $row->service_name ]['status_success_cnt'] = $row->cnt;
                    break;
                    case self::MESSAGE_STATUS_SUCCESS_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_success_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_PENDING:
                        $store[ $row->type_id ][ $row->service_name ]['status_pending_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL:
                        $store[ $row->type_id ][ $row->service_name ]['status_fail_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_FAIL_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_fail_check_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER:
                        $store[ $row->type_id ][ $row->service_name ]['status_another_cnt'] = $row->cnt;
                    break;                    
                    case self::MESSAGE_STATUS_ANOTHER_CHECK:
                        $store[ $row->type_id ][ $row->service_name ]['status_another_check_cnt'] = $row->cnt;
                    break;                    
                 }
                 
                 //$store[ $row->type_id ][ $row->service_name ]['avg_send_time_in_sec'] = $row->avg_send_time_in_sec;
                 $store[ $row->type_id ][ $row->service_name ]['balance'] = ($row->balance==NULL)?0:$row->balance;
                 $store[ $row->type_id ][ $row->service_name ]['cost'] = $row->cost;
                

            }
            
            //vred( $store);
            //echo '<pre>'.print_r( $store ).'</pre>';
            
            
            foreach( $store as $messenger_type_id=>$services )
                foreach( $services as $service_name=>$row )
            {
                $data = array();
                $data['messenger_type_id'] = $messenger_type_id;
                $data['send_service_name'] = $service_name;
                $data['create_dttm'] = $create_dttm;
                $data  = array_merge($data, $row);
                $this->db->insert( 'message_send_1day_statistic', $data );  
                echo '<pre>'.print_r( $data ).'</pre>';

            }         
         
     }     
     
     
    
   
     /**
     * Статистика каждые 5 минут для крона
     * 
     */
     public function generate_5_min_statistic(){
         
            /*$this->load->model('Phone_model', 'phone');
            $operatorDetails =  $this->phone->getOperatorByPhone('7','79053054862' );
            var_dump($operatorDetails);
            return;         
             */
         
            // узнаем сколько записей в таблице
            $cnt = $this->db->count_all('message_send_5min_statistic');
            
            // если нету ни одной записи в статистике то будем разбивать по 5 минут
             if ( $cnt == 0 || $this->repump_statistic == TRUE){
                $this->fill_data_5min();
                return;
             }
         
             // храним лог сутки
             $this->db->query('DELETE FROM message_send_5min_statistic WHERE create_dttm < NOW() - INTERVAL 1 DAY');
             
             $create_dttm = new DateTime();
             $dateStart = date_sub( $create_dttm, date_interval_create_from_date_string('5 minute'));
             
             $query  = $this->db->query('SELECT type_id, service_name, delivery_status, COUNT(*) as cnt, MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                    FROM send_messages_history WHERE added_datetime >= ? GROUP BY type_id, service_name,delivery_status', array(date_format($dateStart,'Y-m-d H:i:s')));
             echo $this->db->last_query();
              $this->add_5min_stat($query,date_format($create_dttm,'Y-m-d H:i:s'));
         

     }
    
     /**
     * Статистика каждый час для крона
     * 
     */
     public function generate_1_hour_statistic(){
         
            // узнаем сколько записей в таблице
            $cnt = $this->db->count_all('message_send_1hour_statistic');

             // храним лог месяц
             $this->db->query('DELETE FROM message_send_1hour_statistic WHERE create_dttm < NOW() - INTERVAL 1 MONTH');
             
             // если нету ни одной записи в статистике то будем разбивать по 60 минут
             if ( $cnt == 0 || $this->repump_statistic == TRUE){
                $this->fill_data_1hour();
                return;
             }
         
             $create_dttm = new DateTime();
             $dateStart = date_sub( $create_dttm, date_interval_create_from_date_string('1 hour'));
             
             $query  = $this->db->query('SELECT type_id, service_name, short_country_name, target_operator, delivery_status, COUNT(*) as cnt,  MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                    FROM send_messages_history WHERE added_datetime >= ? GROUP BY type_id, service_name, short_country_name, target_operator,delivery_status', array(date_format($dateStart,'Y-m-d H:i:s')));
             //echo $this->db->last_query();
             $this->add_1hour_stat($query, date_format($create_dttm,'Y-m-d H:i:s'));
     }  
     
     
     /**
     * Статистика каждый день для крона
     * 
     */
     public function generate_1_day_statistic(){
         
            /*$this->load->model('Phone_model', 'phone');
            $operatorDetails =  $this->phone->getOperatorByPhone('7','79053054862' );
            var_dump($operatorDetails);
            return;         
             */
         
            // узнаем сколько записей в таблице
            $cnt = $this->db->count_all('message_send_1day_statistic');
            
            // если нету ни одной записи в статистике то будем разбивать по 5 минут
             if ( $cnt == 0 || $this->repump_statistic == TRUE){
                $this->fill_data_1day();
                return;
             }
         
             // храним 60 дней
             $this->db->query('DELETE FROM message_send_1day_statistic WHERE create_dttm < NOW() - INTERVAL 60 DAY');
             
             $create_dttm = new DateTime();
             $dateStart = date_sub( $create_dttm, date_interval_create_from_date_string('24 hour'));
             
             $query  = $this->db->query('SELECT type_id, service_name, delivery_status, COUNT(*) as cnt, MIN(NULLIF(service_balance, 0)) as balance, sum(cost) as cost
                    FROM send_messages_history WHERE added_datetime >= ? GROUP BY type_id, service_name,delivery_status', array(date_format($dateStart,'Y-m-d H:i:s')));
             echo $this->db->last_query();
              $this->add_1day_stat($query,date_format($create_dttm,'Y-m-d H:i:s'));
         

     }
     
     
     
     /**
     * Возвращает массив данных для 5м графика
     * object $params
     */
     public function get_5min_stat_data($params)
     {
            $this->load->model('Messenger_model','messenger');
            
            $result = array();
            $messenger = 'ALL';
            $messenger_service = 'ALL';
            $dateFrom = NULL;
            $dateTo = NULL;
            
            
            if ( !empty($params) ){
                if ( isset($params->messenger) )
                    $messenger =  $params->messenger;
                if (isset($params->service))
                    $messenger_service = $params->service;           
                if (isset($params->from))
                    $dateFrom = date_create_from_format('d.m.Y H:i',$params->from);           
                if (isset($params->to))
                    $dateTo = date_create_from_format('d.m.Y H:i',$params->to);           
                
            }
            
             
            
           
           $rows = $this->db->select(array('SUM(status_success_cnt) as status_success_cnt',
                                      'SUM(status_success_check_cnt) as status_success_check_cnt',
                                      'SUM(status_pending_cnt) as status_pending_cnt',
                                      'SUM(status_fail_cnt) as status_fail_cnt',
                                      'SUM(status_fail_check_cnt) as status_fail_check_cnt',
                                      'SUM(status_another_cnt) as status_another_cnt',
                                      'SUM(status_another_check_cnt) as status_another_check_cnt',
                                      'MAX(avg_send_time_in_sec) as max_send_time',
                                      'MIN(avg_send_time_in_sec) as min_send_time',
                                      'create_dttm as date'))
                            ->group_by("create_dttm");
            
           if ( $messenger != 'ALL' )                            
              $rows = $rows->where('messenger_type_id',$messenger);
           if ( $messenger_service != 'ALL' )                            
              $rows = $rows->where('send_service_name',$messenger_service);                            
           if ( !empty($dateFrom) )                            
              $rows = $rows->where('create_dttm >=',  date_format($dateFrom,'Y-m-d H:i:00'));                            
           if ( !empty($dateTo) )                            
              $rows = $rows->where('create_dttm <=',date_format($dateTo,'Y-m-d H:i:59'));                            
           
           $rows = $rows->get('message_send_5min_statistic')
                            ->result();
           
             
            //echo $type,' '.$this->db->last_query();
            if ( empty($rows) )
                return FALSE;
           
           //random [for test]     
            foreach ( $rows as &$r){
                $r->time = substr($r->date,11);
                //$r->success_send_cnt = rand(50,100);
                //$r->success_send_longtime_cnt = rand(1,40);
                //$r->success_send_queued_cnt = rand(10,30);
                //$r->fail_send_cnt = rand(1,10);

            }
           // print_r($result);    
            
            return $rows;
     }
    
    
     /**
     * Возвращает массив данных для 1ч графика
     * object $params
     */
     public function get_1hour_stat_data($params)
     {
            $this->load->model('Messenger_model','messenger');
            
            $result = array();
            $messenger = 'ALL';
            $messenger_service = 'ALL';
            $dateFrom = NULL;
            $dateTo = NULL;            
            
            
            if ( !empty($params) ){
                if ( isset($params->messenger) )
                    $messenger =  $params->messenger;
                if (isset($params->service))
                    $messenger_service = $params->service;           
                if (isset($params->from))
                    $dateFrom = date_create_from_format('d.m.Y H:i',$params->from);           
                if (isset($params->to))
                    $dateTo = date_create_from_format('d.m.Y H:i',$params->to);                    
            }
            
             
            
           
           $rows = $this->db->select(array('SUM(status_success_cnt) as status_success_cnt',
                                      'SUM(status_success_check_cnt) as status_success_check_cnt',
                                      'SUM(status_pending_cnt) as status_pending_cnt',
                                      'SUM(status_fail_cnt) as status_fail_cnt',
                                      'SUM(status_fail_check_cnt) as status_fail_check_cnt',
                                      'SUM(status_another_cnt) as status_another_cnt',
                                      'SUM(status_another_check_cnt) as status_another_check_cnt',
                                      'MAX(avg_send_time_in_sec) as max_send_time',
                                      'MIN(avg_send_time_in_sec) as min_send_time',
                                      'create_dttm as date'))
                            ->group_by("create_dttm");
            
           if ( $messenger != 'ALL' )                            
              $rows = $rows->where('messenger_type_id',$messenger);
           if ( $messenger_service != 'ALL' )                            
              $rows = $rows->where('send_service_name',$messenger_service);                            
           if ( !empty($dateFrom) )                            
              $rows = $rows->where('create_dttm >=',  date_format($dateFrom,'Y-m-d H:i:00'));                            
           if ( !empty($dateTo) )                            
              $rows = $rows->where('create_dttm <=',date_format($dateTo,'Y-m-d H:i:59'));            
           $rows = $rows->get('message_send_1hour_statistic')
                            ->result();
           
             
            //echo $type,' '.$this->db->last_query();
            if ( empty($rows) )
                return FALSE;
           
           //random [for test]     
            foreach ( $rows as &$r){
                $r->time = substr($r->date,11);
                //$r->success_send_cnt = rand(50,100);
                //$r->success_send_longtime_cnt = rand(1,40);
                //$r->success_send_queued_cnt = rand(10,30);
                //$r->fail_send_cnt = rand(1,10);

            }
           // print_r($result);    
            
            return $rows;
     }    
     
     /**
      * Данные для статистики баланса по СМС
      * @param object $params
      * @param array $avail_services OUT
      * @return boolean
      */
     public function get_sms_balance_stat_data($params, &$avail_services, &$daily)
     {
            $this->load->model('Messenger_model','messenger');
            
            $result = array();
            $messenger_service = 'ALL';
            $dateFrom = NULL;
            $dateTo = NULL;            
            $daily = true;
            
            
            if ( !empty($params) ){
                if (isset($params->daily))
                    $daily = $params->daily;                
                
                if (isset($params->service))
                    $messenger_service = $params->service;           
                
                if (isset($params->from))
                    $dateFrom = date_create_from_format('d.m.Y H:i',$params->from);           
                
                if (isset($params->to))
                    $dateTo = date_create_from_format('d.m.Y H:i',$params->to);                    

            }
            
           
           if ( $daily )
           {
                $rows = $this->db->select(array('SUM(cost) as balance','DATE_FORMAT(create_dttm,"%Y-%m-%d") as date', 'send_service_name'))
                                 ->where('send_service_name IS NOT NULL',NULL, FALSE)
                                 ->where('send_service_name !=','');


                if ( $messenger_service != 'ALL' )                            
                   $rows = $rows->where('send_service_name',$messenger_service);                            
                if ( !empty($dateFrom) )                            
                   $rows = $rows->where('create_dttm >=',  date_format($dateFrom,'Y-m-d 00:00:00'));                            
                if ( !empty($dateTo) )                            
                   $rows = $rows->where('create_dttm <=',date_format($dateTo,'Y-m-d 23:59:59'));            
                $rows = $rows->group_by('DATE_FORMAT(create_dttm,"%Y-%m-%d"), send_service_name');
                $rows = $rows->get('message_send_1day_statistic')
                                 ->result();
              
           } else {
            
                $rows = $this->db->select(array('MIN(NULLIF(balance,0)) as balance ','DATE_FORMAT(create_dttm,"%Y-%m-%d %H:00:00") as date', 'send_service_name'))
                                 ->where('send_service_name IS NOT NULL',NULL, FALSE)
                                 ->where('send_service_name !=','');


                if ( $messenger_service != 'ALL' )                            
                   $rows = $rows->where('send_service_name',$messenger_service);                            
                if ( !empty($dateFrom) )                            
                   $rows = $rows->where('create_dttm >=',  date_format($dateFrom,'Y-m-d H:i:00'));                            
                if ( !empty($dateTo) )                            
                   $rows = $rows->where('create_dttm <=',date_format($dateTo,'Y-m-d H:i:59'));            
                $rows = $rows->group_by('DATE_FORMAT(create_dttm,"%Y-%m-%d %H"), send_service_name');
                $rows = $rows->get('message_send_5min_statistic')
                                 ->result();
           }
           
             
            //echo $type,' '.$this->db->last_query();
            if ( empty($rows) )
                return FALSE;
           
            $avail_services = array();
            $temp_array = array();
            $result = array();
            foreach ( $rows as $r){
                $temp_array[$r->date][strtolower($r->send_service_name)] = $r->balance;
                $avail_services[$r->send_service_name] = $r->send_service_name;
            }

            foreach( $temp_array as $date=>$services){
                    $time = ($daily)?'00:00:00':substr($date,11);
                    $result[] = array_merge( array('date' => $date, 'time'=>$time), $services);
            }
            
            //print_r($result);    
            
            return $result;
     }    
     
    /**
     * Данные для общей статистики по СМС
     * @param type $params
     * @return boolean|Array
     */ 
    public function get_sms_total_stat_data($params)
     {
            $this->load->model('Messenger_model','messenger');
            
            $result = array();
            $messenger_services = 'ALL';
            $dateFrom = NULL;
            $dateTo = NULL;            
            
            
            if ( !empty($params) ){
                if (isset($params->service) && !empty($params->service) && $params->service != 'ALL')
                    $messenger_services = explode(',',$params->service);           
                if (isset($params->from))
                    $dateFrom = date_create_from_format('d.m.Y H:i',$params->from);           
                if (isset($params->to))
                    $dateTo = date_create_from_format('d.m.Y H:i',$params->to);                    
            }
            
             
            
           $rows = $this->db->select(array('SUM(status_success_cnt) + SUM(status_success_check_cnt) + SUM(status_pending_cnt)+SUM(status_fail_cnt)+SUM(status_fail_check_cnt)+SUM(status_another_cnt)+SUM(status_another_check_cnt) as sum',
                'COUNT(*) as cnt','create_dttm as date'))
                            ->where('send_service_name IS NOT NULL',NULL, FALSE)
                            ->where('send_service_name !=','')
                            ->group_by('create_dttm');

            
           if ( is_array($messenger_services) && !empty($messenger_services) )                            
              $rows = $rows->where_in('send_service_name',$messenger_services);                            
           if ( !empty($dateFrom) )                            
              $rows = $rows->where('create_dttm >=',  date_format($dateFrom,'Y-m-d H:i:00'));                            
           if ( !empty($dateTo) )                            
              $rows = $rows->where('create_dttm <=',date_format($dateTo,'Y-m-d H:i:59'));            
           $rows = $rows->get('message_send_1hour_statistic')
                            ->result();
           
             
            //echo $type,' '.$this->db->last_query();
            if ( empty($rows) )
                return FALSE;
            
            
            foreach ( $rows as &$r)
                $r->time = substr($r->date,11);

           
             //print_r($result);    
            
            return $rows;
     }    
     
     
     public function get_countries(){
         
         return array('RUS', 'USA');
         
     }
     
    
}
