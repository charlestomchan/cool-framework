<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/1
 * Time: 16:08
 */

namespace Cool\Contracts\Foundation;

/**
 * Interface MiddlewareInterface
 * @package Cool\Contracts\Http
 */
interface MiddlewareInterface
{

    /**
     * 处理
     * @param callable $callback
     * @param \Closure $nex
     * @return mixed
     */
    public function handle(callable $callback, \Closure $next);
}