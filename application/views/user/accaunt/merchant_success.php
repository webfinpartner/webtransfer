<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, max-age=0, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
<link href="/css/user/odnoklassniki.css" rel="stylesheet"/>
<link href="/css/style.css" rel="stylesheet"/>
<script type="text/javascript" src="/js/user/jquery.js"></script>
<title>"<?=$GLOBALS["WHITELABEL_NAME"] ?> - <?=base64_decode($shop->title)?>"</title>

<style>
@import url(http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic&subset=latin,cyrillic-ext);
html {
background-color: #F5F5F5;

}
body
{
    display: block;
    padding: 0px;
    margin: 0px;
    font-family: "Open Sans","Arial","Helvetica",sans-serif;
    background-color: #F5F5F5;
    font-size: 14px;
    line-height: 17px;
    font-weight: 300;
    color: #38383e;
}
.block {
border: 1px solid #ddd;
margin: 20px auto;
background-color: #ffffff;
width:800px;
}
.merchant-logo {
float:right;
position:relative;
display:inline-block;
}
.logo {
position: relative;
display:inline-block;
}
 .table .rower {
  height: auto;
  padding: 10px;
  min-height: 15px;
  font-size: 16px;
  overflow: auto;
}
.table .bg {
  background-color: #eee;
}
			.table .td1 {
  width: 150px !important;
  float: left;
}
.table .td2 {
  text-align: right;
  float: right;
}
 .button {
  outline: none;
  display: block;
  border: medium none;
  width: 250px;
  height: 44px;
  font: 300 24px/24px 'Open Sans';
  font-family: "Open Sans","Arial","Helvetica",sans-serif;
  color: #FFF;
  background: none repeat scroll 0% 0% #7FBFBF;
  cursor: pointer;
  border-radius: 25px;
  outline: none;
  margin: 14px auto 5px; }

			.success {
			width: auto;
			padding:10px;
			background-color: #3C88FF;
			color: #ffffff;
			font-weight:bold;
			}


</style>
</head>
<body>

<div class="block">
<div class="header" style="border-bottom:1px solid #ddd;">
<div class="logo">
    <img src="https://webtransfer-finance.com/img/logo.png" style="height:60px; margin:10px 20px;">
</div>
<div class="merchant-logo">
    <img style="height:60px; margin:10px 20px;" src="/upload/images/merchant/<?=(isset($shop->image)) ? md5($shop->shop_id) . "." . $shop->image : "no.png"; ?>" />
</div>
</div>
<div class="" style="padding:10px 20px;text-align:left;">
<div style="width:100%;text-align:right;">
<!--<b>Skypass Service Limited</b><br/>-->
<?=base64_decode($shop->url)?><br/>
<?=_e('Тел')?>: +<?=base64_decode($shop->tel)?><br/>
<?=_e('Почта')?>: <?=base64_decode($shop->email)?><br/>
</div>
<div style="width:100%;text-align:left;">
<h1><?=_e('Счет на оплату №')?> <?=$vars->order_id; ?></h1>
<?=_e('Статус')?>: <?=_e('Оплачен')?>
</div>
<br/><br/>
<div class="table">
<div class="tbody">
    <? if($id_user){?>
			<div class="rower bg"><div class="td1"><?=_e('Плательщик')?>:</div><div class="td2"><?=$user_name?> (<?=$id_user?>) </div></div>
		    <div class="rower"><div class="td1"><?=_e('Получатель')?>:</div><div class="td2"><a href="<?=base64_decode($shop->url)?>"><?=base64_decode($shop->title)?></a> (<?=$shop->user_id?>) </div></div>

            <? }else{?>
            <div class="rower bg"><div class="td1"><?=_e('Получатель')?></div><div class="td2"><a href="<?=base64_decode($shop->url)?>"><?=base64_decode($shop->title)?></a> (<?=$shop->user_id?>) </div></div>
            <?}?>
    <div class="rower bg"><div class="td1"><?=_e('Описание')?>:</div><div class="td2"><?=$vars->description?></div></div>
    <div class="rower"><div class="td1"><?=_e('Сумма на оплату')?>:</div><div class="td2">$ <?=price_format_double($vars->amount)?></div></div>
    <?php if ($shop->commission == 1){?><div class="rower bg"><div class="td1"><?=_e('Комиссия')?> (0.5%):</div><div class="td2">$ <?=price_format_double($vars->commission)?></div></div><?}?>
    <div class="rower" style="border-top:2px solid #38383e;font-size:24px;font-weight:bold;"><div class="td1"><?=_e('Всего')?>:</div><div class="td2">$ <?=price_format_double($vars->summary)?></div></div>
    </div>
</div>
<center><div class="success"><?=_e('Оплата прошла успешно! Номер транзакции')?>: <?=$trans_id?></div></center>

<form method="POST" action="<?=base64_decode($shop->url_success); ?>">
 <?=form_hidden('order_id', $vars->order_id); ?>
<center><button type="submit" class="button"><?=_e('Вернуться в магазин')?></button></center><br/><br/>
</form>


<!--center><button class="button" onclick="window.location = '<?=base64_decode($shop->url_success); ?>'"><?=_e('Вернуться в магазин')?></button></center><br/><br/-->

</div>
<div class="footer" style="background-color:#38383e;text-align:center;padding:10px;color:#ddd;font-size:12px;">
&copy; <?=$GLOBALS["WHITELABEL_NAME"] ?> 2014-2015 <?=_e('Все права зашищены')?>
</div>

</div>

</body>
</html>