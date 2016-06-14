<?php

class Shablon_model extends CI_Model {
    
    //table transactions_statuses
    //const CONSTANTS            = 10;    
    
    static $last_error;
    
    public $tableName = 'shablon';    
        
    function __construct() {
        parent::__construct();    
        $this->load->library('code');
    }
    
    /**
     * Return last error was catched
     * @return type
     */
    public function get_last_error() {
        return self::$last_error;
    }
    
    public function get_shablon($id) {
        return $this->db
                ->get_where( $this->tableName , array('id_shablon' => (int) $id))
                ->row();
    }
    
    public function create_wire_bank_payment( $transaction, $code = true ) {

        if( empty( $transaction ) )
            return false;

        $replace = array();
        $shablon_id = '29';
        $this->load->model('payment_data_model','payment_data');
        
        $template = $this->get_shablon($shablon_id);        
        if( empty( $template ) || !is_object( $template ) )
        {
            echo _e("Шаблон пустой");
            return false;
        }
        
        
        $data = new stdClass();
        $this->payment_data->get_fields_values_for_profile( $transaction->id_user, $data, 'wire', 0 );
        
        $bank_countries_names = get_country_list();
        if( empty( $bank_countries_names ) || !isset( $bank_countries_names[$data->wire_beneficiary_bank_country] ) &&
            !isset( $bank_countries_names[$data->wire_beneficiary_bank_country]['name'] )
        )
            return FALSE;
        
        
        $data->wire_beneficiary_bank_country = $bank_countries_names[$data->wire_beneficiary_bank_country]['name'];
        
        
        $replace = (array)$data;
        
        $replace['transaction_id'] = $transaction->id;
        $replace['transaction_date'] = date('d/m/Y', strtotime( $transaction->date ) );
        $replace['summa'] = price_format_double($transaction->summa);
        $replace['summa_add'] = price_format_double($replace['summa'] + 7.0);
        
        $html = $template->sh_content; // шаблон оферты
//        $html = file_get_contents( 'application/models/newhtml.html' );        
        
        $html = $this->mail->user_parcer($html, $transaction->id_user, $replace, $transaction->id); // генерация шаблона
        
        //wire_bank_pay/create_wire_bank_payment_{$id}.pdf
        if ( $code )
            $filename = "wire_bank_payment_{$transaction->id}.pdf";
        else
            $filename = "wire_bank_payment_{$transaction->id}_d.pdf";
        $this->code->createPdf($html, 'wire_bank_pay', $filename, $code); // формирование pdf

        $this->code->clearCache();
    }
}
