<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {


    public function reset() {
        $this->_error_array     = array();
        $this->_field_data      = array();
//            $this->_config_rules    = array();
//            $this->_error_array     = array();
//            $this->_error_messages  = array();
//            $this->error_string     = '';
//            $this->_safe_form_data  = FALSE;
    }

    public function alpha_space($str)
    {
            return ( ! preg_match("/^([a-zа-я ])+$/ui", $str)) ? FALSE : TRUE;
    }

    public function alpha_rus($str)
    {
            return ( ! preg_match("/^([а-я])+$/ui", $str)) ? FALSE : TRUE;
    }


    public function type_valid($str)
    {
            $db= new base_model;
            $data=$db->db->select('id')->get('contribution')->result();
            if(empty($data))return false;
            foreach($data as $var)
            {
                    $out[]=$var->id;
            }

            if(in_array($str,$out,true))return true;
            else return false;
    }



    public function  valid_float($str){
        $summ = array();
        return (preg_match('/^[0-9](.[0-9]{1,2}|)$/sui',$str,$summ))?true:false;
    }

    public function  valid_date($str){
        $summ = array();
        return (preg_match('#(\d{1,2} \d{1,2} \d{4})#',$str,$summ))?true:false;
    }


    public function age_date($str){
        $summ = array();
        if (preg_match('#(\d{1,2} \d{1,2} \d{4})#',$str,$summ))
        {
            $data=explode(' ',$summ[1]);
            return $this->pc_checkbirthdate($data[1],$data[0],$data[2]);
        }
        else return false;
    }
    public function pc_checkbirthdate($month,$day,$year) {
        $min_age = 18;
        $max_age = 80;
        if (! checkdate($month,$day,$year)) {
        return false;
        }
        list($this_year,$this_month,$this_day) = explode(',',date('Y,m,d'));
        $min_year = $this_year - $max_age;
        $max_year = $this_year - $min_age;

        if (($year > $min_year) && ($year < $max_year)) {
        return true;
        } elseif (($year == $max_year) &&
        (($month < $this_month) ||
        (($month == $this_month) && ($day <= $this_day)))) {
        return true;
        } elseif (($year == $min_year) &&
        (($month > $this_month) ||
        (($month == $this_month && ($day > $this_day))))) {
        return true;
        } else {
        return false;
        }
    }

    /**
     * проверяет занечение на соответствие
     * при этом оно  должно  существовать
     *
     */

    public function in_myarray_r($str,$arr){
        if(empty($str) or $str=='') return false;
        $arr= explode(".",$arr);
        return (in_array($str, $arr, TRUE))? TRUE: FALSE;
    }
    /**
     * проверяет занечение на соответствие
     * при этом оно  должно  существовать
     *
     */

    public function in_myarray_s($str,$arr){
        if(empty($str) or $str=='') return false;
        $arr= explode(":",$arr);
        return (in_array($str, $arr, TRUE))? TRUE: FALSE;
    }

    /**
     * проверяет занечение на соответствие
     * при этом оно может не существовать
     *
     */

    public function in_myarray_nr($str,$arr){
        if(empty($str)) return true;
        $arr= explode('.',$arr);
        return (in_array($str, $arr, TRUE))? TRUE: FALSE;
    }

    public function check_db($str,$field)
    {
        $workdb= new base_model;
        $code = new code;

        if($field=='email')
                $_POST['email'] = $str = strtolower($str);
        $res = $workdb->db->select($field)->from('users')->where("$field = '".$code->code($str)."'")->get()->row($field);


        if(!empty($this->check_login) and !empty($res))
        {
                return TRUE;
        }
        else
                return (!empty($res))? FALSE:TRUE;

    }

    public function is_email_or_natural($str) {
        return ($this->valid_email($str) or $this->is_natural($str));
    }

    // --------------------------------------------------------------------

    /**
     * Decimal number
     *
     * @access	public
     * @param	string
     * @return	bool
     */
    public function is_decimal_greyter_zero($str)
    {
        if ($str == 0)
            return FALSE;
        return (bool) preg_match('/^[0-9]+?\.[0-9]+$/', $str);
    }
}
