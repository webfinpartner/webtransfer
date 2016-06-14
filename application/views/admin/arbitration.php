<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<style>
    .help.blue,
    #arbitration-table.table-striped > tbody > tr.blue td{
        background-color:rgba(0,0,255,0.2);
    }
    .help.yellow,
    #arbitration-table.table-striped > tbody > tr.yellow td{
        background-color:rgba(255,255,0,0.2);
    }
    .help.orange,
    #arbitration-table.table-striped > tbody > tr.orange td{
        background-color:rgba(255,128,0,0.2);
    }
    .help.red,
    #arbitration-table.table-striped > tbody > tr.red td{
        background-color:rgba(255,0,0,0.2);
    }
    .help.black,
    #arbitration-table.table-striped > tbody > tr.black td{
        background-color:rgba(0,0,0,0.2);
    }
    .help{
        display: inline-block;
        height: 20px;
    }
    .item_delete{
        width: 13px;
        cursor: pointer;
    }
    .form1-control{
        font-size: 11px;
        padding: 7px 6px;
        background: none repeat scroll 0% 0% #FFF;
        border: 1px solid #DDD;
        width: 80px;
        font-family: Arial,Helvetica,sans-serif;
        box-shadow: 0px 0px 0px 2px #F4F4F4;
        color: #656565;
    }
</style>
<?
if (!empty($allInqueries)) {

    if (!empty($error)) {
        ?><div class="nNote nInformation hideit">
            <p><strong>Сообщение: </strong><?= $error ?></p>
        </div><? } ?>
    <style>.center{text-align:center} .center h5{font-size:15px}</style>
    <div class="widget">
    </div>
    <div class="widget">
        <ul class="tabs">
            <li class="activeTab" id="content_admin_arbitration_">
                <a id="tb1" href="#tab1">Активные заявки</a>
            </li>
            <li class="" id="content_admin_arbitration">
                <a id="tb2" href="#tab2">Корзина</a>
            </li>
        </ul>
        <div class="tab_container">
            <div style="display: block;" id="tab1" class="tab_content">
                <div class="title">
                    <img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" />
                </div>
                <div class="body">
                    <div class="widget panel panel-default">
                        <div class="panel-body"></div>
                        <table id="arbitration-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Пользователь</th>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                    <th>Описание</th>
                                    <th>Выплачен</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                foreach ($allInqueries as $item) {
                                    if ($item->payed == '1')
                                        $isPayed = 'checked="checked"';
                                    else
                                        $isPayed = '';
                                    ?>
                                    <tr class="<?= $item->color ?>">
                                        <td><?= $item->id ?></td>
                                        <td class="col-user-id" data-user="<?= $item->user_id ?>"><?= $item->user_id ?></td>
                                        <td><?= $item->date ?></td>
                                        <td>
                                            <input class="form1-control" type="text" value="<?= $item->amount ?>"/>
                                        </td>
                                        <td><?= $item->text ?></td>
                                        <td>
                                            <input class="checkbox_user_id" type="checkbox" <?= $isPayed ?>/>
                                            <input class="item_id" type="hidden" value="<?= $item->id; ?>"/>
                                        </td>
                                        <td>
                                            <img src="/images/w128h1281338911586cross.png" class="item_delete" />
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="help blue">2 раза</div>
                    <div class="help yellow">3 раза</div>
                    <div class="help orange">4 раза</div>
                    <div class="help red">5 раз</div>
                    <div class="help black"> > 5 раз</div>
                </div>
            </div>
            <div style="display: none;" id="tab2" class="tab_content">
                <div class="title">
                    <img src="images/icons/dark/clipboard.png" alt="" class="titleIcon" />
                </div>
                <div class="body">
                    <div class="widget panel panel-default">
                        <div class="panel-body"></div>
                        <table id="arbitration-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Пользователь</th>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                    <th>Описание</th>
                                    <th>Выплачен</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                foreach ($removedInqueries as $item) {
                                    if ($item->payed == '1')
                                        $isPayed = 'checked="checked"';
                                    else
                                        $isPayed = '';
                                    ?>
                                    <tr class="<?= $item->color ?>">
                                        <td><?= $item->id ?></td>
                                        <td class="col-user-id" data-user="<?= $item->user_id ?>"><?= $item->user_id ?></td>
                                        <td><?= $item->date ?></td>
                                        <td><?= $item->amount ?></td>
                                        <td><?= $item->text ?></td>
                                        <td>
                                            <input class="checkbox_user_id not_active" type="checkbox" <?= $isPayed ?>/>
                                        </td>
                                        <td>
                                            <!--<img src="/images/w128h1281338911586cross.png" class="item_delete" />-->
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? } ?>
<script>
    $(function() {

        var showMessageTimeout = null;
        function showMessage(message, color) {
            if (!color)
                color = 'red';
            $('.panel-body').html('<p style="color:' + color + ';display:none;">' + message + '</p>');
            $('.panel-body p').show('slow');
            clearTimeout(showMessageTimeout)
            showMessageTimeout = setTimeout(function() {
                $('.panel-body p').hide('slow');
            }, 7000);
        }
        $('#arbitration-table .col-user-id').click(function() {
            var user_id = $(this).attr('data-user');
            if (user_id && user_id != 0)
                window.open('/opera/payment/all');
        });
        function default_success(responce, _success, _error, _always) { 			// выполнится после AJAX запроса
            var html = '';
            if (responce === '') {
                showMessage('Неверный ответ от сервера.');
                return;
            }
            try {
                html = JSON.parse(responce);
            } catch (e) {
                showMessage('Неверный ответ от сервера.');
                return;
            }

            if (html['error']) {
                showMessage(html['error']);
                if ( _error )
                    _error();
            } else if (html['success']) {
                showMessage(html['success'], 'green');
                if ( _success )
                    _success();
            }
            if ( _always )
                _always();
        }
        ;
        function default_error(responce) { 			// выполнится после AJAX запроса
            showMessage('Сервер вернул ошибку. Пожалуйста, повторите попытку позже.');
        }
        ;
        function sendAjax(_url, _data, _success, _error) {
            if (!_success)
                _success = default_success;
            if (!_error)
                _error = default_error;
            $.ajax({
                type: "POST",
                url: _url,
                dataType: "text",
                cache: false,
                data: _data,
                success: _success,
                error: _error
            });
        }
        //отмечаем заявку выплаченной
        $('#arbitration-table .checkbox_user_id:not(.not_active)').click(function() {
            var item_id = $(this).parentsUntil('tr').find('.item_id').val(),
                is_checked = ($(this).prop('checked') == true ? '1' : '0');
            sendAjax("opera/users/arbitration", {item_id: item_id, action: 'set_payed', was_payed: is_checked});
        });
        $('#arbitration-table .form1-control').keydown(function( e ) {
            if (e.keyCode != 13)
                return true;
            var item_id = $(this).parentsUntil('tbody').find('.item_id').val(),
                amount = parseInt( $(this).val() );//($(this).prop('checked') == true ? '1' : '0');
            sendAjax("opera/users/arbitration", {item_id: item_id, action: 'set_amount', amount: amount});
        });
        $('.item_delete').click(function() {
            var item_row = $(this).parentsUntil('tbody'),
                    item_id = item_row.find('.item_id').val();

            sendAjax("opera/users/arbitration", {item_id: item_id, action: 'delete'},
            function(responce) {

                default_success(responce,
                function() {
                    item_row.slideUp(300, function() {
                        $(this).remove();
                    });
                });
            });
        });

    });
</script>
