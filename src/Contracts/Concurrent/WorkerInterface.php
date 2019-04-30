<?php

namespace Cool\Contracts\Concurrent;

/**
 * Interface WorkerInterface
 * @package Cool\Contracts\Concurrent
 */
interface WorkerInterface
{

    /**
     * 启动
     */
    public function start();

    /**
     * 停止
     */
    public function stop();

    /**
     * 处理
     * @param $data
     */
    public function handle($data);

}
