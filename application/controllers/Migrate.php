<?php
class Migrate extends CI_Controller
{

    public function index()
    {

        if (!isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != "migrate" && $_SERVER['PHP_AUTH_PW'] != "ready") {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authorisation required';
            exit;
        } else {
            $this->load->library('migration');


            if ($this->migration->current() === FALSE)
            {
                show_error($this->migration->error_string());
            }
        }

    }

}