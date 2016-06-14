<?php //if( !$friends ): ?>
    <div class="tab_no-auth">
        <div class="logo">
            <div class="logo_img" style="background-position: 0 <?php echo $style ?>px;"></div>
        </div>
        <div class="text"><?=_e('blocks/user_social_button_1')?></div>
        <a href="<?=site_url('account/friends')?>/<?php echo $url?>" class="button social-auth"><?=_e('blocks/user_social_button_2')?></a>
    </div>
<?php //else
    if( 0&&isset($friends->error) && $friends->error ) :?>
    </br>
    <div class="tab_no-auth">
        <div class="logo">
            <div class="logo_img" style="background-position: 0 <?php echo $style ?>px;"></div>
        </div>
        <div class="text"><?=_e('blocks/user_social_button_3')?></div>        
    </div>
    <?php //echo $friends->error_mesage ?>
<?php endif; ?>

    
<?php /* if( !$friends->Facebook ): ?>
    <div class="tab_no-auth">
        <div class="logo">
            <div class="logo_img" style="background-position: 0 -121px;"></div>
        </div>
        <div class="text">Чтобы отправлять переводы друзьям, добавьте Вашу учетную запись</div>
        <a href="http://<?= base_url_shot() ?>/account/friends/facebook" class="button social-auth">Авторизоваться</a>
    </div>
<?php elseif( isset($friends->Facebook->error) && $friends->Facebook->error ) :?>
    </br>
    <?php echo $friends->Facebook->error_mesage ?>
<?php else: ?>
Друзья:
    <?php vre($friends->Facebook); ?>
<?php endif; */ ?>