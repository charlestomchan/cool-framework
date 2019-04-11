<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 16:22
 */

namespace Cool\Database\Coroutine;


use Cool\Pool\ConnectionTrait;

class PDOConnection extends \Cool\Database\Persistent\PDOConnection
{
    use ConnectionTrait;
}