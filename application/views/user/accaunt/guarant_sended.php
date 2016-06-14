
<?
if (!empty($credits)){
    foreach ($credits as $item)
        $this->load->view('user/accaunt/blocks/renderInvests_part.php', compact("item"));?>

    <?if(10 < $item->count){?>
    <div id="next_orders">

    </div>
    <br/>
    <center><a id="next" class="button narrow" href="#" onclick="return false" data-count="10"><?=_e('accaunt/credits_enqueries_1')?></a></center>

    <script>
        $("#next").click(function(){
            var count = $("#next").data("count");
            var all = <?=$item->count?>;
            console.log(all);
            console.log('next_<?=$page_name?>');
            if(Math.ceil(all/10) <= Math.ceil((count+10)/10))
                $("#next").hide();
            $.get(site_url + "/account/next_<?=$page_name?>/"+count,function(d){
                    $("#next_orders").append(d);
                }
            );
            $("#next").data("count", count + 10);
            return false;
        });
    </script>

<?}
} else
    echo "<div class='message'>"._e('Не найдено')."</div>";
?>
<? $this->load->view( 'user/accaunt/blocks/renderPdfDoc_window' ); ?>


<div class="popup_window small" id="user_popup">
    <div class="close"></div>
    <div class="content" style="padding-top: 15px;"></div>
</div>
