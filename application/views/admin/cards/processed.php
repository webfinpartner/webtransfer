<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" href="/css/bootstrap.min.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<style>
    tbody > tr {
        cursor: pointer;
    }
    tbody > tr > td {
        background: white;
    }
</style>
<div class="widget">
    <div class="title"><img class="titleIcon" alt="" src="images/icons/dark/list.png"><h6>Список заявок</h6>
    </div>
    <div class="formRow">
        <table class="table table-condensed table-bordered table-responsive table-hover">
            <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Держатель</th>
                <th>Дата подтверждения</th>
                <th>Баланс</th>
            </tr>
            </thead>
            <tbody>
            <?
            if(!empty($cards)){
                foreach($cards as $card){
                    ?>
                    <tr data-user_id="<?=$card->id;?>">
                        <td><?=$card->id;?></td>
                        <td><?=$card->id_user;?></td>
                        <td><?=$card->holder_name;?></td>
                        <td><?=$card->processed;?></td>
                        <td>
                            <input type="text"><button class="get_balance">get</button>
                        </td>
                    </tr>
                    <?
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(".get_balance").each(function(){
        $(this).on('click', function(){
            var id = $(this).parents('tr').data('user_id');
            var self = $(this);
            $.post("opera/cards/request",{ action: "balance", card_id: parseInt(id)}, function(data){
                if(data.errors.length){
                    alert(data.errors)
                    self.prev().val("0.0")
                }else{
                    self.prev().val(parseInt(data.answer) | "0.0")
                }
            },"json")
        })
    })
</script>