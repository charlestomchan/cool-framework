<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 19:47
 */

namespace Cool\Console;


use Cool\Foundation\Bean\AbstractObject;
use Cool\Support\Process;

/**
 * Class PidFileHandler
 * @package Cool\Console
 */
class PidFileHandler extends AbstractObject
{
    /**
     * PID文件
     * @var string
     */
    public $pidFile = '';

    /**
     * 写入
     * @return bool
     */
    public function write()
    {
        return file_put_contents($this->pidFile, Process::getPid(), LOCK_EX) ? true : false;
    }

    /**
     * 读取
     * @return bool|string
     */
    public function read()
    {
        if (!file_exists($this->pidFile)) {
            return false;
        }
        $pid = file_get_contents($this->pidFile);
        if (!is_numeric($pid) || !Process::kill($pid, 0)) {
            return false;
        }
        return $pid;
    }
}