<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/5
 * Time: 16:03
 */

namespace Cool\Websocket\Daemon\Commands;


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