<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .mail {margin: 0 auto; text-align: center;  height:  300px;}
    #parent-info{
        width: 300px;
        overflow: auto;
        height: auto;
        margin: 0 auto;
    }
</style>
<div class="mail">
    <h2><?=_e('Ваш старший партнер')?></h2>
    <form action="" method="POST" id='set-parent-form'>
            <div id="parent-info" >
                <? if (!empty($parentUser) and (int) $parentUser->id_user) { ?>
                <div style="border: 1px solid rgb(204, 204, 204); margin-bottom: 10px; padding: 18px 10px 18px 10px;">
                    <div style="display: inline-block; vertical-align: top; width: 60px; float: left;" class="user-profile_img_wrapper">
                        <img width="50" style="border-radius:30px" src="<?= $parentPhotos['photo_50'] ?>" alt="User image" pagespeed_url_hash="4195711572">
                    </div>
                    <div style="display: block; font-weight: 500; margin-bottom: 5px; text-align: left; color: rgb(54, 154, 152);">
                        <?= $parentUser->name . ' ' . $parentUser->sername ?> <br />
                    </div>
                    <span style="margin-top:5px">
                        <?php if( isset($parentUser->email) ){ ?>
                            <a href="mailto:<?= $parentUser->email ?>" /><img width="16" src="/images/icons/em.png" /></a>
                        <?php } ?>
                        <?
                        $soc_icon = array(
                            "vkontakte" => "/images/icons/vk.png",
                            "mail_ru" => "/images/icons/mr.png",
                            "facebook" => "/images/icons/fb.png",
                            "twitter" => "/images/icons/tw.png",
                            "odnoklassniki" => "/images/icons/ok.png",
                            "google_plus" => "/images/icons/gp.png",
                            "link_edin" => "/images/icons/in.png"
                        );
                        if ($parentSocialList) {
                            foreach ($parentSocialList as $social) {
                                echo "<a href='" . $social->url . "' target='_blank'>"
                                . "<img width='16' src='" . $soc_icon[$social->name] . "' />"
                                . "</a>" . PHP_EOL;
                            }
                        }
                        ?>

                        <? if (!empty($parentUser->skype)) { ?>
                            <a href="skype:<?= $parentUser->skype ?>?call">
                                <img width="16" src="/images/icons/sp.png">
                            </a>
                        <? } ?>
                    </span>

                </div>
            <? } else { ?>
                <div style="margin-bottom: 10px;">
                    <p><?=_e('add_parent_info')?></p>
                    <p><?=_e('add_parent_info2')?></p>
                </div>
            <? } ?>
        </div>


        <div>
            <div style="width:100px; display:inline-block;"><?=_e('add_parent_id')?></div>
            <input type="text" style="font-size: 11px;
    padding: 7px 6px;
    background: none repeat scroll 0% 0% #FFF;
    border: 1px solid #DDD;
    width: 110px;
    font-family: Arial,Helvetica,sans-serif;
    box-shadow: 0px 0px 0px 2px #F4F4F4;
    color: #333;" name="parent_id" value="<?= $parentId ?>" />
            <button id='refresh-form' class="but bluebut" href="#" style="height: 29px;"><?=_e('add_parent_refresh')?></button>
        </div>

        <div style="margin-top:10px">
            <!--<input type="submit" name="sub" value="<?=_e('add_parent_save')?>" id='save-form'/>-->
            <button type="submit" name="sub" id='save-form' class="button"><?=_e('add_parent_save')?></button>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#refresh-form').click(function(){
            $('#set-parent-form').attr('action',site_url + '/login/parent/refresh');
        });
        $('#save-form').click(function(){
            $('#set-parent-form').attr('action',site_url + '/login/parent/save');
        });
    });
</script>