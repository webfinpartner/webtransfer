<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class Statistic_messenger extends Permissions_controller
{

    public function __construct()
    {
        parent::__construct();


    }
    
    
    public function index(){
        
         $this->load->model('Messenger_model','messenger');
         $this->load->model('Messengers_log_model','messengers_log');
         
         $data = array();
         $data['messengers'] = $this->messenger->get_list();
         $data['countries'] = $this->messengers_log->get_countries();
         $data['sms_services'] = $this->messenger->get_services( Messengers_log_model::MESSAGE_TYPE_SMS  );
         $this->content->view('statistic_messenger_view', 'Статистика сообщений', $data);
    }

    /**
    * Получает список сервисов по ID мессенджера
    * 
    */

    public function get_services(){
          $services = array();
          
          $this->load->model('Messenger_model','messenger');
          $messenger_id = $this->input->post('messenger');
          
          
          
          if ( !empty($messenger_id) ){
            $services_list = $this->messenger->get_services($messenger_id);
            if ( $services_list !== FALSE )
               $services = (array)$services_list;
          }
          
          echo json_encode( $services );
        
    }
    
    /**
    * Получает код am chart с данными
    * 
    */
    public function get_chart(){
        
          $this->load->model('Messenger_model','messenger');
          
          $container = $this->input->post('container');
          $chart_name = $this->input->post('chart_name');
          $params = $this->input->post('params');
          
          if ( empty($container) || empty($chart_name) ){
              echo 'alert("Bad params")';
              return;
          }
         
          $data_params  = NULL;
          if ( !empty($params) )
            $data_params = json_decode($params);
              
                    
          
          $this->load->model('Messengers_log_model','messengers_log');
          
          
          
          $data = new stdClass();
          $data->container = $container;
          $data->chart_name = $chart_name;
          $data->chart_data = array();
          
          switch( $chart_name ){
            case '5minChart':
                $stat_data = $this->messengers_log->get_5min_stat_data($data_params);
                if ( $stat_data !== false )
                    $data->chart_data = $stat_data; 
                break;  
            case '1hourChart':
                $stat_data = $this->messengers_log->get_1hour_stat_data($data_params);
                if ( $stat_data !== false )
                    $data->chart_data = $stat_data; 
                break;                 
            case 'smsBalanceChart':
                $stat_data = $this->messengers_log->get_sms_balance_stat_data($data_params, $avail_services, $daily);
                if ( $stat_data !== false )
                    $data->chart_data = $stat_data; 
                $data->all_services = $this->messenger->get_services( Messengers_log_model::MESSAGE_TYPE_SMS  );
                $data->avail_services = $avail_services;
                $data->daily = $daily;
                break;    
            case 'smsTotalChart':
                $stat_data = $this->messengers_log->get_sms_total_stat_data($data_params);
                if ( $stat_data !== false )
                    $data->chart_data = $stat_data; 
                break;                      
                
          }
          
          
          $this->load->view('admin/charts/statistic_messenger/'.$chart_name, $data );
                        
    }

 
}
