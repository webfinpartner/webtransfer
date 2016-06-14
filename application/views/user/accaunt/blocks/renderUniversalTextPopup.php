<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<div style="display: none;z-index: 1101; padding: 20px; <?= $popup['add_style_prop'] ?>"      
     class="popup_window <?= $popup['add_class_prop']  ?>" id="<?= $popup['id_prop']  ?>">    
    <div class="close" onclick="$(this).parent().hide('slow');"></div>
    <? if( isset( $popup['title'] ) && !empty( $popup['title'] ) ): ?>
        <h2><?= $popup['title'] ?></h2>
    <? endif; ?>
    <?= $popup['body'] ?><br>
</div>