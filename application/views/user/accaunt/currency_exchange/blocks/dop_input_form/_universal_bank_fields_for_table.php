<?php 
    $fields[] = [ _e('accaunt/profile_bank_4'), 'wire_beneficiary_bank_country', 'wire_beneficiary_bank_country', 'select_countries', 3, true, true, false, false, "id='select_countries'", get_country_list()];
?>
<?php foreach($fields as $field): ?>
    <?php if(!empty($payment_sys_user_data[$field[1]])): ?>
        <span style="display: inline-block; padding-right: 5px">
            <?=$field[0]?>:
        </span> 
        <?=$payment_sys_user_data[$field[1]]; ?>
        <br/>
    <?php endif; ?>
<?php endforeach; ?>