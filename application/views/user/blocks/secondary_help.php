<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
	<nav id="secondary-nav">
		<ul class="secondary-nav_list">
			<li class="secondary-nav_item <?= ($page_name=='about'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('page/about')?>"><?=_e('blocks/secondary_help_1')?></a>
			</li>
			 <li class="secondary-nav_item <?= ($page_name=='lend'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('page/lend')?>"><?=_e('blocks/secondary_help_2')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='borrow'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('page/borrow')?>"><?=_e('blocks/secondary_help_3')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='partnership'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('page/partnership')?>"><?=_e('blocks/secondary_help_4')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='guarantee'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('page/about/guarantee')?>"><?=_e('blocks/secondary_help_5')?></a>
			 </li>
		</ul>
	</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>

<script>
    $(function(){
        $(document).on('click', '.pager a', function(){
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            $("#container").html(l);
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html(a);
                runPluso();
            });
            return false;
        });
        $(document).on('click', '.news_full a[target!=_blank]', function(){
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            $("#container").html(l);
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html('');
                $("#container").append(a);
                runPluso();
            });
            return false;
        });
        $(document).on('click', '.breadcrumbs a', function(){
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            $("#container").html(l);
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html('');
                $("#container").append(a);
                runPluso();
            });
            return false;
        });
        $(document).on('click', '#secondary-nav a', function(){
            $(".active").removeClass("active");
            $(this).parent().addClass('active');
            var url = $(this).attr('href');
            var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
            window.scrollTo(0,0);
            $("#container").html(l);
            $.get(url,function(a){
                history.pushState({}, "", url);
                $("#container").html('');
                $("#container").append(a);
                runPluso();
            });
            return false;
        });
    });

    function runPluso(){
        if (window.pluso && typeof window.pluso.start == "function")
            window.pluso.start();
    }

</script>