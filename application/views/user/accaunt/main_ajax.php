<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
  
<script>
    security = '<?= isset($data['security'])? $data['security'] : '' ?>';
    page_hash = '<?= isset($data['page_hash'])? $data['page_hash'] : '' ?>';
    $(function(){        
        $('form .page_hash').remove();
        $('<input>').attr({
            type: 'hidden',        
            name: 'page_hash',
            value: window.page_hash,
            class: 'page_hash'
        }).appendTo('form'); 
    });
</script>

<? $this->load->view( $view, $data );?>


