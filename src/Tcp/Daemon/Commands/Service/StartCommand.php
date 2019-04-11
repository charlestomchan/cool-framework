<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:06
 */

namespace Cool\Tcp\Daemon\Commands\Service;

use Cool\Console\CommandLine\Flag;
use Cool\Tcp\Server\TcpServer;

/**
 * Class StartCommand
 * @package Cool\Tcp\Daemon\Commands\Service
 */
class StartCommand extends BaseCommand
{
    /**
     * 是否热更新
     * @var bool
     */
    public $update;

    /**
     * 是否守护
     * @var bool
     */
    public $daemon;

    /**
     * 初始化事件
     */
    public function onInitialize()
    {
        parent::onInitialize(); // TODO: Change the autogenerated stub
        // 获取参数
        $this->update = Flag::bool(['u', 'update'], false);
        $this->daemon = Flag::bool(['d', 'daemon'], false);
    }

    /**
     * 主函数
     */
    public function main()
    {
        // 获取服务信息
        $server = new TcpServer($this->config);
        $pid    = $this->getServicePid();
        if ($pid) {
            println(sprintf(self::IS_RUNNING, $pid));
            return;
        }
        // 启动服务
        if ($this->update) {
            $server->setting['max_request'] = 1;
        }
        $server->setting['daemonize'] = $this->daemon;
        $server->start();
    }

}