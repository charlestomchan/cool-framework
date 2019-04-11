<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 17:12
 */

namespace Cool\Database\Pool;


use Cool\Contracts\Pool\DialInterface;

/**
 * Class Dial
 * @package Cool\Database\Pool
 */
class Dial implements DialInterface
{
    /**
     * 处理
     * @return \Cool\Database\Coroutine\PDOConnection
     */
    public function handle()
    {
        return \Cool\Database\Coroutine\PDOConnection::newInstance();
    }
}