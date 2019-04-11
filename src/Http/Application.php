<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 14:58
 */

namespace Cool\Http;

use Cool\Foundation\Application as BaseApplication;
use Cool\Foundation\Middleware\MiddlewareHandler;
use Cool\Foundation\Traits\ComponentInitializeTrait;
use Cool\Support\FileSystem;

class Application extends BaseApplication
{
    use ComponentInitializeTrait;
    /**
     * 公开目录路径
     * @var string
     */
    public $publicPath = 'public';

    /**
     * 视图目录路径
     * @var string
     */
    public $viewPath = 'views';

    /**
     * 执行功能
     */
    public function run()
    {
        $method = \Cool::$app->request->server('request_method', 'GET');
        $action = \Cool::$app->request->server('path_info', '/');
        \Cool::$app->response->content = $this->runAction($method, $action);
        \Cool::$app->response->send();
    }

    /**
     * 执行功能并返回
     * @param $method
     * @param $action
     * @return mixed
     */
    public function runAction($method, $action)
    {
        $rule = "{$method} {$action}";
        list($callback, $middleware) = \Cool::$app->route->getActionContent($rule);
        // 通过中间件执行功能
        $handler = MiddlewareHandler::new($this->route->middlewareNamespace, $middleware);
        return $handler->run($callback, \Cool::$app->request, \Cool::$app->response);
    }

    /**
     * 获取公开目录路径
     * @return string
     */
    public function getPublicPath()
    {
        if (!FileSystem::isAbsolute($this->publicPath)) {
            if ($this->publicPath == '') {
                return $this->basePath;
            }
            return $this->basePath . DIRECTORY_SEPARATOR . $this->publicPath;
        }
        return $this->publicPath;
    }

    /**
     * 获取视图目录路径
     * @return string
     */
    public function getViewPath()
    {
        if (!FileSystem::isAbsolute($this->viewPath)) {
            return $this->getResourcePath($this->viewPath);
        }
        return $this->viewPath;
    }

    /**
     * 获取资源目录.
     *
     * @param  string  $path
     * @return string
     */
    public function getResourcePath($path = '')
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

}