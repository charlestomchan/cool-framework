<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 16:02
 */

namespace Cool\Websocket;

use Cool\Foundation\Application as BaseApplication;
use Cool\Foundation\Traits\ComponentInitializeTrait;

/**
 * Class Application
 * @package Cool\Websocket
 */
class Application extends BaseApplication
{
    use ComponentInitializeTrait;

    /**
     * 执行握手
     * @param $request
     * @param $response
     */
    public function runHandshake($request, $response)
    {
        $interceptor = \Cool::$app->registry->getInterceptor();
        $interceptor->handshake($request, $response);
    }

    /**
     * 执行连接开启
     * @param $ws
     * @param $request
     */
    public function runOpen($ws, $request)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->open($ws, $request);
    }

    /**
     * 执行消息处理
     * @param $ws
     * @param $frame
     */
    public function runMessage($ws, $frame)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->message($ws, $frame);
    }

    /**
     * 执行连接关闭
     * @param $ws
     */
    public function runClose($ws)
    {
        $handler = \Cool::$app->registry->getHandler();
        $handler->close($ws);
    }

}