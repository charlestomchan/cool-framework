<?php

namespace Cool\Http\Message;

use Cool\Foundation\Bean\AbstractObject;
use Cool\Support\XmlHelper;

/**
 * Xml类
 */
class Xml extends AbstractObject
{

    // 编码
    public function encode($data)
    {
        return XmlHelper::encode($data);
    }

}
