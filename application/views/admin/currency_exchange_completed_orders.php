<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
    .table-striped > tbody > tr.blue:nth-child(2n+1) > td,
    tr.blue td, blue{
        background-color: rgba(0,0,255,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.red:nth-child(2n+1) > td,
    tr.red td, red{
        background-color: rgba(255,0,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.green:nth-child(2n+1) > td,
    tr.green td, green{
        background-color: rgba(0,255,0,0.2);
        color: #595959;
    }
    .table-striped > tbody > tr.have_in:nth-child(2n+1) > td,
    tr.have_in td, have_in{
        /*background-color: #cccccc;*/
        color: #009900;
    }
    .table-striped > tbody > tr.user_deactive:nth-child(2n+1) > td,
    tr.user_deactive td, user_deactive{
        /*background-color: #595959;*/
        color: #ddd;
    }
    .table-striped > tbody > tr.std_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.std_credits td.col_id_user_ip, std_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.trans_send:nth-child(2n+1) > td.col_id_user_ip,
    tr.trans_send td.col_id_user_ip, trans_send{
        color: red;
    }
    .table-striped > tbody > tr.diff_acc_credits:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_credits td.col_id_user_ip, diff_acc_credits{
        text-decoration: underline;
    }
    .table-striped > tbody > tr.diff_acc_ip:nth-child(2n+1) > td.col_id_user_ip,
    tr.diff_acc_ip td.col_id_user_ip, diff_acc_ip{
        color: red;
    }

    td.black{
        color: black !important;
    }
    .btn i{
        display: none;
    }
    .btn.active i{
        display: inline;
    }
</style><? if ($this->admin_info->permission == 'admin') { ?>
<!--<div style="float: right;">
	<a id="content_admin_payment_all_annul" style="margin: 5px 0px;float:right;" class="button redB" title="" href="/opera/<?=$controller?>/annul">
        <span>Аннулировать <?//=$view_all['one']?></span>
    </a>
    <a id="content_admin_payment_all_create" style="margin: 5px 5px;float:right;" class="button greenB" title="" href="/opera/<?=$controller?>/create"><span>Добавить <?//=$view_all['one']?></span></a>
</div>-->
<? } ?>

<script src="/js/user/security_module.js"></script>

<script>
    $(function(){
        $('#date_from').datepicker({
            dateFormat: 'yy-mm-dd',
        });
        $('#date_to').datepicker({
            dateFormat: 'yy-mm-dd',
        });
        
    });
    
    
    function generate_data_callback( res )
    {
        console.log(res);
        alert( res['text'] );
        
        if( res['result'] == true ) load_table(  );
        
    }
    function generate_data( form, el )
    {
        var $this = $(this);
        
        var in_from = $(form).find("[name='date_from']");
        var in_to = $(form).find("[name='date_to']");
        var error = 0;
        
        console.log(in_from);
        console.log(in_to);
        
        if( $(el).hasClass('active') ) return false;
        
        if( in_from.length == 0 || in_to.length == 0 ) return false;
        
        if( in_from.val() == '' ){ in_from.parent().addClass('has-error'); error++;}
        else
            in_from.parent().removeClass('has-error');
        
        if( in_to.val() == '' ){ in_to.parent().addClass('has-error'); error++; }
        else
            in_to.parent().removeClass('has-error');
        
        $(el).addClass('active');
        
        if( error > 0 ) return false;
        
                mn.get_ajax('/opera/currency_exchange/ajax_generate_completed_orders',
                    { date_from: in_from.val(), date_to: in_to.val() },
                    function(res){
                        $(el).removeClass('active');                
                        generate_data_callback(res);
                        
                    }); //= function( uri ,data, call_back, params )
        
        return false;
    }
    function save_completed_orders(){
        
        window.open('/opera/currency_exchange/save_completed_orders','_blank');        
        return false;
    }
    
</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Формирование выборки</h3>
    </div>
    <form class="panel-body" id="generate_data_form">
        <div class="form-group date" style="float:left; width: 100px; margin-right: 20px;">
            <label for="date_from">From:</label>
            <input class="form-control" id="date_from"  size="16" type="text" name="date_from" value="<?php echo date('Y-m-d', time() - 30*24*3600); ?>"/>
            <span class="glyphicon form-control-feedback"></span>
        </div>
        <div class="form-group date" style="float:left; width: 100px; margin-right: 20px;">
            <label for="date_to">To:</label>
            <input class="form-control" id="date_to"  size="16" type="text" name="date_to" value="<?php echo date('Y-m-d'); ?>"/>
            <span class="glyphicon form-control-feedback"></span>
        </div>
        <div class="form-group date" style="float:left; width: 72%; margin-top: 25px;">
            <a href="#" onclick="return generate_data('#generate_data_form', this);" class="btn btn-default">Сформировать <i class='fa fa-circle-o-notch fa-spin'></i></a>
            <a href="#" onclick="return save_completed_orders();" class="btn btn-default pull-right" >Сохранить</a>
        </div>        
    </form>
</div>


<div id="table" style="clear: both;">
    <div data-dtable="loading" class="loading">
        Loading...
    </div>
    <div id='data_id_user'></div>
</div>



<!--<script src="/js/jquery.js"></script>-->
<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="css/cssDTableFont.css"/>
<style>
    .button span {
        height: auto;
    }
    .loading {
        color: #000000;
    }

    .order-by a {
        color: #222222;
    }

    .order-by a:hover, .order-by a:active, .order-by a:focus {
        outline: 0;
        text-decoration: none;
    }

    .order-by .active {
        color: #0099FF;
        text-decoration: none;
    }
    .glyphicon {
        /*display: none;*/
    }
    .active {
        /*display: block;*/
    }
    .order-by {
        display: block;
    }
    #table th {
        text-align: center;
    }
    .row_on_click{
        cursor: pointer;
    }
    .col_id_state {
        text-align: center;
    }
</style>


<!-- required for number formatter -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css"/>
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>

<script>
    var table = null;
    
    function load_table( data )
    {
        if( !data )
        {
            data = {};
            data.def = 1;
        }
        
        table = $("#table").dtable({
            template: {
                options: {
                    view_dir: '/js/DTable/views',
                    table_template: 'table.html',
                    rows_template: 'rows.html',
                    pagination_template: 'pagination.html'
                }
            },
            definition: {
                options: {
                    url: "<?=$url?>",
                    method: "post",
                    data: data,
                    timestamp: true,
                    search: false
                }
            },
            source: {
                options: {
                    url: "<?=$url?>",
                    method: "post",
                    onLoad: function(){
                        renderRows();
                        addAction();
                    }
                }
            },
//            logger: {
//                options: {
//                    debug: true
//                }
//            },
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
    }
    
    $().ready(function () {
        load_table();        
    });
    
    function renderRows(){

        var methods = <?= "['".implode("', '", getNameofPaymentSys())."']";?>,
            col = null, ids = {}, ips = {},
            ip = 0,
            user_id = 0,
            ids = {}, ips = {}, col = 0, id = 0, ip_str = '', ip_ar;

        $('#table .table tbody tr').each(function(){
            col = $(this).find('.col_id_metod').attr('metod');
            user_id = $(this).find('.col_id_id_user').attr('id_user');
//            ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
            id = $(this).find('.col_id_id_user').attr('id_user');

//            ip_ar = ip_str.split(',');
//            ip = ip_ar[0];



            if( $.inArray(col, methods) > -1 )
                $(this).addClass('green');
            else
            if( col == 'Вывод средств'){
                $(this).addClass('red');

                if( ip != undefined && ip != 'null' && ip != 0 && col == 'Вывод средств' )
                {
                    ips[ip] = id;
                }

            }else
                if( col == 'Webtransfer' && $(this).find('.col_id_note').attr('note').indexOf('Снятие средств для отправки пользователю №') > -1 )
                    $(this).addClass('blue');

            ids[ user_id ] = user_id;
        });
        $.post("opera/payment/getIdsProp", {ids: ids, ips: ips}, function(a){
            var id, col, ip;

            $('#table .table tbody tr').each(function(){
                var id = $(this).find('.col_id_id_user').attr('id_user'),
                col = $(this).find('.col_id_metod').attr('metod');

//                ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
//                ip_ar = ip_str.split(',');
//                ip = ip_ar[0];

                if (col == 'Вывод средств' )
                {
                    if(50 <= a.money_in[id]) $(this).addClass('have_in');

                    if ( ip != undefined && ip != 'null' && ip != 0 )
                    {
                        if( a.money_out_trans[ip] && a.money_out_trans[ip] == id ) $(this).addClass('trans_send');
                        if( a.diff_acc[ip]) $(this).addClass('diff_acc_ip');
                        if( a.money_out_credits[ip] && a.money_out_credits[ip] == id ) $(this).addClass('std_credits');
//                        if( a.diff_acc_credits[ip]) $(this).addClass('diff_acc_credits');
                    }
                }

                if (!a.users_active[id]) $(this).addClass('user_deactive');
            });
        },
        "json");
    }
    function addAction(){
        $(".col_id_id").click(function(){
            var field = "id";
            var id = $(this).attr(field);
            window.open("/opera/<?=$controller?>/"+id,'_blank');
            return false;
        });
    };

</script>