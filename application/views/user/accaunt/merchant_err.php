<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
message
{
	display: block;
	margin-top: 25px;
	background-color: #637C97;
	color: #8ECBCB;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
	padding: 10px;
	text-align: center;
}

loading
{
	display: block;
	text-align: center;
	padding: 20px;
}

loading img
{
	width: 98px;
	height: 98px;
}
</style>

<?if(!empty($shop->url_fail) && 1==0){?><meta http-equiv="refresh" content="7; url=<?=base64_decode($shop->url_fail); ?>"><?}?>

<form method="POST" action="<?=base64_decode($shop->url_fail); ?>">
 
 <input type="hidden" name="order_id" value="<?=$vars->order_id?>">
<center><button type="submit" class="button"><?=_e('Вернуться в магазин')?></button></center><br/><br/>
</form>


<!--message>
	<?=$error; ?>
	<?if(!empty($shop->url_fail)){?><loading>
		<img src="/images/loading_2.png" />
	</loading>
        <?=_e('new_154')?> <?}?>
</message-->
