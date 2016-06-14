<?php
#################################################################
## MyPHPAuction v6.04															##
##-------------------------------------------------------------##
## Copyright ©2009 MyPHPAuction. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

class image
{
	var $cache_dir = 'cache/';
	var $image_basedir="banner/";

	function gd_info_alternate()
	{
		$array = Array(
			"GD Version" => "",
			"FreeType Support" => 0,
			"FreeType Support" => 0,
			"FreeType Linkage" => "",
			"T1Lib Support" => 0,
			"GIF Read Support" => 0,
			"GIF Create Support" => 0,
			"JPG Support" => 0,
			"PNG Support" => 0,
			"WBMP Support" => 0,
			"XBM Support" => 0
		);

		$gif_support = 0;

		ob_start();
		eval("phpinfo();");
		$info = ob_get_contents();
		ob_end_clean();

		foreach(explode("\n", $info) as $line)
		{
			if(strpos($line, "GD Version")!==false)
				$array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
			if(strpos($line, "FreeType Support")!==false)
				$array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
			if(strpos($line, "FreeType Linkage")!==false)
				$array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
			if(strpos($line, "T1Lib Support")!==false)
				$array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
			if(strpos($line, "GIF Read Support")!==false)
				$array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
			if(strpos($line, "GIF Create Support")!==false)
				$array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
			if(strpos($line, "GIF Support")!==false)
				$gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
			if(strpos($line, "JPG Support")!==false)
				$array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
			if(strpos($line, "PNG Support")!==false)
				$array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
			if(strpos($line, "WBMP Support")!==false)
				$array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
			if(strpos($line, "XBM Support")!==false)
				$array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
		}

		if($gif_support==="enabled")
		{
			$array["GIF Read Support"]  = 1;
			$array["GIF Create Support"] = 1;
		}

		if($array["FreeType Support"]==="enabled")
			$array["FreeType Support"] = 1;

		if($array["T1Lib Support"]==="enabled")
			$array["T1Lib Support"] = 1;

		if($array["GIF Read Support"]==="enabled")
			$array["GIF Read Support"] = 1;

		if($array["GIF Create Support"]==="enabled")
			$array["GIF Create Support"] = 1;

		if($array["JPG Support"]==="enabled")
			$array["JPG Support"] = 1;

		if($array["PNG Support"]==="enabled")
			$array["PNG Support"] = 1;

		if($array["WBMP Support"]==="enabled")
			$array["WBMP Support"] = 1;

		if($array["XBM Support"]==="enabled")
			$array["XBM Support"] = 1;

		return $array;
   }

	function gd_version()
	{
		global $code;

		if (empty($result))
		{
			if (!function_exists('gd_info'))
			{
				$gd_info = $this->gd_info_alternate();
			}
			else
			{
				$gd_info = gd_info();
			}

			if (substr($gd_info['GD Version'], 0, strlen('bundled (')) == 'bundled (')
			{
				$result = (float) substr($gd_info['GD Version'], strlen('bundled ('), 3);
			}
			else
			{
				$result = (float) substr($gd_info['GD Version'], 0, 3);
			}
		}
		return $result;
	}

	function image_create_function($x_size, $y_size)
	{
		$image_create_function = 'ImageCreate';

		if ($this->gd_version() >= 2.0)
		{
			$image_create_function = 'ImageCreateTrueColor';
		}

		if (!function_exists($image_create_function))
		{
			return false;
		}
		return $image_create_function($x_size, $y_size);
	}

	function image_copy_function($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
	{
		$image_copy_function = 'ImageCopyResized';

		if ($this->gd_version() >= 2.0)
		{
			$image_copy_function = 'ImageCopyResampled';
		}

		if (!function_exists($image_copy_function))
		{
			return false;
		}

		return $image_copy_function($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
	}

	function set_cache_filename($filename, $width, $square, $border)
	{
		(string) $filename_output = null;

		$filename_output = eregi_replace("\/","",$filename);
		$filename_output = eregi_replace("\.","",$filename_output);
		$filename_output = eregi_replace("\:","",$filename_output);

		return $this->cache_dir . 'cache_' . $width . '_' . $square . '_' . $border . '_' . $filename_output . '.img';
	}

	function generate_thumb($source_filename, $thumb_x, $square=false, $border=false, $cache_output = null, $watermark = false, $watermark_text = null)
	{
		$image_info = getimagesize($source_filename);
		$image_width = $image_info[0];
		$image_height = $image_info[1];

		$resize_w = ($image_width>$image_height) ? $image_width : $image_height;

		if ($resize_w<$thumb_x) $thumb_x = $resize_w;

		if ($square)
		{
			$thumb_image_x = $thumb_x;
			$thumb_image_y = $thumb_x;
		}

		// workaround for v1.6.2 where the GIF images arent recognized.
		$img_create = 'ImageCreateFromJPEG';
		switch ($image_info['mime'])
		{
			case 'image/gif':
				$img_create = 'ImageCreateFromGIF';
				break;
			case 'image/jpeg':
				$img_create = 'ImageCreateFromJPEG';
				break;
			case 'image/png':
				$img_create = 'ImageCreateFromPNG';
				break;
		}

		if (!$square)
		{
			$shrink_ratio = $image_width/$thumb_x;
			$thumb_y = $image_height/$shrink_ratio;
			$start_x = 0;
			$start_y = 0;
		}
		else if ($square)
		{
			if ($image_width>$image_height)
			{
				$shrink_ratio = $image_width/$thumb_x;
				$thumb_y = $image_height/$shrink_ratio;
				$start_x = 0;
				$start_y = (abs($thumb_image_y - $thumb_y)) / 2;
			}
			else if ($image_width<=$image_height)
			{
				$shrink_ratio = $image_height/$thumb_x;
				$thumb_y =$thumb_x;
				$thumb_x = $image_width/$shrink_ratio;
				$start_y = 0;
				$start_x = (abs($thumb_image_x - $thumb_x)) / 2;
			}
		}

		$thumb_input = @$img_create($source_filename);

		if (!$thumb_input) /* See if it failed */
		{
			$thumb_input  = imagecreate($thumb_x, $thumb_y); /* Create a blank image */

			$white_color = imagecolorallocate($thumb_input, 255, 255, 255);
			$black_color  = imagecolorallocate($thumb_input, 0, 0, 0);

			imagefilledrectangle($thumb_input, 0, 0, 150, 30, $white_color);

			imagestring($thumb_input, 1, 5, 5, 'Error loading ' . $source_filename, $black_color); /* Output an errmsg */

			imagejpeg($thumb_input, '', 90);
			imagedestroy($thumb_input);
		}
		else
		{
			if ($square)
			{
				$thumb_output = $this->image_create_function($thumb_image_x,$thumb_image_y);
				$border_x = $thumb_image_x - 1;
				$border_y = $thumb_image_y - 1;
			}
			else
			{
				$thumb_output = $this->image_create_function($thumb_x,$thumb_y);
				$border_x = $thumb_x - 1;
				$border_y = $thumb_y - 1;
			}

			$background_color = imagecolorallocate($thumb_output, 255, 255, 255);
			imagefill($thumb_output,0,0,$background_color);

			$this->image_copy_function($thumb_output, $thumb_input, $start_x, $start_y, 0, 0, $thumb_x, $thumb_y, $image_width, $image_height);

			if ($border)
			{
				$border_color = imagecolorallocate($thumb_output, 0, 0, 0);
				imagerectangle($thumb_output,0,0,$border_x,$border_y,$border_color);
			}

			if ($watermark)
			{
				// Get identifier for white
				$white = imagecolorallocate($thumb_output, 255, 255, 255);

				// Add text to image
				imagestring($thumb_output, 20, 5, $thumb_y-20, $watermark_text, $white);
			}

			touch($cache_output);
			imagejpeg($thumb_output,$cache_output,90);

			imagedestroy($thumb_output);
		}
	}

	function allowed_extension($input_file)
	{
		$allowed_extension = false;
		$file_array = explode('.', $input_file);
		$pattern = "/(?i)\.php/";

		$nb_array = count($file_array);
		$ext_cnt = count($file_array) - 1;

		$extension = ($nb_array<=1) ? '' : $file_array[$ext_cnt];
		$extension = strtolower($extension);

		$extension_array = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'avi', 'mpg', 'mpeg', 'mov', 'img');
		$allowed_extension = (in_array($extension, $extension_array)) ? true : false;

		##echo (preg_match($pattern, $input_file)) ? FALSE : $allowedExtension;

		return $allowed_extension;
	}
}

?>
