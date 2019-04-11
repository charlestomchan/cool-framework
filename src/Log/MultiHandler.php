<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 10:06
 */

namespace Cool\Log;

use Cool\Contracts\Log\HandlerInterface;
use Cool\Foundation\Component\AbstractComponent;

/**
 * Class MultiHandler
 * @package Cool\Log
 */
class MultiHandler extends AbstractComponent implements HandlerInterface
{
    /**
     * 日志处理器集合
     * @var \Cool\Contracts\Log\HandlerInterface[]
     */
    public $handlers = [];

    /**
     * 写入日志
     * @param $level
     * @param $message
     * @return bool
     */
    public function write($level, $message)
    {
        // TODO: Implement write() method.
        foreach ($this->handlers as $handler) {
            /** @var HandlerInterface $handler */
            $handler->write($level, $message);
        }
        return true;
    }

}