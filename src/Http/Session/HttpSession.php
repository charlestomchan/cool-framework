<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/23
 * Time: 16:56
 */

namespace Cool\Http\Session;


use Cool\Foundation\Component\AbstractComponent;

class HttpSession extends AbstractComponent
{
    /**
     * 处理者
     * @var RedisHandler
     */
    public $handler;

    /**
     * session名
     * @var string
     */
    public $name = 'session_id';

    /**
     * 生存时间
     * @var int
     */
    public $maxLifetime = 7200;

    /**
     * SessionID 长度
     * @var int
     */
    public $sessionIdLength = 26;

    /**
     * 过期时间
     * @var int
     */
    public $cookieExpires = 0;

    /**
     * 有效的服务器路径
     * @var string
     */
    public $cookiePath = '/';

    /**
     * 有效域名/子域名
     * @var string
     */
    public $cookieDomain = '';

    /**
     * 仅通过安全的 HTTPS 连接传给客户端
     * @var bool
     */
    public $cookieSecure = false;

    /**
     * 仅可通过 HTTP 协议访问
     * @var bool
     */
    public $cookieHttpOnly = false;

    /**
     * 前置处理事件
     */
    public function onBeforeInitialize()
    {
        parent::onBeforeInitialize();
        // 加载 SessionId
        if (!$this->handler->loadSessionId($this->name, $this->maxLifetime)) {
            // 创建 session_id
            $this->handler->createSessionId($this->sessionIdLength, $this->maxLifetime);
        }
    }

    /**
     * 获取SessionId
     * @return string
     */
    public function getSessionId()
    {
        return $this->handler->getSessionId();
    }

    /**
     * 创建SessionId
     * @return bool
     */
    public function createSessionId()
    {
        return $this->handler->createSessionId($this->sessionIdLength, $this->maxLifetime);
    }

    /**
     * 赋值
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return $this->handler->set(
            $key,
            $value,
            $this->name,
            $this->maxLifetime,
            $this->cookieExpires,
            $this->cookiePath,
            $this->cookieDomain,
            $this->cookieSecure,
            $this->cookieHttpOnly
        );
    }

    /**
     * 取值
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        return $this->handler->get($key);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return $this->handler->delete($key);
    }

    /**
     * 清除session
     * @return bool
     */
    public function clear()
    {
        return $this->handler->clear();
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->handler->has($key);
    }

}