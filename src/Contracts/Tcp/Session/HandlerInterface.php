<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:02
 */

namespace Cool\Contracts\Tcp\Session;

/**
 * Interface HandlerInterface
 * @package Cool\Contracts\Tcp\Session
 */
interface HandlerInterface
{
    /**
     * 获取
     * @param $fd
     * @param $key
     * @return mixed
     */
    public function get($fd, $key = null);

    /**
     * 设置
     * @param $fd
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($fd, $key, $value);

    /**
     * 删除
     * @param $fd
     * @param $key
     * @return bool
     */
    public function delete($fd, $key);

    /**
     * 清除
     * @param $fd
     * @return bool
     */
    public function clear($fd);

    /**
     * 判断是否存在
     * @param $fd
     * @param $key
     * @return bool
     */
    public function has($fd, $key);
}