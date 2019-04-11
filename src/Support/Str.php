<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:06
 */

namespace Cool\Support;

/**
 * Class Str
 * @package Cool\Support
 */
class Str
{
    // 蛇形命名转换为驼峰命名
    public static function snakeToCamel($name, $ucfirst = false)
    {
        $name = ucwords(str_replace(['_', '-'], ' ', $name));
        $name = str_replace(' ', '', lcfirst($name));
        return $ucfirst ? ucfirst($name) : $name;
    }

    // 驼峰命名转换为蛇形命名
    public static function camelToSnake($name, $separator = '_')
    {
        $name = preg_replace_callback('/([A-Z]{1})/', function ($matches) use ($separator) {
            return $separator . strtolower($matches[0]);
        }, $name);
        if (substr($name, 0, 1) == $separator) {
            return substr($name, 1);
        }
        return $name;
    }
    public static function random($length = 16)
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }
    // 获取随机字符
    public static function randomAlphanumeric($length)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $last  = 61;
        $str   = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars{mt_rand(0, $last)};
        }
        return $str;
    }

    // 获取随机字符
    public static function randomNumeric($length)
    {
        $chars = '1234567890';
        $last  = 9;
        $str   = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0) {
                $str .= $chars{mt_rand(0, $last - 1)};
            } else {
                $str .= $chars{mt_rand(0, $last)};
            }
        }
        return $str;
    }
}