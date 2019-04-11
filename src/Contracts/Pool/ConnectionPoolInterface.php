<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:47
 */

namespace Cool\Contracts\Pool;

/**
 * Interface ConnectionPoolInterface
 * @package Cool\Contracts\Pool
 */
interface ConnectionPoolInterface
{

    /**
     * 获取连接
     * @return mixed
     */
    public function getConnection();

    /**
     * 释放连接
     * @param $connection
     */
    public function release($connection);

    /**
     * 丢弃连接
     * @param $connection
     * @return bool
     */
    public function discard($connection);

    /**
     * 获取连接池的统计信息
     * @return array
     */
    public function getStats();

}