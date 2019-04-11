<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 17:06
 */

namespace Cool\Foundation\Container;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Bean;
use Cool\Foundation\Bean\AbstractObject;
use Cool\Foundation\Exceptions\ComponentException;

/**
 * Class Container
 * @package Cool\Foundation\Container
 */
class Container extends AbstractObject
{
    /**
     * 组件配置
     * @var ContainerManager
     */
    public $manager;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $_instances = [];

    /**
     * 获取容器
     * @param $name
     * @return ComponentInterface
     */
    public function get($name)
    {
        $config = $this->manager->config;
        // 已加载
        if (isset($this->_instances[$name])) {
            return $this->_instances[$name];
        }
        // 未注册
        if (!isset($config[$name])) {
            throw new ComponentException("Did not register this component: {$name}");
        }
        // 创建组件
        $object = Bean::newInstance($config[$name]['ref']);
        // 组件效验
        if (!($object instanceof ComponentInterface)) {
            throw new ComponentException("This class is not a component: {$config[$name]['class']}");
        }
        // 装入容器
        $this->_instances[$name] = $object;
        // 返回
        return $this->_instances[$name];
    }

    /**
     * 判断容器是否存在
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->_instances[$name]);
    }

}