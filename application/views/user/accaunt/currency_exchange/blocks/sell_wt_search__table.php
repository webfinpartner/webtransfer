<table class="table_results" class="display" style="display: none">
    <thead class="table_header">
        <tr>
        <th data-orderable="true" data-data="id_str" data-searchable="true"  class="table_cell">ID</th>    
        <!--<th data-orderable="false" data-data="seller" class="table_cell"><?= _e('currency_exchange/sell_wt_search/contragent')?></th>-->    
                <!--<th data-orderable="true" data-data="sell" class="table_cell"><?//= _e('currency_exchange/sell_wt_search/sell')?></th>-->    
        <th data-orderable="true" data-data="sell" class="table_cell"><?=sprintf(_e('Вы отдаёте'), $curent_user_data->name_sername)?></th>    
        <th data-orderable="true" data-data="seller_set_up_date" class="table_cell"><?= _e('currency_exchange/sell_wt_search/date')?></th>    
        <!--<th data-orderable="true" data-data="buy" class="table_cell"><?//= _e('currency_exchange/sell_wt_search/buy')?></th>-->    
        <th data-orderable="true" data-data="buy" class="table_cell"><?=sprintf(_e('Вы получаете'), $curent_user_data->name_sername)?></th>            
        <th data-orderable="false" data-data="status_action" class="table_cell"><?= _e('currency_exchange/sell_wt_search/status')?></th> 
        </tr>
    </thead>     
</table>