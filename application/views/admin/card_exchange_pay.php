<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<div id="card_exchange" style="clear: both;">
    <div data-dtable="loading" class="loading">
        Loading...
    </div>
</div>
<form id="pass_form" action="/opera/payment/auto_payout/card_exchange" method="post">
    <div style="float: right;">
            <span style="margin: 5px 10px;float:left;">
                <select name="sys">
                    <option value="all">Все</option>
                    <option value="312">Payeer</option>
                    <option value="319">OK Pay</option>
                    <option value="318">Perfectmoney</option>
                    <option value="328">WTCard</option>
                </select>
            </span>
            <span style="margin: 5px 10px;float:left;"><input type="password" name="pas" value="" id="pass"></span>
            <script type="text/javascript" src="js/jshash-2.2/md5-min.js"></script>
            <script>
                $("#pass").focusout(function(){
                    $("#pass").val(hex_md5($("#pass").val()));
                })
            </script>
            <a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="#" onclick="$('#pass_form').submit();return false;">
                <span>Оплатить</span>
            </a>
    </div>
    <div>
        <input type="text" name="OkpayWalletIDPay"/> okpayWalletIDPay<br/>
        <input type="password" name="OkpaySecret"/> okpaySecret<br/>
        <br/>
        <input type="text" name="PayeerPayUser"/> PayeerPayUser<br/>
        <input type="text" name="PayeerPayId"/> PayeerPayId<br/>
        <input type="password" name="PayeerPaySecret"/> PayeerPaySecret<br/>
        <br/>
        <input type="text" name="PerfectmoneyID"/> perfectmoneyID<br/>
        <input type="text" name="PerfectmoneyPayer"/> perfectmoneyPayer<br/>
        <input type="password" name="PerfectmoneySecret"/> perfectmoneySecret<br/>
    </div>
</form>


<script src="/js/nunjucks.min.js"></script>
<script src="/js/DTable/DTable.jquery.js"></script>
<script src="js/numeral/numeral.min.js"></script>
<script src="js/numeral/languages.min.js"></script>

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


<script>

    $().ready(function () {
        //work
        $("#card_exchange").dtable({
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
                    timestamp: true
                }
            },
            source: {
                options: {
                    url: "<?=$url?>",
                    method: "post",
                    onLoad: function(){
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
</script>