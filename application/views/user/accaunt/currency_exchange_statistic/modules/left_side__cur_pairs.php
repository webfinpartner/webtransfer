<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>


<style>
    .zifra {font-size: 32px; line-height: 30px; color: rgb(99, 124, 151);}
    .odd {padding:10px 5px;margin-bottom:5px;}
    .left {float:left;font-size:12px;}
    .right {float:right;font-size:12px;}


    .cur_pairs_module
    {
        width:191px;
        height: 339px;
        margin-top: 10px;
        border-radius: 4px;
        position: relative;
        background-color: white;
    }
    .cur_pairs_module>.header
    {
        top: 0;
        left: 0;
        position: absolute;
        height: 40px;
        width: 191px;
        z-index: 999;
    }
    .cur_pairs_wrapper{
        padding-top:10px;
        padding-bottom:10px;
        width: 191px;
        height: 280px;
        overflow: hidden;
        position: relative;
    }

    .cur_pairs_line{
        position: absolute;
        left: 0px;
        width: 450px;
    }

    .cur_pairs_window{
        width: 181px;
        border: 1px solid transparent;
        height:280px;
        padding: 5px;
        padding-top: 0;

        float: left;
    }
    .cur_pairs_window .title{
        padding:0 10px 10px;

        font-weight:bold;
        text-align:center;
    }

    .carousel-control{
        position: absolute;
        top: 35px;
        left: 5px;
        width: 25px;
        height: 25px;
        margin-top: -20px;
        font-size: 40px;
        font-weight: 100;
        line-height: 15px;
        color: #fff;
        text-align: center;
        /*background: #222;*/
        border: 1px solid #ccc;
        -webkit-border-radius: 23px;
        border-radius: 23px;
        opacity: .5;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        filter: alpha(opacity=50);

        color: #999;
            /*text-shadow: 0 1px 2px rgba(0, 0, 0, .6);*/
            cursor: pointer;

        box-shadow: inset 0 0 5px 1px #ccc;

        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .carousel-control.left{

/*        background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, .5) 0, rgba(0, 0, 0, .0001) 100%);
        background-image: -o-linear-gradient(left, rgba(0, 0, 0, .5) 0, rgba(0, 0, 0, .0001) 100%);
        background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, .5)), to(rgba(0, 0, 0, .0001)));
        background-image: linear-gradient(to right, rgba(0, 0, 0, .5) 0, rgba(0, 0, 0, .0001) 100%);*/
        filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#80000000', endColorstr='#00000000', GradientType=1);
        background-repeat: repeat-x;
    }
    .carousel-control.right{
        left: auto;
        right: 5px;
    }
    .cur_pairs__left{
        position: relative;
        float: left;
        width: 85px;
        overflow: visible;
    }
    .cur_pairs__left:after{
        position: absolute;
        right: -12px;
        content: '';

        top: 13px;
        display: block;
        width: 8px;
        height: 11px;
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAALCAYAAABCm8wlAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6ODEzQzEwMDI3NDlGMTFFNDk1NDVENzYyMkU1ODI4NkUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6ODEzQzEwMDM3NDlGMTFFNDk1NDVENzYyMkU1ODI4NkUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4MTNDMTAwMDc0OUYxMUU0OTU0NUQ3NjIyRTU4Mjg2RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4MTNDMTAwMTc0OUYxMUU0OTU0NUQ3NjIyRTU4Mjg2RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PhbiExoAAACgSURBVHjabM+xCoJQFMbxazb1AuXUGDS5KzaEQ2PU5BoIPkXv0Gv0AGlDQSE4OAZNRWNzg7RE/Q8oxMEPfss937nca33XcWKMibHA3ah0cMYAOdy2wgU+Khwx0QXJDR4eSDHXBcmz3i6wxUoXJC9EeGMDWxf62MHCEp/u33CIDA5mOMlhUxhhjx6mKJstKYxxkOsQ4Kq/GdaP8/VQ8hNgAO9XHQUE6vj1AAAAAElFTkSuQmCC');
        background-repeat: no-repeat;

    }
    .cur_pairs__odd{
        clear: both;

        margin-bottom: 5px;
        display: inline-block;
        width: 100%;
        padding: 0;

        color: #545454;

        font: 100 13px 'Open Sans';
        text-align: center;
    }
    .cur_pairs__right{
        float: right;
        width: 85px;
    }
    .cur_pairs_window .cur_name,
    .cur_pairs_window .cur_sum{
        text-align: center;
    }
</style>
<?php /*
<script>
    if( mn === undefined ) var mn = {};

    mn.cur_pairs_module = {
        handle: null,
        line: null,
        curr_window: 0,
        window_w: 0,
        left_pos: 0,
        line_w: 0,
        next_pos: 0,
        last_p: 1,
        prev_button: {
            handle: null,
            show: function(){
                this.handle.fadeIn();
            },
            hide: function(){
                this.handle.fadeOut();
            },
        },
        next_button: {
            handle: null,
            show: function(){
                this.handle.fadeIn(300);
            },
            hide: function(){
                this.handle.fadeOut(300);
            },
        },
        show_next: function(){
            this.last_p = 1;

            this.next_pos = this.left_pos - this.window_w;
            if( Math.abs( this.next_pos) > this.line_w - this.window_w ) return false;

            this.left_pos = this.next_pos;

            this.show_cur_window();
            this.prev_button.show();

            this.next_pos = this.left_pos - this.window_w;
            if( Math.abs( this.next_pos) > this.line_w - this.window_w ) this.next_button.hide();

            return false;
        },
        show_prev: function(){
            this.last_p = 0;

            this.next_pos = this.left_pos + this.window_w;
            if( this.next_pos >= this.line_w - this.window_w ) return false;

            this.left_pos = this.next_pos;
            this.show_cur_window();
            this.next_button.show();

            this.next_pos = this.left_pos + this.window_w;
            if( this.next_pos >= this.line_w - this.window_w )  this.prev_button.hide();


            return false;
        },
        show_cur_window: function(){
            this.line.animate({left: this.left_pos}, 400);
            return false;
        },
        corusel:function(time){

            setInterval(function(){
                if( mn.cur_pairs_module.last_p == 1 ) mn.cur_pairs_module.show_prev();
                else
                    mn.cur_pairs_module.show_next();
            },time);
        },
        init: function( handle ){

            if( handle === undefined ) return false;

            this.handle = $(handle);
            this.line = this.handle.find('.cur_pairs_line');
            this.curr_window = 0;

            this.window_w = this.handle.find('.cur_pairs_window').outerWidth();
            this.window_c = this.handle.find('.cur_pairs_window').length;

            this.next_button = this.handle.find('.carousel-control.right');
            this.prev_button = this.handle.find('.carousel-control.left');

            this.next_button.hide();

            this.line_w = this.window_w * this.window_c;
            this.line.width( this.line_w );

            this.left_pos -= this.window_w;
            this.show_cur_window();
            this.corusel(15000);
        }
    };
    $(function(){
        mn.cur_pairs_module.init('.cur_pairs_module');
    });

</script>
<div class="cur_pairs_module" style="height:305px;">
    <div class="header">
        <a onclick="mn.cur_pairs_module.show_prev();" class="carousel-control left">‹</a>
        <a onclick="mn.cur_pairs_module.show_next();" class="carousel-control right">›</a>
    </div>
    <div class="cur_pairs_wrapper">
        <div class="cur_pairs_line">
            <?php foreach( $cur_pairs as $pairs ): ?>
            <div class="cur_pairs_window">
                <div class="header">
                    <div class="title"><?= _e('Курсы валют на') ?><br/> <?= date_formate_view(date("Y-m-d", time())) ?></div>
                </div>
                <?php
                    $c = count( $pairs );
                    if( $c > 7 ) $c = 7;
                    $p = 0;
                    for( $i = 0; $i < $c; $p = $pairs[$i],$i++, $left_ps = $p['left']['payment_system_id'] ):
                    if( empty( $p ) ) continue;
                    ?>
                    <div class="cur_pairs__odd">
                        <div class="cur_pairs__left">
                            <?if( $left_ps == 116 ):?>
                                <div class="cur_name">WT DEBIT</div>
                            <?elseif( $left_ps != 113 ): ?>
                                <div class="cur_name"><?= $p['left']['human_name']  ?></div>
                            <?endif;?>

                            <?if( $left_ps == 113 ): ?>
                                <div class="cur_sum" style="margin-top: 10px;">1 <?= _e('currency_id_'.$p['left']['currency_id'])  ?></div>
                            <?else:?>
                                <div class="cur_sum">1 <?= _e('currency_id_'.$p['left']['currency_id'])  ?></div>
                            <?endif;?>
                        </div>
                        <div class="cur_pairs__right">
                            <?if( $p['right']['payment_system_id'] == 115 ):?>
                                <div class="cur_name">WT VISA</div>
                            <?else:?>
                                <div class="cur_name"><?= $p['right']['human_name']  ?></div>
                            <?endif;?>
                            <div class="cur_sum"><?= $p['rate'] ?> <?= _e('currency_id_'.$p['right']['currency_id'])  ?></div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

*/