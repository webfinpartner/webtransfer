<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo _e('account/about_partner_1');
?>

<a href="<?=site_url('page/about/terms')?>">

<?php
echo _e('account/about_partner_1_1');

if ($this->user->partner != 1)
    echo '<a href="'.site_url('account/activePartner').'" class="button long">'._e('account/about_partner_2').'</a>'; 

if ($this->user->partner = 1)
    echo '<a href="'.site_url('partner/my-link').'" class="button long">'._e('blocks/renderHeaderMenu_part_3').'</a>'; 


?>