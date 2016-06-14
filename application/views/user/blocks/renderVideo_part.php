<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id='v<?=$video->id?>' class="video">
    <div data-video_id="<?=$video->id_video?>" data-id="<?=$video->id?>" onclick="showVideo(this);" style="width:120px;height:90px;margin:0 auto">
        <?
            if(!empty($video->foto)) {
                ?>
                <img src="/upload/<?=$video->foto?>" alt="" class="video-img">
                <?
            } else {
                ?>
                <img src="https://img.youtube.com/vi/<?=$video->id_video?>/<?=$size?>.jpg" tabindex="2"/>
                <?
            }
        ?>
    </div>
    <div style="vertical-align:top;max-width:200px;height:105px;margin:15px;text-align:center;" onclick="window.location='<?=site_url('video')?>/<?=video_catigories_link($video->category)?>/<?=$video->id?>'">
        <h3 style="font-size:13px;"><?=$video->title?></h3>
		<span style="font-size:12px;"><?=date("d/m/Y",strtotime($video->date))?></span>
          <?=_e('blocks/secondary_renderVideo_part_1')?> - <span id='v_rate_<?=$video->id?>'><?=$video->vote?></span><br/>

    </div>
    <div style="height:30px;">
        <center>
           <!-- <button class="but agree" style="margin-left:0px;" onclick="$('.popup_window').hide('slow');$('#popup_vote_<?=$video->id?>').show('slow');">Голосовать</button> -->
		   <button class="but agree" style="margin-left:0px;" onclick="window.location='<?=site_url('video')?>/<?=video_catigories_link($video->category)?>/<?=$video->id?>'"><?=_e('blocks/secondary_renderVideo_part_2')?></button>
		   
        </center>
    </div>
</div>
<?
$vote = array('id' => $video->id, 'title' => $video->title, 'text' => $video->info, 'img' => "https://img.youtube.com/vi/$video->id_video/$size.jpg", 'url' => "https://www.youtube.com/watch?v=$video->id_video");
?>
<div id="popup_vote_<?=$video->id?>" class="popup_window" <?=(($me)?'':'style="width: 235px;"')?>>
    <div onclick="$('#popup_vote_<?=$video->id?>').hide('slow');" class="close"></div>
    <?if($me){?>
    <h3><?=_e('blocks/secondary_renderVideo_part_3')?></h3>
    <div style="text-align:center;margin:10px 0px">
        <img src="/images/icons/vk.png" alt="<?=_e('Вконтакте')?>" title="<?=_e('Вконтакте')?>" onclick="social_voting('vk',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;">
        <img src="/images/icons/mr.png" alt="<?=_e('МойМир')?>" title="<?=_e('МойМир')?>" onclick="social_voting('mm',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;">
        <img src="/images/icons/fb.png" alt="Facebook" title="Facebook" onclick="social_voting('fk',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;">
        <img src="/images/icons/tw.png" alt="Twitter" title="Twitter" onclick="social_voting('tw',<?=urlencode($vote['id']);?>,'<?=urlencode($vote['title']);?>','<?=urlencode($vote['text']);?>','<?=urlencode($vote['img']);?>','<?=urlencode($vote['url']);?>');" style="cursor:pointer;">
        <img src="/images/icons/wtb.png" alt="<?=_e('Проголосовать')?>" title="<?=_e('Проголосовать')?>" onclick="sendVot(<?=$video->id?>);" style="cursor:pointer;">
    </div>
    <?} else {?>
    <h2><?=_e('blocks/secondary_renderVideo_part_4')?></h2>
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

        </nav></center>
    <?}?>
</div>
