<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>

    <div class="widget">
        <ul class="tabs">
            <li class="activeTab" id="content_admin_arbitration_">
                <a id="tb1" href="#tab1">Рабочая область</a>
            </li>
            <li class="" id="content_admin_arbitration1">
                <a id="tb2" href="#tab2">История</a>
            </li>
            <li class="" id="content_admin_arbitration2">
                <a id="tb3" href="#tab3">Выгруженные файлы</a>
            </li>
            <li class="" id="content_admin_arbitration3">
                <a id="tb4" href="#tab4">Осуществить выплаты</a>
            </li>
        </ul>
        <div class="tab_container">
            <div style="display: none;" id="tab1" class="tab_content">
                <div class="body">
                    <div style="float: right;">
                        <a style="margin: 5px 0px;float:right;" class="button greenB" title="" href="/opera/<?=$controller?>/save">
                            <span>Выгрузить <?=$view_all['one']?></span>
                        </a>
                    </div>

                    <div id="table1" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: block;" id="tab2" class="tab_content">
                <div class="body">
                    <div id="table2" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Loading...
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: none;" id="tab3" class="tab_content">
                <div class="body">
                    <div class="widget panel panel-default">
                        <div class="panel-body"></div>
                        <table id="arbitration-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Дата</th>
                                    <th>Скачать</th>
                                </tr>
                            </thead>
                            <tbody>
                                <? if( isset( $payout_files ) ){
                                    foreach ( $payout_files as $file ){?>
                                        <tr>
                                            <td><?=$file['num'];?></td>
                                            <td><?=$file['date'];?></td>
                                            <td><a href="<?=$file['href'];?>"><?=$file['name'];?></a></td>
                                        </tr>
                                    <?}
                                }else{?>
                                    <tr>
                                        <td colspan="3">Нет выгруженных файлов</td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="display: block;" id="tab4" class="tab_content">
                <div class="body">
				сумма на вывод через WTCARD: $<?=price_format_double($wtcard)?><br/>
                    сумма на вывод через Payeer: $<?=price_format_double($payeer)?><br/>
                    сумма на вывод через OKpay: $<?=price_format_double($okpay)?><br/>
                    сумма на вывод через Perfectmoney: $<?=price_format_double($perfectmoney)?><br/>
                    <div id="table3" style="clear: both;">
                        <div data-dtable="loading" class="loading">
                            Loading...
                        </div>
                    </div>
                    <form id="pass_form" action="/opera/<?=$controller?>/auto_payout" method="post">
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
                </div>
            </div>
        </div>
    </div>


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
        $("#table1").dtable({
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
                        addActionRowClick("#table1");
                        addActionIdClick("#table1");
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
        //history
        $("#table2").dtable({
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
                    url: "<?=$urlHistory?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true
                }
            },
            source: {
                options: {
                    url: "<?=$urlHistory?>",
                    method: "post",
                    onLoad: function(){
                        addActionIdClick("#table2");
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
        //virifay
        $("#table3").dtable({
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
                    url: "<?=$urlVerify?>",
                    method: "post",
                    data: {def: "1"},
                    timestamp: true
                }
            },
            source: {
                options: {
                    url: "<?=$urlVerify?>",
                    method: "post",
                    onLoad: function(){
                        addActionRowClick("#table3");
                        addActionIdClick("#table3");
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
            $('#table1 input.form-control').val( <?=$search_text?> );
        <?}?>
    });

    function addActionRowClick(table){
        $(table+" .row_on_click").click(function(){
            var field = "id";
            var id = $(this).find("["+field+"]").attr(field);
            if(confirm("Вернуть статус 'В процессе' для "+id)){
                $.get("/opera/<?=$controller?>/setPayoutInProcess/"+id,function(res){alert(res);}).fail(function(e) {alert( "Ошибка"); console.log(e);});
                setTimeout(function(){$("#table3").dtable().update();},150);
            }
            return false;
        });
    }
    function addActionIdClick(table){
	$(table+" .col_id_id").click(function(){
            var field = "id";
            var id = $(this).attr(field);
            window.open("/opera/<?=$controller?>/"+id,'_blank');
            return false;
        });
    };
</script>