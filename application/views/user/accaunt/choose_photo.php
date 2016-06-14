<?php if( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<link rel="stylesheet" type="text/css" href="/css/user/imgareaselect-deprecated.css" />
<!--<script type="text/javascript" src="/js/user/jquery-pack.js"></script>-->
<!--<script type="text/javascript" src="/js/user/jquery.imgareaselect.min.js"></script>-->
<script type="text/javascript" src="/js/user/jquery.imgareaselect.pack.js"></script>

<?php
//var_dump($img);
if( strlen( $img->large_photo_exists ) > 0 )
{
    ?>
    <script type="text/javascript">
        function preview(img, selection) {
            var scaleX = <?php echo $img->thumb_width; ?> / selection.width;
            var scaleY = <?php echo $img->thumb_height; ?> / selection.height;

            $('#thumbnail + div > img').css({
                width: Math.round(scaleX * <?php echo $img->current_large_image_width; ?>) + 'px',
                height: Math.round(scaleY * <?php echo $img->current_large_image_height; ?>) + 'px',
                marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
            });
            $('#x1').val(selection.x1);
            $('#y1').val(selection.y1);
            $('#x2').val(selection.x2);
            $('#y2').val(selection.y2);
            $('#w').val(selection.width);
            $('#h').val(selection.height);
        }

        $(document).ready(function() {
            $('#save_thumb').click(function() {
                var x1 = $('#x1').val();
                var y1 = $('#y1').val();
                var x2 = $('#x2').val();
                var y2 = $('#y2').val();
                var w = $('#w').val();
                var h = $('#h').val();
                if (x1 == "" || y1 == "" || x2 == "" || y2 == "" || w == "" || h == "") {
                    alert("<?=_e('accaunt/choose_photo_1')?>");
                    return false;
                } else {
                    return true;
                }
            });
        });

        $(document).ready(function() {
            $('#thumbnail').imgAreaSelect({
                x1: 0, y1: 0, x2: <?= $img->thumb_width; ?>, y2: <?= $img->thumb_height; ?>,
                handles: true,
                aspectRatio: '1:<?php echo $img->thumb_height / $img->thumb_width; ?>',
                onSelectChange: preview,
                parent: '#thumbnail_wrap'
            });
        });

    </script>
<?php }
?>
<script>
    $(document).ready(function() {
        $('.avatar-heading').click(function() {
            $(this).parent().find('.avatar-content').slideToggle(300);
        });
        $('.avatar_list_form_list_item_label').click(function() {
            $('.avatar_list_form_list_item_label.active .avatar_list_form_list_item_radio').prop('checked', false);
            $('.avatar_list_form_list_item_label.active').removeClass('active');

            $(this).addClass('active')
                    .find('.avatar_list_form_list_item_radio').prop('checked', true);
        });
    });
</script>

<form name="photo" enctype="multipart/form-data" action="<?=site_url('account/choose_photo')?>" method="post">
<div class="avatar">
    <div id="avatar-rules">
        <div class="avatar-heading"><span><?=_e('accaunt/choose_photo_2')?></span></div>
        <div class="avatar-content">
                <p><?=_e('accaunt/choose_photo_3')?></p>
                <ul class="list">
                    <?=_e('accaunt/choose_photo_4')?>
                </ul>
                <p><?=_e('accaunt/choose_photo_5')?></p>
        </div>
    </div>
<!--    <div id="avatar-settings" class="active">
        <div class="avatar-heading"><span>< ?=_e('accaunt/choose_photo_6')?></span></div>
        <div class="avatar-content">

                <label><input type="checkbox" name="show" value="1" < ?=($show_status == 1?'checked="checked"':'')?>/>
                    < ?=_e('accaunt/choose_photo_7')?>
                </label>
                <button class="button" type="submit" name="save_settings" >< ?=_e('accaunt/choose_photo_8')?></button>

        </div>
    </div>    -->
    <div id="avatar-upload" class="<?=($load_photo_active?'active':'')?>">
        <div class="avatar-heading"><span><?=_e('accaunt/choose_photo_9')?></span></div>
        <div class="avatar-content">

                    <input type="file" name="image" size="30" />
                    <input type="submit" name="upload" value="<?=_e('accaunt/choose_photo_10')?>" />

                <?php
                if( strlen( $img->large_photo_exists ) > 0 )
                {
                    ?>
                    <div id="thumbnail_wrap" align="center" style="position: relative;">
                        <img src="<?php echo $img->upload_path . $img->large_image_name . $img->ext; ?>" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" />
                        <div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $img->thumb_width; ?>px; height:<?php echo $img->thumb_height; ?>px;">
                            <img src="<?php echo $img->upload_path . $img->large_image_name . $img->ext; ?>" style="position: relative;" alt="Thumbnail Preview" />
                        </div>
                        <div style="clear:both; margin-bottom: 20px; "></div>
                        <form name="thumbnail" action="<?=site_url('account/choose_photo')?>" method="post">
                            <input type="hidden" name="x1" value="" id="x1" />
                            <input type="hidden" name="y1" value="" id="y1" />
                            <input type="hidden" name="x2" value="" id="x2" />
                            <input type="hidden" name="y2" value="" id="y2" />
                            <input type="hidden" name="w" value="" id="w" />
                            <input type="hidden" name="h" value="" id="h" />

                            <label>
                                <input type="checkbox" name="rules" value="1" />
                                <?=_e('accaunt/choose_photo_11')?>
                            </label>

                            <button id="save_thumb" class="button" type="submit" name="upload_thumbnail" value="1" ><?=_e('accaunt/choose_photo_12')?></button>
                    </div>
            <?php } ?>
        </div>
    </div>
    <? if( !empty( $avatar_list ) ): ?>
        <div id="avatar-list" class="<?= ($avatar_active?'active':'')?>">
            <div class="avatar-heading"><span><?=_e('accaunt/choose_photo_13')?></span></div>
            <div class="avatar-content">
                    <ul class="avatar_list_form_list">
                        <? foreach( $avatar_list as $img ):
                            $is_active = ($stdavatar == $img)?TRUE:FALSE;
                            ?>
                            <li class="avatar_list_form_list_item">
                                <label class="avatar_list_form_list_item_label <?=($is_active?'active':'')?>">
                                    <input class="avatar_list_form_list_item_radio" type="radio" name="stdavatar" value="<?= $img ?>" <?=($is_active?'checked=""':'')?>"/>
                                    <img src="/upload/avatars/<?= $img ?>" alt="">
                                </label>
                            </li>
                        <? endforeach; ?>
                    </ul>
                    <button class="button" type="submit" name="collection"><?=_e('accaunt/choose_photo_14')?></button>
            </div>
        </div>
    <? endif; ?>
</div>
</form>