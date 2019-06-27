<?php

namespace Cool\Http\Commands;

use Cool\Support\Process;

/**
 * Class StopCommand
 * @package Cool\Http\Commands
 * @author charles <charlestomchan@gmail.com>
 */
class StopCommand extends BaseCommand
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
        println(self::EXEC_SUCCESS);
    }

}
