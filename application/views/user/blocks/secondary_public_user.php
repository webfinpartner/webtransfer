<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<nav id="secondary-nav" data-name="public_user">
    <ul class="secondary-nav_list">
        <li class="secondary-nav_item <?= ($page_name=='/'?'active':'') ?>">
            <a class="secondary-nav_link" href="<?=site_url("$user/")?>"><?=_e('Комментарии')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='reviews'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url("$user/reviews")?>"><?=_e('Отзывы')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='statistics'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url("$user/blog")?>"><?=_e('Блог')?></a>
        </li>
        <li class="secondary-nav_item <?= ($page_name=='applications'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url("$user/applications")?>"><?=_e('Заявки')?></a>
        </li>
		<li class="secondary-nav_item <?= ($page_name=='profile'?'active':'') ?>">
           <a class="secondary-nav_link" href="<?=site_url("$user/partners")?>"><?=_e('Партнеры')?></a>
        </li>
    </ul>
</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
<!--<script>
    $(function(){
        $(document).on('click', '#secondary-nav a', function(){
            $(".active").removeClass("active");
            $(this).parent().addClass('active');
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            distroySMS();
            $("#container").html(l);
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html('');
                $("#container").append(a);
                if('undefined' != typeof security)
                    initSMS();
                onChange();
            });
            return false;
        });
    });
    $(function(){
        $(document).on('click', '.see_else', function(){
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            $("#container").html(l);
            distroySMS();
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html('');
                $("#container").append(a);
                if('undefined' != typeof security)
                    initSMS();
                onChange();
            });
            return false;
        });
    });
</script>-->
<!--payment-->
<!--<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<script src="/js/select3/select3-full.min.js"></script>-->
