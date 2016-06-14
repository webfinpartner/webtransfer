<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	var state= '<?=($state=='create')?"0":"$state"?>';
	var controller = '<?=$controller?>';
	var image_folder = '<?=@$image_folder?>';
</script>
	            <div class="widget" >
            <div class="title"><img src="images/icons/dark/pencil.png" alt="" class="titleIcon" /><h6>Изображение</h6></div>

		 <div class="formRow">

<div style="margin-bottom:10px;  "><div class="formRight"  style="float: none"><input type="file"  class="" id="foto"  name="foto"  value="">
		<span id='download'  style="vertical-align:  bottom"><img height="27px" src="/images/download.png" /></span>
		<span id="preview"></span>
	</div>
	<div style="margin:10px 0px -20px  30px; display:  inline-block">
                        <div class="clear" style="margin-top:10px"></div>
	<img id="news_foto" width="100px" src="/upload/imager.php?src=<?=$image_folder?>/<?=@$item->{$tb_foto}?>&w=100&sq=Y"/>
                        <div id="foto_delete" style="cursor:pointer; margin-left:25px;<?php if(empty($item->{$tb_foto}))echo"display:none;"?>">Удалить</div>

			<div id="info_delete" style="margin-left: -15px ;display: none">Изображение удалено.</div>
												</div><div style="margin-bottom:10px" id="foto_error"></div>

												</div> </div>