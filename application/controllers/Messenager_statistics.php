<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messenager_statistics extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'Messengers_log_model', 'messengers_log' );
    }
 
 
    public function min_5(){
        
       $this->messengers_log->generate_5_min_statistic();
        
    }
 
    
    public function hour_1(){
        
       $this->messengers_log->generate_1_hour_statistic(); 
        
    }    
    
    public function day_1(){
        
       $this->messengers_log->generate_1_day_statistic(); 
        
    }   
    
    
    public function test(){
        //$this->load->model( 'Visualdna_model' );
        //$this->Visualdna_model->get_statistics();
       // var_dump( $this->Visualdna_model->gen_cvs_file() );
    }
    
    
}
