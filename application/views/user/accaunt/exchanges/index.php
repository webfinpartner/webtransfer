    <div class="inner">
<h1 class="title"><?=$exchange_item->title?></h1><Br/>
<a href="<?= $exchange_item->source_url ?>" target="_blank"><?= $exchange_item->source_url ?></a>

        <div class="content fright" style="margin-top:10px">


            <div class="news_full" style="display: inline-block;width:100%">

                <div class="new_content">
                    <? if (!empty($exchange_item->foto)) { ?>    <div class="image" style="margin-top:0px;"><img src="/upload/exchanges/<?= $exchange_item->foto ?>" alt="" style="width:100px;" /></div><? } ?> 
					<p><?=$exchange_item->description?></p>
                </div>
				<center>
				<a href="<?= $exchange_item->source_url ?>" target="_blank" class="button long"><?=_e('перейти на сайт')?></a>
				</center>
            </div>


        </div>

     <? if($this->lang->lang() == 'ru'): ?>
        <span id="coment"></span><br/>
        <div id="disqus_thread">&nbsp;</div>
        <script type="text/javascript">// <![CDATA[
            /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
            var disqus_shortname = 'webtransfer'; // required: replace example with your forum shortname
            var disqus_url = 'https://webtransfer-finance.com/ru/account/exchanges-list/<?=$exchange_item->url?>';
            var disqus_title = '<?=$exchange_item->title?>';
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
            var disqus_url = 'https://webtransfer-finance.com/en/account/exchanges-list/<?=$exchange_item->url?>';
            var disqus_title = '<?=$exchange_item->title?>';
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
