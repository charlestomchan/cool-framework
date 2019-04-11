<?php

namespace Cool\Http\Message;


use Cool\Foundation\Bean\AbstractObject;
use Cool\Support\JsonFormat;

/**
 * JSON 类

 */
class Json extends AbstractObject
{

    // 编码
    public static function encode($data)
    {
        // 不转义中文、斜杠
        return JsonFormat::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

}
