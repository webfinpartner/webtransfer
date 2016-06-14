<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
# Пример кода "1.1."
$code['1.1'] =
"<form method=\"POST\" action=\"https://webtransfer-finance.com/merchant/pay\">
 <input type=\"hidden\" name=\"shop_id\" value=\"{ID}\"* />
 <input type=\"hidden\" name=\"order_id\" value=\"{integer}\" />
 <input type=\"hidden\" name=\"description\" value=\"{string length 250}\" />
 <input type=\"hidden\" name=\"other\" value=\"{string length 250}\" />
 <input type=\"text\" name=\"amount\" value=\"{float}\" />
 <input type=\"text\" name=\"currency\" value=\"{'', 'P_CREDS', 'C_CREDS', 'WTUSD1','WTUSD2','WTDEBIT'}\"** />
 <input type=\"text\" name=\"hash\" value=\"{md5(hash_string***)}\" />
</form>
*\"ID\" like \"XXXX-XXXX-XXXX-XXXX-XXXX-XXXX\"
** '' - обычный счет WTUSD1, 'P_CREDS' - партнерский счет, 'C_CREDS' - счет Cash CREDS, 
'WTDEBIT' - счет Webtransfer Debit, 'WTUSD1' - обычный счет WTUSD1,
'WTUSD2' - обычный счет WTUSD2 (Это поле не обязательно, при его отсутствии будет 
установлен обычный счет WTUSD1)
***\"hash_string\" = \"shop_id:order_id:amount:currency:SecretKey\",
где \"SecretKey\" в формате \"XXXX-XXXX-XXXX-XXXX-XXXX-XXXX\"
";
$code['1.2'] =
"<form method=\"POST\" action=\"{URL обработчика}\">
	<input type=\"hidden\" name=\"shop_id\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"amount\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"order_id\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"description\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"transaction_id\" value=\"{id in webtransfer-finance}\" />
	<input type=\"hidden\" name=\"status\" value=\"success\" />
	<input type=\"text\" name=\"other\" value=\"{value from previus form}\" />
	<input type=\"text\" name=\"hash\" value=\"{md5(hash_string*)}\" />
</form>
*\"hash_string\" = \"shop_id:amount:currency:order_id:description:transaction_id:SecretKey**\"
**\"SecretKey\" like \"XXXX-XXXX-XXXX-XXXX-XXXX-XXXX\"";
$code['1.2.1'] =
"<form method=\"POST\" action=\"{URL обработчика}\">
	<input type=\"hidden\" name=\"shop_id\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"amount\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"order_id\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"description\" value=\"{value from previus form}\" />
	<input type=\"hidden\" name=\"transaction_id\" value=\"{id in webtransfer-finance}\" />
	<input type=\"hidden\" name=\"status\" value=\"fail\" />
	<input type=\"text\" name=\"other\" value=\"{value from previus form}\" />
	<input type=\"text\" name=\"hash\" value=\"{md5(hash_string*)}\" />
</form>
*\"hash_string\" = \"shop_id:amount:currency:orderid:description:transaction_id:SecretKey**\"
**\"SecretKey\" like \"XXXX-XXXX-XXXX-XXXX-XXXX-XXXX\"";

$code['1.3'] =
"
_error_reporting(E_ALL ^ E_NOTICE);

if(!isset(\$_POST['hash'])){ // you can also save invalid payments for debug purposes
    // ....
    // uncomment code below if you want to log requests with fake data
    /* \$f=fopen(PATH_TO_LOG.\"bad.log\", \"ab+\");
    fwrite(\$f,
        date(\"d.m.Y H:i\")
        .\"; REASON: fake data; POST: \"
        .serialize(\$_POST)
        .\"; STRING: \$string; HASH: \$hash\\n\"
    );
    fclose(\$f); */
    die;
}

\$string = \$_POST['shop_id'].':'.\$_POST['amount'].':'
          .\$_POST['order_id'].':'.\$_POST['description'].':'
          .\$_POST['transaction_id'].':'.getYourSecretKey();

\$hash=md5(\$string);

if(\$hash==\$_POST['hash']){ // proccessing payment if only hash is valid

    \$order_id = (int) \$_POST['order_id'];

    // .......

    if(\$_POST['amount']==\$my_order_summ && \$_POST['shop_id']==getMyShopId()){

        \$t_id = \$_POST['transaction_id'];

        // .......
        // other proccessing code.

    }else{ // you can also save invalid payments for debug purposes
        // ....
        // uncomment code below if you want to log requests with fake data
        /* \$f=fopen(PATH_TO_LOG.\"bad.log\", \"ab+\");
        fwrite(\$f,
            date(\"d.m.Y H:i\")
            .\"; REASON: fake data; POST: \"
            .serialize(\$_POST)
            .\"; STRING: \$string; HASH: \$hash\\n\"
        );
        fclose(\$f); */
        die;

    }


}else{ // you can also save invalid payments for debug purposes
    // ....
    // uncomment code below if you want to log requests with fake data
    /* \$f=fopen(PATH_TO_LOG.\"bad.log\", \"ab+\");
    fwrite(\$f,
        date(\"d.m.Y H:i\")
        .\"; REASON: fake data; POST: \"
        .serialize(\$_POST)
        .\"; STRING: \$string; HASH: \$hash\\n\"
    );
    fclose(\$f); */
    die;
}";
?>


<style>
	h1[api]
	{
		line-height: 26px;
		margin-bottom: 24px;
		font-weight: 300;
		margin-left: 9px;
		margin-top: 15px;
	}
	code
	{
		background-color: #DBDBDB;
		padding: 10px;
		display: block;
	}

	info
	{
		background-color: #F5F5F5;
		padding: 10px;
		display: block;
	}

	code[bottom], info[bottom]
	{
		-webkit-border-bottom-right-radius: 6px;
		-webkit-border-bottom-left-radius: 6px;
		-moz-border-radius-bottomright: 6px;
		-moz-border-radius-bottomleft: 6px;
		border-bottom-right-radius: 6px;
		border-bottom-left-radius: 6px;
	}

	code[top], info[top]
	{
		-webkit-border-top-left-radius: 6px;
		-webkit-border-top-right-radius: 6px;
		-moz-border-radius-topleft: 6px;
		-moz-border-radius-topright: 6px;
		border-top-left-radius: 6px;
		border-top-right-radius: 6px;
	}
</style>

<h1 api><?=_e('new_127')?></h1>
<info top><?=_e('new_128')?></info>
<?="<code bottom><span>" . substr(highlight_string("<?\n" . $code['1.1'], 1), 83) . ""?>

<h1 api><?=_e('new_129')?></h1>
<info top><?=_e('new_130')?></info>
<?="<code bottom><span>" . substr(highlight_string("<?\n" . $code['1.2'], 1), 83) . ""?>
<info top><?=_e('new_1301')?></info>
<?="<code bottom><span>" . substr(highlight_string("<?\n" . $code['1.2.1'], 1), 83) . ""?>
<info top><?=_e('new_131')?></info>

<h1 api><?=_e('new_132')?></h1>
<?="<code bottom><span>" . substr(highlight_string("<?\n" . $code['1.3'], 1), 83) . ""?>