<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 15:40
 */

namespace Cool\Contracts\Websocket;


use Cool\Http\Message\Request\HttpRequest ;
use Cool\Http\Message\Response\HttpResponse;
use Cool\Websocket\WebSocketConnection;

/**
 * Interface InterceptorInterface
 * @package Cool\Contracts\Websocket
 */
interface InterceptorInterface
{
    /**
     * 握手
     * @param WebSocketConnection $ws
     * @param HttpRequest $request
     * @param HttpResponse $response
     */
    public function handshake(WebSocketConnection $ws, HttpRequest $request, HttpResponse $response);

}