<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 9:27
 */

namespace Cool\Contracts\Log;

/**
 * Interface HandlerInterface
 * @package Cool\Contracts\Log
 */
interface HandlerInterface
{
    /**
     * 写入日志
     * @param $level
     * @param $message
     * @return mixed
     */
    public function write($level,$message);
}