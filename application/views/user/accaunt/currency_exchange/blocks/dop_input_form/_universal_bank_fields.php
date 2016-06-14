<?php foreach($fields as $field): ?>
    <div class="dop_ps_input_field_container">
    <span class="label_text">
        <?=$field[0]?>
        <?php if(in_array($field[1], $require_fields)): ?>
            <span class="req">*</span>
        <?php else: ?>
            <span class="req">&nbsp;&nbsp;</span>
        <?php endif; ?>
    </span>
        <?php if(empty($ps_user_data[$field[1]]) && isset($fields_default_value[$field[1]])): ?>
            <input type="text" name="<?=$field[1]?>" value="<?= $fields_default_value[$field[1]] ?>" style="border: 1px solid #ddd;"/><br/>
        <?php else: ?>
            <input type="text" name="<?=$field[1]?>" value="<?= @$ps_user_data[$field[1]] ?>" style="border: 1px solid #ddd;"/><br/>
        <?php endif; ?>
    </div>
<?php endforeach; ?>