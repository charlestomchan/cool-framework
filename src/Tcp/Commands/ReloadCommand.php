<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:11
 */

namespace Cool\Tcp\Commands;

use Cool\Support\Process;

/**
 * Class ReloadCommand
 * @package Cool\Tcp\Commands
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