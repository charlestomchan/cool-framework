<?php

namespace Cool\Udp;

use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Udp\Handler\UdpHandlerInterface;

/**
 * Class Registry
 * @package Cool\Udp
 */
class Registry extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理者
     * @var UdpHandlerInterface
     */
    public $handler;

    /**
     * 获取处理器
     * @return UdpHandlerInterface
     */
    public function getHandler()
    {
        if (!($this->handler instanceof UdpHandlerInterface)) {
            throw new \RuntimeException("{$handlerClass} type is not 'Cool\Udp\Handler\UdpHandlerInterface'");
        }
        return $this->handler;
    }

}
