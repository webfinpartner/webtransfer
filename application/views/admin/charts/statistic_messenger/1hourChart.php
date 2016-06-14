
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
    "graphs": [{
        "id": "g1",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "green",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_success",
        "useLineColorForBulletBorder": true,
        "valueField": "status_success_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g7",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_success_check_cnt",
        "useLineColorForBulletBorder": true,
        "valueField": "status_success_check_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g2",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "orange",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_pending",
        "useLineColorForBulletBorder": true,
        "valueField": "status_pending_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g3",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "yellow",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_fail",
        "useLineColorForBulletBorder": true,
        "valueField": "status_fail_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g6",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_fail_check_cnt",
        "useLineColorForBulletBorder": true,
        "valueField": "status_fail_check_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g4",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_another",
        "useLineColorForBulletBorder": true,
        "valueField": "status_another_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    },{
        "id": "g5",
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "bulletColor": "#FFFFFF",
        "bulletSize": 5,
        "hideBulletsCount": 50,
        "lineThickness": 2,
        "type":"smoothedLine",
        "title": "status_another_check_cnt",
        "useLineColorForBulletBorder": true,
        "valueField": "status_another_check_cnt",
        "balloonText": "<div style='margin:5px; font-size:19px;'><span style='font-size:13px;'>[[category]] [[time]]</span><br>[[value]]</div>"
    }],
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
        "minPeriod": "hh"
    },
    "export": {
        "enabled": true
    },
    "dataProvider": <?=json_encode($chart_data)?>
});

chart_<?=$container?>.addListener("rendered", zoomChart_<?=$container?>());

zoomChart_<?=$container?>();

function zoomChart_<?=$container?>() {
    chart_<?=$container?>.zoomToIndexes(chart_<?=$container?>.dataProvider.length - 40, chart_<?=$container?>.dataProvider.length - 1);
}

