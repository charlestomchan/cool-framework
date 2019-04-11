<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 17:14
 */

namespace Cool\Tcp;

use Cool\Foundation\Application as BaseApplication;
use Cool\Foundation\Traits\ComponentInitializeTrait;

/**
 * Class Application
 * @package Cool\Tcp
 */
class Application extends BaseApplication
{
    use ComponentInitializeTrait;

    /**
     * 执行连接
     * @param $tcp
     */
    public function runConnect($tcp)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->connect($tcp);
    }

    /**
     * 执行接收
     * @param $tcp
     * @param $data
     */
    public function runReceive($tcp, $data)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->receive($tcp, $data);
    }

    /**
     * 执行连接关闭
     * @param $tcp
     */
    public function runClose($tcp)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->close($tcp);
    }

}