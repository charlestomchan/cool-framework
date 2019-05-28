<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 17:16
 */

namespace Cool\Tcp;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Contracts\Tcp\HandlerInterface;
use Cool\Foundation\Component\AbstractComponent;

class Registry extends AbstractComponent
{
    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理者
     * @var \Cool\Contracts\Tcp\HandlerInterface
     */
    public $handler;

    /**
     * 获取处理器
     * @return \Cool\Contracts\Tcp\HandlerInterface
     */
    public function getHandler()
    {
        if (!($this->handler instanceof HandlerInterface)) {
            throw new \RuntimeException("{$handlerClass} type is not 'Cool\Contracts\Tcp\HandlerInterface'");
        }
        return $this->handler;
    }

}