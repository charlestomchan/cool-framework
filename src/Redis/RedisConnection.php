<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 16:00
 */

namespace Cool\Redis;
use Cool\Redis\Base\AbstractRedisConnection;

class RedisConnection extends AbstractRedisConnection
{

    /**
     * 后置处理事件
     */
    public function onAfterInitialize()
    {
        parent::onAfterInitialize();
        // 关闭连接
        $this->disconnect();
    }

    /**
     * 析构事件
     */
    public function onDestruct()
    {
        parent::onDestruct();
        // 关闭连接
        $this->disconnect();
    }
}