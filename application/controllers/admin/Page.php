<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!class_exists('Admin_controller')) {
    require APPPATH . 'libraries/Admin_controller.php';
}

class Page extends Admin_controller {

    public function __construct() {
        parent::__construct();

        $data = array(
            'id_parent' => 'parent',
            'title',
            'content',
            'shablon',
            'url',
            'active',
            'sort',
            'meta_words',
            'meta_descript',
            'lang',
            'master',
        );
        $this->id = "id_page";
        $this->safe_url_lang = 'title';
        $this->lang_coll = "lang";
        $setting = array('ctrl' => 'page',
            'view' => 'page',
            'table' => 'pages',
            'argument' => $data
        );
        $this->setting($setting);
        $this->data['view_all'] = array(
            "one" => "Страницу",
            "many" => "Структурой Сайта",
            "fields" => array(
                "Наименование" => "title",
                "Ссылка" => "url",
                "Родитель" => "{function:page_parent(title_parent)}",
                "action" => "{function:admin_active(active)}",
            )
        );
    }

    /*
     * Выводит список страниц
     */

    public function all() {
        $this->data['items'] = $this->base_model->get_pages();
        parent::all();
    }

    /**
     * Удаление страницы
     */
    public function delete($id = 0) {
        $id = intval($id);
        $is_parent = $this->db->select('id_page')->from('pages')->where('id_parent', $id)->get()->result();
        if (empty($is_parent)) {
            $this->db->where('id_page', $id)->delete('pages');
            $this->info->add("delete_yes");
        } else {
            $this->info->add("delete_no");
        }
        redirect(base_url() . 'opera/page/all');
    }

}

function page_parent($title) {
    return (empty($title)) ? "Родитель" : $title;
}
