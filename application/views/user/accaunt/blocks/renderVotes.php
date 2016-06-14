<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div style="text-align:center;">
<? foreach ($vote_content as $id => $val) {?>
<div style="display:inline-block;text-align:center;width:310px;height:275px;margin:10px;">
<?=$val?>
<span style="width:150px;float:left;">
<?=_e('blocks/secondary_renderVideo_part_1')?> - <span class="vote_count"><?=(int)$votes[$id]?></span>
</span>
<? if(!$isVoted){ ?>
<span class="vote_button" style="width:100px;float:right;">
    <button class="but agree" style="margin-left:0px;" data-id-vote="<?=$id_vote?>" data-variant="<?=$id?>" onclick="sentVote($(this))"><?=$vote_but_label?></button>
</span>
<?}?>
</div>
<?}?>
</div>
<script>
    function sentVote(obj){
        var id_vote = obj.data('id-vote'), variant = obj.data('variant');
        $.post(
            "<?=site_url('account/vote')?>",
            {id_vote: id_vote, variant: variant},
            function(a){
                var vote_count = obj.parent().parent().find('.vote_count');
                console.log(vote_count, obj.parent(), obj.parent().parent());
                vote_count.text(parseInt(vote_count.text())+1);
                $('.vote_button').hide();
                alert(a);
            }
        );
    }
</script>