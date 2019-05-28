<?php

namespace Cool\Udp\Daemon\Commands;

/**
 * Class StatusCommand
 * @package Cool\Udp\Daemon\Commands
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
