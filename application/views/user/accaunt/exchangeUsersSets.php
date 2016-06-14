<br>
<form method="post">
    <table style="width: 200px">

<? for ( $i=50; $i<200; $i+=50){ ?>
    <tr>
    <td><?=$i?>-<?=($i+50-1)?></td><td><input type="text" name="<?=$i?>-<?=($i+50-1)?>" style="width: 50px" value="<?=@$sets[$i.'-'.($i+50-1)]?>">%</td>
    </tr>    
<? } ?>
    
<? for ( $i=200; $i<1000; $i+=100){ ?>
    <tr>
    <td><?=$i?>-<?=($i+100-1)?></td><td><input type="text" name="<?=$i?>-<?=($i+100-1)?>" style="width: 50px" value="<?=@$sets[$i.'-'.($i+100-1)]?>">%</td>
    </tr>    
<? } ?>    
    
    <tr><td>>1000</td><td><input type="text" style="width: 50px" name="1000-9999999" value="<?=@$sets['1000-9999999']?>">%</td></tr>

</table>
<button type="submit">Сохранить</button>
    
</form>

