<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 17:16
 */

namespace Cool\Tcp;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Foundation\Exceptions\NotFoundException;
use Cool\Support\JsonFormat;

/**
 * Class Error
 * @package Cool\Tcp
 */
class Error extends AbstractComponent
{

    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 错误级别
     * @var int
     */
    public $level = E_ALL;

    /**
     * 异常处理
     * @param $e
     */
    public function handleException($e)
    {
        // 错误参数定义
        $statusCode = $e instanceof NotFoundException ? 404 : 500;
        $errors = [
            'status'  => $statusCode,
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'type'    => get_class($e),
            'trace'   => $e->getTraceAsString(),
        ];
        // 日志处理
        if (!($e instanceof NotFoundException)) {
            self::log($errors);
        }
        // 发送客户端
        self::send($errors);
        // 关闭连接
        // self::close($errors);
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

    /**
     * 发送客户端
     * @param $errors
     */
    protected static function send($errors)
    {
        if (!\Cool::$app->isRunning('tcp')) {
            return;
        }
        $errors['trace'] = explode("\n", $errors['trace']);
        $statusCode = $errors['status'];
        if (!\Cool::$app->appDebug) {
            if ($statusCode == 404) {
                $errors = [
                    'status'  => 404,
                    'message' => $errors['message'],
                ];
            }
            if ($statusCode == 500) {
                $errors = [
                    'status'  => 500,
                    'message' => '服务器内部错误',
                ];
            }
        }
        $data = JsonFormat::encode($errors);
        \Cool::$app->tcp->send($data);
    }

    /**
     * 关闭连接
     * @param $errors
     */
    protected static function close($errors)
    {
        if (\Cool::$app->isRunning('tcp')) {
            \Cool::$app->tcp->disconnect();
        }
    }

}