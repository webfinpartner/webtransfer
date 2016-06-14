
var chart_<?=$container?> = AmCharts.makeChart("<?=$container?>", {
    "type": "serial",
    "theme": "light",
    "marginRight": 80,
    "path": "/js/admin/amcharts_3_13_1/",
    "pathToImages": "/js/admin/amcharts_3_13_1/images/",
    
    "autoMarginOffset": 20,
    "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left"
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [
    <?php $i=0; if (!empty($avail_services)) foreach ( $avail_services as $idx=>$service){ $i++; ?>
    {
        "id": "g<?=$i?>",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "green",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "<?=$service?>",
        "useLineColorForBulletBorder": true,
        "valueField": "<?=strtolower($service)?>",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },
    <?php } ?>
    ],
    "legend": {
        "align": "center",
        "equalWidths": false,
        "periodValueText": "Все: [[value.sum]]",
        "valueAlign": "left",
        "valueText": "[[value]])",
        "valueWidth": 100
    },
    "chartCursor": {
        "pan": true,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":0,
        "valueLineAlpha":0.2
    },
    "chartScrollbar": {
        "graph": "g1",
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": true,
        "dashLength": 1,
        "minorGridEnabled": true,
        "position": "top",
        "minPeriod": "<?=($daily)?'DD':'hh'?>"
    },
    "export": {
        "enabled": true
    },
    "numberFormatter": {
        "precision": -1,
        "decimalSeparator": ".",
        "thousandsSeparator": ""
    },    
    "dataProvider": <?=json_encode($chart_data)?>
});

chart_<?=$container?>.addListener("rendered", zoomChart_<?=$container?>());

zoomChart_<?=$container?>();

function zoomChart_<?=$container?>() {
    chart_<?=$container?>.zoomToIndexes(chart_<?=$container?>.dataProvider.length - 40, chart_<?=$container?>.dataProvider.length - 1);
}

