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
</style><? if ($this->admin_info->permission == 'admin') { ?>
<div style="float: right;">
	<a id="content_admin_payment_all_annul" style="margin: 5px 0px;float:right;" class="button redB" title="" href="/opera/<?=$controller?>/annul">
        <span>Аннулировать <?//=$view_all['one']?></span>
    </a>
    <? if ( in_array(Permissions::getInstance()->getAdminId(), [46773,46778] )){ ?>
    <a id="content_admin_payment_all_create" style="margin: 5px 5px;float:right;" class="button greenB" title="" href="/opera/<?=$controller?>/create"><span>Добавить <?//=$view_all['one']?></span></a>
    <? } ?>
</div>
<? } ?>
<div id="table" style="clear: both;">
    <div data-dtable="loading" class="loading">
        Loading...
    </div>
    <div id='data_id_user'></div>
</div>
<table>
    <tr><td>Легенда:</td><td></td></tr>
    <tr class="trans_send"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - пересылка между пользователями</td> </tr>
    <tr class="diff_acc_ip"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - IP которые использовались для входа в разные акк.</td> </tr>
    <tr class="std_credits"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - не выплаченые кредиты</td> </tr>
    <!--<tr class="diff_acc_credits"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - IP которые использовались для выдачи и получении кредитов с разных акк</td> </tr>-->
    <tr class="green"><td>Строка таблицы...</td> <td class="black"> - транзакции на ввод</td> </tr>
    <tr class="red"><td>Строка таблицы...</td> <td class="black"> - транзакции на вывод</td> </tr>
    <tr class="blue"><td>Строка таблицы...</td> <td class="black"> - транзакции на перевод средств другому пользователю</td> </tr>
    <tr class="user_deactive"><td>Строка таблицы...</td> <td class="black"> - Заблокированые пользователи</td> </tr>
    <tr class="have_in"><td>Строка таблицы...</td> <td class="black"> - Пользователи введшие денег в систему более $50</td> </tr>
</table>


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
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>

<script>

    $().ready(function () {

        $("#table").dtable({
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
                    data: {def: "1"},
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
            ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
            id = $(this).find('.col_id_id_user').attr('id_user');

            ip_ar = ip_str.split(',');
            ip = ip_ar[0];



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
                if( col == '<?=$GLOBALS["WHITELABEL_NAME"] ?>' && $(this).find('.col_id_note').attr('note').indexOf('Снятие средств для отправки пользователю №') > -1 )
                    $(this).addClass('blue');

            ids[ user_id ] = user_id;
        });
        $.post("opera/payment/getIdsProp", {ids: ids, ips: ips}, function(a){
            var id, col, ip;

            $('#table .table tbody tr').each(function(){
                var id = $(this).find('.col_id_id_user').attr('id_user'),
                col = $(this).find('.col_id_metod').attr('metod');

                ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
                ip_ar = ip_str.split(',');
                ip = ip_ar[0];

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
        $(".row_on_click").click(function(){
            var field = "id";
            var id = $(this).find("["+field+"]").attr(field);
            window.location = "/opera/<?=$controller?>/"+id;
            return false;
        });
        $(".col_id_id").click(function(){
            var field = "id";
            var id = $(this).attr(field);
            window.open("/opera/<?=$controller?>/"+id,'_blank');
            return false;
        });
        $(".col_id_user_ip").click(function(){
            var field = "user_ip";
            var ip_res = $(this).attr(field),
                ip = ip_res.split(',');

            window.open("/opera/users/users_block/"+ip[0],'_blank');
            return false;
        });
        $(".col_id_note").click(function(){
            return false;
        });

        $(".on_del_click").click(function(){
            var field = "id";
            var id = $(this).find("["+field+"]").attr(field);
            window.location = "/opera/<?=$controller?>/delete/"+id;
            return false;
        });
    };
</script>