<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:48
 */

namespace Cool\Contracts\Pool;

/**
 * Interface DialInterface
 * @package Cool\Contracts\Pool
 */
interface DialerInterface
{
    /**
     * 处理
     * @return mixed
     */
    public function dial();
}