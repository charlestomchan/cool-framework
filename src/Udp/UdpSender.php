<?php

namespace Cool\Udp;

use Cool\Foundation\Component\AbstractComponent;
use Cool\Contracts\Foundation\ComponentInterface;

/**
 * Class UdpSender
 * @package Cool\Udp
 */
class UdpSender extends AbstractComponent
{

    /**
     * 服务
     * @var \Swoole\Server
     */
    public $server;

    /**
     * 前置初始化
     * @return void
     */
    public function beforeInitialize(\Swoole\Server $server)
    {
        $this->server = $server;
        // 设置组件状态
        $this->setStatus(ComponentInterface::STATUS_RUNNING);
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        // 移除设置组件状态
    }

    /**
     * 发送指定地址
     * @param string $ip
     * @param int $port
     * @param string $data
     * @return bool
     */
    public function sendTo(string $ip, int $port, string $data)
    {
        return $this->server->sendto($ip, $port, $data);
    }

}
