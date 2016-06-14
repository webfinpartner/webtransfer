<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?><header id="header">
  <style>
  #user-main-menu {
    z-index: 9;
    position: relative;
}
</style>
    <div class="wrapper" role="banner">		
        <h1 id="logo">			
            <a href="<?=site_url('page/about/blog')?>">				
                <img class="logo-img" src="/img/logowt.gif" alt="<?=_e('blocks/baner_profil_1')?>">	
                <img class="logo-img-mini" src="/img/logowt.gif" alt="<?=_e('blocks/baner_profil_2')?>">
            </a>		</h1>
		<? $this->load->view('user/blocks/renderHeaderMenu_part.php', array("accaunt_header" => $accaunt_header, "user" => $user));?>	
        <nav id="user-main-menu">              
            <ul class="user-main-menu_list" role="menubar" style="z-index: 100;">   
                <li class="user-main-menu_item invest <?= (($page_name == 'invests_enqueries') ? 'active' : '') ?>">  
                    <a class="menu_item_link" href="<?=site_url('account/invests_enqueries')?>">           
                        <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['invests']); ?></div>     
                        <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_3')?></div>         
                    </a>                        </li>    
                <li class="user-main-menu_item borrow <?= (($page_name == 'credits_enqueries') ? 'active' : '') ?>">       
                    <a class="menu_item_link" href="<?=site_url('account/credits_enqueries')?>">         
                        <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['credits']); ?></div>    
                        <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_4')?></div>           
                    </a>                        </li>              
                <li class="user-main-menu_item wallet <?= (($page_name == 'transactions') ? 'active' : '') ?>">      
                    <a class="menu_item_link" href="<?=site_url('account/transactions')?>">               
                        <div class="user-main-menu_item_amount">$ <?= price_format_double( $accaunt_header['payment_account']- $accaunt_header['payment_account_by_bonus'][5]+$accaunt_header['bonuses']+$accaunt_header['partner_funds']+$accaunt_header['c_creds_total'] + $accaunt_header['c_creds_amount_process']+$accaunt_header['total_cards_balance'] +$accaunt_header['total_other_cards_balance'] , FALSE) ?></div>    
                        <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_5')?></div>          
                    </a>                        </li>            
                <li class="user-main-menu_item balans <?= (($page_name == 'my_balance') ? 'active' : '') ?>">        
                    <a class="menu_item_link" href="<?=site_url('account/my_balance')?>">            
                        <div class="user-main-menu_item_amount">$ <?= price_format_double($accaunt_header['balance'], FALSE); ?></div> 
                        <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_6')?></div>                    
                    </a>                        </li>             
                <li class="user-main-menu_item rating <?= (($page_name == 'my_rating') ? 'active' : '') ?>">      
                    <a class="menu_item_link" href="<?=site_url('account/my_rating')?>">                
                        
                        <div class="user-main-menu_item_amount"><?= $accaunt_header['fsr']; ?></div>      
                        <div class="user-main-menu_item_name"><?=_e('blocks/baner_profil_7')?></div>           
                    </a>                        </li>    
            </ul>		</nav>	</div></header>
<?php /*
<script>
    $(function() {
        $(document).on('click', '#user-main-menu a', function() {
            if ($('[data-name="profile"]').length) {
                $(".active").removeClass("active");
                $(this).parent().addClass('active');
                var url = $(this).attr('href');
                var l = '<center><img class=\'loading-gif\' src="/images/loading.gif"/></center>';
                window.scrollTo(0, 0);
                distroySMS();
                $("#container").html(l);
                $.get(url, function(a) {
                    history.pushState({}, "", url);
                    $("#container").html('');
                    $("#container").append(a);
                    if ('undefined' != typeof security) initSMS();
                });
                return false;
            }
        });
    });
</script> */?>