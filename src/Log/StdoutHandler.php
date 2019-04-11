<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 10:11
 */

namespace Cool\Log;

use Cool\Console\Application;
use Cool\Console\CommandLine\Color;
use Cool\Contracts\Log\HandlerInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Support\Compute;

/**
 * Class StdoutHandler
 * @package Cool\Log
 */
class StdoutHandler extends AbstractComponent implements HandlerInterface
{
    /**
     * 写入日志
     * @param $level
     * @param $message
     * @return bool
     */
    public function write($level, $message)
    {
        // TODO: Implement write() method.
        // FastCGI 模式下不打印
        if (!Compute::isCli()) {
            return;
        }
        // win 系统普通打印
        if (Compute::isWin()) {
            echo $message;
            return true;
        }
        // 带颜色打印
        switch ($level) {
            case 'error':
                Color::new(Color::FG_RED)->print($message);
                break;
            case 'warning':
                Color::new(Color::FG_YELLOW)->print($message);
                break;
            case 'notice':
                Color::new(Color::FG_GREEN)->print($message);
                break;
            default:
                echo $message;
        }
        return true;
    }

}