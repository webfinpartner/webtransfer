<?php
if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

class System_events_model extends CI_Model{
    
    const EMAIL_ERROR_EVENT = 1; // Ошибки почтового сервера
    const LOW_BALANCE_EVENT  = 2; //Низкий баланс смс-оператора
    
    static $last_error;
    

    function __construct()
    {
        parent::__construct();
    }
    
    
    /*
     * Отправляет сообщения подписанным пользователям
     */
    public function notify($event, $event_type, $data = array())
    {
        //echo "$event $event_type ".print_r($data,true);
        // почистим таблицу истории сообщений
         $this->db->where('send_datetime <', 'NOW() - INTERVAL 24 HOUR', FALSE)->delete('system_events_messages');
         
         
        //получаем параметры event'a
         $event_row = $this->db->where('id', $event)->get('system_events')->row();
         
         if ( empty($event_row))
             return;
         
         $event_params = json_decode($event_row->params);
         if ( $event_params === NULL )
               return;         
         
         
         if ( !property_exists($event_params, $event_type) )
              return;
         
         $event_message = $event_row->human_name;
         $event_text = $event_params->{$event_type};
         
        // находим подписанных пользователей
        $subscribes = $this->db->where('event_id',$event)
                  ->where('enabled',1)
                  ->get('system_events_subscribe')
                  ->result();
        
        if ( empty($subscribes) ) return;
        
        
        foreach( $subscribes as $subscribe ){
            //если подписка не активна, пропускаем
           if ( !$subscribe->enabled)
               continue;
             
           $params = json_decode( $subscribe->params ); 
           if ( $params === NULL )
               continue;
           
           if (property_exists($params, $event_type) ){
               
               $type = $params->{$event_type}->type;
               $addr = $params->{$event_type}->addr;
               $max_messages_in_hour = $params->{$event_type}->max_messages_in_hour;
               
               // получим количество отправок за час
               $hour_send_count = $this->db->where( array('event_subscribe_id' => $subscribe->id,
                                                          'event_type' => $event_type,
                                                          //'status' => 1,
                                                          ) )
                       ->where( 'send_datetime >', 'NOW() - INTERVAL 1 HOUR', FALSE )
                       ->count_all_results('system_events_messages');
               //echo $this->db->last_query();
               //echo "!".$hour_send_count;
                       
               // если превышает, то пропускаем отправку
               if ( $hour_send_count > $max_messages_in_hour )
                   continue;
               
               $send_status = 0;
               $title = $event_message."[$event_type]";
               $text = vsprintf($event_text, $data);
               $link =  base_url().'external_action/disable_event?id='.$subscribe->id.'&hash='.md5($subscribe->admin_id.'_:_'.$subscribe->id);
               
               // ВНИМАНИЕ! При отправки сообщений на какой либо мессенджер, желательно убедиться чтобы отправка сообщений не входила в рекурсию.
               // Например: если при отправке на email убрать последний параметр, то будет не очень хорошо
               switch( $type ){
                   case 'email':
                       $text .= "\nClick for disable this event <a href='".$link."'>here</a>";
                       $this->load->library('mail');
                       $res = $this->mail->send_ex( $addr, $text, $title, NULL, TRUE);
                       if ( isset($res['status']))
                           $send_status = 1;
                       break;
                   case 'viber':
                       $text .= "\nClick for disable this event <a href='".$link."'>here</a>";
                       $this->load->model('Viber_model');
                       $res = $this->Viber_model->sendMessageRouter($addr,$text);
                       if ( isset($res['success']))
                           $send_status = 1;                       
                       break;
                   case 'whatsapp':
                       $text .= "\nClick for disable this event <a href='".$link."'>here</a>";
                       $this->load->model('Whatsapp_model');
                       $res = $this->Whatsapp_model->sendMessageRouter($addr, $text);
                       if ( isset($res['success']))
                           $send_status = 1;                       
                       break;                       
                   case 'sms':
                       $text .= "\nGoto for disable this event: ".$link;
                       $this->load->model('Phone_model');
                       $res = $this->Phone_model->sendAdminMessage($text, $addr);
                       if ( isset($res['success']))
                           $send_status = 1;                       
                       break;                   
               }
               
               
               $ins_data = array(
                    'send_datetime' => date('Y-m-d H:i:s'),
                    'event_subscribe_id' => $subscribe->id,
                    'event_type' => $event_type,
                    'status' => $send_status,
                    'text' => substr($text, 0, 500)
               );
               $this->db->insert('system_events_messages', $ins_data);
               
           }
           
           
            
        }
        
        
    }
    
    
    /*
     * Возрвращает Event для получателя
     */
    public function get_event_subscribe($id){
        
        if ( empty($id)  )
            return FALSE;
        
        $row = $this->db->where('id', $id)->get('system_events_subscribe')->row();
        if ( empty($row))
            return FALSE;
        
        
        return $row;
        
    }
        
    
    
    /*
     * Деактивирует Event для получателя
     */
    public function disable_event_subscribe($id, $row = NULL){
        
        if ( empty($row))
            $row = $this->get_event_subscribe($id);
        
        if ( empty($row))
            return FALSE;
        
        if ( !$row->enabled )
            return TRUE;
        
        $this->db->update('system_events_subscribe', array('enabled'=>0), array('id='=>$id) );
        return TRUE;
        
    }
    
    
    /*
     * Деактивирует Event с проверкой hash
     */
    public function disable_event_with_hash($id, $hash){
        
        if ( empty($id) || empty($hash) )
            return FALSE;
        
        $row = $this->get_event_subscribe($id);
        if ( empty($row))
            return FALSE;
        
        if ( md5( $row->admin_id.'_:_'.$row->id ) != $hash)
             return FAlSE;
        
        return $this->disable_event_subscribe($id, $row);
        
        
        
        
        
    }
    
    /**
     * Возвращает все системные события
     * @return type
     */
    public function get_all(&$cnt, $page = 0,$rows = 0){
        
        
        $cnt = 0;
        
        try{
            //$this->db->cache_delete();
        //    $this->db->cache_on();
            
            
            $cnt = $this->db->count_all_results('system_events');

            
            $events = $this->db->order_by('id', 'desc' );
            if (  $rows > 0)
                         $events = $events->limit($rows, $page*$rows );
            $events = $events->get( 'system_events' )->result();
     //       $this->db->cache_off();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        if( empty( $events ) ) return FALSE;
        
        
        return $events;        
        
    }

    
    /**
     * Возвращает системное событие
     * @return type
     */
    public function get($id){
        
        
        $res =  $this->db->where('id', $id)->get( 'system_events' )->row();
        
        if ( empty($res) ) return FALSE;
        
        return $res;
        
    }
    
    /**
     * Добавить событие
     */
    public function add_event($machine_name, $human_name, $params){
       
        
        if ( empty($machine_name) || empty($human_name) || empty($params) ){
            self::$last_error = 'Не все поля заполнены';
            return FALSE;
        }
        
        $this->db->insert('system_events', array(
            'machine_name' => $machine_name,
            'human_name' => $human_name,
            'params' => $params
        ));
    //    $this->db->cache_delete();
        return TRUE;
    }

    /**
     * Добавить событие
     */
    public function edit_event($id, $machine_name, $human_name, $params){
       
        if ( empty($machine_name) || empty($human_name) || empty($params) ){
            self::$last_error = 'Не все поля заполнены';
            return FALSE;
        }
        
        
        $this->db->where('id', $id);
        $this->db->update('system_events', array(
            'machine_name' => $machine_name,
            'human_name' => $human_name,
            'params' => $params
        ));
      //  $this->db->cache_delete();
        return TRUE;
    }
    
    
    
    /**
     * Возвращает подписчиков на системное событие
     * @return type
     */    
    public function get_subscribers($event_id){
        
      // находим подписанных пользователей
        $subscribers = $this->db->select('s.*, a.family as admin_family, a.name as admin_name' )
                    ->from('system_events_subscribe s')
                    ->join('admin a', 'a.id_user=s.admin_id', 'left')
                    ->where('s.event_id', $event_id)
                    ->get()->result();
        return $subscribers;        
        
    }
    
    /**
     * Получить все системны события
     * @return type
     */
    public function get_all_events(){
        
        return  $this->db->get('system_events')->result();
        
    }
            
           
    
    
    /**
     * Возвращает системные события на который подписан пользователь
     * @return type
     */    
    public function get_subscribes($user_id){
        
        $subscribes = $this->db->select('s.*, se.machine_name, se.human_name' )
                    ->from('system_events_subscribe s')
                    ->join('system_events se', 'se.id=s.event_id')
                    ->where('s.admin_id', $user_id)
                    ->get()->result();
        return $subscribes;        
        
    }
    
    
    /**
     * Получает данные об event'e
     * @param type $event_id
     * @param type $user_id
     */
    public function get_event_params($event_id, $user_id){
        $res = array();
        $event = $this->db->where('id', $event_id)->get('system_events')->row();
        if ( empty($event)) return FALSE;
        
        $event_params = json_decode($event->params, TRUE);
        $event_groups = array_keys($event_params);
        
        $user_event_params = array();
        if ( (int)$user_id > 0 ){
            $user_event_data = $this->db->where('event_id', $event_id)->where('admin_id', $user_id)->get('system_events_subscribe')->row();
            if ( !empty($user_event_data) )
                $user_event_params = json_decode($user_event_data->params, TRUE);
        }        
        foreach( $event_groups as $group ){
            
            $res[$group]['type'] = @$user_event_params[$group]['type'];
            $res[$group]['addr'] = @$user_event_params[$group]['addr'];
            $res[$group]['max_messages_in_hour'] = @$user_event_params[$group]['max_messages_in_hour'];
        }
        

        
        
        
        
        
        
        return $res;
        
        
    }
    
    /**
     * Присоединить событие к пользователю
     * @param type $id
     */    
    public function link_event($subscribe_id, $admin_id, $event_id, $enabled, $params){
        
        if ( $subscribe_id == 0){
            $this->db->insert('system_events_subscribe', array(
                'admin_id' => $admin_id,
                'event_id' => $event_id, 
                'enabled' => $enabled, 
                'params' => $params )
            );
        } else {
            $this->db->where('id', $subscribe_id);
            $this->db->update('system_events_subscribe', array(
                'admin_id' => $admin_id,
                'event_id' => $event_id, 
                'enabled' => $enabled, 
                'params' => $params )
            );
            
            
        }
  //      $this->db->cache_delete();
        
        
    }
    
    
    /**
     * Отсоединить событие от пользователя
     * @param type $id
     */    
    public function unlink_event( $subscribe_id )
    {
            $this->db->where('id', $subscribe_id);
            $this->db->delete('system_events_subscribe');
      //      $this->db->cache_delete(); 
    }    

    
        
    /**
     * Return last error was catched
     * @return type
     */
    public function get_last_error() {
        return self::$last_error;
    }
    
    
}
