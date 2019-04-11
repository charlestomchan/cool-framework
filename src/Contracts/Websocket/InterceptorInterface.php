<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 15:40
 */

namespace Cool\Contracts\Websocket;


use Cool\Http\Message\Request\HttpRequest as Request;
use Cool\Http\Message\Response\HttpResponse as Response;

/**
 * Interface InterceptorInterface
 * @package Cool\Contracts\Websocket
 */
interface InterceptorInterface
{
    /**
     * 握手
     * @param Request $request
     * @param Response $response
     */
    public function handshake(Request $request, Response $response);

}