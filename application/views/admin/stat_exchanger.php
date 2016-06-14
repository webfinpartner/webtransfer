<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid black;
  }
  table td,table th{
    border: 1px solid black;
    text-align: center;
    padding: 2px;
  }
</style>

<table>
    <thead>
        <tr>
            <th></th>
            <?foreach( $dates as $date){?>
            <th><?=$date?></th>
            <?}?>
            <th>Итого</th>
        </tr>
    </thead>
    <tbody>
        <? foreach($paysys as $pk=>$p) { ?>
        <tr>
            <td><b><?=$p?></b></td>
            <?foreach( $dates as $date){?>
                <td>$ <?=$results[$pk][$date]?></td>
            <?}?>
            <td>$ <?=$results[$pk]['total']?></td>
        </tr>
        <? } ?>
        <tr style="background-color: #D3D3D3;">
            <td><b>Итого</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['total'][$date]?></b></td>
            <?}?>
              <td><b>$ <?=$results['total']['total']?></b></td>
        </tr>
        
        <tr>
            <td><b>Итого с карт</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['from_card_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>
        <tr>
            <td><b>Итого на карты</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['to_card_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>
        <tr style="background-color: #D3D3D3;">
            <td><b>Дельта карты</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['to_card_total'][$date]-$results['from_card_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>        
        
        <tr>
            <td><b>Итого с DEBIT</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['from_payment_account_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>
        <tr>
            <td><b>Итого на DEBIT</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['to_payment_account_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>        
        <tr style="background-color: #D3D3D3;">
            <td><b>Дельта DEBIT</b></td>
            <?foreach( $dates as $date){?>
                <td><b>$ <?=$results['to_payment_account_total'][$date]-$results['from_payment_account_total'][$date]?></b></td>
            <?}?>
              <td></td>
        </tr>        
        
    </tbody>
</table>