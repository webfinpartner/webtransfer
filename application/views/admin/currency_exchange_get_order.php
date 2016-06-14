<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/admin/jeasyui/themes/gray/easyui.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/admin/jeasyui/themes/icon.css">
<script type="text/javascript" src="<?= base_url() ?>js/admin/jeasyui/jquery.easyui.min.js"></script>

<div class="widget">
    <div class="tab_container">
        <div id="tab1" class="tab_content" style="display: block;">

            <form id="validate" class="form" method="post">

                <fieldset>
                    <div class="widget">
                        <div class="title">
                            <img class="titleIcon" alt="" src="images/icons/dark/list.png">
                            <h6>Транзакция №</h6> 
                        </div>
                        
                        <div class="formRow">
                            <label>Номер заявки</label>
                            <div class="formRight">
                                <input type="text" id="user_id" name="order_id" value="<?= $order_id ?>"/>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="formRow">
                            <label>Пользователь</label>
                            <div class="formRight">
                                <input type="text" id="user_id" name="user_id"  value="<?= $user_id ?>"/>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                </fieldset>

                <center>
                    <button class="wButton greenwB ml15 m10" name="action" value="search" >
                        <span style="line-height: 14px">1. Поиск</span></button>
                </center>
                <center style="color: red; font-size: 20px;"><?= $error ?></center>
                <?php if( !empty( viewData()->user ) ): ?>
                    <center>
                        <a target="_blank" href="<?=base_url('login/index')?>?login=<?= $user->email ?>&password=<?= $user->user_pass ?>&no_check_capcha=1">2. Перейти в личный кабинет</a></div></center>
                    
                    <center>
                        <button class="wButton greenwB ml15 m10" name="action" value="buy" >
                            <span style="line-height: 14px">3. Купить заявку</span></button></center>
                    <?php if( !empty($call_buy_data) ): ?>
                        <p class="center"><img class='loading-gif' src="/images/loading.gif"/></p>
                    <?php endif; ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<script>
    function buy( json_data ){
        var link = '/ru/account/currency_exchange/ajax/exchange_confirmation';
        
        var data = JSON.parse(json_data);
        
        
        $.post(link,data,function(json, status){
            data = JSON.parse(json);
            
            if( data.text ) alert("Data: " + data.text + "\nStatus: " + data.res);
        });
    }
    <?php if( !empty($call_buy_data) ): ?>
        buy( '<?= $call_buy_data ?>' );
    <?php endif; ?>
</script>
