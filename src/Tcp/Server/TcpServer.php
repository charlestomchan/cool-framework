<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 19:07
 */

namespace Cool\Tcp\Server;


use Cool\Foundation\Bean\AbstractObject;
use Cool\Foundation\Coroutine;
use Cool\Support\Process;
use Cool\Tcp\Application;

class TcpServer extends AbstractObject
{
    /**
     * 主机
     * @var string
     */
    public $host = '127.0.0.1';

    /**
     * 端口
     * @var int
     */
    public $port = 9503;

    /**
     * 应用配置文件
     * @var string
     */
    public $configFile = '';

    /**
     * 运行参数
     * @var array
     */
    public $setting = [];
    /**
     * 额外监听端口
     * @var array
     */
    public $add_listener = [];
    /**
     * 服务名称
     * @var string
     */
    const SERVER_NAME = 'cool-tcpd';

    /**
     * 默认运行参数
     * @var array
     */
    protected $_setting = [
        // 开启协程
        'enable_coroutine' => true,
        // 主进程事件处理线程数
        'reactor_num' => 8,
        // 工作进程数
        'worker_num' => 8,
        // 任务进程数
        'task_worker_num' => 0,
        // PID 文件
        'pid_file' => '/var/run/cool-tcpd.pid',
        // 日志文件路径
        'log_file' => '/tmp/cool-tcpd.log',
        // 异步安全重启
        'reload_async' => true,
        // 退出等待时间
        'max_wait_time' => 60,
        // 开启后，PDO 协程多次 prepare 才不会有 40ms 延迟
        'open_tcp_nodelay' => true,
        // 进程的最大任务数
        'max_request' => 0,
    ];

    /**
     * 服务器
     * @var \Swoole\Server
     */
    protected $_server;

    /**
     * 启动服务
     * @return bool
     */
    public function start()
    {
        // 初始化
        $this->_server = new \Swoole\Server($this->host, $this->port);
        // 配置参数
        $this->_setting = $this->setting + $this->_setting;
        $this->_server->set($this->_setting);
        // 关闭内置协程
        $this->_server->set([
            'enable_coroutine' => false,
        ]);
        if ($this->add_listener) {
            $this->_server->addlistener($this->add_listener['host'], $this->add_listener['port'], $this->add_listener['type']);
        }
        // 绑定事件
        $this->_server->on(SwooleEvent::START, [$this, 'onStart']);
        $this->_server->on(SwooleEvent::MANAGER_START, [$this, 'onManagerStart']);
        $this->_server->on(SwooleEvent::WORKER_START, [$this, 'onWorkerStart']);
        $this->_server->on(SwooleEvent::CONNECT, [$this, 'onConnect']);
        $this->_server->on(SwooleEvent::RECEIVE, [$this, 'onReceive']);
        $this->_server->on(SwooleEvent::CLOSE, [$this, 'onClose']);

        if ($this->add_listener['type'] == SWOOLE_SOCK_UDP) {
            $this->_server->on(\Cool\Udp\Server\SwooleEvent::PACKET, [$this, 'onPacket']);
        }
        // 欢迎信息
        $this->welcome();
        // 启动
        return $this->_server->start();
    }

    /**
     * 主进程启动事件
     * @param \Swoole\Server $server
     */
    public function onStart(\Swoole\Server $server)
    {
        // 进程命名
        Process::setProcessTitle(static::SERVER_NAME . ": master {$this->host}:{$this->port}");
    }

    /**
     * 管理进程启动事件
     * @param \Swoole\Server $server
     */
    public function onManagerStart(\Swoole\Server $server)
    {
        // 进程命名
        Process::setProcessTitle(static::SERVER_NAME . ": manager");
    }

    /**
     * 工作进程启动事件
     * @param \Swoole\Server $server
     * @param int $workerId
     */
    public function onWorkerStart(\Swoole\Server $server, int $workerId)
    {
        // 进程命名
        if ($workerId < $server->setting['worker_num']) {
            Process::setProcessTitle(static::SERVER_NAME . ": worker #{$workerId}");
        } else {
            Process::setProcessTitle(static::SERVER_NAME . ": task #{$workerId}");
        }
        // 实例化App
        new Application(require $this->configFile);
    }

    /**
     * 连接事件
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(\Swoole\Server $server, int $fd, int $reactorId)
    {
        if ($this->_setting['enable_coroutine'] && Coroutine::id() == -1) {
            xgo(function () use ($server, $fd, $reactorId) {
                call_user_func([$this, 'onConnect'], $server, $fd, $reactorId);
            });
            return;
        }
        try {
            // 前置初始化
            \Cool::$app->tcp->beforeInitialize($server, $fd);
            // 处理消息
            \Cool::$app->runConnect(\Cool::$app->tcp);
        } catch (\Throwable $e) {
            \Cool::$app->error->handleException($e);
        }
        // 清扫组件容器
        if (!$this->_setting['enable_coroutine']) {
            \Cool::$app->cleanComponents();
        }
    }

    /**
     * 接收事件
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(\Swoole\Server $server, int $fd, int $reactorId, string $data)
    {
        if ($this->_setting['enable_coroutine'] && Coroutine::id() == -1) {
            xgo(function () use ($server, $fd, $reactorId, $data) {
                call_user_func([$this, 'onReceive'], $server, $fd, $reactorId, $data);
            });
            return;
        }
        try {
            // 前置初始化
            \Cool::$app->tcp->beforeInitialize($server, $fd);
            // 处理消息
            \Cool::$app->runReceive(\Cool::$app->tcp, $data);
        } catch (\Throwable $e) {
            \Cool::$app->error->handleException($e);
        }
        // 清扫组件容器
        if (!$this->_setting['enable_coroutine']) {
            \Cool::$app->cleanComponents();
        }
    }

    /**
     * 关闭事件
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        if ($this->_setting['enable_coroutine'] && Coroutine::id() == -1) {
            xgo(function () use ($server, $fd, $reactorId) {
                call_user_func([$this, 'onClose'], $server, $fd, $reactorId);
            });
            return;
        }
        try {
            // 前置初始化
            \Cool::$app->tcp->beforeInitialize($server, $fd);
            // 处理连接关闭
            \Cool::$app->runClose(\Cool::$app->tcp);
        } catch (\Throwable $e) {
            \Cool::$app->error->handleException($e);
        }
        // 清扫组件容器
        if (!$this->_setting['enable_coroutine']) {
            \Cool::$app->cleanComponents();
        }
    }

    /**
     * 监听数据事件
     * @param \Swoole\Server $server
     * @param string $data
     * @param array $clientInfo
     */
    public function onPacket(\Swoole\Server $server, string $data, array $clientInfo)
    {
        if ($this->_setting['enable_coroutine'] && Coroutine::id() == -1) {
            xgo(function () use ($server, $data, $clientInfo) {
                call_user_func([$this, 'onPacket'], $server, $data, $clientInfo);
            });
            return;
        }
        try {
            // 前置初始化
            \Cool::$app->tcp->beforeInitialize($server, -2);  //packet 没有fd
            // 处理消息
            \Cool::$app->runPacket(
                \Cool::$app->tcp,
                $data,
                $clientInfo
            );
        } catch (\Throwable $e) {
            \Cool::$app->error->handleException($e);
        }
        // 清扫组件容器
        if (!$this->_setting['enable_coroutine']) {
            \Cool::$app->cleanComponents();
        }
    }

    /**
     * 欢迎信息
     */
    protected function welcome()
    {
        $swooleVersion = swoole_version();
        $phpVersion = PHP_VERSION;
        echo <<<EOL
        
        CCCCCCCCCCCCC                                  lllllll 
     CCC::::::::::::C                                  l:::::l 
   CC:::::::::::::::C                                  l:::::l 
  C:::::CCCCCCCC::::C                                  l:::::l 
 C:::::C       CCCCCC   ooooooooooo      ooooooooooo    l::::l 
C:::::C               oo:::::::::::oo  oo:::::::::::oo  l::::l 
C:::::C              o:::::::::::::::oo:::::::::::::::o l::::l 
C:::::C              o:::::ooooo:::::oo:::::ooooo:::::o l::::l 
C:::::C              o::::o     o::::oo::::o     o::::o l::::l 
C:::::C              o::::o     o::::oo::::o     o::::o l::::l 
C:::::C              o::::o     o::::oo::::o     o::::o l::::l 
 C:::::C       CCCCCCo::::o     o::::oo::::o     o::::o l::::l 
  C:::::CCCCCCCC::::Co:::::ooooo:::::oo:::::ooooo:::::ol::::::l
   CC:::::::::::::::Co:::::::::::::::oo:::::::::::::::ol::::::l
     CCC::::::::::::C oo:::::::::::oo  oo:::::::::::oo l::::::l
        CCCCCCCCCCCCC   ooooooooooo      ooooooooooo   llllllll
        

EOL;
        println('Server         Name:      ' . static::SERVER_NAME);
        println('System         Name:      ' . strtolower(PHP_OS));
        println("PHP            Version:   {$phpVersion}");
        println("Swoole         Version:   {$swooleVersion}");
        println('Framework      Version:   ' . \Cool::$version);
        $this->_setting['max_request'] == 1 and println('Hot            Update:    enabled');
        $this->_setting['enable_coroutine'] and println('Coroutine      Mode:      enabled');
        println("Listen         Addr:      {$this->host}");
        println("Listen         Port:      {$this->port}");
        println('Reactor        Num:       ' . $this->_setting['reactor_num']);
        println('Worker         Num:       ' . $this->_setting['worker_num']);
        println("Configuration  File:      {$this->configFile}");
    }

}