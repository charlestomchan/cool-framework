<?php

namespace Cool\Udp\Daemon\Commands\Service;

use Cool\Support\Process;


/**
 * Class ReloadCommand
 * @package Cool\Udp\Daemon\Commands\Service
 */
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
