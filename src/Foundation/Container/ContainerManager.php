<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 17:41
 */

namespace Cool\Foundation\Container;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Bean;
use Cool\Foundation\Bean\AbstractObject;
use Cool\Foundation\Coroutine;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerManager
 * @package Cool\Foundation\Container
 */
class ContainerManager extends AbstractObject implements ContainerInterface
{
    /**
     * 组件配置
     * @var array
     */
    public $config = [];

    /**
     * 容器集合
     * @var array
     */
    protected $_containers = [];

    /**
     * 获取容器
     * @param $name
     * @return ComponentInterface
     */
    public function get($name)
    {
        $tid = $this->getTid($name);
        if (!isset($this->_containers[$tid])) {
            $this->_containers[$tid] = new Container([
                'manager' => $this,
            ]);
        }
        return $this->_containers[$tid]->get($name);
    }

    /**
     * 判断容器是否存在
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        $tid = $this->getTid($name);
        if (!isset($this->_containers[$tid])) {
            return false;
        }
        return $this->_containers[$tid]->has($name);
    }

    /**
     * 移除容器
     * 顶部协程id
     * @param $tid
     */
    public function delete($tid)
    {
        $this->_containers[$tid] = null;
        unset($this->_containers[$tid]);
    }

    /**
     * 获取顶部协程id
     * @param $name
     * @return int
     */
    protected function getTid($name)
    {
        $tid = Coroutine::tid();
        $mode = $this->getCoroutineMode($name);
        if ($mode === false) {
            $tid = -2;
        }
        if ($mode == ComponentInterface::COROUTINE_MODE_REFERENCE) {
            $tid = -1;
        }
        return $tid;
    }

    /**
     * 获取协程模式
     * @param $name
     * @return bool|int
     */
    protected function getCoroutineMode($name)
    {
        try {
            $bean = Bean::config($this->config[$name]['ref']);
            $class = $bean['class'];
            return $class::COROUTINE_MODE ?? false;
        } catch (\Throwable $e) {
            return false;
        }
    }


}