<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? if(isset($banner)){?>        <div class="constructor2">
            <div class="inner">
                <img class="miniman" src="/upload/banner/<?=$banner->foto?>" alt="<?=$banner->title?>"/>
                <div style="width:520px;"><?=$banner->text?></div>
                <div style="cursor:pointer;" class="button <?=$banner->title?>" onclick="<?=$banner->url?>"><?=$banner->button?></div>
            </div>
       </div>
<?}?>
