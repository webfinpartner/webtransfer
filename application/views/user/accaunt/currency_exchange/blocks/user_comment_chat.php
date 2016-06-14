<div class="problem_chat_messages">
    <div class="all_chat_messages"></div>
    <img class='loading-gif' style="display: none" src="/images/loading.gif"/>                                
    <div class="alert alert-info answer-form" style="display: none;">
        <form action="<?=site_url('account/currency_exchange/ajax/curency_problem')?>" method="post" enctype="multipart/form-data">
            <?php if(isset($responce) && $responce === true): ?>
                <input type="hidden" name="responce" value="1">
            <?php else: ?>
            <select name="problem_id" style="float:left;">
                <?php foreach($curency_problem as $problem): ?>
                <option value="<?php echo $problem->id; ?>" data-machine-name="<?=$problem->machin_name?>">
                    <?php // echo $problem->human_name; ?>
                    <?php  echo _e('currency_exchange/problem_suject/'.$problem->machin_name); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            <div class="clear"></div>
            <input type="text" name="problem_subject" value="" style="float:left; display: none;">
            <div class="clear"></div>

            <textarea maxlength="2048" rows="5" required="required" class="form-control" name="msg_body"></textarea>
            <br/>
            
            <button class="btn btn-warning pull-right grey-background" onclick="show_form_user_send_message_operator($(this)); return false;" style="background-color: red;" >
                <?= _e('currency_exchange/user_comment_chat/but_operator')?>
            </button>
            
            <?= _e('currency_exchange/user_comment_chat/doc')?> <input id="foto" type="file" name="foto" />
            <br/><br/>
            <div class="clear"></div>
            <button class="btn btn-warning pull-right" onclick="return quick_answer_hide(<?=$order->id?>)">
                <?= _e('currency_exchange/user_comment_chat/but_cancel')?>
            </button>
            <button class="btn btn-success" type="button" onclick="send_user_probem_message(this); return false;">
                <?= _e('currency_exchange/user_comment_chat/but_send')?>
            </button>
            <input type="hidden" name="exchange_id" value="<?=$order->id?>">
            <input type="hidden" name="problem_submit" value="1">
            <div class="clear"></div>
        </form>
    </div>
</div>
<div class="clear"></div>