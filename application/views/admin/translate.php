<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="widget">
<div class="title"><img src="images/icons/dark/full2.png" alt="" class="titleIcon" /><h6>Поиск непереведенного текста</h6></div>                          
<form method="post" id="translate_form">
    <input type="hidden" name="submited" value="1">
    <textarea style="width:100%; height: 400px"><?=$text?></textarea>
    
    <br>
    <div style="text-align: center">
        <a class="wButton greenwB ml15 m10" onclick="$('#translate_form').submit(); return (false)" title="" href="#"><span>Найти</span></a>
    </div>
</form>
</div>