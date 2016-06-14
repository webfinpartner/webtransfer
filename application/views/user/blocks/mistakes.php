<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<TITLE><?_e('Отправить ошибку')?></TITLE>
<style type="text/css">
body {
margin: 20px 25px;
font-size:14px;
font-family:Helvetica, Sans-serif, Arial;
line-height:2em;
}
form
{margin: 0;}
.text {
font-weight: bold;
font-size:12px;
color:#777;
}
.copyright
{
font-size:11px;
color:#777;
}

div#mistake_div input {
    width: 100%;
    padding: 11px;
    border: 1px solid #D8D8D8;
}

#mistake {
    z-index: 9 !important;
}

div#mistake_div .text {
    font-size: 20px !important;
    color: #3C3C3C !important;
    margin-top: 14px !important;
    margin-bottom: -20px !important;
    font-weight: 500 !important;
    display: block;
}

#mistake textarea {
    width: 100% !important;
}


textarea {
    width: 100%;
    max-width: 100%;
    padding: 8px 11px;
    border-color: #D8D8D8;
    font-size: 12px;
}

.send-wt-frame{
	width: 45%;
	float: left;
	background:#FF5100;
	color: #fff;
}

.send-wt-frame {
    width: 45% !important;
    float: left;
    background: #FF5100;
    color: #fff;
    font-size: 15px;
    border: 0 !important;
    margin-top: 15px;
        cursor: pointer;
}

.send-wt-frame:hover, .send-wt-frame2:hover{
	opacity: 0.8;
}

.send-wt-frame2 {
    width: 45% !important;
    float: right;
    background: #007AFF;
    color: #fff;
    font-size: 15px;
    border: 0 !important;
    margin-top: 15px;
    cursor: pointer;
}


#m_window {
    position: fixed !important;
    background: #fff;
    border: 1px solid #DADADA !important;
    overflow: auto;
    width: 350px;
    height: 480px;
    z-index: 0;
    border-radius: 0;
    top: 50% !important;
    -webkit-box-shadow: 0 5px 15px rgba(0,0,0,.5) !important;
    box-shadow: 0 5px 15px rgba(0,0,0,.5) !important;
    margin-top: -200px !important;
}
</style>

<script type="text/javascript" src="/msgs/src/js/jquery-2.2.0.min.js"></script>        
<script language="JavaScript"> 
var p=parent;
var site_url = "<?= site_url('/') ?>";
function readtxt()
{
    if(p!=null) $('#mistake_div [name=url]').val( p.loc );
    if(p!=null) $('#mistake_div [name=mis]').val( p.mis ); 
}

function hide()
{
    var win=p.document.getElementById('mistake');
    win.parentNode.removeChild(win);
}


function send(){
        var url = $('#mistake_div [name=url]').val();
        var mis = $('#mistake_div [name=mis]').val();
        var comment = $('#mistake_div [name=comment]').val();
        $('#mistake_div [name=submit]').prop('disabled', true);
        $('#mistake_div .loading-gif').show();
        $('#mistake_div #message').hide();
        $.post(site_url + '/account/mistakes', { url: url, mis: mis, comment: comment },
               function(data) {
                        $('#mistake_div .loading-gif').hide();
                        $('#mistake_div #message').show();
                        if ( data.status )
                            $('#mistake_div #message').html('Спасибо! Ваше сообщение успешно отправлено.');
                        else {
                            $('#mistake_div [name=submit]').prop('disabled', false);
                            $('#mistake_div #message').html('К сожалению ваше сообщение не удалось отправить. Попробуйте еще раз');
                        }
                       
               }, 'json');                
           }
           
           
</script>

</head>
<body onload=readtxt()>
<div id="mistake_div">
    <span class="text"><?=_e('Адрес страницы:')?></span>
    <br /> 
    <input type="text" name="url" size="30" readonly="readonly">
      <br />
      <span class="text"><?=_e('Ошибка:')?></span>
      <br /> 
      <textarea rows="5" name="mis" cols="30" readonly="readonly"></textarea> 
      <br />
      <span class="text"><?=_e('Комментарий:')?></span>
      <br /> 
      <textarea rows="5" name="comment" cols="30"></textarea> 
      <div style="margin-top: 7px">
        <input class="send-wt-frame" onclick="send()" type="submit" value="<?=_e('Отправить')?>" name="submit">
        <input class="send-wt-frame2" onclick="hide()" type="button" value="<?=_e('Отмена')?>" id="close" name="close"> 
      </div>

      <center>
<p class="center"><img class='loading-gif' style="display: none" src="/images/loading.gif"/></p>
<p class="center error" id="message" style="display: none;"></p>
</center> 



</div>   
</body></html>
