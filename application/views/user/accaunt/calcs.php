<style>
    
    tr.border_bottom td {
        border-bottom: 1px dotted gray;
    }
</style>

<script>
  $(function() {
      $('#b-0').show();
  });
  
  function onChg(){
     var bonus = $('#bonus option:selected').val();       
     for( i=0; i<= 6; i++) $('#b-'+i).hide();
     $('#b-'+bonus).show();
  }
  </script>
  
  <select id="bonus" onchange="onChg()">
    <option value="0">Общее</option>
    <option value="1">Бонус 1</option>
    <option value="2">Бонус 2</option>
    <option value="3">Бонус 3</option>
    <option value="4">Бонус 4</option>
    <option value="5">Бонус 5</option>
	 <option value="6">Бонус 6</option>
</select>


<?for ($b=0; $b<=5; $b++) {?>    
    <div id="b-<?=$b?>" style="display: none">
    <table>
    <? foreach( $calcs[$b] as $key=>$val ){ ?>
        <tr class="border_bottom"><td><b><?= $key ?></b></td><td><?
        if ( is_array($val)) echo '<pre>'.print_r($val, true).'</pre>'; else echo "$val";
                ?></td></tr>   
    <? } ?>
    </table>
    </div>
<? } ?>    
    
    
    
</div>    