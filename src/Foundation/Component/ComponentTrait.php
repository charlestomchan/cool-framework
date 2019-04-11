<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 10:17
 */

namespace Cool\Foundation\Component;

use Cool\Contracts\Foundation\ComponentInterface;

/**
 * Trait ComponentTrait
 * @package Cool\Foundation\Component
 */
trait ComponentTrait
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