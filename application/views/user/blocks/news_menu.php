<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
            <div class="left_menu">
                <ul>
                    <?php if(!empty($news)){
			foreach($news as $menu){?>
			<li  <?if($menu->title==$new->title) echo'class="active"'?>  ><a href="<?=site_url('news')?>/<?=$menu->url?>"><?=cutString($menu->title,20)?></a></li>
			<?}}?>
				</ul>
            </div>