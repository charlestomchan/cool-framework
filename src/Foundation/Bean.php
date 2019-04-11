<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 16:28
 */

namespace Cool\Foundation;


use Cool\Foundation\Exceptions\ConfigException;

class Bean
{

    /**
     * 解析后的配置
     * @var array
     */
    protected static $_config;

    /**
     * 载入配置
     */
    protected static function load()
    {
        static $cache;
        $app = get_class(\Cool::$app);
        if (isset($cache[$app])) {
            self::$_config = $cache[$app];
            return;
        }
        $config = \Cool::$app->beans;
        $data   = [];
        foreach ($config as $item) {
            if (!isset($item['class'])) {
                continue;
            }
            if (isset($item['name'])) {
                $name = $item['name'];
            } else {
                $name = self::name($item['class']);
            }
            $data[$name] = $item;
        }
        self::$_config = $cache[$app] = $data;
    }

    /**
     * 获取配置
     * @param $bean
     * @return array
     */
    public static function config($name)
    {
        self::load();
        if (!isset(self::$_config[$name])) {
            if (self::isBase64($name)) {
                $name = base64_decode($name);
            }
            throw new ConfigException("Bean configuration not found: {$name}");
        }
        return self::$_config[$name];
    }

    /**
     * 创建实例
     * @param $name
     * @return mixed
     */
    public static function newInstance($name)
    {
        $bean       = Bean::config($name);
        $class      = $bean['class'];
        $properties = $bean['properties'] ?? [];
        return new $class($properties);
    }

    /**
     * 获取Bean名称
     * @param $class
     * @return string
     */
    public static function name($class)
    {
        return base64_encode($class);
    }

    /**
     * 判断是否为Base64
     * @param $str
     * @return bool
     */
    protected static function isBase64($str)
    {
        return $str == base64_encode(base64_decode($str)) ? true : false;
    }

}