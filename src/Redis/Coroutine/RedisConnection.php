<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 16:06
 */

namespace Cool\Redis\Coroutine;

use Cool\Pool\ConnectionTrait;
class RedisConnection extends \Cool\Redis\Persistent\RedisConnection
{
    use ConnectionTrait;
}