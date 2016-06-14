<? 
   $blocks_enable = TRUE;
   $blocks = [
     //  'block_2',
	   //'block_1'
   ];
   
   
   $block = NULL;
   if ( !empty($blocks))
    $block = $blocks[ rand(0, count($blocks)-1) ];
   
      if ( $this->router->fetch_class() == 'accaunt' && $this->router->fetch_method() == 'transactions' && !empty($wtcards))
        $block = 'block_3';
?>

<? if ( $blocks_enable && !empty($block) ){ ?>

<style>
div#adv_window_message {
    box-shadow: none !important;
    margin-top: -55px;     background: none !important;
}
div#adv_window_message img {
    width: 85%;
    margin-top: 17px;
}
</style>
<div id="adv_window_message" class="popup_window">
       <div class="modal fade in modal-visa-card" id="modal-visa-card" role="dialog" aria-hidden="false" style="left: 0px; display: block;">
        <div class="modal-dialog" style="width:300px !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="close" onclick="on_popup_adv_close();"></div>
                    <h4 class="modal-title"><?= _e('Реклама') ?></h4>
                </div>
                <div class="modal-body">
                    <? $this->load->view('user/accaunt/adv/'.$block); ?>
                    <form class="form-style-modal" style="padding:10px 30px;">
                        <label>
                            <input type="checkbox" name="nomore"/>
                           <?= _e('Больше не показывать') ?>
                        </label>

                    </form>
                </div>
                <div class="modal-footer">
                    <div class="res-message"></div>
                    <center>
                        <img class="loader" style="display: none" src="/images/loading.gif">
                        
                    </center>
                </div>
                            
                
            </div>
        </div>
    </div>
</div>

<script>
    function on_popup_adv_close(){
        if ( $('#adv_window_message [name=nomore]:checked').length ){
             createCookie("adv_dialog", "1", 365);
        }
        $('#adv_window_message').hide();

    }
    
    $(function(){
        if ( getCookie("adv_dialog") != "1"  )
            $('#adv_window_message').show();
    });
    
</script>
<? } ?>