<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/19
 * Time: 19:35
 */

namespace Cool\Console;

use Cool\Console\CommandLine\Color;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Foundation\Exceptions\NotFoundException;
use Cool\Support\Compute;

/**
 * Class Error
 * @package Cool\Console
 */
class Error extends AbstractComponent
{
    /**
     * 错误级别
     * @var int
     */
    public $level = E_ALL;

    /**
     * 异常处理.
     * @param $e \RuntimeException | NotFoundException
     */
    public function handleException($e)
    {
        // 错误参数定义
        $errors = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'type' => get_class($e),
            'trace' => $e->getTraceAsString(),
        ];
        // 日志处理
        if (!($e instanceof NotFoundException)) {
            self::log($errors);
            return;
        }
        // 打印到屏幕
        println($errors['message']);
    }

    /**
     * 写入日志
     * @param $errors
     */
    protected static function log($errors)
    {
        // 构造消息
        $message = "{message}\n[code] {code} [type] {type}\n[file] {file} [line] {line}\n[trace] {trace}";
        if (!\Cool::$app->appDebug) {
            $message = "{message} [{code}] {type} in {file} line {line}";
        }
        // 写入
        $level = \Cool\Foundation\Error::getLevel($errors['code']);
        switch ($level) {
            case 'error':
                \Cool::$app->log->error($message, $errors);
                break;
            case 'warning':
                \Cool::$app->log->warning($message, $errors);
                break;
            case 'notice':
                \Cool::$app->log->notice($message, $errors);
                break;
        }
    }

}