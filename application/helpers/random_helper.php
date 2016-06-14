<?php

/*
 * Генерация паролей
 * @param int $size длинна пароля
 * @param string $sets набор символов l - lower case, u - uppercase, d - digits, s - specials 
 */

function generate_password($size = 13, $sets = 'luds') {

    $str = '';

    if (strpos($sets, 'l') !== false)
        $str .= 'abcdefghjkmnpqrstuvwxyz';
    if (strpos($sets, 'u') !== false)
        $str .= 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if (strpos($sets, 'd') !== false)
        $str .= '23456789';
    if (strpos($sets, 's') !== false)
        $str .= '!@#$%&*?';

    //$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789.-+=_,!@$#*%<>[]{}";

    $size = (int) $size;

    if ($size < 1) {
        return '';
    }

    // Length of the string to take characters from
    $len = strlen($str);

    if ($len < 1) {
        return '';
    }

    $pw = '';

    for ($i = 1; $i <= $size; $i++) {
        $pw .= substr($str, random_int(0, $len - 1), 1);
    }

    return $pw;
}

if (!function_exists('random_bytes')) {

    function random_bytes($length) {
        $buffer = '';
        $valid = false;
        if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ($buffer) {
                $valid = true;
            }
        }
        if (!$valid && function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($bytes && true === $strong) {
                $buffer = $bytes;
                $valid = true;
            }
        }
        if (!$valid && @is_readable('/dev/urandom')) {
            $handle = fopen('/dev/urandom', 'r');
            $len = strlen($buffer);
            while ($len < $length) {
                $buffer .= fread($handle, $length - $len);
                $len = strlen($buffer);
            }
            fclose($handle);
            if ($len >= $length) {
                $valid = true;
            }
        }
        if (!$valid || strlen($buffer) < $length) {
            $len = strlen($buffer);
            for ($i = 0; $i < $length; $i++) {
                if ($i < $len) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }

        return $buffer;
    }

}

if (!function_exists('random_int')) {

    function random_int($min, $max) {
        $min = (int) $min;
        $max = (int) $max;
        $range = $max - $min;
        if ($range <= 0) {
            return $min;
        }
        $bits = (int) ceil(log($range, 2));
        $bytes = (int) max(ceil($bits / 8), 1);
        if ($bits === 63) {
            $mask = 0x7fffffffffffffff;
        } else {
            $mask = (int) (pow(2, $bits) - 1);
        }
        $num = 0;
        do {
            $num = hexdec(bin2hex(random_bytes($bytes))) & $mask;
        } while ($num > $range);

        return $num + $min;
    }

}