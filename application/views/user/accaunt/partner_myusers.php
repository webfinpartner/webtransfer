<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<? if ( !empty($request_list) ) { ?><br/><Br/>
<h3><?=_e('Заявки')?></h3>
<table width="100%" class="payment_table">
    <thead>
        <tr>
            <th><?=_e('Фото')?></th>
            <th><?=_e('Ф.И.О.')?></th>
            <th><?=_e('Эл. почта')?></th>
            <th><?=_e('Телефон')?></th>
            <th><?=_e('Соц. сети')?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($request_list as $request) {
            $item = $request->user;
            ?>
            <form method="POST" id="request_form_<?=$request->id?>">
            <input type="hidden" name="request_id" value="<?=$request->id?>">
            <input type="hidden" name="status" value="" id="status_<?=$request->id?>">

            <tr>
                <th><img src="<?= (empty($item->foto)) ? "/images/no-photo.jpg" : $item->foto ?>" width="35px"/></th>
                <td class="user_name">
                    <? if( !$item->status ){ ?>
                        <img src="/images/icons/151.png" alt="<?=_e('неверифицирован')?>">
                    <? } else {?>
                        <img src="/images/icons/152.png" alt="<?=_e('верифицирован')?>">
                    <? } ?> <span rel="<?= $item->id_user ?>" class="load_user"><?= $item->name ?> <?= $item->sername ?></span> <br/>
                    <span style="font-size:10px;display:block;margin-top:-7px;color:#8a8a8a;">№ <?= $item->id_user ?> | <?= date_formate_my($item->reg_date, 'd/m/Y') ?></span>

                </td>
                <td class="user_email" style="font-size:12px;"><?= $item->email ?></td>
                <td class="user_phone" style="font-size:12px;"><?= $item->phone ?></td>
                <td class="user_soc" style="font-size:12px;">
		<? foreach ($item->social as $social) {
                    if (empty($social->url)) continue;
                    echo '<a  href="' . $social->url . '" onclick="return !window.open(this.href)" style="margin:2px"><img style="width:16px;" src="/images/icons/' . socialList($social->name) . '"></a>';
                } ?>
                <? if (!empty($item->skype)) { ?>
                        <a class="user_social" href="skype:<?= $item->skype ?>?chat">
                            <img width="16" src="/images/icons/sp.png">
                        </a>
                <? } ?>
                <? $social_id = get_social_id($item->id_user);
                    if ( !empty($social_id) ){ ?>
                        <a href="https://webtransfer.com/social/profile/<?=$social_id?>?lang=<?=_e('lang')?>" target="_blank">
                            <img width="16" src="/images/icons/wt_social.png">
                        </a>
                    <? } ?>

                 </td>

                <td><a href="#" onclick="if (!window.confirm('<?=_e('Вы уверены что добавить пользователя?')?>')) return false; $('#status_<?=$request->id?>').val(1); $('#request_form_<?=$request->id?>').submit(); return false;" style="font-size:12px;"><?=_e('Подтвердить')?></a>
                    / <a href="#" onclick="if (!window.confirm('<?=_e('Вы уверены что отклонить заявку?')?>')) return false; $('#status_<?=$request->id?>').val(2); $('#request_form_<?=$request->id?>').submit(); return false;"  style="font-size:12px;"><?=_e('Отклонить')?></a></td>
            </tr>
            </form>

        <? }?>
    </tbody></table>
<? } ?>
<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
    <?=_e('accaunt/partner_myusers_1')?>
</div><br/>
<a id="volunteer_doc" class="but agree right" href="#" onclick="return false" style="margin-left:20px"><?=_e('new_205')?></a>
<a id="volunteer" class="but agree right" href="#" onclick="return false" style="margin-left:20px"><?=((!$volunteer) ? _e('accaunt/partner_myusers_2') : _e('accaunt/partner_myusers_3'))?></a>

<a class="but agree right" href="<?=site_url('account/guarant')?>" style="margin-left:20px"><?=_e('Выпустить Гарантию')?></a>
<? $this->load->view('user/blocks/volunteer_dialog.php', array("volunteer" => $volunteer, "backPage" => "my_users"));?>
<div class="left">
    <?=_e('Показать на странице:')?>
    <select name="pages">
        <? renderSelect(['20' => 20, '50' => 50, '100' => 100], $statuses->step)?>
    </select>
</div>
<br/><br/>
<br/>
<div style="font-size:11px;">
    <input type="checkbox" name="blocked" <?if($statuses->blocked){?>checked="checked"<?}?>> <img src="/images/icons/151.png" alt="<?=_e('заблокированные пользователи')?>" /> - <?=_e('заблокированные пользователи')?> &nbsp; | &nbsp;
    <input type="checkbox" name="not_verifyed"  <?if($statuses->not_verifyed){?>checked="checked"<?}?>> <img src="/images/icons/160.png" alt="<?=_e('accaunt/partner_myusers_4')?>" /> - <?=_e('accaunt/partner_myusers_5')?> &nbsp; | &nbsp;
    <input type="checkbox" name="verifyed"  <?if($statuses->verifyed){?>checked="checked"<?}?>> <img src="/images/icons/152.png" alt="<?=_e('accaunt/partner_myusers_6')?>" /> - <?=_e('accaunt/partner_myusers_7')?>
</div>
<script>
    $(function(){
        var timer_id;
        var url = window.location.href.split('/');
        var m = {pages: 6, blocked: 7, not_verifyed: 8, verifyed: 9}
        $('[name="pages"], [name="blocked"], [name="not_verifyed"], [name="verifyed"]').change(function(){
            if(timer_id) clearTimeout(timer_id);
            timer_id = setTimeout(function(){
                url[m['pages']] = $('[name="pages"]').val();
                url[m['blocked']] = setUrlType($('[name="blocked"]').is(':checked'));
                url[m['not_verifyed']] = setUrlType($('[name="not_verifyed"]').is(':checked'));
                url[m['verifyed']] = setUrlType($('[name="verifyed"]').is(':checked'));
                window.location.href = url.join('/');
            }, 400);
        });
    });
    function setUrlType(v){
        return (v) ? 't' : 'f';
    }
</script>
<style>
    .payment_table th,.user_reg, .user_soc {
        text-align:center;
    }
    .user_photo img {
        padding:5px;
    }
    .sub_user {
        display: none;
        color: #888;
		background-color:#eee;
    }
    .sub_open {
        cursor: pointer;
    }
    .sub_active {
        display: table-row;
    }
    .sub_right{
        text-align: right;
    }
    .del_user{
        cursor: pointer;
    }
</style>
<script>
    $(document).ready(function(){
        $(".sub_open").click(function(e){
                var id = $(e.target).attr("data-id");
                $(".sub_user_"+id).toggleClass("sub_active");
        });
        $(".del_user").click(function(e){
                var id = $(this).data("id");
                var name = $(this).data("name");
                $(".user_del_agree").data("id",id);
                $("#user_2_del").html(id);
                $("#user_name_2_del").html(name);
                $("#user_del").show();

        });
        $(".user_del_agree").click(function(e){
                var id = $(e.target).data("id");
                $("#user_del").hide();
                $.post(
                    site_url + "/account/ajax_user/del_user_parent",
                        {id:id},
                        function(a){
                            if("ok" == a) {$(".row_id_"+id).hide();alert("<?=_e('accaunt/partner_myusers_8')?>");}
                            else alert("<?=_e('accaunt/partner_myusers_9')?>");
                        }
                );

        });
    });
</script>
<table width="100%" class="payment_table">
    <thead>
        <tr>
            <th><?=_e('accaunt/partner_myusers_10')?></th>
            <th><?=_e('accaunt/partner_myusers_11')?></th>
            <th><?=_e('accaunt/partner_myusers_12')?></th>
            <th><?=_e('accaunt/partner_myusers_13')?></th>
            <th><?=_e('accaunt/partner_myusers_14')?></th>
            <th><?=_e('accaunt/partner_myusers_15')?></th>
<!--            <th>X</th>-->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($list as $item) {
            renderTablePartnerUser($item, ((isset($item->subusers) and $volunteer) ? count($item->subusers) : 0));
            if ($volunteer)
                foreach ($item->subusers as $subitem)
                    renderTablePartnerUser($subitem, 0, true);

        }?>
    </tbody></table>
   <?= $pages ?>
</div>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>



<div class="popup_window small" id="user_popup">
    <div class="close"></div>
    <div class="content" ></div>
</div>

<div class="popup_window small" id="user_del">
    <div class="close"></div>
    <div class="content" >
        <p style="font-size: 13px; padding: 20px; margin: 20px; text-align: center;"><?=_e('accaunt/partner_myusers_16')?><span id="user_2_del"></span> (<span id="user_name_2_del"></span>)?</p>
        <center><span class='user_del_agree but cancel'><?=_e('accaunt/partner_myusers_17')?></span></center>
    </div>
</div>


<script>
    // Обработчик на закрытие попап окошек
    $('.popup_window .close').click(function() {
        $(this).parent().hide('slow')
    })


</script>