<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:12
 */

namespace Cool\Tcp\Daemon\Commands;

/**
 * Class StatusCommand
 * @package Cool\Tcp\Daemon\Commands
 */
class StatusCommand extends BaseCommand
{
    /**
     * 主函数
     */
    public function main()
    {
        $pid = $this->getServicePid();
        if (!$pid) {
            println(self::NOT_RUNNING);
            return;
        }
        println(sprintf(self::IS_RUNNING, $pid));
    }
}