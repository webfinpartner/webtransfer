<?php if(!empty($res_search)): ?>
    <?php  $this->load->view('user/accaunt/currency_exchange/blocks/table_search.php', array('res_search' => $res_search, 'payment_systems_id_arr' => $payment_systems_id_arr, 'user_id'=>$user_id)); ?>
<?php elseif (isset($error_message)): ?>
<table class="table_results">
    <thead class="table_header">
        <td class="table_cell"><?=_e('ID')?></td>    
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/contragent')?></td>    
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/sell')?></td>    
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/date')?></td>
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/buy')?></td>
        <td class="table_cell"><?= _e('currency_exchange/sell_wt_search/status')?></td>                       
    </thead>     
    <tbody class="table_body">
        <tr class="table_row_header htitle">
            <td class="table_cell" colspan="6"><span style="color: #ff7f66;"><?php echo $error_message ?></span></td>  
        </tr>
    </tbody>
</table>
<?php endif; ?>