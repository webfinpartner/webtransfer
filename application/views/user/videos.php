<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="<?=site_url('css/admin/ui_custom.css')?>" rel="stylesheet" type="text/css" />
<style>
    .video{
        margin: 0 auto;
        margin: 10px;
        cursor: pointer;
		display:block;
        float: left;
        width: 220px;
        height: 260px;
    }
	.video_contaner {
        width: 740px;
	}
    .video-img {
        width: 100%;
    }
</style>



<div id="container" class="content" style="overflow:hidden;">
    <div style="float:right;">
        <select id="cat" style="padding:5px;"><option value="" style="padding:2px;"><?=_e('accaunt/videos_1')?></option><?$r=explode("/", uri_string());getVideoCatSelect((isset($r[2]) ? $r[2] : ''));?></select>
    <script>
        $("#cat").change(function(){
            window.location = site_url + '/video/'+$("#cat").val();
        });
    </script>
	</div>

    <h1 class="title"><?=((null !== $cat)?video_catigories($cat):_e('accaunt/videos_2'))?></h1>
	<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
	
    
	
	<? if(($_SERVER['REQUEST_URI']) == '/ru/video/loan-hour'){ ?>
	Уважаемые участники и партнёры Webtransfer!
<br/><br/>
У вас есть отличная возможность получить тысячу ($1000) долларов США на Webtransfer VISA Card, которые сразу можно обналичить в банкомате. Для этого вам всего лишь необходимо записать видео, которое должно быть на одну из тем:
<br/><br/>
- вывод займа “гарант” на <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- вывод средств на  <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- снятие наличных в банкомате с  <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- вывод средств cо счета <A href="https://webtransfer.com/ru/account/transactions">Webtransfer DEBIT Card</a> через <a href="https://webtransfer.com/ru/account/currency_exchange/sell_search?show_start=20">P2P</a>
<br/><br/>
Записанное видео нужно прислать на почту one@webtransfer.com, а также разместить на своих страницах в соц сетях.<br/><br/>
Обязательна ссылка не только на YouTube, но и на пост в соцсети, в котором  размещено видео. Пост на стене в соцсети должен сохраняться до конца акции.
<br/><br/>
В письме сообщите:<br/>
1. Ссылка на видео на Youtube<br/>
2. Номер ID в Webtransfer и Ф.И.О.<br/>
3. Ссылка/и на пост в соцсети/ях
<br/>
Все участники, принявшие участие в акции получают  бонус в размере пятьдесят ($50) долларов США.<br/><Br/>

Победитель ролика получит приз тысячу ($1000) долларов  на Webtransfer VISA Card (Black);<br/>
Следующие 3 (три) победителя по пятьсот ($500) долларов  на Webtransfer VISA Card (Black);<br/>
Следующие 10 (десять) участников по сто ($100) долларов  Webtransfer VISA Card (Virtual).<br/>
<br/><br/>
Подробная информация здесь:<br/>
<a href="https://webtransfer.com/ru/news/campainvisa" target="_blank">https://webtransfer.com/ru/news/campainvisa</a><br/>
<br/>

Всем желаем победить!☺️<br/>
I <span style="color:red;">&#10084;</span> WEBTRANSFER

	<? } elseif(($_SERVER['REQUEST_URI']) == '/en/video/loan-hour'){ ?>
Dear members and partners of Webtransfer!
<br/><br/>
You have excellent opportunity to receive One thousand ($1,000) US Dollars to your Webtransfer Visa Card, which you can withdraw immediately in any ATMs around the world. For that, you need just to record video to one of the following topics:
<br/><br/>
- withdrawal of the loan to your <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- withdrawal of funds to your <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- withdrawals of funds in bank ATM  from your <a href="http://webtransfercard.com" target="_blank">Webtransfer VISA Card</a>;
- withdrawal of funds from your <A href="https://webtransfer.com/en/account/transactions">Webtransfer DEBIT Card</a> via <a href="https://webtransfer.com/en/account/currency_exchange/sell_search?show_start=20">P2P</a>
<br/><br/>
Please forward your recorded video to one@webtransfer.com, as well as publish on your social network pages.<br/><br/>
You are required to indicate not only your YouTube page link, but also the link to your social network wall, where the video is published. The wall post should not be removed until the end of the event. <br/><br/>
In your email plase indicate:<br/>
1. Link to your Youtube video<br/>
2. Your ID in Webtransfer and your full name<br/>
3. Link to your social network post
<br/>
All members participating in the competition will get $50 (fifty) credit bonuses<br/><Br/>

The winner gets $1000 (One thousand US Dollars) to his Webtransfer VISA Card (Black);<br/>
Next 3 (three) winners will get five hundred ($500) to their Webtransfer VISA Card (Black);<br/>
And other 10 members will get one hundred ($100) US Dollars each to their Webtransfer VISA Card (Virtual).<br/>
<br/><br/>
More information is available here:<br/>
<a href="https://webtransfer.com/en/news/campainvisa" target="_blank">https://webtransfer.com/en/news/campainvisa</a><br/>
<br/>
Good luck in winning☺️<br/>
I <span style="color:red;">&#10084;</span> WEBTRANSFER

<? } elseif(($_SERVER['REQUEST_URI']) == '/ru/video/qiwi-terminal'){ ?>

<div class="news_full" style="display: inline-block;width:100%">

                <div class="new_content">
                    <div class="title">Пополнение Webtransfer VISA через терминалы QIWI - ПРИЗ $500 и отчисление Webtransfer USD<span style="color:red;">❤</span> в Гарант фонд</div>
                    <div class="date">2016-03-16 09:21:42</div>

                        <div class="image"><div class="play"></div></div> <p></p><p dir="ltr"><span>Уважаемые участники и партнеры!<br> </span></p>
<p dir="ltr"><span>Мы сделали пополнение карты </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong><span> еще доступнее! </span></p>
<p dir="ltr"><span>Если у вас есть наличные деньги и нет электронных, то у вас появилась отличная возможность пополнить карту <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong></span><span>&nbsp;через </span><strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong><span>. Для этого вам достаточно создать заявку на пополнение через кнопку “</span><strong><a href="https://webtransfer.com/ru/account/payment" target="_blank">ПОПОЛНИТЬ</a></strong><span>”, затем выбрать из доступных методов <strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong></span><span>&nbsp;(самый верхний), оформить платежный счёт (инвойс) и оплатить его наличными через терминал. Ближайшие к вам &nbsp;пункты пополнения наличными вы можете посмотреть здесь: </span><strong><a href="https://qiwi.com/replenish/map.action" target="_blank">карта QIWI терминалов</a></strong></p>
<p dir="ltr"><span>Для всех, кто пополнит карту через <strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong></span><span>&nbsp;и зафиксирует данный процесс на видео, появится возможность погашать отчисления в </span><strong><a href="https://webtransfer.com/ru/page/about/guarantee" target="_blank">Гарантийный фонд</a></strong><span> со счета </span><span>&nbsp;<strong>Webtransfer USD<span style="color: red;">❤</span></strong></span></p>
<p dir="ltr">Записанное видео нужно прислать на почту <a href="mailto:one@webtransfer.com" target="_blank">one@webtransfer.com</a> с темой письма: "<strong>отчисление сердечками в ГФ</strong>".</p>
<p dir="ltr"><span>В письме укажите:</span></p>
<p dir="ltr"><span>1. Ссылка на видео на </span><strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong></p>
<p dir="ltr"><span>2. Сумма пополнения</span></p>
<p dir="ltr"><span>3. Номер ID в Webtransfer и Ф.И.О.</span></p>
<p dir="ltr"><strong><span style="color: #ff0000;">Мы объявляем новый конкурс на лучшую инструкцию, которая станет ценным инструментом в работе для миллионов участников!</span></strong></p>
<p dir="ltr"><span>У вас есть хороший шанс получить </span><strong>$500 (пятьсот долларов США)</strong><span> на </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong><span>, которые сразу можно обналичить в банкомате или потратить на покупки. Для этого вам всего лишь необходимо записать видео инструкцию, в которой детально будет отображён процесс пополнения карты </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong><span> через </span><strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong><span>, начиная с момента нажатия кнопки “</span><strong><a href="https://webtransfer.com/ru/account/payment" target="_blank">ПОПОЛНИТЬ</a></strong><span>” в кабинете и заканчивая вашими действиями возле терминала. Также вы можете присылать не только видео, но и иллюстрированную пошаговую инструкцию с фото и скриншотами экрана. Делайте так, чтобы просмотрев эти инструкции, любой человек смог пополнить карту без дополнительных &nbsp;вопросов. Всё должно быть в простой и доступной форме.</span></p>
<p dir="ltr"><span>Авторы лучших инструкций получат вознаграждение:</span></p>
<p dir="ltr"><span>Победитель получит приз в размере </span><strong>пятисот <strong>($500)</strong>&nbsp;долларов США</strong><span>&nbsp;на <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong></span><span>;</span></p>
<p dir="ltr"><span>Следующие 5 (пять) авторов получат по </span><strong>сто ($100) долларов</strong><span> <strong>США&nbsp;</strong>на <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong></span><span>;</span></p>
<p dir="ltr"><span>Все участники, принявшие участие в акции и приславшие инструкции, получат по <strong>пятьдесят </strong></span><strong>($50) долларов</strong><span> <strong>США&nbsp;</strong>кредитным бонусом с выводом прибыли на карту.</span></p>
<p dir="ltr"><span>Записанную инструкцию нужно прислать на почту </span><span><a href="mailto:one@webtransfer.com">one@webtransfer.com</a>&nbsp;</span><span>с темой письма: </span><span>"<strong>инструкция QIWI</strong>"</span><span>, а также разместить на своих страницах в соц сетях. Обязательна ссылка не только на <strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong>, но и на пост в соцсети, в котором &nbsp;размещена инструкция. Пост в соцсети должен сохраняться до конца акции.</span></p>
<p dir="ltr"><span>В письме укажите:</span></p>
<p dir="ltr"><span>1. Ссылка на видео на </span><strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong><span> или иллюстрированную инструкцию</span></p>
<p dir="ltr"><span>2. Ссылка/и на пост в ваших соцсетях</span></p>
<p dir="ltr"><span>3. Номер ID в Webtransfer и Ф.И.О.</span></p>
<p dir="ltr"><span>Вы можете присылать ваши ролики <strong>до </strong></span><strong>30.04.2016г.</strong></p>

<p dir="ltr"><span>Желаем участникам акции победы и больших профитов!☺</span></p>
<p dir="ltr"><span>I <span style="color: red;">❤</span> WEBTRANSFER<br><br></span></p>

                </div>
            </div>
<? } elseif(($_SERVER['REQUEST_URI']) == '/en/video/qiwi-terminal'){ ?>

<div class="news_full" style="display: inline-block;width:100%">

                <div class="new_content">
                    <div class="title">Пополнение Webtransfer VISA через терминалы QIWI - ПРИЗ $500 и отчисление Webtransfer USD<span style="color:red;">❤</span> в Гарант фонд</div>
                    <div class="date">2016-03-16 09:21:42</div>

                        <div class="image"><div class="play"></div></div> <p></p><p dir="ltr"><span>Уважаемые участники и партнеры!<br> </span></p>
<p dir="ltr"><span>Мы сделали пополнение карты </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong><span> еще доступнее! </span></p>
<p dir="ltr"><span>Если у вас есть наличные деньги и нет электронных, то у вас появилась отличная возможность пополнить карту <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong></span><span>&nbsp;через </span><strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong><span>. Для этого вам достаточно создать заявку на пополнение через кнопку “</span><strong><a href="https://webtransfer.com/ru/account/payment" target="_blank">ПОПОЛНИТЬ</a></strong><span>”, затем выбрать из доступных методов <strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong></span><span>&nbsp;(самый верхний), оформить платежный счёт (инвойс) и оплатить его наличными через терминал. Ближайшие к вам &nbsp;пункты пополнения наличными вы можете посмотреть здесь: </span><strong><a href="https://qiwi.com/replenish/map.action" target="_blank">карта QIWI терминалов</a></strong></p>
<p dir="ltr"><span>Для всех, кто пополнит карту через <strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong></span><span>&nbsp;и зафиксирует данный процесс на видео, появится возможность погашать отчисления в </span><strong><a href="https://webtransfer.com/ru/page/about/guarantee" target="_blank">Гарантийный фонд</a></strong><span> со счета </span><span>&nbsp;<strong>Webtransfer USD<span style="color: red;">❤</span></strong></span></p>
<p dir="ltr">Записанное видео нужно прислать на почту <a href="mailto:one@webtransfer.com" target="_blank">one@webtransfer.com</a> с темой письма: "<strong>отчисление сердечками в ГФ</strong>".</p>
<p dir="ltr"><span>В письме укажите:</span></p>
<p dir="ltr"><span>1. Ссылка на видео на </span><strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong></p>
<p dir="ltr"><span>2. Сумма пополнения</span></p>
<p dir="ltr"><span>3. Номер ID в Webtransfer и Ф.И.О.</span></p>
<p dir="ltr"><strong><span style="color: #ff0000;">Мы объявляем новый конкурс на лучшую инструкцию, которая станет ценным инструментом в работе для миллионов участников!</span></strong></p>
<p dir="ltr"><span>У вас есть хороший шанс получить </span><strong>$500 (пятьсот долларов США)</strong><span> на </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong><span>, которые сразу можно обналичить в банкомате или потратить на покупки. Для этого вам всего лишь необходимо записать видео инструкцию, в которой детально будет отображён процесс пополнения карты </span><strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA</a></strong><span> через </span><strong><a href="https://qiwi.com/replenish/categories/qiwi-terminals.action" target="_blank">QIWI терминал</a></strong><span>, начиная с момента нажатия кнопки “</span><strong><a href="https://webtransfer.com/ru/account/payment" target="_blank">ПОПОЛНИТЬ</a></strong><span>” в кабинете и заканчивая вашими действиями возле терминала. Также вы можете присылать не только видео, но и иллюстрированную пошаговую инструкцию с фото и скриншотами экрана. Делайте так, чтобы просмотрев эти инструкции, любой человек смог пополнить карту без дополнительных &nbsp;вопросов. Всё должно быть в простой и доступной форме.</span></p>
<p dir="ltr"><span>Авторы лучших инструкций получат вознаграждение:</span></p>
<p dir="ltr"><span>Победитель получит приз в размере </span><strong>пятисот <strong>($500)</strong>&nbsp;долларов США</strong><span>&nbsp;на <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong></span><span>;</span></p>
<p dir="ltr"><span>Следующие 5 (пять) авторов получат по </span><strong>сто ($100) долларов</strong><span> <strong>США&nbsp;</strong>на <strong><a href="https://webtransfercard.com/ru/" target="_blank">Webtransfer VISA Card</a></strong></span><span>;</span></p>
<p dir="ltr"><span>Все участники, принявшие участие в акции и приславшие инструкции, получат по <strong>пятьдесят </strong></span><strong>($50) долларов</strong><span> <strong>США&nbsp;</strong>кредитным бонусом с выводом прибыли на карту.</span></p>
<p dir="ltr"><span>Записанную инструкцию нужно прислать на почту </span><span><a href="mailto:one@webtransfer.com">one@webtransfer.com</a>&nbsp;</span><span>с темой письма: </span><span>"<strong>инструкция QIWI</strong>"</span><span>, а также разместить на своих страницах в соц сетях. Обязательна ссылка не только на <strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong>, но и на пост в соцсети, в котором &nbsp;размещена инструкция. Пост в соцсети должен сохраняться до конца акции.</span></p>
<p dir="ltr"><span>В письме укажите:</span></p>
<p dir="ltr"><span>1. Ссылка на видео на </span><strong><a href="https://www.youtube.com/" target="_blank">Youtube</a></strong><span> или иллюстрированную инструкцию</span></p>
<p dir="ltr"><span>2. Ссылка/и на пост в ваших соцсетях</span></p>
<p dir="ltr"><span>3. Номер ID в Webtransfer и Ф.И.О.</span></p>
<p dir="ltr"><span>Вы можете присылать ваши ролики <strong>до </strong></span><strong>30.04.2016г.</strong></p>

<p dir="ltr"><span>Желаем участникам акции победы и больших профитов!☺</span></p>
<p dir="ltr"><span>I <span style="color: red;">❤</span> WEBTRANSFER<br><br></span></p>

                </div>
            </div>


	<? } else { ?>
		<?=_e('videos_txt')?> 
	<? } ?>
	</div>

    <div class="video_contaner">
        <?
        $size = "hqdefault";
        foreach ($videos as $video) {
            $size = "default";
            $this->load->view('user/blocks/renderVideo_part.php', compact("video","size","me"));
        } ?>
    </div>

<div id="dialog_video" title="">
    <center>
        <iframe id="video" src=""
                accesskey="" frameborder="0" width="560" height="315"
                data-mce-src="">
        </iframe>
    </center>
</div>

<script>
    $("#dialog_video").dialog({
      resizable: false,
      autoOpen: false,
      height: 460,
      width: 590,
      modal: true,

    });

    var v = $("#video");
    function showVideo(e){
        if($(e).data("video_id")) {
            console.log($(e).data("video_id"));
            v.attr("src","https://www.youtube.com/embed/"+$(e).data("video_id")+"?rel=0");
            v.attr("data-mce-src","https://www.youtube.com/embed/"+$(e).data("video_id"));
        }
        v.data("id", $(e).data("id"))
        $("#dialog_video").dialog("open");
    }
    <?if($me){?>
    function sendVot(id){
        $('.popup_window').hide('slow');
        $.get(site_url + "/video/vote/"+id,function(id){console.log($("#v_rate_"+id).html(),parseInt($("#v_rate_"+id).html()));
            $("#v_rate_"+id).html(parseInt($("#v_rate_"+id).html())+1);
        });
    }

    function randomString(length)
    {
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
            if (! length)
        {
               length = Math.floor(Math.random() * chars.length);
        }
        var str = '';
        for (var i = 0; i < length; i++)
        {
            str += chars[Math.floor(Math.random() * chars.length)];
        }
        return str;
    }

    function social_voting(soc, id, title, text, img, urll){
        sendVot(id);

        if (soc=='fk')
        {
               var url = "http://www.facebook.com/sharer.php?s=100&p[title]="+title+"&p[summary]="+text+"&p[url]="+urll+"&p[images][0]="+img+"&nocache-"+randomString(7);
               window.open(url,'','toolbar=0,status=0,width=650,height=450');
        }
        else if (soc=='vk')
        {
               var url = "http://vkontakte.ru/share.php?title="+title+"&description="+text+"&url="+urll+"&image="+img+"&nocache-"+randomString(7);
               window.open(url,'','toolbar=0,status=0,width=650,height=450');
        }
        else if (soc=='od')
        {
               var url = "http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl="+urll;
               window.open(url,'','toolbar=0,status=0,width=650,height=450');
        }
        else if (soc=='mm')
        {
               var url = "http://connect.mail.ru/share?url="+urll+"&title="+title+"&description="+text+"&imageurl="+img;
               window.open(url,'','toolbar=0,status=0,width=650,height=450');
        }
        else if (soc=='tw')
        {
               var url = "http://twitter.com/share?text="+title+"&url="+urll+"&counturl="+urll+"&nocache-"+randomString(7);
               window.open(url,'','toolbar=0,status=0,width=650,height=450');
        }
    }
    <?}?>
</script>

<? if(15 < $count){?>
<center style="clear: both;"><a id="next" class="button narrow" href="#" onclick="return false" data-count="15"><?=_e('videos_more')?></a></center>
    <script>
        $("#next").click(function(){
            var count = $("#next").data("count");
            var all = <?=$count?>;
            if(Math.ceil(all/15) <= Math.ceil((count+15)/15))
                $("#next").hide();
            $.get(site_url + "/video/next_video/"+count+"/<?=$cat?>",function(d){
                    $(".video_contaner").append(d);
                }
            );
            $("#next").data("count", count + 15);

            return false;
        });
    </script>
<?}?>
<br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
</div>