<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class  System_events extends Permissions_controller
{
    public $info_state = true;
    public function __construct(){
                 parent::__construct();
                admin_state();
                $this->load->model('System_events_model');
                
                $this->load->helper( 'form' );
                
                if ($this->info_state)
                    $this->load->library('info');
                
    }
    
 
    /**
     * Action: главная страница для редактирования системных событий
     */
     public function all(){
        $this->content->view('system_events', 'Системные события', array());  
     }


     
    public function index($id = 0) {
        
        
        $id = intval($id);
        $this->element_id = $id;
        
        
        if (empty($id))
        {
                 
                 $page = 0;
                 $rows = 0;
                 
                 $recs = $this->System_events_model->get_all($recsCount, $page,$rows);
                 
                 $resultArray = array(
                        'rows' => array(),
                        'total' => $recsCount
                    );
                  foreach( $recs as &$rec )
                  {

                      $rec->action = '<nobr><a title="Редактировать" class="smallButton" href="/opera/system_events/'.$rec->id.'" ><img src="images/icons/018.png"></a>';
                      $rec->action .= '<a title="Удалить" class="smallButton del" onclick="return yes_no()" href="/opera/system_events/delete/'.$rec->id.'" ><img src="images/icons/101.png"></a></nobr>';
                      
                      $resultArray['rows'][] = $rec;
                  }
                    

                  echo json_encode($resultArray);
                  return;
        }

        if (!empty($_POST['submited'])) {
            

            $res = $this->System_events_model->edit_event($id, $_POST['machine_name'], $_POST['human_name'], $_POST['params'] );
            // если при сохранение произошла какая либо ошибка, то покажем ее
            if ( $res === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = $id;
                if ($this->info_state) $this->info->add($this->System_events_model->get_last_error());
                $this->content->view('system_events_item', '', $this->data);
                return;
            }            
            if ($this->info_state) $this->info->add("edit");
            redirect(  base_url() . 'opera/system_events/all'  );                
        }
        

        if (!isset($this->data['item']))
            $this->data['item'] = $this->System_events_model->get($id);

        
        $this->data['state'] = $id;
        //$this->data['messages'] =  $this->System_messages_model->get_all_messages($recsCount,0,0);
        $this->content->view('system_events_item', '', $this->data);
        
    }
    /**
     * Action: Создание системного события
     */
    public function create($id = 0) {
        
        if (empty($_POST['submited'])) {            
            $this->data['state'] = 'create';
            $this->content->view('system_events_item', "",  $this->data);
        } else {
            $res = $this->System_events_model->add_event( $_POST['machine_name'], $_POST['human_name'], $_POST['params'] );            
            // если при сохранение произошла какая либо ошибка то покажем ее
            if ( $res === FALSE ){
                $this->data['item'] = (object)$data;
                $this->data['state'] = 'create';
                if ($this->info_state) $this->info->add($this->System_events_model->get_last_error());
                $this->content->view('system_events_item', '', $this->data);
                return;
            }
            
            
            if ($this->info_state)$this->info->add("add");
            redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
        }
    }
    
    /**
     * Action: Удаление системного события c ID=$id
     * @param int $id
     */
    public function delete($id = 0) {
        $this->info->add("Удалять события могут только разработчики");
        redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
    }	
    
 
    public function subscribers($id = 0) {
           $res = array();
           
           
           $data = $this->System_events_model->get_subscribers($id);
           
           foreach( $data as &$row){
                $params = json_decode( $row->params, true );
                $types = array_keys($params);
                $row->fio = $row->admin_family.' '.$row->admin_name.' ('.$row->admin_id.')';
                $row->enabled = ($row->enabled==1?'ДА':'НЕТ');

                $row->children = array();
                foreach( $types as $event_type ){
                    $child = new stdClass();
                    $child->id = $event_type.'_'.$row->id;
                    $child->fio = $event_type;
                    $child->addr_type = $params[$event_type]['type'];
                    $child->addr = $params[$event_type]['addr'];
                    $child->max_messages_in_hour = $params[$event_type]['max_messages_in_hour'];
                    $row->children[] = $child;
                }
                $res[] = $row;
           }
           
           
           echo json_encode($res);
    }

    

}