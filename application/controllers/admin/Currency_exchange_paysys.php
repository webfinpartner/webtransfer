<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class  Currency_exchange_paysys extends Permissions_controller
{
    public $info_state = true;
    public function __construct(){
                 parent::__construct();
                admin_state();
                $this->load->model('Currency_exchange_model');
                $this->load->model('Currency_model');
                
                
                
                if ($this->info_state)
                    $this->load->library('info');
                
    }
    


    private function _get_new_ps_by_group($group_id)
    {
        $ps = $this->Currency_exchange_model->get_new_user_payment_systems_by_group($group_id);

        if (!empty($ps))
        {
            foreach ($ps as &$p)
            {
//                $p->name = $p->machine_name;
                $p->type = 'none';
                $p->parentId = $_POST['id'];
//                $p->action = '<div align="center">'
//                        . '<a title="Редактировать" class="smallButton" href="' . base_url() . 'opera/currency_exchange_paysys/ps/' . $p->id . '"><img src="images/icons/018.png"></a>'
//                        . '<a title="Удалить" href="' . base_url() . 'opera/currency_exchange_paysys/ps_delete/' . $p->id . '" class="smallButton del"><img src="images/icons/101.png"></a>'
//                        . '</div>';
                $added_state = $p->added;
                
                $p->action = '<nobr>';
                
                switch( $added_state  ){
                   case Currency_exchange_model::NEWPS_ADDED_STATE_NEW:
                       $p->added = 'Новая заявка';
                       $p->action = '<a class="smallButton" href="#" onclick="set_state('.$p->id.','.Currency_exchange_model::NEWPS_ADDED_STATE_ADDED.'); return false;">Добавить</a>';
                       $p->action .= '<a class="smallButton"  href="#"  onclick="set_state('.$p->id.','.Currency_exchange_model::NEWPS_ADDED_STATE_REJECTED.'); return false;">Отклонить</a>';
                       break;
                   case Currency_exchange_model::NEWPS_ADDED_STATE_ADDED:
                       $p->added = 'Добавлено';
                       break;
                   case Currency_exchange_model::NEWPS_ADDED_STATE_REJECTED:
                       $p->added = 'Отклонено';
                       break;                     
                   default: 
                       $p->added = 'Новая заявка';
                }

                $p->action .= '<a title="Удалить" class="smallButton del" href="#"  onclick="delete_new('.$p->id.'); return false;">Удалить</a></nobr>';

                $p->id = 'ps_' . $p->id;
            }
        }
        else
        {
            $ps = [];
        }
        
        return $ps;
    }



    private function _get_group_ps_by_group_id($group_id)
    {
        $id_arr = explode('_', $group_id);

        $id = (int)$id_arr[1];
        $ps = $this->Currency_exchange_model->get_full_list_payment_systems_groups($id);

        if (!empty($ps))
        {
            foreach( $ps as &$p)
            {
                $p->name = $p->human_name;
//                  $p->state = 'closed';
                $p->type = 'showPS';
                $p->parentId = $group_id;
                $p->id = 'g_'.$p->id;
            }
        }
        else
        {
            $ps = [];
        }

        $new_ps = $this->_get_new_ps_by_group($id);

        if(!empty($new_ps) && count($new_ps) > 0)
        {
            $ps = array_merge($ps, $new_ps);
        }

        return $ps;
    }
    
    
    private function _get_new_ps()
    {
//        $ps = $this->_get_new_ps_by_group(1);
        // showGroup загружаем паренты
        if ( isset($_POST['id']) && isset($_POST['type'])  && $_POST['type'] == 'showGroup' )
        {
            $ps = $this->_get_group_ps_by_group_id($_POST['id']);

            echo json_encode($ps);
            
            return false;
        }
        
        // загружаем паренты
        if (isset($_POST['id']) && isset($_POST['type']) && $_POST['type'] == 'showPS')
        {
            $ps = $this->_get_group_ps_by_group_id($_POST['id']);

            echo json_encode($ps);
            
            return;
        }

        $res = [];
        $recs = $this->Currency_exchange_model->get_full_list_payment_systems_groups();
        
        if ( !empty($recs) )
        {
            foreach( $recs as &$row)
            {
                $row->name = $row->human_name;
                $row->state = 'closed';
                $row->parentId = 0;

                $childs = $this->Currency_exchange_model->get_full_list_payment_systems_groups($row->id);     
                
                if ( !empty($childs) )
                {
                    $row->type = 'showGroup';
                }
                else
                {
                    $row->type = 'showPS';
                }

                $count_new_ps = $this->Currency_exchange_model->get_count_children_new_ps($row->id);
                
                if($count_new_ps > 0)
                {
                    $row->name .= ' <span class="show_how_new_ps">'.$count_new_ps.'</span>';
                }
                
                $row->id = 'g_'.$row->id;
                
                $res[] = $row;
            }

            $new_ps = $this->_get_new_ps_by_group(0);
            
            if(!empty($new_ps) && count($new_ps) > 0)
            {
                $res = array_merge($res, $new_ps);
            }
        }
           
        echo json_encode($res);

        return false;
    }



    private function _get_all_ps(){
          
           // загружаем паренты
           if ( isset($_POST['id']) && isset($_POST['type'])  && $_POST['type'] == 'showPS' ){
                $id_arr = explode('_', $_POST['id']);
                $id = (int)$id_arr[1];
                $ps = $this->Currency_exchange_model->get_full_list_payment_systems($id);        
                if (!empty($ps)){
                    foreach( $ps as &$p){
                      $p->name = $p->machine_name;
                      $p->type = 'none';
                      $p->parentId = $_POST['id'];
                      $p->action = '<div align="center">'
                      . '<a title="Редактировать" class="smallButton" href="'.base_url().'opera/currency_exchange_paysys/ps/'.$p->id.'"><img src="images/icons/018.png"></a>'
                      . '<a title="Удалить" href="'.base_url().'opera/currency_exchange_paysys/ps_delete/'.$p->id.'" class="smallButton del"><img src="images/icons/101.png"></a>'
                      . '</div>';

                      
                      $p->id = 'ps_'.$p->id;
                    }
                } else 
                    $ps = [];   
                echo json_encode($ps);
                return;
           }
           
           // загружаем паренты
           if ( isset($_POST['id']) && isset($_POST['type'])  && $_POST['type'] == 'showGroup' ){
                $id_arr = explode('_', $_POST['id']);
                $id = (int)$id_arr[1];
                $ps = $this->Currency_exchange_model->get_full_list_payment_systems_groups($id);        
                if (!empty($ps)){
                    foreach( $ps as &$p){
                      $p->name = $p->human_name;
                      $p->state = 'closed';
                      $p->type = 'showPS';
                      $p->parentId = $_POST['id'];
                      $p->action = '<div align="center">'
                        . '<a title="Редактировать" class="smallButton" href="'.base_url().'opera/currency_exchange_paysys/group/'.$p->id.'"><img src="images/icons/018.png"></a>'
                        . '<a title="Удалить" href="'.base_url().'opera/currency_exchange_paysys/group_delete/'.$p->id.'" class="smallButton del"><img src="images/icons/101.png"></a>'
                        . '</div>';

                      $p->id = 'g_'.$p->id;

                    }
                } else 
                    $ps = [];      
                echo json_encode($ps);
                return;
           }
           
           $res = [];
           $recs = $this->Currency_exchange_model->get_full_list_payment_systems_groups();
           if ( !empty($recs) )
            foreach( $recs as &$row){
                $row->name = $row->human_name;
                $row->state = 'closed';
                $row->parentId = 0;
                
                $childs = $this->Currency_exchange_model->get_full_list_payment_systems_groups($row->id);     
                if ( !empty($childs) )
                    $row->type = 'showGroup';
                else
                    $row->type = 'showPS';
                
                
                $row->action = '<div align="center">'
                        . '<a title="Редактировать" class="smallButton" href="'.base_url().'opera/currency_exchange_paysys/group/'.$row->id.'"><img src="images/icons/018.png"></a>'
                        . '<a title="Удалить" href="'.base_url().'opera/currency_exchange_paysys/group_delete/'.$row->id.'" class="smallButton del"><img src="images/icons/101.png"></a>'
                        . '</div>';
                //<img src="images/icons/160.png">
                $row->id = 'g_'.$row->id;
                
                
                $res[] = $row;
                
            }   
           
//           pred($res);
           echo json_encode($res);        
    }
    
 
    /**
     * Action: главная страница 
     */
     public function all(){
        if ( isset($_POST['tabledata_new'])){
            $this->_get_new_ps();
            return;
        }
        if ( isset($_POST['tabledata_all'])){
            $this->_get_all_ps();
            return;
        }
         
         
        $data['paysys'] = $this->Currency_exchange_model->get_new_user_payment_systems();
        $this->content->view('currency_exchange_paysys', 'Платежные системы', $data);  
     }
     
     
     private function _load_icon($old_path, &$errors){
            $errors = FALSE;
            if ( !isset($_FILES['new_public_path_to_icon']) || (isset($_FILES['new_public_path_to_icon']) && empty($_FILES['new_public_path_to_icon']['name']))  ){
                return $old_path;
            }
            
            $new_path = $old_path;
            $config['upload_path'] = './images/currency_exchange/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']	= '100';
            $config['max_width']  = '40';
            $config['max_height']  = '40';
            $this->load->library('upload', $config);
            if ( $this->upload->do_upload('new_public_path_to_icon'))
            {
                    $upload_data = $this->upload->data();
                    $new_path =  'background: url(images/currency_exchange/'.$upload_data['file_name'].')  no-repeat';
                    

            } else {
                     $errors = 'Информация сохранена, но не удалось загрузить иконку: '.$this->upload->display_errors().'. Попробуй еще раз.';
            }         
            return $new_path;
         
     }

    /**
     * Action: редактирование 
     */
     public function ps($id=0){
         if ( !empty($id))
         {
            // save 
            if (!empty($_POST['submited'])) {

                $data['group_id'] = $this->input->post('group_id');
                $data['payment_id']= $this->input->post('payment_id');
                $data['machine_name']= $this->input->post('machine_name');
                $data['is_bank']= $this->input->post('is_bank');
                $data['present_out']= $this->input->post('present_out');
                $data['present_in']= $this->input->post('present_in');
                $data['fee_percentage']= $this->input->post('fee_percentage');
                $data['fee_percentage_add']= $this->input->post('fee_percentage_add');
                $data['fee_min']= $this->input->post('fee_min');
                $data['fee_max']= $this->input->post('fee_max');
                $data['method_calc_fee']= $this->input->post('method_calc_fee');
                $data['currency_id']= $this->input->post('currency_id');
                $data['public_path_to_icon']= $this->input->post('public_path_to_icon');
                $data['active']= $this->input->post('active');
                $data['order']= $this->input->post('order');

                if ( $data['active'] === NULL)
                    $data['active'] = 0;
                if ( $data['is_bank'] === NULL)
                    $data['is_bank'] = 0;
                
                
                // загрузка иконки
                $data['public_path_to_icon'] =  $this->_load_icon($data['public_path_to_icon'], $upload_errors);
                
                $res = $this->Currency_exchange_model->edit_payment_system($id, $data );
                // если при сохранение произошла какая либо ошибка, то покажем ее
                if ( $res === FALSE ){
                    $this->data['item'] = (object)$_POST;
                    $this->data['state'] = $id;
                    $this->info->add($this->Currency_exchange_model->get_last_error());
                    $this->content->view('currency_exchange_paysys_ps', 'Платежные системы', $data);  
                    return;
                }            
                if ( $upload_errors !== FALSE)
                    $this->info->add($upload_errors);
                else
                    $this->info->add("edit");
                redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );                
            }             
             
             
            // редактирование 
            $data['item'] = $this->Currency_exchange_model->get_payment_systems_by_id($id);
            $data['state'] = $id;
            // добавим группы
            $data['groups'] = [];
            $groups = $this->Currency_exchange_model->get_full_list_payment_systems_groups(-1);
            if (!empty($groups))
                foreach ($groups as $group)
                    $data['groups'][$group->id] = $group->human_name;
            // добавим валюты
            $data['currencies'] = [];
            $cl = $this->Currency_model->get_currency_list();
            if (!empty($cl))
                foreach ($cl as $c)
                    $data['currencies'][$c->code] = $c->short_name;
            
            $this->content->view('currency_exchange_paysys_ps', 'Платежные системы', $data);  
            
            return;
         }
 
         
     }     
     
    /**
     * Action: создание
     */
     public function ps_create($parent_group = 0){
            // save 
            if (!empty($_POST['submited'])) {

                $data['group_id'] = $this->input->post('group_id');
                $data['payment_id']= $this->input->post('payment_id');
                $data['machine_name']= $this->input->post('machine_name');
                $data['is_bank']= $this->input->post('is_bank');
                $data['present_out']= $this->input->post('present_out');
                $data['present_in']= $this->input->post('present_in');
                $data['fee_percentage']= $this->input->post('fee_percentage');
                $data['fee_percentage_add']= $this->input->post('fee_percentage_add');
                $data['fee_min']= $this->input->post('fee_min');
                $data['fee_max']= $this->input->post('fee_max');
                $data['method_calc_fee']= $this->input->post('method_calc_fee');
                $data['currency_id']= $this->input->post('currency_id');
                $data['public_path_to_icon']= $this->input->post('public_path_to_icon');
                $data['active']= $this->input->post('active');
                $data['order']= $this->input->post('order');

                if ( $data['active'] === NULL)
                    $data['active'] = 0;
                if ( $data['is_bank'] === NULL)
                    $data['is_bank'] = 0;
                
                // загрузка иконку
                $data['public_path_to_icon'] =  $this->_load_icon($data['public_path_to_icon'], $upload_errors);


                $res = $this->Currency_exchange_model->create_payment_system( $data );
                // если при сохранение произошла какая либо ошибка, то покажем ее
                if ( $res === FALSE ){
                    $this->data['item'] = (object)$_POST;
                    $this->data['state'] = $id;
                    $this->info->add($this->Currency_exchange_model->get_last_error());
                    $this->content->view('currency_exchange_paysys_ps', 'Платежные системы', $data);  
                    return;
                }            
                
                if ( $upload_errors !== FALSE)
                    $this->info->add($upload_errors);
                else
                    $this->info->add("add");

                redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );                
            }             
             
            $data['item'] = new stdClass();
            $data['item']->group_id = $parent_group;
            $data['state'] = 'create';
            // добавим группы
            $data['groups'] = [];
            $groups = $this->Currency_exchange_model->get_full_list_payment_systems_groups(-1);
            if (!empty($groups))
                foreach ($groups as $group)
                    $data['groups'][$group->id] = $group->human_name;
            // добавим валюты
            $data['currencies'] = [];
            $cl = $this->Currency_model->get_currency_list();
            if (!empty($cl))
                foreach ($cl as $c)
                    $data['currencies'][$c->code] = $c->short_name;
            
            $this->content->view('currency_exchange_paysys_ps', 'Платежные системы', $data);  
            
     }    
     
     /**
      * Удалить платежну систему
      * @param type $id
      */
     public function ps_delete($id = 0){
         if (!empty($id)){
             $this->Currency_exchange_model->remove_payment_system( $id );
             $this->info->add("delete_yes");
         } else {
            $this->info->add("Не указан идентификатор");
         }
         redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );                
     }
     
     
    /**
     * Action: редактирование группы
     */
     public function group($id=0){
         if ( !empty($id)){
          // save 
            if (!empty($_POST['submited'])) {

                $data['parent_id'] = $this->input->post('parent_id');
                $data['machin_name']= $this->input->post('machin_name');
                $data['human_name']= $this->input->post('human_name');
                $data['public_path_to_icon']= $this->input->post('public_path_to_icon');
                $data['language_id']= $this->input->post('language_id');
                $data['show_add_new']= $this->input->post('show_add_new');
                $data['order']= $this->input->post('order');

                if ( $data['show_add_new'] === NULL)
                    $data['show_add_new'] = 0;
                
                // загрузка иконки
                $data['public_path_to_icon'] =  $this->_load_icon($data['public_path_to_icon'], $upload_errors);
                
                $res = $this->Currency_exchange_model->edit_payment_system_group($id, $data );
                // если при сохранение произошла какая либо ошибка, то покажем ее
                if ( $res === FALSE ){
                    $this->data['item'] = (object)$_POST;
                    $this->data['state'] = $id;
                    $this->info->add($this->Currency_exchange_model->get_last_error());
                    $this->content->view('currency_exchange_paysys_group', 'Платежные системы', $data);  
                    return;
                }            
                if ( $upload_errors !== FALSE)
                    $this->info->add($upload_errors);
                else
                    $this->info->add("edit");
                redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );              
            }
            
            
            $data['item'] = $this->Currency_exchange_model->get_payment_systems_group($id);
            // добавим группы
            $data['groups'] = [ 0 => 'Нет' ];
            $groups = $this->Currency_exchange_model->get_full_list_payment_systems_groups(-1);
            if (!empty($groups))
                foreach ($groups as $group)
                    if ( $group->id != $id )
                        $data['groups'][$group->id] = $group->human_name;
            
            $this->content->view('currency_exchange_paysys_group', 'Платежные системы', $data);  
         }
     }     

     /**
     * Action: создание группы
     */
     public function group_create($parent_group = 0){
            // save 
            if (!empty($_POST['submited'])) {

                $data['parent_id'] = $this->input->post('parent_id');
                $data['machin_name']= $this->input->post('machin_name');
                $data['human_name']= $this->input->post('human_name');
                $data['public_path_to_icon']= $this->input->post('public_path_to_icon');
                $data['language_id']= $this->input->post('language_id');
                $data['show_add_new']= $this->input->post('show_add_new');
                $data['order']= $this->input->post('order');

                if ( $data['show_add_new'] === NULL)
                    $data['show_add_new'] = 0;
                
                // загрузка иконки
                $data['public_path_to_icon'] =  $this->_load_icon($data['public_path_to_icon'], $upload_errors);


                $res = $this->Currency_exchange_model->create_payment_system_group( $data );
                // если при сохранение произошла какая либо ошибка, то покажем ее
                if ( $res === FALSE ){
                    $this->data['item'] = (object)$_POST;
                    $this->data['state'] = $id;
                    $this->info->add($this->Currency_exchange_model->get_last_error());
                    $this->content->view('currency_exchange_paysys_group', 'Платежные системы', $data);  
                    return;
                }            
                
                if ( $upload_errors !== FALSE)
                    $this->info->add($upload_errors);
                else
                    $this->info->add("add");

                redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );                
            }             
             
            $data['item'] = new stdClass();
            $data['item']->parent_id = $parent_group;
            $data['state'] = 'create';
            // добавим группы
            $data['groups'] = [ 0 => 'Нет' ];
            $groups = $this->Currency_exchange_model->get_full_list_payment_systems_groups(-1);
            if (!empty($groups))
                foreach ($groups as $group)
                        $data['groups'][$group->id] = $group->human_name;
            
            
            $this->content->view('currency_exchange_paysys_group', 'Платежные системы', $data);  
            
     }    
     
     /**
      * Удалить группу платежной системы
      * @param type $id
      */
     public function group_delete($id = 0){
         if (!empty($id)){
             $res = $this->Currency_exchange_model->remove_payment_system_group( $id );
             if ( $res === TRUE)
                $this->info->add("delete_yes");
             else 
                 $this->info->add("delete_no");
         } else {
            $this->info->add("Не указан идентификатор");
         }
         redirect(  base_url() . 'opera/currency_exchange_paysys/all'  );                
     }
     
     /**
      * Установить состония добавления новых платежных систем
      * @param type $id
      */
     public function new_set_ps_state($id=0){
         if ( !empty($id) && isset($_POST['state']) ){
            $state = $_POST['state'];
            $this->Currency_exchange_model->set_new_user_payment_system_state( $id, $state ); 
            echo 'OK';
         }
     }
 
     
    /**
      * Установить состония добавления новых платежных систем
      * @param type $id
      */
     public function new_delete($id=0){
         if ( !empty($id) ){
            $this->Currency_exchange_model->delete_new_user_payment_system( $id ); 
            echo 'OK';
         }
     }
      
 
}