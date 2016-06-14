<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="container" class="content" style="padding-bottom:20px;margin-bottom:20px;">
<h1 class="title"><?=_e('Моя стена')?></h1> 

<div id="mc-container"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Comment', id: 33232, lang: '<?=_e("lang")?>' , channel: <?=$user_data->id_user?>});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>
</div>
