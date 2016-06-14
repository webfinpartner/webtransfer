<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!--<div class="content fright" style="margin-top:20px">-->
    <div class="news_full" style="display: inline-block;width:100%">
        <div class="new_content">
            <div class="title"><?= $item->title ?></div>
            <div class="date"><?= $item->data ?></div>
            <? if (!empty($item->foto)) { ?>
            <div class="image">
                <div class="play"></div>
                <a rel="fancybox">
                    <img src="/upload/news/<?= $item->foto ?>" style="width:200px;" alt="" />
                </a>
            </div>
            <? } ?>
            <p><?= $item->text ?></p>
        </div>
    </div>
<br/><br/><p><?=_e('news/data1')?> <a href="<?=site_url("news/".$item->url)?>" target="_blanck"><?=site_url("news/".$item->url)?></a></p>
<!--</div>-->
<? //$item->title;?>
<? //$item->text;?>
<? //base_url("news/".$item->url);?>
<? //$item->data;?>
<? //$item->foto;?>