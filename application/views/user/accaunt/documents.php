<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript" src="<?= base_url() ?>js/admin/plugins/forms/uniform.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/user/document.js"></script>
<script src="https://malsup.github.com/jquery.form.js"></script>

<link rel="stylesheet" href="/css/user/document.css" />
<div class="doc_content">
<div style="padding:10px;border:1px solid #eee;margin:10px auto;">
<?=_e('doc_text')?>
</div>
<form id="documents" enctype="multipart/form-data" action="<?=site_url('account/documents')?>"  method="post" >
	<input type="hidden" name="save" value="1" />
	<?=_e('type')?>: <br/>
	<?
        $docs = getDocumentsLabel();
	echo form_dropdown('type', $docs, array('1'), 'id="form_type" style="padding:5px;"')
	?>
	<br /><br />
	<?=_e('Выберите файл')?>: <br/>
	<input type="file" id="foto" name="foto">
    <button class="button" id="documents_submit" type="submit"><?=_e('doc_upload')?></button>
</form>

<div id="error_foto" style="text-align: center; color: red;"></div>
<?
$items2 = array();
foreach($items as $item ) {
    $items2[ $item->num ] = $item;
}
foreach($docs as $i => $docs_lab ) {
    if(!isset($items2[$i]))
        continue;

	$document = $items2[$i]; ?>

	<div id="tab<?= $i ?>" class="tabs" >
		<? if (!empty($document->state)) { ?>
			<h4 class="title"><?= $docs[$i] ?></h4>
			<h4 class="status"><?=_e('doc_status')?> : <span class="state_d"><?
		if (empty($document->state))
			echo _e('doc_noupload');
                else echo getDocumentsStatus()[$document->state];
		?>
				</span>
			</h4>
            <? if( $i == '5' ){
                echo _e('doc_another');
            } ?>

			<div class="docs">
				<div class="first_doc">
                    <? if (!empty($document->img2))
                    {
                        echo"<span class='old_agree'>"._e('doc_old_confirmed')."</span>";
                    }
                    ?><br />
                    <? if (!empty($document->img))
                    {
                        if( isset( $document->ext ) && $document->ext == 'pdf' )
                            echo'<iframe class="image" src="'.site_url('account/doc/'.$document->num).'" ></iframe>';
                        else
                            echo'<img class="image" src="'.site_url('account/doc/'.$document->num).'" />';
                    }?>
                    <? if ($document->state == 1 or $document->state == 3)
                    {
                        echo"<div class='delete_f' style='text-align:center;'>
                            <img src='/images/icons/del.png'>"._e('delete')."</div>";
                    }
                    ?>
				</div>
				<? if (!empty($document->img2))
                {
                    echo '<div class="second_doc">'._e("doc_new_review").'<br />';

                    if( isset( $document->ext ) && $document->ext == 'pdf' )
                    {
                        echo '<iframe class="image" src="' . base_url() . 'account/doc/' . $document->num . '/2" ></iframe>';
                    }
                    else
                    {
                        echo '<img class="image" src="' . base_url() . 'account/doc/' . $document->num . '/2" />';
                    }

                    echo '<div class="delete_f">'._e("delete").'</div></div>';
                }?>
			</div>

	<? }
	else {?>
            <h4><?= _e('doc_upload').": " . $docs[$i]; ?> </h4>
            <? if( $i == '5' ){
                echo _e('doc_another');
            } ?>
    <? }?>
	</div>

<? } ?>



<div class="popup_window"  id="popup_agree_confirm1" >
    <div onclick="$('#popup_agree_confirm1').hide('slow');" class="close"></div>
    <h2><?=_e('doc_popup_title')?></h2>
 <?=_e('doc_popup_text')?><br/>
    <a class="button narrow" ><?=_e('accaunt/documents_1')?></a>
</div>
<script>
    $(function(){
//        $('#popup_agree_confirm1 .button').click(function(){
//            $('#documents_submit').addClass('active');
//
//            $(this).parent().hide('slow');
//            $('#documents_submit').click();
//        });
//        $('#documents_submit').click(function(){
//            if( !$(this).hasClass('active') ){
//                $('#popup_agree_confirm1').show('slow');
//                return false;
//            }
//        });
    });
</script>
</div>