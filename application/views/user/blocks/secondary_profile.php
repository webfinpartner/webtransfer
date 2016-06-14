<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
	<nav id="secondary-nav" data-name="profile">
		<ul class="secondary-nav_list">
			<li class="secondary-nav_item <?= ($page_name=='payment'?'active':'') ?>">
				<a class="secondary-nav_link" href="<?=site_url('account/payment')?>"><?=_e('blocks/secondary_profile_1')?></a>
			</li>
			 <li class="secondary-nav_item <?= ($page_name=='my_invest'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('account/my_invest')?>"><?=_e('blocks/secondary_profile_2')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='my_credits'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('account/my_credits')?>"><?=_e('blocks/secondary_profile_3')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='send_money'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('account/send_money')?>"><?=_e('blocks/secondary_profile_4')?></a>
			 </li>
			 <li class="secondary-nav_item <?= ($page_name=='payout'?'active':'') ?>">
				 <a class="secondary-nav_link" href="<?=site_url('account/payout')?>"><?=_e('blocks/secondary_profile_5')?></a>
			 </li>
		</ul>
	</nav>
<? $this->load->view('user/blocks/qestion_window.php'); ?>
<script>
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
    function gotoInvestArbitrge(){
            var url = $('#invest_arbitraj').attr("href");
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
        }
</script>
<!--payment-->
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/js/select3/select3.css" type="text/css" media="screen" />
<script src="/js/select3/select3-full.min.js"></script>
<!--invests-->
<script type="text/javascript" src="/js/admin/plugins/wizard/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/admin/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/user/jquery.validate.js"></script>
<script type="text/javascript" src="/js/user/jquery-ui-personalized-1.5.3.packed.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/user/additional-methods.js"></script>
<? if('ru' == _e('lang')){ ?><script type="text/javascript" src="/js/user/messages_ru.js"></script><?}?>
<script type="text/javascript" src="/js/user/form_reg.js"></script>
<script type="text/javascript" src="/js/user/credit.js"></script>
<script type="text/javascript" src="/js/user/debits.js"></script>
<!--credits-->
<!--payout-->
<script type="text/javascript" src="/js/user/sms_module.js"></script>

<!--invest_enquaries-->
<link rel="stylesheet" href="/css/user/filter.css" type="text/css" media="screen" />

<!--transaction-->
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link href="/css/calendar-tt.css" rel="stylesheet">
<script src="/js/jquery-ui-1.10.4.custom.js"></script>
<!-- Edn sec nav -->