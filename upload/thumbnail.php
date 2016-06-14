<?php
#################################################################
## MyPHPAuction 2009															##
##-------------------------------------------------------------##
## Copyright ©2009 MyPHPAuction. All rights reserved.	##
##-------------------------------------------------------------##
#################################################################

include_once('class_image.php');

$image = new image();

(string) $pic = null;


$pic = $_GET['pic'];





$thumbnail_width = abs(intval($_GET['w']));
$is_square = ($_GET['sq']=='Y')? true : false;
$is_border = ($_GET['b']=='Y') ? true : false;

(array) $info = null;

$info = @getimagesize($pic);
list($im_width, $im_height, $im_type, $im_attr) = $info;

if (empty($info)||$im_type>3) $pic = '../images/pic.png';

$pic_no_spaces = eregi_replace('%20','',$pic);

$pic_cached = eregi_replace($image->image_basedir,'',$pic_no_spaces);

if ($_GET['check']=='phpinfo')
{
	phpinfo();
}
else
{
	$allowed_extension = $image->allowed_extension($pic);

	if (isset($pic) && $thumbnail_width>0 && $allowed_extension)
	{
		/* check to see if file already exists in cache, and output it with no processing if it does */
		$cached_filename = $image->set_cache_filename($pic_cached, $thumbnail_width, $is_square, $is_border);

		if (is_file($cached_filename)) /* display cached filename */
		{
			header('Content-type: image/jpeg');
			header('Location: ' . $cached_filename . '?' . rand(2,9999));
		}
		else /* create new thumbnail, and add it into the cache directory as well */
		{
			header('Content-type: image/jpeg');

			$cache_output = $image->set_cache_filename($pic_cached, $thumbnail_width, $is_square, $is_border);
			$image->generate_thumb($pic, $thumbnail_width, $is_square, $is_border, $cache_output);

			header('Location: ' . $cache_output . '?' . rand(2,9999));
		}
	}
	else if (!isset($pic))
	{
		echo "<strong>ERROR:</strong> No image submitted";
	}
	else if ($thumbnail_width<=0)
	{
		echo "<strong>ERROR:</strong> Invalid resizing option";
	}
	else if (!$allowed_extension)
	{
		echo "<strong>ERROR:</strong> Prohibited file extension";
	}
}
?>
