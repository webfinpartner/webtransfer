
<? function print_messages_to_popup($messages, $inbox = false) {
    get_instance()->load->helper('inbox_helper');
    foreach ($messages as $item) {
        $url = base_url('account/inbox').'#item'.$item->id;
        if ( $item->cause == "news" )
            $url = site_url("news/".$item->url);
        
        ?>
<div class="pad-b"><strong class="ava-i"><img src="/images/mainface.png" alt="Webtransfer"></strong><div class="pp1">
        <div class="namefq"><?= date_formate_my($item->date, 'd/m/Y H:i') ?>
        </div>
<p id="link-n" class="link-n"  style="cursor: pointer" onclick="window.open('<?=$url?>', '_blank')">
        <? 
        if( $item->cause != "mess") {
             echo '<b>'.viewMessage($item->message, $inbox).'</b><br>';
             echo strip_tags( mb_substr($item->text, 0, 150).(strlen($item->text)>150?'..':'') );
        }
        if ($item->cause == "mess" ) { 
            echo  '<b>'._e("Перевод денег").'</b><br>';
            echo strip_tags( mb_substr($item->message, 0, 150, 'UTF-8').(strlen($item->message)>150?'..':'') );
        }
        
        
        if ( !empty($item->debit_info)){ ?>
                <?=_e('accaunt/inbox_6_1')?> <?= $item->debit ?><br/>
                <?=_e('accaunt/inbox_6_2')?> <?= ($item->debit_info->garant == Base_model::CREDIT_GARANT_ON? _e('accaunt/inbox_6_3'): _e('accaunt/inbox_6_4')) ?><br/>
                <?=_e('accaunt/inbox_7')?> <?= price_format_double($item->debit_info->summa) ?><br/>
                <?=_e('accaunt/inbox_8')?> <?= $item->debit_info->time ?> <br/>
                <?=_e('accaunt/inbox_9')?> <?= $item->debit_info->percent ?>%<br/>
                <?=_e('accaunt/inbox_10')?> <?= price_format_double($item->debit_info->out_summ) ?><br/>            
        <? } 
        
        ?>
    
  </p></div></div>

             <!--div class="pad-b">
                 <div id="link-n" class="pp0 link-n">
        
        </div>
            </div-->
            
<? } 
} ?>

<div class="frew">
<? if ($accaunt_header['socialMessagesNewList']['messages_count'] < 0 ){ ?>    
<h3 class="hidd posap1"><li class="subli">
<a class="" href="#" onclick="return false;">
<div class="icon-cs hvr-buzz-out"></div>
<? if ($accaunt_header['socialMessagesNewList']['messages_count'] > 0 ){ ?>  <div class="re-icond"><?=($accaunt_header['socialMessagesNewList']['messages_count']>99?'&infin;':$accaunt_header['socialMessagesNewList']['messages_count'])?>  </div><? } ?>
</a>
</li></h3>
<div class="sly" <? if ($accaunt_header['socialMessagesNewList']['messages_count'] == 0 ){ ?> style="height:150px"<? } ?> >	

<div class="ntds-h"><?_e('Cообщения')?></div>


<? foreach( $accaunt_header['socialMessagesNewList']['messages'] as $message) {     ?>
<div class="pad-b"><strong class="ava-i"><img src="<?=$message['avatar_path']?>" alt="User image"></strong><div class="pp1">
<div class="namefq"><?=$message['full_name']?></div>
<p id="link-n" class="link-n"  style="cursor: pointer" onclick="window.open('<?=$message['message_path']?>', '_blank')"><?=substr(strip_tags($message['message_subject']),0,200)?></p></div></div>

<? } ?>
<? if ($accaunt_header['socialMessagesNewList']['messages_count'] == 0 ){ ?> <div style="text-align:center; color:grey; padding-top:60px;"><?_e('У вас нет новых сообщений')?>У вас нет новых сообщений</div><? } ?>
</div>
<? } ?>

<? if ( $accaunt_header['socialFriendsPending']['pending_friends_count'] >= 0 ) { ?>
<h3 class="hidd posap2"><li class="subli2">
<a class="" href="#" onclick="return false;">
<div class="icon-cs2 hvr-buzz-out"></div>
<div id="frCount" class="re-icond" style="display:<?=($accaunt_header['socialFriendsPending']['pending_friends_count']>0)?'block':'none' ?>" id="pending_friends_count"><?=($accaunt_header['socialFriendsPending']['pending_friends_count']>99)?'&infin;':$accaunt_header['socialFriendsPending']['pending_friends_count']?></div>
</a></h3>

<div class="sly">
    
<div class="ntds-h"><?_e('Друзья')?></div>
<? foreach( $accaunt_header['socialFriendsPending']['pending_friends'] as $friend ) { ?>
<div id="fr<?=$friend['webtransfer_id']?>" class="pad-b"><strong class="ava-i"><img src="<?=$friend['avatar_path']?>" alt="User image"></strong>
    <div id="link-n9" class="pp1 ">
        <div class="long-nq" style="cursor: pointer;margin-bottom:5px;" onclick="window.open('<?=$friend['profile_path']?>', '_blank')"><?=$friend['full_name']?></div>
<div class="ddl-sv">
 <div class="del-u" onclick="soc_action('deny', <?=$friend['webtransfer_id']?>)"><div class="close-u"></div></div> 
 <div class="add-u" onclick="soc_action('accept', <?=$friend['webtransfer_id']?>)"><div class="adv-u"></div></div>
</div>
        

</div></div>
<? } ?>

<div class="butt-c nbgd" style="cursor: pointer" onclick="window.open('https://webtransfer.com/social/friends','')">Показать все</div> 

<div id="frZero" style="text-align:center; display:<?=($accaunt_header['socialFriendsPending']['pending_friends_count']>0)?'none':'block' ?>;  color:grey; padding-top:60px;"><?=_e('У вас нет заявок в друзья.')?></div>

  </div>
<? } ?>



    
<h3 class="hidd posap3"><li class="subli3">
<a class=" " href="" onclick="return false;">
<div class="icon-cs3 hvr-buzz-out"></div>
 <? if ($accaunt_header['newMessage']): ?>
<div class="re-icond"><?= $accaunt_header['newMessage'] ?></div>
 <? endif; ?>
</a>
</li></h3>    
    
<div class="sly">
<div class="ntds-h"><?_e('Уведомления')?></div>
<? print_messages_to_popup($accaunt_header['allMessagesList']) ?>
<div class="butt-c nbgd" style="cursor: pointer" onclick="window.open('<?=site_url('account/inbox')?>','_blank')">Показать все</div></div>    
    
<style>
    .posap1 {
        margin-left: 3px !important;
    }
    .posap2 {
        left: 3px !important;
    }
    .posap3 {
        left: 70px !important;
    }
    .posap4 {
        left: 135px !important;
    }
    .posap5 {
        position: absolute;
        top: 0px;
        left: 219px;
    }
    .icon-cs55 {
        background-size: 2000% !important;
        background-position-x: 12px !important;
    }
    .text-icons {
        position: absolute;
        color: #FBFBFD;
        top: 19px;
        left: 3px;
        font-size: 22px;
        font-family: sans-serif;
    }
</style>
<? 
$link_chat = site_url('account/currency_exchange/sell_search?show_start=20');

if($accaunt_header['chat_messages']['active_order'] > 0 )
    $link_chat = site_url('account/currency_exchange/my_sell_list');
else if($accaunt_header['chat_messages']['archive_order'] > 0)
    $link_chat = site_url('/account/currency_exchange/my_sell_list_arhive');

?>

<h3 class="posap5">
    <li class="subli5" style="display: inline;" >
        <a href="<?=$link_chat?>" style="position:relative">
             <? if ($accaunt_header['chat_messages']['archive_order'] > 0 || $accaunt_header['chat_messages']['active_order'] > 0): ?>
            <div class="re-icond"><?=$accaunt_header['chat_messages']['archive_order'] + $accaunt_header['chat_messages']['active_order'] ?></div>
             <? endif; ?>
            <div class="icon-cs3 icon-cs55 hvr-buzz-out">
                <span class="text-icons" style="left:-6px;"> P2P </span>    
            </div>
        </a>
    </li>
</h3>  


<!--h3 class="hidd posap3"><li class="subli3">
<a class=" " href="#" onclick="return false;">
<div class="icon-cs3 hvr-buzz-out"></div>
 <? if ($accaunt_header['socialNotificationsNewList']['notifications_count']): ?>
<div class="re-icond"><?= $accaunt_header['socialNotificationsNewList']['notifications_count'] ?></div>
 <? endif; ?>
</a>
</li></h3>
 <div class="sly">
<div class="ntds-h"><?_e('Уведомления')?></div>
<? foreach( $accaunt_header['socialNotificationsNewList']['notifications'] as $notify ) { ?>
<div class="pad-b"><div id="link-n" class="pp0 link-n"><?=$notify['body']?></div></div>
<? } ?>
<div class="butt-c nbgd" onclick="<?=site_url('socal/profile')?>">Показать все</div></div-->

<h3 class="hidd posap4">
        <li class="subli4">
            <a class="" href="/social/profile/<?=$accaunt_header['social_id']?>/activities">
                <div class="icon-cs4 hvr-buzz-out"></div>
               <!-- <div class="re-icond">4</div>-->

            </a>
        </li>
    </h3>
<!-- <div class="sly">
    <div class="ntds-h">Уведомления</div>
    <div class="pad-b">
        <div id="link-n" class="pp0 link-n">Пункт 1</div>
    </div>
    <div class="pad-b no-border-p">
        <div class="pp0 link-n" id="link-n13">Пункт 2</div>
    </div>
    <div class="butt-c nbgd">Показать все</div>
</div>-->





<script>
    $('.hidd').click(function() {
      $('.hidd').removeClass('active');
      $('.sly').fadeOut(400);
      $(this).addClass('active').next()[$(this).next().is(':hidden') ? "slideDown" : "fadeOut"](400);
    });    
    
    function soc_action(action, friend_id){
         $.post("<?=site_url("/account/ajax_social_friend_action")?>",
            {action:action, friend_id:friend_id},
            function(data){
                if ( data == "ok"){
                    $('#fr'+friend_id).hide('slow');
                    var cnt = parseInt( $('#pending_friends_count').text());
                    cnt -= 1;
                    if ( cnt < 99 ) $('#pending_friends_count').text( (cnt) );
                    if ( cnt <= 0) { 
                        $('#frZero').show();
                        $('#frCount').hide();
                    }
                } else {
                    alert(data);
                }
            },
            "html");
        
    }
</script>

</div>


<?	/*$cur_url = uri_string();
    $cur_url = preg_replace("#/news_(?:[0-9]+)#", "", $cur_url);
    $cur_url = urldecode($cur_url);
    $cur_url = explode("/", $cur_url);
    $cur_url = @array_pop($cur_url);
	if ($cur_url == 'stabfund') {?>
	<div id="mc-poll"></div>
	
	<div id="mc-poll"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Poll', id: 43883, pollId: <? if ($this->lang->lang() == 'ru') { ?>1907<? } else { ?>  1908 <? } ?>});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>
<style type="text/css">
.mcp-votes {
    width: 40px  !important;
    overflow: hidden  !important;
    height: 20px !important;
}
.mcp-percent {
     width: 40px  !important;

}
</style>
<br>
<?php }; */ ?>