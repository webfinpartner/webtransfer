<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/jquery-ui.css">
    
<script type="text/javascript" src="/js/user/ui.datepicker-ru.js"></script-->
<style>
        
    
    #placeholder {
	width: 650px;
	border: 1px solid #CDCDCD;
	padding: 5px;
	border-radius: 3px;
	background-color: white;
	margin-bottom: 15px;
	position: relative;
	box-shadow: 1px 1px 2px #EEE, 0 -3px 5px #F3F3F3 inset;
    }

    .ct-label{
        color: black !important;
    }

    #content_chart {
        margin: 0px auto;
        padding: 30px;
        position: relative;
        width: 660px;
    }

    .ct-chart{
        width: 570px;
        height: 400px;
    }

    .tooltip {
        position: absolute;
        display: inline-block;
        min-width: 5em;
        padding: .5em;
        background: #F4C63D;
        color: #453D3F;
        font-weight: 700;
        text-align: center;
        pointer-events: none;
        z-index: 1;
    }
</style>



<div id="content_chart">
<div style="text-align: right; margin-bottom: 5px;"><br>
    <form>
        <? 
           $months = [
                1=>_e('Январь'),
                2=>_e('Февраль'),
                3=>_e('Март'),
                4=>_e('Апрель'),
                5=>_e('Май'),
                6=>_e('Июнь'),
                7=>_e('Июль'),
                8=>_e('Август'),
                9=>_e('Сентябрь'),
               10=>_e('Октябрь'),
               11=>_e('Ноябрь'),
               12=>_e('Декабрь') 
               ];
        ?>
      
        <select name="month_year">
                
            <? 
                $m0 = date('Y-m'); 
                $m1 = date('Y-m', strtotime("-1 month")); 
                $m2 = date('Y-m', strtotime("-2 month")); 
                $m_arr = [$m0, $m1, $m2];
            
            ?>
            
                <? foreach( $m_arr as $i=>$m ){ 
                    $month = $months[intval( substr($m,-2,2) ) ];
                    $year = substr($m,0,4);
                    ?>
                    <option <?if ($month_year == $m){?>selected="selected"<?}?> value="<?=$m?>"><?=$month?> <?=$year?></option>
                <? } ?>
        </select>    
        
        
        
        <? /*
        <select name="month">
            <? foreach( $months as $i=>$m ){ ?>
                <option <?if ($month == $i){?>selected="selected"<?}?> value="<?=$i?>"><?=$m?></option>
            <? } ?>
        </select>    
    
        <select name="year">
            <? for($i=2014; $i<= date('Y'); $i++) {?>
                <option <?if ($year == $i){?>selected="selected"<?}?> value="<?=$i?>"><?=$i?></option>
            <? } ?>
        </select>
         
        <?  */ ?>
    
     <button type="submit"><?=_e('Показать')?></button>
    </form>
</div>    
    
<!--    <form class="form-horizontal">
        <fieldset>
          <div class="input-prepend">
            <span class="add-on"><i class="icon-calendar"></i></span>
            <input type="text" name="range" id="range" />
          </div>
        </fieldset>
    </form>-->

  <div id="placeholder">
      <div class="ct-chart ct-perfect-fourth"></div>
  </div>
<div>
<?   
        $month = $months[intval( substr($month_year,-2,2) ) ];
        $year = substr($month_year,0,4);
        $values = array_values($bal);
        $last = 0;
        if ( !empty($values))  $last = end($values);
        ?>
    <p><?=_e('new_198')?><?=$month?> <?=$year?> = $<?=round($last,2);?></p>
	<p><?=_e('new_199')?>50%. </p>
	<br/><br/>
	<p><?=_e('new_200')?></p>

</div>
</div>
<link rel="stylesheet" href="/js/chart/chartist.min.css">
<script src="/js/chart/chartist.min.js"></script>

<script>
$(function() {
    
    var data = {
    // A labels array that can contain any sort of values
    labels: <?=json_encode($chart['label'])?>,
    // Our series array that contains series objects or in this case series data arrays
    series: [
      {name: '<?=_e('new_201')?>',data: <?=json_encode($chart["line_bal"])?>}<? unset($chart["line_bal"]); unset($chart["label"]); foreach ($chart as $name => $line) { echo ", {name: '" . _e(config_item('partner-plan-name')[substr($name, 5)]) . "',data: ".json_encode($line)."}"; }?>
    ]
  };
  // As options we currently only set a static size of 300x200 px. We can also omit this and use aspect ratio containers
    // as you saw in the previous example
    var options = {
        width: 640,
        height: 400,
//        showArea: true,
//        showLine: false,
//        showPoint: false,
//        fullWidth: true,
        // Don't draw the line chart points
//        showPoint: false,
        // Disable line smoothing
//        lineSmooth: false,
        // X-Axis specific configuration
//        axisX: {
          // We can disable the grid for this axis
//          showGrid: false,
          // and also don't show the label
//          showLabel: false
//        },
        seriesBarDistance: 15,
        // Y-Axis specific configuration
        axisY: {
          // Lets offset the chart a bit from the labels
          offset: 80,
          // The label interpolation function enables you to modify the values
          // used for the labels on each axis. Here we are converting the
          // values into million pound.
          labelInterpolationFnc: function(value) {
            return '$' + value;
          }
        },
        axisX: {
            labelInterpolationFnc: function(value, index) {
              return value;
//              return index % 2 === 0 ? value : null;
            }
        }
    };
    // Create a new line chart object where as first parameter we pass in a selector
    // that is resolving to our chart container element. The Second parameter
    // is the actual data object. As a third parameter we pass in our custom options.
    new Chartist.Line('.ct-chart', data, options);

    var $chart = $('.ct-chart');

    var $toolTip = $chart
      .append('<div class="tooltip"></div>')
      .find('.tooltip')
      .hide();

    $chart.on('mouseenter', '.ct-point', function() {
      var $point = $(this),
        value = $point.attr('ct:value'),
        seriesName = $point.parent().attr('ct:series-name');
      $toolTip.html(seriesName + '<br>' + "$"+Math.round(value*100)/100).show();
    });

    $chart.on('mouseleave', '.ct-point', function() {
      $toolTip.hide();
    });

    $chart.on('mousemove', function(event) {
      $toolTip.css({
        left: (event.offsetX || event.originalEvent.layerX) - $toolTip.width() / 2 - 10,
        top: (event.offsetY || event.originalEvent.layerY) - $toolTip.height() - 40
      });
    });
});
</script>
</div>
<div id="containern" class="content" style="padding: 20px 0px !important;margin-top: 11px;text-align:center;overflow:hidden;">
<iframe id='ad06e55d' name='ad06e55d' src='https://biggerhost.com/ads/www/delivery/afr.php?zoneid=11&amp;target=_blank&amp;cb={random}&amp;ct0={clickurl_enc}' frameborder='0' scrolling='no' width='728' height='90'><a href='https://biggerhost.com/ads/www/delivery/ck.php?n=a283b9ab&amp;cb={random}' target='_blank'><img src='https://biggerhost.com/ads/www/delivery/avw.php?zoneid=11&amp;cb={random}&amp;n=a283b9ab&amp;ct0={clickurl_enc}' border='0' alt='' /></a></iframe>
</div>