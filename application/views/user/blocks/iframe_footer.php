<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    var current_protect_type = '<?= ( !isset( $security )?'none': $security ) ?>';
    var current_protect_type_show = '<?= ( !isset( $security_show )?'none': (int)$security_show ) ?>';
    var show_set_force_phone = "<?=(int)$show_set_force_phone?>";
   
    function forcing_one_pass_setup(res)
    {
        if (typeof res['res'] === undefined || res['res'] != 'success') {
            if (res['res'] == 'closed')
                location.reload();
            return false;
        }

        var data = {"old": {"type": current_protect_type, "code": "0000000"}, 'new': {type: 'one_pass', code: res['code']} };
        console.log(data);

        mn.get_ajax('/' + mn.site_lang + '/security/ajax/save_security_type', {data: JSON.stringify(data)},
        function (res) {
            console.log('save_security_call_back', res);

            if( res['action'] == 'refesh_page' ) location.reload();
            if (res['error'])
            {
                mn.security_module.loader.show(res['error'], 20000);

            }

            if (res['success'])
            {
                mn.security_module.loader.show(res['success'], 10000);
            }

            //location.reload();
        });
        return false;

    }

    function forcing_phone(res)
    {
        console.log('------------------start 1');
        console.log(res);
    }

    $(function(){
        if ( current_protect_type_show == 1 ) {
            mn.security_module
            .init()
            .show_window('set_security_type', 'one_pass')
            .done(forcing_one_pass_setup);        
        } else if (show_set_force_phone == 1 ) { //1 is true
            mn.security_module
            .init()
            .show_window('show_set_phone', 'show_set_phone')
            .done(forcing_phone);
            
        }
    });
</script>
</div>
<!-- Конец CONTENT-->
</div>
<!-- Конец WRAPPER-->
<!-- FOOTER -->





<script  type="text/javascript"  src="/js/user/security_module.js?v=201603143"></script>
<link rel="stylesheet"  href="/css/user/security_module.css?v=20160121"></link>
<? // $this->load->view('user/accaunt/security_module/universal_window'); ?>
<?php
include 'iframe_reviews_block_small.php';
?>


</body>
</html>
