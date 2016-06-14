<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?><br/>


<? $stat = getStatistic();
if( !isset($stat) || empty($stat) ) {
        $stat = new stdClass();
    }
    if( !isset( $stat->today ) || empty( $stat->today )){
        $stat->today = new stdClass();
        $stat->today->birja_deals = 0;
        $stat->today->birja_value = 0;
    }?>
<link href="/css/admin/datatable.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/css/user/form_reg.css" type="text/css" media="screen" />
<!---->
<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.user.jquery.js"></script>

<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>
<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
    <?=_e('new_73')?>
<!--<p>в общем заявок: <?=$stat->today->birja_deals?> </p>
<p>на сумму: $<?=$stat->today->birja_value?></p>-->

</div>   <?if($this->lang->lang()=='ru'){?>
   <center>
<iframe id='a3f2bef2' name='a3f2bef2' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=31&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='468' height='60'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=ace978d7&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=31&amp;cb={random}&amp;n=ace978d7&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>

   </center>
   <br/>
 <?}?>
<style>
    .dTable table{
        margin-top: 0;
    }
    .dTable  tbody tr{cursor:  pointer;}
    .dTable  tbody td {
        text-align: center;
        padding: 0;
    }
    .dTable  tbody td.red {
        font-weight: bold;
        color: #ff5400;
    }
    .dTable  tbody td.blue {
        font-weight: bold;
        color: #377dff;
    }
    .dTable th {text-align:center;padding:0px !important;}
    #dTables{
        /*overflow: auto;*/
    }
    .dataTables_length,
    .dataTables_filter {
        position: static;
    }
    #dTables .dataTables_paginate {
        text-align: left;
    }
    .col_id_time, .th_col_time, .col_id_id_user, .th_col_id_user{
        display: none;
    }
    .dataTables_filter{
        display: none;
    }
    .find_input, input.find_input{
        width: 50px;
        margin: 0;
    }
</style>

<div  id="dTable" class="dTable">
    <center data-dtable="loading" class="loading">
        <div><?=_e('accaunt/applications_4')?></div>
        <img class="loading-gif" src="/images/loading.gif"/>
    </center>
</div>

<!--POP UP-->
<? $this->load->view('user/blocks/sellCertificat_window.php', compact($security));?>
<? $this->load->view( 'user/blocks/bayExchangeCertificat_window' ); ?>
<? $this->load->view( 'user/blocks/delExchangeCertificat_window' ); ?>
<? $this->load->view( 'user/blocks/editExchangeCertificat_window' ); ?>

<div id="popup_exchange" class="popup_window small" style="display: none;">
    <div onclick="$('#popup_exchange').hide('slow');" class="close"></div>
    <div class="content" >
    </div>
    <center>
        <img id="loading-gif" class="loading-gif" style="display: none" src="/images/loading.gif">
    </center>
</div>
<br/><Br/>
<div>
    <?=_e('new_74')?>
</div>
<script>
        $().ready(function() {
            $("#dTable").dtable({
                template: {
                    options: {
                        view_dir: '/js/DTable/exchange_views',
                        table_template: 'table.html',
                        rows_template: 'rows.html',
                        pagination_template: 'pagination.html'
                    }
                },
                definition: {
                    options: {
                        url: "<?= $url ?>",
                        method: "post",
                        data: {def: "1"},
                        timestamp: true
                    }
                },
                source: {
                    options: {
                        url: "<?= $url ?>",
                        method: "post",
                        onLoad: function() {
                            addAction();
                        }
                    }
                },
                pagination: {
                    options: {
                        show_first_and_last: true,
                        pages: 7,
                        rows_per_page: 20,
                        rows_per_page_select: [20, 50, 100]
                    }
                },
                order: {
                    options: {
                        id: "desc"
                    }
                },
                formatter: {
                    name: "advanced",
                    // this is the default config, we can override it in definition config
                    options: {
                        widget: 'string',
                        widget_options: {
                            escape: true
                        }
                    }
                }
            });
        function addAction() {
            var timer;
            var ajax_request;
            var loader = '<center> \
                    <img id="loading-gif" class="loading-gif" src="/images/loading.gif">\
                </center>';
            var wrap = $('#popup_exchange');
            var tooltipHeight = 160;
            $(".row_on_click").click(function(event){
                var p = jQuery($(this)).offset();console.log(p);
                wrap.css('position', 'absolute').css('z-index', 1100);
                var timeout = wrap.is(':visible') ? 300 : 0;
                wrap.find('.content').html(loader);
                timer = setTimeout(function(){wrap.show('slow')}, 500);
                var field = "id_user";
                var id_user = $(this).find("["+field+"]").attr(field);
                if(typeof ajax_request !== 'undefined')
                    ajax_request.abort();
                    ajax_request = $.get(site_url + '/account/ajax_user/get_user_data/1/' + id_user + '/0', function(data) {
                    wrap.find('.content').html(data);
                    wrap.animate({top: p.top - tooltipHeight, left: event.pageX+20}, timeout);
                    wrap.fadeIn(300);
                });
            });
            $(".row_on_click").mouseout(function(){
                clearTimeout(timer);
                if(typeof ajax_request !== 'undefined')
                    ajax_request.abort();
                if(loader == wrap.find('.content').html()) $('#popup_exchange').hide();
            });
            $(".bn_bayExchangeCertificat").click(function(){
                closeAll();
                var obj = $(this).parent().parent();
                var psnts = <?= json_encode(getGarantPercent());?>;
                var field = "id";
                //var id = obj.find("["+field+"]").attr(field);
                var id = obj.data(field);
                field = "summ_exchange";
                var bay_summ = parseFloat(obj.find("["+field+"]").attr(field));
                field = "out_summ";
                var out_summ = parseFloat(obj.find("["+field+"]").attr(field));
                field = "time";
                var time = parseInt(obj.find("["+field+"]").attr(field));
                field = "summa";
                var summa = parseFloat(obj.find("["+field+"]").attr(field));
                field = "bonus";
                var bonus = obj.find("["+field+"]").attr(field);
                var fond_psnt;
                for (var i in psnts){
                    if(time <= i){ fond_psnt = psnts[i]; break; }
                }

                var gross_profit = Math.round((out_summ - summa)*100)/100;
                var wf = Math.round(gross_profit * fond_psnt)/100;
                var net_profit = Math.round((gross_profit - wf)*100)/100;
                var summ_income = Math.round((summa + net_profit)*100)/100;
                var profit = Math.round((summa + net_profit - bay_summ)*100)/100;
                $("#send_bayExchangeCertificat").data("id",id);
                $("#id_certificat").html(id);
                $("#out_summ").html(out_summ);
                $("#summ").html(bay_summ);
                $("#gross_profit").html(gross_profit);
                $("#summ_income").html(summ_income);
                $("#conrtibutions").html(wf);
                $("#profit").html(profit);
                $("#bonus").html(bonus);
                $(".bayExchangeCertificat").show();
                return false;
            });
            $(".bn_delExchangeCertificat").click(function(el){
                closeAll();
                $("#send_delExchangeCertificat").data("id",$(el.currentTarget).data("id"));
                $(".delExchangeCertificat").show();
                return false;
            });
            $(".bn_editExchangeCertificat").click(function(el){
                closeAll();
                var min = parseFloat($(el.currentTarget).data("summ"));
                var max = parseFloat($(el.currentTarget).data("out_summ"));
                $("#min_summ").html(min);
                $("#max_summ").html(max);
                $("#cb_editExchangeCertificat_id").val($(el.currentTarget).data("id"));
                $("#send_editExchangeCertificat").data("min",min);
                $("#send_editExchangeCertificat").data("max",max);
                $(".editExchangeCertificat").show();
                return false;
            });
        }
        function closeAll(){
            $('#popup_exchange').hide();
            $('.editExchangeCertificat').hide();
            $('.delExchangeCertificat').hide();
            $('.bayExchangeCertificat').hide();
        }

    });
</script>
</div>

<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
   </div>