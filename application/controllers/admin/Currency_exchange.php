<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
if(!class_exists('Table_controller')){ require APPPATH.'libraries/Table_controller.php';}
//extends Admin_controller
                           //extends Table_controller{
class Currency_exchange extends Table_controller
{
    public function __construct()
    {
        parent::__construct();

        admin_state();
//        $this->load->model( 'users_model', 'user' );
        $this->load->model( 'currency_exchange_model', 'currency_exchange');
        $this->load->library( 'code' );
        $this->load->helper( 'form' );

        $this->table = $this->currency_exchange->table_orders;
        $this->table_arhiv = $this->currency_exchange->table_orders_arhive;

        $this->cols = array(
            'id' => "int",
            'seller_user_id' => "int",
            'seller_amount' => "float",
            'buyer_amount_down' => "float",
            'seller_set_up_date' => "datetime",
            'status' => "text",
            'bonus' => "int"
        );

        $setting = array(
            'ctrl' => 'currency_exchange',
            'view' => 'currency_exchange',
            'table' => $this->currency_exchange->table_orders,
            'argument' => array_keys($this->cols));


        $this->header = array(
            "title" => "Список заявок",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => true,
                    "order" => true,
                ),
                "seller_user_id" => array(
                    "title" => "ID пользователя",
                    "filter" => true,
                    "order" => true,
                //                    "html_tag_attr" => array("data-dtable" => "order.asc", "data-dtable-column" => "{{ column_id }}")
                ),
                "seller_amount" => array(
                    "title" => "Сумма",
                    "filter" => true,
                    "order" => true,
                ),

                "buyer_amount_down" => array(
                    "title" => "К получению",
                    "filter" => true,
                    "order" => true,
                ),

                "seller_set_up_date" => array(
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
                "bonus" => array(
                    "title" => "Bonus",
                    "filter" => true,
                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'list',
//                        "widget_options" => array(
//                            "list" => []
//                        )
//                    )
                ),
                "status" => array(
                    "title" => "Статус",
                    "filter" => true,
                    "order" => false,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => getCurExchStatuses()
                        )
                    )
                ),


//
            ),
        );

        ##################################33



    }

//    public function all()
//    {
//        $cols = $this->cols;
//        $cols['id'] = "int";
//        $header = $this->header;
//        $table = $this->table;
//        $this->_tableWork("payout", compact(array("cols", "table", "header")));
//
//        $this->data["url"] = "/opera/payment/payout";
//
//        $this->data["search_text"] = '';
////        $this->data["view_all"] = '';
//        if ($user_id != null)
//            $this->data["search_text"] = $user_id;
//        $title = '';
//        $this->content->view('payout', $title, $this->data);
//    }

    public function get_order()
    {
        $order_id = $this->input->post('order_id',true);
        $user_id = $this->input->post('user_id',true);
        $action = $this->input->post('action',true);

        $error = '';
        if( !empty( $order_id ) && !empty( $user_id ) )
        {
            if( $action == 'search' )
            {

                $this->load->model('users_model', 'user');

                $this->user->setUserId($user_id);
                $data2 = $this->user->getUser($user_id);

                viewData()->user = $data2['user'];
            }
            if( $action == 'buy' )
            {
                $order = $this->currency_exchange->get_original_order_by_id( $order_id );
                if( empty( $order ) )
                {
                    $error = "Empty order data!";
                }
                elseif( $order->status > 10 )
                {
                    $error = "Order status is $order->status can't be taken.";
                }
                else
                {

                    $data = [];


                    if( count( $order->sell_payment_data_un ) != 1 )
                    {
                        $error = "This order has more thatn 1 payment system";
                    }
                    else{
                        $ps_id = array_keys($order->sell_payment_data_un);

                        $data['select_payment_systems_sell'] = $order->sell_payment_data_un[$ps_id[0]]->payment_system_id;
                        $data['sell_only_wt'] = 1;
                        $data['correct'] = 1;
                        $data['exchange_id'] = $order_id;

                        $data['purpose'] = $purpose = 'create_get_p2p_orders';

                        $this->load->model('phone_model', 'phone');
                        $code = $this->phone->setPhoneCode( $purpose, $user_id );
                        if( $code['success'] )
                        {
                            $data['code'] = $code['code'];

                            viewData()->call_buy_data = json_encode($data);
                            $this->currency_exchange->set_status_to_details_orig_order($order_id,10);
                        }
                    }
                }
            }
        }

        viewData()->user_id = $user_id;
        viewData()->error = $error;
        viewData()->order_id = $order_id;
        $this->content->view('currency_exchange_get_order', '', (array)viewData());
    }

    public function order($id)
    {
	$this->output->enable_profiler(TRUE);
        $this->load->model('transactions_model','transactions');
        $this->load->model('users_model','users');
        $this->load->model('accaunt_model','accaunt');

        ini_set('memory_limit','2500M');
        $item = $this->currency_exchange->get_original_order_by_id($id);

        if( count( $item ) > 1 )
            $item = $item[ count( $item ) - 1 ];


        $archive = FALSE;
        if( !empty( $item ) ) $this->data['original_order_id'] = $item->id;
        else
            $this->data['original_order_id'] = 0;

        //<editor-fold desc="Getting archive orders" defaultstate="collapsed">
        $arch_item = $this->currency_exchange->get_arhiv_orders_by_parent_id_and_status($id,null);
        $count = count( $arch_item );
        $this->data['archive_order_ids'] = array();
        if( $count > 1 )
        {
            $this->data['archive_order_ids'] = array();

            $this->data['archive_order_ids'][] = $arch_item[$count-1]->id;
            $this->data['archive_order_ids'][] = $arch_item[$count-2]->id;

            if( empty( $this->data['original_order_id'] ) ) $this->data['original_order_id'] = $arch_item[$count-2]->original_order_id;

        }
        $arch_item = $arch_item[ $count - 1 ];//всегда получаем массив заявок

        if(!isset($arch_item->payment_systems) && !empty($arch_item->order_details_arhiv))
        {
            $arch_item->payment_systems = unserialize($arch_item->order_details_arhiv);
        }
        $arch_item = $this->currency_exchange->set_fee_to_order($arch_item);
        $arch_item = $this->currency_exchange->set_machin_name_to_payment_systems($arch_item);
        $arch_item = $this->currency_exchange->set_dop_data_to_unserializ_payment_system($arch_item);
        //</editor-fold>

        if( empty( $item ) )
        {
            $archive = TRUE;
            $item = $arch_item;
        }
        elseif( $item->status >= 60 )
        {
            $archive = TRUE;
            $item_arhiv = $this->currency_exchange->get_arhiv_orders_by_parent_id_and_status($id,$item->status);

            if(!empty($item_arhiv))
            {
                $count = count( $item_arhiv );

                if( $count > 1 )
                {
                    $this->data['archive_order_ids'] = array();

                    $this->data['archive_order_ids'][] = $item_arhiv[$count-1]->id;
                    $this->data['archive_order_ids'][] = $item_arhiv[$count-2]->id;
                    if( empty( $this->data['original_order_id'] ) ) $this->data['original_order_id'] = $item[$count-2]->original_order_id;
                }
                $item_arhiv = $item_arhiv[ $count - 1 ];//всегда получаем массив заявок

                if(!isset($item_arhiv->payment_systems) && !empty($item_arhiv->order_details_arhiv))
                {
                    $item_arhiv->payment_systems = unserialize($item_arhiv->order_details_arhiv);
                }
                $item_arhiv = $this->currency_exchange->set_fee_to_order($item_arhiv);
                $item_arhiv = $this->currency_exchange->set_machin_name_to_payment_systems($item_arhiv);
                $item_arhiv = $this->currency_exchange->set_dop_data_to_unserializ_payment_system( $item_arhiv );


                $item = $item_arhiv;
            }
        }


        $item = $this->currency_exchange->set_fee_to_order($item);
            $item = $this->currency_exchange->set_machin_name_to_payment_systems($item);
            $item = $this->currency_exchange->set_dop_data_to_unserializ_payment_system($item);

         if( !isset( $item->bonus ) ) $item->bonus = 5;

        $this->data['item'] = $item;
        $this->currency_exchange->get_operator_notes( $this->data['item'] );
        $this->currency_exchange->get_operator_reject_notes( $this->data['item'] );
        if( empty( $item->seller_user_id ) )
        {
            echo "Empty user_id";

            return;
        }

//        $seller_active_p2p = $this->currency_exchange->currency_exchange_scores( $this->data['item']->seller_user_id );

        $seller_active_p2p = $this->accaunt->recalculateUserRating( $item->seller_user_id );

        $this->data['item']->seller_active_p2p = $seller_active_p2p['total_processing_p2p_by_bonus'];
        $this->data['item']->seller_payout_limit = $seller_active_p2p['payout_limit_by_bonus'];
        $seller_name = $this->users->getUserDataFields( $item->seller_user_id ,array('name','sername'));
        $this->data['item']->seller_name = $seller_name->sername . ' '.$seller_name->name;


        $buyer_payment_data = unserialize( $item->buy_payment_data );
        $this->data['item']->buyer_payment_data = 0;
        if( isset( $buyer_payment_data[0] ) )
        {
//            $this->data['item']->buyer_payment_data = $buyer_payment_data[0]->payment_data;
            $this->data['item']->seller_payment_data = Currency_exchange_model::get_ps_data_for_table(Currency_exchange_model::get_ps($buyer_payment_data[0]->payment_system_id), $buyer_payment_data[0]->payment_data, ['template' => 'template_admin' ]);
        }

        $seller_payment_data = unserialize( $item->seller_payment_data );

        $this->data['item']->seller_payment_data = 0;
        if( isset( $seller_payment_data[0] ) )
            $this->data['item']->seller_payment_data = $seller_payment_data[0]->payment_data;

        if( empty( $this->data['item']->seller_payment_data ) )
        {

            $item2 = $this->currency_exchange->get_arhiv_orders_by_parent_id_and_status($id,null);
            $seller_payment_data = unserialize( $item2[0]->sell_payment_data );
//            pred($seller_payment_data);
            if( isset( $seller_payment_data[0] ) )
            {
//                $this->data['item']->seller_payment_data = $seller_payment_data[0]->payment_data;
                $this->data['item']->seller_payment_data = Currency_exchange_model::get_ps_data_for_table(Currency_exchange_model::get_ps($seller_payment_data[0]->payment_system_id), $seller_payment_data[0]->payment_data, [/*'order' => $item, 'curent_user' => $user_id, */'template' => 'template_admin' ]);
            }

        }
//        vred($this->data['item']->seller_payment_data);

        $this->data['item']->buyer_blocked = FALSE;
        $this->data['item']->buyer_name = 'Заявка<br> не сведена';
        if( !empty( $this->data['item']->buyer_user_id ) )
        {
//            $buyer_active_p2p = $this->currency_exchange->currency_exchange_scores( $this->data['item']->buyer_user_id );

            $buyer_active_p2p = $this->accaunt->recalculateUserRating( $item->buyer_user_id );
            //$buyer_active_p2p_amount = $buyer_active_p2p['net_processing_p2p'] + $buyer_active_p2p['total_processing_p2p'];
            $this->data['item']->buyer_active_p2p = $buyer_active_p2p['total_processing_p2p_by_bonus'];
            $this->data['item']->buyer_payout_limit = $buyer_active_p2p['payout_limit_by_bonus'];

            $buyer_name = $this->users->getUserDataFields( $item->buyer_user_id ,array('name','sername'));
            $this->data['item']->buyer_name = $buyer_name->sername . ' '.$buyer_name->name;


            $buyer_item = $this->currency_exchange->get_archive_order_by_id( $id, FALSE, array('seller_user_id' => $item->buyer_user_id ) );
            $this->data['item']->buyer_ip = $buyer_item->seller_ip;
            $this->data['buyer_item'] = $buyer_item;
            $this->data['item']->buyer_blocked = $this->users->isUsersActive($item->buyer_user_id);
        }




        $this->data['item']->is_order_have_visa = 22;
        if($this->currency_exchange->is_have_visa_in_order($id)) {
            $this->data['item']->is_order_have_visa = 1;

            $this->load->model('Card_prefunding_transactions_model', 'prefund');

            $prefunds = $this->prefund->get_by_order_id($id);
            $prefund_status = 'Ошибка в префанде';
            if($this->prefund->is_paired_free($prefunds)) {
                $prefund_status = 'Префанд успешно зачислен';
            }
            if(empty($prefunds)) {
                $prefund_status = 'Префанд пуст';
            }
            $this->data['item']->prefund_status = $prefund_status;

        }





        $this->data['item']->seller_blocked = FALSE;
        if( !empty( $this->data['item']->seller_user_id ) ) $this->data['item']->seller_blocked = $this->users->isUsersActive($this->data['item']->seller_user_id);

        $back_url = $this->input->post('back_url',TRUE);
        //$order_url = $this->input->post('order_url',TRUE);
        $go_back_to_all = $this->input->post('go_back_to_all',TRUE);

//        pred($buyer_item);

        if (!empty($_POST['submited']))
        {


            $redirect_url = !empty( $go_back_to_all ) ?'/opera/currency_exchange/all':$_POST['back_url'];
//            if($this->data['item']->status == Currency_exchange_model::ORDER_STATUS_SUCCESS)
//            {
//                $this->info->add("Незвожно изменить данные заявки в Успешном статусе");
//                redirect($redirect_url);
//            }

            //$id = $this->input->post('id', TRUE);

            $original_order_id = $this->input->post('original_order_id', TRUE);
            $archive_order_ids = $this->input->post('archive_order_ids', TRUE);
            if( empty( $id ) ) $id = $original_order_id;

            $seller_user_id = $this->input->post('seller_user_id', TRUE);
            $buyer_user_id = $this->input->post('buyer_user_id', TRUE);

            $date_modified = date('Y-m-d H:i:s');

            $orders = array();
            if( !empty( $archive_order_ids ) && !empty( $archive_order_ids[0] ) && !empty( $archive_order_ids[1] ) ){
                $orders[] = $this->currency_exchange->get_archive_order_by_id($archive_order_ids[0]);
                $orders[] = $this->currency_exchange->get_archive_order_by_id($archive_order_ids[1]);
            }

            $error = 'error';
            //Отменить - работает только для активных заявок или
            if($this->input->post('reject', TRUE) == 1)
            {






                $reject_text = $this->input->post('reject_text', TRUE);
                $reject_text_to_user = $this->input->post('reject_text_to_user', TRUE);
                if( empty( $reject_text ) ){
                    $error = 'Операция не проведена. Поле причины для оператора должно быть заполнено.';
                    goto exit_from_reject_part;
                }

                //получаем список архивных заявок
//                $orders = $this->currency_exchange->get_arhiv_orders_by_parent_id($id);

                $res = FALSE;
                $this->db->trans_start();

                if(empty($orders))//несведенная заявка
                {



                    // Если в заявке есть виза.
                    if($this->currency_exchange->is_have_visa_in_order($id)) {

                        $this->load->model('Card_prefunding_transactions_model', 'prefund');

                        // Проверяем что в этой заявке есть точно свободные деньги в префанде.
                        $prefunds = $this->prefund->get_by_order_id($id);
                        if($this->prefund->is_paired_free($prefunds) && count($prefunds) == 2 ) {
                            $res_pref = $this->currency_exchange->prefund_delete_from_admin($id);
                            if($res_pref === true) {
                                //$this->currency_exchange->set_status_operator_canceled($id);
                            } else {
                                $res = false; // Если указать false то оператору выведет наше сообщение.
                                $error = 'Невозможно отменить заявку с возратом денег пользователю из префанда.';
                                goto exit_from_reject_part;
                            }

                        }

                    }



                    $res = $this->currency_exchange->set_status_operator_canceled($id);
                    $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $id, 'text' => $reject_text, 'date_modified' => $date_modified, 'text_to_user' => $reject_text_to_user ) );
                    $this->currency_exchange->send_mail($id, 'order_processing_reject', $seller_user_id);


                }
                else //сведенная заявка
                {
                    $order = $orders[1];
                    if( $orders[1]->seller_user_id == $seller_user_id )
                        $order = $orders[0];

                    //если заявка завершена - значит ничего не делаем
                    if( $order->status == Currency_exchange_model::ORDER_STATUS_SUCCESS )
                    {
                        $error = 'Заявка со статусом завершена не может быть отклонена.';
                        goto exit_from_reject_part;
                    }
                    $status = Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED;

                    //поставить заявку контрагента в статус - отклонена
//                    echo "поставить заявку контрагента в статус - отклонена<br>";
                    $seller_archive_order_id = $order->id;

                    //если есть оригинальная = меняем ей статус
                    if( FALSE == $this->currency_exchange->set_status_to_orig_order( $seller_archive_order_id, $status ) )
                    {
                        $error = 'Операция отменена. Невозможно изменить статус оригинальной заявке.';
                        goto exit_from_reject_part;
                    }

                    if( FALSE == $this->currency_exchange->set_status_archive_orders( $seller_archive_order_id, $status ) )
                    {
                        $error = 'Операция отменена. Невозможно изменить статус архивных заявок.';
                        goto exit_from_reject_part;
                    }

                    //поставить заявку инициатора в статус - отклонена
//                    echo "поставить заявку инициатора в статус - отклонена<br>";
                     $buyer_archive_order_id = $order->buyer_order_id;
                    if( FALSE == $this->currency_exchange->set_status_archive_orders( $buyer_archive_order_id, $status ) )
                            goto exit_from_reject_part;

                    //добавить причины отказа
                    if( FALSE == $this->currency_exchange->add_operator_reject_note( array( 'order_id' => $seller_archive_order_id, 'text' => $reject_text, 'date_modified' => $date_modified ) ) )
                    {
                        $error = 'Операция отменена. Не удалось сохранить причину отказа. Сообщите разработчику о проблеме.';
                            goto exit_from_reject_part;
                    }
                    if( FALSE == $this->currency_exchange->add_operator_reject_note( array( 'order_id' =>$buyer_archive_order_id, 'text' => $reject_text, 'date_modified' => $date_modified ) ) )
                    {
                        $error = 'Операция отменена. Не удалось сохранить причину отказа контрагенту. Сообщите разработчику о проблеме.';
                        goto exit_from_reject_part;
                    }
                    //оригинальную заявку поставить в статус - Выставлена
//                    echo "оригинальную заявку поставить в статус - Выставлена<br>";
                    if( FALSE == $this->currency_exchange->set_status_set_out($original_order_id) )
                    {
                        $error = 'Операция отменена. Не удалось поставить заявку в статус выставлена. Сообщите разработчику о проблеме.';
                            goto exit_from_reject_part;
                    }

                    if( FALSE == $this->currency_exchange->clean_buyer_data($original_order_id) )
                    {
                        $error = 'Операция отменена. Не удалось обнулить поле покупатель. Сообщите разработчику о проблеме.';
                        goto exit_from_reject_part;
                    }
                    //отправить письмо контрагенту об отклонении заявки
//                    echo "отправить письмо контрагенту об отклонении заявки<br>";
                    $this->currency_exchange->send_mail($order->buyer_order_id, 'order_processing_reject', $order->buyer_user_id);

                    //отправить письмо инициатору об отклонении заявки и выставлении оригинальной заявки
//                    echo "отправить письмо инициатору об отклонении заявки<br>";
                    $this->currency_exchange->send_mail($order->id, 'order_processing_reject', $order->seller_user_id);

                    //отправить письмо инициатору об отклонении заявки и выставлении оригинальной заявки
//                    echo "отправить письмо инициатору о выставлении оригинальной заявки<br>";
                    $this->currency_exchange->send_mail($order->original_order_id, 'order_set_active', $order->seller_user_id);

                    //вывести сообщение об успехе операции
                    $res = TRUE;

                }
                $this->db->trans_complete();

        exit_from_reject_part:

                if($res === TRUE) {
                    $this->info->add("edit");
                } else {
                    $this->info->add($error);

                }
                redirect($redirect_url);
            }
            elseif($this->input->post('save', TRUE) == 1) // кнопка сохранить
            {

                $status = $this->input->post('seller_new_order_status', TRUE);
                $note = $this->input->post('admin_note_text');

                //$res = $this->currency_exchange->set_status_to_order_arhive_and_order($id, $status, $status);
                $this->db->trans_start();
//                    $res = $this->currency_exchange->set_status_to_arch_order_by_orig_order_id($seller_user_id, $id, $status);
                    $res = array();
                    $details_id = $id;

                    if( !empty($archive_order_ids) && !empty( $archive_order_ids[0] ) && !empty( $archive_order_ids[1] ) )
                    {
                        $res[] = $this->currency_exchange->set_status_archive_orders( $archive_order_ids[0], $status );
                        $res[] = $this->currency_exchange->set_status_archive_orders( $archive_order_ids[1], $status );

                        //if( !empty($buyer_user_id) ) $res = $this->currency_exchange->set_status_to_arch_order_by_orig_order_id($buyer_user_id, $id, $status);

                        if( !empty( $orders ) )
                        {
                            if( $orders[0]->original_order_id != $details_id ) $details_id = $orders[0]->original_order_id;
                            else
                                if( $orders[1]->original_order_id != $details_id ) $details_id = $orders[1]->original_order_id;
                        }
                    }

                    $res[] = $this->currency_exchange->set_status_to_details_orig_order( $id, $status );

                    $this->currency_exchange->add_operator_note( array( 'order_id' =>$id, 'text' => $note , 'date_modified' => $date_modified ));

                $this->db->trans_complete();


                //

                $res_one = TRUE;
                foreach( $res as $r )
                    if( $res == FALSE )
                    {
                        $res_one = FALSE;
                        break;
                    }

                if( $res_one === TRUE )$this->info->add("edit");
                else
                    $this->info->add("error");

                //$this->load->model('Admin_user_notes_model');
                //$this->Admin_user_notes_model->addNotes($seller_user_id, $this->input->post('admin_note_text') );

              redirect($redirect_url);
            } else
            {
//                $orders = $this->currency_exchange->get_arhiv_orders_by_parent_id($id);
                //$orders = $this->currency_exchange->get_arhiv_orders_by_parent_id_and_status($id, Currency_exchange_model::ORDER_STATUS_PROCESSING);
                $this->db->trans_start();
                $note = $this->input->post('admin_note_text');

                $this->currency_exchange->add_operator_note( array( 'order_id' =>$id, 'text' => $note , 'date_modified' => $date_modified ));

//                pred($orders);
                if(empty($orders))
                {
                    $res = $this->currency_exchange->set_status_set_out($id);
                    $this->currency_exchange->send_mail($id, 'order_processing_confirm', $seller_user_id);
                }
                else
                {
                    $status = Currency_exchange_model::ORDER_STATUS_CONFIRMATION;
                    $curent_arhiv_order_status = Currency_exchange_model::ORDER_STATUS_PROCESSING;
                    $res = $this->currency_exchange->set_status_to_order_arhive_and_order($id, $status, $status, $curent_arhiv_order_status);

                    $orders_temp = $orders;
                    $one_arhiv_order = array_shift($orders_temp);

                    $data_order = array('buyer_confirmation_date' => date('Y-m-d H:i:s'));
                    $res = $this->currency_exchange->set_order_data($one_arhiv_order->seller_user_id, $one_arhiv_order->id, $data_order);
//                    vre($this->db->last_query());
//                    vred($res);
//                    $order_arhiv$this->currency_exchange->get_arhiv_orders_by_parent_id_and_status($id);
//                    seller_confirmation_date

                    //TODO не понятно нужны ли вообще две строки ниже - скоерее всего нет.
                    $orders_arhiv = $this->currency_exchange->_get_user_orders_arhive_details($orders);
                    $arhiv_order = array_shift($orders_arhiv);

                    $this->currency_exchange->send_mail($id, 'order_processing_confirm_without_search', $seller_user_id);
//                    $this->currency_exchange->send_mail($id, 'order_processing_confirm_without_search', $orders_arhiv->buyer_user_id);
                    $this->currency_exchange->send_mail($id, 'order_processing_confirm_without_search', $seller_user_id, true);
//                    vre(2);
                }
//                vred('>>>>');
                $this->db->trans_complete();

                if($res === TRUE)
                {
                    $this->info->add("edit");
                    redirect($redirect_url);
                }
            }

//            $data = $this->_request();
//            if( isset( $data['user_ip'] ) ) unset( $data['user_ip'] );
//            $paySys = payout_systems_type();
//            $payoutSys = $this->input->post('payout_system');
//            if($payoutSys)
//                $data['type'] = $paySys[$payoutSys];
//            $this->db->where($this->id, $id)->update($this->table, $data);
//            if ($this->info_state)
//                $this->info->add("edit");
//            if ($this->index_redirect == false) {
//                redirect($this->all);
//                exit();
//            }
        }

        $payment_systems = $this->currency_exchange->get_all_payment_systems();
        $this->data['payment_systems'] = $payment_systems;

        foreach($payment_systems as $val)
        {
            $payment_systems_id_arr[$val->id] = $val;
        }
//        pred($payment_systems_id_arr);

        $this->data['payment_systems_id_arr'] = $payment_systems_id_arr;

        if($item->wt_set == 1){
            $user_id = $item->seller_user_id;
        }elseif($item->wt_set == 2){
            $user_id = $item->buyer_user_id;
        }

        $this->load->library('code');
        $this->users->setUserId($user_id);
        $data2 = $this->users->getUser($user_id);
//        pred($data2);
        $this->data['user'] = $data2['user'];
        $this->data['operator_cancell_order'] = '/opera/currency_exchange/operator_cancell_order';

        $this->data["double"] = $this->transactions->getDouble($user_id);

        $this->data["url"] = "/opera/currency_exchange/order_arch";
//        $this->data["controller"] = 'currency_exchange/order';

        $order = $this->currency_exchange->get_archive_order_by_id($id);


        $oreders = [];
        if( !empty( $order->seller_order_id ) ) $oreders[] = $order->seller_order_id;
        if( !empty( $order->buyer_order_id ) ) $oreders[] = $order->buyer_order_id;

        //<editor-fold desc="Get problem chat link" defaultstate="collapsed">
        $problem_chat = $this->currency_exchange->get_problem_chat_by_order_id($this->data['original_order_id'], $orders);

        $this->data["problem_chat"] = FALSE;
        $this->data["new_problem_chat"] = "/opera/currency_exchange_chat/message/{$id}/new";

        if( !empty( $problem_chat ) )
        $this->data["problem_chat"] = '/opera/currency_exchange_chat/message/'.$problem_chat->id;


        //</editor-fold>


//        pred($this->data);

        foreach($this->data["item"]->payment_systems as $ps)
        {
            if(!isset($this->data['payment_systems_id_arr'][$ps->payment_system]))
            {
                $this->data['payment_systems_id_arr'][$ps->payment_system] = Currency_exchange_model::get_ps($ps->payment_system);
                $this->data['payment_systems'][$ps->machine_name] = Currency_exchange_model::get_ps($ps->payment_system);
            }
        }

        $this->data["docs_link"] = '/opera/currency_exchange/order_doc/'.$id;
        if( $this->currency_exchange->doc_file_uploaded_by_orig_order_id( $id ) === FALSE ) $this->data["docs_link"] = FALSE;

        if($item->status != Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK && isset($buyer_item) )
        {
            $this->data['operator_last_confirm_action'] = '/opera/currency_exchange/operator_last_confirm_order';
        }

        $this->content->view('currency_exchange_order', '', $this->data);
    }



    public function get_user_orders($order_id) {
        if( empty( $order_id ) )
        {
            echo "Empty parametr";
            return;
        }

        $this->data = [];

        $this->data['orders'] = array('original','archive');

        $this->data['orders']['original'] = $this->currency_exchange->get_original_order_by_id($order_id);
        $this->data['orders']['archive'] = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id,'DESC');

        $this->data['orders'] = array('original','archive');

        $this->data['orders']['original'] = $this->currency_exchange->get_original_order_by_id($order_id);
        $this->data['orders']['archive'] = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id,'DESC');
        foreach( $this->data['orders']['archive'] as $ao )
        {
            if( !empty( $ao->buyer_document_image_path ) && $ao->buyer_document_image_path != 'wt'  )
            {
                $ar = explode('/', $ao->buyer_document_image_path);

                $ao->buyer_document_image = $ar[2];
            }
            if( !empty( $ao->seller_document_image_path ) && $ao->seller_document_image_path != 'wt'  )
            {
                $ar = explode('/', $ao->seller_document_image_path);

                $ao->seller_document_image = $ar[2];
            }
        }

        $this->load->view('admin/_currency_exchange_order_orders',$this->data);
        return FALSE;

    }
    public function get_user_transactions( $order_id ) {

        if( empty( $order_id ) )
        {
            echo "Empty parametr";
            return;
        }

        $this->data = [];

        $this->data['orders'] = array('original' => null,'archive' => null);

        $this->data['orders']['original'] = $this->currency_exchange->get_original_order_by_id($order_id);
        $this->data['orders']['archive'] = $this->currency_exchange->get_archive_orders_by_orig_order_id($order_id,'DESC');

        $this->data['transactions'] = array('seller'=> null,'buyer'=> null, 'coles'=> null);

        $order = $this->currency_exchange->get_archive_order_by_id($order_id,TRUE,null, 'DESC');



        $this->data['original_order_id'] = $order_id;

        if( empty( $order )&&  !isset( $order->seller_user_id ) || !empty( $order->seller_user_id ) || !isset( $order->seller_order_id ))
        {
            $order->seller_order_id = $order->id;
        }


        if( !empty( $this->data['orders'] ) && !empty( $this->data['orders']['archive'] ) )
        {
//            $arch = $this->data['orders']['archive'];
//            for( $i = count( $arch ) - 1; $i >= 0; $i ++  )
//            {
//                $a = $arch[$i];
            if( $order->seller_user_id == $order->buyer_user_id )
            foreach( $this->data['orders']['archive'] as $a )
            {
                if( $a->initiator == 1 )
                {
                    $order->seller_user_id = $a->seller_user_id;
                    $order->seller_order_id = $a->id;
                    continue;
                }

                if( $a->initiator != 1 )
                {
                    $order->buyer_user_id = $a->seller_user_id;
                    $order->buyer_order_id = $a->id;
                    continue;
                }
            }



            $this->load->model('transactions_model','transactions');

//            echo "$order->buyer_user_id, {$this->data['original_order_id']}, $order->buyer_order_id<br>";
//            echo "$order->seller_user_id, {$this->data['original_order_id']}, $order->seller_order_id<br>";

            $this->data['transactions']['buyer'] = $this->transactions->get_currency_exchange_transactions( $order->buyer_user_id, $this->data['original_order_id'], $order->buyer_order_id, FALSE );

            $this->data['transactions']['seller'] = $this->transactions->get_currency_exchange_transactions( $order->seller_user_id, $this->data['original_order_id'], $order->seller_order_id, FALSE );
            $this->data['transactions']['coles'] = $this->transactions->get_currency_exchange_transactions( 90837257, $this->data['original_order_id'], $order->seller_order_id, FALSE );



        }

        $this->load->view('admin/_currency_exchange_order_transactions',$this->data);
        return FALSE;
    }

    public function get_user_prefund_transactions( $order_id ) {
        if( empty( $order_id ) ) {
            echo "Empty parametr";
            return false;
        }

        $this->load->model('Card_prefunding_transactions_model', 'prefund');
        $this->data = [];

        $this->data['prefunds'] = $this->prefund->get_by_order_id($order_id);
        $this->load->view('admin/_currency_exchange_prefund_transactions',$this->data);
        return FALSE;
    }





    public function search_user_all_orders($user_id_search_orders) {
        if( empty( $user_id_search_orders ) )
        {
            echo "Empty parametr";
            return;
        }

        $this->load->model('accaunt_model','accaunt');
        $this->data = [];

        ini_set('memory_limit','2500M');

        $this->data['user_id_search_orders'] = $user_id_search_orders;
        if( !empty($user_id_search_orders) )
        {
            $this->data['search_orders'] = array('original','archive');
            $this->load->model('users_model','users');

            $user_data = $this->users->getUserFullProfile( (int)$user_id_search_orders );

            $this->data['search_orders']['user'] = array();

            if( empty( $user_data ) )
            {
                $this->data['search_orders']['user']['full_name'] = 'There is no such user ID';
                $this->data['search_orders']['user']['avaliable_usd1'] = '';
                $this->data['search_orders']['user']['avaliable_usd_h'] = '';

                $this->data['search_orders']['user']['net_processing_p2p'] = '';
                $this->data['search_orders']['user']['total_processing_p2p_fee'] = '';
            }else
            {
                $this->data['search_orders']['user']['full_name'] = $user_data->name.' '.$user_data->sername;

                $user_scores = $this->accaunt->recalculateUserRating( $user_id_search_orders );
                $currency_exchange_scores = $this->currency_exchange->currency_exchange_scores( $user_id_search_orders );

                $this->data['search_orders']['user']['scores'] = $user_scores;
                $this->data['search_orders']['user']['scores']['net_processing_p2p_by_bonus'] = $currency_exchange_scores['net_processing_p2p_by_bonus'];

                $this->data['search_orders']['original'] = $this->currency_exchange->get_user_orders('status > 0', $user_id_search_orders);
                $this->data['search_orders']['archive'] = $this->currency_exchange->get_user_orders_arhive(null, $user_id_search_orders);

                foreach( $this->data['search_orders']['archive'] as $ao )
                {
                    if( !empty( $ao->buyer_document_image_path ) && $ao->buyer_document_image_path != 'wt'  )
                    {
                        $ar = explode('/', $ao->buyer_document_image_path);

                        $ao->buyer_document_image = $ar[2];
                    }
                    if( !empty( $ao->seller_document_image_path ) && $ao->seller_document_image_path != 'wt'  )
                    {
                        $ar = explode('/', $ao->seller_document_image_path);

                        $ao->seller_document_image = $ar[2];
                    }
                }
            }
        }

        $this->load->view('admin/_currency_exchange_order_search_orders',$this->data);
        return FALSE;
    }

    public function order_arch() {
        $this->_tableWork_('1=1');
        $this->data["controller"] = 'currency_exchange/order';

    }

    public function order_doc( $doc_num )
    {
        //buyer_document_image_path seller_document_image_path
        $this->load->model('documents_model','documents');
        $this->load->library('image');

//        $user_id = $this->account->get_user_id();

        $doc = $this->currency_exchange->get_doc_file_name_by_orig_order_id( $doc_num );

        $doc_name = '';
        if( empty( $doc ) )
        {
            echo "No docs";
            return FALSE;
        }

        if( !empty( $doc->buyer_document_image_path ) && $doc->buyer_document_image_path != 'wt' )
            $doc_name = $doc->buyer_document_image_path;
        if( !empty( $doc->seller_document_image_path ) && $doc->seller_document_image_path != 'wt' )
            $doc_name = $doc->seller_document_image_path;


        if( empty( $doc_name ) ||  $doc_name == 'wt')
        {
            echo "No docs";
            return FALSE;
        }

        $fileDecoded = $this->code->fileDecode($doc_name);

        if( empty($fileDecoded) )
        {
            echo "File is empty";
            return FALSE;
        }

        $explode_res = explode('.', $doc_name);

        $ext = $explode_res[1];

        @ob_end_clean();

        if( $ext == 'pdf' ){
            header("Content-Type: application/pdf");
            echo $fileDecoded;

            return;
        }
        header("Content-Type: image/png");
        echo $fileDecoded;
    }

    public function order_arhiv($id)
    {

        $item = $this->currency_exchange->get_order_arhiv_by_id($id);
        $item = $this->currency_exchange->_get_user_orders_arhive_details_one($item);

        $this->data['item'] = $item;
        $this->currency_exchange->get_operator_notes( $this->data['item'] );
        $this->currency_exchange->get_operator_reject_notes( $this->data['item'] );

        $seller_active_p2p = $this->currency_exchange->currency_exchange_scores( $this->data['item']->seller_user_id );
        $this->data['item']->seller_active_p2p = $seller_active_p2p['net_processing_p2p'] + $seller_active_p2p['total_processing_p2p'];

        if( !isset($this->data['item']->second_order) && !empty( $item ) && !empty( $item->buyer_order_id ) )
        {
            $this->data['item']->second_order = $second_order = $this->currency_exchange->get_order_arhiv_by_id($item->buyer_order_id);

        }

        if(isset($this->data['item']->second_order)){
            if( empty( $this->data['item']->buyer_user_id ) )
                $this->data['item']->buyer_user_id = $this->data['item']->second_order->seller_user_id;

            if(isset($this->data['item']->second_order->seller_user_id))
            {
                $contr_user_id = $this->data['item']->second_order->seller_user_id;

                $buyer_active_p2p = $this->currency_exchange->currency_exchange_scores( $contr_user_id );

                $this->data['item']->buyer_active_p2p = $buyer_active_p2p['net_processing_p2p'] + $buyer_active_p2p['total_processing_p2p'];

    //            $buyer_item = $this->currency_exchange->get_archive_order_by_id( $id, FALSE, array('seller_user_id' => $contr_user_id ) );
                $this->data['item']->buyer_ip = $this->data['item']->second_order->seller_ip;
            }
        }

        $payment_systems = $this->currency_exchange->get_all_payment_systems();
        $this->data['payment_systems'] = $payment_systems;

        $payment_systems_id_arr = array_set_value_in_key($payment_systems);

        $this->data['payment_systems_id_arr'] = $payment_systems_id_arr;


        $user_id = $this->data['item']->seller_user_id;

        $this->load->library('code');
        $this->load->model('users_model', 'user');

        $this->user->setUserId($user_id);
        $data2 = $this->user->getUser($user_id);
        $this->data['user'] = $data2['user'];

        if(isset($contr_user_id))
        {
            $data3 = $this->user->getUser($contr_user_id);
            $this->data['user_contr'] = $data3['user'];
        }
        //**
        $this->data['item']->buyer_blocked = FALSE;
        $this->data['item']->buyer_name = 'Заявка<br> не сведена';
        if( !empty( $this->data['item']->buyer_user_id ) )
        {
//            $buyer_active_p2p = $this->currency_exchange->currency_exchange_scores( $this->data['item']->buyer_user_id );
            $this->load->model('accaunt_model','accaunt');
            $buyer_active_p2p = $this->accaunt->recalculateUserRating( $item->buyer_user_id );
            //$buyer_active_p2p_amount = $buyer_active_p2p['net_processing_p2p'] + $buyer_active_p2p['total_processing_p2p'];
            $this->data['item']->buyer_active_p2p = $buyer_active_p2p['total_processing_p2p_by_bonus'];
            $this->data['item']->buyer_payout_limit = $buyer_active_p2p['payout_limit_by_bonus'];

            $buyer_name = $this->users->getUserDataFields( $item->buyer_user_id ,array('name','sername'));
            $this->data['item']->buyer_name = $buyer_name->sername . ' '.$buyer_name->name;


            $buyer_item = $this->currency_exchange->get_archive_order_by_id( $id, FALSE, array('seller_user_id' => $item->buyer_user_id ) );
            $this->data['item']->buyer_ip = $buyer_item->seller_ip;
            $this->data['buyer_item'] = $buyer_item;
            $this->data['item']->buyer_blocked = $this->users->isUsersActive($item->buyer_user_id);
        }

        $this->data['item']->seller_blocked = FALSE;
        if( !empty( $this->data['item']->seller_user_id ) )
            $this->data['item']->seller_blocked = $this->users->isUsersActive($this->data['item']->seller_user_id);

        $seller_name = $this->users->getUserDataFields( $item->seller_user_id ,array('name','sername'));
        $this->data['item']->seller_name = $seller_name->sername . ' '.$seller_name->name;
        //**
        $this->data['operator_last_confirm_action'] = '/opera/currency_exchange/operator_last_confirm_order_arhiv';
        $this->data['save_order_arhiv'] = '/opera/currency_exchange/save_order_arhiv';

        $this->content->view('currency_exchange_order', '', $this->data);
    }

    public function operator_last_confirm_order_arhiv()
    {
//        pred($_POST);
        if ($this->input->post('submited', TRUE) != 1)
        {
            echo 'FALSE 1';
            return false;
        }

        $order_id = $this->input->post('id', true);
        $buyer_user_id = $this->input->post('buyer_user_id', true);
        $selected_payed_system = $this->input->post('selected_payed_system', true);
        $selected_sell_system = $this->input->post('selected_sell_system', true);


        $original_order_id = $this->input->post('original_order_id', TRUE);
        $archive_order_ids = $this->input->post('archive_order_ids', TRUE);
        if( empty( $order_id ) ) $order_id = $original_order_id;

        $operator_wt_summ = floatval($this->input->post('operator_wt_summ', true));
        $redirect_url = $this->input->post('back_url', true)?$this->input->post('back_url', true):'/opera/currency_exchange/all_other';

        $this->load->model('accaunt_model', 'account');
        $this->load->model('currency_exchange_model', 'currency_exchange');

        $order = $this->currency_exchange->get_order_arhiv_by_id($order_id);
        if( !empty( $order ) )
            $order = $this->currency_exchange->_get_user_orders_arhive_details_one($order);

        if(empty($order))
        {
            $this->info->add("Ошибка. Не удалось получить данные заявки");

            redirect($redirect_url);

            return false;
        }

        $res = true;

        $this->db->trans_start();

        if($operator_wt_summ > 0)
        {
            $sell_payment_systems =  ['wt' => 1];
            $sell_user_payment_summa =  ['wt' => $operator_wt_summ];
            $buy_payment_systems =  ['wt' => 1];
            $buy_user_payment_summa =  ['wt' => $operator_wt_summ];

            list($sell_sum_fee, $sell_sum_total, $percent, $error) = $this->currency_exchange->getSummAndFeeByListPayments($sell_payment_systems, $sell_user_payment_summa, $buy_payment_systems, $buy_user_payment_summa,[],[]);

            $data_order = [
                'seller_amount' => $operator_wt_summ,
                'seller_fee' => $sell_sum_fee,
            ];

            //where in
            $status_arr = [
                Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR,
                Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR,
                Currency_exchange_model::ORDER_STATUS_CANCELED_BY_USER_BLOCK,
                Currency_exchange_model::ORDER_STATUS_OPERATOR_CANCELED,
                Currency_exchange_model::ORDER_STATUS_ANNULLED,
                Currency_exchange_model::ORDER_STATUS_CONFIRMATION,
                Currency_exchange_model::ORDER_STATUS_PROCESSING_SB,
                Currency_exchange_model::ORDER_STATUS_PROCESSING,
                // добавить необходимые статусы
            ];

            $res = $this->currency_exchange->set_archive_order_data($archive_order_ids, $data_order, $status_arr);
        }

        if($res === FALSE)
        {
            $this->info->add("Невозможно изменить сумму сделки. Операция отменена.");

            redirect($redirect_url);

            return false;
        }

        if( !empty( $selected_payed_system ) && !empty( $selected_sell_system ) )
        {

            $orders = $this->currency_exchange->get_archive_orders_by_orig_order_id( $order->original_order_id );

            $b_user_id = 0;
            if( !empty( $orders ) )
            {
                foreach( $orders as $o )
                {

                    if( $o->initiator == 1 ){
                        $id_init = $o->id;
                        continue;
                    }
                    $id_n_init = $o->id;
                    $b_user_id = $o->seller_user_id;
                }

                if( !empty( $id_n_init ) && !empty($b_user_id) && !empty( $b_user_id ) )
                {
                    $data = ['payed_system' => $selected_payed_system, 'sell_system' => $selected_sell_system, 'buyer_user_id' => $b_user_id];

                    $this->currency_exchange->update_archive_order($id_init, $data);
                    $this->currency_exchange->update_archive_order($id_n_init, $data);
                }
            }

        }
        $this->load->model('transactions_model','transactions');
        if( !$this->transactions->check_p2p_completed_order($order) )
        {
            $this->info->add("По дайнной заявке уже существуют транзакции. Нельзя провести заявку дважды.");
            redirect($redirect_url);
        }

        $status = Currency_exchange_model::ORDER_STATUS_SUCCESS_CONFIRMED_BY_OPERATOR;
        //set_last_confirm_for_operator($confirm_id, $status = false, $delete_original = TRUE, $buyer_user_id = null)
        $res = $this->currency_exchange->set_last_confirm_for_operator($order_id, $status,TRUE, $buyer_user_id, $data_order['seller_amount'], $data_order['seller_fee']);

        if($res === true && isset($data_order))
        {
            $note = 'Сумма wt изменена оператором с '.$order->seller_amount.' на '.$data_order['seller_amount'].'. '
                    ."\n"
                    .'Коммисия изменена с '.$order->seller_fee.' на '.$data_order['seller_fee'].'. '
                    ;
//            pred($note);
            $note_arr = [
                'order_id' => $order->id,
                'is_arhiv' => 1,
                'text' => $note ,
                'date_modified' => date('Y-m-d H:i:s'),
            ];

            $this->currency_exchange->add_operator_note($note_arr);
        }

        $this->currency_exchange->update_rating( array('id' => $order_id) );

        if($res === TRUE)
        {
            $this->db->trans_complete();

            $this->info->add("edit");
        }
        elseif ($res !== false && $res)
        {
            $this->info->add($res);
        }
        else
        {
            $this->info->add("Не удалось провести операцию");
        }

        redirect($redirect_url);
    }

    public function operator_last_confirm_order()
    {
        if ($this->input->post('submited', TRUE) != 1)
        {
            echo 'FALSE 1';
            return false;
        }

        $order_id = $this->input->post('id', true);

        $original_order_id = $this->input->post('original_order_id', TRUE);
        if( empty( $order_id ) ) $order_id = $original_order_id;




        // Если в заявке есть виза.
        if($this->currency_exchange->is_have_visa_in_order($order_id)) {
            $this->load->model('Card_prefunding_transactions_model', 'prefund');

            $prefunds = $this->prefund->get_by_order_id($order_id);

            // Проверяем что в этой заявке есть точно свободные деньги в префанде.
            if($this->prefund->is_paired_free($prefunds)
                && count($prefunds) == 2
                && $this->currency_exchange->is_have_archive_orders($order_id)
                && $this->currency_exchange->is_last_step_in_order($order_id)
            ) {



                $prefund_data = $this->currency_exchange->get_all_data_to_prefund($order_id);
                if($prefund_data !== false) {
                    $pref_res = $this->currency_exchange->prefund_second_transaction($prefund_data);
                    if($pref_res === false) {
                        die('Ошбика при перечеслении на визу ');
                    }
                } else {
                    die('Ошбика при при получение данных для закрытия заявки');
                }
            }

        }

        $order = $this->currency_exchange->get_order_by_id($order_id);

        $res = $this->db
                ->where(['original_order_id' => $order->id, 'seller_user_id' => $order->seller_user_id, 'status' => $order->status, 'initiator' => 1])
                ->get($this->currency_exchange->table_orders_arhive)
                ->row();

        $_POST['id'] = $res->id;

        $this->operator_last_confirm_order_arhiv();
    }

    public function operator_cancell_order()
    {
        if ($this->input->post('submited', TRUE) != 1)
        {
            echo 'FALSE 1';
            return false;
        }

        $order_id = $this->input->post('id', true);
        $reject_text = $this->input->post('reject_text', true);
        $redirect_url = $this->input->post('back_url', true)?$this->input->post('back_url', true):'/opera/currency_exchange/all';


        $original_order_id = $this->input->post('original_order_id', TRUE);
        $archive_order_ids = $this->input->post('archive_order_ids', TRUE);
        if( empty( $order_id ) ) $order_id = $original_order_id;

//        $orders = array();
//        if( !empty( $archive_order_ids ) ){
//            $orders[] = $this->currency_exchange->get_archive_order_by_id($archive_order_ids[0]);
//            $orders[] = $this->currency_exchange->get_archive_order_by_id($archive_order_ids[1]);
//        }
        //$cancel_original = $this->input->post('cancel_original', true);
        $cancel_original = 1;#always reject original
        $order = $this->currency_exchange->get_order_by_id($order_id);




        // Если в заявке есть виза.
        if($this->currency_exchange->is_have_visa_in_order($original_order_id)) {
            $this->load->model('Card_prefunding_transactions_model', 'prefund');

            $prefunds = $this->prefund->get_by_order_id($original_order_id);

            // Проверяем что в этой заявке есть точно свободные деньги в префанде.
            if($this->prefund->is_paired_free($prefunds)
                && count($prefunds) == 2
                && $this->currency_exchange->is_have_archive_orders($original_order_id)
            ) {
                $res_pref = $this->currency_exchange->prefund_delete_from_admin($original_order_id);
                if($res_pref === false) {
                     die('Ошбика при перечеслении на визу');
                }

            }

        }

        $this->db->trans_start();

        //$res = $this->currency_exchange->user_cancel_order_operator($order_id);
        if( $cancel_original != 1 )$orig_order_status = Currency_exchange_model::ORDER_STATUS_SET_OUT;
        else
            $orig_order_status = Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR;


        //$res = $this->currency_exchange->set_status_to_details( $order_id, $orig_order_status );
        //$res_orig = $this->currency_exchange->set_status_to_orig_order( $order_id, $orig_order_status );

        $res = $this->currency_exchange->user_cancel_order_operator_all($order_id, $orig_order_status,
            $archive_order_ids, Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR);


        if($res === TRUE )
        {
            $note_arr = [
                'order_id' => $order->id,
                'text' => $reject_text ,
                'date_modified' => date('Y-m-d H:i:s'),
            ];

     //       $this->currency_exchange->add_operator_reject_note($note_arr);
     //       $this->currency_exchange->add_operator_note($note_arr);
		$this->currency_exchange->add_operator_note($note_arr);
		$note_arr['text_to_user'] = $reject_text;
		$this->currency_exchange->add_operator_reject_note($note_arr);

            if( $cancel_original == 1)
            {
//                $this->db
//                    ->delete($this->currency_exchange->table_orders, array('id' => $order_id));
                $this->db->limit(1)
                    ->update($this->currency_exchange->table_orders, array('status' => Currency_exchange_model::ORDER_STATUS_CANCEL_BRAKEN_UP_BY_OPERATOR),
                            array('id' => $order_id));
        //vred($this->db->last_query());
            }


            $this->info->add("edit");
        }
        else
        {
            $this->info->add("error");
        }
        $this->db->trans_complete();

        redirect($redirect_url);
    }

    public function all() {
        $title = 'Все заявки';
        $this->header['title'] = $title;

        $this->_tableWork_('1=1');

        $this->data["url"] = "/opera/currency_exchange/all";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);
    }

    public function all_new() {
        $title = 'Новые заявки';
        $this->header['title'] = $title;

        $this->_tableWork_('status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING);

        $this->data["url"] = "/opera/currency_exchange/all_new";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);
    }

    public function all_linked(){
        $title = 'Сведенные заявки';
        $this->header['title'] = $title;

        $this->_tableWork_('status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.' AND buyer_user_id >0');

        $this->data["url"] = "/opera/currency_exchange/all_linked";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);

    }

    public function all_unlinked(){
        $title = 'Несведенные заявки';
        $this->header['title'] = $title;

        $this->_tableWork_('status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING.' AND buyer_user_id =0');

        $this->data["url"] = "/opera/currency_exchange/all_unlinked";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);
    }

    public function all_sb(){
        $title = 'Проверка СБ';
        $this->header['title'] = $title;

        $this->_tableWork_('status = '.Currency_exchange_model::ORDER_STATUS_PROCESSING_SB);

        $this->data["url"] = "/opera/currency_exchange/all_sb";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);


    }

    public function all_other()
    {
        $title = 'Все остальные заявки';
        $this->header['title'] = $title;


        $order_ids = $this->currency_exchange->get_all_order_ids(50);
//        pred($order_ids);
        $where = 'original_order_id NOT IN ('.  implode(',', $order_ids).')';
//        pred($where);
        $this->_tableWork_other($where);

        $this->data["url"] = "/opera/currency_exchange/all_other";
        $this->data["controller"] = 'currency_exchange/order_arhiv';
        $this->content->view('currency_exchange_all', 'P2P переводы', $this->data);
    }

    protected function ajax_responce($message, $status = 'success')
    {
        if (!is_string($message) && is_array($message)) $responce = $message;
        else
            if(is_string($message) ) $responce = [$status => $message];
            else
                return FALE;

        echo json_encode($responce);
    }


    public function save_completed_orders()
    {
        $result = $this->currency_exchange->save_completed_orders();

        if( empty( $result ) ){
            echo 'Нет данных для выборки';
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="data-'.date('m-d-Y').'.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        echo $result;
    }
    #нажатие кнопки Сгенерировать данные отчета по завершенным заявкам, Админка
    public function ajax_generate_completed_orders()
    {
        $date_from  = $this->input->post('date_from',TRUE);
        $date_to    = $this->input->post('date_to',TRUE);

        //$date_from  = '2015-02-01';
        //$date_to    = '2016-02-12';


        if( empty( $date_from ) || empty( $date_to ) || strtotime( $date_from ) > strtotime( $date_to ) )
        {
            return $this->ajax_responce( ['text' => 'Ошибка входных параментов', 'result' => FALSE ] );
        }

        $ps_id = 116;

        $this->load->model('currency_exchange_model','currency_exchange');
        $result = $this->currency_exchange->generate_completed_orders( $date_from, $date_to, $ps_id );


        return $this->ajax_responce( $result );
    }


    public function completed_orders() {


        $this->table = $this->currency_exchange->table_completed_orders_data;

        $this->cols = array(
            'id'    => "int",
            'date'  => "datetime",

            'wt_set' => 'int',

            'give_currency' => 'varchat',
            'give_ps'       => 'varchat',
            'give_amount'   => 'float',

            'get_currency'  => 'varchat',
            'get_ps'        => 'varchat',
            'get_amount'    => 'float',

            'status'    => 'int',
            'discount'  => 'float',

        );


        $setting = array(
            'ctrl' => 'currency_exchange',
            'view' => 'currency_exchange',
            'table' => $this->currency_exchange->table_orders,
            'argument' => array_keys($this->cols));


        $this->header = array(
                "title" => 'Данные по завершенным заявкам',
                "columns" => array(
                    'id'    =>          array("title" => "ID","filter" => true,"order" => true,),
                    'date'  =>          array("title" => "Date","filter" => true,"order" => true,),
                    'wt_set' =>         array("title" => "WT set","filter" => true,"order" => true,
                        "formatter" => array(
                            "widget" => 'list',
                            "widget_options" => array(
                                "list" => [ 0 => 'NO WT/0', 1 => 'SELL/1', 2 => 'BUY/2' ]
                            )
                        )
                    ),
                    'give_currency' =>  array("title" => "Give Currency","filter" => true,"order" => true,),
                    'give_ps'       =>  array("title" => "Give PS","filter" => true,"order" => true,),
                    'give_amount'   =>  array("title" => "Give Amount","filter" => true,"order" => true,),
                    'get_currency'  =>  array("title" => "Get Currency","filter" => true,"order" => true,),
                    'get_ps'        =>  array("title" => "Get PS","filter" => true,"order" => true,),
                    'get_amount'    =>  array("title" => "Get Amount","filter" => true,"order" => true,),
                    'status'    =>      array("title" => "Status","filter" => true,"order" => true,),
                    'discount'  =>      array("title" => "Discount","filter" => true,"order" => true,),
            ),
        );

        $this->_tableWork_('1=1');

        $this->data["url"] = "/opera/currency_exchange/completed_orders";
        $this->data["controller"] = 'currency_exchange/order';
        $this->content->view('currency_exchange_completed_orders', 'P2P данные по завершенным заявкам', $this->data);
    }


    //info tab
    //save order
    public function save_order_arhiv()
    {
        if ($this->input->post('submited', TRUE) != 1)
        {
            return false;
        }

        $order_id = $this->input->post('id', true);
        $redirect_url = $this->input->post('back_url', true)?$this->input->post('back_url', true):'/opera/currency_exchange/all_other';
        $note = $this->input->post('admin_note_text');
        $status = $this->input->post('seller_new_order_status', TRUE);

        $this->load->model('accaunt_model', 'account');

        $order = $this->currency_exchange->get_order_arhiv_by_id($order_id);
        $order = $this->currency_exchange->_get_user_orders_arhive_details_one($order);

        $this->db->trans_start();
        $data_order = [
                'status' => $status,
            ];

        $res = $this->currency_exchange->set_order_data($order->seller_user_id, $order_id, $data_order, $order->status);
//        vred($this->db->last_query());
        $note_arr = [
            'order_id' => $order->id,
            'is_arhiv' => 1,
            'text' => $note ,
            'date_modified' => date('Y-m-d H:i:s'),
        ];

        if($res === TRUE)
        {
            $res = $this->currency_exchange->add_operator_note($note_arr);
        }
        $this->db->trans_complete();

        if($res === TRUE)
        {
            $this->info->add("edit");
        }
        else
        {
            $this->info->add("error");
        }

        redirect($redirect_url);
    }

    //archive tab
    public function save_archive_order()
    {
        $back_url = $this->input->post('back_url', true);


        $redirect_url = '/opera/currency_exchange/all_other';
        if( !empty( $back_url ) ) $redirect_url = $back_url;

        $archive_orders = $this->input->post('archive_orders', true);

        if( empty( $archive_orders ) )
        {
             $this->info->add("error");
            redirect( $redirect_url );
        }

        $orders_for_change = array();
        foreach( $archive_orders as $id => $ao )
        {
            if( $ao['save'] != 1 )                continue;
            unset( $ao['save'] );
            $orders_for_change[$id] = $ao;
        }

        if( empty( $orders_for_change ) )
        {
             $this->info->add("error");
            redirect( $redirect_url );
        }

        list( $res_one, $res_array ) = $this->currency_exchange->update_archive_orders( $orders_for_change );

        if( $res_one == TRUE ) $this->info->add("edit");
        else
            $this->info->add("error");

        redirect( $redirect_url );
    }

    //archive tab
    public function reset_timer()
    {
        $arch_order_id1 = $this->input->post('arch_order_id1',TRUE);
        $arch_order_id2 = $this->input->post('arch_order_id2',TRUE);
        $user_type = $this->input->post('user_type',TRUE);


        $modif_time = date('Y-m-d H:i:s');
        $orders_for_change = array();
        if( $user_type == 'buyer' )
        {
            $orders_for_change[ $arch_order_id1 ] = array( $user_type.'_confirmation_date' => $modif_time );
            $orders_for_change[ $arch_order_id2 ] = array( $user_type.'_confirmation_date' => $modif_time );
        }else
        {
            $user_type = 'seller';
            $orders_for_change[ $arch_order_id1 ] = array( $user_type.'_confirmation_date' => $modif_time );
            $orders_for_change[ $arch_order_id2 ] = array( $user_type.'_confirmation_date' => $modif_time );
        }

        list( $res_one, $res_array ) = $this->currency_exchange->update_archive_orders( $orders_for_change );


        $res_ans = array('error' => 0, 'success' => 0);

        if( $res_one === FALSE ) $res_ans['error'] = 'Не все заявки обновлены';
        else
            $res_ans['success'] = 'Заявки обновлены';

        echo json_encode( $res_ans );
        return;
        //redirect( $redirect_url );
    }
    //archive tab
    public function save_original_order()
    {
        $back_url = $this->input->post('back_url', true);


        $redirect_url = '/opera/currency_exchange/all_other';
        if( !empty( $back_url ) ) $redirect_url = $back_url;

        $original_order = $this->input->post('original_orders', true);

        if( empty( $original_order ) )
        {
             $this->info->add("error");
            redirect( $redirect_url );
        }

        $orders_for_change = array();
        foreach( $original_order as $id => $ao )
        {
            if( $ao['save'] != 1 )                continue;
            unset( $ao['save'] );
            $orders_for_change[$id] = $ao;
        }

        if( empty( $orders_for_change ) )
        {
             $this->info->add("error");
            redirect( $redirect_url );
        }

        list( $res_one, $res_array ) = $this->currency_exchange->update_original_orders( $orders_for_change );

        if( $res_one == TRUE ) $this->info->add("edit");
        else
            $this->info->add("error");

        redirect( $redirect_url );
    }

    public function show_any_document( $doc_name )
    {

       //buyer_document_image_path seller_document_image_path
        $this->load->model('documents_model','documents');
        $this->load->library('image');

//        $user_id = $this->account->get_user_id();


        if( empty( $doc_name ) ||  $doc_name == 'wt')
        {
            echo "No docs";
            return FALSE;
        }

        $doc_name = 'upload/currency_exchange_docs/'.$doc_name;

        $fileDecoded = $this->code->fileDecode($doc_name);

        if( empty($fileDecoded) )
        {
            echo "File is empty";
            return FALSE;
        }

        $explode_res = explode('.', $doc_name);

        $ext = $explode_res[1];

        ob_end_clean();

        if( $ext == 'pdf' ){
            header("Content-Type: application/pdf");
            echo $fileDecoded;

            return;
        }
        header("Content-Type: image/png");
        echo $fileDecoded;
    }

    protected function _tableWork_($where)
    {

//        extract($vars);
//        pre($action);
//        pre($vars);
//        vred($header);
        if (isset($_POST["def"]))
        {
            echo $this->_tableHeader4Array($this->header);
            die;
        }

        if(isset($_POST["offset"]))
        {
//            pred();
//            $r = array("count" => "", "rows" => array(), "sql" => "");

            echo $this->currency_exchange->getAll($this->cols, $this->table, $where);

//            $r["rows"] = $res;
//            $r["count"] = count($res);
////            pred($r);
//            echo json_encode($r);

            die;
        }
    }



    protected function _tableWork_other($where)
    {
        if (isset($_POST["def"]))
        {
            print_r($this->header);
            echo $this->_tableHeader4Array($this->header);
            die;
        }

        if(isset($_POST["offset"]))
        {
            echo $this->currency_exchange->getAll($this->cols, $this->table_arhiv, $where);

            die;
        }
    }

}
