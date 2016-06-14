<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="sm" >
    <?php if($captcha_enabled === TRUE): ?>
        <script>
            mn.recaptcha.recaptcha_sitekey = '<?=  config_item('publickeyCapcha')?>';
        </script>
    <?php endif; ?>
    
    <div class="modal fade in" id="confirmDialog" role="dialog" aria-hidden="false" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?php if (!isset($content_header)) { ?>
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
                    <span class="pull-right" style="margin-top: -5px;"><img src="/images/security_module/universal_window/<?= $logo_type_image ?>" alt=""></span>
                    <h4 class="modal-title"><?= _e('Двухэтапная авторизация') ?><br/><?= $title_type?></h4>
                    <?php } else echo $content_header ?>
                </div>
                <div class="modal-body">
                    <?php if (!isset($content_body)) { ?>
                    <form class="form-style-modal">
                        <? if(!empty($top_text)): ?>
                            <div class="form-group form-group-lg"><?= $top_text ?></div>
                        <? endif; ?>
                        
                        <? if( $lang_select ): ?>
                        <div class="form-group form-group-lg">
                            <label><?= _e('Выберите язык')?>:</label>
                            
                            <div class="btn-group bootstrap-select" style="width: 100%;">
                                <input type='hidden' name='lang' value="ru"/>
                                <button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="Russian" aria-expanded="false"><span class="filter-option pull-left"><?= _e('Русский') ?></span>&nbsp;<span class="caret"></span></button>
                                <div class="dropdown-menu">
                                   <ul class="dropdown-menu inner" role="menu">
                                      <li data-original-index="ru" class="selected">
                                          <a tabindex="0" class="" data-normalized-text="" data-tokens="null"><span class="text"><?= _e('Русский') ?></span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>
                                      <li data-original-index="en">
                                          <a tabindex="0" class="" data-normalized-text="" data-tokens="null"><span class="text"><?= _e('English') ?></span><span class="glyphicon glyphicon-ok check-mark"></span></a></li>                                      
                                   </ul>
                                </div>
                             </div>
                        </div>
                        <? endif; ?>
                        
                        <div class="form-group">
                            <label id="universal_window_code_text"><?= $code_text ?> <? if($it_table_num === false) echo ':' ?> </label>
                            <input class="form-control form_input" onkeypress="return mn.security_module.confirm_code_by_enter(event);" type="text">
                        </div>
                        
                        <? if( $hide_button === FALSE ): ?>
                        <div class="form-group">
                            <button class="btn btn-orange pull-left send_button" onclick="mn.security_module.get_code();return false;"><?= $send_button_name ?></button>
                        </div>
                        <? endif; ?>
                        
                        <? /*
                        <div class="form-group form-enter-code" style='display: none;'>
                            <p class="note"><a href="#">Отправить код</a> еще раз, через 60 сек.</p>
                        </div>
                        */ ?>
                    </form>
                    <?php } else echo $content_body ?>
                    
                    <?php if($captcha_enabled === TRUE): ?>
                        <div id="security-recaptcha" class="g-recaptcha" data-sitekey="<?=  config_item('publickeyCapcha')?>"></div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <div class="res-message"></div>
                    <center>
                        <img class="loader" style="display: none" src="/images/loading.gif"/>
                    <center>
                </div>
                <div class="modal-footer">
                    <?php if (!isset($content_footer)) { ?>
                    <button type="button" class="btn btn-primary pull-left" data-dismiss="modal" onclick="mn.security_module.confirm_code();return false;"><?= _e('ПОДТВЕРДИТЬ') ?></button>
                    <button type="button" class="btn pull-right" data-dismiss="modal" onclick="mn.security_module.close();return false;"><?= _e('ОТМЕНИТЬ') ?></button>
                    <?php } else echo $content_footer ?>
                </div>
                <? if( $choose_type ):  ?>
                <div class="panel-footer choose" style="<?= $choose_type_style ?>">
                    <p><?= _e('Выслать код другим способом') ?>:</p>
                    <ul class="list-unstyled list-inline">
                        <? foreach( $type_variants as $tv ):  ?>
                            <li><a href="#" onclick=" mn.security_module.change_security_type('<?= $purpose ?>','<?= $tv['type'] ?>');return false;"><img src="images/security_module/universal_window/<?= $tv['image'] ?>" alt=""></a></li>                        
                        <? endforeach;  ?>
                    </ul>
                </div>
                <? endif;?>
            </div>
        </div>
    </div>

    <div class="modal-backdrop fade in" style="display: none;"></div>
</div>