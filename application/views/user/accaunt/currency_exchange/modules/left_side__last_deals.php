<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
    /* lastobmen widget */
    .lobmen_widget{
        width: 161px;
        height: 810px;
        /*margin: 0 0 30px 0;*/
        background: #fff;
        border-radius: 4px;
        /*box-shadow: 0 2px 4px #c4c2c2;*/
        padding: 20px 15px 15px;
        margin-top: 10px
    }
    .lobmenwidget_title{
        padding: 0px 0 20px;
        margin: 0 -5px;
        font: 600 18px/20px 'Open Sans';
        color: #929292;
        text-align: center;
        border-bottom: 1px solid #ebebeb;
    }
    .lobmline{
        float: left;
        width: 75px;
    }
    .lobmlinebac{
        color: #545454;
        padding: 5px 0 0 0;
        font: 100 12px 'Open Sans';
        text-align: center;
    }
    .lobmlinepr{
        float: left;
        width: 20px;
        height: 50px;
        
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAALCAYAAABCm8wlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6ODEzQzEwMDI3NDlGMTFFNDk1NDVENzYyMkU1ODI4NkUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6ODEzQzEwMDM3NDlGMTFFNDk1NDVENzYyMkU1ODI4NkUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4MTNDMTAwMDc0OUYxMUU0OTU0NUQ3NjIyRTU4Mjg2RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MTNDMTAwMTc0OUYxMUU0OTU0NUQ3NjIyRTU4Mjg2RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhbiExoAAACgSURBVHjabM+xCoJQFMbxazb1AuXUGDS5KzaEQ2PU5BoIPkXv0Gv0AGlDQSE4OAZNRWNzg7RE/Q8oxMEPfss937nca33XcWKMibHA3ah0cMYAOdy2wgU+Khwx0QXJDR4eSDHXBcmz3i6wxUoXJC9EeGMDWxf62MHCEp/u33CIDA5mOMlhUxhhjx6mKJstKYxxkOsQ4Kq/GdaP8/VQ8hNgAO9XHQUE6vj1AAAAAElFTkSuQmCC');
        background-repeat: no-repeat;
        background-position: 10px 30px;
    }
    .lobmentable{
        background: #f5f5f5;
        padding: 10px 10px 15px;
        margin: -1px -15px 0px;
    }
    
    .lobmendate{
        color: #929292;
        font: 100 14px 'Open Sans';
        margin: 13px 0 13px;
    }
    .obmenlinew{display:block; padding: 0 0 0 10px; margin:0 0 0 0;overflow:hidden;text-decoration:none;}
    .obmenlinewico{
        height: 43px; width: 43px; overflow:hidden; margin: auto;        
    }
    
    .obmenlinewtext{color:#545454; float:left; width: 90px; padding: 10px 0 0 0;font: 100 14px 'Open Sans';}
    
    .lobmlineico-wrap
    {
        position: relative;
        height: 40px;
        
    }
    .lobmenwidget_line{
        width: 161px;
        
        position: absolute;
        left: 15px;
    }
    .lobmenwidget_wrapper{
        position: relative;
        
        width: 191px;
        overflow: hidden;
        margin-left: -15px;
        top: -1px;
    }
    .anim {
        position: absolute;
        width: 80px;
        height: 80px;
        
        -webkit-animation:spin 10s ease-in- infinite;
        -moz-animation:spin 10s ease-in-out infinite;
        animation:spin 10s ease-in-out infinite;
    }
    .anim2 {
        -webkit-animation:spin2 10s ease-in- infinite;
        -moz-animation:spin2 10s ease-in-out infinite;
        animation:spin2 10s ease-in-out infinite;
    }
    .curency_id_heart_red{
        color: red;
    }
    @-moz-keyframes spin { 
        0%      {-moz-transform: perspective(400px) rotateY(360deg);}
        25%     {-moz-transform: perspective(400px) rotateY(0deg);}
        50%     {-moz-transform: perspective(400px) rotateY(360deg);}        
        100%    {-moz-transform: perspective(400px) rotateY(360deg);}        
    }
    @-moz-keyframes spin2 { 
        0%      {-moz-transform: perspective(400px) rotateY(360deg);}
        50%     {-moz-transform: perspective(400px) rotateY(360deg);}
        75%     {-moz-transform: perspective(400px) rotateY(0deg);}
        100%     {-moz-transform: perspective(400px) rotateY(360deg);}        
    }
    @-webkit-keyframes spin { 
        0%      {-webkit-transform: perspective(400px) rotateY(360deg);}
        25%     {-webkit-transform: perspective(400px) rotateY(0deg);}
        50%     {-webkit-transform: perspective(400px) rotateY(360deg);}        
        100%    {-webkit-transform: perspective(400px) rotateY(360deg);}        
    }
    @-webkit-keyframes spin2 { 
        0%      {-webkit-transform: perspective(400px) rotateY(360deg);}
        50%     {-webkit-transform: perspective(400px) rotateY(360deg);}
        750%     {-webkit-transform: perspective(400px) rotateY(0deg);}
        100%    {-webkit-transform: perspective(400px) rotateY(360deg);}        
    }
    
    @keyframes spin { 
        0% {transform: perspective(400px) rotateY(360deg);}
        25% {transform: perspective(400px) rotateY(0deg);}
        50% {transform: perspective(400px) rotateY(360deg);}        
        100% {transform: perspective(400px) rotateY(360deg);}        
    }
    @keyframes spin2 { 
        0% {transform: perspective(400px) rotateY(360deg);}
        50% {transform: perspective(400px) rotateY(360deg);}
        75% {transform: perspective(400px) rotateY(0deg);}
        100% {transform: perspective(400px) rotateY(360deg);}        
    }
    

    /* end lastobmenwidget */

</style>
<script>
    if( !mn ){
        var mn = {};
        mn.site_lang = '<?=_e('lang')?>';
    }
    navigator.sayswho= (function(){
        var ua= navigator.userAgent, tem,
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
        if(/trident/i.test(M[1])){
            tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
            return 'IE '+(tem[1] || '');
        }
        if(M[1]=== 'Chrome'){
            tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
            if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
        }
        M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
        if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
        return M.join(' ');
    })();
    
    mn.ws = {
        conection: null,
        url: "ws://146.185.171.249:7002",
        on_open:function(){
            mn.ws.conection.send('New user '+userid+' '+site_url+' '+navigator.sayswho);
            console.log("WSS:Message is sent...");
        },
        on_message:function(evt){            
            var json = evt.data;

            mn.ws.conection.send('OK '+userid+' '+json);
            var data = JSON.parse(json);

            //alert("Message is received...");
            console.log("WSS:Message is received...", data);
        },
        on_close:function(){
            console.log("WSS:Connection is closed...");
        },
        init: function()
        {
            console.log('WSS:init');
            if (!("WebSocket" in window))
            {
                alert("WebSocket NOT supported by your Browser!");
                return false;
            }

            // Let us open a web socket
            this.conection = new WebSocket(mn.ws.url);
            if( !mn.ws.conection ){
                console.log('WSS: connection fails to establish');
                return false;
            }
                
            mn.ws.conection.onopen = mn.ws.on_open;
            mn.ws.conection.onmessage = mn.ws.on_message;
            mn.ws.conection.onclose = mn.ws.on_close;
            
            console.log('WSS:init ends');
        }
    };
    mn.left_side_last_deals = {
        last_dt: '<?=$last_deals->last_dt?>',
        count: '<?=$last_deals->count?>',
        interval: '<?=$last_deals->interval?>',
        uri: '<?=$last_deals->uri?>',
        max_count: 0,
        line: null,
        overload: false,
        show_one_deal:function(deals, i, res){            
            if( mn.left_side_last_deals.last_dt == res['last_dt'] ) return false;
            
            if( deals[i] === undefined ) return false;
            
            var d = deals[i];
            console.log( d['datetime'] +'<=' + mn.left_side_last_deals.last_dt,  d['datetime'] <= mn.left_side_last_deals.last_dt );
            if( d['datetime'] <= mn.left_side_last_deals.last_dt ) return false;
            
            
            mn.left_side_last_deals.
                    line.prepend( JSON.parse(d['data']) );                

            mn.left_side_last_deals.
                line.css('top',-150)
                    .animate({top:0},600,function(){
                        setTimeout(function(){
                            mn.left_side_last_deals.show_one_deal(deals, i+1, res);
                            
                            mn.left_side_last_deals.last_dt = res['last_dt'];
                            mn.left_side_last_deals.interval = res['interval'];
                            
                        },600);
                    });
                
            
        },
        show_deals:function(res){
            console.log('show_deals',mn.left_side_last_deals.max_count);
            var deals = res['success'];
            
            var lines = mn.left_side_last_deals.
                                    line.find('.lobmentable_one');
            var rm_el = ( lines.length - mn.left_side_last_deals.max_count );

            if( mn.left_side_last_deals.overload )
            {
                mn.left_side_last_deals.overload = false;
                mn.left_side_last_deals.last_dt = 0;
                mn.left_side_last_deals.line.html('');
                for( var i = 0; i < res['count']; i++ )
                    mn.left_side_last_deals.line.append( JSON.parse(deals[i]['data']) );
                mn.left_side_last_deals.resize();
                return false;
            }

            for( var i = 0, d = []; i < res['count']; i++ )
            {
                d = deals[i];

                if( d['datetime'] <= mn.left_side_last_deals.last_dt ) continue;
                
                mn.left_side_last_deals.show_one_deal(deals, i, res);
                break;
            }
            
        },
        loop_call_back:function(res){
            if( !res['success'] || res['success'] == 'empty' ) return false;
            
            mn.left_side_last_deals.uri = res.uri;
            
            mn.left_side_last_deals.show_deals( res  );            
        },
        loop_fnc:function(){
            console.log( 'left_side_last_deals--loop_fnc' );
            var data = { dt: mn.left_side_last_deals.last_dt, count: this.count };
            
            console.log( 'data', data );
            
            $.ajax({
                method: "POST",
                type: "POST",
                url: '/'+mn.site_lang+mn.left_side_last_deals.uri,
                data: data,
                dataType:'json'
              }).success( mn.left_side_last_deals.loop_call_back );
        },
        resize:function(){
            console.log('left_side:resize');
            var ch = $('#container').outerHeight();
            if( $('.pager').length == 0 )
                ch -= 35;
            var widget_h = 130;
            var statistic_module = 0;
            var title_h = 48;
            
            
            var footer_h = 19;
            
            var st = $('.statistic_module');
            
            if( st.length > 0 )
            {
                statistic_module += st.outerHeight() *  st.length ;
            }
            var lobmen_widget_h = ch - statistic_module;            
            var lobmenwidget_wrapper_h = lobmen_widget_h - title_h;
            
            var widget_count = Math.floor( lobmenwidget_wrapper_h / widget_h);
            
            if( widget_count > mn.left_side_last_deals.count ){                
                mn.left_side_last_deals.reload( widget_count, true );
                return false;
            }
            
            var widget_fit_h = widget_count * widget_h;
            
            
            var widget_footer_margin_t = (lobmenwidget_wrapper_h - widget_fit_h)/( widget_count * 2 );
            
            if( widget_footer_margin_t < 0  ) widget_footer_margin_t = 0;
            widget_footer_margin_t += 4;
            $('.lobmendate').css( 'margin', widget_footer_margin_t + 'px 0 ' );
            
            
            $('.lobmen_widget').animate({height: lobmen_widget_h},600);
            $('.lobmenwidget_wrapper').animate({height: lobmenwidget_wrapper_h},600);
            
            
            console.log('ch',ch,'lobmen_widget_h',lobmen_widget_h, 'widget_fit_h', widget_fit_h,'widget_count',widget_count);
            
        },
        
        reload:function(count, overload){
            console.log('reload',count, overload);
            if( this.timer_handle !== null ) clearInterval( this.timer_handle );
            if( count !== undefined ) this.count = count;
            if( overload !== undefined ){
                this.overload = overload;
                mn.left_side_last_deals.loop_fnc();
            }
            
            console.log('reload',this.count, this.overload);
            
            
            this.timer_handle = setInterval(function(){
                mn.left_side_last_deals.loop_fnc();
            }, mn.left_side_last_deals.interval );
        },
                
        
        init:function(){            
            mn.left_side_last_deals.line =   $('.lobmenwidget_line');            
            this.reload(10);
            this.resize();
            //mn.ws.init();
        }
    };
    $(function(){
        mn.left_side_last_deals.init();    
    });
    $( document ).ready(function(){
        setTimeout(function(){
            mn.left_side_last_deals.resize();
        },10000);
        
    });    
</script>
<div class="lobmen_widget">
    <div class="lobmenwidget_title"><?= _e('Последние P2P сделки')?></div>
    <div class="lobmenwidget_wrapper">        
        <div class="lobmenwidget_line">        
            <?php 
            $ci = count( $last_deals->success );
            for( $i = $ci-1; $i >= 0; $data = json_decode( $last_deals->success[$i--]->data ) ): ?>        
            <?php echo $data; ?>
            <?php endfor; ?>
        </div>
    </div>
</div>

