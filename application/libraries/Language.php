<?php
class Language {
	function __construct() {
		$this->ci = & get_instance();
		$this->ci->load->helper('language');
		$this->ci->load->helper('url');

		// load language file
		$this->ci->lang->load('project');
	}
}