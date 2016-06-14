<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div>Initiator</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <td>id</td>
            <td>User id</td>
            <td>Summa</td>
            <td>Bonus</td>
            <td>Date</td>
            <td>Type</td>
            <td>Value</td>
            <td>Status</td>
            <td>Note</td>
        </tr>
    </thead>
    <tbody>
        <? foreach( $transactions['seller'] as $t): ?>
        <tr>
            <td><a href="/opera/payment/<?= $t->id ?>" target="_blank"><?= $t->id ?></a></td>                        
            <td><?= $t->id_user ?></td>                        
            <td><?= $t->summa ?></td>                        
            <td><?= $t->bonus ?></td>                        
            <td><?= $t->date ?></td>                        
            <td><?= $t->type ?></td>                        
            <td><?= $t->value ?></td>                        
            <td><?= getTransactionLabelStatus($t->status) ?></td>
            <td><?= $t->note ?></td>                        
        </tr>
        <? endforeach; ?>
    </tbody>            
</table>

<div>Buyer</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <td>id</td>
            <td>User id</td>
            <td>Summa</td>
            <td>Bonus</td>
            <td>Date</td>
            <td>Type</td>
            <td>Value</td>
            <td>Status</td>
            <td>Note</td>
        </tr>
    </thead>
    <tbody>
        <? foreach( $transactions['buyer'] as $t): ?>
        <tr>
            <td><a href="/opera/payment/<?= $t->id ?>" target="_blank"><?= $t->id ?></a></td>                        
            <td><?= $t->id_user ?></td>                        
            <td><?= $t->summa ?></td>                        
            <td><?= $t->bonus ?></td>                        
            <td><?= $t->date ?></td>                        
            <td><?= $t->type ?></td>                        
            <td><?= $t->value ?></td>                        
            <td><?= getTransactionLabelStatus($t->status) ?></td>
            <td><?= $t->note ?></td>                        
        </tr>
        <? endforeach; ?>
    </tbody>            
</table>

<?php if(!empty($transactions['coles']) ): ?>
<div>Comission to Koles</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <td>id</td>
            <td>User id</td>
            <td>Summa</td>
            <td>Bonus</td>
            <td>Date</td>
            <td>Type</td>
            <td>Value</td>
            <td>Status</td>
            <td>Note</td>
        </tr>
    </thead>
    <tbody>
        <? foreach( $transactions['coles'] as $t): ?>
        <tr>
            <td><a href="/opera/payment/<?= $t->id ?>" target="_blank"><?= $t->id ?></a></td>                        
            <td><?= $t->id_user ?></td>                        
            <td><?= $t->summa ?></td>                        
            <td><?= $t->bonus ?></td>                        
            <td><?= $t->date ?></td>                        
            <td><?= $t->type ?></td>                        
            <td><?= $t->value ?></td>                        
            <td><?= getTransactionLabelStatus($t->status) ?></td>
            <td><?= $t->note ?></td>                        
        </tr>
        <? endforeach; ?>
    </tbody>            
</table>
<?php endif; ?>



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

