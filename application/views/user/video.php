<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .video{
        width: 120px;
        height: 110px;
        overflow: hidden;
        float: left;
        margin: 10px;
        cursor: pointer;
    }
    .video_info{
        margin-top: 50px;
    }
        #___plusone_0 {
            width:60px !important;
        }
        .twitter-tweet-button {
            width:80px !important;
        }
    </style>


<div id="container" class="content">
    <div style="text-align:right;margin-top:10px;width:100%;overflow:hidden;">
        <span style="position: relative;top: -7px;right: 10px;"><?=_e('accaunt/video_1')?></span>
        <script type="text/javascript">(function() {
                if (window.pluso)
                    if (typeof window.pluso.start == "function")
                        return;
                if (window.ifpluso == undefined) {
                    window.ifpluso = 1;
                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })();</script>
        <div class="pluso" data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,print"></div>
    </div>
	<ul class="breadcrumbs"style="text-indent:-3px">
            <li><a href="<?=site_url('/')?>"><?=_e('video_home')?> / </a></li>
            <li><a href="<?=site_url('video')?>"> <?=_e('video_video')?>/ </a></li>
            <li><a href="<?=site_url('video')?>/<?=$cat?>"> <?=video_catigories(video_catigories_link_num($cat))?>/ </a></li>
            <li class="active" ></li>
        </ul>

    <h1 class="title"><?=$video->title;?></h1>
    <?
    if($video->id_video) {
        ?>
        <center>
            <iframe id="video" src="https://www.youtube.com/embed/<?=$video->id_video;?>?rel=0"
                    accesskey="" frameborder="0" width="560" height="315"
                    data-mce-src="https://www.youtube.com/embed/<?=$video->id_video;?>">
            </iframe>
        </center>        
        <?
    } else {
        ?>
        <img src="/upload/<?=$video->foto?>"    style="margin: 20px auto; display: block; max-width: 600px">
        <?
    }
    ?>
    
    <div style="height:30px; margin-top: 20px;">
        <center>
            <span style="font-size:12px;"><?=date("d/m/Y",strtotime($video->date))?> - <a href="<?=site_url('video')?>/<?=video_catigories_link($video->category)?>"><?=video_catigories($video->category)?></a></span>
            <?=_e('accaunt/video_2')?><span id='v_rate_<?=$video->id?>'><?=$video->vote?></span><br/><br/>
           
<? if (strpos($_SERVER['REQUEST_URI'],'loan-hour')==FALSE) {?> 
		   <button class="but agree" style="margin-left:0px;" onclick="$('.popup_window').hide('slow');$('#popup_vote_<?=$video->id?>').show('slow');"><?=_e('accaunt/video_3')?></button>
	<? } ?>	   
		   
		   
        </center>
    </div>
    <div class="video_info">
        <?=$video->info;?>
    </div>

    <?
    $vote = array('id' => $video->id, 'title' => $video->title, 'text' => $video->info, 'img' => "https://img.youtube.com/vi/$video->id_video/default.jpg", 'url' => "https://www.youtube.com/watch?v=$video->id_video");
    ?>

    <div id="popup_vote_<?=$video->id?>" class="popup_window" <?=(($me)?'':'style="width: 235px;"')?>>
        <div onclick="$('#popup_vote_<?=$video->id?>').hide('slow');" class="close"></div>
        <?if($me){
            if($verified){?>
        <h3><?=_e('accaunt/video_4')?></h3>
        <div style="text-align:center;margin:10px 0px">
            <img src="/images/icons/vk.png" alt="<?=_e('accaunt/video_5')?>" title="<?=_e('accaunt/video_5')?>" onclick="social_voting('vk',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;" />
            <img src="/images/icons/mr.png" alt="<?=_e('accaunt/video_6')?>" title="<?=_e('accaunt/video_6')?>" onclick="social_voting('mm',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;" />
            <img src="/images/icons/fb.png" alt="<?=_e('accaunt/video_7')?>" title="<?=_e('accaunt/video_7')?>" onclick="social_voting('fk',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;" />
            <img src="/images/icons/tw.png" alt="<?=_e('accaunt/video_8')?>" title="<?=_e('accaunt/video_8')?>" onclick="social_voting('tw',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;" />
            <img src="/images/icons/wtb.png" alt="<?=_e('accaunt/video_9')?>" title="<?=_e('accaunt/video_9')?>" onclick="sendVot(<?=$video->id?>);" style="cursor:pointer;" />
        </div>
            <?} else{?>
                <h2><?=_e('accaunt/video_11')?></h2>
                <center><?=_e('accaunt/video_12')?></center>
            <?}
        } else {?>
        <h2><?=_e('accaunt/video_10')?></h2>
        <center>
            <nav id="social-buttons" class="social-buttons" style="float:none;padding-top:10px;">

                <ul class="social-buttons_list" style="padding-bottom:10px;">
                    <li class="social-buttons_item twitter">
                        <a href="#" class="social-twitter">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item odnoklassniki">
                        <a target="_blank" class="social-odnoklassniki" href="#">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item googleplus">
                        <a href="#" class="social-google_plus">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item vk">
                        <a href="#" class="social-vkontakte">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item fb">
                        <a href="#" class="social-facebook" style="cursor:pointer;">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                    <li class="social-buttons_item wt wt-login" id="wt-login" style="cursor: pointer;">
                        <a href="#" class="social-webtransfer bn_wt_login_form" onclick="return false;" style="cursor:pointer;">
                            <span class="social-buttons_item_img"></span>
                        </a>
                    </li>
                </ul>
            </nav>
        </center>
        <?}?>
    </div>

<script>
    <?if($me){?>
    function sendVot(id){
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
        if(<?=((!$verified)? "true" : "false")?>) return;
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

<!--    <div id="disqus_thread">&nbsp;</div>
    <script id="dsq" type="text/javascript">// <![CDATA[
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'webtransfer'; // required: replace example with your forum shortname
        var disqus_url = 'https://webtransfer-finance.com/video/<?=$video->id?>';
        var disqus_title = '<?=$video->title;?>';
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script');
            dsq.id = 'coments';
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
        // ]]></script>
    <noscript><?=_e('video_noscript')?></noscript>

-->
<br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
</div>
