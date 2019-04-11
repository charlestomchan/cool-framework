<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 17:46
 */

namespace Cool\Contracts\Foundation;

/**
 * Interface ComponentInterface
 * @package Cool\Contracts\Foundation
 */
interface ComponentInterface
{
    /**
     * 协程模式值
     */
    const COROUTINE_MODE_NEW = 0;
    const COROUTINE_MODE_REFERENCE = 1;

    /**
     * 组件状态值
     */
    const STATUS_READY = 0;
    const STATUS_RUNNING = 1;

    /**
     * 获取组件状态
     * @return int
     */
    public function getStatus();

    /**
     * 设置组件状态
     * @param int $status
     */
    public function setStatus(int $status);

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize();

    /**
     * 后置处理事件
     */
    public function onAfterInitialize();

}