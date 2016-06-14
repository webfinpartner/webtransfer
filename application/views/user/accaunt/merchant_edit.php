<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<style>

	form
	{
		display: block;
	}

	form[inline]
	{
		display: inline-block;
		width: 45%;
		margin: 2%;
	}

	form block
	{
		display: inline-block;
		width: 45%;
		margin: 2%;
	}

	form block label
	{
		display: block;
		font-size: 14px;
		padding-left: 15px;
		padding-bottom: 5px;
		font-weight: bolder;
	}

	form block[logo], form block[action]
	{
		display: block;
		margin: 2%;
		width: 100%;
	}

	form block[logo] label
	{
		background-color: #F4F4F4;
		padding: 10px;
		text-align: center;
		-webkit-border-top-left-radius: 3px;
		-webkit-border-top-right-radius: 3px;
		-moz-border-radius-topleft: 3px;
		-moz-border-radius-topright: 3px;
		border-top-left-radius: 3px;
		border-top-right-radius: 3px;
	}

	form block[action] a
	{
		background-color: #F4F4F4;
		padding: 10px;
		text-align: center;
		display: block;
		color: #626262;
		cursor: pointer;
	}

	form block[action] a[save]
	{
		-webkit-border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topleft: 6px;
		-moz-border-radius-topright: 6px;
		border-top-left-radius: 6px;
		border-top-right-radius: 6px;
	}

	form block[action] a[gener]
	{
		background-color: #FF5200;
		color: #FFFFFF;
	}

	form block[action] a[remove]
	{
		background-color: #637C97;
		color: #FFFFFF;
		-webkit-border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-right-radius: 6px;
		border-bottom-left-radius: 6px;
	}

	form block[logo] label img
	{
        -webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
		border: 1px solid #626262;
		max-width: 225px;
	}

	form block input, form block select
	{
            display: block;
            border-width: 0px;
            padding: 15px;
            font-size: 16px;
            color: #8ECBCB;
            outline: medium none;
            box-sizing: border-box;
            width: 100%;
            background-color: #637C97;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
	}

	form h1, h1[form]
	{
		line-height: 26px;
		margin-bottom: 24px;
		font-weight: 300;
		margin-left: 9px;
		margin-top: 15px;
	}

	error, notification
	{
            display: inline-block;
            min-width: 45%;
            margin: 2%;
            background-color: #FF5200;
            color: #FFFFFF;
            padding: 15px;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            outline: medium none;
            box-sizing: border-box;
	}

        error[full], notification[full]
        {
            width: 96%;
        }

	info
	{
		display: block;
		margin: 2%;
	}

	info text
	{
		display: block;
		padding: 10px;
		background-color: #F4F4F4;
		color: #626262;
	}

	info text:nth-child(1)
	{
		-webkit-border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topleft: 6px;
		-moz-border-radius-topright: 6px;
		border-top-left-radius: 6px;
		border-top-right-radius: 6px;
	}

	info code, [code]
	{
		display: block;
		padding: 10px;
		background-color: #dbdbdb;
		color: #626262;
	}

	info a[submit]
	{
		display: block;
		width: 100%;
		padding: 10px;
		background-color: #637C97;
        outline: medium none;
        box-sizing: border-box;
        border-width: 0px;
        color: #FFFFFF;
        text-align: center;
        -webkit-border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-right-radius: 6px;
		border-bottom-left-radius: 6px;
	}

	notification
	{
		background-color: #369A97;
	}


</style>

<script type="text/javascript">
	function submitform(id)
	{
		document.forms[id].submit();
	}
</script>


<? if ($shop->status == Shop_model::CREATE) { ?>
    <h1 style="margin-bottom: 7px;"><?=_e('Активация')?></h1>
<form>
    <info>
        <text>
            <?=_e('new_123')?>
        </text>
        <code>
                <pre style="font-family: monospace;"><span style="color: #009900;">&lt;<span style="color: #000000; font-weight: bold;">meta</span> <span style="color: #000066;">name</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">"webtransfer"</span> <span style="color: #000066;">content</span><span style="color: #66cc66;">=</span><span style="color: #ff0000;">"<?=md5($this->id_user)?>"</span>&gt;</span> </pre>
        </code>
        <text>
            <?=_e('new_124')?>
        </text>
        <a href="<?=site_url('account/merchant/download/')?>" code>
                <img src="/images/merchant/link.png" /> webtransfer_<?=md5($this->id_user)?>.txt
        </a>
        <a href="<?=site_url('account/merchant/edit/' . $shop->shop_slash . '/activation/' . $shop->string_slash)?>" submit><?=_e('new_133')?></a>
    </info>
</form>
<? } ?>


<? if (validation_errors() != NULL || $notification != NULL) echo "<h1 style=\"margin-bottom: 7px;\">" . _e('new_134') . "</h1>"; ?>
<?=validation_errors() . $notification; ?>

<?=form_open(NULL, array('id' => "submit_edit")); ?>

	<h1 style="margin-bottom: 14px;"><?=_e('Редактирование')?></h1>

	<block>
		<label for="shop_id"><img src="/images/merchant/locked.png" /> ID:</label>
		<?=form_input(array('name' => "shop_id", 'id' => "shop_id", 'value' => $shop->shop_slash, 'disabled' => "disabled")); ?>
	</block>

	<block>
		<label for="status"><img src="/images/merchant/locked.png" /> <?=_e('new_135')?>:</label>
		<?=form_input(array('name' => "status", 'id' => "status", 'value' => $activation[$shop->status], 'disabled' => "disabled")); ?>
	</block>


	<block>
		<label for="url"><img src="/images/merchant/locked.png" /> URL:</label>
		<?=form_input(array('name' => "url", 'id' => "url", 'value' => htmlspecialchars_decode(base64_decode($shop->url)), 'disabled' => "disabled")); ?>
	</block>

	<block>
		<label for="title"> <?=_e('new_136')?>:</label>
		<?=form_input(array('name' => "title", 'id' => "title", 'value' => base64_decode($shop->title))) ?>
	</block>

	<block>
		<label for="url_success"><?=_e('new_137')?>:</label>
		<?=form_input(array('name' => "url_success", 'id' => "url_success", 'value' => base64_decode($shop->url_success))) ?>
	</block>

	<block>
		<label for="url_fail"><?=_e('new_138')?>:</label>
		<?=form_input(array('name' => "url_fail", 'id' => "url_fail", 'value' => base64_decode($shop->url_fail))) ?>
	</block>

	<block>
		<label for="url_result"><?=_e('new_139')?>:</label>
		<?=form_input(array('name' => "url_result", 'id' => "url_result", 'value' => base64_decode($shop->url_result))) ?>
	</block>

	<block>
            <label for="string"><img src="/images/merchant/locked.png" /> <?=_e('new_140')?>: <a href="<?=site_url('account/merchant/edit/' . $shop->shop_slash . '/string/' . $shop->string_slash)?>" onclick="return confirm('Вы уверены что хотите сменить секретный ключ?')"><img src="/images/merchant/reload.png" /></a></label>
		<?=form_input(array('name' => "string", 'id' => "string", 'value' => $shop->string_slash, 'disabled' => "disabled")); ?>
	</block>

	<block>
                <label for="commission"><?=_e('new_141')?></label>
                <?=form_dropdown('commission', array(_e('new_152'), _e('new_153')), $shop->commission, "id=\"commission\""); ?>
	</block>

	<block>
                <label for="commission"><?=_e('new_142')?></label>
                <?=form_dropdown('section', getCategories(), $shop->section, "id=\"section\""); ?>
	</block>

	<block>
		<label for="url_result"><?=_e('new_143')?></label>
		<?=form_input(array('name' => "tel", 'id' => "tel", 'value' => base64_decode($shop->tel))) ?>
	</block>

	<block>
		<label for="url_result"><?=_e('new_144')?></label>
		<?=form_input(array('name' => "email", 'id' => "email", 'value' => base64_decode($shop->email))) ?>
	</block>

	<!-- Тип -->
	<?=form_hidden('type', 'edit'); ?>
	<!-- Тип -->

<?=form_close(); ?>

<?=form_open_multipart(NULL, array('inline' => "inline", 'id' => "submit_logo")); ?>
	<h1><?=_e('new_145')?></h1>
	<block logo>
		<label for="logo"><img src="/upload/images/merchant/<?=(isset($shop->image)) ? md5($shop->shop_id) . "." . $shop->image : "no.png"; ?>" /></label>
		<?=form_upload(array('name' => "logo", 'id' => "logo", 'onchange' => "submitform('submit_logo'); return false;", 'accept' => "image/jpeg,image/png")); ?>
                <?=form_hidden('upload', 'logo'); ?>
	</block>
<?=form_close(); ?>

<?=form_open(NULL, array('inline' => "inline", 'style' => "float: right;")); ?>
	<h1><?=_e('new_146')?></h1>
	<block action>
            <a onclick="<? if ($shop->status == Shop_model::ACTIVE) echo "submitform('submit_edit'); return false"; ?>" save><? if ($shop->status != Shop_model::ACTIVE) echo "<img src=\"/images/merchant/locked.png\" />"; ?> <?=_e('new_147')?></a>
	<? if ($shop->status != Shop_model::DELETED): ?>
		<a href="<?=site_url('account/merchant/edit/' . $shop->shop_slash . '/delete/' . $shop->string_slash)?>" onclick="return confirm('<?=_e('new_150')?>')" remove><?=_e('new_148')?></a>
	<? else: ?>
		<a href="<?=site_url('account/merchant/edit/' . $shop->shop_slash . '/delete/' . $shop->string_slash)?>" onclick="return confirm('<?=_e('new_151')?>')" remove><?=_e('new_149')?></a>
	<? endif; ?>
	</block>
<?=form_close(); ?>