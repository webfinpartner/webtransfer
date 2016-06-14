<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _e($line){
    $CI =& get_instance();
    if(is_string($line)) $tr_line = $CI->lang->line($line, FALSE);
    else $tr_line = false;
    return ($tr_line) ? $tr_line : $line;
}

function lang($line, $id = '')
{ return "просьба использовать функцию _e() вместо lang()";
	$CI =& get_instance();
	$line = $CI->lang->line($line);

	$args = func_get_args();

	if(is_array($args)) array_shift($args);

	if(is_array($args) && count($args))
	{
		foreach($args as $arg)
		{
			$line = str_replace_first('%s', $arg, $line);
		}
	}

	if ($id != '')
	{
		$line = '<label for="'.$id.'">'.$line."</label>";
	}

	return $line;
}

function str_replace_first($search_for, $replace_with, $in)
{
	$pos = strpos($in, $search_for);
	if($pos === false)
	{
		return $in;
	}
	else
	{
		return substr($in, 0, $pos) . $replace_with . substr($in, $pos + strlen($search_for), strlen($in));
	}
}

/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper */