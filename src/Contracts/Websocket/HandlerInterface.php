<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 15:38
 */

namespace Cool\Contracts\Websocket;

use Cool\Contracts\Http\Message\HttpRequestInterface as Request;
use Cool\Websocket\WebSocketConnection;
use Swoole\WebSocket\Frame;

/**
 * Interface HandlerInterface
 * @package Cool\Contracts\Websocket
 */
interface HandlerInterface
{
    /**
     * 开启连接
     * @param WebSocketConnection $ws
     * @param HttpRequest $request
     */
    public function open(WebSocketConnection $ws, HttpRequest $request);

    /**
     * 处理消息
     * @param WebSocketConnection $ws
     * @param Frame $frame
     */
    public function message(WebSocketConnection $ws, Frame $frame);

    /**
     * 连接关闭
     * @param WebSocketConnection $ws
     */
    public function close(WebSocketConnection $ws);
}