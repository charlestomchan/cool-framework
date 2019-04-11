<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 15:03
 */

namespace Cool\Concurrent\CoroutinePool;


use Cool\Foundation\Bean\AbstractObject;
use Cool\Foundation\Coroutine;
use Cool\Foundation\Coroutine\Channel;


/**
 * Class Worker
 * @package Cool\Concurrent\CoroutinePool
 */
class Worker extends AbstractObject
{

    /**
     * 工作池
     * @var  \Cool\Foundation\Coroutine\Channel
     */
    public $workerPool;

    /**
     * 任务通道
     * @var  \Cool\Foundation\Coroutine\Channel
     */
    public $jobChannel;

    /**
     * 退出
     * @var \Cool\Foundation\Coroutine\Channel
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
                $job = $this->jobChannel->pop();
                if (!$job) {
                    return;
                }
                list($callback, $params) = $job;
                call_user_func_array($callback, $params);
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