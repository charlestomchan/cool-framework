<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/5
 * Time: 16:04
 */

namespace Cool\Websocket\Commands;

use Cool\Support\Process;

class ReloadCommand extends BaseCommand
{
    /**
     * 主函数
     */
    public function main()
    {
        // 获取服务状态
        $pid = $this->getServicePid();
        if (!$pid) {
            println(self::NOT_RUNNING);
            return;
        }
        // 重启子进程
        Process::kill($pid, SIGUSR1);
        println(self::EXEC_SUCCESS);
    }
}