<?php

namespace Cool\Http\Message;

use Cool\Foundation\Bean\AbstractObject;
use Cool\Support\JsonFormat;

/**
 * JSONP 类
 */
class Jsonp extends AbstractObject
{

    // callback键名
    public $name = 'callback';

    // 编码
    public function encode($data)
    {
        // 不转义中文、斜杠
        $jsonString = JsonFormat::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $callback   = \Cool::$app->request->get($this->name);
        if (is_null($callback)) {
            return $jsonString;
        }
        return $callback . '(' . $jsonString . ')';
    }

}
