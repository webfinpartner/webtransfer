<table>
    <thead>
        <tr><th>#</th><th><?=_e('Страница')?></th><th><?=_e('Регистраций')?></th><th><?=_e('Посещений')?></th></tr>
    </thead>
    <tbody>
        <?foreach($top as $idx=>$t){ $n = $idx+1; ?>
            <tr><td><?=$n?></td><td><?=$t->url?></td><td><?=$t->registration?></td><td><?=$t->hits?></td></tr>   
        <? } ?>
        
    </tbody>
    
    
</table>
