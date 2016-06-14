<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
    <?=_e('accaunt/banner_1')?>
</div>
<!--<br/>
<h3><?=_e('accaunt/banner_2_0')?></h3>
<?= _e('account/about_partner_1'); ?>
<a href="<?=site_url('page/about/terms')?>">
<?= _e('account/about_partner_1_1'); ?>
<br/>
<br/>-->
<h3><?=_e('accaunt/banner_2')?></h3>
<?=_e('accaunt/banner_3')?><a href="<?=site_url('/')?>/?id_partner=<?= $this->accaunt->get_user_id() ?>"><?=site_url('/')?>/?id_partner=<?= $this->accaunt->get_user_id() ?></a>
<table width="100%" celpadding="2" celspacing="2"><?=_e('accaunt/banner_4')?>

    <?php
    foreach ($banners as $item) {
        ?>
        <tr><td style="text-align:center;">
                <img src="https://<?= base_url_shot() ?>/upload/partner_banner/<?= $item->foto ?>" style="max-width:680px;"/></td></tr>
        <tr><td>
                <textarea  style="width:680px; height: 60px;"><a href="<?=site_url('/')?>/?id_partner=<?= $this->accaunt->get_user_id() ?>"><img src="https://<?= base_url_shot() ?>/upload/partner_banner/<?= $item->foto ?>" width="680" /></a></textarea><br /><br />
    </td></tr>
    <? }
    ?>
</table>
</div>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>