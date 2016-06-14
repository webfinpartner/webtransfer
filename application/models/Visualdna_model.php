<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Visualdna_model extends CI_Model {
    public $tableName = "visualdna";
    public $extraTableName = 'visualdna_extra_data';

    const NOT_COMPLETE = 0;
    const COMPLETE = 1;
    
    
    const REPORT_STATUS_NEW = 0;
    const REPORT_STATUS_SENDED_TO_VDNA = 1;

    public function seveStatus($puid, $end_date) {
        if(empty($puid)) return;
        if($this->db->where('puid', $puid)->count_all_results($this->tableName))
            return $this->db->update($this->tableName, array('end_date' => date("Y-m-d H:i:s", $end_date), 'complete' => self::COMPLETE), array('puid' => $puid),1);
        else
            return $this->db->insert($this->tableName, array('puid' => $puid, 'end_date' => date("Y-m-d H:i:s", $end_date), 'complete' => self::COMPLETE));
    }

    public function setStart($puid) {
        if(empty($puid)) return;
        if($this->db->where('puid', $puid)->count_all_results($this->tableName))
            return $this->db->update($this->tableName, array('start_date' => date("Y-m-d H:i:s")), array('puid' => $puid),1);
        else
            return $this->db->insert($this->tableName, array('puid' => $puid, 'start_date' => date("Y-m-d H:i:s")));
    }

    public function getStatus($puid) {
        return (self::COMPLETE == $this->db->where('puid', $puid)->get($this->tableName)->row("complete") ? true : false);
    }
    
    
    // работа с доп данными
    public function isNeedExtraData($puid){
        if (empty($puid))
            return FALSE;
            
        if ( !$this->getStatus($puid) )
            return false;
        $rows = $this->db->where('puid', $puid)->get($this->extraTableName)->num_rows();
        
        return ($rows==0);    
            
    }            
    
    
    
    
    public function saveExtraData($puid, $sex,  $birthday)
    {
        if ( !$this->isNeedExtraData($puid) )
            return TRUE;
        
        if ( empty($sex) || empty($birthday))        
            return FALSE;
            
            //$mysqlDate = date_create_from_format('m/d/Y', $birthday);
            //$birthday = date_format($mysqlDate,'Y-m-d');            
        
            
        try {
            $this->db->insert($this->extraTableName, array('puid' => $puid, 'sex' => $sex, 'birthday' => $birthday ));
        } catch( Exception $e){
            return FALSE;
        }
        
        return TRUE;
        
    }
    
    public function calculateAge($timestr = 0, $now = 0) {
        # default to current time when $now not given
        if ($now == 0)
            $now = time();

        $timestamp = strtotime( $timestr );

        $result = round( ($now - $timestamp) / 60 / 60 / 24 / 30 / 12 );

        # deliver the result
        return $result;
    }    
    public function calculatePeriod( $date_src, $today_date_src = null, $show = FALSE ) {
        
        if( $today_date_src === null ) $today_date = time();
        else
            $today_date = strtotime( $today_date_src );
        
        
        $date = strtotime( $date_src );
        $res_month = round(($today_date - $date) / 60 / 60 / 24 / 30, 2);
        
        # deliver the result
        return $res_month;
    }    
    
    /**
     * Генерация файла VDNA 
     */
    function gen_cvs_file($start_dttm = NULL, $end_dttm = NULL, $limit = -1, $stat_only = FALSE){
        ini_set('memory_limit','15G');
        ini_set('max_execution_time', 60*30);
        ini_set('output_buffering', 'Off');  
        ini_set('output_handler', '');  
        ini_set('zlib.output_compression', 'Off');  
        ini_set('implicit_flush', 'On');  

        $this->load->model('transactions_model');

        $unic_users = array(); //уникальные пользователи
        $application_counter = 0; //счетчик заявок
        $application_approved_counter = 0; //счетчик одобренных заявок
        $application_approved_unic_counter = array();
        $application_approved_time_bounded_counter = 0; //счетчик заявок, подошедши к выплате
        $application_approved_liquidate_counter = 0; //погашены
        $application_approved_not_liquidate_counter = 0; //не погашенный
        
        
        $file_name_app3 = 'upload/vdna_reports/application_'.date('y-m-d');
        $file_name_app4 = 'upload/vdna_reports/behaviour_'.date('y-m-d');
        if ( $limit > 0 )
            $this->db->limit($limit);
        if ( !empty($start_dttm))
            $this->db->where('end_date >=', $start_dttm.' 00:00:00' );
        if ( !empty($end_dttm))
            $this->db->where('end_date <=', $end_dttm.' 23:59:59' );
        
        $user_ids = $this->db
                            //->limit(10000, $limit)
                            ->where('complete',1)
                            ->order_by('puid')
                            ->group_by('puid')
                            ->get('visualdna')
                            ->result();
        $total_users = count($user_ids );

        //var_dump($user_ids);
        //die();
        $user_ids_visualdna_extra_data = $this->db
//                            ->limit(100)
                            ->get('visualdna_extra_data')
                            ->result();

        $user_ids_visualdna_extra_data_ass = array();
        foreach( $user_ids_visualdna_extra_data as $extra )
            $user_ids_visualdna_extra_data_ass[ $extra->puid ] = $extra;

        $user_ids_double = array();
        $max_user_count = 1000;
        $all_credits = array();
        $unic_users_standart = array();
        $application_approved_time_bounded_counter_unic = array();
        $application_approved_liquidate_counter_unic = array();
        $application_approved_not_liquidate_counter_unic = array();
        $application_standart_counter = 0;

        
        //create file
        #appendix 3
        $csv_file_name_app3   = $file_name_app3.'.csv';
        $csv_app3 = fopen($csv_file_name_app3, 'w+');

        if( empty( $csv_app3 ) )
            die("Can\'t create output file app4\n");

        $str_csv_3 = implode(';', array('sent_date', 'puid', 'customer_number', 'application_ID','account_number', 'application_date', 'Product','Gender', 'Age', 'exposure_requested', 'application_client_score',
            'application_client_decision', 'final_status', 'vdna_accept', 'account_open_date', 'rate_issued', 'maturity_date') )."\n";        
        fwrite($csv_app3, $str_csv_3);
        
        #appendix 4
        $csv_file_name_app4   = $file_name_app4.'.csv';
        $csv_app4 = fopen($csv_file_name_app4, 'w+');

        if( empty( $csv_app4 ) )
            die("Can\'t create output file app4\n");
        

        $str_csv_4 = implode(';', array(
            'sent_date',    //дата формирования отчета
            'Puid',
            'customer_number', 
            'application_ID',
            'account_number', 	//номер контракта
            'snapshot_date',     //null
            'account_close_date',    //дата истечения займа/NULL
            'Mob', 	//Количество месяцев, с тех пор, как он получил займ, делать дробное количество месяцев
            '<Bad indicator flags>',// 	оплачен - 1, непогашен - 0
            'pre_quiz_status'// 	null

        ) )."\n";        
        fwrite($csv_app4, $str_csv_4);
            
        //----------------
        #get all the credits in an array
        $cnt = 0;
        $today_date = date('Y-m-d');
        //ob_implicit_flush(1);
        while (1) {
            //echo "Progress: ".round( $cnt / $total_users * 100, 2 ).'-'.round( memory_get_usage() / 1024 / 1024, 2 )."MB\n";
            echo json_encode([
                'act' => 'step',
                'total' => $cnt,
                'perc' => round( $cnt / $total_users * 100, 2 ),
                'mem' => round( memory_get_usage() / 1024 / 1024, 2 ).'MB'
            ]);
            echo str_repeat(' ',1024*64);
            flush();            
            //when array ends
            if( count( $user_ids ) <= 0 )
                break;
            $user_str = array();

            $counter = 0;
            foreach( $user_ids as $key => $id )
            {
                $user_str[] = "id_user = {$id->puid}";
                $user_ids_double[ $id->puid ]->id = $id;
                unset( $user_ids[$key] );
                $counter++;
                $cnt++;
                //when reached  of the main array
                if( $counter > $max_user_count )
                {
                    break;
                }
            }
            //echo "TOTAL USERS: ".count( $user_ids_double )."<br>";

            $where_users = implode(' OR ', $user_str);
            $where = 'arbitration = 0 and type = '.Base_model::CREDIT_TYPE_CREDIT." and overdraft = 0 and ({$where_users})";
            
            
            // получаем доп.инфу о польтзователе
            $users = $this->db->select(array('id_user', 'born','sex'))
            //                ->where( array( 'born !='=> '','sex !=' => '' ) )
                                    ->where( "{$where_users}" )
                                    ->get('users')
                                    ->result();

            //echo "USERS: ".count( $users ) . "<br>";

            #get full user information
            foreach( $users as $u )
            {
                $user_ids_double[ $u->id_user ]->sex = 'NULL';
                $user_ids_double[ $u->id_user ]->birthday = 'NULL';

                $sex = 'NULL';
                if( !empty( $u->sex ) )
                    $sex = $u->sex;
                elseif ( isset( $user_ids_visualdna_extra_data_ass[ $u->id_user ] ) )
                        $sex = $user_ids_visualdna_extra_data_ass[ $u->id_user ]->sex;
                $user_ids_double[ $u->id_user ]->sex = $sex;
                    
                $born = 'NULL';
                if( !empty( $u->born ) )
                    $born = $u->born;
                elseif( isset( $user_ids_visualdna_extra_data_ass[ $u->id_user ] ) )
                   $born = $user_ids_visualdna_extra_data_ass[ $u->id_user ]->birthday;
                $user_ids_double[ $u->id_user ]->birthday = $born;
            }              

            
            // кредиты
            $credits_src = $this->db->select(array('id','id_user', 'date','garant', 'summa', 'state', 'kontract',
                'date_kontract', 'percent', 'out_time', 'type', 'arbitration','overdraft'))
                                ->where( $where )
                                ->order_by( 'date_kontract', 'ASC' )
                                ->get('credits')
                                ->result();

            $first_credits_for_app4 = array();
            $credits = array();
            foreach( $credits_src as $c )
            {
                $credits[ $c->id ] = $c;
                if( !isset( $first_credits_for_app4[ $c->id_user ] ) ) $first_credits_for_app4[ $c->id_user ] = 0; 
                #get the most first credits
                if( !in_array( $c->state,  [Base_model::CREDIT_STATUS_ACTIVE, Base_model::CREDIT_STATUS_PAID ] ) ||
                    ( isset( $first_credits_for_app4[ $c->id_user ] ) &&
                    strtotime( $first_credits_for_app4[ $c->id_user ]->date_kontract ) >= strtotime( $c->date_kontract ) ) 
                )
                continue;
                
                $first_credits_for_app4[ $c->id_user ] = $c;
            }
            
            $credits_archive_src = $this->db->select(array('id','id_user', 'date','garant', 'summa', 'state', 'kontract',
                'date_kontract', 'percent', 'out_time', 'type', 'arbitration','overdraft'))
                                ->where( $where )
                                ->order_by( 'date_kontract', 'ASC' )
                                ->get('archive_credits')
                                ->result();
            
            $credits_archive = array();
            foreach( $credits_archive_src as $c )
            {
                $credits_archive[ $c->id ] = $c;
                    
                if( !isset( $first_credits_for_app4[ $c->id_user ] ) ) $first_credits_for_app4[ $c->id_user ] = 0;
                #get the most first credits                
                if( !in_array( $c->state,  [Base_model::CREDIT_STATUS_ACTIVE, Base_model::CREDIT_STATUS_PAID ] ) ||
                    ( isset( $first_credits_for_app4[ $c->id_user ] ) && !empty( $first_credits_for_app4[ $c->id_user ] ) &&
                    strtotime( $first_credits_for_app4[ $c->id_user ]->date_kontract ) <= strtotime( $c->date_kontract ) ) 
                )
                continue;
                
                $first_credits_for_app4[ $c->id_user ] = $c;                
            }
            //echo "count: ".count($credits).'<br>';
            //echo "count: ".count($credits_archive).'<br>';

            $all_credits = array_merge( $credits, $credits_archive);            
            
            $first_credits_periods = array();
            foreach( $first_credits_for_app4 as $user_id => $c )
            {
                if( empty( $c ) ) $first_credits_periods[ $user_id ] = 0;
                else
                    $first_credits_periods[ $user_id ] = $this->calculatePeriod( $c->date_kontract, null, ($c->id_user == 99676750) );
            }
            
            //echo "CREDITS:" . count( $all_credits ).'<br>';
            foreach( $all_credits as $row )
            {
                $row->puid = (int)$row->id_user;
                #<!--statictic
                //1) Сколько уникальных пользователей прошло тест?
                $unic_users[(int)$row->puid] = 1;
                //2) Сколько всего заявок подано людьми прошедшими тест?                    
                if ( $row->arbitration != 0 || $row->overdraft != 0 || $row->type != 1 ) continue;
                
                $application_counter++;

                //3 Сколько уникальных пользователей прошедшие тест подали заявки на стандартный займ?                
                if( $row->garant == 0 )
                {
                    //4.1) Сколько всего стандартных займов?
                    $application_standart_counter ++;
                    $unic_users_standart[(int)$row->puid] = 1;
                    if( in_array( $row->state, array(Base_model::CREDIT_STATUS_ACTIVE, Base_model::CREDIT_STATUS_PAID) ) )
                    {
                        //4.2) Сколько стандартных займов одобрено?
                        $application_approved_counter ++;
                        $application_approved_unic_counter[(int)$row->puid] = 1;

                        if( strtotime( $row->out_time ) <= time() )
                        {
                            //5) У скольки из них наступили сроки погашения
                            $application_approved_time_bounded_counter ++;
                            $application_approved_time_bounded_counter_unic[(int)$row->puid] = 1;                                

                            //пп 6. Сколько из них погашено
                            if( Base_model::CREDIT_STATUS_PAID == $row->state )
                            {
                                $application_approved_liquidate_counter ++;
                                $application_approved_liquidate_counter_unic[(int)$row->puid] = 1;
                            }else
                            //пп 7. Сколько не погашено
    //                        if( in_array( $row->state, array( Base_model::CREDIT_STATUS_ACTIVE, Base_model::CREDIT_STATUS_DELETED ) ) )
                            {
                                $application_approved_not_liquidate_counter++;
                                $application_approved_not_liquidate_counter_unic[(int)$row->puid] = 1;
                            }
                        }
                    }
                }

                //считаем статистику и пишем в csv
                #appendix 3
                $row->age = 'NULL';
                $row->Gender = 'NULL';
                if( isset( $user_ids_double[ $u->id_user ] ) )
                {
                    if( $user_ids_double[ $u->id_user ]->birthday != 'NULL' )
                        $row->age = $this->calculateAge( $user_ids_double[ $u->id_user ]->birthday );
                    if( $user_ids_double[ $row->id_user ]->sex == 1)
                        $row->Gender = 'm';
                    elseif(  $user_ids_double[ $row->id_user ]->sex == 0 )
                        $row->Gender = 'f';
                }

                if( $row->garant == Base_model::CREDIT_GARANT_ON  )
                    $row->Product = 'G';
                else
                    $row->Product = 'S';

                //если активная или выплачена = 1, выставлена  = 0, если удалена = NULL                
                $row->application_client_decision = 0;
                if( $row->state == Base_model::CREDIT_STATUS_ACTIVE ||
                    $row->state == Base_model::CREDIT_STATUS_PAID 
                )
                    $row->application_client_decision = 1;
                else
                    if( $row->state == Base_model::CREDIT_STATUS_SHOWED )
                        $row->application_client_decision = 0;
                    else
                        if( $row->state == Base_model::CREDIT_STATUS_CANCELED ) 
                                $row->application_client_decision = 'NULL';
                        
                //если отмененан пользовтелем = 1, иначе = 0
                if( $row->state == Base_model::CREDIT_STATUS_CANCELED )
                    $row->final_status = 1;
                else
                    $row->final_status = 0;

                if( NULL == $row->date_kontract )  $row->date_kontract = 'NULL';
                
                $row->rate_issued = 'NULL';
                if( $row->state == Base_model::CREDIT_STATUS_ACTIVE ||
                    $row->state == Base_model::CREDIT_STATUS_PAID 
                ) $row->rate_issued = $row->percent;
                
                if( NULL == $row->out_time )  $row->out_time = 'NULL';
                
                $str_csv = implode(';', array($today_date, //'sent_date' дата формирования отчета
                                        $row->id_user,  //'puid'
                                        $row->id_user, //'customer_number'
                                        $row->id,   //application_ID номер заявки
                                        $row->id_user,      //account_number номер аккаунта, с которого списываются деньги
                                        $row->date,     //application_date дата выставления заявки
                                        $row->Product,  //G/S
                                        $row->Gender,   //m/f/null
                                        $row->age,      //12/null
                                        $row->summa,    //exposure_requested //сумма займа без процентов
                                        'NULL',          //application_client_score //null - рейтинг формирования на момент подачи
                                        $row->application_client_decision, //если активная или выплачена = 1, выставлена  = 0, если удалена = NULL
                                        $row->final_status, //final_status если отмененан пользовтелем = 1, иначе = 0
                                        'NULL',             //vdna_accept
                                        $row->date_kontract, //account_open_date //дата формирования контракта. Если выставлена или отменена = null
                                        $row->rate_issued, //Если активный/выплаченны = процент займа, иначе = null
                                        $row->out_time     //maturity_date                    
                    ) )."\n";
                
                fwrite($csv_app3, $str_csv);                
                
                
                if( empty( $row->kontract ) ) $row->kontract = 'NULL';
                #appendix 4 
                $str_csv_app4 = implode(';', array(
                    $today_date,    //'sent_date',    //дата формирования отчета
                    $row->id_user,  //'Puid',
                    $row->id_user,  //'customer_number', 		
                    $row->id, 	//'application_ID', 	//номер заявки
                    $row->kontract, 	//'account_number', 	//номер контракта
                    $today_date,         //'snapshot_date',
                    $row->out_time,    //'account_close_date',    //дата истечения займа/NULL
                    $first_credits_periods[ $row->id_user ], 	//'Mob', 	//Количество месяцев, с тех пор, как он получил займ, делать дробное количество месяцев
                    ($row->state == Base_model::CREDIT_STATUS_PAID? 1: 0),//'<Bad indicator flags>',// 	оплачен - 1, непогашен - 0
                    'NULL'  //'pre_quiz_status'// 	null
                ) )."\n";
                
            
            
            
            
            
            
            
            
                //echo $str_csv."<br>";
                fwrite($csv_app4, $str_csv_app4); 
            }
            
//           $all_credits = array();
            
            
        } //end while 

                
        fclose($csv_app3);
        fclose($csv_app4);

        $digits = array();
        $digits['unnic_users']                                  = count( $user_ids_double );
        $digits['application_counter']                              = $application_counter;
        $digits['application_standart_counter']                 = $application_standart_counter;
        
        
        $digits['application_approved_counter']                 = $application_approved_counter;
        
        $digits['unic_users_standart']                          = count( $unic_users_standart );
        
        $digits['application_approved_unic_counter']            = count($application_approved_unic_counter);
        $digits['application_approved_time_bounded_counter']    = $application_approved_time_bounded_counter;
        $digits['application_approved_time_bounded_counter_unic'] = count( $application_approved_time_bounded_counter_unic );
        $digits['application_approved_liquidate_counter']       = $application_approved_liquidate_counter;
        
        $digits['file_name_app3'] = $csv_file_name_app3;
        $digits['file_name_app4'] = $csv_file_name_app4;

//        $digits['application_not_liquidate_counter1']           = $digits_old['application_not_liquidate_counter1']
//                                                                + $application_approved_time_bounded_counter - $application_approved_liquidate_counter;
//
        $digits['application_not_liquidate_counter2']           = $application_approved_not_liquidate_counter;

         /*
        echo "1) Сколько уникальных пользователей  прошло тест? {$digits['unnic_users']}\n";
        echo "2) Сколько всего заявок подано людьми прошедшими тест? {$digits['application_counter']}\n";
        echo "3) Сколько уникальных пользователей прошедшие тест подали заявки на стандартный займ? {$digits['unic_users_standart']}\n";

        echo "4.1) Сколько всего стандартных займов? {$digits['application_standart_counter']}\n";
        echo "4.2) Сколько стандартных займов одобрено? {$digits['application_approved_counter']}\n";
        echo "5) У скольки из них наступили сроки погашения? {$digits['application_approved_time_bounded_counter']}\n";
        echo "6) Сколько из них погашено? {$digits['application_approved_liquidate_counter']}\n";

        echo "7) Сколько не погашено? {$digits['application_not_liquidate_counter2']}\n\n";
       
        if ( !$stat_only ){
            echo "Application (app 3)file CSV file: <a href='".base_url()."{$csv_file_name_app3}'>download</a><br>";
            echo "Behaviour (app 4) file: <a href='".base_url()."{$csv_file_name_app4}'>download</a><br>";
        }
         */
        return $digits;
    }
    
    /*
     * Получить список отчетов
     */
    public function get_reports(&$total){
        
        $result = $this->db->order_by('id','desc')->get('vdna_reports')->result();
        $total = count($result);
        return $result;
        
        
    }
    
    
    /*
     * Получить отчет
     */
    public function get_report($id){
        
        $result = $this->db->where('id',$id)->get('vdna_reports')->row();
        return $result;
        
        
    }    
    
    /*
     * Удалить отчет
     */
    public function del_report($id){
        
        $result = $this->db->where('id',$id)->get('vdna_reports')->row();
        if ( !empty($result)){
            @unlink( $result->report_app_path );
            @unlink( $result->report_bhvr_path);
            $this->db->where('id',$id)->delete('vdna_reports');
        }
    }
        
    
    
    /*
     * Сформировать отчет
     */
    public function gen_report($start_dttm = NULL, $stop_dttm = NULL){
        
        $res = $this->gen_cvs_file($start_dttm, $stop_dttm);
        $cnt = $res['unnic_users'];
        $this->db->insert('vdna_reports', [
                'create_date' => date('Y-m-d H:i:s'),
                'records_count' => $cnt,
                'date_from' => $start_dttm,
                'date_to' => $stop_dttm,
                'status' => self::REPORT_STATUS_NEW,
                'report_app_path' => $res['file_name_app3'],
                'report_bhvr_path' => $res['file_name_app4'],
                'stat' => serialize($res)
        ]);
        
        echo json_encode(['act'=>'result', 'id'=> $this->db->insert_id()]);
        
        
    }
    
    /*
     * Получает массив статусов
     */
    public function get_report_statuses(){
        
        return [
            self::REPORT_STATUS_NEW => 'Новый',
            self::REPORT_STATUS_SENDED_TO_VDNA => 'Отправлен'
            
        ];
    }
    /*
     * Обновляет статус отчета
     */    
    public function set_report_status($id, $status){
        
        try {
            $this->db->where('id',$id)->update('vdna_reports', ['status'=>$status]);
            return TRUE;
        } catch (Exception $e){
            return FALSE;
        }
    }    

    
    
   
}
