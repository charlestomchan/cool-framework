<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 19:39
 */

namespace Cool\Support;

/**
 * Class Compute
 * @package Cool\Support
 */
class Compute
{

    // 是否为 CLI 模式
    public static function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    // 是否为 Win 系统
    public static function isWin()
    {
        return stripos(PHP_OS, 'WIN') !== false;
    }

    // 是否为 Mac 系统
    public static function isMac()
    {
        return stripos(PHP_OS, 'Darwin') !== false;
    }
}