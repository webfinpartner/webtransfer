<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
.problem_chat_messages
{
    margin-top: 20px;
    margin-right: 15px;
}

/*.problem_chat_messages select
{
     width: 235px;
}*/

.problem_chat_messages .th_message {
    background: #efefef none repeat scroll 0 0;
    border-radius: 5px;
    display: inline-block;
    
    margin: 5px 0px;
    min-height: 60px;
    padding: 10px;
    position: relative;
    width: 900px;
}

.problem_chat_messages  .th_message.employer {
    background: #f4fced none repeat scroll 0 0;    
}


.problem_chat_messages .th_message .author {
    text-align: left;
    border-right: 1px solid #ccc;
    
    float: left;
    font-weight: bold;
    width: 130px;
}




.problem_chat_messages .th_message .short_date {
    font-size: 10px;
}

.problem_chat_messages .th_message .short_text
{
    width: 80%;
    float: left;
    padding-left: 10px;
    text-align: left;
    color: #646464;
}




.problem_chat_messages .alert-info {
    background-image: linear-gradient(to bottom, #d9edf7 0%, #b9def0 100%);
    background-repeat: repeat-x;
    border-color: #9acfea;
}

.problem_chat_messages .alert {
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.25) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.2);
    
    
    border: 1px solid transparent;
    border-radius: 4px;
    margin-bottom: 20px;
    padding: 15px;
}


.problem_chat_messages .form-control {
    background-color: #ffffff;
    background-image: none;
    border: 1px solid #cccccc;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
    color: #555555;
    display: block;
    font-size: 14px;
    height: 60px;
    line-height: 1.42857;
    padding: 6px 12px;
    transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    vertical-align: middle;
    width: 96%;
    
    
    font-family: inherit;
    overflow: auto;
}


.problem_chat_messages select[name="problem_id"], .problem_chat_messages input[name="problem_subject"]
{
    width: 99%;
    margin-bottom: 10px;
}

.problem_chat_messages select[name="problem_id"]
{
    width: 100%;
}



.problem_chat_messages .btn {
    -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857;
    margin-bottom: 0;
    padding: 6px 12px;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
    float: left;
}

.problem_chat_messages .btn-warning {
    background-color: #f0ad4e;
    border-color: #eea236;
    color: #ffffff;
}


.problem_chat_messages .btn-default, 
.problem_chat_messages .btn-primary, 
.problem_chat_messages .btn-success, 
.problem_chat_messages .btn-info, 
.problem_chat_messages .btn-warning, 
.problem_chat_messages .btn-danger {
    border: medium none;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.15) inset, 0 1px 1px rgba(0, 0, 0, 0.075);
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
}

.problem_chat_messages .btn-warning {
    background-color: #f26722;
}

.problem_chat_messages .btn {
    text-shadow: none;
}

.problem_chat_messages .btn-success {
    background-color: #5cb85c;
    border-color: #4cae4c;
    color: #ffffff;
}

.problem_chat_messages .btn-success {
    background-image: linear-gradient(to bottom, #5cb85c 0%, #419641 100%);
    background-repeat: repeat-x;
    border-color: #3e8f3e;
}

.problem_chat_messages .grey-background {
    background-color: grey;
    border-color: grey;
    color: white;
}
.problem_chat_messages .order {
    margin-bottom: 10px;
}
.problem_chat_messages .close-chat {
    clear: right;
}


.problem_chat_messages .pull-right {
    float: right;
}
.selected_message
{
    text-align: center;
    font-size: 14px;
    font-weight: bold;
}

.problem_chat_file_load
{
    text-align: left;
    margin-top: 10px; 
    font-size: 12px;
}
.messages_list{
    overflow-y: scroll; 
    height: 400px;
    
    background-color: #ссс;
}

</style>
<?php // pre($one_message); ?>
<?php // pre($messages); ?>

<div class="problem_chat_messages">
    
    <div class="all_chat_messages">
        
            <div style="" class="hide_message">    
                
                <?php if( empty( $one_message ) ):  ?>
                    <div class="selected_message">Новый чат</div>
                <?php else:  ?>
                <div class="selected_message">Последнее сообщение</div>
                <div class="th_message employer" id="message2">        
                    <div class="author">
                        <?=$one_message->user_name?>:<br/>
                        <a target="_blank" href="/opera/users/<?=$one_message->user_id?>">(<?=$one_message->user_id?>)</a><br/>
                        <div title="Создано: <?=date_formate_my($one_message->date, 'H:i d.m.y')?>" class="short_date"><?=date_formate_my($one_message->date, 'H:i d.m.y')?></div>        
                    </div>        
                    <div class="short_text">
                        <?php if($one_message->other): ?>
                            <?=$one_message->other?>:
                        <?php else: ?>
                            <?=$one_message->s_human_name?>:
                        <?php endif; ?>
                        <?=$one_message->text?>
                    </div>
                    <?php if($one_message->document): ?>
                    <div class="problem_chat_file_load">
                       <a href="/<?=$one_message->document?>" target="_blank">Прикреплённый файл</a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif;  ?>
            </div>
    
        <hr>
        
    <div class="messages_list">
    <?php if( !empty( $messages ) )
    foreach($messages as $one):?>
            <div style="" class="hide_message">    
                <div class="th_message employer" id="message2">        
                    <div class="author">
                        <? if( $one->user_name == 'Оператор' && $one->group_operator_message ){?>
                            <?=$one->user_name?>:<br/>
                            ::Всем<br/>
                            <div title="Создано: <?=date_formate_my($one->date, 'H:i d.m.y')?>" class="short_date"><?=date_formate_my($one->date, 'H:i d.m.y')?></div>        
                        <? }elseif( $one->user_name == 'Оператор' ){?>
                            <?=$one->user_name?>:<br/>
                            
                            ::<a target="_blank" href="/opera/users/<?=$one->to?>"><?= $chat_users[$one->to]->sername .' '. $chat_users[$one->to]->name ?></a><br/>
                            <div title="Создано: <?=date_formate_my($one->date, 'H:i d.m.y')?>" class="short_date"><?=date_formate_my($one->date, 'H:i d.m.y')?></div>        
                        <? }else{?>
                            <?=$one->user_name?>:<br/>
                            <a target="_blank" href="/opera/users/<?=$one->user_id?>">(<?=$one->user_id?>)</a><br/>
                            <div title="Создано: <?=date_formate_my($one->date, 'H:i d.m.y')?>" class="short_date"><?=date_formate_my($one->date, 'H:i d.m.y')?></div>        
                        <? }?>
                        
                        
                    </div>        
                    <div class="short_text">
                        <div class="text"><?=$one->text?></div>                    
                        <?php if($one->document): ?>
                        <div class="problem_chat_file_load">
                           <a style="color: red;" href="/<?=$one->document?>" target="_blank">Прикреплённый файл</a>
                        </div>                    
                        <?php endif; ?>
                    </div>  
                </div>
            </div>
        
    <?php endforeach; ?>
    </div>
    
    </div>
    
    
   
    
    <img src="/images/loading.gif" style="display: none;" class="loading-gif">                                
    
    <div style="/*display: none;*/" class="alert alert-info answer-form">
        <form method="post" action="" style="position: relative;">
            <!--<input type="hidden" value="1" name="responce">-->
            <div style="float:left; width: 36px; line-height: 25px;">
                Кому:  
            </div>
            <select id="to" class="validate[required]" id="to" name="to" style="float:left;" required="required" >
                <option value=""></option>
                <option value="<?=$chat_users_all?>">Всем</option>
                <?php foreach($chat_users as $one_user): ?>
                <option value="<?php echo $one_user->id_user; ?>" >
                    <?php echo $one_user->sername.' '.$one_user->name .' (' . $one_user->id_user.')'; ?>
                </option>
                <?php endforeach; ?>
            </select>
            
            <div class="clear" style="margin-top: 5px;"></div>
            <input type="text" style="float:left; display: none;" value="" name="problem_subject">
            <div class="clear"></div>

            <textarea name="msg_body" class="validate[required] form-control" required="required" rows="5" maxlength="2048" style="margin-top: 7px; margin-bottom: 10px;"></textarea>
            <!--Выберите документ: <input id="foto" type="file" name="foto" />-->
            <br>
            
            <!--<button style="background-color: red;" onclick="show_form_user_send_message_operator($(this)); return false;" class="btn btn-warning pull-right">Оператору</button>-->
            
            <!--Прикрепить файл: <input type="file" name="foto" id="foto">-->
            
            <div class="clear"></div>
            <!--<button onclick="return quick_answer_hide(50)" class="btn btn-warning pull-right">Отмена</button>-->
            <!--<button onclick="send_user_probem_message(this); return false;" type="button" class="btn btn-success">Отправить сообщение</button>-->
            <button  type="submit" class="btn btn-success" style="position: absolute;bottom: 0;">Отправить</button>
            
            <? if( !empty( $one_message->original_order_id ) ): ?>
                <a href="/opera/currency_exchange/order/<?= $one_message->original_order_id ?>" target="_blank" class="btn pull-right grey-background order">Заявка</a>            
                <a href="/opera/currency_exchange_chat/close_chat/<?= $one_message->order_id ?>" class="btn btn-warning pull-right close-chat" >Закрыть чат</a>
            <? elseif( !empty( $one_message->archive_order_id ) ): ?>
                <a href="/opera/currency_exchange/order_arhiv/<?= $one_message->archive_order_id ?>" target="_blank" class="btn pull-right grey-background order">Заявка</a>            
                <a href="/opera/currency_exchange_chat/close_chat/<?= $one_message->archive_order_id ?>" class="btn btn-warning pull-right close-chat" >Закрыть чат</a>
            <? else: ?>
                <div style="color: red; float:right;">Ошибка при поиске заявки.</div>
            <? endif; ?>

            <input type="hidden" value="<?=$one_message->order_id?>" name="exchange_id">
            <input type="hidden" value="1" name="problem_submit">
            <div class="clear"></div>
        </form>
    </div>
    <!--<div class="clear"></div>-->
</div>