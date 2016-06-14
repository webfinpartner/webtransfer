<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
    <? $this->load->view('user/blocks/header_user');?>
<div class="inner">
	<!--ul class="breadcrumbs">
		<li><a href=""><?=_e('accaunt/main_1')?> /</a></li><li class="active">
		<a href="">/ <?=_e('accaunt/main_2')?></a></li>
	</ul-->

</div>
<link rel="stylesheet" href="/css/user/accaunt.css" />
<div id="container" class="content">
    <h1 class="title"><?= $title_accaunt ?></h1>
    <?= $content; ?>
    <? $this->load->view('user/accaunt/security_module/loader');?>
</div>


<?
$this->load->view('user/blocks/footer');

function url_active($url) {
	if (uri_string() == $url)
		echo "class='active'";
}
?>

