<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="container" class="content">
    <style>
        #___plusone_0 {
            width:60px !important;
        }
        .twitter-tweet-button {
            width:80px !important;
        }
    </style>
    <div style="text-align:right;margin-top:10px;width:100%;overflow:hidden;">
        <span style="position: relative;top: -7px;right: 10px;"><?=_e('novost_share')?></span>
        <script type="text/javascript">(function() {
                if (window.pluso)
                    if (typeof window.pluso.start == "function")
                        return;
                if (window.ifpluso == undefined) {
                    window.ifpluso = 1;
                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })();</script>
        <div class="pluso" data-background="none;" data-options="small,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir,email,print"></div>
    </div>

    <div class="inner">
        <ul class="breadcrumbs"style="text-indent:-3px">
            <li><a href=""><?=_e('novost_home')?> / </a></li>
            
            <li><a href="<?= create_link($page_novosti->url) ?>"> <?=_e('novost_blog')?> / </a></li> <li class="active" ></li>

        </ul>

        <div class="content fright" style="margin-top:20px">


            <div class="news_full" style="display: inline-block;width:100%">

                <div class="new_content">
                    <div class="title"><?= $new->title ?></div>
                    <div class="date"><?= $new->data ?></div>

                    <? if (!empty($new->foto)) { ?>    <div class="image"><div class="play"></div><a rel="fancybox"><img src="/upload/news/<?= $new->foto ?>" alt="" style="width:200px;" /></a></div><? } ?> <p><?= $new->text ?></p>
                </div>
            </div>


        </div>
<br/>
	<hr/>
	<span style="font-size:11px;color:#999999"><?=_e('Если вы заметили ошибку или опечатку в тексте, выделите ее курсором и нажмите Ctrl + Enter')?>
	<br/>
     <? if($this->lang->lang() == 'ru'): ?>
        <span id="coment"></span><br/>
        <div id="disqus_thread">&nbsp;</div>
        <script type="text/javascript">// <![CDATA[
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'webtransfer'; // required: replace example with your forum shortname
            var disqus_url = 'https://webtransfer-finance.com/ru/news/<?= $new->url ?>';
            var disqus_title = '<?= str_replace("'", " ", $new->title) ?>';
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script');
                dsq.type = 'text/javascript';
                dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
            // ]]></script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <p><a class="dsq-brlink" href="http://disqus.com">comments powered by <span class="logo-disqus">Disqus</span></a></p>
    <? endif; ?>
	
	  <? if($this->lang->lang() == 'en'): ?>
        <span id="coment"></span><br/>
        <div id="disqus_thread">&nbsp;</div>
        <script type="text/javascript">// <![CDATA[
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'webtransfer-finance'; // required: replace example with your forum shortname
            var disqus_url = 'https://webtransfer-finance.com/en/news/<?= $new->url ?>';
            var disqus_title = '<?= str_replace("'", " ", $new->title) ?>';
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script');
                dsq.type = 'text/javascript';
                dsq.async = true;
                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
            // ]]></script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <p><a class="dsq-brlink" href="http://disqus.com">comments powered by <span class="logo-disqus">Disqus</span></a></p>
    <? endif; ?>
    </div>
</div>