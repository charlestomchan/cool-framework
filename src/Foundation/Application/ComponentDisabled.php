<?php

namespace Cool\Foundation\Application;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Exceptions\ComponentException;

class ComponentDisabled
{

    /**
     * @var ComponentInterface
     */
    public $_component;

    /**
     * @var string
     */
    public $_name;

    /**
     * ComponentBeforeInitialize constructor.
     * @param $component
     * @param $name
     */
    public function __construct($component, $name)
    {
        $this->_component = $component;
        $this->_name      = $name;
    }

    /**
     * 执行前置初始化
     * @return mixed
     */
    public function beforeInitialize()
    {
        $arguments = func_get_args();
        return call_user_func_array([$this->_component, 'beforeInitialize'], $arguments);
    }

    /**
     * 未初始化错误处理
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        throw new ComponentException("'{$this->_name}' component is no initialize, cannot be used. ");
    }

}
