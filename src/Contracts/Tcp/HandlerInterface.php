<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 17:10
 */

namespace Cool\Contracts\Tcp;

use Cool\Tcp\TcpConnection;

/**
 * Interface HandlerInterface
 * @package Cool\Contracts\Tcp
 */
interface HandlerInterface
{
    /**
     * 开启连接
     * @param TcpConnection $tcp
     */
    public function connect(TcpConnection $tcp);

    /**
     * 处理消息
     * @param TcpConnection $tcp
     * @param string $data
     */
    public function receive(TcpConnection $tcp, string $data);

    /**
     * 连接关闭
     * @param TcpConnection $tcp
     */
    public function close(TcpConnection $tcp);
}