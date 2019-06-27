<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/22
 * Time: 16:21
 */

namespace Cool\Websocket;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Foundation\Exceptions\NotFoundException;
use Cool\Support\JsonFormat;
use Cool\Websocket\Frame\TextFrame;

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
     * @param $e \Exception
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
            static::log($errors);
        }
        // 发送客户端
       // static::send($errors);
        // 关闭连接
       // static::close($errors);
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
        if (!\Cool::$app->isRunning('ws')) {
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
        $frame = new TextFrame([
            'data' => JsonFormat::encode($errors),
        ]);
        \Cool::$app->ws->push($frame);
    }

    /**
     * 关闭连接
     * @param $errors
     */
    protected static function close($errors)
    {
        // 关闭握手
        if (\Cool::$app->isRunning('response')) {
            \Cool::$app->response->statusCode = $errors['status'];
            \Cool::$app->response->send();
        }
        // 关闭连接
        if (\Cool::$app->isRunning('ws')) {
            \Cool::$app->ws->disconnect();
        }
    }

}