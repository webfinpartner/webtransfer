<?php  if(count($localFriends) && isset($localFriends[0]->user_id)): ?>
        <ul id="social_friends" class="social-list">
        <?php foreach ($localFriends as $friend): ?> 
            <li class="social-list_item"> 
                <div class="friend_img">
                    <img src="<?php echo $friend->foto; ?>" />
                </div>                
                <div class="friend_fio">
                    <?php echo $friend->l_name.' '.$friend->f_name.' '.$friend->s_name; ?>
                </div>
                <div class="friend_buttons">
                    <div>
                        <a href="<?php echo $friend->social_uri ?>" target="_blank"><?=_e('blocks/social_friends_1')?></a>
                    </div>
                    <div>
                        <a href="#" data-url="<?=site_url('account/ajax_user/hybridAjaxIsAuth')?>/" data-social="<?= $url ?>" data-uid="<?= $friend->friend_id ?>" class="invite_friend"><?=_e('blocks/social_friends_2')?></a>
                    </div>
                </div>                
            </li>
                       
        <?php endforeach?>
    </ul>
 <?php endif; ?>
 