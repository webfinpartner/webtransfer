<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'libraries/Permissions_controller.php';
require_once APPPATH.'libraries/Table_controller.php';

class Admin_controller extends Table_controller
{
	protected $data = array();
	protected $id = 'id';
	public    $info_state = true;
	protected $all;
	protected $element_id;
	protected $redirect=false;
	protected $index_redirect=false;
	protected $safe_url=false;

	public function __construct()
	{
		parent::__construct();
		admin_state();
		$this->output->enable_profiler(false);

	}

	protected function setting($setting)
	{
		$this->ctrl=$this->data['controller']=$setting['ctrl'];
		$this->all=base_url() . 'opera/'.$this->ctrl.'/all';
		$this->view=(empty($setting['view']))?$this->ctrl:$setting['view'];
		$this->view_all=(empty($setting['views']))?"blocks/view_all_tpl":$setting['views'];
		$this->table=$setting['table'];
		$this->title=(empty($setting['title']))?"":$setting['title'];
		$this->argument=$setting['argument'];
		$this->data['id_element']=$this->id;
		if(!empty($setting['image']))
		{
			$this->load->library('image');
			$this->image_folder=$this->data['image_folder']=$setting['image'];
			$this->image->place="upload/".$setting['image'].'/';
			$this->tb_foto=(!empty($this->tb_foto))?$this->tb_foto:'foto';
			$this->data['tb_foto']=$this->tb_foto;
		}
		$this->data['info_state']=$this->info_state;

		if($this->info_state) $this->load->library('info');
	}

	    private function uploadfile($input_name) {
        $uploaddir = $_SERVER['DOCUMENT_ROOT']. '/upload/';
        $new_random_name = str_replace('==','', base64_encode(openssl_random_pseudo_bytes(10)));
        $extension = new SplFileInfo($_FILES[$input_name]['name']);
        $uploadfile = $uploaddir . $new_random_name .'.'. $extension->getExtension();

        if(move_uploaded_file($_FILES[$input_name]['tmp_name'], $uploadfile)) {
            return $new_random_name .'.'. $extension->getExtension();
        } else {
            return FALSE;
        }
    }

	public function index($id=0)
	{
            $id = intval($id);
            $this->element_id= $id;
            if(empty($id)) redirect($this->all);

            if(!empty($_POST['submited']))
            {
                $data= $this->_request();

                $foto_name = $this->uploadfile('foto');
	            if($foto_name !== FALSE) {
	                $data['foto'] = $foto_name;
	            }

                if( isset( $data['user_ip'] ) ) unset( $data['user_ip'] );

                $this->db->where($this->id,$id)->update($this->table,$data);

                if($this->info_state) $this->info->add("edit");
                if($this->index_redirect==false){redirect($this->all); exit();}
            }

            if(!isset($this->data['item']))
                    $this->data['item']= $this->db->get_where($this->table, array($this->id => $id))->row();

            $this->data['state'] = $id;
            $this->data['status'] = $this->data['item']->status;
            $this->content->view($this->view,'',$this->data);
	}


	public function all()
	{

            if(!isset($this->data['items']))
            $this->data['items']= $this->db->get($this->table)->result();
            $this->content->view($this->view_all, $this->title, $this->data);
	}

	public function create($id=0){
		if(empty($_POST['submited'])){
                    $this->data["is_user_active"] = true;
                    $this->content->view($this->view,"",array_merge(array('state' =>"create"), $this->data));
                } else {
			$data= $this->_request();

            $this->db->insert($this->table,$data);
            $error = $this->db->error();

            $this->element_id= $this->db->insert_id();
            $this->load->model('Admin_changes_model');
            $this->Admin_changes_model->add('transactions', $this->element_id, 'add new', '', json_encode($data) );

            if( !empty( $error ) && isset( $error['code'] ) && $error['code'] != 0 )
            {
                var_dump($error);
                die;
                if($this->redirect==true)return;
                redirect($this->all);
            }

			if($this->info_state)$this->info->add("add");

			if($this->redirect==true)return;
			redirect($this->all);
		}
	}

	public function delete($id=0)
	{
		$id = intval($id);
		if(!empty($this->image->place))
		{
			$image_del=$this->db->select($this->tb_foto)->from($this->table)->where($this->id,$id)->get()->row($this->tb_foto);
			if(!empty($image_del))
			{
				unlink($this->image->place.$image_del);
			}
		}
		$this->db->where($this->id,$id)->delete($this->table);
		if($this->info_state)$this->info->add("delete_yes");
		redirect($this->all);
	}

	protected function _request()
	{
		if(!empty($this->argument))
		{
			foreach($this->argument as $name=>$value) // имя поля в бд =>  имя поля приходящего  запроса
			{
				if(is_integer($name)) $name = $value;

				$data[$name]=$this->input->post($value);

				if($name=="foto") unset($data[$name]);

				if($name=="foto" and empty($this->element_id))
                                    $data[$name]=$this->get_session_foto();
				else if(preg_match('/active/sui', $value))
                                    $data[$name]=($data[$name]=="")? 2:1;
				else if (preg_match('/url/sui', $value) and $this->safe_url!=false){
                                    if(empty($data[$name])) $data[$name]=preg_replace('/[^a-zа-я 0-9\~\%\.\:_\\ \-]+/sui',"_",$data[$this->safe_url]);
                                    else $data[$name]=preg_replace('/[^a-zа-я 0-9\~\%\.\:_\\ \-]+/sui',"_",$data[$name]);
                                    $data[$name]= $this->unic_url($data[$name],$name); // уникальность  url
				} else if (preg_match('/url/sui', $value) and $this->safe_url_lang!=false){
                                    if(empty($data[$name])) $data[$name]=preg_replace('/[^a-zа-я 0-9\~\%\.\:_\\ \-]+/sui',"_",$data[$this->safe_url]);
                                    else $data[$name]=preg_replace('/[^a-zа-я 0-9\~\%\.\:_\\ \-]+/sui',"_",$data[$name]);
                                    $data[$name]= $this->unic_url_lang($data[$name],$name); // уникальность  url
				}

				$this->data_obj->$name = @$data[$name];
			}
			return $data;
		}
		else die("Отсутствуют аргументы");
	}


	private function get_session_foto()
	{
		$foto	   = !empty($_SESSION['foto'])?$_SESSION['foto']:"";
		$foto_name = !empty($_SESSION['foto_name'])?$_SESSION['foto_name']:"";
		$_SESSION['foto']="";
		$_SESSION['foto_name']='';
		if(!empty($foto) and  !empty($foto_name))
		{
			$f = fopen($this->image->place.$foto_name,"w+");   //открываем файл
			fwrite($f, $foto);
			fclose($f);
		}
		return $foto_name;
	}

	public function unic_url($text, $url)
	{
            if(!empty($this->element_id))
                    $where[$this->id.' !=']=$this->element_id; // при сохранении не включать  проверку элемента  под  своим  жде  id
            else $where=array();

            $i=1;
            $text2= $text;
            $res = $this->db->get_where($this->table,array_merge(array($url=>$text), $where))->row();
            while (!empty($res))
            {
                    $text2= $text."_$i";
                    $res = $this->db->get_where($this->table,array_merge(array($url=>$text2), $where))->row();
                    $i++;
            }
            return $text2;
	}

	public function unic_url_lang($text, $url)
	{
            $lang = $this->input->post($this->lang_coll);
            if(!empty($this->element_id))
                    $where[$this->id.' !=']=$this->element_id; // при сохранении не включать  проверку элемента  под  своим  жде  id
            else $where=array();

            $i=1;
            $text2= $text;
            $res = $this->db->get_where($this->table,array_merge(array($url=>$text, $this->lang_coll => $lang), $where))->row();
            while (!empty($res))
            {
                    $text2= $text."_$i";
                    $res = $this->db->get_where($this->table,array_merge(array($url=>$text2, $this->lang_coll => $lang), $where))->row();
                    $i++;
            }
            return $text2;
	}

	//фото
	public function delete_foto($id=0)
	{
		if(!empty($this->image->place))
		{
			$image_del=$this->db->select($this->tb_foto)->from($this->table)->where($this->id,$id)->get()->row($this->tb_foto);
			if(!empty($image_del))
			{
				unlink($this->image->place.$image_del);
				$this->db->where($this->id,$id)->update($this->table,array($this->tb_foto=>""));
			}
		}
	}

	public function get_foto()
	{
		header("Content-Type: image/jpg");
		echo $_SESSION["foto"];
	}

	public  function del_ses_foto()
	{
		$_SESSION['foto']="";
		$_SESSION['foto_name']='';
	}

	public function add_foto($id=0)
	{
		if(!empty($this->image->place))
		{
			$image= $this->image;
			$foto=$image->file('foto');
			if(in_array($foto, array(1,2,3), true))
			{
				$error="";
				if($foto==1)$error="Пожалуйста, попробуйте еще раз отправить картинку";
				if($foto==3)$error="Размер  картинки не должен  превышать 1Мб";
				if($foto==2)$error="Формат  изображения должен быть: jpeg, jpg, png, gif";
				echo json_encode(array('error'=>"yes",'info'=>$error));
			}
			else
			{
				if(!empty($id))
				{
					$add_foto= $image->add_foto($foto);

					if(!empty($add_foto))
					{
						$this->delete_foto($id);
						$this->db->where($this->id, $id)->update($this->table,array($this->tb_foto=>$add_foto));
						echo json_encode(array('error'=>"no","info"=>$add_foto));
					}
				}
				else
				{
					$add_foto= $image->add_foto($foto);
					$fileName = $this->image->place.$add_foto;  //имя файла
					$f = fopen($fileName,"r");   //открываем файл
					$read = fread($f,filesize($fileName));  //считываем содержимое
					fclose($f);
					unlink($this->image->place.$add_foto);
					$_SESSION["foto"]=$read;
						$_SESSION["foto_name"]=$add_foto;
						echo json_encode(array('error'=>"no",'info'=>"ok"));
				}
			}
		}
	}
}

/*
 * {function:caty(parent)}
 * {price} руб.
 * price
 */
function tpl_field($text,$item)
{
	$replace=array();
	preg_match_all('/{([a-z_]+)}/sui', $text, $matches, PREG_SET_ORDER);
	preg_match_all('/{function\:([a-z0-9_\,]+)\(([a-z_:0-9]+)\)}/sui', $text, $functions, PREG_SET_ORDER);

	if(!empty($functions))
	{
		foreach($functions as $var)
		{
			$index=$var[0];
			if (function_exists($var[1]))
			{

			if(preg_match("/^([a-z_]+):([0-9]+)$/sui",$var[2] , $ex))
			$replace[$index]=$var[1]($item->$ex[1], $ex[2]);
			else $replace[$index]=$var[1]($item->$var[2]);
			}
		}

		$text = strtr($text,$replace);
	}
	$replace=array();
	if(!empty($matches))
	{
		foreach($matches as $var)
		{
			$index=$var[0];
			$replace[$index]=$item->{$var[1]};
		}
		$text = strtr($text,$replace);
	}

	if(empty($functions) and empty($matches))return $item->$text;
	else return $text;
}

function  admin_active($active)
{
	return ($active==1)?'<img src="images/icons/152.png">':'<img src="images/icons/160.png">';
}