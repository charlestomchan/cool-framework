<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/21
 * Time: 15:05
 */

namespace Cool\Concurrent\Sync;

use Cool\Foundation\Coroutine\Channel;

/**
 * Class WaitGroup
 * @package Cool\Concurrent\Sync
 */
class WaitGroup
{
    /**
     * @var int
     */
    protected $_count = 0;

    /**
     * @var \Cool\Foundation\Coroutine\Channel
     */
    protected $_chan;

    /**
     * 使用静态方法创建实例
     * @param mixed ...$args
     * @return $this
     */
    public static function new(...$args)
    {
        return new static(...$args);
    }

    /**
     * WaitGroup constructor.
     */
    public function __construct()
    {
        $this->_chan = new Channel();
    }

    /**
     * 增加
     * @param int $num
     */
    public function add($num = 1)
    {
        $this->_count += $num;
    }

    /**
     * 完成
     * @return bool
     */
    public function done()
    {
        return $this->_chan->push(true);
    }

    /**
     * 等待
     * @return bool
     */
    public function wait()
    {
        for ($i = 0; $i < $this->_count; $i++) {
            $this->_chan->pop();
        }
        return true;
    }

}