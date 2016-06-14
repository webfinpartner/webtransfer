<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style>
    /* lastobmen widget */
    .lobmen_widget{
        width: 161px;
        /*margin: 0 0 30px 0;*/
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 4px #c4c2c2;
        padding: 20px 15px 15px;
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
        margin: -1px -15px 15px;
    }
    .lobmendate{
        color: #929292;
        font: 100 14px 'Open Sans';
    }
    .obmenlinew{display:block; padding: 0 0 0 10px; margin:0 0 0 0;overflow:hidden;text-decoration:none;}
    .obmenlinewico{
        height: 40px; width: 40px; overflow:hidden; margin: auto;        
    }
    
    .obmenlinewtext{color:#545454; float:left; width: 90px; padding: 10px 0 0 0;font: 100 14px 'Open Sans';}
    
    .lobmlineico-wrap
    {
        position: relative;
        height: 40px;
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

<div class="lobmen_widget">
    <div class="lobmenwidget_title"><?= _e('Последний обмен')?></div>
    <div class="lobmentable">
        <div class="lobmline">
            <div class="lobmlineico-wrap">
                <div class="lobmlineico anim">
                    <div class="obmenlinewico" style="<?= $last_deal['from']['bg'] ?>"></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="lobmlinebac">
                <?= $last_deal['from']['name'] ?><br><?= $last_deal['from']['amount'] ?> <?= $last_deal['from']['currency_name'] ?>
            </div>
            <div class="clear"></div>
        </div>

        <div class="lobmlinepr"></div>

        <div class="lobmline" style="float: right;">
            <div class="lobmlineico-wrap">
                <div class="lobmlineico anim anim2">
                    <div class="obmenlinewico" style="<?= $last_deal['to']['bg'] ?>"></div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="lobmlinebac">                
                <?= $last_deal['to']['name'] ?><br><?= $last_deal['to']['amount'] ?> <?= $last_deal['to']['currency_name'] ?>                
            </div>
            <div class="clear"></div>
        </div>	
        <div class="clear"></div>
    </div>
    <div class="lobmendate"><?= $last_deal['date'] ?><div style="float:right;"><?= $last_deal['time']?></div></div>
</div>

