<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function pagination($limit,$count, $url,$segment,$addition=array())
	{
            $CI = &get_instance();
            $CI->load->library('pagination');

            $config = array(
                'base_url' => site_url('/') . '/' .$url,
                'uri_segment' => $segment ,
                'total_rows' => $count,
                'per_page' => $limit,

                'first_link' => '<<',
                'last_link' => '>>',
                'next_link' => '>',
                'prev_link' => '<',
                'num_links' => 5,
            );
            
        $CI->pagination->initialize(array_merge($addition,$config));

        return $CI->pagination->create_links();
	}