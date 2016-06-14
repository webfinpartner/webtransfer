<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<? $this->load->view('/user/blocks/header')?>
<div id="container" class="content <?= $classes ?>">
    <?=$contents?>
</div>

<? $this->load->view('/user/blocks/footer')?>