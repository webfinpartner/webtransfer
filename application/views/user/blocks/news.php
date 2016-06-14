<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<br/><br/>
<center>
<iframe id='a02bd51f' name='a02bd51f' src='https://webtransfer.com/reklama/www/delivery/afr.php?zoneid=19&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://webtransfer.com/reklama/www/delivery/ck.php?n=a5acaac2&amp;cb={random}' target='_blank'><img src='https://webtransfer.com/reklama/www/delivery/avw.php?zoneid=19&amp;cb={random}&amp;n=a5acaac2&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</center>
<br/>
<?php
if(!empty($items))
{
$i=$t=0;
foreach($items as $item){?>

<div class="news_full" style="display: inline-block;width:100%;margin-bottom:15px;margin-top:15px;padding-bottom:20px;border-bottom:1px dotted #ccc;">



                   <?if( !empty($item->foto)){?><div class="image" style="margin-top:5px;"><a href="<?=site_url('news')?>/<?=$item->url?>" style="float:left;margin-right:20px;"><img src="/upload/news/<?=$item->foto?>" style="width:150px;" alt="" /></a></div><?}?>
                    <div class="new_content" style="margin-left:190px;">
					<b><a href="<?=site_url('news')?>/<?=$item->url?>" class="title"><?=$item->title?></a></b>
						<a href="<?=site_url('news')?>/<?=$item->url?>#disqus_thread" style="float:right;"></a>
<div class="date"><?=$item->data?></div>

<div>

<a href="<?=site_url('news')?>/<?=$item->url?>#coment" style="position:relative; top:-5px;"><?=_e('blocks/news_1')?></a>

<script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
<span class="pluso" data-background="transparent" data-options="small,square,line,horizontal,nocounter,theme=08" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,print" data-url="<?=site_url('news')?>/<?=$item->url?>" data-title="<?=$item->title?>">
</span>

<a href="<?=site_url('news')?>/<?=$item->url?>#coment" class="comment_tt">&nbsp <?=_e('blocks/news_2')?> &nbsp</a>

</div>

<p><?=cutString($item->text,200); ?> <a href="<?=site_url('news')?>/<?=$item->url?>" style="text-decoration:underline;font-style:italic;"><?=_e('blocks/news_3')?></a></p>
                    </div>
                </div>
<?
if(false){
	$data=explode('-',$item->data);
?>

              <?php if($t==0){?>  <ul class="news_list"><?php  $t=2;}?>
                    <li>
                        <div class="date">
                            <span><?=$data[2]?></span><br /><?=month($data[1]);?>
                        </div>
                        <div class="new_content">
                            <a href="#" class="title"><?=$item->title?></a>
                            <p><?=cutString($item->text,1300); ?></p>
                        </div>
                    </li>
<?}
$i=1;

}?>

                <!--/ul-->
                <script>$('.new_content .title').css('text-decoration',"none")</script>
<?=$pages;
}?>

<?php function month($m){
	switch($m){
		case "01":return _e('blocks/news_4'); break;
		case "02":return _e('blocks/news_5'); break;
		case "03":return _e('blocks/news_6'); break;
		case "04":return _e('blocks/news_7'); break;
		case "05":return _e('blocks/news_8'); break;
		case "06":return _e('blocks/news_9'); break;
		case "07":return _e('blocks/news_10'); break;
		case "08":return _e('blocks/news_11'); break;
		case "09":return _e('blocks/news_12'); break;
		case "10":return _e('blocks/news_13'); break;
		case "11":return _e('blocks/news_14'); break;
		case "12":return _e('blocks/news_15'); break;


	}
}?>