<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Content {
    const ADMINS_FOLDER = "admin/";
    const USER_FOLDER = "user/";
    const TEMPLATE_FOLDER = "";
    const CONTENT_FOLDER = "content/";
    const WINDOW_FOLDER = "windows/";
    const WINDOW_CONTENT_FOLDER = "content/";
    const WINDOW_LATOUT_FOLDER = "layouts/";
    const WINDOW_BUTTONS_FOLDER = "buttons/";
    const BLOCK_LAYOUT = "layouts/";
    const BLOCK_FOOTER = "footers/";
    const BLOCK_HEADER = "headers/";
    const BLOCK_SUBMENU = "blocks/submenus/";
    const BLOCK_SIDEBAR = "blocks/sidebars/";
    const BLOCK= "blocks/";

    public $isInner = true;
    public $columns = 2;
    public $submenu = '';
    public $prefix = '';
    public $action = '';
    public $layout = 'layout';
    public $window_header_text = 'Не название окно';
    public $window_buttons = 'default';
    public $window_btn_ok_name = 'ОФОРМИТЬ';
    public $window_btn_cancel_name = 'ОТМЕНА';
    public $window_layout = 'layout';
    public $request_method = '';
    public $title = 'Webtrasfer';
    public $win_controller = false;
    protected $is_rendered = false;
    protected $is_admin = false;
    protected $js = [];

    const BLOCK_ARBITRAGE = "user/accaunt/blocks_arbitrage/renderPayment_";
    const BLOCK_ARBITRAGE_PART = "user/accaunt/blocks_arbitrage/renderPayment__part_";

    public $data;

    public function __construct() {
            $this->data = new stdClass();
            $this->ci   = &get_instance();
            $url        = $this->ci->uri->segment(2);
            $this->setPartner();

            if($url != 'admin')
                    $this->ci->load->helper('user');
            else
                    $this->ci->load->helper('admin');

            if(!isset($this->data->page_name))
                    $this->data->page_name = null;

            if($this->ci->input->get('debug_me') == 'i_am_developer' or in_array($this->ci->input->ip_address(), array('86.99.51.213', '194.205.243.174'/*,'178.151.240.40', '94.205.243.174'*/), true))
                    $this->ci->output->enable_profiler(true);

            //get content for left sides everywhere
            $this->ci->load->model('Sides_model','sides');

    }

    public function config($title = '', $keywords = '', $description = '') {
        $this->data->setting     = $this->ci->base_model->settings();
//		$this->data->top_menu    = $this->ci->base_model->top_menu();
        $this->data->title       = $title;
        $this->data->keywords    = $keywords;
        $this->data->description = $description;
    }

    public function index($data) {
        $this->data->title = _e('Главная');
        $this->ci->load->view("user/home_old", $this->mergeData($data));
    }

    public function view($view, $title = '', $vars = array()) {
        $this->data->left_side = $this->ci->sides->get_left_side();
        $vars['title']   = $title;
        $vars = $this->mergeData($vars);
        $vars = array_merge($this->ci->base_model->default_admin(), $vars);

        $vars['content'] = $this->ci->load->view('admin/' . $view, $vars, true);
        $this->ci->load->view('admin/main', $vars);
    }

    public function template($view, $data = array()) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $data             = $this->mergeData($data);
        $data['contents'] = $this->ci->load->view('user/' . $view, $data, true);
        $data['classes'] = 'text-formating';
        $this->ci->load->view("user/main", $data);
    }
// <editor-fold defaultstate="collapsed" desc="index_tamplate">

    public function index_template_2($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index2", $this->mergeData($data));
    }
    public function index_template_3($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index3", $this->mergeData($data));
    }
    public function index_template_4($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index4", $this->mergeData($data));
    }
    public function index_template_5($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index5", $this->mergeData($data));
    }
    public function index_template_6($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index6", $this->mergeData($data));
    }
    public function index_template_7($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();


        $this->data->title = "Главная";
        $this->ci->load->view("user/index7", $this->mergeData($data));
    }
    public function index_template_8($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index8", $this->mergeData($data));
    }
    public function index_template_9($data) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->data->title = "Главная";
        $this->ci->load->view("user/index9", $this->mergeData($data));
    }
// </editor-fold>

    public function public_template($view, $data = array()) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $data = $this->mergeData($data);
        $data['contents'] = $this->ci->load->view('user/' . $view, $data, true);
        $this->ci->load->view("user/public_main", $data);
    }

    public function templateAjax($view, $data = array()) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->ci->load->view('user/' . $view, $this->mergeData($data));
    }
    public function user_ajax_view($view, $data = array()) {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $data = $this->mergeData($data);
        $data_new = array( 'view' => $view, 'data' => $data );
        $this->ci->load->view( 'user/accaunt/main_ajax', $data_new );
    }

    public function user_view($view, $data = array(), $title = '') {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->config('Dashboard / ' . $title);
        $this->ci->load->model('users_photo_model', 'users_photo');
        $ctrl = ($this->clientType == 1) ? "accaunt" : "partner_model";

        $this->data->cache_enable   = true;
        $this->data->title_accaunt  = $title;
        $this->data->newMessage     = $this->ci->inbox->countNew();
        if(10 > count($this->data->accaunt_header))
            $this->data->accaunt_header = $this->ci->$ctrl->get_header_info();

        $this->getUserAvatars($ctrl);
        $this->getParentAvatars($ctrl);

        $data            = $this->mergeData($data);
        $data['content'] = $this->ci->load->view('user/accaunt/' . $view, $data, true);
        $this->ci->load->view("user/accaunt/main", $data);
    }

    public function iframe_user_view($view, $data = array(), $title = '') {
        $this->data->left_side = $this->ci->sides->get_left_side();

        $this->config('Dashboard / ' . $title);
        $this->ci->load->model('users_photo_model', 'users_photo');
        $ctrl = ($this->clientType == 1) ? "accaunt" : "partner_model";

        $this->data->cache_enable   = true;
        $this->data->title_accaunt  = $title;
        $this->data->newMessage     = $this->ci->inbox->countNew();
        if(10 > count($this->data->accaunt_header))
            $this->data->accaunt_header = $this->ci->$ctrl->get_header_info();

        $this->getUserAvatars($ctrl);
        $this->getParentAvatars($ctrl);

        $data            = $this->mergeData($data);
        $data['content'] = $this->ci->load->view('user/accaunt/' . $view, $data, true);
        $this->ci->load->view("user/accaunt/iframe_main", $data);
    }

// <editor-fold defaultstate="collapsed" desc="NEW FUNCTIONS">


    public function renderWindow($action = false, $data = array()) {
        $this->setRendered();
        if ($action)
            $this->action = $action;
        $this->data = $this->mergeData($data);
        $this->_setHeader();
        $this->_setContent();
        $this->_setButtons();
        $this->_setId();
        $this->_viewWindow();
        $this->includeJs();
    }

    protected function _setHeader() {
        $this->data['win_header'] = $this->window_header_text;
    }

    protected function _setContent() {
        $file_path = str_replace('_', '/', $this->action);
        $this->data['win_content'] =
            make(Content::TEMPLATE_FOLDER.Content::WINDOW_FOLDER.Content::WINDOW_CONTENT_FOLDER,$file_path,true);
    }

    protected function _setButtons() {
        $this->data['win_btn_ok_name'] = $this->window_btn_ok_name;
        $this->data['win_btn_cencel_name'] = $this->window_btn_cancel_name;
        $this->data['win_buttons'] = make(Content::TEMPLATE_FOLDER.Content::WINDOW_FOLDER.Content::WINDOW_BUTTONS_FOLDER,$this->window_buttons,true);
    }

    protected function _setId() {
        $this->data['win_id'] = $this->action;
    }

    protected function _viewWindow() {
        echo make(Content::TEMPLATE_FOLDER.Content::WINDOW_FOLDER.Content::WINDOW_LATOUT_FOLDER,$this->window_layout, true);
    }

    public function render($action = false, $prefix = false, $data = array()){
        $this->setRendered();
        if ($prefix)
            $this->prefix = $prefix;
        if ($action)
            $this->action = $action;

//        $this->data->inner = ($this->isInner) ? 'class="inner"' : '';

        if($this->ci->input->is_ajax_request()){
            $this->data = $this->mergeData($data);
            $this->viewAjax();
        } else {
            if($this->is_admin)
                $this->view($this->prefix.$this->action, $this->title, $data);
            else
                $this->user_view($this->prefix.$this->action, $data, $this->title);
//            $this->setHeader();
//            $this->setSidebar();
//            $this->setContent();
//            $this->viewSimple();
        }
        $this->includeJs();
    }

    public function isRendered() {
        return $this->is_rendered;
    }

    public function setAdmin() {
        $this->is_admin = true;
    }

    protected function setRendered() {
        $this->is_rendered = true;
    }

    protected function setHeader(){
        //Set submenu
        $this->data['submenu'] = ($this->submenu && $this->isInner) ?
            make(Content::TEMPLATE_FOLDER.Content::BLOCK_SUBMENU,$this->submenu,true) :
            "";

        //Headers part
        $this->data['header_bottom'] = ($this->isInner) ?
            make(Content::TEMPLATE_FOLDER.CONTENT::BLOCK_HEADER,'bottom',true) :
            ""; // внутри используется submenu

        $this->data['header_top'] = ($this->data['user_is_login']) ?
            make(Content::TEMPLATE_FOLDER.CONTENT::BLOCK_HEADER,'top_logged',true) :
            make(Content::TEMPLATE_FOLDER.Content::BLOCK_HEADER,'top',true);


    }

    protected function setSidebar() {
        // set partner block
        $this->data['partner'] = ($this->data['user_is_login']) ?
            make(Content::TEMPLATE_FOLDER.Content::BLOCK,'partner',true) :
            "";

        // создаем сайтбары
        $this->data['sidebars'] = '';
        foreach (config_item('sidebars') as $sidebar) {
            $this->data['sidebars'] .= make(Content::TEMPLATE_FOLDER.Content::BLOCK_SIDEBAR,$sidebar,true);
        }
    }

    protected function setContent(){
        $this->data['columns'] = $this->columns;
        $this->data['content'] = make($this->prefix, $this->action, true);
    }

    protected function viewAjax(){
        echo make($this->prefix,$this->action,true);
        return;
    }

    protected function viewSimple(){
        echo make(Content::TEMPLATE_FOLDER.Content::BLOCK_LAYOUT,$this->layout, true);
        return;
    }

    public function addJs($path){
        if(file_exists(FCPATH."js/$path.js"))
            $this->js[] = "/js/$path.js";
    }

    protected function includeJs() {
        $js = '';
        foreach ($this->js as $jsFile) {
            $js .= "Loader.includeOnce('$jsFile');";
        }
        if($js)
            echo "<script>$js</script>";
    }
// </editor-fold>

    private function mergeData($data){
            if(empty($data)) $data = array();
            $data = (is_object($data)) ? get_object_vars($data) : $data;
            return array_merge($data, get_object_vars($this->data));
    }

    private function getUserAvatars($ctrl) {
        if(!$this->data->accaunt_header['photo_50']){
            if(!isset($_SESSION["avatar"]) || empty( $_SESSION["avatar"] )){
                $photo_50 = $photo_100 = getUserAvatar($this->ci->$ctrl->user->id_user);
                if( !isset( $_SESSION["avatar"] ) ) $_SESSION["avatar"] = [];

                $_SESSION["avatar"]["norm"] = $photo_50;
                $_SESSION["avatar"]["_100"] = $photo_100;
                $_SESSION["avatar"]["time"] = time();
            } else {
                $photo_50 = $_SESSION["avatar"]["norm"];
                $photo_100 = $_SESSION["avatar"]["_100"];
                if ((time() - $_SESSION["avatar"]["time"]) > 86400)
                    unset($_SESSION["avatar"]);
            }
            $this->data->accaunt_header['photo_50']  = $photo_50;
            $this->data->accaunt_header['photo_100'] = $photo_100;
        }
    }

    private function getParentAvatars($ctrl) {
        if(!(isset($this->data->parentUser) && isset($this->data->parentUser->id_user)))
            return;

        if(!isset($_SESSION["parent_avatar"]) || empty( $_SESSION["parent_avatar"] )){
            $photo_50 = $photo_100 = getUserAvatar($this->data->parentUser->id_user);
            if( !isset( $_SESSION["parent_avatar"] ) ) $_SESSION["parent_avatar"] = [];

            $_SESSION["parent_avatar"]["norm"] = $photo_50;
            $_SESSION["parent_avatar"]["_100"] = $photo_100;
            $_SESSION["parent_avatar"]["time"] = time();
        } else {
            $photo_50 = $_SESSION["parent_avatar"]["norm"];
            $photo_100 = $_SESSION["parent_avatar"]["_100"];
            if ((time() - $_SESSION["parent_avatar"]["time"]) > 86400)
                unset($_SESSION["parent_avatar"]);
        }
        $this->data->accaunt_header['parent_photo_50']  = $photo_50;
        $this->data->accaunt_header['parent_photo_100'] = $photo_100;

        $social = $this->ci->$ctrl->accaunt->getSocialList($this->data->parentUser->id_user);
        $this->data->accaunt_header['parent_social']    = $social;
    }

    private function setPartner() {
            if(!empty($_GET['id_partner'])) {
                    $time = 60 * 60 * 24 * 30 * 3;

                    $cookie = array(
                            'name'   => 'id_partner',
                            'value'  => (int)$_GET['id_partner'],
                            'expire' => $time,
                            'domain' => '',
                            'path'   => '/',
                            'prefix' => '',
                    );

                    $this->ci->input->set_cookie($cookie);
                    $this->data->id_partner = (int)$_GET['id_partner'];
                    redirect('partner/id-'.(int)$_GET['id_partner']);
            }
    }
}