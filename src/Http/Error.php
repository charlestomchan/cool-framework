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
use Cool\Support\JsonFormat;
use Cool\Support\XmlHelper;

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
            static::log($errors);
        }
        // 发送客户端
        static::send($errors);
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
        \Cool::$app->response->statusCode = $statusCode;
        switch (\Cool::$app->error->format) {
            case static::FORMAT_HTML:
                \Cool::$app->response->content = static::html($errors);
                \Cool::$app->response->format  = \Cool\Http\Message\Response\HttpResponse::FORMAT_HTML;
                break;
            case static::FORMAT_JSON:
                \Cool::$app->response->content = static::json($errors);
                \Cool::$app->response->format  = \Cool\Http\Message\Response\HttpResponse::FORMAT_JSON;
                break;
            case static::FORMAT_XML:
                \Cool::$app->response->content = static::xml($errors);
                \Cool::$app->response->format  = \Cool\Http\Message\Response\HttpResponse::FORMAT_XML;
                break;
        }
        \Cool::$app->response->send();
    }

    /**
     * 生成html
     * @param $errors
     * @return string
     */
    protected static function html($errors)
    {
        $tpl = [
            404 => "errors.not_found",
            500 => "errors.internal_server_error",
        ];
        return (new View())->render($tpl[$errors['status']], $errors);
    }

    /**
     * 生成json
     * @param $errors
     * @return string
     */
    protected static function json($errors)
    {
        // 转换trace格式
        if (isset($errors['trace'])) {
            $tmp = [];
            foreach (explode("\n", $errors['trace']) as $key => $item) {
                $tmp[strstr($item, ' ', true)] = trim(strstr($item, ' '));
            }
            $errors['trace'] = $tmp;
        }
        // 生成
        return JsonFormat::encode($errors, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 生成xml
     * @param $errors
     * @return string
     */
    protected static function xml($errors)
    {
        // 转换trace格式
        if (isset($errors['trace'])) {
            $tmp = [];
            foreach (explode("\n", $errors['trace']) as $key => $item) {
                $tmp['item' . substr(strstr($item, ' ', true), 1)] = trim(strstr($item, ' '));
            }
            $errors['trace'] = $tmp;
        }
        // 生成
        return XmlHelper::encode($errors);
    }

}