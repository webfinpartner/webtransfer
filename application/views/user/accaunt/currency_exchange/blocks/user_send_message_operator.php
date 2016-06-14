<div class="popup__overlay"></div> 
<div class="popup_window_problem_chat_operator popup_window_exchange" style="/*left: 35%;  width: 25%;*/">
    <div class="close" onclick="hide_form_user_send_message_operator($(this).parent())/*.parent().hide('slow')*/;"></div>
    <h2><?= _e('currency_exchange/user_send_message_operator/title')?></h2>
    
    <div class="problem_chat_messages">
        <div class="alert alert-info answer-form operator_message" style="/*display: none;*/">
            <form action="<?=site_url('account/currency_exchange/ajax/curency_problem')?>" method="post" enctype="multipart/form-data">
                <select name="problem_id" style="float:left;">
                    <?php foreach($curency_problem as $problem): ?>
                    <option value="<?php echo $problem->id; ?>" data-machine-name="<?=$problem->machin_name?>">
                        <?php // echo $problem->human_name; ?>
                        <?php  echo _e('currency_exchange/problem_suject/'.$problem->machin_name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <div class="clear"></div>
                <input type="text" name="problem_subject" value="" style="float:left; display: none;">
                <div class="clear"></div>

                <textarea maxlength="2048" rows="5" required="required" class="form-control" name="msg_body"></textarea>
                <br/>

                <?= _e('currency_exchange/user_send_message_operator/doc')?> <input id="foto" type="file" name="foto" />
                <br/><br/>
                <div class="clear"></div>
                
                <button class="btn btn-success" type="button" onclick="send_user_probem_message_for_operator(this); return false;">
                    <?= _e('currency_exchange/user_send_message_operator/send_message')?>
                </button>
                
                <img class='loading-gif' style="display: none" src="/images/loading.gif"/>
                
                <input type="hidden" name="exchange_id" value="">
                <input type="hidden" name="problem_submit" value="1">
                <input type="hidden" name="problem_submit_operator" value="1">
                <div class="clear"></div>
            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>