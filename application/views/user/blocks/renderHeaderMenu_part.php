<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="user-profile">
    <div class="user-profile_menu">
        <div class="user-profile_menu_name">
            <div class="datetime"><span class="time"><?= $user->time ?></span> <?= $user->date ?></div>
<?php if($accaunt_header['userNickname'] !== FALSE): ?>
            <div class="name"><?= $accaunt_header['userNickname'] ?>
<?php else: ?>
                <div class="name"><?= $user->name . ' ' . $user->sername ?>
<?php endif;?>
                <?if(1==0&&$user->partner_plan > 1 ){?><br><span style="font-size:11px"><?=_e(getNameOfPartnerPlan($user->partner_plan));?> <?=_e('партнер')?></span><?}?>
                <br><span style="font-size:11px">ID <?=$user->id_user?></span>
                </div>
        </div>
        <ul class="user-profile_menu_list">
            <li>
                <div id="google_translate_element"></div>
                  <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: '<?=$this->lang->lang()?>',
                                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL,
                                autoDisplay: false,
                                gaTrack: true,
                                gaId: 'UA-52668034-1'}, 'google_translate_element');
                        }

                        $(document).on('change','#google_translate_element select',function(e){
                        	if($(this).val()=='ru'){
                        		window.location = window.location.pathname.replace(/\/[a-z]{2}\//,'/ru/')

                        	} else if($(this).val()=='en'){
                        		window.location = window.location.pathname.replace(/\/[a-z]{2}\//,'/en/')
                        	}

                        })
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
                    </script>
            </li>
            <li class="user-profile_menu_item volunteerChat">
                <a href="/social/profile?lang=<?=_e('lang')?>">
                    <div class="user-profile_menu_item_img">
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('соц.сеть')?></div>
                </a>
            </li> 
			<!--<li class="user-profile_menu_item volunteerChat">
                <a href="<?=site_url('account/under_construction')?>">
                    <div class="user-profile_menu_item_img">
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('соц.сеть')?></div>
                </a>
            </li>-->
            <li class="user-profile_menu_item blog">
                <a href="<?=site_url('page/about/blog')?>">
                    <div class="user-profile_menu_item_img">
                        <!--							<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_1')?></div>
                </a>
            </li>
           <li class="user-profile_menu_item bonuses">
                <a href="<?=site_url('partner/invite')?>">
                    <div class="user-profile_menu_item_img">
                        <!--							<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_2')?></div>
                </a>
            </li>

			<li class="user-profile_menu_item arbitrage">
                <a href="<?=site_url('stabfund')?>">
                    <div class="user-profile_menu_item_img">
                    <!--<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('стаб. фонд')?></div>
                </a>
            </li>
            <li class="user-profile_menu_item applications_credits">
                <a href="<?=site_url('account/applications_credits')?>">
                    <div class="user-profile_menu_item_img">
                                                                            <!--<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_8')?></div>
                </a>
            </li>
            <!--<li class="user-profile_menu_item archive">
                <a href="<?=site_url('account/archive_invests_enqueries')?>">
                    <div class="user-profile_menu_item_img">
                                                                            <!--<div class="user-profile_menu_item_img_notification">3</div>
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_9')?></div>
                </a>
            </li>-->
            <?php 
//            $user_array = array(
////                '91802698',
////                '60830397',
////                '93517463',
////                '82938815',
////                '38854127',
//                
//                '99676729',
//                '500150',
//                '500733',
//                '500757',
//                '32906549',
//                '90837257', 
//                '96013991', 
//                '81336307',
//                '26070292',
//                '49643480',
//                '90835923',
//                '90836571'
//                
//            );
//            if(array_search($user->id_user, $user_array) !== false):
            ?>
            <li class="user-profile_menu_item currency_exchange" style="width: auto;">
                <a href="<?=site_url('account/currency_exchange/sell_search?show_start=20')?>">
                    <div class="user-profile_menu_item_img">
                                                                            <!--<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name" style="white-space: nowrap; "><?=_e('blocks/renderHeaderMenu_part_10')?></div>
                </a>
            </li>
            <?php // endif; ?>
			<li class="user-profile_menu_item webtransfer_visa" style="width: auto;">

                   <a href="<?=site_url('account/card-lst')?>">

                    <div class="user-profile_menu_webtransfer_visa">
                       
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('WEBTRANSFER VISA')?></div>
                </a>
            </li>
            <li class="user-profile_menu_item affiliate_program">

                    <? if ($this->user->partner != 1)  echo '<a href="'. site_url('account/about_partner') .'">';
                       else  echo '<a href="'.site_url('partner/my-link').'">'; ?>

                    <div class="user-profile_menu_item_img">
                        <!--							<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_3')?></div>
                </a>
            </li>
            <!--li class="user-profile_menu_item volunteerChat">
                <a href="<?=site_url('partner/support')?>">
                    <div class="user-profile_menu_item_img">
                        <? if ($accaunt_header['openTopics']){ ?>
                        <div class="user-profile_menu_item_img_notification"><?= $accaunt_header['openTopics'] ?></div>
                        <?}?>
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_4')?></div>
                </a>
            </li-->
<!--
            <li class="user-profile_menu_item notifications">
                <a href="<?=site_url('account/inbox')?>">

                    <div class="user-profile_menu_item_img">
                        <? if ($accaunt_header['newMessage']): ?>
                            <div class="user-profile_menu_item_img_notification"><?= $accaunt_header['newMessage'] ?></div>
                        <? endif; ?>
                    </div>

                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_5')?></div>
                </a>
            </li>-->
            <li class="user-profile_menu_item settings">
                <a href="<?=site_url('account/profile')?>">
                    <div class="user-profile_menu_item_img">
                        <!--							<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_6')?></div>
                </a>
            </li>
            
            <li class="user-profile_menu_item exit">
                <a href="<?=site_url('login/out')?>">
                    <div class="user-profile_menu_item_img">
                        <!--							<div class="user-profile_menu_item_img_notification">3</div>-->
                    </div>
                    <div class="user-profile_menu_item_name"><?=_e('blocks/renderHeaderMenu_part_7')?></div>
                </a>
            </li>

        </ul>
        </div>
        <div class="user-profile_wrapper">
                <a class="user-profile_img" href="https://webtransfer.com/social/profile/?lang=<?=_e('lang')?>">
                        <div class="user-profile_img_wrapper">
                                <img src="<?= $accaunt_header['photo_50'] ?>" alt="User image">
                        </div>
                        <!-- <? if ($accaunt_header['socialMessagesNewCnt']): ?>
                                <div class="user-profile_notification top-prof-not"><?= $accaunt_header['socialMessagesNewCnt'] ?></div>
                        <? elseif ($accaunt_header['newMessage']): ?>
                            <div class="user-profile_notification top-prof-not"><?= $accaunt_header['newMessage'] ?></div>
                        <? endif; ?> -->

                </a>
        </div>
</nav>

