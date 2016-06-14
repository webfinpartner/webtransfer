<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SimpleREST_controller extends CI_Controller {

    public function __construct($redirect = true, $userRating = false, $skipLogin = false){
        parent::__construct();
        view()->prefix = Content::TEMPLATE_FOLDER.Content::CONTENT_FOLDER.strtolower($this->router->fetch_class())."/";
        view()->submenu = strtolower($this->router->fetch_class());
    }
    /**
     * Remap
     *
     * Requests are not made to methods directly, the request will be for
     * an "object". This simply maps the object and method to the correct
     * Controller method.
     *
     * @access public
     * @param  string $object_called
     * @param  array  $arguments     The arguments passed to the controller method.
     */
    public function _remap($method, $params = []){
        $postfix = strtolower($this->input->server('REQUEST_METHOD'));
        $this->req_params = $params;
        view()->data->page_name = $method; // для подсветки пункта меню в второй линии
        view()->action = $method; // для определения меню вьюхи по дефаулту и для определения id окна в менеджере окон
        view()->request_method = $postfix;
        $method_rest = $method."_".$postfix;

        if (method_exists($this, $method_rest)){
            return call_user_func_array(array($this, $method_rest), $params);
        } elseif(method_exists($this, $method)){
            return call_user_func_array(array($this, $method), $params);
        } else {
            throw new Exception('такого метода или контролера не существует :(');
        }
    }

}