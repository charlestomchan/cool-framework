<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 9:55
 */

namespace Cool\Log;

use Cool\Contracts\Log\HandlerInterface;
use Cool\Foundation\Bean\AbstractObject;
use Cool\Support\FileSystem;

/**
 * Class FileHandler
 * @package Cool\Log
 */
class FileHandler extends AbstractObject implements HandlerInterface
{
    /**
     * 轮转规则
     */
    const ROTATE_HOUR = 1;
    const ROTATE_DAY = 2;
    const ROTATE_WEEKLY = 3;

    /**
     * 单文件
     * @var string
     */
    public $single = '';

    /**
     * 日志目录
     * @var string
     */
    public $dir = '';

    /**
     * 日志轮转类型
     * @var int
     */
    public $rotate = self::ROTATE_DAY;

    /**
     * 最大文件尺寸
     * @var int
     */
    public $maxFileSize = 0;

    /**
     * 写入日志
     * @param $level
     * @param $message
     * @return bool
     */
    public function write($level, $message)
    {
        $file = $this->getFile($level);
        if (!$file) {
            return false;
        }
        return error_log($message, 3, $file);
    }

    /**
     * 获取文件
     * @param $level
     * @return bool|string
     */
    protected function getFile($level)
    {
        // 没有文件信息
        if (!$this->single && !$this->dir) {
            return false;
        }
        // 单文件
        if ($this->single) {
            return $this->single;
        }
        // 生成文件名
        $logDir = $this->dir;
        if (!FileSystem::isAbsolute($logDir)) {
            $logDir = \Cool::$app->getRuntimePath() . DIRECTORY_SEPARATOR . $this->dir;
        }
        switch ($this->rotate) {
            case self::ROTATE_HOUR:
                $subDir     = date('Ymd');
                $timeFormat = date('YmdH');
                break;
            case self::ROTATE_DAY:
                $subDir     = date('Ym');
                $timeFormat = date('Ymd');
                break;
            case self::ROTATE_WEEKLY:
                $subDir     = date('Y');
                $timeFormat = date('YW');
                break;
            default:
                $subDir     = '';
                $timeFormat = '';
        }
        $filename = $logDir . ($subDir ? DIRECTORY_SEPARATOR . $subDir : '') . DIRECTORY_SEPARATOR . $level . ($timeFormat ? '_' . $timeFormat : '');
        $file     = "{$filename}.log";
        // 创建目录
        $dir = dirname($file);
        is_dir($dir) or mkdir($dir, 0777, true);
        // 尺寸轮转
        $number = 0;
        while (file_exists($file) && $this->maxFileSize > 0 && filesize($file) >= $this->maxFileSize) {
            $file = "{$filename}_" . ++$number . '.log';
        }
        // 返回
        return $file;
    }

}