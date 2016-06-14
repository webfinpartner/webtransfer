<? if(!defined('BASEPATH')) exit('No direct script access allowed');

class Code
{
	private static $_secret_key = "a'Ф\"N^в)$#kO;п*t";
	private $fileDelete = array();
	private $_exception = array('id_user', 'parent', 'vip', 'partner', 'date', 'state', 'id', 'reg_date', 'online_date', 'account_verification', 'car', 'work', 'type', 'bot', 'volunteer', 'id_topic', 'for_admin', 'is_admin', 'text');
	private $_group = array("fio" => " ");
	public $mpdf;

	public function  __construct() {
		$this->ci = &get_instance();
		$this->ci->load->library('xxtea');
		$this->ci->coding = $this->ci->xxtea;
	}
    
    
    

	public static function getSecret() {
		return self::$_secret_key;
	}

	public function __invoke($x) {
		return $this->encode($x);
	}

	public function code($var) {
		return base64_encode($this->ci->coding->xxtea_encrypt($var, self::$_secret_key)); //шифрование
	}

	public function decrypt($var) {
		return $this->ci->coding->xxtea_decrypt(base64_decode($var), self::$_secret_key); //расшифровка
	}

	public function decode($var) {
		$r = $this->decrypt($var);
		return (false === $r) ? $var : $r;
	}

	public function encode($var) {
		return $this->code($var);
	}

	public function db_decode($arr) {
		$exp    = $this->_exception;
		$group  = $this->_group;
		if(is_array($arr)) {
			$result = array();
			foreach ($arr as $object) {
				$object_arr = (array)$object;
				foreach ($object_arr as $name => $value) {
					if(!in_array($name, array_merge($exp, array_keys($group)))) {
						$r             = $this->decrypt($value);
						$object->$name = ($r) ? $r : $value;

					} else if(in_array($name, array_keys($group))) {
						$val = explode($group[$name], $value);
						foreach ($val as &$part) $part = $this->decrypt($part);
						$object->$name = implode($group[$name], $val);
					} else $object->$name = $value;
				}
				$result[] = $object;
			}
			return $result;
		} else if(is_object($arr)) {
			$object_arr = (array)$arr;
			foreach ($object_arr as $name => $value) {
				if(!in_array($name, $exp)) {
					$r          = $this->decrypt($value);
					$arr->$name = ($r) ? $r : $value;
				} else $arr->$name = $value;
			}
			return $arr;
		}
	}

	public function request($name) {
		$name = $this->ci->input->get_post($name, TRUE);
		if(!empty($name) or $name == 0) return $this->code($name);
		return "";
	}

	public function createPdf($document, $path, $name, $code = false) {
		if(!class_exists('mPDF')) {
			require('./pdf/mpdf.php');
		}
		error_reporting(0);
		$this->mpdf                          = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
		$this->mpdf->charset_in              = 'utf-8'; /*не забываем про русский*/
		$this->mpdf->list_indent_first_level = 0;
		$this->mpdf->WriteHTML($document, 2); /*формируем pdf*/

		$fullPath = "./upload/$path/$name";
		if($code == true) {
			$code = $this->mpdf->Output($name, 'S');
			$this->fileCodeSave($fullPath, $code);
		} else {
			$this->mpdf->Output($fullPath, 'F');
		}
		error_reporting(E_ALL);
		return $fullPath;
	}

	public function fileDecode($path) {
		return (file_exists($path)) ? $this->decrypt($this->fileCode($path)) : false;
	}

	public function fileCodeSave($path, $code = '') {
		file_put_contents($path, $this->code($code));
	}

	public function fileCode($path) {
		return file_get_contents($path);
	}
        
	public function createImage($path) {
		$data = $this->fileCode($path);
		unlink($path);
		$this->fileCodeSave($path, $data);
	}

	public function fileCodeView($path) {
		$data    = $this->decrypt($this->fileCode($path));
		$fileTmp = 'upload/tmp/' . time() . '-' . basename($path);
		file_put_contents($fileTmp, $data);
		$this->fileDelete[] = $fileTmp;
		return $fileTmp;
	}

	public function viewPdf($path, $code = false) {
		if(ob_get_contents()) ob_end_clean();
		header('Content-Type: application/pdf');
		header('Content-disposition: inline; filename="' . basename($path) . '"');
		$data = $this->fileCode($path);
		echo ($code == true) ? $this->decrypt($data) : $data;
	}

	public function clearCache() {
		foreach ($this->fileDelete as $item) {
			unlink($item);
		}
		$this->fileDelete = array();
	}

	public function __destruct() {
		$this->clearCache();
	}
}
