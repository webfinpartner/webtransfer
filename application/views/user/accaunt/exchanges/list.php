    <? foreach ($exchanges_list as $item) { ?>
	
	<div class="news_full" style="display: inline-block;width:100%;margin-bottom:15px;margin-top:15px;padding-bottom:20px;border-bottom:1px dotted #ccc;">
<div class="image" style="margin-top:5px;"><a href="<?= $item->source_url ?>" style="float:left;margin-right:20px;">

<img src="http://<?= base_url_shot() ?>/upload/exchanges/<?= $item->foto ?>" style="width:150px;" alt=""></a></div> 
<div class="new_content" style="margin-left:190px;">
<b><a href="<?= site_url('account/exchanges-list/' . $item->url) ?>" class="title" style="text-decoration: none;"><?= $item->title ?></a></b>
<a href="<?= site_url('account/exchanges-list/' . $item->url) ?>#disqus_thread" style="float:right;"></a>
<div class="date"><a href="<?= $item->source_url ?>" target="_blank"><?= $item->source_url ?></a></div>

<p><?= $item->info ?> </p>
</div>
</div>

    <? } ?><br/><Br/>
<center>
<a class="but bluebut" href="<?=site_url('account/exchanges-registration')?>" style="padding:7px 50px;"><?=_e('Стать сертифицированным обменником');?></a>
</center><br/><br/>
