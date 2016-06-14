<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<script type="text/javascript" src="/js/user/friends.js"></script>
<div id="fill_tab">
    <nav id="fill_navigation">
        <ul class="tab_list">
            <li class="tab_list_item webtransfer <?= ($social === false ? 'active' : '') ?>" data-page="webtransfer">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item google_plus <?= ($social == 'google' ? 'active' : '') ?>" data-page="google_plus">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item odnoklassniki <?= ($social == 'ok' ? 'active' : '') ?>" data-page="odnoklassniki">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item twitter <?= ($social == 'twitter' ? 'active' : '') ?>" data-page="twitter">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item facebook <?= ($social == 'facebook' ? 'active' : '') ?>" data-page="facebook">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item vk <?= ($social == 'vkontakte' ? 'active' : '') ?>" data-page="vk">
                <div class="logo"></div>
            </li>
            <li class="tab_list_item mail_ru <?= ($social == 'mailru' ? 'active' : '') ?>" data-page="mail_ru">
                <div class="logo"></div>
            </li>
        </ul>
    </nav>
    <div class="tabs">
        <div style="display: none;" class="tab webtransfer_tab <?= ($social === false ? 'active' : '') ?>">
            <div class="tab_no-auth">
                <div class="logo">
                    <div class="logo_img"></div>
                </div>
                <div class="text"><?=_e('accaunt/friends_1')?></div>
                <a href="#" class="button"><?=_e('accaunt/friends_2')?></a>
            </div>
        </div>
        <div style="display: none;" class="tab google_plus_tab <?= ($social == 'google' ? 'active' : '') ?>">
            <? if( isset( $localFriends->Google ) && !empty($localFriends->Google) ) $this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->Google, 'url' => 'google' ) ); 
               else
                 $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->Google, 'url' => 'google', 'style' => '-30' ) ); ?>
        </div>
        <div style="display: none;" class="tab odnoklassniki_tab <?= ($social == 'ok' ? 'active' : '') ?>">
            <? if( isset( $localFriends->odnoklassniki ) && !empty($localFriends->odnoklassniki) ) $this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->odnoklassniki, 'url' => 'ok' ) );
            else
                $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->odnoklassniki, 'url' => 'ok', 'style' => '-58' ) ); ?>
        </div>
        <div style="display: none;" class="tab twitter_tab <?= ($social == 'twitter' ? 'active' : '') ?>">
            <? if( isset( $localFriends->Twitter ) && !empty($localFriends->Twitter) )$this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->Twitter, 'url' => 'twitter' ) );
            else
                $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->Twitter, 'url' => 'twitter', 'style' => '-87' ) ); ?>
        </div>
        <div style="display: none;" class="tab facebook_tab <?= ($social == 'facebook' ? 'active' : '') ?>">
            <? if( isset( $localFriends->Facebook ) && !empty($localFriends->Facebook) ) $this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->Facebook, 'url' => 'facebook' ) );
                else
                $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->Facebook, 'url' => 'facebook', 'style' => '-121' ) ); ?>
        </div>
        <div style="display: none;" class="tab vk_tab <?= ( $social == 'vkontakte' ? 'active' : '') ?>">
            <? // if( isset( $localFriends->Vkontakte ) && !empty($localFriends->Vkontakte) ) 
                $this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->Vkontakte, 'url' => 'vkontakte' ) );
//               else 
                 $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->Vkontakte, 'url' => 'vkontakte', 'style' => '-150' ) ); ?>
        </div>
        <div style="display: none;" class="tab mail_ru_tab <?= ($social == 'mailru' ? 'active' : '') ?>">
            <? if( isset( $localFriends->Mailru ) && !empty($localFriends->Mailru) ) $this->load->view( 'user/blocks/user_social_friends.php', array( "localFriends" => $localFriends->Mailru, 'url' => 'mailru' ) );
               else
                $this->load->view( 'user/blocks/user_social_button.php', array( "friends" => $friends->Mailru, 'url' => 'mailru', 'style' => '-175' ) ); ?>
        </div>
    </div>
</div>

<? //$this->load->view( 'user/blocks/user_social_friends_popup.php' ); ?>

<div id="user_social_friends_popup">
    <div class="close"></div>
    <div class="hybrid_send_message">
        <div class="friend_fio"></div>
        <div class='social_button send_mess'> <?=_e('accaunt/friends_3')?></div>
    </div>
    <div class="user_message"></div>
<!--    <div class="error_auth">
        <?=_e('accaunt/friends_4')?>
        <br/>
        <?=_e('accaunt/friends_5')?>
    </div>-->
    <div class="loader">
        <center><img class="loading-gif" src="/images/loading.gif" alt="" /></center>
    </div>    
</div>