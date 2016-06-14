<?php

class Payment_data_model extends CI_Model {

    //table transactions_statuses
    //const CONSTANTS            = 10;

    static $last_error;

    public $tableNameFieldsNames = 'payment_data_names';
    public $tableNameFieldsValues = 'payment_data_values';

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

    public function get_fields_names( $language_id = 0, $field = null, $prefix = null )
    {
        $where = array( 'language_id' => $language_id );

        if( !empty( $prefix ) )  $where['prefix'] = $prefix;

        $fields_names_res =  $this->db->get_where($this->tableNameFieldsNames, $where)
                                      ->result();

        if( empty( $fields_names_res ) )
            return FALSE;

        if( empty( $field ) )
        {
            return $fields_names_res;
        }

        $fields_names = array();
        foreach( $fields_names_res as $f )
        {
//            var_dump($f);
            if( isset( $f->{$field} ) ) $fields_names[$f->{$field}] = $f;
        }
//   var_dump($fields_names);
        return $fields_names;

    }

    /**
     * Insert or add new fields.
     * Check for excistence the very FIRST field.
     *
     * @param type $user_id
     * @param type $fields_array
     * @return boolean
     */
    public function update_fields( $user_id, $fields_array, $prefix = null )
    {
        if( empty( $user_id ) || empty( $fields_array )
            || !is_array( $fields_array )
        )
        return FALSE;

        $field_names = $this->get_fields_names(0,'machine_name', $prefix);

        if( empty($field_names) )
            return FALSE;

        $field_names_keys = array_keys( $field_names );

        $is_insert =
        $this->db->limit(1)
                 ->get_where($this->tableNameFieldsValues,array( 'field_id' => $field_names[ $field_names_keys[0] ]->id))
                 ->row();

        if( empty( $is_insert ) ) $is_insert = TRUE;
        else
            $is_insert = FALSE;

        $update_array = array();
        $i = 0;
        foreach( $fields_array as $key => $val )
        {
            if( !in_array( $key, $field_names_keys ) ) continue;
            $update_array[$i] = array( 'field_id' => $field_names[$key]->id,
                                       'value' => $this->code->code($val) );

            $update_array[$i]['user_id'] = $user_id;
            $update_array[$i]['id'] = null;

            $i++;
        }

        //delete rows
        if( $is_insert === FALSE )
        {
            $field_update_ids = array();
            foreach( $field_names as $f )
                $field_update_ids[] = "field_id = {$f->id}";

            $where = "user_id = $user_id ";
            $where .=  "AND ( ". implode( ' OR ', $field_update_ids )." )";

            $this->db->where( $where )
                     ->delete( $this->tableNameFieldsValues );

        }

        $this->db->insert_batch( $this->tableNameFieldsValues, $update_array );
            return TRUE;
    }

    /**
     *
     * @param type $user_id
     * @param type $prefix
     * @param type $language_id
     * @return boolean
     */
    public function get_fields_values( $user_id, $prefix = null, $language_id = 0 )
    {
        if( empty( $user_id ) || empty( $prefix ) ) return FALSE;

        $field_names = $this->get_fields_names($language_id, 'id', $prefix);

        if( empty($field_names) )
            return FALSE;


        $field_update_ids = array();
        foreach( $field_names as $f )
            $field_update_ids[] = "field_id = {$f->id}";

        $where = "user_id = $user_id ";
        $where .=  "AND ( ". implode( ' OR ', $field_update_ids )." )";

        $res = $this->db->where( $where )
                 ->get( $this->tableNameFieldsValues )
                 ->result();

        if( empty( $res ) ) return FALSE;

        foreach( $res as $r )
        {
            if( !isset( $field_names[ $r->id ] ) ) continue;

            $r->machine_name = $field_names[ $r->id ]->machine_name;
            $r->human_name = $field_names[ $r->id ]->human_name;
            $r->value = $this->code->decrypt( $r->value );
        }

        return $res;
    }

    /**
     *
     * @param type $user_id
     * @param type $data
     * @param type $prefix
     * @param type $language_id
     * @return boolean
     */
    public function get_fields_values_for_profile( $user_id, $data = null, $prefix = null, $language_id = 0 )
    {
        if( empty( $user_id ) || empty( $prefix ) || empty( $data ) ) return FALSE;

        $field_names = $this->get_fields_names($language_id, 'id', $prefix);

        if( empty($field_names) )
            return FALSE;


        $field_update_ids = array();
        foreach( $field_names as $f )
            $field_update_ids[] = "field_id = {$f->id}";

        $where = "user_id = $user_id ";
        $where .=  "AND ( ". implode( ' OR ', $field_update_ids )." )";

        $res = $this->db->where( $where )
                        ->get( $this->tableNameFieldsValues )
                        ->result();

        //get old bank data from users table
//        if( !isset( $this->accaunt ) ) $this->load->model('accaunt_model','accaunt');
        $user_data = $this->base_model->getUserPayment($user_id);

        //there is no data at all
        if( empty( $res ) && (!isset( $user_data->bank_name ) || empty( $user_data->bank_name )) )
        {
            return FALSE;
        }else//there is old data
        if( isset( $user_data->bank_name ) && !empty( $user_data->bank_name )){
            $data->wire_beneficiary_bank = $user_data->bank_name;
            $data->wire_beneficiary_account = $user_data->bank_schet;
            $data->wire_beneficiary_swift = $user_data->bank_bik;

            return TRUE;
        }


        if( empty( $res ) && !empty( $user_data )){
            $data->wire_beneficiary_bank = $user_data->bank_name;
            $data->wire_beneficiary_account = $user_data->bank_schet;
            $data->wire_beneficiary_swift = $user_data->bank_bik;

            return TRUE;
        }

        foreach( $res as $r )
        {
            if( !isset( $field_names[ $r->field_id ] ) ) continue;

            $machine_name = $field_names[ $r->field_id ]->machine_name;
            if( empty( $r->value ) && !empty( $user_data ))
            {
                if( $machine_name == 'wire_beneficiary_bank' && isset( $user_data->bank_name ) && !empty( $user_data->bank_name ) )
                {
                    $data->wire_beneficiary_bank = $user_data->bank_name;
                }else
                if( $machine_name == 'wire_beneficiary_account' && isset( $user_data->bank_schet ) && !empty( $user_data->bank_schet ) )
                {
                    $data->wire_beneficiary_account = $user_data->bank_schet;

            }else
                if( $machine_name == 'wire_beneficiary_swift' && isset( $user_data->bank_bik ) && !empty( $user_data->bank_bik ))
            {
                    $data->wire_beneficiary_account = $user_data->bank_bik;
                }  else
                {
                $data->{$machine_name} = $this->code->decrypt( $r->value );
            }
            }else
            {
                $data->{$machine_name} = $this->code->decrypt( $r->value );
            }

        }

    }

    public function get_wire_bank_reqired_fileds( $var = '' )
    {
        $fields = array();

        $fields['ea'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
//                        'wire_beneficiary_bank_address',
                        'wire_beneficiary_account',
                        'wire_beneficiary_swift',
                       );
        //UK & Ireland
        $fields['uk'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
//                        'wire_beneficiary_bank_address',
                        'wire_beneficiary_account',
                        'wire_sort'
                       );
        $fields['others'] = array( 'wire_beneficiary_name',
                        'wire_beneficiary_address',
                        'wire_beneficiary_bank',
                        'wire_beneficiary_bank_country',
//                        'wire_beneficiary_bank_address',

                        'wire_beneficiary_account',
                        'wire_beneficiary_swift',
                        'wire_corresponding_bank',
                        'wire_corresponding_bank_swift',
                        'wire_corresponding_account',
                       );

        if( !empty( $var ) && isset( $fields[ $var ] ) )
        {
            return $fields[ $var ];
        }

        return $fields;

    }

    /**
     *
     * @param type $user_id
     * @param type $data
     * @return boolean
     */
    public function check_wire_bank_fields( $data )
    {
        if( empty( $data ) || !is_array( $data ) )
            return FALSE;

        $fields_values_for_profile = $this->get_fields_names( 0, 'machine_name', 'wire' );

        if( empty( $fields_values_for_profile ) || !isset( $data['wire_beneficiary_bank_country'] ))
            return FALSE;

        $this->load->helper('main_helper');
        $countries = get_country_list();

        $wire_beneficiary_bank_country = (int)$data['wire_beneficiary_bank_country'];
        if( !isset($countries[ $wire_beneficiary_bank_country ] ) && $wire_beneficiary_bank_country > -1 )
        {
            return FALSE;
        }

        $country_data = $countries[ $wire_beneficiary_bank_country ];

        if( !isset( $country_data['wire_form'] ) )
        {
            return FALSE;
        }

        $wire_bank_reqired_fileds = array();
        if( $country_data['wire_form'] == 'SA' || $country_data['wire_form'] == 'NA' ){
            $wire_bank_reqired_fileds = $this->get_wire_bank_reqired_fileds('ea');
        }else
            if( $country_data['wire_form'] == 'UK' || $country_data['wire_form'] == 'IR' )
            {
            $wire_bank_reqired_fileds = $this->get_wire_bank_reqired_fileds('uk');
            }else
            $wire_bank_reqired_fileds = $this->get_wire_bank_reqired_fileds('others');

        if( empty( $wire_bank_reqired_fileds ) )
        {
            return FALSE;
        }

        $empty = 0;
        foreach( $wire_bank_reqired_fileds as $field_name )
        {
            if( !isset( $data[ $field_name ] ) || $data[ $field_name ] === '' )
            {
                $empty++;
            }
        }

        //var_dump($empty);
        //var_dump(count( $wire_bank_reqired_fileds ));
        if( $empty == 0 )
        {
            return TRUE;
            }
        else
            if( $empty == count( $wire_bank_reqired_fileds ) ||
              ( $empty == count( $wire_bank_reqired_fileds) - 1 && $data[ 'wire_beneficiary_bank_country' ] != -1 )
            )
            {
                return NULL;
            }else
            {
                return FALSE;
        }

    }
}
