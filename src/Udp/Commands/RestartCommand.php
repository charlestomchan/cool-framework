<?php

namespace Cool\Udp\Commands;

use Cool\Support\Process;

/**
 * Class RestartCommand
 * @package Cool\Udp\Commands
 */
class RestartCommand extends StartCommand
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
        // 停止服务
        Process::kill($pid);
        while (Process::kill($pid, 0)) {
            // 等待进程退出
            usleep(100000);
        }
        // 启动服务
        parent::main();
    }

}
