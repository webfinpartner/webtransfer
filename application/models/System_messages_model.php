<?php

class System_messages_model extends CI_Model {
    
    
    const STATUS_ACTIVE          = 10;  
    const STATUS_INACTIVE        = 20;
    const DEFAULT_LANGUAGE_ID    = 'en';
    
    public $table_read_list = 'system_messages_read';
    public $table_list = 'system_messages_list';
    public $table_list_type = 'system_messages_type';
    
    
    private $test_mode = FALSE;
    
    static $last_error;
    
    function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    /**
     * Hide message
     * 
     * @param type $user_id
     * @param type $message_id
     * @return boolean|int
     */
    public function set_user_message_status($user_id, $message_id )
    {
        if( empty( $user_id ) || empty($message_id) ) 
            return FALSE;
        
        $closed_system_messages_keys = $this->get_user_closed_messages( $user_id );
        $closed_system_messages_keys[] = $message_id;
        $this->session->set_userdata('closed_system_messages', $closed_system_messages_keys );
        
        try{
            $this->db->insert( $this->table_read_list, array( 'user_id' => $user_id, 
                                                    'message_id' => $message_id, 
                                                    'datetime' => date('Y-m-d H:i:s') ) );
            
        }catch( Exception $e ){
            self::$last_error = $e;
            return FALSE;
        }
        
        return 0; //it's ok
    }
    
    /**
     * Get next user message to show
     * 
     * @param type $user_id
     * @param type $language_id
     * @return boolean|null
     */
    
    public function get_user_message( $user_id, $language_id )
    {
        if( empty( $user_id ) ) 
            return FALSE;        
                
        $closed_system_messages = $this->get_user_closed_messages( $user_id );            
        $get_valid_messages = $this->get_valid_messages( $language_id );
                
        if( !empty( $get_valid_messages ) && !empty( $closed_system_messages ) )
        {
            foreach( $closed_system_messages as $id )
            {            
                if( isset( $get_valid_messages[ $id ] ) )
                    unset( $get_valid_messages[ $id ] );
                
            }

        }
        
        if( empty( $get_valid_messages ) ) return NULL;
        
        $get_valid_messages_keys = array_keys($get_valid_messages);            
        $last_id = $get_valid_messages_keys[ 0 ];

        return $get_valid_messages[ $last_id ];            
    }
    
    public function get_user_messages( $user_id )
    {
        if( empty( $user_id ) ) 
            return FALSE;        
        
        $language = $this->lang->lang();
                
        $closed_system_messages = $this->get_user_closed_messages( $user_id );            
        $get_valid_messages = $this->get_valid_messages( $language, $closed_system_messages );
        
        /*
        if( !empty( $get_valid_messages ) && !empty( $closed_system_messages ) )
        {
            foreach( $closed_system_messages as $id )
            {            
                if( isset( $get_valid_messages[ $id ] ) )
                    unset( $get_valid_messages[ $id ] );
                
            }

        }*/
        
        if( 1 )
        {
            #get additional messages    
            $get_add_valid_messages = $this->get_add_valid_messages( $user_id, $language );

            if( !empty( $get_add_valid_messages ) && empty( $get_valid_messages ) )
            {
                $get_valid_messages = $get_add_valid_messages;
            }else
            {                
                //$get_valid_messages = array_merge($get_valid_messages, $get_add_valid_messages);
            }
        }           
        if( empty( $get_valid_messages ) ) return NULL;
        
        return $get_valid_messages;
        
//        $get_valid_messages_keys = array_keys($get_valid_messages);            
//        $last_id = $get_valid_messages_keys[ 0 ];
//
//        return $get_valid_messages[ $last_id ];            
    }
    
    public function get_add_valid_messages_old( $user_id, $lang )
    {
        #closed for a while
        #return false;
        
        if( empty( $user_id ) || empty( $lang ) )
        {
            return NULL;
        }
        
        $pay_off_arbitration = $this->db->select('pay_off_arbitration')
                                    ->where('user_id', $user_id )
                                    ->where('status', 0 )
                                    ->where('pay_off_date >', date('Y-m-d') )                                    
                                    ->limit(1)
                                    ->order_by('id','DESC')
                                    ->get( 'users_pay_off_arbitration' )
                                    ->row();
        
        if( empty( $pay_off_arbitration ) )
        {
            return NULL;
        }
        $params = array();
        $params['sum'] = $pay_off_arbitration->pay_off_arbitration;
        
        $res = new stdClass();
        $res->id = NULL;
        $res->message_id        = 0;

        $res->start_datetime    = 0;
        $res->exp_datetime      = 0;    
        $res->status            = self::STATUS_ACTIVE;
        $res->type_id           = 10;//полоса
                
        if( $lang == 'ru' )
        {
            $res->lang              = 'ru';
            $res->text              = 'Уважаемый Участник! Займ(ы) на Арбитраж '.
                                       'в размере '. round( $params['sum'], 2 ) .'USD являются необеспеченными. Вам необходимо принять меры по обеспечению Арбитража, '.
                                        'иначе в течении 20 часов ваши кредитные сертификаты будут проданы для погашения '.
                                        'необеспеченного Арбитража. <a href="account/arbitrage_credit_recalculate" style="color: orange;">Пересчитать</a>.';
        }else{
            $res->lang              = 'en';
            $res->text              = 'Dear '.$GLOBALS["WHITELABEL_NAME"].' user! We would like to inform you that you currently have Arbitrage US$'.round( $params['sum'], 2 ).
                                        ' which is need additional cover. Please take immediate action, otherwise '.
                                        'the system will automatically sell required amount of credit certificates to '.
                                        'pay off the Arbitrage loan. <a href="account/arbitrage_credit_recalculate" style="color: orange;">Recalculate</a>';
        }
        $messages = array();
        $messages[] = $res;
        
        
        $new_messages = array();        
        foreach( $messages as $m )
        {
                $new_messages[$m->type_id][ $m->message_id ] = $m;
        }
        
        return $new_messages;
    }
    
    public function get_add_valid_messages( $user_id, $lang )
    {
        
        //overdue_arbitration
        if( empty( $user_id ) || empty( $lang ) )
        {
            return NULL;
        }
        
        $pay_off_arbitration = $this->db->select('pay_off_arbitration')
                                    ->where('user_id', $user_id )
                                    ->where('status', 0 )
                                    ->where('pay_off_date >=', date('Y-m-d') )
                                    ->where('purpose', 'overdue' )
                                    ->limit(1)
                                    ->order_by('id','DESC')
                                    ->get( 'users_pay_off_arbitration' )
                                    ->row();
        
        if( empty( $pay_off_arbitration ) )
        {
            return NULL;
        }
        $params = array();
        $params['sum'] = $pay_off_arbitration->pay_off_arbitration;
        
        $res = new stdClass();
        $res->id = NULL;
        $res->message_id        = 0;

        $res->start_datetime    = 0;
        $res->exp_datetime      = 0;    
        $res->status            = self::STATUS_ACTIVE;
        $res->type_id           = 10;//полоса
                
        if( $lang == 'ru' )
        {
            $res->lang              = 'ru';
            $res->text              = 'Уважаемый Участник! У вас есть займ(ы) на Арбитраж, у которых '.
                                      'наступил срок погашения. Вам необходимо принять меры по возврату Арбитража, '.
                                      'иначе в течении 24 часов они будут возвращены автоматически, при этом могут быть проданы '.
                                      'ваши кредитные сертификаты. '.
                                      '<a href="arbitrage/arbitrage_credit_recalculate_overdue" style="color: orange;">Пересчитать</a>';
        }else{
            $res->lang              = 'en';
            $res->text              =   'Dear user! You currently have overdue Arbitrage loan(s). Please take '.
                                        'immediate action, otherwise the system will automatically pay off Arbitrage loan(s) '.
                                        'in 24 hours and required amount of your credit certificates may be sold.'.
                                        '<a href="arbitrage/arbitrage_credit_recalculate_overdue" style="color: orange;">Recalculate</a>';
        }
        $messages = array();
        $messages[] = $res;
        
        
        $new_messages = array();        
        foreach( $messages as $m )
        {
                $new_messages[$m->type_id][ $m->message_id ] = $m;
        }
        
        return $new_messages;
    }
    
    /**
     * Get up to date closed user's messages from DB OR SESSION
     * 
     * @param type $user_id
     * @return boolean|null
     */
    public function get_user_closed_messages( $user_id )
    {
        if( empty( $user_id ) ) 
            return FALSE;

        
        
        $closed_system_messages = $this->session->userdata('closed_system_messages');
        if ($this->test_mode)
            $closed_system_messages = FALSE;
        
        if( !empty( $closed_system_messages ) && $closed_system_messages !== FALSE ) 
            return $closed_system_messages;
        
        try{

            $messages = $this->db->select('message_id')
                                ->where( array( 'user_id' => $user_id, 'datetime >=' => date('Y-m-d H:i:s') ))
                                ->group_by('message_id')
                                ->order_by('message_id', 'ASC')
                                ->get($this->table_read_list)
                                ->result();

        }catch( Exception $e ){
            self::$last_error = $e;
        }
        //write
 
        if( empty( $messages ) )
            return NULL;

        $closed_system_messages_keys = array();
        foreach( $messages as $m )
            $closed_system_messages_keys[] = $m->message_id;
        
        $this->session->set_userdata('closed_system_messages', $closed_system_messages_keys );

        return $closed_system_messages_keys; //it's ok
    }
    
    /**
     * Get ALL up to date and STATUS_ACTIVE messages ONLY from DB 
     * Using DB cache
     * 
     * @param type $language_id
     * @return boolean|null
     */
    public function get_valid_messages( $language, $exclude = array() )
    {        
        //if( !is_numeric( $language) )
          //  return FALSE;
        
        
        try{
        //    $this->db->cache_on();
            $messages = $this->db->where( array( 'start_datetime <=' => date('Y-m-d H:i:s'), 
                                                 'exp_datetime >=' => date('Y-m-d H:i:s'),
                                                 'lang'   => $language,
                                                 'status'        => self::STATUS_ACTIVE
                                           )
                                       );
            if ( !empty($exclude) )
                $messages = $messages->where_not_in('message_id',$exclude); 
            $messages = $messages->group_by( array('type_id','message_id')) 
                                   ->order_by('message_id', 'ASC')
                                   ->get($this->table_list )
                                   ->result();
      //      $this->db->cache_off();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        
        if( empty( $messages ) ) return NULL;
        
        $new_messages = array();
        
        foreach( $messages as $m ){
                if ( $m->type_id == 21 )
                    $new_messages[20][ $m->message_id ] = $m;
                else
                    $new_messages[$m->type_id][ $m->message_id ] = $m;
        }
        
        return $new_messages; //it's ok
    }
    
    /**
     * Get ALL  messages from DB 
     * Using DB cache
     * 
     * @return array|boolean
     */
    public function get_all_messages(&$cnt, $page = 0, $rows = 0, $sort = array())
    {        
        $cnt = 0;
        
        try{
      //      $this->db->cache_on();
            
            $cnt = $this->db->select("COUNT(*) as count")
                                   ->from($this->table_list.' m')
                                   ->where('m.lang',self::DEFAULT_LANGUAGE_ID)
                                   ->get()->row()->count;
            
            $messages = $this->db->select("m.*")
                                   ->from($this->table_list.' m')
                                   ->where('m.lang',self::DEFAULT_LANGUAGE_ID);
            
            if (  $rows > 0)
                         $messages = $messages->limit($rows, $page*$rows );
            
            if (!empty($sort)){
                foreach ( $sort as  $sort_field=>$sort_order){
                    switch ( $sort_field ){
                        case 'status_name':
                             $messages = $messages->order_by('status', $sort_order );
                            break;
                        default:
                             $messages = $messages->order_by($sort_field, $sort_order );
                            break;
                    }
                }
            } else {
                $messages = $messages->order_by('id', 'desc' );
            }
                
            $messages = $messages->get( )
                                   ->result_array();
        //    $this->db->cache_off();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        if( empty( $messages ) ) return FALSE;
        
        $statuses = $this->get_statuses();
        $types = $this->get_types();
        if( empty( $types ) ) return FALSE;
        
        // переконвертим даты и добавим названмя статусам
        foreach( $messages as &$m )
        {
            $m['start_datetime'] = $this->_convertToHumanDateTime( $m['start_datetime'] );
            $m['exp_datetime'] = $this->_convertToHumanDateTime( $m['exp_datetime'] );
            $m['status_name'] = $statuses[$m['status']];
            $m['type_name'] = $types[$m['type_id']];
        }

        
        return $messages;
    }
    
    
    /**
     * Get last message id
     * @return null
     */
    public function get_last_message_id()
    {
        try{            
            /*$message = $this->db->where( array( 'start_datetime <=' => date('Y-m-d H:i:s') ))
                                 ->order_by('message_id','ASC')
                                 ->limit(1)
                                 ->get($this->table_list )
                                 ->row();            
                                 */
            $message = $this->db->order_by('message_id','DESC')
                                 ->limit(1)
                                 ->get($this->table_list )
                                 ->row();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        
        if( empty( $message ) ) return NULL;
        
        return $message->message_id; //it's ok
    }
    
    /**
     * Adding a new information message for all users. 
     * Clear DB cache.
     * 
     * @param type $text
     * @param type $language_id
     * @param type $start_date
     * @param type $exp_datetime
     * @param int $type_id
     * @param type $status
     * @return boolean|int
     */
    public function add_message( $text, $language_id, $start_datetime, $exp_datetime, $type_id, $message_id = null, $status = self::STATUS_ACTIVE )
    {
        if( empty( $text ) || empty($exp_datetime) || empty( $start_datetime )  || empty( $type_id ) ) 
            return FALSE;
        
        if( $message_id == null ) $message_id = $this->get_last_message_id() + 1;
        
    //    $this->db->cache_delete_all();
        try{
            $this->db->insert( $this->table_list, 
                                array( 'start_datetime' => $start_datetime,
                                       'exp_datetime'   => $exp_datetime,
                                       'text'           => $text,
                                       'lang'    => $language_id,
                                       'type_id' => $type_id,
                                       'status'         => $status,
                                       'message_id'     => $message_id
                                ) 
                            );        
            $insert_id = $this->db->insert_id();
        }
        catch( Exception $e )
        {
            self::$last_error = $e;
        }
        
        if( $insert_id ==  0 ) return FALSE;
        
        return $insert_id; //it's ok
    }
    
    /**
    * Добавляет системное сообщение из формы
    * 
    * @param mixed $data
    */
    public function create_message($data){
        
        if( empty( $data['base_text'] ) || empty($data['exp_datetime']) || empty( $data['start_datetime'] ) || empty( $data['status']) || empty( $data['type_id'] ) )
            return FALSE;

        
        //проверим языки на дубликаты
        $dublicatesArray = array(self::DEFAULT_LANGUAGE_ID);
        if ( !empty($data['language_new']) )
             $dublicatesArray = array_merge($dublicatesArray,$data['language_new']);
      
        
        if ( count($dublicatesArray) > count(array_unique($dublicatesArray))){
            self::$last_error = 'У сообщения не может быть текста  на одном и том же языке';
            return FALSE;
        }        
        //echo '<pre>';var_dump($data);echo '</pre>';
        
        $message_id = $this->get_last_message_id() + 1;
        
   //    $this->db->cache_delete();
        try{
            $this->db->trans_start();   //stransaction starts
            
            
            // добавим общие данные 
            $this->db->insert( $this->table_list, 
                                array( 'start_datetime' => $this->_convertToMysqlDateTime($data['start_datetime']),
                                       'exp_datetime'   => $this->_convertToMysqlDateTime($data['exp_datetime']),
                                       'status'         => $data['status'],
                                       'title' => $title,
                                       'type_id' => $data['type_id'],
                                       'lang'    => self::DEFAULT_LANGUAGE_ID,
                                       'text' => $data['base_text'],
                                       'title' => isset($data['base_title'])?$data['base_title']:NULL,
                                       'message_id' => $message_id
                                )
                            );        
            //echo $this->db->last_query();            
            $id = $this->db->insert_id();
            
            // добавим новые сообщения для текущего message_id
            if ( !empty($data['language_new']) && !empty($data['text_new'] ) )
                foreach( $data['text_new'] as $idx=>$text)
                {
                    if ( trim($text) !== '')
                                    $this->db->insert( $this->table_list, array(
                                                            'start_datetime' => $this->_convertToMysqlDateTime($data['start_datetime']),
                                                            'exp_datetime'   => $this->_convertToMysqlDateTime($data['exp_datetime']),
                                                            'status'         => $data['status'],
                                                            'text' => $text,
                                                            'title' =>$data['title_new'][$idx],
                                                            'type_id' => $data['type_id'],
                                                            'lang' => $data['language_new'][$idx],
                                                            'message_id' => $message_id 
                                                                ) );
                    //echo $this->db->last_query();
                }
            
            $this->db->trans_complete(); //stransaction ends
            
        }
        catch( Exception $e )
        {
            self::$last_error = $e;
            return FALSE;
        }
        
        
        return $id;
        
        
        
    }
    
    
    /**
    * Редактирует системное сообщение из формы
    * 
    * @param mixed $id
    * @param mixed $data
    */
    public function edit_message($id, $data){
        
        
        if( empty( $data['base_text'] ) || empty($data['exp_datetime']) || empty( $data['start_datetime'] ) ||
            empty( $data['status'] ) ||  empty($data['message_id']) || empty( $data['type_id'] ) )
            return FALSE;
        
        //echo '<pre>';var_dump($data);echo '</pre>';
        
        //проверим языки на дубликаты
        $dublicatesArray = array( self::DEFAULT_LANGUAGE_ID );
        if ( !empty($data['language_new']) )
             $dublicatesArray = array_merge($dublicatesArray,$data['language_new']);
        if ( !empty($data['language']) )
             $dublicatesArray = array_merge($dublicatesArray,$data['language']);        
        
        if ( count($dublicatesArray) > count(array_unique($dublicatesArray))){
            self::$last_error = 'У сообщения не может быть текста  на одном и том же языке';
            return FALSE;
        }
        
    //    $this->db->cache_delete();
        try{
            $this->db->trans_start();   //stransaction starts
            
            
            
            // удалим сообщения, которых нет в списке
            $exists_ids = array($id);
            if ( !empty($data['language']) )
                   foreach( $data['language'] as $exist_id=>$lang)
                      $exists_ids[] = $exist_id;
            $this->db->where_not_in('id', $exists_ids)->delete($this->table_list, array( 'message_id' => $data['message_id']));                         
            //echo $this->db->last_query();
    
            
            
            
            // обновим общие данные для сообщений c текущим message_id
            $this->db->update( $this->table_list, 
                                array( 'start_datetime' => $this->_convertToMysqlDateTime($data['start_datetime']),
                                       'exp_datetime'   => $this->_convertToMysqlDateTime($data['exp_datetime']),
                                       'type_id' => $data['type_id'],
                                       'status'         => $data['status']
                                       
                                       
                                ),
                                array('message_id' => $data['message_id']) 
                            );        
            //echo $this->db->last_query();
            
            // обновим текст для языка по умолчанию (редактируем всегда id для текста по умолчанию)
            $this->db->update( $this->table_list, array( 'text' => $data['base_text'], 'title' => $data['base_title']), array('id' => $id));       
            //echo $this->db->last_query();
            
            // добавим новые сообщения для текущего message_id
            if ( !empty($data['language_new']) && !empty($data['text_new'] ) )
                foreach( $data['text_new'] as $idx=>$text)
                {
                    if ( trim($text) !== '')
                                    $this->db->insert( $this->table_list, array(
                                                            'start_datetime' => $this->_convertToMysqlDateTime($data['start_datetime']),
                                                            'exp_datetime'   => $this->_convertToMysqlDateTime($data['exp_datetime']),
                                                            'status'         => $data['status'],
                                                            'title' => $data['title_new'][$idx],
                                                            'text' => $text,
                                                            'type_id' => $data['type_id'],
                                                            'lang' => $data['language_new'][$idx],
                                                            'message_id' => $data['message_id'] 
                                                                ) );
                   // echo $this->db->last_query();
                } 

            
            // редактирование сообщения для текущего message_id
            if ( !empty($data['language']) && !empty($data['text'] ) )
                foreach( $data['text'] as $idx=>$text)
                {
                    if ( trim($text) !== '')
                                    $this->db->where('id',$idx)->update( $this->table_list, array(
                                                            'start_datetime' => $this->_convertToMysqlDateTime($data['start_datetime']),
                                                            'exp_datetime'   => $this->_convertToMysqlDateTime($data['exp_datetime']),
                                                            'status'         => $data['status'],
                                                            'title' =>$data['title'][$idx],
                                                            'text' => $text,
                                                            'type_id' => $data['type_id'],
                                                            'lang' => $data['language'][$idx],
                                                            'message_id' => $data['message_id'] 
                                                                ) );
                    //echo $this->db->last_query();
                } 

            
            
            
            
                            
                                         
            $this->db->trans_complete(); //stransaction ends

        }
        catch( Exception $e )
        {
            self::$last_error = $e;
            return FALSE;
        }
        
        
        return TRUE;
        
        
        
    }
    
    
    /**
    * Удаляем системное сообщение 
    * Если full = true, то удалим все связанные сообщения
    * 
    * @param mixed $id
    * @param boolean $full
    */
    public function remove_message($id, $full = false){
        
        
   //     $this->db->cache_delete();
        try{
            $this->db->trans_start();   //stransaction starts
            
            // удалим сообщение
            if ( $full){
              $message = $this->get($id);
              if ( $message === FALSE)
                    throw new Exception("Сообщение не найдено");
              $this->db->delete($this->table_list, array( 'message_id' => $message->message_id));                           
            } else
                $this->db->delete($this->table_list, array( 'id' => $id));                         
        
            //echo $this->db->last_query();
                                         
            $this->db->trans_complete(); //stransaction ends

        }
        catch( Exception $e )
        {
            self::$last_error = $e;
            return FALSE;
        }
        
        
        return TRUE;
        
        
        
    }
    
    
    
     /**
     * TODO: Вынести в какую нибуть общую библиотеку
     * 
     * @param mixed $mysql_datetime
     */
    private function _convertToHumanDateTime($mysql_datetime){
        
          //return $mysql_datetime;
          //var_dump($humanDateTime);
          if ( $mysql_datetime == '0000-00-00 00:00:00')
             return '00-00-0000 00:00:00';

          $humanDateTime = date_create_from_format('Y-m-d H:i:s', $mysql_datetime);
          return date_format($humanDateTime,'d-m-Y H:i:s');
        
    }    
    

     /**
     * TODO: Вынести в какую нибуть общую библиотеку
     *  
     * @param mixed $human_datetime
     */
    private function _convertToMysqlDateTime($human_datetime){
        
          //return $mysql_datetime;
          //var_dump($humanDateTime);
          if ( $human_datetime == '00-00-0000 00:00:00')
             return '0000-00-00 00:00:00';

          $mysqlDateTime = date_create_from_format('d-m-Y H:i:s', $human_datetime);
          return date_format($mysqlDateTime,'Y-m-d H:i:s');
        
    }    

    
    /**
    * Получить системное сособщение с ID = $id
    * 
    * @param int $id
    */
    public function get($id){
                
         try{
            $message = $this->db->where('id',$id)    
                                   ->get(  $this->table_list )
                                   ->row();
        }catch( Exception $e ){
            self::$last_error = $e;
        }
        
        if( empty( $message ) ) return FALSE;
        
        // установим базовый текст
        $message->base_text = $message->text;
        unset($message->text);
        // и заголовок
        $message->base_title = $message->title;
        unset($message->title);
        
        // переведем даты
        $message->start_datetime = $this->_convertToHumanDateTime( $message->start_datetime );
        $message->exp_datetime = $this->_convertToHumanDateTime( $message->exp_datetime );
        
        
        
        // получим субитемы
        try{
            $subitems = $this->db->where( array( 'message_id'=>$message->message_id,
                                                 'id !=' => $id ) )
                                   ->get(  $this->table_list )
                                   ->result();
        }catch( Exception $e ){
            self::$last_error = $e;
        }  
        
        // и добавим язык и текст субитема к итему
        if ( !empty($subitems))
           foreach ( $subitems as $subitem)
           {
              $message->language[$subitem->id] = $subitem->lang;  
              $message->text[$subitem->id] = $subitem->text;  
              $message->title[$subitem->id] = $subitem->title;  
           }
        
        return $message;        
    }    
    
    /**
     * Возвращает массив статусов системного сообщения
     * @return type
     */
    public function get_statuses(){
        
        return array(self::STATUS_ACTIVE => 'Активно',
                     self::STATUS_INACTIVE => 'Неактивно'
            );
        
    }
    
    /**
     * Возвращает массив типов системного сообщения
     * @return type
     */
    public function get_types(){

        $result = array();
        try {
  //          $this->db->cache_on();
            $statuses = $this->db->get($this->table_list_type)->result();
      //       $this->db->cache_off();
        } catch(Exception $e){
            self::$last_error = $e;
        }
        
        if ( !empty($statuses))
            foreach( $statuses as $status)
                   $result[ $status->id ] = $status->human_name;
        
        return $result;

        
        
    }
    
    
    /**
     * Return last error was catched
     * @return type
     */
    public function get_last_error() {
        return self::$last_error;
    }
}