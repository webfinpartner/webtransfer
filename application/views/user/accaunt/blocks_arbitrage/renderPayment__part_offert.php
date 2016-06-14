<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="formRow">
    <center>
        <input type="checkbox" name="agree"  value="agree" />
        <?=_e('Я принимаю условия оферты')?>
    </center>
</div>
<script>
    $('[name="agree"]').click(function(){
        if ($(this).prop("checked")){
            $('[name="submit"]').removeAttr("disabled");
            $('[name="submit"]').css("opacity", "1");
            $('[name="submit"]').css("cursor", "pointer");
        }
        else {
            $('[name="submit"]').attr("disabled", "disabled");
            $('[name="submit"]').css("opacity", "0.5");
            $('[name="submit"]').css("cursor", "default");
        }
    });
    function resetWindowArbitrage(){
        $('[name="agree"]').prop('checked', false);
        $('[name="submit"]').attr("disabled", "disabled");
        $('[name="submit"]').css("opacity", "0.5");
        $('[name="submit"]').css("cursor", "default");
    }
</script>