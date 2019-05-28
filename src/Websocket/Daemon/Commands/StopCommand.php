<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/5
 * Time: 16:03
 */

namespace Cool\Websocket\Daemon\Commands;


use Cool\Support\Process;

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