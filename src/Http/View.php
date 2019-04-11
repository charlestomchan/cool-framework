<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:04
 */

namespace Cool\Http;

use Cool\Foundation\Exceptions\ViewException;
use Cool\Support\Str;

/**
 * Class View
 * @package Cool\Http
 */
class View
{

    /**
     * 标题
     * @var string
     */
    public $title;

    /**
     * 渲染视图
     * @param $__template__
     * @param $__data__
     * @return string
     */
    public function render($__template__, $__data__)
    {
        // 传入变量
        extract($__data__);
        // 生成视图
        $__filepath__ = \Cool::$app->getViewPath() . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $__template__) . '.php';
        if (!is_file($__filepath__)) {
            throw new ViewException("视图文件不存在：{$__filepath__}");
        }
        ob_start();
        include $__filepath__;
        return ob_get_clean();
    }

    /**
     * 获取视图前缀
     * @param $controller
     * @return string
     */
    public static function prefix($controller)
    {
        $prefix = str_replace([\Cool::$app->route->controllerNamespace . '\\', '\\', 'Controller'], ['', '.', ''], get_class($controller));
        $items  = [];
        foreach (explode('.', $prefix) as $item) {
            $items[] = Str::camelToSnake($item);
        }
        return implode('.', $items);
    }

}