<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
if(!class_exists('Table_controller')){ require APPPATH.'libraries/Table_controller.php';}
//extends Admin_controller
                           //extends Table_controller{
class Currency_exchange_chat extends Table_controller
{

    public function __construct()
    {
        parent::__construct();
        
        admin_state();
//        $this->load->model( 'users_model', 'user' );
        $this->load->model( 'currency_exchange_model', 'currency_exchange');
        $this->load->library( 'code' );
        $this->load->helper( 'form' );
        
        $this->cols = array(
            'id' => "int",
            'user_id' => "int",
            'user_id' => "int",
        );

        $setting = array(
            'ctrl' => 'currency_exchange_chat', 
            'view' => 'currency_exchange_chat', 
            'table' => 'currency_exchange_problem_chat', 
            'argument' => array_keys($this->cols));
        
        
        $this->header = array(
            "title" => "Управление",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "order_id" => array(
                    "title" => "Заявка",
                    "filter" => true,
                    "order" => true,
                ),
                "user_id" => array(
                    "title" => "ID пользователя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "s_human_name" => array(
                    "title" => "Тема",
                    "filter" => true,
                    "order" => true,
                ),
                
                "text" => array(
                    "title" => "Текст",
                    "filter" => true,
                    "order" => true,
                ),
                
                "date" => array(
                    "title" => "Дата",
                    "filter" => true,
                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'date',
//                                "widget_options" => array(
//                                    "formatte" => "D/M/Y"
//                                )
//                    )
                ),
                
                "status" => array(
                    "title" => "Статус",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                0 => "<center><img src='images/icons/152.png' /></center>",
                                1 => "<center><img src='images/icons/151.png' /></center>"
                                )
                        )
                    )
                ),
                
//                "summa" => array(
//                    "title" => "Сумма",
//                    "filter" => true,
//                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'currency',
////                                "widget_options" => array(
////                                    cur_format: ' 0,0[.]00',
////                                    cur_symbol: '$',
////                                    language: 'en',
////                                    force_number: false
////                                )
//                    )
//                ),
//                "metod" => array(
//                    "title" => "Метод пополнения",
//                    "filter" => true,
//                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => getTransactionsMethodLabel(false, true)
//                        )
//                    )
//                ),
//                "bonus" => array(
//                    "title" => "Бонус",
//                    "filter" => true,
//                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => array(
//                                1 => 'Да',
//                                2 => 'Нет'
//                            )
//                        )
//                    )
//                ),
//                "status" => array(
//                    "title" => "Статус", // table title, if false no column title displayed,
//                    // its work if all column title is false
//                    "filter" => true, // column filter, placeholder: input field placeholder
//                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => getTransactionStatuses()
//                        )
//                    )
//                ),
//                "time" => array(
//                    "title" => "Срок", // table title, if false no column title displayed,
//                    // its work if all column title is false
//                    "filter" => true, // column filter, placeholder: input field placeholder
//                    "order" => true
//                ),
//                "note" => array(
//                    "title" => "Транзакция",
//                    "filter" => true,
//                    "order" => true,
//                ),
//                "note_admin" => array(
//                    "title" => "Заметки админа",
//                    "filter" => true,
//                    "order" => true,
//                ),
//                "user_ip" => array(
//                    "title" => "IP адрес",
//                    "filter" => true,
//                    "order" => false,
//                ),
            ),
        );
        
        ##################################33
    }
    
    public function all() {
        
        $this->_tableWork_();
        
        $this->data["url"] = "/opera/currency_exchange_chat/all";
        
        $this->data["controller"] = 'currency_exchange_chat/message';
        
        $title = 'Чат проблемы';
        
        $this->content->view('currency_exchange_chat_all', $title, $this->data);
    }
    
    public function message($id, $type = null)
    {
        if($this->input->post('problem_submit') == 1)
        {
//            $one = $this->currency_exchange->get_problem_chat_message_by_id($id);
//            if($one->order_id == $this->input->post('exchange_id') && $this->input->post('to'))
//            {
//                $exchange_id = $this->input->post('exchange_id');
//                $user_id = 0;
//                $subject_id = 0;
//                $subject = '';
//                $problem_text = $this->input->post('msg_body');
//                $image_place = '';
//                $add_foto = '';
//                $operator = 1;
//                $suport_team_operator_id = $this->admin_info->id_user;
//                $to = $this->input->post('to');
//                
//                $res = $this->currency_exchange->get_order_arhiv_by_id($exchange_id);
//                
//                $this->db->trans_start();
//                
//                $id_message = $this->currency_exchange->add_to_problem_chat($exchange_id, $user_id, $subject_id, $subject, $problem_text, $image_place, $add_foto, $operator, $to, $suport_team_operator_id);
//              
//                if($res->seller_user_id == $to)
//                {
//                    $this->currency_exchange->send_mail_by_arhiv_order_id($exchange_id, 'operator_send_message', $to);
//                }
//                else
//                {
//                    $this->currency_exchange->send_mail_by_arhiv_order_id($exchange_id, 'operator_send_message', $res->seller_user_id, TRUE);    
//                }
//                $this->db->trans_complete();
//                
//                $this->info->add("edit");
//            }
            if( $type == 'new' ){
                $order = $this->currency_exchange->get_archive_order_by_id($id);
                
                $add_res = $this->_add_operator_multi_chat_message(0, $order->original_order_id, $order->id, 5);

                if($add_res !== TRUE)                
                    $add_res = $this->_add_operator_chat_message(0, false, $order->original_order_id, $order->id, 5);
                
            }else
            {
                $order = $this->currency_exchange->get_order_arhiv_by_id($this->input->post('exchange_id'));
                $add_res = $this->_add_operator_multi_chat_message($id, $order->original_order_id);
                //$add_res = $this->_add_operator_multi_chat_message($id);

                if($add_res !== TRUE)
                {
                    $add_res = $this->_add_operator_chat_message($id, false);
                }
            }
            if($add_res === TRUE)
            {
                $this->info->add("edit");
            }
            else
            {
                $this->info->add("error");
            }
            
        }
        
        list($one_message, $messages, $user_data) = $this->currency_exchange->get_all_order_message_by_one_message_id($id);
        
        if( ( empty($one_message) || empty($messages) ) &&  $type === null )
        {
            echo 'Не удалось найти сообщение чата';
            return;
        }
        
        if( $type == 'new' )
        {
            $messages = null;
            $one_message = null;
            
            $user_data = $this->currency_exchange->create_chat($id);
            if( empty( $user_data ) )
            {
                echo "Заявка не сведена. Чат не может быть создан";
                return false;
            }
            $problem_chat = $this->currency_exchange->get_problem_chat_by_order_id($id);
            
            if( !empty($problem_chat) ) redirect( site_url( '/opera/currency_exchange_chat/message/'.$problem_chat->id ) );            
        }
        
        if( count( $user_data ) > 1 )
        {
            $chat_users_all = 'all';
            foreach ($user_data as $one){
                $chat_users_all .= '_'.$one->id_user;
            }
        }
        
        if( $type == null )
        {
            $this->currency_exchange->set_show_to_admin_chat($id);
            $messages_temp = $messages;

            $count = 0;
            $messages = [];

            foreach($messages_temp as $mes)
            {
                if($mes->group_operator_message)
                {
                    $messages[$mes->group_operator_message] = $mes;
                }
                else
                {
                    $messages[$count] = $mes;
                }

                $count++;    
            }

    //        pred($messages);

            $this->data['one_message'] = $one_message;

            $order = $this->currency_exchange->get_order_arhiv_by_id($one_message->order_id);        
            

            $this->data['one_message']->original_order_id = FALSE;
            $this->data['one_message']->archive_order_id = FALSE;
            if( !empty( $order ) )
            {
//                echo "-1-";
                $message_user_id = 0;
                if( !empty( $one_message->user_id ) ){
                    $message_user_id = $one_message->user_id;
//                    echo "-2-";
                }
                else
                    if( !empty( $one_message->to ) ){
                        $message_user_id = $one_message->to;
//                        echo "-3-";
                    }
                
                //$o_order = $this->currency_exchange->get_original_order_by_id($order->original_order_id);

                //проверяем оригинал - если контрагент не совпадает, значит эта заявка в архиве или у оригинала несколько архивных заявок
//                if( $o_order && ($message_user_id == $o_order->seller_user_id || $message_user_id == $o_order->buyer_user_id ) )
//                {
                    //echo "-4-";
                    $this->data['one_message']->original_order_id = $order->original_order_id;
//                }else//проверяем, что архивная содержит ИД пользователя, который пишет в чат
//                    if( $message_user_id == $order->seller_user_id || $message_user_id == $order->buyer_user_id )
//                    {
//                        $this->data['one_message']->archive_order_id = $order->id;
//                        echo "-5-";
//                    }
            }
            

            $this->data['messages'] = $messages;
        }
        $this->data['chat_users'] = $user_data;        
        $this->data['chat_users_all'] = $chat_users_all;

        
        $this->content->view('currency_exchange_chat_message', '', $this->data);
    }
    
    public function close_chat($id)
    {
        
        $this->currency_exchange->hide_admin_chat_by_order_id($id);
        
        
        redirect( '/opera/currency_exchange_chat/all' );
    }
    public function close_chat_order_arhiv($id)
    {
        $this->currency_exchange->hide_admin_chat_by_archive_order_id($id);
        
        redirect( '/opera/currency_exchange_chat/all' );
    }
    
    protected function _tableWork_()
    {
        if (isset($_POST["def"])) 
        {
            echo $this->_tableHeader4Array($this->header);
            die;
        }
        
        if(isset($_POST["offset"]))
        {
            $r = array("count" => "", "rows" => array(), "sql" => "");
            
            $where = 'operator = 1 AND show_operator = 1';
//            $where = 'operator = 1 AND suport_team_operator_id = 0 AND show = 0';
            
            $res = $this->currency_exchange->getAllChatGroupChatID( $where);
            
            echo $res;
            die;
        }
    }
    
    public function close_inactive_chats()
    {
        $this->currency_exchange->close_inactive_chats();
        redirect( site_url('/opera/currency_exchange_chat/all') );
    }
    
    private function _add_operator_chat_message($id, $group_operator_message = false, $orig_order_id = 0, $exchange_id = 0, $subject_id = 0)
    {
        $one = $this->currency_exchange->get_problem_chat_message_by_id($id);
        
        if($one->order_id != $this->input->post('exchange_id') || !$this->input->post('to'))
        {
            return false;
        }
        
        if( empty( $exchange_id ) ) $exchange_id = $this->input->post('exchange_id');
        $user_id = 0;
        
        $subject = '';
        $problem_text = $this->input->post('msg_body');
        $image_place = '';
        $add_foto = '';
        $operator = 1;
        $suport_team_operator_id = $this->admin_info->id_user;
        $to = $this->input->post('to');

        $res = $this->currency_exchange->get_order_arhiv_by_id($exchange_id);

        $this->db->trans_start();

        $id_message = $this->currency_exchange->add_to_problem_chat($exchange_id, $user_id, $subject_id, $subject, $problem_text, $image_place, $add_foto, $operator, $to, $suport_team_operator_id, $orig_order_id);
        
        
        if($group_operator_message !== false)
        {
            $this->db
                ->where('id', $id_message)                
                ->update($this->currency_exchange->table_problem_chat, array('group_operator_message' => $group_operator_message));
        }
        
        if($res->seller_user_id == $to)
        {
            $this->currency_exchange->send_mail_by_arhiv_order_id($exchange_id, 'operator_send_message', $to);
        }
        else
        {
            $this->currency_exchange->send_mail_by_arhiv_order_id($exchange_id, 'operator_send_message', $res->seller_user_id, TRUE);    
        }

        $this->db->trans_complete();

        $this->load->model('monitoring_model', 'monitoring');
        $this->monitoring->log(null, "Р2Р::Добавлен комментарий в чат {$id}", 'private');
        
        return true;
    }
    
    //$this->_add_operator_chat_message(0, false, $order->original_order_id, $order->id, 5)
    private function _add_operator_multi_chat_message($id, $original_order_id = 0, $order_id = 0, $subject_id = 0 )
    {
        if(!$this->input->post('to'))
        {
            return false;
        }
        
        $res_split = preg_split('/_/', $this->input->post('to'));
        
        if(!isset($res_split[0]) || $res_split[0] != 'all')
        {
            return false;
        }
        
        unset($res_split[0]);
        $this->db->trans_start();
        
        $group_operator_message = md5($id.time().rand(111, 999).rand(111, 999).rand(111, 999));
                
        foreach($res_split as $one)
        {
            $_POST['to'] = $one;
            
            $add_mess = $this->_add_operator_chat_message($id, $group_operator_message, $original_order_id, $order_id, $subject_id);
            
            if($add_mess === FALSE)
            {
                return false;
            }
        }
        
        $this->db->trans_complete();
        
        return true;
//        pred($res_split);
    }
}
