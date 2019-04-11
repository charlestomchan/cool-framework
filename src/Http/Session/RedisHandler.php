<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/23
 * Time: 16:58
 */

namespace Cool\Http\Session;


use Cool\Contracts\Http\Seesion\HandlerInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Support\Str;

class RedisHandler extends AbstractComponent implements HandlerInterface
{
    /**
     * 连接池
     * @var \Cool\Contracts\Pool\ConnectionPoolInterface
     */
    public $pool;

    /**
     * 连接
     * @var \Cool\Contracts\Redis\RedisConnectionInterface
     */
    public $connection;

    /**
     * Key前缀
     * @var string
     */
    public $keyPrefix = 'SESSION:';

    /**
     * SessionID
     * @var string
     */
    protected $_sessionId = '';

    /**
     * SessionKey
     * @var string
     */
    protected $_key = '';

    /**
     * 初始化事件
     */
    public function onInitialize()
    {
        parent::onInitialize(); // TODO: Change the autogenerated stub
        // 从连接池获取连接
        if (isset($this->pool)) {
            $this->connection = $this->pool->getConnection();
        }
    }

    /**
     * 加载 SessionId
     * @param $name
     * @param $maxLifetime
     * @return bool
     */
    public function loadSessionId($name, $maxLifetime)
    {
        // 加载
        $sessionId = \Cool::$app->request->cookie($name);
        if (is_null($sessionId)) {
            return false;
        }
        $this->_sessionId = $sessionId;
        $this->_key       = $this->keyPrefix . $this->_sessionId;
        // 延长 session 有效期
        $this->connection->expire($this->_key, $maxLifetime);
        // 返回
        return true;
    }

    /**
     * 创建 SessionId
     * @param $sessionIdLength
     * @param $maxLifetime
     * @return bool
     */
    public function createSessionId($sessionIdLength, $maxLifetime)
    {
        // 创建
        do {
            $this->_sessionId = Str::randomAlphanumeric($sessionIdLength);
            $this->_key       = $this->keyPrefix . $this->_sessionId;
        } while ($this->connection->exists($this->_key));
        // 延长 session 有效期
        $this->connection->expire($this->_key, $maxLifetime);
        // 返回
        return true;
    }

    /**
     * 获取 SessionId
     * @return string
     */
    public function getSessionId()
    {
        return $this->_sessionId;
    }

    /**
     * 赋值
     * @param $key
     * @param $value
     * @param $name
     * @param $maxLifetime
     * @param $cookieExpires
     * @param $cookiePath
     * @param $cookieDomain
     * @param $cookieSecure
     * @param $cookieHttpOnly
     * @return bool
     */
    public function set($key, $value, $name, $maxLifetime, $cookieExpires, $cookiePath, $cookieDomain, $cookieSecure, $cookieHttpOnly)
    {
        $success = $this->connection->hmset($this->_key, [$key => serialize($value)]);
        $this->connection->expire($this->_key, $maxLifetime);
        if ($success) {
            \Cool::$app->response->setCookie(
                $name,
                $this->getSessionId(),
                $cookieExpires,
                $cookiePath,
                $cookieDomain,
                $cookieSecure,
                $cookieHttpOnly
            );
        }
        return $success ? true : false;
    }

    /**
     * 取值
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        if (is_null($key)) {
            $result = $this->connection->hgetall($this->_key);
            foreach ($result as $key => $item) {
                $result[$key] = unserialize($item);
            }
            return $result ?: [];
        }
        $value = $this->connection->hget($this->_key, $key);
        return $value === false ? null : unserialize($value);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $success = $this->connection->hdel($this->_key, $key);
        return $success ? true : false;
    }

    /**
     * 清除session
     * @return bool
     */
    public function clear()
    {
        $success = $this->connection->del($this->_key);
        return $success ? true : false;
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $exist = $this->connection->hexists($this->_key, $key);
        return $exist ? true : false;
    }

}