<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?  if(!empty($item)){?>
	<div class="widget">
		<div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" />
			<h6><?=($item->type==1)?"Заявка на кредит":"Предложения на кредит"?></h6>
			 <!-- <a style="margin: 4px 4px; float: right; " class="button greenB" title=""><?=($item->active==3 or $item->active==1)?"<span name='$item->id' class='credit_open'>Активировать":"<span name='$item->id' class='credit_close'>Отклонить"?></span></a> -->

			 <a style="margin: 4px 4px; float: right;" class="button redB del" href="<?php base_url()?>opera/credit/delete/<?=$item->id?>">
				<span>Удалить</span>
			</a>
		</div>
     <fieldset>
		 <div class='body'>
			Имя: <?=$user->name?><br/>
			Фамилия: <?=$user->sername?><br/>
			Отчество: <?=$user->patronymic?><br/>
			Телефон: <?=$user->phone?><br/>
			<a href="/opera/users/<?=$item->id_user?>">Профиль пользователя</a>
		 </div>
		<?$this->load->view('admin/blocks/credit')?>
     </div>
 </div>
 <?}