<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Table_controller')) {
    require APPPATH . 'libraries/Table_controller.php';
}

class Feedback extends Table_controller {

    public function __construct() {
        parent::__construct();
        admin_state();
        $this->load->model('feedback_model', 'feedback');
        $this->cols = array(
            'id' => "int",
            'id_user' => "int",
            'name' => "text",
            'status' => "int"
        );
        $this->cols_bots = array(
            'id' => "int",
            'to' => "int",
            'message' => "text",
            'sent' => "int"
        );
        $this->header = array(
            "title" => "База чатов",
            "columns" => array(
                "id" => array(
                    "title" => "ID",
                    "filter" => false,
                    "order" => true
                ),
                "id_user" => array(
                    "title" => "ID пользователя",
                    "filter" => false,
                    "order" => true
                ),
                "name" => array(
                    "title" => "Название",
                    "filter" => false,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'row_highlight',
                        "widget_options" => array(
                            "col_name" => "admin",
                            "col_val" => "1"
                        )
                    )
                ),
                "status" => array(
                    "title" => "Статус",
                    "filter" => false,
                    "order" => true,
                    "formatter" => array(
                        "widget" => 'list',
                        "widget_options" => array(
                            "list" => array(
                                1 => "<center><img src='images/icons/152.png' /></center>",
                                2 => "<center><img src='images/icons/151.png' /></center>"
                            )
                        )
                    )
                )
            )
        );
        $this->header_bots = array(
            "title" => "VIP Чат",
            "columns" => array(
                "to" => array(
                    "title" => "ID Бота",
                    "filter" => false,
                    "order" => true
                ),
                "from" => array(
                    "title" => "ID пользователя",
                    "filter" => false,
                    "order" => true
                ),
//                "name"=> array(
//                    "title" =>  "Имя",
//                    "filter"=> false,
//                    "order" =>  true,
////                    "formatter" => array(
////                        "widget" => 'row_highlight',
////                        "widget_options" => array(
////                            "col_name" => "admin",
////                            "col_val" => "1"
////                        )
////                    )
//                ),
//                "sername"=> array(
//                    "title" =>  "Фамилия",
//                    "filter"=> false,
//                    "order" =>  true,
////                    "formatter" => array(
////                        "widget" => 'row_highlight',
////                        "widget_options" => array(
////                            "col_name" => "admin",
////                            "col_val" => "1"
////                        )
////                    )
//                ),
                "count_m" => array(
                    "title" => "Кол-во сообщений",
                    "filter" => false,
                    "order" => true
                ),
                "count_u" => array(
                    "title" => "Кол-во пользователей",
                    "filter" => false,
                    "order" => true
                ),
                "sent" => array(
                    "title" => "Дата последнего сообщения",
                    "filter" => false,
                    "order" => true,
//                    "formatter" => array(
//                        "widget" => 'date_unix_time',
//                        "widget_options" => array(
//                            "formatte" => 'h:m:s D.M.Y'
//                        )
//                    )
                )
            )
        );
    }

    public function admin_support_ask() {
        $cols = $this->cols;
        $header = $this->header;
        $table = "volunteer_topic";

        $this->_tableWork("support_ask", compact("cols", "table", "header"));

        $data['url'] = "/opera/feedback/admin_support_ask";
        $data["controller"] = "feedback";
        $this->content->view('admin_support', "", $data);
    }

   
    public function send_admin_vip_message() {
        $this->base_model->redirectNotAjax();
        $this->load->model('cometchat_model', 'cometchat');
        $to = (int) $this->input->post("to");
        $from = (int) $this->input->post("from");
        $txt = $this->input->post("txt", true);
        $this->cometchat->addMessage($txt, $to, $from);
        $this->get_last_vip_message();
    }

    public function get_last_vip_message() {
        $id = (int) $this->input->post("id");
        $me = (int) $this->input->post("to");
        $from = (int) $this->input->post("from");
        $this->load->model('cometchat_model', 'cometchat');
        $this->load->model('cometchat_status_model', 'cometchat_status');
        $this->cometchat_status->setOnline($me);
        $this->load->model('users_model', 'users');
        $res = $this->cometchat->getLastMessage($id, $me, $from);

        $me = $this->users->getUserData($me);
        $from = $this->users->getUserData($from);

        $r = array();
        foreach ($res as $row) {
            $r[] = array(
                "meORnot" => (($me->id_user == $row->from) ? "me" : "other"),
                "id" => $row->id,
                "label" => (($me->id_user == $row->from) ? "Я" : "$from->name $from->sername"),
                "date" => date('d.m.y H:i', $row->sent),
                "message" => $row->message
            );
        }
        echo json_encode($r);
    }



    public function admin_support() {
        $cols = $this->cols;
        $header = $this->header;
        $table = "volunteer_topic";

        $this->_tableWork("support", compact("cols", "table", "header"));

        $data['url'] = "/opera/feedback/admin_support";
        $data["controller"] = "feedback";
        $this->content->view('admin_support', "", $data);
    }

    public function admin_support_topic($id = NULL) {
        if (null == $id)
            show_404();
        $this->load->model('volunteer_topic_model');
        $data["topics"] = $this->volunteer_topic_model->getMess4Admin((int) $id);
        $data["me"] = Permissions::getInstance()->getAdminId();
        $this->content->view('admin_support_topic', "Тема чата", $data);
    }

    public function ajaxSendQuestion() {
        $this->load->library('form_validation');
        $this->load->model('volunteer_topic_model');
        $this->load->model('volunteer_message_model');
        $txt = htmlentities(removeXSS($this->input->post('text', true)), ENT_QUOTES | ENT_HTML5 | ENT_IGNORE, "UTF-8");
        $tpc = htmlentities(removeXSS($this->input->post('topic', true)), ENT_QUOTES | ENT_HTML5 | ENT_IGNORE, "UTF-8");
        $id_topic = (int) $this->input->post('id');
        $for_admin = Volunteer_message_model::FOR_ADMIN_FALSE;
        $from_admin = ("true" == $this->input->post("from_admin")) ? Volunteer_message_model::FOR_ADMIN_TRUE : Volunteer_message_model::FOR_ADMIN_FALSE;

        if (empty($txt)) {
            echo json_encode(array("e" => "no_text"));
            return;
        }

        if (empty($id_topic))
            $id_topic = $this->volunteer_topic_model->getTopicId($tpc);
        $this->volunteer_message_model->addMessage($id_topic, $txt, $for_admin, $from_admin, Permissions::getInstance()->getAdminId());

        $this->load->library("mail");
        $id_owner = $this->volunteer_topic_model->getOwnerTopic($id_topic);
        $id_parent = $this->users->getParent($id_owner);
        $this->mail->addMessagesFromAdmin($txt, $tpc, array("owner" => $id_owner, "parent" => $id_parent));

        echo json_encode(array("e" => ""));
    }

    public function closeTopic($id = null) {
        if (null == $id)
            show_404();
        $this->load->model('volunteer_topic_model');
        $this->volunteer_topic_model->setClose($id);
        redirect(base_url() . "opera/feedback/admin_support");
    }

    /*
     * Выводит список новых сообщений
     */

    public function new_feeds() {
        $data['feedback'] = $this->feedback->new_feeds();
        // Подключение layout (шаблона) отображения
        $this->content->view('feedbacks', "", $data);
    }

    public function all_feeds() {
        $data['feedback'] = $this->feedback->all_feeds();
        // Подключение layout (шаблона) отображения
        $this->content->view('feedbacks', "", $data);
    }

    /*
     *  Выводит страницу сообщения
     */

    public function index($id = 0) {
        $id = intval($id);
        $data = $this->feedback->get_feedback($id);
        if ($data['item']->admin_state == 1) {
            $this->feedback->feed_readen($id);
        }

        if (!empty($_POST['message'])) {
            $this->feedback->add_answer($data, $_POST['message']);
            $data = $this->feedback->get_feedback($id);
        }
        $this->content->view('feedback', '', $data);
    }

    function close($id = 0) {
        $id = intval($id);
        $this->feedback->feed_close($id);
    }

    function open($id = 0) {
        $id = intval($id);
        $this->feedback->feed_open($id);
    }

    /**
     * Удаление страницы
     */
    public function delete($id = 0) {
        $id = intval($id);
        $this->db->where('id', $id)->delete('feedback');
        $this->db->where('id_back', $id)->delete('feedback_answer');
        $this->info->add("delete_yes");
        redirect(base_url() . 'opera/feedback/all_feeds');
    }

}
