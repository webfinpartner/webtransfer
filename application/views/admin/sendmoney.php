<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div style="float: right;">
    <a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/<?=$controller?>/create"><span>Добавить <?=$view_all['one']?></span></a>
</div>
<div id="table" style="clear: both;">
    <div data-dtable="loading" class="loading">
        Loading...
    </div>
</div>
<table>
    <tr><td>Легенда:</td><td></td></tr>
    <tr class="trans_send"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - пересылка между пользователями</td> </tr>
    <tr class="diff_acc_ip"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - IP которые использовались для входа в разные акк.</td> </tr>
    <tr class="std_credits"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - не выплаченые кредиты</td> </tr>
    <!--<tr class="diff_acc_credits"><td class="col_id_user_ip">192.168.0.1</td> <td class="black"> - IP которые использовались для выдачи и получении кредитов с разных акк</td> </tr>-->
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
</style>


<!-- required for number formatter -->
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>

<script>

    $().ready(function () {
//        setTimeout(function(){
//            location.reload();
//        }, 5*60*1000);
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
        <? if( $search_text ){ ?>
            $('#table input.form-control').val( <?=$search_text?> );
        <?}?>
    });

    function renderRows(){

        var ids = {}, ips = {}, col = 0, id = 0, ip = 0, ip_str = '', ip_ar;

        $('#table .table tbody tr').each(function(){
            col = $(this).find('.col_id_metod').attr('metod');
            ip_str = $(this).find('.col_id_user_ip').attr('user_ip');
            id = $(this).find('.col_id_id_user').attr('id_user');

            ip_ar = ip_str.split(',');
            ip = ip_ar[0];


            ids[$(this).find('.col_id_id_user').attr('id_user')] = $(this).find('.col_id_id_user').attr('id_user');

            if( ip != undefined && ip != 'null' && ip != 0 && col == 'Вывод средств' )
            {
                ips[ip] = id;
            }
        });
        $.post("opera/payment/getIdsProp", {ids: ids, ips:ips}, function(a){
            var id, col, ip;

            $('#table .table tbody tr').each(function(){
                id = $(this).find('.col_id_id_user').attr('id_user'),
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
        $(".col_id_id").click(function(){
            var field = "id";
            var id = $(this).attr(field);
            window.open("/opera/<?=$controller?>/"+id,'_blank');
            return false;
        });
    };
</script>