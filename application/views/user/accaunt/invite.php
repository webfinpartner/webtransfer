<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    function addDesc(type) {
        $(".addthis_button" + type).attr('addthis:description', $(".description_" + type));
    }


</script>

<script type="text/javascript">
    function addDesc(type) {
        $(".addthis_button" + type).attr('addthis:description', $(".description_" + type));
    }
    function post_on_wall() {
        FB.login(function(response) {
            if (response.authResponse) {
                FB.ui(
                        {
                            method: 'feed',
                            name: 'Facebook Dialogs',
                            link: 'http://developers.facebook.com/docs/reference/dialogs/',
                            picture: 'http://fbrell.com/f8.jpg',
                            caption: 'Reference Documentation',
                            description: 'Dialogs provide a simple, consistent interface for applications to interface with users.',
                            message: 'Facebook Dialogs are easy!'
                        },
                function(response) {
                    if (response && response.post_id) {
                        alert('Post was published.');
                    } else {
                        alert('Post was not published.');
                    }
                }
                );
            } else {
                alert('User cancelled login or did not fully authorize.');
            }
        }, {scope: 'user_likes,offline_access,publish_stream'});
        return false;
    }
</script>
<link rel="stylesheet" href="/css/user/invite.css" />
<link rel="stylesheet" href="/css/user/ion.tabs.css" />
<style>
    .addthis_button_facebook span,
    .addthis_button_twitter span,
    .addthis_button_odnoklassniki_ru span,
    .addthis_button_vk span,
    .addthis_button_google_plusone_share span,
    .addthis_button_mymailru span{
        display: none !important;
    }
</style>
<div class="message info">
    <?=_e('accaunt/invite_1')?>
</div>
<!-- AddThis Button BEGIN -->
<br/><Br/>

<div class="korpus">

    <input type="radio" name="odin" checked="checked" id="vkl1"/><label for="vkl1" class="inv_fb"><img src="/images/icons/fb.png"></label>
    <input type="radio" name="odin" id="vkl2"/><label for="vkl2" class="inv_tw"><img src="/images/icons/tw.png"></label>
    <input type="radio" name="odin" id="vkl3"/><label for="vkl3" class="inv_ok"><img src="/images/icons/ok.png"></label>
    <input type="radio" name="odin" id="vkl4"/><label for="vkl4" class="inv_vk"><img src="/images/icons/vk.png"></label>
    <input type="radio" name="odin" id="vkl5"/><label for="vkl5" class="inv_gg"><img src="/images/icons/gp.png"></label>
    <input type="radio" name="odin" id="vkl6"/><label for="vkl6" class="inv_mm"><img src="/images/icons/mr.png"></label>
    <input type="radio" name="odin" id="vkl7"/><label for="vkl7" class="inv_em"><img src="/images/icons/em.png"></label>
    <input type="radio" name="odin" id="vkl8"/><label for="vkl8" class="inv_em"><img src="/images/icons/lin.png"></label>


    <div><h4><?=_e('accaunt/invite_2')?></h4>
        <?=_e('accaunt/invite_3')?>
        <a onclick="return false;" class="button narrow addthis_button_facebook" addthis:url="<?=site_url("partner/?id_partner=".$this->user->id_user) ?>"
           addthis:title="<?=_e('accaunt/invite_4')?>"
           addthis:description="<?=_e('accaunt/invite_5')?>" ><?=_e('accaunt/invite_6')?>
        </a>
    </div>


    <div><h4><?=_e('accaunt/invite_7')?></h4>
        <?=_e('accaunt/invite_8')?>
        <a class="button narrow addthis_button_twitter" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
           addthis:title="<?=_e('accaunt/invite_9')?>"
           addthis:description="<?=_e('accaunt/invite_10')?>"><?=_e('accaunt/invite_11')?>
        </a>
    </div>


    <div><h4><?=_e('accaunt/invite_12')?></h4>
        <?=_e('accaunt/invite_13')?>
        <a class="button narrow addthis_button_odnoklassniki_ru" addthis:url="https://webtransfercard.com/<?if($this->lang->lang()=='ru'){?>ru/<?} else {?>en/<? } ?>"
           addthis:title="<?=_e('accaunt/invite_14')?>"
           addthis:description="<?=_e('accaunt/invite_15')?>"><?=_e('accaunt/invite_16')?></a>
    </div>


    <div>
        <h4><?=_e('accaunt/invite_17')?></h4>
        <?=_e('accaunt/invite_18')?>
            <a class="button narrow" href="http://vk.com/share.php?url=<?=site_url("partner/id-".$this->user->id_user) ?>&title=<?=_e('accaunt/invite_14')?>&image=<?if($this->lang->lang()=='ru'){?>https://webtransfer.com/images/banner_new_ru_vk3.jpg<?} else {?>https://webtransfer.com/images/banner_new_en_vk2.jpg<?}?>&noparse=true&description=<?=_e('accaunt/invite_15')?>" onclick="window.open(this.href, 'targetWindow', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=550,height=420');
                        return false;"><?=_e('accaunt/invite_19')?></a>

    </div>
    <div>
        <h4><?=_e('accaunt/invite_20')?></h4>
        <?=_e('accaunt/invite_21')?>
            <a class="button narrow addthis_button_google_plusone_share" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
               addthis:title="<?=_e('accaunt/invite_14')?>"
               addthis:description="<?=_e('accaunt/invite_15')?>" onclick="return false;"><?=_e('accaunt/invite_23')?></a>
    </div>
    <div>
        <h4><?=_e('accaunt/invite_24')?></h4>
        <?=_e('accaunt/invite_25')?>
            <a class="button narrow addthis_button_mymailru" addthis:url="https://webtransfercard.com/<?if($this->lang->lang()=='ru'){?>ru/<?} else {?>en/<? } ?>"
               addthis:title="<?=_e('accaunt/invite_26')?>" addthis:imageurl="https://webtransfer.com/images/banner_new_ru_vk3.jpg" addthis:image="https://webtransfer.com/images/banner_new_ru_vk3.jpg" addthis:description="<?=_e('accaunt/invite_27')?>"><?=_e('accaunt/invite_28')?></a>
    </div>
    <div><h4><?=_e('accaunt/invite_29')?></h4>
        <?=_e('accaunt/invite_30')?>

        <div class="email_align">
            <center>
                <a class="addthis_button_email" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
                   addthis:title="<?=_e('accaunt/invite_31')?>"
                   addthis:description="<?=_e('accaunt/invite_32')?>"><img src="/images/icons/common-mail.png" border="0"></a>

                <a class="addthis_button_yahoomail" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
                   addthis:title="<?=_e('accaunt/invite_31')?>"
                   addthis:description="<?=_e('accaunt/invite_32')?>"><img src="/images/icons/mail-yahoo.png" border="0"></a>

                <a class="addthis_button_gmail" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
                   addthis:title="<?=_e('accaunt/invite_31')?>"
                   addthis:description="<?=_e('accaunt/invite_32')?>"><img src="/images/icons/mail-gmail.png" border="0"></a>

                <a class="addthis_button_hotmail" addthis:url="<?=site_url("partner/id-".$this->user->id_user) ?>"
                   addthis:title="<?=_e('accaunt/invite_31')?>"
                   addthis:description="<?=_e('accaunt/invite_32')?>"><img src="/images/icons/windows-mail.png" border="0"></a>
            </center>
        </div>

    </div>
    <div><h4><?=_e('accaunt/invite_33')?></h4>
        <?=_e('accaunt/invite_34')?>
        <div class="url_align"> &nbsp </div>
        <div style="background-color:#eee;border:1px solid #ccc;padding:5px;">
            <?=_e('accaunt/invite_35')?>
            <a href="<?=site_url("partner/id-".$this->user->id_user) ?>"><?=site_url("partner/id-".$this->user->id_user) ?></a>
        </div>
    </div>

</div>



<script type="text/javascript">

    var addthis_config = {
        data_track_clickback: true,
        ui_language: "ru",
        ui_use_addressbook: true,
        data_track_clickback: false
    };
</script>
<script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e04c4e5742f8fc7"></script>
<!-- AddThis Button END -->
<br/><br/><br/>

<style>
    .texter p {
        margin-bottom:10px;
        text-align:justify;
    }
    #container p.q {
        font-weight:bold;
        margin-bottom:5px !important;
    }
    #container p.a {
        padding: 0;
        margin-bottom:15px !important;
    }
</style>

<div class="texter" style="width:49%;float:left">
    <h4><?=_e('accaunt/invite_36')?></h4>
    <p><?=_e('accaunt/invite_37')?></p>

    <h4><?=_e('accaunt/invite_38')?></h4>
    <p><?=_e('accaunt/invite_39')?></p>

    <p><?=_e('accaunt/invite_41')?></p>


    <h4><?=_e('accaunt/invite_42')?></h4>
    <p><?=_e('accaunt/invite_43')?></p>

    <h4><?=_e('accaunt/invite_44')?></h4>
    <p><?=_e('accaunt/invite_45')?></p>
    <p><?=_e('accaunt/invite_46')?></p>

</div>

<div class="texter" style="width:49%;float:right;">
    <img class="pT" src="/images/moneyy.jpg" alt="<?=_e('accaunt/invite_47')?>" style="margin-top:10px;" title="" width="100%">
    <h4><?=_e('accaunt/invite_48')?></h4>
    <p class="q"><?=_e('accaunt/invite_49')?></p>
    <p class="a"><?=_e('accaunt/invite_50')?></p>

    <p class="q"><?=_e('accaunt/invite_51')?></p>
    <p class="a"><?=_e('accaunt/invite_52')?></p>

    <p class="q"><?=_e('accaunt/invite_53')?></p>
    <p class="a"><?=_e('accaunt/invite_54')?></p>

    <p class="q"><?=_e('accaunt/invite_55')?></p>
    <p class="a"><?=_e('accaunt/invite_56')?></p>

<p><?=_e('accaunt/invite_57')?></p>
<p><a href='<?=site_url('page/partnership')?>'><?=site_url('page/partnership')?></a></p>
</div>
</div>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>
