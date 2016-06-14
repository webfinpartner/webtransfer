<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="secondary-nav">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='profile'?'active':'') ?>" style="width:25%">
            <a class="secondary-nav_link" href="<?=site_url('account/profile')?>"><?=_e('blocks/secondary_settings_1')?></a>
        </li>
        <? /*
         <li class="secondary-nav_item <?= ($page_name=='choose_photo'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url('account/choose_photo')?>"><?=_e('blocks/secondary_settings_2')?></a>
         </li>
         */ ?>
         <li class="secondary-nav_item <?= ($page_name=='documents'?'active':'') ?>" style="width:25%">
            <a class="secondary-nav_link" href="<?=site_url('account/documents')?>"><?=_e('blocks/secondary_settings_3')?></a>
         </li>
         <li class="secondary-nav_item <?= ($page_name=='social'?'active':'') ?>" style="width:25%">
            <a class="secondary-nav_link" href="<?=site_url('account/social')?>"><?=_e('blocks/secondary_settings_4')?></a>
         </li>
         <li class="secondary-nav_item <?= ($page_name=='security'?'active':'') ?>" style="width:25%">
            <a class="secondary-nav_link" href="<?=site_url('account/security')?>"><?=_e('blocks/secondary_settings_5')?></a>
         </li>
    </ul>
</nav>
<script>
$(function(){
    $(document).on('click', '#secondary-nav a', function(){
        $(".active").removeClass("active");
        $(this).parent().addClass('active');
        var url = $(this).attr('href');
        var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
        window.scrollTo(0,0);
        $("#container").html(l);
        $.get(url,function(a){
            history.pushState({}, "", url);
            $("#container").html(a);
        });
        return false;
    });
    $(document).on('click', '#documents_submit', function(){
        var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
//        var form = new FormData($("#documents"));
        window.scrollTo(0,0);
        $(".doc_content").hide();
        $(".doc_content").after(l);
        $("#documents").ajaxSubmit(function(a){
                $("#container").html(a);
        });
        return false;
    });
    $(document).on('click', '#pass_change_save', function(){
        var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
//        var form = new FormData($("#documents"));
        window.scrollTo(0,0);
        $(".security").hide();
        $(".security").after(l);
        $("#change_pass").ajaxSubmit(function(a){
                $("#container").html(a);
        });
        return false;
    });
});
</script>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
