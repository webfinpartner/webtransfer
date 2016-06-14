<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="container" class="content" style="padding-bottom:20px;margin-bottom:20px;">
<h1 class="title"><?=_e("reviews/title")?></h1> 

<div id="mc-review"></div>
<script type="text/javascript">
cackle_widget = window.cackle_widget || [];
cackle_widget.push({widget: 'Review', id: 33231, lang: '<?=_e("lang")?>', channel: <?=$user_data->id_user?>,
msg: {
        recom: '<?=_e("reviews/recom")?>',
		formhead: '<?=_e("reviews/formhead")?>',
		yRecom: '<?=_e("reviews/yrecom")?>',
		nRecom: '<?=_e("reviews/nrecom")?>',
		vb: '<?=_e("reviews/vb")?>',
		vbtitle: '<?=_e("reviews/vbtitle")?>',
    }

});
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();
</script>
</div>


