<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/23
 * Time: 16:44
 */

namespace Cool\Tcp\Session;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Component\AbstractComponent;

/**
 * Class TcpSession
 * @package Cool\Tcp\Session
 */
class TcpSession extends AbstractComponent
{
    /**
     * 协程模式
     * @var int
     */
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 处理器
     * @var \Cool\Contracts\Tcp\Session\HandlerInterface
     */
    public $handler;

    /**
     * 获取文件描述符
     * 为了实现可在任意子协程中使用，必须每次使用都提取当前的文件描述符
     * @return int
     */
    protected function getFileDescriptor()
    {
        // 设置fd
        if (\Cool::$app->isRunning('tcp')) {
            return \Cool::$app->tcp->fd;
        }
        if (\Cool::$app->isRunning('ws')) {
            return \Cool::$app->ws->fd;
        }
        if (\Cool::$app->isRunning('request')) {
            return \Cool::$app->request->fd;
        }
        return -1;
    }

    /**
     * 获取
     * @param null $key
     * @return mixed
     */
    public function get($key = null)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->get($fd, $key);
    }

    /**
     * 设置
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->set($fd, $key, $value);
    }

    /**
     * 删除
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->delete($fd, $key);
    }

    /**
     * 清除
     * @return bool
     */
    public function clear()
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->clear($fd);
    }

    /**
     * 判断是否存在
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $fd = $this->getFileDescriptor();
        return $this->handler->has($fd, $key);
    }
}