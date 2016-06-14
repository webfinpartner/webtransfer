<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>



<?  if(!empty($item)){?>
                <div class="widget">
                    <div class="title"><img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" /><h6>Сообщение</h6>

                    <a style="margin: 4px 4px; float: right; " class="button greenB" title=""><?=($item->admin_state==3)?"<span name='$item->id' class='feed_open'>Открыть":"<span name='$item->id' class='feed_close'>Закрыть"?></span></a>
                                         <a style="margin: 4px 4px; float: right; " class="button redB del" title="" href="<?php base_url()?>opera/feedback/delete/<?=$item->id?>"><span>Удалить</span></a></div>
                    <div class="body">

                        <h3><?=($item->state==2)? "Перезвонить":"Вопрос"?></h3>
                        <p>



Имя: <?=$item->name?><br/>
Маил: <?=$item->email?><br/>
Телефон: <?=$item->telephone?><br/>
№ Кошелька: <?=$item->sys_id?><br/>
Дата: <?=$item->date?><br/>
<? if($item->state==1){ ?>
Сообщение:<br />
<?=$item->text?><br/>
<? if(!empty($answer))
{
echo "Ответы";
	foreach($answer as $an)
	{
		echo "<br />$an->date <br /> $an->text <br />";
	}
}?>
<br />
Ответить:<br />
<form action="opera/feedback/<?=$item->id?>" method="post">
              <textarea  style="width:100%; height: 300px;" name="message" rows="" cols="">





Best regards,
<b><?=$GLOBALS["WHITELABEL_NAME"] ?> Team</b>


<a href="mailto:support@webtransfer.com">support@webtransfer.com</a>
<a href="http://<?=base_url_shot()?>">http://www.<?=base_url_shot()?></a>

<img src="https://<?=base_url_shot()?>/img/logo13.gif">
</textarea>
<br />
<input type="submit" value="Ответить">
</form>

      </p>
                <?}
                echo "                    </div>
                </div>
                ";
                }?>
