<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php
if(!empty($items))
{
	foreach($items as $item){?>

			<div class="news_full" style="display: inline-block;margin-top:20px;">
                   <?if( !empty($item->foto)){?> <div class="image"><div class="play"></div><a ><img src="/upload/system_news/<?=$item->foto?>" style="width:200px;" alt="" /></a></div><?}?>
                    <div class="new_content">
                        <div class="date"><?=$item->data?></div>
                        <a  class="title"><?=$item->title?></a>
                        <p><?=$item->text; ?></p>
                    </div>
                </div>
<?}?>

                <script>$('.new_content .title').css('text-decoration',"none")</script>
<?=$pages;
}?>


