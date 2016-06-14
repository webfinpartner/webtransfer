<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <td>id</td>
            <td>Debet user id</td>
            <td>Credit user id</td>
            <td> Счет пользователя перед списанием </td>
            <td> Счет пользователя перед получением </td>
            <td> paired </td>
            <td> Сумма </td>
            <td> Сумма пополнения </td>
            <td> Сумма списания </td>
            <td> id оригинальной заявки  </td>
            <td> Note </td>
            <td> Информация </td>
            <td> Дата </td>
        </tr>
    </thead>
    <tbody>
        <? foreach( $prefunds as $item): ?>
        <tr>
            <td><?= $item->id ?></td>
            <td><?= $item->debet_uid ?></td>
            <td><?= $item->credit_uid ?></td>
            <td><?= $item->debet_current_score ?></td>
            <td><?= $item->credit_current_score ?></td>
            <td><?= $item->paired ?></td>
            <td><?= $item->amount ?></td>
            <td><?= $item->credit_amount ?></td>
            <td><?= $item->debit_amount ?></td>
            <td><?= $item->value ?></td>
            <td><?= $item->note ?></td>
            <td><?= $item->tech_note ?></td>
            <td><?= $item->date ?></td>
        </tr>
        <? endforeach; ?>
    </tbody>            
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

