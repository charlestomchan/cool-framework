<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/10
 * Time: 18:47
 */

namespace Cool\Concurrent\CoroutinePool;


use Cool\Foundation\Bean\AbstractObject;
use Cool\Foundation\Coroutine;
use Cool\Foundation\Coroutine\Channel;

class AbstractWorker extends AbstractObject
{
    /**
     * 工作池
     * @var Channel
     */
    public $workerPool;

    /**
     * 任务通道
     * @var Channel
     */
    public $jobChannel;

    /**
     * 退出
     * @var Channel
     */
    protected $_quit;

    /**
     * 初始化事件
     */
    public function onInitialize()
    {
        parent::onInitialize(); // TODO: Change the autogenerated stub
        // 初始化
        $this->jobChannel = new Channel();
        $this->_quit      = new Channel();
    }

    /**
     * 启动
     */
    public function start()
    {
        Coroutine::create(function () {
            while (true) {
                $this->workerPool->push($this->jobChannel);
                $data = $this->jobChannel->pop();
                if ($data === false) {
                    return;
                }
                $this->handle($data);
            }
        });
        Coroutine::create(function () {
            $this->_quit->pop();
            $this->jobChannel->close();
        });
    }

    /**
     * 停止
     */
    public function stop()
    {
        Coroutine::create(function () {
            $this->_quit->push(true);
        });
    }

}