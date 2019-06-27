<?php

namespace Cool\Http\Commands;

/**
 * Class StatusCommand
 * @package Cool\Http\Commands
 * @author charles <charlestomchan@gmail.com>
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
