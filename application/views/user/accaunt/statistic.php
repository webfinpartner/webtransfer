<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
		.zifra {font-size: 32px; line-height: 30px; color: rgb(99, 124, 151);}
		.odd {padding:10px 5px;margin-bottom:5px;}
		.left {float:left;font-size:12px;}
		.right {float:right;font-size:12px;}
		</style>


<div style="width:191px;border: 1px solid rgb(204, 204, 204);padding-bottom:10px;">
<div style="padding:10px;font-weight:bold;text-align:center;"><?=_e('accaunt/statistic_1')?><?=date_formate_view(date('Y-m-d'))?></div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_2')?></span>
<span class="right"><?=price_format_view($today->public_deals)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_3')?></span>
<span class="right">$ <?=price_format_view($today->public_volume)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_4')?></span>
<span class="right"><?=$today->avg_period?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_5')?></span>
<span class="right">$ <?=price_format_view($today->avg_summa)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_6')?></span>
<span class="right"><?=$today->avg_rate?>%</span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_7')?></span>
<span class="right"><?=price_format_view($today->new_users)?></span>
</div>
</div>
<br/>

<div style="width:191px;border: 1px solid rgb(204, 204, 204);padding-bottom:10px;">
<div style="padding:10px;font-weight:bold;text-align:center;"><?=_e('accaunt/statistic_1')?> <?=date_formate_view($yesterday_date)?></div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_2')?></span>
<span class="right"><?=price_format_view($yesterday->public_deals)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_3')?></span>
<span class="right">$ <?=price_format_view($yesterday->public_volume)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_4')?></span>
<span class="right"><?=$yesterday->avg_period?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_5')?></span>
<span class="right">$ <?=price_format_view($yesterday->avg_summa)?></span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_6')?></span>
<span class="right"><?=$yesterday->avg_rate?>%</span>
</div>
<div class="odd">
<span class="left"><?=_e('accaunt/statistic_7')?></span>
<span class="right"><?=price_format_view($yesterday->new_users)?></span>
</div>
</div>