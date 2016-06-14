<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>


<style>

	error, notification
	{
            display: block;
            width: 96%;
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

        line
        {
            display: block;
            padding: 0px !important;
        }

        line input
        {
            border-width: 0px;
            padding: 17px 17px 18px 0px;
            color: #999999;
            display: inline-block;
            outline: medium none;
            font-size: 115%;
            box-sizing: border-box;
            width: 100%;
            background-color: #dbdbdb;
        }

        line select
        {
            border-width: 0px;
            padding: 17px 0px 17px 17px;
            color: #999999;
            outline: medium none;
            font-size: 115%;
            box-sizing: border-box;
            display: inline-block;
            width: 20%;
            background-color: #dbdbdb;
            text-align: center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

</style>

<script type="text/javascript">
	function submitform(id)
	{
		document.forms[id].submit();
	}
</script>

<?php if (isset($err)) echo "<error>" . $err . "</error>"; ?>

<form method="POST" id="submit_add">
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
            <text>
                <?=_e('new_125')?>
            </text>
            <line>
                <input type="text" name="link" placeholder="https://wt-shop.ru" value="<?=htmlentities($this->input->post('link'))?>" />
            </line>
            <a onclick="submitform('submit_add'); return false;" submit><?=_e('new_126')?></a>
    </info>
</form>