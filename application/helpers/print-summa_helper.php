<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Возвращает сумму прописью
 * @author runcore
 * @uses morph(...)
 */
function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('долл.США'   ,'долл.США'   ,'долл.США'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    //$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
   // $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/**
 * Склоняем словоформу
 * @ author runcore
 */
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

function num3str($number) {
if (($number < 0) || ($number > 999999999)) {
return ;
}

$Gn = floor($number / 1000000);
/* Millions (giga) */
$number -= $Gn * 1000000;
$kn = floor($number / 1000);
/* Thousands (kilo) */
$number -= $kn * 1000;
$Hn = floor($number / 100);
/* Hundreds (hecto) */
$number -= $Hn * 100;
$Dn = floor($number / 10);
/* Tens (deca) */
$n = $number % 10;
/* Ones */

$res = "";

if ($Gn) {
$res .= num3str($Gn) . "Million";
}

if ($kn) {
$res .= (empty($res) ? "" : " ") . num3str($kn) . " Thousand";
}

if ($Hn) {
$res .= (empty($res) ? "" : " ") . num3str($Hn) . " Hundred";
}

$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

if ($Dn || $n) {
if (!empty($res)) {
$res .= " and ";
}

if ($Dn < 2) {
$res .= $ones[$Dn * 10 + $n];
} else {
$res .= $tens[$Dn];

if ($n) {
$res .= "-" . $ones[$n];
}
}
}

if (empty($res)) {
$res = "zero";
}

return $res;
}
function countCenterX($start,$text,$width)
{
    return  $start-(mb_strlen($text)/2)*$width;
}

//центр текста
function getCenter(array $boxSyze, $text, $font, $size, $space = 0, $tightness = 0, $angle = 0) {
    // find the size of the text
    $box = ImageTTFBBox($size, $angle, $font, $text);

    $xr = abs(max($box[2], $box[4]));
    $yr = abs(max($box[5], $box[7]));

    // compute centering
    $x = intval(($boxSyze['x'] - $xr) / 2);
    $y = intval(($boxSyze['y'] + $yr) / 2);

    return array('x' => $x, 'y' => $y);
}