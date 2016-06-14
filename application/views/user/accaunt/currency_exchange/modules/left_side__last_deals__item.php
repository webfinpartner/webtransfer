<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="lobmentable_one">
    <div class="lobmentable">
        <div class="lobmline">
            <div class="lobmlineico-wrap">
                <div class="lobmlineico anim">
                    <div class="obmenlinewico" style="<?= $last_deal['from']['bg'] ?>"></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="lobmlinebac">
                <?= $last_deal['from']['name'] ?><br><?= $last_deal['from']['amount'] ?> <?= $last_deal['from']['currency_name'] ?>
            </div>
            <div class="clear"></div>
        </div>

        <div class="lobmlinepr"></div>

        <div class="lobmline" style="float: right;">
            <div class="lobmlineico-wrap">
                <div class="lobmlineico anim anim2">
                    <div class="obmenlinewico" style="<?= $last_deal['to']['bg'] ?>"></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="lobmlinebac">                
                <?= $last_deal['to']['name'] ?><br><?= $last_deal['to']['amount'] ?> <?= $last_deal['to']['currency_name'] ?>                
            </div>
            <div class="clear"></div>
        </div>	
        <div class="clear"></div>
    </div>
    <div class="lobmendate footer"><?= $last_deal['date'] ?><div style="float:right;"><?= $last_deal['time']?></div></div>
</div>

