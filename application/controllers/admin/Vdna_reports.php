<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!class_exists('Permissions_controller')){ require APPPATH.'libraries/Permissions_controller.php';}
class  Vdna_reports extends Permissions_controller
{
    public $info_state = true;
    public function __construct(){
                 parent::__construct();
                admin_state();
                $this->load->model('Visualdna_model');
                
                
                
                if ($this->info_state)
                    $this->load->library('info');
                
    }
    
 
    /**
     * Action: главная страница для редактирования системных событий
     */
     public function all(){
        $this->content->view('vdna_reports', 'Отчеты VisualDNA', array());  
     }


     
    public function index($id = -1) {
        
        if ($id==-1)
        {
                 
                 $page = 0;
                 $rows = 0;
                 
                 $statuses = $this->Visualdna_model->get_report_statuses();
                 $recs = $this->Visualdna_model->get_reports($recsCount, $page,$rows);
                 
                 $resultArray = array(
                        'rows' => array(),
                        'total' => $recsCount
                    );
                 
                  foreach( $recs as &$rec )
                  {
                      $rec->period = 'с '. ($rec->date_from=='0000-00-00'?'начала':$rec->date_from).' по '. ($rec->date_to=='0000-00-00'?$rec->create_date:$rec->date_to);
                      $rec->report_app_path = '<a href="'.base_url().$rec->report_app_path.'" target="_blank">Скачать</a>';
                      $rec->report_bhvr_path = '<a href="'.base_url().$rec->report_bhvr_path.'" target="_blank">Скачать</a>';
                      $rec->action = '<a title="Смотреть" class="smallButton" onclick="onLoad('.$rec->id.');return false;" href="#" ><img src="images/icons/dark/preview.png"></a></nobr>';
                      //$rec->action .= '<a title="Удалить" class="smallButton del" onclick="return yes_no()" href="/opera/vdna_reports/delete/'.$rec->id.'" ><img src="images/icons/dark/close.png"></a></nobr>';
                      $rec->status_text = $statuses[ $rec->status ];
                      
                      $resultArray['rows'][] = $rec;
                  }
                    

                  echo json_encode($resultArray);
                  return;
        }
        
        $report = $this->Visualdna_model->get_report($id);
        if ( !empty($report) ){
            $res = unserialize($report->stat);
            if ( !empty($res) ){
                echo json_encode([
                    'date_from' => ($report->date_from=='0000-00-00'?'':$report->date_from),
                    'date_to' => ($report->date_to=='0000-00-00'?$report->create_date:$report->date_to),
                    'create_date' => $report->create_date,
                    'status' => $report->status,
                    'stat' => [ 'rows' => [
                        ['name'=> 'Сколько уникальных пользователей  прошло тест?', 'value'=>$res['unnic_users']],
                        ['name'=> 'Сколько всего заявок подано людьми прошедшими тест?', 'value'=>$res['application_counter']],
                        ['name'=> 'Сколько уникальных пользователей прошедшие тест подали заявки на стандартный займ?', 'value'=>$res['unic_users_standart']],
                        ['name'=> 'Сколько всего стандартных займов?', 'value'=>$res['application_standart_counter']],
                        ['name'=> 'Сколько стандартных займов одобрено?', 'value'=>$res['application_approved_counter']],
                        ['name'=> 'У скольки из них наступили сроки погашения?', 'value'=>$res['application_approved_time_bounded_counter']],
                        ['name'=> 'Сколько из них погашено?', 'value'=>$res['application_approved_liquidate_counter']],
                        ['name'=> 'Сколько не погашено?', 'value'=>$res['application_not_liquidate_counter2']],
                    ] ]
                ]);
            }
            
        } else {
            echo json_encode([]);
            
        }

        
        
        

    }
    /**
     * Action: Создание отчета
     */
    public function gen() {
        $dateFrom = $this->input->post('dateFrom');
        $dateTo = $this->input->post('dateTo');
        $this->Visualdna_model->gen_report($dateFrom, $dateTo);
        
    }
    
    /**
     * Action: Удаление отчета c ID=$id
     * @param int $id
     */
    public function delete($id = 0) {
        $id = (int)$id;
        $this->Visualdna_model->del_report($id);
        redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
    }	
    
    /**
     * Action: Отправка отчета c ID=$id
     * @param int $id
     */
    public function send($id = 0) {
        
        $report = $this->Visualdna_model->get_report($id);
        if ( empty($report) ){
            if ($this->info_state)$this->info->add("Не удалось получить отчет.");
            redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
            return;
        }
        
        /*if ( $report->status == Visualdna_model::REPORT_STATUS_SENDED_TO_VDNA ){
            if ($this->info_state)$this->info->add("Отчет уже был отправлен.");
            redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
            return;
        }*/
        
        // получим emails
        $this->load->model('Var_model', 'vars');
        $var_emails = $this->vars->get('vdna_emails');
        $emails = explode(',', trim($var_emails));

        if (count($emails) == 0 || (count($emails) == 1 && empty($emails[0]))) {
            if ($this->info_state)$this->info->add("Нет ни одного почтового адреса в настройках.");
            redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
            return;
        }


        // отправим почту в VDNA
        $this->load->library('mail');
        //get some bank data

        $title = "Reports from {$report->create_date}";
        $text = "Reports from {$report->create_date}";
        
        
        $zip = new ZipArchive();
        $fileName = "upload/vdna_reports/vdna_".date('Y_m_d_h_i_s').".zip";
        if ($zip->open($fileName, ZIPARCHIVE::CREATE) !== true) {
            if ($this->info_state) $this->info->add("Ошибка создания архива.");
            return;
        }
        //добавляем файлы в архив 
        $zip->addFile($report->report_app_path, basename($report->report_app_path));
        $zip->addFile($report->report_bhvr_path, basename($report->report_bhvr_path));
        //закрываем архив
        $zip->close();
        

        foreach ($emails as $email) {
            //$this->mail->attachment[] = realpath(APPPATH . '/../') . '/' .$report->report_app_path;
            //$this->mail->attachment[] = realpath(APPPATH . '/../') . '/' .$report->report_bhvr_path;
            $this->mail->attachment[] = realpath(APPPATH . '/../') . '/' .$fileName;
            $res = $this->mail->send($email, $text, $title);
        }

        if ($this->info_state) $this->info->add("Отчет успешно отправлен в VDNA.");
        $this->Visualdna_model->set_report_status( $id, Visualdna_model::REPORT_STATUS_SENDED_TO_VDNA );
        
        
        redirect(  base_url() . 'opera/' . strtolower( get_class() ) . '/all'  ); 
    }	    
 
}