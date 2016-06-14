<?

class Messenger_model extends CI_Model {
    
    
    /**
    * Получает список всех мессенжеров. 
    * 
    */
    
     public function get_list(  )
     {
         
         // TODO: пока нет инерфейса для редактирования сервисов - кэширование не делаем         
         $res = $this->db->get('messengers')->result();
         if ( empty($res) )
            return FALSE;
            
         return $res;
         
     }
    
    
    /**
    * 
    * 
    */
    
     public function get_messenger_by_human_name( $name )
     {
         
         // TODO: пока нет инерфейса для редактирования сервисов - кэширование не делаем         
         $res = $this->db->where('machine_name', $name)->get('messengers')->row();
         if ( empty($res) )
            return FALSE;
            
         return $res;
         
     }    
    
    
    /**
    * Получает список сервисов для мессенджера. 
    * 
    * INT $id
    */
    
     public function get_services( $messenger_id )
     {
         
         
         // TODO: пока нет инерфейса для редактирования сервисов - кэширование не делаем         
         /*if ( !is_numeric($id) ){
            $messenger = $this->get_messenger_by_human_name($messenger_id); 
            if ( $messenger === FALSE)
                return FALSE;
            $messenger_id = $messenger->id;
         } */
         
         
         $res = $this->db->where('messenger_id', $messenger_id)->get('messengers_services')->result();            
         
         if ( empty($res) )
            return FALSE;
            
         return $res;
         
     }
    
     /**
     * Получить серсис по ID
     * 
     * @param mixed $id
     */
     public function get_service( $id )
     {
         
         // TODO: пока нет инерфейса для редактирования сервисов - кэширование не делаем         
         $res = $this->db->where('id', $id)->get('messengers_services')->result();
         if ( empty($res) )
            return FALSE;
            
         return $res;
         
     }    
    
    
    
}