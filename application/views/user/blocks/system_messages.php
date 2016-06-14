<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if( isset( $system_messages[10] ) && !empty( $system_messages[10] ) )    
{     
    ?>
        <link rel="stylesheet" type="text/css" href="/css/user/system_messages.css" />
        <?if( count( $system_messages[10] ) > 1 ){?>
            <div class="page__messages messages messages_collapsed">
                <div class="messages__inner">
                    <span class="messages__counter"><?= count( $system_messages ) ?></span>
                </div>
        <?}else{?>
            <div class="page__messages messages">                
        <?}?>
        <div class="messages__list">
            <? foreach( $system_messages[10] as $message )
            { ?>
                    <div class="alert alert_type_advice">
                        <div class="alert__inner">
                            <span class="alert__icon">
                                <i class="alert__icon-inner"></i>
                            </span>
                            <span class="alert__close ng-hide" onclick="closeSystemMessage(this, '<?= $message->message_id ?>');">
                                <i class="alert__close-inner">×</i>
                            </span>                             
                            <span class="alert__text ng-binding">
                                <p><?= $message->text ?></p>
                            </span>
                        </div>
                    </div>
            <? } ?>
        </div>
        
           
    </div>
    <script type="text/javascript">
        $('.messages').bind('click', function() {
            if( $(this).find('.alert').length < 2 ) return;
            
            if ($(this).hasClass('messages_collapsed'))
            {
                $(this).removeClass('messages_collapsed');
            } else {
                $(this).addClass('messages_collapsed');
            }
        });
        var language_name = '';
        function closeSystemMessage(el, message_id)
        {
            $.post(site_url  + '/account/ajax_user/close_system_message', {message_id: message_id})
                    .done(function(json_data) {
                if (!json_data)
                    return false;

                try {
                    var data = JSON.parse(json_data);
                } catch (exp) {
                    return false;
                }
                
                if (data['success'] && data['success'] == 'OK')
                {
                    
                    if( $(el).parentsUntil('.messages__list').length == 1 )
                    {
                        $('.page__messages').slideUp(300);
                        
                    }else{
                        $(el).parent().parent().slideUp(300);
                    }
                }
            });
        }
    </script>
<? } ?>


<?  if( isset( $system_messages[20] ) && !empty( $system_messages[20] ) )  { ?>


<style>
    .popup_window_overlay {
        background-color: rgba(0, 0, 0, 0.6);
        position: fixed;
        left:  0;
        top:  0;
        width: 100%;
        height: 100%;
        z-index: 16000002;        
    }    
    
 </style>

<div class="popup_window_overlay" id="system_messages_2x">
    <?php $i=99; foreach( $system_messages[20] as $idx=>$message) {?> 
    <div id="popup_system_message" class="popup_window popup_system_message" style="margin: <?=($idx*5)?>px 0 0 <?=($idx*5)?>px;z-index:160000<?=--$i?>;">
        <!--div class="close" onclick="$(this).parent().hide();"></div-->
        <h2><?=$message->title?></h2>
        <br/>
        <div><?=$message->text?></div>
        <br/>    
        <?php if ( $message->type_id == 21 ){ ?>
        <input id="iknow<?= $message->message_id ?>" type="checkbox"><label style="margin-left: 5px; font-weight: bold" for="iknow<?= $message->message_id ?>"><?=_e('Я ознакомлен')?></label>
        <div id="sysmsg_error<?= $message->message_id ?>" style="color: #D8000C; font-size: 12px;  background-color: #FFBABA;"></div>
        <?php } ?>
        <button name="submit" id="sysmsg_bn_save" type="submit" class="button" onclick="closeSystemMessage2x(this,<?= $message->message_id ?>,<?= $message->type_id ?>)"><?= _e( 'Закрыть' ) ?></button>       
    </div>
    <?php } ?>
</div>

<script>
        $(function() { 
            $('.popup_system_message').show();
        });     
        function closeSystemMessage2x(el, message_id, type_id){
            if (type_id == 21)
            {
                
                if ( !$(el).prevAll('input').is(':checked') ){
                    $('#sysmsg_error'+message_id).html('Вы не ознакомились');
                    return false;
                }
                
            }
        
            $.post(site_url  + '/account/ajax_user/close_system_message', {message_id: message_id})
                    .done(function(json_data) {
                if (!json_data)
                    return false;

                try {
                    var data = JSON.parse(json_data);
                } catch (exp) {
                    return false;
                }
                
                if (data['success'] && data['success'] == 'OK')
                {
                    if( $('#system_messages_2x .popup_system_message:visible').length == 1 )
                    {
                        $(el).parent().parent().hide();
                        
                    }else{
                        $(el).parent().hide();
                    }
                }
            });
        }    
 </script>

<? } ?>