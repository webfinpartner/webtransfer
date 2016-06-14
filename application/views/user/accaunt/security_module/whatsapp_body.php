<form class="form-style-modal">


    <div class="form-group">
        <label id="universal_window_code_text"><?php echo $code_text ?></label>
    </div>
    
    <input class="form-control form_input" type="hidden" value="000000">
    
    <div class="form-group">
        <button class="btn btn-orange pull-left send_button" data-dismiss="modal" onclick="mn.security_module.confirm_code();return false;"><?php echo _e('whatsapp_text_ive_sent_ok'); ?></button>
    </div>

</form>