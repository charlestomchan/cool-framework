<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/3/20
 * Time: 15:01
 */

namespace Cool\Http;


use Cool\Contracts\Foundation\ComponentInterface;
use Cool\Foundation\Component\AbstractComponent;
use Cool\Foundation\Exceptions\NotFoundException;
use Cool\Http\Message\Response\HttpResponse;

/**
 * Class Error
 * @package Cool\Http
 */
class Error extends AbstractComponent
{
    /**
     * 协程模式
     * @var int
     */
    const COROUTINE_MODE = ComponentInterface::COROUTINE_MODE_REFERENCE;

    /**
     * 格式值
     */
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    /**
     * 输出格式
     * @var string
     */
    public $format = self::FORMAT_HTML;

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
        $errors     = [
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
        $format                          = \Cool::$app->error->format;
        $tpl                             = [
            404 => "errors.{$format}.not_found",
            500 => "errors.{$format}.internal_server_error",
        ];
        $content                         = (new View())->render($tpl[$statusCode], $errors);
        \Cool::$app->response->statusCode = $statusCode;
        \Cool::$app->response->content    = $content;
        switch ($format) {
            case self::FORMAT_HTML:
                \Cool::$app->response->format = HttpResponse::FORMAT_HTML;
                break;
            case self::FORMAT_JSON:
                \Cool::$app->response->format = HttpResponse::FORMAT_JSON;
                break;
            case self::FORMAT_XML:
                \Cool::$app->response->format = HttpResponse::FORMAT_XML;
                break;
        }
        \Cool::$app->response->send();
    }

}