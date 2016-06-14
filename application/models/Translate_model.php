<?php

class Translate_model extends CI_Model
{

     private function _getExtension1($filename) {
          return substr($filename, strrpos($filename, '.') + 1);
    }

    
    private function _find_e ($lang, $patch, &$all_strings) {
        
        $handle = opendir($patch);

         while(($file = readdir($handle))) {

           if (is_file ($patch."/".$file) && $this->_getExtension1($file) == "php" && $file != 'index.php'  ){
            //echo $patch .  "/" . $file.'<br>';
            $fhandle = fopen($patch .  "/" . $file, "r");
            while (!feof($fhandle)) {
                $buffer = fgets($fhandle);
                //if (preg_match_all('/_e\(.*\)/', $buffer, $matches)){
                if (preg_match_all('/_e\([ ]*["\']+(.*?)["\']+[ ]*\)[;]?/', $buffer, $matches)){
                    //echo '<pre>';var_dump($matches);echo '</pre>';
                    foreach( $matches[1] as $m){
                      if (!isset($all_strings[$m]) && empty($lang->line($m))){
                          $m = str_ireplace('<', '&#60;', $m);
                          $m = str_ireplace('>', '&#62;', $m);
                          
                          $all_strings[$m] = 1;
                              //echo "\$lang['$m'] = '';<br>";
                      }
                    }
                    flush();
                }
                
            }
            fclose($fhandle);
           }
           if (is_dir ($patch."/".$file) && ($file != ".") && ($file != ".."))
             $this->_find_e($lang,$patch."/".$file, $all_strings);  // Обходим вложенный каталог
         }
         closedir($handle);
         
       }    
       
      private function _find_not_e ($lang, $patch, &$all_strings) {
        $handle = opendir($patch);

         while(($file = readdir($handle))) {

           if (strpos($patch, 'controllers/user') > 0  )  
           if (is_file ($patch."/".$file) && $this->_getExtension1($file) == "php" && $file != 'index.php'  ){
            echo '-<b>'.$patch .  "/" . $file.'</b><br>';
            $fhandle = fopen($patch .  "/" . $file, "r");
            while (!feof($fhandle)) {
                $buffer = fgets($fhandle);
                //if (preg_match_all('/_e\(.*\)/', $buffer, $matches)){
                if (preg_match_all('/^(.*[А-Яа-я]+.*)$/', $buffer, $matches)){
                    //echo '<pre>';var_dump($matches);echo '</pre>';
                    if ( count($matches[1]) > 0 ){
                        foreach( $matches[1] as &$m){
                             if (strpos($m, '_e') <=0 ){
                                $m = str_ireplace('script', 'script!', $m);
                                $m = str_ireplace('img', 'image!', $m);
                                $m = str_ireplace('<', '&lt;', $m);
                                $m = str_ireplace('>', '&gt;', $m);
                                $m = str_ireplace('&lt;br&gt;', '<br>', $m);
                                $m = str_ireplace('&lt;br/&gt;', '<br>', $m);
                                echo $m;
                             }
                        }
                    }
                    flush();
                }
                
            }
            fclose($fhandle);
           }
           if (is_dir ($patch."/".$file) && ($file != ".") && ($file != ".."))
             $this->_find_not_e($lang,$patch."/".$file, $all_strings);  // Обходим вложенный каталог
         }
         closedir($handle);
         
       }
       
    
   /**
     * Проходит все _e(*) в коде и выводит непереведнные на английский
     */
   public function find_untransalated_strings($return = FALSE){
       $this->lang->load('project', 'english');
       $all_strings = array();
     
       $this->_find_e($this->lang, "./application", $all_strings);  
       if ( $return ){
           $ret = '';
           foreach( array_keys($all_strings) as $key)
            $ret .= "\$lang['$key'] = '';\n";
           return $ret;
       } else {
         echo '<pre>';
         foreach( array_keys($all_strings) as $key)
            echo "\$lang['$key'] = '';<br>";
         echo count($all_strings);
       }
        
    }    
    
   /**
     * Проходит все файлы в коде и выводит русский текст где нет _e
     */
   public function find_russian_strings(){
     $all_strings = array();
     
     echo "<pre>";
       $this->_find_not_e($this->lang, "./application", $all_strings);    
       echo '<pre>';
       foreach( array_keys($all_strings) as $key)
        echo "$key<br>";
       
       echo count($all_strings);
        
    }        

    
}