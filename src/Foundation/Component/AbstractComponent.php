<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 19:36
 */

namespace Cool\Foundation\Component;

use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Bean\AbstractObject;

/**
 * Class AbstractComponent
 * @package Cool\Foundation\Component
 */
abstract class AbstractComponent extends AbstractObject implements ComponentInterface
{
    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_NEW;

    /**
     * 组件状态
     * @var int
     */
    private $_status = ComponentInterface::STATUS_READY;

    /**
     * 获取组件状态
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * 设置组件状态
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->_status = $status;
    }

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        $this->setStatus(ComponentInterface::STATUS_RUNNING);
    }

    /**
     * 后置处理事件
     */
    public function onAfterInitialize()
    {
        $this->setStatus(ComponentInterface::STATUS_READY);
    }

}