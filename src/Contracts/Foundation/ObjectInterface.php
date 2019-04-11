<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 15:41
 */

namespace Cool\Contracts\Foundation;

/**
 * Interface ObjectInterface
 * @package Cool\Contracts\Foundation
 */
interface ObjectInterface
{
    /**
     * ObjectInterface constructor.
     * @param array $config
     */
    public function __construct($config = []);

    /**
     *  析构
     */
    public function __destruct();

    /**
     *  构造事件
     */
    public function onConstruct();

    /**
     *  初始化事件
     */
    public function onInitialize();

    /**
     * 析构事件
     */
    public function onDestruct();

    /**
     *  使用依赖创建实例
     * @param null $name
     * @return $this
     */
    public static function newInstance($name = null);


}