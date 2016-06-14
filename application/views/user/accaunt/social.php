<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<style>
    .header_login{
        display: none;
    }
</style>
<? $this->load->view('user/blocks/banner_profile_login', array('vkontakteConfig' => config_item('vkontakteConfig'), 'facebookConfig' => config_item('facebookConfig'))); ?>
<div class="widget">
    <form>
    <div class="title"><img alt="" src="/images/icons/dark/pencil.png" class="titleIcon">
        <h6><?=_e('accaunt/social_1')?></h6>
    </div>
    <fieldset>
        <? foreach ($socialList as $item) { ?>
                <? if (isset($socials[$item])) { ?><tr>
                        <div class="formRow">
                            <label><a href="<?= $socials[$item]->url ?>" target="_blank"><img src="/images/icons/big_<?= socialList($item) ?>"></a></label>
                            <div class="formRight">
                                <a href="<?= $socials[$item]->url ?>" target="_blank" style="float:left;margin: 0px 50px"><img width='50px' style="padding:1px;border:1px solid #ccc" src="<?= "/upload/imager.php?src=".$socials[$item]->foto."&w=50" ?>" /></a>
                                <div class="close" onclick="delSocNet('<?=$item?>');"></div>

                            </div>
                    </div>
                <?
                }
        }

        function socailCheck($socials, $name) {
                return (isset($socials[$name])) ? "" :
                                "<a  href='#' onclick='return false' class='social-$name'>"._e('accaunt/social_3')."</a>";
        }
        ?>

        <div style=" height: 100px;">
            <center><br/>
                <h3 style="margin-bottom:10px;"><?=_e('accaunt/social_4')?></h3>
                    <? foreach ($socialList as $item) { ?>

                        <? if (isset($socials[$item])) { ?><tr>
                        <?
                        } else {
                                ?>
                        <a href='#' onclick='return false' class='social-<?= $item ?>'>	<img src="/images/icons/<?= socialList($item) ?>" /></a>
                     <? } ?>

    <? } ?>

                </center>

        </div>
    </fieldset>
</form>
</div>
<script>
    function delSocNet(n){
        mn.security_module
            .init()
            .show_window('withdrawal_standart_credit')
            .done(function(res){
                if( res['res'] != 'success' ) return false;
                mn.security_module.loader.show();
                var code = res['code'];
                $.post('<?=site_url('account/social_delete')?>/'+n, {code: code}, function(r){
                    console.log('ready. Delete ok');
                    window.location.reload();
                }, 'json');
            });
        return false;
    }

</script>
<br/><br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>




