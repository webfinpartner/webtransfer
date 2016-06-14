<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<style>
    shops
    {
        display: block;
    }

    shops shop
    {
        display: inline-block;
        width: 210px;
        height: 50px;
        padding: 10px;
        margin: 8px 3px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        border-bottom: 4px solid red;
        position: relative;
        background-color: #F5F5F5;
        cursor: pointer;
        position: relative;
        transition: 0.2s linear;
    }

    shops shop[active]
    {
        border-bottom-color: #2a5c03 !important;
    }

    shops shop[blocked]
    {
        border-bottom-color: #cc0029 !important;
    }

    shops shop[wait]
    {
        border-bottom-color: #f4a900 !important;
    }

    shops shop[deleted]
    {
        border-bottom-color: #808080 !important;
    }

    shops shop[add]
    {
        border-bottom-color: #808080 !important;
    }

    shops shop:hover[active], shops shop:hover[blocked], shops shop:hover[wait], shops shop:hover[add]
    {
        border-bottom-color: #637C97 !important;
    }

    shops shop info
    {
        display: block;
        position: absolute;
        bottom: 0px;
        left: 0px;
        padding: 8px 10px 8px 20px;
        background-color: #637C97;
        color: #8ECBCB;
        opacity: 0;
        transition: 0.2s linear;
        height: 14px;
        width: 200px;
        -webkit-border-bottom-right-radius: 4px;
        -webkit-border-bottom-left-radius: 4px;
        -moz-border-radius-bottomright: 4px;
        -moz-border-radius-bottomleft: 4px;
        border-bottom-right-radius: 4px;
        border-bottom-left-radius: 4px;
        white-space: nowrap;
        word-wrap: break-word;
        text-transform:uppercase;
    }

    shops shop:hover info
    {
        opacity: 0.95 !important;
    }

    shops shop img
    {
        transition: 0.2s;
        display: block;
        margin: 0px auto;
        max-width: 200px;
        max-height: 48px;
    }

    shops shop:hover img
    {
        filter: blur(3px);
        transition: 0.2s;
    }

    shops shop[add]:hover img
    {
        filter: blur(0px);
        transition: 0s;
    }

    .content
    {
        position: relative !important;
    }

    help
    {
        position: absolute;
        display: block;
        bottom: 0px;
        left: 0px;
        right: 0px;
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.1);
    }

    help type
    {
        display: block;
    }

    help type span
    {
        width: 80px;
        display: inline-block;
        text-align: center;
    }

    popup
    {
        display: block;
        position: fixed;
        top: 0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        background-color: rgba(0,0,0,0.9);
        z-index: 16000002;
    }

    popup text
    {
        display: block;
        padding: 10px;
    }

    popup a
    {
        text-decoration: none;
        margin: 0px;
        padding: 0px;
        color: #000000;
        display: block;
    }

    popup code
    {
        display: block;
        padding: 10px;
        background-color: #F5F5F5;
    }

    popup img
    {
        vertical-align: middle;
    }

    popup form[auth]
    {
        width: 590px;
        margin: 10% auto;
        background-color: #FFFFFF;
    }

    popup form[auth] close
    {
        float: right;
        margin-top: -45px;
        cursor: pointer;
    }

    popup form[auth] popupTitle
    {
        float: left;
        margin-top: -45px;
        font-weight: 300;
        font-family: "Open Sans",Arial,sans-serif;
        font-size: 125%;
        color: #FFF;
        line-height: 1.375;
    }

    popup form[auth] block
    {
        display: block;
        padding: 0px 0px 0px 0px;
    }

    popup form line
    {
        display: block;
        padding: 0px !important;
    }

    popup form line input
    {
        border-width: 0px;
        padding: 17px 17px 17px 0px;
        color: #999999;
        display: inline-block;
        outline: medium none;
        font-size: 115%;
        box-sizing: border-box;
        width: 80%;
        background-color: #F5F5F5;
    }

    popup form line select
    {
        border-width: 0px;
        padding: 17px 0px 17px 17px;
        color: #999999;
        outline: medium none;
        font-size: 115%;
        box-sizing: border-box;
        display: inline-block;
        width: 20%;
        background-color: #F5F5F5;
        text-align: center;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    popup form line input[type="submit"]
    {
        background: none repeat scroll 0px 0px #7FBFBF;
        color: #FFF;
        display: block;
        width: 100%;
        padding: 17px 17px 17px 17px;
    }

</style>

<!-- Список магазинов -->
<shops>
    <? if(!count($list)) echo _e('new_158');
       else { $count = 1; ?>
<table cellspacing="0" class="payment_table">
    <thead>
        <tr>
            <th><center><?=_e('new_159')?></center></th>
            <th>Logo</th>
            <th>URL</th>
            <th><?=_e('new_155')?></th>
            <th><?=_e('new_156')?></th>
        </tr>
    </thead>
    <tbody>
    <?foreach ($list as $row) { ?>
        <tr>
            <td><center><?=$count++?></center></td>
            <td><img src="/upload/images/merchant/<?=(isset($row->image)) ? md5($row->shop_id) . "." . $row->image : "no.png"; ?>" /></td>
            <td><?=base64_decode($row->url); ?></td>
            <td><?=$status[$row->status]?></td>
            <td>
                <a href="<?=site_url('account/merchant/edit/' . $this->shop->slash($row->shop_id))?>"><?=_e('new_157')?></a>
                <!--<a href="<?=site_url('account/merchant/showTransaction/' . $this->shop->slash($row->shop_id))?>">Посмотреть</a>-->
            </td>
        </tr>
    <? } ?>
    </tbody>
</table>
<?}?>
</shops>